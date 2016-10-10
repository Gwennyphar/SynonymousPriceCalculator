<?php
/**
 * Created by PhpStorm.
 * User: michaelfuerst
 * Date: 10.10.16
 * Time: 13:23
 */

namespace SynonymousPriceCalculator\Tests;

use Shopware\Components\Test\Plugin\TestCase;

class Test extends TestCase

{

    protected static $ensureLoadedPlugins = [
        'SynonymousPriceCalculator' => []
    ];

    public function testServiceObjectInstanceType() {

        $service = null;
        $container = Shopware()->Container();

        $service = $container->get("synonymous_price_calculator.price_calulation_service_decorator");

        $this->assertInstanceOf('SynonymousPriceCalculator\Services\Price\PriceCalculationServiceDecorator', $service,
            "Invalid service instance."
        );
    }


}