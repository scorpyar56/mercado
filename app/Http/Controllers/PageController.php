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

namespace App\Http\Controllers;

use App\Helpers\ArrayHelper;
use App\Http\Requests\ContactRequest;
use App\Models\City;
use App\Models\Message;
use App\Models\Page;
use App\Models\Permission;
use App\Models\User;
use App\Notifications\FormSent;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Torann\LaravelMetaTags\Facades\MetaTag;
use App\Helpers\Files\Storage\StorageDisk;
use Intervention\Image\Facades\Image;

class PageController extends FrontController
{
	/**
	 * ReportController constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		
		$this->middleware('demo.restriction')->only(['contactPost']);
	}
	
	/**
	 * @param $slug
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index($slug)
	{
		// Get the Page
		$page = Page::where('slug', $slug)->trans()->first();
		if (empty($page)) {
			abort(404);
		}
		view()->share('page', $page);
		view()->share('uriPathPageSlug', $slug);
		
		// Check if an external link is available
		if (!empty($page->external_link)) {
			return headerLocation($page->external_link);
		}
		
		// SEO
		$title = $page->title;
		$description = Str::limit(str_strip($page->content), 200);
		
		// Meta Tags
		MetaTag::set('title', $title);
		MetaTag::set('description', $description);
		
		// Open Graph
		$this->og->title($title)->description($description);
		if (!empty($page->picture)) {
			if ($this->og->has('image')) {
				$this->og->forget('image')->forget('image:width')->forget('image:height');
			}
			$this->og->image(imgUrl($page->picture, 'page'), [
				'width'  => 600,
				'height' => 600,
			]);
		}
		view()->share('og', $this->og);
		
		return view('pages.index');
	}
	
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function contact()
	{
		// Get the Country's largest city for Google Maps
		$city = City::currentCountry()->orderBy('population', 'desc')->first();
		view()->share('city', $city);
		
		// Meta Tags
		MetaTag::set('title', getMetaTag('title', 'contact'));
		MetaTag::set('description', strip_tags(getMetaTag('description', 'contact')));
		MetaTag::set('keywords', getMetaTag('keywords', 'contact'));
		
		return view('pages.contact');
	}
	
	/**
	 * @param ContactRequest $request
	 * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function contactPost(ContactRequest $request)
	{	
		// Store Contact Info
		$contactForm = $request->all();
		if( !is_null($request->input("file")) ){
			$contactForm['file'] =  fileUrl(  $this->setFileAttribute($request->input("file"), $request->input("email")));
		}


		$contactForm['country_code'] = config('country.code');
		$contactForm['country_name'] = config('country.name');
		$contactForm = ArrayHelper::toObject($contactForm);

		// Send Contact Email
		try {
			$admins = User::permission(Permission::getStaffPermissions())->get();

			if ($admins->count() > 0) {
				foreach ($admins as $admin) {
					Notification::route('mail', $admin->email)->notify(new FormSent($contactForm));
				}
			}
			flash(t("Your message has been sent to our moderators. Thank you"))->success();
		} catch (\Exception $e) {
			flash($e->getMessage())->error();
		}
		
		return redirect(config('app.locale') . '/' . trans('routes.contact-us'));
	}

	/**
	 * R.S
	 * Add photo contact us
	 */
	public function setFileAttribute($value, $email)
	{
		$disk = StorageDisk::getDisk();
		$attribute_name = 'photo';
		
		$extension = getUploadedFileExtension($value);
		if (empty($extension)) {
			$extension = 'jpg';
		}

		// Path
		$destination_path = '/' . 'files/' . strtolower(config('country.code')) . '/' . $email ;
		$url =  "/" .strtolower(config('country.code')) . '/' . $email ;
		$filename = md5($value . time()) . '.' . $extension;
		
		// For files
		// pdf, doc, docx, word, rtf, rtx, ppt, pptx, odt, odp, wps
		if(preg_match('/(pdf)|(doc)|(docx)|(word)|(rtf)|(rtx)|(ppt)|(pptx)|(odt)|(odp)|(wps)$/i',$extension) == 1){
				
			$write = $disk->put($destination_path  , $value );
			
			// Save the path to the database
			$this->attributes[$attribute_name] = $write;

			return ($write);
		}
	
		// R.S for photo 

		// If laravel request->file('filename') resource OR base64 was sent, store it in the db
		try {
			if (fileIsUploaded($value)) {
				// // Remove all the current user's photos, by removing his photo directory.
				// $disk->deleteDirectory($destination_path);
				
				// Get file extension
				
				// Image quality
				$imageQuality = config('settings.upload.image_quality', 90);
				
				// Image default sizes
				$width = (int)config('settings.upload.img_resize_width', 1000);
				$height = (int)config('settings.upload.img_resize_height', 1000);
				
				// Other parameters
				$ratio = config('settings.upload.img_resize_ratio', '1');
				$upSize = config('settings.upload.img_resize_upsize', '0');
				
				// Make the image
				if (exifExtIsEnabled()) {
					$image = Image::make($value)->orientate()->resize($width, $height, function ($constraint) use ($ratio, $upSize) {
						if ($ratio == '1') {
							$constraint->aspectRatio();
						}
						if ($upSize == '1') {
							$constraint->upsize();
						}
					})->encode($extension, $imageQuality);
				} else {
					$image = Image::make($value)->resize($width, $height, function ($constraint) use ($ratio, $upSize) {
						if ($ratio == '1') {
							$constraint->aspectRatio();
						}
						if ($upSize == '1') {
							$constraint->upsize();
						}
					})->encode($extension, $imageQuality);
				}
				
				// Generate a filename.
				$filename = md5($value . time()) . '.' . $extension;
				
				// Store the image on disk.
				$disk->put($destination_path . '/' . $filename, $image->stream()->__toString());
				
				// Save the path to the database
				$this->attributes[$attribute_name] = $destination_path . '/' . $filename;
			} else {
				// Retrieve current value without upload a new file.
				if (Str::startsWith($value, config('larapen.core.picture.default'))) {
					$value = null;
				} else {
					if (!Str::startsWith($value, 'avatars/')) {
						$value = $destination_path . last(explode($destination_path, $value));
					}
				}
				$this->attributes[$attribute_name] = $value;
			}
			return ($url . '/' . $filename);

		} catch (\Exception $e) {
			flash($e->getMessage())->error();
			$this->attributes[$attribute_name] = null;
			
			return false;
		}
    }
}
