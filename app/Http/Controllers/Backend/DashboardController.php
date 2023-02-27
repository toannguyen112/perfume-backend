<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Jamstackvietnam\Support\Traits\HasCrudActions;

class DashboardController extends Controller
{
    public function index()
    {
        $todayCustomers = User::whereDate('created_at', Carbon::today())->count();

        $data = [
            'todaySales' => 0,
            'todayOrders' => 0,
            'todayCustomers' => $todayCustomers,
            'todayContacts' => 0,
        ];

        return inertia('Dashboard', ['data' => $data]);
    }
}
