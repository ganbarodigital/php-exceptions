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

class NonCheckCodeCaller
{
    public static function fromBacktrace($backtrace)
    {
        static $dataToExtract = [
            'class',
            'function',
            'file',
            'line',
        ];

        $retval = [null, null, null, null, 'class' => null, 'function' => null, 'file' => null, 'line' => null];

        $frame = self::findCaller($backtrace);

        foreach ($dataToExtract as $index => $key) {
            if (isset($frame[$key])) {
                $retval[$index] = $frame[$key];
                $retval[$key] = $frame[$key];
            }
        }

        return $retval;
    }

    /**
     * find the caller that we want
     *
     * @param  array $backtrace
     *         the backtrace to examine
     * @return array
     */
    private static function findCaller($backtrace)
    {
        foreach ($backtrace as $frame) {
            if (!isset($frame['function'])) {
                continue;
            }

            if (!isset($frame['class'])) {
                return $frame;
            }

            if (self::isClassNameOkay($frame['class'])) {
                return $frame;
            }
        }

        // if we get here, then we have run out of places to look
        return $backtrace[1];
    }

    private static function isClassNameOkay($className)
    {
        // special case - called from a test
        if (substr($className, -4, 4) == 'Test') {
            return true;
        }

        // general case
        //
        // this also deals with the situation where one of our blacklisted
        // namespaces has namespaces inside it
        $parts = explode('\\', $className);
        if (empty(array_intersect(self::$blacklistedNamespaces, $parts))) {
            return true;
        }

        // if we get here, then this class isn't one that we want to return
        // to the caller
        return false;
    }

    /**
     * a list of the namespaces that we skip
     * @var array
     */
    private static $blacklistedNamespaces = [
        'Checks' => 'Checks',
        'Exceptions' => 'Exceptions',
        'Requirements' => 'Requirements',
    ];
}