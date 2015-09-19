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

use ArrayObject;
use GanbaroDigital\DataContainers\Exceptions\E4xx_UnsupportedType;
use GanbaroDigital\Reflection\Checks\IsAssignable;
use GanbaroDigital\Reflection\Checks\IsIndexable;
use GanbaroDigital\Reflection\Requirements\RequireAssignable;
use GanbaroDigital\Reflection\Requirements\RequireIndexable;
use GanbaroDigital\Reflection\ValueBuilders\LookupMethodByType;
use GanbaroDigital\Reflection\ValueBuilders\SimpleType;

class RemoveProperty
{
    use LookupMethodByType;

    /**
     * remove data from a container
     *
     * @param  mixed $container
     *         the container that we want to remove data from
     * @param  string $property
     *         the data that we want to remove
     * @return void
     */
    public function __invoke(&$container, $property)
    {
        return self::from($container, $property);
    }

    /**
     * remove data from a container
     *
     * @param  mixed $container
     *         the container that we want to remove data from
     * @param  string $property
     *         the data that we want to remove
     * @return void
     */
    public static function from(&$container, $property)
    {
        $method = self::lookupMethodFor($container, self::$dispatchTable);
        self::$method($container, $property);
    }

    /**
     * called when we're given a container that we cannot do anything with
     *
     * @return void
     */
    public static function nothingMatchesTheInputType($container)
    {
        throw new E4xx_UnsupportedType(SimpleType::from($container));
    }

    /**
     * remove data from a container
     *
     * @param  array|ArrayObject $container
     *         the container that we want to remove data from
     * @param  string $property
     *         the data that we want to remove
     * @return void
     */
    private static function fromArray(&$container, $property)
    {
        if (isset($container[$property])) {
            unset($container[$property]);
        }
    }

    /**
     * remove data from a container
     *
     * @param  object $container
     *         the container that we want to remove data from
     * @param  string $property
     *         the data that we want to remove
     * @return void
     */
    private static function fromObject($container, $property)
    {
        if (isset($container->{$property})) {
            unset($container->{$property});
        }
    }

    private static $dispatchTable = [
        'Array' => 'fromArray',
        'Assignable' => 'fromObject',
        'Indexable' => 'fromArray',
        'Object' => 'fromObject',
    ];
}