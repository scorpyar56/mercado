<!-- this (.mobile-filter-sidebar) part will be position fixed in mobile version -->
<div class="col-md-3 page-sidebar mobile-filter-sidebar xxx pb-4">
    <aside style="height: inherit;">
        <div class="sidebar-modern-inner enable-long-words">
            <ul class="nav navbar-nav navbar-mobile ml-auto navbar-right">
                @if (is_array(LaravelLocalization::getSupportedLocales()) &&
                count(LaravelLocalization::getSupportedLocales()) > 1)
                <!-- Language Selector -->
                <li class="dropdown lang-menu nav-item" style="padding:14px 0px;">
                    <!-- <span style="font-weight:bold;">{{ t('Language') }}</span>
                        <span class="lang-title" data-toggle="dropdown">
                        @if ( Config::get('app.locale') == 'en')
                            {{ 'English' }}
                        @elseif ( Config::get('app.locale') == 'pt' )
                            {{ 'Portigise' }}
                        @endif
                    </span> -->
                    <!-- <ul id="langMenuDropdown" class="dropdown-menu-nav dropdown-menu dropdown-menu-right user-menu" role="menu"> -->
                    <ul id="langMenuDropdown" class="dropdown-menu-nav dropdown-menu-right user-menu" role="menu">
                        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        @if (strtolower($localeCode) != strtolower(config('app.locale')))
                        <?php
					// Controller Parameters
					$attr = [];
					$attr['countryCode'] = config('country.icode');
					if (isset($uriPathCatSlug)) {
						$attr['catSlug'] = $uriPathCatSlug;
						if (isset($uriPathSubCatSlug)) {
							$attr['subCatSlug'] = $uriPathSubCatSlug;
						}
					}
					if (isset($uriPathCityName) && isset($uriPathCityId)) {
						$attr['city'] = $uriPathCityName;
						$attr['id'] = $uriPathCityId;
					}
					if (isset($uriPathUserId)) {
						$attr['id'] = $uriPathUserId;
						if (isset($uriPathUsername)) {
							$attr['username'] = $uriPathUsername;
						}
					}
					if (isset($uriPathUsername)) {
						if (isset($uriPathUserId)) {
							$attr['id'] = $uriPathUserId;
						}
						$attr['username'] = $uriPathUsername;
					}
					if (isset($uriPathTag)) {
						$attr['tag'] = $uriPathTag;
					}
					if (isset($uriPathPageSlug)) {
						$attr['slug'] = $uriPathPageSlug;
					}
					if (\Illuminate\Support\Str::contains(\Route::currentRouteAction(), 'Post\DetailsController')) {
						$postArgs = request()->route()->parameters();
						$attr['slug'] = isset($postArgs['slug']) ? $postArgs['slug'] : getSegment(1);
						$attr['id'] = isset($postArgs['id']) ? $postArgs['id'] : getSegment(2);
					}
					// $attr['debug'] = '1';
					
					// Default
					// $link = LaravelLocalization::getLocalizedURL($localeCode, null, $attr);
					$link = lurl(null, $attr, $localeCode);
                    $localeCode = strtolower($localeCode);
					?>
                        <li class="">
                            <a style="padding-left:5px;" href="{{ $link }}" tabindex="-1" rel="alternate" hreflang="{{ $localeCode }}">
                                <span style="font-weight:bold;">{{ t('Change Language') }}</span><span
                                    class="lang-name"> {!! $properties['native'] !!}</span>
                            </a>
                        </li>
                        @endif
                        @endforeach
                    </ul>
                </li>
                @endif
                @if (!auth()->check())
                <li class="nav-item">
                    @if (config('settings.security.login_open_in_modal'))
                    <a href="#quickLogin" class="nav-link" data-toggle="modal"><i class="unib-user"></i>
                        {{ t('Log In') }}</a>
                    @else
                    <a href="{{ lurl(trans('routes.login')) }}" class="nav-link"><i class="unib-user"></i>
                        {{ t('Log In') }}</a>
                    @endif
                </li>
                <li class="nav-item">
                    <a href="{{ lurl(trans('routes.register')) }}" class="nav-link"><i class="unib-lock"></i> {{ t('Register') }}</a>
                </li>
                <li class="nav-item" style="padding:10px 0px;">
                        <a id="help-down" class="dropdown-toggle" data-toggle="dropdown" style="font-weight:bold;font-size:14px;padding:5px 5px; display:block;"><i class="unib-info"></i> {{ t('Help links') }}<i style="float: right;" class="help-down unir-rarrow2"></i></a>
                        <ul id="userMenuDropdown" class="dropdown-menu navbar-mobile user-menu dropdown-menu-right" style="border: none;">
                            <a href="{{ lurl('page/terms-of-use')}}"><i
                                    class="unir-sheild">&nbsp;</i>{{ t('Terms of Use') }}</a>
                            <a href="{{ lurl('page/privacy-policy')}}"><i
                                    class="unir-note">&nbsp;</i>{{ t('Privacy Policy') }}</a>
                            <a href="{{ lurl('page/posting-rules')}}"><i
                                    class="unir-pencil">&nbsp;</i>{{ t('Posting Rules') }}</a>
                            <a href="{{ lurl('page/tips')}}"><i class="unir-safe">&nbsp;</i>{{ t('Tips for Users') }}</a>
                            <a href="{{ lurl('page/faq')}}"><i
                                    class="unir-cards">&nbsp;</i>{{ t('FAQ') }}</a>
                            <a href="{{ lurl('sitemap')}}"><i style="font-size: 13.6px;"
                                    class="unir-list">&nbsp;</i>{{ t('Sitemap') }}</a>
                            <a href="{{ lurl('contact')}}"><i class="unir-mail">&nbsp;</i>{{ t('Contact Us') }}</a>
                        </ul>
                </li>
                @else
                <li class="nav-item dropdown no-arrow">
                    <!-- <a href="{{ url('/') }}/account" class="dropdown-toggle nav-link" data-toggle="dropdown"> -->
                    <a href="{{ url('/') }}/account" class="nav-link">
                        <i class="unib-user fa"></i>
                        <span>{{ auth()->user()->name }}</span>
                        <!-- <span class="badge badge-pill badge-important count-conversations-with-new-messages">0</span> -->
                        <!-- <div class="count-conversations-with-new-messages" id="badge-nm">0</div> -->
                        <img class="count-conversations-with-new-messages" id="badge-notif-nm" src="/images/notifications.svg" alt="">
                    </a>
                    <!-- <ul id="userMenuDropdown" class="dropdown-menu user-menu dropdown-menu-right shadow-sm">
                            <li class="dropdown-item active">
                                <a href="{{ lurl('account') }}">
                                    <i class="icon-home"></i> {{ t('Personal Home') }}
                                </a>
                            </li>
                            <li class="dropdown-item"><a href="{{ lurl('account/my-posts') }}"><i class="unir-ads"></i>
                                    {{ t('My ads') }} </a></li>
                            <li class="dropdown-item"><a href="{{ lurl('account/favourite') }}"><i
                                        class="unir-heart"></i> {{ t('Favourite ads') }} </a></li>
                            <li class="dropdown-item"><a href="{{ lurl('account/saved-search') }}"><i
                    class="icon-star-circled"></i> {{ t('Saved searches') }} </a></li>
                            <li class="dropdown-item"><a href="{{ lurl('account/pending-approval') }}"><i
                                        class="unir-clock"></i> {{ t('Pending approval') }} </a></li>
                            <li class="dropdown-item"><a href="{{ lurl('account/archived') }}"><i
                                        class="unir-folder"></i> {{ t('Archived ads') }}</a></li>
                            <li class="dropdown-item">
                                <a href="{{ lurl('account/conversations') }}">
                                    <i class="unir-mail"></i> {{ t('Conversations') }}
                                    <span
                                        class="badge badge-pill badge-important count-conversations-with-new-messages">0</span>
                                </a>
                            </li>
                            <li class="dropdown-item"><a href="{{ lurl('account/transactions') }}"><i
                                        class="unir-cards"></i> {{ t('Transactions') }}</a></li>
                        </ul> -->
                </li>
                <li class="nav-item">
                    @if (app('impersonate')->isImpersonating())
                    <a href="{{ route('impersonate.leave') }}" class="nav-link">
                        <i class="unib-exit">&nbsp;</i>{{ t('Leave') }}
                    </a>
                    @else
                    <a href="{{ lurl(trans('routes.logout')) }}" class="nav-link">
                        <i class="unib-exit">&nbsp;</i>{{ t('Log Out') }}
                    </a>
                    @endif
                </li>
                <li class="nav-item" style="padding:10px 0px;">
                        <a id="help-down" class="dropdown-toggle" data-toggle="dropdown" style="font-weight:bold;font-size:14px;padding:5px 5px; display:block;"><i class="unib-info"></i> {{ t('Help links') }}<i style="float: right;" class="help-down unir-rarrow2"></i></a>
                        <ul id="userMenuDropdown" class="dropdown-menu navbar-mobile user-menu dropdown-menu-right" style="border: none;">
                            <a href="{{ lurl('page/terms-of-use')}}"><i
                                    class="unir-note">&nbsp;</i>{{ t('Terms of Use') }}</a>
                            <a href="{{ lurl('page/privacy-policy')}}"><i
                                    class="unir-sheild">&nbsp;</i>{{ t('Privacy Policy') }}</a>
                            <a href="{{ lurl('page/posting-rules')}}"><i
                                    class="unir-pencil">&nbsp;</i>{{ t('Posting Rules') }}</a>
                            <a href="{{ lurl('page/tips')}}"><i class="unir-info">&nbsp;</i>{{ t('Tips for Users') }}</a>
                            <a href="{{ lurl('page/faq')}}"><i
                                    class="unir-search">&nbsp;</i>{{ t('FAQ') }}</a>
                            <a href="{{ lurl('sitemap')}}"><i style="font-size: 13.6px;"
                                    class="unir-list">&nbsp;</i>{{ t('Sitemap') }}</a>
                            <a href="{{ lurl('contact')}}"><i class="unir-mail">&nbsp;</i>{{ t('Contact Us') }}</a>
                        </ul>
                </li>
                @endif
                @if (config('plugins.currencyexchange.installed'))
                @include('currencyexchange::select-currency')
                @endif
                <li class="nav-item postadd desk-vers">
                    @if (!auth()->check())
                    @if (config('settings.single.guests_can_post_ads') != '1')
                    <a class="btn btn-block btn-add-listing" href="#quickLogin" data-toggle="modal">
                        <i class="unir-add" style="font-size: 24px; vertical-align: middle; margin-right:5px;"></i><span
                            style="vertical-align: middle;">{{ t('Place an Ad') }}</span>
                    </a>
                    @else
                    <a class="btn btn-block btn-add-listing" href="{{ \App\Helpers\UrlGen::addPost() }}">
                        <i class="unir-add" style="font-size: 24px; vertical-align: middle; margin-right:5px;"></i><span
                            style="vertical-align: middle;">{{ t('Place an Ad') }}</span>
                    </a>
                    @endif
                    @else
                    <a class="btn btn-block btn-add-listing" href="{{ \App\Helpers\UrlGen::addPost() }}">
                        <i class="unir-add" style="font-size: 24px; vertical-align: middle; margin-right:5px;"></i><span
                            style="vertical-align: middle;">{{ t('Place an Ad') }}</span>
                    </a>
                    @endif
                </li>
            </ul>
            <div style="clear:both"></div>
        </div>
    </aside>
</div>