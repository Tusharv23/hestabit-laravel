<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class UserController extends Controller
{
    //
    public $response;
    public function __construct()
    {
        $this->response = [
            'data'=>null,
            'code'=>200,
            'error'=>null
        ];
    }

    public function getInvitedUsers(Request $request)
    {
        $this->response['data'] =  \DB::table('Profiles')
                    ->whereNotNull('code_id')
                    ->whereBetween('reg_date', [Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()])
                    ->get();
        return $this->response;
    }

    public function leaderboard(Request $request)
    {
        return \DB::table('Profiles as p1')
                    ->join('Code','p1.id','=','Code.profile_id')
                    ->join('Profiles as p2','Code.id','=','p2.code_id')
                    ->groupBy('p2.code_id','Code.profile_id','p1.firstname','p1.reg_date')
                    ->select(\DB::raw('count(*) as total'),'p1.firstname','p1.reg_date')
                    ->orderBy('total','desc')
                    ->orderBy('reg_date','desc')
                    ->get();
    }
}
