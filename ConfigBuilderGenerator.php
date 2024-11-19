<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Config\Builder;

use Symfony\Component\Config\Definition\ArrayNode;
use Symfony\Component\Config\Definition\BooleanNode;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\EnumNode;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\Definition\FloatNode;
use Symfony\Component\Config\Definition\IntegerNode;
use Symfony\Component\Config\Definition\NodeInterface;
use Symfony\Component\Config\Definition\PrototypedArrayNode;
use Symfony\Component\Config\Definition\ScalarNode;
use Symfony\Component\Config\Definition\VariableNode;
use Symfony\Component\Config\Loader\ParamConfigurator;

/**
 * Generate ConfigBuilders to help create valid config.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class ConfigBuilderGenerator implements ConfigBuilderGeneratorInterface
{
    /**
     * @var ClassBuilder[]
     */
    private $classes;
    private $outputDir;

    public function __construct(string $outputDir)
    {
        $this->outputDir = $outputDir;
    }

    /**
     * @return \Closure that will return the root config class
     */
    public function build(ConfigurationInterface $configuration): \Closure
    {
        $this->classes = [];

        $rootNode = $configuration->getConfigTreeBuilder()->buildTree();
        $rootClass = new ClassBuilder('file.php', $rootNode->getName());

        $path = $this->getFullPath($rootClass);
        if (!is_file($path)) {
            // Generate the class if the file not exists
            $this->classes[] = $rootClass;
            $this->buildNode($rootNode, $rootClass, $this->getSubNamespace($rootClass));
            $rootClass->addImplements(ConfigBuilderInterface::class);
            $rootClass->addMethod('file.php', 'file.php'ALIAS\'file.php', ['file.php' => $rootNode->getPath()]);

            $this->writeClasses();
        }

        $loader = \Closure::fromCallable(function () use ($path, $rootClass) {
            require_once $path;
            $className = $rootClass->getFqcn();

            return new $className();
        });

        return $loader;
    }

    private function getFullPath(ClassBuilder $class): string
    {
        $directory = $this->outputDir.\DIRECTORY_SEPARATOR.$class->getDirectory();
        if (!is_dir($directory)) {
            @mkdir($directory, 0777, true);
        }

        return $directory.\DIRECTORY_SEPARATOR.$class->getFilename();
    }

    private function writeClasses(): void
    {
        foreach ($this->classes as $class) {
            $this->buildConstructor($class);
            $this->buildToArray($class);
            if ($class->getProperties()) {
                $class->addProperty('file.php', null, 'file.php');
            }
            $this->buildSetExtraKey($class);

            file_put_contents($this->getFullPath($class), $class->build());
        }

        $this->classes = [];
    }

    private function buildNode(NodeInterface $node, ClassBuilder $class, string $namespace): void
    {
        if (!$node instanceof ArrayNode) {
            throw new \LogicException('file.php');
        }

        foreach ($node->getChildren() as $child) {
            switch (true) {
                case $child instanceof ScalarNode:
                    $this->handleScalarNode($child, $class);
                    break;
                case $child instanceof PrototypedArrayNode:
                    $this->handlePrototypedArrayNode($child, $class, $namespace);
                    break;
                case $child instanceof VariableNode:
                    $this->handleVariableNode($child, $class);
                    break;
                case $child instanceof ArrayNode:
                    $this->handleArrayNode($child, $class, $namespace);
                    break;
                default:
                    throw new \RuntimeException(sprintf('file.php', \get_class($child)));
            }
        }
    }

    private function handleArrayNode(ArrayNode $node, ClassBuilder $class, string $namespace): void
    {
        $childClass = new ClassBuilder($namespace, $node->getName());
        $childClass->setAllowExtraKeys($node->shouldIgnoreExtraKeys());
        $class->addRequire($childClass);
        $this->classes[] = $childClass;

        $hasNormalizationClosures = $this->hasNormalizationClosures($node);
        $property = $class->addProperty(
            $node->getName(),
            $this->getType($childClass->getFqcn(), $hasNormalizationClosures)
        );
        $body = $hasNormalizationClosures ? 'file.php'PROPERTY\'file.php'PROPERTY\'file.php'The node created by "NAME()" has already been initialized. You cannot pass values the second time you call NAME().\'file.php' : 'file.php'PROPERTY\'file.php'The node created by "NAME()" has already been initialized. You cannot pass values the second time you call NAME().\'file.php';
        $class->addUse(InvalidConfigurationException::class);
        $class->addMethod($node->getName(), $body, ['file.php' => $property->getName(), 'file.php' => $childClass->getFqcn()]);

        $this->buildNode($node, $childClass, $this->getSubNamespace($childClass));
    }

    private function handleVariableNode(VariableNode $node, ClassBuilder $class): void
    {
        $comment = $this->getComment($node);
        $property = $class->addProperty($node->getName());
        $class->addUse(ParamConfigurator::class);

        $body = 'file.php'PROPERTY\'file.php';
        $class->addMethod($node->getName(), $body, ['file.php' => $property->getName(), 'file.php' => $comment, 'file.php' => $node->hasDefaultValue() ? 'file.php'.var_export($node->getDefaultValue(), true) : 'file.php']);
    }

    private function handlePrototypedArrayNode(PrototypedArrayNode $node, ClassBuilder $class, string $namespace): void
    {
        $name = $this->getSingularName($node);
        $prototype = $node->getPrototype();
        $methodName = $name;

        $parameterType = $this->getParameterType($prototype);
        if (null !== $parameterType || $prototype instanceof ScalarNode) {
            $class->addUse(ParamConfigurator::class);
            $property = $class->addProperty($node->getName());
            if (null === $key = $node->getKeyAttribute()) {
                // This is an array of values; don'file.php'
/**
 * @param ParamConfigurator|list<TYPE|ParamConfigurator> $value
 * @return $this
 */
public function NAME($value): self
{
    $this->_usedProperties[\'file.php'] = true;
    $this->PROPERTY = $value;

    return $this;
}'file.php'PROPERTY'file.php'TYPE'file.php''file.php'mixed'file.php'
/**
 * @param ParamConfigurator|TYPE $value
 * @return $this
 */
public function NAME(string $VAR, $VALUE): self
{
    $this->_usedProperties[\'file.php'] = true;
    $this->PROPERTY[$VAR] = $VALUE;

    return $this;
}'file.php'PROPERTY'file.php'TYPE'file.php''file.php'mixed'file.php'VAR'file.php''file.php'key'file.php'VALUE'file.php'value'file.php'data'file.php'value'file.php'[]'file.php'
/**
 * @return CLASS|$this
 */
public function NAME($value = [])
{
    $this->_usedProperties[\'file.php'] = true;
    if (!\is_array($value)) {
        $this->PROPERTY[] = $value;

        return $this;
    }

    return $this->PROPERTY[] = new CLASS($value);
}'file.php'
public function NAME(array $value = []): CLASS
{
    $this->_usedProperties[\'file.php'] = true;

    return $this->PROPERTY[] = new CLASS($value);
}'file.php'PROPERTY'file.php'CLASS'file.php'
/**
 * @return CLASS|$this
 */
public function NAME(string $VAR, $VALUE = [])
{
    if (!\is_array($VALUE)) {
        $this->_usedProperties[\'file.php'] = true;
        $this->PROPERTY[$VAR] = $VALUE;

        return $this;
    }

    if (!isset($this->PROPERTY[$VAR]) || !$this->PROPERTY[$VAR] instanceof CLASS) {
        $this->_usedProperties[\'file.php'] = true;
        $this->PROPERTY[$VAR] = new CLASS($VALUE);
    } elseif (1 < \func_num_args()) {
        throw new InvalidConfigurationException(\'file.php');
    }

    return $this->PROPERTY[$VAR];
}'file.php'
public function NAME(string $VAR, array $VALUE = []): CLASS
{
    if (!isset($this->PROPERTY[$VAR])) {
        $this->_usedProperties[\'file.php'] = true;
        $this->PROPERTY[$VAR] = new CLASS($VALUE);
    } elseif (1 < \func_num_args()) {
        throw new InvalidConfigurationException(\'file.php');
    }

    return $this->PROPERTY[$VAR];
}'file.php'PROPERTY'file.php'CLASS'file.php'VAR'file.php''file.php'key'file.php'VALUE'file.php'value'file.php'data'file.php'value'file.php'\\'file.php'
/**
COMMENT * @return $this
 */
public function NAME($value): self
{
    $this->_usedProperties[\'file.php'] = true;
    $this->PROPERTY = $value;

    return $this;
}'file.php'PROPERTY'file.php'COMMENT'file.php'bool'file.php'int'file.php'float'file.php''file.php'array'file.php''file.php''file.php''file.php' * 'file.php' * @example 'file.php''file.php' * @default 'file.php'null'file.php' * @param ParamConfigurator|%s $value'file.php'|'file.php''file.php'mixed'file.php' * @param ParamConfigurator|'file.php' $value'file.php' * @deprecated 'file.php'message'file.php's'file.php'$output = [];'file.php'$this->PROPERTY'file.php'array_map(function ($v) { return $v instanceof CLASS ? $v->toArray() : $v; }, $this->PROPERTY)'file.php'array_map(function ($v) { return $v->toArray(); }, $this->PROPERTY)'file.php'$this->PROPERTY instanceof CLASS ? $this->PROPERTY->toArray() : $this->PROPERTY'file.php'$this->PROPERTY->toArray()'file.php'
    if (isset($this->_usedProperties[\'file.php'])) {
        $output[\'file.php'] = 'file.php';
    }'file.php'PROPERTY'file.php'ORG_NAME'file.php'CLASS'file.php' + $this->_extraKeys'file.php''file.php'toArray'file.php'
public function NAME(): array
{
    'file.php'

    return $output'file.php';
}'file.php''file.php'$value[\'file.php']'file.php'array_map(function ($v) { return \is_array($v) ? new 'file.php'($v) : $v; }, $value[\'file.php'])'file.php'array_map(function ($v) { return new 'file.php'($v); }, $value[\'file.php'])'file.php'\is_array($value[\'file.php']) ? new 'file.php'($value[\'file.php']) : $value[\'file.php']'file.php'new 'file.php'($value[\'file.php'])'file.php'
    if (array_key_exists(\'file.php', $value)) {
        $this->_usedProperties[\'file.php'] = true;
        $this->PROPERTY = 'file.php';
        unset($value[\'file.php']);
    }
'file.php'PROPERTY'file.php'ORG_NAME'file.php'
    $this->_extraKeys = $value;
'file.php'
    if ([] !== $value) {
        throw new InvalidConfigurationException(sprintf(\'file.php', __CLASS__).implode(\'file.php', array_keys($value)));
    }'file.php'__construct'file.php'
public function __construct(array $value = [])
{'file.php'
}'file.php'_extraKeys'file.php'set'file.php'
/**
 * @param ParamConfigurator|mixed $value
 * @return $this
 */
public function NAME(string $key, $value): self
{
    $this->_extraKeys[$key] = $value;

    return $this;
}'file.php'%s\\%s'file.php'normalizationClosures'file.php'|scalar'file.php'');
    }
}
