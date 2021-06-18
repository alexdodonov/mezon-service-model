<?php
namespace Mezon\Service;

use Mezon\PdoCrud\ConnectionTrait;
use Mezon\Functional\Fetcher;
use Mezon\PdoCrud\ApropriateConnectionTrait;

/**
 * Class CustomFieldsModel
 *
 * @package Service
 * @subpackage CustomFieldsModel
 * @author Dodonov A.A.
 * @version v.1.0 (2019/11/08)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Model for processing custom fields
 *
 * @author Dodonov A.A.
 */
class CustomFieldsModel
{

    use ApropriateConnectionTrait;

    /**
     * Table name
     */
    private $tableName = '';

    /**
     * Constructor
     *
     * @param string $tableName
     *            name of the table
     */
    public function __construct(string $tableName)
    {
        $this->tableName = $tableName;
    }

    /**
     * Method returns table name
     *
     * @return string Table name
     */
    protected function getCustomFieldsTemplateName(): string
    {
        return $this->tableName . '_custom_field';
    }

    /**
     * Getting custom fields for object
     *
     * @param int $objectId
     *            Object id
     * @param array $filter
     *            List of required fields or all
     * @return array Result of the fetching
     */
    public function getCustomFieldsForObject(int $objectId, array $filter = [
        '*'
    ]): array
    {
        $result = [];

        $this->getApropriateConnection()->prepare(
            'SELECT * FROM ' . $this->getCustomFieldsTemplateName() . ' WHERE object_id = :object_id');
        $this->getApropriateConnection()->bindParameter(':object_id', $objectId);
        $customFields = $this->getApropriateConnection()->executeSelect();

        foreach ($customFields as $field) {
            $fieldName = Fetcher::getField($field, 'field_name');

            // if the field in the list or all fields must be fetched
            if (in_array($fieldName, $filter) || in_array('*', $filter)) {
                $result[$fieldName] = Fetcher::getField($field, 'field_value');
            }
        }

        return $result;
    }

    /**
     * Deleting custom fields for object
     *
     * @param int $objectId
     *            Object id
     * @param array $filter
     *            List of required fields
     */
    public function deleteCustomFieldsForObject(int $objectId, array $filter = []): void
    {
        if (! empty($filter)) {
            $condition = 'field_name IN ("' . implode('", "', $filter) . '") AND ' . 'object_id = :object_id';

            $this->getApropriateConnection()->prepare(
                'DELETE FROM ' . $this->getCustomFieldsTemplateName() . ' WHERE ' . $condition);
            $this->getApropriateConnection()->bindParameter(':object_id', $objectId, \PDO::PARAM_INT);
            $this->getApropriateConnection()->execute();
        }
    }

    /**
     * Method updates custom field without any validations
     *
     * @param int $objectId
     *            Object id
     * @param string $fieldName
     *            Field name
     * @param string $fieldValue
     *            Field value
     */
    public function updateCustomFieldWithoutValidations(int $objectId, string $fieldName, string $fieldValue): void
    {
        $this->getApropriateConnection()->prepare(
            'UPDATE ' . $this->getCustomFieldsTemplateName() .
            ' SET field_value = :field_value WHERE field_name LIKE :field_name AND object_id = :object_id');
        $this->getApropriateConnection()->bindParameter(':field_value', $fieldValue, \PDO::PARAM_STR);
        $this->getApropriateConnection()->bindParameter(':field_name', $fieldName, \PDO::PARAM_STR);
        $this->getApropriateConnection()->bindParameter(':object_id', $objectId, \PDO::PARAM_INT);

        $this->getApropriateConnection()->execute();
    }

    /**
     * Method sets custom field
     *
     * @param int $objectId
     *            Object id
     * @param string $fieldName
     *            Field name
     * @param string $fieldValue
     *            Field value
     */
    public function setFieldForObject(int $objectId, string $fieldName, string $fieldValue): void
    {
        if (count($this->getCustomFieldsForObject($objectId, [
            $fieldName
        ])) > 0) {
            $this->updateCustomFieldWithoutValidations($objectId, $fieldName, $fieldValue);
        } else {
            $this->getApropriateConnection()->prepare(
                'INSERT INTO ' . $this->getCustomFieldsTemplateName() .
                ' (field_value, field_name, object_id) VALUES (:field_value, :field_name, :object_id)');
            $this->getApropriateConnection()->bindParameter(':field_value', $fieldValue, \PDO::PARAM_STR);
            $this->getApropriateConnection()->bindParameter(':field_name', $fieldName, \PDO::PARAM_STR);
            $this->getApropriateConnection()->bindParameter(':object_id', $objectId, \PDO::PARAM_INT);
            $this->getApropriateConnection()->execute();
        }
    }

    /**
     * Method fetches custom fields for record
     *
     * @param array $records
     *            List of records
     * @return array Transformed records
     */
    public function getCustomFieldsForRecords(array $records): array
    {
        foreach ($records as $i => $record) {
            $id = Fetcher::getField($record, 'id');

            if ($id === null) {
                throw (new \Exception('Field "id" was not found in record', - 1));
            }

            $records[$i]['custom'] = $this->getCustomFieldsForObject($id);
        }

        return $records;
    }

    /**
     * Method sets custom field for object
     *
     * @param int $objectId
     *            - object's id
     * @param string $fieldName
     *            - field's name
     * @return string field's value
     */
    public function getFieldForObject(int $objectId, string $fieldName, string $defaultValue): string
    {
        $this->getApropriateConnection()->prepare(
            'SELECT * FROM ' . $this->getCustomFieldsTemplateName() .
            ' WHERE object_id = :object_id AND field_name LIKE :field_name');
        $this->getApropriateConnection()->bindParameter(':object_id', $objectId);
        $this->getApropriateConnection()->bindParameter(':field_name', $fieldName, \PDO::PARAM_STR);

        $customField = $this->getApropriateConnection()->executeSelect();

        if (empty($customField)) {
            // field was not found
            return $defaultValue;
        }

        return $customField[0]->field_value;
    }

    /**
     * Checking if the custom field exists
     *
     * @param int $objectId
     *            object's id
     * @param string $fieldName
     *            field name
     * @return bool true if the field exists, false otherwise
     */
    public function customFieldExists(int $objectId, string $fieldName): bool
    {
        $fields = $this->getCustomFieldsForObject($objectId);

        return isset($fields[$fieldName]);
    }
}
