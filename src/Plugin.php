<?php
namespace mutuasm\commercepaystack;

use craft\base\Plugin as BasePlugin;
use craft\commerce\services\Gateways;
use mutuasm\commercepaystack\gateways\PaystackGateway;
use yii\base\Event;

class Plugin extends BasePlugin
{
    public static $plugin;

    public function init()
    {
        parent::init();
        self::$plugin = $this;

        // Register the Paystack Gateway within Craft Commerce
        Event::on(
            Gateways::class,
            Gateways::EVENT_REGISTER_GATEWAY_TYPES,
            function (Event $event) {
                $event->types[] = PaystackGateway::class;
            }
        );
    }
}
