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
			<div class="row">
				
				@if (Session::has('flash_notification'))
					<div class="col-xl-12">
						<div class="row">
							<div class="col-xl-12">
								@include('flash::message')
							</div>
						</div>
					</div>
				@endif
				
				<div class="col-md-3 page-sidebar">
					@include('account.inc.sidebar')
				</div>
				<!--/.page-sidebar-->
				
				<div class="col-md-9 page-content">
					<div class="inner-box inner-box-dif">
						<h2 class="title-2 title-2-mob">
							{{ t('Conversations') }}
						</h2>
						<div id="reloadBtn" class="mb30" style="display: none;">
							<a href="" class="btn btn-primary" class="tooltipHere" title="" data-placement="{{ (config('lang.direction')=='rtl') ? 'left' : 'right' }}"
							   data-toggle="tooltip"
							   data-original-title="{{ t('Reload to see New Messages') }}"><i class="icon-arrows-cw"></i> {{ t('Reload') }}</a>
							<br><br>
						</div>
						
						<div style="clear:both"></div>
						
						<div class="table-responsive">
							<form name="listForm" method="POST" action="{{ lurl('account/'.$pagePath.'/delete') }}">
								{!! csrf_field() !!}
								<div class="table-action table-action-dif">
									<label for="checkAll" class="btn-archive">
{{--										<input type="checkbox" id="checkAll">--}}
{{--										{{ t('Select') }}: {{ t('All') }} |--}}
										<button type="submit" class="btn btn-sm btn-default delete-action btn-default-cab btn-grey">
											{{ t('Delete') }}
										</button>
									</label>
									<div class="table-search pull-right col-sm-7 table-search-cab">
										<div class="form-group">
											<div class="row row-dif">
{{--												<label class="col-sm-5 control-label text-right">{{ t('Search') }} <br>--}}
{{--													<a title="clear filter" class="clear-filter" href="#clear">[{{ t('clear') }}]</a>--}}
{{--												</label>--}}
												<div class="col-sm-7 searchpan padding-2 search-mob">
													<input type="text" class="form-control" id="filter" placeholder="Search">
												</div>
												<div class="flexcol-button padding-2">
													<button class="btn btn-block btn-primary">
														<img src="/images/search.svg">
													</button>
												</div>
											</div>
										</div>
									</div>
								</div>
								
								<table id="addManageTable" class="table table-bordered add-manage-table table demo table-dif" data-filter="#filter" data-filter-text-only="true">
									<thead class="table-head">
										<tr>
											<th style="width:2%" data-type="numeric" data-sort-initial="true" class="cel-borderless" align="center">
											<div class="cntr">
												<label class="label-cbt">
												<input type="checkbox" id="checkAll" class="invisible">
												<div class="checkbox">
													<svg width="14px" height="14px" viewBox="0 0 14 14">
													<path d="M3,1 L17,1 L17,1 C18.1045695,1 19,1.8954305 19,3 L19,17 L19,17 C19,18.1045695 18.1045695,19 17,19 L3,19 L3,19 C1.8954305,19 1,18.1045695 1,17 L1,3 L1,3 C1,1.8954305 1.8954305,1 3,1 Z"></path>
													<polyline points="4 8 6 10 11 5"></polyline>
													</svg>
												</div>
												</label>
											</div>
											<!-- <input type="checkbox" id="checkAll"> -->
											</th>
											<th style="width:98%" data-sort-ignore="true" class="cel-borderless">{{ t('Conversations') }}</th>
	{{--										<th style="width:10%">{{ t('Option') }}</th>--}}
										</tr>
									</thead>
									<tbody>
									<?php
									if (isset($conversations) && $conversations->count() > 0):
										foreach($conversations as $key => $conversation):
									?>
										<tr>
											<td class="add-img-selector cel-borderless">
												<!-- <div class="checkbox">
													<label>
														<input type="checkbox" name="entries[]" value="{{ $conversation->id }}">
													</label>
												</div> -->
												<div class="cntr">
												<label class="label-cbt">
													<input type="checkbox" name="entries[]" value="{{ $conversation->id }}" class="invisible">
													<div class="checkbox">
														<svg width="14px" height="14px" viewBox="0 0 14 14">
														<path d="M3,1 L17,1 L17,1 C18.1045695,1 19,1.8954305 19,3 L19,17 L19,17 C19,18.1045695 18.1045695,19 17,19 L3,19 L3,19 C1.8954305,19 1,18.1045695 1,17 L1,3 L1,3 C1,1.8954305 1.8954305,1 3,1 Z"></path>
														<polyline points="4 8 6 10 11 5"></polyline>
														</svg>
													</div>
												</label>
											</div>
											</td>
											<td class="cel-borderless">
												<div style="word-break:break-all;">
													<div class="conversation-info receive-info">
														<strong class="conversation-main">{{ t('Date') }}:</strong>
														&nbsp{{ $conversation->created_at->formatLocalized(config('settings.app.default_datetime_format_mod')) }}
														@if (\App\Models\Message::conversationHasNewMessages($conversation))
															<!-- <span class="badge badge-pill badge-important new-messages badge-dif-imp">{{ $conversation->new_messages }}</span> -->
															<!-- <div style="width: 18px; height: 18px; line-height: 19px;" class="new-messages" id="badge-nm">{{ $conversation->new_messages }}</div> -->
															<img class="new-messages" id="badge-notif-nm" src="/images/notifications.svg" alt="">
														@endif
													</div>
{{--													<br>--}}
													<div class="conversation-info">
														<strong class="conversation-main">{{ t('Ad') }}:</strong>&nbsp{{ $conversation->subject }}
													</div>
													<div class="conversation-info">
														<strong class="conversation-main">{{ t('Sender') }}:</strong>&nbsp{{ \Illuminate\Support\Str::limit($conversation->from_name, 50) ?? "Unregistered user" }}
														{!! (!empty($conversation->filename) and $disk->exists($conversation->filename)) ? ' <i class="icon-attach-2"></i> ' : '' !!}&nbsp;
														|&nbsp;
														<a href="{{ lurl('account/conversations/' . $conversation->id . '/messages') }}">
															{{  t('Message') }}
														</a>
													</div>
												</div>
											</td>
	{{--										<td class="action-td">--}}
	{{--											<div>--}}
	{{--												<p>--}}
	{{--													<a class="btn btn-default btn-sm" href="{{ lurl('account/conversations/' . $conversation->id . '/messages') }}">--}}
	{{--														<i class="icon-eye"></i> {{ t('View') }}--}}
	{{--													</a>--}}
	{{--												</p>--}}
	{{--												<p>--}}
	{{--													<a class="btn btn-danger btn-sm delete-action" href="{{ lurl('account/conversations/' . $conversation->id . '/delete') }}">--}}
	{{--														<i class="fa fa-trash"></i> {{ t('Spam') }}--}}
	{{--													</a>--}}
	{{--												</p>--}}
	{{--											</div>--}}
	{{--										</td>--}}
										</tr>
									<?php endforeach; ?>
									<?php endif; ?>
									</tbody>
								</table>
							</form>
						</div>
						
						<nav class="" aria-label="">
							{{ (isset($conversations)) ? $conversations->links() : '' }}
						</nav>
						
						<div style="clear:both"></div>
					
					</div>
				</div>
				<!--/.page-content-->
				
			</div>
			<!--/.row-->
		</div>
		<!--/.container-->
	</div>
	<!-- /.main-container -->

