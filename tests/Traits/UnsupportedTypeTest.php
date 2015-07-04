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
 * @package   Exceptions/Traits
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-exceptions
 */

namespace GanbaroDigital\Exceptions\Traits;

use PHPUnit_Framework_TestCase;
use RuntimeException;

class Exxx_ExceptionException extends RuntimeException
{
    use ExceptionMessageData;

    public function __construct($code, $msg, $data)
    {
        $this->setMessageData($data);
        parent::__construct($msg, $code);
    }
}

// you can cut and paste this definition into your own libraries
class E4xx_UnsupportedType extends Exxx_ExceptionException
{
    use UnsupportedType;

    public function __construct($type, $level = 1)
    {
        // our list of args, in case someone wants to dig deeper into
        // what went wrong
        $data = $this->buildErrorData($type, $level);

        // what do we want to tell our error handler?
        $msg = $this->buildErrorMessage($data['type'], $data['caller']);

        // all done
        parent::__construct(400, $msg, $data);
    }
}

/**
 * @coversDefaultClass GanbaroDigital\Exceptions\Traits\UnsupportedType
 */
class UnsupportedTypeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers ::ensureString
     * @dataProvider provideListOfPhpTypes
     */
    public function testAutomaticallyHandlesTypesPassedIn($item)
    {
        // ----------------------------------------------------------------
        // setup your test

        $expectedType = is_string($item)? $item : gettype($item);

        // ----------------------------------------------------------------
        // perform the change

        $obj = new E4xx_UnsupportedType($item);

        // ----------------------------------------------------------------
        // test the results

        $actualArgs = $obj->getMessageData();
        $this->assertEquals($expectedType, $actualArgs['type']);
    }

    public function provideListOfPhpTypes()
    {
        return [
            [ null ],
            [ true ],
            [ false ],
            [ [ 'alfred' ] ],
            [ 3.1415927 ],
            [ 100 ],
            [ new \stdClass ],
            [ "hello, world!" ]
        ];
    }

    /**
     * @covers ::buildErrorData
     */
    public function testAutomaticallyWorksOutWhoIsThrowingTheException()
    {
        // ----------------------------------------------------------------
        // setup your test

        $expectedCaller = [
            get_class($this),
            'testAutomaticallyWorksOutWhoIsThrowingTheException',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $obj = new E4xx_UnsupportedType("NULL");

        // ----------------------------------------------------------------
        // test the results

        $actualArgs = $obj->getMessageData();
        $this->assertEquals($expectedCaller, $actualArgs['caller']);
    }

    /**
     * @covers ::buildErrorData
     */
    public function testSupportsUnwindingTheCallStackFurther()
    {
        // ----------------------------------------------------------------
        // setup your test

        $expectedCaller = [
            get_class($this),
            'testSupportsUnwindingTheCallStackFurther',
        ];

        $func = function() {
            return new E4xx_UnsupportedType("NULL", 2);
        };

        // ----------------------------------------------------------------
        // perform the change

        $obj = $func();

        // ----------------------------------------------------------------
        // test the results

        $actualArgs = $obj->getMessageData();
        $this->assertEquals($expectedCaller, $actualArgs['caller']);
    }

    /**
     * @covers ::buildErrorMessage
     */
    public function testAutomaticallyAddsThrowerDetailsIntoExceptionMessage()
    {
        // ----------------------------------------------------------------
        // setup your test

        $expectedMessage = "type 'NULL' is not supported by "
            .get_class($this)
            .'::testAutomaticallyAddsThrowerDetailsIntoExceptionMessage';

        // ----------------------------------------------------------------
        // perform the change

        $obj = new E4xx_UnsupportedType("NULL");

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedMessage, $obj->getMessage());
    }
}