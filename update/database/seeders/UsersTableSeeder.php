<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createRootUser();
        $this->createAdminUser();
        $this->createVisitorUser();
    }

    private function createRootUser()
    {
        $rootUser = User::factory()->create([
            'first_name' => 'Root',
            'email' => 'root@example.com',
            'mobile' => '01000000001',
            'is_active' => true,
        ]);

        Wallet::factory()->create([
            'user_id' => $rootUser->id,
        ]);

        $permissions = config('acl.permissions');

        foreach ($permissions as $permission => $value) {
            $rootUser->givePermissionTo($permission);
        }
        $rootUser->assignRole('root');
    }

    private function createAdminUser()
    {
        $adminUser = User::factory()->create([
            'first_name' => 'Admin',
            'email' => 'admin@example.com',
            'mobile' => '01000000002',
        ]);

        Wallet::factory()->create([
            'user_id' => $adminUser->id,
        ]);

        $adminUser->givePermissionTo('root');
        $adminUser->assignRole('admin');
    }

    private function createVisitorUser()
    {
        $visitorUser = User::factory()->create([
            'first_name' => 'Visitor',
            'email' => 'visitor@example.com',
            'mobile' => '01000000003',
        ]);

        Wallet::factory()->create([
            'user_id' => $visitorUser->id,
        ]);

        $visitorUser->givePermissionTo('root');
        $visitorUser->assignRole('visitor');
    }
}
