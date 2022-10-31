<?php

namespace Database\Seeders;


use App\Enums\User\Roles;
use App\Models\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if(User::count() > 0) return;


        $users = $this->userList();
        foreach ($users as $user) {
            $user = User::create($user);
            $user->assignRole(Roles::REGISTRAR->value, Roles::REVIEWER->value);
        }
    }


    /**
     * @return array[]
     */
    private function userList() : array
    {
        return [
            [
                'name' => 'iman',
                'email' => 'iman@gmail.com',
                'password' => Hash::make('123456')
            ],
            [
                'name' => 'amin',
                'email' => 'amin@gmail.com',
                'password' => Hash::make('123456')
            ]
        ];
    }

}
