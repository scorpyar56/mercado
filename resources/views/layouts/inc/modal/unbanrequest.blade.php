<div class="modal fade modal-dif" id="unbanRequest" tabindex="-1" role="dialog">
	<div class="modal-dialog  modal-sm">
		<div class="modal-content modal-content-dif">

			<div class="modal-header modal-header-dif">
				{{--				<h4 class="modal-title"><i class="icon-login fa"></i> {{ t('Log In') }} </h4>--}}
				<h1 class="modal-title"> {{ t('This user has been banned.') }} </h1>

				<button type="button" class="close" data-dismiss="modal">
					{{--					<span aria-hidden="true"><i class="unir-close"></i></span>--}}
					<span aria-hidden="true"><i class="unir-close"></i></span>
					<span class="sr-only">{{ t('Close') }}</span>
				</button>
			</div>
{{--            {{ lurl('unban/' . $post . '/request') }}--}}
			<form id="unbanModal" role="form" method="POST" action="">
				{!! csrf_field() !!}
                <div class="modal-body modal-body-dif">

                    <!-- phone -->
                    <?php $phoneError = (isset($errors) and $errors->has('phone')) ? ' is-invalid' : ''; ?>
                    <div class="form-group">
                        <label for="login" class="control-label" style="font-size: medium">
                            {{ t('Phone') }}
                        </label>
                        <div class="input-group">
{{--                            {{ $post }}--}}

                            <input id="phone" hidden name="phone" type="text" maxlength="60" class="form-control{{ $phoneError }}" value="">
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
                </div>
                <div class="modal-footer modal-footer-dif">
                    <button type="submit" class="btn btn-success pull-right btn-dif">{{ t('Send request') }}</button>
                    <button type="button" class="btn btn-default btn-default-dif btn-modal" data-dismiss="modal">{{ t('Back') }}</button>
{{--                        <a href="{{ rawurldecode(URL::previous()) }}" class="btn btn-default btn-lg">{{ t('Back') }}</a>--}}
                </div>
			</form>

		</div>
	</div>
</div>

<script>
	function mShowPass() {
		var x = document.getElementById("mPassword");
		if (x.type === "password") {
			x.type = "text";
		} else {
			x.type = "password";
		}
	}
</script>