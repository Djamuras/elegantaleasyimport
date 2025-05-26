<?php
/**
 * @author    ELEGANTAL <info@elegantal.com>
 * @copyright (c) 2025, ELEGANTAL <www.elegantal.com>
 * @license   Proprietary License - It is forbidden to resell or redistribute copies of the module or modified copies of the module.
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

require_once 'initialize.php';

/**
 * Main class of the module
 */
class ElegantalEasyImport extends ElegantalEasyImportModule
{
    /**
     * ID of this module as product on addons
     *
     * @var int
     */
    protected $productIdOnAddons = 24523;

    /**
     * List of hooks to register
     *
     * @var array
     */
    protected $hooksToRegister = [
        'displayBackOfficeHeader',
    ];

    /**
     * List of tabs (menu) to add during installation
     *
     * @var array
     */
    protected $tabsToAdd = [
        [
            'name' => 'Easy Import Products',
            'class' => 'AdminElegantalEasyImport',
            'icon' => 'repeat',
        ],
    ];

    /**
     * Current model object being edited on back-office
     */
    public $model;

    /**
     * List of module settings to be saved as Configuration record
     *
     * @var array
     */
    protected $settings = [
        'is_auto_restart_cron_after_import_finished' => 0,
        'is_delete_products_not_exist_in_file' => 0,
        'is_delete_combinations_not_exist_in_file' => 0,
        'product_ids_to_exclude_from_deactivation' => '',
        'skip_product_from_update_if_id_exists_in' => '',
        'skip_product_from_update_if_reference_has_sign' => '',
        'skip_product_from_update_if_mpn_exists_in' => '',
        'text_column_value_dictionary' => '',
        'product_quantity_data_type' => 'int',
        'cron_waiting_hour_after_completion' => 0,
        'is_assign_product_from_another_shop_if_it_already_exists' => 0,
        'is_allow_multiple_values_for_the_same_product_feature' => 1,
        'is_compare_image_using_imagick' => 0,
        'is_allow_hook_exec_after_product_save' => 0,
        'employee_id_for_events_log' => '',
        'security_token_key' => '',
        'is_disable_url_rewrite' => 0,
        'is_debug_mode' => 0,
    ];

    /**
     * Constructor method called on each newly-created object
     */
    public function __construct()
    {
        $this->name = 'elegantaleasyimport';
        $this->tab = 'administration';
        $this->version = '7.6.8';
        $this->author = 'ELEGANTAL';
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->module_key = '2429d8c323f7c699758b4ced91d7f5e7';

        parent::__construct();

        $this->displayName = $this->l('Easy Import Products From CSV, EXCEL, XML, JSON, TXT Files');
        $this->description = $this->l('Import/Export products and combinations easily with just a few clicks. You can automate this process by using CRON Job and import file from directory path, HTTP or FTP.');
        $this->ps_versions_compliancy = ['min' => '1.5.0.0', 'max' => _PS_VERSION_];

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }

    /**
     * This function plays controller role for the back-office page of the module
     *
     * @return string HTML
     */
    public function getContent()
    {
        if (_PS_VERSION_ < '1.6') {
            $this->context->controller->addCSS($this->_path . 'views/css/elegantaleasyimport-bootstrap.css', 'all');
            $this->context->controller->addCSS($this->_path . 'views/css/font-awesome.css', 'all');
            if (!in_array(Tools::getValue('event'), ['settings', 'importEdit', 'exportEdit', 'importMapping', 'exportColumns', 'importManageCategory'])) {
                $this->context->controller->addJS($this->_path . 'views/js/jquery-1.11.0.min.js');
                $this->context->controller->addJS($this->_path . 'views/js/bootstrap.js');
            }
        }
        $this->context->controller->addCSS($this->_path . 'views/css/elegantaleasyimport-back28.css', 'all');
        $this->context->controller->addJS($this->_path . 'views/js/elegantaleasyimport-back28.js');

        $this->initModel();

        $html = $this->getRedirectAlerts();

        try {
            if ($event = Tools::getValue('event')) {
                switch ($event) {
                    case 'settings':
                        $html .= $this->settings();
                        break;
                    case 'importEdit':
                        $html .= $this->importEdit();
                        break;
                    case 'importChangeStatus':
                        $html .= $this->importChangeStatus();
                        break;
                    case 'importRestart':
                        $html .= $this->importRestart();
                        break;
                    case 'importOtherSettings':
                        $html .= $this->importOtherSettings();
                        break;
                    case 'importDuplicate':
                        $html .= $this->importDuplicate();
                        break;
                    case 'importDelete':
                        $html .= $this->importDelete();
                        break;
                    case 'importMapping':
                        $html .= $this->importMapping();
                        break;
                    case 'importManageCategory':
                        $html .= $this->importManageCategory();
                        break;
                    case 'import':
                        $html .= $this->import();
                        break;
                    case 'importLatestFile':
                        $html .= $this->importLatestFile();
                        break;
                    case 'importSelectHeaderRow':
                        $html .= $this->importSelectHeaderRow();
                        break;
                    case 'importCronInfo':
                        $html .= $this->importCronInfo();
                        break;
                    case 'importHistoryList':
                        $html .= $this->importHistoryList();
                        break;
                    case 'importHistoryDelete':
                        $html .= $this->importHistoryDelete();
                        break;
                    case 'importHistoryDeleteAll':
                        $html .= $this->importHistoryDeleteAll();
                        break;
                    case 'importHistoryEventsLog':
                        $html .= $this->importHistoryEventsLog();
                        break;
                    case 'importHistoryErrors':
                        $html .= $this->importHistoryErrors();
                        break;
                    case 'importHistoryErrorsDeleteAll':
                        $html .= $this->importHistoryErrorsDeleteAll();
                        break;
                    case 'exportList':
                        $html .= $this->exportList();
                        break;
                    case 'exportEdit':
                        $html .= $this->exportEdit();
                        break;
                    case 'exportColumns':
                        $html .= $this->exportColumns();
                        break;
                    case 'export':
                        $html .= $this->export();
                        break;
                    case 'exportChangeStatus':
                        $html .= $this->exportChangeStatus();
                        break;
                    case 'exportDuplicate':
                        $html .= $this->exportDuplicate();
                        break;
                    case 'exportDelete':
                        $html .= $this->exportDelete();
                        break;
                    case 'exportCronInfo':
                        $html .= $this->exportCronInfo();
                        break;
                    case 'triggerCron':
                        $html .= $this->triggerCron();
                        break;
                    case 'backupModule':
                        $html .= $this->backupModule();
                        break;
                    case 'restoreModule':
                        $html .= $this->restoreModule();
                        break;
                    default:
                        $html .= $this->importList();
                        break;
                }
            } else {
                $html .= $this->importList();
            }
        } catch (Exception $e) {
            $html = $this->displayError($e->getMessage());
        }

        return $html;
    }

    /**
     * Add CSS to Admin Controller to display icon next to menu item
     */
    public function hookDisplayBackOfficeHeader()
    {
        if (_PS_VERSION_ < '1.7') {
            $this->context->controller->addCSS($this->_path . 'views/css/elegantaleasyimport-back-menu.css', 'all');
        }
    }

    /**
     * Initializes current model object and its attributes
     */
    public function initModel($model_id = null)
    {
        $model_id = Tools::getValue('id_elegantaleasyimport', $model_id);
        if ($model_id) {
            $model = new ElegantalEasyImportClass($model_id);
            if (Validate::isLoadedObject($model)) {
                $this->model = $model;
            }
        }
    }

    /**
     * Renders initial page of module for import rule list
     *
     * @return string HTML
     */
    protected function importList()
    {
        // Pagination data
        $total = ElegantalEasyImportClass::model()->countAll();
        $limit = 30;
        $pages = ceil($total / $limit);
        $currentPage = (int) Tools::getValue('page', 1);
        $currentPage = ($currentPage > $pages) ? $pages : $currentPage;
        $halfVisibleLinks = 5;
        $offset = ($total > $limit) ? ($currentPage - 1) * $limit : 0;

        // Sorting records
        $sortableColumns = [
            't.id_elegantaleasyimport',
            't.name',
            't.entity',
            't.is_cron',
            'h.date_ended',
            't.active',
        ];

        $orderBy = in_array(Tools::getValue('orderBy'), $sortableColumns) ? Tools::getValue('orderBy') : 't.id_elegantaleasyimport';
        $orderType = Tools::getValue('orderType') == 'asc' ? 'asc' : 'desc';

        $sql = 'SELECT *, COALESCE(t.`id_elegantaleasyimport`, h.`id_elegantaleasyimport`) as `id_elegantaleasyimport`
            FROM `' . _DB_PREFIX_ . 'elegantaleasyimport` t
            INNER JOIN `' . _DB_PREFIX_ . 'elegantaleasyimport_shop` sh ON sh.`id_elegantaleasyimport` = t.`id_elegantaleasyimport` AND sh.`id_shop` = ' . (int) $this->context->shop->id . '
            LEFT JOIN `' . _DB_PREFIX_ . 'elegantaleasyimport_history` h ON h.`id_elegantaleasyimport` = t.`id_elegantaleasyimport` AND h.`id_elegantaleasyimport_history` = (SELECT h2.`id_elegantaleasyimport_history` FROM `' . _DB_PREFIX_ . 'elegantaleasyimport_history` h2 WHERE h2.`id_elegantaleasyimport` = t.`id_elegantaleasyimport` ORDER BY h2.`id_elegantaleasyimport_history` DESC LIMIT 1)
            ORDER BY ' . $orderBy . ' ' . $orderType . '
            LIMIT ' . (int) $limit . ' OFFSET ' . (int) $offset;
        $models = Db::getInstance()->executeS($sql);

        foreach ($models as &$model) {
            $is_categories_mapped = false; // Check if categories are mapped
            if ($model['map']) {
                $modelObj = new ElegantalEasyImportClass($model['id_elegantaleasyimport']);
                $map = $modelObj->getMap();
                $category_map_keys = $modelObj->getCategoryMapKeys($map);
                foreach ($category_map_keys as $attr) {
                    if ($map && isset($map[$attr]) && $map[$attr] >= 0) {
                        $is_categories_mapped = true;
                        break;
                    }
                }
                if ($map && isset($map['manufacturer']) && $map['manufacturer'] >= 0) {
                    $is_categories_mapped = true;
                }
            }
            $model['is_categories_mapped'] = $is_categories_mapped;
        }

        $this->context->smarty->assign(
            [
                'models' => $models,
                'adminUrl' => $this->getAdminUrl(),
                'moduleUrl' => $this->getModuleUrl(),
                'version' => $this->version,
                'documentationUrls' => $this->getDocumentationUrls(['csv', 'xml', 'json', 'xls', 'xlsx', 'txt']),
                'contactDeveloperUrl' => $this->getContactDeveloperUrl(),
                'rateModuleUrl' => $this->getRateModuleUrl(),
                'pages' => $pages,
                'currentPage' => $currentPage,
                'halfVisibleLinks' => $halfVisibleLinks,
                'orderBy' => $orderBy,
                'orderType' => $orderType,
            ]
        );

        return $this->display(__FILE__, 'views/templates/admin/import_list.tpl');
    }

    protected function importRenderSteps($step)
    {
        $this->context->smarty->assign(
            [
                'adminUrl' => $this->getAdminUrl(),
                'model' => $this->model->getAttributes(),
                'step' => $step,
            ]
        );

        return $this->display(__FILE__, 'views/templates/admin/import_steps.tpl');
    }

