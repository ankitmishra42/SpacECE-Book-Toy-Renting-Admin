<?php

namespace App\Repositories;

use App\Models\DriverDeviceKey;
use Ladumor\OneSignal\OneSignal;

class DriverDeviceKeyRepository extends Repository
{
    public function model()
    {
        return DriverDeviceKey::class;
    }

    public function findByKey($key)
    {
        return $this->query()->where('key', $key)->first();
    }

    public function storeByRequest($driver, $key): DriverDeviceKey
    {
        $exists = $this->findByKey($key);
        if (! $exists) {
            $exists = $this->create([
                'driver_id' => $driver->id,
                'key' => $key,
            ]);
        }

        return $exists;
    }

    public function destroy($key): bool
    {
        $exists = $this->findByKey($key);

        if ($exists) {
            $exists->delete();

            return true;
        }

        return false;
    }
}
