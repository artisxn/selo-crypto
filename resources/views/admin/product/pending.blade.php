@extends('layouts.admin') 

@section('content')  
					<input type="hidden" id="headerdata" value="{{ __("PENDING PRODUCT") }}">
					<div class="content-area">
						<div class="mr-breadcrumb">
							<div class="row">
								<div class="col-lg-12">
										<h4 class="heading">{{ __("Pending Products") }}</h4>
										<ul class="links">
											<li>
												<a href="{{ route('admin.dashboard') }}">{{ __("Dashboard") }} </a>
											</li>
											<li>
												<a href="javascript:;">{{ __("Products") }} </a>
											</li>
											<li>
												<a href="{{ route('admin-prod-pending') }}">{{ __("Pending Products") }}</a>
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
									                        <th>{{ __("Name") }}</th>
															{{-- <th>{{ __("Type") }}</th> --}}
															<th>{{ __("Category") }}</th>
															<th>{{ __("Shop Name") }}</th>
									                        <th>{{ __("Stock") }}</th>
									                        <th>{{ __("Price") }}</th>
									                        <th>{{ __("Status Verified") }}</th>
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
               ajax: '{{ route('admin-prod-pending-datatables') }}',
               columns: [
						{ data: 'name', name: 'name' },
						// { data: 'type', name: 'type' },
						{ data: 'category', name: 'category' },
						{ data: 'shop_name', name: 'shop_name' },
                        { data: 'stock', name: 'stock' },
                        { data: 'price', name: 'price' },
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