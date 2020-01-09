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
							<!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							<h5><strong>{{ t('Oops ! An error has occurred. Please correct the red fields in the form') }}</strong></h5> -->
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

				<div class="col-md-8  page-content">
					<div class="inner-box inner-box-dif">
						<div style="padding:14px 0px 0px 0px !important;">
							<h3  class="title-3 py-3">
								{{ t('Register') }}
							</h3>
							<p class="under-title">
								{{ t('Create your account, Its free') }}
							</p>
						</div>
						
						@if (
							config('settings.social_auth.social_login_activation')
							and (
								(config('settings.social_auth.facebook_client_id') and config('settings.social_auth.facebook_client_secret'))
								or (config('settings.social_auth.linkedin_client_id') and config('settings.social_auth.linkedin_client_secret'))
								or (config('settings.social_auth.twitter_client_id') and config('settings.social_auth.twitter_client_secret'))
								or (config('settings.social_auth.google_client_id') and config('settings.social_auth.google_client_secret'))
								)
							)
							<div class="row mb-3 d-flex justify-content-center pl-3 pr-3">
								@if (config('settings.social_auth.facebook_client_id') and config('settings.social_auth.facebook_client_secret'))
								<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-1 pl-1 pr-1">
									<div class="col-xl-12 col-md-12 col-sm-12 col-xs-12 btn btn-lg btn-fb">
										<a href="{{ lurl('auth/facebook') }}" class="btn-fb"><i class="icon-facebook-rect"></i> {!! t('Login with Facebook') !!}</a>
									</div>
								</div>
								@endif
								@if (config('settings.social_auth.linkedin_client_id') and config('settings.social_auth.linkedin_client_secret'))
								<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-1 pl-1 pr-1">
									<div class="col-xl-12 col-md-12 col-sm-12 col-xs-12 btn btn-lg btn-lkin">
										<a href="{{ lurl('auth/linkedin') }}" class="btn-lkin"><i class="icon-linkedin"></i> {!! t('Login with LinkedIn') !!}</a>
									</div>
								</div>
								@endif
								@if (config('settings.social_auth.twitter_client_id') and config('settings.social_auth.twitter_client_secret'))
								<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-1 pl-1 pr-1">
									<div class="col-xl-12 col-md-12 col-sm-12 col-xs-12 btn btn-lg btn-tw">
										<a href="{{ lurl('auth/twitter') }}" class="btn-tw"><i class="icon-twitter-bird"></i> {!! t('Login with Twitter') !!}</a>
									</div>
								</div>
								@endif
								@if (config('settings.social_auth.google_client_id') and config('settings.social_auth.google_client_secret'))
								<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-1 pl-1 pr-1">
									<div class="col-xl-12 col-md-12 col-sm-12 col-xs-12 btn btn-lg btn-danger">
										<a href="{{ lurl('auth/google') }}" class="btn-danger"><i class="icon-googleplus-rect"></i> {!! t('Login with Google') !!}</a>
									</div>
								</div>
								@endif
							</div>
							
							<div class="row d-flex justify-content-center loginOr">
								<div class="col-xl-12 mb-1">
									<hr class="hrOr">
									<span class="spanOr rounded">{{ t('or') }}</span>
								</div>
							</div>
						@endif
						
