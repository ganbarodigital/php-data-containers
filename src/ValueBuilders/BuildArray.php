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
use GanbaroDigital\Reflection\ValueBuilders\LookupMethodByType;
use GanbaroDigital\Reflection\ValueBuilders\SimpleType;
use Traversable;

class BuildArray
{
    use LookupMethodByType;

    /**
     * convert an existing data structure into an array
     *
     * @param  mixed $data
     *         the data type to convert
     * @return array
     */
    public function __invoke($data)
    {
        return self::from($data);
    }

    /**
     * convert an existing data structure into an array
     *
     * @param  mixed $data
     *         the data type to convert
     * @return array
     */
    public static function from($data)
    {
        $method = self::lookupMethodFor($data, self::$dispatchTable);
        return self::$method($data);
    }

    /**
     * convert an array (or traversable object) into an array
     *
     * this will do a deep conversion
     *
     * @param  Traversable $data
     *         the data to convert
     * @return array
     */
    private static function fromTraversable($data)
    {
        // our return value
        $retval = [];

        // walk the array
        foreach ($data as $key => $value) {
            if ($value instanceof Traversable || is_array($value)) {
                // array, object - deep copy required
                $retval[$key] = self::from($value);
            }
            else {
                // it is either opaque, or a simple type
                $retval[$key] = $value;
            }
        }

        // all done
        return $retval;
    }

    /**
     * fallback method - just wrap the data in an array
     *
     * @param  mixed $data
     * @return array
     */
    private static function fromEverythingElse($data)
    {
        return [ $data ];
    }

    /**
     * our lookup table of which types are handled by which method
     * @var array
     */
    private static $dispatchTable = [
        'Traversable' => 'fromTraversable',
        'EverythingElse' => 'fromEverythingElse',
    ];
}