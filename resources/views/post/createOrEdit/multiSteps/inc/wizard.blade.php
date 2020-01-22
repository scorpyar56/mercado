<div id="stepWizard" class="container stepWizard-dif">
    <div class="row">
        <div class="col-xl-12">
            <section>
                <div class="wizard wizard-dif">
                    <ul class="nav nav-wizard nav-wizard-dif">
                        @if (getSegment(2) == 'create')
                            <?php $uriPath = getSegment(4); ?>
							@if (!in_array($uriPath, ['finish']))
								<li class="{{ ($uriPath == '') ? 'active' : (in_array($uriPath, ['photos', 'packages', 'finish']) or (isset($post) and !empty($post)) ? '' : 'disabled') }}">
									@if (isset($post) and !empty($post))
										<a href="{{ lurl('posts/create/' . $post->tmp_token) }}">{{ t('Edit Details') }}</a>
									@else
										<a href="{{ lurl('posts/create') }}">{{ t('Ad Details') }}</a>
									@endif
								</li>
								<div class="block-arr"></div>
												
								<li class="picturesBloc {{ ($uriPath == 'photos') ? 'active' : ((in_array($uriPath, ['photos', 'packages', 'finish']) or (isset($post) and !empty($post))) ? '' : 'disabled') }}">
									@if (isset($post) and !empty($post))
										<a href="{{ lurl('posts/create/' . $post->tmp_token . '/photos') }}">{{ t('Ad Photos') }}</a>
									@else
										<a>{{ t('Ad Photos') }}</a>
									@endif
								</li>
								<div class="block-arr"></div>
			
								@if (isset($countPackages) and isset($countPaymentMethods) and $countPackages > 0 and $countPaymentMethods > 0)
								<li class="{{ ($uriPath == 'payment') ? 'active' : ((in_array($uriPath, ['finish']) or (isset($post) and !empty($post))) ? '' : 'disabled') }}">
									@if (isset($post) and !empty($post))
										<a href="{{ lurl('posts/create/' . $post->tmp_token . '/payment') }}">{{ t('Payment') }}</a>
									@else
										<a>{{ t('Payment') }}</a>
									@endif
								</li>
								<div class="block-arr"></div>
								@endif
							@endif
							
                            <!-- @if ($uriPath == 'activation')
                            <li class="{{ ($uriPath == 'activation') ? 'active' : 'disabled' }}">
                                <a>{{ t('Activation') }}</a>
                            </li>
							<div class="block-arr"></div>
                            @else
                            <li class="{{ ($uriPath == 'finish') ? 'active' : 'disabled' }}">
                                <a>{{ t('Finish') }}</a>
                            </li>
							<div class="block-arr"></div>
                            @endif -->
                        @else
                            <?php $uriPath = getSegment(3); ?>
							@if (!in_array($uriPath, ['finish']))
								<li class="{{ (in_array($uriPath, [null, 'edit'])) ? 'active' : '' }}">
									@if (isset($post) and !empty($post))
										<a href="{{ lurl('posts/' . $post->id . '/edit') }}">{{ t('Edit Details') }}</a>
									@else
										<a href="{{ lurl('posts/create') }}">{{ t('Edit Details') }}</a>
									@endif
								</li>
								<div class="block-arr"></div>
							
								<li class="picturesBloc {{ ($uriPath == 'photos') ? 'active' : '' }}">
									@if (isset($post) and !empty($post))
										<a href="{{ lurl('posts/' . $post->id . '/photos') }}">{{ t('Edit Photos') }}</a>
									@else
										<a>{{ t('Edit Photos') }}</a>
									@endif
								</li>
								<div class="block-arr"></div>
			
								@if (isset($countPackages) and isset($countPaymentMethods) and $countPackages > 0 and $countPaymentMethods > 0)
								<li class="{{ ($uriPath == 'payment') ? 'active' : '' }}">
									@if (isset($post) and !empty($post))
										<a href="{{ lurl('posts/' . $post->id . '/payment') }}">{{ t('Payment') }}</a>
									@else
										<a>{{ t('Payment') }}</a>
									@endif
								</li>
								<div class="block-arr"></div>
								@endif
							@endif
        
                            <!-- <li class="{{ ($uriPath == 'finish') ? 'active' : 'disabled' }}">
                                <a>{{ t('Finish') }}xx</a>
                            </li> -->
							<div class="block-arr"></div>
                        @endif
                    </ul>
                    
                </div>
            </section>
        </div>
    </div>
</div>

@section('after_styles')
    @parent
	@if (config('lang.direction') == 'rtl')
    	<link href="{{ url('assets/css/rtl/wizard.css') }}" rel="stylesheet">
	@else
		<link href="{{ url('assets/css/wizard.css') }}" rel="stylesheet">
	@endif
@endsection
@section('after_scripts')
    @parent
@endsection