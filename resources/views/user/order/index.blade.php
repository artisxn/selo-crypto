@extends('layouts.front')
@section('styles')
<style>
	div::-webkit-scrollbar {
		width: 10px;
	}

	div::-webkit-scrollbar-thumb {
		background: grey;
	}

	.review-area .star-area .star-list li {
		display: inline-block;
		margin-right: 20px;
		font-size: 14px;
		color: #bdbdbd;
		position: relative;
	}

	.review-area .star-area .star-list li i {
		margin-right: -3px;
		-webkit-transition: all .3s linear;
		-o-transition: all .3s linear;
		transition: all .3s linear;
		cursor: pointer;
	}

	.review-area .star-area .star-list li::after {
		position: absolute;
		content: "||";
		top: 0px;
		right: -18px;
	}

	.review-area .star-area .star-list li:last-child::after {
		display: none;
	}

	.review-area .star-area .star-list li.active i {
		color: #fece37;
	}

	.review-area .star-area .star-list li:hover i {
		color: #fece37;
	}

	.write-comment-area {
		padding-top: 30px;
	}

	.write-comment-area input {
		width: 100%;
		height: 55px;
		background: #fff;
		color: #555;
		border: 1px solid rgba(0, 0, 0, 0.2);
		padding: 0px 20px;
		font-size: 14px;
		margin-bottom: 15px;
	}

	.write-comment-area input::-webkit-input-placeholder {
		/* WebKit browsers */
		color: #555;
	}

	.write-comment-area input:-moz-placeholder {
		/* Mozilla Firefox 4 to 18 */
		color: #555;
	}

	.write-comment-area input::-moz-placeholder {
		/* Mozilla Firefox 19+ */
		color: #555;
	}

	.write-comment-area input:-ms-input-placeholder {
		/* Internet Explorer 10+ */
		color: #555;
	}

	.write-comment-area textarea {
		width: 100%;
		height: 190px;
		background: #fff;
		color: #888888;
		border: 1px solid rgba(0, 0, 0, 0.2);
		padding: 10px 20px;
		font-size: 14px;
		margin-bottom: 15px;
		resize: none;
	}

	.write-comment-area textarea::-webkit-input-placeholder {
		/* WebKit browsers */
		color: #888888;
	}

	.write-comment-area textarea:-moz-placeholder {
		/* Mozilla Firefox 4 to 18 */
		color: #888888;
	}

	.write-comment-area textarea::-moz-placeholder {
		/* Mozilla Firefox 19+ */
		color: #888888;
	}

	.write-comment-area textarea:-ms-input-placeholder {
		/* Internet Explorer 10+ */
		color: #888888;
	}

	.write-comment-area .submit-btn {
		background: #0f78f2;
		border: 0px;
		color: #fff;
		padding: 14px 30px 11px;
		border-radius: 3px;
		font-size: 14px;
		font-weight: 600;
		border: 1px solid #0f78f2;
		cursor: pointer;
		-webkit-transition: all 0.3s ease-in;
		-o-transition: all 0.3s ease-in;
		transition: all 0.3s ease-in;
	}

	.write-comment-area .submit-btn:hover {
		background: none;
		color: #0f78f2;
	}

	.write-comment-area .submit-btn:focus {
		outline: 0px;
	}

	
</style>
@endsection
@section('content')


