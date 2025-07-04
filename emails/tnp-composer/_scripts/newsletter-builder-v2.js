// add delete buttons
jQuery.fn.add_delete = function () {
    this.append(
        '<span class="psource-tooltip tnpc-row-delete" tabindex="0">' +
            '<img src="' + TNP_PLUGIN_URL + '/emails/tnp-composer/_assets/delete.png" width="32">' +
            '<span class="psource-tooltip-text">Delete</span>' +
        '</span>'
    );
    this.find('.tnpc-row-delete').perform_delete();
};

// delete row
jQuery.fn.perform_delete = function () {
    this.click(function () {
        tnpc_hide_block_options();
        // remove block
        jQuery(this).parent().remove();
    });
}

// add edit button
jQuery.fn.add_block_edit = function () {
    //console.log('Add block edit');
    this.append(
        '<span class="psource-tooltip tnpc-row-edit-block" tabindex="0">' +
            '<img src="' + TNP_PLUGIN_URL + '/emails/tnp-composer/_assets/edit.png" width="32">' +
            '<span class="psource-tooltip-text">Edit</span>' +
        '</span>'
    );
    this.find('.tnpc-row-edit-block').perform_block_edit();
}

// edit block
jQuery.fn.perform_block_edit = function () {

    //console.log('Perform block edit');

    jQuery(".tnpc-row-edit-block").on("click", function (e) {
        e.preventDefault()
    });

    this.on("click", function (e) {

        e.preventDefault();

        target = jQuery(this).parent().find('.edit-block');

        // The row container which is a global variable and used later after the options save
        tnp_container = jQuery(this).closest("table");

        if (tnp_container.hasClass('tnpc-row-block')) {

            tnpc_show_block_options();

            var options = tnp_container.find(".tnpc-block-content").attr("data-json");

            // Compatibility
            if (!options) {
                options = target.attr("data-options");
            }

            var data = {
                action: "tnpc_options",
                id: tnp_container.data("id"),
                context_type: tnp_context_type,
                options: options
            };

            tnpc_add_global_options(data);

            builderAreaHelper.lock();
            jQuery("#tnpc-block-options-form").load(ajaxurl, data, function () {
                start_options = jQuery("#tnpc-block-options-form").serializeArray();
                tnpc_add_global_options(start_options);
                builderAreaHelper.unlock();
            });

        } else {
            alert("This is deprecated block version and cannot be edited. Please replace it with a new one.");
        }

    });

};

// add clone button
jQuery.fn.add_block_clone = function () {
    this.append(
        '<span class="psource-tooltip tnpc-row-clone" tabindex="0">' +
            '<img src="' + TNP_PLUGIN_URL + '/emails/tnp-composer/_assets/copy.png" width="32">' +
            '<span class="psource-tooltip-text">Clone</span>' +
        '</span>'
    );
    this.find('.tnpc-row-clone').perform_clone();
}

// clone block
jQuery.fn.perform_clone = function () {

    jQuery(".tnpc-row-clone").on("click", function (e) {
        e.preventDefault();
    });

    this.on("click", function (e) {

        e.preventDefault();

        // hide block edit form
        tnpc_hide_block_options();

        // find the row
        let row = jQuery(this).closest('.tnpc-row');

        // clone the block
        let new_row = row.clone();
        new_row.find(".tnpc-row-delete").remove();
        new_row.find(".tnpc-row-edit-block").remove();
        new_row.find(".tnpc-row-clone").remove();

        new_row.add_delete();
        new_row.add_block_edit();
        new_row.add_block_clone();
        // if (new_row.hasClass('tnpc-row-block')) {
        //     new_row.find(".tnpc-row-edit-block i").click();
        // }
        new_row.insertAfter(row);
    });
};

let start_options = null;
let tnp_container = null;

