<?php
namespace Aijko\Typo3Helper\Core;

/*                                                                        *
 * This script belongs to the aijko project autoinstaller app              *
 *                                                                        *
 *                                                                        */

use \Aijko\Typo3Helper\Service\RegistryService as Registry;

/**
 * Class Bootstrap
 *
 * @author Julian Kleinhans <julian.kleinhans@aijko.de>
 * @copyright Copyright (c) 2013 aijko GmbH (http://www.aijko.de)
 */
class Bootstrap {

	/**
	 * @var \Aura\Cli\Stdio
	 */
	protected $cliStdio = NULL;

	/**
	 * Initialize bootstrap
	 *
	 * @return void
	 */
	public function initialize() {
		$this->initializeClassLoader();
		$this->initializeConfiguration();
		$this->initializeCommandLineSupport();
	}

	/**
	 * Initialize composer class loader
	 *
	 * @return void
	 */
	protected function initializeClassLoader() {
		include ROOT . '/Vendor/autoload.php';
	}

	/**
	 * Initialize configuration
	 *
	 * @return void
	 */
	protected function initializeConfiguration() {
		$configuration = new \Aijko\Typo3Helper\Configuration\Configuration();
		\Aijko\Typo3Helper\Service\RegistryService::set('configuration', $configuration->initialize()->getConfiguration());
	}

	/**
	 * Initialize command line StdIo support
	 *
	 * @return void
	 */
	protected function initializeCommandLineSupport() {
		$this->cliStdio = new \Aura\Cli\Stdio(
				new \Aura\Cli\StdioResource('php://stdin', 'r'),
				new \Aura\Cli\StdioResource('php://stdout', 'w+'),
				new \Aura\Cli\StdioResource('php://stderr', 'w+'),
				new \Aura\Cli\Vt100
			);
		\Aijko\Typo3Helper\Service\RegistryService::setObject('cliStdio', $this->cliStdio);
	}

	/**
	 * Run tasks
	 *
	 * @throw \Exception
	 * @return void
	 */
	public function runTasks() {
		try {
			$this->collectInformation();

			# Renamer
			(new \Aijko\Typo3Helper\Task\Renamer\Typo3\RenameContentTask())->execute(Registry::get('targetPath'));

			$this->cliStdio->outln('');
			$this->cliStdio->outln('Finish');
		} catch (\Exception $e) {
			$this->cliStdio->errln($e->getMessage());
		}
	}

	/**
	 * Collect all necessary informations
	 *
	 * @return void
	 */
	public function collectInformation() {
		$command = new \Aijko\Typo3Helper\Task\CollectInformationTask(
			new \Aura\Cli\Context($GLOBALS),
			$this->cliStdio,
			new \Aura\Cli\Getopt(
				new \Aura\Cli\OptionFactory,
				new \Aura\Cli\ExceptionFactory(
					new \Aura\Cli\Translator(
						include ROOT . 'Vendor/aura/cli/intl/en_US.php'
					)
				)
			),
			new \Aura\Cli\Signal
		);

		$command->exec();
	}

}

?>