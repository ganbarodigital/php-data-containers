# CHANGELOG

## develop branch

### New

We have a new DataBag (based on the old DataSift/Stone BaseObject), which comes with full dot.notation.support :)

* Containers\DataBag added
* Exceptions\E4xx_NoSuchIndex added
* Exceptions\E4xx_NoSuchProperty added
* Exceptions\E4xx_UnsupportedType added
* Filters\FilterDotNotationParts added
* ValueBuilders\MergeIntoProperty added

The following Internal classes were also added:

* Internal\Checks\AreMergeable added
* Internal\Checks\ShouldOverwrite added

## 2.0.1 - Sat Jul 4 2015

### Fixed

* Wrong type hints in docblock for StaticDataCache::getFromCache()
* Wrong type hints in docblock for StaticDataCache::setInCache()

## 2.0.0 - Sat Jun 27 2015

### Backwards-Compatibility Breaks

* Moved BaseContainer and LazyValueObject into Containers namespace
* Moved exceptions into Exceptions namespace

### New

* Caches\StaticDataCache trait
* Exceptions\E4xx_DataContainerException added to the hierarchy

### Fixes

* Update @link attribute in headers

## 1.0.1 - Sun Jun 21 2015

### Fixes

* Fix package name for Packagist / Composer

## 1.0.0 - Sun Jun 21 2015

Initial release.

### New

* BaseContainer added
* LazyValueObject added