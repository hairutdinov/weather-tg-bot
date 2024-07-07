<?php

namespace app\bootstrap;

use app\services\weather\WeatherServiceInterface;
use app\services\weather\WeatherstackService;
use app\services\telegram\TelegramClient;
use app\services\telegram\TelegramClientInterface;
use app\services\weather\YandexWeatherService;
use Yii;

class SetUp implements \yii\base\BootstrapInterface
{

    /**
     * @inheritDoc
     */
    public function bootstrap($app)
    {
        $container = Yii::$container;
        $container->setSingleton(TelegramClientInterface::class, function ($container, $params, $config) {
            return new TelegramClient(Yii::$app->params['telegram']['token']);
        });

        $container->setSingleton(WeatherServiceInterface::class, function ($container, $params, $config) {
            return new WeatherstackService(Yii::$app->params['weatherstack']['token']);
        });
    }
}