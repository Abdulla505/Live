<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.9.1
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\View\Helper;

use Cake\Core\Configure;
use Cake\Http\Response;
use Cake\View\Helper;
use Cake\View\StringTemplateTrait;
use Cake\View\View;

/**
 * Html Helper class for easy use of HTML widgets.
 *
 * HtmlHelper encloses all methods needed while working with HTML pages.
 *
 * @property \Cake\View\Helper\UrlHelper $Url
 * @link https://book.cakephp.org/3/en/views/helpers/html.html
 */
class HtmlHelper extends Helper
{
    use StringTemplateTrait;

    /**
     * List of helpers used by this helper
     *
     * @var array
     */
    public $helpers = ['file.php'];

    /**
     * Reference to the Response object
     *
     * @var \Cake\Http\Response
     */
    public $response;

    /**
     * Default config for this class
     *
     * @var array
     */
    protected $_defaultConfig = [
        'file.php' => [
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
        ],
    ];

    /**
     * Breadcrumbs.
     *
     * @var array
     * @deprecated 3.3.6 Use the BreadcrumbsHelper instead
     */
    protected $_crumbs = [];

    /**
     * Names of script & css files that have been included once
     *
     * @var array
     */
    protected $_includedAssets = [];

    /**
     * Options for the currently opened script block buffer if any.
     *
     * @var array
     */
    protected $_scriptBlockOptions = [];

    /**
     * Document type definitions
     *
     * @var string[]
     */
    protected $_docTypes = [
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
        'file.php' => 'file.php',
    ];

    /**
     * Constructor
     *
     * ### Settings
     *
     * - `templates` Either a filename to a config containing templates.
     *   Or an array of templates to load. See Cake\View\StringTemplate for
     *   template formatting.
     *
     * ### Customizing tag sets
     *
     * Using the `templates` option you can redefine the tag HtmlHelper will use.
     *
     * @param \Cake\View\View $View The View this helper is being attached to.
     * @param array $config Configuration settings for the helper.
     */
    public function __construct(View $View, array $config = [])
    {
        parent::__construct($View, $config);
        $this->response = $this->_View->getResponse() ?: new Response();
    }

