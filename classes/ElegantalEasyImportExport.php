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
 * This is an object model class used to manage export rules
 */
class ElegantalEasyImportExport extends ElegantalEasyImportObjectModel
{
    public $tableName = 'elegantaleasyimport_export';
    public static $definition = [
        'table' => 'elegantaleasyimport_export',
        'primary' => 'id_elegantaleasyimport_export',
        'multishop' => true,
        'fields' => [
            'name' => ['type' => self::TYPE_STRING, 'size' => 255, 'validate' => 'isString', 'required' => true],
            'entity' => ['type' => self::TYPE_STRING, 'size' => 25, 'validate' => 'isString', 'required' => true],
            'columns' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'column_override_values' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'file_path' => ['type' => self::TYPE_STRING, 'size' => 255, 'validate' => 'isString', 'required' => true],
            'file_format' => ['type' => self::TYPE_STRING, 'size' => 10, 'validate' => 'isString', 'required' => true],
            'is_compress_file_to_zip' => ['type' => self::TYPE_BOOL, 'validate' => 'isUnsignedInt'],
            'csv_delimiter' => ['type' => self::TYPE_STRING, 'size' => 5],
            'multiple_value_separator' => ['type' => self::TYPE_STRING, 'size' => 5, 'required' => true],
            'multiple_subcategory_separator' => ['type' => self::TYPE_STRING, 'size' => 5],
            'currency_id' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true],
            'shop_ids' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'category_ids' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'disallowed_category_ids' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'supplier_ids' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'manufacturer_ids' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'exclude_product_ids' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'export_products_updated_within_minute' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'],
            'product_status' => ['type' => self::TYPE_BOOL, 'validate' => 'isUnsignedInt'],
            'price_modifier' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'price_range' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'quantity_range' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'warehouse_ids' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'features_in_separate_columns' => ['type' => self::TYPE_BOOL, 'validate' => 'isUnsignedInt'],
            'images_in_separate_columns' => ['type' => self::TYPE_BOOL, 'validate' => 'isUnsignedInt'],
            'add_currency_code_to_prices' => ['type' => self::TYPE_BOOL, 'validate' => 'isUnsignedInt'],
            'add_unit_for_dimensions' => ['type' => self::TYPE_BOOL, 'validate' => 'isUnsignedInt'],
            'root_category_included' => ['type' => self::TYPE_BOOL, 'validate' => 'isUnsignedInt'],
            'xml_root_node' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'xml_item_node' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'order_by' => ['type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true],
            'order_direction' => ['type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true],
            'last_export_date' => ['type' => self::TYPE_DATE, 'validate' => 'isDate'],
            'email_to_send_notification' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'active' => ['type' => self::TYPE_BOOL, 'validate' => 'isUnsignedInt'],
        ],
    ];

    /**
     * Class fields
     *
     * @var mixed
     */
    public $id_elegantaleasyimport_export;
    public $name;
    public $entity;
    public $columns;
    public $column_override_values;
    public $file_path;
    public $file_format;
    public $is_compress_file_to_zip;
    public $csv_delimiter;
    public $multiple_value_separator;
    public $multiple_subcategory_separator;
    public $currency_id;
    public $shop_ids;
    public $category_ids;
    public $disallowed_category_ids;
    public $supplier_ids;
    public $manufacturer_ids;
    public $exclude_product_ids;
    public $export_products_updated_within_minute;
    public $product_status;
    public $price_modifier;
    public $price_range;
    public $quantity_range;
    public $warehouse_ids;
    public $features_in_separate_columns;
    public $images_in_separate_columns;
    public $add_currency_code_to_prices;
    public $add_unit_for_dimensions;
    public $root_category_included;
    public $xml_root_node;
    public $xml_item_node;
    public $order_by;
    public $order_direction;
    public $last_export_date;
    public $email_to_send_notification;
    public $active;

    /**
     * Export data columns for products
     *
     * @var array
     */
    public static $columnsProduct = [
        'product_id' => 'Product ID',
        'reference' => 'Reference',
        'name' => 'Product Name',
        'active' => 'Active',
        'description_short' => 'Short Description',
        'description' => 'Long Description',
        'meta_title' => 'Meta Title',
        'meta_description' => 'Meta Description',
        'meta_keywords' => 'Meta Keywords',
        'link_rewrite' => 'Friendly URL',
        'price_tax_excluded' => 'Retail Price Tax Exc',
        'price_tax_included' => 'Retail Price Tax Inc',
        'discounted_price_tax_excluded' => 'Discounted Price Tax Exc',
        'discounted_price_tax_included' => 'Discounted Price Tax Inc',
        'discounted_price_tax_excluded_if_discount_exists' => 'Discounted Price Tax Exc If Discount Exists',
        'discounted_price_tax_included_if_discount_exists' => 'Discounted Price Tax Inc If Discount Exists',
        'discount_percent' => 'Discount Percent',
        'discount_amount' => 'Discount Amount',
        'discount_base_price' => 'Discount Base Price',
        'discount_starting_unit' => 'Discount Starting Unit',
        'discount_from' => 'Discount from',
        'discount_to' => 'Discount to',
        'wholesale_price' => 'Cost price (Wholesale price)',
        'unit_price' => 'Unit Price',
        'unity' => 'Unity',
        'tax_rule' => 'Tax Rule',
        'quantity' => 'Quantity',
        'stock_availability' => 'Stock Availability',
        'minimal_quantity' => 'Minimal Quantity',
        'stock_location' => 'Stock Location',
        'low_stock_level' => 'Low Stock Level',
        'email_alert_on_low_stock' => 'Email Alert on Low Stock',
        'manufacturer' => 'Brand (Manufacturer)',
        'suppliers' => 'Suppliers',
        'supplier_reference' => 'Supplier References',
        'supplier_price' => 'Supplier Prices',
        'default_category' => 'Default Category',
        'categories' => 'Categories',
        'cover_image' => 'Cover Image URL',
        'images' => 'Images',
        'captions' => 'Image Captions',
        'features' => 'Features',
        'accessories' => 'Related products (Accessories)',
        'carriers' => 'Carriers',
        'tags' => 'Tags',
        'attachments' => 'Attachments',
        'attachment_names' => 'Attachment Names',
        'attachment_descriptions' => 'Attachment Descriptions',
        'pack_items_refs' => 'Pack Items',
        'url' => 'Product URL',
        'visibility' => 'Visibility',
        'available_for_order' => 'Available for order',
        'show_price' => 'Show Price',
        'on_sale' => 'On Sale',
        'condition' => 'Condition',
        'ean' => 'EAN',
        'upc' => 'UPC',
        'isbn' => 'ISBN',
        'mpn' => 'MPN',
        'width' => 'Width',
        'height' => 'Height',
        'depth' => 'Depth',
        'weight' => 'Weight',
        'action_when_out_of_stock' => 'Action when out of stock',
        'text_when_in_stock' => 'Label when in stock',
        'text_when_backorder' => 'Label when backorder',
        'availability_date' => 'Availability date',
        'delivery_time' => 'Delivery time',
        'delivery_in_stock' => 'Delivery time if in-stock',
        'delivery_out_stock' => 'Delivery time if out-of-stock',
        'date_created' => 'Date created',
        'shop_id' => 'Shop ID',
        'shop_name' => 'Shop Name',
        'custom_column_1' => 'Custom Column 1',
        'custom_column_2' => 'Custom Column 2',
        'custom_column_3' => 'Custom Column 3',
    ];

    /**
     * Export data columns for products
     *
     * @var array
     */
    public static $columnsCombination = [
        'product_id' => 'Product ID',
        'product_reference' => 'Product Reference',
        'combination_id' => 'Combination ID',
        'combination_reference' => 'Combination Reference',
        'attribute_names' => 'Attribute Names',
        'attribute_values' => 'Attribute Values',
        'attribute_colors' => 'Attribute Colors (Hex)',
        'attribute_textures' => 'Attribute Textures (URLs)',
        'supplier_reference' => 'Supplier Reference',
        'supplier_price' => 'Supplier Price',
        'ean' => 'EAN',
        'images' => 'Images',
        'price_tax_excluded' => 'Retail Price Tax Exc',
        'price_tax_included' => 'Retail Price Tax Inc',
        'discounted_price_tax_excluded' => 'Discounted Price Tax Exc',
        'discounted_price_tax_included' => 'Discounted Price Tax Inc',
        'discounted_price_tax_excluded_if_discount_exists' => 'Discounted Price Tax Exc If Discount Exists',
        'discounted_price_tax_included_if_discount_exists' => 'Discounted Price Tax Inc If Discount Exists',
        'discount_percent' => 'Discount Percent',
        'discount_amount' => 'Discount Amount',
        'discount_base_price' => 'Discount Base Price',
        'discount_starting_unit' => 'Discount Starting Unit',
        'discount_from' => 'Discount from',
        'discount_to' => 'Discount to',
        'wholesale_price' => 'Cost price (Wholesale price)',
        'impact_on_price' => 'Impact on Price',
        'impact_on_price_per_unit' => 'Impact on Price per Unit',
        'ecotax' => 'Ecotax',
        'quantity' => 'Quantity',
        'stock_availability' => 'Stock Availability',
        'minimal_quantity' => 'Minimal Quantity',
        'stock_location' => 'Stock location',
        'low_stock_level' => 'Low stock level',
        'email_alert_on_low_stock' => 'Email alert on low stock',
        'available_date' => 'Available Date',
        'impact_on_weight' => 'Impact on Weight',
        'default' => 'Default',
        'isbn' => 'ISBN',
        'upc' => 'UPC',
        'mpn' => 'MPN',
        'width' => 'Width',
        'height' => 'Height',
        'depth' => 'Depth',
        'weight' => 'Weight',
        'url' => 'Combination URL',
        'shop_id' => 'Shop ID',
        'shop_name' => 'Shop name',
        // Product data
        'product_name' => 'Product Name',
        'product_active' => 'Active',
        'product_description_short' => 'Short Description',
        'product_description' => 'Long Description',
        'product_link_rewrite' => 'Friendly URL',
        'product_price_tax_excluded' => 'Product Price Tax Exc',
        'product_price_tax_included' => 'Product Price Tax Inc',
        'product_discounted_price_tax_excluded' => 'Product Discounted Price Tax Exc',
        'product_discounted_price_tax_included' => 'Product Discounted Price Tax Inc',
        'product_discounted_price_tax_excluded_if_discount_exists' => 'Product Discounted Price Tax Exc If Discount Exists',
        'product_discounted_price_tax_included_if_discount_exists' => 'Product Discounted Price Tax Inc If Discount Exists',
        'product_discount_percent' => 'Product Discount Percent',
        'product_discount_amount' => 'Product Discount Amount',
        'product_discount_base_price' => 'Product Discount Base Price',
        'product_discount_starting_unit' => 'Product Discount Starting Unit',
        'product_discount_from' => 'Product Discount from',
        'product_discount_to' => 'Product Discount to',
        'product_tax_rule' => 'Tax Rule',
        'product_manufacturer' => 'Brand',
        'product_suppliers' => 'Suppliers',
        'product_default_category' => 'Default Category',
        'product_categories' => 'Categories',
        'product_cover_image' => 'Cover Image URL',
        'product_images' => 'Product Images',
        'product_features' => 'Features',
        'product_accessories' => 'Accessories',
        'product_carriers' => 'Carriers',
        'product_tags' => 'Tags',
        'product_attachments' => 'Attachments',
        'product_attachment_names' => 'Attachment Names',
        'product_attachment_descriptions' => 'Attachment Descriptions',
        'product_url' => 'Product URL',
        'product_condition' => 'Condition',
        'product_ean' => 'Product EAN',
        'product_upc' => 'Product UPC',
        'product_isbn' => 'Product ISBN',
        'product_mpn' => 'Product MPN',
        // Custom columns
        'custom_column_1' => 'Custom Column 1',
        'custom_column_2' => 'Custom Column 2',
        'custom_column_3' => 'Custom Column 3',
    ];

    /**
     * List of columns that may have translation
     *
     * @var array
     */
    public static $multilangColumns = [
        'name',
        'description_short',
        'description',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'link_rewrite',
        'tags',
        'url',
        'default_category',
        'categories',
        'delivery_in_stock',
        'delivery_out_stock',
        'attachment_names',
        'attachment_descriptions',
    ];

    /**
     * File pointer resource to the current export file
     *
     * @var resource
     */
    private $handle;

    /**
     * Temporary file for exporting data. This will replace file_path of export rule once export is finished.
     *
     * @var string
     */
    private $file_path_tmp;

    /**
     * Instance of PHPExcel or PhpSpreadsheet class being used for current file (if xls or xlsx or ods file format)
     *
     * @var PHPExcel
     */
    private $phpExcel;

    /**
     * Default language ID of the shop
     *
     * @var int
     */
    private $id_lang_default = 1;

    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        parent::__construct($id, $id_lang, $id_shop);

        if ($this->last_export_date == '0000-00-00 00:00:00') {
            $this->last_export_date = null;
        }
        if (method_exists('Shop', 'addTableAssociation')) {
            Shop::addTableAssociation($this->tableName, ['type' => 'shop']);
        }
        $this->id_lang_default = (int) Configuration::get('PS_LANG_DEFAULT');

        if ($this->features_in_separate_columns) {
            $features = Feature::getFeatures($this->id_lang_default);

            $new_columnsProduct = [];
            foreach (self::$columnsProduct as $key => $name) {
                if ($key == 'features') {
                    foreach ($features as $feature) {
                        $new_columnsProduct['feature_' . $feature['id_feature']] = $feature['name'];
                    }
                } else {
                    $new_columnsProduct[$key] = $name;
                }
            }
            self::$columnsProduct = $new_columnsProduct;

            $new_columnsCombination = [];
            foreach (self::$columnsCombination as $key => $name) {
                if ($key == 'product_features') {
                    foreach ($features as $feature) {
                        $new_columnsCombination['product_feature_' . $feature['id_feature']] = $feature['name'];
                    }
                } else {
                    $new_columnsCombination[$key] = $name;
                }
            }
            self::$columnsCombination = $new_columnsCombination;
        }
        if ($this->images_in_separate_columns) {
            $max_number_of_images = (int) Db::getInstance()->getValue('SELECT MAX(c.`number_of_images`) FROM (SELECT COUNT(i.`id_image`) AS `number_of_images` FROM `' . _DB_PREFIX_ . 'image` i GROUP BY i.`id_product`) c');
            if ($max_number_of_images < 5) {
                $max_number_of_images = 5;
            } elseif ($max_number_of_images > 20) {
                $max_number_of_images = 20;
            }

            $new_columnsProduct = [];
            foreach (self::$columnsProduct as $key => $name) {
                $new_columnsProduct[$key] = $name;
                if ($key == 'images') {
                    for ($i = 1; $i <= $max_number_of_images; ++$i) {
                        $new_columnsProduct['image_' . $i] = 'Image ' . $i;
                    }
                }
            }
            self::$columnsProduct = $new_columnsProduct;

            $new_columnsCombination = [];
            foreach (self::$columnsCombination as $key => $name) {
                $new_columnsCombination[$key] = $name;
                if ($key == 'images') {
                    for ($i = 1; $i <= $max_number_of_images; ++$i) {
                        $new_columnsCombination['image_' . $i] = 'Image ' . $i;
                    }
                }
            }
            self::$columnsCombination = $new_columnsCombination;
        }
        if (ElegantalEasyImportTools::isModuleInstalled('totcustomfields')) {
            $sql = 'SELECT * FROM `' . _DB_PREFIX_ . "totcustomfields_input` WHERE `code_object` = 'product' AND `active` = 1";
            $totcustomfields = Db::getInstance()->executeS($sql);
            if ($totcustomfields && is_array($totcustomfields)) {
                foreach ($totcustomfields as $totcustomfield) {
                    self::$columnsProduct['totcustomfields_' . $totcustomfield['code']] = $totcustomfield['name'];
                    if ($totcustomfield['is_translatable']) {
                        self::$multilangColumns[] = 'totcustomfields_' . $totcustomfield['code'];
                    }
                }
            }
        }
        if (ElegantalEasyImportTools::isModuleInstalled('advancedcustomfields')) {
            $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'advanced_custom_fields` acf
                LEFT JOIN `' . _DB_PREFIX_ . "advanced_custom_fields_lang` acfl ON (acfl.`id_custom_field` = acf.`id_custom_field`)
                WHERE acf.`location` = 'product' AND acf.`active` = 1 AND acfl.`id_lang` = " . (int) $this->id_lang_default;
            $acfs = Db::getInstance()->executeS($sql);
            if ($acfs && is_array($acfs)) {
                foreach ($acfs as $acf) {
                    self::$columnsProduct['acf_' . $acf['technical_name']] = $acf['name'];
                    if ($acf['translatable']) {
                        self::$multilangColumns[] = 'acf_' . $acf['technical_name'];
                    }
                }
            }
        }
        if (ElegantalEasyImportTools::isModuleInstalled('pproperties')) {
            $row = Db::getInstance()->getRow('SELECT * FROM `' . _DB_PREFIX_ . 'product_shop`');
            if (isset($row['quantity_step'])) {
                self::$columnsProduct['pproperties_quantity_step'] = 'Pproperties Quantity Step';
            }
            if (isset($row['minimal_quantity_fractional'])) {
                self::$columnsProduct['pproperties_minimal_quantity'] = 'Pproperties Minimal Quantity';
            }
            $row = Db::getInstance()->getRow('SELECT * FROM `' . _DB_PREFIX_ . 'product_attribute_shop`');
            if (isset($row['quantity_step'])) {
                self::$columnsCombination['pproperties_quantity_step'] = 'Pproperties Quantity Step';
            }
            if (isset($row['minimal_quantity_fractional'])) {
                self::$columnsCombination['pproperties_minimal_quantity'] = 'Pproperties Minimal Quantity';
            }
        }
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getColumns()
    {
        $new_columns = [];
        $languages = Language::getLanguages(false);
        $columns = ($this->entity == 'combination') ? self::$columnsCombination : self::$columnsProduct;
        foreach ($columns as $column_key => $column_name) {
            if (($this->entity == 'product' && in_array($column_key, self::$multilangColumns)) || ($this->entity == 'combination' && in_array(str_replace('product_', '', $column_key), self::$multilangColumns))) {
                foreach ($languages as $lang) {
                    $new_columns[$column_key . '_' . $lang['id_lang']] = $column_name . (count($languages) > 1 ? ' ' . Tools::strtoupper($lang['iso_code']) : '');
                }
            } else {
                $new_columns[$column_key] = $column_name;
            }
        }

        return $new_columns;
    }

    public function export()
    {
        $result = [];

        try {
            if ($this->file_format == 'xls' || $this->file_format == 'xlsx' || $this->file_format == 'ods') {
                if (class_exists("\PhpOffice\PhpSpreadsheet\Spreadsheet")) {
                    $phpExcelClass = "\PhpOffice\PhpSpreadsheet\Spreadsheet";
                } elseif (class_exists('PHPExcel')) {
                    $phpExcelClass = 'PHPExcel';
                } elseif (is_file(dirname(__FILE__) . '/../vendors/phpoffice/phpspreadsheet/src/PhpSpreadsheet/Spreadsheet.php')) {
                    require_once dirname(__FILE__) . '/../vendors/autoload.php';
                    $phpExcelClass = "\PhpOffice\PhpSpreadsheet\Spreadsheet";
                } elseif (is_file(dirname(__FILE__) . '/../vendors/PHPExcel-1.8/Classes/PHPExcel.php')) {
                    require_once dirname(__FILE__) . '/../vendors/PHPExcel-1.8/Classes/PHPExcel.php';
                    $phpExcelClass = 'PHPExcel';
                } else {
                    throw new Exception('PHPExcel library could not be loaded. Please contact module developer.');
                }
                $this->phpExcel = new $phpExcelClass();
                $this->phpExcel->setActiveSheetIndex(0);
            } else {
                $this->file_path_tmp = ElegantalEasyImportTools::getTempDir() . '/export' . date('dmYHis') . mt_rand(100, 1000) . 'tmp.' . $this->file_format;
                $this->handle = fopen($this->file_path_tmp, 'w+');
                if (!$this->handle) {
                    throw new Exception('Cannot open file for writing: ' . $this->file_path_tmp);
                }
            }

            $this->writeHeader();

            // Write main body
            $count = 0;
            if ($this->entity == 'product') {
                $count = $this->exportProducts();
            } elseif ($this->entity == 'combination') {
                $count = $this->exportCombinations();
            } else {
                throw new Exception('Wrong entity.');
            }

            $this->writeFooter();

            if ($this->handle) {
                fclose($this->handle);
                // Move tmp file to replace original file
                if (!rename($this->file_path_tmp, $this->file_path)) {
                    throw new Exception('Cannot write into file: ' . $this->file_path);
                }
            }

            if ($this->is_compress_file_to_zip) {
                ElegantalEasyImportZip::compressToZip($this->file_path);
            }

            if ($count > 0) {
                $result['success'] = true;
                $result['count'] = $count;
                $this->last_export_date = date('Y-m-d H:i:s');
                $this->update();
            } else {
                @unlink($this->file_path);
                @unlink($this->file_path_tmp);
                $result['success'] = false;
                $result['message'] = 'No records found to export.';
            }
        } catch (Exception $ex) {
            @unlink($this->file_path_tmp);
            $result['success'] = false;
            $result['message'] = $ex->getMessage();
        }

        return $result;
    }

    protected function exportProducts()
    {
        $count = 0;
        $columns = ElegantalEasyImportTools::unstorable($this->columns);
        if (empty($columns) || !is_array($columns)) {
            throw new Exception('Columns not selected.');
        }
        $override = ElegantalEasyImportTools::unstorable($this->column_override_values);
        $shop_ids = ElegantalEasyImportTools::unstorable($this->shop_ids);
        if (empty($shop_ids) || (isset($shop_ids[0]) && empty($shop_ids[0])) || (isset($shop_ids[0]) && $shop_ids[0] == 'all' && !Shop::isFeatureActive())) {
            $shop_ids = [Configuration::get('PS_SHOP_DEFAULT')];
        } elseif (isset($shop_ids[0]) && $shop_ids[0] == 'all' && Shop::isFeatureActive()) {
            $shop_ids = [];
            $shops = Shop::getShops();
            foreach ($shops as $sh) {
                $shop_ids[] = $sh['id_shop'];
            }
        }
        $languages = Language::getLanguages(false);
        $defaultCurrency = Currency::getDefaultCurrency();
        $ruleCurrency = new Currency($this->currency_id);
        if (!Validate::isLoadedObject($ruleCurrency)) {
            $ruleCurrency = null;
        }
        $id_currency = $ruleCurrency ? $ruleCurrency->id : $defaultCurrency->id;
        $id_country = (int) Configuration::get('PS_COUNTRY_DEFAULT');
        $id_group = (int) Configuration::get('PS_UNIDENTIFIED_GROUP');
        $exclude_product_ids = $this->exclude_product_ids ? explode(',', $this->exclude_product_ids) : null;
        $category_ids = $this->category_ids ? ElegantalEasyImportTools::unstorable($this->category_ids) : null;
        $disallowed_category_ids = $this->disallowed_category_ids ? ElegantalEasyImportTools::unstorable($this->disallowed_category_ids) : null;
        $supplier_ids = ElegantalEasyImportTools::unstorable($this->supplier_ids);
        if (is_array($supplier_ids) && in_array('all', $supplier_ids)) {
            $supplier_ids = null;
        }
        $manufacturer_ids = ElegantalEasyImportTools::unstorable($this->manufacturer_ids);
        if (is_array($manufacturer_ids) && in_array('all', $manufacturer_ids)) {
            $manufacturer_ids = null;
        }
        $warehouse_ids = ElegantalEasyImportTools::unstorable($this->warehouse_ids);
        if (is_array($warehouse_ids) && in_array('all', $warehouse_ids)) {
            $warehouse_ids = null;
        }
        $price_from = null;
        $price_to = null;
        if ($this->price_range) {
            if (preg_match("/^([0-9]+(\.[0-9]{1,})?)-([0-9]+(\.[0-9]{1,})?)$/", str_replace(' ', '', $this->price_range), $match)) {
                if ($match[1] <= $match[3]) {
                    $price_from = $match[1];
                    $price_to = $match[3];
                }
            }
        }
        $quantity_from = null;
        $quantity_to = null;
        if ($this->quantity_range) {
            if (preg_match("/^(\d+)-(\d+)$/", str_replace(' ', '', $this->quantity_range), $match)) {
                if ($match[1] <= $match[2]) {
                    $quantity_from = $match[1];
                    $quantity_to = $match[2];
                }
            }
        }
        $currency_iso_code_suffix = $this->add_currency_code_to_prices ? ($ruleCurrency ? ' ' . $ruleCurrency->iso_code : ' ' . $defaultCurrency->iso_code) : '';
        $weight_unit_suffix = $this->add_unit_for_dimensions ? ' ' . Configuration::get('PS_WEIGHT_UNIT') : '';
        $dimension_unit_suffix = $this->add_unit_for_dimensions ? ' ' . Configuration::get('PS_DIMENSION_UNIT') : '';

        $already_loaded_shop_ids = [];

        foreach ($shop_ids as $id_shop) {
            $sql = 'SELECT DISTINCT p.`id_product` FROM `' . _DB_PREFIX_ . 'product` p
                INNER JOIN `' . _DB_PREFIX_ . 'product_shop` psh ON (psh.`id_product` = p.`id_product`)
                INNER JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (pl.`id_product` = p.`id_product`) ';
            if ($category_ids || $disallowed_category_ids) {
                $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'category_product` cp ON (cp.`id_product` = p.`id_product`) ';
            }
            if ($supplier_ids) {
                $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_supplier` ps ON (ps.`id_product` = p.`id_product` AND ps.`id_product_attribute` = 0) ';
            }
            if (Configuration::get('PS_STOCK_MANAGEMENT') && (!is_null($quantity_from) || !is_null($quantity_to))) {
                $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'stock_available` sa ON (sa.`id_product` = p.`id_product` AND sa.`id_product_attribute` = 0 AND sa.`id_shop` = ' . (int) $id_shop . ') ';
            }
            $sql .= 'WHERE psh.`id_shop` = ' . (int) $id_shop . ' AND pl.`id_shop` = ' . (int) $id_shop . ' AND pl.`id_lang` = ' . (int) $this->id_lang_default . ' ';
            $sql .= count($already_loaded_shop_ids) > 0 ? 'AND p.`id_product` NOT IN (SELECT `id_product` FROM `' . _DB_PREFIX_ . 'product_shop` WHERE `id_shop` IN (' . implode(',', array_map('intval', $already_loaded_shop_ids)) . ')) ' : '';
            $sql .= $exclude_product_ids ? 'AND p.`id_product` NOT IN (' . implode(',', array_map('intval', $exclude_product_ids)) . ') ' : '';
            $sql .= ($this->product_status == 1 || $this->product_status == 0) ? 'AND psh.`active` = ' . (int) $this->product_status . ' ' : '';
            $sql .= !is_null($price_from) ? 'AND psh.`price` >= ' . (float) $price_from . ' ' : '';
            $sql .= !is_null($price_to) ? 'AND psh.`price` <= ' . (float) $price_to . ' ' : '';
            $sql .= $category_ids ? ' AND cp.`id_category` IN (' . implode(',', array_map('intval', $category_ids)) . ') ' : '';
            $sql .= $disallowed_category_ids ? ' AND cp.`id_category` NOT IN (' . implode(',', array_map('intval', $disallowed_category_ids)) . ') ' : '';
            $sql .= $supplier_ids ? ' AND ps.`id_supplier` IN (' . implode(',', array_map('intval', $supplier_ids)) . ') ' : '';
            $sql .= $manufacturer_ids ? ' AND p.`id_manufacturer` IN (' . implode(',', array_map('intval', $manufacturer_ids)) . ') ' : '';
            $sql .= (Configuration::get('PS_STOCK_MANAGEMENT') && !is_null($quantity_from)) ? 'AND sa.`quantity` >= ' . (int) $quantity_from . ' ' : '';
            $sql .= (Configuration::get('PS_STOCK_MANAGEMENT') && !is_null($quantity_to)) ? 'AND sa.`quantity` <= ' . (int) $quantity_to . ' ' : '';
            if ($this->export_products_updated_within_minute) {
                $updated_since = date('Y-m-d H:i:s', strtotime('-' . $this->export_products_updated_within_minute . ' minutes'));
                $sql .= ' AND (';
                $sql .= "psh.`date_upd` >= '" . pSQL($updated_since) . "' ";
                $sql .= 'OR p.`id_product` IN (SELECT savl.`id_product` FROM `' . _DB_PREFIX_ . 'stock_mvt` smvt INNER JOIN `' . _DB_PREFIX_ . "stock_available` savl ON (savl.`id_stock_available` = smvt.`id_stock`) WHERE smvt.`date_add` >= '" . pSQL($updated_since) . "') ";
                $sql .= 'OR p.`id_product` IN (SELECT ordet.`product_id` FROM `' . _DB_PREFIX_ . 'order_detail` ordet INNER JOIN `' . _DB_PREFIX_ . "orders` ord ON (ord.`id_order` = ordet.`id_order`) WHERE ord.`date_add` >= '" . pSQL($updated_since) . "') ";
                $sql .= ') ';
            }
            $sql .= 'GROUP BY p.`id_product` ORDER BY ' . pSQL($this->order_by) . ' ' . (($this->order_by != 'RAND()') ? pSQL($this->order_direction) : '');

            $products = Db::getInstance()->executeS($sql);

            $already_loaded_shop_ids[] = $id_shop;

            if (!$products || !is_array($products)) {
                continue;
            }

            foreach ($products as $p) {
                $product = new Product($p['id_product'], true);
                if (!Validate::isLoadedObject($product)) {
                    continue;
                }
                $data = [];
                foreach ($columns as $key => $column_name) {
                    $override_value = isset($override[$key]) ? $override[$key] : '';
                    if ($override_value != '') {
                        if ((preg_match("/^description_short_([\d]+)$/", $key) && $override_value == 'strip_tags')
                            || (preg_match("/^description_([\d]+)$/", $key) && $override_value == 'strip_tags')
                            || ($override_value == 'skip_product_if_empty')
                            || preg_match("/\[FORMULA:([\s\[\]\#\.\,\;\+\-\*\/\d]+)\]/", $override_value)
                            || preg_match("/\[CONVERT:\s*(.*?)\]/", $override_value)
                            || preg_match_all("/\[\%([a-zA-Z0-9\.\s\_\-\'\"\p{L}]+)\%\]/u", $override_value)) {
                            // Skip this override
                        } else {
                            $data[] = $override_value;
                            continue;
                        }
                    }

                    $value = $this->getProductDataByColumn($product, $key, $override_value, $id_shop, $id_currency, $id_country, $id_group, $languages, $defaultCurrency, $ruleCurrency, $warehouse_ids, $currency_iso_code_suffix, $weight_unit_suffix, $dimension_unit_suffix);

                    if ($value === '' && $override_value == 'skip_product_if_empty') {
                        continue 2;
                    }

                    // Check if override_value has [%column_key%]
                    if (preg_match_all("/\[\%([a-zA-Z0-9\.\s\_\-\'\"\p{L}]+)\%\]/u", $override_value, $column_concat_matches)) {
                        foreach ($column_concat_matches[0] as $column_concat_key => $column_concat_match) {
                            if (isset($column_concat_matches[1][$column_concat_key]) && $column_concat_matches[1][$column_concat_key]) {
                                $tmp_value = $this->getProductDataByColumn($product, $column_concat_matches[1][$column_concat_key], '', $id_shop, $id_currency, $id_country, $id_group, $languages, $defaultCurrency, $ruleCurrency, $warehouse_ids, '', '', '');
                                $override_value = str_replace($column_concat_match, $tmp_value, $override_value);
                            }
                        }
                    }

                    // Check if override_value has [FORMULA: ]
                    if (preg_match("/\[FORMULA:([\s\[\]\#\.\,\;\+\-\*\/\d]+)\]/", $override_value, $formula_match)) {
                        $formula_value = round((float) ElegantalEasyImportTools::getModifiedPriceByFormula((float) $value, $formula_match[1]), 2);
                        $override_value = str_replace($formula_match[0], $formula_value, $override_value);
                    }

                    // Check if override_value has [CONVERT: ]
                    if (preg_match("/\[CONVERT:\s*(.*?)\]/", $override_value, $convert_match)) {
                        $override_value = '';
                        $dictionaries = explode('|', $convert_match[1]);
                        if ($dictionaries) {
                            foreach ($dictionaries as $dict) {
                                $dict_key_value = explode('=>', $dict);
                                if (isset($dict_key_value[0], $dict_key_value[1]) && trim($dict_key_value[0]) === trim($value)) {
                                    $override_value = trim($dict_key_value[1]);
                                }
                            }
                        }
                    }

                    if ($override_value === '0cm*0cm*0cm' && strpos($key, 'custom_column') === 0) {
                        $override_value = '';
                    }

                    if ($override_value != '') {
                        $value = $override_value;
                    }

                    $data[] = $value;
                }
                if ($this->writeBody($data) !== false) {
                    ++$count;
                }
            }
        }

        return $count;
    }

    protected function getProductDataByColumn($product, $key, $override_value, $id_shop, $id_currency, $id_country, $id_group, $languages, $defaultCurrency, $ruleCurrency, $warehouse_ids, $currency_iso_code_suffix, $weight_unit_suffix, $dimension_unit_suffix)
    {
        $context = Context::getContext();
        $product_base_price = (float) Product::getPriceStatic($product->id, false, false, 6, null, false, false); // If you want the default combination, use NULL value for id_product_attribute
        $specific_price = SpecificPrice::getSpecificPrice((int) $product->id, $id_shop, $id_currency, $id_country, $id_group, 1);
        $is_convert_currency = ($this->currency_id && $this->currency_id != $defaultCurrency->id && $ruleCurrency) ? true : false;
        $rootCategory = Category::getRootCategory();

        $value = '';
        switch ($key) {
            case 'id':
            case 'product_id':
                $value = $product->id;
                break;
            case 'reference':
                $value = $product->reference;
                break;
            case 'active':
                $value = $product->active;
                break;
            case 'price_tax_excluded':
                $value = $product_base_price;
                if ($is_convert_currency) {
                    $value = Tools::convertPriceFull($value, $defaultCurrency, $ruleCurrency);
                }
                $value = (float) ElegantalEasyImportTools::getModifiedPriceByFormula($value, $this->price_modifier);
                $value = round($value, 2) . $currency_iso_code_suffix;
                break;
            case 'price_tax_included':
                $value = (float) Product::getPriceStatic($product->id, true, false, 6, null, false, false);
                if ($is_convert_currency) {
                    $value = Tools::convertPriceFull($value, $defaultCurrency, $ruleCurrency);
                }
                $value = (float) ElegantalEasyImportTools::getModifiedPriceByFormula($value, $this->price_modifier);
                $value = round($value, 2) . $currency_iso_code_suffix;
                break;
            case 'discounted_price_tax_excluded':
                $value = (float) Product::getPriceStatic($product->id, false, false, 6, null, false, true);
                if ($is_convert_currency) {
                    $value = (float) Tools::convertPriceFull($value, $defaultCurrency, $ruleCurrency);
                }
                $value = round($value, 2) . $currency_iso_code_suffix;
                break;
            case 'discounted_price_tax_included':
                $value = (float) Product::getPriceStatic($product->id, true, false, 6, null, false, true);
                if ($is_convert_currency) {
                    $value = (float) Tools::convertPriceFull($value, $defaultCurrency, $ruleCurrency);
                }
                $value = round($value, 2) . $currency_iso_code_suffix;
                break;
            case 'discounted_price_tax_excluded_if_discount_exists':
                $value = (float) Product::getPriceStatic($product->id, false, false, 6, null, false, true);
                if ($is_convert_currency) {
                    $value = (float) Tools::convertPriceFull($value, $defaultCurrency, $ruleCurrency);
                }
                $value = round($value, 2);
                // Make this value empty if product discounted price is not less than product price
                $product_price_tax_excl = $product_base_price;
                if ($is_convert_currency) {
                    $product_price_tax_excl = Tools::convertPriceFull($product_price_tax_excl, $defaultCurrency, $ruleCurrency);
                }
                if ($value >= $product_price_tax_excl) {
                    $value = '';
                } else {
                    $value .= $currency_iso_code_suffix;
                }
                break;
            case 'discounted_price_tax_included_if_discount_exists':
                $value = (float) Product::getPriceStatic($product->id, true, false, 6, null, false, true);
                if ($is_convert_currency) {
                    $value = (float) Tools::convertPriceFull($value, $defaultCurrency, $ruleCurrency);
                }
                $value = round($value, 2);
                // Make this value empty if product discounted price is not less than product price
                $product_price_tax_incl = (float) Product::getPriceStatic($product->id, true, false, 6, null, false, false);
                if ($is_convert_currency) {
                    $product_price_tax_incl = Tools::convertPriceFull($product_price_tax_incl, $defaultCurrency, $ruleCurrency);
                }
                if ($value >= $product_price_tax_incl) {
                    $value = '';
                } else {
                    $value .= $currency_iso_code_suffix;
                }
                break;
            case 'discount_percent':
                if ($specific_price && $specific_price['reduction_type'] == 'percentage') {
                    $value = round((float) $specific_price['reduction'] * 100, 6) . '%';
                }
                break;
            case 'discount_amount':
                if ($specific_price && $specific_price['reduction_type'] == 'amount') {
                    $value = round((float) $specific_price['reduction'], 6);
                }
                break;
            case 'discount_base_price':
                if ($specific_price && isset($specific_price['price']) && $specific_price['price'] >= 0) {
                    $value = $specific_price['price'];
                }
                break;
            case 'discount_starting_unit':
                if ($specific_price && isset($specific_price['from_quantity'])) {
                    $value = $specific_price['from_quantity'];
                }
                break;
            case 'discount_from':
                if ($specific_price && isset($specific_price['from']) && $specific_price['from'] && $specific_price['from'] != '0000-00-00 00:00:00' && strtotime($specific_price['from'])) {
                    $value = date('Y-m-d', strtotime($specific_price['from']));
                }
                break;
            case 'discount_to':
                if ($specific_price && isset($specific_price['to']) && $specific_price['to'] && $specific_price['to'] != '0000-00-00 00:00:00' && strtotime($specific_price['to'])) {
                    $value = date('Y-m-d', strtotime($specific_price['to']));
                }
                break;
            case 'wholesale_price':
                $value = (float) $product->wholesale_price;
                if ($is_convert_currency) {
                    $value = (float) Tools::convertPriceFull($value, $defaultCurrency, $ruleCurrency);
                }
                $value = round($value, 2) . $currency_iso_code_suffix;
                break;
            case 'unit_price':
                $value = ($product->unit_price_ratio != 0) ? (float) ($product_base_price / $product->unit_price_ratio) : 0;
                if ($is_convert_currency) {
                    $value = (float) Tools::convertPriceFull($value, $defaultCurrency, $ruleCurrency);
                }
                $value = round($value, 2) . $currency_iso_code_suffix;
                break;
            case 'unity':
                $value = $product->unity;
                break;
            case 'tax_rule':
                if ($product->id_tax_rules_group) {
                    $taxRule = new TaxRulesGroup($product->id_tax_rules_group);
                    $value = Validate::isLoadedObject($taxRule) ? $taxRule->name : '';
                }
                break;
            case 'quantity':
            case 'stock_availability':
                $value = StockAvailable::getQuantityAvailableByProduct($product->id);
                if ($warehouse_ids) {
                    $sql = 'SELECT SUM(physical_quantity) as `quantity` FROM `' . _DB_PREFIX_ . 'stock`
                        WHERE `id_product` = ' . (int) $product->id . ' AND `id_product_attribute` = 0 AND `id_warehouse` IN (' . implode(',', array_map('intval', $warehouse_ids)) . ') ';
                    $value = (int) Db::getInstance()->getValue($sql);
                }
                if ($key == 'stock_availability') {
                    $value = $value > 0 ? 'in stock' : 'out of stock';
                }
                break;
            case 'minimal_quantity':
                $value = $product->minimal_quantity;
                break;
            case 'stock_location':
                $value = method_exists('StockAvailable', 'getLocation') ? call_user_func(['StockAvailable', 'getLocation'], $product->id, null, $id_shop) : '';
                break;
            case 'low_stock_level':
                $value = property_exists($product, 'low_stock_threshold') ? $product->low_stock_threshold : '';
                break;
            case 'email_alert_on_low_stock':
                $value = property_exists($product, 'low_stock_alert') ? $product->low_stock_alert : '';
                break;
            case 'manufacturer':
                if ($product->id_manufacturer) {
                    $manufacturer = new Manufacturer($product->id_manufacturer);
                    $value = Validate::isLoadedObject($manufacturer) ? $manufacturer->name : '';
                }
                break;
            case 'suppliers':
            case 'supplier_reference':
            case 'supplier_price':
                $sql = 'SELECT ps.`id_supplier`, s.`name`, ps.`product_supplier_reference`, ps.`product_supplier_price_te`
                    FROM `' . _DB_PREFIX_ . 'product_supplier` ps
                    INNER JOIN `' . _DB_PREFIX_ . 'supplier` s ON (s.`id_supplier` = ps.`id_supplier`)
                    WHERE ps.`id_product` = ' . (int) $product->id . ' AND ps.`id_product_attribute` = 0 GROUP BY ps.`id_supplier`, s.`name`, ps.`product_supplier_reference`, ps.`product_supplier_price_te` ORDER BY ps.`id_supplier`';
                $product_suppliers = Db::getInstance()->executeS($sql);
                if ($product_suppliers) {
                    foreach ($product_suppliers as $item_index => $product_supplier) {
                        $value .= ($item_index > 0) ? $this->multiple_value_separator : '';
                        if ($key == 'suppliers') {
                            $value .= $product_supplier['name'];
                        } elseif ($key == 'supplier_reference') {
                            $value .= $product_supplier['product_supplier_reference'];
                        } elseif ($key == 'supplier_price') {
                            $value .= $product_supplier['product_supplier_price_te'];
                        }
                    }
                }
                break;
            case preg_match("/^default_category_([\d]+)$/", $key, $default_category_match) ? true : false:
                $category_lang_id = isset($default_category_match[1]) ? (int) $default_category_match[1] : $this->id_lang_default;
                $category = new Category($product->id_category_default, $category_lang_id);
                if (Validate::isLoadedObject($category)) {
                    if ($this->multiple_subcategory_separator) {
                        $parents = $category->getParentsCategories($category_lang_id);
                        if ($parents) {
                            $category_path = '';
                            $parents = array_reverse($parents);
                            foreach ($parents as $parent) {
                                if (!$this->root_category_included && $parent['id_category'] == $rootCategory->id) {
                                    continue;
                                }
                                $category_path .= $category_path ? $this->multiple_subcategory_separator : '';
                                $category_path .= $parent['name'];
                            }
                            $value = $category_path;
                        } else {
                            $value = $category->name;
                        }
                    } else {
                        $value = $category->name;
                    }
                }
                break;
            case preg_match("/^categories_([\d]+)$/", $key, $categories_match) ? true : false:
                $category_lang_id = isset($categories_match[1]) ? (int) $categories_match[1] : $this->id_lang_default;
                $id_categories = $product->getCategories();
                if ($id_categories && $category_lang_id) {
                    $category_count = 0;
                    foreach ($id_categories as $id_category) {
                        if (!$this->root_category_included && $id_category == $rootCategory->id) {
                            continue;
                        }
                        $category = new Category($id_category, $category_lang_id);
                        if ($this->multiple_subcategory_separator) {
                            $parents = $category->getParentsCategories($category_lang_id);
                            if ($parents) {
                                $parents = array_reverse($parents);
                                $category_path = '';
                                $sub_category_count = 0;
                                foreach ($parents as $parent) {
                                    if (!$this->root_category_included && $parent['id_category'] == $rootCategory->id) {
                                        continue;
                                    }
                                    $category_path .= ($sub_category_count > 0) ? $this->multiple_subcategory_separator : '';
                                    $category_path .= $parent['name'];
                                    ++$sub_category_count;
                                }
                                $value .= ($category_count > 0) ? $this->multiple_value_separator : '';
                                $value .= $category_path;
                            }
                        } else {
                            $value .= ($category_count > 0) ? $this->multiple_value_separator : '';
                            $value .= $category->name;
                        }
                        ++$category_count;
                    }
                }
                break;
            case 'cover_image':
            case 'images':
            case 'captions':
            case preg_match("/^image_([\d]+)$/", $key, $image_match) ? true : false:
                $image_position_current = isset($image_match[1]) ? (int) $image_match[1] : 0;

                $cover_image = Image::getCover($product->id);
                if ($cover_image) {
                    $value = $context->link->getImageLink($product->link_rewrite[$this->id_lang_default], $product->id . '-' . $cover_image['id_image']);
                }
                if ($key == 'cover_image') {
                    break;
                }

                $captions = '';
                $product_images_without_cover = [];

                $product_images = Image::getImages($this->id_lang_default, $product->id);
                if ($product_images) {
                    $image_position = 1;
                    foreach ($product_images as $item_index => $image) {
                        if ($image['cover']) {
                            $captions = $captions ? $image['legend'] . $this->multiple_value_separator . $captions : $image['legend'];
                        } else {
                            $product_images_without_cover[$image_position] = $image;
                            $captions .= ($item_index > 0) ? $this->multiple_value_separator : '';
                            $captions .= $image['legend'];
                            ++$image_position;
                        }
                    }
                    if ($product_images_without_cover) {
                        if (preg_match("/^image_([\d]+)$/", $key)) {
                            if (isset($product_images_without_cover[$image_position_current])) {
                                $value = $context->link->getImageLink($product->link_rewrite[$this->id_lang_default], $product->id . '-' . $product_images_without_cover[$image_position_current]['id_image']);
                            } else {
                                $value = '';
                            }
                            break;
                        }
                        foreach ($product_images_without_cover as $image) {
                            $value .= $value ? $this->multiple_value_separator : '';
                            $value .= $context->link->getImageLink($product->link_rewrite[$this->id_lang_default], $product->id . '-' . $image['id_image']);
                        }
                    }
                }
                if ($key == 'captions') {
                    $value = $captions;
                }
                break;
            case 'features':
                $features = $product->getFeatures();
                if ($features) {
                    foreach ($features as $feature) {
                        $featureObj = new Feature($feature['id_feature'], $this->id_lang_default);
                        if (Validate::isLoadedObject($featureObj) && $featureObj->name) {
                            $featureValue = new FeatureValue($feature['id_feature_value'], $this->id_lang_default);
                            if (Validate::isLoadedObject($featureValue) && $featureValue->value !== '') {
                                $value .= $value ? $this->multiple_value_separator : '';
                                $value .= $featureObj->name . ':' . $featureValue->value;
                            }
                        }
                    }
                }
                break;
            case preg_match("/^feature_([\d]+)$/", $key, $feature_match) ? true : false:
                $feature_id = isset($feature_match[1]) ? (int) $feature_match[1] : 0;
                $featureObj = new Feature($feature_id, $this->id_lang_default);
                if (Validate::isLoadedObject($featureObj)) {
                    $features = $product->getFeatures();
                    foreach ($features as $feature) {
                        if ($feature['id_feature'] == $featureObj->id) {
                            $featureValue = new FeatureValue($feature['id_feature_value'], $this->id_lang_default);
                            if (Validate::isLoadedObject($featureValue) && $featureValue->value !== '') {
                                $value .= ($value !== '') ? $this->multiple_value_separator : '';
                                $value .= $featureValue->value;
                            }
                        }
                    }
                }
                break;
            case 'accessories':
                $accessories = Product::getAccessoriesLight($this->id_lang_default, $product->id);
                if ($accessories) {
                    foreach ($accessories as $accessory) {
                        if ($accessory['reference'] !== '') {
                            $value .= ($value !== '') ? $this->multiple_value_separator : '';
                            $value .= $accessory['reference'];
                        }
                    }
                }
                break;
            case 'carriers':
                $carriers = $product->getCarriers();
                if ($carriers) {
                    foreach ($carriers as $carrier) {
                        if ($carrier['name'] !== '') {
                            $value .= ($value !== '') ? $this->multiple_value_separator : '';
                            $value .= $carrier['name'];
                        }
                    }
                }
                break;
            case preg_match("/^tags_([\d]+)$/", $key, $tag_match) ? true : false:
                $tag_lang_id = isset($tag_match[1]) ? (int) $tag_match[1] : 0;
                if (isset($product->tags[$tag_lang_id]) && $product->tags[$tag_lang_id]) {
                    foreach ($product->tags[$tag_lang_id] as $tag) {
                        if ($tag !== '') {
                            $value .= ($value !== '') ? $this->multiple_value_separator : '';
                            $value .= $tag;
                        }
                    }
                }
                break;
            case 'attachments':
            case preg_match("/^attachment_names_([\d]+)$/", $key, $attachment_match) ? true : false:
                // no break
            case preg_match("/^attachment_descriptions_([\d]+)$/", $key, $attachment_match) ? true : false:
                $attachment_lang_id = isset($attachment_match[1]) ? (int) $attachment_match[1] : $this->id_lang_default;
                $attachments = $product->getAttachments($attachment_lang_id);
                if ($attachments) {
                    foreach ($attachments as $item_index => $attachment) {
                        $value .= ($item_index > 0) ? $this->multiple_value_separator : '';
                        if ($key == 'attachments') {
                            $value .= $context->link->getPageLink('attachment', null, $this->id_lang_default, ['id_attachment' => $attachment['id_attachment'], 'file_name' => $attachment['file_name']], false, $id_shop, false);
                        } elseif (preg_match("/^attachment_names_([\d]+)$/", $key) && isset($attachment['name'])) {
                            $value .= $attachment['name'];
                        } elseif (preg_match("/^attachment_descriptions_([\d]+)$/", $key) && isset($attachment['description'])) {
                            $value .= $attachment['description'];
                        }
                    }
                }
                break;
            case 'pack_items_refs':
                if (method_exists('Pack', 'getItems') && Pack::isPack($product->id)) {
                    $pack_items = Pack::getItems($product->id, $this->id_lang_default);
                    if ($pack_items && is_array($pack_items)) {
                        foreach ($pack_items as $pack_item) {
                            if ($pack_item->reference !== '') {
                                $value .= ($value !== '') ? $this->multiple_value_separator : '';
                                $value .= $pack_item->reference;
                            }
                        }
                    }
                }
                break;
            case preg_match("/^url_([\d]+)$/", $key, $url_match) ? true : false:
                $url_lang_id = isset($url_match[1]) ? (int) $url_match[1] : $this->id_lang_default;
                $value = $context->link->getProductLink($product, $product->link_rewrite, Category::getLinkRewrite($product->id_category_default, $url_lang_id), null, $url_lang_id);
                break;
            case 'visibility':
                $value = $product->visibility;
                break;
            case 'available_for_order':
                $value = $product->available_for_order;
                break;
            case 'show_price':
                $value = $product->show_price;
                break;
            case 'on_sale':
                $value = $product->on_sale;
                break;
            case 'condition':
                $value = $product->condition;
                break;
            case 'ean':
                $value = $product->ean13;
                break;
            case 'upc':
                $value = $product->upc;
                break;
            case 'isbn':
                $value = property_exists($product, 'isbn') ? $product->isbn : '';
                break;
            case 'mpn':
                $value = property_exists($product, 'mpn') ? $product->mpn : '';
                break;
            case 'width':
                $value = round($product->width, 2) . $dimension_unit_suffix;
                break;
            case 'height':
                $value = round($product->height, 2) . $dimension_unit_suffix;
                break;
            case 'depth':
                $value = round($product->depth, 2) . $dimension_unit_suffix;
                break;
            case 'weight':
                $value = round($product->weight, 2) . $weight_unit_suffix;
                break;
            case 'action_when_out_of_stock':
                $value = StockAvailable::outOfStock($product->id, $id_shop);
                break;
            case 'text_when_in_stock':
                $value = $product->available_now[$this->id_lang_default];
                break;
            case 'text_when_backorder':
                $value = $product->available_later[$this->id_lang_default];
                break;
            case 'availability_date':
                $value = ($product->available_date && $product->available_date != '0000-00-00 00:00:00' && $product->available_date != '0000-00-00') ? $product->available_date : '';
                break;
            case 'delivery_time':
                $value = (int) $product->additional_delivery_times;
                break;
            case preg_match("/^delivery_in_stock_([\d]+)$/", $key, $delivery_in_stock_match) ? true : false:
                if (isset($product->delivery_in_stock[$delivery_in_stock_match[1]]) && $product->delivery_in_stock[$delivery_in_stock_match[1]]) {
                    $value = $product->delivery_in_stock[$delivery_in_stock_match[1]];
                }
                break;
            case preg_match("/^delivery_out_stock_([\d]+)$/", $key, $delivery_out_stock_match) ? true : false:
                if (isset($product->delivery_out_stock[$delivery_out_stock_match[1]]) && $product->delivery_out_stock[$delivery_out_stock_match[1]]) {
                    $value = $product->delivery_out_stock[$delivery_out_stock_match[1]];
                }
                break;
            case 'date_created':
                $value = $product->date_add;
                break;
            case 'shop_id':
                $value = $id_shop;
                break;
            case 'shop_name':
                $shop = new Shop($id_shop);
                $value = $shop->name;
                break;
            case preg_match("/^custom_column_[\d]+$/", $key) ? true : false:
                break;
            case preg_match('/^totcustomfields_/', $key) ? true : false:
                $key_without_lang = null;
                $lang_id = null;
                if (isset(self::$columnsProduct[$key])) {
                    $key_without_lang = $key;
                } else {
                    $lang_id = Tools::strrpos($key, '_') !== false ? Tools::substr($key, Tools::strrpos($key, '_') + 1, 5) : null;
                    $lang_id = Validate::isInt($lang_id) ? (int) $lang_id : null;
                    $key_without_lang = $lang_id ? preg_replace('/_' . $lang_id . '$/', '', $key) : $key;
                    if (!in_array($key_without_lang, self::$multilangColumns)) {
                        $key_without_lang = null;
                    }
                }
                if ($key_without_lang) {
                    $code = preg_replace('/^totcustomfields_/', '', $key_without_lang);
                    $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'totcustomfields_input` t
                        INNER JOIN `' . _DB_PREFIX_ . "totcustomfields_input_shop` sh ON sh.`id_input` = t.`id_input`
                        WHERE t.`code_object` = 'product' AND t.`code` = '" . pSQL($code) . "' AND sh.`id_shop` = " . (int) $id_shop . ' ';
                    $sql .= ($lang_id) ? 'AND t.`is_translatable` = 1' : '';
                    $totcustomfield = Db::getInstance()->getRow($sql);
                    if ($totcustomfield) {
                        $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'totcustomfields_input_' . pSQL($totcustomfield['code_input_type']) . '_value`
                            WHERE `id_input` = ' . (int) $totcustomfield['id_input'] . ' AND `id_object` = ' . (int) $product->id . ' ';
                        $sql .= ($lang_id) ? 'AND `id_lang` = ' . (int) $lang_id : '';
                        $totcustomfield_val = Db::getInstance()->getRow($sql);
                        if ($totcustomfield_val && isset($totcustomfield_val['value'])) {
                            $value = $totcustomfield_val['value'];
                        }
                    }
                }
                break;
            case preg_match('/^acf_/', $key) ? true : false:
                $key_without_lang = null;
                $lang_id = null;
                if (isset(self::$columnsProduct[$key])) {
                    $key_without_lang = $key;
                } else {
                    $lang_id = Tools::strrpos($key, '_') !== false ? Tools::substr($key, Tools::strrpos($key, '_') + 1, 5) : null;
                    $lang_id = Validate::isInt($lang_id) ? (int) $lang_id : null;
                    $key_without_lang = $lang_id ? preg_replace('/_' . $lang_id . '$/', '', $key) : $key;
                    if (!in_array($key_without_lang, self::$multilangColumns)) {
                        $key_without_lang = null;
                    }
                }
                if ($key_without_lang) {
                    $code = preg_replace('/^acf_/', '', $key_without_lang);
                    $sql = 'SELECT * FROM `' . _DB_PREFIX_ . "advanced_custom_fields` acf
                        WHERE acf.`location` = 'product' AND acf.`technical_name` = '" . pSQL($code) . "' ";
                    $sql .= ($lang_id) ? 'AND acf.`translatable` = 1' : '';
                    $acf = Db::getInstance()->getRow($sql);
                    if ($acf) {
                        $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'advanced_custom_fields_content` acfc
                            LEFT JOIN `' . _DB_PREFIX_ . 'advanced_custom_fields_content_lang` acfcl ON (acfcl.`id_custom_field_content` = acfc.`id_custom_field_content`)
                            WHERE acfc.`id_custom_field` = ' . (int) $acf['id_custom_field'] . ' AND acfc.`resource_id` = ' . (int) $product->id . ' AND acfc.`id_store` = ' . (int) $id_shop . ' ';
                        $sql .= ($lang_id) ? 'AND acfcl.`id_lang` = ' . (int) $lang_id : '';
                        $acf_val = Db::getInstance()->getRow($sql);
                        $value = ($lang_id && isset($acf_val['lang_value']) && $acf_val['lang_value'] !== '') ? $acf_val['lang_value'] : (isset($acf_val['value']) ? $acf_val['value'] : '');
                    }
                }
                break;
            case 'pproperties_quantity_step':
                $value = $product->quantity_step;
                break;
            case 'pproperties_minimal_quantity':
                $value = $product->minimal_quantity_fractional;
                break;
            default:
                foreach (self::$multilangColumns as $mcolumn) {
                    foreach ($languages as $mlang) {
                        $mkey = $mcolumn . '_' . $mlang['id_lang'];
                        if ($mkey == $key) {
                            if (isset($product->{$mcolumn}[$mlang['id_lang']])) {
                                $value = $product->{$mcolumn}[$mlang['id_lang']];
                                if ((preg_match("/^description_short_([\d]+)$/", $key) && $override_value == 'strip_tags')
                                    || (preg_match("/^description_([\d]+)$/", $key) && $override_value == 'strip_tags')) {
                                    $value = str_ireplace(['<br />', '<br>', '<br/>'], PHP_EOL, $value);
                                    $value = strip_tags($value);
                                }
                            }
                            break 2;
                        }
                    }
                }
                break;
        }

        return $value;
    }

    protected function exportCombinations()
    {
        $count = 0;
        $columns = ElegantalEasyImportTools::unstorable($this->columns);
        if (empty($columns) || !is_array($columns)) {
            throw new Exception('Columns not selected.');
        }
        $override = ElegantalEasyImportTools::unstorable($this->column_override_values);
        $shop_ids = ElegantalEasyImportTools::unstorable($this->shop_ids);
        if (empty($shop_ids) || (isset($shop_ids[0]) && empty($shop_ids[0])) || (isset($shop_ids[0]) && $shop_ids[0] == 'all' && !Shop::isFeatureActive())) {
            $shop_ids = [Configuration::get('PS_SHOP_DEFAULT')];
        } elseif (isset($shop_ids[0]) && $shop_ids[0] == 'all' && Shop::isFeatureActive()) {
            $shop_ids = [];
            $shops = Shop::getShops();
            foreach ($shops as $sh) {
                $shop_ids[] = $sh['id_shop'];
            }
        }
        $languages = Language::getLanguages(false);
        $defaultCurrency = Currency::getDefaultCurrency();
        $ruleCurrency = new Currency($this->currency_id);
        if (!Validate::isLoadedObject($ruleCurrency)) {
            $ruleCurrency = null;
        }
        $id_currency = $ruleCurrency ? $ruleCurrency->id : $defaultCurrency->id;
        $id_country = (int) Configuration::get('PS_COUNTRY_DEFAULT');
        $id_group = (int) Configuration::get('PS_UNIDENTIFIED_GROUP');
        $exclude_product_ids = $this->exclude_product_ids ? explode(',', $this->exclude_product_ids) : null;
        $category_ids = $this->category_ids ? ElegantalEasyImportTools::unstorable($this->category_ids) : null;
        $disallowed_category_ids = $this->disallowed_category_ids ? ElegantalEasyImportTools::unstorable($this->disallowed_category_ids) : null;
        $supplier_ids = ElegantalEasyImportTools::unstorable($this->supplier_ids);
        if (is_array($supplier_ids) && in_array('all', $supplier_ids)) {
            $supplier_ids = null;
        }
        $manufacturer_ids = ElegantalEasyImportTools::unstorable($this->manufacturer_ids);
        if (is_array($manufacturer_ids) && in_array('all', $manufacturer_ids)) {
            $manufacturer_ids = null;
        }
        $warehouse_ids = ElegantalEasyImportTools::unstorable($this->warehouse_ids);
        if (is_array($warehouse_ids) && in_array('all', $warehouse_ids)) {
            $warehouse_ids = null;
        }
        $price_from = null;
        $price_to = null;
        if ($this->price_range) {
            if (preg_match("/^([0-9]+(\.[0-9]{1,})?)-([0-9]+(\.[0-9]{1,})?)$/", str_replace(' ', '', $this->price_range), $match)) {
                if ($match[1] <= $match[3]) {
                    $price_from = $match[1];
                    $price_to = $match[3];
                }
            }
        }
        $quantity_from = null;
        $quantity_to = null;
        if ($this->quantity_range) {
            if (preg_match("/^(\d+)-(\d+)$/", str_replace(' ', '', $this->quantity_range), $match)) {
                if ($match[1] <= $match[2]) {
                    $quantity_from = $match[1];
                    $quantity_to = $match[2];
                }
            }
        }
        $currency_iso_code_suffix = $this->add_currency_code_to_prices ? ($ruleCurrency ? ' ' . $ruleCurrency->iso_code : ' ' . $defaultCurrency->iso_code) : '';
        $weight_unit_suffix = $this->add_unit_for_dimensions ? ' ' . Configuration::get('PS_WEIGHT_UNIT') : '';
        $dimension_unit_suffix = $this->add_unit_for_dimensions ? ' ' . Configuration::get('PS_DIMENSION_UNIT') : '';

        $already_loaded_shop_ids = [];

        foreach ($shop_ids as $id_shop) {
            $sql = "SELECT p.*, psh.*, pa.*, pash.*, p.`reference` AS `product_reference`, p.`weight` AS `product_weight`, psh.`price` AS `product_price`, ps.`supplier_reference`, ps.`supplier_price`, pai.`images`, GROUP_CONCAT(agl.`name` ORDER BY pac.`id_attribute` ASC SEPARATOR '" . pSQL($this->multiple_value_separator) . "') AS `Attributes`, GROUP_CONCAT(al.`name` ORDER BY pac.`id_attribute` ASC SEPARATOR '" . pSQL($this->multiple_value_separator) . "') AS `Values`
                FROM `" . _DB_PREFIX_ . 'product_attribute` pa
                INNER JOIN `' . _DB_PREFIX_ . 'product_attribute_shop` pash ON pash.`id_product_attribute` = pa.`id_product_attribute` AND pash.`id_shop` = ' . (int) $id_shop . '
                INNER JOIN `' . _DB_PREFIX_ . 'product` p ON p.`id_product` = pa.`id_product`
                INNER JOIN `' . _DB_PREFIX_ . 'product_shop` psh ON psh.`id_product` = p.`id_product` AND psh.`id_shop` = ' . (int) $id_shop . "
                LEFT JOIN (SELECT `id_product_attribute`, GROUP_CONCAT(`product_supplier_reference` ORDER BY `id_supplier` ASC SEPARATOR '" . pSQL($this->multiple_value_separator) . "') AS `supplier_reference`, GROUP_CONCAT(`product_supplier_price_te` ORDER BY `id_supplier` ASC SEPARATOR '" . pSQL($this->multiple_value_separator) . "') AS `supplier_price` FROM `" . _DB_PREFIX_ . "product_supplier` GROUP BY `id_product_attribute`) ps ON ps.`id_product_attribute` = pa.`id_product_attribute`
                LEFT JOIN (SELECT `id_product_attribute`, GROUP_CONCAT(`id_image` SEPARATOR '" . pSQL($this->multiple_value_separator) . "') AS `images` FROM `" . _DB_PREFIX_ . 'product_attribute_image` GROUP BY `id_product_attribute`) pai ON pai.`id_product_attribute` = pa.`id_product_attribute`
                LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute_combination` pac ON pac.`id_product_attribute` = pa.`id_product_attribute`
                LEFT JOIN `' . _DB_PREFIX_ . 'attribute` a ON a.`id_attribute` = pac.`id_attribute`
                LEFT JOIN `' . _DB_PREFIX_ . 'attribute_lang` al ON al.`id_attribute` = a.`id_attribute` AND al.`id_lang` = ' . (int) $this->id_lang_default . '
                LEFT JOIN `' . _DB_PREFIX_ . 'attribute_group` ag ON ag.`id_attribute_group` = a.`id_attribute_group`
                LEFT JOIN `' . _DB_PREFIX_ . 'attribute_group_lang` agl ON agl.`id_attribute_group` = ag.`id_attribute_group` AND agl.`id_lang` = ' . (int) $this->id_lang_default . ' ';
            if (Configuration::get('PS_STOCK_MANAGEMENT') && (!is_null($quantity_from) || !is_null($quantity_to))) {
                $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'stock_available` sa ON (sa.`id_product_attribute` = pa.`id_product_attribute` AND sa.`id_product` = pa.`id_product` AND sa.`id_shop` = ' . (int) $id_shop . ') ';
            }
            $sql .= 'WHERE pash.`id_shop` = ' . (int) $id_shop . ' AND psh.`id_shop` = ' . (int) $id_shop . ' ';
            $sql .= count($already_loaded_shop_ids) > 0 ? 'AND p.`id_product` NOT IN (SELECT `id_product` FROM `' . _DB_PREFIX_ . 'product_shop` WHERE `id_shop` IN (' . implode(',', array_map('intval', $already_loaded_shop_ids)) . ')) ' : '';
            $sql .= $exclude_product_ids ? 'AND p.`id_product` NOT IN (' . implode(',', array_map('intval', $exclude_product_ids)) . ') ' : '';
            $sql .= ($this->product_status == 1 || $this->product_status == 0) ? 'AND psh.`active` = ' . (int) $this->product_status . ' ' : '';
            $sql .= !is_null($price_from) ? 'AND psh.`price` >= ' . (float) $price_from . ' ' : '';
            $sql .= !is_null($price_to) ? 'AND psh.`price` <= ' . (float) $price_to . ' ' : '';
            $sql .= $category_ids ? 'AND p.`id_product` IN (SELECT `id_product` FROM `' . _DB_PREFIX_ . 'category_product` WHERE `id_category` IN (' . implode(',', array_map('intval', $category_ids)) . ')) ' : '';
            $sql .= $disallowed_category_ids ? 'AND p.`id_product` NOT IN (SELECT `id_product` FROM `' . _DB_PREFIX_ . 'category_product` WHERE `id_category` IN (' . implode(',', array_map('intval', $disallowed_category_ids)) . ')) ' : '';
            $sql .= $supplier_ids ? 'AND p.`id_product` IN (SELECT DISTINCT `id_product` FROM `' . _DB_PREFIX_ . 'product_supplier` WHERE `id_supplier` IN (' . implode(',', array_map('intval', $supplier_ids)) . ')) ' : '';
            $sql .= $manufacturer_ids ? ' AND p.`id_manufacturer` IN (' . implode(',', array_map('intval', $manufacturer_ids)) . ') ' : '';
            $sql .= (Configuration::get('PS_STOCK_MANAGEMENT') && !is_null($quantity_from)) ? 'AND sa.`quantity` >= ' . (int) $quantity_from . ' ' : '';
            $sql .= (Configuration::get('PS_STOCK_MANAGEMENT') && !is_null($quantity_to)) ? 'AND sa.`quantity` <= ' . (int) $quantity_to . ' ' : '';
            $sql .= 'GROUP BY pa.`id_product_attribute` ORDER BY pa.`id_product` ASC, pa.`id_product_attribute` ASC';

            $combinations = Db::getInstance()->executeS($sql);

            $already_loaded_shop_ids[] = $id_shop;

            if (!$combinations || !is_array($combinations)) {
                continue;
            }

            foreach ($combinations as $combination) {
                $product = new Product($combination['id_product'], true);
                if (!Validate::isLoadedObject($product)) {
                    continue;
                }
                $data = [];
                foreach ($columns as $key => $column_name) {
                    $override_value = isset($override[$key]) ? $override[$key] : '';
                    if ($override_value != '') {
                        if ((preg_match("/^product_description_short_([\d]+)$/", $key) && $override_value == 'strip_tags')
                            || (preg_match("/^product_description_([\d]+)$/", $key) && $override_value == 'strip_tags')
                            || ($override_value == 'skip_product_if_empty')
                            || preg_match("/\[FORMULA:([\s\[\]\#\.\,\;\+\-\*\/\d]+)\]/", $override_value)
                            || preg_match("/\[CONVERT:\s*(.*?)\]/", $override_value)
                            || preg_match_all("/\[\%([a-zA-Z0-9\.\s\_\-\'\"\p{L}]+)\%\]/u", $override_value)) {
                            // Skip this override
                        } else {
                            $data[] = $override_value;
                            continue;
                        }
                    }

                    $value = $this->getCombinationDataByColumn($product, $combination, $key, $override_value, $id_shop, $id_currency, $id_country, $id_group, $languages, $defaultCurrency, $ruleCurrency, $warehouse_ids, $currency_iso_code_suffix, $weight_unit_suffix, $dimension_unit_suffix);

                    if ($value === '' && $override_value == 'skip_product_if_empty') {
                        continue 2;
                    }

                    // Check if override_value has [%column_key%]
                    if (preg_match_all("/\[\%([a-zA-Z0-9\.\s\_\-\'\"\p{L}]+)\%\]/u", $override_value, $column_concat_matches)) {
                        foreach ($column_concat_matches[0] as $column_concat_key => $column_concat_match) {
                            if (isset($column_concat_matches[1][$column_concat_key]) && $column_concat_matches[1][$column_concat_key]) {
                                $tmp_value = $this->getCombinationDataByColumn($product, $combination, $column_concat_matches[1][$column_concat_key], '', $id_shop, $id_currency, $id_country, $id_group, $languages, $defaultCurrency, $ruleCurrency, $warehouse_ids, '', '', '');
                                $override_value = str_replace($column_concat_match, $tmp_value, $override_value);
                            }
                        }
                    }

                    // Check if override_value has [FORMULA: ]
                    if (preg_match("/\[FORMULA:([\s\[\]\#\.\,\;\+\-\*\/\d]+)\]/", $override_value, $formula_match)) {
                        $formula_value = round((float) ElegantalEasyImportTools::getModifiedPriceByFormula((float) $value, $formula_match[1]), 2);
                        $override_value = str_replace($formula_match[0], $formula_value, $override_value);
                    }

                    // Check if override_value has [CONVERT: ]
                    if (preg_match("/\[CONVERT:\s*(.*?)\]/", $override_value, $convert_match)) {
                        $override_value = '';
                        $dictionaries = explode('|', $convert_match[1]);
                        if ($dictionaries) {
                            foreach ($dictionaries as $dict) {
                                $dict_key_value = explode('=>', $dict);
                                if (isset($dict_key_value[0], $dict_key_value[1]) && trim($dict_key_value[0]) === trim($value)) {
                                    $override_value = trim($dict_key_value[1]);
                                }
                            }
                        }
                    }

                    if ($override_value === '0cm*0cm*0cm' && strpos($key, 'custom_column') === 0) {
                        $override_value = '';
                    }

                    if ($override_value != '') {
                        $value = $override_value;
                    }

                    $data[] = $value;
                }
                if ($this->writeBody($data) !== false) {
                    ++$count;
                }
            }
        }

        return $count;
    }

