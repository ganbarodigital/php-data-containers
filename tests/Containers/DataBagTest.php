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

use PHPUnit_Framework_TestCase;
use stdClass;

/**
 * @coversDefaultClass GanbaroDigital\DataContainers\Containers\DataBag
 */
class DataBagTest extends PHPUnit_Framework_TestCase
{
    /**
     * @coversNothing
     */
    public function testCanInstantiate()
    {
        $obj = new DataBag;
        $this->assertTrue($obj instanceof DataBag);
    }

    /**
     * @coversNothing
     */
    public function testIsInstanceOfStdclass()
    {
        $obj = new DataBag;
        $this->assertTrue($obj instanceof stdClass);
    }

    /**
     * @coversNothing
     */
    public function testCanGetAndSetProperties()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new DataBag;

        $expectedResult = (object)[ "one" => 1 ];

        // ----------------------------------------------------------------
        // perform the change

        $obj->test1 = $expectedResult;

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($expectedResult, $obj->test1);
    }

    /**
     * @coversNothing
     */
    public function testCanCheckIfPropertyIsSet()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new DataBag;
        $this->assertFalse(isset($obj->test1));

        // ----------------------------------------------------------------
        // perform the change

        $obj->test1 = 1;

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue(isset($obj->test1));
    }

    /**
     * @coversNothing
     */
    public function testCanUnsetProperty()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new DataBag;
        $obj->test1 = 1;
        $this->assertTrue(isset($obj->test1));

        // ----------------------------------------------------------------
        // perform the change

        unset($obj->test1);

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse(isset($obj->test1));
    }

    /**
     * @covers ::__get
     */
    public function testCanUseDotNotationToGetProperties()
    {
        // ----------------------------------------------------------------
        // setup your test

        $expectedResult = "hello, world";

        $obj = new DataBag;
        $obj->child1 = new stdClass;
        $obj->child1->child2 = new stdClass;
        $obj->child1->child2->child3 = $expectedResult;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj->{'child1.child2.child3'};

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::__set
     */
    public function testCanUseDotNotationToSetProperties()
    {
        // ----------------------------------------------------------------
        // setup your test

        $expectedResult = "hello, world";

        $obj = new DataBag;
        $path = 'child1.child2.child3';

        // ----------------------------------------------------------------
        // perform the change

        $obj->$path = $expectedResult;
        $actualResult = $obj->{$path};

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::__isset
     */
    public function testCanCheckIfPropertyIsSetUsingDotNotationSupport()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new DataBag;
        $this->assertFalse(isset($obj->{'child1.child2'}));

        // ----------------------------------------------------------------
        // perform the change

        $obj->child1 = new DataBag;
        $obj->child1->child2 = 100;

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue(isset($obj->{'child1.child2'}));
    }

    /**
     * @covers ::__unset
     */
    public function testCanUnsetPropertyUsingDotNotationSupport()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new DataBag;
        $obj->child1 = new DataBag;
        $obj->child1->child2 = 100;

        $this->assertTrue(isset($obj->child1->child2));
        $this->assertTrue(isset($obj->{'child1.child2'}));

        // ----------------------------------------------------------------
        // perform the change

        unset($obj->{'child1.child2'});

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue(isset($obj->child1));
        $this->assertFalse(isset($obj->child1->child2));
    }

    /**
     * @covers ::__get
     * @expectedException GanbaroDigital\DataContainers\Exceptions\E4xx_NoSuchProperty
     */
    public function testThrowsExceptionWhenPropertyNotFound()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new DataBag;

        // ----------------------------------------------------------------
        // perform the change

        $doesNotExist = $obj->doesNotExist;

    }

    /**
     * @covers ::__get
     * @expectedException GanbaroDigital\DataContainers\Exceptions\E4xx_NoSuchProperty
     */
    public function testThrowsExceptionWhenDotNotationPropertyNotFound()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new DataBag;

        // ----------------------------------------------------------------
        // perform the change

        $doesNotExist = $obj->{'does.not.exist'};
    }

    /**
     * @covers ::getIterator
     */
    public function testCanIterateOverDataBag()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new DataBag;
        $expectedResult = [
            'one' => 'hello',
            'two' => 'world',
            'three' => 'how are you',
            'four' => 'today?',
        ];

        foreach ($expectedResult as $key => $value) {
            $obj->$key = $value;
        }

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = [];
        foreach ($obj as $key => $value) {
            $actualResult[$key] = $value;
        }

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::__clone
     */
    public function testSupportsDeepCloning()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new DataBag;
        $obj->child1 = new DataBag;
        $obj->child1->child2 = new DataBag;

        // ----------------------------------------------------------------
        // perform the change

        $cloned = clone $obj;

        // ----------------------------------------------------------------
        // test the results

        $this->assertNotSame($obj, $cloned);
        $this->assertNotSame($obj->child1, $cloned->child1);
        $this->assertNotSame($obj->child1->child2, $cloned->child1->child2);
    }
}