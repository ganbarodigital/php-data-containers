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
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-data-containers
 */

namespace GanbaroDigital\DataContainers\Containers;

use JsonSerializable;
use RuntimeException;
use GanbaroDigital\DataContainers\Exceptions\E4xx_NoSuchMethod;

class LazyValueObject extends BaseContainer implements JsonSerializable
{
	/** @var array a list of fake operations supported */
	protected $supportedOperations = [
		'get'   => 'getData',
		'has'   => 'hasData',
		'set'   => 'setData',
		'reset' => 'resetData',
	];

	/**
	 * make this value object read-only
	 *
	 * @return void
	 */
	public function makeReadOnly()
	{
		unset($this->supportedOperations['set']);
		unset($this->supportedOperations['reset']);
	}

	/**
	 * magic method, which provides fake getter / setting support
	 *
	 * @param  string $methodName
	 *         the fake method that was called
	 * @param  array $args
	 *         a list of the args that were passed to us
	 * @return mixed
	 *         depends on the fake method being called
	 */
	public function __call($methodName, $args)
	{
		list($verb, $infoName) = $this->convertMethodName($methodName);

		if (!isset($this->supportedOperations[$verb])) {
			throw new E4xx_NoSuchMethod(get_class($this), $methodName);
		}

		$callable   = [ $this, $verb . 'Data'];
		$argsToPass = array_merge([ $infoName ], $args);
		return call_user_func_array($callable, $argsToPass);
	}

	/**
	 * given the 'get/set' kind of method name, convert it into:
	 *
	 * 1. the operation being performed (get, set, et al)
	 * 2. a normalised version of the name of the information
	 *
	 * @param  string $methodName the method name to decode
	 * @return array<string>
	 */
	protected function convertMethodName($methodName)
	{
        // turn the method name into an array of words
        $words = explode(' ', preg_replace('/([^A-Z])([A-Z])/', "$1 $2", $methodName));

        // lose the first word
        $verb = array_shift($words);

        // concat into underscore_format
        $retval = implode("_", $words);

        // all done
        return [$verb, $retval];
	}

	/**
	 * returns the data to turn into a JSON data structure
	 * @return object
	 */
	public function jsonSerialize()
	{
		return (object)$this->getAllData();
	}
}