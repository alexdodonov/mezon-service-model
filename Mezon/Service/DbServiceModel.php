<?php
namespace Mezon\Service;

use Mezon\FieldsSet;

/**
 * Class DbServiceModel
 *
 * @package Service
 * @subpackage DbServiceModel
 * @author Dodonov A.A.
 * @version v.1.0 (2019/10/18)
 * @copyright Copyright (c) 2019, http://aeon.su
 */

/**
 * Default DB model for the service
 *
 * @author Dodonov A.A.
 */
class DbServiceModel extends DbServiceModelBase
{

    /**
     * Fields algorithms
     *
     * @var FieldsSet
     */
    private $fieldsSet;

    /**
     * Entity name
     *
     * @var string
     */
    private $entityName = '';

    /**
     * Constructor
     *
     * @param
     *            string|FieldsSet|array<string, array{type: string, title: string}> $fields fields of the model
     * @param string $tableName
     *            name of the table
     * @param string $entityName
     *            name of the entity
     * @psalm-suppress MissingParamType
     */
    public function __construct($fields = '*', string $tableName = '', string $entityName = '')
    {
        parent::__construct($tableName);

        $this->entityName = $entityName;

        if (is_string($fields)) {
            $this->fieldsSet = new FieldsSet([
                '*' => [
                    'type' => 'string',
                    'title' => 'All fields'
                ]
            ]);
        } elseif (is_array($fields)) {
            /** @var array<string, array{type: string, title: string}> $fields */
            $this->fieldsSet = new FieldsSet($fields);
        } elseif ($fields instanceof FieldsSet) {
            $this->fieldsSet = $fields;
        } else {
            throw (new \Exception('Invalid fields description', - 1));
        }
    }

    /**
     * Method returns list of all fields as string
     *
     * @return string list of all fields as string
     */
    public function getFieldsNames(): string
    {
        return implode(', ', $this->fieldsSet->getFieldsNames());
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
        return $this->fieldsSet->hasField($fieldName);
    }

    /**
     * Method returns true if the custom field exists
     *
     * @return bool
     */
    public function hasCustomFields(): bool
    {
        return $this->fieldsSet->hasCustomFields();
    }

    /**
     * Method validates if the field $field exists
     *
     * @param string $field
     *            Field name
     */
    public function validateFieldExistance(string $field): void
    {
        $this->fieldsSet->validateFieldExistance($field);
        // @codeCoverageIgnoreStart
    }

    // @codeCoverageIgnoreEnd

    /**
     * Method returns fields list
     *
     * @return string[] fields list
     */
    public function getFields(): array
    {
        return $this->fieldsSet->getFieldsNames();
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
        return $this->fieldsSet->getFieldType($fieldName);
    }
}
