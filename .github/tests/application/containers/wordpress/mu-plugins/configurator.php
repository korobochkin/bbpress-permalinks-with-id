<?php

function configurator_disable_auto_updates()
{
    remove_action('admin_notices', 'update_nag', 3);
    remove_action('network_admin_notices', 'update_nag', 3);
    remove_action('admin_notices', 'maintenance_nag');
    remove_action('network_admin_notices', 'maintenance_nag');

    /*
     * Disable Theme Updates
     * 2.8 to 3.0
     */
    remove_action('load-themes.php', 'wp_update_themes');
    remove_action('load-update.php', 'wp_update_themes');
    remove_action('admin_init', '_maybe_update_themes');
    remove_action('wp_update_themes', 'wp_update_themes');
    wp_clear_scheduled_hook('wp_update_themes');

    // 3.0
    remove_action('load-update-core.php', 'wp_update_themes');
    wp_clear_scheduled_hook('wp_update_themes');

    /*
     * Disable Plugin Updates
     * 2.8 to 3.0
     */
    remove_action('load-plugins.php', 'wp_update_plugins');
    remove_action('load-update.php', 'wp_update_plugins');
    remove_action('admin_init', '_maybe_update_plugins');
    remove_action('wp_update_plugins', 'wp_update_plugins');
    wp_clear_scheduled_hook('wp_update_plugins');

    // 3.0
    remove_action('load-update-core.php', 'wp_update_plugins');
    wp_clear_scheduled_hook('wp_update_plugins');

    /*
     * Disable Core Updates
     * 2.8 to 3.0
     */
    add_filter('pre_option_update_core', '__return_null');

    remove_action('wp_version_check', 'wp_version_check');
    remove_action('admin_init', '_maybe_update_core');
    wp_clear_scheduled_hook('wp_version_check');

    // 3.0
    wp_clear_scheduled_hook('wp_version_check');

    // 3.7+
    remove_action('wp_maybe_auto_update', 'wp_maybe_auto_update');
    remove_action('admin_init', 'wp_maybe_auto_update');
    remove_action('admin_init', 'wp_auto_update_core');
    wp_clear_scheduled_hook('wp_maybe_auto_update');

    remove_all_filters('plugins_api');
}
add_action('admin_init', 'configurator_disable_auto_updates');
