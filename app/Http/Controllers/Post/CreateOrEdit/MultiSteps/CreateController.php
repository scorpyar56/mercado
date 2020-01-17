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

namespace App\Http\Controllers\Post\CreateOrEdit\MultiSteps;

use App\Helpers\ArrayHelper;
use App\Helpers\DBTool;
use App\Helpers\Ip;
use App\Helpers\Localization\Country as CountryLocalization;
use App\Helpers\Localization\Helpers\Country as CountryLocalizationHelper;
use App\Helpers\UrlGen;
use App\Http\Controllers\Account\NotifyController;
use App\Http\Controllers\Auth\Traits\VerificationTrait;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\Post\CreateOrEdit\MultiSteps\Traits\EditTrait;
use App\Http\Controllers\Post\CreateOrEdit\Traits\AutoRegistrationTrait;
use App\Http\Controllers\Post\Traits\CustomFieldTrait;
use App\Http\Requests\PostRequest;
use App\Models\Blacklist;
use App\Models\Category;
use App\Models\City;
use App\Models\Package;
use App\Models\Permission;
use App\Models\Post;
use App\Models\PostType;
use App\Models\Scopes\ReviewedScope;
use App\Models\Scopes\VerifiedScope;
use App\Models\User;
use App\Notifications\PostActivated;
use App\Notifications\PostNotification;
use App\Notifications\PostReviewed;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Torann\LaravelMetaTags\Facades\MetaTag;

class CreateController extends FrontController
{
    use EditTrait, VerificationTrait, CustomFieldTrait, AutoRegistrationTrait;

    public $data;

