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
		<div class="container">
			<div class="row">

				@if (Session::has('flash_notification'))
					<div class="col-xl-12">
						<div class="row">
							<div class="col-xl-12">
								@include('flash::message')
							</div>
						</div>
					</div>
				@endif

				<div class="col-md-3 page-sidebar">
					@include('account.inc.sidebar')
				</div>
				<!--/.page-sidebar-->

				<div class="col-md-9 page-content">
					<div class="inner-box inner-box-dif">
						@if ($pagePath=='my-posts')
{{--							<h2 class="title-2"><i class="icon-docs"></i> {{ t('My Ads') }} </h2>--}}
							<h2 class="title-2 title-2-mob"> {{ t('My Ads') }} </h2>
						@elseif ($pagePath=='archived')
{{--							<h2 class="title-2"><i class="icon-folder-close"></i> {{ t('Archived ads') }} </h2>--}}
							<h2 class="title-2 title-2-mob"> {{ t('Archived ads') }} </h2>
						@elseif ($pagePath=='favourite')
{{--							<h2 class="title-2"><i class="icon-heart-1"></i> {{ t('Favourite ads') }} </h2>--}}
							<h2 class="title-2 title-2-mob"> {{ t('Favourite ads') }} </h2>
						@elseif ($pagePath=='pending-approval')
{{--							<h2 class="title-2"><i class="icon-hourglass"></i> {{ t('Pending approval') }} </h2>--}}
							<h2 class="title-2 title-2-mob"> {{ t('Pending approval') }} </h2>
						@else
{{--							<h2 class="title-2"><i class="icon-docs"></i> {{ t('Posts') }} </h2>--}}
							<h2 class="title-2 title-2-mob"> {{ t('Posts') }} </h2>
						@endif

						<div class="table-responsive">
							<form name="listForm" method="POST" action="{{ lurl('account/'.$pagePath.'/delete') }}">
								{!! csrf_field() !!}
								<div class="table-action table-action-dif">
									<label for="checkAll" class="btn-archive">
{{--										<input type="checkbox" id="checkAll">--}}
{{--										{{ t('Select') }}: {{ t('All') }} |--}}
										@if($pagePath=='archived' or $pagePath=='favourite')
											<button type="submit" class="btn btn-sm btn-default delete-action btn-default-cab btn-grey">
												{{ t('Remove') }}
											</button>
										@else
											<button type="submit" class="btn btn-sm btn-default delete-action btn-default-cab btn-grey">
												{{ t('Archive') }}
											</button>
										@endif
									</label>
									<div class="table-search pull-right col-sm-7 table-search-cab">
										<div class="form-group">
											<div class="row row-dif">
{{--												<label class="col-sm-5 control-label text-right">{{ t('Search') }} <br>--}}
{{--													<a title="clear filter" class="clear-filter" href="#clear">[{{ t('clear') }}]</a>--}}
{{--												</label>--}}
												<div class="col-sm-7 searchpan padding-2 search-mob" style="padding-top: 0 !important;">
													<input type="text" class="form-control" id="filter" placeholder="Search">
												</div>
												<div class="flexcol-button padding-2" style="padding-top: 0 !important;">
													<button class="btn btn-block btn-primary">
														{{--<i class="fa fa-search"></i>--}}
														{{--<strong>{{ t('Find') }}</strong> --}}
														<img src="/images/search.svg">
													</button>
												</div>
											</div>
										</div>
									</div>
								</div>
								
								<table id="addManageTable" class="table  table-bordered add-manage-table table demo" data-filter="#filter" data-filter-text-only="true">
									<thead>
									<tr class="border-right table-head">
{{--										<th data-type="numeric" data-sort-initial="true"></th>--}}
										<!-- <th class="cel-borderless"><input type="checkbox" id="checkAll"></th> -->
										<th class="cel-borderless" style="border-top-left-radius: .3rem">
										<div class="cntr">
											<label class="label-cbt">
											<input type="checkbox" id="checkAll" class="invisible">
											<div class="checkbox">
												<svg width="14px" height="14px" viewBox="0 0 14 14">
												<path d="M3,1 L17,1 L17,1 C18.1045695,1 19,1.8954305 19,3 L19,17 L19,17 C19,18.1045695 18.1045695,19 17,19 L3,19 L3,19 C1.8954305,19 1,18.1045695 1,17 L1,3 L1,3 C1,1.8954305 1.8954305,1 3,1 Z"></path>
												<polyline points="4 8 6 10 11 5"></polyline>
												</svg>
											</div>
											</label>
										</div>
										</th>
										<th class="cel-borderless"><span>{{ t('Photo') }}</span></th>
										<th data-sort-ignore="true" class="cel-borderless cel-mob"><span>{{ t('Ads Details') }}</span></th>
										<th data-type="numeric" class="cel-borderless cel-mob"><span>{{ t('Price') }}</span></th>
										@if($pagePath!='favourite')
											<th class="cel-borderless border-right" style="text-align: center;border-top-right-radius: .3rem"><span>{{ t('Edit') }}</span></th>
										@endif
									</tr>
									</thead>
									<tbody class="table-body-border">

										<?php
										if (isset($posts) && $posts->count() > 0):
										foreach($posts as $key => $post):
											
											// Fixed 1
											if ($pagePath == 'favourite') {
												if (isset($post->post)) {
													if (!empty($post->post)) {
														$post = $post->post;
													} else {
														continue;
													}
												} else {
													continue;
												}
											}

											// Fixed 2
											if (!$countries->has($post->country_code)) continue;

											// Get Post's URL
											$postUrl = \App\Helpers\UrlGen::post($post);

											// Get Post's Pictures
											if ($post->pictures->count() > 0) {
												$postImg = imgUrl($post->pictures->get(0)->filename, 'medium');
											} else {
												$postImg = imgUrl(config('larapen.core.picture.default'));
											}

											// Get country flag
											$countryFlagPath = 'images/flags/16/' . strtolower($post->country_code) . '.png';
										?>
										<tr>
											<td style="width:2%" class="add-img-selector cel-borderless col-2">
												<!-- <div class="checkbox">
													<label><input type="checkbox" name="entries[]" value="{{ $post->id }}"></label>
												</div> -->
												<div class="cntr">
													<label class="label-cbt">
														<input type="checkbox" name="entries[]" value="{{ $post->id }}" class="invisible">
														<div class="checkbox">
															<svg width="14px" height="14px" viewBox="0 0 14 14">
															<path d="M3,1 L17,1 L17,1 C18.1045695,1 19,1.8954305 19,3 L19,17 L19,17 C19,18.1045695 18.1045695,19 17,19 L3,19 L3,19 C1.8954305,19 1,18.1045695 1,17 L1,3 L1,3 C1,1.8954305 1.8954305,1 3,1 Z"></path>
															<polyline points="4 8 6 10 11 5"></polyline>
															</svg>
														</div>
													</label>
												</div>
											</td>
											<td style="width:14%" class="add-img-td cel-borderless mobile-cab-cel col-4">
												<a href="{{ $postUrl }}"><img class="img-thumbnail img-fluid" src="{{ $postImg }}" alt="img"></a>
												<div class="mobile-view">
													<p>
														<strong>
															<a href="{{ $postUrl }}" title="{{ $post->title }}">{{ \Illuminate\Support\Str::limit($post->title, 40) }}</a>
														</strong>
														@if (in_array($pagePath, ['my-posts', 'archived', 'pending-approval']))
															@if (isset($post->latestPayment) and !empty($post->latestPayment))
																@if (isset($post->latestPayment->package) and !empty($post->latestPayment->package))
																	<?php
																	if ($post->featured == 1) {
																		$color = $post->latestPayment->package->ribbon;
																		$packageInfo = '';
																	} else {
																		$color = '#ddd';
																		$packageInfo = ' (' . t('Expired') . ')';
																	}
																	?>
																	<i class="fa fa-check-circle tooltipHere" style="color: {{ $color }};" title="" data-placement="bottom"
																	   data-toggle="tooltip" data-original-title="{{ $post->latestPayment->package->short_name . $packageInfo }}"></i>
																@endif
															@endif
														@endif
													</p>
													<p>
														<strong><i class="unir-clock" title="{{ t('Posted On') }}"></i></strong>&nbsp;
														{{ $post->created_at->formatLocalized(config('settings.app.default_datetime_format')) }}
													</p>
													<p>
														<strong><i class="unir-eye" title="{{ t('Visitors') }}"></i></strong> {{ $post->visits ?? 0 }}
														<strong><i class="unir-location" title="{{ t('Located In') }}"></i></strong> {{ !empty($post->city) ? $post->city->name : '-' }}
													<!-- start test -->
													<!-- @if (file_exists(public_path($countryFlagPath)))
														<img src="{{ url($countryFlagPath) }}" data-toggle="tooltip" title="{{ $post->country->name }}">
														@endif -->
														<!-- end test -->
													</p>
												</div>

												<div class="mobile-view">
													<strong>
														@if ($post->price > 0)
															{!! \App\Helpers\Number::money($post->price) !!}
														@else
															{!! \App\Helpers\Number::money('Negotiable') !!}
														@endif
													</strong>
												</div>

											</td>
											@if($pagePath!='favourite')
												<td style="width:43%" class="items-details-td cel-borderless cel-mob">
											@else
												<td style="width:60%" class="items-details-td cel-borderless cel-mob">
											@endif
												<div>
													<p>
														<strong>
														@if($pagePath!='favourite')
															<a href="{{ $postUrl }}" title="{{ $post->title }}">{{ \Illuminate\Support\Str::limit($post->title, 40) }}</a>
														@else
															<a href="{{ $postUrl }}" title="{{ $post->title }}">{{ \Illuminate\Support\Str::limit($post->title, 60) }}</a>
														@endif
														</strong>
														@if (in_array($pagePath, ['my-posts', 'archived', 'pending-approval']))
															@if (isset($post->latestPayment) and !empty($post->latestPayment))
																@if (isset($post->latestPayment->package) and !empty($post->latestPayment->package))
																	<?php
																	if ($post->featured == 1) {
																		$color = $post->latestPayment->package->ribbon;
																		$packageInfo = '';
																	} else {
																		$color = '#ddd';
																		$packageInfo = ' (' . t('Expired') . ')';
																	}
																	?>
																	<i class="fa fa-check-circle tooltipHere" style="color: {{ $color }};" title="" data-placement="bottom"
																	   data-toggle="tooltip" data-original-title="{{ $post->latestPayment->package->short_name . $packageInfo }}"></i>
																@endif
															@endif
														@endif
													</p>
													<span class="info-row">
														<p class="info-row-home">
															<strong><i class="unir-clock" title="{{ t('Posted On') }}"></i></strong>&nbsp;
															{{ $post->created_at->formatLocalized(config('settings.app.default_datetime_format')) }}
														</p>
														<p class="info-row-home">
															<strong><i class="unir-eye" title="{{ t('Visitors') }}"></i></strong>&nbsp; {{ $post->visits ?? 0 }} &nbsp;
															<strong><i class="unir-location" title="{{ t('Located In') }}"></i></strong> {{ !empty($post->city) ? $post->city->name : '-' }}
															<!-- start test -->
															<!-- @if (file_exists(public_path($countryFlagPath)))
																<img src="{{ url($countryFlagPath) }}" data-toggle="tooltip" title="{{ $post->country->name }}">
															@endif -->
															<!-- end test -->
														</p>
													</span>
												</div>
											</td>
											<td style="width:24%" class="price-td cel-borderless cel-mob">
												<div>
													<strong>
														@if ($post->price > 0)
															{!! \App\Helpers\Number::money($post->price) !!}
														@else
															{!! t('Negotiable') !!}
														@endif
													</strong>
												</div>
											</td>
											@if($pagePath!='favourite')
												<td style="width:17%" class="action-td cel-borderless border-right">
													<div>
		{{--												@if (in_array($pagePath, ['my-posts']) and $post->user_id==$user->id and $post->archived==0)--}}
															<p>
																<a class="btn btn-primary btn-sm btn-edit navbar-list-item navbar-list-btn" href="{{ \App\Helpers\UrlGen::editPost($post) }}">
																	<i class="unir-edit edit-icon"></i>
																</a>
															</p>
		{{--												@endif--}}
		{{--												@if (in_array($pagePath, ['my-posts']) and isVerifiedPost($post) and $post->archived==0)--}}
		{{--													<p>--}}
		{{--														<a class="btn btn-warning btn-sm confirm-action" href="{{ lurl('account/'.$pagePath.'/'.$post->id.'/offline') }}">--}}
		{{--															 <i class="icon-eye-off"></i> {{ t('Offline') }}--}}
		{{--															<i class="icon-eye-off"></i> {{ t('Archive') }}--}}
		{{--														</a>--}}
		{{--													</p>--}}
		{{--												@endif--}}
		{{--												@if (in_array($pagePath, ['archived']) and $post->user_id==$user->id and $post->archived==1)--}}
		{{--													<p>--}}
		{{--                                                        <a class="btn btn-info btn-sm confirm-action" href="{{ lurl('account/'.$pagePath.'/'.$post->id.'/repost') }}">--}}
		{{--                                                            <i class="fa fa-recycle"></i> {{ t('Repost') }}--}}
		{{--                                                        </a>--}}
		{{--                                                    </p>--}}
		{{--												@endif--}}
		{{--												<p>--}}
		{{--                                                    <a class="btn btn-danger btn-sm delete-action" href="{{ lurl('account/'.$pagePath.'/'.$post->id.'/delete') }}">--}}
		{{--                                                        <i class="fa fa-trash"></i> {{ t('Delete') }}--}}
		{{--                                                    </a>--}}
		{{--                                                </p>--}}
													</div>
												</td>
											@endif
										</tr>
										<?php endforeach; ?>
										<?php endif; ?>
									</tbody>
								</table>
							</form>
						</div>
                            
                        <nav>
                            {{ (isset($posts)) ? $posts->links() : '' }}
                        </nav>

					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('after_styles')
	<style>
		.action-td p {
			margin-bottom: 5px;
		}
	</style>
