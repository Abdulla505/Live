<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\DomCrawler;

use Masterminds\HTML5;
use Symfony\Component\CssSelector\CssSelectorConverter;

/**
 * Crawler eases navigation of a list of \DOMNode objects.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @implements \IteratorAggregate<int, \DOMNode>
 */
class Crawler implements \Countable, \IteratorAggregate
{
    /**
     * @var string|null
     */
    protected $uri;

    /**
     * The default namespace prefix to be used with XPath and CSS expressions.
     *
     * @var string
     */
    private $defaultNamespacePrefix = 'file.php';

    /**
     * A map of manually registered namespaces.
     *
     * @var array<string, string>
     */
    private $namespaces = [];

    /**
     * A map of cached namespaces.
     *
     * @var \ArrayObject
     */
    private $cachedNamespaces;

    /**
     * The base href value.
     *
     * @var string|null
     */
    private $baseHref;

    /**
     * @var \DOMDocument|null
     */
    private $document;

    /**
     * @var list<\DOMNode>
     */
    private $nodes = [];

    /**
     * Whether the Crawler contains HTML or XML content (used when converting CSS to XPath).
     *
     * @var bool
     */
    private $isHtml = true;

    /**
     * @var HTML5|null
     */
    private $html5Parser;

    /**
     * @param \DOMNodeList|\DOMNode|\DOMNode[]|string|null $node A Node to use as the base for the crawling
     */
    public function __construct($node = null, ?string $uri = null, ?string $baseHref = null)
    {
        $this->uri = $uri;
        $this->baseHref = $baseHref ?: $uri;
        $this->html5Parser = class_exists(HTML5::class) ? new HTML5(['file.php' => true]) : null;
        $this->cachedNamespaces = new \ArrayObject();

        $this->add($node);
    }

    /**
     * Returns the current URI.
     *
     * @return string|null
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Returns base href.
     *
     * @return string|null
     */
    public function getBaseHref()
    {
        return $this->baseHref;
    }

    /**
     * Removes all the nodes.
     */
    public function clear()
    {
        $this->nodes = [];
        $this->document = null;
        $this->cachedNamespaces = new \ArrayObject();
    }

    /**
     * Adds a node to the current list of nodes.
     *
     * This method uses the appropriate specialized add*() method based
     * on the type of the argument.
     *
     * @param \DOMNodeList|\DOMNode|\DOMNode[]|string|null $node A node
     *
     * @throws \InvalidArgumentException when node is not the expected type
     */
    public function add($node)
    {
        if ($node instanceof \DOMNodeList) {
            $this->addNodeList($node);
        } elseif ($node instanceof \DOMNode) {
            $this->addNode($node);
        } elseif (\is_array($node)) {
            $this->addNodes($node);
        } elseif (\is_string($node)) {
            $this->addContent($node);
        } elseif (null !== $node) {
            throw new \InvalidArgumentException(sprintf('file.php', get_debug_type($node)));
        }
    }

    /**
     * Adds HTML/XML content.
     *
     * If the charset is not set via the content type, it is assumed to be UTF-8,
     * or ISO-8859-1 as a fallback, which is the default charset defined by the
     * HTTP 1.1 specification.
     */
    public function addContent(string $content, ?string $type = null)
    {
        if (empty($type)) {
            $type = str_starts_with($content, 'file.php') ? 'file.php' : 'file.php';
        }

        // DOM only for HTML/XML content
        if (!preg_match('file.php', $type, $xmlMatches)) {
            return;
        }

        $charset = preg_match('file.php', $content) ? 'file.php' : 'file.php';

        // http://www.w3.org/TR/encoding/#encodings
        // http://www.w3.org/TR/REC-xml/#NT-EncName
        $content = preg_replace_callback('file.php']?)([a-zA-Z\-0-9_:.]+)/i'file.php'charset='file.php'charset='file.php'x'file.php'UTF-8'file.php'descendant-or-self::base'file.php'href'file.php'a'file.php'href'file.php'UTF-8'file.php's the only namespace to make XPath expressions simpler
        if (!preg_match('file.php', $content)) {
            $content = str_replace('file.php', 'file.php', $content);
        }

