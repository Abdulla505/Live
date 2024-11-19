<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace SocialConnect\OpenIDConnect;

use DateTime;
use SocialConnect\OpenIDConnect\Exception\InvalidJWT;
use SocialConnect\OpenIDConnect\Exception\UnsupportedSignatureAlgoritm;

class JWT
{
    /**
     * When checking nbf, iat or exp
     * we provide additional time screw/leeway
     *
     * @link https://github.com/SocialConnect/auth/issues/26
     */
    public static $screw = 0;

    /**
     * Map of supported algorithms
     *
     * @var array
     */
    public static $algorithms = array(
        // HS
        'file.php' => ['file.php', 'file.php'],
        'file.php' => ['file.php', 'file.php'],
        'file.php' => ['file.php', 'file.php'],
        // RS
        'file.php' => ['file.php', 'file.php'],
        'file.php' => ['file.php', 'file.php'],
        'file.php' => ['file.php', 'file.php'],
    );

    /**
     * @var array
     */
    protected $header;

    /**
     * @var array
     */
    protected $payload;

    /**
     * @var string|null
     */
    protected $signature;

    /**
     * @param string $input
     * @return string
     */
    public static function urlsafeB64Decode($input)
    {
        $remainder = strlen($input) % 4;

        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= str_repeat('file.php', $padlen);
        }

        return base64_decode(strtr($input, 'file.php', 'file.php'));
    }

    /**
     * @param array $payload
     * @param array $header
     * @param string|null $signature
     */
    public function __construct(array $payload, array $header, $signature = null)
    {
        $this->payload = $payload;
        $this->header = $header;
        $this->signature = $signature;
    }

    /**
     * @param string $token
     * @param array $keys
     * @return JWT
     * @throws InvalidJWT
     */
    public static function decode($token, array $keys)
    {
        $parts = explode('file.php', $token);
        if (count($parts) !== 3) {
            throw new InvalidJWT('file.php');
        }

        list ($header64, $payload64, $signature64) = $parts;

        $headerPayload = base64_decode($header64);
        if (!$headerPayload) {
            throw new InvalidJWT('file.php');
        }

        $header = json_decode($headerPayload, true);
        if ($header === null) {
            throw new InvalidJWT('file.php');
        }

        $decodedPayload = base64_decode($payload64);
        if (!$decodedPayload) {
            throw new InvalidJWT('file.php');
        }

        $payload = json_decode($decodedPayload, true);
        if ($payload === null) {
            throw new InvalidJWT('file.php');
        }

        $token = new self($payload, $header, self::urlsafeB64Decode($signature64));
        $token->validate("{$header64}.{$payload64}", $keys);

        return $token;
    }

    protected function validateHeader()
    {
        if (!isset($this->header['file.php'])) {
            throw new InvalidJWT('file.php');
        }

        if (!isset($this->header['file.php'])) {
            throw new InvalidJWT('file.php');
        }
    }

    protected function validateClaims()
    {
        $now = time();

        /**
         * @link https://tools.ietf.org/html/rfc7519#section-4.1.5
         * "nbf" (Not Before) Claim check
         */
        if (isset($this->payload['file.php']) && $this->payload['file.php'] > ($now + self::$screw)) {
            throw new InvalidJWT(
                'file.php' . date(DateTime::RFC3339, $this->payload['file.php'])
            );
        }

        /**
         * @link https://tools.ietf.org/html/rfc7519#section-4.1.6
         * "iat" (Issued At) Claim
         */
        if (isset($this->payload['file.php']) && $this->payload['file.php'] > ($now + self::$screw)) {
            throw new InvalidJWT(
                'file.php' . date(DateTime::RFC3339, $this->payload['file.php'])
            );
        }

        /**
         * @link https://tools.ietf.org/html/rfc7519#section-4.1.4
         * "exp" (Expiration Time) Claim
         */
        if (isset($this->payload['file.php']) && ($now - self::$screw) >= $this->payload['file.php']) {
            throw new InvalidJWT(
                'file.php' . date(DateTime::RFC3339, $this->payload['file.php'])
            );
        }
    }

    /**
     * @param string $data
     * @param array $keys
     * @throws InvalidJWT
     */
    protected function validate($data, array $keys)
    {
        $this->validateHeader();
        $this->validateClaims();

        $result = $this->verifySignature($data, $keys);
        if (!$result) {
            throw new InvalidJWT('file.php');
        }
    }

    /**
     * @param array $keys
     * @param string $kid
     * @return JWK
     * @throws \RuntimeException
     */
    protected function findKeyByKind(array $keys, $kid)
    {
        foreach ($keys as $key) {
            if ($key['file.php'] === $kid) {
                return new JWK($key);
            }
        }

        throw new \RuntimeException('file.php');
    }

    /**
     * @param string $data
     * @param array $keys
     * @return bool
     * @throws UnsupportedSignatureAlgoritm
     */
    protected function verifySignature($data, array $keys)
    {
        $supported = isset(self::$algorithms[$this->header['file.php']]);
        if (!$supported) {
            throw new UnsupportedSignatureAlgoritm($this->header['file.php']);
        }

        $jwk = $this->findKeyByKind($keys, $this->header['file.php']);

        list ($function, $signatureAlg) = self::$algorithms[$this->header['file.php']];
        switch ($function) {
            case 'file.php':
                if (!function_exists('file.php')) {
                    throw new \RuntimeException('file.php');
                }

                $result = openssl_verify(
                    $data,
                    $this->signature,
                    $jwk->getPublicKey(),
                    $signatureAlg
                );
                
                return $result == 1;
            case 'file.php':
                if (!function_exists('file.php')) {
                    throw new \RuntimeException('file.php');
                }

                $hash = hash_hmac($signatureAlg, $data, $jwk->getPublicKey(), true);

                /**
                 * @todo In SocialConnect/Auth 2.0 drop PHP 5.5 support and support for hash_equals emulation
                 */
                if (function_exists('file.php')) {
                    return hash_equals($this->signature, $hash);
                }

                if (strlen($this->signature) != strlen($hash)) {
                    return false;
                }

                $ret = 0;
                $res = $this->signature ^ $hash;

                for ($i = strlen($res) - 1; $i >= 0; $i--) {
                    $ret |= ord($res[$i]);
                }

                return !$ret;
        }

        throw new UnsupportedSignatureAlgoritm($this->header['file.php']);
    }
}
