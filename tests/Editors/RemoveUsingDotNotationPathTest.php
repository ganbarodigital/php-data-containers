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
 * @package   DataContainers/Editors
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-data-containers
 */

namespace GanbaroDigital\DataContainers\Editors;

use PHPUnit_Framework_TestCase;
use stdClass;

/**
 * @coversDefaultClass GanbaroDigital\DataContainers\Editors\RemoveUsingDotNotationPath
 */
class RemoveUsingDotNotationPathTest extends PHPUnit_Framework_TestCase
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

        $obj = new RemoveUsingDotNotationPath();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof RemoveUsingDotNotationPath);
    }

    /**
     * @covers ::__invoke
     * @covers ::from
     * @covers ::splitPathInTwo
     * @dataProvider provideContainersToTest
     */
    public function testCanUseAsObject($container, $propertyName, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new RemoveUsingDotNotationPath;

        // ----------------------------------------------------------------
        // perform the change

        $obj($container, $propertyName);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $container);
    }

    /**
     * @covers ::from
     * @covers ::splitPathInTwo
     * @dataProvider provideContainersToTest
     */
    public function testCanCallStatically($container, $propertyName, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        RemoveUsingDotNotationPath::from($container, $propertyName);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $container);
    }

    /**
     * @covers ::__invoke
     * @covers ::from
     * @dataProvider provideArrayContainersToTest
     */
    public function testCanRemoveFromArray($container, $propertyName, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new RemoveUsingDotNotationPath;

        // ----------------------------------------------------------------
        // perform the change

        $obj($container, $propertyName);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $container);
    }

    /**
     * @covers ::__invoke
     * @covers ::from
     * @dataProvider provideObjectContainersToTest
     */
    public function testCanRemoveFromObject($container, $propertyName, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new RemoveUsingDotNotationPath;

        // ----------------------------------------------------------------
        // perform the change

        $obj($container, $propertyName);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $container);
    }

    /**
     * @covers ::from
     * @expectedException GanbaroDigital\DataContainers\Exceptions\E4xx_UnsupportedType
     * @dataProvider provideNonIndexableNorAssignable
     */
    public function testThrowsExceptionWhenContainerIsNotIndexableNorAssignable($container)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new RemoveUsingDotNotationPath;
        $property = 100;

        // ----------------------------------------------------------------
        // perform the change

        $obj($container, $property);
    }

    public function provideNonIndexableNorAssignable()
    {
        return [
            [ null ],
            [ false ],
            [ true ],
            [ 3.1415927 ],
            [ 100 ],
            [ STDIN ],
            [ "traverse me!" ],
        ];
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
            "four" => (object) [ "fred" => (object)[ "harry" => "alice", "william" => "kate" ]],
        ];
        return $arrContainer;
    }

    public function provideArrayContainersToTest()
    {
        $arrContainer1a = $this->getArrayContainer();
        $arrContainer1b = $this->getArrayContainer();
        unset($arrContainer1b['three'][0]);

        $arrContainer2a = $this->getArrayContainer();
        $arrContainer2b = $this->getArrayContainer();
        unset($arrContainer2b['three'][1]);

        $arrContainer3a = $this->getArrayContainer();
        $arrContainer3b = $this->getArrayContainer();
        unset($arrContainer3b['three'][3]);

        return [
            [ $arrContainer1a, 'three.0', $arrContainer1b ],
            [ $arrContainer2a, 'three.1', $arrContainer2b ],
            [ $arrContainer3a, 'three.3', $arrContainer3b ],
        ];
    }

    public function provideObjectContainersToTest()
    {
        $objContainer1a = (object)$this->getArrayContainer();
        $objContainer1b = (object)$this->getArrayContainer();
        unset($objContainer1b->four->fred->harry);

        $objContainer2a = (object)$this->getArrayContainer();
        $objContainer2b = (object)$this->getArrayContainer();
        unset($objContainer2b->four->fred->william);

        return [
            [ $objContainer1a, 'four.fred.harry', $objContainer1b ],
            [ $objContainer2b, 'four.fred.william', $objContainer2b ],
        ];
    }
}