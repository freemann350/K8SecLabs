<?php

namespace App\Http\Controllers;

use App\Models\Environment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $environments = Environment::where('end_date', null)
        ->withCount(['environmentAccesses as user_count' => function($query) {
            $query->select(DB::raw('count(user_id)'));
        }])
        ->get();

        return  view('dashboard.index', compact('environments'));
    }
}
