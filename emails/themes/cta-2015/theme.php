<?php
/*
 * Name: CTA 2015
 * Type: standard
 * Description: Single call to action marketing template
 */

if (!defined('ABSPATH')) exit;

$color = '#87aa14';
if (!empty($theme_options['theme_color'])) {
    $color = $theme_options['theme_color'];
}

$header = '';
$header_color = '#000000';
if (!empty($theme_options['main_header_logo']['url'])) {
    $header = '<img src="' . $theme_options['main_header_logo']['url'] . '" style="max-width: 500px">';
} else {
    if (!empty($theme_options['main_header_title'])) {
        $header = '<h2 style="color: ' . $header_color . '">' . $theme_options['main_header_title'] . '</h2>';
    } else {
        $header = '<h2 style="color: ' . $header_color . '">' . get_option('blogname') . '</h2>';
    }
    if (!empty($theme_options['main_header_sub'])) {
        $header .= '<p style="color: #666666">' . $theme_options['main_header_sub'] . '</p>';
    }
}
$font_family = 'Verdana';
//$cta_bgcolor = '#008800';
//if (!empty($theme_options['theme_cta_bgcolor'])) {
//    $cta_bgcolor = $theme_options['theme_cta_bgcolor'];
//}
$social_icon_url = plugins_url('e-newsletter') . '/emails/themes/cta-2015/images';
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title></title>
    </head>
    <body bgcolor="#ffffff">
        <table width="500" cellpadding="0" cellspacing="0" align="center" bgcolor="#ffffff" border="0">
            <tr>
                <td align="center" style="font-family: Verdana">
                    <table cellpadding="10" cellspacing="0" border="0" width="100%">
                        <tr>
                            <td style="font-size: 12px" align="center">
                                <a href="{email_url}" style="text-decoration: none">View online</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!-- header -->
            <tr>
                <td align="center" style="font-family: Verdana">
                    <?php echo $header; ?>   
                </td>
            </tr>

            <!-- body -->
            <tr>
                <td style="font-family: Verdana">

                    <!-- main text -->
                    <table cellpadding="15" cellspacing="0" align="center" border="0" width="100%">
                        <tr>
                            <td style="font-size: 15px" align="center">
                                <h1>Super catching title</h1>
                                <p>
                                    Here you should introduce your incredible offer. Remember the golden rule: write
                                    something useful for your readers, not for yourself.
                                </p>
                                <p>
                                    If a reader ask the question: what's here for me, the content is answering?
                                </p>
                            </td>
                        </tr>
                    </table>

                    <!-- cta -->
                    <table cellpadding="15" cellspacing="0" align="center" bgcolor="<?php echo $color ?>" border="0" style="border-radius: 5px">
                        <tr>
                            <td><a href="#" style="font-size: 15px; color: #ffffff; text-decoration: none">Call to action</a></td>
                        </tr>
                    </table>

                    <!-- spacer -->
                    <table cellpadding="0" cellspacing="0" align="center" bgcolor="#ffffff" border="0" width="100%">
                        <tr>
                            <td>
                                &nbsp;
                            </td>
                        </tr>
                    </table>

                    <?php include WP_PLUGIN_DIR . '/newsletter/emails/themes/default/footer.php'; ?>

                    <!-- spacer -->
                    <table cellpadding="0" cellspacing="0" align="center" bgcolor="#ffffff" border="0" width="100%">
                        <tr>
                            <td>
                                &nbsp;
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>
        </table>
    </body>
</html>