<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = 'admin/vendors';

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
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function register(Request $request)
    {
    // validate the form 

    $this->validator($request->all())->validate();

    // add the user
    $this->create($request->all());

    // redirect user
    return redirect($this->redirectPath());
    }
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        if(request()->hasFile('image')){
        $profileImage = request()->file('image');
        $imageName = time().'.'.$profileImage->getClientOriginalExtension();
        $profileImage->move("images", $imageName); 
        }
        else{
            $imageName = '';
        }
         User::insert([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'type' => $data['type'],
            'phone' => $data['phone'],
            'profit' => $data['profit'],
            'newinsta' => $data['instahandle'],    
            'image' => $imageName,
        ]);
        return redirect('/admin/vendors');  
    }
}
