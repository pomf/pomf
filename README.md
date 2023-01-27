# Pomf
[![Build
Status](https://travis-ci.org/pomf/pomf.svg?branch=master)](https://travis-ci.org/pomf/pomf)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=pomf_pomf&metric=alert_status)](https://sonarcloud.io/dashboard?id=pomf_pomf)
[![MIT
licensed](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/pomf/pomf/master/LICENSE)
[![Documentation Status](https://docs.uguu.se/img/flat.svg)](https://docs.uguu.se/pomf)

Pomf is a simple file uploading and sharing platform.

-> Until Pomf is updated I recommend using [Uguu](https://github.com/nokonoko/Uguu)!<- 

## Features

- One click uploading, no registration required
- A minimal, modern web interface
- Drag & Drop & Paste supported
- Upload API with multiple response choices
  - JSON
  - HTML
  - Text
  - CSV
- Supports [ShareX](https://getsharex.com/) and other screenshot tools

### Demo

See the real world example at [demo.pomf.se](https://demo.pomf.se).


## Requirements

Tested and working with Nginx + PHP-8.0/8.1 + SQLite/MySQL. 

Node is used to compile Pomf, after that is runs on PHP.

## Installation

Installation and configuration can be found at [Pomf Documentation](https://docs.uguu.se/pomf).

If you need a admin panel check out [Moe Panel](https://github.com/pomf/MoePanel).

## File expiration

If you want files to expire please have a look at [Uguu](https://github.com/nokonoko/uguu) instead which is based on Pomf.

## Getting help

Hit me up at [@nekunekus](https://twitter.com/nekunekus) or email me at [neku@pomf.se](mailto:neku@pomf.se).

The Pomf community gathers on IRC.

- IRC (users): `#pomfret` on Rizon (`irc.rizon.net`)

## Contributing

We'd really like if you can take some time to make sure your coding style is
consistent with the project. Pomf follows [PHP
PSR-12](https://www.php-fig.org/psr/psr-12/) and [Airbnb JavaScript
(ES5)](https://github.com/airbnb/javascript/tree/es5-deprecated/es5) (`airbnb/legacy`)
coding style guides. We use ESLint and PHPCS tools to enforce these standards.

You can also help by sending us feature requests or writing documentation and
tests.

Thanks!

## Credits

Pomf was created by Go Johansson (nekunekus) & Emma Lejeck for
[Pomf.se](http://pomf.se/). The software is currently maintained by the
community.

## License

Pomf is free software, and is released under the terms of the Expat license. See
`LICENSE`.
