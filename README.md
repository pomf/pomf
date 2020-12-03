# Pomf
[![Build
Status](https://travis-ci.org/pomf/pomf.svg?branch=master)](https://travis-ci.org/pomf/pomf)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=pomf_pomf&metric=alert_status)](https://sonarcloud.io/dashboard?id=pomf_pomf)
[![MIT
licensed](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/pomf/pomf/master/LICENSE)
[![Documentation Status](https://readthedocs.org/projects/pomf/badge/?version=latest)](http://pomf.readthedocs.io/en/latest/?badge=latest)

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

See the ((slightly modified)) real world example at [safe.moe](https://safe.moe).

## Requirements

Original development environment is Nginx + PHP5.5 + MySQL, but is confirmed to
work with Apache 2.4 and newer PHP versions. Should work with any other
PDO-compatible database.

## Install

For the purposes of this guide, we won't cover setting up Nginx, PHP, MySQL,
Node, or NPM. So we'll just assume you already have them all running well.

**NPM/Node is only needed to compile the files, Pomf runs on PHP.**

### Compiling

First you must get a copy of the pomf code.  To do so, clone this git repo.
You will need to recursively clone the repo to get the required PHP submodule,
and the optional user panel submodule.
```bash
git clone --recursive https://github.com/pomf/pomf
```
If you don't want either of the submodules run the following command,
```bash
git clone https://github.com/pomf/pomf
```

Assuming you already have Node and NPM working, compilation is easy. If you would like any additional submodules, or to exclude the default PHP submodule, use the `MODULES="..."` variable.

Run the following commands to do so.
```bash
cd pomf/
make
# alternatively
make MODULES="" # compile no submodules; exclude the default php backend module
make MODULES="php moe" # compile the php and moe submodules
#
make install
```
OR
```bash
make install DESTDIR=/desired/path/for/site
```
After this, the pomf site is now compressed and set up inside `dist/`, or, if specified, `DESTDIR`.

## Configuring

Front-end related settings, such as the name of the site, and maximum allowable
file size, are found in `dist.json`.  Changes made here will
only take effect after rebuilding the site pages.  This may be done by running
`make` from the root of the site directory.

Back-end related settings, such as database configuration, and path for uploaded files, are found in `static/php/includes/settings.inc.php`.  Changes made here take effect immediately.

If you intend to allow uploading files larger than 2 MB, you may also need to
increase POST size limits in `php.ini` and webserver configuration. For PHP,
modify `upload_max_filesize` and `post_max_size` values. The configuration
option for nginx webserver is `client_max_body_size`.

Example nginx configs can be found in confs/.

## File expiration

If you want files to expire please have a look at [Uguu](https://github.com/nokonoko/uguu) instead which is based on Pomf.

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
define('POMF_DB_CONN', '[stuff]'); ---> define('POMF_DB_CONN', 'sqlite:/var/db/pomf/pomf.sq3');
define('POMF_DB_USER', '[stuff]'); ---> define('POMF_DB_USER', null);
define('POMF_DB_PASS', '[stuff]'); ---> define('POMF_DB_PASS', null);
```

*NOTE: The directory where the SQLite database is stored, must be writable by the web server user*

## Nginx example config

I won't cover settings everything up, here are some Nginx examples. Use [Letsencrypt](https://letsencrypt.org) to obain a SSL cert.

Main domain:
```
server{
    
    listen	        443 ssl http2;
    server_name		www.yourdomain.com yourdomain.com;

    ssl on;
    ssl_certificate /path/to/fullchain.pem;
    ssl_certificate_key /path/toprivkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;   

    root /path/to/pomf/dist/;
    autoindex		off;
    access_log      off;
    index index.html index.php;  

    location ~* \.(ico|css|js|ttf)$ {
    expires 7d;
    }

    location ~* \.php$ {
    fastcgi_pass unix:/var/run/php/php7.3-fpm.sock;
    fastcgi_intercept_errors on;
    fastcgi_index index.php;
    fastcgi_split_path_info ^(.+\.php)(.*)$;
    include fastcgi_params;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

Subdomain serving files (do not enable PHP here):
```
server{
    listen          443 ssl http2;
    server_name     www.subdomain.serveryourfiles.com subdomain.serveryourfiles.com;

    ssl on;
    ssl_certificate /path/to/fullchain.pem;
    ssl_certificate_key /path/to/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    
    root            /path/where/uploaded/files/are/stored/;
    autoindex       off;
    access_log	    off;
    index           index.html;
}
```

To redirect HTTP to HTTPS make a config for each domain like so:
```
server {
    listen 80;
    server_name www.domain.com domain.com; 
    return 301 https://domain.com$request_uri;
}
```

### Migrating from MySQL to SQLite
 ,
Compared to SQLite, MySQL is relatively complicated to administer, brings in many unneeded dependencies, and consumes more resources.  Additonally, as a network service, poorly configured installations have the potential
to pose a security risk.

For these reasons, you may wish to use SQLite rather than MySQL.

Fortunately, it is incredibly simple to migrate your database.  This may be done on a live server, if you desire, and requires zero downtime.

The process described below involves running these commands on a live server.  Nothing done here affects your main site, until running the very last command, which is done after verifying there are no issues.  

No changes described here are destructive, and are easily reverted.  They only have the potential to cause uploading to fail gracefully, and will not affect downloading.

Run the following commands as root, to dump your database, and make a SQLite database with the contents.  
```bash
mkdir /var/db/pomf
wget -O /tmp/m2s https://github.com/dumblob/mysql2sqlite/raw/master/mysql2sqlite.sh
mysqldump -u OLD_DB_USER -p OLD_DB_PASS pomf | sh /tmp/m2s | sqlite3 /var/db/pomf/sq3
rm /tmp/m2s
chown -R nginx:nginx /var/db/pomf #replace user as appropriate
chmod 0750 /var/db/pomf && chmod 0640 /var/db/pomf/sq3
```
Edit the file `php/includes/settings.inc.php`, in your **source directory**, making the changes outlined below.  Note, changing the second two lines is optional, as they are simply ignored when using SQLite.
```php
define('POMF_DB_CONN', '[stuff]'); ---> define('POMF_DB_CONN', 'sqlite:/var/db/pomf/pomf.sq3');
define('POMF_DB_USER', '[stuff]'); ---> define('POMF_DB_USER', null);
define('POMF_DB_PASS', '[stuff]'); ---> define('POMF_DB_PASS', null);
```
Then, run `make DESTDIR=/path/to/main_site/testing_dir` (note the *testing_dir* component) to rebuild the website, and copy it into place, in a new testing subdirectory.

Now, navigate to this subdirectory in your web browser, e.g. http://example.com/testing_dir, and verify that uploading works fine.  If so, excellent!  You may rerun `make DESTDIR=/path/to/main_site` to update your main site.

All done! You may disable or uninstall MySQL if you wish.

## API
To upload using curl or make a tool you can post using: 
```
curl -i -F files[]=@yourfile.jpeg https://pomf.se/upload.php (JSON Response)
```
```
curl -i -F files[]=@yourfile.jpeg https://pomf.se/upload.php?output=text (Text Response)
```
```
curl -i -F files[]=@yourfile.jpeg https://pomf.se/upload.php?output=csv (CSV Response)
```
```
curl -i -F files[]=@yourfile.jpeg https://pomf.se/upload.php?output=html (HTML Response)
```


## Getting help

The Pomf community gathers on IRC. You can also email the maintainer for help.

- IRC (users): `#pomfret` on Rizon (`irc.rizon.net`)

## Contributing

We'd really like if you can take some time to make sure your coding style is
consistent with the project. Pomf follows [PHP
PSR-2](http://www.php-fig.org/psr/psr-2/) and [Airbnb JavaScript
(ES5)](https://github.com/airbnb/javascript/tree/es5-deprecated/es5) (`airbnb/legacy`)
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
