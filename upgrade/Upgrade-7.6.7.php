<?php
/**
 * @author    ELEGANTAL <info@elegantal.com>
 * @copyright (c) 2025, ELEGANTAL <www.elegantal.com>
 * @license   Proprietary License - It is forbidden to resell or redistribute copies of the module or modified copies of the module.
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_7_6_7($module)
{
    if (Db::getInstance()->executeS('SHOW COLUMNS FROM `' . _DB_PREFIX_ . 'elegantaleasyimport` LIKE "find_combinations_by"') == false) {
        $sql = 'ALTER TABLE `' . _DB_PREFIX_ . 'elegantaleasyimport` ADD `find_combinations_by` varchar(50) AFTER `find_products_by`';
        if (Db::getInstance()->execute($sql) == false) {
            throw new Exception(Db::getInstance()->getMsgError());
        }
    }

    if (Db::getInstance()->executeS('SHOW COLUMNS FROM `' . _DB_PREFIX_ . 'elegantaleasyimport_data` LIKE "id_reference_comb"') == false) {
        $sql = 'ALTER TABLE `' . _DB_PREFIX_ . 'elegantaleasyimport_data` ADD `id_reference_comb` varchar(255) AFTER `id_reference`';
        if (Db::getInstance()->execute($sql) == false) {
            throw new Exception(Db::getInstance()->getMsgError());
        }
    }

    $import_rules = ElegantalEasyImportClass::model()->findAll();

    // Update import rules id_reference_comb
    if ($import_rules) {
        foreach ($import_rules as $rule) {
            $model = new ElegantalEasyImportClass($rule['id_elegantaleasyimport']);
            if (Validate::isLoadedObject($model) && $model->entity == 'combination') {
                $map = ElegantalEasyImportTools::unstorable($model->map);
                if ($map && is_array($map) && !isset($map['id_reference_comb'])) {
                    $model->find_combinations_by = empty($model->find_combinations_by) ? 'combination_reference' : $model->find_combinations_by;

                    // Save new map
                    $new_map = [];
                    foreach ($map as $key => $value) {
                        if ($key == 'id_reference') {
                            $new_map[$key] = $value;
                            if (isset($map['combination_id']) && $map['combination_id'] >= 0) {
                                $model->find_combinations_by = 'combination_id';
                                $new_map['id_reference_comb'] = $map['combination_id'];
                            } elseif (isset($map['combination_reference']) && $map['combination_reference'] >= 0) {
                                $model->find_combinations_by = 'combination_reference';
                                $new_map['id_reference_comb'] = $map['combination_reference'];
                            } elseif (isset($map['combination_ean']) && $map['combination_ean'] >= 0) {
                                $model->find_combinations_by = 'combination_ean';
                                $new_map['id_reference_comb'] = $map['combination_ean'];
                            } elseif (isset($map['combination_mpn']) && $map['combination_mpn'] >= 0) {
                                $model->find_combinations_by = 'combination_mpn';
                                $new_map['id_reference_comb'] = $map['combination_mpn'];
                            } elseif (isset($map['supplier_reference']) && $map['supplier_reference'] >= 0) {
                                $model->find_combinations_by = 'combination_supplier_reference';
                                $new_map['id_reference_comb'] = $map['supplier_reference'];
                            } else {
                                $new_map['id_reference_comb'] = -1;
                            }
                        } else {
                            $new_map[$key] = $value;
                        }
                    }
                    $model->map = ElegantalEasyImportTools::storable($new_map);
                    $model->is_processing_now = 0;
                    $model->update();

                    // Update pending _data
                    $data = Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'elegantaleasyimport_data` WHERE `id_elegantaleasyimport` = ' . (int) $model->id . ' AND (`id_reference_comb` IS NULL OR `id_reference_comb` = "")');
                    if ($data && is_array($data)) {
                        foreach ($data as $d) {
                            if ($d['id_reference_comb'] == '' && isset($new_map['id_reference_comb']) && $new_map['id_reference_comb'] >= 0) {
                                $csv_row = ElegantalEasyImportTools::unstorable($d['csv_row']);
                                if (isset($csv_row[$new_map['id_reference_comb']]) && $csv_row[$new_map['id_reference_comb']]) {
                                    Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . "elegantaleasyimport_data` SET `id_reference_comb` = '" . pSQL($csv_row[$new_map['id_reference_comb']]) . "' WHERE `id_elegantaleasyimport_data` = " . (int) $d['id_elegantaleasyimport_data']);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    // Get global settings
    $settings = $module->getSettings();
    $global_rule_ids_for_auto_restart = [];
    $global_rule_ids_for_delete_products = [];
    $global_rule_ids_for_delete_combinations = [];
    if (isset($settings['rule_ids_for_auto_restart_cron_import']) && $settings['rule_ids_for_auto_restart_cron_import']) {
        $global_rule_ids_for_auto_restart = array_filter(array_map('trim', explode(',', preg_replace('/[^0-9,]/', '', $settings['rule_ids_for_auto_restart_cron_import']))), 'strlen');
    }
    if (isset($settings['rule_ids_for_delete_products_not_exist_in_file']) && $settings['rule_ids_for_delete_products_not_exist_in_file']) {
        $global_rule_ids_for_delete_products = array_filter(array_map('trim', explode(',', preg_replace('/[^0-9,]/', '', $settings['rule_ids_for_delete_products_not_exist_in_file']))), 'strlen');
    }
    if (isset($settings['rule_ids_for_delete_combinations_not_exist_in_file']) && $settings['rule_ids_for_delete_combinations_not_exist_in_file']) {
        $global_rule_ids_for_delete_combinations = array_filter(array_map('trim', explode(',', preg_replace('/[^0-9,]/', '', $settings['rule_ids_for_delete_combinations_not_exist_in_file']))), 'strlen');
    }

    // Update import rule other settings
    if ($import_rules) {
        foreach ($import_rules as $rule) {
            $model = new ElegantalEasyImportClass($rule['id_elegantaleasyimport']);
            if (Validate::isLoadedObject($model)) {
                $other_settings = [];

                if ($model->other_settings) {
                    $other_settings = ElegantalEasyImportTools::unstorable($model->other_settings);
                } else {
                    if (($global_rule_ids_for_auto_restart && in_array($model->id, $global_rule_ids_for_auto_restart))
                        || $global_rule_ids_for_delete_products && in_array($model->id, $global_rule_ids_for_delete_products)
                        || $global_rule_ids_for_delete_combinations && in_array($model->id, $global_rule_ids_for_delete_combinations)) {
                        $other_settings = $settings;
                    }
                }

                if ($other_settings) {
                    if (!isset($other_settings['is_auto_restart_cron_after_import_finished'])) {
                        $other_settings['is_auto_restart_cron_after_import_finished'] = 0;
                    }
                    if (!isset($other_settings['is_delete_products_not_exist_in_file'])) {
                        $other_settings['is_delete_products_not_exist_in_file'] = 0;
                    }
                    if (!isset($other_settings['is_delete_combinations_not_exist_in_file'])) {
                        $other_settings['is_delete_combinations_not_exist_in_file'] = 0;
                    }

                    if (isset($other_settings['rule_ids_for_auto_restart_cron_import']) && $other_settings['rule_ids_for_auto_restart_cron_import']) {
                        $rule_ids_for_auto_restart = array_filter(array_map('trim', explode(',', preg_replace('/[^0-9,]/', '', $other_settings['rule_ids_for_auto_restart_cron_import']))), 'strlen');
                        if ($rule_ids_for_auto_restart && in_array($model->id, $rule_ids_for_auto_restart)) {
                            $other_settings['is_auto_restart_cron_after_import_finished'] = 1;
                        }
                    }
                    if (isset($other_settings['rule_ids_for_delete_products_not_exist_in_file']) && $other_settings['rule_ids_for_delete_products_not_exist_in_file']) {
                        $rule_ids_for_delete_products = array_filter(array_map('trim', explode(',', preg_replace('/[^0-9,]/', '', $other_settings['rule_ids_for_delete_products_not_exist_in_file']))), 'strlen');
                        if ($rule_ids_for_delete_products && in_array($model->id, $rule_ids_for_delete_products)) {
                            $other_settings['is_delete_products_not_exist_in_file'] = 1;
                        }
                    }
                    if (isset($other_settings['rule_ids_for_delete_combinations_not_exist_in_file']) && $other_settings['rule_ids_for_delete_combinations_not_exist_in_file']) {
                        $rule_ids_for_delete_combinations = array_filter(array_map('trim', explode(',', preg_replace('/[^0-9,]/', '', $other_settings['rule_ids_for_delete_combinations_not_exist_in_file']))), 'strlen');
                        if ($rule_ids_for_delete_combinations && in_array($model->id, $rule_ids_for_delete_combinations)) {
                            $other_settings['is_delete_combinations_not_exist_in_file'] = 1;
                        }
                    }

                    if (isset($other_settings['rule_ids_for_auto_restart_cron_import'])) {
                        unset($other_settings['rule_ids_for_auto_restart_cron_import']);
                    }
                    if (isset($other_settings['rule_ids_for_delete_products_not_exist_in_file'])) {
                        unset($other_settings['rule_ids_for_delete_products_not_exist_in_file']);
                    }
                    if (isset($other_settings['rule_ids_for_delete_combinations_not_exist_in_file'])) {
                        unset($other_settings['rule_ids_for_delete_combinations_not_exist_in_file']);
                    }

                    $model->other_settings = ElegantalEasyImportTools::storable($other_settings);
                    $model->update();
                }
            }
        }
    }

    // Update settings for each shop
    if (Shop::isFeatureActive()) {
        $shop_groups = Shop::getTree();
        foreach ($shop_groups as $shop_group) {
            foreach ($shop_group['shops'] as $shop) {
                $module->setSetting('is_auto_restart_cron_after_import_finished', 0, $shop['id_shop_group'], $shop['id_shop']);
                $module->setSetting('is_delete_products_not_exist_in_file', 0, $shop['id_shop_group'], $shop['id_shop']);
                $module->setSetting('is_delete_combinations_not_exist_in_file', 0, $shop['id_shop_group'], $shop['id_shop']);
                $module->deleteSetting('rule_ids_for_auto_restart_cron_import', $shop['id_shop_group'], $shop['id_shop']);
                $module->deleteSetting('rule_ids_for_delete_products_not_exist_in_file', $shop['id_shop_group'], $shop['id_shop']);
                $module->deleteSetting('rule_ids_for_delete_combinations_not_exist_in_file', $shop['id_shop_group'], $shop['id_shop']);
            }
        }
    }
    // For all shops
    $module->setSetting('is_auto_restart_cron_after_import_finished', 0, '', '');
    $module->setSetting('is_delete_products_not_exist_in_file', 0, '', '');
    $module->setSetting('is_delete_combinations_not_exist_in_file', 0, '', '');
    $module->deleteSetting('rule_ids_for_auto_restart_cron_import', '', '');
    $module->deleteSetting('rule_ids_for_delete_products_not_exist_in_file', '', '');
    $module->deleteSetting('rule_ids_for_delete_combinations_not_exist_in_file', '', '');

    return true;
}
