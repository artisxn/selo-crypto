@extends('layouts.vendor')
@section('styles')

<link href="{{asset('assets/vendor/css/product.css')}}" rel="stylesheet" />
<link href="{{asset('assets/admin/css/jquery.Jcrop.css')}}" rel="stylesheet" />
<link href="{{asset('assets/admin/css/Jcrop-style.css')}}" rel="stylesheet" />
@endsection
@section('content')

<div class="content-area">
	<div class="mr-breadcrumb">
		<div class="row">
			<div class="col-lg-12">
		
				<ul class="links">
					<li>
						<a href="{{ route('vendor-dashboard') }}">{{ $langg->lang441 }}</a>
					</li>
					<li>
						<a href="javascript:;"> Edit Vendor Profil </a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="add-product-content">
		<div class="row">
			<div class="col-lg-12">
				<div class="product-description">
					<div class="body-area">
          @include('includes.form-success')
          @if(Session::has('msg'))
            <p class="alert {{ Session::get('alert') == 'success' ?  'alert-success' : 'alert-danger' }}">{{ Session::get('msg') }}</p>
          @endif
            <form action="{{route('vendor-profile-store')}}"  method="POST">
            {{csrf_field()}}
            <div class="row">
								<div class="col-lg-4">
                <div class="left-area">
										<h4 class="heading">Nama Toko</h4>
									</div>
								</div>
								<div class="col-lg-7">
									<input type="text" class="input-field" 
										name="shop_name" required="" value="{{$data->shop_name}}">
								</div>
							</div>

              <div class="row">
								<div class="col-lg-4">
                <div class="left-area">
										<h4 class="heading">Pemilik Toko</h4>
									</div>
								</div>
								<div class="col-lg-7">
									<input type="text" class="input-field" 
										name="owner_name" required="" value="{{$data->owner_name}}">
								</div>
							</div>

              <div class="row">
								<div class="col-lg-4">
                <div class="left-area">
										<h4 class="heading">Nomer Toko</h4>
									</div>
								</div>
								<div class="col-lg-7">
									<input type="text" class="input-field" 
										name="shop_number" required="" value="{{$data->shop_number}}">
								</div>
							</div>

              <div class="row">
								<div class="col-lg-4">
                <div class="left-area">
										<h4 class="heading">Alamat Toko</h4>
									</div>
								</div>
								<div class="col-lg-7">
									<input type="text" class="input-field" 
										name="shop_address" required="" value="{{$data->shop_address}}">
								</div>
							</div>

              <div class="row">
								<div class="col-lg-4">
                <div class="left-area">
										<h4 class="heading">Nomor Registrasi</h4>
									</div>
								</div>
								<div class="col-lg-7">
									<input type="text" class="input-field" 
										name="reg_number" required="" value="{{$data->reg_number}}">
								</div>
							</div>

              <div class="row">
								<div class="col-lg-4">
                <div class="left-area">
										<h4 class="heading">Nama Rekening</h4>
									</div>
								</div>
								<div class="col-lg-7">
									<input type="text" class="input-field" 
										name="rekening_name" required="" value="{{$data->rekening_name}}">
								</div>
							</div>

              <div class="row">
								<div class="col-lg-4">
                <div class="left-area">
										<h4 class="heading">Nomer Rekening</h4>
									</div>
								</div>
								<div class="col-lg-7">
									<input type="text" class="input-field" 
										name="rekening_no" required="" value="{{$data->rekening_no}}">
								</div>
							</div>

							@if (Auth::user()->subscribes->is_dropship === 1)
              <div class="row">
								<div class="col-lg-4">
                <div class="left-area">
										<h4 class="heading">Nama Domain</h4>
									</div>
								</div>
								<div class="col-lg-7">
									<input type="text" class="input-field" 
										name="domain_name" required="" value="{{$data->domain_name}}"> 
								</div>
							</div>									
							@endif

              <div class="row">
								<div class="col-lg-4">
                <div class="left-area">
										<h4 class="heading">Pesan</h4>
									</div>
								</div>
								<div class="col-lg-7">
                  <textarea name="shop_message" id="" cols="30" rows="3"class="form-control">{{$data->shop_message}}</textarea>
								</div>
							</div>

              <div class="row">
								<div class="col-lg-4">
                <div class="left-area">
										<!-- <h4 class="heading">Pesan</h4> -->
									</div>
								</div>
								<div class="col-lg-7">
                  <button class="btn btn-primary" type="submit">Save</button>
								</div>
							</div>            
            </form>
          </div>
				</div>
			</div>
		</div>
	</div>
</div>


@endsection