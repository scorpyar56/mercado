<?php
if (!isset($cacheExpiration)) {
    $cacheExpiration = (int)config('settings.optimization.cache_expiration');
}
if (config('settings.listing.display_mode') == '.compact-view') {
	$colDescBox = 'col-sm-9';
	$colPriceBox = 'col-sm-3';
} else {
	$colDescBox = 'col-sm-10 col-md-10';
	$colPriceBox = 'col-sm-3';
}
?>
@if (isset($posts) and count($posts) > 0)
	@include('home.inc.spacer')
	<div class="container">
		<div class="col-xl-12 content-box layout-section no-paddings d-none d-sm-block">
			<div class="row row-featured row-featured-category">
				<div class="col-xl-12 box-title no-border">
					<div class="inner" style="display: flex;align-items: center;justify-content: space-between;">
						<h2 style="padding-left:15px;">
							<span class="title-3">{!! t('Home - Latest Ads') !!}</span>
						</h2>
						<h2 style="padding-right:15px;">
							<?php $attr = ['countryCode' => config('country.icode')]; ?>
							<a href="{{ lurl(trans('routes.v-search', $attr), $attr) }}" class="more-arrow-but" >
								<span> {{ t('View more') }} <i class="unir-rarrow2"></i></span>
							</a>
						</h2>
					</div>
				</div>

				<div id="postsList" class="adds-wrapper noSideBar category-list">
					<?php
					foreach($posts as $key => $post):
					if (empty($countries) or !$countries->has($post->country_code)) continue;

					// Get Package Info
					$package = null;
					if ($post->featured == 1) {
						$cacheId = 'package.' . $post->package_id . '.' . config('app.locale');
						$package = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post) {
							$package = \App\Models\Package::findTrans($post->package_id);
							return $package;
						});
					}

					// Get PostType Info
					$cacheId = 'postType.' . $post->post_type_id . '.' . config('app.locale');
					$postType = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post) {
						$postType = \App\Models\PostType::findTrans($post->post_type_id);
						return $postType;
					});
					if (empty($postType)) continue;

					// Get Post's Pictures
					$pictures = \App\Models\Picture::where('post_id', $post->id)->orderBy('position')->orderBy('id');
					if ($pictures->count() > 0) {
						$postImg = imgUrl($pictures->first()->filename, 'medium');
					} else {
						$postImg = imgUrl(config('larapen.core.picture.default'));
					}

					// Get the Post's City
					$cacheId = config('country.code') . '.city.' . $post->city_id;
					$city = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post) {
						$city = \App\Models\City::find($post->city_id);
						return $city;
					});
					if (empty($city)) continue;

					// Convert the created_at date to Carbon object
					$post->created_at = \Date::parse($post->created_at)->timezone(config('timezone.id'));
					$post->created_at = $post->created_at->ago(['syntax' => true]);

					// Category
					$cacheId = 'category.' . $post->category_id . '.' . config('app.locale');
					$liveCat = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post) {
						$liveCat = \App\Models\Category::findTrans($post->category_id);
						return $liveCat;
					});

					// Check parent
					if (empty($liveCat->parent_id)) {
						$liveCatParentId = $liveCat->id;
						$liveCatId = $liveCat->id;
					} else {
						$liveCatParentId = $liveCat->parent_id;
						$liveCatId = $liveCat->id;
					}

					// Check translation
					$liveCatName = $liveCat->name;
					
					?>
					
					<div class="item-list">
						@if (isset($package) and !empty($package))
							@if ($package->ribbon != '')
								<div class="cornerRibbons {{ $package->ribbon }}"><a href="#"> {{ $package->short_name }}</a></div>
							@endif
						@endif

						<div class="row">
							<div class="col-sm-2 no-padding photobox">
								<div class="add-image">
									<!-- @if (isset($package) and !empty($package))
										@if ($package->has_badge == 1)
											<a class="favorite-custom btn btn-danger btn-sm make-favorite"><i class="fa fa-certificate"></i><span> {{ $package->short_name }} </span></a>&nbsp;
										@endif
									@endif
									@if (auth()->check())
										<a class="favorite-custom btn btn-{{ (\App\Models\SavedPost::where('user_id', auth()->user()->id)->where('post_id', $post->id)->count() > 0) ? 'success' : 'default' }} btn-sm make-favorite"
										id="{{ $post->id }}">
											<i class="fa fa-heart"></i><span> {{ t('Save') }} </span>
										</a>
									@else
										<a class="favorite-custom btn btn-default btn-sm make-favorite" id="{{ $post->id }}"><i class="fa fa-heart"></i><span> {{ t('Save') }} </span></a>
									@endif
									<span class="photo-count"><i class="fa fa-camera"></i> {{ $pictures->count() }} </span> -->
									<a href="{{ \App\Helpers\UrlGen::post($post) }}">
										<img class="lazyload img-thumbnail no-margin" src="{{ $postImg }}" alt="{{ $post->title }}">
									</a>
								</div>
							</div>

							<div class="{{ $colDescBox }} add-desc-box">
								<div class="items-details">
									<h4 class="item-price ellipsis-text">
										@if (isset($liveCat->type))
											@if (!in_array($liveCat->type, ['not-salable']))
												@if ($post->price > 0)
													{!! \App\Helpers\Number::money($post->price) !!}
												@else
													{!! t("Negotiable") !!}
												@endif
											@endif
										@else
											{!! t("Negotiable") !!}
										@endif
									</h4>
									<h5 id="h5-col" class="add-title ellipsis-text">
										<a href="{{ \App\Helpers\UrlGen::post($post) }}">{{ \Illuminate\Support\Str::limit($post->title, 150) }} </a>
									</h5>
									<!-- N.M. Hide Additional Infomation -->
									<span class="info-row ellipsis-text">
										@if (isset($liveCatParentId) and isset($liveCatName))
											<span class="category">
												<i class="unir-folder">&ensp;</i>
												<a href="{!! qsurl(config('app.locale').'/'.trans('routes.v-search', ['countryCode' => config('country.icode')]), array_merge(request()->except('c'), ['c'=>$liveCatId]), null, false) !!}" class="info-link">{{$liveCatName}}</a>
											</span><br>
										@endif
										<span class="item-location">
											<i class="unir-location">&ensp;</i>
											<a href="{!! qsurl(config('app.locale').'/'.trans('routes.v-search', ['countryCode' => config('country.icode')]), array_merge(request()->except(['l', 'location']), ['l'=>$post->city_id]), null, false) !!}" class="info-link">{{$city->name}}</a> {{ (isset($post->distance)) ? '- ' . round($post->distance, 2) . getDistanceUnit() : '' }}
										</span>&nbsp;
										<span class="date"><i class="unir-clock">&ensp;</i>{{$post->created_at}}</span>
									</span>
								</div>

								@if (config('plugins.reviews.installed'))
									@if (view()->exists('reviews::ratings-list'))
										@include('reviews::ratings-list')
									@endif
								@endif

							</div>

							<!-- <div class="{{ $colPriceBox }} text-right price-box">
								<h4 class="item-price">
									@if (isset($liveCat->type))
										@if (!in_array($liveCat->type, ['not-salable']))
											@if ($post->price > 0)
												{!! \App\Helpers\Number::money($post->price) !!}
											@else
												{!! \App\Helpers\Number::money('--') !!}
											@endif
										@endif
									@else
										{{ '--' }}
									@endif
								</h4>
								@if (isset($package) and !empty($package))
									@if ($package->has_badge == 1)
										<a class="btn btn-danger btn-sm make-favorite"><i class="fa fa-certificate"></i><span> {{ $package->short_name }} </span></a>&nbsp;
									@endif
								@endif
								@if (auth()->check())
									<a class="btn btn-{{ (\App\Models\SavedPost::where('user_id', auth()->user()->id)->where('post_id', $post->id)->count() > 0) ? 'success' : 'default' }} btn-sm make-favorite"
									   id="{{ $post->id }}">
										<i class="fa fa-heart"></i><span> {{ t('Save') }} </span>
									</a>
								@else
									<a class="btn btn-default btn-sm make-favorite" id="{{ $post->id }}"><i class="fa fa-heart"></i><span> {{ t('Save') }} </span></a>
								@endif
							</div> -->
						</div>
					</div>
					<?php endforeach; ?>

					<div style="clear: both"></div>

					@if (isset($latestOptions) and isset($latestOptions['show_view_more_btn']) and $latestOptions['show_view_more_btn'] == '1')
						<!-- <div class="mb20 text-center separator">
							<?php $attr = ['countryCode' => config('country.icode')]; ?>
							<a href="{{ lurl(trans('routes.v-search', $attr), $attr) }}" >
								<img src="/images/menu_gray.svg" style="width:25px">
							</a>
						</div> -->
					@endif
				</div>

			</div>
		</div>
	</div>
@endif

@section('after_scripts')
    @parent
    <script>

		var display = window.localStorage.getItem('display');
		if (display === "list") {
			listView('.list-view');
		} else {
			gridView('.grid-view');
		}


		/* Favorites Translation */
		var lang = {
			labelSavePostSave: "{!! t('Save ad') !!}",
			labelSavePostRemove: "{!! t('Remove favorite') !!}",
			loginToSavePost: "{!! t('Please log in to save the Ads.') !!}",
			loginToSaveSearch: "{!! t('Please log in to save your search.') !!}",
			confirmationSavePost: "{!! t('Post saved in favorites successfully !') !!}",
			confirmationRemoveSavePost: "{!! t('Post deleted from favorites successfully !') !!}",
			confirmationSaveSearch: "{!! t('Search saved successfully !') !!}",
			confirmationRemoveSaveSearch: "{!! t('Search deleted successfully !') !!}"
		};
    </script>
@endsection
