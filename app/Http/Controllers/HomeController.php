<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $level = Auth::user()->level;
        if($level == 'administrator' )return view('admin.dashboard');
        return redirect('/');

    }

    public function check(Request $request)
    {
        $resulf = [];
        foreach ($request['data'] as $value) {
            $data[$value['name']] = $value['value'];
        }
        if(isset($data['redirect_url'])){
            $redirect_url = $data['redirect_url'];
            unset($data['redirect_url']);
        }

        if (Auth::attempt($data, true)) {
            $user = Auth::user();
            $request->session()->regenerate();
            if($user->level == 'administrator'){ $resulf['redirect']= url('admin');}else{
                $resulf['redirect']= url('/my-account');
            }
            if(isset($redirect_url)){
                $resulf['redirect'] = $redirect_url;
            }

        } else {
            $resulf['error'] = trans('auth.failed');
        }

        return  \GuzzleHttp\json_encode($resulf);
    }

    public function register(Request $request){
        $resulf = [];
        foreach ($request['data'] as $value) {
            $data[$value['name']] = $value['value'];
        }
        $isUsernameExists = DB::table('users')->where('username', $data['username'])->count();
        $isEmailExists =  DB::table('users')->where('email', $data['email'])->count();
        if($isUsernameExists)$resulf['error'] = array('username'=>'Username is exist');
        if($isEmailExists)$resulf['error'] = array('email'=>'Email is exist');
        if(!$isUsernameExists && !$isEmailExists){
            $user =  User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'username' => $data['username'],
                'level'=>'subscribe',
                'password' => bcrypt($data['password']),
            ]);
            if( isset($user->id)){
                foreach ($data as $meta_key=>$meta_value){
                    User::add_user_meta($user->id,$meta_key,$meta_value);
                }
                Auth::attempt(
                    array(
                        'username'=>$data['username'],
                        'password'=>$data['password'],
                    ), true);

            }
            $resulf['success'] = 'Successfully!';
            $resulf['user'] = $user;
            $resulf['redirect'] = url('my-account');
            return \GuzzleHttp\json_encode($resulf);
        }


        return \GuzzleHttp\json_encode($resulf);
    }


}
