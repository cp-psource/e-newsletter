<dialog id="test-newsletter-modal">
    <div class="psource-modal-content">
        <div class="psource-modal-header">
            <h3><?php esc_html_e('Send Test', 'newsletter') ?></h3>
            <button class="psource-modal-close">&times;</button>
        </div>
        <div class="psource-modal-body">

            <div id="test-newsletter-message">
            </div>

            <div id="test-newsletter-form">
        <h4><?php _e("Send a test to", 'newsletter') ?></h4>
        <input name="options[test_email]" id="options-test_email" type="email" placeholder="<?php _e("Email", 'newsletter') ?>" id="test-newsletter-email">
        <input type="button" class="button-secondary" onclick="tnpc_test(true);return false;" value="<?php esc_attr_e("Send", 'newsletter') ?>">


        <div class="tnp-separator"><?php _e("or", 'newsletter') ?></div>

        <div class="test-subscribers">
            <?php if (!empty(NewsletterUsersAdmin::instance()->get_test_users())): ?>
                <h4><?php _e("Send a test to test subscribers", 'newsletter') ?></h4>
                <ul>
                    <?php foreach (NewsletterUsersAdmin::instance()->get_test_users() as $user) { ?>
                        <li><?php echo $user->email ?></li>
                    <?php } ?>
                </ul>
                <input type="button" class="button-secondary" onclick="tnpc_test(false);return false;" value="<?php esc_attr_e("Send", 'newsletter') ?>">

            <?php endif; ?>
            <p style="float: right">
                <a href="https://www.thenewsletterplugin.com/documentation/subscribers#test" target="_blank">
                    <?php esc_html_e('More on test subscribers', 'newsletter') ?></a>
            </p>
        </div>

            </div>
        </div>
    </div>
</dialog>

