<?php

namespace app\services\telegram\enums;

class Phrases
{
    const START_BUTTON = 'Запуск';
    const HELP_BUTTON = 'Помощь';
    const REQUEST_CONTACT_BUTTON = 'Поделиться контактами';

    const HELP_MESSAGE = "Я бот-синоптик, который подскажет вам погоду в любом городе мира. Для получения погоды отправьте геолокацию (доступно с мобильных устройств).
Также возможно указать город в формате:
- Город 
- Город, код страны 
Примеры: _London_, _London, uk_";
    const WELCOME_MESSAGE = 'Привет, @{username}!' . PHP_EOL . self::HELP_MESSAGE;
    const AFTER_USER_SHARE_PHONE_MESSAGE = 'Привет, {username}!';
    const AVAILABLE_COMMANDS_MESSAGE = 'Доступные команды:
{botCommands}';
    const CURRENT_WEATHER_MESSAGE = "_Информация о погоде_:
Город: *{city}*
Страна: *{country}*
Погода: *{description}*
Температура: *{temperature}*";
}