<?php

namespace app\services\weather;

use app\services\weather\exceptions\ResponseError;
use Exception;
use Yii;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;
use app\services\weather\exceptions\ValidationError;

class WeatherstackService implements WeatherServiceInterface
{
    protected string $token;
    protected Client $client;

    public function __construct(string $token)
    {
        $this->token = $token;
        $this->client = new Client(['baseUrl' => Yii::$app->params['weatherstack']['baseUrl']]);
    }

    public function getCurrentWeatherByQuery(string $query): Weather
    {
        return $this->getCurrentWeather($query);
    }

    public function getCurrentWeatherByLatAndLong(string $latitude, string $longitude): Weather
    {
        return $this->getCurrentWeather(sprintf('%s,%s', $latitude, $longitude));
    }

    protected function getCurrentWeather($query): Weather
    {
        $response = $this->client->get(
            Yii::$app->params['weatherstack']['current'],
            $this->mergeParams([
                'query' => $query,
            ])
        )
            ->send();
        $response = $response->getData();

        if (isset($response['success']) && $response['success'] === false) {
            throw new ResponseError(Yii::t('telegram', 'Возникла ошибка. Попробуйте позже...'));
        }

        $weather = new Weather();
        $weatherLocation = new WeatherLocation();
        if (!$weatherLocation->load($response, 'location') || !$weatherLocation->validate()) {
            throw new ValidationError(Yii::t('telegram', 'Возникла ошибка. Попробуйте позже...'));
        }

        if (
            !$weather->load(
                array_merge(
                    ArrayHelper::getValue($response, 'current', []),
                    [
                        'icon' => ArrayHelper::getValue($response, 'current.weather_icons.0', ''),
                        'descriptions' => ArrayHelper::getValue($response, 'current.weather_descriptions', []),
                        'location' => $weatherLocation
                    ]
                ),
                ''
            ) || !$weather->validate()
        ) {
            throw new ValidationError(Yii::t('telegram', 'Возникла ошибка. Попробуйте позже...'));
        }
        return $weather;
    }

    protected function mergeParams(array $params)
    {
        return array_merge($params, [
            'access_key' => $this->token
        ]);
    }
}