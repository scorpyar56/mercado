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

<?php
use App\Models\User;

if (isset(auth()->user()->id)) {
    $userID = auth()->user()->id;
    $user = User::find($userID);
    $is_admin = $user->is_admin == "1" ? true : false;
}
?>

@section('content')
    {!! csrf_field() !!}
    <input type="hidden" id="postId" name="post_id" value="{{ $post->id }}">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div id="make_favorite">
                </div>
            </div>
        </div>
    </div>

    @if (Session::has('flash_notification'))

        <?php $paddingTopExists = true; ?>
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    @include('flash::message')
                </div>
            </div>
        </div>
        <?php Session::forget('flash_notification.message'); ?>
    @endif

    <div class="main-container cart">

        <?php if (\App\Models\Advertising::where('slug', 'top')->count() > 0): ?>
        @include('layouts.inc.advertising.top', ['paddingTopExists' => (isset($paddingTopExists)) ? $paddingTopExists : false])
        <?php
        $paddingTopExists = false;
        endif;
        ?>

        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <nav aria-label="breadcrumb" role="navigation" class="pull-left">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ lurl('/') }}">{{ t('Home')}} &#8194;<i
                                            class="unir-rarrow2"> </i> </a></li>
                            <?php

                            $attr = ['city' => $post->city->name];?>
                            <li class="breadcrumb-item"><a
                                        href="{{ \App\Helpers\UrlGen::city( $post->city) }}">{{ $post->city->name }}
                                    &#8194;<i class="unir-rarrow2"></i></a></li>
                            @if (!empty($post->category->parent))
                                <li class="breadcrumb-item">
                                    <a href="{{ \App\Helpers\UrlGen::category($post->category->parent) }}">
                                        {{ $post->category->parent->name }}
                                        &#8194;<i class="unir-rarrow2"></i>
                                    </a>
                                </li>
                                @if ($post->category->parent->id != $post->category->id)
                                    <li class="breadcrumb-item">
                                        <a href="{{ \App\Helpers\UrlGen::category($post->category, 1) }}">
                                            {{ $post->category->name }}
                                            &#8194;<i class="unir-rarrow2"></i>
                                        </a>
                                    </li>
                                @endif
                            @else
                                <li class="breadcrumb-item">
                                    <a href="{{ \App\Helpers\UrlGen::category($post->category) }}">
                                        {{ $post->category->name }}
                                        &#8194;<i class="unir-rarrow2"></i>
                                    </a>
                                </li>
                            @endif
                            <li class="breadcrumb-item"
                                aria-current="page">{{ t("Ad")}}</li>
                        </ol>
                    </nav>


                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="enable-long-words-posts">
                        <strong>
                            {{ $post->title}}
                        </strong>
                    <!--<small class="label label-default adlistingtype">{{ $post->postType->name }}</small>-->
                        @if ($post->featured==1 and !empty($post->latestPayment))
                            @if (isset($post->latestPayment->package) and !empty($post->latestPayment->package))
                                <i class="icon-ok-circled tooltipHere"
                                   style="color: {{ $post->latestPayment->package->ribbon }};" title=""
                                   data-placement="right"
                                   data-toggle="tooltip"
                                   data-original-title="{{ $post->latestPayment->package->short_name }}"></i>
                            @endif
                        @endif
                    </h2>
                </div>
            </div>

            <div class="modal fade" id="phoneModal" tabindex="-1" role="dialog" aria-labeledby="#titlePhoneModal" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        @if( $post->phone)
                            <div class="modal-header modal-header-dif">
                                <h2 class="modal-title" id="titlePhoneModal">
                                    {{ t('Phone') }}&#58
                                    <?php
                                    if (preg_match('/^\+(\d{3})(\d{2})(\d{3})(\d{4})$/', $post->phone, $matches)) {
                                        $phone = "+" . $matches[1] . ' ' . $matches[2] . ' ' . $matches[3];
                                    }
                                    if (isset($matches[4])) {
                                        $phone .= ' ' . $matches[4];
                                    }
                                    ?>
                                    @if( isset($phone))
                                        {{ $phone }}
                                    @else
                                        {{ $post->phone }}
                                    @endif
                                </h2>
                                <button type="button" class="close" data-dismiss="modal">
                                    {{--					<span aria-hidden="true">&times;</span>--}}
                                    <span aria-hidden="true"><i class="unir-close"></i></span>
                                    <span class="sr-only">{{ t('Close') }}</span>
                                </button>
                            </div>
                        @endif

                        <div class="modal-body modal-body-dif modal-body-user">
                            <div class="modal-text">
                                {{ t("tell seller that you found this ad on Mercado.Gratis")}}
                            </div>

                            <div class="block-cell user">
                                <div class="cell-media cart-user-photo">
                                    @if (!empty($userPhoto))
                                        <img src="{{ $userPhoto }}" alt="{{ $post->contact_name }}">
                                    @else
                                        <img src="{{ url('images/userCard.png') }}" alt="{{ $post->contact_name }}">
                                    @endif
                                </div>

                                <?php
                                //                                    $userID = DB::select('SELECT DISTINCT(user_id) FROM posts WHERE contact_name = "' . $post->contact_name . '"');
                                //                                    $url = str_replace(substr(url()->current(), strripos(url()->current(), '/')), '', url()->current());
                                //                                    var_dump($url);
                                //                                    var_dump($url . '/users/' . $userID[0]->user_id . '/ads');
                                ?>
                                <div class="cell-content">
                                <span class="name">
                                    @if (isset($user) and !empty($user))
                                        <a href="{{ \App\Helpers\UrlGen::userPosts($post->user_id) }}">
                                            {{ $post->contact_name }}
                                        </a>
                                    @else
                                        {{ $post->contact_name }}
                                    @endif

                                    @if (isset($user) and !empty($user) and isset($joined))
                                        <div class="grid-col">
                                        <div class="col gray">
                                            <span>{{ t('On site since ') }} {{ $joined }}</span>
                                        </div>
                                    </div>
                                    @elseif($post->user_id == 1)
                                        <div class="grid-col">
                                        <div class="col gray">
                                            <span>{{ t('Admin') }}</span>
                                        </div>
                                    </div>
                                    @else
                                        <div class="grid-col">
                                        <div class="col gray">
                                            <span>{{ t('Not registered user') }} </span>
                                        </div>
                                    </div>
                                    @endif

                                    @if (config('plugins.reviews.installed'))
                                        @if (view()->exists('reviews::ratings-user'))
                                            @include('reviews::ratings-user')
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-lg-9 page-content col-thin-right cart">
                    <div class="inner inner-box items-details-wrapper pb-0">

                        <div class="row info-row-cart">
                            <div class="col-md-12">
                                <span class="info-row-cart">
                                    <span class="item-location left15 gray">
                                        <i class="unir-location"> </i>
                                        <!-- <img src="{{ url('images/point.svg') }}" alt="{{ $post->contact_name }}" class="sidebar-image"> -->
                                        <a href="{!! \App\Helpers\UrlGen::city($post->city) !!}">
                                            {{ $post->city->name }}
                                        </a>
                                    </span> &nbsp;
                                    <span class="date gray">
                                            <i class="unir-clock"> </i>
                                            <!-- <img src="{{ url('images/clock.svg') }}" alt="{{ $post->contact_name }}" class="sidebar-image">     -->
                                     {{ $post->created_at_ta }} </span> &nbsp;
                                    <!-- <span class="category">{{ (!empty($post->category->parent)) ? $post->category->parent->name : $post->category->name }}</span> -&nbsp; -->

                                    <span class="category gray">
                                         <i class="unir-eye"> </i>
                                        <!-- <img src="{{ url('images/view.svg') }}" alt="{{ $post->contact_name }}" class="sidebar-image">     -->
                                        {{ \App\Helpers\Number::short($post->visits) }} {{ trans_choice('global.count_views', getPlural($post->visits)) }}
                                    </span>
                                    <span class="detail action center">
                                        <span class="left15">
                                            <a href="{{ lurl('posts/' . $post->id . '/report') }}">
                                                <i class="unir-info gray" style="font-size: 14px;"> </i>
                                                <!-- <img src="{{ url('images/inform.svg') }}" alt="{{ $post->contact_name }}" class="sidebar-image"> -->
                                                <span class="actions-text">
                                                    {{ t('Report abuse')}}
                                                </span>
                                            </a>
                                        </span>

                                        <span>
                                            <a class="make-favorite" id="{{ $post->id }}"
                                               href="javascript:void(0)">
                                                @if (auth()->check())
                                                    @if (\App\Models\SavedPost::where('user_id', auth()->user()->id)->where('post_id', $post->id)->count() > 0)
                                                        <i class="unir-bheart gray"> </i>
                                                    <!-- <img src="{{ url('images/heart_blue.svg') }}" alt="{{ $post->contact_name }}" class="sidebar-image"> -->
                                                        <span class="actions-text">
                                                            {{ t('Remove favorite') }}
                                                        </span>
                                                    @else
                                                        <i class="unir-heart gray"> </i>
                                                    <!-- <img src="{{ url('images/heart.svg') }}" alt="{{ $post->contact_name }}" class="sidebar-image"> -->
                                                        <span class="actions-text">
                                                            {{ t('To favorites.') }}
                                                        </span>
                                                    @endif
                                                @else
                                                    <i class="unir-heart gray"> </i>
                                                <!-- <img src="{{ url('images/heart.svg') }}" alt="{{ $post->contact_name }}" class="sidebar-image"> -->
                                                    <span class="actions-text">
                                                    {{ t('To favorites.') }}
                                                </span>
                                                @endif
                                            </a>
                                        </span>

                                    </span>
                                </span>
                            </div>
                        </div>
                        <div class="posts-image">
                        <?php $titleSlug = \Illuminate\Support\Str::slug($post->title); ?>
                        <!-- @if (!in_array($post->category->type, ['not-salable']))
                            <h1 class="pricetag">
