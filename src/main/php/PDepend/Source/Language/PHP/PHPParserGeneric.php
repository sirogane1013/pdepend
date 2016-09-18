<?php
/**
 * This file is part of PDepend.
 *
 * PHP Version 5
 *
 * Copyright (c) 2008-2015, Manuel Pichler <mapi@pdepend.org>.
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
 *   * Neither the name of Manuel Pichler nor the names of his
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
 * @copyright 2008-2015 Manuel Pichler. All rights reserved.
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @since 0.9.20
 */

namespace PDepend\Source\Language\PHP;

use PDepend\Source\Tokenizer\Tokens;

/**
 * Concrete parser implementation that is very tolerant and accepts language
 * constructs and keywords that are reserved in newer php versions, but not in
 * older versions.
 *
 * @copyright 2008-2015 Manuel Pichler. All rights reserved.
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @since 0.9.20
 */
class PHPParserGeneric extends PHPParserVersion70
{
    /* Keyword test methods {{{ */

    /**
     * Will return <b>true</b> if the given <b>$tokenType</b> is a valid class
     * name part.
     *
     * @param integer $tokenType
     * @return boolean
     * @since 0.10.6
     */
    protected function isClassName($tokenType)
    {
        switch ($tokenType) {
            case Tokens::T_DIR:
            case Tokens::T_USE:
            case Tokens::T_GOTO:
            case Tokens::T_NULL:
            case Tokens::T_NS_C:
            case Tokens::T_TRUE:
            case Tokens::T_CLONE:
            case Tokens::T_FALSE:
            case Tokens::T_TRAIT:
            case Tokens::T_STRING:
            case Tokens::T_TRAIT_C:
            case Tokens::T_CALLABLE:
            case Tokens::T_INSTEADOF:
            case Tokens::T_NAMESPACE:
                return true;
        }
        return false;
    }

    /**
     * Tests if the give token is a valid constant name in the supported PHP
     * version.
     *
     * @param integer $tokenType
     * @return boolean
     */
    protected function isConstantName($tokenType)
    {
        switch ($tokenType) {
            case Tokens::T_NULL:
            case Tokens::T_SELF:
            case Tokens::T_TRUE:
            case Tokens::T_FALSE:
            case Tokens::T_STRING:
            case Tokens::T_PARENT:
            case Tokens::T_ABSTRACT:
            case Tokens::T_ARRAY:
            case Tokens::T_AS:
            case Tokens::T_BREAK:
            case Tokens::T_CALLABLE:
            case Tokens::T_CASE:
            case Tokens::T_CATCH:
            case Tokens::T_CLASS_C:
            case Tokens::T_CLONE:
            case Tokens::T_CONST:
            case Tokens::T_CONTINUE:
            case Tokens::T_DECLARE:
            case Tokens::T_DEFAULT:
            case Tokens::T_DIR:
            case Tokens::T_DO:
            case Tokens::T_ECHO:
            case Tokens::T_ELSE:
            case Tokens::T_ELSEIF:
            case Tokens::T_EMPTY:
            case Tokens::T_ENDDECLARE:
            case Tokens::T_ENDFOR:
            case Tokens::T_ENDFOREACH:
            case Tokens::T_ENDIF:
            case Tokens::T_ENDSWITCH:
            case Tokens::T_ENDWHILE:
            case Tokens::T_EVAL:
            case Tokens::T_EXIT:
            case Tokens::T_EXTENDS:
            case Tokens::T_FILE:
            case Tokens::T_FINAL:
            case Tokens::T_FINALLY:
            case Tokens::T_FOR:
            case Tokens::T_FOREACH:
            case Tokens::T_FUNCTION:
            case Tokens::T_FUNC_C:
            case Tokens::T_GLOBAL:
            case Tokens::T_GOTO:
            case Tokens::T_HALT_COMPILER:
            case Tokens::T_IF:
            case Tokens::T_IMPLEMENTS:
            case Tokens::T_INCLUDE:
            case Tokens::T_INCLUDE_ONCE:
            case Tokens::T_INSTANCEOF:
            case Tokens::T_INSTEADOF:
            case Tokens::T_INTERFACE:
            case Tokens::T_ISSET:
            case Tokens::T_LINE:
            case Tokens::T_LIST:
            case Tokens::T_LOGICAL_AND:
            case Tokens::T_LOGICAL_OR:
            case Tokens::T_LOGICAL_XOR:
            case Tokens::T_METHOD_C:
            case Tokens::T_NAMESPACE:
            case Tokens::T_NEW:
            case Tokens::T_NS_C:
            case Tokens::T_PRINT:
            case Tokens::T_PRIVATE:
            case Tokens::T_PROTECTED:
            case Tokens::T_PUBLIC:
            case Tokens::T_REQUIRE:
            case Tokens::T_REQUIRE_ONCE:
            case Tokens::T_RETURN:
            case Tokens::T_STATIC:
            case Tokens::T_SWITCH:
            case Tokens::T_THROW:
            case Tokens::T_TRAIT:
            case Tokens::T_TRAIT_C:
            case Tokens::T_TRY:
            case Tokens::T_UNSET:
            case Tokens::T_USE:
            case Tokens::T_VAR:
            case Tokens::T_WHILE:
            case Tokens::T_YIELD:
                return true;
        }
        return false;
    }

