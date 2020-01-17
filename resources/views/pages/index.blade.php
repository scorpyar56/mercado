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
@include('pages.inc.page-intro')
@endsection

@section('content')
@include('common.spacer')
<div class="main-container inner-page">
    <div class="container">
        <div class="section-content">
            <div class="row">

                <!-- @if (empty($page->picture))
                        <h1 class="text-center title-1" style="color: {!! $page->name_color !!};"><strong>{{ $page->name }}</strong></h1>
                        <hr class="center-block small mt-0" style="background-color: {!! $page->name_color !!};">
                    @endif -->

                <div class="col-md-12 page-content">
                    <!-- <div class="inner-box relative"> -->
                    <div class="row">
                        <div class="col-md-9 order-md-1 order-2">
                            @if (empty($page->picture))
                            <h3 class="pages-title title-3 py-3" style="padding:30px 0px 30px 0px !important;">
                                <span style="font-size:25px;" class="title-3">{{ $page->title }}</span>
                            </h3>
                            @endif
                            @if (Request::path() == 'page/terms-conditions')
                                @include('pages.contents')
                            @endif
                            <div class="text-content text-left from-wysiwyg">
                                {!! $page->content !!}
                            </div>
                        </div>
                        <div class="col-md-3 order-1 page-content">
                            <div class="help-block sticky-top">
                                <h3 class="title-3 py-3">{{ t('Help links') }}</h3>
                                <div class="text-content text-left from-wysiwyg">
                                    <h4><a href="{{ lurl('page/terms-of-use')}}">{{ t('Terms of Use') }}</a></h4>
                                    <h4><a href="{{ lurl('page/privacy-policy')}}">{{ t('Privacy Policy') }}</a></h4>
                                    <h4><a href="{{ lurl('page/posting-rules')}}">{{ t('Posting Rules') }}</a></h4>
                                    <h4><a href="{{ lurl('page/tips')}}">{{ t('Tips for Users') }}</a></h4>
                                    <h4><a href="{{ lurl('page/faq')}}">{{ t('FAQ') }}</a></h4>
                                    <h4><a href="{{ lurl('sitemap')}}">{{ t('Sitemap') }}</a></h4>
                                    <h4><a href="{{ lurl('contact')}}">{{ t('Contact Us') }}</a></h4>
                                </div>
                            </div>
                            <div class="toTop">
                                <div id="toTop"><i class="fa fa-angle-up"></i></div>
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript">
                         $(function() {
                            $(window).scroll(function() {
                                if($(this).scrollTop() != 0) {
                                    $('#toTop').fadeIn();
                                } else {
                                    $('#toTop').fadeOut();
                                }
                            });
                            $('#toTop').click(function() {
                                $('body,html').animate({scrollTop:0},800);
                            });
                        });
                    </script>
                    <!-- </div> -->
                </div>

            </div>

            @include('layouts.inc.social.horizontal')

        </div>
    </div>
</div>
@endsection

@section('info')
@endsection