    /**
     * CreateController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        // Check if guests can post Ads
        if (config('settings.single.guests_can_post_ads') != '1') {
            $this->middleware('auth')->only(['getForm', 'postForm']);
        }

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
        // References
        $data = [];

        // Get Countries
        $data['countries'] = CountryLocalizationHelper::transAll(CountryLocalization::getCountries());
        view()->share('countries', $data['countries']);

        // Get Categories
        $cacheId = 'categories.parentId.0.with.children' . config('app.locale');
        $data['categories'] = Cache::remember($cacheId, $this->cacheExpiration, function () {
            $categories = Category::trans()->where('parent_id', 0)->with([
                'children' => function ($query) {
                    $query->trans();
                },
            ])->orderBy('lft')->get();
            return $categories;
        });
        view()->share('categories', $data['categories']);

        // Get Post Types
        $cacheId = 'postTypes.all.' . config('app.locale');
        $data['postTypes'] = Cache::remember($cacheId, $this->cacheExpiration, function () {
            $postTypes = PostType::trans()->orderBy('lft')->get();
            return $postTypes;
        });
        view()->share('postTypes', $data['postTypes']);

        // Count Packages
        $data['countPackages'] = Package::trans()->applyCurrency()->count();
        view()->share('countPackages', $data['countPackages']);

        // Count Payment Methods
        $data['countPaymentMethods'] = $this->countPaymentMethods;

        // Save common's data
        $this->data = $data;
    }

    /**
     * New Post's Form.
     *
     * @param null $tmpToken
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getForm($tmpToken = null)
    {
        // TMP
        $tmp_sess = session()->all();

        // Check if the form type is 'Single Step Form', and make redirection to it (permanently).
        if (config('settings.single.publication_form_type') == '2') {
            return redirect(lurl('create'), 301)->header('Cache-Control', 'no-store, no-cache, must-revalidate');
        }

        // Check possible Update
        if (!empty($tmpToken)) {
            session()->keep(['message']);
            $tmp_tmpToken = $tmpToken;
            return $this->getUpdateForm($tmpToken);
        } else {
            $tmp_tmpToken = "none";
        }

        // Meta Tags
        MetaTag::set('title', getMetaTag('title', 'create'));
        MetaTag::set('description', strip_tags(getMetaTag('description', 'create')));
        MetaTag::set('keywords', getMetaTag('keywords', 'create'));

        // Create
        return view('post.createOrEdit.multiSteps.create', ['tmpSession'=>$tmp_sess, 'tmpTmpToken'=>$tmp_tmpToken]);
    }

    /**
     * Store a new Post.
     *
     * @param null $tmpToken
     * @param PostRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postForm($tmpToken = null, PostRequest $request)
    {
        // R.S.
        if (Blacklist::where('entry', $request->phone)->first()) {
            return redirect(config('app.locale') . "/unban/{$request->phone}/request");
//                    return array('banPhone', $banPhone);
        }

        // Check possible Update
        if (!empty($tmpToken)) {
            session()->keep(['message']);

            return $this->postUpdateForm($tmpToken, $request);
        }

        // Get the Post's City
        $city = City::find($request->input('city_id', 0));
        if (empty($city)) {
            flash(t("Posting Ads was disabled for this time. Please try later. Thank you."))->error();

            return back()->withInput();
        }

        // Conditions to Verify User's Email or Phone
        if (auth()->check()) {
            $emailVerificationRequired = config('settings.mail.email_verification') == 1 && $request->filled('email') && $request->input('email') != auth()->user()->email;
            $phoneVerificationRequired = config('settings.sms.phone_verification') == 1 && $request->filled('phone') && $request->input('phone') != auth()->user()->phone;
        } else {
            $emailVerificationRequired = config('settings.mail.email_verification') == 1 && $request->filled('email');
            $phoneVerificationRequired = config('settings.sms.phone_verification') == 1 && $request->filled('phone');
        }

        // New Post
        $post = new Post();
        $input = $request->only($post->getFillable());
        foreach ($input as $key => $value) {
            $post->{$key} = $value;
        }

        $post->country_code = config('country.code');
        $post->user_id = (auth()->check()) ? auth()->user()->id : 0;
        $post->negotiable = $request->input('negotiable');
        $post->phone_hidden = $request->input('phone_hidden');
        $post->lat = $city->latitude;
        $post->lon = $city->longitude;
        $post->ip_addr = Ip::get();
        $post->tmp_token = md5(microtime() . mt_rand(100000, 999999));
        $post->verified_email = 1;
        $post->verified_phone = 1;
        $post->reviewed = 1;

        // R.S
        // $post->phone_token = mt_rand(100000, 999999);
        // $post->verified_phone = 0;

        // Email verification key generation
        if ($emailVerificationRequired) {
            $post->email_token = md5(microtime() . mt_rand());
            $post->verified_email = 0;
        }

        // Mobile activation key generation
        if ($phoneVerificationRequired) {
            $post->phone_token = mt_rand(100000, 999999);
            $post->verified_phone = 0;
        }
        // Save
        $post->save();

        // Save ad Id in session (for next steps)
        session(['tmpPostId' => $post->id]);

        // Custom Fields
        $this->createPostFieldsValues($post, $request);

        // Auto-Register the Author
        $user = $this->register($post);

        // The Post's creation message
        if (getSegment(2) == 'create') {
            session()->flash('message', t('Your ad has been created.'));
        }

        //R.S
        // if(User::where('phone',$request->phone)->first() == null){
        // 	$nextStepUrl = config('app.locale') . '/posts/create/' . $post->tmp_token . '/register';
        // }else{
        // Get Next URL
        $nextStepUrl = config('app.locale') . '/posts/create/' . $post->tmp_token . '/photos';

        // }


        // Send Admin Notification Email
        if (config('settings.mail.admin_notification') == 1) {
            try {
                // Get all admin users
                $admins = User::permission(Permission::getStaffPermissions())->get();
                if ($admins->count() > 0) {
                    Notification::send($admins, new PostNotification($post));
                    /*
                    foreach ($admins as $admin) {
                        Notification::route('mail', $admin->email)->notify(new PostNotification($post));
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
            session(['itemNextUrl' => $nextStepUrl]);

            // Email
            if ($emailVerificationRequired) {
                // Send Verification Link by Email
                $this->sendVerificationEmail($post);

                // Show the Re-send link
                $this->showReSendVerificationEmailLink($post, 'post');
            }

            // Phone
            if ($phoneVerificationRequired) {
                // Send Verification Code by SMS
                $this->sendVerificationSms($post);

                // Show the Re-send link
                $this->showReSendVerificationSmsLink($post, 'post');

                // Go to Phone Number verification
                $nextStepUrl = config('app.locale') . '/verify/post/phone/';
            }

            // Send Confirmation Email or SMS,
            // When User clicks on the Verification Link or enters the Verification Code.
            // Done in the "app/Observers/PostObserver.php" file.

        } else {

            // Send Confirmation Email or SMS
            if (config('settings.mail.confirmation') == 1) {
                try {
                    if (config('settings.single.posts_review_activation') == 1) {
                        $post->notify(new PostActivated($post));
                    } else {
                        $post->notify(new PostReviewed($post));
                    }
                } catch (\Exception $e) {
                    flash($e->getMessage())->error();
                }
            }

        }
        // var_dump($nextStepUrl);
        // die();
        // Redirection
        return redirect($nextStepUrl);
    }

    //WORK WITH NEW BLOCK_SCHEMA is not FINISHED

    /**
     * Check if user want to register
     * R.S
     *
     */
    public function wantToRegsiter($tmpToken)
    {
        // Create
        return view('post.createOrEdit.multiSteps.check', ['token' => $tmpToken]);
    }

