<?php

namespace app\services\telegram\commands;

use app\constants\LogCategories;
use app\models\forms\CreateUserThroughTelegramForm;
use app\services\telegram\enums\Commands;
use app\services\telegram\enums\Phrases;
use app\services\telegram\enums\Stickers;
use app\services\telegram\TelegramClient;
use app\services\weather\Weather;
use DateTime;
use DomainException;
use app\services\user\UserService;
use Yii;
use yii\base\InvalidConfigException;
use yii\di\NotInstantiableException;

class RequestContactCommand extends CustomCommand
{
    protected $name = Commands::REQUEST_CONTACT;
    protected ?UserService $userService = null;

    const MESSAGE_TYPE = 'contact';

    public function __construct(TelegramClient $client)
    {
        parent::__construct($client);

        try {
            $this->userService = Yii::createObject(UserService::class);
        } catch (InvalidConfigException|NotInstantiableException $e) {
            Yii::error(
                LogCategories::createLogMessage(
                    $e,
                    __METHOD__
                ),
                LogCategories::API_INTEGRATION_TELEGRAM
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function isExecutable(): bool
    {
        return $this->detectMessageType() == self::MESSAGE_TYPE;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $phone = $this->update->getMessage()->getContact()->getPhoneNumber();

        if (!empty($phone)) {
            $form = new CreateUserThroughTelegramForm();
            if (!$form->load([
                    'chatId'   => $this->getChatId(),
                    'username' => $this->getUsername(),
                    'phone'    => $phone,
                ], '') || !$form->validate()) {
                throw new DomainException('Ошибка валидации данные... Попробуйте позже.');
            }

            try {

                $this->userService->create($form, new DateTime('now'));
                /** @var Weather*/
                $this->replyWithMessage([
                    'text' => Yii::t(
                        'telegram',
                        Phrases::AFTER_USER_SIGN_UP_MESSAGE,
                        [
                            'username' => $this->getUsername(),
                        ]
                    )
                ]);
                $this->replyWithSticker([
                    'sticker' => Stickers::PARTYING_FACE_STICKER,
                ]);
            } catch (DomainException $e) {
                $this->replyWithMessage([
                    'text' => $e->getMessage()
                ]);
                throw $e;
            }
        }
    }
}