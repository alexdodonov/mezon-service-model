<?php
namespace Mezon\Service;

use Mezon\PdoCrud\ApropriateConnectionTrait;

/**
 * Class DbServiceModelBase
 *
 * @package Service
 * @subpackage DbServiceModelBase
 * @author Dodonov A.A.
 * @version v.1.0 (2019/10/18)
 * @copyright Copyright (c) 2019, http://aeon.su
 */

/**
 * Default DB model for the service
 *
 * @author Dodonov A.A.
 */
class DbServiceModelBase extends ServiceModel
{

    use ApropriateConnectionTrait;

    /**
     * Table name
     *
     * @var string
     */
    private $tableName = '';

    /**
     * Constructor
     *
     * @param string $tableName
     *            name of the table
     * @psalm-suppress MissingParamType
     */
    public function __construct(string $tableName = '')
    {
        $this->setTableName($tableName);
    }

    /**
     * Method sets table name
     *
     * @param string $tableName
     *            table name
     */
    protected function setTableName(string $tableName = ''): void
    {
        if (strpos($tableName, '-') !== false && strpos($tableName, '`') === false) {
            $tableName = "`$tableName`";
        }

        $this->tableName = $tableName;
    }

    /**
     * Method returns table name
     *
     * @return string table name
     */
    public function getTableName(): string
    {
        return $this->tableName;
    }
}
