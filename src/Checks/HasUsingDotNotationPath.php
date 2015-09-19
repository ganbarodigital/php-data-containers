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

use GanbaroDigital\DataContainers\Exceptions\E4xx_NoSuchIndex;
use GanbaroDigital\DataContainers\Exceptions\E4xx_NoSuchProperty;
use GanbaroDigital\DataContainers\Exceptions\E4xx_NotDotNotationPath;
use GanbaroDigital\DataContainers\Exceptions\E4xx_UnsupportedType;
use GanbaroDigital\DataContainers\Requirements\RequireDotNotationPath;
use GanbaroDigital\DataContainers\Requirements\RequireReadableContainer;
use GanbaroDigital\DataContainers\ValueBuilders\DescendDotNotationPath;
use GanbaroDigital\Reflection\ValueBuilders\LookupMethodByType;
use GanbaroDigital\Reflection\Checks\IsAssignable;
use GanbaroDigital\Reflection\Checks\IsIndexable;
use GanbaroDigital\Reflection\Checks\IsStringy;
use GanbaroDigital\Reflection\Requirements\RequireAssignable;
use GanbaroDigital\Reflection\Requirements\RequireIndexable;
use GanbaroDigital\Reflection\ValueBuilders\SimpleType;

class HasUsingDotNotationPath
{
    use LookupMethodByType;

    /**
     * does $path point to a valid piece of data inside $container?
     *
     * @param  mixed $container
     *         the container to look inside
     * @param  string $path
     *         the dot.notation.support path to walk
     * @return boolean
     *         TRUE if $path points to a vlaid piece of data
     *         FALSE otherwise
     */
    public function __invoke($container, $path)
    {
        return self::in($container, $path);
    }

    /**
     * does $path point to a valid piece of data inside $container?
     *
     * @param  mixed $container
     *         the container to look inside
     * @param  string $path
     *         the dot.notation.support path to walk
     * @return boolean
     *         TRUE if $path points to a vlaid piece of data
     *         FALSE otherwise
     */
    public static function in($container, $path)
    {
        RequireReadableContainer::check($container);

        $method = self::lookupMethodFor($container, self::$dispatchTable);
        return self::$method($container, $path);
    }

    /**
     * does $path point to a valid piece of data inside $container?
     *
     * @param  array $container
     *         the container to look inside
     * @param  string $path
     *         the dot.notation.support path to walk
     * @return boolean
     *         TRUE if $path points to a vlaid piece of data
     *         FALSE otherwise
     */
    public static function inArray($container, $path)
    {
        // defensive programming!
        RequireIndexable::check($container, E4xx_UnsupportedType::class);
        IsDotNotationPath::in($path) || IsStringy::check($path) || E4xx_NotDotNotationPath::raise($path);

        try {
            $value = DescendDotNotationPath::intoArray($container, $path);
            return true;
        }
        catch (E4xx_NoSuchIndex $e) {
            return false;
        }
        catch (E4xx_NoSuchProperty $e) {
            return false;
        }
    }

    /**
     * does $path point to a valid piece of data inside $container?
     *
     * @param  object $container
     *         the container to look inside
     * @param  string $path
     *         the dot.notation.support path to walk
     * @return boolean
     *         TRUE if $path points to a vlaid piece of data
     *         FALSE otherwise
     */
    public static function inObject($container, $path)
    {
        // defensive programming!
        RequireAssignable::check($container, E4xx_UnsupportedType::class);
        IsDotNotationPath::in($path) || IsStringy::check($path) || E4xx_NotDotNotationPath::raise($path);

        try {
            $value = DescendDotNotationPath::intoObject($container, $path);
            return true;
        }
        catch (E4xx_NoSuchIndex $e) {
            return false;
        }
        catch (E4xx_NoSuchProperty $e) {
            return false;
        }
    }

    /**
     * lookup table of which method to call for which supported type
     *
     * @var array
     */
    private static $dispatchTable = [
        'Array' => 'inArray',
        'Object' => 'inObject',
    ];
}