{*
* @author    ELEGANTAL <info@elegantal.com>
* @copyright (c) 2025, ELEGANTAL <www.elegantal.com>
* @license   Proprietary License - It is forbidden to resell or redistribute copies of the module or modified copies of the module.
*}
<div class="elegantalBootstrapWrapper">
    <div class="panel">
        <div class="panel-heading">
            <i class="icon-time"></i> {l s='Import Queue CRON' mod='elegantaleasyimport'}
        </div>
        <div class="panel-body">
            <div class="alert alert-info">
                {l s='Use one real server cronjob for all supplier imports. Add supplier product and combination CRON URLs below in the order they must run.' mod='elegantaleasyimport'}
                {l s='If a supplier does not have a product import or does not have a combination import, leave that URL field empty.' mod='elegantaleasyimport'}
            </div>

            <div class="alert alert-success">
                <strong>{l s='Why use this queue CRON?' mod='elegantaleasyimport'}</strong>
                <br>
                {l s='It is safer than creating many separate cronjobs because only one import step is triggered at a time.' mod='elegantaleasyimport'}
                {l s='This helps prevent two suppliers from importing products, stock, prices, categories, or combinations at the same time.' mod='elegantaleasyimport'}
                <br><br>
                <strong>{l s='Example' mod='elegantaleasyimport'}:</strong>
                {l s='Supplier A products must be imported before Supplier A combinations. With separate cronjobs, the combination import could start too early. With this queue, Products run first, and Combinations run only after Products are finished.' mod='elegantaleasyimport'}
                <br><br>
                <strong>{l s='Rotation example' mod='elegantaleasyimport'}:</strong>
                {l s='Run 1: Supplier A Products. Run 2: Supplier A Combinations. Run 3: Supplier B Products. Run 4: Supplier B Combinations. Then the queue starts again from Supplier A.' mod='elegantaleasyimport'}
            </div>

            <p>{l s='CRON URL' mod='elegantaleasyimport'}:</p>
            <div class="well">{$queue_cron_url|escape:'html':'UTF-8'}</div>

            <p>{l s='Example server cron command' mod='elegantaleasyimport'}:</p>
            <div class="well">{$queue_cron_example|escape:'html':'UTF-8'}</div>

            <form method="post" action="{$adminUrl|escape:'html':'UTF-8'}&event=importQueueCron">
                <div id="import_queue_suppliers">
                    {foreach from=$suppliers item=supplier}
                        <div class="panel import_queue_supplier">
                            <div class="panel-heading clearfix">
                                <i class="icon-truck"></i> {l s='Supplier' mod='elegantaleasyimport'}
                                <button type="button" class="btn btn-default btn-sm pull-right import_queue_delete_supplier">
                                    <i class="icon-trash"></i> {l s='Delete' mod='elegantaleasyimport'}
                                </button>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label>{l s='Supplier name' mod='elegantaleasyimport'}</label>
                                    <input type="text" name="supplier_name[]" class="form-control" value="{$supplier.name|escape:'html':'UTF-8'}">
                                </div>
                                <div class="form-group">
                                    <label>{l s='Products CRON URL' mod='elegantaleasyimport'}</label>
                                    <input type="text" name="product_url[]" class="form-control" value="{$supplier.product_url|escape:'html':'UTF-8'}">
                                    <p class="help-block">{l s='Leave empty if this supplier has no separate product import.' mod='elegantaleasyimport'}</p>
                                </div>
                                <div class="form-group">
                                    <label>{l s='Combinations CRON URL' mod='elegantaleasyimport'}</label>
                                    <input type="text" name="combination_url[]" class="form-control" value="{$supplier.combination_url|escape:'html':'UTF-8'}">
                                    <p class="help-block">{l s='Leave empty if this supplier has no separate combination import.' mod='elegantaleasyimport'}</p>
                                </div>
                            </div>
                        </div>
                    {/foreach}
                </div>

                <button type="button" id="import_queue_add_supplier" class="btn btn-default">
                    <i class="icon-plus"></i> {l s='Add Supplier' mod='elegantaleasyimport'}
                </button>

                <div class="alert alert-warning" style="margin-top: 15px;">
                    <strong>{l s='Important' mod='elegantaleasyimport'}:</strong>
                    {l s='For suppliers with products and combinations, the queue runs Products first and Combinations second. If one field is empty, that step is skipped.' mod='elegantaleasyimport'}
                </div>

                <div class="panel-footer clearfix">
                    <button type="submit" name="submitSaveImportQueueCron" class="btn btn-primary pull-right">
                        <i class="process-icon-save"></i> {l s='Save Queue' mod='elegantaleasyimport'}
                    </button>
                    <a href="{$adminUrl|escape:'html':'UTF-8'}" class="btn btn-default">
                        <i class="process-icon-back"></i> {l s='Back' mod='elegantaleasyimport'}
                    </a>
                    <button type="submit" name="submitResetImportQueueState" class="btn btn-default" onclick="return confirm(&quot;{l s='Reset queue rotation state?' mod='elegantaleasyimport'}&quot;)">
                        <i class="process-icon-refresh"></i> {l s='Reset Rotation' mod='elegantaleasyimport'}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    (function () {
        var suppliers = document.getElementById('import_queue_suppliers');
        var addButton = document.getElementById('import_queue_add_supplier');

        function bindDeleteButtons() {
            var buttons = suppliers.querySelectorAll('.import_queue_delete_supplier');
            for (var i = 0; i < buttons.length; i++) {
                buttons[i].onclick = function () {
                    var supplierPanel = this;
                    while (supplierPanel && supplierPanel.className.indexOf('import_queue_supplier') === -1) {
                        supplierPanel = supplierPanel.parentNode;
                    }
                    var panels = suppliers.querySelectorAll('.import_queue_supplier');
                    if (panels.length <= 1) {
                        var inputs = supplierPanel.querySelectorAll('input');
                        for (var j = 0; j < inputs.length; j++) {
                            inputs[j].value = '';
                        }
                        return;
                    }
                    supplierPanel.parentNode.removeChild(supplierPanel);
                };
            }
        }

        addButton.onclick = function () {
            var first = suppliers.querySelector('.import_queue_supplier');
            var clone = first.cloneNode(true);
            var inputs = clone.querySelectorAll('input');
            for (var i = 0; i < inputs.length; i++) {
                inputs[i].value = '';
            }
            inputs[0].value = 'Supplier ' + (suppliers.querySelectorAll('.import_queue_supplier').length + 1);
            suppliers.appendChild(clone);
            bindDeleteButtons();
        };

        bindDeleteButtons();
    })();
</script>
