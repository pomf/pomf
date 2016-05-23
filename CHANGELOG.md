# Change Log

All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## `2.1.0` - Unreleased

### Added

- `CHANGELOG.md`. (Juuso Lapinlampi)
- HTML `<meta name="generator">` with Pomf version. Generated with grunt-swig.
  (Juuso Lapinlampi)

### Changed

- All client-side JavaScript replaced with more efficient, smaller vanilla
  JavaScript. (Luminarys)
- Luminarys added to `package.json` contributors. (Juuso Lapinlampi)
- Missing essential PHP includes and classes are now errors (`require_once`)
  instead of warnings (`include_once`). (Austin Gillmann)

### Deprecated

- Swig banners. Use Swig templates or an alternative. (Juuso Lapinlampi)
- Unused file expiration (`expire`). This feature may be removed in the
  next major version. (Juuso Lapinlampi)
- `get_sha1()` function. This API may be changed or removed in the next
  major version, switching to SHA-256 hashes. (Juuso Lapinlampi)
- `csv_error`, `csv_success`, `html_error`, `html_success`, `json_error`,
  `json_success`, `text_error` and `text_success` functions. The
  functions will be renamed to `camelCase` format (e.g. `csvError`,
  `htmlSuccess`). (Juuso Lapinlampi)

### Fixed

- Code cleanliness of `upload_file()` in `upload.php`. (Juuso Lapinlampi)

## `2.0.2` - 2016-05-20

### Changed

- `npm bugs` URL replaced with an email address. (Juuso Lapinlampi)

### Fixed

- LibreJS `@source` URL to git.pantsu.cat's new cgit tree. (Juuso Lapinlampi)
- `package.json` package name to lowercase as per NPM rules. (Juuso Lapinlampi)
- Alphabetical order of grunt-swig options in `Gruntfile.js`. (Juuso Lapinlampi)
- "Getting help" IRC contact for developers in `README.md`. (Juuso Lapinlampi)
- Missing Bootstrap license header in `pomf.css`. (Juuso Lapinlampi)

## `2.0.1` - 2016-01-28

### Changed

- Gyazo Client renamed to Gyazowin in tools page to match the actual project
  name. (Juuso Lapinlampi)

### Fixed

- ShareX URL and settings in tools page. (Juuso Lapinlampi)
- Gyazowin URL in tools page. (Juuso Lapinlampi)
- PHPCS lint error about a missing newline after `text_error()`. (Juuso
  Lapinlampi)
- PHP warnings about undeclared variables in `html_success()` and
  `text_success()`. (Juuso Lapinlampi)

### Removed

- Unused, commented `a:focus` `outline-offset` in CSS. (Luminarys)
- Unused `check_fileapi.swig` inclusion in `nojs.swig`. (Juuso Lapinlampi)
- Unused `span.old` and `section.ded` CSS rules leftover from 2.0.0 release.
  (Eliot Whalan)

## `2.0.0` - 2016-01-10

### Added

- "Other services" page for Pantsu.cat. (Eliot Whalan)
- `lang` attributes for screen reader accessibility. (Eliot Whalan)
- Configuration option `POMF_FILES_LENGTH` for number of random characters to
  use in a generated filename. (Juuso Lapinlampi)
- JavaScript-free upload. (Eliot Whalan)
- HTML response API (for JavaScript-free upload). (Eliot Whalan)
- Text response API. (Eliot Whalan)
- limf and 1339secure to tools page. (Eliot Whalan, Juuso Lapinlampi)
- LibreJS compatibility. (Eliot Whalan)
- License headers in source code. (Juuso Lapinlampi)
- Link to Pantsu.cat's Tor hidden service. (Eliot Whalan)
- Credits, contributing, features, demo and getting help sections to
  `README.md`. (Juuso Lapinlampi)
- Git mailmap. (Juuso Lapinlampi)

### Changed

- Rebrand from Pomf.se to Pantsu.cat. (Eliot Whalan)
- Viewports no longer have a maximum scale. (Eliot Whalan)
- All tool configurations on tools page to use Pantsu.cat for uploading.
  (Eliot Whalan)
- "Autist neckbeard" warning on front page for users without JavaScript. (Eliot
  Whalan)
- Response Content-Type charset is now all uppercase (UTF-8). (Juuso
  Lapinlampi)
- npm dependencies have been updated to latest versions as of release. Fixes
  deprecation warnings. (Eliot Whalan)
- npm dependencies accept caret (minor release) instead of tilde (patch
  release). (Eliot Whalan)
- Increase user-configured maximum upload size to 128MiB. (Eliot Whalan)
- The upload button is now a `<button>` element instead of `<a>` hyperlink.
  Improves semantics and accessibility. (Juuso Lapinlampi)
