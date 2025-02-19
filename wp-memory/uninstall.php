<?php

/**
 * @author William Sergio Minossi
 * @copyright 2016
 */

// If uninstall is not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}

// Array of options to be deleted
$wpmemory_options = array(
    'wpmemory_activated_pointer',
    'wpmemory_php_memory_limit',
    'wpmemory_activated_notice',
    'wpmemory_was_activated',
    'wp_memory_update',
    'wpmemory_dismiss_language',
    'wpmemory_last_notification_date',
    'wpmemory_last_notification_date2'
);

// Delete options
foreach ($wpmemory_options as $option_name) {
    if (is_multisite()) {
        // Delete the option from the site in a multisite installation
        delete_site_option($option_name);
    } else {
        // Delete the option from a single site
        delete_option($option_name);
    }
}

// Drop a custom db table
global $wpdb;
$table = $wpdb->prefix . "wpmemory_log";
$wpdb->query("DROP TABLE IF EXISTS $table");

$table = $wpdb->prefix . 'bill_catch_some_bots';
$wpdb->query("DROP TABLE IF EXISTS $table");
// Clean up scheduled cron jobs
wp_clear_scheduled_hook('wpmemory_keep_latest_records_cron');

$plugin_name = 'bill-catch-errors.php'; // Name of the plugin file to be removed

// Retrieve all must-use plugins
$wp_mu_plugins = get_mu_plugins();


// MU-Plugins directory
$mu_plugins_dir = WPMU_PLUGIN_DIR;

if (isset($wp_mu_plugins[$plugin_name])) {
    // Get the plugin's destination path
    $destination = $mu_plugins_dir . '/' . $plugin_name;

    // Attempt to remove the plugin
    if (!unlink($destination)) {
        // Log the error if the file could not be deleted
        error_log("Error removing the plugin file from the MU-Plugins directory: $destination");
    } else {
        // Optionally, log success if the plugin is removed successfully
        // error_log("Successfully removed the plugin file: $destination");
    }
}
