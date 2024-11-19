<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright 2005-2011, Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         3.6.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Core;

use InvalidArgumentException;
use ReflectionClass;

/**
 * Base Plugin Class
 *
 * Every plugin should extends from this class or implement the interfaces and
 * include a plugin class in it'file.php'name'file.php'path'file.php'classPath'file.php'configPath'file.php'\\'file.php'/'file.php'src'file.php'config'file.php'src'file.php', 'file.php'routes.php'file.php'bootstrap.php';
        if (file_exists($bootstrap)) {
            require $bootstrap;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function console($commands)
    {
        return $commands->addMany($commands->discoverPlugin($this->getName()));
    }

    /**
     * {@inheritdoc}
     */
    public function middleware($middleware)
    {
        return $middleware;
    }
}
