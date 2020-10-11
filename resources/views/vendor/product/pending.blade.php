@extends('layouts.vendor') 

@section('content')  
					<input type="hidden" id="headerdata" value="{{ __("PENDING PRODUCT") }}">
					<div class="content-area">
						<div class="mr-breadcrumb">
							<div class="row">
								<div class="col-lg-12">
										<h4 class="heading">{{ $langg->lang805 }}</h4>
										<ul class="links">
											<li>
												<a href="{{ route('admin.dashboard') }}">{{ $langg->lang441 }} </a>
											</li>
											<li>
												<a href="javascript:;">{{ $langg->lang444 }} </a>
											</li>
											<li>
												<a href="{{ route('admin-prod-pending') }}">{{ $langg->lang805 }}</a>
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
									                        <th>{{ $langg->lang608 }}</th>
									                        <th>{{ $langg->lang609 }}</th>
															<th>{{ $langg->lang610 }}</th>
															<th>{{ $langg->lang669 }}</th>
									                        <th>{{ $langg->lang611 }}</th>
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


{{-- DATA TABLE --}}

    <script type="text/javascript">

		var table = $('#geniustable').DataTable({
			   ordering: false,
               processing: true,
               serverSide: true,
               ajax: '{{ route('vendor-prod-pending-datatables') }}',
               columns: [
                        { data: 'name', name: 'name' },
						{ data: 'type', name: 'type' },
						{ data: 'price', name: 'price' },
                        { data: 'stock', name: 'stock' },
                        { data: 'status', searchable: false, orderable: false},

                     ],
                language : {
                	processing: '<img src="{{asset('assets/images/'.$gs->admin_loader)}}">'
                },
				drawCallback : function( settings ) {
	    				$('.select').niceSelect();	
				}
            });								
	
{{-- DATA TABLE ENDS--}}


</script>

@endsection   