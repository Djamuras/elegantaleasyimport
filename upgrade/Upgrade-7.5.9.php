<?php
/**
 * @author    ELEGANTAL <info@elegantal.com>
 * @copyright (c) 2025, ELEGANTAL <www.elegantal.com>
 * @license   Proprietary License - It is forbidden to resell or redistribute copies of the module or modified copies of the module.
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_7_5_9($module)
{
    unset($module);

    $sql = 'ALTER TABLE `' . _DB_PREFIX_ . 'elegantaleasyimport_history` ADD `events_log` text AFTER `number_of_products_deleted`';
    if (Db::getInstance()->execute($sql) == false) {
        throw new Exception(Db::getInstance()->getMsgError());
    }

    return true;
}
