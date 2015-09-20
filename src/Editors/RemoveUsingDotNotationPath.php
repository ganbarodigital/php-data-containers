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
 * @package   DataContainers/Editors
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-data-containers
 */

namespace GanbaroDigital\DataContainers\Editors;

use GanbaroDigital\DataContainers\Containers\DataBag;
use GanbaroDigital\DataContainers\Exceptions\E4xx_UnsupportedType;
use GanbaroDigital\DataContainers\Filters\FilterDotNotationParts;
use GanbaroDigital\DataContainers\Requirements\RequireDotNotationPath;
use GanbaroDigital\DataContainers\ValueBuilders\DescendDotNotationPath;
use GanbaroDigital\Defensive\Requirements\RequireAnyOneOf;
use GanbaroDigital\Reflection\Checks\IsAssignable;
use GanbaroDigital\Reflection\Checks\IsIndexable;

class RemoveUsingDotNotationPath
{
    /**
     * remove data from a container, using dot.notation.support to identify
     * the data to remove
     *
     * @param  mixed $container
     *         the container we want to remove data from
     * @param  string $path
     *         the dot.notation.support path to the data to remove
     * @return void
     */
    public function __invoke(&$container, $path)
    {
        return self::from($container, $path);
    }

    /**
     * merge data from a container, using dot.notation.support to identify
     * the data to remove
     *
     * @param  mixed $container
     *         the container we want to remove from
     * @param  string $path
     *         the dot.notation.support path to the data to remove
     * @return void
     */
    public static function from(&$container, $path)
    {
        // defensive programming!
        RequireAnyOneOf::check([new IsAssignable, new IsIndexable], [$container], E4xx_UnsupportedType::class);
        RequireDotNotationPath::check($path);

        // find the point where we want to remove the data from
        list ($firstPart, $finalPart) = self::splitPathInTwo($path);
        $leaf =& DescendDotNotationPath::into($container, $firstPart);

        // remove it
        RemoveProperty::from($leaf, $finalPart);
    }

    /**
     * split the dot.notation.support path up into two parts - where we are
     * inserting, and the name of what we are inserting
     *
     * @param  string $path
     *         the dot.notation.support path that needs splitting
     * @return array
     *         the two parts of the path
     */
    private static function splitPathInTwo($path)
    {
        // find the point where we want to merge
        $firstPart = FilterDotNotationParts::fromString($path, 0, -1);
        $finalPart = FilterDotNotationParts::fromString($path, -1, 1);

        return [ $firstPart, $finalPart ];
    }
}