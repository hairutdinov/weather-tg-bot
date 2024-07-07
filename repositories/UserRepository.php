<?php

namespace app\repositories;

use app\models\User;
use app\repositories\exceptions\NotFoundException;
use app\repositories\interfaces\UserRepositoryInterface;
use RuntimeException;
use Yii;

class UserRepository implements UserRepositoryInterface
{
    public function add(User $user)
    {
        if (!$user->getIsNewRecord()) {
            throw new RuntimeException(Yii::t('app', 'Добавление существующего пользователя'));
        }
        if (!$user->insert(false)) {
            throw new RuntimeException(Yii::t('app', 'Ошибка добавления пользователя'));
        }
        return $user;
    }

    public function find(int $id)
    {
        if (!($user = User::findOne($id))) {
            throw new NotFoundException(Yii::t('app', 'Пользователь не найден'));
        }
        return $user;
    }

    public function delete(User $user)
    {
        if (!$user->delete()) {
            throw new RuntimeException(Yii::t('app', 'Ошибка удаления Свойства товара'));
        }
    }

    public function save(User $user)
    {
        if ($user->getIsNewRecord()) {
            throw new RuntimeException(Yii::t('app', 'Сохранение нового пользователя'));
        }
        if ($user->update(false) === false) {
            throw new RuntimeException(Yii::t('app', 'Ошибка сохранения пользователя'));
        }
        return $user;
    }
}