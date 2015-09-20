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

use GanbaroDigital\DataContainers\Exceptions\E4xx_UnsupportedType;
use GanbaroDigital\Reflection\Requirements\RequireStringy;
use GanbaroDigital\Reflection\Requirements\RequireInteger;

class FilterDotNotationParts
{
    /**
     * extract all but the end of a dot.notation.support string
     *
     * @param  string $dotNotation
     *         the string to extract from
     * @param  int $start
     *         which part do we want to start from?
     * @param  int $len
     *         how many parts do we want?
     * @return string
     *         the extracted parts
     */
    public static function fromString($dotNotation, $start, $len)
    {
        // robustness!
        RequireStringy::check($dotNotation, E4xx_UnsupportedType::class);
        RequireInteger::check($start, E4xx_UnsupportedType::class);
        RequireInteger::check($len, E4xx_UnsupportedType::class);

        $parts = explode(".", (string)$dotNotation);
        $parts = array_slice($parts, (int)$start, (int)$len);
        return implode(".", $parts);
    }

    /**
     * extract all but the end of a dot.notation.support string
     *
     * @param  mixed $dotNotation
     *         the string to extract from
     * @param  int $start
     *         which part do we want to start from?
     * @param  int $len
     *         how many parts do we want?
     * @return string
     *         the extracted parts
     */
    public static function from($dotNotation, $start, $len)
    {
        return self::fromString($dotNotation, $start, $len);
    }

    /**
     * extract all but the end of a dot.notation.support string
     *
     * @param  mixed $dotNotation
     *         the string to extract from
     * @param  int $start
     *         which part do we want to start from?
     * @param  int $len
     *         how many parts do we want?
     * @return string
     *         the extracted parts
     */
    public function __invoke($dotNotation, $start, $len)
    {
        return self::from($dotNotation, $start, $len);
    }
}