<?php
/**
 * Import queue configuration.
 *
 * HOW TO USE
 * 1. In Elegant Easy Import admin, create one import rule for each supplier step.
 *    Example: Supplier A Products = one rule, Supplier A Combinations = another rule.
 * 2. Open each rule's CRON page and copy its generated CRON URL.
 * 3. Paste those URLs into cron_import_queue_urls.json from the back-office
 *    Import Queue CRON page. Keep product imports before combination imports.
 * 4. Add only ONE real server cronjob for the dispatcher:
 *
 *    Every 3 minutes:
 *    0,3,6,9,12,15,18,21,24,27,30,33,36,39,42,45,48,51,54,57 * * * *
 *    /usr/bin/php /path/to/modules/elegantaleasyimport/commands/cron_import_queue.php
 *
 *    Replace /path/to/modules with the real path on your server.
 *
 * HOW ROTATION WORKS
 * - Each cron execution runs one configured step.
 * - If a step still has remaining rows, the next cron execution continues the same step.
 * - When a product step finishes, the queue moves to its combinations step.
 * - When all supplier steps finish, the queue moves to the next supplier.
 * - A lock file prevents two queue executions from running at the same time.
 *
 * This PHP file is kept as a fallback for old/manual setups.
 * The recommended editable config file is cron_import_queue_urls.json.
 */

return [
    [
        'name' => 'Supplier 1',
        'steps' => [
            [
                'name' => 'Products',
                'url' => 'https://your-shop.com/module/elegantaleasyimport/import?id=1&secure_key=CHANGE_ME',
            ],
            [
                'name' => 'Combinations',
                'url' => 'https://your-shop.com/module/elegantaleasyimport/import?id=2&secure_key=CHANGE_ME',
            ],
        ],
    ],
    /*
    [
        'name' => 'Supplier 2',
        'steps' => [
            [
                'name' => 'Products',
                'url' => 'https://your-shop.com/module/elegantaleasyimport/import?id=3&secure_key=CHANGE_ME',
            ],
            [
                'name' => 'Combinations',
                'url' => 'https://your-shop.com/module/elegantaleasyimport/import?id=4&secure_key=CHANGE_ME',
            ],
        ],
    ],
    */
];