- Banners are no longer displayed on the FAQ page. (Juuso Lapinlampi)
- FAQ has been rewritten from scratch to explain what Pantsu.cat is and what
  its policies are. Semantically correct and prettier. (Juuso Lapinlampi)

### Deprecated

- Gyazo response API. Use text response instead. (Juuso Lapinlampi)
- JavaScript ES5 code. A future release will update the JavaScript source to
  modern ES6. Browser compatibility may be affected. (Juuso Lapinlampi)

### Removed

- All unmaintained and dead tools from tools page. (Eliot Whalan)
- Internet Explorer 8 quirks mode compatibility in uglified JavaScript. (Juuso
  Lapinlampi)
- Moe panel from core, including most of the code in core for it. It is now an
  (unsupported) extension. (Eliot Whalan, Juuso Lapinlampi)
- Unnecessary TODO section from `README.md`. (Harry H)
- Code and images for non-free kawaii anime girls. The latter cannot be
  redistributed with the source. (Juuso Lapinlampi)
- Unused Grunt `cssmin` task's banner option. (Juuso Lapinlampi)
- Grunt HTML minification task. (Juuso Lapinlampi)
- Unused Grunt `mkdir` task. (Juuso Lapinlampi)
- Unused "POCKY~" CSS style rules. (Juuso Lapinlampi)
- Unused, commented Mozilla file input JavaScript code. (Juuso Lapinlampi)

### Fixed

- Missing whitespace in upload button label. (Eliot Whalan)
- Favicon HTML markup now uses modern `rel="icon"` markup. (Eliot Whalan)
- Refactored `generate_name()` for bug fixing. (Juuso Lapinlampi)
- Use UNIX socket in `POMF_DB_CONN` configuration option for faster database
  queries. (Juuso Lapinlampi)
- Format all PHP code to PSR-2 style guide, fixes lint errors. (Juuso
  Lapinlampi)
- Decode `&hellip;` correctly when the file is too big. (Juuso Lapinlampi)
- Remove other `&hellip;` decoding hack in `case 413`. (Juuso Lapinlampi)
- Whitespace in Swig templates, PHP and JavaScript. (Juuso Lapinlampi)
- `README.md` header outline, fixes accessibility. (Juuso Lapinlampi)
- Add missing authors to `LICENSE` and other files. (Juuso Lapinlampi)
- Format and lint all JavaScript code to Airbnb ES5 style. About 350 lint
  errors found, each carefully fixed by hand. (Juuso Lapinlampi)
- Ambiguity in choice of words for licensing (MIT to Expat). No actual change
  of license. (Eliot Whalan)
- Use strict PHP comparison for upload `$tries`. (Juuso Lapinlampi)
- Remove multiple instances of code duplication in JavaScript. (Juuso
  Lapinlampi)
- Virus scan banner is now informative (`.alert-info`), not an error
  (`.alert-error`). (Juuso Lapinlampi)
- Reduced `npm install` time from 15-30 seconds to mere ~5 seconds (on modern
  Intel i7-3770K machine), resulting in faster builds. (Juuso Lapinlampi)
- Minor `README.md` documentation errors. (Juuso Lapinlampi)
- WCAG 2.0 contrast (section 1.4.3) in `.alert-info`. (Juuso Lapinlampi)
- Capitalization of "ShareX" heading on tools page. (Juuso Lapinlampi)

## `1.0.0+8757e9a` - 2015-08-15

Last Git repository snapshot in `nokonoko/Pomf` before forking to
`pantsu/pomf`. No formal release.

### Added

- Gyazo and CSV response types. (Peter Lejeck)
- Flattr to donation banner. (Peter Lejeck)
- Label and message to Bitcoin URL in donation banner. (Peter Lejeck)
- CSS for donation buttons. (Peter Lejeck)
- Moe panel for login/administration. (Eric Johansson)
- Alternative email contact for file deletion in FAQ. (Eric Johansson)
- CSS for old and dead tools. (Peter Lejeck)
- Double dot file extensions support. Configurable in `settings.inc.php`. (Peter
  Lejeck)
- Disclaimer about unsupported web browsers in FAQ. (Eric Johansson)
- Initial file expiration support for moe panel. (Eric Johansson)
- MySQL schema (`schema.sql`) for installing Pomf. (Eric Johansson, cenci0)
- MySQL schema (`update.sql`) to help migrate old databases to the new moe panel
  schema. (cenci0, Austin Gillman)
- HTTP compression support in `upload.php`. (cenci0)
- Configuration option `POMF_URL`. (cenci0)
- `diverse_array` and `refiles` API in `upload.php`. (cenci0)
- HTML minification using grunt-contrib-htmlmin. (cenci0)
- Instructions in `README.md` on how to enable compression in Apache webserver.
  (cenci0)
