/**
 * @author    ELEGANTAL <info@elegantal.com>
 * @copyright (c) 2025, ELEGANTAL <www.elegantal.com>
 * @license   Proprietary License - It is forbidden to resell or redistribute copies of the module or modified copies of the module.
 */

var elegantalFormGroupClass = 'form-group';
var elegantalProcessing = false;
var elegantalTimer;

jQuery(document).ready(function () {

    // Identify form group class
    if (jQuery('[type="submit"]').parents('.margin-form').length > 0) {
        elegantalFormGroupClass = 'margin-form';
    }

    // Back button fix on < 1.6.1
    jQuery('.panel-footer button[name="submitOptionsmodule"]').click(function () {
        if (jQuery(this).find('.process-icon-back')) {
            var url = window.location.href.replace(/&event=\w+/gi, "");
            window.location.href = url;
        }
    });

    // Import Edit Page
    if (jQuery('[name="submitImportEdit"]').length > 0) {
        elegantalImportFormVisibility(0);
        jQuery('input, select').on('change', function () {
            elegantalImportFormVisibility(250);
        });
        jQuery('[name="is_cron"]').on('change', function () {
            if (jQuery(this).val() === '1') {
                jQuery('[name="product_limit_per_request"]').val('50');
            } else {
                jQuery('[name="product_limit_per_request"]').val('5');
            }
        });
        jQuery('select[name="find_products_by"]').on('change', function () {
            if (jQuery(this).val() === 'id') {
                alert("You should use ID option IF ONLY product IDs in your import file match product IDs in your shop.");
            }
        });
        jQuery('select[name="find_combinations_by"]').on('change', function () {
            if (jQuery(this).val() === 'combination_id') {
                alert("You should use Combination ID option IF ONLY combination IDs in your import file match combination IDs in your shop.");
            }
        });
        jQuery('[name="supplier_id"]').on('change', function () {
            if (jQuery(this).val() > 0) {
                alert("Please note: if you select a supplier here, it means the module will update products IF ONLY they are associated with the selected supplier.");
            }
        });
    }

    // Mapping Page
    if (jQuery('[name="submitMapping"]').length > 0) {
        var elegantal_form_mapping = jQuery('[name="submitMapping"]').parents('form.elegantalBootstrapWrapper').eq(0);

        jQuery('#elegantal_header_row').appendTo(jQuery('.elegantalBootstrapWrapper .panel .panel-heading')).show();

        // Add padding to multilang custom input fields
        jQuery('.elegantal_lang_select').each(function () {
            jQuery(this).parent().find('input[type="text"][name^="default_"]').eq(0).css('paddingRight', '40px');
        });

        // Ignore all columns
        jQuery('body').on('click', '.ignore_all_columns', function (e) {
            e.preventDefault();
            elegantal_form_mapping.find('select').each(function (index, el) {
                // Don't touch id_reference column and check if first option is "Ignore all" which has value of -1
                if (index > 0 && jQuery(el).find('option:first').val() === '-1') {
                    jQuery(el).find('option:first').prop('selected', true).trigger('change');
                    jQuery(el).parents('.row').find('input[type="text"][name="default_' + jQuery(el).attr('name') + '"]').val("");
                }
            });
            elegantalChangeMappingLabelStyle();
        });

        // Add new column button after category_3, image_3, accessory_3, feature_3, attachment_3, attachment_name_3, attachment_description_3, attribute_name_3, attribute_value_3 ...
        var mapping_multiple_columns = ['category', 'image', 'accessory', 'feature', 'attachment', 'attachment_name', 'attachment_description', 'attribute_name', 'attribute_value', 'iqitadditionaltabs_title', 'iqitadditionaltabs_description', 'classy_productextratab_title', 'classy_productextratab_content'];
        jQuery.each(mapping_multiple_columns, function (index, column) {
            jQuery(jQuery('input[name^="default_' + column + '_"]').get().reverse()).each(function () {
                if (jQuery(this).prop('name').match(new RegExp("^default_" + column + "_[\\d]+"))) {
                    jQuery(this).parent().append(jQuery('<button type="button" class="btn btn-default elegantal_mapping_add">+</button>'));
                    return false; // break
                }
            });
        });
        jQuery('body').on('click', '.elegantal_mapping_add', function (e) {
            e.preventDefault();
            var form_group = jQuery(this).parents('.elegantal_mapping_form_group');
            var new_form_group = form_group.clone();
            var label = new_form_group.find('label');
            var matches = new_form_group.find('select').eq(0).prop('name').match(/([a-z_]+)_([\d]+)_?([\d]+)?/);
            if (matches) {
                var input_name = matches[1];
                var next_number = parseInt(matches[2]) + 1;
                label.text(label.text().trim().match(/[a-zA-Z_\s]+/) + " " + next_number).css({ fontWeight: 'normal', color: 'inherit' });
                new_form_group.find('select').each(function (index, el) {
                    var select = jQuery(el);
                    var input = select.parent().parent().find('input').eq(index);
                    matches = select.prop('name').match(/([a-z_]+)_([\d]+)_?([\d]+)?/);
                    if (matches) {
                        var id_lang = matches[3] ? parseInt(matches[3]) : false;
                        var new_input_name = input_name + "_" + next_number + (id_lang ? "_" + id_lang : "");
                        select.prop('name', new_input_name).val('-1');
                        select.prop('id', new_input_name);
                        input.prop('name', "default_" + new_input_name).val("");
                        input.prop('id', "default_" + new_input_name);
                        input.prop('placeholder', input.prop('placeholder').match(/[a-zA-Z_\s]+/) + next_number);
                    }
                });
                form_group.after(new_form_group);
                jQuery(this).remove();
            }
        });

        jQuery('body').on('click', '.elegantal_lang_select a', function (e) {
            e.preventDefault();
            var btn = jQuery(this);
            if (btn.parent().prop("class") !== 'active') {
                var id_lang = parseInt(btn.data('id_lang'));
                var form_group = btn.parents('.elegantal_mapping_form_group');
                form_group.find('select').each(function (index, el) {
                    var select = jQuery(el);
                    var input = select.parent().parent().find('input').eq(index);
                    var matches = select.prop('name').match(/([a-z_]+)_([\d]+)_?([\d]+)?/);
                    var id_lang_select = (matches && matches[3]) ? parseInt(matches[3]) : ((matches && matches[2]) ? parseInt(matches[2]) : false);
                    if (id_lang_select && id_lang_select === id_lang) {
                        form_group.find('select').hide();
                        form_group.find('input').hide();
                        form_group.find('.elegantal_lang_select li.active').removeClass('active');
                        btn.parent().addClass('active');
                        form_group.find('.elegantal_lang_select button.dropdown-toggle span').html(btn.data('iso_code'));
                        select.show();
                        input.show();
                        return false;
                    }
                });
            }
        });

        // Change label style for selected mapping
        elegantalChangeMappingLabelStyle();
        jQuery('body').on('change', 'form .elegantal_mapping_form_group select', function () {
            elegantalChangeMappingLabelStyle();
        });
        jQuery('body').on('keyup change input propertychange', 'form .elegantal_mapping_form_group input[name^="default_"]', function () {
            elegantalChangeMappingLabelStyle();
        });

        // Submit the mapping form when input changes
        jQuery('body').on('keyup change input propertychange', "form.elegantalBootstrapWrapper :input", function () {
            if (elegantalProcessing === false && jQuery('#elegantal_header_row input').val() === '1') {
                clearTimeout(elegantalTimer);
                elegantalTimer = setTimeout(function () {
                    elegantalAjaxSubmitForm(elegantal_form_mapping);
                }, 3000);
            }
        });
        elegantal_form_mapping.find('button[type="submit"]').click(function () {
            clearTimeout(elegantalTimer);
            elegantalAjaxSubmitForm(elegantal_form_mapping);
            if (elegantal_max_input_vars !== undefined && elegantal_max_input_vars > 0) {
                var input_count = elegantal_form_mapping.find(":input").length;
                if (input_count > elegantal_max_input_vars) {
                    return confirm("You need to increase max_input_vars limit in php.ini file on your server, because it is lower than the number of inputs you have in this page. It prevents the module from submitting all input fields from this page. Currently max_input_vars = " + elegantal_max_input_vars + ", form_inputs = " + input_count + ". Do you want to continue?");
                }
            }
        });
    }

    // Manage Category Page
    if (jQuery('[name="submitManageCategory"]').length > 0) {
        var elegantal_form_manage_category = jQuery('[name="submitManageCategory"]').parents('form.elegantalBootstrapWrapper').eq(0);

        jQuery('.cattree.tree').addClass('full_loaded');

        // Use jQuery Plugin "Chosen" to make select box with search option
        var categories_map_chooser = jQuery('.elegantal_categories_map_chooser:last');
        var categories_map_chooser_select_file = categories_map_chooser.find('select[name="categories_map_file[]"]');
        var categories_map_chooser_select_shop = categories_map_chooser.find('select[name="categories_map_shop[]"]');
        categories_map_chooser_select_file.chosen();
        categories_map_chooser_select_shop.chosen();

        // Add new category mapping
        jQuery('body').on('click', '.add_new_category_map', function (e) {
            e.preventDefault();
            var chooser_container = jQuery(this).parents('.elegantal_categories_map_chooser');
            var chosen_category_file = chooser_container.find('[name="categories_map_file[]"]').val();
            var chosen_category_shop = chooser_container.find('[name="categories_map_shop[]"]').val();
            var chosen_shop_category_name = chooser_container.find('[name="categories_map_shop[]"] option:selected').text();
            if (chosen_category_file) {
                var cloned_categories_map = jQuery('.elegantal_categories_map_hidden').clone();
                cloned_categories_map.removeClass('elegantal_categories_map_hidden');
                cloned_categories_map.find('[name="categories_map_file[]"]').val(chosen_category_file);
                cloned_categories_map.find('[name="categories_map_shop[]"]').val(chosen_category_shop);
                cloned_categories_map.find('.categories_map_file_category_name').html(chosen_category_file);
                cloned_categories_map.find('.categories_map_shop_category_name').html(chosen_shop_category_name);
                cloned_categories_map.insertBefore(chooser_container);
                if (jQuery('.elegantal_categories_map_chooser').length > 1) {
                    chooser_container.remove();
                } else {
                    chooser_container.find('[name="categories_map_file[]"]').val('').trigger('chosen:updated');
                    chooser_container.find('[name="categories_map_shop[]"]').val('').trigger('chosen:updated');
                }
            }
        });

        // Edit category mapping
        jQuery('body').on('click', '.elegantal_categories_map_edit', function (e) {
            e.preventDefault();
            jQuery('.add_new_category_map').click(); // Need this to close other edit box and allow one edit at a time
            var current_categories_map = jQuery(this).parents('.elegantal_categories_map');
            var current_category_file_val = current_categories_map.find('[name="categories_map_file[]"]').val();
            var current_category_shop_val = current_categories_map.find('[name="categories_map_shop[]"]').val();
            var chooser_cloned = categories_map_chooser.clone();
            chooser_cloned.find('.chosen-container').remove();
            chooser_cloned.find('select').show();
            chooser_cloned.insertAfter(current_categories_map);
            chooser_cloned.find('select[name="categories_map_file[]"]').val(current_category_file_val).chosen();
            chooser_cloned.find('select[name="categories_map_shop[]"]').val(current_category_shop_val).chosen();
            current_categories_map.remove();
        });

        // Remove category mapping
        jQuery('body').on('click', '.elegantal_categories_map_remove', function (e) {
            e.preventDefault();
            jQuery(this).parents('.elegantal_categories_map').remove();
            categories_map_chooser_select_file.trigger('change');
        });

        // Add mapping for all Allowed Categories
        jQuery('.category_mapping_for_allowed_categories').click(function (e) {
            e.preventDefault();
            // Get all existing category mappings into array
            var existing_categories_map_file = [];
            jQuery('.elegantal_categories_map').each(function (index, el) {
                var current_map_file = jQuery(el).find('[name="categories_map_file[]"]').val();
                if (current_map_file && jQuery.inArray(current_map_file, existing_categories_map_file) === -1) {
                    existing_categories_map_file.push(current_map_file);
                }
            });
            // For each allowed category, create mapping if it does not exist already in existing_categories_map_file
            jQuery('[name="categories_allowed[]"]:checkbox:checked').each(function (index, el) {
                var current_val = jQuery(el).val();
                if (current_val && jQuery.inArray(current_val, existing_categories_map_file) === -1) {
                    categories_map_chooser_select_file.val(current_val);
                    categories_map_chooser.find('.add_new_category_map').click();
                }
            });
            categories_map_chooser_select_file.val('').trigger('chosen:updated');
            categories_map_chooser_select_shop.val('').trigger('chosen:updated');
        });

        // Get title for each li item from select box of category mapping, to show category path for better visibility
        jQuery('body').on('mouseover', '.chosen-container ul.chosen-results li', function () {
            var li = jQuery(this);
            var index = li.data('option-array-index');
            if (index !== undefined && !li.prop('title')) {
                var title = jQuery(this).parents('.chosen-container').parent().find('select option').eq(index).prop('title');
                if (title) {
                    li.prop('title', title);
                }
            }
        });

        // Submit the manage-category form when input changes
        jQuery('body').on('keyup change input propertychange', "form.elegantalBootstrapWrapper :input", function () {
            if (elegantalProcessing === false) {
                clearTimeout(elegantalTimer);
                elegantalTimer = setTimeout(function () {
                    elegantalAjaxSubmitForm(elegantal_form_manage_category);
                }, 3000);
            }
        });
        elegantal_form_manage_category.find('button[type="submit"]').click(function () {
            clearTimeout(elegantalTimer);
            elegantalAjaxSubmitForm(elegantal_form_manage_category);
            if (elegantal_max_input_vars !== undefined && elegantal_max_input_vars > 0) {
                var input_count = elegantal_form_manage_category.find(":input").length;
                if (input_count > elegantal_max_input_vars) {
                    return confirm("You need to increase max_input_vars limit in php.ini file on your server, because it is lower than the number of inputs you have in this page. It prevents the module from submitting all input fields from this page. Currently max_input_vars = " + elegantal_max_input_vars + ", form_inputs = " + input_count + ". Do you want to continue?");
                }
            }
        });
    }

    // Import Page
    if (jQuery('.elegantal_import_panel').length > 0) {
        // Prevent accidental page reload
        window.onbeforeunload = function () {
            if (elegantalProcessing) {
                return jQuery('.elegantal_import_panel').data('reloadmsg');
            }
        };
        // Start timer
        elegantalTimer = setInterval(elegantalDisplayTimer, 1000);
        // Make first request to save csv rows
        elegantalImportSaveCsvRows();
    }

    // Export Edit Page
    if (jQuery('[name="submitExportEdit"]').length > 0) {
        // Change file path extension if file format is changed
        jQuery('[name="file_format"]').on('change', function () {
            var file_path = jQuery('[name="file_path"]').val();
            var file_ext = file_path.substr((file_path.lastIndexOf('.') + 1));
            var new_ext = jQuery(this).val();
            if (file_ext !== new_ext && (file_ext.length === 3 || file_ext.length === 4)) {
                jQuery('[name="file_path"]').val(file_path.substr(0, (file_path.lastIndexOf('.'))) + '.' + new_ext);
            }
        });

        elegantalExportFormVisibility(0);
        jQuery('input, select').on('change', function () {
            elegantalExportFormVisibility(250);
        });
    }

    // Export Columns Page
    if (jQuery('[name="submitExportColumns"]').length > 0) {
        var export_columns_form = jQuery('[name="submitExportColumns"]').parents('form').eq(0);
        export_columns_form.addClass('export_columns_form');
        jQuery('.elegantalBootstrapWrapper .panel .panel-heading').append('<a href="#" class="checkUncheckAllColumns"><i class="icon-server"></i></a>');

        jQuery('.checkUncheckAllColumns').addClass('checkedAllColumns');
        jQuery('.checkUncheckAllColumns').prop('title', 'Deselect all columns');

        jQuery('body').on('click', '.checkUncheckAllColumns', function (e) {
            e.preventDefault();
            if (jQuery(this).hasClass('checkedAllColumns')) {
                jQuery(this).removeClass('checkedAllColumns').addClass('uncheckedAllColumns').prop('title', 'Select all columns');
                export_columns_form.find('.switch input[type="radio"][value="0"]').prop('checked', true);
                export_columns_form.find('input[type="text"][name^="default_"]').val("");
            } else if (jQuery(this).hasClass('uncheckedAllColumns')) {
                jQuery(this).removeClass('uncheckedAllColumns').addClass('checkedAllColumns').prop('title', 'Uncheck all columns');
                export_columns_form.find('.switch input[type="radio"][value="1"]').prop('checked', true);
            }
            // Trigger change event of any input to submit the form by ajax
            jQuery('form.elegantalBootstrapWrapper input[type="text"]').eq(0).change();
        });

        // Make sortable rows
        export_columns_form.find('.panel .form-wrapper').sortable({
            cursor: 'move',
            axis: "y",
            stop: function (event, ui) {
                // Trigger change event of any input to submit the form by ajax
                jQuery('form.elegantalBootstrapWrapper input[type="text"]').eq(0).change();
            }
        });

        // Convert export column label into text input for changing column name
        jQuery('body').on('click', '.panel .form-wrapper .form-group.elegantal_columns_form_group>label.control-label', function () {
            var label = jQuery(this);
            label.addClass('elegantal_columns_label_converted');
            if (label.find('.elegantal_columns_label').length === 0) {
                label.html(label.parents('.form-group').find('.elegantal_columns_label').show());
            }
        });
        jQuery('body').on('mouseover', '.panel .form-wrapper .form-group.elegantal_columns_form_group', function () {
            var label = jQuery(this).find('label.control-label');
            if (label.find('i').length === 0 && label.find('input').length === 0) {
                label.append('<i class="icon-pencil elegantal_columns_icon_edit"></i>');
            }
        });

        var export_multiple_columns = ['custom_column'];
        jQuery.each(export_multiple_columns, function (index, column) {
            jQuery(jQuery('input[name^="default_' + column + '_"]').get().reverse()).each(function () {
                if (jQuery(this).prop('name').match(/[a-z_]+[\d]+/)) {
                    jQuery(this).parent().append(jQuery('<button type="button" class="btn btn-default elegantal_column_add">+</button>'));
                    return false; // break
                }
            });
        });
        jQuery('body').on('click', '.elegantal_column_add', function (e) {
            e.preventDefault();
            var form_group = jQuery(this).parents('.elegantal_columns_form_group');
            var new_form_group = form_group.clone();
            var matches = new_form_group.find('input[type="radio"]').eq(0).prop('name').match(/([a-z_]+)_([\d]+)_?([\d]+)?/);
            if (matches) {
                // Find latest created custom field number, it can be on top because of re-positioning, but we need max number
                var max_number = 1;
                jQuery('input[name^="default_custom_column_"]').each(function (index, el) {
                    var n = parseInt(jQuery(el).prop('name').replace("default_custom_column_", ""));
                    if (n > max_number) {
                        max_number = n;
                    }
                });
                var input_name = matches[1];
                var current_number = parseInt(matches[2]);
                var next_number = max_number + 1;
                var old_input_name = input_name + '_' + current_number;
                var new_input_name = input_name + '_' + next_number;
                var label = new_form_group.find('label.control-label').eq(0);
                var default_text_input = new_form_group.find('input[type="text"][name="default_' + old_input_name + '"]');
                var label_text_original = default_text_input.prop('placeholder');
                var new_label_text = label_text_original.match(/[a-zA-Z_\s]+/) + next_number;
                // If there is input inside label, hide it and move it to different parent
                if (label.find('input.elegantal_columns_label').length > 0) {
                    label.find('input.elegantal_columns_label').hide().detach().appendTo(default_text_input.parent());
                }
                label.removeClass('elegantal_columns_label_converted').html(new_label_text);
                new_form_group.find('input.elegantal_columns_label').prop('name', 'label_' + new_input_name).prop('title', new_label_text).val(new_label_text);
                new_form_group.find('input[type="radio"][value="1"]').prop('name', new_input_name).prop('id', new_input_name + '_on').prop('checked', true);
                new_form_group.find('input[type="radio"][value="0"]').prop('name', new_input_name).prop('id', new_input_name + '_off').prop('checked', false);
                new_form_group.find('label[for="' + old_input_name + '_on"]').prop('for', new_input_name + '_on');
                new_form_group.find('label[for="' + old_input_name + '_off"]').prop('for', new_input_name + '_off');
                default_text_input.prop('name', 'default_' + new_input_name).prop('placeholder', label_text_original.match(/[a-zA-Z_\s]+/) + next_number).prop('title', label_text_original.match(/[a-zA-Z_\s]+/) + next_number).val("");
                form_group.after(new_form_group);
                jQuery(this).remove();
            }
        });

        // Submit the export-columns form when input changes
        jQuery('body').on('keyup change input propertychange', "form.elegantalBootstrapWrapper :input", function () {
            if (elegantalProcessing === false) {
                clearTimeout(elegantalTimer);
                elegantalTimer = setTimeout(function () {
                    elegantalAjaxSubmitForm(export_columns_form);
                }, 3000);
            }
        });
        export_columns_form.find('button[type="submit"]').click(function () {
            clearTimeout(elegantalTimer);
            elegantalAjaxSubmitForm(export_columns_form);
            if (elegantal_max_input_vars !== undefined && elegantal_max_input_vars > 0) {
                var input_count = export_columns_form.find(":input").length;
                if (input_count > elegantal_max_input_vars) {
                    return confirm("You need to increase max_input_vars limit in php.ini file on your server, because it is lower than the number of inputs you have in this page. It prevents the module from submitting all input fields from this page. Currently max_input_vars = " + elegantal_max_input_vars + ", form_inputs = " + input_count + ". Do you want to continue?");
                }
            }
        });
    }

    // Export Page
    if (jQuery('.elegantal_export_panel').length > 0) {
        // Prevent accidental page reload
        window.onbeforeunload = function () {
            if (elegantalProcessing) {
                return jQuery('.elegantal_export_panel').data('reloadmsg');
            }
        };
        // Start timer
        elegantalTimer = setInterval(elegantalDisplayTimer, 1000);
        // Start with export
        elegantalExportRequest();
    }
});

