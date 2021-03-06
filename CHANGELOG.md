# CHANGELOG

## develop branch

Nothing yet.

## 2.5.2 - Wed Nov 2 2016

### Fixes

* Merging into a child array (where the parent is an object) was broken in 2.5.1

## 2.5.1 - Wed Nov 2 2016

### Fixes

* dot.notation.path no longer has to contain a dot
  - updated `IsDotNotationPath` check
* the dot.notation.path classes now support appending to arrays on their own

## 2.5.0 - Tue Feb 23 2016

### New

* Added interfaces to represent a read-write data container, and a read-only data container
  - Added `Containers\DataContainer` - read-write data container
  - Added `Containers\ReadOnlyDataContainer` - read-only data container
* `DataBag` is now a `DataContainer` too

## 2.4.1 - Thu Dec 17 2015

### Fixes

* `ValueBuilders\BuildArray` - handle deep conversion of `stdClass`

## 2.4.0 - Thu Dec 17 2015

### New

* `ValueBuilders\BuildArray` added - do deep conversion

## 2.3.0 - Sun Sep 20 2015

### Deprecated

We've added a new `Editors` namespace for tools which change the data that they're given to work on. In the past, these tools have ended up in the `ValueBuilders` namespace.

* ValueBuilders\MergeIntoAssignable - use Editors\MergeIntoAssignable instead
* ValueBuilders\MergeIntoIndexable - use Editors\MergeIntoIndexable instead
* ValueBuilders\MergeIntoProperty - use Editors\MergeIntoProperty instead
* ValueBuilders\MergeUsingDotNotationPath - use Editors\MergeIntoDotNotationPath instead

### New

* Checks\HasUsingDotNotationPath - does the dot.notation.support path point at data in a container?
* Containers\DataBag - now supports isset() for dot.notation paths
* Containers\DataBag - now supports unset() for dot.notation paths
* Editors\RemoveProperty - added - remove a property from an array or index
* Editors\RemoveUsingDotNotationPath - added - remove a property from a container using dot.notation.support

### Fixes

* Checks\IsDotNotationType - updated to use latest Reflection for performance

### Test Fixes

* E4xx_UnsupportedType - latest Exceptions release means we no longer attempt to unwind the caller stack further

## v2.2.0 - Fri Sep 4 2015

### New

* We now have generic static methods in all our classes.
* The ::xxxMixed() methods are now deprecated.

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
