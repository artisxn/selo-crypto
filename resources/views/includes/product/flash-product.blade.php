@if(Carbon\Carbon::now()->format('Y-m-d') < Carbon\Carbon::parse($prod->discount_date)->format('Y-m-d'))

		{{-- If This product belongs to vendor then apply this --}}
		@if($prod->user_id != 0)

		{{-- check  If This vendor status is active --}}
		@if($prod->user->is_vendor == 2)
		<a href="{{ route('front.product', $prod->slug) }}" class="card" style="border-radius:10px;border:none;">
			<!-- <div class="card-title" style="border-radius:10px!important;background-color:#B9BBBC;">
			</div> -->
			<div class="card-header" style="padding:0px;border-top-right-radius:10px;border-top-left-radius:10px;border:none;">
				<img class="img-fluid" src="{{ $prod->photo ? asset('assets/images/thumbnails/'.$prod->thumbnail):asset('assets/images/noimage.png') }}" alt="" style="border-top-right-radius:10px;border-top-left-radius:10px;height:240px!important;">
			</div>
			<div class="card-body" style="height:160px;">
				{{-- @if(!empty($prod->features))
					<div class="sell-area">
					@foreach($prod->features as $key => $data1)
						<span class="sale" style="background-color:{{ $prod->colors[$key] }}">{{ $prod->features[$key] }}</span>
						@endforeach 
					</div>
				@endif --}}
					<!-- <div class="extra-list">
						<ul>
							<li>
								@if(Auth::guard('web')->check())

								<span class="add-to-wish" data-href="{{ route('user-wishlist-add',$prod->id) }}" data-toggle="tooltip" data-placement="right" title="{{ $langg->lang54 }}" data-placement="right"><i class="icofont-heart-alt" ></i>
								</span>

								@else 

								<span rel-toggle="tooltip" title="{{ $langg->lang54 }}" data-toggle="modal" id="wish-btn" data-target="#comment-log-reg" data-placement="right">
									<i class="icofont-heart-alt"></i>
								</span>

								@endif
							</li>
							<li>
							<span class="quick-view" rel-toggle="tooltip" title="{{ $langg->lang55 }}" href="javascript:;" data-href="{{ route('product.quick',$prod->id) }}" data-toggle="modal" data-target="#quickview" data-placement="right"> <i class="icofont-eye"></i>
							</span>
							</li>
							<li>
								<span class="add-to-compare" data-href="{{ route('product.compare.add',$prod->id) }}"  data-toggle="tooltip" data-placement="right" title="{{ $langg->lang57 }}" data-placement="right">
									<i class="icofont-exchange"></i>
								</span>
							</li>
							@if(Auth::guard('web')->check() && @Auth::user()->subscribes->is_dropship === '1')
							<li>
								<span class="add-to-dropship" data-href="{{ route('vendor-dropship-store',$prod->id) }}"  data-toggle="tooltip" data-placement="right" title="{{ __('Tambahkan Ke Produk Dropship') }}" data-placement="right">
									<i class="far fa-handshake"></i>
								</span>
							</li>
							@endif																	
						</ul>
					</div> -->

				<center>
				<p style="color:#212121;">{{ $prod->showName() }}</p>
				<h5 class="price" style="color:#EF4623;">{{ $prod->showPrice() }} <br><del><small>{{ $prod->showPreviousPrice() }}</small></del></h5>
				<!-- <div class="deal-counter"> -->
				<!-- </div> -->
				</center>
				
			</div>
			<div class="card-footer" style="font-size:15px;font-weight:bold; padding:0px;border-bottom-right-radius:10px;border-bottom-left-radius:10px;">
				<div data-countdown="{{ $prod->discount_date }}" style="background-color:#FCB415;color:#fff;text-align:center;padding:15px;border-bottom-right-radius:10px;border-bottom-left-radius:10px;"></div>
			</div>
			<div class="info">
				<!-- <div class="stars">
																	<div class="ratings">
																			<div class="empty-stars"></div>
																			<div class="full-stars" style="width:{{App\Models\Rating::ratings($prod->id)}}%"></div>
																	</div>
				</div> -->
				<!-- <p class="">{{ $prod->showName() }}</p>
				<h5 class="price">{{ $prod->showPrice() }} <del><small>{{ $prod->showPreviousPrice() }}</small></del></h5> -->
						
						<!-- <div class="item-cart-area">
							@if($prod->product_type == "affiliate")
								<span class="add-to-cart-btn affilate-btn"
									data-href="{{ route('affiliate.product', $prod->slug) }}"><i class="icofont-cart"></i>
									{{ $langg->lang251 }}
								</span>
							@else
								@if($prod->emptyStock())
								<span class="add-to-cart-btn cart-out-of-stock">
									<i class="icofont-close-circled"></i> {{ $langg->lang78 }}
								</span>													
								@else
								<span class="add-to-cart add-to-cart-btn" data-href="{{ route('product.cart.add',$prod->id) }}">
									<i class="icofont-cart"></i> {{ $langg->lang56 }}
								</span>
								<span class="add-to-cart-quick add-to-cart-btn"
									data-href="{{ route('product.cart.quickadd',$prod->id) }}">
									<i class="icofont-cart"></i> {{ $langg->lang251 }}
								</span>
								@endif
							@endif
						</div> -->
			</div>
			

		</a>


