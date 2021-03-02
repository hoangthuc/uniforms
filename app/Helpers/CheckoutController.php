<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Models\User;
use App\Models\UserLicense;
use App\Models\UserLicenseLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Cashier\Cashier;

class CheckoutController extends Controller
{
    public function index(Request $request, License $license){
        $user_license = \Auth::user()->license;
        return view('landing.checkout.index', ['license' => $license, 'user_license' => $user_license]);
    }

    public function success(Request $request){
        if(!\Session::has('checkout_success') || !\Session::has('license')){
            return redirect()->route('/');
        }
        $license = \Session::get('license');
        /** @var User $user */
        $user = \Auth::user();
        $user_license = $user->license;
        if(!$user_license){
            return redirect()->route('/');
        }
        $payment_id = $request->get('payment_id');
        return view('landing.checkout.success', ['user_license' => $user_license, 'license' => $license, 'payment_id' => $payment_id]);
    }

    public function newKey(){
        /** @var User $user */
        $user = \Auth::user();
        $user_license = $user->license;
        if($user_license){
            return redirect()->route('/');
        }
        $user_license = new UserLicense();
        $user_license->user_id = $user->id;
        $user_license->enabled = true;
        $user_license->keys = Str::uuid();
        $user_license->started_at = now();
        $user_license->ended_at = now();
        $user_license->save();

        return redirect()->route('landing.checkout.success')->with(['checkout_success' => 'You have successfully purchased license!', 'license' => [
            'id' => 0,
            'name' => 'Free',
        ]]);
    }

    public function checkout(Request $request, License $license){
        /** @var User $user */
        $user = \Auth::user();
        $user_license = $user->license;
        try {
            $payment_result = $user->charge($license->price * 100, $request->get('payment_method_id'), [
                'description' => 'Purchase ['.$license->name.'] ('.$license->days.' days), user: ['.$user->email.']',
                'receipt_email' => $user->email,
            ]);
            $log = new UserLicenseLog();
            if(!$user_license){
                $user_license = new UserLicense();
                $user_license->id = $user->id;
                $user_license->keys = Str::uuid();
                $user_license->started_at = now();
                $user_license->ended_at = now();
                $user_license->enabled = true;
            }

            $log->old_started_at = $user_license->started_at;
            $log->old_ended_at = $user_license->ended_at;

            $user_license->ended_at = $user_license->ended_at->addDays($license->days);

            $log->new_started_at = $user_license->started_at;
            $log->new_ended_at = $user_license->ended_at;
            $log->type = UserLicenseLog::TYPE_USER_CHECKOUT;
            $log->desc = __('admin.commons.license_desc.user_checkout'). ': '.$license->name.' ('.$license->days.' ngày) - '.number_format($license->price).'₫. [Payment ID: '.$payment_result->id.']';
            $log->user_id = $user->id;
            $user_license->save();
            $log->save();
            return redirect()->route('landing.checkout.success', ['payment_id' => $payment_result->id])->with(['checkout_success' => 'You have successfully purchased license!', 'license' => $license]);
        }
        catch (\Exception $exception){
            return redirect()->route('landing.checkout.index', ['license' => $license])->with(['checkout_error' => $exception->getMessage()]);
        }
    }
}