function elegantalImportSaveCsvRows() {
    elegantalProcessing = true;
    var elegantalAdminUrl = jQuery('.elegantaleasyimportJsDef').data('adminurl');
    var panel = jQuery('.elegantal_import_panel');
    var progress = panel.find('.elegantal_progress_bar');
    var id = panel.data('id');
    var limit = panel.data('limit');
    var min = 100;
    var max = 100000000;
    var random = Math.floor(Math.random() * (max - min + 1)) + min;

    jQuery.ajax({
        url: elegantalAdminUrl,
        type: 'GET',
        dataType: 'json',
        data: {
            event: 'import',
            ajax: 1,
            saveCsvRows: 1,
            id_elegantaleasyimport: id,
            elegantal: random
        },
        success: function (result) {
            if (result && result.success) {
                var total = result.count;
                var requests = 1;

                if (total && total > limit) {
                    requests = Math.ceil(total / limit);
                }

                panel.data('requests', requests);

                jQuery('.elegantal_prepare_csv_txt').hide();
                jQuery('.elegantal_import_csv_txt').fadeIn();

                jQuery('.elegantal_import_total_number_of_products').text(total);
                jQuery('.elegantal_import_number_of_products_processed').text(0);
                jQuery('.elegantal_import_number_of_products_created').text(0);
                jQuery('.elegantal_import_number_of_products_updated').text(0);
                jQuery('.elegantal_import_number_of_products_deleted').text(0);
                jQuery('.elegantal_import_errors_found').text(0);

                // Start import with the first request
                elegantalImportRequest(1);
            } else {
                elegantalProcessing = false;
                clearInterval(elegantalTimer);
                jQuery('.elegantal_error_txt').text(((result && result.message) ? result.message : "Unknown error occurred."));
                jQuery('.elegantal_error').fadeIn();
                jQuery('html, body').animate({ scrollTop: 0 });
                progress.css({ width: '100%' });
                progress.text('Stopped at ' + progress.text());
                progress.addClass('progress-bar-danger');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            elegantalProcessing = false;
            clearInterval(elegantalTimer);
            jQuery('.elegantal_error_txt').text(errorThrown);
            jQuery('.elegantal_error').fadeIn();
            jQuery('html, body').animate({ scrollTop: 0 });
            progress.css({ width: '100%' });
            progress.text('Stopped at ' + progress.text());
            progress.addClass('progress-bar-danger');
        }
    });
}

function elegantalImportRequest(currentRequest) {
    elegantalProcessing = true;
    var elegantalAdminUrl = jQuery('.elegantaleasyimportJsDef').data('adminurl');
    var panel = jQuery('.elegantal_import_panel');
    var progress = panel.find('.elegantal_progress_bar');

    var id = panel.data('id');
    var totalRequests = panel.data('requests');

    // Generate random number for GET request. This is needed to prevent if there is cache for the URL
    var min = 100;
    var max = 100000000;
    var random = Math.floor(Math.random() * (max - min + 1)) + min;

    jQuery.ajax({
        url: elegantalAdminUrl,
        type: 'GET',
        dataType: 'json',
        data: {
            event: 'import',
            ajax: 1,
            id_elegantaleasyimport: id,
            elegantal: random
        },
        success: function (result) {
            if (result.success) {
                var completed = (currentRequest * 100) / totalRequests;
                var finishmsg = (completed === 100) ? progress.data('finishmsg') + " " : "";
                progress.css({ width: completed + '%' });
                progress.text(finishmsg + Math.round(completed) + '%');

                if (result.processed > 0) {
                    jQuery('.elegantal_import_number_of_products_processed').text(result.processed);
                }
                if (result.created > 0) {
                    jQuery('.elegantal_import_number_of_products_created').text(result.created);
                }
                if (result.updated > 0) {
                    jQuery('.elegantal_import_number_of_products_updated').text(result.updated);
                }
                if (result.deleted > 0) {
                    jQuery('.elegantal_import_number_of_products_deleted').text(result.deleted);
                    jQuery('.elegantal_import_number_of_products_deleted_block').fadeIn();
                }
                if (result.errors > 0) {
                    jQuery('.elegantal_import_errors_found').text(result.errors);
                    jQuery('.elegantal_import_errors_found_block').fadeIn();
                }

                if (currentRequest < totalRequests) {
                    elegantalImportRequest(currentRequest + 1);
                } else {
                    elegantalProcessing = false;
                    clearInterval(elegantalTimer);
                    progress.addClass('progress-bar-success');
                }
            } else {
                elegantalProcessing = false;
                clearInterval(elegantalTimer);
                jQuery('.elegantal_error_txt').text(result.message);
                jQuery('.elegantal_error').fadeIn();
                jQuery('html, body').animate({ scrollTop: 0 });
                progress.css({ width: '100%' });
                progress.text('Stopped at ' + progress.text());
                progress.addClass('progress-bar-danger');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);
            console.log(jqXHR.responseText);

            var completed = (currentRequest * 100) / totalRequests;
            var finishmsg = (completed === 100) ? progress.data('finishmsg') + " " : "";
            progress.css({ width: completed + '%' });
            progress.text(finishmsg + Math.round(completed) + '%');

            if (currentRequest < totalRequests) {
                elegantalImportRequest(currentRequest);
            } else {
                elegantalProcessing = false;
                clearInterval(elegantalTimer);
                progress.addClass('progress-bar-success');
            }
        }
    });
}

function elegantalExportRequest() {
    elegantalProcessing = true;
    var elegantalAdminUrl = jQuery('.elegantaleasyimportJsDef').data('adminurl');
    var panel = jQuery('.elegantal_export_panel');
    var id = panel.data('id');
    var min = 100;
    var max = 100000000;
    var random = Math.floor(Math.random() * (max - min + 1)) + min;

    jQuery.ajax({
        url: elegantalAdminUrl,
        type: 'GET',
        dataType: 'json',
        data: {
            event: 'export',
            ajax: 1,
            id_elegantaleasyimport_export: id,
            elegantal: random
        },
        success: function (result) {
            if (result.success) {
                elegantalProcessing = false;
                clearInterval(elegantalTimer);
                jQuery('.elegantal_result_txt').text(jQuery('.elegantal_result_txt').text().replace('_count', result.count));
                jQuery('.elegantal_progress_row').hide();
                jQuery('.elegantal_result_row').fadeIn();
            } else {
                elegantalProcessing = false;
                clearInterval(elegantalTimer);
                jQuery('.elegantal_progress_row').hide();
                jQuery('.elegantal_error_txt').text(result.message);
                jQuery('.elegantal_error').fadeIn();
                jQuery('html, body').animate({ scrollTop: 0 });
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            elegantalProcessing = false;
            clearInterval(elegantalTimer);
            console.log(errorThrown);
            console.log(jqXHR.responseText);
            jQuery('.elegantal_progress_row').hide();
            jQuery('.elegantal_error_txt').text(errorThrown);
            jQuery('.elegantal_error').fadeIn();
            jQuery('html, body').animate({ scrollTop: 0 });
        }
    });
}

function elegantalImportFormVisibility(speed) {
    if (jQuery('[name="import_type"]').val() === '1') {
        jQuery('[name="csv_file_upload"]').parents('.' + elegantalFormGroupClass).fadeIn(speed);
        jQuery('[name="csv_path"], [name="csv_url"], [name="csv_url_username"], [name="csv_url_password"], [name="csv_url_method"], [name="ftp_host"], [name="ftp_port"], [name="ftp_username"], [name="ftp_password"], [name="ftp_file"]').parents('.' + elegantalFormGroupClass).fadeOut(speed);
    } else if (jQuery('[name="import_type"]').val() === '2') {
        jQuery('[name="csv_path"]').parents('.' + elegantalFormGroupClass).fadeIn(speed);
        jQuery('[name="csv_file_upload"], [name="csv_url"], [name="csv_url_username"], [name="csv_url_password"], [name="csv_url_method"], [name="ftp_host"], [name="ftp_port"], [name="ftp_username"], [name="ftp_password"], [name="ftp_file"]').parents('.' + elegantalFormGroupClass).fadeOut(speed);
    } else if (jQuery('[name="import_type"]').val() === '3') {
        jQuery('[name="csv_url"], [name="csv_url_username"], [name="csv_url_password"], [name="csv_url_method"]').parents('.' + elegantalFormGroupClass).fadeIn(speed);
        jQuery('[name="csv_file_upload"], [name="csv_path"], [name="ftp_host"], [name="ftp_port"], [name="ftp_username"], [name="ftp_password"], [name="ftp_file"]').parents('.' + elegantalFormGroupClass).fadeOut(speed);
    } else if (jQuery('[name="import_type"]').val() === '4' || jQuery('[name="import_type"]').val() === '5') {
        jQuery('[name="ftp_host"], [name="ftp_port"], [name="ftp_username"], [name="ftp_password"], [name="ftp_file"]').parents('.' + elegantalFormGroupClass).fadeIn(speed);
        jQuery('[name="csv_file_upload"], [name="csv_path"], [name="csv_url"], [name="csv_url_username"], [name="csv_url_password"], [name="csv_url_method"]').parents('.' + elegantalFormGroupClass).fadeOut(speed);
    } else {
        jQuery('[name="csv_file_upload"], [name="csv_path"], [name="csv_url"], [name="csv_url_username"], [name="csv_url_password"], [name="csv_url_method"], [name="ftp_host"], [name="ftp_port"], [name="ftp_username"], [name="ftp_password"], [name="ftp_file"]').parents('.' + elegantalFormGroupClass).fadeIn(speed);
    }

    if (jQuery('[name="import_type"]').val() === '3' && jQuery('[name="csv_url_method"]').val() === 'POST') {
        jQuery('[name="csv_url_post_params"]').parents('.' + elegantalFormGroupClass).fadeIn(speed);
    } else {
        jQuery('[name="csv_url_post_params"]').parents('.' + elegantalFormGroupClass).fadeOut(speed);
    }

    if (jQuery('[name="entity"]').val() === 'combination') {
        jQuery('[name="delete_old_combinations"], [name="find_combinations_by"]').parents('.' + elegantalFormGroupClass).fadeIn(speed);
    } else {
        jQuery('[name="delete_old_combinations"], [name="find_combinations_by"]').parents('.' + elegantalFormGroupClass).fadeOut(speed);
    }

    var elegantal_form_is_cron = jQuery('[name="email_to_send_notification"], [name="product_limit_per_request"]').parents('.' + elegantalFormGroupClass);
    if (jQuery('[name="is_cron"]:checked').val() === '1') {
        elegantal_form_is_cron.fadeIn(speed);
    } else {
        elegantal_form_is_cron.fadeOut(speed);
    }

    var elegantal_form_create_new_products = jQuery('[name="enable_new_products_by_default"], [name="skip_if_no_stock"]').parents('.' + elegantalFormGroupClass);
    if (jQuery('[name="create_new_products"]:checked').val() === '1' && jQuery('[name="entity"]').val() === 'product') {
        elegantal_form_create_new_products.fadeIn(speed);
    } else {
        elegantal_form_create_new_products.fadeOut(speed);
    }

    var elegantal_form_force_id = jQuery('[name="force_id_product"]').parents('.' + elegantalFormGroupClass);
    if (jQuery('[name="create_new_products"]:checked').val() === '1' && jQuery('[name="entity"]').val() === 'product' && jQuery('[name="find_products_by"]').val() === 'id') {
        elegantal_form_force_id.fadeIn(speed);
    } else {
        elegantal_form_force_id.fadeOut(speed);
    }

    var elegantal_form_update_existing_products = jQuery('[name="disable_all_products_not_found_in_csv"], [name="deny_orders_when_no_stock_for_products_not_found_in_file"], [name="delete_stock_for_products_not_found_in_csv"]').parents('.' + elegantalFormGroupClass);
    if (jQuery('[name="update_existing_products"]:checked').val() === '1') {
        elegantal_form_update_existing_products.fadeIn(speed);
    } else {
        elegantal_form_update_existing_products.fadeOut(speed);
    }
}

function elegantalExportFormVisibility(speed) {
    var visible_for_product = jQuery('[name="multiple_subcategory_separator"], [name="currency_id"], [name="order_by"], [name="order_direction"]');
    if (jQuery('[name="entity"]').val() === 'product') {
        visible_for_product.parents('.' + elegantalFormGroupClass).fadeIn(speed);
    } else {
        visible_for_product.parents('.' + elegantalFormGroupClass).fadeOut(speed);
    }
    if (jQuery('[name="file_format"]').val() === 'csv') {
        jQuery('[name="csv_delimiter"]').parents('.' + elegantalFormGroupClass).fadeIn(speed);
    } else {
        jQuery('[name="csv_delimiter"]').parents('.' + elegantalFormGroupClass).fadeOut(speed);
    }
    if (jQuery('[name="file_format"]').val() === 'xml') {
        jQuery('[name="xml_root_node"], [name="xml_item_node"]').parents('.' + elegantalFormGroupClass).fadeIn(speed);
    } else {
        jQuery('[name="xml_root_node"], [name="xml_item_node"]').parents('.' + elegantalFormGroupClass).fadeOut(speed);
    }
}

function elegantalChangeMappingLabelStyle() {
    jQuery('[name="submitMapping"]').parents('form').find('.elegantal_mapping_form_group').each(function () {
        var form_group = jQuery(this);
        var label = form_group.find('label');
        form_group.find('select').each(function (index, el) {
            if (jQuery(el).val() !== '-1' || (form_group.find('input[name^="default_"]').eq(index).val() !== "" && form_group.find('input[name^="default_"]').eq(index).val() !== undefined)) {
                label.css({ fontWeight: 'bold', color: '#008bf0' });
                return false;
            } else {
                label.css({ fontWeight: 'normal', color: 'inherit' });
            }
        });
    });
}

function elegantalDisplayTimer() {
    var display_el = jQuery('.elegantal_import_timer span');
    var time_shown = display_el.text();
    var time_chunks = time_shown.split(":");
    var h, m, s;

    h = Number(time_chunks[0]);
    m = Number(time_chunks[1]);
    s = Number(time_chunks[2]);
    s++;

    if (s === 60) {
        s = 0;
        m++;
    }
    if (m === 60) {
        m = 0;
        h++;
    }

    h = '0' + h;
    m = '0' + m;
    s = '0' + s;
    display_el.text(h.substr(h.length - 2) + ':' + m.substr(m.length - 2) + ':' + s.substr(s.length - 2));
}

function elegantalAjaxSubmitForm(form) {
    elegantalProcessing = true;
    var data = form.serialize();
    var min = 100;
    var max = 100000000;
    var random = Math.floor(Math.random() * (max - min + 1)) + min;
    data += '&ajax=1&elegantal=' + random;
    jQuery.ajax({
        url: form.prop('action'),
        type: 'POST',
        dataType: 'json',
        data: data,
        success: function (result) {
            elegantalProcessing = false;
            if (result.message) {
                alert(result.message);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            elegantalProcessing = false;
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
}