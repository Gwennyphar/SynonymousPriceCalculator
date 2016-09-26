<?php

namespace SynonymousPriceCalculator\Services\Price;

use Shopware\Bundle\StoreFrontBundle\Service\PriceCalculationServiceInterface;
use Shopware\Bundle\StoreFrontBundle\Struct;

class PriceCalculationServiceDecorator implements PriceCalculationServiceInterface
{
    /**
     * @var PriceCalculationServiceInterface
     */
    private $decoratedService;

    /**
     * @param PriceCalculationServiceInterface $service
     */
    public function __construct(PriceCalculationServiceInterface $service)
    {
        $this->decoratedService = $service;
    }

    /**
     * @param Struct\ListProduct $product
     * @param Struct\ProductContextInterface $context
     */
    public function calculateProduct(Struct\ListProduct $product, Struct\ProductContextInterface $context) {

        // Call inner (decorated) service method to build product struct with prices
        $this->decoratedService->calculateProduct($product,$context);
        $product->resetStates();

        // Fetch prices of given product and calculate them according to our own
        // rules. In this demo we simply override the calculated prices with fixed
        // prices.
        $prices = $product->getPrices();
        foreach($prices as &$price):

            /**
             * Calculate some random price between 90 and 99
             */
            $randomPrice = rand(900,999);

            // Set the basic calculated price to $randomPrice
            $price->setCalculatedPrice($randomPrice);

            // If there is a pseudo price
            if($price->getCalculatedPseudoPrice()):
                $price->setCalculatedPseudoPrice($randomPrice + 10);
            endif;

            // Set reference price
            if ($price->getCalculatedReferencePrice()):
                $price->setCalculatedReferencePrice($randomPrice);
            endif;

            $product->setCheapestPrice($price);
            $product->setCheapestUnitPrice($price);

        endforeach;

        // Assign manipulated prices to product and set state to STATE_PRICE_CALCULATED
        $product->setPrices($prices);
        $product->addState(Struct\ListProduct::STATE_PRICE_CALCULATED);

    }

}