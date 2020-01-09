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

				<div class="col-lg-5 col-md-8 col-sm-10 col-xs-12 login-box">
					<div class="card card-default">
						<div class="panel-intro text-center">
							<h2 class="logo-title">
								<span class="logo-icon"> </span>123 {{ t('Reset Password') }} <span> </span>
							</h2>
						</div>
						
						<div class="card-body">
							<form method="POST" action="{{ lurl('password/reset') }}">
								{!! csrf_field() !!}
								<input type="hidden" name="token" value="{{ $token }}">
								{{ $token }}
								<!-- login -->
								<?php $phoneError = (isset($errors) and $errors->has('phone')) ? ' is-invalid' : ''; ?>
								<div class="form-group">
									<label for="phone" class="control-label">{{ t('Phone Number')  }}:</label>
									<input type="text" name="phone" value="{{ old('phone') }}" placeholder="{{ t('Phone Number') }}" class="form-control{{ $phoneError }}">
								</div>
								
								<!-- password -->
								<?php $passwordError = (isset($errors) and $errors->has('password')) ? ' is-invalid' : ''; ?>
								<div class="form-group">
									<label for="password" class="control-label">{{ t('Password') }}:</label>
									<input id="password" type="password" name="password" placeholder="" class="form-control email{{ $passwordError }}">
								</div>
								
								<!-- password_confirmation -->
								<?php $passwordError = (isset($errors) and $errors->has('password')) ? ' is-invalid' : ''; ?>
								<div class="form-group">
									<label for="password_confirmation" class="col-form-label">{{ t('Password Confirmation') }}:</label>
									<input id="password_confirmation" type="password" name="password_confirmation" placeholder="" class="form-control email{{ $passwordError }}">
								</div>

								<!-- Show pass checkbox R.S -->
								<div class="form-group">
										<div class="form-check">
											<input class="form-check-input" type="checkbox" onclick="showPass()">
											<label class="form-check-label" for="term">
												{!! t('Show Password') !!}
											</label>
										</div>
										<div style="clear:both"></div>
								</div>
								@include('layouts.inc.tools.recaptcha', ['noLabel' => true])
								
								<!-- Submit -->
								<div class="form-group">
									<button type="submit" class="btn btn-primary btn-lg btn-block">{{ t('Reset the Password') }}</button>
								</div>
							</form>
						</div>
						
						<div class="card-footer text-center">
							<a href="{{ lurl(trans('routes.login')) }}"> {{ t('Back to the Log In page') }} </a>
						</div>
					</div>
					<div class="login-box-btm text-center">
						<p>
							{{ t('Don\'t have an account?') }} <br>
							<a href="{{ lurl(trans('routes.register')) }}"><strong>{{ t('Register') }}</strong></a>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('after_scripts')
	<script>
		$(document).ready(function () {
			$("#pwdBtn").click(function () {
				$("#pwdForm").submit();
				return false;
			});
		});
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
		}
	</script>
@endsection