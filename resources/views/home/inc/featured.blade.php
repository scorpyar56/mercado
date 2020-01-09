<?php
if (!isset($cacheExpiration)) {
    $cacheExpiration = (int)config('settings.optimization.cache_expiration');
}
?>

@if (isset($featured) and !empty($featured) and !empty($featured->posts))

<div class="container">
    <hr>
    <div class="col-xl-12 content-box layout-section">
        <div class="row row-featured row-featured-category">
            <div class="col-xl-12 box-title">
                <div class="inner">
                    <h4>
                        <span class="similar">{{ t("Similar Ads")}}</span>
                        <a href="{{ $featured->link }}" class="sell-your-item-cart">
                            {{ t('View more') }}&nbsp<i class="unir-rarrow2"></i>
                        </a>
                    </h4>
                </div>
            </div>

            <div class="relative content featured-list-row clearfix cart">
                <div class="large-12 columns">
                    <div class="no-margin featured-list-slider owl-carousel owl-theme">
                        <?php
							foreach($featured->posts as $key => $post):
								if (empty($countries) or !$countries->has($post->country_code)) continue;
			
								// Picture setting
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
									$liveCat = \App\Models\Category::find($post->category_id);
									return $liveCat;
								});

								// Check parent
								if (empty($liveCat->parent_id)) {
									$liveCatParentId = $liveCat->id;
								} else {
									$liveCatParentId = $liveCat->parent_id;
								}

								// Check translation
								$liveCatName = $liveCat->name;
								?>
                        <div class="item">
                            <a href="{{ \App\Helpers\UrlGen::post($post) }}">
                                <span class="item-carousel-thumb">
                                    <img class="lazyload img-thumbnail no-margin" src="{{ $postImg }}"
                                        alt="{{ $post->title }}">
                                </span>
                                <div class="items-details">
                                    <h4 class="item-price-cart ellipsis-text">
                                        @if (isset($liveCat->type))
                                        @if (!in_array($liveCat->type, ['not-salable']))
                                        @if ($post->price > 0)
                                        {!! \App\Helpers\Number::money($post->price) !!}
                                        @else
                                        {!! t("Negotiable") !!}
                                        @endif
                                        @endif
                                        @else
                                        {{ t("Negotiable") }}
                                        @endif
                                    </h4>
                                    <h5 id="h5-col" class="add-title-cart ellipsis-text">
                                        <a href="{{ \App\Helpers\UrlGen::post($post) }}">{{ \Illuminate\Support\Str::limit($post->title, 70) }}
                                        </a>
                                    </h5>
                                    <span class="info-row ellipsis-text ">
                                        @if (isset($liveCatParentId) and isset($liveCatName))
                                        <span class="category">
                                            <div class="inline">
                                                <i class="unir-folder">&ensp;</i>
                                                <a class="info-link" href="{!! qsurl(config('app.locale').'/'.trans('routes.v-search', ['countryCode' => config('country.icode')]), array_merge(request()->except('c'), ['c'=>$liveCatParentId]), null, false) !!}">{{$liveCatName}}</a>
                                            </div>
                                        </span>
                                        @endif
                                        <span class="item-location">
                                                <i class="unir-location">&ensp;</i>
                                                <a class="info-link" href="{!! qsurl(config('app.locale').'/'.trans('routes.v-search', ['countryCode' => config('country.icode')]), array_merge(request()->except(['l', 'location']), ['l'=>$post->city_id]), null, false) !!}">{{$city->name}}</a>
                                                {{ (isset($post->distance)) ? '- ' . round($post->distance, 2) . getDistanceUnit() : '' }}
                                        </span>&nbsp;
                                        <span class="date gray"><i class="unir-clock">&ensp;</i>{{$post->created_at}}</span>
                                    </span>
                            </a>
                        </div>
                        @if (config('plugins.reviews.installed'))
                        @if (view()->exists('reviews::ratings-list'))
                        @include('reviews::ratings-list')
                        @endif
                        @endif

                        <!-- <span class="price">
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
										</span> -->
                    </div>
                    <?php endforeach; ?>

                </div>
            </div>

        </div>
    </div>
</div>
@endif

@section('after_style')
@parent
@endsection

@section('before_scripts')
@parent
<script>
/* Carousel Parameters */
var carouselItems = {{  (isset($featured) and isset($featured -> posts)) ? collect($featured -> posts) -> count(): 0}};
var carouselAutoplay = {{ (isset($featuredOptions) && isset($featuredOptions['autoplay'])) ? $featuredOptions['autoplay']: 'false'}};
var carouselAutoplayTimeout = {{ (isset($featuredOptions) && isset($featuredOptions['autoplay_timeout'])) ? $featuredOptions['autoplay_timeout'] : 1500 }};
</script>
@endsection