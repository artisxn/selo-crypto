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
                                Notifikasi
                                <a href="{{ route('user-notif-clear') }}" style="float: right;" class="btn btn-success" type="button">Bersihkan Notifikasi</a>
                            </h4>
                            
                        </div>
                        <div class="mr-table allproduct message-area  mt-4">
                            @include('includes.form-success')
                            <div class="table-responsiv">
                                <table id="example" class="table table-hover dt-responsive" cellspacing="0"
                                    width="100%">
                                    <thead>
                                        <tr>
                                            <th>{{ _("No") }}</th>
                                            <th>{{ _("Judul") }}</th>
                                            <th>{{ _("Isi Pesan") }}</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; ?>
                                        @foreach($notif as $data)
                                        <tr class="conv">
                                            <td>{{ $no++ }}</td>
                                            <td>{{$data->header}}</td>
                                            <td>{{$data->messages}}</td>
                                            <td>
                                                @if ($data->invoice_id <> '' && $data->invoice->id <> '')
                                                    @if($data->invoice->is_subscription <> 1)
                                                        <a href="{{route('user-invoice-detail', $data->invoice_id)}}">Detail</a>
                                                    @endif
                                                @elseif ($data->order_id <> '' && $data->order->id <> '')
                                                <a href="{{route('user-order', $data->order_id)}}">Detail</a>
                                                @elseif($data->conversation_id <> '')
                                                <a href="{{route('user-message-show',$data->conversation_id)}}">Detail</a>
                                                
                                                @endif
                                            </td>

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
    </div>
</section>

@endsection