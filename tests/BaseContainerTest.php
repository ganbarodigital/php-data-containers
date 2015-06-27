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
 * @link      http://code.ganbarodigital.com/php-data-containers
 */

namespace GanbaroDigital\DataContainers;

use PHPUnit_Framework_TestCase;
use GanbaroDigital\UnitTestHelpers\ClassesAndObjects\InvokeMethod;

/**
 * @coversDefaultClass GanbaroDigital\DataContainers\BaseContainer
 */
class BaseContainerTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @coversNone
	 */
	public function testCanInstantiate()
	{
		$obj = new BaseContainer;
		$this->assertTrue($obj instanceof BaseContainer);
	}

	/**
	 * @covers ::getData
	 * @covers ::setData
	 * @dataProvider getAndSetDataProvider
	 */
	public function testCanGetAndSetData($dataName, $expectedData)
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new BaseContainer;

	    // ----------------------------------------------------------------
	    // perform the change

	    InvokeMethod::onObject($obj, 'setData', [ $dataName, $expectedData ]);

	    // ----------------------------------------------------------------
	    // test the results

	    $actualData = InvokeMethod::onObject($obj, 'getData', [ $dataName ]);
	    $this->assertEquals($expectedData, $actualData);
	}

	public function getAndSetDataProvider()
	{
		return [
			[ 'Value', true ],
			[ 'Value', false ],
		];
	}

	/**
	 * @covers ::getData
	 */
	public function testReturnsNullWhenDataNotFound()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new BaseContainer;

	    // ----------------------------------------------------------------
	    // perform the change

	    // ----------------------------------------------------------------
	    // test the results

	    $actualData = InvokeMethod::onObject($obj, 'getData', [ 'Value' ]);
	    $this->assertNull($actualData);
	}

	/**
	 * @covers ::getAllData
	 */
	public function testCanGetAllData()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new BaseContainer;

	    $expectedData = [
	    	"Value1" => 100,
	    	"Value2" => 200,
	    ];

	    // ----------------------------------------------------------------
	    // perform the change

	    foreach ($expectedData as $key => $value) {
		    InvokeMethod::onObject($obj, 'setData', [ $key, $value ]);
		}

	    // ----------------------------------------------------------------
	    // test the results

	    $actualData = InvokeMethod::onObject($obj, 'getAllData');
	    $this->assertEquals($expectedData, $actualData);
	}

	/**
	 * @covers ::hasData
	 * @dataProvider getAndSetDataProvider
	 */
	public function testCanCheckDataExists($dataName, $expectedData)
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new BaseContainer;

	    // ----------------------------------------------------------------
	    // perform the change

	    // make sure we do not have this data first
	    $actualData = InvokeMethod::onObject($obj, 'hasData', [ $dataName ]);
	    $this->assertFalse($actualData);

	    // add the data
	    InvokeMethod::onObject($obj, 'setData', [ $dataName, $expectedData ]);

	    // ----------------------------------------------------------------
	    // test the results

	    $actualData = InvokeMethod::onObject($obj, 'hasData', [ $dataName ]);
	    $this->assertTrue($actualData);
	}

	/**
	 * @covers ::resetData
	 * @dataProvider getAndSetDataProvider
	 */
	public function testCanRemoveData($dataName, $expectedData)
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new BaseContainer;

	    // add the data
	    InvokeMethod::onObject($obj, 'setData', [ $dataName, $expectedData ]);

	    // make sure the data is now there
	    $actualData = InvokeMethod::onObject($obj, 'hasData', [ $dataName ]);
	    $this->assertTrue($actualData);

	    // ----------------------------------------------------------------
	    // perform the change

	    InvokeMethod::onObject($obj, 'resetData', [ $dataName ]);

	    // ----------------------------------------------------------------
	    // test the results

	    $actualData = InvokeMethod::onObject($obj, 'hasData', [ $dataName ]);
	    $this->assertFalse($actualData);
	}

	/**
	 * @covers ::resetData
	 */
	public function testNothingHappensWhenRemovingDataThatDoesNotExist()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new BaseContainer;
	    $dataName = 'Value1';

	    // ----------------------------------------------------------------
	    // perform the change

	    InvokeMethod::onObject($obj, 'resetData', [ $dataName ]);

	    // ----------------------------------------------------------------
	    // test the results

	    $actualData = InvokeMethod::onObject($obj, 'hasData', [ $dataName ]);
	    $this->assertFalse($actualData);
	}

}