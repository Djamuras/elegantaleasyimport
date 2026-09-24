<?php
/**
 * @author    ELEGANTAL <info@elegantal.com>
 * @copyright (c) 2025, ELEGANTAL <www.elegantal.com>
 * @license   Proprietary License - It is forbidden to resell or redistribute copies of the module or modified copies of the module.
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

class ElegantalEasyImportMissingImage
{
    const MAX_ATTEMPTS = 60;

    public static function getCreateTableSql()
    {
        return 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . "elegantaleasyimport_missing_image` (
            `id_elegantaleasyimport_missing_image` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `id_elegantaleasyimport` int(11) unsigned NOT NULL,
            `id_product` int(11) unsigned NOT NULL DEFAULT '0',
            `id_product_attribute` int(11) unsigned NOT NULL DEFAULT '0',
            `product_reference` varchar(255),
            `combination_reference` varchar(255),
            `image_url` text NOT NULL,
            `image_url_hash` char(32) NOT NULL,
            `attempts` int(11) unsigned NOT NULL DEFAULT '1',
            `last_error` text,
            `last_attempt_at` DATETIME,
            `next_attempt_at` DATETIME,
            `date_add` DATETIME,
            `date_upd` DATETIME,
            PRIMARY KEY (`id_elegantaleasyimport_missing_image`),
            UNIQUE KEY `missing_image_unique` (`id_elegantaleasyimport`, `id_product`, `id_product_attribute`, `combination_reference`, `image_url_hash`),
            KEY `next_attempt_at` (`next_attempt_at`),
            KEY `id_product` (`id_product`),
            KEY `id_product_attribute` (`id_product_attribute`)
        ) ENGINE=" . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=UTF8;';
    }

    public static function install()
    {
        if (!Db::getInstance()->execute(self::getCreateTableSql())) {
            return false;
        }

        return self::installIndexes();
    }

    public static function installIndexes()
    {
        $table = _DB_PREFIX_ . 'elegantaleasyimport_missing_image';
        $indexes = Db::getInstance()->executeS("SHOW INDEX FROM `" . bqSQL($table) . "` WHERE `Key_name` = 'missing_image_unique'");

        if ($indexes && count($indexes) === 4) {
            Db::getInstance()->execute('ALTER TABLE `' . bqSQL($table) . '` DROP INDEX `missing_image_unique`');
            return Db::getInstance()->execute('ALTER TABLE `' . bqSQL($table) . '` ADD UNIQUE KEY `missing_image_unique` (`id_elegantaleasyimport`, `id_product`, `id_product_attribute`, `combination_reference`, `image_url_hash`)');
        }

        return true;
    }

    public static function enqueue($id_import, $id_product, $id_product_attribute, $product_reference, $combination_reference, $image_url, $error)
    {
        if (!$id_import || !$id_product || !$image_url) {
            return false;
        }

        self::install();

        $now = date('Y-m-d H:i:s');
        $next = self::getNextAttemptDate(1);
        $hash = md5($image_url);
        $sql = 'INSERT INTO `' . _DB_PREFIX_ . "elegantaleasyimport_missing_image`
            (`id_elegantaleasyimport`, `id_product`, `id_product_attribute`, `product_reference`, `combination_reference`, `image_url`, `image_url_hash`, `attempts`, `last_error`, `last_attempt_at`, `next_attempt_at`, `date_add`, `date_upd`)
            VALUES (
                " . (int) $id_import . ',
                ' . (int) $id_product . ',
                ' . (int) $id_product_attribute . ",
                '" . pSQL($product_reference) . "',
                '" . pSQL($combination_reference) . "',
                '" . pSQL($image_url) . "',
                '" . pSQL($hash) . "',
                1,
                '" . pSQL($error) . "',
                '" . pSQL($now) . "',
                '" . pSQL($next) . "',
                '" . pSQL($now) . "',
                '" . pSQL($now) . "'
            )
            ON DUPLICATE KEY UPDATE
                `product_reference` = VALUES(`product_reference`),
                `combination_reference` = VALUES(`combination_reference`),
                `last_error` = VALUES(`last_error`),
                `date_upd` = VALUES(`date_upd`)";

        return Db::getInstance()->execute($sql);
    }

    public static function process($limit = 25)
    {
        self::install();
        $limit = max(1, min(100, (int) $limit));
        $rows = Db::getInstance()->executeS('SELECT * FROM `' . _DB_PREFIX_ . "elegantaleasyimport_missing_image`
            WHERE `next_attempt_at` <= '" . pSQL(date('Y-m-d H:i:s')) . "'
            ORDER BY `next_attempt_at` ASC, `id_elegantaleasyimport_missing_image` ASC
            LIMIT " . (int) $limit);

        $result = [
            'checked' => 0,
            'imported' => 0,
            'failed' => 0,
            'deleted' => 0,
        ];

        foreach ($rows as $row) {
            ++$result['checked'];
            $status = self::processRow($row);
            if (isset($result[$status])) {
                ++$result[$status];
            }
        }

        return $result;
    }

    public static function getStats()
    {
        self::install();
        $pending = (int) Db::getInstance()->getValue('SELECT COUNT(*) FROM `' . _DB_PREFIX_ . 'elegantaleasyimport_missing_image`');
        $due = (int) Db::getInstance()->getValue('SELECT COUNT(*) FROM `' . _DB_PREFIX_ . "elegantaleasyimport_missing_image` WHERE `next_attempt_at` <= '" . pSQL(date('Y-m-d H:i:s')) . "'");

        return [
            'pending' => $pending,
            'due' => $due,
        ];
    }

    protected static function processRow(array $row)
    {
        $product = new Product((int) $row['id_product']);
        if (!Validate::isLoadedObject($product)) {
            self::delete((int) $row['id_elegantaleasyimport_missing_image']);
            return 'deleted';
        }

        $image = new Image();
        $image->id_product = (int) $product->id;
        $image->position = Image::getHighestPosition((int) $product->id) + 1;
        $image->cover = false;

        if ($image->add()) {
            $imageFile = _PS_PROD_IMG_DIR_ . $image->getExistingImgPath() . '.' . $image->image_format;
            if (ElegantalEasyImportTools::copyImg((int) $product->id, $image, $row['image_url']) && is_file($imageFile)) {
                self::assignImageToCombination($image->id, (int) $row['id_product_attribute'], (int) $product->id, $row['combination_reference']);
                self::fixCoverImage((int) $product->id, (int) $image->id);
                self::delete((int) $row['id_elegantaleasyimport_missing_image']);
                return 'imported';
            }
            $image->delete();
            ElegantalEasyImportTools::deleteFolderIfEmpty(dirname($imageFile));
        }

        return self::markFailed($row);
    }

    protected static function assignImageToCombination($id_image, $id_product_attribute, $id_product, $combination_reference)
    {
        if (!$id_product_attribute && $combination_reference) {
            $id_product_attribute = (int) Db::getInstance()->getValue('SELECT `id_product_attribute` FROM `' . _DB_PREFIX_ . "product_attribute`
                WHERE `id_product` = " . (int) $id_product . " AND `reference` = '" . pSQL($combination_reference) . "'");
        }

        if ($id_product_attribute) {
            Db::getInstance()->execute('INSERT IGNORE INTO `' . _DB_PREFIX_ . 'product_attribute_image` (`id_product_attribute`, `id_image`) VALUES (' . (int) $id_product_attribute . ', ' . (int) $id_image . ')');
        }
    }

    protected static function fixCoverImage($id_product, $id_image)
    {
        $hasCover = (bool) Db::getInstance()->getValue('SELECT `id_image` FROM `' . _DB_PREFIX_ . 'image` WHERE `id_product` = ' . (int) $id_product . ' AND `cover` = 1');
        if (!$hasCover) {
            Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'image` SET `cover` = 1 WHERE `id_product` = ' . (int) $id_product . ' AND `id_image` = ' . (int) $id_image);
            Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'image_shop` SET `cover` = 1 WHERE `id_product` = ' . (int) $id_product . ' AND `id_image` = ' . (int) $id_image);
        }
    }

    protected static function markFailed(array $row)
    {
        $attempts = (int) $row['attempts'] + 1;
        if ($attempts >= self::MAX_ATTEMPTS) {
            self::delete((int) $row['id_elegantaleasyimport_missing_image']);
            return 'deleted';
        }

        Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . "elegantaleasyimport_missing_image`
            SET `attempts` = " . (int) $attempts . ",
                `last_error` = 'Image not found',
                `last_attempt_at` = '" . pSQL(date('Y-m-d H:i:s')) . "',
                `next_attempt_at` = '" . pSQL(self::getNextAttemptDate($attempts)) . "',
                `date_upd` = '" . pSQL(date('Y-m-d H:i:s')) . "'
            WHERE `id_elegantaleasyimport_missing_image` = " . (int) $row['id_elegantaleasyimport_missing_image']);

        return 'failed';
    }

    protected static function delete($id)
    {
        return Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'elegantaleasyimport_missing_image` WHERE `id_elegantaleasyimport_missing_image` = ' . (int) $id);
    }

    protected static function getNextAttemptDate($attempts)
    {
        $attempts = (int) $attempts;
        if ($attempts <= 15) {
            $days = 1;
        } elseif ($attempts <= 35) {
            $days = 2;
        } else {
            $days = 5;
        }

        return date('Y-m-d H:i:s', strtotime('+' . (int) $days . ' day'));
    }
}
