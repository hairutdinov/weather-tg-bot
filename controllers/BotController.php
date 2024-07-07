<?php

namespace app\controllers;

use Yii;
use Throwable;
use yii\rest\Controller;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;
use Telegram\Bot\Objects\Update;
use app\constants\LogCategories;
use app\services\telegram\exceptions\TelegramException;
use app\services\telegram\TelegramClientInterface;
use app\services\telegram\TelegramClient;

class BotController extends Controller
{
    private TelegramClient $telegram;

    public function init()
    {
        parent::init();
        try {
            $this->telegram = Yii::createObject(TelegramClientInterface::class);
        } catch (InvalidConfigException|NotInstantiableException $e) {
        }
    }

    public function actionWebhook()
    {
        try {
            if (empty($this->telegram)) {
                throw new TelegramException(Yii::t('telegram', 'Ошибка интеграции с телеграм ботом'));
            }

            /** @var yii\web\HeaderCollection */
            $headers = Yii::$app->request->getHeaders();
            $secretToken = $headers->get('x-telegram-bot-api-secret-token');

            if ($secretToken !== Yii::$app->params['telegram']['secretToken']) {
                throw new TelegramException(Yii::t('telegram', 'Токен не корректен'));
            }

            /** @var Update|null */
            $updates = $this->telegram->handle();

            Yii::info(
                sprintf(
                    'Метод: %s | Событие: %s',
                    __METHOD__,
                    json_encode($updates, JSON_UNESCAPED_UNICODE)
                ),
                LogCategories::API_INTEGRATION_TELEGRAM
            );

            return Yii::$app->response->statusCode = 200;
        } catch (Throwable $e) {
            Yii::error(
                sprintf(
                    "Метод: %s | Ошибка: %s.\nСтрока: %s.\nТрассировка: %s",
                    __METHOD__,
                    $e->getMessage(),
                    $e->getLine(),
                    $e->getTraceAsString(),
                ),
                LogCategories::API_INTEGRATION_TELEGRAM
            );
            return Yii::$app->response->statusCode = 200;
        }
    }
}