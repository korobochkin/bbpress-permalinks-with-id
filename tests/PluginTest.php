<?php
namespace Setka\Editor\Tests;

use Korobochkin\BBPressPermalinksWithID\Plugin;

class PluginTest extends \WP_UnitTestCase {


	/**
	 * @var \ReflectionClass Reflection instance for a \Setka\Editor\Plugin class.
	 */
	protected $pluginReflection;

	/**
	 * @var string A path to plugin root dir.
	 */
	protected $pluginRootPath;

	public function setUp()
    {
		parent::setUp();

		$this->pluginReflection = new \ReflectionClass('Korobochkin\BBPressPermalinksWithID\Plugin');
		$this->pluginRootPath = dirname(dirname($this->pluginReflection->getFileName()));
	}

	public function testPluginClassExists() {
		$this->assertTrue(class_exists('Korobochkin\BBPressPermalinksWithID\Plugin'));
	}

	public function testPluginVersionsEquals() {
		// plugin file
		$plugin = $this->pluginRootPath . '/plugin.php';

		// load info from WordPress metadata
		$data = get_plugin_data($plugin, true, false);

		$this->assertEquals(Plugin::VERSION, $data['Version']);
	}

	public function testPluginVersionsEqualsJSON() {
		$packagePath = $this->pluginRootPath . '/package.json';

		$this->assertFileExists($packagePath);
		$this->assertTrue(is_readable($packagePath));

		$package = fopen($packagePath, 'r');

		$json = fread($package, filesize($packagePath));
		$json = json_decode($json);
		$this->assertEquals(JSON_ERROR_NONE, json_last_error());

		$this->assertTrue(property_exists($json, 'version'));
		$this->assertEquals(Plugin::VERSION, $json->version);
	}
}
