@extends('layouts.vendor')

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ $langg->lang443 }}</h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('vendor-dashboard') }}">{{ $langg->lang441 }} </a>
                    </li>
                    <li>
                        <a href="javascript:;">{{ $langg->lang442 }}</a>
                    </li>
                    <li>
                        <a href="{{ route('vendor-order-index') }}">{{ $langg->lang443 }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="product-area">
        <div class="row">
            <div class="col-lg-12">
                <div class="mr-table allproduct">
                    @include('includes.form-success')
                    <div class="table-responsiv">
                        <div class="gocover"
                            style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                        </div>
                        <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th style="font-size: 13px;">{{ $langg->lang534 }}</th>
                                    <th style="font-size: 13px;">{{ $langg->lang535 }}</th>
                                    <th style="font-size: 13px;">{{ $langg->lang536. ' (Rp)' }}</th>
                                    <th style="font-size: 13px;">{{ $langg->lang536. ' (EDC)' }}</th>
                                    <th style="font-size: 13px;">{{ $langg->lang537 }}</th>
                                    <th style="font-size: 13px;">Status Pembayaran</th>
                                    {{-- <th style="font-size: 13px;">Kirim Sebagai Dropshipper ?</th> --}}
                                    <th style="font-size: 13px;">{{ $langg->lang538 }}</th>
                                </tr>
                            </thead>

                            <tbody style="font-size: 13px;">
                                @foreach($orders as $orderr)

                                    
                                    
                                    @php
                                $qty = $orderr->sum('qty');
                                $price = $orderr->sum('price');
                                @endphp
                                @foreach($orderr as $order)


                                @php

                                if($user->shipping_cost != 0){
                                $price += round($user->shipping_cost * $order->order->currency_value , 2);
                                }
                                if(App\Models\Order::where('order_number','=',$order->order->order_number)->first()->tax
                                != 0){
                                $price += ($price / 100) *
                                App\Models\Order::where('order_number','=',$order->order->order_number)->first()->tax;
                                }

                                @endphp
                                @if($order->order <> '' && $order->order->payment_status === 'Paid')
                                <tr>
                                    <td> <a
                                            href="{{route('vendor-order-invoice',$order->order_number)}}">{{ $order->order->order_number}}</a>
                                    </td>
                                    <td>{{$qty}}</td>
                                    <td>
                                        @if ($order->order->method === 'Bank Transfer')
                                        {{$order->order->currency_sign}} @rp( round($order->order->pay_amount * $order->order->currency_value, 2) )
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        {{ $order->order->method === 'EDCCASH' ? round($order->order->pay_amount / $gs->edccash_currency, 3). ' EDC' : '-' }}
                                    </td>
                                    <td>{{$order->order->method}}</td>
                                    <td>{!! $order->order->payment_status == 'Pending' ? "<span
                                            class='badge badge-danger'>Belum dibayar</span>" : "<span
                                            class='badge badge-success'>Sudah dibayar</span>" !!}
                                    </td>
                                    {{-- <td>
                                        @if ($order->order->is_dropship === '1')
                                            <div><i class="far fa-check-circle"></i></div>
                                        @else
                                            <div><i class="far fa-times-circle"></i></div>
                                        @endif
                                    </td> --}}
                                    <td>


                                        <div class="action-list">

                                            <a href="{{route('vendor-order-show',$order->order->order_number)}}"
                                                class="btn btn-primary product-btn"><i class="fa fa-eye"></i>
                                                {{ $langg->lang539 }}</a>
                                            <select class="vendor-btn {{ $order->status }}">
                                                <option
                                                    value="{{ route('vendor-order-status',['slug' => $order->order->order_number, 'status' => 'pending']) }}" data-order="{{ $order->order->order_number }}"
                                                    {{  $order->status == "pending" ? 'selected' : ''  }} {{ $order->status == "completed" || $order->status == "processing" || $order->status == "on delivery" ? 'disabled' : null }} data-status="pending">
                                                    {{ $langg->lang540 }}</option>
                                                <option
                                                    value="{{ route('vendor-order-status',['slug' => $order->order->order_number, 'status' => 'processing']) }}" data-order="{{ $order->order->order_number }}"
                                                    {{  $order->status == "processing" ? 'selected' : ''  }} {{ $order->status == "completed" || $order->status == "on delivery" ? 'disabled' : null }} data-status="processing">
                                                    {{ $langg->lang541 }}</option>
                                                <option
                                                    value="{{ route('vendor-order-status',['slug' => $order->order->order_number, 'status' => 'on delivery']) }}" data-order="{{ $order->order->order_number }}"
                                                    {{  $order->status == "on delivery" ? 'selected' : ''  }} {{ $order->status == "completed" ? 'disabled' : null }} data-status="on delivery">
                                                    {{ _("On Delivery") }}</option>
                                                <option
                                                    value="{{ route('vendor-order-status',['slug' => $order->order->order_number, 'status' => 'declined']) }}" data-order="{{ $order->order->order_number }}"
                                                    {{  $order->status == "declined" ? 'selected' : ''  }} {{ $order->status !== "pending" ? 'disabled' : null }} data-status="declined">
                                                    {{ $langg->lang543 }}</option>
                                                <option
                                                    value="{{ route('vendor-order-status',['slug' => $order->order->order_number, 'status' => 'completed']) }}" data-order="{{ $order->order->order_number }}"
                                                    {{  $order->status == "completed" ? 'selected' : ''  }} {{ $order->status == "completed" || $order->status == "processing" || $order->status == "on delivery" || $order->status == "pending" ? 'disabled' : null }} data-status="completed">
                                                    {{ $langg->lang542 }}</option>                                                    
                                            </select>

                                        </div>

                                    </td>

                                </tr>
                                @endif

                                @break
                                @endforeach                                    
                                    
                                    

                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ORDER MODAL --}}

