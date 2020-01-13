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
 * R.S
 */


namespace App\Http\Controllers\Account;

use App\Models\Message;
use App\Models\User;
use App\Helpers\UrlGen;
use Prologue\Alerts\Facades\Alert;


class NotifyController
{
	/**
	 * Sending an notification through the desired method.
	 * R.S
	 * @param array $via 
	 * @param array $params
	 * @param bool $displayFlashMessage
	 * @return bool
	 */
	public static function SendNotification(array $via, array $params, $displayFlashMessage = true)
	{
		// var_dump($via);
		// var_dump($params);
		// die();

		// Send tokens via SMS
		if(in_array('NOFIFY_SMS', $via)){
			// Try To Send SMS
			if(self::sendVerificationCodeSMS($params) && $displayFlashMessage){
				if (isset($params["conversation"])){
					$message = t("Notification to owner is send.");
				}
				elseif(isset($params["verify"])){
					$message = t("An activation code has been sent to you to verify your phone number.");
				}
				elseif(isset($params["reset_password"])){
					$message = t("Code for reset your password has been sent.");
				}
				flash($message)->success();
				return true;// When SMS Is Sended To User
			}
			return false;
		}
	}

	 /**
	 * Send verification via SMS
	 * R.S
	 * @param array $parametres
	 * @return bool
	 */
	public static function sendVerificationCodeSMS(array $parametres )
	{
		// var_dump($parametres);
		// die();

		// ======++Example========
		// http://93.113.112.44:13101/cgi-bin/sendsms?
		// username=unifun_alpha&password=a536346tear4523h42tr5&to=+37378800361&from=Unifun&priority=3&text=Hello
		$gateway = env('SMS_GATEWAY') ; 
		$options = http_build_query(array('username' => 'unifun_alpha',
							'password' => 'a536346tear4523h42tr5',
							// 'to' => "{$parametres['phone']", 
							'to' => "+37379298693",   
							'from' => env('SMS_FROM'),    
							'priority' => '3',  
							'text' => $parametres['message'],    
				));
		// var_dump($options);
		// die();
                
		// $response = file_get_contents( $gateway . $options);
		// 		var_dump($options);
		// 		die();
		// MAKE VERIFICATION
		// if($response != "0: Accepted for delivery"){
				// $problems = t('problems with sending SMS');
				// flash($problems)->error();
				// return false;	
		// }
		session(['verificationSmsSent' => true]);
		
		return true;
	}

	/**
	 * Send message to user
	 * R.S
	 * @param $userId int
	 * @param $itemId int
	 * @param $ownerName string
	 * 
	 *  */
	public static function sendMesageFromAdmin($item, $text)
	{
		$user = User::where('id', $item->user_id)->first();

		// New Message
		$message = new Message();

		$message->post_id = $item->id;
		$message->from_user_id = auth()->user()->id;
		$message->from_name = "Admin";
		// $message->from_email = auth()->user()->email;
		// $message->from_phone = auth()->user()->phone;
		$message->to_user_id = $user->id;
		$message->to_name = $item->contact_name;
		// $message->to_email = $toEmail;
		// $message->to_phone = $toPhone;
		$message->subject = "$item->title : " . $text;
		
		$message->message = $text;
		// Save
		try{

			$message->save();
			Alert::success(t("Your message has sent successfully to :contact_name.", ['contact_name' => $message->to_name ]))->flash();
		}catch(\Exception $e){
			Alert::error($e->getMessage())->flash();
		}	
	} 
}
