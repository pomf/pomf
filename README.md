# Pomf

## Install

For the purposes of this guide, we won't cover setting up Nginx, PHP, MySQL,
Node, or NPM. So we'll just assume you already have them all running well.

### Compiling

The assets are minified and combined using [Grunt](http://gruntjs.com/).

Assuming you already have Node and NPM working, compilation is easy. Use the
following shell code:

#### Apache
If you are running Apache and want to compress your output when serving files, add to your `.htaccess` file:
=======
```
npm install -g grunt-cli
git clone git://github.com/pantsucat/pomf.git
cd pomf/
npm install
grunt
```

After this, the pomf site is now compressed and set up inside `dist/`.

## Configuring

The majority of settings are in `static/includes/settings.inc.php`. Read the
comments in that file for more information.

For file size configuration, there is no server-side verification: we assume
that PHP and Nginx provide ample protection in this department. There is,
however, client-side configuration for max size, the `data-max-size` attribute
on the file input in `pages/upload_form.swig`.

Make sure to disable PHP from being executed on the file download
domain/directory (e.g a.site.com), otherwise a attacker can upload a malicious
.php file and execute it on your server.

### Apache

If you are running Apache and want to compress your output when serving files,
add to your `.htaccess` file:

```
AddOutputFilterByType DEFLATE text/html text/plain text/css application/javascript application/x-javascript application/json
```

Remember to enable `deflate_module` and `filter_module` modules in your Apache
configuration file.

## Todo

* Clean up Moe code, a lot..
* API keys?

## Contact
I can be contacted via hostmaster@pantsu.cat.
=======
## License

Pomf is free software, and is released under the terms of the MIT (Expat)
license. See LICENSE.

## Contact

The maintainer can be contacted via hostmaster@pantsu.cat.
