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
     * @var NodeCountAnalyzer[][]
     */
    private $analyzers = array();

    public function addAnalyzer(NodeTreeProcessor $analyzer)
    {
        foreach ($analyzer->getNodeTypes() as $nodeType) {
            if (false === isset($this->analyzers[$nodeType])) {
                $this->analyzers[$nodeType] = array();
            }
            $this->analyzers[$nodeType][] = $analyzer;
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

        if (false === isset($this->analyzers[$type])) {
            return;
        }

        foreach ($this->analyzers[$type] as $analyzer) {
            $analyzer->enterNode($node, $type);
        }
    }

    public function leaveNode(Node $node)
    {
        $type = $node->getType();

        if (false === isset($this->analyzers[$type])) {
            return;
        }

        foreach ($this->analyzers[$type] as $analyzer) {
            $analyzer->leaveNode($node, $type);
        }
    }


    public function afterTraverse(array $nodes)
    {
        // echo "After(", $this->count, ")\n";
    }
}

interface NodeTreeProcessor
{
    public function getNodeTypes();
}

class IdGenerator implements NodeTreeProcessor
{
    private $parentIds = ['@global'];

    private $namespacedName;

    public function getNodeTypes()
    {
        return array(
            'Const',
            'Stmt_Class',
            'Stmt_ClassMethod',
            'Stmt_Function',
            'Stmt_Interface',
            'Stmt_Namespace',
            'Stmt_PropertyProperty',
            'Stmt_Trait',
        );
    }

    public function enterNode(Node $node, $type)
    {
        $qName = null;

        switch ($type) {
            case 'Stmt_Class':
                $qName = $this->namespacedName = $node->namespacedName;
                break;
            case 'Const':
                $qName = $this->namespacedName . '::' . $node->name;
                break;
            case 'Stmt_ClassMethod':
                $qName = $this->namespacedName . '::' . $node->name . '()';
                break;
            case 'Stmt_Function':
                $qName = $node->namespacedName . '()';
                break;
            case 'Stmt_Interface':
                $qName = $this->namespacedName = $node->namespacedName;
                break;
            case 'Stmt_Namespace':
                $qName = join('\\', $node->name->parts);
                break;
            case 'Stmt_PropertyProperty':
                $qName = $this->namespacedName . '::$' . $node->name;
                break;
            case 'Stmt_Trait':
                $qName = $this->namespacedName = $node->namespacedName;
                break;
        }

        if ($qName) {
            $nodeId = $qName . '#' . $node->getLine();

            $node->setAttribute('parentId', end($this->parentIds) ?: null);
            $node->setAttribute('qualifiedName', $qName);
            $node->setAttribute('id', $nodeId);

            //echo $node->getAttribute('id'), PHP_EOL;

            $this->parentIds[] = $nodeId;
        }
    }

    public function leaveNode(Node $node, $type)
    {
        switch ($type) {
            case 'Const':
            case 'Stmt_ClassMethod':
            case 'Stmt_Function':
            case 'Stmt_PropertyProperty':
                array_pop($this->parentIds);
                break;
            case 'Stmt_Class':
            case 'Stmt_Interface':
            case 'Stmt_Trait':
            case 'Stmt_Namespace':
                array_pop($this->parentIds);
                $this->namespacedName = null;
                break;
        }
    }
}

class NodeCountAnalyzer implements AnalyzerProjectAware, AnalyzerNodeAware, NodeTreeProcessor
{
    /**
     * Metrics provided by the analyzer implementation.
     */
    const M_NUMBER_OF_PACKAGES   = 'nop',
          M_NUMBER_OF_CLASSES    = 'noc',
          M_NUMBER_OF_INTERFACES = 'noi',
          M_NUMBER_OF_METHODS    = 'nom',
          M_NUMBER_OF_FUNCTIONS  = 'nof',
          M_NUMBER_OF_TRAITS     = 'not';

    private $numberOfPackages = 0;

    private $numberOfClasses = 0;

    private $numberOfInterfaces = 0;

    private $numberOfMethods = 0;

    private $numberOfFunctions = 0;

    private $numberOfTraits = 0;

    private $nodeMetrics = [
        '@global' => [
            self::M_NUMBER_OF_CLASSES => 0,
            self::M_NUMBER_OF_FUNCTIONS => 0,
            self::M_NUMBER_OF_INTERFACES => 0,
            self::M_NUMBER_OF_TRAITS => 0,
        ]
    ];

    /**
     * Constructs a new analyzer instance.
     *
     * @param array(string=>mixed) $options Global option array, every analyzer
     *                                      can extract the required options.
     */
    public function __construct(array $options = [])
    {

    }

    public function getNodeTypes()
    {
        return array(
            'Stmt_Class',
            'Stmt_ClassMethod',
            'Stmt_Function',
            'Stmt_Interface',
            'Stmt_Namespace',
            'Stmt_Trait',
        );
    }

