<?php
namespace Aijko\Typo3Helper\Task\Renamer;

/*                                                                        *
 * This script belongs to the aijko project autoinstaller app              *
 *                                                                        *
 *                                                                        */

use \Aijko\Typo3Helper\Service\RegistryService as Registry;


/**
 * Abstract Task
 *
 * @author Julian Kleinhans <julian.kleinhans@aijko.de>
 * @copyright Copyright (c) 2013 aijko GmbH (http://www.aijko.de)
 */
abstract class AbstractTask {

	/**
	 * @var \Aura\Cli\Stdio
	 */
	protected $cliStdio = NULL;

	/**
	 * @var array
	 */
	protected $configuration = [];

	/**
	 * @var string
	 */
	protected $newProjectKey = '';

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->cliStdio = Registry::getObject('cliStdio');
		$this->configuration = Registry::get('configuration');
		$this->newProjectKey = \strtoupper(\trim(\str_replace(' ', '_', Registry::get('projectKey'))));

		return $this;
	}

	/**
	 * @param string $targetPath
	 * @param array $replacePatterns
	 * @return mixed
	 */
	abstract public function execute($targetPath = '', array $replacePatterns = array());

}

?>