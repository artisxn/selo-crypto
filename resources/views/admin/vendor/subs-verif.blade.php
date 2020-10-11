@extends('layouts.admin') 

@section('content')  
                    <input type="hidden" id="headerdata" value="{{ __("VENDOR SUBSCRIPTIONS") }}">
                    <div class="content-area">
                        <div class="mr-breadcrumb">
                            <div class="row">
                                <div class="col-lg-12">
                                        <h4 class="heading">{{ __("Vendor Subscriptions") }}</h4>
                                        <ul class="links">
                                            <li>
                                                <a href="{{ route('admin.dashboard') }}">{{ __("Dashboard") }} </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">{{ __("Vendors") }}</a>
                                            </li>
                                            <li>
                                                <a href="{{ route('admin-vendor-subs') }}">{{ __("Vendor Subscriptions") }}</a>
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
                                        <div class="table-responsiv">
                                                <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ __("Vendor Name") }}</th>
                                                            <th>{{ __("Plan") }}</th>
                                                            {{-- <th>{{ __("Method") }}</th> --}}
                                                            {{-- <th>{{ __("Transcation ID") }}</th> --}}
                                                            <th>{{ __("Payment Status") }}</th>
                                                            <th>{{ __("Purchase Time") }}</th>
                                                            <th>{{ __("Actions") }}</th>
                                                            <th>{{ __("Options") }}</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

{{-- ADD / EDIT MODAL --}}

            <div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
                                        
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="submit-loader">
                                <img  src="{{asset('assets/images/'.$gs->admin_loader)}}" alt="">
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
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __("Close") }}</button>
                            </div>
                        </div>
                    </div>

                </div>

{{-- ADD / EDIT MODAL ENDS --}}

@endsection    

@section('scripts')

{{-- DATA TABLE --}}

    <script type="text/javascript">

        var table = $('#geniustable').DataTable({
               ordering: false,
               processing: true,
               serverSide: true,
               ajax: '{{ route('admin-vendor-subs-datatables', 'verification') }}',
               columns: [
                        { data: 'name', searchable: false, orderable: false },
                        { data: 'title', name: 'title' },
                        // { data: 'method', name: 'method' },
                        // { data: 'txnid', name: 'txnid' },
                        { data: 'payment_status', name: 'payment_status' },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'action_verify', name: 'action_verify' },
                        { data: 'action_details', name: 'action_details' },
                        // { data: 'action', searchable: false, orderable: false }
                     ],
               language : {
                    processing: '<img src="{{asset('assets/images/'.$gs->admin_loader)}}">'
                },
                drawCallback : function( settings ) {
	    				$('.select').niceSelect();	
				}
            });
                           
    </script>

{{-- DATA TABLE --}}
    
@endsection   