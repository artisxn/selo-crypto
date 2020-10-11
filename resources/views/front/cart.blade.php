@extends('layouts.front')
@section('content')
<!-- Breadcrumb Area Start -->
<!-- <div class="breadcrumb-area">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <ul class="pages">
          <li>
            <a href="{{ route('front.index') }}">
              {{ $langg->lang17 }}
            </a>
          </li>
          <li>
            <a href="{{ route('front.cart') }}">
              {{ $langg->lang121 }}
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div> -->
<!-- Breadcrumb Area End -->

@php
$total_edc = 0;
  @endphp


<!-- Cart Area Start -->
<section class="cartpage no-garis">
  <div class="container">
    <div class="row">
      <div class="col-lg-8">
        <p style="font-weight:bold;">Pilihan semua produk</p>
        <div class="left-area">
          <div class="cart-table no-garis">
            <table border="0" class="table  table-borderless no-garis">
              @include('includes.form-success')
                <thead class="no-garis" style="text-align:center;font-weight:normal;color:#FFFFFF!important;font-size:12px!important;background-color:#A5A5A5!important;">
                    <tr>
                      <th width="20%">Produk</th>
                      <th style="text-align:left;"width="30%">{{ $langg->lang122 }}</th>
                      <!-- <th width="30%">{{ $langg->lang539 }}</th> -->
                      
                      <th style="text-align:left!important;">Jumlah Produk</th>
                      <!-- <th>Subtotal Point</th> -->
                      <th>{{ $langg->lang126 }}</th>
                    </tr>
                  </thead>
                  <tbody class="no-garis" style="font-size:12px!important;text-align:center;">
                    @if(Session::has('cart'))
                    @foreach($products as $product)
                    <tr class="cremove{{ $product['item']['id'].$product['size'].$product['color'].str_replace(str_split(' ,'),'',$product['values']) }} no-garis" style="background-color:#F6F6F6;margin-top:100px!important;">
                      <td class="product-img no-garis">
                        <div class="item">
                          <img src="{{ $product['item']['photo'] ? asset('assets/images/products/'.$product['item']['photo']):asset('assets/images/noimage.png') }}" alt="">
                        </div>
                        @if ( config('dropship.is_dropship') !== TRUE )
                          <p style="color:#212121;text-align:left!important;margin-top:-10px!important;margin-left:5px!important;">{{$product['toko_name']}}    
                        @endif
                        
                          

                          <span style="color:#A5A5A5!important;font-size:10px!important;font-weight:normal!important;margin-top:0px!important;display:block;">

                          @php
                            $kota = DB::table('users')
                            ->where('users.id', '=', $product['item']['user_id'])
                            ->leftJoin('regencies', 'users.city_id', '=', 'regencies.id')
                            ->first();
                          @endphp
                          {{$kota->name}}

                          </span> 
                          
                          </p>
                      </td>
                      <td style="vertical-align: top;text-align:left!important;">
                        <p class="name" style="font-color:#000;font-weight:bold"><a href="{{ route('front.product', $product['item']['slug']) }}">{{strlen($product['item']['name']) > 20 ? substr($product['item']['name'],0,20).'...' : $product['item']['name']}}</a></p>
                        <p></p>
                      </td>
                                            <!-- <td>
                                                @if(!empty($product['size']))
                                                <b>{{ $langg->lang312 }}</b>: {{ $product['item']['measure'] }}{{$product['size']}} <br>
                                                @endif
                                                @if(!empty($product['color']))
                                                <div class="d-flex mt-2">
                                                <b>{{ $langg->lang313 }}</b>:  <span id="color-bar" style="border: 10px solid #{{$product['color'] == "" ? "white" : $product['color']}};"></span>
                                                </div>
                                                @endif

                                                    @if(!empty($product['keys']))

                                                    @foreach( array_combine(explode(',', $product['keys']), explode(',', $product['values']))  as $key => $value)

                                                        <b>{{ ucwords(str_replace('_', ' ', $key))  }} : </b> {{ $value }} <br>
                                                    @endforeach

                                                    @endif

                                                  </td>
 -->



                      <td class="unit-price quantity" style="vertical-align: top;">
                        <!-- <p class="product-unit-price">
                          {{ App\Models\Product::convertPrice($product['item']['price']) }}                        
                        </p> -->
          @if($product['item']['type'] == 'Physical')

                          <div class="qty" style="text-align:center;">
                              <ul>
              <input type="hidden" class="prodid" value="{{$product['item']['id']}}">  
              <input type="hidden" class="itemid" value="{{$product['item']['id'].$product['size'].$product['color'].str_replace(str_split(' ,'),'',$product['values'])}}">     
              <input type="hidden" class="size_qty" value="{{$product['size_qty']}}">     
              <input type="hidden" class="size_price" value="{{$product['item']['price']}}">   
                                <li>
                                  <span class="qtminus1 reducing">
                                    <i class="icofont-minus"></i>
                                  </span>
                                </li>
                                <li>
                                  <span class="qttotal1" id="qty{{$product['item']['id'].$product['size'].$product['color'].str_replace(str_split(' ,'),'',$product['values'])}}">{{ $product['qty'] }}</span>
                                </li>
                                <li>
                                  <span class="qtplus1 adding">
                                    <i class="icofont-plus"></i>
                                  </span>
                                </li>
                              </ul>
                          </div>
        @endif
        

                      </td>
        
                      <!-- <td style="vertical-align: top;color:#EF4623;font-weight:bold;font-size:15px;"> 
                        
                          <span class="subtotal-price-edc{{$product['item']['id'].$product['size'].$product['color'].str_replace(str_split(' ,'),'',$product['values'])}}">
                            {{ App\Models\Product::showCoin2($product['price_edc']) }} 
                          </span>
                          @php
                          $b= App\Models\Product::showCoin2($product['price_edc']) ;
                          $total_edc = $total_edc + $b;
                          
                          if (Auth::guard('web')->check()) {
                            $wished = in_array($product['item']['id'], Auth::user()->wishlists->pluck('product_id')->toArray()) ? 'red' : null ;
                          }else{
                            $wished = null;
                          }
                          @endphp
                          EDC
                        <p style="color:#A5A5A5!important;font-size:13px;text-align:center;font-weight:normal!important;">setara dengan <br>{{ App\Models\Product::convertPrice($product['price_edc']) }}    </p>
                      </td> -->

                     
                            @if($product['size_qty'])
                            <input type="hidden" id="stock{{$product['item']['id'].$product['size'].$product['color'].str_replace(str_split(' ,'),'',$product['values'])}}" value="{{$product['size_qty']}}">
                            @elseif($product['item']['type'] != 'Physical') 
                            <input type="hidden" id="stock{{$product['item']['id'].$product['size'].$product['color'].str_replace(str_split(' ,'),'',$product['values'])}}" value="1">
                            @else
                            <input type="hidden" id="stock{{$product['item']['id'].$product['size'].$product['color'].str_replace(str_split(' ,'),'',$product['values'])}}" value="{{$product['stock']}}">
                            @endif
