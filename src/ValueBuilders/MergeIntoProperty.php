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

use GanbaroDigital\DataContainers\Exceptions\E4xx_UnsupportedType;
use GanbaroDigital\DataContainers\Internal\Checks\ShouldOverwrite;
use GanbaroDigital\Reflection\Checks\IsAssignable;
use GanbaroDigital\Reflection\Checks\IsIndexable;
use GanbaroDigital\Reflection\Requirements\RequireAssignable;
use GanbaroDigital\Reflection\Requirements\RequireIndexable;
use GanbaroDigital\Reflection\ValueBuilders\SimpleType;

class MergeIntoProperty
{
    /**
     * merge their data into one of our array's properties
     *
     * @param  array &$arr
     *         the array that we want to merge into
     * @param  string $property
     *         the property to merge into
     * @param  mixed $value
     *         the data that we want to merge from
     * @return void
     */
    public static function ofArray(&$arr, $property, $value)
    {
        // robustness!
        RequireIndexable::check($arr, E4xx_UnsupportedType::class);

        // easiest case - no clash
        if (ShouldOverwrite::intoArray($arr, $property, $value)) {
            $arr[$property] = $value;
            return;
        }

        // special case - we are merging into an object
        if (is_object($arr[$property])) {
            MergeIntoAssignable::from($arr[$property], $value);
            return;
        }

        // if we get here, then we must be merging into an array
        MergeIntoIndexable::from($arr[$property], $value);
    }

    /**
     * merge their data into one of our object's properties
     *
     * @param  object &$obj
     *         the object that we want to merge into
     * @param  string $property
     *         the property to merge into
     * @param  mixed $value
     *         the data that we want to merge from
     * @return void
     */
    public static function ofObject($obj, $property, $value)
    {
        // robustness!
        RequireAssignable::check($obj, E4xx_UnsupportedType::class);

        // easiest case - no clash
        if (ShouldOverwrite::intoObject($obj, $property, $value)) {
            $obj->$property = $value;
            return;
        }

        // special case - we are merging into an object
        if (is_object($obj->$property)) {
            MergeIntoAssignable::from($obj->$property, $value);
            return;
        }

        // if we get here, then we must be merging into an array
        MergeIntoIndexable::from($obj->$property, $value);
    }

    /**
     * merge their data into one of our data's properties
     *
     * @param  mixed $ours
     *         the data that we want to merge into
     * @param  string $property
     *         the property to merge into
     * @param  mixed $value
     *         the data that we want to merge from
     * @return void
     */
    public static function of(&$ours, $property, $value)
    {
        if (IsIndexable::check($ours)) {
            return self::ofArray($ours, $property, $value);
        }
        if (IsAssignable::check($ours)) {
            return self::ofObject($ours, $property, $value);
        }

        throw new E4xx_UnsupportedType(SimpleType::from($ours));
    }

    /**
     * merge their data into one of our data's properties
     *
     * @deprecated since 2.10.0
     * @codeCoverageIgnore
     *
     * @param  mixed $ours
     *         the data that we want to merge into
     * @param  string $property
     *         the property to merge into
     * @param  mixed $value
     *         the data that we want to merge from
     * @return void
     */
    public static function ofMixed(&$ours, $property, $value)
    {
        return self::of($ours, $property, $value);
    }

    /**
     * merge their data into one of our data's properties
     *
     * @param  mixed $ours
     *         the data that we want to merge into
     * @param  string $property
     *         the property to merge into
     * @param  mixed $value
     *         the data that we want to merge from
     * @return void
     */
    public function __invoke(&$ours, $property, $value)
    {
        return self::of($ours, $property, $value);
    }
}