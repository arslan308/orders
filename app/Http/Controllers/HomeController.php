<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Profit;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user  = Auth::user();
        $month = date('m');
        $year = date('y');
        if($user['is_admin'] == 0){
        $colid = $user['name'];
        $records =  Profit::where('client_id','=', $colid)->whereYear('month', '=', '20'.$year)->whereMonth('month', '=', $month)->get();
        $items = [];
        foreach($records as $record){
            $items[] = $record['items'];
        }
        $array2 = array_count_values(explode(',', implode(',', $items)));
        $popular = key($array2);
    }
    else{
        $records =  Profit::whereYear('month', '=', '20'.$year)->whereMonth('month', '=', $month)->get();
        $items = [];
        foreach($records as $record){
            $items[] = $record['items'];
        }
        $array2 = array_count_values(explode(',', implode(',', $items)));
        $popular = key($array2);
    }
        $updateDetails = [
            'to_id' => $user->id,
            'seen' => '0',
        ];
        $totalunread = DB::table('messages')->where($updateDetails)->count(); 
        return view('home',['totalunread' => $totalunread, 'records' => $records,'popular' => $popular]);
    }
}