<!-- edit disini -->
                      <td class="total-price" style="vertical-align: top;">
                        <p id="prc{{$product['item']['id'].$product['size'].$product['color'].str_replace(str_split(' ,'),'',$product['values'])}}" style="color:#FCB415!important;text-align:center;font-weight:bold;">
                          @php
                            $idLama = $product['item']['id'];
                            $hargaLama = DB::table('products')
                            ->where('id', '=', $idLama)
                            ->first();
                          @endphp
                          {{ __('Rp ') }} @rp($product['price'])
                        </p>
                        <!-- <p style="color:#A5A5A5!important;font-size:13px;text-align:center;font-weight:normal!important;">lebih untung <br> pakai coin!</p> -->
                        <br>
                          <i class="far fa-heart add-to-wish {{ $wished }}" data-href="{{ route('user-wishlist-add',$product['item']['id']) }}" style="cursor: pointer;font-size:30px;margin-right:10px; {{ $wished == '' ? 'color:#A5A5A5' : null }}"></i>
                          <span class="removecart cart-remove" data-class="cremove{{ $product['item']['id'].$product['size'].$product['color'].str_replace(str_split(' ,'),'',$product['values']) }}" data-href="{{ route('product.cart.remove',$product['item']['id'].$product['size'].$product['color'].str_replace(str_split(' ,'),'',$product['values'])) }}"><i class="icofont-ui-delete" style="font-size:30px;color:#A5A5A5;"></i> </span>
                      </td>
                      
                    </tr>
                    @endforeach
                    @endif
                  </tbody>
            </table>
          </div>
        </div>
      </div>

      
      @if(Session::has('cart'))
      <div class="col-lg-4">
      <p>Ringkasan Belanja <span style="font-weight:bold;float:right">{{count($products)}} Produk</span></p>
        <table border="0" class="table  table-borderless no-garis">
          <thead class="no-garis" style="text-align:left;font-weight:normal;color:#FFFFFF!important;font-size:12px!important;background-color:#A5A5A5!important;">
            <tr>
              <th width="45%">Detail Harga</th>
              <td></td>
            </tr>
            
            <tbody class="no-garis" style="font-size:15px!important;background-color:#F6F6F6;text-align:right;">
              <tr>
                <!-- <td>
                <b class="total-price-edc" style="color:#EF4623;"> 
                {{ $total_edc . ' EDC' }}                  </b>
                </td> -->
                <td style="color:#A5A5A5;text-align:left;">Subtotal</td>
                <td>
                  <b class="cart-total" style="color:#212121;">{{ Session::has('cart') ? App\Models\Product::convertPrice($totalPrice) : '0.00' }}</b>
                </td>
              </tr>
              <tr>
                <!-- <td>
                <b class=""> 

                
                -</b>
                </td> -->
                <td style="color:#A5A5A5;text-align:left;">Potongan</td>
                <td>
                <b class="discount">{{ App\Models\Product::convertPrice(0)}}</b>
               
                  <input type="hidden" id="d-val" value="{{ App\Models\Product::convertPrice(0)}}">
                </td>
              </tr>
              <tr style="margin-bottom:100px;">
                <td colspan="3">
                  <hr>
                </td>
              </tr>
              <tr>
                <!-- <td>
                <b class="total-price-edc" style="color:#EF4623!important;"> 
                {{$total_edc.' EDC'}}</b>
                </td> -->
                <td style="color:#A5A5A5;text-align:left;">Total</td>
                <td>
                <!-- <b class="discount">{{ App\Models\Product::convertPrice(0)}}</b> -->
                <span class="main-total" style="color:#EF4623;font-weight:bold;">{{ Session::has('cart') ? App\Models\Product::convertPrice($totalPrice) : '0.00' }}</span>

                </td>
              </tr>
              <tr height="40">
              <td colspan="3" style="padding:0px!important;">
              </td>
              </tr>
              
              <tr>
                <td colspan="3" style="padding:0px!important;">
                <a href="{{ route('front.checkout') }}" class="btn btn-danger btn-sm btn-block" style="border-radius:0px;background-color:#EF4623!important;">
                  {{ $langg->lang135 }}
                </a>
                </td>
              </tr>
              <tr>
                <td colspan="3">
                  <form id="coupon-form" class="coupon">
                    <input type="text" placeholder="{{ $langg->lang133 }}" id="code" required="" autocomplete="off" class="form-control" style="border-radius:10px;">
                    <input type="hidden" class="coupon-total" id="grandtotal" value="{{ Session::has('cart') ? App\Models\Product::convertPrice($mainTotal) : '0.00' }}">
                    <!-- <button type="submit">{{ $langg->lang134 }}</button> -->
                  </form>
                </td>
              </tr>
            </tbody>
          </thead>
        </table>
        <!-- <div class="right-area">
          <div class="order-box">
            <h4 class="title">{{ $langg->lang127 }}</h4>
            <ul class="order-list">
              <li>
                <p>
                  {{ $langg->lang128 }}
                </p>
                <P>
                  <b class="cart-total">{{ Session::has('cart') ? App\Models\Product::convertPrice($totalPrice) : '0.00' }}</b>
                </P>
              </li>
              <li>
                <p>
                  {{ $langg->lang129 }}
                </p>
                <P>
                  <b class="discount">{{ App\Models\Product::convertPrice(0)}}</b>
                  <input type="hidden" id="d-val" value="{{ App\Models\Product::convertPrice(0)}}">
                </P>
              </li>
              <li>
                <p>
                  {{ $langg->lang130 }}
                </p>
                <P>
                  <b>{{$tx}}%</b>
                </P>
              </li>
            </ul>
            <div class="total-price">
              <p>
                  {{ $langg->lang131 }}
              </p>
              <p>
                  <span class="main-total">{{ Session::has('cart') ? App\Models\Product::convertPrice($mainTotal) : '0.00' }}</span>
              </p>
            </div>
            <div class="cupon-box">
              <div id="coupon-link">
                  {{ $langg->lang132 }}
              </div>
              <form id="coupon-form" class="coupon">
                <input type="text" placeholder="{{ $langg->lang133 }}" id="code" required="" autocomplete="off">
                <input type="hidden" class="coupon-total" id="grandtotal" value="{{ Session::has('cart') ? App\Models\Product::convertPrice($mainTotal) : '0.00' }}">
                <button type="submit">{{ $langg->lang134 }}</button>
              </form>
            </div>
            <a href="{{ route('front.checkout') }}" class="order-btn">
              {{ $langg->lang135 }}
            </a>
          </div>
        </div> -->
      </div>
      @endif
    </div>
  </div>



  <section class="sub-categori">
   <div class="container">
      
      <div class="row">
         <div class="col-lg-12 order-first order-lg-last">
            <h5>Mungkin <span style="color:#EF4623;">Kamu</span> Juga <span style="color:#EF4623;">Suka</span></h5>
            <div class="right-area" id="app">

               <!-- @include('includes.filter') -->
               <div class="categori-item-area">
                 <div class="row">
                 @foreach (isset($prods) ? $prods->shuffle()->take(6) : [] as $key => $prod)
									<div class="col-lg-2 col-md-4 col-6 padding-8">


										<a href="{{ route('front.product', $prod->slug) }}" class="item">
											<div class="item-img">
												{{-- @if(!empty($prod->features))
													<div class="sell-area">
													  @foreach($prod->features as $key => $data1)
														<span class="sale" style="background-color:{{ $prod->colors[$key] }}">{{ $prod->features[$key] }}</span>
														@endforeach
													</div>
												@endif --}}
												@if ($prod->is_highlight === '1')
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
													</div>
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

													<span class="add-to-cart" data-href="{{ route('product.cart.add',$prod->id) }}">
															<img src="{{ asset('assets/images/icons/cart.png') }}" style="width: 28%; padding: 10px;" alt="" srcset="">
															<br>
															<img src="{{ asset('assets/images/icons/wish.png') }}" style="width: 28%; padding: 10px;" alt="" srcset="">
															<br>
															<img src="{{ asset('assets/images/icons/compare.png') }}" style="width: 28%; padding: 10px;" alt="" srcset="">
													</span>
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
														<h4><span class="price">{{ $prod->showCoin() .' Coin' }}</span></h4>
														<h5 style="margin-top: -15px;"><span class="coin"> {{ $prod->showPrice() }}</span></h5>
														<div class="stars">
															<div class="ratings">
																<div class="empty-stars"></div>
																<div class="full-stars" style="width:{{App\Models\Rating::ratings($prod->id)}}%"></div>
															</div>
														</div>													
														<div class="location">
															Bandung
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
  
                 </div>
                 <div id="ajaxLoader" class="ajax-loader" style="background: url({{asset('assets/images/'.$gs->loader)}}) no-repeat scroll center center rgba(0,0,0,.6);"></div>
               </div>

            </div>
         </div>
      </div>


      <div class="row">
         <div class="col-lg-12 order-first order-lg-last">
            <h5>Kamu <span style="color:#EF4623;">Terakhir Lihat</span></h5>
            <div class="right-area" id="app">

               <!-- @include('includes.filter') -->
               <div class="categori-item-area">
                 <div class="row">
                 @foreach (isset($prods) ? $prods->shuffle()->take(6) : [] as $key => $prod)
									<div class="col-lg-2 col-md-4 col-6 padding-8">


										<a href="{{ route('front.product', $prod->slug) }}" class="item">
											<div class="item-img">
												{{-- @if(!empty($prod->features))
													<div class="sell-area">
													  @foreach($prod->features as $key => $data1)
														<span class="sale" style="background-color:{{ $prod->colors[$key] }}">{{ $prod->features[$key] }}</span>
														@endforeach
													</div>
												@endif --}}
												@if ($prod->is_highlight === '1')
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
													</div>
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

													<span class="add-to-cart" data-href="{{ route('product.cart.add',$prod->id) }}">
															<img src="{{ asset('assets/images/icons/cart.png') }}" style="width: 28%; padding: 10px;" alt="" srcset="">
															<br>
															<img src="{{ asset('assets/images/icons/wish.png') }}" style="width: 28%; padding: 10px;" alt="" srcset="">
															<br>
															<img src="{{ asset('assets/images/icons/compare.png') }}" style="width: 28%; padding: 10px;" alt="" srcset="">
													</span>
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
														<h4><span class="price">{{ $prod->showCoin() .' Coin' }}</span></h4>
														<h5 style="margin-top: -15px;"><span class="coin"> {{ $prod->showPrice() }}</span></h5>
														<div class="stars">
															<div class="ratings">
																<div class="empty-stars"></div>
																<div class="full-stars" style="width:{{App\Models\Rating::ratings($prod->id)}}%"></div>
															</div>
														</div>													
														<div class="location">
															Bandung
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
  
                 </div>
                 <div id="ajaxLoader" class="ajax-loader" style="background: url({{asset('assets/images/'.$gs->loader)}}) no-repeat scroll center center rgba(0,0,0,.6);"></div>
               </div>

            </div>
         </div>
      </div>
   </div>
</section>
<!-- Cart Area End -->
@endsection 