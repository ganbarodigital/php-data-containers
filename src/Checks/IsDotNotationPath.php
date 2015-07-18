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
 * @package   DataContainers/Checks
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-data-containers
 */

namespace GanbaroDigital\DataContainers\Checks;

use GanbaroDigital\DataContainers\Exceptions\E4xx_UnsupportedType;
use GanbaroDigital\Reflection\ValueBuilders\FirstMethodMatchingType;
use GanbaroDigital\Reflection\ValueBuilders\SimpleType;

class IsDotNotationPath
{
    /**
     * do we have a dot.notation string at all?
     *
     * @param  mixed $item
     *         the item to examine
     * @return boolean
     *         TRUE if the string is in dot.notation
     *         FALSE otherwise
     */
    public function __invoke($item)
    {
        $methodName = FirstMethodMatchingType::fromMixed($item, get_class($this), 'in');
        return self::$methodName($item);
    }

    /**
     * do we have a dot.notation string at all?
     *
     * @param  string $item
     *         the item to examine
     * @return boolean
     *         TRUE if the string is in dot.notation
     *         FALSE otherwise
     */
    public static function inString($item)
    {
        // robustness!!
        if (!is_string($item)) {
            throw new E4xx_UnsupportedType(SimpleType::fromMixed($item));
        }

        // make sure we have a dot somewhere we like
        if (!self::hasDotInAcceptablePlace($item)) {
            return false;
        }

        // if we get here, we're happy
        return true;
    }

    /**
     * do we have a dot, and is it somewhere we're happy with?
     *
     * @param  string $item
     * @return boolean
     *         FALSE if there is no '.' anywhere
     *         FALSE if the first '.' is at the end of the string
     *         TRUE otherwise
     */
    private static function hasDotInAcceptablePlace($item)
    {
        // where is the dot?
        $firstDot = strpos($item, '.');

        // do we even have one?
        if (false === $firstDot) {
            return false;
        }

        // can't be at the end of the string
        if ($firstDot === strlen($item) - 1) {
            return false;
        }

        // this is okay
        return true;
    }
}