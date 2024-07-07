<?php

namespace app\services\telegram;

use Exception;
use app\services\telegram\commands\CustomCommand;
use app\services\telegram\commands\CustomCommandBus;

interface TelegramClientInterface
{
    public function setWebhook(): bool;

    /**
     * @param array $params
     * @return object
     */
    public function sendMessage(array $params);

    /**
     * @param array $params
     * @return object
     */
    public function sendChatAction(array $params);

    /**
     * @param array $params
     * @return object
     */
    public function sendPhoto(array $params);

    /**
     * @param object $object
     * @return string|null
     */
    public function detectMessageType($object);

    /**
     * @param object $object
     * @param string $text
     * @return object
     * @throws Exception
     */
    public function editMessageText($object, string $text);

    /**
     * @param array $params
     * @return object
     * @throws Exception
     */
    public function sendSticker(array $params);

    /**
     * @return mixed
     */
    public function handle();

    /**
     * @param object $update
     * @return mixed
     */
    public function processCommand($update);

    /**
     * @return CustomCommandBus
     */
    public function getCommandBus();

    /**
     * @param string[] $commands Имена классов команд
     * @return $this
     */
    public function addCustomCommands(array $commands);

    /**
     * @param string $command
     * @return mixed
     */
    public function addCustomCommand(string $command);

    /**
     * @return CustomCommand|CustomCommand[]
     */
    public function getCommands();

    /**
     * @return CustomCommand|CustomCommand[]
     */
    public function getBotCommands();

    /**
     * @return string
     */
    public function getBotCommandsString(): string;

    /**
     * @return string
     */
    public function getParseMode(): string;

    /**
     * @param string $parseMode
     * @return void
     */
    public function setParseMode(string $parseMode): void;
}