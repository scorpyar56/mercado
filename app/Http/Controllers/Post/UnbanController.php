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

namespace App\Http\Controllers\Post;

use App\Helpers\UrlGen;
use App\Http\Requests\UnbanRequest;
use App\Models\Permission;
use App\Models\ReportType;
use App\Http\Controllers\FrontController;
use App\Models\User;
use App\Notifications\UnbanSent;
use Illuminate\Support\Facades\Notification;
use App\Models\Blacklist;

// R.S.
class UnbanController extends FrontController
{  
    /**
    * ReportController constructor.
    */
   public function __construct()
   {
       parent::__construct();
       
       // From Laravel 5.3.4 or above
       $this->middleware(function ($request, $next) {
           $this->commonQueries();
           
           return $next($request);
       });
   
       $this->middleware('demo.restriction')->only(['sendReport']);
   }
   
   /**
    * Common Queries
    */
   public function commonQueries()
   {
       // Get Report abuse types
       $reportTypes = ReportType::trans()->get();
       view()->share('reportTypes', $reportTypes);
   }
   
   public function showRequestForm($phone)
   {
        // R.S
        $data = [];

        if(!Blacklist::where('entry',$phone)->first()){
            return redirect(config('app.locale') . "/");
        }
        // Get Post
        $data['post'] = ($phone);
        return view('post.unbanrequest', $data);
         
   }
   
   /**
    * @param $phone
    * @param UnbanRequest $request
    * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
    */
   public function sendRequest($phone, UnbanRequest $request)
   {
    // R.S
    $notification = "User with phone number $phone want to be deleted from banned users.";

       // Send Unban Request to admin
       try {
           if (config('settings.app.email')) {

            Notification::route('mail', config('settings.app.email'))->notify(new UnbanSent(config('settings.app.email') , $notification));
            //    Notification::route('mail', 'solihodjaev.work@gmail.com')->notify(new UnbanSent(config('settings.app.email') , $notification));
           } else {
               $admins = User::permission(Permission::getStaffPermissions())->get();
               if ($admins->count() > 0) {
                   Notification::send($admins, new UnbanSent(config('settings.app.email') , $notification));
                   /*
                   foreach ($admins as $admin) {
                       Notification::route('mail', $admin->email)->notify(new ReportSent($request, $report));
                   }
                   */
               }
           }
           
           flash(t('Your unban request has sent successfully to us. Thank you!'))->success();
       } catch (\Exception $e) {
           flash($e->getMessage())->error();
                  
           return back()->withInput();
       }
       
       return redirect(UrlGen::postUri($request));
   }
   
}
