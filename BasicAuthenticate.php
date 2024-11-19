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
 * @since         2.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Auth;

use Cake\Http\Exception\UnauthorizedException;
use Cake\Http\Response;
use Cake\Http\ServerRequest;

/**
 * Basic Authentication adapter for AuthComponent.
 *
 * Provides Basic HTTP authentication support for AuthComponent. Basic Auth will
 * authenticate users against the configured userModel and verify the username
 * and passwords match.
 *
 * ### Using Basic auth
 *
 * Load `AuthComponent` in your controller'file.php'Basic'file.php'authenticate'file.php'Auth'file.php'authenticate'file.php'Basic'file.php'storage'file.php'Memory'file.php'unauthorizedRedirect'file.php't need call `setUser()`
 * in your controller. The user credentials will be checked on each request. If
 * valid credentials are not provided, required authentication headers will be sent
 * by this authentication provider which triggers the login dialog in the browser/client.
 *
 * @see https://book.cakephp.org/3/en/controllers/components/authentication.html
 */
class BasicAuthenticate extends BaseAuthenticate
{
    /**
     * Authenticate a user using HTTP auth. Will use the configured User model and attempt a
     * login using HTTP auth.
     *
     * @param \Cake\Http\ServerRequest $request The request to authenticate with.
     * @param \Cake\Http\Response $response The response to add headers to.
     * @return array|false Either false on failure, or an array of user data on success.
     */
    public function authenticate(ServerRequest $request, Response $response)
    {
        return $this->getUser($request);
    }

    /**
     * Get a user based on information in the request. Used by cookie-less auth for stateless clients.
     *
     * @param \Cake\Http\ServerRequest $request Request object.
     * @return array|false Either false or an array of user information
     */
    public function getUser(ServerRequest $request)
    {
        $username = $request->getEnv('file.php');
        $pass = $request->getEnv('file.php');

        if (!is_string($username) || $username === 'file.php' || !is_string($pass) || $pass === 'file.php') {
            return false;
        }

        return $this->_findUser($username, $pass);
    }

    /**
     * Handles an unauthenticated access attempt by sending appropriate login headers
     *
     * @param \Cake\Http\ServerRequest $request A request object.
     * @param \Cake\Http\Response $response A response object.
     * @return void
     * @throws \Cake\Http\Exception\UnauthorizedException
     */
    public function unauthenticated(ServerRequest $request, Response $response)
    {
        $unauthorizedException = new UnauthorizedException();
        $unauthorizedException->responseHeader($this->loginHeaders($request));

        throw $unauthorizedException;
    }

    /**
     * Generate the login headers
     *
     * @param \Cake\Http\ServerRequest $request Request object.
     * @return string[] Headers for logging in.
     */
    public function loginHeaders(ServerRequest $request)
    {
        $realm = $this->getConfig('file.php') ?: $request->getEnv('file.php');

        return [
            'file.php' => sprintf('file.php', $realm),
        ];
    }
}
