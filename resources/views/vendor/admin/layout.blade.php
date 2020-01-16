<?php

use LiveControl\EloquentDataTable\DataTable;

if (isset($title)) {
    $title = strip_tags($title);
}
?>
<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Encrypted CSRF token for Laravel, in order for Ajax requests to work --}}
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>
      {!! isset($title) ? $title . ' :: ' . config('app.name').' Admin' : config('app.name').' Admin' !!}
    </title>

    @yield('before_styles')
    <style>
        .user-info-modal, .reject-info-modal {
            display: none;
            position: fixed;
            z-index: 1000000;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
        }
        .user-modal-content {
            padding: 0;
            border-radius: .3rem;
        }
        .user-modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            width: 30%;
        }
        .modal-header.modal-header-dif {
            display: flex;
            align-items: center;
            background: #f7f9f9;
            border: none;
            padding: 8px 30px 8px 30px;
            height: 80px;
        }
        .modal-header.modal-header-dif > h2{
            font-weight: bold;
        }
        .modal-header .close {
            margin-top: -2px;
        }
        .reject-info-modal .close {
            margin-left: 50% !important;
        }
        button.close {
            -webkit-appearance: none;
            padding: 0;
            cursor: pointer;
            background: 0 0;
            border: 0;
        }
        .close {
            float: right;
            font-size: 21px;
            font-weight: 700;
            line-height: 1;
            color: #000;
            text-shadow: 0 1px 0 #fff;
            filter: alpha(opacity=20);
            opacity: .2;
        }
        .modal-body.modal-body-user {
            padding-bottom: 16px;
        }
        .modal-body.modal-body-dif {
            padding: 16px 30px 0 30px;
        }
    </style>

    <link href="https://market.unifun.com/css/style.css" rel="stylesheet">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/dist/css/skins/_all-skins.min.css">

    <link rel="stylesheet" href="{{ asset('vendor/adminlte/') }}/plugins/pace/pace.min.css">
    <link rel="stylesheet" href="{{ asset('vendor/admin/pnotify/pnotify.custom.min.css') }}">
    <link href="{{ url('css/custom.css') . getPictureVersion() }}" rel="stylesheet">

    <!-- Admin Global CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/admin/style.css') . vTime() }}">

    @yield('after_styles')

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition {{ config('larapen.admin.skin') }} sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">

      <header class="main-header">
        <!-- Logo -->
        <a href="{{ url('') }}" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini">{!! config('larapen.admin.logo_mini') !!}</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg">
              <strong>{!! strtoupper(\Illuminate\Support\Str::limit(config('app.name'), 15, '.')) !!}</strong>
          </span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">{{ trans('admin::messages.toggle_navigation') }}</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>

          @include('admin::inc.menu')
        </nav>
      </header>

      <!-- =============================================== -->

      @include('admin::inc.sidebar')

      <!-- =============================================== -->

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
         @yield('header')

        <!-- Main content -->
        <!-- <section class="content" style="display: flex"> -->
        <section class="content">

        <!-- R.S -->
        <div class="reject-info-modal " style="display:none">
            <div class="user-modal-content">
                <div class="modal-header modal-header-dif">
                    <h2 class="modal-title">
                        Rejection reason
                    </h2>
                    <button type="button" class="close" data-dismiss="modal">                             
                        <span aria-hidden="true"><i class="unir-close"></i></span>
                        <span class="sr-only">{{ t('Close') }}</span>
                    </button>
                </div>

                <div class="modal-body modal-body-dif modal-body-user">
                    <div class="block-cell user">
                        <div class="cell-media">
                        </div>

                        <div class="cell-content">
                            
                            <form  id="rejectReason" role="form" method="POST">
                                 <input  name='_token' id='tokenForm' type="hidden" value="{{ csrf_token() }}">
                                 <input  name='tableInfo' id='tableInfo' type="hidden">
                                <div class="form-group required">
                                    <div class="form-check">
                                        <label for="reason-0"  class="radio">
                                            <input type="radio" name='reason' id='reason-0' value="0" class="hidden">
                                            <span class="label"></span>
                                            {{ t('The ad does not correspond Posting Rules') }}
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <label for="reason-1" class="radio">
                                            <input type="radio" name='reason' id='reason-1' value="3" class="hidden">
                                            <span class="label"></span>
                                            {{ t('The ad does not correspond selected Category or Sub-Category.') }}
                                        </label>
                                    </div>
                                </div>

                                <!-- Submit -->
                                <div class="form-group">
                                    <button type="submit" class="btn btn-green btn-block btn-dif "> {{ t('Reject') }} </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>

          @yield('content')

        </section>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->

      <footer class="main-footer">
        @if (config('larapen.admin.show_powered_by'))
            <div class="pull-right hidden-xs">
                @if (config('settings.footer.powered_by_info'))
                    {{ trans('admin::messages.powered_by') }} {!! config('settings.footer.powered_by_info') !!}
                @else
					{{ trans('admin::messages.powered_by') }} <a target="_blank" href="http://www.bedigit.com">Bedigit</a>.
                @endif
            </div>
        @endif
        {{ trans('admin::messages.Version') }} {{ config('app.version') }}
      </footer>
    </div>
    <!-- ./wrapper -->


    @yield('before_scripts')

	<script>
		var siteUrl = '<?php echo url('/'); ?>';
	</script>

    <!-- jQuery 2.2.0 -->
    <script src="https://code.jquery.com/jquery-2.2.0.min.js"></script>
    <script>window.jQuery || document.write('<script src="{{ asset('vendor/adminlte') }}/plugins/jQuery/jquery-2.2.0.min.js"><\/script>')</script>
    <!-- Bootstrap 3.3.5 -->
    <script src="{{ asset('vendor/adminlte') }}/bootstrap/js/bootstrap.min.js"></script>
    <script src="{{ asset('vendor/adminlte') }}/plugins/pace/pace.min.js"></script>
    <script src="{{ asset('vendor/adminlte') }}/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <script src="{{ asset('vendor/adminlte') }}/plugins/fastclick/fastclick.js"></script>
    <script src="{{ asset('vendor/adminlte') }}/dist/js/app.min.js"></script>

    <script src="{{ asset('vendor/admin/script.js') }}"></script>

    <!-- page script -->
    <script type="text/javascript">
        /* To make Pace works on Ajax calls */
        $(document).ajaxStart(function() { Pace.restart(); });
        /* Ajax calls should always have the CSRF token attached to them, otherwise they won't work */
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        /* Set active state on menu element */
        var current_url = "{{ url(Route::current()->uri()) }}";
        $("ul.sidebar-menu li a").each(function() {
            if ($(this).attr('href').startsWith(current_url) || current_url.startsWith($(this).attr('href')))
            {
                $(this).parents('li').addClass('active');
            }
        });
    </script>
    <script>
        // R.S
        var modal_reason = false;

        $(document).ready(function()
        {
            /* Send an ajax update request */
            $(document).on('click', '.ajax-request', function(e)
            {
                e.preventDefault(); /* prevents the submit or reload */
                var confirmation = confirm("<?php echo trans('admin::messages.confirm_this_action'); ?>");

                if (confirmation) {
                    console.log(this);
                    saveAjaxRequest(siteUrl, this);
                }
            });



            $( "#rejectReason" ).submit( function( data ) {
                var value = $(this);

                var $inputs = $('#rejectReason :input');

                var values = {};
                $inputs.each(function() {
                    values[this.name] = $(this).val();
                });

                // var data = {};
                data.table = values.table;
                data.field = values.field;
                data.id = values.id;
                data.lineId = values.lineId;

                // var value = values.reason;

                var value =  $("input[name='reason']:checked").val();

                saveReviewedAjaxRequest(siteUrl, data, value);
            });

            //E.K.
            $(document).on('click', '.reviewed-request', function(e)
            {

                
                e.preventDefault(); /* prevents the submit or reload */
                
                // R.S
                if($(this).data().value == 0){
                    //click to open btn
                    
                    if(modal_reason == false){
                        $(".reject-info-modal").attr("style","display:block;");
                        modal_reason = true;

                        var $self = $(this); /* magic here! */
                        let value = $(this).data().value;

                        /* Get database info */
                        var tableInfo = $($(this).parent().prev()).data();
                        
                        $.each(tableInfo, function(key , value){
                            

                            $("#tableInfo").after(
                                "<input type='hidden' name='" + key + "' value='" + value + "' >"
                            );
                        });
                    }

                }
                else{
                    var confirmation = confirm("<?php echo trans('admin::messages.confirm_this_action'); ?>");
                    if (confirmation) {
                        let value = $(this).data().value;
                        console.log(value) ;
                        console.log($(this).parent().prev().data()) ;

                        saveReviewedAjaxRequest(siteUrl, $(this).parent().prev().data(), value);
                    }
                }
            });
        });

        function saveAjaxRequest(siteUrl, el)
        {
        	if (isDemo()) {
				return false;
			}

            var $self = $(this); /* magic here! */

            /* Get database info */
            var _token = $('input[name=_token]').val();
            var dataTable = $(el).data('table');
            var dataField = $(el).data('field');
            var dataId = $(el).data('id');
            var dataLineId = $(el).data('lineId');
            var dataValue = $(el).data('value');
            
            /* Remove dot (.) from var (referring to the PHP var) */
            dataLineId = dataLineId.split('.').join("");

            
            $.ajax({
                method: 'POST',
                url: siteUrl + '/<?php echo admin_uri(); ?>/ajax/' + dataTable + '/' + dataField + '',
                context: this,
                data: {
                    'primaryKey': dataId,
                    '_token': _token
                }
            }).done(function(data) {
				/* Check 'status' */
                if (data.status != 1) {
                    return false;
                }

                /* Decoration */
                if (data.table == 'countries' && dataField == 'active')
                {
                    if (!data.resImport) {
						new PNotify({
							text: "{{ trans('admin::messages.Error - You can\'t install this country.') }}",
							type: "error"
						});

                        return false;
                    }

                    if (data.isDefaultCountry == 1) {
						new PNotify({
							text: "{{ trans('admin::messages.You can not disable the default country') }}",
							type: "warning"
						});

                        return false;
                    }

                    /* Country case */
                    if (data.fieldValue == 1) {
                        $('#' + dataLineId).removeClass('fa fa-toggle-off').addClass('fa fa-toggle-on');
                        $('#install' + dataId).removeClass('btn-default').addClass('btn-success').empty().html('<i class="fa fa-download"></i> <?php echo trans('admin::messages.Installed'); ?>');
                    } else {
                        $('#' + dataLineId).removeClass('fa fa-toggle-on').addClass('fa fa-toggle-off');
                        $('#install' + dataId).removeClass('btn-success').addClass('btn-default').empty().html('<i class="fa fa-download"></i> <?php echo trans('admin::messages.Install'); ?>');
                    }
                }
                else
                {
                    /* All others cases */
                    if (data.fieldValue == 1) {
                        $('#' + dataLineId).removeClass('fa fa-toggle-off').addClass('fa fa-toggle-on').blur();
                    } else {
                        $('#' + dataLineId).removeClass('fa fa-toggle-on').addClass('fa fa-toggle-off').blur();
                    }
                }

                return false;
            }).fail(function(xhr, textStatus, errorThrown) {
                /*
                console.log('FAILURE: ' + textStatus);
                console.log(xhr);
                */

				/* Show an alert with the result */
				/* console.log(xhr.responseText); */
				if (typeof xhr.responseText !== 'undefined') {
					if (xhr.responseText.indexOf("{{ trans('admin::messages.unauthorized') }}") >= 0) {
						new PNotify({
							text: xhr.responseText,
							type: "error"
						});

						return false;
					}
				}

				/* Show an alert with the standard message */
				new PNotify({
					text: xhr.responseText,
					type: "error"
				});

                return false;
            });

            return false;
        }

        function saveReviewedAjaxRequest(siteUrl, el, value) {
            if (isDemo()) {
                return false;
            }

            // alert( JSON.stringify(el));
            // alert(value);

            // R.S
            // table
            if( typeof($(el).data('table')) === undefined ){
                // alert(el.table);
                var dataTable = $(el).data('table');
            }
            else{
                var dataTable = el.table;
            }

            // field
            if( typeof($(el).data('field')) === undefined ){
                var dataField = $(el).data('field');
            }
            else{
                // alert(el.table);
                var dataField = el.field;
            }
            // id
            if( typeof($(el).data('id')) === undefined ){
                var dataId = $(el).data('id');
            }
            else{
                // alert(el.id);
                var dataId = el.id;
            }

            // lineId
            if( typeof($(el).data('lineId')) === undefined ){
                var dataLineId = $(el).data('lineId');
            }
            else{
                // alert(el.lineId);
                var dataLineId = el.lineId;
            }

            var $self = $(this); /* magic here! */

            /* Get database info */
            var _token = $('input[name=_token]').val();
            // var dataTable = $(el).data('table');
            // var dataField = $(el).data('field');
            // var dataId = $(el).data('id');
            // var dataLineId = $(el).data('line-id');
            var dataValue = value;

            /* Remove dot (.) from var (referring to the PHP var) */

            // dataLineId = dataLineId.split('.').join("");


            $.ajax({
                method: 'POST',
                url: siteUrl + '/<?php echo admin_uri(); ?>/ajax/' + dataTable + '/' + dataField + '',
                // context: this,
                data: {
                    'value': dataValue,
                    'primaryKey': dataId,
                    '_token': _token
                }
            }).done(function(data) {

                console.log(data);
                /* Check 'status' */
                if (data.status != 1) {
                    return false;
                }

                /* All others cases */
                if (data.fieldValue == 0) {
                    $('#' + dataLineId).text('Rejected Rules 0');
                    $('#' + dataLineId).css('background-color', '#B00020');
                } else if (data.fieldValue == 1) {
                    $('#' + dataLineId).text('In process 1');
                    $('#' + dataLineId).css('background-color', '#FFAB00');
                } else if (data.fieldValue == 2) {
                    $('#' + dataLineId).text('Confirmed 2');
                    $('#' + dataLineId).css('background-color', '#2E7D32');
                }
                else if (data.fieldValue == 3) {
                    $('#' + dataLineId).text('Rejected Wrong Category 3');
                    $('#' + dataLineId).css('background-color', '#2E7D32');
                }


                return false;
            }).fail(function(xhr, textStatus, errorThrown) {
                /*
                console.log('FAILURE: ' + textStatus);
                console.log(xhr);
                */

                /* Show an alert with the result */
                /* console.log(xhr.responseText); */
                if (typeof xhr.responseText !== 'undefined') {
                    if (xhr.responseText.indexOf("{{ trans('admin::messages.unauthorized') }}") >= 0) {
                        new PNotify({
                            text: xhr.responseText,
                            type: "error"
                        });

                        return false;
                    }
                }

                /* Show an alert with the standard message */
                new PNotify({
                    text: xhr.responseText,
                    type: "error"
                });

                return false;
            });

            return false;
        }

        $(".modal-header.modal-header-dif .close").click( function(){
            console.log(modal_reason);

            if( modal_reason === true){
                console.log("close");
                 $(".reject-info-modal").attr("style", "display:none;");
                modal_reason = false;
            }
        });

		function isDemo()
		{
			<?php
				$varJs = isDemo() ? 'var demoMode = true;' : 'var demoMode = false;';
				echo $varJs . "\n";
			?>
			var msg = '{{ addcslashes(t('demo_mode_message'), "'") }}';

			if (demoMode) {
				new PNotify({title: 'Information', text: msg, type: "info"});
				return true;
			}

			return false;
		}
    </script>

    @include('admin::inc.alerts')
    @include('admin::inc.maintenance')

	<script>
		$(document).ready(function () {
			@if (isset($errors) and $errors->any())
				@if ($errors->any() and old('maintenanceForm')=='1')
					$('#maintenanceMode').modal();
				@endif
			@endif
		});
	</script>

    @yield('after_scripts')

</body>
</html>
