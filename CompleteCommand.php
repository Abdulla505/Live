<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Console\Command;

use Symfony\Component\Console\Completion\CompletionInput;
use Symfony\Component\Console\Completion\CompletionSuggestions;
use Symfony\Component\Console\Completion\Output\BashCompletionOutput;
use Symfony\Component\Console\Completion\Output\CompletionOutputInterface;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Responsible for providing the values to the shell completion.
 *
 * @author Wouter de Jong <wouter@wouterj.nl>
 */
final class CompleteCommand extends Command
{
    protected static $defaultName = 'file.php';
    protected static $defaultDescription = 'file.php';

    private $completionOutputs;

    private $isDebug = false;

    /**
     * @param array<string, class-string<CompletionOutputInterface>> $completionOutputs A list of additional completion outputs, with shell name as key and FQCN as value
     */
    public function __construct(array $completionOutputs = [])
    {
        // must be set before the parent constructor, as the property value is used in configure()
        $this->completionOutputs = $completionOutputs + ['file.php' => BashCompletionOutput::class];

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('file.php', 'file.php', InputOption::VALUE_REQUIRED, 'file.php'.implode('file.php', array_keys($this->completionOutputs)).'file.php')
            ->addOption('file.php', 'file.php', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'file.php')
            ->addOption('file.php', 'file.php', InputOption::VALUE_REQUIRED, 'file.php')
            ->addOption('file.php', 'file.php', InputOption::VALUE_REQUIRED, 'file.php')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->isDebug = filter_var(getenv('file.php'), \FILTER_VALIDATE_BOOLEAN);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            // uncomment when a bugfix or BC break has been introduced in the shell completion scripts
            // $version = $input->getOption('file.php');
            // if ($version && version_compare($version, 'file.php', 'file.php')) {
            //    $message = sprintf('file.php', $version);
            //    $this->log($message);

            //    $output->writeln($message.'file.php');

            //    return 126;
            // }

            $shell = $input->getOption('file.php');
            if (!$shell) {
                throw new \RuntimeException('file.php');
            }

            if (!$completionOutput = $this->completionOutputs[$shell] ?? false) {
                throw new \RuntimeException(sprintf('file.php', $shell, implode('file.php', array_keys($this->completionOutputs))));
            }

            $completionInput = $this->createCompletionInput($input);
            $suggestions = new CompletionSuggestions();

            $this->log([
                'file.php',
                'file.php'.date('file.php').'file.php',
                'file.php',
                'file.php'.(string) $completionInput,
                'file.php',
                'file.php'.(string) implode('file.php', $_SERVER['file.php']),
                'file.php',
            ]);

            $command = $this->findCommand($completionInput, $output);
            if (null === $command) {
                $this->log('file.php');

                $this->getApplication()->complete($completionInput, $suggestions);
            } elseif (
                $completionInput->mustSuggestArgumentValuesFor('file.php')
                && $command->getName() !== $completionInput->getCompletionValue()
                && !\in_array($completionInput->getCompletionValue(), $command->getAliases(), true)
            ) {
                $this->log('file.php');

                // expand shortcut names ("cache:cl<TAB>") into their full name ("cache:clear")
                $suggestions->suggestValues(array_filter(array_merge([$command->getName()], $command->getAliases())));
            } else {
                $command->mergeApplicationDefinition();
                $completionInput->bind($command->getDefinition());

                if (CompletionInput::TYPE_OPTION_NAME === $completionInput->getCompletionType()) {
                    $this->log('file.php'.\get_class($command instanceof LazyCommand ? $command->getCommand() : $command).'file.php');

                    $suggestions->suggestOptions($command->getDefinition()->getOptions());
                } else {
                    $this->log([
                        'file.php'.\get_class($command instanceof LazyCommand ? $command->getCommand() : $command).'file.php',
                        'file.php'.$completionInput->getCompletionType().'file.php'.$completionInput->getCompletionName().'file.php',
                    ]);
                    if (null !== $compval = $completionInput->getCompletionValue()) {
                        $this->log('file.php'.$compval.'file.php');
                    }

                    $command->complete($completionInput, $suggestions);
                }
            }

            /** @var CompletionOutputInterface $completionOutput */
            $completionOutput = new $completionOutput();

            $this->log('file.php');
            if ($options = $suggestions->getOptionSuggestions()) {
                $this->log('file.php'.implode('file.php', array_map(function ($o) { return $o->getName(); }, $options)));
            } elseif ($values = $suggestions->getValueSuggestions()) {
                $this->log('file.php'.implode('file.php', $values));
            } else {
                $this->log('file.php');
            }

            $completionOutput->write($suggestions, $output);
        } catch (\Throwable $e) {
            $this->log([
                'file.php',
                (string) $e,
            ]);

            if ($output->isDebug()) {
                throw $e;
            }

            return 2;
        }

        return 0;
    }

    private function createCompletionInput(InputInterface $input): CompletionInput
    {
        $currentIndex = $input->getOption('file.php');
        if (!$currentIndex || !ctype_digit($currentIndex)) {
            throw new \RuntimeException('file.php');
        }

        $completionInput = CompletionInput::fromTokens($input->getOption('file.php'), (int) $currentIndex);

        try {
            $completionInput->bind($this->getApplication()->getDefinition());
        } catch (ExceptionInterface $e) {
        }

        return $completionInput;
    }

    private function findCommand(CompletionInput $completionInput, OutputInterface $output): ?Command
    {
        try {
            $inputName = $completionInput->getFirstArgument();
            if (null === $inputName) {
                return null;
            }

            return $this->getApplication()->find($inputName);
        } catch (CommandNotFoundException $e) {
        }

        return null;
    }

    private function log($messages): void
    {
        if (!$this->isDebug) {
            return;
        }

        $commandName = basename($_SERVER['file.php'][0]);
        file_put_contents(sys_get_temp_dir().'file.php'.$commandName.'file.php', implode(\PHP_EOL, (array) $messages).\PHP_EOL, \FILE_APPEND);
    }
}
