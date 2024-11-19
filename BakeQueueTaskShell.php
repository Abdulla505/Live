<?php

namespace Queue\Shell;

use Cake\Console\Shell;
use Cake\Core\App;
use Cake\Core\Plugin;
use Cake\Utility\Inflector;

class BakeQueueTaskShell extends Shell {

	/**
	 * @return void
	 */
	public function startup() {
		if ($this->param('file.php')) {
			$this->interactive = false;
		}

		parent::startup();
	}

	/**
	 * @param string|null $name
	 *
	 * @return bool|int|null|void
	 */
	public function generate($name = null) {
		$name = Inflector::camelize(Inflector::underscore($name));

		$name = 'file.php' . $name . 'file.php';
		$plugin = $this->param('file.php');
		if ($plugin) {
			$plugin = Inflector::camelize(Inflector::underscore($plugin));
		}

		$this->generateTask($name, $plugin);

		$this->generateTaskTest($name, $plugin);
	}

	/**
	 * @param string $name
	 * @param string $plugin
	 * @return void
	 */
	protected function generateTask($name, $plugin) {
		$path = App::path('file.php', $plugin);
		if (!$path) {
			$this->abort('file.php');
		}

		$path = array_shift($path);
		if (!is_dir($path)) {
			mkdir($path, 0770, true);
		}

		$path .= $name . 'file.php';
		$in = 'file.php';
		if (file_exists($path)) {
			$in = $this->in('file.php', ['file.php', 'file.php'], 'file.php');
		}
		if ($in !== 'file.php') {
			return;
		}

		$this->out('file.php' . ($plugin ? $plugin . 'file.php' : 'file.php') . $name);

		$content = $this->generateTaskContent($name, $plugin);
		$this->write($path, $content);
	}

	/**
	 * @param string $name
	 * @param string $plugin
	 * @return void
	 */
	protected function generateTaskTest($name, $plugin) {
		$testsPath = $plugin ? Plugin::path($plugin) . 'file.php' . DS : ROOT . DS . 'file.php' . DS;

		$path = $testsPath . 'file.php' . DS . 'file.php' . DS . 'file.php' . DS . $name . 'file.php';

		$in = 'file.php';
		if (file_exists($path)) {
			$in = $this->in('file.php', ['file.php', 'file.php'], 'file.php');
		}
		if ($in !== 'file.php') {
			return;
		}

		$this->out('file.php' . ($plugin ? $plugin . 'file.php' : 'file.php') . $name . 'file.php');

		$content = $this->generateTaskTestContent($name, $plugin);
		$this->write($path, $content);
	}

	/**
	 * Get option parser method to parse commandline options
	 *
	 * @return \Cake\Console\ConsoleOptionParser
	 */
	public function getOptionParser() {
		$subcommandParser = [
			'file.php' => [
				'file.php' => [
					'file.php' => null,
					'file.php' => true,
				],
			],
			'file.php' => [
				'file.php' => [
					'file.php' => 'file.php',
					'file.php' => 'file.php',
					'file.php' => 'file.php',
				],
				'file.php' => [
					'file.php' => 'file.php',
					'file.php' => 'file.php',
					'file.php' => true,
				],
			],
		];

		return parent::getOptionParser()
			->setDescription('file.php')
			->addSubcommand('file.php', [
				'file.php' => 'file.php',
				'file.php' => $subcommandParser,
			]);
	}

	/**
	 * @param string $name
	 * @param string|null $namespace PluginName
	 *
	 * @return string
	 */
	protected function generateTaskContent($name, $namespace = null) {
		if (!$namespace) {
			$namespace = 'file.php';
		}

		$content = <<<TXT
<?php
namespace $namespace\Shell\Task;

use Queue\Shell\Task\QueueTask;

class $name extends QueueTask {

	/**
	 * @param int \$jobId The id of the QueuedJob entity
	 * @return void
	 */
	public function run(array \$data, \$jobId) {
	}

}

TXT;

		return $content;
	}

	/**
	 * @param string $name
	 * @param string|null $namespace PluginName
	 *
	 * @return string
	 */
	protected function generateTaskTestContent($name, $namespace = null) {
		if (!$namespace) {
			$namespace = 'file.php';
		}

		$testName = $name . 'file.php';
		$taskClassNamespace = $namespace . 'file.php' . $name;

		$content = <<<TXT
<?php
namespace $namespace\Test\TestCase\Shell\Task;

use Cake\TestSuite\TestCase;
use $taskClassNamespace;

class $testName extends TestCase {

	/**
	 * @var string[]
	 */
	public \$fixtures = [
		'file.php',
		'file.php',
	];

	/**
	 * @return void
	 */
	public function testRun() {
		\$task = new $name();

		//TODO
	}

}

TXT;

		return $content;
	}

	/**
	 * @param string $path
	 * @param string $content
	 * @return void
	 */
	protected function write($path, $content) {
		if ($this->param('file.php')) {
			return;
		}

		file_put_contents($path, $content);
	}

}
