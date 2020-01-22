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
	<div class="container">
		<div class="row">
			@include('post.inc.notification')
		</div>
	</div>
	@if( !auth()->check() )
		@include('layouts.alert_warning')
	@endif
	@include('post.createOrEdit.multiSteps.inc.wizard')
@endsection

@section('content')
	@include('common.spacer')
	<div class="main-container">
		<div class="container">
			<div class="row">
				<div class="col-md-8 page-content top-card">
					<div class="inner-box category-content category-content-dif">
{{--						<h2 class="title-2 title-2-dif">--}}
{{--							<strong><i class="icon-docs"></i> {{ t('Post Free Ads') }}</strong>--}}
{{--							<strong>{{ t('Post Free Ads') }}</strong>--}}
{{--						</h2>--}}
						<div class="row">
							<div class="col-xl-12">

								<form class="form-horizontal" id="postForm" method="POST" action="/posts/create" enctype="multipart/form-data">
									{!! csrf_field() !!}
									<fieldset>

										<div class="col-xl-12 col-xl-12-dif">
											<!-- <div class="ads-header">
												<h3>
													<strong>{{ t('Ads information') }}</strong>
												</h3>
											</div> -->

											<div class="inner-ads-box">
												<!-- parent_id -->
												<?php $parentIdError = (isset($errors) and $errors->has('category_id')) ? ' is-invalid' : ''; ?>
												<div class="ns-form-group required">
													<label
															class="ns-form-label{{ $parentIdError }}">{{ t('Category') }} <sup>*</sup></label>
													<div>
														<select name="parent_id" id="parentId" class="form-control selecter{{ $parentIdError }}">
															<option value="0" data-type=""
																	@if (old('parent_id')=='' or old('parent_id')==0)
																		selected="selected"
																	@endif
															> {{ t('Select a category') }} </option>
															@foreach ($categories as $cat)
																<option value="{{ $cat->tid }}" data-type="{{ $cat->type }}"
																		@if (old('parent_id')==$cat->tid)
																			selected="selected"
																		@endif
																> {{ $cat->name }} </option>
															@endforeach
														</select>
														<input type="hidden" name="parent_type" id="parentType" value="{{ old('parent_type') }}">
													</div>
												</div>

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
		{{--												<div class="form-check form-check-inline pt-2">--}}
		{{--													<input name="post_type_id"--}}
		{{--														   id="postTypeId-{{ $postType->tid }}"--}}
		{{--														   value="{{ $postType->tid }}"--}}
		{{--														   type="radio"--}}
		{{--														   class="form-check-input{{ $postTypeIdError }}" {{ (old('post_type_id')==$postType->tid) ? 'checked="checked"' : '' }}--}}
		{{--													>--}}
		{{--													<label class="form-check-label" for="postTypeId-{{ $postType->tid }}">--}}
		{{--														{{ $postType->name }}--}}
		{{--													</label>--}}
		{{--												</div>--}}
		{{--												@endforeach--}}
		{{--											</div>--}}
		{{--										</div> --}}
												-->
												<!-- NS: все объявления Private. Post_type_id скрыт -->
												<input name="post_type_id" id="postTypeId-1" value="1" type="hidden">

												<!-- title -->
												<?php $titleError = (isset($errors) and $errors->has('title')) ? ' is-invalid' : ''; ?>
												<div class="ns-form-group required">
													<label
															class="ns-form-label" for="title">{{ t('Title') }} <sup>*</sup></label>
													<div>
														<input id="title" name="title" placeholder="{{ t('Ad title') }}" class="form-control input-md{{ $titleError }}"
															   type="text" value="{{ old('title') }}">
														<small id="input-feedback" class="form-text text-muted"></small>
													</div>
												
												</div>

												<!-- description -->
												<?php $descriptionError = (isset($errors) and $errors->has('description')) ? ' is-invalid' : ''; ?>
												<div class="ns-form-group required">
													<?php
														$descriptionErrorLabel = '';
														$descriptionColClass = '';
														if (config('settings.single.simditor_wysiwyg') or config('settings.single.ckeditor_wysiwyg')) {
															$descriptionColClass = '';
															$descriptionErrorLabel = $descriptionError;
														}
														$ckeditorClass = (config('settings.single.ckeditor_wysiwyg')) ? ' ckeditor' : '';
													?>
													<label
															class="ns-form-label{{ $descriptionErrorLabel }}" for="description">
														{{ t('Description') }} <sup>*</sup>
													</label>
													<div class="{{ $descriptionColClass }}">
														<textarea class="form-control{{ $ckeditorClass . $descriptionError }}"
																  id="description"
																  name="description"
																  rows="5"
														>{{ old('description') }}</textarea>
														<small id="textarea-feedback" class="form-text text-muted">{{ t('Describe what makes your ad unique from 5 to 6000 characters.') }}</small>
													</div>
												</div>

												<!-- customFields -->
												<div id="customFields"></div>

												<!-- price -->
												<?php $priceError = (isset($errors) and $errors->has('price')) ? ' is-invalid' : ''; ?>
												<div id="priceBloc"
													 class="ns-form-group">
													<label
															class="ns-form-label" for="price">{{ t('Price') }}</label>
													<div class="input-group">
														<div class="input-group-prepend">
															<span class="input-group-text">{!! config('currency')['symbol'] !!}</span>
														</div>

														<input id="price"
															   name="price"
															   class="form-control{{ $priceError }}"
															   placeholder="{{ t('e.i. 15000') }}"
															   type="number" value="{{ old('price') }}"
															   onkeydown="return checkOnlyDigits(this,event)"
														>

														<div class="input-group-append">
															<!-- <span class="input-group-text input-group-text-dif">
																<input id="negotiable" name="negotiable" type="checkbox"
																	   value="1" {{ (old('negotiable')=='1') ? 'checked="checked"' : '' }}>&nbsp;<small>{{ t('Negotiable') }}</small>
															</span> -->
															<div class="input-group-check flex-align">
																<div class="cntr">
																	<label for="negotiable" class="label-cbx">
																	<input id="negotiable" name="negotiable" type="checkbox" class="invisible" value="1" {{ (old('negotiable')=='1') ? 'checked="checked"' : '' }}>
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
												<?php $countryCodeError = (isset($errors) and $errors->has('country_code')) ? ' is-invalid' : ''; ?>
												@if (empty(config('country.code')))
													<div class="ns-form-group
													required">
														<label
																class="ns-form-label{{ $countryCodeError }}" for="country_code">{{ t('Your Country') }} <sup>*</sup></label>
														<div>
															<select id="countryCode" name="country_code" class="form-control sselecter{{ $countryCodeError }}">
																<option value="0" {{ (!old('country_code') or old('country_code')==0) ? 'selected="selected"' : '' }}> {{ t('Select a country') }} </option>
																@foreach ($countries as $item)
																	<option value="{{ $item->get('code') }}" {{ (old('country_code', (!empty(config('ipCountry.code'))) ? config('ipCountry.code') : 0)==$item->get('code')) ? 'selected="selected"' : '' }}>{{ $item->get('name') }}</option>
																@endforeach
															</select>
														</div>
													</div>
												@else
													<input id="countryCode" name="country_code" type="hidden" value="{{ config('country.code') }}">
												@endif

												<?php
												/*
												@if (\Illuminate\Support\Facades\Schema::hasColumn('posts', 'address'))
												<!-- address -->
												<div class="form-group required <?php echo ($errors->has('address')) ? ' is-invalid' : ''; ?>">
													<label class="col-md-2 control-label" for="title">{{ t('Address') }} </label>
													<div class="col-md-9">
														<input id="address" name="address" placeholder="{{ t('Address') }}" class="form-control input-md"
															   type="text" value="{{ old('address') }}">
														<span class="help-block">{{ t('Fill an address to display on Google Maps.') }} </span>
													</div>
												</div>
												@endif
												*/
												?>

												@if (config('country.admin_field_active') == 1 and in_array(config('country.admin_type'), ['1', '2']))
													<!-- admin_code -->
													<?php $adminCodeError = (isset($errors) and $errors->has('admin_code')) ? ' is-invalid' : ''; ?>
													<div id="locationBox"
														 class="ns-form-group
														 required">
														<label
																class="ns-form-label{{ $adminCodeError }}" for="admin_code">{{ t('Location') }} <sup>*</sup></label>
														<div>
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
		{{--										<div id="cityBox" class="form-group row required">--}}
		{{--											<label class="col-md-2 col-form-label{{ $cityIdError }}" for="city_id">{{ t('City') }} <sup>*</sup></label>--}}
		{{--											<div class="col-md-9">--}}
		{{--												<select id="cityId" name="city_id" class="form-control sselecter{{ $cityIdError }}">--}}
		{{--													<option value="0" {{ (!old('city_id') or old('city_id')==0) ? 'selected="selected"' : '' }}>--}}
		{{--														{{ t('Select a city') }}--}}
		{{--													</option>--}}
		{{--												</select>--}}
		{{--											</div>--}}
		{{--										</div>--}}

												{{-- E.K. --}}
												<?php
												$cities = DB::select('SELECT id, `name` FROM cities');
												?>

												<div id="locationBox"
													 class="ns-form-group
													 required">
													<label
															class="ns-form-label{{ $cityIdError }}" for="city_id">{{ t('City') }} <sup>*</sup></label>
													<div class="search-col locationicon">
														<select name="city_id" id="location_id" class="form-control selecter{{ $parentIdError }}">
															<option value="" {{ (!old('city_id') || old('city_id')==0) ? 'selected="selected"' : '' }}> {{ t('Select a city') }} </option>
															@foreach ($cities as $city)
																<option value="{{ $city->id }}" {{ (old('city_id')==$city->id) ? 'selected="selected"' : '' }}> {{ $city->name }} </option>
															@endforeach
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
															   value="{{ old('tags') }}"
														>
														<small id="" class="form-text text-muted">{{ t('Enter the tags separated by commas.') }}</small>
													</div>
												</div> -->
											</div>
										</div>

										<div class="col-xl-12 col-xl-12-dif">
											<div class="ads-header seller-header">
												<h3>
													<strong>{{ t('Seller information') }}</strong>
												</h3>
											</div>

											<div class="inner-ads-box">
												<!-- contact_name -->
												<?php $contactNameError = (isset($errors) and $errors->has('contact_name')) ? ' is-invalid' : '';?>
												@if (auth()->check())
													<input id="contact_name" name="contact_name" type="hidden" value="{{ auth()->user()->name }}">
												@else
													<div class="col-md-2 col-sm-3 name">
																<label for="contact_name">{{ t('Your name') }} <sup>*</sup></label>
													</div>
													<div class="form-group row required owner-contact-name">
														<div class="col-md-9 col-sm-9">
															<input id="contact_name" name="contact_name" placeholder="{{ t('Your name') }}"
																   class="form-control input-md{{ $contactNameError }}" type="text" value="{{ old('contact_name') }}">
														</div>
													</div>
												@endif



												<?php
												if (auth()->check()) {
													$formPhone = (auth()->user()->country_code == config('country.code')) ? auth()->user()->phone : '';
												} else {
													$formPhone = '';
												}
												?>
											<!-- phone -->
												<?php
												$phoneError = (isset($errors) and $errors->has('phone')) ? ' is-invalid' : '';
												$editable = auth()->check() ? 'readonly' : '';
												?>
												<!-- <div class="form-group row required owner-phone"> -->
													<div class="col-md-2 col-sm-3 phone">
														<label class="col-form-label name" for="phone">{{ t('Phone Number') }}</label>
													</div>
													<div class="form-group row required owner-phone">
														<div class="col-md-9 col-sm-9">

															<!-- start test -->
														<!-- <div class="input-group-prepend">
																	<span id="phoneCountry" class="input-group-text">{!! getPhoneIcon(config('country.code')) !!}</span>
																</div> -->
															<!-- end test -->

															<input id="phone" name="phone"
																placeholder="{{ t('Phone Number') }}"
																class="form-control input-md{{ $phoneError }}" type="text"
																value="{{ phoneFormat(old('phone', $formPhone), old('country', config('country.code'))) }}"
																	{{ $editable }}
															>
															@if (auth()->check())
																<div class="input-group-append">
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
															@endif
														</div>
													</div>
												<!-- </div> -->

												@if (!auth()->check())
													@if (in_array(config('settings.single.auto_registration'), [1, 2]))
													<!-- auto_registration -->
														@if (config('settings.single.auto_registration') == 1)
															<?php $autoRegistrationError = (isset($errors) and $errors->has('auto_registration')) ? ' is-invalid' : ''; ?>
															<div class="form-group row required">
																<label class="col-md-2 col-form-label"></label>
																<div class="col-md-9">
																	<div class="form-check">
																		<input name="auto_registration" id="auto_registration"
																			   class="form-check-input{{ $autoRegistrationError }}"
																			   value="1"
																			   type="checkbox"
																			   checked="checked"
																		>

																		<label class="form-check-label" for="auto_registration">
																			{!! t('I want to register by submitting this ad.') !!}
																		</label>
																	</div>
																	<small id="" class="form-text text-muted">{{ t('You will receive your authentication information by email.') }}</small>
																	<div style="clear:both"></div>
																</div>
															</div>
														@else
															<input type="hidden" name="auto_registration" id="auto_registration" value="1">
														@endif
													@endif
												@endif

												@include('layouts.inc.tools.recaptcha', ['colLeft' => 'col-md-2', 'colRight' => 'col-md-9'])

											<!-- term -->
												<?php $termError = (isset($errors) and $errors->has('term')) ? ' is-invalid' : ''; ?>
												<div class="form-group row required terms-conditions">
													<label class="col-md-2 col-form-label{{ $termError }}"></label>
													<div class="col-md-9 cntr">
														<label class="checkbox mb-0 label-cbx" id="term" for="terms">
															@if (!auth()->check())
																<input id="terms" name="terms" type="checkbox" class="invisible" value="0">
																<div class="checkbox">
																	<svg width="14px" height="14px" viewBox="0 0 14 14">
																		<path d="M3,1 L17,1 L17,1 C18.1045695,1 19,1.8954305 19,3 L19,17 L19,17 C19,18.1045695 18.1045695,19 17,19 L3,19 L3,19 C1.8954305,19 1,18.1045695 1,17 L1,3 L1,3 C1,1.8954305 1.8954305,1 3,1 Z"></path>
																		<polyline points="4 8 6 10 11 5"></polyline>
																	</svg>
																</div>
																<script>
																	$('#terms').click(function () {
																		if ($(this).is(':checked') == false) {
																			$('#nextStepBtn').attr('disabled', 'disabled');
																		} else {
																			$('#nextStepBtn').removeAttr('disabled');
																		}
																	});
																</script>
															@else
																<input id="terms" name="terms" type="checkbox" class="invisible" value="1" checked="checked">
															@endif
															{!! t('I have read and agree to the <a :attributes>Terms of Use</a>', ['attributes' => getUrlPageByType('terms')]) !!}
														</label>
													</div>
												</div>

												<!-- Button  -->
												<div class="form-group row pt-3 post-submit" >
													<div class="col-md-9 text-center">
													@if (auth()->check())
														<button id="nextStepBtn" class="btn btn-primary btn-lg btn-dif btn-green" > {{ t('Submit') }} </button>
													@else
														<button id="nextStepBtn" class="btn btn-primary btn-lg btn-dif btn-green" disabled="disabled"> {{ t('Submit') }} </button>
													@endif
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
				<!-- N.M. Help Links -->
				<div style="margin-top:30px;" class="col-md-4 page-content">
					<div class="help-block sticky-top">
						<h3 class="title-3 py-3">{{ t('Help links') }}</h3>
						<div class="text-content text-left from-wysiwyg">
							<h4><a href="{{ lurl('page/terms-of-use')}}">{{ t('Terms of Use') }}</a></h4>
							<h4><a href="{{ lurl('page/privacy-policy')}}">{{ t('Privacy Policy') }}</a></h4>
							<h4><a href="{{ lurl('page/posting-rules')}}">{{ t('Posting Rules') }}</a></h4>
							<h4><a href="{{ lurl('page/tips')}}">{{ t('Tips for Users') }}</a></h4>
							<h4><a href="{{ lurl('page/faq')}}">{{ t('FAQ') }}</a></h4>
							<h4><a href="{{ lurl('sitemap')}}">{{ t('Sitemap') }}</a></h4>
							<h4><a href="{{ lurl('contact')}}">{{ t('Contact Us') }}</a></h4> 
						</div>
					</div>
                </div>
				<!-- <div class="col-md-2 reg-sidebar">
					<div class="reg-sidebar-inner text-center">
						<div class="promo-text-box"><i class=" icon-picture fa fa-4x icon-color-1"></i>
							<h3><strong>{{ t('Post Free Ads') }}</strong></h3>
							<p>
								{{ t('Do you have something to sell, to rent, any service to offer or a job offer? Post it at :app_name, its free, local, easy, reliable and super fast!', ['app_name' => config('app.name')]) }}
							</p>
						</div>

						<div class="card sidebar-card">
							<div class="card-header uppercase">
								<small><strong>{{ t('How to sell quickly?') }}</strong></small>
							</div>
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
						</div>
					</div>
				</div> -->
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
			    'next': "{{ t('Publish') }}",
                'submit': "{{ t('Submit') }}"
			}
		};

		/* Categories */
		var category = {{ old('parent_id', 0) }};
		var categoryType = '{{ old('parent_type') }}';
		if (categoryType=='') {
			var selectedCat = $('select[name=parent_id]').find('option:selected');
			categoryType = selectedCat.data('type');
		}
		var subCategory = {{ old('category_id', 0) }};

		/* Custom Fields */
		var errors = '{!! addslashes($errors->toJson()) !!}';
		var oldInput = '{!! addslashes(collect(session()->getOldInput('cf'))->toJson()) !!}';
		var postId = '';

		/* Locations */
        var countryCode = '{{ old('country_code', config('country.code', 0)) }}';
        var adminType = '{{ config('country.admin_type', 0) }}';
        var selectedAdminCode = '{{ old('admin_code', (isset($admin) ? $admin->code : 0)) }}';
        var cityId = '{{ old('city_id', (isset($post) ? $post->city_id : 0)) }}';

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
	<script>
		// $(document).ready(function() {
		// 	$('#postForm').submit(function (event) {
		// 		event.preventDefault();
		// 		var action = $(this).attr('action');
		// 		var data = $(this).serializeArray();
		//
		// 		$.ajax({
		// 			type: 'POST',
		// 			url: siteUrl + action,
		// 			data: data
		// 		}).done(function (e) {
		// 			if(e[0] === "banPhone") {
		// 				$('#unbanModal').attr('action', '/unban/' + e[1].entry + '/request');
		// 				$('#unbanModal>.modal-body>.form-group>.input-group>#phone').attr('value', e[1].entry);
		// 				$('.control-label').append(': ' + e[1].entry);
		// 				$('#unbanRequest').modal();
		// 			} else {
        //                 event.currentTarget.submit();
		// 			}
		// 		})
		// 	})
		// })
	</script>

	<script>
		$(document).ready(function() {
			var text_max = 55;
			$('#input-feedback').html(text_max + "{{ t('characters left') }}");

			$('#title').keyup(function() {
				var text_length = $(this).val().length,
					text_remaining = text_max - text_length;

				if (text_length === 0) {
					$('#input-feedback').html(text_max + "{{ t('characters left') }}");
				} else if (text_length > text_max) {
					$('#input-feedback').html('Too many characters');
				} else {
					$('#input-feedback').html(text_remaining + "{{ t('characters left') }}");
				}
			});
		});
	</script>
	
	<script>
		$(document).ready(function() {
			var textarea_max = 6000;
			$('#textarea-feedback').html(textarea_max + "{{ t('characters left') }}");

			$('.simditor-body').keyup(function() {
				var textarea_length = $(this).children('p').text().length,
					textarea_remaining = textarea_max - textarea_length;

				if (textarea_length === 0) {
					$('#textarea-feedback').html(textarea_max + "{{ t('characters left') }}");
				} else if (textarea_length > textarea_max) {
					$('#textarea-feedback').html('Too many characters');
				} else {
					$('#textarea-feedback').html(textarea_remaining + "{{ t('characters left') }}");
				}
			});
		});
	</script>

	<script src="{{ url('assets/js/app/d.select.category.js') . vTime() }}"></script>
	<script src="{{ url('assets/js/app/d.select.location.js') . vTime() }}"></script>
	<script src="{{ url('assets/js/bootstrap-treeview.min.js') }}"></script>
	<script>
		function checkOnlyDigits(element,event) {
			if (event.keyCode == 69 || event.keyCode == 189||event.keyCode == 109) {
				return false;
			}
		}
	</script>
@endsection
