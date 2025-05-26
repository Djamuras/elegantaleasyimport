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
 * This is controller for CRON job for import
 */
class ElegantalEasyImportImportModuleFrontController extends ModuleFrontController
{
    /** @var ElegantalEasyImport */
    public $module;

    public function __construct()
    {
        // Allow CRON in maintenance mode
        if (!(int) Configuration::get('PS_SHOP_ENABLE')) {
            $ip = Tools::getRemoteAddr();
            $allowed_ips_str = Configuration::get('PS_MAINTENANCE_IP');
            if ($allowed_ips_str) {
                $allowed_ips_arr = array_map('trim', explode(',', $allowed_ips_str));
                if (!in_array($ip, $allowed_ips_arr)) {
                    $allowed_ips_arr[] = $ip;
                    Configuration::updateValue('PS_MAINTENANCE_IP', implode(',', $allowed_ips_arr));
                }
            } else {
                Configuration::updateValue('PS_MAINTENANCE_IP', $ip);
            }
        }
        parent::__construct();
    }

    public function display()
    {
        $start = time();

        $this->module->initModel(Tools::getValue('id'));
        $model = $this->module->model;

        if (!$model || !$model->id) {
            exit('Object not found.');
        } elseif (!$model->active) {
            exit('Import rule is not active.');
        } elseif (!$model->is_cron) {
            exit('Import rule is not enabled for CRON.');
        }

        $settings = $model->getModelSettings();

        if (!$settings['security_token_key'] || Tools::getValue('secure_key') != $settings['security_token_key']) {
            exit('Access Denied.');
        }

        try {
            // Latest history object
            $history = ElegantalEasyImportHistory::model()->find([
                'condition' => [
                    'id_elegantaleasyimport' => $model->id,
                ],
                'order' => 'id_elegantaleasyimport_history DESC',
            ]);

            // Count of remaining rows to process
            $remaining_rows = ElegantalEasyImportData::model()->countAll([
                'condition' => [
                    'id_elegantaleasyimport' => $model->id,
                ],
            ]);

            if ($settings['cron_waiting_hour_after_completion'] && empty($remaining_rows) && $history && isset($history['date_ended']) && $history['date_ended']) {
                $cron_waiting_minute = $settings['cron_waiting_hour_after_completion'] * 60;
                $seconds_to_wait = strtotime($history['date_ended']) - strtotime('-' . (int) $cron_waiting_minute . ' minute');
                if ($seconds_to_wait > 0) {
                    $waiting_time = $seconds_to_wait > 60 ? ($seconds_to_wait > 3600 ? round($seconds_to_wait / 3600) . ' hours' : round($seconds_to_wait / 60) . ' minutes') : round($seconds_to_wait) . ' seconds';
                    exit('Import process is 100% completed. The next process will be allowed after ' . $waiting_time . '.');
                }
            }

            // Update status of import rule to prevent execution of the same rule simultaneously
            if ($model->is_processing_now && !Tools::getValue('resetProcess')) {
                // Check last execution time. If it was more than 1 hour ago, do not stop the import process.
                if ($history && isset($history['date_ended']) && $history['date_ended'] && (strtotime($history['date_ended']) > strtotime('-1 hour'))) {
                    exit('Import rule is still processing.');
                }
            }
            register_shutdown_function([$this, 'cronImportCallback'], $model->id);
            $model->is_processing_now = 1;
            $model->update();

            // Start import process
            $file = ElegantalEasyImportTools::getRealPath($model->csv_file);
            $limit = ($model->product_limit_per_request > 0 && $model->product_limit_per_request <= 1000000) ? (int) $model->product_limit_per_request : 50;

            if (!$file || !is_file($file) || !filesize($file)) {
                // File does not exist, download and start import
                $model->downloadImportFile();
                $model->saveCsvRows();
                $model->import($limit);
            } elseif (empty($remaining_rows)) {
                $old_md5 = $model->cron_csv_file_md5;
                $old_size = $model->cron_csv_file_size;

                $model->downloadImportFile();

                $new_md5 = $model->cron_csv_file_md5;
                $new_size = $model->cron_csv_file_size;

                // If file is different, start import
                if ($old_size != $new_size || $old_md5 != $new_md5 || $settings['is_auto_restart_cron_after_import_finished']) {
                    $model->saveCsvRows();
                    $model->import($limit);
                } else {
                    // The file did not change, so don't start import, but save this log.
                    ElegantalEasyImportHistory::createNew($model->id);
                }
            } elseif ($remaining_rows > 0) {
                $model->import($limit);
            }
        } catch (Exception $e) {
            $model->addError('CRON: ' . $e->getMessage());
            if ($settings['is_debug_mode']) {
                echo $e->getMessage();
                exit;
            }
        }
        $end = time();
        $seconds = $end - $start;
        echo date('d-m-Y H:i:s') . ' CRON execution completed in ' . $seconds . ' seconds.';
        exit;
    }

    public function cronImportCallback($id)
    {
        $model = new ElegantalEasyImportClass($id);
        if (Validate::isLoadedObject($model)) {
            $model->is_processing_now = 0;
            $model->update();
        }
    }
}
