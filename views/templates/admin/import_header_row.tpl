{*
* @author    ELEGANTAL <info@elegantal.com>
* @copyright (c) 2025, ELEGANTAL <www.elegantal.com>
* @license   Proprietary License - It is forbidden to resell or redistribute copies of the module or modified copies of the module.
*}
<div id="elegantal_header_row" title="{l s='This is the header row number. Normally the first row will be header in import file. If your file has no header, make this number 0 here.' mod='elegantaleasyimport'}">
    <div class="elegantal_header_row_title">{l s='Header Row' mod='elegantaleasyimport'}:</div>
    <div class="elegantal_header_row_input_group">
        <div class="input-group">
            <span class="input-group-btn">
                <a class="btn btn-default" href="{$adminUrl|escape:'html':'UTF-8'}&event=importSelectHeaderRow&header_row={$model.header_row|intval - 1}&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}" {if $model.header_row < 1}disabled="disabled"{/if}>-</a>
            </span>
            <input type="text" class="form-control" value="{$model.header_row|intval}" disabled="disabled" readonly>
            <span class="input-group-btn">
                <a class="btn btn-default" href="{$adminUrl|escape:'html':'UTF-8'}&event=importSelectHeaderRow&header_row={$model.header_row|intval + 1}&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}">+</a>
            </span>
        </div>
    </div>
    <a href="#" class="ignore_all_columns">
        <i class="icon-undo" style="font-size: 13px"></i> {l s='Unset all columns' mod='elegantaleasyimport'}
    </a>
</div>