<section class="user-dashbord">
	<div class="container">
		<div class="row">
			@include('includes.user-dashboard-sidebar')
			<div class="col-lg-9">
				<div class="user-profile-details">
					<div class="order-history">
						<div class="header-area no-garis" >
							<h4 class="title" style="color:#000;">
								{{ $langg->lang277 }}
							</h4>
						</div>
						<div class="mr-table allproduct mt-4">
							<div class="table-responsiv">
								<table id="example" class="table table-hover dt-responsive" cellspacing="0"
									width="100%" style="font-size:12px!important">
									<thead>
										<tr>
											<th>{{ $langg->lang278 }}</th>
											<th>{{ $langg->lang279 }}</th>
											<th>{{ $langg->lang280 }}</th>
											<th>{{ $langg->lang281 }}</th>
											<th>{{ __("Konfirmasi Order") }}</th>
											<th>{{ __("Track Order") }}</th>
											{{-- <th>{{ __("Komplain Barang") }}</th> --}}
											<th>{{ $langg->lang282 }}</th>

										</tr>
									</thead>
									<tbody>
										@foreach($orders as $order)

												

										<tr>
											<td>
												{{$order->order_number}}
											</td>
											<td>
												{{date('d M Y',strtotime($order->created_at))}}
											</td>
											<td>
												{{$order->currency_sign}} @rp( round($order->pay_amount *
												$order->currency_value , 2) )

												
												
											</td>
											<td>
												<div class="order-status {{ $order->status }}">
													{{ucwords($order->status)}}
												</div>
											</td>
											
											<td style="text-align: center;">
												@if ($order->status == 'on delivery')
												<button type="button"
													data-confirm="{{route('user-confirm-order', $order->order_number)}}"
													class="btn btn-primary btn-sm btn-confirm"
													{{ $order->status == 'on delivery' ? null : 'disabled' }}>
													{{ __("Konfirmasi") }}
												</button>
												@else
												{{ '-' }}
												@endif
											</td>
											<td style="text-align: center;">
												@if ( ($order->status == 'on delivery' || $order->status == 'completed') && $order->no_resi <> '' )
													<button type="button"
														data-resi="{{ $order->no_resi }}"
														data-kurir="{{ $order->kurir }}"
														class="btn btn-primary btn-sm btn-track">
														{{ __("Lacak") }}
													</button>
												@else													
													{{ '-' }}
												@endif												
											</td>
											{{-- <td>
												<a href="{{ route('user-dmessage-index') }}" class="btn btn-sm btn-danger">
													{{ __('Komplain') }}
												</a>
											</td> --}}
											<td>
												<a href="{{route('user-order',$order->id)}}">
													{{ $langg->lang283 }}
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

<div class="modal fade" id="confirm-order" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			{{-- <div class="submit-loader">
					<img src="{{asset('assets/images/'.$gs->admin_loader)}}" alt="">
		</div> --}}
		<div class="modal-header d-block text-center">
			<h4 class="modal-title d-inline-block">{{ _("Konfirmasi Order") }}</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>

		<!-- Modal body -->
		<div class="modal-body">
			<p class="text-center">{{ $langg->lang545 }}</p>
			<p class="text-center">{{ $langg->lang546 }}</p>
		</div>

		<!-- Modal footer -->
		<div class="modal-footer justify-content-center">
			<a href="{{ route('user-dmessage-index') }}" class="btn btn-sm btn-danger">
				{{ __('Komplain') }}
			</a>			
			<button type="button" class="btn btn-default" data-dismiss="modal">{{ $langg->lang547 }}</button>
			<a href="#" class="btn btn-success btn-ok btn-confirm">{{ _("Konfirmasi") }}</a>
		</div>

	</div>
