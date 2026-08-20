<?php
/**
 * Rotating CRON dispatcher for Elegant Easy Import.
 *
 * HOW TO USE
 * 1. Edit commands/cron_import_queue_urls.json from the back-office
 *    Import Queue CRON page and paste all supplier import
 *    CRON URLs there.
 * 2. Use one real server cronjob to run this file:
 *
 *    Every 3 minutes:
 *    0,3,6,9,12,15,18,21,24,27,30,33,36,39,42,45,48,51,54,57 * * * *
 *    /usr/bin/php /path/to/modules/elegantaleasyimport/commands/cron_import_queue.php
 *
 * 3. Do not create separate real cronjobs for each supplier URL. This file is
 *    the single dispatcher that rotates them safely.
 *
 * PRODUCT + COMBINATION IMPORTS
 * Put each supplier's product import first and combination import second in
 * cron_import_queue_urls.json. The queue only advances to combinations after
 * the product import rule has no remaining rows.
 *
 * Configure supplier import URLs in cron_import_queue_urls.json and run this
 * file from one server cron. It executes one configured step per run and only
 * advances to the next step when the current import rule has no remaining rows.
 */
error_reporting(0);

require_once dirname(__FILE__) . '/../../../config/config.inc.php';
require_once dirname(__FILE__) . '/../elegantaleasyimport.php';

if (!defined('_PS_VERSION_')) {
    exit;
}

define('ELEGANTAL_IMPORT_QUEUE_STATE_FILE', dirname(__FILE__) . '/../tmp/cron_import_queue_state.json');
define('ELEGANTAL_IMPORT_QUEUE_LOCK_FILE', dirname(__FILE__) . '/../tmp/cron_import_queue.lock');
define('ELEGANTAL_IMPORT_QUEUE_JSON_CONFIG_FILE', dirname(__FILE__) . '/cron_import_queue_urls.json');
define('ELEGANTAL_IMPORT_QUEUE_PHP_CONFIG_FILE', dirname(__FILE__) . '/cron_import_queue_urls.php');

$lockHandle = fopen(ELEGANTAL_IMPORT_QUEUE_LOCK_FILE, 'c');
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

$queue = elegantalReadImportQueueConfig();
$queue = elegantalNormalizeImportQueue($queue);

if (!$queue) {
    exit('Import queue is empty. Add URLs to commands/cron_import_queue_urls.json.');
}

$state = elegantalReadImportQueueState();
$position = elegantalNormalizeImportQueuePosition($queue, $state);
$supplier = $queue[$position['supplier_index']];
$step = $supplier['steps'][$position['step_index']];
$idImportRule = elegantalGetImportRuleIdFromUrl($step['url']);

if (!$idImportRule) {
    elegantalAdvanceImportQueueState($queue, $position);
    exit('Import queue step has no id parameter: ' . $supplier['name'] . ' / ' . $step['name']);
}

$model = new ElegantalEasyImportClass($idImportRule);
if (!Validate::isLoadedObject($model) || !$model->active || !$model->is_cron) {
    elegantalAdvanceImportQueueState($queue, $position);
    exit('Import rule is missing, inactive, or not enabled for CRON: ' . (int) $idImportRule);
}

$startedAt = time();
$response = elegantalImportQueueRequest($step['url']);
$remainingRows = (int) ElegantalEasyImportData::model()->countAll([
    'condition' => [
        'id_elegantaleasyimport' => (int) $idImportRule,
    ],
]);

if (!$remainingRows) {
    $position = elegantalAdvanceImportQueueState($queue, $position);
} else {
    elegantalWriteImportQueueState($position);
}

$seconds = time() - $startedAt;
echo date('d-m-Y H:i:s') . ' Import queue ran "' . $supplier['name'] . ' / ' . $step['name'] . '"';
echo ' in ' . (int) $seconds . ' seconds. Remaining rows: ' . (int) $remainingRows . '.';
if ($response) {
    echo PHP_EOL . trim($response);
}
exit;

function elegantalReadImportQueueConfig()
{
    if (is_file(ELEGANTAL_IMPORT_QUEUE_JSON_CONFIG_FILE)) {
        $content = file_get_contents(ELEGANTAL_IMPORT_QUEUE_JSON_CONFIG_FILE);
        $queue = json_decode((string) $content, true);
        return is_array($queue) ? $queue : [];
    }

    if (is_file(ELEGANTAL_IMPORT_QUEUE_PHP_CONFIG_FILE)) {
        return require ELEGANTAL_IMPORT_QUEUE_PHP_CONFIG_FILE;
    }

    return [];
}

function elegantalNormalizeImportQueue($queue)
{
    if (!is_array($queue)) {
        return [];
    }

    $normalized = [];
    foreach ($queue as $supplierIndex => $supplier) {
        if (is_string($supplier)) {
            $supplier = [
                'name' => 'Import ' . ((int) $supplierIndex + 1),
                'steps' => [
                    [
                        'name' => 'Import',
                        'url' => $supplier,
                    ],
                ],
            ];
        }

        if (!is_array($supplier) || empty($supplier['steps']) || !is_array($supplier['steps'])) {
            continue;
        }

        $steps = [];
        foreach ($supplier['steps'] as $stepIndex => $step) {
            if (is_string($step)) {
                $step = [
                    'name' => 'Step ' . ((int) $stepIndex + 1),
                    'url' => $step,
                ];
            }

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

function elegantalReadImportQueueState()
{
    if (!is_file(ELEGANTAL_IMPORT_QUEUE_STATE_FILE)) {
        return [];
    }

    $state = json_decode((string) file_get_contents(ELEGANTAL_IMPORT_QUEUE_STATE_FILE), true);
    return is_array($state) ? $state : [];
}

function elegantalWriteImportQueueState(array $position)
{
    $state = [
        'supplier_index' => (int) $position['supplier_index'],
        'step_index' => (int) $position['step_index'],
        'updated_at' => date('Y-m-d H:i:s'),
    ];

    file_put_contents(ELEGANTAL_IMPORT_QUEUE_STATE_FILE, json_encode($state, JSON_PRETTY_PRINT));
}

function elegantalNormalizeImportQueuePosition(array $queue, array $state)
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

function elegantalAdvanceImportQueueState(array $queue, array $position)
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
    elegantalWriteImportQueueState($position);

    return $position;
}

function elegantalGetImportRuleIdFromUrl($url)
{
    $query = parse_url($url, PHP_URL_QUERY);
    if (!$query) {
        return 0;
    }

    parse_str($query, $params);
    return !empty($params['id']) ? (int) $params['id'] : 0;
}

function elegantalImportQueueRequest($url)
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
