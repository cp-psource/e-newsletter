<?php

include '../../../wp-load.php';

$module = NewsletterApi::$instance;
$newsletter = Newsletter::instance();

$posted = wp_unslash($_REQUEST);

$key = $posted['nk'];
if (empty($module->options['key']) || $key !== $module->options['key']) {
    die('Wrong API key');
}

$email = $newsletter->sanitize_email($posted['ne']);
$r = 0;
if ($email) {
    $r = $wpdb->query($wpdb->prepare("delete from " . NEWSLETTER_USERS_TABLE . " where email=%s", $email));
}
die($r = 0 ? 'ko' : 'ok');
