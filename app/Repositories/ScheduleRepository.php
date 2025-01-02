<?php

namespace App\Repositories;

use App\Http\Requests\SettingRequest;
use App\Models\OrderSchedule;
use App\Models\Setting;

class ScheduleRepository extends Repository
{
    public function model()
    {
        return OrderSchedule::class;
    }

    public function getByType(string $type)
    {
        $user = auth()->user();
        $schedules = $this->query()->where('type', $type);
        if ($user->hasRole('store')) {
            $schedules = $schedules->where('store_id', $user->store->id);
        }

        return $schedules->get();
    }

    public function updateByRequest(SettingRequest $request, Setting $setting): Setting
    {
        $setting->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return $setting;
    }
}
