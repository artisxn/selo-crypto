@extends('layouts.front')
@section('content')

<section class="user-dashbord">
    <div class="container">
      <div class="row">
        @include('includes.user-dashboard-sidebar')
<div class="col-lg-9">
                    <div class="user-profile-details no-garis">
                        <div class="account-info no-garis">
                            <div class="header-area no-garis">
                                <h4 class="title">
                                    {{ $langg->lang262 }}
                                </h4>
                            </div>
                            <div class="edit-info-area">

                                <div class="body">
                                    <div class="edit-info-area-form">
                                        <div class="gocover"
                                            style="background: url({{ asset('assets/images/'.$gs->loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                                        </div>
                                        <form id="userform" action="{{route('user-profile-update')}}" method="POST"
                                            enctype="multipart/form-data">
    
                                            {{ csrf_field() }}
    
                                            @include('includes.admin.form-both')

                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-group row">
                                                        <label for="" class="col-sm-3 col-form-label">{{ $langg->lang264 }}</label>
                                                        <div class="col-sm-9">
                                                            <input name="name" type="text" class="form-control" style="border:0px;"
                                                            placeholder="{{ $langg->lang264 }}" required=""
                                                            value="{{ $user->name }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="" class="col-sm-3 col-form-label">{{ $langg->lang265 }}</label>
                                                        <div class="col-sm-9">
                                                        <input name="email" type="email" class="form-control" style="border:0px;background-color:#fff;"
                                                        placeholder="{{ $langg->lang265 }}" required=""
                                                        value="{{ $user->email }}" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="" class="col-sm-3 col-form-label">{{ $langg->lang266 }}</label>
                                                        <div class="col-sm-9">
                                                        <input name="phone" type="text" class="form-control" style="border:0px;"
                                                        placeholder="{{ $langg->lang266 }}" required=""
                                                        value="{{ $user->phone }}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="" class="col-sm-3 col-form-label">{{ $langg->lang267 }}</label>
                                                        <div class="col-sm-9">
                                                        <input name="fax" type="text"  class="form-control" style="border:0px;"
                                                        placeholder="{{ $langg->lang267 }}" value="{{ $user->fax }}">
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="" class="col-sm-3 col-form-label">Pilih Provinsi</label>
                                                        <div class="col-sm-9">
                                                            <select class="form-control" style="border:0px;" name="provinces">
                                                                <option value="">Pilih Provinsi</option>
                                                                @foreach ($provinces as $data)
                                                                    <option value="{{ $data->id }}" {{ $user->provinces == $data->id ? 'selected' : '' }}>
                                                                        {{ $data->name }}
                                                                    </option>		
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="" class="col-sm-3 col-form-label">Pilih Kota / Kabupaten</label>
                                                        <div class="col-sm-9">
                                                            <select class="form-control" style="border:0px;" name="city">
                                                                <option value="">Pilih Kota / Kabupaten</option>
                                                                @if ($regencies <> [])
                                                                    @foreach ($regencies as $item)
                                                                        <option value="{{ $item->id }}" {{ $user->city_id == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="" class="col-sm-3 col-form-label">Pilih Kecamatan</label>
                                                        <div class="col-sm-9">
                                                            <select  class="form-control" style="border:0px;"  name="districts">
                                                                <option value="">Pilih Kecamatan</option>
                                                                @if ($districts <> [])
                                                                    @foreach ($districts as $item)
                                                                        <option value="{{ $item->id }}" {{ $user->districts == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="" class="col-sm-3 col-form-label">Pilih Kode Post</label>
                                                        <div class="col-sm-9">
                                                            <select class="form-control" style="border:0px;" name="zip">
                                                                <option value="">Pilih Kode Pos</option>

                                                                @if ($zip !==null OR $zip!==null)
                                                                <option value="{{$zip}}" selected>{{$zip}}</option>
                                                                @endif
                                                            </select>       
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="" class="col-sm-3 col-form-label">{{ $langg->lang270 }}</label>
                                                        <div class="col-sm-9">
                                                        <textarea  class="form-control" style="border:0px;" name="address" required=""
                                                        placeholder="{{ $langg->lang270 }}">{{ $user->address }}</textarea>     
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="upload-img" style="display:block;">
                                                        @if($user->is_provider == 1)
                                                        <div class="img" style="display:block;margin:0 auto;"><img
                                                                src="{{ $user->photo ? asset($user->photo):asset('assets/images/'.$gs->user_image) }}">
                                                        </div>
                                                        @else
                                                        <div class="img" style="display:block;margin:0 auto;"><img
                                                                src="{{ $user->photo ? asset('assets/images/users/'.$user->photo):asset('assets/images/'.$gs->user_image) }}">
                                                        </div>
                                                        @endif
                                                        @if($user->is_provider != 1)
                                                        <br>    
                                                        <div class="file-upload-area" style="display:block;margin:0 auto;">
                                                            <div class="upload-file" style="display:block;margin:0 auto;">
                                                                <input type="file" name="photo" class="upload">
                                                                <span>{{ $langg->lang263 }}</span>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            
    
                                            <div class="form-links">
                                                <button class="btn btn-orange" style="width:15%;border-radius:10px;" type="submit">{{ $langg->lang271 }}</button>
                                            </div>
                                        </div>

                                        {{-- <div class="form-links">
                                            <button class="submit-btn" type="submit">{{ $langg->lang271 }}</button>
                                        </div> --}}
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
                    $('.gocover').show();
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
                    $('.gocover').hide();
                }
        });

        $('select[name="city"]').on('change', function(e) {
                var kecamatan = [];
                if ($(this).val() !== '') {
                    $('.gocover').show();
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
                    $('.gocover').hide();
                }
        });    

        $('select[name="districts"]').on('change', function(e) {
                var zip = [];
                if ($(this).val() !== '') {
                    $('.gocover').show();
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
                    $('.gocover').hide();
                }
        });             


        $(".upload").on( "change", function() {
          var imgpath = $('.upload-img img');
          var file = $(this);
          readURL(this,imgpath);
        });

        function readURL(input,imgpath) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                imgpath.attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
</script>

@endsection