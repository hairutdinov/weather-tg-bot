<?php

namespace app\repositories\interfaces;

use app\models\User;

interface UserRepositoryInterface
{
    public function add(User $user);
    public function find(int $id);
    public function delete(User $user);
    public function save(User $user);
}