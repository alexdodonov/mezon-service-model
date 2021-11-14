<?php
namespace Mezon\Service;

use Mezon\FieldsSet;
use Mezon\PdoCrud\ApropriateConnectionTrait;

/**
 * Class DbServiceModel
 *
 * @package Service
 * @subpackage DbServiceModel
 * @author Dodonov A.A.
 * @version v.1.0 (2019/10/18)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Default DB model for the service
 *
 * @author Dodonov A.A.
 */
class DbServiceModel extends ServiceModel
{

    use ApropriateConnectionTrait;

    /**
     * Table name
     *
     * @var string
     */
    private $tableName = '';

    /**
     * Fields algorithms
     *
     * @var ?FieldsSet
     */
    private $fieldsSet = null;

    /**
     * Method returns $fieldsSet
     *
     * @return FieldsSet
     */
    private function getFieldSet(): FieldsSet
    {
        if ($this->fieldsSet === null) {
            throw (new \Exception('Field fieldsSet is not setup', - 1));
        }

        return $this->fieldsSet;
    }

    /**
     * Entity name
     *
     * @var string
     */
    private $entityName = '';

    /**
     * Constructor
     *
     * @param mixed $fields
     *            fields of the model
     * @param string $tableName
     *            name of the table
     * @param string $entityName
     *            name of the entity
     */
    public function __construct($fields = '*', string $tableName = '', string $entityName = '')
    {
        $this->setTableName($tableName);

        $this->entityName = $entityName;

        if (is_string($fields)) {
            $this->fieldsSet = new FieldsSet([
                '*' => [
                    'type' => 'string',
                    'title' => 'All fields'
                ]
            ]);
        } elseif (is_array($fields)) {
            $this->fieldsSet = new FieldsSet($fields);
        } elseif ($fields instanceof FieldsSet) {
            $this->fieldsSet = $fields;
        } else {
            throw (new \Exception('Invalid fields description', - 1));
        }
    }

    /**
     * Method sets table name
     *
     * @param string $tableName
     *            table name
     */
    protected function setTableName(string $tableName = ''): void
    {
        if (strpos($tableName, '-') !== false && strpos($tableName, '`') === false) {
            $tableName = "`$tableName`";
        }

        $this->tableName = $tableName;
    }

    /**
     * Method returns table name
     *
     * @return string table name
     */
    public function getTableName(): string
    {
        return $this->tableName;
    }

    /**
     * Method returns list of all fields as string
     *
     * @return string list of all fields as string
     */
    public function getFieldsNames(): string
    {
        return implode(', ', $this->getFieldSet()->getFieldsNames());
    }

    /**
     * Method returns true if the field exists
     *
     * @param string $fieldName
     *            field name
     * @return bool
     */
    public function hasField(string $fieldName): bool
    {
        return $this->getFieldSet()->hasField($fieldName);
    }

    /**
     * Method returns true if the custom field exists
     *
     * @return bool
     */
    public function hasCustomFields(): bool
    {
        return $this->getFieldSet()->hasCustomFields();
    }

    /**
     * Method validates if the field $field exists
     *
     * @param string $field
     *            Field name
     */
    public function validateFieldExistance(string $field): void
    {
        $this->getFieldSet()->validateFieldExistance($field);
    }

    /**
     * Method returns fields list
     *
     * @return string[] fields list
     */
    public function getFields(): array
    {
        return $this->getFieldSet()->getFieldsNames();
    }

    /**
     * Method returns entity name
     *
     * @return string entity name
     */
    public function getEntityName(): string
    {
        return $this->entityName;
    }

    /**
     * Method returns field type
     *
     * @param string $fieldName
     *            field name
     * @return string field type
     */
    public function getFieldType(string $fieldName): string
    {
        return $this->getFieldSet()->getFieldType($fieldName);
    }
}
