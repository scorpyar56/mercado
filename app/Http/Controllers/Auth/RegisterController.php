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

use App\Helpers\Ip;
use App\Helpers\Localization\Country as CountryLocalization;
use App\Helpers\Localization\Helpers\Country as CountryLocalizationHelper;
use App\Http\Controllers\Auth\Traits\VerificationTrait;
use App\Http\Controllers\FrontController;
use App\Http\Requests\RegisterCodeRequest;// all field who are required R.S.
use App\Http\Requests\UserRequest;
use App\Http\Requests\CodeRequest;//R.S.
use App\Models\Gender;
use App\Models\Permission;
use App\Models\User;
use App\Models\UserType;
use App\Notifications\UserActivated;
use App\Notifications\UserNotification;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\View;
use Torann\LaravelMetaTags\Facades\MetaTag;

//R.S.
use App\Helpers\DBTool;
use Illuminate\Support\Facades\DB;
use App\Helpers\ArrayHelper;
use App\Http\Controllers\Account\NotifyController;
use App\Models\Blacklist;

class RegisterController extends FrontController
{
	use RegistersUsers, VerificationTrait;
	
	/**
	 * Where to redirect users after login / registration.
	 *
	 * @var string
	 */
	protected $redirectTo = '/account';
	
	/**
	 * @var array
	 */
	public $msg = [];

	/**
	 * @var string
	 * R.S
	 */
	public $message = "Confirmation code for your phone number is: ";
	
	/**
	 * RegisterController constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		
		// From Laravel 5.3.4 or above
		$this->middleware(function ($request, $next) {
			$this->commonQueries();
			
			return $next($request);
		});
	}
	
	/**
	 * Common Queries
	 */
	public function commonQueries()
	{
		$this->redirectTo = config('app.locale') . '/account';
	}
	
