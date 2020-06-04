<?php
namespace Mezon\Service\Tests;

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
        $model->deleteCustomFieldsForObject(1, ['deleting-field']);

        // assertions
        $this->assertEquals(1, $model->getConnection()->deleteWasCalledCounter);
    }
}
