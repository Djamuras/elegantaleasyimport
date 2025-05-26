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
 * This is a helper class for holding mapping array and some functions related to it
 */
class ElegantalEasyImportMap
{
    /**
     * Default map of fields for products
     *
     * @var array
     */
    private static $defaultMapProducts = [
        'id_reference' => '0',
        'name' => '-1',
        'enabled' => '-1',
        'reference' => '-1',
        'ean' => '-1',
        'upc_barcode' => '-1',
        'isbn' => '-1',
        'mpn' => '-1',
        'meta_title' => '-1',
        'meta_description' => '-1',
        'meta_keywords' => '-1',
        'friendly_url' => '-1',
        'short_description' => '-1',
        'long_description' => '-1',
        'wholesale_price' => '-1',
        'tax_rules_group' => '-1',
        'price_tax_excluded' => '-1',
        'price_tax_included' => '-1',
        'price_currency' => '-1',
        'unit_price' => '-1',
        'unity' => '-1',
        'delete_existing_discount' => '-1',
        'discounted_price' => '-1',
        'discount_type_is_percent' => '-1',
        'discount_amount' => '-1',
        'discount_percent' => '-1',
        'discount_from' => '-1',
        'discount_to' => '-1',
        'discount_tax_included' => '-1',
        'discount_base_price' => '-1',
        'discount_starting_unit' => '-1',
        'discount_customer_group' => '-1',
        'discount_customer_id' => '-1',
        'discount_country' => '-1',
        'discount_currency' => '-1',
        'ecotax' => '-1',
        'advanced_stock_management' => '-1',
        'depends_on_stock' => '-1',
        'warehouse_id' => '-1',
        'location_in_warehouse' => '-1',
        'quantity' => '-1',
        'quantity_add_or_subtract' => '-1',
        'minimal_quantity' => '-1',
        'stock_location' => '-1',
        'low_stock_level' => '-1',
        'email_alert_on_low_stock' => '-1',
        'action_when_out_of_stock' => '-1',
        'text_when_in_stock' => '-1',
        'text_when_backordering' => '-1',
        'availability_date' => '-1',
        'delete_existing_categories' => '-1',
        'categories' => '-1',
        'category_1' => '-1',
        'category_2' => '-1',
        'category_3' => '-1',
        'default_category' => '-1',
        'delete_existing_images' => '-1',
        'default_image' => '-1',
        'product_images' => '-1',
        'image_1' => '-1',
        'image_2' => '-1',
        'image_3' => '-1',
        'captions' => '-1',
        'convert_image_to' => '-1',
        'delete_existing_features' => '-1',
        'features' => '-1',
        'feature_1' => '-1',
        'feature_2' => '-1',
        'feature_3' => '-1',
        'tags' => '-1',
        'delete_existing_accessories' => '-1',
        'accessories' => '-1',
        'accessory_1' => '-1',
        'accessory_2' => '-1',
        'accessory_3' => '-1',
        'delete_existing_attachments' => '-1',
        'attachments' => '-1',
        'attachment_1' => '-1',
        'attachment_2' => '-1',
        'attachment_3' => '-1',
        'attachment_names' => '-1',
        'attachment_name_1' => '-1',
        'attachment_name_2' => '-1',
        'attachment_name_3' => '-1',
        'attachment_descriptions' => '-1',
        'attachment_description_1' => '-1',
        'attachment_description_2' => '-1',
        'attachment_description_3' => '-1',
        'carriers' => '-1',
        'carriers_id_reference' => '-1',
        'manufacturer' => '-1',
        'delete_existing_suppliers' => '-1',
        'supplier' => '-1',
        'supplier_reference' => '-1',
        'supplier_price' => '-1',
        'package_width' => '-1',
        'package_height' => '-1',
        'package_depth' => '-1',
        'package_weight' => '-1',
        'additional_shipping_cost' => '-1',
        'delivery_time' => '-1',
        'delivery_in_stock' => '-1',
        'delivery_out_stock' => '-1',
        'available_for_order' => '-1',
        'show_price' => '-1',
        'on_sale' => '-1',
        'online_only' => '-1',
        'condition' => '-1',
        'display_condition' => '-1',
        'delete_existing_pack_items' => '-1',
        // 'pack_items_ids' => '-1', // Not used yet but it works if enabled
        'pack_items_refs' => '-1',
        'is_virtual_product' => '-1',
        'customizable' => '-1',
        'delete_existing_customize_fields' => '-1',
        'uploadable_files' => '-1',
        'uploadable_files_labels' => '-1',
        'text_fields' => '-1',
        'text_fields_labels' => '-1',
        'visibility' => '-1',
        'product_type' => '-1',
        'shop' => '-1',
        'date_created' => '-1',
        'redirect_type_when_offline' => '-1',
        'redirect_target_category_id' => '-1',
        'delete_product' => '-1',
    ];

