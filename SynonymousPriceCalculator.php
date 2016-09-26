<?php

namespace SynonymousPriceCalculator;

use Shopware\Components\Plugin;

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
            'Shopware_Plugins_HttpCache_GetCacheIds' => 'filterHttpCacheId'
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


}
