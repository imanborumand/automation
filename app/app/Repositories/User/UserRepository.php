<?php

namespace App\Repositories\User;


use App\Models\User\User;
use App\Repositories\Contracts\User\UserRepositoryInterface;
use App\Repositories\BaseRepositoryClass;


class UserRepository extends BaseRepositoryClass implements UserRepositoryInterface
{
    /**
     * set your model
     * @inheritDoc
     */
    public function getModel(): string
    {
        return User::class;
    }



}


