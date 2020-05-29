<?php

class PdoCrudMock extends \Mezon\PdoCrud\PdoCrud
{

    public $selectResult = [];

    public function select(
        string $fields,
        string $tableNames,
        string $where = '1 = 1',
        int $from = 0,
        int $limit = 1000000): array
    {
        return $this->selectResult;
    }
}

class CustomFieldsModelMock extends \Mezon\Service\CustomFieldsModel
{

    public $selectResult = [];

    public function getConnection(string $connectionName = 'default-db-connection')
    {
        $mock = new \PdoCrudMock();
        $mock->selectResult = $this->selectResult;

        return $mock;
    }
}

class CustomFieldsModelUnitTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Data compilator
     */
    protected function customField(string $fieldName, string $fieldValue): array
    {
        return [
            'field_name' => $fieldName,
            'field_value' => $fieldValue
        ];
    }

    /**
     * Data provider
     *
     * @return array testing data
     */
    public function getFieldForObjectDataProvider(): array
    {
        return [
            [
                [],
                'default'
            ],
            [
                [
                    [
                        'field_value' => '111'
                    ]
                ],
                '111'
            ]
        ];
    }

    /**
     * Testing getFieldForObject
     *
     * @param array $data
     *            custom fields of the object
     * @param string $expectedResult
     *            expected result of the call getFieldForObject
     * @dataProvider getFieldForObjectDataProvider
     */
    public function testGetExistingCustomField(array $data, string $expectedResult): void
    {
        // setup
        $model = new \CustomFieldsModelMock('entity');
        $model->selectResult = $data;

        // test body
        $actualResult = $model->getFieldForObject(1, 'id', 'default');

        // assertions
        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * Testing method customFieldExists
     */
    public function testCustomFieldExists(): void
    {
        // setup
        $model = new \CustomFieldsModelMock('entity');
        $model->selectResult = [
            $this->customField('existing-field', 1)
        ];

        // test body and assertions
        $this->assertTrue($model->customFieldExists(1, 'existing-field'));
        $this->assertFalse($model->customFieldExists(1, 'unexisting-field'));
    }
}
