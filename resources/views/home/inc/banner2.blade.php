
@include('home.inc.spacer')

	<!-- banner -->

	@if(isset($bigBanners2))
		<div class="container"  id="desktopBanner2">
			<div class="banner-home">
				<div class="owl-carousel owl-theme owl-loaded owl-drag">
					<div class="owl-stage-outer">
						<div class="owl-stage">
							@foreach($bigBanners2 as $banner)
								<div class="owl-item">
									<div class="item">
										<a  href="{{  url('posts/create') }}">
											<?php $url = url('storage') . "/" . $banner ;?>
											<img src="{{  $url }}">
										</a>
									</div>
								</div>
							@endforeach
						</div>
					</div>
				</div>
			</div>
		</div>
	@endif
	
	<!-- mobile -->
	@if(isset($smallBanners2))
		<div class="container" id="mobileBanner2">
			<div class="banner-home">
				<div class="owl-carousel owl-theme owl-loaded owl-drag">
					<div class="owl-stage-outer">
						<div class="owl-stage">
							@foreach($smallBanners2 as $banner)
								<div class="owl-item">
									<div class="item">
										<a  href="{{  url('posts/create') }}">
										<?php $urlSmall = url('storage') . "/" . $banner ;?>
											<img src="{{  $urlSmall }}">
										</a>
									</div>
								</div>
							@endforeach
						</div>
					</div>
				</div>
			</div>
		</div>
	@endif

@section('after_scripts')
	@parent
		<script>
			var owl = $('.owl-carousel');
			owl.owlCarousel({
				items:1,
				loop:true,
				margin:0,
				dots: false,
				autoplay:true,
				autoplayTimeout:4000,
				autoplayHoverPause:true,
			});

			if($(document).width() >= 575){
				$("#mobileBanner2").attr("style", "display: none;");
			}
			else{
				$("#desktopBanner2").attr("style", "display: none;");
				// $("#mobileBanner2").attr("style", "display: none;");
				$( "#mobileBanner2" ).insertBefore( $( "#categories-home" ) );
			}
		</script>
@endsection