@endsection

@section('after_scripts')
	<script src="{{ url('assets/js/footable.js?v=2-0-1') }}" type="text/javascript"></script>
	<script src="{{ url('assets/js/footable.filter.js?v=2-0-1') }}" type="text/javascript"></script>
	<script type="text/javascript">
		$(function () {
			$('#addManageTable').footable().bind('footable_filtering', function (e) {
				var selected = $('.filter-status').find(':selected').text();
				if (selected && selected.length > 0) {
					e.filter += (e.filter && e.filter.length > 0) ? ' ' + selected : selected;
					e.clear = !e.filter;
				}
			});

			$('.clear-filter').click(function (e) {
				e.preventDefault();
				$('.filter-status').val('');
				$('table.demo').trigger('footable_clear_filter');
			});

			$('#checkAll').click(function () {
				checkAll(this);
			});
			
			{{--$('a.delete-action, button.delete-action, a.confirm-action').click(function(e)--}}
			{{--{--}}
			{{--	e.preventDefault(); /* prevents the submit or reload */--}}
			{{--	var confirmation = confirm("{{ t('confirm_this_action') }}");--}}
			{{--	--}}
			{{--	if (confirmation) {--}}
			{{--		if( $(this).is('a') ){--}}
			{{--			var url = $(this).attr('href');--}}
			{{--			if (url !== 'undefined') {--}}
			{{--				redirect(url);--}}
			{{--			}--}}
			{{--		} else {--}}
			{{--			$('form[name=listForm]').submit();--}}
			{{--		}--}}
			{{--		--}}
			{{--	}--}}
			{{--	--}}
			{{--	return false;--}}
			{{--});--}}
		});
	</script>
	<!-- include custom script for ads table [select all checkbox]  -->
	<script>
		function checkAll(bx) {
			var chkinput = document.getElementsByTagName('input');
			for (var i = 0; i < chkinput.length; i++) {
				if (chkinput[i].type == 'checkbox') {
					chkinput[i].checked = bx.checked;
				}
			}
		}
	</script>
@endsection
