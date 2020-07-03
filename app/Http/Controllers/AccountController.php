<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Illuminate\Support\Facades\Hash;
use App\User;

class AccountController extends Controller
{
public function __construct(){
    $this->middleware('auth');
}

public function account(){
    $user  = Auth::user();
    $users  = User::where('id', '=', $user['id'])->first();
    return view('account.view',['account' => $users]);
}

public function update(Request $request){
    $user  = Auth::user();
    if($request->hasFile('image')){
        $profileImage = $request->file('image');
        $imageName = time().'.'.$profileImage->getClientOriginalExtension();
        $profileImage->move("images", $imageName); 
        $updateDetails = [
            'password' => Hash::make($request->password), 
            'email' => $request->email,
            'phone' => $request->phone,
            'image' => $imageName
        ];
        }
        else{
            $updateDetails = [
                'password' => Hash::make($request->password),
                'email' => $request->email,
                'phone' => $request->phone 
            ];
        }
    
    User::where('id', '=', $user['id'])->update($updateDetails);
    return redirect('/admin/account')->with('status', 'You record has been updated');
}
} 
