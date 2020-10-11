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
                    <h4 class="heading">{{ __("Report Payment") }}</h4>
                    <ul class="links">
                      <li>
                        <a href="{{ route('admin.dashboard') }}">{{ __("Dashboard") }} </a>
                      </li>
                      <li>
                          <a href="{{ route('admin-report-order') }}">{{ __('Report Payment') }}</a>
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

                    <form action="{{route('admin-report-payment')}}" method="get" >
                        <div class="row">
                            <div class="col-md-3">
                                <label for="">Form Date</label>
                                <input type="text" name="start_date" id="form-date" class="form-control" required autocomplete="off" value="{{ request()->input('start_date') }}">
                            </div>

                            <div class="col-md-3">
                                <label for="">End Date</label>
                                <input type="text" name="end_date" id="end-date" class="form-control" required autocomplete="off" value="{{ request()->input('end_date') }}">
                            </div>
                            <div class="col-md-3">
                                <br>
                                <button class="btn btn-primary">Filter</button>
                                <a href="{{ route('admin-report-payment') }}" class="btn btn-primary">Clear Filter</a>
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
                                    <th>{{ __('Fee Vendor') }}</th>
                                    <th>{{ __('Fee Company') }}</th>
                                    <th>{{ __('Fee Dropshipper') }}</th>
                                    <th>{{ __('Shipping Cost') }}</th>
                                    <th>{{ __('Tax') }}</th>
                                    <th>{{ __('Payment Vendor') }}</th>
                                    <th>{{ __('Payment Vendor Date') }}</th>
                                    <th>{{ __('Payment Vendor Reference') }}</th>
                                    <th>{{ __('Payment Dropshipper') }}</th>
                                    <th>{{ __('Payment Dropshipper Date') }}</th>
                                    <th>{{ __('Payment Dropshipper Reference') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                                            $feeSeller = [];
                                                            $feeCompany = [];
                                                            $feeDropshipper = [];    
                                                            $totalOrder = [];
                                @endphp
                                @foreach($orders as $key => $order)
                                    @foreach ( unserialize(bzdecompress(utf8_decode($order->cart)))->items as $k => $product)
                                        @php
                                                                $totalOrder[$k] = $product['price'];
                                                                $feeDropshipper[$key] = 0;
                                                                if ($product['is_dropship'] === true) {
                                                                    $feeDropshipper[$key] = feeDropshipper( array_sum($totalOrder) );                                     
                                                                    $feeCompany[$key] = (feeCompany( array_sum($totalOrder) ) - feeDropshipper( array_sum($totalOrder) ) );
                                                                }else{
                                                                    $feeCompany[$key] = feeCompany( array_sum($totalOrder) );
                                                                }
                            
                                                                $feeSeller[$key] = feeVendor( array_sum($totalOrder) );
                            
                                        @endphp
                                    @endforeach

                                    
                                    <tr>
                                        <td>{{ $order->created_at }}</td>
                                        <td>#{{ $order->order_number }}</td>
                                        <td>{{$feeSeller[$key]}}</td>
                                        <td>{{$feeCompany[$key]}}</td>
                                        <td>{{$feeDropshipper[$key]}}</td>
                                        <td>{{$order->shipping_cost}}</td>
                                        <td>{{ array_sum($totalOrder) * ((int) $order->tax / 100) }}</td>
                                        <td>{{ $order->vendor_payment === '1' ? 'Paid' : 'Unpaid' }}</td>
                                        <td>{{ $order->payment_vendor_date }}</td>
                                        <td>{{ $order->payment_vendor_reference }}</td>
                                        <td>{{ $order->dropshipper_payment === '1' ? 'Paid' : '-' }}</td>
                                        <td>{{ $order->payment_dropshipper_date }}</td>                                       
                                        <td>{{ $order->payment_dropshipper_reference }}</td>
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
                        title: 'Report Payment'
                    }, 
                    {
                        extend: 'pdfHtml5',
                        orientation: 'landscape',
                        title: 'Report Payment'
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
