<?php
/* @var $this NewsletterUnsubscriptionAdmin */
/* @var $controls NewsletterControls */
/* @var $logger NewsletterLogger */


defined('ABSPATH') || exit;

if (!$controls->is_action()) {
    $controls->data = $this->get_options('', $language);
} else {
    foreach ($controls->data as $k => $v) {
        if (strpos($k, '_custom') > 0) {
            if (empty($v)) {
                $controls->data[str_replace('_custom', '', $k)] = '';
            }
            // Remove the _custom field
            unset($controls->data[$k]);
        }
    }

    if ($controls->is_action('save')) {
        $controls->data = wp_kses_post_deep($controls->data);
        $this->save_options($controls->data, '', $language);
        $controls->data = $this->get_options('', $language);
        $controls->add_toast_saved();
    }

    if ($controls->is_action('change')) {
        $controls->data = wp_kses_post_deep($controls->data);
        $this->save_options($controls->data, '', $language);
        $controls->data = $this->get_options('', $language);
        $controls->add_toast_saved();
    }
}

foreach (['unsubscribe_text', 'error_text', 'unsubscribed_text', 'unsubscribed_message', 'reactivated_text'] as $key) {
    if (!empty($controls->data[$key])) {
        $controls->data[$key . '_custom'] = '1';
    }
}

?>

<div class="wrap" id="tnp-wrap">

    <?php include NEWSLETTER_ADMIN_HEADER; ?>

    <div id="tnp-heading">
        <?php $controls->title_help('/cancellation') ?>
