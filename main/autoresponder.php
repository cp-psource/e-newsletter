<?php
defined('ABSPATH') || exit;

class NewsletterAutoresponder {
    static $instance;

    function __construct() {
        self::$instance = $this;
    }

    function init() {
        add_action('admin_menu', array($this, 'add_menu'));
        $this->schedule_cron();
    }

    function panel_index() {
        include NEWSLETTER_DIR . '/main/autoresponderindex.php';
    }

    function panel_edit() {
        include NEWSLETTER_DIR . '/main/autoresponderedit.php';
    }

    function panel() {
        $this->panel_index();
    }

    function panel_subscribers() {
        include NEWSLETTER_DIR . '/main/autoresponderusers.php';
    }

    function panel_statistics() {
        include NEWSLETTER_DIR . '/main/autoresponderstatistics.php';
    }

    function panel_messages() {
        include NEWSLETTER_DIR . '/main/autorespondermessages.php';
    }

    function panel_composer() {
        include NEWSLETTER_DIR . '/main/autorespondercomposer.php';
    }

    public function process_queue() {
        global $wpdb;
        $newsletter = Newsletter::instance();
        $now = time();

        $progress_rows = $wpdb->get_results(
            "SELECT * FROM {$wpdb->prefix}tnp_autoresponder_progress WHERE status = 'active'"
        );

        foreach ($progress_rows as $progress) {
            $email = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}tnp_autoresponder_emails WHERE autoresponder_id = %d AND step = %d",
                $progress->autoresponder_id, $progress->current_step
            ));

            if (!$email) {
                $wpdb->update(
                    "{$wpdb->prefix}tnp_autoresponder_progress",
                    ['status' => 'completed', 'updated_at' => current_time('mysql')],
                    ['id' => $progress->id]
                );
                continue;
            }

            $last_sent = strtotime($progress->last_sent);
            $started_at = strtotime($progress->started_at);
            $base_time = $last_sent ? $last_sent : $started_at;
            $due_time = $base_time + (int)$email->delay * 3600;

            if ($now < $due_time) continue;

            $user = get_userdata($progress->user_id);
            if (!$user) continue;

            // Platzhalter ersetzen
            $body = $email->body;
            $body = str_replace('[name]', $user->display_name, $body);
            $body = str_replace('[email]', $user->user_email, $body);
            // ...weitere Platzhalter...

            $message = [
                'subject' => $email->subject,
                'body'    => $email->body,
                'to'      => $user->user_email,
            ];

            $result = $newsletter->deliver($message);

            if (is_wp_error($result)) {
                error_log('Autoresponder-Fehler für User ' . $progress->user_id . ': ' . $result->get_error_message());
                $wpdb->update(
                    "{$wpdb->prefix}tnp_autoresponder_progress",
                    ['status' => 'error', 'updated_at' => current_time('mysql')],
                    ['id' => $progress->id]
                );
                continue;
            }

            $wpdb->update(
                "{$wpdb->prefix}tnp_autoresponder_progress",
                [
                    'current_step' => $progress->current_step + 1,
                    'last_sent'    => current_time('mysql'),
                    'updated_at'   => current_time('mysql')
                ],
                ['id' => $progress->id]
            );
        }
    }

    public function schedule_cron() {
        if (!wp_next_scheduled('tnp_autoresponder_cron')) {
            wp_schedule_event(time(), 'hourly', 'tnp_autoresponder_cron');
        }
        add_action('tnp_autoresponder_cron', [$this, 'process_queue']);
    }

    function add_menu() {
        // Edit-Seite (versteckt)
        add_submenu_page(
            '',
            __('Edit Autoresponder Series', 'newsletter'),
            '',
            'manage_options',
            'newsletter_main_autoresponderedit',
            array($this, 'panel_edit')
        );
        // Subscribers-Seite (versteckt)
        add_submenu_page(
            '',
            __('Autoresponder Subscribers', 'newsletter'),
            '',
            'manage_options',
            'newsletter_main_autoresponderusers',
            array($this, 'panel_subscribers')
        );
        add_submenu_page(
            '', // kein Parent, damit nicht sichtbar
            __('Autoresponder Statistics', 'newsletter'),
            '',
            'manage_options',
            'newsletter_main_autoresponderstatistics',
            array($this, 'panel_statistics')
        );
        add_submenu_page(
            '', // kein Parent, damit nicht sichtbar
            __('Autoresponder Messages', 'newsletter'),
            '',
            'manage_options',
            'newsletter_main_autorespondermessages',
            array($this, 'panel_messages')
        );
        add_submenu_page(
            '', // kein Parent, damit nicht sichtbar
            __('Autoresponder Composer', 'newsletter'),
            '',
            'manage_options',
            'newsletter_main_autorespondercomposer',
            array($this, 'panel_composer')
        );
    }
}

add_action('newsletter_user_confirmed', function($user) {
    global $wpdb;
    // Hole alle Autoresponder, die automatisch starten sollen
    $autoresponders = $wpdb->get_results(
        "SELECT id FROM {$wpdb->prefix}tnp_autoresponders WHERE auto_start = 1"
    );
    foreach ($autoresponders as $ar) {
        $exists = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}tnp_autoresponder_progress WHERE user_id = %d AND autoresponder_id = %d",
            $user->ID, $ar->id
        ));
        if (!$exists) {
            $wpdb->insert(
                "{$wpdb->prefix}tnp_autoresponder_progress",
                [
                    'user_id' => $user->ID,
                    'autoresponder_id' => $ar->id,
                    'current_step' => 1,
                    'status' => 'active',
                    'started_at' => current_time('mysql'),
                    'updated_at' => current_time('mysql')
                ]
            );
        }
    }
});

// Initialisierung (z.B. in deiner Plugin-Hauptdatei)
if (class_exists('NewsletterAutoresponder')) {
    $GLOBALS['newsletter_autoresponder'] = new NewsletterAutoresponder();
    $GLOBALS['newsletter_autoresponder']->init();
}
