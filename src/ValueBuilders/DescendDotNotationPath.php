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
 * @package   DataContainers/ValueBuilders
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-data-containers
 */

namespace GanbaroDigital\DataContainers\ValueBuilders;

use GanbaroDigital\DataContainers\Checks\IsReadableContainer;
use GanbaroDigital\DataContainers\Exceptions\E4xx_CannotDescendPath;
use GanbaroDigital\DataContainers\Exceptions\E4xx_NoSuchIndex;
use GanbaroDigital\DataContainers\Exceptions\E4xx_NoSuchProperty;
use GanbaroDigital\DataContainers\Exceptions\E4xx_UnsupportedType;
use GanbaroDigital\Reflection\Checks\IsAssignable;
use GanbaroDigital\Reflection\Checks\IsIndexable;
use GanbaroDigital\Reflection\Checks\IsTraversable;
use GanbaroDigital\Reflection\Requirements\RequireAssignable;
use GanbaroDigital\Reflection\Requirements\RequireIndexable;
use GanbaroDigital\Reflection\ValueBuilders\FirstMethodMatchingType;
use GanbaroDigital\Reflection\ValueBuilders\SimpleType;

class DescendDotNotationPath
{
    /**
     * descend inside an array, using dot.notation.support, and optionally
     * extending the array if the end of the dot.notation.path is missing
     *
     * @param  array &$arr
     *         the array to dig into
     * @param  string $index
     *         the dot.notation.support path to descend
     * @param  array|callable|string|null $extendingItem
     *         if we need to extend, what data type do we extend using?
     * @return mixed
     */
    public static function &intoArray(&$arr, $index, $extendingItem = null)
    {
        // robustness!
        RequireIndexable::checkMixed($arr, E4xx_UnsupportedType::class);

        $retval =& self::getPathFromRoot($arr, $index, $extendingItem);
        return $retval;
    }

    /**
     * descend inside an object, using dot.notation.support, and optionally
     * extending the object if the end of the dot.notation.path is missing
     *
     * @param  object $obj
     *         the object to dig into
     * @param  string $property
     *         the dot.notation.support path to descend
     * @param  array|callable|string|null $extendingItem
     *         if we need to extend, what data type do we extend using?
     * @return mixed
     */
    public static function &intoObject($obj, $property, $extendingItem = null)
    {
        // robustness!
        RequireAssignable::checkMixed($obj, E4xx_UnsupportedType::class);

        $retval =& self::getPathFromRoot($obj, $property, $extendingItem);
        return $retval;
    }

    /**
     * descend inside a container, using dot.notation.support, and optionally
     * extending the container if the end of the dot.notation.path is missing
     *
     * @param  mixed &$item
     *         the container to dig into
     * @param  string $property
     *         the dot.notation.support path to descend
     * @param  array|callable|string|null $extendingItem
     *         if we need to extend, what data type do we extend using?
     * @return mixed
     */
    public static function &intoMixed(&$item, $property, $extendingItem = null)
    {
        if (IsAssignable::checkMixed($item)) {
            $retval =& self::intoObject($item, $property, $extendingItem);
            return $retval;
        }
        if (IsTraversable::checkMixed($item)) {
            $retval =& self::intoArray($item, $property, $extendingItem);
            return $retval;
        }

        throw new E4xx_UnsupportedType(SimpleType::fromMixed($item));
    }

    /**
     * descend inside a variable, using dot.notation.support, and optionally
     * extending the variable if the end of the dot.notation.path is missing
     *
     * @param  object|array &$item
     *         the variable to dig into
     * @param  string $path
     *         the dot.notation.support path to descend
     * @param  array|callable|string|null $extendingItem
     *         if we need to extend, what data type do we extend using?
     * @return mixed
     */
    public function &__invoke($item, $path, $extendingItem = null)
    {
        return self::intoMixed($item, $path, $extendingItem);
    }

    /**
     * get whatever is at the end of the given dot.notation.support path,
     * optionally extending the path as required
     *
     * @param  array|object &$root
     *         where we start from
     * @param  string $path
     *         the dot.notation.support path to descend
     * @param  array|callable|string|null $extendingItem
     *         if we need to extend, what data type do we extend using?
     * @return mixed
     */
    private static function &getPathFromRoot(&$root, $path, $extendingItem)
    {
        $retval =& $root;

        $visitedPath = [];

        $parts = explode(".", $path);
        foreach ($parts as $part) {
            if (!IsReadableContainer::checkMixed($retval)) {
                throw new E4xx_CannotDescendPath($retval, implode('.', $visitedPath));
            }

            $retval =& self::getChildFromPart($retval, $part, $extendingItem);
            $visitedPath[] = $part;
        }

        return $retval;
    }

    /**
     * get a child from part of our container tree, optionally extending
     * the container if needed
     *
     * @param  array|object &$container
     *         the container we want to get the child from
     * @param  string $part
     *         the name of the child that we want
     * @param  array|callable|string|null $extendingItem
     *         if we need to extend the container, this is what we use to
     *         do the extension
     * @return mixed
     */
    private static function &getChildFromPart(&$container, $part, $extendingItem = null)
    {
        if (IsAssignable::checkMixed($container)) {
            return self::getPartFromObject($container, $part, $extendingItem);
        }

        // if we get here, this must be an array
        $retval =& self::getPartFromArray($container, $part, $extendingItem);
        return $retval;
    }

    /**
     * get a property from an object, extending the object if the property
     * does not exist
     *
     * @param  object $obj
     *         the object to look inside
     * @param  string $part
     *         the name of the property to extract
     * @param  array|callable|string|null $extendingItem
     *         if we need to extend, what data type do we extend using?
     * @return mixed
     */
    private static function &getPartFromObject($obj, $part, $extendingItem = null)
    {
        // general case
        if (isset($obj->$part)) {
            return $obj->$part;
        }

        // can we extend?
        if ($extendingItem === null) {
            throw new E4xx_NoSuchProperty($obj, $part);
        }

        $obj->$part = self::getExtension($extendingItem);
        return $obj->$part;
    }

    /**
     * get an index from an array, extending the array if the index does
     * not exist
     *
     * @param  array &$arr
     *         the array to look inside
     * @param  string $part
     *         the index to look up
     * @param  array|callable|string|null $extendingItem
     *         if we need to extend, what data type do we extend using?
     * @return mixed
     */
    private static function &getPartFromArray(&$arr, $part, $extendingItem = null)
    {
        if (isset($arr[$part])) {
            return $arr[$part];
        }

        // can we extend?
        if ($extendingItem === null) {
            throw new E4xx_NoSuchIndex('array', $part);
        }

        $arr[$part] = self::getExtension($extendingItem);
        return $arr[$part];
    }

    /**
     * create new extension
     *
     * @param  array|callable|string|null $extendingItem
     *         what data type do we extend using?
     * @return array|object
     *         the extension that has been created
     */
    private static function getExtension($extendingItem)
    {
        if (is_array($extendingItem)) {
            return $extendingItem;
        }

        // if they don't return anything useful, that is their problem!
        if (is_callable($extendingItem)) {
            return $extendingItem();
        }

        // assume that it is a class name
        return new $extendingItem;
    }
}