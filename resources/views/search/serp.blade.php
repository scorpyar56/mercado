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
<?php
	$fullUrl = rawurldecode(url(request()->getRequestUri()));
	$tmpExplode = explode('?', $fullUrl);
	$fullUrlNoParams = current($tmpExplode);
?>
@extends('layouts.master')

@section('after_styles')
	@parent
	<link type="text/css" href="{{ url('assets/css/bootstrap-treeview.min.css') }}" rel="stylesheet">
@endsection



@section('search')
	@parent
	@include('search.inc.form')
@endsection

@section('content')

	<!-- R.S. -->
	@if (Session::has('flash_notification'))
		@include('common.spacer')
		<?php $paddingTopExists = true; ?>
		<div class="container">
			<div class="row">
				<div class="col-xl-12">
					@include('flash::message')
				</div>
			</div>
		</div>
	@endif

	<div class="main-container">

		@include('search.inc.breadcrumbs')
		@include('search.inc.categories')
		<?php if (\App\Models\Advertising::where('slug', 'top')->count() > 0): ?>
			@include('layouts.inc.advertising.top', ['paddingTopExists' => true])
		<?php
			$paddingTopExists = false;
		else:
			if (isset($paddingTopExists) and $paddingTopExists) {
				$paddingTopExists = false;
			}
		endif;
		?>
		@include('common.spacer')
		<div class="container">
			<!-- <div class="row" style="margin-top:16px;"> -->
			<div class="row">

				<!-- Sidebar -->
                @if (config('settings.listing.left_sidebar'))
                    @include('search.inc.sidebar')
                    <?php $contentColSm = 'col-md-9'; ?>
                @else
                    <?php $contentColSm = 'col-md-12'; ?>
                @endif

				<!-- Content -->
				<div class="{{ $contentColSm }} page-content col-thin-left no-padding">
					<div class="category-list{{ ($contentColSm == 'col-md-12') ? ' noSideBar' : '' }}">
						<div class="tab-box">

							<!-- Nav tabs -->
							<!--
							<ul id="postType" class="nav nav-tabs add-tabs tablist" role="tablist">
                                <?php
                                $liClass = 'class="nav-item"';
                                $spanClass = 'alert-danger';
                                if (!request()->filled('type') or request()->get('type') == '') {
                                    $liClass = 'class="nav-item active"';
                                    $spanClass = 'badge-danger';
                                }
                                ?>
								<li {!! $liClass !!}>
									<a href="{!! qsurl($fullUrlNoParams, request()->except(['page', 'type']), null, false) !!}" role="tab" data-toggle="tab" class="nav-link">
										{{ t('All Ads') }} <span class="badge badge-pill {!! $spanClass !!}">{{ $count->get('all') }}</span>
									</a>
								</li>
                                @if (!empty($postTypes))
                                    @foreach ($postTypes as $postType)
                                        <?php
                                            $postTypeUrl = qsurl($fullUrlNoParams, array_merge(request()->except(['page']), ['type' => $postType->tid]), null, false);
                                            $postTypeCount = ($count->has($postType->tid)) ? $count->get($postType->tid) : 0;
                                        ?>
                                        @if (request()->filled('type') && request()->get('type') == $postType->tid)
                                            <li class="nav-item active">
                                                <a href="{!! $postTypeUrl !!}" role="tab" data-toggle="tab" class="nav-link">
                                                    {{ $postType->name }}
                                                    <span class="badge badge-pill badge-danger">
                                                        {{ $postTypeCount }}
                                                    </span>
                                                </a>
                                            </li>
                                        @else
                                            <li class="nav-item">
                                                <a href="{!! $postTypeUrl !!}" role="tab" data-toggle="tab" class="nav-link">
                                                    {{ $postType->name }}
                                                    <span class="badge badge-pill alert-danger">
                                                        {{ $postTypeCount }}
                                                    </span>
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                @endif
							</ul>
                             -->



						</div>

						@if ($count->get('all') > 0)
						<div class="listing-filter" style="margin: 0px 15px;">
								<div class="pull-left col-xs-6">
									<div class="tab-filter">
										<select id="orderBy" class="niceselecter select-sort-by" data-style="btn-select" data-width="auto">
											<!-- <option value="{!! qsurl($fullUrlNoParams, request()->except(['orderBy']), null, false) !!}">{{ t('Sort by') }}</option> -->
											<option{{ (request()->get('orderBy')=='date') ? ' selected="selected"' : '' }}
													value="{!! qsurl($fullUrlNoParams, array_merge(request()->except('orderBy'), ['orderBy'=>'date']), null, false) !!}">
													{{ t('Sort by') }} {{ t('Date') }}
											</option>
											<option{{ (request()->get('orderBy')=='priceAsc') ? ' selected="selected"' : '' }}
													value="{!! qsurl($fullUrlNoParams, array_merge(request()->except('orderBy'), ['orderBy'=>'priceAsc']), null, false) !!}">
											{{ t('Price : Low to High') }}
											</option>
											<option{{ (request()->get('orderBy')=='priceDesc') ? ' selected="selected"' : '' }}
													value="{!! qsurl($fullUrlNoParams, array_merge(request()->except('orderBy'), ['orderBy'=>'priceDesc']), null, false) !!}">
											{{ t('Price : High to Low') }}
											</option>
{{--											<option{{ (request()->get('orderBy')=='relevance') ? ' selected="selected"' : '' }}--}}
{{--													value="{!! qsurl($fullUrlNoParams, array_merge(request()->except('orderBy'), ['orderBy'=>'relevance']), null, false) !!}">--}}
{{--											{{ t('Relevance') }}--}}
{{--											</option>--}}

											@if (isset($isCitySearch) and $isCitySearch and isset($distanceRange) and !empty($distanceRange))
												@foreach($distanceRange as $key => $value)
													<option{{ (request()->get('distance', config('settings.listing.search_distance_default', 100))==$value) ? ' selected="selected"' : '' }}
															value="{!! qsurl($fullUrlNoParams, array_merge(request()->except('distance'), ['distance' => $value]), null, false) !!}">
													{{ t('Around :distance :unit', ['distance' => $value, 'unit' => getDistanceUnit()]) }}
													</option>
												@endforeach
											@endif
											@if (config('plugins.reviews.installed'))
												<option{{ (request()->get('orderBy')=='rating') ? ' selected="selected"' : '' }}
														value="{!! qsurl($fullUrlNoParams, array_merge(request()->except('orderBy'), ['orderBy'=>'rating']), null, false) !!}">
												{{ trans('reviews::messages.Rating') }}
												</option>
											@endif
										</select>
									</div>
								</div>

							@if ($paginator->getCollection()->count() > 0)
								<div class="pull-right col-xs-6 text-right listing-view-action action-big-only">
									<span class="list-view">
										<i><svg width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg"><path d="M6 1.644A.65.65 0 0 1 6.645 1h10.709c.356 0 .645.278.645.644v.712a.65.65 0 0 1-.645.644H6.645A.638.638 0 0 1 6 2.356v-.712zm0 7A.65.65 0 0 1 6.645 8h10.709c.356 0 .645.278.645.644v.712a.65.65 0 0 1-.645.644H6.645A.638.638 0 0 1 6 9.356v-.712zm.001 6.944a.65.65 0 0 1 .645-.644h10.709c.356 0 .645.278.645.644v.712a.65.65 0 0 1-.645.644H6.646a.638.638 0 0 1-.645-.644v-.712zM0 .643C0 .288.288 0 .643 0h2.714C3.712 0 4 .288 4 .643v2.714A.643.643 0 0 1 3.357 4H.643A.643.643 0 0 1 0 3.357V.643zm0 7C0 7.288.288 7 .643 7h2.714c.355 0 .643.288.643.643v2.714a.643.643 0 0 1-.643.643H.643A.643.643 0 0 1 0 10.357V7.643zm.001 7c0-.355.288-.643.643-.643h2.715c.354 0 .642.288.642.643v2.714A.643.643 0 0 1 3.36 18H.644A.643.643 0 0 1 0 17.357v-2.714z" fill="#90A4AE" fill-rule="evenodd"/></svg></i>
									</span>
{{--									<span class="compact-view"><i class="unir-toggles"></i></span>--}}
									<span class="grid-view active">
										<i><svg width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg"><path d="M10 11.99c0-1.097.897-1.99 1.99-1.99h4.02c1.097 0 1.99.897 1.99 1.99v4.02c0 1.097-.897 1.99-1.99 1.99h-4.02A1.996 1.996 0 0 1 10 16.01v-4.02zm2 .51v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5zm-12-.51C0 10.894.897 10 1.99 10h4.02C7.106 10 8 10.897 8 11.99v4.02C8 17.106 7.103 18 6.01 18H1.99A1.996 1.996 0 0 1 0 16.01v-4.02zm2 .51v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5zm8-10.51C10 .894 10.897 0 11.99 0h4.02C17.106 0 18 .897 18 1.99v4.02C18 7.106 17.103 8 16.01 8h-4.02A1.996 1.996 0 0 1 10 6.01V1.99zm2 .51v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5zM0 1.99C0 .894.897 0 1.99 0h4.02C7.106 0 8 .897 8 1.99v4.02C8 7.106 7.103 8 6.01 8H1.99A1.996 1.996 0 0 1 0 6.01V1.99zm2 .51v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5z" fill="#90A4AE"/></svg></i>
									</span>
								</div>
							@endif

							<div style="clear:both"></div>
						</div>
						@endif
