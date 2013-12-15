<?php
//This file will include all settings

// PDO connection
define('db_conn', 'mysql:unix_socket=/var/run/mysqld/mysqld.sock;dbname=');
// PDO Database
define('db_name', 'pomf')
// PDO user
define('db_user', 'xxx');
// PDO password
define('db_pass', 'xxx');

// Install (if making an installer sometime?)
//define('install', '0');
// Maintenance
define('maintenance', '0');
// Registration open/closed
define('reg', '1');

// Bcrypt hash settings
define('bsettings', array('cost' => 11));
?>