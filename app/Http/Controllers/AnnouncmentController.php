<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Announcments;
class AnnouncmentController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    public function index(){    
        if(isset($_GET['search'])){
            $search = $_GET['search'];
            $users = Announcments::where('title', 'LIKE', "%$search%")->paginate(50);
        } 
        else{
        $users = Announcments::paginate(50);
        }
        return view('announcments.index',['users' => $users]);
    }

    public function add(){
        return view('announcments.add'); 
    }
    public function submit(Request $request){
        if($request->hasFile('image')){ 
            $profileImage = $request->file('image');
            $imageName = time().'.'.$profileImage->getClientOriginalExtension();
            $profileImage->move("images", $imageName); 
           Announcments::create([
            'title' => $request['title'],
            'description' => $request['description'],
            'image' => $imageName
        ]);
    }
    return redirect()->back()->with('status', 'Annoucement Has be placed');
    }
    
    public function edit(Request $request){
        $announce = Announcments::where('id','=',$request->id)->get();
        return view('announcments.edit',['announce' => $announce]); 
    }

    public function update(Request $request){
        if($request->hasFile('image')){ 
            $profileImage = $request->file('image');
            $imageName = time().'.'.$profileImage->getClientOriginalExtension();
            $profileImage->move("images", $imageName);  
            $updateDetails = [
            'title' => $request['title'],
            'description' => $request['description'],
            'image' => $imageName
        ];
     }
     else{
        $updateDetails = [
            'title' => $request['title'],
            'description' => $request['description']
        ];
     } 
    Announcments::where('id', '=', $request['id'])->update($updateDetails);
    return redirect()->back()->with('status', 'Annoucement Updated');
    }

    public function delete(Request $request){
        Announcments::where('id', '=', $request->id)->delete();
        return 'You record has been deleted successfully';
}
}
