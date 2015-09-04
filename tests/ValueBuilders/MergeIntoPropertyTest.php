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

use PHPUnit_Framework_TestCase;
use stdClass;

/**
 * @coversDefaultClass GanbaroDigital\DataContainers\ValueBuilders\MergeIntoProperty
 */
class MergeIntoPropertyTest extends PHPUnit_Framework_TestCase
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

        $obj = new MergeIntoProperty();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof MergeIntoProperty);
    }

    /**
     * @covers ::__invoke
     * @covers ::of
     */
    public function testCanUseAsObjectWithArray()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new MergeIntoProperty;
        $property = 100;
        $originalValue = "hello, world";
        $expectedResult = "goodbye tomorrow";
        $ours = [ $property => $originalValue ];

        // ----------------------------------------------------------------
        // perform the change

        $obj($ours, $property, $expectedResult);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($ours[$property], $expectedResult);
    }

    /**
     * @covers ::__invoke
     * @covers ::of
     */
    public function testCanUseAsObjectWithObject()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new MergeIntoProperty;
        $property = 100;
        $originalValue = "hello, world";
        $expectedResult = "goodbye tomorrow";
        $ours = (object)[ $property => $originalValue ];

        // ----------------------------------------------------------------
        // perform the change

        $obj($ours, $property, $expectedResult);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($ours->$property, $expectedResult);
    }

    /**
     * @covers ::__invoke
     * @covers ::of
     * @expectedException GanbaroDigital\DataContainers\Exceptions\E4xx_UnsupportedType
     * @dataProvider provideNonIndexableNorAssignable
     */
    public function testThrowsExceptionWhenOursIsNotIndexableNorAssignable($ours)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new MergeIntoProperty;
        $property = 100;

        // ----------------------------------------------------------------
        // perform the change

        $obj($ours, $property, true);
    }

    /**
     * @covers ::ofArray
     * @dataProvider provideNonIndexable
     * @expectedException GanbaroDigital\DataContainers\Exceptions\E4xx_UnsupportedType
     */
    public function testThrowsExceptionWhenNonIndexablePassedIntoStaticCall($data)
    {
        // ----------------------------------------------------------------
        // perform the change

        MergeIntoProperty::ofArray($data, '100', true);
    }

    /**
     * @covers ::ofObject
     * @dataProvider provideNonAssignable
     * @expectedException GanbaroDigital\DataContainers\Exceptions\E4xx_UnsupportedType
     */
    public function testThrowsExceptionWhenNonAssignablePassedIntoStaticCall($data)
    {
        // ----------------------------------------------------------------
        // perform the change

        MergeIntoProperty::ofObject($data, '100', true);
    }


    public function provideNonIndexableNorAssignable()
    {
        return [
            [ null ],
            [ false ],
            [ true ],
            [ 3.1415927 ],
            [ 100 ],
            [ new MergeIntoProperty ],
            [ fopen("php://input", "r") ],
            [ "traverse me!" ],
        ];
    }

    public function provideNonIndexable()
    {
        return [
            [ null ],
            [ false ],
            [ true ],
            [ 3.1415927 ],
            [ 100 ],
            [ new MergeIntoProperty ],
            [ new stdClass ],
            [ fopen("php://input", "r") ],
            [ "traverse me!" ],
        ];
    }

    public function provideNonAssignable()
    {
        return [
            [ null ],
            [ [ ] ],
            [ false ],
            [ true ],
            [ 3.1415927 ],
            [ 100 ],
            [ new MergeIntoProperty ],
            [ fopen("php://input", "r") ],
            [ "traverse me!" ],
        ];
    }

    /**
     * @covers ::ofArray
     * @dataProvider provideNonMergeables
     */
    public function testOverwritesArrayIndexWhenNotMergeable($expectedResult)
    {
        $property = 100;
        $originalValue = "hello, world";
        $ours = [ $property => $originalValue ];

        // ----------------------------------------------------------------
        // perform the change

        MergeIntoProperty::ofArray($ours, $property, $expectedResult);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($ours[$property], $expectedResult);
    }

    public function provideNonMergeables()
    {
        return [
            [ null ],
            [ false ],
            [ true ],
            [ 3.1415927 ],
            [ 100 ],
            [ new MergeIntoProperty ],
            [ fopen("php://input", "r") ],
            [ "traverse me!" ],
        ];
    }

    /**
     * @covers ::ofArray
     */
    public function testCanMergeTwoSubArraysTogether()
    {
        // ----------------------------------------------------------------
        // setup your test

        $ours = [
            "child" => [
                "one" => 1,
                "two" => 2,
                "three" => 3,
            ],
        ];

        $theirs = [
            "one" => 5,
            "four" => 6,
        ];

        $expectedResult = [
            "child" => [
                "one" => 5,
                "two" => 2,
                "three" => 3,
                "four" => 6,
            ],
        ];

        // ----------------------------------------------------------------
        // perform the change

        MergeIntoProperty::ofArray($ours, "child", $theirs);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $ours);
    }

    /**
     * @covers ::ofArray
     * @dataProvider provideMergeableIndexables
     */
    public function testCanMergeIntoAssignablesIndex($ours, $theirs, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test


        // ----------------------------------------------------------------
        // perform the change

        MergeIntoProperty::ofArray($ours, "child", $theirs);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $ours);
    }

    public function provideMergeableIndexables()
    {
        return [
            [
                // merges array into array
                [
                    "child" => [
                        "one" => 1,
                        "two" => 2,
                        "three" => 3,
                    ],
                ],
                [
                    "one" => 5,
                    "four" => 6,
                ],
                [
                    "child" => [
                        "one" => 5,
                        "two" => 2,
                        "three" => 3,
                        "four" => 6,
                    ],
                ]
            ],
            [
                // merges array into object
                [
                    "child" => (object)[
                        "one" => 1,
                        "two" => 2,
                        "three" => 3,
                    ],
                ],
                [
                    "one" => 5,
                    "four" => 6,
                ],
                [
                    "child" => (object)[
                        "one" => 5,
                        "two" => 2,
                        "three" => 3,
                        "four" => 6,
                    ],
                ]
            ],
            [
                // merges object into array
                [
                    "child" => [
                        "one" => 1,
                        "two" => 2,
                        "three" => 3,
                    ],
                ],
                (object)[
                    "one" => 5,
                    "four" => 6,
                ],
                [
                    "child" => [
                        "one" => 5,
                        "two" => 2,
                        "three" => 3,
                        "four" => 6,
                    ],
                ]
            ],

        ];
    }

    /**
     * @covers ::ofObject
     * @dataProvider provideNonMergeables
     */
    public function testOverwritesObjectPropertyWhenNotMergeable($expectedResult)
    {
        $property = 100;
        $originalValue = "hello, world";
        $ours = (object)[ $property => $originalValue ];

        // ----------------------------------------------------------------
        // perform the change

        MergeIntoProperty::ofObject($ours, $property, $expectedResult);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($ours->$property, $expectedResult);
    }

    /**
     * @covers ::ofObject
     */
    public function testCanMergeTwoSubAssignablesTogether()
    {
        // ----------------------------------------------------------------
        // setup your test

        $ours = (object)[
            "child" => (object)[
                "one" => 1,
                "two" => 2,
                "three" => 3,
            ],
        ];

        $theirs = (object)[
            "one" => 5,
            "four" => 6,
        ];

        $expectedResult = (object)[
            "child" => (object)[
                "one" => 5,
                "two" => 2,
                "three" => 3,
                "four" => 6,
            ],
        ];

        // ----------------------------------------------------------------
        // perform the change

        MergeIntoProperty::ofObject($ours, "child", $theirs);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $ours);
    }

    /**
     * @covers ::ofObject
     * @dataProvider provideMergeableAssignables
     */
    public function testCanMergeIntoAssignablesProperty($ours, $theirs, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test


        // ----------------------------------------------------------------
        // perform the change

        MergeIntoProperty::ofObject($ours, "child", $theirs);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $ours);
    }

    public function provideMergeableAssignables()
    {
        return [
            [
                // merges array into array
                (object)[
                    "child" => [
                        "one" => 1,
                        "two" => 2,
                        "three" => 3,
                    ],
                ],
                [
                    "one" => 5,
                    "four" => 6,
                ],
                (object)[
                    "child" => [
                        "one" => 5,
                        "two" => 2,
                        "three" => 3,
                        "four" => 6,
                    ],
                ]
            ],
            [
                // merges array into object
                (object)[
                    "child" => (object)[
                        "one" => 1,
                        "two" => 2,
                        "three" => 3,
                    ],
                ],
                [
                    "one" => 5,
                    "four" => 6,
                ],
                (object)[
                    "child" => (object)[
                        "one" => 5,
                        "two" => 2,
                        "three" => 3,
                        "four" => 6,
                    ],
                ]
            ],
            [
                // merges object into array
                (object)[
                    "child" => [
                        "one" => 1,
                        "two" => 2,
                        "three" => 3,
                    ],
                ],
                (object)[
                    "one" => 5,
                    "four" => 6,
                ],
                (object)[
                    "child" => [
                        "one" => 5,
                        "two" => 2,
                        "three" => 3,
                        "four" => 6,
                    ],
                ]
            ],

        ];
    }

}