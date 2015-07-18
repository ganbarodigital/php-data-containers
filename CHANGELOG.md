# CHANGELOG

## develop branch

Nothing yet.

## v2.1.0 - Sat Jul 18 2015

### New

We have a new DataBag (based on the old DataSift/Stone BaseObject), which comes with full dot.notation.support :)

* Checks\IsDotNotationPath added
* Checks\IsReadableContainer added
* Containers\DataBag added
* Exceptions\E4xx_CannotDescendPath added
* Exceptions\E4xx_NoSuchContainedData added
* Exceptions\E4xx_NoSuchIndex added
* Exceptions\E4xx_NoSuchProperty added
* Exceptions\E4xx_NotDotNotationPath added
* Exceptions\E4xx_UnsupportedType added
* Filters\FilterDotNotationPath added
* Filters\FilterDotNotationParts added
* Requirements\RequireDotNotationPath added
* Requirements\RequireReadableContainer added
* ValueBuilders\BuildDataBag added
* ValueBuilders\DescendDotNotationPath added
* ValueBuilders\MergeIntoAssignable added
* ValueBuilders\MergeIntoIndexable added
* ValueBuilders\MergeIntoProperty added
* ValueBuilders\MergeUsingDotNotationPath added

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