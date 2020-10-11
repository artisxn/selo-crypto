@extends('layouts.vendor')

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ $langg->lang443 }}</h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('vendor-dashboard') }}">{{ $langg->lang441 }} </a>
                    </li>
                    <li>
                        <a href="javascript:;">{{ __('Penerimaan Pembayaran') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="product-area">
        <div class="row">
            <div class="col-lg-12">
                <div class="mr-table allproduct">
                    @include('includes.form-success')

                    <div class="table-responsiv">
                        <div class="gocover"
                            style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                        </div>
                        <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>{{ __('Nomor Order') }}</th>
                                    <th>{{ __('Kode Referensi') }}</th>
                                    <th>{{ __('Tanggal Penerimaan') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $payment)
                                    <tr>
                                        <td>{{ $payment->order->order_number }}</td>
                                        <td>{{ $payment->order->payment_vendor_reference }}</td>
                                        <td>{{ Carbon\Carbon::parse($payment->created_at)->format('d/m/Y H:i:s') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        $(function () {
            var table = $('#geniustable').DataTable({
               ordering: false
           });
        });
    </script>
@endsection