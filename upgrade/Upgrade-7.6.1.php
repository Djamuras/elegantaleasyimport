<?php
/**
 * @author    ELEGANTAL <info@elegantal.com>
 * @copyright (c) 2025, ELEGANTAL <www.elegantal.com>
 * @license   Proprietary License - It is forbidden to resell or redistribute copies of the module or modified copies of the module.
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_7_6_1($module)
{
    unset($module);

    $export_rules = Db::getInstance()->executeS('SELECT * FROM ' . _DB_PREFIX_ . 'elegantaleasyimport_export');
    if ($export_rules && is_array($export_rules)) {
        foreach ($export_rules as $rule) {
            $new_columns = [];
            $columns = ElegantalEasyImportTools::unstorable($rule['columns']);
            if ($columns && is_array($columns)) {
                foreach ($columns as $key => $value) {
                    if ($key == 'custom1') {
                        $new_columns['custom_column_1'] = $value;
                    } elseif ($key == 'custom2') {
                        $new_columns['custom_column_2'] = $value;
                    } elseif ($key == 'custom3') {
                        $new_columns['custom_column_3'] = $value;
                    } else {
                        $new_columns[$key] = $value;
                    }
                }
            }

            $new_column_override_values = [];
            $column_override_values = ElegantalEasyImportTools::unstorable($rule['column_override_values']);
            if ($column_override_values && is_array($column_override_values)) {
                foreach ($column_override_values as $key => $value) {
                    if ($key == 'custom1') {
                        $new_column_override_values['custom_column_1'] = $value;
                    } elseif ($key == 'custom2') {
                        $new_column_override_values['custom_column_2'] = $value;
                    } elseif ($key == 'custom3') {
                        $new_column_override_values['custom_column_3'] = $value;
                    } else {
                        $new_column_override_values[$key] = $value;
                    }
                }
            }

            $new_columns = ElegantalEasyImportTools::storable($new_columns);
            $new_column_override_values = ElegantalEasyImportTools::storable($new_column_override_values);
            Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'elegantaleasyimport_export` SET `columns` = \'' . pSQL($new_columns) . '\', `column_override_values` = \'' . pSQL($new_column_override_values) . '\' WHERE `id_elegantaleasyimport_export` = ' . (int) $rule['id_elegantaleasyimport_export']);
        }
    }

    return true;
}
