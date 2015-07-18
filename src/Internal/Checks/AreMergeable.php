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
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-data-containers
 */

namespace GanbaroDigital\DataContainers\Internal\Checks;

use GanbaroDigial\DataContainers\Exceptions\E4xx_UnsupportedType;
use GanbaroDigital\Reflection\Checks\IsAssignable;
use GanbaroDigital\Reflection\Checks\IsIndexable;
use GanbaroDigital\Reflection\Checks\IsTraversable;
use GanbaroDigital\Reflection\ValueBuilders\FirstMethodMatchingType;

class AreMergeable
{
    /**
     * does it make sense to attempt to merge the contents of $theirs into
     * $ours?
     *
     * @param  mixed $ours
     *         where we want to merge to
     * @param  mixed $theirs
     *         where we want to merge from
     * @return boolean
     *         true if merging makes sense
     *         false otherwise
     */
    public static function intoMixed($ours, $theirs)
    {
        // if we can't traverse over theirs, no good
        if (!IsTraversable::checkMixed($theirs)) {
            return false;
        }

        // if we have arrays or databag-type objects, we're good
        if (IsIndexable::checkMixed($ours) || IsAssignable::checkMixed($ours)) {
            return true;
        }

        // if we get here, then $ours is a complex object, which
        // we do not know how to merge
        //
        // or it is a scalar, which doesn't support merging at all
        return false;
    }

    /**
     * does it make sense to attempt to merge the contents of $theirs into
     * $ours?
     *
     * @param  mixed $ours
     *         where we want to merge to
     * @param  mixed $theirs
     *         where we want to merge from
     * @return boolean
     *         true if merging makes sense
     *         false otherwise
     */
    public function __invoke($ours, $theirs)
    {
        return self::intoMixed($ours, $theirs);
    }

}