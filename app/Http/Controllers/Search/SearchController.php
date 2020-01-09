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

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Search\Traits\PreSearchTrait;
use App\Models\Category;
use App\Models\CategoryField;
use App\Models\City;
use App\Models\Setting;
use Torann\LaravelMetaTags\Facades\MetaTag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class SearchController extends BaseController
{
	use PreSearchTrait;

	public $isIndexSearch = true;

	public $cat = null;
	public $subCat = null;
	protected $city = null;
	protected $admin = null;

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index()
	{
		view()->share('isIndexSearch', $this->isIndexSearch);

		// Pre-Search
		if (request()->filled('c')) {
            $this->isCatSearch = true;
            view()->share('isCatSearch', $this->isCatSearch);

            if (request()->filled('sc')) {
                $this->isSubCatSearch = true;
                view()->share('isSubCatSearch', $this->isSubCatSearch);
                $this->categoryTree = $this->makeCategoryTreeFromId(request()->get('sc'));
                $this->cat = $this->categoryTree->first();
                $this->subCat = $this->categoryTree->last();
                view()->share('subCat', $this->subCat);
			} else {
                $this->categoryTree = $this->makeCategoryTreeFromId(request()->get('c'));
                $this->cat = $this->categoryTree->first();
			}
            view()->share('cat', $this->cat);
			// Get Custom Fields
			$customFields = CategoryField::newGetFields($this->categoryTree);
			view()->share('customFields', $customFields);
		}

		if (request()->filled('l') || request()->filled('location')) {
			$city = $this->getCity(request()->get('l'), request()->get('location'));
		}
		if (request()->filled('r') && !request()->filled('l')) {
			$admin = $this->getAdmin(request()->get('r'));
		}

		// Pre-Search values
		$preSearch = [
			'city'  => (isset($city) && !empty($city)) ? $city : null,
			'admin' => (isset($admin) && !empty($admin)) ? $admin : null,
		];

		// Search
		$search = new $this->searchClass($preSearch);
		if (!empty($this->categoryTree)) {
            $searchedCats = $this->makeRootTree($this->categoryTree->last()->translation_of);
            $data = $search->setNarrowedCategory($searchedCats)->fetch();

            // Get base category URL
            $this->baseCatURL = url('/');
            if (!isset($languageCode) or empty($languageCode)) {
                $languageCode = config('app.locale', session('language_code'));
            }
            if ($languageCode != "en") {
                $this->baseCatURL .= "/" . $languageCode;
            }
            $this->baseCatURL .= "/search";
            $this->baseURL = $this->baseCatURL;

            view()->share('baseCatURL', $this->baseCatURL);

            $sidebarCatList = $this->getSubsTreeSearch(clone $this->categoryTree, $this->baseURL);
            view()->share('sidebarCatList', $sidebarCatList);

            $SubCatId = request()->get('sc');
            view()->share('SubCatId', $SubCatId);

        } else {
            $data = $search->fetch();
        }

		// Export Search Result
		view()->share('count', $data['count']);
		view()->share('paginator', $data['paginator']);

		// Get Titles
		$title = $this->getTitle();
		$this->getBreadcrumbSearch();
		$this->getHtmlTitle();

		// Meta Tags
		MetaTag::set('title', $title);
		MetaTag::set('description', $title);

		return view('search.serp');
	}
}
