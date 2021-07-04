<?php

/**
 * User configurable settings for Pomf.
 *
 * @copyright Copyright (c) 2013, 2014 Peter Lejeck <peter.lejeck@gmail.com>
 * @copyright Copyright (c) 2015 cenci0 <alchimist94@gmail.com>
 * @copyright Copyright (c) 2015, 2016, 2017 the Pantsu.cat developers <hostmaster@pantsu.cat>
 * <hostmaster@pantsu.cat>
 * @copyright Copyright (c) 2019, 2020, 2021 Eric Johansson (nekunekus) <neku@pomf.se>
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

/**
 * PDO connection socket
 *
 * Database connection to use for communication. Currently, MySQL is the only
 * DSN prefix supported.
 *
 * @see http://php.net/manual/en/ref.pdo-mysql.connection.php PHP manual for
 * PDO_MYSQL DSN.
 * @param string POMF_DB_CONN DSN:host|unix_socket=hostname|path;dbname=database
 */
//define('POMF_DB_CONN', 'mysql:unix_socket=/tmp/mysql.sock;dbname=pomf');
define('POMF_DB_CONN', 'sqlite:/path/to/db/pomf.sq3');

/**
 * PDO database login credentials
 */

/** @param string POMF_DB_NAME Database username */
define('POMF_DB_USER', '');
/** @param string POMF_DB_PASS Database password */
define('POMF_DB_PASS', '');

/**
 * File system location where to store uploaded files
 *
 * @param string Path to directory with trailing delimiter
 */
define('POMF_FILES_ROOT', '/path/to/your/uploaded/files/');

/**
 * Maximum number of iterations while generating a new filename
 *
 * Pomf uses an algorithm to generate random filenames. Sometimes a file may
 * exist under a randomly generated filename, so we count tries and keep trying.
 * If this value is exceeded, we give up trying to generate a new filename.
 *
 * @param int POMF_FILES_RETRIES Number of attempts to retry
 */
define('POMF_FILES_RETRIES', 15);

/** 
 * @param boolean Log IP of uploads 
 * */
define('LOG_IP', false);

/** 
 * @param boolean blacklist DB
 * ONLY ENABLE THIS IS YOU ARE USING THE LATEST DB SCHEMA!
 */
define('BLACKLIST_DB', false);

/**
 * The length of generated filename (without file extension)
 *
 * @param int POMF_FILES_LENGTH Number of random alphabetical ASCII characters
 * to use
 */
define('POMF_FILES_LENGTH', 8);

/**
 * URI to prepend to links for uploaded files
 *
 * @param string POMF_URL URI with trailing delimiter
 */
define('POMF_URL', 'https://your.file.serving.domain/');

/**
 * URI for filename generation
 *
 * @param string characters to be used in generateName()
 */
define('ID_CHARSET', 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');

/**
 * @param string[] Filtered mime types and extensions
 */
define('CONFIG_BLOCKED_EXTENSIONS', serialize(['exe', 'scr', 'com', 'vbs', 'bat', 'cmd', 'htm', 'html', 'jar', 'msi', 'apk', 'phtml', 'svg']));
define('CONFIG_BLOCKED_MIME', serialize(['application/msword', 'text/html', 'application/x-dosexec', 'application/java', 'application/java-archive', 'application/x-executable', 'application/x-mach-binary', 'image/svg+xml']));

/**
 * Whitelist or blacklist mode
 * @param boolean blacklist (false) | whitelist (true)
 */
define('CONFIG_FILTER_MODE', false);

/**
 * Double dot file extensions
 *
 * Pomf keeps the last file extension for the uploaded file. In other words, an
 * uploaded file with `.tar.gz` extension will be given a random filename which
 * ends in `.gz` unless configured here to ignore discards for `.tar.gz`.
 *
 * @param string[] $doubledots Array of double dot file extensions strings
 * without the first prefixing dot
 */
$doubledots = array_map('strrev', array(
    'tar.gz',
    'tar.bz',
    'tar.bz2',
    'tar.xz',
    'user.js',
));

define('MOE', 'false');