<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ApiTokenController extends Controller
{
    /**
     * Update the authenticated user's API token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function update(Request $request)
    {
        $data['username'] = $request['username'];
        $data['password'] = $request['password'];

        if(Auth::attempt($data, true)){
            $user = Auth::user();
            if($user->level == 'administrator'){
                $token = Str::random(60);
                $request->user()->forceFill([
                    'remember_token' => hash('sha256', $token),
                ])->save();
                return ['token' => $token];
            }else{
                return response()->json([
                    'status' => 'fails',
                    'message' => 'Account not authorized'
                ], 401);
            }

        }else{
            return response()->json([
                'status' => 'fails',
                'message' => 'Account is Invalid'
            ], 401);
        }

    }
}