    protected function importEdit()
    {
        $html = '';

        if (Shop::getContext() == Shop::CONTEXT_ALL) {
            $html .= $this->displayWarning($this->l('Warning! You are about to import products to ALL SHOPS. If you intend to import to a particular shop only, please select that shop on top of control panel first.'));
        }

        if (!$this->model) {
            $this->model = new ElegantalEasyImportClass();
        }

        if ($this->isPostRequest()) {
            $errors = $this->model->validateAndAssignModelAttributes();

            if ($this->model->is_cron && $this->model->import_type == ElegantalEasyImportClass::$IMPORT_TYPE_UPLOAD) {
                $errors[] = $this->l('You cannot use File Upload method for CRON Job.');
            }
            if ($this->model->email_to_send_notification && !Validate::isEmail($this->model->email_to_send_notification) && !Validate::isAbsoluteUrl($this->model->email_to_send_notification)) {
                $errors[] = $this->l('Email to send notification should be either valid email or valid URL.');
            }

            if ($this->model->product_limit_per_request < 1 || $this->model->product_limit_per_request > 1000000) {
                $this->model->product_limit_per_request = $this->model->is_cron ? 50 : 5;
            }
            if ($this->model->product_range_to_import) {
                $this->model->product_range_to_import = str_replace(' ', '', $this->model->product_range_to_import);
                if (preg_match("/^((\s*\d+\s*)-(\s*\d+\s*);?)+$/", $this->model->product_range_to_import, $match)) {
                    $product_ranges_validated = '';
                    $this->model->product_range_to_import = str_replace(' ', '', $this->model->product_range_to_import);
                    $product_ranges = explode(';', $this->model->product_range_to_import);
                    foreach ($product_ranges as $product_range) {
                        $ranges = explode('-', $product_range);
                        if (isset($ranges[0], $ranges[1]) && $ranges[1] >= $ranges[0]) {
                            $product_ranges_validated .= $product_ranges_validated ? ';' : '';
                            $product_ranges_validated .= ($ranges[0] == 0 ? 1 : $ranges[0]) . '-' . $ranges[1];
                        }
                    }
                    $this->model->product_range_to_import = $product_ranges_validated;
                } else {
                    $this->model->product_range_to_import = '';
                }
            }

            if (empty($errors)) {
                try {
                    // If file is not uploaded, skip this and continue to save rule settings
                    if ($this->model->id && $this->model->import_type == ElegantalEasyImportClass::$IMPORT_TYPE_UPLOAD && (!isset($_FILES['csv_file_upload']) || empty($_FILES['csv_file_upload']['tmp_name']) || !is_uploaded_file($_FILES['csv_file_upload']['tmp_name']))) {
                        // Nothing
                    } else {
                        $this->model->downloadImportFile();
                        $this->model->is_processing_now = 0;
                    }
                } catch (Exception $e) {
                    $errors[] = $e->getMessage();
                }
            }

            if (empty($errors)) {
                $result = empty($this->model->id) ? $this->model->add() : $this->model->update();
                if ($result) {
                    if (Tools::isSubmit('submitAndStay') && !Tools::isSubmit('submitAndNext')) {
                        $this->setRedirectAlert($this->l('Rule saved successfully.'), 'success');
                        $this->redirectAdmin([
                            'event' => 'importEdit',
                            'id_elegantaleasyimport' => $this->model->id,
                        ]);
                    } else {
                        $this->redirectAdmin([
                            'event' => 'importMapping',
                            'id_elegantaleasyimport' => $this->model->id,
                        ]);
                    }
                } else {
                    $html .= $this->displayError($this->l('Rule could not be saved.') . ' ' . Db::getInstance()->getMsgError());
                }
            } else {
                $html .= $this->displayError(nl2br(implode(PHP_EOL, $errors)));
            }
        }

        $fields_value = $this->model->getAttributes();

        // Default Values
        if (!$fields_value['id_elegantaleasyimport'] && !$this->isPostRequest()) {
            $fields_value['entity'] = Tools::getValue('import_entity');
            $fields_value['is_cron'] = 0;
            $fields_value['product_limit_per_request'] = 5;
            $fields_value['find_products_by'] = 'reference';
            $fields_value['find_combinations_by'] = 'combination_reference';
            $fields_value['create_new_products'] = 1;
            $fields_value['update_existing_products'] = 1;
            $fields_value['update_products_on_all_shops'] = 0;
            $fields_value['decimal_char'] = '.';
            $fields_value['multiple_value_separator'] = '|';
            $fields_value['shipping_package_size_unit'] = 'cm';
            $fields_value['shipping_package_weight_unit'] = 'kg';
            $fields_value['delete_old_combinations'] = 0;
            $fields_value['enable_new_products_by_default'] = 1;
            $fields_value['skip_if_no_stock'] = 0;
            $fields_value['enable_if_have_stock'] = 0;
            $fields_value['disable_if_no_stock'] = 0;
            $fields_value['disable_if_no_image'] = 0;
            $fields_value['enable_all_products_found_in_csv'] = 0;
            $fields_value['disable_all_products_not_found_in_csv'] = 0;
            $fields_value['deny_orders_when_no_stock_for_products_not_found_in_file'] = 0;
            $fields_value['delete_stock_for_products_not_found_in_csv'] = 0;
            $fields_value['is_utf8_encode'] = 0;
            $fields_value['active'] = 1;
        }

        $inputs = [
            [
                'type' => 'text',
                'label' => $this->l('Name'),
                'name' => 'name',
                'desc' => $this->l('Name is for your reference only.'),
            ],
            [
                'type' => 'select',
                'label' => $this->l('Import entity'),
                'name' => 'entity',
                'options' => [
                    'query' => [
                        ['key' => 'product', 'value' => $this->l('Products')],
                        ['key' => 'combination', 'value' => $this->l('Combinations')],
                    ],
                    'id' => 'key',
                    'name' => 'value',
                ],
                'desc' => $this->l('Choose what you want to import.'),
            ],
            [
                'type' => 'select',
                'label' => $this->l('Import method'),
                'name' => 'import_type',
                'options' => [
                    'query' => [
                        ['key' => ElegantalEasyImportClass::$IMPORT_TYPE_UPLOAD, 'value' => $this->l('File Upload')],
                        ['key' => ElegantalEasyImportClass::$IMPORT_TYPE_PATH, 'value' => $this->l('File Path')],
                        ['key' => ElegantalEasyImportClass::$IMPORT_TYPE_URL, 'value' => $this->l('File From URL')],
                        ['key' => ElegantalEasyImportClass::$IMPORT_TYPE_FTP, 'value' => $this->l('File From FTP')],
                        ['key' => ElegantalEasyImportClass::$IMPORT_TYPE_SFTP, 'value' => $this->l('File From SFTP')],
                    ],
                    'id' => 'key',
                    'name' => 'value',
                ],
                'desc' => $this->l('Choose how you want to import.'),
            ],
            [
                'type' => 'file',
                'label' => $this->l('Upload import file'),
                'name' => 'csv_file_upload',
                'desc' => $this->l('Upload file from your computer.') . ' ' . $this->l('Supported file formats:') . ' ' . implode(', ', ElegantalEasyImportClass::$allowed_file_types),
            ],
            [
                'type' => 'text',
                'label' => $this->l('Full path to import file'),
                'name' => 'csv_path',
                'desc' => $this->l('Enter absolute path to the file on your server.') . ' ' . $this->l('Supported file formats:') . ' ' . implode(', ', ElegantalEasyImportClass::$allowed_file_types) . '. ' . $this->l('For example') . ': ' . realpath(dirname(__FILE__) . '/../..') . '/products.csv ' . $this->l('NOTE: You can use this method only if your file is located on the same server as your shop.'),
            ],
            [
                'type' => 'text',
                'label' => $this->l('URL to import file'),
                'name' => 'csv_url',
                'desc' => $this->l('Enter HTTP URL to your file.') . ' ' . $this->l('Supported file formats:') . ' ' . implode(', ', ElegantalEasyImportClass::$allowed_file_types) . '. ' . $this->l('For example') . ': http://example.com/filename.csv',
            ],
            [
                'type' => 'text',
                'label' => $this->l('HTTP username'),
                'name' => 'csv_url_username',
                'autocomplete' => false,
                'desc' => $this->l('If your file is password protected, enter username for authentication.'),
            ],
            [
                'type' => 'elegantalpassword',
                'label' => $this->l('HTTP password'),
                'name' => 'csv_url_password',
                'autocomplete' => false,
                'desc' => $this->l('If your file is password protected, enter password for authentication.'),
            ],
            [
                'type' => 'select',
                'label' => $this->l('HTTP method for downloading file'),
                'name' => 'csv_url_method',
                'options' => [
                    'query' => [
                        ['key' => 'GET', 'value' => 'GET'],
                        ['key' => 'POST', 'value' => 'POST'],
                    ],
                    'id' => 'key',
                    'name' => 'value',
                ],
                'desc' => $this->l('Choose HTTP method that you want to use for downloading import file from given URL.'),
            ],
            [
                'type' => 'text',
                'label' => $this->l('POST request content'),
                'name' => 'csv_url_post_params',
                'desc' => $this->l('You can enter POST request parameters in the following format:') . ' key1=value1&key2=value2',
            ],
            [
                'type' => 'text',
                'label' => $this->l('FTP host'),
                'name' => 'ftp_host',
                'desc' => $this->l('Enter address of FTP server.') . ' ' . $this->l('This parameter should not have any trailing slashes and should not be prefixed with ftp://') . ' ' . $this->l('For example:') . ' ftp.example.com',
            ],
            [
                'type' => 'text',
                'label' => $this->l('FTP port'),
                'name' => 'ftp_port',
                'desc' => $this->l('Enter FTP port number. If left empty, default 21 port will be used.'),
            ],
            [
                'type' => 'text',
                'label' => $this->l('FTP username'),
                'name' => 'ftp_username',
                'desc' => $this->l('Enter username for the FTP.'),
            ],
            [
                'type' => 'elegantalpassword',
                'label' => $this->l('FTP password'),
                'name' => 'ftp_password',
                'autocomplete' => false,
                'desc' => $this->l('Enter password for the FTP.'),
            ],
            [
                'type' => 'text',
                'label' => $this->l('FTP file'),
                'name' => 'ftp_file',
                'desc' => $this->l('Enter file name located in FTP directory.') . ' ' . $this->l('Supported file formats:') . ' ' . implode(', ', ElegantalEasyImportClass::$allowed_file_types) . '. ' . $this->l('For example') . ': uploads/data/example.csv',
            ],
            [
                'type' => (_PS_VERSION_ < '1.6') ? 'el_switch' : 'switch',
                'label' => $this->l('Enable CRON job'),
                'name' => 'is_cron',
                'is_bool' => true,
                'values' => [
                    [
                        'id' => 'is_cron_on',
                        'value' => 1,
                        'label' => $this->l('Yes'),
                    ],
                    [
                        'id' => 'is_cron_off',
                        'value' => 0,
                        'label' => $this->l('No'),
                    ],
                ],
                'desc' => $this->l('CRON job will be enabled for this import rule so that you can automate importing your file from specified location at scheduled time.'),
            ],
            [
                'type' => 'text',
                'label' => $this->l('Email to send notification'),
                'name' => 'email_to_send_notification',
                'desc' => $this->l('You can enter an email to which a notification will be sent when CRON finishes importing.'),
            ],
            [
                'type' => 'text',
                'label' => $this->l('Number of products to process per CRON request'),
                'name' => 'product_limit_per_request',
                'desc' => $this->l('You can control the number of products that should be processed per CRON execution.') . ' ' . $this->l('It is recommended that you keep it 50 for importing by CRON.') . ' ' . $this->l('You should not make it large number which may cause issues on the server because hosting servers do not allow long time for web requests.') . ' ' . $this->l('If you want CRON to import more products, just make it run frequently, for example, every 5 minutes.'),
            ],
            [
                'type' => 'text',
                'label' => $this->l('Range of products to import'),
                'name' => 'product_range_to_import',
                'desc' => $this->l('You can specify the range (interval) of products that should be imported.') . ' ' . $this->l('Leave this EMPTY to import ALL PRODUCTS.') . ' ' . $this->l('If you want to import specific chunk (interval) of products, enter it in this format:') . ' FROM - TO. ' . $this->l('For example') . ': ' . $this->l('All Products') . ': ' . $this->l('Empty') . ', ' . $this->l('First 100 products') . ': 1 - 100, ' . $this->l('From product 101 to product 500') . ': 101 - 500  ' . $this->l('You can also use multiple intervals.') . ' ' . $this->l('For example') . ': 1-100;300-500;850-900',
            ],
            [
                'type' => 'select',
                'label' => $this->l('Find existing products by'),
                'name' => 'find_products_by',
                'options' => [
                    'query' => $this->getFindProductsByForSelect(),
                    'id' => 'key',
                    'name' => 'value',
                ],
                'desc' => $this->l('Select product property by which you want to find existing products.') . ' ' . $this->l('Usually Product Code is used') . ' (Reference). ' . $this->l('You can use ID option IF ONLY product IDs in your import file match product IDs in your shop.'),
            ],
            [
                'type' => 'select',
                'label' => $this->l('Find existing combinations by'),
                'name' => 'find_combinations_by', // This is select box setting. If it is needed to find existing combinations by multiple properties, it will be implemented using a setting that accepts comma separated properties.
                'options' => [
                    'query' => $this->getFindCombinationsByForSelect(),
                    'id' => 'key',
                    'name' => 'value',
                ],
                'desc' => $this->l('Select combination property by which you want to find existing combinations.') . ' ' . $this->l('Usually Combination Code is used') . ' (Combination Reference). ' . $this->l('You can use Combination ID option IF ONLY combination IDs in your import file match combination IDs in your shop.'),
            ],
            [
                'type' => (_PS_VERSION_ < '1.6') ? 'el_switch' : 'switch',
                'label' => $this->l('Force ID for new products'),
                'name' => 'force_id_product',
                'is_bool' => true,
                'values' => [
                    [
                        'id' => 'force_id_product_on',
                        'value' => 1,
                        'label' => $this->l('Yes'),
                    ],
                    [
                        'id' => 'force_id_product_off',
                        'value' => 0,
                        'label' => $this->l('No'),
                    ],
                ],
                'desc' => $this->l('IDs of new products will be imported as-is. If you keep this option disabled, IDs will be auto-generated for new products.'),
            ],
            [
                'type' => 'select',
                'label' => $this->l('Supplier'),
                'name' => 'supplier_id',
                'options' => [
                    'query' => $this->getSuppliersForSelect(false),
                    'id' => 'key',
                    'name' => 'value',
                ],
                'desc' => $this->l('Select supplier if you import products from different suppliers and from different files.') . ' ' . $this->l('The products of each supplier will be managed independently even if they use the same product reference for different products.') . ' ' . $this->l('PLEASE NOTE') . ': ' . $this->l('If you select a supplier here, it means the module will update products IF ONLY they are associated with the selected supplier.'),
            ],
            [
                'type' => 'text',
                'label' => $this->l('Base URL/PATH for product images'),
                'name' => 'base_url_images',
                'desc' => $this->l('For example') . ' URL: http://example.com/images/csv/ or PATH: ' . realpath(dirname(__FILE__) . '/../..') . '/images/ ' . $this->l('If the image in your import file is just name of file, the module will take it from') . ' http://example.com/images/csv/filename.jpg',
            ],
            [
                'type' => 'text',
                'label' => $this->l('Product reference modifier'),
                'name' => 'product_reference_modifier',
                'desc' => $this->l('You can modify product reference by adding prefix and suffix.') . ' ' . sprintf($this->l('The keyword %s will be replaced with actual reference during import.'), 'REFERENCE') . ' ' . $this->l('For example') . ': prefixREFERENCEsuffix',
            ],
            [
                'type' => 'text',
                'label' => $this->l('Price modifier'),
                'name' => 'price_modifier',
                'desc' => $this->l('You can use arithmetic formula which will be used to modify product price.') . ' ' . $this->l('Examples') . ': *2; /3; +1.11; -0.5 ' . $this->l('You can also create different formula based on price.') . ' ' . $this->l('For example') . ': ' . $this->l('If you want to add 15% for products that have price from 0 to 100 and 20% for products that have price from 101 to above, you can write this formula:') . ' [0 - 100]*1.15; [101 - #]*1.20',
            ],
            [
                'type' => 'text',
                'label' => $this->l('Min price amount'),
                'name' => 'min_price_amount',
                'desc' => $this->l('New products that have lower price than the specified min price amount will be skipped during the import and price of existing products will not be updated if lower than min price.'),
            ],
            [
                'type' => 'select',
                'label' => $this->l('Multiple value separator'),
                'name' => 'multiple_value_separator',
                'options' => [
                    'query' => [
                        ['key' => '|', 'value' => '|'],
                        ['key' => ',', 'value' => ','],
                        ['key' => ';', 'value' => ';'],
                        ['key' => ':', 'value' => ':'],
                        ['key' => '#', 'value' => '#'],
                        ['key' => '*', 'value' => '*'],
                        ['key' => '-', 'value' => '-'],
                        ['key' => '_', 'value' => '_'],
                        ['key' => '/', 'value' => '/'],
                        ['key' => '\\', 'value' => '\\'],
                        ['key' => '>', 'value' => '>'],
                        ['key' => '>>', 'value' => '>>'],
                        ['key' => '>>>', 'value' => '>>>'],
                        ['key' => '->', 'value' => '->'],
                        ['key' => '=>', 'value' => '=>'],
                        ['key' => ' ', 'value' => 'Space'],
                    ],
                    'id' => 'key',
                    'name' => 'value',
                ],
                'desc' => $this->l('Select a character used as separator for list type values.') . ' ' . $this->l('For example') . ':  image1.jpg|image2.jpg|image3.jpg',
            ],
            [
                'type' => 'select',
                'label' => $this->l('Price decimal mark'),
                'name' => 'decimal_char',
                'options' => [
                    'query' => [
                        ['key' => '.', 'value' => '. (dot)'],
                        ['key' => ',', 'value' => ', (comma)'],
                    ],
                    'id' => 'key',
                    'name' => 'value',
                ],
                'desc' => $this->l('Choose a decimal mark used in your file for product price.') . ' ' . $this->l('A decimal mark is a symbol used to separate the integer part from the fractional part of product price.'),
            ],
            [
                'type' => 'select',
                'label' => $this->l('Shipping package size unit'),
                'name' => 'shipping_package_size_unit',
                'options' => [
                    'query' => [
                        ['key' => 'm', 'value' => 'm'],
                        ['key' => 'cm', 'value' => 'cm'],
                        ['key' => 'mm', 'value' => 'mm'],
                    ],
                    'id' => 'key',
                    'name' => 'value',
                ],
                'desc' => $this->l('Select unit of length used in your file for shipping package size.'),
            ],
            [
                'type' => 'select',
                'label' => $this->l('Shipping package weight unit'),
                'name' => 'shipping_package_weight_unit',
                'options' => [
                    'query' => [
                        ['key' => 'kg', 'value' => 'kg'],
                        ['key' => 'g', 'value' => 'g'],
                    ],
                    'id' => 'key',
                    'name' => 'value',
                ],
                'desc' => $this->l('Select unit of weight used in your file for shipping package weight.'),
            ],
            [
                'type' => (_PS_VERSION_ < '1.6') ? 'el_switch' : 'switch',
                'label' => $this->l('Create new products'),
                'name' => 'create_new_products',
                'is_bool' => true,
                'values' => [
                    [
                        'id' => 'create_new_products_on',
                        'value' => 1,
                        'label' => $this->l('Yes'),
                    ],
                    [
                        'id' => 'create_new_products_off',
                        'value' => 0,
                        'label' => $this->l('No'),
                    ],
                ],
                'desc' => $this->l('New products / combinations will be created if they do not already exist.'),
            ],
            [
                'type' => (_PS_VERSION_ < '1.6') ? 'el_switch' : 'switch',
                'label' => $this->l('Enable new products by default'),
                'name' => 'enable_new_products_by_default',
                'is_bool' => true,
                'values' => [
                    [
                        'id' => 'enable_new_products_by_default_on',
                        'value' => 1,
                        'label' => $this->l('Yes'),
                    ],
                    [
                        'id' => 'enable_new_products_by_default_off',
                        'value' => 0,
                        'label' => $this->l('No'),
                    ],
                ],
                'desc' => $this->l('New products will be enabled by default. If this option is disabled, new products will be disabled.') . ' ' . $this->l('This option has no affect if ENABLED column is used on the next step in mapping.'),
            ],
            [
                'type' => (_PS_VERSION_ < '1.6') ? 'el_switch' : 'switch',
                'label' => $this->l('Skip new products without stock quantity'),
                'name' => 'skip_if_no_stock',
                'is_bool' => true,
                'values' => [
                    [
                        'id' => 'skip_if_no_stock_on',
                        'value' => 1,
                        'label' => $this->l('Yes'),
                    ],
                    [
                        'id' => 'skip_if_no_stock_off',
                        'value' => 0,
                        'label' => $this->l('No'),
                    ],
                ],
                'desc' => $this->l('New products that have no stock quantity will not be imported.'),
            ],
            [
                'type' => (_PS_VERSION_ < '1.6') ? 'el_switch' : 'switch',
                'label' => $this->l('Update existing products'),
                'name' => 'update_existing_products',
                'is_bool' => true,
                'values' => [
                    [
                        'id' => 'update_existing_products_on',
                        'value' => 1,
                        'label' => $this->l('Yes'),
                    ],
                    [
                        'id' => 'update_existing_products_off',
                        'value' => 0,
                        'label' => $this->l('No'),
                    ],
                ],
                'desc' => $this->l('Existing products / combinations will be updated.'),
            ],
            [
                'type' => (_PS_VERSION_ < '1.6') ? 'el_switch' : 'switch',
                'label' => $this->l('Enable products that have stock quantity'),
                'name' => 'enable_if_have_stock',
                'is_bool' => true,
                'values' => [
                    [
                        'id' => 'enable_if_have_stock_on',
                        'value' => 1,
                        'label' => $this->l('Yes'),
                    ],
                    [
                        'id' => 'enable_if_have_stock_off',
                        'value' => 0,
                        'label' => $this->l('No'),
                    ],
                ],
                'desc' => $this->l('All products in file that have stock quantity will be enabled.') . ' ' . $this->l('This option has no affect if ENABLED column is used on the next step in mapping.'),
            ],
            [
                'type' => (_PS_VERSION_ < '1.6') ? 'el_switch' : 'switch',
                'label' => $this->l('Disable products that have no stock quantity'),
                'name' => 'disable_if_no_stock',
                'is_bool' => true,
                'values' => [
                    [
                        'id' => 'disable_if_no_stock_on',
                        'value' => 1,
                        'label' => $this->l('Yes'),
                    ],
                    [
                        'id' => 'disable_if_no_stock_off',
                        'value' => 0,
                        'label' => $this->l('No'),
                    ],
                ],
                'desc' => $this->l('All products in file that have no stock quantity will be disabled.') . ' ' . $this->l('This option has no affect if ENABLED column is used on the next step in mapping.'),
            ],
            [
                'type' => (_PS_VERSION_ < '1.6') ? 'el_switch' : 'switch',
                'label' => $this->l('Disable products that have no image'),
                'name' => 'disable_if_no_image',
                'is_bool' => true,
                'values' => [
                    [
                        'id' => 'disable_if_no_image_on',
                        'value' => 1,
                        'label' => $this->l('Yes'),
                    ],
                    [
                        'id' => 'disable_if_no_image_off',
                        'value' => 0,
                        'label' => $this->l('No'),
                    ],
                ],
                'desc' => $this->l('All products in file that have no image will be disabled.'),
            ],
            [
                'type' => (_PS_VERSION_ < '1.6') ? 'el_switch' : 'switch',
                'label' => $this->l('Enable products found in import file'),
                'name' => 'enable_all_products_found_in_csv',
                'is_bool' => true,
                'values' => [
                    [
                        'id' => 'enable_all_products_found_in_csv_on',
                        'value' => 1,
                        'label' => $this->l('Yes'),
                    ],
                    [
                        'id' => 'enable_all_products_found_in_csv_off',
                        'value' => 0,
                        'label' => $this->l('No'),
                    ],
                ],
                'desc' => $this->l('All products that exist in import file will be enabled.') . ' ' . $this->l('This option has no affect if ENABLED column is used on the next step in mapping.'),
            ],
            [
                'type' => (_PS_VERSION_ < '1.6') ? 'el_switch' : 'switch',
                'label' => $this->l('Disable products not found in import file'),
                'name' => 'disable_all_products_not_found_in_csv',
                'is_bool' => true,
                'values' => [
                    [
                        'id' => 'disable_all_products_not_found_in_csv_on',
                        'value' => 1,
                        'label' => $this->l('Yes'),
                    ],
                    [
                        'id' => 'disable_all_products_not_found_in_csv_off',
                        'value' => 0,
                        'label' => $this->l('No'),
                    ],
                ],
                'desc' => $this->l('All products that do not exist in import file will be disabled.'),
            ],
            [
                'type' => (_PS_VERSION_ < '1.6') ? 'el_switch' : 'switch',
                'label' => $this->l('Delete stock for products / combinations not found in file'),
                'name' => 'delete_stock_for_products_not_found_in_csv',
                'is_bool' => true,
                'values' => [
                    [
                        'id' => 'delete_stock_for_products_not_found_in_csv_on',
                        'value' => 1,
                        'label' => $this->l('Yes'),
                    ],
                    [
                        'id' => 'delete_stock_for_products_not_found_in_csv_off',
                        'value' => 0,
                        'label' => $this->l('No'),
                    ],
                ],
                'desc' => $this->l('All products / combinations that do not exist in import file will have 0 quantity.'),
            ],
            [
                'type' => (_PS_VERSION_ < '1.6') ? 'el_switch' : 'switch',
                'label' => $this->l('Deny orders when out of stock for products not found in file'),
                'name' => 'deny_orders_when_no_stock_for_products_not_found_in_file',
                'is_bool' => true,
                'values' => [
                    [
                        'id' => 'deny_orders_when_no_stock_for_products_not_found_in_file_on',
                        'value' => 1,
                        'label' => $this->l('Yes'),
                    ],
                    [
                        'id' => 'deny_orders_when_no_stock_for_products_not_found_in_file_off',
                        'value' => 0,
                        'label' => $this->l('No'),
                    ],
                ],
                'desc' => $this->l('All products that do not exist in import file will have "Deny orders" behavior when out of stock.'),
            ],
            [
                'type' => (_PS_VERSION_ < '1.6') ? 'el_switch' : 'switch',
                'label' => $this->l('Delete old combinations'),
                'name' => 'delete_old_combinations',
                'is_bool' => true,
                'values' => [
                    [
                        'id' => 'delete_old_combinations_on',
                        'value' => 1,
                        'label' => $this->l('Yes'),
                    ],
                    [
                        'id' => 'delete_old_combinations_off',
                        'value' => 0,
                        'label' => $this->l('No'),
                    ],
                ],
                'desc' => $this->l('CAUTION: This will delete ALL old combinations of the products being imported.'),
            ],
            [
                'type' => (_PS_VERSION_ < '1.6') ? 'el_switch' : 'switch',
                'label' => $this->l('Enable UTF-8 encoding'),
                'name' => 'is_utf8_encode',
                'is_bool' => true,
                'values' => [
                    [
                        'id' => 'is_utf8_encode_on',
                        'value' => 1,
                        'label' => $this->l('Yes'),
                    ],
                    [
                        'id' => 'is_utf8_encode_off',
                        'value' => 0,
                        'label' => $this->l('No'),
                    ],
                ],
                'desc' => $this->l('Encodes an ISO-8859-1 string to UTF-8. You need to enable this option if your file is ISO-8859-1 encoded.'),
            ],
            [
                'type' => 'hidden',
                'name' => 'active',
            ],
        ];
        if (Shop::isFeatureActive()) {
            $inputs[] = [
                'type' => (_PS_VERSION_ < '1.6') ? 'el_switch' : 'switch',
                'label' => $this->l('Update products on all shops'),
                'name' => 'update_products_on_all_shops',
                'is_bool' => true,
                'values' => [
                    [
                        'id' => 'update_products_on_all_shops_on',
                        'value' => 1,
                        'label' => $this->l('Yes'),
                    ],
                    [
                        'id' => 'update_products_on_all_shops_off',
                        'value' => 0,
                        'label' => $this->l('No'),
                    ],
                ],
                'desc' => $this->l('Products that exist in multiple shops will be updated even if a particular shop is selected.'),
            ];
        } else {
            $inputs[] = [
                'type' => 'hidden',
                'name' => 'update_products_on_all_shops',
            ];
        }

        $fields_form = [
            'form' => [
                'legend' => [
                    'title' => $this->l('Step') . ' 1: ' . $this->l('Import Settings'),
                    'icon' => 'icon-cloud-upload',
                ],
                'input' => $inputs,
                'submit' => [
                    'title' => $this->l('Save & Next'),
                    'name' => 'submitAndNext',
                ],
                'buttons' => [
                    [
                        'title' => $this->l('Save & Stay'),
                        'name' => 'submitAndStay',
                        'type' => 'submit',
                        'class' => 'pull-right',
                        'icon' => 'process-icon-save',
                    ],
                    [
                        'href' => $this->getAdminUrl(),
                        'title' => $this->l('Back'),
                        'class' => 'pull-left',
                        'icon' => 'process-icon-back',
                    ],
                ],
            ],
        ];

        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->submit_action = 'submitImportEdit';
        $helper->name_controller = 'elegantalBootstrapWrapper';
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->module = $this;
        $helper->identifier = $this->identifier;
        $helper->currentIndex = $this->getAdminUrl(['event' => 'importEdit', 'id_elegantaleasyimport' => $this->model->id]);
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = [
            'base_url' => $this->context->shop->getBaseURL(),
            'language' => [
                'id_lang' => $lang->id,
                'iso_code' => $lang->iso_code,
            ],
            'fields_value' => $fields_value,
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        ];

        return $this->importRenderSteps(1) . $html . $helper->generateForm([$fields_form]);
    }

