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
 * This is controller for one rotating CRON job for multiple import rules.
 */
class ElegantalEasyImportImportqueueModuleFrontController extends ModuleFrontController
{
    /** @var ElegantalEasyImport */
    public $module;

    public function display()
    {
        $secure_key = $this->module->getSetting('security_token_key');
        if (!$secure_key || Tools::getValue('secure_key') != $secure_key) {
            exit('Access Denied.');
        }

        $start = time();
        $stateFile = dirname(__FILE__) . '/../../tmp/cron_import_queue_state.json';
        $lockFile = dirname(__FILE__) . '/../../tmp/cron_import_queue.lock';
        $configFile = dirname(__FILE__) . '/../../commands/cron_import_queue_urls.json';

        $lockHandle = fopen($lockFile, 'c');
        if (!$lockHandle) {
            exit('Could not open import queue lock file.');
        }

        if (!flock($lockHandle, LOCK_EX | LOCK_NB)) {
            exit('Import queue is already running.');
        }

        register_shutdown_function(function () use ($lockHandle) {
            flock($lockHandle, LOCK_UN);
            fclose($lockHandle);
        });

        $queue = $this->normalizeQueue($this->readQueueConfig($configFile));
        if (!$queue) {
            exit('Import queue is empty.');
        }

        $position = $this->normalizePosition($queue, $this->readState($stateFile));
        $supplier = $queue[$position['supplier_index']];
        $step = $supplier['steps'][$position['step_index']];
        $idImportRule = $this->getImportRuleIdFromUrl($step['url']);

        if (!$idImportRule) {
            $this->advanceState($queue, $position, $stateFile);
            exit('Import queue step has no id parameter: ' . $supplier['name'] . ' / ' . $step['name']);
        }

        if (!$this->isAllowedImportUrl($step['url'])) {
            $this->advanceState($queue, $position, $stateFile);
            exit('Import queue step URL is not an allowed module import URL.');
        }

        $model = new ElegantalEasyImportClass($idImportRule);
        if (!Validate::isLoadedObject($model) || !$model->active || !$model->is_cron) {
            $this->advanceState($queue, $position, $stateFile);
            exit('Import rule is missing, inactive, or not enabled for CRON: ' . (int) $idImportRule);
        }

        $response = $this->requestUrl($step['url']);
        $remainingRows = (int) ElegantalEasyImportData::model()->countAll([
            'condition' => [
                'id_elegantaleasyimport' => (int) $idImportRule,
            ],
        ]);

        if (!$remainingRows) {
            $this->advanceState($queue, $position, $stateFile);
        } else {
            $this->writeState($position, $stateFile);
        }

        $seconds = time() - $start;
        echo date('d-m-Y H:i:s') . ' Import queue ran "' . $supplier['name'] . ' / ' . $step['name'] . '"';
        echo ' in ' . (int) $seconds . ' seconds. Remaining rows: ' . (int) $remainingRows . '.';
        if ($response) {
            echo PHP_EOL . trim($response);
        }
        exit;
    }

    protected function readQueueConfig($configFile)
    {
        if (!is_file($configFile)) {
            return [];
        }

        $queue = json_decode((string) file_get_contents($configFile), true);
        return is_array($queue) ? $queue : [];
    }

    protected function normalizeQueue($queue)
    {
        $normalized = [];
        foreach ($queue as $supplierIndex => $supplier) {
            if (!is_array($supplier) || empty($supplier['steps']) || !is_array($supplier['steps'])) {
                continue;
            }

            $steps = [];
            foreach ($supplier['steps'] as $stepIndex => $step) {
                if (!is_array($step) || empty($step['url'])) {
                    continue;
                }

                $steps[] = [
                    'name' => !empty($step['name']) ? (string) $step['name'] : 'Step ' . ((int) $stepIndex + 1),
                    'url' => (string) $step['url'],
                ];
            }

            if ($steps) {
                $normalized[] = [
                    'name' => !empty($supplier['name']) ? (string) $supplier['name'] : 'Supplier ' . ((int) $supplierIndex + 1),
                    'steps' => $steps,
                ];
            }
        }

        return $normalized;
    }

    protected function readState($stateFile)
    {
        if (!is_file($stateFile)) {
            return [];
        }

        $state = json_decode((string) file_get_contents($stateFile), true);
        return is_array($state) ? $state : [];
    }

    protected function writeState(array $position, $stateFile)
    {
        $state = [
            'supplier_index' => (int) $position['supplier_index'],
            'step_index' => (int) $position['step_index'],
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        file_put_contents($stateFile, json_encode($state, JSON_PRETTY_PRINT));
    }

    protected function normalizePosition(array $queue, array $state)
    {
        $supplierIndex = isset($state['supplier_index']) ? (int) $state['supplier_index'] : 0;
        $stepIndex = isset($state['step_index']) ? (int) $state['step_index'] : 0;

        if (!isset($queue[$supplierIndex])) {
            $supplierIndex = 0;
            $stepIndex = 0;
        }

        if (!isset($queue[$supplierIndex]['steps'][$stepIndex])) {
            $stepIndex = 0;
        }

        return [
            'supplier_index' => $supplierIndex,
            'step_index' => $stepIndex,
        ];
    }

    protected function advanceState(array $queue, array $position, $stateFile)
    {
        $supplierIndex = (int) $position['supplier_index'];
        $stepIndex = (int) $position['step_index'] + 1;

        if (!isset($queue[$supplierIndex]['steps'][$stepIndex])) {
            $stepIndex = 0;
            $supplierIndex++;
        }

        if (!isset($queue[$supplierIndex])) {
            $supplierIndex = 0;
            $stepIndex = 0;
        }

        $position = [
            'supplier_index' => $supplierIndex,
            'step_index' => $stepIndex,
        ];
        $this->writeState($position, $stateFile);

        return $position;
    }

    protected function getImportRuleIdFromUrl($url)
    {
        $query = parse_url($url, PHP_URL_QUERY);
        if (!$query) {
            return 0;
        }

        parse_str($query, $params);
        return !empty($params['id']) ? (int) $params['id'] : 0;
    }

    protected function isAllowedImportUrl($url)
    {
        $parsedUrl = parse_url($url);
        $shopUrl = parse_url($this->context->shop->getBaseURL(true));

        if (empty($parsedUrl['host']) || empty($shopUrl['host']) || Tools::strtolower($parsedUrl['host']) != Tools::strtolower($shopUrl['host'])) {
            return false;
        }

        if (empty($parsedUrl['query'])) {
            return false;
        }

        parse_str($parsedUrl['query'], $params);
        if (empty($params['secure_key']) || $params['secure_key'] != $this->module->getSetting('security_token_key')) {
            return false;
        }

        $path = !empty($parsedUrl['path']) ? Tools::strtolower($parsedUrl['path']) : '';
        if (strpos($path, '/module/elegantaleasyimport/import') !== false) {
            return true;
        }

        return !empty($params['fc']) && $params['fc'] == 'module'
            && !empty($params['module']) && $params['module'] == 'elegantaleasyimport'
            && !empty($params['controller']) && $params['controller'] == 'import';
    }

    protected function requestUrl($url)
    {
        if (function_exists('curl_init')) {
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($curl, CURLOPT_TIMEOUT, 0);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            $response = curl_exec($curl);
            $error = curl_error($curl);
            curl_close($curl);

            return $response !== false ? $response : $error;
        }

        $context = stream_context_create([
            'http' => [
                'timeout' => 0,
            ],
        ]);

        $response = file_get_contents($url, false, $context);
        return $response !== false ? $response : '';
    }
}
