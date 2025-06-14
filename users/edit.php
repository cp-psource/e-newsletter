<?php
// phpcs:disable WordPress.WP.EnqueuedResources.NonEnqueuedScript

/* @var $this NewsletterUsersAdmin */
/* @var $controls NewsletterControls */

defined('ABSPATH') || exit;

$user = $this->get_user((int) $_GET['id'] ?? -1);

if (!$user) {
    echo 'Subscriber not found.';
    return;
}

if ($controls->is_action('save')) {

    $controls->data = wp_kses_post_deep($controls->data);

    $email = $this->sanitize_email($controls->data['email']);
    if (empty($email)) {
        $controls->errors = esc_html__('Wrong email address', 'newsletter');
    } else {
        $controls->data['email'] = $email;
    }


    if (empty($controls->errors)) {
        $u = $this->get_user($controls->data['email']);
        if ($u && $u->id != $user->id) {
            $controls->errors = esc_html__('The email address is already in use', 'newsletter');
        }
    }

    if (empty($controls->errors)) {
        // For unselected preferences, force the zero value
        for ($i = 1; $i <= NEWSLETTER_LIST_MAX; $i++) {
            if (!isset($controls->data['list_' . $i])) {
                $controls->data['list_' . $i] = 0;
            }
        }

        if (empty($controls->data['token'])) {
            $controls->data['token'] = $this->get_token();
        }

        $controls->data['id'] = $user->id;

        // Sanitize
        $controls->data['name'] = $this->sanitize_name($controls->data['name']);
        $controls->data['surname'] = $this->sanitize_name($controls->data['surname']);
        $controls->data['wp_user_id'] = (int) $controls->data['wp_user_id'];

        for ($i = 1; $i <= NEWSLETTER_PROFILE_MAX; $i++) {
            if (isset($controls->data['profile_' . $i])) {
                $controls->data['profile_' . $i] = $this->sanitize_user_field($controls->data['profile_' . $i]);
            }
        }

        $user = $this->save_user($controls->data);
        $this->add_user_log($user, 'edit');
        //$this->save_user_meta($id, 'ip', $controls->data['ip']);
        if ($user === false) {
            $controls->errors = esc_html__('Error. Check the log files.', 'newsletter');
        } else {
            $controls->add_toast_saved();
            $controls->data = (array) $user;
        }
    }
}

if ($controls->is_action('delete')) {
    $this->delete_user($user->id);
    $controls->js_redirect($this->get_admin_page_url('index'));
    return;
}

if (!$controls->is_action()) {
    $controls->data = (array) $user;
}

$options_profile = NewsletterSubscription::instance()->get_options('customfields');

function percent($value, $total) {
    if ($total == 0) {
        return '-';
    }
    return sprintf("%.2f", $value / $total * 100) . '%';
}

function percentValue($value, $total) {
    if ($total == 0) {
        return 0;
    }
    return round($value / $total * 100);
}
?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages': ['corechart', 'geomap']});
</script>

