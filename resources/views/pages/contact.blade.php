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

@section('search')
@parent
@include('pages.inc.contact-intro')
@endsection

@section('content')
@include('common.spacer')
<?php
if (!isset($languageCode) or empty($languageCode)) {
    $languageCode = config('app.locale', session('language_code'));

    $errorClass = (isset($errors)) ? ' is-invalid' : '';
}
?>
<div class="main-container">
    <div class="container">
        <div class="row clearfix">
            @if (isset($errors) and $errors->any())
            <div class="col-xl-12">
                <div class="alert alert-danger">
                    <!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><i class="unir-close"></i></button>
                    <h5><strong>{{ t('Oops ! An error has occurred. Please correct the red fields in the form') }}</strong> -->
                    </h5>
                    <ul class="list list-check">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            @if (Session::has('flash_notification'))
            <div class="col-xl-12">
                <div class="row">
                    <div class="col-xl-12">
                        @include('flash::message')
                    </div>
                </div>
            </div>
            @endif
            <div class="col-md-8 order-md-1 order-2">
                <div class="contact-form">
                    <h2 class="py-3" style="padding-top:30px !important;">
                        <span class="title-3">{{ t('Contact Us') }}</span>
                    </h2>
                    <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{ lurl(trans('routes.contact')) }}">
                        {!! csrf_field() !!}
                        <fieldset>
                            <div class="row">
                                <div class="col-md-12">
                                    <?php $firstNameError = (isset($errors) and $errors->has('first_name')) ? ' is-invalid' : ''; ?>
                                    <div class="form-group required">
                                        <input id="first_name" name="first_name" type="text"
                                            placeholder="{{ t('First Name') }}"
                                            class="form-control{{ $firstNameError }}" value="{{ old('first_name') }}">
                                    </div>
                                </div>
                                <!-- N.M. -->
                                <div class="col-md-12">
                                    <?php $phoneError = (isset($errors) and $errors->has('phone')) ? ' is-invalid' : ''; ?>
                                    <div class="form-group required">
                                        <input id="phone" name="phone" type="text" placeholder="{{ t('Phone Number') }}"
                                            class="form-control{{ $phoneError }}" value="{{ old('phone') }}">
                                    </div>
                                </div>
                                <!-- END N.M. -->
                                <!-- <div class="col-md-6">
                                    <?php $lastNameError = (isset($errors) and $errors->has('last_name')) ? ' is-invalid' : ''; ?>
                                    <div class="form-group required">
                                        <input id="last_name" name="last_name" type="text"
                                            placeholder="{{ t('Last Name') }}" class="form-control{{ $lastNameError }}"
                                            value="{{ old('last_name') }}">
                                    </div>
                                </div> -->
                                <!-- <div class="col-md-6">
                                    <?php $companyNameError = (isset($errors) and $errors->has('company_name')) ? ' is-invalid' : ''; ?>
                                    <div class="form-group required">
                                        <input id="company_name" name="company_name" type="text"
                                            placeholder="{{ t('Company Name') }}"
                                            class="form-control{{ $companyNameError }}"
                                            value="{{ old('company_name') }}">
                                    </div>
                                </div> -->
                                <div class="col-md-12">
                                    <?php $emailError = (isset($errors) and $errors->has('email')) ? ' is-invalid' : ''; ?>
                                    <div class="form-group required">
                                        <input id="email" name="email" type="text"
                                            placeholder="{{ t('Email Address') }}" class="form-control{{ $emailError }}"
                                            value="{{ old('email') }}">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <?php $failError = (isset($errors) and $errors->has('file')) ? ' is-invalid' : ''; ?>
                                    <div class="mb10">
                                        <!-- <input name="file" id="file" type="file"  class="form-control{{ $failError }}"> -->
                                        <div class="button-wrap">
                                            <label class ="custom-button-upfile btn-grey" for="file"> Attach file </label>
                                            <input class="custom-upfile" id="file" name="file" type="file" class="form-control{{ $failError }}">
                                            <div id="fileName"></div>
                                            <small style="display:block;" class="text-muted">
                                                {{ t('File types: :file_types', ['file_types' => showValidFileTypes('file')], 'global', $languageCode) }}
                                            </small>
                                        </div>
                                        <script>
                                            $(".custom-upfile").on('change', function () {
                                                let e = $(".custom-upfile")[0].value.split('\\');
                                                $('#fileName').html(e[e.length - 1]);
                                            });
                                        </script>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <?php $messageError = (isset($errors) and $errors->has('message')) ? ' is-invalid' : ''; ?>
                                    <div class="form-group required">
                                        <textarea class="form-control{{ $messageError }}" id="message" name="message"
                                            placeholder="{{ t('Message') }}" rows="7">{{ old('message') }}</textarea>
                                    </div>

                                    @include('layouts.inc.tools.recaptcha')

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-lg btn-dif btn-green">{{ t('Submit') }}</button>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </form> 
                </div>
            </div>
            <div class="offset-md-1 col-md-3 order-1 page-content">
                <div class="help-block sticky-top">
                    <h3 class="title-3 py-3">{{ t('Help links') }}</h3>
                    <div class="text-content text-left from-wysiwyg">
                        <h4><a href="{{ lurl('page/terms-of-use')}}">{{ t('Terms of Use') }}</a></h4>
                        <h4><a href="{{ lurl('page/privacy-policy')}}">{{ t('Privacy Policy') }}</a></h4>
                        <h4><a href="{{ lurl('page/posting-rules')}}">{{ t('Posting Rules') }}</a></h4>
                        <h4><a href="{{ lurl('page/tips')}}">{{ t('Tips for Users') }}</a></h4>
                        <h4><a href="{{ lurl('page/faq')}}">{{ t('FAQ') }}</a></h4>
                        <h4><a href="{{ lurl('contact')}}">{{ t('Contact Us') }}</a></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('after_scripts')
<script src="{{ url('assets/js/form-validation.js') }}"></script>
@endsection