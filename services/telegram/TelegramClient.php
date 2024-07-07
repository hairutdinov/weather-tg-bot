<?php

namespace app\services\telegram;

use app\constants\LogCategories;
use app\services\telegram\commands\CustomBotCommand;
use app\services\telegram\commands\CustomCommand;
use app\services\telegram\commands\CustomCommandBus;
use app\services\telegram\commands\HelpCommand;
use app\services\telegram\commands\SendLocationCommand;
use app\services\telegram\commands\StartCommand;
use app\services\telegram\commands\UnknownCommand;
use app\services\telegram\commands\WeatherQueryCommand;
use app\services\telegram\enums\Phrases;
use Exception;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Update;
use Telegram\Bot\TelegramRequest;
use Telegram\Bot\TelegramResponse;
use Yii;
use yii\base\Component;

class TelegramClient extends Component implements TelegramClientInterface
{
    /** @var Api */
    public $client;
    /**
     * @var CustomCommandBus|null
     */
    protected $commandBus = null;
    protected $parseMode = 'Markdown';
    protected ?string $token = null;

    public function __construct(string $token)
    {
        parent::__construct();
        $this->token = $token;
        $this->client = new Api($token);
        $this->addCustomCommands([
            StartCommand::class,
            HelpCommand::class,
            WeatherQueryCommand::class,
            SendLocationCommand::class,
            UnknownCommand::class,
        ]);
    }

    public function setWebhook(): bool
    {
        $webhookUrl = Yii::$app->params['telegram']['webhookUrl'] ?? '';
        if (empty($webhookUrl)) {
            return false;
        }

        try {
            /** @var TelegramResponse */
            $result = $this->client->setWebhook([
                'url' => $webhookUrl,
                'secret_token' => Yii::$app->params['telegram']['secretToken']
            ]);

            $isOk = !$result->isError();

            Yii::info(
                sprintf(
                    'Метод: %s | Статус установки вебхука: %s',
                    __METHOD__,
                    json_encode($isOk)
                ),
                LogCategories::API_INTEGRATION_TELEGRAM
            );
            return $isOk;
        } catch (TelegramSDKException $e) {
            Yii::error(
                LogCategories::createLogMessage(
                    $e,
                    __METHOD__
                ),
                LogCategories::API_INTEGRATION_TELEGRAM
            );
            return false;
        }
    }

    /**
     * @param array $params
     * @return Message
     */
    public function sendMessage(array $params)
    {
        if ($this->getParseMode()) {
            $params = array_merge(['parse_mode' => $this->getParseMode()], $params);
        }
        return $this->client->sendMessage($params);
    }

    /**
     * @param array $params
     * @return TelegramResponse
     * @throws TelegramSDKException
     */
    public function sendChatAction(array $params)
    {
        return $this->client->sendChatAction($params);
    }

    /**
     * @param array $params
     * @return Message
     */
    public function sendPhoto(array $params)
    {
        return $this->client->sendPhoto($params);
    }

    /**
     * @param Update|Message $object
     * @return string|null
     */
    public function detectMessageType($object)
    {
        return $this->client->detectMessageType($object);
    }

    /**
     * @param Message $object
     * @param string $text
     * @return Message
     * @throws TelegramSDKException
     */
    public function editMessageText($object, string $text)
    {
        $params = [
            'chat_id' => $object->getChat()->getId(),
            'message_id' => $object->getMessageId(),
            'text' => $text,
        ];

        if ($this->getParseMode()) {
            $params = array_merge(['parse_mode' => $this->getParseMode()], $params);
        }

        $request = new TelegramRequest($this->token, 'GET', 'editMessageText', $params);
        $response = $this->client->getClient()->sendRequest($request);

        return new Message($response->getDecodedBody());
    }

    /**
     * @param array $params
     * @return Message
     * @throws TelegramSDKException
     */
    public function sendSticker(array $params)
    {
        return $this->client->sendSticker($params);
    }

    public function handle()
    {
        $updates = $this->client->getWebhookUpdates();
        $this->processCommand($updates);
        return $updates;
    }

    /**
     * @param Update $update
     * @return mixed|Update
     */
    public function processCommand($update)
    {
        return $this->getCommandBus()->handler($update);
    }


    public function getCommandBus()
    {
        if (is_null($this->commandBus)) {
            return $this->commandBus = new CustomCommandBus($this);
        }

        return $this->commandBus;
    }


    /**
     * @param string[] $commands Имена классов команд
     * @return $this
     */
    public function addCustomCommands(array $commands)
    {
        foreach ($commands as $command) {
            $this->addCustomCommand($command);
        }

        return $this;
    }

    public function addCustomCommand(string $command)
    {
        try {
            return $this->getCommandBus()->addCommand($command);
        } catch (Exception $e) {
            Yii::error(
                LogCategories::createLogMessage(
                    $e,
                    __METHOD__
                ),
                LogCategories::API_INTEGRATION_TELEGRAM
            );
            return $this->getCommandBus();
        }
    }

    /**
     * @return CustomCommand|CustomCommand[]
     */
    public function getCommands()
    {
        return $this->getCommandBus()->getCommands();
    }

    public function getBotCommands()
    {
        $commands = $this->getCommandBus()->getCommands();
        if (empty($commands)) {
            return [];
        }
        return array_filter($commands, function (CustomCommand $command) {
            return $command instanceof CustomBotCommand;
        });
    }

    public function getBotCommandsString(): string
    {
        return implode(PHP_EOL, array_map(function ($name, CustomCommand $command) {
            return sprintf(
                '/%s - %s',
                Yii::t('telegram', $name),
                Yii::t('telegram', $command->getDescription())
            );
        }, array_keys($this->getBotCommands()), $this->getBotCommands()));
    }

    /**
     * @return string
     */
    public function getParseMode(): string
    {
        return $this->parseMode;
    }

    /**
     * @param string $parseMode
     */
    public function setParseMode(string $parseMode): void
    {
        $this->parseMode = $parseMode;
    }
}