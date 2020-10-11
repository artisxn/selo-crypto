@extends('layouts.admin') 
@section('styles')

<link href="{{asset('assets/admin/css/jquery-ui.css')}}" rel="stylesheet" type="text/css">

<style>
    .dt-button{
        position: relative;
        top: 35px;
    }
</style>

@endsection

@section('content')  
          <input type="hidden" id="headerdata" value="{{ __('ROLE') }}">
          <div class="content-area">
            <div class="mr-breadcrumb">
              <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __("Report Order") }}</h4>
                    <ul class="links">
                      <li>
                        <a href="{{ route('admin.dashboard') }}">{{ __("Dashboard") }} </a>
                      </li>
                      <li>
                          <a href="{{ route('admin-report-order') }}">{{ __('Report Order') }}</a>
                      </li>
                    </ul>
                </div>
              </div>
            </div>
            <div class="product-area">
              <div class="row">
                <div class="col-lg-12">
                  <div class="mr-table allproduct">

                        @include('includes.admin.form-success')  

                    <form action="{{route('admin-report-order')}}" method="get" >
                        <div class="row">
                            <div class="col-md-3">
                                <label for="">From Date</label>
                                <input type="text" name="start_date" id="form-date" class="form-control" required autocomplete="off" value="{{ request()->input('start_date') }}">
                            </div>

                            <div class="col-md-3">
                                <label for="">End Date</label>
                                <input type="text" name="end_date" id="end-date" class="form-control" required autocomplete="off" value="{{ request()->input('end_date') }}">
                            </div>
                            <div class="col-md-3">
                                <br>
                                <button class="btn btn-primary">Filter</button>
                                <a href="{{ route('admin-report-order') }}" class="btn btn-primary">Clear Filter</a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsiv">
                        <div class="gocover"
                            style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                        </div>
                        <table id="geniustable" style="font-size: 13px;" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>{{ __('Order Date') }}</th>
                                    <th>{{ __('Order Number') }}</th>
                                    <th>{{ __('Total Order') }}</th>
                                    <th>{{ __('Tax') }}</th>
                                    <th>{{ __('Shipping Cost') }}</th>
                                    <th>{{ __('Fee Vendor') }}</th>
                                    <th>{{ __('Fee Company') }}</th>
                                    <th>{{ __('Fee Dropshipper') }}</th>
                                    <th>{{ __('Sistem Pembayaran') }}</th>
                                    <th>{{ __('Shop Name') }}</th>
                                    <th>{{ __('Full  Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Rekening') }}</th>
                                    <th>{{ __('Dropshipper Name') }}</th>
                                    <th>{{ __('Full  Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Rekening Dropshipper') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $feeSeller = [];
                                    $feeCompany = [];
                                    $feeDropshipper = [];    
                                    $totalOrder = [];
                                    $totalTax = [];
                                    $slugDropshipper = [];
                                    $dropshippers = [];
                                @endphp
                                @foreach($orders as $key => $order)
                                    @foreach ( unserialize(bzdecompress(utf8_decode($order->cart)))->items as $k => $product)
                                        @php
                                                                $slugDropshipper[$key] = [];
                                                                $totalOrder[$k] = $product['price'];
                                                                $feeDropshipper[$key] = 0;
                                                                if ($product['is_dropship'] === true) {
                                                                    $feeDropshipper[$key] = feeDropshipper( array_sum($totalOrder) );                                     
                                                                    $feeCompany[$key] = (feeCompany( array_sum($totalOrder) ) - feeDropshipper( array_sum($totalOrder) ) );

                                                                    $slugDropshipper[$key] = $product['item']['dropshipper_id'];
                                                                }else{
                                                                    $feeCompany[$key] = feeCompany( array_sum($totalOrder) );
                                                                }
                            
                                                                $feeSeller[$key] = feeVendor( array_sum($totalOrder) );

                                        @endphp
                                    @endforeach

                                    @php
                                        $vendor[$key] = @$order->vendororders[0]->user;

                                        if ($slugDropshipper[$key] <> []) {
                                            foreach ($slugDropshipper[$key] as $key => $toko) {
                                                $user = User::where('id', $toko)
                                                                ->where('is_vendor', 2)
                                                                ->firstOrFail();
                                                if ($user->subscribes->is_dropship === '1') {
                                                    $dropshippers[$key][] = $user;    
                                                }
                                            }                                             
                                        }else{
                                            $dropshippers[$key] = [];
                                        }         
                                    @endphp

                                    
                                    <tr>
                                        <td>{{ $order->created_at }}</td>
                                        <td>#{{ $order->order_number }}</td>
                                        <td> {{ $order->pay_amount }} </td>
                                        <td> {{ array_sum($totalOrder) * ((int) $order->tax / 100) }}  </td>
                                        <td> {{ $order->shipping_cost }} </td>
                                        <td> {{ $feeSeller[$key] }} </td>
                                        <td> {{ $feeCompany[$key] }} </td>
                                        <td> {{ $feeDropshipper[$key] }} </td>
                                        <td> {{ ($order->method == 'Bank Transfer' ? 'Bank Transfer - GV' : $order->method) }} </td>
                                        <td> {{ $vendor[$key]->shop_name }} </td>
                                        <td> {{ $vendor[$key]->name }} </td>
                                        <td> {{ $vendor[$key]->email }} </td>
                                        <td> {{ $vendor[$key]->rekening_no.'/'.$vendor[$key]->rekening_name.' - '.$vendor[$key]->bank.'/'.$vendor[$key]->cabang }} </td>
                                        <td> {{ count( $dropshippers[$key] ) > 0 ? @$dropshippers[$key][0]->shop_name : '-' }} </td>
                                        <td> {{ count( $dropshippers[$key] ) > 0 ? @$dropshippers[$key][0]->name : '-' }} </td>
                                        <td> {{ count( $dropshippers[$key] ) > 0 ? @$dropshippers[$key][0]->email : '-' }} </td>
                                        <td> {{ count( $dropshippers[$key] ) > 0 ? @$dropshippers[$key][0]->rekening_no.'/'.@$dropshippers[$key][0]->rekening_name.' - '.@$dropshippers[$key][0]->bank.'/'.@$dropshippers[$key][0]->cabang : '-' }} </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>                    
                  </div>
                </div>
              </div>
            </div>
          </div>


@endsection    

@section('scripts')
<script src="https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js"></script>
<script>
		$(function () {
            $('#form-date').datepicker({
            autoclose: true,
            dateFormat: 'yy-mm-dd'
        	});
            $('#end-date').datepicker({
            autoclose: true,
            dateFormat: 'yy-mm-dd'
        	});
    	});
</script>

<script type="text/javascript">
    var table = $('#geniustable').DataTable({
               ordering: false,
               dom: 'Bfrtip',
               buttons: [
                    {
                        extend: 'csvHtml5',
                        title: 'Report Order'
                    }, 
                    {
                        extend: 'pdfHtml5',
                        title: 'Report Order'
                    }
                ],
               language : {
                    processing: '<img src="{{asset('assets/images/'.$gs->admin_loader)}}">'
                },
                drawCallback : function( settings ) {
                        $('.select').niceSelect();  
                },
                // initComplete: function() {
                //     $('#geniustable_filter').append( $('.dt-buttons').clone() );
                // },
            });                                                                          
</script>


@endsection   
