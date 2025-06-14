<?php
/* @var $this NewsletterSystemAdmin */
/* @var $controls NewsletterControls */

defined('ABSPATH') || exit;

if (isset($_GET['id'])) {
    include __DIR__ . '/backup-view.php';
    return;
}

$items = $wpdb->get_results("select option_name from {$wpdb->options} where option_name like 'newsletter_backup_%' order by option_name");

if ($controls->is_action('delete')) {

}
?>

<style>
<?php include __DIR__ . '/css/system.css' ?>
</style>

<div class="wrap tnp-system tnp-system-logs" id="tnp-wrap">

    <?php include NEWSLETTER_ADMIN_HEADER; ?>

    <div id="tnp-heading">

<!--        <h2><?php esc_html_e('Settings backup', 'newsletter') ?></h2>-->
        <?php include __DIR__ . '/nav.php' ?>

    </div>

    <div id="tnp-body">
        <?php $controls->show(); ?>

        <form method="post" action="">
            <?php $controls->init(); ?>

            <div class="psource-tabs" id="tabs">
                <div class="psource-tabs-nav">
                    <button class="psource-tab active" data-tab="tabs-logs"><?php esc_html_e('Settings backup', 'newsletter') ?></button>
                </div>
                <div class="psource-tabs-content">
                    <div class="psource-tab-panel active" id="tabs-logs">
                        <ul class="tnp-log-files">
                            <?php
                            foreach ($items as $item) {
                                $version = substr($item->option_name, -5);
                                echo '<li><a href="?page=newsletter_system_backup&id=', esc_attr($version), '">', esc_html($version), '</a></li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="tnp-buttons">
                <?php $controls->button('delete', 'Delete all'); ?>
            </div>

        </form>
    </div>

</div>
