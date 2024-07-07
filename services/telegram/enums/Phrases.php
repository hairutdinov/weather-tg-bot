<?php

namespace app\services\telegram\enums;

class Phrases
{
    const START_BUTTON = 'Запуск';
    const HELP_BUTTON = 'Помощь';
    const STICKER_BUTTON = 'Стикер';
    const SIGN_UP_BY_PHONE_NUMBER_BUTTON = 'Регистрация по номеру телефона';

    const SIGN_UP_BUTTON_DESCRIPTION = 'Регистрация по номеру телефона';

    const HELP_MESSAGE = "Я бот-синоптик, который подскажет вам погоду в любом городе мира. Для получения погоды отправьте геолокацию (доступно с мобильных устройств).
Также возможно указать город в формате:
- Город 
- Город, код страны 
Примеры: _London_, _London, uk_";
    const WELCOME_MESSAGE = 'Привет, @{username}!' . PHP_EOL . self::HELP_MESSAGE;
    const AFTER_USER_SIGN_UP_MESSAGE = 'Вы успешно зарегистрированы!';
    const AVAILABLE_COMMANDS_MESSAGE = 'Доступные команды:
{botCommands}';
    const CURRENT_WEATHER_MESSAGE = "_Информация о погоде_:
Город: *{city}*
Страна: *{country}*
Погода: *{description}*
Температура: *{temperature}*";
    const SIGN_UP_MESSAGE = 'Для того, чтобы зарегистрироваться, нажмите кнопку *' . self::SIGN_UP_BY_PHONE_NUMBER_BUTTON . '*';

    const YOU_ALREADY_SIGNED_UP_ERROR_MESSAGE = 'Вы уже зарегистрированы😌!';
}