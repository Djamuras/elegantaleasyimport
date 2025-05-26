{*
* @author    ELEGANTAL <info@elegantal.com>
* @copyright (c) 2025, ELEGANTAL <www.elegantal.com>
* @license   Proprietary License - It is forbidden to resell or redistribute copies of the module or modified copies of the module.
*}
<div class="elegantalBootstrapWrapper">
    <div class="panel">
        <div class="panel-heading" style="margin-bottom: 5px">
            <i class="icon-refresh"></i> {l s='Step' mod='elegantaleasyimport'} 3: {l s='Import' mod='elegantaleasyimport'} - "{$model.name|escape:'html':'UTF-8'}"
        </div>
        <div class="panel-body">
            <div class="row elegantal_import_panel" data-id="{$model.id_elegantaleasyimport|intval}" data-limit="{$limit|intval}" data-reloadmsg="{l s='Import has not finished yet.' mod='elegantaleasyimport'}">
                <div class="col-xs-12 col-md-offset-2 col-md-8">
                    <div class="bootstrap elegantal_hidden elegantal_error">
                        <div class="module_error alert alert-danger">
                            <span class="elegantal_error_txt"></span>
                        </div>
                    </div>
                    <div class="panel">
                        <div class="panel-heading">
                            <i class="icon-time"></i> 
                            <span class="elegantal_prepare_csv_txt">
                                {l s='Analyzing import file' mod='elegantaleasyimport'}...
                            </span>
                            <span class="elegantal_import_csv_txt elegantal_hidden">
                                {if $model.entity == "combination"}
                                    {l s='Importing combinations' mod='elegantaleasyimport'}...
                                {else}
                                    {l s='Importing products' mod='elegantaleasyimport'}...
                                {/if}
                            </span>
                            <span class="elegantal_import_timer">
                                <span>00:00:01</span>
                            </span>
                        </div>
                        <div class="panel-body">
                            <div class="elegantal_import_stats">
                                <div class="row">
                                    <div class="col-xs-10 col-sm-9">
                                        {if $model.entity == "combination"}
                                            {l s='Total number of combinations from file' mod='elegantaleasyimport'}
                                        {else}
                                            {l s='Total number of products from file' mod='elegantaleasyimport'}
                                        {/if}
                                    </div>
                                    <div class="col-xs-2 col-sm-3 text-right">
                                        <span class="elegantal_import_total_number_of_products">-</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-10 col-sm-9">
                                        {if $model.entity == "combination"}
                                            {l s='Number of combinations processed' mod='elegantaleasyimport'}
                                        {else}
                                            {l s='Number of products processed' mod='elegantaleasyimport'}
                                        {/if}
                                    </div>
                                    <div class="col-xs-2 col-sm-3 text-right">
                                        <span class="elegantal_import_number_of_products_processed">-</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-10 col-sm-9">
                                        {if $model.entity == "combination"}
                                            {l s='Number of new combinations created' mod='elegantaleasyimport'}
                                        {else}
                                            {l s='Number of new products created' mod='elegantaleasyimport'}
                                        {/if}
                                    </div>
                                    <div class="col-xs-2 col-sm-3 text-right">
                                        <span class="elegantal_import_number_of_products_created">-</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-10 col-sm-9">
                                        {if $model.entity == "combination"}
                                            {l s='Number of existing combinations updated' mod='elegantaleasyimport'}
                                        {else}
                                            {l s='Number of existing products updated' mod='elegantaleasyimport'}
                                        {/if}
                                    </div>
                                    <div class="col-xs-2 col-sm-3 text-right">
                                        <span class="elegantal_import_number_of_products_updated">-</span>
                                    </div>
                                </div>
                                <div class="row elegantal_hidden elegantal_import_number_of_products_deleted_block">
                                    <div class="col-xs-10 col-sm-9">
                                        {if $model.entity == "combination"}
                                            {l s='Number of combinations deleted' mod='elegantaleasyimport'}
                                        {else}
                                            {l s='Number of products deleted' mod='elegantaleasyimport'}
                                        {/if}
                                    </div>
                                    <div class="col-xs-2 col-sm-3 text-right">
                                        <span class="elegantal_import_number_of_products_deleted">-</span>
                                    </div>
                                </div>
                                <div class="row elegantal_hidden elegantal_import_errors_found_block">
                                    <div class="col-xs-10 col-sm-9">
                                        <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importHistoryErrors&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}" target="_blank">
                                            {l s='Errors found during import' mod='elegantaleasyimport'}
                                        </a>
                                    </div>
                                    <div class="col-xs-2 col-sm-3 text-right">
                                        <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importHistoryErrors&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}" target="_blank">
                                            <span class="elegantal_import_errors_found">-</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row elegantal_progress_row">
                                <div class="col-xs-12">
                                    <div class="progress">
                                        <div class="elegantal_progress_bar progress-bar" role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100" style="min-width: 3em; width: 0%;" data-finishmsg="{l s='Import Completed' mod='elegantaleasyimport'}">
                                            0%
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row elegantal_result_row">
                                <div class="col-xs-12 text-center">
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}">
                                        <i class="icon-angle-left"></i> {l s='Main Page' mod='elegantaleasyimport'}
                                    </a>&nbsp;&nbsp;&nbsp;&nbsp;
                                    <a href="{$adminUrl|escape:'html':'UTF-8'}&event=importHistoryList&id_elegantaleasyimport={$model.id_elegantaleasyimport|intval}" class="elegantal_log_view_btn" target="_blank">
                                        {l s='View Logs' mod='elegantaleasyimport'} <i class="icon-angle-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="elegantaleasyimportJsDef" data-adminurl="{$adminUrl|escape:'html':'UTF-8'}"></div>
</div>