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
use GanbaroDigital\Reflection\ValueBuilders\FirstMethodMatchingType;

class ShouldOverwrite
{
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
        $methodName = FirstMethodMatchingType::fromMixed($ours, get_class($this), 'into');
        return self::$methodName($ours, $property, $theirs);
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
    public static function intoMixed($ours, $property, $theirs)
    {
        if (IsIndexable::checkMixed($ours)) {
            return self::intoArray($ours, $property, $theirs);
        }
        if (IsAssignable::checkMixed($ours, $property, $theirs)) {
            return self::intoObject($ours, $property, $theirs);
        }

        throw new E4xx_UnsupportedType(gettype($ours));
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
        if (!IsAssignable::checkMixed($ours)) {
            throw new E4xx_UnsupportedType(gettype($ours));
        }

        // special case - property does not exist
        if (!isset($ours->$property)) {
            return true;
        }

        // general case - property exists, and we need to decide what should
        // be done about it
        return !AreMergeable::intoMixed($ours->$property, $theirs);
    }

    /**
     * should we overwrite $ours[$property] with the value of $theirs?
     *
     * @param  array $ours
     *         the array where $property may exist
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
    public static function intoArray($ours, $property, $theirs)
    {
        // robustness!
        if (!IsIndexable::checkMixed($ours)) {
            throw new E4xx_UnsupportedType(gettype($ours));
        }

        // special case - property does not exist
        if (!isset($ours[$property])) {
            return true;
        }

        // general case - property exists, and we need to decide what should
        // be done about it
        return !AreMergeable::intoMixed($ours[$property], $theirs);
    }
}