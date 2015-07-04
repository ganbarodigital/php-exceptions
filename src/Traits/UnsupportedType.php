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

trait UnsupportedType
{
    use ExceptionCaller;

    /**
     * @param string $type
     *        result of calling gettype() on the unsupported item
     * @param integer $level
     *        how far up the call stack to go
     * @return array
     */
    private function buildErrorData($type, $level = 1)
    {
        // our list of args, in case someone wants to dig deeper into
        // what went wrong
        $data = [];

        // special case - someone passed us the original item, rather than
        // the type of the item
        //
        // we do this conversion to avoid a fatal PHP error
        $data['type'] = $this->ensureString($type);

        // let's find out who is trying to throw this exception
        // as we are a nested function, we need to look 1 deeper into
        // the call stack to find the true caller
        $data['caller'] = $this->getCaller($level + 1);

        // all done
        return $data;
    }

    /**
     * make sure that we have a string for our message
     *
     * @param  mixed $type
     *         the item to check
     * @return string
     *         the original string, or the type of $type
     */
    private function ensureString($type)
    {
        if (!is_string($type)) {
            $type = gettype($type);
        }

        return $type;
    }

    /**
     * create the error message to add to the exception
     *
     * @param  string $type
     *         the data type that the thrower does not support
     * @param  array $caller
     *         details about who is throwing the exception
     * @return string
     */
    private function buildErrorMessage($type, $caller)
    {
        $msg = "type '{$type}' is not supported by ";
        if ($caller[0]) {
            $msg .= $caller[0];
        }
        if ($caller[1]) {
            $msg .= "::{$caller[1]}";
        }

        return $msg;
    }

}