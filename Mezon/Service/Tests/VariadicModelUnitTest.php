<?php
namespace Mezon\Service\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\FieldsSet;
use Mezon\Service\DbServiceModel;
use Mezon\PdoCrud\Tests\PdoCrudMock;
use Mezon\Conf\Conf;
use Mezon\Service\VariadicModel;
use Mezon\Service\ServiceModel;

class VariadicModelUnitTest extends TestCase
{

    /**
     * Testing data provider
     *
     * @return array testing data
     */
    public function constructorDataProvider(): array
    {
        return [
            // #0, default value
            [
                function (): object {
                    Conf::deleteConfigValue('variadic-model-config-key');

                    return new TestingVariadicModel();
                },
                DbServiceModel::class
            ],
            // #1, local model
            [
                function (): object {
                    Conf::setConfigValue('variadic-model-config-key', 'local');

                    return new TestingVariadicModel();
                },
                DbServiceModel::class
            ],
            // #2, remote model
            [
                function (): object {
                    Conf::setConfigValue('variadic-model-config-key', 'remote');

                    return new TestingVariadicModel();
                },
                VariadicModel::class
            ],
            // #3, explicit model
            [
                function (): object {
                    return new TestingVariadicModel(new ServiceModel());
                },
                ServiceModel::class
            ],
            // #4, global model
            [
                function (): object {
                    Conf::setConfigValue('variadic-model-config-key', new ServiceModel());
                    return new TestingVariadicModel();
                },
                ServiceModel::class
            ],
            // #5, some other model
            [
                function (): object {
                    Conf::setConfigValue('variadic-model-config-key', ServiceModel::class);
                    return new TestingVariadicModel();
                },
                ServiceModel::class
            ]
        ];
    }

    /**
     * Testing method
     *
     * @param callable $setup
     *            setup method
     * @param string $expected
     *            expected type
     * @dataProvider constructorDataProvider
     */
    public function testConstructor(callable $setup, string $expected): void
    {
        // setup
        $model = $setup();

        // assertions
        $this->assertInstanceOf($expected, $model->getRealModel());
    }
}
