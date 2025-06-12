<?php
defined('ABSPATH') || exit;

class NewsletterAutomated {
    static $instance;

    function __construct() {
        self::$instance = $this;
    }

    function init() {
        add_action('admin_menu', array($this, 'add_menu'));
    }

    function panel_newsletters() {
        include NEWSLETTER_DIR . '/main/automatednewsletters.php';
    }

    function panel_edit() {
        include NEWSLETTER_DIR . '/main/automatededit.php';
    }

    function panel_index() {
        include NEWSLETTER_DIR . '/main/automatedindex.php';
    }

    function panel() {
        $this->panel_index();
    }

    function add_menu() {
        add_submenu_page(
            'newsletter_main_index',
            __('Automated', 'newsletter'),
            '<span class="tnp-side-menu">Automated</span>',
            'manage_options',
            'newsletter_main_automated',
            array($this, 'panel')
        );
        // Edit-Seite (versteckt)
        add_submenu_page(
            '',
            __('Edit Automated Channel', 'newsletter'),
            '',
            'manage_options',
            'newsletter_main_automatededit',
            array($this, 'panel_edit')
        );
        // Template-Seite (versteckt)
        add_submenu_page(
            '',
            __('Automated Template', 'newsletter'),
            '',
            'manage_options',
            'newsletter_main_automatedtemplate',
            array($this, 'panel_template')
        );
        // Newsletters-Seite (versteckt)
        add_submenu_page(
            '',
            __('Automated Newsletters', 'newsletter'),
            '',
            'manage_options',
            'newsletter_main_automatednewsletters',
            array($this, 'panel_newsletters')
        );
        // Index-Seite (versteckt, aber für Direktaufruf)
        add_submenu_page(
            '',
            __('Automated Index', 'newsletter'),
            '',
            'manage_options',
            'newsletter_main_automatedindex',
            array($this, 'panel_index')
        );
    }

    function panel_template() {
        include NEWSLETTER_DIR . '/main/automatedtemplate.php';
    }
}