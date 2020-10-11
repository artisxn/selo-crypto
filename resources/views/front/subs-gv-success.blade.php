@extends('layouts.front')




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
                        <a href="{{ route('payment.return') }}">
                            {{ $langg->lang169 }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Area End -->







<section class="tempcart">

    @if(!empty($invoice))

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Starting of Dashboard data-table area -->
                <div class="content-box section-padding add-product-1">
                    <div class="top-area">
                        <div class="content">
                            <h4 class="heading">
                                {{ $langg->order_title }}
                            </h4>
                            <a href="{{ route('front.index') }}" class="link">{{ $langg->lang170 }}</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">

                            <div class="product__header">
                                <div class="row reorder-xs">
                                    <div class="col-lg-12">
                                        <div class="product-header-title" style="text-align: center;">
                                            <h2>{{ $langg->lang285 }} {{$invoice->invoice_number}}</h2>
                                        </div>
                                    </div>
                                    @include('includes.form-success')
                                    <div class="col-md-12" id="tempview">
                                        <div class="dashboard-content">
                                            <div class="view-order-page" id="print">
                                                <p class="order-date" style="text-align: center;">{{ $langg->lang301 }}
                                                    {{date('d-M-Y',strtotime($invoice->created_at))}}</p>
                                                <br>
                                                <iframe src="{!! $urlGV."&bank=1" !!}" width="100%;" height="700"></iframe>
                                                <br>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <h4 class="text-center">{{ $langg->lang308 }}</h4>
                                                        <thead>
                                                            <tr>

                                                                <th width="25%">{{ $langg->lang310 }}</th>
                                                                <th width="25%">{{ $langg->lang539 }}</th>
                                                                <th width="25%">{{ $langg->lang314 }}</th>
                                                                <th width="25%">{{ $langg->lang315 }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>{{ 'Subscription '.$userSubs->title }}</td>
                                                                <td>
                                                                    {{ $langg->lang413 }} {{$userSubs->days}} {{ $langg->lang403 }}
                                                                    <br>
                                                                    {{ _("Hightlight Produk: ".$userSubs->allowed_products) }}
                                                                </td>
                                                                <td>{{$userSubs->currency}} @rp($userSubs->price)</td>
                                                                <td>{{$userSubs->currency}} @rp($userSubs->price)</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- Ending of Dashboard data-table area -->
            </div>

            @endif

</section>

@endsection