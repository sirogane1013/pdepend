<?php
namespace PDepend\Metrics;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

class NodeCountVisitor extends NodeVisitorAbstract
{
    /**
     * Metrics provided by the analyzer implementation.
     */
    const M_NUMBER_OF_PACKAGES   = 'nop',
        M_NUMBER_OF_CLASSES    = 'noc',
        M_NUMBER_OF_INTERFACES = 'noi',
        M_NUMBER_OF_METHODS    = 'nom',
        M_NUMBER_OF_FUNCTIONS  = 'nof';

    /**
     * @var \PDepend\Metrics\Processor[][]
     */
    private $processors = array();

    public function register(Processor $processor)
    {
        foreach ($processor->getSupportedTypes() as $type) {
            if (false === isset($this->processors[$type])) {
                $this->processors[$type] = [];
            }
            $this->processors[$type][] = $processor;
        }
    }

    public function beforeTraverse(array $nodes)
    {
        $this->addAnalyzer(new IdGenerator());
        $this->addAnalyzer(new NodeCountAnalyzer());

        // echo "Before\n";
    }

    public function enterNode(Node $node)
    {
        $type = $node->getType();

        if (false === isset($this->processors[$type])) {
            return;
        }

        foreach ($this->processors[$type] as $analyzer) {
            $analyzer->enterNode($node, $type);
        }
    }

    public function leaveNode(Node $node)
    {
        $type = $node->getType();

        if (false === isset($this->processors[$type])) {
            return;
        }

        foreach ($this->processors[$type] as $analyzer) {
            $analyzer->leaveNode($node, $type);
        }
    }


    public function afterTraverse(array $nodes)
    {
        // echo "After(", $this->count, ")\n";
    }
}
