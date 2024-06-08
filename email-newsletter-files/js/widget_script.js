jQuery(document).ready(function() {
    // Submit-Event für das Formular abfangen
    jQuery("#subscribes_form").on("submit", function(event) {
        event.preventDefault(); // Standardverhalten des Formulars deaktivieren
    });

    // Click-Event für den Button abfangen
    jQuery("#subscribes_form .enewletter_widget_submit").on("click", function(event) {
        var stop = 0;

        event.preventDefault(); // Standardverhalten des Buttons deaktivieren

        var parent = jQuery(this).closest('.e-newsletter-widget');

        parent.find("#newsletter_action").val(jQuery(this).attr('id'));

        parent.find("#message").text(email_newsletter_widget_scripts.saving).slideDown();

        if (jQuery(this).attr('id') == "new_subscribe") {
            if ("" == parent.find("#e_newsletter_email").val()) {
                // Fehlermeldung hinzufügen
                parent.find("#message").text(email_newsletter_widget_scripts.empty_email).slideDown();
                stop = 1;
            }
        }

        if (stop == 0) {
            var e_newsletter_groups_id = new Array(); // Daten für pdata-Filter vorbereiten
            jQuery.each(parent.find('input[name="e_newsletter_groups_id[]"]'), function() {
                if (jQuery(this).is(':checked') || jQuery(this).attr('type') == 'hidden')
                    e_newsletter_groups_id.push(jQuery(this).val());
            });

            var e_newsletter_auto_groups_id = new Array(); // Daten für pdata-Filter vorbereiten
            jQuery.each(parent.find('input[name="e_newsletter_auto_groups_id[]"]'), function() {
                e_newsletter_auto_groups_id.push(jQuery(this).val());
            });

            var e_newsletter_add_groups_id = new Array(); // Daten für pdata-Filter vorbereiten
            jQuery.each(parent.find('input[name="e_newsletter_add_groups_id[]"]'), function() {
                e_newsletter_add_groups_id.push(jQuery(this).val());
            });

            var e_newsletter_remove_groups_id = new Array(); // Daten für pdata-Filter vorbereiten
            jQuery.each(parent.find('input[name="e_newsletter_remove_groups_id[]"]'), function() {
                e_newsletter_remove_groups_id.push(jQuery(this).val());
            });

            var data = { // Variablen für den Export suchen und setzen
                action: 'manage_subscriptions_ajax',
                newsletter_action: parent.find("#newsletter_action").val(),
                unsubscribe_code: parent.find("#unsubscribe_code").val(),
                e_newsletter_email: parent.find("#e_newsletter_email").val(),
                e_newsletter_name: parent.find("#e_newsletter_name").val(),
                newsletter_action: parent.find("#newsletter_action").val(),
                e_newsletter_groups_id: e_newsletter_groups_id,
                e_newsletter_auto_groups_id: e_newsletter_auto_groups_id,
                e_newsletter_add_groups_id: e_newsletter_add_groups_id,
                e_newsletter_remove_groups_id: e_newsletter_remove_groups_id
            };

            jQuery.post(email_newsletter_widget_scripts.ajax_url, data, function(data) { // Daten an die spezifische Aktion übergeben
                data = JSON.parse(data);

                if (typeof data.redirect !== 'undefined' && data.redirect)
                    window.location = data.redirect;
                else {
                    parent.find("#message").slideUp('fast', function() {
                        jQuery(this).text(data.message).slideDown('fast');
                    });

                    if (typeof data.subscribe_groups !== "undefined") {
                        jQuery.each(data.subscribe_groups, function(index, value) {
                            parent.find('.e_newsletter_groups_id_' + value).prop("checked", true);
                        });
                    }
                    if (typeof data.unsubscribe_code !== "undefined") {
                        parent.find("#unsubscribe_code").val(data.unsubscribe_code);
                    }
                    parent.find('#' + data.view).slideDown('fast');
                    parent.find('#' + data.hide).slideUp('fast');
                }
            });
        }
    });
});