@extends('layouts.front')
@section('content')


<section class="user-dashbord">
    <div class="container">
        <div class="row">
            @include('includes.user-dashboard-sidebar')
            <div class="col-lg-9">
                <div class="width-60">
                    <p><span style="font-weight:bold;">{{$langg->lang233}}</span> <br>Anda harus mempunyai akun EDCCash untuk mulai berjualan</p>
                    @if ($message = Session::get('msg'))
                    <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                    @endif
                    <form class="form" action="{{route('user-post-number')}}" method="POST">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control"
                        aria-describedby="emailHelp" value="{{ $user->email }}" placeholder="Email harus sama dengan akun EDC Cash" readonly>
                            <small id="emailHelp" class="form-text text-muted" style="color:#EF4623!important;">Email EDC Strore harus sama dengan email akun EDC Cash</small>
                        </div>
                        <button type="submit" class="btn btn-orange btn-sm btn-block" style="width:40%;">Submit</button>
                    </form>
                </div>
                <hr>
                <p>Belum punya akun EDCCash?</p>
                <a href="https://edccash.com/Account/SK" class="btn btn-orange btn-sm btn-block" style="width:24%;">Daftar EDCCash</a>
                <!-- <div class="user-profile-details">
                    <div class="order-history">
                        <div class="header-area">
                            <h4 class="title">
                                EDC Cash
                            </h4>
                        </div>
                        <div class="mr-table allproduct mt-4">
                            @if ($message = Session::get('msg'))
                            <div class="alert alert-danger alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ $message }}</strong>
                            </div>
                            @endif
                            <div class="table-responsiv">
                                <form class="form" action="{{route('user-post-number')}}" method="POST">
                                    {{ csrf_field() }}
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control"
                                    aria-describedby="emailHelp" value="{{ $user->email }}" placeholder="Email harus sama dengan akun EDC Cash" readonly>
                                        <small id="emailHelp" class="form-text text-muted">Email EDC Strore harus sama dengan email akun EDC Cash</small>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>

                                <small>Note: Klik <u><a href="https://edccash.com/Account/SK">register</a></u> untuk
                                    membuat akun EDC Cash anda</small>
                            </div>
                        </div>
                    </div>
                </div> -->

                <!-- @if($gs->reg_vendor == 1)
                <div class="mt-4">
                  <div class="col-lg-12 text-center">
                    <a href="{{ route('user-package') }}" class="mybtn1 lg">
                      <i class="fas fa-dollar-sign"></i>
                      {{ Auth::user()->is_vendor == 1 ? $langg->lang233 : (Auth::user()->is_vendor == 0 ? $langg->lang233 : $langg->lang237) }}
                    </a>
                  </div>
                </div>
                @endif                       -->
            </div>
        </div>

    
    </div>
</section>
@endsection