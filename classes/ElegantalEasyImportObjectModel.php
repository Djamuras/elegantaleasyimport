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
 * This is a parent class for all object models in the module
 */
class ElegantalEasyImportObjectModel extends ObjectModel
{
    /**
     * Table name of this model
     *
     * @var string
     */
    public $tableName;

    /**
     * Replicate default language value to all other languages on multilang property if the other languages are not set
     *
     * @var bool
     */
    public $replicate_all_languages = true;

    /**
     * Module
     *
     * @var object
     */
    private $module;

    /**
     * Module settings
     *
     * @var array
     */
    private $settings = [];

    /**
     * Cached models
     *
     * @var array
     */
    private static $_models = [];

    /**
     * Object properties should be defined here.
     * Instead of defining properties individually in each class, it is better to do it here because we already have list of properties.
     *
     * @param int $id
     * @param int $id_lang
     * @param int $id_shop
     */
    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        // Get definition. Prestashop way.
        $class_name = get_class($this);
        $def = ObjectModel::getDefinition($class_name);

        // Init properties of object
        $languages = Language::getLanguages(false);
        foreach ($def['fields'] as $attr => $options) {
            if (isset($def['multilang']) && $def['multilang'] && isset($options['lang']) && $options['lang'] == true) {
                foreach ($languages as $lang) {
                    $this->{$attr}[$lang['id_lang']] = null;
                }
            } else {
                $this->$attr = null;
            }
        }

        parent::__construct($id, $id_lang, $id_shop);

