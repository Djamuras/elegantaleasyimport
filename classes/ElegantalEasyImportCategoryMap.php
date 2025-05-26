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
 * This is an object model class used to manage CSV rows saved in database
 */
class ElegantalEasyImportCategoryMap extends ElegantalEasyImportObjectModel
{
    public $tableName = 'elegantaleasyimport_category_map';
    public static $definition = [
        'table' => 'elegantaleasyimport_category_map',
        'primary' => 'id_elegantaleasyimport_category_map',
        'fields' => [
            'id_elegantaleasyimport' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true],
            'type' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true],
            'csv_category' => ['type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true],
            'shop_category_id' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'],
        ],
    ];

    /**
     * Class fields
     *
     * @var mixed
     */
    public $id_elegantaleasyimport_category_map;
    public $id_elegantaleasyimport;
    public $type;
    public $csv_category;
    public $shop_category_id;

    /**
     * Types of the class records
     *
     * @var int
     */
    public static $CATEGORIES_ALLOWED = 1;
    public static $CATEGORIES_DISALLOWED = 2;
    public static $CATEGORIES_MAP = 3;
    public static $MANUFACTURERS_ALLOWED = 4;
    public static $MANUFACTURERS_DISALLOWED = 5;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public static function addCategoriesToTree($categories_tree, $category_names, $multiple_value_separator, $multiple_subcategory_separator)
    {
        if (!is_array($category_names) || empty($category_names)) {
            return $categories_tree;
        }
        $parent_cats = [];
        foreach ($category_names as $category_name) {
            $category_name = trim($category_name);
            if (empty($category_name)) {
                continue;
            }
            if ($multiple_subcategory_separator) {
                $sub_category_names = explode($multiple_subcategory_separator, $category_name);
                $categories_tree = self::addCategoriesToTree($categories_tree, $sub_category_names, $multiple_subcategory_separator, false);
            } else {
                if (empty($parent_cats)) {
                    if (isset($categories_tree[$category_name])) {
                        $parent_cats[] = $category_name;
                    } else {
                        $categories_tree[$category_name] = ['id_category' => $category_name, 'name' => $category_name, 'children' => []];
                        $parent_cats[] = $category_name;
                    }
                } else {
                    $categories_tree_ref = null;
                    foreach ($parent_cats as $parent_cat) {
                        if (!$categories_tree_ref) {
                            $categories_tree_ref = &$categories_tree[$parent_cat];
                        } else {
                            $categories_tree_ref = &$categories_tree_ref['children'][$parent_cat];
                        }
                    }
                    if (isset($categories_tree_ref['children'][$category_name])) {
                        $parent_cats[] = $category_name;
                    } else {
                        $categories_tree_ref['children'][$category_name] = ['id_category' => implode($multiple_value_separator, $parent_cats) . $multiple_value_separator . $category_name, 'name' => $category_name, 'children' => []];
                        $parent_cats[] = $category_name;
                    }
                    unset($categories_tree_ref);
                }
            }
        }

        return $categories_tree;
    }

    public static function getCategoriesFromTree($categories_tree, $level = '-')
    {
        $result = [];
        if (!is_array($categories_tree)) {
            return $result;
        }
        foreach ($categories_tree as $tree) {
            $result[$tree['id_category']] = $level . ($level ? ' ' : '') . $tree['name'];
            if (isset($tree['children']) && is_array($tree['children']) && count($tree['children']) > 0) {
                $result += self::getCategoriesFromTree($tree['children'], $level . '-');
            }
        }

        return $result;
    }

    public static function deleteAllByRule($id_elegantaleasyimport)
    {
        $sql = 'DELETE FROM `' . _DB_PREFIX_ . 'elegantaleasyimport_category_map` WHERE `id_elegantaleasyimport` = ' . (int) $id_elegantaleasyimport;
        if (Db::getInstance()->execute($sql) == false) {
            throw new Exception(Db::getInstance()->getMsgError());
        }

        return true;
    }

    public static function getCategoryMappingByRule($id_elegantaleasyimport)
    {
        $result = [];
        $models = self::model()->findAll([
            'condition' => [
                'id_elegantaleasyimport' => $id_elegantaleasyimport,
            ],
        ]);
        if ($models && is_array($models)) {
            foreach ($models as $model) {
                switch ($model['type']) {
                    case self::$CATEGORIES_ALLOWED:
                        $result['categories_allowed'][] = $model['csv_category'];
                        break;
                    case self::$CATEGORIES_DISALLOWED:
                        $result['categories_disallowed'][] = $model['csv_category'];
                        break;
                    case self::$CATEGORIES_MAP:
                        $result['categories_map'][] = ['csv_category' => $model['csv_category'], 'shop_category_id' => $model['shop_category_id']];
                        break;
                    case self::$MANUFACTURERS_ALLOWED:
                        $result['manufacturers_allowed'][] = $model['csv_category'];
                        break;
                    case self::$MANUFACTURERS_DISALLOWED:
                        $result['manufacturers_disallowed'][] = $model['csv_category'];
                        break;
                    default:
                        break;
                }
            }
        }

        return $result;
    }
}
