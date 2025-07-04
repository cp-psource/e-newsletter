<!-- Social -->
<?php
if (!defined('ABSPATH')) exit;

if (isset($theme_options['theme_social_disable'])) return;
$social_icon_url = plugins_url('e-newsletter') . '/emails/themes/default/images';
?>
<table cellpadding="5" align="center">
    <tr>
        <?php if (!empty($theme_options['theme_facebook'])) { ?>
            <td style="text-align: center; vertical-align: top" align="center" valign="top">
                <a href="<?php echo esc_attr($theme_options['theme_facebook']) ?>"><img src="<?php echo $social_icon_url ?>/facebook.png"><br>Facebook</a>
            </td>
        <?php } ?>

        <?php if (!empty($theme_options['theme_twitter'])) { ?>
            <td style="text-align: center; vertical-align: top" align="center" valign="top">
                <a href="<?php echo esc_attr($theme_options['theme_twitter']) ?>"><img src="<?php echo $social_icon_url ?>/twitter.png"><br>Twitter</a>
            </td>
        <?php } ?>

        <?php if (!empty($theme_options['theme_pinterest'])) { ?>
            <td style="text-align: center; vertical-align: top" align="center" valign="top">
                <a href="<?php echo esc_attr($theme_options['theme_pinterest']) ?>"><img src="<?php echo $social_icon_url ?>/pinterest.png"><br>Pinterest</a>
            </td>
        <?php } ?>

        <?php if (!empty($theme_options['theme_linkedin'])) { ?>
            <td style="text-align: center; vertical-align: top" align="center" valign="top">
                <a href="<?php echo esc_attr($theme_options['theme_linkedin']) ?>"><img src="<?php echo $social_icon_url ?>/linkedin.png"><br>LinkedIn</a>
            </td>
        <?php } ?>

        <?php if (!empty($theme_options['theme_tumblr'])) { ?>
            <td style="text-align: center; vertical-align: top" align="center" valign="top">
                <a href="<?php echo esc_attr($theme_options['theme_tumblr']) ?>"><img src="<?php echo $social_icon_url ?>/tumblr.png"><br>Tumblr</a>
            </td>
        <?php } ?>

        <?php if (!empty($theme_options['theme_youtube'])) { ?>
            <td style="text-align: center; vertical-align: top" align="center" valign="top">
                <a href="<?php echo esc_attr($theme_options['theme_youtube']) ?>"><img src="<?php echo $social_icon_url ?>/youtube.png"><br>Youtube</a>
            </td>
        <?php } ?>

        <?php if (!empty($theme_options['theme_soundcloud'])) { ?>
            <td style="text-align: center; vertical-align: top" align="center" valign="top">
                <a href="<?php echo esc_attr($theme_options['theme_soundcloud']) ?>"><img src="<?php echo $social_icon_url ?>/soundcloud.png"><br>SoundCloud</a>
            </td>
        <?php } ?>
        <?php if (!empty($theme_options['theme_instagram'])) { ?>
            <td style="text-align: center; vertical-align: top" align="center" valign="top">
                <a href="<?php echo esc_attr($theme_options['theme_instagram']) ?>"><img src="<?php echo $social_icon_url ?>/instagram.png"><br>Instagram</a>
            </td>
        <?php } ?>    
            <?php if (!empty($theme_options['theme_vimeo'])) { ?>
            <td style="text-align: center; vertical-align: top" align="center" valign="top">
                <a href="<?php echo esc_attr($theme_options['theme_vimeo']) ?>"><img src="<?php echo $social_icon_url ?>/vimeo.png"><br>Vimeo</a>
            </td>
        <?php } ?>    
    </tr>
</table>