        // Set primary key value
        $pk = $this->def['primary'];
        $this->{$pk} = $this->id;
    }

    /**
     * Returns the static model of the class. The model returned is a static instance of the class.
     *
     * @param string $className
     *
     * @return class
     */
    public static function model($className = __CLASS__)
    {
        if (isset(self::$_models[$className])) {
            return self::$_models[$className];
        } else {
            $model = self::$_models[$className] = new $className(null);

            return $model;
        }
    }

    /**
     * Checks if object model is multi-language
     *
     * @return bool
     */
    public function isMultilang()
    {
        return !empty($this->def['multilang']);
    }

    /**
     * Returns module object
     *
     * @return object
     *
     * @throws Exception
     */
    public function getModule()
    {
        if (empty($this->module)) {
            $module_name = basename(realpath(dirname(__FILE__) . '/..'));
            $this->module = Module::getInstanceByName($module_name);
            if (!$this->module || !Validate::isLoadedObject($this->module)) {
                throw new Exception('Unable to load the module.');
            }
        }

        return $this->module;
    }

    /**
     * Returns module settings
     *
     * @return array
     */
    public function getModuleSettings()
    {
        if (empty($this->settings)) {
            $module = $this->getModule();
            $this->settings = $module->getSettings();
        }

        return $this->settings;
    }

    /**
     * Returns translated string using module function
     *
     * @param string $text
     *
     * @return string
     */
    public function l($text)
    {
        // ObjectModel has a function called trans !
        $module = $this->getModule();

        return $module->l($text);
    }

    /**
     * Returns attribute values of the model
     *
     * @return array
     */
    public function getAttributes()
    {
        $attrs = [];
        $attrs[$this->def['primary']] = $this->id;

        foreach ($this->def['fields'] as $attr => $options) {
            if (isset($this->def['multilang']) && $this->def['multilang'] && isset($options['lang']) && $options['lang'] == true) {
                $languages = Language::getLanguages(false);
                foreach ($languages as $lang) {
                    if (is_array($this->{$attr}) && isset($this->{$attr}[$lang['id_lang']])) {
                        $attrs[$attr][$lang['id_lang']] = $this->{$attr}[$lang['id_lang']];
                    } else {
                        $attrs[$attr][$lang['id_lang']] = null;
                    }
                }
            } else {
                $attrs[$attr] = $this->$attr;
            }
        }

        return $attrs;
    }

    /**
     * Returns attributes of one object by finding from db
     *
     * @return array
     */
    public function find($criteria = [])
    {
        $id_shop = (int) Context::getContext()->shop->id;
        $id_lang = (int) Context::getContext()->language->id;

        $sql = new DbQuery();
        $sql->select('*');
        $sql->from($this->tableName, 't');

        if ($this->isMultishop()) {
            $sql->innerJoin($this->tableName . '_shop', 's', 's.' . $this->def['primary'] . ' = t.' . $this->def['primary'] . ' AND s.id_shop = ' . (int) $id_shop);
        }

        if ($this->isMultilang()) {
            $sql->innerJoin($this->tableName . '_lang', 'l', 'l.' . $this->def['primary'] . ' = t.' . $this->def['primary'] . ' AND l.id_lang = ' . (int) $id_lang);
        }

        if (!empty($criteria['condition']) && is_array($criteria['condition'])) {
            $where = '';
            foreach ($criteria['condition'] as $key => $value) {
                $key = (strpos($key, '.') === false) ? 't.' . $key : $key;
                $where .= (Tools::strlen($where) > 0 ? ' AND ' : '') . pSQL($key) . ' = ' . (Validate::isInt($value) ? (int) $value : "'" . pSQL($value) . "' ");
            }
            if (!empty($where)) {
                $sql->where($where);
            }
        }

        if (!empty($criteria['order'])) {
            $orderBy = (strpos($criteria['order'], '.') === false && $criteria['order'] != 'RAND()') ? 't.' . $criteria['order'] : $criteria['order'];
            $sql->orderBy($orderBy);
        }

        return Db::getInstance()->getRow($sql);
    }

    /**
     * Returns attributes of objects by finding from db
     *
     * @return array
     */
    public function findAll($criteria = [])
    {
        $id_shop = (int) Context::getContext()->shop->id;
        $id_lang = (int) Context::getContext()->language->id;

        $sql = new DbQuery();
        $sql->select('*');
        $sql->from($this->tableName, 't');

        if ($this->isMultishop()) {
            $sql->innerJoin($this->tableName . '_shop', 's', 's.' . $this->def['primary'] . ' = t.' . $this->def['primary'] . ' AND s.id_shop = ' . (int) $id_shop);
        }

        if ($this->isMultilang()) {
            $sql->innerJoin($this->tableName . '_lang', 'l', 'l.' . $this->def['primary'] . ' = t.' . $this->def['primary'] . ' AND l.id_lang = ' . (int) $id_lang);
        }

        if (!empty($criteria['condition']) && is_array($criteria['condition'])) {
            $where = '';
            foreach ($criteria['condition'] as $key => $value) {
                $key = (strpos($key, '.') === false) ? 't.' . $key : $key;
                $where .= (Tools::strlen($where) > 0) ? ' AND ' : '';
                if (is_array($value)) {
                    $count_value = count($value);
                    if ($count_value > 0) {
                        $where .= pSQL($key) . ' IN (';
                        foreach ($value as $key_val => $val) {
                            $where .= ($count_value > 0 && $key_val > 0) ? ', ' : '';
                            $where .= Validate::isInt($val) ? (int) $val : "'" . pSQL($val) . "'";
                        }
                        $where .= ')';
                    }
                } else {
                    $where .= pSQL($key) . ' = ' . (Validate::isInt($value) ? (int) $value : "'" . pSQL($value) . "' ");
                }
            }
            if (!empty($where)) {
                $sql->where($where);
            }
        }

        if (!empty($criteria['order'])) {
            $orderBy = (strpos($criteria['order'], '.') === false && $criteria['order'] != 'RAND()') ? 't.' . $criteria['order'] : $criteria['order'];
            $sql->orderBy($orderBy);
        }

        if (!empty($criteria['limit'])) {
            $offset = 0;
            if (isset($criteria['offset'])) {
                $offset = (int) $criteria['offset'];
            }
            $sql->limit($criteria['limit'], $offset);
        }

        return Db::getInstance()->executeS($sql, true, false);
    }

    /**
     * Returns number of records
     *
     * @param array $criteria
     *
     * @return int
     */
    public function countAll($criteria = [])
    {
        $id_shop = (int) Context::getContext()->shop->id;
        $id_lang = (int) Context::getContext()->language->id;

        $sql = new DbQuery();
        $sql->select('COUNT(*)');
        $sql->from($this->tableName, 't');

        if ($this->isMultishop()) {
            $sql->innerJoin($this->tableName . '_shop', 's', 's.' . $this->def['primary'] . ' = t.' . $this->def['primary'] . ' AND s.id_shop = ' . (int) $id_shop);
        }

        if ($this->isMultilang()) {
            $sql->innerJoin($this->tableName . '_lang', 'l', 'l.' . $this->def['primary'] . ' = t.' . $this->def['primary'] . ' AND l.id_lang = ' . (int) $id_lang);
        }

        if (!empty($criteria['condition']) && is_array($criteria['condition'])) {
            $where = '';
            foreach ($criteria['condition'] as $key => $value) {
                $key = (strpos($key, '.') === false) ? 't.' . $key : $key;
                $where .= (Tools::strlen($where) > 0 ? ' AND ' : '') . pSQL($key) . ' = ' . (Validate::isInt($value) ? (int) $value : "'" . pSQL($value) . "' ");
            }
            if (!empty($where)) {
                $sql->where($where);
            }
        }

        return (int) Db::getInstance()->getValue($sql);
    }

    /**
     * Validates and sets submitted values to model attributes
     *
     * @param bool $htmlentities
     *
     * @return array
     */
    public function validateAndAssignModelAttributes($htmlentities = true)
    {
        $languages = Language::getLanguages(false);
        if (method_exists($this, 'cacheFieldsRequiredDatabase')) {
            $this->cacheFieldsRequiredDatabase();
        }
        $errors = [];
        $required_fields_database = (isset(self::$fieldsRequiredDatabase[get_class($this)])) ? self::$fieldsRequiredDatabase[get_class($this)] : [];
        foreach ($this->def['fields'] as $field => $data) {
            // Init model attribute
            $this->{$field} = property_exists($this, $field) ? $this->{$field} : null;

            // Check if field is required by user
            if (in_array($field, $required_fields_database)) {
                $data['required'] = true;
            }

            // Setting submitted value to field
            if (isset($data['lang']) && $data['lang'] == true) {
                $firstValue = null;
                foreach ($languages as $lan) {
                    $value = Tools::getValue($field . '_' . $lan['id_lang']);
                    if (!$firstValue && $value) {
                        $firstValue = $value;
                    }
                    if (!$value && $firstValue && $this->replicate_all_languages) {
                        $value = $firstValue;
                    }
                    $this->{$field}[$lan['id_lang']] = $value;
                    if ($lan['id_lang'] == Context::getContext()->language->id && ($error = $this->validateModelAttribute($field, $value, $data, $htmlentities, $lan['iso_code'])) !== true) {
                        $errors[$field] = $error;
                    }
                }
            } else {
                $value = Tools::getValue($field, $this->{$field});
                if (is_array($value)) {
                    $value = ElegantalEasyImportTools::storable($value);
                }
                $this->{$field} = $value;

                if (($error = $this->validateModelAttribute($field, $value, $data, $htmlentities)) !== true) {
                    $errors[$field] = $error;
                }
            }
        }

        return $errors;
    }

    /**
     * Validates attribute of model object
     *
     * @param string $field
     * @param string $value
     * @param array $data
     * @param bool $htmlentities
     * @param string $lang
     *
     * @return bool|string
     */
    protected function validateModelAttribute($field, $value, $data, $htmlentities = true, $lang = '')
    {
        $error = '';
        $field_name = ucwords(str_replace('_', ' ', self::displayFieldName($field, get_class($this), $htmlentities)));

        // Checking for required fields
        if (isset($data['required']) && $data['required'] && empty($value) && $value !== '0') {
            if (!$this->id || $field != 'passwd') {
                $error = '<b>' . $field_name . ' ' . $lang . ' </b> is required.';
            }
        }

        // Checking for maximum fields sizes
        if (isset($data['size']) && !empty($value) && Tools::strlen($value) > $data['size']) {
            $error = sprintf('%1$s is too long. Maximum length: %2$d', $field_name . ' ' . $lang, $data['size']);
        }

        // Checking for fields validity
        // Hack for postcode required for country which does not have postcodes
        if (!empty($value) || $value === '0' || ($field == 'postcode' && $value == '0')) {
            if (isset($data['validate']) && !call_user_func(['Validate', $data['validate']], $value) && (!empty($value) || $data['required'])) {
                $error = '<b>' . $field_name . ' ' . $lang . '</b> is invalid.';
            }
        }

        return $error ? $error : true;
    }
}
