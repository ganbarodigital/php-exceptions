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

use GanbaroDigital\Exceptions\ValueBuilders\CodeCaller;
use GanbaroDigital\Exceptions\ValueBuilders\NonCheckCodeCaller;

trait ExceptionCaller
{
    /**
     * work out who is throwing the exception
     *
     * @param  int $level
     *         how deep into the backtrace we need to go
     *
     *         this is relative to the caller
     * @return array
     *         the calling class, and the calling method
     */
    private function getCaller($level = 1)
    {
        // let's find out who is trying to throw this exception
        $backtrace = debug_backtrace();
        return CodeCaller::fromBacktrace($backtrace, $level + 1);
    }

    /**
     * work out who is throwing the exception
     *
     * we unwind the stack, looking for code that is NOT in these namespaces:
     *
     * - Checks
     * - Exceptions
     * - Requirements
     *
     * @return array
     *         the calling class, and the calling method
     */
    private function getNonCheckCaller()
    {
        $backtrace = debug_backtrace();
        return NonCheckCodeCaller::fromBacktrace($backtrace);
    }
}