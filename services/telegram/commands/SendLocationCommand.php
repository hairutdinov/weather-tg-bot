<?php

namespace app\services\telegram\commands;

use app\constants\LogCategories;
use app\services\telegram\enums\Phrases;
use app\services\telegram\TelegramClient;
use app\services\weather\exceptions\ResponseError;
use app\services\weather\exceptions\ValidationError;
use app\services\weather\Weather;
use app\services\weather\WeatherServiceInterface;
use Exception;
use Telegram\Bot\Actions;
use Yii;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;

class SendLocationCommand extends CustomCommand
{
    protected $name = 'send_location';
    protected ?WeatherServiceInterface $weatherService = null;
    const MESSAGE_TYPE = 'location';

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

    public function isExecutable(): bool
    {
        return $this->client->detectMessageType($this->getUpdate()) == self::MESSAGE_TYPE;
    }

    public function execute()
    {
        $update = $this->replyWithMessage([
            'text' => 'Запрашиваю данные...',
        ]);

        try {
            /** @var Weather*/
            $weatherResponse = $this->weatherService->getCurrentWeatherByLatAndLong(
                $this->getUpdate()->getMessage()->getLocation()->getLatitude(),
                $this->getUpdate()->getMessage()->getLocation()->getLongitude()
            );
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
}