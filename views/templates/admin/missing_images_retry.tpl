{*
* @author    ELEGANTAL <info@elegantal.com>
* @copyright (c) 2025, ELEGANTAL <www.elegantal.com>
* @license   Proprietary License - It is forbidden to resell or redistribute copies of the module or modified copies of the module.
*}
<div class="elegantalBootstrapWrapper">
    <div class="panel">
        <div class="panel-heading">
            <i class="icon-picture-o"></i> {l s='Missing Images Retry' mod='elegantaleasyimport'}
        </div>
        <div class="panel-body">
            <div class="alert alert-info">
                {l s='When an import rule has "Save missing images to retry queue" enabled, unavailable image URLs are saved here and retried later. Product and combination imports continue normally.' mod='elegantaleasyimport'}
                <br>
                {l s='Retry schedule: attempts 1-15 daily, attempts 16-35 every 2 days, attempts 36-60 every 5 days. After 60 failed attempts, the image is removed from the queue.' mod='elegantaleasyimport'}
            </div>

            <p>{l s='Retry CRON URL' mod='elegantaleasyimport'}:</p>
            <div class="well">{$retry_url|escape:'html':'UTF-8'}</div>

            <p>{l s='Example server cron command' mod='elegantaleasyimport'}:</p>
            <div class="well">{$retry_cron_example|escape:'html':'UTF-8'}</div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="alert alert-warning">
                        {l s='Pending missing images' mod='elegantaleasyimport'}: <strong>{$pending_count|intval}</strong>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="alert alert-warning">
                        {l s='Due for retry now' mod='elegantaleasyimport'}: <strong>{$due_count|intval}</strong>
                    </div>
                </div>
            </div>

            <form method="post" action="{$adminUrl|escape:'html':'UTF-8'}&event=missingImagesRetry">
                <div class="panel-footer clearfix">
                    <button type="submit" name="submitRetryMissingImagesNow" class="btn btn-primary pull-right">
                        <i class="process-icon-refresh"></i> {l s='Retry 25 Images Now' mod='elegantaleasyimport'}
                    </button>
                    <a href="{$adminUrl|escape:'html':'UTF-8'}" class="btn btn-default">
                        <i class="process-icon-back"></i> {l s='Back' mod='elegantaleasyimport'}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
