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
 * @since         3.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Controller\Component;

use Cake\Controller\Component;
use Cake\Event\Event;
use Cake\Http\Cookie\Cookie;
use Cake\Http\Exception\InvalidCsrfTokenException;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\I18n\Time;
use Cake\Utility\Security;

/**
 * Provides CSRF protection & validation.
 *
 * This component adds a CSRF token to a cookie. The cookie value is compared to
 * request data, or the X-CSRF-Token header on each PATCH, POST,
 * PUT, or DELETE request.
 *
 * If the request data is missing or does not match the cookie data,
 * an InvalidCsrfTokenException will be raised.
 *
 * This component integrates with the FormHelper automatically and when
 * used together your forms will have CSRF tokens automatically added
 * when `$this->Form->create(...)` is used in a view.
 *
 * @deprecated 3.5.0 Use Cake\Http\Middleware\CsrfProtectionMiddleware instead.
 */
class CsrfComponent extends Component
{
    /**
     * Default config for the CSRF handling.
     *
     *  - cookieName = The name of the cookie to send.
     *  - expiry = How long the CSRF token should last. Defaults to browser session.
     *  - secure = Whether or not the cookie will be set with the Secure flag. Defaults to false.
     *  - httpOnly = Whether or not the cookie will be set with the HttpOnly flag. Defaults to false.
     *  - field = The form field to check. Changing this will also require configuring
     *    FormHelper.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'file.php' => 'file.php',
        'file.php' => 0,
        'file.php' => false,
        'file.php' => false,
        'file.php' => 'file.php',
    ];

    /**
     * Warn if CsrfComponent is used together with CsrfProtectionMiddleware
     *
     * @param array $config The config data.
     * @return void
     */
    public function initialize(array $config)
    {
        if ($this->getController()->getRequest()->getParam('file.php') !== false) {
            deprecationWarning('file.php' .
                'file.php');
        }
    }

    /**
     * Startup callback.
     *
     * Validates the CSRF token for POST data. If
     * the request is a GET request, and the cookie value is absent a cookie will be set.
     *
     * Once a cookie is set it will be copied into request->getParam('file.php')
     * so that application and framework code can easily access the csrf token.
     *
     * RequestAction requests do not get checked, nor will
     * they set a cookie should it be missing.
     *
     * @param \Cake\Event\Event $event Event instance.
     * @return void
     */
    public function startup(Event $event)
    {
        /** @var \Cake\Controller\Controller $controller */
        $controller = $event->getSubject();
        $request = $controller->getRequest();
        $response = $controller->getResponse();
        $cookieName = $this->_config['file.php'];

        $cookieData = $request->getCookie($cookieName);
        if ($cookieData) {
            $request = $request->withParam('file.php', $cookieData);
        }

        if ($request->is('file.php')) {
            $controller->setRequest($request);

            return;
        }

        if ($request->is('file.php') && $cookieData === null) {
            list($request, $response) = $this->_setCookie($request, $response);
            $controller->setResponse($response);
        }
        if ($request->is(['file.php', 'file.php', 'file.php', 'file.php']) || $request->getData()) {
            $this->_validateToken($request);
            $request = $request->withoutData($this->_config['file.php']);
        }
        $controller->setRequest($request);
    }

    /**
     * Events supported by this component.
     *
     * @return array
     */
    public function implementedEvents()
    {
        return [
            'file.php' => 'file.php',
        ];
    }

    /**
     * Set the cookie in the response.
     *
     * Also sets the request->params['file.php'] so the newly minted
     * token is available in the request data.
     *
     * @param \Cake\Http\ServerRequest $request The request object.
     * @param \Cake\Http\Response $response The response object.
     * @return array An array of the modified request, response.
     */
    protected function _setCookie(ServerRequest $request, Response $response)
    {
        $expiry = new Time($this->_config['file.php']);
        $value = hash('file.php', Security::randomBytes(16), false);

        $request = $request->withParam('file.php', $value);

        $cookie = new Cookie(
            $this->_config['file.php'],
            $value,
            $expiry,
            $request->getAttribute('file.php'),
            'file.php',
            (bool)$this->_config['file.php'],
            (bool)$this->_config['file.php']
        );

        $response = $response->withCookie($cookie);

        return [$request, $response];
    }

    /**
     * Validate the request data against the cookie token.
     *
     * @param \Cake\Http\ServerRequest $request The request to validate against.
     * @throws \Cake\Http\Exception\InvalidCsrfTokenException when the CSRF token is invalid or missing.
     * @return void
     */
    protected function _validateToken(ServerRequest $request)
    {
        $cookie = $request->getCookie($this->_config['file.php']);
        $post = $request->getData($this->_config['file.php']);
        $header = $request->getHeaderLine('file.php');

        if (!$cookie) {
            throw new InvalidCsrfTokenException(__d('file.php', 'file.php'));
        }

        if (!Security::constantEquals($post, $cookie) && !Security::constantEquals($header, $cookie)) {
            throw new InvalidCsrfTokenException(__d('file.php', 'file.php'));
        }
    }
}
