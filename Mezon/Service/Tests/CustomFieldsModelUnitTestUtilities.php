<?php
namespace Mezon\Service\Tests;

class CustomFieldsModelUnitTestUtilities
{

    /**
     * Data compilator
     *
     * @param string $fieldName
     *            field name
     * @param string $fieldValue
     *            field value
     * @return object object
     */
    public static function customField(string $fieldName = 'name', string $fieldValue = 'value'): object
    {
        $record = new \stdClass();
        $record->field_name = $fieldName;
        $record->field_value = $fieldValue;

        return $record;
    }
}
