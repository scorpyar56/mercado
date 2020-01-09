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
							<strong><i class="icon-user-add"></i> {{ t('Enter your verification code') }}</strong>
							<!-- 'Create your account, Its free' -->
						</h2>

						
						<div class="row mt-5">
							<div class="col-xl-12">

								<form id="signupForm" class="form-horizontal" method="POST" action="{{ url('posts/create'). '/' . $token }}">
									{!! csrf_field() !!}
									<fieldset>
									<!-- User Choise -->
									<!-- term -->
									<?php $termError = (isset($errors) and $errors->has('term')) ? ' is-invalid' : ''; ?>
										<div class="form-group row required">
											<label class="col-md-3 col-form-label{{ $termError }}"></label>
											<div class="col-md-8">
												<label class="checkbox mb-0" for="term-0">
													{!! t('Some text here.') !!}
												</label>
											</div>
										</div>
										<!-- Button  -->
										<div class="form-group row">
											<label class="col-md-4 col-form-label"></label>
											<div class="col-md-6">
											<form action="{{ url('register') }}">
												<button id="signupBtn2" class="btn btn-success btn-lg"> {{ t('Register') }} </button>
											</form>
												<button id="signupBtn" class="btn btn-success btn-lg"> {{ t('Refuse') }} </button>
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