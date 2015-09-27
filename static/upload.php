<?php

/**
 * Copyright (c) 2013, 2014 Peter Lejeck <peter.lejeck@gmail.com>
 * Copyright (c) 2013, 2014, 2015 Eric Johansson <neku@pomf.se>
 * Copyright (c) 2015 cenci0 <alchimist94@gmail.com>
 * Copyright (c) 2015 the Pantsu.cat developers <hostmaster@pantsu.cat>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

// Check if we can compress our output; if we can, we'll do it
if (ini_get('zlib.output_compression') !== 'Off'
    && isset($_SERVER['HTTP_ACCEPT_ENCODING'])
    && strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false) {
    ob_start('ob_gzhandler');
}

include_once 'classes/Response.class.php';
include_once 'classes/UploadException.class.php';
include_once 'classes/UploadedFile.class.php';
include_once 'includes/database.inc.php';

/**
 * Generates a random name for the file, retrying until we get an unused one.
 *
 * @param UploadedFile $file
 *
 * @return string
 */
function generate_name($file)
{
    global $db;
    global $doubledots;

    // We start at N retries, and --N until we give up
    $tries = POMF_FILES_RETRIES;
    $length = POMF_FILES_LENGTH;
    $ext = pathinfo($file->name, PATHINFO_EXTENSION);

    // Check if extension is a double-dot extension and, if true, override $ext
    $revname = strrev($file->name);
    foreach ($doubledots as $ddot) {
        if (stripos($revname, $ddot) === 0) {
            $ext = strrev($ddot);
        }
    }

    do {
        // Iterate until we reach the maximum number of retries
        if ($tries-- === 0) {
            throw new Exception('Gave up trying to find an unused name', 500);
        }

        $chars = range('a', 'z');
        $name = '';
        for ($i = 0; $i < $length; ++$i) {
            $name .= $chars[array_rand($chars)];
        }

        // Add the extension to the file name
        if (isset($ext) && $ext !== '') {
            $name .= '.'.strip_tags($ext);
        }

        // Check if a file with the same name does already exist in the database
        $q = $db->prepare('SELECT COUNT(name) FROM pomf WHERE name = (:name)');
        $q->bindValue(':name', $name, PDO::PARAM_STR);
        $q->execute();
        $result = $q->fetchColumn();
    // If it does, generate a new name
    } while ($result > 0);

    return $name;
}

/**
 * Handles the uploading and db entry for a file.
 *
 * @param UploadedFile $file
 *
 * @return array
 */
function upload_file($file)
{
    global $db;

    // Handle file errors
    if ($file->error) {
        throw new UploadException($file->error);
    }

    // Check if a file with the same hash and size (a file which is the same) does already exist in
    // the database; if it does, delete the file just uploaded and return the proper link and data.
    $q = $db->prepare('SELECT filename, COUNT(*) AS count FROM files WHERE hash = (:hash) '.
                      'AND size = (:size)');
    $q->bindValue(':hash', $file->get_sha1(), PDO::PARAM_STR);
    $q->bindValue(':size', $file->size,       PDO::PARAM_INT);
    $q->execute();
    $result = $q->fetch();
    if ($result['count'] > 0) {
        unlink($file->tempfile);

        return array(
            'hash' => $file->get_sha1(),
            'name' => $file->name,
            'url' => POMF_URL.$result['filename'],
            'size' => $file->size,
        );
    }

    // Generate a name for the file
    $newname = generate_name($file);

    // Attempt to move it to the static directory
    if (move_uploaded_file($file->tempfile, POMF_FILES_ROOT.$newname)) {
        // Need to change permissions for the new file to make it world readable
        if (chmod(POMF_FILES_ROOT.$newname, 0644)) {
            // Add it to the database
            $q = $db->prepare('INSERT INTO files (hash, originalname, filename, size, date, '.
                              'expire, delid) VALUES (:hash, :orig, :name, :size, :date, '.
                              ':exp, :del)');

            // Common parameters binding
            $q->bindValue(':hash', $file->get_sha1(),       PDO::PARAM_STR);
            $q->bindValue(':orig', strip_tags($file->name), PDO::PARAM_STR);
            $q->bindValue(':name', $newname,                PDO::PARAM_STR);
            $q->bindValue(':size', $file->size,             PDO::PARAM_INT);
            $q->bindValue(':date', date('Y-m-d'),           PDO::PARAM_STR);
            $q->bindValue(':exp',  null,                    PDO::PARAM_STR);
            $q->bindValue(':del',  sha1($file->tempfile),   PDO::PARAM_STR);
            $q->execute();

            return array(
                'hash' => $file->get_sha1(),
                'name' => $file->name,
                'url' => POMF_URL.$newname,
                'size' => $file->size,
            );
        } else {
            throw new Exception('Failed to change file permissions', 500);
        }
    } else {
        throw new Exception('Failed to move file to destination', 500);
    }
}

/**
 * Reorder files array by file.
 *
 * @param  $_FILES
 *
 * @return array
 */
function diverse_array($files)
{
    $result = array();

    foreach ($files as $key1 => $value1) {
        foreach ($value1 as $key2 => $value2) {
            $result[$key2][$key1] = $value2;
        }
    }

    return $result;
}

/**
 * Reorganize the $_FILES array into something saner.
 *
 * @param  $_FILES
 *
 * @return array
 */
function refiles($files)
{
    $result = array();
    $files = diverse_array($files);

    foreach ($files as $file) {
        $f = new UploadedFile();
        $f->name = $file['name'];
        $f->mime = $file['type'];
        $f->size = $file['size'];
        $f->tempfile = $file['tmp_name'];
        $f->error = $file['error'];
        // 'expire' doesn't exist neither in $_FILES nor in UploadedFile;
        // commented out for future implementation
        //$f->expire   = $file['expire'];
        $result[] = $f;
    }

    return $result;
}

$type = isset($_GET['output']) ? $_GET['output'] : 'json';
$response = new Response($type);

if (isset($_FILES['files'])) {
    $uploads = refiles($_FILES['files']);

    try {
        foreach ($uploads as $upload) {
            $res[] = upload_file($upload);
        }
        $response->send($res);
    } catch (Exception $e) {
        $response->error($e->getCode(), $e->getMessage());
    }
} else {
    $response->error(400, 'No input file(s)');
}
