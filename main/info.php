<?php
/* @var $this NewsletterMainAdmin */
/* @var $controls NewsletterControls */

defined('ABSPATH') || exit;

if (!$controls->is_action()) {
    $controls->data = $this->get_options('info');
} else {

    if ($controls->is_action('save')) {
        $controls->data = wp_kses_post_deep($controls->data);
        $this->save_options($controls->data, 'info');
        $controls->add_toast_saved();
        NewsletterMainAdmin::instance()->set_completed_step('company');
    }
}
?>

<div class="wrap" id="tnp-wrap">
    <?php include NEWSLETTER_ADMIN_HEADER; ?>

    <div id="tnp-heading">

<!--        <h2><?php esc_html_e('Settings', 'newsletter') ?></h2>-->
        <?php include __DIR__ . '/nav.php' ?>

    </div>
    <div id="tnp-body">

        <?php $controls->show() ?>

        <form method="post" action="">
            <?php $controls->init(); ?>

            <div class="psource-tabs" id="tabs">
                <div class="psource-tabs-nav">
                    <button class="psource-tab active" data-tab="tabs-general"><?php esc_html_e('General', 'newsletter') ?></button>
                    <button class="psource-tab" data-tab="tabs-social"><?php esc_html_e('Social', 'newsletter') ?></button>
                    <?php if (NEWSLETTER_DEBUG) { ?>
                        <button class="psource-tab" data-tab="tabs-debug">Debug</button>
                    <?php } ?>
                </div>
                <div class="psource-tabs-content">
                    <div class="psource-tab-panel active" id="tabs-general">

                        <?php $this->language_notice(); ?>

                        <?php if ($is_all_languages) { ?>

                            <table class="form-table">
                                <tr>
                                    <th>
                                        <?php esc_html_e('Logo', 'newsletter') ?><br>
                                    </th>
                                    <td style="cursor: pointer">
                                        <?php $controls->media('header_logo', 'medium'); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e('Title', 'newsletter') ?></th>
                                    <td>
                                        <?php $controls->text('header_title', 40); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e('Motto', 'newsletter') ?></th>
                                    <td>
                                        <?php $controls->text('header_sub', 40); ?>
                                    </td>
                                </tr>
                            </table>

                            <table class="form-table">
                                <tr>
                                    <th><?php esc_html_e('Company name', 'newsletter') ?></th>
                                    <td>
                                        <?php $controls->text('footer_title', 40); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e('Address', 'newsletter') ?></th>
                                    <td>
                                        <?php $controls->text('footer_contact', 40); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th><?php esc_html_e('Copyright or legal text', 'newsletter') ?></th>
                                    <td>
                                        <?php $controls->text('footer_legal', 40); ?>
                                    </td>
                                </tr>
                            </table>
                        <?php } ?>
                    </div>

                    <div class="psource-tab-panel" id="tabs-social">
                        <?php $this->language_notice(); ?>
                        <?php if (!$language) { ?>

                            <table class="form-table">
                                <tr>
                                    <th>Facebook</th>
                                    <td>
                                        <?php $controls->text('facebook_url', 40); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>X</th>
                                    <td>
                                        <?php $controls->text('twitter_url', 40); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Instagram</th>
                                    <td>
                                        <?php $controls->text('instagram_url', 40); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Pinterest</th>
                                    <td>
                                        <?php $controls->text('pinterest_url', 40); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Linkedin</th>
                                    <td>
                                        <?php $controls->text('linkedin_url', 40); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tumblr</th>
                                    <td>
                                        <?php $controls->text('tumblr_url', 40); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>YouTube</th>
                                    <td>
                                        <?php $controls->text('youtube_url', 40); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Vimeo</th>
                                    <td>
                                        <?php $controls->text('vimeo_url', 40); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Soundcloud</th>
                                    <td>
                                        <?php $controls->text('soundcloud_url', 40); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Telegram</th>
                                    <td>
                                        <?php $controls->text('telegram_url', 40); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>VK</th>
                                    <td>
                                        <?php $controls->text('vk_url', 40); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Twitch</th>
                                    <td>
                                        <?php $controls->text('twitch_url', 40); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Discord</th>
                                    <td>
                                        <?php $controls->text('discord_url', 40); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>TikTok</th>
                                    <td>
                                        <?php $controls->text('tiktok_url', 40); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>WhatsApp</th>
                                    <td>
                                        <?php $controls->text('whatsapp_url', 40); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Threads</th>
                                    <td>
                                        <?php $controls->text('threads_url', 40); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Amazon</th>
                                    <td>
                                        <?php $controls->text('amazon_url', 40); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Mastodon</th>
                                    <td>
                                        <?php $controls->text('mastodon_url', 40); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Kickstarter</th>
                                    <td>
                                        <?php $controls->text('kickstarter_url', 40); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Bluesky</th>
                                    <td>
                                        <?php $controls->text('bluesky_url', 40); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Spotify</th>
                                    <td>
                                        <?php $controls->text('spotify_url', 40); ?>
                                    </td>
                                </tr>
                            </table>
                        <?php } ?>
                    </div>

                    <?php if (NEWSLETTER_DEBUG) { ?>
                        <div class="psource-tab-panel" id="tabs-debug">
                            <pre><?php echo esc_html(json_encode($this->get_db_options('info', $language), JSON_PRETTY_PRINT)) ?></pre>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="tnp-buttons">
                <?php $controls->button_save(); ?>
            </div>
        </form>
    </div>

</div>
