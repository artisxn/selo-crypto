@extends('layouts.front')
@section('content')

<section class="user-dashbord">
    <div class="container">
        <div class="row">
            @include('includes.user-dashboard-sidebar')
            <div class="col-lg-9">
                <div class="user-profile-details no-garis">
                    <div class="account-info no-garis">
                        <div class="header-area no-garis">
                            <h4 class="title">
                                {{ $langg->lang272 }}
                            </h4>
                        </div>
                        <div class="edit-info-area">

                            <div class="body">
                                <div class="edit-info-area-form">
                                    <div class="gocover"
                                        style="background: url({{ asset('assets/images/'.$gs->loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                                    </div>
                                    <form id="userform" action="{{route('user-reset-submit')}}" method="POST"
                                        enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        @if(Auth::user()->password)
                                        <div class="form-group row">
                                            <label for="" class="col-sm-3 col-form-label">{{ $langg->lang273 }}</label>
                                            <div class="col-sm-9">
                                            <input type="password" name="cpass" class="form-control"
                                                    placeholder="Ketik disini" value="" required="" style="border:0px;">
                                            </div>
                                        </div>
                                        @endif
                                        <div class="form-group row">
                                            <label for="" class="col-sm-3 col-form-label">{{ $langg->lang274 }}</label>
                                            <div class="col-sm-9">
                                            <input type="password" name="newpass" class="form-control"
                                                    placeholder="Ketik disini" value="" required="" style="border:0px;">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="" class="col-sm-3 col-form-label">{{ $langg->lang275 }}</label>
                                            <div class="col-sm-9">
                                            <input type="password" name="renewpass" class="form-control"
                                                    placeholder="Ketik disini" value="" required="" style="border:0px;">
                                            </div>
                                        </div>

                                        <div class="form-links">
                                            <button class="btn btn-orange btn-sm" style="width:15%;border-radius:10px;" type="submit">{{ $langg->lang276 }}</button>
                                        </div>
                                        <!-- @include('includes.admin.form-both') -->
                                        <div class="alert alert-success validation" style="display: none;">
                                            <button type="button" class="close alert-close"><span>×</span></button>
                                                    <p class="text-left"></p> 
                                            </div>
                                            <div class="alert alert-danger validation" style="display: none;color:#EF4623;background-color:#fff;border:none;margin-left:-10px;">
                                            <button type="button" class="close alert-close"><span>×</span></button>
                                                    <ul class="text-left">
                                                    </ul>
                                            </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>    
            </div>
        </div>
    </div>
</section>

@endsection