    public function enterNode(Node $node, $type)
    {
        $metrics = [];
        if (false === isset($this->nodeMetrics[$node->getAttribute('id')])) {
            $this->nodeMetrics[$node->getAttribute('id')] = [];

            $metrics =& $this->nodeMetrics[$node->getAttribute('id')];
        }

        $parentMetrics = [];
        if ($parentId = $node->getAttribute('parentId')) {
            $parentMetrics =& $this->nodeMetrics[$parentId];
        }

        switch ($type) {
            case 'Stmt_Class':
                $metrics = [self::M_NUMBER_OF_METHODS => 0];

                ++$parentMetrics[self::M_NUMBER_OF_CLASSES];
                ++$this->numberOfTraits;
                break;
            case 'Stmt_ClassMethod':
                ++$parentMetrics[self::M_NUMBER_OF_METHODS];
                ++$this->numberOfMethods;
                break;
            case 'Stmt_Function':
                ++$parentMetrics[self::M_NUMBER_OF_FUNCTIONS];
                ++$this->numberOfFunctions;
                break;
            case 'Stmt_Interface':
                $metrics = [self::M_NUMBER_OF_METHODS => 0];

                ++$parentMetrics[self::M_NUMBER_OF_INTERFACES];
                ++$this->numberOfInterfaces;
                break;
            case 'Stmt_Namespace':
                if (!$metrics) {
                    $metrics = [
                        self::M_NUMBER_OF_CLASSES => 0,
                        self::M_NUMBER_OF_FUNCTIONS => 0,
                        self::M_NUMBER_OF_INTERFACES => 0,
                        self::M_NUMBER_OF_TRAITS => 0,
                    ];

                    ++$this->numberOfPackages;
                }
                break;
            case 'Stmt_Trait':
                $metrics = [self::M_NUMBER_OF_METHODS => 0];

                ++$parentMetrics[self::M_NUMBER_OF_TRAITS];
                ++$this->numberOfTraits;
                break;
        }
    }

    public function leaveNode(Node $node, $type)
    {

    }

    /**
     * Provides the project summary as an <b>array</b>.
     *
     * <code>
     * array(
     *     'loc'  =>  1742,
     *     'nop'  =>  23,
     *     'noc'  =>  17
     * )
     * </code>
     *
     * @return array(string=>mixed)
     */
    public function getProjectMetrics()
    {
        return [
            self::M_NUMBER_OF_CLASSES => $this->numberOfClasses,
            self::M_NUMBER_OF_FUNCTIONS => $this->numberOfFunctions,
            self::M_NUMBER_OF_INTERFACES => $this->numberOfInterfaces,
            self::M_NUMBER_OF_METHODS => $this->numberOfPackages,
            self::M_NUMBER_OF_PACKAGES => $this->numberOfPackages,
            self::M_NUMBER_OF_TRAITS => $this->numberOfTraits,
        ];
    }

    /**
     * This method will return an <b>array</b> with all generated metric values
     * for the node with the given <b>$id</b> identifier. If there are no
     * metrics for the requested node, this method will return an empty <b>array</b>.
     *
     * <code>
     * array(
     *     'loc'    =>  42,
     *     'ncloc'  =>  17,
     *     'cc'     =>  12
     * )
     * </code>
     *
     * @param \PhpParser\Node $node
     * @return array|mixed
     */
    public function getMetricsForNode(Node $node)
    {
        if (isset($this->nodeMetrics[$node->getAttribute('id')])) {
            return $this->nodeMetrics[$node->getAttribute('id')];
        }
        return array();
    }

    /**
     * Adds a listener to this analyzer.
     *
     * @param  \PDepend\Metrics\AnalyzerListener $listener The listener instance.
     * @return void
     */
    public function addAnalyzeListener(AnalyzerListener $listener)
    {
        // TODO: Implement addAnalyzeListener() method.
    }

    /**
     * Processes all {@link \PDepend\Source\AST\ASTNamespace} code nodes.
     *
     * @param  \PDepend\Source\AST\ASTNamespace[] $namespaces
     * @return void
     */
    public function analyze($namespaces)
    {
        // TODO: Implement analyze() method.
    }

    /**
     * An analyzer that is active must return <b>true</b> to recognized by
     * pdepend framework, while an analyzer that does not perform any action
     * for any reason should return <b>false</b>.
     *
     * @return boolean
     * @since  0.9.10
     */
    public function isEnabled()
    {
        // TODO: Implement isEnabled() method.
    }

    /**
     * Set global options
     *
     * @param array(string=>mixed) $options
     * @since 2.0.1
     */
    public function setOptions(array $options = array())
    {
        // TODO: Implement setOptions() method.
    }
}
