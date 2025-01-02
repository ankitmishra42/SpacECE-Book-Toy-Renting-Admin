<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = Role::all();
        foreach ($roles as $role) {
            $users = User::role($role->name)->get();
            $permissions = $role->getPermissionNames()->toArray();
            foreach ($users as $user) {
                $user->syncPermissions($permissions);
            }
        }
    }
}
