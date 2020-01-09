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

use App\Helpers\UrlGen;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class UnbanRequestSent extends Notification implements ShouldQueue
{
	use Queueable;
	
	protected $post;
	protected $request;
	
	public function __construct($request)
	{
		// R.S
		$this->request = $request;

	}
	
	public function via($notifiable)
	{
		return ['mail'];
	}
	
	public function toMail($notifiable)
	{
		return (new MailMessage)
			->replyTo($this->request->email, $this->request->email)
			->subject(trans('mail.post_unban_request'))
			->line("<p>This was banned </p> " . $this->request->phone )
			->line(nl2br($this->request->message));
	}
}
