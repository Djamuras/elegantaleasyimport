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
 * This is an object model class used to manage import rules
 */
class ElegantalEasyImportClass extends ElegantalEasyImportObjectModel
{
    public $tableName = 'elegantaleasyimport';
    public static $definition = [
        'table' => 'elegantaleasyimport',
        'primary' => 'id_elegantaleasyimport',
        'multishop' => true,
        'fields' => [
            'name' => ['type' => self::TYPE_STRING, 'size' => 255, 'validate' => 'isString', 'required' => true],
            'entity' => ['type' => self::TYPE_STRING, 'size' => 25, 'validate' => 'isString', 'required' => true],
            'supplier_id' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'],
            'map' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'map_default_values' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'csv_header' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'header_row' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'],
            'import_type' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true],
            'csv_file' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'csv_path' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'csv_url' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'csv_url_username' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'csv_url_password' => ['type' => self::TYPE_STRING, 'validate' => 'isString'], // Can hold Authorization Bearer token
            'csv_url_method' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'csv_url_post_params' => ['type' => self::TYPE_HTML, 'validate' => 'isString'],
            'ftp_host' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'ftp_port' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'ftp_username' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'ftp_password' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'ftp_file' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'is_cron' => ['type' => self::TYPE_BOOL, 'validate' => 'isUnsignedInt'],
            'cron_csv_file_size' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'],
            'cron_csv_file_md5' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'product_limit_per_request' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'],
            'product_range_to_import' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'email_to_send_notification' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'find_products_by' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'find_combinations_by' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'create_new_products' => ['type' => self::TYPE_BOOL, 'validate' => 'isUnsignedInt'],
            'update_existing_products' => ['type' => self::TYPE_BOOL, 'validate' => 'isUnsignedInt'],
            'update_products_on_all_shops' => ['type' => self::TYPE_BOOL, 'validate' => 'isUnsignedInt'],
            'force_id_product' => ['type' => self::TYPE_BOOL, 'validate' => 'isUnsignedInt'],
            'price_modifier' => ['type' => self::TYPE_STRING],
            'product_reference_modifier' => ['type' => self::TYPE_STRING],
            'min_price_amount' => ['type' => self::TYPE_FLOAT, 'validate' => 'isFloat'],
            'multiple_value_separator' => ['type' => self::TYPE_STRING],
            'multiple_subcategory_separator' => ['type' => self::TYPE_STRING],
            'is_associate_all_subcategories' => ['type' => self::TYPE_BOOL, 'validate' => 'isUnsignedInt'],
            'is_first_parent_root_for_categories' => ['type' => self::TYPE_BOOL, 'validate' => 'isUnsignedInt'],
            'decimal_char' => ['type' => self::TYPE_STRING],
            'shipping_package_size_unit' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'shipping_package_weight_unit' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'base_url_images' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'delete_old_combinations' => ['type' => self::TYPE_BOOL, 'validate' => 'isUnsignedInt'],
            'enable_new_products_by_default' => ['type' => self::TYPE_BOOL, 'validate' => 'isUnsignedInt'],
            'skip_if_no_stock' => ['type' => self::TYPE_BOOL, 'validate' => 'isUnsignedInt'],
            'enable_if_have_stock' => ['type' => self::TYPE_BOOL, 'validate' => 'isUnsignedInt'],
            'disable_if_no_stock' => ['type' => self::TYPE_BOOL, 'validate' => 'isUnsignedInt'],
            'disable_if_no_image' => ['type' => self::TYPE_BOOL, 'validate' => 'isUnsignedInt'],
            'enable_all_products_found_in_csv' => ['type' => self::TYPE_BOOL, 'validate' => 'isUnsignedInt'],
            'disable_all_products_not_found_in_csv' => ['type' => self::TYPE_BOOL, 'validate' => 'isUnsignedInt'],
            'deny_orders_when_no_stock_for_products_not_found_in_file' => ['type' => self::TYPE_BOOL, 'validate' => 'isUnsignedInt'],
            'delete_stock_for_products_not_found_in_csv' => ['type' => self::TYPE_BOOL, 'validate' => 'isUnsignedInt'],
            'is_utf8_encode' => ['type' => self::TYPE_BOOL, 'validate' => 'isUnsignedInt'],
            'other_settings' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'is_processing_now' => ['type' => self::TYPE_BOOL, 'validate' => 'isUnsignedInt'],
            'active' => ['type' => self::TYPE_BOOL, 'validate' => 'isUnsignedInt'],
        ],
    ];

    /**
     * Class fields
     *
     * @var mixed
     */
    public $id_elegantaleasyimport;
    public $name;
    public $entity;
    public $supplier_id;
    public $map;
    public $map_default_values;
    public $csv_header;
    public $header_row;
    public $import_type;
    public $csv_file;
    public $csv_path;
    public $csv_url;
    public $csv_url_username;
    public $csv_url_password;
    public $csv_url_method;
    public $csv_url_post_params;
    public $ftp_host;
    public $ftp_port;
    public $ftp_username;
    public $ftp_password;
    public $ftp_file;
    public $is_cron;
    public $cron_csv_file_size;
    public $cron_csv_file_md5;
    public $product_limit_per_request;
    public $product_range_to_import;
    public $email_to_send_notification;
    public $find_products_by;
    public $find_combinations_by;
    public $create_new_products;
    public $update_existing_products;
    public $update_products_on_all_shops;
    public $force_id_product;
    public $price_modifier;
    public $product_reference_modifier;
    public $min_price_amount;
    public $multiple_value_separator;
    public $multiple_subcategory_separator;
    public $is_associate_all_subcategories;
    public $is_first_parent_root_for_categories;
    public $decimal_char;
    public $shipping_package_size_unit;
    public $shipping_package_weight_unit;
    public $base_url_images;
    public $delete_old_combinations;
    public $enable_new_products_by_default;
    public $skip_if_no_stock;
    public $enable_if_have_stock;
    public $disable_if_no_stock;
    public $disable_if_no_image;
    public $enable_all_products_found_in_csv;
    public $disable_all_products_not_found_in_csv;
    public $deny_orders_when_no_stock_for_products_not_found_in_file;
    public $delete_stock_for_products_not_found_in_csv;
    public $is_utf8_encode;
    public $other_settings;
    public $is_processing_now;
    public $active;

    /**
     * History Object for current import process
     *
     * @var object
     */
    protected $currentHistory;

    /**
     * id_reference from file for current product being imported
     *
     * @var string
     */
    protected $current_id_reference;

    /**
     * Variables to cache data
     *
     * @var array
     */
    public $defaultMapProducts = [];
    public $defaultMapCombinations = [];
    protected $context;
    protected $rootCategory;
    protected $manufacturers = [];
    protected $suppliers = [];
    protected $carriers = [];
    protected $column_value_dictionary = [];
    protected $category_map_keys = [];
    protected $id_lang_default = 1;
    protected $id_all_langs = [];
    protected $id_other_langs = [];

    /**
     * Import Types
     *
     * @var int
     */
    public static $IMPORT_TYPE_UPLOAD = 1;
    public static $IMPORT_TYPE_PATH = 2;
    public static $IMPORT_TYPE_URL = 3;
    public static $IMPORT_TYPE_FTP = 4;
    public static $IMPORT_TYPE_SFTP = 5;

    /**
     * List of allowed file types for import
     *
     * @var array
     */
    public static $allowed_file_types = ['csv', 'xls', 'xlsx', 'xml', 'json', 'txt', 'ods', 'rss', 'asc']; // zip,gz can be added

    /**
     * List of allowed mime types for import
     *
     * @var array
     */
    public static $allowed_mime_types = [
        'text/xml',
        'text/html',
        'application/xml',
        'text/csv',
        'application/csv',
        'application/x-csv',
        'text/plain',
        'text/comma-separated-values',
        'text/x-comma-separated-values',
        'text/tab-separated-values',
        'application/octet-stream',
        'application/vnd.ms-excel',
        'application/vnd.ms-office',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.oasis.opendocument.spreadsheet',
        'application/json',
        'message/news',
        // 'application/zip',
        // 'application/gzip',
        // 'application/x-gzip',
    ];

    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        parent::__construct($id, $id_lang, $id_shop);

        $this->context = Context::getContext();

        if (!$this->id) {
            $this->header_row = 1;
            $this->is_first_parent_root_for_categories = 1;
        }

        if (method_exists('Shop', 'addTableAssociation')) {
            Shop::addTableAssociation($this->tableName, ['type' => 'shop']);
        }

        $this->id_lang_default = (int) Configuration::get('PS_LANG_DEFAULT');
        $this->id_all_langs[] = $this->id_lang_default;
        $languages = Language::getLanguages(true, false, true);
        foreach ($languages as $id_lang) {
            if ($id_lang != $this->id_lang_default) {
                $this->id_all_langs[] = $id_lang;
                $this->id_other_langs[] = $id_lang;
            }
        }

        $this->defaultMapProducts = ElegantalEasyImportMap::getDefaultMapProducts();
        $this->defaultMapCombinations = ElegantalEasyImportMap::getDefaultMapCombinations();
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getModelSettings()
    {
        $settings = $this->getModuleSettings();
        $model_settings = ElegantalEasyImportTools::unstorable($this->other_settings);
        if (!empty($model_settings)) {
            $settings = array_merge($settings, $model_settings);
        }

        return $settings;
    }

    /**
     * Reads CSV file and saves each row into database in bulk insert query
     *
     * @return int
     *
     * @throws Exception
     */
    public function saveCsvRows()
    {
        $id_shop = $this->context->shop->id;
        $context_shop = Shop::getContext();
        $settings = $this->getModelSettings();
        $id_reference_column = $this->getReferenceColumn();
        $id_reference_comb_column = $this->getReferenceColumnForCombination();
        $history = ElegantalEasyImportHistory::createNew($this->id);
        $skip_product_from_update_if_id_exists_in = array_filter(array_map('trim', explode(',', preg_replace('/[^0-9,]/', '', $settings['skip_product_from_update_if_id_exists_in']))), 'strlen'); // Remove empty items
        $skip_product_from_update_if_reference_has_sign = array_filter(array_map('trim', explode(',', $settings['skip_product_from_update_if_reference_has_sign'])), 'strlen');
        $skip_product_from_update_if_mpn_exists_in = array_filter(array_map('trim', explode(',', $settings['skip_product_from_update_if_mpn_exists_in'])), 'strlen');
        $product_ids_to_exclude_from_deactivation = array_filter(array_map('trim', explode(',', preg_replace('/[^0-9,]/', '', $settings['product_ids_to_exclude_from_deactivation']))), 'strlen');
        $multiple_value_separator = $this->multiple_value_separator;
        $multiple_subcategory_separator = $this->multiple_subcategory_separator;
        $update_products_on_all_shops = $this->update_products_on_all_shops && Shop::isFeatureActive();

        // Delete old csv rows if exists
        Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'elegantaleasyimport_data` WHERE `id_elegantaleasyimport` = ' . (int) $this->id);

        // We do not need to do anything because both create_new_products and update_existing_products are disabled
        if (!$this->create_new_products && !$this->update_existing_products) {
            return 0;
        }

        $file = ElegantalEasyImportTools::getRealPath($this->csv_file);
        if (!$file || !is_file($file) || !is_readable($file) || !filesize($file)) {
            throw new Exception('File not found or it is empty.');
        }

        $delimiter = ElegantalEasyImportCsv::identifyCsvDelimiter($file);
        $csv_header = ElegantalEasyImportCsv::getCsvHeaderRow($file, $this->header_row, $this->is_utf8_encode);

        $handle = fopen($file, 'r');
        if (!$handle) {
            throw new Exception('Cannot open file: ' . $file);
        }

        $map = $this->getMap();
        $map_default_values = $this->getMapDefaultValues();

        $ranges_to_import = [];
        if ($this->product_range_to_import) {
            $product_ranges = explode(';', $this->product_range_to_import);
            foreach ($product_ranges as $product_range) {
                $ranges = explode('-', $product_range);
                if (isset($ranges[0], $ranges[1]) && $ranges[1] >= $ranges[0]) {
                    $ranges_to_import[] = ['from' => $ranges[0], 'to' => $ranges[1]];
                }
            }
        }

        $row_count = 0;
        $insert_count = 0;
        $current_row = 0;
        while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
            ++$row_count;
            if ($this->header_row > 0 && $this->header_row >= $row_count) {
                continue;
            }
            // Check if non-empty row. Remove spaces & tabs and utf-8 BOM and then check length of line
            $line_str = preg_replace("/[\s\t\"]+/", '', implode('', $data));
            $line_str = str_replace("\xEF\xBB\xBF", '', $line_str);
            if (Tools::strlen($line_str) <= 0) {
                continue;
            }

            if ($this->is_utf8_encode) {
                $data = array_map(['ElegantalEasyImportTools', 'encodeUtf8'], $data);
            }

            $id_reference = '';
            if ($map['id_reference'] >= 0 && isset($data[$map['id_reference']])) {
                $id_reference = trim($data[$map['id_reference']]);
                $id_reference = trim($id_reference, "'");
                $id_reference = trim($id_reference, '"');
                $id_reference = preg_replace('/\xc2\xa0/', '', $id_reference); // NO-BREAK SPACE character \u00a0
                $id_reference = preg_replace('/[<>;={}\0]*/', '', $id_reference); // Need to use single quote here!
                // Product reference modifier. This works for product reference only.
                if ($this->find_products_by == 'reference' && $id_reference && strpos($this->product_reference_modifier, 'REFERENCE') !== false) {
                    $id_reference = str_replace('REFERENCE', $id_reference, $this->product_reference_modifier);
                }
                $data[$map['id_reference']] = $id_reference;
            }

            $id_reference_comb = '';
            if ($this->entity == 'combination' && isset($map['id_reference_comb']) && $map['id_reference_comb'] >= 0 && isset($data[$map['id_reference_comb']])) {
                $id_reference_comb = trim($data[$map['id_reference_comb']]);
                $id_reference_comb = trim($id_reference_comb, "'");
                $id_reference_comb = trim($id_reference_comb, '"');
                $id_reference_comb = preg_replace('/\xc2\xa0/', '', $id_reference_comb); // NO-BREAK SPACE character \u00a0
                $id_reference_comb = preg_replace('/[<>;={}\0]*/', '', $id_reference_comb); // Need to use single quote here!
                $data[$map['id_reference_comb']] = $id_reference_comb;
            }

            ++$current_row;

            if ($ranges_to_import) {
                $exist_in_range = false;
                foreach ($ranges_to_import as $range) {
                    if ($current_row >= $range['from'] && $current_row <= $range['to']) {
                        $exist_in_range = true;
                        break;
                    }
                }
                if (!$exist_in_range) {
                    continue;
                }
            }

            // Skip this product if its ID exists in this array
            if ($this->find_products_by == 'id' && $skip_product_from_update_if_id_exists_in && $id_reference && in_array($id_reference, $skip_product_from_update_if_id_exists_in)) {
                continue;
            }
            // Skip this product if the reference contains specified symbol
            // skip_product_from_update_if_reference_has_sign setting accepts multiple values separated by comma
            // We check if any symbols exist in $id_reference using str_replace method
            if ($this->find_products_by == 'reference' && $skip_product_from_update_if_reference_has_sign && $id_reference && str_replace($skip_product_from_update_if_reference_has_sign, '', $id_reference) != $id_reference) {
                continue;
            }
            // Skip this product if its MPN exists in this array
            if ($this->find_products_by == 'mpn' && $skip_product_from_update_if_mpn_exists_in && $id_reference && in_array($id_reference, $skip_product_from_update_if_mpn_exists_in)) {
                continue;
            }

            if ($this->entity == 'combination') {
                // Skip this combination if the combination reference contains specified symbol
                if ($this->find_combinations_by == 'combination_reference' && $skip_product_from_update_if_reference_has_sign && $id_reference_comb && str_replace($skip_product_from_update_if_reference_has_sign, '', $id_reference_comb) != $id_reference_comb) {
                    continue;
                }
                // Skip this combination if the combination MPN exists in this array
                if ($this->find_combinations_by == 'combination_mpn' && $skip_product_from_update_if_mpn_exists_in && $id_reference_comb && in_array($id_reference_comb, $skip_product_from_update_if_mpn_exists_in)) {
                    continue;
                }
            }

            // If "create_new_products" is disabled, make sure this product exists
            // If "update_existing_products" is disabled, make sure this product does not exists
            if (!$this->create_new_products || !$this->update_existing_products || ($this->create_new_products && $this->skip_if_no_stock)) {
                $quantity = (isset($data[$map['quantity']]) && $data[$map['quantity']]) ? trim($data[$map['quantity']]) : trim($map_default_values['quantity']);
                $quantity = (int) $this->getDictionaryValue('quantity', $quantity, $data, $csv_header);
                if ($this->entity == 'product') {
                    $product_exists = false;
                    if ($id_reference) {
                        $sql = 'SELECT DISTINCT p.`id_product` FROM `' . _DB_PREFIX_ . 'product` p ';
                        if (!$update_products_on_all_shops) {
                            $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_shop` psh ON (psh.`id_product` = p.`id_product` AND psh.`id_shop` = ' . (int) $id_shop . ') ';
                        }
                        if ($this->find_products_by == 'combination_reference' || $this->find_products_by == 'combination_ean') {
                            $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_attribute` pa ON (pa.`id_product` = p.`id_product`) ';
                        }
                        if ($this->find_products_by == 'supplier_reference' || $this->supplier_id) {
                            $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_supplier` ps ON (ps.`id_product` = p.`id_product` AND ps.`id_product_attribute` = 0) ';
                        }
                        $sql .= 'WHERE (' . $id_reference_column . " = '" . pSQL($id_reference) . "' OR " . $id_reference_column . " = '" . pSQL('MERGED_' . $id_reference) . "') ";
                        if ($this->supplier_id) {
                            $sql .= 'AND ps.`id_supplier` = ' . (int) $this->supplier_id . ' ';
                        }
                        $product_exists = Db::getInstance()->getValue($sql);
                    }
                    if ((!$this->create_new_products && !$product_exists)
                        || (!$this->update_existing_products && $product_exists)
                        || ($this->create_new_products && $this->skip_if_no_stock && !$product_exists && !$quantity)
                    ) {
                        continue;
                    }
                } elseif ($this->entity == 'combination') {
                    $combination_exists = false;
                    if ($id_reference_comb) {
                        $sql = 'SELECT DISTINCT pa.`id_product_attribute` FROM `' . _DB_PREFIX_ . 'product_attribute` pa ';
                        if (!$update_products_on_all_shops) {
                            $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_attribute_shop` pash ON (pash.`id_product_attribute` = pa.`id_product_attribute` AND pash.`id_shop` = ' . (int) $id_shop . ') ';
                        }
                        if ($this->supplier_id) {
                            // It is necessary for the product to have supplier, combination may not have supplier, it is not required!
                            // No need for AND ps.`id_product_attribute` = pa.`id_product_attribute`
                            $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_supplier` ps ON (ps.`id_product` = pa.`id_product`) ';
                        }
                        $sql .= 'WHERE pa.`id_product` > 0 AND (' . $id_reference_comb_column . " = '" . pSQL($id_reference_comb) . "' OR " . $id_reference_comb_column . " = '" . pSQL('MERGED_' . $id_reference_comb) . "') ";
                        if ($this->supplier_id) {
                            $sql .= 'AND ps.`id_supplier` = ' . (int) $this->supplier_id . ' ';
                        }
                        $combination_exists = (int) Db::getInstance()->getValue($sql);
                        if (!$combination_exists && $this->find_combinations_by == 'combination_supplier_reference') {
                            $sql = 'SELECT DISTINCT ps.`id_product_attribute` FROM `' . _DB_PREFIX_ . 'product_supplier` ps ';
                            if (!$update_products_on_all_shops) {
                                $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_attribute_shop` pash ON (pash.`id_product_attribute` = ps.`id_product_attribute` AND pash.`id_shop` = ' . (int) $id_shop . ') ';
                            }
                            $sql .= "WHERE ps.`id_product_attribute` > 0 AND (ps.`product_supplier_reference` = '" . pSQL($id_reference_comb) . "' OR ps.`product_supplier_reference` = '" . pSQL('MERGED_' . $id_reference_comb) . "') ";
                            if ($this->supplier_id) {
                                $sql .= 'AND ps.`id_supplier` = ' . (int) $this->supplier_id . ' ';
                            }
                            $combination_exists = (int) Db::getInstance()->getValue($sql);
                        }
                    }
                    if ((!$this->create_new_products && !$combination_exists)
                        || (!$this->update_existing_products && $combination_exists && !$this->delete_old_combinations)
                        || ($this->create_new_products && $this->skip_if_no_stock && !$combination_exists && !$quantity)
                    ) {
                        continue;
                    }
                }
            }

            // Check with category mapping
            $category_mapping = ElegantalEasyImportCategoryMap::getCategoryMappingByRule($this->id);
            if ($category_mapping) {
                // Prepare file categories for checking
                $categories_to_check = [];
                $line_categories_full = '';
                $category_map_keys = $this->getCategoryMapKeys($map);
                foreach ($category_map_keys as $category_map_key) {
                    if (isset($map[$category_map_key]) && $map[$category_map_key] >= 0 && isset($data[$map[$category_map_key]]) && $data[$map[$category_map_key]]) {
                        $line_categories_full .= $line_categories_full ? $multiple_value_separator : '';
                        $line_categories_full .= $data[$map[$category_map_key]];
                    }
                }
                if ($line_categories_full) {
                    $file_category_names = explode($multiple_value_separator, $line_categories_full);
                    $file_category_names = array_filter(array_map('trim', $file_category_names), 'strlen'); // Remove empty items
                    foreach ($file_category_names as $file_category_key => $file_category_name) {
                        if ($multiple_subcategory_separator) {
                            $file_subcategory_names = explode($multiple_subcategory_separator, $file_category_name);
                            $file_subcategory_names = array_filter(array_map('trim', $file_subcategory_names), 'strlen'); // Remove empty items
                            if ($file_subcategory_names) {
                                $subcategories_to_check = [];
                                foreach ($file_subcategory_names as $file_subcategory_key => $file_subcategory_name) {
                                    if (isset($subcategories_to_check[$file_subcategory_key - 1])) {
                                        $subcategories_to_check[] = $subcategories_to_check[$file_subcategory_key - 1] . $multiple_subcategory_separator . $file_subcategory_name;
                                    } else {
                                        $subcategories_to_check[] = $file_subcategory_name;
                                    }
                                }
                            }
                            $categories_to_check = array_merge($categories_to_check, $subcategories_to_check);
                        } else {
                            if (isset($categories_to_check[$file_category_key - 1])) {
                                $categories_to_check[] = $categories_to_check[$file_category_key - 1] . $multiple_value_separator . $file_category_name;
                            } else {
                                $categories_to_check[] = $file_category_name;
                            }
                        }
                    }
                }
                // Check if file categories are allowed/disallowed
                if ($categories_to_check) {
                    if (isset($category_mapping['categories_disallowed']) && $category_mapping['categories_disallowed']) {
                        $categories_disallowed_found = false;
                        foreach ($categories_to_check as $category_to_check) {
                            if (in_array($category_to_check, $category_mapping['categories_disallowed'])) {
                                $categories_disallowed_found = true;
                                break;
                            }
                        }
                        if ($categories_disallowed_found) {
                            continue; // Disallowed category found.
                        }
                    }
                    if (isset($category_mapping['categories_allowed']) && $category_mapping['categories_allowed']) {
                        $categories_allowed_found = false;
                        foreach ($categories_to_check as $category_to_check) {
                            if (in_array($category_to_check, $category_mapping['categories_allowed'])) {
                                $categories_allowed_found = true;
                                break;
                            }
                        }
                        if (!$categories_allowed_found) {
                            continue; // Allowed category not found.
                        }
                    }
                }
                // Check if file manufacturers are allowed/disallowed
                if ($map['manufacturer'] >= 0 && isset($data[$map['manufacturer']]) && $data[$map['manufacturer']]) {
                    if (isset($category_mapping['manufacturers_disallowed']) && $category_mapping['manufacturers_disallowed'] && in_array($data[$map['manufacturer']], $category_mapping['manufacturers_disallowed'])) {
                        continue; // Disallowed brand found.
                    }
                    if (isset($category_mapping['manufacturers_allowed']) && $category_mapping['manufacturers_allowed'] && !in_array($data[$map['manufacturer']], $category_mapping['manufacturers_allowed'])) {
                        continue; // Allowed brand not found.
                    }
                }
            }

            $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'elegantaleasyimport_data` (`id_elegantaleasyimport`, `id_reference`, `id_reference_comb`, `csv_row`)
                VALUES(' . (int) $this->id . ", '" . pSQL($id_reference) . "', '" . pSQL($id_reference_comb) . "', '" . pSQL(ElegantalEasyImportTools::storable($data)) . "'); " . PHP_EOL;
            if (Db::getInstance()->execute($sql) == false) {
                throw new Exception(Db::getInstance()->getMsgError() . ' SQL: ' . $sql);
            }
            ++$insert_count;

            // We need to update the history here because it may not reach to the end due to timeout or database error
            $history->total_number_of_products = $insert_count;
            $history->date_ended = date('Y-m-d H:i:s');
            $history->update();
        }
        fclose($handle);

        if ($insert_count < 1) {
            return 0;
        }

        $shop_ids = [];
        if ($update_products_on_all_shops) {
            $shop_groups = Shop::getTree();
            foreach ($shop_groups as $shop_group) {
                foreach ($shop_group['shops'] as $shop) {
                    $shop_ids[] = $shop['id_shop'];
                }
            }
        }
        if (empty($shop_ids)) {
            $shop_ids = [$id_shop];
        }

        // Enable products found in csv
        if ($this->enable_all_products_found_in_csv && $map['id_reference'] >= 0) {
            $sql = 'UPDATE `' . _DB_PREFIX_ . 'product_shop` psh
                INNER JOIN `' . _DB_PREFIX_ . 'product` p ON (psh.`id_product` = p.`id_product`) ';
            if ($this->find_products_by == 'combination_reference' || $this->find_products_by == 'combination_ean') {
                $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_attribute` pa ON (pa.`id_product` = p.`id_product`) ';
            }
            if ($this->find_products_by == 'supplier_reference' || $this->supplier_id) {
                $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_supplier` ps ON (ps.`id_product` = p.`id_product` AND ps.`id_product_attribute` = 0) ';
            }
            $sql .= 'SET psh.`active` = 1, p.`active` = 1
                WHERE ' . $id_reference_column . ' IN (SELECT c.`id_reference` FROM `' . _DB_PREFIX_ . 'elegantaleasyimport_data` c WHERE c.`id_elegantaleasyimport` = ' . (int) $this->id . ') ';
            if (!$update_products_on_all_shops) {
                $sql .= 'AND psh.`id_shop` = ' . (int) $id_shop . ' ';
            }
            if ($this->supplier_id) {
                $sql .= 'AND ps.`id_supplier` = ' . (int) $this->supplier_id . ' ';
            }
            if ($skip_product_from_update_if_id_exists_in) {
                $sql .= 'AND p.`id_product` NOT IN (' . implode(', ', array_map('intval', $skip_product_from_update_if_id_exists_in)) . ') ';
            }
            if ($skip_product_from_update_if_reference_has_sign) {
                foreach ($skip_product_from_update_if_reference_has_sign as $sign) {
                    $sql .= "AND p.`reference` NOT LIKE '%" . pSQL($sign) . "%' ";
                }
            }
            if ($skip_product_from_update_if_mpn_exists_in) {
                $sql .= "AND p.`mpn` NOT IN ('" . implode("', '", array_map('pSQL', $skip_product_from_update_if_mpn_exists_in)) . "') ";
            }
            if (Db::getInstance()->execute($sql) == false) {
                throw new Exception(Db::getInstance()->getMsgError() . ' SQL: ' . $sql);
            }
        }

        // Disable products not found in csv.
        // This works only if "Update existing products" is enabled. Otherwise it may disable many products even if they exist in file.
        // This works only if "Product range to import" is not used. Otherwise it may disable many products even if they exist in file.
        if ($this->disable_all_products_not_found_in_csv && $this->update_existing_products && !$this->product_range_to_import && $map['id_reference'] >= 0) {
            $sql = 'UPDATE `' . _DB_PREFIX_ . 'product_shop` psh
                INNER JOIN `' . _DB_PREFIX_ . 'product` p ON (psh.`id_product` = p.`id_product`) ';
            if ($this->find_products_by == 'combination_reference' || $this->find_products_by == 'combination_ean') {
                $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_attribute` pa ON (pa.`id_product` = p.`id_product`) ';
            }
            if ($this->find_products_by == 'supplier_reference' || $this->supplier_id) {
                $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_supplier` ps ON (ps.`id_product` = p.`id_product` AND ps.`id_product_attribute` = 0) ';
            }
            $sql .= 'SET psh.`active` = 0, p.`active` = 0
                WHERE ' . $id_reference_column . ' NOT IN (SELECT c.`id_reference` FROM `' . _DB_PREFIX_ . 'elegantaleasyimport_data` c WHERE c.`id_elegantaleasyimport` = ' . (int) $this->id . ') ';
            if (!$update_products_on_all_shops) {
                $sql .= 'AND psh.`id_shop` = ' . (int) $id_shop . ' ';
            }
            if ($this->supplier_id) {
                $sql .= 'AND ps.`id_supplier` = ' . (int) $this->supplier_id . ' ';
            }
            if ($product_ids_to_exclude_from_deactivation && is_array($product_ids_to_exclude_from_deactivation)) {
                $sql .= 'AND p.`id_product` NOT IN (' . implode(', ', array_map('intval', $product_ids_to_exclude_from_deactivation)) . ')';
            }
            if ($skip_product_from_update_if_id_exists_in) {
                $sql .= 'AND p.`id_product` NOT IN (' . implode(', ', array_map('intval', $skip_product_from_update_if_id_exists_in)) . ') ';
            }
            if ($skip_product_from_update_if_reference_has_sign) {
                foreach ($skip_product_from_update_if_reference_has_sign as $sign) {
                    $sql .= "AND p.`reference` NOT LIKE '%" . pSQL($sign) . "%' ";
                }
            }
            if ($skip_product_from_update_if_mpn_exists_in) {
                $sql .= "AND p.`mpn` NOT IN ('" . implode("', '", array_map('pSQL', $skip_product_from_update_if_mpn_exists_in)) . "') ";
            }
            if (Db::getInstance()->execute($sql) == false) {
                throw new Exception(Db::getInstance()->getMsgError() . ' SQL: ' . $sql);
            }
        }

        // Deny orders when out of stock for products not found in import file.
        // This works only if "Update existing products" is enabled. Otherwise it may deny many products even if they exist in file.
        // This works only if "Product range to import" is not used. Otherwise it may deny many products even if they exist in file.
        if ($this->deny_orders_when_no_stock_for_products_not_found_in_file && $this->update_existing_products && !$this->product_range_to_import && $map['id_reference'] >= 0) {
            $sql = 'UPDATE `' . _DB_PREFIX_ . 'stock_available` sa
                INNER JOIN `' . _DB_PREFIX_ . 'product` p ON (sa.`id_product` = p.`id_product`) ';
            if ($this->find_products_by == 'combination_reference' || $this->find_products_by == 'combination_ean') {
                $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_attribute` pa ON (pa.`id_product` = p.`id_product`) ';
            }
            if ($this->find_products_by == 'supplier_reference' || $this->supplier_id) {
                $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_supplier` ps ON (ps.`id_product` = p.`id_product` AND ps.`id_product_attribute` = 0) ';
            }
            $sql .= 'SET sa.`out_of_stock` = 0
                WHERE ' . $id_reference_column . ' NOT IN (SELECT c.`id_reference` FROM `' . _DB_PREFIX_ . 'elegantaleasyimport_data` c WHERE c.`id_elegantaleasyimport` = ' . (int) $this->id . ') ';
            if (!$update_products_on_all_shops) {
                $sql .= 'AND sa.`id_shop` = ' . (int) $id_shop . ' ';
            }
            if ($this->supplier_id) {
                $sql .= 'AND ps.`id_supplier` = ' . (int) $this->supplier_id . ' ';
            }
            if ($product_ids_to_exclude_from_deactivation && is_array($product_ids_to_exclude_from_deactivation)) {
                $sql .= 'AND p.`id_product` NOT IN (' . implode(', ', array_map('intval', $product_ids_to_exclude_from_deactivation)) . ')';
            }
            if ($skip_product_from_update_if_id_exists_in) {
                $sql .= 'AND p.`id_product` NOT IN (' . implode(', ', array_map('intval', $skip_product_from_update_if_id_exists_in)) . ') ';
            }
            if ($skip_product_from_update_if_reference_has_sign) {
                foreach ($skip_product_from_update_if_reference_has_sign as $sign) {
                    $sql .= "AND p.`reference` NOT LIKE '%" . pSQL($sign) . "%' ";
                }
            }
            if ($skip_product_from_update_if_mpn_exists_in) {
                $sql .= "AND p.`mpn` NOT IN ('" . implode("', '", array_map('pSQL', $skip_product_from_update_if_mpn_exists_in)) . "') ";
            }
            if (Db::getInstance()->execute($sql) == false) {
                throw new Exception(Db::getInstance()->getMsgError() . ' SQL: ' . $sql);
            }
        }

        // Delete stock for products/combinations not found in csv.
        // This works only if "Update existing products" is enabled. Otherwise it may disable many products even if they exist in file.
        // This works only if "Product range to import" is not used. Otherwise it may delete stock for many products even if they exist in file.
        if ($this->delete_stock_for_products_not_found_in_csv && $this->update_existing_products && !$this->product_range_to_import) {
            if ($this->entity == 'product' && $map['id_reference'] >= 0) {
                $sql = 'SELECT DISTINCT psh.`id_product` FROM `' . _DB_PREFIX_ . 'product_shop` psh
                    INNER JOIN `' . _DB_PREFIX_ . 'product` p ON (psh.`id_product` = p.`id_product`) ';
                if ($this->find_products_by == 'combination_reference' || $this->find_products_by == 'combination_ean') {
                    $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_attribute` pa ON (pa.`id_product` = p.`id_product`) ';
                }
                if ($this->find_products_by == 'supplier_reference' || $this->supplier_id) {
                    $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_supplier` ps ON (ps.`id_product` = p.`id_product` AND ps.`id_product_attribute` = 0) ';
                }
                $sql .= 'WHERE ' . $id_reference_column . ' NOT IN (SELECT c.`id_reference` FROM `' . _DB_PREFIX_ . 'elegantaleasyimport_data` c WHERE c.`id_elegantaleasyimport` = ' . (int) $this->id . ') ';
                if (!$update_products_on_all_shops) {
                    $sql .= 'AND psh.`id_shop` = ' . (int) $id_shop . ' ';
                }
                if ($this->supplier_id) {
                    $sql .= 'AND ps.`id_supplier` = ' . (int) $this->supplier_id . ' ';
                }
                if ($skip_product_from_update_if_id_exists_in) {
                    $sql .= 'AND p.`id_product` NOT IN (' . implode(', ', array_map('intval', $skip_product_from_update_if_id_exists_in)) . ') ';
                }
                if ($skip_product_from_update_if_reference_has_sign) {
                    foreach ($skip_product_from_update_if_reference_has_sign as $sign) {
                        $sql .= "AND p.`reference` NOT LIKE '%" . pSQL($sign) . "%' ";
                    }
                }
                if ($skip_product_from_update_if_mpn_exists_in) {
                    $sql .= "AND p.`mpn` NOT IN ('" . implode("', '", array_map('pSQL', $skip_product_from_update_if_mpn_exists_in)) . "') ";
                }
                $rows = Db::getInstance()->executeS($sql);
                if ($rows && is_array($rows)) {
                    Shop::setContext(Shop::CONTEXT_SHOP, $id_shop);
                    foreach ($rows as $row) {
                        $product = new Product($row['id_product']);
                        if (!Validate::isLoadedObject($product)) {
                            continue;
                        }
                        $combinations = $product->getAttributeCombinations($this->context->language->id);
                        if ($combinations && is_array($combinations)) {
                            foreach ($combinations as $combination) {
                                // Skip this combination if the combination reference contains specified symbol
                                if ($skip_product_from_update_if_reference_has_sign && $combination['reference'] && str_replace($skip_product_from_update_if_reference_has_sign, '', $combination['reference']) != $combination['reference']) {
                                    continue;
                                }
                                if ($skip_product_from_update_if_mpn_exists_in && $combination['mpn'] && in_array($combination['mpn'], $skip_product_from_update_if_mpn_exists_in)) {
                                    continue;
                                }
                                foreach ($shop_ids as $sh_id) {
                                    StockAvailable::setQuantity($row['id_product'], $combination['id_product_attribute'], 0, $sh_id);
                                }
                            }
                        } else {
                            foreach ($shop_ids as $sh_id) {
                                StockAvailable::setQuantity($row['id_product'], null, 0, $sh_id);
                            }
                        }
                    }
                    Shop::setContext($context_shop, $id_shop);
                }
            } elseif ($this->entity == 'combination' && !$this->delete_old_combinations && $map['id_reference_comb'] >= 0) {
                $sql = 'SELECT pa.`id_product`, pa.`id_product_attribute` FROM `' . _DB_PREFIX_ . 'product_attribute` pa ';
                if (!$update_products_on_all_shops) {
                    $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_attribute_shop` pash ON (pash.`id_product_attribute` = pa.`id_product_attribute` AND pash.`id_shop` = ' . (int) $id_shop . ') ';
                }
                if ($this->supplier_id || $this->find_combinations_by == 'combination_supplier_reference') {
                    $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_supplier` ps ON (ps.`id_product` = pa.`id_product`) ';
                }
                $sql .= 'WHERE pa.`id_product` > 0 AND ' . $id_reference_comb_column . ' NOT IN (SELECT c.`id_reference_comb` FROM `' . _DB_PREFIX_ . 'elegantaleasyimport_data` c WHERE c.`id_elegantaleasyimport` = ' . (int) $this->id . ') ';
                if ($this->find_combinations_by == 'combination_supplier_reference') {
                    $sql .= 'AND ps.`id_product_attribute` > 0 AND ps.`product_supplier_reference` NOT IN (SELECT c.`id_reference_comb` FROM `' . _DB_PREFIX_ . 'elegantaleasyimport_data` c WHERE c.`id_elegantaleasyimport` = ' . (int) $this->id . ') ';
                }
                if ($this->supplier_id) {
                    $sql .= 'AND ps.`id_supplier` = ' . (int) $this->supplier_id . ' ';
                }
                if ($skip_product_from_update_if_id_exists_in) {
                    $sql .= 'AND pa.`id_product` NOT IN (' . implode(', ', array_map('intval', $skip_product_from_update_if_id_exists_in)) . ') ';
                }
                if ($skip_product_from_update_if_reference_has_sign) {
                    foreach ($skip_product_from_update_if_reference_has_sign as $sign) {
                        $sql .= "AND pa.`reference` NOT LIKE '%" . pSQL($sign) . "%' ";
                    }
                }
                if ($skip_product_from_update_if_mpn_exists_in) {
                    $sql .= "AND pa.`mpn` NOT IN ('" . implode("', '", array_map('pSQL', $skip_product_from_update_if_mpn_exists_in)) . "') ";
                }
                $sql .= 'GROUP BY pa.`id_product`, pa.`id_product_attribute` ';
                $rows = Db::getInstance()->executeS($sql);
                if ($rows && is_array($rows)) {
                    Shop::setContext(Shop::CONTEXT_SHOP, $id_shop);
                    foreach ($rows as $row) {
                        foreach ($shop_ids as $sh_id) {
                            StockAvailable::setQuantity($row['id_product'], $row['id_product_attribute'], 0, $sh_id);
                        }
                    }
                    Shop::setContext($context_shop, $id_shop);
                }
            }
        }

        // Delete products not found in csv.
        // This works only if "Update existing products" is enabled. Otherwise it may disable many products even if they exist in file.
        // This works only if "Product range to import" is not used. Otherwise it may delete products even if they exist in file.
        if ($this->entity == 'product' && $settings['is_delete_products_not_exist_in_file'] && $this->update_existing_products && !$this->product_range_to_import && $map['id_reference'] >= 0) {
            $sql = 'SELECT DISTINCT psh.`id_product` FROM `' . _DB_PREFIX_ . 'product_shop` psh
                INNER JOIN `' . _DB_PREFIX_ . 'product` p ON (psh.`id_product` = p.`id_product`) ';
            if ($this->find_products_by == 'combination_reference' || $this->find_products_by == 'combination_ean') {
                $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_attribute` pa ON (pa.`id_product` = p.`id_product`) ';
            }
            if ($this->find_products_by == 'supplier_reference' || $this->supplier_id) {
                $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_supplier` ps ON (ps.`id_product` = p.`id_product` AND ps.`id_product_attribute` = 0) ';
            }
            $sql .= 'WHERE ' . $id_reference_column . ' NOT IN (SELECT c.`id_reference` FROM `' . _DB_PREFIX_ . 'elegantaleasyimport_data` c WHERE c.`id_elegantaleasyimport` = ' . (int) $this->id . ') ';
            if (!$update_products_on_all_shops) {
                $sql .= 'AND psh.`id_shop` = ' . (int) $id_shop . ' ';
            }
            if ($this->supplier_id) {
                $sql .= 'AND ps.`id_supplier` = ' . (int) $this->supplier_id . ' ';
            }
            if ($skip_product_from_update_if_id_exists_in) {
                $sql .= 'AND p.`id_product` NOT IN (' . implode(', ', array_map('intval', $skip_product_from_update_if_id_exists_in)) . ') ';
            }
            if ($skip_product_from_update_if_reference_has_sign) {
                foreach ($skip_product_from_update_if_reference_has_sign as $sign) {
                    $sql .= "AND p.`reference` NOT LIKE '%" . pSQL($sign) . "%' ";
                }
            }
            if ($skip_product_from_update_if_mpn_exists_in) {
                $sql .= "AND p.`mpn` NOT IN ('" . implode("', '", array_map('pSQL', $skip_product_from_update_if_mpn_exists_in)) . "') ";
            }
            $rows = Db::getInstance()->executeS($sql);
            if ($rows && is_array($rows)) {
                Shop::setContext(Shop::CONTEXT_SHOP, $id_shop);
                foreach ($rows as $row) {
                    $product = new Product($row['id_product']);
                    if (Validate::isLoadedObject($product)) {
                        $product->delete();
                    }
                }
                Shop::setContext($context_shop, $id_shop);
            }
        }
        if ($this->entity == 'combination' && $settings['is_delete_combinations_not_exist_in_file'] && !$this->delete_old_combinations && $this->update_existing_products && !$this->product_range_to_import && $map['id_reference_comb'] >= 0) {
            $sql = 'SELECT pa.`id_product`, pa.`id_product_attribute` FROM `' . _DB_PREFIX_ . 'product_attribute` pa ';
            if (!$update_products_on_all_shops) {
                $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_attribute_shop` pash ON (pash.`id_product_attribute` = pa.`id_product_attribute` AND pash.`id_shop` = ' . (int) $id_shop . ') ';
            }
            if ($this->supplier_id || $this->find_combinations_by == 'combination_supplier_reference') {
                $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_supplier` ps ON (ps.`id_product` = pa.`id_product`) ';
            }
            $sql .= 'WHERE pa.`id_product` > 0 AND ' . $id_reference_comb_column . ' NOT IN (SELECT c.`id_reference_comb` FROM `' . _DB_PREFIX_ . 'elegantaleasyimport_data` c WHERE c.`id_elegantaleasyimport` = ' . (int) $this->id . ') ';
            if ($this->find_combinations_by == 'combination_supplier_reference') {
                $sql .= 'AND ps.`id_product_attribute` > 0 AND ps.`product_supplier_reference` NOT IN (SELECT c.`id_reference_comb` FROM `' . _DB_PREFIX_ . 'elegantaleasyimport_data` c WHERE c.`id_elegantaleasyimport` = ' . (int) $this->id . ') ';
            }
            if ($this->supplier_id) {
                $sql .= 'AND ps.`id_supplier` = ' . (int) $this->supplier_id . ' ';
            }
            if ($skip_product_from_update_if_id_exists_in) {
                $sql .= 'AND pa.`id_product` NOT IN (' . implode(', ', array_map('intval', $skip_product_from_update_if_id_exists_in)) . ') ';
            }
            if ($skip_product_from_update_if_reference_has_sign) {
                foreach ($skip_product_from_update_if_reference_has_sign as $sign) {
                    $sql .= "AND pa.`reference` NOT LIKE '%" . pSQL($sign) . "%' ";
                }
            }
            if ($skip_product_from_update_if_mpn_exists_in) {
                $sql .= "AND pa.`mpn` NOT IN ('" . implode("', '", array_map('pSQL', $skip_product_from_update_if_mpn_exists_in)) . "') ";
            }
            $sql .= 'GROUP BY pa.`id_product`, pa.`id_product_attribute` ';
            $rows = Db::getInstance()->executeS($sql);
            if ($rows && is_array($rows)) {
                Shop::setContext(Shop::CONTEXT_SHOP, $id_shop);
                foreach ($rows as $row) {
                    $product = new Product($row['id_product']);
                    if (!Validate::isLoadedObject($product)) {
                        continue;
                    }
                    $depends_on_stock = StockAvailable::dependsOnStock($row['id_product']);
                    if ($depends_on_stock && StockAvailable::getQuantityAvailableByProduct($row['id_product'], $row['id_product_attribute'])) {
                        continue;
                    }
                    // Delete combination
                    $product->deleteAttributeCombination((int) $row['id_product_attribute']);
                    $product->checkDefaultAttributes();
                    Tools::clearColorListCache((int) $product->id);
                    if (!$product->hasAttributes()) {
                        $product->cache_default_attribute = 0;
                        $product->update();
                    } else {
                        Product::updateDefaultAttribute($row['id_product']);
                    }
                }
                Shop::setContext($context_shop, $id_shop);
            }
        }

        // Delete old combinations
        if ($this->entity == 'combination' && $this->delete_old_combinations) {
            $sql = 'DELETE ps FROM `' . _DB_PREFIX_ . 'product_supplier` ps
                INNER JOIN `' . _DB_PREFIX_ . 'product_attribute` pa ON ps.`id_product_attribute` = pa.`id_product_attribute`
                INNER JOIN `' . _DB_PREFIX_ . 'product` p ON pa.`id_product` = p.`id_product` ';
            if (!$update_products_on_all_shops) {
                $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_attribute_shop` pash ON (pash.`id_product_attribute` = pa.`id_product_attribute` AND pash.`id_shop` = ' . (int) $id_shop . ') ';
                $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_shop` psh ON (psh.`id_product` = p.`id_product` AND psh.`id_shop` = ' . (int) $id_shop . ') ';
            }
            $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'elegantaleasyimport_data` ec ON ' . $id_reference_column . ' = ec.`id_reference`
                WHERE ec.`id_elegantaleasyimport` = ' . (int) $this->id . ' ';
            if ($skip_product_from_update_if_id_exists_in) {
                $sql .= 'AND p.`id_product` NOT IN (' . implode(', ', array_map('intval', $skip_product_from_update_if_id_exists_in)) . ') ';
            }
            if ($skip_product_from_update_if_reference_has_sign) {
                foreach ($skip_product_from_update_if_reference_has_sign as $sign) {
                    $sql .= "AND p.`reference` NOT LIKE '%" . pSQL($sign) . "%' ";
                    $sql .= "AND pa.`reference` NOT LIKE '%" . pSQL($sign) . "%' ";
                }
            }
            if ($skip_product_from_update_if_mpn_exists_in) {
                $sql .= "AND p.`mpn` NOT IN ('" . implode("', '", array_map('pSQL', $skip_product_from_update_if_mpn_exists_in)) . "') ";
                $sql .= "AND pa.`mpn` NOT IN ('" . implode("', '", array_map('pSQL', $skip_product_from_update_if_mpn_exists_in)) . "') ";
            }
            Db::getInstance()->execute($sql);

            $sql = 'DELETE pac FROM `' . _DB_PREFIX_ . 'product_attribute_combination` pac
                INNER JOIN `' . _DB_PREFIX_ . 'product_attribute` pa ON pac.`id_product_attribute` = pa.`id_product_attribute`
                INNER JOIN `' . _DB_PREFIX_ . 'product` p ON pa.`id_product` = p.`id_product` ';
            if (!$update_products_on_all_shops) {
                $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_attribute_shop` pash ON (pash.`id_product_attribute` = pa.`id_product_attribute` AND pash.`id_shop` = ' . (int) $id_shop . ') ';
                $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_shop` psh ON (psh.`id_product` = p.`id_product` AND psh.`id_shop` = ' . (int) $id_shop . ') ';
            }
            if ($this->find_products_by == 'supplier_reference') {
                $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_supplier` ps ON (ps.`id_product` = pa.`id_product` AND ps.`id_product_attribute` = pa.`id_product_attribute`) ';
            }
            $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'elegantaleasyimport_data` ec ON ' . $id_reference_column . ' = ec.`id_reference`
                WHERE ec.`id_elegantaleasyimport` = ' . (int) $this->id . ' ';
            if ($skip_product_from_update_if_id_exists_in) {
                $sql .= 'AND p.`id_product` NOT IN (' . implode(', ', array_map('intval', $skip_product_from_update_if_id_exists_in)) . ') ';
            }
            if ($skip_product_from_update_if_reference_has_sign) {
                foreach ($skip_product_from_update_if_reference_has_sign as $sign) {
                    $sql .= "AND p.`reference` NOT LIKE '%" . pSQL($sign) . "%' ";
                    $sql .= "AND pa.`reference` NOT LIKE '%" . pSQL($sign) . "%' ";
                }
            }
            if ($skip_product_from_update_if_mpn_exists_in) {
                $sql .= "AND p.`mpn` NOT IN ('" . implode("', '", array_map('pSQL', $skip_product_from_update_if_mpn_exists_in)) . "') ";
                $sql .= "AND pa.`mpn` NOT IN ('" . implode("', '", array_map('pSQL', $skip_product_from_update_if_mpn_exists_in)) . "') ";
            }
            Db::getInstance()->execute($sql);

            $sql = 'DELETE pai FROM `' . _DB_PREFIX_ . 'product_attribute_image` pai
                INNER JOIN `' . _DB_PREFIX_ . 'product_attribute` pa ON pai.`id_product_attribute` = pa.`id_product_attribute`
                INNER JOIN `' . _DB_PREFIX_ . 'product` p ON pa.`id_product` = p.`id_product` ';
            if (!$update_products_on_all_shops) {
                $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_attribute_shop` pash ON (pash.`id_product_attribute` = pa.`id_product_attribute` AND pash.`id_shop` = ' . (int) $id_shop . ') ';
                $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_shop` psh ON (psh.`id_product` = p.`id_product` AND psh.`id_shop` = ' . (int) $id_shop . ') ';
            }
            if ($this->find_products_by == 'supplier_reference') {
                $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_supplier` ps ON (ps.`id_product` = pa.`id_product` AND ps.`id_product_attribute` = pa.`id_product_attribute`) ';
            }
            $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'elegantaleasyimport_data` ec ON ' . $id_reference_column . ' = ec.`id_reference`
                WHERE ec.`id_elegantaleasyimport` = ' . (int) $this->id . ' ';
            if ($skip_product_from_update_if_id_exists_in) {
                $sql .= 'AND p.`id_product` NOT IN (' . implode(', ', array_map('intval', $skip_product_from_update_if_id_exists_in)) . ') ';
            }
            if ($skip_product_from_update_if_reference_has_sign) {
                foreach ($skip_product_from_update_if_reference_has_sign as $sign) {
                    $sql .= "AND p.`reference` NOT LIKE '%" . pSQL($sign) . "%' ";
                    $sql .= "AND pa.`reference` NOT LIKE '%" . pSQL($sign) . "%' ";
                }
            }
            if ($skip_product_from_update_if_mpn_exists_in) {
                $sql .= "AND p.`mpn` NOT IN ('" . implode("', '", array_map('pSQL', $skip_product_from_update_if_mpn_exists_in)) . "') ";
                $sql .= "AND pa.`mpn` NOT IN ('" . implode("', '", array_map('pSQL', $skip_product_from_update_if_mpn_exists_in)) . "') ";
            }
            Db::getInstance()->execute($sql);

            $sql = 'DELETE sa FROM `' . _DB_PREFIX_ . 'stock_available` sa
                INNER JOIN `' . _DB_PREFIX_ . 'product_attribute` pa ON sa.`id_product_attribute` = pa.`id_product_attribute`
                INNER JOIN `' . _DB_PREFIX_ . 'product` p ON sa.`id_product` = p.`id_product` ';
            if (!$update_products_on_all_shops) {
                $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_attribute_shop` pash ON (pash.`id_product_attribute` = pa.`id_product_attribute` AND pash.`id_shop` = ' . (int) $id_shop . ') ';
                $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_shop` psh ON (psh.`id_product` = p.`id_product` AND psh.`id_shop` = ' . (int) $id_shop . ') ';
            }
            if ($this->find_products_by == 'supplier_reference') {
                $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_supplier` ps ON (ps.`id_product` = pa.`id_product` AND ps.`id_product_attribute` = pa.`id_product_attribute`) ';
            }
            $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'elegantaleasyimport_data` ec ON ' . $id_reference_column . ' = ec.`id_reference`
                WHERE sa.`id_product_attribute` != 0 AND ec.`id_elegantaleasyimport` = ' . (int) $this->id . ' ';
            if ($skip_product_from_update_if_id_exists_in) {
                $sql .= 'AND p.`id_product` NOT IN (' . implode(', ', array_map('intval', $skip_product_from_update_if_id_exists_in)) . ') ';
            }
            if ($skip_product_from_update_if_reference_has_sign) {
                foreach ($skip_product_from_update_if_reference_has_sign as $sign) {
                    $sql .= "AND p.`reference` NOT LIKE '%" . pSQL($sign) . "%' ";
                    $sql .= "AND pa.`reference` NOT LIKE '%" . pSQL($sign) . "%' ";
                }
            }
            if ($skip_product_from_update_if_mpn_exists_in) {
                $sql .= "AND p.`mpn` NOT IN ('" . implode("', '", array_map('pSQL', $skip_product_from_update_if_mpn_exists_in)) . "') ";
                $sql .= "AND pa.`mpn` NOT IN ('" . implode("', '", array_map('pSQL', $skip_product_from_update_if_mpn_exists_in)) . "') ";
            }
            Db::getInstance()->execute($sql);

            $sql = 'DELETE sp FROM `' . _DB_PREFIX_ . 'specific_price` sp
                INNER JOIN `' . _DB_PREFIX_ . 'product_attribute` pa ON sp.`id_product_attribute` = pa.`id_product_attribute`
                INNER JOIN `' . _DB_PREFIX_ . 'product` p ON sp.`id_product` = p.`id_product` ';
            if (!$update_products_on_all_shops) {
                $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_attribute_shop` pash ON (pash.`id_product_attribute` = pa.`id_product_attribute` AND pash.`id_shop` = ' . (int) $id_shop . ') ';
                $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_shop` psh ON (psh.`id_product` = p.`id_product` AND psh.`id_shop` = ' . (int) $id_shop . ') ';
            }
            if ($this->find_products_by == 'supplier_reference') {
                $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_supplier` ps ON (ps.`id_product` = pa.`id_product` AND ps.`id_product_attribute` = pa.`id_product_attribute`) ';
            }
            $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'elegantaleasyimport_data` ec ON ' . $id_reference_column . ' = ec.`id_reference`
                WHERE sp.`id_product_attribute` != 0 AND ec.`id_elegantaleasyimport` = ' . (int) $this->id . ' ';
            if ($skip_product_from_update_if_id_exists_in) {
                $sql .= 'AND p.`id_product` NOT IN (' . implode(', ', array_map('intval', $skip_product_from_update_if_id_exists_in)) . ') ';
            }
            if ($skip_product_from_update_if_reference_has_sign) {
                foreach ($skip_product_from_update_if_reference_has_sign as $sign) {
                    $sql .= "AND p.`reference` NOT LIKE '%" . pSQL($sign) . "%' ";
                    $sql .= "AND pa.`reference` NOT LIKE '%" . pSQL($sign) . "%' ";
                }
            }
            if ($skip_product_from_update_if_mpn_exists_in) {
                $sql .= "AND p.`mpn` NOT IN ('" . implode("', '", array_map('pSQL', $skip_product_from_update_if_mpn_exists_in)) . "') ";
                $sql .= "AND pa.`mpn` NOT IN ('" . implode("', '", array_map('pSQL', $skip_product_from_update_if_mpn_exists_in)) . "') ";
            }
            Db::getInstance()->execute($sql);

            $sql = 'DELETE pa FROM `' . _DB_PREFIX_ . 'product_attribute` pa
                INNER JOIN `' . _DB_PREFIX_ . 'product` p ON pa.`id_product` = p.`id_product` ';
            if (!$update_products_on_all_shops) {
                $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_attribute_shop` pash ON (pash.`id_product_attribute` = pa.`id_product_attribute` AND pash.`id_shop` = ' . (int) $id_shop . ') ';
                $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_shop` psh ON (psh.`id_product` = p.`id_product` AND psh.`id_shop` = ' . (int) $id_shop . ') ';
            }
            if ($this->find_products_by == 'supplier_reference') {
                $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_supplier` ps ON (ps.`id_product` = pa.`id_product` AND ps.`id_product_attribute` = pa.`id_product_attribute`) ';
            }
            $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'elegantaleasyimport_data` ec ON ' . $id_reference_column . ' = ec.`id_reference`
                WHERE ec.`id_elegantaleasyimport` = ' . (int) $this->id . ' ';
            if ($skip_product_from_update_if_id_exists_in) {
                $sql .= 'AND p.`id_product` NOT IN (' . implode(', ', array_map('intval', $skip_product_from_update_if_id_exists_in)) . ') ';
            }
            if ($skip_product_from_update_if_reference_has_sign) {
                foreach ($skip_product_from_update_if_reference_has_sign as $sign) {
                    $sql .= "AND p.`reference` NOT LIKE '%" . pSQL($sign) . "%' ";
                    $sql .= "AND pa.`reference` NOT LIKE '%" . pSQL($sign) . "%' ";
                }
            }
            if ($skip_product_from_update_if_mpn_exists_in) {
                $sql .= "AND p.`mpn` NOT IN ('" . implode("', '", array_map('pSQL', $skip_product_from_update_if_mpn_exists_in)) . "') ";
                $sql .= "AND pa.`mpn` NOT IN ('" . implode("', '", array_map('pSQL', $skip_product_from_update_if_mpn_exists_in)) . "') ";
            }
            Db::getInstance()->execute($sql);

            $sql = 'DELETE FROM `' . _DB_PREFIX_ . 'product_attribute_shop`
                WHERE `id_product_attribute` NOT IN (SELECT `id_product_attribute` FROM `' . _DB_PREFIX_ . 'product_attribute`) ';
            Db::getInstance()->execute($sql);

            Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'product_shop` SET `cache_default_attribute` = 0 WHERE `cache_default_attribute` IS NOT NULL AND `cache_default_attribute` != 0 AND `cache_default_attribute` NOT IN (SELECT DISTINCT `id_product_attribute` FROM `' . _DB_PREFIX_ . 'product_attribute`)');
            Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'product` SET `cache_default_attribute` = 0 WHERE `cache_default_attribute` IS NOT NULL AND `cache_default_attribute` != 0 AND `cache_default_attribute` NOT IN (SELECT DISTINCT `id_product_attribute` FROM `' . _DB_PREFIX_ . 'product_attribute`)');
        }

        return $insert_count;
    }

    public function import($limit)
    {
        // This currentHistory is used in other functions below
        $this->currentHistory = $this->getLastHistory();

        $settings = $this->getModelSettings();
        if (isset($settings['is_allow_hook_exec_after_product_save']) && !$settings['is_allow_hook_exec_after_product_save'] && !defined('PS_INSTALLATION_IN_PROGRESS')) {
            define('PS_INSTALLATION_IN_PROGRESS', true);
        }

        if ($this->entity == 'product') {
            $this->importProducts($limit);
        } elseif ($this->entity == 'combination') {
            $this->importCombinations($limit);
        } else {
            throw new Exception('Unknown import entity.');
        }

        $this->currentHistory->date_ended = date('Y-m-d H:i:s');
        $this->currentHistory->update();

        // Action to call at the end of the import process if the import was by CRON and all rows were processed
        if ($this->currentHistory->total_number_of_products == $this->currentHistory->number_of_products_processed) {
            $this->actionAfterImport();
        }

        $result = [
            'processed' => $this->currentHistory->number_of_products_processed,
            'created' => $this->currentHistory->number_of_products_created,
            'updated' => $this->currentHistory->number_of_products_updated,
            'deleted' => $this->currentHistory->number_of_products_deleted,
            'errors' => $this->currentHistory->getErrorsCount(),
        ];

        return $result;
    }

    public function importProducts($limit)
    {
        $map = $this->getMap();
        $map_default_values = $this->getMapDefaultValues();
        $file = ElegantalEasyImportTools::getRealPath($this->csv_file);
        $csv_header = ElegantalEasyImportCsv::getCsvHeaderRow($file, $this->header_row, $this->is_utf8_encode);
        $id_shop_context = $this->context->shop->id;
        $context_shop = Shop::getContext();
        $settings = $this->getModelSettings();
        $multiple_value_separator = $this->multiple_value_separator;
        $multiple_subcategory_separator = $this->multiple_subcategory_separator;
        $update_products_on_all_shops = $this->update_products_on_all_shops && Shop::isFeatureActive();
        $category_mapping = ElegantalEasyImportCategoryMap::getCategoryMappingByRule($this->id);
        $ps_weight_unit = Configuration::get('PS_WEIGHT_UNIT');
        $ps_dimension_unit = Configuration::get('PS_DIMENSION_UNIT');
        $skip_product_from_update_if_id_exists_in = array_filter(array_map('trim', explode(',', preg_replace('/[^0-9,]/', '', $settings['skip_product_from_update_if_id_exists_in']))), 'strlen'); // Remove empty items
        $skip_product_from_update_if_reference_has_sign = array_filter(array_map('trim', explode(',', $settings['skip_product_from_update_if_reference_has_sign'])), 'strlen');
        $skip_product_from_update_if_mpn_exists_in = array_filter(array_map('trim', explode(',', $settings['skip_product_from_update_if_mpn_exists_in'])), 'strlen');
        if (!$this->rootCategory) {
            $this->rootCategory = Category::getRootCategory();
        }
        $rootCategory = $this->rootCategory;

        $shop_ids = [];
        if ($update_products_on_all_shops) {
            $shop_groups = Shop::getTree();
            foreach ($shop_groups as $shop_group) {
                foreach ($shop_group['shops'] as $shop) {
                    $shop_ids[] = $shop['id_shop'];
                }
            }
        }
        if (empty($shop_ids)) {
            $shop_ids = [$id_shop_context];
        }

        $csvRows = ElegantalEasyImportData::model()->findAll([
            'condition' => [
                'id_elegantaleasyimport' => $this->id,
            ],
            'limit' => $limit,
        ]);

        foreach ($csvRows as $csvRow) {
            $csvRowModel = new ElegantalEasyImportData($csvRow['id_elegantaleasyimport_data']);
            if (!Validate::isLoadedObject($csvRowModel)) {
                continue;
            }

            $line = ElegantalEasyImportTools::unstorable($csvRowModel->csv_row);

            // We don't need this row in database anymore
            $csvRowModel->delete();

            ++$this->currentHistory->number_of_products_processed;

            // We need this update because the current row is already deleted and number_of_products_processed should be saved.
            // It may fail to save by the end of importing this row.
            $this->currentHistory->update();

            $id_reference = isset($line[$map['id_reference']]) ? $line[$map['id_reference']] : '';
            $this->current_id_reference = $id_reference;

            // If shop is given in import file, use it for the context
            $shop_map = (isset($line[$map['shop']]) && $line[$map['shop']]) ? $line[$map['shop']] : $map_default_values['shop'];
            $id_shop_map = $this->getShopIdByName($shop_map);
            if ($id_shop_map) {
                $id_shop = $id_shop_map;
                $shop_ids = [$id_shop];
                Shop::setContext(Shop::CONTEXT_SHOP, $id_shop);
            } else {
                $id_shop = $id_shop_context;
            }

            $id_reference_column = $this->getReferenceColumn();

            $products_rows = [];
            if ($id_reference) {
                $sql = 'SELECT DISTINCT p.`id_product` FROM `' . _DB_PREFIX_ . 'product` p ';
                if (!$update_products_on_all_shops) {
                    $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_shop` psh ON (psh.`id_product` = p.`id_product` AND psh.`id_shop` = ' . (int) $id_shop . ') ';
                }
                if ($this->find_products_by == 'combination_reference' || $this->find_products_by == 'combination_ean') {
                    $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_attribute` pa ON (pa.`id_product` = p.`id_product`) ';
                }
                if ($this->find_products_by == 'supplier_reference' || $this->supplier_id) {
                    $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_supplier` ps ON (ps.`id_product` = p.`id_product` AND ps.`id_product_attribute` = 0) ';
                }
                $sql .= 'WHERE (' . $id_reference_column . " = '" . pSQL($id_reference) . "' OR " . $id_reference_column . " = '" . pSQL('MERGED_' . $id_reference) . "') ";
                if ($this->supplier_id) {
                    $sql .= 'AND ps.`id_supplier` = ' . (int) $this->supplier_id . ' ';
                }
                $products_rows = Db::getInstance()->executeS($sql);
            }
            if (empty($products_rows) || !isset($products_rows[0]['id_product'])) {
                if ($settings['is_assign_product_from_another_shop_if_it_already_exists'] && $this->create_new_products && Shop::isFeatureActive()) {
                    // Maybe there is a product with the same reference in another shop. If yes, assign it to this shop.
                    $sql = 'SELECT psh.* FROM `' . _DB_PREFIX_ . 'product_shop` psh
                        INNER JOIN `' . _DB_PREFIX_ . 'product` p ON p.`id_product` = psh.`id_product` ';
                    if ($this->find_products_by == 'combination_reference' || $this->find_products_by == 'combination_ean') {
                        $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_attribute` pa ON (pa.`id_product` = p.`id_product`) ';
                    }
                    if ($this->find_products_by == 'supplier_reference' || $this->supplier_id) {
                        $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_supplier` ps ON (ps.`id_product` = p.`id_product` AND ps.`id_product_attribute` = 0) ';
                    }
                    $sql .= 'WHERE (' . $id_reference_column . " = '" . pSQL($id_reference) . "' OR " . $id_reference_column . " = '" . pSQL('MERGED_' . $id_reference) . "') AND psh.`id_shop` != " . (int) $id_shop;
                    $product_exists_in_other_shop = Db::getInstance()->getRow($sql);
                    if ($product_exists_in_other_shop) {
                        unset($product_exists_in_other_shop['id_shop']);
                        $sql = 'INSERT IGNORE INTO `' . _DB_PREFIX_ . 'product_shop` (`' . implode('`, `', array_keys($product_exists_in_other_shop)) . "`, `id_shop`)
                            VALUES ('" . implode("', '", $product_exists_in_other_shop) . "', " . (int) $id_shop . ')';
                        if (Db::getInstance()->execute($sql)) {
                            $products_rows = [
                                ['id_product' => $product_exists_in_other_shop['id_product']],
                            ];
                        }
                    }
                }
            }
            if (empty($products_rows) || !isset($products_rows[0]['id_product'])) {
                $products_rows = [
                    ['id_product' => null],
                ];
            }

            foreach ($products_rows as $product_row) {
                try {
                    $product = null;
                    $id_product_attribute = 0;
                    $product_categories_ids = [];
                    $current_category_to_replace = '';

                    if ($product_row && isset($product_row['id_product']) && $product_row['id_product'] > 0) {
                        $id_shop_for_product = $id_shop;
                        if ($update_products_on_all_shops) {
                            if (isset($product_row['id_shop_default']) && $product_row['id_shop_default'] != $id_shop) {
                                $id_shop_for_product = $product_row['id_shop_default'];
                            }
                        } elseif ($this->find_products_by != 'id') {
                            // Check if product exists in context shop
                            $sql = 'SELECT `id_product` FROM `' . _DB_PREFIX_ . 'product_shop` WHERE `id_product` = ' . (int) $product_row['id_product'] . ' AND `id_shop` = ' . (int) $id_shop;
                            $product_exists_in_current_shop = (int) Db::getInstance()->getValue($sql);
                            if (!$product_exists_in_current_shop) {
                                continue;
                            }
                        }
                        $product = new Product($product_row['id_product'], false, null, $id_shop_for_product);
                    }
                    if ($product_row && isset($product_row['id_product_attribute']) && $product_row['id_product_attribute'] > 0) {
                        $id_product_attribute = (int) $product_row['id_product_attribute'];
                    }

                    if (Validate::isLoadedObject($product)) {
                        if ($skip_product_from_update_if_id_exists_in && in_array($product->id, $skip_product_from_update_if_id_exists_in)) {
                            continue;
                        }
                        if ($skip_product_from_update_if_reference_has_sign && $product->reference && str_replace($skip_product_from_update_if_reference_has_sign, '', $product->reference) != $product->reference) {
                            continue;
                        }
                        if ($skip_product_from_update_if_mpn_exists_in && $product->mpn && in_array($product->mpn, $skip_product_from_update_if_mpn_exists_in)) {
                            continue;
                        }
                        if ($this->update_existing_products) {
                            $delete_product = ($map['delete_product'] >= 0 && isset($line[$map['delete_product']]) && $line[$map['delete_product']]) ? $line[$map['delete_product']] : $map_default_values['delete_product'];
                            if ($delete_product && $this->isCsvValueTrue($delete_product)) {
                                if ($product->delete() && $settings['employee_id_for_events_log']) {
                                    PrestaShopLogger::addLog('Product deletion', 1, null, 'Product', $product->id, true, (int) $settings['employee_id_for_events_log']);
                                }
                                ++$this->currentHistory->number_of_products_deleted;
                                $this->currentHistory->ids_of_products_deleted .= ($this->currentHistory->ids_of_products_deleted ? ',' : '') . $product->id;
                                continue;
                            }
                            // Load additional properties to the product
                            $product->quantity = StockAvailable::getQuantityAvailableByProduct($product->id, $id_product_attribute);
                            $product->tax_rate = $product->getTaxesRate(new Address());
                            $product->out_of_stock = StockAvailable::outOfStock($product->id);
                            $product->depends_on_stock = (int) StockAvailable::dependsOnStock($product->id);
                        } else {
                            $product = null;
                            // $this->addError('Updating product not allowed.');
                        }
                    } else {
                        if (!$this->create_new_products) {
                            continue;
                        }
                        if ($this->create_new_products && (($map['name_' . $this->id_lang_default] >= 0 && isset($line[$map['name_' . $this->id_lang_default]]) && trim($line[$map['name_' . $this->id_lang_default]])) || trim($map_default_values['name_' . $this->id_lang_default])) && ($map['id_reference'] < 0 || $id_reference)) {
                            // New product must have name and reference must be either skipped or must have value, because empty reference is not allowed for new products.
                            // Sometimes supplier may provide empty value for SKU and if the same product already exists, it gets duplicated.
                            $product = new Product();
                        } else {
                            $product = null;
                            if (($map['name_' . $this->id_lang_default] < 0 || $line[$map['name_' . $this->id_lang_default]] == '') && empty($map_default_values['name_' . $this->id_lang_default])) {
                                $this->addError('Name is required for new product.');
                            } elseif ($map['id_reference'] >= 0 && empty($id_reference)) {
                                $this->addError('Reference cannot be empty for new product.');
                            } else {
                                $this->addError('New product could not be created.');
                            }
                        }
                    }

                    if (!$product) {
                        continue;
                    }

                    $currency = (isset($line[$map['price_currency']]) && $line[$map['price_currency']]) ? $line[$map['price_currency']] : $map_default_values['price_currency'];
                    if ($currency) {
                        $currency = $this->getDictionaryValue('price_currency', $currency, $line, $csv_header);
                    }

                    foreach ($map as $attr => $index) {
                        // Skip if neither mapped nor provided default value
                        if ($index < 0 && $map_default_values[$attr] === '') {
                            continue;
                        }

                        $value = isset($line[$index]) ? $line[$index] : '';
                        $value_default = isset($map_default_values[$attr]) ? $this->getDictionaryValue($attr, $map_default_values[$attr], $line, $csv_header) : '';
                        $value = ($value === '') ? trim($value_default) : trim($value);
                        $value = $this->getDictionaryValue($attr, $value, $line, $csv_header);

                        switch ($value_default) {
                            case 'strip_tags':
                                $value = strip_tags($value);
                                break;
                            case 'nl2br':
                                $value = nl2br($value);
                                break;
                            default:
                                break;
                        }

                        switch ($attr) {
                            case 'reference':
                                if ($value && Validate::isReference($value)) {
                                    $product->reference = $value;
                                }
                                break;
                            case preg_match("/^name_([\d]+)$/", $attr, $match) ? true : false:
                                if ($value) {
                                    $value = preg_replace('/\s+/', ' ', $value);
                                    $value = Tools::substr(preg_replace('/[<>;=#{}]*/', '', $value), 0, 128);
                                    if (!Validate::isCatalogName($value) && !$this->is_utf8_encode) {
                                        $value = ElegantalEasyImportTools::encodeUtf8($value);
                                    }
                                    $product->name[$match[1]] = $value;
                                }
                                break;
                            case 'enabled':
                                $product->active = $this->isCsvValueFalse($value) ? 0 : 1;
                                break;
                            case 'ean':
                                if ($this->find_products_by == 'ean') {
                                    $product->ean13 = $value;
                                } else {
                                    if ($value && Validate::isEan13($value)) {
                                        $product->ean13 = $value;
                                    } else {
                                        $product->ean13 = '';
                                        if ($value) {
                                            $this->addError('EAN is not valid: ' . $value, $product);
                                        }
                                    }
                                }
                                break;
                            case 'upc_barcode':
                                if ($value && Validate::isUpc($value)) {
                                    $product->upc = $value;
                                } else {
                                    $product->upc = '';
                                    if ($value) {
                                        $this->addError('UPC is not valid: ' . $value, $product);
                                    }
                                }
                                break;
                            case 'isbn':
                                $value = str_replace(',', '.', $value);
                                $value = preg_replace('/[^0-9-]/', '', $value);
                                $product->isbn = $value ? Tools::substr($value, 0, 32) : '';
                                break;
                            case 'mpn':
                                $product->mpn = $value ? Tools::substr($value, 0, 40) : '';
                                break;
                            case preg_match("/^meta_title_([\d]+)$/", $attr, $match) ? true : false:
                                $value = preg_replace('/[<>={}]*/', '', $value);
                                $product->meta_title[$match[1]] = Tools::substr($value, 0, 128);
                                break;
                            case preg_match("/^meta_description_([\d]+)$/", $attr, $match) ? true : false:
                                $value = ElegantalEasyImportTools::cleanDescription($value);
                                $value = strip_tags($value);
                                $value = preg_replace('/[<>={}]*/', '', $value);
                                $product->meta_description[$match[1]] = Tools::substr($value, 0, 255);
                                break;
                            case preg_match("/^meta_keywords_([\d]+)$/", $attr, $match) ? true : false:
                                $value = strip_tags($value);
                                $value = preg_replace('/[<>={}]*/', '', $value);
                                $product->meta_keywords[$match[1]] = Tools::substr($value, 0, 255);
                                break;
                            case preg_match("/^friendly_url_([\d]+)$/", $attr, $match) ? true : false:
                                if ($value) {
                                    $product->link_rewrite[$match[1]] = Tools::link_rewrite(Tools::substr($value, 0, 128));
                                }
                                break;
                            case preg_match("/^short_description_([\d]+)$/", $attr, $match) ? true : false:
                                $value = ElegantalEasyImportTools::cleanDescription($value);
                                if ($product->validateField('description_short', $value, $match[1]) !== true) {
                                    $short_desc_limit = (int) Configuration::get('PS_PRODUCT_SHORT_DESC_LIMIT');
                                    if ($short_desc_limit <= 0) {
                                        $short_desc_limit = 800;
                                    }
                                    // Replace br tag with such ugly way because of Validator requirement
                                    $br_tags = [];
                                    $br_tags[0] = '<';
                                    $br_tags[0] .= 'br';
                                    $br_tags[0] .= ' /';
                                    $br_tags[0] .= '>';
                                    $br_tags[1] = '<';
                                    $br_tags[1] .= 'br';
                                    $br_tags[1] .= '>';
                                    $br_tags[2] = '<';
                                    $br_tags[2] .= 'br';
                                    $br_tags[2] .= '/';
                                    $br_tags[2] .= '>';
                                    $value = str_ireplace($br_tags, PHP_EOL, $value);
                                    $value = strip_tags($value);
                                    $value = Tools::substr($value, 0, $short_desc_limit);
                                }
                                // Wrapping with div is needed because of PS bug
                                $wrap1 = '<';
                                $wrap1 .= 'div';
                                $wrap1 .= '>';
                                $wrap2 = '<';
                                $wrap2 .= '/';
                                $wrap2 .= 'div';
                                $wrap2 .= '>';
                                $product->description_short[$match[1]] = $wrap1 . $value . $wrap2;
                                break;
                            case preg_match("/^long_description_([\d]+)$/", $attr, $match) ? true : false:
                                $value = ElegantalEasyImportTools::cleanDescription($value);
                                if ($value && Validate::isAbsoluteUrl($value)) {
                                    // If the value is URL, wrap it inside iframe
                                    if ($map_default_values['long_description_' . $match[1]] == 'iframe') {
                                        $module = $this->getModule();
                                        $value = $module->getIframeHtmlCode($value);
                                    } elseif ($map_default_values['long_description_' . $match[1]] == 'html') {
                                        $value = Tools::file_get_contents($value);
                                        $value = ElegantalEasyImportTools::cleanDescription($value);
                                    }
                                } elseif (strpos($value, '</iframe>') !== false && strpos($value, 'youtu') !== false) {
                                    // If there are iframes with watch link, convert the link to embed
                                    preg_match_all("/(<iframe\s+.*?\s+src=\")(.*?)(\".*?<\/iframe>)/", $value, $matches_iframe);
                                    if ($matches_iframe && isset($matches_iframe[0]) && $matches_iframe[0]) {
                                        foreach ($matches_iframe[0] as $iframe_index => $iframe_tag) {
                                            if (isset($matches_iframe[2][$iframe_index]) && $matches_iframe[2][$iframe_index]) {
                                                $youtube_id = '';
                                                if (preg_match('/youtube.com\/((?:embed)|(?:watch))((?:\?v\=)|(?:\/))([a-zA-Z0-9_-]+)/i', $matches_iframe[2][$iframe_index], $match_youtube)) {
                                                    $youtube_id = $match_youtube[count($match_youtube) - 1];
                                                } elseif (preg_match('/youtu.be\/([a-zA-Z0-9_-]+)\??/i', $matches_iframe[2][$iframe_index], $match_youtube)) {
                                                    $youtube_id = $match_youtube[count($match_youtube) - 1];
                                                }
                                                $youtube_link = $youtube_id ? 'https://www.youtube.com/embed/' . $youtube_id : $matches_iframe[2][$iframe_index];
                                                $value = str_replace($iframe_tag, $matches_iframe[1][$iframe_index] . $youtube_link . $matches_iframe[3][$iframe_index], $value);
                                            } else {
                                                $value = str_replace($iframe_tag, '', $value);
                                            }
                                        }
                                    }
                                }
                                $product->description[$match[1]] = $value;
                                break;
                            case 'wholesale_price':
                                if (preg_match("/\[FORMULA:([\s\[\]\#\.\,\;\+\-\*\/\d]+)\]/", $value, $match)) {
                                    // No mapping selected for wholesale_price column and map_default_values contains formula.
                                    // So base price is 0 and calculation result will be final value.
                                    // map_default_values may contain other columns e.g. [%column%]
                                    $product->wholesale_price = (float) ElegantalEasyImportTools::getModifiedPriceByFormula(0, $match[1], $this->decimal_char);
                                } else {
                                    $product->wholesale_price = (float) $this->extractPriceInDefaultCurrency($value, $currency);
                                    if ($value_default && preg_match("/\[FORMULA:([\s\[\]\#\.\,\;\+\-\*\/\d]+)\]/", $value_default, $match)) {
                                        $product->wholesale_price = (float) ElegantalEasyImportTools::getModifiedPriceByFormula($product->wholesale_price, $match[1], $this->decimal_char);
                                    }
                                }
                                if (preg_match("/^round_([\d]+)$/", $value_default, $match2)) {
                                    $product->wholesale_price = round($product->wholesale_price, (int) $match2[1]);
                                } elseif ($value_default == 'ceil') {
                                    $product->wholesale_price = ceil($product->wholesale_price);
                                } elseif ($value_default == 'floor') {
                                    $product->wholesale_price = floor($product->wholesale_price);
                                }
                                break;
                            case 'tax_rules_group':
                                // If there is no tax rule group, set tax rule group from default. This is not needed, as it is possible to set default value now
                                if (($index >= 0 && !$value) || ($index < 0 && !$value_default && $value_default !== '')) {
                                    // $product->id_tax_rules_group = (int) Product::getIdTaxRulesGroupMostUsed();
                                    $product->id_tax_rules_group = 0;
                                } elseif ($value) {
                                    $id_tax_rules_group = (int) Db::getInstance()->getValue('SELECT `id_tax_rules_group` FROM `' . _DB_PREFIX_ . "tax_rules_group` WHERE `name` = '" . pSQL($value) . "' AND `deleted` = 0");
                                    if (!$id_tax_rules_group && Validate::isInt($value)) {
                                        $taxRulesGroup = new TaxRulesGroup($value);
                                        if (Validate::isLoadedObject($taxRulesGroup) && !$taxRulesGroup->deleted) {
                                            $id_tax_rules_group = (int) $value;
                                        }
                                    }
                                    if ($id_tax_rules_group) {
                                        $product->id_tax_rules_group = $id_tax_rules_group;
                                    }
                                }
                                break;
                            case 'price_tax_excluded':
                            case 'price_tax_included':
                                if ($value === '') {
                                    break;
                                }
                                if (preg_match("/\[FORMULA:([\s\[\]\#\.\,\;\+\-\*\/\d]+)\]/", $value, $match)) {
                                    // No mapping selected for price_tax_excluded column and map_default_values contains formula.
                                    // So base price is 0 and calculation result will be final value.
                                    // map_default_values may contain other columns e.g. [%column%]
                                    $price_value = ElegantalEasyImportTools::getModifiedPriceByFormula(0, $match[1], $this->decimal_char);
                                    if ($price_value < $this->min_price_amount) {
                                        break;
                                    }
                                    $product->price = $price_value;
                                } else {
                                    $price_value = (float) $this->extractPriceInDefaultCurrency($value, $currency);
                                    if ($price_value < $this->min_price_amount) {
                                        break;
                                    }
                                    if ($value_default && preg_match("/\[FORMULA:([\s\[\]\#\.\,\;\+\-\*\/\d]+)\]/", $value_default, $match)) {
                                        $product->price = ElegantalEasyImportTools::getModifiedPriceByFormula($price_value, $match[1], $this->decimal_char);
                                    } else {
                                        $product->price = ElegantalEasyImportTools::getModifiedPriceByFormula($price_value, $this->price_modifier, $this->decimal_char);
                                    }
                                }
                                if (preg_match("/^round_([\d]+)$/", $value_default, $match)) {
                                    $product->price = round($product->price, (int) $match[1]);
                                } elseif ($value_default == 'ceil') {
                                    $product->price = ceil($product->price);
                                } elseif ($value_default == 'floor') {
                                    $product->price = floor($product->price);
                                }
                                if ($attr == 'price_tax_included' && $product->price > 0) {
                                    // Check if Tax Rule Group exists
                                    $taxRulesGroup = new TaxRulesGroup($product->id_tax_rules_group);
                                    if (!Validate::isLoadedObject($taxRulesGroup) || $taxRulesGroup->deleted) {
                                        $product->id_tax_rules_group = 0;
                                    }
                                    // If a tax is already included in price, withdraw it from price
                                    $tax_rate = $product->tax_rate;
                                    if ($product->id_tax_rules_group) {
                                        $address = Address::initialize();
                                        $tax_manager = TaxManagerFactory::getManager($address, $product->id_tax_rules_group);
                                        $tax_calculator = $tax_manager->getTaxCalculator();
                                        $tax_rate = $tax_calculator->getTotalRate();
                                    }
                                    if ($tax_rate) {
                                        $product->price = (float) number_format($product->price / (1 + $tax_rate / 100), 6, '.', '');
                                    }
                                }
                                break;
                            case 'unit_price':
                                $price_value = (float) $this->extractPriceInDefaultCurrency($value, $currency);
                                if ($price_value >= $this->min_price_amount) {
                                    $product->unit_price = $price_value;
                                }
                                break;
                            case 'unity':
                                $product->unity = $value;
                                break;
                            case 'ecotax':
                                $product->ecotax = Configuration::get('PS_USE_ECOTAX') ? (float) $this->extractPriceInDefaultCurrency($value) : 0;
                                break;
                            case 'advanced_stock_management':
                                $product->advanced_stock_management = $this->isCsvValueTrue($value) ? 1 : 0;
                                break;
                            case 'depends_on_stock':
                                $value = $this->isCsvValueTrue($value) ? 1 : 0;
                                if (!$product->advanced_stock_management) {
                                    $value = 0;
                                }
                                $product->depends_on_stock = $value;
                                break;
                            case 'quantity':
                                if ($value !== '') {
                                    if (preg_match("/\[FORMULA:([\s\[\]\#\.\,\;\+\-\*\/\d]+)\]/", $value, $match)) {
                                        // No mapping selected for quantity column and map_default_values contains formula.
                                        // So base quantity is 0 and calculation result will be final value.
                                        // map_default_values may contain other columns e.g. [%column%]
                                        $value = ElegantalEasyImportTools::getModifiedPriceByFormula(0, $match[1], $this->decimal_char);
                                    } else {
                                        $value = str_replace([' ', '>', '<', '='], '', $value);
                                        if (strpos($value, ',') !== false && strpos($value, '.') !== false) {
                                            $value = str_replace(',', '', $value);
                                        }
                                        if ($value_default && preg_match("/\[FORMULA:([\s\[\]\#\.\,\;\+\-\*\/\d]+)\]/", $value_default, $match)) {
                                            $value = ElegantalEasyImportTools::getModifiedPriceByFormula($value, $match[1], $this->decimal_char);
                                        }
                                    }
                                    $value = $settings['product_quantity_data_type'] == 'float' ? (float) $value : (int) $value;
                                    $value = $value < 0 ? 0 : $value;
                                    $product->quantity = $value;
                                }
                                break;
                            case 'minimal_quantity':
                                if ($value && $value >= 1) {
                                    $product->minimal_quantity = (int) $value;
                                } elseif (($index >= 0 && !$value) || ($index < 0 && !$value_default && $value_default !== '')) {
                                    $product->minimal_quantity = 1;
                                }
                                break;
                            case 'low_stock_level':
                                $product->low_stock_threshold = (int) $value;
                                break;
                            case 'email_alert_on_low_stock':
                                $product->low_stock_alert = $this->isCsvValueTrue($value) ? 1 : 0;
                                break;
                            case 'action_when_out_of_stock':
                                $value = (int) $value;
                                $product->out_of_stock = ($value === 1 || $value === 0) ? $value : 2;
                                break;
                            case preg_match("/^text_when_in_stock_([\d]+)$/", $attr, $match) ? true : false:
                                $product->available_now[$match[1]] = Tools::substr(preg_replace('/[<>;=#{}]*/', '', $value), 0, 255);
                                break;
                            case preg_match("/^text_when_backordering_([\d]+)$/", $attr, $match) ? true : false:
                                $product->available_later[$match[1]] = Tools::substr(preg_replace('/[<>;=#{}]*/', '', $value), 0, 255);
                                break;
                            case 'availability_date':
                                if ($value && strtotime($value)) {
                                    $product->available_date = date('Y-m-d', strtotime($value));
                                } else {
                                    $product->available_date = null;
                                }
                                break;
                            case 'delete_existing_categories':
                                if ($value && $this->isCsvValueTrue($value)) {
                                    $product->deleteCategories();
                                }
                                break;
                            case 'categories_' . $this->id_lang_default:
                            case preg_match("/^category_([\d]+)_" . $this->id_lang_default . '$/', $attr, $match) ? true : false:
                                // Build multilang categories array
                                $cat_lang_arr = [];
                                if (preg_match("/^([a-z_]+)_([\d]+)_?([\d]+)?$/", $attr, $matches)) {
                                    foreach ($this->id_all_langs as $id_lang) {
                                        $attr_tmp = $matches[1] . '_' . ((isset($matches[3]) && $matches[3]) ? $matches[2] . '_' . $id_lang : $id_lang);
                                        $val = '';
                                        if (isset($line[$map[$attr_tmp]]) && $line[$map[$attr_tmp]]) {
                                            $val = $this->getDictionaryValue($attr_tmp, $line[$map[$attr_tmp]], $line, $csv_header);
                                        }
                                        if (isset($map_default_values[$attr_tmp]) && $map_default_values[$attr_tmp]) {
                                            $val .= $val ? $multiple_value_separator : '';
                                            $val .= $this->getDictionaryValue($attr_tmp, $map_default_values[$attr_tmp], $line, $csv_header);
                                        }
                                        if ($val) {
                                            $categoryNames = explode($multiple_value_separator, $val);
                                            $categoryNames = array_filter(array_map('trim', $categoryNames), 'strlen'); // Remove empty items
                                            foreach ($categoryNames as $key1 => $categoryName) {
                                                if ($multiple_subcategory_separator) {
                                                    $categories_arr = explode($multiple_subcategory_separator, $categoryName);
                                                    $categories_arr = array_filter(array_map('trim', $categories_arr), 'strlen'); // Remove empty items
                                                    $count_categories_arr = count($categories_arr);
                                                    if ($count_categories_arr > 1) {
                                                        foreach ($categories_arr as $key2 => $category_name) {
                                                            $cat_lang_arr[$key1 . '_' . $key2][$id_lang] = $category_name;
                                                        }
                                                    } else {
                                                        $cat_lang_arr[$key1][$id_lang] = $categoryName;
                                                    }
                                                } else {
                                                    $cat_lang_arr[$key1][$id_lang] = $categoryName;
                                                }
                                            }
                                        }
                                    }
                                }

                                if ($value) {
                                    $categoryNames = explode($multiple_value_separator, $value);
                                    $categoryNames = array_filter(array_map('trim', $categoryNames), 'strlen'); // Remove empty items
                                    foreach ($categoryNames as $key1 => $categoryName) {
                                        // If multiple_subcategory_separator is set, it means each category is path of categories
                                        if ($multiple_subcategory_separator) {
                                            $categories_arr = explode($multiple_subcategory_separator, $categoryName);
                                            $categories_arr = array_filter(array_map('trim', $categories_arr), 'strlen'); // Remove empty items
                                            $count_categories_arr = count($categories_arr);
                                            if ($count_categories_arr > 1) {
                                                $id_parent_category2 = $rootCategory->id;
                                                $current_category_to_replace = '';
                                                foreach ($categories_arr as $key2 => $category_name) {
                                                    $is_category_replaced = false;
                                                    $current_category_to_replace .= $current_category_to_replace ? $multiple_subcategory_separator : '';
                                                    $current_category_to_replace .= $category_name;
                                                    if (isset($category_mapping['categories_map']) && $category_mapping['categories_map']) {
                                                        foreach ($category_mapping['categories_map'] as $categories_map) {
                                                            if ($categories_map['csv_category'] == $current_category_to_replace && $categories_map['shop_category_id']) {
                                                                $is_category_replaced = true;
                                                                $categoryId = $categories_map['shop_category_id'];
                                                                $id_parent_category2 = $categoryId;
                                                                if (!in_array($categoryId, $product_categories_ids)) {
                                                                    // Assign this cat if enabled by settings OR assign only the last cat
                                                                    if ($this->is_associate_all_subcategories || ($count_categories_arr == ($key2 + 1))) {
                                                                        $product_categories_ids[] = $categoryId;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                    if (!$is_category_replaced) {
                                                        $category_names_lang = isset($cat_lang_arr[$key1 . '_' . $key2]) ? $cat_lang_arr[$key1 . '_' . $key2] : $category_name;
                                                        $categoryId = $this->getCategoryIdByName($category_names_lang, $id_parent_category2);
                                                        if ($categoryId) {
                                                            $id_parent_category2 = $categoryId;
                                                            if (!in_array($categoryId, $product_categories_ids)) {
                                                                // Assign this cat if enabled by settings OR assign only the last cat
                                                                if ($this->is_associate_all_subcategories || ($count_categories_arr == ($key2 + 1))) {
                                                                    $product_categories_ids[] = $categoryId;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            } else {
                                                $is_category_replaced = false;
                                                if (isset($category_mapping['categories_map']) && $category_mapping['categories_map']) {
                                                    foreach ($category_mapping['categories_map'] as $categories_map) {
                                                        if ($categories_map['csv_category'] == $categoryName && $categories_map['shop_category_id']) {
                                                            $is_category_replaced = true;
                                                            $categoryId = $categories_map['shop_category_id'];
                                                            if (!in_array($categoryId, $product_categories_ids)) {
                                                                $product_categories_ids[] = $categoryId;
                                                            }
                                                        }
                                                    }
                                                }
                                                if (!$is_category_replaced) {
                                                    $category_names_lang = isset($cat_lang_arr[$key1]) ? $cat_lang_arr[$key1] : $categoryName;
                                                    $categoryId = $this->getCategoryIdByName($category_names_lang, $rootCategory->id);
                                                    if ($categoryId && !in_array($categoryId, $product_categories_ids)) {
                                                        $product_categories_ids[] = $categoryId;
                                                    }
                                                }
                                            }
                                        } else {
                                            $is_category_replaced = false;
                                            $current_category_to_replace .= $current_category_to_replace ? $multiple_value_separator : '';
                                            $current_category_to_replace .= $categoryName;
                                            if (isset($category_mapping['categories_map']) && $category_mapping['categories_map']) {
                                                foreach ($category_mapping['categories_map'] as $categories_map) {
                                                    if ($categories_map['csv_category'] == $current_category_to_replace && $categories_map['shop_category_id']) {
                                                        $is_category_replaced = true;
                                                        $categoryId = $categories_map['shop_category_id'];
                                                        if (!in_array($categoryId, $product_categories_ids)) {
                                                            $product_categories_ids[] = $categoryId;
                                                        }
                                                    }
                                                }
                                            }
                                            if (!$is_category_replaced) {
                                                $id_parent_category = end($product_categories_ids);
                                                if (!$id_parent_category) {
                                                    $id_parent_category = $this->is_first_parent_root_for_categories ? $rootCategory->id : null;
                                                }
                                                $category_names_lang = isset($cat_lang_arr[$key1]) ? $cat_lang_arr[$key1] : $categoryName;
                                                $categoryId = $this->getCategoryIdByName($category_names_lang, $id_parent_category);
                                                if ($categoryId && !in_array($categoryId, $product_categories_ids)) {
                                                    $product_categories_ids[] = $categoryId;
                                                }
                                            }
                                        }
                                    }
                                }
                                break;
                            case 'default_category':
                                if (empty($value)) {
                                    break;
                                }
                                // Check if default category is mapped
                                $id_category_default_from_mapping = null;
                                if (isset($category_mapping['categories_map']) && $category_mapping['categories_map']) {
                                    $current_category_to_replace = '';
                                    if ($multiple_subcategory_separator) {
                                        $value_arr = explode($multiple_value_separator, $value);
                                        $value_arr = array_filter(array_map('trim', $value_arr), 'strlen'); // Remove empty items
                                        $current_category_to_replace = implode($multiple_subcategory_separator, array_filter(array_map('trim', explode($multiple_subcategory_separator, end($value_arr))), 'strlen'));
                                    } else {
                                        $current_category_to_replace = $value;
                                    }
                                    foreach ($category_mapping['categories_map'] as $categories_map) {
                                        if ($categories_map['csv_category'] == $current_category_to_replace && $categories_map['shop_category_id']) {
                                            $id_category_default_from_mapping = $categories_map['shop_category_id'];
                                        }
                                    }
                                }
                                if ($id_category_default_from_mapping) {
                                    $product->id_category_default = $id_category_default_from_mapping;
                                    break;
                                }

                                // In case value is array, get the last element as default category
                                $value_arr = explode($multiple_value_separator, $value);
                                $value_arr = array_filter(array_map('trim', $value_arr), 'strlen'); // Remove empty items
                                if (is_array($value_arr) && count($value_arr) > 1) {
                                    $value = end($value_arr);
                                }

                                $value_arr2 = [];
                                if ($multiple_subcategory_separator) {
                                    // Here $value is already last item of $value_arr
                                    $value_arr2 = explode($multiple_subcategory_separator, $value);
                                    $value_arr2 = array_filter(array_map('trim', $value_arr2), 'strlen'); // Remove empty items
                                    $value = end($value_arr2);
                                }

                                // Need to find parent category of default category:
                                $id_parent_category = null;

                                // If default category column contains multiple categories:
                                if ((is_array($value_arr) && count($value_arr) > 1) || $multiple_subcategory_separator) {
                                    // There is more than 1 category in array, so the first one must be under Home
                                    if ($this->is_first_parent_root_for_categories) {
                                        $id_parent_category = $rootCategory->id;
                                    }
                                    // If multiple_subcategory_separator is set, it means each category is path of categories
                                    if ($multiple_subcategory_separator) {
                                        if (is_array($value_arr2) && count($value_arr2) > 1) {
                                            foreach ($value_arr2 as $key => $value_name) {
                                                if (!$value_name) {
                                                    continue;
                                                }
                                                if ($value_name == $value) {
                                                    break;
                                                }
                                                $categoryId = $this->getCategoryIdByName($value_name, $id_parent_category);
                                                if (!$categoryId) {
                                                    continue;
                                                }
                                                $id_parent_category = $categoryId;
                                            }
                                        }
                                    } else {
                                        foreach ($value_arr as $key => $categoryName) {
                                            if (!$categoryName) {
                                                continue;
                                            }
                                            if ($categoryName == $value) {
                                                break;
                                            }
                                            $categoryId = $this->getCategoryIdByName($categoryName, $id_parent_category);
                                            if (!$categoryId) {
                                                continue;
                                            }
                                            $id_parent_category = $categoryId;
                                        }
                                    }
                                } elseif (isset($line[$map['categories_' . $this->id_lang_default]]) && $line[$map['categories_' . $this->id_lang_default]]) {
                                    // If default category column contains only one category and there is 'categories':
                                    $categoryNames = explode($multiple_value_separator, $line[$map['categories_' . $this->id_lang_default]]);
                                    $categoryNames = array_filter(array_map('trim', $categoryNames), 'strlen'); // Remove empty items

                                    $default_category_exists_in_categories = false;

                                    // If there is only one category, parent id should be null. Otherwise first parent will be Home.
                                    $id_parent_category = null;
                                    if (count($categoryNames) > 1 && $this->is_first_parent_root_for_categories) {
                                        $id_parent_category = $rootCategory->id;
                                    }

                                    foreach ($categoryNames as $key => $categoryName) {
                                        if (!$categoryName) {
                                            continue;
                                        }
                                        if ($categoryName == $value) {
                                            $default_category_exists_in_categories = true;
                                            break;
                                        }

                                        // If multiple_subcategory_separator is set, it means each category is path of categories
                                        if ($multiple_subcategory_separator) {
                                            $categories_arr = explode($multiple_subcategory_separator, $categoryName);
                                            $categories_arr = array_filter(array_map('trim', $categories_arr), 'strlen'); // Remove empty items
                                            $id_parent_category = $rootCategory->id;
                                            if (count($categories_arr) > 1) {
                                                foreach ($categories_arr as $category_name) {
                                                    if (!$category_name) {
                                                        continue;
                                                    }
                                                    if ($category_name == $value) {
                                                        $default_category_exists_in_categories = true;
                                                        break 2;
                                                    }
                                                    $categoryId = $this->getCategoryIdByName($category_name, $id_parent_category);
                                                    if ($categoryId) {
                                                        $id_parent_category = $categoryId;
                                                    }
                                                }
                                            }
                                        } else {
                                            $categoryId = $this->getCategoryIdByName($categoryName, $id_parent_category);
                                            if (!$categoryId) {
                                                continue;
                                            }
                                            $id_parent_category = $categoryId;
                                        }
                                    }

                                    if (!$default_category_exists_in_categories) {
                                        $id_parent_category = $rootCategory->id;
                                    }
                                }

                                // If new product, it will be used later, that's why it is not inside if statement
                                $product->id_category_default = $this->getCategoryIdByName($value, $id_parent_category);
                                break;
                            case 'manufacturer':
                                if ($value) {
                                    $product->id_manufacturer = $this->getManufacturerIdByName($value);
                                } else {
                                    $product->id_manufacturer = null;
                                }
                                break;
                            case 'package_width':
                            case 'package_height':
                            case 'package_depth':
                                if ($value && preg_match('/^([0-9.]*)x([0-9.]*)x([0-9.]*)$/i', $value, $matches)) {
                                    $product->width = ElegantalEasyImportTools::getConvertedDimension($matches[1], $this->shipping_package_size_unit, $ps_dimension_unit);
                                    $product->height = ElegantalEasyImportTools::getConvertedDimension($matches[2], $this->shipping_package_size_unit, $ps_dimension_unit);
                                    $product->depth = ElegantalEasyImportTools::getConvertedDimension($matches[3], $this->shipping_package_size_unit, $ps_dimension_unit);
                                } else {
                                    $package_attr = str_replace('package_', '', $attr);
                                    $product->{$package_attr} = ElegantalEasyImportTools::getConvertedDimension($value, $this->shipping_package_size_unit, $ps_dimension_unit);
                                }
                                break;
                            case 'package_weight':
                                $value = str_replace(',', '.', $value);
                                $product->weight = ElegantalEasyImportTools::getConvertedWeight($value, $this->shipping_package_weight_unit, $ps_weight_unit);
                                break;
                            case 'additional_shipping_cost':
                                if (preg_match("/\[FORMULA:([\s\[\]\#\.\,\;\+\-\*\/\d]+)\]/", $value, $match)) {
                                    // No mapping selected for additional_shipping_cost column and map_default_values contains formula.
                                    // So base price is 0 and calculation result will be final value.
                                    // map_default_values may contain other columns e.g. [%column%]
                                    $product->additional_shipping_cost = ElegantalEasyImportTools::getModifiedPriceByFormula(0, $match[1], $this->decimal_char);
                                } else {
                                    $product->additional_shipping_cost = (float) $this->extractPriceInDefaultCurrency($value, $currency);
                                    if ($value_default && preg_match("/\[FORMULA:([\s\[\]\#\.\,\;\+\-\*\/\d]+)\]/", $value_default, $match)) {
                                        $product->additional_shipping_cost = ElegantalEasyImportTools::getModifiedPriceByFormula($product->additional_shipping_cost, $match[1], $this->decimal_char);
                                    }
                                }
                                break;
                            case 'delivery_time':
                                $value = (int) $value;
                                $product->additional_delivery_times = ($value === 0 || $value === 2) ? $value : 1;
                                break;
                            case preg_match("/^delivery_in_stock_([\d]+)$/", $attr, $match) ? true : false:
                                $product->delivery_in_stock[$match[1]] = Tools::substr($value, 0, 255);
                                break;
                            case preg_match("/^delivery_out_stock_([\d]+)$/", $attr, $match) ? true : false:
                                $product->delivery_out_stock[$match[1]] = Tools::substr($value, 0, 255);
                                break;
                            case 'available_for_order':
                                $product->available_for_order = $this->isCsvValueFalse($value) ? 0 : 1;
                                if ($product->available_for_order) {
                                    $product->show_price = 1;
                                }
                                break;
                            case 'show_price':
                                if (!$product->available_for_order) {
                                    $product->show_price = $this->isCsvValueFalse($value) ? 0 : 1;
                                }
                                break;
                            case 'on_sale':
                                $product->on_sale = $this->isCsvValueTrue($value) ? 1 : 0;
                                break;
                            case 'online_only':
                                $product->online_only = $this->isCsvValueTrue($value) ? 1 : 0;
                                break;
                            case 'condition':
                                $value = Tools::strtolower($value);
                                if ($value && in_array($value, ['new', 'used', 'refurbished'])) {
                                    $product->condition = $value;
                                } else {
                                    $product->condition = 'new';
                                }
                                break;
                            case 'display_condition':
                                $product->show_condition = $this->isCsvValueTrue($value) ? 1 : 0;
                                break;
                            case 'is_virtual_product':
                                $product->is_virtual = $this->isCsvValueTrue($value) ? 1 : 0;
                                break;
                            case 'customizable':
                                $product->customizable = $this->isCsvValueTrue($value) ? 1 : 0;
                                break;
                            case 'uploadable_files':
                                $product->uploadable_files = (int) $value;
                                break;
                            case 'text_fields':
                                $product->text_fields = (int) $value;
                                break;
                            case 'visibility':
                                $value = $value ? Tools::strtolower($value) : $value;
                                switch ($value) {
                                    case 'everywhere':
                                    case 'both':
                                        $product->visibility = 'both';
                                        break;
                                    case 'catalog only':
                                    case 'catalog':
                                        $product->visibility = 'catalog';
                                        break;
                                    case 'search only':
                                    case 'search':
                                        $product->visibility = 'search';
                                        break;
                                    case 'nowhere':
                                    case 'none':
                                        $product->visibility = 'none';
                                        break;
                                    default:
                                        $product->visibility = 'both';
                                        break;
                                }
                                break;
                            case 'product_type':
                                $value = in_array($value, ['standard', 'combinations', 'virtual', 'pack']) ? $value : 'standard';
                                $product->product_type = $value;
                                $product->cache_is_pack = ($product->product_type == 'pack') ? 1 : 0;
                                $product->is_virtual = ($product->product_type == 'virtual') ? 1 : 0;
                                break;
                            case 'date_created':
                                if (strtotime($value)) {
                                    $product->date_add = date('Y-m-d H:i:s', strtotime($value));
                                }
                                break;
                            case 'redirect_type_when_offline':
                                $value = Tools::strtolower($value);
                                $product->redirect_type = in_array($value, ['301-category', '302-category', '301-product', '302-product', '404']) ? $value : '404';
                                break;
                            case 'redirect_target_category_id':
                                $product->id_type_redirected = (int) $value;
                                break;
                            default:
                                break;
                        }
                    }

                    // Make update on all shops
                    if ($update_products_on_all_shops && Shop::getContext() != Shop::CONTEXT_ALL) {
                        Shop::setContext(Shop::CONTEXT_ALL);
                    }

                    if ($map['enabled'] < 0) {
                        if ($this->enable_if_have_stock && $product->quantity >= 1) {
                            $product->active = 1;
                        } elseif ($this->disable_if_no_stock && $product->quantity <= 0) {
                            $product->active = 0;
                        } elseif (!$product->id && $this->enable_new_products_by_default) {
                            $product->active = 1;
                        } elseif (!$product->id && !$this->enable_new_products_by_default) {
                            $product->active = 0;
                        }
                    }

                    if ($id_reference) {
                        switch ($this->find_products_by) {
                            case 'reference':
                                if (empty($product->reference)) {
                                    $product->reference = $id_reference;
                                }
                                break;
                            case 'ean':
                                if (empty($product->ean13) && Validate::isEan13($id_reference)) {
                                    $product->ean13 = $id_reference;
                                }
                                break;
                            case 'mpn':
                                if (empty($product->mpn)) {
                                    $product->mpn = $id_reference;
                                }
                                break;
                            case 'isbn':
                                if (empty($product->isbn)) {
                                    $product->isbn = $id_reference;
                                }
                                break;
                            default:
                                break;
                        }
                    }

                    foreach ($this->id_all_langs as $id_lang) {
                        if (empty($product->name[$id_lang])) {
                            $product->name[$id_lang] = $product->name[$this->id_lang_default];
                        }
                        if (empty($product->link_rewrite[$id_lang])) {
                            $product->link_rewrite[$id_lang] = Tools::link_rewrite(str_replace("\xc2\xa0", '-', Tools::substr($product->name[$id_lang], 0, 128)));
                        }
                        // This is needed for Greek language
                        $product->link_rewrite[$id_lang] = preg_replace('/σ-/', 'ς-', $product->link_rewrite[$id_lang]);
                        $product->link_rewrite[$id_lang] = preg_replace('/σ$/', 'ς', $product->link_rewrite[$id_lang]);
                    }

                    // If product has no default category, select it from the last category in categories tree
                    if (!$product->id_category_default && !empty($product_categories_ids) && ($map['default_category'] < 0 || !isset($line[$map['default_category']]) || !$line[$map['default_category']]) && !$map_default_values['default_category']) {
                        $product->id_category_default = end($product_categories_ids);
                    }

                    // If product has no default category, add it to Home category
                    if (!$product->id_category_default) {
                        $product->id_category_default = $rootCategory->id;
                    }

                    $product->customizable = ($product->uploadable_files > 0 || $product->text_fields > 0) ? 1 : $product->customizable;

                    if ($product->id) {
                        if (!$product->update()) {
                            throw new Exception(Db::getInstance()->getMsgError());
                        }

                        // number_of_products_updated can get higher than number_of_products_processed because several products may have the same reference
                        ++$this->currentHistory->number_of_products_updated;
                        $this->currentHistory->ids_of_products_updated .= ($this->currentHistory->ids_of_products_updated ? ',' : '') . $product->id;

                        if (_PS_VERSION_ < '1.7' && Shop::isFeatureActive()) {
                            // This is needed to update shop fields. This is not needed in PS 1.7. Probably a bug in PS 1.6.
                            $product->setFieldsToUpdate($product->getFieldsShop());
                            $product->update();
                        }
                        if ($settings['employee_id_for_events_log']) {
                            PrestaShopLogger::addLog('Product modification', 1, null, 'Product', $product->id, true, (int) $settings['employee_id_for_events_log']);
                        }
                    } else {
                        // Don't allow new product if price is less than MIN PRICE
                        if ($this->min_price_amount > 0 && $product->price < $this->min_price_amount) {
                            throw new Exception('Creating product not allowed because of Min Price Amount.');
                        }

                        if ($this->force_id_product && $this->find_products_by == 'id' && Validate::isInt($id_reference) && $id_reference > 0) {
                            $product->id = (int) $id_reference;
                            $product->force_id = true;
                        }

                        try {
                            if (!$product->add()) {
                                throw new Exception(Db::getInstance()->getMsgError());
                            }
                        } catch (Exception $ex) {
                            if ($product->id !== null) {
                                $product->delete();
                            }
                            throw new Exception($ex->getMessage());
                        }

                        ++$this->currentHistory->number_of_products_created;
                        if ($product->id) {
                            $this->currentHistory->ids_of_products_created .= ($this->currentHistory->ids_of_products_created ? ',' : '') . $product->id;
                        }

                        // If date_created is set in mapping, make update for it
                        $date_created = (isset($line[$map['date_created']]) && $line[$map['date_created']]) ? $line[$map['date_created']] : $map_default_values['date_created'];
                        if ($date_created && strtotime($date_created)) {
                            $product->date_add = date('Y-m-d H:i:s', strtotime($date_created));
                            $product->update();
                        }

                        // If Supplier selected for this rule and Supplier not mapped, but Supplier Reference/Price is mapped, copy reference/price from mapping.
                        if ($this->supplier_id && ($map['supplier'] < 0 || !isset($line[$map['supplier']]) || !$line[$map['supplier']]) && !$map_default_values['supplier']) {
                            $supplier_references = null;
                            $supplier_prices = null;
                            if (isset($line[$map['supplier_reference']]) && $line[$map['supplier_reference']]) {
                                $supplier_references = $this->getDictionaryValue('supplier_reference', $line[$map['supplier_reference']], $line, $csv_header);
                                if ($map_default_values['supplier_reference']) {
                                    $supplier_references .= $multiple_value_separator . $this->getDictionaryValue('supplier_reference', $map_default_values['supplier_reference'], $line, $csv_header);
                                }
                            } elseif ($map_default_values['supplier_reference']) {
                                $supplier_references = $this->getDictionaryValue('supplier_reference', $map_default_values['supplier_reference'], $line, $csv_header);
                            } elseif ($this->find_products_by == 'supplier_reference' && $id_reference) {
                                $supplier_references = $this->getDictionaryValue('supplier_reference', $id_reference, $line, $csv_header);
                            }

                            if (isset($line[$map['supplier_price']]) && $line[$map['supplier_price']]) {
                                $supplier_prices = $this->getDictionaryValue('supplier_price', $line[$map['supplier_price']], $line, $csv_header);
                                if ($map_default_values['supplier_price']) {
                                    $supplier_prices .= $multiple_value_separator . $this->getDictionaryValue('supplier_price', $map_default_values['supplier_price'], $line, $csv_header);
                                }
                            } elseif ($map_default_values['supplier_price']) {
                                $supplier_prices = $this->getDictionaryValue('supplier_price', $map_default_values['supplier_price'], $line, $csv_header);
                            }
                            $this->createProductSuppliers($product, $this->supplier_id, $supplier_references, $supplier_prices);
                        }
                        if (in_array($product->visibility, ['both', 'search']) && Configuration::get('PS_SEARCH_INDEXATION')) {
                            Search::indexation(false, $product->id);
                        }
                        if ($settings['employee_id_for_events_log']) {
                            PrestaShopLogger::addLog('Product addition', 1, null, 'Product', $product->id, true, (int) $settings['employee_id_for_events_log']);
                        }
                    }

                    // Continue processing the rest of columns in mapping that require $product->id
                    foreach ($map as $attr => $index) {
                        // Skip if neither mapped nor provided default value
                        if ($index < 0 && $map_default_values[$attr] === '') {
                            continue;
                        }

                        $value = isset($line[$index]) ? $line[$index] : '';
                        $value_default = isset($map_default_values[$attr]) ? $this->getDictionaryValue($attr, $map_default_values[$attr], $line, $csv_header) : '';
                        $value = ($value === '') ? trim($value_default) : trim($value);
                        $value = $this->getDictionaryValue($attr, $value, $line, $csv_header);

                        switch ($value_default) {
                            case 'strip_tags':
                                $value = strip_tags($value);
                                break;
                            case 'nl2br':
                                $value = nl2br($value);
                                break;
                            default:
                                break;
                        }

                        switch ($attr) {
                            case 'delete_existing_discount':
                                if ($value && $this->isCsvValueTrue($value)) {
                                    Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'specific_price` WHERE `id_specific_price_rule` = 0 AND `id_product` = ' . (int) $product->id . ' AND (`id_shop` = 0 OR `id_shop` IN (' . implode(', ', array_map('intval', $shop_ids)) . '))');
                                }
                                break;
                            case 'discount_amount':
                            case 'discount_percent':
                                if (empty($value)) {
                                    // Delete existing discounts if discount_amount or discount_percent has empty value.
                                    // If it is not expected behavior by all merchants, a new setting is needed to control this option.
                                    // I think this code can be removed because delete_existing_discount is enough. It will delete existing discounts and if there is discount_amount or discount_percent from the file, it will be created accordingly.
                                    Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'specific_price` WHERE `id_specific_price_rule` = 0 AND `id_product` = ' . (int) $product->id . ' AND (`id_shop` = 0 OR `id_shop` IN (' . implode(', ', array_map('intval', $shop_ids)) . '))');
                                    break;
                                }
                                $is_percentage = (strpos($value, '%') !== false || $attr == 'discount_percent') ? true : false;
                                $discount_from = '0000-00-00 00:00:00';
                                $discount_to = '0000-00-00 00:00:00';
                                $is_discount_tax_included = 1;
                                $discount_base_price = (isset($line[$map['discount_base_price']]) && $line[$map['discount_base_price']] !== '') ? $line[$map['discount_base_price']] : $map_default_values['discount_base_price'];
                                $discount_starting_unit = (isset($line[$map['discount_starting_unit']]) && $line[$map['discount_starting_unit']]) ? $line[$map['discount_starting_unit']] : $map_default_values['discount_starting_unit'];
                                $discount_customer_group = (isset($line[$map['discount_customer_group']]) && $line[$map['discount_customer_group']]) ? $line[$map['discount_customer_group']] : $map_default_values['discount_customer_group'];
                                $discount_customer_id = (isset($line[$map['discount_customer_id']]) && $line[$map['discount_customer_id']] !== '') ? $line[$map['discount_customer_id']] : $map_default_values['discount_customer_id'];
                                $discount_country = (isset($line[$map['discount_country']]) && $line[$map['discount_country']]) ? $line[$map['discount_country']] : $map_default_values['discount_country'];
                                $discount_currency = (isset($line[$map['discount_currency']]) && $line[$map['discount_currency']]) ? $line[$map['discount_currency']] : $map_default_values['discount_currency'];
                                if (isset($line[$map['discount_from']]) && $line[$map['discount_from']]) {
                                    $discount_from = date('Y-m-d H:i:s', strtotime($line[$map['discount_from']]));
                                } elseif ($map_default_values['discount_from']) {
                                    $discount_from = date('Y-m-d H:i:s', strtotime($map_default_values['discount_from']));
                                }
                                if (isset($line[$map['discount_to']]) && $line[$map['discount_to']]) {
                                    $discount_to = date('Y-m-d H:i:s', strtotime($line[$map['discount_to']]));
                                } elseif ($map_default_values['discount_to']) {
                                    $discount_to = date('Y-m-d H:i:s', strtotime($map_default_values['discount_to']));
                                }
                                if (isset($line[$map['discount_tax_included']]) && $line[$map['discount_tax_included']] !== '') {
                                    $is_discount_tax_included = $this->isCsvValueFalse($line[$map['discount_tax_included']]) ? 0 : 1;
                                } elseif ($map_default_values['discount_tax_included'] !== '') {
                                    $is_discount_tax_included = $this->isCsvValueFalse($map_default_values['discount_tax_included']) ? 0 : 1;
                                }
                                $value = $is_percentage ? $value : (float) $this->extractPriceInDefaultCurrency($value);
                                $this->createProductSpecificPrice($product->id, $value, $is_percentage, $is_discount_tax_included, $discount_from, $discount_to, $discount_base_price, $discount_starting_unit, $discount_customer_group, $discount_customer_id, $discount_country, $discount_currency, 0, $shop_ids);
                                break;
                            case 'discounted_price':
                                $discounted_price = (float) $this->extractPriceInDefaultCurrency($value, $currency);
                                if (!$discounted_price) {
                                    break;
                                }
                                $discounted_price = ElegantalEasyImportTools::getModifiedPriceByFormula($discounted_price, $this->price_modifier, $this->decimal_char);
                                $is_discount_tax_included = 1;
                                $discount_tax_included = (isset($line[$map['discount_tax_included']]) && $line[$map['discount_tax_included']] !== '') ? $line[$map['discount_tax_included']] : $map_default_values['discount_tax_included'];
                                if ($discount_tax_included !== '') {
                                    $is_discount_tax_included = $this->isCsvValueFalse($discount_tax_included) ? 0 : 1;
                                }
                                if ($is_discount_tax_included) {
                                    // Check if Tax Rule Group exists
                                    $taxRulesGroup = new TaxRulesGroup($product->id_tax_rules_group);
                                    if (!Validate::isLoadedObject($taxRulesGroup) || $taxRulesGroup->deleted) {
                                        $product->id_tax_rules_group = null;
                                    }
                                    // If a tax is already included in price, withdraw it from price
                                    $tax_rate = $product->tax_rate;
                                    if ($product->id_tax_rules_group) {
                                        $address = Address::initialize();
                                        $tax_manager = TaxManagerFactory::getManager($address, $product->id_tax_rules_group);
                                        $tax_calculator = $tax_manager->getTaxCalculator();
                                        $tax_rate = $tax_calculator->getTotalRate();
                                    }
                                    if ($tax_rate) {
                                        $discounted_price = (float) number_format($discounted_price / (1 + $tax_rate / 100), 6, '.', '');
                                    }
                                }

                                $discount_type_is_percent = (isset($line[$map['discount_type_is_percent']]) && $line[$map['discount_type_is_percent']] !== '') ? $line[$map['discount_type_is_percent']] : $map_default_values['discount_type_is_percent'];
                                $is_percentage = $this->isCsvValueTrue($discount_type_is_percent) ? 1 : 0;

                                $discount_base_price = (isset($line[$map['discount_base_price']]) && $line[$map['discount_base_price']] !== '') ? $line[$map['discount_base_price']] : $map_default_values['discount_base_price'];
                                $discount_starting_unit = (isset($line[$map['discount_starting_unit']]) && $line[$map['discount_starting_unit']]) ? $line[$map['discount_starting_unit']] : $map_default_values['discount_starting_unit'];
                                $discount_customer_group = (isset($line[$map['discount_customer_group']]) && $line[$map['discount_customer_group']]) ? $line[$map['discount_customer_group']] : $map_default_values['discount_customer_group'];
                                $discount_customer_id = (isset($line[$map['discount_customer_id']]) && $line[$map['discount_customer_id']] !== '') ? $line[$map['discount_customer_id']] : $map_default_values['discount_customer_id'];
                                $discount_country = (isset($line[$map['discount_country']]) && $line[$map['discount_country']]) ? $line[$map['discount_country']] : $map_default_values['discount_country'];
                                $discount_currency = (isset($line[$map['discount_currency']]) && $line[$map['discount_currency']]) ? $line[$map['discount_currency']] : $map_default_values['discount_currency'];
                                $discount_from = '0000-00-00 00:00:00';
                                $discount_to = '0000-00-00 00:00:00';
                                if (isset($line[$map['discount_from']]) && $line[$map['discount_from']]) {
                                    $discount_from = date('Y-m-d H:i:s', strtotime($line[$map['discount_from']]));
                                } elseif ($map_default_values['discount_from']) {
                                    $discount_from = date('Y-m-d H:i:s', strtotime($map_default_values['discount_from']));
                                }
                                if (isset($line[$map['discount_to']]) && $line[$map['discount_to']]) {
                                    $discount_to = date('Y-m-d H:i:s', strtotime($line[$map['discount_to']]));
                                } elseif ($map_default_values['discount_to']) {
                                    $discount_to = date('Y-m-d H:i:s', strtotime($map_default_values['discount_to']));
                                }

                                if ((isset($line[$map['price_tax_excluded']]) || isset($line[$map['price_tax_included']]) || $map_default_values['price_tax_excluded'] || $map_default_values['price_tax_included'])
                                    && (!isset($line[$map['discount_amount']]) && !isset($line[$map['discount_percent']]) && !$map_default_values['discount_amount'] && !$map_default_values['discount_percent'])
                                    && $product->price > $discounted_price
                                ) {
                                    if ($is_percentage) {
                                        // Discount percent
                                        $discount_amount = round(($product->price - $discounted_price) * 100 / $product->price, 6);
                                    } else {
                                        // Discount amount
                                        $discount_amount = round($product->price - $discounted_price, 6);
                                    }
                                    $this->createProductSpecificPrice($product->id, $discount_amount, $is_percentage, 0, $discount_from, $discount_to, $discount_base_price, $discount_starting_unit, $discount_customer_group, $discount_customer_id, $discount_country, $discount_currency, 0, $shop_ids);
                                } elseif ((!isset($line[$map['price_tax_excluded']]) && !isset($line[$map['price_tax_included']]) && !$map_default_values['price_tax_excluded'] && !$map_default_values['price_tax_included'])
                                    && (isset($line[$map['discount_amount']]) && $discounted_price > $line[$map['discount_amount']]) || ($map_default_values['discount_amount'] && $discounted_price > $map_default_values['discount_amount'])
                                ) {
                                    // Product price
                                    $discount_amount = isset($line[$map['discount_amount']]) ? $line[$map['discount_amount']] : $map_default_values['discount_amount'];
                                    $discount_amount = (float) $this->extractPriceInDefaultCurrency($discount_amount);
                                    $product->price = (float) number_format($discounted_price + $discount_amount, 6, '.', '');
                                    if (preg_match("/^round_([\d]+)$/", $value_default, $match)) {
                                        $product->price = round($product->price, (int) $match[1]);
                                    } elseif ($value_default == 'ceil') {
                                        $product->price = ceil($product->price);
                                    } elseif ($value_default == 'floor') {
                                        $product->price = floor($product->price);
                                    }
                                    $product->update();
                                } elseif ((!isset($line[$map['price_tax_excluded']]) && !isset($line[$map['price_tax_included']]) && !$map_default_values['price_tax_excluded'] && !$map_default_values['price_tax_included'])
                                    && (isset($line[$map['discount_percent']]) || $map_default_values['discount_percent'])
                                ) {
                                    // Product price
                                    $discount_percent = isset($line[$map['discount_percent']]) ? $line[$map['discount_percent']] : $map_default_values['discount_percent'];
                                    if (preg_match('/([0-9]+\.{0,1}[0-9]*)/', $discount_percent, $match)) {
                                        $discount_percent = $match[0];
                                    }
                                    if ($discount_percent > 0 && $discount_percent < 1) {
                                        $discount_percent *= 100;
                                    }
                                    $product->price = (float) number_format($discounted_price / (1 - $discount_percent / 100), 6, '.', '');
                                    if (preg_match("/^round_([\d]+)$/", $value_default, $match)) {
                                        $product->price = round($product->price, (int) $match[1]);
                                    } elseif ($value_default == 'ceil') {
                                        $product->price = ceil($product->price);
                                    } elseif ($value_default == 'floor') {
                                        $product->price = floor($product->price);
                                    }
                                    $product->update();
                                }
                                break;
                            case 'discount_base_price':
                                if ($value !== '' && !isset($line[$map['discount_amount']]) && $map_default_values['discount_amount'] === '' && !isset($line[$map['discount_percent']]) && $map_default_values['discount_percent'] === '') {
                                    $discount_from = '0000-00-00 00:00:00';
                                    $discount_to = '0000-00-00 00:00:00';
                                    $discount_base_price = (isset($line[$map['discount_base_price']]) && $line[$map['discount_base_price']] !== '') ? $line[$map['discount_base_price']] : $map_default_values['discount_base_price'];
                                    $discount_starting_unit = (isset($line[$map['discount_starting_unit']]) && $line[$map['discount_starting_unit']]) ? $line[$map['discount_starting_unit']] : $map_default_values['discount_starting_unit'];
                                    $discount_customer_group = (isset($line[$map['discount_customer_group']]) && $line[$map['discount_customer_group']]) ? $line[$map['discount_customer_group']] : $map_default_values['discount_customer_group'];
                                    $discount_customer_id = (isset($line[$map['discount_customer_id']]) && $line[$map['discount_customer_id']] !== '') ? $line[$map['discount_customer_id']] : $map_default_values['discount_customer_id'];
                                    $discount_country = (isset($line[$map['discount_country']]) && $line[$map['discount_country']]) ? $line[$map['discount_country']] : $map_default_values['discount_country'];
                                    $discount_currency = (isset($line[$map['discount_currency']]) && $line[$map['discount_currency']]) ? $line[$map['discount_currency']] : $map_default_values['discount_currency'];
                                    if (isset($line[$map['discount_from']]) && $line[$map['discount_from']]) {
                                        $discount_from = date('Y-m-d H:i:s', strtotime($line[$map['discount_from']]));
                                    } elseif ($map_default_values['discount_from']) {
                                        $discount_from = date('Y-m-d H:i:s', strtotime($map_default_values['discount_from']));
                                    }
                                    if (isset($line[$map['discount_to']]) && $line[$map['discount_to']]) {
                                        $discount_to = date('Y-m-d H:i:s', strtotime($line[$map['discount_to']]));
                                    } elseif ($map_default_values['discount_to']) {
                                        $discount_to = date('Y-m-d H:i:s', strtotime($map_default_values['discount_to']));
                                    }
                                    $this->createProductSpecificPrice($product->id, null, 0, 0, $discount_from, $discount_to, $discount_base_price, $discount_starting_unit, $discount_customer_group, $discount_customer_id, $discount_country, $discount_currency, 0, $shop_ids);
                                }
                                break;
                            case 'depends_on_stock':
                                StockAvailable::setProductDependsOnStock($product->id, $product->depends_on_stock);
                                break;
                            case 'warehouse_id':
                                if ($value && $product->advanced_stock_management) {
                                    if (Warehouse::exists($value)) {
                                        $product->warehouse = (int) $value;
                                        $query = new DbQuery();
                                        $query->select('id_warehouse_product_location');
                                        $query->from('warehouse_product_location');
                                        $query->where('id_product = ' . (int) $product->id . ' AND id_product_attribute = ' . (int) $id_product_attribute . ' AND id_warehouse = ' . (int) $product->warehouse);
                                        $warehouse_product_location = (int) Db::getInstance()->getValue($query);
                                        if ($warehouse_product_location) {
                                            $wpl = new WarehouseProductLocation($warehouse_product_location);
                                            $wpl->location = (isset($line[$map['location_in_warehouse']]) && $line[$map['location_in_warehouse']]) ? $line[$map['location_in_warehouse']] : $map_default_values['location_in_warehouse'];
                                            $wpl->update();
                                        } else {
                                            $wpl = new WarehouseProductLocation();
                                            $wpl->id_product = $product->id;
                                            $wpl->id_product_attribute = $id_product_attribute;
                                            $wpl->id_warehouse = (int) $product->warehouse;
                                            $wpl->location = (isset($line[$map['location_in_warehouse']]) && $line[$map['location_in_warehouse']]) ? $line[$map['location_in_warehouse']] : $map_default_values['location_in_warehouse'];
                                            $wpl->add();
                                        }
                                        StockAvailable::synchronize($product->id);
                                    } else {
                                        $this->addError('Warehouse does not exist with ID ' . $value . '.', $product);
                                    }
                                }
                                break;
                            case 'quantity':
                                if ($value !== '') {
                                    // Update quantity in Warehouse
                                    if ($product->advanced_stock_management) {
                                        if (empty($product->warehouse)) {
                                            $query = new DbQuery();
                                            $query->select('id_warehouse');
                                            $query->from('warehouse_product_location');
                                            $query->where('id_product = ' . (int) $product->id . ' AND id_product_attribute = ' . (int) $id_product_attribute);
                                            $product->warehouse = (int) Db::getInstance()->getValue($query);
                                        }
                                        if ($product->warehouse) {
                                            $stock_manager = StockManagerFactory::getManager();
                                            $price = str_replace(',', '.', $product->wholesale_price);
                                            if ($price == 0) {
                                                $price = $product->price; // 0 will not work because price_te is required and 0.000001 will not work because it will be converted to 1.0E-6
                                            }
                                            $price = round((float) $price, 6);
                                            $warehouse = new Warehouse($product->warehouse);
                                            if ($stock_manager->addProduct($product->id, $id_product_attribute, $warehouse, $product->quantity, 1, $price, true)) {
                                                StockAvailable::synchronize($product->id);
                                            }
                                        } else {
                                            $this->addError('Warehouse is missing.', $product);
                                        }
                                    }
                                    // Update quantity in Shop
                                    $tmp_context_shop = Shop::getContext();
                                    Shop::setContext(Shop::CONTEXT_SHOP, $id_shop);
                                    foreach ($shop_ids as $sh_id) {
                                        StockAvailable::setQuantity($product->id, $id_product_attribute, $product->quantity, $sh_id);
                                    }
                                    Shop::setContext($tmp_context_shop, $id_shop);
                                }
                                break;
                            case 'quantity_add_or_subtract':
                                if ($value !== '') {
                                    $quantity = (isset($product->quantity) && $product->quantity) ? $product->quantity + (int) $value : (int) $value;
                                    $product->quantity = $quantity < 0 ? 0 : $quantity;
                                    $tmp_context_shop = Shop::getContext();
                                    Shop::setContext(Shop::CONTEXT_SHOP, $id_shop);
                                    foreach ($shop_ids as $sh_id) {
                                        StockAvailable::setQuantity($product->id, $id_product_attribute, $product->quantity, $sh_id);
                                    }
                                    Shop::setContext($tmp_context_shop, $id_shop);
                                }
                                break;
                            case 'stock_location':
                                if (method_exists('StockAvailable', 'setLocation')) {
                                    $value = Tools::substr($value, 0, 64);
                                    call_user_func(['StockAvailable', 'setLocation'], $product->id, $value, $id_shop, $id_product_attribute);
                                }
                                break;
                            case 'action_when_out_of_stock':
                                $tmp_context_shop = Shop::getContext();
                                Shop::setContext(Shop::CONTEXT_SHOP, $id_shop);
                                foreach ($shop_ids as $sh_id) {
                                    StockAvailable::setProductOutOfStock($product->id, $product->out_of_stock, $sh_id, $id_product_attribute);
                                }
                                Shop::setContext($tmp_context_shop, $id_shop);
                                break;
                            case 'delete_existing_images':
                                if ($value && $this->isCsvValueTrue($value)) {
                                    $product->deleteImages();
                                    Db::getInstance()->execute('DELETE FROM ' . _DB_PREFIX_ . 'image_shop WHERE id_image NOT IN (SELECT id_image FROM ' . _DB_PREFIX_ . 'image)');
                                }
                                break;
                            case preg_match("/^captions_([\d]+)$/", $attr, $match) ? true : false:
                                $is_images_mapped = false;
                                if (
                                    $map['product_images'] >= 0 || $map_default_values['product_images']
                                    || $map['default_image'] >= 0 || $map_default_values['default_image']
                                    || $map['image_1'] >= 0 || $map_default_values['image_1']
                                    || $map['image_2'] >= 0 || $map_default_values['image_2']
                                    || $map['image_3'] >= 0 || $map_default_values['image_3']
                                ) {
                                    $is_images_mapped = true;
                                }
                                if ($value && !$is_images_mapped) {
                                    $captions = explode($multiple_value_separator, $value);
                                    $images = Image::getImages($id_lang, $product->id);
                                    foreach ($images as $image_pos => $image) {
                                        $imageObj = new Image($image['id_image']);
                                        if (Validate::isLoadedObject($imageObj)) {
                                            $caption = isset($captions[$image_pos]) ? $captions[$image_pos] : $captions[0];
                                            $caption = $this->getDictionaryValue('captions', $caption, $line, $csv_header);
                                            $caption = preg_replace('/[<>={}]*/', '', $caption);
                                            $imageObj->legend[$match[1]] = trim($caption);
                                            $imageObj->update();
                                        }
                                    }
                                }
                                break;
                            case 'product_images':
                            case 'default_image':
                            case preg_match("/^image_[\d]+$/", $attr) ? true : false:
                                if ($value_default == 'import_images_only_if_product_has_no_image') {
                                    if (Image::getImages($id_lang, $product->id)) {
                                        break;
                                    } else {
                                        $value_default = '';
                                    }
                                }
                                if ($value) {
                                    $captions = [];
                                    $default_image = '';
                                    $images = array_filter(array_map('trim', explode($multiple_value_separator, $value)), 'strlen'); // Remove empty images
                                    if ($images && is_array($images) && count($images) > 1) {
                                        if (isset($line[$index]) && $line[$index] && $value_default) {
                                            // Import selected images by position
                                            if (preg_match("/^[\d\,]+$/", $value_default) || $value_default == 'LAST') {
                                                if ($value_default == 'LAST') {
                                                    $images = [end($images)];
                                                } else {
                                                    // map_default_values contains positions of images to be imported. e.g. 1,2,3
                                                    $img_positions = explode(',', $value_default);
                                                    $img_positions = array_map('intval', $img_positions);
                                                    foreach ($images as $img_key => $img_value) {
                                                        if (!in_array($img_key + 1, $img_positions)) {
                                                            unset($images[$img_key]);
                                                        }
                                                    }
                                                }
                                            } else {
                                                $images[] = $value_default;
                                            }
                                        }
                                    }
                                    if ($images) {
                                        foreach ($this->id_all_langs as $id_lang) {
                                            if (isset($line[$map['captions_' . $id_lang]]) && $line[$map['captions_' . $id_lang]]) {
                                                $caption = $this->getDictionaryValue('captions', $line[$map['captions_' . $id_lang]], $line, $csv_header);
                                                $captions[$id_lang] = array_map('trim', explode($multiple_value_separator, $caption));
                                            } else {
                                                $caption = $this->getDictionaryValue('captions', $map_default_values['captions_' . $id_lang], $line, $csv_header);
                                                $captions[$id_lang] = array_map('trim', explode($multiple_value_separator, $caption));
                                            }
                                        }
                                        if ($attr == 'default_image') {
                                            $default_image = reset($images);
                                        }
                                        $convert_to = (isset($line[$map['convert_image_to']]) && $line[$map['convert_image_to']]) ? $line[$map['convert_image_to']] : $map_default_values['convert_image_to'];
                                        $this->createProductImages($product, $images, $default_image, $captions, $convert_to, $settings['is_compare_image_using_imagick']);
                                    }
                                }
                                break;
                            case 'delete_existing_features':
                                if ($value && $this->isCsvValueTrue($value)) {
                                    $product->deleteFeatures();
                                }
                                break;
                            case 'features_' . $this->id_lang_default:
                                if (isset($line[$index]) && $line[$index] && $value_default) {
                                    $value .= $value ? $multiple_value_separator : '';
                                    $value .= $value_default;
                                }
                                if ($value) {
                                    $features = [$this->id_lang_default => explode($multiple_value_separator, $value)];
                                    foreach ($this->id_other_langs as $id_lang) {
                                        if (isset($line[$map['features_' . $id_lang]]) && $line[$map['features_' . $id_lang]]) {
                                            $value = $line[$map['features_' . $id_lang]];
                                            if ($map_default_values['features_' . $id_lang]) {
                                                $value .= $value ? $multiple_value_separator : '';
                                                $value .= $map_default_values['features_' . $id_lang];
                                            }
                                            $features[$id_lang] = explode($multiple_value_separator, $value);
                                        } elseif ($map_default_values['features_' . $id_lang]) {
                                            $features[$id_lang] = explode($multiple_value_separator, $map_default_values['features_' . $id_lang]);
                                        }
                                    }
                                    $this->createProductFeatures($product->id, $features, $settings['is_allow_multiple_values_for_the_same_product_feature']);
                                }
                                break;
                            case preg_match("/^feature_([\d]+)_" . $this->id_lang_default . '$/', $attr, $match) ? true : false:
                                if ($value && $index >= 0 && isset($csv_header[$index]) && $csv_header[$index]) {
                                    $feature_values = explode($multiple_value_separator, $value);
                                    $value = '';
                                    foreach ($feature_values as $fv) {
                                        $value .= $value ? $multiple_value_separator : '';
                                        $value .= $csv_header[$index] . ':"' . htmlspecialchars($fv) . '"';
                                    }
                                    if ($value_default) {
                                        $value .= $value ? $multiple_value_separator : '';
                                        $value .= $value_default;
                                    }
                                }
                                if ($value) {
                                    $features = [$this->id_lang_default => explode($multiple_value_separator, $value)];
                                    foreach ($this->id_other_langs as $id_lang) {
                                        if (isset($line[$map['feature_' . $match[1] . '_' . $id_lang]]) && $line[$map['feature_' . $match[1] . '_' . $id_lang]]) {
                                            $value = $line[$map['feature_' . $match[1] . '_' . $id_lang]];
                                            $feature_values = explode($multiple_value_separator, $value);
                                            $value = '';
                                            foreach ($feature_values as $fv) {
                                                $value .= $value ? $multiple_value_separator : '';
                                                $value .= $csv_header[$map['feature_' . $match[1] . '_' . $id_lang]] . ':"' . htmlspecialchars($fv) . '"';
                                            }
                                            if ($map_default_values['feature_' . $match[1] . '_' . $id_lang]) {
                                                $value .= $value ? $multiple_value_separator : '';
                                                $value .= $map_default_values['feature_' . $match[1] . '_' . $id_lang];
                                            }
                                            $features[$id_lang] = explode($multiple_value_separator, $value);
                                        } elseif ($map_default_values['feature_' . $match[1] . '_' . $id_lang]) {
                                            $features[$id_lang] = explode($multiple_value_separator, $map_default_values['feature_' . $match[1] . '_' . $id_lang]);
                                        }
                                    }
                                    $this->createProductFeatures($product->id, $features, $settings['is_allow_multiple_values_for_the_same_product_feature']);
                                }
                                break;
                            case preg_match("/^tags_([\d]+)$/", $attr, $match) ? true : false:
                                if (isset($line[$index]) && $line[$index] && $value_default) {
                                    $value .= $value ? $multiple_value_separator : '';
                                    $value .= $value_default;
                                }
                                if ($value) {
                                    $this->createProductTags($product, $value, $match[1]);
                                }
                                break;
                            case 'delete_existing_accessories':
                                if ($value && $this->isCsvValueTrue($value)) {
                                    $product->deleteAccessories();
                                }
                                break;
                            case 'accessories':
                            case preg_match("/^accessory_[\d]+$/", $attr) ? true : false:
                                if (isset($line[$index]) && $line[$index] && $value_default) {
                                    $value .= $value ? $multiple_value_separator : '';
                                    $value .= $value_default;
                                }
                                if ($value) {
                                    $this->createProductAccessories($product, $value);
                                }
                                break;
                            case 'delete_existing_pack_items':
                                if ($value && $this->isCsvValueTrue($value) && Pack::isPack((int) $product->id)) {
                                    Pack::deleteItems($product->id);
                                    $product->setDefaultAttribute(0); // Reset cache_default_attribute
                                }
                                break;
                            case 'pack_items_ids':
                            case 'pack_items_refs':
                                if (isset($line[$index]) && $line[$index] && $value_default) {
                                    $value .= $value ? $multiple_value_separator : '';
                                    $value .= $value_default;
                                }
                                if ($value) {
                                    $this->createProductPackItems($product, $value, $attr);
                                }
                                break;
                            case 'delete_existing_attachments':
                                if ($value && $this->isCsvValueTrue($value)) {
                                    $attachments = Attachment::getAttachments($this->id_lang_default, $product->id);
                                    if ($attachments && is_array($attachments)) {
                                        foreach ($attachments as $attachment) {
                                            $is_attached_to_other_product = false;
                                            $sql = 'SELECT `id_product` FROM `' . _DB_PREFIX_ . 'product_attachment` WHERE `id_attachment` = ' . (int) $attachment['id_attachment'];
                                            $attachment_products = Db::getInstance()->executeS($sql);
                                            if ($attachment_products && is_array($attachment_products)) {
                                                foreach ($attachment_products as $attachment_product) {
                                                    if ($attachment_product['id_product'] != $product->id) {
                                                        $is_attached_to_other_product = true;
                                                        break;
                                                    }
                                                }
                                            }
                                            if (!$is_attached_to_other_product) {
                                                $attachmentObj = new Attachment((int) $attachment['id_attachment']);
                                                if (!Validate::isLoadedObject($attachmentObj) || !$attachmentObj->delete()) {
                                                    $this->addError('Failed to delete attachment ID: ' . $attachment['id_attachment'], $product);
                                                }
                                            }
                                        }
                                        Attachment::deleteProductAttachments($product->id);
                                    }
                                    // Delete files without lang name because it causes issue with importing the same attachment
                                    // $files_without_name = Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'attachment` WHERE `id_attachment` NOT IN (SELECT `id_attachment` FROM `' . _DB_PREFIX_ . 'attachment_lang`)');
                                    // if ($files_without_name) {
                                    //     foreach ($files_without_name as $file_without_name) {
                                    //         $attachmentToDelete = new Attachment($file_without_name['id_attachment']);
                                    //         if (Validate::isLoadedObject($attachmentToDelete)) {
                                    //             $attachmentToDelete->delete();
                                    //         }
                                    //     }
                                    // }
                                }
                                break;
                            case 'attachments':
                                if (isset($line[$index]) && $line[$index] && $value_default) {
                                    $value .= $value ? $multiple_value_separator : '';
                                    $value .= $value_default;
                                }
                                if ($value) {
                                    $attachment_names = [];
                                    $attachment_descriptions = [];
                                    foreach ($this->id_all_langs as $id_lang) {
                                        $attachment_names[$id_lang] = (isset($line[$map['attachment_names_' . $id_lang]]) && $line[$map['attachment_names_' . $id_lang]]) ? $line[$map['attachment_names_' . $id_lang]] : $map_default_values['attachment_names_' . $id_lang];
                                        $attachment_names[$id_lang] = array_map('trim', explode($multiple_value_separator, $this->getDictionaryValue('attachment_name', $attachment_names[$id_lang], $line, $csv_header)));
                                        $attachment_descriptions[$id_lang] = (isset($line[$map['attachment_descriptions_' . $id_lang]]) && $line[$map['attachment_descriptions_' . $id_lang]]) ? $line[$map['attachment_descriptions_' . $id_lang]] : $map_default_values['attachment_descriptions_' . $id_lang];
                                        $attachment_descriptions[$id_lang] = array_map('trim', explode($multiple_value_separator, $this->getDictionaryValue('attachment_description', $attachment_descriptions[$id_lang], $line, $csv_header)));
                                    }
                                    $this->createProductAttachments($product, $value, $attachment_names, $attachment_descriptions);
                                }
                                break;
                            case preg_match("/^attachment_([\d]+)$/", $attr, $match) ? true : false:
                                if ($value) {
                                    $attachment_number = (int) $match[1];
                                    $attachment_names = [];
                                    $attachment_descriptions = [];
                                    foreach ($this->id_all_langs as $id_lang) {
                                        $attachment_name_key = 'attachment_name_' . $attachment_number . '_' . $id_lang;
                                        $attachment_description_key = 'attachment_description_' . $attachment_number . '_' . $id_lang;
                                        $attachment_names[$id_lang] = (isset($line[$map[$attachment_name_key]]) && $line[$map[$attachment_name_key]]) ? $line[$map[$attachment_name_key]] : $map_default_values[$attachment_name_key];
                                        $attachment_names[$id_lang] = array_map('trim', explode($multiple_value_separator, $this->getDictionaryValue('attachment_name', $attachment_names[$id_lang], $line, $csv_header)));
                                        $attachment_descriptions[$id_lang] = (isset($line[$map[$attachment_description_key]]) && $line[$map[$attachment_description_key]]) ? $line[$map[$attachment_description_key]] : $map_default_values[$attachment_description_key];
                                        $attachment_descriptions[$id_lang] = array_map('trim', explode($multiple_value_separator, $this->getDictionaryValue('attachment_description', $attachment_descriptions[$id_lang], $line, $csv_header)));
                                    }
                                    $this->createProductAttachments($product, $value, $attachment_names, $attachment_descriptions);
                                }
                                break;
                            case 'carriers':
                                if (isset($line[$index]) && $line[$index] && $value_default) {
                                    $value .= $value ? $multiple_value_separator : '';
                                    $value .= $value_default;
                                }
                                if ($value !== '') {
                                    $this->createProductCarriers($product, $value);
                                }
                                break;
                            case 'carriers_id_reference':
                                if (isset($line[$index]) && $line[$index] && $value_default) {
                                    $value .= $value ? $multiple_value_separator : '';
                                    $value .= $value_default;
                                }
                                if ($value !== '') {
                                    $this->createProductCarriers($product, $value, true);
                                }
                                break;
                            case 'delete_existing_suppliers':
                                if ($value && $this->isCsvValueTrue($value)) {
                                    $product->deleteFromSupplier();
                                }
                                break;
                            case 'supplier':
                                if (isset($line[$index]) && $line[$index] && $value_default) {
                                    $value .= $value ? $multiple_value_separator : '';
                                    $value .= $value_default;
                                }
                                if ($value) {
                                    $supplier_references = null;
                                    $supplier_prices = null;
                                    if (isset($line[$map['supplier_reference']]) && $line[$map['supplier_reference']]) {
                                        $supplier_references = $this->getDictionaryValue('supplier_reference', $line[$map['supplier_reference']], $line, $csv_header);
                                        if ($map_default_values['supplier_reference']) {
                                            $supplier_references .= $multiple_value_separator . $this->getDictionaryValue('supplier_reference', $map_default_values['supplier_reference'], $line, $csv_header);
                                        }
                                    } elseif ($map_default_values['supplier_reference']) {
                                        $supplier_references = $this->getDictionaryValue('supplier_reference', $map_default_values['supplier_reference'], $line, $csv_header);
                                    }
                                    if (isset($line[$map['supplier_price']]) && $line[$map['supplier_price']]) {
                                        $supplier_prices = $this->getDictionaryValue('supplier_price', $line[$map['supplier_price']], $line, $csv_header);
                                        if ($map_default_values['supplier_price']) {
                                            $supplier_prices .= $multiple_value_separator . $this->getDictionaryValue('supplier_price', $map_default_values['supplier_price'], $line, $csv_header);
                                        }
                                    } elseif ($map_default_values['supplier_price']) {
                                        $supplier_prices = $this->getDictionaryValue('supplier_price', $map_default_values['supplier_price'], $line, $csv_header);
                                    }
                                    $this->createProductSuppliers($product, $value, $supplier_references, $supplier_prices);
                                }
                                break;
                            case 'delete_existing_customize_fields':
                                if ($value && $this->isCsvValueTrue($value)) {
                                    $sql = 'DELETE `' . _DB_PREFIX_ . 'customization_field`, `' . _DB_PREFIX_ . 'customization_field_lang`
                                        FROM `' . _DB_PREFIX_ . 'customization_field`
                                        INNER JOIN `' . _DB_PREFIX_ . 'customization_field_lang` ON `' . _DB_PREFIX_ . 'customization_field`.`id_customization_field` = `' . _DB_PREFIX_ . 'customization_field_lang`.`id_customization_field`
                                        WHERE `' . _DB_PREFIX_ . 'customization_field`.`id_product` = ' . (int) $product->id;
                                    if (Db::getInstance()->execute($sql)) {
                                        Configuration::updateGlobalValue('PS_CUSTOMIZATION_FEATURE_ACTIVE', Customization::isCurrentlyUsed());
                                    }
                                }
                                break;
                            case 'uploadable_files':
                                if ($value) {
                                    $uploadable_files_labels = (isset($line[$map['uploadable_files_labels']]) && $line[$map['uploadable_files_labels']]) ? $line[$map['uploadable_files_labels']] : $map_default_values['uploadable_files_labels'];
                                    $text_fields_labels = (isset($line[$map['text_fields_labels']]) && $line[$map['text_fields_labels']]) ? $line[$map['text_fields_labels']] : $map_default_values['text_fields_labels'];
                                    $this->createProductCustomizableFields($product, $uploadable_files_labels, $text_fields_labels);
                                }
                                break;
                            case 'text_fields':
                                $uploadable_files = (isset($line[$map['uploadable_files']]) && $line[$map['uploadable_files']]) ? $line[$map['uploadable_files']] : $map_default_values['uploadable_files'];
                                if ($value && !$uploadable_files) {
                                    $text_fields_labels = (isset($line[$map['text_fields_labels']]) && $line[$map['text_fields_labels']]) ? $line[$map['text_fields_labels']] : $map_default_values['text_fields_labels'];
                                    $this->createProductCustomizableFields($product, '', $text_fields_labels);
                                }
                                break;
                            case 'fsproductvideo_url':
                                if (isset($line[$index]) && $line[$index] && $value_default) {
                                    $value .= $value ? $multiple_value_separator : '';
                                    $value .= $value_default;
                                }
                                $this->createFsProductVideoUrls($product, $value);
                                break;
                            case 'additionalproductsorder_ids':
                                if (isset($line[$index]) && $line[$index] && $value_default) {
                                    $value .= $value ? $multiple_value_separator : '';
                                    $value .= $value_default;
                                }
                                $this->createAdditionalproductsorderRelation($product, $value);
                                break;
                            case 'jmarketplace_seller_id':
                                $this->createJmarketplaceSellerRelation($product->id, $value);
                                break;
                            case 'productaffiliate_external_shop_url':
                                if (isset($line[$map['productaffiliate_button_text']]) || isset($map_default_values['productaffiliate_button_text'])) {
                                    $productaffiliate_button_text = isset($line[$map['productaffiliate_button_text']]) ? $line[$map['productaffiliate_button_text']] : $map_default_values['productaffiliate_button_text'];
                                    $this->createProductaffiliateRelation($product->id, $this->id_lang_default, $value, $productaffiliate_button_text);
                                }
                                break;
                            case 'iqitadditionaltabs_description':
                            case preg_match("/^iqitadditionaltabs_description_([\d]+)$/", $attr, $match) ? true : false:
                                $iqit_count = isset($match[1]) ? (int) $match[1] : 1;
                                $iqit_suffix = isset($match[1]) ? '_' . $match[1] : '';
                                if (isset($line[$map['iqitadditionaltabs_title' . $iqit_suffix]]) || isset($map_default_values['iqitadditionaltabs_title' . $iqit_suffix])) {
                                    $iqitadditionaltabs_title = isset($line[$map['iqitadditionaltabs_title' . $iqit_suffix]]) ? $line[$map['iqitadditionaltabs_title' . $iqit_suffix]] : $map_default_values['iqitadditionaltabs_title' . $iqit_suffix];
                                    $this->createIqitadditionaltabsRelation($product->id, $iqitadditionaltabs_title, $value, $this->id_lang_default, $shop_ids, $iqit_count);
                                }
                                break;
                            case 'iqitextendedproduct_video_links':
                                if ($value) {
                                    $this->createIqitextendedproductRelation($product->id, $value);
                                }
                                break;
                            case 'ecm_cmlid_xml':
                                if ($value) {
                                    $this->createEcmCmlidRelation($product->id, $value);
                                }
                                break;
                            case preg_match("/^acf_([a-zA-Z0-9_]+)_([\d]+)$/", $attr, $match) ? true : false:
                                // id_lang is always added to the key in order to be able to extract id_lang from code
                                $this->createAdvancedcustomfieldsValue($product->id, $match[1], $value, $match[2]);
                                break;
                            case preg_match("/^totcustomfields_([a-zA-Z0-9_]+)_([\d]+)$/", $attr, $match) ? true : false:
                                // id_lang is always added to the key in order to be able to extract id_lang from code
                                $this->createTotcustomfieldsValue($product->id, $match[1], $value, $match[2]);
                                break;
                            case 'pproperties_quantity_step':
                                $this->createPpropertiesRelation($product->id, null, $value, false, $shop_ids);
                                break;
                            case 'pproperties_minimal_quantity':
                                $this->createPpropertiesRelation($product->id, null, false, $value, $shop_ids);
                                break;
                            case 'bms_advancedstock_warehouse':
                                $bms_advancedstock_physical_quantity = (isset($line[$map['bms_advancedstock_physical_quantity']]) && $line[$map['bms_advancedstock_physical_quantity']] !== '') ? $line[$map['bms_advancedstock_physical_quantity']] : $map_default_values['bms_advancedstock_physical_quantity'];
                                $bms_advancedstock_available_quantity = (isset($line[$map['bms_advancedstock_available_quantity']]) && $line[$map['bms_advancedstock_available_quantity']] !== '') ? $line[$map['bms_advancedstock_available_quantity']] : $map_default_values['bms_advancedstock_available_quantity'];
                                $bms_advancedstock_reserved_quantity = (isset($line[$map['bms_advancedstock_reserved_quantity']]) && $line[$map['bms_advancedstock_reserved_quantity']] !== '') ? $line[$map['bms_advancedstock_reserved_quantity']] : $map_default_values['bms_advancedstock_reserved_quantity'];
                                $bms_advanced_stock_shelf_location = (isset($line[$map['bms_advanced_stock_shelf_location']]) && $line[$map['bms_advanced_stock_shelf_location']]) ? $line[$map['bms_advanced_stock_shelf_location']] : $map_default_values['bms_advanced_stock_shelf_location'];
                                $this->createBmsAdvancedstockRelation($product->id, 0, $value, $bms_advancedstock_physical_quantity, $bms_advancedstock_available_quantity, $bms_advancedstock_reserved_quantity, $bms_advanced_stock_shelf_location, $shop_ids);
                                break;
                            case 'wk_measurement_allowed':
                                $wk_measurement_type = (isset($line[$map['wk_measurement_type']]) && $line[$map['wk_measurement_type']]) ? $line[$map['wk_measurement_type']] : $map_default_values['wk_measurement_type'];
                                $wk_measurement_value = (isset($line[$map['wk_measurement_value']]) && $line[$map['wk_measurement_value']]) ? $line[$map['wk_measurement_value']] : $map_default_values['wk_measurement_value'];
                                $wk_measurement_unit = (isset($line[$map['wk_measurement_unit']]) && $line[$map['wk_measurement_unit']] !== '') ? $line[$map['wk_measurement_unit']] : $map_default_values['wk_measurement_unit'];
                                $wk_measurement_units_for_customer = (isset($line[$map['wk_measurement_units_for_customer']]) && $line[$map['wk_measurement_units_for_customer']]) ? $line[$map['wk_measurement_units_for_customer']] : $map_default_values['wk_measurement_units_for_customer'];
                                $this->createWkgrocerymanagementRelation($product->id, $value, $wk_measurement_type, $wk_measurement_value, $wk_measurement_unit, $wk_measurement_units_for_customer, $shop_ids);
                                break;
                            case 'msrp_price_tax_excl':
                                $this->createMsrpRelation($product->id, 0, $product->id_tax_rules_group, $value, false, $shop_ids);
                                break;
                            case 'msrp_price_tax_incl':
                                $this->createMsrpRelation($product->id, 0, $product->id_tax_rules_group, $value, true, $shop_ids);
                                break;
                            case 'sld_accessories_type':
                                $this->createSldaccessoriestypeRelation($product->id, $value);
                                break;
                            case preg_match('/^aprealestate_([a-zA-Z0-9_]+)$/', $attr, $match) ? true : false:
                                $this->createAprealestateRelation($product->id, $value, $match[1]);
                                break;
                            case 'classy_productextratab_delete_existing':
                                if ($value && $this->isCsvValueTrue($value)) {
                                    $this->deleteClassyProductExtratabRelation($product->id);
                                }
                                break;
                            case preg_match("/^classy_productextratab_content_([\d]+)_([\d]+)$/", $attr, $match) ? true : false:
                                $classy_productextratab_title = isset($line[$map['classy_productextratab_title_' . $match[1] . '_' . $match[2]]]) ? $line[$map['classy_productextratab_title_' . $match[1] . '_' . $match[2]]] : $map_default_values['classy_productextratab_title_' . $match[1] . '_' . $match[2]];
                                $this->createClassyProductExtratabRelation($product->id, $classy_productextratab_title, $value, $match[2], $shop_ids);
                                break;
                            default:
                                break;
                        }
                    }

                    if ($product->advanced_stock_management == 0 && StockAvailable::dependsOnStock($product->id) == 1) {
                        StockAvailable::setProductDependsOnStock($product->id, 0);
                    }
                    if (!empty($product_categories_ids)) {
                        $product->addToCategories($product_categories_ids);
                    }
                    if ($product->id_category_default) {
                        $category_exists = Db::getInstance()->getRow('SELECT * FROM `' . _DB_PREFIX_ . 'category_product` WHERE `id_category` = ' . (int) $product->id_category_default . ' AND `id_product` = ' . (int) $product->id, false);
                        if (!$category_exists) {
                            $product->addToCategories($product->id_category_default);
                        }
                    }
                    // Disable product if it has no image
                    if ($this->disable_if_no_image) {
                        if (empty(Image::getImages($this->id_lang_default, $product->id))) {
                            $product->active = 0;
                            $product->update();
                        }
                    }

                    // Process 3rd party module integration
                    // Module: areapacks
                    if (isset($map['areapacks_label'], $map['areapacks_type'], $map['areapacks_area'])) {
                        $areapacks_label = (isset($line[$map['areapacks_label']]) && $line[$map['areapacks_label']]) ? $line[$map['areapacks_label']] : $map_default_values['areapacks_label'];
                        $areapacks_type = (isset($line[$map['areapacks_type']]) && $line[$map['areapacks_type']]) ? $line[$map['areapacks_type']] : $map_default_values['areapacks_type'];
                        $areapacks_area = (isset($line[$map['areapacks_area']]) && $line[$map['areapacks_area']] !== '') ? $line[$map['areapacks_area']] : $map_default_values['areapacks_area'];
                        if ($areapacks_label || $areapacks_type || $areapacks_area !== '') {
                            $this->createAreapacksRelation($product->id, 0, $areapacks_label, $areapacks_type, $areapacks_area);
                        }
                    }

                    // Set context shop back to its original value
                    Shop::setContext($context_shop, $id_shop_context);
                } catch (Exception $e) {
                    $this->addError('Exception: ' . $e->getMessage());
                    if ($settings['is_debug_mode']) {
                        $this->currentHistory->date_ended = date('Y-m-d H:i:s');
                        $this->currentHistory->update();
                        throw new Exception($e->getMessage());
                    }
                }
            }
            // We need this update because it may fail to update at the end, if timeout error happens.
            $this->currentHistory->date_ended = date('Y-m-d H:i:s');
            $this->currentHistory->update();
        }

        return true;
    }

    public function importCombinations($limit)
    {
        $map = $this->getMap();
        $map_default_values = $this->getMapDefaultValues();
        $file = ElegantalEasyImportTools::getRealPath($this->csv_file);
        $csv_header = ElegantalEasyImportCsv::getCsvHeaderRow($file, $this->header_row, $this->is_utf8_encode);
        $id_shop_context = $this->context->shop->id;
        $context_shop = Shop::getContext();
        $id_currency_default = (int) Configuration::get('PS_CURRENCY_DEFAULT');
        $settings = $this->getModelSettings();
        $update_products_on_all_shops = $this->update_products_on_all_shops && Shop::isFeatureActive();
        $multiple_value_separator = $this->multiple_value_separator;
        $skip_product_from_update_if_id_exists_in = array_filter(array_map('trim', explode(',', preg_replace('/[^0-9,]/', '', $settings['skip_product_from_update_if_id_exists_in']))), 'strlen'); // Remove empty items
        $skip_product_from_update_if_reference_has_sign = array_filter(array_map('trim', explode(',', $settings['skip_product_from_update_if_reference_has_sign'])), 'strlen');
        $skip_product_from_update_if_mpn_exists_in = array_filter(array_map('trim', explode(',', $settings['skip_product_from_update_if_mpn_exists_in'])), 'strlen');
        $attribute_class = (_PS_VERSION_ >= '8.0.0') ? 'ProductAttribute' : 'Attribute';

        $shop_ids = [];
        if ($update_products_on_all_shops) {
            $shop_groups = Shop::getTree();
            foreach ($shop_groups as $shop_group) {
                foreach ($shop_group['shops'] as $shop) {
                    $shop_ids[] = $shop['id_shop'];
                }
            }
        }
        if (empty($shop_ids)) {
            $shop_ids = [$id_shop_context];
        }

        $groups = [];
        $attributes_groups = AttributeGroup::getAttributesGroups($this->id_lang_default);
        foreach ($attributes_groups as $group) {
            $groups[Tools::strtolower($group['name'])] = (int) $group['id_attribute_group'];
        }

        $attributes = [];
        foreach ($attribute_class::getAttributes($this->id_lang_default) as $attribute) {
            $attributes[Tools::strtolower($attribute['attribute_group']) . '_' . Tools::strtolower($attribute['name'])] = (int) $attribute['id_attribute'];
        }

        $csvRows = ElegantalEasyImportData::model()->findAll([
            'condition' => [
                'id_elegantaleasyimport' => $this->id,
            ],
            'limit' => $limit,
        ]);

        foreach ($csvRows as $csvRow) {
            $csvRowModel = new ElegantalEasyImportData($csvRow['id_elegantaleasyimport_data']);
            if (!Validate::isLoadedObject($csvRowModel)) {
                continue;
            }

            $line = ElegantalEasyImportTools::unstorable($csvRowModel->csv_row);

            // We don't need this row in database anymore
            $csvRowModel->delete();

            ++$this->currentHistory->number_of_products_processed;

            // We need this update because the current row is already deleted and number_of_products_processed should be saved.
            // It may fail to save by the end of importing this row.
            $this->currentHistory->update();

            $id_reference = isset($line[$map['id_reference']]) ? $line[$map['id_reference']] : '';
            $this->current_id_reference = $id_reference;

            $id_reference_comb = isset($line[$map['id_reference_comb']]) ? $line[$map['id_reference_comb']] : '';

            // If shop is given in import file, use it for the context
            $shop_map = (isset($line[$map['shop']]) && $line[$map['shop']]) ? $line[$map['shop']] : $map_default_values['shop'];
            $id_shop_map = $this->getShopIdByName($shop_map);
            if ($id_shop_map) {
                $id_shop = $id_shop_map;
                $shop_ids = [$id_shop];
                Shop::setContext(Shop::CONTEXT_SHOP, $id_shop);
            } else {
                $id_shop = $id_shop_context;
            }

            $id_reference_column = $this->getReferenceColumn();
            $id_reference_comb_column = $this->getReferenceColumnForCombination();

            $products_rows = [];
            if ($id_reference) {
                $sql = 'SELECT DISTINCT p.`id_product` FROM `' . _DB_PREFIX_ . 'product` p ';
                if (!$update_products_on_all_shops) {
                    $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_shop` psh ON (psh.`id_product` = p.`id_product` AND psh.`id_shop` = ' . (int) $id_shop . ') ';
                }
                if ($this->find_products_by == 'combination_reference' || $this->find_products_by == 'combination_ean') {
                    $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_attribute` pa ON (pa.`id_product` = p.`id_product`) ';
                }
                if ($this->find_products_by == 'supplier_reference' || $this->supplier_id) {
                    $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_supplier` ps ON (ps.`id_product` = p.`id_product` AND ps.`id_product_attribute` = 0) ';
                }
                $sql .= 'WHERE (' . $id_reference_column . " = '" . pSQL($id_reference) . "' OR " . $id_reference_column . " = '" . pSQL('MERGED_' . $id_reference) . "') ";
                if ($this->supplier_id) {
                    $sql .= 'AND ps.`id_supplier` = ' . (int) $this->supplier_id . ' ';
                }
                $products_rows = Db::getInstance()->executeS($sql);
            } elseif ($id_reference_comb) { // Find product by Combination
                $sql = 'SELECT pa.`id_product`, pa.`id_product_attribute` FROM `' . _DB_PREFIX_ . 'product_attribute` pa ';
                if (!$update_products_on_all_shops) {
                    $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_attribute_shop` pash ON (pash.`id_product_attribute` = pa.`id_product_attribute` AND pash.`id_shop` = ' . (int) $id_shop . ') ';
                }
                if ($this->supplier_id) {
                    // It is necessary for the product to have supplier, combination may not have supplier, it is not required!
                    // No need for AND ps.`id_product_attribute` = pa.`id_product_attribute`
                    $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_supplier` ps ON (ps.`id_product` = pa.`id_product`) ';
                }
                $sql .= 'WHERE pa.`id_product` > 0 AND (' . $id_reference_comb_column . " = '" . pSQL($id_reference_comb) . "' OR " . $id_reference_comb_column . " = '" . pSQL('MERGED_' . $id_reference_comb) . "') ";
                if ($this->supplier_id) {
                    $sql .= 'AND ps.`id_supplier` = ' . (int) $this->supplier_id . ' ';
                }
                $sql .= 'GROUP BY pa.`id_product`, pa.`id_product_attribute`';
                $products_rows = Db::getInstance()->executeS($sql);
                if (!$products_rows && $this->find_combinations_by == 'combination_supplier_reference') {
                    $sql = 'SELECT pa.`id_product`, ps.`id_product_attribute` FROM `' . _DB_PREFIX_ . 'product_supplier` ps ';
                    if (!$update_products_on_all_shops) {
                        $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_attribute_shop` pash ON (pash.`id_product_attribute` = ps.`id_product_attribute` AND pash.`id_shop` = ' . (int) $id_shop . ') ';
                    }
                    $sql .= "WHERE ps.`id_product_attribute` > 0 AND (ps.`product_supplier_reference` = '" . pSQL($id_reference_comb) . "' OR ps.`product_supplier_reference` = '" . pSQL('MERGED_' . $id_reference_comb) . "') ";
                    if ($this->supplier_id) {
                        $sql .= 'AND ps.`id_supplier` = ' . (int) $this->supplier_id . ' ';
                    }
                    $sql .= 'GROUP BY pa.`id_product`, ps.`id_product_attribute`';
                    $products_rows = Db::getInstance()->executeS($sql);
                }
            }

            if (empty($products_rows)) {
                // If $id_reference is given but product was not found, it means we cannot continue.
                // $this->addError("Product not found.");
                continue;
            }

            foreach ($products_rows as $product_row) {
                try {
                    $product = null;
                    $id_product_attribute = 0;
                    if ($product_row && isset($product_row['id_product']) && $product_row['id_product'] > 0) {
                        $id_shop_for_product = $id_shop_context;
                        if ($update_products_on_all_shops) {
                            if (isset($product_row['id_shop_default']) && $product_row['id_shop_default'] != $id_shop_context) {
                                $id_shop_for_product = $product_row['id_shop_default'];
                            }
                        } elseif ($this->find_products_by != 'id') {
                            // Check if product exists in context shop
                            $sql = 'SELECT `id_product` FROM `' . _DB_PREFIX_ . 'product_shop` WHERE `id_product` = ' . (int) $product_row['id_product'] . ' AND `id_shop` = ' . (int) $id_shop_context;
                            $product_exists_in_current_shop = (int) Db::getInstance()->getValue($sql);
                            if (!$product_exists_in_current_shop) {
                                continue;
                            }
                        }
                        $product = new Product($product_row['id_product'], false, $this->id_lang_default, $id_shop_for_product);
                    }
                    if ($product_row && isset($product_row['id_product_attribute']) && $product_row['id_product_attribute'] > 0) {
                        $id_product_attribute = (int) $product_row['id_product_attribute'];
                    }

                    if (!Validate::isLoadedObject($product)) {
                        $this->addError('Product could not be loaded.');
                        continue;
                    }

                    if ($skip_product_from_update_if_id_exists_in && in_array($product->id, $skip_product_from_update_if_id_exists_in)) {
                        continue;
                    }
                    if ($skip_product_from_update_if_reference_has_sign && $product->reference && str_replace($skip_product_from_update_if_reference_has_sign, '', $product->reference) != $product->reference) {
                        continue;
                    }
                    if ($skip_product_from_update_if_mpn_exists_in && $product->mpn && in_array($product->mpn, $skip_product_from_update_if_mpn_exists_in)) {
                        continue;
                    }

                    $product->depends_on_stock = (int) StockAvailable::dependsOnStock($product->id);

                    // Prepare attributes and values per language
                    $csv_attribute_groups = [];
                    $csv_attribute_values = [];
                    $attribute_names = [];
                    $attribute_values = [];
                    foreach ($this->id_all_langs as $id_lang) {
                        if (isset($line[$map['attribute_names_' . $id_lang]]) && $line[$map['attribute_names_' . $id_lang]]) {
                            $attribute_names[$id_lang] = $line[$map['attribute_names_' . $id_lang]];
                        }
                        if ($map_default_values['attribute_names_' . $id_lang]) {
                            if (!isset($attribute_names[$id_lang])) {
                                $attribute_names[$id_lang] = '';
                            }
                            $attribute_names[$id_lang] .= (isset($attribute_names[$id_lang]) && $attribute_names[$id_lang]) ? $multiple_value_separator : '';
                            $attribute_names[$id_lang] .= $map_default_values['attribute_names_' . $id_lang];
                        }
                        if (isset($line[$map['attribute_values_' . $id_lang]]) && $line[$map['attribute_values_' . $id_lang]]) {
                            $attribute_values[$id_lang] = $line[$map['attribute_values_' . $id_lang]];
                        }
                        if ($map_default_values['attribute_values_' . $id_lang]) {
                            if (!isset($attribute_values[$id_lang])) {
                                $attribute_values[$id_lang] = '';
                            }
                            $attribute_values[$id_lang] .= (isset($attribute_values[$id_lang]) && $attribute_values[$id_lang]) ? $multiple_value_separator : '';
                            $attribute_values[$id_lang] .= $map_default_values['attribute_values_' . $id_lang];
                        }
                    }
                    foreach ($map as $attr => $index) {
                        // Skip if neither mapped nor provided default value
                        if ($index < 0 && $map_default_values[$attr] === '') {
                            continue;
                        }
                        switch ($attr) {
                            case preg_match("/^attribute_value_([\d]+)_([\d]+)$/", $attr, $match) ? true : false:
                                $id_lang = $match[2];
                                $attribute_value = ($index >= 0 && isset($line[$index]) && trim($line[$index]) !== '') ? $line[$index] : $map_default_values[$attr];
                                $attribute_value = trim($attribute_value);
                                $attribute_name = (isset($map['attribute_name_' . $match[1] . '_' . $match[2]]) && $map['attribute_name_' . $match[1] . '_' . $match[2]] >= 0 && isset($line[$map['attribute_name_' . $match[1] . '_' . $match[2]]]) && $line[$map['attribute_name_' . $match[1] . '_' . $match[2]]]) ? $line[$map['attribute_name_' . $match[1] . '_' . $match[2]]] : $map_default_values['attribute_name_' . $match[1] . '_' . $match[2]];
                                $attribute_name = trim($attribute_name);
                                if (!$attribute_name && isset($csv_header[$index]) && trim($csv_header[$index]) !== '') {
                                    $attribute_name = $csv_header[$index];
                                }
                                if ($attribute_name && $attribute_value !== '') {
                                    if (!isset($attribute_names[$id_lang])) {
                                        $attribute_names[$id_lang] = '';
                                    }
                                    if (!isset($attribute_values[$id_lang])) {
                                        $attribute_values[$id_lang] = '';
                                    }
                                    $attribute_names[$id_lang] .= (isset($attribute_names[$id_lang]) && $attribute_names[$id_lang]) ? $multiple_value_separator : '';
                                    if (strpos($attribute_name, ':select') !== false || strpos($attribute_name, ':color') !== false || strpos($attribute_name, ':radio') !== false) {
                                        // Do not replace : because it is used as separator
                                        $attribute_names[$id_lang] .= $attribute_name;
                                    } else {
                                        // Replace : because it is part of attribute name and may cause problems
                                        $attribute_names[$id_lang] .= str_replace(':', '∶', $attribute_name) . ':select';
                                    }
                                    $attribute_values[$id_lang] .= (isset($attribute_values[$id_lang]) && $attribute_values[$id_lang]) ? $multiple_value_separator : '';
                                    $attribute_values[$id_lang] .= str_replace(':', '∶', $attribute_value);
                                }
                                break;
                            default:
                                break;
                        }
                    }
                    if ($attribute_names) {
                        foreach ($attribute_names as $id_lang => $attr_names) {
                            $attribute_names[$id_lang] = explode($multiple_value_separator, $attr_names);
                            foreach ($attribute_names[$id_lang] as $key => $attr_group_line) {
                                $attr_group_parts = explode(':', $attr_group_line);
                                $attribute_names[$id_lang][$key] = ['name' => '', 'public_name' => '', 'type' => '', 'position' => ''];
                                if (is_array($attr_group_parts) && count($attr_group_parts) == 4) {
                                    $attribute_names[$id_lang][$key]['name'] = $this->getDictionaryValue('attribute_name', trim($attr_group_parts[0]), $line, $csv_header);
                                    $attribute_names[$id_lang][$key]['public_name'] = $this->getDictionaryValue('attribute_name', trim($attr_group_parts[1]), $line, $csv_header);
                                    $attribute_names[$id_lang][$key]['type'] = Tools::strtolower(trim($attr_group_parts[2]));
                                    $attribute_names[$id_lang][$key]['position'] = (int) $attr_group_parts[3];
                                } else {
                                    $attribute_names[$id_lang][$key]['name'] = $this->getDictionaryValue('attribute_name', trim($attr_group_parts[0]), $line, $csv_header);
                                    $attribute_names[$id_lang][$key]['public_name'] = $this->getDictionaryValue('attribute_name', $attribute_names[$id_lang][$key]['name'], $line, $csv_header);
                                    $attribute_names[$id_lang][$key]['type'] = isset($attr_group_parts[1]) ? Tools::strtolower(trim($attr_group_parts[1])) : 'select';
                                    $attribute_names[$id_lang][$key]['position'] = isset($attr_group_parts[2]) ? (int) $attr_group_parts[2] : false;
                                }
                            }
                        }
                        if (is_array($attribute_names[$this->id_lang_default]) && count($attribute_names[$this->id_lang_default]) > 0) {
                            foreach ($attribute_names[$this->id_lang_default] as $key => $attr_group_parts) {
                                $csv_attribute_groups[$key]['group'] = $attr_group_parts['name'];
                                if (isset($groups[Tools::strtolower($attr_group_parts['name'])])) {
                                    $csv_attribute_groups[$key]['id'] = $groups[Tools::strtolower($attr_group_parts['name'])];
                                    $attributeGroupTmp = new AttributeGroup($csv_attribute_groups[$key]['id']);
                                    $csv_attribute_groups[$key]['group_type'] = $attributeGroupTmp->group_type;
                                } else {
                                    $attributeGroup = new AttributeGroup();
                                    $attributeGroup->is_color_group = ($attr_group_parts['type'] == 'color') ? 1 : 0;
                                    $attributeGroup->group_type = in_array($attr_group_parts['type'], ['select', 'color', 'radio']) ? $attr_group_parts['type'] : 'select';
                                    $attributeGroup->name[$this->id_lang_default] = $attr_group_parts['name'];
                                    $attributeGroup->public_name[$this->id_lang_default] = $attr_group_parts['public_name'];
                                    foreach ($this->id_other_langs as $id_lang) {
                                        if (isset($attribute_names[$id_lang][$key]['name']) && $attribute_names[$id_lang][$key]['name']) {
                                            $attributeGroup->name[$id_lang] = $attribute_names[$id_lang][$key]['name'];
                                        } else {
                                            $attributeGroup->name[$id_lang] = $attributeGroup->name[$this->id_lang_default];
                                        }
                                        if (isset($attribute_names[$id_lang][$key]['public_name']) && $attribute_names[$id_lang][$key]['public_name']) {
                                            $attributeGroup->public_name[$id_lang] = $attribute_names[$id_lang][$key]['public_name'];
                                        } else {
                                            $attributeGroup->public_name[$id_lang] = $attributeGroup->public_name[$this->id_lang_default];
                                        }
                                    }
                                    $attributeGroup->position = (!$attr_group_parts['position']) ? AttributeGroup::getHigherPosition() + 1 : $attr_group_parts['position'];
                                    $attributeGroup->add();
                                    $attributeGroup->associateTo($shop_ids);
                                    $groups[Tools::strtolower($attr_group_parts['name'])] = $attributeGroup->id;
                                    $csv_attribute_groups[$key]['id'] = $attributeGroup->id;
                                    $csv_attribute_groups[$key]['group_type'] = $attributeGroup->group_type;
                                    AttributeGroup::cleanPositions();
                                }
                            }
                        }
                    }
                    if ($attribute_values) {
                        foreach ($attribute_values as $id_lang => $attr_values) {
                            $attribute_values[$id_lang] = explode($multiple_value_separator, $attr_values);
                            foreach ($attribute_values[$id_lang] as $key => $attr_value_line) {
                                $attr_value_parts = explode(':', $attr_value_line);
                                $attribute_values[$id_lang][$key] = [
                                    'value' => $this->getDictionaryValue('attribute_value', str_replace('\n', '', str_replace('\r', '', trim($attr_value_parts[0]))), $line, $csv_header),
                                    'position' => (isset($attr_value_parts[1]) ? (int) $attr_value_parts[1] : false),
                                ];
                            }
                        }
                        if (is_array($attribute_values[$this->id_lang_default]) && count($attribute_values[$this->id_lang_default]) > 0) {
                            foreach ($attribute_values[$this->id_lang_default] as $key => $attr_value_parts) {
                                if (!isset($csv_attribute_groups[$key])) {
                                    continue;
                                }
                                $attribute_group = Tools::strtolower($csv_attribute_groups[$key]['group']);
                                $attr_value = $attr_value_parts['value'];
                                $attr_position = $attr_value_parts['position'];
                                if (empty($attr_value)) {
                                    continue;
                                }
                                if (isset($attributes[$attribute_group . '_' . Tools::strtolower($attr_value)])) {
                                    $csv_attribute_values[$key] = $attributes[$attribute_group . '_' . Tools::strtolower($attr_value)];
                                } else {
                                    $attributeObj = new $attribute_class();
                                    $attributeObj->id_attribute_group = (int) $csv_attribute_groups[$key]['id'];
                                    $attributeObj->name[$this->id_lang_default] = $attr_value;
                                    foreach ($this->id_other_langs as $id_lang) {
                                        if (isset($attribute_values[$id_lang][$key]['value']) && $attribute_values[$id_lang][$key]['value']) {
                                            $attributeObj->name[$id_lang] = $attribute_values[$id_lang][$key]['value'];
                                        } else {
                                            $attributeObj->name[$id_lang] = $attributeObj->name[$this->id_lang_default];
                                        }
                                    }
                                    $attributeObj->position = (!$attr_position && isset($groups[$attribute_group])) ? $attribute_class::getHigherPosition($groups[$attribute_group]) + 1 : $attr_position;
                                    if ($csv_attribute_groups[$key]['group_type'] == 'color') {
                                        $attributeObj->color = (isset($line[$map['color_hex_value']]) && $line[$map['color_hex_value']]) ? $this->getDictionaryValue('color_hex_value', $line[$map['color_hex_value']], $line, $csv_header) : $map_default_values['color_hex_value'];
                                        if (Tools::strlen($attributeObj->color) == 6 && Tools::substr($attributeObj->color, 0, 1) != '#') {
                                            $attributeObj->color = '#' . $attributeObj->color;
                                        }
                                    }
                                    $attributeObj->add();
                                    $attributeObj->associateTo($shop_ids);
                                    $attributes[$attribute_group . '_' . Tools::strtolower($attr_value)] = $attributeObj->id;
                                    $csv_attribute_values[$key] = $attributeObj->id;
                                    // After insertion, we clean attribute position and group attribute position
                                    $attributeObj->cleanPositions((int) $attributeObj->id_attribute_group, false);
                                    AttributeGroup::cleanPositions();
                                }
                                // If there is texture for color attribute, move the image to img/co folder
                                $texture = (isset($line[$map['color_texture']]) && $line[$map['color_texture']]) ? $line[$map['color_texture']] : $map_default_values['color_texture'];
                                if ($csv_attribute_groups[$key]['group_type'] == 'color' && $csv_attribute_values[$key] && $texture) {
                                    try {
                                        if ($this->base_url_images && !ElegantalEasyImportTools::isValidUrl($texture)) {
                                            if (strpos($this->base_url_images, '%s') !== false) {
                                                $texture = str_replace('%s', $texture, $this->base_url_images);
                                            } elseif (!ElegantalEasyImportTools::isValidUrl($texture)) {
                                                $texture = $this->base_url_images . $texture;
                                            }
                                        }
                                        ElegantalEasyImportTools::downloadFileFromUrl($texture, _PS_COL_IMG_DIR_ . $csv_attribute_values[$key] . '.jpg', null, null, 'GET', '', 30);
                                    } catch (Exception $e) {
                                        $this->addError('Failed to download texture: ' . $texture . ' ' . $e->getMessage(), $product);
                                    }
                                }
                            }
                        }
                    }

                    $combination_data = [
                        'combination_id' => '',
                        'combination_reference' => '',
                        'combination_ean' => '',
                        'quantity' => null,
                        'minimal_quantity' => 1,
                        'stock_location' => '',
                        'low_stock_threshold' => null,
                        'low_stock_alert' => 0,
                        'wholesale_price' => 0,
                        'combination_price' => null,
                        'combination_price_tax_incl' => null,
                        'impact_on_price' => 0,
                        'impact_on_weight' => 0,
                        'impact_on_unit_price' => 0,
                        'images' => null,
                        'supplier_reference' => '',
                        'supplier_price' => 0,
                        'upc' => '',
                        'isbn' => '',
                        'combination_mpn' => '',
                        'ecotax' => 0,
                        'default' => null,
                        'available_date' => null,
                    ];

                    if (!$id_product_attribute && $id_reference_comb) {
                        $sql = 'SELECT DISTINCT pa.`id_product_attribute` FROM `' . _DB_PREFIX_ . 'product_attribute` pa ';
                        if (!$update_products_on_all_shops) {
                            $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_attribute_shop` pash ON (pash.`id_product_attribute` = pa.`id_product_attribute` AND pash.`id_shop` = ' . (int) $id_shop . ') ';
                        }
                        if ($this->supplier_id) {
                            // It is necessary for the product to have supplier, combination may not have supplier, it is not required!
                            // No need for AND ps.`id_product_attribute` = pa.`id_product_attribute`
                            $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_supplier` ps ON (ps.`id_product` = pa.`id_product`) ';
                        }
                        $sql .= 'WHERE pa.`id_product` = ' . (int) $product->id . ' AND ' . $id_reference_comb_column . " = '" . pSQL($id_reference_comb) . "' ";
                        if ($this->supplier_id) {
                            $sql .= 'AND ps.`id_supplier` = ' . (int) $this->supplier_id . ' ';
                        }
                        $id_product_attribute = (int) Db::getInstance()->getValue($sql);
                        if (!$id_product_attribute && $this->find_combinations_by == 'combination_supplier_reference') {
                            $sql = 'SELECT DISTINCT ps.`id_product_attribute` FROM `' . _DB_PREFIX_ . 'product_supplier` ps ';
                            if (!$update_products_on_all_shops) {
                                $sql .= 'INNER JOIN `' . _DB_PREFIX_ . 'product_attribute_shop` pash ON (pash.`id_product_attribute` = ps.`id_product_attribute` AND pash.`id_shop` = ' . (int) $id_shop . ') ';
                            }
                            $sql .= 'WHERE ps.`id_product_attribute` > 0 AND ps.`id_product` = ' . (int) $product->id . " AND ps.`product_supplier_reference` = '" . pSQL($id_reference_comb) . "' ";
                            if ($this->supplier_id) {
                                $sql .= 'AND ps.`id_supplier` = ' . (int) $this->supplier_id . ' ';
                            }
                            $id_product_attribute = (int) Db::getInstance()->getValue($sql);
                        }
                    }

                    // If combination does not exist, check if combination with the same attributes exists
                    if (!$id_product_attribute && count($csv_attribute_values) > 0) {
                        $id_product_attribute = (int) $product->productAttributeExists($csv_attribute_values, false, null, true, true);
                    }

                    // Check if creating or updating combination allowed
                    if (($id_product_attribute && !$this->update_existing_products) || (!$id_product_attribute && !$this->create_new_products)) {
                        continue;
                    }

                    if ($id_product_attribute) {
                        $existingCombination = new Combination($id_product_attribute);

                        // Skip this combination if the combination reference contains specified symbol
                        if ($skip_product_from_update_if_reference_has_sign && $existingCombination->reference && str_replace($skip_product_from_update_if_reference_has_sign, '', $existingCombination->reference) != $existingCombination->reference) {
                            continue;
                        }
                        // Skip this combination if the combination MPN exists in this array
                        if ($skip_product_from_update_if_mpn_exists_in && $existingCombination->mpn && in_array($existingCombination->mpn, $skip_product_from_update_if_mpn_exists_in)) {
                            continue;
                        }

                        $combination_data = [
                            'combination_reference' => $existingCombination->reference,
                            'combination_ean' => $existingCombination->ean13,
                            'quantity' => StockAvailable::getQuantityAvailableByProduct($product->id, $id_product_attribute),
                            'minimal_quantity' => $existingCombination->minimal_quantity,
                            'stock_location' => '',
                            'low_stock_threshold' => $existingCombination->low_stock_threshold,
                            'low_stock_alert' => $existingCombination->low_stock_alert,
                            'wholesale_price' => $existingCombination->wholesale_price,
                            'combination_price' => null,
                            'combination_price_tax_incl' => null,
                            'impact_on_price' => $existingCombination->price,
                            'impact_on_weight' => $existingCombination->weight,
                            'impact_on_unit_price' => $existingCombination->unit_price_impact,
                            'images' => null,
                            'supplier_reference' => $existingCombination->supplier_reference,
                            'supplier_price' => null,
                            'upc' => $existingCombination->upc,
                            'isbn' => $existingCombination->isbn,
                            'combination_mpn' => (property_exists($existingCombination, 'mpn')) ? $existingCombination->mpn : '',
                            'ecotax' => $existingCombination->ecotax,
                            'default' => $existingCombination->default_on,
                            'available_date' => $existingCombination->available_date,
                        ];
                    }

                    $currency = (isset($line[$map['price_currency']]) && $line[$map['price_currency']]) ? $line[$map['price_currency']] : $map_default_values['price_currency'];
                    if ($currency) {
                        $currency = $this->getDictionaryValue('price_currency', $currency, $line, $csv_header);
                    }

                    foreach ($map as $attr => $index) {
                        // Skip if neither mapped nor provided default value
                        if ($index < 0 && $map_default_values[$attr] === '') {
                            continue;
                        }

                        $value = isset($line[$index]) ? $line[$index] : '';
                        $value_default = isset($map_default_values[$attr]) ? $this->getDictionaryValue($attr, $map_default_values[$attr], $line, $csv_header) : '';
                        $value = ($value === '') ? trim($value_default) : trim($value);
                        $value = $this->getDictionaryValue($attr, $value, $line, $csv_header);

                        switch ($value_default) {
                            case 'strip_tags':
                                $value = strip_tags($value);
                                break;
                            case 'nl2br':
                                $value = nl2br($value);
                                break;
                            default:
                                break;
                        }

                        switch ($attr) {
                            case 'combination_reference':
                                $combination_data['combination_reference'] = $value;
                                break;
                            case 'combination_price':
                            case 'combination_price_tax_incl':
                                if ($value === '') {
                                    break;
                                }
                                if (preg_match("/\[FORMULA:([\s\[\]\#\.\,\;\+\-\*\/\d]+)\]/", $value, $match)) {
                                    // No mapping selected for price_tax_excluded column and map_default_values contains formula.
                                    // So base price is 0 and calculation result will be final value.
                                    // map_default_values may contain other columns e.g. [%column%]
                                    $value = (float) ElegantalEasyImportTools::getModifiedPriceByFormula(0, $match[1], $this->decimal_char);
                                    if ($value < $this->min_price_amount) {
                                        break;
                                    }
                                } else {
                                    $value = (float) $this->extractPriceInDefaultCurrency($value, $currency);
                                    if ($value < $this->min_price_amount) {
                                        break;
                                    }
                                    if ($value_default && preg_match("/\[FORMULA:([\s\[\]\#\.\,\;\+\-\*\/\d]+)\]/", $value_default, $match)) {
                                        $value = (float) ElegantalEasyImportTools::getModifiedPriceByFormula($value, $match[1], $this->decimal_char);
                                    } else {
                                        $value = (float) ElegantalEasyImportTools::getModifiedPriceByFormula($value, $this->price_modifier, $this->decimal_char);
                                    }
                                }
                                if (preg_match("/^round_([\d]+)$/", $value_default, $match)) {
                                    $value = round($value, (int) $match[1]);
                                } elseif ($value_default == 'ceil') {
                                    $value = ceil($value);
                                } elseif ($value_default == 'floor') {
                                    $value = floor($value);
                                }
                                if ($attr == 'combination_price_tax_incl') {
                                    $tax_rate = $product->getTaxesRate(new Address());
                                    $product_price_tax_incl = (float) number_format($product->price * (1 + $tax_rate / 100), 6, '.', '');
                                    $combination_data['impact_on_price'] = (float) number_format(($value - $product_price_tax_incl) / (1 + $tax_rate / 100), 4, '.', ''); // Decimals 4 is because of Prestashop bug. It should be 6 after bug is fixed.
                                } else {
                                    $combination_data['impact_on_price'] = round($value - $product->price, 6);
                                }
                                break;
                            case 'wholesale_price':
                                if (preg_match("/\[FORMULA:([\s\[\]\#\.\,\;\+\-\*\/\d]+)\]/", $value, $match)) {
                                    // No mapping selected for wholesale_price column and map_default_values contains formula.
                                    // So base price is 0 and calculation result will be final value.
                                    // map_default_values may contain other columns e.g. [%column%]
                                    $combination_data['wholesale_price'] = ElegantalEasyImportTools::getModifiedPriceByFormula(0, $match[1], $this->decimal_char);
                                } else {
                                    $combination_data['wholesale_price'] = (float) $this->extractPriceInDefaultCurrency($value, $currency);
                                    if ($value_default && preg_match("/\[FORMULA:([\s\[\]\#\.\,\;\+\-\*\/\d]+)\]/", $value_default, $match)) {
                                        $combination_data['wholesale_price'] = ElegantalEasyImportTools::getModifiedPriceByFormula($combination_data['wholesale_price'], $match[1], $this->decimal_char);
                                    }
                                }
                                break;
                            case 'impact_on_price':
                                $combination_data['impact_on_price'] = ElegantalEasyImportTools::getModifiedPriceByFormula((float) $this->extractPriceInDefaultCurrency($value, $currency), $this->price_modifier, $this->decimal_char);
                                break;
                            case 'impact_on_weight':
                                $combination_data['impact_on_weight'] = (float) str_replace(',', '.', $value);
                                break;
                            case 'impact_on_unit_price':
                                $combination_data['impact_on_unit_price'] = (float) $this->extractPriceInDefaultCurrency($value, $currency);
                                break;
                            case 'advanced_stock_management':
                                $product->advanced_stock_management = $this->isCsvValueTrue($value) ? 1 : 0;
                                $product->update();
                                break;
                            case 'depends_on_stock':
                                $value = $this->isCsvValueTrue($value) ? 1 : 0;
                                if (!$product->advanced_stock_management) {
                                    $value = 0;
                                }
                                $product->depends_on_stock = $value;
                                StockAvailable::setProductDependsOnStock($product->id, $product->depends_on_stock);
                                break;
                            case 'quantity':
                                if ($value !== '') {
                                    if (preg_match("/\[FORMULA:([\s\[\]\#\.\,\;\+\-\*\/\d]+)\]/", $value, $match)) {
                                        // No mapping selected for quantity column and map_default_values contains formula.
                                        // So base quantity is 0 and calculation result will be final value.
                                        // map_default_values may contain other columns e.g. [%column%]
                                        $value = ElegantalEasyImportTools::getModifiedPriceByFormula(0, $match[1], $this->decimal_char);
                                    } else {
                                        $value = str_replace([' ', '>', '<', '='], '', $value);
                                        if (strpos($value, ',') !== false && strpos($value, '.') !== false) {
                                            $value = str_replace(',', '', $value);
                                        }
                                        if ($value_default && preg_match("/\[FORMULA:([\s\[\]\#\.\,\;\+\-\*\/\d]+)\]/", $value_default, $match)) {
                                            $value = ElegantalEasyImportTools::getModifiedPriceByFormula($value, $match[1], $this->decimal_char);
                                        }
                                    }
                                    $value = $settings['product_quantity_data_type'] == 'float' ? (float) $value : (int) $value;
                                    $value = $value < 0 ? 0 : $value;
                                    $combination_data['quantity'] = $value;
                                }
                                break;
                            case 'quantity_add_or_subtract':
                                if ($value !== '') {
                                    $combination_data['quantity'] = (isset($combination_data['quantity']) && $combination_data['quantity']) ? $combination_data['quantity'] + (int) $value : (int) $value;
                                    $combination_data['quantity'] = $combination_data['quantity'] < 0 ? 0 : $combination_data['quantity'];
                                }
                                break;
                            case 'minimal_quantity':
                                $combination_data['minimal_quantity'] = ($value >= 1) ? (int) $value : 1;
                                break;
                            case 'stock_location':
                                $value = Tools::substr($value, 0, 64);
                                $combination_data['stock_location'] = $value;
                                break;
                            case 'low_stock_level':
                                $combination_data['low_stock_threshold'] = (int) $value;
                                break;
                            case 'email_alert_on_low_stock':
                                $combination_data['low_stock_alert'] = $this->isCsvValueTrue($value) ? 1 : 0;
                                break;
                            case 'ecotax':
                                $combination_data['ecotax'] = Configuration::get('PS_USE_ECOTAX') ? (float) str_replace(',', '.', $value) : 0;
                                break;
                            case 'delete_existing_images':
                                if ($value && $this->isCsvValueTrue($value) && $id_product_attribute) {
                                    // Delete combinations images completely if they are not used by other combinations, otherwise just un-assign them from this combination.
                                    $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'product_attribute_image`
                                        WHERE `id_product_attribute`=' . (int) $id_product_attribute . ' AND `id_image` NOT IN (SELECT `id_image` FROM `' . _DB_PREFIX_ . 'product_attribute_image` WHERE `id_product_attribute` != ' . (int) $id_product_attribute . ')';
                                    $product_attribute_images = Db::getInstance()->executeS($sql);
                                    if ($product_attribute_images && is_array($product_attribute_images)) {
                                        foreach ($product_attribute_images as $product_attribute_image) {
                                            $image = new Image($product_attribute_image['id_image']);
                                            if (Validate::isLoadedObject($image)) {
                                                $image->delete();
                                            }
                                        }
                                    }
                                    Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'product_attribute_image` WHERE `id_product_attribute`=' . (int) $id_product_attribute);
                                }
                                break;
                            case 'images':
                            case 'default_image':
                            case preg_match("/^image_[\d]+$/", $attr) ? true : false:
                                if ($value_default == 'import_images_only_if_product_has_no_image') {
                                    if (Image::getImages($id_lang, $product->id)) {
                                        break;
                                    } else {
                                        $value_default = '';
                                    }
                                }
                                if ($value) {
                                    $captions = [];
                                    $default_image = '';
                                    $images = array_filter(array_map('trim', explode($multiple_value_separator, $value)), 'strlen'); // Remove empty images
                                    if ($images && is_array($images) && count($images) > 1) {
                                        if (isset($line[$index]) && $line[$index] && $value_default) {
                                            // Import selected images by position
                                            if (preg_match("/^[\d\,]+$/", $value_default) || $value_default == 'LAST') {
                                                if ($value_default == 'LAST') {
                                                    $images = [end($images)];
                                                } else {
                                                    // map_default_values contains positions of images to be imported. e.g. 1,2,3
                                                    $img_positions = explode(',', $value_default);
                                                    $img_positions = array_map('intval', $img_positions);
                                                    foreach ($images as $img_key => $img_value) {
                                                        if (!in_array($img_key + 1, $img_positions)) {
                                                            unset($images[$img_key]);
                                                        }
                                                    }
                                                }
                                            } else {
                                                $images[] = $value_default;
                                            }
                                        }
                                    }
                                    if ($images) {
                                        foreach ($this->id_all_langs as $id_lang) {
                                            if (isset($line[$map['captions_' . $id_lang]]) && $line[$map['captions_' . $id_lang]]) {
                                                $caption = $this->getDictionaryValue('captions', $line[$map['captions_' . $id_lang]], $line, $csv_header);
                                                $captions[$id_lang] = array_map('trim', explode($multiple_value_separator, $caption));
                                            } else {
                                                $caption = $this->getDictionaryValue('captions', $map_default_values['captions_' . $id_lang], $line, $csv_header);
                                                $captions[$id_lang] = array_map('trim', explode($multiple_value_separator, $caption));
                                            }
                                        }
                                        if ($attr == 'default_image') {
                                            $default_image = reset($images);
                                        }
                                        $convert_to = (isset($line[$map['convert_image_to']]) && $line[$map['convert_image_to']]) ? $line[$map['convert_image_to']] : $map_default_values['convert_image_to'];
                                        $combination_image_ids = $this->createProductImages($product, $images, $default_image, $captions, $convert_to, $settings['is_compare_image_using_imagick']);
                                        if ($combination_image_ids && is_array($combination_image_ids)) {
                                            if ($combination_data['images']) {
                                                foreach ($combination_image_ids as $img_id) {
                                                    if (!in_array($img_id, $combination_data['images'])) {
                                                        $combination_data['images'][] = $img_id;
                                                    }
                                                }
                                            } else {
                                                $combination_data['images'] = $combination_image_ids;
                                            }
                                        }
                                    }
                                }
                                break;
                            case 'combination_ean':
                                if ($this->find_combinations_by == 'combination_ean') {
                                    $combination_data['combination_ean'] = $value;
                                } else {
                                    if ($value && Validate::isEan13($value)) {
                                        $combination_data['combination_ean'] = $value;
                                    } else {
                                        $combination_data['combination_ean'] = '';
                                        if ($value) {
                                            $this->addError('EAN is not valid: ' . $value, $product);
                                        }
                                    }
                                }
                                break;
                            case 'default':
                                $combination_data['default'] = $this->isCsvValueTrue($value) ? 1 : 0;
                                break;
                            case 'upc':
                                $combination_data['upc'] = ($value && Validate::isUpc($value)) ? $value : '';
                                break;
                            case 'isbn':
                                $combination_data['isbn'] = ($value && Validate::isIsbn($value)) ? $value : '';
                                break;
                            case 'combination_mpn':
                                $combination_data['combination_mpn'] = $value ? Tools::substr($value, 0, 40) : '';
                                break;
                            case 'supplier_reference':
                                $combination_data['supplier_reference'] = $value;
                                break;
                            case 'supplier_price':
                                $combination_data['supplier_price'] = (float) $this->extractPriceInDefaultCurrency($value, $currency);
                                break;
                            case 'available_date':
                                if ($value && strtotime($value)) {
                                    $combination_data['available_date'] = date('Y-m-d', strtotime($value));
                                } else {
                                    $combination_data['available_date'] = null;
                                }
                                break;
                            default:
                                break;
                        }
                    }

                    if ($id_reference_comb) {
                        switch ($this->find_combinations_by) {
                            case 'combination_reference':
                                if (empty($combination_data['combination_reference'])) {
                                    $combination_data['combination_reference'] = $id_reference_comb;
                                }
                                break;
                            case 'combination_ean':
                                if (empty($combination_data['combination_ean']) && Validate::isEan13($id_reference_comb)) {
                                    $combination_data['combination_ean'] = $id_reference_comb;
                                }
                                break;
                            case 'combination_mpn':
                                if (empty($combination_data['combination_mpn'])) {
                                    $combination_data['combination_mpn'] = $id_reference_comb;
                                }
                                break;
                            case 'combination_isbn':
                                if (empty($combination_data['combination_isbn'])) {
                                    $combination_data['isbn'] = $id_reference_comb;
                                }
                                break;
                            case 'combination_supplier_reference':
                                if (empty($combination_data['supplier_reference'])) {
                                    $combination_data['supplier_reference'] = $id_reference_comb;
                                }
                                break;
                            default:
                                break;
                        }
                    }

                    if ($combination_data['default']) {
                        $product->deleteDefaultAttributes();
                    }

                    // To call product->update() one time at the end
                    $product_update = false;

                    // If product price is 0, use combination price for product
                    // This is disabled because some people make product price = 0 on purpose
                    // If this is really needed, make a new setting to enable this behavior
                    /*
                    if ($product->price <= 0 && $combination_data['impact_on_price'] > 0) {
                    $product->price = $combination_data['impact_on_price'];
                    $combination_data['impact_on_price'] = 0;
                    $product_update = true;
                    }*/

                    if ($id_product_attribute) {
                        $product->updateAttribute($id_product_attribute, $combination_data['wholesale_price'], $combination_data['impact_on_price'], $combination_data['impact_on_weight'], $combination_data['impact_on_unit_price'], $combination_data['ecotax'], $combination_data['images'], $combination_data['combination_reference'], $combination_data['combination_ean'], $combination_data['default'], null, $combination_data['upc'], $combination_data['minimal_quantity'], $combination_data['available_date'], true, $shop_ids, $combination_data['isbn'], $combination_data['low_stock_threshold'], $combination_data['low_stock_alert'], $combination_data['combination_mpn']);
                        // number_of_products_updated can get higher than number_of_products_processed because several products may have the same reference
                        ++$this->currentHistory->number_of_products_updated;
                        $this->currentHistory->ids_of_products_updated .= ($this->currentHistory->ids_of_products_updated ? ',' : '') . $id_product_attribute;
                    } elseif (count($csv_attribute_values) > 0) {
                        $id_product_attribute = $product->addCombinationEntity($combination_data['wholesale_price'], $combination_data['impact_on_price'], $combination_data['impact_on_weight'], 0, $combination_data['ecotax'], $combination_data['quantity'], $combination_data['images'], $combination_data['combination_reference'], (isset($product->id_supplier) && $product->id_supplier > 0) ? $product->id_supplier : 0, $combination_data['combination_ean'], $combination_data['default'], null, $combination_data['upc'], $combination_data['minimal_quantity'], $shop_ids, $combination_data['available_date'], $combination_data['isbn'], $combination_data['low_stock_threshold'], $combination_data['low_stock_alert'], $combination_data['combination_mpn']);
                        if ($id_product_attribute) {
                            ++$this->currentHistory->number_of_products_created;
                            $this->currentHistory->ids_of_products_created .= ($this->currentHistory->ids_of_products_created ? ',' : '') . $id_product_attribute;
                        }
                    }

                    if ($id_product_attribute) {
                        // Add attributes to the combination
                        if (count($csv_attribute_values) > 0) {
                            Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'product_attribute_combination` WHERE `id_product_attribute` = ' . (int) $id_product_attribute);
                            foreach ($csv_attribute_values as $csv_attribute_value_id) {
                                Db::getInstance()->execute('INSERT IGNORE INTO `' . _DB_PREFIX_ . 'product_attribute_combination` (`id_attribute`, `id_product_attribute`) VALUES (' . (int) $csv_attribute_value_id . ',' . (int) $id_product_attribute . ')', false);
                            }
                        }
                        // Assign supplier to the combination
                        if ($id_product_attribute && $combination_data['supplier_reference']) {
                            $supplier_price = $combination_data['supplier_price'] > 0 ? $combination_data['supplier_price'] : null;
                            $product->addSupplierReference($product->id_supplier, $id_product_attribute, $combination_data['supplier_reference'], $supplier_price, $id_currency_default);
                        }
                        // Set quantity to the combination
                        $combination_warehouse = (isset($line[$map['warehouse_id']]) && $line[$map['warehouse_id']]) ? $line[$map['warehouse_id']] : $map_default_values['warehouse_id'];
                        if ($combination_warehouse && $product->advanced_stock_management) {
                            if (Warehouse::exists($combination_warehouse)) {
                                $query = new DbQuery();
                                $query->select('id_warehouse_product_location');
                                $query->from('warehouse_product_location');
                                $query->where('id_product = ' . (int) $product->id . ' AND id_product_attribute = ' . (int) $id_product_attribute . ' AND id_warehouse = ' . (int) $combination_warehouse);
                                $warehouse_product_location = (int) Db::getInstance()->getValue($query);
                                if ($warehouse_product_location) {
                                    $wpl = new WarehouseProductLocation($warehouse_product_location);
                                    $wpl->location = (isset($line[$map['location_in_warehouse']]) && $line[$map['location_in_warehouse']]) ? $line[$map['location_in_warehouse']] : $map_default_values['location_in_warehouse'];
                                    $wpl->update();
                                } else {
                                    $wpl = new WarehouseProductLocation();
                                    $wpl->id_product = $product->id;
                                    $wpl->id_product_attribute = $id_product_attribute;
                                    $wpl->id_warehouse = (int) $combination_warehouse;
                                    $wpl->location = (isset($line[$map['location_in_warehouse']]) && $line[$map['location_in_warehouse']]) ? $line[$map['location_in_warehouse']] : $map_default_values['location_in_warehouse'];
                                    $wpl->add();
                                }
                                StockAvailable::synchronize($product->id);
                            } else {
                                $this->addError('Warehouse does not exist with ID: ' . $combination_warehouse, $product);
                            }
                        }
                        if (!is_null($combination_data['quantity'])) {
                            // Update quantity in Warehouse
                            if ($product->advanced_stock_management) {
                                if (empty($combination_warehouse)) {
                                    $query = new DbQuery();
                                    $query->select('id_warehouse');
                                    $query->from('warehouse_product_location');
                                    $query->where('id_product = ' . (int) $product->id . ' AND id_product_attribute = ' . (int) $id_product_attribute);
                                    $combination_warehouse = (int) Db::getInstance()->getValue($query);
                                }
                                if ($combination_warehouse) {
                                    $stock_manager = StockManagerFactory::getManager();
                                    $price = str_replace(',', '.', $product->wholesale_price);
                                    if ($price == 0) {
                                        $price = $product->price; // 0 will not work because price_te is required and 0.000001 will not work because it will be converted to 1.0E-6
                                    }
                                    $price = round((float) $price, 6);
                                    $warehouse = new Warehouse($combination_warehouse);
                                    if ($stock_manager->addProduct($product->id, $id_product_attribute, $warehouse, $combination_data['quantity'], 1, $price, true)) {
                                        StockAvailable::synchronize($product->id);
                                    }
                                }
                            }
                            // Update quantity in Shop
                            $tmp_context_shop = Shop::getContext();
                            Shop::setContext(Shop::CONTEXT_SHOP, $id_shop);
                            foreach ($shop_ids as $sh_id) {
                                StockAvailable::setQuantity($product->id, $id_product_attribute, $combination_data['quantity'], $sh_id);
                            }
                            Shop::setContext($tmp_context_shop, $id_shop);
                        }
                        // Set stock_location to the combination
                        if ($id_product_attribute && isset($combination_data['stock_location']) && method_exists('StockAvailable', 'setLocation')) {
                            $tmp_context_shop = Shop::getContext();
                            Shop::setContext(Shop::CONTEXT_SHOP, $id_shop);
                            foreach ($shop_ids as $sh_id) {
                                call_user_func(['StockAvailable', 'setLocation'], $product->id, $combination_data['stock_location'], $sh_id, $id_product_attribute);
                            }
                            Shop::setContext($tmp_context_shop, $id_shop);
                        }
                    }

                    // Check and make sure default combination is set
                    $product->checkDefaultAttributes();
                    if (!$product->cache_default_attribute) {
                        Product::updateDefaultAttribute($product->id);
                    }

                    // Disable dependsOnStock if advanced_stock_management is disabled
                    if ($product->advanced_stock_management == 0 && StockAvailable::dependsOnStock($product->id) == 1) {
                        StockAvailable::setProductDependsOnStock($product->id, 0);
                    }

                    // Enable this product if it has at least one stock from its combinations or disable it if it has no any stock
                    if ($this->enable_if_have_stock || $this->disable_if_no_stock) {
                        $product_stock = StockAvailable::getQuantityAvailableByProduct($product->id);
                        if ($this->enable_if_have_stock && $product_stock) {
                            $product->active = 1;
                            $product_update = true;
                        } elseif ($this->disable_if_no_stock && !$product_stock) {
                            $product->active = 0;
                            $product_update = true;
                        }
                    }

                    // Disable product if it has no image
                    if ($this->disable_if_no_image) {
                        if (empty(Image::getImages($this->id_lang_default, $product->id))) {
                            $product->active = 0;
                            $product_update = true;
                        }
                    }

                    if ($product_update) {
                        $product->update();
                    }

                    if ($id_product_attribute) {
                        // Create specific price for combination
                        $discount_amount = (isset($line[$map['discount_amount']]) && $line[$map['discount_amount']]) ? $line[$map['discount_amount']] : $map_default_values['discount_amount'];
                        $discount_amount = (float) $this->extractPriceInDefaultCurrency($discount_amount);
                        $discount_percent = (isset($line[$map['discount_percent']]) && $line[$map['discount_percent']]) ? $line[$map['discount_percent']] : $map_default_values['discount_percent'];
                        $discount_base_price = (isset($line[$map['discount_base_price']]) && $line[$map['discount_base_price']] !== '') ? $line[$map['discount_base_price']] : $map_default_values['discount_base_price'];
                        $discounted_price = (isset($line[$map['discounted_price']]) && $line[$map['discounted_price']] !== '') ? $line[$map['discounted_price']] : $map_default_values['discounted_price'];
                        $delete_existing_discount = (isset($line[$map['delete_existing_discount']]) && $line[$map['delete_existing_discount']]) ? $line[$map['delete_existing_discount']] : $map_default_values['delete_existing_discount'];
                        if ($delete_existing_discount && $this->isCsvValueTrue($delete_existing_discount)) {
                            Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'specific_price` WHERE `id_specific_price_rule` = 0 AND `id_product` = ' . (int) $product->id . ' AND `id_product_attribute` = ' . (int) $id_product_attribute . ' AND (`id_shop` = 0 OR `id_shop` IN (' . implode(', ', array_map('intval', $shop_ids)) . '))');
                        }

                        if ($discounted_price) {
                            if (preg_match("/\[FORMULA:([\s\[\]\#\.\,\;\+\-\*\/\d]+)\]/", $discounted_price, $match)) {
                                // No mapping selected for discounted_price column and map_default_values contains formula.
                                // So base price is 0 and calculation result will be final value.
                                // map_default_values may contain other columns e.g. [%column%]
                                $discounted_price = ElegantalEasyImportTools::getModifiedPriceByFormula(0, $match[1], $this->decimal_char);
                            } else {
                                $discounted_price = (float) $this->extractPriceInDefaultCurrency($discounted_price, $currency);
                                if ($map_default_values['discounted_price'] && preg_match("/\[FORMULA:([\s\[\]\#\.\,\;\+\-\*\/\d]+)\]/", $map_default_values['discounted_price'], $match)) {
                                    $discounted_price = ElegantalEasyImportTools::getModifiedPriceByFormula($discounted_price, $match[1], $this->decimal_char);
                                }
                            }
                        }

                        if ($discount_percent || $discount_amount || $discounted_price || $discount_base_price !== '') {
                            $discount = 0;
                            $is_percentage = false;
                            if ($discount_percent) {
                                if (preg_match('/([0-9]+\.{0,1}[0-9]*)/', $discount_percent, $match)) {
                                    $discount_percent = $match[0];
                                }
                                if ($discount_percent > 0 && $discount_percent < 1) {
                                    $discount_percent *= 100;
                                }
                                $discount = $discount_percent;
                                $is_percentage = true;
                            } elseif ($discount_amount) {
                                $discount = $discount_amount;
                                if (strpos($discount, '%') !== false) {
                                    $is_percentage = true;
                                }
                            }
                            $discount_from = '0000-00-00 00:00:00';
                            $discount_to = '0000-00-00 00:00:00';
                            $is_discount_tax_included = 1;
                            $discount_starting_unit = (isset($line[$map['discount_starting_unit']]) && $line[$map['discount_starting_unit']]) ? $line[$map['discount_starting_unit']] : $map_default_values['discount_starting_unit'];
                            $discount_customer_group = (isset($line[$map['discount_customer_group']]) && $line[$map['discount_customer_group']]) ? $line[$map['discount_customer_group']] : $map_default_values['discount_customer_group'];
                            $discount_customer_id = (isset($line[$map['discount_customer_id']]) && $line[$map['discount_customer_id']] !== '') ? $line[$map['discount_customer_id']] : $map_default_values['discount_customer_id'];
                            $discount_country = (isset($line[$map['discount_country']]) && $line[$map['discount_country']]) ? $line[$map['discount_country']] : $map_default_values['discount_country'];
                            $discount_currency = (isset($line[$map['discount_currency']]) && $line[$map['discount_currency']]) ? $line[$map['discount_currency']] : $map_default_values['discount_currency'];
                            if (isset($line[$map['discount_from']]) && $line[$map['discount_from']]) {
                                $discount_from = date('Y-m-d H:i:s', strtotime($line[$map['discount_from']]));
                            } elseif ($map_default_values['discount_from']) {
                                $discount_from = date('Y-m-d H:i:s', strtotime($map_default_values['discount_from']));
                            }
                            if (isset($line[$map['discount_to']]) && $line[$map['discount_to']]) {
                                $discount_to = date('Y-m-d H:i:s', strtotime($line[$map['discount_to']]));
                            } elseif ($map_default_values['discount_to']) {
                                $discount_to = date('Y-m-d H:i:s', strtotime($map_default_values['discount_to']));
                            }
                            $discount_tax_included = (isset($line[$map['discount_tax_included']]) && $line[$map['discount_tax_included']] !== '') ? $line[$map['discount_tax_included']] : $map_default_values['discount_tax_included'];
                            if ($discount_tax_included !== '') {
                                $is_discount_tax_included = $this->isCsvValueFalse($discount_tax_included) ? 0 : 1;
                            }

                            $combination_price = (float) Product::getPriceStatic($product->id, $is_discount_tax_included, $id_product_attribute, 2, null, false, false);

                            if ((isset($line[$map['combination_price']]) || isset($line[$map['combination_price_tax_incl']]) || $map_default_values['combination_price'] || $map_default_values['combination_price_tax_incl'])
                                && !$discount_amount && !$discount_percent && $combination_price > $discounted_price) {
                                if ($is_percentage) {
                                    // Discount percent
                                    $discount = round(($combination_price - $discounted_price) * 100 / $combination_price, 6);
                                } else {
                                    // Discount amount
                                    $discount = round($combination_price - $discounted_price, 6);
                                }
                            } elseif ((!isset($line[$map['combination_price']]) && !isset($line[$map['combination_price_tax_incl']]) && !$map_default_values['combination_price'] && !$map_default_values['combination_price_tax_incl'])
                                && (($discount_amount && $discounted_price > $discount_amount) || $discount_percent)) {
                                if ($discount_amount && $discounted_price > $discount_amount) {
                                    $combination_price = (float) number_format($discounted_price + $discount_amount, 6, '.', '');
                                } elseif ($discount_percent) {
                                    $combination_price = (float) number_format($discounted_price / (1 - $discount_percent / 100), 6, '.', '');
                                }
                                // Update combination price
                                $tax_rate = $product->getTaxesRate(new Address());
                                $product_price_tax_incl = (float) number_format($product->price * (1 + $tax_rate / 100), 6, '.', '');
                                $combination_data['impact_on_price'] = round(($combination_price - $product_price_tax_incl) / (1 + $tax_rate / 100), 6);
                                $product->updateAttribute($id_product_attribute, $combination_data['wholesale_price'], $combination_data['impact_on_price'], $combination_data['impact_on_weight'], $combination_data['impact_on_unit_price'], $combination_data['ecotax'], $combination_data['images'], $combination_data['combination_reference'], $combination_data['combination_ean'], $combination_data['default'], null, $combination_data['upc'], $combination_data['minimal_quantity'], $combination_data['available_date'], true, $shop_ids, $combination_data['isbn'], $combination_data['low_stock_threshold'], $combination_data['low_stock_alert'], $combination_data['combination_mpn']);
                            }

                            $this->createProductSpecificPrice($product->id, $discount, $is_percentage, $is_discount_tax_included, $discount_from, $discount_to, $discount_base_price, $discount_starting_unit, $discount_customer_group, $discount_customer_id, $discount_country, $discount_currency, $id_product_attribute, $shop_ids);
                        }

                        // Process modules
                        if (isset($map['ecm_cmlid_xml']) && ($map['ecm_cmlid_xml'] >= 0 || $map_default_values['ecm_cmlid_xml'] != '')) {
                            $ecm_cmlid_xml = (isset($line[$map['ecm_cmlid_xml']]) && $line[$map['ecm_cmlid_xml']]) ? $line[$map['ecm_cmlid_xml']] : $map_default_values['ecm_cmlid_xml'];
                            $this->createEcmCmlidRelation($product->id, $ecm_cmlid_xml, $id_product_attribute);
                        }
                        if ((isset($map['pproperties_quantity_step']) && ($map['pproperties_quantity_step'] >= 0 || $map_default_values['pproperties_quantity_step'] != '')) || (isset($map['pproperties_minimal_quantity']) && ($map['pproperties_minimal_quantity'] >= 0 || $map_default_values['pproperties_minimal_quantity'] != ''))) {
                            $pp_quantity_step = ($map['pproperties_quantity_step'] >= 0 && isset($line[$map['pproperties_quantity_step']])) ? $line[$map['pproperties_quantity_step']] : $map_default_values['pproperties_quantity_step'];
                            $pp_minimal_quantity = ($map['pproperties_minimal_quantity'] >= 0 && isset($line[$map['pproperties_minimal_quantity']])) ? $line[$map['pproperties_minimal_quantity']] : $map_default_values['pproperties_minimal_quantity'];
                            $this->createPpropertiesRelation($product->id, $id_product_attribute, $pp_quantity_step, $pp_minimal_quantity, $shop_ids);
                        }
                        if (isset($map['bms_advancedstock_warehouse']) && ($map['bms_advancedstock_warehouse'] >= 0 || $map_default_values['bms_advancedstock_warehouse'] != '')) {
                            $bms_advancedstock_warehouse = (isset($line[$map['bms_advancedstock_warehouse']]) && $line[$map['bms_advancedstock_warehouse']]) ? $line[$map['bms_advancedstock_warehouse']] : $map_default_values['bms_advancedstock_warehouse'];
                            $bms_advancedstock_physical_quantity = (isset($line[$map['bms_advancedstock_physical_quantity']]) && $line[$map['bms_advancedstock_physical_quantity']] !== '') ? $line[$map['bms_advancedstock_physical_quantity']] : $map_default_values['bms_advancedstock_physical_quantity'];
                            $bms_advancedstock_available_quantity = (isset($line[$map['bms_advancedstock_available_quantity']]) && $line[$map['bms_advancedstock_available_quantity']] !== '') ? $line[$map['bms_advancedstock_available_quantity']] : $map_default_values['bms_advancedstock_available_quantity'];
                            $bms_advancedstock_reserved_quantity = (isset($line[$map['bms_advancedstock_reserved_quantity']]) && $line[$map['bms_advancedstock_reserved_quantity']] !== '') ? $line[$map['bms_advancedstock_reserved_quantity']] : $map_default_values['bms_advancedstock_reserved_quantity'];
                            $bms_advanced_stock_shelf_location = (isset($line[$map['bms_advanced_stock_shelf_location']]) && $line[$map['bms_advanced_stock_shelf_location']]) ? $line[$map['bms_advanced_stock_shelf_location']] : $map_default_values['bms_advanced_stock_shelf_location'];
                            $this->createBmsAdvancedstockRelation($product->id, $id_product_attribute, $bms_advancedstock_warehouse, $bms_advancedstock_physical_quantity, $bms_advancedstock_available_quantity, $bms_advancedstock_reserved_quantity, $bms_advanced_stock_shelf_location, $shop_ids);
                        }
                        if (isset($map['msrp_price_tax_excl']) && ($map['msrp_price_tax_excl'] >= 0 || $map_default_values['msrp_price_tax_excl'] != '')) {
                            $msrp_price_tax_excl = ($map['msrp_price_tax_excl'] >= 0 && isset($line[$map['msrp_price_tax_excl']])) ? $line[$map['msrp_price_tax_excl']] : $map_default_values['msrp_price_tax_excl'];
                            $this->createMsrpRelation($product->id, $id_product_attribute, $product->id_tax_rules_group, $msrp_price_tax_excl, false, $shop_ids);
                        }
                        if (isset($map['msrp_price_tax_incl']) && ($map['msrp_price_tax_incl'] >= 0 || $map_default_values['msrp_price_tax_incl'] != '')) {
                            $msrp_price_tax_incl = ($map['msrp_price_tax_incl'] >= 0 && isset($line[$map['msrp_price_tax_incl']])) ? $line[$map['msrp_price_tax_incl']] : $map_default_values['msrp_price_tax_incl'];
                            $this->createMsrpRelation($product->id, $id_product_attribute, $product->id_tax_rules_group, $msrp_price_tax_incl, true, $shop_ids);
                        }
                        if (isset($map['areapacks_label'], $map['areapacks_type'], $map['areapacks_area'])) {
                            $areapacks_label = (isset($line[$map['areapacks_label']]) && $line[$map['areapacks_label']]) ? $line[$map['areapacks_label']] : $map_default_values['areapacks_label'];
                            $areapacks_type = (isset($line[$map['areapacks_type']]) && $line[$map['areapacks_type']]) ? $line[$map['areapacks_type']] : $map_default_values['areapacks_type'];
                            $areapacks_area = (isset($line[$map['areapacks_area']]) && $line[$map['areapacks_area']] !== '') ? $line[$map['areapacks_area']] : $map_default_values['areapacks_area'];
                            if ($areapacks_label || $areapacks_type || $areapacks_area !== '') {
                                $this->createAreapacksRelation($product->id, $id_product_attribute, $areapacks_label, $areapacks_type, $areapacks_area);
                            }
                        }
                    }

                    // Set context shop back to its original value
                    Shop::setContext($context_shop, $id_shop_context);
                } catch (Exception $e) {
                    $this->addError('Exception: ' . $e->getMessage());
                    if ($settings['is_debug_mode']) {
                        $this->currentHistory->date_ended = date('Y-m-d H:i:s');
                        $this->currentHistory->update();
                        throw new Exception($e->getMessage());
                    }
                }
            }
            // We need this update because it may fail to update at the end, if timeout error happens.
            $this->currentHistory->date_ended = date('Y-m-d H:i:s');
            $this->currentHistory->update();
        }

        return true;
    }

    /**
     * Action called after finishing import
     */
    protected function actionAfterImport()
    {
        if ($this->is_cron) {
            try {
                if ($this->email_to_send_notification && Validate::isEmail($this->email_to_send_notification)) {
                    // Send email notification after CRON has finished importing
                    $error_log = '';
                    $errors = ElegantalEasyImportError::model()->findAll([
                        'condition' => [
                            'id_elegantaleasyimport_history' => $this->currentHistory->id,
                        ],
                        'order' => 'id_elegantaleasyimport_error DESC',
                        'limit' => 50,
                    ]);
                    if ($errors) {
                        foreach ($errors as $error) {
                            $error_log .= $error_log ? PHP_EOL : '';
                            $error_log .= $error['error'] . '  Product: ' . $error['product_id_reference'];
                        }
                    }

                    $subject = 'CRON has finished importing for the rule "' . $this->name . '"';

                    $events_log = '';
                    $products = $this->currentHistory->getProductsProcessed($this->entity, 'created', '', '', 500);
                    if ($products) {
                        $events_log = PHP_EOL . ($this->entity == 'combination' ? $this->l('New combinations') : $this->l('New products')) . ': ' . PHP_EOL;
                        foreach ($products as $product) {
                            if ($product['reference']) {
                                $events_log .= $this->l('Product reference') . ': ' . $product['reference'];
                            } elseif ($product['id_product']) {
                                $events_log .= $this->l('Product ID') . ': ' . $product['id_product'];
                            }

                            if ($product['combination_reference']) {
                                $events_log .= ', ' . $this->l('Combination reference') . ': ' . $product['combination_reference'];
                            } elseif ($product['combination_ean']) {
                                $events_log .= ', ' . $this->l('Combination EAN') . ': ' . $product['combination_ean'];
                            }

                            if ($product['id_product_attribute']) {
                                $attributes = Db::getInstance()->executeS('
                                    SELECT agl.`name` AS `attribute_name`, al.`name` AS `attribute_value`
                                    FROM `' . _DB_PREFIX_ . 'product_attribute_combination` pac
                                    INNER JOIN `' . _DB_PREFIX_ . 'attribute` a ON (a.`id_attribute` = pac.`id_attribute`)
                                    INNER JOIN `' . _DB_PREFIX_ . 'attribute_lang` al ON (al.`id_attribute` = a.`id_attribute` AND al.`id_lang` = ' . (int) $this->context->language->id . ')
                                    INNER JOIN `' . _DB_PREFIX_ . 'attribute_group` ag ON (ag.`id_attribute_group` = a.`id_attribute_group`)
                                    INNER JOIN `' . _DB_PREFIX_ . 'attribute_group_lang` agl ON (agl.`id_attribute_group` = ag.`id_attribute_group` AND agl.`id_lang` = ' . (int) $this->context->language->id . ')
                                    WHERE pac.`id_product_attribute` = ' . (int) $product['id_product_attribute']);
                                if ($attributes) {
                                    foreach ($attributes as $attr) {
                                        $events_log .= ' ' . $attr['attribute_name'] . ':' . $attr['attribute_value'] . ' ';
                                    }
                                }
                            }
                            $events_log .= PHP_EOL;
                        }
                    }

                    $error_log = $error_log ? (!$events_log ? PHP_EOL : '') . 'Error Log: ' . PHP_EOL . $error_log . PHP_EOL : '';
                    $is_html = in_array(Configuration::get('PS_MAIL_TYPE'), [Mail::TYPE_BOTH, Mail::TYPE_HTML]);
                    if ($is_html) {
                        if ($events_log) {
                            $events_log = nl2br($events_log . PHP_EOL);
                        }
                        if ($error_log) {
                            $error_log = nl2br($error_log . PHP_EOL);
                        }
                    }
                    $template_vars = [
                        '{rule_name}' => $this->name,
                        '{error_log}' => $error_log,
                        '{events_log}' => $events_log,
                        '{history_date_started}' => date('d-m-Y H:i:s', strtotime($this->currentHistory->date_started)),
                        '{history_date_ended}' => date('d-m-Y H:i:s', strtotime($this->currentHistory->date_ended)),
                        '{total_number_of_products}' => $this->currentHistory->total_number_of_products,
                        '{number_of_products_processed}' => $this->currentHistory->number_of_products_processed,
                        '{number_of_products_created}' => $this->currentHistory->number_of_products_created,
                        '{number_of_products_updated}' => $this->currentHistory->number_of_products_updated,
                        '{number_of_products_deleted}' => $this->currentHistory->number_of_products_deleted,
                    ];
                    $template_path = dirname(__FILE__) . '/../mails/';
                    $template = $this->entity == 'combination' ? 'import_finished_combination' : 'import_finished_product';

                    $iso = Tools::strtolower(Language::getIsoById((int) $this->context->language->id));
                    if ($iso) {
                        if (!file_exists($template_path . $iso)) {
                            mkdir($template_path . $iso, 0777, true);
                        }
                        if (!file_exists($template_path . $iso . '/' . $template . '.txt')) {
                            @copy($template_path . 'en/' . $template . '.txt', $template_path . $iso . '/' . $template . '.txt');
                        }
                        if (!file_exists($template_path . $iso . '/' . $template . '.html')) {
                            @copy($template_path . 'en/' . $template . '.html', $template_path . $iso . '/' . $template . '.html');
                        }
                    }

                    Mail::Send($this->context->language->id, $template, $subject, $template_vars, $this->email_to_send_notification, null, null, 'Easy Import Module', null, null, $template_path);
                } elseif ($this->email_to_send_notification && Validate::isAbsoluteUrl($this->email_to_send_notification)) {
                    Tools::file_get_contents($this->email_to_send_notification, false, null, 200);
                }
            } catch (Exception $e) {
                // Do nothing
            }
        }

        // Delete old logs
        $this->currentHistory->deleteOldLogs();
    }

    public function addError($error_text, $product = null)
    {
        if (!$this->currentHistory) {
            $this->currentHistory = $this->getLastHistory();
        }
        $error = new ElegantalEasyImportError();
        $error->id_elegantaleasyimport_history = $this->currentHistory->id;
        $error->product_id_reference = $this->current_id_reference . (($product && !empty($product->id) && $this->find_products_by != 'id') ? ' ID: ' . $product->id : '');
        $error->error = htmlspecialchars_decode($error_text);
        $error->date_created = date('Y-m-d H:i:s');
        if (!$error->add()) {
            throw new Exception(Db::getInstance()->getMsgError());
        }
    }

    public function getLastHistory()
    {
        $historyObj = null;
        $history = ElegantalEasyImportHistory::model()->find([
            'condition' => [
                'id_elegantaleasyimport' => $this->id,
            ],
            'order' => 'id_elegantaleasyimport_history DESC',
        ]);
        if ($history) {
            $historyObj = new ElegantalEasyImportHistory($history['id_elegantaleasyimport_history']);
        }
        if (!Validate::isLoadedObject($historyObj)) {
            $historyObj = ElegantalEasyImportHistory::createNew($this->id);
        }

        return $historyObj;
    }

    protected function isCsvValueTrue($value)
    {
        $value_lower = Tools::strtolower($value);
        if ($value == 1 || $value_lower == 'yes' || $value_lower == 'true') {
            return true;
        }

        return false;
    }

    protected function isCsvValueFalse($value)
    {
        $value_lower = Tools::strtolower($value);
        if (empty($value) || $value == ' ' || $value == '-' || $value_lower == 'no' || $value_lower == 'false') {
            return true;
        }

        return false;
    }

    protected function extractPriceInDefaultCurrency($price, $currency_sign = null)
    {
        if (empty($price)) {
            return 0;
        }

        if ($this->decimal_char == ',') {
            $amount = preg_replace("/[^0-9\,\-]/", '', $price);
            $amount = preg_replace("/\,/", '.', $amount);
        } else {
            $amount = preg_replace("/[^0-9\.\-]/", '', $price);
        }

        $amount = (float) $amount;

        $currencySigns = [];
        $currencies = Currency::getCurrencies();
        foreach ($currencies as $currency) {
            $currencySigns[Tools::strtoupper($currency['name'])] = $currency['id_currency'];
            $currencySigns[Tools::strtoupper($currency['iso_code'])] = $currency['id_currency'];
            $currencySigns[Tools::strtoupper($currency['sign'])] = $currency['id_currency'];
        }

        $pattern = '/(?:';
        $count = 0;
        foreach ($currencySigns as $currency_code => $id_currency) {
            $pattern .= ($count == 0) ? '' : '|';
            $pattern .= (Tools::strlen($currency_code) > 1) ? $currency_code : '[' . $currency_code . ']';
            ++$count;
        }
        $pattern .= ")\s*/iu";

        $priceCurrency = null;
        if ($currency_sign) {
            $currency_sign = Tools::strtoupper($currency_sign);
            if (isset($currencySigns[$currency_sign])) {
                $priceCurrency = Currency::getCurrencyInstance($currencySigns[$currency_sign]);
            }
        }
        if (!$priceCurrency && preg_match($pattern, $price, $match) && isset($match[0]) && Tools::strlen(trim($match[0])) > 0) {
            $priceCurrency = Currency::getCurrencyInstance($currencySigns[Tools::strtoupper(trim($match[0]))]);
        }
        if ($priceCurrency) {
            $defaultCurrency = Currency::getDefaultCurrency();
            if (Tools::strtoupper($priceCurrency->iso_code) != Tools::strtoupper($defaultCurrency->iso_code)) {
                $amount = (float) Tools::convertPriceFull($amount, $priceCurrency, $defaultCurrency);
            }
        }

        return round((float) $amount, 6);
    }

    protected function getDictionaryValue($attr, $value, $csv_row = [], $header_row = [])
    {
        // Build column value dictionary, that is used to replace value when column xyz = val then xyz = val2
        if (empty($this->column_value_dictionary)) {
            $settings = $this->getModelSettings();
            $column_value_dictionary = $settings['text_column_value_dictionary'];
            if ($column_value_dictionary) {
                $column_value_dictionary = preg_split('/\\r\\n|\\r|\\n/', $column_value_dictionary);
                if ($column_value_dictionary && is_array($column_value_dictionary)) {
                    $current_key = null;
                    foreach ($column_value_dictionary as $dict) {
                        $dict = trim($dict);
                        if (empty($dict)) {
                            continue;
                        }
                        if (preg_match("/^\[([a-zA-Z0-9_\s]+)\]$/i", $dict, $dict_matches)) {
                            $current_key = str_replace(' ', '_', Tools::strtolower($dict_matches[1]));
                        } else {
                            $key_value = explode('=>', $dict);
                            if (isset($key_value[0], $key_value[1]) && $current_key && !isset($this->column_value_dictionary[$current_key][$key_value[0]])) {
                                $key_value[0] = Tools::strtolower(trim($key_value[0]));
                                $this->column_value_dictionary[$current_key][$key_value[0]] = trim($key_value[1]);
                            }
                        }
                    }
                }
            }
        }

        // Combine fields if the value contains field names in this format: [%Name%] - [%Price%] etc.
        if ($csv_row && $header_row && preg_match_all("/\[\%([a-zA-Z0-9\.\,\s\_\-\'\"\p{L}\p{Sc}]+)\%\]/u", $value, $header_matches)) {
            foreach ($header_matches[0] as $key => $match) {
                if (isset($header_matches[1][$key])) {
                    $header_key = array_search($header_matches[1][$key], $header_row);
                    if ($header_key !== false) {
                        $value = str_replace($match, $csv_row[$header_key], $value);
                    }
                }
            }
        }

        // Replace value with dictionary value
        $value_l = Tools::strtolower($value);
        if (isset($this->column_value_dictionary[$attr][$value_l]) && array_key_exists($value_l, $this->column_value_dictionary[$attr])) {
            $value = $this->column_value_dictionary[$attr][$value_l];
        } elseif ($value < 0 && isset($this->column_value_dictionary[$attr]['negative_value_placeholder'])) {
            $value = $this->column_value_dictionary[$attr]['negative_value_placeholder'];
        }

        return $value;
    }

    public function getReferenceColumn()
    {
        $column = 'p.`reference`';
        if ($this->find_products_by == 'id') {
            $column = 'p.`id_product`';
        } elseif ($this->find_products_by == 'ean') {
            $column = 'p.`ean13`';
        } elseif ($this->find_products_by == 'supplier_reference') {
            $column = 'ps.`product_supplier_reference`';
        } elseif ($this->find_products_by == 'mpn') {
            $column = 'p.`mpn`';
        } elseif ($this->find_products_by == 'isbn') {
            $column = 'p.`isbn`';
        } elseif ($this->find_products_by == 'combination_reference') {
            $column = 'pa.`reference`';
        } elseif ($this->find_products_by == 'combination_ean') {
            $column = 'pa.`ean13`';
        }

        return $column;
    }

    public function getReferenceColumnForCombination()
    {
        $column = 'pa.`reference`';
        if ($this->find_combinations_by == 'combination_id') {
            $column = 'pa.`id_product_attribute`';
        } elseif ($this->find_combinations_by == 'combination_ean') {
            $column = 'pa.`ean13`';
        } elseif ($this->find_combinations_by == 'combination_supplier_reference') {
            $column = 'pa.`supplier_reference`';
        } elseif ($this->find_combinations_by == 'combination_mpn') {
            $column = 'pa.`mpn`';
        } elseif ($this->find_combinations_by == 'combination_isbn') {
            $column = 'pa.`isbn`';
        }

        return $column;
    }

    public function getCsvHeader()
    {
        $file = ElegantalEasyImportTools::getRealPath($this->csv_file);

        return ElegantalEasyImportCsv::getCsvHeaderRow($file, $this->header_row, $this->is_utf8_encode);
    }

    public function getMap()
    {
        $map = ElegantalEasyImportTools::unstorable($this->map);
        $map_default = $this->entity == 'combination' ? $this->defaultMapCombinations : $this->defaultMapProducts;
        $map = array_merge($map_default, $map);

        try {
            // Check and compare old csv_header with new csv_header and update map if csv_header is changed.
            // The new map and new csv_header are not saved because we need to keep original configuration.
            if ($this->csv_header && $this->header_row > 0 && $this->map) {
                $csv_header_old = ElegantalEasyImportTools::unstorable($this->csv_header);
                $csv_header_new = $this->getCsvHeader();
                if ($csv_header_old && $csv_header_new) {
                    foreach ($map as $attr => $index) {
                        if ($index >= 0 && isset($csv_header_old[$index])) {
                            if (isset($csv_header_new[$index]) && $csv_header_old[$index] == $csv_header_new[$index]) {
                                continue; // Old header and new header matched for this map
                            }
                            $new_index = array_search($csv_header_old[$index], $csv_header_new);
                            if ($new_index !== false && $new_index >= 0) {
                                $map[$attr] = $new_index;
                            } else {
                                $map[$attr] = '-1'; // Remove mapping, because header not found.
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            // nothing
        }

        return $map;
    }

    public function getMapDefaultValues()
    {
        $map_default_values = ElegantalEasyImportTools::unstorable($this->map_default_values);
        $map_default = $this->entity == 'combination' ? $this->defaultMapCombinations : $this->defaultMapProducts;
        $map_default_values_empty = [];
        foreach ($map_default as $key => $value) {
            $map_default_values_empty[$key] = '';
        }
        $map_default_values = array_merge($map_default_values_empty, $map_default_values);

        return $map_default_values;
    }

    public function getCategoryMapKeys($map = null)
    {
        if (empty($this->category_map_keys)) {
            if (!$map) {
                $map = $this->getMap();
            }
            $this->category_map_keys = ['categories_' . $this->id_lang_default];
            foreach ($map as $attr => $index) {
                if (preg_match("/^category_([\d]+)_" . $this->id_lang_default . '$/', $attr)) {
                    $this->category_map_keys[] = $attr;
                }
            }
        }

        return $this->category_map_keys;
    }

    protected function getShopIdByName($value)
    {
        if (empty($value)) {
            return null;
        }
        if (Validate::isInt($value) && Shop::getShop($value)) {
            $id_shop = (int) $value;
        } else {
            $id_shop = Shop::getIdByName($value);
        }

        return $id_shop;
    }

    /**
     * Finds category by name, if not found creates new one and returns ID
     *
     * @param array|string $name
     * @param int $id_parent_category
     *
     * @return int
     */
    protected function getCategoryIdByName($names_lang, $id_parent_category = null)
    {
        $id_category = null;

        if (!is_array($names_lang)) {
            $names_lang = [$this->id_lang_default => $names_lang];
        }
        if (!isset($names_lang[$this->id_lang_default])) {
            $names_lang[$this->id_lang_default] = reset($names_lang);
        }

        foreach ($names_lang as &$cat_name) {
            $cat_name = html_entity_decode($cat_name);
            $cat_name = Tools::substr(preg_replace('/[<>;=#{}]*/', '', $cat_name), 0, 128);
            $cat_name = trim($cat_name);
        }

        $name = $names_lang[$this->id_lang_default];
        if (empty($name)) {
            return null;
        }

        $original_name = $name;
        $name = $this->getDictionaryValue('category', $name);

        $original_name_l = Tools::strtolower($original_name);
        if (isset($this->column_value_dictionary['category'][$original_name_l]) && array_key_exists($original_name_l, $this->column_value_dictionary['category']) && empty($name)) {
            throw new Exception('Products of category ' . $original_name . ' are skipped.');
        }
        if (empty($name)) {
            return null;
        }

        if (!$this->rootCategory) {
            $this->rootCategory = Category::getRootCategory();
        }
        $rootCategory = $this->rootCategory;

        if ($name == $rootCategory->name && (!$id_parent_category || $id_parent_category == $rootCategory->id)) {
            return $rootCategory->id;
        }

        // If ID is given instead of name
        if (Validate::isInt($name)) {
            $category_id_from_name = Db::getInstance()->getValue('SELECT c.`id_category` FROM `' . _DB_PREFIX_ . 'category` c INNER JOIN `' . _DB_PREFIX_ . 'category_lang` cl ON (cl.`id_category` = c.`id_category`) INNER JOIN `' . _DB_PREFIX_ . 'category_shop` csh ON (csh.`id_category` = c.`id_category`) WHERE c.`id_category` = ' . (int) $name . ' AND cl.`id_lang` = ' . (int) $this->id_lang_default . ' AND cl.`id_shop` = ' . (int) $this->context->shop->id . ' AND csh.`id_shop` = ' . (int) $this->context->shop->id);
            if ($category_id_from_name) {
                $categotyObj = new Category($category_id_from_name, $this->id_lang_default, $this->context->shop->id);
                if (Validate::isLoadedObject($categotyObj)) {
                    $id_category = $categotyObj->id;
                }
            }

            return $id_category;
        }

        $sql = 'SELECT c.`id_category`
            FROM `' . _DB_PREFIX_ . 'category` c
            INNER JOIN `' . _DB_PREFIX_ . 'category_shop` csh ON (csh.`id_category` = c.`id_category` AND csh.`id_shop` = ' . (int) $this->context->shop->id . ')
            LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` cl ON c.`id_category` = cl.`id_category` AND cl.id_shop = ' . (int) $this->context->shop->id . "
            WHERE cl.`name` = '" . pSQL(Tools::strtolower($name)) . "' ";
        if (!is_null($id_parent_category) && $id_parent_category >= 0) {
            $sql .= 'AND c.`id_parent` = ' . (int) $id_parent_category . ' ';
        }
        $sql .= 'GROUP BY c.`id_category` ORDER BY c.`level_depth` ASC, csh.`position` ASC';
        $id_category = (int) Db::getInstance()->getValue($sql, false);

        if (!$id_category) {
            if (!$id_parent_category) {
                $id_parent_category = $rootCategory->id;
            }
            $categoryObj = new Category();
            $categoryObj->id_parent = $id_parent_category;
            $categoryObj->name = [];
            $categoryObj->link_rewrite = [];
            foreach ($this->id_all_langs as $id_lang) {
                $category_name = (isset($names_lang[$id_lang]) && $names_lang[$id_lang]) ? $names_lang[$id_lang] : $name;
                if (Validate::isCatalogName($category_name)) {
                    $categoryObj->name[$id_lang] = $category_name;
                }
                $category_link_rewrite = Tools::link_rewrite(Tools::substr($categoryObj->name[$id_lang], 0, 128));
                if (Validate::isLinkRewrite($category_link_rewrite)) {
                    $categoryObj->link_rewrite[$id_lang] = $category_link_rewrite;
                }
            }
            $categoryObj->doNotRegenerateNTree = false;
            if (isset($categoryObj->name[$this->id_lang_default]) && $categoryObj->name[$this->id_lang_default] && isset($categoryObj->link_rewrite[$this->id_lang_default]) && $categoryObj->link_rewrite[$this->id_lang_default]) {
                if ($categoryObj->add()) {
                    $id_category = $categoryObj->id;
                    // Category::regenerateEntireNtree(); doNotRegenerateNTree = false does this job.
                }
            }
        }

        return $id_category;
    }

    /**
     * Finds manufacturer by name, if not found creates new one and returns ID
     *
     * @param string $name
     *
     * @return int
     */
    protected function getManufacturerIdByName($name)
    {
        $id_manufacturer = null;

        if (empty($name)) {
            return null;
        }

        $name = html_entity_decode($name);
        $name = Tools::substr(preg_replace('/[<>;=#{}]*/', '', $name), 0, 64);
        $name = trim($name);

        if (preg_match("/^([\d\s]+\|)(.+)/", $name, $match)) {
            // If brand is given in format as 85|Brand, remove ID part
            $name = $match[2];
        }

        if (empty($this->manufacturers)) {
            $this->manufacturers = Manufacturer::getManufacturers(false, $this->id_lang_default, false, false, false, true);
        }

        foreach ($this->manufacturers as $m) {
            if ((Validate::isInt($name) && $m['id_manufacturer'] == $name) || Tools::strtolower($m['name']) == Tools::strtolower($name)) {
                $id_manufacturer = $m['id_manufacturer'];
                break;
            }
        }

        if (!$id_manufacturer) {
            $manufacturer = new Manufacturer();
            $manufacturer->name = $name;
            $manufacturer->active = 1;
            if ($manufacturer->add()) {
                $id_manufacturer = $manufacturer->id;
                $this->manufacturers[] = ['id_manufacturer' => $id_manufacturer, 'name' => $name];
            }
        }

        return $id_manufacturer;
    }

    /**
     * Finds supplier by name, if not found creates new one and returns ID
     *
     * @param string $name
     *
     * @return int
     */
    protected function getSupplierIdByName($name)
    {
        $id_supplier = null;

        if (empty($name)) {
            return null;
        }

        if (empty($this->suppliers)) {
            $this->suppliers = Supplier::getSuppliers(false, $this->id_lang_default, false, false, false, true);
        }

        foreach ($this->suppliers as $s) {
            if ((Validate::isInt($name) && $s['id_supplier'] == $name) || Tools::strtolower($s['name']) == Tools::strtolower($name)) {
                $id_supplier = $s['id_supplier'];
                break;
            }
        }

        if (!$id_supplier) {
            $supplier = new Supplier();
            $supplier->name = $name;
            $supplier->active = 1;
            if ($supplier->add()) {
                $id_supplier = $supplier->id;
                $this->suppliers[] = ['id_supplier' => $id_supplier, 'name' => $name];
            }
        }

        return $id_supplier;
    }

    protected function createProductSuppliers($product, $value, $supplier_references, $supplier_prices)
    {
        $id_default_supplier = false;
        $default_supplier_reference = '';
        $suppliers = explode($this->multiple_value_separator, $value);
        $suppliers = array_unique($suppliers);
        $suppliers = array_map('trim', $suppliers);
        if ($supplier_references) {
            $supplier_references = array_unique(explode($this->multiple_value_separator, $supplier_references));
            $supplier_references = array_map('trim', $supplier_references);
        }
        if ($supplier_prices) {
            $supplier_prices = array_unique(explode($this->multiple_value_separator, $supplier_prices));
            $supplier_prices = array_map('trim', $supplier_prices);
        }

        $product_suppliers = ProductSupplier::getSupplierCollection($product->id);

        foreach ($suppliers as $key => $supplier_name) {
            $id_supplier = $this->getSupplierIdByName($supplier_name);
            if (!$id_supplier) {
                continue;
            }

            // Get first supplier as default supplier. Will be used if product has no default supplier.
            if (!$id_default_supplier) {
                $id_default_supplier = $id_supplier;
            }

            // Check if supplier is already associated
            $already_accociated = false;
            foreach ($product_suppliers as $product_supplier) {
                if ($product_supplier->id_supplier == $id_supplier) {
                    $already_accociated = true;
                    if (isset($supplier_references[$key]) && $supplier_references[$key]) {
                        $product_supplier->product_supplier_reference = pSQL($supplier_references[$key]);
                    }
                    if (isset($supplier_prices[$key]) && $supplier_prices[$key]) {
                        $product_supplier->product_supplier_price_te = (float) $this->extractPriceInDefaultCurrency($supplier_prices[$key]);
                    }
                    if ($product_supplier->id_supplier == $id_default_supplier) {
                        $default_supplier_reference = $product_supplier->product_supplier_reference;
                    }
                    $product_supplier->update();
                    break;
                }
            }
            if (!$already_accociated) {
                $productSupplier = new ProductSupplier();
                $productSupplier->id_product = $product->id;
                $productSupplier->id_product_attribute = 0;
                $productSupplier->id_supplier = $id_supplier;
                $productSupplier->id_currency = (int) Configuration::get('PS_CURRENCY_DEFAULT');
                if (isset($supplier_references[$key]) && $supplier_references[$key]) {
                    $productSupplier->product_supplier_reference = pSQL($supplier_references[$key]);
                }
                if (isset($supplier_prices[$key]) && $supplier_prices[$key]) {
                    $productSupplier->product_supplier_price_te = (float) $this->extractPriceInDefaultCurrency($supplier_prices[$key]);
                }
                if ($productSupplier->id_supplier == $id_default_supplier) {
                    $default_supplier_reference = $productSupplier->product_supplier_reference;
                }
                $productSupplier->save();

                $attributes = $product->getAttributesResume($this->context->language->id);
                if ($attributes && is_array($attributes)) {
                    foreach ($attributes as $attribute) {
                        if ((int) $attribute['id_product_attribute'] > 0) {
                            // Check if already associated
                            $already_accociated_comb = Db::getInstance()->getValue('SELECT `id_product_supplier` FROM `' . _DB_PREFIX_ . 'product_supplier` WHERE `id_product` = ' . (int) $product->id . ' AND `id_product_attribute` = ' . (int) $attribute['id_product_attribute'] . ' AND `id_supplier` = ' . (int) $id_supplier);
                            if (!$already_accociated_comb) {
                                $productSupplier = new ProductSupplier();
                                $productSupplier->id_product = (int) $product->id;
                                $productSupplier->id_product_attribute = (int) $attribute['id_product_attribute'];
                                $productSupplier->id_supplier = (int) $id_supplier;
                                $productSupplier->id_currency = (int) Configuration::get('PS_CURRENCY_DEFAULT');
                                $productSupplier->save();
                            }
                        }
                    }
                }
            }
        }
        if (!$product->id_supplier && $id_default_supplier) {
            $product->id_supplier = $id_default_supplier;
            $product->supplier_reference = $default_supplier_reference;
            $product->update();
        }
    }

    protected function createProductSpecificPrice($id_product, $discount_amount, $is_percentage, $discount_tax_included = 1, $discount_from = '0000-00-00 00:00:00', $discount_to = '0000-00-00 00:00:00', $discount_base_price = '', $discount_starting_unit = 1, $discount_customer_group = '', $discount_customer_id = 0, $discount_country = '', $discount_currency = '', $id_product_attribute = 0, $shop_ids = null)
    {
        if (preg_match('/([0-9]+\.{0,1}[0-9]*)/', $discount_amount, $match)) {
            $discount_amount = $match[0];
        }
        $discount_amount = (float) $discount_amount;

        if ($is_percentage && $discount_amount > 0 && $discount_amount < 1) {
            $discount_amount *= 100;
        }

        if ($discount_base_price === '' && (!$discount_amount || empty($discount_amount) || $discount_amount === 0 || $discount_amount === 0.00)) {
            return;
        }
        if ($discount_starting_unit === 0 || $discount_starting_unit === '0') {
            return;
        }

        $discount_starting_unit = (int) $discount_starting_unit;
        if ($discount_starting_unit < 1) {
            $discount_starting_unit = 1;
        }

        if ($discount_customer_group && !Validate::isInt($discount_customer_group)) {
            $group = Group::searchByName($discount_customer_group);
            if ($group && $group['id_group']) {
                $discount_customer_group = $group['id_group'];
            }
        }
        if ($discount_customer_id) {
            $customer = new Customer($discount_customer_id);
            if (!$customer || !Validate::isLoadedObject($customer)) {
                $discount_customer_id = 0;
            }
        }
        if ($discount_country && !Validate::isInt($discount_country)) {
            $country_id = Country::getIdByName(null, $discount_country);
            if ($country_id) {
                $discount_country = $country_id;
            } else {
                $discount_country = Country::getByIso($discount_country);
            }
        }
        if ($discount_currency && !Validate::isInt($discount_currency)) {
            $discount_currency = Currency::getIdByIsoCode($discount_currency);
        }
        if ($discount_base_price !== '' && !is_null($discount_base_price)) {
            $discount_base_price = (float) $this->extractPriceInDefaultCurrency($discount_base_price);
        } else {
            $discount_base_price = '-1';
        }

        foreach ($shop_ids as $id_shop) {
            // "Delete discount that was created by this module only" feature was implemented in elegantalautopricepack module with dates 2017-01-01 01:01:01 and 2071-01-01 01:01:01
            // It is not needed here so far but may be needed in future.
            $id_specific_price = SpecificPrice::exists((int) $id_product, (int) $id_product_attribute, $id_shop, (int) $discount_customer_group, (int) $discount_country, (int) $discount_currency, (int) $discount_customer_id, $discount_starting_unit, $discount_from, $discount_to, false);
            $specificPrice = new SpecificPrice($id_specific_price);
            $specificPrice->id_product = (int) $id_product;
            $specificPrice->id_product_attribute = (int) $id_product_attribute;
            $specificPrice->id_shop = $id_shop;
            $specificPrice->id_currency = (int) $discount_currency;
            $specificPrice->id_country = (int) $discount_country;
            $specificPrice->id_group = (int) $discount_customer_group;
            $specificPrice->id_customer = (int) $discount_customer_id;
            $specificPrice->from_quantity = $discount_starting_unit;
            $specificPrice->price = $discount_base_price;
            $specificPrice->from = $discount_from;
            $specificPrice->to = $discount_to;
            $specificPrice->reduction = (float) ($is_percentage ? round($discount_amount / 100, 8) : round($discount_amount, 8));
            $specificPrice->reduction_tax = $discount_tax_included ? 1 : 0;
            $specificPrice->reduction_type = $is_percentage ? 'percentage' : 'amount';
            if (!$specificPrice->save()) {
                throw new Exception('Discount is invalid: ' . Db::getInstance()->getMsgError());
            }
        }
    }

    protected function createProductImages($product, $csv_images, $default_image, $captions, $convert_to, $is_compare_image_using_imagick)
    {
        $id_shop = $this->context->shop->id;
        $base_url_images = $this->base_url_images;

        // Base URL may contain username and password in this format: @@username:password@@
        $username = null;
        $password = null;
        if (preg_match("/^@@([\w\W]+):([\w\W]+)@@/", $base_url_images, $match)) {
            $username = $match[1];
            $password = $match[2];
            $base_url_images = str_replace($match[0], '', $base_url_images);
        }

        $image_ids = [];

        if ($csv_images && is_array($csv_images) && count($csv_images) > 0) {
            // Prepare image hash array from existing images of the product
            $images_hashes = [];
            $coverImageCurrent = null;
            $images = Image::getImages($this->context->language->id, $product->id);
            foreach ($images as $product_image) {
                $imageObj = new Image($product_image['id_image']);
                if (Validate::isLoadedObject($imageObj)) {
                    if ($imageObj->cover) {
                        $coverImageCurrent = $imageObj;
                    }
                    $image_file = _PS_PROD_IMG_DIR_ . $imageObj->getExistingImgPath() . '.' . $imageObj->image_format;
                    if (is_file($image_file)) {
                        $hash = md5_file($image_file);
                        $images_hashes[$hash] = ['image_id' => $imageObj->id, 'image_file' => $image_file];
                    }
                }
            }

            foreach ($csv_images as $key => $file) {
                $url = $file;
                if (strlen($url) < 4 || in_array(substr($url, -4), ['.pdf', '.mp4'])) {
                    continue;
                }
                // If number is given, take existing image of the product by this position
                if (Validate::isInt($url) && $this->entity == 'combination') {
                    foreach ($images as $img) {
                        if (isset($img['id_image'], $img['position']) && $img['position'] == $url && !in_array($img['id_image'], $image_ids)) {
                            $image_ids[] = $img['id_image'];
                        }
                    }
                    continue;
                }
                if ($base_url_images) {
                    if (strpos($base_url_images, '%s') !== false) {
                        $url = str_replace('%s', $file, $base_url_images);
                    } elseif (!ElegantalEasyImportTools::isValidUrl($url)) {
                        $url = $base_url_images . $file;
                    }
                }

                $image = new Image();
                $image->id_product = $product->id;
                $image->position = Image::getHighestPosition($product->id) + 1;
                $image->cover = false; // We adjust cover at the end because it is giving "Duplicate entry for key id_product_cover" error
                $id_cover_image = null;

                foreach ($this->id_all_langs as $id_lang) {
                    if ($captions && isset($captions[$id_lang][$key]) && $captions[$id_lang][$key]) {
                        $image->legend[$id_lang] = Tools::substr(preg_replace('/[<>;=#{}]*/', '', $captions[$id_lang][$key]), 0, 128);
                    }
                }

                $image_add = $image->add();
                if (!$image_add) {
                    Db::getInstance()->execute('DELETE FROM ' . _DB_PREFIX_ . 'image_shop WHERE id_image NOT IN (SELECT id_image FROM ' . _DB_PREFIX_ . 'image)');
                    $image_add = $image->add();
                }
                if ($image_add) {
                    $image_file = _PS_PROD_IMG_DIR_ . $image->getExistingImgPath() . '.' . $image->image_format;
                    $image_dir = dirname($image_file);

                    if ($file == $default_image) {
                        $id_cover_image = $image->id;
                    }

                    if (ElegantalEasyImportTools::copyImg($product->id, $image, $url, $username, $password, $convert_to) && is_file($image_file)) {
                        // Delete image if it is duplicate and the same image already exists
                        $id_image_existing = false;

                        $hash = md5_file($image_file);

                        if (isset($images_hashes[$hash])) {
                            $id_image_existing = $images_hashes[$hash]['image_id'];
                        } elseif ($is_compare_image_using_imagick) {
                            foreach ($images_hashes as $existing_img) {
                                if (ElegantalEasyImportTools::checkIfImageIsDuplicateByImagick($image_file, $existing_img['image_file'])) {
                                    $id_image_existing = $existing_img['image_id'];
                                    break;
                                }
                            }
                        }

                        // If this is default_image and product has cover image, delete that cover image if it is not the same image
                        if ($id_cover_image && $id_cover_image == $image->id && $coverImageCurrent) {
                            $cover_image_file = _PS_PROD_IMG_DIR_ . $coverImageCurrent->getExistingImgPath() . '.' . $coverImageCurrent->image_format;
                            if ($cover_image_file && md5_file($cover_image_file) != $hash) {
                                $coverImageCurrent->delete();
                                ElegantalEasyImportTools::deleteFolderIfEmpty(dirname($cover_image_file));
                            }
                        }

                        if ($id_image_existing) {
                            if (!in_array($id_image_existing, $image_ids)) {
                                $image_ids[] = $id_image_existing;
                            }

                            // If current image was set as cover, update cover image ID with existing image's ID
                            if ($id_cover_image == $image->id) {
                                $id_cover_image = $id_image_existing;
                            }

                            // Delete new image because it is duplicate
                            $image->delete();

                            ElegantalEasyImportTools::deleteFolderIfEmpty($image_dir);

                            // If existing image is not assigned to the current shop, assign it.
                            if (!Db::getInstance()->getValue('SELECT `id_image` FROM `' . _DB_PREFIX_ . 'image_shop` WHERE `id_image` = ' . (int) $id_image_existing . ' AND `id_product` = ' . (int) $product->id . ' AND `id_shop` = ' . (int) $id_shop)) {
                                $is_cover_exist = (bool) Db::getInstance()->getValue('SELECT `id_image` FROM `' . _DB_PREFIX_ . 'image_shop` WHERE `cover` = 1 AND `id_product` = ' . (int) $product->id . ' AND `id_shop` = ' . (int) $id_shop);
                                Db::getInstance()->execute('INSERT INTO `' . _DB_PREFIX_ . 'image_shop` (`id_product`, `id_image`, `id_shop`, `cover`) VALUES (' . (int) $product->id . ', ' . (int) $id_image_existing . ', ' . (int) $id_shop . ', ' . ($is_cover_exist ? 'NULL' : 1) . ')');
                            }
                        } else {
                            $images_hashes[$hash] = ['image_id' => $image->id, 'image_file' => $image_file];
                            if (!in_array($image->id, $image_ids)) {
                                $image_ids[] = $image->id;
                            }
                        }

                        // Update cover image
                        if ($id_cover_image) {
                            Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'image` SET `cover` = NULL WHERE `id_product` = ' . (int) $product->id);
                            Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'image_shop` SET `cover` = NULL WHERE `id_product` = ' . (int) $product->id);
                            Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'image` SET `cover` = 1 WHERE `id_product` = ' . (int) $product->id . ' AND `id_image` = ' . (int) $id_cover_image);
                            Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'image_shop` SET `cover` = 1 WHERE `id_product` = ' . (int) $product->id . ' AND `id_image` = ' . (int) $id_cover_image);
                        }
                    } else {
                        $image->delete();
                        ElegantalEasyImportTools::deleteFolderIfEmpty($image_dir);
                        $this->addError('Image not found: ' . $url, $product);
                    }
                } else {
                    $this->addError('Failed to create image object. ' . Db::getInstance()->getMsgError(), $product);
                }
            }
        }

        // Fix cover on ps_image table
        $has_cover = Db::getInstance()->getRow('SELECT * FROM `' . _DB_PREFIX_ . 'image` WHERE `id_product` = ' . (int) $product->id . ' AND `cover`= 1');
        if (!$has_cover) {
            $first_image = Db::getInstance()->getRow('SELECT * FROM `' . _DB_PREFIX_ . 'image` WHERE `id_product` = ' . (int) $product->id . ' ORDER BY `id_image` ASC');
            if ($first_image && $first_image['id_image']) {
                Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'image` SET `cover` = 1 WHERE `id_product` = ' . (int) $product->id . ' AND `id_image` = ' . (int) $first_image['id_image']);
            }
        }
        // Fix cover on ps_image_shop table
        $shop_groups = Shop::getTree();
        if (is_array($shop_groups) && $shop_groups) {
            foreach ($shop_groups as $shop_group) {
                if (is_array($shop_group['shops']) && $shop_group['shops']) {
                    foreach ($shop_group['shops'] as $shop) {
                        $has_cover = Db::getInstance()->getRow('SELECT * FROM `' . _DB_PREFIX_ . 'image_shop` WHERE `id_product` = ' . (int) $product->id . ' AND `id_shop` = ' . (int) $shop['id_shop'] . ' AND `cover`= 1');
                        if (!$has_cover) {
                            $first_image = Db::getInstance()->getRow('SELECT * FROM `' . _DB_PREFIX_ . 'image_shop` WHERE `id_product` = ' . (int) $product->id . ' AND `id_shop` = ' . (int) $shop['id_shop'] . ' ORDER BY `id_image` ASC');
                            if ($first_image && $first_image['id_image']) {
                                Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'image_shop` SET `cover` = 1 WHERE `id_product` = ' . (int) $product->id . ' AND `id_shop` = ' . (int) $shop['id_shop'] . ' AND `id_image` = ' . (int) $first_image['id_image']);
                            }
                        }
                    }
                }
            }
        }

        return $image_ids;
    }

    protected function createProductFeatures($id_product, $features, $is_allow_multiple_values_for_the_same_product_feature)
    {
        if (!$id_product || !$features || !isset($features[$this->id_lang_default]) || !$features[$this->id_lang_default]) {
            return;
        }

        // Prepare features array
        $features_arr_lang = [];
        foreach ($features as $id_lang => $features_arr) {
            foreach ($features_arr as $feature) {
                if (empty($feature)) {
                    continue;
                }
                // $feature_parts = explode(':', $feature);
                // We cannot use explode here because feature value may contain : as well
                $feature_parts = str_getcsv($feature, ':', '"');
                $feature_name = isset($feature_parts[0]) ? trim($feature_parts[0]) : '';
                $feature_value = isset($feature_parts[1]) ? trim($feature_parts[1]) : '';
                $position = (isset($feature_parts[2]) && $feature_parts[2] !== '') ? (int) $feature_parts[2] - 1 : false;
                $is_custom = isset($feature_parts[3]) ? (bool) $feature_parts[3] : false;

                $feature_name = html_entity_decode($feature_name);
                $feature_name = str_replace('=', '-', $feature_name);
                $feature_name = str_replace(['=', '[', ']', '<', '>', '{', '}'], '', $feature_name);

                $feature_value = html_entity_decode($feature_value);
                $feature_value = str_replace('=', '-', $feature_value);
                $feature_value = str_replace(['=', '[', ']', '<', '>', '{', '}'], '', $feature_value);

                $feature_name = $this->getDictionaryValue('feature_name', $feature_name);
                $feature_value = $this->getDictionaryValue('feature_value', $feature_value);

                if (empty($feature_name) || (empty($feature_value) && $feature_value !== '0')) {
                    continue;
                }

                $feature_name_size = isset(Feature::$definition['fields']['name']['size']) ? (int) Feature::$definition['fields']['name']['size'] : 12;
                $feature_value_size = isset(FeatureValue::$definition['fields']['value']['size']) ? (int) FeatureValue::$definition['fields']['value']['size'] : 255;

                $features_arr_lang[$id_lang][] = [
                    'feature_name' => Tools::substr($feature_name, 0, $feature_name_size),
                    'feature_value' => Tools::substr($feature_value, 0, $feature_value_size),
                    'position' => $position,
                    'is_custom' => $is_custom,
                ];
            }
        }

        if (!$features_arr_lang || !isset($features_arr_lang[$this->id_lang_default]) || !is_array($features_arr_lang[$this->id_lang_default]) || !$features_arr_lang[$this->id_lang_default]) {
            return;
        }

        // Get existing features of the product
        $product_features = Product::getFeaturesStatic($id_product);

        // Create features and values
        foreach ($features_arr_lang[$this->id_lang_default] as $key => $feature) {
            $id_feature = null;
            $id_feature_value = null;
            // $id_feature = (int) Feature::addFeatureImport($feature_name, $position);
            // We cannot use this function because it does not save translations
            $feature_exists = Db::getInstance()->getRow('SELECT `id_feature` FROM ' . _DB_PREFIX_ . "feature_lang WHERE `name` = '" . pSQL($feature['feature_name']) . "' AND `id_lang` = " . (int) $this->id_lang_default . ' GROUP BY `id_feature`');
            if (empty($feature_exists)) {
                // Feature does not exist, so create it.
                $featureObj = new Feature();
                $featureObj->name[$this->id_lang_default] = $feature['feature_name'];
                foreach ($this->id_other_langs as $id_lang) {
                    if (isset($features_arr_lang[$id_lang][$key]['feature_name']) && $features_arr_lang[$id_lang][$key]['feature_name']) {
                        $featureObj->name[$id_lang] = $features_arr_lang[$id_lang][$key]['feature_name'];
                    } else {
                        $featureObj->name[$id_lang] = $feature['feature_name'];
                    }
                }
                $featureObj->position = $position ? (int) $position : Feature::getHigherPosition() + 1;
                $featureObj->add();
                $id_feature = (int) $featureObj->id;
            } elseif (isset($feature_exists['id_feature']) && $feature_exists['id_feature']) {
                $featureObj = new Feature((int) $feature_exists['id_feature']);
                if (Validate::isLoadedObject($featureObj)) {
                    if (is_numeric($position)) {
                        $featureObj->position = (int) $position;
                        $featureObj->update();
                    }
                    $id_feature = (int) $featureObj->id;
                }
            }
            if (!$id_feature) {
                continue;
            }
            // $id_feature_value = (int) FeatureValue::addFeatureValueImport($id_feature, $feature_value, $id_product, $id_lang, $is_custom);
            // We cannot use this function because it does not save translations
            $id_feature_value = Db::getInstance()->getValue('
				SELECT fp.`id_feature_value`
				FROM ' . _DB_PREFIX_ . 'feature_product fp
				INNER JOIN ' . _DB_PREFIX_ . 'feature_value fv USING (`id_feature_value`)
				WHERE fp.`id_feature` = ' . (int) $id_feature . '
				AND fv.`custom` = ' . (int) $is_custom . '
				AND fp.`id_product` = ' . (int) $id_product);

            if ($is_custom && $id_feature_value && $this->id_lang_default) {
                Db::getInstance()->execute('
				UPDATE ' . _DB_PREFIX_ . "feature_value_lang
				SET `value` = '" . pSQL($feature['feature_value']) . "'
				WHERE `id_feature_value` = " . (int) $id_feature_value . "
				AND `value` != '" . pSQL($feature['feature_value']) . "'
				AND `id_lang` = " . (int) $this->id_lang_default);
            }
            if (!$is_custom) {
                $id_feature_value = Db::getInstance()->getValue('
                    SELECT fv.`id_feature_value`
                    FROM ' . _DB_PREFIX_ . 'feature_value fv
                    LEFT JOIN ' . _DB_PREFIX_ . 'feature_value_lang fvl ON (fvl.`id_feature_value` = fv.`id_feature_value` AND fvl.`id_lang` = ' . (int) $this->id_lang_default . ")
                    WHERE `value` = '" . pSQL($feature['feature_value']) . "'
                    AND fv.`id_feature` = " . (int) $id_feature . '
                    AND fv.`custom` = 0
                    GROUP BY fv.`id_feature_value`');
            }
            if (!$id_feature_value) {
                $feature_value = new FeatureValue();
                $feature_value->id_feature = (int) $id_feature;
                $feature_value->custom = (bool) $is_custom;
                $feature_value->value[$this->id_lang_default] = $feature['feature_value'];
                foreach ($this->id_other_langs as $id_lang) {
                    if (isset($features_arr_lang[$id_lang][$key]['feature_value'])) {
                        $feature_value->value[$id_lang] = $features_arr_lang[$id_lang][$key]['feature_value'];
                    } else {
                        $feature_value->value[$id_lang] = $feature['feature_value'];
                    }
                }
                $feature_value->add();
                $id_feature_value = (int) $feature_value->id;
            }
            if (!$id_feature) {
                continue;
            }
            // Check if the product has this feature
            $is_product_has_this_feature = false;
            if ($product_features && is_array($product_features) && !$is_allow_multiple_values_for_the_same_product_feature) {
                foreach ($product_features as $product_feature) {
                    if ($product_feature['id_feature'] == $id_feature) {
                        if (isset($product_feature['custom']) && $product_feature['custom']) {
                            Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'feature_value_lang` WHERE `id_feature_value` = ' . (int) $product_feature['id_feature_value']);
                            Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'feature_value` WHERE `id_feature_value` = ' . (int) $product_feature['id_feature_value']);
                        }
                        Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'feature_product` WHERE `id_product` = ' . (int) $id_product . ' AND `id_feature` = ' . $id_feature);
                        $is_product_has_this_feature = true;
                        break;
                    }
                }
            }

            Product::addFeatureProductImport($id_product, $id_feature, $id_feature_value);

            if (!$is_product_has_this_feature) {
                $product_features[] = [
                    'id_feature' => $id_feature,
                    'id_product' => $id_product,
                    'id_feature_value' => $id_feature_value,
                    'custom' => 0,
                ];
            }
        }
        Feature::cleanPositions();
    }

    protected function createProductTags($product, $value, $id_lang)
    {
        if (empty($value)) {
            return;
        }
        $tags = explode($this->multiple_value_separator, $value);
        $value = implode(',', $tags);
        if (Validate::isTagsList($value)) {
            // Delete old tags. Similar function to Tag::deleteTagsForProduct but need to add id_lang
            $tagsRemoved = Db::getInstance()->executeS('SELECT id_tag FROM ' . _DB_PREFIX_ . 'product_tag WHERE id_product = ' . (int) $product->id . ' AND id_lang = ' . (int) $id_lang);
            Db::getInstance()->delete('product_tag', 'id_product = ' . (int) $product->id . ' AND id_lang = ' . (int) $id_lang);
            Db::getInstance()->delete('tag', 'NOT EXISTS (SELECT 1 FROM ' . _DB_PREFIX_ . 'product_tag WHERE ' . _DB_PREFIX_ . 'product_tag.id_tag = ' . _DB_PREFIX_ . 'tag.id_tag)');
            $tagList = [];
            foreach ($tagsRemoved as $tagRemoved) {
                $tagList[] = $tagRemoved['id_tag'];
            }
            if ($tagList != []) {
                Tag::updateTagCount($tagList);
            }
            // Add tags to the product
            Tag::addTags($id_lang, $product->id, $value, ',');
        } else {
            $this->addError('Tags list is not valid.', $product);
        }
    }

    protected function createProductAccessories($product, $value)
    {
        if (!$product->id || !$value) {
            return;
        }

        // Get existing accessories of the product
        $existing_accessory_ids = [];
        $product_accessories = Product::getAccessoriesLight($this->context->language->id, $product->id);
        if ($product_accessories) {
            foreach ($product_accessories as $product_accessory) {
                if (!in_array($product_accessory['id_product'], $existing_accessory_ids)) {
                    $existing_accessory_ids[] = $product_accessory['id_product'];
                }
            }
        }

        $new_accessory_ids = [];
        $file_accessories = explode($this->multiple_value_separator, $value);
        $file_accessories = array_map('trim', $file_accessories);
        $file_accessories = array_unique($file_accessories);
        foreach ($file_accessories as $file_accessory) {
            if (empty($file_accessory)) {
                continue;
            }
            $file_accessory_id = null;
            if ($this->find_products_by == 'reference' || !Validate::isInt($file_accessory)) {
                // Find product id by reference
                $sql = 'SELECT * FROM `' . _DB_PREFIX_ . "product` WHERE `reference` = '" . pSQL($file_accessory) . "'";
                $row = Db::getInstance()->getRow($sql);
                if ($row && isset($row['id_product']) && $row['id_product']) {
                    $file_accessory_id = (int) $row['id_product'];
                }
            } else {
                $file_accessory_id = (int) $file_accessory;
            }
            if ($file_accessory_id && !in_array($file_accessory_id, $existing_accessory_ids) && !in_array($file_accessory_id, $new_accessory_ids)) {
                $new_accessory_ids[] = $file_accessory_id;
            }
        }
        $product->changeAccessories($new_accessory_ids);
    }

    protected function createProductAttachments($product, $attachments_str, $attachment_names, $attachment_descriptions)
    {
        $attachments = array_map('trim', explode($this->multiple_value_separator, $attachments_str));
        if (!$attachments || !is_array($attachments) || count($attachments) < 1) {
            return;
        }

        foreach ($attachments as $key => $attachment_file) {
            if (empty($attachment_file)) {
                continue;
            }
            if (Validate::isInt($attachment_file)) {
                $attachment = new Attachment($attachment_file);
                if (Validate::isLoadedObject($attachment)) {
                    // Check if already attached
                    $already_attached = (int) Db::getInstance()->getValue('SELECT `id_attachment` FROM `' . _DB_PREFIX_ . 'product_attachment` WHERE `id_attachment` = ' . (int) $attachment->id . ' AND `id_product` = ' . (int) $product->id);
                    if (!$already_attached) {
                        $attachment->attachProduct($product->id);
                    }
                    $product->cache_has_attachments = 1;
                }
                continue;
            }

            $tmp_file = null;
            $filename = basename($attachment_file);
            $extension = Tools::strrpos($filename, '.') !== false ? '.' . Tools::substr($filename, Tools::strrpos($filename, '.') + 1, 5) : '';
            $filename = Validate::isGenericName($filename) ? $filename : Tools::passwdGen(8) . $extension;

            // Download attachment to tmp file
            if (ElegantalEasyImportTools::isValidUrl($attachment_file)) {
                try {
                    $tmp_file = ElegantalEasyImportTools::downloadFileFromUrl($attachment_file, null, null, null, 'GET', '', 100);
                    $parced_url = parse_url($attachment_file);
                    $parced_params = [];
                    if (isset($parced_url['query'])) {
                        parse_str($parced_url['query'], $parced_params);
                    }
                    if (isset($parced_url['host']) && Tools::strtolower($parced_url['host']) == 'drive.google.com' && isset($parced_url['path']) && preg_match("/^(\/file\/d\/)(.+?(?=\/))/", $parced_url['path'])) {
                        $filename = basename($tmp_file);
                    } elseif (isset($parced_params['file_name']) && $parced_params['file_name']) {
                        $filename = $parced_params['file_name'];
                    }
                    $attachment_file = $tmp_file;
                } catch (Exception $e) {
                    $this->addError('Failed to download attachment: ' . $attachment_file . ' ' . $e->getMessage(), $product);
                    @unlink($tmp_file);
                    continue;
                }
            } elseif (Tools::substr($attachment_file, 0, 1) != '/') {
                $attachment_file = _PS_ROOT_DIR_ . '/' . $attachment_file;
            }

            if (!is_file($attachment_file)) {
                $this->addError('Attachment file is not found: ' . $attachment_file, $product);
                @unlink($tmp_file);
                continue;
            }

            $filesize = filesize($attachment_file);
            if (!$filesize) {
                $this->addError('Attachment file is empty: ' . $attachment_file, $product);
                @unlink($tmp_file);
                continue;
            }
            if ($filesize > (Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE') * 1024 * 1024)) {
                $this->addError('Attachment file size is too large (' . ElegantalEasyImportTools::displaySize($filesize) . '). Max allowed size is ' . ElegantalEasyImportTools::displaySize(Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE') * 1024 * 1024) . '. ' . $filename, $product);
                @unlink($tmp_file);
                continue;
            }

            // If there is a file with the same name and size, use it instead of creating a new attachment
            $sql = 'SELECT `id_attachment` FROM `' . _DB_PREFIX_ . "attachment` WHERE `file_name` = '" . pSQL($filename) . "' AND `file_size` = '" . pSQL($filesize) . "'";
            $id_attachment = Db::getInstance()->getValue($sql);
            $attachment = new Attachment($id_attachment);

            if ($id_attachment > 0 && Validate::isLoadedObject($attachment)) {
                // Check if already attached
                $already_attached = (int) Db::getInstance()->getValue('SELECT `id_attachment` FROM `' . _DB_PREFIX_ . 'product_attachment` WHERE `id_attachment` = ' . (int) $attachment->id . ' AND `id_product` = ' . (int) $product->id);
                if (!$already_attached) {
                    $attachment->attachProduct($product->id);
                }
                $product->cache_has_attachments = 1;
            } else {
                $uniqid = null;
                do {
                    $uniqid = Tools::passwdGen(32);
                } while (is_file(_PS_DOWNLOAD_DIR_ . $uniqid));
                if (!copy($attachment_file, _PS_DOWNLOAD_DIR_ . $uniqid)) {
                    $this->addError('Failed to copy attachment: ' . $attachment_file, $product);
                    @unlink($tmp_file);
                    continue;
                }

                $attachment = new Attachment();
                foreach ($this->id_all_langs as $id_lang) {
                    $attachment->name[$id_lang] = ($attachment_names && isset($attachment_names[$id_lang][$key]) && $attachment_names[$id_lang][$key]) ? Tools::substr($attachment_names[$id_lang][$key], 0, 32) : Tools::substr(rawurldecode($filename), 0, 32);
                    $attachment->description[$id_lang] = ($attachment_descriptions && isset($attachment_descriptions[$id_lang][$key]) && $attachment_descriptions[$id_lang][$key]) ? $attachment_descriptions[$id_lang][$key] : '';
                }
                $attachment->file = $uniqid;
                $attachment->mime = ElegantalEasyImportTools::getMimeType($attachment_file);
                $attachment->file_name = $filename;
                $attachment->add();
                $attachment->attachProduct($product->id);
                $product->cache_has_attachments = 1;
            }
            @unlink($tmp_file);
        }
    }

    protected function createProductCarriers($product, $value, $is_id_reference = false)
    {
        $carriers = explode($this->multiple_value_separator, $value);
        $carriers = array_unique($carriers);
        $carriers = array_map('trim', $carriers);
        if (!$carriers || !is_array($carriers)) {
            return;
        }
        if (empty($this->carriers)) {
            $this->carriers = Carrier::getCarriers($this->id_lang_default, false, false, false, null, Carrier::ALL_CARRIERS);
        }
        $carriers_ids = [];
        foreach ($carriers as $carrier) {
            if (empty($carrier)) {
                continue;
            }
            foreach ($this->carriers as $c) {
                if ($is_id_reference) {
                    if (Validate::isInt($carrier) && $carrier == $c['id_reference']) {
                        $carriers_ids[] = $c['id_reference'];
                        break;
                    }
                } else {
                    if ((Validate::isInt($carrier) && $carrier == $c['id_carrier']) || Tools::strtolower($c['name']) == Tools::strtolower($carrier)) {
                        // Before we used Validate::isInt($carrier) && $carrier == $c['id_reference']
                        // This caused issue because people can see only id_carrier, so they use id_carrier, not id_reference
                        // But we  need id_reference to assign carrier to the product
                        $carriers_ids[] = $c['id_reference'];
                        break;
                    }
                }
            }
        }
        $product->setCarriers($carriers_ids);
    }

    protected function createProductCustomizableFields($product, $uploadable_files_labels, $text_fields_labels)
    {
        $current_customization = $product->getCustomizationFieldIds();
        $files_count = 0;
        $text_count = 0;
        if (is_array($current_customization)) {
            foreach ($current_customization as $field) {
                if ($field['type'] == 1) {
                    ++$text_count;
                } else {
                    ++$files_count;
                }
            }
        }
        // Create only new fields
        $files_count = (int) $product->uploadable_files - $files_count;
        $text_count = (int) $product->text_fields - $text_count;
        if ($files_count > 0 || $text_count > 0) {
            $shop_ids = Shop::getContextListShopID();
            if ($files_count > 0) {
                $uploadable_files_labels = explode($this->multiple_value_separator, $uploadable_files_labels);
                $uploadable_files_labels = array_map('trim', $uploadable_files_labels);
                for ($i = 0; $i < $files_count; ++$i) {
                    $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'customization_field` (`id_product`, `type`, `required`)
                        VALUES (' . (int) $product->id . ', ' . (int) Product::CUSTOMIZE_FILE . ', 0)';
                    if (Db::getInstance()->execute($sql) && ($id_customization_field = (int) Db::getInstance()->Insert_ID())) {
                        $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'customization_field_lang` (`id_customization_field`, `id_lang`, `id_shop`, `name`)
                                VALUES ';
                        $values = '';
                        $label = isset($uploadable_files_labels[$i]) ? $uploadable_files_labels[$i] : '';
                        foreach ($this->id_all_langs as $id_lang) {
                            foreach ($shop_ids as $id_shop) {
                                $values .= $values ? ', ' : '';
                                $values .= '(' . (int) $id_customization_field . ', ' . (int) $id_lang . ', ' . (int) $id_shop . ", '" . pSQL($label) . "')";
                            }
                        }
                        $sql .= $values;
                        Db::getInstance()->execute($sql);
                    }
                }
            }
            if ($text_count > 0) {
                $text_fields_labels = explode($this->multiple_value_separator, $text_fields_labels);
                $text_fields_labels = array_map('trim', $text_fields_labels);
                for ($i = 0; $i < $text_count; ++$i) {
                    $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'customization_field` (`id_product`, `type`, `required`)
                        VALUES (' . (int) $product->id . ', ' . (int) Product::CUSTOMIZE_TEXTFIELD . ', 0)';
                    if (Db::getInstance()->execute($sql) && ($id_customization_field = (int) Db::getInstance()->Insert_ID())) {
                        $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'customization_field_lang` (`id_customization_field`, `id_lang`, `id_shop`, `name`)
                                VALUES ';
                        $values = '';
                        $label = isset($text_fields_labels[$i]) ? $text_fields_labels[$i] : '';
                        foreach ($this->id_all_langs as $id_lang) {
                            foreach ($shop_ids as $id_shop) {
                                $values .= $values ? ', ' : '';
                                $values .= '(' . (int) $id_customization_field . ', ' . (int) $id_lang . ', ' . (int) $id_shop . ", '" . pSQL($label) . "')";
                            }
                        }
                        $sql .= $values;
                        Db::getInstance()->execute($sql);
                    }
                }
            }
            Configuration::updateGlobalValue('PS_CUSTOMIZATION_FEATURE_ACTIVE', '1');
        }
    }

    protected function createProductPackItems($product, $value, $attr)
    {
        $items = explode($this->multiple_value_separator, $value);
        if (!$items || !is_array($items)) {
            return;
        }

        $item_ids = [];
        foreach ($items as $item) {
            $product_ref = null;
            $combination_ref = null;
            $quantity = 1;
            $item_parts = explode(':::', $item);
            if (isset($item_parts[0]) && $item_parts[0]) {
                $product_ref = $item_parts[0];
            }
            if (isset($item_parts[1]) && $item_parts[1]) {
                $combination_ref = $item_parts[1];
            }
            if (isset($item_parts[2])) {
                $quantity = (int) $item_parts[2];
            }

            if (!$product_ref) {
                continue;
            }

            $id_product = null;
            $id_product_attribute = 0;
            if ($attr == 'pack_items_refs') {
                $id_product = (int) Db::getInstance()->getValue('SELECT `id_product` FROM ' . _DB_PREFIX_ . "product WHERE `reference` = '" . pSQL($product_ref) . "'");
                if ($combination_ref) {
                    $id_product_attribute = (int) Db::getInstance()->getValue('SELECT `id_product_attribute` FROM ' . _DB_PREFIX_ . "product_attribute WHERE `reference` = '" . pSQL($combination_ref) . "'");
                }
            } else {
                $id_product = (int) Db::getInstance()->getValue('SELECT `id_product` FROM ' . _DB_PREFIX_ . 'product WHERE `id_product` = ' . (int) $product_ref);
                if ($combination_ref) {
                    $id_product_attribute = (int) Db::getInstance()->getValue('SELECT `id_product_attribute` FROM ' . _DB_PREFIX_ . 'product_attribute WHERE `id_product_attribute` = ' . (int) $combination_ref);
                }
            }
            if ($id_product) {
                if (Pack::isPack((int) $id_product)) {
                    continue;
                }
                $item_ids[] = [
                    'id_product' => $id_product,
                    'id_product_attribute' => $id_product_attribute,
                    'quantity' => $quantity,
                ];
            }
        }
        if ($item_ids) {
            Pack::deleteItems($product->id);
            $product->setDefaultAttribute(0); // Reset cache_default_attribute
            foreach ($item_ids as $item) {
                Pack::addItem((int) $product->id, (int) $item['id_product'], (int) $item['quantity'], (int) $item['id_product_attribute']);
            }
        }
    }

    protected function createFsProductVideoUrls($product, $urls)
    {
        if (!$product->id || !ElegantalEasyImportTools::isModuleInstalled('fsproductvideo')) {
            return;
        }
        $urls = explode($this->multiple_value_separator, $urls);
        if (Shop::getContext() == Shop::CONTEXT_ALL) {
            $shops = [['id_shop' => $this->context->shop->id]];
        } else {
            $shops = Shop::getShops();
        }

        // Delete old lang records
        $sql = 'DELETE l FROM `' . _DB_PREFIX_ . 'fsproductvideo_lang` l
            INNER JOIN `' . _DB_PREFIX_ . 'fsproductvideo` f ON f.`id_fsproductvideo` = l.`id_fsproductvideo`
            WHERE f.`id_product` = ' . (int) $product->id;
        Db::getInstance()->execute($sql);

        // Delete old records
        $sql = 'DELETE FROM `' . _DB_PREFIX_ . 'fsproductvideo` WHERE `id_product` = ' . (int) $product->id;
        Db::getInstance()->execute($sql);

        // Create new record
        $ids = [];
        foreach ($urls as $pos => $url) {
            $url = (Tools::substr($url, 0, 7) != 'http://' && Tools::substr($url, 0, 8) != 'https://') ? 'http://' . $url : $url;
            if (ElegantalEasyImportTools::isValidUrl($url)) {
                // Get thumbnail image as fsproductvideo module's controller
                $thumbnail = $this->fsSaveThumbnailImage($url, $product->id);
                foreach ($shops as $shop) {
                    $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'fsproductvideo` (`id_shop`, `id_product`, `active`, `position`, `thumbnail`, `date_add`, `date_upd`)
                        VALUES (' . (int) $shop['id_shop'] . ', ' . (int) $product->id . ', 1, ' . (int) ($pos + 1) . ", '" . pSQL($thumbnail) . "', '" . pSQL(date('Y-m-d H:i:s')) . "', '" . pSQL(date('Y-m-d H:i:s')) . "')";
                    if (Db::getInstance()->execute($sql)) {
                        $ids[DB::getInstance()->Insert_ID()] = $url;
                    }
                }
            }
        }
        if (!empty($ids) && is_array($ids)) {
            foreach ($ids as $id => $url) {
                foreach ($this->id_all_langs as $id_lang) {
                    $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'fsproductvideo_lang` (`id_fsproductvideo`, `id_lang`, `url`, `title`)
                        VALUES(' . (int) $id . ', ' . (int) $id_lang . ", '" . pSQL($url) . "', '" . pSQL($product->name) . "')";
                    Db::getInstance()->execute($sql);
                }
            }
        }
    }

    protected function fsSaveThumbnailImage($fspv_video_url, $fspv_id_product)
    {
        $fsproductvideo = Module::getInstanceByName('fsproductvideo');

        $imageName = '';
        if ($fsproductvideo && $fspv_video_url && $fspv_id_product) {
            $image_url = '';
            $youtube_matches = [];
            $exp = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|';
            $exp .= '(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i';
            preg_match($exp, trim($fspv_video_url), $youtube_matches);
            if (isset($youtube_matches[1]) && $youtube_matches[1]) {
                $videoType = 'youtube';
                $videoId = $youtube_matches[1];
                $image_url = 'http://img.youtube.com/vi/' . $videoId . '/sddefault.jpg';
                $image_curl_handle = curl_init($image_url);
                curl_setopt($image_curl_handle, CURLOPT_RETURNTRANSFER, true);
                curl_exec($image_curl_handle);
                $image_curl_response_http_code = curl_getinfo($image_curl_handle, CURLINFO_HTTP_CODE);
                if ($image_curl_response_http_code == 404) {
                    $image_url = 'http://img.youtube.com/vi/' . $videoId . '/hqdefault.jpg';
                }
            }
            $vimeo_matches = [];
            $exp = '/https?:\/\/(?:www\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|';
            $exp .= 'groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)(?:$|\/|\?)/';
            preg_match($exp, trim($fspv_video_url), $vimeo_matches);
            if (isset($vimeo_matches[3]) && $vimeo_matches[3]) {
                $videoType = 'vimeo';
                $videoId = $vimeo_matches[3];
                $vimeo = json_decode(Tools::file_get_contents('http://vimeo.com/api/v2/video/' . $videoId . '.json', false, null, 20), true);
                $image_url = $vimeo[0]['thumbnail_large'];
            }
            if ($videoType) {
                $file_attachment = [];
                $file_attachment['content'] = Tools::file_get_contents($image_url, false, null, 20);
                $ext = Tools::strtolower(strrchr(basename($image_url), '.'));
                $file_attachment['name'] = $fspv_id_product . '_' . $videoId . $ext;
                $same_file_counter = 1;
                while (file_exists(dirname(call_user_func([$fsproductvideo, 'getModuleFile'])) . '/thumbnail/' . $file_attachment['name'])) {
                    $file_attachment['name'] = $fspv_id_product . '_' . $videoId . '-';
                    $file_attachment['name'] .= $same_file_counter . $ext;
                    ++$same_file_counter;
                }
                $class = 'FsProductVideoImage';
                $image = new $class($file_attachment);
                $image->setResizeOptions(640, 480, 'crop');
                $image->saveImage(dirname(call_user_func([$fsproductvideo, 'getModuleFile'])) . '/thumbnail/' . $file_attachment['name']);
                $imageName = $file_attachment['name'];
            }
        }

        return $imageName;
    }

    protected function createAdditionalproductsorderRelation($product, $value)
    {
        if (!$product->id || !ElegantalEasyImportTools::isModuleInstalled('additionalproductsorder')) {
            return;
        }
        // Delete old records
        $sql = 'DELETE FROM `' . _DB_PREFIX_ . 'lineven_apo` WHERE `product_cart_id` = ' . (int) $product->id;
        Db::getInstance()->execute($sql);

        $id_shop = $this->context->shop->id;
        $id_shop_group = $this->context->shop->id_shop_group;

        $ids = explode($this->multiple_value_separator, $value);
        $ids = array_map('trim', $ids);
        $ids = array_unique($ids);
        foreach ($ids as $id) {
            if (empty($id)) {
                continue;
            }
            if ($this->find_products_by == 'reference' || !Validate::isInt($id)) {
                // Find product id by reference
                $sql = 'SELECT * FROM `' . _DB_PREFIX_ . "product` WHERE `reference` = '" . pSQL($id) . "'";
                $row = Db::getInstance()->getRow($sql);
                if ($row && isset($row['id_product']) && $row['id_product']) {
                    $id = $row['id_product'];
                }
            }
            $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'lineven_apo` (`id_shop_group`, `id_shop`, `name`, `short_description`, `comments`, `category_id`, `product_cart_id`, `product_id`, `minimum_amount`, `maximum_amount`, `is_active_groups`, `order_display`)
                VALUES (' . (int) $id_shop_group . ', ' . (int) $id_shop . ", 'a:1:{i:1;s:0:\"\";}', 'a:1:{i:1;s:0:\"\";}', '', NULL, " . (int) $product->id . ', ' . (int) $id . ', 0, 0, 0, 1)';
            Db::getInstance()->execute($sql);
        }
    }

    protected function createJmarketplaceSellerRelation($id_product, $value)
    {
        if (!$id_product || !ElegantalEasyImportTools::isModuleInstalled('jmarketplace')) {
            return;
        }
        $sql = 'DELETE FROM `' . _DB_PREFIX_ . 'seller_product` WHERE `id_seller_product` = ' . (int) $value . ' AND `id_product` = ' . (int) $id_product;
        Db::getInstance()->execute($sql);
        if ($id_product && $value && Validate::isInt($value)) {
            $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'seller_product` (`id_seller_product`, `id_product`) VALUES (' . (int) $value . ', ' . (int) $id_product . ')';
            Db::getInstance()->execute($sql);
        }
    }

    protected function createProductaffiliateRelation($product_id, $lang_id, $productaffiliate_external_shop_url, $productaffiliate_button_text)
    {
        if (!$product_id || !$lang_id || !ElegantalEasyImportTools::isModuleInstalled('productaffiliate')) {
            return;
        }
        if (!empty($productaffiliate_button_text) && !empty($productaffiliate_external_shop_url)) {
            $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'affiliated_product` (`id_product`,`id_language`,`text`,`href`)
                VALUES (' . (int) $product_id . ',' . (int) $lang_id . ",'" . pSQL($productaffiliate_button_text) . "','" . pSQL($productaffiliate_external_shop_url) . "')
                ON DUPLICATE KEY UPDATE `text`='" . pSQL($productaffiliate_button_text) . "', `href`='" . pSQL($productaffiliate_external_shop_url) . "'";
            $result = Db::getInstance()->execute($sql);
            if ($result) {
                Configuration::updateValue('AFFP_ID_LANGUAGE', $lang_id);
            }
        } else {
            $sql = 'DELETE FROM `' . _DB_PREFIX_ . 'affiliated_product` WHERE `id_product`=' . (int) $product_id . ' AND `id_language`=' . (int) $lang_id;
            $result = Db::getInstance()->execute($sql);
        }
    }

    protected function createIqitadditionaltabsRelation($id_product, $title, $description, $lang_id, $shop_ids, $iqit_count)
    {
        if (!$id_product || !ElegantalEasyImportTools::isModuleInstalled('iqitadditionaltabs')) {
            return;
        }
        if (empty($shop_ids)) {
            $shop_ids = [$this->context->shop->id];
        }

        $offset = ($iqit_count > 0) ? $iqit_count - 1 : 0;
        $sql = 'SELECT `id_iqitadditionaltab` FROM `' . _DB_PREFIX_ . 'iqitadditionaltab` WHERE `id_product` = ' . (int) $id_product . ' ORDER BY `position`, `id_iqitadditionaltab` LIMIT 1 OFFSET ' . (int) $offset;
        $id_iqitadditionaltab = Db::getInstance()->executeS($sql);
        $id_iqitadditionaltab = isset($id_iqitadditionaltab[0]['id_iqitadditionaltab']) ? $id_iqitadditionaltab[0]['id_iqitadditionaltab'] : null;

        if ($id_iqitadditionaltab) {
            $sql = 'SELECT `id_iqitadditionaltab` FROM `' . _DB_PREFIX_ . 'iqitadditionaltab_lang` WHERE `id_iqitadditionaltab` = ' . (int) $id_iqitadditionaltab . ' AND `id_lang` = ' . (int) $lang_id;
            $id_iqitadditionaltab = Db::getInstance()->getValue($sql);
            if ($id_iqitadditionaltab) {
                $sql = 'UPDATE `' . _DB_PREFIX_ . "iqitadditionaltab_lang`
                    SET `title` = '" . pSQL($title) . "', `description` = '" . pSQL($description, true) . "'
                    WHERE `id_iqitadditionaltab` = " . (int) $id_iqitadditionaltab . ' AND `id_lang` = ' . (int) $lang_id;
            } else {
                $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'iqitadditionaltab_lang` (`id_iqitadditionaltab`, `id_lang`, `title`, `description`)
                    VALUES (' . (int) $id_iqitadditionaltab . ', ' . (int) $lang_id . ", '" . pSQL($title) . "', '" . pSQL($description, true) . "')
                    WHERE `id_iqitadditionaltab` = " . (int) $id_iqitadditionaltab . ' AND `id_lang` = ' . (int) $lang_id;
            }
            Db::getInstance()->execute($sql);
        } else {
            $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'iqitadditionaltab` (`id_product`, `position`, `active`)
                VALUES (' . (int) $id_product . ', 0, 1)';
            if (Db::getInstance()->execute($sql) && ($id_iqitadditionaltab = (int) Db::getInstance()->Insert_ID())) {
                foreach ($this->id_all_langs as $id_lang) {
                    $title_lang = ($lang_id == $id_lang) ? $title : '';
                    $desc_lang = ($lang_id == $id_lang) ? $description : '';
                    $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'iqitadditionaltab_lang` (`id_iqitadditionaltab`, `id_lang`, `title`, `description`)
                        VALUES (' . (int) $id_iqitadditionaltab . ', ' . (int) $id_lang . ", '" . pSQL($title_lang) . "', '" . pSQL($desc_lang, true) . "')";
                    Db::getInstance()->execute($sql);
                }
                foreach ($shop_ids as $shop_id) {
                    $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'iqitadditionaltab_shop` (`id_iqitadditionaltab`, `id_shop`)
                        VALUES (' . (int) $id_iqitadditionaltab . ', ' . (int) $shop_id . ')';
                    Db::getInstance()->execute($sql);
                }
            }
        }
    }

    protected function createIqitextendedproductRelation($id_product, $video_links)
    {
        if (!$id_product || !ElegantalEasyImportTools::isModuleInstalled('iqitextendedproduct')) {
            return;
        }

        $video_links = explode($this->multiple_value_separator, $video_links);
        if ($video_links) {
            // Get existing videos
            $id_productvideo = 0;
            $videos = [];
            $productvideo = Db::getInstance()->getRow('SELECT * FROM `' . _DB_PREFIX_ . 'iqit_productvideo` WHERE `id_product` = ' . (int) $id_product);
            if ($productvideo) {
                $id_productvideo = (int) $productvideo['id_productvideo'];
                $videos = json_decode($productvideo['content'], true);
                if (empty($videos) || !is_array($videos)) {
                    $videos = [];
                }
            }

            foreach ($video_links as $video_link) {
                $video_type = '';
                $video_id = '';
                if (Validate::isAbsoluteUrl($video_link)) {
                    if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video_link, $match)) {
                        $video_type = 'youtube';
                        $video_id = $match[1];
                    } elseif (preg_match('/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/', $video_link, $match)) {
                        $video_type = 'vimeo';
                        $video_id = $match[5];
                    } elseif (preg_match('!^.+dailymotion\.com/(video|hub)/([^_]+)[^#]*(#video=([^_&]+))?|(dai\.ly/([^_]+))!', $video_link, $match)) {
                        $video_type = 'dailymotion';
                        if (isset($match[6])) {
                            $video_id = $match[6];
                        } elseif (isset($match[4])) {
                            $video_id = $match[4];
                        } else {
                            $video_id = $match[2];
                        }
                    }
                } else {
                    $ext = pathinfo($video_link, PATHINFO_EXTENSION);
                    if (Tools::strtolower($ext) == 'mp4') {
                        $video_type = 'hosted';
                        $video_id = $video_link;
                    }
                }
                if ($video_type && $video_id) {
                    $video_exists = false;
                    foreach ($videos as $v) {
                        if ($v['p'] == $video_type && $v['id'] == $video_id) {
                            $video_exists = true;
                            break;
                        }
                    }
                    if (!$video_exists) {
                        $videos[] = ['p' => $video_type, 'id' => $video_id];
                    }
                }
            }

            if ($videos) {
                $videos = json_encode($videos);
                if ($id_productvideo) {
                    Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . "iqit_productvideo` SET `content` = '" . pSQL($videos) . "' WHERE `id_productvideo` = " . (int) $id_productvideo);
                } else {
                    Db::getInstance()->execute('INSERT INTO `' . _DB_PREFIX_ . 'iqit_productvideo` (`id_product`, `content`) VALUES (' . (int) $id_product . ", '" . pSQL($videos) . "')");
                }
            }
        }
    }

    protected function createEcmCmlidRelation($id_product, $value, $id_product_attribute = null)
    {
        if (!$id_product || !ElegantalEasyImportTools::isModuleInstalled('ecm_cmlid')) {
            return;
        }
        $check_xml = Db::getInstance()->ExecuteS('SHOW COLUMNS FROM `' . _DB_PREFIX_ . "product` WHERE `Field` = 'xml'");
        if (!empty($check_xml) && $id_product && $value) {
            if ($id_product_attribute) {
                Db::getInstance()->update('product_attribute', ['xml' => $value], 'id_product = ' . (int) $id_product . ' AND id_product_attribute = ' . (int) $id_product_attribute);
                Db::getInstance()->update('product_attribute_shop', ['xml' => $value], 'id_product_attribute = ' . (int) $id_product_attribute);
            } else {
                Db::getInstance()->update('product', ['xml' => $value], 'id_product = ' . (int) $id_product);
                Db::getInstance()->update('product_shop', ['xml' => $value], 'id_product = ' . (int) $id_product);
            }
        }
    }

    protected function createAdvancedcustomfieldsValue($product_id, $acf_technical_name, $value, $id_lang)
    {
        if (!$product_id || !$acf_technical_name || !ElegantalEasyImportTools::isModuleInstalled('advancedcustomfields')) {
            return;
        }
        $sql = 'SELECT * FROM `' . _DB_PREFIX_ . "advanced_custom_fields` WHERE `location` = 'product' AND `technical_name` = '" . pSQL($acf_technical_name) . "'";
        $acf = Db::getInstance()->getRow($sql);
        if (!$acf || !isset($acf['id_custom_field'])) {
            return;
        }
        $value_main = $acf['translatable'] ? '' : $value;
        $value_lang = $acf['translatable'] ? $value : '';
        $id_custom_field_content = null;
        $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'advanced_custom_fields_content` WHERE `id_custom_field` = ' . (int) $acf['id_custom_field'] . ' AND `resource_id` = ' . (int) $product_id;
        $acfc = Db::getInstance()->getRow($sql);
        if ($acfc && isset($acfc['id_custom_field_content']) && $acfc['id_custom_field_content']) {
            $id_custom_field_content = (int) $acfc['id_custom_field_content'];
        } else {
            $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'advanced_custom_fields_content` (`id_store`, `id_custom_field`, `resource_id`, `value`)
                VALUES (' . (int) $this->context->shop->id . ', ' . (int) $acf['id_custom_field'] . ', ' . (int) $product_id . ", '" . pSQL($value_main, true) . "')";
            if (Db::getInstance()->execute($sql)) {
                $id_custom_field_content = (int) Db::getInstance()->Insert_ID();
            }
        }
        if ($id_custom_field_content) {
            $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'advanced_custom_fields_content_lang` WHERE `id_custom_field_content` = ' . (int) $id_custom_field_content . ' AND `id_lang` = ' . (int) $id_lang;
            $acfcl = Db::getInstance()->getRow($sql);
            if ($acfcl) {
                // Check if the field is tranlatable
                if ($acf['translatable']) {
                    $sql = 'UPDATE `' . _DB_PREFIX_ . "advanced_custom_fields_content_lang` SET `lang_value` = '" . pSQL($value_lang, true) . "' WHERE `id_custom_field_content` = " . (int) $id_custom_field_content . ' AND `id_lang` = ' . (int) $id_lang;
                } else {
                    $sql = 'UPDATE `' . _DB_PREFIX_ . "advanced_custom_fields_content` SET `value` = '" . pSQL($value_main, true) . "' WHERE `id_custom_field_content` = " . (int) $id_custom_field_content;
                }
            } else {
                $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'advanced_custom_fields_content_lang` (`id_custom_field_content`, `id_lang`, `lang_value`) VALUES (' . (int) $id_custom_field_content . ', ' . (int) $id_lang . ", '" . pSQL($value_lang, true) . "')";
            }
            Db::getInstance()->execute($sql);
        }
    }

    protected function createTotcustomfieldsValue($product_id, $code, $value, $id_lang)
    {
        if (!$product_id || !$code || !ElegantalEasyImportTools::isModuleInstalled('totcustomfields')) {
            return;
        }
        $totcustomfield = Db::getInstance()->getRow('SELECT * FROM `' . _DB_PREFIX_ . "totcustomfields_input` t WHERE t.`code_object` = 'product' AND t.`code` = '" . pSQL($code) . "'");
        if (!$totcustomfield) {
            return;
        }
        if ($totcustomfield['is_translatable']) {
            $exists = Db::getInstance()->getRow('SELECT * FROM `' . _DB_PREFIX_ . 'totcustomfields_input_' . pSQL($totcustomfield['code_input_type']) . '_value` WHERE `id_input` = ' . (int) $totcustomfield['id_input'] . ' AND `id_object` = ' . (int) $product_id . ' AND `id_lang` = ' . (int) $id_lang);
            if ($exists) {
                $sql = 'UPDATE `' . _DB_PREFIX_ . 'totcustomfields_input_' . pSQL($totcustomfield['code_input_type']) . '_value`
                    SET `id_input` = ' . (int) $totcustomfield['id_input'] . ', `id_object` = ' . (int) $product_id . ', `id_lang` = ' . (int) $id_lang . ", `value` = '" . pSQL($value, true) . "'
                    WHERE `id_input_value` = " . (int) $exists['id_input_value'];
            } else {
                $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'totcustomfields_input_' . pSQL($totcustomfield['code_input_type']) . '_value` (`id_input`, `id_object`, `id_lang`, `value`)
                    VALUES (' . (int) $totcustomfield['id_input'] . ', ' . (int) $product_id . ', ' . (int) $id_lang . ", '" . pSQL($value, true) . "')";
            }
            Db::getInstance()->execute($sql);
        } else {
            // Delete existing
            $sql = 'DELETE FROM `' . _DB_PREFIX_ . 'totcustomfields_input_' . pSQL($totcustomfield['code_input_type']) . '_value` WHERE `id_input` = ' . (int) $totcustomfield['id_input'] . ' AND `id_object` = ' . (int) $product_id;
            Db::getInstance()->execute($sql);
            // Insert new
            $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'totcustomfields_input_' . pSQL($totcustomfield['code_input_type']) . '_value` (`id_input`, `id_object`, `value`)  VALUES (' . (int) $totcustomfield['id_input'] . ', ' . (int) $product_id . ", '" . pSQL($value, true) . "')";
            Db::getInstance()->execute($sql);
        }
    }

    protected function createPpropertiesRelation($id_product, $id_product_attribute, $quantity_step, $minimal_quantity, $shop_ids)
    {
        if (!$id_product || !ElegantalEasyImportTools::isModuleInstalled('pproperties')) {
            return;
        }
        if (empty($shop_ids)) {
            $shop_ids = [$this->context->shop->id];
        }
        $data = [];
        if ($quantity_step !== false) {
            $data['quantity_step'] = $quantity_step;
        }
        if ($minimal_quantity !== false) {
            $data['minimal_quantity_fractional'] = $minimal_quantity;
        }
        foreach ($shop_ids as $key => $shop_id) {
            if ($id_product_attribute) {
                if ($key === 0) {
                    Db::getInstance()->update('product_attribute', $data, '`id_product` = ' . (int) $id_product . ' AND `id_product_attribute` = ' . (int) $id_product_attribute);
                }
                Db::getInstance()->update('product_attribute_shop', $data, '`id_product` = ' . (int) $id_product . ' AND `id_product_attribute` = ' . (int) $id_product_attribute . ' AND `id_shop` = ' . (int) $shop_id);
            } else {
                if ($key === 0) {
                    Db::getInstance()->update('product', $data, '`id_product` = ' . (int) $id_product);
                }
                Db::getInstance()->update('product_shop', $data, '`id_product` = ' . (int) $id_product . ' AND `id_shop` = ' . (int) $shop_id);
            }
        }
    }

    protected function createBmsAdvancedstockRelation($id_product, $id_product_attribute, $warehouse, $physical_quantity, $available_quantity, $reserved_quantity, $stock_shelf_location, $shop_ids)
    {
        if (!$id_product || !$warehouse || !ElegantalEasyImportTools::isModuleInstalled('advancedstock')) {
            return;
        }
        if (empty($shop_ids)) {
            $shop_ids = [$this->context->shop->id];
        }

        $physical_quantity = $physical_quantity < 0 ? 0 : $physical_quantity;
        $available_quantity = $available_quantity < 0 ? 0 : $available_quantity;
        $reserved_quantity = $reserved_quantity < 0 ? 0 : $reserved_quantity;

        $warehouse_id = 0;
        $sql = 'SELECT DISTINCT w.`w_id` FROM `' . _DB_PREFIX_ . 'bms_advancedstock_warehouse` w
            INNER JOIN `' . _DB_PREFIX_ . "bms_advancedstock_warehouse_shop` ws ON ws.`ws_warehouse_id` = w.`w_id`
            WHERE w.`w_name` = '" . pSQL($warehouse) . "' AND ws.`ws_shop_id` IN (" . implode(', ', array_map('intval', $shop_ids)) . ')';
        $w_id = (int) Db::getInstance()->getValue($sql);
        if ($w_id) {
            $warehouse_id = (int) $w_id;
        } elseif (!$w_id && Validate::isInt($warehouse)) {
            $warehouse_id = (int) $warehouse;
        } else {
            return;
        }

        $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'bms_advancedstock_warehouse_product` wp
            WHERE wp.`wi_warehouse_id` = ' . (int) $warehouse_id . ' AND wp.`wi_product_id` = ' . (int) $id_product . ' AND wp.`wi_attribute_id` = ' . (int) $id_product_attribute;
        $wp = Db::getInstance()->getRow($sql);
        if ($wp) {
            if (!$stock_shelf_location) {
                $stock_shelf_location = $wp['wi_shelf_location'];
            }
            $sql = 'UPDATE `' . _DB_PREFIX_ . 'bms_advancedstock_warehouse_product` SET `wi_physical_quantity` = ' . (int) $physical_quantity . ', `wi_available_quantity` = ' . (int) $available_quantity . ', `wi_reserved_quantity` = ' . (int) $reserved_quantity . ", wi_shelf_location = '" . pSQL($stock_shelf_location) . "' WHERE `wi_id` = " . (int) $wp['wi_id'];
            Db::getInstance()->execute($sql);
        } else {
            $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'bms_advancedstock_warehouse_product` (`wi_warehouse_id`, `wi_product_id`, `wi_attribute_id`, `wi_physical_quantity`, `wi_available_quantity`, `wi_reserved_quantity`, `wi_shelf_location`)
                VALUES (' . (int) $warehouse_id . ', ' . (int) $id_product . ', ' . (int) $id_product_attribute . ', ' . (int) $physical_quantity . ', ' . (int) $available_quantity . ', ' . (int) $reserved_quantity . ", '" . pSQL($stock_shelf_location) . "')";
            Db::getInstance()->execute($sql);
        }
    }

    protected function createWkgrocerymanagementRelation($id_product, $wk_measurement_allowed, $wk_measurement_type, $wk_measurement_value, $wk_measurement_unit, $wk_measurement_units_for_customer, $shop_ids)
    {
        if (!$id_product || !ElegantalEasyImportTools::isModuleInstalled('wkgrocerymanagement') || !$wk_measurement_value) {
            return;
        }
        if (!$this->isCsvValueTrue($wk_measurement_allowed)) {
            foreach ($shop_ids as $shop_id) {
                $sql = 'DELETE FROM `' . _DB_PREFIX_ . 'wk_grocery_products` WHERE `id_ps_product` = ' . (int) $id_product . ' AND `id_ps_shop` = ' . (int) $shop_id;
                Db::getInstance()->execute($sql);
            }

            return;
        }

        $measurement_types = [];
        $wk_measurement_type = Tools::strtolower($wk_measurement_type);
        if (method_exists('WkGroceryModuleDb', 'predefinedMeasurementTypes')) {
            $wk_types = call_user_func(['WkGroceryModuleDb', 'predefinedMeasurementTypes']);
            foreach ($wk_types as $wk_type) {
                $measurement_types[$wk_type['id']] = Tools::strtolower($wk_type['name']);
            }
        } else {
            $measurement_types = [1 => 'weight', 2 => 'length', 3 => 'area', 4 => 'volume'];
        }
        if (!in_array($wk_measurement_type, $measurement_types)) {
            return;
        }
        $wk_measurement_type = array_search($wk_measurement_type, $measurement_types);
        if (!$wk_measurement_type) {
            return;
        }

        $measurement_units = [];
        $wk_measurement_unit = Tools::strtolower($wk_measurement_unit);
        // Only position is needed because position is saved as measurement_unit
        $sql = 'SELECT u.`position`, ul.`measurement_units` FROM `' . _DB_PREFIX_ . 'wk_grocery_measurement_units` u
            INNER JOIN `' . _DB_PREFIX_ . 'wk_grocery_measurement_units_lang` ul ON ul.`id` = u.`id`
            WHERE u.`measurement_type` = ' . (int) $wk_measurement_type . '
            GROUP BY u.`position`, ul.`measurement_units`';
        $wk_measurement_units = Db::getInstance()->executeS($sql);
        foreach ($wk_measurement_units as $wk_mu) {
            $measurement_units[Tools::strtolower($wk_mu['measurement_units'])] = $wk_mu['position'];
        }
        $wk_measurement_unit = isset($measurement_units[$wk_measurement_unit]) ? $measurement_units[$wk_measurement_unit] : false;
        if (!$wk_measurement_unit && $wk_measurement_unit !== 0 && $wk_measurement_unit !== '0') {
            return;
        }

        $measurement_units_for_customer = [];
        $wk_measurement_units_for_customer = explode($this->multiple_value_separator, $wk_measurement_units_for_customer);
        foreach ($wk_measurement_units_for_customer as $wk_muc) {
            $wk_muc = isset($measurement_units[$wk_muc]) ? $measurement_units[$wk_muc] : false;
            if ($wk_muc || $wk_muc === 0 || $wk_muc === '0') {
                $measurement_units_for_customer[] = $wk_muc;
            }
        }
        if (!in_array($wk_measurement_unit, $measurement_units_for_customer)) {
            $measurement_units_for_customer[] = $wk_measurement_unit;
        }
        $measurement_units_for_customer = json_encode($measurement_units_for_customer);

        foreach ($shop_ids as $shop_id) {
            $sql = 'SELECT `id_grocery_product` FROM `' . _DB_PREFIX_ . 'wk_grocery_products`
                WHERE `id_ps_product` = ' . (int) $id_product . ' AND `id_ps_shop` = ' . (int) $shop_id;
            $id_grocery_product = Db::getInstance()->getValue($sql);
            if ($id_grocery_product) {
                $sql = 'UPDATE `' . _DB_PREFIX_ . 'wk_grocery_products`
                    SET `measurement_type` = ' . (int) $wk_measurement_type . ', `measurement_unit` = ' . (int) $wk_measurement_unit . ', `measurement_initial_value` = ' . (float) $wk_measurement_value . ", `selected_measurement_units` = '" . pSQL($measurement_units_for_customer) . "', `is_grocery` = 1
                    WHERE `id_grocery_product` = " . (int) $id_grocery_product;
            } else {
                $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'wk_grocery_products` (`id_ps_product`, `id_ps_shop`, `measurement_type`, `measurement_unit`, `measurement_initial_value`, `selected_measurement_units`, `is_grocery`)
                    VALUES (' . (int) $id_product . ', ' . (int) $shop_id . ', ' . (int) $wk_measurement_type . ', ' . (int) $wk_measurement_unit . ', ' . (float) $wk_measurement_value . ", '" . pSQL($measurement_units_for_customer) . "', 1)";
            }
            Db::getInstance()->execute($sql);
        }
    }

    protected function createMsrpRelation($id_product, $id_product_attribute, $id_tax_rules_group, $tex, $tax_included, $shop_ids)
    {
        if (!$id_product || !ElegantalEasyImportTools::isModuleInstalled('msrp')) {
            return;
        }

        $tex = (float) $this->extractPriceInDefaultCurrency($tex);

        // Check if Tax Rule Group exists
        if ($tax_included) {
            $tax_rate = 0;
            if ($id_tax_rules_group) {
                $taxRulesGroup = new TaxRulesGroup($id_tax_rules_group);
                if (!Validate::isLoadedObject($taxRulesGroup) || $taxRulesGroup->deleted) {
                    $id_tax_rules_group = 0;
                }
            }
            if ($id_tax_rules_group) {
                $address = Address::initialize();
                $tax_manager = TaxManagerFactory::getManager($address, $id_tax_rules_group);
                $tax_calculator = $tax_manager->getTaxCalculator();
                $tax_rate = $tax_calculator->getTotalRate();
            }
            if ($tax_rate) {
                $tex = (float) number_format($tex / (1 + $tax_rate / 100), 2, '.', '');
            }
        }

        foreach ($shop_ids as $shop_id) {
            if ($id_product_attribute) {
                $sql = 'SELECT `id_msrp_combination` FROM `' . _DB_PREFIX_ . 'msrp_combination`
                    WHERE `id_product` = ' . (int) $id_product . ' AND `id_combination` = ' . (int) $id_product_attribute . ' AND `id_shop` = ' . (int) $shop_id;
                $id_msrp_combination = Db::getInstance()->getValue($sql);
                if ($id_msrp_combination) {
                    $sql = 'UPDATE `' . _DB_PREFIX_ . 'msrp_combination` SET `tex` = ' . (float) $tex . ' WHERE `id_msrp_combination` = ' . (int) $id_msrp_combination;
                } else {
                    $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'msrp_combination` (`id_product`, `id_combination`, `id_shop`, `tex`, `tin`) VALUES (' . (int) $id_product . ', ' . (int) $id_product_attribute . ', ' . (int) $shop_id . ', ' . (float) $tex . ', 0)';
                }
            } else {
                $sql = 'SELECT `id_msrp_product` FROM `' . _DB_PREFIX_ . 'msrp_product`
                    WHERE `id_product` = ' . (int) $id_product . ' AND `id_shop` = ' . (int) $shop_id;
                $id_msrp_product = Db::getInstance()->getValue($sql);
                if ($id_msrp_product) {
                    $sql = 'UPDATE `' . _DB_PREFIX_ . 'msrp_product` SET `tex` = ' . (float) $tex . ' WHERE `id_msrp_product` = ' . (int) $id_msrp_product;
                } else {
                    $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'msrp_product` (`id_product`, `id_shop`, `tex`, `tin`) VALUES (' . (int) $id_product . ', ' . (int) $shop_id . ', ' . (float) $tex . ', 0)';
                }
            }
            Db::getInstance()->execute($sql);
        }
    }

    protected function createAreapacksRelation($id_product, $id_product_attribute, $areapacks_label, $areapacks_type, $areapacks_area)
    {
        if (!$id_product || !ElegantalEasyImportTools::isModuleInstalled('areapacks')) {
            return;
        }

        $id_areapacks = Db::getInstance()->getValue('SELECT `id_areapacks` FROM `' . _DB_PREFIX_ . 'areapacks` WHERE `id_product` = ' . (int) $id_product);
        if ($id_areapacks && ($areapacks_label || $areapacks_type || $areapacks_area !== '')) {
            $sql = 'UPDATE `' . _DB_PREFIX_ . 'areapacks` SET ';
            if ($areapacks_area !== '') {
                $sql .= '`item_area` = ' . (float) $areapacks_area . ', ';
            }
            if ($areapacks_label) {
                $sql .= "`item_label` = '" . pSQL($areapacks_label) . "', ";
            }
            if ($areapacks_type) {
                $sql .= '`pak_type` = ' . (int) $areapacks_type . ', ';
            }
            $sql = rtrim($sql, ', ') . ' WHERE `id_areapacks` = ' . (int) $id_areapacks;
            Db::getInstance()->execute($sql);
        } elseif (!$id_areapacks && $areapacks_label && $areapacks_type && $areapacks_area !== '') {
            $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'areapacks` (`id_product`, `item_label`, `pak_type`, `item_area`) VALUES (' . (int) $id_product . ", '" . pSQL($areapacks_label) . "', " . (int) $areapacks_type . ', ' . (float) $areapacks_area . ')';
            Db::getInstance()->execute($sql);
        }

        if ($id_product_attribute && $areapacks_area !== '') {
            Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'areapacks_combination` WHERE `id_product` = ' . (int) $id_product . ' AND `id_combination` = ' . (int) $id_product_attribute);
            Db::getInstance()->execute('INSERT INTO `' . _DB_PREFIX_ . 'areapacks_combination` (`id_product`, `id_combination`, `item_area`) VALUES (' . (int) $id_product . ', ' . (int) $id_product_attribute . ', ' . (float) $areapacks_area . ')');
        }
    }

    protected function createSldaccessoriestypeRelation($id_product, $value)
    {
        if (!$id_product || !$value || !ElegantalEasyImportTools::isModuleInstalled('sldaccessoriestype')) {
            return;
        }

        $name_separator = '>';
        $product_separator = ',';

        $types = explode($this->multiple_value_separator, $value);
        if (empty($types)) {
            return;
        }
        foreach ($types as $type) {
            $name_and_products = explode($name_separator, $type);
            if (!isset($name_and_products[0]) || !$name_and_products[0] || !isset($name_and_products[1]) || !$name_and_products[1]) {
                continue;
            }
            $name = trim($name_and_products[0]);
            $id_accessory_type = (int) Db::getInstance()->getValue('SELECT `id_accessory_type` FROM `' . _DB_PREFIX_ . "sld_accessory_type` WHERE `name` = '" . pSQL($name) . "'");
            if (!$id_accessory_type) {
                continue;
            }
            $products = explode($product_separator, $name_and_products[1]);
            if ($products && is_array($products)) {
                foreach ($products as $product_sku) {
                    $product_sku = trim($product_sku);
                    if (!$product_sku) {
                        continue;
                    }
                    $id_product_associated = (int) Db::getInstance()->getValue('SELECT `id_product` FROM `' . _DB_PREFIX_ . "product` WHERE `reference` = '" . pSQL($product_sku) . "'");
                    if (!$id_product_associated) {
                        continue;
                    }
                    Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'sld_accessory_type_product` WHERE `id_accessory_type` = ' . (int) $id_accessory_type . ' AND `id_product` = ' . (int) $id_product . ' AND `id_product_associated` = ' . (int) $id_product_associated);
                    Db::getInstance()->execute('INSERT INTO `' . _DB_PREFIX_ . 'sld_accessory_type_product` (`id_accessory_type`, `id_product`, `id_product_associated`) VALUES (' . (int) $id_accessory_type . ', ' . (int) $id_product . ', ' . (int) $id_product_associated . ')');
                }
            }
        }
    }

    protected function createAprealestateRelation($id_product, $value, $column)
    {
        if (!$id_product || $value === '' || !$column || !ElegantalEasyImportTools::isModuleInstalled('aprealestate')) {
            return;
        }

        if ($column == 'price_postfix' && $this->id_all_langs) {
            $new_value = [];
            foreach ($this->id_all_langs as $id_lang) {
                $new_value[$id_lang] = $id_lang == $this->id_lang_default ? $value : '';
            }
            $value = ElegantalEasyImportTools::storable($new_value);
        }
        if ($column == 'rent_sale') {
            if ($value == 'A') {
                $value = '1';
            } elseif ($value == 'V') {
                $value = '2';
            }
        }

        $id_aprealestate_product = (int) Db::getInstance()->getValue('SELECT `id_aprealestate_product` FROM `' . _DB_PREFIX_ . 'aprealestate_product` WHERE `id_product` = ' . (int) $id_product);
        if ($id_aprealestate_product) {
            Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'aprealestate_product` SET `' . bqSQL($column) . "` = '" . pSQL($value) . "' WHERE `id_aprealestate_product` = " . (int) $id_aprealestate_product);
        } else {
            Db::getInstance()->execute('INSERT INTO `' . _DB_PREFIX_ . 'aprealestate_product` (`id_product`, `' . bqSQL($column) . '`) VALUES (' . (int) $id_product . ", '" . pSQL($value) . "')");
        }
    }

    protected function createClassyProductExtratabRelation($id_product, $title, $content, $id_lang, $shop_ids)
    {
        if (!$id_product || !$title || !$content || !ElegantalEasyImportTools::isModuleInstalled('classy_productextratab')) {
            return;
        }
        $content = ElegantalEasyImportTools::cleanDescription($content);
        $id = Db::getInstance()->getValue('SELECT t.`id_classyproductextratabs` FROM `' . _DB_PREFIX_ . 'classyproductextratabs` t INNER JOIN `' . _DB_PREFIX_ . 'classyproductextratabs_lang` l ON (l.`id_classyproductextratabs` = t.`id_classyproductextratabs` AND l.`id_lang` = ' . (int) $id_lang . ") WHERE t.`active` = 1 AND t.`product_page` = 0 AND t.`specific_product` = '" . (int) $id_product . "-' AND l.`title` = '" . pSQL($title) . "'");
        if (!$id) {
            Db::getInstance()->execute('INSERT INTO `' . _DB_PREFIX_ . "classyproductextratabs` (`active`, `product_page`, `specific_product`) VALUES (1, 0, '" . (int) $id_product . "-')");
            $id = (int) Db::getInstance()->Insert_ID();
        }
        if ($id) {
            Db::getInstance()->execute('INSERT INTO `' . _DB_PREFIX_ . 'classyproductextratabs_lang` (`id_classyproductextratabs`, `id_lang`, `title`, `content`) VALUES (' . (int) $id . ', ' . (int) $id_lang . ", '" . pSQL($title) . "', '" . pSQL($content, true) . "') ON DUPLICATE KEY UPDATE `title` = '" . pSQL($title) . "', `content` = '" . pSQL($content, true) . "'");
            foreach ($shop_ids as $shop_id) {
                Db::getInstance()->execute('INSERT INTO `' . _DB_PREFIX_ . 'classyproductextratabs_shop` (`id_classyproductextratabs`, `id_shop`) VALUES (' . (int) $id . ', ' . (int) $shop_id . ') ON DUPLICATE KEY UPDATE `id_shop` = `id_shop`');
            }
        }
    }

    protected function deleteClassyProductExtratabRelation($id_product)
    {
        if (!$id_product || !ElegantalEasyImportTools::isModuleInstalled('classy_productextratab')) {
            return;
        }
        Db::getInstance()->execute('DELETE t, l, sh FROM `' . _DB_PREFIX_ . 'classyproductextratabs` t INNER JOIN `' . _DB_PREFIX_ . 'classyproductextratabs_lang` l ON (l.`id_classyproductextratabs` = t.`id_classyproductextratabs`) INNER JOIN `' . _DB_PREFIX_ . "classyproductextratabs_shop` sh ON (sh.`id_classyproductextratabs` = t.`id_classyproductextratabs`) WHERE t.`active` = 1 AND t.`product_page` = 0 AND t.`specific_product` = '" . (int) $id_product . "-'");
    }

    /**
     * Downloads import file according to selected method
     *
     * @return bool
     *
     * @throws Exception
     */
    public function downloadImportFile()
    {
        $error = null;

        // Get old file name so that we can delete it after downloading new file
        $old_file = $this->csv_file;

        // Generate name for new file
        $this->csv_file = ElegantalEasyImportTools::generateTmpFilename('csv');

        $remote_file_extension = null;
        try {
            switch ($this->import_type) {
                case self::$IMPORT_TYPE_UPLOAD:
                    $remote_file_extension = $this->downloadFileUploaded();
                    break;
                case self::$IMPORT_TYPE_URL:
                    $remote_file_extension = $this->downloadFileFromUrl();
                    break;
                case self::$IMPORT_TYPE_PATH:
                    $remote_file_extension = $this->downloadFileFromPath();
                    break;
                case self::$IMPORT_TYPE_FTP:
                    $remote_file_extension = $this->downloadFileFromFtp();
                    break;
                case self::$IMPORT_TYPE_SFTP:
                    $remote_file_extension = $this->downloadFileFromSftp();
                    break;
                default:
                    throw new Exception('Import Method is not valid.');
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }

        // If model has old file, delete it
        if ($old_file && !$error) {
            ElegantalEasyImportTools::deleteTmpFile($old_file);
        }

        if ($error) {
            throw new Exception($error);
        }

        $local_file = ElegantalEasyImportTools::getRealPath($this->csv_file);

        if ($this->is_cron) {
            $this->cron_csv_file_size = filesize($local_file);
            $this->cron_csv_file_md5 = md5_file($local_file);
        }

        $this->convertFileToCsv($local_file, $remote_file_extension);

        if ($this->id) {
            $this->update();
        }

        return true;
    }

    protected function downloadFileUploaded()
    {
        if (!isset($_FILES['csv_file_upload']) || empty($_FILES['csv_file_upload']['tmp_name']) || !is_uploaded_file($_FILES['csv_file_upload']['tmp_name'])) {
            throw new Exception('File is not uploaded.');
        }

        // Validate file type
        $extension = Tools::strtolower(pathinfo($_FILES['csv_file_upload']['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, self::$allowed_file_types)) {
            throw new Exception(sprintf($this->l('File type %s is not allowed.'), $extension) . ' ' . $this->l('Supported file formats:') . ' ' . implode(', ', self::$allowed_file_types));
        }

        // Validate mime type
        $mime = ElegantalEasyImportTools::getMimeType($_FILES['csv_file_upload']['tmp_name'], $extension);
        if (!in_array($mime, self::$allowed_mime_types)) {
            throw new Exception($this->l('This type of file is not allowed.') . ' Mime Type: ' . $mime);
        }

        $local_file = ElegantalEasyImportTools::createPath($this->csv_file);

        if (!move_uploaded_file($_FILES['csv_file_upload']['tmp_name'], $local_file)) {
            throw new Exception('There was an error uploading your file. Please try again.');
        }

        return $extension;
    }

    protected function downloadFileFromUrl()
    {
        if (!$this->csv_url || !ElegantalEasyImportTools::isValidUrl($this->csv_url)) {
            throw new Exception($this->l('Wrong URL') . ': ' . $this->csv_url);
        }

        $local_file = ElegantalEasyImportTools::createPath($this->csv_file);

        ElegantalEasyImportTools::downloadFileFromUrl($this->csv_url, $local_file, $this->csv_url_username, $this->csv_url_password, $this->csv_url_method, $this->csv_url_post_params);

        // Get file size
        $file_size = filesize($local_file);
        if (!$file_size) {
            @unlink($local_file);
            throw new Exception($this->l('File not found or it is empty.') . ' ' . $this->csv_url);
        }

        $extension = Tools::strtolower(pathinfo($this->csv_url, PATHINFO_EXTENSION));

        // Validate mime type
        $mime = ElegantalEasyImportTools::getMimeType($local_file, $extension);
        if (!in_array($mime, self::$allowed_mime_types)) {
            @unlink($local_file);
            throw new Exception($this->l('This type of file is not allowed.') . ' Mime Type: ' . $mime);
        }

        // Validate file type
        if (!in_array($extension, self::$allowed_file_types)) {
            switch ($mime) {
                case 'text/xml':
                case 'text/html':
                case 'application/xml':
                    $extension = 'xml';
                    break;
                case 'text/csv':
                case 'text/plain':
                case 'application/csv':
                case 'application/x-csv':
                case 'text/comma-separated-values':
                case 'text/x-comma-separated-values':
                case 'text/tab-separated-values':
                case 'message/news':
                    $extension = 'csv';
                    break;
                case 'application/vnd.ms-excel':
                case 'application/vnd.ms-office':
                    $extension = 'xls';
                    break;
                case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
                    $extension = 'xlsx';
                    break;
                case 'application/vnd.oasis.opendocument.spreadsheet':
                    $extension = 'ods';
                    break;
                case 'application/json':
                    $extension = 'json';
                    break;
                default:
                    break;
            }
            if (!in_array($extension, self::$allowed_file_types)) {
                @unlink($local_file);
                throw new Exception(sprintf($this->l('File type %s is not allowed.'), $extension) . ' ' . $this->l('Supported file formats:') . ' ' . implode(', ', self::$allowed_file_types));
            }
        }

        return $extension;
    }

    protected function downloadFileFromPath()
    {
        $csv_path = $this->csv_path;
        if (Tools::substr($csv_path, 0, 1) != '/') {
            $csv_path = realpath(_PS_ROOT_DIR_ . '/' . $csv_path);
        }

        // Validate file from path
        if (!$csv_path || !is_file($csv_path) || !is_readable($csv_path)) {
            throw new Exception($this->l('File not found from given path.') . ' ' . $csv_path);
        }

        // Validate file type
        $extension = Tools::strtolower(pathinfo($csv_path, PATHINFO_EXTENSION));
        if (!in_array($extension, self::$allowed_file_types)) {
            throw new Exception(sprintf($this->l('File type %s is not allowed.'), $extension) . ' ' . $this->l('Supported file formats:') . ' ' . implode(', ', self::$allowed_file_types));
        }

        // Validate mime type
        $mime = ElegantalEasyImportTools::getMimeType($csv_path, $extension);
        if (!in_array($mime, self::$allowed_mime_types)) {
            throw new Exception($this->l('This type of file is not allowed.') . ' Mime Type: ' . $mime);
        }

        // Clear cache of old filesize
        clearstatcache(true, $csv_path);
        // Get file size
        $file_size = filesize($csv_path);
        if (!$file_size) {
            throw new Exception($this->l('File not found or it is empty.') . ' ' . $csv_path);
        }

        $local_file = ElegantalEasyImportTools::createPath($this->csv_file);

        $file_contents = Tools::file_get_contents($csv_path);
        if ($file_contents) {
            if (!file_put_contents($local_file, $file_contents)) {
                throw new Exception('An error occured while saving the file. ' . $csv_path);
            }
        } else {
            throw new Exception($this->l('File not found or it is empty.') . ' ' . $csv_path);
        }

        return $extension;
    }

    protected function downloadFileFromFtp()
    {
        if (!$this->ftp_host) {
            throw new Exception(sprintf($this->l('%s is not valid.'), $this->l('FTP Host')));
        }
        if (!$this->ftp_username) {
            throw new Exception(sprintf($this->l('%s is not valid.'), $this->l('FTP Username')));
        }
        if (!$this->ftp_password) {
            throw new Exception(sprintf($this->l('%s is not valid.'), $this->l('FTP Password')));
        }
        if (!$this->ftp_file) {
            throw new Exception(sprintf($this->l('%s is not valid.'), $this->l('FTP File')));
        }

        $ftp_file = $this->ftp_file;
        $pathinfo = pathinfo($ftp_file);

        // Validate file type
        $extension = Tools::strtolower($pathinfo['extension']);
        if (!in_array($extension, self::$allowed_file_types)) {
            throw new Exception(sprintf($this->l('File type %s is not allowed.'), $extension) . ' ' . $this->l('Supported file formats:') . ' ' . implode(', ', self::$allowed_file_types));
        }

        // Connect to FTP server
        $ftp_port = $this->ftp_port ? $this->ftp_port : 21;
        $ftp_conn = ftp_connect($this->ftp_host, $ftp_port);
        if (!$ftp_conn) {
            throw new Exception($this->l('Could not connect to FTP') . ': ' . $this->ftp_host . ':' . $ftp_port);
        }

        // Login to FTP server
        $ftp_login = ftp_login($ftp_conn, $this->ftp_username, $this->ftp_password);
        if (!$ftp_login) {
            ftp_close($ftp_conn);
            // Try new connection with SSL
            if (function_exists('ftp_ssl_connect')) {
                $ftp_conn = ftp_ssl_connect($this->ftp_host, $ftp_port);
                if (!$ftp_conn) {
                    throw new Exception($this->l('Could not connect to FTP') . ': ' . $this->ftp_host . ':' . $ftp_port);
                }
                $ftp_login = ftp_login($ftp_conn, $this->ftp_username, $this->ftp_password);
                if (!$ftp_login) {
                    ftp_close($ftp_conn);
                    throw new Exception($this->l('FTP login failed.'));
                }
            } else {
                throw new Exception($this->l('FTP login failed.'));
            }
        }

        // You can list files this way: ftp_nlist($ftp_conn, ".")

        // Example: /path/to/file/[FILE_PATTERN: ARTICLE_FILE_DD-MM-YYYY].csv OR [FILE_PATTERN: ARTICLE_FILE_[0-9\-]+].csv
        if (preg_match("/^\[FILE\_PATTERN\:[\s]*([\w\.\-\_\(\)\$\&\%\@\s\[\]\\\+]+)\]$/", $pathinfo['filename'], $match_file_pattern)) {
            ftp_pasv($ftp_conn, true); // Try with passive mode
            $files = ftp_nlist($ftp_conn, $pathinfo['dirname']);
            if (!$files) {
                ftp_pasv($ftp_conn, false); // Disable passive mode and try again
                $files = ftp_nlist($ftp_conn, $pathinfo['dirname']);
                if (!$files) {
                    ftp_close($ftp_conn);
                    throw new Exception('FTP error getting files list.');
                }
            }
            if ($files && is_array($files)) {
                $filtered_files = preg_grep("/\." . $pathinfo['extension'] . '$/i', $files);
                if ($filtered_files && is_array($filtered_files)) {
                    // Find last modified file
                    $most_recent = [
                        'time' => 0,
                        'file' => null,
                    ];
                    foreach ($filtered_files as $file) {
                        // If $this->ftp_file starts with / then $file contains dirs, otherwise it doesn't contain and we need to add it.
                        $file = (substr($this->ftp_file, 0, 1) == '/') ? $file : $pathinfo['dirname'] . '/' . $file;
                        $pathinfo_ftp_file = pathinfo($file);
                        $pattern = $match_file_pattern[1];
                        $pattern = str_replace('YYYY', "[\d]{4}", $pattern);
                        $pattern = str_replace('MM', "[\d]{2}", $pattern);
                        $pattern = str_replace('DD', "[\d]{2}", $pattern);
                        if (preg_match('/^' . $pattern . '$/', $pathinfo_ftp_file['filename'], $match_ftp_file)) {
                            // Get the last modified time for the file
                            if (isset($match_ftp_file[1]) && $match_ftp_file[1]) {
                                $time = strtotime($match_ftp_file[1]);
                            } else {
                                $time = ftp_mdtm($ftp_conn, $file);
                            }
                            if ($time > $most_recent['time']) {
                                // This file is the most recent so far
                                $most_recent['time'] = $time;
                                $most_recent['file'] = $file;
                            }
                        }
                    }
                    $ftp_file = $most_recent['file'];
                }
            }
        } elseif ($pathinfo['filename'] == 'GET_LATEST_FILE') {
            ftp_pasv($ftp_conn, true); // Try with passive mode
            $files = ftp_nlist($ftp_conn, $pathinfo['dirname']);
            if (!$files) {
                ftp_pasv($ftp_conn, false); // Disable passive mode and try again
                $files = ftp_nlist($ftp_conn, $pathinfo['dirname']);
                if (!$files) {
                    ftp_close($ftp_conn);
                    throw new Exception('FTP error getting files list.');
                }
            }
            if ($files && is_array($files)) {
                $filtered_files = preg_grep("/\." . $pathinfo['extension'] . '$/i', $files);
                if ($filtered_files && is_array($filtered_files)) {
                    // Find last modified file
                    $most_recent = [
                        'time' => 0,
                        'file' => null,
                    ];
                    foreach ($filtered_files as $file) {
                        // Get the last modified time for the file
                        $time = ftp_mdtm($ftp_conn, $file);
                        if ($time > $most_recent['time']) {
                            // This file is the most recent so far
                            $most_recent['time'] = $time;
                            $most_recent['file'] = $file;
                        }
                    }
                    $ftp_file = $most_recent['file'];
                }
            }
        }

        // Get file size
        $file_size = ftp_size($ftp_conn, $ftp_file);
        if (!$file_size) {
            ftp_close($ftp_conn);
            throw new Exception($this->l('File not found or it is empty.'));
        }

        $local_file = ElegantalEasyImportTools::createPath($this->csv_file);

        // Download server file
        ftp_pasv($ftp_conn, true); // Try with passive mode
        if (!ftp_get($ftp_conn, $local_file, $ftp_file, FTP_BINARY)) {
            ftp_pasv($ftp_conn, false); // Disable passive mode and try again
            if (!ftp_get($ftp_conn, $local_file, $ftp_file, FTP_BINARY)) {
                ftp_close($ftp_conn);
                throw new Exception(sprintf($this->l('Error downloading %s'), $ftp_file));
            }
        }

        ftp_close($ftp_conn);

        // Validate mime type
        $mime = ElegantalEasyImportTools::getMimeType($local_file, $extension);
        if (!in_array($mime, self::$allowed_mime_types)) {
            @unlink($local_file);
            throw new Exception($this->l('This type of file is not allowed.') . ' Mime Type: ' . $mime);
        }

        return $extension;
    }

    protected function downloadFileFromSftp()
    {
        if (!$this->ftp_host) {
            throw new Exception(sprintf($this->l('%s is not valid.'), $this->l('FTP Host')));
        }
        if (!$this->ftp_username) {
            throw new Exception(sprintf($this->l('%s is not valid.'), $this->l('FTP Username')));
        }
        if (!$this->ftp_password) {
            throw new Exception(sprintf($this->l('%s is not valid.'), $this->l('FTP Password')));
        }
        if (!$this->ftp_file) {
            throw new Exception(sprintf($this->l('%s is not valid.'), $this->l('FTP File')));
        }
        if (!function_exists('ssh2_connect')) {
            throw new Exception($this->l('Function ssh2_connect not found. You need to install it on your hosting server.'));
        }

        // Validate file type
        $extension = Tools::strtolower(pathinfo($this->ftp_file, PATHINFO_EXTENSION));
        if (!in_array($extension, self::$allowed_file_types)) {
            throw new Exception(sprintf($this->l('File type %s is not allowed.'), $extension) . ' ' . $this->l('Supported file formats:') . ' ' . implode(', ', self::$allowed_file_types));
        }

        $sftp_port = $this->ftp_port ? $this->ftp_port : 22;
        $connection = call_user_func('ssh2_connect', $this->ftp_host, $sftp_port);
        if (!$connection) {
            throw new Exception($this->l('Unable to connect to SFTP.') . ' ' . $this->ftp_host . ':' . $this->ftp_port);
        }
        if (!call_user_func('ssh2_auth_password', $connection, $this->ftp_username, $this->ftp_password)) {
            throw new Exception($this->l('SFTP authentication failed.') . ' ' . $this->ftp_username . ' : ' . $this->ftp_password);
        }
        $stream = call_user_func('ssh2_sftp', $connection);
        if (!$stream) {
            throw new Exception('Failed to create SFTP stream.');
        }

        $handle = fopen('ssh2.sftp://' . (int) $stream . '/' . $this->ftp_file, 'r');
        if (!$handle) {
            throw new Exception($this->l('Failed to read SFTP file.') . ' ' . $this->ftp_file);
        }
        $contents = stream_get_contents($handle);
        if (empty($contents)) {
            throw new Exception($this->l('File not found or it is empty.') . ' ' . $this->ftp_file);
        }

        $local_file = ElegantalEasyImportTools::createPath($this->csv_file);

        $result = file_put_contents($local_file, $contents);
        @fclose($handle);
        if (!$result) {
            throw new Exception(sprintf($this->l('Error downloading %s'), $this->ftp_file));
        }

        // Validate mime type
        $mime = ElegantalEasyImportTools::getMimeType($local_file, $extension);
        if (!in_array($mime, self::$allowed_mime_types)) {
            @unlink($local_file);
            throw new Exception($this->l('This type of file is not allowed.') . ' Mime Type: ' . $mime);
        }

        return $extension;
    }

    protected function convertFileToCsv($file, $extension)
    {
        if (!is_file($file)) {
            throw new Exception('File does not exist: ' . $file);
        }
        switch ($extension) {
            case 'csv':
            case 'txt':
                ElegantalEasyImportCsv::convertToCsv($file, $this);
                break;
            case 'xml':
            case 'rss':
                ElegantalEasyImportXml::convertToCsv($file, $this);
                break;
            case 'json':
                ElegantalEasyImportJson::convertToCsv($file, $this);
                break;
            case 'xls':
            case 'xlsx':
            case 'ods':
                ElegantalEasyImportExcel::convertToCsv($file, $this);
                break;
            case 'zip':
                ElegantalEasyImportZip::convertToCsv($file, $this);
                break;
            case 'gz':
                ElegantalEasyImportGz::convertToCsv($file, $this);
                break;
            default:
                break;
        }

        return true;
    }
}
