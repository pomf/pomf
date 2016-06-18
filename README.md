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

    git clone https://github.com/pomf/pomf
    cd pomf/
    npm install
    make

After this, the pomf site is now compressed and set up inside `dist/`.

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

Example nginx configs can be found:
[nginx.conf](https://u.pantsu.cat/ymixjx.conf)
[u.pantsu.cat](https://u.pantsu.cat/fqurgp.conf)
[pantsu.cat](https://u.pantsu.cat/pnuyls.conf)

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
