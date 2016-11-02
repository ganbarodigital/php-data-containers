<?php

/**
 * Copyright (c) 2015-present Ganbaro Digital Ltd
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category  Libraries
 * @package   DataContainers/Editors
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-data-containers
 */

namespace GanbaroDigital\DataContainers\Editors;

use GanbaroDigital\DataContainers\Checks\IsDotNotationPath;
use GanbaroDigital\DataContainers\Containers\DataBag;
use GanbaroDigital\DataContainers\Exceptions\E4xx_NotDotNotationPath;
use GanbaroDigital\DataContainers\Exceptions\E4xx_UnsupportedType;
use GanbaroDigital\DataContainers\Filters\FilterDotNotationParts;
use GanbaroDigital\DataContainers\Requirements\RequireDotNotationPath;
use GanbaroDigital\DataContainers\ValueBuilders\DescendDotNotationPath;
use GanbaroDigital\Reflection\Checks\IsAssignable;
use GanbaroDigital\Reflection\Checks\IsIndexable;
use GanbaroDigital\Reflection\Requirements\RequireAssignable;
use GanbaroDigital\Reflection\Requirements\RequireIndexable;
use GanbaroDigital\Reflection\ValueBuilders\SimpleType;

class MergeUsingDotNotationPath
{
    /**
     * merge their data into our array, using dot.notation.support to
     * find the point where the merge starts
     *
     * @param  array &$arr
     *         the array that we want to merge into
     * @param  string $path
     *         the dot.notation.support path to where the merge should start
     * @param  mixed $value
     *         the data that we want to merge from
     * @param  array|callable|string|null
     *         if $path goes beyond what exists in $ours, how do we want to
     *         extend $ours?
     * @return void
     */
    public static function intoArray(&$arr, $path, $value, $extendingItem = null)
    {
        // robustness!
        RequireIndexable::check($arr, E4xx_UnsupportedType::class);
        RequireDotNotationPath::check($path);

        // find the point where we want to merge
        list ($firstPart, $finalPart) = self::splitPathInTwo($path);
        if ($firstPart !== null) {
            $leaf =& DescendDotNotationPath::intoArray($arr, $firstPart, $extendingItem);
        }
        else {
            $leaf =& $arr;
        }

        // merge it
        MergeIntoProperty::of($leaf, $finalPart, $value);
    }

    /**
     * merge their data into our object, using dot.notation.support to
     * find the point where the merge starts
     *
     * @param  object $obj
     *         the object that we want to merge into
     * @param  string $path
     *         the dot.notation.support path to where the merge should start
     * @param  mixed $value
     *         the data that we want to merge from
     * @param  array|callable|string|null
     *         if $path goes beyond what exists in $ours, how do we want to
     *         extend $ours?
     * @return void
     */
    public static function intoObject($obj, $path, $value, $extendingItem = null)
    {
        // robustness!
        RequireAssignable::check($obj, E4xx_UnsupportedType::class);
        RequireDotNotationPath::check($path);

        // find the point where we want to merge
        list ($firstPart, $finalPart) = self::splitPathInTwo($path);
        if ($firstPart !== null) {
            $leaf =& DescendDotNotationPath::intoObject($obj, $firstPart, $extendingItem);
        }
        else {
            $leaf = $obj;
        }

        // merge it
        MergeIntoProperty::of($leaf, $finalPart, $value);
    }

    /**
     * merge their data into our data, using dot.notation.support to
     * find the point where the merge starts
     *
     * @param  object $ours
     *         the object that we want to merge into
     * @param  string $path
     *         the dot.notation.support path to where the merge should start
     * @param  mixed $value
     *         the data that we want to merge from
     * @param  array|callable|string|null
     *         if $path goes beyond what exists in $ours, how do we want to
     *         extend $ours?
     * @return void
     */
    public static function into(&$ours, $path, $value, $extendingItem = null)
    {
        if (IsAssignable::check($ours)) {
            return self::intoObject($ours, $path, $value, $extendingItem);
        }
        if (IsIndexable::check($ours)) {
            return self::intoArray($ours, $path, $value, $extendingItem);
        }

        throw new E4xx_UnsupportedType(SimpleType::from($ours));
    }

    /**
     * merge their data into our data, using dot.notation.support to
     * find the point where the merge starts
     *
     * @deprecated since 2.2.0
     * @codeCoverageIgnore
     *
     * @param  mixed $ours
     *         the data that we want to merge into
     * @param  string $path
     *         the dot.notation.support path to where the merge should start
     * @param  mixed $value
     *         the data that we want to merge from
     * @param  array|callable|string|null
     *         if $path goes beyond what exists in $ours, how do we want to
     *         extend $ours?
     * @return void
     */
    public static function intoMixed(&$ours, $path, $value, $extendingItem = null)
    {
        return self::into($ours, $path, $value, $extendingItem);
    }

    /**
     * merge their data into our data, using dot.notation.support to
     * find the point where the merge starts
     *
     * @param  mixed $ours
     *         the data that we want to merge into
     * @param  string $path
     *         the dot.notation.support path to where the merge should start
     * @param  mixed $value
     *         the data that we want to merge from
     * @param  array|callable|string|null
     *         if $path goes beyond what exists in $ours, how do we want to
     *         extend $ours?
     * @return void
     */
    public function __invoke(&$ours, $path, $value, $extendingItem = null)
    {
        return self::into($ours, $path, $value, $extendingItem);
    }

    /**
     * split the dot.notation.support path up into two parts - where we are
     * inserting, and the name of what we are inserting
     *
     * @param  string $path
     *         the dot.notation.support path that needs splitting
     * @return array
     *         the two parts of the path
     */
    private static function splitPathInTwo($path)
    {
        // find the point where we want to merge
        $firstPart = FilterDotNotationParts::fromString($path, 0, -1);
        $finalPart = FilterDotNotationParts::fromString($path, -1, 1);

        // what do we have?
        // var_dump("path", $path, "firstPart", $firstPart, "finalPart", $finalPart);

        // special case - we're already at the end of the path
        if (strlen($firstPart) === 0) {
            return [ null, $finalPart ];
        }

        // general case - we're not at the end of the path
        return [ $firstPart, $finalPart ];
    }
}
