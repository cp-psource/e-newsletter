<?php

defined('ABSPATH') || exit;

if (!isset($controls) || !$controls) {
    include_once NEWSLETTER_INCLUDES_DIR . '/controls.php';
    $controls = new NewsletterControls();
}

// Channel-ID aus der URL holen
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
?>
<script src="<?php echo plugins_url('e-newsletter') ?>/vendor/driver/driver.js.iife.js"></script>
<link rel="stylesheet" href="<?php echo plugins_url('e-newsletter') ?>/vendor/driver/driver.css"/>

<div class="wrap" id="tnp-wrap">
    <?php include NEWSLETTER_ADMIN_HEADER ?>
    <div id="tnp-heading">
       <?php include __DIR__ . '/automatednav.php' ?>
    </div>

    <div id="tnp-body" class="tnp-automated-edit">


        <form method="post" action="">
            <?php $controls->init(); ?>


            <table class="widefat" id="tnp-automated-newsletters">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Subject</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $count = 0; ?>
                    <?php foreach ($emails as $email) { ?>
                        <?php $count++; ?>
                        <tr>
                            <td><?php echo $email->id; ?></td>
                            <td>
                                <?php echo esc_html($email->subject); ?>
                            </td>
                            <td style="white-space: nowrap">
                                <?php echo NewsletterControls::print_date($email->send_on); ?>
                            </td>
                            <td class="tnp-automated-status">
                                <?php Newsletter::instance()->show_email_status_label($email) ?>
                            </td>

                            <td style="white-space: nowrap" class="tnp-automated-actions">
                                <?php $controls->button_icon_statistics('#') ?>
                                <?php $controls->button_icon_view('#') ?>
                                <?php $controls->button_icon_delete(0); ?>
                                <?php $controls->button_icon('abort', 'fa-stop', 'Block this newsletter', 0, true); ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>



        </form>

    </div>

</div>