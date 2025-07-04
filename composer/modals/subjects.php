<script>
    jQuery(function ($) {
        jQuery("#tnp-edit-subjects-list a").on('click', function (e) {
            e.preventDefault();
            jQuery("#options-subject").val(this.innerText);
            //console.log(jQuery("#options-subject"));
            $.modal.close();
        });

    });
</script>

<dialog id="subject-ideas-modal">
    <div class="psource-modal-content">
        <div class="psource-modal-header">
            <h3><?php esc_html_e('Subject Ideas', 'newsletter') ?></h3>
            <button class="psource-modal-close">&times;</button>
        </div>
        <div class="psource-modal-body">
            <div id="tnp-edit-subjects-list">
        <h3 class="tnp-subject-category">Promotions</h3>
        <a href="#"><?php _e('Last day to save 30%', 'newsletter') ?></a><br>
        <a href="#"><?php _e('Black Friday Sale is almost over', 'newsletter') ?></a><br>
        <a href="#"><?php _e('Black Friday 65% off ending soon!', 'newsletter') ?></a><br>
        <a href="#"><?php _e('Your Final 24 Hours | Last Call for Black Friday Deals', 'newsletter') ?></a><br>
        <a href="#"><?php _e('Black Friday Sale is Live', 'newsletter') ?></a><br>
        <a href="#"><?php _e('Black Friday Sale: few hours left!', 'newsletter') ?></a><br>

        <h3 class="tnp-subject-category">Dangers</h3>
        <a href="#"><?php _e('How safe is your <em>[something]</em> from <em>[danger]</em>?', 'newsletter') ?></a><br>
        <a href="#"><?php _e('10 Warning Signs That <em>[something]</em>', 'newsletter') ?></a><br>
        <a href="#"><?php _e('10 Lies <em>[kind of people]</em> Likes to Tell', 'newsletter') ?></a><br>



        <h3 class="tnp-subject-category">Better life, problem management</h3>
        <a href="#"><?php _e('10 Ways to Simplify Your <em>[something]</em>', 'newsletter') ?></a><br>
        <a href="#"><?php _e('Get Rid of <em>[problem]</em> Once and Forever', 'newsletter') ?></a><br>
        <a href="#"><?php _e('How to End [problem]', 'newsletter') ?></a><br>
        <a href="#"><?php _e('Secrets of [famous people]', 'newsletter') ?></a><br>

        <h3 class="tnp-subject-category">Mistakes</h3>
        <a href="#"><?php _e('Do You Make These 10 <em>[something]</em> Mistakes?', 'newsletter') ?></a><br>
        <a href="#"><?php _e('10 <em>[something]</em> Mistakes That Make You Look Dumb', 'newsletter') ?></a><br>
        <a href="#"><?php _e('Don\'t do These 10 Things When <em>[something]</em>', 'newsletter') ?></a><br>

        <h3 class="tnp-subject-category">Lists</h3>
        <a href="#"><?php _e('10 Ways to <em>[something]</em>', 'newsletter') ?></a><br>
        <a href="#"><?php _e('The Top 10 <em>[something]</em>', 'newsletter') ?></a><br>
        <a href="#"><?php _e('The 10 Rules for <em>[something]</em>', 'newsletter') ?></a><br>
        <a href="#"><?php _e('Get/Become <em>[something]</em>. 10 Ideas That Work', 'newsletter') ?></a><br>
            </div>
        </div>
    </div>
</dialog>
