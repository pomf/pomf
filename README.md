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
Fortunately, this is incredibly simple.  To create it from the schema,
simply run `sqlite3 /path/to/db.sq3 -init /path/to/pomf/sqlite_schema.sql`,
obviously ensuring the paths are correct.  Using default paths, this
would be `sqlite3 /var/www/pomf.sq3 -init /var/www/htdocs/sqlite_schema.sql`.

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