@if ($post->price > 0)
                                {!! \App\Helpers\Number::money($post->price) !!}
                            @else
                                {!! \App\Helpers\Number::money(' --') !!}
                            @endif
                                    </h1>
@endif -->

                            @if (count($post->pictures) > 0)
                                <ul class="bxslider">
                                    @foreach($post->pictures as $key => $image)
                                        <li><img src="{{ imgUrl($image->filename, 'big') }}"
                                                 alt="{{ $titleSlug . '-big-' . $key }}"></li>
                                    @endforeach
                                </ul>
                                <div class="product-view-thumb-wrapper">
                                    <ul id="bx-pager" class="product-view-thumb">
                                        @foreach($post->pictures as $key => $image)
                                            <li>
                                                <a class="thumb-item-link" data-slide-index="{{ $key }}" href="">
                                                    <img src="{{ imgUrl($image->filename, 'small') }}"
                                                         alt="{{ $titleSlug . '-small-' . $key }}">
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <ul class="bxslider">
                                    <li><img src="{{ imgUrl(config('larapen.core.picture.default'), 'big') }}"
                                             alt="img"></li>
                                </ul>
                                <div class="product-view-thumb-wrapper">
                                    <ul id="bx-pager" class="product-view-thumb">
                                        <li>
                                            <a class="thumb-item-link" data-slide-index="0" href="">
                                                <img src="{{ imgUrl(config('larapen.core.picture.default'), 'small') }}"
                                                     alt="img">
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <!--posts-image-->


                        @if (config('plugins.reviews.installed'))
                            @if (view()->exists('reviews::ratings-single'))
                                @include('reviews::ratings-single')
                            @endif
                        @endif


                        <div class="items-details">
                            <ul class="nav nav-tabs no-border" id="itemsDetailsTabs" role="tablist">
                                @if (config('plugins.reviews.installed'))
                                    <li class="nav-item">
                                        <a class="nav-link"
                                           id="item-{{ config('plugins.reviews.name') }}-tab"
                                           data-toggle="tab"
                                           href="#item-{{ config('plugins.reviews.name') }}"
                                           role="tab"
                                           aria-controls="item-{{ config('plugins.reviews.name') }}"
                                           aria-selected="false"
                                        >
                                            <h4>
                                                {{ trans('reviews::messages.Reviews') }}
                                                @if (isset($rvPost) and !empty($rvPost))
                                                    ({{ $rvPost->rating_count }})
                                                @endif
                                            </h4>
                                        </a>
                                    </li>
                                @endif
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content p-3 mb-3 no-border cart" id="itemsDetailsTabsContent">
                                <div class="tab-pane show active" id="item-details" role="tabpanel"
                                     aria-labelledby="item-details-tab">
                                    <div class="row">
                                        <div class="items-details-info col-md-12 col-sm-12 col-xs-12 enable-long-words from-wysiwyg">

                                            <!-- Description -->
                                            <div class="row">
                                                <div class="col-12 detail-line-content">
                                                    {!! transformDescription($post->description) !!}
                                                </div>
                                            </div>

                                            <!-- Custom Fields -->
                                        @include('post.inc.fields-values')

                                        <!-- Tags -->
                                        <!-- @if (!empty($post->tags))
                                            <?php $tags = array_map('trim', explode(',', $post->tags)); ?>
                                            @if (!empty($tags))
                                                <div class="row">
                                                    <div class="tags col-12">
                                                        <h4><i class="icon-tag"></i> {{ t('Tags') }}:</h4>
                                                            @foreach($tags as $iTag)
                                                    <a href="{{ \App\Helpers\UrlGen::tag($iTag) }}">
                                                                    {{ $iTag }}
                                                            </a>
