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

use GanbaroDigital\DataContainers\Containers\DataBag;
use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass GanbaroDigital\DataContainers\ValueBuilders\BuildDataBag
 */
class BuildDataBagTest extends PHPUnit_Framework_TestCase
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

        $obj = new BuildDataBag;

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof BuildDataBag);
    }

    /**
     * @covers ::__invoke
     */
    public function testCanUseAsObject()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new BuildDataBag;
        $data = (object)[
            "one" => 1,
            "two" => 2,
            "three" => 3
        ];

        $expectedResult = new DataBag;
        $expectedResult->one = 1;
        $expectedResult->two = 2;
        $expectedResult->three = 3;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::from
     * @covers ::fromArray
     * @dataProvider provideArraysToBuildFrom
     */
    public function testCanBuildFromArray($data)
    {
        // ----------------------------------------------------------------
        // setup your test

        $expectedResult = new DataBag;
        foreach ($data as $key => $value) {
            $expectedResult->$key = $value;
        }

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = BuildDataBag::from($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function provideArraysToBuildFrom()
    {
        return [
            [
                // single array
                [
                    "one" => 1,
                    "two" => 2,
                    "three" => 3,
                ]
            ],
            [
                // array with child array
                [
                    "one" =>  [
                        "four" => 4
                    ],
                    "two" => 2,
                    "three" => 3,
                ]
            ],
            [
                // array with child object
                [
                    "one" => (object)[
                        "four" => 4
                    ],
                    "two" => 2,
                    "three" => 3,
                ]
            ],
            [
                // array with child array and child object
                [
                    "one" => (object)[
                        "four" => 4
                    ],
                    "two" => 2,
                    "three" => 3,
                    "four" => [
                        "five" => 5,
                        "six" => 6,
                    ]
                ]
            ]
        ];
    }

    /**
     * @covers ::from
     * @covers ::fromObject
     * @dataProvider provideObjectsToBuildFrom
     */
    public function testCanBuildFromObject($data)
    {
        // ----------------------------------------------------------------
        // setup your test

        $expectedResult = new DataBag;
        foreach ($data as $key => $value) {
            $expectedResult->$key = $value;
        }

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = BuildDataBag::from($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function provideObjectsToBuildFrom()
    {
        return [
            [
                // single object
                (object)[
                    "one" => 1,
                    "two" => 2,
                    "three" => 3,
                ]
            ],
            [
                // object with child array
                (object)[
                    "one" =>  [
                        "four" => 4
                    ],
                    "two" => 2,
                    "three" => 3,
                ]
            ],
            [
                // object with child object
                (object)[
                    "one" => (object)[
                        "four" => 4
                    ],
                    "two" => 2,
                    "three" => 3,
                ]
            ],
            [
                // object with child array and child object
                (object)[
                    "one" => (object)[
                        "four" => 4
                    ],
                    "two" => 2,
                    "three" => 3,
                    "four" => [
                        "five" => 5,
                        "six" => 6,
                    ]
                ]
            ]
        ];
    }
}