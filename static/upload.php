<?php
session_start();
include_once 'classes/UploadedFile.class.php';
//include_once 'includes/database.inc.php'; Included in database.inc.php
include_once 'includes/database.inc.php';
/**
 * Generates a name for the file, retrying until we get an unused one 
 *
 * @param UploadedFile $file
 * @return string
 */
function generate_name ($file) {
	global $doubledots;
	// We start at N retries, and --N until we give up
	$tries = POMF_FILES_RETRIES;
	// We rip out the extension using pathinfo
	$ext = pathinfo($file->name, PATHINFO_EXTENSION);
	// And if we realize it's actually a doubledot
	// we just override $ext
	$revname = strrev($file->name);
	foreach ($doubledots as $ddot) {
		if (stripos($revname, $ddot) === 0) {
			$ext = strrev($ddot);
		}
	}
	do {
		// If we run out of tries, throw an exception.  Should be caught and JSONified.
		if ($tries-- == 0) throw new Exception('Gave up trying to find an unused name');
		//Random filename generator
		$newname='';
		$startNumber = ord("a");
		$endNumber = ord("z");
		for ($i = 0; $i < 6; $i++) {
			$newname .= chr(mt_rand($startNumber, $endNumber));
		}
		// To add a dot or not after a file which has no extension
		if ($ext != '') $newname .= '.' . strip_tags($ext);
	} while (file_exists(POMF_FILES_ROOT . $newname)); // TODO: check the database instead?
	return $newname;
}
/**
 * Handles the uploading and db entry for a file
 *
 * @param UploadedFile $file
 * @return array
 */
function upload_file ($file) {
	global $db;
	// If the file has an error attached, we just throw it as an exception.
	if ($file->error) throw new Exception($file->error);
	// Check if we have a file with that hash in the db
	if(empty($_SESSION['id'])){
	$q = $db->prepare("SELECT hash, filename, size FROM files WHERE hash = (:hash) AND user = (:user)");
	$q->bindValue('hash', $file->get_sha1());
	$q->bindValue('user', '0');
	$q->execute();
	$result = $q->fetch();
	// If we found a file with the same checksums, then we can assume it's a dupe
	// so we don't bother with it, and just unlink (delete) the tmpfile and return
	// the previous data.
	if ($result['hash'] === $file->get_sha1()) {
		unlink($file->tempfile);
		return array(
			'hash' => $result['hash'],
			'name' => $file->name,
			'url' => $result['filename'],
			'size' => $result['size']
		);
	} else {
		// Generate a name for the file
		$newname = generate_name($file);
		// Attempt to move it to the static directory
		if (move_uploaded_file($file->tempfile, POMF_FILES_ROOT . $newname)) {
			// Add it to the database
			$q = $db->prepare('INSERT INTO files (hash, orginalname, filename, size, date, expire, delid)' .
			                  'VALUES (:hash, :orig, :name, :size, :date, :expires, :delid)');
			$q->bindValue(':hash', $file->get_sha1());
			$q->bindValue(':orig', strip_tags($file->name));
			$q->bindValue(':name', $newname);
			$q->bindValue(':size', $file->size);
			$q->bindValue(':date', date('Y-m-d'));
			$q->bindValue(':expires', null);
			$q->bindValue(':delid', sha1($file->tempfile));
			$q->execute();
			return array(
				'hash' => $file->get_sha1(),
				'name' => $file->name,
				'url' => $newname,
				'size' => $file->size
			);
		} else {
			throw new Exception('Failed to move file to destination');
		}
	}
}else{//if session exists
        $q = $db->prepare("SELECT hash, filename, size FROM files WHERE hash = (:hash) AND user = (:user)");
        $q->bindValue('hash', $file->get_sha1());
	$q->bindValue('user', $_SESSION['id']);
        $q->execute();
        $result = $q->fetch();
        // If we found a file with the same checksums, then we can assume it's a dupe
        // so we don't bother with it, and just unlink (delete) the tmpfile and return
        // the previous data.
        if ($result['hash'] === $file->get_sha1()) {
                unlink($file->tempfile);
                return array(
                        'hash' => $result['hash'],
                        'name' => $file->name,
                        'url' => $result['filename'],
                        'size' => $result['size']
                );
        } else {
                // Generate a name for the file
                $newname = generate_name($file);
                // Attempt to move it to the static directory
                if (move_uploaded_file($file->tempfile, POMF_FILES_ROOT . $newname)) {
                        // Add it to the database
                        $q = $db->prepare('INSERT INTO files (hash, orginalname, filename, size, date, expire, delid, user)' .
                                          'VALUES (:hash, :orig, :name, :size, :date, :expires, :delid, :user)');
                        $q->bindValue(':hash', $file->get_sha1());
                        $q->bindValue(':orig', strip_tags($file->name));
                        $q->bindValue(':name', $newname);
                        $q->bindValue(':size', $file->size);
                        $q->bindValue(':date', date('Y-m-d'));
                        $q->bindValue(':expires', null);
                        $q->bindValue(':delid', sha1($file->tempfile));
                      	$q->bindValue(':user', $_SESSION['id']);
			$q->execute();
                        return array(
                                'hash' => $file->get_sha1(),
                                'name' => $file->name,
                                'url' => $newname,
                                'size' => $file->size
                        );
                } else {
                        throw new Exception('Failed to move file to destination');
                }
        }
}
}
/**
 * Reorganize the $_FILES array into something saner
 *
 * @param $_FILES
 */
