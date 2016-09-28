<?php

namespace SynonymousPriceCalculator;

use Shopware\Components\Plugin;
use Shopware\Models\Article\Article;
use Shopware\Models\Order\Basket;

/**
 * Class SynonymousPriceCalculator
 * @package SynonymousPriceCalculator
 */
class SynonymousPriceCalculator extends Plugin
{

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure' => 'addTemplateDir',
            'Shopware_Plugins_HttpCache_GetCacheIds' => 'filterHttpCacheId',
            'Shopware_Modules_Basket_UpdateArticle_FilterSqlDefault' => 'updateBasketArticleFilterSql'
        ];
    }


    /**
     * Set the template Dir for all requests
     */
    public function addTemplateDir() {
        $this->container->get('template')->addTemplateDir(__DIR__ . '/Resources/views');
    }

    /**
     * Add an additional Cache-ID to the HTTP Cache identifier that binds the
     * actual page to the discount ID. That allows us to have unique prices
     * following the discount calculation model but also use the HTTP Cache.
     *
     * @param \Enlight_Event_EventArgs $args
     * @return array|mixed
     */
    public function filterHttpCacheId(\Enlight_Event_EventArgs $args) {

        $cacheIds = $args->getReturn();

        if(is_null($cacheIds) || !is_array($cacheIds)):
            $cacheIds = array();
        endif;

        // For example we add the user ID to the Cache IDs so that every user
        // hast his "own" cached version of http request. If the user is not
        // logged in only the original cache id is used.
        if(Shopware()->Session()->sUserId):
            $additionalCacheId = 'userId'.Shopware()->Session()->sUserId;
            $cacheIds[] = $additionalCacheId;
        endif;

        return $cacheIds;

    }

    /**
     * Due to the fact that the cart is on the old system and uses an own price calculation
     * algorithm and not the price_calculation_service, we have to emit the event
     * Shopware_Modules_Basket_UpdateArticle_FilterSqlDefault and manipulate the SQL query
     * which is executed each time a product is added to or updated in cart.
     *
     * @param \Enlight_Event_EventArgs $args
     * @return string
     */
    public function updateBasketArticleFilterSql(\Enlight_Event_EventArgs $args) {

        // Get the shop context
        $context = $this->container->get('shopware_storefront.context_service')->getContext();

        // Get orderNumber of article for order_basket id
        $orderBasketArticle = $this->container->get('models')->getRepository(Basket::class)->find($args->get('id'));
        $orderNumber = $orderBasketArticle->getOrderNumber();

        // Use the productService to get the product with prices calculated by the
        // price_calculation_service which is decorated by this plugin.
        $productService = Shopware()->Container()->get('shopware_storefront.product_service');
        $product = $productService->get($orderNumber,$context);

        $cheapestPrice = $product->getCheapestPrice();

        // Add some dummy prices here
        $price = $cheapestPrice->getCalculatedPrice();
        $netPrice = $cheapestPrice->getRule()->getPrice();

        // Manipulate the query so that the correct price is set on the statement
        $query = "
            UPDATE s_order_basket
            SET quantity = ?, price = ?, netprice = ?, price = ".$price.", netprice = ".$netPrice.", currencyFactor = ?, tax_rate = ?
            WHERE id = ? AND sessionID = ? AND modus = 0";

        return $query;

    }

}
