<?php
/**
 * @author    ELEGANTAL <info@elegantal.com>
 * @copyright (c) 2025, ELEGANTAL <www.elegantal.com>
 * @license   Proprietary License - It is forbidden to resell or redistribute copies of the module or modified copies of the module.
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

class ElegantalEasyImportMissingimagesretryModuleFrontController extends ModuleFrontController
{
    /** @var ElegantalEasyImport */
    public $module;

    public function display()
    {
        $secure_key = $this->module->getSetting('security_token_key');
        if (!$secure_key || Tools::getValue('secure_key') != $secure_key) {
            exit('Access Denied.');
        }

        $limit = Tools::getValue('limit') ? (int) Tools::getValue('limit') : 25;
        $result = ElegantalEasyImportMissingImage::process($limit);
        echo date('d-m-Y H:i:s') . ' Missing images retry completed. ';
        echo 'Checked: ' . (int) $result['checked'] . '. ';
        echo 'Imported: ' . (int) $result['imported'] . '. ';
        echo 'Failed: ' . (int) $result['failed'] . '. ';
        echo 'Deleted: ' . (int) $result['deleted'] . '.';
        exit;
    }
}
