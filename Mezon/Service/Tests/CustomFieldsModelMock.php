<?php
namespace Mezon\Service\Tests;

use Mezon\PdoCrud\Tests\PdoCrudMock;
use Mezon\Service\CustomFieldsModel;

class CustomFieldsModelMock extends CustomFieldsModel
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
