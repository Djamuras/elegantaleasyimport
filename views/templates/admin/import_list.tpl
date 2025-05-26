{*
* @author    ELEGANTAL <info@elegantal.com>
* @copyright (c) 2025, ELEGANTAL <www.elegantal.com>
* @license   Proprietary License - It is forbidden to resell or redistribute copies of the module or modified copies of the module.
*}
<div class="elegantalBootstrapWrapper">
    <div class="panel">
        <div class="panel-heading">
            <i class="icon-list"></i> {l s='Import Rules' mod='elegantaleasyimport'}
            <a class="elegantal_panel_heading_version" href="{$adminUrl|escape:'html':'UTF-8'}&event=settings" title="{l s='Settings' mod='elegantaleasyimport'}">
                <span>v</span>{$version|escape:'html':'UTF-8'}
            </a>
        </div>
        <div class="panel-body">
            {if $models}
                <div>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>
                                    ID
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy=t.id_elegantaleasyimport&orderType=desc"><i class="icon-caret-down"></i></a>
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy=t.id_elegantaleasyimport&orderType=asc"><i class="icon-caret-up"></i></a>
                                </th>
                                <th>
                                    {l s='Name' mod='elegantaleasyimport'}
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy=t.name&orderType=desc"><i class="icon-caret-down"></i></a>
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy=t.name&orderType=asc"><i class="icon-caret-up"></i></a>
                                </th>
                                <th class="text-center">
                                    {l s='Import Entity' mod='elegantaleasyimport'}
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy=t.entity&orderType=desc"><i class="icon-caret-down"></i></a>
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy=t.entity&orderType=asc"><i class="icon-caret-up"></i></a>
                                </th>
                                <th class="text-center">
                                    {l s='Import Type' mod='elegantaleasyimport'}
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy=t.is_cron&orderType=desc"><i class="icon-caret-down"></i></a>
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy=t.is_cron&orderType=asc"><i class="icon-caret-up"></i></a>
                                </th>
                                <th class="text-center">
                                    {l s='Last Import' mod='elegantaleasyimport'}
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy=h.date_ended&orderType=desc"><i class="icon-caret-down"></i></a>
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy=h.date_ended&orderType=asc"><i class="icon-caret-up"></i></a>
                                </th>
                                <th class="text-center">
                                    {l s='Status' mod='elegantaleasyimport'}
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy=t.active&orderType=desc"><i class="icon-caret-down"></i></a>
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy=t.active&orderType=asc"><i class="icon-caret-up"></i></a>
                                </th>
                                <th style="min-width:150px">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            {foreach from=$models item=model}
                                <tr>
                                    <td data-csv="{$moduleUrl|escape:'html':'UTF-8'}tmp/{$model.csv_file|escape:'html':'UTF-8'}">
                                        {$model.id_elegantaleasyimport|escape:'html':'UTF-8'}
                                    </td>
                                    <td>
                                        {$model.name|escape:'html':'UTF-8'}
                                    </td>
                                    <td class="text-center">
                                        {$model.entity|ucfirst|escape:'html':'UTF-8'}
                                    </td>
                                    <td class="text-center">
                                        {if $model.is_cron}
                                            CRON
                                        {else}
                                            {l s='Manual' mod='elegantaleasyimport'}
                                        {/if}
                                    </td>
                                    <td class="text-center">
                                        {if $model.date_ended}
                                            {$model.date_ended|escape:'html':'UTF-8'|date_format:'%e %b %Y %H:%M:%S'}
                                            {if $model.total_number_of_products > 0 && $model.total_number_of_products > $model.number_of_products_processed}
                                                {assign var=finished_percent value=($model.number_of_products_processed * 100) / $model.total_number_of_products}
                                            {else}
                                                {assign var=finished_percent value=100}
                                            {/if}
                                            <span class="label {if $finished_percent < 100}label-warning{else}label-success{/if} progress_label">
                                                {if $finished_percent < 100}
                                                    {l s='In Progress' mod='elegantaleasyimport'}
                                                {else}
                                                    {l s='Completed' mod='elegantaleasyimport'}
                                                {/if}
                                                {$finished_percent|intval}%
                                            </span>
                                        {else}
                                            -
                                        {/if}
                                    </td>
                                    <td class="text-center">
                                        {if $model.active}
                                            <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importChangeStatus&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}">
                                                <i class="icon-check" style="color: #72C279"></i>
                                            </a>
                                        {else}
                                            <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importChangeStatus&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}">
                                                <i class="icon-remove" style="color: #E08F95"></i>
                                            </a>
                                        {/if}
                                    </td>
                                    <td class="text-right">
                                        <div class="btn-group" role="group">
                                            <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importEdit&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}" class="btn btn-sm btn-default">
                                                <i class="icon-edit"></i> {l s='Edit Rule' mod='elegantaleasyimport'}
                                            </a>
                                            <a href="" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="caret"></span>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                {if $model.map && $model.is_cron}
                                                    <li>
                                                        <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importCronInfo&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}">
                                                            <i class="icon-time"></i> {l s='Setup CRON Job' mod='elegantaleasyimport'}
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{$adminUrl|escape:'html':'UTF-8'}&event=triggerCron&type=import&id={$model.id_elegantaleasyimport|intval}">
                                                            <i class="icon-dot-circle-o"></i> {l s='Trigger CRON Now' mod='elegantaleasyimport'}
                                                        </a>
                                                    </li>
                                                {/if}
                                                <li>
                                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importMapping&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}">
                                                        <i class="icon-random"></i> {l s='Edit Mapping' mod='elegantaleasyimport'}
                                                    </a>
                                                </li>
                                                {if $model.map && $model.is_categories_mapped}
                                                    <li>
                                                        <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importManageCategory&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}">
                                                            <i class="icon-edit"></i> {l s='Manage Category' mod='elegantaleasyimport'}
                                                        </a>
                                                    </li>
                                                {/if}
                                                {if $model.map && !$model.is_cron && $model.date_ended}
                                                    <li>
                                                        <a href="{$adminUrl|escape:'html':'UTF-8'}&event=import&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}">
                                                            <i class="icon-repeat"></i> {l s='Repeat Last Import' mod='elegantaleasyimport'}
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importLatestFile&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}">
                                                            <i class="icon-repeat"></i> {l s='Import Latest File' mod='elegantaleasyimport'}
                                                        </a>
                                                    </li>
                                                {/if}
                                                {if $model.map && $model.is_cron && $model.date_ended}
                                                    <li>
                                                        <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importRestart&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}">
                                                            <i class="icon-repeat"></i> {l s='Restart Import' mod='elegantaleasyimport'}
                                                        </a>
                                                    </li>
                                                {/if}
                                                {if $model.date_ended}
                                                    <li>
                                                        <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importHistoryList&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}">
                                                            <i class="icon-list-ul"></i> {l s='Import Logs' mod='elegantaleasyimport'}
                                                        </a>
                                                    </li>
                                                {/if}
                                                <li>
                                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importOtherSettings&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}">
                                                        <i class="icon-cogs"></i> {l s='Other Settings' mod='elegantaleasyimport'}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importDuplicate&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}">
                                                        <i class="icon-copy"></i> {l s='Duplicate' mod='elegantaleasyimport'}
                                                    </a>
                                                </li>
                                                {if $model.active == 1}
                                                    <li>
                                                        <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importChangeStatus&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}">
                                                            <i class="icon-off"></i> {l s='Disable' mod='elegantaleasyimport'}
                                                        </a>
                                                    </li>
                                                {else}
                                                    <li>
                                                        <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importChangeStatus&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}">
                                                            <i class="icon-off"></i> {l s='Enable' mod='elegantaleasyimport'}
                                                        </a>
                                                    </li>
                                                {/if}
                                                <li>
                                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importDelete&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}" onclick="return confirm('Are you sure?')">
                                                        <i class="icon-trash"></i> {l s='Delete' mod='elegantaleasyimport'}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
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
                                            <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy={$orderBy|escape:'html':'UTF-8'}&orderType={$orderType|escape:'html':'UTF-8'}&page=1" aria-label="Previous">
                                                <span aria-hidden="true">&lt;&lt; {l s='First' mod='elegantaleasyimport'}</span>
                                            </a>
                                        </li>
                                        {if $pPrev > 1}
                                            <li>
                                                <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy={$orderBy|escape:'html':'UTF-8'}&orderType={$orderType|escape:'html':'UTF-8'}&page={$pPrev|intval}" aria-label="Previous">
                                                    <span aria-hidden="true">&lt; {l s='Prev' mod='elegantaleasyimport'}</span>
                                                </a>
                                            </li>
                                        {/if}
                                    {/if}
                                    {for $i=$pStart to $pages max=$pMax}
                                        <li{if $i == $currentPage} class="active" onclick="return false;" {/if}>
                                            <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy={$orderBy|escape:'html':'UTF-8'}&orderType={$orderType|escape:'html':'UTF-8'}&page={$i|intval}">{$i|intval}</a>
                                            </li>
                                        {/for}
                                        {if $pNext > $currentPage && $pNext <= $pages}
                                            {if $pNext < $pages}
                                                <li>
                                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy={$orderBy|escape:'html':'UTF-8'}&orderType={$orderType|escape:'html':'UTF-8'}&page={$pNext|intval}" aria-label="Next">
                                                        <span aria-hidden="true">{l s='Next' mod='elegantaleasyimport'} &gt;</span>
                                                    </a>
                                                </li>
                                            {/if}
                                            <li>
                                                <a href="{$adminUrl|escape:'html':'UTF-8'}&orderBy={$orderBy|escape:'html':'UTF-8'}&orderType={$orderType|escape:'html':'UTF-8'}&page={$pages|intval}" aria-label="Next">
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
                    {l s='You have not created import rules yet.' mod='elegantaleasyimport'}
                </div>
            {/if}
        </div>
        <div class="panel-footer clearfix elegantal_panel_footer">
            <div class="btn-group btn_group_primary">
                <button type="button" class="btn btn-primary btn-lg dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="icon-cloud-download"></i> {l s='New Import' mod='elegantaleasyimport'} <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li>
                        <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importEdit&import_entity=product">
                            {l s='Import products' mod='elegantaleasyimport'}
                        </a>
                    </li>
                    <li>
                        <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importEdit&import_entity=combination">
                            {l s='Import combinations' mod='elegantaleasyimport'}
                        </a>
                    </li>
                </ul>
            </div>
            <a href="{$adminUrl|escape:'html':'UTF-8'}&event=exportList" class="btn btn-default btn-lg">
                <i class="icon-cloud-upload"></i> {l s='Export Rules' mod='elegantaleasyimport'}
            </a>
            <a href="{$adminUrl|escape:'html':'UTF-8'}&event=restoreModule" class="btn btn-default btn-lg">
                <i class="icon-save"></i> {l s='Backup/Restore' mod='elegantaleasyimport'}
            </a>
            <div class="btn-group">
                <button type="button" class="btn btn-default btn-lg dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="icon-file-text-o"></i> {l s='Sample Files' mod='elegantaleasyimport'} <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" style="left: 0; right: auto;">
                    {foreach from=$documentationUrls key=docName item=documentationUrl}
                        <li>
                            <a href="{$documentationUrl|escape:'html':'UTF-8'}" target="_blank">
                                {$docName|escape:'html':'UTF-8'}
                            </a>
                        </li>
                    {/foreach}
                </ul>
            </div>
            <a href="{$rateModuleUrl|escape:'html':'UTF-8'}" target="_blank" class="btn btn-default btn-lg">
                <i class="icon-thumbs-o-up"></i> {l s='Rate Module' mod='elegantaleasyimport'}
            </a>
            <a href="{$contactDeveloperUrl|escape:'html':'UTF-8'}" target="_blank" class="btn btn-default btn-lg">
                <i class="icon-envelope-o"></i> {l s='Contact Developer' mod='elegantaleasyimport'}
            </a>
        </div>
    </div>
</div>