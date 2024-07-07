<?php

namespace app\services\user;

use app\services\telegram\enums\Phrases;
use DateTimeInterface;
use app\models\User;
use app\models\forms\CreateUserThroughTelegramForm;
use app\repositories\interfaces\UserRepositoryInterface;
use DomainException;
use Exception;

class UserService
{
    protected ?UserRepositoryInterface $repository = null;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function create(CreateUserThroughTelegramForm $form, DateTimeInterface $createdAt)
    {
        $model = User::create($form->username, $form->chatId, $createdAt, $form->phone);
        try {
            return $this->repository->add($model);
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'DETAIL:  Key (phone, "chatId")=') !== false) {
                throw new DomainException(Phrases::YOU_ALREADY_SIGNED_UP_ERROR_MESSAGE);
            }
            throw $e;
        }
    }
}