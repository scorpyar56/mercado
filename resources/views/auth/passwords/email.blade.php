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
						<div class="col-xl-12" style="padding-left:0px;">
							<div class="alert alert-danger">
								<ul class="list list-check">
									@foreach ($errors->all() as $error)
										@if (count($errors->all()) < 2)
											<li class="remTicks">{{ $error }}</li>
										@else
											<li>{{ $error }}</li>
										@endif
									@endforeach
								</ul>
							</div>
						</div>
					@endif

					@if (session('status'))
						<div class="col-xl-12">
							<div class="alert alert-success">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="unir-close"></i></button>
								<p>{{ session('status') }}</p>
							</div>
						</div>
					@endif

					<!-- @if (session('email'))
						<div class="col-xl-12">
							<div class="alert alert-danger">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="unir-close"></i></button>
								<p>{{ session('email') }}</p>
							</div>
						</div>
					@endif -->


					@if (Session::has('flash_notification'))
						<div class="col-xl-12">
							<div class="row">
								<div class="col-xl-12" style="padding: 0">
									@include('flash::message')
								</div>
							</div>
						</div>
					@endif

					<div class="col-lg-5 col-md-8 col-sm-10 col-xs-12" style="margin-top: 30px; padding-left:0px;">
						<!-- <div class="card card-default"> -->
							<!-- <div class="ads-header"> -->
								<h3>
									<strong>{{ t('Password Recovery') }}</strong>
								</h3>
							<!-- </div> -->
							<!-- <div class="card-body"> -->
								<form id="pwdForm" role="form" method="POST" action="{{ lurl('password/email') }}">
									{!! csrf_field() !!}

									<!-- login -->
									<?php $phoneError = (isset($errors) and $errors->has('phone')) ? ' is-invalid' : ''; ?>
									<div class="form-group">
										<label for="phone" class="control-label">{{ t('Phone Number')  }}:</label>
										<div class="input-icon">
											<input id="phone"
												   name="phone"
												   type="tel"
												   placeholder="{{ t('Phone Number') }}"
												   class="form-control{{ $phoneError }} form-control-dif"
												   value="{{ old('phone') }}">
										</div>
									</div>
									@include('layouts.inc.tools.recaptcha', ['noLabel' => true])
									<div class="reset-password">
										<label for="login" class="control-label">{{ t('Steps for password recovery')  }}:</label>
										<div>{{ t('1.  Enter your phone number') }}</div>
										<div>{{ t('2.  Click “Send” button') }}</div>
										<div>{{ t('3.  We will send your new password on your email address')}}</div>
										<div class="note">{{ t('Please note – password recovery option is available only for users that previously indicated email address in Mercado.gratice account settings')}}</div>
									</div>
									
									<!-- Submit -->
									<div class="form-group form-group-dif">
										<button id="pwdBtn" type="submit" class="btn btn-green btn-lg btn-block btn-dif btn-res">{{ t('Submit') }}</button>
										<!-- <a href="{{ lurl(trans('routes.login')) }}" class="btn btn-default btn-lg btn-default-dif btn-rep"> {{ t('Back') }} </a> -->
									</div>
								</form>
							<!-- </div> -->
						<!-- </div> -->
						<div class="login-box-btm">
							<p>
								{{ t('Don\'t have an account?') }} <br>
								<a href="{{ lurl(trans('routes.register')) }}">{{ t('Register') }}</a>
							</p>
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
			$("#pwdBtn").click(function () {
				$("#pwdForm").submit();
				return false;
			});
		});
	</script>
@endsection