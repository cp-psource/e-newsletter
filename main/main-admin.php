<?php

defined('ABSPATH') || exit;

class NewsletterMainAdmin extends NewsletterModuleAdmin {

    static $instance;

    /**
     * @return NewsletterMainAdmin
     */
    static function instance() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    function __construct() {
        parent::__construct('main');
        add_filter('display_post_states', [$this, 'hook_display_post_states'], 10, 2);
    }

    function wp_loaded() {
        if ($this->is_admin_page()) {

            // Dismiss messages
            if (isset($_GET['dismiss'])) {
                $dismissed = $this->get_option_array('newsletter_dismissed');
                $dismissed[$_GET['dismiss']] = 1;
                update_option('newsletter_dismissed', $dismissed, false);
                wp_safe_redirect(remove_query_arg(['dismiss', 'noheader', 'debug']));
                exit();
            }

            // Dismiss news
            if (isset($_GET['news'])) {
                Newsletter\News::dismiss($_GET['news']);
                wp_safe_redirect(remove_query_arg('news'));
                exit();
            }
        }
    }

    function admin_notices() {
        if ($this->get_option('debug')) {
            echo '<div class="notice notice-warning"><p>PS eNewsletter is in <strong>debug mode</strong>. When done change it on Newsletter <a href="admin.php?page=newsletter_main_main"><strong>main settings</strong></a>. Do not keep the debug mode active on production sites.</p></div>';
        }

        $count = $this->get_emails_blocked_count();
        if ($count) {
            echo '<div class="notice notice-error"><p style="font-size: 1.2em">One or more newsletters have been blocked due to severe delivery error. <a href="admin.php?page=newsletter_system_delivery#newsletters-error">Check and restart</a>.</p></div>';
        }
    }

    function admin_menu() {
        //$this->add_menu_page('index', __('Dashboard', 'newsletter'));
        $this->add_admin_page('info', esc_html__('Company info', 'newsletter'));

        if (current_user_can('administrator')) {
            $this->add_admin_page('welcome', esc_html__('Welcome', 'newsletter'));
            //$this->add_menu_page('main', __('Settings', 'newsletter'));
            // Pages not on menu
            $this->add_admin_page('cover', 'Cover');
            //$this->add_admin_page('setup', 'Setup');
            $this->add_admin_page('flow', 'Flow');
        }
    }

    function hook_display_post_states($post_states, $post) {

        $for = [];
        if ($this->is_multilanguage()) {
            $languages = $this->get_languages();
            foreach ($languages as $id => $name) {
                $page_id = $this->get_option('page', '', $id);
                if ($page_id == $post->ID) {
                    $for[] = $name;
                }
            }
            if ($post->ID == $this->get_main_option('page')) {
                $for[] = 'All languages fallback';
            }
            if ($for) {
                $post_states[] = __('Newsletter public page, keep public and published', 'newsletter')
                        . ' - ' . esc_html(implode(', ', $for));
            }
        } else {

            if ($post->ID == $this->get_main_option('page')) {
                $post_states[] = __('Newsletter public page, keep public and published', 'newsletter');
            }
        }

        return $post_states;
    }

    /* Wrappers */

    function set_completed_step($step) {
        $steps = $this->get_option_array('newsletter_main_steps');
        $steps[$step] = 1;
        update_option('newsletter_main_steps', $steps);
    }
}
