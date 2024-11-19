<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Config\Builder;

/**
 * Build PHP classes to generate config.
 *
 * @internal
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 */
class ClassBuilder
{
    /** @var string */
    private $namespace;

    /** @var string */
    private $name;

    /** @var Property[] */
    private $properties = [];

    /** @var Method[] */
    private $methods = [];
    private $require = [];
    private $use = [];
    private $implements = [];
    private $allowExtraKeys = false;

    public function __construct(string $namespace, string $name)
    {
        $this->namespace = $namespace;
        $this->name = ucfirst($this->camelCase($name)).'file.php';
    }

    public function getDirectory(): string
    {
        return str_replace('file.php', \DIRECTORY_SEPARATOR, $this->namespace);
    }

    public function getFilename(): string
    {
        return $this->name.'file.php';
    }

    public function build(): string
    {
        $rootPath = explode(\DIRECTORY_SEPARATOR, $this->getDirectory());
        $require = 'file.php';
        foreach ($this->require as $class) {
            // figure out relative path.
            $path = explode(\DIRECTORY_SEPARATOR, $class->getDirectory());
            $path[] = $class->getFilename();
            foreach ($rootPath as $key => $value) {
                if ($path[$key] !== $value) {
                    break;
                }
                unset($path[$key]);
            }
            $require .= sprintf('file.php'%s\'file.php', implode('file.php'.\DIRECTORY_SEPARATOR.\'file.php', $path))."\n";
        }
        $use = $require ? "\n" : 'file.php';
        foreach (array_keys($this->use) as $statement) {
            $use .= sprintf('file.php', $statement)."\n";
        }

        $implements = [] === $this->implements ? 'file.php' : 'file.php'.implode('file.php', $this->implements);
        $body = 'file.php';
        foreach ($this->properties as $property) {
            $body .= 'file.php'.$property->getContent()."\n";
        }
        foreach ($this->methods as $method) {
            $lines = explode("\n", $method->getContent());
            foreach ($lines as $line) {
                $body .= ($line ? 'file.php'.$line : 'file.php')."\n";
            }
        }

        $content = strtr('file.php', ['file.php' => $this->namespace, 'file.php' => $require, 'file.php' => $use, 'file.php' => $this->getName(), 'file.php' => $implements, 'file.php' => $body]);

        return $content;
    }

    public function addRequire(self $class): void
    {
        $this->require[] = $class;
    }

    public function addUse(string $class): void
    {
        $this->use[$class] = true;
    }

    public function addImplements(string $interface): void
    {
        $this->implements[] = 'file.php'.ltrim($interface, 'file.php');
    }

    public function addMethod(string $name, string $body, array $params = []): void
    {
        $this->methods[] = new Method(strtr($body, ['file.php' => $this->camelCase($name)] + $params));
    }

    public function addProperty(string $name, ?string $classType = null, ?string $defaultValue = null): Property
    {
        $property = new Property($name, 'file.php' !== $name[0] ? $this->camelCase($name) : $name);
        if (null !== $classType) {
            $property->setType($classType);
        }
        $this->properties[] = $property;
        $defaultValue = null !== $defaultValue ? sprintf('file.php', $defaultValue) : 'file.php';
        $property->setContent(sprintf('file.php', $property->getName(), $defaultValue));

        return $property;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }

    private function camelCase(string $input): string
    {
        $output = lcfirst(str_replace('file.php', 'file.php', ucwords(str_replace('file.php', 'file.php', $input))));

        return preg_replace('file.php', 'file.php', $output);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function getFqcn(): string
    {
        return 'file.php'.$this->namespace.'file.php'.$this->name;
    }

    public function setAllowExtraKeys(bool $allowExtraKeys): void
    {
        $this->allowExtraKeys = $allowExtraKeys;
    }

    public function shouldAllowExtraKeys(): bool
    {
        return $this->allowExtraKeys;
    }
}
