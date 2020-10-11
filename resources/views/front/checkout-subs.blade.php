@extends('layouts.front')

@section('styles')

<style type="text/css">
	.root.root--in-iframe {
		background: #4682b447 !important;
	}

	a[disabled] {
    	pointer-events: none;
	}

	div::-webkit-scrollbar {
		width: 10px;
	}

	div::-webkit-scrollbar-thumb {
		background: grey;
	}
</style>

@endsection



@section('content')


<!-- Breadcrumb Area Start -->
<div class="breadcrumb-area">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<ul class="pages">
					{{-- <li>
						<a href="{{ route('front.index') }}">
							{{ $langg->lang17 }}
						</a>
					</li>
					<li>
						<a href="{{ route('front.checkout') }}">
							{{ $langg->lang136 }}
						</a>
					</li> --}}
					<li class="">
						<a class="" id="pills-step1-tab" data-toggle="pill" href="#pills-step1"
							role="tab" aria-controls="pills-step1" aria-selected="true">
							{{ $langg->lang743 }}
						</a>
					</li>
					<li class="">
						<a class=" {{ $tab_active }}" id="pills-step2-tab" data-toggle="pill"
							href="#pills-step2" role="tab" aria-controls="pills-step2" aria-selected="false">
							 {{ $langg->lang744 }}
						</a>
					</li>
					<li class="">
						<a class=" {{ $tab_active }}" id="pills-step3-tab" data-toggle="pill"
							href="#pills-step3" role="tab" aria-controls="pills-step3" aria-selected="false">
							{{ __('Cek Pesanan') }}
						</a>
					</li>					
				</ul>
			</div>
		</div>
	</div>
