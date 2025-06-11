<?php

namespace TNP\Forms;

/*
  Plugin Name: Newsletter - Forms
  Plugin URI: https://www.thenewsletterplugin.com/documentation/addons/extended-features/bounce-extension/
  Description: Design your own subscription forms
  Version: 1.1.3
  Requires PHP: 7.0
  Requires at least: 5.6
  Author: The Newsletter Team
  Author URI: https://www.thenewsletterplugin.com
  Disclaimer: Use at your own risk. No warranty expressed or implied is provided.
 */

add_action('newsletter_loaded', function ($version) {
    if (version_compare($version, '8.3.6', '<')) {
        add_action('admin_notices', function () {
            echo '<div class="notice notice-error"><p>Newsletter plugin upgrade required by <strong>Newsletter - Forms Addon</strong>.</p></div>';
        });
    } else {
        require_once __DIR__ . '/plugin.php';
        new Addon('1.1.3');
    }
});
