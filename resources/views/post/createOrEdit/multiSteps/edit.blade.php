{{--
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
--}}
@extends('layouts.master')

@section('wizard')
	@include('post.createOrEdit.multiSteps.inc.wizard')
@endsection

<?php
// Category
if ($post->category) {
    if ($post->category->parent_id == 0) {
        $postCatParentId = $post->category->id;
    } else {
	    $postCatParentId = $post->category->parent_id;
	}
} else {
	$postCatParentId = 0;
}
?>
@section('content')
	@include('common.spacer')
	<div class="main-container">
		<div class="container">
			<div class="row">

				@include('post.inc.notification')

				<div class="col-md-8 page-content ">
					<div class="inner-box category-content category-content-dif">
						<!-- <h2 class="title-2 title-2-dif">
{{--							<strong> <i class="icon-docs"></i> {{ t('Update My Ad') }}</strong> -&nbsp;--}}
							<strong>{{ t('Update My Ad') }} -</strong>&nbsp;
							<a href="{{ \App\Helpers\UrlGen::post($post) }}" class="tooltipHere" title="" data-placement="top"
								data-toggle="tooltip"
								data-original-title="{!! $post->title !!}">
								{!! \Illuminate\Support\Str::limit($post->title, 45) !!}
							</a>
						</h2> -->

						<div class="row">
							<div class="col-xl-12">

								<form class="form-horizontal" id="postForm" method="POST" action="{{ url()->current() }}" enctype="multipart/form-data">
									{!! csrf_field() !!}
									<input name="_method" type="hidden" value="PUT">
									<input type="hidden" name="post_id" value="{{ $post->id }}">
									<fieldset>

										<div class="col-xl-12 col-xl-12-dif">
											<div class="ads-header edit-header">
												<h3>
													<strong>{{ t('Ads information') }} 
														<a href="{{ \App\Helpers\UrlGen::post($post) }}" class="tooltipHere" title="" data-placement="top"
															data-toggle="tooltip"
															data-original-title="{!! $post->title !!}">
															{!! \Illuminate\Support\Str::limit($post->title, 45) !!}
														</a>
													</strong>
												</h3>
											</div>

											<div class="inner-ads-box">
												<!-- parent_id -->
												<?php $parentIdError = (isset($errors) and $errors->has('category_id')) ? ' is-invalid' : ''; ?>
												<div class="form-group row required">
													<label class="col-md-2 col-form-label{{ $parentIdError }}">{{ t('Category') }} <sup>*</sup></label>
													<div class="col-md-9">
														<select name="parent_id" id="parentId" class="form-control selecter{{ $parentIdError }}">
															<option value="0" data-type=""
																	@if (old('parent_id', $postCatParentId)=='' or old('parent_id', $postCatParentId)==0)
																		selected="selected"
																	@endif
															>
																{{ t('Select a category') }}
															</option>
															@foreach ($categories as $cat)
																<option value="{{ $cat->tid }}" data-type="{{ $cat->type }}"
																		@if (old('parent_id', $postCatParentId)==$cat->tid)
																			selected="selected"
																		@endif
																>
																	{{ $cat->name }}
																</option>
															@endforeach
														</select>
														<?php
														$parentType = null;
														if (isset($post->category) && isset($post->category->type)) {
															$parentType = $post->category->type;
															if (isset($post->category->parent) && isset($post->category->parent->type) && !empty($post->category->parent->type)) {
																$parentType = $post->category->parent->type;
															}
														}
														?>
														<input type="hidden" name="parent_type" id="parentType" value="{{ old('parent_type', $parentType) }}">
													</div>
												</div>

												<!-- category_id -->
												<?php $categoryIdError = (isset($errors) and $errors->has('category_id')) ? ' is-invalid' : ''; ?>
												<!--<div id="subCatBloc" class="form-group row required">
													<label class="col-md-2 col-form-label{{ $categoryIdError }}">{{ t('Sub-Category') }} <sup>*</sup></label>
													<div class="col-md-9">
														<select name="category_id" id="categoryId" class="form-control selecter{{ $categoryIdError }}">
															<option value="0" data-type=""
																	@if (old('category_id', $post->category_id)=='' or old('category_id', $post->category_id)==0)
																		selected="selected"
																	@endif
															>
																{{ t('Select a sub-category') }}
															</option>
														</select>
														<input type="hidden" name="category_type" id="categoryType" value="{{ old('category_type') }}">
													</div>
												</div> -->

												<!-- category_id -->
												<?php
												$categoryIdError = (isset($errors) and $errors->has('category_id')) ? ' is-invalid' : '';
												?>
												<div id="subCatBloc2"
													 class="ns-form-group
													 required"
													 style="display: none;">
													<label
															class="ns-form-label">{{ t('Sub-Category') }} <sup>*</sup></label>
													<div>
														<input name="category_id" id="categoryId" type="hidden"/>
														<select
																id="subCatTree2"></select>
													</div>
												</div>

												<!-- post_type_id -->
												<!--
		{{--										<?php $postTypeIdError = (isset($errors) and $errors->has('post_type_id')) ? ' is-invalid' : ''; ?>--}}
		{{--										<div id="postTypeBloc" class="form-group row required">--}}
		{{--											<label class="col-md-2 col-form-label">{{ t('Type') }} <sup>*</sup></label>--}}
		{{--											<div class="col-md-9">--}}
		{{--												@foreach ($postTypes as $postType)--}}
		{{--													<div class="form-check form-check-inline">--}}
		{{--														<input name="post_type_id"--}}
		{{--															   id="postTypeId-{{ $postType->tid }}" value="{{ $postType->tid }}"--}}
		{{--															   type="radio"--}}
		{{--															   class="form-check-input{{ $postTypeIdError }}"--}}
		{{--																{{ (old('post_type_id', $post->post_type_id)==$postType->tid) ? 'checked="checked"' : '' }}--}}
		{{--														>--}}
		{{--														<label class="form-check-label" for="postTypeId-{{ $postType->tid }}">--}}
		{{--															{{ $postType->name }}--}}
		{{--														</label>--}}
		{{--													</div>--}}
		{{--												@endforeach--}}
		{{--											</div>--}}
		{{--										</div>--}}
		-->
											<!-- NS: все объявления Private. Post_type_id скрыт -->
												<input name="post_type_id" id="postTypeId-1" value="1" type="hidden">

												<!-- title -->
												<?php $titleError = (isset($errors) and $errors->has('title')) ? ' is-invalid' : ''; ?>
												<div class="form-group row required">
													<label class="col-md-2 col-form-label" for="title">{{ t('Title') }} <sup>*</sup></label>
													<div class="col-md-9">
														<input id="title" name="title" placeholder="{{ t('Ad title') }}" class="form-control input-md{{ $titleError }}"
															   type="text" value="{{ old('title', $post->title) }}">
														<small id="" class="form-text text-muted">{{ t('A great title from 10 to 55 characters.') }}</small>
													</div>
												</div>

												<!-- description -->
												<?php $descriptionError = (isset($errors) and $errors->has('description')) ? ' is-invalid' : ''; ?>
												<div class="form-group row required">
													<?php
														$descriptionErrorLabel = '';
														$descriptionColClass = 'col-md-9';
														if (config('settings.single.simditor_wysiwyg') or config('settings.single.ckeditor_wysiwyg')) {
															$descriptionColClass = 'col-md-12';
															$descriptionErrorLabel = $descriptionError;
														}
														$ckeditorClass = (config('settings.single.ckeditor_wysiwyg')) ? ' ckeditor' : '';
													?>
													<label class="col-md-2 col-form-label{{ $descriptionErrorLabel }}" for="description">
														{{ t('Description') }} <sup>*</sup>
													</label>
													<div class="{{ $descriptionColClass }}">
														<?php $ckeditorClass = (config('settings.single.ckeditor_wysiwyg')) ? ' ckeditor' : ''; ?>
														<textarea
																class="form-control{{ $ckeditorClass . $descriptionError }}"
																id="description"
																name="description"
																rows="10"
														>{{ old('description', $post->description) }}</textarea>
														<small id="" class="form-text text-muted">{{ t('Describe what makes your ad unique from 5 to 3000 characters.') }}</small>
													</div>
												</div>

												<!-- customFields -->
												<div id="customFields"></div>

												<!-- price -->
												<?php $priceError = (isset($errors) and $errors->has('price')) ? ' is-invalid' : ''; ?>
												<div id="priceBloc" class="form-group row required">
													<label class="col-md-2 col-form-label" for="price">{{ t('Price') }}</label>
													<div class="input-group col-md-9">
														<div class="input-group-prepend">
															<span class="input-group-text">{!! config('currency')['symbol'] !!}</span>
														</div>

														<input id="price"
															   name="price"
															   class="form-control{{ $priceError }}"
															   placeholder="{{ t('e.i. 15000') }}"
															   type="number"
															   value="{{ \App\Helpers\Number::toFloat(old('price', $post->price)) }}"
														>

														<div class="input-group-append">
															<!-- <span class="input-group-text">
																<input id="negotiable" name="negotiable" type="checkbox"
																	   value="1" {{ (old('negotiable', $post->negotiable)=='1') ? 'checked="checked"' : '' }}>
																&nbsp;<small>{{ t('Negotiable') }}</small>
															</span> -->
															<div class="input-group-check flex-align">
																<div class="cntr">
																	<label for="negotiable" class="label-cbx">
																	<input id="negotiable" name="negotiable" type="checkbox" class="invisible" value="1" {{ (old('negotiable', $post->negotiable)=='1') ? 'checked="checked"' : '' }}>
																	<div class="checkbox">
																		<svg width="14px" height="14px" viewBox="0 0 14 14">
																		<path d="M3,1 L17,1 L17,1 C18.1045695,1 19,1.8954305 19,3 L19,17 L19,17 C19,18.1045695 18.1045695,19 17,19 L3,19 L3,19 C1.8954305,19 1,18.1045695 1,17 L1,3 L1,3 C1,1.8954305 1.8954305,1 3,1 Z"></path>
																		<polyline points="4 8 6 10 11 5"></polyline>
																		</svg>
																	</div>
																	{{ t('Negotiable') }}
																	</label>
																</div>
															</div>
														</div>
													</div>
												</div>

												<!-- country_code -->
												<input id="countryCode" name="country_code" type="hidden" value="{{ !empty($post->country_code) ? $post->country_code : config('country.code') }}">

												@if (config('country.admin_field_active') == 1 and in_array(config('country.admin_type'), ['1', '2']))
													<!-- admin_code -->
													<?php $adminCodeError = (isset($errors) and $errors->has('admin_code')) ? ' is-invalid' : ''; ?>
													<div id="locationBox" class="form-group row required">
														<label class="col-md-2 col-form-label{{ $adminCodeError }}" for="admin_code">{{ t('Location') }} <sup>*</sup></label>
														<div class="col-md-9">
															<select id="adminCode" name="admin_code" class="form-control sselecter{{ $adminCodeError }}">
																<option value="0" {{ (!old('admin_code') or old('admin_code')==0) ? 'selected="selected"' : '' }}>
																	{{ t('Select your Location') }}
																</option>
															</select>
														</div>
													</div>
												@endif

												<!-- city_id -->
												<?php $cityIdError = (isset($errors) and $errors->has('city_id')) ? ' is-invalid' : ''; ?>
												<div id="cityBox" class="form-group row required">
													<label class="col-md-2 col-form-label{{ $cityIdError }}" for="city_id">{{ t('City') }} <sup>*</sup></label>
													<div class="col-md-9">
														<select id="cityId" name="city_id" class="form-control sselecter{{ $cityIdError }}">
															<option value="0" {{ (!old('city_id') or old('city_id')==0) ? 'selected="selected"' : '' }}>
																{{ t('Select a city') }}
															</option>
														</select>
													</div>
												</div>

												<!-- tags -->
												<!-- <?php $tagsError = (isset($errors) and $errors->has('tags')) ? ' is-invalid' : ''; ?> -->
												<!-- <div class="form-group row">
													<label class="col-md-2 col-form-label" for="tags">{{ t('Tags') }}</label>
													<div class="col-md-9">
														<input id="tags"
															   name="tags"
															   placeholder="{{ t('Tags') }}"
															   class="form-control input-md{{ $tagsError }}"
															   type="text"
															   value="{{ old('tags', $post->tags) }}"
														>
														<small id="" class="form-text text-muted">{{ t('Enter the tags separated by commas.') }}</small>
													</div>
												</div> -->
											</div>
										</div>

										<div class="col-xl-12 col-xl-12-dif">
											<div class="ads-header seller-header ">
												<h3>
													<strong>{{ t('Seller information') }}</strong>
												</h3>
											</div>

											<div class="inner-ads-box">
												<!-- contact_name -->
												<!-- @if ( old('contact_name', $post->contact_name) == auth()->user()->name )
												<?php $contactNameError = (isset($errors) and $errors->has('contact_name')) ? ' is-invalid' : ''; ?>
												<div class="form-group row required">
													<label class="col-md-2 col-form-label" for="contact_name">{{ t('Your name') }} <sup>*</sup></label>
													<div class="col-md-9">
														<input id="contact_name" name="contact_name" placeholder="{{ t('Your name') }}"
															   class="form-control input-md{{ $contactNameError }}" type="text"
															   value="{{ old('contact_name', $post->contact_name) }}">
													</div>
												</div> @endif -->

												<?php $contactNameError = (isset($errors) and $errors->has('contact_name')) ? ' is-invalid' : ''; ?>
														<input id="contact_name" name="contact_name" placeholder="{{ t('Your name') }}"
															   class="form-control input-md{{ $contactNameError }}" type="hidden"
															   value="{{ auth()->user()->name }}">

												<!-- email -->
												<!-- <?php $emailError = (isset($errors) and $errors->has('email')) ? ' is-invalid' : ''; ?> -->
												<!-- <div class="form-group row required">
													<label class="col-md-2 col-form-label" for="email"> {{ t('Email') }} </label>
													<div class="input-group col-md-9">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="icon-mail"></i></span>
														</div>

														<input id="email" name="email" class="form-control{{ $emailError }}"
															   placeholder="{{ t('Email') }}" type="text"
															   value="{{ old('email', $post->email) }}">
													</div>
												</div> -->

												<!-- phone -->
												<?php $phoneError = (isset($errors) and $errors->has('phone')) ? ' is-invalid' : ''; ?>
												<div class="form-group row required">
													<label class="col-md-2 col-form-label" for="phone">{{ t('Phone Number') }}</label>
													<div class="input-group col-md-9 edit-post-phone">

														<input id="phone" name="phone"
															   placeholder="{{ t('Phone Number') }}" class="form-control input-md{{ $phoneError }}"
															   type="text" value="{{ phoneFormat(old('phone', $post->phone), $post->country_code) }}"
														>

														<div class="input-group-append">
															<!-- <span class="input-group-text">
																<input name="phone_hidden" id="phoneHidden" type="checkbox"
																	   value="1" {{ (old('phone_hidden', $post->phone_hidden)=='1') ? 'checked="checked"' : '' }}>
																&nbsp;<small>{{ t('Hide') }}</small>
															</span> -->
															<div class="input-group-check check-phone flex-align">
																	<div class="cntr">
																		<label for="phoneHidden" class="label-cbx">
																		<input id="phoneHidden" name="phone_hidden" type="checkbox" class="invisible" value="1" {{ (old('phone_hidden')=='1') ? 'checked="checked"' : '' }}>
																		<div class="checkbox">
																			<svg width="14px" height="14px" viewBox="0 0 14 14">
																			<path d="M3,1 L17,1 L17,1 C18.1045695,1 19,1.8954305 19,3 L19,17 L19,17 C19,18.1045695 18.1045695,19 17,19 L3,19 L3,19 C1.8954305,19 1,18.1045695 1,17 L1,3 L1,3 C1,1.8954305 1.8954305,1 3,1 Z"></path>
																			<polyline points="4 8 6 10 11 5"></polyline>
																			</svg>
																		</div>
																		{{ t('Hide') }}
																		</label>
																	</div>
																</div>
														</div>
													</div>
												</div>

												<!-- Button  -->
												<div class="form-group row pt-3 post-submit">
													<div class="col-md-9 text-center text-center-dif">
														<a href="{{ \App\Helpers\UrlGen::post($post) }}" class="btn btn-default btn-lg btn-dif post-backBtn"> {{ t('Back') }}</a>
														<button id="nextStepBtn" class="btn btn-primary btn-lg btn-dif"> {{ t('Update') }} </button>
													</div>
												</div>
											</div>
										</div>

									</fieldset>
								</form>

							</div>
						</div>
					</div>
				</div>
				<!-- /.page-content -->

				<div class="col-md-4 reg-sidebar" style="margin-top:20px;">
					<!-- <div class="reg-sidebar-inner text-center"> -->
						<!-- @if (getSegment(2) != 'create' && auth()->check())
							@if (auth()->user()->id == $post->user_id)
								<div class="card sidebar-card panel-contact-seller">
									чч
									<div class="card-header">{{ t('Author\'s Actions') }}</div>
									<div class="card-content user-info">
										<div class="card-body text-center">
											<a href="{{ \App\Helpers\UrlGen::post($post) }}" class="btn btn-default btn-block">
												<i class="fa fa-chevron-left"></i> {{ t('Return to the Ad') }}
											</a>
											<a href="{{ lurl('posts/' . $post->id . '/photos') }}" class="btn btn-default btn-block">
												<i class="unir-edit"></i> {{ t('Update Photos') }}
											</a>
											@if (isset($countPackages) and isset($countPaymentMethods) and $countPackages > 0 and $countPaymentMethods > 0)
												<a href="{{ lurl('posts/' . $post->id . '/payment') }}" class="btn btn-success btn-block">
													<i class="icon-ok-circled2"></i> {{ t('Make It Premium') }}
												</a>
											@endif
										</div>
									</div>
								</div>
							@endif
						@endif -->

						<!-- N.M. -->
						<!-- <div style="margin-top:30px;" class="col-md-4 page-content"> -->
							<div class="help-block sticky-top">
								<h3 class="title-3 py-3">{{ t('Help links') }}</h3>
								<div class="text-content text-left from-wysiwyg">
									<h4><a href="{{ lurl('page/about')}}">{{ t('About Mercado.gratis') }}</a></h4>
									<h4><a href="{{ lurl('page/account')}}">{{ t('Managing Account & Ads') }}</a></h4>
									<h4><a href="{{ lurl('page/safety')}}">{{ t('Safety Tips') }}</a></h4>
									<h4><a href="{{ lurl('page/fastsell')}}">{{ t('How to sell fast') }}</a></h4>
									<h4><a href="{{ lurl('page/report')}}">{{ t('Report a suspicious user or add') }}</a></h4>
									<h4><a href="{{ lurl('page/fraudvictim')}}">{{ t('If you become a victim of fraud') }}</a></h4>
									<h4><a href="{{ lurl('page/terms-conditions')}}">{{ t('Terms and Conditions') }}</a></h4>
									<h4><a href="{{ lurl('contact')}}">{{ t('Contact Us') }}</a></h4>
								</div>
							</div>
                		<!-- </div> -->

						<!-- <div class="card sidebar-card">
							<div class="card-header">{{ t('How to sell quickly?') }}</div>
							<div class="card-content">
								<div class="card-body text-left">
									<ul class="list-check">
										<li> {{ t('Use a brief title and description of the item') }} </li>
										<li> {{ t('Make sure you post in the correct category') }}</li>
										<li> {{ t('Add nice photos to your ad') }}</li>
										<li> {{ t('Put a reasonable price') }}</li>
										<li> {{ t('Check the item before publish') }}</li>
									</ul>
								</div>
							</div>
						</div> -->

					<!-- </div> -->
				</div>
			</div>
		</div>
	</div>