</div>
<!-- Breadcrumb Area End -->
<!-- Check Out Area Start -->
<section class="checkout no-garis">
	<div class="container">
		<div class="row">
			{{-- <div class="col-lg-12">
				<div class="checkout-area mb-0 pb-0">
					<div class="checkout-process">
						<ul class="nav" role="tablist">
							<li class="nav-item">
								<a class="nav-link active" id="pills-step1-tab" data-toggle="pill" href="#pills-step1"
									role="tab" aria-controls="pills-step1" aria-selected="true">
									<span>1</span> {{ $langg->lang743 }}
									<i class="far fa-address-card"></i>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link {{ $tab_active }}" id="pills-step2-tab" data-toggle="pill"
									href="#pills-step2" role="tab" aria-controls="pills-step2" aria-selected="false">
									<span>2</span> {{ $langg->lang744 }}
									<i class="fas fa-dolly"></i>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link {{ $tab_active }}" id="pills-step3-tab" data-toggle="pill"
									href="#pills-step3" role="tab" aria-controls="pills-step3" aria-selected="false">
									<span>3</span> {{ __('Cek Pesanan') }}
									<i class="far fa-list-alt"></i>
								</a>
							</li>
							@if ($tab_active === 'active')
							<li class="nav-item">
								<a class="nav-link {{ $tab_active }}" id="pills-step4-tab" data-toggle="pill"
									href="#pills-step4" role="tab" aria-controls="pills-step4" aria-selected="false">
									<span>4</span> Selesaikan
									<i class="far fa-credit-card"></i>
								</a>
							</li>
							@endif
						</ul>
					</div>
				</div>
			</div> --}}


			<div class="col-lg-8">




				<form id="" action="" method="POST" class="checkoutform">

					@include('includes.form-success')
					@include('includes.form-error')

					{{ csrf_field() }}

					@php
						$pillsActive = $tab_active === 'disabled' ? 'show active' : null;
					@endphp

					<div class="checkout-area">
						<div class="tab-content" id="pills-tabContent">
							<div class="tab-pane fade {{ $pillsActive }}" id="pills-step1" role="tabpanel"
								aria-labelledby="pills-step1-tab">
								<div class="content-box">

									<div class="content">
										<div class="personal-info">
											<h5 class="title">
												{{ $langg->lang746 }} :
											</h5>
											<div class="row">
												<div class="col-lg-6">
													<input type="text" id="personal-name" class="form-control"
														name="personal_name" placeholder="{{ $langg->lang747 }}"
														value="{{ Auth::check() ? Auth::user()->name : '' }}" {!!
														Auth::check() ? 'readonly' : '' !!}>
												</div>
												<div class="col-lg-6">
													<input type="email" id="personal-email" class="form-control"
														name="personal_email" placeholder="{{ $langg->lang748 }}"
														value="{{ Auth::check() ? Auth::user()->email : '' }}" {!!
														Auth::check() ? 'readonly' : '' !!}>
												</div>
											</div>
											@if(!Auth::check())
											<div class="row">
												<div class="col-lg-12 mt-3">
													<input class="styled-checkbox" id="open-pass" type="checkbox"
														value="1" name="pass_check">
													<label for="open-pass">{{ $langg->lang749 }}</label>
												</div>
											</div>
											<div class="row set-account-pass d-none">
												<div class="col-lg-6">
													<input type="password" name="personal_pass" id="personal-pass"
														class="form-control" placeholder="{{ $langg->lang750 }}">
												</div>
												<div class="col-lg-6">
													<input type="password" name="personal_confirm"
														id="personal-pass-confirm" class="form-control"
														placeholder="{{ $langg->lang751 }}">
												</div>
											</div>
											@endif
										</div>
										<div class="billing-address">
											<h5 class="title">
												{{ $langg->lang752 }}
											</h5>
											<div class="row">
												<div class="col-lg-12 {{ $digital == 1 ? 'd-none' : '' }}">
													<select class="form-control shipping" name="shipping"
														required="">
														<option value="shipto" selected>{{ $langg->lang149." Utama" }} - {{ $alamat }}</option>
															@if ($alamatLainnya <> [])
																@foreach ($alamatLainnya as $key => $item)
																<option value="{{ $key }}" data-nama="{{ $item['nama'] }}" data-phone="{{ $item['phone'] }}" data-kota="{{ $item['kota'] }}">{{ $item['alamat'] }}</option>
																@endforeach
															@endif															
														<option value="another">{{ __('Tambah Alamat Lainnya') }}</option>
													</select>
												</div>

												{{-- <div class="col-lg-6 d-none" id="shipshow">
													<select class="form-control nice" name="pickup_location">
														@foreach($pickups as $pickup)
														<option value="{{$pickup->location}}">{{$pickup->location}}
														</option>
														@endforeach
													</select>
												</div>

												<div class="col-lg-6">
													<input class="form-control" type="text" name="name"
														placeholder="{{ $langg->lang152 }}" required=""
														value="{{ Auth::guard('web')->check() ? Auth::guard('web')->user()->name : '' }}" 
														{!! Auth::check() ? 'readonly' : '' !!}>
												</div>
												<div class="col-lg-6">
													<input class="form-control" type="text" name="phone"
														placeholder="{{ $langg->lang153 }}" required=""
														value="{{ Auth::guard('web')->check() ? Auth::guard('web')->user()->phone : '' }}"
														{!! Auth::check() ? 'readonly' : '' !!}
														>
												</div>
												<div class="col-lg-6">
													<input class="form-control" type="text" name="email"
														placeholder="{{ $langg->lang154 }}" required=""
														value="{{ Auth::guard('web')->check() ? Auth::guard('web')->user()->email : '' }}"
														{!! Auth::check() ? 'readonly' : '' !!}>
												</div>
												<div class="col-lg-6">
													<input class="form-control" type="text" name="provinces"
														placeholder="Provinsi"
														required=""
														value="{{ Auth::guard('web')->check() ? @App\Models\Provinces::where('id', Auth::guard('web')->user()->provinces)->first()->name : '' }}"
														{!! Auth::check() ? 'readonly' : '' !!}>
												</div>
												<div class="col-lg-6">
													<input class="form-control" type="text" name="regencies"
														placeholder="Kota / Kabupaten" required=""
														value="{{ Auth::guard('web')->check() ? @App\Models\Regencies::where('id', Auth::guard('web')->user()->city_id)->first()->name : '' }}"
														{!! Auth::check() ? 'readonly' : '' !!}>
												</div>
												<div class="col-lg-6">
													<input class="form-control" type="text" name="districts"
														placeholder="Kecamatan" required=""
														value="{{ Auth::guard('web')->check() ? @App\Models\Districts::where('id', Auth::guard('web')->user()->districts)->first()->name : '' }}"
														{!! Auth::check() ? 'readonly' : '' !!}>
												</div>				
												<div class="col-lg-6">
													<input class="form-control" type="text" name="zip"
														placeholder="Kode Pos" required=""
														value="{{ Auth::guard('web')->check() ? Auth::guard('web')->user()->zip : '' }}" {!! Auth::check() ? 'readonly' : '' !!}>
												</div>																																												
												<div class="col-lg-12">
													<input class="form-control" type="text" name="address"
														placeholder="{{ $langg->lang155 }}" required=""
														value="{{ Auth::guard('web')->check() ? Auth::guard('web')->user()->address : '' }}" {!! Auth::check() ? 'readonly' : '' !!}>
												</div> --}}
												{{-- <div class="col-lg-6">
													<select class="form-control" name="customer_country" required="">
														@include('includes.countries')
													</select>
												</div>
												<div class="col-lg-6">
													<input class="form-control" type="text" id="city_input" name="city"
														placeholder="{{ $langg->lang158 }}" required=""
														value="{{ Auth::guard('web')->check() ? Auth::guard('web')->user()->city : '' }}">
												</div> --}}
											</div>
										</div>
										{{-- <div class="row {{ $digital == 1 || @Auth::user()->subscribes->is_dropship !== '1' ? 'd-none' : '' }}">
											<div class="col-lg-12 mt-3">
												<input class="styled-checkbox" id="ship-diff-address" name="dropship" type="checkbox"
													value="1">
												<label for="ship-diff-address">{{ __('Kirim Sebagai Dropship') }}</label>
											</div>
										</div>										 --}}
										<div class="ship-diff-addres-area d-none">
											<h5 class="title">
												{{ $langg->lang752 }}
											</h5>
											<div class="another-address">
											
												<div class="row">
													<div class="col-lg-6 {{ $digital == 1 ? 'd-none' : '' }}">
														<select class="form-control shipping" 
															required="">
															<option value="shipto">{{ $langg->lang149 }} - {{ $alamat }}</option>
															@if ($alamatLainnya <> [])
																@foreach ($alamatLainnya as $key => $item)
																<option value="{{ $key }}" data-nama="{{ $item['nama'] }}" data-phone="{{ $item['phone'] }}" data-kota="{{ $item['kota'] }}">{{ $item['alamat'] }}</option>
																@endforeach
															@endif	
															<option value="another" selected>{{ __('Tambah Alamat Lainnya') }}</option>
														</select>
													</div>													
													<div class="col-lg-6">
														<input class="form-control ship_input" type="text"
															name="shipping_name" id="shippingFull_name"
															placeholder="{{ __('Label Alamat')  }}">
													</div>												
													{{-- <div class="col-lg-6">
														<input class="form-control ship_input" type="text"
															name="shipping_phone" id="shipingPhone_number"
															placeholder="{{ $langg->lang153 }}">
													</div> --}}
												</div>
												<div class="row">
													<div class="col-lg-6">
														<input class="form-control ship_input" type="text"
															name="receiver_name" id="shippingFull_name"
															placeholder="{{ __('Nama Penerima') }}">
													</div>													
													<div class="col-lg-6">
														<input class="form-control ship_input" type="number"
															name="shipping_phone" id="shipingPhone_number"
															placeholder="{{ $langg->lang153 }}">
													</div>
													
													{{-- <div class="col-lg-6">
														<input class="form-control ship_input" type="email"
															name="shipping_email" id="shiping_email"
															placeholder="{{ $langg->lang748 }}">
													</div>												 --}}
												</div>
											
											{{-- <div class="row">
												<div class="col-lg-6">
													<input class="form-control ship_input" type="text"
														name="shipping_city" id="shipping_city"
														placeholder="{{ $langg->lang158 }}">
												</div>
												<div class="col-lg-6">
													<input class="form-control ship_input" type="text"
														name="shipping_zip" id="shippingPostal_code"
														placeholder="{{ $langg->lang159 }}">
												</div>

											</div> --}}
												<div class="row">
													<div class="col-lg-6">
														<select class="form-control" name="shipping_provinces">
															<option value="">Pilih Provinsi</option>
															@foreach ($provinces as $data)
																<option value="{{ $data->id }}">
																	{{ $data->name }}
																</option>		
															@endforeach
														</select>
													</div>

													<div class="col-lg-6">
														<select class="form-control" name="shipping_city">
															<option value="">Pilih Kota / Kabupaten</option>
														</select>
													</div>
		
												</div>
												<div class="row" style="margin-top: 12px;">
													<div class="col-lg-6">
														<select class="form-control" name="shipping_districts">
															<option value="">Pilih Kecamatan</option>
														</select>
													</div>
		
													<div class="col-lg-6">
															<select class="form-control" name="shipping_zip">
																<option value="">Pilih Kode Pos</option>
															</select>                                                    
													</div>
		
												</div>
												
												<div class="row" style="margin-top: 12px;">
													<div class="col-lg-12">
														<input class="form-control ship_input" type="text"
															name="shipping_address" id="shipping_address"
															placeholder="{{ $langg->lang155 }}">
													</div>			
												</div>	

											</div>
											<button type="button" class="mybtn1 mybtn1-first btn-alamat">{{ __('Simpan Alamat') }}</button>							
										</div>
										<div class="order-note mt-3">
											<div class="row">
												<div class="col-lg-12">
													<input type="text" id="Order_Note" class="form-control"
														name="order_notes"
														placeholder="{{ $langg->lang217 }} ({{ $langg->lang218 }})">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-lg-12  mt-3">
												<div class="bottom-area paystack-area-btn">
													@if ( @Auth::user()->city_id <> '' )
														<button type="submit" class="mybtn1 mybtn1-first">{{ $langg->lang753 }}</button>
													@else
														<button type="button" onclick="location.href='/user/profile'" class="mybtn1 mybtn1-first">Lengkapi Alamat</button>
													@endif
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="pills-step2" role="tabpanel"
								aria-labelledby="pills-step2-tab">
								<div class="content-box">
									<div class="content">

										<div class="order-area">
                                            <?php 
                                                $pertoko = array(); 
                                                $perDropship = array();
                                                $perTokoDropship = array();
                                            ?>
											@foreach($products as $element)
                                            <?php
                                                    if ($element['is_dropship'] === FALSE) {
                                                        $pertoko[$element['slug_toko']][] = $element;
                                                    }else{
                                                        $perDropship[$element['slug_toko']][] = $element;
                                                        $perTokoDropship[$element['vendor_slug_shop_name']][] = $element;
                                                    }
													
												?>
											@endforeach
											

                                            
                                            
                                            @foreach ($pertoko as $i => $value)
                                            @php
                                                $user = App\Models\User::where('slug_shop_name', $i)->firstOrFail();
                                            @endphp
											{{-- <a href="{{url('store/'.$i)}}">
												<h3><i class="fas fa-store"></i> {{strtoupper($i)}}</h3>
											</a> --}}
											<?php
													$berat_total_barang_per_toko = 0;
													$harga_total_barang_per_toko = 0;
												?>
												<div class="area-{{$i}}">									
													@foreach($value as $product)
													<div class="order-item">
															<div class="product-img">
																<div class="d-flex">
																	<img src=" {{ asset('assets/images/products/'.$product['item']['photo']) }}"
																		height="80" width="80" class="p-1">
																</div>
															</div>
															<div class="product-content">
																
																<p class="name"><a
																		href="{{ route('front.product', $product['item']['slug']) }}"
																		target="_blank">{{ $product['item']['name'] }}</a></p>
																
																
																@if(!empty($product['size']))
																<div class="unit-price">
																	<h5 class="label">{{ $langg->lang312 }} : </h5>
																	<p>{{ $product['size'] }}</p>
																</div>
																@endif
																@if(!empty($product['color']))
																<div class="unit-price">
																	<h5 class="label">{{ $langg->lang313 }} : </h5>
																	<span id="color-bar"
																		style="border: 10px solid {{$product['color'] == "" ? "white" : '#'.$product['color']}};"></span>
																</div>
																@endif
																@if(!empty($product['keys']))
			
																@foreach( array_combine(explode(',', $product['keys']), explode(',',
																$product['values'])) as $key => $value)
			
																<div class="quantity">
																	<h5 class="label">{{ ucwords(str_replace('_', ' ', $key))  }} :
																	</h5>
																	<span class="qttotal">{{ $value }} </span>
																</div>
																@endforeach
			
																@endif
																<div class="quantity">
																	<h5 class="label">{{ $langg->lang755 }} : </h5>
																	<span class="qttotal">{{ $product['qty'] }} </span>
																</div>
																<div class="quantity">
																	<h5 class="label">Berat : </h5>
																	<span class="qttotal">{{ $product['weight'] }}g</span>
																</div>
																<div class="unit-price">
																	<h5 class="label">{{ $langg->lang754 }} : </h5>
																	<p>
																		<span>
																		{{ App\Models\Product::convertPrice($product['item']['publish_price']) }}
																		
																		
																	</p>
																</div>	
																<div class="total-price">
																	<h5 class="label">Total : </h5>
																	<p>
																		<span>
																		{{ App\Models\Product::convertPrice($product['price']) }}
																	</p>
																</div>
																<?php 
																				// $harga_total_barang_per_toko += $product['price'] * $product['qty'];
																				// $edc_total_barang_per_toko += $product['price_edc'] * $product['qty'];
																				
																				$harga_total_barang_per_toko = $product['price'];
																				$edc_total_barang_per_toko = $product['price_edc'];
																				
																				$berat_total_barang_per_toko += $product['qty'] * $product['weight'];
																			?>
															</div>
			
															<!-- edc -->
															
															<!-- akhir edc -->
															
														</div>
													@endforeach
												</div>
											<h4>Pilih jasa pengiriman anda </h4>
											<dl class="row">
												<dt class="col-sm-3">Harga Total</dt>
												<dd class="col-sm-9">
													{{ App\Models\Product::convertPrice($harga_total_barang_per_toko) }}
												</dd>
												<dt class="col-sm-3">Berat Total</dt>
												<dd class="col-sm-9"><?php echo $berat_total_barang_per_toko; ?>g(Gram)
                                                </dd>
                                                
                                                <dt class="col-sm-3">Kota Pengirim</dt>
												<dd class="col-sm-9"><?php echo $user->regency->name; ?>
												</dd>
											</dl>
											<div class="row">
												<div class="col">
													<select id="{{$i}}_kurir" toko="{{$i}}"
														class="form-control kurir-pengiriman">
														<option value="default-kurir">Pilih kurir pengiriman</option>
														@foreach($shipping_data as $data)
														<option value="{{$data->title}}">{{strtoupper($data->title)}}
														</option>
														@endforeach
													</select>
												</div>
												<div class="col">
													<select class="form-control paket-pengiriman" toko="{{$i}}"
														id="{{$i}}_paket_kurir" placeholder="Paket pengiriman" disabled>
														<option>Pilih paket kurir pengiriman</option>
													</select>
												</div>
											</div>
											<br>
											<div class="row">
												<div class="col alert-error-kurir alert-error-kurir-{{$i}}">
													<small class="alert alert-danger"><strong>!</strong> Nama kota
														tujuan anda salah atau kurang tepat, harap periksa
														kembali</small>
												</div>
												<div class="col alert-loading-kurir alert-loading-kurir-{{$i}}">
													<small class="alert alert-primary"> Mohon tunggu, data sedang di
														proses...</small>
												</div>
											</div>
											<br>
											<input class="form-check-input checkbox-toko" type="checkbox"
												harga-total="{{$harga_total_barang_per_toko}}"
												berat-total="{{$berat_total_barang_per_toko}}" ongkir-total=""
												toko="{{$i}}" id="checkbox{{$i}}" disabled>
											<label class="form-check-label" for="checkbox{{$i}}">
												Klik untuk checkout barang?
											</label>
											<?php echo "<hr style='border-top: 1px dashed black; margin: 20px 0'>"; ?>
                                            @endforeach
                                            
                                            @if (count($perTokoDropship) > 0)
                                            @foreach ($perTokoDropship as $i => $value)
                                            @php
                                                $user = App\Models\User::where('slug_shop_name', $i)->firstOrFail();
                                            @endphp
											{{-- <a href="{{url('store/'.$i)}}">
												<h3><i class="fas fa-store"></i> {{strtoupper($i)}}</h3>
											</a> --}}
											<?php
													$berat_total_barang_per_toko = 0;
													$harga_total_barang_per_toko = 0;
												?>
											<div class="area-{{$i}}">
											@foreach($value as $product)
											@foreach($value as $product)
											<div class="order-item">
												<div class="product-img">
													<div class="d-flex">
														<img src=" {{ asset('assets/images/products/'.$product['item']['photo']) }}"
															height="80" width="80" class="p-1">
													</div>
												</div>
												<div class="product-content">
													
													<p class="name"><a
															href="{{ route('front.product', $product['item']['slug']) }}"
															target="_blank">{{ $product['item']['name'] }}</a></p>
													
													
													@if(!empty($product['size']))
													<div class="unit-price">
														<h5 class="label">{{ $langg->lang312 }} : </h5>
														<p>{{ $product['size'] }}</p>
													</div>
													@endif
													@if(!empty($product['color']))
													<div class="unit-price">
														<h5 class="label">{{ $langg->lang313 }} : </h5>
														<span id="color-bar"
															style="border: 10px solid {{$product['color'] == "" ? "white" : '#'.$product['color']}};"></span>
													</div>
													@endif
													@if(!empty($product['keys']))

													@foreach( array_combine(explode(',', $product['keys']), explode(',',
													$product['values'])) as $key => $value)

													<div class="quantity">
														<h5 class="label">{{ ucwords(str_replace('_', ' ', $key))  }} :
														</h5>
														<span class="qttotal">{{ $value }} </span>
													</div>
													@endforeach

													@endif
													<div class="quantity">
														<h5 class="label">{{ $langg->lang755 }} : </h5>
														<span class="qttotal">{{ $product['qty'] }} </span>
													</div>
													<div class="quantity">
														<h5 class="label">Berat : </h5>
														<span class="qttotal">{{ $product['weight'] }}g</span>
													</div>
													<div class="unit-price">
														<h5 class="label">{{ $langg->lang754 }} : </h5>
														<p>
															<span>
															{{ App\Models\Product::convertPrice($product['item']['publish_price']) }}
															
															
														</p>
													</div>	
													<div class="total-price">
														<h5 class="label">Total : </h5>
														<p>
															<span>
															{{ App\Models\Product::convertPrice($product['price']) }}
														</p>
													</div>
													<?php 
																	// $harga_total_barang_per_toko += $product['price'] * $product['qty'];
																	// $edc_total_barang_per_toko += $product['price_edc'] * $product['qty'];
																	
																	$harga_total_barang_per_toko = $product['price'];
																	$edc_total_barang_per_toko = $product['price_edc'];
																	
																	$berat_total_barang_per_toko += $product['qty'] * $product['weight'];
																?>
												</div>

												<!-- edc -->
												
												<!-- akhir edc -->
												
											</div>
										@endforeach
											@endforeach
											</div>
											<h4>Pilih jasa pengiriman anda </h4>
											<dl class="row">
													<dt class="col-sm-3">Harga Total</dt>
													<dd class="col-sm-9">
														
														<span>
														{{ App\Models\Product::convertPrice($harga_total_barang_per_toko) }}
													</dd>												
												<dt class="col-sm-3">Berat Total</dt>
												<dd class="col-sm-9"><?php echo $berat_total_barang_per_toko; ?>g(Gram)
                                                </dd>
                                                <dt class="col-sm-3">Kota Pengirim</dt>
												<dd class="col-sm-9"><?php echo $user->regency->name; ?>
												</dd>
											</dl>
											<div class="row">
												<div class="col">
													<select id="{{$i}}_kurir" toko="{{$i}}"
														class="form-control kurir-pengiriman">
														<option value="default-kurir">Pilih kurir pengiriman</option>
														@foreach($shipping_data as $data)
														<option value="{{$data->title}}">{{strtoupper($data->title)}}
														</option>
														@endforeach
													</select>
												</div>
												<div class="col">
													<select class="form-control paket-pengiriman" toko="{{$i}}"
														id="{{$i}}_paket_kurir" placeholder="Paket pengiriman" disabled>
														<option>Pilih paket kurir pengiriman</option>
													</select>
												</div>
											</div>
											<br>
											<div class="row">
												<div class="col alert-error-kurir alert-error-kurir-{{$i}}">
													<small class="alert alert-danger"><strong>!</strong> Nama kota
														tujuan anda salah atau kurang tepat, harap periksa
														kembali</small>
												</div>
												<div class="col alert-loading-kurir alert-loading-kurir-{{$i}}">
													<small class="alert alert-primary"> Mohon tunggu, data sedang di
														proses...</small>
												</div>
											</div>
											<br>
											<input class="form-check-input checkbox-toko" type="checkbox"
												harga-total="{{$harga_total_barang_per_toko}}"
												harga-total-edc ="{{$edc_total_barang_per_toko}}"
												berat-total="{{$berat_total_barang_per_toko}}" ongkir-total=""
												toko="{{$i}}" id="checkbox{{$i}}" disabled>
											<label class="form-check-label" for="checkbox{{$i}}">
												Klik untuk checkout barang?
											</label>
											<?php echo "<hr style='border-top: 1px dashed black; margin: 20px 0'>"; ?>
											@endforeach                                                
                                            @endif

										</div>



										<div class="row">
											<div class="col-lg-12 mt-3">
												<div class="bottom-area">
													<a href="javascript:;" id="step1-btn"
														class="mybtn1 mr-3">{{ $langg->lang757 }}</a>
													<a href="javascript:;" id="step3-btn"
														class="mybtn1">{{ $langg->lang753 }}</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="tab-pane fade" id="pills-step3" role="tabpanel"
								aria-labelledby="pills-step3-tab">
								<div class="content-box">
									<div class="content">

										<div class="billing-info-area {{ $digital == 1 ? 'd-none' : '' }}">
											<h4 class="title">
												{{ $langg->lang758 }}
											</h4>
											<ul class="info-list">
												<li>
													<p id="shipping_user"></p>
												</li>
												<li>
													<p id="shipping_location"></p>
												</li>
												<li>
													<p id="shipping_phone"></p>
												</li>
												<li>
													<p id="shipping_email"></p>
												</li>
											</ul>
										</div>
										<div class="billing-info-area order-area">
											<h4 class="title">
												{{ __('Review Pembelian') }}
											</h4>
											<div class="orderan">

											</div>
											{{-- @foreach ($pertoko as $i => $value)
											@foreach($value as $product)
											<div class="order-item">
												<div class="product-img">
													<div class="d-flex">
														<img src=" {{ asset('assets/images/products/'.$product['item']['photo']) }}"
															height="80" width="80" class="p-1">
													</div>
												</div>
												<div class="product-content">
													<p class="name"><a
															href="{{ route('front.product', $product['item']['slug']) }}"
															target="_blank">{{ $product['item']['name'] }}</a></p>
													<div class="unit-price">
														<h5 class="label">{{ $langg->lang754 }} : </h5>
														<p>{{ App\Models\Product::convertPrice($product['item']['price']) }}
														</p>
													</div>
													@if(!empty($product['size']))
													<div class="unit-price">
														<h5 class="label">{{ $langg->lang312 }} : </h5>
														<p>{{ $product['size'] }}</p>
													</div>
													@endif
													@if(!empty($product['color']))
													<div class="unit-price">
														<h5 class="label">{{ $langg->lang313 }} : </h5>
														<span id="color-bar"
															style="border: 10px solid {{$product['color'] == "" ? "white" : '#'.$product['color']}};"></span>
													</div>
													@endif
													@if(!empty($product['keys']))

													@foreach( array_combine(explode(',', $product['keys']), explode(',',
													$product['values'])) as $key => $value)

													<div class="quantity">
														<h5 class="label">{{ ucwords(str_replace('_', ' ', $key))  }} :
														</h5>
														<span class="qttotal">{{ $value }} </span>
													</div>
													@endforeach

													@endif
													<div class="quantity">
														<h5 class="label">{{ $langg->lang755 }} : </h5>
														<span class="qttotal">{{ $product['qty'] }} </span>
													</div>
													<div class="quantity">
														<h5 class="label">Berat : </h5>
														<span class="qttotal">{{ $product['weight'] }}g</span>
													</div>
													<div class="total-price">
														<h5 class="label">Jumlah Harga : </h5>
														<p>{{ App\Models\Product::convertPrice($product['price']) }}
														</p>
													</div>
												</div>
											</div>
											@endforeach
											@endforeach --}}
										</div>										
										<div class="payment-information">
											<h4 class="title">
												{{ $langg->lang759 }}
											</h4>
											<div class="row">
												<div class="col-lg-12">
													<div class="nav flex-column" role="tablist"
														aria-orientation="vertical">
														<!-- 														
														<a class="nav-link payment" data-val="" data-show="yes" data-form="{{route('stripe.submit')}}" data-href="{{ route('front.load.payment',['slug1' => 'stripe','slug2' => 0]) }}" id="v-pills-tab2-tab" data-toggle="pill" href="#v-pills-tab2" role="tab" aria-controls="v-pills-tab2" aria-selected="false">
																<div class="icon">
																		<span class="radio"></span>
																</div>
																<p>
																EDC Cash

																	@if($gs->stripe_text != null)

																	<small>
																		Pay via your EDC Cash
																	</small>

																	@endif

																</p>
														</a> -->

														@if($digital == 0)

														@foreach($gateways as $gt)
														@php
															// $show = $gt->id == 48 ? 'no' : 'yes';
															$rand = mt_rand();
															$disable = '';
															$ins = '';
															if ($gt->title === 'EDCCASH') {
																

																// if (empty(Auth::user()->edccash_id) || Auth::user()->edccash_id == '') {
																	$disable = 'disabled';
																	$ins = ' - Coming Soon';
																// }
															}
														@endphp	
														<a class="nav-link payment klik" data-val="" data-payment="{{ $gt->title }}" data-show="no"
															data-form="{{route('gateway.submit')}}"
															data-href="{{ route('front.load.payment',['slug1' => 'other','slug2' => $gt->id]) }}"
															id="v-pills-tab{{ $gt->id }}-tab" data-toggle="pill"
															href="#v-pills-tab{{ $gt->id }}" role="tab"
															aria-controls="v-pills-tab{{ $gt->id }}"
															aria-selected="false" {!! $disable !!}>
															<div class="icon">
																<span class="radio"></span>
															</div>
															<p>
																{{ $gt->title }} {{ $ins }}

																@if($gt->subtitle != null)

																<small>
																	{{ $gt->subtitle }}
																</small>

																@endif

															</p>
														</a>



														@endforeach

														@endif

													</div>
												</div>
												<div class="col-lg-12">
													<div class="pay-area d-none">
														<div class="tab-content" id="v-pills-tabContent">															
															@if($gs->paypal_check == 1)
															<div class="tab-pane fade" id="v-pills-tab1" role="tabpanel"
																aria-labelledby="v-pills-tab1-tab">

															</div>
															@endif
															@if($gs->stripe_check == 1)
															<div class="tab-pane fade" id="v-pills-tab2" role="tabpanel"
																aria-labelledby="v-pills-tab2-tab">
															</div>
															@endif
															@if($gs->cod_check == 1)
															@if($digital == 0)
															<div class="tab-pane fade" id="v-pills-tab3" role="tabpanel"
																aria-labelledby="v-pills-tab3-tab">
															</div>
															@endif
															@endif
															@if($gs->is_instamojo == 1)
															<div class="tab-pane fade" id="v-pills-tab4" role="tabpanel"
																aria-labelledby="v-pills-tab4-tab">
															</div>
															@endif
															@if($gs->is_paytm == 1)
															<div class="tab-pane fade" id="v-pills-tab5" role="tabpanel"
																aria-labelledby="v-pills-tab5-tab">
															</div>
															@endif
															@if($gs->is_razorpay == 1)
															<div class="tab-pane fade" id="v-pills-tab6" role="tabpanel"
																aria-labelledby="v-pills-tab6-tab">
															</div>
															@endif
															@if($gs->is_paystack == 1)
															<div class="tab-pane fade" id="v-pills-tab7" role="tabpanel"
																aria-labelledby="v-pills-tab7-tab">
															</div>
															@endif
															@if($gs->is_molly == 1)
															<div class="tab-pane fade" id="v-pills-tab8" role="tabpanel"
																aria-labelledby="v-pills-tab8-tab">
															</div>
															@endif

															@if($digital == 0)
															@foreach($gateways as $gt)

															<div class="tab-pane fade" id="v-pills-tab{{ $gt->id }}"
																role="tabpanel"
																aria-labelledby="v-pills-tab{{ $gt->id }}-tab">

															</div>

															@endforeach
															@endif
														</div>
													</div>
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-lg-12 mt-3">
												<div class="bottom-area">

													<a href="javascript:;" id="step2-btn"
														class="mybtn1 mr-3">{{ $langg->lang757 }}</a>
													{{-- <button type="submit" id="final-btn"
														class="mybtn1">{{ $langg->lang753 }}</button> --}}
														<button type="button" id="btn-payment"
														class="mybtn1">{{ __('Pembayaran') }}</button>														
												</div>

											</div>
										</div>
									</div>
								</div>
							</div>
							@if ($tab_active === 'active')
							<div class="tab-pane fade show active" id="pills-step4" role="tabpanel"
								aria-labelledby="pills-step4-tab">
								<div class="content-box">
									<div class="content">
									<iframe src="{!! $urlGV."&bank=1" !!}" width="100%;" height="700"></iframe>

									<button type="button" style="margin-top: 15px;" onclick="location.href='{{ route('front.index') }}'" class="mybtn1">{{ $langg->lang753 }}</button></div>	
									
									
								</div>
							</div>								
							@endif
						</div>
					</div>


					<input type="hidden" id="kurir" name="kurir" value="">
					<input type="hidden" id="paket_kurir" name="paket_kurir" value="">
					<input type="hidden" id="shipping-cost" name="shipping_cost" value="0">
					<input type="hidden" id="packing-cost" name="packing_cost" value="0">
					<input type="hidden" name="dp" value="{{$digital}}">
					<input type="hidden" name="tax" value="{{$gs->tax}}">
					<input type="hidden" name="totalQty" value="{{$totalQty}}">
					<input type="hidden" name="gudang_voucher_reference" value="">
					<input type="hidden" name="edccash_number" value="">
					<input type="hidden" id="txn_id4" name="txn_id4" value="{{ mt_rand(5, 5000) }}">

					<input type="hidden" name="vendor_shipping_id" value="{{ $vendor_shipping_id }}">
					<input type="hidden" name="vendor_packing_id" value="{{ $vendor_packing_id }}">


					@if(Session::has('coupon_total'))
					<input type="hidden" name="total" id="grandtotal" value="{{ $totalPrice }}">
					<input type="hidden" id="tgrandtotal" value="{{ $totalPrice }}">
					@elseif(Session::has('coupon_total1'))
					<input type="hidden" name="total" id="grandtotal"
						value="{{ preg_replace("/[^0-9,.]/", "", Session::get('coupon_total1') ) }}">
					<input type="hidden" id="tgrandtotal"
						value="{{ preg_replace("/[^0-9,.]/", "", Session::get('coupon_total1') ) }}">
					@else
					<input type="hidden" name="total" id="grandtotal" value="{{round($totalPrice * $curr->value,2)}}">
					<input type="hidden" id="tgrandtotal" value="{{round($totalPrice * $curr->value,2)}}">
					@endif


					<input type="hidden" name="coupon_code" id="coupon_code"
						value="{{ Session::has('coupon_code') ? Session::get('coupon_code') : '' }}">
					<input type="hidden" name="coupon_discount" id="coupon_discount"
						value="{{ Session::has('coupon') ? Session::get('coupon') : '' }}">
					<input type="hidden" name="coupon_id" id="coupon_id"
						value="{{ Session::has('coupon') ? Session::get('coupon_id') : '' }}">
					<input type="hidden" name="user_id" id="user_id"
						value="{{ Auth::guard('web')->check() ? Auth::guard('web')->user()->id : '' }}">



				</form>

			</div>
			@if(Session::has('cart'))
			<div class="col-lg-4">
			
				<div class="right-area">
					<div class="order-box" style="background-color:#F6F6F6!important;padding-top:0px!important;">
						<h4 class="title" style="padding:5px 25px 5px!important;font-size:13px!important;color:#fff!important;background-color:#A5A5A5!important;margin-left:-26px!important;margin-right:-26px!important;">{{ $langg->lang127 }}</h4>
							<ul class="order-list no-garis">
								<!-- <li>
								<p>
									{{ $langg->lang128 }}
								</p>
								<P>
									<b
									class="cart-total">{{ Session::has('cart') ? App\Models\Product::convertPrice(Session::get('cart')->totalPrice) : '0.00' }}</b>
								</P>
								</li> -->

								<!-- @if($gs->tax != 0)

								<li>
									<p>
										{{ $langg->lang144 }}
									</p>
									<P>
										<b> {{$gs->tax}}% </b>

									</P>
								</li>

								@endif -->

								@if(Session::has('coupon'))


								<li class="discount-bar">
									<p>
										{{ $langg->lang145 }} <span
											class="dpercent">{{ Session::get('coupon_percentage') == 0 ? '' : '('.Session::get('coupon_percentage').')' }}</span>
									</p>
									<p>
										@if($gs->currency_format == 0)
										<b id="discount">{{ $curr->sign }}{{ Session::get('coupon') }}</b>
										@else
										<b id="discount">{{ $curr->sign }}{{ Session::get('coupon') }}</b>
										@endif
									</p>
								</li>

								@else

								<li class="discount-bar d-none">
									<p>
										{{ $langg->lang145 }} <span class="dpercent"></span>
									</p>
									<P>
										<b id="discount">{{ $curr->sign }}{{ Session::get('coupon') }}</b>
									</P>
								</li>

								@endif

							</ul>

							<div class="total-price">
							<!-- totaledc -->
								<!-- <p style="color:#EF4623!important;">
									<span id="total-edc">0 EDC</span>
								</p> -->
								<p style="color:#A5A5A5!important;">
									{{ $langg->lang131 }}
								</p>

								

								<p style="color:#212121!important;">

									@if ($tab_active === 'disabled')
										@if(Session::has('coupon_total'))
											@if($gs->currency_format == 0)
											<span id="total-cost">{{ $curr->sign }}{{ $totalPrice }}</span>
											@else
											<span id="total-cost">{{ $curr->sign }}{{ $totalPrice }}</span>
											@endif

										@elseif(Session::has('coupon_total1'))
										<span id="total-cost"> {{ Session::get('coupon_total1') }}</span>
										@else
										<span id="total-cost">Rp0</span>
										@endif
									@else
										<span id="total-cost">{{ App\Models\Product::convertPrice($total['totalOrder']) }}</span>
									@endif


								</p>
								
							</div>
							<!-- <div class="total-price price-coint">
						<p>
							Coint
						</p>
						<p>

							@if(Session::has('coupon_total'))
								@if($gs->currency_format == 0)
									<span id="total-cost">{{ $curr->sign }}{{ $totalPrice }}</span>
								@else 
									<span id="total-cost">{{ $curr->sign }}{{ $totalPrice }}</span>
								@endif

							@elseif(Session::has('coupon_total1'))
								<span id="total-cost"> {{ Session::get('coupon_total1') }}</span>
								@else
								<span id="total-coint">{{ App\Models\Product::convertPriceToCoint($totalPrice) }}</span>
							@endif

						</p>
						</div> -->
						@if($gs->tax != 0)

						


						<div class="total-price price-tax">
								<!-- ongkir_edc -->
								<!-- <p style="color:#EF4623!important;">
									<span id="ongkir-edc">0 EDC</span>
								</p> -->
								<p style="color:#A5A5A5!important;">
								{{ $langg->lang144 }} <span>{{$gs->tax}}%</span>
								</p>
								<p style="color:#212121!important;">
									<span id="pajaknya">
									Rp.0		
									</span>
									
								</p>
							</div>

						@endif

							
							<div class="total-price price-ongkir">
								<!-- ongkir_edc -->
								<!-- <p style="color:#EF4623!important;">
									<span id="ongkir-edc">0 EDC</span>
								</p> -->
								<p style="color:#A5A5A5!important;">
									Ongkos Kirim
								</p>
								<p style="color:#212121!important;">
									@if ($tab_active === 'disabled')
									<span id="total-ongkir" total-ongkir="">Rp 0</span>
									@else
									<span id="total-ongkir" total-ongkir="">{{ App\Models\Product::convertPrice($total['totalOngkir']) }}</span>
									@endif
								</p>
							</div>
							

							<?php $totalberat=0; ?>
							@if ($tab_active === 'disabled')
							<div class="total-price price-weight">
								<p style="color:#A5A5A5!important;">
									Berat Total
								</p>
								<p style="color:#212121important;">
									
									@foreach($products as $product)
									<?php $totalberat+=$product['weight']*$product['qty']; ?>
									@endforeach
									<span id="total-berat" total-berat="{{$totalberat}}">0g</span>
								</p>
							</div>
							@endif

						<!-- <div class="cupon-box">

							<div id="coupon-link">
							<img src="{{ asset('assets/front/images/tag.png') }}"> Have Coupon Code?
							</div>
							<form id="check-coupon-form" class="coupon">
								<input type="text" placeholder="{{ $langg->lang133 }}" id="code" required="" autocomplete="off">
								<button type="submit">{{ $langg->lang134 }}</button>
							</form>


						</div> -->

						@if($digital == 0)

						{{-- Shipping Method Area Start --}}
						<!-- <div class="packeging-area packeging-area-shipping">
								<h4 class="title">{{ $langg->lang765 }}</h4>
								<select name="select-kurir" id="select-kurir" class="form-control" onchange="checkkurir(this.val)" style="margin-bottom:10px">
									<option>Pilih kurir pengiriman</option>
									@foreach($shipping_data as $data)	
										<option value="{{$data->title}}">{{strtoupper($data->title)}}</option>
									@endforeach	
								</select>
								<small class="alert alert-danger alert-for-kurir" style="padding:5px"></small>
								<div id="shipping-type">
								</div>
							
						</div> -->
						{{-- Shipping Method Area End --}}

						{{-- Packeging Area Start --}}
						<!-- <div class="packeging-area">
								<h4 class="title">{{ $langg->lang766 }}</h4>
								
							@foreach($package_data as $data)	

								<div class="radio-design">
										<input type="radio" class="packing" id="free-package{{ $data->id }}" name="packeging" value="{{ round($data->price * $curr->value,2) }}" {{ ($loop->first) ? 'checked' : '' }}> 
										<span class="checkmark"></span>
										<label for="free-package{{ $data->id }}"> 
												{{ $data->title }}
												@if($data->price != 0)
												+ {{ $curr->sign }}{{ round($data->price * $curr->value,2) }}
												@endif
												<small>{{ $data->subtitle }}</small>
										</label>
								</div>

							@endforeach	

						</div> -->
						{{-- Packeging Area End Start--}}

						{{-- Final Price Area Start--}}
							<div class="final-price">
							<!-- ongkir_edc -->
								<!-- <p style="color:#EF4623!important;">
									<span id="total-tagihan-edc">0 EDC</span>
								</p> -->
								<span style="color:#A5A5A5!important;">Total Tagihan :</span>
								@if ($tab_active === 'disabled')
									<!-- @if(Session::has('coupon_total'))
										@if($gs->currency_format == 0)
											<span id="final-cost">{{ $curr->sign }}{{ $totalPrice }} h1</span>
										@else 
											<span id="final-cost"> {{ $totalPrice }}{{ $curr->sign }} h2</span>
										@endif

									@elseif(Session::has('coupon_total1'))
										<span id="final-cost">{{ Session::get('coupon_total1') }} h3</span>
										@else
										<span id="final-cost">{{ App\Models\Product::convertPrice($totalPrice) }}</span>
										<span id="final-cost">Rp0</span>
									@endif -->								
									<span id="total-tagihan" style="color:#212121!important;">Rp0</span>
								@else
									<span id="total-tagihan" style="color:#212121!important;">{{ App\Models\Product::convertPrice($total['totalBayar']) }}</span>
								@endif
								
								
							</div>
						{{-- Final Price Area End --}}

						{{-- Final Price with coint Area Start--}}
						<!-- <div class="final-price">
							<span>Final Price with Coint :</span>
						@if(Session::has('coupon_total'))
							@if($gs->currency_format == 0)
								<span id="final-cost">{{ $totalPrice }}{{ $curr->sign }}</span>
							@else 
								<span id="final-cost">{{ $curr->sign }}{{ $totalPrice }}</span>
							@endif

						@elseif(Session::has('coupon_total1'))
							<span id="final-cost">{{ Session::get('coupon_total1') }}</span>
							@else
							<span id="final-cost-coint">1000 Coints</span>
						@endif

						</div> -->
						{{-- Final Price with coint Area End --}}

						@endif

						{{-- 						<a href="{{ route('front.checkout') }}" class="order-btn mt-4">
						{{ $langg->lang135 }} h5
						</a> --}}
					</div>
				</div>

				
			</div>
			@endif
		</div>
	</div>
