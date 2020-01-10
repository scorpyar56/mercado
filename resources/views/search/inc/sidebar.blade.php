<!-- this (.mobile-filter-sidebar) part will be position fixed in mobile version -->
<?php
$fullUrl = url(request()->getRequestUri());
$tmpExplode = explode('?', $fullUrl);
$fullUrlNoParams = current($tmpExplode);

function buildList($list)
{

    $result = '<ul class="list-unstyled">';
    foreach ($list as $l) {

        if (isset($l['bold']) && $l['bold']) {
            $name = '<strong>' . $l['name'] . '</strong>';
        } else {
            $name = $l['name'];
        }


        $result .= '<li><a href="' . $l['url'] . '" title="' . $l['name'] . '">'
            . '<span class="title">' . $name . '</span>'
            . '<span class="count">(' . $l['count'] . ')</span>'
            . '</a>';
        if (count($l['children']) > 0) {
            $result .= buildList($l['children']);
        }
    }
    $result .= '</li></ul>';
    return $result;
}

?>
<div class="col-md-3 page-sidebar mobile-filter-sidebar pb-4">
    <aside>
        <div class="sidebar-modern-inner enable-long-words">


            <!-- Date -->
        {{--            <div class="block-title has-arrow sidebar-header">--}}
        {{--                <h5><strong><a href="#"> {{ t('Date Posted') }} </a></strong></h5>--}}
        {{--            </div>--}}
        {{--            <div class="block-content list-filter">--}}
        {{--                <div class="filter-date filter-content">--}}
        {{--                    <ul>--}}
        {{--                        @if (isset($dates) and !empty($dates))--}}
        {{--                            @foreach($dates as $key => $value)--}}
        {{--                                <li>--}}
        {{--                                    <input type="radio" name="postedDate" value="{{ $key }}"--}}
        {{--                                           id="postedDate_{{ $key }}" {{ (request()->get('postedDate')==$key) ? 'checked = "checked"' : '' }}>--}}
        {{--                                    <label for="postedDate_{{ $key }}">{{ $value }}</label>--}}
        {{--                                </li>--}}
        {{--                            @endforeach--}}
        {{--                        @endif--}}
        {{--                        <input type="hidden" id="postedQueryString"--}}
        {{--                               value="{{ httpBuildQuery(request()->except(['page', 'postedDate'])) }}">--}}
        {{--                    </ul>--}}
        {{--                </div>--}}
        {{--            </div>--}}

        @if (isset($cat))
            @if (!in_array($cat->type, ['not - salable']))
                <!-- Price -->

                    <?php
                    if ($id = (request()->get('sc'))) $queryStr = 'cats.id = ' . $id;
                    else if (isset($subCat)) $queryStr = 'cats.id = ' . $subCat->tid;
                    else if (isset($cat)) $queryStr = 'cats.parent_id = ' . $cat->tid;

                    // Default min and max values
                    $placeholderValue = DB::select('SELECT
                                                        MIN(price) AS min,
                                                        MAX(price) AS max
                                                    FROM
                                                        posts
                                                    INNER JOIN categories AS cats ON cats.id = posts.category_id
                                                    INNER JOIN blacklist ON posts.phone != blacklist.entry
                                                    WHERE
                                                        reviewed > 0
                                                    AND ' . $queryStr . '
                                                    AND posts.verified_phone = 1');
                    ?>

                    <div class="block-title has-arrow sidebar-header">
                        <h5>
                            <strong><a href="#">{{ (!in_array($cat->type, ['job-offer', 'job-search'])) ? t('Price range') : t('Salary range') }}</a></strong>
                        </h5>
                    </div>
                    <div class="block-content list-filter">
                        <form role="form" class="form-inline" action="{{ $fullUrlNoParams }}" method="GET">
                            {!! csrf_field() !!}
                            @foreach(request()->except(['page', 'minPrice', 'maxPrice', '_token']) as $key => $value)
                                @if (is_array($value))
                                    @foreach($value as $k => $v)
                                        @if (is_array($v))
                                            @foreach($v as $ik => $iv)
                                                @continue(is_array($iv))
                                                <input type="hidden" name="{{ $key.'['.$k.']['.$ik.']' }}"
                                                       value="{{ $iv }}">
                                            @endforeach
                                        @else
                                            <input type="hidden" name="{{ $key.'['.$k.']' }}" value="{{ $v }}">
                                        @endif
                                    @endforeach
                                @else
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endif
                            @endforeach
                            <?php
                            $minv = floor(request()->get('minPrice') ?? $placeholderValue[0]->min);
                            $maxv = request()->get('maxPrice') ?? $placeholderValue[0]->max;
                            ?>
                            <div class="form-group col-sm-4 no-padding">
                                <input type="number" id="minPrice"
                                       name="minPrice" class="form-control"
                                       value="{{ $minv }}" min="{{ $minv }}"
                                      >
                            </div>
                            <div class="form-group col-sm-1 no-padding text-center hidden-xs"> -</div>
                            <div class="form-group col-sm-4 no-padding">
                                <input type="number" id="maxPrice"
                                       name="maxPrice" class="form-control"
                                       value="{{ $maxv }}" min="{{ $placeholderValue[0]->min }}"">
                            </div>
                            <div class="form-group col-sm-3 no-padding">
                                <button class="btn btn-default pull-right btn-block-xs go-button"
                                        type="submit">{{ t('GO') }}</button>
                            </div>
                        </form>
                        <div style="clear:both"></div>
                    </div>
            @endif
        @endif
        <?php
        // Clear All link
        if (request()->filled('cf')) {
            $clearTitle = t('Clear all the :category\'s filters', ['category' => $cat->name]);
            $clearAll = '<div class="block-title sidebar-header">'
                . '<a class="btn btn-grey btn-block" href="' . qsurl($fullUrlNoParams, request()->except(['page', 'cf']), null, false) . '">'
                . strtoupper(t('Clear all')) . '</a></div>';
            echo $clearAll;
        }
        ?>

        @include('search.inc.fields')

        @if (isset($cat))
            <?php $parentId = ($cat->parent_id == 0) ? $cat->tid : $cat->parent_id;
            ?>

            <!-- SubCategory -->
                <div id="subCatsList">
                    <div class="block-title has-arrow sidebar-header">
                        <h5>
                            <strong>
                                <a href="#"> {{ t('Others Categories') }}
                                    <i class="fa fa-chevron-down"></i>
                                </a>
                            </strong>
                        </h5>
                    </div>
                    <div class="block-content list-filter categories-list">
                        <?php echo(buildList($sidebarCatList)); ?>
                    </div>
                </div>
            <?php $style = 'style = "display: none;"'; ?>
        @endif



        <!-- Category -->
            <div id="catsList" {!! (isset($style)) ? $style : '' !!}>
                <div class="block-title has-arrow sidebar-header">
                    <h5>
                        <strong>
                            <a href="#">{{ t('All Categories') }}
                            </a>
                    </strong></h5>
                </div>
                <div class="block-content list-filter categories-list">
                    <ul class="list-unstyled">
                        @if ($cats->groupBy('parent_id')->has(0))
                            @foreach ($cats->groupBy('parent_id')->get(0) as $iCat)
                                <li>

                                    @if ((isset($uriPathCatSlug) and $uriPathCatSlug == $iCat->slug) or (request()->input('c') == $iCat->tid))
                                        <strong>
                                            <a href="{{ \App\Helpers\UrlGen::category($iCat) }}"
                                               title="{{ $iCat->name }}">
                                                <span class="title">{{ $iCat->name }}</span>
                                                @if( $iCat->name == "Furniture")
                                                        {{  $iCat->name }}
                                                @endif
                                                <span class="count">&nbsp;{{ $countCatPosts->get($iCat->tid)->total ?? 0 }}</span>
                                            </a>
                                        </strong>
                                    @else
                                        <a href="{{ \App\Helpers\UrlGen::category($iCat) }}" title="{{ $iCat->name }}">
                                            <span class="title">{{ $iCat->name }}</span>
                                            <span class="count">&nbsp;{{ $countCatPosts->get($iCat->tid)->total ?? 0 }}</span>
                                        </a>
                                    @endif
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>

            <!-- City -->
            <div class="block-title has-arrow sidebar-header sidebar-location">
                <h5><strong><a href="#">{{ t('Locations') }}</a></strong></h5>
            </div>
            <div class="block-content list-filter locations-list">
                <ul class="browse-list list-unstyled long-list">
                    @if (isset($cities) and $cities->count() > 0)
                        @foreach ($cities as $city)
                            <?php
                            $attr = ['countryCode' => config('country.icode')];
                            $fullUrlLocation = lurl(trans('routes.v-search', $attr), $attr);
                            $locationParams = [
                                'location' => $city->name,
                                'r' => '',
                                'c' => (isset($cat)) ? $cat->tid : '',
                                'sc' => (isset($subCat)) ? $subCat->tid : '',
                            ];

                            $adsNum = isset($ads) ? $ads[$city->name][0]->ads : 0;

                            ?>
                            <li>
                                @if ((isset($uriPathCityId) and $uriPathCityId == $city->id) or (request()->input('location')==$city->name))
                                    <strong>
                                        <a href="{!! qsurl($fullUrlLocation, array_merge(request()->except(['page'] + array_keys($locationParams)), $locationParams), null, false) !!}"
                                           title="{{ $city->name }}">
                                            <span class="title">{{ $city->name }}</span>
{{--                                            <span class="count">{{ '(' . Cache::get('ads' . $city->name)[0]->ads . ')' }}</span>--}}
                                            <span class="count">{{ '(' . $adsNum . ')' }}</span>
                                        </a>
                                    </strong>
                                @else
                                    <a href="{!! qsurl($fullUrlLocation, array_merge(request()->except(['page'] + array_keys($locationParams)), $locationParams), null, false) !!}"
                                       title="{{ $city->name }}">
                                        <span class="title">{{ $city->name }}</span>
{{--                                        <span class="count">{{ '(' . Cache::get('ads' . $city->name)[0]->ads . ')' }}</span>--}}
                                        <span class="count">{{ '(' . $adsNum . ')' }}</span>
                                    </a>
                                @endif
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>

            <div style="clear:both"></div>
        </div>
    </aside>

</div>

@section('after_scripts')
    @parent
    <script>
        var baseUrl = '{{ $fullUrlNoParams }}';

        $(document).ready(function () {
            $('input[type=radio][name=postedDate]').click(function () {
                var postedQueryString = $('#postedQueryString').val();

                if (postedQueryString != '') {
                    postedQueryString = postedQueryString + '&';
                }
                postedQueryString = postedQueryString + 'postedDate=' + $(this).val();

                var searchUrl = baseUrl + '?' + postedQueryString;
                redirect(searchUrl);
            });
        });
    </script>
@endsection
