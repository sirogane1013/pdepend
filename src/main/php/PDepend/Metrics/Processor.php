<?php
namespace PDepend\Metrics;

use PhpParser\Node;

interface Processor
{
    public function enterNode(Node $node, $type);

    public function leaveNode(Node $node, $type);

    public function getSupportedTypes();
}
