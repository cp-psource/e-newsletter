<?php

/*
  Plugin Name: Newsletter - Locked Content Addon
  Plugin URI: https://www.thenewsletterplugin.com/documentation/addons/extended-features/locked-content-extension/
  Description: Hide partially or totally posts content and requires a subscription to unlock them
  Version: 1.2.1
  Requires PHP: 7.0
  Requires at least: 5.6
  Author: The Newsletter Team
  Author URI: https://www.thenewsletterplugin.com
  Disclaimer: Use at your own risk. No warranty expressed or implied is provided.
 */

add_action('newsletter_loaded', function ($version) {
    if (version_compare($version, '8.7.0') < 0) {
        add_action('admin_notices', function () {
            echo '<div class="notice notice-error"><p>Newsletter plugin upgrade required <strong>Newsletter - Locked Content Addon</strong>.</p></div>';
        });
    } else {
        require_once __DIR__ . '/plugin.php';
        new NewsletterLock('1.2.1');
    }
});

