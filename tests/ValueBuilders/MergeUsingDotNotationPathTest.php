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
 * @coversDefaultClass GanbaroDigital\DataContainers\ValueBuilders\MergeUsingDotNotationPath
 */
class MergeUsingDotNotationPathTest extends PHPUnit_Framework_TestCase
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

        $obj = new MergeUsingDotNotationPath();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof MergeUsingDotNotationPath);
    }

    /**
     * @covers ::__invoke
     * @dataProvider provideMergeables
     */
    public function testCanUseAsObject($ours, $path, $theirs, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new MergeUsingDotNotationPath;

        // ----------------------------------------------------------------
        // perform the change

        $obj($ours, $path, $theirs);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $ours);
    }

    /**
     * @covers ::into
     * @dataProvider provideMergeables
     */
    public function testCanCallStatically($ours, $path, $theirs, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test


        // ----------------------------------------------------------------
        // perform the change

        MergeUsingDotNotationPath::into($ours, $path, $theirs);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $ours);
    }

    /**
     * @covers ::intoArray
     * @dataProvider provideMergeableIndexables
     */
    public function testCanCallIntoArrayStatically($ours, $path, $theirs, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test


        // ----------------------------------------------------------------
        // perform the change

        MergeUsingDotNotationPath::intoArray($ours, $path, $theirs);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $ours);
    }

    /**
     * @covers ::intoObject
     * @dataProvider provideMergeableAssignables
     */
    public function testCanCallIntoObjectStatically($ours, $path, $theirs, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test


        // ----------------------------------------------------------------
        // perform the change

        MergeUsingDotNotationPath::intoObject($ours, $path, $theirs);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $ours);
    }

    /**
     * @covers ::into
     * @dataProvider provideNonIndexableNorAssignable
     * @expectedException GanbaroDigital\DataContainers\Exceptions\E4xx_UnsupportedType
     */
    public function testThrowsExceptionIfCalledStaticallyWithNonContainer($container)
    {
        // ----------------------------------------------------------------
        // setup your test

        $path = 'one.two';
        $theirs = [ ];

        // ----------------------------------------------------------------
        // perform the change

        MergeUsingDotNotationPath::into($container, $path, $theirs);
    }


    /**
     * @covers ::splitPathInTwo
     */
    public function testInternallySplitsAPathIntoParentAndChild()
    {
        // ----------------------------------------------------------------
        // setup your test

        $path = "dot.notation.support";
        $expectedResult = [ 'dot.notation', 'support' ];

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = InvokeMethod::onClass(MergeUsingDotNotationPath::class, 'splitPathInTwo', [$path]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
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

    public function provideMergeables()
    {
        return array_merge(
            $this->provideMergeableAssignables(),
            $this->provideMergeableIndexables()
        );
    }

    public function provideMergeableIndexables()
    {
        return [
            [
                // merges array into array
                [
                    "child" => [
                        "one" => [],
                        "two" => 2,
                        "three" => 3,
                    ],
                ],
                "child.one",
                [
                    "one" => 5,
                    "four" => 6,
                ],
                [
                    "child" => [
                        "one" => [
                            "one" => 5,
                            "four" => 6,
                        ],
                        "two" => 2,
                        "three" => 3,
                    ],
                ]
            ],
            [
                // merges array into object
                [
                    "child" => (object)[
                        "one" => [],
                        "two" => 2,
                        "three" => 3,
                    ],
                ],
                "child.one",
                [
                    "one" => 5,
                    "four" => 6,
                ],
                [
                    "child" => (object)[
                        "one" => [
                            "one" => 5,
                            "four" => 6,
                        ],
                        "two" => 2,
                        "three" => 3,
                    ],
                ]
            ],
            [
                // merges object into array
                [
                    "child" => [
                        "one" => [],
                        "two" => 2,
                        "three" => 3,
                    ],
                ],
                "child.one",
                (object)[
                    "one" => 5,
                    "four" => 6,
                ],
                [
                    "child" => [
                        "one" => [
                            "one" => 5,
                            "four" => 6,
                        ],
                        "two" => 2,
                        "three" => 3,
                    ],
                ]
            ],

        ];
    }

    public function provideMergeableAssignables()
    {
        return [
            [
                // merges array into array
                (object)[
                    "child" => [
                        "one" => [],
                        "two" => 2,
                        "three" => 3,
                    ],
                ],
                "child.one",
                [
                    "one" => 5,
                    "four" => 6,
                ],
                (object)[
                    "child" => [
                        "one" => [
                            "one" => 5,
                            "four" => 6,
                        ],
                        "two" => 2,
                        "three" => 3,
                    ],
                ]
            ],
            [
                // merges array into object
                (object)[
                    "child" => (object)[
                        "one" => (object)[],
                        "two" => 2,
                        "three" => 3,
                    ],
                ],
                "child.one",
                [
                    "one" => 5,
                    "four" => 6,
                ],
                (object)[
                    "child" => (object)[
                        "one" => (object)[
                            "one" => 5,
                            "four" => 6,
                        ],
                        "two" => 2,
                        "three" => 3,
                    ],
                ]
            ],
            [
                // merges object into array
                (object)[
                    "child" => [
                        "one" => [],
                        "two" => 2,
                        "three" => 3,
                    ],
                ],
                "child.one",
                (object)[
                    "one" => 5,
                    "four" => 6,
                ],
                (object)[
                    "child" => [
                        "one" => [
                            "one" => 5,
                            "four" => 6,
                        ],
                        "two" => 2,
                        "three" => 3,
                    ],
                ]
            ],

        ];
    }

}