    protected function importChangeStatus()
    {
        if (!$this->model) {
            $this->setRedirectAlert($this->l('Record not found.'), 'error');
            $this->redirectAdmin();
        }
        $this->model->active = $this->model->active == 1 ? 0 : 1;
        $this->model->is_processing_now = 0;
        if ($this->model->update()) {
            $this->setRedirectAlert($this->l('Status changed successfully.'), 'success');
        } else {
            $this->setRedirectAlert('Status could not be changed.', 'error');
        }
        $this->redirectAdmin();
    }

    protected function importRestart()
    {
        if (!$this->model) {
            $this->setRedirectAlert($this->l('Record not found.'), 'error');
            $this->redirectAdmin();
        }
        if (!$this->model->is_cron) {
            $this->setRedirectAlert('You cannot do this for manual import.', 'error');
            $this->redirectAdmin();
        }
        $this->model->is_processing_now = 0;
        $file = ElegantalEasyImportTools::getRealPath($this->model->csv_file);
        if (!$file || !is_file($file) || !filesize($file)) {
            $this->model->downloadImportFile();
        }
        $this->model->saveCsvRows();
        $this->setRedirectAlert($this->model->name . ' [' . $this->model->id_elegantaleasyimport . ']: ' . $this->l('import rule restarted.'), 'success');
        $this->redirectAdmin();
    }

    protected function importDuplicate()
    {
        if (!$this->model) {
            $this->setRedirectAlert($this->l('Record not found.'), 'error');
            $this->redirectAdmin();
        }

        $model = clone $this->model;
        $model->id = null;
        $model->id_elegantaleasyimport = null;
        $model->name .= ' (Copy)';
        $model->cron_csv_file_size = null;
        $model->cron_csv_file_md5 = null;
        $model->is_processing_now = 0;
        $model->active = 1;

        // Copy csv file
        $old_csv_file = ElegantalEasyImportTools::getRealPath($this->model->csv_file);
        if ($old_csv_file && is_file($old_csv_file) && filesize($old_csv_file)) {
            $model->csv_file = ElegantalEasyImportTools::generateTmpFilename('csv');
            $new_csv_file = ElegantalEasyImportTools::createPath($model->csv_file);
            ElegantalEasyImportTools::copyFile($old_csv_file, $new_csv_file);
        } else {
            $model->csv_file = null;
        }

        if ($model->add()) {
            // Copy category map
            $category_maps = ElegantalEasyImportCategoryMap::model()->findAll([
                'condition' => [
                    'id_elegantaleasyimport' => $this->model->id,
                ],
            ]);
            if ($category_maps) {
                foreach ($category_maps as $category_map) {
                    $categoryMap = new ElegantalEasyImportCategoryMap();
                    $categoryMap->id_elegantaleasyimport = $model->id;
                    $categoryMap->type = $category_map['type'];
                    $categoryMap->csv_category = $category_map['csv_category'];
                    $categoryMap->shop_category_id = $category_map['shop_category_id'];
                    $categoryMap->add();
                }
            }
            $this->setRedirectAlert($this->l('Rule duplicated successfully.'), 'success');
            $this->redirectAdmin([
                'event' => 'importEdit',
                'id_elegantaleasyimport' => $model->id,
            ]);
        } else {
            $this->setRedirectAlert('Rule could not be duplicated. ' . Db::getInstance()->getMsgError(), 'error');
        }

        $this->redirectAdmin();
    }

    protected function importDelete()
    {
        if (!$this->model) {
            $this->setRedirectAlert($this->l('Record not found.'), 'error');
            $this->redirectAdmin();
        }
        if ($this->model->delete()) {
            ElegantalEasyImportTools::deleteTmpFile($this->model->csv_file);
            $this->setRedirectAlert($this->l('Rule deleted successfully.'), 'success');
        } else {
            $this->setRedirectAlert('Rule could not be deleted. ' . Db::getInstance()->getMsgError(), 'error');
        }
        $this->redirectAdmin();
    }

    protected function importOtherSettings()
    {
        if (!$this->model) {
            $this->setRedirectAlert($this->l('Record not found.'), 'error');
            $this->redirectAdmin();
        }

        $html = '';
        $settings = $this->model->getModelSettings();

        // Process Form
        if ($this->isPostRequest()) {
            foreach ($settings as $key => $value) {
                if (Tools::isSubmit($key)) {
                    $settings[$key] = Tools::getValue($key);
                }
            }

            if (!empty($settings)) {
                $this->model->other_settings = ElegantalEasyImportTools::storable($settings);
                $this->model->update();
                $this->setRedirectAlert($this->l('Settings saved successfully.'), 'success');
                if (Tools::isSubmit('submitAndStay') && !Tools::isSubmit('submitAndBack')) {
                    $this->redirectAdmin([
                        'event' => 'importOtherSettings',
                        'id_elegantaleasyimport' => $this->model->id,
                    ]);
                } else {
                    $this->redirectAdmin();
                }
            } else {
                $html .= $this->displayError($this->l('Settings could not be saved.'));
            }
        }

        // Render Form
        $inputs = [];
        foreach ($settings as $key => &$value) {
            if (is_array($value)) {
                continue;
            }
            if (in_array($key, ['security_token_key', 'is_disable_url_rewrite'])) {
                continue;
            }
            if (Tools::substr($key, 0, 3) == 'is_') {
                $inputs[] = [
                    'type' => (_PS_VERSION_ < '1.6') ? 'el_switch' : 'switch',
                    'label' => Tools::strtoupper(str_replace(['is_', '_'], ' ', $key)),
                    'name' => $key,
                    'is_bool' => true,
                    'values' => [
                        [
                            'id' => $key . '_on',
                            'value' => 1,
                            'label' => $this->l('Yes'),
                        ],
                        [
                            'id' => $key . '_off',
                            'value' => 0,
                            'label' => $this->l('No'),
                        ],
                    ],
                ];
            } elseif (Tools::substr($key, 0, 5) == 'text_') {
                $inputs[] = [
                    'type' => 'textarea',
                    'label' => Tools::strtoupper(str_replace(['text_', '_'], ' ', $key)),
                    'name' => $key,
                ];
            } else {
                $inputs[] = [
                    'type' => 'text',
                    'label' => Tools::strtoupper(str_replace('_', ' ', $key)),
                    'name' => $key,
                ];
            }
        }
        $fields_value = $settings;
        $fields_form = [
            'form' => [
                'legend' => [
                    'title' => $this->model->name . ' - ' . $this->l('Other Settings'),
                    'icon' => 'icon-cogs',
                ],
                'input' => $inputs,
                'submit' => [
                    'title' => $this->l('Save'),
                    'name' => 'submitAndBack',
                ],
                'buttons' => [
                    [
                        'title' => $this->l('Save & Stay'),
                        'name' => 'submitAndStay',
                        'type' => 'submit',
                        'class' => 'pull-right',
                        'icon' => 'process-icon-save',
                    ],
                    [
                        'href' => $this->getAdminUrl(),
                        'title' => $this->l('Back'),
                        'class' => 'pull-left',
                        'icon' => 'process-icon-back',
                    ],
                ],
            ],
        ];

        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->submit_action = 'importOtherSettings';
        $helper->name_controller = 'elegantalBootstrapWrapper';
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->module = $this;
        $helper->identifier = $this->identifier;
        $helper->currentIndex = $this->getAdminUrl(['event' => 'importOtherSettings', 'id_elegantaleasyimport' => $this->model->id]);
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = [
            'base_url' => $this->context->shop->getBaseURL(),
            'language' => [
                'id_lang' => $lang->id,
                'iso_code' => $lang->iso_code,
            ],
            'fields_value' => $fields_value,
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        ];

        $html .= $helper->generateForm([$fields_form]);

        return $html;
    }

    protected function importHistoryList()
    {
        if (!$this->model) {
            $this->setRedirectAlert($this->l('Record not found.'), 'error');
            $this->redirectAdmin();
        }

        // Pagination data
        $total = ElegantalEasyImportHistory::model()->countAll([
            'condition' => [
                'id_elegantaleasyimport' => $this->model->id,
            ],
        ]);
        $limit = 30;
        $pages = ceil($total / $limit);
        $currentPage = (int) Tools::getValue('page', 1);
        $currentPage = ($currentPage > $pages) ? $pages : $currentPage;
        $halfVisibleLinks = 5;
        $offset = ($total > $limit) ? ($currentPage - 1) * $limit : 0;

        // Sorting records
        $sortableColumns = [
            'h.total_number_of_products',
            'h.number_of_products_processed',
            'h.number_of_products_created',
            'h.number_of_products_updated',
            'h.number_of_products_deleted',
            'h.date_started',
            'h.date_ended',
            'e.errors_count',
        ];

        $orderBy = in_array(Tools::getValue('orderBy'), $sortableColumns) ? Tools::getValue('orderBy') : 'h.id_elegantaleasyimport_history';
        $orderType = Tools::getValue('orderType') == 'asc' ? 'asc' : 'desc';

        $sql = 'SELECT *, COALESCE(h.`id_elegantaleasyimport_history`, e.`id_elegantaleasyimport_history`) as `id_elegantaleasyimport_history`
            FROM `' . _DB_PREFIX_ . 'elegantaleasyimport_history` h
            LEFT JOIN (SELECT `id_elegantaleasyimport_history`, COUNT(*) AS `errors_count` FROM `' . _DB_PREFIX_ . 'elegantaleasyimport_error` GROUP BY `id_elegantaleasyimport_history`) e ON e.id_elegantaleasyimport_history = h.id_elegantaleasyimport_history
            WHERE h.`id_elegantaleasyimport` = ' . (int) $this->model->id . '
            ORDER BY ' . $orderBy . ' ' . $orderType . '
            LIMIT ' . (int) $limit . ' OFFSET ' . (int) $offset;
        $models = Db::getInstance()->executeS($sql);

        $this->context->smarty->assign(
            [
                'model' => $this->model->getAttributes(),
                'models' => $models,
                'adminUrl' => $this->getAdminUrl(),
                'pages' => $pages,
                'currentPage' => $currentPage,
                'halfVisibleLinks' => $halfVisibleLinks,
                'orderBy' => $orderBy,
                'orderType' => $orderType,
            ]
        );

        return $this->display(__FILE__, 'views/templates/admin/import_history_list.tpl');
    }

    protected function importHistoryDelete()
    {
        $history_id = Tools::getValue('id_elegantaleasyimport_history');
        $history = new ElegantalEasyImportHistory($history_id);
        if (!Validate::isLoadedObject($history)) {
            $this->setRedirectAlert($this->l('Record not found.'), 'error');
            $this->redirectAdmin();
        }

        if ($history->delete()) {
            $this->setRedirectAlert($this->l('Record deleted successfully.'), 'success');
        } else {
            $this->setRedirectAlert('Record could not be deleted. ' . Db::getInstance()->getMsgError(), 'error');
        }
        $this->redirectAdmin(['event' => 'importHistoryList', 'id_elegantaleasyimport' => $history->id_elegantaleasyimport]);
    }

    protected function importHistoryDeleteAll()
    {
        if (!$this->model) {
            $this->setRedirectAlert($this->l('Record not found.'), 'error');
            $this->redirectAdmin();
        }

        $sql = 'DELETE FROM `' . _DB_PREFIX_ . 'elegantaleasyimport_history` WHERE `id_elegantaleasyimport` = ' . (int) $this->model->id;
        if (Db::getInstance()->execute($sql)) {
            $this->setRedirectAlert($this->l('Record deleted successfully.'), 'success');
        } else {
            $this->setRedirectAlert('Record could not be deleted. ' . Db::getInstance()->getMsgError(), 'error');
        }

        $this->redirectAdmin(['event' => 'importHistoryList', 'id_elegantaleasyimport' => $this->model->id]);
    }

