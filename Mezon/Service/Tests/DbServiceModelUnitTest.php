<?php
namespace Mezon\Service\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\FieldsSet;
use Mezon\Service\DbServiceModel;
use Mezon\PdoCrud\Tests\PdoCrudMock;

/** @psalm-suppress PropertyNotSetInConstructor */
class DbServiceModelUnitTest extends TestCase
{

    /**
     * Method returns field set
     *
     * @return array field set
     */
    private function getFieldSet(): array
    {
        return [
            'id' => [
                'type' => 'integer',
            ],
        ];
    }

    /**
     * Test data for testConstructor test
     *
     * @return array
     */
    public function constructorTestData(): array
    {
        return [
            [
                $this->getFieldSet(),
                'id',
            ],
            [
                '*',
                '*',
            ],
            [
                new FieldsSet($this->getFieldSet()),
                'id',
            ],
        ];
    }

    /**
     * Testing constructor
     *
     * @param mixed $data
     *            Parameterfor constructor
     * @param string $origin
     *            original data for validation
     * @dataProvider constructorTestData
     */
    public function testConstructor($data, string $origin): void
    {
        // setup and test body
        $model = new DbServiceModel($data, 'entity_name_constructor');

        // assertions
        $this->assertTrue($model->hasField($origin), 'Invalid contruction');
        $this->assertEquals($origin, $model->getFieldsNames());
        $this->assertFalse($model->hasCustomFields(), 'Invalid contruction');
    }

    /**
     * Testing constructor with exception
     */
    public function testConstructorException(): void
    {
        // setup and assertions
        $this->expectException(\Exception::class);

        // test body
        new DbServiceModel(new \stdClass(), 'entity_name');
    }

    /**
     * Testing getFieldsNames method
     */
    public function testGetFieldsNames(): void
    {
        // setup and test body
        $model = new DbServiceModel($this->getFieldSet(), 'entity_name');

        // assertions
        $this->assertEquals('id', implode('', $model->getFields()));
    }

    /**
     * Testing tricky path of setting table name
     */
    public function testSetTableName(): void
    {
        // setup and test body
        $model = new DbServiceModel($this->getFieldSet(), 'entity-name');

        // assertions
        $this->assertEquals('`entity-name`', $model->getTableName());
    }

    /**
     * Testing getEntityName method
     */
    public function testGetEntityName(): void
    {
        // setup
        $model = new DbServiceModel($this->getFieldSet(), 'table-name', 'table-entity-name');

        // test body and assertions
        $this->assertEquals('table-entity-name', $model->getEntityName());
    }

    /**
     * Testing getFieldType method
     */
    public function testGetFieldType(): void
    {
        // setup
        $model = new DbServiceModel($this->getFieldSet(), 'table-name');

        // test body and assertions
        $this->assertEquals('integer', $model->getFieldType('id'));
    }

    /**
     * Testing validateFieldExistance method
     */
    public function testValidateFieldExistence(): void
    {
        // setup and assertions
        $model = new DbServiceModel($this->getFieldSet(), 'table-name');
        $this->expectException(\Exception::class);

        // test body
        $model->validateFieldExistance('title');
    }

    /**
     * Testing method
     */
    public function testGetApropriateConnection(): void
    {
        // setup
        $model = new DbServiceModel($this->getFieldSet(), 'entity_name');
        $model->setConnection(new PdoCrudMock());

        // test body 1
        $connection = $model->getConnection();

        // assertions 1
        $this->assertInstanceOf(PdoCrudMock::class, $connection);

        // test body 2
        $connection = $model->getApropriateConnection();

        // assertions 2
        $this->assertInstanceOf(PdoCrudMock::class, $connection);
    }
}
