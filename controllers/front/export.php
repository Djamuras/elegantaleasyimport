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
 * This is controller for CRON job for export
 */
class ElegantalEasyImportExportModuleFrontController extends ModuleFrontController
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

        $secure_key = $this->module->getSetting('security_token_key');
        $model = new ElegantalEasyImportExport(Tools::getValue('id'));

        if (!$secure_key || Tools::getValue('secure_key') != $secure_key) {
            exit('Access Denied.');
        } elseif (!$model || !$model->id) {
            exit('Object not found.');
        } elseif (!$model->active) {
            exit('Export rule is not active.');
        }

        if (Tools::getValue('action') == 'download') {
            return $this->downloadExportFile($model);
        }

        $date_started = date('d-m-Y H:i:s');

        $result = $model->export();

        if (isset($result['success']) && $result['success']) {
            $end = time();
            $seconds = $end - $start;
            $message = date('d-m-Y H:i:s') . ' Exporting ';
            $message .= isset($result['count']) ? $result['count'] . ' ' : '';
            $message .= ($model->entity == 'combination') ? 'combinations' : 'products';
            $message .= ' completed in ' . $seconds . ' seconds.';
            // Send notification
            if ($model->email_to_send_notification && Validate::isEmail($model->email_to_send_notification)) {
                try {
                    $subject = 'CRON has finished exporting for the rule "' . $model->name . '"';
                    $template_vars = [
                        '{rule_name}' => $model->name,
                        '{date_started}' => $date_started,
                        '{date_ended}' => date('d-m-Y H:i:s'),
                        '{message}' => $message,
                        '{download_link}' => $this->module->getControllerUrl('export', ['action' => 'download', 'id' => $model->id, 'secure_key' => $secure_key, 'filename' => $model->entity . $model->id . '.' . $model->file_format]),
                    ];
                    $template_path = dirname(__FILE__) . '/../../mails/';
                    $template = 'export_finished';

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

                    Mail::Send($this->context->language->id, $template, $subject, $template_vars, $model->email_to_send_notification, null, null, 'Easy Import Module', null, null, $template_path);
                } catch (Exception $e) {
                    // Do nothing
                }
            }
            echo $message;
        } elseif (isset($result['message'])) {
            echo $result['message'];
        }
        exit;
    }

    private function downloadExportFile($model)
    {
        if (!is_file($model->file_path)) {
            exit('File not found.');
        }
        $mime = 'application/octet-stream';
        switch ($model->file_format) {
            case 'csv':
                $mime = 'text/csv';
                break;
            case 'xml':
                $mime = 'text/xml';
                break;
            case 'json':
                $mime = 'application/json';
                break;
            case 'xls':
                $mime = 'application/vnd.ms-excel';
                break;
            case 'xlsx':
                $mime = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                break;
            case 'ods':
                $mime = 'application/vnd.oasis.opendocument.spreadsheet';
                break;
            case 'txt':
                $mime = 'text/plain';
                break;
            default:
                break;
        }
        header('Content-Description: File Transfer');
        header('Content-Type: ' . $mime);
        header('Content-Disposition: attachment; filename="' . basename($model->file_path) . '"');
        header('Content-Length: ' . filesize($model->file_path));
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Expires: 0');
        while (ob_get_level()) {
            ob_end_clean();
        }
        readfile($model->file_path);

        if (Tools::getValue('deleteFileAfterDownload') == 1) {
            @unlink($model->file_path);
        }

        exit;
    }
}
