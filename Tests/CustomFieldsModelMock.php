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
    
    public $selectResult = [];
    
    public function getConnection(string $connectionName = 'default-db-connection')
    {
        $mock = new PdoCrudMock();
        $mock->selectResult = $this->selectResult;
        
        return $mock;
    }
}
