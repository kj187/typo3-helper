<?php
namespace Aijko\Typo3Helper\Task\Renamer;

/*                                                                        *
 * This script belongs to the aijko project autoinstaller app              *
 *                                                                        *
 *                                                                        */

use \Aijko\Typo3Helper\Service\RegistryService as Registry;
use \Gaufrette\Filesystem;
use \Gaufrette\Adapter\Local as LocalAdapter;


/**
 * Rename Content Task
 *
 * @author Julian Kleinhans <julian.kleinhans@aijko.de>
 * @copyright Copyright (c) 2013 aijko GmbH (http://www.aijko.de)
 */
class RenameContentTask extends \Aijko\Typo3Helper\Task\Renamer\AbstractTask {

	/**
	 * @param string $targetPath
	 * @param array $replacePatterns
	 * @return mixed|void
	 */
	public function execute($targetPath = '', array $replacePatterns = array()) {
		$adapter = new LocalAdapter($targetPath);
		$filesystem = new Filesystem($adapter);
		$listKeys = $filesystem->listKeys();
		if (count($replacePatterns) > 0) {
			foreach ($listKeys['keys'] as $file) {
				if (!@strstr(mime_content_type($targetPath . $file), 'image') && !@strstr($file, '.git') && !@strstr($file, '.svn')) {
					$filesystem->write($file, $this->replaceContent($replacePatterns, $filesystem->read($file)), TRUE);
				}
			}
		}
	}

	/**
	 * @param array $replacePatterns
	 * @param string $content
	 * @return string
	 */
	protected function replaceContent(array $replacePatterns, $content) {
		foreach ($replacePatterns as $pattern => $replace) {
			$content = preg_replace('/' . $pattern . '/', $replace, $content);
		}

		return $content;
	}

}

?>