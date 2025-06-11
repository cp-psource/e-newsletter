<?php

defined('ABSPATH') || exit;

class NewsletterSmtp extends NewsletterMailerAddon {

    /**
     * @var NewsletterSmtp
     */
    static $instance;

    public function __construct($version) {
        self::$instance = $this;
        $this->menu_title = 'SMTP';
        parent::__construct('smtp', $version, __DIR__);
    }

    function init() {
        parent::init();
    }

    function weekly_check() {
        parent::weekly_check();
        $license_key = Newsletter::instance()->get_license_key();
        $response = wp_remote_post('https://www.thenewsletterplugin.com/wp-content/addon-check.php?k=' . rawurlencode($license_key)
                . '&a=' . rawurlencode($this->name) . '&d=' . rawurlencode(home_url()) . '&v=' . rawurlencode($this->version)
                . '&ml=' . (Newsletter::instance()->is_multilanguage() ? '1' : '0'));
    }

    public function get_mailer() {
        static $mailer = null;
        if (!$mailer) {
            require_once __DIR__ . '/classes/mailer.php';
            $mailer = new NewsletterSmtpMailer($this->options);
        }
        return $mailer;
    }

}