    /**
     * Tests if the give token is a valid function name in the supported PHP
     * version.
     *
     * @param integer $tokenType
     * @return boolean
     */
    protected function isFunctionName($tokenType)
    {
        switch ($tokenType) {
            case Tokens::T_NULL:
            case Tokens::T_SELF:
            case Tokens::T_TRUE:
            case Tokens::T_FALSE:
            case Tokens::T_STRING:
            case Tokens::T_PARENT:
            case Tokens::T_ABSTRACT:
            case Tokens::T_ARRAY:
            case Tokens::T_AS:
            case Tokens::T_BREAK:
            case Tokens::T_CALLABLE:
            case Tokens::T_CASE:
            case Tokens::T_CATCH:
            case Tokens::T_CLASS:
            case Tokens::T_CLASS_C:
            case Tokens::T_CLONE:
            case Tokens::T_CONST:
            case Tokens::T_CONTINUE:
            case Tokens::T_DECLARE:
            case Tokens::T_DEFAULT:
            case Tokens::T_DIR:
            case Tokens::T_DO:
            case Tokens::T_ECHO:
            case Tokens::T_ELSE:
            case Tokens::T_ELSEIF:
            case Tokens::T_EMPTY:
            case Tokens::T_ENDDECLARE:
            case Tokens::T_ENDFOR:
            case Tokens::T_ENDFOREACH:
            case Tokens::T_ENDIF:
            case Tokens::T_ENDSWITCH:
            case Tokens::T_ENDWHILE:
            case Tokens::T_EVAL:
            case Tokens::T_EXIT:
            case Tokens::T_EXTENDS:
            case Tokens::T_FILE:
            case Tokens::T_FINAL:
            case Tokens::T_FINALLY:
            case Tokens::T_FOR:
            case Tokens::T_FOREACH:
            case Tokens::T_FUNCTION:
            case Tokens::T_FUNC_C:
            case Tokens::T_GLOBAL:
            case Tokens::T_GOTO:
            case Tokens::T_HALT_COMPILER:
            case Tokens::T_IF:
            case Tokens::T_IMPLEMENTS:
            case Tokens::T_INCLUDE:
            case Tokens::T_INCLUDE_ONCE:
            case Tokens::T_INSTANCEOF:
            case Tokens::T_INSTEADOF:
            case Tokens::T_INTERFACE:
            case Tokens::T_ISSET:
            case Tokens::T_LINE:
            case Tokens::T_LIST:
            case Tokens::T_LOGICAL_AND:
            case Tokens::T_LOGICAL_OR:
            case Tokens::T_LOGICAL_XOR:
            case Tokens::T_METHOD_C:
            case Tokens::T_NAMESPACE:
            case Tokens::T_NEW:
            case Tokens::T_NS_C:
            case Tokens::T_PRINT:
            case Tokens::T_PRIVATE:
            case Tokens::T_PROTECTED:
            case Tokens::T_PUBLIC:
            case Tokens::T_REQUIRE:
            case Tokens::T_REQUIRE_ONCE:
            case Tokens::T_RETURN:
            case Tokens::T_STATIC:
            case Tokens::T_SWITCH:
            case Tokens::T_THROW:
            case Tokens::T_TRAIT:
            case Tokens::T_TRAIT_C:
            case Tokens::T_TRY:
            case Tokens::T_UNSET:
            case Tokens::T_USE:
            case Tokens::T_VAR:
            case Tokens::T_WHILE:
            case Tokens::T_YIELD:
                return true;
        }
        return false;
    }

    /**
     * Tests if the give token is a valid namespace name in the supported PHP
     * version.
     *
     * @param integer $tokenType
     * @return boolean
     */
    protected function isNamespaceName($tokenType)
    {
        switch ($tokenType) {
            case Tokens::T_NULL:
            case Tokens::T_SELF:
            case Tokens::T_TRUE:
            case Tokens::T_FALSE:
            case Tokens::T_STRING:
            case Tokens::T_PARENT:
                return true;
        }
        return false;
    }

    /* }}} Keyword test methods */

    /**
     * Tests if the given token type is a reserved keyword in the supported PHP
     * version.
     *
     * @param  $tokenType
     * @return boolean
     * @since  1.1.1
     */
    protected function isKeyword($tokenType)
    {
        switch ($tokenType) {
            case Tokens::T_CLASS:
            case Tokens::T_INTERFACE:
                return true;
        }
        return false;
    }

    /**
     * Implements some quirks and hacks to support php here- and now-doc for
     * PHP 5.2.x versions :/
     *
     * @return \PDepend\Source\AST\ASTHeredoc
     * @since 1.0.0
     */
    protected function parseHeredoc()
    {
        $heredoc = parent::parseHeredoc();
        if (version_compare(phpversion(), "5.3.0alpha") >= 0) {
            return $heredoc;
        }

        // Consume dangling semicolon
        $this->tokenizer->next();

        $token = $this->tokenizer->next();
        preg_match('(/\*(\'|")\*/)', $token->image, $match);

        return $heredoc;
    }

    /**
     * Parses additional static values that are valid in the supported php version.
     *
     * @param \PDepend\Source\AST\ASTValue $value
     * @return \PDepend\Source\AST\ASTValue
     * @throws \PDepend\Source\Parser\UnexpectedTokenException
     * @todo Handle shift left/right expressions in ASTValue
     */ /*
    protected function parseStaticValueVersionSpecific(ASTValue $value)
    {
        switch ($this->tokenizer->peek()) {
            case Tokens::T_SL:
                $shift = $this->parseShiftLeftExpression();
                $this->parseStaticValue();
                break;
            case Tokens::T_SR:
                $shift = $this->parseShiftRightExpression();
                $this->parseStaticValue();
                break;
            default:
                throw new UnexpectedTokenException(
                    $this->tokenizer->next(),
                    $this->tokenizer->getSourceFile()
                );
        }

        return $value;
    }*/

    /**
     * Parses constant default values as they are supported by the most recent
     * PHP version.
     *
     * @return \PDepend\Source\AST\ASTValue
     * @since 2.2.x
     */
    protected function parseConstantDeclaratorValue()
    {
        return $this->parseStaticValueOrStaticArray();
    }
}