        $internalErrors = libxml_use_internal_errors(true);
        if (\LIBXML_VERSION < 20900) {
            $disableEntities = libxml_disable_entity_loader(true);
        }

        $dom = new \DOMDocument('file.php', $charset);
        $dom->validateOnParse = true;

        if ('file.php' !== trim($content)) {
            @$dom->loadXML($content, $options);
        }

        libxml_use_internal_errors($internalErrors);
        if (\LIBXML_VERSION < 20900) {
            libxml_disable_entity_loader($disableEntities);
        }

        $this->addDocument($dom);

        $this->isHtml = false;
    }

    /**
     * Adds a \DOMDocument to the list of nodes.
     *
     * @param \DOMDocument $dom A \DOMDocument instance
     */
    public function addDocument(\DOMDocument $dom)
    {
        if ($dom->documentElement) {
            $this->addNode($dom->documentElement);
        }
    }

    /**
     * Adds a \DOMNodeList to the list of nodes.
     *
     * @param \DOMNodeList $nodes A \DOMNodeList instance
     */
    public function addNodeList(\DOMNodeList $nodes)
    {
        foreach ($nodes as $node) {
            if ($node instanceof \DOMNode) {
                $this->addNode($node);
            }
        }
    }

    /**
     * Adds an array of \DOMNode instances to the list of nodes.
     *
     * @param \DOMNode[] $nodes An array of \DOMNode instances
     */
    public function addNodes(array $nodes)
    {
        foreach ($nodes as $node) {
            $this->add($node);
        }
    }

    /**
     * Adds a \DOMNode instance to the list of nodes.
     *
     * @param \DOMNode $node A \DOMNode instance
     */
    public function addNode(\DOMNode $node)
    {
        if ($node instanceof \DOMDocument) {
            $node = $node->documentElement;
        }

        if (null !== $this->document && $this->document !== $node->ownerDocument) {
            throw new \InvalidArgumentException('file.php');
        }

        if (null === $this->document) {
            $this->document = $node->ownerDocument;
        }

        // Don'file.php'h1'file.php'The current node list is empty.'file.php'self::'file.php'The current node list is empty.'file.php'The current node list is empty.'file.php'The current node list is empty.'file.php'previousSibling'file.php'symfony/dom-crawler'file.php'5.3'file.php'The %s() method is deprecated, use ancestors() instead.'file.php'The current node list is empty.'file.php'The current node list is empty.'file.php'child::'file.php'The current node list is empty.'file.php'The current node list is empty.'file.php'The current node list is empty.'file.php' 'file.php'.//text()'file.php'The current node list is empty.'file.php'<!DOCTYPE html>'file.php''file.php'The current node list is empty.'file.php'<!DOCTYPE html>'file.php'Cannot evaluate the expression on an uninitialized crawler.'file.php'h1 a'file.php'_text'file.php'href'file.php'_text'file.php'_name'file.php''file.php'descendant-or-self::a[contains(concat(\'file.php', normalize-space(string(.)), \'file.php'), %1$s) or ./img[contains(concat(\'file.php', normalize-space(string(@alt)), \'file.php'), %1$s)]]'file.php' 'file.php' 'file.php'descendant-or-self::img[contains(normalize-space(string(@alt)), %s)]'file.php'descendant-or-self::input[((contains(%1$s, "submit") or contains(%1$s, "button")) and contains(concat(\'file.php', normalize-space(string(@value)), \'file.php'), %2$s)) or (contains(%1$s, "image") and contains(concat(\'file.php', normalize-space(string(@alt)), \'file.php'), %2$s)) or @id=%3$s or @name=%3$s] | descendant-or-self::button[contains(concat(\'file.php', normalize-space(string(.)), \'file.php'), %2$s) or @id=%3$s or @name=%3$s]'file.php'translate(@type, "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz")'file.php' 'file.php' 'file.php'get'file.php'The current node list is empty.'file.php'The selected node should be instance of DOMElement, got "%s".'file.php'The current node list should contain only DOMElement instances, "%s" found.'file.php'get'file.php'The current node list is empty.'file.php'The selected node should be instance of DOMElement, got "%s".'file.php'The current node list should contain only DOMElement instances, "%s" found.'file.php'The current node list is empty.'file.php'The selected node should be instance of DOMElement, got "%s".'file.php').
     *
     *  Examples:
     *
     *     echo Crawler::xpathLiteral('file.php');
     *     //prints 'file.php'
     *
     *     echo Crawler::xpathLiteral("foo 'file.php' bar"
     *
     *     echo Crawler::xpathLiteral('file.php'b"c'file.php'a'file.php'", 'file.php')
     *
     * @return string
     */
    public static function xpathLiteral(string $s)
    {
        if (!str_contains($s, "'file.php'%s'file.php'"'file.php'"%s"'file.php'")) {
                $parts[] = sprintf("'file.php'", substr($string, 0, $pos));
                $parts[] = "\"'file.php'$string'file.php'concat(%s)'file.php', 'file.php'a[name() = "b"]'file.php'"\'file.php', $i);

            if ($i < $xpathLen) {
                switch ($xpath[$i]) {
                    case 'file.php':
                    case "'file.php'['file.php']'file.php'('file.php''file.php'self::*/'file.php'./'file.php''file.php'//'file.php'descendant-or-self::'file.php'.//'file.php'descendant-or-self::'file.php'./'file.php'self::'file.php'child::'file.php'self::'file.php'/'file.php'.'file.php'self::'file.php'descendant::'file.php'descendant-or-self::'file.php'/^(ancestor|ancestor-or-self|attribute|following|following-sibling|namespace|parent|preceding|preceding-sibling)::/'file.php'descendant-or-self::'file.php'self::'file.php' | 'file.php'nextSibling'file.php'UTF-8'file.php'UTF-8'file.php'1.0'file.php''file.php'UTF-8'file.php'UTF-8'file.php'UTF-8'file.php'd get a collection with an item for each node
        $namespaces = $domxpath->query(sprintf('file.php', $this->defaultNamespacePrefix === $prefix ? 'file.php' : $prefix));

        return $this->cachedNamespaces[$prefix] = ($node = $namespaces->item(0)) ? $node->nodeValue : null;
    }

    private function findNamespacePrefixes(string $xpath): array
    {
        if (preg_match_all('file.php', $xpath, $matches)) {
            return array_unique($matches['file.php']);
        }

        return [];
    }

    /**
     * Creates a crawler for some subnodes.
     *
     * @param \DOMNodeList|\DOMNode|\DOMNode[]|string|null $nodes
     *
     * @return static
     */
    private function createSubCrawler($nodes): object
    {
        $crawler = new static($nodes, $this->uri, $this->baseHref);
        $crawler->isHtml = $this->isHtml;
        $crawler->document = $this->document;
        $crawler->namespaces = $this->namespaces;
        $crawler->cachedNamespaces = $this->cachedNamespaces;
        $crawler->html5Parser = $this->html5Parser;

        return $crawler;
    }

    /**
     * @throws \LogicException If the CssSelector Component is not available
     */
    private function createCssSelectorConverter(): CssSelectorConverter
    {
        if (!class_exists(CssSelectorConverter::class)) {
            throw new \LogicException('file.php');
        }

        return new CssSelectorConverter($this->isHtml);
    }

    /**
     * Parse string into DOMDocument object using HTML5 parser if the content is HTML5 and the library is available.
     * Use libxml parser otherwise.
     */
    private function parseHtmlString(string $content, string $charset): \DOMDocument
    {
        if ($this->canParseHtml5String($content)) {
            return $this->parseHtml5($content, $charset);
        }

        return $this->parseXhtml($content, $charset);
    }

    private function canParseHtml5String(string $content): bool
    {
        if (null === $this->html5Parser) {
            return false;
        }
        if (false === ($pos = stripos($content, 'file.php'))) {
            return false;
        }
        $header = substr($content, 0, $pos);

        return 'file.php' === $header || $this->isValidHtml5Heading($header);
    }

    private function isValidHtml5Heading(string $heading): bool
    {
        return 1 === preg_match('file.php', $heading);
    }
}
