@extends('layouts.front')
@section('content')


<section class="user-dashbord">
    <div class="container">
        <div class="row">
            @include('includes.user-dashboard-sidebar')
            <div class="col-lg-9">
                <div class="user-profile-details">
                    <div class="order-history">
                        <div class="header-area">
                            <h4 class="title">
                                EDC Cash
                            </h4>
                        </div>
                        <div class="mr-table allproduct mt-4">
                            @if ($message = Session::get('msg'))
                            <div class="alert alert-success alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ $message }}</strong>
                            </div>
                            @endif                            
                            <div class="table-responsiv">
                                <form class="form" action="{{route('user-verikasi-number')}}" method="POST">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Verification Code</label>
                                        <input type="number" name="code_verification" maxlength="4" class="form-control"
                                            id="exampleInputEmail1" aria-describedby="emailHelp"
                                            placeholder="Input four numbers for verification code">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Verifikasi</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection