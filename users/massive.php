<?php
/* @var $wpdb wpdb */
/* @var $this NewsletterUsersAdmin */
/* @var $controls NewsletterControls */

defined('ABSPATH') || exit;

if ($controls->is_action('remove_unconfirmed')) {
    $r = $wpdb->query("delete from " . NEWSLETTER_USERS_TABLE . " where status='S'");
    $controls->messages = __('Subscribers not confirmed deleted: ', 'newsletter') . $r . '.';
}

if ($controls->is_action('remove_unsubscribed')) {
    $r = $wpdb->query("delete from " . NEWSLETTER_USERS_TABLE . " where status='U'");
    $controls->messages = __('Subscribers unsubscribed deleted: ', 'newsletter') . $r . '.';
}

if ($controls->is_action('remove_complained')) {
    $r = $wpdb->query("delete from " . NEWSLETTER_USERS_TABLE . " where status='P'");
    $controls->messages = __('Subscribers complained deleted: ', 'newsletter') . $r . '.';
}

if ($controls->is_action('remove_bounced')) {
    $r = $wpdb->query("delete from " . NEWSLETTER_USERS_TABLE . " where status='B'");
    $controls->messages = __('Subscribers bounced deleted: ', 'newsletter') . $r . '.';
}

if ($controls->is_action('unconfirm_all')) {
    $r = $wpdb->query("update " . NEWSLETTER_USERS_TABLE . " set status='S' where status='C'");
    $controls->messages = __('Subscribers changed to not confirmed: ', 'newsletter') . $r . '.';
}

if ($controls->is_action('confirm_all')) {
    $r = $wpdb->query("update " . NEWSLETTER_USERS_TABLE . " set status='C' where status='S'");
    $controls->messages = __('Subscribers changed to confirmed: ', 'newsletter') . $r . '.';
}

if ($controls->is_action('remove_all')) {
    $r = $wpdb->query("delete from " . NEWSLETTER_USERS_TABLE);
    $controls->messages = __('Subscribers deleted: ', 'newsletter') . $r . '.';
}

if ($controls->is_action('list_add')) {
    $r = $wpdb->query("update " . NEWSLETTER_USERS_TABLE . " set list_" . ((int) $controls->data['list']) . "=1");
    $controls->messages = $r . ' ' . __('added to list', 'newsletter') . ' ' . $controls->data['list'];
}

if ($controls->is_action('list_remove')) {
    $r = $wpdb->query("update " . NEWSLETTER_USERS_TABLE . " set list_" . ((int) $controls->data['list']) . "=0");
    $controls->messages = $r . ' ' . __('removed from list', 'newsletter') . ' ' . $controls->data['list'];
}

if ($controls->is_action('list_delete')) {
    $count = $wpdb->query("delete from " . NEWSLETTER_USERS_TABLE . " where list_" . ((int) $controls->data['list']) . "<>0");
    $this->clean_sent_table();
    $this->clean_stats_table();

    $controls->messages = $count . ' ' . __('deleted', 'newsletter');
}

if ($controls->is_action('language')) {
    $count = $wpdb->query($wpdb->prepare("update " . NEWSLETTER_USERS_TABLE . " set language=%s where language=''", $controls->data['language']));
    $controls->add_message_done();
}

if ($controls->is_action('list_manage')) {
    if ($controls->data['list_action'] == 'move') {
        $wpdb->query("update " . NEWSLETTER_USERS_TABLE . ' set list_' . ((int) $controls->data['list_1']) . '=0, list_' . ((int) $controls->data['list_2']) . '=1' .
                ' where list_' . $controls->data['list_1'] . '=1');
    }

    if ($controls->data['list_action'] == 'add') {
        $wpdb->query("update " . NEWSLETTER_USERS_TABLE . ' set list_' . ((int) $controls->data['list_2']) . '=1' .
                ' where list_' . $controls->data['list_1'] . '=1');
    }
}

if ($controls->is_action('list_none')) {
    $where = '1=1';

    for ($i = 1; $i <= NEWSLETTER_LIST_MAX; $i++) {
        $where .= ' and list_' . $i . '=0';
    }

    $count = $wpdb->query("update " . NEWSLETTER_USERS_TABLE . ' set list_' . ((int) $controls->data['list_3']) . '=1' .
            ' where ' . $where);
    $controls->messages = $count . ' subscribers updated';
}

