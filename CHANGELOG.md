# Change Log

All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

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
- Unused `check_fileapi.swig` inclusion in `nojs.swig`.  (Juuso Lapinlampi)
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
`pantsu/pomf`.

## `1.0.0` - 2013-10-31

Initial unofficial release in `nokonoko/Pomf`.
