<?php
/* @var $this NewsletterMainAdmin */
/* @var $controls NewsletterControls */

defined('ABSPATH') || exit;

// Very very naif
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'save') {

    if (!check_admin_referer('save')) {
        die('Invalid request');
    }
    // Sender
    $options = $this->get_options();
    $options['sender_name'] = sanitize_text_field(wp_unslash($_POST['sender_name'] ?? ''));
    $options['sender_email'] = sanitize_email(wp_unslash($_POST['sender_email'] ?? ''));
    if ($options['sender_email']) {
        $this->save_options($options);
    }

    // Form
    $options = NewsletterSubscriptionAdmin::instance()->get_form_options();
    $options['privacy_status'] = isset($_POST['field_privacy']) ? 1 : 0;
    $options['name_status'] = isset($_POST['field_name']) ? 2 : 0;
    NewsletterSubscriptionAdmin::instance()->save_options($options, 'form');
    die();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] == 'test') {
    if (!check_admin_referer('save')) {
        die('Invalid request');
    }
    $email = sanitize_email(wp_unslash($_POST['test_email']));
    $status_options = $this->get_options('status');

    if (!$this->is_email($email)) {
        echo esc_html_e('Please check the email address, it seems wrong.', 'newsletter');
        die();
    }
    // Newsletter mail
    $text = [];
    $text['html'] = '<p>This is an <b>HTML</b> test email sent using the sender data set on Newsletter main setting. <a href="https://www.thenewsletterplugin.com">This is a link to an external site</a>.</p>';
    $text['text'] = 'This is a textual test email part sent using the sender data set on Newsletter main setting.';
    $r = Newsletter::instance()->mail($email, 'Newsletter test email at ' . date(DATE_ISO8601), $text);

    if ($r) {
        //$status_options['mail'] = 1;
        //$module->save_options($status_options, 'status');
        echo esc_html_e('Check your mailbox for a test message. Check the spam folder as well.', 'newsletter');
        die();
    } else {
        //$status_options['mail'] = 0;
        //$status_options['mail_error'] = $module->mail_last_error;
        //$module->save_options($status_options, 'status');
        echo esc_html_e('There was an error. Complete the setup and then use the System panels to test again.', 'newsletter');
        die();
    }
    die();
}

$profile_options = NewsletterSubscriptionAdmin::instance()->get_form_options();
$subscription_options = NewsletterSubscriptionAdmin::instance()->get_options();

if (empty($this->get_option('page'))) {

    $this->logger->info('Adding a dedicated page');
    // Page creation
    $page = [];
    $page['post_title'] = 'Newsletter';
    $page['post_content'] = '[newsletter]';
    $page['post_status'] = 'publish';
    $page['post_type'] = 'page';
    $page['comment_status'] = 'closed';
    $page['ping_status'] = 'closed';
    $page['post_category'] = array(1);

    // Insert the post into the database
    $page_id = wp_insert_post($page);

    $options = $this->get_main_options();
    $options['page'] = $page_id;
    $this->save_main_options($options);

    // Test subscriber creation
    $users = $this->get_test_users();
    if (!$users) {
        $this->logger->info('Adding a test subscriber');
        global $current_user;
        $user = [];
        $user['email'] = $current_user->user_email;
        $user['name'] = $current_user->first_name;
        $user['surname'] = $current_user->last_name;
        $user['test'] = 1;
        $user['status'] = TNP_User::STATUS_CONFIRMED;
        $this->save_user($user);
    }
} else {
    $this->logger->info('Dedicated page already exists');
}
?>
<style>
<?php include __DIR__ . '/css/welcome.css' ?>
</style>
<script src="<?php echo esc_attr(plugins_url('e-newsletter')) ?>/main/js/welcome.js"></script>
<script>
    // Email test
    function tnp_welcome_test() {
        jQuery.post("?page=newsletter_main_welcome&noheader=1&action=test",
                jQuery("#tnp-welcome").serialize(),
                function (response) {
                    alert(response);
                });
    }

    function tnp_welcome_subscribe() {
        var form = document.getElementById("tnp-subscription");
        form.elements["ne"].value = document.getElementById("tnp-ne").value;
        form.submit();
        alert('Thank you!');
    }