</section>
<!-- Check Out Area End-->

@if(isset($checked))

<!-- LOGIN MODAL -->
{{-- <div class="modal fade" id="comment-log-reg1" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog"
	aria-labelledby="comment-log-reg-Title" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" aria-label="Close">
					<a href="{{ url()->previous() }}"><span aria-hidden="true">&times;</span></a>
				</button>
			</div>
			<div class="modal-body">
				<nav class="comment-log-reg-tabmenu">
					<div class="nav nav-tabs" id="nav-tab" role="tablist">
						<a class="nav-item nav-link login active" id="nav-log-tab" data-toggle="tab" href="#nav-log"
							role="tab" aria-controls="nav-log" aria-selected="true">
							{{ $langg->lang197 }}
						</a>
						<a class="nav-item nav-link" id="nav-reg-tab" data-toggle="tab" href="#nav-reg" role="tab"
							aria-controls="nav-reg" aria-selected="false">
							{{ $langg->lang198 }}
						</a>
					</div>
				</nav>
				<div class="tab-content" id="nav-tabContent">
					<div class="tab-pane fade show active" id="nav-log" role="tabpanel" aria-labelledby="nav-log-tab">
						<div class="login-area">
							<div class="header-area">
								<h4 class="title">{{ $langg->lang172 }}</h4>
							</div>
							<div class="login-form signin-form">
								@include('includes.admin.form-login')
								<form id="loginform" action="{{ route('user.login.submit') }}" method="POST">
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
											<input type="hidden" name="modal" value="1">
											<input type="checkbox" name="remember" id="mrp"
												{{ old('remember') ? 'checked' : '' }}>
											<label for="mrp">{{ $langg->lang175 }}</label>
										</div>
										<div class="right">
											<a href="{{ route('user-forgot') }}">
												{{ $langg->lang176 }}
											</a>
										</div>
									</div>
									<input id="authdata" type="hidden" value="{{ $langg->lang177 }}">
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
					<div class="tab-pane fade" id="nav-reg" role="tabpanel" aria-labelledby="nav-reg-tab">
						<div class="login-area signup-area">
							<div class="header-area">
								<h4 class="title">{{ $langg->lang181 }}</h4>
							</div>
							<div class="login-form signup-form">
								@include('includes.admin.form-login')
								<form id="registerform" action="{{route('user-register-submit')}}" method="POST">
									{{ csrf_field() }}

									<div class="form-input">
										<input type="text" class="User Name" name="name"
											placeholder="{{ $langg->lang182 }}" required="">
										<i class="icofont-user-alt-5"></i>
									</div>

									<div class="form-input">
										<input type="email" class="User Name" name="email"
											placeholder="{{ $langg->lang183 }}" required="">
										<i class="icofont-email"></i>
									</div>

									<div class="form-input">
										<input type="text" class="User Name" name="phone"
											placeholder="{{ $langg->lang184 }}" required="">
										<i class="icofont-phone"></i>
									</div>

									<div class="form-input">
										<input type="text" class="User Name" name="address"
											placeholder="{{ $langg->lang185 }}" required="">
										<i class="icofont-location-pin"></i>
									</div>

									<div class="form-input">
										<input type="password" class="Password" name="password"
											placeholder="{{ $langg->lang186 }}" required="">
										<i class="icofont-ui-password"></i>
									</div>

									<div class="form-input">
										<input type="password" class="Password" name="password_confirmation"
											placeholder="{{ $langg->lang187 }}" required="">
										<i class="icofont-ui-password"></i>
									</div>

									@if($gs->is_capcha == 1)

									<ul class="captcha-area">
										<li>
											<p><img class="codeimg1" src="{{asset("assets/images/capcha_code.png")}}"
													alt=""> <i class="fas fa-sync-alt pointer refresh_code "></i></p>
										</li>
									</ul>

									<div class="form-input">
										<input type="text" class="Password" name="codes"
											placeholder="{{ $langg->lang51 }}" required="">
										<i class="icofont-refresh"></i>
									</div>

									@endif

									<input id="processdata" type="hidden" value="{{ $langg->lang188 }}">
									<button type="submit" class="submit-btn">{{ $langg->lang189 }}</button>

								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> --}}
