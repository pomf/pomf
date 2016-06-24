<?php
session_start();

/**
 * Handles POST uploads, generates filenames, moves files around and commits
 * uploaded metadata to database.
 */

require_once 'classes/Response.class.php';
require_once 'classes/UploadException.class.php';
require_once 'classes/UploadedFile.class.php';
require_once 'includes/database.inc.php';

/**
 * Generates a random name for the file, retrying until we get an unused one.
 *
 * @param UploadedFile $file
 *
 * @return string
 */
function generateName($file)
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
            throw new Exception(
                'Gave up trying to find an unused name',
                500
            ); // HTTP status code "500 Internal Server Error"
        }

        $chars = ID_CHARSET;
        $name = '';
        for ($i = 0; $i < $length; ++$i) {
            $name .= $chars[mt_rand(0, strlen($chars))];
        }

        // Add the extension to the file name
        if (isset($ext) && $ext !== '') {
            $name .= '.'.strip_tags($ext);
        }

        // Check if a file with the same name does already exist in the database
        $q = $db->prepare('SELECT COUNT(filename) FROM files WHERE filename = (:name)');
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
function uploadFile($file)
{
    global $db;
    global $FILTER_MODE;
    global $FILTER_MIME;

    // Handle file errors
    if ($file->error) {
        throw new UploadException($file->error);
    }

    // Check if mime type is blocked
    if (!empty($FILTER_MIME)) {
        if ($FILTER_MODE == true) { //whitelist mode
            if (!in_array($file->mime, $FILTER_MIME)) {
                throw new UploadException(UPLOAD_ERR_EXTENSION);
            }
        } else { //blacklist mode
            if (in_array($file->mime, $FILTER_MIME)) {
                throw new UploadException(UPLOAD_ERR_EXTENSION);
            }
        }
    }


    // Check if a file with the same hash and size (a file which is the same)
    // does already exist in the database; if it does, return the proper link
    // and data. PHP deletes the temporary file just uploaded automatically.
    $q = $db->prepare('SELECT filename, COUNT(*) AS count FROM files WHERE hash = (:hash) '.
                      'AND size = (:size)');
    $q->bindValue(':hash', $file->getSha1(), PDO::PARAM_STR);
    $q->bindValue(':size', $file->size, PDO::PARAM_INT);
    $q->execute();
    $result = $q->fetch();
    if ($result['count'] > 0) {
        return array(
            'hash' => $file->getSha1(),
            'name' => $file->name,
            'url' => POMF_URL.$result['filename'],
            'size' => $file->size,
        );
    }

    // Generate a name for the file
    $newname = generateName($file);

    // Store the file's full file path in memory
    $uploadFile = POMF_FILES_ROOT . $newname;

    // Attempt to move it to the static directory
    if (!move_uploaded_file($file->tempfile, $uploadFile)) {
        throw new Exception(
            'Failed to move file to destination',
            500
        ); // HTTP status code "500 Internal Server Error"
    }

    // Need to change permissions for the new file to make it world readable
    if (!chmod($uploadFile, 0644)) {
        throw new Exception(
            'Failed to change file permissions',
            500
        ); // HTTP status code "500 Internal Server Error"
    }

    // Add it to the database
    // 'expire' support is deprecated since version 2.1.0. It may be
    // removed in a future release.
    if (empty($_SESSION['id'])) {
        // Query if user is NOT logged in
        $q = $db->prepare('INSERT INTO files (hash, originalname, filename, size, date, ' .
                    'expire, delid) VALUES (:hash, :orig, :name, :size, :date, ' .
                        ':exp, :del)');
    } else {
        // Query if user is logged in (insert user id together with other data)
        $q = $db->prepare('INSERT INTO files (hash, originalname, filename, size, date, ' .
                    'expire, delid, user) VALUES (:hash, :orig, :name, :size, :date, ' .
                        ':exp, :del, :user)');
        $q->bindValue(':user', $_SESSION['id'], PDO::PARAM_INT);
    }

    // Common parameters binding
    $q->bindValue(':hash', $file->getSha1(), PDO::PARAM_STR);
    $q->bindValue(':orig', strip_tags($file->name), PDO::PARAM_STR);
    $q->bindValue(':name', $newname, PDO::PARAM_STR);
    $q->bindValue(':size', $file->size, PDO::PARAM_INT);
    $q->bindValue(':date', date('Y-m-d'), PDO::PARAM_STR);
    $q->bindValue(':exp', null, PDO::PARAM_STR);
    $q->bindValue(':del', sha1($file->tempfile), PDO::PARAM_STR);
    $q->execute();

    return array(
        'hash' => $file->getSha1(),
        'name' => $file->name,
        'url' => POMF_URL.$newname,
        'size' => $file->size,
    );
}

/**
 * Reorder files array by file.
 *
 * @param  $_FILES
 *
 * @return array
 */
function diverseArray($files)
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
    $files = diverseArray($files);

    foreach ($files as $file) {
        $f = new UploadedFile();
        $f->name = $file['name'];
        $f->mime = $file['type'];
        $f->size = $file['size'];
        $f->tempfile = $file['tmp_name'];
        $f->error = $file['error'];
        // 'expire' doesn't exist neither in $_FILES nor in UploadedFile;
        // it was never implemented and has been deprecated since version 2.1.0.
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
            $res[] = uploadFile($upload);
        }
        $response->send($res);
    } catch (Exception $e) {
        $response->error($e->getCode(), $e->getMessage());
    }
} else {
    $response->error(400, 'No input file(s)');
}