</script>
<div class="wrap" id="tnp-wrap">
    <form id="tnp-welcome">
        <?php wp_nonce_field('save') ?>
        <section class="cd-slider-wrapper">
            <ul class="cd-slider">
                <li class="tnp-first-slide visible">
                    <div>
                        <img class="tnp-logo-big" src="<?php echo esc_attr(plugins_url('e-newsletter')); ?>/admin/images/logo-white.png">
                        <p><?php esc_html_e('Welcome to PS eNewsletter and thank you for choosing the best mail management system for ClassicPress!', 'newsletter'); ?><br><br>
                            <?php esc_html_e('In this short tutorial we will guide you through some of the basic settings to get the most out of our plugin. ', 'newsletter'); ?></p>
                    </div>
                </li>

                <li data-update="tnp_slider_sender">
                    <div>
                        <h2><?php esc_html_e('Sender', 'newsletter'); ?></h2>
                        <p><?php esc_html_e('Choose which name and email address you\'d like to appear as the sender of your newsletters.', 'newsletter'); ?></p>
                        <input type="text" placeholder="<?php esc_attr_e('Sender name', 'newsletter') ?>" value="<?php echo esc_attr(Newsletter::instance()->get_sender_name()) ?>" name="sender_name">&nbsp;
                        <input type="email" placeholder="<?php esc_attr_e('Sender email', 'newsletter') ?>" value="<?php echo esc_attr(Newsletter::instance()->get_sender_email()) ?>" name="sender_email">
                    </div>
                </li>

                <li>
                    <div>
                        <h2><?php esc_html_e('Subscription Forms', 'newsletter'); ?></h2>
                        <p><?php esc_html_e('Choose what to ask to your subscribers in your forms.', 'newsletter'); ?></p>
                        <div class="row tnp-row-padded">
                            <div class="tnp-col-3-boxed">
                                <p><?php esc_html_e('Ask for their name', 'newsletter'); ?></p>
                                <label class="switch">
                                    <input type="checkbox" name="field_name" <?php echo $profile_options['name_status'] > 0 ? 'checked' : '' ?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <div class="tnp-col-3-boxed">
                                <p><?php esc_html_e('Add a privacy checkbox', 'newsletter'); ?></p>
                                <label class="switch">
                                    <input type="checkbox" name="field_privacy" <?php echo $profile_options['privacy_status'] > 0 ? 'checked' : '' ?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                </li>

                <li>
                    <div>
                        <h2><?php esc_html_e('Subscription and Edit page creation', 'newsletter'); ?></h2>
                        <p><?php esc_html_e('We\'ve just created the page where your visitors will subscribe and where they will edit their preferences.', 'newsletter'); ?></p>
                    </div>
                </li>
                <li>
                    <div>
                        <h2><?php esc_html_e('Time for some tests!', 'newsletter'); ?></h2>
                        <p><?php esc_html_e('Check if your website can send emails correctly.', 'newsletter'); ?></p>
                        <input type="email" value="<?php echo esc_attr(get_option('admin_email')) ?>" name="test_email" placeholder="<?php esc_html_e('Email address', 'newsletter'); ?>">
                        <div>
                            <a href="#" class="tnp-welcome-confirm-button" onclick="tnp_welcome_test(); return false;"><?php esc_html_e('Send a test message', 'newsletter'); ?></a>
                        </div>
                    </div>
                </li>


                <li>
                    <div>
                        <h2><?php esc_html_e('Add Newsletter widget to sidebar', 'newsletter'); ?></h2>
                        <p><?php esc_html_e('If you use sidebars in your blog, it may be a good idea to add a subscription form there. Remember to come back here when you\'re done', 'newsletter'); ?> ;)</p>
                        <div>
                            <a href="<?php echo esc_attr(admin_url('widgets.php')); ?>" class="tnp-welcome-confirm-button" target="_blank"><?php esc_html_e('Take me to my widget settings (opens in a new window)', 'newsletter'); ?></a>
                        </div>
                    </div>
                </li>



                <li class="tnp-last-slide">
                    <div>
                        <svg style="margin-bottom: 25px;" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 48 48" xml:space="preserve" width="64" height="64"><g class="nc-icon-wrapper"><path fill="#FFD764" d="M24,47C11.31738,47,1,36.68213,1,24S11.31738,1,24,1s23,10.31787,23,23S36.68262,47,24,47z"></path> <path fill="#444444" d="M17,19c-0.55273,0-1-0.44775-1-1c0-1.10303-0.89746-2-2-2s-2,0.89697-2,2c0,0.55225-0.44727,1-1,1 s-1-0.44775-1-1c0-2.20557,1.79395-4,4-4s4,1.79443,4,4C18,18.55225,17.55273,19,17,19z"></path> <path fill="#444444" d="M37,19c-0.55273,0-1-0.44775-1-1c0-1.10303-0.89746-2-2-2s-2,0.89697-2,2c0,0.55225-0.44727,1-1,1 s-1-0.44775-1-1c0-2.20557,1.79395-4,4-4s4,1.79443,4,4C38,18.55225,37.55273,19,37,19z"></path> <path fill="#FFFFFF" d="M35.6051,32C35.85382,31.03912,36,30.03748,36,29c0-0.55225-0.44727-1-1-1H13c-0.55273,0-1,0.44775-1,1 c0,1.03748,0.14618,2.03912,0.3949,3H35.6051z"></path> <path fill="#AE453E" d="M12.3949,32c1.33734,5.16699,6.02551,9,11.6051,9s10.26776-3.83301,11.6051-9H12.3949z"></path> <path fill="#FA645A" d="M18.01404,39.38495C19.77832,40.40594,21.81903,41,24,41s4.22168-0.59406,5.98596-1.61505 C28.75952,37.35876,26.54126,36,24,36S19.24048,37.35876,18.01404,39.38495z"></path></g></svg>
                        <h2>Hooooray!</h2>
                        <p><?php esc_html_e('You\'re now ready to begin using Newsletter!', 'newsletter'); ?></p>

                        <p><a href="<?php echo esc_attr(admin_url('admin.php?page=newsletter_main_index')); ?>" class="tnp-welcome-link-button"><?php esc_html_e('Go to your dashboard', 'newsletter'); ?></a></p>

                </li>
            </ul> <!-- .cd-slider -->

            <div class="cd-slider-navigation">
                <a class="tnp-welcome-prev" style="display: none" href="#" onclick="prevSlide(); return false;"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 24 24" xml:space="preserve" width="16" height="16"<g class="nc-icon-wrapper" fill="#ffffff"><path fill="#ffffff" d="M17,23.414L6.293,12.707c-0.391-0.391-0.391-1.023,0-1.414L17,0.586L18.414,2l-10,10l10,10L17,23.414z"></path></g></svg><?php esc_html_e('Previous', 'newsletter'); ?></a>
                <a class="tnp-welcome-next" href="#" onclick="nextSlide(); return false;"><?php esc_html_e('Next', 'newsletter'); ?><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 24 24" xml:space="preserve" width="16" height="16"><g class="nc-icon-wrapper" fill="#ffffff"><path fill="#ffffff" d="M7,23.414L5.586,22l10-10l-10-10L7,0.586l10.707,10.707c0.391,0.391,0.391,1.023,0,1.414L7,23.414z"></path></g></svg></a>
            </div>

            <div class="cd-svg-cover" data-step1="M1402,800h-2V0.6c0-0.3,0-0.3,0-0.6h2v294V800z" data-step2="M1400,800H383L770.7,0.6c0.2-0.3,0.5-0.6,0.9-0.6H1400v294V800z" data-step3="M1400,800H0V0.6C0,0.4,0,0.3,0,0h1400v294V800z" data-step4="M615,800H0V0.6C0,0.4,0,0.3,0,0h615L393,312L615,800z" data-step5="M0,800h-2V0.6C-2,0.4-2,0.3-2,0h2v312V800z" data-step6="M-2,800h2L0,0.6C0,0.3,0,0.3,0,0l-2,0v294V800z" data-step7="M0,800h1017L629.3,0.6c-0.2-0.3-0.5-0.6-0.9-0.6L0,0l0,294L0,800z" data-step8="M0,800h1400V0.6c0-0.2,0-0.3,0-0.6L0,0l0,294L0,800z" data-step9="M785,800h615V0.6c0-0.2,0-0.3,0-0.6L785,0l222,312L785,800z" data-step10="M1400,800h2V0.6c0-0.2,0-0.3,0-0.6l-2,0v312V800z">
                <svg height='100%' width="100%" preserveAspectRatio="none" viewBox="0 0 1400 800">
                <title>SVG cover layer</title>
                <desc>an animated layer to switch from one slide to the next one</desc>
                <path id="cd-changing-path" d="M1402,800h-2V0.6c0-0.3,0-0.3,0-0.6h2v294V800z"/>
                </svg>
            </div>  .cd-svg-cover
        </section> <!-- .cd-slider-wrapper -->
    </form>

</div>
