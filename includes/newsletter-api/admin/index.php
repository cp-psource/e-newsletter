<?php
/* @var $this NewsletterApi */

include_once NEWSLETTER_INCLUDES_DIR . '/controls.php';
require_once __DIR__ . '/../autoloader.php';

use TNP\API\V2\TNP_REST_Authentication_Repository;

$controls = new NewsletterControls();

if (!$controls->is_action()) {
    $controls->data = $this->options;
} else {
    if ($controls->is_action('save')) {
        $this->save_options($controls->data);
        $controls->add_message_saved();
    }

    if ($controls->is_action('delete_v2_key')) {
        $this->delete_key($controls->button_data);
        $controls->add_message_deleted();
    }
}
?>

<div class="wrap" id="tnp-wrap">
    <?php include NEWSLETTER_ADMIN_HEADER ?>
    <div id="tnp-heading">
        <?php $controls->title_help('/developers/newsletter-api-2/') ?>
        <h2>Newsletter API</h2>
    </div>
    <div id="tnp-body">
        <?php $controls->show(); ?>
        <form action="" method="post">
            <?php $controls->init(); ?>
            <div class="psource-tabs" id="tabs">
                <div class="psource-tabs-nav">
                    <button class="psource-tab active" data-tab="tab-api-v2">API v2</button>
                    <button class="psource-tab" data-tab="tab-api-v1">API v1</button>
                    <button class="psource-tab" data-tab="tab-log">Log</button>
                </div>
                <div class="psource-tabs-content">
                    <div class="psource-tab-panel active tab-min-height" id="tab-api-v2">
                        <?php /* ...dein kompletter Inhalt von <div id="tab-api-v2" ...> ... </div> bleibt hier wie gehabt... */ ?>
                        <?php if ($controls->is_action('create_v2_key')) : ?>
                            <!-- ... -->
                        <?php elseif ($controls->is_action('save_v2_key')) : ?>
                            <!-- ... -->
                        <?php else: ?>
                            <!-- ... -->
                        <?php endif; ?>
                    </div>
                    <div class="psource-tab-panel tab-min-height" id="tab-api-v1">
                        <table class="form-table">
                            <tr>
                                <th>API Key</th>
                                <td>
                                    <?php $controls->text('key', 50); ?>
                                </td>
                            </tr>
                        </table>
                        <p class="submit">
                            <?php $controls->button_save(); ?>
                        </p>
                    </div>
                    <div class="psource-tab-panel tab-min-height" id="tab-log">
                        <p>For more details enable debug logs in the <a href="?page=newsletter_main_main">advanced settings</a> and check them on the <a href="?page=newsletter_system_logs">logs page</a>.</p>
                        <?php $controls->logs('api'); ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <?php include NEWSLETTER_ADMIN_FOOTER ?>
</div>
