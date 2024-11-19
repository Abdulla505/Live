<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         3.6.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Http\Middleware;

use Cake\Http\Exception\BadRequestException;
use Cake\Utility\Exception\XmlException;
use Cake\Utility\Xml;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Parse encoded request body data.
 *
 * Enables JSON and XML request payloads to be parsed into the request'file.php'PUT'file.php'POST'file.php'PATCH'file.php'DELETE'file.php'json'file.php'xml'file.php'methods'file.php'json'file.php'application/json'file.php'text/json'file.php'decodeJson'file.php'xml'file.php'application/xml'file.php'text/xml'file.php'decodeXml'file.php'methods'file.php'methods'file.php'text/csv'file.php';'file.php'Content-Type'file.php'return'file.php'domdocument'file.php'readFile' => false]);
            // We might not get child nodes if there are nested inline entities.
            if ((int)$xml->childNodes->length > 0) {
                return Xml::toArray($xml);
            }

            return [];
        } catch (XmlException $e) {
            return [];
        }
    }
}
