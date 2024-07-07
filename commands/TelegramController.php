<?php

namespace app\commands;

use app\services\telegram\TelegramClient;
use app\services\telegram\TelegramClientInterface;
use Yii;
use yii\base\InvalidConfigException;
use yii\console\Controller;
use yii\di\NotInstantiableException;

class TelegramController extends Controller
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

    public function actionSetWebhook()
    {
        echo sprintf(
            "Статус установки вебхука: %s\n",
            $this->telegram->setWebhook() ? 'ok' : 'error'
        );
        exit(0);
    }
}