    /**
     * Redirect if user
     *
     * R.S
     */
    public function refuseToRegisterPostForm($tmpToken)
    {
        // return redirect(config('app.locale') . '/posts/create/' . $tmpToken . '/photos');
        return redirect(config('app.locale') . '/posts/create/' . $tmpToken . '/code/check');
    }

    /**
     * Send user code
     * R.S
     *
     */
    public function codeSend($tmpToken)
    {
        $message = "Confirmation code for your phone number is: ";

        $querry = "SELECT * FROM " . DBTool::rawTable('posts') . " WHERE tmp_token = '" . $tmpToken . "'";
        $post = DB::select(DB::raw($querry));
        $post = ArrayHelper::fromObject($post);
        // var_dump($post[0]['phone_token']);
        // die();

        $message = t("Confirmation code for your phone number is: ");
        if (!((NotifyController::SendNotification(
            ['1' => 'NOFIFY_SMS'],
            ['phone' => "{$post[0]['phone']}",
                'message' => "$message {$post[0]['phone_token']}",
                'verify' => 1])
        ))) {
            return redirect()->back();
        }
        // Redirect co code verification
        return redirect(config('app.locale') . '/posts/create/' . $tmpToken . '/code/check');
        // SELECT * FROM 999_lara.posts where tmp_token="ada79cad2255cc611dd49131b4ae716a"
    }

    /**
     * Send user code
     * R.S
     *
     */
    public function codeCheck($tmpToken)
    {
        var_dump('Code check');
        var_dump($tmpToken);
        die();
    }

    /**
     * Redirect if user
     *
     * R.S
     */
    public function checkCode($tmpToken)
    {

        // return redirect(config('app.locale') . '/posts/create/' . $tmpToken . '/photos');
        return redirect(config('app.locale') . '/posts/create/' . $tmpToken . '/code/check');
    }


    /**
     * Confirmation
     *
     * @param $tmpToken
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function finish($tmpToken)
    {
        // Keep Success Message for the page refreshing
        // session()->keep(['message']);
        // if (!session()->has('message')) {
        // 	return redirect(config('app.locale') . '/');
        // }

        // Clear the steps wizard
        if (session()->has('tmpPostId')) {
            // Get the Post
            $post = Post::withoutGlobalScopes([VerifiedScope::class, ReviewedScope::class])->where('id', session('tmpPostId'))->where('tmp_token', $tmpToken)->first();
            if (empty($post)) {
                abort(404);
            }

            // Apply finish actions
            $post->tmp_token = null;
            $post->save();
            session()->forget('tmpPostId');
        }

        // Redirect to the Post,
        // - If User is logged
        // - Or if Email and Phone verification option is not activated
        if (auth()->check() || (config('settings.mail.email_verification') != 1 && config('settings.sms.phone_verification') != 1)) {
            if (!empty($post)) {
                flash(session('message'))->success();
                return redirect(UrlGen::postUri($post));
            }
        }

        // Meta Tags
        MetaTag::set('title', session('message'));
        MetaTag::set('description', session('message'));

        return view('post.createOrEdit.multiSteps.finish');
    }
}
