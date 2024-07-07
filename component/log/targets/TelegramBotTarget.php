<?php

namespace app\component\log\targets;

use app\services\telegram\TelegramClientInterface;
use Yii;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;

class TelegramBotTarget extends \yii\log\Target
{
    protected ?TelegramClientInterface $client = null;
    public $logVars = [];

    public function init()
    {
        parent::init();
        try{
            $this->client = Yii::createObject(TelegramClientInterface::class);
        } catch (InvalidConfigException|NotInstantiableException $e) {

        }
    }

    public function export()
    {
        if (is_null($this->client)) {
            throw new InvalidConfigException("Ошибка инициализации сервиса Телеграм");
        }

        $text = implode("\n", array_map([$this, 'formatMessage'], $this->messages)) . "\n";

        if (trim($text) === '') {
            return;
        }

        $this->client->sendMessage([
            'chat_id' => Yii::$app->params['telegram']['adminChatId'],
            'text'    => $text
        ]);
    }
}