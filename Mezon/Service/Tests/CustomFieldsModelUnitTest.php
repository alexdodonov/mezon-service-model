<?php
namespace Mezon\Service\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\Service\CustomFieldsModel;
use Mezon\PdoCrud\Tests\PdoCrudMock;

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
        $obj = new \stdClass();
        $obj->field_value = '111';

        return [
            [
                [],
                'default'
            ],
            [
                [
                    $obj
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
        $model = new CustomFieldsModel('existing-entity');
        $model->setConnection($connection = new PdoCrudMock());
        $connection->selectResults[] = $data;

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
        $model = new CustomFieldsModel('entity');
        $model->setConnection($connection = new PdoCrudMock());
        $connection->selectResults[] = [
            $this->customField('existing-field', 1)
        ];
        $connection->selectResults[] = [
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
        $model = new CustomFieldsModel('entity');
        $model->setConnection($connection = new PdoCrudMock());

        // test body
        $model->updateCustomFieldWithoutValidations(1, 'updating-field', 'new-value');

        // assertions
        $this->assertEquals(1, $connection->executeWasCalledCounter);
    }

    /**
     * Testing method deleteCustomFieldsForObject
     */
    public function testDeleteCustomFieldsForObject(): void
    {
        // setup
        $model = new CustomFieldsModel('delete-entity');
        $model->setConnection($connection = new PdoCrudMock());
        $connection->deleteWasCalledCounter = 0;

        // test body
        $model->deleteCustomFieldsForObject(1, [
            'deleting-field'
        ]);

        // assertions
        $this->assertEquals(1, $connection->executeWasCalledCounter);
    }

    /**
     * Testing getCustomFieldsForRecords method
     */
    public function testGetCustomFieldsForRecords(): void
    {
        // setup
        $model = new CustomFieldsModel('get-entity');
        $model->setConnection($connection = new PdoCrudMock());
        $connection->selectResults[] = [
            [
                'field_name' => 'field',
                'field_value' => true
            ]
        ];
        $connection->selectResults[] = [
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
        $model = new CustomFieldsModel('get-entity');
        $records = [
            []
        ];

        // assertions
        $this->expectException(\Exception::class);

        // test body
        $model->getCustomFieldsForRecords($records);
    }

    /**
     * Testing data provider
     *
     * @return array testing data
     */
    public function setFieldForObjectDataProvider(): array
    {
        return [
            // #0, the first case - field does not exist
            [
                function (): object {
                    // setup method
                    $connection = new PdoCrudMock();
                    $connection->selectResults[] = [];

                    return $connection;
                },
                function (PdoCrudMock $connection): void {
                    // asserting method
                    $this->assertEquals(1, $connection->executeWasCalledCounter);
                }
            ],
            // #1, the first case - field exists
            [
                function (): object {
                    // setup method
                    $connection = new PdoCrudMock();
                    $connection->selectResults[] = [
                        [
                            'field_name' => 'setting-field',
                            'field_value' => 1
                        ]
                    ];

                    return $connection;
                },
                function (PdoCrudMock $connection): void {
                    // asserting method
                    $this->assertEquals(1, $connection->executeWasCalledCounter);
                }
            ]
        ];
    }

    /**
     * Testing method setFieldForObject
     *
     * @param callable $setup
     *            setup method
     * @param callable $assertions
     *            assertion method
     * @dataProvider setFieldForObjectDataProvider
     */
    public function testSetFieldForObject(callable $setup, callable $assertions): void
    {
        // setup
        $model = new CustomFieldsModel('get-entity');
        $model->setConnection($connection = $setup());

        // test body
        $model->setFieldForObject(1, 'setting-field', 'some-field-value');

        // assertions
        $assertions($connection);
    }
}
