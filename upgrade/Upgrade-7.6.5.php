<?php
/**
 * @author    ELEGANTAL <info@elegantal.com>
 * @copyright (c) 2025, ELEGANTAL <www.elegantal.com>
 * @license   Proprietary License - It is forbidden to resell or redistribute copies of the module or modified copies of the module.
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_7_6_5($module)
{
    unset($module);

    Db::getInstance()->execute('ALTER TABLE ' . _DB_PREFIX_ . 'elegantaleasyimport_history DROP COLUMN `events_log`');

    $sql = 'ALTER TABLE `' . _DB_PREFIX_ . 'elegantaleasyimport_history` ADD `ids_of_products_created` text AFTER `number_of_products_deleted`';
    if (Db::getInstance()->execute($sql) == false) {
        throw new Exception(Db::getInstance()->getMsgError());
    }
    $sql = 'ALTER TABLE `' . _DB_PREFIX_ . 'elegantaleasyimport_history` ADD `ids_of_products_updated` text AFTER `ids_of_products_created`';
    if (Db::getInstance()->execute($sql) == false) {
        throw new Exception(Db::getInstance()->getMsgError());
    }
    $sql = 'ALTER TABLE `' . _DB_PREFIX_ . 'elegantaleasyimport_history` ADD `ids_of_products_deleted` text AFTER `ids_of_products_updated`';
    if (Db::getInstance()->execute($sql) == false) {
        throw new Exception(Db::getInstance()->getMsgError());
    }

    return true;
}