	/**
	 * Show the form the create a new user account.
	 *
	 * @return View
	 */
	public function showRegistrationForm()
	{
		$data = [];
		
		// References
		$data['countries'] = CountryLocalizationHelper::transAll(CountryLocalization::getCountries());
		$data['genders'] = Gender::trans()->get();
		
		// Meta Tags
		MetaTag::set('title', getMetaTag('title', 'register'));
		MetaTag::set('description', strip_tags(getMetaTag('description', 'register')));
		MetaTag::set('keywords', getMetaTag('keywords', 'register'));
		
		// return view('auth.register.indexFirst', $data);
		return view('auth.register.index', $data);

	}

	
	/**
	 * Register a new user account.
	 * R.S
	 * @param UserRequest $request
	 * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function register(UserRequest $request)
	{
		//If userphone is in black list redirect
		if(Blacklist::where('entry', $request->input('phone'))->first()){
			$data['post'] = $request->input('phone');
			return view('post.unbanrequest', $data);
//                    return view('layouts.inc.modal.unbanrequest', $data);
//                    return array('banPhone', $banPhone);
		}

		// R.S
		// Delete users who has delete prifile one year later
		$querry = "SELECT * FROM ". DBTool::rawTable('users') . " WHERE deleted_at < NOW() - INTERVAL '1' YEAR";
		$deletedUser = DB::select(DB::raw($querry));
		$deletedUser = ArrayHelper::fromObject($deletedUser);
		if(is_array($deletedUser) && isset($deletedUser[0]['id'])){
			foreach($deletedUser as $kk => $value){
				User::where('id', $value['id'])->delete();
			}
		}

		// if exists users with this phone number
		if(User::where( "phone", $request->input('phone'))->where("deleted_at", null)->count() > 0){
			$message = t("The phone has already been taken.");
			flash($message)->error();
			return redirect()->back();
		}

		//if is deleted user with this phone
		if( User::where( "phone", $request->input('phone'))->count() > 0 ){
			$querry = "SELECT * FROM ". DBTool::rawTable('users') . " WHERE phone='" . $request->input('phone') . "'";
			$userDB = DB::select(DB::raw($querry));
			$userDB = ArrayHelper::fromObject($userDB);
			if(isset($userDB[0]['closed']) && isset($userDB[0]['deleted_at']) ){
				$user = User::withoutGlobalScopes([VerifiedScope::class])->find($userDB[0]['id']);
				$user->closed = 0;
				$user->password = Hash::make($request->input('password'));
				$user->deleted_at = null;
				$user->save();
				if (Auth::loginUsingId($user->id)) {
					$message = t("Re-register successfully.");
					flash($message)->success();
					return redirect()->intended(config('app.locale') . '/account');
				}
			}
		}
		// end R.S

		// Conditions to Verify User's Email or Phone
		$emailVerificationRequired = config('settings.mail.email_verification') == 1 && $request->filled('email');
		$phoneVerificationRequired = config('settings.sms.phone_verification') == 1 && $request->filled('phone');

		// R.S=============================================
		// SEARCH USER WITH THIS PHONE NUMBER
		// $sql = "SELECT * FROM ". DBTool::rawTable('users') ;
		// $query = $sql ." WHERE phone = '" . $request->input('phone') . "'";
		// $user = DB::select(DB::raw($query));
		// $user = ArrayHelper::fromObject($user);

		// //Check New Users
		// if(isset($user[0]["id"])){
		// 	$newQuerry = $sql . " WHERE id = '" . $user[0]["id"] . "' AND password IS NULL AND name IS NULL ";
		// 	$newUser = DB::select(DB::raw($newQuerry));
		// 	$newUser = ArrayHelper::fromObject($newUser);

		// 	// If Is New User Resend SMS CODE
		// 	if(isset($newUser[0]["id"])){
		// 		// Redirection
		// 		return redirect( '/resending/verification/code/' . $request->input('phone') );
		// 	}
		// 	else{
		// 		$userExists = t("User with this phone number already existing.");
		// 		flash($userExists)->error();
		// 		// Redirection
		// 		return redirect()->back();
		// 	}
		// }
		// R.S=============================================

		// New User
		$user = new User();
		$input = $request->only($user->getFillable());
		foreach ($input as $key => $value) {
			$user->{$key} = $value;
		}

		$user->name = 'user_' . substr(($request->input('phone')), -4);

		$request->session()->flash('message', t("Your account has been created."));
		
		$user->country_code   = config('country.code');
		$user->language_code  = config('app.locale');
		$user->password       = Hash::make($request->input('password'));
		$user->phone_hidden   = $request->input('phone_hidden');
		$user->ip_addr        = Ip::get();
		$user->verified_email = 1;
		$user->verified_phone = 1;

		// R.S
		// // Email verification key generation
		// if ($emailVerificationRequired) {
		// 	$user->email_token = md5(microtime() . mt_rand());
		// 	$user->verified_email = 0;
		// }
		
		// // Mobile activation key generation
		// if ($phoneVerificationRequired) {
		// 	$user->phone_token = mt_rand(100000, 999999);
		// 	$user->verified_phone = 0;
		// }

		// $user->phone_token = mt_rand(100000, 999999);
		// $user->verified_phone = 0;
		// R.S

		// Save
		$user->save();

		$updatePosts = 	"UPDATE ". DBTool::rawTable('posts') .
		" SET user_id = '" . $user->id .
		"', contact_name = '" . $user->name .
		"' WHERE phone = '" . $user->phone . "'";

		DB::update(DB::raw($updatePosts));

		// R.S
		// if(!((NotifyController::SendNotification(
		// 		['1' => 'NOFIFY_SMS'],
		// 		['phone' => "$user->phone",
		// 		'message'=>"$this->message $user->phone_token",
		// 		'verify' => 1])
		// ))){
		// 	return redirect()->back();
		// }
		
		// return redirect( '/verification/code/' . $user->phone );
		// R.S
		// ============================================================================

		// From ORIGINAL
		// Message Notification & Redirection
		$request->session()->flash('message', t("Your account has been created."));
		$nextUrl = config('app.locale') . '/register/finish';
		

		// Send Admin Notification Email
		if (config('settings.mail.admin_notification') == 1) {
			try {
				// Get all admin users
				$admins = User::permission(Permission::getStaffPermissions())->get();
				if ($admins->count() > 0) {
					Notification::send($admins, new UserNotification($user));
					/*
					foreach ($admins as $admin) {
						Notification::route('mail', $admin->email)->notify(new UserNotification($user));
					}
					*/
				}
			} catch (\Exception $e) {
				flash($e->getMessage())->error();
			}
		}
		
	
		// Send Verification Link or Code
		if ($emailVerificationRequired || $phoneVerificationRequired) {
			
			// Save the Next URL before verification
			session(['userNextUrl' => $nextUrl]);
						
			// Email
			if ($emailVerificationRequired) {
				// Send Verification Link by Email
				$this->sendVerificationEmail($user);
				
				// Show the Re-send link
				$this->showReSendVerificationEmailLink($user, 'user');
			}

			// Phone
			if ($phoneVerificationRequired) {
					// Send Verification Code by SMS
					$this->sendVerificationSms($user);
				
					// Show the Re-send link
					$this->showReSendVerificationSmsLink($user, 'user');
					
					// Go to Phone Number verification
					$nextUrl = config('app.locale') . '/verify/user/phone/';
			}
			
		// Send Confirmation Email or SMS,
		// When User clicks on the Verification Link or enters the Verification Code.
		// Done in the "app/Observers/UserObserver.php" file.

		} else {

				// Send Confirmation Email or SMS
				if (config('settings.mail.confirmation') == 1) {
					try {
						$user->notify(new UserActivated($user));
					} catch (\Exception $e) {
						flash($e->getMessage())->error();
					}
				}

				// Redirect to the user area If Email or Phone verification is not required
			if (Auth::loginUsingId($user->id)) {
				return redirect()->intended(config('app.locale') . '/account');
			}
		}
		return redirect( $nextUrl);
	}


	// Routes for methods are in /resurces/web.php
	/**
	 *  Check SMS code
	 * R.S
	 */
	public function codeVerification($phone)
	{
		$data = [];

		// References
		$data['countries'] = CountryLocalizationHelper::transAll(CountryLocalization::getCountries());
		$data['genders'] = Gender::trans()->get();
		$data['phone'] = $phone;
		// Meta Tags
		MetaTag::set('title', getMetaTag('title', 'register'));
		MetaTag::set('description', strip_tags(getMetaTag('description', 'register')));
		MetaTag::set('keywords', getMetaTag('keywords', 'register'));
		
		return view('auth.register.indexSecond', $data);
	}

	/**
	 * RESEND SMS CODE 
	 * 
	 * R.S
	 * @return View
	 */
	public function resendCode($phone)
	{	
		$user = new User();
		$user->phone = $phone;
		$user->phone_token = mt_rand(100000, 999999);

		$querry = "UPDATE ". DBTool::rawTable('users') . " SET phone_token = '" . $user->phone_token . "', verified_phone = 0 WHERE phone =  '" . $phone . "'";
		$updated = DB::update(DB::raw($querry));
		$updated = ArrayHelper::fromObject($updated);

		// Send Verification Code by SMS
		// $this->sendVerificationCode($user);

		if(!(NotifyController::SendNotification(
				['1' => 'NOFIFY_SMS'],
				['phone' => "$phone",
				'message'=>"$this->message $user->phone_token",
				'verify' => 1]
		))){
			return redirect()->back();
		}
		$data = [];
		
		// References
		$data['countries'] = CountryLocalizationHelper::transAll(CountryLocalization::getCountries());
		$data['genders'] = Gender::trans()->get();
		$data['phone'] = $phone;
		// Meta Tags
		MetaTag::set('title', getMetaTag('title', 'register'));
		MetaTag::set('description', strip_tags(getMetaTag('description', 'register')));
		MetaTag::set('keywords', getMetaTag('keywords', 'register'));
		
		return view('auth.register.indexSecond', $data);
	}
	
	/**
	 *  Check SMS code
	 * R.S
	 *	@param CodeRequest $request
	 */
	public function verifyCode(CodeRequest $request )
	{
		if(!is_numeric($request->input('code'))){
			$invalidMessage = t("You enter invalid code.");
			flash($invalidMessage)->error();
			return redirect("/register");
		}

		$querry = "SELECT * FROM ". DBTool::rawTable('users') . " WHERE phone_token = '" . $request->input('code') . "' AND phone =  '" . $request->input('phone') . "'";
		$user = DB::select(DB::raw($querry));

		$user = ArrayHelper::fromObject($user);

		//if user enter wrong code
		if(!isset($user[0]['id']) && !isset($user[0]['phone']) ){
			$invalidMessage = t("You enter invalid code.");
			flash($invalidMessage)->error();
			return redirect("/register");
		}
		// SET THE CODE IS VERIFIED
		$querry = "UPDATE ". DBTool::rawTable('users') . " SET verified_phone = 1 WHERE id =  '" . $user[0]['id'] . "'";
		$updated = DB::update(DB::raw($querry));
		$updated = ArrayHelper::fromObject($updated);

		// Redirection
		// return $this->lastRecords($user[0]['phone']);
		return redirect( '/end/registration/' . $user[0]['phone'] );
	}

	/**
	 * Last form for add info about user
	 * R.S	
	 * @return View
	 */
	public function lastRecords( $user = null)
	{
		$data = [];
		
		// References
		$data['countries'] = CountryLocalizationHelper::transAll(CountryLocalization::getCountries());
		$data['genders'] = Gender::trans()->get();

		// Meta Tags
		MetaTag::set('title', getMetaTag('title', 'register'));
		MetaTag::set('description', strip_tags(getMetaTag('description', 'register')));
		MetaTag::set('keywords', getMetaTag('keywords', 'register'));
		if(isset($user)){
			$data['phone'] = $user;
		}

		return view('auth.register.index', $data);
	}
	

	/**
	 * Register a new user account.
	 * R.S.
	 * @param UserRequest $request
	 * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function endRegistration( UserRequest $request )
	{
		$querry = "SELECT * FROM ". DBTool::rawTable('users') . " WHERE phone =  '" . $request->input('phone') . "'";
		$user = DB::select(DB::raw($querry));
		$user = ArrayHelper::fromObject($user);
		$pass = Hash::make($request->input('password'));
		$id = $user[0]['id'];
		$update = 	"UPDATE ". DBTool::rawTable('users') . " SET " . 
					" name = '" . $request->input('name') .
					 "', password = '" . $pass .
					 "' WHERE phone = " . $request->input('phone')
					. " AND id = " . $id ;
		DB::update(DB::raw($update));

		// R.S.
		$updatePosts = 	"UPDATE ". DBTool::rawTable('posts') .
					" SET user_id = '" . $id .
					"', contact_name = '" . $request->input('name') .
					"' WHERE phone = '" . $request->input('phone') . "'";
		DB::update(DB::raw($updatePosts));

		Auth::loginUsingId($id);

		$nextUrl = config('app.locale') . '/register/finish';
		
		// Redirection
		return redirect($nextUrl);
	}
	
	/**
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|View
	 */
	public function finish()
	{
		// Keep Success Message for the page refreshing
		session()->keep(['message']);
		if (!session()->has('message')) {
			return redirect(config('app.locale') . '/');
		}
		
		// Meta Tags
		MetaTag::set('title', session('message'));
		MetaTag::set('description', session('message'));
		
		return view('auth.register.finish');
	}
}
