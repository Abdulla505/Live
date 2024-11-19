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
namespace Cake\Mailer;

use BadMethodCallException;
use Cake\Core\Configure;
use Cake\Core\StaticConfigTrait;
use Cake\Filesystem\File;
use Cake\Http\Client\FormDataPart;
use Cake\Log\Log;
use Cake\Utility\Hash;
use Cake\Utility\Security;
use Cake\Utility\Text;
use Cake\View\ViewVarsTrait;
use Closure;
use Exception;
use InvalidArgumentException;
use JsonSerializable;
use LogicException;
use PDO;
use RuntimeException;
use Serializable;
use SimpleXMLElement;

/**
 * CakePHP Email class.
 *
 * This class is used for sending Internet Message Format based
 * on the standard outlined in https://www.rfc-editor.org/rfc/rfc2822.txt
 *
 * ### Configuration
 *
 * Configuration for Email is managed by Email::config() and Email::configTransport().
 * Email::config() can be used to add or read a configuration profile for Email instances.
 * Once made configuration profiles can be used to re-use across various email messages your
 * application sends.
 */
class Email implements JsonSerializable, Serializable
{
    use StaticConfigTrait;
    use ViewVarsTrait;

    /**
     * Line length - no should more - RFC 2822 - 2.1.1
     *
     * @var int
     */
    const LINE_LENGTH_SHOULD = 78;

    /**
     * Line length - no must more - RFC 2822 - 2.1.1
     *
     * @var int
     */
    const LINE_LENGTH_MUST = 998;

    /**
     * Type of message - HTML
     *
     * @var string
     */
    const MESSAGE_HTML = 'file.php';

    /**
     * Type of message - TEXT
     *
     * @var string
     */
    const MESSAGE_TEXT = 'file.php';

