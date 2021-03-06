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
 * @package   DataContainers/Internal
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-data-containers
 */

namespace GanbaroDigital\DataContainers\Internal\Checks;

use GanbaroDigital\DataContainers\Exceptions\E4xx_UnsupportedType;
use GanbaroDigital\Reflection\Checks\IsAssignable;
use GanbaroDigital\Reflection\Checks\IsIndexable;
use GanbaroDigital\Reflection\ValueBuilders\LookupMethodByType;
use GanbaroDigital\Reflection\ValueBuilders\SimpleType;

class ShouldOverwrite
{
    use LookupMethodByType;

    /**
     * should we overwrite $ours's $property with the value of $theirs?
     *
     * @param  mixed $ours
     *         the variable where $property may exist
     * @param  string $property
     *         the property on $ours whose fate we are deciding
     * @param  mixed $theirs
     *         the data we want to assign to the property
     * @return boolean
     *         TRUE if we should overwrite the property's existing value
     *         with $value
     *         TRUE if $property currently has no value
     *         FALSE if we should merge $value into the property's exist
     *         value
     */
    public function __invoke($ours, $property, $theirs)
    {
        return self::into($ours, $property, $theirs);
    }

    /**
     * should we overwrite $ours's $property with the value of $theirs?
     *
     * @param  mixed $ours
     *         the variable where $property may exist
     * @param  string $property
     *         the property on $ours whose fate we are deciding
     * @param  mixed $theirs
     *         the data we want to assign to the property
     * @return boolean
     *         TRUE if we should overwrite the property's existing value
     *         with $value
     *         TRUE if $property currently has no value
     *         FALSE if we should merge $value into the property's exist
     *         value
     */
    public static function into($ours, $property, $theirs)
    {
        $methodName = self::lookupMethodFor($ours, self::$dispatchTable);
        return self::$methodName($ours, $property, $theirs);
    }

    /**
     * called when there's no entry in our dispatch table for a data type
     *
     * @param  mixed $ours
     * @return void
     */
    private static function nothingMatchesTheInputType($ours)
    {
        throw new E4xx_UnsupportedType(SimpleType::from($ours));
    }

    /**
     * should we overwrite $ours->$property with the value of $theirs?
     *
     * @param  object $ours
     *         the object where $property may exist
     * @param  string $property
     *         the property on $ours whose fate we are deciding
     * @param  mixed $theirs
     *         the data we want to assign to the property
     * @return boolean
     *         TRUE if we should overwrite the property's existing value
     *         with $value
     *         TRUE if $property currently has no value
     *         FALSE if we should merge $value into the property's exist
     *         value
     */
    public static function intoObject($ours, $property, $theirs)
    {
        // robustness!
        if (!IsAssignable::check($ours)) {
            throw new E4xx_UnsupportedType(SimpleType::from($ours));
        }

        return self::checkObject($ours, $property, $theirs);
    }

    /**
     * should we overwrite $ours->$property with the value of $theirs?
     *
     * @param  object $ours
     *         the object where $property may exist
     * @param  string $property
     *         the property on $ours whose fate we are deciding
     * @param  mixed $theirs
     *         the data we want to assign to the property
     * @return boolean
     *         TRUE if we should overwrite the property's existing value
     *         with $value
     *         TRUE if $property currently has no value
     *         FALSE if we should merge $value into the property's exist
     *         value
     */
    private static function checkObject($ours, $property, $theirs)
    {
        // special case - property does not exist
        if (!isset($ours->$property)) {
            return true;
        }

        // general case - property exists, and we need to decide what should
        // be done about it
        return !AreMergeable::into($ours->$property, $theirs);
    }

    /**
     * should we overwrite $ours[$key] with the value of $theirs?
     *
     * @param  array $ours
     *         the array where $key may exist
     * @param  string $key
     *         the index on $ours whose fate we are deciding
     * @param  mixed $theirs
     *         the data we want to assign to the index
     * @return boolean
     *         TRUE if we should overwrite the index's existing value
     *         with $value
     *         TRUE if $key currently has no value
     *         FALSE if we should merge $value into the index's exist
     *         value
     */
    public static function intoArray($ours, $key, $theirs)
    {
        // robustness!
        if (!IsIndexable::check($ours)) {
            throw new E4xx_UnsupportedType(SimpleType::from($ours));
        }

        return self::checkArray($ours, $key, $theirs);
    }

    /**
     * should we overwrite $ours[$key] with the value of $theirs?
     *
     * @param  array $ours
     *         the array where $key may exist
     * @param  string $key
     *         the index on $ours whose fate we are deciding
     * @param  mixed $theirs
     *         the data we want to assign to the index
     * @return boolean
     *         TRUE if we should overwrite the index's existing value
     *         with $value
     *         TRUE if $key currently has no value
     *         FALSE if we should merge $value into the index's exist
     *         value
     */
    private static function checkArray(array $ours, $key, $theirs)
    {
        // special case - index does not exist
        if (!array_key_exists($key, $ours)) {
            return true;
        }

        // general case - index exists, and we need to decide what should
        // be done about it
        return !AreMergeable::into($ours[$key], $theirs);
    }

    /**
     * lookup table of which method to call for which data type
     * @var array
     */
    private static $dispatchTable = [
        'Array' => 'intoArray',
        'Object' => 'intoObject',
    ];
}