{{--						<div class="row mt-5">--}}
								<form id="signupForm" class="form-horizontal" method="POST" action="{{ url()->current() }}">

									{!! csrf_field() !!}

										<div class="col-xl-12 col-xl-12-dif">
											<div class="ads-header">
												<h3>
													<strong>{{ t('User information') }}</strong>
												</h3>
											</div>

											<div class="inner-ads-box">
												<!-- name -->
												<!-- <?php $nameError = (isset($errors) and $errors->has('name')) ? ' is-invalid' : ''; ?>
												<div class="form-group row required">
													<label class="col-md-2 col-form-label">{{ t('Name') }} <sup>*</sup></label>
													<div class="col-md-9">
														<input id="name" name="name" placeholder="{{ t('Name') }}" class="form-control input-md{{ $nameError }}" type="text" value="{{ old('name') }}">
													</div>
												</div> -->

												<!-- country_code -->
												@if (empty(config('country.code')))
													<?php $countryCodeError = (isset($errors) and $errors->has('country_code')) ? ' is-invalid' : ''; ?>
													<div class="form-group row required">
														<label class="col-md-2 col-form-label{{ $countryCodeError }}" for="country_code">{{ t('Your Country') }} <sup>*</sup></label>
														<div class="col-md-9">
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
														<label class="col-md-2 col-form-label">{{ t('Phone') }} <sup>*</sup>
														<!-- start test -->
															@if (!isEnabledField('email'))
																<sup>*</sup>
															@endif
															<!-- <sup>*</sup> -->
														<!-- end test -->
														</label>
														<div class="col-md-9">
															<div class="input-group">
															<!-- start test -->
																<!-- <div class="input-group-prepend">
																	<span id="phoneCountry" class="input-group-text">{!! getPhoneIcon(old('country', config('country.code'))) !!}</span>
																</div> -->
															<!-- end test -->
												
																<input id="phone"
																	   name="phone"
																	   placeholder="{{ (!isEnabledField('email')) ? t('Mobile Phone Number') : t('Phone number') }}"
																	   class="form-control input-md{{ $phoneError }}"
																	   type="tel"
																	   value="{{ phoneFormat(old('phone'), old('country', config('country.code'))) }}"
																>

																<!-- <div class="input-group-append tooltipHere" data-placement="top"
																	 data-toggle="tooltip"
																	 data-original-title="{{ t('Hide the phone number on the ads.') }}">
																	<span class="input-group-text">
																		<input name="phone_hidden" id="phoneHidden" type="checkbox"
																			   value="1" {{ (old('phone_hidden')=='1') ? 'checked="checked"' : '' }}>&nbsp;<small>{{ t('Hide') }}</small>
																	</span>
																</div> -->
															</div>
														</div>
													</div>
												@endif
											<!-- start test -->
												<!-- @if (isEnabledField('email')) -->
													<!-- email -->
													<!-- <?php $emailError = (isset($errors) and $errors->has('email')) ? ' is-invalid' : ''; ?> -->
													<!-- <div class="form-group row required">
														<label class="col-md-2 col-form-label" for="email">{{ t('Email') }}
															@if (!isEnabledField('phone'))
																<sup>*</sup>
															@endif
														</label>
														<div class="col-md-9">
															<div class="input-group">
																<div class="input-group-prepend">
																	<span class="input-group-text"><i class="icon-mail"></i></span>
																</div>
																<input id="email"
																	   name="email"
																	   type="email"
																	   class="form-control{{ $emailError }}"
																	   placeholder="{{ t('Email') }}"
																	   value="{{ old('email') }}"
																>
															</div>
														</div>
													</div>
												@endif -->
											<!-- end test -->
												@if (isEnabledField('username'))
													<!-- username -->
													<?php $usernameError = (isset($errors) and $errors->has('username')) ? ' is-invalid' : ''; ?>
													<div class="form-group row required">
														<label class="col-md-2 col-form-label" for="email">{{ t('Username') }}</label>
														<div class="col-md-9">
															<div class="input-group">
																<div class="input-group-prepend">
																	<span class="input-group-text"><i class="icon-user"></i></span>
																</div>
																<input id="username"
																	   name="username"
																	   type="text"
																	   class="form-control{{ $usernameError }}"
																	   placeholder="{{ t('Username') }}"
																	   value="{{ old('username') }}"
																>
															</div>
														</div>
													</div>
												@endif

												<!-- password -->
												<?php $passwordError = (isset($errors) and $errors->has('password')) ? ' is-invalid' : ''; ?>
												<div class="form-group row required">
													<label class="col-md-2 col-form-label" for="password">{{ t('Password') }} <sup>*</sup></label>
													<div class="col-md-9">
														<input id="password" name="password" type="password" class="form-control{{ $passwordError }}"
															   placeholder="{{ t('Password') }}">
														<br>
														<input id="password_confirmation" name="password_confirmation" type="password" class="form-control{{ $passwordError }}"
															   placeholder="{{ t('Password Confirmation') }}">
														<small id="" class="form-text text-muted">
															{{ t('At least :num characters', ['num' => 4]) }}
														</small>
													</div>

												</div>

												<!-- Show pass checkbox R.S -->
												<div class="form-group row required">
{{--													<label class="col-md-2 col-form-label"></label>--}}
													<div class="col-md-2"></div>
													<div class="col-md-9">
														<div class="form-check flex-align">
															<div class="cntr">
																<label for="cbx" class="label-cbx">
																<input id="cbx" type="checkbox" class="invisible" onclick="showPass()">
																<div class="checkbox">
																	<svg width="14px" height="14px" viewBox="0 0 14 14">
																	<path d="M3,1 L17,1 L17,1 C18.1045695,1 19,1.8954305 19,3 L19,17 L19,17 C19,18.1045695 18.1045695,19 17,19 L3,19 L3,19 C1.8954305,19 1,18.1045695 1,17 L1,3 L1,3 C1,1.8954305 1.8954305,1 3,1 Z"></path>
																	<polyline points="4 8 6 10 11 5"></polyline>
																	</svg>
																</div>
																{!! t('Show Password') !!}
																</label>
															</div>
															<!-- <input class="form-check-input" type="checkbox" onclick="showPass()">

															<label class="form-check-label" for="term">
																{!! t('Show Password') !!}
															</label> -->
														</div>
														<!-- <div style="clear:both"></div> -->
													</div>
												</div>


												@include('layouts.inc.tools.recaptcha', ['colLeft' => 'col-md-2', 'colRight' => 'col-md-9'])

												<!-- term -->
												<?php $termError = (isset($errors) and $errors->has('term')) ? ' is-invalid' : ''; ?>
												<div class="form-group row required">
{{--													<label class="col-md-2 col-form-label"></label>--}}
													<div class="col-md-2"></div>
													<div class="col-md-9">
														<div class="form-check flex-align">
															<div class="cntr">
																<label for="term" class="label-cbx">
																<input name="term" id="term" type="checkbox" class="invisible {{ $termError }}" value="1" {{ (old('term')=='1') ? 'checked="checked"' : '' }}>
																<div class="checkbox">
																	<svg width="14px" height="14px" viewBox="0 0 14 14">
																	<path d="M3,1 L17,1 L17,1 C18.1045695,1 19,1.8954305 19,3 L19,17 L19,17 C19,18.1045695 18.1045695,19 17,19 L3,19 L3,19 C1.8954305,19 1,18.1045695 1,17 L1,3 L1,3 C1,1.8954305 1.8954305,1 3,1 Z"></path>
																	<polyline points="4 8 6 10 11 5"></polyline>
																	</svg>
																</div>
																{!! t('I have read and agree to the <a :attributes>Terms of Use</a>', ['attributes' => getUrlPageByType('terms')]) !!}
																</label>
															</div>

															<!-- <input name="term" id="term"
																   class="form-check-input{{ $termError }}"
																   value="1"
																   type="checkbox" {{ (old('term')=='1') ? 'checked="checked"' : '' }}
															>

															<label class="form-check-label" for="term">
																{!! t('I have read and agree to the <a :attributes>Terms & Conditions</a>', ['attributes' => getUrlPageByType('terms')]) !!}
															</label> -->
														</div>
														<!-- <div style="clear:both"></div> -->
													</div>
												</div>

												<!-- Button  -->
												<div class="form-group row">
{{--													<label class="col-md-2 col-form-label"></label>--}}
													<div class="col-md-2"></div>
													<div class="col-md-9">
														<button id="signupBtn" class="btn btn-green btn-lg btn-dif btn-reg"> {{ t('Register') }} </button>
													</div>
												</div>

