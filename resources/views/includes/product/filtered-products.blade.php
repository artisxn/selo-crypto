				@if (count($prods) > 0)
					@foreach ($prods as $key => $prod)
									<div class="col-lg-3 col-md-4 col-6 padding-8">


										<a href="{{ route('front.product', $prod->slug) }}" class="item">
											<div class="item-img">
												{{-- @if(!empty($prod->features))
													<div class="sell-area">
													  @foreach($prod->features as $key => $data1)
														<span class="sale" style="background-color:{{ $prod->colors[$key] }}">{{ $prod->features[$key] }}</span>
														@endforeach
													</div>
												@endif --}}
												{{-- @if ($prod->is_highlight === '1')
												<span class="ribbon">Highlight Products</span>
												@endif
													<div class="extra-list" style="left: 240px;">
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
													</div> --}}
												<img class="img-fluid" src="{{ $prod->photo ? asset('assets/images/thumbnails/'.$prod->thumbnail):asset('assets/images/noimage.png') }}" alt="">
											</div>
											<div class="item-cart-area">
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

													{{-- <span  data-href="{{ route('product.cart.add',$prod->id) }}"> --}}
															@if (Auth::user() && Auth::user()->subscribes <> '' && Auth::user()->subscribes->is_dropship === '1')
																<img class="add-to-cart" data-href="{{ route('product.cart.add',$prod->id) }}" src="{{ asset('assets/images/icons/cart.png') }}" style="width: 28%; padding: 10px; margin-left: 20px; margin-top: 15px;" alt="" srcset="">
																<img class="add-to-wish" data-href="{{ route('user-wishlist-add',$prod->id) }}" src="{{ asset('assets/images/icons/wish.png') }}" style="width: 28%; padding: 10px; margin-right: 20px; margin-top: 15px; float:right;" alt="" srcset="">
																<br>
																<img class="add-to-compare" data-href="{{ route('product.compare.add',$prod->id) }}" src="{{ asset('assets/images/icons/compare.png') }}" style="width: 28%; padding: 10px; margin-left: 20px; margin-top: 20px;" alt="" srcset="">
																<img class="add-to-dropship" data-href="{{ route('vendor-dropship-store',$prod->id) }}" src="{{ asset('assets/images/sosial/white-handshake.png') }}" style="width: 35%; padding: 10px; float: right; margin-right: 20px; margin-top: 20px;" alt="" srcset="">
															@else
																<img class="add-to-cart" data-href="{{ route('product.cart.add',$prod->id) }}" src="{{ asset('assets/images/icons/cart.png') }}" style="width: 28%; padding: 10px; margin-left: 75px; margin-top: 15px;" alt="" srcset="">
																<br>
																<img class="add-to-wish" data-href="{{ route('user-wishlist-add',$prod->id) }}" src="{{ asset('assets/images/icons/wish.png') }}" style="width: 28%; padding: 10px; margin-left: 37px;" alt="" srcset="">
																<img class="add-to-compare" data-href="{{ route('product.compare.add',$prod->id) }}" src="{{ asset('assets/images/icons/compare.png') }}" style="width: 28%; padding: 10px; float:right; margin-right: 30px;" alt="" srcset="">
															@endif
													{{-- </span> --}}
													{{-- <span class="add-to-cart add-to-cart-btn" data-href="{{ route('product.cart.add',$prod->id) }}">
														<i class="icofont-cart"></i> {{ $langg->lang56 }}
													</span>
													<span class="add-to-cart-quick add-to-cart-btn"
														data-href="{{ route('product.cart.quickadd',$prod->id) }}">
														<i class="icofont-cart"></i> {{ $langg->lang251 }}
													</span> --}}
													@endif
												@endif
											</div>

											<div class="info">
												{{-- <div class="stars">
													<div class="ratings">
														<div class="empty-stars"></div>
														<div class="full-stars" style="width:{{App\Models\Rating::ratings($prod->id)}}%"></div>
													</div>
												</div> --}}
														{{-- <h4 class="price">{{ $prod->showPrice() }} <del><small>{{ $prod->showPreviousPrice() }}</small></del></h4> --}}
														<h5 class="name">{{ $prod->showName() }}</h5>
														<h4>
															<span class="price">{{ $prod->showPrice() }}</span>
														</h4>
														{{-- <h5 style="margin-top: -15px;"><span class="coin"> {{ $prod->showPrice() }}</span></h5> --}}
														<div class="stars">
															<div class="ratings">
																<div class="empty-stars"></div>
																<div class="full-stars" style="width:{{App\Models\Rating::ratings($prod->id)}}%"></div>
															</div>
														</div>													
														<div class="location">
															{{ @$prod->user->regency->name }}
															@if (Auth::guard('web')->check())
																@php
																	$wished = in_array($prod->id, Auth::user()->wishlists->pluck('product_id')->toArray()) ? 'red' : null ;
																@endphp																
																<span class="add-to-wish {{ $wished }}" data-href="{{ route('user-wishlist-add',$prod->id) }}" data-toggle="tooltip" data-placement="right" title="{{ $langg->lang54 }}" data-placement="right"><i class="icofont-heart-alt" ></i>
																</span>
															@else
																{{-- <a href="#" data-toggle="tooltip" data-placement="right" title="{{ $langg->lang54 }}" data-placement="right">
																	<i class="icofont-heart-alt" ></i>
																</a> --}}
																<span class="wish-login" data-toggle="tooltip" data-placement="right" title="{{ $langg->lang54 }}" data-placement="right">
																		<i class="icofont-heart-alt" ></i>
																</span>
															@endif
														</div>
											</div>
										</a>

									</div>
				@endforeach
				<div class="col-lg-12">
					<div class="page-center mt-5">
						{!! $prods->appends(['search' => request()->input('search')])->links() !!}
					</div>
				</div>
			@else
				<div class="col-lg-12">
					<div class="page-center">
						 <h4 class="text-center">{{ $langg->lang60 }}</h4>
					</div>
				</div>
			@endif


@if(isset($ajax_check))


<script type="text/javascript">


// Tooltip Section


    $('[data-toggle="tooltip"]').tooltip({
      });
      $('[data-toggle="tooltip"]').on('click',function(){
          $(this).tooltip('hide');
      });




      $('[rel-toggle="tooltip"]').tooltip();

      $('[rel-toggle="tooltip"]').on('click',function(){
          $(this).tooltip('hide');
      });


// Tooltip Section Ends

</script>

@endif