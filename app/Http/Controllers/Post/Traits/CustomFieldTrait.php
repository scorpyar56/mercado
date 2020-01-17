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

namespace App\Http\Controllers\Post\Traits;


use App\Models\CategoryField;
use App\Models\Field;
use App\Models\Post;
use App\Models\PostValue;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;

trait CustomFieldTrait
{
	/**
	 * Get Category's Custom Fields Buffer
	 *
	 * @param $catNestedIds
	 * @param $languageCode
	 * @param null $errors
	 * @param null $oldInput
	 * @param null $postId
	 * @return string
	 */
	public function getCategoryFieldsBuffer($catNestedIds, $languageCode = null, $errors = null, $oldInput = null, $postId = null)
	{
		// $html = '';

		// $fields = CategoryField::getFields($catNestedIds, $postId, $languageCode);
		// var_dump($fields);die();
//		if (count($fields) > 0) {
//			$view = View::make('post.inc.fields', [
//				'fields'       => $fields,
//				'languageCode' => $languageCode,
//				'errors'       => $errors,
//				'oldInput'     => $oldInput,
//			]);
//			$html = $view->render();
//		}

                // TODO: N.Stanschi changes in custom fields list:
                $tree = CategoryField::getCategoryParents($catNestedIds->id);


		if (empty($postId)) {
                    $cacheId = 'customFields.parentId.' . $catNestedIds->parentId . '.' . $catNestedIds->id . '.' . $languageCode;

                    $newFields = Cache::remember($cacheId, new \DateInterval('PT1M'), function () use ($tree, $postId, $languageCode) {
                        $newFields = new Collection([]);
                        for ($i = 0; $i < count($tree) - 1; $i++) {
                            $catNestedIds = (object)[
                                'parentId' => $tree[$i],
                                'id' => $tree[$i + 1],
                            ];
                            /** @var Collection $temp */
                            $iid = $tree[$i];
                            $temp = CategoryField::getFields($catNestedIds, $postId, $languageCode);
                            $temp = $temp->each(function ($item, $key) use ($iid){
                                $t = $item;
                                $t->levelcat = $iid;
                                return $t;
                            });
                            $newFields = $newFields->concat($temp);
                        }
                        //$newFields = $newFields->unique();

                        return $newFields;
                    });
                } else {
                    $newFields = new Collection([]);
                    for ($i = 0; $i < count($tree) - 1; $i++) {
                        $catNestedIds = (object)[
                            'parentId' => $tree[$i],
                            'id' => $tree[$i + 1],
                        ];
                        /** @var Collection $temp */
                        $iid = $tree[$i];
                        $temp = CategoryField::getFields($catNestedIds, $postId, $languageCode);
                        $temp = $temp->each(function ($item, $key) use ($iid){
                            $t = $item;
                            $t->levelcat = $iid;
                            return $t;
                        });
                        $newFields = $newFields->concat($temp);
                    }
                    //$newFields = $newFields->unique();
                }



                if (count($newFields) > 0) {
                    $view = View::make('post.inc.fields', [
                        'fields'       => $newFields,
                        'languageCode' => $languageCode,
                        'errors'       => $errors,
                        'oldInput'     => $oldInput,
					]);
					// $view .= "<script>
					// 			$('.select2-selection.select2-selection--single').on('click', function () {
					// 				console.log('here');
					// 				if ($(this).find('.unir-rarrow2.icon').hasClass('rotate-arrow')) {
					// 					$('.select2-selection.select2-selection--single>.unir-rarrow2.icon').removeClass('rotate-arrow');
					// 					// $(this).find('.unir-rarrow2.icon').removeClass('rotate-arrow');
					// 				} else {
					// 					$('.select2-selection.select2-selection--single>.unir-rarrow2.icon').removeClass('rotate-arrow');
					// 					$(this).find('.unir-rarrow2.icon').addClass('rotate-arrow');
					// 				}
					// 			});
					// 		</script>";
                    $html = $view->render();
                }
				// TODO: END Changes

		return $html;
	}

