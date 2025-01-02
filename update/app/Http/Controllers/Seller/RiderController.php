<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignRiderRequest;
use App\Http\Requests\DriverRequest;
use App\Http\Resources\RiderDetailsResource;
use App\Http\Resources\RiderOrderResource;
use App\Http\Resources\RiderResource;
use App\Models\Driver;
use App\Models\DriverOrder;
use App\Models\Order;
use App\Repositories\DriverDeviceKeyRepository;
use App\Repositories\DriverNotificationRepository;
use App\Repositories\DriverOrderRepository;
use App\Repositories\DriverRepository;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;
use App\Services\NotificationServices;
use Illuminate\Http\Response;

class RiderController extends Controller
{
    public function index()
    {
        $request = request();

        $page = $request->page ?? 1;
        $perPage = $request->per_page  ?? 10;
        $skip = ($page * $perPage) - $perPage;

        $search = $request->search;

        $drivers = (new DriverRepository())->query()->when($search, function ($query) use ($search) {
            $query->whereHas('user', function ($user) use ($search) {
                $user->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('mobile', 'like', "%{$search}%");
            });
        });
        return $this->json('Riders list', [
            'total' => $drivers->count(),
            'riders' => RiderResource::collection($drivers->skip($skip)->take($perPage)->get())
        ]);
    }

    public function store(DriverRequest $request)
    {
        $user = (new UserRepository())->registerUser($request, true);

        $driver = (new DriverRepository())->storeByUser($user);

        $driver->update([
            'is_approve' => true,
        ]);

        return $this->json('Rider created successfully', [
            'rider' => RiderResource::make($driver)
        ]);
    }

    public function show(Driver $driver)
    {
        return $this->json('Rider details', [
            'rider' => RiderDetailsResource::make($driver)
        ]);
    }

    public function update(DriverRequest $request, Driver $driver)
    {
        $user = (new UserRepository())->updateProfileByRequest($request, $driver->user);

        return $this->json('Rider updated successfully', [
            'rider' => RiderDetailsResource::make($driver)
        ]);
    }

    public function assignRider(AssignRiderRequest $request)
    {
        $driver = Driver::find($request->rider_id);
        $order = (new OrderRepository())->find($request->order_id);

        $assignDriver = $this->hasAssignDriver($order);

        if ($assignDriver) {
            $assignDriver->delete();
        }

        $riderOrder = (new DriverOrderRepository())->storeByrequest($driver, $order);

        $message = 'You have received a '.$riderOrder->assign_for.' request. Order ID: '.$order->prefix.''.$order->order_code;
        $keys = $driver->driverDevices->pluck('key')->toArray();

        $title = 'Assign Order';

        NotificationServices::sendNotification($message, $keys, $title);

        (new DriverNotificationRepository())->storeByRequest($request->rider_id, $message);

        return $this->json('Rider assigned successfully', [
            'rider_order' => RiderOrderResource::make($riderOrder)
        ]);
    }

    private function hasAssignDriver(Order $order){
       return DriverOrder::where('order_id', $order->id)->where('is_completed', false)->first();
    }
}