@endsection

@section('after_styles')
	@include('layouts.inc.tools.wysiwyg.css')
    <link type="text/css" href="{{ url('assets/css/bootstrap-treeview.min.css') }}" rel="stylesheet">
@endsection

@section('after_scripts')
    @include('layouts.inc.tools.wysiwyg.js')

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.payment/1.2.3/jquery.payment.min.js"></script>
	@if (file_exists(public_path() . '/assets/plugins/forms/validation/localization/messages_'.config('app.locale').'.min.js'))
		<script src="{{ url('assets/plugins/forms/validation/localization/messages_'.config('app.locale').'.min.js') }}" type="text/javascript"></script>
	@endif

	<script>
		/* Translation */
		var lang = {
			'select': {
				'category': "{{ t('Select a category') }}",
				'subCategory': "{{ t('Select a sub-category') }}",
				'country': "{{ t('Select a country') }}",
				'admin': "{{ t('Select a location') }}",
				'city': "{{ t('Select a city') }}"
			},
			'price': "{{ t('Price') }}",
			'salary': "{{ t('Salary') }}",
            'nextStepBtnLabel': {
                'next': "{{ t('Next') }}",
                'submit': "{{ t('Update') }}"
            }
		};

		var stepParam = 0;

		/* Categories */
		var category = {{ old('parent_id', (int)$postCatParentId) }};
		var categoryType = '{{ old('parent_type', $parentType) }}';
		if (categoryType == '') {
			var selectedCat = $('select[name=parent_id]').find('option:selected');
			categoryType = selectedCat.data('type');
		}
		var subCategory = {{ old('category_id', (int)$post->category_id) }};
		console.log (subCategory);

		/* Custom Fields */
		var errors = '{!! addslashes($errors->toJson()) !!}';
		var oldInput = '{!! addslashes(collect(session()->getOldInput('cf'))->toJson()) !!}';
		var postId = '{{ $post->id }}';

		/* Locations */
		var countryCode = '{{ old('country_code', !empty($post->country_code) ? $post->country_code : config('country.code')) }}';
        var adminType = '{{ config('country.admin_type', 0) }}';
        var selectedAdminCode = '{{ old('admin_code', ((isset($admin) and !empty($admin)) ? $admin->code : 0)) }}';
        var cityId = '{{ old('city_id', (int)$post->city_id) }}';

		/* Packages */
        var packageIsEnabled = false;
		@if (isset($packages) and isset($paymentMethods) and $packages->count() > 0 and $paymentMethods->count() > 0)
            packageIsEnabled = true;
		@endif
	</script>
	<script>
		$(document).ready(function() {
			$('#tags').tagit({
				fieldName: 'tags',
				placeholderText: '{{ t('add a tag') }}',
				caseSensitive: false,
				allowDuplicates: false,
				allowSpaces: false,
				tagLimit: {{ (int)config('settings.single.tags_limit', 15) }},
				singleFieldDelimiter: ','
			});
		});
	</script>

	<script src="{{ url('assets/js/app/d.select.category.js') . vTime() }}"></script>
	<script src="{{ url('assets/js/app/d.select.location.js') . vTime() }}"></script>
    <script src="{{ url('assets/js/bootstrap-treeview.min.js') }}"></script>
@endsection
