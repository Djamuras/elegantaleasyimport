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
 * This is an object model class used to manage import history error logs
 */
class ElegantalEasyImportError extends ElegantalEasyImportObjectModel
{
    public $tableName = 'elegantaleasyimport_error';
    public static $definition = [
        'table' => 'elegantaleasyimport_error',
        'primary' => 'id_elegantaleasyimport_error',
        'fields' => [
            'id_elegantaleasyimport_history' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true],
            'product_id_reference' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'error' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'date_created' => ['type' => self::TYPE_DATE, 'validate' => 'isDate'],
        ],
    ];

    /**
     * Class fields
     *
     * @var mixed
     */
    public $id_elegantaleasyimport_error;
    public $id_elegantaleasyimport_history;
    public $product_id_reference;
    public $error;
    public $date_created;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
