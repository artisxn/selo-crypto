@extends('layouts.vendor')

@section('styles')


@endsection


@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ $langg->lang549 }} <a class="add-btn" href="{{ route('vendor-order-index') }}"><i
                            class="fas fa-arrow-left"></i> {{ $langg->lang550 }}</a></h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('vendor-dashboard') }}">{{ $langg->lang441 }} </a>
                    </li>
                    <li>
                        <a href="javascript:;">{{ $langg->lang443 }}</a>
                    </li>
                    <li>
                        <a href="javascript:;">{{ $langg->lang549 }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="order-table-wrap">
        @include('includes.admin.form-both')
        <div class="row">
            <div class="col-lg-6">
                <div class="special-box">
                    <div class="heading-area">
                        <h4 class="title">
                            {{ $langg->lang549 }}
                        </h4>
                    </div>
                    @php

                    $price = $order->vendororders()->where('user_id','=',$user->id)->sum('price');

                    if($user->shipping_cost != 0){
                    $price = $price + round($user->shipping_cost * $order->currency_value , 2);
                    }

                    if($order->tax != 0){
                    $tax = $price * $order->tax /100 ;
                    $price += $tax;
                    }



                    @endphp
                    <div class="table-responsive-sm">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th class="45%" width="45%">{{ $langg->lang551 }}</th>
                                    <td width="10%">:</td>
                                    <td class="45%" width="45%">{{$order->order_number}}</td>
                                </tr>
                                <tr>
                                    <th width="45%">{{ $langg->lang552 }}</th>
                                    <td width="10%">:</td>
                                    <td width="45%">
                                        {{$order->vendororders()->where('user_id','=',$user->id)->sum('qty')}}</td>
                                </tr>
                                <tr>
                                    <th width="45%">{{ $langg->lang553 }}</th>
                                    <td width="10%">:</td>
                                    <td width="45%">{{$order->currency_sign}} @rp( round($order->pay_amount * $order->currency_value
                                        , 2) )</td>
                                </tr>
                                <tr>
                                    <th width="45%">{{ $langg->lang554 }}</th>
                                    <td width="10%">:</td>
                                    <td width="45%">{{date('d-M-Y H:i:s a',strtotime($order->created_at))}}</td>
                                </tr>


                                <tr>
                                    <th width="45%">{{ $langg->lang795 }}</th>
                                    <td width="10%">:</td>
                                    <td width="45%">{{$order->method}}</td>
                                </tr>

                                @if($order->method != "Cash On Delivery")
                                @if($order->method=="Stripe")
                                <tr>
                                    <th width="45%">{{$order->method}} {{ $langg->lang796 }}</th>
                                    <td width="10%">:</td>
                                    <td width="45%">{{$order->charge_id}}</td>
                                </tr>
                                @endif
                                @endif


                                <th width="45%">{{ $langg->lang798 }}</th>
                                <th width="10%">:</th>
                                <td width="45%">{!! $order->payment_status == 'Pending' ? "<span
                                        class='badge badge-danger'>". $langg->lang799 ."</span>":"<span
                                        class='badge badge-success'>". $langg->lang800 ."</span>" !!}</td>

                                @if(!empty($order->order_note))
                                <th width="45%">{{ $langg->lang801 }}</th>
                                <th width="10%">:</th>
                                <td width="45%">{{$order->order_note}}</td>
                                @endif

                            </tbody>
                        </table>
                    </div>
                    <div class="footer-area">
                        <a href="{{ route('vendor-order-invoice',$order->order_number) }}" class="mybtn1"><i
                                class="fas fa-eye"></i> {{ $langg->lang555 }}</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="special-box">
                    <div class="heading-area">
                        <h4 class="title">
                            {{ $langg->lang556 }}
                        </h4>
                    </div>
                    <div class="table-responsive-sm">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th width="45%">{{ $langg->lang557 }}</th>
                                    <th width="10%">:</th>
                                    <td width="45%">{{$order->customer_name}}</td>
                                </tr>
                                <tr>
                                    <th width="45%">{{ $langg->lang558 }}</th>
                                    <th width="10%">:</th>
                                    <td width="45%">{{$order->customer_email}}</td>
                                </tr>
                                <tr>
                                    <th width="45%">{{ $langg->lang559 }}</th>
                                    <th width="10%">:</th>
                                    <td width="45%">{{$order->customer_phone}}</td>
                                </tr>
                                <tr>
                                    <th width="45%">{{ $langg->lang560 }}</th>
                                    <th width="10%">:</th>
                                    <td width="45%">{{$order->customer_address}}</td>
                                </tr>
                                <tr>
                                    <th width="45%">{{ $langg->lang561 }}</th>
                                    <th width="10%">:</th>
                                    <td width="45%">{{$order->customer_country}}</td>
                                </tr>
                                <tr>
                                    <th width="45%">{{ $langg->lang562 }}</th>
                                    <th width="10%">:</th>
                                    <td width="45%">{{$order->customer_city}}</td>
                                </tr>
                                <tr>
                                    <th width="45%">{{ $langg->lang563 }}</th>
                                    <th width="10%">:</th>
                                    <td width="45%">{{$order->customer_zip}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if($order->dp == 0)
            <div class="col-lg-6">
                <div class="special-box">
                    <div class="heading-area">
                        <h4 class="title">
                            {{ $langg->lang564 }}
                        </h4>
                    </div>
                    <div class="table-responsive-sm">
                        <table class="table">
                            <tbody>
                                @if($order->shipping == "pickup")
                                <tr>
                                    <th width="45%"><strong>{{ $langg->lang565 }}:</strong></th>
                                    <th width="10%">:</th>
                                    <td width="45%">{{$order->pickup_location}}</td>
                                </tr>
                                @else
                                <tr>
                                    <th width="45%"><strong>{{ $langg->lang557 }}:</strong></th>
                                    <th width="10%">:</th>
                                    <td>{{$order->shipping_name == null ? $order->customer_name : $order->shipping_name}}
                                    </td>
                                </tr>
                                <tr>
                                    <th width="45%"><strong>{{ $langg->lang558 }}:</strong></th>
                                    <th width="10%">:</th>
                                    <td width="45%">
                                        {{$order->shipping_email == null ? $order->customer_email : $order->shipping_email}}
                                    </td>
                                </tr>
                                <tr>
                                    <th width="45%"><strong>{{ $langg->lang559 }}:</strong></th>
                                    <th width="10%">:</th>
                                    <td width="45%">
                                        {{$order->shipping_phone == null ? $order->customer_phone : $order->shipping_phone}}
                                    </td>
                                </tr>
                                <tr>
                                    <th width="45%"><strong>{{ $langg->lang560 }}:</strong></th>
                                    <th width="10%">:</th>
                                    <td width="45%">
                                        {{$order->shipping_address == null ? $order->customer_address : $order->shipping_address}}
                                    </td>
                                </tr>
                                <tr>
                                    <th width="45%"><strong>{{ $langg->lang561 }}:</strong></th>
                                    <th width="10%">:</th>
                                    <td width="45%">
                                        {{$order->shipping_country == null ? $order->customer_country : $order->shipping_country}}
                                    </td>
                                </tr>
                                <tr>
                                    <th width="45%"><strong>{{ $langg->lang562 }}:</strong></th>
                                    <th width="10%">:</th>
                                    <td width="45%">
                                        {{$order->shipping_city == null ? $order->customer_city : $order->shipping_city}}
                                    </td>
                                </tr>
                                <tr>
                                    <th width="45%"><strong>{{ $langg->lang563 }}:</strong></th>
                                    <th width="10%">:</th>
                                    <td width="45%">
                                        {{$order->shipping_zip == null ? $order->customer_zip : $order->shipping_zip}}
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
            <div class="col-lg-6">
            @if($order->status == 'on delivery')
                <div class="special-box">
                    <div class="heading-area">
                        <h4 class="title">
                            Nomor Resi
                        </h4>
                    </div>
                    <div class="table-responsive-sm">
                        @if($order->no_resi)
                        {{$order->no_resi}}
                        <button class="btn btn-primary btn-update-resi">Update</button>
                        <hr>
                        <form action="{{url('vendor/order/'.$order->order_number.'/update-resi')}}" method="POST"
                            id="update-resi">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <input type="text" class="form-control" name="no_resi" id="exampleInputEmail1"
                                    aria-describedby="emailHelp" placeholder="Nomor resi pengiriman"
                                    value="{{$order->no_resi}}">
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                        @if(Session::get('msg'))
                        <small class="alert alert-success">{{Session::get('msg')}}</small>
                        <br>
                        @endif
                        @else
                        <form action="{{url('vendor/order/'.$order->order_number.'/update-resi')}}" method="POST">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <input type="text" class="form-control" name="no_resi" id="exampleInputEmail1"
                                    aria-describedby="emailHelp" placeholder="Nomor resi pengiriman"
                                    value="{{$order->no_resi}}">
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                        @endif
                    </div>
                </div>
                @endif
                <div class="special-box">
                        <div class="heading-area">
                            <h4 class="title">
                                Kurir
                            </h4>
                        </div>
                        <div class="table-responsive-sm">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th width="45%">{{ _("Pengiriman Kurir") }}</th>
                                    <th width="10%">:</th>
                                    <td width="45%">{{ strtoupper($order->kurir) }}</td>
                                </tr>
                                <tr>
                                        <th width="45%">{{ _("Layanan Kurir") }}</th>
                                        <th width="10%">:</th>
                                        <td width="45%">{{ strtoupper($order->paket_kurir) }}</td>
                                </tr>
                                <tr>
                                        <th width="45%">{{ _("Biaya Pengiriman") }}</th>
                                        <th width="10%">:</th>
                                        <td width="45%">{{$order->currency_sign}} @rp( $order->shipping_cost )</td>
                                </tr>                                
                            </tbody>
                        </table>                            
                        </div>
                    </div>
            </div>

            @if(count($dropshippers) >= 0)
                @foreach($dropshippers as $dropshipper)
                <div class="col-lg-6">
                    <div class="special-box">
                        <div class="heading-area">
                            <h4 class="title">
                                {{ __('Detail Dropshipper') }}
                            </h4>
                        </div>
                        <div class="table-responsive-sm">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th width="45%"><strong>{{ __("Nama Toko") }}:</strong></th>
                                        <th width="10%">:</th>
                                        <td>{{ ucwords(strtolower( $dropshipper->shop_name )) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="45%"><strong>{{ $langg->lang558 }}:</strong></th>
                                        <th width="10%">:</th>
                                        <td width="45%">
                                            {{ $dropshipper->email }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="45%"><strong>{{ $langg->lang559 }}:</strong></th>
                                        <th width="10%">:</th>
                                        <td width="45%">
                                            {{ $dropshipper->phone }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="45%"><strong>{{ $langg->lang560 }}:</strong></th>
                                        <th width="10%">:</th>
                                        <td width="45%">
                                            {{ $dropshipper->shop_address }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="45%"><strong>{{ $langg->lang562 }}:</strong></th>
                                        <th width="10%">:</th>
                                        <td width="45%">
                                            {{ $dropshipper->regency->name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="45%"><strong>{{ $langg->lang563 }}:</strong></th>
                                        <th width="10%">:</th>
                                        <td width="45%">
                                            {{ $dropshipper->zip }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>                
                @endforeach
            @endif
        </div>



        <div class="row">
            <div class="col-lg-12 order-details-table">
                <div class="mr-table">
                    <h4 class="title">{{ $langg->lang566 }}</h4>
                    <div class="table-responsiv">
                        <table id="example2" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                <tr>
                                    <th>{{ $langg->lang567 }}</th>
                                    <th>{{ $langg->lang568 }}</th>
                                    <th>{{ __("Toko Dropshipper") }}</th>
                                    <th>{{ $langg->lang569 }}</th>
                                    <th style="width: 5%;">{{ __("Kirim Sebagai Dropshipper?") }}</th>
                                    <th>{{ $langg->lang570 }}</th>
                                    <th>{{ $langg->lang539 }}</th>
                                    <th>{{ $langg->lang574 }}</th>
                                </tr>
                                </tr>
                            </thead>
                            {{-- {{ dd($cart->items) }} --}}
                            <tbody>
                                @foreach($cart->items as $key => $product)

                                @if($product['item']['user_id'] != 0)

                                @if($product['item']['user_id'] == Auth::user()->id)
                                
                                <tr>

                                    <td><input type="hidden" value="{{$key}}">{{ $product['item']['id'] }}</td>

                                    <td>
                                        @if($product['item']['user_id'] != 0)
                                        @php
                                        $user = App\Models\User::find($product['item']['user_id']);
                                        @endphp
                                        @if(isset($user))
                                        {{$user->shop_name}}
                                        @else
                                        {{ $langg->lang575 }}
                                        @endif
                                        @endif

                                    </td>
                                    <td>
                                        @if($product['is_dropship'] === TRUE)
                                        @php
                                        $user = App\Models\User::find($product['item']['dropshipper_id']);
                                        @endphp
                                        @if(isset($user))
                                        {{$user->shop_name}}
                                        @else
                                        {{ $langg->lang575 }}
                                        @endif
                                        @else 
                                        {{ '-' }}
                                        @endif

                                    </td>
                                    <td>
                                        @if($product['item']['user_id'] != 0)
                                        @php
                                        $user =
                                        App\Models\VendorOrder::where('order_id','=',$order->id)->where('user_id', '=', $product['item']['user_id'])->first();
                                        @endphp

                                        @if($order->dp == 1 && $order->payment_status == 'Completed')

                                        <span class="badge badge-success">{{ $langg->lang542 }}</span>

                                        @else

                                        @if($user->status == 'pending')
                                        <span class="badge badge-warning">{{ucwords($user->status)}}</span>
                                        @elseif($user->status == 'processing')
                                        <span class="badge badge-info">{{ucwords($user->status)}}</span>
                                        @elseif($user->status == 'on delivery')
                                        <span class="badge badge-primary">{{ucwords($user->status)}}</span>
                                        @elseif($user->status == 'completed')
                                        <span class="badge badge-success">{{ucwords($user->status)}}</span>
                                        @elseif($user->status == 'declined')
                                        <span class="badge badge-danger">{{ucwords($user->status)}}</span>
                                        @endif

                                        @endif

                                        @endif
                                    </td>

                                    <td align="center">

                                        @if ($product['is_dropship'] === true)
                                            <div><i class="far fa-check-circle"></i></div>
                                        @else
                                            <div><i class="far fa-times-circle"></i></div>
                                        @endif                                        
                                    </td>


                                    <td>
                                        <input type="hidden" value="{{ $product['license'] }}">

                                        @if($product['item']['user_id'] != 0)
                                        @php
                                        $user = App\Models\User::find($product['item']['user_id']);
                                        @endphp
                                        @if(isset($user))
                                        <a target="_blank"
                                            href="{{ route('front.product', $product['item']['slug']) }}">{{strlen($product['item']['name']) > 30 ? substr($product['item']['name'],0,30).'...' : $product['item']['name']}}</a>
                                        @else
                                        <a
                                            href="javascript:;">{{strlen($product['item']['name']) > 30 ? substr($product['item']['name'],0,30).'...' : $product['item']['name']}}</a>
                                        @endif
                                        @endif


                                        @if($product['license'] != '')
                                        <a href="javascript:;" data-toggle="modal" data-target="#confirm-delete"
                                            class="btn btn-info product-btn" id="license" style="padding: 5px 12px;"><i
                                                class="fa fa-eye"></i> View License</a>
                                        @endif

                                    </td>
                                    <td>
                                        @if($product['size'])
                                        <p>
                                            <strong>{{ $langg->lang312 }} :</strong> {{$product['size']}}
                                        </p>
                                        @endif
                                        @if($product['color'])
                                        <p>
                                            <strong>{{ $langg->lang313 }} :</strong> <span
                                                style="width: 40px; height: 20px; display: block; background: #{{$product['color']}};"></span>
                                        </p>
                                        @endif
                                        <p>
                                            <strong>{{ $langg->lang754 }} :</strong> 
                                            @if ($order->method === 'Bank Transfer')
                                                {{$order->currency_sign}}
                                                @rp(round($product['item']['publish_previous_price'] * $order->currency_value , 2))
                                            @else
                                                {{ round($product['item']['price'] / $gs->edccash_currency, 3) . ' EDC' }}
                                            @endif
                                        </p>
                                        <p>
                                            <strong>{{ $langg->lang311 }} :</strong> {{$product['qty']}}
                                            {{ $product['item']['measure'] }}
                                        </p>
                                        @if(!empty($product['keys']))

                                        @foreach( array_combine(explode(',', $product['keys']), explode(',',
                                        $product['values'])) as $key => $value)
                                        <p>

                                            <b>{{ ucwords(str_replace('_', ' ', $key))  }} : </b> {{ $value }}

                                        </p>
                                        @endforeach

                                        @endif

                                    </td>

                                    <td>
                                        @if ($order->method === 'Bank Transfer')
                                        {{$order->currency_sign}} @rp( round($product['price'] * $order->currency_value, 2) )
                                        @else
                                        {{ round($product['item']['price'] / $gs->edccash_currency, 3) . ' EDC' }}
                                        @endif
                                    </td>

                                </tr>

                                @endif

                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 text-center mt-2">
                <a class="btn sendEmail send" href="javascript:;" class="send" data-email="{{ $order->customer_email }}"
                    data-toggle="modal" data-target="#vendorform">
                    <i class="fa fa-send"></i> {{ $langg->lang576 }}
                </a>
            </div>
        </div>
    </div>
</div>
<!-- Main Content Area End -->
</div>
</div>


</div>

{{-- LICENSE MODAL --}}

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header d-block text-center">
                <h4 class="modal-title d-inline-block">{{ $langg->lang577 }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p class="text-center">{{ $langg->lang578 }} : <span id="key"></span> <a href="javascript:;"
                        id="license-edit">{{ $langg->lang577 }}</a><a href="javascript:;" id="license-cancel"
                        class="showbox">{{ $langg->lang584 }}</a></p>
                <form method="POST" action="{{route('vendor-order-license',$order->order_number)}}" id="edit-license"
                    style="display: none;">
                    {{csrf_field()}}
                    <input type="hidden" name="license_key" id="license-key" value="">
                    <div class="form-group text-center">
                        <input type="text" name="{{ $langg->lang585 }}" placeholder="{{ $langg->lang579 }}"
                            style="width: 40%; border: none;" required=""><input type="submit" name="submit"
                            value="Save License" class="btn btn-primary"
                            style="border-radius: 0; padding: 2px; margin-bottom: 2px;">
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-danger" data-dismiss="modal">{{ $langg->lang580 }}</button>
            </div>
        </div>
    </div>
</div>


{{-- LICENSE MODAL ENDS --}}

{{-- MESSAGE MODAL --}}
<div class="sub-categori">
    <div class="modal" id="vendorform" tabindex="-1" role="dialog" aria-labelledby="vendorformLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="vendorformLabel">{{ $langg->lang576 }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid p-0">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="contact-form">
                                    <form id="emailreply">
                                        {{csrf_field()}}
                                        <ul>
                                            <li>
                                                <input type="email" class="input-field eml-val" id="eml" name="to"
                                                    placeholder="{{ $langg->lang583 }} *" value="" required="">
                                            </li>
                                            <li>
                                                <input type="text" class="input-field" id="subj" name="subject"
                                                    placeholder="{{ $langg->lang581 }} *" required="">
                                            </li>
                                            <li>
                                                <textarea class="input-field textarea" name="message" id="msg"
                                                    placeholder="{{ $langg->lang582 }} *" required=""></textarea>
                                            </li>
                                        </ul>
                                        <button class="submit-btn" id="emlsub"
                                            type="submit">{{ $langg->lang576 }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MESSAGE MODAL ENDS --}}




@endsection


@section('scripts')

<script type="text/javascript">
    $('#example2').dataTable( {
  "ordering": false,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : false,
      'info'        : false,
      'autoWidth'   : false,
      'responsive'  : true
} );
</script>

<script type="text/javascript">
    $('#update-resi').hide();
    $('.btn-update-resi').click(function(){
        $('#update-resi').toggle();
    })
        $(document).on('click','#license' , function(e){
            var id = $(this).parent().find('input[type=hidden]').val();
            var key = $(this).parent().parent().find('input[type=hidden]').val();
            $('#key').html(id);  
            $('#license-key').val(key);    
    });
        $(document).on('click','#license-edit' , function(e){
            $(this).hide();
            $('#edit-license').show();
            $('#license-cancel').show();
        });
        $(document).on('click','#license-cancel' , function(e){
            $(this).hide();
            $('#edit-license').hide();
            $('#license-edit').show();
        });

        $(document).on('submit','#edit-license' , function(e){
            e.preventDefault();
          $('button#license-btn').prop('disabled',true);
              $.ajax({
               method:"POST",
               url:$(this).prop('action'),
               data:new FormData(this),
               dataType:'JSON',
               contentType: false,
               cache: false,
               processData: false,
               success:function(data)
               {
                  if ((data.errors)) {
                    for(var error in data.errors)
                    {
                        $.notify('<li>'+ data.errors[error] +'</li>','error');
                    }
                  }
                  else
                  {
                    $.notify(data,'success');
                    $('button#license-btn').prop('disabled',false);
                    $('#confirm-delete').modal('toggle');

                   }
               }
                });
        });
</script>

@endsection