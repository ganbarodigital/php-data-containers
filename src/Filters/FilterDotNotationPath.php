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
 * @package   DataContainers/Filters
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-data-containers
 */

namespace GanbaroDigital\DataContainers\Filters;

use GanbaroDigital\DataContainers\Checks\IsReadableContainer;
use GanbaroDigital\DataContainers\Exceptions\E4xx_UnsupportedType;
use GanbaroDigital\DataContainers\Requirements\RequireReadableContainer;
use GanbaroDigital\DataContainers\ValueBuilders\DescendDotNotationPath;
use GanbaroDigital\Reflection\Checks\IsAssignable;
use GanbaroDigital\Reflection\Checks\IsIndexable;
use GanbaroDigital\Reflection\Requirements\RequireAssignable;
use GanbaroDigital\Reflection\Requirements\RequireIndexable;
use GanbaroDigital\Reflection\ValueBuilders\FirstMethodMatchingType;
use GanbaroDigital\Reflection\ValueBuilders\SimpleType;

/**
 * this is a very simple wrapper around the DescendDotNotationPath
 * value builder
 */
class FilterDotNotationPath
{
    /**
     * extract a value from an array, using dot.notation.support
     *
     * @param  array $arr
     *         the array to extract from
     * @param  string $index
     *         the dot.notation.support path to walk
     * @return mixed
     *         whatever we find when we walk the path
     */
    public static function fromArray($arr, $index)
    {
        // robustness!
        RequireIndexable::checkMixed($arr, E4xx_UnsupportedType::class);

        return DescendDotNotationPath::intoArray($arr, $index);
    }

    /**
     * extract a value from an object, using dot.notation.support
     *
     * @param  object $obj
     *         the object to extract from
     * @param  string $property
     *         the dot.notation.support path to walk
     * @return mixed
     *         whatever we find when we walk the path
     */
    public static function fromObject($obj, $property)
    {
        // robustness!
        RequireAssignable::checkMixed($obj, E4xx_UnsupportedType::class);

        return DescendDotNotationPath::intoObject($obj, $property);
    }

    /**
     * extract a value from a container, using dot.notation.support
     *
     * @param  mixed $item
     *         the container to extract from
     * @param  string $path
     *         the dot.notation.support path to walk
     * @return mixed
     *         whatever we find when we walk the path
     */
    public static function fromMixed($item, $path)
    {
        // robustness!
        RequireReadableContainer::checkMixed($item, E4xx_UnsupportedType::class);

        if (IsAssignable::checkMixed($item)) {
            return self::fromObject($item, $path);
        }

        return self::fromArray($item, $path);
    }

    /**
     * extract a value from a variable, using dot.notation.support
     *
     * @param  object|array $item
     *         the variable to extract from
     * @param  string $property
     *         the dot.notation.support path to walk
     * @return mixed
     *         whatever we find when we walk the path
     */
    public function __invoke($item, $property)
    {
        return self::fromMixed($item, $property);
    }
}