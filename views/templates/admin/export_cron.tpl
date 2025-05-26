{*
* @author    ELEGANTAL <info@elegantal.com>
* @copyright (c) 2025, ELEGANTAL <www.elegantal.com>
* @license   Proprietary License - It is forbidden to resell or redistribute copies of the module or modified copies of the module.
*}
<div class="elegantalBootstrapWrapper">
    <div class="panel">
        <div class="panel-heading">
            <i class="icon-time"></i> {l s='Setup CRON for export' mod='elegantaleasyimport'}
        </div>
        <div class="panel-body">
            <p>{l s='You can use CRON to automatically export products and combinations on scheduled time periods.' mod='elegantaleasyimport'}</p>
            <p>{l s='You will need to create a crontab for the following URL on your hosting server' mod='elegantaleasyimport'}: </p>
            <div class="alert alert-info alert_with_link_icon">
                {$cron_url|escape:'html':'UTF-8'}
            </div>
            {l s='Your CRON command for this rule is' mod='elegantaleasyimport'}: <br>
            <div class="well">curl "{$cron_url|escape:'html':'UTF-8'}" >/dev/null 2>&1</div>
            <div class="well" style="display: none;">/usr/bin/php {$cron_command|escape:'html':'UTF-8'}</div>
            {l s='The following is an example crontab which runs every hour' mod='elegantaleasyimport'}: <br>
            <div class="well">0 * * * * curl "{$cron_url|escape:'html':'UTF-8'}" >/dev/null 2>&1</div>
            <p>
                {l s='Learn more about CRON' mod='elegantaleasyimport'}: <br>
                <a href="https://en.wikipedia.org/wiki/Cron" target="_blank">https://en.wikipedia.org/wiki/Cron</a>
            </p>
            {if $cron_cpanel_doc}
                <p>
                    {l s='Learn how to setup CRON Job in cPanel' mod='elegantaleasyimport'}: <br>
                    <a href="{$cron_cpanel_doc|escape:'html':'UTF-8'}" target="_blank">
                        {l s='User guide on how to setup CRON Job in cPanel' mod='elegantaleasyimport'}
                    </a>
                </p>
            {/if}
            <p>
                {l s='If you do not know how to setup CRON Job on your server, there is another easy way to do this.' mod='elegantaleasyimport'}
                <br>
                {l s='You do not even need to open your cPanel or server. Just use any free or paid online CRON services, for example:' mod='elegantaleasyimport'}
                <a href="https://cron-job.org" target="_blank">https://cron-job.org</a>
                <br>
                {l s='You will select time and put command above (curl "http://....") and this online tool will take care of automatic execution of module.' mod='elegantaleasyimport'}
            </p>
        </div>
        <div class="panel-footer">
            <a href="{$adminUrl|escape:'html':'UTF-8'}&event=exportList" class="btn btn-default">
                <i class="process-icon-back"></i> {l s='Back' mod='elegantaleasyimport'}
            </a>
        </div>
    </div>
</div>