{{--												<div class="mb-5"></div>--}}
											</div>
										</div>
									</fieldset>
								</form>
{{--						</div>--}}
					</div>
				</div>

				<div class="col-md-4 d-none d-md-block page-content">
					<div class="help-block sticky-top">
						<h3 class="title-3 py-3">{{ t('Help links') }}</h3>
						<div class="text-content text-left from-wysiwyg">
							<h4><a href="{{ lurl('page/terms-of-use')}}">{{ t('Terms of Use') }}</a></h4>
							<h4><a href="{{ lurl('page/privacy-policy')}}">{{ t('Privacy Policy') }}</a></h4>
							<h4><a href="{{ lurl('page/posting-rules')}}">{{ t('Posting Rules') }}</a></h4>
							<h4><a href="{{ lurl('page/tips')}}">{{ t('Tips for Users') }}</a></h4>
							<h4><a href="{{ lurl('page/faq')}}">{{ t('FAQ') }}</a></h4>
							<h4><a href="{{ lurl('contact')}}">{{ t('Contact Us') }}</a></h4>
						</div>
					</div>
           		 </div>

				<!-- <div class="col-md-2 reg-sidebar">
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
				</div> -->
			</div>
		</div>
	</div>
@endsection

@section('after_scripts')
	<script>
		// $(document).ready(function () {
		// 	/* Submit Form */
		// 	$("#signupBtn").click(function () {
		// 		// $("#signupForm").submit();
		// 		// return false;
		// 		$('#signupForm').submit(function (event) {
		// 			event.preventDefault();
		// 			var action = $(this).attr('action');
		// 			var data = $(this).serializeArray();
		//
		// 			$.ajax({
		// 				type: 'POST',
		// 				url: action,
		// 				data: data
		// 			}).done(function (e) {
		// 				console.log(e);
		// 				if(e[0] === "banPhone") {
		// 					$('#unbanModal').attr('action', '/unban/' + e[1].entry + '/request');
		// 					$('#unbanModal>.modal-body>.form-group>.input-group>#phone').attr('value', e[1].entry);
		// 					$('.control-label').append(': ' + e[1].entry);
		// 					$('#unbanRequest').modal();
		// 				} else {
		// 					event.currentTarget.submit();
		// 				}
		// 			}).error(function(e){
		// 				var result = jQuery.parseJSON(e.responseText);
		//
		// 				if(!result.success){
		// 					$.each( result.data, function( key, value ) {
		// 						var oldClass = $("#" + key).attr("class");
		// 						console.log(oldClass);
		// 						$("#" + key).attr("class", oldClass + " is-invalid");
		//
		// 						if(key === "password"){
		// 							$("#" + key + "_confirmation").attr("class", oldClass + " is-invalid");
		// 						}
		// 					});
		// 				}
		// 			});
		// 		})
		// 	});
		// });
		function showPass() {
			var x = document.getElementById("password");
			if (x.type === "password") {
				x.type = "text";
			} else {
				x.type = "password";
			}
			var y = document.getElementById("password_confirmation");
			if (y.type === "password") {
				y.type = "text";
			} else {
				y.type = "password";
			}
		};
	</script>
@endsection