@extends('layouts.admin')

@section('content')


<input type="hidden" id="headerdata" value="{{ __('ORDER') }}">

<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Waiting Dropshipper Payment orders') }}</h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                    </li>
                    <li>
                        <a href="javascript:;">{{ __('Orders') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('admin-order-dropshipper-payment') }}">{{ __('Waiting Dropshipper Payment orders') }}</a>
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
                    {{-- <div class="table-responsiv">
                        <div class="gocover"
                            style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                        </div>
                        <table id="geniustable" style="font-size: 13px;" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>{{ __('Order Date') }}</th>
                                    <th>{{ __('Order Number') }}</th>
                                    <th>{{ __('Fee Company') }}</th>
                                    <th>{{ __('Fee Dropshipper') }}</th>
                                    <th>{{ __('Rekening Dropshipper') }}</th>
                                    <th>{{ __('Payment') }}</th>
                                    <th>{{ __('Options') }}</th>
                                </tr>
                            </thead>
                        </table>
                    </div> --}}

                    <div class="table-responsiv">
                        <div class="gocover"
                            style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                        </div>
                        <table id="geniustable" style="font-size: 13px;" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>{{ __('Order Date') }}</th>
                                    <th>{{ __('Order Number') }}</th>
                                    <th>{{ __('Fee Company') }}</th>
                                    <th>{{ __('Fee Dropshipper') }}</th>
                                    <th>{{ __('Rekening Dropshipper') }}</th>
                                    <th>{{ __('Payment') }}</th>
                                    <th>{{ __('Options') }}</th>
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

                                    @php
                                        $vendor = $order->vendororders[0]->user;
                                    @endphp

                                    @if ($feeDropshipper[$key] > 0 && $order->dropshipper_payment <> '1')
                                    <tr>
                                        <td>{{ $order->created_at }}</td>
                                        <td>#{{ $order->order_number }}</td>
                                        <td>{{ $feeCompany[$key] }}</td>
                                        <td>{{ $feeDropshipper[$key] }}</td>
                                        <td>
                                            {{
                                                $vendor->rekening_no.'/'.$vendor->rekening_name.' - '.$vendor->bank.'/'.$vendor->cabang
                                            }}
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-dropshipper" data-toggle="modal" data-target="#notf-dropshipper" data-order="{{ base64_encode($order->order_number) }}">Pay To Dropshipper</button>
                                        </td>
                                        <td>
                                            <div class="godropdown">
                                                <button class="go-dropdown-toggle"> Actions<i class="fas fa-chevron-down"></i></button>
                                                <div class="action-list">
                                                    <a href="' . route('admin-order-show',$data->id) . '" > 
                                                        <i class="fas fa-eye"></i> Details
                                                    </a>
                                                    <a href="javascript:;" class="send" data-email="'. $data->customer_email .'" data-toggle="modal" data-target="#vendorform">
                                                        <i class="fas fa-envelope"></i> Send
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>                                        
                                    @endif
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

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="submit-loader">
                <img src="{{asset('assets/images/'.$gs->admin_loader)}}" alt="">
            </div>
            <div class="modal-header d-block text-center">
                <h4 class="modal-title d-inline-block">{{ __('Update Status') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <p class="text-center">{{ __("You are about to update the order's Status.") }}</p>
                <p class="text-center">{{ __('Do you want to proceed?') }}</p>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                <a class="btn btn-success btn-ok order-btn">{{ __('Proceed') }}</a>
            </div>

        </div>
    </div>
</div>

{{-- ORDER MODAL ENDS --}}


{{-- MESSAGE MODAL --}}
<div class="sub-categori">
    <div class="modal" id="vendorform" tabindex="-1" role="dialog" aria-labelledby="vendorformLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="vendorformLabel">{{ __('Send Email') }}</h5>
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
                                                    placeholder="{{ __('Email') }} *" value="" required="">
                                            </li>
                                            <li>
                                                <input type="text" class="input-field" id="subj" name="subject"
                                                    placeholder="{{ __('Subject') }} *" required="">
                                            </li>
                                            <li>
                                                <textarea class="input-field textarea" name="message" id="msg"
                                                    placeholder="{{ __('Your Message') }} *" required=""></textarea>
                                            </li>
                                        </ul>
                                        <button class="submit-btn" id="emlsub"
                                            type="submit">{{ __('Send Email') }}</button>
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

{{-- ADD / EDIT MODAL --}}

<div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="submit-loader">
                <img src="{{asset('assets/images/'.$gs->admin_loader)}}" alt="">
            </div>
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="notf" role="dialog" aria-labelledby="modal1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="submit-loader">
                <img src="{{asset('assets/images/'.$gs->admin_loader)}}" alt="">
            </div>
            <div class="modal-header d-block text-center">
                <h4 class="modal-title d-inline-block">{{ __("Vendor Payment") }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form action="{{ route('send-notif') }}" id="send" method="POST">
                    {{ csrf_field() }}
                    <div class="input-group mb-3">
                        {!! Form::number('reference', '', ['class' => 'form-control', 'placeholder' => 'Input Reference Number', 'required' => true]) !!}
                    </div>								
                </form>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __("Cancel") }}</button>
                <button type="button" class="btn btn-success btn-ok" id="send-notif">{{ __("Submit") }}</button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="notf-dropshipper" role="dialog" aria-labelledby="modal1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="submit-loader">
                <img src="{{asset('assets/images/'.$gs->admin_loader)}}" alt="">
            </div>
            <div class="modal-header d-block text-center">
                <h4 class="modal-title d-inline-block">{{ __("Dropshipper Payment") }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <form action="{{ route('send-notif-ds') }}" id="sendds" method="POST">
                    {{ csrf_field() }}
                    <div class="input-group mb-3">
                        {!! Form::number('reference', '', ['class' => 'form-control', 'placeholder' => 'Input Reference Number', 'required' => true]) !!}
                    </div>								
                </form>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __("Cancel") }}</button>
                <button type="button" class="btn btn-success btn-ok" id="send-notif-ds">{{ __("Submit") }}</button>
            </div>

        </div>
    </div>
