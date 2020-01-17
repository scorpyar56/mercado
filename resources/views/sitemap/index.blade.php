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

@section('search')
	@parent
@endsection

@section('content')
	@include('common.spacer')
	<div class="main-container inner-page">
		<div class="container">
			<div class="section-content">
				<div class="row">

					@if (Session::has('message'))
						<div class="alert alert-danger">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="unir-close"></i></button>
							{{ session('message') }}
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
					
					@include('home.inc.spacer')
{{--					<h1 class="text-center title-1"><strong>{{ t('Sitemap') }}</strong></h1>--}}
{{--					<hr class="center-block small mt-0">--}}
						
					<div class="col-xl-12">
						<div class="content-box">
							<div class="row-featured-category">
								<!-- <h2>
									<span class="title-2 title-2-dif" style="font-weight: bold;">{{ t('Sitemap') }}</span>
								</h2> -->
								<h2 style="padding-left:15px; padding: 15px 0;">
									<span style="font-size:20px" class="title-3">{{ t('Sitemap') }}</span>
								</h2>
								<div class="col-xl-12" style="padding-left: 0; padding-right: 0;">
									<div class="list-categories-children styled">
										<div class="row">
											@foreach ($cats as $key => $col)
<!--												--><?php //var_dump($col); ?>
												<div class="col-md-4 col-sm-4 {{ (count($cats) == $key+1) ? 'last-column' : '' }}">
													@foreach ($col as $iCat)
														
														<?php
															$randomId = '-' . substr(uniqid(rand(), true), 5, 5);
														?>
														
														<div class="cat-list">
															<h3 class="cat-title rounded cat-title-dif" >
																<a href="{{ \App\Helpers\UrlGen::category($iCat) }}" style="display:inline-flex;align-items:center;margin-top:5px;margin-bottom:5px;color: #212121">
																	{{--																	<i class="{{ $iCat->icon_class ?? 'icon-ok' }}"></i>--}}
{{--																	<i><img style="height: 30px; display:inline-block; padding-right: 5px" src="storage/{{ $iCat->picture }}"></i>--}}
																	<i><img style="height: 30px; display:inline-block; padding-right: 15px" src="{{ url('storage').'/'.$iCat->picture }}"></i>
																	<p style="display:inline-block;">{{ $iCat->name }}</p>
																	<span class="count"></span>
																</a>
																@if (isset($subCats) and $subCats->has($iCat->tid))
																	<span class="btn-cat-collapsed collapsed"
																		  data-toggle="collapse"
																		  data-target=".cat-id-{{ $iCat->id . $randomId }}"
																		  aria-expanded="false"
																		  style="margin-top: 10px;"
																	>
																		<span class="unir-rarrow2"></span>
																	</span>
																@endif
															</h3>
															<ul class="cat-collapse collapse show cat-id-{{ $iCat->id . $randomId }} long-list-home">
																@if (isset($subCats) and $subCats->has($iCat->tid))
																	@foreach ($subCats->get($iCat->tid) as $iSubCat)
																		<li>
																			<a href="{{ \App\Helpers\UrlGen::category($iSubCat, 1) }}" class="subCats" style="text-decoration:none;">
																				{{ $iSubCat->name }}
																			</a>
																		</li>
																	@endforeach
																@endif
															</ul>
														</div>
													@endforeach
												</div>
											@endforeach
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- @if (isset($cities))
						@include('home.inc.spacer')
						<div class="col-xl-12">
							<div class="content-box mb-0">
								<div class="row-featured-category">
									<div class="col-xl-12 box-title">
										<div class="inner">
											<h2>
												<span class="title-3" style="font-weight: bold;">
													<i class="icon-location-2"></i> {{ t('List of Cities in') }} {{ config('country.name') }}
												</span>
											</h2>
										</div>
									</div>
									
									<div class="col-xl-12">
										<div class="list-categories-children">
											<div class="row">
												@foreach ($cities as $key => $cols)
													<ul class="cat-list col-lg-3 col-md-4 col-sm-6 {{ ($cities->count() == $key+1) ? 'cat-list-border' : '' }}">
														@foreach ($cols as $j => $city)
															<li>
																<a href="{{ \App\Helpers\UrlGen::city($city) }}" title="{{ t('Free Ads') }} {{ $city->name }}">
																	<strong>{{ $city->name }}</strong>
																</a>
															</li>
														@endforeach
													</ul>
												@endforeach
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					@endif -->

				</div>
				
				@include('layouts.inc.social.horizontal')
				
			</div>
		</div>
	</div>
@endsection

@section('before_scripts')
	@parent
	<script>
		var maxSubCats = 15;
	</script>
@endsection
