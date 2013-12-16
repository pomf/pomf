<?php
/**
 * PDO Connection data
 */
// PDO socket
// Or using socket would be even faster then 127.0.0.1 :3
define('POMF_DB_CONN', 'mysql:host=127.0.0.1;dbname=pomf');
// PDO user
define('POMF_DB_USER', 'pomf');
// PDO password
define('POMF_DB_PASS', '***');

/**
 * File stuff
 */
// Root location of files
define('POMF_FILES_ROOT', '/mnt/disk1/pomf/files/');
// How many times to retry when exists() before giving up
define('POMF_FILES_RETRIES', 30);
?>