<!-- 
						<script>

						</script> -->

						<!-- Mobile Filter Bar -->
						<div class="mobile-filter-bar col-xl-12">
							<ul class="list-unstyled list-inline no-margin no-padding" >

								<li class="mobile-dropdown" >
									<div class="dropdown">						
										<a data-toggle="dropdown"class="dropdown-toggle">
											<span id="mobileCurrent">
												{{ t('Sort by') }}  {{ t('Date') }}
											</span> 
											<i class="unir-rarrow2 icon"></i>
										</a>
                                           
										<!-- <a data-toggle="dropdown" class="dropdown-toggle">{{ t('Sort by') }} <i class="unir-larrow2"></i></a> -->
										<ul class="dropdown-menu">
											<li>
												<a href="{!! qsurl($fullUrlNoParams, array_merge(request()->except('orderBy'), ['orderBy'=>'date']), null, false) !!}" rel="nofollow"
												class="{{ (request()->get('orderBy')=='date') ? "link-bold" : "" }}">
														{{ t('Sort by') }} {{ t('Date') }}
												</a>
											</li>
											<li>
												<a href="{!! qsurl($fullUrlNoParams, array_merge(request()->except('orderBy'), ['orderBy'=>'priceAsc']), null, false) !!}" rel="nofollow"
                                                class="{{ (request()->get('orderBy')=='priceAsc') ? "link-bold" : "" }}">
													{{ t('Price : Low to High') }}
												</a>
											</li>
											<li>
												<a href="{!! qsurl($fullUrlNoParams, array_merge(request()->except('orderBy'), ['orderBy'=>'priceDesc']), null, false) !!}" rel="nofollow"
                                                    class="{{ (request()->get('orderBy')=='priceDesc') ? "link-bold" : "" }}">
                                                    {{ t('Price : High to Low') }}
												</a>
											</li>
{{--											<li>--}}
{{--												<a href="{!! qsurl($fullUrlNoParams, array_merge(request()->except('orderBy'), ['orderBy'=>'relevance']), null, false) !!}" rel="nofollow"--}}
{{--                                                    class="{{ (request()->get('orderBy')=='relevance') ? "link-bold" : "" }}">--}}
{{--                                                    {{ t('Relevance') }}--}}
{{--												</a>--}}
{{--											</li>--}}
											<!-- <li>
												<a href="{!! qsurl($fullUrlNoParams, array_merge(request()->except('orderBy'), ['orderBy'=>'date']), null, false) !!}" rel="nofollow"
                                                    class="{{ (request()->get('orderBy')=='date') ? "link-bold" : "" }}">
                                                    {{ t('Date') }}
												</a>
											</li> -->
											@if (isset($isCitySearch) and $isCitySearch and isset($distanceRange) and !empty($distanceRange))
												@foreach($distanceRange as $key => $value)
													<li>
														<a href="{!! qsurl($fullUrlNoParams, array_merge(request()->except('distance'), ['distance' => $value]), null, false) !!}" rel="nofollow">
															{{ t('Around :distance :unit', ['distance' => $value, 'unit' => getDistanceUnit()]) }}
														</a>
													</li>
												@endforeach
											@endif
											@if (config('plugins.reviews.installed'))
												<li>
													<a href="{!! qsurl($fullUrlNoParams, array_merge(request()->except('orderBy'), ['orderBy'=>'rating']), null, false) !!}"
													   rel="nofollow">
														{{ trans('reviews::messages.Rating') }}
													</a>
												</li>
											@endif
										</ul>
									</div>
								</li>
								<li id="view">
									<div class="listing-view-action">
										<!-- <span class="list-view"><i class="unir-list"></i></span>
										<span class="grid-view active"><i class="unir-table"></i></span> -->
										<span class="list-view">
										<i><svg width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg"><path d="M6 1.644A.65.65 0 0 1 6.645 1h10.709c.356 0 .645.278.645.644v.712a.65.65 0 0 1-.645.644H6.645A.638.638 0 0 1 6 2.356v-.712zm0 7A.65.65 0 0 1 6.645 8h10.709c.356 0 .645.278.645.644v.712a.65.65 0 0 1-.645.644H6.645A.638.638 0 0 1 6 9.356v-.712zm.001 6.944a.65.65 0 0 1 .645-.644h10.709c.356 0 .645.278.645.644v.712a.65.65 0 0 1-.645.644H6.646a.638.638 0 0 1-.645-.644v-.712zM0 .643C0 .288.288 0 .643 0h2.714C3.712 0 4 .288 4 .643v2.714A.643.643 0 0 1 3.357 4H.643A.643.643 0 0 1 0 3.357V.643zm0 7C0 7.288.288 7 .643 7h2.714c.355 0 .643.288.643.643v2.714a.643.643 0 0 1-.643.643H.643A.643.643 0 0 1 0 10.357V7.643zm.001 7c0-.355.288-.643.643-.643h2.715c.354 0 .642.288.642.643v2.714A.643.643 0 0 1 3.36 18H.644A.643.643 0 0 1 0 17.357v-2.714z" fill="#90A4AE" fill-rule="evenodd"/></svg></i>
									</span>
									<span class="grid-view active">
										<i><svg width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg"><path d="M10 11.99c0-1.097.897-1.99 1.99-1.99h4.02c1.097 0 1.99.897 1.99 1.99v4.02c0 1.097-.897 1.99-1.99 1.99h-4.02A1.996 1.996 0 0 1 10 16.01v-4.02zm2 .51v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5zm-12-.51C0 10.894.897 10 1.99 10h4.02C7.106 10 8 10.897 8 11.99v4.02C8 17.106 7.103 18 6.01 18H1.99A1.996 1.996 0 0 1 0 16.01v-4.02zm2 .51v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5zm8-10.51C10 .894 10.897 0 11.99 0h4.02C17.106 0 18 .897 18 1.99v4.02C18 7.106 17.103 8 16.01 8h-4.02A1.996 1.996 0 0 1 10 6.01V1.99zm2 .51v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5zM0 1.99C0 .894.897 0 1.99 0h4.02C7.106 0 8 .897 8 1.99v4.02C8 7.106 7.103 8 6.01 8H1.99A1.996 1.996 0 0 1 0 6.01V1.99zm2 .51v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5z" fill="#90A4AE"/></svg></i>
									</span>
									</div>
								</li>
                                @if (config('settings.listing.left_sidebar'))
                                    <li class="filter-toggle" id="filter" style="float:right;">
                                        <a class="filter">
										<!-- <i class="icon icon-filter "></i> -->
											{{ t("Filtres")}}
                                        </a>
                                    </li>
                                @endif
							</ul>
						</div>
						<!-- Mobile Filter bar End-->

						<!-- <div id="postsList" class="adds-wrapper row no-margin d-flex justify-content-between"> -->
						<div id="postsList" class="adds-wrapper row no-margin">

							@include('search.inc.posts')
						</div>

						<!-- <div class="tab-box save-search-bar text-center">
							@if (request()->filled('q') and request()->get('q') != '' and $count->get('all') > 0)
								<a name="{!! qsurl($fullUrlNoParams, request()->except(['_token', 'location']), null, false) !!}" id="saveSearch"
								   count="{{ $count->get('all') }}">
									<i class="icon-star-empty"></i> {{ t('Save Search') }}
								</a>
							@else
								<a href="#"> &nbsp; </a>
							@endif
						</div> -->
					</div>

					<nav class="pagination-bar mb-5 pagination-sm" aria-label="">
						{!! $paginator->appends(request()->query())->render() !!}
					</nav>

					<!-- <div class="post-promo text-center mb-5">
						<h2> {{ t('Do have anything to sell or rent?') }} </h2>
						<h5>{{ t('Sell your products and services online FOR FREE. It\'s easier than you think !') }}</h5>
						@if (!auth()->check() and config('settings.single.guests_can_post_ads') != '1')
							<a href="#quickLogin" class="btn btn-border btn-post btn-add-listing" data-toggle="modal">{{ t('Start Now!') }}</a>
						@else
							<a href="{{ \App\Helpers\UrlGen::addPost() }}" class="btn btn-border btn-post btn-add-listing">{{ t('Start Now!') }}</a>
						@endif
					</div> -->

				</div>

				<div style="clear:both;"></div>

				<!-- Advertising -->
				@include('layouts.inc.advertising.bottom')

			</div>
		</div>
	</div>
