@extends('layouts.front')
@section('content')


<section class="user-dashbord">  
  <div class="container">
    <div class="row">
      @include('includes.vendor-dashboard-seidebar')
      <div class="col-lg-9">
        @if($user->checkWarning())
        <div class="alert alert-danger validation text-center">
                <h3>{{ $user->displayWarning() }} </h3> <a href="{{ route('vendor-warning',$user->verifies()->where('admin_warning','=','1')->orderBy('id','desc')->first()->id) }}"> {{$langg->lang803}} </a>
        </div>
        @endif


        @include('includes.form-success')

        <a href="{{route('vendor-order-index')}}" style="color:#000!important;display:block;margin-bottom:10px;">
            <div class="card" style="border:1px solid #FCB415!important;">
                <div class="card-body" style="padding:10px!important;">
                    <span style="float:left;font-weight:bold!important;">{{ $langg->lang465 }}</span>
                    <span class="number" style="float:right;color:#EF4623!important;font-weight:bold!important;">{{ count($pending) }}</span>
                </div>
            </div>  
        </a>

        <a href="{{route('vendor-order-index')}}" style="color:#000!important;display:block;margin-bottom:10px;">
            <div class="card" style="border:1px solid #FCB415!important;">
                <div class="card-body" style="padding:10px!important;">
                    <span style="float:left;font-weight:bold!important;">{{ $langg->lang466 }}</span>
                    <span class="number" style="float:right;color:#EF4623!important;font-weight:bold!important;">{{ count($processing) }}</span>
                </div>
            </div>
        </a>

        <a href="{{route('vendor-order-index')}}" style="color:#000!important;display:block;margin-bottom:10px;">
            <div class="card" style="border:1px solid #FCB415!important;">
                <div class="card-body" style="padding:10px!important;">
                    <span style="float:left;font-weight:bold!important;">{{ $langg->lang467 }}</span>
                    <span class="number" style="float:right;color:#EF4623!important;font-weight:bold!important;">{{ count($completed) }}</span>
                </div>
            </div>
        </a>

        <a href="{{route('vendor-order-index')}}" style="color:#000!important;display:block;margin-bottom:10px;">
            <div class="card" style="border:1px solid #FCB415!important;">
                <div class="card-body" style="padding:10px!important;">
                    <span style="float:left;font-weight:bold!important;">{{ $langg->lang468 }}</span>
                    <span class="number" style="float:right;color:#EF4623!important;font-weight:bold!important;">{{ count($user->products) }}</span>
                </div>
            </div>
        </a>

        <a href="#" style="color:#000!important;display:block;margin-bottom:10px;">
        <div class="card" style="border:1px solid #FCB415!important;">
            <div class="card-body" style="padding:10px!important;">
                <span style="float:left;font-weight:bold!important;">{{ $langg->lang469 }}</span>
                <span class="number" style="float:right;color:#EF4623!important;font-weight:bold!important;">{{ App\Models\VendorOrder::where('user_id','=',$user->id)->where('status','=','completed')->sum('qty') }}</span>
            </div>
        </div>
        </a>


        <a href="{{route('vendor-order-index')}}" style="color:#000!important;display:block;margin-bottom:10px;">
        <div class="card" style="border:1px solid #FCB415!important;">
            <div class="card-body" style="padding:10px!important;">
                <span style="float:left;font-weight:bold!important;">{{ $langg->lang465 }}</span>
                <span class="number" style="float:right;color:#EF4623!important;font-weight:bold!important;">{{ count($pending) }}</span>
            </div>
        </div>
        </a>
        
      </div>
    </div>
  </div>
</section>



@endsection