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

class ForgotPasswordRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            // 'login' => ['required'],
            'phone' =>['required']
        ];

        		// Phone
		// if (config('settings.sms.phone_verification') == 1) {
			if ($this->filled('phone')) {
				$countryCode = $this->input('country_code', config('country.code'));
				if ($countryCode == 'UK') {
					$countryCode = 'GB';
				}
				$rules['phone'][] = 'phone:' . $countryCode;
			}
		// }

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
