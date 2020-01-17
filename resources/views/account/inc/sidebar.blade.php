<div class="modal fade" id="deleteAcc" tabindex="-1" role="dialog">
    <div class="modal-dialog  modal-sm">
        <div class="modal-content modal-content-dif">

            <div class="modal-header modal-header-dif">
                <h2 class="modal-title"> {{ t('Close account') }} </h2>

                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true"><i class="unir-close"></i></span>
                    <span class="sr-only">{{ t('Close') }}</span>
                </button>
            </div>

            @if ($user->can(\App\Models\Permission::getStaffPermissions()))
                <div class="alert alert-danger" role="alert">
                    {{ t('Admin users can\'t be deleted by this way.') }}
                </div>
            @else
                <form role="form" method="POST" action="{{ lurl('account/close') }}">
                    {!! csrf_field() !!}
                    <div class="modal-body modal-body-dif">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <p>{{ t('You are sure you want to close your account?') }}</p>
                                <div class="form-check form-check-inline pt-2">
                                    <!-- <label class="form-check-label">
                                        <input class="form-check-input"
                                               type="radio"
                                               name="close_account_confirmation"
                                               id="closeAccountConfirmation1"
                                               value="1"
                                        > {{ t('Yes') }}
                                    </label> -->
                                    <label for="closeAccountConfirmation1" class="radio">
                                        <input type="radio" 
                                                name="close_account_confirmation" 
                                                id="closeAccountConfirmation1" 
                                                value="1" 
                                                class="hidden" />
                                        <span class="label"></span>{{ t('Yes') }}
                                    </label>
                                </div>
                                <div class="form-check form-check-inline pt-2">
                                    <!-- <label class="form-check-label">
                                        <input class="form-check-input"
                                               type="radio"
                                               name="close_account_confirmation"
                                               id="closeAccountConfirmation0"
                                               value="0" checked
                                        > {{ t('No') }}
                                    </label> -->
                                    <label for="closeAccountConfirmation0" class="radio">
                                        <input type="radio" 
                                                name="close_account_confirmation" 
                                                id="closeAccountConfirmation0" 
                                                value="0" checked
                                                class="hidden" />
                                        <span class="label"></span>{{ t('No') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer modal-footer-dif">
                        <button type="submit" class="btn btn-primary pull-right btn-dif">{{ t('Confirm') }}</button>
                        <button type="button" class="btn btn-default btn-default-dif btn-modal" data-dismiss="modal">{{ t('Cancel') }}</button>
                    </div>
                </form>
            @endif

{{--            <form role="form" method="POST" action="{{ lurl(trans('routes.login')) }}">--}}
{{--                {!! csrf_field() !!}--}}
{{--                <div class="modal-body modal-body-dif">--}}
{{--                    <input type="hidden" name="quickLoginForm" value="1">--}}
{{--                </div>--}}
{{--                <div class="modal-footer modal-footer-dif">--}}
{{--                    <button type="submit" class="btn btn-success pull-right btn-dif">{{ t('Confirm') }}</button>--}}
{{--                </div>--}}
{{--            </form>--}}

        </div>
    </div>
</div>

<aside>
    <div class="inner-box">
        <div class="user-panel-sidebar">

            <div class="collapse-box">
                <h5 class="collapse-title no-border">
                    {{ t('My Account') }}&nbsp;
                    <a href="#MyClassified" data-toggle="collapse" class="pull-right"><i class="fa fa-angle-down"></i></a>
                </h5>
                <div class="panel-collapse collapse show" id="MyClassified">
                    <ul class="acc-list">
                        <li>
                            <a class="navbar-list-item {!! ($pagePath=='') ? 'active' : '' !!}" href="{{ lurl('account') }}">
                                {{--								<i class="icon-home"></i> {{ t('Personal Home') }}--}}
                                <i class="unir-settings sidebar-icon"></i> {{ t('Home') }}
                            </a>
                        </li>
                        <li>
                            <a class="navbar-list-item {!! ($pagePath=='my-posts') ? 'active' : '' !!}" href="{{ lurl('account/my-posts') }}">
                                {{--							<i class="icon-docs"></i> {{ t('My ads') }}&nbsp;--}}
                                <i class="unir-ads sidebar-icon"></i> {{ t('My ads') }}
                                <span class="badge badge-pill badge-dif">
							{{ isset($countMyPosts) ? \App\Helpers\Number::short($countMyPosts) : 0 }}
						</span>
                            </a>
                        </li>
                        <li>
                            <a class="navbar-list-item {!! ($pagePath=='favourite') ? 'active' : '' !!}" href="{{ lurl('account/favourite') }}">
                                {{--							<i class="icon-heart"></i> {{ t('Favourite ads') }}&nbsp;--}}
                                <i class="unir-heart sidebar-icon"></i> {{ t('Favourite ads') }}
                                <span class="badge badge-pill badge-dif">
							{{ isset($countFavouritePosts) ? \App\Helpers\Number::short($countFavouritePosts) : 0 }}
						</span>
                            </a>
                        </li>
                        {{--						<li>--}}
                        {{--							<a{!! ($pagePath=='saved-search') ? ' class="active"' : '' !!} href="{{ lurl('account/saved-search') }}">--}}
                        {{--							<i class="icon-star-circled"></i> {{ t('Saved searches') }}&nbsp;--}}
                        {{--							<span class="badge badge-pill">--}}
                        {{--								{{ isset($countSavedSearch) ? \App\Helpers\Number::short($countSavedSearch) : 0 }}--}}
                        {{--							</span>--}}
                        {{--							</a>--}}
                        {{--						</li>--}}
                        <li>
                            <a class="navbar-list-item {!! ($pagePath=='pending-approval') ? 'active' : '' !!}" href="{{ lurl('account/pending-approval') }}">
                                {{--							<i class="icon-hourglass"></i> {{ t('Pending approval') }}&nbsp;--}}
                                <i class="unir-clock sidebar-icon"></i> {{ t('Rejected ads') }}
                                <span class="badge badge-pill badge-dif">
							{{ isset($countPendingPosts) ? \App\Helpers\Number::short($countPendingPosts) : 0 }}
						</span>
                            </a>
                        </li>
                        <li>
                            <a class="navbar-list-item {!! ($pagePath=='archived') ? 'active' : '' !!}" href="{{ lurl('account/archived') }}">
                                {{--							<i class="icon-folder-close"></i> {{ t('Archived ads') }}&nbsp;--}}
                                <i class="unir-folder sidebar-icon"></i> {{ t('Archived ads') }}
                                <span class="badge badge-pill badge-dif">
							{{ isset($countArchivedPosts) ? \App\Helpers\Number::short($countArchivedPosts) : 0 }}
						</span>
                            </a>
                        </li>
                        <li>
                            <a class="navbar-list-item {!! ($pagePath=='conversations') ? 'active' : '' !!}" href="{{ lurl('account/conversations') }}">
                                {{--							<i class="icon-mail-1"></i> {{ t('Conversations') }}&nbsp;--}}
                                <i class="unir-mail sidebar-icon"></i> {{ t('Conversations') }}
                                <span class="badge badge-pill badge-dif">
									{{ isset($countConversations) ? \App\Helpers\Number::short($countConversations) : 0 }}
								</span>&nbsp;
                                {{--								@if(isset($noReadConversations) && $noReadConversations > 0)--}}
                            <!-- <span class="badge badge-pill badge-important count-conversations-with-new-messages badge-dif-imp">0</span> -->
                                <!-- <div class="count-conversations-with-new-messages" id="badge-nm">0</div> -->
                                <img class="count-conversations-with-new-messages" id="badge-notif-nm" src="/images/notifications.svg" alt="">
                                {{--								@endif--}}
                            </a>
                        </li>
                        {{--						<li>--}}
                        {{--							<a{!! ($pagePath=='transactions') ? ' class="active"' : '' !!} href="{{ lurl('account/transactions') }}">--}}
                        {{--							<i class="icon-money"></i> {{ t('Transactions') }}&nbsp;--}}
                        {{--							<span class="badge badge-pill">--}}
                        {{--								{{ isset($countTransactions) ? \App\Helpers\Number::short($countTransactions) : 0 }}--}}
                        {{--							</span>--}}
                        {{--							</a>--}}
                        {{--						</li>--}}
                        @if (config('plugins.apilc.installed'))
                            <li>
                                <a class="navbar-list-item {!! ($pagePath=='close') ? 'active' : '' !!}" href="{{ lurl('account/api-dashboard') }}">
                                    <i class="icon-cog"></i> {{ trans('api::messages.Clients & Applications') }}&nbsp;
                                </a>
                            </li>
                        @endif
                        @if (app('impersonate')->isImpersonating())
                            <li>
                                <a class="navbar-list-item" href="{{ route('impersonate.leave') }}">
                                    {{--								<i class="icon-home"></i> {{ t('Personal Home') }}--}}
                                    <i class="unir-exit sidebar-icon"></i> {{ t('Leave') }}
                                </a>
                            </li>
                        @else
                            <li>
                                <a class="navbar-list-item" href="{{ lurl(trans('routes.logout')) }}">
                                    {{--								<i class="icon-home"></i> {{ t('Personal Home') }}--}}
                                    <i class="unir-exit sidebar-icon"></i> {{ t('Log Out') }}
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
            <!-- /.collapse-box  -->

        {{--			<div class="collapse-box">--}}
        {{--				<h5 class="collapse-title no-border">--}}
        {{--					{{ t('My Ads') }}--}}
        {{--					<a href="#MyAds" data-toggle="collapse" class="pull-right"><i class="fa fa-angle-down"></i></a>--}}
        {{--				</h5>--}}
        {{--				<div class="panel-collapse collapse show" id="MyAds">--}}
        {{--					<ul class="acc-list">--}}
        {{--						<li>--}}
        {{--							<a class="navbar-list-item {!! ($pagePath=='close') ? 'active' : '' !!}" href="{{ lurl('account/my-posts') }}">--}}
        {{--							<i class="icon-docs"></i> {{ t('My ads') }}&nbsp;--}}
        {{--							<i class="unir-ads sidebar-icon"></i> {{ t('My ads') }}--}}
        {{--							<span class="badge badge-pill badge-dif">--}}
        {{--							{{ isset($countMyPosts) ? \App\Helpers\Number::short($countMyPosts) : 0 }}--}}
        {{--						</span>--}}
        {{--							</a>--}}
        {{--						</li>--}}
        {{--						<li>--}}
        {{--							<a class="navbar-list-item {!! ($pagePath=='close') ? 'active' : '' !!}" href="{{ lurl('account/favourite') }}">--}}
        {{--							<i class="icon-heart"></i> {{ t('Favourite ads') }}&nbsp;--}}
        {{--							<i class="unir-heart sidebar-icon"></i> {{ t('Favourite ads') }}--}}
        {{--							<span class="badge badge-pill badge-dif">--}}
        {{--							{{ isset($countFavouritePosts) ? \App\Helpers\Number::short($countFavouritePosts) : 0 }}--}}
        {{--						</span>--}}
        {{--							</a>--}}
        {{--						</li>--}}
        {{--						--}}{{--						<li>--}}
        {{--						--}}{{--							<a{!! ($pagePath=='saved-search') ? ' class="active"' : '' !!} href="{{ lurl('account/saved-search') }}">--}}
        {{--						--}}{{--							<i class="icon-star-circled"></i> {{ t('Saved searches') }}&nbsp;--}}
        {{--						--}}{{--							<span class="badge badge-pill">--}}
        {{--						--}}{{--								{{ isset($countSavedSearch) ? \App\Helpers\Number::short($countSavedSearch) : 0 }}--}}
        {{--						--}}{{--							</span>--}}
        {{--						--}}{{--							</a>--}}
        {{--						--}}{{--						</li>--}}
        {{--						<li>--}}
        {{--							<a class="navbar-list-item {!! ($pagePath=='close') ? 'active' : '' !!}" href="{{ lurl('account/pending-approval') }}">--}}
        {{--							<i class="icon-hourglass"></i> {{ t('Pending approval') }}&nbsp;--}}
        {{--							<i class="unir-clock sidebar-icon"></i> {{ t('Pending approval') }}--}}
        {{--							<span class="badge badge-pill badge-dif">--}}
        {{--							{{ isset($countPendingPosts) ? \App\Helpers\Number::short($countPendingPosts) : 0 }}--}}
        {{--						</span>--}}
        {{--							</a>--}}
        {{--						</li>--}}
        {{--						<li>--}}
        {{--							<a class="navbar-list-item {!! ($pagePath=='close') ? 'active' : '' !!}" href="{{ lurl('account/archived') }}">--}}
        {{--							<i class="icon-folder-close"></i> {{ t('Archived ads') }}&nbsp;--}}
        {{--							<i class="unir-folder sidebar-icon"></i> {{ t('Archived ads') }}--}}
        {{--							<span class="badge badge-pill badge-dif">--}}
        {{--							{{ isset($countArchivedPosts) ? \App\Helpers\Number::short($countArchivedPosts) : 0 }}--}}
        {{--						</span>--}}
        {{--							</a>--}}
        {{--						</li>--}}
        {{--						<li>--}}
        {{--							<a class="navbar-list-item {!! ($pagePath=='close') ? 'active' : '' !!}" href="{{ lurl('account/conversations') }}">--}}
        {{--							<i class="icon-mail-1"></i> {{ t('Conversations') }}&nbsp;--}}
        {{--							<i class="unir-mail sidebar-icon"></i> {{ t('Conversations') }}--}}
        {{--							<span class="badge badge-pill badge-dif">--}}
        {{--							{{ isset($countConversations) ? \App\Helpers\Number::short($countConversations) : 0 }}--}}
        {{--						</span>&nbsp;--}}
        {{--							<span class="badge badge-pill badge-important count-conversations-with-new-messages badge-dif-imp">0</span>--}}
        {{--							</a>--}}
        {{--						</li>--}}
        {{--						--}}{{--						<li>--}}
        {{--						--}}{{--							<a{!! ($pagePath=='transactions') ? ' class="active"' : '' !!} href="{{ lurl('account/transactions') }}">--}}
        {{--						--}}{{--							<i class="icon-money"></i> {{ t('Transactions') }}&nbsp;--}}
        {{--						--}}{{--							<span class="badge badge-pill">--}}
        {{--						--}}{{--								{{ isset($countTransactions) ? \App\Helpers\Number::short($countTransactions) : 0 }}--}}
        {{--						--}}{{--							</span>--}}
        {{--						--}}{{--							</a>--}}
        {{--						--}}{{--						</li>--}}
        {{--						@if (config('plugins.apilc.installed'))--}}
        {{--							<li>--}}
        {{--								<a class="navbar-list-item {!! ($pagePath=='close') ? 'active' : '' !!}" href="{{ lurl('account/api-dashboard') }}">--}}
        {{--								<i class="icon-cog"></i> {{ trans('api::messages.Clients & Applications') }}&nbsp;--}}
        {{--								</a>--}}
        {{--							</li>--}}
        {{--						@endif--}}
        {{--					</ul>--}}
        {{--				</div>--}}
        {{--			</div>--}}
        <!-- /.collapse-box  -->

            <div class="collapse-box no-border">
                <h5 class="collapse-title no-border">
                    {{ t('Terminate Account') }}&nbsp;
                    <a href="#TerminateAccount" data-toggle="collapse" class="pull-right collapsed" aria-expanded="false"><i class="fa fa-angle-down"></i></a>
                </h5>
                <div class="panel-collapse collapse" id="TerminateAccount">
                    <ul class="acc-list">
                        <li>
                            <a href="#deleteAcc" class="navbar-list-item {!! ($pagePath=='close') ? 'active' : '' !!}" href="{{ lurl('account/close') }}" data-toggle="modal">
                                {{--								<i class="icon-cancel-circled "></i> {{ t('Close account') }}--}}
                                <i class="unir-close sidebar-icon"></i> {{ t('Close account') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- /.collapse-box  -->

        </div>
    </div>
    <!-- /.inner-box  -->
</aside>

{{--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>--}}
{{--<script type="text/javascript">--}}
{{--	$(document).ready(function() {--}}
{{--		var item = $(".acc-list>li");--}}
{{--		$(".acc-list>li").on("click", function () {--}}
{{--			console.log(this);--}}
{{--			item.removeClass("active-link");--}}
{{--			$(this).addClass("active-link");--}}
{{--			localStorage.setItem("blockIsActive", $(this).hasClass('active-link'));--}}
{{--		})--}}

{{--	})--}}
{{--</script>--}}