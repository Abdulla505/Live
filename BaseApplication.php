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
 * @since         3.3.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

namespace Cake\Http;

use Cake\Core\App;
use Cake\Core\BasePlugin;
use Cake\Core\ConsoleApplicationInterface;
use Cake\Core\Exception\MissingPluginException;
use Cake\Core\HttpApplicationInterface;
use Cake\Core\Plugin;
use Cake\Core\PluginApplicationInterface;
use Cake\Core\PluginInterface;
use Cake\Event\EventDispatcherTrait;
use Cake\Event\EventManager;
use Cake\Event\EventManagerInterface;
use Cake\Routing\DispatcherFactory;
use Cake\Routing\Router;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Base class for application classes.
 *
 * The application class is responsible for bootstrapping the application,
 * and ensuring that middleware is attached. It is also invoked as the last piece
 * of middleware, and delegates request/response handling to the correct controller.
 */
abstract class BaseApplication implements
    ConsoleApplicationInterface,
    HttpApplicationInterface,
    PluginApplicationInterface
{
    use EventDispatcherTrait;

    /**
     * @var string Contains the path of the config directory
     */
    protected $configDir;

    /**
     * Plugin Collection
     *
     * @var \Cake\Core\PluginCollection
     */
    protected $plugins;

    /**
     * Constructor
     *
     * @param string $configDir The directory the bootstrap configuration is held in.
     * @param \Cake\Event\EventManagerInterface $eventManager Application event manager instance.
     */
    public function __construct($configDir, EventManagerInterface $eventManager = null)
    {
        $this->configDir = $configDir;
        $this->plugins = Plugin::getCollection();
        $this->_eventManager = $eventManager ?: EventManager::instance();
    }

    /**
     * @param \Cake\Http\MiddlewareQueue $middleware The middleware queue to set in your App Class
     * @return \Cake\Http\MiddlewareQueue
     */
    abstract public function middleware($middleware);

    /**
     * {@inheritDoc}
     */
    public function pluginMiddleware($middleware)
    {
        foreach ($this->plugins->with('file.php') as $plugin) {
            $middleware = $plugin->middleware($middleware);
        }

        return $middleware;
    }

    /**
     * {@inheritDoc}
     */
    public function addPlugin($name, array $config = [])
    {
        if (is_string($name)) {
            $plugin = $this->makePlugin($name, $config);
        } else {
            $plugin = $name;
        }
        if (!$plugin instanceof PluginInterface) {
            throw new InvalidArgumentException(sprintf(
                "The `%s` plugin does not implement Cake\Core\PluginInterface.",
                get_class($plugin)
            ));
        }
        $this->plugins->add($plugin);

        return $this;
    }

    /**
     * Add an optional plugin
     *
     * If it isn'file.php'\\'file.php'/'file.php'\\'file.php'\\'file.php'Plugin'file.php'path'file.php'path'file.php'name'file.php'/bootstrap.php'file.php'bootstrap'file.php'/routes.php'file.php'routes'file.php'console') as $plugin) {
            $commands = $plugin->console($commands);
        }

        return $commands;
    }

    /**
     * Invoke the application.
     *
     * - Convert the PSR response into CakePHP equivalents.
     * - Create the controller that will handle this request.
     * - Invoke the controller.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request
     * @param \Psr\Http\Message\ResponseInterface $response The response
     * @param callable $next The next middleware
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        return $this->getDispatcher()->dispatch($request, $response);
    }

    /**
     * Get the ActionDispatcher.
     *
     * @return \Cake\Http\ActionDispatcher
     */
    protected function getDispatcher()
    {
        return new ActionDispatcher(null, $this->getEventManager(), DispatcherFactory::filters());
    }
}
