@extends('layouts.vendor')

@section('content')

<div class="content-area">
<div class="mr-breadcrumb">
    <div class="row">
        <div class="col-lg-12">
            @if($conv->order_number != null)
            <h4 class="heading">{{ __('Order Number') }}: {{$conv->order_number}}</h4>
            @endif
            <h4 class="heading">{{ __('Conversation with') }} {{$conv->user->name}} <a class="add-btn" href="{{ url()->previous() }}"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                    </li>
                    <li>
                        <a href="{{ route('admin-message-index') }}">{{ __('Messages') }}</a>
                    </li>
                    <li>
                        <a href="javascript:;">{{ __('Conversations Details') }}</a>
                    </li>
                </ul>
        </div>
    </div>
</div>

<div class="order-table-wrap support-ticket-wrapper ">
                        <div class="panel panel-primary">
                        <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
                        @include('includes.admin.form-both')  
                            <div class="panel-body">
                                    @if ($conv->type === 'Dispute' && $order <> '')
                                    <div class="col-lg-12 row">
                                        @if($order->dp == 0)
                                        <div class="col-lg-6">
                                            <div class="special-box">
                                                <div class="heading-area">
                                                    <h4 class="title">
                                                        {{ __('Shipping Details') }}
                                                    </h4>
                                                </div>
                                                <div class="table-responsive-sm">
                                                    <table class="table">
                                                        <tbody>
                                                            @if($order->shipping == "pickup")
                                                            <tr>
                                                                <th width="45%"><strong>{{ __('Pickup Location') }}:</strong></th>
                                                                <th width="10%">:</th>
                                                                <td width="45%">{{$order->pickup_location}}</td>
                                                            </tr>
                                                            @else
                                                            <tr>
                                                                <th width="45%"><strong>{{ __('Name') }}:</strong></th>
                                                                <th width="10%">:</th>
                                                                <td>{{$order->shipping_name == null ? $order->customer_name : $order->shipping_name}}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th width="45%"><strong>{{ __('Email') }}:</strong></th>
                                                                <th width="10%">:</th>
                                                                <td width="45%">
                                                                    {{$order->shipping_email == null ? $order->customer_email : $order->shipping_email}}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th width="45%"><strong>{{ __('Phone') }}:</strong></th>
                                                                <th width="10%">:</th>
                                                                <td width="45%">
                                                                    {{$order->shipping_phone == null ? $order->customer_phone : $order->shipping_phone}}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th width="45%"><strong>{{ __('Address') }}:</strong></th>
                                                                <th width="10%">:</th>
                                                                <td width="45%">
                                                                    {{$order->shipping_address == null ? $order->customer_address : $order->shipping_address}}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th width="45%"><strong>{{ __('Country') }}:</strong></th>
                                                                <th width="10%">:</th>
                                                                <td width="45%">
                                                                    {{$order->shipping_country == null ? $order->customer_country : $order->shipping_country}}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th width="45%"><strong>{{ __('City') }}:</strong></th>
                                                                <th width="10%">:</th>
                                                                <td width="45%">
                                                                    {{$order->shipping_city == null ? $order->customer_city : $order->shipping_city}}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th width="45%"><strong>{{ __('Postal Code') }}:</strong></th>
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
                                            <div class="special-box">
                                                <div class="heading-area">
                                                    <h4 class="title">
                                                        {{ __('Vendor Details') }}
                                                    </h4>
                                                </div>
                                        
                                                <div class="table-responsive-sm">
                                                    <table class="table">
                                                        <tbody>
                                                            <tr>
                                                                <th width="45%">{{ __('Shop Name') }}</th>
                                                                <th width="10%">:</th>
                                                                <td width="45%">{{$order->vendororders[0]->user->shop_name}}</td>
                                                            </tr>
                                                            <tr>
                                                                <th width="45%">{{ __('Email') }}</th>
                                                                <th width="10%">:</th>
                                                                <td width="45%">{{ $order->vendororders[0]->user->email }}</td>
                                                            </tr>                                
                                                            <tr>
                                                                <th width="45%">{{ __('Phone') }}</th>
                                                                <th width="10%">:</th>
                                                                <td width="45%">{{ $order->vendororders[0]->user->phone }}</td>
                                                            </tr>
                                                            
                                                            {{-- <tr>
                                                                <th width="45%">Rekening Name</th>
                                                                <td width="10%">:</td>
                                                                <td width="45%">{{$order->vendororders[0]->user->rekening_name}}</td>
                                        
                                                            </tr>
                                                            <tr>
                                                                <th width="45%">No Rekening</th>
                                                                <td width="10%">:</td>
                                                                <td width="45%">{{$order->vendororders[0]->user->rekening_no}}</td>
                                                            </tr>
                                                            <tr>
                                                                <th width="45%">Bank</th>
                                                                <td width="10%">:</td>
                                                                <td width="45%">{{$order->vendororders[0]->user->bank}}</td>
                                                            </tr> --}}
                                                            <tr>
                                                                <th width="45%">Cabang</th>
                                                                <td width="10%">:</td>
                                                                <td width="45%">{{$order->vendororders[0]->user->cabang}}</td>
                                                            </tr>                                                                
                                                            
                                                                                                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-lg-12 order-details-table">
                                            <div class="mr-table">
                                                <h4 class="title">{{ __('Products Ordered') }}</h4>
                                                <div class="table-responsiv">
                                                    <table id="example2" class="table table-hover dt-responsive"  cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr>
                                                            <tr>
                                                                {{-- <th width="10%">{{ __('Product ID#') }}</th> --}}
                                                                <th>{{ __('Vendor Shop Name') }}</th>
                                                                {{-- <th>{{ __('Dropshipper Shop Name') }}</th> --}}
                                                                <th>{{ __('Vendor Status') }}</th>
                                                                <th>{{ __('Product Title') }}</th>
                                                                <th width="20%">{{ __('Details') }}</th>
                                                                <th>{{ __('Total Price') }}</th>
                                                            </tr>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($cart->items as $key => $product)
                            
                                                            <?php //dd($cart->items) ?>  
                                                            <tr>
                            
                                                                {{-- <td><input type="hidden" value="{{$key}}">{{ $product['item']['id'] }}</td> --}}
                            
                                                                <td style="width: 20%;">
                                                                    @if($product['item']['user_id'] != 0)
                                                                    @php
                                                                    $dropshipper = '';
                                                                    
                                                                    if ($product['is_dropship'] === true) {
                                                                        $dropshipper = App\Models\User::find($product['item']['dropshipper_id']);
                                                                        $user = App\Models\User::find($product['item']['vendor_id']);
                                                                    }else{
                                                                        $user = App\Models\User::find($product['item']['user_id']);
                                                                    }
                                                                    
                                                                    @endphp
                                                                    @if(isset($user))
                                                                    <a target="_blank"
                                                                        href="{{route('admin-vendor-show',$user->id)}}">{{$user->shop_name}}</a>
                                                                    @else
                                                                    {{ __('Vendor Removed') }}
                                                                    @endif
                                                                    @else
                                                                    <a href="javascript:;">{{ App\Models\Admin::find(1)->shop_name }}</a>
                                                                    @endif
                            
                                                                </td>
                                                                {{-- <td>
                                                                    @if($product['is_dropship'] === true)
                                                                        <a target="_blank"
                                                                        href="{{route('admin-vendor-show',$dropshipper->id)}}">{{$dropshipper->shop_name}}</a>
                                                                    @else
                                                                        {{'-'}}
                                                                    @endif
                                                                </td> --}}
                                                                <td style="width: 15%;">
                                                                    @if($product['item']['user_id'] != 0)
                                                                    @php
                                                                    $user =
                                                                    App\Models\VendorOrder::where('order_id','=',$order->id)->where('user_id', '=', $product['is_dropship'] === true ? $product['item']['vendor_id'] : $product['item']['user_id'])->first();
                                                                    @endphp
                            
                                                                    @if($order->dp == 1 && $order->payment_status == 'Completed')
                            
                                                                    <span class="badge badge-success">{{ __('Completed') }}</span>
                            
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
                            
                            
                                                                <td style="width: 25%;">
                                                                    <input type="hidden" value="{{ $product['license'] }}">
                            
                                                                    @if($product['item']['user_id'] != 0)
                                                                    @php
                                                                    $user = App\Models\User::find($product['item']['user_id']);
                                                                    @endphp
                                                                    @if(isset($user))
                                                                    <a target="_blank"
                                                                        href="{{ route('front.product', $product['item']['slug']) }}">{{strlen($product['item']['name']) > 30 ? substr($product['item']['name'],0,30).'...' : $product['item']['name']}}</a>
                                                                    @else
                                                                    <a target="_blank"
                                                                        href="{{ route('front.product', $product['item']['slug']) }}">
                                                                        {{strlen($product['item']['name']) > 30 ? substr($product['item']['name'],0,30).'...' : $product['item']['name']}}
                                                                    </a>
                                                                    @endif
                                                                    @else
                            
                                                                    <a target="_blank"
                                                                        href="{{ route('front.product', $product['item']['slug']) }}">
                                                                        {{strlen($product['item']['name']) > 30 ? substr($product['item']['name'],0,30).'...' : $product['item']['name']}}
                                                                    </a>
                            
                                                                    @endif
                            
                            
                                                                    @if($product['license'] != '')
                                                                    <a href="javascript:;" data-toggle="modal" data-target="#confirm-delete"
                                                                        class="btn btn-info product-btn" id="license" style="padding: 5px 12px;"><i
                                                                            class="fa fa-eye"></i> {{ __('View License') }}</a>
                                                                    @endif
                            
                                                                </td>
                                                                <td style="width: 20%;">
                                                                    @if($product['size'])
                                                                    <p>
                                                                        <strong>{{ __('Size') }} :</strong> {{$product['size']}}
                                                                    </p>
                                                                    @endif
                                                                    @if($product['color'])
                                                                    <p>
                                                                        <strong>{{ __('color') }} :</strong> <span
                                                                            style="width: 40px; height: 20px; display: block; background: #{{$product['color']}};"></span>
                                                                    </p>
                                                                    @endif
                                                                    <p>
                                                                        <strong>{{ __('Price') }} :</strong> {{$order->currency_sign}}
                                                                        @rp(round($product['item']['publish_price'] * $order->currency_value , 2))
                                                                    </p>
                                                                    <p>
                                                                        <strong>{{ __('Qty') }} :</strong> {{$product['qty']}}
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
                            
                                                                <td style="width: 15%;">{{$order->currency_sign}} @rp(round( ($product['item']['price'] * $product['qty']) *
                                                                    $order->currency_value , 2))</td>
                            
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    @endif                                
                            </div>
                            <div class="panel-body" id="messages">                                
                                @foreach($conv->messages as $message)
                                    @if($message->user_id != null)
                                    <div class="single-reply-area user">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="reply-area">
                                                    <div class="left">
                                                        <p>{{ $message->message }}</p>
                                                    </div>
                                                    <div class="right">
                                                {{-- @if($message->user->is_provider == 1)
                                                <img class="img-circle" src="{{$message->user->photo != null ? $message->user->photo : asset('assets/images/noimage.png')}}" alt="">
                                                @else  --}}

                                                {{-- <img class="img-circle" src="{{$message->user->photo != null ? asset('assets/images/users/'.$message->user->photo) : asset('assets/images/noimage.png')}}" alt=""> --}}
                                                <img class="img-circle" src="{{ asset('assets/images/noimage.png') }}" alt="">

                                                {{-- @endif --}}
                                                        {{-- <a target="_blank" class="d-block profile-btn" href="{{ route('admin-user-show',$message->user_id) }}" class="d-block">{{ __('View Profile') }}</a> --}}
                                                        <p class="ticket-date">{{ $message->created_at->diffForHumans() }}</p>
                                                        <p>{{ $message->user_id === @$order->vendororders[0]->user->id  ? 'Vendor' : 'Customer' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <br>

                                @else

                                <div class="single-reply-area admin">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="reply-area">
                                                <div class="left">
                                                    {{-- <img class="img-circle" src="{{ Auth::guard('admin')->user()->photo ? asset('assets/images/admins/'.Auth::guard('admin')->user()->photo ):asset('assets/images/noimage.png') }}" alt=""> --}}
                                                    @if ($message->admin_id <> '' && $message->admin <> '')
                                                        @if ($message->admin->photo <> '')
                                                        <img src="{{ asset('assets/images/admins/'.$message->admin->photo) }}" alt="">
                                                        @else
                                                        <img class="img-circle" src="{{ asset('assets/images/noimage.png') }}" alt="">
                                                        @endif
                                                    @else
                                                    <img class="img-circle" src="{{ asset('assets/images/noimage.png') }}" alt="">
                                                    @endif
                                                    <p class="ticket-date">{{ $message->created_at->diffForHumans() }}</p>
                                                    Admin
                                                </div>
                                                <div class="right">
                                                    <p>{{ $message->message }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <br>

                                @endif

                                @endforeach
                            </div>
                            @if ($conv->status <> '1')
                            <div class="panel-footer">
                                <form id="messageform" action="{{route('vendor-message-store')}}" data-href="{{ route('vendor-message-load',$conv->id) }}" method="POST">
                                    {{csrf_field()}}
                                    <div class="form-group">
                                        <input type="hidden" name="conversation_id" value="{{$conv->id}}">
                                        <textarea class="form-control" name="message" id="wrong-invoice" rows="5" required="" placeholder="{{ __('Message') }}"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="mybtn1">
                                            {{ __('Add Reply') }}
                                        </button>
                                    </div>
                                </form>
                            </div>                                
                            @endif
                        </div>
                    </div>

</div>
@endsection