jQuery(function () {

    // open blocks tab
    document.getElementById("defaultOpen").click();

    // preload content from a body named input
    var preloadedContent = jQuery('input[name="message"]').val();
    if (!preloadedContent) {
        preloadedContent = jQuery('input[name="options[message]"]').val();
    }

    if (!preloadedContent && tnp_preset_show) {
        tnpc_show_presets_modal();
    } else {
        jQuery('#tnpb-content').html(preloadedContent);
        start_composer();
    }

    // subject management
    jQuery('#options-subject').val(jQuery('#tnpc-form input[name="options[subject]"]').val());

    // preheader management
    jQuery('#options-preheader').val(jQuery('#tnpc-form input[name="options[options_preheader]"]').val());

    // ======================== //
    // ==  BACKGROUND COLOR  == //
    // ======================== //
    _setBuilderAreaBackgroundColor(document.getElementById('options-options_composer_background').value);

    function _setBuilderAreaBackgroundColor(color) {
        jQuery('#tnpb-content').css('background-color', color);
    }

    window._setBuilderAreaBackgroundColor = _setBuilderAreaBackgroundColor; //BAD STUFF!!!

    // ======================== //
    // ==  BACKGROUND COLOR  == //
    // ======================== //

});

function BuilderAreaHelper() {

    var _builderAreaEl = document.querySelector('#tnpb-main');
    var _overlayEl = document.createElement('div');
    _overlayEl.style.zIndex = 99999;
    _overlayEl.style.position = 'absolute';
    _overlayEl.style.top = 0;
    _overlayEl.style.left = 0;
    _overlayEl.style.width = '100%';
    _overlayEl.style.height = '100%';

    this.lock = function () {
        _builderAreaEl.appendChild(_overlayEl);
    }

    this.unlock = function () {
        _builderAreaEl.removeChild(_overlayEl);
    }

}

let builderAreaHelper = new BuilderAreaHelper();

function init_builder_area() {

    //Drag & Drop
    jQuery("#tnpb-content").sortable({
        revert: false,
        placeholder: "tnpb-placeholder",
        forcePlaceholderSize: true,
        opacity: 0.6,
        tolerance: "pointer",
        helper: function (e) {
            var helper = jQuery(document.getElementById("tnpb-sortable-helper")).clone();
            return helper;
        },
        update: function (event, ui) {
            if (ui.item.attr("id") === "tnpb-draggable-helper") {
                loading_row = jQuery('<div style="text-align: center; padding: 20px; background-color: #d4d5d6; color: #52BE7F;"><i class="fa fa-cog fa-2x fa-spin" /></div>');
                ui.item.before(loading_row);
                ui.item.remove();
                var data = new Array(
                        {"name": 'action', "value": 'tnpc_render'},
                        {"name": 'id', "value": ui.item.data("id")},
                        {"name": 'b', "value": ui.item.data("id")},
                        {"name": 'full', "value": 1},
                        {"name": '_wpnonce', "value": tnp_nonce}
                );

                tnpc_add_global_options(data);

                jQuery.post(ajaxurl, data, function (response) {

                    var new_row = jQuery(response);
//                    ui.item.before(new_row);
//                    ui.item.remove();
                    loading_row.before(new_row);
                    loading_row.remove();
                    new_row.add_delete();
                    new_row.add_block_edit();
                    new_row.add_block_clone();
                    // new_row.find(".tnpc-row-edit").hover_edit();
                    if (new_row.hasClass('tnpc-row-block')) {
                        new_row.find(".tnpc-row-edit-block").click();
                    }
                }).fail(function () {
                    alert("Block rendering failed.");
                    loading_row.remove();
                }).always(function () {
                });
            }
        }
    });

    jQuery(".tnpb-block-icon").draggable({
        connectToSortable: "#tnpb-content",

        // Build the helper for dragging
        helper: function (e) {
            var helper = jQuery(document.getElementById("tnpb-draggable-helper")).clone();
            // Do not uset .data() with jQuery
            helper.attr("data-id", e.currentTarget.dataset.id);
            helper.html(e.currentTarget.dataset.name);
            return helper;
        },
        revert: false,
        start: function () {
            if (jQuery('.tnpc-row').length) {
            } else {
                jQuery('#tnpb-content').append('<div class="tnpc-drop-here">Drag&Drop blocks here!</div>');
            }
        },
        stop: function (event, ui) {
            jQuery('.tnpc-drop-here').remove();
        }
    });

    jQuery(".tnpc-row").add_delete();
    jQuery(".tnpc-row").add_block_edit();
    jQuery(".tnpc-row").add_block_clone();

}

