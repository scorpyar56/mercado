@if (is_array(LaravelLocalization::getSupportedLocales()) && count(LaravelLocalization::getSupportedLocales()) > 1)
<!-- Language Selector -->
<li class="dropdown lang-menu nav-item">
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
    @endif
	@endforeach
{{--        <button type="button" class="btn btn-secondary">--}}
{{--            <span class="lang-title">{{ strtoupper(config('app.locale')) }}</span>--}}
			<a class="btn btn-secondary lang_switcher" href="{{ $link }}" tabindex="-1" rel="alternate" hreflang="{{ $localeCode }}" style="display: flex;align-items: center;justify-content: center;text-decoration: none;color: #212121 !important;">
					<?= strtoupper((config('app.locale') === 'en')? 'pt':'en'); ?> 
			</a>
{{--		</button>--}}
</li>
@endif