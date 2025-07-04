<?php
/* @var $this NewsletterSubscriptionAdmin */
/* @var $logger NewsletterLogger */
/* @var $controls NewsletterControls */

defined('ABSPATH') || exit;

if (!$controls->is_action()) {
    $controls->data = $this->get_options('customfields', $language);
} else {
    if ($controls->is_action('save')) {
        if ($language) {
            foreach ($controls->data as $k => $v) {
                if ($v === '') {
                    //unset($controls->data[$k]);
                }
            }
        }

        // Processing profile fields
        if ($language) {
            for ($i = 0; $i <= NEWSLETTER_PROFILE_MAX; $i++) {
                if (empty($controls->data['profile_' . $i])) {
                    unset($controls->data['profile_' . $i]);
                }
                if (empty($controls->data['profile_' . $i . '_options'])) {
                    unset($controls->data['profile_' . $i . '_options']);
                }
                if (empty($controls->data['profile_' . $i . '_placeholder'])) {
                    unset($controls->data['profile_' . $i . '_placeholder']);
                }
            }
        }
        $controls->data = wp_kses_post_deep($controls->data);
        $this->save_options($controls->data, 'customfields', $language);
        $controls->data = $this->get_options('customfields', $language);
        $controls->add_toast_saved();
    }
}

$status = array(0 => __('Private', 'newsletter'), 1 => __('Public', 'newsletter'));
$rules = array(0 => __('Optional', 'newsletter'), 1 => __('Required', 'newsletter'));
$extra_type = array('text' => __('Text', 'newsletter'), 'select' => __('List', 'newsletter'));

$main_options = $this->get_main_options('customfields');
?>

<div class="wrap" id="tnp-wrap">

    <?php include NEWSLETTER_ADMIN_HEADER; ?>

    <div id="tnp-heading">
        <h2><?php esc_html_e('Custom fields', 'newsletter') ?></h2>
        <?php //include __DIR__ . '/nav.php' ?>
    </div>

    <div id="tnp-body">

        <?php $controls->show(); ?>

        <form action="" method="post">
            <?php $controls->init(); ?>

            <p>
                Change the <a href="?page=newsletter_subscription_form">Subscription Form</a> and the
                <a href="?page=newsletter_profile_index">Profile Page</a> selecting the fields to show.
            </p>

            <div class="psource-tabs" id="tabs">
                <div class="psource-tabs-nav">
                    <button class="psource-tab active" data-tab="tabs-fields"><?php esc_html_e('Fields', 'newsletter') ?></button>
                    <?php if (NEWSLETTER_DEBUG) { ?>
                        <button class="psource-tab" data-tab="tabs-debug">Debug</button>
                    <?php } ?>
                </div>
                <div class="psource-tabs-content">
                    <div class="psource-tab-panel active" id="tabs-fields">

                        <?php $this->language_notice(); ?>

                        <table class="widefat">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e('Field', 'newsletter') ?></th>
                                    <th><?php esc_html_e('Name/Label', 'newsletter') ?></th>
                                    <th><?php esc_html_e('Placeholder', 'newsletter') ?></th>
                                    <?php if (!$language) { ?>
                                        <th><?php esc_html_e('Status', 'newsletter') ?></th>
                                        <th><?php esc_html_e('Type', 'newsletter') ?></th>
                                        <th><?php esc_html_e('Rule', 'newsletter') ?></th>
                                    <?php } ?>
                                    <th><?php esc_html_e('List values comma separated', 'newsletter') ?></th>
                                </tr>
                            </thead>
                            <?php for ($i = 1; $i <= NEWSLETTER_PROFILE_MAX; $i++) { ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td>
                                        <?php $placeholder = !$language ? '' : $main_options['profile_' . $i] ?>
                                        <?php $controls->text('profile_' . $i, ['placeholder' => $placeholder]); ?>
                                    </td>
                                    <td>
                                        <?php $placeholder = !$language ? '' : $main_options['profile_' . $i . '_placeholder'] ?>
                                        <?php $controls->text('profile_' . $i . '_placeholder', ['placeholder' => $placeholder]); ?>
                                    </td>
                                    <?php if (!$language) { ?>
                                        <td><?php $controls->select('profile_' . $i . '_status', $status); ?></td>
                                        <td><?php $controls->select('profile_' . $i . '_type', $extra_type); ?></td>
                                        <td><?php $controls->select('profile_' . $i . '_rules', $rules); ?></td>
                                    <?php } ?>
                                    <td>
                                        <?php $placeholder = !$language ? '' : $main_options['profile_' . $i . '_options'] ?>
                                        <?php $controls->textarea('profile_' . $i . '_options', ['width' => '200px', 'height' => '50px', 'placeholder' => $placeholder]); ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <?php if (NEWSLETTER_DEBUG) { ?>
                        <div class="psource-tab-panel" id="tabs-debug">
                            <pre><?php echo esc_html(json_encode($this->get_db_options('customfields', $language), JSON_PRETTY_PRINT)) ?></pre>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <p>
                <?php $controls->button_save(); ?>
            </p>
        </form>


    </div>

</div>
