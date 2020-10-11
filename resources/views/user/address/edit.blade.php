@extends('layouts.front')
@section('content')

<section class="user-dashbord">
    <div class="container">
        <div class="row">
            @include('includes.user-dashboard-sidebar')
            <div class="col-lg-9">
                <div class="user-profile-details">
                    <div class="account-info">
                        <div class="header-area">
                            <h4 class="title">
                                {{ __('Ubah Alamat') }}
                            </h4>
                        </div>
                        <div class="edit-info-area">

                            <div class="body">
                                <div class="edit-info-area-form">
                                    <form action="{{route('user-address-update', [$alamat->id])}}" method="POST">

                                        {{ csrf_field() }}

                                        @include('includes.form-success')
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <input name="name" type="text" class="input-field"
                                                    placeholder="{{ __('Label Alamat') }}" required=""
                                                    value="{{ $alamat->address_name }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <input name="receiver" type="text" class="input-field"
                                                    placeholder="{{ __('Nama Penerima') }}" required=""
                                                    value="{{ $alamat->receiver }}">
                                            </div>                                            
                                            <div class="col-lg-6">
                                                <input name="phone" type="text" class="input-field"
                                                    placeholder="{{ $langg->lang266 }}" required=""
                                                    value="{{ $alamat->phone }}">
                                            </div>
                                            {{-- <div class="col-lg-6">
                                                <input name="fax" type="text" class="input-field"
                                                    placeholder="{{ $langg->lang267 }}" value="{{ $alamat->fax }}">
                                            </div> --}}
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <select class="input-field" name="provinces">
                                                    <option value="">Pilih Provinsi</option>
                                                    @foreach ($provinces as $data)
                                                    <option value="{{ $data->id }}"
                                                        {{ $alamat->province_id == $data->id ? 'selected' : '' }}>
                                                        {{ $data->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-lg-6">
                                                <select class="input-field" name="city">
                                                    <option value="">Pilih Kota / Kabupaten</option>
                                                    @if ($regencies <> [])
                                                        @foreach ($regencies as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ $alamat->city_id == $item->id ? 'selected' : '' }}>
                                                            {{ $item->name }}</option>
                                                        @endforeach
                                                        @endif
                                                </select>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <select class="input-field" name="districts">
                                                    <option value="">Pilih Kecamatan</option>
                                                    @if ($districts <> [])
                                                        @foreach ($districts as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ $alamat->district_id == $item->id ? 'selected' : '' }}>
                                                            {{ $item->name }}</option>
                                                        @endforeach
                                                        @endif
                                                </select>
                                            </div>

                                            <div class="col-lg-6">
                                                <select class="input-field" name="zip">
                                                    <option value="">Pilih Kode Pos</option>

                                                    @if ($zip !==null OR $zip!==null)
                                                    <option value="{{$zip}}" selected>{{$zip}}</option>
                                                    @endif
                                                </select>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <textarea class="input-field" name="address" required=""
                                                    placeholder="{{ $langg->lang270 }}">{{ $alamat->address }}</textarea>
                                            </div>
                                        </div>

                                        <div class="form-links">
                                            <button class="submit-btn" type="submit">{{ $langg->lang271 }}</button>
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

@section('scripts')

<script type="text/javascript">
    $('select[name="provinces"]').on('change', function(e) {
                var kota = [];
                if ($(this).val() !== '') {
                    $('#preloader').show();
                    $.getJSON('{{ route("front.city")}}', {province : $(this).val()},
                        function (response, textStatus, jqXHR) {
                            data = response.data;
                            $('select[name="city"]').empty();
                            $('select[name="city"]').append('<option value="">Pilih Kota / Kabupaten</option>');
                            if (data.length !== 0) {
                                $.each(data, function (i, v) { 
                                    var option = '<option value="'+v.id+'">'+v.name+'</option>';

                                    kota[i] = option;

                                });
                                $('select[name="city"]').append(kota);
                            }
                        }
                    );
                    $('#preloader').hide();
                }
        });

        $('select[name="city"]').on('change', function(e) {
                var kecamatan = [];
                if ($(this).val() !== '') {
                    $('#preloader').show();
                    $.getJSON('{{ route("front.district")}}', {city : $(this).val()},
                        function (response, textStatus, jqXHR) {
                            data = response.data;
                            $('select[name="districts"]').empty();
                            $('select[name="districts"]').append('<option value="">Pilih Kecamatan</option>');
                            if (data.length !== 0) {
                                $.each(data, function (i, v) { 
                                    var option = '<option value="'+v.id+'">'+v.name+'</option>';

                                    kecamatan[i] = option;

                                });
                                $('select[name="districts"]').append(kecamatan);
                            }
                        }
                    );
                    $('#preloader').hide();
                }
        });    

        $('select[name="districts"]').on('change', function(e) {
                var zip = [];
                if ($(this).val() !== '') {
                    $('#preloader').show();
                    kota = $('select[name="city"] option:selected').text().replace('KABUPATEN', '').replace('KOTA', '');
                    $.post('{{ route("front.zip")}}', {
                            city : kota, 
                            district: $('select[name="districts"] option:selected').text(),
                            province: $('select[name="provinces"] option:selected').val(),
                            _token: '{!! csrf_token() !!}',
                        },
                        function (response, textStatus, jqXHR) {
                            data = response.data;
                            $('select[name="zip"]').empty();
                            $('select[name="zip"]').append('<option value="">Pilih Kode Pos</option>');
                            if (data.length !== 0) {
                                $.each(data, function (i, v) { 
                                    var option = '<option value="'+v.postal_code+'">'+v.postal_code+'</option>';

                                    zip[i] = option;

                                });
                                $('select[name="zip"]').append(zip);
                            }
                        }
                    );
                    $('#preloader').hide();
                }
        });             

</script>

@endsection