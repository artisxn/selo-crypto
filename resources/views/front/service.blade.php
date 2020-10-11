@if($ps->service == 1)

{{-- Info Area Start --}}
<section class="info-area" style="box-shadow: 0px 3px 6px #00000029;">
    <div class="container">

        @foreach($services->chunk(4) as $chunk)

        <div class="row">

            <div class="col-lg-12 p-0">
                <div class="info-big-box">
                    <div class="row">
                        @foreach($chunk as $service)
                        <div class="col-6 col-xl-3 p-0">
                            <div class="info-box">
                                <div class="icon">
                                    <img src="{{ asset('assets/images/services/'.$service->photo) }}">
                                </div>
                                <div class="info">
                                    <div class="details">
                                        <h4 class="title" style="color:#FEDD00!important;">{{ $service->title }}</h4>
                                        <p class="text" style="color:#FEDD00!important;">
                                            {!! $service->details !!}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>

        @endforeach

    </div>
</section>
{{-- Info Area End  --}}

@endif