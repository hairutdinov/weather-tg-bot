<?php

namespace app\services\telegram\commands;

use app\services\telegram\TelegramClient;
use Telegram\Bot\Objects\Update;

abstract class CustomCommand implements interfaces\CustomCommandInterfaces
{
    /** @var TelegramClient|null */
    protected $client = null;
    /** @var string */
    protected $name = '';
    protected $description = '';
    /** @var Update */
    protected $update;

    public function __construct(TelegramClient $client)
    {
        $this->client = $client;
    }

    /**
     * @inheritDoc
     */
    abstract public function isExecutable(): bool;

    /**
     * @inheritDoc
     */
    abstract public function execute();

    /**
     * @return Update
     */
    public function getUpdate(): Update
    {
        return $this->update;
    }

    /**
     * @param Update $update
     */
    public function setUpdate(Update $update): void
    {
        $this->update = $update;
    }

    public function getChatId()
    {
        return $this->update->getMessage()->getChat()->getId();
    }

    public function getUsername()
    {
        return $this->update->getMessage()->getChat()->getUsername();
    }

    public function replyWithMessage(array $params)
    {
        return $this->client->sendMessage(array_merge($params, [
            'chat_id' => $this->getChatId()
        ]));
    }

    public function replyWithPhoto(array $params)
    {
        return $this->client->sendPhoto(array_merge($params, [
            'chat_id' => $this->getChatId()
        ]));
    }

    public function replyWithChatAction(array $params)
    {
        return $this->client->sendChatAction(array_merge($params, [
            'chat_id' => $this->getChatId()
        ]));
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public static function create(TelegramClient $client, Update $update)
    {
        $cmd = new static($client);
        $cmd->setUpdate($update);
        return $cmd;
    }
}