    protected function importHistoryEventsLog()
    {
        if (!$this->model) {
            $this->setRedirectAlert($this->l('Record not found.'), 'error');
            $this->redirectAdmin();
        }

        $history_id = Tools::getValue('id_elegantaleasyimport_history');
        $history = new ElegantalEasyImportHistory($history_id);
        if (!Validate::isLoadedObject($history)) {
            $this->setRedirectAlert($this->l('Record not found.'), 'error');
            $this->redirectAdmin();
        }

        $type = Tools::getValue('type', 'created');

        // Pagination data
        $all_products = $history->getProductsProcessed($this->model->entity, $type);
        $total = is_array($all_products) ? count($all_products) : 0;
        $limit = 500;
        $pages = ceil($total / $limit);
        $currentPage = (int) Tools::getValue('page', 1);
        $currentPage = ($currentPage > $pages) ? $pages : $currentPage;
        $halfVisibleLinks = 5;
        $offset = ($total > $limit) ? ($currentPage - 1) * $limit : 0;

        $sortableColumns = [
            'p.id_product',
            'p.reference',
            'p.ean13',
            'pa.id_product_attribute',
            'pa.reference',
            'pa.ean13',
        ];
        $orderBy = in_array(Tools::getValue('orderBy'), $sortableColumns) ? Tools::getValue('orderBy') : '';
        $orderType = Tools::getValue('orderType') == 'desc' ? 'desc' : 'asc';

        $products = $history->getProductsProcessed($this->model->entity, $type, $orderBy, $orderType, $limit, $offset);

        $this->context->smarty->assign(
            [
                'adminUrl' => $this->getAdminUrl(),
                'model' => $this->model->getAttributes(),
                'history' => $history->getAttributes(),
                'type' => $type,
                'products' => $products,
                'total' => $total,
                'pages' => $pages,
                'currentPage' => $currentPage,
                'halfVisibleLinks' => $halfVisibleLinks,
                'orderBy' => $orderBy,
                'orderType' => $orderType,
            ]
        );

        return $this->display(__FILE__, 'views/templates/admin/import_history_events_log.tpl');
    }

    protected function importHistoryErrors()
    {
        if (!$this->model) {
            $this->setRedirectAlert($this->l('Record not found.'), 'error');
            $this->redirectAdmin();
        }

        $history_id = Tools::getValue('id_elegantaleasyimport_history');
        if (!$history_id) {
            $history_id = $this->model->getLastHistory()->id;
        }
        if (!$history_id) {
            $this->setRedirectAlert('History ID is required.', 'error');
            $this->redirectAdmin();
        }

        // Pagination data
        $total = ElegantalEasyImportError::model()->countAll([
            'condition' => [
                'id_elegantaleasyimport_history' => $history_id,
            ],
        ]);
        $limit = 100;
        $pages = ceil($total / $limit);
        $currentPage = (int) Tools::getValue('page', 1);
        $currentPage = ($currentPage > $pages) ? $pages : $currentPage;
        $halfVisibleLinks = 5;
        $offset = ($total > $limit) ? ($currentPage - 1) * $limit : 0;

        // Sorting records
        $sortableColumns = [
            'product_id_reference',
            'error',
            'date_created',
        ];

        $orderBy = in_array(Tools::getValue('orderBy'), $sortableColumns) ? Tools::getValue('orderBy') : 'id_elegantaleasyimport_error';
        $orderType = Tools::getValue('orderType') == 'asc' ? 'asc' : 'desc';

        $errors = ElegantalEasyImportError::model()->findAll([
            'condition' => [
                'id_elegantaleasyimport_history' => $history_id,
            ],
            'order' => $orderBy . ' ' . $orderType,
            'limit' => $limit,
            'offset' => $offset,
        ]);

        $this->context->smarty->assign(
            [
                'model' => $this->model->getAttributes(),
                'history_id' => $history_id,
                'errors' => $errors,
                'adminUrl' => $this->getAdminUrl(),
                'pages' => $pages,
                'currentPage' => $currentPage,
                'halfVisibleLinks' => $halfVisibleLinks,
                'orderBy' => $orderBy,
                'orderType' => $orderType,
            ]
        );

        return $this->display(__FILE__, 'views/templates/admin/import_history_errors.tpl');
    }

    protected function importHistoryErrorsDeleteAll()
    {
        $history_id = Tools::getValue('id_elegantaleasyimport_history');
        $history = new ElegantalEasyImportHistory($history_id);
        if (!Validate::isLoadedObject($history)) {
            $this->setRedirectAlert($this->l('Record not found.'), 'error');
            $this->redirectAdmin();
        }

        $sql = 'DELETE FROM `' . _DB_PREFIX_ . 'elegantaleasyimport_error` WHERE `id_elegantaleasyimport_history` = ' . (int) $history_id;
        if (Db::getInstance()->execute($sql)) {
            $this->setRedirectAlert($this->l('Record deleted successfully.'), 'success');
        } else {
            $this->setRedirectAlert('Record could not be deleted. ' . Db::getInstance()->getMsgError(), 'error');
        }

        $this->redirectAdmin(['event' => 'importHistoryErrors', 'id_elegantaleasyimport' => $history->id_elegantaleasyimport, 'id_elegantaleasyimport_history' => $history_id]);
    }

    /**
     * Process mapping form
     *
     * @return string HTML
     */
    protected function importMapping()
    {
        if (!$this->model) {
            $this->setRedirectAlert($this->l('Record not found.'), 'error');
            $this->redirectAdmin();
        }

        $id_lang_default = (int) Configuration::get('PS_LANG_DEFAULT');
        $languages = $this->context->controller->getLanguages();
        $multilangColumns = ElegantalEasyImportMap::getLangColumns();
        $mapping_multiple_columns = ['category_3_' . $id_lang_default, 'image_3', 'accessory_3', 'feature_3_' . $id_lang_default, 'attachment_3', 'attachment_name_3_' . $id_lang_default, 'attachment_description_3_' . $id_lang_default, 'attribute_name_3_' . $id_lang_default, 'attribute_value_3_' . $id_lang_default, 'iqitadditionaltabs_title_3', 'iqitadditionaltabs_description_3', 'classy_productextratab_title_3_' . $id_lang_default, 'classy_productextratab_content_3_' . $id_lang_default];
        $default_map = ($this->model->entity == 'combination') ? $this->model->defaultMapCombinations : $this->model->defaultMapProducts;
        $map_keys = array_keys($default_map);
        $csv_header_from_file = $this->model->getCsvHeader();

        if ($this->isPostRequest()) {
            if (Tools::getValue('ajax')) {
                $result = ['success' => true];
                try {
                    $map = [];
                    $map_default_values = [];
                    foreach ($map_keys as $key) {
                        if (Tools::isSubmit($key)) {
                            $map[$key] = Tools::getValue($key);
                        } else {
                            $map[$key] = '-1';
                        }
                        if (Tools::isSubmit('default_' . $key)) {
                            $map_default_values[$key] = Tools::getValue('default_' . $key);
                        } else {
                            $map_default_values[$key] = '';
                        }
                        // Add multi-language columns to map
                        foreach ($multilangColumns as $mcol) {
                            if ($key == $mcol . '_' . $id_lang_default) {
                                foreach ($languages as $language) {
                                    if ($language['id_lang'] != $id_lang_default && $language['active']) {
                                        if (Tools::isSubmit($mcol . '_' . $language['id_lang'])) {
                                            $map[$mcol . '_' . $language['id_lang']] = Tools::getValue($mcol . '_' . $language['id_lang']);
                                        } else {
                                            $map[$mcol . '_' . $language['id_lang']] = '-1';
                                        }
                                        if (Tools::isSubmit('default_' . $mcol . '_' . $language['id_lang'])) {
                                            $map_default_values[$mcol . '_' . $language['id_lang']] = Tools::getValue('default_' . $mcol . '_' . $language['id_lang']);
                                        } else {
                                            $map_default_values[$mcol . '_' . $language['id_lang']] = '';
                                        }
                                    }
                                }
                                break;
                            }
                        }
                        // Add multiple columns
                        if (in_array($key, $mapping_multiple_columns) && preg_match("/([a-z_]+)_([\d]+)_?([\d]+)?/", $key, $match)) {
                            $column_name = $match[1];
                            $column_number = $match[2] + 1;
                            $column_id_lang = (isset($match[3]) && $match[3]) ? $match[3] : null;
                            $next_key = $column_name . '_' . $column_number . ($column_id_lang ? '_' . $id_lang_default : '');

                            while ($next_key && Tools::isSubmit($next_key)) {
                                $map[$next_key] = Tools::getValue($next_key);
                                if (Tools::isSubmit('default_' . $next_key)) {
                                    $map_default_values[$next_key] = Tools::getValue('default_' . $next_key);
                                } else {
                                    $map_default_values[$next_key] = '';
                                }
                                // Add multi-language columns to map
                                if ($column_id_lang) {
                                    foreach ($languages as $language) {
                                        if ($language['id_lang'] != $id_lang_default && $language['active']) {
                                            $next_key_lang = $column_name . '_' . $column_number . '_' . $language['id_lang'];
                                            if (Tools::isSubmit($next_key_lang)) {
                                                $map[$next_key_lang] = Tools::getValue($next_key_lang);
                                            } else {
                                                $map[$next_key_lang] = '-1';
                                            }
                                            if (Tools::isSubmit('default_' . $next_key_lang)) {
                                                $map_default_values[$next_key_lang] = Tools::getValue('default_' . $next_key_lang);
                                            } else {
                                                $map_default_values[$next_key_lang] = '';
                                            }
                                        }
                                    }
                                }
                                ++$column_number;
                                $next_key = $column_name . '_' . $column_number . ($column_id_lang ? '_' . $id_lang_default : '');
                            }
                        }
                    }

                    // Save map
                    $this->model->map = ElegantalEasyImportTools::storable($map);
                    $this->model->map_default_values = ElegantalEasyImportTools::storable($map_default_values);
                    $this->model->csv_header = ElegantalEasyImportTools::storable($csv_header_from_file);

                    $this->model->is_processing_now = 0;

                    $this->model->update();
                } catch (Exception $e) {
                    $result = ['success' => false, 'message' => $e->getMessage()];
                }
                echo json_encode($result);
                exit;
            }

            // If CRON, save csv rows in db so that import will start from next execution
            if ($this->model->is_cron) {
                if (Tools::isSubmit('submitAndNext')) {
                    $this->model->saveCsvRows();
                } else {
                    $data_rows_exist = ElegantalEasyImportData::model()->find([
                        'condition' => [
                            'id_elegantaleasyimport' => $this->model->id,
                        ],
                    ]);
                    if (!$data_rows_exist) {
                        $this->model->saveCsvRows();
                    }
                }
            }
            if (Tools::isSubmit('submitAndStay') && !Tools::isSubmit('submitAndNext')) {
                $this->setRedirectAlert($this->l('Rule saved successfully.'), 'success');
                $this->redirectAdmin([
                    'event' => 'importMapping',
                    'id_elegantaleasyimport' => $this->model->id,
                ]);
            } elseif (Tools::isSubmit('submitAndManageCategory') && !Tools::isSubmit('submitAndNext')) {
                $this->redirectAdmin([
                    'event' => 'importManageCategory',
                    'id_elegantaleasyimport' => $this->model->id,
                ]);
            } else {
                $this->redirectAdmin([
                    'event' => 'import',
                    'id_elegantaleasyimport' => $this->model->id,
                ]);
            }
        }

        if ($this->model->header_row == 1) {
            // This will make adjustment in mapping if needed
            $fields_value = $this->model->getMap();
        } else {
            // This will show mapping as it is
            $model_map = ElegantalEasyImportTools::unstorable($this->model->map);
            $fields_value = array_merge($default_map, $model_map);
        }

        // Move added multiple column keys after its sibling, instead of showing it at the end.
        $new_map_keys = [];
        foreach ($map_keys as $key) {
            $new_map_keys[] = $key;
            if (in_array($key, $mapping_multiple_columns) && preg_match("/([a-z_]+)_([\d]+)_?([\d]+)?/", $key, $match)) {
                $column_name = $match[1];
                $column_number = $match[2] + 1;
                $column_id_lang = (isset($match[3]) && $match[3]) ? $match[3] : null;
                $next_key = $column_name . '_' . $column_number . ($column_id_lang ? '_' . $id_lang_default : '');
                while (!in_array($next_key, $map_keys) && isset($fields_value[$next_key])) {
                    $new_map_keys[] = $next_key;
                    ++$column_number;
                    $next_key = $column_name . '_' . $column_number . ($column_id_lang ? '_' . $id_lang_default : '');
                }
            }
        }
        $map_keys = $new_map_keys;

        $inputs = [];
        $csv_header_for_select = $this->getCsvHeaderForSelect($csv_header_from_file);
        foreach ($map_keys as $key) {
            $inputs[] = [
                'type' => 'elegantal_mapping_select',
                'label' => ElegantalEasyImportMap::getLabelByKey($key, $this),
                'name' => $key,
                'options' => [
                    'query' => $csv_header_for_select,
                    'id' => 'key',
                    'name' => 'value',
                ],
                'form_group_class' => 'elegantal_mapping_form_group',
                'multiple_value_separator' => $this->model->multiple_value_separator,
                'desc' => ($key == 'delete_product') ? $this->l('CAUTION: This will delete product') : null,
            ];
        }

        $fields_form = [
            'form' => [
                'legend' => [
                    'title' => $this->l('Step') . ' 2: ' . $this->l('Match file data with product data') . ' - "' . $this->model->name . '"',
                    'icon' => 'icon-random',
                ],
                'input' => $inputs,
                'submit' => [
                    'title' => ($this->model->is_cron ? $this->l('Save') . ' & CRON' : $this->l('Save & Import')),
                    'name' => 'submitAndNext',
                ],
                'buttons' => [
                    [
                        'title' => $this->l('Save & Stay'),
                        'name' => 'submitAndStay',
                        'type' => 'submit',
                        'class' => 'pull-right',
                        'icon' => 'process-icon-save',
                    ],
                    [
                        'href' => $this->getAdminUrl(),
                        'title' => $this->l('Main Page'),
                        'class' => 'pull-left',
                        'icon' => 'process-icon-back',
                    ],
                    [
                        'href' => $this->getAdminUrl(['event' => 'importEdit', 'id_elegantaleasyimport' => $this->model->id]),
                        'title' => $this->l('Back'),
                        'class' => 'pull-left',
                        'icon' => 'process-icon-back',
                    ],
                ],
            ],
        ];

        if ($this->model->entity == 'product') {
            $fields_form['form']['buttons'][] = [
                'title' => $this->l('Save') . ' & ' . $this->l('More Settings'),
                'name' => 'submitAndManageCategory',
                'type' => 'submit',
                'class' => 'pull-right',
                'icon' => 'process-icon-edit',
            ];
        }

        $lang = new Language($id_lang_default);
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->submit_action = 'submitMapping';
        $helper->name_controller = 'elegantalBootstrapWrapper';
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->module = $this;
        $helper->identifier = $this->identifier;
        $helper->currentIndex = $this->getAdminUrl(['event' => 'importMapping', 'id_elegantaleasyimport' => $this->model->id]);
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = [
            'base_url' => $this->context->shop->getBaseURL(),
            'language' => [
                'id_lang' => $lang->id,
                'iso_code' => $lang->iso_code,
            ],
            'languages' => $languages,
            'id_language' => $this->context->language->id,
            'fields_value' => $fields_value,
            'model_map_default_values' => $this->model->getMapDefaultValues(),
            'id_lang_default' => $id_lang_default,
            'multilang_columns' => $multilangColumns,
        ];

        Media::addJsDef([
            'elegantal_max_input_vars' => (int) ini_get('max_input_vars'),
        ]);

        $this->context->smarty->assign(
            [
                'adminUrl' => $this->getAdminUrl(),
                'model' => $this->model->getAttributes(),
            ]
        );

        return $this->importRenderSteps(2) . $this->display(__FILE__, 'views/templates/admin/import_header_row.tpl') . $helper->generateForm([$fields_form]);
    }

    /**
     * Action to change header row number for the CSV file of the current rule
     */
    protected function importSelectHeaderRow()
    {
        if ($this->model && Tools::isSubmit('header_row')) {
            $this->model->header_row = (int) Tools::getValue('header_row');
            $this->model->header_row = $this->model->header_row >= 0 ? $this->model->header_row : 1;
            $this->model->update();
        }
        $this->redirectAdmin([
            'event' => 'importMapping',
            'id_elegantaleasyimport' => $this->model->id,
        ]);
    }

