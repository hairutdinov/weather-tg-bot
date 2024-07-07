<?php

namespace app\services\weather;

use yii\base\Model;

class Weather extends Model
{
    public $temperature;
    public $icon;
    public $humidity;
    public $feelslike;
    public $descriptions = [];
    /** @var WeatherLocation */
    public $location;

    public function rules()
    {
        return [
            [['temperature', 'humidity', 'feelslike'], 'number'],
            [['icon'], 'string'],
            [['descriptions', 'location'], 'safe'],
        ];
    }
}