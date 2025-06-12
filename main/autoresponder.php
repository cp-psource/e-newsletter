<?php
defined('ABSPATH') || exit;

class NewsletterAutoresponder {
    static $instance;

    function __construct() {
        self::$instance = $this;
    }

    function init() {
        add_action('admin_menu', array($this, 'add_menu'));
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

// Initialisierung (z.B. in deiner Plugin-Hauptdatei)
if (class_exists('NewsletterAutoresponder')) {
    $GLOBALS['newsletter_autoresponder'] = new NewsletterAutoresponder();
    $GLOBALS['newsletter_autoresponder']->init();
}
