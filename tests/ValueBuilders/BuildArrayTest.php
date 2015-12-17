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
use stdClass;

/**
 * @coversDefaultClass GanbaroDigital\DataContainers\ValueBuilders\BuildArray
 */
class BuildArrayTest extends PHPUnit_Framework_TestCase
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

        $obj = new BuildArray;

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof BuildArray);
    }

    /**
     * @covers ::__invoke
     * @dataProvider provideDataToConvert
     */
    public function testCanUseAsObject($data, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new BuildArray;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::from
     * @dataProvider provideDataToConvert
     */
    public function testCanCallStatically($data, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = BuildArray::from($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::fromEverythingElse
     * @dataProvider provideScalarsToConvert
     */
    public function testCanWrapScalarsAtTheTopLevel($data, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = BuildArray::from($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::fromTraversable
     * @dataProvider provideArraysToConvert
     */
    public function testCanPerformDeepConversionOnArrays($data, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = BuildArray::from($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::fromTraversable
     * @dataProvider provideTraversablesToConvert
     */
    public function testCanConvertTraversableObjects($data, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = BuildArray::from($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function provideDataToConvert()
    {
        return array_merge(
            $this->provideArraysToConvert(),
            $this->provideScalarsToConvert(),
            $this->provideTraversablesToConvert()
        );
    }

    public function provideArraysToConvert()
    {
        // we need a few objects, to prove that BuildArray does deep
        // conversion
        $popo = new stdClass();
        $popo->fish = "trout";

        $dataBag = new DataBag;
        $dataBag->value1 = $popo;

        return [
            [
                [ 1 => 1, 2 => 2, 3 => 4],
                [ 1 => 1, 2 => 2, 3 => 4],
            ],
            [
                [ "fred" => [ 1 => 2], "harry" => $dataBag ],
                [ "fred" => [ 1 => 2], "harry" => [ "value1" => $popo ] ]
            ]
        ];
    }

    public function provideScalarsToConvert()
    {
        return [
            [
                null,
                [ null ],
            ],
            [
                false,
                [ false ],
            ],
            [
                true,
                [ true ],
            ],
            [
                3.1415927,
                [ 3.1415927 ],
            ],
            [
                0,
                [ 0 ],
            ],
            [
                100,
                [ 100 ],
            ],
            [
                "hello, world!",
                [ "hello, world!" ]
            ],
        ];
    }

    public function provideTraversablesToConvert()
    {
        // we need a few objects, to prove that BuildArray does deep
        // conversion
        $popo = new stdClass();
        $popo->fish = "trout";

        $dataBag1 = new DataBag;
        $dataBag1->value1 = $popo;

        return [
            [
                $dataBag1,
                [ "value1" => $popo ],
            ]
        ];
    }
}