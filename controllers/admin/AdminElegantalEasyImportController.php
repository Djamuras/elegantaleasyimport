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
 * This is controller for admin Menu
 */
class AdminElegantalEasyImportController extends ModuleAdminController
{
    /** @var ElegantalEasyImport */
    public $module;

    public function __construct()
    {
        parent::__construct();

        Tools::redirectAdmin($this->module->getAdminUrl());
    }
}
