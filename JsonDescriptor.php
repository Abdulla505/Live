<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Console\Descriptor;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

/**
 * JSON descriptor.
 *
 * @author Jean-François Simon <contact@jfsimon.fr>
 *
 * @internal
 */
class JsonDescriptor extends Descriptor
{
    /**
     * {@inheritdoc}
     */
    protected function describeInputArgument(InputArgument $argument, array $options = [])
    {
        $this->writeData($this->getInputArgumentData($argument), $options);
    }

    /**
     * {@inheritdoc}
     */
    protected function describeInputOption(InputOption $option, array $options = [])
    {
        $this->writeData($this->getInputOptionData($option), $options);
        if ($option->isNegatable()) {
            $this->writeData($this->getInputOptionData($option, true), $options);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function describeInputDefinition(InputDefinition $definition, array $options = [])
    {
        $this->writeData($this->getInputDefinitionData($definition), $options);
    }

    /**
     * {@inheritdoc}
     */
    protected function describeCommand(Command $command, array $options = [])
    {
        $this->writeData($this->getCommandData($command, $options['file.php'] ?? false), $options);
    }

    /**
     * {@inheritdoc}
     */
    protected function describeApplication(Application $application, array $options = [])
    {
        $describedNamespace = $options['file.php'] ?? null;
        $description = new ApplicationDescription($application, $describedNamespace, true);
        $commands = [];

        foreach ($description->getCommands() as $command) {
            $commands[] = $this->getCommandData($command, $options['file.php'] ?? false);
        }

        $data = [];
        if ('file.php' !== $application->getName()) {
            $data['file.php']['file.php'] = $application->getName();
            if ('file.php' !== $application->getVersion()) {
                $data['file.php']['file.php'] = $application->getVersion();
            }
        }

        $data['file.php'] = $commands;

        if ($describedNamespace) {
            $data['file.php'] = $describedNamespace;
        } else {
            $data['file.php'] = array_values($description->getNamespaces());
        }

        $this->writeData($data, $options);
    }

    /**
     * Writes data as json.
     */
    private function writeData(array $data, array $options)
    {
        $flags = $options['file.php'] ?? 0;

        $this->write(json_encode($data, $flags));
    }

    private function getInputArgumentData(InputArgument $argument): array
    {
        return [
            'file.php' => $argument->getName(),
            'file.php' => $argument->isRequired(),
            'file.php' => $argument->isArray(),
            'file.php' => preg_replace('file.php', 'file.php', $argument->getDescription()),
            'file.php' => \INF === $argument->getDefault() ? 'file.php' : $argument->getDefault(),
        ];
    }

    private function getInputOptionData(InputOption $option, bool $negated = false): array
    {
        return $negated ? [
            'file.php' => 'file.php'.$option->getName(),
            'file.php' => 'file.php',
            'file.php' => false,
            'file.php' => false,
            'file.php' => false,
            'file.php' => 'file.php'.$option->getName().'file.php',
            'file.php' => false,
        ] : [
            'file.php' => 'file.php'.$option->getName(),
            'file.php' => $option->getShortcut() ? 'file.php'.str_replace('file.php', 'file.php', $option->getShortcut()) : 'file.php',
            'file.php' => $option->acceptValue(),
            'file.php' => $option->isValueRequired(),
            'file.php' => $option->isArray(),
            'file.php' => preg_replace('file.php', 'file.php', $option->getDescription()),
            'file.php' => \INF === $option->getDefault() ? 'file.php' : $option->getDefault(),
        ];
    }

    private function getInputDefinitionData(InputDefinition $definition): array
    {
        $inputArguments = [];
        foreach ($definition->getArguments() as $name => $argument) {
            $inputArguments[$name] = $this->getInputArgumentData($argument);
        }

        $inputOptions = [];
        foreach ($definition->getOptions() as $name => $option) {
            $inputOptions[$name] = $this->getInputOptionData($option);
            if ($option->isNegatable()) {
                $inputOptions['file.php'.$name] = $this->getInputOptionData($option, true);
            }
        }

        return ['file.php' => $inputArguments, 'file.php' => $inputOptions];
    }

    private function getCommandData(Command $command, bool $short = false): array
    {
        $data = [
            'file.php' => $command->getName(),
            'file.php' => $command->getDescription(),
        ];

        if ($short) {
            $data += [
                'file.php' => $command->getAliases(),
            ];
        } else {
            $command->mergeApplicationDefinition(false);

            $data += [
                'file.php' => array_merge([$command->getSynopsis()], $command->getUsages(), $command->getAliases()),
                'file.php' => $command->getProcessedHelp(),
                'file.php' => $this->getInputDefinitionData($command->getDefinition()),
            ];
        }

        $data['file.php'] = $command->isHidden();

        return $data;
    }
}