<div class="wrap tnp-users tnp-users-edit" id="tnp-wrap">

    <?php include NEWSLETTER_ADMIN_HEADER ?>

    <div id="tnp-heading">
        <?php $controls->title_help('/subscribers-and-management/') ?>
        <h2><?php echo esc_html($user->email) ?></h2>
        <?php include __DIR__ . '/edit-nav.php' ?>
    </div>

    <div id="tnp-body">

        <?php $controls->show(); ?>

        <form method="post" action="">
            <?php $controls->init(); ?>

            <div class="psource-tabs" id="tabs">
                <div class="psource-tabs-nav">
                    <button class="psource-tab active" data-tab="tabs-general"><?php esc_html_e('Main', 'newsletter') ?></button>
                    <button class="psource-tab" data-tab="tabs-preferences"><?php esc_html_e('Lists', 'newsletter') ?></button>
                    <button class="psource-tab" data-tab="tabs-profile"><?php esc_html_e('Custom fields', 'newsletter') ?></button>
                    <button class="psource-tab" data-tab="tabs-other"><?php esc_html_e('Advanced', 'newsletter') ?></button>
                    <?php if (NEWSLETTER_DEBUG) { ?>
                        <button class="psource-tab" data-tab="tabs-meta">Meta</button>
                    <?php } ?>
                </div>
                <div class="psource-tabs-content">
                    <div class="psource-tab-panel active tnp-tab" id="tabs-general">
                        <?php do_action('newsletter_users_edit_general', $user->id, $controls) ?>
                        <table class="form-table">
                            <tr>
                                <th><?php esc_html_e('Email', 'newsletter'); ?></th>
                                <td>
                                    <?php $controls->text_email('email', 60); ?>
                                </td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e('First name', 'newsletter'); ?></th>
                                <td>
                                    <?php $controls->text('name', 50); ?>
                                </td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e('Last name', 'newsletter'); ?></th>
                                <td>
                                    <?php $controls->text('surname', 50); ?>
                                </td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e('Gender', 'newsletter'); ?></th>
                                <td>
                                    <?php $controls->gender('sex'); ?>
                                </td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e('Status', 'newsletter'); ?></th>
                                <td>
                                    <?php $controls->user_status() ?>
                                    <span class="description"><a href="?page=newsletter_users_logs&id=<?php echo esc_attr($user->id); ?>">Status change history</a></span>
                                </td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e('Language', 'newsletter'); ?></th>
                                <td>
                                    <?php $controls->language('language', __('None', 'newsletter')); ?>
                                </td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e('Test subscriber', 'newsletter'); ?>
                                    <br><?php $controls->help('https://www.thenewsletterplugin.com/documentation/subscribers#test-subscribers') ?></th>
                                <td>
                                    <?php $controls->yesno('test'); ?>
                                </td>
                            </tr>
                            <?php do_action('newsletter_user_edit_extra', $controls); ?>
                        </table>
                    </div>
                    <div class="psource-tab-panel tnp-tab" id="tabs-preferences">
                        <p>
                            <a href="?page=newsletter_subscription_lists" target="_blank"><?php esc_html_e('Configure', 'newsletter') ?></a>
                        </p>
                        <table class="form-table">
                            <tr>
                                <th>
                                    <?php esc_html_e('Lists', 'newsletter') ?>
                                    <br>
                                    <?php $controls->help('https://www.thenewsletterplugin.com/plugins/newsletter/newsletter-preferences') ?></th>
                                <td>
                                    <?php $controls->preferences('list'); ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="psource-tab-panel tnp-tab" id="tabs-profile">
                        <p>
                            <a href="?page=newsletter_subscription_customfields" target="_blank"><?php esc_html_e('Configure', 'newsletter') ?></a>
                        </p>
                        <table class="widefat">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php esc_html_e('Name', 'newsletter'); ?></th>
                                    <th><?php esc_html_e('Value', 'newsletter'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($this->get_all_customfields() as $customfield) {
                                    echo '<tr><td>';
                                    echo (int) $customfield->id;
                                    echo '</td><td>';
                                    echo esc_html($customfield->name);
                                    echo '</td><td>';
                                    if ($customfield->is_text()) {
                                        $controls->text('profile_' . $customfield->id, 70);
                                    } else {
                                        $controls->select('profile_' . $customfield->id, $customfield->options);
                                    }
                                    echo '</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="psource-tab-panel tnp-tab" id="tabs-other">
                        <table class="form-table">
                            <tr>
                                <th>ID</th>
                                <td>
                                    <?php $controls->value('id'); ?>
                                </td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e('Created', 'newsletter') ?></th>
                                <td>
                                    <?php $controls->echo_date(strtotime($controls->data['created'])); ?>
                                </td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e('Referrer', 'newsletter') ?></th>
                                <td>
                                    <?php $controls->value('referrer'); ?>
                                </td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e('HTTP Referrer', 'newsletter') ?></th>
                                <td>
                                    <?php $controls->value('http_referer'); ?>
                                </td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e('Last activity', 'newsletter') ?></th>
                                <td>
                                    <?php $controls->echo_date($controls->data['last_activity']); ?>
                                </td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e('WP user ID', 'newsletter') ?></th>
                                <td>
                                    <?php $controls->text('wp_user_id'); ?>
                                    <span class="description">ID of the WP user connected with this subscriber. Install
                                        <a href="<?php echo esc_attr(\Newsletter\Integrations::get_addon_url('wpusers')); ?>">WP Users Addon</a> for automated integration.</span>
                                </td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e('IP address', 'newsletter'); ?></th>
                                <td>
                                    <?php $controls->value('ip'); ?>
                                </td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e('Secret token', 'newsletter'); ?></th>
                                <td>
                                    <?php $controls->text('token', 50); ?>
                                </td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e('Profile URL', 'newsletter'); ?></th>
                                <td>
                                    <?php $profile_url = NewsletterProfile::instance()->get_profile_url($user) ?>
                                    <a href='<?php echo esc_attr($profile_url) ?>' target="_blank"><?php echo esc_html($profile_url) ?></a>
                                    <?php if (NEWSLETTER_DEBUG) { ?>
                                        <br>
                                        <?php $profile_page_url = NewsletterProfile::instance()->get_profile_page_url($user) ?>
                                        <a href='<?php echo esc_attr($profile_page_url) ?>' target="_blank"><?php echo esc_html($profile_page_url) ?></a>
                                    <?php } ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <?php if (NEWSLETTER_DEBUG) { ?>
                        <div class="psource-tab-panel tnp-tab" id="tabs-meta">
                            <p>
                                Dump of the meta data only for debug.
                            </p>
                            <table class="widefat" style="width: auto">
                                <thead>
                                    <tr>
                                        <th><?php esc_html_e('Name', 'newsletter'); ?></th>
                                        <th><?php esc_html_e('Value', 'newsletter'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Welcome email ID</td>
                                        <td>
                                            <?php echo esc_html($this->get_user_meta($user->id, 'welcome_email_id')); ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <p>
                <?php $controls->button_save(); ?>
                <?php $controls->button_delete(); ?>
            </p>

        </form>
    </div>

    <?php include NEWSLETTER_ADMIN_FOOTER ?>

</div>
