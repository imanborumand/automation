<?php

namespace App\Services\Contracts;



use App\Models\User\User;
use Illuminate\Database\Eloquent\Builder;


interface UserServiceInterface
{

    public function getRandomUser();
}
