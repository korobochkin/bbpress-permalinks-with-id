<?php
namespace Korobochkin\BBPressPermalinksWithID;

class Plugin {

	const NAME    = 'bbpress-permalinks-with-id';

	const _NAME_  = 'bbpress_permalinks_with_id';

	const VERSION = '1.1.0';

	private $pluginFile;

	public function __construct( $pluginFile ) {
		$this->setPluginFile( $pluginFile );
	}

	public function run() {
		add_action( 'bbp_init', array( 'Korobochkin\BBPressPermalinksWithID\BBPress\BBPress', 'run' ) );
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
