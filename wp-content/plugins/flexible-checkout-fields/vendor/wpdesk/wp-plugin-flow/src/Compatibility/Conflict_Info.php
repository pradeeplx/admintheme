<?php

/**
 * Data container for info about dependency conflict
 */
class WPDesk_Conflict_Info {

	/** @var string  */
	private $conflicting_plugin;

	/** @var string  */
	private $library_name;

	/** @var string  */
	private $required_version;

	/** @var string  */
	private $version_found;

	/**
	 * @param string $conflicting_plugin
	 * @param string $library_name
	 * @param string $required_version
	 * @param string $version_found
	 */
	public function __construct($conflicting_plugin, $library_name, $required_version, $version_found) {
		$this->conflicting_plugin = $conflicting_plugin;
		$this->library_name = $library_name;
		$this->required_version = $required_version;
		$this->version_found = $version_found;
	}

	/**
	 * @return string
	 */
	public function get_conflict_plugin_name() {
		return $this->conflicting_plugin;
	}

	/**
	 * @return string
	 */
	public function get_conflict_library_name() {
		return $this->library_name;
	}

	/**
	 * @return string
	 */
	public function get_required_library_version() {
		return $this->required_version;
	}

	/**
	 * @return string
	 */
	public function get_provided_library_version() {
		return $this->version_found;
	}
}