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
 * @package   DataContainers/Requirements
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-data-containers
 */

namespace GanbaroDigital\DataContainers\Requirements;

use GanbaroDigital\DataContainers\Checks\IsDotNotationPath;
use GanbaroDigital\DataContainers\Exceptions\E4xx_NotDotNotationPath;
use GanbaroDigital\DataContainers\Exceptions\E4xx_UnsupportedType;
use GanbaroDigital\Reflection\Requirements\RequireStringy;
class RequireDotNotationPath
{
    /**
     * throws exceptions if $path is not a valid dot.notation.support path
     *
     * @param  string $path
     *         the path to check
     * @param  string $eUnsupportedType
     *         the class to use when throwing an unsupported-type exception
     * @param  string $eNotDotNotationPath
     *         the class to use when throwing a not-dot-notation-path exception
     * @return void
     */
    public static function check($path, $eUnsupportedType = E4xx_UnsupportedType::class, $eNotDotNotationPath = E4xx_NotDotNotationPath::class)
    {
        // make sure we have a string
        RequireStringy::check($path, $eUnsupportedType);

        // make sure the string contains a dot.notation.support path
        if (!IsDotNotationPath::inString($path)) {
            throw new $eNotDotNotationPath($path);
        }
    }

    /**
     * throws exceptions if $path is not a valid dot.notation.support path
     *
     * @param  string $path
     *         the path to check
     * @param  string $eUnsupportedType
     *         the class to use when throwing an unsupported-type exception
     * @param  string $eNotDotNotationPath
     *         the class to use when throwing a not-dot-notation-path exception
     * @return void
     */
    public function __invoke($path, $eUnsupportedType = E4xx_UnsupportedType::class, $eNotDotNotationPath = E4xx_NotDotNotationPath::class)
    {
        self::check($path, $eUnsupportedType, $eNotDotNotationPath);
    }

    /**
     * throws exceptions if $path is not a valid dot.notation.support path
     *
     * @deprecated since 2.2.0
     * @codeCoverageIgnore
     *
     * @param  string $path
     *         the path to check
     * @param  string $eUnsupportedType
     *         the class to use when throwing an unsupported-type exception
     * @param  string $eNotDotNotationPath
     *         the class to use when throwing a not-dot-notation-path exception
     * @return void
     */
    public static function checkMixed($path, $eUnsupportedType = E4xx_UnsupportedType::class, $eNotDotNotationPath = E4xx_NotDotNotationPath::class)
    {
        self::check($path, $eUnsupportedType, $eNotDotNotationPath);
    }

}