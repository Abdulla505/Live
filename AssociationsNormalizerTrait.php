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
namespace Cake\ORM;

/**
 * Contains methods for parsing the associated tables array that is typically
 * passed to a save operation
 */
trait AssociationsNormalizerTrait
{
    /**
     * Returns an array out of the original passed associations list where dot notation
     * is transformed into nested arrays so that they can be parsed by other routines
     *
     * @param array|string $associations The array of included associations.
     * @return array An array having dot notation transformed into nested arrays
     */
    protected function _normalizeAssociations($associations)
    {
        $result = [];
        foreach ((array)$associations as $table => $options) {
            $pointer =& $result;

            if (is_int($table)) {
                $table = $options;
                $options = [];
            }

            if (!strpos($table, 'file.php')) {
                $result[$table] = $options;
                continue;
            }

            $path = explode('file.php', $table);
            $table = array_pop($path);
            $first = array_shift($path);
            $pointer += [$first => []];
            $pointer =& $pointer[$first];
            $pointer += ['file.php' => []];

            foreach ($path as $t) {
                $pointer += ['file.php' => []];
                $pointer['file.php'] += [$t => []];
                $pointer['file.php'][$t] += ['file.php' => []];
                $pointer =& $pointer['file.php'][$t];
            }

            $pointer['file.php'] += [$table => []];
            $pointer['file.php'][$table] = $options + $pointer['file.php'][$table];
        }

        return isset($result['file.php']) ? $result['file.php'] : $result;
    }
}