    protected function getCombinationDataByColumn($product, $combination, $key, $override_value, $id_shop, $id_currency, $id_country, $id_group, $languages, $defaultCurrency, $ruleCurrency, $warehouse_ids, $currency_iso_code_suffix, $weight_unit_suffix, $dimension_unit_suffix)
    {
        $context = Context::getContext();
        $specific_price = SpecificPrice::getSpecificPrice((int) $product->id, $id_shop, $id_currency, $id_country, $id_group, 1, $combination['id_product_attribute']);
        $is_convert_currency = ($this->currency_id && $this->currency_id != $defaultCurrency->id && $ruleCurrency) ? true : false;

        $value = '';
        switch ($key) {
            case 'product_id':
                $value = $combination['id_product'];
                break;
            case 'product_reference':
                $value = $combination['product_reference'];
                break;
            case 'combination_id':
                $value = $combination['id_product_attribute'];
                break;
            case 'combination_reference':
                $value = $combination['reference'];
                break;
            case 'attribute_names':
                $value = $combination['Attributes'];
                break;
            case 'attribute_values':
                $value = $combination['Values'];
                break;
            case 'attribute_colors':
                // Get all attributes for this combination with color information
                $sql = 'SELECT pac.`id_attribute`, a.`color`, ag.`is_color_group`, al.`name`
                        FROM `' . _DB_PREFIX_ . 'product_attribute_combination` pac
                        LEFT JOIN `' . _DB_PREFIX_ . 'attribute` a ON a.`id_attribute` = pac.`id_attribute`
                        LEFT JOIN `' . _DB_PREFIX_ . 'attribute_group` ag ON ag.`id_attribute_group` = a.`id_attribute_group`
                        LEFT JOIN `' . _DB_PREFIX_ . 'attribute_lang` al ON al.`id_attribute` = a.`id_attribute` AND al.`id_lang` = ' . (int) $this->id_lang_default . '
                        WHERE pac.`id_product_attribute` = ' . (int) $combination['id_product_attribute'];
                
                $attributes = Db::getInstance()->executeS($sql);
                
                if ($attributes) {
                    $colors = [];
                    
                    foreach ($attributes as $attribute) {
                        // Only process if it's a color group and has a color value
                        if ($attribute['is_color_group'] && !empty($attribute['color'])) {
                            $colors[] = $attribute['color'];
                        }
                    }
                    
                    $value = implode($this->multiple_value_separator, $colors);
                }
                break;
            case 'attribute_textures':
                // Get all attributes for this combination
                $sql = 'SELECT pac.`id_attribute`, a.`color`, ag.`is_color_group` 
                        FROM `' . _DB_PREFIX_ . 'product_attribute_combination` pac
                        LEFT JOIN `' . _DB_PREFIX_ . 'attribute` a ON a.`id_attribute` = pac.`id_attribute`
                        LEFT JOIN `' . _DB_PREFIX_ . 'attribute_group` ag ON ag.`id_attribute_group` = a.`id_attribute_group`
                        WHERE pac.`id_product_attribute` = ' . (int) $combination['id_product_attribute'];
                
                $attributes = Db::getInstance()->executeS($sql);
                
                if ($attributes) {
                    $colors = [];
                    $textures = [];
                    
                    foreach ($attributes as $attribute) {
                        // Only process if it's a color group
                        if ($attribute['is_color_group']) {
                            if ($key == 'attribute_colors') {
                                // Add hex color if available
                                if (!empty($attribute['color'])) {
                                    $colors[] = $attribute['color'];
                                }
                            } else { // attribute_textures
                                // Check for texture image
                                $texture_path = _PS_COL_IMG_DIR_ . $attribute['id_attribute'] . '.jpg';
                                
                                // Check different image formats
                                $extensions = ['jpg', 'jpeg', 'png', 'gif'];
                                $texture_url = '';
                                
                                foreach ($extensions as $ext) {
                                    $check_path = _PS_COL_IMG_DIR_ . $attribute['id_attribute'] . '.' . $ext;
                                    if (file_exists($check_path)) {
                                        $texture_url = $context->link->getBaseLink() . 'img/co/' . $attribute['id_attribute'] . '.' . $ext;
                                        break;
                                    }
                                }
                                
                                if ($texture_url) {
                                    $textures[] = $texture_url;
                                }
                            }
                        }
                    }
                    
                    $value = $key == 'attribute_colors' 
                        ? implode($this->multiple_value_separator, $colors)
                        : implode($this->multiple_value_separator, $textures);
                }
                break;
            case 'supplier_reference':
                $value = $combination['supplier_reference'];
                break;
            case 'supplier_price':
                $value = $combination['supplier_price'];
                break;
            case 'ean':
                $value = $combination['ean13'];
                break;
            case 'upc':
                $value = $combination['upc'];
                break;
            case 'isbn':
                $value = isset($combination['isbn']) ? $combination['isbn'] : '';
                break;
            case 'mpn':
                $value = isset($combination['mpn']) ? $combination['mpn'] : '';
                break;
            case 'width':
                $value = isset($combination['width']) ? $combination['width'] . $dimension_unit_suffix : '';
                break;
            case 'height':
                $value = isset($combination['height']) ? $combination['height'] . $dimension_unit_suffix : '';
                break;
            case 'depth':
                $value = isset($combination['depth']) ? $combination['depth'] . $dimension_unit_suffix : '';
                break;
            case 'weight':
                $value = isset($combination['product_weight']) ? $combination['product_weight'] . $weight_unit_suffix : '';
                break;
            case 'wholesale_price':
                $value = (float) $combination['wholesale_price'];
                if ($is_convert_currency) {
                    $value = (float) Tools::convertPriceFull($value, $defaultCurrency, $ruleCurrency);
                }
                $value = round($value, 2) . $currency_iso_code_suffix;
                break;
            case 'impact_on_price':
                $value = $combination['price'];
                break;
            case 'impact_on_price_per_unit':
                $value = $combination['unit_price_impact'];
                break;
            case 'price_tax_excluded':
                $value = (float) Product::getPriceStatic($combination['id_product'], false, $combination['id_product_attribute'], 2, null, false, false) . $currency_iso_code_suffix;
                break;
            case 'price_tax_included':
                $value = (float) Product::getPriceStatic($combination['id_product'], true, $combination['id_product_attribute'], 2, null, false, false) . $currency_iso_code_suffix;
                break;
            case 'ecotax':
                $value = $combination['ecotax'];
                break;
            case 'quantity':
            case 'stock_availability':
                $value = StockAvailable::getQuantityAvailableByProduct($combination['id_product'], $combination['id_product_attribute'], $id_shop);
                if ($warehouse_ids) {
                    $sql = 'SELECT SUM(physical_quantity) as `quantity` FROM `' . _DB_PREFIX_ . 'stock`
                        WHERE `id_product` = ' . (int) $combination['id_product'] . ' AND `id_product_attribute` = ' . (int) $combination['id_product_attribute'] . ' AND `id_warehouse` IN (' . implode(',', array_map('intval', $warehouse_ids)) . ') ';
                    $value = (int) Db::getInstance()->getValue($sql);
                }
                if ($key == 'stock_availability') {
                    $value = $value > 0 ? 'in stock' : 'out of stock';
                }
                break;
            case 'minimal_quantity':
                $value = $combination['minimal_quantity'];
                break;
            case 'stock_location':
                $value = method_exists('StockAvailable', 'getLocation') ? call_user_func(['StockAvailable', 'getLocation'], $combination['id_product'], $combination['id_product_attribute'], $id_shop) : '';
                break;
            case 'low_stock_level':
                $value = isset($combination['low_stock_threshold']) ? $combination['low_stock_threshold'] : '';
                break;
            case 'email_alert_on_low_stock':
                $value = isset($combination['low_stock_alert']) ? $combination['low_stock_alert'] : '';
                break;
            case 'impact_on_weight':
                $value = $combination['weight'];
                break;
            case 'default':
                $value = (int) $combination['default_on'];
                break;
            case 'discounted_price_tax_excluded':
                $value = (float) Product::getPriceStatic($combination['id_product'], false, $combination['id_product_attribute'], 6, null, false, true);
                if ($is_convert_currency) {
                    $value = (float) Tools::convertPriceFull($value, $defaultCurrency, $ruleCurrency);
                }
                $value = round($value, 2) . $currency_iso_code_suffix;
                break;
            case 'discounted_price_tax_included':
                $value = (float) Product::getPriceStatic($combination['id_product'], true, $combination['id_product_attribute'], 6, null, false, true);
                if ($is_convert_currency) {
                    $value = (float) Tools::convertPriceFull($value, $defaultCurrency, $ruleCurrency);
                }
                $value = round($value, 2) . $currency_iso_code_suffix;
                break;
            case 'discounted_price_tax_excluded_if_discount_exists':
                $value = (float) Product::getPriceStatic($combination['id_product'], false, $combination['id_product_attribute'], 6, null, false, true);
                if ($is_convert_currency) {
                    $value = (float) Tools::convertPriceFull($value, $defaultCurrency, $ruleCurrency);
                }
                $value = round($value, 2);
                // Make this value empty if product discounted price is not less than product price
                $price_tax_excl = (float) Product::getPriceStatic($combination['id_product'], false, $combination['id_product_attribute'], 6, null, false, false);
                if ($is_convert_currency) {
                    $price_tax_excl = (float) Tools::convertPriceFull($price_tax_excl, $defaultCurrency, $ruleCurrency);
                }
                $price_tax_excl = round($price_tax_excl, 2);
                if ($value >= $price_tax_excl) {
                    $value = '';
                } else {
                    $value .= $currency_iso_code_suffix;
                }
                break;
            case 'discounted_price_tax_included_if_discount_exists':
                $value = (float) Product::getPriceStatic($combination['id_product'], true, $combination['id_product_attribute'], 6, null, false, true);
                if ($is_convert_currency) {
                    $value = (float) Tools::convertPriceFull($value, $defaultCurrency, $ruleCurrency);
                }
                $value = round($value, 2);
                // Make this value empty if product discounted price is not less than product price
                $price_tax_incl = (float) Product::getPriceStatic($combination['id_product'], true, $combination['id_product_attribute'], 6, null, false, false);
                if ($is_convert_currency) {
                    $price_tax_incl = (float) Tools::convertPriceFull($price_tax_incl, $defaultCurrency, $ruleCurrency);
                }
                $price_tax_incl = round($price_tax_incl, 2);
                if ($value >= $price_tax_incl) {
                    $value = '';
                } else {
                    $value .= $currency_iso_code_suffix;
                }
                break;
            case 'discount_percent':
                if ($specific_price && $specific_price['reduction_type'] == 'percentage') {
                    $value = round((float) $specific_price['reduction'] * 100, 6) . '%';
                }
                break;
            case 'discount_amount':
                if ($specific_price && $specific_price['reduction_type'] == 'amount') {
                    $value = round((float) $specific_price['reduction'], 6);
                }
                break;
            case 'discount_base_price':
                if ($specific_price && isset($specific_price['price']) && $specific_price['price'] >= 0) {
                    $value = $specific_price['price'];
                }
                break;
            case 'discount_starting_unit':
                if ($specific_price && isset($specific_price['from_quantity'])) {
                    $value = $specific_price['from_quantity'];
                }
                break;
            case 'discount_from':
                if ($specific_price && isset($specific_price['from']) && $specific_price['from'] && $specific_price['from'] != '0000-00-00 00:00:00' && strtotime($specific_price['from'])) {
                    $value = date('Y-m-d', strtotime($specific_price['from']));
                }
                break;
            case 'discount_to':
                if ($specific_price && isset($specific_price['to']) && $specific_price['to'] && $specific_price['to'] != '0000-00-00 00:00:00' && strtotime($specific_price['to'])) {
                    $value = date('Y-m-d', strtotime($specific_price['to']));
                }
                break;
            case 'available_date':
                $value = ($combination['available_date'] && $combination['available_date'] != '0000-00-00 00:00:00' && $combination['available_date'] != '0000-00-00') ? $combination['available_date'] : '';
                break;
            case 'images':
                if ($combination['images']) {
                    $combination_images = explode($this->multiple_value_separator, $combination['images']);
                    foreach ($combination_images as $combination_image_id) {
                        $value .= $value ? $this->multiple_value_separator : '';
                        $value .= $context->link->getImageLink($combination['product_reference'], $combination['id_product'] . '-' . $combination_image_id);
                    }
                }
                break;
            case preg_match("/^image_([\d]+)$/", $key, $image_match) ? true : false:
                if (isset($image_match[1]) && $combination['images']) {
                    $image_position_current = $image_match[1] - 1;
                    $combination_images = explode($this->multiple_value_separator, $combination['images']);
                    if (isset($combination_images[$image_position_current])) {
                        $value = $context->link->getImageLink($combination['product_reference'], $combination['id_product'] . '-' . $combination_images[$image_position_current]);
                    }
                }
                break;
            case preg_match("/^url_([\d]+)$/", $key, $url_match) ? true : false:
                $url_lang_id = isset($url_match[1]) ? (int) $url_match[1] : $this->id_lang_default;
                $value = $context->link->getProductLink($product, $product->link_rewrite, Category::getLinkRewrite($product->id_category_default, $url_lang_id), null, $url_lang_id, $id_shop, $combination['id_product_attribute']);
                break;
            case 'shop_id':
                $value = $id_shop;
                break;
            case 'shop_name':
                $shop = new Shop($id_shop);
                $value = $shop->name;
                break;
            case preg_match("/^custom_column_[\d]+$/", $key) ? true : false:
                break;
            case 'pproperties_quantity_step':
                $value = $combination['quantity_step'];
                break;
            case 'pproperties_minimal_quantity':
                $value = $combination['minimal_quantity_fractional'];
                break;
            default:
                if (substr($key, 0, 8) === 'product_') {
                    $value = $this->getProductDataByColumn($product, str_replace('product_', '', $key), $override_value, $id_shop, $id_currency, $id_country, $id_group, $languages, $defaultCurrency, $ruleCurrency, $warehouse_ids, $currency_iso_code_suffix, $weight_unit_suffix, $dimension_unit_suffix);
                }
                break;
        }

        return $value;
    }

