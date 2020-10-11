<section class="root">
    <figure>
        <img src="{{ asset('assets/images/tracking.svg') }}" alt="">
        <figcaption>
            <h4>Tracking Details</h4>
            <h6>Nomor Resi</h6>
            <h2>{{ $resi }}</h2>
        </figcaption>
    </figure>
    @if ($result['rajaongkir']->status->code === 200)
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>{{ __('Kurir') }}</th>
                        <th>{{ __('Paket Layanan') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Penerima') }}</th>
                        <th>{{ __('Tanggal Diterima')}}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ strtoupper($result['rajaongkir']->result->summary->courier_code) }}</td>
                        <td>{{ strtoupper($result['rajaongkir']->result->summary->service_code) }}</td>
                        <td>{{ strtoupper($result['rajaongkir']->result->delivery_status->status) }}</td>
                        <td>{{ strtoupper($result['rajaongkir']->result->delivery_status->pod_receiver) }}</td>
                        <td>{{ $result['rajaongkir']->result->delivery_status->pod_date .' '. $result['rajaongkir']->result->delivery_status->pod_time }}</td>
                    </tr>
                </tbody>
            </table>    
        <div class="order-track">
        @foreach ($result['rajaongkir']->result->manifest as $manifest)
                <div class="order-track-step">
                    <div class="order-track-status">
                        <span class="order-track-status-dot"></span>
                        <span class="order-track-status-line"></span>
                    </div>
                    <div class="order-track-text">
                        <p class="order-track-text-stat">{{ $manifest->manifest_description }} </p>
                        <span class="order-track-text-sub">{{ $manifest->manifest_date . '  '. $manifest->manifest_time }}</span>
                    </div>
                </div>
        @endforeach
        </div>
    @else
        {{ $result['rajaongkir']->status->description }}
    @endif
</section>