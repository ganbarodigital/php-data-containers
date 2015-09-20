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

/**
 * @coversDefaultClass GanbaroDigital\DataContainers\Filters\FilterDotNotationParts
 */
class FilterDotNotationPartsTest extends PHPUnit_Framework_TestCase
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

        $obj = new FilterDotNotationParts;

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof FilterDotNotationParts);
    }

    /**
     * @covers ::__invoke
     * @covers ::from
     * @covers ::fromString
     */
    public function testCanUseAsObject()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new FilterDotNotationParts;
        $expectedResult = 'dot.notation.support';

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj('dot.notation.support', 0, 3);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::from
     * @covers ::fromString
     */
    public function testCanCallStatically()
    {
        // ----------------------------------------------------------------
        // setup your test

        $expectedResult = 'dot.notation.support';

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = FilterDotNotationParts::from($expectedResult, 0, 3);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::from
     * @covers ::fromString
     */
    public function testCanGetStartOfPath()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = 'dot.notation.support';
        $expectedResult = 'dot';

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = FilterDotNotationParts::from($data, 0, 1);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::from
     * @covers ::fromString
     */
    public function testCanGetEndOfPath()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = 'dot.notation.support';
        $expectedResult = 'support';

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = FilterDotNotationParts::from($data, -1, 1);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::from
     * @covers ::fromString
     * @expectedException GanbaroDigital\DataContainers\Exceptions\E4xx_UnsupportedType
     * @dataProvider provideNonIntegers
     */
    public function testStartMustBeAnInteger($start)
    {
        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        FilterDotNotationParts::from('dot.notation.support', $start, 1);
    }

    /**
     * @covers ::from
     * @covers ::fromString
     * @expectedException GanbaroDigital\DataContainers\Exceptions\E4xx_UnsupportedType
     * @dataProvider provideNonIntegers
     */
    public function testLenMustBeAnInteger($len)
    {
        // ----------------------------------------------------------------
        // setup your test



        // ----------------------------------------------------------------
        // perform the change

        FilterDotNotationParts::from('dot.notation.support', 0, $len);
    }

    public function provideNonIntegers()
    {
        return [
            [ null ],
            [ false ],
            [ true ],
            [ [ ] ],
            [ 3.1415927 ],
            [ new \stdClass ],
        ];
    }
}