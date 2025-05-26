{*
* @author    ELEGANTAL <info@elegantal.com>
* @copyright (c) 2025, ELEGANTAL <www.elegantal.com>
* @license   Proprietary License - It is forbidden to resell or redistribute copies of the module or modified copies of the module.
*}
<div class="elegantalBootstrapWrapper">
    <div class="panel">
        <div class="panel-heading">
            <i class="icon-warning"></i> {l s='Error log for import rule' mod='elegantaleasyimport'} "{$model.name|escape:'html':'UTF-8'}"
        </div>
        <div class="panel-body">
            {if $errors}
                <div>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>
                                    {l s='Error' mod='elegantaleasyimport'} 
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importHistoryErrors&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}&id_elegantaleasyimport_history={$history_id|intval}&orderBy=error&orderType=desc"><i class="icon-caret-down"></i></a>
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importHistoryErrors&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}&id_elegantaleasyimport_history={$history_id|intval}&orderBy=error&orderType=asc"><i class="icon-caret-up"></i></a>
                                </th>
                                <th class="text-center">
                                    {l s='Product' mod='elegantaleasyimport'} 
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importHistoryErrors&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}&id_elegantaleasyimport_history={$history_id|intval}&orderBy=product_id_reference&orderType=desc"><i class="icon-caret-down"></i></a>
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importHistoryErrors&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}&id_elegantaleasyimport_history={$history_id|intval}&orderBy=product_id_reference&orderType=asc"><i class="icon-caret-up"></i></a>
                                </th>
                                <th class="text-right">
                                    {l s='Date' mod='elegantaleasyimport'} 
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importHistoryErrors&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}&id_elegantaleasyimport_history={$history_id|intval}&orderBy=date_created&orderType=desc"><i class="icon-caret-down"></i></a>
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importHistoryErrors&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}&id_elegantaleasyimport_history={$history_id|intval}&orderBy=date_created&orderType=asc"><i class="icon-caret-up"></i></a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$errors item=error}
                                <tr>
                                    <td>
                                        {$error.error|escape:'html':'UTF-8'}
                                    </td>
                                    <td class="text-center">
                                        {$error.product_id_reference|escape:'html':'UTF-8'}
                                    </td>
                                    <td class="text-right">
                                        {$error.date_created|escape:'html':'UTF-8'|date_format:'%d.%m.%Y %H:%M:%S'}
                                    </td>
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
                                            <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importHistoryErrors&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}&id_elegantaleasyimport_history={$history_id|intval}&orderBy={$orderBy|escape:'html':'UTF-8'}&orderType={$orderType|escape:'html':'UTF-8'}&page=1" aria-label="Previous">
                                                <span aria-hidden="true">&lt;&lt; {l s='First' mod='elegantaleasyimport'}</span>
                                            </a>
                                        </li>
                                        {if $pPrev > 1}
                                            <li>
                                                <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importHistoryErrors&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}&id_elegantaleasyimport_history={$history_id|intval}&orderBy={$orderBy|escape:'html':'UTF-8'}&orderType={$orderType|escape:'html':'UTF-8'}&page={$pPrev|intval}" aria-label="Previous">
                                                    <span aria-hidden="true">&lt; {l s='Prev' mod='elegantaleasyimport'}</span>
                                                </a>
                                            </li>
                                        {/if}
                                    {/if}
                                    {for $i=$pStart to $pages max=$pMax}
                                        <li{if $i == $currentPage} class="active" onclick="return false;"{/if}>
                                            <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importHistoryErrors&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}&id_elegantaleasyimport_history={$history_id|intval}&orderBy={$orderBy|escape:'html':'UTF-8'}&orderType={$orderType|escape:'html':'UTF-8'}&page={$i|intval}">{$i|intval}</a>
                                        </li>
                                    {/for}
                                    {if $pNext > $currentPage && $pNext <= $pages}
                                        {if $pNext < $pages}
                                            <li>
                                                <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importHistoryErrors&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}&id_elegantaleasyimport_history={$history_id|intval}&orderBy={$orderBy|escape:'html':'UTF-8'}&orderType={$orderType|escape:'html':'UTF-8'}&page={$pNext|intval}" aria-label="Next">
                                                    <span aria-hidden="true">{l s='Next' mod='elegantaleasyimport'} &gt;</span>
                                                </a>
                                            </li>
                                        {/if}
                                        <li>
                                            <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importHistoryErrors&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}&id_elegantaleasyimport_history={$history_id|intval}&orderBy={$orderBy|escape:'html':'UTF-8'}&orderType={$orderType|escape:'html':'UTF-8'}&page={$pages|intval}" aria-label="Next">
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
                    {l s='No errors found.' mod='elegantaleasyimport'}
                </div>
            {/if}
        </div>
        <div class="panel-footer clearfix">
            {if $errors}
                <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importHistoryErrorsDeleteAll&id_elegantaleasyimport_history={$history_id|intval}" class="btn btn-default pull-right" onclick="return confirm('Are you sure?')">
                    <i class="process-icon-cancel"></i> &nbsp;{l s='Delete All' mod='elegantaleasyimport'}
                </a>
            {/if}
            <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importHistoryList&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}" class="btn btn-default">
                <i class="process-icon-back"></i> &nbsp;{l s='Back' mod='elegantaleasyimport'}
            </a>
        </div>
    </div>
</div>