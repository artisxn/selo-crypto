@extends('layouts.front')

@section('styles')
<style>
	.item .beli {
		position: absolute;
		bottom: -8px;
		right: -15px;
		transform: translate(-50%, -50%);
		-ms-transform: translate(-50%, -50%);
		background-color: #EF4623;
		color: white;
		font-size: 16px;
		padding: 12px 18px;
		border: none;
		cursor: pointer;
		border-radius: 10px;
		text-align: center;
	}
</style>
@endsection

@section('content')

<!-- Breadcrumb Area Start -->
<div class="breadcrumb-area">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<ul class="pages">
					<li>
						<a href="{{ route('front.index') }}">
							{{ $langg->lang17 }}
						</a>
					</li>
					<li>
						<a href="{{ route('user-wishlists') }}">
							{{ $langg->lang168 }}
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<!-- Breadcrumb Area End -->

<!-- Wish List Area Start -->
<section class="sub-categori wish-list">
	<div class="container">


		@if(count($wishlists))
		<div class="right-area">
			@include('includes.filter')
			<div id="ajaxContent">
				<div class="row wish-list-area">

					@foreach($wishlists as $wishlist)

					@if(!empty($sort))


					<div class="col-lg-3 col-md-4 col-6 padding-8">
						<a href="{{ route('front.product', $wishlist->slug) }}" class="item">
							<div class="item-img">


								<img class="img-fluid"
									src="{{ $wishlist->photo ? asset('assets/images/products/'.$wishlist->photo):asset('assets/images/noimage.png') }}"
									alt="">
								<button class="add-to-cart beli" type="button"
									data-href="{{ route('product.cart.add', $wishlist->id) }}">Beli</button>
							</div>

							<div class="info">

								<h5 class="name">
									{{ strlen($wishlist->name) > 60 ? substr($wishlist->name, 0, 60) . '...' : $wishlist->name }}
								</h5>
								<h4><span class="price">{{ $wishlist->showPrice() }}</span></h4>
								{{-- <h5 style="margin-top: -15px;">
									<span class="coin"> {{ $wishlist->showPrice() }}
										<small>&nbsp;<del>{{ $wishlist->showPreviousPrice() }}</del></small>
									</span></h5> --}}
								<div class="stars">
									<div class="ratings">
										<div class="empty-stars"></div>
										<div class="full-stars"
											style="width:{{App\Models\Rating::ratings($wishlist->id)}}%"></div>
									</div>
								</div>
								<div class="location">
									{{ $wishlist->user->regency <> '' ? $wishlist->user->regency->name : '-' }}

									<span onclick="event.preventDefault()" class="wishlist-remove"
										data-href="{{ route('user-wishlist-remove', $wishlist->id) }}"
										data-toggle="tooltip" data-placement="right" title=""
										data-original-title="Hapus dari Wishlist"><i
											class="fa fa-trash width: 21px;height: 24px;"></i>
									</span>
								</div>
							</div>
						</a>
					</div>

					@else

					<div class="col-lg-3 col-md-4 col-6 padding-8">
						<a href="{{ route('front.product', $wishlist->slug) }}" class="item">
							<div class="item-img">


								<img class="img-fluid"
									src="{{ $wishlist->photo ? asset('assets/images/products/'.$wishlist->photo):asset('assets/images/noimage.png') }}"
									alt="">
								<button class="add-to-cart beli" type="button"
									data-href="{{ route('product.cart.add',$wishlist->id) }}">Beli</button>
							</div>

							<div class="info">

								<h5 class="name">
									{{ strlen($wishlist->name) > 60 ? substr($wishlist->name, 0, 60) . '...' : $wishlist->name }}
								</h5>
								<h4><span class="price">{{ $wishlist->showPrice() }}</span></h4>
								{{-- <h5 style="margin-top: -15px;">
									<span class="coin"> {{ $wishlist->showPrice() }}
										<small>&nbsp;<del>{{ $wishlist->showPreviousPrice() }}</del></small>
									</span></h5> --}}
								<div class="stars">
									<div class="ratings">
										<div class="empty-stars"></div>
										<div class="full-stars"
											style="width:{{App\Models\Rating::ratings($wishlist->id)}}%"></div>
									</div>
								</div>
								<div class="location">
									{{ $wishlist->user->regency <> '' ? $wishlist->user->regency->name : '-' }}

									<span onclick="event.preventDefault()" class="wishlist-remove"
										data-href="{{ route('user-wishlist-remove', $wishlist->id) }}"
										data-toggle="tooltip" data-placement="right" title=""
										data-original-title="Hapus dari Wishlist"><i
											class="fa fa-trash width: 21px;height: 24px;"></i>
									</span>
								</div>
							</div>
						</a>
					</div>
					@endif
					@endforeach

				</div>



				<div class="page-center category">
					{{ $wishlists->appends(['sort' => $sort])->links() }}
				</div>


			</div>
		</div>
		@else

		<div class="page-center">
			<h4 class="text-center">{{ $langg->lang60 }}</h4>
		</div>

		@endif

	</div>
</section>
<!-- Wish List Area End -->

@endsection

@section('scripts')

<script type="text/javascript">
	$("#sortby").on('change',function () {
        var sort = $("#sortby").val();
        window.location = "{{url('/user/wishlists')}}?sort="+sort;
		});
		
		// location.reload();
</script>

@endsection