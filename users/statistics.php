<?php
/* @var $this NewsletterUsersAdmin */
/* @var $controls NewsletterControls */

defined('ABSPATH') || exit;

wp_enqueue_script('tnp-chart');

$all_count = $wpdb->get_var("select count(*) from " . NEWSLETTER_USERS_TABLE);

$referres = $wpdb->get_results("select referrer, count(*) as total, SUM(if(status='C', 1, 0)) as confirmed, SUM(if(status='S', 1, 0)) as unconfirmed, SUM(if(status='B', 1, 0)) as bounced, SUM(if(status='U', 1, 0)) as unsubscribed, SUM(if(status='P', 1, 0)) as complained from " . NEWSLETTER_USERS_TABLE . " group by referrer order by confirmed desc");
?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load("current", {packages: ['corechart', 'geochart', 'geomap']});
</script>

<div class="wrap" id="tnp-wrap">

    <?php include NEWSLETTER_ADMIN_HEADER; ?>

    <div id="tnp-heading">
        <?php $controls->title_help('/subscribers-and-management/') ?>
<!--        <h2><?php esc_html_e('Subscribers', 'newsletter') ?></h2>-->
        <?php include __DIR__ . '/nav.php' ?>
    </div>

    <div id="tnp-body" class="tnp-users-statistics">

        <?php $controls->show(); ?>

        <?php $controls->init(); ?>

        <div class="psource-tabs" id="tabs">
            <div class="psource-tabs-nav">
                <button class="psource-tab active" data-tab="tabs-overview"><?php esc_html_e('By Status', 'newsletter'); ?></button>
                <button class="psource-tab" data-tab="tabs-lists"><?php esc_html_e('By Lists', 'newsletter'); ?></button>
                <button class="psource-tab" data-tab="tabs-language"><?php esc_html_e('By Language', 'newsletter'); ?></button>
                <button class="psource-tab" data-tab="tabs-countries"><?php esc_html_e('By location', 'newsletter'); ?></button>
                <button class="psource-tab" data-tab="tabs-referrers"><?php esc_html_e('By Referrer', 'newsletter'); ?></button>
                <button class="psource-tab" data-tab="tabs-sources"><?php esc_html_e('By URL', 'newsletter'); ?></button>
                <button class="psource-tab" data-tab="tabs-gender"><?php esc_html_e('By Gender', 'newsletter'); ?></button>
                <button class="psource-tab" data-tab="tabs-time"><?php esc_html_e('By Time', 'newsletter'); ?></button>
            </div>
            <div class="psource-tabs-content">
                <div class="psource-tab-panel active" id="tabs-overview">
                <?php
                $list = $wpdb->get_row("select count(*) as total, SUM(if(status='C', 1, 0)) as confirmed, SUM(if(status='S', 1, 0)) as unconfirmed, SUM(if(status='B', 1, 0)) as bounced, SUM(if(status='U', 1, 0)) as unsubscribed, SUM(if(status='P', 1, 0)) as complained from " . NEWSLETTER_USERS_TABLE);
                ?>

                <div class="row">
                    <div class="col-md-6">
                        <table class="widefat" style="width: 250px">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e('Status', 'newsletter') ?></th>
                                    <th><?php esc_html_e('Total', 'newsletter') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php esc_html_e('Any', 'newsletter') ?></td>
                                    <td>
                                        <?php echo (int)$list->total; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php esc_html_e('Confirmed', 'newsletter') ?></td>
                                    <td>
                                        <?php echo (int)$list->confirmed; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php esc_html_e('Not confirmed', 'newsletter') ?></td>
                                    <td>
                                        <?php echo (int)$list->unconfirmed; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php esc_html_e('Unsubscribed', 'newsletter') ?></td>
                                    <td>
                                        <?php echo (int)$list->unsubscribed; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php esc_html_e('Bounced', 'newsletter') ?></td>
                                    <td>
                                        <?php echo (int)$list->bounced; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php esc_html_e('Complained', 'newsletter') ?></td>
                                    <td>
                                        <?php echo (int)$list->complained; ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <div style="height: 250px;">
                            <canvas id="tnp-users-chart-status"></canvas>
                        </div>
                        <script>
                            const dataStatus = {
                                labels: ['Confirmed', 'Unconfirmed', 'Unsubscribed', 'Bounced', 'Complained'],
                                datasets: [{
                                        label: 'Status',
                                        backgroundColor: ["#0074D9", "#FF4136", "#2ECC40", "#FF851B", "#7FDBFF", "#B10DC9", "#FFDC00", "#001f3f", "#39CCCC", "#01FF70", "#85144b", "#F012BE", "#3D9970", "#111111", "#AAAAAA"],
                                        data: <?php echo json_encode([(int) $list->confirmed, (int) $list->unconfirmed, (int) $list->unsubscribed, (int) $list->bounced, (int) $list->complained]) ?>,
                                    }]
                            };

                            jQuery(function () {
                                const myChartx = new Chart(
                                        document.getElementById('tnp-users-chart-status'),
                                        {
                                            type: 'doughnut',
                                            data: dataStatus,
                                            options: {
                                                maintainAspectRatio: false,
                                                legend: {
                                                    position: 'right'
                                                }

                                            }
                                        });
                            });
                        </script>
                    </div>
                </div>

            </div>


            <div class="psource-tab-panel" id="tabs-lists">

                <table class="widefat" style="width: auto">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th><?php esc_html_e('List', 'newsletter') ?></th>
                            <th style="text-align: right"><?php esc_html_e('Total', 'newsletter') ?></th>
                            <th style="text-align: right"><?php $this->echo_user_status_label('C') ?></th>
                            <th style="text-align: right"><?php $this->echo_user_status_label('S') ?></th>
                            <th style="text-align: right"><?php $this->echo_user_status_label('U') ?></th>
                            <th style="text-align: right"><?php $this->echo_user_status_label('B') ?></th>
                            <th style="text-align: right"><?php $this->echo_user_status_label('P') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $lists = $this->get_lists(); ?>
                        <?php foreach ($lists as $list) { ?>
                            <?php
                            $row = $wpdb->get_row("select count(*) as total, SUM(if(status='C', 1, 0)) as confirmed, SUM(if(status='S', 1, 0)) as unconfirmed, SUM(if(status='B', 1, 0)) as bounced, SUM(if(status='U', 1, 0)) as unsubscribed, SUM(if(status='P', 1, 0)) as complained from " . NEWSLETTER_USERS_TABLE . " where list_" . $list->id . "=1");
                            ?>
                            <tr>
                                <td><?php echo esc_html($list->id) ?></td>
                                <td><?php echo esc_html($list->name) ?></td>

                                <td style="text-align: right"><?php echo (int) $row->total; ?></td>
                                <td style="text-align: right"><?php echo (int) $row->confirmed; ?></td>
                                <td style="text-align: right"><?php echo (int) $row->unconfirmed; ?></td>
                                <td style="text-align: right"><?php echo (int) $row->unsubscribed; ?></td>
                                <td style="text-align: right"><?php echo (int) $row->bounced; ?></td>
                                <td style="text-align: right"><?php echo (int) $row->complained; ?></td>
                            </tr>


                        <?php } ?>

                        <?php
                        $where = ' 1=1';

                        for ($i = 1; $i <= NEWSLETTER_LIST_MAX; $i++) {
                            $where .= ' and list_' . $i . '=0';
                        }
                        $row = $wpdb->get_row("select count(*) as total, SUM(if(status='C', 1, 0)) as confirmed, SUM(if(status='S', 1, 0)) as unconfirmed, SUM(if(status='B', 1, 0)) as bounced, SUM(if(status='U', 1, 0)) as unsubscribed, SUM(if(status='P', 1, 0)) as complained from " . NEWSLETTER_USERS_TABLE . " where " . $where);
                        ?>
                        <tr>
                            <td>&nbsp;</td>
                            <td style="font-style: italic"><?php esc_html_e('None', 'newsletter') ?></td>
                            <td style="text-align: right"><?php echo (int)$row->total; ?></td>
                            <td style="text-align: right"><?php echo (int)$row->confirmed; ?></td>
                            <td style="text-align: right"><?php echo (int)$row->unconfirmed; ?></td>
                            <td style="text-align: right"><?php echo (int)$row->unsubscribed; ?></td>
                            <td style="text-align: right"><?php echo (int)$row->bounced; ?></td>
                            <td style="text-align: right"><?php echo (int)$row->complained; ?></td>
                        </tr>
                    </tbody>
                </table>

            </div>

            <div class="psource-tab-panel" id="tabs-language">
                <?php if ($this->is_multilanguage()) { ?>
                    <?php $languages = $this->get_languages(); ?>

                    <table class="widefat" style="width: auto">
                        <thead>
                            <tr>
                                <th><?php esc_html_e('Status', 'newsletter') ?></th>
                                <th><?php esc_html_e('Total', 'newsletter') ?></th>
                            </tr>
                        <tbody>
                            <?php foreach ($languages as $code => $label) { ?>
                                <tr>
                                    <td><?php echo esc_html($label) ?></td>
                                    <td>
                                        <?php echo (int)$wpdb->get_var($wpdb->prepare("select count(*) from " . NEWSLETTER_USERS_TABLE . " where language=%s", $code)); ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td><?php esc_html_e('Without language', 'newsletter') ?></td>
                                <td>
                                    <?php echo (int)$wpdb->get_var("select count(*) from " . NEWSLETTER_USERS_TABLE . " where language=''"); ?>
                                </td>
                            </tr>
                            </thead>
                        </tbody>
                    </table>
                <?php } else { ?>
                    <p>
                        This panel is active when a <a href="https://www.thenewsletterplugin.com/documentation/newsletters/multilanguage/" target="_blank">supported multilanguage plugin</a> is installed.
                    </p>
                <?php } ?>

            </div>

            <div class="psource-tab-panel" id="tabs-countries">
                <?php
                if (!has_action('newsletter_users_statistics_countries')) {
                    include __DIR__ . '/statistics-countries.php';
                } else {
                    do_action('newsletter_users_statistics_countries', $controls);
                }
                ?>
            </div>


            <div class="psource-tab-panel" id="tabs-referrers">
                <table class="widefat" style="width: auto">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Referrer', 'newsletter') ?></th>
                            <th style="text-align: right"><?php esc_html_e('Total', 'newsletter') ?></th>
                            <th style="text-align: right"><?php $this->echo_user_status_label('C') ?></th>
                            <th style="text-align: right"><?php $this->echo_user_status_label('S') ?></th>
                            <th style="text-align: right"><?php $this->echo_user_status_label('U') ?></th>
                            <th style="text-align: right"><?php $this->echo_user_status_label('B') ?></th>
                            <th style="text-align: right"><?php $this->echo_user_status_label('P') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($referres as $row) { ?>
                            <tr>
                                <td><?php echo empty($row->referrer) ? '[not set]' : esc_html($row->referrer) ?></td>
                                <td style="text-align: right"><?php echo (int)$row->total; ?></td>
                                <td style="text-align: right"><?php echo (int)$row->confirmed; ?></td>
                                <td style="text-align: right"><?php echo (int)$row->unconfirmed; ?></td>
                                <td style="text-align: right"><?php echo (int)$row->unsubscribed; ?></td>
                                <td style="text-align: right"><?php echo (int)$row->bounced; ?></td>
                                <td style="text-align: right"><?php echo (int)$row->complained; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

            </div>


            <div class="psource-tab-panel" id="tabs-sources">

                <?php
                $list = $wpdb->get_results("select http_referer, count(*) as total, SUM(if(status='C', 1, 0)) as confirmed, SUM(if(status='S', 1, 0)) as unconfirmed, SUM(if(status='B', 1, 0)) as bounced, SUM(if(status='U', 1, 0)) as unsubscribed, SUM(if(status='P', 1, 0)) as complained from " . NEWSLETTER_USERS_TABLE . " group by http_referer order by count(*) desc limit 100");
                ?>
                <table class="widefat" style="width: auto">
                    <thead>
                        <tr>
                            <th>URL</th>
                            <th style="text-align: right"><?php esc_html_e('Total', 'newsletter') ?></th>
                            <th style="text-align: right"><?php $this->echo_user_status_label('C') ?></th>
                            <th style="text-align: right"><?php $this->echo_user_status_label('S') ?></th>
                            <th style="text-align: right"><?php $this->echo_user_status_label('U') ?></th>
                            <th style="text-align: right"><?php $this->echo_user_status_label('B') ?></th>
                            <th style="text-align: right"><?php $this->echo_user_status_label('P') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($list as $row) { ?>
                            <tr>
                                <td><?php echo esc_html(empty($row->http_referer) ? '[not set]' : $controls->print_truncated($row->http_referer, 120)); ?></td>
                                <td style="text-align: right"><?php echo (int)$row->total; ?></td>
                                <td style="text-align: right"><?php echo (int)$row->confirmed; ?></td>
                                <td style="text-align: right"><?php echo (int)$row->unconfirmed; ?></td>
                                <td style="text-align: right"><?php echo (int)$row->unsubscribed; ?></td>
                                <td style="text-align: right"><?php echo (int)$row->bounced; ?></td>
                                <td style="text-align: right"><?php echo (int)$row->complained; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

            </div>


            <div class="psource-tab-panel" id="tabs-gender">
                <?php
                $male_count = $wpdb->get_row("select SUM(if(status='C', 1, 0)) as confirmed, SUM(if(status='S', 1, 0)) as unconfirmed, SUM(if(status='B', 1, 0)) as bounced, SUM(if(status='U', 1, 0)) as unsubscribed, SUM(if(status='P', 1, 0)) as complained from " . NEWSLETTER_USERS_TABLE . " where sex='m'");
                $female_count = $wpdb->get_row("select SUM(if(status='C', 1, 0)) as confirmed, SUM(if(status='S', 1, 0)) as unconfirmed, SUM(if(status='B', 1, 0)) as bounced, SUM(if(status='U', 1, 0)) as unsubscribed, SUM(if(status='P', 1, 0)) as complained from " . NEWSLETTER_USERS_TABLE . " where sex='f'");
                $none_count = $wpdb->get_row("select SUM(if(status='C', 1, 0)) as confirmed, SUM(if(status='S', 1, 0)) as unconfirmed, SUM(if(status='B', 1, 0)) as bounced, SUM(if(status='U', 1, 0)) as unsubscribed, SUM(if(status='P', 1, 0)) as complained from " . NEWSLETTER_USERS_TABLE . " where sex='n'");
                ?>

                <table class="widefat" style="width: auto">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Gender', 'newsletter') ?></th>
                            <th style="text-align: right"><?php $this->echo_user_status_label('C') ?></th>
                            <th style="text-align: right"><?php $this->echo_user_status_label('S') ?></th>
                            <th style="text-align: right"><?php $this->echo_user_status_label('U') ?></th>
                            <th style="text-align: right"><?php $this->echo_user_status_label('B') ?></th>
                            <th style="text-align: right"><?php $this->echo_user_status_label('P') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php esc_html_e('Female', 'newsletter') ?></td>
                            <td style="text-align: right"><?php echo (int)$female_count->confirmed; ?></td>
                            <td style="text-align: right"><?php echo (int)$female_count->unconfirmed; ?></td>
                            <td style="text-align: right"><?php echo (int)$female_count->unsubscribed; ?></td>
                            <td style="text-align: right"><?php echo (int)$female_count->bounced; ?></td>
                            <td style="text-align: right"><?php echo (int)$female_count->complained; ?></td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e('Male', 'newsletter') ?></td>
                            <td style="text-align: right"><?php echo (int)$male_count->confirmed; ?></td>
                            <td style="text-align: right"><?php echo (int)$male_count->unconfirmed; ?></td>
                            <td style="text-align: right"><?php echo (int)$male_count->unsubscribed; ?></td>
                            <td style="text-align: right"><?php echo (int)$male_count->bounced; ?></td>
                            <td style="text-align: right"><?php echo (int)$male_count->complained; ?></td>
                        </tr>
                        <tr>
                            <td><?php esc_html_e('Not specified', 'newsletter') ?></td>
                            <td style="text-align: right"><?php echo (int)$none_count->confirmed; ?></td>
                            <td style="text-align: right"><?php echo (int)$none_count->unconfirmed; ?></td>
                            <td style="text-align: right"><?php echo (int)$none_count->unsubscribed; ?></td>
                            <td style="text-align: right"><?php echo (int)$none_count->bounced; ?></td>
                            <td style="text-align: right"><?php echo (int)$none_count->complained; ?></td>
                        </tr>
                    </tbody>
                </table>


            </div>


            <div class="psource-tab-panel" id="tabs-time">

                <?php
                if (!has_action('newsletter_users_statistics_time')) {
                    include __DIR__ . '/statistics-time.php';
                } else {
                    do_action('newsletter_users_statistics_time', $controls);
                }
                ?>

            </div>

            <?php
            if (isset($panels['user_statistics'])) {
                foreach ($panels['user_statistics'] as $panel) {
                    call_user_func($panel['callback'], $id, $controls);
                }
            }
            ?>
        </div>

    </div>

</div>



