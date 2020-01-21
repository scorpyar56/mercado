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

namespace App\Http\Requests;

use App\Rules\BetweenRule;
use App\Rules\BlacklistDomainRule;
use App\Rules\BlacklistEmailRule;
use App\Rules\EmailRule;

class ContactRequest extends Request
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$rules = [
			'first_name' => ['required', new BetweenRule(2, 100)],
			'phone'      => ['required', 'max:20'],
			'email'      => ['required', 'email', new EmailRule(), new BlacklistEmailRule(), new BlacklistDomainRule()],
			'message'    => ['required', new BetweenRule(5, 500)],
		];

		// R.S
		if (isEnabledField('phone')) {
			$countryCode = $this->input('country_code', config('country.code'));
			if ($countryCode == 'UK') {
				$countryCode = 'GB';
			}
			$rules['phone'][] = 'phone:' . $countryCode;
		}

		if (isEnabledField('file')) {
			$rules['file'] = [
				'mimes:' . getUploadFileTypes('file'),
				'min:' . (int)config('settings.upload.min_file_size', 0),
				'max:' . (int)config('settings.upload.max_file_size', 12000),
			];
		}

		// reCAPTCHA
		$rules = $this->recaptchaRules($rules);
		
		return $rules;
	}
	
	/**
	 * @return array
	 */
	public function messages()
	{
		$messages = [];
		
		return $messages;
	}
}