	/**
	 * Create & Update for Custom Fields
	 *
	 * @param Post $post
	 * @param Request $request
	 * @return array
	 */
	public function createPostFieldsValues(Post $post, Request $request)
	{
		$postValues = [];

		if (empty($post)) {
			return $postValues;
		}

		// Delete all old PostValue entries, if exist
		$oldPostValues = PostValue::with(['field'])->where('post_id', $post->id)->get();
		if ($oldPostValues->count() > 0) {
			foreach ($oldPostValues as $oldPostValue) {
				if ($oldPostValue->field->type == 'file') {
					if ($request->hasFile('cf.' . $oldPostValue->field->tid)) {
						$oldPostValue->delete();
					}
				} else {
					$oldPostValue->delete();
				}
			}
		}

		// Get Category nested IDs
		$catNestedIds = (object)[
			'parentId' => $request->input('parent_id'),
			'id'       => $request->input('category_id'),
		];

		// Get Category's Fields details

		$fields = CategoryField::getFields($catNestedIds);


		// TODO; N.Stanschi
        $tree = CategoryField::getCategoryParents($catNestedIds->id);

        $newFields = new Collection([]);
        for ($i=0;$i<count($tree)-1;$i++) {
            $catNestedIds = (object)[
                'parentId' => $tree[$i],
                'id'       => $tree[$i+1],
            ];
            /** @var Collection $temp */
            $temp = CategoryField::getFields($catNestedIds);
            $newFields = $newFields->merge($temp);
        }
        $fields = $newFields->unique();
        // TODO: End


		if ($fields->count() > 0) {
			foreach ($fields as $field) {
				if ($field->type == 'file') {
					if ($request->hasFile('cf.' . $field->tid)) {
						// Get file's destination path
						$destinationPath = 'files/' . strtolower($post->country_code) . '/' . $post->id;

						// Get the file
						$file = $request->file('cf.' . $field->tid);

						// Check if the file is valid
						if (!$file->isValid()) {
							continue;
						}

						// Get filename & file path
						$filename = $file->getClientOriginalName();
						$extension = $file->getClientOriginalExtension();
						$newFilename = md5($filename . time()) . '.' . $extension;
						$filePath = $destinationPath . '/' . $newFilename;

						$postValueInfo = [
							'post_id'  => $post->id,
							'field_id' => $field->tid,
							'value'    => $filePath,
						];

						$newPostValue = new PostValue($postValueInfo);
						$newPostValue->save();

						$this->disk->put($newPostValue->value, File::get($file->getrealpath()));

						$postValues[$newPostValue->id] = $newPostValue;
					}
				} else {
					if ($request->filled('cf.' . $field->tid)) {
						// Get the input
						$input = $request->input('cf.' . $field->tid);

						// TODO: NS: Поскольку чекбоксы теперь селекты, нужно
                        // переиграть чуть этот момент:
                        if (in_array($field->type, ['checkbox_multiple', 'checkbox_multiple_or', 'radio'])) {
                            $postValueInfo = [
                                'post_id'  => $post->id,
                                'field_id' => $field->tid,
                                'option_id' => $input,
                                'value'    => $input,
                            ];

                            $newPostValue = new PostValue($postValueInfo);
                            $newPostValue->save();
                            $postValues[$newPostValue->id] = $newPostValue;
                            $postValues[$newPostValue->id][$input] =
                                $newPostValue;
                        } elseif (is_array($input)) {
							foreach ($input as $optionId => $optionValue) {
								$postValueInfo = [
									'post_id'   => $post->id,
									'field_id'  => $field->tid,
									'option_id' => $optionId,
									'value'     => $optionValue,
								];

								$newPostValue = new PostValue($postValueInfo);
								$newPostValue->save();
								$postValues[$newPostValue->id][$optionId] = $newPostValue;
							}
						} else {
							$postValueInfo = [
								'post_id'  => $post->id,
								'field_id' => $field->tid,
								'value'    => $input,
							];

							$newPostValue = new PostValue($postValueInfo);
							$newPostValue->save();
							$postValues[$newPostValue->id] = $newPostValue;
						}
					}
				}
			}
		}

		return $postValues;
	}

	/**
	 * Get Post's Custom Fields Values
	 *
	 * @param $catNestedIds
	 * @param $postId
	 * @return \Illuminate\Support\Collection
	 */
	public function getPostFieldsValues($catNestedIds, $postId)
	{
		// Get the Post's Custom Fields by its Parent Category
		$customFields = CategoryField::getFields($catNestedIds, $postId);

		// Get the Post's Custom Fields that have a value
		$postValue = [];
		if ($customFields->count() > 0) {
			foreach ($customFields as $key => $field) {
				if (!empty($field->default)) {
					$postValue[$key] = $field;
				}
			}
		}

		return collect($postValue);
	}
}