if ($controls->is_action('update_inactive')) {
    //Update users 'last_activity' column
    $wpdb->query("update `{$wpdb->prefix}newsletter` n join (select user_id, max(s.time) as max_time from `{$wpdb->prefix}newsletter_sent` s where s.open>0 group by user_id) as ss
        on n.id=ss.user_id set last_activity=ss.max_time");

    $inactive_time = (int) $controls->data['inactive_time'];

    $where = 'last_activity > 0 and last_activity<' . (time() - $inactive_time * 30 * 24 * 3600);

    $count = $wpdb->query("update " . NEWSLETTER_USERS_TABLE . ' set list_' . ((int) $controls->data['list_inactive']) . '=1 where ' . $where);
    $controls->messages = $count . ' subscribers updated';
}

if ($controls->is_action('change_status')) {
    $status_1 = $controls->data['status_1'];
    $status_2 = $controls->data['status_2'];

    // Status validation
    if (!TNP_User::is_status_valid($status_1) || !TNP_User::is_status_valid($status_2)) {
        echo 'Invalid status value';
        return;
    }

    $count = $wpdb->query($wpdb->prepare("update `" . NEWSLETTER_USERS_TABLE . "` set status=%s where status=%s", $status_2, $status_1));

    $controls->messages = $count . ' subscribers updated';
}
?>

<div class="wrap tnp-users tnp-users-massive" id="tnp-wrap">

    <?php include NEWSLETTER_ADMIN_HEADER; ?>

    <div id="tnp-heading">

<!--        <h2><?php esc_html_e('Subscribers', 'newsletter') ?></h2>-->

        <?php include __DIR__ . '/nav.php' ?>
    </div>

    <div id="tnp-body">

        <?php $controls->show(); ?>

        <div class="tnp-notice"><?php esc_html_e('Please backup before running maintenance actions.', 'newsletter') ?></div>

        <?php if (!empty($results)) { ?>

            <h3>Results</h3>

            <textarea wrap="off" style="width: 100%; height: 150px; font-size: 11px; font-family: monospace"><?php echo esc_html($results) ?></textarea>

        <?php } ?>


        <form method="post" action="">
            <?php $controls->init(); ?>

            <div class="psource-tabs" id="tabs">
                <div class="psource-tabs-nav">
                    <button class="psource-tab active" data-tab="tabs-1"><?php esc_html_e('General', 'newsletter') ?></button>
                    <button class="psource-tab" data-tab="tabs-2"><?php esc_html_e('Lists', 'newsletter') ?></button>
                </div>
                <div class="psource-tabs-content">
                    <div class="psource-tab-panel active" id="tabs-1">
                        <table class="widefat" style="width: auto">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e('Status', 'newsletter') ?></th>
                                    <th><?php esc_html_e('Total', 'newsletter') ?></th>
                                    <th><?php esc_html_e('Actions', 'newsletter') ?></th>
                                </tr>
                            </thead>
                            <tr>
                                <td><?php esc_html_e('Total', 'newsletter') ?></td>
                                <td>
                                    <?php echo (int) $wpdb->get_var("select count(*) from " . NEWSLETTER_USERS_TABLE); ?>
                                </td>
                                <td nowrap>
                                    <?php $controls->button_confirm_secondary('remove_all', __('Delete all', 'newsletter'), __('Are you sure you want to remove ALL subscribers?', 'newsletter')); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php esc_html_e('Confirmed', 'newsletter') ?></td>
                                <td>
                                    <?php echo (int) $wpdb->get_var("select count(*) from " . NEWSLETTER_USERS_TABLE . " where status='C'"); ?>
                                </td>
                                <td nowrap>
                                    <?php $controls->button_confirm_secondary('unconfirm_all', __('Unconfirm all', 'newsletter')); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php esc_html_e('Not comfirmed', 'newsletter') ?></td>
                                <td>
                                    <?php echo (int) $wpdb->get_var("select count(*) from " . NEWSLETTER_USERS_TABLE . " where status='S'"); ?>
                                </td>
                                <td nowrap>
                                    <?php $controls->button_confirm_secondary('remove_unconfirmed', __('Delete all', 'newsletter')); ?>
                                    <?php $controls->button_confirm_secondary('confirm_all', __('Confirm all', 'newsletter'), __('Are you sure you want to mark ALL subscribers as confirmed?', 'newsletter')); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php esc_html_e('Unsubscribed', 'newsletter') ?></td>
                                <td>
                                    <?php echo (int) $wpdb->get_var("select count(*) from " . NEWSLETTER_USERS_TABLE . " where status='U'"); ?>
                                </td>
                                <td>
                                    <?php $controls->button_confirm_secondary('remove_unsubscribed', __('Delete all', 'newsletter')); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php esc_html_e('Bounced', 'newsletter') ?></td>
                                <td>
                                    <?php echo (int) $wpdb->get_var("select count(*) from " . NEWSLETTER_USERS_TABLE . " where status='B'"); ?>
                                </td>
                                <td>
                                    <?php $controls->button_confirm_secondary('remove_bounced', __('Delete all', 'newsletter')); ?>
                                </td>
                            </tr>
                            <tr>
                                <td><?php esc_html_e('Complained', 'newsletter') ?></td>
                                <td>
                                    <?php echo (int) $wpdb->get_var("select count(*) from " . NEWSLETTER_USERS_TABLE . " where status='P'"); ?>
                                </td>
                                <td>
                                    <?php $controls->button_confirm_secondary('remove_complained', __('Delete all', 'newsletter')); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php esc_html_e('Change status', 'newsletter') ?>
                                </td>
                                <td>
                                    <?php $controls->user_status('status_1'); ?>
                                    <?php esc_html_e('to', 'newsletter') ?>
                                    <?php $controls->user_status('status_2'); ?>
                                </td>
                                <td>
                                    <?php $controls->button_confirm_secondary('change_status', __('Change', 'newsletter')); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php esc_html_e('Inactive since', 'newsletter') ?>
                                    <?php $controls->field_help('https://www.thenewsletterplugin.com/documentation/subscribers-and-management/subscribers/#inactive') ?>
                                </td>
                                <td>
                                    <?php
                                    $controls->select('inactive_time', array(
                                        '3' => '3 ' . __('months', 'newsletter'),
                                        '6' => '6 ' . __('months', 'newsletter'),
                                        '12' => '1 ' . __('year', 'newsletter'),
                                        '24' => '2 ' . __('years', 'newsletter'),
                                        '36' => '3 ' . __('years', 'newsletter'),
                                        '48' => '4 ' . __('years', 'newsletter'),
                                        '60' => '5 ' . __('years', 'newsletter'),
                                        '72' => '6 ' . __('years', 'newsletter'),
                                        '84' => '7 ' . __('years', 'newsletter'),
                                        '96' => '8 ' . __('years', 'newsletter'),
                                        '108' => '9 ' . __('years', 'newsletter'),
                                        '120' => '10 ' . __('years', 'newsletter')
                                    ))
                                    ?>
                                    add to
                                    <?php $controls->lists_select('list_inactive'); ?>
                                </td>
                                <td>
                                    <?php $controls->button_confirm_secondary('update_inactive', __('Update', 'newsletter')); ?>
                                </td>
                            </tr>
                            <?php if ($this->is_multilanguage()) { ?>
                                <tr>
                                    <td>Language</td>
                                    <td>
                                        <?php esc_html_e('Set to', 'newsletter') ?>
                                        <?php $controls->language('language', false) ?> <?php esc_html_e('subscribers without a language', 'newsletter') ?>
                                    </td>
                                    <td>
                                        <?php $controls->btn('language', '&raquo;', ['confirm' => true]); ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="psource-tab-panel" id="tabs-2">
                        <table class="form-table">
                            <tr>
                                <td>
                                    <?php $controls->lists_select('list') ?>
                                    <?php $controls->button_confirm_secondary('list_add', 'Activate for everyone'); ?>
                                    <?php $controls->button_confirm_secondary('list_remove', 'Deactivate for everyone'); ?>
                                    <?php $controls->button_confirm_secondary('list_delete', 'Delete everyone in that list'); ?>
                                    <p class="description">
                                        <?php esc_html_e('If you choose to', 'newsletter'); ?>
                                        <strong><?php esc_html_e('delete', 'newsletter'); ?></strong>
                                        <?php esc_html_e('users in a list, they will be', 'newsletter'); ?>
                                        <strong><?php esc_html_e('physically deleted', 'newsletter'); ?></strong>
                                        <?php esc_html_e('from the database (no way back).', 'newsletter'); ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php $controls->select('list_action', array('move' => 'Change', 'add' => 'Add')); ?>
                                    <?php esc_html_e('all subscribers in', 'newsletter') ?> <?php $controls->lists_select('list_1'); ?>
                                    <?php esc_html_e('to', 'newsletter') ?> <?php $controls->lists_select('list_2'); ?>
                                    <?php $controls->button_confirm_secondary('list_manage', '&raquo;'); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <?php esc_html_e('Add to list', 'newsletter') ?>
                                    <?php $controls->lists_select('list_3') ?> <?php esc_html_e('subscribers without a list', 'newsletter') ?>
                                    <?php $controls->button_confirm_secondary('list_none', '&raquo;'); ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </form>
    </div>

</div>
