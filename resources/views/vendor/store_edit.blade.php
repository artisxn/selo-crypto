@extends('layouts.vendor') 

@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">Setting Display Your Store</h4>
                <ul class="links">
                    <li>
                    <a href="{{ route('vendor-dashboard') }}">Dashboard </a>
                    </li>
                    <li>
                    <a href="{{ route('vendor-store-edit') }}">Your Store</a>
                    </li>
                </ul>
                </div>
            </div>
        </div>
        @include('includes.form-success')
        @if(Session::has('msg'))
        <p class="alert {{ Session::get('alert') == 'success' ?  'alert-success' : 'alert-danger' }}">{{ Session::get('msg') }}</p>
        @endif
        <form action="{{route('vendor-store-update')}}"  method="POST" enctype="multipart/form-data">
        {{csrf_field()}}
        <div class="row justify-content-center">
            <div class="col-lg-3">
            <div class="left-area">
                <h4 class="heading">Current Logo Image</h4>
            </div>
            </div>
            <div class="col-lg-6">
            <div class="img-upload">
                @if(@Auth::user()->store_color->logo)
                <div id="image-preview" class="img-preview" style="background: url({{url('assets/images/store/'.Auth::user()->store_color->logo)}}); height: 100px">
                @else
                <div id="image-preview" class="img-preview" style="background: url({{url('assets/images/store/')}}); height: 100px">
                @endif
                    <label for="image-upload" class="img-label" id="image-label"><i class="icofont-upload-alt"></i>Upload Image</label>
                    <input type="file" name="logo" class="img-upload" id="image-upload">
                </div>
            </div>
            Ukuran : 150 x 60
            <br>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-3">
                <div class="left-area">
                    <h4 class="heading">Header Color</h4>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <div class="input-group colorpicker-component cp">
                    <input type="text" class="input-field color-field" name="header_color" value="{{@Auth::user()->store_color->header_color ? Auth::user()->store_color->header_color : ''}}"  class="form-control cp"  />
                        <span class="input-group-addon"><i></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-3">
                <div class="left-area">
                    <h4 class="heading">Primary Color</h4>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <div class="input-group colorpicker-component cp">
                    <input type="text" class="input-field color-field" name="primary_color" value="{{@Auth::user()->store_color->primary_color ? Auth::user()->store_color->primary_color : ''}}"  class="form-control cp"  />
                        <span class="input-group-addon"><i></i></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-3">
                <div class="left-area">
                    <h4 class="heading">Footer Color</h4>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <div class="input-group colorpicker-component cp">
                    <input type="text" class="input-field color-field" name="copyright_color" value="{{@Auth::user()->store_color->copyright_color ? Auth::user()->store_color->copyright_color : ''}}"  class="form-control cp"  />
                        <span class="input-group-addon"><i></i></span>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="row justify-content-center">
            <div class="col-lg-3">
                <div class="left-area">
                    <h4 class="heading">Menu Color</h4>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <div class="input-group colorpicker-component cp">
                    <input type="text" class="input-field color-field" name="menu_color" value="{{@Auth::user()->store_color->menu_color ? Auth::user()->store_color->menu_color : ''}}"  class="form-control cp"  />
                        <span class="input-group-addon"><i></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-3">
                <div class="left-area">
                    <h4 class="heading">Menu-hover Color</h4>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <div class="input-group colorpicker-component cp">
                    <input type="text" class="input-field color-field" name="menuhover_color" value="{{@Auth::user()->store_color->menuhover_color ? Auth::user()->store_color->menuhover_color : ''}}"  class="form-control cp"  />
                        <span class="input-group-addon"><i></i></span>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="row justify-content-center">
            <div class="col-lg-3">
            <div class="left-area">

            </div>
            </div>
            <div class="col-lg-6">
                <button class="btn btn-primary product-btn" type="submit">Save</button>
            </div>
        </div>
        </form>
    </div>
@endsection