    /**
     * Default map of fields for combinations
     *
     * @var array
     */
    private static $defaultMapCombinations = [
        'id_reference' => '0',
        'id_reference_comb' => '-1',
        'combination_id' => '-1',
        'combination_reference' => '-1',
        'combination_ean' => '-1',
        'combination_mpn' => '-1',
        'attribute_names' => '-1',
        'attribute_values' => '-1',
        'attribute_name_1' => '-1',
        'attribute_name_2' => '-1',
        'attribute_name_3' => '-1',
        'attribute_value_1' => '-1',
        'attribute_value_2' => '-1',
        'attribute_value_3' => '-1',
        'color_hex_value' => '-1',
        'color_texture' => '-1',
        'advanced_stock_management' => '-1',
        'depends_on_stock' => '-1',
        'warehouse_id' => '-1',
        'location_in_warehouse' => '-1',
        'quantity' => '-1',
        'quantity_add_or_subtract' => '-1',
        'minimal_quantity' => '-1',
        'stock_location' => '-1',
        'low_stock_level' => '-1',
        'email_alert_on_low_stock' => '-1',
        'wholesale_price' => '-1',
        'combination_price' => '-1',
        'combination_price_tax_incl' => '-1',
        'impact_on_price' => '-1',
        'price_currency' => '-1',
        'impact_on_weight' => '-1',
        'impact_on_unit_price' => '-1',
        'delete_existing_images' => '-1',
        'default_image' => '-1',
        'images' => '-1',
        'image_1' => '-1',
        'image_2' => '-1',
        'image_3' => '-1',
        'captions' => '-1',
        'convert_image_to' => '-1',
        'supplier_reference' => '-1',
        'supplier_price' => '-1',
        'upc' => '-1',
        'isbn' => '-1',
        'ecotax' => '-1',
        'default' => '-1',
        'available_date' => '-1',
        'delete_existing_discount' => '-1',
        'discounted_price' => '-1',
        'discount_amount' => '-1',
        'discount_percent' => '-1',
        'discount_from' => '-1',
        'discount_to' => '-1',
        'discount_tax_included' => '-1',
        'discount_base_price' => '-1',
        'discount_starting_unit' => '-1',
        'discount_customer_group' => '-1',
        'discount_customer_id' => '-1',
        'discount_country' => '-1',
        'discount_currency' => '-1',
        'shop' => '-1',
    ];

