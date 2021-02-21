<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\auth\CheckFormRequest;
use Illuminate\Http\Request;
use Validator;
use App\User;
use Illuminate\Support\Facades\DB;

class UpdateUser extends Controller
{
    public function ChangePassword(Request $request)
    {
//       // dd($request->input());
//        return redirect()->back()->withInput();
        $validate = Validator::make(
            $request->all(),
            [
                'password' => 'required|min:5|max:255',
                'password_comfirm' => 'required|same:password|min:5',
            ],

            [
                'required' => ':attribute not required',
                'min' => ':attribute Not to be smaller :min',
                'max' => ':attribute Not bigger :max',
                'same' => 'Password Confirmation should match the Password',
            ],

            [
                'password' => 'Password',
                'password_comfirm' => 'Password Confirmation',
            ]

        );

        if ($validate->fails()) {
            return View('admin.users.user_edit',['user_id'=>$request->user_id,'request'=>$request])->withErrors($validate);
        }else{
            DB::table('users')
                ->where('id', $request->user_id)
                ->update(['password' => \Hash::make($request->password_u)]);
            return redirect('admin/user/'.$request->user_id.'/edit')->with('success_password', 'Update successfully');
        }
dd(1);
    }

    public function UpdateUserInfo(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'name' => 'required|min:5',
                'email' => 'required',
            ],

            [
                'required' => ':attribute not required',
            ],

            [
                'name' => 'Name',
                'email' => 'Email',
            ]

        );

        if ($validate->fails()) {
            return View('admin.users.user_edit',['user_id'=>$request->user_id,'request'=>$request])->withErrors($validate);
        }else{
            DB::table('users')
                ->where('id', $request->user_id)
                ->update(['name' => $request->name,'email'=>$request->email, 'level'=> $request->level ]);
            $user = User::getUserByID($request->user_id);
            $user->syncRoles($request->level);
            return redirect('admin/user/'.$request->user_id.'/edit')->with('success_info', 'Update successfully');
        }
        dd(1);
    }

    public function DeleteUser(Request $request)
    {
      if($request->_token){
          User::find($request->user_id)->delete();
          return $request->user_id;
      }
      //  dd(1);
    }


}
