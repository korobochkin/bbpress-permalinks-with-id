<?php
use Korobochkin\BBPressPermalinksWithID\Plugin;
/*
Plugin Name: bbPress Permalinks with ID
Plugin URI: https://wordpress.org/plugins/bbpress-permalinks-with-id/
Description: ID instead of slug in bbPress topic and forum links.
Author: Kolya Korobochkin
Author URI: https://korobochkin.com/
Version: 2.0.0
Text Domain: bbpress-permalinks-with-id
Domain Path: /languages/
Requires at least: 4.1.1
Tested up to: 4.7.0
License: GPLv2 or later
*/

if( !class_exists( 'Korobochkin\BBPressPermalinksWithID\Plugin' ) ) {
	require_once 'vendor/autoload.php';
}
$GLOBALS['BBPressPermalinksWithIDPlugin'] = new Plugin( __FILE__ );
$GLOBALS['BBPressPermalinksWithIDPlugin']->run();
