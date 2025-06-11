<?php
/* @var $this NewsletterArchive */
require_once NEWSLETTER_INCLUDES_DIR . '/controls.php';
$controls = new NewsletterControls();

if (!$controls->is_action()) {
    $controls->data = $this->options;
} else {
    if ($controls->is_action('save')) {
        $this->save_options($controls->data);
        $controls->add_toast_saved();
    }
}
?>

<div class="wrap" id="tnp-wrap">
    <?php include NEWSLETTER_ADMIN_HEADER; ?>
    <div id="tnp-heading">
        <h2>Newsletter Archive</h2>
    </div>

    <div id="tnp-body">
        <?php $controls->show(); ?>

        <p>
            <strong>Newsletter-Archiv einbinden</strong><br>
            Füge den folgenden Shortcode auf einer beliebigen WordPress-Seite ein, um ein Archiv aller gesendeten Newsletter anzuzeigen:
        </p>
        <div style="margin-bottom:10px;">
            <input type="text" value="[newsletter_archive]" id="archive-shortcode" readonly style="width:220px; font-family:monospace;">
            <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('archive-shortcode').value)">Kopieren</button>
        </div>

        <p>
            <strong>Beispiel für einen Einführungstext:</strong><br>
            <code>
                [newsletter_archive]<br>
                Willkommen in unserem Newsletter-Archiv!<br>
                [/newsletter_archive]
            </code>
            <br>
            Der Einführungstext wird nur in der Listenansicht angezeigt.
        </p>

        <p>
            <strong>Shortcode-Attribute:</strong><br>
            <ul>
                <li><code>max</code> – Maximale Anzahl der angezeigten Newsletter (z.B. <code>[newsletter_archive max="10" /]</code>)</li>
                <li><code>list</code> – Nur Newsletter einer bestimmten Liste anzeigen (z.B. <code>[newsletter_archive list="1" /]</code>)</li>
                <li><code>type</code> – Nur Newsletter eines bestimmten Typs anzeigen, z.B. für Automated Addon: <code>[newsletter_archive type="automated_1"]</code></li>
                <li><code>show_date</code> – Datum anzeigen (<code>true</code> oder <code>false</code>)</li>
                <li><code>separator</code> – Trennzeichen zwischen Datum und Titel (z.B. <code>[newsletter_archive separator=" | " /]</code>)</li>
                <li><code>title</code> – Überschrift für die Liste als H2</li>
            </ul>
        </p>

        <p>
            <strong>Hinweis:</strong><br>
            Bei Problemen mit der eingebetteten Ansicht (z.B. durch Page Builder oder Theme) kannst du in den Addon-Einstellungen einstellen, dass Newsletter in einem neuen Tab geöffnet werden.
        </p>

        <form action="" method="post">
            <?php $controls->init(); ?>

            <table class="form-table">
                <tr valign="top">
                    <th>Show newsletter date?</th>
                    <td>
                        <?php $controls->checkbox('date'); ?>
                    </td>
                </tr>
                <tr valign="top">
                    <th>Showing the newsletter</th>
                    <td>
                        <?php $controls->select('show', ['' => 'Embedded the same page', 'blank' => 'In a new browser page', 'self' => 'In the same browser page']); ?>
                        <p class="description">
                            Some page biulder or some page filters do not allow to show the newsletter in the same page: use an alternative option.
                        </p>
                    </td>
                </tr>
            </table>

            <p>
                <?php $controls->button_save(); ?>
            </p>
        </form>
    </div>
    
</div>