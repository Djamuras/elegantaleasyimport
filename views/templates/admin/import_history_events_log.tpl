{*
* @author    ELEGANTAL <info@elegantal.com>
* @copyright (c) 2025, ELEGANTAL <www.elegantal.com>
* @license   Proprietary License - It is forbidden to resell or redistribute copies of the module or modified copies of the module.
*}
<div class="elegantalBootstrapWrapper">
    <div class="panel">
        <div class="panel-heading">
            <i class="icon-list-ul"></i>
            {l s='Import Rule' mod='elegantaleasyimport'} "{$model.name|escape:'html':'UTF-8'}" /
            {$history.date_started|escape:'html':'UTF-8'|date_format:'%d.%m.%Y %H:%M:%S'} - {$history.date_ended|escape:'html':'UTF-8'|date_format:'%d.%m.%Y %H:%M:%S'}
            /
            {if $model.entity == 'combination'}
                {l s='Combinations' mod='elegantaleasyimport'}
            {else}
                {l s='Products' mod='elegantaleasyimport'}
            {/if}
            {if $type == 'updated'}
                {l s='Updated' mod='elegantaleasyimport'}
            {elseif $type == 'deleted'}
                {l s='Deleted' mod='elegantaleasyimport'}
            {else}
                {l s='Created' mod='elegantaleasyimport'}
            {/if}
            : {$total|intval}
        </div>
        <div class="panel-body">
            {if $products}
                <div>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>
                                    {l s='Product ID' mod='elegantaleasyimport'}
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importHistoryEventsLog&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}&id_elegantaleasyimport_history={$history.id_elegantaleasyimport_history|intval}&type={$type|escape:'html':'UTF-8'}&orderBy=p.id_product&orderType=desc"><i class="icon-caret-down"></i></a>
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importHistoryEventsLog&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}&id_elegantaleasyimport_history={$history.id_elegantaleasyimport_history|intval}&type={$type|escape:'html':'UTF-8'}&orderBy=p.id_product&orderType=asc"><i class="icon-caret-up"></i></a>
                                </th>
                                <th>
                                    {l s='Product Reference' mod='elegantaleasyimport'}
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importHistoryEventsLog&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}&id_elegantaleasyimport_history={$history.id_elegantaleasyimport_history|intval}&type={$type|escape:'html':'UTF-8'}&orderBy=p.reference&orderType=desc"><i class="icon-caret-down"></i></a>
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importHistoryEventsLog&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}&id_elegantaleasyimport_history={$history.id_elegantaleasyimport_history|intval}&type={$type|escape:'html':'UTF-8'}&orderBy=p.reference&orderType=asc"><i class="icon-caret-up"></i></a>
                                </th>
                                <th>
                                    {l s='Product EAN' mod='elegantaleasyimport'}
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importHistoryEventsLog&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}&id_elegantaleasyimport_history={$history.id_elegantaleasyimport_history|intval}&type={$type|escape:'html':'UTF-8'}&orderBy=p.ean13&orderType=desc"><i class="icon-caret-down"></i></a>
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importHistoryEventsLog&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}&id_elegantaleasyimport_history={$history.id_elegantaleasyimport_history|intval}&type={$type|escape:'html':'UTF-8'}&orderBy=p.ean13&orderType=asc"><i class="icon-caret-up"></i></a>
                                </th>
                                {if $model.entity == 'combination'}
                                    <th>
                                        {l s='Combination ID' mod='elegantaleasyimport'}
                                        <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importHistoryEventsLog&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}&id_elegantaleasyimport_history={$history.id_elegantaleasyimport_history|intval}&type={$type|escape:'html':'UTF-8'}&orderBy=pa.id_product_attribute&orderType=desc"><i class="icon-caret-down"></i></a>
                                        <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importHistoryEventsLog&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}&id_elegantaleasyimport_history={$history.id_elegantaleasyimport_history|intval}&type={$type|escape:'html':'UTF-8'}&orderBy=pa.id_product_attribute&orderType=asc"><i class="icon-caret-up"></i></a>
                                    </th>
                                    <th>
                                        {l s='Combination Reference' mod='elegantaleasyimport'}
                                        <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importHistoryEventsLog&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}&id_elegantaleasyimport_history={$history.id_elegantaleasyimport_history|intval}&type={$type|escape:'html':'UTF-8'}&orderBy=pa.reference&orderType=desc"><i class="icon-caret-down"></i></a>
                                        <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importHistoryEventsLog&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}&id_elegantaleasyimport_history={$history.id_elegantaleasyimport_history|intval}&type={$type|escape:'html':'UTF-8'}&orderBy=pa.reference&orderType=asc"><i class="icon-caret-up"></i></a>
                                    </th>
                                    <th>
                                        {l s='Combination EAN' mod='elegantaleasyimport'}
                                        <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importHistoryEventsLog&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}&id_elegantaleasyimport_history={$history.id_elegantaleasyimport_history|intval}&type={$type|escape:'html':'UTF-8'}&orderBy=pa.ean13&orderType=desc"><i class="icon-caret-down"></i></a>
                                        <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importHistoryEventsLog&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}&id_elegantaleasyimport_history={$history.id_elegantaleasyimport_history|intval}&type={$type|escape:'html':'UTF-8'}&orderBy=pa.ean13&orderType=asc"><i class="icon-caret-up"></i></a>
                                    </th>
                                {/if}
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$products item=product}
                                <tr>
                                    <td>{$product.id_product|intval}</td>
                                    <td>{$product.reference|escape:'html':'UTF-8'}</td>
                                    <td>{$product.ean13|escape:'html':'UTF-8'}</td>
                                    {if $model.entity == 'combination'}
                                        <td>{$product.id_product_attribute|intval}</td>
                                        <td>{$product.combination_reference|escape:'html':'UTF-8'}</td>
                                        <td>{$product.combination_ean|escape:'html':'UTF-8'}</td>
                                    {/if}
                                </tr>
                            {/foreach}
                        </tbody>
                    </table>
                    {*START PAGINATION*}
                    {if $pages > 1}
                        {assign var="pMax" value=2 * $halfVisibleLinks + 1} {*Number of visible pager links*}
                        {assign var="pStart" value=$currentPage - $halfVisibleLinks} {*Starter link*}
                        {assign var="moveStart" value=$currentPage - $pages + $halfVisibleLinks} {*Numbers that pStart can be moved left to fill right side space*}
                        {if $moveStart > 0}
                            {assign var="pStart" value=$pStart - $moveStart}
                        {/if}
                        {if $pStart < 1}
                            {assign var="pStart" value=1}
                        {/if}
                        {assign var="pNext" value=$currentPage + 1} {*Next page*}
                        {if $pNext > $pages}
                            {assign var="pNext" value=$pages}
                        {/if}
                        {assign var="pPrev" value=$currentPage - 1} {*Previous page*}
                        {if $pPrev < 1}
                            {assign var="pPrev" value=1}
                        {/if}
                        <div class="text-center">
                            <br>
                            <nav>
                                <ul class="pagination pagination-sm">
                                    {if $pPrev < $currentPage}
                                        <li>
                                            <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importHistoryEventsLog&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}&id_elegantaleasyimport_history={$history.id_elegantaleasyimport_history|intval}&type={$type|escape:'html':'UTF-8'}&orderBy={$orderBy|escape:'html':'UTF-8'}&orderType={$orderType|escape:'html':'UTF-8'}&page=1" aria-label="Previous">
                                                <span aria-hidden="true">&lt;&lt; {l s='First' mod='elegantaleasyimport'}</span>
                                            </a>
                                        </li>
                                        {if $pPrev > 1}
                                            <li>
                                                <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importHistoryEventsLog&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}&id_elegantaleasyimport_history={$history.id_elegantaleasyimport_history|intval}&type={$type|escape:'html':'UTF-8'}&orderBy={$orderBy|escape:'html':'UTF-8'}&orderType={$orderType|escape:'html':'UTF-8'}&page={$pPrev|intval}" aria-label="Previous">
                                                    <span aria-hidden="true">&lt; {l s='Prev' mod='elegantaleasyimport'}</span>
                                                </a>
                                            </li>
                                        {/if}
                                    {/if}
                                    {for $i=$pStart to $pages max=$pMax}
                                        <li{if $i == $currentPage} class="active" onclick="return false;" {/if}>
                                            <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importHistoryEventsLog&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}&id_elegantaleasyimport_history={$history.id_elegantaleasyimport_history|intval}&type={$type|escape:'html':'UTF-8'}&orderBy={$orderBy|escape:'html':'UTF-8'}&orderType={$orderType|escape:'html':'UTF-8'}&page={$i|intval}">{$i|intval}</a>
                                            </li>
                                        {/for}
                                        {if $pNext > $currentPage && $pNext <= $pages}
                                            {if $pNext < $pages}
                                                <li>
                                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importHistoryEventsLog&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}&id_elegantaleasyimport_history={$history.id_elegantaleasyimport_history|intval}&type={$type|escape:'html':'UTF-8'}&orderBy={$orderBy|escape:'html':'UTF-8'}&orderType={$orderType|escape:'html':'UTF-8'}&page={$pNext|intval}" aria-label="Next">
                                                        <span aria-hidden="true">{l s='Next' mod='elegantaleasyimport'} &gt;</span>
                                                    </a>
                                                </li>
                                            {/if}
                                            <li>
                                                <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importHistoryEventsLog&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}&id_elegantaleasyimport_history={$history.id_elegantaleasyimport_history|intval}&type={$type|escape:'html':'UTF-8'}&orderBy={$orderBy|escape:'html':'UTF-8'}&orderType={$orderType|escape:'html':'UTF-8'}&page={$pages|intval}" aria-label="Next">
                                                    <span aria-hidden="true">{l s='Last' mod='elegantaleasyimport'} &gt;&gt;</span>
                                                </a>
                                            </li>
                                        {/if}
                                </ul>
                            </nav>
                        </div>
                    {/if}
                    {*END PAGINATION*}
                </div>
            {else}
                <div style="padding: 20px; color: #999; text-align: center; font-size: 22px;">
                    {l s='No records found.' mod='elegantaleasyimport'}
                </div>
            {/if}
        </div>
        <div class="panel-footer clearfix">
            <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importHistoryList&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}" class="btn btn-default">
                <i class="process-icon-back"></i> &nbsp;{l s='Back' mod='elegantaleasyimport'}
            </a>
        </div>
    </div>
</div>