function start_composer() {

    init_builder_area();

    // Closes the block options layer (without saving)
    jQuery("#tnpc-block-options-cancel").click(function () {

        tnpc_hide_block_options();

        var _target = target;

        jQuery.post(ajaxurl, start_options, function (response) {
            _target.html(response);
            jQuery("#tnpc-block-options-form").html("");
        });
    });

    // Fires the save event for block options
    jQuery("#tnpc-block-options-save").click(function (e) {
        e.preventDefault();

        var _target = target;

        // fix for Codemirror
        if (typeof templateEditor !== 'undefined') {
            templateEditor.save();
        }

        if (window.tinymce)
            window.tinymce.triggerSave();

        var data = jQuery("#tnpc-block-options-form").serializeArray();

        tnpc_add_global_options(data);

        tnpc_hide_block_options();

        jQuery.post(ajaxurl, data, function (response) {
            _target.html(response);

            jQuery("#tnpc-block-options-form").html("");
        });
    });

    jQuery('#tnpc-block-options-form').on('change', function (event) {
        var data = jQuery("#tnpc-block-options-form").serializeArray();

        var _container = tnp_container;
        var _target = target;

        tnpc_add_global_options(data);

        jQuery.post(ajaxurl, data, function (response) {
            _target.html(response);
            if (event.target.dataset.afterRendering === 'reload') {
                _container.find(".tnpc-row-edit-block").click();
            }
        }).fail(function () {
            alert("Block rendering failed");
        });

    });



}

function tnpc_show_block_options() {

    const animationDuration = 500;

    //jQuery("#tnpc-blocks").fadeOut(animationDuration);
    //jQuery("#tnpc-global-styles").fadeOut(animationDuration);
    //jQuery("#tnpc-mobile-tab").fadeOut(animationDuration);
    //jQuery("#tnpc-test-tab").fadeOut(animationDuration);

    jQuery("#tnpc-block-options").fadeIn(animationDuration);
    jQuery("#tnpc-block-options").css('display', 'flex');

}

function tnpc_hide_block_options() {

    const animationDuration = 500;

    jQuery("#tnpc-block-options").fadeOut(animationDuration);

    //var $activeTab = jQuery(".tnpc-tabs .tablinks.active");
    //jQuery('#' + $activeTab.data('tabId')).fadeIn(animationDuration);

    jQuery("#tnpc-block-options-form").html('');

}

function tnpc_save(form) {

    if (window.tinymce)
        window.tinymce.triggerSave();

    form.elements["options[message]"].value = tnpc_get_email_content_from_builder_area();

    // When the composer is not showing the subject field (for example in Automated)
    if (document.getElementById("options-preheader")) {
        form.elements["options[options_preheader]"].value = jQuery('#options-preheader').val();
    } else {
        form.elements["options[options_preheader]"].value = "";
    }
    if (document.getElementById("options-subject")) {
        form.elements["options[subject]"].value = jQuery('#options-subject-subject').val();
    } else {
        form.elements["options[subject]"].value = "";
    }

    var global_form = document.getElementById("tnpb-settings-form");
    //Copy "Global styles" form inputs into main form
    tnpc_copy_form(global_form, form);

}

function tnpc_get_email_content_from_builder_area() {

    var $elMessage = jQuery("#tnpb-content").clone();

    //console.log(document.getElementById('tnpb-content').innerHTML);

    $elMessage.find('.tnpc-row-delete').remove();
    $elMessage.find('.tnpc-row-edit-block').remove();
    $elMessage.find('.tnpc-row-clone').remove();
    $elMessage.find('.tnpc-row').removeClass('ui-draggable');
    $elMessage.find('#tnpb-sortable-helper').remove();

    return btoa(encodeURIComponent($elMessage.html()));

}

function tnpc_copy_form(source, dest) {
    for (var i = 0; i < source.elements.length; i++) {
        var field = document.createElement("input");
        field.type = "hidden";
        field.name = source.elements[i].name;
        field.value = source.elements[i].value;

        // Non clona le select!
        //var clonedEl = source.elements[i].cloneNode();
        //clonedEl.style.display = 'none';
        dest.appendChild(field);
    }
}

function tnpc_test() {
    let form = document.getElementById("tnpc-form");
    tnpc_save(form);
    form.act.value = "test";
    form.submit();
}

function tnpb_open_tab(evt, tabName) {
    evt.preventDefault();
    let items = document.getElementsByClassName("tnpb-tab");
    for (let i = 0; i < items.length; i++) {
        items[i].style.display = "none";
    }

    items = document.getElementsByClassName("tnpb-tab-button");
    for (let i = 0; i < items.length; i++) {
        items[i].className = items[i].className.replace(" active", "");
    }

    //document.getElementsByClassName("tnpb-tab").forEach(e => e.style.display = "none");
    //document.getElementsByClassName("tnpb-tab-button").forEach(e => e.className = e.className.replace(" active", ""));

    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}

