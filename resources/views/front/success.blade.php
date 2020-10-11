@extends('layouts.front')




@section('content')

<!-- Breadcrumb Area Start -->
<div class="breadcrumb-area">
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
            <a href="{{ route('payment.return') }}">
              {{ $langg->lang169 }}
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- Breadcrumb Area End -->


@php
$subtotal = 0;
$total_ongkir =0;
@endphp

@for($i=0; $i < count($order['detail_ongkir']); $i++)
@php
$to_ongkir = explode(',',$order['detail_ongkir'][$i]);
$total_ongkir +=  $to_ongkir[3];
@endphp
@endfor
<section class="tempcart">

@if(!empty($tempcart))

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <!-- Starting of Dashboard data-table area -->
                    <div class="content-box section-padding add-product-1">
                        <div class="top-area">
                                <div class="content">
                                    <h4 class="heading">
                                        {{ $langg->order_title }}
                                    </h4>
                                    <p class="text">
                                        Nomor Invoice : {{ $invoice }}
                                        <br>
                                        Jika anda belum membayar selama 24 jam maka transaksi anda akan otomatis dibatalkan
                                    </p>
                                    <a href="{{ route('front.index') }}" class="link">{{ $langg->lang170 }}</a>
                                  </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">

                                    <div class="product__header">
                                        <div class="row reorder-xs">
                                            <div class="col-lg-12">
                                                <div class="product-header-title">
                                                    {{-- <h2>{{ $langg->lang285 }} {{$order->order_number}}</h2> --}}
                                        </div>   
                                    </div>
                                        @include('includes.form-success')
                                            <div class="col-md-12" id="tempview">
                                                <div class="dashboard-content">
                                                    <div class="view-order-page" id="print">
                                                        <p class="order-date">{{ $langg->lang301 }} {{date('d-M-Y',strtotime($order->created_at))}}</p>


@if($order->dp == 1)

                                                        <div class="billing-add-area">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <h5>{{ $langg->lang287 }}</h5>
                                                                    <address>
                                                                        {{ $langg->lang288 }} {{$order->customer_name}}<br>
                                                                        {{ $langg->lang289 }} {{$order->customer_email}}<br>
                                                                        {{ $langg->lang290 }} {{$order->customer_phone}}<br>
                                                                        {{ $langg->lang291 }} {{$order->customer_address}}<br>
                                                                        {{$order->customer_city}}-{{$order->customer_zip}}
                                                                    </address>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <h5>{{ $langg->lang292 }}</h5>
                                                                    <p>{{ $langg->lang293 }} {{$order->currency_sign}}{{ round($order->pay_amount * $order->currency_value , 2) }}</p>
                                                                    <p>{{ $langg->lang294 }} {{$order->method}}</p>

                                                                    @if($order->method != "Cash On Delivery")
                                                                        @if($order->method=="Stripe")
                                                                            {{$order->method}} {{ $langg->lang295 }} <p>{{$order->charge_id}}</p>
                                                                        @endif
                                                                        {{$order->method}} {{ $langg->lang296 }} <p id="ttn">{{$order->txnid}}</p>

                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>

