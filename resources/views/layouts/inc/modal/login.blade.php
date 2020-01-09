<div class="modal fade" id="quickLogin" tabindex="-1" role="dialog">
	<div class="modal-dialog  modal-sm">
		<div class="modal-content modal-content-dif">
			
			<div class="modal-header modal-header-dif">
{{--				<h4 class="modal-title"><i class="icon-login fa"></i> {{ t('Log In') }} </h4>--}}
				<h1 class="modal-title"> {{ t('Log In') }} </h1>
				
				<button type="button" class="close" data-dismiss="modal">
{{--					<span aria-hidden="true"><i class="unir-close"></i></span>--}}
					<span aria-hidden="true"><i class="unir-close"></i></span>
					<span class="sr-only">{{ t('Close') }}</span>
				</button>
			</div>
			
			<form role="form" method="POST" action="{{ lurl(trans('routes.login')) }}">
				{!! csrf_field() !!}
				<div class="modal-body modal-body-dif">

					@if (isset($errors) and $errors->any() and old('quickLoginForm')=='1')
						<div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="unir-close"></i></button>
							<ul class="list list-check">
								@foreach($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
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
						<div class="row mb-3 d-flex justify-content-center pl-2 pr-2">
							@if (config('settings.social_auth.facebook_client_id') and config('settings.social_auth.facebook_client_secret'))
							<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-1 pl-1 pr-1">
								<div class="col-xl-12 col-md-12 col-sm-12 col-xs-12 btn btn-lg btn-fb">
									<a href="{{ lurl('auth/facebook') }}" class="btn-fb" title="{!! strip_tags(t('Login with Facebook')) !!}">
										<i class="icon-facebook-rect"></i> Facebook
									</a>
								</div>
							</div>
							@endif
							@if (config('settings.social_auth.linkedin_client_id') and config('settings.social_auth.linkedin_client_secret'))
							<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-1 pl-1 pr-1">
								<div class="col-xl-12 col-md-12 col-sm-12 col-xs-12 btn btn-lg btn-lkin">
									<a href="{{ lurl('auth/linkedin') }}" class="btn-lkin" title="{!! strip_tags(t('Login with LinkedIn')) !!}">
										<i class="icon-linkedin"></i> LinkedIn
									</a>
								</div>
							</div>
							@endif
							@if (config('settings.social_auth.twitter_client_id') and config('settings.social_auth.twitter_client_secret'))
							<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-1 pl-1 pr-1">
								<div class="col-xl-12 col-md-12 col-sm-12 col-xs-12 btn btn-lg btn-tw">
									<a href="{{ lurl('auth/twitter') }}" class="btn-tw" title="{!! strip_tags(t('Login with Twitter')) !!}">
										<i class="icon-twitter-bird"></i> Twitter
									</a>
								</div>
							</div>
							@endif
							@if (config('settings.social_auth.google_client_id') and config('settings.social_auth.google_client_secret'))
							<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 mb-1 pl-1 pr-1">
								<div class="col-xl-12 col-md-12 col-sm-12 col-xs-12 btn btn-lg btn-danger">
									<a href="{{ lurl('auth/google') }}" class="btn-danger" title="{!! strip_tags(t('Login with Google')) !!}">
										<i class="icon-googleplus-rect"></i> Google
									</a>
								</div>
							</div>
							@endif
						</div>
					@endif
					
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
						<label for="login" class="control-label">
							{{ t('Phone') }} <sup>*</sup>
						</label>
{{--						<div class="input-icon">--}}
						<div class="input-group">
							<!-- start test -->
{{--							<input id="mLogin" name="login" type="text" placeholder="{{ getLoginLabel() }}" class="form-control{{ $loginError }}" value="{{ $loginValue }}">--}}
							<input id="mLogin" name="login" type="text" placeholder="{{ t('Phone') }}" class="form-control{{ $loginError }}" value="{{ $loginValue }}">
							<!-- end test -->
						</div>
					</div>
					
					<!-- password -->
					<?php $passwordError = (isset($errors) and $errors->has('password')) ? ' is-invalid' : ''; ?>
					<div class="form-group">
						<label for="password" class="control-label">
							{{ t('Password') }} <sup>*</sup>
						</label>
{{--						<div class="input-icon">--}}
						<div class="input-group">
							<input id="mPassword" name="password" type="password" class="form-control{{ $passwordError }}" placeholder="{{ t('Password') }}" autocomplete="off">
						</div>
					</div>
					<!-- Show pass checkbox R.S -->
					<!-- <div class="form-group">
						<div class="form-check" >
							<input class="form-check-input" type="checkbox" onclick="mShowPass()">
							<label class="form-check-label form-check-label-dif" for="term" style="font-weight: normal;">
								{!! t('Show Password') !!}
							</label>
							<p class="pull-right">
								<a href="{{ lurl(trans('routes.register')) }}">{{ t('Register') }}</a>
							</p>
						</div>
						<div style="clear:both"></div>
					</div> -->

					<!-- Show pass checkbox N.M -->
					<!-- <div class="form-group flex-align">
						<div>
							<input id="term" type="checkbox" onclick="mShowPass()">
							<label class="checkbox form-check-label form-check-label-dif" for="term" style="font-weight: normal;">
								{!! t('Show Password') !!}
							</label>
						</div>
						<p class="pull-right">
							<a href="{{ lurl(trans('routes.register')) }}">{{ t('Register') }}</a>
						</p>
					</div> -->
					<!-- Show pass checkbox N.M -->
					<div class="form-group flex-align">
						<div class="cntr">
							<label for="cbx1" class="label-cbx">
							<input id="cbx1" type="checkbox" class="invisible" onclick="mShowPass()">
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
							<a href="{{ lurl(trans('routes.register')) }}">{{ t('Register') }}</a>
						</p>
					</div>
					
					<!-- remember -->
					<?php $rememberError = (isset($errors) and $errors->has('remember')) ? ' is-invalid' : ''; ?>
					<div class="form-group flex-align">
						<div class="cntr">
							<label for="mRemember" class="label-cbx">
							<input type="checkbox" value="1" name="remember" id="mRemember" class="invisible {{ $rememberError }}">
							<div class="checkbox">
								<svg width="14px" height="14px" viewBox="0 0 14 14">
								<path d="M3,1 L17,1 L17,1 C18.1045695,1 19,1.8954305 19,3 L19,17 L19,17 C19,18.1045695 18.1045695,19 17,19 L3,19 L3,19 C1.8954305,19 1,18.1045695 1,17 L1,3 L1,3 C1,1.8954305 1.8954305,1 3,1 Z"></path>
								<polyline points="4 8 6 10 11 5"></polyline>
								</svg>
							</div>
							{{ t('Keep me logged in') }}
							</label>
						</div>
						<p class="pull-right">
							<a href="{{ lurl('password/reset') }}"> {{ t('Lost your password?') }} </a>
						</p>
						<!-- <div style=" clear:both"></div> -->
					</div>
					
					@include('layouts.inc.tools.recaptcha', ['label' => true])
					
					<input type="hidden" name="quickLoginForm" value="1">
				</div>
				<div class="modal-footer modal-footer-dif">
					<button type="submit" class="btn btn-green pull-right btn-dif">{{ t('Log In') }}</button>
					<button type="button" class="btn btn-default btn-default-dif btn-modal btn-grey" data-dismiss="modal">{{ t('Cancel') }}</button>
				</div>
			</form>
			
		</div>
	</div>
</div>

<script>
	function mShowPass() {
		var x = document.getElementById("mPassword");
		if (x.type === "password") {
			x.type = "text";
		} else {
			x.type = "password";
		}
	}
</script>

