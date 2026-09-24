<?php
/**
 * @author    ELEGANTAL <info@elegantal.com>
 * @copyright (c) 2025, ELEGANTAL <www.elegantal.com>
 * @license   Proprietary License - It is forbidden to resell or redistribute copies of the module or modified copies of the module.
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_7_6_9($module)
{
    unset($module);

    if (!ElegantalEasyImportMissingImage::install()) {
        throw new Exception(Db::getInstance()->getMsgError());
    }

    return true;
}