@else
                                                        <div class="shipping-add-area">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    {{-- @if($order->shipping == "shipto") --}}
                                                                        <h5>{{ $langg->lang302 }}</h5>
                                                                        <address>
                {{ $langg->lang288 }} {{$order->shipping_name == null ? $order->customer_name : $order->shipping_name}}<br>
                {{ $langg->lang289 }} {{$order->shipping_email == null ? $order->customer_email : $order->shipping_email}}<br>
                {{ $langg->lang290 }} {{$order->shipping_phone == null ? $order->customer_phone : $order->shipping_phone}}<br>
                {{ $langg->lang291 }} {{$order->shipping_address == null ? $order->customer_address : $order->shipping_address}}<br>
{{$order->shipping_city == null ? $order->customer_city : $order->shipping_city}}-{{$order->shipping_zip == null ? $order->customer_zip : $order->shipping_zip}}
                                                                        </address>
                                                                    {{-- @else
                                                                        <h5>{{ $langg->lang303 }}</h5>
                                                                        <address>
                                                                            {{ $langg->lang304 }} {{$order->pickup_location}}<br>
                                                                        </address>
                                                                    @endif --}}

                                                                </div>
                                                                <div class="col-md-6">
                                                                    <h5>{{ $langg->lang305 }}</h5>
                                                                    {{-- @if($order->shipping == "shipto") --}}
                                                                        <!-- <p>{{ strtoupper($order->kurir) }} - {{ $order->paket_kurir }} : {{ $order->shipping_cost }}</p> -->
                                                                        <?php for($i=0; $i < count($order['detail_ongkir']);$i++) { ?>
                                                                            <p>{{$order['detail_ongkir'][$i]}}</p>
                                                                        <?php } ?>
                                                                    {{-- @else
                                                                        <p>{{ $langg->lang307 }}</p>
                                                                    @endif --}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="billing-add-area">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <h5>{{ $langg->lang287 }}</h5>
                                                                    <address>
                                                                        {{ $langg->lang288 }} {{$order->customer_name}}<br>
                                                                        {{ $langg->lang289 }} {{$order->customer_email}}<br>
                                                                        {{ $langg->lang290 }} {{$order->customer_phone}}<br>
                                                                        {{ $langg->lang291 }} {{$order->customer_address}}<br>
                                                                        {{$order->customer_city}}-{{$order->customer_zip}}
                                                                    </address>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <h5>{{ $langg->lang292 }}</h5>
                                                                    <!-- <p>
                                                                        {{ $langg->lang293 }}  

                                                                        @switch($order->method)
                                                                            @case('EDCCASH')
                                                                                {{ round($order->pay_amount / $gs->edccash_currency, 3) }} {{ __('EDCCASH') }}
                                                                                @break
                                                                            @case('Bank Transfer')
                                                                                {{$order->currency_sign}} @rp( $order->pay_amount * $order->currency_value )
                                                                                
                                                                                @break
                                                                            @default
                                                                                ''
                                                                        @endswitch
                                                                    </p> -->
                                                                    <p>{{ $langg->lang294 }} {{$order->method}}</p>
                                                                </div>
                                                            </div>
                                                        </div>
@endif
                                                        <br>
                                                        <div class="table-responsive">
                            <table  class="table">
                                <thead>
                                <tr>

                                    <th width="40%">{{ $langg->lang310 }}</th>
                                    <th width="20%">{{ $langg->lang539 }}</th>
                                    <th width="20%">{{ $langg->lang314 }}</th>
                                    <th width="20%">{{ $langg->lang315 }}</th>
                                </tr>
                                </thead>
                                <tbody>

                                @php
                                  $x=0;
                                @endphp

                                @foreach($tempcart->items as $product)
                                    <tr>

                                            <td>{{ $product['item']['name'] }}</td>
                                            <td>
                                                <b>{{ $langg->lang311 }}</b>: {{$product['qty']}} <br>
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
                                            <td>
                                                @switch($order->method)
                                                    @case('EDCCASH')
                                                        {{ round($product['item']['publish_price'] / $gs->edccash_currency, 3) }} {{ __('EDCCASH') }}
                                                        @break
                                                    @case('Bank Transfer')
                                                        {{$order->currency_sign}} @rp( round($product['item']['publish_price'] * $order->currency_value,2) )
                                                        @break
                                                    @default
                                                        ''
                                                @endswitch
                                            </td>
                                            <td>
                                                @switch($order->method)
                                                    @case('EDCCASH')
                                                        {{ round($product['item']['publish_price']  * $product['qty'] / $gs->edccash_currency, 3) }} {{ __('EDCCASH') }}
                                                        @php
                                                        $subtotal += round($product['item']['publish_price'] * $product['qty'] / $gs->edccash_currency, 3);
                                                        @endphp
                                                        @break
                                                    @case('Bank Transfer')
                                                        {{-- {{$order->currency_sign}} @rp( round($product['price'] * $order->currency_value,2) ) --}}

                                                        {{$order->currency_sign}} @rp( round($product['item']['publish_price'] * $product['qty'] * $order->currency_value,2) )
                                                        @php
                                                         $subtotal += $product['item']['publish_price'] * $product['qty'];
                                                        @endphp

                                                        @break
                                                    @default
                                                        ''
                                                @endswitch                                                
                                            </td>
                                            
                                    </tr>
                                @endforeach
                                <tr>
                                                <td colspan="2"></td>
                                                <td>Subtotal</td>
                                                <td>
                                                @switch($order->method)
                                                    @case('EDCCASH')
                                                        {{$subtotal}} {{ __('EDCCASH') }}

                                                        @break
                                                    @case('Bank Transfer')
                                                        {{$order->currency_sign}} @rp( round($subtotal * $order->currency_value,2) )
                                                        @break
                                                    @default
                                                        ''
                                                @endswitch          
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2"></td>
                                                <td>{{ $langg->lang144  }} - {{ $gs->tax}}%</td>
                                                <td>
                                                @switch($order->method)
                                                    @case('EDCCASH')
                                                       @php
                                                        $pajaknya = $subtotal * $gs->tax / 100;
                                                        @endphp
                                                        {{$pajaknya}} {{ __('EDCCASH') }}

                                                        @break
                                                    @case('Bank Transfer')
                                                        @php
                                                        $pajaknya = $subtotal * $gs->tax / 100;
                                                        @endphp
                                                        {{$order->currency_sign}} @rp( round($pajaknya * $order->currency_value,2) )
                                                        @break
                                                    @default
                                                        ''
                                                @endswitch          
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"></td>
                                                <td>Total Ongkir</td>
                                                <td>
                                                @switch($order->method)
                                                    @case('EDCCASH')
                                                        {{round($total_ongkir / $gs->edccash_currency, 3)}}
                                                        {{ __('EDCCASH') }}

                                                        @break
                                                    @case('Bank Transfer')
                                                        {{$order->currency_sign}} @rp( round($total_ongkir* $order->currency_value,2) )
                                                        @break
                                                    @default
                                                        ''
                                                @endswitch    
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2"></td>
                                                <td>Grand Total</td>
                                                <td>
                                                @switch($order->method)
                                                    @case('EDCCASH')
                                               

                                                        {{ round($subtotal + $pajaknya  + $total_ongkir  / $gs->edccash_currency, 3) }} {{ __('EDCCASH') }}
                                                        @break
                                                    @case('Bank Transfer')
                                                        {{$order->currency_sign}} @rp( round($subtotal + $total_ongkir + $pajaknya * $order->currency_value,2) )
                            

                                                        @break
                                                    @default
                                                        ''
                                                @endswitch 
                                                </td>
                                            </tr>




                                </tbody>
                            </table>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>

                        </div>
                    </div>
                </div>
                <!-- Ending of Dashboard data-table area -->
            </div>

@endif

  </section>

@endsection