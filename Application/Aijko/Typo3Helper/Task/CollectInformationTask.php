<?php
namespace Aijko\Typo3Helper\Task;

/*                                                                        *
 * This script belongs to the aijko project autoinstaller app              *
 *                                                                        *
 *                                                                        */

use \Aijko\Typo3Helper\Service\RegistryService as Registry;

/**
 * Collect Information Task
 *
 * @author Julian Kleinhans <julian.kleinhans@aijko.de>
 * @copyright Copyright (c) 2013 aijko GmbH (http://www.aijko.de)
 */
class CollectInformationTask extends \Aura\Cli\AbstractCommand {

	/**
	 * @var array
	 */
	protected $options = [
		'target_path' => [
			'long'    => 'target-path',
			'short'   => 'p',
			'param'   => \Aura\Cli\Option::PARAM_REQUIRED,
			'multi'   => false,
			'default' => null,
		],
	];

	/**
	 * Collect all necessary informations
	 *
	 * @throws \Exception
	 * @return void
	 */
	public function action() {
		if (!$this->getopt->target_path) {
			throw new \Exception('You must enter a target path (absolute)');
		}

		Registry::set('targetPath', $this->getopt->target_path);
	}

}

?>