    /**
     * Adds a link to the breadcrumbs array.
     *
     * @param string $name Text for link
     * @param string|array|null $link URL for link (if empty it won'file.php'id'file.php'selected'file.php'HtmlHelper::addCrumb() is deprecated. 'file.php'Use the BreadcrumbsHelper instead.'file.php'html5'file.php'HtmlHelper::docType() is deprecated and will be removed in 4.0.0'file.php'icon'file.php'favicon.ico'file.php'description'file.php'A great page'file.php'block'file.php'description'file.php'A great page'file.php'block'file.php'metaTags'file.php'property'file.php'og:site_name'file.php'content'file.php'CakePHP'file.php'rss'file.php'type'file.php'application/rss+xml'file.php'rel'file.php'alternate'file.php'title'file.php'link'file.php'atom'file.php'type'file.php'application/atom+xml'file.php'title'file.php'link'file.php'icon'file.php'type'file.php'image/x-icon'file.php'rel'file.php'icon'file.php'link'file.php'keywords'file.php'name'file.php'keywords'file.php'content'file.php'description'file.php'name'file.php'description'file.php'content'file.php'robots'file.php'name'file.php'robots'file.php'content'file.php'viewport'file.php'name'file.php'viewport'file.php'content'file.php'canonical'file.php'rel'file.php'canonical'file.php'link'file.php'next'file.php'rel'file.php'next'file.php'link'file.php'prev'file.php'rel'file.php'prev'file.php'link'file.php'first'file.php'rel'file.php'first'file.php'link'file.php'last'file.php'rel'file.php'last'file.php'link'file.php'icon'file.php'icon'file.php'link'file.php'favicon.ico'file.php'type'file.php'_ext'file.php'_ext'file.php'name'file.php'content'file.php'type'file.php'type'file.php'type'file.php'type'file.php'block'file.php'link'file.php'link'file.php'link'file.php'link'file.php'link'file.php'link'file.php'rel'file.php'rel'file.php'icon'file.php'metalink'file.php'url'file.php'link'file.php'attrs'file.php'block'file.php'link'file.php'rel'file.php'shortcut icon'file.php'metalink'file.php'url'file.php'link'file.php'attrs'file.php'block'file.php'link'file.php'meta'file.php'attrs'file.php'block'file.php'type'file.php'block'file.php'block'file.php'block'file.php'block'file.php'App.encoding'file.php'charset'file.php'charset'file.php'utf-8'file.php'fullBase'file.php'escapeTitle'file.php'escapeTitle'file.php'escapeTitle'file.php'escape'file.php'escape'file.php'confirm'file.php'confirm'file.php'confirm'file.php'return true;'file.php'return false;'file.php'onclick'file.php'confirmJs'file.php'confirmMessage'file.php'confirm'file.php'link'file.php'url'file.php'attrs'file.php'content'file.php'styles.css'file.php'one.css'file.php'two.css'file.php'styles.css'file.php'block'file.php'styles.css'file.php'block'file.php'layoutCss'file.php'stylesheet'file.php'import'file.php'/'file.php'once'file.php'block'file.php'rel'file.php'stylesheet'file.php''file.php'block'file.php'//'file.php'fullBase'file.php'pathPrefix'file.php'once'file.php'once'file.php'rel'file.php'import'file.php'style'file.php'attrs'file.php'rel'file.php'block'file.php'content'file.php'@import url('file.php');'file.php'css'file.php'rel'file.php'rel'file.php'url'file.php'attrs'file.php'rel'file.php'block'file.php'block'file.php'block'file.php'block'file.php'block'file.php'styles.js'file.php'one.js'file.php'two.js'file.php'styles.js'file.php'block'file.php'bodyScript'file.php'block'file.php'once'file.php''file.php'block'file.php'//'file.php'fullBase'file.php'pathPrefix'file.php'once'file.php'javascriptlink'file.php'url'file.php'attrs'file.php'block'file.php'once'file.php'block'file.php'block'file.php'block'file.php'block'file.php'block'file.php'safe'file.php'block'file.php'safe'file.php'//<![CDATA['file.php'//]]>'file.php'safe'file.php'javascriptblock'file.php'attrs'file.php'block'file.php'content'file.php'block'file.php'block'file.php'block'file.php'script'file.php'block'file.php'margin'file.php'10px'file.php'padding'file.php'10px'file.php'margin:10px;padding:10px;'file.php':'file.php';'file.php' 'file.php'&raquo;'file.php'HtmlHelper::getCrumbs() is deprecated. 'file.php'Use the BreadcrumbsHelper instead.'file.php''file.php'first'file.php'last'file.php'HtmlHelper::getCrumbList() is deprecated. 'file.php'Use the BreadcrumbsHelper instead.'file.php'firstClass'file.php'first'file.php'lastClass'file.php'last'file.php'separator'file.php''file.php'escape'file.php'firstClass'file.php'lastClass'file.php'separator'file.php'escape'file.php'firstClass'file.php'lastClass'file.php'separator'file.php'escape'file.php''file.php'class'file.php'class'file.php'li'file.php'content'file.php'attrs'file.php'ul'file.php'content'file.php'attrs'file.php'HtmlHelper::_prepareCrumbs() is deprecated. 'file.php'Use the BreadcrumbsHelper instead.'file.php'url'file.php'/'file.php'text'file.php'url'file.php'/'file.php'text'file.php'cake'file.php'Home'file.php'url'file.php'text'file.php'url'file.php'text'file.php'escape'file.php'cake_icon.png'file.php'alt'file.php'CakePHP'file.php'cake_icon.png'file.php'alt'file.php'CakePHP'file.php'url'file.php'https://cakephp.org'file.php'url'file.php'fullBase'file.php'pathPrefix'file.php'alt'file.php'alt'file.php''file.php'url'file.php'url'file.php'url'file.php'image'file.php'url'file.php'attrs'file.php'link'file.php'url'file.php'attrs'file.php'content'file.php'tableheader'file.php'attrs'file.php'content'file.php' 'file.php's with TD'file.php' 'file.php'class'file.php'class'file.php' column-'file.php'class'file.php'column-'file.php'tablerow'file.php'attrs'file.php'content'file.php'tablecell'file.php'attrs'file.php'content'file.php'escape'file.php'escape'file.php'escape'file.php'tagstart'file.php'tag'file.php'attrs'file.php'tag'file.php'content'file.php'class'file.php'div'file.php'escape'file.php'class'file.php'para'file.php'parastart'file.php'attrs'file.php'content'file.php'audio.mp3'file.php'fullBase'file.php'video.mp4'file.php'text'file.php'Fallback text'file.php'video.mp4'file.php'src'file.php'video.ogv'file.php'type'file.php'theora, vorbis'file.php'tag'file.php'video'file.php'autoplay'file.php'theora, vorbis'file.php's guessed based on file'file.php'files/'file.php'pathPrefix'file.php'tag'file.php'pathPrefix'file.php'files/'file.php'text'file.php''file.php'tag'file.php'tag'file.php''file.php'src'file.php'type'file.php'src'file.php'type'file.php'src'file.php'src'file.php'tagselfclosing'file.php'tag'file.php'source'file.php'attrs'file.php'text'file.php'text'file.php'fullBase'file.php'src'file.php'src'file.php'src'file.php'type'file.php'#^video/#'file.php'video'file.php'audio'file.php'poster'file.php'poster'file.php'poster'file.php'pathPrefix'file.php'App.imageBaseUrl'file.php'text'file.php'tag'file.php'fullBase'file.php'pathPrefix'file.php'text'file.php'tag'file.php'ul'file.php'tag'file.php'attrs'file.php'tag'file.php'content'file.php''file.php'even'file.php'class'file.php'even'file.php'odd'file.php'class'file.php'odd'file.php'li'file.php'attrs'file.php'even'file.php'odd'file.php'content' => $item,
            ]);
            $index++;
        }

        return $out;
    }

    /**
     * Event listeners.
     *
     * @return array
     */
    public function implementedEvents()
    {
        return [];
    }
}
