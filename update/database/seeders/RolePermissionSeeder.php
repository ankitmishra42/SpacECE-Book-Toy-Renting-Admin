<?php

namespace Database\Seeders;

use App\Enums\Roles;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Artisan::call('config:clear');
        $this->grantPermissionsToRoles();
    }

    private function grantPermissionsToRoles()
    {
        foreach (Roles::cases() as $roleName) {
            $role = Role::where('name', $roleName->value)->first();

            if ($role) {
                $permissions = [];
                foreach (config('acl.permissions') as $permission => $roles) {
                    if (in_array($role->name, $roles)) {
                        $permissions[] = $permission;
                    }
                }
                $role->syncPermissions($permissions);
            }
        }
    }
}
