<?php

namespace Database\Seeders;

use App\Enums\Roles;
use App\Models\Address;
use App\Models\Store;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < rand(5, 16); $i++) {
            $user = User::factory()->create([
                'last_name' => 'shop',
                'email' => 'shop_'.$i.'@example.com',
            ]);

            Wallet::factory()->create([
                'user_id' => $user->id,
            ]);

            $store = Store::factory()->create([
                'shop_owner' => $user->id,
            ]);

            Address::factory()->create([
                'customer_id' => null,
                'store_id' => $store->id,
            ]);

            $role = Role::where('name', Roles::STORE->value)->first();
            $permissions = $role->getPermissionNames()->toArray();
            $user->givePermissionTo($permissions);

            $user->assignRole(Roles::STORE->value);
        }
    }
}
