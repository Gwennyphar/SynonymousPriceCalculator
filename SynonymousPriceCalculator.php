<?php

namespace SynonymousPriceCalculator;

use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\DeactivateContext;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UpdateContext;
use Shopware\Components\Plugin\Context\UninstallContext;

/**
 * Class SynonymousPriceCalculator
 * @package SynonymousPriceCalculator
 */
class SynonymousPriceCalculator extends Plugin
{

    /**
     * @param InstallContext $context
     * This method is called on plugin installation
     */
    public function install(InstallContext $context)
    {
        parent::install($context);
    }

    /**
     * @param UpdateContext $context
     * This method is called on update of the plugin
     */
    public function update(UpdateContext $context)
    {
        return parent::update($context);
    }

    /**
     * @param ActivateContext $context
     * This method is called on activation of the plugin
     */
    public function activate(ActivateContext $context)
    {
        return parent::activate($context);
    }

    /**
     * @param DeactivateContext $context
     * This method is called on deactivation of the plugin
     */
    public function deactivate(DeactivateContext $context)
    {
        return parent::deactivate($context);
    }

    /**
     * @param UninstallContext $context
     * This method is called once on uninstallation of the plugin
     */
    public function uninstall(UninstallContext $context)
    {
        return parent::uninstall($context);
    }

    /**
     * Decorate the calculation service with an own calculation service to allow individual price calculation.
     */
    public function decorateService()
    {
        $coreService = Shopware()->Container()->get('shopware_storefront.price_calculation_service');
        $newService = new PriceCalculationServiceDecorator($coreService);
        Shopware()->Container()->set('shopware_storefront.price_calculation_service', $newService);
    }

}