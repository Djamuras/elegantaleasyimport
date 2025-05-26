{*
* @author    ELEGANTAL <info@elegantal.com>
* @copyright (c) 2025, ELEGANTAL <www.elegantal.com>
* @license   Proprietary License - It is forbidden to resell or redistribute copies of the module or modified copies of the module.
*}

{extends file="helpers/form/form.tpl"}

{block name="input"}
    {if $input.type == 'el_switch'}
        <div class="radio clearfix" style="margin-bottom: 20px">
            {foreach $input.values as $value}
                <label style="margin-right: 15px">
                    <input type="radio" name="{$input.name|escape:'html':'UTF-8'}" {if $value.value == 1} id="{$input.name|escape:'html':'UTF-8'}_on" {else} id="{$input.name|escape:'html':'UTF-8'}_off" {/if} value="{$value.value|escape:'html':'UTF-8'}" {if $fields_value[$input.name] == $value.value} checked="checked" {/if}{if isset($input.disabled) && $input.disabled} disabled="disabled" {/if} style="margin-top: 1px" />
                    {if $value.value == 1} Yes {else} No {/if}
                </label>
            {/foreach}
        </div>
    {elseif $input.type == 'elegantalpassword'}
        {assign var='value_text' value=$fields_value[$input.name]}
        <input type="password" id="{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}" name="{$input.name|escape:'html':'UTF-8'}" class="{if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if}" value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}" {if isset($input.size)} size="{$input.size|escape:'html':'UTF-8'}" {/if} {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}" {/if} {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}" {/if} {if isset($input.readonly) && $input.readonly} readonly="readonly" {/if} {if isset($input.disabled) && $input.disabled} disabled="disabled" {/if} {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="new-password" {/if} {if isset($input.required) && $input.required} required="required" {/if} {if isset($input.placeholder) && $input.placeholder} placeholder="{$input.placeholder|escape:'html':'UTF-8'}" {/if} />
    {elseif $input.type == 'elegantal_mapping_select'}
        <div class="row">
            <div class="col-xs-6">
                <select name="{$input.name|escape:'html':'UTF-8'}" class="elegantal_mapping_select" id="{$input.name|escape:'html':'UTF-8'}">
                    {foreach $input.options.query as $option}
                        <option value="{$option[$input.options.id]|escape:'html':'UTF-8'}" {if $fields_value[$input.name] == $option[$input.options.id]}selected="selected" {/if}>{$option[$input.options.name]|escape:'html':'UTF-8'}</option>
                    {/foreach}
                </select>
                {if is_array($languages) && count($languages) > 1}
                    {foreach $multilang_columns as $mcol}
                        {if $input.name == $mcol|cat:'_'|cat:$id_lang_default}
                            {foreach $languages as $language}
                                {if $language.id_lang != $id_lang_default && $language.active}
                                    {assign var='input_name' value=$mcol|cat:'_'|cat:$language.id_lang}
                                    <select name="{$input_name|escape:'html':'UTF-8'}" class="elegantal_mapping_select" id="{$input_name|escape:'html':'UTF-8'}" style="display:none">
                                        {foreach $input.options.query as $option}
                                            <option value="{$option[$input.options.id]|escape:'html':'UTF-8'}" {if isset($fields_value[$input_name]) && $fields_value[$input_name] == $option[$input.options.id]}selected="selected" {/if}>{$option[$input.options.name]|escape:'html':'UTF-8'}</option>
                                        {/foreach}
                                    </select>
                                {/if}
                            {/foreach}
                            {break}
                        {/if}
                    {/foreach}
                {/if}
            </div>
            <div class="col-xs-6">
                {if $input.name=='id_reference'}
                    <div style="text-align: right">
                        <span style="color:#00aff0">&#9660;</span>
                        {l s='You can enter custom value that will be applied to all products.' mod='elegantaleasyimport'}
                        {l s='It will be used if value is missing in import file.' mod='elegantaleasyimport'}
                    </div>
                {elseif $input.name=='id_reference_comb'}
                    {* Nothing *}
                {else}
                    <input type="text" name="default_{$input.name|escape:'html':'UTF-8'}" value="{if isset($model_map_default_values[$input.name])}{$model_map_default_values[$input.name]|escape:'html':'UTF-8'}{/if}" placeholder="{if $input.name == 'action_when_out_of_stock'}WHEN OUT OF STOCK{elseif !in_array($input.name, array('redirect_type_when_offline'))}{$input.label|upper|escape:'html':'UTF-8'}{/if}{if in_array($input.name, array('delete_existing_discount','discount_tax_included','available_for_order','show_price', 'enabled','on_sale','delete_existing_images','delete_existing_features','delete_existing_categories','delete_existing_accessories','customizable','advanced_stock_management','depends_on_stock','delete_product','is_virtual_product','online_only','display_condition','delete_existing_attachments', 'delete_existing_suppliers', 'delete_existing_customize_fields', 'delete_existing_pack_items'))}   1 = Yes   0 = No{elseif in_array($input.name, array('categories_1', 'supplier', 'carriers'))}   Name or ID separated by {$input.multiple_value_separator|escape:'html':'UTF-8'}{elseif in_array($input.name, array('product_images', 'images', 'captions_1', 'tags_1', 'attachments', 'attachment_names_1', 'attachment_descriptions_1', 'supplier_reference', 'supplier_price', 'pack_items_ids', 'pack_items_refs'))}   Multiple values separated by {$input.multiple_value_separator|escape:'html':'UTF-8'}{elseif in_array($input.name, array('tax_rules_group', 'manufacturer'))}   Name or ID{elseif $input.name == 'action_when_out_of_stock'}   0 = Deny  1 = Allow  2 = Default{elseif $input.name=='condition'}   new / used / refurbished{elseif $input.name == 'accessories'}   Reference or ID separated by {$input.multiple_value_separator|escape:'html':'UTF-8'}{elseif $input.name == 'features_1'}   Name:Value:Position:Custom{$input.multiple_value_separator|escape:'html':'UTF-8'}...{elseif $input.name == 'delivery_time'}   0 = None   1 = Default   2 = Specific{elseif $input.name == 'redirect_type_when_offline'}301-category 302-category 301-product 302-product{elseif $input.name == 'shop'}   ID or Name{elseif $input.name == 'convert_image_to'}   jpg / png / gif / webp{elseif $input.name == 'product_type'} standard / pack / virtual / combinations{/if}" title="{l s='You can enter custom value that will be applied to all products.' mod='elegantaleasyimport'} {l s='This value will be used only when you select "Ignore this column" for the mapping or the value is missing in your import file.' mod='elegantaleasyimport'}">
                    {if is_array($languages) && count($languages) > 1}
                        {foreach $multilang_columns as $mcol}
                            {if $input.name == $mcol|cat:'_'|cat:$id_lang_default}
                                {assign var='default_lang_iso_code' value="EN"}
                                {foreach $languages as $language}
                                    {if $language.id_lang != $id_lang_default && $language.active}
                                        {assign var='input_name' value=$mcol|cat:'_'|cat:$language.id_lang}
                                        <input type="text" name="default_{$input_name|escape:'html':'UTF-8'}" value="{if isset($model_map_default_values[$input_name])}{$model_map_default_values[$input_name]|escape:'html':'UTF-8'}{/if}" placeholder="{$input.label|upper|escape:'html':'UTF-8'}" style="display: none; padding-right: 40px;">
                                    {elseif $language.id_lang == $id_lang_default}
                                        {assign var='default_lang_iso_code' value=$language.iso_code|upper|escape:'html':'UTF-8'}
                                    {/if}
                                {/foreach}
                                <div class="elegantal_lang_select">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                        <span>{$default_lang_iso_code|escape:'html':'UTF-8'}</span> <i class="icon-caret-down"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        {foreach $languages as $language}
                                            {if $language.active}
                                                <li {if $language.id_lang == $id_lang_default}class="active" {/if}>
                                                    <a href="#" data-id_lang="{$language.id_lang|escape:'html':'UTF-8'}" data-iso_code="{$language.iso_code|upper|escape:'html':'UTF-8'}">
                                                        {$language.name|escape:'html':'UTF-8'}
                                                    </a>
                                                </li>
                                            {/if}
                                        {/foreach}
                                    </ul>
                                </div>
                                {break}
                            {/if}
                        {/foreach}
                    {/if}
                {/if}
            </div>
        </div>
    {elseif $input.type == 'elegantal_columns_select'}
        <div class="row">
            <div class="col-xs-6 col-md-3">
                <span class="switch prestashop-switch fixed-width-lg">
                    {foreach $input.values as $value}
                        <input type="radio" name="{$input.name|escape:'html':'UTF-8'}" {if $value.value == 1} id="{$input.name|escape:'html':'UTF-8'}_on" {else} id="{$input.name|escape:'html':'UTF-8'}_off" {/if} value="{$value.value|escape:'html':'UTF-8'}" {if isset($fields_value[$input.name]) && $fields_value[$input.name] == $value.value} checked="checked" {/if}{if (isset($input.disabled) && $input.disabled) or (isset($value.disabled) && $value.disabled)} disabled="disabled" {/if} />
                        {strip}
                            <label {if $value.value == 1} for="{$input.name|escape:'html':'UTF-8'}_on" {else} for="{$input.name|escape:'html':'UTF-8'}_off" {/if}>
                                {if $value.value == 1}
                                    {l s='Yes' d='Admin.Global'}
                                {else}
                                    {l s='No' d='Admin.Global'}
                                {/if}
                            </label>
                        {/strip}
                    {/foreach}
                    <a class="slide-button btn"></a>
                </span>
                <i class="icon-sort elegantal_columns_icon_sort"></i>
            </div>
            <div class="col-xs-6 col-md-8">
                {if $input.name=='product_id'}
                    <div>
                        <span style="color:#00aff0">&#9660;</span>
                        {l s='You can enter custom value that will override actual data for the corresponding product property.' mod='elegantaleasyimport'}
                    </div>
                {else}
                    <input type="text" name="default_{$input.name|escape:'html':'UTF-8'}" value="{$input.column_override_value|escape:'html':'UTF-8'}" placeholder="{$input.original_label|escape:'html':'UTF-8'}{if in_array($input.name, ['active', 'available_for_order', 'show_price', 'on_sale', 'default'])} (1=Yes, 0=No){elseif in_array($input.name, ['attribute_names'])} (Name:Type:Position){elseif in_array($input.name, ['attribute_values'])} (Value:Position){elseif strpos($input.name, 'description_') === 0} <HTML>{elseif in_array($input.name, ['images', 'captions', 'features', 'accessories', 'carriers', 'attachments', 'pack_items_refs', 'suppliers', 'supplier_reference', 'supplier_price'])} ({implode($input.multiple_value_separator,['x','y','z'])|escape:'html':'UTF-8'}...){/if}" title="{$input.name|escape:'html':'UTF-8'}">
                {/if}
            </div>
            <input type="text" class="form-control elegantal_columns_label" name="label_{$input.name|escape:'html':'UTF-8'}" value="{$input.label|escape:'html':'UTF-8'}" style="display:none" title="{$input.original_label|escape:'html':'UTF-8'}">
        </div>
    {elseif $input.type == 'elegantal_categories'}
        {$input.categories_tree nofilter}
    {elseif $input.type == 'elegantal_categories_map'}
        <div class="row" style="text-transform: uppercase;">
            <div class="col-xs-5">
                <p><strong>{l s='Category from file' mod='elegantaleasyimport'}</strong></p>
            </div>
            <div class="col-xs-5">
                <p><strong>{l s='Category from shop' mod='elegantaleasyimport'}</strong></p>
            </div>
            <div class="col-xs-2">

            </div>
        </div>
        <div class="row elegantal_categories_map elegantal_categories_map_hidden">
            <div class="col-xs-5">
                <input type="hidden" name="categories_map_file[]" value="">
                <span class="categories_map_file_category_name"></span>
            </div>
            <div class="col-xs-5">
                <input type="hidden" name="categories_map_shop[]" value="">
                <span class="categories_map_shop_category_name"></span>
            </div>
            <div class="col-xs-2">
                <a href="#" class="elegantal_categories_map_edit"><span class="icon-edit"></span></a>
                <a href="#" class="elegantal_categories_map_remove"><span class="icon-remove"></span></a>
            </div>
        </div>
        {foreach $input.selected_categories_map as $category_map}
            <div class="row elegantal_categories_map">
                <div class="col-xs-5">
                    <input type="hidden" name="categories_map_file[]" value="{$category_map.csv_category|escape:'html':'UTF-8'}">
                    <span class="categories_map_file_category_name">
                        {$category_map.csv_category|escape:'html':'UTF-8'}
                    </span>
                </div>
                <div class="col-xs-5">
                    <input type="hidden" name="categories_map_shop[]" value="{$category_map.shop_category_id|escape:'html':'UTF-8'}">
                    {assign var='shop_category_name' value=''}
                    {foreach from=$input.shop_categories key=category_id item=category_name}
                        {if $category_id == $category_map.shop_category_id}
                            {assign var='shop_category_name' value=$category_name}
                            {break}
                        {/if}
                    {/foreach}
                    <span class="categories_map_shop_category_name">
                        {if $shop_category_name}
                            {$shop_category_name|escape:'html':'UTF-8'}
                        {else}
                            {$category_map.shop_category_id|escape:'html':'UTF-8'}
                        {/if}
                    </span>
                </div>
                <div class="col-xs-2">
                    <a href="#" class="elegantal_categories_map_edit"><span class="icon-edit"></span></a>
                    <a href="#" class="elegantal_categories_map_remove"><span class="icon-remove"></span></a>
                </div>
            </div>
        {/foreach}
        <div class="row elegantal_categories_map_chooser">
            <div class="col-xs-5">
                <select name="categories_map_file[]" title="{l s='Select category from file' mod='elegantaleasyimport'}">
                    <option value="" style="color:#aaa">{l s='Select category from file' mod='elegantaleasyimport'}</option>
                    {foreach from=$input.file_categories key=category_id item=category_name}
                        <option value="{$category_id|escape:'html':'UTF-8'}" title="{$category_id|escape:'html':'UTF-8'}">
                            {$category_name|escape:'html':'UTF-8'}
                        </option>
                    {/foreach}
                </select>
            </div>
            <div class="col-xs-5">
                <select name="categories_map_shop[]" title="{l s='Select category from shop' mod='elegantaleasyimport'}">
                    <option value="" style="color:#aaa">{l s='Select category from shop' mod='elegantaleasyimport'}</option>
                    {foreach from=$input.shop_categories key=category_id item=category_name}
                        <option value="{$category_id|escape:'html':'UTF-8'}" title="{$category_id|escape:'html':'UTF-8'}">
                            {$category_name|escape:'html':'UTF-8'}
                        </option>
                    {/foreach}
                </select>
            </div>
            <div class="col-xs-2">
                <button type="button" class="btn btn-info btn-xs add_new_category_map"><i class="icon-check"></i></button>
            </div>
        </div>
        <button type="button" class="btn btn-default category_mapping_for_allowed_categories">
            <i class="icon-sort-amount-asc"></i> {l s='Add mapping for all ALLOWED CATEGORIES' mod='elegantaleasyimport'}
        </button>
    {else}
        {$smarty.block.parent}
    {/if}
{/block}