function refiles ($files) {
	$out = array();
	for ($i = 0, $n = count($files['name']); $i < $n; ++$i) {
		// We create a new UploadedFile instance
		$file = new UploadedFile();
		// And fill it with our shit
		$file->name = $files['name'][$i];
		$file->mime = $files['type'][$i];
		$file->tempfile = $files['tmp_name'][$i];
		$file->error = $files['error'][$i];
		$file->size = $files['size'][$i];
		$file->expire = $files['expire'][$i];
		$out[] = $file;
	}
	return $out;
}
/**
 * Give a response that gyazo understands
 */
function respond_gyazo ($code, $files) {
	if ($files instanceof Exception) {
		echo "ERROR: " . $files->getMessage();
	} else {
		echo 'http://a.pomf.se/' . $files[0]['url'];
	}
}
/**
 * Responds to a request in CSV form.
 */
function respond_csv ($code, $files) {
	if ($files instanceof Exception) {
		echo "error\n";
		echo $files->getMessage() . "\n";
	} else {
		echo "name,url,hash,size\n";
		foreach ($files as $file) {
			echo "${file['name']},${file['url']},${file['hash']},${file['size']}\n";
		}
	}
}
/**
 * Responds to a request in JSON form.
 */
function respond_json ($code, $files) {
	// Now we send the response based on the type
	if ($files instanceof Exception) {
		// If it's an Exception, we put the message in the error field
		echo json_encode(array(
			'success' => false,
			'error' => $files->getMessage()
		));
	} elseif (is_array($files)) {
		echo json_encode(array(
			'success' => true,
			'error' => null,
			'files' => $files
		));
	}
}
/**
 * Determines the proper response function based on $_GET['output]
 */
function respond ($code, $files = null) {
	if (is_int($code)) { // If the code is an integer, we assume it's a response code
		http_response_code($code);
	} else { // Otherwise we just use the default and shift
		http_response_code(200);
		$files = $code;
	}
	$format = array_key_exists('output', $_GET) ? $_GET['output'] : 'json';
	switch ($format) {
		case 'gyazo':
			respond_gyazo($code, $files);
			break;
		case 'csv':
			respond_csv($code, $files);
			break;
		case 'json':
			respond_json($code, $files);
			break;
	}
}
if (isset($_FILES['files'])) {
	try {
		$uploads = refiles($_FILES['files']);
		foreach ($uploads as $upload) {
			$out[] = upload_file($upload);
		}
		respond($out);
	} catch (Exception $e) {
		respond(500, $e);
	}
} else {
	respond(500, new Exception('Nigga what you doin\' here?'));
}
?>
