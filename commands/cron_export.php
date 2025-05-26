<?php
/**
 * @author    ELEGANTAL <info@elegantal.com>
 * @copyright (c) 2025, ELEGANTAL <www.elegantal.com>
 * @license   Proprietary License - It is forbidden to resell or redistribute copies of the module or modified copies of the module.
 */
error_reporting(0);

require_once dirname(__FILE__) . '/../../../config/config.inc.php';
require_once dirname(__FILE__) . '/../elegantaleasyimport.php';
require_once dirname(__FILE__) . '/../controllers/front/export.php';

if (!defined('_PS_VERSION_')) {
    exit;
}

$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['HTTPS'] = Configuration::get('PS_SSL_ENABLED') ? 'on' : null;
$_GET['module'] = 'elegantaleasyimport';

$controller = new ElegantalEasyImportExportModuleFrontController();
$controller->ssl = Configuration::get('PS_SSL_ENABLED') ? true : false;
$controller->init();

$module = new ElegantalEasyImport();

$_GET['secure_key'] = $module->getSetting('security_token_key');
if (!Tools::getValue('secure_key')) {
    exit('Token key is wrong.');
}

$_GET['id'] = (isset($_SERVER['argv'][1]) && $_SERVER['argv'][1]) ? $_SERVER['argv'][1] : (Tools::getValue('id') ? Tools::getValue('id') : null);
if (!Tools::getValue('id')) {
    exit('ID is required.');
}

$controller->display();

exit;
