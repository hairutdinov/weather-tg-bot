<?php

namespace app\services\telegram\commands;

use app\services\telegram\enums\Commands;
use app\services\telegram\enums\Phrases;
use Yii;

class StickerCommand extends CustomCommand
{
    protected $name = Commands::STICKER;
    protected $description = Phrases::STICKER_BUTTON;

    const MESSAGE_TYPE = 'sticker';

    public function execute()
    {
        $this->replyWithSticker([
            'sticker' => $this->update->getMessage()->getSticker()->getFileId(),
        ]);
    }

    public function isExecutable(): bool
    {
        return $this->detectMessageType() === self::MESSAGE_TYPE;
    }
}