</div>
{{-- ADD / EDIT MODAL ENDS --}}

@endsection

@section('scripts')

{{-- DATA TABLE --}}

<script type="text/javascript">
    // var table = $('#geniustable').DataTable({
    //            ordering: false,
    //            processing: true,
    //            serverSide: true,
    //            ajax: '{{ route('admin-order-datatables','dropshipper-payment') }}',
    //            columns: [
    //                     { data: 'created_at', name: 'created_at' },
    //                     { data: 'id', name: 'id' },
    //                     { data: 'fee_company', name: 'fee_company'},
    //                     { data: 'fee_dropshipper', name: 'fee_dropshipper'},
    //                     { data: 'rek_dropship', name: 'rek_dropship'},      
    //                     { data: 'pay_dropshipper', name: 'pay_dropshipper'},                  
    //                     { data: 'action', searchable: false, orderable: false }
    //                  ],
    //            language : {
    //                 processing: '<img src="{{asset('assets/images/'.$gs->admin_loader)}}">'
    //             },
    //             drawCallback : function( settings ) {
    //                     $('.select').niceSelect();  
    //             }
    //         });

            $(document).on('click', '.btn-payment', function () {
                $( $(this).data('target') ).data('order', atob($(this).data('order')));
            });

            $(document).on('click', '.btn-dropshipper', function () {
                $( $(this).data('target') ).data('order', atob($(this).data('order')));
            });            
				$(document).on('click', '#send-notif', function (e) {
					e.preventDefault();

					$.post($('form#send').attr('action'), $('form#send').serialize() + '&order='+$(this).closest('#notf').data('order'),
						function (data, textStatus, jqXHR) {
							if (data['errors']) {
                                $.each(data['errors'], function (i, e) { 
                                    $.notify(e, {
									    className: 'error'
								    });                                    
                                }); 
							}else{
								$.notify(data[0], {
									className: 'success'
								});

                                $('#notf').modal('hide');
                                table.ajax.reload( null, false );
							}
						},
					);
				});

				$(document).on('click', '#send-notif-ds', function (e) {
					e.preventDefault();

					$.post($('form#sendds').attr('action'), $('form#sendds').serialize() + '&order='+$(this).closest('#notf-dropshipper').data('order'),
						function (data, textStatus, jqXHR) {
							if (data['errors']) {
                                $.each(data['errors'], function (i, e) { 
                                    $.notify(e, {
									    className: 'error'
								    });                                    
                                }); 
							}else{
								$.notify(data[0], {
									className: 'success'
								});

                                $('#notf-dropshipper').modal('hide');
                                // table.ajax.reload( null, false );
                                location.reload();
							}
						},
					);
				});                                                                                
</script>

{{-- DATA TABLE --}}

@endsection