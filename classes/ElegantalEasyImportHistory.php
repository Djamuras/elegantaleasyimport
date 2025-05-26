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
 * This is an object model class used to manage import history logs
 */
class ElegantalEasyImportHistory extends ElegantalEasyImportObjectModel
{
    public $tableName = 'elegantaleasyimport_history';
    public static $definition = [
        'table' => 'elegantaleasyimport_history',
        'primary' => 'id_elegantaleasyimport_history',
        'fields' => [
            'id_elegantaleasyimport' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true],
            'total_number_of_products' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'],
            'number_of_products_processed' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'],
            'number_of_products_created' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'],
            'number_of_products_updated' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'],
            'number_of_products_deleted' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'],
            'ids_of_products_created' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'ids_of_products_updated' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'ids_of_products_deleted' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'date_started' => ['type' => self::TYPE_DATE, 'validate' => 'isDate'],
            'date_ended' => ['type' => self::TYPE_DATE, 'validate' => 'isDate'],
        ],
    ];

    /**
     * Class fields
     *
     * @var mixed
     */
    public $id_elegantaleasyimport_history;
    public $id_elegantaleasyimport;
    public $total_number_of_products;
    public $number_of_products_processed;
    public $number_of_products_created;
    public $number_of_products_updated;
    public $number_of_products_deleted;
    public $ids_of_products_created;
    public $ids_of_products_updated;
    public $ids_of_products_deleted;
    public $date_started;
    public $date_ended;

    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        parent::__construct($id, $id_lang, $id_shop);

        if ($this->date_started == '0000-00-00 00:00:00') {
            $this->date_started = null;
        }
        if ($this->date_ended == '0000-00-00 00:00:00') {
            $this->date_ended = null;
        }
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public static function createNew($id_elegantaleasyimport)
    {
        $history = new self();
        $history->id_elegantaleasyimport = $id_elegantaleasyimport;
        $history->total_number_of_products = 0;
        $history->number_of_products_processed = 0;
        $history->number_of_products_created = 0;
        $history->number_of_products_updated = 0;
        $history->number_of_products_deleted = 0;
        $history->date_started = date('Y-m-d H:i:s');
        $history->date_ended = date('Y-m-d H:i:s');
        if (!$history->add()) {
            throw new Exception(Db::getInstance()->getMsgError());
        }

        return $history;
    }

    public function deleteOldLogs()
    {
        $date = date('Y-m-d H:i:s', strtotime('-1 week'));
        Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'elegantaleasyimport_history` WHERE `id_elegantaleasyimport` = ' . (int) $this->id_elegantaleasyimport . " AND `date_ended` < '" . pSQL($date) . "'");
    }

    public function getErrorsCount()
    {
        $count = ElegantalEasyImportError::model()->countAll([
            'condition' => [
                'id_elegantaleasyimport_history' => $this->id,
            ],
        ]);

        return (int) $count;
    }

    public function getProductsProcessed($entity, $type, $orderBy = '', $orderType = '', $limit = '', $offset = '')
    {
        $ids = [];
        $products = [];

        switch ($type) {
            case 'created':
                $ids = $this->ids_of_products_created ? explode(',', $this->ids_of_products_created) : [];
                break;
            case 'updated':
                $ids = $this->ids_of_products_updated ? explode(',', $this->ids_of_products_updated) : [];
                break;
            case 'deleted':
                $ids = $this->ids_of_products_deleted ? explode(',', $this->ids_of_products_deleted) : [];
                break;
            default:
                break;
        }

        if ($ids) {
            $sql = '';
            switch ($entity) {
                case 'product':
                    $sql = 'SELECT p.`id_product`, p.`reference`, p.`ean13` FROM `' . _DB_PREFIX_ . 'product` p WHERE p.`id_product` IN (' . implode(',', array_map('intval', $ids)) . ') ';
                    break;
                case 'combination':
                    $sql = 'SELECT p.`id_product`, p.`reference`, p.`ean13`, pa.`id_product_attribute`, pa.`reference` AS `combination_reference`, pa.`ean13` AS `combination_ean` 
                        FROM `' . _DB_PREFIX_ . 'product_attribute` pa 
                        INNER JOIN ' . _DB_PREFIX_ . 'product p ON (p.`id_product` = pa.`id_product`) 
                        WHERE pa.`id_product_attribute` IN (' . implode(',', array_map('intval', $ids)) . ') ';
                    break;
                default:
                    break;
            }
            if ($sql) {
                if ($orderBy) {
                    $sql .= 'ORDER BY ' . pSQL($orderBy) . ' ' . pSQL($orderType) . ' ';
                }
                if ($limit) {
                    $sql .= 'LIMIT ' . (int) $limit . ' ';
                }
                if ($offset) {
                    $sql .= 'OFFSET ' . (int) $offset;
                }
                $products = Db::getInstance()->executeS($sql);
            }
        }

        return $products;
    }
}