</div>
</div>
<div class="modal fade" id="order-rating" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true"
	style="top: 4vh;">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			{{-- <div class="submit-loader">
						<img src="{{asset('assets/images/'.$gs->admin_loader)}}" alt="">
		</div> --}}
		<div class="modal-header d-block text-center">
			<h4 class="modal-title d-inline-block">Ulasan Produk</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>

		<!-- Modal body -->
		<div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto;">
			<div class="row" style="padding: 25px;">
				@if ($cart <> '')
					@foreach($cart->items as $product)
					<div class="col-md-2">
						<img src="{{ asset('assets/images/products/'.$product["item"]["photo"]) }}"
							style="width: 5.75rem;height: 5.75rem;" alt="">
					</div>
					<div class="col-md-10">
						<h6>{{ strtoupper($product['item']['name']) }}</h6>
						<div class="review-area">
							<div class="star-area">
								<ul class="star-list">
									<li class="stars" data-val="1">
										<i class="fas fa-star"></i>
									</li>
									<li class="stars" data-val="2">
										<i class="fas fa-star"></i>
										<i class="fas fa-star"></i>
									</li>
									<li class="stars" data-val="3">
										<i class="fas fa-star"></i>
										<i class="fas fa-star"></i>
										<i class="fas fa-star"></i>
									</li>
									<li class="stars" data-val="4">
										<i class="fas fa-star"></i>
										<i class="fas fa-star"></i>
										<i class="fas fa-star"></i>
										<i class="fas fa-star"></i>
									</li>
									<li class="stars active" data-val="5">
										<i class="fas fa-star"></i>
										<i class="fas fa-star"></i>
										<i class="fas fa-star"></i>
										<i class="fas fa-star"></i>
										<i class="fas fa-star"></i>
									</li>
								</ul>
							</div>
						</div>
						<div class="write-comment-area">
							<div class="gocover"
								style="background: url({{ asset('assets/images/'.$gs->loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
							</div>
							<form id="reviewform" action="{{route('front.review.submit')}}"
								data-href="{{ route('front.reviews',@$product['item']['id']) }}" method="POST">
								{{ csrf_field() }}
								<input type="hidden" id="rating" name="rating" value="5">
								<input type="hidden" name="user_id" value="{{Auth::guard('web')->user()->id}}">
								<input type="hidden" name="product_id" value="{{@$product['item']['id']}}">
								<div class="row">
									<div class="col-lg-12">
										<textarea name="review" placeholder="{{ $langg->lang99 }}"
											required=""></textarea>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<button class="submit-btn" type="button">{{ $langg->lang100 }}</button>
									</div>
								</div>
							</form>
						</div>
					</div>
					@endforeach
					@endif
			</div>
		</div>

		<!-- Modal footer -->
		<div class="modal-footer">
			<button type="button" class="btn btn-default"
				data-dismiss="modal">{{ $langg->lang547.' '.$langg->lang98 }}</button>
		</div>

	</div>
</div>
</div>

<div class="modal fade" id="lacak-paket" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body">
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default"
					data-dismiss="modal">{{ $langg->lang547 }}</button>
			</div>		
		</div>
	</div>
</div>
@endsection

@section('scripts')

<script>
	$(document).ready(function () {
		// $('div.modal#lacak-paket').modal('show');
		$('.btn-confirm').on('click',function(){
			$('div.modal#confirm-order').modal('show');
			$('div.modal#confirm-order').find('a.btn-confirm').attr('href', $(this).data('confirm'));
		});

		$(document).on('click', '.btn-track', function () {
			$.post('{{ route("user-order-tracking") }}', {resi : $(this).data('resi'), kurir: $(this).data('kurir'), _token: "{{ csrf_token() }}",},
				function (data, textStatus, jqXHR) {
					$('div.modal#lacak-paket .modal-body').html(data);

					$('div.modal#lacak-paket').modal('show');
				},
			);
		});


		// var table =  $('#example').DataTable({

        //     "paging": true,
        //     "ordering": true,
        //     "filter": true,
        //     "info": false,
        //     "searching": true,
        //     "dom": '<lf<t>ip>',
        //     // "dom": '',
        //     "language": {
        //         "paginate": {
        //             "previous": "<",
        //             "next": ">"

        //         },
        //         "zeroRecords": "Pencarian tidak ditemukan",
        //         search: "Pencarian"
		// 	},
		// 	"order": [[1, "desc"]]

        // });
	});
</script>

@if (Session::get("konfirmasi-order-sukses"))
<script>
	toastr.success('{{ Session::get("konfirmasi-order-sukses") }}');
</script>
<script type="text/javascript">
	$('div.modal#order-rating').modal('show');


			$(document).on('click', '.submit-btn', function (e) {
				e.preventDefault();
				$.post($(this).closest('form').attr('action'), $(this).closest('form').serialize(),
					function (data, textStatus, jqXHR) {
						if (data['errors']) {
							toastr.error(data['errors'][0]);
						}else{
							toastr.success(data[0]);
						}
					},
				);
			});
</script>
@elseif (Session::get("konfirmasi-order-gagal"))
<script>
	toastr.danger('{{ Session::get("konfirmasi-order-gagal") }}');
</script>
@endif

@endsection