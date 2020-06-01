<?php
namespace Mezon\Service\Tests;

// TODO remove this class and use \Mezon\PdoCrud\Tests\PdoCrudMock
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

    /**
     * Counter for update method calls
     *
     * @var integer
     */
    public $updateWasCalledCounter = 0;

    /**
     * Updating records
     *
     * @param string $tableName
     *            Table name
     * @param array $record
     *            Updating records
     * @param string $where
     *            Condition
     * @param int $limit
     *            Liti for afffecting records
     * @return int Count of updated records
     */
    public function update(string $tableName, array $record, string $where, int $limit = 10000000)
    {
        $this->updateWasCalledCounter ++;
    }
}

class CustomFieldsModelMock extends \Mezon\Service\CustomFieldsModel
{

    var $id = false;

    var $field = false;

    var $value = false;

    public function setfieldForObject($id, $field, $value): void
    {
        $this->id = $id;
        $this->field = $field;
        $this->value = $value;
    }

    private $connection = [];

    public function getConnection(string $connectionName = 'default')
    {
        if (isset($this->connection[$connectionName]) === false) {
            $this->connection[$connectionName] = new PdoCrudMock();
        }

        return $this->connection[$connectionName];
    }
}
