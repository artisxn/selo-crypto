@extends('layouts.vendor')

@section('content')

<div class="content-area">
            <div class="mr-breadcrumb">
              <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">Tambah Produk Dropship</h4>
                    <ul class="links">
                      <li>
                        <a href="{{ route('vendor-dashboard') }}">{{ $langg->lang441 }}</a>
                      </li>

                      <li>
                        <a href="#">Semua Produk Dropship</a>
                      </li>
                      <li>
                        <a href="{{ route('vendor-dropship-create') }}">Tambah Produk Dropship</a>
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
                          Cari Produk
                      </h2>
                    </div>
                  </div>
                </div>
              </div>
              <div class="ap-product-categories">
                <div class="row">
                    <div class="col-md-12">
                    <form action="{{route('vendor-dropship-store')}}" method="post">
                    {{csrf_field()}}
                    <div class="form-group">
                      <select class="itemName form-control" name="itemName[]" multiple="multiple" required></select>
                    </div>
                    <div class="form-group">
                      <button class="add-btn" type="submit">Tambah Produk Dropship</button>
                    </div>
                    </form>
                    </div>
                </div>
              </div>
            </div>
          </div>


@endsection