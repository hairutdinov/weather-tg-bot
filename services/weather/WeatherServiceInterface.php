<?php

namespace app\services\weather;

interface WeatherServiceInterface
{
    public function getCurrentWeatherByQuery(string $query);
    public function getCurrentWeatherByLatAndLong(string $latitude, string $longitude);
}