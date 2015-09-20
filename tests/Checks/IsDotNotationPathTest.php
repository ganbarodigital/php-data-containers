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
 * @coversDefaultClass GanbaroDigital\DataContainers\Checks\IsDotNotationPath
 */
class IsDotNotationPathTest extends PHPUnit_Framework_TestCase
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

        $obj = new IsDotNotationPath;

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof IsDotNotationPath);
    }

    /**
     * @covers ::__invoke
     * @covers ::check
     */
    public function testCanUseAsObject()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsDotNotationPath;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj('dot.notation.support');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult);
    }

    /**
     * @covers ::checkString
     */
    public function testCanCallStatically()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = IsDotNotationPath::check('dot.notation.support');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult);
    }

    /**
     * @covers ::checkString
     * @covers ::hasDotInAcceptablePlace
     */
    public function testChecksForDotNotation()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $IsDotNotationPath = IsDotNotationPath::check('dot.notation.support');
        $isNotDotNotation = IsDotNotationPath::check('dotNotationSupport');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($IsDotNotationPath);
        $this->assertFalse($isNotDotNotation);
    }

    /**
     * @covers ::checkString
     * @covers ::hasDotInAcceptablePlace
     */
    public function testRejectsWhenOnlyDotIsAtEndOfString()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $isNotDotNotation = IsDotNotationPath::check('dotNotationSupport.');

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($isNotDotNotation);
    }

    /**
     * @covers ::checkString
     * @covers ::nothingMatchesTheInputType
     * @expectedException GanbaroDigital\DataContainers\Exceptions\E4xx_UnsupportedType
     * @dataProvider provideNonStrings
     */
    public function testThrowsExceptionIfPathIsNotAString($path)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        IsDotNotationPath::check($path);
    }

    public function provideNonStrings()
    {
        return [
            [ null ],
            [ true ],
            [ false ],
            [ [ ] ],
            [ 3.1415927 ],
            [ 100 ],
            [ new stdClass ],
            [ fopen("php://input", "r") ],
        ];
    }
}