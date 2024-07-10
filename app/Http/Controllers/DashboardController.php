<?php

namespace App\Http\Controllers;

use App\Models\Environment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $environments = Environment::where('end_date', null)
            ->withCount(['environmentAccesses as user_count' => function($query) {
                $query->select(DB::raw('count(user_id)'));
            }])->get()->filter(function ($environment) {
                return $environment->user_count < $environment->quantity;
            });
            
            return  view('dashboard.index', compact('environments'));
        } catch (\Exception $e) {
            $errormsg = $this->createError('500','Internal Server Error',$e->getMessage());
            return redirect()->route("User.editMe")->with('error_msg', $errormsg);
        }
    }

    private function createError($code, $status, $message) {
        $errormsg['code']= $code;
        $errormsg['status']= $status;
        $errormsg['message']= $message;

        return $errormsg;
    }
}
