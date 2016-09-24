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

        // Call inner (decorated) service method to bild product struct with prices
        $this->decoratedService->calculateProduct($product,$context);
        $product->resetStates();

        // Fetch prices of given product and calculate them according to our own
        // rules. In this demo we simply override the calculated prices with fixed
        // prices.
        $prices = $product->getPrices();
        foreach($prices as &$price):

            // Set the basic calculated price to 88
            $price->setCalculatedPrice(88);

            // If there is a pseudo price set it to 99
            if($price->getCalculatedPseudoPrice()):
                $price->setCalculatedPseudoPrice(99);
            endif;

            // Set reference price
            if ($price->getCalculatedReferencePrice()):
                $price->setCalculatedReferencePrice(77);
            endif;

        endforeach;

        echo '<h1>'.$product->getName().'</h1>';

        sdump($product);

        $product->setPrices($prices);
        $product->addState(Struct\ListProduct::STATE_PRICE_CALCULATED);



    }


}
