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
 */

namespace PDepend\Source\AST;

/**
 * Abstract base class for code item.
 *
 * @copyright 2008-2015 Manuel Pichler. All rights reserved.
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */
abstract class AbstractASTArtifact implements ASTArtifact, ASTNode
{
    /**
     * The name for this item.
     *
     * @var string
     */
    protected $name = '';

    /**
     * The unique identifier for this function.
     *
     * @var string
     */
    protected $id = null;

    /**
     * @var \Pdepend\Source\AST\ASTNode
     * @since 2.3
     */
    protected $parent;

    /**
     * The line number where the item declaration starts.
     *
     * @var integer
     */
    protected $startLine = 0;

    /**
     * The line number where the item declaration ends.
     *
     * @var integer
     */
    protected $endLine = 0;

    /**
     * @var integer
     * @since 2.3
     */
    protected $startColumn = 0;

    /**
     * @var integer
     * @since 2.3
     */
    protected $endColumn = 0;

    /**
     * The source file for this item.
     *
     * @var \PDepend\Source\AST\ASTCompilationUnit
     */
    protected $compilationUnit = null;

    /**
     * The comment for this type.
     *
     * @var string
     */
    protected $comment = null;

    /**
     * Constructs a new item for the given <b>$name</b>.
     *
     * @param string $name The item name.
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     * @since 2.3
     */
    public function getImage()
    {
        return $this->name;
    }

    /**
     * Sets the item name.
     *
     * @param string $name The item name.
     * @return void
     * @since 1.0.0
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Returns a id for this code node.
     *
     * @return string
     */
    public function getId()
    {
        if ($this->id === null) {
            $this->id = md5(microtime());
        }
        return $this->id;
    }

    /**
     * Sets the unique identifier for this node instance.
     *
     * @param  string $id Identifier for this node.
     * @return void
     * @since  0.9.12
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Returns the source file for this item.
     *
     * @return \PDepend\Source\AST\ASTCompilationUnit
     */
    public function getCompilationUnit()
    {
        return $this->compilationUnit;
    }

    /**
     * Sets the source file for this item.
     *
     * @param \PDepend\Source\AST\ASTCompilationUnit $compilationUnit
     * @return void
     */
    public function setCompilationUnit(ASTCompilationUnit $compilationUnit)
    {
        if ($this->compilationUnit === null || $this->compilationUnit->getName() === null) {
            $this->compilationUnit = $compilationUnit;
        }
    }

    /**
     * Returns the parent node of this node or <b>null</b> when this node is
     * the root of a node tree.
     *
     * @return \PDepend\Source\AST\ASTNode
     * @since 2.3
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param \PDepend\Source\AST\ASTNode $parent
     * @return void
     * @since 2.3
     */
    public function setParent(ASTNode $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Traverses up the node tree and finds all parent nodes that are instances
     * of <b>$parentType</b>.
     *
     * @param string $parentType
     * @return \PDepend\Source\AST\ASTNode[]
     * @since 2.3
     */
    public function getParentsOfType($parentType)
    {
        return array();
    }

    /**
     * Returns a doc comment for this node or <b>null</b> when no comment was
     * found.
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Sets the raw doc comment for this node.
     *
     * @param string $comment
     * @return void
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * Returns the line number where the class or interface declaration starts.
     *
     * @return integer
     */
    public function getStartLine()
    {
        return $this->startLine;
    }

    /**
     * Returns the line number where the class or interface declaration ends.
     *
     * @return integer
     */
    public function getEndLine()
    {
        return $this->endLine;
    }

    /**
     * Returns the start column where the declaration of this artifact begins.
     *
     * @return integer
     * @since 2.3
     */
    public function getStartColumn()
    {
        return $this->startColumn;
    }

    /**
     * Returns the end column where the declaration of this artifact ends.
     *
     * @return integer
     */
    public function getEndColumn()
    {
        return $this->endColumn;
    }

    /**
     * For better performance we have moved the single setter methods for the
     * node columns and lines into this configure method.
     *
     * @param integer $startLine
     * @param integer $endLine
     * @param integer $startColumn
     * @param integer $endColumn
     * @return void
     * @since 2.3
     */
    public function configureLinesAndColumns(
        $startLine,
        $endLine,
        $startColumn,
        $endColumn
    ) {
        $this->startLine = $startLine;
        $this->startColumn = $startColumn;
        $this->endLine = $endLine;
        $this->endColumn = $endColumn;
    }

    // BEGIN@deprecated

    /**
     * Returns the doc comment for this item or <b>null</b>.
     *
     * @return string
     * @deprecated Use getComment() inherit from ASTNode instead.
     */
    public function getDocComment()
    {
        return $this->getComment();
    }

    /**
     * Sets the doc comment for this item.
     *
     * @param string $docComment
     * @return void
     * @deprecated Use setComment() inherit from ASTNode instead.
     */
    public function setDocComment($docComment)
    {
        $this->setComment($docComment);
    }

    /**
     * Returns the item name.
     *
     * @return string
     * @deprecated Use getImage() instead since 2.3
     */
    public function getName()
    {
        return $this->name;
    }

    // END@deprecated
}
