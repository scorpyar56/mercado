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

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

// R.S.

class UnbanSent extends Notification implements ShouldQueue
{
	use Queueable;

	protected $request;
	protected $route;
	
	public function __construct( $request)
	{
		$this->request = $request;
	}
	
	public function via($notifiable)
	{
		return ['mail'];
	}
	
	public function toMail($notifiable)
	{	

		// R.S.
		return (new MailMessage)
			->replyTo($this->request->email, trans('mail.post_unban_request'))
			->subject(trans('mail.post_unban_request', [
				'appName'     => config('app.name')
			]))
			->line($this->request->message)
			->line(trans('mail.Unban Request'));
	}
}
