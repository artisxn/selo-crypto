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
                                EDC Cash TopUp
                            </h4>
                        </div>
                        <div class="mr-table allproduct mt-4">
                            <div class="table-responsiv">
                               <button class="btn btn-primary">TOPUP</button>
                            </div>
                        </div>
                    </div>
                </div>

                         
            </div>
        </div>
    </div>
</section>
@endsection