@endforeach
                                                        </div>
                                                    </div>
                                            @endif
                                        @endif -->

                                            <!-- Actions -->
                                        <!-- <div class="row detail-line-action text-center">
                                                <div class="col-4">
                                                        @if (auth()->check())
                                            @if (auth()->user()->id == $post->user_id || $is_admin)
                                                <a href="{{ lurl('posts/' . $post->id . '/edit') }}">
                                                                    <i class="icon-pencil-circled tooltipHere"
                                                                       data-toggle="tooltip"
                                                                       data-original-title="{{ t('Edit') }}"></i>
                                                                </a>
                                                            @else
                                                {!! genEmailContactBtn($post, false, true) !!}
                                            @endif
                                        @else
                                            {!! genEmailContactBtn($post, false, true) !!}
                                        @endif
                                                </div>
                                                <div class="col-4">
                                                    <a class="make-favorite" id="{{ $post->id }}"
                                                       href="javascript:void(0)">
                                                        @if (auth()->check())
                                            @if (\App\Models\SavedPost::where('user_id', auth()->user()->id)->where('post_id', $post->id)->count() > 0)
                                                <i class="fa fa-heart tooltipHere" data-toggle="tooltip"
                                                   data-original-title="{{ t('Remove favorite') }}"></i>
                                                            @else
                                                <i class="far fa-heart" class="tooltipHere"
                                                   data-toggle="tooltip"
                                                   data-original-title="{{ t('Save ad') }}"></i>
                                                            @endif
                                        @else
                                            <i class="far fa-heart" class="tooltipHere"
                                               data-toggle="tooltip"
                                               data-original-title="{{ t('Save ad') }}"></i>
                                                        @endif
                                                </a>
                                            </div>
                                            <div class="col-4">
                                                <a href="{{ lurl('posts/' . $post->id . '/report') }}">
                                                        <i class="fa icon-info-circled-alt tooltipHere"
                                                           data-toggle="tooltip"
                                                           data-original-title="{{ t('Report abuse') }}"></i>
                                                    </a>
                                                </div>
                                            </div> -->

                                            <div class="col-xl-12 pl-1 pr-1 cart">
                                            @if (!in_array($post->category->type, ['not-salable']))
                                                <!-- Price / Salary -->
                                                    <div class="col-xl-12 pl-1 pr-1 cart">
                                                        <span class="title-3">
                                                            <!-- <span >
                                                                {{ (!in_array($post->category->type, ['job-offer', 'job-search'])) ? t('Price') : t('Salary') }}:
                                                            </span> -->
                                                                <span class="big_price detail-line-value">
                                                                @if ($post->price > 0)
                                                                    {!! \App\Helpers\Number::money($post->price) !!}
                                                                @elseif($post->negotiable == 1)
                                                                    {{ t('Negotiable') }}
                                                                @else
                                                                    {{ t('Negotiable') }}
                                                                @endif
                                                                <!-- @if ($post->negotiable == 1)
                                                                    {{ t('Negotiable') }}

                                                                @endif -->
                                                            </span>
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                            <!-- Location -->
                                            <div class="col-xl-12 pl-1 pr-1 cart location">
                                                <div class="col-xl-12 pl-1 pr-1 cart location">
                                                    <span class="title-3">
                                                        <span>
                                                             {{ t('Location') }}: 
                                                        </span>
                                                        <span class="detail-line-value">
                                                                {{ $post->city->name }}
                                                                <i class="unir-darrow2"></i>
                                                        </span>
                                                    </span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                @if (config('plugins.reviews.installed'))
                                    @if (view()->exists('reviews::comments'))
                                        @include('reviews::comments')
                                    @endif
                                @endif
                            </div>
                            <!-- /.tab content -->

                        <!-- <div class="content-footer text-left">
                                @if (auth()->check())
                            @if (auth()->user()->id == $post->user_id)
                                <a class="btn btn-default" href="{{ \App\Helpers\UrlGen::editPost($post) }}"><i
                                                    class="fa fa-pencil-square-o"></i> {{ t('Edit') }}</a>
                                    @else
                                {!! genEmailContactBtn($post) !!}
                            @endif
                        @else
                            {!! genEmailContactBtn($post) !!}
                        @endif
                        {!! genPhoneNumberBtn($post) !!}
                                </div> -->
                        </div>
                    </div>
                    <!--/.items-details-wrapper-->
                </div>
                <!--/.page-content-->

                <div class="col-lg-3 page-sidebar-right">
                    <aside>

                        <div class="card card-user-info sidebar-card">
                                    <span class="back">
                                        <a href="{{ rawurldecode(url()->previous()) }}">
                                            <i class="unir-larrow2"></i>
                                            {{ t('Back') }}</a>
                                    </span>
                            @if (auth()->check() and auth()->user()->getAuthIdentifier() == $post->user_id)
                                <div class="card-content-cart">
                                <!-- <div>{{ t('Manage Ad') }}</div>xx -->
                                @if (auth()->user()->id == $post->user_id)

                                    {!! genPhoneNumberBtn($post, true) !!}
                                    {!! genEmailContactBtn($post, true) !!}
                                    <!-- <a href="{{ \App\Helpers\UrlGen::editPost($post) }}"
                                            class="btn btn-default btn-block message">
                                                <i class="unir-edit"></i> {{ t('Update the Details') }}
                                            </a>
                                            @if (config('settings.single.publication_form_type') == '1')
                                        <a href="{{ lurl('posts/' . $post->id . '/photos') }}"
                                                class="btn btn-default btn-block message">
                                                    <i class="unir-edit"></i> {{ t('Update Photos') }}
                                                </a>
                                                @if (isset($countPackages) and isset($countPaymentMethods) and $countPackages > 0 and $countPaymentMethods > 0)
                                            <a href="{{ lurl('posts/' . $post->id . '/payment') }}"
                                                    class="btn btn-success btn-block">
                                                        <i class="icon-ok-circled2"></i> {{ t('Make It Premium') }}
                                                    </a>
                                                @endif
                                    @endif -->
                                        <div class="block-cell user">
                                            <div class="cell-media cart-user-photo">
                                                @if (!empty($userPhoto))
                                                    <img src="{{ $userPhoto }}" alt="{{ $post->contact_name }}">
                                                @else
                                                    <img src="{{ url('images/userCard.png') }}"
                                                         alt="{{ $post->contact_name }}">
                                                @endif
                                            </div>
                                            <div class="cell-content">
                                                <span class="name">
                                                    @if (isset($user) and !empty($user))
                                                        <a href="{{ \App\Helpers\UrlGen::userPosts($post->user_id) }}">
                                                            {{ $post->contact_name }}
                                                        </a>
                                                    @else
                                                        {{ $post->contact_name }}
                                                    @endif

                                                    @if (isset($user) and !empty($user) and isset($joined) and $joined)
                                                        <div class="grid-col">
                                                        <div class="col gray">
                                                            <span>{{ t('On site since ') }} {{ $joined }}</span>
                                                        </div>
                                                    </div>
                                                    @elseif($post->user_id == 1)
                                                        <div class="grid-col">
                                                        <div class="col gray">
                                                            <span>{{ t('Admin') }}</span>
                                                        </div>
                                                    </div>
                                                    @else
                                                        <div class="grid-col">
                                                        <div class="col gray">
                                                            <span>{{ t('Not registered user') }} </span>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if (config('plugins.reviews.installed'))
                                                    @if (view()->exists('reviews::ratings-user'))
                                                        @include('reviews::ratings-user')
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        {!! genPhoneNumberBtn($post, true) !!}
                                        {!! genEmailContactBtn($post, true) !!}
                                    @endif
                                </div>
                            @else
                                <div class="card-content-cart">
                                    <?php $evActionStyle = 'style="border-top: 0;"'; ?>
                                    @if (auth()->check())
                                        @if (auth()->user()->id == $post->user_id)
                                            <a href="{{ \App\Helpers\UrlGen::editPost($post) }}"
                                               class="btn btn-default btn-block message">
                                                <i class="unir-edit"></i> {{ t('Update the Details') }}
                                            </a>
                                            @if (config('settings.single.publication_form_type') == '1')
                                                <a href="{{ lurl('posts/' . $post->id . '/photos') }}"
                                                   class="btn btn-default btn-block message">
                                                    <i class="unir-edit"></i> {{ t('Update Photos') }}
                                                </a>
                                                @if (isset($countPackages) and isset($countPaymentMethods) and $countPackages > 0 and $countPaymentMethods > 0)
                                                    <a href="{{ lurl('posts/' . $post->id . '/payment') }}"
                                                       class="btn btn-success btn-block">
                                                        <i class="icon-ok-circled2"></i> {{ t('Make It Premium') }}
                                                    </a>
                                                @endif
                                            @endif
                                        @else
                                            {!! genPhoneNumberBtn($post, true) !!}
                                            {!! genEmailContactBtn($post, true) !!}
                                        @endif
                                        <?php
                                        try {
                                            if (auth()->user()->can(\App\Models\Permission::getStaffPermissions())) {
                                                $btnUrl = admin_url('blacklists/add') . '?phone=' . $post->phone;

                                                if (!isDemo($btnUrl)) {
                                                    $cMsg = trans('admin::messages.confirm_this_action');
                                                    $cLink = "window.location.replace('" . $btnUrl . "'); window.location.href = '" . $btnUrl . "';";
                                                    $cHref = "javascript: if (confirm('" . addcslashes($cMsg, "'") . "')) { " . $cLink . " } else { void('') }; void('')";

                                                    $btnText = trans("admin::messages.ban_the_user");
                                                    // $btnHint = trans("admin::messages.ban_the_user_phone", ['phone' => $post->phone]);
                                                    // $tooltip = ' data-toggle="tooltip" title="' . $btnHint . '"';
                                                    $tooltip = ' data-toggle="tooltip"';

                                                    $btnOut = '';
                                                    $btnOut .= '<a href="' . $cHref . '" class="btn btn-danger btn-block"' . $tooltip . '>';
                                                    $btnOut .= $btnText;
                                                    $btnOut .= '</a>';

                                                    echo $btnOut;
                                                }
                                            }
                                        } catch (\Exception $e) {
                                        }
                                        ?>
                                    @else
                                        {!! genPhoneNumberBtn($post, true) !!}
                                        {!! genEmailContactBtn($post, true) !!}
                                    @endif
                                </div>
                                <div class="block-cell user">
                                    <div class="cell-media cart-user-photo">
                                        @if (!empty($userPhoto))
                                            <img src="{{ $userPhoto }}" alt="{{ $post->contact_name }}">
                                        @else
                                            <img src="{{ url('images/userCard.png') }}" alt="{{ $post->contact_name }}">
                                        @endif
                                    </div>

                                    <?php
                                    //                                    $userID = DB::select('SELECT DISTINCT(user_id) FROM posts WHERE contact_name = "' . $post->contact_name . '"');
                                    //                                    $url = str_replace(substr(url()->current(), strripos(url()->current(), '/')), '', url()->current());
                                    //                                    var_dump($url);
                                    //                                    var_dump($url . '/users/' . $userID[0]->user_id . '/ads');
                                    ?>
                                    <div class="cell-content">
                                        <span class="name">
											@if (isset($user) and !empty($user))
                                                <a href="{{ \App\Helpers\UrlGen::userPosts($post->user_id) }}">
													{{ $post->contact_name }}
                                                </a>
                                            @else
                                                {{ $post->contact_name }}
                                            @endif

                                            @if (isset($user) and !empty($user) and isset($joined) and $joined)
                                                <div class="grid-col">
                                                <div class="col gray">
                                                    <span>{{ t('On site since ') }} {{ $joined }}</span>
                                                </div>
                                            </div>
                                            @elseif($post->user_id == 1)
                                                <div class="grid-col">
                                                <div class="col gray">
                                                    <span>{{ t('Admin') }}</span>
                                                </div>
                                            </div>
                                            @else
                                                <div class="grid-col">
                                                <div class="col gray">
                                                    <span>{{ t('Not registered user') }} </span>
                                                </div>
                                            </div>
                                        @endif

                                        @if (config('plugins.reviews.installed'))
                                            @if (view()->exists('reviews::ratings-user'))
                                                @include('reviews::ratings-user')
                                            @endif
                                        @endif

                                    </div>
                                </div>
                            @endif
                        </div>

                        @if (config('settings.single.show_post_on_googlemap'))
                            <div class="card sidebar-card">
                                <div class="card-header">{{ t('Location\'s Map') }}</div>
                                <div class="card-content">
                                    <div class="card-body text-left p-0">
                                        <div class="ads-googlemaps">
                                            <iframe id="googleMaps" width="100%" height="250" frameborder="0"
                                                    scrolling="no" marginheight="0" marginwidth="0" src=""></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    @endif

                    @if (isVerifiedPost($post))
                        @include('layouts.inc.social.horizontal')
                    @endif

                    <!-- <div class="card sidebar-card">
                            <div class="card-header">{{ t('Safety Tips for Buyers') }}</div>
                            <div class="card-content">
                                <div class="card-body text-left">
                                    <ul class="list-check">
                                        <li> {{ t('Meet seller at a public place') }} </li>
                                        <li> {{ t('Check the item before you buy') }} </li>
                                        <li> {{ t('Pay only after collecting the item') }} </li>
                                    </ul>
                                    <?php $tipsLinkAttributes = getUrlPageByType('tips'); ?>
                    @if (!\Illuminate\Support\Str::contains($tipsLinkAttributes, 'href="#"') and !\Illuminate\Support\Str::contains($tipsLinkAttributes, 'href=""'))
                        <p>
                            <a class="pull-right" {!! $tipsLinkAttributes !!}>
                                                {{ t('Know more') }}
                                <i class="fa fa-angle-double-right"></i>
                            </a>
                        </p>
