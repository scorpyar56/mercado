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

use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\Router;
use Illuminate\Config\Repository;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

// R.S.
class CodeRequest extends Request
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		if (in_array($this->method(), ['POST', 'CREATE'])) {
			return true;
		} else {
			return auth()->check();
		}
	}
	
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @param \Illuminate\Routing\Router $router
	 * @param \Illuminate\Filesystem\Filesystem $files
	 * @param \Illuminate\Config\Repository $config
	 * @return array
	 */
	public function rules(Router $router, Filesystem $files, Repository $config)
	{
		$rules = [];
		
		// CREATE
		if (in_array($this->method(), ['POST', 'CREATE'])) {
			$rules = $this->storeRules($router, $files, $config);
		}
		
		// UPDATE
		if (in_array($this->method(), ['PUT', 'PATCH', 'UPDATE'])) {
			$rules = $this->updateRules($router, $files, $config);
		}
		
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
	
	/**
	 *  R.S.
	 * @param $router
	 * @param $files
	 * @param $config
	 * @return array
	 */
	private function storeRules($router, $files, $config)
	{
		$rules = [
			'phone'        => ['max:20'],	
			'code'        => ['max:6,min:5']
		];

		// reCAPTCHA
		$rules = $this->recaptchaRules($rules);
		return $rules;
	}
	
}
