<?php
/**
 * @author    ELEGANTAL <info@elegantal.com>
 * @copyright (c) 2025, ELEGANTAL <www.elegantal.com>
 * @license   Proprietary License - It is forbidden to resell or redistribute copies of the module or modified copies of the module.
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_7_5_10($module)
{
    unset($module);

    $sql = 'ALTER TABLE `' . _DB_PREFIX_ . 'elegantaleasyimport` MODIFY COLUMN `find_products_by` varchar(50) NOT NULL';
    if (Db::getInstance()->execute($sql) == false) {
        throw new Exception(Db::getInstance()->getMsgError());
    }

    return true;
}