<!--        <h2><?php esc_html_e('Subscribers', 'newsletter') ?></h2>-->
        <?php include __DIR__ . '/../users/nav.php' ?>
    </div>

    <div id="tnp-body">

        <?php $controls->show() ?>

        <form method="post" action="">
            <?php $controls->init(); ?>

            <div class="psource-tabs" id="tabs">
                <div class="psource-tabs-nav">
                    <button class="psource-tab active" data-tab="tabs-cancellation"><?php esc_html_e('Confirm', 'newsletter') ?></button>
                    <button class="psource-tab" data-tab="tabs-goodbye"><?php esc_html_e('Goodbye', 'newsletter') ?></button>
                    <button class="psource-tab" data-tab="tabs-reactivation"><?php esc_html_e('Resubscribe', 'newsletter') ?></button>
                    <button class="psource-tab" data-tab="tabs-advanced" style="font-style: italic"><?php esc_html_e('Advanced', 'newsletter') ?></button>
                    <?php if (NEWSLETTER_DEBUG) { ?>
                        <button class="psource-tab" data-tab="tabs-debug">Debug</button>
                    <?php } ?>
                </div>
                <div class="psource-tabs-content">
                    <div class="psource-tab-panel active" id="tabs-cancellation">
                        <?php $this->language_notice(); ?>
                        <table class="form-table">
                            <tr>
                                <th><?php esc_html_e('Opt-out message', 'newsletter') ?></th>
                                <td>
                                    <?php $controls->checkbox2('unsubscribe_text_custom', 'Customize', ['onchange' => 'tnp_refresh_binds()']); ?>
                                    <div data-bind="options-unsubscribe_text_custom">
                                        <?php $controls->wp_editor('unsubscribe_text', ['editor_height' => 250], ['default' => wp_kses_post($this->get_default_text('unsubscribe_text'))]); ?>
                                    </div>
                                    <div data-bind="!options-unsubscribe_text_custom" class="tnpc-default-text">
                                        <?php echo wp_kses_post($this->get_default_text('unsubscribe_text')) ?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="psource-tab-panel" id="tabs-goodbye">
                        <?php $this->language_notice(); ?>
                        <table class="form-table">
                            <tr>
                                <th><?php esc_html_e('Goodbye message', 'newsletter') ?></th>
                                <td>
                                    <?php $controls->checkbox2('unsubscribed_text_custom', 'Customize', ['onchange' => 'tnp_refresh_binds()']); ?>
                                    <div data-bind="options-unsubscribed_text_custom">
                                        <?php $controls->wp_editor('unsubscribed_text', ['editor_height' => 150], ['default' => wp_kses_post($this->get_default_text('unsubscribed_text'))]); ?>
                                    </div>
                                    <div data-bind="!options-unsubscribed_text_custom" class="tnpc-default-text">
                                        <?php echo wp_kses_post($this->get_default_text('unsubscribed_text')) ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e('Goodbye email', 'newsletter') ?></th>
                                <td>
                                    <?php if (!$language) { ?>
                                        <?php $controls->disabled('unsubscribed_disabled') ?>
                                    <?php } ?>
                                    <?php $controls->text('unsubscribed_subject', 70, wp_kses_post($this->get_default_text('unsubscribed_subject'))); ?>
                                    <br><br>
                                    <?php $controls->checkbox2('unsubscribed_message_custom', 'Customize', ['onchange' => 'tnp_refresh_binds()']); ?>
                                    <div data-bind="options-unsubscribed_message_custom">
                                        <?php $controls->wp_editor('unsubscribed_message', ['editor_height' => 150], ['default' => wp_kses_post($this->get_default_text('unsubscribed_message'))]); ?>
                                    </div>
                                    <div data-bind="!options-unsubscribed_message_custom" class="tnpc-default-text">
                                        <?php echo wp_kses_post($this->get_default_text('unsubscribed_message')) ?>
                                    </div>
                                    <p class="description">
                                        Sending a goodbye email is no longer a best practice.
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="psource-tab-panel" id="tabs-reactivation">
                        <?php $this->language_notice(); ?>
                        <table class="form-table">
                            <tr>
                                <th><?php esc_html_e('Reactivated message', 'newsletter') ?></th>
                                <td>
                                    <?php $controls->checkbox2('reactivated_text_custom', 'Customize', ['onchange' => 'tnp_refresh_binds()']); ?>
                                    <div data-bind="options-reactivated_text_custom">
                                        <?php $controls->wp_editor('reactivated_text', ['editor_height' => 150], ['default' => wp_kses_post($this->get_default_text('reactivated_text'))]); ?>
                                    </div>
                                    <div data-bind="!options-reactivated_text_custom" class="tnpc-default-text">
                                        <?php echo wp_kses_post($this->get_default_text('reactivated_text')) ?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="psource-tab-panel" id="tabs-advanced">
                        <?php $this->language_notice(); ?>
                        <?php if (!$language) { ?>
                            <table class="form-table">
                                <tr>
                                    <th><?php esc_html_e('Notifications', 'newsletter') ?></th>
                                    <td>
                                        <?php $controls->yesno('notify'); ?>
                                        <?php $controls->text_email('notify_email'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e('On error', 'newsletter') ?></th>
                                    <td>
                                        <?php $controls->checkbox2('error_text_custom', 'Customize', ['onchange' => 'tnp_refresh_binds()']); ?>
                                        <div data-bind="options-error_text_custom">
                                            <?php $controls->wp_editor('error_text', ['editor_height' => 150], ['default' => wp_kses_post($this->get_default_text('error_text'))]); ?>
                                        </div>
                                        <div data-bind="!options-error_text_custom" class="tnpc-default-text">
                                            <?php echo wp_kses_post($this->get_default_text('error_text')) ?>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            <h3>List-Unsubscribe headers</h3>
                            <table class="form-table">
                                <tr>
                                    <th>
                                        <?php esc_html_e('Disable unsubscribe headers', 'newsletter') ?>
                                        <?php $controls->field_help('/subscribers-and-management/cancellation/#list-unsubscribe') ?>
                                    </th>
                                    <td>
                                        <?php $controls->yesno('disable_unsubscribe_headers'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <?php esc_html_e('Cancellation requests via email', 'newsletter') ?>
                                        <?php $controls->field_help('/subscribers-and-management/cancellation/#list-unsubscribe') ?>
                                    </th>
                                    <td>
                                        <?php $controls->text_email('list_unsubscribe_mailto_header'); ?>
                                        <span class="description">
                                            <i class="fas fa-exclamation-triangle"></i> Please, read carefully the documentation page
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        <?php } ?>
                    </div>
                    <?php if (NEWSLETTER_DEBUG) { ?>
                        <div class="psource-tab-panel" id="tabs-debug">
                            <pre><?php echo esc_html(wp_json_encode($this->get_db_options('', $language), JSON_PRETTY_PRINT)) ?></pre>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <p>
                <?php $controls->button_save() ?>
                <?php if (current_user_can('administrator')) { ?>
                    <?php $controls->btn_link($this->build_dummy_action_url('u'), __('Preview', 'newsletter'), ['tertiary' => true, 'target' => '_blank']); ?>
                <?php } ?>
            </p>
        </form>


    </div>

</div>