@endsection

@section('after_scripts')
	<script src="{{ url('assets/js/footable.js?v=2-0-1') }}" type="text/javascript"></script>
	<script src="{{ url('assets/js/footable.filter.js?v=2-0-1') }}" type="text/javascript"></script>
	<script type="text/javascript">
		$(function () {
			$('#addManageTable').footable().bind('footable_filtering', function (e) {
				var selected = $('.filter-status').find(':selected').text();
				if (selected && selected.length > 0) {
					e.filter += (e.filter && e.filter.length > 0) ? ' ' + selected : selected;
					e.clear = !e.filter;
				}
			});
			
			$('.clear-filter').click(function (e) {
				e.preventDefault();
				$('.filter-status').val('');
				$('table.demo').trigger('footable_clear_filter');
			});
			
			$('#checkAll').click(function () {
				checkAll(this);
			});
			
			{{--$('a.delete-action, button.delete-action').click(function(e)--}}
			{{--{--}}
			{{--	e.preventDefault(); /* prevents the submit or reload */--}}
			{{--	var confirmation = confirm("{{ t('confirm_this_action') }}");--}}
			{{--	--}}
			{{--	if (confirmation) {--}}
			{{--		if( $(this).is('a') ){--}}
			{{--			var url = $(this).attr('href');--}}
			{{--			if (url !== 'undefined') {--}}
			{{--				redirect(url);--}}
			{{--			}--}}
			{{--		} else {--}}
			{{--			$('form[name=listForm]').submit();--}}
			{{--		}--}}
			{{--	}--}}
			{{--	--}}
			{{--	return false;--}}
			{{--});--}}
		});
	</script>
	<!-- include custom script for ads table [select all checkbox]  -->
	<script>
		function checkAll(bx) {
			var chkinput = document.getElementsByTagName('input');
			for (var i = 0; i < chkinput.length; i++) {
				if (chkinput[i].type == 'checkbox') {
					chkinput[i].checked = bx.checked;
				}
			}
		}
	</script>
@endsection