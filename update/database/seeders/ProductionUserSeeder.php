<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProductionUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
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
            'email' => 'root@laundrymart.com',
            'password' => Hash::make('secret@123'),
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
            'email' => 'admin@laundrymart.com',
            'password' => Hash::make('secret@123'),
            'mobile' => '01000000002',
        ]);

        Wallet::factory()->create([
            'user_id' => $adminUser->id,
        ]);

        $adminUser->assignRole('admin');
    }

    private function createVisitorUser()
    {
        $visitorUser = User::factory()->create([
            'first_name' => 'Visitor',
            'email' => 'visitor@laundrymart.com',
            'password' => Hash::make('secret@123'),
            'mobile' => '01000000003',
        ]);

        Wallet::factory()->create([
            'user_id' => $visitorUser->id,
        ]);

        $visitorUser->assignRole('visitor');
    }
}
