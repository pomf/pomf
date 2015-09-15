<?php

// PDO socket
// mysql.sock path can be different from /tmp/mysql.sock, see /etc/my.cnf
define('POMF_DB_CONN', 'mysql:unix_socket=/tmp/mysql.sock;dbname=pomf');
// MySQL user and password
define('POMF_DB_USER', 'pomf');
define('POMF_DB_PASS', '***');

// Root location of files
define('POMF_FILES_ROOT', '/mnt/pantsu/http/files/');
// Maximum number of iterations while generating a new filename
define('POMF_FILES_RETRIES', 15);
// Number of random characters to use in a new filename
define('POMF_FILES_LENGTH', 6);
// URL to prepend to output (include trailing slash)
define('POMF_URL', 'https://i.pantsu.cat/');

$doubledots = array_map('strrev', array(
    'tar.gz',
    'tar.bz',
    'tar.bz2',
    'tar.xz',
    'user.js',
));
