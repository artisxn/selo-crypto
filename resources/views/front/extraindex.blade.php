	@if($ps->flash_deal == 1)
		<!-- Electronics Area Start -->
		<section class="categori-item electronics-section">
			<div class="container" >
				<div class="row">
					<div class="col-lg-12 remove-padding">
						<div class="section-top" style="border-bottom:none!important;">
							<h2 class="section-title" style="color:#F79F65;">
								{{ $langg->lang244 }}
							</h2>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="card card-kuning">
							<div class="card-body">
								<div class="flash-deals">
									<div class="flas-deal-slider">

										@foreach($discount_products as $prod)
											@include('includes.product.flash-product')
										@endforeach
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Electronics Area start-->
	@endif


	@if($ps->featured_category == 1)

	{{-- Slider buttom Category Start --}}
	<section class="">
		<div class="container">

			<div class="card card-shadow">
				<div class="card-body">
				
					<div class="row">
						<div class="col-md-6">
							<h6 style="color:#212121;font-weiht:bold;">Kategori Pilihan <span style="color:#F47623!important;">Buat Kamu </span>
								<span style="color:#212121;float:right;font-size:13px;opacity: 0.5;"><a href="{{ route('allcategory') }}">Lihat Semua >></a></span>
							</h6>
							<br>

							<div class="row">
								@foreach($categories->where('is_featured','=',1)->shuffle()->take(3)	 as $cat)
									<div class="col-md-4">
										<a href="{{ route('front.category',$cat->slug) }}" class="single-category">
											<div class="card" style="border: 1px solid red;">
												<div class="card-body" style="text-align:center;padding:1px!important;">
													<img src="{{asset('assets/images/categories/'.$cat->image) }}" alt="" style="height:100px;width:100px;">
												</div>
											</div>
											<p style="text-align:center;">{{ $cat->name }}</p>
										</a>
									</div>
								@endforeach
							</div>
						</div>

						<div class="col-md-6">
						<h6 style="color:#212121;font-weiht:bold;">Product Pilihan <span style="color:#F47623!important;">Buat Kamu </span>
								<span style="color:#212121;float:right;font-size:13px;opacity: 0.5;"><a href="{{route('front.category')}}">Lihat Semua >></a></span>
							</h6>
							<br>
							<div class="row">
								@foreach($products->shuffle()->take(3)	 as $p)
									<div class="col-md-4">
										<a href="{{ route('front.product',$p->slug) }}" class="single-category">
											<div class="card" style="padding:0px!important;opacity:0.5;">
												<div class="card-body" style="text-align:center;padding:0px!important;">
													<img src="{{asset('assets/images/products/'.$p->photo) }}" alt="" style="height:100px;width:100%">
												</div>
											</div>
											<p style="text-align:center;">{{ strlen($p->name) > 35 ? substr($p->name,0,30).'...' : $p->name }}</p>
										</a>
									</div>
								@endforeach
							</div>
						</div>	
						
					</div>

				</div>
			</div>
		</div>
	</section>
	{{-- Slider buttom banner End --}}

	@endif



	@if($ps->best == 1)
		<!-- Phone and Accessories Area Start -->
		<section class="phone-and-accessories categori-item">
			<div class="container">
				<div class="row">
					<div class="col-lg-12 remove-padding">
						<div class="section-top">
							<h2 class="section-title">
								{{ $langg->lang27 }}
							</h2>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-9">
						<div class="row">
							@foreach($best_products as $prod)
								@include('includes.product.home-product')
							@endforeach
						</div>
					</div>
					<div class="col-lg-3 remove-padding d-none d-lg-block">
						<div class="aside">
							<a class="banner-effect mb-10" href="{{ $ps->best_seller_banner_link }}">
								<img src="{{asset('assets/images/'.$ps->best_seller_banner)}}" alt="">
							</a>
							<a class="banner-effect" href="{{ $ps->best_seller_banner_link1 }}">
								<img src="{{asset('assets/images/'.$ps->best_seller_banner1)}}" alt="">
							</a>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Phone and Accessories Area start-->
	@endif

	@if($ps->large_banner == 1)
		<!-- Banner Area One Start -->
		<section class="banner-section">
			<div class="container">
				@foreach($large_banners->chunk(1) as $chunk)
					<div class="row">
						@foreach($chunk as $img)
							<div class="col-lg-12 remove-padding">
								<div class="img">
									<a class="banner-effect" href="{{ $img->link }}">
										<img src="{{asset('assets/images/banners/'.$img->photo)}}" alt="">
									</a>
								</div>
							</div>
						@endforeach
					</div>
				@endforeach
			</div>
		</section>
		<!-- Banner Area One Start -->
	@endif

	@if($ps->top_rated == 1)
		<!-- Electronics Area Start -->
		<section class="categori-item electronics-section">
			<div class="container">
				<div class="row">
					<div class="col-lg-12 remove-padding">
						<div class="section-top">
							<h2 class="section-title">
								{{ $langg->lang28 }}
							</h2>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="row">

							@foreach($top_products as $prod)
								@include('includes.product.top-product')
							@endforeach

						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Electronics Area start-->
	@endif

	@if($ps->bottom_small == 1)
		<!-- Banner Area One Start -->
		<section class="banner-section">
			<div class="container">
				@foreach($bottom_small_banners->chunk(3) as $chunk)
					<div class="row">
						@foreach($chunk as $img)
							<div class="col-lg-4 remove-padding">
								<div class="left">
									<a class="banner-effect" href="{{ $img->link }}" target="_blank">
										<img src="{{asset('assets/images/banners/'.$img->photo)}}" alt="">
									</a>
								</div>
							</div>
						@endforeach
					</div>
				@endforeach
			</div>
		</section>
		<!-- Banner Area One Start -->
	@endif

	@if($ps->big == 1)
		<!-- Clothing and Apparel Area Start -->
		<section class="categori-item clothing-and-Apparel-Area">
			<div class="container">
				<div class="row">
					<div class="col-lg-12 remove-padding">
						<div class="section-top">
							<h2 class="section-title">
								{{ $langg->lang29 }}
							</h2>

						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-9">
						<div class="row">
							@foreach($big_products as $prod)
								@include('includes.product.home-product')
							@endforeach



						</div>
					</div>
					<div class="col-lg-3 remove-padding d-none d-lg-block">
						<div class="aside">
							<a class="banner-effect mb-10" href="{{ $ps->big_save_banner_link }}">
								<img src="{{asset('assets/images/'.$ps->big_save_banner)}}" alt="">
							</a>
							<a class="banner-effect" href="{{ $ps->big_save_banner_link1 }}">
								<img src="{{asset('assets/images/'.$ps->big_save_banner1)}}" alt="">
							</a>
						</div>
					</div>
				</div>
			</div>
			</div>
		</section>
		<!-- Clothing and Apparel Area start-->
	@endif

	@if($ps->hot_sale == 1)
		<!-- hot-and-new-item Area Start -->
		<section class="hot-and-new-item">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
					<div class="accessories-slider">
							<div class="slide-item">
								<div class="row">
									<div class="col-lg-4 col-sm-12">
										<div class="categori">
											<div class="section-top">
												<h2 class="section-title">
													@php 
														$banyakdicari = explode(" ",$langg->lang30);
														for($i=0; $i < count($banyakdicari)-1;$i++){
															echo $banyakdicari[$i]." ";
														}
														echo "<span style='color:#F47623;'>".end($banyakdicari)."</span>";
													@endphp
												</h2>
											</div>
											<div class="hot-and-new-item-slider">
												@foreach($hot_products->chunk(3) as $chunk)
													<div class="item-slide">
														<ul class="item-list">
															@foreach($chunk as $prod)
																@include('includes.product.list-product')
															@endforeach
														</ul>
													</div>
												@endforeach
											</div>

										</div>
									</div>
									<div class="col-lg-4 col-sm-12">
										<div class="categori">
											<div class="section-top">
												<h2 class="section-title">
												@php 
														$hargaspesial = explode(" ",$langg->lang31);
														for($i=0; $i < count($hargaspesial)-1;$i++){
															echo $hargaspesial[$i]." ";
														}
														echo "<span style='color:#F47623;'>".end($hargaspesial)."</span>";
													@endphp
												</h2>
											</div>

											<div class="hot-and-new-item-slider">

												@foreach($latest_products->chunk(3) as $chunk)
													<div class="item-slide">
														<ul class="item-list">
															@foreach($chunk as $prod)
																@include('includes.product.list-product')
															@endforeach
														</ul>
													</div>
												@endforeach

											</div>
										</div>
									</div>
									<div class="col-lg-4 col-sm-12">
										<div class="categori">
											<div class="section-top">
												<h2 class="section-title">
												@php 
														$rekomendasi = explode(" ",$langg->lang32);
														echo "<span style='color:#F47623;'>".current($rekomendasi)."</span> ";
														for($i=0; $i < count($rekomendasi);$i++){
															if($i != 0){
																echo $rekomendasi[$i]." ";
															}
														}
													@endphp
												</h2>
											</div>


											<div class="hot-and-new-item-slider">

												@foreach($trending_products->chunk(3) as $chunk)
													<div class="item-slide">
														<ul class="item-list">
															@foreach($chunk as $prod)
																@include('includes.product.list-product')
															@endforeach
														</ul>
													</div>
												@endforeach

											</div>

										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Clothing and Apparel Area start-->
	@endif

	@if($ps->review_blog == 1)
		<!-- Blog Area Start -->
		<section class="blog-area">
			<div class="container">
				<div class="row">
					<div class="col-lg-6">
						<div class="aside">
							<div class="slider-wrapper">
								<div class="aside-review-slider">
									@foreach($reviews as $review)
										<div class="slide-item">
											<div class="top-area">
												<div class="left">
													<img src="{{ $review->photo ? asset('assets/images/reviews/'.$review->photo) : asset('assets/images/noimage.png') }}" alt="">
												</div>
												<div class="right">
													<div class="content">
														<h4 class="name">{{ $review->title }}</h4>
														<p class="dagenation">{{ $review->subtitle }}</p>
													</div>
												</div>
											</div>
											<blockquote class="review-text">
												<p>
													{!! $review->details !!}
												</p>
											</blockquote>
										</div>
									@endforeach


								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						@foreach(DB::table('blogs')->orderby('views','desc')->take(2)->get() as $blogg)

							<div class="blog-box">
								<div class="blog-images">
									<div class="img">
										<img src="{{ $blogg->photo ? asset('assets/images/blogs/'.$blogg->photo):asset('assets/images/noimage.png') }}" class="img-fluid" alt="">
										<div class="date d-flex justify-content-center">
											<div class="box align-self-center">
												<p>{{date('d', strtotime($blogg->created_at))}}</p>
												<p>{{date('M', strtotime($blogg->created_at))}}</p>
											</div>
										</div>
									</div>

								</div>
								<div class="details">
									<a href='{{route('front.blogshow',$blogg->id)}}'>
										<h4 class="blog-title">
											{{strlen($blogg->title) > 40 ? substr($blogg->title,0,40)."...":$blogg->title}}
										</h4>
									</a>
									<p class="blog-text">
										{{substr(strip_tags($blogg->details),0,170)}}
									</p>
									<a class="read-more-btn" href="{{route('front.blogshow',$blogg->id)}}">{{ $langg->lang34 }}</a>
								</div>
							</div>

						@endforeach

					</div>
				</div>
			</div>
		</section>
		<!-- Blog Area start-->
	@endif


	@if($ps->partners == 1)
		<!-- Partners Area Start -->
		<section class="partners">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="section-top">
							<h2 class="section-title">
								{{ $langg->lang236 }}
							</h2>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="partner-slider">
							@foreach($partners as $data)
								<div class="item-slide">
									<a href="{{ $data->link }}" target="_blank">
										<img src="{{asset('assets/images/partner/'.$data->photo)}}" alt="">
									</a>
								</div>
							@endforeach
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Partners Area Start -->
	@endif

	{{-- @include('front.service') --}}
<!-- 
	@if($ps->service == 1)

	{{-- Info Area Start --}}
	<section class="info-area" style="box-shadow: 0px 3px 6px #00000029;">
			<div class="container">

					@foreach($services->chunk(4) as $chunk)
	
						<div class="row">
	
							<div class="col-lg-12 p-0">
								<div class="info-big-box">
									<div class="row">
										@foreach($chunk as $service)
											<div class="col-6 col-xl-3 p-0">
												<div class="info-box">
													<div class="icon">
														<img src="{{ asset('assets/images/services/'.$service->photo) }}">
													</div>
													<div class="info" >
														<div class="details" >
															<h4 class="title" style="color:#FEDD00!important;">{{ $service->title }}</h4>
															<p class="text" style="color:#FEDD00!important;">
																{!! $service->details !!}
															</p>
														</div>
													</div>
												</div>
											</div>
										@endforeach
									</div>
								</div>
							</div>
	
						</div>
	
					@endforeach
	
			</div>
		</section>
		{{-- Info Area End  --}}

		@endif		 -->

	<!-- main -->
	<script src="{{asset('assets/front/js/mainextra.js')}}"></script>