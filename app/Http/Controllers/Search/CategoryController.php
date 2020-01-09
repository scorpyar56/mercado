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

use App\Models\Category;
use App\Models\CategoryField;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Torann\LaravelMetaTags\Facades\MetaTag;

class CategoryController extends BaseController
{
    public $isCatSearch = true;

    /**
     * @param $countryCode
     * @param $catSlug
     * @param null $subCatSlug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function index($countryCode, $catSlug, $subCatSlug = null)
    {
        // Check multi-countries site parameters
        if (!config('settings.seo.multi_countries_urls')) {
            $subCatSlug = $catSlug;
            $catSlug = $countryCode;
        }
        $fullPath = $catSlug . (isset($subCatSlug) ? "/" . $subCatSlug : "");

        $this->categoryTree = $this->makeCategoryTreeFromPath($fullPath);

        $allSubs = explode('/', $subCatSlug);
        $subCatSlug = $allSubs[0];

        view()->share('isCatSearch', $this->isCatSearch);

        // Get Category
        $this->cat = $this->categoryTree->first();
        view()->share('cat', $this->cat);

        // Get common Data
        $catName = $this->categoryTree->last()->name;
        $catDescription = $this->categoryTree->last()->description;

        // Get base category URL
        $this->baseCatURL = url('/');
        if (!isset($languageCode) or empty($languageCode)) {
            $languageCode = config('app.locale', session('language_code'));
        }

        if ($languageCode != "en") {
            $this->baseCatURL .= "/" . $languageCode;
        }
        $this->baseCatURL .= "/category";
        $this->baseURL = $this->baseCatURL;

        $this->categoryTree->each(function ($i) {
            $this->baseCatURL .= "/" . $i->slug;
        });
        view()->share('baseCatURL', $this->baseCatURL);


        $this->catChildren = Category::where('parent_id', '=', $this->categoryTree->last()->translation_of)
            ->where('translation_lang', $languageCode)
            ->orderBy('lft', 'asc')
            ->get();

        view()->share('catChildren', $this->catChildren);

        // Check if this is SubCategory Request
        if ($this->categoryTree->count() > 1) {
            $this->isSubCatSearch = true;
            view()->share('isSubCatSearch', $this->isSubCatSearch);

            $this->subCat = $this->categoryTree->last();
            view()->share('subCat', $this->subCat);
        }

        $sidebarCatList = $this->getSubsTree(clone $this->categoryTree, $this->baseURL);
        view()->share('sidebarCatList', $sidebarCatList);

        // Get Custom Fields
        $customFields = CategoryField::newGetFields($this->categoryTree);
        view()->share('customFields', $customFields);

        // Search
        $search = new $this->searchClass();
        $searchedCats = $this->makeRootTree($this->categoryTree->last()->translation_of);
        $data = $search->setNarrowedCategory($searchedCats)->fetch();

        // Get Titles
        $bcTab = $this->getBreadcrumb();
        $htmlTitle = $this->getHtmlTitle();
        view()->share('bcTab', $bcTab);
        view()->share('htmlTitle', $htmlTitle);

        // SEO
        $title = $this->getTitle();
        if (isset($catDescription) && !empty($catDescription)) {
            $description = Str::limit($catDescription, 200);
        } else {
            $description = Str::limit(t('Free ads :category in :location', [
                    'category' => $catName,
                    'location' => config('country.name')
                ]) . '. ' . t('Looking for a product or service') . ' - ' . config('country.name'), 200);
        }

        // Meta Tags
        MetaTag::set('title', $title);
        MetaTag::set('description', $description);

        // Open Graph
        $this->og->title($title)->description($description)->type('website');
        if ($data['count']->get('all') > 0) {
            if ($this->og->has('image')) {
                $this->og->forget('image')->forget('image:width')->forget('image:height');
            }
        }
        view()->share('og', $this->og);

        // Translation vars
        view()->share('uriPathCatSlug', $catSlug);
        if (!empty($subCatSlug)) {
            view()->share('uriPathSubCatSlug', $subCatSlug);
        }

        return view('search.serp', $data);
    }
}
