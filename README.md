# Pomf
[![Build
Status](https://travis-ci.org/pomf/pomf.svg?branch=master)](https://travis-ci.org/pomf/pomf)
[![Dependency
Status](https://david-dm.org/pomf/pomf.svg)](https://david-dm.org/pomf/pomf)
[![devDependency
Status](https://david-dm.org/pomf/pomf/dev-status.svg)](https://david-dm.org/pomf/pomf#info=devDependencies)
[![MIT
licensed](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/pomf/pomf/master/LICENSE)

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

Assuming you already have Node and NPM working, compilation is easy. Use the
following shell code:
```bash
git clone https://github.com/pomf/pomf
cd pomf/
make
make install
```
OR
```bash
make install DESTDIR=/desired/path/for/site
```
After this, the pomf site is now compressed and set up inside `dist/`, or, if specified, `DESTDIR`.

## Configuring

Front-end related settings, such as the name of the site, and maximum allowable
file size, are found in `templates/site_variables.json`.  Changes made here will
only take effect after rebuilding the site pages.  This may be done by running
`make` from the root of the site directory.

Back-end related settings, such as database configuration, and path for uploaded files, are found in `static/php/includes/settings.inc.php`.  Changes made here take effect immediately.

If you intend to allow uploading files larger than 2 MB, you may also need to
increase POST size limits in `php.ini` and webserver configuration. For PHP,
modify `upload_max_filesize` and `post_max_size` values. The configuration
option for nginx webserver is `client_max_body_size`.

Example nginx configs can be found in confs/.

## Using SQLite as DB engine

We need to create the SQLite database before it may be used by pomf.
Fortunately, this is incredibly simple.  

First create a directory for the database, e.g. `mkdir /var/db/pomf`.  
Then, create a new SQLite database from the schema, e.g. `sqlite3 /var/db/pomf/pomf.sq3 -init /home/pomf/sqlite_schema.sql`.
Then, finally, ensure the permissions are correct, e.g.
```bash
chown nginx:nginx /var/db/pomf
chmod 0750 /var/db/pomf
chmod 0640 /var/db/pomf/pomf.sq3
```

Finally, edit `php/includes/settings.inc.php` to indicate this is the database engine you would like to use.  Make the changes outlined below
```php
define('POMF_DB_CONN', '[stuff]'); ---> define('POMF_DB_CONN', 'sqlite:/var/db/pomf/pomf.sq3');`
define('POMF_DB_USER', '[stuff]'); ---> define('POMF_DB_USER', null);
define('POMF_DB_PASS', '[stuff]'); ---> define('POMF_DB_PASS', null);
```

*NOTE: The directory where the SQLite database is stored, must be writable by the web server user*

### Apache

If you are running Apache and want to compress your output when serving files,
add to your `.htaccess` file:

    AddOutputFilterByType DEFLATE text/html text/plain text/css application/javascript application/x-javascript application/json

Remember to enable `deflate_module` and `filter_module` modules in your Apache
configuration file.

### Migrating from MySQL to SQLite

For older versions of Pomf you may want to migrate to SQLite. Fortunately, it is incredibly simple to migrate your database.  This may be done on a live server, and should require zero downtime.

_If doing this on a live server, you way wish to work in a subdirectory (or vhost, or equivelant), so that any complications or mistakes do not affect your main site.  
If you choose not to do so, know that mistakes in the changes outlined below, will only temporarily impact **uploading**, causing **Server error** to be displayed.  None of these steps are destructive, and are easily reverted._

Run the following commands as root, to dump your database, and make a SQLite database with the contents.  
```bash
mkdir /var/db/pomf
wget -O /tmp/m2s https://github.com/dumblob/mysql2sqlite/raw/master/mysql2sqlite.sh
mysqldump -u OLD_DB_USER -p OLD_DB_PASS pomf | sh /tmp/m2s | sqlite3 /var/db/pomf/sq3
rm /tmp/m2s
chown -R nginx:nginx /var/db/pomf #replace user as appropriate
chmod 0750 /var/db/pomf && chmod 0640 /var/db/pomf/sq3
```
Edit the file `php/includes/settings.inc.php`, in the subdirectory you just made, making the changes outlined below.
```php
define('POMF_DB_CONN', '[stuff]'); ---> define('POMF_DB_CONN', 'sqlite:/var/db/pomf/pomf.sq3');`
define('POMF_DB_USER', '[stuff]'); ---> define('POMF_DB_USER', null);
define('POMF_DB_PASS', '[stuff]'); ---> define('POMF_DB_PASS', null);
```

Then, run `make` to rebuild the website pages, and copy the new `settings.inc.php` file into place. 

All done! You may disable or uninstall MySQL if you wish.

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
