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
 * This is an object model class used to manage CSV rows saved in database
 */
class ElegantalEasyImportData extends ElegantalEasyImportObjectModel
{
    public $tableName = 'elegantaleasyimport_data';
    public static $definition = [
        'table' => 'elegantaleasyimport_data',
        'primary' => 'id_elegantaleasyimport_data',
        'fields' => [
            'id_elegantaleasyimport' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true],
            'id_reference' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'id_reference_comb' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
            'csv_row' => ['type' => self::TYPE_STRING, 'validate' => 'isString'],
        ],
    ];

    /**
     * Class fields
     *
     * @var mixed
     */
    public $id_elegantaleasyimport_data;
    public $id_elegantaleasyimport;
    public $id_reference;
    public $id_reference_comb;
    public $csv_row;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