function tnpc_scratch() {

    jQuery('#tnpb-content').html(" ");
    init_builder_area();

}

function tnpc_reload_options(e) {
    e.preventDefault();
    let options = jQuery("#tnpc-block-options-form").serializeArray();
    for (let i = 0; i < options.length; i++) {
        if (options[i].name === 'action') {
            options[i].value = 'tnpc_options';
        }
    }

    jQuery("#tnpc-block-options-form").load(ajaxurl, options);
}

function tnpc_add_global_options(data) {
    let globalOptions = jQuery("#tnpb-settings-form").serializeArray();
    for (let i = 0; i < globalOptions.length; i++) {
        globalOptions[i].name = globalOptions[i].name.replace("[options_", "[").replace("options[", "composer[").replace("composer_", "");
        if (Array.isArray(data)) {
            data.push(globalOptions[i]);
        } else {
            //Inline edit data format is object not array
            data[globalOptions[i].name] = globalOptions[i].value;
        }
    }
}

// ==================================================== //
// =================    PRESET    ===================== //
// ==================================================== //

//TODO - spostare gestione dei preset in contesto privato ma aggiungendo comunque a window le funzioni triggerate da html (load_preset, delete_preset,...) per mantenere compatibilità?
const presetListModal = new TNPModal({
    closeWhenClickOutside: true,
    showClose: true,
    style: {
        backgroundColor: '#ECF0F1',
        height: '500px',
        width: '900px',
    },
    onClose: function () {
        start_composer();
        //Enable buttons
        jQuery('.tnpc-controls input[type=button]').attr('disabled', false);
    }
});

function tnpc_show_presets_modal() {

    jQuery('.tnpc-controls input[type=button]').attr('disabled', true);

    const elModalContent = presetListModal.open();

    jQuery.ajax({
        type: "POST",
        url: ajaxurl,
        data: {
            action: "tnpc_get_all_presets",
            context_type: tnp_context_type,
        },
        success: function (res) {
            jQuery(elModalContent).html(res.data);
        },
    });

}

function tnpc_load_preset(id, subject, isEditMode) {

    presetListModal.close();

    jQuery.ajax({
        type: "POST",
        url: ajaxurl,
        data: {
            action: "tnpc_get_preset",
            id: id
        },
        success: function (res) {
            jQuery('#tnpb-content').html(res.data.content);
            _restore_global_options(res.data.globalOptions);

            start_composer();

            if (!isEditMode) {
                //Enable buttons
                jQuery('.tnpc-controls input[type=button]').attr('disabled', false);
            }

            if (subject && subject.length > 0) {
                jQuery('#options-subject-subject').val(tnpc_remove_double_quotes_escape_from(subject));
            }
        },
    });

    function _restore_global_options(options) {
        jQuery.each(options, function (name, value) {
            var el = jQuery(`#tnpb-settings-form #options-options_composer_${name}`);
            if (el.length) {
                el.val(value);
            }
        });

        tnp_controls_init();
        _setBuilderAreaBackgroundColor(document.getElementById('options-options_composer_background').value);
        let padding = document.getElementById('options-options_composer_padding').value;
        if (padding !== '') {
            padding = parseInt(padding);
        } else {
            padding = 0;
        }
        let style = document.getElementById('tnp-backend-css');
        style.innerText = '#tnpb-content.tnp-view-mobile { padding-left: ' + padding + 'px; padding-right: ' + padding + 'px; padding-top: ' + padding + 'px; padding-bottom: ' + padding + 'px; }';

    }

}