    protected function importManageCategory()
    {
        if (!$this->model) {
            $this->setRedirectAlert($this->l('Record not found.'), 'error');
            $this->redirectAdmin();
        }

        if ($this->isPostRequest()) {
            if (Tools::getValue('ajax')) {
                $result = ['success' => true];
                try {
                    if (Tools::isSubmit('multiple_subcategory_separator')) {
                        $this->model->multiple_subcategory_separator = Tools::getValue('multiple_subcategory_separator');
                        if ($this->model->multiple_subcategory_separator == $this->model->multiple_value_separator) {
                            throw new Exception($this->l('Multiple Subcategory Separator cannot be the same as Multiple Value Separator.'));
                        }
                    }
                    if (Tools::isSubmit('is_associate_all_subcategories')) {
                        $this->model->is_associate_all_subcategories = (int) Tools::getValue('is_associate_all_subcategories');
                    }
                    if (Tools::isSubmit('is_first_parent_root_for_categories')) {
                        $this->model->is_first_parent_root_for_categories = (int) Tools::getValue('is_first_parent_root_for_categories');
                    }
                    $this->model->is_processing_now = 0;

                    ElegantalEasyImportCategoryMap::deleteAllByRule($this->model->id);

                    $categories_allowed = Tools::getValue('categories_allowed');
                    if ($categories_allowed && is_array($categories_allowed)) {
                        foreach ($categories_allowed as $allowed_category) {
                            if (!$allowed_category) {
                                continue;
                            }
                            $categoryMap = new ElegantalEasyImportCategoryMap();
                            $categoryMap->id_elegantaleasyimport = $this->model->id;
                            $categoryMap->type = ElegantalEasyImportCategoryMap::$CATEGORIES_ALLOWED;
                            $categoryMap->csv_category = $allowed_category;
                            $categoryMap->add();
                        }
                    }

                    $categories_disallowed = Tools::getValue('categories_disallowed');
                    if ($categories_disallowed && is_array($categories_disallowed)) {
                        foreach ($categories_disallowed as $disallowed_category) {
                            if (!$disallowed_category) {
                                continue;
                            }
                            $categoryMap = new ElegantalEasyImportCategoryMap();
                            $categoryMap->id_elegantaleasyimport = $this->model->id;
                            $categoryMap->type = ElegantalEasyImportCategoryMap::$CATEGORIES_DISALLOWED;
                            $categoryMap->csv_category = $disallowed_category;
                            $categoryMap->add();
                        }
                    }

                    $categories_map_file = Tools::getValue('categories_map_file');
                    $categories_map_shop = Tools::getValue('categories_map_shop');
                    if ($categories_map_file && is_array($categories_map_file) && $categories_map_shop && is_array($categories_map_shop)) {
                        foreach ($categories_map_file as $key => $csv_category) {
                            if (!$csv_category || !isset($categories_map_shop[$key]) || !$categories_map_shop[$key]) {
                                continue;
                            }
                            $categoryMap = new ElegantalEasyImportCategoryMap();
                            $categoryMap->id_elegantaleasyimport = $this->model->id;
                            $categoryMap->type = ElegantalEasyImportCategoryMap::$CATEGORIES_MAP;
                            $categoryMap->csv_category = $csv_category;
                            $categoryMap->shop_category_id = $categories_map_shop[$key];
                            $categoryMap->add();
                        }
                    }

                    $manufacturers_allowed = Tools::getValue('manufacturers_allowed');
                    if ($manufacturers_allowed && is_array($manufacturers_allowed)) {
                        foreach ($manufacturers_allowed as $allowed_manufacturer) {
                            if (!$allowed_manufacturer) {
                                continue;
                            }
                            $categoryMap = new ElegantalEasyImportCategoryMap();
                            $categoryMap->id_elegantaleasyimport = $this->model->id;
                            $categoryMap->type = ElegantalEasyImportCategoryMap::$MANUFACTURERS_ALLOWED;
                            $categoryMap->csv_category = $allowed_manufacturer;
                            $categoryMap->add();
                        }
                    }

                    $manufacturers_disallowed = Tools::getValue('manufacturers_disallowed');
                    if ($manufacturers_disallowed && is_array($manufacturers_disallowed)) {
                        foreach ($manufacturers_disallowed as $disallowed_manufacturer) {
                            if (!$disallowed_manufacturer) {
                                continue;
                            }
                            $categoryMap = new ElegantalEasyImportCategoryMap();
                            $categoryMap->id_elegantaleasyimport = $this->model->id;
                            $categoryMap->type = ElegantalEasyImportCategoryMap::$MANUFACTURERS_DISALLOWED;
                            $categoryMap->csv_category = $disallowed_manufacturer;
                            $categoryMap->add();
                        }
                    }
                    $this->model->update();
                } catch (Exception $e) {
                    $result = ['success' => false, 'message' => $e->getMessage()];
                }
                echo json_encode($result);
                exit;
            }

            // If CRON, save csv rows in db so that import will start from next execution
            if ($this->model->is_cron) {
                if (Tools::isSubmit('submitAndNext')) {
                    $this->model->saveCsvRows();
                } else {
                    $data_rows_exist = ElegantalEasyImportData::model()->find([
                        'condition' => [
                            'id_elegantaleasyimport' => $this->model->id,
                        ],
                    ]);
                    if (!$data_rows_exist) {
                        $this->model->saveCsvRows();
                    }
                }
            }

            if (Tools::isSubmit('submitAndStay') && !Tools::isSubmit('submitAndNext')) {
                $this->setRedirectAlert($this->l('Categories saved successfully.'), 'success');
                $this->redirectAdmin([
                    'event' => 'importManageCategory',
                    'id_elegantaleasyimport' => $this->model->id,
                ]);
            } else {
                $this->redirectAdmin([
                    'event' => 'import',
                    'id_elegantaleasyimport' => $this->model->id,
                ]);
            }
        }

        $file = ElegantalEasyImportTools::getRealPath($this->model->csv_file);
        if (!$file || !is_file($file) || !is_readable($file) || !filesize($file)) {
            throw new Exception($this->l('File not found or it is empty.'));
        }

        $delimiter = ElegantalEasyImportCsv::identifyCsvDelimiter($file);
        $rootCategory = Category::getRootCategory();
        $map = $this->model->getMap();
        $category_map_keys = $this->model->getCategoryMapKeys();
        $multiple_value_separator = $this->model->multiple_value_separator;

        $handle = fopen($file, 'r');
        if (!$handle) {
            throw new Exception('Cannot open file. ' . $file);
        }

        // Build categories tree from file categories
        $file_categories_tree = [];
        $shop_categories_tree = [];
        $manufacturers_of_file = [];
        $manufacturers_of_file_for_select = [['key' => '', 'value' => ' ']];
        $row_count = 0;
        while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
            ++$row_count;
            if ($this->model->header_row > 0 && $this->model->header_row >= $row_count) {
                continue;
            }
            // Check if non-empty row. Remove spaces & tabs and utf-8 BOM and then check length of line
            $line_str = preg_replace("/[\s\t\"]+/", '', implode('', $data));
            $line_str = str_replace("\xEF\xBB\xBF", '', $line_str);
            if (Tools::strlen($line_str) <= 0) {
                continue;
            }
            if ($this->model->is_utf8_encode) {
                $data = array_map(['ElegantalEasyImportTools', 'encodeUtf8'], $data);
            }

            $category_names = [];
            foreach ($category_map_keys as $attr) {
                if (isset($data[$map[$attr]]) && $data[$map[$attr]]) {
                    $category_names_tmp = explode($multiple_value_separator, $data[$map[$attr]]);
                    foreach ($category_names_tmp as $category_name_tmp) {
                        $category_names[] = $category_name_tmp;
                    }
                }
            }
            if (!empty($category_names)) {
                $file_categories_tree = ElegantalEasyImportCategoryMap::addCategoriesToTree($file_categories_tree, $category_names, $multiple_value_separator, $this->model->multiple_subcategory_separator);
                $shop_categories_tree = Category::getNestedCategories($rootCategory->id, $this->context->language->id, false);
            }

            if ($map['manufacturer'] >= 0 && isset($data[$map['manufacturer']]) && $data[$map['manufacturer']] && !in_array($data[$map['manufacturer']], $manufacturers_of_file)) {
                $manufacturers_of_file[] = $data[$map['manufacturer']];
                $manufacturers_of_file_for_select[] = ['key' => $data[$map['manufacturer']], 'value' => $data[$map['manufacturer']]];
            }
        }
        fclose($handle);

        $fields_value = ElegantalEasyImportCategoryMap::getCategoryMappingByRule($this->model->id);
        $fields_value['multiple_subcategory_separator'] = $this->model->multiple_subcategory_separator;
        $fields_value['is_associate_all_subcategories'] = $this->model->is_associate_all_subcategories;
        $fields_value['is_first_parent_root_for_categories'] = $this->model->is_first_parent_root_for_categories;
        $selected_categories_allowed = isset($fields_value['categories_allowed']) ? $fields_value['categories_allowed'] : [];
        $selected_categories_disallowed = isset($fields_value['categories_disallowed']) ? $fields_value['categories_disallowed'] : [];
        $selected_categories_map = isset($fields_value['categories_map']) ? $fields_value['categories_map'] : [];
        $file_categories = ElegantalEasyImportCategoryMap::getCategoriesFromTree($file_categories_tree);
        $shop_categories = ElegantalEasyImportCategoryMap::getCategoriesFromTree($shop_categories_tree);
        $fields_value['manufacturers_allowed[]'] = isset($fields_value['manufacturers_allowed']) ? $fields_value['manufacturers_allowed'] : [];
        $fields_value['manufacturers_disallowed[]'] = isset($fields_value['manufacturers_disallowed']) ? $fields_value['manufacturers_disallowed'] : [];

        $categories_allowed_tree = new HelperTreeCategories('elegantal_categories_allowed');
        $categories_allowed_tree->setInputName('categories_allowed')
            ->setUseSearch(true)
            ->setUseCheckBox(true)
            ->setData($file_categories_tree)
            ->setRootCategory($rootCategory->id)
            ->setSelectedCategories($selected_categories_allowed);

        $categories_disallowed_tree = new HelperTreeCategories('elegantal_categories_disallowed');
        $categories_disallowed_tree->setInputName('categories_disallowed')
            ->setUseSearch(true)
            ->setUseCheckBox(true)
            ->setData($file_categories_tree)
            ->setRootCategory($rootCategory->id)
            ->setSelectedCategories($selected_categories_disallowed);

        $inputs = [
            [
                'type' => 'select',
                'label' => $this->l('Multiple subcategory separator'),
                'name' => 'multiple_subcategory_separator',
                'options' => [
                    'query' => [
                        ['key' => '', 'value' => ' '],
                        ['key' => '|', 'value' => '|'],
                        ['key' => ',', 'value' => ','],
                        ['key' => ';', 'value' => ';'],
                        ['key' => ':', 'value' => ':'],
                        ['key' => '#', 'value' => '#'],
                        ['key' => '*', 'value' => '*'],
                        ['key' => '-', 'value' => '-'],
                        ['key' => '_', 'value' => '_'],
                        ['key' => '/', 'value' => '/'],
                        ['key' => '\\', 'value' => '\\'],
                        ['key' => '>', 'value' => '>'],
                        ['key' => '>>', 'value' => '>>'],
                        ['key' => '>>>', 'value' => '>>>'],
                        ['key' => '->', 'value' => '->'],
                        ['key' => '=>', 'value' => '=>'],
                    ],
                    'id' => 'key',
                    'name' => 'value',
                ],
                'hint' => $this->l('For example') . ':  Home/Fashion/Men, Home/Fashion/Men/T-Shirt, Home/Fashion/Men/T-Shirt/Polo. ' . $this->l('According to this example, you should select /'),
                'desc' => $this->l('Select separator that is used to separate subcategories.') . ' ' . $this->l('For example, if your categories are written like the following:') . ' Home/Fashion/Men, Home/Fashion/Men/T-Shirt, Home/Fashion/Men/T-Shirt/Polo. ' . $this->l('According to this example, you should select /') . ' ' . $this->l('NOTE that this is DIFFERENT than Multiple Value Separator.') . ' ' . $this->l('In this example, Multiple Value Separator is a comma.'),
            ],
            [
                'type' => (_PS_VERSION_ < '1.6') ? 'el_switch' : 'switch',
                'label' => $this->l('Associate products with all subcategories'),
                'name' => 'is_associate_all_subcategories',
                'is_bool' => true,
                'values' => [
                    [
                        'id' => 'is_associate_all_subcategories_on',
                        'value' => 1,
                        'label' => $this->l('Yes'),
                    ],
                    [
                        'id' => 'is_associate_all_subcategories_off',
                        'value' => 0,
                        'label' => $this->l('No'),
                    ],
                ],
                'hint' => $this->l('For example') . ':  Home/Fashion/Men, Home/Fashion/Men/T-Shirt. ' . sprintf($this->l('In this example, product will be associated with %s and %s categories as well.'), 'Home', 'Fashion'),
                'desc' => $this->l('If enabled, this option will make product be associated with all subcategories in the categories path.') . ' ' . $this->l('If you want to associate product only with last categories in subcategory path, disable this option.') . ' ' . $this->l('For example') . ':  Home/Fashion/Men, Home/Fashion/Men/T-Shirt. ' . sprintf($this->l('In this example, product will be associated with %s and %s categories as well.'), 'Home', 'Fashion'),
            ],
            [
                'type' => (_PS_VERSION_ < '1.6') ? 'el_switch' : 'switch',
                'label' => $this->l('Parent of first category is Root category'),
                'name' => 'is_first_parent_root_for_categories',
                'is_bool' => true,
                'values' => [
                    [
                        'id' => 'is_first_parent_root_for_categories_on',
                        'value' => 1,
                        'label' => $this->l('Yes'),
                    ],
                    [
                        'id' => 'is_first_parent_root_for_categories_off',
                        'value' => 0,
                        'label' => $this->l('No'),
                    ],
                ],
                'hint' => $this->l('Disable this option if your categories tree does not start from Root category.'),
                'desc' => $this->l('You need to enable this option if categories are in hierarchical order as parent-child tree under Root category.'),
            ],
            [
                'type' => 'elegantal_categories',
                'label' => $this->l('Allowed categories from import file'),
                'name' => 'categories_allowed',
                'categories_tree' => $categories_allowed_tree->render(),
                'hint' => $this->l('Products will be imported only from selected categories.') . ' ' . ($selected_categories_allowed ? $this->l('Currently selected categories are:') . ' ' . implode(' ' . $multiple_value_separator . ' ', $selected_categories_allowed) : null),
                'desc' => $this->l('Select categories that you want to allow for import.') . ' ' . $this->l('If you select categories here, the products will be imported only from selected categories.') . ' ' . $this->l('Leave this empty if you want to import products from all categories.'),
            ],
            [
                'type' => 'elegantal_categories',
                'label' => $this->l('Disallowed categories from import file'),
                'name' => 'categories_disallowed',
                'categories_tree' => $categories_disallowed_tree->render(),
                'hint' => $this->l('Products of selected categories will not be imported.') . ' ' . ($selected_categories_disallowed ? $this->l('Currently selected categories are:') . ' ' . implode(' ' . $multiple_value_separator . ' ', $selected_categories_disallowed) : null),
                'desc' => $this->l('Select categories that you want to disallow for import.') . ' ' . $this->l('If you select categories here, the products of selected categories will not be imported.') . ' ' . $this->l('Leave this empty if you want to import products from all categories.'),
            ],
            [
                'type' => 'elegantal_categories_map',
                'label' => $this->l('Categories Mapping'),
                'name' => 'categories_mapping',
                'file_categories' => $file_categories,
                'shop_categories' => $shop_categories,
                'selected_categories_map' => $selected_categories_map,
                'hint' => $this->l('You can match categories from the import file with the categories of the shop.') . ' ' . $this->l('Selected categories of the shop will be used instead of categories of the import file during the import process.'),
            ]];

        if ($manufacturers_of_file_for_select) {
            $inputs[] = [
                'type' => 'select',
                'label' => $this->l('Allowed brands from import file'),
                'name' => 'manufacturers_allowed[]',
                'multiple' => true,
                'options' => [
                    'query' => $manufacturers_of_file_for_select,
                    'id' => 'key',
                    'name' => 'value',
                ],
                'desc' => $this->l('Only products of selected brands will be imported.') . ' ' . $this->l('You can select multiple items with SHIFT + LEFT CLICK.'),
            ];
            $inputs[] = [
                'type' => 'select',
                'label' => $this->l('Disallowed brands from import file'),
                'name' => 'manufacturers_disallowed[]',
                'multiple' => true,
                'options' => [
                    'query' => $manufacturers_of_file_for_select,
                    'id' => 'key',
                    'name' => 'value',
                ],
                'desc' => $this->l('Products of selected brands will not be imported.') . ' ' . $this->l('You can select multiple items with SHIFT + LEFT CLICK.'),
            ];
        }

        $fields_form = [
            'form' => [
                'legend' => [
                    'title' => $this->l('Step') . ' 2: ' . $this->l('Manage mapping of categories') . ' - "' . $this->model->name . '"',
                    'icon' => 'icon-edit',
                ],
                'input' => $inputs,
                'submit' => [
                    'title' => ($this->model->is_cron ? $this->l('Save') . ' & CRON' : $this->l('Save & Import')),
                    'name' => 'submitAndNext',
                ],
                'buttons' => [
                    [
                        'title' => $this->l('Save & Stay'),
                        'name' => 'submitAndStay',
                        'type' => 'submit',
                        'class' => 'pull-right',
                        'icon' => 'process-icon-save',
                    ],
                    [
                        'href' => $this->getAdminUrl(),
                        'title' => $this->l('Main Page'),
                        'class' => 'pull-left',
                        'icon' => 'process-icon-back',
                    ],
                    [
                        'href' => $this->getAdminUrl(['event' => 'importMapping', 'id_elegantaleasyimport' => $this->model->id]),
                        'title' => $this->l('Back'),
                        'class' => 'pull-left',
                        'icon' => 'process-icon-back',
                    ],
                ],
            ],
        ];

        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->submit_action = 'submitManageCategory';
        $helper->name_controller = 'elegantalBootstrapWrapper';
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->module = $this;
        $helper->identifier = $this->identifier;
        $helper->currentIndex = $this->getAdminUrl(['event' => 'importManageCategory', 'id_elegantaleasyimport' => $this->model->id]);
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = [
            'base_url' => $this->context->shop->getBaseURL(),
            'language' => [
                'id_lang' => $lang->id,
                'iso_code' => $lang->iso_code,
            ],
            'fields_value' => $fields_value,
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        ];

        Media::addJsDef([
            'elegantal_max_input_vars' => (int) ini_get('max_input_vars'),
        ]);

        $this->context->smarty->assign(
            [
                'adminUrl' => $this->getAdminUrl(),
                'model' => $this->model->getAttributes(),
            ]
        );