    public static function getLabelByKey($key, $module)
    {
        $map = [
            'id_reference' => '<b>' . $module->l('Find products by', 'ElegantalEasyImportMap') . ' "' . (isset($module->model->find_products_by) ? str_replace('_', ' ', $module->model->find_products_by) : '') . '"</b>',
            'id_reference_comb' => '<b>' . $module->l('Find combinations by', 'ElegantalEasyImportMap') . ' "' . (isset($module->model->find_combinations_by) ? str_replace('_', ' ', $module->model->find_combinations_by) : '') . '"</b>',
            'reference' => $module->l('Reference', 'ElegantalEasyImportMap'),
            'name' => $module->l('Product name', 'ElegantalEasyImportMap'),
            'enabled' => $module->l('Enabled (Active)', 'ElegantalEasyImportMap'),
            'ean' => 'EAN',
            'upc_barcode' => 'UPC',
            'isbn' => 'ISBN',
            'mpn' => 'MPN',
            'meta_title' => $module->l('Meta title', 'ElegantalEasyImportMap'),
            'meta_description' => $module->l('Meta description', 'ElegantalEasyImportMap'),
            'meta_keywords' => $module->l('Meta keywords', 'ElegantalEasyImportMap'),
            'friendly_url' => $module->l('Friendly URL', 'ElegantalEasyImportMap'),
            'short_description' => $module->l('Short description (Summary)', 'ElegantalEasyImportMap'),
            'long_description' => $module->l('Long description', 'ElegantalEasyImportMap'),
            'wholesale_price' => $module->l('Cost price (Wholesale price)', 'ElegantalEasyImportMap'),
            'tax_rules_group' => $module->l('Tax rule', 'ElegantalEasyImportMap'),
            'price_tax_excluded' => $module->l('Retail price (tax excl.)', 'ElegantalEasyImportMap'),
            'price_tax_included' => $module->l('Retail price (tax incl.)', 'ElegantalEasyImportMap'),
            'price_currency' => $module->l('Price currency', 'ElegantalEasyImportMap'),
            'unit_price' => $module->l('Unit price', 'ElegantalEasyImportMap'),
            'unity' => $module->l('Unity', 'ElegantalEasyImportMap'),
            'delete_existing_discount' => $module->l('Delete existing discount (Specific Price)', 'ElegantalEasyImportMap'),
            'discounted_price' => $module->l('Discounted price', 'ElegantalEasyImportMap'),
            'discount_type_is_percent' => $module->l('Discount type is percent', 'ElegantalEasyImportMap'),
            'discount_amount' => $module->l('Discount amount', 'ElegantalEasyImportMap'),
            'discount_percent' => $module->l('Discount percent', 'ElegantalEasyImportMap'),
            'discount_from' => $module->l('Discount from', 'ElegantalEasyImportMap'),
            'discount_to' => $module->l('Discount to', 'ElegantalEasyImportMap'),
            'discount_tax_included' => $module->l('Discount tax included', 'ElegantalEasyImportMap'),
            'discount_base_price' => $module->l('Discount base price', 'ElegantalEasyImportMap'),
            'discount_starting_unit' => $module->l('Discount starting unit', 'ElegantalEasyImportMap'),
            'discount_customer_group' => $module->l('Discount customer group', 'ElegantalEasyImportMap'),
            'discount_customer_id' => $module->l('Discount customer ID', 'ElegantalEasyImportMap'),
            'discount_country' => $module->l('Discount country', 'ElegantalEasyImportMap'),
            'discount_currency' => $module->l('Discount currency', 'ElegantalEasyImportMap'),
            'ecotax' => $module->l('Ecotax', 'ElegantalEasyImportMap'),
            'advanced_stock_management' => $module->l('Advanced stock management', 'ElegantalEasyImportMap'),
            'depends_on_stock' => $module->l('Depends on stock', 'ElegantalEasyImportMap'),
            'warehouse_id' => $module->l('Warehouse ID', 'ElegantalEasyImportMap'),
            'location_in_warehouse' => $module->l('Location in warehouse', 'ElegantalEasyImportMap'),
            'quantity' => $module->l('Quantity', 'ElegantalEasyImportMap'),
            'quantity_add_or_subtract' => $module->l('Quantity add or subtract', 'ElegantalEasyImportMap'),
            'minimal_quantity' => $module->l('Minimal quantity', 'ElegantalEasyImportMap'),
            'stock_location' => $module->l('Stock location', 'ElegantalEasyImportMap'),
            'low_stock_level' => $module->l('Low stock level', 'ElegantalEasyImportMap'),
            'email_alert_on_low_stock' => $module->l('Email alert on low stock', 'ElegantalEasyImportMap'),
            'action_when_out_of_stock' => $module->l('Action when out of stock', 'ElegantalEasyImportMap'),
            'text_when_in_stock' => $module->l('Label when in stock', 'ElegantalEasyImportMap'),
            'text_when_backordering' => $module->l('Label when backordering', 'ElegantalEasyImportMap'),
            'availability_date' => $module->l('Availability date', 'ElegantalEasyImportMap'),
            'delete_existing_categories' => $module->l('Delete existing categories', 'ElegantalEasyImportMap'),
            'categories' => $module->l('Categories', 'ElegantalEasyImportMap'),
            'category_1' => $module->l('Category', 'ElegantalEasyImportMap') . ' 1',
            'category_2' => $module->l('Category', 'ElegantalEasyImportMap') . ' 2',
            'category_3' => $module->l('Category', 'ElegantalEasyImportMap') . ' 3',
            'default_category' => $module->l('Default category', 'ElegantalEasyImportMap'),
            'delete_existing_images' => $module->l('Delete existing images', 'ElegantalEasyImportMap'),
            'default_image' => $module->l('Cover Image', 'ElegantalEasyImportMap'),
            'product_images' => $module->l('Images', 'ElegantalEasyImportMap'),
            'image_1' => $module->l('Image', 'ElegantalEasyImportMap') . ' 1',
            'image_2' => $module->l('Image', 'ElegantalEasyImportMap') . ' 2',
            'image_3' => $module->l('Image', 'ElegantalEasyImportMap') . ' 3',
            'captions' => $module->l('Image captions', 'ElegantalEasyImportMap'),
            'convert_image_to' => $module->l('Convert image to', 'ElegantalEasyImportMap'),
            'delete_existing_features' => $module->l('Delete existing features', 'ElegantalEasyImportMap'),
            'features' => $module->l('Features', 'ElegantalEasyImportMap'),
            'feature_1' => $module->l('Feature', 'ElegantalEasyImportMap') . ' 1',
            'feature_2' => $module->l('Feature', 'ElegantalEasyImportMap') . ' 2',
            'feature_3' => $module->l('Feature', 'ElegantalEasyImportMap') . ' 3',
            'tags' => $module->l('Tags', 'ElegantalEasyImportMap'),
            'delete_existing_accessories' => $module->l('Delete existing related products', 'ElegantalEasyImportMap'),
            'accessories' => $module->l('Related products (Accessories)', 'ElegantalEasyImportMap'),
            'accessory_1' => $module->l('Related product', 'ElegantalEasyImportMap') . ' 1',
            'accessory_2' => $module->l('Related product', 'ElegantalEasyImportMap') . ' 2',
            'accessory_3' => $module->l('Related product', 'ElegantalEasyImportMap') . ' 3',
            'delete_existing_attachments' => $module->l('Delete existing attachments', 'ElegantalEasyImportMap'),
            'attachments' => $module->l('Attachments', 'ElegantalEasyImportMap'),
            'attachment_1' => $module->l('Attachment', 'ElegantalEasyImportMap') . ' 1',
            'attachment_2' => $module->l('Attachment', 'ElegantalEasyImportMap') . ' 2',
            'attachment_3' => $module->l('Attachment', 'ElegantalEasyImportMap') . ' 3',
            'attachment_names' => $module->l('Attachment names', 'ElegantalEasyImportMap'),
            'attachment_name_1' => $module->l('Attachment name', 'ElegantalEasyImportMap') . ' 1',
            'attachment_name_2' => $module->l('Attachment name', 'ElegantalEasyImportMap') . ' 2',
            'attachment_name_3' => $module->l('Attachment name', 'ElegantalEasyImportMap') . ' 3',
            'attachment_descriptions' => $module->l('Attachment descriptions', 'ElegantalEasyImportMap'),
            'attachment_description_1' => $module->l('Attachment description', 'ElegantalEasyImportMap') . ' 1',
            'attachment_description_2' => $module->l('Attachment description', 'ElegantalEasyImportMap') . ' 2',
            'attachment_description_3' => $module->l('Attachment description', 'ElegantalEasyImportMap') . ' 3',
            'carriers' => $module->l('Carriers', 'ElegantalEasyImportMap'),
            'carriers_id_reference' => $module->l('Carriers', 'ElegantalEasyImportMap') . ' id_reference',
            'manufacturer' => $module->l('Brand (Manufacturer)', 'ElegantalEasyImportMap'),
            'delete_existing_suppliers' => $module->l('Delete existing suppliers', 'ElegantalEasyImportMap'),
            'supplier' => $module->l('Suppliers', 'ElegantalEasyImportMap'),
            'supplier_reference' => $module->l('Supplier reference', 'ElegantalEasyImportMap'),
            'supplier_price' => $module->l('Supplier price', 'ElegantalEasyImportMap'),
            'package_width' => $module->l('Package width', 'ElegantalEasyImportMap'),
            'package_height' => $module->l('Package height', 'ElegantalEasyImportMap'),
            'package_depth' => $module->l('Package depth', 'ElegantalEasyImportMap'),
            'package_weight' => $module->l('Package weight', 'ElegantalEasyImportMap'),
            'additional_shipping_cost' => $module->l('Additional shipping cost', 'ElegantalEasyImportMap'),
            'delivery_time' => $module->l('Delivery time', 'ElegantalEasyImportMap'),
            'delivery_in_stock' => $module->l('Delivery time if in-stock', 'ElegantalEasyImportMap'),
            'delivery_out_stock' => $module->l('Delivery time if out-of-stock', 'ElegantalEasyImportMap'),
            'available_for_order' => $module->l('Available for order', 'ElegantalEasyImportMap'),
            'show_price' => $module->l('Show price', 'ElegantalEasyImportMap'),
            'on_sale' => $module->l('On sale', 'ElegantalEasyImportMap'),
            'online_only' => $module->l('Web only', 'ElegantalEasyImportMap'),
            'condition' => $module->l('Condition', 'ElegantalEasyImportMap'),
            'display_condition' => $module->l('Display condition', 'ElegantalEasyImportMap'),
            'delete_existing_pack_items' => $module->l('Delete existing pack items', 'ElegantalEasyImportMap'),
            'pack_items_ids' => $module->l('Pack items IDs', 'ElegantalEasyImportMap'),
            'pack_items_refs' => $module->l('Pack items', 'ElegantalEasyImportMap'),
            'is_virtual_product' => $module->l('Is virtual product', 'ElegantalEasyImportMap'),
            'customizable' => $module->l('Customizable', 'ElegantalEasyImportMap'),
            'delete_existing_customize_fields' => $module->l('Delete existing customize fields', 'ElegantalEasyImportMap'),
            'uploadable_files' => $module->l('Customization files count', 'ElegantalEasyImportMap'),
            'uploadable_files_labels' => $module->l('Customization files labels', 'ElegantalEasyImportMap'),
            'text_fields' => $module->l('Customization text fields count', 'ElegantalEasyImportMap'),
            'text_fields_labels' => $module->l('Customization text fields labels', 'ElegantalEasyImportMap'),
            'visibility' => $module->l('Visibility', 'ElegantalEasyImportMap'),
            'product_type' => $module->l('Product type', 'ElegantalEasyImportMap'),
            'shop' => $module->l('Shop', 'ElegantalEasyImportMap'),
            'date_created' => $module->l('Date created', 'ElegantalEasyImportMap'),
            'redirect_type_when_offline' => $module->l('Redirect type when offline', 'ElegantalEasyImportMap'),
            'redirect_target_category_id' => $module->l('Redirect target category ID', 'ElegantalEasyImportMap'),
            'delete_product' => $module->l('Delete product', 'ElegantalEasyImportMap'),
            'combination_id' => $module->l('Combination', 'ElegantalEasyImportMap') . ' ID',
            'combination_reference' => $module->l('Combination', 'ElegantalEasyImportMap') . ' ' . $module->l('Reference', 'ElegantalEasyImportMap'),
            'combination_ean' => $module->l('Combination', 'ElegantalEasyImportMap') . ' EAN',
            'combination_mpn' => $module->l('Combination', 'ElegantalEasyImportMap') . ' MPN',
            'attribute_names' => $module->l('Attribute Names', 'ElegantalEasyImportMap'),
            'attribute_values' => $module->l('Attribute Values', 'ElegantalEasyImportMap'),
            'attribute_name_1' => $module->l('Attribute Name', 'ElegantalEasyImportMap') . ' 1',
            'attribute_name_2' => $module->l('Attribute Name', 'ElegantalEasyImportMap') . ' 2',
            'attribute_name_3' => $module->l('Attribute Name', 'ElegantalEasyImportMap') . ' 3',
            'attribute_value_1' => $module->l('Attribute Value', 'ElegantalEasyImportMap') . ' 1',
            'attribute_value_2' => $module->l('Attribute Value', 'ElegantalEasyImportMap') . ' 2',
            'attribute_value_3' => $module->l('Attribute Value', 'ElegantalEasyImportMap') . ' 3',
            'color_hex_value' => $module->l('Color Hex Value', 'ElegantalEasyImportMap'),
            'color_texture' => $module->l('Color texture', 'ElegantalEasyImportMap'),
            'combination_price' => $module->l('Combination price tax excl', 'ElegantalEasyImportMap'),
            'combination_price_tax_incl' => $module->l('Combination price tax incl', 'ElegantalEasyImportMap'),
            'impact_on_price' => $module->l('Impact on price', 'ElegantalEasyImportMap'),
            'impact_on_weight' => $module->l('Impact on weight', 'ElegantalEasyImportMap'),
            'impact_on_unit_price' => $module->l('Impact on unit price', 'ElegantalEasyImportMap'),
            'images' => $module->l('Images', 'ElegantalEasyImportMap'),
            'default' => $module->l('Default', 'ElegantalEasyImportMap'),
            'available_date' => $module->l('Available Date', 'ElegantalEasyImportMap'),
        ];
        foreach (self::getLangColumns() as $column) {
            if (preg_match('/^' . $column . "_[\d]+$/", $key)) {
                $key = $column;
                break;
            }
        }

        return isset($map[$key]) ? $map[$key] : str_replace('_', ' ', $key);
    }

