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
 * @coversDefaultClass GanbaroDigital\DataContainers\Editors\RemoveProperty
 */
class RemovePropertyTest extends PHPUnit_Framework_TestCase
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

        $obj = new RemoveProperty();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof RemoveProperty);
    }

    /**
     * @covers ::__invoke
     * @dataProvider provideContainersToTest
     */
    public function testCanUseAsObject($container, $propertyName, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new RemoveProperty;

        // ----------------------------------------------------------------
        // perform the change

        $obj($container, $propertyName);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $container);
    }

    /**
     * @covers ::from
     * @dataProvider provideContainersToTest
     */
    public function testCanCallStatically($container, $propertyName, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        RemoveProperty::from($container, $propertyName);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $container);
    }

    /**
     * @covers ::__invoke
     * @covers ::from
     * @covers ::fromArray
     * @dataProvider provideArrayContainersToTest
     */
    public function testCanRemoveFromArray($container, $propertyName, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new RemoveProperty;

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
     * @covers ::fromObject
     * @dataProvider provideObjectContainersToTest
     */
    public function testCanRemoveFromObject($container, $propertyName, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new RemoveProperty;

        // ----------------------------------------------------------------
        // perform the change

        $obj($container, $propertyName);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $container);
    }

    /**
     * @covers ::nothingMatchesTheInputType
     * @expectedException GanbaroDigital\DataContainers\Exceptions\E4xx_UnsupportedType
     * @dataProvider provideNonIndexableNorAssignable
     */
    public function testThrowsExceptionWhenContainerIsNotIndexableNorAssignable($container)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new RemoveProperty;
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
        unset($arrContainer1b['one']);

        $arrContainer2a = $this->getArrayContainer();
        $arrContainer2b = $this->getArrayContainer();
        unset($arrContainer2b['two']);

        $arrContainer3a = $this->getArrayContainer();
        $arrContainer3b = $this->getArrayContainer();
        unset($arrContainer3b['three']);

        $arrContainer4a = $this->getArrayContainer()['three'];
        $arrContainer4b = $this->getArrayContainer()['three'];
        unset($arrContainer4b[0]);

        $arrContainer5a = $this->getArrayContainer()['three'];
        $arrContainer5b = $this->getArrayContainer()['three'];
        unset($arrContainer5b[1]);

        $arrContainer6a = $this->getArrayContainer()['three'];
        $arrContainer6b = $this->getArrayContainer()['three'];
        unset($arrContainer6b[3]);

        return [
            [ $arrContainer1a, 'one', $arrContainer1b ],
            [ $arrContainer2a, 'two', $arrContainer2b ],
            [ $arrContainer3a, 'three' , $arrContainer3b ],
            [ $arrContainer4a, 0, $arrContainer4b ],
            [ $arrContainer5a, 1, $arrContainer5b ],
            [ $arrContainer6a, 3, $arrContainer6b ],
        ];
    }

    public function provideObjectContainersToTest()
    {
        $objContainer1a = (object)$this->getArrayContainer();
        $objContainer1b = (object)$this->getArrayContainer();
        unset($objContainer1b->one);

        $objContainer2a = (object)$this->getArrayContainer();
        $objContainer2b = (object)$this->getArrayContainer();
        unset($objContainer2b->two);

        $objContainer3a = (object)$this->getArrayContainer();
        $objContainer3b = (object)$this->getArrayContainer();
        unset($objContainer3b->four);

        $objContainer = (object)$this->getArrayContainer();
        $objContainer4a = clone $objContainer->four->fred;
        $objContainer4b = clone $objContainer->four->fred;
        unset($objContainer4b->harry);

        $objContainer = (object)$this->getArrayContainer();
        $objContainer5a = clone $objContainer->four->fred;
        $objContainer5b = clone $objContainer->four->fred;
        unset($objContainer5b->william);

        return [
            [ $objContainer1a, 'one', $objContainer1b ],
            [ $objContainer2a, 'two', $objContainer2b ],
            [ $objContainer3a, 'four' , $objContainer3b ],
            [ $objContainer4a, 'harry', $objContainer4b ],
            [ $objContainer5b, 'william', $objContainer5b ],
        ];
    }
}