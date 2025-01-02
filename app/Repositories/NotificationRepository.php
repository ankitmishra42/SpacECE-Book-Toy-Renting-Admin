<?php

namespace App\Repositories;

use App\Models\Notification;

class NotificationRepository extends Repository
{
    public function model()
    {
        return Notification::class;
    }

    public function notificationListByStatus($read = null)
    {
        $customer = auth()->user()->customer;
        $notifications = $this->query()->where('customer_id', $customer->id);

        if ($read) {
            $notifications = $notifications->where('isRead', $read);
        }

        return $notifications->latest()->get();
    }

    public function storeByRequest($customerId, $message, $title): Notification
    {
        $notification = $this->create([
            'customer_id' => $customerId,
            'title' => $title,
            'message' => $message,
            'isRead' => (int) 0,
        ]);

        return $notification;
    }

    public function readUpdateByRequest(Notification $notification): Notification
    {
        $notification->update([
            'isRead' => 1,
        ]);

        return $notification;
    }

    public function deleteByRequest(Notification $notification): Notification
    {
        $notification->delete();

        return $notification;
    }
}