    /**
     * Returns list of multi-language columns
     *
     * @return string[]
     */
    public static function getLangColumns()
    {
        $multilangColumns = [
            'name',
            'short_description',
            'long_description',
            'meta_title',
            'meta_description',
            'meta_keywords',
            'link_rewrite',
            'friendly_url',
            'text_when_in_stock',
            'text_when_backordering',
            'delivery_in_stock',
            'delivery_out_stock',
            'tags',
            'features',
            'categories',
            'captions',
            'attachment_names',
            'attachment_descriptions',
            'attribute_names',
            'attribute_values',
        ];
        // Add multiple columns that support multilang
        for ($i = 1; $i <= 100; ++$i) {
            $multilangColumns[] = 'feature_' . $i;
            $multilangColumns[] = 'category_' . $i;
            $multilangColumns[] = 'attribute_name_' . $i;
            $multilangColumns[] = 'attribute_value_' . $i;
            $multilangColumns[] = 'attachment_name_' . $i;
            $multilangColumns[] = 'attachment_description_' . $i;
        }
        if (ElegantalEasyImportTools::isModuleInstalled('totcustomfields')) {
            // AND `is_translatable` = 1 not added because we need to have id_lang on input name always
            $totcustomfields = Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . "totcustomfields_input` WHERE `code_object` = 'product' AND `active` = 1");
            if ($totcustomfields) {
                foreach ($totcustomfields as $totcustomfield) {
                    $multilangColumns[] = 'totcustomfields_' . $totcustomfield['code'];
                }
            }
        }
        if (ElegantalEasyImportTools::isModuleInstalled('advancedcustomfields')) {
            // AND `translatable` = 1 not added because we need to have id_lang on input name always
            $advancedcustomfields = Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . "advanced_custom_fields` WHERE `location` = 'product' AND `active` = 1");
            if ($advancedcustomfields) {
                foreach ($advancedcustomfields as $acf) {
                    $multilangColumns[] = 'acf_' . $acf['technical_name'];
                }
            }
        }
        if (ElegantalEasyImportTools::isModuleInstalled('classy_productextratab')) {
            for ($i = 1; $i <= 100; ++$i) {
                $multilangColumns[] = 'classy_productextratab_title_' . $i;
                $multilangColumns[] = 'classy_productextratab_content_' . $i;
            }
        }

        return $multilangColumns;
    }

    public static function getDefaultMapProducts()
    {
        $defaultMapProducts = [];

        // Add multi-language fields
        $multilangColumns = self::getLangColumns();
        $id_lang_default = (int) Configuration::get('PS_LANG_DEFAULT');
        foreach (self::$defaultMapProducts as $key => $value) {
            if (in_array($key, $multilangColumns)) {
                $defaultMapProducts[$key . '_' . $id_lang_default] = $value;
            } else {
                $defaultMapProducts[$key] = $value;
            }
        }

        // Remove unsupported mapping for old PS versions
        if (_PS_VERSION_ < '1.7') {
            unset($defaultMapProducts['isbn']);
            unset($defaultMapProducts['stock_location']);
            unset($defaultMapProducts['low_stock_level']);
            unset($defaultMapProducts['email_alert_on_low_stock']);
        }
        if (_PS_VERSION_ < '1.7.5.0') {
            unset($defaultMapProducts['stock_location']);
        }
        if (_PS_VERSION_ < '1.7.7.0') {
            unset($defaultMapProducts['mpn']);
        }

        // Mapping for 3rd party modules
        if (ElegantalEasyImportTools::isModuleInstalled('fsproductvideo')) {
            $defaultMapProducts['fsproductvideo_url'] = -1;
        }
        if (ElegantalEasyImportTools::isModuleInstalled('additionalproductsorder')) {
            $defaultMapProducts['additionalproductsorder_ids'] = -1;
        }
        if (ElegantalEasyImportTools::isModuleInstalled('jmarketplace')) {
            $defaultMapProducts['jmarketplace_seller_id'] = -1;
        }
        if (ElegantalEasyImportTools::isModuleInstalled('productaffiliate')) {
            $defaultMapProducts['productaffiliate_button_text'] = -1;
            $defaultMapProducts['productaffiliate_external_shop_url'] = -1;
        }
        if (ElegantalEasyImportTools::isModuleInstalled('iqitadditionaltabs')) {
            $defaultMapProducts['iqitadditionaltabs_title'] = -1;
            $defaultMapProducts['iqitadditionaltabs_title_2'] = -1;
            $defaultMapProducts['iqitadditionaltabs_title_3'] = -1;
            $defaultMapProducts['iqitadditionaltabs_description'] = -1;
            $defaultMapProducts['iqitadditionaltabs_description_2'] = -1;
            $defaultMapProducts['iqitadditionaltabs_description_3'] = -1;
        }
        if (ElegantalEasyImportTools::isModuleInstalled('iqitextendedproduct')) {
            $defaultMapProducts['iqitextendedproduct_video_links'] = -1;
        }
        if (ElegantalEasyImportTools::isModuleInstalled('ecm_cmlid')) {
            $defaultMapProducts['ecm_cmlid_xml'] = -1;
        }
        if (ElegantalEasyImportTools::isModuleInstalled('totcustomfields')) {
            // https://addons.prestashop.com/en/additional-information-product-tab/29193-advanced-custom-fields-create-new-fields-quickly.html
            $sql = 'SELECT * FROM `' . _DB_PREFIX_ . "totcustomfields_input` WHERE `code_object` = 'product' AND `active` = 1";
            $totcustomfields = Db::getInstance()->executeS($sql);
            if ($totcustomfields) {
                foreach ($totcustomfields as $totcustomfield) {
                    $defaultMapProducts['totcustomfields_' . $totcustomfield['code'] . '_' . $id_lang_default] = -1; // Need to add id_lang always
                }
            }
        }
        if (ElegantalEasyImportTools::isModuleInstalled('advancedcustomfields')) {
            // https://codecanyon.net/item/advanced-custom-fields-for-prestashop/23001846
            $sql = 'SELECT * FROM `' . _DB_PREFIX_ . "advanced_custom_fields` WHERE `location` = 'product' AND `active` = 1";
            $acfs = Db::getInstance()->executeS($sql);
            if ($acfs && is_array($acfs)) {
                foreach ($acfs as $acf) {
                    $defaultMapProducts['acf_' . $acf['technical_name'] . '_' . $id_lang_default] = -1; // Need to add id_lang always
                }
            }
        }
        if (ElegantalEasyImportTools::isModuleInstalled('pproperties')) {
            // https://addons.prestashop.com/en/sizes-units/5628-product-properties-extension-sell-by-weight-lengthetc.html
            $row = Db::getInstance()->getRow('SELECT * FROM `' . _DB_PREFIX_ . 'product_shop`');
            if (isset($row['quantity_step'])) {
                $defaultMapProducts['pproperties_quantity_step'] = -1;
            }
            if (isset($row['minimal_quantity_fractional'])) {
                $defaultMapProducts['pproperties_minimal_quantity'] = -1;
            }
        }
        if (ElegantalEasyImportTools::isModuleInstalled('advancedstock')) {
            $defaultMapProducts['bms_advancedstock_warehouse'] = -1;
            $defaultMapProducts['bms_advancedstock_physical_quantity'] = -1;
            $defaultMapProducts['bms_advancedstock_available_quantity'] = -1;
            $defaultMapProducts['bms_advancedstock_reserved_quantity'] = -1;
            $defaultMapProducts['bms_advanced_stock_shelf_location'] = -1;
        }
        if (ElegantalEasyImportTools::isModuleInstalled('wkgrocerymanagement')) {
            $defaultMapProducts['wk_measurement_allowed'] = -1;
            $defaultMapProducts['wk_measurement_type'] = -1;
            $defaultMapProducts['wk_measurement_value'] = -1;
            $defaultMapProducts['wk_measurement_unit'] = -1;
            $defaultMapProducts['wk_measurement_units_for_customer'] = -1;
        }
        if (ElegantalEasyImportTools::isModuleInstalled('msrp')) {
            $defaultMapProducts['msrp_price_tax_excl'] = -1;
            $defaultMapProducts['msrp_price_tax_incl'] = -1;
        }
        if (ElegantalEasyImportTools::isModuleInstalled('areapacks')) {
            $defaultMapProducts['areapacks_label'] = -1;
            $defaultMapProducts['areapacks_type'] = -1;
            $defaultMapProducts['areapacks_area'] = -1;
        }
        if (ElegantalEasyImportTools::isModuleInstalled('sldaccessoriestype')) {
            $defaultMapProducts['sld_accessories_type'] = -1;
        }
        if (ElegantalEasyImportTools::isModuleInstalled('aprealestate')) {
            $aprealestate_cols = Db::getInstance()->executeS('SHOW COLUMNS FROM `' . _DB_PREFIX_ . 'aprealestate_product`');
            if ($aprealestate_cols) {
                foreach ($aprealestate_cols as $aprealestate_col) {
                    if (isset($aprealestate_col['Field']) && $aprealestate_col['Field'] && !in_array($aprealestate_col['Field'], ['id_aprealestate_product', 'id_product', 'array_detail'])) {
                        $defaultMapProducts['aprealestate_' . $aprealestate_col['Field']] = -1;
                    }
                }
            }
        }
        if (ElegantalEasyImportTools::isModuleInstalled('classy_productextratab')) {
            // https://classydevs.com/free-modules/classy-product-extra-tab/
            $defaultMapProducts['classy_productextratab_delete_existing'] = -1;
            $defaultMapProducts['classy_productextratab_title_1_' . $id_lang_default] = -1;
            $defaultMapProducts['classy_productextratab_title_2_' . $id_lang_default] = -1;
            $defaultMapProducts['classy_productextratab_title_3_' . $id_lang_default] = -1;
            $defaultMapProducts['classy_productextratab_content_1_' . $id_lang_default] = -1;
            $defaultMapProducts['classy_productextratab_content_2_' . $id_lang_default] = -1;
            $defaultMapProducts['classy_productextratab_content_3_' . $id_lang_default] = -1;
        }

        return $defaultMapProducts;
    }

    public static function getDefaultMapCombinations()
    {
        $defaultMapCombinations = [];

        // Add multi-language fields
        $multilangColumns = self::getLangColumns();
        $id_lang_default = (int) Configuration::get('PS_LANG_DEFAULT');
        foreach (self::$defaultMapCombinations as $key => $value) {
            if (in_array($key, $multilangColumns)) {
                $defaultMapCombinations[$key . '_' . $id_lang_default] = $value;
            } else {
                $defaultMapCombinations[$key] = $value;
            }
        }

        // Remove unsupported mapping for old PS versions
        if (_PS_VERSION_ < '1.7') {
            unset($defaultMapCombinations['stock_location']);
            unset($defaultMapCombinations['low_stock_level']);
            unset($defaultMapCombinations['email_alert_on_low_stock']);
        }
        if (_PS_VERSION_ < '1.7.5.0') {
            unset($defaultMapCombinations['stock_location']);
        }
        if (_PS_VERSION_ < '1.7.7.0') {
            unset($defaultMapCombinations['mpn']);
        }

        // Mapping for 3rd party modules
        if (ElegantalEasyImportTools::isModuleInstalled('ecm_cmlid')) {
            $defaultMapCombinations['ecm_cmlid_xml'] = -1;
        }
        if (ElegantalEasyImportTools::isModuleInstalled('pproperties')) {
            $row = Db::getInstance()->getRow('SELECT * FROM `' . _DB_PREFIX_ . 'product_attribute_shop`');
            if (isset($row['quantity_step'])) {
                $defaultMapCombinations['pproperties_quantity_step'] = -1;
            }
            if (isset($row['minimal_quantity_fractional'])) {
                $defaultMapCombinations['pproperties_minimal_quantity'] = -1;
            }
        }
        if (ElegantalEasyImportTools::isModuleInstalled('advancedstock')) {
            $defaultMapCombinations['bms_advancedstock_warehouse'] = -1;
            $defaultMapCombinations['bms_advancedstock_physical_quantity'] = -1;
            $defaultMapCombinations['bms_advancedstock_available_quantity'] = -1;
            $defaultMapCombinations['bms_advancedstock_reserved_quantity'] = -1;
            $defaultMapCombinations['bms_advanced_stock_shelf_location'] = -1;
        }
        if (ElegantalEasyImportTools::isModuleInstalled('msrp')) {
            $defaultMapCombinations['msrp_price_tax_excl'] = -1;
            $defaultMapCombinations['msrp_price_tax_incl'] = -1;
        }
        if (ElegantalEasyImportTools::isModuleInstalled('areapacks')) {
            $defaultMapCombinations['areapacks_label'] = -1;
            $defaultMapCombinations['areapacks_type'] = -1;
            $defaultMapCombinations['areapacks_area'] = -1;
        }

        return $defaultMapCombinations;
    }
}
