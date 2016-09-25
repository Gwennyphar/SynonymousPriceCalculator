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
        ];
    }


    /**
     * Set the template Dir for all requests
     */
    public function addTemplateDir() {
        $this->container->get('template')->addTemplateDir(__DIR__ . '/Resources/views');
    }


}
