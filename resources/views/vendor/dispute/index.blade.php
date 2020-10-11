@extends('layouts.vendor') 

@section('content')  
          <div class="content-area">
            <div class="mr-breadcrumb">
              <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Disputes') }}</h4>
                    <ul class="links">
                      <li>
                        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                      </li>
                      <li>
                        <a href="javascript:;">{{ __('Disputes') }}</a>
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
                              <th>{{ __('Name') }}</th>
                              <th>{{ __('Subject') }}</th>
                              <th>{{ __('Order Number') }}</th>
                              <th>{{ __('Date') }}</th>
                              <th>{{ __('Status') }}</th>
                              <th>{{ __('Actions') }}</th>
                            </tr>
                          </thead>
                        </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

@endsection    



@section('scripts')

    <script type="text/javascript">

    var table = $('#geniustable').DataTable({
         ordering: false,
               processing: true,
               serverSide: true,
               ajax: '{{ route('vendor-dmessage-datatables') }}',
               columns: [
                  { data: 'name', name: 'name' },
                  { data: 'subject', name: 'subject' },
                  { data: 'order_number', name: 'order_number' },
                  { data: 'created_at', name: 'created_at'},
                  { data: 'status', name: 'status'},
                  { data: 'action', searchable: false, orderable: false }

                     ],
               language: {
                  processing: '<img src="{{asset('assets/images/'.$gs->admin_loader)}}">'
                },
        drawCallback : function( settings ) {
              $('.select').niceSelect();  
        }
            });
                                
      

    </script>


@endsection   