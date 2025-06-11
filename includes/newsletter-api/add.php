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
if (!$email) {
    die('Wrong email');
}

$subscriber = [];
$subscriber['email'] = $email;
$subscriber['name'] = $newsletter->sanitize_name($posted['nn'] ?? '');
$subscriber['surname'] = $newsletter->sanitize_name($posted['ns'] ?? '');


if (is_array($posted['nl'])) {
  foreach ($posted['nl'] as $add_list) {
    $subscriber['list_' . ((int)$add_list)] = 1;
  }
}
else if (!empty($posted['nl'])) {
  $add_lists = explode('|', $posted['nl']);
  foreach ($add_lists as $add_list) {
    $subscriber['list_' . ((int)$add_list)] = 1;
  }
}

$subscriber['status'] = 'C';

// TODO: add control for already subscribed emails
NewsletterUsers::instance()->save_user($subscriber);
die('ok');