    protected function writeHeader()
    {
        switch ($this->file_format) {
            case 'csv':
                $this->writeCsvHeader();
                break;
            case 'xml':
                $this->writeXmlHeader();
                break;
            case 'json':
                $this->writeJsonHeader();
                break;
            case 'xls':
            case 'xlsx':
            case 'ods':
                $this->writeExcelHeader();
                break;
            default:
                break;
        }
    }

    protected function writeCsvHeader()
    {
        $csv_header = [];
        $columns = ElegantalEasyImportTools::unstorable($this->columns);
        if (empty($columns) || !is_array($columns)) {
            throw new Exception('Columns not selected.');
        }
        foreach ($columns as $column_key => $column_name) {
            $csv_header[] = $column_name ? $column_name : $column_key;
        }
        $this->writeCsvBody($csv_header);
    }

    protected function writeXmlHeader()
    {
        $xml_root_node = ($this->entity == 'combination') ? '<COMBINATIONS>' . PHP_EOL : '<PRODUCTS>' . PHP_EOL;
        if ($this->xml_root_node) {
            $xml_root_node = '';
            $xml_root_nodes = explode('->', $this->xml_root_node);
            foreach ($xml_root_nodes as $key => $node) {
                if ($key == 0 && isset($xml_root_nodes[1]) && $node == 'rss' && $xml_root_nodes[1] == 'channel') {
                    // XML for Google Merchant Center
                    $xml_root_node .= '<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">' . PHP_EOL;
                } else {
                    $xml_root_node .= '<' . ElegantalEasyImportTools::xmlNodeNameFilter($node) . '>' . PHP_EOL;
                }
            }
        }
        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();
        $xmlWriter->setIndent(true);
        $xmlWriter->setIndentString('    ');
        $xmlWriter->startDocument('1.0', 'UTF-8');
        $xmlWriter->writeRaw($xml_root_node);
        fwrite($this->handle, $xmlWriter->flush(true));
    }

