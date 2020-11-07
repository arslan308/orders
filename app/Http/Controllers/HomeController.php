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
        $allrec =  Profit::orderBy('month', 'ASC')->where('client_id','=', $colid)->take(5)->get();
        $records =  Profit::where('client_id','=', $colid)->whereYear('month', '=', '20'.$year)->whereMonth('month', '=', $month)->get();
        $records2 =  Profit::orderBy('month', 'DESC')->where('client_id','=', $colid)->get();  
        $incentiverecord =  Profit::orderBy('month', 'DESC')->where('client_id','=', $colid)->where('month','>=', "2020-08-01")->sum('shop_price');
        $items = [];
        foreach($records2 as $record){  
            $splited = explode(',',$record['items']);
                foreach($splited as $split){ 
                    $items[] =  $split;
                }
        }
        $totalitems = count($items);
        $newitemtitle = [];
        foreach($items as $item){
        $splitedtitle = explode(' -', $item);
        $pieces = explode(' ', $splitedtitle[0]);
        $last_word = array_pop($pieces);
        $newitemtitle[] =  $last_word;
    }
        $array2 = array_count_values(explode(',', implode(',', $newitemtitle)));
        $popular = $array2;
        arsort($popular);
        $ptypes = [];
        foreach($popular as $pop => $val){
            $ptypes[]   = array("label"=>$pop, "y"=>$val);
            }
        $newarr = [];
        $ctotal  = 0;
        $lastm = count($allrec)-1;
        $secondlast = count($allrec)-2; 
        $lastval = '';
        $secondval = '';
        foreach($allrec as $key => $rec){  
            $ctotal += $rec['shop_price'];
            $newarr[]   = array("y"=> $ctotal, "label"=>$rec['month']);
            if($key == $lastm){
                $lastval = $ctotal;
            }
            if($key == $secondlast){
                $secondval = $ctotal; 
            }
            }

            if($lastval > 0 & $secondval !=''){
            $growth = ($lastval - $secondval) / $secondval*100;
            $growth = round($growth, 2);
            $growth = $growth.'%'; 
           }
        else{
            $growth = "N/A";
        }
    }
    else{
        $incentiverecord= '';
        $newarr = '';
        $ptypes = '';
        $records2 =  Profit::all(); 
        $items = [];
        foreach($records2 as $record){ 
            $splited = explode(',',$record['items']);
                foreach($splited as $split){ 
                    $items[] =  $split;
                }
        }
        $growth = "N/A"; 
        $totalitems = count($items);
        $records =  Profit::whereYear('month', '=', '20'.$year)->whereMonth('month', '=', $month)->get();
    }
        $updateDetails = [
            'to_id' => $user->id, 
            'seen' => '0',
        ]; 

        $totalunread = DB::table('messages')->where($updateDetails)->count();  
        $announce = DB::table('announcments')->orderBy('id', 'DESC')->get();
        return view('home',['incentiverecord' => $incentiverecord, 'growth' => $growth , 'totalitems' => $totalitems, 'records2' => $records2 ,'ptypes' => $ptypes ,'totalunread' => $totalunread, 'records' => $records,'allsale' => $newarr,'announce' => $announce]);
    }
}
