<?php

namespace SynonymousPriceCalculator\Subscriber;

class Subscriber implements \Enlight\Event\SubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return array(
            'Enlight_Controller_Action_PostDispatchSecure_Frontend' => 'onFrontendPostDispatch'
        );
    }

    public function onFrontendPostDispatch(\Enlight_Event_EventArgs $args)
    {
        return __DIR__ . '/../Slogan.php';
    }

}