<div class="modal fade" id="confirm-delete2" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="submit-loader">
                <img src="{{asset('assets/images/'.$gs->admin_loader)}}" alt="">
            </div>
            <div class="modal-header d-block text-center">
                <h4 class="modal-title d-inline-block">{{ $langg->lang544 }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <span data-select=""></span>

            <!-- Modal body -->
            <div class="modal-body">
                <p class="text-center">{{ $langg->lang545 }}</p>
                <p class="text-center">{{ $langg->lang546 }}</p>
                <form action="#" method="POST"
                    id="update-resi">
                    {{ csrf_field() }}
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="no_resi" placeholder="Input Nomor Resi Pengiriman"
                            aria-label="Recipient's username" aria-describedby="button-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button" id="btn-resi">Submit</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ $langg->lang547 }}</button>
                <a class="btn btn-success btn-ok order-btn">{{ $langg->lang548 }}</a>
            </div>

        </div>
    </div>
</div>

{{-- ORDER MODAL ENDS --}}


@endsection

@section('scripts')

{{-- DATA TABLE --}}

<script type="text/javascript">
    var order = '';
    $('.vendor-btn').on('change',function(){
        $('.modal form').show();
        if ($(this).find('option:selected').data('status') !== 'on delivery') {
            $('.modal form').hide();    
        }

        

        order = $(this).find('option:selected').data('order');

          $('#confirm-delete2').modal('show');
          $('#confirm-delete2').find('span').data('select', $(this));
          $('#confirm-delete2').find('.btn-ok').attr('href', $(this).val());

    });

    $('#confirm-delete2').on('hidden.bs.modal', function (e) {
                var select = $(this).find('span').data('select');

                // select.prop('selectedIndex', 0);
                $('.vendor-btn option').prop('selected', function() {
                    return this.defaultSelected;
                });
                
    });


        var table = $('#geniustable').DataTable({
               ordering: false
           });


                 $(document).on('click', '#btn-resi', function (e) {
                    e.preventDefault();
                     let form = $('form#update-resi');
                      $.post('{{ url("/") }}'+'/vendor/order/' + order + '/update-resi', form.serialize(),
                          function (data, textStatus, jqXHR) {
                            $.notify(data.msg, data.status);
                          },
                      );
                 });                                               
</script>

{{-- DATA TABLE --}}

@endsection