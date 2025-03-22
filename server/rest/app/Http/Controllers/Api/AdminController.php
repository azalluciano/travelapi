<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Create a new AdminController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('admin');
    }

    /**
     * Get all destinations for admin dashboard.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destinations()
    {
        $destinations = Destination::all();

        return response()->json([
            'data' => $destinations
        ]);
    }

    /**
     * Get admin dashboard statistics.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function statistics()
    {
        $totalDestinations = Destination::count();
        $totalValue = Destination::sum('price');
        $averageDuration = Destination::avg('duration');

        return response()->json([
            'total_destinations' => $totalDestinations,
            'total_value' => $totalValue,
            'average_duration' => $averageDuration
        ]);
    }
}