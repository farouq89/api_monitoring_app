<?php

namespace App\Http\Controllers;

use App\Models\ApiEndpoint;  // Assuming this is the model for your API endpoints
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch statistics for APIs and users
        $totalApis = ApiEndpoint::count();
        $activeApis = ApiEndpoint::where('status', true)->count(); // Count active APIs
        $inactiveApis = ApiEndpoint::where('status', false)->count(); // Count inactive APIs

        $totalUsers = User::count();
        $activeUsers = User::where('status', 'Active')->count(); // Assuming you have an active status for users
        $inactiveUsers = User::where('status', 'Inactive')->count(); // Count inactive users

        // Pass statistics to the view
        addVendors(['amcharts', 'amcharts-maps', 'amcharts-stock']);
        return view('pages/dashboards.index', compact(
            'totalApis',
            'activeApis',
            'inactiveApis',
            'totalUsers',
            'activeUsers',
            'inactiveUsers'
        ));
    }
}