    protected function writeJsonHeader()
    {
        fwrite($this->handle, '[');
    }

    protected function writeExcelHeader()
    {
        $col = 1;
        $columns = ElegantalEasyImportTools::unstorable($this->columns);
        if (empty($columns) || !is_array($columns)) {
            throw new Exception('Columns not selected.');
        }
        foreach ($columns as $column_key => $column_name) {
            $title = $column_name ? $column_name : $column_key;
            $this->phpExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $title);
            ++$col;
        }
    }

    protected function writeBody($data)
    {
        if (empty($data) || !is_array($data)) {
            throw new Exception('No data to export.');
        }

        $result = false;
        switch ($this->file_format) {
            case 'csv':
                $result = $this->writeCsvBody($data);
                break;
            case 'xml':
                $result = $this->writeXmlBody($data);
                break;
            case 'json':
                $result = $this->writeJsonBody($data);
                break;
            case 'xls':
            case 'xlsx':
            case 'ods':
                $result = $this->writeExcelBody($data);
                break;
            case 'txt':
                $result = $this->writeTxtBody($data);
                break;
            default:
                break;
        }

        return $result;
    }

    protected function writeCsvBody($data)
    {
        $csv_delimiter = $this->csv_delimiter ? $this->csv_delimiter : ',';

        return fputcsv($this->handle, $data, $csv_delimiter, '"');
    }

    protected function writeXmlBody($data)
    {
        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();
        $xmlWriter->setIndent(true);
        $xmlWriter->setIndentString('    ');

        $current = 0;

        $xml_item_node = ElegantalEasyImportTools::xmlNodeNameFilter($this->xml_item_node);
        if (empty($xml_item_node)) {
            $xml_item_node = 'PRODUCT';
        }

        $xmlWriter->startElement($xml_item_node);
        $columns = ElegantalEasyImportTools::unstorable($this->columns);
        foreach ($columns as $column_key => $column_name) {
            $column_name = ElegantalEasyImportTools::xmlNodeNameFilter($column_name);
            $value = isset($data[$current]) ? $data[$current] : '';
            if (empty($value) || !preg_match("/[^A-Za-z0-9\%\@\.\,\:\;\|\-\_\(\)\s]/", $value)) {
                $xmlWriter->writeElement($column_name, $value);
            } else {
                $xmlWriter->startElement($column_name);
                $xmlWriter->writeCData($value);
                $xmlWriter->endElement();
            }
            ++$current;
        }
        $xmlWriter->endElement();

        return fwrite($this->handle, $xmlWriter->flush(true));
    }

    protected function writeJsonBody($data)
    {
        $current = 0;
        $product_array = [];
        $columns = ElegantalEasyImportTools::unstorable($this->columns);
        foreach ($columns as $column_name) {
            $product_array[$column_name] = isset($data[$current]) ? $data[$current] : '';
            ++$current;
        }

        return fwrite($this->handle, json_encode($product_array, JSON_PRETTY_PRINT) . ',' . PHP_EOL);
    }

    protected function writeExcelBody($data)
    {
        $col = 1;
        $row = $this->phpExcel->getActiveSheet()->getHighestRow() + 1;
        foreach ($data as $value) {
            $this->phpExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $value);
            ++$col;
        }

        return true;
    }

    protected function writeTxtBody($data)
    {
        return fputcsv($this->handle, $data, '|', '"');
    }

    protected function writeFooter()
    {
        switch ($this->file_format) {
            case 'xml':
                $this->writeXmlFooter();
                break;
            case 'json':
                $this->writeJsonFooter();
                break;
            case 'xls':
            case 'xlsx':
            case 'ods':
                $this->writeExcelFooter();
                break;
            default:
                break;
        }
    }

    protected function writeXmlFooter()
    {
        $xml_root_node = ($this->entity == 'combination') ? '</COMBINATIONS>' : '</PRODUCTS>';
        if ($this->xml_root_node) {
            $xml_root_node = '';
            $xml_root_nodes = explode('->', $this->xml_root_node);
            $xml_root_nodes = array_reverse($xml_root_nodes);
            foreach ($xml_root_nodes as $node) {
                $xml_root_node .= $xml_root_node ? PHP_EOL : '';
                $xml_root_node .= '</' . ElegantalEasyImportTools::xmlNodeNameFilter($node) . '>';
            }
        }
        $xmlWriter = new XMLWriter();
        $xmlWriter->openMemory();
        $xmlWriter->setIndent(true);
        $xmlWriter->setIndentString('    ');
        $xmlWriter->writeRaw($xml_root_node);
        fwrite($this->handle, $xmlWriter->flush(true));
    }

    protected function writeJsonFooter()
    {
        // Remove last comma and new line
        $stat = fstat($this->handle);
        if ($stat['size'] > 2) {
            ftruncate($this->handle, $stat['size'] - 2);
        }

        // Move file pointer to end
        fseek($this->handle, 0, SEEK_END);

        // Write closing bracket
        fwrite($this->handle, ']');
    }

    protected function writeExcelFooter()
    {
        $PHPExcel_IOFactory_Class = get_class($this->phpExcel) == 'PHPExcel' ? 'PHPExcel_IOFactory' : "\PhpOffice\PhpSpreadsheet\IOFactory";
        if ($PHPExcel_IOFactory_Class == 'PHPExcel_IOFactory') {
            switch ($this->file_format) {
                case 'xlsx':
                    $writerType = 'Excel2007';
                    break;
                case 'xls':
                    $writerType = 'Excel5';
                    break;
                case 'ods':
                    $writerType = 'OpenDocument';
                    break;
                default:
                    $writerType = 'Excel2007';
                    break;
            }
        } else {
            switch ($this->file_format) {
                case 'xlsx':
                    $writerType = 'Xlsx';
                    break;
                case 'xls':
                    $writerType = 'Xls';
                    break;
                case 'ods':
                    $writerType = 'Ods';
                    break;
                default:
                    $writerType = 'Xlsx';
                    break;
            }
        }
        $writer = $PHPExcel_IOFactory_Class::createWriter($this->phpExcel, $writerType);
        $writer->save($this->file_path);
    }
}
