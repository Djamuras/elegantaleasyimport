<?php
/**
 * @author    ELEGANTAL <info@elegantal.com>
 * @copyright (c) 2025, ELEGANTAL <www.elegantal.com>
 * @license   Proprietary License - It is forbidden to resell or redistribute copies of the module or modified copies of the module.
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * This is @deprecated controller for CRON job for import. Use import controller instead.
 */
class ElegantalEasyImportCronModuleFrontController extends ModuleFrontController
{
    /** @var ElegantalEasyImport */
    public $module;

    public function display()
    {
        $url = $this->module->getControllerUrl('import', ['id' => Tools::getValue('id_elegantaleasyimport'), 'secure_key' => Tools::getValue('secure_key')]);
        Tools::redirect($url);
    }
}
