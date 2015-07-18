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
use GanbaroDigital\Reflection\Checks\IsIndexable;
use GanbaroDigital\Reflection\Checks\IsTraversable;
use GanbaroDigital\Reflection\ValueBuilders\FirstMethodMatchingType;
use GanbaroDigital\Reflection\ValueBuilders\SimpleType;

class MergeIntoIndexable
{
    /**
     * merge their array into our array
     *
     * @param  array &$ours
     *         the array that we want to merge into
     * @param  array $theirs
     *         the array that we want to merge from
     * @return void
     */
    public static function fromArray(&$ours, $theirs)
    {
        // robustness!
        if (!IsIndexable::checkMixed($ours)) {
            throw new E4xx_UnsupportedType(SimpleType::fromMixed($ours));
        }
        if (!IsTraversable::checkMixed($theirs)) {
            throw new E4xx_UnsupportedType(SimpleType::fromMixed($theirs));
        }

        // copy from them to us
        foreach ($theirs as $key => $value) {
            self::mergeKeyIntoArray($ours, $key, $value);
        }

        // all done
    }

    /**
     * merge a single key into our array
     *
     * @param  array &$ours
     *         the array to merge into
     * @param  mixed $key
     *         the array key to merge into
     * @param  mixed $value
     *         the data to merge in
     * @return void
     */
    private static function mergeKeyIntoArray(&$ours, $key, $value)
    {
        // general case - overwrite because merging isn't possible
        if (ShouldOverwrite::intoArray($ours, $key, $value)) {
            $ours[$key] = $value;
            return;
        }

        // special case - we are merging into an object
        if (is_object($ours[$key])) {
            MergeIntoAssignable::fromMixed($ours[$key], $value);
            return;
        }

        // at this point, we are merging into an array, using recursion
        // for which I am going to hell
        MergeIntoIndexable::fromMixed($ours[$key], $value);
    }

    /**
     * merge their object into our array
     *
     * @param  array &$ours
     *         the array that we want to merge into
     * @param  object $theirs
     *         the object that we want to merge from
     * @return void
     */
    public static function fromObject(&$ours, $theirs)
    {
        // robustness!
        if (!is_object($theirs)) {
            throw new E4xx_UnsupportedType(SimpleType::fromMixed($theirs));
        }
        self::fromArray($ours, get_object_vars($theirs));
    }

    /**
     * merge their data into our array
     *
     * @param  array &$ours
     *         the array that we want to merge into
     * @param  array|object $theirs
     *         the data that we want to merge from
     * @return void
     */
    public static function fromMixed(&$ours, $theirs)
    {
        // robustness!
        if (!IsIndexable::checkMixed($ours)) {
            throw new E4xx_UnsupportedType(SimpleType::fromMixed($ours));
        }

        if (IsTraversable::checkMixed($theirs)) {
            return self::fromArray($ours, $theirs);
        }

        // cannot merge anything that reaches here!
        throw new E4xx_UnsupportedType(SimpleType::fromMixed($ours));
    }

    /**
     * merge their data into our array
     *
     * @param  array &$ours
     *         the array that we want to merge into
     * @param  array|object $theirs
     *         the data that we want to merge from
     * @return void
     */
    public function __invoke(&$ours, $theirs)
    {
        $methodName = FirstMethodMatchingType::fromMixed($theirs, get_class($this), 'from');
        return self::$methodName($ours, $theirs);
    }
}