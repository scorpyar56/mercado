{{--
 * LaraClassified - Classified Ads Web Application
 * Copyright (c) BedigitCom. All Rights Reserved
 *
 * Website: http://www.bedigit.com
 *
 * LICENSE
 * -------
 * This software is furnished under a license and may be used and copied
 * only in accordance with the terms of such license and with the inclusion
 * of the above copyright notice. If you Purchased from Codecanyon,
 * Please read the full License from here - http://codecanyon.net/licenses/standard
--}}

@extends('layouts.master')

@section('content')
	@include('common.spacer')
	<div class="main-container">
		<div class="container">

			<div class="row clearfix mid-position">

				@if (isset($errors) and $errors->any())
					<div class="col-md-12">
						<div class="alert alert-danger">
							<!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="unir-close"></i></button>
							<h5><strong>{{ t('Oops ! An error has occurred. Please correct the red fields in the form') }}</strong></h5> -->
							<ul class="list list-check">
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					</div>
				@endif

				<div class="col-md-8">
					<div class="inner-box category-content category-content-dif col-dif">

                        <div class="card card-default">
                            <div class="ads-header">
                                <h3>
                                    <strong> {{ t('This user has been banned.') }} </strong>
                                </h3>
                            </div>

                            <form role="form" method="POST" action="{{ lurl('unban/' . $post . '/request') }}">
                                {!! csrf_field() !!}
                                <fieldset>
                                    <div class="card-body">
                                        <!-- phone -->
                                        <?php $phoneError = (isset($errors) and $errors->has('phone')) ? ' is-invalid' : ''; ?>
                                        <div class="form-group required">
                                            <label for="phone" class="control-label">{{ t('Phone') }} <sup>*</sup></label>
                                            <div class="input-group">
                                                <input id="phone" name="phone" type="text" maxlength="60" class="form-control{{ $phoneError }}" value="{{ $post }}">
                                            </div>
                                        </div>

                                        <!-- email -->
                                        @if (auth()->check() and isset(auth()->user()->email))
                                            <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                                        @else
                                        <?php $emailError = (isset($errors) and $errors->has('email')) ? ' is-invalid' : ''; ?>
                                            <input  name="email" type="hidden" maxlength="33" class="form-control{{ $emailError }}" value="banned@unifun.com">
                                        @endif

                                        @include('layouts.inc.tools.recaptcha', ['label' => true])
                                        <input type="hidden" name="abuseForm" value="1">

                                        <div class="form-group form-group-dif">
                                            <button type="submit" class="btn btn-green btn-lg btn-dif btn-rep">{{ t('Send request') }}</button>
                                            <a href="{{ rawurldecode(URL::previous()) }}" class="btn btn-default btn-default-dif btn-lg btn-rep btn-grey">{{ t('Back') }}</a>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
					</div>
				</div>

			</div>
		</div>
	</div>
@endsection

@section('after_scripts')
	<script src="{{ url('assets/js/form-validation.js') }}">
	</script>
@endsection
