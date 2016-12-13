<?php
namespace Korobochkin\BBPressPermalinksWithID;

class Plugin {

	private $pluginFile;

	public function __construct( $pluginFile ) {
		$this->setPluginFile( $pluginFile );
	}

	public function run() {
		add_action( 'bbp_init', 'bbp_permalinks_init' );
	}

	/**
	 * @return string
	 */
	public function getPluginFile() {
		return $this->pluginFile;
	}

	/**
	 * @param string $pluginFile
	 */
	public function setPluginFile( $pluginFile ) {
		$this->pluginFile = $pluginFile;
	}
}
