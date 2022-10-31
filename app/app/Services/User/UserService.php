<?php

namespace App\Services\User;


use App\Repositories\Contracts\User\UserRepositoryInterface;

use App\Services\Contracts\UserServiceInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class UserService implements UserServiceInterface
{


    /**
     * @param UserRepositoryInterface $repository
     */
    public function __construct(
        private readonly UserRepositoryInterface $repository
    ) {
    }



    public function getRandomUser()
    {
        return $this->repository->baseQuery()
                    ->inRandomOrder()
                    ->first();
    }
}
