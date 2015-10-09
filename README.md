# Pomf

Pomf is a simple file uploading and sharing platform.

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
    git clone https://git.pantsu.cat/pantsu/pomf.git
    cd pomf/
    npm install
    grunt

After this, the pomf site is now compressed and set up inside `dist/`.

## Configuring

The majority of settings are in `static/includes/settings.inc.php`. Read the
comments in that file for more information.

For file size configuration, there is no server-side verification: we assume
that PHP and Nginx provide ample protection in this department. There is,
however, client-side configuration for max size, the `data-max-size` attribute
on the file input in `pages/upload_form.swig`.

Make sure to disable PHP from being executed on the file download
domain/directory (e.g., `a.pomf.example`), otherwise an attacker can upload a
malicious `.php` file and execute it on your server.

### Apache

If you are running Apache and want to compress your output when serving files,
add to your `.htaccess` file:

    AddOutputFilterByType DEFLATE text/html text/plain text/css application/javascript application/x-javascript application/json

Remember to enable `deflate_module` and `filter_module` modules in your Apache
configuration file.

## Getting help

The Pantsu.cat community gathers on IRC. You can also email the maintainer for
help.

- IRC: `#pantsucat` on Partyvan (`irc.partyvan.eu`)
- Email: <hostmaster@pantsu.cat>

## Contributing

For source code changes, please submit `git-format-patch(1)` formatted patches
by email or come talk to us on IRC. See "Getting help". At this time, we do not
use the issue tracker on `git.pantsu.cat` to track issues/pull requests.

We'd really like if you can take some time to make sure your coding style is
consistent with the project. Pomf follows [PHP
PSR-2](http://www.php-fig.org/psr/psr-2/) and [Airbnb JavaScript
(ES5)](https://github.com/airbnb/javascript/tree/master/es5) (`airbnb/legacy`)
coding style guides. We use ESLint and PHPCS tools to enforce these standards.

You can also help by sending us feature requests and writing documentation and
tests.

Thanks!

## Credits

Pomf was created by Eric Johansson and Peter Lejeck for
[Pomf.se](http://pomf.se/). The software is currently maintained by the
community.

## License

Pomf is free software, and is released under the terms of the Expat license. See
`LICENSE`.
