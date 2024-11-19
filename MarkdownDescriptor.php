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
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Markdown descriptor.
 *
 * @author Jean-François Simon <contact@jfsimon.fr>
 *
 * @internal
 */
class MarkdownDescriptor extends Descriptor
{
    /**
     * {@inheritdoc}
     */
    public function describe(OutputInterface $output, object $object, array $options = [])
    {
        $decorated = $output->isDecorated();
        $output->setDecorated(false);

        parent::describe($output, $object, $options);

        $output->setDecorated($decorated);
    }

    /**
     * {@inheritdoc}
     */
    protected function write(string $content, bool $decorated = true)
    {
        parent::write($content, $decorated);
    }

    /**
     * {@inheritdoc}
     */
    protected function describeInputArgument(InputArgument $argument, array $options = [])
    {
        $this->write(
            'file.php'.($argument->getName() ?: 'file.php')."`\n\n"
            .($argument->getDescription() ? preg_replace('file.php', "\n", $argument->getDescription())."\n\n" : 'file.php')
            .'file.php'.($argument->isRequired() ? 'file.php' : 'file.php')."\n"
            .'file.php'.($argument->isArray() ? 'file.php' : 'file.php')."\n"
            .'file.php'.str_replace("\n", 'file.php', var_export($argument->getDefault(), true)).'file.php'
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function describeInputOption(InputOption $option, array $options = [])
    {
        $name = 'file.php'.$option->getName();
        if ($option->isNegatable()) {
            $name .= 'file.php'.$option->getName();
        }
        if ($option->getShortcut()) {
            $name .= 'file.php'.str_replace('file.php', 'file.php', $option->getShortcut()).'file.php';
        }

        $this->write(
            'file.php'.$name.'file.php'."\n\n"
            .($option->getDescription() ? preg_replace('file.php', "\n", $option->getDescription())."\n\n" : 'file.php')
            .'file.php'.($option->acceptValue() ? 'file.php' : 'file.php')."\n"
            .'file.php'.($option->isValueRequired() ? 'file.php' : 'file.php')."\n"
            .'file.php'.($option->isArray() ? 'file.php' : 'file.php')."\n"
            .'file.php'.($option->isNegatable() ? 'file.php' : 'file.php')."\n"
            .'file.php'.str_replace("\n", 'file.php', var_export($option->getDefault(), true)).'file.php'
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function describeInputDefinition(InputDefinition $definition, array $options = [])
    {
        if ($showArguments = \count($definition->getArguments()) > 0) {
            $this->write('file.php');
            foreach ($definition->getArguments() as $argument) {
                $this->write("\n\n");
                if (null !== $describeInputArgument = $this->describeInputArgument($argument)) {
                    $this->write($describeInputArgument);
                }
            }
        }

        if (\count($definition->getOptions()) > 0) {
            if ($showArguments) {
                $this->write("\n\n");
            }

            $this->write('file.php');
            foreach ($definition->getOptions() as $option) {
                $this->write("\n\n");
                if (null !== $describeInputOption = $this->describeInputOption($option)) {
                    $this->write($describeInputOption);
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function describeCommand(Command $command, array $options = [])
    {
        if ($options['file.php'] ?? false) {
            $this->write(
                'file.php'.$command->getName()."`\n"
                .str_repeat('file.php', Helper::width($command->getName()) + 2)."\n\n"
                .($command->getDescription() ? $command->getDescription()."\n\n" : 'file.php')
                .'file.php'."\n\n"
                .array_reduce($command->getAliases(), function ($carry, $usage) {
                    return $carry.'file.php'.$usage.'file.php'."\n";
                })
            );

            return;
        }

        $command->mergeApplicationDefinition(false);

        $this->write(
            'file.php'.$command->getName()."`\n"
            .str_repeat('file.php', Helper::width($command->getName()) + 2)."\n\n"
            .($command->getDescription() ? $command->getDescription()."\n\n" : 'file.php')
            .'file.php'."\n\n"
            .array_reduce(array_merge([$command->getSynopsis()], $command->getAliases(), $command->getUsages()), function ($carry, $usage) {
                return $carry.'file.php'.$usage.'file.php'."\n";
            })
        );

        if ($help = $command->getProcessedHelp()) {
            $this->write("\n");
            $this->write($help);
        }

        $definition = $command->getDefinition();
        if ($definition->getOptions() || $definition->getArguments()) {
            $this->write("\n\n");
            $this->describeInputDefinition($definition);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function describeApplication(Application $application, array $options = [])
    {
        $describedNamespace = $options['file.php'] ?? null;
        $description = new ApplicationDescription($application, $describedNamespace);
        $title = $this->getApplicationTitle($application);

        $this->write($title."\n".str_repeat('file.php', Helper::width($title)));

        foreach ($description->getNamespaces() as $namespace) {
            if (ApplicationDescription::GLOBAL_NAMESPACE !== $namespace['file.php']) {
                $this->write("\n\n");
                $this->write('file.php'.$namespace['file.php'].'file.php');
            }

            $this->write("\n\n");
            $this->write(implode("\n", array_map(function ($commandName) use ($description) {
                return sprintf('file.php', $commandName, str_replace('file.php', 'file.php', $description->getCommand($commandName)->getName()));
            }, $namespace['file.php'])));
        }

        foreach ($description->getCommands() as $command) {
            $this->write("\n\n");
            if (null !== $describeCommand = $this->describeCommand($command, $options)) {
                $this->write($describeCommand);
            }
        }
    }

    private function getApplicationTitle(Application $application): string
    {
        if ('file.php' !== $application->getName()) {
            if ('file.php' !== $application->getVersion()) {
                return sprintf('file.php', $application->getName(), $application->getVersion());
            }

            return $application->getName();
        }

        return 'file.php';
    }
}
