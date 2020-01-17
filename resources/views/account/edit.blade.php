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
	@include('common.spacer')
	<div class="main-container">

		<div class="user-info-modal">
			<div class="user-modal-content">
				<div class="modal-header modal-header-dif">
					<h4 class="modal-title"> {{ t('Are you sure you want to delete this picture?') }}</h4>
					<button type="button" class="close" data-dismiss="modal">
						<span aria-hidden="true"><i class="unir-close"></i></span>
						<span class="sr-only">{{ t('Close') }}</span>
					</button>
				</div>
				<div class="modal-footer modal-footer-dif modal-footer-user">
					<div class="modal-chose">
						<form role="form" method="POST" action="{{ lurl('account/' . auth()->user()->id . '/photo/delete') }}" enctype="multipart/form-data">
							<button type="submit" class="btn btn-dif btn-grey">{{ ('Delete') }}</button>
						</form>
					</div>
					<div class="modal-cancel">
						<button class="kv-file-remove btn btn-default btn-default-dif btn-modal btn-kv btn-outline-secondary btn-grey" id="cancelDelPhoto">{{ t('Cancel') }}</button>
					</div>
				</div>
			</div>
		</div>

		<div class="container">
			<div class="row">
				<div class="col-md-3 page-sidebar">
					@include('account.inc.sidebar')
				</div>
				<!--/.page-sidebar-->

				<div class="col-md-9 page-content">

					@include('flash::message')

					@if (isset($errors) and $errors->any())
						<div class="alert alert-danger">
							<!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="unir-close"></i></button>
							<h5><strong>{{ t('Oops ! An error has occurred. Please correct the red fields in the form') }}</strong></h5> -->
							<ul class="list list-check">
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif

					<div id="avatarUploadError" class="center-block" style="width:100%; display:none"></div>
					<div id="avatarUploadSuccess" class="alert alert-success fade show" style="display:none;"></div>


					<div class="inner-box default-inner-box inner-box-dif">

						<!-- <div class="col-md-6 col-xs-12 col-xxs-12 cab-cel cab-cel-tl cab-cel-mob">
							<div class="ads-header">
								<h3>
									<a href="#userPanel" aria-expanded="true" data-toggle="collapse" data-parent="#accordion">{{ t('Personal Home') }}</a>
								</h3>
							</div>
						</div> -->

						<div class="col-md-6 col-xs-12 col-xxs-12 cab-cel cab-cel-tl main-cel">
							<div class="cab-cel-main">
								<div class="head-message">
									<h1 class="page-sub-header2 clearfix no-padding">{{ t('Hello') }} {{ $user->name }} </h1>
									<span class="page-sub-header-sub">
                                        {{ t('You last logged in at') }}: {{ $user->last_login_at->formatLocalized(config('settings.app.default_datetime_format')) }}
                                    </span>
								</div>

								<div class="card card-default card-dif">
									<div class="panel-collapse collapse {{ (old('panel')=='' or old('panel')=='photoPanel') ? 'show' : '' }}" id="photoPanel">
										<form name="details" class="form-horizontal" role="form" method="POST" action="{{ lurl('account/' . $user->id . '/photo') }}">
											<?php $photoError = (isset($errors) and $errors->has('photo')) ? ' is-invalid' : ''; ?>
											<div class="photo-field photo-field-dif">
												<div class="file-loading">
													<input id="photoField" name="photo" type="file" class="file {{ $photoError }}">
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-6 col-xs-12 col-xxs-12 cab-cel cab-cel-tr">
							<!-- Conversations Stats -->
							<div class="col-md-6 cab-cel-tl cab-cel-inner">
								<div class="cab-cel-child">
									<div class="cel-image">
										<img src="/images/mail.svg">
									</div>
									<div>
										<!-- Number of messages -->
										<p>
											<a href="{{ lurl('account/conversations') }}">
												{{ t('Conversations') }}
											</a>
											{{ isset($countConversations) ? \App\Helpers\Number::short($countConversations) : 0 }}
										</p>
									</div>
								</div>
							</div>

							<!-- Traffic Stats -->
							<div class="col-md-6 cab-cel-tr cab-cel-inner">
								<div class="cab-cel-child">
									<div class="cel-image">
										<img src="/images/users.svg">
									</div>
									<div>
										<!-- Number of visitors -->
										<p>
											<a href="{{ lurl('account/my-posts') }}">
												{{ t('Visits') }}
											</a>
											<?php $totalPostsVisits = (isset($countPostsVisits) and $countPostsVisits->total_visits) ? $countPostsVisits->total_visits : 0 ?>
											{{ \App\Helpers\Number::short($totalPostsVisits) }}
										</p>
									</div>
								</div>
							</div>

							<!-- Ads Stats -->
							<div class="col-md-6 cab-cel-bl cab-cel-inner">
								<div class="cab-cel-child">
									<div class="cel-image">
										<img src="/images/ads.svg">
									</div>
									<div>
										<!-- Number of ads -->
										<p>
											<a href="{{ lurl('account/my-posts') }}">
												{{ t('Ads') }}
											</a>
											{{ \App\Helpers\Number::short($countPosts) }}
										</p>
									</div>
								</div>
							</div>

							<!-- Favorites Stats -->
							<div class="col-md-6 cab-cel-br cab-cel-inner">
								<div class="cab-cel-child">
									<div class="cel-image">
										<img src="/images/heart.svg">
									</div>
									<div>
										<!-- Number of favorites -->
										<p>
											<a href="{{ lurl('account/favourite') }}">
												{{ t('Favorite') }}
											</a>
											{{ \App\Helpers\Number::short($countFavoritePosts) }}
										</p>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-6 col-xs-12 col-xxs-12 cab-cel cel-dif cab-cel-bl cal-acc">
							<div class="card card-default col-xl-12-dif">
								<div class="ads-header">
									<h3>
										<a>{{ t('Account Details') }}</a>
									</h3>
								</div>
								<div class="panel-collapse collapse {{ (old('panel')=='' or old('panel')=='userPanel') ? 'show' : '' }} inner-ads-box" id="userPanel">
									{{--                                    <div class="card-body">--}}
									<form name="details" class="form-horizontal" role="form" method="POST" action="{{ url()->current() }}">
										{!! csrf_field() !!}
										<input name="_method" type="hidden" value="PUT">
										<input name="panel" type="hidden" value="userPanel">

										<!-- gender_id -->
										<?php $genderIdError = (isset($errors) and $errors->has('gender_id')) ? ' is-invalid' : ''; ?>
										<label class="col-4 col-form-label">{{ t('Gender') }}</label>
										<div class="form-group flex-block required">
											<div style="display: flex; align-items: center;" class="col-8">
												@if ($genders->count() > 0)
													@foreach ($genders as $gender)
														<div class="form-check form-check-inline">
															<!-- <input name="gender_id"
																   id="gender_id-{{ $gender->tid }}"
																   value="{{ $gender->tid }}"
																   class="form-check-input{{ $genderIdError }}"
																   type="radio" {{ (old('gender_id', $user->gender_id)==$gender->tid) ? 'checked="checked"' : '' }}
															>
															<label class="form-check-label" for="gender_id-{{ $gender->tid }}">
																{{ $gender->name }}
															</label> -->
															<label for="gender_id-{{ $gender->tid }}" class="radio">
																<input type="radio" name="gender_id" id="gender_id-{{ $gender->tid }}" value="{{ $gender->tid }}" class="hidden {{ $genderIdError }}" {{ (old('gender_id', $user->gender_id)==$gender->tid) ? 'checked="checked"' : '' }}  />
																<span class="label"></span>{{ $gender->name }}
															</label>
														</div>
													@endforeach
												@endif
											</div>
										</div>

										<!-- name -->
										<?php $nameError = (isset($errors) and $errors->has('name')) ? ' is-invalid' : ''; ?>
										<label class="col-4 col-form-label">{{ t('Name') }} <sup>*</sup></label>
										<div class="form-group flex-block required">
											<div class="col-8">
												<input name="name" type="text" class="form-control{{ $nameError }}" placeholder="" value="{{ old('name', $user->name) }}">
											</div>
										</div>

										<!-- phone -->
										<?php $phoneError = (isset($errors) and $errors->has('phone')) ? ' is-invalid' : ''; ?>
										<label for="phone" class="col-4 col-form-label">{{ t('Phone') }} <sup>*</sup></label>
										<div class="form-group flex-block required">

											<div class="input-group col-8">
												<input id="phone" name="phone" type="text" class="form-control{{ $phoneError }}"
													   placeholder="{{ (!isEnabledField('email')) ? t('Mobile Phone Number') : t('Phone Number') }}"
													   value="{{ phoneFormat(old('phone', $user->phone), old('country_code', $user->country_code)) }}">

												{{--                                                    <div class="input-group-append">--}}
												{{--														<span class="input-group-text">--}}
												{{--															<input name="phone_hidden" id="phoneHidden" type="checkbox"--}}
												{{--                                                                   value="1" {{ (old('phone_hidden', $user->phone_hidden)=='1') ? 'checked="checked"' : '' }}>&nbsp;--}}
												{{--															<small>{{ t('Hide') }}</small>--}}
												{{--														</span>--}}
												{{--                                                    </div>--}}
											</div>
										</div>

										<!-- Button -->
										<div class="form-group flex-block">
											<div class="offset-4 col-8">
												<button type="submit" class="btn btn-acc btn-green pull-right">{{ t('Update') }}</button>
											</div>
										</div>
									</form>
									{{--                                    </div>--}}
								</div>
							</div>
						</div>

						<div class="col-md-6 col-xs-12 col-xxs-12 cab-cel cel-last cab-cel-br cal-acc">
							<div class="card card-default col-xl-12-dif">
								<div class="ads-header">
									<h3>
										<a>{{ t('Change password') }}</a>
									</h3>
								</div>
								<div class="panel-collapse {{ (old('panel')=='settingsPanel') ? 'show' : '' }} inner-ads-box" id="settingsPanel">
									<form name="settings" class="form-horizontal" role="form" method="POST" action="{{ lurl('account/settings') }}">
										{!! csrf_field() !!}
										<input name="_method" type="hidden" value="PUT">
										<input name="panel" type="hidden" value="settingsPanel">

										@if (config('settings.single.activation_facebook_comments') and config('services.facebook.client_id'))
										<!-- disable_comments -->
											<div class="form-group flex-block">
												<label class="col-4 col-form-label"></label>
												<div class="col-8">
													<div class="form-check form-check-inline pt-2">
														<label>
															<input id="disable_comments"
																   name="disable_comments"
																   value="1"
																   type="checkbox" {{ ($user->disable_comments==1) ? 'checked' : '' }}
															>
															{{ t('Disable comments on my ads') }}
														</label>
													</div>
												</div>
											</div>
										@endif

										<div class="supp"></div>

										<!-- password -->
										<?php $passwordError = (isset($errors) and $errors->has('password')) ? ' is-invalid' : ''; ?>
										<label class="col-4 col-form-label">{{ t('New Password') }}</label>
										<div class="form-group flex-block">
											<div class="col-8">
												<input id="password" name="password" type="password" class="form-control{{ $passwordError }}" placeholder="{{ t('Password') }}">
											</div>
										</div>

										<!-- password_confirmation -->
										<?php $passwordError = (isset($errors) and $errors->has('password')) ? ' is-invalid' : ''; ?>
										<label class="col-4 col-form-label">{{ t('Confirm Password') }}</label>
										<div class="form-group flex-block">
											<div class="col-8">
												<input id="password_confirmation" name="password_confirmation" type="password"
													   class="form-control{{ $passwordError }}" placeholder="{{ t('Confirm Password') }}">
											</div>
										</div>

										<!-- Button -->
										<div class="form-group flex-block">
											<div class="offset-4 col-8">
												<button type="submit" class="btn btn-acc btn-green pull-right">{{ t('Update') }}</button>
											</div>
										</div>
									</form>
								</div>
							</div>

						</div>

						<div class="col-md-12 col-xs-12 col-xxs-12 cab-cel-email cab-cel-bl cal-acc">
							<div class="card card-default col-xl-12-dif">
								<div class="panel-collapse {{ (old('panel')=='settingsPanel') ? 'show' : '' }} inner-ads-box" id="settingsPanel">
									<form name="settings" class="form-horizontal" role="form" method="POST" action="{{ lurl('account/settings') }}">
										{!! csrf_field() !!}
										<input name="_method" type="hidden" value="PUT">
										<input name="panel" type="hidden" value="settingsPanel">

										<?php $emailError = (isset($errors) and $errors->has('email')) ? ' is-invalid' : ''; ?>
										<div class="form-group flex-block margin-none email-last">
											<label class="col-md-4 col-form-label line-small">
												<b>&#33;</b>
												{{ t('Add email') }}
												<br>
												<i>{{ t('if you forget password we send it on email') }}</i>
											</label>
											<div class="col-md-4 email-input">
												<input id="email" name="email" type="email"
													   class="form-control{{ $passwordError }}" placeholder="{{ t('Your Email') }}" value="{{ old('email', $user->email) }}">
											</div>
											<div class="col-md-4 email-btn">
												<button type="submit" class="btn btn-acc btn-green pull-right">{{ t('Update') }}</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
						<div class="modal fade" id="checkEmailModal" tabindex="-1" role="dialog" aria-labeledby="#titleCheckEmailModal" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
										<div class="modal-header modal-header-dif">
											<h2 class="modal-title" id="titleCheckEmailModal">
												{{t('Add email')}}
											</h2>
											<button type="button" class="close" data-dismiss="modal">
												{{--					<span aria-hidden="true">&times;</span>--}}
												<span aria-hidden="true"><i class="unir-close"></i></span>
												<span class="sr-only">{{ t('Close') }}</span>
											</button>
										</div>

									<div class="modal-body modal-body-dif modal-body-user">
										<div class="modal-text">
											{{t('if you forget password we send it on email')}}
										</div>
									</div>
								</div>
							</div>
						</div>

					{{--					<div class="inner-box default-inner-box">--}}
					{{--						<div class="row">--}}
					{{--							<div class="col-md-5 col-xs-4 col-xxs-12">--}}
					{{--								<h3 class="no-padding text-center-480 useradmin">--}}
					{{--									<a href="">--}}
					{{--										@if (!empty($userPhoto))--}}
					{{--											<img id="userImg" class="userImg" src="{{ $userPhoto }}" alt="user">&nbsp;--}}
					{{--										@else--}}
					{{--											<img id="userImg" class="userImg" src="{{ url('images/user.jpg') }}" alt="user">--}}
					{{--										@endif--}}
					{{--										{{ $user->name }}--}}
					{{--									</a>--}}
					{{--								</h3>--}}
					{{--							</div>--}}
					{{--							<div class="col-md-7 col-xs-8 col-xxs-12">--}}
					{{--								<div class="header-data text-center-xs">--}}
					{{--									<!-- Conversations Stats -->--}}
					{{--									<div class="hdata">--}}
					{{--										<div class="mcol-left">--}}
					{{--											<i class="fas fa-envelope ln-shadow"></i></div>--}}
					{{--										<div class="mcol-right">--}}
					{{--											<!-- Number of messages -->--}}
					{{--											<p>--}}
					{{--												<a href="{{ lurl('account/conversations') }}">--}}
					{{--													{{ isset($countConversations) ? \App\Helpers\Number::short($countConversations) : 0 }}--}}
					{{--													<em>{{ trans_choice('global.count_mails', getPlural($countConversations)) }}</em>--}}
					{{--												</a>--}}
					{{--											</p>--}}
					{{--										</div>--}}
					{{--										<div class="clearfix"></div>--}}
					{{--									</div>--}}
					{{--									--}}
					{{--									<!-- Traffic Stats -->--}}
					{{--									<div class="hdata">--}}
					{{--										<div class="mcol-left">--}}
					{{--											<i class="fa fa-eye ln-shadow"></i>--}}
					{{--										</div>--}}
					{{--										<div class="mcol-right">--}}
					{{--											<!-- Number of visitors -->--}}
					{{--											<p>--}}
					{{--												<a href="{{ lurl('account/my-posts') }}">--}}
					<?php $totalPostsVisits = (isset($countPostsVisits) and $countPostsVisits->total_visits) ? $countPostsVisits->total_visits : 0 ?>
					{{--													{{ \App\Helpers\Number::short($totalPostsVisits) }}--}}
					{{--													<em>{{ trans_choice('global.count_visits', getPlural($totalPostsVisits)) }}</em>--}}
					{{--												</a>--}}
					{{--											</p>--}}
					{{--										</div>--}}
					{{--										<div class="clearfix"></div>--}}
					{{--									</div>--}}

					{{--									<!-- Ads Stats -->--}}
					{{--									<div class="hdata">--}}
					{{--										<div class="mcol-left">--}}
					{{--											<i class="icon-th-thumb ln-shadow"></i>--}}
					{{--										</div>--}}
					{{--										<div class="mcol-right">--}}
					{{--											<!-- Number of ads -->--}}
					{{--											<p>--}}
					{{--												<a href="{{ lurl('account/my-posts') }}">--}}
					{{--													{{ \App\Helpers\Number::short($countPosts) }}--}}
					{{--													<em>{{ trans_choice('global.count_posts', getPlural($countPosts)) }}</em>--}}
					{{--												</a>--}}
					{{--											</p>--}}
					{{--										</div>--}}
					{{--										<div class="clearfix"></div>--}}
					{{--									</div>--}}

					{{--									<!-- Favorites Stats -->--}}
					{{--									<div class="hdata">--}}
					{{--										<div class="mcol-left">--}}
					{{--											<i class="fa fa-user ln-shadow"></i>--}}
					{{--										</div>--}}
					{{--										<div class="mcol-right">--}}
					{{--											<!-- Number of favorites -->--}}
					{{--											<p>--}}
					{{--												<a href="{{ lurl('account/favourite') }}">--}}
					{{--													{{ \App\Helpers\Number::short($countFavoritePosts) }}--}}
					{{--													<em>{{ trans_choice('global.count_favorites', getPlural($countFavoritePosts)) }} </em>--}}
					{{--												</a>--}}
					{{--											</p>--}}
					{{--										</div>--}}
					{{--										<div class="clearfix"></div>--}}
					{{--									</div>--}}
					{{--								</div>--}}
					{{--							</div>--}}
					{{--						</div>--}}
					{{--					</div>--}}

					{{--					<div class="inner-box default-inner-box">--}}
					{{--						--}}
					{{--						<div id="accordion" class="panel-group">--}}
					<!-- PHOTO -->
					{{--							<div class="card card-default">--}}
					{{--								<div class="card-header">--}}
					{{--									<h4 class="card-title">--}}
					{{--										<a href="#photoPanel" data-toggle="collapse" data-parent="#accordion">{{ t('Photo or Avatar') }}</a>--}}
					{{--									</h4>--}}
					{{--								</div>--}}
					{{--								<div class="panel-collapse collapse {{ (old('panel')=='' or old('panel')=='photoPanel') ? 'show' : '' }}" id="photoPanel">--}}
					{{--									<div class="card-body">--}}
					{{--										<form name="details" class="form-horizontal" role="form" method="POST" action="{{ lurl('account/' . $user->id . '/photo') }}">--}}
					{{--											<div class="row">--}}
					{{--												<div class="col-xl-12 text-center">--}}
					{{--													--}}
					<?php $photoError = (isset($errors) and $errors->has('photo')) ? ' is-invalid' : ''; ?>
					{{--													<div class="photo-field">--}}
					{{--														<div class="file-loading">--}}
					{{--															<input id="photoField" name="photo" type="file" class="file {{ $photoError }}">--}}
					{{--														</div>--}}
					{{--													</div>--}}
					{{--												--}}
					{{--												</div>--}}
					{{--											</div>--}}
					{{--										</form>--}}
					{{--									</div>--}}
					{{--								</div>--}}
					{{--							</div>--}}

					<!-- USER -->
					{{--							<div class="card card-default">--}}
					{{--								<div class="card-header">--}}
					{{--									<h4 class="card-title">--}}
					{{--										<a href="#userPanel" aria-expanded="true" data-toggle="collapse" data-parent="#accordion">{{ t('Account Details') }}</a>--}}
					{{--									</h4>--}}
					{{--								</div>--}}
					{{--								<div class="panel-collapse collapse {{ (old('panel')=='' or old('panel')=='userPanel') ? 'show' : '' }}" id="userPanel">--}}
					{{--									<div class="card-body">--}}
					{{--										<form name="details" class="form-horizontal" role="form" method="POST" action="{{ url()->current() }}">--}}
					{{--											{!! csrf_field() !!}--}}
					{{--											<input name="_method" type="hidden" value="PUT">--}}
					{{--											<input name="panel" type="hidden" value="userPanel">--}}

					{{--											<!-- gender_id -->--}}
					<?php $genderIdError = (isset($errors) and $errors->has('gender_id')) ? ' is-invalid' : ''; ?>
					{{--											<div class="form-group row required">--}}
					{{--												<label class="col-md-2 col-form-label">{{ t('Gender') }}</label>--}}
					{{--												<div class="col-md-9">--}}
					{{--													@if ($genders->count() > 0)--}}
					{{--                                                        @foreach ($genders as $gender)--}}
					{{--															<div class="form-check form-check-inline pt-2">--}}
					{{--																<input name="gender_id"--}}
					{{--																	   id="gender_id-{{ $gender->tid }}"--}}
					{{--																	   value="{{ $gender->tid }}"--}}
					{{--																	   class="form-check-input{{ $genderIdError }}"--}}
					{{--																	   type="radio" {{ (old('gender_id', $user->gender_id)==$gender->tid) ? 'checked="checked"' : '' }}--}}
					{{--																>--}}
					{{--																<label class="form-check-label" for="gender_id-{{ $gender->tid }}">--}}
					{{--																	{{ $gender->name }}--}}
					{{--																</label>--}}
					{{--															</div>--}}
					{{--                                                        @endforeach--}}
					{{--													@endif--}}
					{{--												</div>--}}
					{{--											</div>--}}
					{{--												--}}
					{{--											<!-- name -->--}}
					<?php $nameError = (isset($errors) and $errors->has('name')) ? ' is-invalid' : ''; ?>
					{{--											<div class="form-group row required">--}}
					{{--												<label class="col-md-2 col-form-label">{{ t('Name') }} <sup>*</sup></label>--}}
					{{--												<div class="col-md-9">--}}
					{{--													<input name="name" type="text" class="form-control{{ $nameError }}" placeholder="" value="{{ old('name', $user->name) }}">--}}
					{{--												</div>--}}
					{{--											</div>--}}
					{{--											--}}
					{{--											<!-- username -->--}}
					<!-- <?php $usernameError = (isset($errors) and $errors->has('username')) ? ' is-invalid' : ''; ?> -->
					{{--											<!-- <div class="form-group row required">--}}
					{{--												<label class="col-md-2 col-form-label" for="email">{{ t('Username') }} <sup>*</sup></label>--}}
					{{--												<div class="input-group col-md-9">--}}
					{{--													<div class="input-group-prepend">--}}
					{{--														<span class="input-group-text"><i class="icon-user"></i></span>--}}
					{{--													</div>--}}
					{{--													--}}
					{{--													<input id="username"--}}
					{{--														   name="username"--}}
					{{--														   type="text"--}}
					{{--														   class="form-control{{ $usernameError }}"--}}
					{{--														   placeholder="{{ t('Username') }}"--}}
					{{--														   value="{{ old('username', $user->username) }}"--}}
					{{--													>--}}
					{{--												</div>--}}
					{{--											</div> -->--}}
					{{--												--}}
					{{--											<!-- email -->--}}
					{{--											<!-- start test -->--}}
					<?php $emailError = (isset($errors) and $errors->has('email')) ? ' is-invalid' : ''; ?>
					{{--											<div class="form-group row required">--}}
					{{--												<label class="col-md-2 col-form-label">{{ t('Email') }} <sup>*</sup></label>--}}
					{{--												<div class="input-group col-md-9">--}}
					{{--													<div class="input-group-prepend">--}}
					{{--														<span class="input-group-text"><i class="icon-mail"></i></span>--}}
					{{--													</div>--}}
					{{--													--}}
					{{--													<input id="email"--}}
					{{--														   name="email"--}}
					{{--														   type="email"--}}
					{{--														   class="form-control{{ $emailError }}"--}}
					{{--														   placeholder="{{ t('Email') }}"--}}
					{{--														   value="{{ old('email', $user->email) }}"--}}
					{{--													>--}}
					{{--												</div>--}}
					{{--											</div> -->--}}
					{{--                                            <!-- end test -->--}}

					{{--                                            <!-- country_code -->--}}
					{{--                                            /*--}}
					<?php $countryCodeError = (isset($errors) and $errors->has('country_code')) ? ' is-invalid' : ''; ?>
					{{--											<div class="form-group row required">--}}
					{{--												<label class="col-md-2 control-label{{ $countryCodeError }}" for="country_code">--}}
					{{--                                            		{{ t('Your Country') }} <sup>*</sup>--}}
					{{--                                            	</label>--}}
					{{--												<div class="col-md-9">--}}
					{{--													<select name="country_code" class="form-control sselecter{{ $countryCodeError }}">--}}
					{{--														<option value="0" {{ (!old('country_code') or old('country_code')==0) ? 'selected="selected"' : '' }}>--}}
					{{--															{{ t('Select a country') }}--}}
					{{--														</option>--}}
					{{--														@foreach ($countries as $item)--}}
					{{--															<option value="{{ $item->get('code') }}" {{ (old('country_code', $user->country_code)==$item->get('code')) ? 'selected="selected"' : '' }}>--}}
					{{--																{{ $item->get('name') }}--}}
					{{--															</option>--}}
					{{--														@endforeach--}}
					{{--													</select>--}}
					{{--												</div>--}}
					{{--											</div>--}}
					{{--                                            */--}}
					{{--                                            <input name="country_code" type="hidden" value="{{ $user->country_code }}">--}}
					{{--												--}}
					{{--											<!-- phone -->--}}
					<?php $phoneError = (isset($errors) and $errors->has('phone')) ? ' is-invalid' : ''; ?>
					{{--											<div class="form-group row required">--}}
					{{--												<label for="phone" class="col-md-2 col-form-label">{{ t('Phone') }} <sup>*</sup></label>--}}
					{{--												<div class="input-group col-md-9">--}}
					{{--												<!-- start test -->--}}
					{{--													<!-- <div class="input-group-prepend">--}}
					{{--														<span id="phoneCountry" class="input-group-text">{!! getPhoneIcon(old('country_code', $user->country_code)) !!}</span>--}}
					{{--													</div> -->--}}
					{{--												<!-- end test -->--}}
					{{--													<input id="phone" name="phone" type="text" class="form-control{{ $phoneError }}"--}}
					{{--														   placeholder="{{ (!isEnabledField('email')) ? t('Mobile Phone Number') : t('Phone Number') }}"--}}
					{{--														   value="{{ phoneFormat(old('phone', $user->phone), old('country_code', $user->country_code)) }}">--}}
					{{--													--}}
					{{--													<div class="input-group-append">--}}
					{{--														<span class="input-group-text">--}}
					{{--															<input name="phone_hidden" id="phoneHidden" type="checkbox"--}}
					{{--																   value="1" {{ (old('phone_hidden', $user->phone_hidden)=='1') ? 'checked="checked"' : '' }}>&nbsp;--}}
					{{--															<small>{{ t('Hide') }}</small>--}}
					{{--														</span>--}}
					{{--													</div>--}}
					{{--												</div>--}}
					{{--											</div>--}}

					{{--											<div class="form-group row">--}}
					{{--												<div class="offset-md-3 col-md-9"></div>--}}
					{{--											</div>--}}
					{{--											--}}
					{{--											<!-- Button -->--}}
					{{--											<div class="form-group row">--}}
					{{--												<div class="offset-md-3 col-md-9">--}}
					{{--													<button type="submit" class="btn btn-primary">{{ t('Update') }}</button>--}}
					{{--												</div>--}}
					{{--											</div>--}}
					{{--										</form>--}}
					{{--									</div>--}}
					{{--								</div>--}}
					{{--							</div>--}}

					<!-- SETTINGS -->
					{{--							<div class="card card-default">--}}
					{{--								<div class="card-header">--}}
					{{--									<h4 class="card-title"><a href="#settingsPanel" data-toggle="collapse" data-parent="#accordion">{{ t('Settings') }}</a></h4>--}}
					{{--								</div>--}}
					{{--								<div class="panel-collapse collapse {{ (old('panel')=='settingsPanel') ? 'show' : '' }}" id="settingsPanel">--}}
					{{--									<div class="card-body">--}}
					{{--										<form name="settings" class="form-horizontal" role="form" method="POST" action="{{ lurl('account/settings') }}">--}}
					{{--											{!! csrf_field() !!}--}}
					{{--											<input name="_method" type="hidden" value="PUT">--}}
					{{--											<input name="panel" type="hidden" value="settingsPanel">--}}
					{{--										--}}
					{{--											@if (config('settings.single.activation_facebook_comments') and config('services.facebook.client_id'))--}}
					{{--												<!-- disable_comments -->--}}
					{{--												<div class="form-group row">--}}
					{{--													<label class="col-md-2 col-form-label"></label>--}}
					{{--													<div class="col-md-9">--}}
					{{--														<div class="form-check form-check-inline pt-2">--}}
					{{--															<label>--}}
					{{--																<input id="disable_comments"--}}
					{{--																	   name="disable_comments"--}}
					{{--																	   value="1"--}}
					{{--																	   type="checkbox" {{ ($user->disable_comments==1) ? 'checked' : '' }}--}}
					{{--																>--}}
					{{--																{{ t('Disable comments on my ads') }}--}}
					{{--															</label>--}}
					{{--														</div>--}}
					{{--													</div>--}}
					{{--												</div>--}}
					{{--											@endif--}}
					{{--											--}}
					{{--											<!-- password -->--}}
					<?php $passwordError = (isset($errors) and $errors->has('password')) ? ' is-invalid' : ''; ?>
					{{--											<div class="form-group row">--}}
					{{--												<label class="col-md-2 col-form-label">{{ t('New Password') }}</label>--}}
					{{--												<div class="col-md-9">--}}
					{{--													<input id="password" name="password" type="password" class="form-control{{ $passwordError }}" placeholder="{{ t('Password') }}">--}}
					{{--												</div>--}}
					{{--											</div>--}}
					{{--											--}}
					{{--											<!-- password_confirmation -->--}}
					<?php $passwordError = (isset($errors) and $errors->has('password')) ? ' is-invalid' : ''; ?>
					{{--											<div class="form-group row">--}}
					{{--												<label class="col-md-2 col-form-label">{{ t('Confirm Password') }}</label>--}}
					{{--												<div class="col-md-9">--}}
					{{--													<input id="password_confirmation" name="password_confirmation" type="password"--}}
					{{--														   class="form-control{{ $passwordError }}" placeholder="{{ t('Confirm Password') }}">--}}
					{{--												</div>--}}
					{{--											</div>--}}
					{{--											--}}
					{{--											<!-- Button -->--}}
					{{--											<div class="form-group row">--}}
					{{--												<div class="offset-md-3 col-md-9">--}}
					{{--													<button type="submit" class="btn btn-primary">{{ t('Update') }}</button>--}}
					{{--												</div>--}}
					{{--											</div>--}}
					{{--										</form>--}}
					{{--									</div>--}}
					{{--								</div>--}}
					{{--							</div>--}}

					{{--						</div>--}}
					<!--/.row-box End-->

						{{--					</div>--}}
					</div>
					<!--/.page-content-->
				</div>
				<!--/.row-->
			</div>
			<!--/.container-->
		</div>
		<!-- /.main-container -->
		@endsection

		@section('after_styles')
			<link href="{{ url('assets/plugins/bootstrap-fileinput/css/fileinput.min.css') }}" rel="stylesheet">
			@if (config('lang.direction') == 'rtl')
				<link href="{{ url('assets/plugins/bootstrap-fileinput/css/fileinput-rtl.min.css') }}" rel="stylesheet">
			@endif
			<style>
				.krajee-default.file-preview-frame:hover:not(.file-preview-error) {
					box-shadow: 0 0 5px 0 #666666;
				}
				.file-loading:before {
					content: " {{ t('Loading') }}...";
				}
			</style>
			<style>
				/* Avatar Upload */
				.photo-field {
					display: inline-block;
					vertical-align: middle;
				}
				.photo-field .krajee-default.file-preview-frame,
				.photo-field .krajee-default.file-preview-frame:hover {
					margin: 0;
					padding: 0;
					border: none;
					box-shadow: none;
					text-align: center;
				}
				.photo-field .file-input {
					display: table-cell;
					width: 150px;
				}
				.photo-field .krajee-default.file-preview-frame .kv-file-content {
					width: 150px;
					height: 160px;
				}
				.kv-reqd {
					color: red;
					font-family: monospace;
					font-weight: normal;
				}

				.file-preview {
					padding: 2px;
				}
				.file-drop-zone {
					margin: 2px;
				}
				.file-drop-zone .file-preview-thumbnails {
					cursor: pointer;
				}

				.krajee-default.file-preview-frame .file-thumbnail-footer {
					height: 30px;
				}
			</style>
		@endsection

		@section('after_scripts')
			<script src="{{ url('assets/plugins/bootstrap-fileinput/js/plugins/sortable.min.js') }}" type="text/javascript"></script>
			<script src="{{ url('assets/plugins/bootstrap-fileinput/js/fileinput.min.js') }}" type="text/javascript"></script>
			<script src="{{ url('assets/plugins/bootstrap-fileinput/themes/fa/theme.js') }}" type="text/javascript"></script>
			@if (file_exists(public_path() . '/assets/plugins/bootstrap-fileinput/js/locales/'.ietfLangTag(config('app.locale')).'.js'))
				<script src="{{ url('assets/plugins/bootstrap-fileinput/js/locales/'.ietfLangTag(config('app.locale')).'.js') }}" type="text/javascript"></script>
			@endif

			<?php
			$date = $user->created_at;
			$startDate = new DateTime($date);
			$currentDate = new DateTime();

			// $time = $startDate->diff($currentDate);
			// if ($time->format("%y") == 0 && $time->format("%m") == 0 && $time->format("%d") == 0 && $time->format("%H") == 0) {
			// 	$ans = 'Some minutes on the site';
			// }
			// else if ($time->format("%y") == 0 && $time->format("%m") == 0 && $time->format("%d") == 0) {
			// 	if ($time->format("%H") == 1 || $time->format("%H") < 2) $ans = '1 hour on the site';
			// 	else $ans = $time->format("%H") . ' hours on the site';
			// }
			// else if ($time->format("%y") == 0 && $time->format("%m") == 0) {
			// 	if ($time->format("%d") == 1 || $time->format("%d") < 2) $ans = '1 day ' . $time->format("%H") . ' hours on the site';
			// 	else $ans = $time->format("%d") . ' days on the site';
			// }
			// else if ($time->format("%y") == 0) {
			// 	if ($time->format("%m") == 1 || $time->format("%m") < 2) $ans = '1 month ' . $time->format("%d") . ' days on the site';
			// 	else $ans = $time->format("%m") . ' months on the site';
			// }
			// else {
			// 	if ($time->format("%y") == 1 || $time->format("%y") < 2) $ans = '1 year ' . $time->format("%m") . ' months on the site';
			// 	else $ans = $time->format("%y") . ' years on the site';
			// }
			// R.S 
				$joined =  explode("-",substr( $user->created_at, 0 , strpos(date('Y-m-d H:i:s'), " ") )) ;

				switch($joined[1]){
					case "01":
						$month = t("Jan");
					break;
					case "02":
						$month = t("Feb");
					break;
					case "03":
						$month = t("Mar");
					break;
					case "04":
						$month = t("Apr");
					break;
					case "05":
						$month = t("May");
					break;
					case "06":
						$month = t("Jun");
					break;
					case "07":
						$month = t("Jul");
					break;
					case "08":
						$month = t("Aug");
					break;
					case "09":
						$month = t("Sept");
					break;
					case "10":
						$month = t("Oct");
					break;
					case "11":
						$month = t("Nov");
					break;
					case "12":
						$month = t("Dec");
					break;
				}

				$ans =  t('On site since ') .  $month . " " .  $joined[0];
			?>

			<script>
				var photoInfo = '<h6 class="text-muted pb-0">{{ t('Click to select') }}</h6>';
				var footerPreview = '<div class="file-thumbnail-footer pt-2">\n' +
						'<h4 class="user-name">{{ $user->name }}</h4>\n' +
						'<p class="user-onSite">{{ $ans }}</p>\n' +
						'    {actions}\n' +
						'</div>';

				$('#photoField').fileinput(
						{
							theme: "fa",
							language: '{{ config('app.locale') }}',
							@if (config('lang.direction') == 'rtl')
							rtl: true,
							@endif
							overwriteInitial: true,
							showCaption: false,
							showPreview: true,
							allowedFileExtensions: {!! getUploadFileTypes('image', true) !!},
							uploadUrl: '{{ lurl('account/' . $user->id . '/photo') }}',
							uploadAsync: false,
							showBrowse: false,
							showCancel: true,
							showUpload: false,
							showRemove: false,
							minFileSize: {{ (int)config('settings.upload.min_image_size', 0) }}, {{-- in KB --}}
							maxFileSize: {{ (int)config('settings.upload.max_image_size', 1000) }}, {{-- in KB --}}
							browseOnZoneClick: true,
							minFileCount: 0,
							maxFileCount: 1,
							validateInitialCount: true,
							uploadClass: 'btn btn-primary',
							defaultPreviewContent: '<img src="{{ !empty($gravatar) ? $gravatar : url('images/user.png') }}" alt="{{ t('Your Photo or Avatar') }}">' + photoInfo,
							/* Retrieve current images */
							/* Setup initial preview with data keys */
							initialPreview: [
								@if (isset($user->photo) and !empty($user->photo))
										'{{ imgUrl($user->photo) }}'
								@endif
							],
							initialPreviewAsData: true,
							initialPreviewFileType: 'image',
							/* Initial preview configuration */
							initialPreviewConfig: [
								{
									<?php
											// Get the file size
											try {
												$fileSize = (isset($disk) && $disk->exists($user->photo)) ? (int)$disk->size($user->photo) : 0;
											} catch (\Exception $e) {
												$fileSize = 0;
											}
											?>
											@if (isset($user->photo) and !empty($user->photo))
									caption: '{{ last(explode('/', $user->photo)) }}',
									size: {{ $fileSize }},
									url: '{{ lurl('account/' . $user->id . '/photo/delete') }}',
									key: {{ (int)$user->id }}
									@endif
								}
							],

							showClose: false,
							fileActionSettings: {
								// removeIcon: '<i class="far fa-trash-alt"></i>',
								removeIcon: '<a class="change-text">Change</a>',
								// removeClass: 'btn btn-sm btn-danger',
								removeTitle: '{{ t('Remove file') }}'
							},

							elErrorContainer: '#avatarUploadError',
							msgErrorClass: 'alert alert-block alert-danger',

							layoutTemplates: {main2: '{preview} {remove} {browse}', footer: footerPreview}
						});

				/* Auto-upload added file */
				$('#photoField').on('filebatchselected', function(event, data, id, index) {
					if (typeof data === 'object') {
						{{--
                            Display the exact error (If it exists (Before making AJAX call))
                            NOTE: The index '0' is available when the first file size is smaller than the maximum size allowed.
                                  This index does not exist in the opposite case.
                        --}}
						if (data.hasOwnProperty('0')) {
							$(this).fileinput('upload');
							return true;
						}
					}

					return false;
				});

				/* Show upload status message */
				$('#photoField').on('filebatchpreupload', function(event, data, id, index) {
					$('#avatarUploadSuccess').html('<ul></ul>').hide();
				});

				/* Show success upload message */
				$('#photoField').on('filebatchuploadsuccess', function(event, data, previewId, index) {
					/* Show uploads success messages */
					var out = '';
					$.each(data.files, function(key, file) {
						if (typeof file !== 'undefined') {
							var fname = file.name;
							out = out + {!! t('Uploaded file #key successfully') !!};
						}
					});
					$('#avatarUploadSuccess ul').append(out);
					$('#avatarUploadSuccess').fadeIn('slow');

					$('#userImg').attr({'src':$('.photo-field .kv-file-content .file-preview-image').attr('src')});
				});

				// /* Delete picture */

				$('#photoField').on('filepredelete', function() {
					var abort = true;

					// if (confirm("{{ t('Are you sure you want to delete this picture?') }}")) {
					// 	abort = false;
					// }

					return abort;
				});

				$('#photoField').on('filedeleted', function() {
					$('#userImg').attr({'src':"{!! !empty($gravatar) ? $gravatar : url('/images/user.jpg') !!}"});

					var out = "{{ t('Your photo or avatar has been deleted.') }}";
					$('#avatarUploadSuccess').html('<ul><li></li></ul>').hide();
					$('#avatarUploadSuccess ul li').append(out);
					$('#avatarUploadSuccess').fadeIn('slow');
				});

				// R.S
				var modal_userInfo = false;



				//show modal
				$(".file-footer-buttons>button").on("click", function(){
					if(modal_userInfo === false){
						$(".user-info-modal").attr("style","display:block;");
						$('.menu-overly-mask').addClass('is-visible');
						modal_userInfo = true;
					}
				});

				//hide modal
				$(".user-modal-content").on("click",function(){
					var modal_userInfo = false;
				});

				$(".user-info-modal").on("click", function(){
					if(modal_userInfo === true){
						$(".user-info-modal").attr("style","display:none;");
						modal_userInfo = false;
						$('.menu-overly-mask').removeClass('is-visible');
					}
				});

				$(".modalClose").on("click", function(){
					$(".user-info-modal").attr("style","display:none;");
					$('.menu-overly-mask').removeClass('is-visible');
					modal_userInfo = false;
				});

				$("#cancelDelPhoto").on("click", function(){
					$(".user-info-modal").attr("style","display:none;");
					$('.menu-overly-mask').removeClass('is-visible');
					modal_userInfo = false;
				});
				
				$(window).on('load',function(){
					var email = document.getElementById("email").value;
					if(email===""){
						$('#checkEmailModal').modal('show');
					}
    			});
				// $(".btn.btn-dif.btn-grey").hover(function(value){
				// 	console.log(value);
				// 	// value. = ("bgcolr","#6b8096 !important");
				// });

			</script>
@endsection