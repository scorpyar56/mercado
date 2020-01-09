<?php
$cities = DB::select('SELECT id, name FROM cities');

// Keywords
$keywords = rawurldecode(request()->get('q'));

// Category
$qCategory = (isset($cat) and !empty($cat)) ? $cat->tid : request()->get('c');

// Location
$qLocation = request()->get('location');
//if (isset($city) and !empty($city)) {
//	$qLocationId = (isset($city->id)) ? $city->id : 0;
//	$qLocation = $city->name;
////	$qAdmin = request()->get('r');
//} else {
//	$qLocationId = request()->get('l');
//	$qLocation = (request()->filled('r')) ? t('area:') . rawurldecode(request()->get('r')) : request()->get('location');
//    $qAdmin = request()->get('r');
//}
?>
<div class="container">
	
	<div class="search-row-wrapper search-row-wrapper-dif">
		<div class="container">
			<?php $attr = ['countryCode' => config('country.icode')]; ?>
			<form id="search" name="search" action="{{ lurl(trans('routes.v-search', $attr), $attr) }}" method="GET">
				<div class="row m-0 flex-fields flex-fields-dif">
{{--					<div class="col-xl-3 col-md-3 col-sm-6 col-xs-6 flexcol-category">--}}

					<div class="flexcol-category padding-2">
						<select name="c" id="catSearch" class="form-control selecter">
							<option value="" {{ ($qCategory=='') ? 'selected="selected"' : '' }}> {{ t('All Categories') }} </option>
							@if (isset($cats) and $cats->count() > 0)
								@foreach ($cats->groupBy('parent_id')->get(0) as $itemCat)
                                    <p> {{ $itemCat->tid }} </p>
									<option {{ ($qCategory==$itemCat->tid) ? ' selected="selected"' : '' }} value="{{ $itemCat->tid }}"> {{ $itemCat->name }} </option>
								@endforeach
							@endif
						</select>
					</div>
					
{{--					<div class="col-xl-auto col-md-auto col-sm-auto col-xs-auto flexcol-search">--}}
					<div class="flexcol-search padding-2">
						<input name="q" class="form-control keyword" type="text" placeholder="{{ t('Search') }}" value="{{ $keywords }}">
					</div>

					<div class="search-col locationicon flexcol-cities padding-2">
						<select name="location" id="locSearch" class="form-control selecter">
							<option value="" {{ ($qLocation=='') ? 'selected="selected"' : '' }}> {{ t('All Cities') }} </option>
							@if (isset($cities) and count($cities) > 0)
								@foreach ($cities as $city)
									<p> {{ $city->id }} </p>
									<option {{ ($qLocation==$city->name) ? ' selected="selected"' : '' }} value="{{ $city->name }}"> {{ $city->name }} </option>
								@endforeach
							@endif
						</select>
					</div>

{{--					<div class="col-xl-3 col-md-3 col-sm-6 col-xs-6 search-col locationicon flexcol-cities">--}}
{{--					<div class="search-col locationicon flexcol-cities padding-2">--}}
{{--						<i class="icon-location-2 icon-append"></i>--}}
{{--						<input type="text" id="locSearch" name="location" class="form-control locinput input-rel searchtag-input has-icon tooltipHere"--}}
{{--							   placeholder="{{ t('All Cities') }}" value="{{ $qLocation }}" title="" data-placement="bottom"--}}
{{--							   data-toggle="tooltip"--}}
{{--							   data-original-title="{{ t('Enter a city name OR a state name with the prefix ":prefix" like: :prefix', ['prefix' => t('area:')]) . t('State Name') }}">--}}
{{--					</div>--}}

{{--					<input type="hidden" id="lSearch" name="l" value="{{ $qLocationId }}">--}}
{{--					<input type="hidden" id="rSearch" name="r" value="{{ $qAdmin }}">--}}
	
{{--					<div class="col-xl-auto col-md-auto col-sm-auto col-xs-auto flexcol-button">--}}
					<div class="flexcol-button padding-2">
						<button class="btn btn-block btn-primary">
{{--							<i class="fa fa-search"></i>--}}
{{--                            <strong>{{ t('Find') }}</strong> --}}
								<img src="/images/search.svg">
						</button>
					</div>

					@if(isset($SubCatId))
						<input id="subCatId" name="sc" type="hidden" value="{{ $SubCatId }}">
					@endif

					{!! csrf_field() !!}

				</div>
			</form>
		</div>
	</div>
</div>

@section('after_scripts')
	@parent
	<script>
		$(document).ready(function () {
			$('#locSearch').on('change', function () {
				if ($(this).val() == '') {
					$('#lSearch').val('');
					$('#rSearch').val('');
				}
			});
			$('#search').on('submit', function () {
				@if(isset($cat->id))
					if(parseInt($('#catSearch').val()) !== {{ $cat->id }}) {
						$('#subCatId').remove();
					}
				@endif
			})
			// Remove title above category section
			$('#select2-catSearch-container').removeAttr('title');
			$('#catSearch').on( "change" ,function(){
				$('#select2-catSearch-container').removeAttr('title');
			});
			// Remove title above location section
			$('#select2-locSearch-container').removeAttr('title');
			$('#locSearch').on( "change" ,function(){
				console.log(1);
				$('#select2-locSearch-container').removeAttr('title');
			});
		});
	</script>
@endsection
