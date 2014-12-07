# Pomf.se
Only tested with Nginx + PHP5.5 + MySQL, but should work with newer PHP or any
other PDO-compatible database.

Most of the code is ghetto and easily improved, will clean it up sometime.

# Install
For the purposes of this guide, we won't cover setting up Nginx, PHP, MySQL,
Node, or NPM.  So we'll just assume you already have them all running well.

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

## Configuring
The majority of settings are in `static/includes/settings.inc.php`.  Read the 
comments in that file for more information.

For file size configuration, there is no server-side verification: we assume 
that PHP and Nginx provide ample protection in this department.  There is,
however, client-side configuration for max size, the `data-max-size` attribute
on the file input in `pages/upload_form.swig`.

# Contact
I can be contacted via neku@pomf.se or twitter at [@nekunekus](https://twitter.com/nekunekus).
