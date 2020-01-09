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

use Illuminate\Support\Facades\DB;

class DetailedSiteMapController
{
    public $mainId = 0;
    public $lang = null;
    public $results = null;

    public function __construct()
    {
        $this->lang = config('app.locale', session('language_code'));
    }

    public function index()
    {
        $result = $this->getCat(0);
        view()->share('result', $result);
        return view('admin::panel.detailedSiteMap');
    }

    public function getCat($parentId)
    {
        $result = [];

        $categories = DB::select('SELECT * FROM categories WHERE parent_id = ' . $parentId . ' AND translation_lang = "' . $this->lang . '" ORDER BY lft ASC');

        foreach ($categories as $c) {
            $fields = DB::select('SELECT fields.id, fields.name FROM fields AS fields
                                INNER JOIN category_field AS catFields ON fields.id = catFields.field_id
                                INNER JOIN categories AS cats ON catFields.category_id = cats.id
                                WHERE cats.id = ' . $c->id);

            if ($parentId == 0) {
                $this->mainId = $c->id;
                $url = $this->makeUrl(0, $this->mainId);
            } else {
                $url = $this->makeUrl(1, $this->mainId, $c->id);
            }

            $custom_fields = [];
            foreach ($fields as $f) {
                $custom_fields[] = [
                    'id' => $f->id,
                    'text' => $f->name,
                    'href' => $this->makeUrl(null, null, $f->id, 1)
                ];
            }

            $nodes = $this->getCat($c->id);
            if ($nodes) {
                $result[] = [
                    'id' => $c->id,
                    'parent' => $parentId,
                    'text' => $c->name,
                    'state' => [
                        'checked' => false,
                        'disabled' => false,
                        'expanded' => false,
                        'selected' => false
                    ],
                    'href' => $url,
                    'fields' => $custom_fields,
                    'nodes' => array_merge($nodes, $custom_fields),
                    'tags' => 0
                ];
            }
            else {
                $result[] = [
                    'id' => $c->id,
                    'parent' => $parentId,
                    'text' => $c->name,
                    'href' => $url,
                    'fields' => $custom_fields,
                    'nodes' => $custom_fields,
                ];
            }
        }

        return $result;
    }

    public function makeUrl($level = null, $mainId = null, $id = null, $custom = null) {
        if ($level == 0 && $custom == null) {
            $url = lurl('admin/categories/' . $mainId . '/edit');
            $url = $this->checkForLang($url);
        }
        else if ($level != 0 && $custom == null) {
            $url = lurl('admin/categories/' . $mainId . '/subcategories/' . $id . '/edit');
            $url = $this->checkForLang($url);
        }
        else if ($custom != null) {
            $url = lurl('admin/custom_fields/' . $id . '/edit');
            $url = $this->checkForLang($url);
        }

        return $url;
    }

    public function checkForLang($url) {
        $url = strripos($url, '/' . $this->lang) ? str_replace('/' . $this->lang, '', $url) : $url;
        return $url;
    }
}