<!-- LOGIN MODAL ENDS -->

@endif

<div class="modal fade" id="confirm-payment" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			{{-- <div class="submit-loader">
					<img src="{{asset('assets/images/'.$gs->admin_loader)}}" alt="">
		</div> --}}
			<div class="modal-header d-block text-center">
				<h4 class="modal-title d-inline-block">{{ __("Konfirmasi Pembayaran") }}</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<!-- Modal body -->
			<div class="modal-body">
			</div>

			<!-- Modal footer -->
			<div class="modal-footer justify-content-center">
				<button type="button" class="mybtn1" data-dismiss="modal">{{ $langg->lang547 }}</button>
				<a href="javascript:;" class="mybtn1" id="btn-pay">{{ __("Bayar") }}</a>
			</div>

		</div>
	</div>
</div>

@endsection

@section('scripts')

<script src="https://js.paystack.co/v1/inline.js"></script>

<script type="text/javascript">
	$('a.payment:first').addClass('active');
	$('.checkoutform').prop('action',$('a.payment:first').data('form'));
	$($('a.payment:first').attr('href')).load($('a.payment:first').data('href'));


		var show = $('a.payment:first').data('show');
		if(show != 'no') {
			$('.pay-area').removeClass('d-none');
		}
		else {
			$('.pay-area').addClass('d-none');
		}
	$($('a.payment:first').attr('href')).addClass('active').addClass('show');
