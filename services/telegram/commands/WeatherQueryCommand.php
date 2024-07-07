<?php

namespace app\services\telegram\commands;

use app\constants\LogCategories;
use app\services\telegram\enums\Phrases;
use app\services\weather\exceptions\ResponseError;
use app\services\weather\exceptions\ValidationError;
use app\services\weather\Weather;
use app\services\weather\WeatherServiceInterface;
use app\services\telegram\TelegramClient;
use Exception;
use Telegram\Bot\Actions;
use Yii;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;

class WeatherQueryCommand extends CustomCommand
{
    protected $name = 'weather_query';
    protected ?WeatherServiceInterface $weatherService = null;

    public function __construct(TelegramClient $client)
    {
        parent::__construct($client);
        try {
            $this->weatherService = Yii::createObject(WeatherServiceInterface::class);
        } catch (InvalidConfigException|NotInstantiableException $e) {
            Yii::error(
                LogCategories::createLogMessage(
                    $e,
                    __METHOD__
                ),
                LogCategories::API_INTEGRATION_TELEGRAM
            );
        }
    }

    public function execute()
    {
        $update = $this->replyWithMessage([
            'text' => 'Запрашиваю данные...',
        ]);

        try {
            /** @var Weather*/
            $weatherResponse = $this->weatherService->getCurrentWeatherByQuery($this->getUpdate()->getMessage()->getText());
            $this->client->editMessageText(
                $update,
                Yii::t('telegram', Phrases::CURRENT_WEATHER_MESSAGE, [
                    'city' => $weatherResponse->location->name,
                    'country' => $weatherResponse->location->country,
                    'description' => implode(', ', $weatherResponse->descriptions),
                    'temperature' => $weatherResponse->temperature,
                ])
            );
        } catch (ResponseError|ValidationError $e) {
            $this->client->editMessageText(
                $update,
                $e->getMessage()
            );
            throw $e;
        }
    }

    public function isExecutable(): bool
    {
        return !empty($this->getUpdate()->getMessage()->getText());
    }
}