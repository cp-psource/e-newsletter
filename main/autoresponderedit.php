<?php

defined('ABSPATH') || exit;

require_once NEWSLETTER_INCLUDES_DIR . '/controls.php';
$controls = new NewsletterControls();

global $wpdb;

// ID aus URL holen
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Beim Speichern
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    error_log('ID: ' . $id);
    error_log('POST: ' . print_r($_POST, true));
    $wpdb->update(
        $wpdb->prefix . 'tnp_autoresponders',
        [
            'name'         => sanitize_text_field($_POST['options']['name'] ?? ''),
            'status'       => isset($_POST['options']['status']) ? 1 : 0,
            'list_id'      => isset($_POST['options']['list']) ? intval($_POST['options']['list']) : 0,
            'keep_active'  => isset($_POST['options']['keep_active']) ? 1 : 0,
            'language'     => sanitize_text_field($_POST['options']['language'] ?? ''),
            'restart'      => isset($_POST['options']['restart']) ? 1 : 0,
            'regenerate'   => isset($_POST['options']['regenerate']) ? 1 : 0,
            'utm_campaign' => sanitize_text_field($_POST['options']['utm_campaign'] ?? ''),
            'utm_source'   => sanitize_text_field($_POST['options']['utm_source'] ?? ''),
            'utm_medium'   => sanitize_text_field($_POST['options']['utm_medium'] ?? ''),
            'utm_term'     => sanitize_text_field($_POST['options']['utm_term'] ?? ''),
            'utm_content'  => sanitize_text_field($_POST['options']['utm_content'] ?? ''),
        ],
        ['id' => $id]
    );

    error_log('Rows affected: ' . $wpdb->rows_affected);
    error_log('DB-Fehler: ' . $wpdb->last_error);
    if ($wpdb->last_error) {
        echo '<div class="notice notice-error"><p>DB-Fehler: ' . esc_html($wpdb->last_error) . '</p></div>';
    } else {
        echo '<div class="notice notice-success"><p>Gespeichert!</p></div>';
    }
}

// Daten laden
$autoresponder = $wpdb->get_row($wpdb->prepare(
    "SELECT * FROM {$wpdb->prefix}tnp_autoresponders WHERE id = %d", $id
));

if (!$autoresponder) {
    echo '<div class="notice notice-error">Serie nicht gefunden.</div>';
    return;
}

$controls->set_data($autoresponder);

?>

<div class="wrap" id="tnp-wrap">

    <?php include NEWSLETTER_ADMIN_HEADER; ?>
    <div id="tnp-heading">
        <?php include __DIR__ . '/autorespondernav.php' ?>
    </div>

    <div id="tnp-body">

        <?php $controls->show(); ?>

        <p>This is only a demonstrative panel.</p>

        <form method="post" action="">

        <?php $controls->init(); ?>

        <div class="psource-tabs" id="tabs">
            <div class="psource-tabs-nav">
                <button class="psource-tab active" data-tab="tabs-general"><?php esc_html_e('General', 'newsletter') ?></button>
                <button class="psource-tab" data-tab="tabs-advanced"><?php esc_html_e('Advanced', 'newsletter') ?></button>
                <button class="psource-tab" data-tab="tabs-analytics"><?php esc_html_e('Google Analytics', 'newsletter') ?></button>
            </div>
            <div class="psource-tabs-content">
                <div class="psource-tab-panel active" id="tabs-general">
                    <table class="form-table">
                        <tr>
                            <th>Enabled</th>
                            <td><?php $controls->yesno('status') ?></td>
                        </tr>
                        <tr>
                            <th>Title</th>
                            <td><?php $controls->text('name', 70) ?></td>
                        </tr>
                    </table>
                    <table class="form-table">
                        <tr>
                            <th>Activation rules</th>
                            <td>
                                <?php $controls->enabled('rules', ['bind_to'=>'divrules']) ?>
                                <p class="description">
                                    When disabled the series can anyway be activated by addons, custom subscription forms, and so on.
                                </p>
                            </td>
                        </tr>
                    </table>
                    <table class="form-table" id="options-divrules">
                        <tr>
                            <th>List</th>
                            <td>
                                <?php $controls->lists_select_with_notes('list', 'All subscribers') ?>
                                <p class="description">
                                    <strong>List set</strong> - the series is activated to subscribers in the specified list. Subscribers are automatically
                                    captured when they enter the list and automatically released when they exit the list (usually within 5 minutes).
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th>Keep active</th>
                            <td>
                                <?php $controls->yesno('keep_active') ?>
                                <p class="description">
                                    Keep the series activeif the subscriber is removed from the list, otherwise stop it.
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th>Language</th>
                            <td>
                                <?php $controls->language() ?>
                                <p class="description">
                                    Only subscribers with a matching language will be linked.
                                </p>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="psource-tab-panel" id="tabs-advanced">
                    <table class="form-table">
                        <tr>
                            <th>Restart on re-subscription</th>
                            <td>
                                <?php $controls->yesno('restart') ?>
                                <p class="description">
                                    If a subscriber re-subscribes and the series is already completed, restart it.
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th>Update emails content</th>
                            <td>
                                <?php $controls->yesno('regenerate') ?>
                                <p class="description">
                                    If the content of the emails should be updated every day (it applies to post list, product list and so on)
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th>Lists to add on completion</th>
                            <td>
                                <?php $controls->lists('new_lists') ?>
                                <p class="description">
                                    List to be set on a subscriber's profile when the series reaches its end.
                                </p>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="psource-tab-panel" id="tabs-analytics">
                    <p>
                        Google Analytics addon required.<br>
                        On UTM parameters <code>{email_id}</code> and <code>{email_subsject}</code> can be used to make them dynamic.<br>
                    </p>
                    <table class="form-table">
                        <tr>
                            <th>UTM Campaign</th>
                            <td>
                                <?php $controls->text('utm_campaign', 50); ?>
                                <p class="description"></p>
                            </td>
                        </tr>
                        <tr>
                            <th>UTM Source (mandatory)</th>
                            <td>
                                <?php $controls->text('utm_source', 50); ?>
                                <p class="description">
                                    Use the <code>{step}</code> tag to have the step number inserted (1, 2, 3, ...). The suggested value
                                    is <code>step-{step}</code>.
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th>UTM Medium</th>
                            <td>
                                <?php $controls->text('utm_medium', 50); ?>
                                <p class="description">
                                    Should be set to "email" since this is the only medium used.
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th>UTM Term</th>
                            <td>
                                <?php $controls->text('utm_term', 50); ?>
                                <p class="description">
                                    Usually empty can be used on specific newsletters but it is more related to keyword based advertising.
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th>UTM Content</th>
                            <td>
                                <?php $controls->text('utm_content', 50); ?>
                                <p class="description">
                                    Usually empty can be used on specific newsletters.
                                </p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="tnp-buttons">
                <?php $controls->button_save('Save'); ?>
            </div>
        </div>
    </form>

    </div>

</div>
