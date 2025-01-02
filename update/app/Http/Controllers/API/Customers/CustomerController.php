<?php

namespace App\Http\Controllers\API\Customers;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;

class CustomerController extends Controller
{
    public function show()
    {
        $customer = auth()->user()->customer;

        return $this->json('customer details', [
            'customer' => (new CustomerResource($customer)),
        ]);
    }
}
