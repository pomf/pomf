# Pomf.se
Original development environment is Nginx + PHP5.5 + MySQL, but is confirmed to work with Apache 2.4
and newer PHP versions. Should work with any other PDO-compatible database.

# Install
For the purposes of this guide, we won't cover setting up Nginx, PHP, MySQL,
Node, or NPM.  So we'll just assume you already have them all running well.

[Nginx Config](http://transparency.pomf.se/configs/nginx)

[PHP Config](http://transparency.pomf.se/configs/php/)

## Compiling
The assets are minified and combined using [Grunt](http://gruntjs.com/).

Assuming you already have Node and NPM working, compilation is easy:
```
$ npm install -g grunt-cli
$ git clone https://github.com/nokonoko/Pomf.git
$ cd Pomf
$ npm install
$ grunt
```
After this, the pomf site is now compressed and set up inside `dist/`.

### Apache
If you are running Apache and want to compress your output when serving files, add to your `.htaccess` file:
```
AddOutputFilterByType DEFLATE text/html text/plain text/css application/javascript application/x-javascript application/json
```
Remember to enable `deflate_module` and `filter_module` modules in your Apache configuration file.

## Configuring
The majority of settings are in `static/includes/settings.inc.php`.  Read the 
comments in that file for more information.

For file size configuration, there is no server-side verification: we assume 
that PHP and Nginx provide ample protection in this department.  There is,
however, client-side configuration for max size, the `data-max-size` attribute
on the file input in `pages/upload_form.swig`.

## Todo

* Clean up Moe code, a lot..
* API keys?

# Contact
I can be contacted via neku@pomf.se or twitter at [@nekunekus](https://twitter.com/nekunekus).
