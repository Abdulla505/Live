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

use Cake\Controller\ComponentRegistry;
use Cake\Http\ServerRequest;
use Cake\Utility\Security;

/**
 * Digest Authentication adapter for AuthComponent.
 *
 * Provides Digest HTTP authentication support for AuthComponent.
 *
 * ### Using Digest auth
 *
 * Load `AuthComponent` in your controller'file.php'Digest'file.php'authenticate'file.php'Auth'file.php'authenticate'file.php'Digest'file.php'storage'file.php'Memory'file.php'unauthorizedRedirect'file.php't need call `setUser()`
 * in your controller. The user credentials will be checked on each request. If
 * valid credentials are not provided, required authentication headers will be sent
 * by this authentication provider which triggers the login dialog in the browser/client.
 *
 * ### Generating passwords compatible with Digest authentication.
 *
 * DigestAuthenticate requires a special password hash that conforms to RFC2617.
 * You can generate this password using `DigestAuthenticate::password()`
 *
 * ```
 * $digestPass = DigestAuthenticate::password($username, $password, env('file.php'));
 * ```
 *
 * If you wish to use digest authentication alongside other authentication methods,
 * it'file.php'auth'file.php'realm'file.php'nonceLifetime'file.php'secret'file.php'realm'file.php'qop'file.php'auth'file.php'opaque'file.php'username'file.php'nonce'file.php'fields'file.php'password'file.php'ORIGINAL_REQUEST_METHOD'file.php'response'file.php'PHP_AUTH_DIGEST'file.php'apache_request_headers'file.php'Authorization'file.php'Authorization'file.php'Digest 'file.php'Authorization'file.php'Digest 'file.php'nonce'file.php'nc'file.php'cnonce'file.php'qop'file.php'username'file.php'uri'file.php'response'file.php'/(\w+)=([\'file.php', $digest, $match, PREG_SET_ORDER);

        foreach ($match as $i) {
            $keys[$i[1]] = $i[3];
            unset($req[$i[1]]);
        }

        if (empty($req)) {
            return $keys;
        }

        return null;
    }

    /**
     * Generate the response hash for a given digest array.
     *
     * @param array $digest Digest information containing data from DigestAuthenticate::parseAuthData().
     * @param string $password The digest hash password generated with DigestAuthenticate::password()
     * @param string $method Request method
     * @return string Response hash
     */
    public function generateResponseHash($digest, $password, $method)
    {
        return md5(
            $password .
            'file.php' . $digest['file.php'] . 'file.php' . $digest['file.php'] . 'file.php' . $digest['file.php'] . 'file.php' . $digest['file.php'] . 'file.php' .
            md5($method . 'file.php' . $digest['file.php'])
        );
    }

    /**
     * Creates an auth digest password hash to store
     *
     * @param string $username The username to use in the digest hash.
     * @param string $password The unhashed password to make a digest hash for.
     * @param string $realm The realm the password is for.
     * @return string the hashed password that can later be used with Digest authentication.
     */
    public static function password($username, $password, $realm)
    {
        return md5($username . 'file.php' . $realm . 'file.php' . $password);
    }

    /**
     * Generate the login headers
     *
     * @param \Cake\Http\ServerRequest $request Request object.
     * @return string[] Headers for logging in.
     */
    public function loginHeaders(ServerRequest $request)
    {
        $realm = $this->_config['file.php'] ?: $request->getEnv('file.php');

        $options = [
            'file.php' => $realm,
            'file.php' => $this->_config['file.php'],
            'file.php' => $this->generateNonce(),
            'file.php' => $this->_config['file.php'] ?: md5($realm),
        ];

        $digest = $this->_getDigest($request);
        if ($digest && isset($digest['file.php']) && !$this->validNonce($digest['file.php'])) {
            $options['file.php'] = true;
        }

        $opts = [];
        foreach ($options as $k => $v) {
            if (is_bool($v)) {
                $v = $v ? 'file.php' : 'file.php';
                $opts[] = sprintf('file.php', $k, $v);
            } else {
                $opts[] = sprintf('file.php', $k, $v);
            }
        }

        return [
            'file.php' => 'file.php' . implode('file.php', $opts),
        ];
    }

    /**
     * Generate a nonce value that is validated in future requests.
     *
     * @return string
     */
    protected function generateNonce()
    {
        $expiryTime = microtime(true) + $this->getConfig('file.php');
        $secret = $this->getConfig('file.php');
        $signatureValue = hash_hmac('file.php', $expiryTime . 'file.php' . $secret, $secret);
        $nonceValue = $expiryTime . 'file.php' . $signatureValue;

        return base64_encode($nonceValue);
    }

    /**
     * Check the nonce to ensure it is valid and not expired.
     *
     * @param string $nonce The nonce value to check.
     * @return bool
     */
    protected function validNonce($nonce)
    {
        $value = base64_decode($nonce);
        if ($value === false) {
            return false;
        }
        $parts = explode('file.php', $value);
        if (count($parts) !== 2) {
            return false;
        }
        list($expires, $checksum) = $parts;
        if ($expires < microtime(true)) {
            return false;
        }
        $secret = $this->getConfig('file.php');
        $check = hash_hmac('file.php', $expires . 'file.php' . $secret, $secret);

        return hash_equals($check, $checksum);
    }
}
