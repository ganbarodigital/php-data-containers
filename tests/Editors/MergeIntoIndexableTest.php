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

/**
 * @coversDefaultClass GanbaroDigital\DataContainers\Editors\MergeIntoIndexable
 */
class MergeIntoIndexableTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers ::fromArray
     * @covers ::mergeKeyIntoArray
     */
    public function testCanMergeSimpleArrays()
    {
        // ----------------------------------------------------------------
        // setup your test

        $ours = [ 'one' => 1, 'two' => 2];
        $theirs = [ 'three' => 3, 'four' => 4];

        $expectedResult = [ 'one' => 1, 'two' => 2, 'three' => 3, 'four' => 4];

        // ----------------------------------------------------------------
        // perform the change

        MergeIntoIndexable::fromArray($ours, $theirs);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $ours);
    }

    /**
     * @covers ::fromArray
     * @covers ::mergeKeyIntoArray
     */
    public function testCanMergeNestedArrays()
    {
        // ----------------------------------------------------------------
        // setup your test

        $ours = [ 'one' => 1, 'two' => 2];
        $theirs = [ 'three' => 3, 'four' => 4, 'five' => [ 1,2,3]];

        $expectedResult = [ 'one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => [1,2,3]];

        // ----------------------------------------------------------------
        // perform the change

        MergeIntoIndexable::fromArray($ours, $theirs);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $ours);
    }

    /**
     * @covers ::fromArray
     * @covers ::mergeKeyIntoArray
     */
    public function testCanMergeIntoSimpleArrays()
    {
        // ----------------------------------------------------------------
        // setup your test

        $ours = [ 'one' => 1, 'two' => 2, 'three' => ['three' => 3]];
        $theirs = [ 'three' => ['three' => 3, 'four' => 4]];

        $expectedResult = [ 'one' => 1, 'two' => 2, 'three' => ['three' => 3, 'four' => 4]];

        // ----------------------------------------------------------------
        // perform the change

        MergeIntoIndexable::fromArray($ours, $theirs);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $ours);
    }

    /**
     * @covers ::fromObject
     * @covers ::fromArray
     * @covers ::mergeKeyIntoArray
     */
    public function testCanMergeSimpleObject()
    {
        // ----------------------------------------------------------------
        // setup your test

        $ours = [ 'one' => 1, 'two' => 2];
        $theirs = (object)[ 'three' => 3, 'four' => 4];

        $expectedResult = [ 'one' => 1, 'two' => 2, 'three' => 3, 'four' => 4];

        // ----------------------------------------------------------------
        // perform the change

        MergeIntoIndexable::fromObject($ours, $theirs);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $ours);
    }

    /**
     * @covers ::fromObject
     * @covers ::fromArray
     * @covers ::mergeKeyIntoArray
     */
    public function testCanMergeNestedObjects()
    {
        // ----------------------------------------------------------------
        // setup your test

        $ours = [ 'one' => 1, 'two' => 2];
        $theirs = (object)[ 'three' => 3, 'four' => 4, 'five' => (object)[ 1,2,3]];

        $expectedResult = [ 'one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => (object)[1,2,3]];

        // ----------------------------------------------------------------
        // perform the change

        MergeIntoIndexable::fromObject($ours, $theirs);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $ours);
    }

    /**
     * @covers ::fromObject
     * @covers ::mergeKeyIntoArray
     */
    public function testCanMergeIntoSimpleObjects()
    {
        // ----------------------------------------------------------------
        // setup your test

        $ours = [ 'one' => 1, 'two' => 2, 'three' => (object)['three' => 3]];
        $theirs = (object)[ 'three' => ['three' => 3, 'four' => 4]];

        $expectedResult = [ 'one' => 1, 'two' => 2, 'three' => (object)['three' => 3, 'four' => 4]];

        // ----------------------------------------------------------------
        // perform the change

        MergeIntoIndexable::fromObject($ours, $theirs);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $ours);
    }

    /**
     * @covers ::fromObject
     * @covers ::fromArray
     * @dataProvider provideIncompatibleDataTypes
     */
    public function testIncompatibleTypesGetOverwritten($ours, $theirs, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        MergeIntoIndexable::fromArray($ours, $theirs);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $ours);
    }

    public function provideIncompatibleDataTypes()
    {
        return [
            [
                [
                    'one' => 1,
                    'two' => false,
                    'three' => 3.1415927,
                    'four' => 'hello, world',
                ],
                [
                    'one' => true,
                    'two' => 'two',
                    'three' => 3,
                    'four' => 4.567,
                ],
                [
                    'one' => true,
                    'two' => 'two',
                    'three' => 3,
                    'four' => 4.567,
                ]
            ],
        ];
    }

    /**
     * @covers ::fromArray
     * @expectedException GanbaroDigital\DataContainers\Exceptions\E4xx_UnsupportedType
     */
    public function testThrowsExceptionIfNonAssignablePassedIntoFromArray()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = false;

        // ----------------------------------------------------------------
        // perform the change

        MergeIntoIndexable::fromArray($data, []);
    }

    /**
     * @covers ::fromArray
     * @expectedException GanbaroDigital\DataContainers\Exceptions\E4xx_UnsupportedType
     */
    public function testThrowsExceptionIfNonTraversablePassedIntoFromArray()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = [];

        // ----------------------------------------------------------------
        // perform the change

        MergeIntoIndexable::fromArray($data, false);
    }

    /**
     * @covers ::fromObject
     * @expectedException GanbaroDigital\DataContainers\Exceptions\E4xx_UnsupportedType
     */
    public function testThrowsExceptionIfNonObjectPassedIntoFromObject()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = (object)[];

        // ----------------------------------------------------------------
        // perform the change

        MergeIntoIndexable::fromObject($data, false);
    }

    /**
     * @coversNothing
     */
    public function testCanInstantiate()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $obj = new MergeIntoIndexable();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof MergeIntoIndexable);
    }

    /**
     * @covers ::__invoke
     */
    public function testCanUseAsObject()
    {
        // ----------------------------------------------------------------
        // setup your test

        $ours1 = [];
        $ours2 = [];
        $theirs1 = ["one" => 1];
        $theirs2 = (object)["two" => 2];

        $obj = new MergeIntoIndexable();

        // ----------------------------------------------------------------
        // perform the change

        $obj($ours1, $theirs1);
        $obj($ours2, $theirs2);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($ours1, $theirs1);
        $this->assertEquals($ours2, (array)$theirs2);
    }

    /**
     * @covers ::from
     */
    public function testCanStaticallyCallStatically()
    {
        // ----------------------------------------------------------------
        // setup your test

        $ours1 = [];
        $ours2 = [];
        $theirs1 = ["one" => 1];
        $theirs2 = (object)["two" => 2];

        // ----------------------------------------------------------------
        // perform the change

        MergeIntoIndexable::from($ours1, $theirs1);
        MergeIntoIndexable::from($ours2, $theirs2);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($ours1, $theirs1);
        $this->assertEquals($ours2, (array)$theirs2);
    }

    /**
     * @covers ::from
     * @expectedException GanbaroDigital\DataContainers\Exceptions\E4xx_UnsupportedType
     * @dataProvider provideUnsupportedTypes
     */
    public function testStaticCallThrowsExceptionWhenUnsupportedTypeProvidedAsFirstArg($ours)
    {
        // ----------------------------------------------------------------
        // setup your test

        $theirs = (object)[];

        // ----------------------------------------------------------------
        // perform the change

        MergeIntoIndexable::from($ours, $theirs);
    }

    /**
     * @covers ::from
     * @expectedException GanbaroDigital\DataContainers\Exceptions\E4xx_UnsupportedType
     * @dataProvider provideUnsupportedTypes
     */
    public function testStaticCallThrowsExceptionWhenUnsupportedTypeProvidedAsSecondArg($theirs)
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = [];

        // ----------------------------------------------------------------
        // perform the change

        MergeIntoIndexable::from($data, $theirs);
    }

    public function provideUnsupportedTypes()
    {
        return [
            [ null ],
            [ true ],
            [ false ],
            [ 3.1415927 ],
            [ 100 ],
            [ fopen("php://input", "r") ],
            [ "hello, world" ]
        ];
    }

}