@endif

		{{-- If This product belongs admin and apply this --}}

@else 

<a href="{{ route('front.product', $prod->slug) }}" class="item">
<div class="item-img">
@if(!empty($prod->features))
<div class="sell-area">
@foreach($prod->features as $key => $data1)
<span class="sale" style="background-color:{{ $prod->colors[$key] }}">{{ $prod->features[$key] }}</span>
@endforeach 
</div>
@endif
<div class="extra-list">
<ul>
<li>
@if(Auth::guard('web')->check())

<span class="add-to-wish" data-href="{{ route('user-wishlist-add',$prod->id) }}" data-toggle="tooltip" data-placement="right" title="{{ $langg->lang54 }}" data-placement="right"><i class="icofont-heart-alt" ></i>
</span>

@else 

<span rel-toggle="tooltip" title="{{ $langg->lang54 }}" data-toggle="modal" id="wish-btn" data-target="#comment-log-reg" data-placement="right">
	<i class="icofont-heart-alt"></i>
</span>

@endif
</li>
<li>
<span class="quick-view" rel-toggle="tooltip" title="{{ $langg->lang55 }}" href="javascript:;" data-href="{{ route('product.quick',$prod->id) }}" data-toggle="modal" data-target="#quickview" data-placement="right"> <i class="icofont-eye"></i>
</span>
</li>
<li>
<span class="add-to-compare" data-href="{{ route('product.compare.add',$prod->id) }}"  data-toggle="tooltip" data-placement="right" title="{{ $langg->lang57 }}" data-placement="right">
	<i class="icofont-exchange"></i>
</span>
</li>
</ul>
</div>
<img class="img-fluid" src="{{ $prod->photo ? asset('assets/images/thumbnails/'.$prod->thumbnail):asset('assets/images/noimage.png') }}" alt="">
</div>
<div class="info">
<div class="stars">
									<div class="ratings">
											<div class="empty-stars"></div>
											<div class="full-stars" style="width:{{App\Models\Rating::ratings($prod->id)}}%"></div>
									</div>
</div>
<h4 class="price">{{ $prod->showPrice() }} <del><small>{{ $prod->showPreviousPrice() }}</small></del></h4>
<h5 class="name">{{ $prod->showName() }}</h5>
<div class="item-cart-area">
					@if($prod->product_type == "affiliate")
						<span class="add-to-cart-btn affilate-btn" data-href="{{ route('affiliate.product', $prod->slug) }}"><i class="icofont-cart"></i> {{ $langg->lang251 }}
						</span>
					@else
						<span class="add-to-cart add-to-cart-btn" data-href="{{ route('product.cart.add',$prod->id) }}">
							<i class="icofont-cart"></i> {{ $langg->lang56 }}
						</span>
						<span class="add-to-cart-quick add-to-cart-btn" data-href="{{ route('product.cart.quickadd',$prod->id) }}">
							<i class="icofont-cart"></i> {{ $langg->lang251 }}
						</span>
					@endif
</div>
</div>

<div class="deal-counter">
<div data-countdown="{{ $prod->discount_date }}"></div>
</div>
</a>


@endif

@endif				