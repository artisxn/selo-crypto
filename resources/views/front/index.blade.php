@extends('layouts.front')

@section('content')

	@if($ps->slider == 1)

		@if(count($sliders))

			@include('includes.slider-style')
		@endif
	@endif

	@if($ps->slider == 1)
		<!-- Hero Area Start -->
		<section class="hero-area" style="margin-top:20px;">
			@if($ps->slider == 1)

				@if(count($sliders))
					<div class="container">
						<div class="row">
							<div class="col-md-8">
								<!-- slider -->
								<div class="hero-area-slider">
									<div class="top-slider-fan">
										@foreach($sliders as $data)
										<a href="">
											<img src="{{asset('assets/images/sliders/'.$data->photo)}}" alt="" class="img-responsive slider-bulet">
										</a>
										@endforeach	
									</div>
								</div>
								<!-- akhir slider -->
							</div>
							<div class="col-md-4">
								<div class="top-banner-fan">
									@foreach($top_small_banners as $img)
										<div class="col-md-12 remove-padding">
												<a class="" href="{{ $img->link }}" target="_blank">
													<img src="{{asset('assets/images/banners/'.$img->photo)}}" alt="" class="img-responsive slider-bulet">
												</a>
										</div>
									@endforeach								
								</div>
							</div>
						</div>
					</div>
				@endif

			@endif

		</section>
		<!-- Hero Area End -->
	@endif

	@if($ps->featured == 1)
		<!-- Trending Item Area Start -->
		<section  class="trending">
			<div class="container">
				<div class="row">
					<div class="col-lg-12 remove-padding">
						<div class="section-top">
							<h2 class="section-title">
								{{ $langg->lang26 }}
							</h2>
							{{-- <a href="#" class="link">View All</a> --}}
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12 remove-padding">
						<div class="trending-item-slider">
							@foreach($feature_products as $prod)
								@include('includes.product.slider-product')
							@endforeach
						</div>
					</div>

				</div>
			</div>
		</section>
		<!-- Tranding Item Area End -->
	@endif


	<section id="extraData">
		<div class="text-center">
			<img src="{{asset('assets/images/'.$gs->loader)}}">
		</div>
	</section>


@endsection

@section('scripts')
	<script>
        $(window).on('load',function() {

            setTimeout(function(){

                $('#extraData').load('{{route('front.extraIndex')}}');

            }, 500);
        });

	</script>

	<!-- <script src="{{asset('assets/slick/slick.js')}}"></script> -->

	<script type="text/javascript">
	$(document).ready(function(){
        $('.top-banner-fan').slick({
            // centerMode: true,
            // centerPadding: '60px',
            dots: false,
            infinite: true,
			autoplay: true,
			autoplaySpeed: 2000,
            // speed: 300,
            slidesToShow: 1,
            slidesToScroll: 1,
			arrows: true
        });
      });
   </script>

<script type="text/javascript">
     $(document).ready(function(){
       $('.top-slider-fan').slick({
         slidesToShow: 1,
         slidesToScroll: 1,
         autoplay: true,
         autoplaySpeed: 2000,
		
         arrows: false,
         dots: true,
		//  infinite: true,
	     cssEase: 'linear',
		 
           pauseOnHover: true,
           responsive: [{
           breakpoint: 768,
           settings: {
             slidesToShow: 1
           }
         }, {
           breakpoint: 520,
           settings: {
             slidesToShow: 1
           }
         }]
       });
     });
   </script>
@endsection
