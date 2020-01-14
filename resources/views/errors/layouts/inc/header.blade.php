<?php
//// Search parameters
//$queryString = (request()->getQueryString() ? ('?' . request()->getQueryString()) : '');
//
//// Check if the Multi-Countries selection is enabled
//$multiCountriesIsEnabled = false;
//$multiCountriesLabel = '';
//
//// Logo Label
//$logoLabel = '';
//if (getSegment(1) != trans('routes.countries')) {
//	$logoLabel = config('settings.app.app_name') . ((!empty(config('country.name'))) ? ' ' . config('country.name') : '');
//}
?>
{{--<div class="header">--}}
{{--	<nav class="navbar fixed-top navbar-site navbar-light bg-light navbar-expand-md" role="navigation">--}}
{{--        <div class="container">--}}
{{--			--}}
{{--			<div class="navbar-identity">--}}
{{--				--}}{{-- Logo --}}
{{--				<a href="{{ url(config('app.locale') . '/') }}" class="navbar-brand logo logo-title">--}}
{{--					<img src="{{ imgUrl(config('settings.app.logo', config('larapen.core.logo')), 'logo') }}" class="tooltipHere main-logo" />--}}
{{--				</a>--}}
{{--				--}}{{-- Toggle Nav (Mobile) --}}
{{--				<button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggler pull-right" type="button">--}}
{{--					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30" width="30" height="30" focusable="false">--}}
{{--						<title>{{ t('Menu') }}</title>--}}
{{--						<path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-miterlimit="10" d="M4 7h22M4 15h22M4 23h22"></path>--}}
{{--					</svg>--}}
{{--				</button>--}}
{{--				--}}{{-- Country Flag (Mobile) --}}
{{--				@if (getSegment(1) != trans('routes.countries'))--}}
{{--					@if (isset($multiCountriesIsEnabled) and $multiCountriesIsEnabled)--}}
{{--						@if (!empty(config('country.icode')))--}}
{{--							@if (file_exists(public_path() . '/images/flags/24/' . config('country.icode') . '.png'))--}}
{{--								<button class="flag-menu country-flag d-block d-md-none btn btn-secondary hidden pull-right" href="#selectCountry" data-toggle="modal">--}}
{{--									<img src="{{ url('images/flags/24/'.config('country.icode').'.png') . getPictureVersion() }}" style="float: left;">--}}
{{--									<span class="caret hidden-xs"></span>--}}
{{--								</button>--}}
{{--							@endif--}}
{{--						@endif--}}
{{--					@endif--}}
{{--				@endif--}}
{{--            </div>--}}
{{--	--}}
{{--			<div class="navbar-collapse collapse">--}}
{{--			<!-- test -->--}}
{{--				<!-- <ul class="nav navbar-nav navbar-left">--}}
{{--					--}}{{-- Country Flag --}}
{{--					@if (getSegment(1) != trans('routes.countries'))--}}
{{--						@if (config('settings.geo_location.country_flag_activation'))--}}
{{--							@if (!empty(config('country.icode')))--}}
{{--								@if (file_exists(public_path() . '/images/flags/32/' . config('country.icode') . '.png'))--}}
{{--									<li class="flag-menu country-flag tooltipHere hidden-xs nav-item" data-toggle="tooltip" data-placement="{{ (config('lang.direction') == 'rtl') ? 'bottom' : 'right' }}">--}}
{{--										@if (isset($multiCountriesIsEnabled) and $multiCountriesIsEnabled)--}}
{{--											<a href="#selectCountry" data-toggle="modal" class="nav-link">--}}
{{--												<img class="flag-icon" src="{{ url('images/flags/32/' . config('country.icode') . '.png') . getPictureVersion() }}">--}}
{{--												<span class="caret hidden-sm"></span>--}}
{{--											</a>--}}
{{--										@else--}}
{{--											<a style="cursor: default;">--}}
{{--												<img class="flag-icon no-caret" src="{{ url('images/flags/32/' . config('country.icode') . '.png') . getPictureVersion() }}">--}}
{{--											</a>--}}
{{--										@endif--}}
{{--									</li>--}}
{{--								@endif--}}
{{--							@endif--}}
{{--						@endif--}}
{{--					@endif--}}
{{--				</ul> -->--}}
{{--			<!-- test end-->--}}

{{--				--}}
{{--				<ul class="nav navbar-nav ml-auto navbar-right">--}}
{{--                    @if (!auth()->check())--}}
{{--                        <li class="nav-item">--}}
{{--							<a href="{{ url(config('app.locale') . '/' . trans('routes.login')) }}" class="nav-link">--}}
{{--								<i class="icon-user fa"></i> {{ t('Log In') }}--}}
{{--							</a>--}}
{{--						</li>--}}
{{--                        <li class="nav-item">--}}
{{--							<a href="{{ url(config('app.locale') . '/' . trans('routes.register')) }}" class="nav-link">--}}
{{--								<i class="icon-user-add fa"></i> {{ t('Register') }}--}}
{{--							</a>--}}
{{--						</li>--}}
{{--                    @else--}}
{{--                        <li class="nav-item">--}}
{{--							@if (app('impersonate')->isImpersonating())--}}
{{--								<a href="{{ route('impersonate.leave') }}" class="nav-link">--}}
{{--									<i class="icon-logout hidden-sm"></i> {{ t('Leave') }}--}}
{{--								</a>--}}
{{--							@else--}}
{{--								<a href="{{ url(config('app.locale') . '/logout') }}" class="nav-link">--}}
{{--									<i class="glyphicon glyphicon-off"></i> {{ t('Log Out') }}--}}
{{--								</a>--}}
{{--							@endif--}}
{{--						</li>--}}
{{--						<li class="nav-item dropdown no-arrow">--}}
{{--							<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">--}}
{{--								<i class="icon-user fa hidden-sm"></i>--}}
{{--                                <span>{{ auth()->user()->name }}</span>--}}
{{--								<i class="icon-down-open-big fa hidden-sm"></i>--}}
{{--                            </a>--}}
{{--							<ul id="userMenuDropdown" class="dropdown-menu user-menu dropdown-menu-right shadow-sm">--}}
{{--                                <li class="dropdown-item active">--}}
{{--                                    <a href="{{ url(config('app.locale') . '/account') }}">--}}
{{--                                        <i class="icon-home"></i> {{ t('Personal Home') }}--}}
{{--                                    </a>--}}
{{--                                </li>--}}
{{--                                <li class="dropdown-item">--}}
{{--									<a href="{{ url(config('app.locale') . '/account/my-posts') }}">--}}
{{--										<i class="icon-th-thumb"></i> {{ t('My ads') }}--}}
{{--									</a>--}}
{{--								</li>--}}
{{--                                <li class="dropdown-item">--}}
{{--									<a href="{{ url(config('app.locale') . '/account/favourite') }}">--}}
{{--										<i class="icon-heart"></i> {{ t('Favourite ads') }}--}}
{{--									</a>--}}
{{--								</li>--}}
{{--                                <li class="dropdown-item">--}}
{{--									<a href="{{ url(config('app.locale') . '/account/saved-search') }}">--}}
{{--										<i class="icon-star-circled"></i> {{ t('Saved searches') }}--}}
{{--									</a>--}}
{{--								</li>--}}
{{--                                <li class="dropdown-item">--}}
{{--									<a href="{{ url(config('app.locale') . '/account/pending-approval') }}">--}}
{{--										<i class="icon-hourglass"></i> {{ t('Pending approval') }}--}}
{{--									</a>--}}
{{--								</li>--}}
{{--                                <li class="dropdown-item">--}}
{{--									<a href="{{ url(config('app.locale') . '/account/archived') }}">--}}
{{--										<i class="icon-folder-close"></i> {{ t('Archived ads') }}--}}
{{--									</a>--}}
{{--								</li>--}}
{{--                                <li class="dropdown-item">--}}
{{--									<a href="{{ url(config('app.locale') . '/account/conversations') }}">--}}
{{--										<i class="icon-mail-1"></i> {{ t('Conversations') }}--}}
{{--									</a>--}}
{{--								</li>--}}
{{--                                <li class="dropdown-item">--}}
{{--									<a href="{{ url(config('app.locale') . '/account/transactions') }}">--}}
{{--										<i class="icon-money"></i> {{ t('Transactions') }}--}}
{{--									</a>--}}
{{--								</li>--}}
{{--                            </ul>--}}
{{--                        </li>--}}
{{--                    @endif--}}
{{--	--}}
{{--					<li class="nav-item postadd">--}}
{{--						@if (!auth()->check())--}}
{{--							@if (config('settings.single.guests_can_post_ads') != '1')--}}
{{--								<a class="btn btn-block btn-border btn-post btn-add-listing" href="#quickLogin" data-toggle="modal">--}}
{{--									<i class="fa fa-plus-circle"></i> {{ t('Add Listing') }}--}}
{{--								</a>--}}
{{--							@else--}}
{{--								<a class="btn btn-block btn-border btn-post btn-add-listing" href="{{ \App\Helpers\UrlGen::addPost(true) }}">--}}
{{--									<i class="fa fa-plus-circle"></i> {{ t('Add Listing') }}--}}
{{--								</a>--}}
{{--							@endif--}}
{{--						@else--}}
{{--							<a class="btn btn-block btn-border btn-post btn-add-listing" href="{{ \App\Helpers\UrlGen::addPost(true) }}">--}}
{{--								<i class="fa fa-plus-circle"></i> {{ t('Add Listing') }}--}}
{{--							</a>--}}
{{--						@endif--}}
{{--					</li>--}}

{{--                    @if (!empty(config('lang.abbr')))--}}
{{--                        @if (is_array(LaravelLocalization::getSupportedLocales()) && count(LaravelLocalization::getSupportedLocales()) > 1)--}}
{{--                            <!-- Language selector -->--}}
{{--							<li class="dropdown lang-menu nav-item">--}}
{{--								<button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">--}}
{{--									<span class="lang-title">{{ strtoupper(config('app.locale')) }}</span>--}}
{{--                                </button>--}}
{{--								<ul id="langMenuDropdown" class="dropdown-menu dropdown-menu-right user-menu shadow-sm" role="menu">--}}
{{--                                    @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)--}}
{{--                                        @if (strtolower($localeCode) != strtolower(config('app.locale')))--}}
											<?php
												// Controller Parameters
//												$attr = [];
//												$attr['countryCode'] = config('country.icode');
//												if (isset($uriPathCatSlug)) {
//													$attr['catSlug'] = $uriPathCatSlug;
//													if (isset($uriPathSubCatSlug)) {
//														$attr['subCatSlug'] = $uriPathSubCatSlug;
//													}
//												}
//												if (isset($uriPathCityName) && isset($uriPathCityId)) {
//													$attr['city'] = $uriPathCityName;
//													$attr['id'] = $uriPathCityId;
//												}
//												if (isset($uriPathUserId)) {
//													$attr['id'] = $uriPathUserId;
//													if (isset($uriPathUsername)) {
//														$attr['username'] = $uriPathUsername;
//													}
//												}
//												if (isset($uriPathUsername)) {
//													if (isset($uriPathUserId)) {
//														$attr['id'] = $uriPathUserId;
//													}
//													$attr['username'] = $uriPathUsername;
//												}
//												if (isset($uriPathTag)) {
//													$attr['tag'] = $uriPathTag;
//												}
//												if (isset($uriPathPageSlug)) {
//													$attr['slug'] = $uriPathPageSlug;
//												}
//
//												// Default
//												// $link = LaravelLocalization::getLocalizedURL($localeCode, null, $attr);
//												$link = lurl(null, $attr, $localeCode);
//												$localeCode = strtolower($localeCode);
											?>
{{--											<li class="dropdown-item">--}}
{{--                                                <a href="{{ $link }}" tabindex="-1" rel="alternate" hreflang="{{ $localeCode }}">--}}
{{--													<span class="lang-name">{{{ $properties['native'] }}}</span>--}}
{{--                                                </a>--}}
{{--                                            </li>--}}
{{--                                        @endif--}}
{{--                                    @endforeach--}}
{{--                                </ul>--}}
{{--                            </li>--}}
{{--                        @endif--}}
{{--                    @endif--}}
{{--                </ul>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </nav>--}}
{{--</div>--}}



<?php
// Search parameters
$queryString = (request()->getQueryString() ? ('?' . request()->getQueryString()) : '');

// Get the Default Language
$cacheExpiration = (isset($cacheExpiration)) ? $cacheExpiration : config('settings.optimization.cache_expiration', 86400);
$defaultLang = Cache::remember('language.default', $cacheExpiration, function () {
	$defaultLang = \App\Models\Language::where('default', 1)->first();
	return $defaultLang;
});

// Check if the Multi-Countries selection is enabled
$multiCountriesIsEnabled = false;
$multiCountriesLabel = '';
if (config('settings.geo_location.country_flag_activation')) {
	if (!empty(config('country.code'))) {
		if (\App\Models\Country::where('active', 1)->count() > 1) {
			$multiCountriesIsEnabled = true;
			$multiCountriesLabel = 'title="' . t('Select a Country') . '"';
		}
	}
}

// Logo Label
$logoLabel = '';
if (getSegment(1) != trans('routes.countries')) {
	$logoLabel = config('settings.app.app_name') . ((!empty(config('country.name'))) ? ' ' . config('country.name') : '');
}
?>
@include('layouts.inc.modal.login')
<div class="header">
	<nav id="navshadow" class="navbar fixed-top navbar-site navbar-light bg-light navbar-expand-md" role="navigation">
		<div class="container">
			@include('layouts.inc.navbar')
			<div class="menu-overly-mask"></div>
			<div class="navbar-identity">
				{{-- Logo --}}
				<a href="{{ lurl('/') }}" class="navbar-brand logo logo-title">
					<img src="/storage/app/logo/mercado_logo.png"
						 alt="{{ strtolower(config('settings.app.app_name')) }}" class="tooltipHere main-logo" title="" data-placement="bottom"
						 data-toggle="tooltip"
							{{--						 style="margin-top: calc(80px / 2 - 26.94px / 2)"--}}
					/>
					<img src="/storage/app/logo/mercado_logo_mobile.png"
						 alt="{{ strtolower(config('settings.app.app_name')) }}" class="tooltipHere main-logo-mob" title="" data-placement="bottom"
						 data-toggle="tooltip"
							{{--						 style="margin-top: calc(80px / 2 - 26.94px / 2)"--}}
					/>
				</a>

				{{--				<a class="btn btn-block btn-border btn-post btn-add-listing" href="{{ \App\Helpers\UrlGen::addPost() }}">--}}
				{{--						<i class="fa fa-plus-circle"></i> {{ t('Add Listing') }}--}}
				{{--					<i><img src="/images/add.png" style="width:25px;height:25px "></i>--}}
				{{--				</a>--}}

				{{-- Toggle Nav (Mobile) --}}
				<button data-target=".navbar-collapse" data-toggle="" class="navbar-toggle navbar-toggler pull-right" type="button">
					{{--					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30" width="30" height="30" focusable="false">--}}
					<title>{{ t('Menu') }}</title>
					{{--						<path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-miterlimit="10" d="M4 7h22M4 15h22M4 23h22"></path>--}}
					<img src="/images/menu.svg">
					{{--					</svg>--}}
					{{--						@if(isset($noReadConversations) && $noReadConversations > 0)--}}
				<!-- <span class="badge badge-pill badge-important count-conversations-with-new-messages badge-dif-imp badge-dif-imp-header"></span> -->
					<div class="count-conversations-with-new-messages badge-mob" id="badge-nm">0</div>
					{{--						@endif--}}
				</button>
				{{--Add Listing Button --}}

				@if (!auth()->check())
					@if (config('settings.single.guests_can_post_ads') != '1')
						<a class="btn btn-border btn-post btn-add-listing mob-vers pull-right" href="#quickLogin" data-toggle="modal">
							<i class="unir-add"></i>
						</a>
					@else
						<a class="btn btn-border btn-post btn-add-listing mob-vers pull-right" href="{{ \App\Helpers\UrlGen::addPost() }}">
							<i class="unir-add"></i>
						</a>
					@endif
				@else
					<a class="btn btn-border btn-post btn-add-listing mob-vers pull-right" href="{{ \App\Helpers\UrlGen::addPost() }}">
						<i class="unir-add"></i>
					</a>
				@endif

				{{-- Country Flag (Mobile) --}}
				@if (getSegment(1) != trans('routes.countries'))
					@if (isset($multiCountriesIsEnabled) and $multiCountriesIsEnabled)
						@if (!empty(config('country.icode')))
							@if (file_exists(public_path() . '/images/flags/24/' . config('country.icode') . '.png'))
								<button class="flag-menu country-flag d-block d-md-none btn btn-secondary hidden pull-right" href="#selectCountry" data-toggle="modal">
									<img src="{{ url('images/flags/24/' . config('country.icode') . '.png') . getPictureVersion() }}" style="float: left;">
									<span class="caret hidden-xs"></span>
								</button>
							@endif
						@endif
					@endif
				@endif
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav ml-auto navbar-right">
					@if (!auth()->check())
						<li class="nav-item">
							@if (config('settings.security.login_open_in_modal'))
								<a href="#quickLogin" class="nav-link" data-toggle="modal">{{ t('Log In') }}</a>
							@else
								<a href="{{ lurl(trans('routes.login')) }}" class="nav-link">{{ t('Log In') }}</a>
							@endif
						</li>
						<li class="nav-item">
							<a style="color: #888888 !important; padding: 14px 0px;" class="nav-link">or</a>
						</li>
						<li class="nav-item">
							<a href="{{ lurl(trans('routes.register')) }}" class="nav-link"> {{ t('Register') }}</a>
						</li>
					@else
						{{--						<li class="nav-item">--}}
						{{--							@if (app('impersonate')->isImpersonating())--}}
						{{--								<a href="{{ route('impersonate.leave') }}" class="nav-link">--}}
						{{--									<!-- <i class="unib-exit"></i>  -->--}}
						{{--									{{ t('Leave') }}--}}
						{{--								</a>--}}
						{{--							@else--}}
						{{--								<a href="{{ lurl(trans('routes.logout')) }}" class="nav-link">--}}
						{{--									<!-- <i class="unib-exit"></i>  -->--}}
						{{--									{{ t('Log Out') }}--}}
						{{--								</a>--}}
						{{--							@endif--}}
						{{--						</li>--}}
						<li class="nav-item dropdown no-arrow">
							{{--                            <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">--}}
							<a href="{{ url('/') }}/account" class="nav-link receive-info" style="display: flex; align-items: center">
								<!-- <i class="unib-user fa"></i> -->
								<span>{{ auth()->user()->name }}</span>
							{{--								@if(isset($noReadConversations) && $noReadConversations > 0)--}}
							<!-- <span class="badge badge-pill badge-important count-conversations-with-new-messages badge-dif-imp badge-dif-imp-header header-badget">0</span> -->
								<div class="count-conversations-with-new-messages" id="badge-nm">0</div>
								{{--								@endif--}}
							</a>
							{{--							<ul id="userMenuDropdown" class="dropdown-menu user-menu dropdown-menu-right shadow-sm">--}}
							{{--								<li class="dropdown-item active">--}}
							{{--									<a href="{{ lurl('account') }}">--}}
							{{--										<i class="icon-home"></i> {{ t('Personal Home') }}--}}
							{{--									</a>--}}
							{{--								</li>--}}
							{{--								<li class="dropdown-item"><a href="{{ lurl('account/my-posts') }}"><i class="unir-ads"></i> {{ t('My ads') }} </a></li>--}}
							{{--								<li class="dropdown-item"><a href="{{ lurl('account/favourite') }}"><i class="unir-heart"></i> {{ t('Favourite ads') }} </a></li>--}}
							{{--								<!-- <li class="dropdown-item"><a href="{{ lurl('account/saved-search') }}"><i class="icon-star-circled"></i> {{ t('Saved searches') }} </a></li> -->--}}
							{{--								<li class="dropdown-item"><a href="{{ lurl('account/pending-approval') }}"><i class="unir-clock"></i> {{ t('Pending approval') }} </a></li>--}}
							{{--								<li class="dropdown-item"><a href="{{ lurl('account/archived') }}"><i class="unir-folder"></i> {{ t('Archived ads') }}</a></li>--}}
							{{--								<li class="dropdown-item">--}}
							{{--									<a href="{{ lurl('account/conversations') }}">--}}
							{{--										<i class="unir-mail"></i> {{ t('Conversations') }}--}}
							{{--										<span class="badge badge-pill badge-important count-conversations-with-new-messages">0</span>--}}
							{{--									</a>--}}
							{{--								</li>--}}
							{{--								<li class="dropdown-item"><a href="{{ lurl('account/transactions') }}"><i class="unir-cards"></i> {{ t('Transactions') }}</a></li>--}}
							{{--							</ul>--}}
						</li>
					@endif

					@if (config('plugins.currencyexchange.installed'))
						@include('currencyexchange::select-currency')
					@endif

					<li class="nav-item postadd desk-vers">
						@if (!auth()->check())
							@if (config('settings.single.guests_can_post_ads') != '1')
								<a class="btn btn-block btn-add-listing" href="#quickLogin" data-toggle="modal">
									<i class="unir-add"></i><span>{{ t('Place an Ad') }}</span>
								</a>
							@else
								<a class="btn btn-block btn-add-listing" href="{{ \App\Helpers\UrlGen::addPost() }}">
									<i class="unir-add"></i><span>{{ t('Place an Ad') }}</span>
								</a>
							@endif
						@else
							<a class="btn btn-block btn-add-listing" href="{{ \App\Helpers\UrlGen::addPost() }}">
								<i class="unir-add"></i><span>{{ t('Place an Ad') }}</span>
							</a>
						@endif
					</li>

					@include('layouts.inc.menu.select-language-new')

				</ul>
			</div>


		</div>
	</nav>
</div>

@section('after_scripts')
	<script src="{{ url('assets/js/script.js') }}" type="text/javascript"></script>
@endsection



