<?php

namespace app\services\weather;

use NotImplementedException;
use Yii;
use yii\db\Exception;
use yii\httpclient\Client;

class YandexWeatherService implements WeatherServiceInterface
{
    protected string $token;
    protected Client $client;

    public function __construct(string $token)
    {
        $this->token = $token;
        $this->client = new Client(['baseUrl' => Yii::$app->params['yandexweather']['baseUrl']]);
    }

    public function getCurrentWeatherByQuery(string $query)
    {
        $response = $this->client->get(
            Yii::$app->params['yandexweather']['forecast'],
            [
                'lat' => '52.37125',
                'lon' => '4.89388',
            ],
            [
                'X-Yandex-Weather-Key' => $this->token
            ]
        )
            ->send();
        return 1;
    }

    public function getCurrentWeatherByLatAndLong(string $latitude, string $longitude)
    {
        throw new NotImplementedException();
    }
}