<?php
namespace Korobochkin\BBPressPermalinksWithID\BBPress;

/**
 * Class BBPress
 */
class BBPress
{

    /**
     * Run the class.
     */
    public static function run()
    {
        $structure = get_option('permalink_structure');
        if ($structure) {
            // Run (add rewrite rules) only if WordPress permalink settings not default (default looks like site.com/?p=123)
            add_action('bbp_add_rewrite_rules', 'bbp_permalinks_rewrites_init', 3);
            // Create valid URL for our new rewrite rules
            add_filter('post_type_link', array('Korobochkin\BBPressPermalinksWithID\BBPress\Utils', 'linkPretty'), 99, 2);
        } else {
            // If permalink settings is default only change permalinks
            add_filter('post_type_link', array('Korobochkin\BBPressPermalinksWithID\BBPress\Utils', 'linkNotPretty'), 99, 2);
        }
    }
}
