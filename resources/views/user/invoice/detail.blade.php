@extends('layouts.front')
@section('content')


<section class="user-dashbord">
    <div class="container">
      <div class="row">
        @include('includes.user-dashboard-sidebar')
        <div class="col-lg-9">
					<div class="user-profile-details no-garis">
						<div class="order-history no-garis">
							<div class="header-area no-garis">
								<h4 class="title">
                                    Pesanan
								</h4>
							</div>
							<div class="mr-table allproduct mt-4">
                                <div class="">
                                    <table class="table table-hover dt-responsive">
                                        <thead>
                                            <tr>
                                                <th width="5%">{{ __('Order Number') }}</th>
                                                <th>Total Tagihan</th>
                                                <th>Pengiriman</th>
                                                <th>Paket Pengiriman</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($invoice->orders as $order)
                                            <tr>
                                                <td width="5%">#{{$order->order_number}}</td>
                                                <td>{{ App\Models\Product::convertPrice($order->pay_amount) }}</td>
                                                <td>{{$order->kurir}}</td>
                                                <td>{{$order->paket_kurir}}</td>
                                                <td>
                                                    <a href="{{route('user-order',$order->id)}}">
                                                            Lihat Order
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <!-- <dl class="row">
                                        <dt class="col-sm-3">Total Tagihan</dt>
                                        <dd class="col-sm-9">{{ App\Models\Product::convertPrice($invoice->total_tagihan) }}</dd>
                                        <dt class="col-sm-3">Status</dt>
                                        <dd class="col-sm-9">{{$invoice->status}}</dd>
                                    </dl> -->
                                    
                                    <div class="edit-account-info-div">
                                        <div class="form-group" style="text-align:right">
                                            <a class="btn btn-orange btn-sm" style="border-radius:10px;width:15%;" href="{{ route('user-orders') }}">{{ $langg->lang318 }}</a>
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
@endsection