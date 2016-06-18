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

Original development environment is Nginx + PHP5.5 + MySQL, but is confirmed to
work with Apache 2.4 and newer PHP versions. Should work with any other
PDO-compatible database.

## Install

For the purposes of this guide, we won't cover setting up Nginx, PHP, MySQL,
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

Front-end related settings, such as the name of the site, and maximum allowable file size, are found in `templates/site_variables.json`.  Changes made here will only take effect after rebuilding the site pages.  This may be done by running `grunt` from the root of the site directory.

Back-end related settings, such as database configuration, and path for uploaded files, are found in `static/php/includes/settings.inc.php`.  Changes made here take effect immediately.

We need to create the SQLite database before it may be used by pomf.
Fortunately, this is incredibly simple.  

To create it from the schema, simply run `sqlite3 /path/to/db.sq3 -init /path/to/pomf/sqlite_schema.sql`,
obviously ensuring the paths are correct.  Using default paths, this would be
`sqlite3 /usr/share/nginx/pomf.sq3 -init /usr/share/nginx/html/sqlite_schema.sql`.

_NOTE: The **directory** where the SQLite database is stored, must be writable by the user the web server is running as_

If you intend to allow uploading files larger than 2 MB, you may also need to
increase POST size limits in `php.ini` and webserver configuration. For PHP,
modify `upload_max_filesize` and `post_max_size` values. The configuration
option for nginx webserver is `client_max_body_size`.

A best practice is to disable executing `.php` files on the `POMF_URL` domain
for uploaded files. This assures that a malicious user cannot execute arbitrary
PHP code on the server.

### Apache

If you are running Apache and want to compress your output when serving files,
add to your `.htaccess` file:

    AddOutputFilterByType DEFLATE text/html text/plain text/css application/javascript application/x-javascript application/json

Remember to enable `deflate_module` and `filter_module` modules in your Apache
configuration file.

### Migrating from MySQL to SQLite

Previously, we have used MySQL as the default database platform for pomf.  Generally this
is undesirable.  

MySQL is relatively complicated to administer, brings in many unneeded dependencies, and consumes
more resources than SQLite would, and result in worse performance for pomf.  Additonally, poorly configured installations have the potential
to pose a security risk.

Fortunately, it is incredibly simple to migrate your database.  This may be done on a live server, and should require
zero downtime.

**Note: some directories that were initally in the web root, are now in static/php/.  Ensure you consider this when reading the following**

*If doing this on a live server, you way wish to work in a subdirectory (or vhost, or equivelant), so that any complications or mistakes do not affect your main site.  This is what is explained below.

If you choose not to do so, know that mistakes in the changes outlined below, will only temporarily impact **uploading**, causing **Server error** to be displayed.  None of these steps are destructive, and are easily reverted.*

To create a subdirectory to work in
```bash
cp -R /path/to/pomfroot /path/to/webroot/sqlite_testing
e.g.
cp -R /usr/share/nginx /usr/share/nginx/html/sqlite_testing
```

Edit the file `static/php/includes/settings.inc.php`, in the subdirectory you just made, making the changes outlined below.
```php
define('POMF_DB_CONN', '[stuff]'); ---> define('POMF_DB_CONN', 'sqlite:/var/db/pomf/pomf.sq3');`
define('POMF_DB_USER', '[stuff]'); ---> define('POMF_DB_USER', null);
define('POMF_DB_PASS', '[stuff]'); ---> define('POMF_DB_PASS', null);
```

The following script will make a directory with the appopriate permissions, for the new SQLite database.  It will then make a dump of your existing database, convert it to a format suitable for SQLite, and then save it in the new database directory.  The script will then create a new SQLite database, populating it with the contents of the dump.  

It will have no impact on the operation of your MySQL server, or your main site.
```bash
#!/bin/bash
# ensure you change these to match your environment
OLD_DB_USER=pomf
OLD_DB_PASS=pass
WEB_SERVER_USER=nginx
# it is unlikely the following two need to be changed
OLD_DB_NAME=pomf
NEW_DB_NAME=pomf.sq3
NEW_DB_PATH='/var/db/pomf'

mkdir $NEW_DB_PATH

wget -O /tmp/m2s https://github.com/dumblob/mysql2sqlite/raw/master/mysql2sqlite.sh
mysqldump -u $OLD_DB_USER -p $OLD_DB_PASS $OLD_DB_NAME | sh /tmp/m2s > ${NEW_DB_PATH}/${NEW_DB_NAME}
sqlite3 ${NEW_DB_PATH}/${NEW_DB_NAME} -init ${NEW_DB_PATH}/db_dump.sql

rm /tmp/m2s
chown -R ${WEB_SERVER_USER}:${WEB_SERVER_USER} $NEW_DB_PATH
chmod 0750 $NEW_DB_PATH
chmod 0640 ${NEW_DB_PATH}/${NEW_DB_NAME}

echo == SQLite database has been prepared at ${NEW_DB_PATH}/${NEW_DB_NAME} ==
echo == A copy of the database dump used to create it is located at ${NEW_DB_PATH}/${NEW_DB_NAME} ==
```

Then, from the root of the new subdirectory you made, run `grunt` to rebuild the website pages, and copy the new `settings.inc.php` file into place.  Alternatively, if you would rather not do this, you may simply copy it yourself.

Now, navigate to the new dist/ subdirectory in your browser, e.g. https://pantsu.cat/sqlite_testing/dist, and ensure you are able to upload files without issue.  If so, great!  If not, you will need to troubleshoot the issue yourself.

Assuming you were able to upload files without issue on the testing directory, you may make the changes on your main site.  To do so, simply copy the file  `static/php/includes/settings.inc.php`from your testing directory to the matching location for your main site.  
E.g. `cp /usr/share/nginx/html/sqlite_testing/static/php/includes/settings.inc.php /usr/share/nginx/static/php/includes/settings.inc.php`

Then, run `grunt` to rebuild the site, and all done!  You may disable or uninstall MySQL if you wish.

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
