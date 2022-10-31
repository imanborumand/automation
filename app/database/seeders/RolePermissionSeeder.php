<?php

namespace Database\Seeders;


use App\Enums\User\Roles;
use App\Models\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        if(Role::count() > 0) return;

        $rolesList = $this->rolesList();

        foreach ($rolesList as $role) {
            Role::create(
                ['name' => $role['name'], 'guard_name' => $role['guard_name']]
            );
        }
    }


    /**
     * @return array[]
     */
    private function rolesList() : array
    {
        return [
            [
                'name' => Roles::REGISTRAR->value,
                'guard_name' => 'api'
            ],
            [
                'name' => Roles::REVIEWER->value,
                'guard_name' => 'api'
            ],
        ];
    }

}
