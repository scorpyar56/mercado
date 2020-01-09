<?php
/**
 * LaraClassified - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.bedigit.com
 *
 * LICENSE
 * -------
 * This software is furnished under a license and may be used and copied
 * only in accordance with the terms of such license and with the inclusion
 * of the above copyright notice. If you Purchased from Codecanyon,
 * Please read the full License from here - http://codecanyon.net/licenses/standard
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\Traits\SendsPasswordResetSmsTrait;
use App\Http\Requests\ForgotPasswordRequest;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Http\Controllers\FrontController;
use Torann\LaravelMetaTags\Facades\MetaTag;

use App\Models\PasswordReset;
use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use App\Http\Controllers\Account\NotifyController;
use App\Models\Blacklist;
use App\Helpers\ArrayHelper;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewPassword;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends FrontController
{
    use SendsPasswordResetEmails {
        sendResetLinkEmail as public traitSendResetLinkEmail;
    }
    use SendsPasswordResetSmsTrait;
    
    protected $redirectTo = '/account';
    
    /**
     * PasswordController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->middleware('guest');
    }
    
    // -------------------------------------------------------
    // Laravel overwrites for loading LaraClassified views
    // -------------------------------------------------------
    
    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        // Meta Tags
        MetaTag::set('title', getMetaTag('title', 'password'));
        MetaTag::set('description', strip_tags(getMetaTag('description', 'password')));
        MetaTag::set('keywords', getMetaTag('keywords', 'password'));
        
        return view('auth.passwords.email');
    }
    
    /**
     * Send a reset link to the given user.
     *
     * @param ForgotPasswordRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(ForgotPasswordRequest $request)
    {
        $user = !is_null(User::where("phone", $request->input("phone"))->first())  ? User::where("phone", $request->input("phone"))->first() : null;

        if( is_null($user['deleted_at'])){
            flash(t('User is deleted.'))->error();
            return redirect()->back();
        }

        $contactForm = $request->all();
		$contactForm['country_code'] = config('country.code');
		$contactForm['country_name'] = config('country.name');
        $contactForm = ArrayHelper::toObject($contactForm);
        $contactForm->email = $user->email ;
        $contactForm->first_name = $user->name;
        $new_password = rand(0000,9999);   
        $user->password = Hash::make($new_password);     
        $user->save();

        $contactForm->message = t("New password : ") . $new_password;

		// Send Contact Email
		try {
            Notification::route('mail', $user->email)->notify(new NewPassword($contactForm));
            $message = t("New password is sent on your email address");
            flash($message)->success();
            return redirect(config('app.locale') . '/');
		} catch (\Exception $e) {
            flash($e->getMessage())->error();
            return redirect()->back();
        }
        
        // return redirect(config('app.locale') . '/password/token')->with(['phone' => $request->input("phone")]);
        return redirect(config('app.locale') . '/');


        // Get the right login field
        // $field = getLoginField($request->input('phone'));
        // $request->merge([$field => $request->input('phone')]);
        // if ($field != 'email') {
        //     $request->merge(['email' => $request->input('phone')]);
        // }
        
        // // Send the Token by SMS
        // if ($field == 'phone') {
        //     // return $this->sendResetTokenSms($request);
        //     // R.S
        //     if(!($this->sendVerificationCode($request))){
        //         return redirect()->back();
        //     }
        //     // Got to Token verification Form
            // return redirect(config('app.locale') . '/password/token');

        // }
        
        // // Go to the core process
        return $this->traitSendResetLinkEmail($request);
    }


    /**
	 * Send verification SMS
	 * R.S
	 * @param $request
	 * @param bool $displayFlashMessage
	 * @return bool
	 */
	public function sendVerificationCode( $request, $displayFlashMessage = true)
	{
        // Form validation
        $rules = ['phone' => 'required'];
        $this->validate($request, $rules);

        // Check if the phone exists
        // var_dump($request->input('phone'));
        // die();
        $user = User::where('phone', $request->input('phone'))->first();

        if($user['name'] == NULL && $user['password'] == NULL && isset($user['phone'])){
            flash(t('Please complete your registration.'))->error();
            return false;
        }

        if( is_null($user['deleted_at'])){
            flash(t('User is deleted.'))->error();
            return false;
        }

        if (empty($user)){
            flash(t('The entered value is not registered with us.'))->error();
            return false;
        }
        
		//If userphone is in black list redirect
		if(Blacklist::where('entry', $request->input('phone'))->first() != NULL){
            flash(t('This number is banned.'))->error();            
			return false;
		}

        // Create the token in database
        $token = mt_rand(100000, 999999);
        $passwordReset = PasswordReset::where('phone', $request->input('phone'))->first();
        if (empty($passwordReset)) {
            $passwordResetInfo = [
                'email'      => null,
                'phone'      => $request->input('phone'),
                'token'      => $token,
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $passwordReset = new PasswordReset($passwordResetInfo);
        } else {
            $passwordReset->token = $token;
            $passwordReset->created_at = date('Y-m-d H:i:s');
        }
        $passwordReset->save();

        try {
            // Send the token by SMS
            // $passwordReset->notify(new ResetPasswordNotification($user, $token, 'phone'));
            
            if(!NotifyController::SendNotification(
                ['1' => 'NOFIFY_SMS'],
                ['phone' => "$user->phone",
                'message'=>"Confirmation code for reset password id: $token",
                'reset_password' => 1]
            )){
                return redirect()->back();
            }
        } catch (\Exception $e) {
            flash($e->getMessage())->error();
        }
		return true;
	}
}
