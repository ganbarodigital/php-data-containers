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
 * @package   GanbaroDigital/DataContainers
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-factfinder
 */

namespace GanbaroDigital\DataContainers;

class BaseContainer
{
	/**
	 * the information we are storing
	 * @var array
	 */
	private $data = [];

	/**
	 * retrieve a single piece of information
	 *
	 * @param  string $key
	 *         the information to retrieve
	 * @return mixed
	 *         the information found
	 */
	protected function getData($key)
	{
		if (array_key_exists($key, $this->data)) {
			return $this->data[$key];
		}

		// if we get here, we do not know anything
		return null;
	}

	/**
	 * retrieve all of the information we have
	 *
	 * @return array
	 */
	protected function getAllData()
	{
		return $this->data;
	}

	/**
	 * store a single piece of information
	 *
	 * @param string $key
	 *        the name of this information
	 * @param mixed $value
	 *        the information to store
	 */
	protected function setData($key, $value)
	{
		$this->data[$key] = $value;
	}

	/**
	 * do we have a given piece of information?
	 *
	 * @param  string $key
	 *         the information to check for
	 * @return boolean
	 *         TRUE if we have the information
	 *         FALSE otherwise
	 */
	protected function hasData($key)
	{
		return array_key_exists($key, $this->data);
	}

	/**
	 * delete a piece of data (if we have it)
	 *
	 * @param  string $key
	 *         the information to delete
	 * @return void
	 */
	protected function resetData($key)
	{
		if (!array_key_exists($key, $this->data)) {
			return;
		}

		unset($this->data[$key]);
	}
}