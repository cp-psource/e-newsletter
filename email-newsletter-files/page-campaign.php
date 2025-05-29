<div class="wrap">
    <h1><?php _e('Kampagnen (ALPHA)', 'email-newsletter'); ?></h1>
    <p><?php _e('Hier kannst du automatisierte Newsletter-Kampagnen anlegen und verwalten.', 'email-newsletter'); ?></p>
    <p><?php _e('Diese Funktion befindet sich in einer frühen Entwicklungsphase, bitte melde Probleme und Feature-Wünsche auf <a href="https://github.com/cp-psource/e-newsletter">GITHUB</a>.', 'email-newsletter'); ?></p>

    <a href="<?php echo admin_url('admin.php?page=newsletters-campaigns&action=new'); ?>" class="button button-primary">
        <?php _e('Neue Kampagne anlegen', 'email-newsletter'); ?>
    </a>

    <hr>

    <?php
    // Hier später: Übersicht aller Kampagnen ausgeben
    // Beispiel: $campaigns = $this->get_campaigns();
    // foreach($campaigns as $campaign) { ... }
    ?>
</div>
<?php
if (isset($_GET['action']) && $_GET['action'] === 'new') {
    // Beispiel: Gruppen und Newsletter laden
    $groups = method_exists($this, 'get_groups') ? $this->get_groups() : [];
    $newsletters = method_exists($this, 'get_newsletters') ? $this->get_newsletters() : [];
    ?>
    <div class="wrap">
        <h2><?php _e('Neue Kampagne anlegen', 'email-newsletter'); ?></h2>
        <form method="post">
            <table class="form-table">
                <tr>
                    <th><label for="campaign_name"><?php _e('Kampagnenname', 'email-newsletter'); ?></label></th>
                    <td><input type="text" name="campaign_name" id="campaign_name" required></td>
                </tr>
                <tr>
                    <th><label for="group_id"><?php _e('Zielgruppe', 'email-newsletter'); ?></label></th>
                    <td>
                        <select name="group_id" id="group_id" required>
                            <option value=""><?php _e('Bitte wählen', 'email-newsletter'); ?></option>
                            <?php foreach($groups as $group): ?>
                                <option value="<?php echo esc_attr($group['group_id']); ?>"><?php echo esc_html($group['group_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
            </table>
            <h3><?php _e('Schritte (Newsletter)', 'email-newsletter'); ?></h3>
            <div id="campaign-steps">
                <div class="campaign-step">
                    <select name="newsletter_id[]" required>
                        <option value=""><?php _e('Newsletter wählen', 'email-newsletter'); ?></option>
                        <?php foreach($newsletters as $nl): ?>
                            <option value="<?php echo esc_attr($nl['newsletter_id']); ?>"><?php echo esc_html($nl['subject']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="number" name="delay_days[]" min="0" value="0" style="width:60px;">
                    <span>
                        <?php _e('Tage nach vorherigem Versand', 'email-newsletter'); ?>
                        <br>
                        <small>
                            <?php _e('Beim ersten Schritt bezieht sich die Zeit auf den Kampagnenstart (z.B. Anmeldung des Nutzers).', 'email-newsletter'); ?>
                        </small>
                    </span>
                    <button type="button" class="button remove-step" onclick="removeStep(this)"><?php _e('Entfernen', 'email-newsletter'); ?></button>
                </div>
            </div>
            <button type="button" class="button" onclick="addStep()"><?php _e('Weiteren Schritt hinzufügen', 'email-newsletter'); ?></button>
            <br><br>
            <input type="submit" class="button button-primary" value="<?php _e('Kampagne speichern', 'email-newsletter'); ?>">
        </form>
        <script>
        function addStep() {
            var step = document.querySelector('.campaign-step').cloneNode(true);
            document.getElementById('campaign-steps').appendChild(step);
        }
        function removeStep(btn) {
            var steps = document.querySelectorAll('.campaign-step');
            if (steps.length > 1) {
                btn.parentNode.remove();
            }
        }
        </script>
    </div>
    <?php
    return;
}
?>