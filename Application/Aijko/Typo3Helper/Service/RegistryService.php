<?php
namespace Aijko\Typo3Helper\Service;

/*                                                                        *
 * This script belongs to the aijko project autoinstaller app              *
 *                                                                        *
 *                                                                        */

/**
 * Class RegistryService
 *
 * @author Julian Kleinhans <julian.kleinhans@aijko.de>
 * @copyright Copyright (c) 2013 aijko GmbH (http://www.aijko.de)
 */
class RegistryService {

	/**
	* Registry array of objects
	* @access private
	*/
	private static $objects = array();

	/**
	 * Registry array for key value storage
	 * @var array
	 */
	private static $keyValueStorage = array();

	/**
	* The instance of the registry
	* @access private
	*/
	private static $instance;

	/**
	 * Prevent directly access
	 */
	private function __construct(){}

	/**
	 * Prevent clone
	 */
	public function __clone(){}

	/**
	 * Singleton method used to access the object
	 * @access public
	 */
	public static function singleton() {
		if (!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/*
	 * Set value with key
	 */
	static function set($key, $val) {
		if (!isset(self::$keyValueStorage[$key])) {
			self::$keyValueStorage[$key] = $val;
		} else {
			throw new \Exception('Key "' . $key . '" schon belegt');
		}
	}

	/*
	 * Get value from key
	 */
	static function get($key) {
		if (isset(self::$keyValueStorage[$key])) {
			return self::$keyValueStorage[$key];
		}
		return NULL;
	}

	/*
	 * Set object
	 */
	static function setObject($key, $instance) {
		if (!isset(self::$objects[$key])) {
			self::$objects[$key] = $instance;
		} else {
			throw new \Exception('Key "' . $key . '" schon belegt');
		}
	}

	/*
	 * Get stored object
	 */
	static function getObject($key) {
		if (isset(self::$objects[$key])) {
			return self::$objects[$key];
		}
		return NULL;
	}

}

?>