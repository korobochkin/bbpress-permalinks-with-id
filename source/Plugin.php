<?php
namespace Korobochkin\BBPressPermalinksWithID;

/**
 * Class Plugin
 */
class Plugin
{
    const NAME = 'bbpress-permalinks-with-id';

    const _NAME_ = 'bbpress_permalinks_with_id';

    const VERSION = '2.0.0-alpha';

    private $pluginFile;

    /**
     * Plugin constructor.
     *
     * @param string $pluginFile The path of file which run the plugin.
     */
    public function __construct($pluginFile)
    {
        $this->setPluginFile($pluginFile);
    }

    /**
     * Run our plugin.
     */
    public function run()
    {
        add_action(
            'bbp_init',
            array('Korobochkin\BBPressPermalinksWithID\BBPress\BBPress', 'run')
        );
    }

    /**
     * @return string
     */
    public function getPluginFile()
    {
        return $this->pluginFile;
    }

    /**
     * @param string $pluginFile
     */
    public function setPluginFile($pluginFile)
    {
        $this->pluginFile = $pluginFile;
    }
}
