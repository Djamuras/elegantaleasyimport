<?php
/**
 * @author    ELEGANTAL <info@elegantal.com>
 * @copyright (c) 2025, ELEGANTAL <www.elegantal.com>
 * @license   Proprietary License - It is forbidden to resell or redistribute copies of the module or modified copies of the module.
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_7_6_3($module)
{
    unset($module);

    $import_rules = ElegantalEasyImportClass::model()->findAll();
    if ($import_rules) {
        foreach ($import_rules as $rule) {
            $model = new ElegantalEasyImportClass($rule['id_elegantaleasyimport']);
            if (Validate::isLoadedObject($model) && $model->entity == 'combination') {
                $new_map = [];
                $map = ElegantalEasyImportTools::unstorable($model->map);
                if ($map && is_array($map)) {
                    foreach ($map as $key => $value) {
                        if (preg_match("/^attribute_([\d]+)_([\d]+)$/", $key)) {
                            $new_map[str_replace('attribute_', 'attribute_value_', $key)] = $value;
                        } else {
                            $new_map[$key] = $value;
                        }
                    }
                }
                if ($new_map) {
                    $model->map = ElegantalEasyImportTools::storable($new_map);
                }

                $new_map_default_values = [];
                $map_default_values = ElegantalEasyImportTools::unstorable($model->map_default_values);
                if ($map_default_values && is_array($map_default_values)) {
                    foreach ($map_default_values as $key => $value) {
                        if (preg_match("/^attribute_([\d]+)_([\d]+)$/", $key)) {
                            $new_map_default_values[str_replace('attribute_', 'attribute_value_', $key)] = $value;
                        } else {
                            $new_map_default_values[$key] = $value;
                        }
                    }
                }
                if ($new_map_default_values) {
                    $model->map_default_values = ElegantalEasyImportTools::storable($new_map_default_values);
                }

                $model->is_processing_now = 0;
                $model->update();
            }
        }
    }

    return true;
}
