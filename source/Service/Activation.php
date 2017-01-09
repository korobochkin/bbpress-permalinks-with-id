<?php
namespace Korobochkin\BBPressPermalinksWithID\Service;

/**
 * Class Activation
 */
class Activation
{

    /**
     * Activation callback. Check if bbPress activated. Check permalink structure settings in WordPress.
     * If both of conditions comes to true then add new rewrite rules and flush it.
     *
     * @since 1.0.0
     */
    public static function run()
    {
        /*
         * We need add new rewrite rules first and only after this call flush_rewrite_rules
         * In other ways flush_rewrite_rules doesn't work.
         */
        if (function_exists('bbpress')) {
            /*
             * Check if bbPress plugin activated
             * bbp_permalinks_rewrites_init use bbPress links and if bbPress not activated we call undefined functions
             * and got a fatal error.
             */
            $structure = get_option('permalink_structure');
            if ($structure) {
                // Run (add rewrite rules) only if WordPress permalink settings not default (site.com/?p=123)
                // bbp_permalinks_rewrites_init();.
                flush_rewrite_rules(false);
            }
        }
    }
    // This stuff not working (Currently in progress)
    // register_activation_hook( __FILE__, 'bbp_permalinks_activate' );.
}
