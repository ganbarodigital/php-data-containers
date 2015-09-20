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

use GanbaroDigital\DataContainers\Containers\DataBag;
use GanbaroDigital\DataContainers\ValueBuilders\MergeDataBag;
use GanbaroDigital\DataContainers\Exceptions\E4xx_UnsupportedType;
use GanbaroDigital\Reflection\ValueBuilders\LookupMethodByType;
use GanbaroDigital\Reflection\ValueBuilders\SimpleType;

class BuildDataBag
{
    use LookupMethodByType;

    /**
     * create a DataBag from an array of data
     *
     * @param  array $item
     *         the array to build from
     * @return DataBag
     */
    public static function fromArray($item)
    {
        $retval = new DataBag;
        MergeIntoAssignable::fromArray($retval, $item);
        return $retval;
    }

    /**
     * create a DataBag from an object containing data
     *
     * @param  object $item
     *         the object to build from
     * @return DataBag
     */
    public static function fromObject($item)
    {
        $retval = new DataBag;
        MergeIntoAssignable::fromObject($retval, $item);
        return $retval;
    }

    /**
     * create a DataBag from another container
     *
     * @param  array|object $item
     *         the container to extract from
     * @return DataBag
     */
    public static function from($item)
    {
        $methodName = self::lookupMethodFor($item, self::$dispatchTable);
        return self::$methodName($item);
    }

    /**
     * create a DataBag from another container
     *
     * @param  array|object $item
     *         the container to extract from
     * @return DataBag
     */
    public function __invoke($item)
    {
        $methodName = self::lookupMethodFor($item, self::$dispatchTable);
        return self::$methodName($item);
    }

    /**
     * called when we have a data type we cannot support
     *
     * @param  mixed $item
     * @return void
     */
    private static function nothingMatchesTheInputType($item)
    {
        throw new E4xx_UnsupportedType(SimpleType::from($item));
    }

    /**
     * our list of which method to call for which input data type
     * @var array
     */
    private static $dispatchTable = [
        'Array' => 'fromArray',
        'Object' => 'fromObject',
    ];
}