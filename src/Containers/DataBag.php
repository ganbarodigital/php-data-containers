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
 * @package   DataContainers/Containers
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-data-containers
 */

namespace GanbaroDigital\DataContainers\Containers;

use ArrayIterator;
use IteratorAggregate;
use stdClass;
use GanbaroDigital\DataContainers\Exceptions\E4xx_NoSuchProperty;
use GanbaroDigital\DataContainers\Checks\HasUsingDotNotationPath;
use GanbaroDigital\DataContainers\Checks\IsDotNotationPath;
use GanbaroDigital\DataContainers\Editors\RemoveUsingDotNotationPath;
use GanbaroDigital\DataContainers\Filters\FilterDotNotationPath;
use GanbaroDigital\DataContainers\ValueBuilders\MergeUsingDotNotationPath;

/**
 * The DataBag is based on the BaseObject that I built for Datasift's
 * open-source library 'Stone'.
 *
 * It's essentially a stdClass-on-steroids.
 */
class DataBag extends stdClass implements IteratorAggregate
{
    /**
     * magic method, called when there's an attempt to get a property
     * that doesn't actually exist
     *
     * if $propertyName is a dot.notation.support path, we'll attempt to
     * retrieve the property from the data bag's children
     *
     * @param  string $propertyName
     *         name of the property being read
     * @return mixed
     *
     * @throws E4xx_NoSuchProperty
     */
    public function __get($propertyName)
    {
        // is the user trying to use dot.notation?
        if (IsDotNotationPath::inString($propertyName)) {
            return FilterDotNotationPath::fromObject($this, $propertyName);
        }

        // if we get here, then we have no idea what you are trying to get
        throw new E4xx_NoSuchProperty($this, $propertyName);
    }

    /**
     * magic method, called when there's an attempt to set a property that
     * doesn't actually exist
     *
     * if $propertyName is a dot.notation.support path, we'll attempt to
     * set the property using the path
     *
     * @param string $propertyName
     *        name of the property to set
     * @param mixed $propertyValue
     *        value of the property to set
     * @return void
     */
    public function __set($propertyName, $propertyValue)
    {
        // is the user trying to use dot.notation?
        if (IsDotNotationPath::inString($propertyName)) {
            return MergeUsingDotNotationPath::intoObject($this, $propertyName, $propertyValue, DataBag::class);
        }

        // if we get here, then we simply have a new property to set
        //
        // I hope that it does not recurse!
        $this->$propertyName = $propertyValue;
    }

    /**
     * magic method, called when we want to know if a fake property exists
     * or not
     *
     * if $propertyName is a dot.notation.support path, we'll attempt to
     * follow it to find the stated property
     *
     * @param  string $propertyName
     *         name of the property to search for
     * @return boolean
     *         TRUE if the property exists (or is emulated)
     *         FALSE otherwise
     */
    public function __isset($propertyName)
    {
        // is the user trying to use dot.notation?
        if (IsDotNotationPath::inString($propertyName)) {
            return HasUsingDotNotationPath::inObject($this, $propertyName);
        }

        // if we get here, the property does not exist
        return false;
    }

    /**
     * magic method, called when we want to delete a fake property
     *
     * @param string $propertyName
     *        the property to remove
     */
    public function __unset($propertyName)
    {
        RemoveUsingDotNotationPath::from($this, $propertyName);
    }

    /**
     * magic method, called after we have been cloned
     *
     * we go all-in and perform a deep clone
     *
     * @return void
     */
    public function __clone()
    {
        $properties = get_object_vars($this);
        foreach ($properties as $propertyName => $propertyValue) {
            if (is_object($propertyValue)) {
                $this->$propertyName = clone $propertyValue;
            }
        }
    }

    /**
     * support foreach() loops over our data
     *
     * @return \Traversable
     */
    public function getIterator()
    {
        return new ArrayIterator($this);
    }
}