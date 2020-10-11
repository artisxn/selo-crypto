@extends('layouts.vendor')

@section('content')

<div class="content-area">
            <div class="mr-breadcrumb">
              <div class="row">
                <div class="col-lg-12">
                <h4 class="heading">Dropship</h4>
										<ul class="links">
											<li>
												<a href="{{ route('vendor-dashboard') }}">{{ $langg->lang441 }} </a>
											</li>
											<li>
												<a href="javascript:;">Dropship </a>
											</li>
											<li>
												<a href="#">Blocked</a>
											</li>
										</ul>
                </div>
              </div>
            </div>
            <div class="add-product-content">
              <div class="row">
                <div class="col-lg-12">
                  <div class="product-description">
                    <div class="heading-area">
                      <h2 class="title">
                        Blocked
                      </h2>
                    </div>
                  </div>
                </div>
              </div>
              <div class="ap-product-categories">
                <center>
                    <b>
                    <h5>Anda Tidak Memiliki Akses !</h5>
                    <p>Untuk mengakses menu ini silahkan pilih paket dengan fitur dropship</p>
                    </b>
                    <a href="{{ route('user-package') }}" class="btn btn-danger">List Paket</a>
                </center>
              </div>
            </div>
          </div>

@endsection