@endif
                            </div>
                        </div>
                    </div> -->
                    </aside>
                </div>
            </div>

        </div>

        @if (config('settings.single.similar_posts') == '1' || config('settings.single.similar_posts') == '2')
            @include('home.inc.featured', ['firstSection' => false])
        @endif

        @include('layouts.inc.advertising.bottom', ['firstSection' => false])

        @if (isVerifiedPost($post))
            @include('layouts.inc.tools.facebook-comments', ['firstSection' => false])
        @endif

    </div>
@endsection

@section('modal_message')
    @if (auth()->check() or config('settings.single.guests_can_contact_ads_authors')=='1')
        @include('post.inc.compose-message')
    @endif
@endsection

@section('modal_message')
    @include('post.inc.phone-info')
@endsection

@section('after_styles')
    <!-- bxSlider CSS file -->
    @if (config('lang.direction') == 'rtl')
        <link href="{{ url('assets/plugins/bxslider/jquery.bxslider.rtl.css') }}" rel="stylesheet"/>
    @else
        <link href="{{ url('assets/plugins/bxslider/jquery.bxslider.css') }}" rel="stylesheet"/>
    @endif
@endsection

@section('after_scripts')
    @if (config('services.googlemaps.key'))
        <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.googlemaps.key') }}"
                type="text/javascript"></script>
    @endif

    <!-- bxSlider Javascript file -->
    <script src="{{ url('assets/plugins/bxslider/jquery.bxslider.min.js') }}"></script>

    <script>
        var scrollAdded = false;

        var old
        /* Favorites Translation */
        var lang = {
            labelSavePostSave: "{!! t('To favorites.') !!}",
            labelSavePostRemove: "{!! t('Remove favorite') !!}",
            loginToSavePost: "{!! t('Please log in to save the Ads.') !!}",
            loginToSaveSearch: "{!! t('Please log in to save your search.') !!}",
            confirmationSavePost: "{!! t('Post saved in favorites successfully!') !!}",
            confirmationRemoveSavePost: "{!! t('Post deleted from favorites successfully!') !!}",
            confirmationSaveSearch: "{!! t('Search saved successfully!') !!}",
            confirmationRemoveSaveSearch: "{!! t('Search deleted successfully!') !!}"
        };

        $('.owl-carousel').owlCarousel({
            loop: true,
            dots: true,
            autoplay: true,
            autoplayTimeout: 3000,
            responsive: {
                200: {
                    items: 2,
                    dotsEach: 2,
                },
                700: {
                    items: 3,
                    dotsEach: 3,
                },
                930: {
                    items: 4,
                    dotsEach: 3,
                },
                1200: {
                    items: 5,
                    dotsEach: 3,
                }
            }
        });

        $(document).ready(function () {

            /* bxSlider - Main Images */
            $('.bxslider').bxSlider({
                speed: 300,
                mode: 'fade',
                pagerCustom: '#bx-pager',
                adaptiveHeight: true,
                preloadImages: 'all',
                onSlideAfter: function ($slideElement, oldIndex, newIndex) {
                    @if (!userBrowser('Chrome'))
                    $('#bx-pager li:not(.bx-clone)').eq(newIndex).find('a.thumb-item-link').addClass('active');
                    @endif
                }
            });

            /* bxSlider - Thumbnails */
            @if (userBrowser('Chrome'))
            $('#bx-pager').addClass('m-3');
            $('#bx-pager .thumb-item-link').unwrap();
                    @else
            var thumbSlider = $('.product-view-thumb').bxSlider(bxSliderSettings());
            $(window).on('resize', function () {
                thumbSlider.reloadSlider(bxSliderSettings());
            });
            @endif

            @if (config('settings.single.show_post_on_googlemap'))
            /* Google Maps */
            getGoogleMaps(
                '{{ config('services.googlemaps.key') }}',
                '{{ (isset($post->city) and !empty($post->city)) ? addslashes($post->city->name) . ',' . config('country.name') : config('country.name') }}',
                '{{ config('app.locale') }}'
            );
            @endif

            /* Keep the current tab active with Twitter Bootstrap after a page reload */
            /* For bootstrap 3 use 'shown.bs.tab', for bootstrap 2 use 'shown' in the next line */
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                /* save the latest tab; use cookies if you like 'em better: */
                localStorage.setItem('lastTab', $(this).attr('href'));
            });
            /* Go to the latest tab, if it exists: */
            var lastTab = localStorage.getItem('lastTab');
            if (lastTab) {
                $('[href="' + lastTab + '"]').tab('show');
            }



            $(".bx-controls-direction").append("<div class='next' ><i class='unib-rarrow2' ></i></div>");
            $(".bx-controls-direction").append("<div class='prev' ><i class='unib-larrow2'></i></div>");
            $(".bx-controls-direction").append("<div class='prev' ><i class='unib-larrow2'></i></div>");
            $(".product-view-thumb-wrapper .bx-controls .next").remove();
            $(".product-view-thumb-wrapper .bx-controls .prev").remove();


        });



        $(".div.bx-viewport").ready(function () {
            var height = "460px";

            $(".bx-next").attr("style", " height: " + height + " !important;");
            $(".bx-prev").attr("style", " height: " + height + " !important;");

        });

        $(window).resize(function () {
            if ($(document).width() < 767) {
                var text = "<?php echo t("Use all links")  ?>";
                $("#terms>a").text(text);
            } else {
                $("#terms>a").text(" <?php echo t("Terms & Conditions") ?> ");
            }
        });

        if ($(window).width() <= 992) {

            $(".footer-content .row").attr("style", "padding-bottom: 40px;");


            $(".unir-phone.btn.btn-success.phoneBlock.btn-block").on("click", function () {
                $(".new-button.phoneBtn .btn-user-card").parent("click");
                console.log("clicked");
            });
        }

        var modal_userInfo = false;



        if ($(window).width() >= 992) {//убираем функционал звонка с pc
            $(".new-button.phoneBtn .btn-user-card").removeAttr("href");

        }


        $(".make-favorite").on("click", function () {
            $(".right15").on('load', function () {
                // console.log("Ready");
                $(".make-favorite .right15").attr("style", "margin-right: 5px;");
            });

        });

        /* bxSlider - Initiates Responsive Carousel */
        function bxSliderSettings() {
            var smSettings = {
                slideWidth: 65,
                minSlides: 1,
                maxSlides: 4,
                slideMargin: 5,
                adaptiveHeight: true,
                pager: false
            };
            var mdSettings = {
                slideWidth: 100,
                minSlides: 1,
                maxSlides: 4,
                slideMargin: 5,
                adaptiveHeight: true,
                pager: false
            };
            var lgSettings = {
                slideWidth: 100,
                minSlides: 3,
                maxSlides: 6,
                pager: false,
                slideMargin: 10,
                adaptiveHeight: true
            };

            if ($(window).width() <= 640) {
                return smSettings;
            } else if ($(window).width() > 640 && $(window).width() < 768) {
                return mdSettings;
            } else {
                return lgSettings;
            }
        }


    </script>
@endsection
