<?php
/* @var $this NewsletterAutomated */

defined('ABSPATH') || exit;

if (!isset($controls) || !$controls) {
    include_once NEWSLETTER_INCLUDES_DIR . '/controls.php';
    $controls = new NewsletterControls();
}

// ID aus der URL holen
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$channels = get_option('tnp_automated_channels', []);
if ($id && isset($channels[$id])) {
    $channel = (object) $channels[$id];
} else {
    // Fallback: leerer Kanal
    $channel = new stdClass();
    $channel->id = 0;
    $channel->data = [
        'name' => '',
        'track' => 1,
        'frequency' => 'weekly',
        'day_1' => 1,
    ];
}

// Echte E-Mail-Daten laden
$emails = get_option('tnp_automated_emails', []);
$email = null;
foreach ($emails as $e) {
    if ($e['channel_id'] == $channel->id) {
        $email = (object)$e;
        break;
    }
}
if (!$email) {
    $email = new stdClass();
    $email->id = 0;
    $email->status = '';
    $email->subject = '';
    $email->send_on = 0;
}

TNP_Composer::prepare_controls($controls, $email);
?>
<div class="wrap" id="tnp-wrap">
    <?php include NEWSLETTER_ADMIN_HEADER ?>
    <div id="tnp-heading">

        <?php include __DIR__ . '/automatednav.php' ?>

    </div>

    <div id="tnp-body" class="tnp-automated-edit">
        <?php $controls->show(); ?>


        <div class="tnp-automated-edit">

            <form method="post" id="tnpc-form" action="" onsubmit="tnpc_save(this); return true;">
                <?php $controls->init(); ?>

                <?php $controls->composer_fields_v2() ?>

            </form>
            <?php $controls->composer_load_v2(true, false, 'automated') ?>

        </div>

    </div>
</div>
