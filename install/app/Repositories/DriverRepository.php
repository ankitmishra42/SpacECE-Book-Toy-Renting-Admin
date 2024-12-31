<?php

namespace App\Repositories;

use App\Enums\Roles;
use App\Models\Driver;
use App\Models\User;
use Spatie\Permission\Models\Role;

class DriverRepository extends Repository
{
    public function model()
    {
        return Driver::class;
    }

    public function storeByUser(User $user)
    {
        $role = Role::where('name', Roles::DRIVER->value)->first();
        $user->assignRole(Roles::DRIVER->value);

        $permissions = $role->getPermissionNames()->toArray();
        $user->givePermissionTo($permissions);

        return $this->create([
            'user_id' => $user->id,
            // 'store_id' => auth()->user()->store->id,
        ]);
    }

    public function getAllDeactive()
    {
        $drivers = $this->model()::query();
        $active = 0;
        $drivers = $drivers->whereHas('user', function ($user) use ($active) {
            $user->where('is_active', $active);
        });

        return $drivers->latest('id')->get();
    }

    public function findById($id)
    {
        return $this->find($id);
    }

    public function getTodaysOrder()
    {
        $today = date('Y-m-d');
        $drive = auth()->user()->driver;
        $orders = $drive->orders();

        $orders = $orders->where('order_status', '!=', 'Delivered')
            ->where('pick_date', $today)
            ->orWhere('delivery_date', $today)
            ->wherePivot('is_accept', true)
            ->get();

        return $orders;
    }

    public function getTodaysOrderByRequest($status)
    {
        $today = date('Y-m-d');
        $drive = auth()->user()->driver;
        $orders = $drive->orders();

        $orders = $orders->where('pick_date', $today)
            ->orWhere('delivery_date', $today)
            ->wherePivot('is_accept', true)
            ->wherePivot('status', $status)
            ->get();

        return $orders;
    }

    public function getTodaysTotalPending()
    {
        $today = date('Y-m-d');
        $drive = auth()->user()->driver;
        $orders = $drive->orders();
        $orders = $orders->where('pick_date', $today)
            ->where('order_status', 'Pending')
            ->wherePivot('is_accept', true)
            ->get();

        return $orders;
    }

    public function getThisWeekDelivery()
    {
        $startDate = now()->startOfWeek()->format('Y-m-d');
        $endDate = now()->endOfWeek()->format('Y-m-d');

        $drive = auth()->user()->driver;
        $orders = $drive->orders();

        $orders = $orders->whereBetween('delivery_date', [$startDate, $endDate])
            ->where('order_status', '!=', 'Delivered')
            ->wherePivot('is_accept', true)
            ->wherePivot('status', 'delivery')
            ->get();

        return $orders;
    }

    public function getLastWeek()
    {
        $startDate = now()->subWeek()->startOfWeek()->format('Y-m-d');
        $endDate = now()->subWeek()->endOfWeek()->format('Y-m-d');

        $drive = auth()->user()->driver;
        $orders = $drive->orderHistories();

        return $orders->whereBetween('delivery_date', [$startDate, $endDate])
            ->orWhereBetween('delivery_date', [$startDate, $endDate])
            ->get();
    }

    public function getTotalOrder()
    {
        $drive = auth()->user()->driver;

        $is_accept = \request()->isAccept;

        $orders = $drive->orders()->wherePivot('is_accept', $is_accept)->get();

        return $orders;
    }
}
