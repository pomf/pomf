# Pomf
[![Build Status](https://travis-ci.org/pomf/pomf.svg?branch=master)](https://travis-ci.org/pomf/pomf) [![devDependency Status](https://david-dm.org/pomf/pomf/dev-status.svg)](https://david-dm.org/pomf/pomf#info=devDependencies)

Pomf is a simple file uploading and sharing platform.

## Features

- One click uploading, no registration required
- A minimal, modern web interface
- Drag & drop supported
- Upload API with multiple response choices
  - JSON
  - HTML
  - Text
  - CSV
- Supports [ShareX](https://getsharex.com/) and other screenshot tools

### Demo

See the real world example at [Pantsu.cat](https://pantsu.cat/).

## Requirements

Original development environment is Nginx + PHP5.5 + SQLite, but is confirmed to
work with Apache 2.4 and newer PHP versions. Should work with any other
PDO-compatible database.  Is known to work with MySQL, which has been the previous
default.

## Install

For the purposes of this guide, we won't cover setting up Nginx, PHP, or SQLite,
Node, or NPM. So we'll just assume you already have them all running well.

### Compiling

The assets are minified and combined using [Grunt](http://gruntjs.com/).

Assuming you already have Node and NPM working, compilation is easy. Use the
following shell code:

    npm install -g grunt-cli
    git clone https://github.com/pomf/pomf
    cd pomf/
    npm install
    grunt

After this, the pomf site is now compressed and set up inside `dist/`.

## Configuring

We need to create the SQLite database before it may be used by pomf.
Fortunately, this is incredibly simple.  

To create it from the schema, simply run `sqlite3 /path/to/db.sq3 -init /path/to/pomf/sqlite_schema.sql`,
obviously ensuring the paths are correct.  Using default paths, this would be
`sqlite3 /usr/share/nginx/pomf.sq3 -init /usr/share/nginx/html/sqlite_schema.sql`.

_NOTE: The **directory** where the SQLite database is stored, must be writable by the user the web server is running as_

The majority of the settings are in `static/includes/settings.inc.php`.

For file size configuration, open `Gruntfile.js` in an editor and modify the
`max_upload_size` value. The value is expressed in mebibytes (MiB). Run `grunt`
again to rebuild the pages for the changes to take effect.

If you intend to allow uploading files larger than 2 MB, you may also need to
increase POST size limits in `php.ini` and webserver configuration. For PHP,
modify `upload_max_filesize` and `post_max_size` values. The configuration
option for nginx webserver is `client_max_body_size`.

A best practice is to disable executing `.php` files on the `POMF_URL` domain
for uploaded files. This assures that a malicious user cannot execute arbitrary
PHP code on the server.

### Migrating from MySQL to SQLite

Previously, we have used MySQL as the default database platform for pomf.  Generally this
is undesirable.  

MySQL is relatively complicated to administer, brings in many unneeded dependencies, and consumes
more resources than SQLite would.  Additonally, poorly configured installations have the potential
to pose a security risk.

Fortunately, it is incredibly simple to migrate your database.  This may be done on a live server, and should require
zero downtime.

**Note: some directories that were initally in the web root, are now in static/php/.  Ensure you consider this when reading the following**

*You may test this first in a subdirectory (or vhost, or equivelant) if you wish.  If you make a mistake, however, only uploading will temporarily be impacted.  None of these steps are destructive, and are easily reverted.*

Make a copy of the file `static/php/includes/settings.inc.php`, and edit it, making the changes outlined below.  Note where you save it.
```php
define('POMF_DB_CONN', '[stuff]'); ---> define('POMF_DB_CONN', 'sqlite:/usr/share/nginx/pomf.sq3');`
define('POMF_DB_USER', '[stuff]'); ---> define('POMF_DB_USER', null);
define('POMF_DB_PASS', '[stuff]'); ---> define('POMF_DB_PASS', null);
```

The following script will make a dump of the MySQL database, convert it to a format acceptable for SQLite, then initialise a new SQLite database with the contents of the dump.  It will then backup your existing `settings.inc.php` file, and move the new one into place.
```bash
#!/bin/bash
# ensure you change these to match your environment
OLD_DB_USER=pomf
OLD_DB_PASS=pass
SETTINGS_INC_FILE='/usr/share/nginx/html/static/php/includes/settings.inc.php'
NEW_SETTINGS_INC_FILE='/path/to/edited/file'
# it is unlikely the following two need to be changed
OLD_DB_NAME=pomf
NEW_DB_PATH='/usr/share/nginx/pomf.sq3'

wget -O /tmp/m2s https://github.com/dumblob/mysql2sqlite/raw/master/mysql2sqlite.sh
mysqldump -u $OLD_DB_USER -p $OLD_DB_PASS $OLD_DB_NAME | sh /tmp/m2s | sqlite3 $NEW_DB_PATH
rm /tmp/m2s
echo == SQLite database has been prepared at $NEW_DB_PATH ==
cp $SETTINGS_INC_FILE ${SETTINGS_INC_FILE}.bak
mv $TMP_SETTINGS_INC_FILE $SETTINGS_INC_FILE
echo == Backed up old settings.inc.file, and moved new one into place ==
```

Ensure you are able to upload files, and then, all done!  You may now uninstall, or disable. MySQL if you wish.

### Apache

If you are running Apache and want to compress your output when serving files,
add to your `.htaccess` file:

    AddOutputFilterByType DEFLATE text/html text/plain text/css application/javascript application/x-javascript application/json

Remember to enable `deflate_module` and `filter_module` modules in your Apache
configuration file.

## Getting help

The Pomf community gathers on IRC. You can also email the maintainer for help.

- IRC (users): `#pomfret` on Rizon (`irc.rizon.net`)
- Email: <hostmaster@pantsu.cat>

## Contributing

We'd really like if you can take some time to make sure your coding style is
consistent with the project. Pomf follows [PHP
PSR-2](http://www.php-fig.org/psr/psr-2/) and [Airbnb JavaScript
(ES5)](https://github.com/airbnb/javascript/tree/master/es5) (`airbnb/legacy`)
coding style guides. We use ESLint and PHPCS tools to enforce these standards.

You can also help by sending us feature requests or writing documentation and
tests.

Thanks!

## Credits

Pomf was created by Eric Johansson and Peter Lejeck for
[Pomf.se](http://pomf.se/). The software is currently maintained by the
community.

## License

Pomf is free software, and is released under the terms of the Expat license. See
`LICENSE`.
