<?php
namespace Mezon\Service;

use Mezon\Conf\Conf;
use Mezon\Transport\RequestParams;

/**
 * Class VariadicModel
 *
 * @package ServiceModel
 * @subpackage VariadicModel
 * @author Dodonov A.A.
 * @version v.1.0 (2021/02/06)
 * @copyright Copyright (c) 2021, aeon.org
 */

/**
 * Base class for all controllers
 */
class VariadicModel
{

    /**
     * Real model
     *
     * @var object
     */
    private $realModel = null;

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
    protected $localModel = ServiceModel::class;

    /**
     * Remote model class name
     *
     * @var string
     */
    protected $remoteModel = ServiceModel::class;

    /**
     * Constructor
     *
     * @param mixed $model
     *            real model
     */
    public function __construct($model = null)
    {
        $modelSetting = Conf::getConfigValue($this->configKey, 'local');

        if ($model !== null) {
            $this->setRealModel($model);
        } elseif ($modelSetting === 'local') {
            $this->setRealModel(new $this->localModel());
        } elseif ($modelSetting === 'remote') {
            $this->setRealModel(new $this->remoteModel());
        } elseif (is_string($modelSetting) && class_exists($modelSetting)) {
            $this->setRealModel(new $modelSetting());
        } elseif ($modelSetting instanceof ServiceModel) {
            $this->setRealModel($modelSetting);
        } else {
            throw (new \Exception(
                'Can not construct model from value ' .
                (is_string($modelSetting) ? $modelSetting : serialize($modelSetting))));
        }
    }

    /**
     * Method returns real model
     *
     * @return object real model
     */
    public function getRealModel(): object
    {
        return $this->realModel;
    }

    /**
     * Method sets real model
     *
     * @param object $realModel
     *            real model
     */
    public function setRealModel(object $realModel): void
    {
        $this->realModel = $realModel;
    }
}
