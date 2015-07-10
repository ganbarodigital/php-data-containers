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
 * @package   DataContainers/Internal
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-data-containers
 */

namespace GanbaroDigital\DataContainers\Internal\Checks;

use PHPUnit_Framework_TestCase;
use GanbaroDigital\Reflection\ValueBuilders\FirstMethodMatchingType;
use stdClass;

/**
 * @coversDefaultClass GanbaroDigital\DataContainers\Internal\Checks\ShouldOverwrite
 */
class ShouldOverwriteTest extends PHPUnit_Framework_TestCase
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

        $obj = new ShouldOverwrite;

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof ShouldOverwrite);
    }

    /**
     * @covers ::__invoke
     * @covers ::intoMixed
     */
    public function testCanUseAsObject()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new ShouldOverwrite;
        $data1 = [];
        $data2 = 3.1415927;
        $expectedResult = true;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($data1, 'noSuchProperty', $data2);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::intoMixed
     * @expectedException GanbaroDigital\DataContainers\Exceptions\E4xx_UnsupportedType
     * @dataProvider provideNonIndexableNorAssignable
     */
    public function testOursMustBeIndexableOrAssignable($ours)
    {
        // ----------------------------------------------------------------
        // setup your test

        $data2 = [];

        // ----------------------------------------------------------------
        // perform the change

        ShouldOverwrite::intoMixed($ours, 'noSuchProperty', $data2);
    }

    public function provideNonIndexableNorAssignable()
    {
        return [
            [ null ],
            [ false ],
            [ true ],
            [ 3.1415927 ],
            [ 100 ],
            [ new AreMergeable ],
            [ fopen("php://input", "r") ],
            [ "traverse me!" ],
        ];
    }

    /**
     * @covers ::intoMixed
     * @covers ::intoArray
     */
    public function testReturnsTrueWhenOurArrayIndexNotFound()
    {
        // ----------------------------------------------------------------
        // setup your test

        $ours = [];
        $property = 'doesNotExist';
        $theirs = 100;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = ShouldOverwrite::intoMixed($ours, $property, $theirs);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult);
    }

    /**
     * @covers ::__invoke
     * @covers ::intoMixed
     * @covers ::intoArray
     * @dataProvider provideNonMergeables
     */
    public function testReturnsTrueWhenOurArrayContainsNonMergeableType($data)
    {
        // ----------------------------------------------------------------
        // setup your test

        $property = 'key';
        $ours = [ $property => $data ];
        $theirs = [];

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = ShouldOverwrite::intoMixed($ours, $property, []);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult);
    }

    public function provideNonMergeables()
    {
        return [
            [ null ],
            [ false ],
            [ true ],
            [ 3.1415927 ],
            [ 100 ],
            [ new AreMergeable ],
            [ fopen("php://input", "r") ],
            [ "traverse me!" ],
        ];
    }

    /**
     * @covers ::__invoke
     * @covers ::intoMixed
     * @covers ::intoObject
     * @dataProvider provideNonMergeables
     */
    public function testReturnsTrueWhenOurObjectContainsNonMergeableType($data)
    {
        // ----------------------------------------------------------------
        // setup your test

        $property = 'key';
        $ours = (object)[ $property => $data ];
        $theirs = [];

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = ShouldOverwrite::intoMixed($ours, $property, []);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult);
    }
    /**
     * @covers ::__invoke
     * @covers ::intoMixed
     * @covers ::intoArray
     * @covers ::intoObject
     * @dataProvider provideNonMergeables
     */
    public function testReturnsTrueWhenTheirDataIsNotMergeable($theirs)
    {
        // ----------------------------------------------------------------
        // setup your test

        $property = 'key';
        $ours1 = [ $property => [] ];
        $ours2 = (object)$ours1;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult1 = ShouldOverwrite::intoMixed($ours1, $property, $theirs);
        $actualResult2 = ShouldOverwrite::intoMixed($ours2, $property, $theirs);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult1);
        $this->assertTrue($actualResult2);
    }

    /**
     * @covers ::intoArray
     * @dataProvider provideNonIndexable
     * @expectedException GanbaroDigital\DataContainers\Exceptions\E4xx_UnsupportedType
     */
    public function testThrowsExceptionWhenNonArrayPassedIntoIsArray($data)
    {
        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        ShouldOverwrite::intoArray($data, 'doesNotMatter', []);
    }

    public function provideNonIndexable()
    {
        return [
            [ null ],
            [ false ],
            [ true ],
            [ 3.1415927 ],
            [ 100 ],
            [ new AreMergeable ],
            [ new stdClass ],
            [ fopen("php://input", "r") ],
            [ "traverse me!" ],
        ];
    }

    /**
     * @covers ::intoObject
     * @dataProvider provideNonAssignable
     * @expectedException GanbaroDigital\DataContainers\Exceptions\E4xx_UnsupportedType
     */
    public function testThrowsExceptionWhenNonAssignablePassedIntoIsObject($data)
    {
        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        ShouldOverwrite::intoObject($data, 'doesNotMatter', []);

    }

    public function provideNonAssignable()
    {
        return [
            [ null ],
            [ [] ],
            [ false ],
            [ true ],
            [ 3.1415927 ],
            [ 100 ],
            [ new AreMergeable ],
            [ fopen("php://input", "r") ],
            [ "traverse me!" ],
        ];
    }

}