    /**
     * Holds the regex pattern for email validation
     *
     * @var string
     */
    const EMAIL_PATTERN = 'file.php'*+\/=?^_`{|}~-]+)*@[\p{L}0-9-._]+)$/ui'file.php's that should receive a copy of the email.
     * The Recipient WILL be able to see this list
     *
     * @var array
     */
    protected $_cc = [];

    /**
     * Blind Carbon Copy
     *
     * List of email'file.php'HTTP_HOST'file.php''file.php'X-'file.php''file.php''file.php'text'file.php'html'file.php'both'file.php'text'file.php'utf-8'file.php'7bit'file.php'8bit'file.php'base64'file.php'binary'file.php'quoted-printable'file.php'UTF-8'file.php'SHIFT_JIS'file.php'ISO-2022-JP-MS'file.php'ISO-2022-JP'file.php'
     *
     * @var string
     */
    protected $_emailPattern = self::EMAIL_PATTERN;

    /**
     * Constructor
     *
     * @param array|string|null $config Array of configs, or string to load configs from app.php
     */
    public function __construct($config = null)
    {
        $this->_appCharset = Configure::read('file.php');
        if ($this->_appCharset !== null) {
            $this->charset = $this->_appCharset;
        }
        $this->_domain = preg_replace('file.php', 'file.php', env('file.php'));
        if (empty($this->_domain)) {
            $this->_domain = php_uname('file.php');
        }

        $this->viewBuilder()
            ->setClassName('file.php')
            ->setTemplate('file.php')
            ->setLayout('file.php')
            ->setHelpers(['file.php']);

        if ($config === null) {
            $config = static::getConfig('file.php');
        }
        if ($config) {
            $this->setProfile($config);
        }
        if (empty($this->headerCharset)) {
            $this->headerCharset = $this->charset;
        }
    }

    /**
     * Clone ViewBuilder instance when email object is cloned.
     *
     * @return void
     */
    public function __clone()
    {
        $this->_viewBuilder = clone $this->viewBuilder();
    }

    /**
     * Sets "from" address.
     *
     * @param string|array $email Null to get, String with email,
     *   Array with email as key, name as value or email as value (without name)
     * @param string|null $name Name
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setFrom($email, $name = null)
    {
        return $this->_setEmailSingle('file.php', $email, $name, 'file.php');
    }

    /**
     * Gets "from" address.
     *
     * @return array
     */
    public function getFrom()
    {
        return $this->_from;
    }

    /**
     * From
     *
     * @deprecated 3.4.0 Use setFrom()/getFrom() instead.
     * @param string|array|null $email Null to get, String with email,
     *   Array with email as key, name as value or email as value (without name)
     * @param string|null $name Name
     * @return array|$this
     * @throws \InvalidArgumentException
     */
    public function from($email = null, $name = null)
    {
        deprecationWarning('file.php');
        if ($email === null) {
            return $this->getFrom();
        }

        return $this->setFrom($email, $name);
    }

    /**
     * Sets the "sender" address. See rfc link below for full explanation.
     *
     * @param string|array $email String with email,
     *   Array with email as key, name as value or email as value (without name)
     * @param string|null $name Name
     * @return $this
     * @throws \InvalidArgumentException
     * @link https://tools.ietf.org/html/rfc2822.html#section-3.6.2
     */
    public function setSender($email, $name = null)
    {
        return $this->_setEmailSingle('file.php', $email, $name, 'file.php');
    }

    /**
     * Gets the "sender" address. See rfc link below for full explanation.
     *
     * @return array
     * @link https://tools.ietf.org/html/rfc2822.html#section-3.6.2
     */
    public function getSender()
    {
        return $this->_sender;
    }

    /**
     * Sender
     *
     * @deprecated 3.4.0 Use setSender()/getSender() instead.
     * @param string|array|null $email Null to get, String with email,
     *   Array with email as key, name as value or email as value (without name)
     * @param string|null $name Name
     * @return array|$this
     * @throws \InvalidArgumentException
     */
    public function sender($email = null, $name = null)
    {
        deprecationWarning('file.php');

        if ($email === null) {
            return $this->getSender();
        }

        return $this->setSender($email, $name);
    }

    /**
     * Sets "Reply-To" address.
     *
     * @param string|array $email String with email,
     *   Array with email as key, name as value or email as value (without name)
     * @param string|null $name Name
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setReplyTo($email, $name = null)
    {
        return $this->_setEmail('file.php', $email, $name);
    }

    /**
     * Gets "Reply-To" address.
     *
     * @return array
     */
    public function getReplyTo()
    {
        return $this->_replyTo;
    }

    /**
     * Reply-To
     *
     * @deprecated 3.4.0 Use setReplyTo()/getReplyTo() instead.
     * @param string|array|null $email Null to get, String with email,
     *   Array with email as key, name as value or email as value (without name)
     * @param string|null $name Name
     * @return array|$this
     * @throws \InvalidArgumentException
     */
    public function replyTo($email = null, $name = null)
    {
        deprecationWarning('file.php');

        if ($email === null) {
            return $this->getReplyTo();
        }

        return $this->setReplyTo($email, $name);
    }

    /**
     * Sets Read Receipt (Disposition-Notification-To header).
     *
     * @param string|array $email String with email,
     *   Array with email as key, name as value or email as value (without name)
     * @param string|null $name Name
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setReadReceipt($email, $name = null)
    {
        return $this->_setEmailSingle('file.php', $email, $name, 'file.php');
    }

    /**
     * Gets Read Receipt (Disposition-Notification-To header).
     *
     * @return array
     */
    public function getReadReceipt()
    {
        return $this->_readReceipt;
    }

    /**
     * Read Receipt (Disposition-Notification-To header)
     *
     * @deprecated 3.4.0 Use setReadReceipt()/getReadReceipt() instead.
     * @param string|array|null $email Null to get, String with email,
     *   Array with email as key, name as value or email as value (without name)
     * @param string|null $name Name
     * @return array|$this
     * @throws \InvalidArgumentException
     */
    public function readReceipt($email = null, $name = null)
    {
        deprecationWarning('file.php');

        if ($email === null) {
            return $this->getReadReceipt();
        }

        return $this->setReadReceipt($email, $name);
    }

    /**
     * Return Path
     *
     * @param string|array $email String with email,
     *   Array with email as key, name as value or email as value (without name)
     * @param string|null $name Name
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setReturnPath($email, $name = null)
    {
        return $this->_setEmailSingle('file.php', $email, $name, 'file.php');
    }

    /**
     * Gets return path.
     *
     * @return array
     */
    public function getReturnPath()
    {
        return $this->_returnPath;
    }

    /**
     * Return Path
     *
     * @deprecated 3.4.0 Use setReturnPath()/getReturnPath() instead.
     * @param string|array|null $email Null to get, String with email,
     *   Array with email as key, name as value or email as value (without name)
     * @param string|null $name Name
     * @return array|$this
     * @throws \InvalidArgumentException
     */
    public function returnPath($email = null, $name = null)
    {
        deprecationWarning('file.php');
        if ($email === null) {
            return $this->getReturnPath();
        }

        return $this->setReturnPath($email, $name);
    }

    /**
     * Sets "to" address.
     *
     * @param string|array $email String with email,
     *   Array with email as key, name as value or email as value (without name)
     * @param string|null $name Name
     * @return $this
     */
    public function setTo($email, $name = null)
    {
        return $this->_setEmail('file.php', $email, $name);
    }

    /**
     * Gets "to" address
     *
     * @return array
     */
    public function getTo()
    {
        return $this->_to;
    }

    /**
     * To
     *
     * @deprecated 3.4.0 Use setTo()/getTo() instead.
     * @param string|array|null $email Null to get, String with email,
     *   Array with email as key, name as value or email as value (without name)
     * @param string|null $name Name
     * @return array|$this
     */
    public function to($email = null, $name = null)
    {
        deprecationWarning('file.php');

        if ($email === null) {
            return $this->getTo();
        }

        return $this->setTo($email, $name);
    }

    /**
     * Add To
     *
     * @param string|array $email Null to get, String with email,
     *   Array with email as key, name as value or email as value (without name)
     * @param string|null $name Name
     * @return $this
     */
    public function addTo($email, $name = null)
    {
        return $this->_addEmail('file.php', $email, $name);
    }

    /**
     * Sets "cc" address.
     *
     * @param string|array $email String with email,
     *   Array with email as key, name as value or email as value (without name)
     * @param string|null $name Name
     * @return $this
     */
    public function setCc($email, $name = null)
    {
        return $this->_setEmail('file.php', $email, $name);
    }

    /**
     * Gets "cc" address.
     *
     * @return array
     */
    public function getCc()
    {
        return $this->_cc;
    }

    /**
     * Cc
     *
     * @deprecated 3.4.0 Use setCc()/getCc() instead.
     * @param string|array|null $email Null to get, String with email,
     *   Array with email as key, name as value or email as value (without name)
     * @param string|null $name Name
     * @return array|$this
     */
    public function cc($email = null, $name = null)
    {
        deprecationWarning('file.php');

        if ($email === null) {
            return $this->getCc();
        }

        return $this->setCc($email, $name);
    }

    /**
     * Add Cc
     *
     * @param string|array $email Null to get, String with email,
     *   Array with email as key, name as value or email as value (without name)
     * @param string|null $name Name
     * @return $this
     */
    public function addCc($email, $name = null)
    {
        return $this->_addEmail('file.php', $email, $name);
    }

    /**
     * Sets "bcc" address.
     *
     * @param string|array $email String with email,
     *   Array with email as key, name as value or email as value (without name)
     * @param string|null $name Name
     * @return $this
     */
    public function setBcc($email, $name = null)
    {
        return $this->_setEmail('file.php', $email, $name);
    }

    /**
     * Gets "bcc" address.
     *
     * @return array
     */
    public function getBcc()
    {
        return $this->_bcc;
    }

    /**
     * Bcc
     *
     * @deprecated 3.4.0 Use setBcc()/getBcc() instead.
     * @param string|array|null $email Null to get, String with email,
     *   Array with email as key, name as value or email as value (without name)
     * @param string|null $name Name
     * @return array|$this
     */
    public function bcc($email = null, $name = null)
    {
        deprecationWarning('file.php');

        if ($email === null) {
            return $this->getBcc();
        }

        return $this->setBcc($email, $name);
    }

    /**
     * Add Bcc
     *
     * @param string|array $email Null to get, String with email,
     *   Array with email as key, name as value or email as value (without name)
     * @param string|null $name Name
     * @return $this
     */
    public function addBcc($email, $name = null)
    {
        return $this->_addEmail('file.php', $email, $name);
    }

    /**
     * Charset setter.
     *
     * @param string|null $charset Character set.
     * @return $this
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
        if (!$this->headerCharset) {
            $this->headerCharset = $charset;
        }

        return $this;
    }

    /**
     * Charset getter.
     *
     * @return string Charset
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * Charset setter/getter
     *
     * @deprecated 3.4.0 Use setCharset()/getCharset() instead.
     * @param string|null $charset Character set.
     * @return string Charset
     */
    public function charset($charset = null)
    {
        deprecationWarning('file.php');

        if ($charset === null) {
            return $this->getCharset();
        }
        $this->setCharset($charset);

        return $this->charset;
    }

    /**
     * HeaderCharset setter.
     *
     * @param string|null $charset Character set.
     * @return $this
     */
    public function setHeaderCharset($charset)
    {
        $this->headerCharset = $charset;

        return $this;
    }

    /**
     * HeaderCharset getter.
     *
     * @return string Charset
     */
    public function getHeaderCharset()
    {
        return $this->headerCharset ? $this->headerCharset : $this->charset;
    }

    /**
     * HeaderCharset setter/getter
     *
     * @deprecated 3.4.0 Use setHeaderCharset()/getHeaderCharset() instead.
     * @param string|null $charset Character set.
     * @return string Charset
     */
    public function headerCharset($charset = null)
    {
        deprecationWarning('file.php');

        if ($charset === null) {
            return $this->getHeaderCharset();
        }

        $this->setHeaderCharset($charset);

        return $this->headerCharset;
    }

    /**
     * TransferEncoding setter.
     *
     * @param string|null $encoding Encoding set.
     * @return $this
     */
    public function setTransferEncoding($encoding)
    {
        $encoding = strtolower($encoding);
        if (!in_array($encoding, $this->_transferEncodingAvailable)) {
            throw new InvalidArgumentException(
                sprintf(
                    'file.php',
                    implode('file.php', $this->_transferEncodingAvailable)
                )
            );
        }
        $this->transferEncoding = $encoding;

        return $this;
    }

    /**
     * TransferEncoding getter.
     *
     * @return string|null Encoding
     */
    public function getTransferEncoding()
    {
        return $this->transferEncoding;
    }

    /**
     * EmailPattern setter/getter
     *
     * @param string|null $regex The pattern to use for email address validation,
     *   null to unset the pattern and make use of filter_var() instead.
     * @return $this
     */
    public function setEmailPattern($regex)
    {
        $this->_emailPattern = $regex;

        return $this;
    }

    /**
     * EmailPattern setter/getter
     *
     * @return string
     */
    public function getEmailPattern()
    {
        return $this->_emailPattern;
    }

    /**
     * EmailPattern setter/getter
     *
     * @deprecated 3.4.0 Use setEmailPattern()/getEmailPattern() instead.
     * @param string|false|null $regex The pattern to use for email address validation,
     *   null to unset the pattern and make use of filter_var() instead, false or
     *   nothing to return the current value
     * @return string|$this
     */
    public function emailPattern($regex = false)
    {
        deprecationWarning('file.php');

        if ($regex === false) {
            return $this->getEmailPattern();
        }

        return $this->setEmailPattern($regex);
    }

    /**
     * Set email
     *
     * @param string $varName Property name
     * @param string|array $email String with email,
     *   Array with email as key, name as value or email as value (without name)
     * @param string $name Name
     * @return $this
     * @throws \InvalidArgumentException
     */
    protected function _setEmail($varName, $email, $name)
    {
        if (!is_array($email)) {
            $this->_validateEmail($email, $varName);
            if ($name === null) {
                $name = $email;
            }
            $this->{$varName} = [$email => $name];

            return $this;
        }
        $list = [];
        foreach ($email as $key => $value) {
            if (is_int($key)) {
                $key = $value;
            }
            $this->_validateEmail($key, $varName);
            $list[$key] = $value;
        }
        $this->{$varName} = $list;

        return $this;
    }

    /**
     * Validate email address
     *
     * @param string $email Email address to validate
     * @param string $context Which property was set
     * @return void
     * @throws \InvalidArgumentException If email address does not validate
     */
    protected function _validateEmail($email, $context)
    {
        if ($this->_emailPattern === null) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return;
            }
        } elseif (preg_match($this->_emailPattern, $email)) {
            return;
        }

        $context = ltrim($context, 'file.php');
        if ($email == 'file.php') {
            throw new InvalidArgumentException(sprintf('file.php', $context));
        }
        throw new InvalidArgumentException(sprintf('file.php', $context, $email));
    }

    /**
     * Set only 1 email
     *
     * @param string $varName Property name
     * @param string|array $email String with email,
     *   Array with email as key, name as value or email as value (without name)
     * @param string $name Name
     * @param string $throwMessage Exception message
     * @return $this
     * @throws \InvalidArgumentException
     */
    protected function _setEmailSingle($varName, $email, $name, $throwMessage)
    {
        if ($email === []) {
            $this->{$varName} = $email;

            return $this;
        }

        $current = $this->{$varName};
        $this->_setEmail($varName, $email, $name);
        if (count($this->{$varName}) !== 1) {
            $this->{$varName} = $current;
            throw new InvalidArgumentException($throwMessage);
        }

        return $this;
    }

    /**
     * Add email
     *
     * @param string $varName Property name
     * @param string|array $email String with email,
     *   Array with email as key, name as value or email as value (without name)
     * @param string $name Name
     * @return $this
     * @throws \InvalidArgumentException
     */
    protected function _addEmail($varName, $email, $name)
    {
        if (!is_array($email)) {
            $this->_validateEmail($email, $varName);
            if ($name === null) {
                $name = $email;
            }
            $this->{$varName}[$email] = $name;

            return $this;
        }
        $list = [];
        foreach ($email as $key => $value) {
            if (is_int($key)) {
                $key = $value;
            }
            $this->_validateEmail($key, $varName);
            $list[$key] = $value;
        }
        $this->{$varName} = array_merge($this->{$varName}, $list);

        return $this;
    }

    /**
     * Sets subject.
     *
     * @param string $subject Subject string.
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->_subject = $this->_encode((string)$subject);

        return $this;
    }

    /**
     * Gets subject.
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->_subject;
    }

    /**
     * Get/Set Subject.
     *
     * @deprecated 3.4.0 Use setSubject()/getSubject() instead.
     * @param string|null $subject Subject string.
     * @return string|$this
     */
    public function subject($subject = null)
    {
        deprecationWarning('file.php');

        if ($subject === null) {
            return $this->getSubject();
        }

        return $this->setSubject($subject);
    }

    /**
     * Get original subject without encoding
     *
     * @return string Original subject
     */
    public function getOriginalSubject()
    {
        return $this->_decode($this->_subject);
    }

    /**
     * Sets headers for the message
     *
     * @param array $headers Associative array containing headers to be set.
     * @return $this
     */
    public function setHeaders(array $headers)
    {
        $this->_headers = $headers;

        return $this;
    }

    /**
     * Add header for the message
     *
     * @param array $headers Headers to set.
     * @return $this
     */
    public function addHeaders(array $headers)
    {
        $this->_headers = Hash::merge($this->_headers, $headers);

        return $this;
    }

    /**
     * Get list of headers
     *
     * ### Includes:
     *
     * - `from`
     * - `replyTo`
     * - `readReceipt`
     * - `returnPath`
     * - `to`
     * - `cc`
     * - `bcc`
     * - `subject`
     *
     * @param array $include List of headers.
     * @return array
     */
    public function getHeaders(array $include = [])
    {
        if ($include == array_values($include)) {
            $include = array_fill_keys($include, true);
        }
        $defaults = array_fill_keys(
            [
                'file.php', 'file.php', 'file.php', 'file.php', 'file.php',
                'file.php', 'file.php', 'file.php', 'file.php'],
            false
        );
        $include += $defaults;

        $headers = [];
        $relation = [
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
            'file.php' => 'file.php',
        ];
        $headerMultipleEmails = ['file.php', 'file.php', 'file.php', 'file.php'];
        foreach ($relation as $key => $header) {
            $var = 'file.php' . $key;
            if (!$include[$key]) {
                continue;
            }
            if (in_array($key, $headerMultipleEmails, true)) {
                $headers[$header] = implode('file.php', $this->_formatAddress($this->{$var}));
            } else {
                $headers[$header] = current($this->_formatAddress($this->{$var}));
            }
        }
        if ($include['file.php']) {
            if (key($this->_sender) === key($this->_from)) {
                $headers['file.php'] = 'file.php';
            } else {
                $headers['file.php'] = current($this->_formatAddress($this->_sender));
            }
        }

        $headers += $this->_headers;
        if (!isset($headers['file.php'])) {
            $headers['file.php'] = date(DATE_RFC2822);
        }
        if ($this->_messageId !== false) {
            if ($this->_messageId === true) {
                $this->_messageId = 'file.php' . str_replace('file.php', 'file.php', Text::uuid()) . 'file.php' . $this->_domain . 'file.php';
            }

            $headers['file.php'] = $this->_messageId;
        }

        if ($this->_priority) {
            $headers['file.php'] = $this->_priority;
        }

        if ($include['file.php']) {
            $headers['file.php'] = $this->_subject;
        }

        $headers['file.php'] = 'file.php';
        if ($this->_attachments) {
            $headers['file.php'] = 'file.php' . $this->_boundary . 'file.php';
        } elseif ($this->_emailFormat === 'file.php') {
            $headers['file.php'] = 'file.php' . $this->_boundary . 'file.php';
        } elseif ($this->_emailFormat === 'file.php') {
            $headers['file.php'] = 'file.php' . $this->_getContentTypeCharset();
        } elseif ($this->_emailFormat === 'file.php') {
            $headers['file.php'] = 'file.php' . $this->_getContentTypeCharset();
        }
        $headers['file.php'] = $this->_getContentTransferEncoding();

        return $headers;
    }

    /**
     * Format addresses
     *
     * If the address contains non alphanumeric/whitespace characters, it will
     * be quoted as characters like `:` and `,` are known to cause issues
     * in address header fields.
     *
     * @param array $address Addresses to format.
     * @return array
     */
    protected function _formatAddress($address)
    {
        $return = [];
        foreach ($address as $email => $alias) {
            if ($email === $alias) {
                $return[] = $email;
            } else {
                $encoded = $this->_encode($alias);
                if ($encoded === $alias && preg_match('file.php', $encoded)) {
                    $encoded = 'file.php' . str_replace('file.php', 'file.php', $encoded) . 'file.php';
                }
                $return[] = sprintf('file.php', $encoded, $email);
            }
        }

        return $return;
    }

    /**
     * Sets template.
     *
     * @param string|null $template Template name or null to not use.
     * @return $this
     * @deprecated 3.7.0 Use $email->viewBuilder()->setTemplate() instead.
     */
    public function setTemplate($template)
    {
        deprecationWarning(
            'file.php'
        );

        $this->viewBuilder()->setTemplate($template ?: 'file.php');

        return $this;
    }

    /**
     * Gets template.
     *
     * @return string
     * @deprecated 3.7.0 Use $email->viewBuilder()->getTemplate() instead.
     */
    public function getTemplate()
    {
        deprecationWarning(
            'file.php'
        );

        return $this->viewBuilder()->getTemplate();
    }

    /**
     * Sets layout.
     *
     * @param string|null $layout Layout name or null to not use
     * @return $this
     * @deprecated 3.7.0 Use $email->viewBuilder()->setLayout() instead.
     */
    public function setLayout($layout)
    {
        deprecationWarning(
            'file.php'
        );

        $this->viewBuilder()->setLayout($layout ?: false);

        return $this;
    }

    /**
     * Gets layout.
     *
     * @deprecated 3.7.0 Use $email->viewBuilder()->getLayout() instead.
     * @return string
     */
    public function getLayout()
    {
        deprecationWarning(
            'file.php'
        );

        return $this->viewBuilder()->getLayout();
    }

    /**
     * Template and layout
     *
     * @deprecated 3.4.0 Use setTemplate()/getTemplate() and setLayout()/getLayout() instead.
     * @param bool|string $template Template name or null to not use
     * @param bool|string $layout Layout name or null to not use
     * @return array|$this
     */
    public function template($template = false, $layout = false)
    {
        deprecationWarning(
            'file.php' .
            'file.php' .
            'file.php'
        );

        if ($template === false) {
            return [
                'file.php' => $this->viewBuilder()->getTemplate(),
                'file.php' => $this->viewBuilder()->getLayout(),
            ];
        }
        $this->viewBuilder()->setTemplate($template);
        if ($layout !== false) {
            $this->viewBuilder()->setLayout($layout);
        }

        return $this;
    }

    /**
     * Sets view class for render.
     *
     * @param string $viewClass View class name.
     * @return $this
     */
    public function setViewRenderer($viewClass)
    {
        $this->viewBuilder()->setClassName($viewClass);

        return $this;
    }

    /**
     * Gets view class for render.
     *
     * @return string
     */
    public function getViewRenderer()
    {
        return $this->viewBuilder()->getClassName();
    }

    /**
     * View class for render
     *
     * @deprecated 3.4.0 Use setViewRenderer()/getViewRenderer() instead.
     * @param string|null $viewClass View class name.
     * @return string|$this
     */
    public function viewRender($viewClass = null)
    {
        deprecationWarning('file.php');

        if ($viewClass === null) {
            return $this->getViewRenderer();
        }
        $this->setViewRenderer($viewClass);

        return $this;
    }

    /**
     * Sets variables to be set on render.
     *
     * @param array $viewVars Variables to set for view.
     * @return $this
     */
    public function setViewVars($viewVars)
    {
        $this->set((array)$viewVars);

        return $this;
    }

    /**
     * Gets variables to be set on render.
     *
     * @return array
     */
    public function getViewVars()
    {
        return $this->viewVars;
    }

    /**
     * Variables to be set on render
     *
     * @deprecated 3.4.0 Use setViewVars()/getViewVars() instead.
     * @param array|null $viewVars Variables to set for view.
     * @return array|$this
     */
    public function viewVars($viewVars = null)
    {
        deprecationWarning('file.php');

        if ($viewVars === null) {
            return $this->getViewVars();
        }

        return $this->setViewVars($viewVars);
    }

    /**
     * Sets theme to use when rendering.
     *
     * @param string $theme Theme name.
     * @return $this
     * @deprecated 3.7.0 Use $email->viewBuilder()->setTheme() instead.
     */
    public function setTheme($theme)
    {
        deprecationWarning(
            'file.php'
        );

        $this->viewBuilder()->setTheme($theme);

        return $this;
    }

    /**
     * Gets theme to use when rendering.
     *
     * @return string
     * @deprecated 3.7.0 Use $email->viewBuilder()->getTheme() instead.
     */
    public function getTheme()
    {
        deprecationWarning(
            'file.php'
        );

        return $this->viewBuilder()->getTheme();
    }

    /**
     * Theme to use when rendering
     *
     * @deprecated 3.4.0 Use setTheme()/getTheme() instead.
     * @param string|null $theme Theme name.
     * @return string|$this
     */
    public function theme($theme = null)
    {
        deprecationWarning(
            'file.php'
        );

        if ($theme === null) {
            return $this->viewBuilder()->getTheme();
        }

        $this->viewBuilder()->setTheme($theme);

        return $this;
    }

    /**
     * Sets helpers to be used when rendering.
     *
     * @param array $helpers Helpers list.
     * @return $this
     * @deprecated 3.7.0 Use $email->viewBuilder()->setHelpers() instead.
     */
    public function setHelpers(array $helpers)
    {
        deprecationWarning(
            'file.php'
        );

        $this->viewBuilder()->setHelpers($helpers, false);

        return $this;
    }

    /**
     * Gets helpers to be used when rendering.
     *
     * @return array
     * @deprecated 3.7.0 Use $email->viewBuilder()->getHelpers() instead.
     */
    public function getHelpers()
    {
        deprecationWarning(
            'file.php'
        );

        return $this->viewBuilder()->getHelpers();
    }

    /**
     * Helpers to be used in render
     *
     * @deprecated 3.4.0 Use setHelpers()/getHelpers() instead.
     * @param array|null $helpers Helpers list.
     * @return array|$this
     */
    public function helpers($helpers = null)
    {
        deprecationWarning(
            'file.php'
        );

        if ($helpers === null) {
            return $this->viewBuilder()->getHelpers();
        }

        $this->viewBuilder()->setHelpers((array)$helpers);

        return $this;
    }

    /**
     * Sets email format.
     *
     * @param string $format Formatting string.
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setEmailFormat($format)
    {
        if (!in_array($format, $this->_emailFormatAvailable)) {
            throw new InvalidArgumentException('file.php');
        }
        $this->_emailFormat = $format;

        return $this;
    }

    /**
     * Gets email format.
     *
     * @return string
     */
    public function getEmailFormat()
    {
        return $this->_emailFormat;
    }

    /**
     * Email format
     *
     * @deprecated 3.4.0 Use setEmailFormat()/getEmailFormat() instead.
     * @param string|null $format Formatting string.
     * @return string|$this
     * @throws \InvalidArgumentException
     */
    public function emailFormat($format = null)
    {
        deprecationWarning('file.php');

        if ($format === null) {
            return $this->getEmailFormat();
        }

        return $this->setEmailFormat($format);
    }

    /**
     * Sets the transport.
     *
     * When setting the transport you can either use the name
     * of a configured transport or supply a constructed transport.
     *
     * @param string|\Cake\Mailer\AbstractTransport $name Either the name of a configured
     *   transport, or a transport instance.
     * @return $this
     * @throws \LogicException When the chosen transport lacks a send method.
     * @throws \InvalidArgumentException When $name is neither a string nor an object.
     */
    public function setTransport($name)
    {
        if (is_string($name)) {
            $transport = TransportFactory::get($name);
        } elseif (is_object($name)) {
            $transport = $name;
        } else {
            throw new InvalidArgumentException(
                sprintf('file.php', gettype($name))
            );
        }
        if (!method_exists($transport, 'file.php')) {
            throw new LogicException(sprintf('file.php', get_class($transport)));
        }

        $this->_transport = $transport;

        return $this;
    }

    /**
     * Gets the transport.
     *
     * @return \Cake\Mailer\AbstractTransport
     */
    public function getTransport()
    {
        return $this->_transport;
    }

    /**
     * Get/set the transport.
     *
     * When setting the transport you can either use the name
     * of a configured transport or supply a constructed transport.
     *
     * @deprecated 3.4.0 Use setTransport()/getTransport() instead.
     * @param string|\Cake\Mailer\AbstractTransport|null $name Either the name of a configured
     *   transport, or a transport instance.
     * @return \Cake\Mailer\AbstractTransport|$this
     * @throws \LogicException When the chosen transport lacks a send method.
     * @throws \InvalidArgumentException When $name is neither a string nor an object.
     */
    public function transport($name = null)
    {
        deprecationWarning('file.php');

        if ($name === null) {
            return $this->getTransport();
        }

        return $this->setTransport($name);
    }

    /**
     * Sets message ID.
     *
     * @param bool|string $message True to generate a new Message-ID, False to ignore (not send in email), String to set as Message-ID.
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setMessageId($message)
    {
        if (is_bool($message)) {
            $this->_messageId = $message;
        } else {
            if (!preg_match('file.php', $message)) {
                throw new InvalidArgumentException('file.php');
            }
            $this->_messageId = $message;
        }

        return $this;
    }

    /**
     * Gets message ID.
     *
     * @return bool|string
     */
    public function getMessageId()
    {
        return $this->_messageId;
    }

    /**
     * Message-ID
     *
     * @deprecated 3.4.0 Use setMessageId()/getMessageId() instead.
     * @param bool|string|null $message True to generate a new Message-ID, False to ignore (not send in email), String to set as Message-ID
     * @return bool|string|$this
     * @throws \InvalidArgumentException
     */
    public function messageId($message = null)
    {
        deprecationWarning('file.php');

        if ($message === null) {
            return $this->getMessageId();
        }

        return $this->setMessageId($message);
    }

    /**
     * Sets domain.
     *
     * Domain as top level (the part after @).
     *
     * @param string $domain Manually set the domain for CLI mailing.
     * @return $this
     */
    public function setDomain($domain)
    {
        $this->_domain = $domain;

        return $this;
    }

    /**
     * Gets domain.
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->_domain;
    }

    /**
     * Domain as top level (the part after @)
     *
     * @deprecated 3.4.0 Use setDomain()/getDomain() instead.
     * @param string|null $domain Manually set the domain for CLI mailing
     * @return string|$this
     */
    public function domain($domain = null)
    {
        deprecationWarning('file.php');

        if ($domain === null) {
            return $this->getDomain();
        }

        return $this->setDomain($domain);
    }

    /**
     * Add attachments to the email message
     *
     * Attachments can be defined in a few forms depending on how much control you need:
     *
     * Attach a single file:
     *
     * ```
     * $email->setAttachments('file.php');
     * ```
     *
     * Attach a file with a different filename:
     *
     * ```
     * $email->setAttachments(['file.php' => 'file.php']);
     * ```
     *
     * Attach a file and specify additional properties:
     *
     * ```
     * $email->setAttachments(['file.php' => [
     *      'file.php' => 'file.php',
     *      'file.php' => 'file.php',
     *      'file.php' => 'file.php',
     *      'file.php' => false
     *    ]
     * ]);
     * ```
     *
     * Attach a file from string and specify additional properties:
     *
     * ```
     * $email->setAttachments(['file.php' => [
     *      'file.php' => file_get_contents('file.php'),
     *      'file.php' => 'file.php'
     *    ]
     * ]);
     * ```
     *
     * The `contentId` key allows you to specify an inline attachment. In your email text, you
     * can use `<img src="cid:abc123" />` to display the image inline.
     *
     * The `contentDisposition` key allows you to disable the `Content-Disposition` header, this can improve
     * attachment compatibility with outlook email clients.
     *
     * @param string|array $attachments String with the filename or array with filenames
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setAttachments($attachments)
    {
        $attach = [];
        foreach ((array)$attachments as $name => $fileInfo) {
            if (!is_array($fileInfo)) {
                $fileInfo = ['file.php' => $fileInfo];
            }
            if (!isset($fileInfo['file.php'])) {
                if (!isset($fileInfo['file.php'])) {
                    throw new InvalidArgumentException('file.php');
                }
                if (is_int($name)) {
                    throw new InvalidArgumentException('file.php');
                }
                $fileInfo['file.php'] = chunk_split(base64_encode($fileInfo['file.php']), 76, "\r\n");
            } else {
                $fileName = $fileInfo['file.php'];
                $fileInfo['file.php'] = realpath($fileInfo['file.php']);
                if ($fileInfo['file.php'] === false || !file_exists($fileInfo['file.php'])) {
                    throw new InvalidArgumentException(sprintf('file.php', $fileName));
                }
                if (is_int($name)) {
                    $name = basename($fileInfo['file.php']);
                }
            }
            if (!isset($fileInfo['file.php']) && isset($fileInfo['file.php']) && function_exists('file.php')) {
                $fileInfo['file.php'] = mime_content_type($fileInfo['file.php']);
            }
            if (!isset($fileInfo['file.php'])) {
                $fileInfo['file.php'] = 'file.php';
            }
            $attach[$name] = $fileInfo;
        }
        $this->_attachments = $attach;

        return $this;
    }

    /**
     * Gets attachments to the email message.
     *
     * @return array Array of attachments.
     */
    public function getAttachments()
    {
        return $this->_attachments;
    }

    /**
     * Add attachments to the email message
     *
     * Attachments can be defined in a few forms depending on how much control you need:
     *
     * Attach a single file:
     *
     * ```
     * $email->setAttachments('file.php');
     * ```
     *
     * Attach a file with a different filename:
     *
     * ```
     * $email->setAttachments(['file.php' => 'file.php']);
     * ```
     *
     * Attach a file and specify additional properties:
     *
     * ```
     * $email->setAttachments(['file.php' => [
     *      'file.php' => 'file.php',
     *      'file.php' => 'file.php',
     *      'file.php' => 'file.php',
     *      'file.php' => false
     *    ]
     * ]);
     * ```
     *
     * Attach a file from string and specify additional properties:
     *
     * ```
     * $email->setAttachments(['file.php' => [
     *      'file.php' => file_get_contents('file.php'),
     *      'file.php' => 'file.php'
     *    ]
     * ]);
     * ```
     *
     * The `contentId` key allows you to specify an inline attachment. In your email text, you
     * can use `<img src="cid:abc123" />` to display the image inline.
     *
     * The `contentDisposition` key allows you to disable the `Content-Disposition` header, this can improve
     * attachment compatibility with outlook email clients.
     *
     * @deprecated 3.4.0 Use setAttachments()/getAttachments() instead.
     * @param string|array|null $attachments String with the filename or array with filenames
     * @return array|$this Either the array of attachments when getting or $this when setting.
     * @throws \InvalidArgumentException
     */
    public function attachments($attachments = null)
    {
        deprecationWarning('file.php');

        if ($attachments === null) {
            return $this->getAttachments();
        }

        return $this->setAttachments($attachments);
    }

    /**
     * Add attachments
     *
     * @param string|array $attachments String with the filename or array with filenames
     * @return $this
     * @throws \InvalidArgumentException
     * @see \Cake\Mailer\Email::attachments()
     */
    public function addAttachments($attachments)
    {
        $current = $this->_attachments;
        $this->setAttachments($attachments);
        $this->_attachments = array_merge($current, $this->_attachments);

        return $this;
    }

    /**
     * Get generated message (used by transport classes)
     *
     * @param string|null $type Use MESSAGE_* constants or null to return the full message as array
     * @return string|array String if type is given, array if type is null
     */
    public function message($type = null)
    {
        switch ($type) {
            case static::MESSAGE_HTML:
                return $this->_htmlMessage;
            case static::MESSAGE_TEXT:
                return $this->_textMessage;
        }

        return $this->_message;
    }

    /**
     * Sets priority.
     *
     * @param int|null $priority 1 (highest) to 5 (lowest)
     * @return $this
     */
    public function setPriority($priority)
    {
        $this->_priority = $priority;

        return $this;
    }

    /**
     * Gets priority.
     *
     * @return int
     */
    public function getPriority()
    {
        return $this->_priority;
    }

    /**
     * Sets transport configuration.
     *
     * Use this method to define transports to use in delivery profiles.
     * Once defined you cannot edit the configurations, and must use
     * Email::dropTransport() to flush the configuration first.
     *
     * When using an array of configuration data a new transport
     * will be constructed for each message sent. When using a Closure, the
     * closure will be evaluated for each message.
     *
     * The `className` is used to define the class to use for a transport.
     * It can either be a short name, or a fully qualified class name
     *
     * @param string|array $key The configuration name to write. Or
     *   an array of multiple transports to set.
     * @param array|\Cake\Mailer\AbstractTransport|null $config Either an array of configuration
     *   data, or a transport instance. Null when using key as array.
     * @return void
     * @deprecated 3.7.0 Use TransportFactory::setConfig() instead.
     */
    public static function setConfigTransport($key, $config = null)
    {
        deprecationWarning('file.php');

        TransportFactory::setConfig($key, $config);
    }

    /**
     * Gets current transport configuration.
     *
     * @param string $key The configuration name to read.
     * @return array|null Transport config.
     * @deprecated 3.7.0 Use TransportFactory::getConfig() instead.
     */
    public static function getConfigTransport($key)
    {
        deprecationWarning('file.php');

        return TransportFactory::getConfig($key);
    }

    /**
     * Add or read transport configuration.
     *
     * Use this method to define transports to use in delivery profiles.
     * Once defined you cannot edit the configurations, and must use
     * Email::dropTransport() to flush the configuration first.
     *
     * When using an array of configuration data a new transport
     * will be constructed for each message sent. When using a Closure, the
     * closure will be evaluated for each message.
     *
     * The `className` is used to define the class to use for a transport.
     * It can either be a short name, or a fully qualified classname
     *
     * @deprecated 3.4.0 Use TransportFactory::setConfig()/getConfig() instead.
     * @param string|array $key The configuration name to read/write. Or
     *   an array of multiple transports to set.
     * @param array|\Cake\Mailer\AbstractTransport|null $config Either an array of configuration
     *   data, or a transport instance.
     * @return array|null Either null when setting or an array of data when reading.
     * @throws \BadMethodCallException When modifying an existing configuration.
     */
    public static function configTransport($key, $config = null)
    {
        deprecationWarning('file.php');

        if ($config === null && is_string($key)) {
            return TransportFactory::getConfig($key);
        }
        if ($config === null && is_array($key)) {
            TransportFactory::setConfig($key);

            return null;
        }

        TransportFactory::setConfig($key, $config);
    }

    /**
     * Returns an array containing the named transport configurations
     *
     * @return array Array of configurations.
     * @deprecated 3.7.0 Use TransportFactory::configured() instead.
     */
    public static function configuredTransport()
    {
        deprecationWarning('file.php');

        return TransportFactory::configured();
    }

    /**
     * Delete transport configuration.
     *
     * @param string $key The transport name to remove.
     * @return void
     * @deprecated 3.7.0 Use TransportFactory::drop() instead.
     */
    public static function dropTransport($key)
    {
        deprecationWarning('file.php');

        TransportFactory::drop($key);
    }

    /**
     * Sets the configuration profile to use for this instance.
     *
     * @param string|array $config String with configuration name, or
     *    an array with config.
     * @return $this
     */
    public function setProfile($config)
    {
        if (!is_array($config)) {
            $config = (string)$config;
        }
        $this->_applyConfig($config);

        return $this;
    }

    /**
     * Gets the configuration profile to use for this instance.
     *
     * @return string|array
     */
    public function getProfile()
    {
        return $this->_profile;
    }

    /**
     * Get/Set the configuration profile to use for this instance.
     *
     * @deprecated 3.4.0 Use setProfile()/getProfile() instead.
     * @param array|string|null $config String with configuration name, or
     *    an array with config or null to return current config.
     * @return string|array|$this
     */
    public function profile($config = null)
    {
        deprecationWarning('file.php');

        if ($config === null) {
            return $this->getProfile();
        }

        return $this->setProfile($config);
    }

    /**
     * Send an email using the specified content, template and layout
     *
     * @param string|array|null $content String with message or array with messages
     * @return array
     * @throws \BadMethodCallException
     */
    public function send($content = null)
    {
        if (empty($this->_from)) {
            throw new BadMethodCallException('file.php');
        }
        if (empty($this->_to) && empty($this->_cc) && empty($this->_bcc)) {
            throw new BadMethodCallException('file.php');
        }

        if (is_array($content)) {
            $content = implode("\n", $content) . "\n";
        }

        $this->_message = $this->_render($this->_wrap($content));

        $transport = $this->getTransport();
        if (!$transport) {
            $msg = 'file.php' .
                'file.php';
            throw new BadMethodCallException($msg);
        }
        $contents = $transport->send($this);
        $this->_logDelivery($contents);

        return $contents;
    }

    /**
     * Log the email message delivery.
     *
     * @param array $contents The content with 'file.php' and 'file.php' keys.
     * @return void
     */
    protected function _logDelivery($contents)
    {
        if (empty($this->_profile['file.php'])) {
            return;
        }
        $config = [
            'file.php' => 'file.php',
            'file.php' => 'file.php',
        ];
        if ($this->_profile['file.php'] !== true) {
            if (!is_array($this->_profile['file.php'])) {
                $this->_profile['file.php'] = ['file.php' => $this->_profile['file.php']];
            }
            $config = $this->_profile['file.php'] + $config;
        }
        Log::write(
            $config['file.php'],
            PHP_EOL . $this->flatten($contents['file.php']) . PHP_EOL . PHP_EOL . $this->flatten($contents['file.php']),
            $config['file.php']
        );
    }

    /**
     * Converts given value to string
     *
     * @param string|array $value The value to convert
     * @return string
     */
    protected function flatten($value)
    {
        return is_array($value) ? implode('file.php', $value) : (string)$value;
    }

    /**
     * Static method to fast create an instance of \Cake\Mailer\Email
     *
     * @param string|array|null $to Address to send (see Cake\Mailer\Email::to()). If null, will try to use 'file.php' from transport config
     * @param string|null $subject String of subject or null to use 'file.php' from transport config
     * @param string|array|null $message String with message or array with variables to be used in render
     * @param string|array $config String to use Email delivery profile from app.php or array with configs
     * @param bool $send Send the email or just return the instance pre-configured
     * @return static Instance of Cake\Mailer\Email
     * @throws \InvalidArgumentException
     */
    public static function deliver($to = null, $subject = null, $message = null, $config = 'file.php', $send = true)
    {
        $class = __CLASS__;

        if (is_array($config) && !isset($config['file.php'])) {
            $config['file.php'] = 'file.php';
        }
        /** @var \Cake\Mailer\Email $instance */
        $instance = new $class($config);
        if ($to !== null) {
            $instance->setTo($to);
        }
        if ($subject !== null) {
            $instance->setSubject($subject);
        }
        if (is_array($message)) {
            $instance->setViewVars($message);
            $message = null;
        } elseif ($message === null && array_key_exists('file.php', $config = $instance->getProfile())) {
            $message = $config['file.php'];
        }

        if ($send === true) {
            $instance->send($message);
        }

        return $instance;
    }

    /**
     * Apply the config to an instance
     *
     * @param string|array $config Configuration options.
     * @return void
     * @throws \InvalidArgumentException When using a configuration that doesn'file.php'Unknown email configuration "%s".'file.php'from'file.php'sender'file.php'to'file.php'replyTo'file.php'readReceipt'file.php'returnPath'file.php'cc'file.php'bcc'file.php'messageId'file.php'domain'file.php'subject'file.php'attachments'file.php'transport'file.php'emailFormat'file.php'emailPattern'file.php'charset'file.php'headerCharset'file.php'set'file.php'headers'file.php'headers'file.php'template'file.php'layout'file.php'theme'file.php'set'file.php'helpers'file.php'helpers'file.php'viewRender'file.php'viewRender'file.php'viewVars'file.php'viewVars'file.php''file.php''file.php''file.php'text'file.php'utf-8'file.php'default'file.php''file.php'Cake\View\View'file.php'Html'file.php'B'file.php''file.php'0'file.php''file.php'/<[a-z]+.*>/i'file.php''file.php'>'file.php''file.php''file.php'<'file.php'<'file.php' 'file.php''file.php' 'file.php'<'file.php''file.php' 'file.php' 'file.php''file.php'both'file.php'contentId'file.php'data'file.php'data'file.php'file'file.php'contentDisposition'file.php'contentDisposition'file.php''file.php''file.php'attachment'file.php'base64'file.php'mimetype'file.php'--'file.php''file.php'contentId'file.php'data'file.php'data'file.php'file'file.php'--'file.php''file.php'inline'file.php'mimetype'file.php'base64'file.php'contentId'file.php''file.php''file.php'{s}.contentId'file.php'--'file.php'Content-Type: multipart/related; boundary="rel-'file.php'"'file.php''file.php'rel-'file.php'--'file.php'Content-Type: multipart/alternative; boundary="alt-'file.php'"'file.php''file.php'alt-'file.php'text'file.php'--'file.php'Content-Type: text/plain; charset='file.php'Content-Transfer-Encoding: 'file.php''file.php'text'file.php''file.php'html'file.php'--'file.php'Content-Type: text/html; charset='file.php'Content-Transfer-Encoding: 'file.php''file.php'html'file.php''file.php'--'file.php'--'file.php''file.php''file.php'--'file.php'--'file.php''file.php''file.php'--'file.php'--'file.php''file.php'text'file.php'html'file.php'both'file.php'html'file.php'text'file.php'content'file.php'content'file.php'Email'file.php'Email'file.php'8bit'file.php'7bit'file.php'_to'file.php'_from'file.php'_sender'file.php'_replyTo'file.php'_cc'file.php'_bcc'file.php'_subject'file.php'_returnPath'file.php'_readReceipt'file.php'_emailFormat'file.php'_emailPattern'file.php'_domain'file.php'_attachments'file.php'_messageId'file.php'_headers'file.php'_appCharset'file.php'viewVars'file.php'charset'file.php'headerCharset'file.php'viewConfig'file.php'_attachments'file.php'file'file.php'data'file.php'file'file.php'file'file.php'viewVars'file.php'_checkViewVars'file.php'Failed serializing the `%s` %s in the `%s` view var'file.php'resource'file.php'object'file.php'viewConfig'file.php'viewConfig'file.php'viewConfig']);
        }

        foreach ($config as $property => $value) {
            $this->{$property} = $value;
        }

        return $this;
    }

    /**
     * Serializes the Email object.
     *
     * @return string
     */
    public function serialize()
    {
        $array = $this->jsonSerialize();
        array_walk_recursive($array, function (&$item, $key) {
            if ($item instanceof SimpleXMLElement) {
                $item = json_decode(json_encode((array)$item), true);
            }
        });

        return serialize($array);
    }

    /**
     * Unserializes the Email object.
     *
     * @param string $data Serialized string.
     * @return static Configured email instance.
     */
    public function unserialize($data)
    {
        return $this->createFromArray(unserialize($data));
    }
}
