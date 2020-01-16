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
<div class="header">
	<nav id="navshadow" class="navbar fixed-top navbar-site navbar-light bg-light navbar-expand-md" role="navigation">
		<div class="container">
				@include('layouts.inc.navbar')
			<div class="menu-overly-mask"></div>
			<div class="navbar-identity">
				{{-- Logo --}}
				<a href="{{ lurl('/') }}" class="navbar-brand logo logo-title">
					<img src="/storage/app/logo/mercado_logo.svg"
						 alt="{{ strtolower(config('settings.app.app_name')) }}" class="tooltipHere main-logo" title="" data-placement="bottom"
						 data-toggle="tooltip"
{{--						 style="margin-top: calc(80px / 2 - 26.94px / 2)"--}}
						 />
					<img src="/storage/app/logo/mercado_logo_mobile.svg"
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
							<!-- <div class="count-conversations-with-new-messages badge-mob" id="badge-nm">0</div> -->
							<img class="count-conversations-with-new-messages badge-mob" id="badge-notif-nm" src="/images/notifications.svg" alt="">
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
									<!-- <div class="count-conversations-with-new-messages" id="badge-nm">0</div> -->
									<img class="count-conversations-with-new-messages" id="badge-notif-nm" src="/images/notifications.svg" alt="">
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
{{--							 btn-block--}}
							@if (config('settings.single.guests_can_post_ads') != '1')
								<a class="btn btn-add-listing" href="#quickLogin" data-toggle="modal">
									<i class="unir-add"></i><span>{{ t('Place an Ad') }}</span>
								</a>
							@else
								<a class="btn btn-add-listing" href="{{ \App\Helpers\UrlGen::addPost() }}">
									<i class="unir-add"></i><span>{{ t('Place an Ad') }}</span>
								</a>
							@endif
						@else
							<a class="btn btn-add-listing" href="{{ \App\Helpers\UrlGen::addPost() }}">
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
