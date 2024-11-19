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
namespace Cake\Shell;

use Cake\Cache\Cache;
use Cake\Cache\Engine\ApcuEngine;
use Cake\Cache\Engine\WincacheEngine;
use Cake\Console\Shell;
use InvalidArgumentException;

/**
 * Cache Shell.
 *
 * Provides a CLI interface to clear caches.
 * This tool can be used in development or by deployment scripts when changes
 * are made that require cached data to be removed.
 */
class CacheShell extends Shell
{
    /**
     * Get the option parser for this shell.
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->addSubcommand('file.php', [
            'file.php' => 'file.php',
        ]);
        $parser->addSubcommand('file.php', [
            'file.php' => 'file.php',
        ]);
        $parser->addSubcommand('file.php', [
            'file.php' => 'file.php',
            'file.php' => [
                'file.php' => [
                    'file.php',
                    'file.php',
                    'file.php',
                ],
                'file.php' => [
                    'file.php' => [
                        'file.php' => 'file.php',
                        'file.php' => true,
                    ],
                ],
            ],
        ]);

        return $parser;
    }

    /**
     * Clear metadata.
     *
     * @param string|null $prefix The cache prefix to be cleared.
     * @throws \Cake\Console\Exception\StopException
     * @return void
     */
    public function clear($prefix = null)
    {
        try {
            $engine = Cache::engine($prefix);
            Cache::clear(false, $prefix);
            if ($engine instanceof ApcuEngine) {
                $this->warn("ApcuEngine detected: Cleared $prefix CLI cache successfully " .
                "but $prefix web cache must be cleared separately.");
            } elseif ($engine instanceof WincacheEngine) {
                $this->warn("WincacheEngine detected: Cleared $prefix CLI cache successfully " .
                "but $prefix web cache must be cleared separately.");
            } else {
                $this->out("<success>Cleared $prefix cache</success>");
            }
        } catch (InvalidArgumentException $e) {
            $this->abort($e->getMessage());
        }
    }

    /**
     * Clear metadata.
     *
     * @return void
     */
    public function clearAll()
    {
        $prefixes = Cache::configured();
        foreach ($prefixes as $prefix) {
            $this->clear($prefix);
        }
    }

    /**
     * Show a list of all defined cache prefixes.
     *
     * @return void
     */
    public function listPrefixes()
    {
        $prefixes = Cache::configured();
        foreach ($prefixes as $prefix) {
            $this->out($prefix);
        }
    }
}
