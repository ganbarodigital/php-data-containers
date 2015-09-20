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
 * @package   DataContainers/Checks
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-data-containers
 */

namespace GanbaroDigital\DataContainers\Checks;

use PHPUnit_Framework_TestCase;
use stdClass;

/**
 * @coversDefaultClass GanbaroDigital\DataContainers\Checks\HasUsingDotNotationPath
 */
class HasUsingDotNotationPathTest extends PHPUnit_Framework_TestCase
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

        $obj = new HasUsingDotNotationPath;

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof HasUsingDotNotationPath);
    }

    /**
     * @covers ::__invoke
     * @dataProvider provideContainersToTest
     */
    public function testCanUseAsObject($container, $path, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new HasUsingDotNotationPath;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($container, $path, $expectedResult);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::in
     * @dataProvider provideContainersToTest
     */
    public function testCanCallStatically($container, $path, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = HasUsingDotNotationPath::in($container, $path);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::inArray
     * @dataProvider provideArrayContainersToTest
     */
    public function testCanSearchInsideArrayContainers($container, $path, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = HasUsingDotNotationPath::inArray($container, $path);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::inObject
     * @dataProvider provideObjectContainersToTest
     */
    public function testCanSearchInsideObjectContainers($container, $path, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = HasUsingDotNotationPath::inObject($container, $path);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function provideContainersToTest()
    {
        return array_merge(
            $this->provideArrayContainersToTest(),
            $this->provideObjectContainersToTest()
        );
    }

    public function getArrayContainer()
    {
        $arrContainer = [
            "one" => 1,
            "two" => 2,
            "three" => [ 1, 2, 3 => [ 4, 5, 6 ]],
            "four" => (object) [ "fred" => [ "harry" => "alice", "william" => "kate" ]],
        ];
        return $arrContainer;
    }

    public function provideArrayContainersToTest()
    {
        $arrContainer = $this->getArrayContainer();

        return [
            [ $arrContainer, 'one', true ], // value is 1
            [ $arrContainer, 'two', true ], // value is 2
            [ $arrContainer, 'three' , true ], // value is array
            [ $arrContainer, 'three.0', true ], // value is 1
            [ $arrContainer, 'three.1', true ], // value is 2
            [ $arrContainer, 'three.3', true ], // value is array
            [ $arrContainer, 'three.3.0', true ], // value is 4
            [ $arrContainer, 'three.3.1', true ], // value is 5
            [ $arrContainer, 'three.3.2', true ], // value is 6
            [ $arrContainer, 'four', true ], // value is object
            [ $arrContainer, 'four.fred', true ], // value is array
            [ $arrContainer, 'four.fred.harry', true ], // value is alice
            [ $arrContainer, 'four.fred.william', true ], // value is kate
            [ $arrContainer, 'zero', false ],
            [ $arrContainer, 'three.100', false ],
            [ $arrContainer, 'four.harry', false ],
            [ $arrContainer, 'four.fred.charles', false ],
        ];
    }

    public function provideObjectContainersToTest()
    {
        $objContainer = (object)$this->getArrayContainer();

        return [
            [ $objContainer, 'one', true ], // value is 1
            [ $objContainer, 'two', true ], // value is 2
            [ $objContainer, 'three' , true ], // value is array
            [ $objContainer, 'three.0', true ], // value is 1
            [ $objContainer, 'three.1', true ], // value is 2
            [ $objContainer, 'three.3', true ], // value is array
            [ $objContainer, 'three.3.0', true ], // value is 4
            [ $objContainer, 'three.3.1', true ], // value is 5
            [ $objContainer, 'three.3.2', true ], // value is 6
            [ $objContainer, 'four', true ], // value is object
            [ $objContainer, 'four.fred', true ], // value is array
            [ $objContainer, 'four.fred.harry', true ], // value is alice
            [ $objContainer, 'four.fred.william', true ], // value is kate
            [ $objContainer, 'zero', false ],
            [ $objContainer, 'three.100', false ],
            [ $objContainer, 'four.harry', false ],
            [ $objContainer, 'four.fred.charles', false ],
        ];
    }
}