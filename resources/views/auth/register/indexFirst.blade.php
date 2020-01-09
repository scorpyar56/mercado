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

@section('content')
	@if (!(isset($paddingTopExists) and $paddingTopExists))
		<div class="h-spacer"></div>
	@endif
	<div class="main-container">
		<div class="container">
			<div class="row">
				
				@if (isset($errors) and $errors->any())
					<div class="col-xl-12">
						<div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<h5><strong>{{ t('Oops ! An error has occurred. Please correct the red fields in the form') }}</strong></h5>
							<ul class="list list-check">
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					</div>
				@endif

				@if (Session::has('flash_notification'))
					<div class="col-xl-12">
						<div class="row">
							<div class="col-xl-12">
								@include('flash::message')
							</div>
						</div>
					</div>
				@endif


				<div class="col-md-8 page-content">
					<div class="inner-box">
						<h2 class="title-2">
							<strong><i class="icon-user-add"></i> {{ t('Create your account, Its free') }}</strong>
						</h2>
						
						<div class="row mt-5">
							<div class="col-xl-12">
								<form id="signupForm" class="form-horizontal" method="POST" action="{{ url()->current() }}">
									{!! csrf_field() !!}
									<fieldset>
										<!-- country_code -->
										@if (empty(config('country.code')))
											<?php $countryCodeError = (isset($errors) and $errors->has('country_code')) ? ' is-invalid' : ''; ?>
											<div class="form-group row required">
												<label class="col-md-4 col-form-label{{ $countryCodeError }}" for="country_code">{{ t('Your Country') }} <sup>*</sup></label>
												<div class="col-md-6">
													<select id="countryCode" name="country_code" class="form-control sselecter{{ $countryCodeError }}">
														<option value="0" {{ (!old('country_code') or old('country_code')==0) ? 'selected="selected"' : '' }}>{{ t('Select') }}</option>
														@foreach ($countries as $code => $item)
															<option value="{{ $code }}" {{ (old('country_code', (!empty(config('ipCountry.code'))) ? config('ipCountry.code') : 0)==$code) ? 'selected="selected"' : '' }}>
																{{ $item->get('name') }}
															</option>
														@endforeach
													</select>
												</div>
											</div>
										@else
											<input id="countryCode" name="country_code" type="hidden" value="{{ config('country.code') }}">
										@endif

										@if (isEnabledField('phone'))
											<!-- phone -->
											<?php $phoneError = (isset($errors) and $errors->has('phone')) ? ' is-invalid' : ''; ?>
											<div class="form-group row required">
												<label class="col-md-4 col-form-label">{{ t('Phone') }}
												<!-- start test -->
													<!-- @if (!isEnabledField('email'))
														<sup>*</sup>
													@endif -->
													<sup>*</sup>
												<!-- end test -->
												</label>
												<div class="col-md-6">
													<div class="input-group">
													<!-- start test -->
														<!-- <div class="input-group-prepend">
															<span id="phoneCountry" class="input-group-text">{!! getPhoneIcon(old('country', config('country.code'))) !!}</span>
														</div> -->
													<!-- end test -->										
														<input name="phone"
															   placeholder="{{ (!isEnabledField('email')) ? t('Mobile Phone Number') : t('Phone Number') }}"
															   class="form-control input-md{{ $phoneError }}"
															   type="tel"
															   value="{{ phoneFormat(old('phone'), old('country', config('country.code'))) }}"
														>
														
														<div class="input-group-append tooltipHere" data-placement="top"
															 data-toggle="tooltip"
															 data-original-title="{{ t('Hide the phone number on the ads.') }}">
															<span class="input-group-text">
																<input name="phone_hidden" id="phoneHidden" type="checkbox"
																	   value="1" {{ (old('phone_hidden')=='1') ? 'checked="checked"' : '' }}>&nbsp;<small>{{ t('Hide') }}</small>
															</span>
														</div>
													</div>
												</div>
											</div>
										@endif
										
										@include('layouts.inc.tools.recaptcha', ['colLeft' => 'col-md-4', 'colRight' => 'col-md-6'])
										 <!-- R.S. -->
									
										<!-- term -->
										<?php $termError = (isset($errors) and $errors->has('term')) ? ' is-invalid' : ''; ?>
										<div class="form-group row required">
											<label class="col-md-4 col-form-label"></label>
											<div class="col-md-6">
												<div class="form-check">
													<input name="term" id="term"
														   class="form-check-input{{ $termError }}"
														   value="1"
														   type="checkbox" {{ (old('term')=='1') ? 'checked="checked"' : '' }}
													>
													
													<label class="form-check-label" for="term">
														{!! t('I have read and agree to the <a :attributes>Terms of Use</a>',', ['attributes' => getUrlPageByType('terms')]) !!}
													</label>
												</div>
												<div style="clear:both"></div>
											</div>
										</div>

										<!-- Button  -->
										<div class="form-group row">
											<label class="col-md-4 col-form-label"></label>
											<div class="col-md-6">
												<button id="signupBtn" class="btn btn-success btn-lg"> {{ t('Register') }} </button>
											</div>
										</div>

										<div class="mb-5"></div>

									</fieldset>
								</form>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-4 reg-sidebar">
					<div class="reg-sidebar-inner text-center">
						<div class="promo-text-box"><i class="icon-picture fa fa-4x icon-color-1"></i>
							<h3><strong>{{ t('Post a Free Classified') }}</strong></h3>
							<p>
								{{ t('Do you have something to sell, to rent, any service to offer or a job offer? Post it at :app_name, its free, local, easy, reliable and super fast!',
								['app_name' => config('app.name')]) }}
							</p>
						</div>
						<div class="promo-text-box"><i class=" icon-pencil-circled fa fa-4x icon-color-2"></i>
							<h3><strong>{{ t('Create and Manage Items') }}</strong></h3>
							<p>{{ t('Become a best seller or buyer. Create and Manage your ads. Repost your old ads, etc.') }}</p>
						</div>
						<div class="promo-text-box"><i class="icon-heart-2 fa fa-4x icon-color-3"></i>
							<h3><strong>{{ t('Create your Favorite ads list.') }}</strong></h3>
							<p>{{ t('Create your Favorite ads list, and save your searches. Don\'t forget any deal!') }}</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('after_scripts')
	<script>
		$(document).ready(function () {
			/* Submit Form */
			$("#signupBtn").click(function () {
				$("#signupForm").submit();
				return false;
			});
		});
	</script>
@endsection