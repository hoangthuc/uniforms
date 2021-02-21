<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','username','level',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function get_all_users($level='subscribe'){
        if($level=='administrator' || $level=='super-admin' ){
            $users = DB::table('users')->get();
        }else{
            $users = DB::table('users')->where('level', '=', 'subscribe')->get();
        }

        return $users;
    }

    public static function get_roles(){
        $roles= [
            'subscribe' => 'Subscribe',
            'administrator' => 'Administrator'
        ];
      return $roles;
    }


    public static function getUserByID($id){
        $user = User::where('id',$id)->first();
        return $user;
    }
    public static function update_user($id,$data){
        DB::table('users')
            ->where('id', $id)
            ->update($data);
    }

    // get meta product
    public static function get_user_meta($user_id,$meta_key,$re=''){
        $user_meta = DB::table('user_meta')->where('user_id',$user_id)->where('meta_key',$meta_key)->first();
        if($user_meta)return $user_meta->meta_value ;
        return $re;
    }
    // update meta product
    public static function update_user_meta($user_id,$meta_key,$meta_value){
        $user_meta = self::get_user_meta($user_id,$meta_key);
        if($user_meta){
            DB::table('user_meta')->where('user_id',$user_id)->where('meta_key',$meta_key)->update(['meta_value'=>$meta_value]);
        }else{
            $user_meta =  self::add_user_meta($user_id,$meta_key,$meta_value);
        }
        return $user_meta;
    }

    // get meta product
    public static function add_user_meta($user_id,$meta_key,$meta_value){
        $user_meta = self::get_user_meta($user_id,$meta_key);
        if(!$user_meta){
            $data = array(
                'user_id' => $user_id,
                'meta_key' => $meta_key,
                'meta_value' => $meta_value,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $meta_id = DB::table('user_meta')->insertGetId($data);
            return $meta_id;
        }
        return false;
    }

}
