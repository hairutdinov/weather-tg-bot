<?php

namespace app\services\telegram\commands;

use app\services\telegram\enums\Commands;
use app\services\telegram\enums\Phrases;
use Yii;

class AuthCommand extends CustomCommand
{
    protected $name = Commands::AUTH;

    /**
     * @inheritDoc
     */
    public function isExecutable(): bool
    {
        return $this->update->getMessage()->has('contact');
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $phone = $this->update->getMessage()->getContact()->getPhoneNumber();

        if (!empty($phone)) {
            $this->replyWithMessage([
                'text' => Yii::t(
                    'telegram',
                    Phrases::AFTER_USER_SHARE_PHONE_MESSAGE,
                    [
                        'username' => $this->getUsername(),
                    ]
                )
            ]);
        }
    }
}