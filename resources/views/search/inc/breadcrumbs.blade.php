<div class="container">
	<nav aria-label="breadcrumb" role="navigation" class="search-breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item breadcrumb-home"><a href="{{ lurl('/') }}">{{ t('Home') }}</a></li>
			<li class="breadcrumb-item">
				<?php $attr = ['countryCode' => config('country.icode')]; ?>
				<a href="{{ lurl(trans('routes.v-search', $attr), $attr) }}">
					<i class="unir-rarrow2">&#8194;</i>
					@if (isset($city))
						{!! $city->name !!}
					@else
						{{ t('All cities') }}
					@endif
				</a>
			</li>
			@if (isset($bcTab) and count($bcTab) > 0)
				@foreach($bcTab as $key => $value)
					<?php $value = collect($value); ?>
					@if ($value->has('position') and $value->get('position') > count($bcTab)+1 and !$value->has('location'))
						<li class="breadcrumb-item active">
							<i class="unir-rarrow2">&#8194;</i>{!! $value->get('name') !!}
							&nbsp;
							@if (isset($city) or isset($admin))
								<a href="#browseAdminCities" id="dropdownMenu1" data-toggle="modal"> <span class="caret"></span></a>
							@endif
						</li>
					@elseif ($value->has('location'))
{{--						All ads in 50 km around ... --}}
{{--						<li class="breadcrumb-item"><a id="city" href="{{ $value->get('url') }}"><i class="unir-rarrow2">&#8194;</i>{!! $value->get('name') !!}</a></li>--}}
					@else
						<li class="breadcrumb-item"><a href="{{ $value->get('url') }}"><i class="unir-rarrow2">&#8194;</i>{!! $value->get('name') !!}</a></li>
					@endif
				@endforeach
			@endif
		</ol>
	</nav>
</div>

@section('after_scripts')
	@parent
	<script>
		$(document).ready(function () {
			if ($('a').is('#city')) {
				$('#city').removeAttr('href');
			}
		})
	</script>
@endsection
