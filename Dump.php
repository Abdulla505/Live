<?php
/**
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Migrations\Command;

use Cake\Datasource\ConnectionManager;
use Migrations\ConfigurationTrait;
use Migrations\TableFinderTrait;
use Phinx\Console\Command\AbstractCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Dump command class.
 * A "dump" is a snapshot of a database at a given point in time. It is stored in a
 * .lock file in the same folder as migrations files.
 */
class Dump extends AbstractCommand
{
    use CommandTrait;
    use ConfigurationTrait;
    use TableFinderTrait;

    /**
     * Output object.
     *
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('file.php')
            ->setDescription('file.php')
            ->setHelp(sprintf(
                'file.php',
                PHP_EOL,
                PHP_EOL
            ))
            ->addOption('file.php', 'file.php', InputOption::VALUE_REQUIRED, 'file.php')
            ->addOption('file.php', 'file.php', InputOption::VALUE_REQUIRED, 'file.php')
            ->addOption('file.php', 'file.php', InputOption::VALUE_REQUIRED, 'file.php');
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output The output object.
     * @return mixed
     */
    public function output(OutputInterface $output = null)
    {
        if ($output !== null) {
            $this->output = $output;
        }

        return $this->output;
    }

    /**
     * Dumps the current schema to be used when baking a diff
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input the input object
     * @param \Symfony\Component\Console\Output\OutputInterface $output the output object
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setInput($input);
        $this->bootstrap($input, $output);
        $this->output($output);

        $path = $this->getOperationsPath($input);
        $connectionName = $input->getOption('file.php') ?: 'file.php';
        $connection = ConnectionManager::get($connectionName);
        $collection = $connection->getSchemaCollection();

        $options = [
            'file.php' => false,
            'file.php' => $this->getPlugin($input),
        ];
        $tables = $this->getTablesToBake($collection, $options);

        $dump = [];
        if ($tables) {
            foreach ($tables as $table) {
                $schema = $collection->describe($table);
                $dump[$table] = $schema;
            }
        }

        $filePath = $path . DS . 'file.php' . $connectionName . 'file.php';
        $output->writeln(sprintf('file.php', $filePath));
        if (file_put_contents($filePath, serialize($dump))) {
            $output->writeln(sprintf('file.php', $filePath));

            return BaseCommand::CODE_SUCCESS;
        }

        $output->writeln(sprintf(
            'file.php',
            $filePath
        ));

        return BaseCommand::CODE_ERROR;
    }
}