</script>


<script type="text/javascript">
	var coup = 0;
var pos = {{ $gs->currency_format }};

@if(isset($checked))

	$('#comment-log-reg').modal('show');

@endif

var mship = $('.shipping').length > 0 ? $('.shipping').first().val() : 0;
var mpack = $('.packing').length > 0 ? $('.packing').first().val() : 0;
mship = parseFloat(mship);
mpack = parseFloat(mpack);

$('#shipping-cost').val(mship);
$('#packing-cost').val(mpack);
var ftotal = parseFloat($('#grandtotal').val()) + mship + mpack;
var fcoint = Math.ceil(ftotal/88); //Rp88/1EDC
ftotal = parseFloat(ftotal);
      if(ftotal % 1 != 0)
      {
        ftotal = ftotal.toFixed(2);
      }
		if(pos == 0){
			$('#final-cost').html('{{ $curr->sign }}'+ftotal)
			$('#final-cost-coint').html(fcoint+'Coints')
		}
		else{
			$('#final-cost').html('{{ $curr->sign }}'+ftotal)
			$('#final-cost-coint').html(fcoint+' Coints')
		}

$('#grandtotal').val(ftotal);

$('select.shipping').on('change',function(){

var val = $(this).val();

if(val == 'shipto'){
	$("#ship-diff-address").parent().addClass('d-none');
	$('.ship-diff-addres-area').addClass('d-none');  
	$('.ship-diff-addres-area input, .ship-diff-addres-area select').prop('required',false);  
	$('.billing-address').removeClass('d-none');
	$('select.shipping').first().val('shipto').change();	
}else if(val == 'another'){
	$('.billing-address').addClass('d-none');
	$("#ship-diff-address").parent().removeClass('d-none');
	$('.ship-diff-addres-area').removeClass('d-none');  
	$('.ship-diff-addres-area input, .ship-diff-addres-area select').prop('required',true); 
	$('select.shipping').last().val('another').change();	
}
else{
	$("#ship-diff-address").parent().addClass('d-none');
	$('.ship-diff-addres-area').addClass('d-none');  
	$('.ship-diff-addres-area input, .ship-diff-addres-area select').prop('required',false);  
	$('.billing-address').removeClass('d-none');
	$('select.shipping').first().val(val).change();
}

});
$(document).on('click', ".shipping", function(){
	mship = $(this).val();
	paket_kurir = $(this).attr('paket_kurir');
	console.log(mship);
	$('#paket_kurir').val(paket_kurir);
	$('#shipping-cost').val(mship);
	var ttotal = parseFloat($('#tgrandtotal').val()) + parseFloat(mship) + parseFloat(mpack);
	var tcoint = Math.ceil(ttotal/88); //Rp88/1EDC
	ttotal = parseFloat(ttotal);
		if(ttotal % 1 != 0)
		{
			ttotal = ttotal.toFixed(2);
		}
			if(pos == 0){
				$('#final-cost').html('{{ $curr->sign }}'+ttotal);
				$('#final-cost-coint').html(tcoint+' Coints');
			}
			else{
				$('#final-cost').html('{{ $curr->sign }}'+ttotal);
				$('#final-cost-coint').html(tcoint+' Coints');
			}
		
	$('#grandtotal').val(ttotal);

})

