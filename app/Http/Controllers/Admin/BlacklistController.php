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

namespace App\Http\Controllers\Admin;

use App\Models\Blacklist;
use App\Models\Post;
use App\Models\User;
use Larapen\Admin\app\Http\Controllers\PanelController;
use App\Http\Requests\Admin\BlacklistRequest as StoreRequest;
use App\Http\Requests\Admin\BlacklistRequest as UpdateRequest;
use Prologue\Alerts\Facades\Alert;

class BlacklistController extends PanelController
{
	public function setup()
	{
		/*
		|--------------------------------------------------------------------------
		| BASIC CRUD INFORMATION
		|--------------------------------------------------------------------------
		*/
		$this->xPanel->setModel('App\Models\Blacklist');
		$this->xPanel->setRoute(admin_uri('blacklists'));
		$this->xPanel->setEntityNameStrings(trans('admin::messages.blacklist'), trans('admin::messages.blacklists'));
		$this->xPanel->orderBy('id', 'DESC');
		
		$this->xPanel->addButtonFromModelFunction('top', 'bulk_delete_btn', 'bulkDeleteBtn', 'end');
		
		/*
		|--------------------------------------------------------------------------
		| COLUMNS AND FIELDS
		|--------------------------------------------------------------------------
		*/
		// COLUMNS
		$this->xPanel->addColumn([
			'name'  => 'id',
			'label' => '',
			'type'  => 'checkbox',
			'orderable' => false,
		]);

		$this->xPanel->addColumn([
			'name'  => 'entry',
			'label' => trans("admin::messages.Phone"),
		]);
		
		// FIELDS
		$this->xPanel->addField([
			'name'  => 'type',
			'label' => trans('admin::messages.Type'),
			'type'  => 'enum',
		]);
		$this->xPanel->addField([
			'name'       => 'entry',
			'label'      => trans('admin::messages.Entry'),
			'type'       => 'text',
			'attributes' => [
				'placeholder' => trans('admin::messages.Entry'),
			],
		]);
		$entity = $this->xPanel->getModel()->find(request()->segment(3));
		if (!empty($entity)) {

			if (!empty($entity->entry)) {
				$btnUrl = admin_url('blacklists/') . "/" . $entity->id . "/unban";
				
				$btnText = trans("admin::messages.unban_the_user");
				$tooltip = 'data-button-type="delete"';
				
				$btnLink = '<a id="deleteBtn" href="' . $btnUrl . '" class="btn btn-danger"' . $tooltip . '>' . $btnText . '</a>';
				$this->xPanel->addField([
					'name'              => 'unban_button',
					'type'              => 'custom_html',
					'value'             => $btnLink,
					'wrapperAttributes' => [
						'style' => 'text-align:center;',
					],
				], 'update');
			}
		}
	}
	
	public function store(StoreRequest $request)
	{
		return parent::storeCrud();
	}
	
	public function update(UpdateRequest $request)
	{
		return parent::updateCrud();
	}
	
	/**
	 * Ban user by email address (from link)
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function banUserByPhone()
	{
		//R.S

		// Get email address
		$phone = request()->get('phone');
	


		// Get previous URL
		$previousUrl = url()->previous();
		// Exceptions
		if (empty($phone) || isDemo($previousUrl)) {
			if (isDemo($previousUrl)) {
				$message = t('demo_mode_message');
			} else {
				$message = trans("admin::messages.no_action_is_performed");
			}
			if (isFromAdminPanel($previousUrl)) {
				Alert::info($message)->flash();
			} else {
				flash($message)->info();
			}
			
			return redirect()->back();
		}

		// Check the phone has been banned
		$banned = Blacklist::where('type', 'email')->where('entry', $phone)->first();

		// Check the phone has been banned with +
		if( (empty($banned)) ){
			if(substr($phone, 0 ,1) != "+"){
				$phone = "+" . $phone;

			}
			$banned = Blacklist::where('type', 'email')->where('entry', $phone)->first();
		}
		
		// R.S.
		//If does not exist admin with that number
		if( User::where('is_admin', 1)->first()->phone !== $phone ){

			if (!empty($banned) ) {
				// Delete the banned user related to the phone address
				$user = User::where('phone', $banned->entry)->get();
				

				// Add the phone address to the blacklist

				// if (!empty($user)) {
				// 	$user->delete();
				// }
				
				// Delete the banned user's posts related to the phone address
				$posts = Post::where('phone', $banned->entry)->get();

				if ($posts->count() > 0) {
					foreach ($posts as $post) {
						// $post->delete();
						// $post->reviewed = 0;
					}
				}
			} 
			else {

				// Add the phone address to the blacklist
				$banned = new Blacklist();
				$banned->type = 'email';
				$banned->entry = $phone;
				$banned->save();
			}
			
			$message = trans("admin::messages.phone_address_banned_successfully", ['phone' => $phone]);
			if (isFromAdminPanel($previousUrl)) {
				Alert::success($message)->flash();
			} else {
				flash($message)->success();
			}
			
			// Get next URL
			$nextUrl = '/';
			if (isFromAdminPanel($previousUrl)) {
				$tmp = preg_split('#\/[0-9]+\/edit#ui', $previousUrl);
				$nextUrl = isset($tmp[0]) ? $tmp[0] : $previousUrl;
			}
			
			return redirect($nextUrl)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
			//end test
		}
		//R.S.
		if (isFromAdminPanel($previousUrl)) {
			Alert::error("You can't ban Admin.")->flash();
		} else {
			flash("You can't ban Admin.")->error();
		}
		return redirect()->back();
	}

		/**
	 * Ban user by email address (from link)
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delBannedPhone($id)
	{

		$banned = Blacklist::where('type', 'email')->where('id', $id)->first();

		// Get previous URL
		$previousUrl = url()->previous();

		if(	!empty($banned)){
			$phone = $banned->entry;

			Blacklist::where('type', 'email')->where('id', $id)->delete();

			$message = trans("admin::messages.phone_address_unbanned_successfully", ['phone' => $phone]);
			
			if (isFromAdminPanel($previousUrl)) {
				Alert::success($message)->flash();
			} else {
				flash($message)->success();
			}
			return redirect(admin_url('blacklists/'));
		}
		else{
			$message = trans("admin::messages.phone_address_unbanned_error");
			if (isFromAdminPanel($previousUrl)) {
				Alert::success($message)->flash();
			} else {
				flash($message)->success();
			}
			return redirect(admin_url('blacklists/'));
		}
	}
}