        return $this->importRenderSteps(2) . $helper->generateForm([$fields_form]);
    }

    /**
     * Process import page
     *
     * @return string HTML
     */
    protected function import()
    {
        $limit = 1;
        if (Tools::getValue('ajax')) {
            $result = [];
            try {
                if ($this->model) {
                    $result['success'] = true;
                    if (Tools::getValue('saveCsvRows')) {
                        $result['count'] = $this->model->saveCsvRows();
                    } else {
                        $data = $this->model->import($limit);
                        $result = array_merge($result, $data);
                    }
                } else {
                    $result['success'] = false;
                    $result['message'] = $this->l('Record not found.');
                }
            } catch (Exception $e) {
                $result['success'] = false;
                $result['message'] = $e->getMessage();
            }
            echo json_encode($result);
            exit;
        }

        if (!$this->model) {
            $this->setRedirectAlert($this->l('Record not found.'), 'error');
            $this->redirectAdmin();
        }

        if ($this->model->is_cron) {
            $this->redirectAdmin([
                'event' => 'importCronInfo',
                'id_elegantaleasyimport' => $this->model->id,
            ]);
        }

        $this->context->smarty->assign(
            [
                'adminUrl' => $this->getAdminUrl(),
                'model' => $this->model->getAttributes(),
                'limit' => $limit,
            ]
        );

        return $this->importRenderSteps(3) . $this->display(__FILE__, 'views/templates/admin/import.tpl');
    }

    protected function importLatestFile()
    {
        if (!$this->model) {
            $this->setRedirectAlert($this->l('Record not found.'), 'error');
            $this->redirectAdmin();
        }
        $this->model->downloadImportFile();
        $this->redirectAdmin([
            'event' => 'import',
            'id_elegantaleasyimport' => $this->model->id,
        ]);
    }

    protected function importCronInfo()
    {
        $cron_cpanel_doc = null;
        $documentation_urls = $this->getDocumentationUrls(['pdf']);
        foreach ($documentation_urls as $doc => $url) {
            if ($doc == 'setup_cron_job_in_cpanel.pdf') {
                $cron_cpanel_doc = $url;
                break;
            }
        }
        $this->context->smarty->assign(
            [
                'adminUrl' => $this->getAdminUrl(),
                'cron_url' => $this->getControllerUrl('import', ['id' => $this->model->id]),
                'cron_command' => realpath(dirname(__FILE__) . '/commands/cron_import.php') . ' ' . $this->model->id,
                'cron_cpanel_doc' => $cron_cpanel_doc,
            ]
        );

        return $this->importRenderSteps(3) . $this->display(__FILE__, 'views/templates/admin/import_cron.tpl');
    }

    protected function getFindProductsByForSelect()
    {
        $result = [
            ['key' => 'id', 'value' => 'ID'],
            ['key' => 'reference', 'value' => 'Reference'],
            ['key' => 'ean', 'value' => 'EAN'],
            ['key' => 'supplier_reference', 'value' => 'Supplier Reference'],
        ];
        if (_PS_VERSION_ >= '1.7.7.0') {
            $result[] = ['key' => 'mpn', 'value' => 'MPN'];
        }
        $result[] = ['key' => 'isbn', 'value' => 'ISBN'];
        $result[] = ['key' => 'combination_reference', 'value' => 'Combination Reference'];
        $result[] = ['key' => 'combination_ean', 'value' => 'Combination EAN'];

        return $result;
    }

    protected function getFindCombinationsByForSelect()
    {
        $result = [
            ['key' => 'combination_id', 'value' => 'Combination ID'],
            ['key' => 'combination_reference', 'value' => 'Combination Reference'],
            ['key' => 'combination_ean', 'value' => 'Combination EAN'],
            ['key' => 'combination_supplier_reference', 'value' => 'Combination Supplier Reference'],
        ];
        if (_PS_VERSION_ >= '1.7.7.0') {
            $result[] = ['key' => 'combination_mpn', 'value' => 'Combination MPN'];
        }
        $result[] = ['key' => 'combination_isbn', 'value' => 'Combination ISBN'];

        return $result;
    }

    protected function getCurrenciesForSelect()
    {
        $result = [];
        $defaultCurrency = Currency::getDefaultCurrency();
        $result[] = ['key' => $defaultCurrency->id, 'value' => Tools::strtoupper($defaultCurrency->iso_code)];
        $currencies = Currency::getCurrencies();
        foreach ($currencies as $currency) {
            if ($currency['id_currency'] != $defaultCurrency->id) {
                $result[] = ['key' => $currency['id_currency'], 'value' => Tools::strtoupper($currency['iso_code'])];
            }
        }

        return $result;
    }

    protected function getSuppliersForSelect($is_multiple = true)
    {
        $result = [];
        if ($is_multiple) {
            $result[] = ['key' => 'all', 'value' => $this->l('ALL SUPPLIERS')];
        } else {
            $result[] = ['key' => '', 'value' => ' '];
        }
        $suppliers = Supplier::getSuppliers(false, null, false);
        foreach ($suppliers as $s) {
            $result[] = ['key' => $s['id_supplier'], 'value' => $s['name']];
        }

        return $result;
    }

    protected function getManufacturersForSelect()
    {
        $result = [['key' => 'all', 'value' => $this->l('ALL MANUFACTURERS')]];
        if ($manufacturers = Manufacturer::getManufacturers()) {
            $ids = [];
            foreach ($manufacturers as $manufacturer) {
                if (!in_array($manufacturer['id_manufacturer'], $ids)) {
                    $ids[] = $manufacturer['id_manufacturer'];
                    $result[] = ['key' => $manufacturer['id_manufacturer'], 'value' => $manufacturer['name']];
                }
            }
        }

        return $result;
    }

    protected function getWarehousesForSelect()
    {
        $result = [['key' => 'all', 'value' => $this->l('ALL WAREHOUSES')]];
        $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'warehouse`';
        $warehouses = Db::getInstance()->executeS($sql);
        if ($warehouses) {
            foreach ($warehouses as $warehouse) {
                $result[] = ['key' => $warehouse['id_warehouse'], 'value' => $warehouse['name']];
            }
        }

        return $result;
    }

    protected function getCsvHeaderForSelect($csv_header)
    {
        $result = [['key' => '-1', 'value' => $this->l('Ignore this column')]];
        if ($csv_header && is_array($csv_header)) {
            foreach ($csv_header as $key => $value) {
                $result[] = ['key' => $key, 'value' => $value];
            }
        }

        return $result;
    }

    protected function exportList()
    {
        $settings = $this->getSettings();
        // Pagination data
        $total = ElegantalEasyImportExport::model()->countAll();
        $limit = 30;
        $pages = ceil($total / $limit);
        $currentPage = (int) Tools::getValue('page', 1);
        $currentPage = ($currentPage > $pages) ? $pages : $currentPage;
        $halfVisibleLinks = 5;
        $offset = ($total > $limit) ? ($currentPage - 1) * $limit : 0;

        // Sorting records
        $sortableColumns = [
            'id_elegantaleasyimport_export',
            'name',
            'entity',
            'file_path',
            'last_export_date',
            'active',
        ];

        $orderBy = in_array(Tools::getValue('orderBy'), $sortableColumns) ? Tools::getValue('orderBy') : 'id_elegantaleasyimport_export';
        $orderType = Tools::getValue('orderType') == 'asc' ? 'asc' : 'desc';

        $models = ElegantalEasyImportExport::model()->findAll([
            'order' => $orderBy . ' ' . $orderType,
            'limit' => $limit,
            'offset' => $offset,
        ]);

        foreach ($models as &$model) {
            if (!empty($model['last_export_date']) && $model['last_export_date'] != '0000-00-00 00:00:00' && $model['last_export_date'] != '0000-00-00') {
                $model['download_link'] = $this->getControllerUrl('export', ['action' => 'download', 'id' => $model['id_elegantaleasyimport_export'], 'secure_key' => $settings['security_token_key'], 'filename' => $model['entity'] . $model['id_elegantaleasyimport_export'] . '.' . $model['file_format']]);
            } else {
                $model['last_export_date'] = '';
                $model['download_link'] = '';
            }
        }

        $this->context->smarty->assign(
            [
                'models' => $models,
                'adminUrl' => $this->getAdminUrl(),
                'pages' => $pages,
                'currentPage' => $currentPage,
                'halfVisibleLinks' => $halfVisibleLinks,
                'orderBy' => $orderBy,
                'orderType' => $orderType,
            ]
        );

        return $this->display(__FILE__, 'views/templates/admin/export_list.tpl');
    }

    protected function exportRenderSteps($step, $model)
    {
        $this->context->smarty->assign(
            [
                'adminUrl' => $this->getAdminUrl(),
                'model' => $model->getAttributes(),
                'step' => $step,
            ]
        );

        return $this->display(__FILE__, 'views/templates/admin/export_steps.tpl');
    }

    protected function exportEdit()
    {
        $html = '';

        $model = null;
        if (Tools::getValue('id_elegantaleasyimport_export')) {
            $model = new ElegantalEasyImportExport(Tools::getValue('id_elegantaleasyimport_export'));
            if (!Validate::isLoadedObject($model)) {
                $this->setRedirectAlert($this->l('Record not found.'), 'error');
                $this->redirectAdmin(['event' => 'exportList']);
            }
        }
        if (!$model) {
            $model = new ElegantalEasyImportExport();
        }

        if ($this->isPostRequest()) {
            $old_file_path = $model->file_path;

            // Validate submitted data
            $errors = $model->validateAndAssignModelAttributes();

            if ($model->file_path && Tools::substr($model->file_path, 0, 1) != '/') {
                $errors[] = $this->l('You should enter absolute path for the File Path which should start with /');
            } elseif ($model->file_path && (!is_file($model->file_path) || !filesize($model->file_path) || filesize($model->file_path) < 5) && !file_put_contents($model->file_path, ' ')) {
                $errors[] = 'File Path you specified is not writable. ' . $model->file_path . ' Please make sure you enter file path that the module has permissions to write.';
            }

            if ($model->price_range) {
                $model->price_range = str_replace(' ', '', $model->price_range);
                if (preg_match("/^([0-9]+(\.[0-9]{1,})?)-([0-9]+(\.[0-9]{1,})?)$/", $model->price_range, $match)) {
                    if ($match[1] > $match[3]) {
                        $model->price_range = '';
                    }
                } else {
                    $model->price_range = '';
                }
            }
            if ($model->quantity_range) {
                $model->quantity_range = str_replace(' ', '', $model->quantity_range);
                if (preg_match("/^(\d+)-(\d+)$/", $model->quantity_range, $match)) {
                    if ($match[1] > $match[2]) {
                        $model->quantity_range = '';
                    }
                } else {
                    $model->quantity_range = '';
                }
            }

            if (!Tools::getValue('category_ids')) {
                $model->category_ids = null;
            }
            if (!Tools::getValue('disallowed_category_ids')) {
                $model->disallowed_category_ids = null;
            }

            if (empty($errors)) {
                $result = empty($model->id) ? $model->add() : $model->update();
                if ($result) {
                    if ($old_file_path != $model->file_path) {
                        @unlink($old_file_path);
                    }
                    if (Tools::isSubmit('submitAndStay') && !Tools::isSubmit('submitAndNext')) {
                        $this->setRedirectAlert($this->l('Rule saved successfully.'), 'success');
                        $this->redirectAdmin([
                            'event' => 'exportEdit',
                            'id_elegantaleasyimport_export' => $model->id,
                        ]);
                    } else {
                        $this->redirectAdmin([
                            'event' => 'exportColumns',
                            'id_elegantaleasyimport_export' => $model->id,
                        ]);
                    }
                } else {
                    $html .= $this->displayError($this->l('Rule could not be saved.') . ' ' . Db::getInstance()->getMsgError());
                }
            } else {
                $html .= $this->displayError(nl2br(implode(PHP_EOL, $errors)));
            }
        }

        $fields_value = $model->getAttributes();
        $fields_value['shop_ids[]'] = ElegantalEasyImportTools::unstorable($fields_value['shop_ids']);
        $fields_value['category_ids'] = ElegantalEasyImportTools::unstorable($fields_value['category_ids']);
        $fields_value['disallowed_category_ids'] = ElegantalEasyImportTools::unstorable($fields_value['disallowed_category_ids']);
        $fields_value['supplier_ids[]'] = ElegantalEasyImportTools::unstorable($fields_value['supplier_ids']);
        $fields_value['manufacturer_ids[]'] = ElegantalEasyImportTools::unstorable($fields_value['manufacturer_ids']);
        $fields_value['warehouse_ids[]'] = ElegantalEasyImportTools::unstorable($fields_value['warehouse_ids']);

        // Default Values
        if (!$fields_value['id_elegantaleasyimport_export'] && !$this->isPostRequest()) {
            $fields_value['entity'] = Tools::getValue('export_entity');
            $fields_value['currency_id'] = Currency::getDefaultCurrency()->id;
            $fields_value['shop_ids[]'] = ['all'];
            $fields_value['file_path'] = realpath(dirname(__FILE__) . '/tmp') . '/export_' . date('d-m-Y') . '_' . date('His') . '.csv';
            $fields_value['supplier_ids[]'] = ['all'];
            $fields_value['manufacturer_ids[]'] = ['all'];
            $fields_value['warehouse_ids[]'] = ['all'];
            $fields_value['xml_root_node'] = 'PRODUCTS';
            $fields_value['xml_item_node'] = 'PRODUCT';
            $fields_value['active'] = 1;
        }

        // Allowed/Disallowed ategory input
        $rootCategory = Category::getRootCategory();
        $categories_allowed_tree = new HelperTreeCategories('elegantal_categories_allowed');
        $categories_allowed_tree->setInputName('category_ids')
            ->setUseSearch(true)
            ->setUseCheckBox(true)
            ->setRootCategory($rootCategory->id)
            ->setSelectedCategories($fields_value['category_ids']);
        $categories_disallowed_tree = new HelperTreeCategories('elegantal_categories_disallowed');
        $categories_disallowed_tree->setInputName('disallowed_category_ids')
            ->setUseSearch(true)
            ->setUseCheckBox(true)
            ->setRootCategory($rootCategory->id)
            ->setSelectedCategories($fields_value['disallowed_category_ids']);

        $inputs = [
            [
                'type' => 'text',
                'label' => $this->l('Name'),
                'name' => 'name',
                'desc' => $this->l('Name is for your reference only.'),
            ],
            [
                'type' => 'select',
                'label' => $this->l('Export Entity'),
                'name' => 'entity',
                'options' => [
                    'query' => [
                        ['key' => 'product', 'value' => $this->l('Products')],
                        ['key' => 'combination', 'value' => $this->l('Combinations')],
                    ],
                    'id' => 'key',
                    'name' => 'value',
                ],
                'desc' => $this->l('Choose what you want to export.'),
            ],
            [
                'type' => 'text',
                'label' => $this->l('Full path to export file'),
                'name' => 'file_path',
                'desc' => $this->l('Enter absolute file path where exported file should be saved.') . ' ' . $this->l('For example') . ': ' . realpath(dirname(__FILE__) . '/tmp') . '/export_123.csv',
            ],
            [
                'type' => 'select',
                'label' => $this->l('File format'),
                'name' => 'file_format',
                'options' => [
                    'query' => [
                        ['key' => 'csv', 'value' => 'CSV'],
                        ['key' => 'xml', 'value' => 'XML'],
                        ['key' => 'json', 'value' => 'JSON'],
                        ['key' => 'xls', 'value' => 'XLS'],
                        ['key' => 'xlsx', 'value' => 'XLSX'],
                        ['key' => 'txt', 'value' => 'TXT'],
                        ['key' => 'ods', 'value' => 'ODS'],
                    ],
                    'id' => 'key',
                    'name' => 'value',
                ],
                'desc' => $this->l('Choose file format to use for this export.'),
            ],
            [
                'type' => (_PS_VERSION_ < '1.6') ? 'el_switch' : 'switch',
                'label' => $this->l('Compress file to ZIP'),
                'name' => 'is_compress_file_to_zip',
                'is_bool' => true,
                'values' => [
                    [
                        'id' => 'is_compress_file_to_zip_on',
                        'value' => 1,
                        'label' => $this->l('Yes'),
                    ],
                    [
                        'id' => 'is_compress_file_to_zip_off',
                        'value' => 0,
                        'label' => $this->l('No'),
                    ],
                ],
                'desc' => $this->l('Compress exported file to zip archive. It will be located on the same location as the export file.'),
            ],
            [
                'type' => 'select',
                'label' => $this->l('CSV Delimiter'),
                'name' => 'csv_delimiter',
                'options' => [
                    'query' => [
                        ['key' => ',', 'value' => ','],
                        ['key' => ';', 'value' => ';'],
                        ['key' => '|', 'value' => '|'],
                        ['key' => "\t", 'value' => 'Tab'],
                        ['key' => ' ', 'value' => 'Space'],
                    ],
                    'id' => 'key',
                    'name' => 'value',
                ],
                'desc' => $this->l('Select a character used as a separator for CSV file.'),
            ],
            [
                'type' => 'text',
                'label' => $this->l('XML root node'),
                'name' => 'xml_root_node',
                'desc' => $this->l('Enter a name of the root node for the XML file.') . ' ' . $this->l('You can write multiple root nodes separated by') . ' (->) ' . $this->l('For example') . ': shop->offers',
            ],
            [
                'type' => 'text',
                'label' => $this->l('XML item node'),
                'name' => 'xml_item_node',
                'desc' => $this->l('Enter a name of the item node for the XML file.') . ' ' . $this->l('For example') . ': offer',
            ],
            [
                'type' => 'select',
                'label' => $this->l('Multiple value separator'),
                'name' => 'multiple_value_separator',
                'options' => [
                    'query' => [
                        ['key' => '|', 'value' => '|'],
                        ['key' => ',', 'value' => ','],
                        ['key' => ';', 'value' => ';'],
                        ['key' => ':', 'value' => ':'],
                        ['key' => '#', 'value' => '#'],
                        ['key' => '*', 'value' => '*'],
                        ['key' => '-', 'value' => '-'],
                        ['key' => '_', 'value' => '_'],
                        ['key' => '/', 'value' => '/'],
                        ['key' => '\\', 'value' => '\\'],
                        ['key' => '>', 'value' => '>'],
                        ['key' => '>>', 'value' => '>>'],
                        ['key' => '>>>', 'value' => '>>>'],
                        ['key' => '->', 'value' => '->'],
                        ['key' => '=>', 'value' => '=>'],
                    ],
                    'id' => 'key',
                    'name' => 'value',
                ],
                'desc' => $this->l('Select a character used as separator for list type values.') . ' ' . $this->l('For example') . ':  image1.jpg|image2.jpg|image3.jpg',
            ],
            [
                'type' => 'select',
                'label' => $this->l('Multiple subcategory separator'),
                'name' => 'multiple_subcategory_separator',
                'options' => [
                    'query' => [
                        ['key' => '', 'value' => ' '],
                        ['key' => '>', 'value' => '>'],
                        ['key' => '=>', 'value' => '=>'],
                        ['key' => '->', 'value' => '->'],
                        ['key' => '>>', 'value' => '>>'],
                        ['key' => '>>>', 'value' => '>>>'],
                        ['key' => '|', 'value' => '|'],
                        ['key' => ',', 'value' => ','],
                        ['key' => ';', 'value' => ';'],
                        ['key' => ':', 'value' => ':'],
                        ['key' => '#', 'value' => '#'],
                        ['key' => '*', 'value' => '*'],
                        ['key' => '-', 'value' => '-'],
                        ['key' => '_', 'value' => '_'],
                        ['key' => '/', 'value' => '/'],
                        ['key' => '\\', 'value' => '\\'],
                    ],
                    'id' => 'key',
                    'name' => 'value',
                ],
                'desc' => $this->l('Select separator that is used to separate subcategories.') . ' ' . $this->l('For example') . ': ' . $this->l('If your categories are written like this:') . ' Home/Fashion/Men, Home/Fashion/Men/T-Shirt, Home/Fashion/Men/T-Shirt/Polo. ' . $this->l('According to this example, you should select /') . ' ' . $this->l('NOTE that this is DIFFERENT than Multiple Value Separator.') . ' ' . $this->l('In this example, Multiple Value Separator is a comma.'),
            ],
        ];
        $currencies = $this->getCurrenciesForSelect();
        if (count($currencies) > 1) {
            $inputs[] = [
                'type' => 'select',
                'label' => $this->l('Currency'),
                'name' => 'currency_id',
                'options' => [
                    'query' => $currencies,
                    'id' => 'key',
                    'name' => 'value',
                ],
                'desc' => $this->l('Select currency to use for the product price.'),
            ];
        } else {
            $inputs[] = [
                'type' => 'hidden',
                'name' => 'currency_id',
            ];
        }
        $shops = Shop::getShops();
        if (Shop::isFeatureActive() && is_array($shops) && count($shops) > 1) {
            $shops_for_select = [['key' => 'all', 'value' => $this->l('ALL SHOPS')]];
            foreach ($shops as $shop) {
                $shops_for_select[] = ['key' => $shop['id_shop'], 'value' => $shop['name']];
            }
            $inputs[] = [
                'type' => 'select',
                'label' => $this->l('Shops'),
                'name' => 'shop_ids[]',
                'multiple' => true,
                'options' => [
                    'query' => $shops_for_select,
                    'id' => 'key',
                    'name' => 'value',
                ],
                'desc' => $this->l('Select shop(s) from which products should be exported.'),
            ];
        }
        $inputs[] = [
            'type' => (_PS_VERSION_ < '1.6') ? 'el_switch' : 'switch',
            'label' => $this->l('Export features in separate columns'),
            'name' => 'features_in_separate_columns',
            'is_bool' => true,
            'values' => [
                [
                    'id' => 'features_in_separate_columns_on',
                    'value' => 1,
                    'label' => $this->l('Yes'),
                ],
                [
                    'id' => 'features_in_separate_columns_off',
                    'value' => 0,
                    'label' => $this->l('No'),
                ],
            ],
            'desc' => $this->l('Each feature will be exported in separate columns.'),
        ];
        $inputs[] = [
            'type' => (_PS_VERSION_ < '1.6') ? 'el_switch' : 'switch',
            'label' => $this->l('Export images in separate columns'),
            'name' => 'images_in_separate_columns',
            'is_bool' => true,
            'values' => [
                [
                    'id' => 'images_in_separate_columns_on',
                    'value' => 1,
                    'label' => $this->l('Yes'),
                ],
                [
                    'id' => 'images_in_separate_columns_off',
                    'value' => 0,
                    'label' => $this->l('No'),
                ],
            ],
            'desc' => $this->l('Each product image will be exported in separate columns.'),
        ];
        $inputs[] = [
            'type' => (_PS_VERSION_ < '1.6') ? 'el_switch' : 'switch',
            'label' => $this->l('Add currency code to prices'),
            'name' => 'add_currency_code_to_prices',
            'is_bool' => true,
            'values' => [
                [
                    'id' => 'add_currency_code_to_prices_on',
                    'value' => 1,
                    'label' => $this->l('Yes'),
                ],
                [
                    'id' => 'add_currency_code_to_prices_off',
                    'value' => 0,
                    'label' => $this->l('No'),
                ],
            ],
            'desc' => $this->l('Price columns will include currency code.'),
        ];
        $inputs[] = [
            'type' => (_PS_VERSION_ < '1.6') ? 'el_switch' : 'switch',
            'label' => $this->l('Add unit of measurement to dimensions'),
            'name' => 'add_unit_for_dimensions',
            'is_bool' => true,
            'values' => [
                [
                    'id' => 'add_unit_for_dimensions_on',
                    'value' => 1,
                    'label' => $this->l('Yes'),
                ],
                [
                    'id' => 'add_unit_for_dimensions_off',
                    'value' => 0,
                    'label' => $this->l('No'),
                ],
            ],
            'desc' => $this->l('Unit of measurement will be included next to dimensions and weight.') . ' ' . $this->l('For example') . ': ' . $this->l('width') . ', ' . $this->l('height') . ', ' . $this->l('length') . ', ' . $this->l('weight'),
        ];
        $inputs[] = [
            'type' => (_PS_VERSION_ < '1.6') ? 'el_switch' : 'switch',
            'label' => $this->l('Export root category in categories list'),
            'name' => 'root_category_included',
            'is_bool' => true,
            'values' => [
                [
                    'id' => 'root_category_included_on',
                    'value' => 1,
                    'label' => $this->l('Yes'),
                ],
                [
                    'id' => 'root_category_included_off',
                    'value' => 0,
                    'label' => $this->l('No'),
                ],
            ],
            'desc' => $this->l('Root category will be included in categories list during export.'),
        ];
        $inputs[] = [
            'type' => 'elegantal_categories',
            'label' => $this->l('Allowed Categories'),
            'name' => 'category_ids',
            'categories_tree' => $categories_allowed_tree->render(),
            'desc' => $this->l('Select categories from which you want to export products. You can leave it empty to export products from all categories.'),
        ];
        $inputs[] = [
            'type' => 'elegantal_categories',
            'label' => $this->l('Disallowed Categories'),
            'name' => 'disallowed_category_ids',
            'categories_tree' => $categories_disallowed_tree->render(),
            'desc' => $this->l("Select categories from which you don't want to export products.") . ' ' . $this->l('Products of these categories will not be exported.'),
        ];
        $inputs[] = [
            'type' => 'select',
            'label' => $this->l('Suppliers'),
            'name' => 'supplier_ids[]',
            'multiple' => true,
            'options' => [
                'query' => $this->getSuppliersForSelect(),
                'id' => 'key',
                'name' => 'value',
            ],
            'desc' => $this->l('Products of selected suppliers will be exported.') . ' ' . $this->l('You can select multiple items with SHIFT + LEFT CLICK.'),
        ];
        $inputs[] = [
            'type' => 'select',
            'label' => $this->l('Manufacturers'),
            'name' => 'manufacturer_ids[]',
            'multiple' => true,
            'options' => [
                'query' => $this->getManufacturersForSelect(),
                'id' => 'key',
                'name' => 'value',
            ],
            'desc' => $this->l('Products of selected manufacturers will be exported.') . ' ' . $this->l('You can select multiple items with SHIFT + LEFT CLICK.'),
        ];
        $inputs[] = [
            'type' => 'select',
            'label' => $this->l('Product status'),
            'name' => 'product_status',
            'options' => [
                'query' => [
                    ['key' => '2', 'value' => 'Both active and inactive products'],
                    ['key' => '1', 'value' => 'Only active products'],
                    ['key' => '0', 'value' => 'Only inactive products'],
                ],
                'id' => 'key',
                'name' => 'value',
            ],
            'desc' => $this->l('Select whether you want to export only active products or only disabled products or both.'),
        ];
        $inputs[] = [
            'type' => 'text',
            'label' => $this->l('Price range'),
            'name' => 'price_range',
            'desc' => $this->l('Only products that have price in specified range will be exported.') . ' ' . $this->l('You need to enter it in this format:') . ' 100 - 500',
        ];
        $inputs[] = [
            'type' => 'text',
            'label' => $this->l('Quantity range'),
            'name' => 'quantity_range',
            'desc' => $this->l('Only products that have quantity in specified range will be exported.') . ' ' . $this->l('You need to enter it in this format:') . ' 100 - 500',
        ];
        if (_PS_VERSION_ < '1.7') {
            $inputs[] = [
                'type' => 'select',
                'label' => $this->l('Warehouses'),
                'name' => 'warehouse_ids[]',
                'multiple' => true,
                'options' => [
                    'query' => $this->getWarehousesForSelect(),
                    'id' => 'key',
                    'name' => 'value',
                ],
                'desc' => $this->l('Product stock (Quantity) will be exported from selected warehouse(s).') . ' ' . $this->l('You can select multiple items with SHIFT + LEFT CLICK.'),
            ];
        }
        $inputs[] = [
            'type' => 'text',
            'label' => $this->l('Export products updated within specified minute'),
            'name' => 'export_products_updated_within_minute',
            'desc' => $this->l('Only products updated within specified minute will be exported.') . ' 60 minutes = 1 hour.',
        ];
        $inputs[] = [
            'type' => 'text',
            'label' => $this->l('Exclude products by ID'),
            'name' => 'exclude_product_ids',
            'desc' => $this->l('Enter product IDs separated by comma. For example: 8,9,10,25. These products will be excluded from export.'),
        ];
        $inputs[] = [
            'type' => 'text',
            'label' => $this->l('Price Modifier'),
            'name' => 'price_modifier',
            'desc' => $this->l('You can use arithmetic formula which will be used to modify product price.') . ' ' . $this->l('Examples') . ': *2; /3; +1.11; -0.5 ' . $this->l('You can also create different formula based on price range.') . ' ' . $this->l('For example') . ': ' . $this->l('If you want to add 15% for products that have price from 0 to 100 and 20% for products that have price from 101 to above, you can write this formula:') . ' [0 - 100]*1.15; [101 - #]*1.20',
        ];
        $inputs[] = [
            'type' => 'text',
            'label' => $this->l('Email to send notification'),
            'name' => 'email_to_send_notification',
            'desc' => $this->l('You can enter an email to which a notification will be sent when CRON finishes exporting.'),
        ];
        $inputs[] = [
            'type' => 'select',
            'label' => $this->l('Order by'),
            'name' => 'order_by',
            'options' => [
                'query' => [
                    ['key' => 'p.id_product', 'value' => $this->l('Product ID')],
                    ['key' => 'pl.name', 'value' => $this->l('Name')],
                    ['key' => 'psh.date_add', 'value' => $this->l('Date added')],
                    ['key' => 'psh.date_upd', 'value' => $this->l('Date updated')],
                    ['key' => 'psh.price', 'value' => $this->l('Price')],
                    ['key' => 'RAND()', 'value' => $this->l('Random')],
                ],
                'id' => 'key',
                'name' => 'value',
            ],
            'desc' => $this->l('Products will be sorted by specified attribute.'),
        ];
        $inputs[] = [
            'type' => 'select',
            'label' => $this->l('Order direction'),
            'name' => 'order_direction',
            'options' => [
                'query' => [
                    ['key' => 'ASC', 'value' => $this->l('From smallest to largest (ASC)')],
                    ['key' => 'DESC', 'value' => $this->l('From largest to smallest (DESC)')],
                ],
                'id' => 'key',
                'name' => 'value',
            ],
            'desc' => $this->l('Sort products in ascending (ASC) or descending (DESC) order.'),
        ];
        $inputs[] = [
            'type' => 'hidden',
            'name' => 'active',
        ];

        $fields_form = [
            'form' => [
                'legend' => [
                    'title' => $this->l('Step') . ' 1: ' . $this->l('Export Settings'),
                    'icon' => 'icon-cloud-upload',
                ],
                'input' => $inputs,
                'submit' => [
                    'title' => $this->l('Save & Next'),
                    'name' => 'submitAndNext',
                ],
                'buttons' => [
                    [
                        'title' => $this->l('Save & Stay'),
                        'name' => 'submitAndStay',
                        'type' => 'submit',
                        'class' => 'pull-right',
                        'icon' => 'process-icon-save',
                    ],
                    [
                        'href' => $this->getAdminUrl(),
                        'title' => $this->l('Main Page'),
                        'class' => 'pull-left',
                        'icon' => 'process-icon-back',
                    ],
                    [
                        'href' => $this->getAdminUrl(['event' => 'exportList']),
                        'title' => $this->l('Back'),
                        'class' => 'pull-left',
                        'icon' => 'process-icon-back',
                    ],
                ],
            ],
        ];

        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->submit_action = 'submitExportEdit';
        $helper->name_controller = 'elegantalBootstrapWrapper';
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->module = $this;
        $helper->identifier = $this->identifier;
        $helper->currentIndex = $this->getAdminUrl(['event' => 'exportEdit', 'id_elegantaleasyimport_export' => $model->id]);
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = [
            'base_url' => $this->context->shop->getBaseURL(),
            'language' => [
                'id_lang' => $lang->id,
                'iso_code' => $lang->iso_code,
            ],
            'fields_value' => $fields_value,
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        ];

        return $this->exportRenderSteps(1, $model) . $html . $helper->generateForm([$fields_form]);
    }

    protected function exportColumns()
    {
        $model = null;
        if (Tools::getValue('id_elegantaleasyimport_export')) {
            $model = new ElegantalEasyImportExport(Tools::getValue('id_elegantaleasyimport_export'));
        }
        if (!Validate::isLoadedObject($model)) {
            $this->setRedirectAlert($this->l('Record not found.'), 'error');
            $this->redirectAdmin(['event' => 'exportList']);
        }

        $columns_with_title = $model->getColumns();
        $columns_keys = array_keys($columns_with_title);
        $model_columns = ElegantalEasyImportTools::unstorable($model->columns);

        if ($this->isPostRequest()) {
            if (Tools::getValue('ajax')) {
                $result = ['success' => true];
                try {
                    $columns = [];
                    $column_override_values = [];
                    foreach ($_POST as $post_key => $post_value) {
                        if (in_array($post_key, $columns_keys) || preg_match("/^custom_column_[\d]+$/", $post_key)) {
                            if ($post_value == 1) {
                                if (Tools::getValue('label_' . $post_key)) {
                                    $columns[$post_key] = Tools::getValue('label_' . $post_key);
                                } elseif (isset($columns_with_title[$post_key])) {
                                    $columns[$post_key] = $columns_with_title[$post_key];
                                } elseif (isset($model_columns[$post_key])) {
                                    $columns[$post_key] = $model_columns[$post_key];
                                } else {
                                    $columns[$post_key] = ucwords(str_replace('_', ' ', $post_key));
                                }
                            }
                            if (Tools::isSubmit('default_' . $post_key)) {
                                $column_override_values[$post_key] = Tools::getValue('default_' . $post_key);
                            } else {
                                $column_override_values[$post_key] = '';
                            }
                        }
                    }

                    // Save columns
                    $model->columns = ElegantalEasyImportTools::storable($columns);
                    $model->column_override_values = ElegantalEasyImportTools::storable($column_override_values);
                    $model->update();
                } catch (Exception $e) {
                    $result = ['success' => false, 'message' => $e->getMessage()];
                }
                echo json_encode($result);
                exit;
            }

            if (Tools::isSubmit('submitAndStay') && !Tools::isSubmit('submitAndNext')) {
                $this->setRedirectAlert($this->l('Rule saved successfully.'), 'success');
                $this->redirectAdmin([
                    'event' => 'exportColumns',
                    'id_elegantaleasyimport_export' => $model->id,
                ]);
            } elseif (Tools::isSubmit('submitAndExport') && !Tools::isSubmit('submitAndNext')) {
                $this->redirectAdmin([
                    'event' => 'export',
                    'id_elegantaleasyimport_export' => $model->id,
                ]);
            } else {
                $this->redirectAdmin([
                    'event' => 'exportCronInfo',
                    'id_elegantaleasyimport_export' => $model->id,
                ]);
            }
        }

        $this->context->controller->addJqueryUI('ui.sortable');

        $fields_value = [];
        if ($model_columns && is_array($model_columns)) {
            foreach ($model_columns as $key => $column_name) {
                $fields_value[$key] = 1;
            }
            foreach ($columns_with_title as $key => $column_name) {
                if (!isset($fields_value[$key])) {
                    $fields_value[$key] = 0;
                }
            }
        } else {
            // Default values
            foreach ($columns_with_title as $key => $column_name) {
                $fields_value[$key] = 1;
            }
        }
        $column_override_values = ElegantalEasyImportTools::unstorable($model->column_override_values);

        $inputs = [];
        // Add disabled columns to the end of the list
        foreach ($columns_with_title as $key => $column_name) {
            if (!isset($model_columns[$key])) {
                $model_columns[$key] = $column_name;
            }
        }
        foreach ($model_columns as $key => $column_name) {
            $inputs[] = [
                'type' => 'elegantal_columns_select',
                'label' => $column_name,
                'name' => $key,
                'is_bool' => true,
                'values' => [
                    [
                        'id' => $key . '_on',
                        'value' => 1,
                        'label' => $this->l('Yes'),
                    ],
                    [
                        'id' => $key . '_off',
                        'value' => 0,
                        'label' => $this->l('No'),
                    ],
                ],
                'form_group_class' => 'elegantal_columns_form_group',
                'original_label' => isset($columns_with_title[$key]) ? $columns_with_title[$key] : ucwords(str_replace('_', ' ', $key)),
                'column_override_value' => isset($column_override_values[$key]) ? $column_override_values[$key] : '',
                'multiple_value_separator' => $model->multiple_value_separator,
            ];
        }

        $fields_form = [
            'form' => [
                'legend' => [
                    'title' => $this->l('Step') . ' 2: ' . $this->l('Select what to export') . ' - "' . $model->name . '"',
                    'icon' => 'icon-columns',
                ],
                'input' => $inputs,
                'submit' => [
                    'title' => $this->l('Save & Export'),
                    'name' => 'submitAndExport',
                    'icon' => 'process-icon-upload',
                ],
                'buttons' => [
                    [
                        'title' => $this->l('Save & CRON'),
                        'name' => 'submitAndNext',
                        'type' => 'submit',
                        'class' => 'pull-right',
                        'icon' => 'process-icon-terminal',
                    ],
                    [
                        'title' => $this->l('Save & Stay'),
                        'name' => 'submitAndStay',
                        'type' => 'submit',
                        'class' => 'pull-right',
                        'icon' => 'process-icon-edit',
                    ],
                    [
                        'href' => $this->getAdminUrl(),
                        'title' => $this->l('Main Page'),
                        'class' => 'pull-left',
                        'icon' => 'process-icon-back',
                    ],
                    [
                        'href' => $this->getAdminUrl(['event' => 'exportList']),
                        'title' => $this->l('Export Rules'),
                        'class' => 'pull-left',
                        'icon' => 'process-icon-back',
                    ],
                    [
                        'href' => $this->getAdminUrl(['event' => 'exportEdit', 'id_elegantaleasyimport_export' => $model->id]),
                        'title' => $this->l('Back'),
                        'class' => 'pull-left',
                        'icon' => 'process-icon-back',
                    ],
                ],
            ],
        ];

        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->submit_action = 'submitExportColumns';
        $helper->name_controller = 'elegantalBootstrapWrapper';
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->module = $this;
        $helper->identifier = $this->identifier;
        $helper->currentIndex = $this->getAdminUrl(['event' => 'exportColumns', 'id_elegantaleasyimport_export' => $model->id]);
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = [
            'base_url' => $this->context->shop->getBaseURL(),
            'language' => [
                'id_lang' => $lang->id,
                'iso_code' => $lang->iso_code,
            ],
            'fields_value' => $fields_value,
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        ];

        Media::addJsDef([
            'elegantal_max_input_vars' => (int) ini_get('max_input_vars'),
        ]);

        return $this->exportRenderSteps(2, $model) . $helper->generateForm([$fields_form]);
    }

    protected function export()
    {
        $model = null;
        if (Tools::getValue('id_elegantaleasyimport_export')) {
            $model = new ElegantalEasyImportExport(Tools::getValue('id_elegantaleasyimport_export'));
        }
        if (!Validate::isLoadedObject($model)) {
            $this->setRedirectAlert($this->l('Record not found.'), 'error');
            $this->redirectAdmin(['event' => 'exportList']);
        }

        if (Tools::getValue('ajax')) {
            $result = $model->export();
            echo json_encode($result);
            exit;
        }

        $this->context->smarty->assign(
            [
                'adminUrl' => $this->getAdminUrl(),
                'moduleUrl' => $this->getModuleUrl(),
                'download_link' => $this->getControllerUrl('export', ['action' => 'download', 'id' => $model->id, 'secure_key' => $this->getSetting('security_token_key'), 'filename' => $model->entity . $model->id . '.' . $model->file_format]),
            ]
        );

        return $this->exportRenderSteps(3, $model) . $this->display(__FILE__, 'views/templates/admin/export.tpl');
    }

    protected function exportChangeStatus()
    {
        $model = null;
        if (Tools::getValue('id_elegantaleasyimport_export')) {
            $model = new ElegantalEasyImportExport(Tools::getValue('id_elegantaleasyimport_export'));
        }
        if (!Validate::isLoadedObject($model)) {
            $this->setRedirectAlert($this->l('Record not found.'), 'error');
            $this->redirectAdmin(['event' => 'exportList']);
        }
        $model->active = $model->active == 1 ? 0 : 1;
        if ($model->update()) {
            $this->setRedirectAlert($this->l('Status changed successfully.'), 'success');
        } else {
            $this->setRedirectAlert('Status could not be changed.', 'error');
        }
        $this->redirectAdmin(['event' => 'exportList']);
    }

    protected function exportDuplicate()
    {
        $model = null;
        if (Tools::getValue('id_elegantaleasyimport_export')) {
            $model = new ElegantalEasyImportExport(Tools::getValue('id_elegantaleasyimport_export'));
        }
        if (!Validate::isLoadedObject($model)) {
            $this->setRedirectAlert($this->l('Record not found.'), 'error');
            $this->redirectAdmin(['event' => 'exportList']);
        }

        $model->id = null;
        $model->id_elegantaleasyimport_export = null;
        $model->name .= ' (Copy)';

        $count = 1;
        $dir = pathinfo($model->file_path, PATHINFO_DIRNAME);
        $filename = pathinfo($model->file_path, PATHINFO_FILENAME);
        $ext = Tools::strtolower(pathinfo($model->file_path, PATHINFO_EXTENSION));
        do {
            ++$count;
            $model->file_path = $dir . '/' . $filename . '_' . $count . '.' . $ext;
        } while (is_file($model->file_path));

        $model->last_export_date = null;
        $model->active = 1;
        if ($model->add()) {
            $this->setRedirectAlert($this->l('Rule duplicated successfully.'), 'success');
            if (!file_put_contents($model->file_path, ' ')) {
                $this->setRedirectAlert($this->l('File Path you specified is not writable.') . ' ' . $model->file_path . ' ' . $this->l('Please make sure you enter file path that the module has permissions to write.'), 'error');
            }
            $this->redirectAdmin([
                'event' => 'exportEdit',
                'id_elegantaleasyimport_export' => $model->id,
            ]);
        } else {
            $this->setRedirectAlert('Rule could not be duplicated. ' . Db::getInstance()->getMsgError(), 'error');
        }

        $this->redirectAdmin(['event' => 'exportList']);
    }

    protected function exportDelete()
    {
        $model = null;
        if (Tools::getValue('id_elegantaleasyimport_export')) {
            $model = new ElegantalEasyImportExport(Tools::getValue('id_elegantaleasyimport_export'));
        }
        if (!Validate::isLoadedObject($model)) {
            $this->setRedirectAlert($this->l('Record not found.'), 'error');
            $this->redirectAdmin(['event' => 'exportList']);
        }
        $pathinfo = pathinfo($model->file_path);
        $zip_file = $pathinfo['dirname'] . '/' . $pathinfo['filename'] . '.zip';
        if ($model->delete()) {
            if (is_file($zip_file)) {
                @unlink($zip_file);
            }
            @unlink($model->file_path);
            $this->setRedirectAlert($this->l('Rule deleted successfully.'), 'success');
        } else {
            $this->setRedirectAlert('Rule could not be deleted. ' . Db::getInstance()->getMsgError(), 'error');
        }
        $this->redirectAdmin(['event' => 'exportList']);
    }

    protected function exportCronInfo()
    {
        $model = null;
        if (Tools::getValue('id_elegantaleasyimport_export')) {
            $model = new ElegantalEasyImportExport(Tools::getValue('id_elegantaleasyimport_export'));
        }
        if (!Validate::isLoadedObject($model)) {
            $this->setRedirectAlert($this->l('Record not found.'), 'error');
            $this->redirectAdmin(['event' => 'exportList']);
        }

        $cron_cpanel_doc = null;
        $documentation_urls = $this->getDocumentationUrls(['pdf']);
        foreach ($documentation_urls as $doc => $url) {
            if ($doc == 'setup_cron_job_in_cpanel.pdf') {
                $cron_cpanel_doc = $url;
                break;
            }
        }
        $this->context->smarty->assign(
            [
                'adminUrl' => $this->getAdminUrl(),
                'cron_url' => $this->getControllerUrl('export', ['id' => $model->id]),
                'cron_command' => realpath(dirname(__FILE__) . '/commands/cron_export.php') . ' ' . $model->id,
                'cron_cpanel_doc' => $cron_cpanel_doc,
            ]
        );

        return $this->exportRenderSteps(3, $model) . $this->display(__FILE__, 'views/templates/admin/export_cron.tpl');
    }

    /**
     * Action to trigger CRON manually
     */
    protected function triggerCron()
    {
        $url = '';
        $id = Tools::getValue('id');
        $type = Tools::getValue('type');
        if ($type == 'import') {
            $url = $this->getControllerUrl('import', ['id' => $id]);
        } elseif ($type == 'export') {
            $url = $this->getControllerUrl('export', ['id' => $id]);
        } else {
            $this->setRedirectAlert('Invalid Type.', 'error');
            $this->redirectAdmin();
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/54.0.1');

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = ($response === false) ? curl_error($ch) : '';

        curl_close($ch);

        if ($http_code == 200) {
            $this->setRedirectAlert($this->l('CRON executed successfully.'), 'success');
        } else {
            $this->setRedirectAlert($this->l('CRON execution failed.') . ' ' . $this->l('Error') . ': ' . $http_code . ' ' . $error, 'error');
        }

        if ($type == 'export') {
            $this->redirectAdmin(['event' => 'exportList']);
        }

        $this->redirectAdmin();
    }

    /**
     * Action to backup module configuration and settings.
     * Returns json file for download.
     */
    protected function backupModule()
    {
        $backup = [];

        $import_rules = Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'elegantaleasyimport`');
        if ($import_rules) {
            foreach ($import_rules as $rule) {
                $import_rule_category_map = Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'elegantaleasyimport_category_map` WHERE `id_elegantaleasyimport` = ' . (int) $rule['id_elegantaleasyimport']);
                if ($import_rule_category_map) {
                    foreach ($import_rule_category_map as $rule_category_map) {
                        unset($rule_category_map['id_elegantaleasyimport']);
                        unset($rule_category_map['id_elegantaleasyimport_category_map']);
                        $rule['elegantaleasyimport_category_map'][] = $rule_category_map;
                    }
                }
                unset($rule['id_elegantaleasyimport']);
                unset($rule['csv_url_username']);
                unset($rule['csv_url_password']);
                unset($rule['ftp_host']);
                unset($rule['ftp_username']);
                unset($rule['ftp_password']);
                unset($rule['email_to_send_notification']);
                $backup['elegantaleasyimport'][] = $rule;
            }
        }
        $export_rules = Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . 'elegantaleasyimport_export`');
        if ($export_rules) {
            foreach ($export_rules as $rule) {
                unset($rule['id_elegantaleasyimport_export']);
                unset($rule['file_path']);
                unset($rule['last_export_date']);
                unset($rule['email_to_send_notification']);
                $backup['elegantaleasyimport_export'][] = $rule;
            }
        }
        $backup['settings'] = $this->getSettings();

        header('Content-Description: File Transfer');
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="elegantaleasyimport-backup-' . date('d-m-Y') . '.json"');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Expires: 0');
        echo json_encode($backup, JSON_PRETTY_PRINT);
        exit;
    }

    /**
     * Action to restore module configuration and settings from json file.
     */
    protected function restoreModule()
    {
        if ($this->isPostRequest()) {
            $error = '';
            if (!isset($_FILES['backup_file_upload']) || empty($_FILES['backup_file_upload']['tmp_name']) || !is_uploaded_file($_FILES['backup_file_upload']['tmp_name'])) {
                $error = $this->l('Please upload backup file.');
            } else {
                // Validate file extension
                $extension = Tools::strtolower(pathinfo($_FILES['backup_file_upload']['name'], PATHINFO_EXTENSION));
                if ($extension != 'json') {
                    $error = $this->l('File type is not allowed') . ': ' . $extension;
                } else {
                    // Validate mime type
                    $mime = ElegantalEasyImportTools::getMimeType($_FILES['backup_file_upload']['tmp_name'], $extension);
                    if (!in_array($mime, ['application/json'])) {
                        $error = $this->l('File type is not allowed') . '. Mime Type: ' . $mime;
                    }
                }
            }
            if (!$error) {
                $backup = json_decode(Tools::file_get_contents($_FILES['backup_file_upload']['tmp_name']), true);
                if (empty($backup['elegantaleasyimport']) && empty($backup['elegantaleasyimport_export']) && empty($backup['settings'])) {
                    $this->setRedirectAlert($this->l('No data found to restore.'), 'error');
                    $this->redirectAdmin(['event' => 'restoreModule']);
                }
                if (isset($backup['elegantaleasyimport']) && is_array($backup['elegantaleasyimport'])) {
                    if (Tools::getValue('is_delete_existing_import_rules')) {
                        Db::getInstance()->execute('DELETE FROM ' . _DB_PREFIX_ . 'elegantaleasyimport');
                    }
                    foreach ($backup['elegantaleasyimport'] as $rule) {
                        $importRule = new ElegantalEasyImportClass();
                        foreach ($rule as $key => $value) {
                            $importRule->{$key} = $value;
                        }
                        if ($importRule->add()) {
                            if (isset($rule['elegantaleasyimport_category_map']) && is_array($rule['elegantaleasyimport_category_map'])) {
                                foreach ($rule['elegantaleasyimport_category_map'] as $category_map) {
                                    $categoryMap = new ElegantalEasyImportCategoryMap();
                                    $categoryMap->id_elegantaleasyimport = $importRule->id;
                                    foreach ($category_map as $key2 => $value2) {
                                        $categoryMap->{$key2} = $value2;
                                    }
                                    if (!$categoryMap->add()) {
                                        throw new Exception(Db::getInstance()->getMsgError());
                                    }
                                }
                            }
                        } else {
                            throw new Exception(Db::getInstance()->getMsgError());
                        }
                    }
                }
                if (isset($backup['elegantaleasyimport_export']) && is_array($backup['elegantaleasyimport_export'])) {
                    if (Tools::getValue('is_delete_existing_export_rules')) {
                        Db::getInstance()->execute('DELETE FROM ' . _DB_PREFIX_ . 'elegantaleasyimport_export');
                    }
                    foreach ($backup['elegantaleasyimport_export'] as $rule) {
                        $exportRule = new ElegantalEasyImportExport();
                        foreach ($rule as $key => $value) {
                            $exportRule->{$key} = $value;
                        }
                        $exportRule->last_export_date = null;
                        $exportRule->file_path = ElegantalEasyImportTools::getTempDir() . '/export_' . date('d-m-Y') . '_' . date('His') . mt_rand(100000, 100000000) . '.' . $exportRule->file_format;
                        if (!$exportRule->add()) {
                            throw new Exception(Db::getInstance()->getMsgError());
                        }
                    }
                }
                if (isset($backup['settings']) && is_array($backup['settings'])) {
                    $settings = ElegantalEasyImportTools::storable(array_merge($this->getSettings(), $backup['settings']));
                    Configuration::updateValue($this->name, $settings, false);
                }
                $this->setRedirectAlert($this->l('Module settings restored successfully.'), 'success');
                $this->redirectAdmin();
            } else {
                $this->setRedirectAlert($error, 'error');
                $this->redirectAdmin(['event' => 'restoreModule']);
            }
        }

        $fields_value = [
            'backup_file_upload' => '',
            'is_delete_existing_import_rules' => 0,
            'is_delete_existing_export_rules' => 0,
        ];

        $fields_form = [
            'form' => [
                'legend' => [
                    'title' => $this->l('Backup/Restore module configuration'),
                    'icon' => 'icon-save',
                ],
                'input' => [
                    [
                        'type' => 'file',
                        'label' => $this->l('Upload backup file to restore module'),
                        'name' => 'backup_file_upload',
                        'desc' => $this->l('Upload json file that you downloaded as a backup in order to restore the module configuration settings.'),
                    ],
                    [
                        'type' => (_PS_VERSION_ < '1.6') ? 'el_switch' : 'switch',
                        'label' => $this->l('Delete existing import rules'),
                        'name' => 'is_delete_existing_import_rules',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'is_delete_existing_import_rules_on',
                                'value' => 1,
                                'label' => $this->l('Yes'),
                            ],
                            [
                                'id' => 'is_delete_existing_import_rules_off',
                                'value' => 0,
                                'label' => $this->l('No'),
                            ],
                        ],
                        'desc' => $this->l('All existing import rules will be deleted before restoring from the backup.'),
                    ],
                    [
                        'type' => (_PS_VERSION_ < '1.6') ? 'el_switch' : 'switch',
                        'label' => $this->l('Delete existing export rules'),
                        'name' => 'is_delete_existing_export_rules',
                        'is_bool' => true,
                        'values' => [
                            [
                                'id' => 'is_delete_existing_export_rules_on',
                                'value' => 1,
                                'label' => $this->l('Yes'),
                            ],
                            [
                                'id' => 'is_delete_existing_export_rules_off',
                                'value' => 0,
                                'label' => $this->l('No'),
                            ],
                        ],
                        'desc' => $this->l('All existing export rules will be deleted before restoring from the backup.'),
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Upload & Restore'),
                    'name' => 'submitAndNext',
                    'icon' => 'process-icon-upload',
                ],
                'buttons' => [
                    [
                        'href' => $this->getAdminUrl(),
                        'title' => $this->l('Back'),
                        'class' => 'pull-left',
                        'icon' => 'process-icon-back',
                    ],
                    [
                        'href' => $this->getAdminUrl(['event' => 'backupModule']),
                        'title' => $this->l('Download Backup'),
                        'class' => 'pull-left',
                        'icon' => 'process-icon-save',
                    ],
                ],
            ],
        ];

        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->submit_action = 'submitRestoreModule';
        $helper->name_controller = 'elegantalBootstrapWrapper';
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->module = $this;
        $helper->identifier = $this->identifier;
        $helper->currentIndex = $this->getAdminUrl(['event' => 'restoreModule']);
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = [
            'base_url' => $this->context->shop->getBaseURL(),
            'language' => [
                'id_lang' => $lang->id,
                'iso_code' => $lang->iso_code,
            ],
            'fields_value' => $fields_value,
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        ];

        return $helper->generateForm([$fields_form]);
    }

    public function getIframeHtmlCode($url)
    {
        $this->context->smarty->assign(['url' => $url]);

        return $this->display(__FILE__, 'views/templates/admin/iframe.tpl');
    }
}
