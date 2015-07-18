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
 * @package   DataContainers/Filters
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-data-containers
 */

namespace GanbaroDigital\DataContainers\Filters;

use PHPUnit_Framework_TestCase;
use stdClass;

/**
 * @coversDefaultClass GanbaroDigital\DataContainers\Filters\FilterDotNotationPath
 */
class FilterDotNotationPathTest extends PHPUnit_Framework_TestCase
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

        $obj = new FilterDotNotationPath;

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof FilterDotNotationPath);
    }

    /**
     * @covers ::__invoke
     * @dataProvider provideContainersAndPaths
     */
    public function testCanUseAsObject($container, $path, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new FilterDotNotationPath;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($container, $path);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::fromMixed
     * @dataProvider provideContainersAndPaths
     */
    public function testCanCallStatically($container, $path, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = FilterDotNotationPath::fromMixed($container, $path, $expectedResult);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::fromMixed
     * @expectedException GanbaroDigital\DataContainers\Exceptions\E4xx_UnsupportedType
     * @dataProvider provideNonContainers
     */
    public function testThrowsExceptionWhenCalledStaticallyWithNonContainer($container)
    {
        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        FilterDotNotationPath::fromMixed($container, "one");
    }

    /**
     * @covers ::fromArray
     * @dataProvider provideArrayContainersAndPaths
     */
    public function testCanCallFromArrayStatically($container, $path, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = FilterDotNotationPath::fromArray($container, $path, $expectedResult);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::fromArray
     * @expectedException GanbaroDigital\DataContainers\Exceptions\E4xx_UnsupportedType
     * @dataProvider provideNonIndexableContainers
     */
    public function testThrowsExceptionWhenFromArrayCalledStaticallyWithNonIndexable($container)
    {
        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        FilterDotNotationPath::fromArray($container, "one");
    }

    /**
     * @covers ::fromObject
     * @dataProvider provideObjectContainersAndPaths
     */
    public function testCanCallFromObjectStatically($container, $path, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = FilterDotNotationPath::fromObject($container, $path, $expectedResult);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::fromObject
     * @expectedException GanbaroDigital\DataContainers\Exceptions\E4xx_UnsupportedType
     * @dataProvider provideNonAssignableContainers
     */
    public function testThrowsExceptionWhenFromObjectCalledStaticallyWithNonAssignable($container)
    {
        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        FilterDotNotationPath::fromObject($container, "one");
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

    public function provideNonAssignableContainers()
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

    public function provideNonIndexableContainers()
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
}