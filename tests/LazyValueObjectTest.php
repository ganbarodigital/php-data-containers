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

/**
 * @coversDefaultClass GanbaroDigital\DataContainers\LazyValueObject
 */
class LazyValueObjectTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @coversNone
	 */
	public function testCanInstantiate()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new LazyValueObject;

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($obj instanceof LazyValueObject);
	}

	/**
	 * @covers ::__call
	 * @covers ::convertMethodName
	 * @dataProvider getAndSetDataProvider
	 */
	public function testCanGetAndSetData($dataName, $expectedData)
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new LazyValueObject;

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->{'set' . $dataName }($expectedData);

	    // ----------------------------------------------------------------
	    // test the results

	    $actualData = $obj->{'get' . $dataName }();
	    $this->assertEquals($expectedData, $actualData);
	}

	public function getAndSetDataProvider()
	{
		return [
			[ 'Value1', true ],
			[ 'Value2', false ]
		];
	}

	/**
	 * @covers ::__call
	 * @dataProvider getAndSetDataProvider
	 */
	public function testCanCheckDataExists($dataName, $expectedData)
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new LazyValueObject;

	    // ----------------------------------------------------------------
	    // perform the change

	    $actualData = $obj->{'has' . $dataName}();
	    $this->assertFalse($actualData);
	    $obj->{'set' . $dataName}($expectedData);

	    // ----------------------------------------------------------------
	    // test the results

	    $actualData = $obj->{'has' . $dataName}();
	    $this->assertTrue($actualData);
	}

	/**
	 * @covers ::__call
	 * @dataProvider getAndSetDataProvider
	 */
	public function testCanRemoveData($dataName, $expectedData)
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new LazyValueObject;

	    $actualData = $obj->{'has' . $dataName}();
	    $this->assertFalse($actualData);
	    $obj->{'set' . $dataName}($expectedData);

	    $actualData = $obj->{'has' . $dataName}();
	    $this->assertTrue($actualData);

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->{'reset' . $dataName}();

	    // ----------------------------------------------------------------
	    // test the results

	    $actualData = $obj->{'has' . $dataName}();
	    $this->assertFalse($actualData);
	}

	/**
	 * @covers ::__call
	 * @expectedException GanbaroDigital\DataContainers\E4xx_NoSuchMethod
	 */
	public function testThrowsExceptionWhenUnsupportedMethodCalled()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new LazyValueObject;

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->doSomethingWeirdAndWonderful();
	}

	/**
	 * @covers ::__call
	 * @covers ::makeReadOnly
	 * @dataProvider getAndSetDataProvider
	 * @expectedException GanbaroDigital\DataContainers\E4xx_NoSuchMethod
	 */
	public function testCanMakeReadOnly($dataName, $expectedData)
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new LazyValueObject;
	    $obj->{'set' . $dataName }($expectedData);

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->makeReadOnly();

	    // ----------------------------------------------------------------
	    // test the results

	    // this will trigger the exception
	    $obj->{'set' . $dataName }($expectedData);
	}

	/**
	 * @covers ::jsonSerialize
	 */
	public function testCanJsonEncode()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new LazyValueObject;

	    $rawData = [
	    	"Value1" => 100,
	    	"Value2" => 200,
	    ];
	    $expectedData = json_encode($rawData);

	    // ----------------------------------------------------------------
	    // perform the change

	    foreach ($rawData as $key => $value) {
		    $obj->{'set' . $key}($value);
		}

	    // ----------------------------------------------------------------
	    // test the results

	    $actualData = json_encode($obj);
	    $this->assertEquals($expectedData, $actualData);
	}
}