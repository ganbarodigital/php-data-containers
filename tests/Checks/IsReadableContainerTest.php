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

use ArrayObject;
use GanbaroDigital\DataContainers\Containers\DataBag;
use PHPUnit_Framework_TestCase;
use stdClass;

/**
 * @coversDefaultClass GanbaroDigital\DataContainers\Checks\IsReadableContainer
 */
class IsReadableContainerTest extends PHPUnit_Framework_TestCase
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

        $obj = new IsReadableContainer;

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof IsReadableContainer);
    }

    /**
     * @covers ::__invoke
     * @covers ::checkMixed
     */
    public function testCanUseAsObject()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new IsReadableContainer;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj([]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult);
    }

    /**
     * @covers ::checkMixed
     */
    public function testCanCallStatically()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = IsReadableContainer::checkMixed([]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult);
    }

    /**
     * @covers ::checkMixed
     */
    public function testReportsArraysAsReadableContainers()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = IsReadableContainer::checkMixed([]);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult);
    }

    /**
     * @covers ::checkMixed
     */
    public function testReportsArrayObjectsAsReadableContainers()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = new ArrayObject([]);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = IsReadableContainer::checkMixed($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult);
    }

    /**
     * @covers ::checkMixed
     */
    public function testReportsDataBagAsReadableContainers()
    {
        // ----------------------------------------------------------------
        // setup your test

        $data = new DataBag;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = IsReadableContainer::checkMixed($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($actualResult);
    }

    /**
     * @covers ::checkMixed
     * @dataProvider provideScalars
     */
    public function testReportsScalarsAsNotReadableContainers($data)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = IsReadableContainer::checkMixed($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($actualResult);
    }


    public function provideScalars()
    {
        return [
            [ null ],
            [ true ],
            [ false ],
            [ 3.1415927 ],
            [ 100 ],
            [ fopen("php://input", "r") ],
            [ "hello, world!" ]
        ];
    }
}