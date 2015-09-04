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
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-data-containers
 */

namespace GanbaroDigital\DataContainers\ValueBuilders;

use GanbaroDigital\UnitTestHelpers\ClassesAndObjects\InvokeMethod;
use PHPUnit_Framework_TestCase;
use stdClass;

/**
 * @coversDefaultClass GanbaroDigital\DataContainers\ValueBuilders\DescendDotNotationPath
 */
class DescendDotNotationPathTest extends PHPUnit_Framework_TestCase
{
    /**
     * @coversNothing
     */
    public function testCanInstantiate()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $obj = new DescendDotNotationPath;

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof DescendDotNotationPath);
    }

    /**
     * @covers ::__invoke
     * @covers ::getPathFromRoot
     * @dataProvider provideContainersAndPaths
     */
    public function testCanUseAsObject($container, $path, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new DescendDotNotationPath;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($container, $path);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::__invoke
     * @dataProvider provideNonContainers
     * @expectedException GanbaroDigital\DataContainers\Exceptions\E4xx_UnsupportedType
     */
    public function testThrowsExceptionIfCalledAsObjectWithNonContainer($container)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new DescendDotNotationPath();

        // ----------------------------------------------------------------
        // perform the change

        $obj($container, 'one.two');
    }

    /**
     * @covers ::into
     * @dataProvider provideContainersAndPaths
     */
    public function testCanCallStatically($container, $path, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = DescendDotNotationPath::into($container, $path);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::into
     * @dataProvider provideNonContainers
     * @expectedException GanbaroDigital\DataContainers\Exceptions\E4xx_UnsupportedType
     */
    public function testThrowsExceptionIfCalledStaticallyWithNonContainer($container)
    {
        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        DescendDotNotationPath::into($container, 'one.two');
    }

    /**
     * @covers ::intoArray
     * @dataProvider provideArrayContainersAndPaths
     */
    public function testCanStaticallyCallIntoArray($container, $path, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = DescendDotNotationPath::intoArray($container, $path);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::intoObject
     * @dataProvider provideObjectContainersAndPaths
     */
    public function testCanStaticallyCallIntoObject($container, $path, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = DescendDotNotationPath::intoObject($container, $path);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::intoArray
     * @dataProvider provideNonIndexables
     * @expectedException GanbaroDigital\DataContainers\Exceptions\E4xx_UnsupportedType
     */
    public function testThrowsExceptionIfIntoArrayCalledStaticallyWithNonIndexable($container)
    {
        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        DescendDotNotationPath::intoArray($container, 'one.two');
    }

    /**
     * @covers ::intoObject
     * @dataProvider provideNonAssignables
     * @expectedException GanbaroDigital\DataContainers\Exceptions\E4xx_UnsupportedType
     */
    public function testThrowsExceptionIfIntoObjectCalledStaticallyWithNonAssignable($container)
    {
        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        DescendDotNotationPath::intoObject($container, 'one.two');
    }

    /**
     * @covers ::getPartFromObject
     */
    public function testCanTraverseObjects()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = (object)[ "one" => 1, "two" => 2];
        $expectedResult = 1;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = InvokeMethod::onClass(DescendDotNotationPath::class, 'getPartFromObject', [ $obj, "one" ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::getPartFromArray
     * @expectedException GanbaroDigital\DataContainers\Exceptions\E4xx_NoSuchIndex
     */
    public function testThrowsExceptionWhenArrayDoesNotContainPart()
    {
        // ----------------------------------------------------------------
        // setup your test

        $arr = [ "one" => 1, "two" => 2 ];

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = InvokeMethod::onClass(DescendDotNotationPath::class, 'getPartFromArray', [ &$arr, "three" ]);
    }

    /**
     * @covers ::getPartFromArray
     */
    public function testCanTraverseArrays()
    {
        // ----------------------------------------------------------------
        // setup your test

        $arr = [ "one" => 1, "two" => 2 ];
        $expectedResult = 1;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = InvokeMethod::onClass(DescendDotNotationPath::class, 'getPartFromArray', [ &$arr, "one" ]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::getPartFromObject
     * @expectedException GanbaroDigital\DataContainers\Exceptions\E4xx_NoSuchProperty
     */
    public function testThrowsExceptionWhenObjectDoesNotContainPart()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = (object)[ "one" => 1, "two" => 2];

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = InvokeMethod::onClass(DescendDotNotationPath::class, 'getPartFromObject', [ $obj, "three" ]);
    }

    /**
     * @covers ::intoArray
     * @covers ::getPathFromRoot
     * @covers ::getChildFromPart
     * @expectedException GanbaroDigital\DataContainers\Exceptions\E4xx_NoSuchIndex
     */
    public function testThrowsExceptionWhenChildArrayOfArrayDoesNotMatchPath()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = [ "one" => [ "two" => 2] ];

        // ----------------------------------------------------------------
        // perform the change

        DescendDotNotationPath::intoArray($data, "one.three");
    }

    /**
     * @covers ::intoArray
     * @covers ::getPathFromRoot
     * @covers ::getChildFromPart
     * @expectedException GanbaroDigital\DataContainers\Exceptions\E4xx_NoSuchProperty
     */
    public function testThrowsExceptionWhenChildObjectOfArrayDoesNotMatchPath()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = [ "one" => (object)[ "two" => 2] ];

        // ----------------------------------------------------------------
        // perform the change

        DescendDotNotationPath::intoArray($data, "one.three");
    }

    /**
     * @covers ::intoObject
     * @covers ::getPathFromRoot
     * @covers ::getChildFromPart
     * @expectedException GanbaroDigital\DataContainers\Exceptions\E4xx_NoSuchIndex
     */
    public function testThrowsExceptionWhenChildArrayOfObjectDoesNotMatchPath()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = (object)[ "one" => [ "two" => 2 ] ];

        // ----------------------------------------------------------------
        // perform the change

        DescendDotNotationPath::intoObject($data, "one.three");
    }

    /**
     * @covers ::intoObject
     * @covers ::getPathFromRoot
     * @covers ::getChildFromPart
     * @expectedException GanbaroDigital\DataContainers\Exceptions\E4xx_NoSuchProperty
     */
    public function testThrowsExceptionWhenChildObjectOfObjectDoesNotMatchPath()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = (object)[ "one" => (object)[ "two" => 2] ];

        // ----------------------------------------------------------------
        // perform the change

        DescendDotNotationPath::intoObject($data, "one.three");
    }

    /**
     * @covers ::getPartFromArray
     * @covers ::getExtension
     */
    public function testCanExtendArrayUsingArray()
    {
        // ----------------------------------------------------------------
        // setup your test

        $arr = [ "one" => 1, "two" => 2];
        $expectedResult = [];

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = InvokeMethod::onClass(
            DescendDotNotationPath::class,
            'getPartFromArray',
            [ &$arr, "three", [] ]
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
        $this->assertTrue(isset($arr['three']));
        $this->assertTrue(is_array($arr['three']));
    }

    /**
     * @covers ::getPartFromArray
     * @covers ::getExtension
     */
    public function testCanExtendArrayUsingCallable()
    {
        // ----------------------------------------------------------------
        // setup your test

        $arr = [ "one" => 1, "two" => 2];
        $callable = function() {
            return "extended!";
        };

        $expectedResult = $callable();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = InvokeMethod::onClass(
            DescendDotNotationPath::class,
            'getPartFromArray',
            [ &$arr, "three", $callable ]
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
        $this->assertTrue(isset($arr['three']));
        $this->assertEquals($expectedResult, $arr['three']);
    }

    /**
     * @covers ::getPartFromArray
     * @covers ::getExtension
     */
    public function testCanExtendArrayUsingClassname()
    {
        // ----------------------------------------------------------------
        // setup your test

        $arr = [ "one" => 1, "two" => 2];
        $expectedResult = new stdClass;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = InvokeMethod::onClass(
            DescendDotNotationPath::class,
            'getPartFromArray',
            [ &$arr, "three", stdClass::class ]
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
        $this->assertTrue(isset($arr['three']));
        $this->assertTrue($arr['three'] instanceof stdClass);
    }

    /**
     * @covers ::getPartFromObject
     * @covers ::getExtension
     */
    public function testCanExtendObjectUsingArray()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = (object)[ "one" => 1, "two" => 2];
        $expectedResult = [];

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = InvokeMethod::onClass(
            DescendDotNotationPath::class,
            'getPartFromObject',
            [ $obj, "three", [] ]
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
        $this->assertTrue(isset($obj->three));
        $this->assertTrue(is_array($obj->three));
    }

    /**
     * @covers ::getPartFromObject
     * @covers ::getExtension
     */
    public function testCanExtendObjectUsingCallable()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = (object)[ "one" => 1, "two" => 2];
        $callable = function() {
            return "extended!";
        };

        $expectedResult = $callable();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = InvokeMethod::onClass(
            DescendDotNotationPath::class,
            'getPartFromObject',
            [ $obj, "three", $callable ]
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
        $this->assertTrue(isset($obj->three));
        $this->assertEquals($expectedResult, $obj->three);
    }

    /**
     * @covers ::getPartFromObject
     * @covers ::getExtension
     */
    public function testCanExtendObjectUsingClassname()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = (object)[ "one" => 1, "two" => 2];
        $expectedResult = new stdClass;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = InvokeMethod::onClass(
            DescendDotNotationPath::class,
            'getPartFromObject',
            [ $obj, "three", stdClass::class ]
        );

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
        $this->assertTrue(isset($obj->three));
        $this->assertTrue($obj->three instanceof stdClass);
    }

    /**
     * @covers ::getPathFromRoot
     * @dataProvider provideUnextendablePaths
     * @expectedException GanbaroDigital\DataContainers\Exceptions\E4xx_CannotDescendPath
     */
    public function testThrowsExceptionWhenAttemptingToExtendNonContainer($container, $path)
    {
        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        DescendDotNotationPath::into($container, $path, []);

    }

    public function provideContainersAndPaths()
    {
        return array_merge(
            $this->provideArrayContainersAndPaths(),
            $this->provideObjectContainersAndPaths()
        );
    }

    public function provideArrayContainersAndPaths()
    {
        $arrContainer = [
            "one" => 1,
            "two" => 2,
            "three" => [ 1, 2, 3 => [ 4, 5, 6 ]]
        ];
        return [
            [
                $arrContainer,
                "one",
                1
            ]
        ];
    }

    public function provideObjectContainersAndPaths()
    {
        $objContainer = (object)[
            "one" => 1,
            "two" => 2,
            "three" => [ 1, 2, 3 => [ 4, 5, 6 ]]
        ];
        return [
            [
                $objContainer,
                "one",
                1
            ]
        ];
    }

    public function provideNonContainers()
    {
        return [
            [ null ],
            [ false ],
            [ true ],
            [ 3.1415927 ],
            [ 100 ],
            [ fopen("php://input", "r") ],
            [ "hello, world!" ]
        ];
    }

    public function provideNonAssignables()
    {
        return [
            [ null ],
            [ [] ],
            [ false ],
            [ true ],
            [ 3.1415927 ],
            [ 100 ],
            [ fopen("php://input", "r") ],
            [ "hello, world!" ]
        ];
    }

    public function provideNonIndexables()
    {
        return [
            [ null ],
            [ false ],
            [ true ],
            [ 3.1415927 ],
            [ 100 ],
            [ new stdClass ],
            [ fopen("php://input", "r") ],
            [ "hello, world!" ]
        ];
    }

    public function provideUnextendablePaths()
    {
        $arrContainer = [
            "one" => 1,
            "two" => 2,
            "three" => [ 1, 2, 3 => [ 4, 5, 6 ]]
        ];
        $objContainer = (object)[
            "one" => 1,
            "two" => 2,
            "three" => [ 1, 2, 3 => [ 4, 5, 6 ]]
        ];

        return [
            [ $arrContainer, "three.1.4" ],
            [ $objContainer, "three.1.4" ],
        ];
    }
}