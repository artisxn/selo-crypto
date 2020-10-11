@if (config('dropship.is_dropship') !== TRUE)
<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	@if(isset($page->meta_tag) && isset($page->meta_description))
	<meta name="keywords" content="{{ $page->meta_tag }}">
	<meta name="description" content="{{ $page->meta_description }}">
	<title>{{$gs->title}}</title>
	@elseif(isset($blog->meta_tag) && isset($blog->meta_description))
	<meta name="keywords" content="{{ $blog->meta_tag }}">
	<meta name="description" content="{{ $blog->meta_description }}">
	{{$gs->title}}</title>
	@elseif(isset($productt))
	<meta name="keywords" content="{{ !empty($productt->meta_tag) ? implode(',', $productt->meta_tag ): '' }}">
	<meta name="description"
		content="{{ $productt->meta_description != null ? $productt->meta_description : strip_tags($productt->description) }}">
	<meta property="og:title" content="{{$productt->name}}" />
	<meta property="og:description"
		content="{{ $productt->meta_description != null ? $productt->meta_description : strip_tags($productt->description) }}" />
	<meta property="og:image" content="{{asset('assets/images/'.$productt->photo)}}" />
	<meta name="author" content="{{$gs->title}}">
	<title>{{$gs->title}}</title>
	@else
	<meta name="keywords" content="{{ $seo->meta_keys }}">
	<meta name="author" content="{{$gs->title}}">
	<title>{{$gs->title}}</title>
	@endif
	<!-- favicon -->
	<link rel="icon" type="image/x-icon" href="{{asset('assets/images/'.$gs->favicon)}}" />
	<!-- bootstrap -->
	<link rel="stylesheet" href="{{asset('assets/front/css/bootstrap.min.css')}}">
	<!-- Plugin css -->
	<link rel="stylesheet" href="{{asset('assets/front/css/plugin.css')}}">
	<link rel="stylesheet" href="{{asset('assets/front/css/animate.css')}}">
	<link rel="stylesheet" href="{{asset('assets/front/css/toastr.css')}}">
	<link rel="stylesheet" href="{{asset('assets/front/css/toastr.css')}}">

	<!-- jQuery Ui Css-->
	<link rel="stylesheet" href="{{asset('assets/front/jquery-ui/jquery-ui.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/front/jquery-ui/jquery-ui.structure.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/slick/slick.css')}}">
	<link rel="stylesheet" href="{{asset('assets/slick/slick-theme.css')}}">

	@if($langg->rtl == "1")

	<!-- stylesheet -->
	<link rel="stylesheet" href="{{asset('assets/front/css/rtl/style.css')}}">
	<link rel="stylesheet" href="{{asset('assets/front/css/rtl/custom.css')}}">
	<link rel="stylesheet" href="{{asset('assets/front/css/rtl/custom1.css')}}">
	<link rel="stylesheet" href="{{asset('assets/front/css/common.css')}}">
	<!-- responsive -->
	<link rel="stylesheet" href="{{asset('assets/front/css/rtl/responsive.css')}}">
	<link rel="stylesheet" href="{{asset('assets/front/css/common-responsive.css')}}">

	<!--Updated CSS-->
	<link rel="stylesheet"
		href="{{ asset('assets/front/css/rtl/styles.php?color='.str_replace('#','',$gs->colors).'&amp;'.'header_color='.str_replace('#','',$gs->header_color).'&amp;'.'footer_color='.str_replace('#','',$gs->footer_color).'&amp;'.'copyright_color='.str_replace('#','',$gs->copyright_color).'&amp;'.'menu_color='.str_replace('#','',$gs->menu_color).'&amp;'.'menu_hover_color='.str_replace('#','',$gs->menu_hover_color)) }}">
		
	@else

	<!-- stylesheet -->
	<link rel="stylesheet" href="{{asset('assets/front/css/style.css')}}">
	<link rel="stylesheet" href="{{asset('assets/front/css/custom.css')}}">
	<link rel="stylesheet" href="{{asset('assets/front/css/common.css')}}">
	<!-- responsive -->
	<link rel="stylesheet" href="{{asset('assets/front/css/responsive.css')}}">
	<link rel="stylesheet" href="{{asset('assets/front/css/common-responsive.css')}}">

	<!--Updated CSS-->
	<link rel="stylesheet"
		href="{{ asset('assets/front/css/styles.php?color='.str_replace('#','',$gs->colors).'&amp;'.'header_color='.str_replace('#','',$gs->header_color).'&amp;'.'footer_color='.str_replace('#','',$gs->footer_color).'&amp;'.'copyright_color='.str_replace('#','',$gs->copyright_color).'&amp;'.'menu_color='.str_replace('#','',$gs->menu_color).'&amp;'.'menu_hover_color='.str_replace('#','',$gs->menu_hover_color)) }}">

	@endif
	<link rel="stylesheet" href="{{asset('assets/front/css/kostum.css')}}">
	
	<style>
			@import url(https://fonts.googleapis.com/css?family=Montserrat:700);
		  
		  .ribbon {
			font-family: "Montserrat", sans-serif;
			font-weight: 700;
			font-size: 12px;
			text-transform: uppercase;
			letter-spacing: 1px;
			position: absolute;
			left: -20px;
			top: 42px;
			color: #34495e;
			background-color: rgba(255,255,255,0.5);
			border-top: 2px solid #000;
			border-bottom: 2px solid #000;
			padding: 10px 5px;
			-moz-transform: rotate(-45deg);
			-ms-transform: rotate(-45deg);
			-webkit-transform: rotate(-45deg);
			transform: rotate(-45deg);
			z-index: 3;
		  }


		  .terakhir{
			  display:inline;
			  float:left;
			  font-size:12px;
			  margin-right:15px;
			  color:#212121;
		  }


		
		  </style>



	@yield('styles')

</head>

<body>
@include('includes.modal-logout')

@php
$last_view=[];
@endphp

@if(Auth::user() !== NULL)
@php
$last_view = DB::table('product_last_views')
            ->leftJoin('products', 'product_last_views.product_id', '=', 'products.id')
            ->where('product_last_views.user_id', Auth::user()->id)
            ->orderBy('datetime', 'DESC')
            ->limit(3)
            ->get();
@endphp
@endif


	@if($gs->is_loader == 1)
	<div class="preloader" id="preloader"
		style="background: url({{asset('assets/images/'.$gs->loader)}}) no-repeat scroll center center #FFF;"></div>
	@endif

	@if($gs->is_popup== 1)

	@if(isset($visited))
	<div style="display:none">
		<img src="{{asset('assets/images/'.$gs->popup_background)}}">
	</div>

	<!--  Starting of subscribe-pre-loader Area   -->
	<div class="subscribe-preloader-wrap" id="subscriptionForm" style="display: none;">
		<div class="subscribePreloader__thumb"
			style="background-image: url({{asset('assets/images/'.$gs->popup_background)}});">
			<span class="preload-close"><i class="fas fa-times"></i></span>
			<div class="subscribePreloader__text text-center">
				<h1>{{$gs->popup_title}}</h1>
				<p>{{$gs->popup_text}}</p>
				<form action="{{route('front.subscribe')}}" id="subscribeform" method="POST">
					{{csrf_field()}}
					<div class="form-group">
						<input type="email" name="email" placeholder="{{ $langg->lang741 }}" required="">
						<button id="sub-btn" type="submit">{{ $langg->lang742 }}</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!--  Ending of subscribe-pre-loader Area   -->

	@endif

	@endif


	<section class="top-header">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 remove-padding">
					<div class="content">
						<div class="left-content">
							<div class="list">
								<ul>
									<!-- @if($gs->is_language == 1)
									<li>
										<div class="language-selector">
											<i class="fas fa-globe-americas"></i>
											<select name="language" class="language selectors nice">
												@foreach(DB::table('languages')->get() as $language)
												<option value="{{route('front.language',$language->id)}}"
													{{ Session::has('language') ? ( Session::get('language') == $language->id ? 'selected' : '' ) : (DB::table('languages')->where('is_default','=',1)->first()->id == $language->id ? 'selected' : '') }}>
													{{$language->language}}</option>
												@endforeach
											</select>
										</div>
									</li>
									@endif -->

									<!-- @if($gs->is_currency == 1)
									<li>
										<div class="currency-selector">
											<span>{{ Session::has('currency') ?   DB::table('currencies')->where('id','=',Session::get('currency'))->first()->sign   : DB::table('currencies')->where('is_default','=',1)->first()->sign }}</span>
											<select name="currency" class="currency selectors nice">
												@foreach(DB::table('currencies')->get() as $currency)
												<option value="{{route('front.currency',$currency->id)}}"
													{{ Session::has('currency') ? ( Session::get('currency') == $currency->id ? 'selected' : '' ) : (DB::table('currencies')->where('is_default','=',1)->first()->id == $currency->id ? 'selected' : '') }}>
													{{$currency->name}}</option>
												@endforeach
											</select>
										</div>
									</li>
									@endif -->
									@if($gs->is_faq == 1)
										<li class="left-top"><a href="{{ route('front.faq') }}" style="border-right:0px !important;">FAQ</a></li>
									@endif

									@foreach(DB::table('pages')->where('header','=',1)->get() as $data)
									<li class="left-top"><a href="{{ route('front.page',$data->slug) }}" style="border-right:0px !important;">@if ($data->title == "About Us") Tentang Kami @else {{$data->title}} @endif</a></li>
									@endforeach
									@if($gs->is_contact == 1)
									<li class="left-top"><a href="{{ route('front.contact') }}" style="border-right:0px !important;">{{ $langg->lang20 }}</a></li>
									@endif


								</ul>
							</div>
						</div>
						<div class="right-content">
							<div class="list">
								<ul>


									@if($gs->reg_vendor == 1)
									<li class="right-top">
										@if(Auth::check())
										@if(@Auth::guard('web')->user()->is_vendor == 2)
										<a href="{{ route('vendor-dashboard') }}"
											class="">{{ $langg->lang220 }}</a>
										@else
										<a href="{{ route('user-package') }}" class="">{{ $langg->lang220 }}</a>
										@endif
									</li>
									@else
									<li class="right-top">
										<a href="javascript:;" data-toggle="modal" data-target="#vendor-login"
											class="">{{ $langg->lang220 }}</a>
									</li>
									@endif
									@endif



								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Top Header Area End -->

	<!-- Logo Header Area Start -->
	<!-- <div style="position:fixed;zindex:1900">sodium_crypto_aead_aes256gcm_decrypt</div> -->
	<section class="logo-header">
		<div class="container" >
 			<div class="row ">
				<div class="col-lg-2 col-sm-4 col-5 remove-padding">
					<div class="logo">
						<a href="{{ route('front.index') }}">
							{{-- <img src="{{asset('assets/images/majapahit.png')}}" alt=""> --}}
							<img src="{{asset('assets/images/'.$gs->logo)}}" alt="" class="img-logo">
						</a>
					</div>
				</div>
				<div class="col-lg-6 col-sm-12 remove-padding order-last order-sm-2 order-md-2">
					<div class="search-box-wrapper">
						<div class="search-box">
							<div class="categori-container" id="catSelectForm">
							<select name="category" id="category_select" class="categoris" onchange="if (this.selectedIndex) window.location.assign('{!! url('/') !!}/category/'+this.value);">
									<option value="">{{ $langg->lang1 }}</option>
									@foreach($categories as $data)
									<option value="{{ $data->slug }}"
										{{ Request::route('category') == $data->slug ? 'selected' : '' }}>
										{{ $data->name }}</option>
									@endforeach
								</select>
							</div>

							<form id="searchForm" class="search-form"
								action="{{ route('front.category', [Request::route('category'),Request::route('subcategory'),Request::route('childcategory')]) }}"
								method="GET">
								@if (!empty(request()->input('sort')))
								<input type="hidden" name="sort" value="{{ request()->input('sort') }}" autofocus>
								@endif
								@if (!empty(request()->input('minprice')))
								<input type="hidden" name="minprice" value="{{ request()->input('minprice') }}" autofocus>
								@endif
								@if (!empty(request()->input('maxprice')))
								<input type="hidden" name="maxprice" value="{{ request()->input('maxprice') }}" autofocus>
								@endif
								<input type="text" id="prod_name" name="search" placeholder="{{ $langg->lang2 }}"
									value="{{ request()->input('search') }}" autocomplete="off" autofocus>
								<div class="autocomplete">
									<div id="myInputautocomplete-list" class="autocomplete-items">
									</div>
								</div>
								<button type="submit"><i class="icofont-search-1"></i></button>
							</form>
							<!-- terakhir dilihat -->
		  					@if($last_view !== [])
							  <div class="terakhir">Terakhir Dilihat >> </div>
							@endif
							@foreach($last_view as $l)
							<div class="terakhir">
							<a href="{{ route('front.product',$l->slug) }}" title="{{$l->name}}"> {{ strlen($l->name) > 20 ? substr($l->name,0,20).'...' : $l->name }}</a>
							</div>
							@endforeach
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-sm-8 col-7 order-lg-last" style="">
		  
					<div class="helpful-links">
						<ul class="helpful-links-inner" style="margin-top:-10px">
							
							
							
							<li class="wishlist" data-toggle="tooltip" data-placement="top" title="{{ $langg->lang9 }}">
								@if(Auth::guard('web')->check())
								<a href="{{ route('user-wishlists') }}" class="wish">
									<i class="far fa-heart"></i>
									<span id="wishlist-count">{{ count(Auth::user()->wishlists) }}</span>
								</a>
								@else
								<a href="javascript:;" data-toggle="modal" id="wish-btn" data-target="#comment-log-reg"
									class="wish">
									<i class="far fa-heart"></i>
									<span id="wishlist-count">0</span>
								</a>
								@endif
							</li>

							<li class="compare" data-toggle="tooltip" data-placement="top" title="{{ $langg->lang10 }}">
								<a href="{{ route('product.compare') }}" class="wish compare-product">
									<div class="icon">
										<i class="fas fa-exchange-alt"></i>
										<span
											id="compare-count">{{ Session::has('compare') ? count(Session::get('compare')->items) : '0' }}</span>
									</div>
								</a>
							</li>

							<li class="my-dropdown" data-toggle="tooltip" data-placement="top"
								title="{{ $langg->lang3 }}">
								<a href="javascript:;" class="cart carticon">
									<div class="icon" style="">
									{{-- <i class="fas fa-fancart" style="padding-bottom:-400px;"></i> --}}
									<i class="fas fa-shopping-bag" style="padding-bottom:-400px;"></i>
									
										<!-- <i class="icofont-cart"></i> -->
										<!-- <img src="{{asset('assets/icon/cart.png')}}" alt="" class="img-fluid" width="20%"> -->
										<span class="cart-quantity" id="cart-count">
										  {{ Session::has('cart') ? count(Session::get('cart')->items) : '0' }}
										</span>	
									</div>

								</a>
								<div class="my-dropdown-menu" id="cart-items">
									  <div class="">
									  @include('load.cart')
									  </div>
								</div>
							</li>



							

							<!-- tambahan -->							
							<li class="batas kosong"></li>
							@if(!Auth::guard('web')->check())
							<li class="kanan-tambahan"><a href="{{ route('user.login') }}">{{ $langg->lang12 }}</a></li>
							<li class="kanan-tambahan" style=><a href="{{ route('user.login') }}">{{ $langg->lang13 }}</a></li>
							@else

							<li class="nav-item dropdown" style="">
								<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									@if(Auth::user()->photo == NULL OR Auth::user()->photo == "")
									<i class="far fa-user"></i> 
									@else
									<img src="{{asset('assets/images/users/'.Auth::user()->photo)}}" alt="" class="rounded-circle" style="display:inline!important;width:30px;height:30px;">
									@endif

									{{ strlen(Auth::user()->name) > 6 ? ucwords(substr(Auth::user()->name,0,6)).'...' : ucwords(Auth::user()->name) }}
								
								</a>
								<div class="dropdown-menu" aria-labelledby="navbarDropdown" style="border-radius:10px;text-align:center;font-size:15px;">
									<a class="dropdown-item" href="{{ route('user-dashboard') }}"> {{ $langg->lang221 }}</a>
									@if(Auth::user()->IsVendor())
															
									<a class="dropdown-item" href="{{ route('vendor-dashboard') }}">{{ $langg->lang222 }}</a>
									@endif
									<a class="dropdown-item"  href="{{ route('user-profile') }}">
														{{ $langg->lang205 }}</a>
														<a href="javascript:;" data-toggle="modal" data-target="#confirm-logout" data-href="{{ route('user-logout') }}" class="">{{ $langg->lang207 }}</a>

								</div>
							</li>
					
							@endif


									
							

						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Logo Header Area End -->

	<!--Main-Menu Area Start-->
	<!-- dihapus -->
	<!--Main-Menu Area End-->

	@yield('content')




<section class="info-area" style="box-shadow: 0px 3px 6px #00000029;">
			<div class="container">

					@foreach(DB::table('services')->where('user_id','=',0)->get()->chunk(4) as $chunk)
	
						<div class="row">
	
							<div class="col-lg-12 p-0">
								<div class="info-big-box">
									<div class="row justify-content-center">
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

	<!-- Footer Area Start -->
	<footer class="footer" id="footer">
		<div class="container">
			<div class="row">
				<div class="col-md-4 col-lg-4">
					<div class="footer-info-area">
						<div class="footer-logo">
							<a href="{{ route('front.index') }}" class="logo-link">
								<img src="{{asset('assets/images/'.$gs->footer_logo)}}" alt="">
							</a>
						</div>
						<div class="text" style="color:#000;">
							<p>
								{!! $gs->footer !!}
							</p>
							<p>{!! $gs->copyright !!}</p> 
						</div>
					</div>
					
				</div>
				<div class="col-md-4 col-lg-8">
				  <div class="row">
					<div class="col-md-4 col-lg-4">
						<div class="footer-widget info-link-widget">
							
							<ul class="link-list">
							<li style="color:#000;font-weight:600;">Jelajahi Kami</li>
							

								@foreach(DB::table('pages')->where('footer','=',1)->where('position','=',1)->orderBy('sort', 'ASC')->get() as $data)
								<li>
									<a href="{{ route('front.page',$data->slug) }}">
										{{ $data->title }}
									</a>
								</li>
								@endforeach

								<!-- <li>
									<a href="{{ route('front.contact') }}">
										{{ $langg->lang23 }}
									</a>
								</li> -->

								<li>
									<a href="{{ route('front.blog') }}">
										{{ $langg->lang18 }}
									</a>
								</li>
								<li>
									<a href="{{ route('front.faq') }}">
										FAQ
									</a>
								</li>
								

							</ul>
						</div>
						
					</div>
					<div class="col-md-4 col-lg-4">
						<div class="footer-widget info-link-widget">
							
							<ul class="link-list">
							<li style="color:#000;font-weight:600;">Layanan Pelanggan</li>

							    <li>
									<a href="{{ route('front.contact') }}">
										{{ $langg->lang23 }}
									</a>
								</li>

							

								@foreach(DB::table('pages')->where('footer','=',1)->where('position','=',2)->orderBy('sort', 'ASC')->get() as $data)
								<li>
									<a href="{{ route('front.page',$data->slug) }}">
										</i>{{ $data->title }}
									</a>
								</li>
								@endforeach

							
							</ul>
						</div>
					</div>
					<div class="col-md-4 col-lg-4">
						<div class="footer-widget info-link-widget">
						
						<ul class="link-list" style="display:inline;">
						<li style="color:#000;font-weight:600;">Ikuti Kami</li>
						@if(App\Models\Socialsetting::find(1)->f_status == 1)
						<li style="display:inline;margin-right:10px;">
							<a href="{{ App\Models\Socialsetting::find(1)->facebook }}"
								target="_blank">
								<img src="{{asset('assets/images/sosial/fb.png')}}" alt="">
							</a>
						</li>
						@endif

						@if(App\Models\Socialsetting::find(1)->l_status == 1)
						<li style="display:inline;margin-right:10px;">
							<a href="{{ App\Models\Socialsetting::find(1)->linkedin }}" 
								target="_blank">
								<img src="{{asset('assets/images/sosial/ig.png')}}" alt="">
							</a>
						</li>
						@endif
				
				
						@if(App\Models\Socialsetting::find(1)->t_status == 1)
						<li style="display:inline;margin-right:10px;">
							<a href="{{ App\Models\Socialsetting::find(1)->twitter }}" 
								target="_blank">
								<img src="{{asset('assets/images/sosial/twiter.png')}}" alt="">
							</a>
						</li>
						@endif

						</ul>
					</div>
					</div>
				  </div>
				</div>
			</div>

		</div>

		<div class="copy-bg" style="background-color:#fff;">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="content">
							<div class="content">
								<!-- <p>{!! $gs->copyright !!}</p> -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</footer>
	<!-- Footer Area End -->

	<!-- Back to Top Start -->
	<div class="bottomtotop">
		<i class="fas fa-chevron-right bulet"></i>
	</div>
	<!-- Back to Top End -->

	<!-- LOGIN MODAL -->
	<div class="modal fade" id="comment-log-reg" tabindex="-1" role="dialog" aria-labelledby="comment-log-reg-Title"
		aria-hidden="true">

		<div class="modal-dialog  modal-dialog-centered" role="document">
			<div class="row">
						
						<div class="col-lg-12">
						<div align="center" style="text-align: center;background: #FCB415; border-radius: 5px 5px 0px 0px;margin-left:30px; width: 450px;font: SemiBold 22px/25px Kanit;letter-spacing: 0;color: #FFFFFF;" class="d-none pendaftaran">
							Halo, Selamat Bergabung!
						</div>
						</div>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" aria-label="Close">
							<a href="{{ url()->previous() }}"><span aria-hidden="true">&times;</span></a>
					</button>
				</div>

				<div class="modal-body">
					
					</div>
					<nav class="comment-log-reg-tabmenu">
						<div class="nav nav-tabs" id="nav-tab" role="tablist">
							<a class="nav-item nav-link login active" id="nav-log-tab1" data-toggle="tab"
								href="#nav-log1" role="tab" aria-controls="nav-log" aria-selected="true">
								{{ $langg->lang197 }}
							</a>
							

							<a class="nav-item nav-link" id="nav-reg-tab1" data-toggle="tab" href="#nav-reg1" role="tab"
								aria-controls="nav-reg" aria-selected="false">
								{{ $langg->lang198 }}
							</a>

							<hr style="border: 1px solid #A5A5A5;
						margin-bottom: 10px;padding-left:450px;">
						</div>

					</nav>

					<div class="tab-content" id="nav-tabContent">
						



						<div class="tab-pane fade show active" id="nav-log1" role="tabpanel"
							aria-labelledby="nav-log-tab1">

							<div class="login-area" style=" -webkit-box-shadow: none !important;  -moz-box-shadow: none !important; box-shadow: none !important;">
							 	
								<div class="login-form signin-form">

									@include('includes.admin.form-login')
									<form class="mloginform" action="{{ route('user.login.submit') }}" method="POST">
										{{ csrf_field() }}
										<div class="form-input">
											<input type="email" style="text-align:center;border-radius:10px;" name="email" placeholder="E-mail"
												required="">

										</div>
										<div class="form-input">
											<input type="password" class="Password" name="password"
												style="text-align:center;border-radius:10px;" placeholder="Kata Sandi" required="">

										</div>
										<div class="form-forgot-pass">
											<div class="left">
												<input type="checkbox" name="remember" id="mrp"
													{{ old('remember') ? 'checked' : '' }}>
												<label for="mrp">{{ $langg->lang175 }}</label>
											</div>
											<div class="right">
												<a href="javascript:;" id="show-forgot">
													{{ $langg->lang176 }}
												</a>
											</div>
										</div>
										<input type="hidden" name="modal" value="1">
										<input class="mauthdata" type="hidden" value="{{ $langg->lang177 }}">
										<div class="row">
											<div class="col-lg-6">
										<button data-dismiss="modal" class="myButton90">Nanti Saja</button>
										</div>
											<div class="col-lg-6">
										<button type="submit" class="myButton95">{{ $langg->lang178 }}</button>
									</div>
										</div>
										@if(App\Models\Socialsetting::find(1)->f_check == 1 ||
										App\Models\Socialsetting::find(1)->g_check == 1)
										<div class="social-area">

											<p class="text"><font color="#A5A5A5">atau masuk dengan</font></p>
											<ul class="social-links">
												

												@if(App\Models\Socialsetting::find(1)->f_check == 1)
												<li>
													<a href="{{ route('social-provider','google') }}">
														<span style="text-align: center;
															font: Regular 15px/25px Kanit;
															letter-spacing: 0;
															color: #34A853;
															opacity: 1;
															top: 569px;
															left: 646px;
															width: 64px;
															height: 23px;">google</span>
													</a>
												</li>
												@endif
												@if(App\Models\Socialsetting::find(1)->g_check == 1)
												<li>&nbsp;</li>
												<li>&nbsp;</li>

												<li>
													<a href="{{ route('social-provider','facebook') }}">
														<span style="text-align: center;
															font: Regular 15px/25px Kanit;
															letter-spacing: 0;
															color: #4267B2;
															opacity: 1;
															top: 569px;
												left: 646px;
												width: 64px;
												height: 23px;">Facebook</span>
													</a>
												</li>
												@endif
											</ul>
										</div>
										@endif
									</form>

								</div>
							</div>
						</div>

						<div class="tab-pane fade" id="nav-reg1" role="tabpanel" aria-labelledby="nav-reg-tab1">
							
							<div class="login-area signup-area">
								<!--<div class="header-area">
								
								</div>-->
								<div class="login-form signup-form">
									@include('includes.admin.form-login')
									<form class="mregisterform" action="{{route('user-register-submit')}}"
										method="POST">
										{{ csrf_field() }}

										<div class="form-input">
											<input type="text" class="User Name" name="name"
												placeholder="{{ $langg->lang182 }}" style="text-align:center;border-radius:10px;" required="">
											
										</div>

										<div class="form-input">
											<input type="email" class="User Name" name="email"
												placeholder="{{ $langg->lang183 }}" style="text-align:center;border-radius:10px;" required="">
											<small style="color:red;">(Jika ingin menjadi vendor, email harus sama dengan email EDCCASH)</small>
										</div>

										<div class="form-input">
											<input type="text" class="User Name" name="phone"
												placeholder="{{ $langg->lang184 }}" style="text-align:center;border-radius:10px;" required="">
											</div>

										<div class="form-input">
											<input type="text" class="User Name" name="address"
												placeholder="{{ $langg->lang185 }}" style="text-align:center;border-radius:10px;" required="">
											
										</div>

										<div class="form-input">
											<input type="password" class="Password" name="password"
												placeholder="{{ $langg->lang186 }}" style="text-align:center;border-radius:10px;" required="">
											
										</div>

										<div class="form-input">
											<input type="password" class="Password" name="password_confirmation"
												placeholder="{{ $langg->lang187 }}" style="text-align:center;border-radius:10px;" required="">
											
										</div>

										<div class="row">
											<div class="col-lg-6">
										@if($gs->is_capcha == 1)

										<ul class="captcha-area">
											<li>
												<p><img class="codeimg1" style="width: 150px; height: 31px;"
														src="{{asset("assets/images/capcha_code.png")}}" alt=""> <i
														class="fas fa-sync-alt pointer refresh_code "></i></p>
											</li>
										</ul>
									</div>

											<div class="col-lg-6">
										<div class="form-input">
											<input type="text" class="Password" name="codes"
												placeholder="{{ $langg->lang51 }}" style="text-align:center;border-radius:10px;" required="">
											
										</div>


										@endif

										<input class="mprocessdata" type="hidden" value="{{ $langg->lang188 }}">
									</div>
								</div>
										<div class="row">
											<div class="col-lg-6">
										<button data-dismiss="modal" class="myButton90">Nanti Saja</button>
										</div>
											<div class="col-lg-6">
										<button type="submit" class="myButton95">{{ $langg->lang178 }}</button>
									</div>
										</div>

									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- LOGIN MODAL ENDS -->

	<!-- FORGOT MODAL -->
	<div class="modal fade" id="forgot-modal" tabindex="-1" role="dialog" aria-labelledby="comment-log-reg-Title"
		aria-hidden="true">
		<div class="modal-dialog  modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">

					<div class="login-area">
						<div class="header-area forgot-passwor-area">
							<h4 class="title">{{ $langg->lang191 }} </h4>
							<p class="text">{{ $langg->lang192 }} </p>
						</div>
						<div class="login-form">
							@include('includes.admin.form-login')
							<form id="mforgotform" action="{{route('user-forgot-submit')}}" method="POST">
								{{ csrf_field() }}
								<div class="form-input">
									<input type="email" name="email" class="User Name"
										placeholder="{{ $langg->lang193 }}" required="">
									<i class="icofont-user-alt-5"></i>
								</div>
								<div class="to-login-page">
									<a href="javascript:;" id="show-login">
										{{ $langg->lang194 }}
									</a>
								</div>
								<input class="fauthdata" type="hidden" value="{{ $langg->lang195 }}">
								<button type="submit" class="submit-btn">{{ $langg->lang196 }}</button>
							</form>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
	<!-- FORGOT MODAL ENDS -->


	<!-- VENDOR LOGIN MODAL -->
	<div class="modal fade" id="vendor-login" tabindex="-1" role="dialog" aria-labelledby="vendor-login-Title"
		aria-hidden="true">
		<div class="modal-dialog  modal-dialog-centered" style="transition: .5s;" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<nav class="comment-log-reg-tabmenu">
						<div class="nav nav-tabs" id="nav-tab1" role="tablist">
							<a class="nav-item nav-link login active" id="nav-log-tab11" data-toggle="tab"
								href="#nav-log11" role="tab" aria-controls="nav-log" aria-selected="true">
								{{ $langg->lang234 }}
							</a>
							{{-- <a class="nav-item nav-link" id="nav-reg-tab11" data-toggle="tab" href="#nav-reg11"
								role="tab" aria-controls="nav-reg" aria-selected="false">
								{{ $langg->lang235 }}
							</a> --}}
						</div>
					</nav>
					<div class="tab-content" id="nav-tabContent">
						<div class="tab-pane fade show active" id="nav-log11" role="tabpanel"
							aria-labelledby="nav-log-tab1">

							<div class="login-area" style=" -webkit-box-shadow: none !important;  -moz-box-shadow: none !important; box-shadow: none !important;">
							 	
								<div class="login-form signin-form">

									@include('includes.admin.form-login')
									<form class="mloginform" action="{{ route('user.login.submit') }}" method="POST">
										{{ csrf_field() }}
										<div class="form-input">
											<input type="email" style="text-align:center;border-radius:10px;" name="email" placeholder="E-mail"
												required="">

										</div>
										<div class="form-input">
											<input type="password" class="Password" name="password"
												style="text-align:center;border-radius:10px;" placeholder="Kata Sandi" required="">

										</div>
										<div class="form-forgot-pass">
											<div class="left">
												<input type="checkbox" name="remember" id="mrp"
													{{ old('remember') ? 'checked' : '' }}>
												<label for="mrp">{{ $langg->lang175 }}</label>
											</div>
											<div class="right">
												<a href="javascript:;" id="show-forgot">
													{{ $langg->lang176 }}
												</a>
											</div>
										</div>
										<input type="hidden" name="modal" value="1">
										<input class="mauthdata" type="hidden" value="{{ $langg->lang177 }}">
										<div class="row">
											<div class="col-lg-6">
										<button data-dismiss="modal" class="myButton90">Nanti Saja</button>
										</div>
											<div class="col-lg-6">
										<button type="submit" class="myButton95">{{ $langg->lang178 }}</button>
									</div>
										</div>
										@if(App\Models\Socialsetting::find(1)->f_check == 1 ||
										App\Models\Socialsetting::find(1)->g_check == 1)
										<div class="social-area">

											<p class="text"><font color="#A5A5A5">atau masuk dengan</font></p>
											<ul class="social-links">
												

												@if(App\Models\Socialsetting::find(1)->f_check == 1)
												<li>
													<a href="{{ route('social-provider','google') }}">
														<span style="text-align: center;
															font: Regular 15px/25px Kanit;
															letter-spacing: 0;
															color: #34A853;
															opacity: 1;
															top: 569px;
															left: 646px;
															width: 64px;
															height: 23px;">google</span>
													</a>
												</li>
												@endif
												@if(App\Models\Socialsetting::find(1)->g_check == 1)
												<li>&nbsp;</li>
												<li>&nbsp;</li>

												<li>
													<a href="{{ route('social-provider','facebook') }}">
														<span style="text-align: center;
															font: Regular 15px/25px Kanit;
															letter-spacing: 0;
															color: #4267B2;
															opacity: 1;
															top: 569px;
												left: 646px;
												width: 64px;
												height: 23px;">Facebook</span>
													</a>
												</li>
												@endif
											</ul>
										</div>
										@endif
									</form>

								</div>
							</div>
						</div>						
						{{-- <div class="tab-pane fade show active" id="nav-log11" role="tabpanel"
							aria-labelledby="nav-log-tab">
							<div class="login-area">
								<div class="login-form signin-form">
									@include('includes.admin.form-login')
									<form class="mloginform" action="{{ route('user.login.submit') }}" method="POST">
										{{ csrf_field() }}
										<div class="form-input">
											<input type="email" name="email" placeholder="{{ $langg->lang173 }}"
												required="">
											<i class="icofont-user-alt-5"></i>
										</div>
										<div class="form-input">
											<input type="password" class="Password" name="password"
												placeholder="{{ $langg->lang174 }}" required="">
											<i class="icofont-ui-password"></i>
										</div>
										<div class="form-forgot-pass">
											<div class="left">
												<input type="checkbox" name="remember" id="mrp1"
													{{ old('remember') ? 'checked' : '' }}>
												<label for="mrp1">{{ $langg->lang175 }}</label>
											</div>
											<div class="right">
												<a href="javascript:;" id="show-forgot1">
													{{ $langg->lang176 }}
												</a>
											</div>
										</div>
										<input type="hidden" name="modal" value="1">
										<input type="hidden" name="vendor" value="1">
										<input class="mauthdata" type="hidden" value="{{ $langg->lang177 }}">
										<button type="submit" class="submit-btn">{{ $langg->lang178 }}</button>
										@if(App\Models\Socialsetting::find(1)->f_check == 1 ||
										App\Models\Socialsetting::find(1)->g_check == 1)
										<div class="social-area">
											<h3 class="title">{{ $langg->lang179 }}</h3>
											<p class="text">{{ $langg->lang180 }}</p>
											<ul class="social-links">
												@if(App\Models\Socialsetting::find(1)->f_check == 1)
												<li>
													<a href="{{ route('social-provider','facebook') }}">
														<i class="fab fa-facebook-f"></i>
													</a>
												</li>
												@endif
												@if(App\Models\Socialsetting::find(1)->g_check == 1)
												<li>
													<a href="{{ route('social-provider','google') }}">
														<i class="fab fa-google-plus-g"></i>
													</a>
												</li>
												@endif
											</ul>
										</div>
										@endif
									</form>
								</div>
							</div>
						</div> --}}
						{{-- <div class="tab-pane fade" id="nav-reg11" role="tabpanel" aria-labelledby="nav-reg-tab">
							<div class="login-area signup-area">
								<div class="login-form signup-form">
									@include('includes.admin.form-login')
									<form class="mregisterform" action="{{route('user-register-submit')}}"
										method="POST">
										{{ csrf_field() }}

										<div class="row">

											<div class="col-lg-6">
												<div class="form-input">
													<input type="text" class="User Name" name="name"
														placeholder="{{ $langg->lang182 }}" required="">
													<i class="icofont-user-alt-5"></i>
												</div>
											</div>

											<div class="col-lg-6">
												<div class="form-input">
													<input type="email" class="User Name" name="email"
														placeholder="{{ $langg->lang183 }}" required="">
													<i class="icofont-email"></i>
												</div>

											</div>
											<div class="col-lg-6">
												<div class="form-input">
													<input type="text" class="User Name" name="phone"
														placeholder="{{ $langg->lang184 }}" required="">
													<i class="icofont-phone"></i>
												</div>

											</div>
											<div class="col-lg-6">

												<div class="form-input">
													<input type="text" class="User Name" name="address"
														placeholder="{{ $langg->lang185 }}" required="">
													<i class="icofont-location-pin"></i>
												</div>
											</div>

											<div class="col-lg-6">
												<div class="form-input">
													<input type="text" class="User Name" name="shop_name"
														placeholder="{{ $langg->lang238 }}" required="">
													<i class="icofont-cart-alt"></i>
												</div>

											</div>
											<div class="col-lg-6">

												<div class="form-input">
													<input type="text" class="User Name" name="owner_name"
														placeholder="{{ $langg->lang239 }}" required="">
													<i class="icofont-cart"></i>
												</div>
											</div>
											<div class="col-lg-6">

												<div class="form-input">
													<input type="text" class="User Name" name="shop_number"
														placeholder="{{ $langg->lang240 }}" required="">
													<i class="icofont-shopping-cart"></i>
												</div>
											</div>
											<div class="col-lg-6">

												<div class="form-input">
													<input type="text" class="User Name" name="shop_address"
														placeholder="{{ $langg->lang241 }}" required="">
													<i class="icofont-opencart"></i>
												</div>
											</div>
											<div class="col-lg-6">

												<div class="form-input">
													<input type="text" class="User Name" name="reg_number"
														placeholder="{{ $langg->lang242 }}" required="">
													<i class="icofont-ui-cart"></i>
												</div>
											</div>
											<div class="col-lg-6">

												<div class="form-input">
													<input type="text" class="User Name" name="shop_message"
														placeholder="{{ $langg->lang243 }}" required="">
													<i class="icofont-envelope"></i>
												</div>
											</div>

											<div class="col-lg-6">
												<div class="form-input">
													<input type="password" class="Password" name="password"
														placeholder="{{ $langg->lang186 }}" required="">
													<i class="icofont-ui-password"></i>
												</div>

											</div>
											<div class="col-lg-6">
												<div class="form-input">
													<input type="password" class="Password" name="password_confirmation"
														placeholder="{{ $langg->lang187 }}" required="">
													<i class="icofont-ui-password"></i>
												</div>
											</div>

											@if($gs->is_capcha == 1)

											<div class="col-lg-6">


												<ul class="captcha-area">
													<li>
														<p>
															<img class="codeimg1"
																src="{{asset("assets/images/capcha_code.png")}}" alt="">
															<i class="fas fa-sync-alt pointer refresh_code "></i>
														</p>

													</li>
												</ul>


											</div>

											<div class="col-lg-6">

												<div class="form-input">
													<input type="text" class="Password" name="codes"
														placeholder="{{ $langg->lang51 }}" required="">
													<i class="icofont-refresh"></i>

												</div>



											</div>

											@endif

											<input type="hidden" name="vendor" value="1">
											<input class="mprocessdata" type="hidden" value="{{ $langg->lang188 }}">
											<button type="submit" class="submit-btn">{{ $langg->lang189 }}</button>

										</div>




									</form>
								</div>
							</div>
						</div> --}}
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- VENDOR LOGIN MODAL ENDS -->

	<!-- Product Quick View Modal -->

	<div class="modal fade" id="quickview" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog quickview-modal modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<div class="submit-loader">
					<img src="{{asset('assets/images/'.$gs->loader)}}" alt="">
				</div>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="container quick-view-modal">

					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Product Quick View Modal -->

	<!-- Order Tracking modal Start-->
	{{-- <div class="modal fade" id="track-order-modal" tabindex="-1" role="dialog" aria-labelledby="order-tracking-modal"
		aria-hidden="true">
		<div class="modal-dialog  modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h6 class="modal-title"> <b>{{ $langg->lang772 }}</b> </h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">

					<div class="order-tracking-content">
						<form id="track-form" class="track-form">
							{{ csrf_field() }}
							<input type="text" id="track-code" placeholder="{{ $langg->lang773 }}" required="">
							<button type="submit" class="mybtn1">{{ $langg->lang774 }}</button>
							<a href="#" data-toggle="modal" data-target="#order-tracking-modal"></a>
						</form>
					</div>

					<div>
						<div class="submit-loader d-none">
							<img src="{{asset('assets/images/'.$gs->loader)}}" alt="">
						</div>
						<div id="track-order">

						</div>
					</div>

				</div>
			</div>
		</div>
	</div>	 --}}

	@include('includes.modal-logout')	
	<!-- Order Tracking modal End -->
	<script type="text/javascript">
		var mainurl = "{{url('/')}}";
  var gs      = {!! json_encode($gs) !!};
  var langg    = {!! json_encode($langg) !!};

	</script>

	<!-- jquery -->
	<script src="{{asset('assets/front/js/jquery.js')}}"></script>
	{{-- <script src="{{asset('assets/front/js/vue.js')}}"></script> --}}
	<script src="{{asset('assets/front/jquery-ui/jquery-ui.min.js')}}"></script>
	<!-- popper -->
	<script src="{{asset('assets/front/js/popper.min.js')}}"></script>
	<!-- bootstrap -->
	<script src="{{asset('assets/front/js/bootstrap.min.js')}}"></script>
	<!-- plugin js-->
	<script src="{{asset('assets/front/js/plugin.js')}}"></script>

	<script src="{{asset('assets/front/js/xzoom.min.js')}}"></script>
	<script src="{{asset('assets/front/js/jquery.hammer.min.js')}}"></script>
	<script src="{{asset('assets/front/js/setup.js')}}"></script>

	<script src="{{asset('assets/front/js/toastr.js')}}"></script>
	<!-- main -->
	<script src="{{asset('assets/front/js/main.js')}}"></script>
	<!-- custom -->
	<script src="{{asset('assets/front/js/custom.js')}}"></script>
	<script>
		  $(window).scroll(function() {    
			var scroll = $(window).scrollTop();
			// console.log(scroll);
			if (scroll >= 50) {
				$('.logo-header').addClass('fixed-fan');
			} else{
				$('.logo-header').removeClass('fixed-fan');
			}
		});
	</script>
	@if (Auth::user())
	<script>
		//cek unread message
		$.ajax({
			type: 'get',
			url: "{{URL::to('/user/user/count_message')}}",
			success: function( data) {
				if(data > 0){
					document.getElementById("count-unread-messages").innerHTML = data+" pesan baru belum dibaca";
				}
			}
		});

		setInterval(function(){
            $.ajax({
                    type: "GET",
                    url:$(".notif-count").data('count'),
                    success:function(data){
                        $(".notif-count").text(data);
                      }
              });
    	}, 5000);
	</script>


	@endif

		<script type="text/javascript">
		$(document).ready(function(){
			$(document).on("click",'#nav-reg-tab1',function(){
				$('.pendaftaran').removeClass('d-none');


			});
			$(document).on("click",'#nav-log-tab1',function(){
				$('.pendaftaran').addClass('d-none');
					

			});
		});
	</script>	
	{!! $seo->google_analytics !!}

	@if($gs->is_talkto == 1)
	<!--Start of Tawk.to Script-->
	{!! $gs->talkto !!}
	<!--End of Tawk.to Script-->
	@endif

	@yield('scripts')

</body>

</html>

@else

<!DOCTYPE html>
<html lang="en">


	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		@if(isset($page->meta_tag) && isset($page->meta_description))
		<meta name="keywords" content="{{ $page->meta_tag }}">
		<meta name="description" content="{{ $page->meta_description }}">
		<title>{{$gs->title}}</title>
		@elseif(isset($blog->meta_tag) && isset($blog->meta_description))
		<meta name="keywords" content="{{ $blog->meta_tag }}">
		<meta name="description" content="{{ $blog->meta_description }}">
		{{$gs->title}}</title>
		@elseif(isset($productt))
		<meta name="keywords" content="{{ !empty($productt->meta_tag) ? implode(',', $productt->meta_tag ): '' }}">
		<meta name="description"
			content="{{ $productt->meta_description != null ? $productt->meta_description : strip_tags($productt->description) }}">
		<meta property="og:title" content="{{$productt->name}}" />
		<meta property="og:description"
			content="{{ $productt->meta_description != null ? $productt->meta_description : strip_tags($productt->description) }}" />
		<meta property="og:image" content="{{asset('assets/images/'.$productt->photo)}}" />
		<meta name="author" content="{{$gs->title}}">
		<title>{{$gs->title}}</title>
		@else
		<meta name="keywords" content="{{ $seo->meta_keys }}">
		<meta name="author" content="{{$gs->title}}">
		<title>{{$gs->title}}</title>
		@endif
		<!-- favicon -->
		<link rel="icon" type="image/x-icon" href="{{asset('assets/images/'.$gs->favicon)}}" />
		<!-- bootstrap -->
		<link rel="stylesheet" href="{{asset('assets/front/css/bootstrap.min.css')}}">
		<!-- Plugin css -->
		<link rel="stylesheet" href="{{asset('assets/front/css/plugin.css')}}">
		<link rel="stylesheet" href="{{asset('assets/front/css/animate.css')}}">
		<link rel="stylesheet" href="{{asset('assets/front/css/toastr.css')}}">
		<link rel="stylesheet" href="{{asset('assets/front/css/toastr.css')}}">
	
		<!-- jQuery Ui Css-->
		<link rel="stylesheet" href="{{asset('assets/front/jquery-ui/jquery-ui.min.css')}}">
		<link rel="stylesheet" href="{{asset('assets/front/jquery-ui/jquery-ui.structure.min.css')}}">
		<link rel="stylesheet" href="{{asset('assets/slick/slick.css')}}">
		<link rel="stylesheet" href="{{asset('assets/slick/slick-theme.css')}}">
	
		@if($langg->rtl == "1")
	
		<!-- stylesheet -->
		<link rel="stylesheet" href="{{asset('assets/front/css/rtl/style.css')}}">
		<link rel="stylesheet" href="{{asset('assets/front/css/rtl/custom.css')}}">
		<link rel="stylesheet" href="{{asset('assets/front/css/rtl/custom1.css')}}">
		<link rel="stylesheet" href="{{asset('assets/front/css/common.css')}}">
		<!-- responsive -->
		<link rel="stylesheet" href="{{asset('assets/front/css/rtl/responsive.css')}}">
		<link rel="stylesheet" href="{{asset('assets/front/css/common-responsive.css')}}">
	
		<!--Updated CSS-->
		<link rel="stylesheet"
			href="{{ asset('assets/front/css/rtl/styles.php?color='.str_replace('#','',$gs->colors).'&amp;'.'header_color='.str_replace('#','',$gs->header_color).'&amp;'.'footer_color='.str_replace('#','',$gs->footer_color).'&amp;'.'copyright_color='.str_replace('#','',$gs->copyright_color).'&amp;'.'menu_color='.str_replace('#','',$gs->menu_color).'&amp;'.'menu_hover_color='.str_replace('#','',$gs->menu_hover_color)) }}">
			
		@else
	
		<!-- stylesheet -->
		<link rel="stylesheet" href="{{asset('assets/front/css/style.css')}}">
		<link rel="stylesheet" href="{{asset('assets/front/css/custom.css')}}">
		<link rel="stylesheet" href="{{asset('assets/front/css/common.css')}}">
		<!-- responsive -->
		<link rel="stylesheet" href="{{asset('assets/front/css/responsive.css')}}">
		<link rel="stylesheet" href="{{asset('assets/front/css/common-responsive.css')}}">
	
		<!--Updated CSS-->
		<link rel="stylesheet"
			href="{{ asset('assets/front/css/styles.php?color='.str_replace('#','',$gs->colors).'&amp;'.'header_color='.str_replace('#','',$gs->header_color).'&amp;'.'footer_color='.str_replace('#','',$gs->footer_color).'&amp;'.'copyright_color='.str_replace('#','',$gs->copyright_color).'&amp;'.'menu_color='.str_replace('#','',$gs->menu_color).'&amp;'.'menu_hover_color='.str_replace('#','',$gs->menu_hover_color)) }}">
	
		@endif
		<link rel="stylesheet" href="{{asset('assets/front/css/kostum.css')}}">
		
		<style>
				@import url(https://fonts.googleapis.com/css?family=Montserrat:700);
			  
			  .ribbon {
				font-family: "Montserrat", sans-serif;
				font-weight: 700;
				font-size: 12px;
				text-transform: uppercase;
				letter-spacing: 1px;
				position: absolute;
				left: -20px;
				top: 42px;
				color: #34495e;
				background-color: rgba(255,255,255,0.5);
				border-top: 2px solid #000;
				border-bottom: 2px solid #000;
				padding: 10px 5px;
				-moz-transform: rotate(-45deg);
				-ms-transform: rotate(-45deg);
				-webkit-transform: rotate(-45deg);
				transform: rotate(-45deg);
				z-index: 3;
			  }
	
	
			  .terakhir{
				  display:inline;
				  float:left;
				  font-size:12px;
				  margin-right:15px;
				  color:#212121;
			  }
	
	
			
			  </style>
	
	
	
		@yield('styles')
	
	</head>
<body>
		@include('includes.modal-logout')
	@php
	$last_view=[];

	    $domain = $_SERVER['SERVER_NAME'];
        
        $vendor = App\Models\User::where('domain_name', $domain)->where('is_vendor', 2)->firstOrFail();
	@endphp
	
	@if(Auth::user() !== NULL)
	@php
	$last_view = DB::table('product_last_views')
				->leftJoin('products', 'product_last_views.product_id', '=', 'products.id')
				->where('product_last_views.user_id', Auth::user()->id)
				->orderBy('datetime', 'DESC')
				->limit(3)
				->get();
	@endphp
	@endif
	<section class="top-header">
			<div class="container">
				<div class="row">
					<div class="col-lg-12 remove-padding">
						<div class="content">
							<div class="left-content">
								<div class="list">
									<ul>
										<!-- @if($gs->is_language == 1)
										<li>
											<div class="language-selector">
												<i class="fas fa-globe-americas"></i>
												<select name="language" class="language selectors nice">
													@foreach(DB::table('languages')->get() as $language)
													<option value="{{route('front.language',$language->id)}}"
														{{ Session::has('language') ? ( Session::get('language') == $language->id ? 'selected' : '' ) : (DB::table('languages')->where('is_default','=',1)->first()->id == $language->id ? 'selected' : '') }}>
														{{$language->language}}</option>
													@endforeach
												</select>
											</div>
										</li>
										@endif -->
	
										<!-- @if($gs->is_currency == 1)
										<li>
											<div class="currency-selector">
												<span>{{ Session::has('currency') ?   DB::table('currencies')->where('id','=',Session::get('currency'))->first()->sign   : DB::table('currencies')->where('is_default','=',1)->first()->sign }}</span>
												<select name="currency" class="currency selectors nice">
													@foreach(DB::table('currencies')->get() as $currency)
													<option value="{{route('front.currency',$currency->id)}}"
														{{ Session::has('currency') ? ( Session::get('currency') == $currency->id ? 'selected' : '' ) : (DB::table('currencies')->where('is_default','=',1)->first()->id == $currency->id ? 'selected' : '') }}>
														{{$currency->name}}</option>
													@endforeach
												</select>
											</div>
										</li>
										@endif -->
										@if($gs->is_faq == 1)
											<li class="left-top"><a href="{{ route('front.faq') }}" style="border-right:0px !important;">FAQ</a></li>
										@endif
	
										@foreach(DB::table('pages')->where('header','=',1)->get() as $data)
										<li class="left-top"><a href="{{ route('front.page',$data->slug) }}" style="border-right:0px !important;">@if ($data->title == "About Us") Tentang Kami @else {{$data->title}} @endif</a></li>
										@endforeach
										@if($gs->is_contact == 1)
										<li class="left-top"><a href="{{ route('front.contact') }}" style="border-right:0px !important;">{{ $langg->lang20 }}</a></li>
										@endif
	
	
									</ul>
								</div>
							</div>
							<div class="right-content">

							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
	
	@if($gs->is_loader == 1)
	<div class="preloader" id="preloader"
		style="background: url({{asset('assets/images/'.$gs->loader)}}) no-repeat scroll center center #FFF;"></div>
	@endif

	@if($gs->is_popup== 1)

	@if(isset($visited))
	<div style="display:none">
		<img src="{{asset('assets/images/'.$gs->popup_background)}}">
	</div>

	<!--  Starting of subscribe-pre-loader Area   -->
	<div class="subscribe-preloader-wrap" id="subscriptionForm" style="display: none;">
		<div class="subscribePreloader__thumb"
			style="background-image: url({{asset('assets/images/'.$gs->popup_background)}});">
			<span class="preload-close"><i class="fas fa-times"></i></span>
			<div class="subscribePreloader__text text-center">
				<h1>{{$gs->popup_title}}</h1>
				<p>{{$gs->popup_text}}</p>
				<form action="{{route('front.subscribe')}}" id="subscribeform" method="POST">
					{{csrf_field()}}
					<div class="form-group">
						<input type="email" name="email" placeholder="{{ $langg->lang741 }}" required="">
						<button id="sub-btn" type="submit">{{ $langg->lang742 }}</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!--  Ending of subscribe-pre-loader Area   -->

	@endif

	@endif



	<!-- Logo Header Area Start -->
	<section class="logo-header">
		<div class="container" >
 			<div class="row ">
				<div class="col-lg-2 col-sm-4 col-5 remove-padding">
					<div class="logo">
						<a href="{{ route('front.index') }}">
							{{-- <img src="{{asset('assets/images/majapahit.png')}}" alt=""> --}}
							@if ($vendor->store_color <> '')
								<img src="{{asset('assets/images/store/'.$vendor->store_color->logo)}}" alt="" class="img-logo">
							@endif
						</a>
					</div>
				</div>
				<div class="col-lg-6 col-sm-12 remove-padding order-last order-sm-2 order-md-2">
					<div class="search-box-wrapper">
						<div class="search-box">
							<div class="categori-container" id="catSelectForm">
								<select name="category" id="category_select" class="categoris">
									<option value="">{{ $langg->lang1 }}</option>
									@foreach($categories as $data)
									<option value="{{ $data->slug }}"
										{{ Request::route('category') == $data->slug ? 'selected' : '' }}>
										{{ $data->name }}</option>
									@endforeach
								</select>
							</div>

							<form id="searchForm" class="search-form"
								action="{{ route('front.category', [Request::route('category'),Request::route('subcategory'),Request::route('childcategory')]) }}"
								method="GET">
								@if (!empty(request()->input('sort')))
								<input type="hidden" name="sort" value="{{ request()->input('sort') }}" autofocus>
								@endif
								@if (!empty(request()->input('minprice')))
								<input type="hidden" name="minprice" value="{{ request()->input('minprice') }}" autofocus>
								@endif
								@if (!empty(request()->input('maxprice')))
								<input type="hidden" name="maxprice" value="{{ request()->input('maxprice') }}" autofocus>
								@endif
								<input type="text" id="prod_name" name="search" placeholder="{{ $langg->lang2 }}"
									value="{{ request()->input('search') }}" autocomplete="off" autofocus>
								<div class="autocomplete">
									<div id="myInputautocomplete-list" class="autocomplete-items">
									</div>
								</div>
								<button type="submit"><i class="icofont-search-1"></i></button>
							</form>
							<!-- terakhir dilihat -->
		  					@if($last_view !== [])
							  <div class="terakhir">Terakhir Dilihat >> </div>
							@endif
							@foreach($last_view as $l)
							<div class="terakhir">
							<a href="{{ route('front.product',$l->slug) }}" title="{{$l->name}}"> {{ strlen($l->name) > 20 ? substr($l->name,0,20).'...' : $l->name }}</a>
							</div>
							@endforeach
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-sm-8 col-7 order-lg-last" style="">
		  
					<div class="helpful-links">
						<ul class="helpful-links-inner" style="margin-top:-10px">
							
							
							
							<li class="wishlist" data-toggle="tooltip" data-placement="top" title="{{ $langg->lang9 }}">
								@if(Auth::guard('web')->check())
								<a href="{{ route('user-wishlists') }}" class="wish">
									<i class="far fa-heart"></i>
									<span id="wishlist-count">{{ count(Auth::user()->wishlists) }}</span>
								</a>
								@else
								<a href="javascript:;" data-toggle="modal" id="wish-btn" data-target="#comment-log-reg"
									class="wish">
									<i class="far fa-heart"></i>
									<span id="wishlist-count">0</span>
								</a>
								@endif
							</li>

							<li class="compare" data-toggle="tooltip" data-placement="top" title="{{ $langg->lang10 }}">
								<a href="{{ route('product.compare') }}" class="wish compare-product">
									<div class="icon">
										<i class="fas fa-exchange-alt"></i>
										<span
											id="compare-count">{{ Session::has('compare') ? count(Session::get('compare')->items) : '0' }}</span>
									</div>
								</a>
							</li>

							<li class="my-dropdown" data-toggle="tooltip" data-placement="top"
								title="{{ $langg->lang3 }}">
								<a href="javascript:;" class="cart carticon">
									<div class="icon" style="">
									<i class="fas fa-fancart" style="padding-bottom:-400px;"></i>
										<!-- <i class="icofont-cart"></i> -->
										<!-- <img src="{{asset('assets/icon/cart.png')}}" alt="" class="img-fluid" width="20%"> -->
										<span class="cart-quantity" id="cart-count">
										  {{ Session::has('cart') ? count(Session::get('cart')->items) : '0' }}
										</span>	
									</div>

								</a>
								<div class="my-dropdown-menu" id="cart-items">
									  <div class="">
									  @include('load.cart')
									  </div>
								</div>
							</li>



							

							<!-- tambahan -->							
							<li class="batas kosong"></li>
							@if(!Auth::guard('web')->check())
							<li class="kanan-tambahan"><a href="{{ route('user.login') }}">{{ $langg->lang12 }}</a></li>
							<li class="kanan-tambahan" style=><a href="{{ route('user.login') }}">{{ $langg->lang13 }}</a></li>
							@else

							<li class="nav-item dropdown" style="">
								<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									@if(Auth::user()->photo == NULL OR Auth::user()->photo == "")
									<i class="far fa-user"></i> 
									@else
									<img src="{{asset('assets/images/users/'.Auth::user()->photo)}}" alt="" class="rounded-circle" style="display:inline!important;width:30px;height:30px;">
									@endif

									{{ strlen(Auth::user()->name) > 6 ? ucwords(substr(Auth::user()->name,0,6)).'...' : ucwords(Auth::user()->name) }}
								
								</a>
								<div class="dropdown-menu" aria-labelledby="navbarDropdown" style="border-radius:10px;text-align:center;font-size:15px;">
									<a class="dropdown-item" href="{{ route('user-dashboard') }}"> {{ $langg->lang221 }}</a>
									@if(Auth::user()->IsVendor())
															
									<a class="dropdown-item" href="{{ route('vendor-dashboard') }}">{{ $langg->lang222 }}</a>
									@endif
									<a class="dropdown-item"  href="{{ route('user-profile') }}">
														{{ $langg->lang205 }}</a>
														<a href="javascript:;" data-toggle="modal" data-target="#confirm-logout" data-href="{{ route('user-logout') }}" class="">{{ $langg->lang207 }}</a>

								</div>
							</li>
					
							@endif


									
							

						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Logo Header Area End -->

	{{-- @if (Route::current()->uri() !== '/' && Route::current()->uri() !== 'checkout')

	<div class="mainmenu-area mainmenu-bb">
		<div class="container">
			<div class="row align-items-center mainmenu-area-innner">
				<div class="col-lg-3 col-md-6 categorimenu-wrapper remove-padding">
					<!--categorie menu start-->
					<div class="categories_menu">
						<div class="categories_title">
							<h2 class="categori_toggle"><i class="fa fa-bars"></i> {{ $langg->lang14 }} <i
									class="fa fa-angle-down arrow-down"></i></h2>
						</div>
						<div class="categories_menu_inner">
							<ul>
								@php
								$i=1;
								@endphp
								@foreach($categories as $category)

								<li
									class="{{count($category->subs) > 0 ? 'dropdown_list':''}} {{ $i >= 15 ? 'rx-child' : '' }}">
									@if(count($category->subs) > 0)
									<div class="img">
										<img src="{{ asset('assets/images/categories/'.$category->photo) }}" alt="">
									</div>
									<div class="link-area">
										<span><a
												href="{{ route('front.category',$category->slug) }}">{{ $category->name }}</a></span>
										@if(count($category->subs) > 0)
										<a href="javascript:;">
											<i class="fa fa-angle-right" aria-hidden="true"></i>
										</a>
										@endif
									</div>

									@else
									<a href="{{ route('front.category',$category->slug) }}"><img
											src="{{ asset('assets/images/categories/'.$category->photo) }}">
										{{ $category->name }}</a>

									@endif
									@if(count($category->subs) > 0)

									@php
									$ck = 0;
									foreach($category->subs as $subcat) {
									if(count($subcat->childs) > 0) {
									$ck = 1;
									break;
									}
									}
									@endphp
									<ul
										class="{{ $ck == 1 ? 'categories_mega_menu' : 'categories_mega_menu column_1' }}">
										@foreach($category->subs as $subcat)
										<li>
											<a
												href="{{ route('front.subcat',['slug1' => $subcat->category->slug, 'slug2' => $subcat->slug]) }}">{{$subcat->name}}</a>
											@if(count($subcat->childs) > 0)
											<div class="categorie_sub_menu">
												<ul>
													@foreach($subcat->childs as $childcat)
													<li><a
															href="{{ route('front.childcat',['slug1' => $childcat->subcategory->category->slug, 'slug2' => $childcat->subcategory->slug, 'slug3' => $childcat->slug]) }}">{{$childcat->name}}</a>
													</li>
													@endforeach
												</ul>
											</div>
											@endif
										</li>
										@endforeach
									</ul>

									@endif

								</li>

								@php
								$i++;
								@endphp

								@if($i == 15)
								<li>
									<a href="{{ route('front.categories') }}"><i class="fas fa-plus"></i>
										{{ $langg->lang15 }} </a>
								</li>
								@break
								@endif


								@endforeach

							</ul>
						</div>
					</div>
					<!--categorie menu end-->
					
				</div>
			</div>
		</div>
	</div>
	
	@endif --}}

	

	@yield('content')



	

	<!-- Footer Area Start -->
	<footer class="footer" id="footer" style="padding:0px; background: none;">

		<div class="copy-bg">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="content">
							<div class="content">
								<p>{!! $gs->copyright !!}</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</footer>
	<!-- Footer Area End -->

	<!-- Back to Top Start -->
	<div class="bottomtotop">
		<i class="fas fa-chevron-right"></i>
	</div>
	<!-- Back to Top End -->

	<!-- LOGIN MODAL -->
	<div class="modal fade" id="comment-log-reg" tabindex="-1" role="dialog" aria-labelledby="comment-log-reg-Title"
		aria-hidden="true">

		<div class="modal-dialog  modal-dialog-centered" role="document">
			<div class="row">
						
						<div class="col-lg-12">
						<div align="center" style="text-align: center;background: #FCB415; border-radius: 5px 5px 0px 0px;margin-left:30px; width: 450px;font: SemiBold 22px/25px Kanit;letter-spacing: 0;color: #FFFFFF;" class="d-none pendaftaran">
							Halo, Selamat Bergabung!
						</div>
						</div>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" aria-label="Close">
							<a href="{{ url()->previous() }}"><span aria-hidden="true">&times;</span></a>
					</button>
				</div>

				<div class="modal-body">
					
					</div>
					<nav class="comment-log-reg-tabmenu">
						<div class="nav nav-tabs" id="nav-tab" role="tablist">
							<a class="nav-item nav-link login active" id="nav-log-tab1" data-toggle="tab"
								href="#nav-log1" role="tab" aria-controls="nav-log" aria-selected="true">
								{{ $langg->lang197 }}
							</a>
							

							<a class="nav-item nav-link" id="nav-reg-tab1" data-toggle="tab" href="#nav-reg1" role="tab"
								aria-controls="nav-reg" aria-selected="false">
								{{ $langg->lang198 }}
							</a>

							<hr style="border: 1px solid #A5A5A5;
						margin-bottom: 10px;padding-left:450px;">
						</div>

					</nav>

					<div class="tab-content" id="nav-tabContent">
						



						<div class="tab-pane fade show active" id="nav-log1" role="tabpanel"
							aria-labelledby="nav-log-tab1">

							<div class="login-area" style=" -webkit-box-shadow: none !important;  -moz-box-shadow: none !important; box-shadow: none !important;">
							 	
								<div class="login-form signin-form">

									@include('includes.admin.form-login')
									<form class="mloginform" action="{{ route('user.login.submit') }}" method="POST">
										{{ csrf_field() }}
										<div class="form-input">
											<input type="email" style="text-align:center;border-radius:10px;" name="email" placeholder="E-mail"
												required="">

										</div>
										<div class="form-input">
											<input type="password" class="Password" name="password"
												style="text-align:center;border-radius:10px;" placeholder="Kata Sandi" required="">

										</div>
										<div class="form-forgot-pass">
											<div class="left">
												<input type="checkbox" name="remember" id="mrp"
													{{ old('remember') ? 'checked' : '' }}>
												<label for="mrp">{{ $langg->lang175 }}</label>
											</div>
											<div class="right">
												<a href="javascript:;" id="show-forgot">
													{{ $langg->lang176 }}
												</a>
											</div>
										</div>
										<input type="hidden" name="modal" value="1">
										<input class="mauthdata" type="hidden" value="{{ $langg->lang177 }}">
										<div class="row">
											<div class="col-lg-6">
										<button data-dismiss="modal" class="myButton90">Nanti Saja</button>
										</div>
											<div class="col-lg-6">
										<button type="submit" class="myButton95">{{ $langg->lang178 }}</button>
									</div>
										</div>
										@if(App\Models\Socialsetting::find(1)->f_check == 1 ||
										App\Models\Socialsetting::find(1)->g_check == 1)
										<div class="social-area">

											<p class="text"><font color="#A5A5A5">atau masuk dengan</font></p>
											<ul class="social-links">
												

												@if(App\Models\Socialsetting::find(1)->f_check == 1)
												<li>
													<a href="{{ route('social-provider','google') }}">
														<span style="text-align: center;
															font: Regular 15px/25px Kanit;
															letter-spacing: 0;
															color: #34A853;
															opacity: 1;
															top: 569px;
															left: 646px;
															width: 64px;
															height: 23px;">google</span>
													</a>
												</li>
												@endif
												@if(App\Models\Socialsetting::find(1)->g_check == 1)
												<li>&nbsp;</li>
												<li>&nbsp;</li>

												<li>
													<a href="{{ route('social-provider','facebook') }}">
														<span style="text-align: center;
															font: Regular 15px/25px Kanit;
															letter-spacing: 0;
															color: #4267B2;
															opacity: 1;
															top: 569px;
												left: 646px;
												width: 64px;
												height: 23px;">Facebook</span>
													</a>
												</li>
												@endif
											</ul>
										</div>
										@endif
									</form>

								</div>
							</div>
						</div>

						<div class="tab-pane fade" id="nav-reg1" role="tabpanel" aria-labelledby="nav-reg-tab1">
							
							<div class="login-area signup-area">
								<!--<div class="header-area">
								
								</div>-->
								<div class="login-form signup-form">
									@include('includes.admin.form-login')
									<form class="mregisterform" action="{{route('user-register-submit')}}"
										method="POST">
										{{ csrf_field() }}

										<div class="form-input">
											<input type="text" class="User Name" name="name"
												placeholder="{{ $langg->lang182 }}" style="text-align:center;border-radius:10px;" required="">
											
										</div>

										<div class="form-input">
											<input type="email" class="User Name" name="email"
												placeholder="{{ $langg->lang183 }}" style="text-align:center;border-radius:10px;" required="">
											
										</div>

										<div class="form-input">
											<input type="text" class="User Name" name="phone"
												placeholder="{{ $langg->lang184 }}" style="text-align:center;border-radius:10px;" required="">
											</div>

										<div class="form-input">
											<input type="text" class="User Name" name="address"
												placeholder="{{ $langg->lang185 }}" style="text-align:center;border-radius:10px;" required="">
											
										</div>

										<div class="form-input">
											<input type="password" class="Password" name="password"
												placeholder="{{ $langg->lang186 }}" style="text-align:center;border-radius:10px;" required="">
											
										</div>

										<div class="form-input">
											<input type="password" class="Password" name="password_confirmation"
												placeholder="{{ $langg->lang187 }}" style="text-align:center;border-radius:10px;" required="">
											
										</div>

										<div class="row">
											<div class="col-lg-6">
										@if($gs->is_capcha == 1)

										<ul class="captcha-area">
											<li>
												<p><img class="codeimg1" style="width: 150px; height: 31px;"
														src="{{asset("assets/images/capcha_code.png")}}" alt=""> <i
														class="fas fa-sync-alt pointer refresh_code "></i></p>
											</li>
										</ul>
									</div>

											<div class="col-lg-6">
										<div class="form-input">
											<input type="text" class="Password" name="codes"
												placeholder="{{ $langg->lang51 }}" style="text-align:center;border-radius:10px;" required="">
											
										</div>


										@endif

										<input class="mprocessdata" type="hidden" value="{{ $langg->lang188 }}">
									</div>
								</div>
										<div class="row">
											<div class="col-lg-6">
										<button data-dismiss="modal" class="myButton90">Nanti Saja</button>
										</div>
											<div class="col-lg-6">
										<button type="submit" class="myButton95">{{ $langg->lang178 }}</button>
									</div>
										</div>

									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	
	<!-- LOGIN MODAL ENDS -->

	<!-- FORGOT MODAL -->
	<div class="modal fade" id="forgot-modal" tabindex="-1" role="dialog" aria-labelledby="comment-log-reg-Title"
		aria-hidden="true">
		<div class="modal-dialog  modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">

					<div class="login-area">
						<div class="header-area forgot-passwor-area">
							<h4 class="title">{{ $langg->lang191 }} </h4>
							<p class="text">{{ $langg->lang192 }} </p>
						</div>
						<div class="login-form">
							@include('includes.admin.form-login')
							<form id="mforgotform" action="{{route('user-forgot-submit')}}" method="POST">
								{{ csrf_field() }}
								<div class="form-input">
									<input type="email" name="email" class="User Name"
										placeholder="{{ $langg->lang193 }}" required="">
									<i class="icofont-user-alt-5"></i>
								</div>
								<div class="to-login-page">
									<a href="javascript:;" id="show-login">
										{{ $langg->lang194 }}
									</a>
								</div>
								<input class="fauthdata" type="hidden" value="{{ $langg->lang195 }}">
								<button type="submit" class="submit-btn">{{ $langg->lang196 }}</button>
							</form>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
	<!-- FORGOT MODAL ENDS -->


	<!-- VENDOR LOGIN MODAL -->
	<div class="modal fade" id="vendor-login" tabindex="-1" role="dialog" aria-labelledby="vendor-login-Title"
		aria-hidden="true">
		<div class="modal-dialog  modal-dialog-centered" style="transition: .5s;" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<nav class="comment-log-reg-tabmenu">
						<div class="nav nav-tabs" id="nav-tab1" role="tablist">
							<a class="nav-item nav-link login active" id="nav-log-tab11" data-toggle="tab"
								href="#nav-log11" role="tab" aria-controls="nav-log" aria-selected="true">
								{{ $langg->lang234 }}
							</a>
							<a class="nav-item nav-link" id="nav-reg-tab11" data-toggle="tab" href="#nav-reg11"
								role="tab" aria-controls="nav-reg" aria-selected="false">
								{{ $langg->lang235 }}
							</a>
						</div>
					</nav>
					<div class="tab-content" id="nav-tabContent">
						<div class="tab-pane fade show active" id="nav-log11" role="tabpanel"
							aria-labelledby="nav-log-tab">
							<div class="login-area">
								<div class="login-form signin-form">
									@include('includes.admin.form-login')
									<form class="mloginform" action="{{ route('user.login.submit') }}" method="POST">
										{{ csrf_field() }}
										<div class="form-input">
											<input type="email" name="email" placeholder="{{ $langg->lang173 }}"
												required="">
											<i class="icofont-user-alt-5"></i>
										</div>
										<div class="form-input">
											<input type="password" class="Password" name="password"
												placeholder="{{ $langg->lang174 }}" required="">
											<i class="icofont-ui-password"></i>
										</div>
										<div class="form-forgot-pass">
											<div class="left">
												<input type="checkbox" name="remember" id="mrp1"
													{{ old('remember') ? 'checked' : '' }}>
												<label for="mrp1">{{ $langg->lang175 }}</label>
											</div>
											<div class="right">
												<a href="javascript:;" id="show-forgot1">
													{{ $langg->lang176 }}
												</a>
											</div>
										</div>
										<input type="hidden" name="modal" value="1">
										<input type="hidden" name="vendor" value="1">
										<input class="mauthdata" type="hidden" value="{{ $langg->lang177 }}">
										<button type="submit" class="submit-btn">{{ $langg->lang178 }}</button>
										@if(App\Models\Socialsetting::find(1)->f_check == 1 ||
										App\Models\Socialsetting::find(1)->g_check == 1)
										<div class="social-area">
											<h3 class="title">{{ $langg->lang179 }}</h3>
											<p class="text">{{ $langg->lang180 }}</p>
											<ul class="social-links">
												@if(App\Models\Socialsetting::find(1)->f_check == 1)
												<li>
													<a href="{{ route('social-provider','facebook') }}">
														<i class="fab fa-facebook-f"></i>
													</a>
												</li>
												@endif
												@if(App\Models\Socialsetting::find(1)->g_check == 1)
												<li>
													<a href="{{ route('social-provider','google') }}">
														<i class="fab fa-google-plus-g"></i>
													</a>
												</li>
												@endif
											</ul>
										</div>
										@endif
									</form>
								</div>
							</div>
						</div>
						<div class="tab-pane fade" id="nav-reg11" role="tabpanel" aria-labelledby="nav-reg-tab">
							<div class="login-area signup-area">
								<div class="login-form signup-form">
									@include('includes.admin.form-login')
									<form class="mregisterform" action="{{route('user-register-submit')}}"
										method="POST">
										{{ csrf_field() }}

										<div class="row">

											<div class="col-lg-6">
												<div class="form-input">
													<input type="text" class="User Name" name="name"
														placeholder="{{ $langg->lang182 }}" required="">
													<i class="icofont-user-alt-5"></i>
												</div>
											</div>

											<div class="col-lg-6">
												<div class="form-input">
													<input type="email" class="User Name" name="email"
														placeholder="{{ $langg->lang183 }}" required="">
													<i class="icofont-email"></i>
												</div>

											</div>
											<div class="col-lg-6">
												<div class="form-input">
													<input type="text" class="User Name" name="phone"
														placeholder="{{ $langg->lang184 }}" required="">
													<i class="icofont-phone"></i>
												</div>

											</div>
											<div class="col-lg-6">

												<div class="form-input">
													<input type="text" class="User Name" name="address"
														placeholder="{{ $langg->lang185 }}" required="">
													<i class="icofont-location-pin"></i>
												</div>
											</div>

											<div class="col-lg-6">
												<div class="form-input">
													<input type="text" class="User Name" name="shop_name"
														placeholder="{{ $langg->lang238 }}" required="">
													<i class="icofont-cart-alt"></i>
												</div>

											</div>
											<div class="col-lg-6">

												<div class="form-input">
													<input type="text" class="User Name" name="owner_name"
														placeholder="{{ $langg->lang239 }}" required="">
													<i class="icofont-cart"></i>
												</div>
											</div>
											<div class="col-lg-6">

												<div class="form-input">
													<input type="text" class="User Name" name="shop_number"
														placeholder="{{ $langg->lang240 }}" required="">
													<i class="icofont-shopping-cart"></i>
												</div>
											</div>
											<div class="col-lg-6">

												<div class="form-input">
													<input type="text" class="User Name" name="shop_address"
														placeholder="{{ $langg->lang241 }}" required="">
													<i class="icofont-opencart"></i>
												</div>
											</div>
											<div class="col-lg-6">

												<div class="form-input">
													<input type="text" class="User Name" name="reg_number"
														placeholder="{{ $langg->lang242 }}" required="">
													<i class="icofont-ui-cart"></i>
												</div>
											</div>
											<div class="col-lg-6">

												<div class="form-input">
													<input type="text" class="User Name" name="shop_message"
														placeholder="{{ $langg->lang243 }}" required="">
													<i class="icofont-envelope"></i>
												</div>
											</div>

											<div class="col-lg-6">
												<div class="form-input">
													<input type="password" class="Password" name="password"
														placeholder="{{ $langg->lang186 }}" required="">
													<i class="icofont-ui-password"></i>
												</div>

											</div>
											<div class="col-lg-6">
												<div class="form-input">
													<input type="password" class="Password" name="password_confirmation"
														placeholder="{{ $langg->lang187 }}" required="">
													<i class="icofont-ui-password"></i>
												</div>
											</div>

											@if($gs->is_capcha == 1)

											<div class="col-lg-6">


												<ul class="captcha-area">
													<li>
														<p>
															<img class="codeimg1"
																src="{{asset("assets/images/capcha_code.png")}}" alt="">
															<i class="fas fa-sync-alt pointer refresh_code "></i>
														</p>

													</li>
												</ul>


											</div>

											<div class="col-lg-6">

												<div class="form-input">
													<input type="text" class="Password" name="codes"
														placeholder="{{ $langg->lang51 }}" required="">
													<i class="icofont-refresh"></i>

												</div>



											</div>

											@endif

											<input type="hidden" name="vendor" value="1">
											<input class="mprocessdata" type="hidden" value="{{ $langg->lang188 }}">
											<button type="submit" class="submit-btn">{{ $langg->lang189 }}</button>

										</div>




									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- VENDOR LOGIN MODAL ENDS -->

	<!-- Product Quick View Modal -->

	<div class="modal fade" id="quickview" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog quickview-modal modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<div class="submit-loader">
					<img src="{{asset('assets/images/'.$gs->loader)}}" alt="">
				</div>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="container quick-view-modal">

					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Product Quick View Modal -->

	<!-- Order Tracking modal Start-->
	<div class="modal fade" id="track-order-modal" tabindex="-1" role="dialog" aria-labelledby="order-tracking-modal"
		aria-hidden="true">
		<div class="modal-dialog  modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h6 class="modal-title"> <b>{{ $langg->lang772 }}</b> </h6>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">

					<div class="order-tracking-content">
						<form id="track-form" class="track-form">
							{{ csrf_field() }}
							<input type="text" id="track-code" placeholder="{{ $langg->lang773 }}" required="">
							<button type="submit" class="mybtn1">{{ $langg->lang774 }}</button>
							<a href="#" data-toggle="modal" data-target="#order-tracking-modal"></a>
						</form>
					</div>

					<div>
						<div class="submit-loader d-none">
							<img src="{{asset('assets/images/'.$gs->loader)}}" alt="">
						</div>
						<div id="track-order">

						</div>
					</div>

				</div>
			</div>
		</div>
	</div>	
	<!-- Order Tracking modal End -->


	@include('includes.modal-logout')	
	
	<script type="text/javascript">
		var mainurl = "{{url('/')}}";
  var gs      = {!! json_encode($gs) !!};
  var langg    = {!! json_encode($langg) !!};

	</script>

	<!-- jquery -->
	<script src="{{asset('assets/front/js/jquery.js')}}"></script>
	{{-- <script src="{{asset('assets/front/js/vue.js')}}"></script> --}}
	<script src="{{asset('assets/front/jquery-ui/jquery-ui.min.js')}}"></script>
	<!-- popper -->
	<script src="{{asset('assets/front/js/popper.min.js')}}"></script>
	<!-- bootstrap -->
	<script src="{{asset('assets/front/js/bootstrap.min.js')}}"></script>
	<!-- plugin js-->
	<script src="{{asset('assets/front/js/plugin.js')}}"></script>

	<script src="{{asset('assets/front/js/xzoom.min.js')}}"></script>
	<script src="{{asset('assets/front/js/jquery.hammer.min.js')}}"></script>
	<script src="{{asset('assets/front/js/setup.js')}}"></script>

	<script src="{{asset('assets/front/js/toastr.js')}}"></script>
	<!-- main -->
	<script src="{{asset('assets/front/js/main.js')}}"></script>
	<!-- custom -->
	<script src="{{asset('assets/front/js/custom.js')}}"></script>
	@if (Auth::user())
	<script>
		//cek unread message
		$.ajax({
			type: 'get',
			url: "{{URL::to('/user/user/count_message')}}",
			success: function( data) {
				if(data > 0){
					$('#count-unread-messages').text( data+" pesan baru belum dibaca" );
				}
			}
		});


		setInterval(function(){
            $.ajax({
                    type: "GET",
                    url:$(".notif-count").data('count'),
                    success:function(data){
                        $(".notif-count").text(data);
                      }
              });
    	}, 5000);


	</script>
	@endif
	{!! $seo->google_analytics !!}

	@if($gs->is_talkto == 1)
	<!--Start of Tawk.to Script-->
	{!! $gs->talkto !!}
	<!--End of Tawk.to Script-->
	@endif
	

	@yield('scripts')




</body>

</html>

@endif