@endsection

@section('modal_location')
	@include('layouts.inc.modal.location')
@endsection

@section('after_scripts')
	<script>
		$(document).ready(function () {
			$('#postType a').click(function (e) {
				e.preventDefault();
				var goToUrl = $(this).attr('href');
				redirect(goToUrl);
			});
			$('#orderBy').change(function () {
				var goToUrl = $(this).val();
				redirect(goToUrl);
			});

			if($(window).width() > 992){
				$(".nice-select").attr("style","display:initial;");
			}
			
			$(".nice-select").each(function( key, value){
				if(key>0)value.remove();
			});

			$('.nice-select.niceselecter.select-sort-by').append("<span class='unir-rarrow2 icon'></span>");

			// $(".nice-select").on("click",function(){
			// 	if($('.nice-select .unir-rarrow2.icon').attr("style") !== 'transform: rotate(90deg);'){
			// 		$('.nice-select .unir-rarrow2.icon').attr("style", "transform: rotate(90deg);");
			// 	}
			// 	else{
			// 		$('.nice-select .unir-rarrow2.icon').attr("style", "transform: rotate(0deg);");
			// 	}
			// });

			// $(".mobile-dropdown a.dropdown-toggle").on("click",function(){
			// 	$(".mobile-dropdown .link-bold").parent().attr("class","mobile-dropdown-chosen");
			// });

			// if( $(".mobile-dropdown .link-bold").text() ){
			// 	console.log( $(".mobile-dropdown .link-bold").text() );
			// 	$("#mobileCurrent").html($(".mobile-dropdown .link-bold").text());
			// }
		});

		$(".unir-rarrow2.icon").on("click",function(){
			if($('.mobile-dropdown .unir-rarrow2').attr("style") !='transform: rotate(90deg);' ){
				console.log('xxx');
				$('.mobile-dropdown .unir-rarrow2').attr("style", "transform: rotate(90deg);");
			}
			else {
				console.log('aaa');
				$('.mobile-dropdown .unir-rarrow2').attr("style", "transform: rotate(0deg);");
			}
		});
		
		$("#mobileCurrent").on("click",function(){
			if($('.mobile-dropdown .unir-rarrow2').attr("style") !='transform: rotate(90deg);' ){
				console.log('xxx');
				$('.mobile-dropdown .unir-rarrow2').attr("style", "transform: rotate(90deg);");
			}
			else {
				console.log('aaa');
				$('.mobile-dropdown .unir-rarrow2').attr("style", "transform: rotate(0deg);");
			}
		});

		$('.dropdown-menu').on("click", function(){
			console.log('list');
			$('.mobile-dropdown .unir-rarrow2').attr("style", "transform: rotate(0deg);");
		});

		@if (config('settings.optimization.lazy_loading_activation') == 1)
		$(document).ready(function () {
			$('#postsList').each(function () {
				var $masonry = $(this);
				var update = function () {
					$.fn.matchHeight._update();
				};
				$('.item-list', $masonry).matchHeight();
				this.addEventListener('load', update, true);
			});
		});
		@endif

		$(document).ready(function () {
			$('.grid-view').click(function () {
				window.localStorage.setItem('display', 'grid');
			});
			$('.list-view').click(function () {
				window.localStorage.setItem('display', 'list');
			});
		});

		$(document).ready(function () {
			var numOfItemLists = $('.item-list').length;
			if (numOfItemLists >= {{ config('settings.listing')["items_per_page"] }}) {
				$('.post-promo').hide();
			}
		})
	</script>
@endsection