function tnpc_remove_double_quotes_escape_from(str) {
    return str.replace(/\\"/g, '"');
}

function tnpc_remove_double_quotes_from(str) {
    return str.replace(/['"]+/g, '');
}


// ========================================================= //
// =================    PRESET FINE    ===================== //
// ========================================================= //

jQuery(document).ready(function () {
    'use strict'

    var TNPInlineEditor = (function () {

        var className = 'tnpc-inline-editable';
        var newInputName = 'new_name';
        var activeInlineElements = [];

        function init() {
            // find all inline editable elements
            jQuery('#tnpb-content').on('click', '.' + className, function (e) {
                e.preventDefault();
                removeAllActiveElements();

                var originalEl = jQuery(this).hide();
                var newEl = jQuery(getEditableComponent(this.innerText.trim(), this.dataset.id, this.dataset.type, originalEl)).insertAfter(this);

                activeInlineElements.push({'originalEl': originalEl, 'newEl': newEl});

                //Add submit event listener for newly created block
                jQuery('.tnpc-inline-editable-form-' + this.dataset.type + this.dataset.id).on('submit', function (e) {
                    submit(e, newEl, jQuery(originalEl));
                });

                //Add close event listener for newly created block
                jQuery('.tnpc-inline-editable-form-actions .tnpc-dismiss-' + this.dataset.type + this.dataset.id).on('click', function (e) {
                    removeAllActiveElements();
                });

            });

            // Close all created elements if clicked outside
            jQuery('#tnpb-content').on('click', function (e) {
                if (activeInlineElements.length > 0
                        && !jQuery(e.target).hasClass(className)
                        && jQuery(e.target).closest('.tnpc-inline-editable-container').length === 0) {
                    removeAllActiveElements();
                }
            });

        }

        function removeAllActiveElements() {
            activeInlineElements.forEach(function (obj) {
                obj.originalEl.show();

                obj.newEl.off();
                obj.newEl.remove();
            });

            activeInlineElements = []
        }

        function getEditableComponent(value, id, type, originalEl) {

            var element = '';

            //COPY FONT STYLE FROM ORIGINAL ELEMENT
            var fontFamily = originalEl.css('font-family');
            var fontSize = originalEl.css('font-size');
            var styleAttr = "style='font-family:" + fontFamily + ";font-size:" + fontSize + ";'";

            switch (type) {
                case 'text':
                {
                    element = "<textarea name='" + newInputName + "' class='" + className + "-textarea' rows='5' " + styleAttr + ">" + value + "</textarea>";
                    break;
                }
                case 'title':
                {
                    element = "<textarea name='" + newInputName + "' class='" + className + "-textarea' rows='2'" + styleAttr + ">" + value + "</textarea>";
                    break;
                }
            }

            var component = "<td>";
            component += "<form class='tnpc-inline-editable-form tnpc-inline-editable-form-" + type + id + "'>";
            component += "<input type='hidden' name='id' value='" + id + "'>";
            component += "<input type='hidden' name='type' value='" + type + "'>";
            component += "<input type='hidden' name='old_value' value='" + value + "'>";
            component += "<div class='tnpc-inline-editable-container'>";
            component += element;
            component += "<div class='tnpc-inline-editable-form-actions'>";
            component += "<button type='submit'><span class='dashicons dashicons-yes-alt' title='save'></span></button>";
            component += "<span class='dashicons dashicons-dismiss tnpc-dismiss-" + type + id + "' title='close'></span>";
            component += "</div>";
            component += "</div>";
            component += "</form>";
            component += "</td>";
            return component;
        }

        function submit(e, elementToDeleteAfterSubmit, elementToShow) {
            e.preventDefault();

            var id = elementToDeleteAfterSubmit.find('form input[name=id]').val();
            var type = elementToDeleteAfterSubmit.find('form input[name=type]').val();
            var newValue = elementToDeleteAfterSubmit.find('form [name="' + newInputName + '"]').val();

            ajax_render_block(elementToShow, type, id, newValue);

            elementToDeleteAfterSubmit.remove();
            elementToShow.show();

        }

        function ajax_render_block(inlineElement, type, postId, newContent) {

            var target = inlineElement.closest('.edit-block');
            var container = target.closest('table');
            var blockContent = target.children('.tnpc-block-content');

            if (container.hasClass('tnpc-row-block')) {
                var data = {
                    'action': 'tnpc_render',
                    'id': container.data('id'),
                    'b': container.data('id'),
                    'full': 1,
                    '_wpnonce': tnp_nonce,
                    'options': {
                        'inline_edits': [{
                                'type': type,
                                'post_id': postId,
                                'content': newContent
                            }]
                    },
                    'encoded_options': blockContent.data('json')
                };

                tnpc_add_global_options(data);

                jQuery.post(ajaxurl, data, function (response) {
                    var new_row = jQuery(response);

                    container.before(new_row);
                    container.remove();

                    new_row.add_delete();
                    new_row.add_block_edit();
                    new_row.add_block_clone();

                    //Force reload options
                    if (new_row.hasClass('tnpc-row-block')) {
                        new_row.find(".tnpc-row-edit-block").click();
                    }

                }).fail(function () {
                    alert("Block rendering failed.");
                });

            }

        }

        return {init};
    })();

    TNPInlineEditor.init();

});

// =================================================== //
// ===============   GLOBAL STYLE   ================== //
// =================================================== //

jQuery(function () {

    (function globalStyleIIFE() {

        var _elTrigger = document.querySelector('#tnpb-settings-form [name="apply"]');

        _elTrigger.addEventListener('click', function (e) {
            e.preventDefault();

            var data = {
                'action': 'tnpc_regenerate_email',
                'content': tnpc_get_email_content_from_builder_area(),
                '_wpnonce': tnp_nonce,
            };

            tnpc_add_global_options(data);

            jQuery.post(ajaxurl, data, function (response) {
                if (response && response.success) {
                    jQuery('#tnpb-content').html(response.data.content);
                    //Change background color of builder area
                    _setBuilderAreaBackgroundColor(document.getElementById('options-options_composer_background').value);

                    let padding = document.getElementById('options-options_composer_padding').value;
                    if (padding !== '') {
                        padding = parseInt(padding);
                    } else {
                        padding = 0;
                    }
                    let style = document.getElementById('tnp-backend-css');
                    style.innerText = '#tnpb-content.tnp-view-mobile { padding-left: ' + padding + 'px; padding-right: ' + padding + 'px; }';

                    init_builder_area();
                }
                TNP.toast(response.data.message);
            });

        });

    })();

// ========================================================= //
// =================    SEND A TEST    ===================== //
// ========================================================= //

    (function sendATestIIFE($) {

        let el = document.getElementById('test-newsletter-form');

        if (!el) {
            return;
        }

        el.addEventListener('submit', function (ev) {
            ev.preventDefault();
            var testEmail = el.querySelector('input[name="email"]').value;

            let form = document.getElementById("tnpc-form");
            tnpc_save(form);

            form.act.value = "send-test-to-email-address";
            var input = document.createElement("input");
            input.setAttribute("type", "hidden");
            input.setAttribute("name", "test_address_email");
            input.setAttribute("value", testEmail);
            form.appendChild(input);

            form.submit();
        });

    })(jQuery);

// ================================================================== //
// =================    SUBJECT LENGTH ICONS    ===================== //
// ================================================================== //

    (function subjectLengthIconsIIFE($) {
        var $subjectContainer = $('#tnpc-subject');
        var $subjectInput = $('#tnpc-subject input');
        var subjectCharCounterEl = null;

        $subjectInput.on('focusin', function (e) {
            $subjectContainer.find('img').fadeTo(400, 1);
        });

        $subjectInput.on('keyup', function (e) {
            setSubjectCharactersLenght(this.value.length);
        });

        $subjectInput.on('focusout', function (e) {
            $subjectContainer.find('img').fadeTo(300, 0);
        });

        function setSubjectCharactersLenght(length = 0) {

            if (length === 0 && subjectCharCounterEl !== null) {
                subjectCharCounterEl.remove();
                subjectCharCounterEl = null;
                return;
            }

            if (!subjectCharCounterEl) {
                subjectCharCounterEl = document.createElement("span");
                subjectCharCounterEl.style.position = 'absolute';
                subjectCharCounterEl.style.top = '-18px';
                subjectCharCounterEl.style.right = $subjectContainer[0].getBoundingClientRect().width - $subjectInput[0].getBoundingClientRect().width + 'px';
                subjectCharCounterEl.style.color = '#999';
                subjectCharCounterEl.style.fontSize = '0.8rem';
                $subjectContainer.find('div')[0].appendChild(subjectCharCounterEl);
            }

            const word = length === 1 ? 'character' : 'characters';
            subjectCharCounterEl.innerHTML = `${length} ${word}`;
        }

    })(jQuery);

// ======================================================================= //
// =================    COMPOSER MODE VIEW SWITCH    ===================== //
// ======================================================================= //

    (function composerModeViewIIFE($) {

        var status = 'desktop';


        $('#tnpc-view-mode').on('click', function () {
            //console.log('change view mode');
            if (status === 'desktop') {
                status = 'mobile';
                document.getElementById('tnpc-view-mode-icon').className = 'fas fa-mobile';
            } else {
                status = 'desktop';
                document.getElementById('tnpc-view-mode-icon').className = 'fas fa-desktop';
            }


            tnp_view(status);
        });
    })(jQuery);

});