- Todo section to `README.md`. (Eric Johansson)
- Advice to disable PHP execution for uploaded `.php` files. (Eric Johansson)
- Configurable `{{max_upload_size`}} option in `Gruntfile.js` to replace
  hardcoded 50MiB value. Defaults to value 50. (Kieran Harkin)

### Changed

- `POMF_DB_CONN` default host to `127.0.0.1`. (Eric Johansson)
- Paddings on `.alert` boxes simplified. (Peter Lejeck)
- Donation plea. (Peter Lejeck)
- Donate buttons are unified and on a single row. (Peter Lejeck)
- PayPal's encrypted `<form>` replaced with a link in donation banner. (Peter
  Lejeck)
- Virus scans banner to match the donate banner scheme.
- Donate banner is now an `.alert-info` instead of `.alert-error`. (Peter
  Lejeck)
- `generate_name` algorithm. Generates random lowercase letters from `a` to `z`
  instead of a mix of CRC-32B and random lowercase letters. (Eric Johansson)
- Tools page tool names and API status updated. (Eric Johansson, Peter Lejeck)
- Gyazo `generate_name` algorithm to `FxxAxxG.png`, where `x` is a random
  lowercase letter. Removes CRC-32B checksums and `$grill` parameter from the
  function. (Eric Johansson)
- Increased length of `generate_name` return string by one character. (Eric
  Johansson)
- Default `POMF_FILES_RETRIES` increased from 5 to 15. (Eric Johansson, Peter
  Lejeck)
- `nav.swig` links to new places of interest of other Pomf.se projects and
  social media. (Eric Johansson)
- Genericize defaults function into merge utility in `cheesesteak.js`. (Peter
  Lejeck)
- `README.md` now uses a hyperlink to `@nekunekus` Twitter account. (Eric
  Johansson)
- Refactored the core PHP code in Pomf. Introduced classes. (cenci0)
- `.alert-info`, `.alert-error`, `nav a` and `a` CSS colors to satisfy WCAG 2.0
  requirements on contrast (section 1.4.3). Patch by Juuso Lapinlampi. (Eric
  Johansson)

### Fixed

- `git clone` URL in `README.md`. (Eric Johansson)
- PHPDoc comments in `upload.php`. (Peter Lejeck)
- A bug in `refiles()`. Deferences `$file`. (Peter Lejeck, catboy)
- Broken tools download URLs. (Eric Johansson)
- Uploading files without file extension. (Eric Johansson)
- Bitcoin wallet URI in donation banner. (Eric Johansson)
- ShareX settings URL in tools. (Eric Johansson)
- Gyazo Client author's Twitter URL. (Eric Johansson)
- Code indentation in `upload.php`. (Peter Lejeck)
- Reduntant `settings.inc.php` inclusion twice in `upload.php`. (benwaffle)
- Moe panel incorrectly returning 5 entries instead of none in search while
  there's no search query. (benwaffle)
- Cross-site scripting vulnerability in moe panel from arbitrary `originalname`.
  No CVE requested. Reported by Juuso Lapinlampi. (Eric Johansson)
- Copy-pasted code rolled into a `for` loop in `generate_name`. (Michael
  "wafflestealer654")
- HTML validation for `case 413` in `pomf.js`. (cenci0)
- `README.md` title from Pomf.se to Pomf. (Eric Johansson)
- Pass full URL back to the client in response. (Kieran Harkin)

### Removed

- Link to mail newsletter in FAQ. (Eric Johansson)
- Unused `blackniggers/kittens.php`. Previously used for Gyazo client, now
  replaced. (Eric Johansson)
- Unused `get_crc32` function in `UploadedFile.class.php`. Reported by Juuso
  Lapinlampi. (Eric Johansson)

## `v1.0.0` - 2013-10-31

Initial unofficial release in `nokonoko/Pomf`. Changes are since initial commit.

### Added

- `LICENSE` with Expat license. (Eric Johansson)
- Email and Twitter contacts to `README.md`. (Eric Johansson)
- Frequently asked questions (FAQ). (Eric Johansson)
- Favicon. (Eric Johansson)
- Kawaii anime girls (`grill.php`). (Eric Johansson)
- Web interface with JavaScript. (Eric Johansson, Peter Lejeck)
- Tools page. (Eric Johansson)
- `upload.php`, `UploadedFile.class.php`, `database.inc.php`,
  `settings.inc.php`. (Eric Johansson, Peter Lejeck)
- Gyazo support (`blackniggers/kittens.php`) with `generate_name` function.
  (Eric Johansson)
- Grunt buildsystem. (Peter Lejeck)
- Swig pages. (Peter Lejeck)
- Swig banners. (Peter Lejeck)
- `README.md` documentation. (Peter Lejeck)

### Changed

- `.gitignore` will only ignore `dist/` and `node_modules`. (Peter Lejeck)

### Removed

- `.gitattributes`. (Eric Johansson)
