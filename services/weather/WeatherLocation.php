<?php

namespace app\services\weather;

use yii\base\Model;

class WeatherLocation extends Model
{
    public $name;
    public $country;
    public $region;
    public $lat;
    public $lon;
    public $timezone_id;
    public $localtime;

    public function rules()
    {
        return [
            [['name', 'country', 'region', 'lat', 'lon', 'timezone_id', 'localtime'], 'string']
        ];
    }
}