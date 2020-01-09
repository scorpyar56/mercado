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
				<div class="col-md-12 page-content">
					@if (isset($errors) and $errors->any())
						<div class="col-xl-12">
							<div class="alert alert-danger">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="unir-close"></i></button>
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

					@if (
						config('settings.social_auth.social_login_activation')
						and (
							(config('settings.social_auth.facebook_client_id') and config('settings.social_auth.facebook_client_secret'))
							or (config('settings.social_auth.linkedin_client_id') and config('settings.social_auth.linkedin_client_secret'))
							or (config('settings.social_auth.twitter_client_id') and config('settings.social_auth.twitter_client_secret'))
							or (config('settings.social_auth.google_client_id') and config('settings.social_auth.google_client_secret'))
							)
						)
						<div class="col-xl-12">
							<div class="row d-flex justify-content-center">
								<div class="col-8">
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
								</div>
							</div>
						</div>
					@endif

					<div class="col-lg-5 col-md-8 col-sm-10 col-xs-12 login-box mt-3">
						<form id="loginForm" role="form" method="POST" action="{{ url()->current() }}">
							{!! csrf_field() !!}
							<input type="hidden" name="country" value="{{ config('country.code') }}">
							<div class="col-xl-12 col-xl-12-dif">
								<div class="ads-header">
									<h3>
										<strong>{{ t('Log In') }}</strong>
									</h3>
								</div>

								<div class="inner-ads-box">
									<?php
										$loginValue = (session()->has('login')) ? session('login') : old('login');
										$loginField = getLoginField($loginValue);
										if ($loginField == 'phone') {
											$loginValue = phoneFormat($loginValue, old('country', config('country.code')));
										}
									?>
									<!-- login -->
									<?php $loginError = (isset($errors) and $errors->has('login')) ? ' is-invalid' : ''; ?>
									<div class="form-group">
										<label for="login" class="col-form-label">{{ t('Login') . ' (' . t('Phone') . ')' }}:</label>
										<div class="input-icon">
											<input id="login" name="login" type="text" placeholder="{{ getLoginLabel() }}" class="form-control{{ $loginError }} form-control-dif" value="{{ $loginValue }}">
										</div>
									</div>

									<!-- password -->
									<?php $passwordError = (isset($errors) and $errors->has('password')) ? ' is-invalid' : ''; ?>
									<div class="form-group">
										<label for="password" class="col-form-label">{{ t('Password') }}:</label>
										<div class="input-icon">
											<input id="password" name="password" type="password" class="form-control{{ $passwordError }} form-control-dif" placeholder="{{ t('Password') }}" autocomplete="off">
										</div>
									</div>

									@include('layouts.inc.tools.recaptcha', ['noLabel' => true])

									<!-- Show pass checkbox R.S -->
									<div class="form-group flex-align"  onclick="showPass1()">
										<div class="cntr">
{{--											<input class="form-check-input" type="checkbox" onclick="showPass1()">--}}
{{--											<label class="form-check-label form-check-label-dif" for="term">--}}
{{--												{!! t('Show Password') !!}--}}
{{--											</label>--}}
											<label for="cbx" class="label-cbx">
												<input id="cbx" type="checkbox" class="invisible">
												<div class="checkbox">
													<svg width="14px" height="14px" viewBox="0 0 14 14">
														<path d="M3,1 L17,1 L17,1 C18.1045695,1 19,1.8954305 19,3 L19,17 L19,17 C19,18.1045695 18.1045695,19 17,19 L3,19 L3,19 C1.8954305,19 1,18.1045695 1,17 L1,3 L1,3 C1,1.8954305 1.8954305,1 3,1 Z"></path>
														<polyline points="4 8 6 10 11 5"></polyline>
													</svg>
												</div>
												{!! t('Show Password') !!}
											</label>
										</div>
										<p class="pull-right">
											<a href="https://market.unifun.com/register">Registration</a>
										</p>
{{--										<div style="clear:both"></div>--}}
									</div>

									<!-- remember -->
									<?php $rememberError = (isset($errors) and $errors->has('remember')) ? ' is-invalid' : ''; ?>
									<div class="form-group flex-align">
										<div class="cntr">
											<label for="mRemember" class="label-cbx">
												<input type="checkbox" value="1" name="remember" id="mRemember" class="invisible ">
												<div class="checkbox">
													<svg width="14px" height="14px" viewBox="0 0 14 14">
														<path d="M3,1 L17,1 L17,1 C18.1045695,1 19,1.8954305 19,3 L19,17 L19,17 C19,18.1045695 18.1045695,19 17,19 L3,19 L3,19 C1.8954305,19 1,18.1045695 1,17 L1,3 L1,3 C1,1.8954305 1.8954305,1 3,1 Z"></path>
														<polyline points="4 8 6 10 11 5"></polyline>
													</svg>
												</div>
												{{ t('Keep me logged in') }}
											</label>
										</div>
{{--										<label class="checkbox form-check-label pull-left" style="font-weight: normal;">--}}
{{--											<input type="checkbox" value="1" name="remember" id="mRemember" class="{{ $rememberError }}"> {{ t('Keep me logged in') }}--}}
{{--										</label>--}}
{{--										<p class="pull-right">--}}
{{--											<a href="{{ lurl('password/reset') }}"> {{ t('Lost your password?') }} </a>--}}
{{--										</p>--}}
										<p class="pull-right">
											<a href="https://market.unifun.com/password/reset"> Lost your password? </a>
										</p>
{{--										<div style=" clear:both"></div>--}}
									</div>

									<!-- Submit -->
									<div class="form-group">
										<button id="loginBtn" class="btn btn-green btn-block btn-dif "> {{ t('Log In') }} </button>
									</div>
								</div>

{{--								<div class="card-footer card-footer-dif">--}}
{{--									<div class="text-center pull-right mt-2 mb-2">--}}
{{--										<a href="{{ lurl('password/reset') }}"> {{ t('Lost your password?') }} </a>--}}
{{--									</div>--}}
{{--									<div style=" clear:both"></div>--}}
{{--								</div>--}}
								<div class="login-box-btm text-center">
									<p>
										{{ t('Don\'t have an account?') }}<br>
										<a href="{{ lurl(trans('routes.register')) }}"><strong>{{ t('Register') }} !</strong></a>
									</p>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('after_scripts')
	<script>
		$(document).ready(function () {
			$("#loginBtn").click(function () {
				$("#loginForm").submit();
				return false;
			});
		});
		function showPass1() {
			var x = document.getElementById("password");
			if (x.type === "password") {
				x.type = "text";
			} else {
				x.type = "password";
			}
		}
</script>
@endsection
