<?php
namespace Mezon\Service\Tests;

use Mezon\Service\DbServiceModel;
use Mezon\Service\VariadicModel;

class TestingVariadicModel extends VariadicModel
{

    /**
     * Config key to read settings
     *
     * @var string
     */
    protected $configKey = 'variadic-model-config-key';

    /**
     * Local model class name
     *
     * @var string
     */
    protected $localModel = DbServiceModel::class;

    /**
     * Remote model class name
     *
     * @var string
     */
    protected $remoteModel = VariadicModel::class;
}
