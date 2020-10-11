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
								Invoices Belanja
							</h4>
						</div>
						<div class="mr-table allproduct mt-4">
							<div class="table-responsiv">
								<table id="example" class="table table-hover dt-responsive" cellspacing="0"
									width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>Nomor Invoice</th>
											<th>Tagihan</th>
											<th>Order dari toko</th>
											<th>Status</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php $no = 1; ?>
										@foreach($invoices as $invoice)
										<tr>
											<td>{{$no++}}</td>
											<td> {{ '#'.$invoice->invoice_number }} </td>
											<td>{{ App\Models\Product::convertPrice($invoice->total_tagihan) }}</td>
											<td>{{count($invoice->orders)}}</td>
											<td>{{$invoice->status}}</td>
											<td>
												<a href="{{route('user-invoice-detail', base64_encode($invoice->invoice_number) )}}">
													Lihat Invoice
												</a>
											</td>
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
	</div>
</section>
@endsection