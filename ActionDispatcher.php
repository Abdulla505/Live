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

use Cake\Controller\Controller;
use Cake\Event\EventDispatcherTrait;
use Cake\Event\EventListenerInterface;
use Cake\Routing\Router;
use LogicException;

/**
 * This class provides compatibility with dispatcher filters
 * and interacting with the controller layers.
 *
 * Long term this should just be the controller dispatcher, but
 * for now it will do a bit more than that.
 */
class ActionDispatcher
{
    use EventDispatcherTrait;

    /**
     * Attached routing filters
     *
     * @var \Cake\Event\EventListenerInterface[]
     */
    protected $filters = [];

    /**
     * Controller factory instance.
     *
     * @var \Cake\Http\ControllerFactory
     */
    protected $factory;

    /**
     * Constructor
     *
     * @param \Cake\Http\ControllerFactory|null $factory A controller factory instance.
     * @param \Cake\Event\EventManager|null $eventManager An event manager if you want to inject one.
     * @param \Cake\Event\EventListenerInterface[] $filters The list of filters to include.
     */
    public function __construct($factory = null, $eventManager = null, array $filters = [])
    {
        if ($eventManager) {
            $this->setEventManager($eventManager);
        }
        foreach ($filters as $filter) {
            $this->addFilter($filter);
        }
        $this->factory = $factory ?: new ControllerFactory();
    }

    /**
     * Dispatches a Request & Response
     *
     * @param \Cake\Http\ServerRequest $request The request to dispatch.
     * @param \Cake\Http\Response $response The response to dispatch.
     * @return \Cake\Http\Response A modified/replaced response.
     * @throws \ReflectionException
     */
    public function dispatch(ServerRequest $request, Response $response)
    {
        if (Router::getRequest(true) !== $request) {
            Router::pushRequest($request);
        }
        $beforeEvent = $this->dispatchEvent('file.php', compact('file.php', 'file.php'));

        $request = $beforeEvent->getData('file.php');
        if ($beforeEvent->getResult() instanceof Response) {
            return $beforeEvent->getResult();
        }

        // Use the controller built by an beforeDispatch
        // event handler if there is one.
        if ($beforeEvent->getData('file.php') instanceof Controller) {
            $controller = $beforeEvent->getData('file.php');
        } else {
            $controller = $this->factory->create($request, $response);
        }

        $response = $this->_invoke($controller);
        if ($request->getParam('file.php')) {
            return $response;
        }

        $afterEvent = $this->dispatchEvent('file.php', compact('file.php', 'file.php'));

        return $afterEvent->getData('file.php');
    }

    /**
     * Invoke a controller'file.php'Dispatcher.invokeController'file.php'controller'file.php'Controller actions can only return Cake\Http\Response or null.'file.php'ActionDispatcher::addFilter() is deprecated. 'file.php'This is only available for backwards compatibility with DispatchFilters'
        );

        $this->filters[] = $filter;
        $this->getEventManager()->on($filter);
    }

    /**
     * Get the connected filters.
     *
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }
}
