<?php
namespace Korobochkin\BBPressPermalinksWithID\Service;

/**
 * Class Deactivation
 */
class Deactivation
{

    /**
     * Deactivation callback. Flush rewrite rules.
     *
     * @since 1.0.0
     */
    public static function run()
    {
        flush_rewrite_rules(false);
    }
}