$('.packing').on('click',function(){
	mpack = $(this).val();
	$('#packing-cost').val(mpack);
	var ttotal = parseFloat($('#tgrandtotal').val()) + parseFloat(mship) + parseFloat(mpack);
	var tcoint = Math.ceil(ttotal/88); //Rp88/1EDC
	ttotal = parseFloat(ttotal);
		if(ttotal % 1 != 0)
		{
			ttotal = ttotal.toFixed(2);
		}
			if(pos == 0){
				$('#final-cost').html('{{ $curr->sign }}'+ttotal);
				$('#final-cost-coint').html(tcoint+' Coints');
			}
			else{
				$('#final-cost').html('{{ $curr->sign }}'+ttotal);
				$('#final-cost-coint').html(tcoint+' Coints');
			}	


	$('#grandtotal').val(ttotal);
		
})

    $("#check-coupon-form").on('submit', function () {
        var val = $("#code").val();
        var total = $("#grandtotal").val();
        var ship = 0;
            $.ajax({
                    type: "GET",
                    url:mainurl+"/carts/coupon/check",
                    data:{code:val, total:total, shipping_cost:ship},
                    success:function(data){
                        if(data == 0)
                        {
                        	toastr.error(langg.no_coupon);
                            $("#code").val("");
                        }
                        else if(data == 2)
                        {
                        	toastr.error(langg.already_coupon);
                            $("#code").val("");
                        }
                        else
                        {
                            $("#check-coupon-form").toggle();
                            $(".discount-bar").removeClass('d-none');

							if(pos == 0){
								$('#total-cost').html('{{ $curr->sign }}'+data[0]);
								$('#discount').html('{{ $curr->sign }}'+data[2]);
							}
							else{
								$('#total-cost').html(data[0]+'{{ $curr->sign }}');
								$('#discount').html(data[2]+'{{ $curr->sign }}');
							}
								$('#grandtotal').val(data[0]);
								$('#tgrandtotal').val(data[0]);
								$('#coupon_code').val(data[1]);
								$('#coupon_discount').val(data[2]);
								if(data[4] != 0){
								$('.dpercent').html('('+data[4]+')');
								}
								else{
								$('.dpercent').html('');									
								}


			var ttotal = parseFloat($('#grandtotal').val()) + parseFloat(mship) + parseFloat(mpack);
			ttotal = parseFloat(ttotal);
			if(ttotal % 1 != 0)
			{
				ttotal = ttotal.toFixed(2);
			}

			if(pos == 0){
				$('#final-cost').html('{{ $curr->sign }}'+ttotal)
			}
			else{
				$('#final-cost').html(ttotal+'{{ $curr->sign }}')
			}	

						toastr.success(langg.coupon_found);
						$("#code").val("");
					}
				}
			}); 
		return false;
    });
	$('.alert-for-kurir').hide();
	//NEW CHECK ONGKIR HERE !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	function checkkurir(){
		var id = $('#select-kurir').val();
		var kota = $('#city_input').val();
		var berat = {{ isset($totalberat) ? $totalberat : 0 }};
		
		if(!kota){
			$('.radion-for-shipping').remove();
			$('#kurir').val();
			return false;
		}
		$('#kurir').val(id);
		$.ajax({
			method: 'post',
			data: {id: id, berat: berat, kota: kota,  _token: '{{csrf_token()}}'},
			url:mainurl+"/checkout/check-kurir",
			success:function(data){
				//console.log(data);
				if(data == "gagal"){
					$('.alert-for-kurir').html("Nama kota tujuan tidak ditemukan !");
					$('.alert-for-kurir').show();
					$('.radion-for-shipping').remove();
					return false;
				}
				$('.alert-for-kurir').hide();
				$('.radion-for-shipping').remove();
				var data = JSON.parse(data);
				var datakurir = data.rajaongkir.results;
				var cost = datakurir[0].costs;
				//console.log(cost);
				var num = 1;
				cost.forEach(element => {
                    $('#shipping-type').append("<div class='radio-design radion-for-shipping'><input type='radio' class='shipping' id='free-shepping"+num+"' paket_kurir='"+element.description+"' name='shipping' value='"+element.cost[0].value+"'><span class='checkmark'></span><label for='free-shepping"+num+"'>"+element.description+"<small>"+element.cost[0].value+"</small></label></div>");	
					num++;
				});											
			}
		});
	}
	$('.alert-error-kurir').hide();
	$('.alert-loading-kurir').hide();
	$('.kurir-pengiriman').on('change', function() {

		var kurir = $(this).val();
		var kota = $('input[name="regencies"]').val();

		
		if ( $('select[name="shipping"]').first().val() == 'shipto' ) {
			var kota = '{{ Auth::user() ? $kota : null }}';
		}else if ( $('select[name="shipping"]').first().val() == 'another' ) {
			var kota = $('select[name="shipping_city"] option:selected').last().text();
		}else{
			var kota = $('select[name="shipping"] option:selected').first().data('kota');
		}

		//var berat = {{$totalberat}};
		var toko = $(this).attr('toko');
		$('.alert-loading-kurir-'+toko).show();
		var berat = $('#checkbox'+toko).attr('berat-total');
		var select_toko = document.getElementById(toko+'_paket_kurir');
		$('#'+toko+'_paket_kurir').prop('disabled', true);
		$('#checkbox'+toko).prop('disabled', true);
		document.getElementById('checkbox'+toko).checked = false;
		reset_harga();
		for (var i=0; i<select_toko.length; i++) {
			select_toko.remove(i);
		}
		$('.option-pengiriman-'+toko).remove();
		$.ajax({
			method: 'post',
			data: {id: kurir, berat: berat, kota: kota, toko: toko, _token: '{{csrf_token()}}'},
			url:mainurl+"/checkout/check-kurir",
			success:function(data){
				if(data == 'gagal'){
					$('.alert-error-kurir-'+toko).show();
					$('.alert-loading-kurir').hide();
					return false;
				}
				$('.alert-error-kurir-'+toko).hide();
				$('.alert-loading-kurir-'+toko).hide();
				var data = JSON.parse(data);
				var datakurir = data.rajaongkir.results;
				var cost = datakurir[0].costs;
				//console.log(cost);
				var num = 1;
				$('#'+toko+'_paket_kurir').prop('disabled', false);
				$('#'+toko+'_paket_kurir').append("<option class='option-pengiriman-"+toko+"'>Pilih paket kurir pengiriman</option>");
				var price_format = 0;
				cost.forEach(element => {
					price_format = convert_price(element.cost[0].value);
                    $('#'+toko+'_paket_kurir').append("<option class='option-pengiriman-"+toko+"' paket_kurir='"+element.description+"' value='"+element.cost[0].value+"'>"+element.description+" Rp"+price_format+"</option>");	
					num++;
				});											
			}
		});
	});

	$(document).on('change', '.paket-pengiriman', function() {
		var toko = $(this).attr('toko');
		var check_value = $(this).val();
		document.getElementById('checkbox'+toko).checked = false;
		reset_harga();
		if(isNaN(check_value)){
			$('#checkbox'+toko).prop('disabled', true);
			return false;
		}else{
			$('#checkbox'+toko).prop('disabled', false);
		}	
	});
	$('#step3-btn').hide();
	$('#step1-btn').click(function(){
		$('.checkbox-toko').prop('disabled', true);
		$('.checkbox-toko').prop('checked',false);
		$(".kurir-pengiriman").selectedIndex = 'default-kurir';
		reset_harga();
	})
	function reset_harga(){
		var harga_total = 0;
		var harga_total_edc = 0;
		var ongkir_edc = 0;


		var harga_per_toko = 0;
		var berat_total = 0;
		var ongkir_total = 0;
		var nama_toko = '';
		var length_checkbox = $('.checkbox-toko');
		var get_ongkir = 0;
		var get_kurir = '';
		$('.check_toko_vendor').remove();
		$('.ongkir_toko_vendor').remove();
		
		for (var i=0; i<length_checkbox.length; i++) {       
           if (length_checkbox[i].type == "checkbox" && length_checkbox[i].checked == true){
              count++;
			  harga_per_toko = Number($(length_checkbox[i]).attr('harga-total'));
			  nama_toko = $(length_checkbox[i]).attr('toko');
			  get_ongkir = $('#'+nama_toko+'_paket_kurir').val();
			  get_kurir = $('#'+nama_toko+'_kurir').val();
			  get_paket_kurir = $('option:selected', '#'+nama_toko+'_paket_kurir').attr('paket_kurir');
			  $('.checkoutform').append('<input type="hidden" class="check_toko_vendor" name="toko_vendor[]" value="'+nama_toko+'">');
			  $('.checkoutform').append('<input type="hidden" class="ongkir_toko_vendor" name="ongkir[]" value="'+nama_toko+','+get_kurir+', '+get_paket_kurir+', '+get_ongkir+'">');
			  harga_total += Number($(length_checkbox[i]).attr('harga-total'));
			  harga_total_edc += Number($(length_checkbox[i]).attr('harga-total'));
			  berat_total += Number($(length_checkbox[i]).attr('berat-total'));
			  ongkir_total += Number(get_ongkir);
           }
        }
		$('#total-berat').html(berat_total+"g");
		var price_format = convert_price(harga_total);
		$('#total-cost').html("Rp"+price_format);

		var edc_format = convert_edc(harga_total_edc);
		$('#total-edc').html(edc_format );

		var edc_ongkir = convert_edc(ongkir_total);
		$('#total-edc').html(edc_ongkir);

		var ongkir_format = convert_price(ongkir_total);
		$('#total-ongkir').html("Rp"+edc_ongkir);
		$('#ongkir-edc').html(edc_ongkir + " EDC");
		
		var total_tagihan = harga_total + ongkir_total;
		if(total_tagihan == 0){
			$('#step3-btn').hide();
		}else{
			$('#step3-btn').show();
		}
		total_tagihan = convert_price(total_tagihan);
		$('#total-tagihan').html("Rp"+total_tagihan);
	}

	var review_produk = [];
	$('.checkbox-toko').on('change', function() {
		var harga_total = 0;
		var harga_total_edc = 0;
		var harga_per_toko = 0;
		var berat_total = 0;
		var ongkir_total = 0;
		var nama_toko = '';
		var length_checkbox = $('.checkbox-toko');
		var get_ongkir = 0;
		var get_kurir = '';
		$('.check_toko_vendor').remove();
		$('.ongkir_toko_vendor').remove();

		
		for (var i=0; i<length_checkbox.length; i++) {       
           if (length_checkbox[i].type == "checkbox" && length_checkbox[i].checked == true){
			  count++;
			  harga_per_toko = Number($(length_checkbox[i]).attr('harga-total'));
			  nama_toko = $(length_checkbox[i]).attr('toko');
			  get_ongkir = $('#'+nama_toko+'_paket_kurir').val();
			  get_kurir = $('#'+nama_toko+'_kurir').val();
			  get_paket_kurir = $('option:selected', '#'+nama_toko+'_paket_kurir').attr('paket_kurir');
			  $('.checkoutform').append('<input type="hidden" class="check_toko_vendor" name="toko_vendor[]" value="'+nama_toko+'">');
			  $('.checkoutform').append('<input type="hidden" class="ongkir_toko_vendor" name="ongkir[]" value="'+nama_toko+','+get_kurir+', '+get_paket_kurir+', '+get_ongkir+'">');
			  harga_total += Number($(length_checkbox[i]).attr('harga-total'));
			  harga_total_edc += Number($(length_checkbox[i]).attr('harga-total-edc'));

			  berat_total += Number($(length_checkbox[i]).attr('berat-total'));
			  ongkir_total += Number(get_ongkir);

			  review_produk[i] = $(this).prevAll('.area-'+nama_toko).html();
           }else if(length_checkbox[i].checked == false){
			  review_produk[i] = '';
		   }
		   
		}
		
		var pajak = {{$gs->tax}};
		var pajaknya = harga_total*pajak /100;
		$( ".klik" ).click(function() {
			var payType = $(this).data('payment');

			// alert(payType);
			if (payType=='Bank Transfer') {
				var price_format = convert_price(harga_total);
				$('#total-cost').html("Rp" +price_format);
				var ongkir_format = convert_price(ongkir_total);
				$('#total-ongkir').html("Rp "+ongkir_format);
				total_tagihan = convert_price(harga_total + ongkir_total+pajaknya);
				$('#total-tagihan').html("Rp "+total_tagihan);
				var format_pajak = convert_price(pajaknya);
				$('#pajaknya').html("Rp "+format_pajak).css({"color":"#212121"});
		
			} else if(payType=='EDCCASH'){
				var edc_format = convert_edc(harga_total);
				$('#total-cost').html(edc_format+" EDC").css({"color":"#212121"});
				var edc_format = convert_edc(ongkir_total);
				$('#total-ongkir').html(edc_format+" EDC").css({"color":"#212121"});
				var edc_format = convert_edc(harga_total + ongkir_total+pajaknya);
				$('#total-tagihan').html(edc_format+" EDC").css({"color":"#EF4623"});

				var format_pajak = convert_edc(pajaknya);
				$('#pajaknya').html(format_pajak+" EDC");

				}
		});

		var format_pajak = convert_price(pajaknya);
		$('#pajaknya').html("Rp "+format_pajak);

		

		
		$('#total-berat').html(berat_total+"g");
		var price_format = convert_price(harga_total);
		$('#total-cost').html("Rp"+price_format);
		
		var edc_format = convert_edc(harga_total_edc);
		$('#total-edc').html(edc_format+" EDC");
		var ongkir_edc = convert_edc(ongkir_total);
		$('#ongkir-edc').html(ongkir_edc+" EDC");

		var total_semua_edc = edc_format+ongkir_edc;
		$('#total-tagihan-edc').html(total_semua_edc.toFixed(3)+" EDC");

		// console.log(total_semua_edc);
		
		var ongkir_format = convert_price(ongkir_total);
		$('#total-ongkir').html("Rp"+ongkir_format);
		var total_tagihan = harga_total + ongkir_total+pajaknya;
		if(total_tagihan == 0){
			$('#step3-btn').hide();
		}else{
			$('#step3-btn').show();
		}
		total_tagihan = convert_price(total_tagihan);
		$('#total-tagihan').html("Rp"+total_tagihan);
		
	});


	function convert_price(price)
	{
		var	number_string = price.toString(),
		sisa 	= number_string.length % 3,
		data 	= number_string.substr(0, sisa),
		ribuan 	= number_string.substr(sisa).match(/\d{3}/g);
			
		if (ribuan) {
		separator = sisa ? '.' : '';
		data += separator + ribuan.join('.');
		}           
		return data;
	}


	function convert_edc(price){
		var kon = {{ $gs->edccash_currency }};
		var tot = price /kon;
		var tot = parseFloat(tot.toFixed(3))
		return tot;
	}
	//NEW FUNCTION CHECK ONGKIR HERE

