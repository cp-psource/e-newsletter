<?php

defined('ABSPATH') || exit;

require_once NEWSLETTER_INCLUDES_DIR . '/controls.php';
$controls = new NewsletterControls();

global $wpdb;

// E-Mail-ID aus GET holen
$email_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// E-Mail laden
$email = $wpdb->get_row($wpdb->prepare(
    "SELECT * FROM {$wpdb->prefix}tnp_autoresponder_emails WHERE id = %d", $email_id
));

// Autoresponder laden (für Navigation etc.)
$autoresponder = $wpdb->get_row($wpdb->prepare(
    "SELECT * FROM {$wpdb->prefix}tnp_autoresponders WHERE id = %d", $email->autoresponder_id
));

// Fallback, falls keine E-Mail gefunden wurde
if (!$email) {
    echo '<div class="notice notice-error">E-Mail nicht gefunden.</div>';
    return;
}

// Optionen vorbereiten (z.B. Delay)
$email->options = ['delay' => $email->delay ?? 0];

TNP_Composer::prepare_controls($controls, $email);
?>
<div class="wrap" id="tnp-wrap">

    <?php $controls->show(); ?>

    <div id="tnp-body">

        <form id="tnpc-form" method="post" action="" onsubmit="tnpc_save(this); return true;">
            <?php $controls->init(); ?>
            <p>
                <?php $controls->button_back('?page=newsletter_main_autorespondermessages&id=' . intval($autoresponder->id), '') ?>
            </p>

            <table class="form-table" style="width: auto; margin-bottom: 20px">
                <tr>
                    <th>Delay (hours)</th>
                    <td><?php $controls->text('options_delay') ?></td>
                </tr>
            </table>

            <?php $controls->composer_fields_v2() ?>

        </form>

        <?php $controls->composer_load_v2(true, false, 'autoresponder') ?>
    </div>

</div>
