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
                                {{ __('Tambah Alamat') }}
                            </h4>
                        </div>
                        <div class="edit-info-area">

                            <div class="body">
                                <div class="edit-info-area-form">
                                    <form action="{{route('user-address-store')}}" method="POST">

                                        {{ csrf_field() }}

                                        @include('includes.form-success')
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <input name="shipping_name" type="text" class="input-field"
                                                    placeholder="{{ __('Label Alamat') }}" required=""
                                                    value="">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <input name="receiver_name" type="text" class="input-field"
                                                    placeholder="{{ __('Nama Penerima') }}" required=""
                                                    value="">
                                            </div>                                            
                                            <div class="col-lg-6">
                                                <input name="shipping_phone" type="number" class="input-field"
                                                    placeholder="{{ $langg->lang266 }}" required=""
                                                    value="">
                                            </div>
                                            {{-- <div class="col-lg-6">
                                                <input name="fax" type="text" class="input-field"
                                                    placeholder="{{ $langg->lang267 }}" value="{{ $alamat->fax }}">
                                            </div> --}}
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <select class="input-field" name="shipping_provinces">
                                                    <option value="">Pilih Provinsi</option>
                                                    @foreach ($provinces as $data)
                                                    <option value="{{ $data->id }}">
                                                        {{ $data->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-lg-6">
                                                <select class="input-field" name="shipping_city">
                                                    <option value="">Pilih Kota / Kabupaten</option>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <select class="input-field" name="shipping_districts">
                                                    <option value="">Pilih Kecamatan</option>
                                                </select>
                                            </div>

                                            <div class="col-lg-6">
                                                <select class="input-field" name="shipping_zip">
                                                    <option value="">Pilih Kode Pos</option>
                                                </select>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <textarea class="input-field" name="shipping_address" required=""
                                                    placeholder="{{ $langg->lang270 }}"></textarea>
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
    $('select[name="shipping_provinces"]').on('change', function(e) {
                var kota = [];
                if ($(this).val() !== '') {
                    $('#preloader').show();
                    $.getJSON('{{ route("front.city")}}', {province : $(this).val()},
                        function (response, textStatus, jqXHR) {
                            data = response.data;
                            $('select[name="shipping_city"]').empty();
                            $('select[name="shipping_city"]').append('<option value="">Pilih Kota / Kabupaten</option>');
                            if (data.length !== 0) {
                                $.each(data, function (i, v) { 
                                    var option = '<option value="'+v.id+'">'+v.name+'</option>';

                                    kota[i] = option;

                                });
                                $('select[name="shipping_city"]').append(kota);
                            }
                        }
                    );
                    $('#preloader').hide();
                }
        });

        $('select[name="shipping_city"]').on('change', function(e) {
                var kecamatan = [];
                if ($(this).val() !== '') {
                    $('#preloader').show();
                    $.getJSON('{{ route("front.district")}}', {city : $(this).val()},
                        function (response, textStatus, jqXHR) {
                            data = response.data;
                            $('select[name="shipping_districts"]').empty();
                            $('select[name="shipping_districts"]').append('<option value="">Pilih Kecamatan</option>');
                            if (data.length !== 0) {
                                $.each(data, function (i, v) { 
                                    var option = '<option value="'+v.id+'">'+v.name+'</option>';

                                    kecamatan[i] = option;

                                });
                                $('select[name="shipping_districts"]').append(kecamatan);
                            }
                        }
                    );
                    $('#preloader').hide();
                }
        });    

        $('select[name="shipping_districts"]').on('change', function(e) {
                var zip = [];
                if ($(this).val() !== '') {                    
                    kota = $('select[name="shipping_city"] option:selected').text().replace('KABUPATEN', '').replace('KOTA', '');
                    $.post('{{ route("front.zip")}}', {
                            city : kota, 
                            district: $('select[name="shipping_districts"] option:selected').text(),
                            province: $('select[name="shipping_provinces"] option:selected').val(),
                            _token: '{!! csrf_token() !!}',
                        },
                        function (response, textStatus, jqXHR) {
                            data = response.data;
                            $('select[name="shipping_zip"]').empty();
                            $('select[name="shipping_zip"]').append('<option value="">Pilih Kode Pos</option>');
                            if (data.length !== 0) {
                                $.each(data, function (i, v) { 
                                    var option = '<option value="'+v.postal_code+'">'+v.postal_code+'</option>';

                                    zip[i] = option;

                                });
                                $('select[name="shipping_zip"]').append(zip);
                            }
                        }
                    );
                    $('#preloader').hide();
                }
        });             

</script>

@endsection