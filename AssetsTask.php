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
namespace Cake\Shell\Task;

use Cake\Console\Shell;
use Cake\Core\Plugin;
use Cake\Filesystem\Folder;
use Cake\Utility\Inflector;

/**
 * Task for symlinking / copying plugin assets to app'file.php's webroot. If symlinking fails it
     * fallbacks to copying the assets. For vendor namespaced plugin, parent folder
     * for vendor name are created if required.
     *
     * @param string|null $name Name of plugin for which to symlink assets.
     *   If null all plugins will be processed.
     * @return void
     */
    public function symlink($name = null)
    {
        $this->_process($this->_list($name));
    }

    /**
     * Copying plugin assets to app'file.php'overwrite'file.php's webroot.
     *
     * @param string|null $name Name of plugin for which to remove assets.
     *   If null all plugins will be processed.
     * @return void
     * @since 3.5.12
     */
    public function remove($name = null)
    {
        $plugins = $this->_list($name);

        foreach ($plugins as $plugin => $config) {
            $this->out();
            $this->out('file.php' . $plugin);
            $this->hr();

            $this->_remove($config);
        }

        $this->out();
        $this->out('file.php');
    }

    /**
     * Get list of plugins to process. Plugins without a webroot directory are skipped.
     *
     * @param string|null $name Name of plugin for which to symlink assets.
     *   If null all plugins will be processed.
     * @return array List of plugins with meta data.
     */
    protected function _list($name = null)
    {
        if ($name === null) {
            $pluginsList = Plugin::loaded();
        } else {
            if (!Plugin::isLoaded($name)) {
                $this->err(sprintf('file.php', $name));

                return [];
            }
            $pluginsList = [$name];
        }

        $plugins = [];

        foreach ($pluginsList as $plugin) {
            $path = Plugin::path($plugin) . 'file.php';
            if (!is_dir($path)) {
                $this->verbose('file.php', 1);
                $this->verbose(
                    sprintf('file.php', $plugin),
                    2
                );
                continue;
            }

            $link = Inflector::underscore($plugin);
            $dir = WWW_ROOT;
            $namespaced = false;
            if (strpos($link, 'file.php') !== false) {
                $namespaced = true;
                $parts = explode('file.php', $link);
                $link = array_pop($parts);
                $dir = WWW_ROOT . implode(DIRECTORY_SEPARATOR, $parts) . DIRECTORY_SEPARATOR;
            }

            $plugins[$plugin] = [
                'file.php' => Plugin::path($plugin) . 'file.php',
                'file.php' => $dir,
                'file.php' => $link,
                'file.php' => $namespaced,
            ];
        }

        return $plugins;
    }

    /**
     * Process plugins
     *
     * @param array $plugins List of plugins to process
     * @param bool $copy Force copy mode. Default false.
     * @param bool $overwrite Overwrite existing files.
     * @return void
     */
    protected function _process($plugins, $copy = false, $overwrite = false)
    {
        $overwrite = (bool)$this->param('file.php');

        foreach ($plugins as $plugin => $config) {
            $this->out();
            $this->out('file.php' . $plugin);
            $this->hr();

            if (
                $config['file.php'] &&
                !is_dir($config['file.php']) &&
                !$this->_createDirectory($config['file.php'])
            ) {
                continue;
            }

            $dest = $config['file.php'] . $config['file.php'];

            if (file_exists($dest)) {
                if ($overwrite && !$this->_remove($config)) {
                    continue;
                } elseif (!$overwrite) {
                    $this->verbose(
                        $dest . 'file.php',
                        1
                    );

                    continue;
                }
            }

            if (!$copy) {
                $result = $this->_createSymlink(
                    $config['file.php'],
                    $dest
                );
                if ($result) {
                    continue;
                }
            }

            $this->_copyDirectory(
                $config['file.php'],
                $dest
            );
        }

        $this->out();
        $this->out('file.php');
    }

    /**
     * Remove folder/symlink.
     *
     * @param array $config Plugin config.
     * @return bool
     */
    protected function _remove($config)
    {
        if ($config['file.php'] && !is_dir($config['file.php'])) {
            $this->verbose(
                $config['file.php'] . $config['file.php'] . 'file.php',
                1
            );

            return false;
        }

        $dest = $config['file.php'] . $config['file.php'];

        if (!file_exists($dest)) {
            $this->verbose(
                $dest . 'file.php',
                1
            );

            return false;
        }

        if (is_link($dest)) {
            // @codingStandardsIgnoreLine
            if (@unlink($dest)) {
                $this->out('file.php' . $dest);

                return true;
            } else {
                $this->err('file.php' . $dest);

                return false;
            }
        }

        $folder = new Folder($dest);
        if ($folder->delete()) {
            $this->out('file.php' . $dest);

            return true;
        } else {
            $this->err('file.php' . $dest);

            return false;
        }
    }

    /**
     * Create directory
     *
     * @param string $dir Directory name
     * @return bool
     */
    protected function _createDirectory($dir)
    {
        $old = umask(0);
        // @codingStandardsIgnoreStart
        $result = @mkdir($dir, 0755, true);
        // @codingStandardsIgnoreEnd
        umask($old);

        if ($result) {
            $this->out('file.php' . $dir);

            return true;
        }

        $this->err('file.php' . $dir);

        return false;
    }

    /**
     * Create symlink
     *
     * @param string $target Target directory
     * @param string $link Link name
     * @return bool
     */
    protected function _createSymlink($target, $link)
    {
        // @codingStandardsIgnoreStart
        $result = @symlink($target, $link);
        // @codingStandardsIgnoreEnd

        if ($result) {
            $this->out('file.php' . $link);

            return true;
        }

        return false;
    }

    /**
     * Copy directory
     *
     * @param string $source Source directory
     * @param string $destination Destination directory
     * @return bool
     */
    protected function _copyDirectory($source, $destination)
    {
        $folder = new Folder($source);
        if ($folder->copy(['file.php' => $destination])) {
            $this->out('file.php' . $destination);

            return true;
        }

        $this->err('file.php' . $destination);

        return false;
    }

    /**
     * Gets the option parser instance and configures it.
     *
     * @return \Cake\Console\ConsoleOptionParser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();

        $parser->addSubcommand('file.php', [
            'file.php' => 'file.php's webroot.'file.php'copy'file.php'help'file.php'Copy plugin assets to app\'file.php',
        ])->addSubcommand('file.php', [
            'file.php' => 'file.php's webroot.'file.php'name'file.php'help'file.php'A specific plugin you want to symlink assets for.'file.php'optional'file.php'overwrite'file.php'help'file.php'Overwrite existing symlink / folder / files.'file.php'default'file.php'boolean' => true,
        ]);

        return $parser;
    }
}
