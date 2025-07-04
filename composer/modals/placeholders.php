<dialog id="tnpc-placeholders-modal">
    <div class="psource-modal-content">
        <div class="psource-modal-header">
            <h3><?php esc_html_e('Placeholders', 'newsletter') ?></h3>
            <button class="psource-modal-close">&times;</button>
        </div>
        <div class="psource-modal-body">
            <ul>
                <li>{name} - <?php esc_html_e('First name', 'newsletter') ?></li>
                <li>{surname} - <?php esc_html_e('Last name', 'newsletter') ?></li>
                <li>{email} - <?php esc_html_e('Email', 'newsletter') ?></li>
                <li>{profile_N} - <?php esc_html_e('Profile numner N with N=1, 2, 3, ...', 'newsletter') ?></li>
                <li>{email_url} - <?php esc_html_e('Email online view', 'newsletter') ?></li>
            </ul>
            <p>
                <a href="https://cp-psource.github.io/e-newsletter/newsletter-tags/" target="_blank">See the documentation</a>
            </p>
        </div>
    </div>
</dialog>