// Password Checking
        $("#open-pass").on( "change", function() {
            if(this.checked){
             $('.set-account-pass').removeClass('d-none');  
             $('.set-account-pass input').prop('required',true); 
             $('#personal-email').prop('required',true);
             $('#personal-name').prop('required',true);
            }
            else{
             $('.set-account-pass').addClass('d-none');   
             $('.set-account-pass input').prop('required',false); 
             $('#personal-email').prop('required',false);
             $('#personal-name').prop('required',false);
            }
        });
// Password Checking Ends

// Shipping Address Checking
		$("#ship-diff-address").on( "change", function() {
            if(this.checked){
             $('.ship-diff-addres-area').removeClass('d-none');  
             $('.ship-diff-addres-area input, .ship-diff-addres-area select').prop('required',true); 
            }
            else{
             $('.ship-diff-addres-area').addClass('d-none');  
             $('.ship-diff-addres-area input, .ship-diff-addres-area select').prop('required',false);  
            }
            
        });
// Shipping Address Checking Ends
</script>

<script type="text/javascript">

	$('select[name="shipping_provinces"]').on('change', function(e) {
			var kota = [];
			if ($(this).val() !== '') {
				$('#preloader').show();
				$.getJSON('{{ route("front.city")}}', {province : $(this).val()},
					function (response, textStatus, jqXHR) {
						data = response.data;
						$('select[name="shipping_city"]').empty();
						$('select[name="shipping_city"]').append('<option value="">Pilih Kota / Kabupaten</option>');
						if (data.length !== 0) {
							$.each(data, function (i, v) { 
								var option = '<option value="'+v.id+'">'+v.name+'</option>';

								kota[i] = option;

							});
							$('select[name="shipping_city"]').append(kota);
						}
					}
				);
				$('#preloader').hide();
			}
	});

	$('select[name="shipping_city"]').on('change', function(e) {
			var kecamatan = [];
			if ($(this).val() !== '') {
				$('#preloader').show();
				$.getJSON('{{ route("front.district")}}', {city : $(this).val()},
					function (response, textStatus, jqXHR) {
						data = response.data;
						$('select[name="shipping_districts"]').empty();
						$('select[name="shipping_districts"]').append('<option value="">Pilih Kecamatan</option>');
						if (data.length !== 0) {
							$.each(data, function (i, v) { 
								var option = '<option value="'+v.id+'">'+v.name+'</option>';

								kecamatan[i] = option;

							});
							$('select[name="shipping_districts"]').append(kecamatan);
						}
					}
				);
				$('#preloader').hide();
			}
	});    

	$('select[name="shipping_districts"]').on('change', function(e) {
			var zip = [];
			if ($(this).val() !== '') {
				kota = $('select[name="shipping_city"] option:selected').text().replace('KABUPATEN', '').replace('KOTA', '');
				$.post('{{ route("front.zip")}}', {
						city : kota, 
						district: $('select[name="shipping_districts"] option:selected').text(),
						province: $('select[name="shipping_provinces"] option:selected').val(),
						_token: '{!! csrf_token() !!}',
					},
					function (response, textStatus, jqXHR) {
						data = response.data;
						$('select[name="shipping_zip"]').empty();
						$('select[name="shipping_zip"]').append('<option value="">Pilih Kode Pos</option>');
						if (data.length !== 0) {
							$.each(data, function (i, v) { 
								var option = '<option value="'+v.postal_code+'">'+v.postal_code+'</option>';

								zip[i] = option;

							});
							$('select[name="shipping_zip"]').append(zip);
						}
					}
				);
				$('#preloader').hide();
			}
	});             

