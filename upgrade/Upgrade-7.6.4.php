<?php
/**
 * @author    ELEGANTAL <info@elegantal.com>
 * @copyright (c) 2025, ELEGANTAL <www.elegantal.com>
 * @license   Proprietary License - It is forbidden to resell or redistribute copies of the module or modified copies of the module.
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_7_6_4($module)
{
    unset($module);

    $sql = 'ALTER TABLE `' . _DB_PREFIX_ . 'elegantaleasyimport_export` ADD `images_in_separate_columns` tinyint(1) unsigned AFTER `features_in_separate_columns`';
    if (Db::getInstance()->execute($sql) == false) {
        throw new Exception(Db::getInstance()->getMsgError());
    }
    $sql = 'ALTER TABLE `' . _DB_PREFIX_ . 'elegantaleasyimport_export` ADD `add_currency_code_to_prices` tinyint(1) unsigned AFTER `images_in_separate_columns`';
    if (Db::getInstance()->execute($sql) == false) {
        throw new Exception(Db::getInstance()->getMsgError());
    }
    $sql = 'ALTER TABLE `' . _DB_PREFIX_ . 'elegantaleasyimport_export` ADD `add_unit_for_dimensions` tinyint(1) unsigned AFTER `add_currency_code_to_prices`';
    if (Db::getInstance()->execute($sql) == false) {
        throw new Exception(Db::getInstance()->getMsgError());
    }

    return true;
}
