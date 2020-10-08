<?php
namespace Mezon\Service\Tests;

use PHPUnit\Framework\TestCase;

class CustomFieldsModelUnitTest extends TestCase
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
        $model = new CustomFieldsModelMock('existing-entity');
        $model->getConnection()->selectResult = $data;

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
        $model = new CustomFieldsModelMock('entity');
        $model->getConnection()->selectResult = [
            $this->customField('existing-field', 1)
        ];

        // test body and assertions
        $this->assertTrue($model->customFieldExists(1, 'existing-field'));
        $this->assertFalse($model->customFieldExists(1, 'unexisting-field'));
    }

    /**
     * Testing method updateCustomFieldWithoutValidations
     */
    public function testUpdateCustomFieldWithoutValidations(): void
    {
        // setup
        $model = new CustomFieldsModelMock('entity');
        $model->getConnection()->updateWasCalledCounter = 0;

        // test body
        $model->updateCustomFieldWithoutValidations(1, 'updating-field', 'new-value');

        // assertions
        $this->assertEquals(1, $model->getConnection()->updateWasCalledCounter);
    }

    /**
     * Testing method deleteCustomFieldsForObject
     */
    public function testDeleteCustomFieldsForObject(): void
    {
        // setup
        $model = new CustomFieldsModelMock('delete-entity');
        $model->getConnection()->deleteWasCalledCounter = 0;

        // test body
        $model->deleteCustomFieldsForObject(1, [
            'deleting-field'
        ]);

        // assertions
        $this->assertEquals(1, $model->getConnection()->deleteWasCalledCounter);
    }

    /**
     * Testing getCustomFieldsForRecords method
     */
    public function testGetCustomFieldsForRecords(): void
    {
        // setup
        $model = new CustomFieldsModelMock('get-entity');
        $model->getConnection()->selectResult = [
            [
                'field_name' => 'field',
                'field_value' => true
            ]
        ];
        $records = [
            [
                'id' => 1
            ],
            [
                'id' => 2
            ]
        ];

        // test body
        $result = $model->getCustomFieldsForRecords($records);

        // assertions
        $this->assertArrayHasKey('custom', $result[0]);
        $this->assertArrayHasKey('custom', $result[1]);
        $this->assertArrayHasKey('field', $result[0]['custom']);
        $this->assertArrayHasKey('field', $result[1]['custom']);
        $this->assertTrue($result[0]['custom']['field']);
        $this->assertTrue($result[1]['custom']['field']);
    }

    /**
     * Testing getCustomFieldsForRecords method with invaid data
     */
    public function testGetCustomFieldsForRecordsInvalid(): void
    {
        // setup
        $model = new CustomFieldsModelMock('get-entity');
        $records = [
            []
        ];

        // assertions
        $this->expectException(\Exception::class);

        // test body
        $model->getCustomFieldsForRecords($records);
    }
}