</script>

<script type="text/javascript">
	var ck = 0;

	$('.checkoutform').on('submit',function(e){
		if(ck == 0) {
			e.preventDefault();			
		$('#pills-step2-tab').removeClass('disabled');
		$('#pills-step2-tab').click();

	}else {
		$('#preloader').show();
	}
	$('#pills-step1-tab').addClass('active');
	});

	$('#step1-btn').on('click',function(){
		$('#pills-step1-tab').removeClass('active');
		$('#pills-step2-tab').removeClass('active');
		$('#pills-step3-tab').removeClass('active');
		$('#pills-step2-tab').addClass('disabled');
		$('#pills-step3-tab').addClass('disabled');
		$('#pills-step1-tab').click();
	});

// Step 2 btn DONE

	$('#step2-btn').on('click',function(){
		$('#pills-step3-tab').removeClass('active');
		$('#pills-step1-tab').removeClass('active');
		$('#pills-step2-tab').removeClass('active');
		$('#pills-step3-tab').addClass('disabled');
		$('#pills-step2-tab').click();
		$('#pills-step1-tab').addClass('active');
	});

	$('#step3-btn').on('click',function(){
	 	if($('a.payment:first').data('val') == 'paystack'){
			$('.checkoutform').prop('id','step1-form');
		}
		else {
			$('.checkoutform').prop('id','');
		}
		$('#pills-step3-tab').removeClass('disabled');
		$('#pills-step3-tab').click();

		if ( $('select[name="shipping"] option:selected').first().val() == 'shipto' ) {
			var shipping_user  = '{{ Auth::user() ? Auth::user()->name : null }}';
			var shipping_location  = '{{ $alamat }}';
			var shipping_phone = '{{ Auth::user() ? Auth::user()->phone : null }}';
			var shipping_email= '{{ Auth::user() ? Auth::user()->email : null }}';
		}else if( $('select[name="shipping"] option:selected').first().val() == 'another' ) {
			var shipping_user  = !$('input[name="shipping_name"]').val() ? $('input[name="name"]').val() : $('input[name="shipping_name"]').val();
			var shipping_location  = !$('input[name="shipping_address"]').val() ? $('input[name="address"]').val() : $('input[name="shipping_address"]').val();
			var shipping_phone = !$('input[name="shipping_phone"]').val() ? $('input[name="phone"]').val() : $('input[name="shipping_phone"]').val();
			var shipping_email= !$('input[name="shipping_email"]').val() ? $('input[name="email"]').val() : $('input[name="shipping_email"]').val();
		}else{
			var shipping_user = $('select[name="shipping"] option:selected').first().data('nama');
			var shipping_location = $('select[name="shipping"] option:selected').first().text();
			var shipping_phone = $('select[name="shipping"] option:selected').first().data('phone');
			var shipping_email= '{{ Auth::user() ? Auth::user()->email : null }}';
		}


		$('#shipping_user').html('<i class="fas fa-user"></i>'+shipping_user);
		$('#shipping_location').html('<i class="fas fas fa-map-marker-alt"></i>'+shipping_location);
		$('#shipping_phone').html('<i class="fas fa-phone"></i>'+shipping_phone);
		$('#shipping_email').html('<i class="fas fa-envelope"></i>'+shipping_email);

		$('.orderan').html('');
		$.each(review_produk, function (i, v) { 
			$('.orderan').append(v);
		});

		$('#pills-step1-tab').addClass('active');
		$('#pills-step2-tab').addClass('active');
	});

	$('#final-btn').on('click',function(){
		ck = 1;
	})

	$('.payment').on('click',function(){
		if($(this).data('val') == 'paystack'){
			$('.checkoutform').prop('id','step1-form');
		}
		else {
			$('.checkoutform').prop('id','');
		}
		$('.checkoutform').prop('action',$(this).data('form'));
		$('.pay-area #v-pills-tabContent .tab-pane.fade').not($(this).attr('href')).html('');
		var show = $(this).data('show');
		if(show != 'no') {
			$('.pay-area').removeClass('d-none');
		}
		else {
			$('.pay-area').addClass('d-none');
		}
		$($(this).attr('href')).load($(this).data('href'));
	})

	$(document).on('submit','#step1-form',function(){
		$('#preloader').hide();
		var val = $('#sub').val();
		var total = $('#grandtotal').val();

		if(val == 0)
		{
		var handler = PaystackPop.setup({
			key: '{{$gs->paystack_key}}',
			email: $('input[name=email]').val(),
			amount: total * 100,
			currency: "{{$curr->name}}",
			ref: ''+Math.floor((Math.random() * 1000000000) + 1),
			callback: function(response){
			$('#ref_id').val(response.reference);
			$('#sub').val('1');
			$('#final-btn').click();
			},
			onClose: function(){
			window.location.reload();
			
			}
		});
		handler.openIframe();
			return false;                    
		}
		else {
			$('#preloader').show();
			return true;   
		}
	});
</script>
@endsection