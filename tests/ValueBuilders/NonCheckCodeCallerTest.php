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
 * @package   Exceptions/ValueBuilders
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-exceptions
 */

namespace GanbaroDigital\Exceptions\ValueBuilders;

use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass GanbaroDigital\Exceptions\ValueBuilders\NonCheckCodeCaller
 */
class NonCheckCodeCallerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers ::fromBacktrace
     */
    public function testRetrievesClassAndMethod()
    {
        // ----------------------------------------------------------------
        // setup your test

        $backtrace = $this->getBacktrace();

        $expectedClass = 'GanbaroDigital\\Reflection\\Jackpot\\JackpotClass';
        $expectedMethod = 'from';

        // ----------------------------------------------------------------
        // perform the change

        list($actualClass, $actualMethod) = NonCheckCodeCaller::fromBacktrace($backtrace);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedClass, $actualClass);
        $this->assertEquals($expectedMethod, $actualMethod);
    }

    /**
     * @covers ::fromBacktrace
     */
    public function testRetrievesFileAndLineToo()
    {
        // ----------------------------------------------------------------
        // setup your test

        $backtrace = $this->getBacktrace();

        $expectedFile = '/tmp/jackpot';
        $expectedLine = 31415;

        // ----------------------------------------------------------------
        // perform the change

        $actualCaller = NonCheckCodeCaller::fromBacktrace($backtrace);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedFile, $actualCaller[2]);
        $this->assertEquals($expectedLine, $actualCaller[3]);
    }

    public function getBacktrace()
    {
        return [
            [
                'file' => '/tmp/1',
                'line' => 100,
                'function' => 'raise',
                'class' => 'GanbaroDigital\\Reflection\\Exceptions\\E4xx_UnsupportedType',
                'type' => '->',
            ],
            [
                'file' => '/tmp/2',
                'line' => 235,
                'function' => 'checkString',
                'class' => 'GanbaroDigital\\Reflection\\Checks\\IsStringy',
                'type' => '->',
            ],
            [
                'file' => '/tmp/3',
                'line' => 9999,
                'function' => 'check',
                'class' => 'GanbaroDigital\\Reflection\\Checks\\IsStringy',
                'type' => '->',
            ],
            [
                'file' => '/tmp/4',
                'line' => 1023,
                'function' => 'check',
                'class' => 'GanbaroDigital\\Reflection\\Requirements\\RequireStringy',
                'type' => '->',
            ],
            [
                'file' => '/tmp/jackpot',
                'line' => 31415,
                'function' => 'from',
                'class' => 'GanbaroDigital\\Reflection\\Jackpot\\JackpotClass',
                'type' => '->',
            ],
        ];
    }
}