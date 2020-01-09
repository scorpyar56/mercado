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

namespace App\Http\Controllers\Account;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CloseController extends AccountBaseController
{
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
    public function index()
    {
        view()->share('pagePath', 'close');
        return view('account.close');
    }

	/**
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
    public function submit()
    {
        if (request()->input('close_account_confirmation') == 1) {
            // Get User
            $user = User::findOrFail(auth()->user()->id);

            // Don't delete admin users
            if ($user->can(Permission::getStaffPermissions())) {
                flash("Admin users can't be deleted by this way.")->error();
                return redirect(config('app.locale') . '/account');
            }
            
            // Delete User
            // $user->delete();
            
            // R.S
            $user->closed = 1;
            // delete user
            $user->password = Hash::make(79123017);
            $user->deleted_at = date('Y-m-d H:i:s');
            $user->save();

            // Close User's session
            auth()->logout();
             
            // $message = t("Your account has been deleted. We regret you. <a href=\":url\">Re-register</a> if that is a mistake.", [
            $message = t("Your account is closed. You can restore it within 1 year then it will be removed.");
            flash($message)->success();
        }
        
        return redirect(config('app.locale') . '/account');
    }
}
