# CHANGELOG

## develop branch

### New

* ExceptionCaller - added ExceptionCaller::getNonCheckCaller()
* NonCheckCodeCaller - a better way to work out who is calling us

### Fixes

* UnsupportedType - now uses the NonCheckCodeCaller (gets closer to the original intended behaviour)

## 1.2.0 - Wed Jul 22 2015

### New

* CodeCaller now returns the file and line number of the caller (if available)
* CodeCaller now returns associative keys (as well as the original numbered keys)

## 1.1.1 - Sat Jul 4 2015

### Fixed

* ExceptionCaller now uses an immutable debug_backtrace() for better performance

## 1.1.0 - Sat Jul 4 2015

### New

* CodeCaller value builder (moved from ganbarodigital/php-reflection)
* ExceptionCaller trait
* ExceptionMessageData trait (in the Traits folder)
* UnsupportedType trait

## 1.0.0 - Sat Jun 27 2015

Initial release