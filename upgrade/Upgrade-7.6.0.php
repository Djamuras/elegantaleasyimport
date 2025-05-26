<?php
/**
 * @author    ELEGANTAL <info@elegantal.com>
 * @copyright (c) 2025, ELEGANTAL <www.elegantal.com>
 * @license   Proprietary License - It is forbidden to resell or redistribute copies of the module or modified copies of the module.
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_7_6_0($module)
{
    unset($module);

    $sql = 'ALTER TABLE `' . _DB_PREFIX_ . 'elegantaleasyimport_export` ADD `xml_root_node` varchar(255) AFTER `root_category_included`';
    if (Db::getInstance()->execute($sql) == false) {
        throw new Exception(Db::getInstance()->getMsgError());
    }
    $sql = 'ALTER TABLE `' . _DB_PREFIX_ . 'elegantaleasyimport_export` ADD `xml_item_node` varchar(255) AFTER `xml_root_node`';
    if (Db::getInstance()->execute($sql) == false) {
        throw new Exception(Db::getInstance()->getMsgError());
    }

    $export_rules = ElegantalEasyImportExport::model()->findAll();
    if ($export_rules) {
        foreach ($export_rules as $export_rule) {
            if ($export_rule['file_format'] != 'xml') {
                continue;
            }
            $model = new ElegantalEasyImportExport($export_rule['id_elegantaleasyimport_export']);
            if (Validate::isLoadedObject($model)) {
                $model_columns = ElegantalEasyImportTools::unstorable($model->columns);
                if ($model_columns) {
                    foreach ($model_columns as $key => $value) {
                        $value = str_replace('-', '', $value);
                        $model_columns[$key] = Tools::strtoupper($value);
                    }
                    $model->columns = ElegantalEasyImportTools::storable($model_columns);
                    $model->update();
                }
            }
        }
    }

    return true;
}
