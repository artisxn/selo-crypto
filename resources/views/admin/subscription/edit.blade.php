@extends('layouts.load')

@section('content')
            <div class="content-area">

              <div class="add-product-content">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="product-description">
                      <div class="body-area">
                        @include('includes.admin.form-error') 
                      <form id="geniusformdata" action="{{route('admin-subscription-update',$data->id)}}" method="POST" enctype="multipart/form-data">
                        {{csrf_field()}}

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading">{{ __("Title") }} *</h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="text" class="input-field" name="title" placeholder="{{ __("Enter Subscription Title") }}" required="" value="{{ $data->title }}">
                          </div>
                        </div>

                        <!-- <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading">{{ __("Currency Symbol") }} *</h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="text" class="input-field" name="currency" placeholder="{{ __("Enter Subscription Currency") }}" required="" value="{{ $data->currency }}">
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading">{{ __("Currency Code") }} *</h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="text" class="input-field" name="currency_code" placeholder="{{ __("Enter Subscription Currency Code") }}" required="" value="{{ $data->currency_code }}">
                          </div>
                        </div> -->

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading">{{ __("Cost") }} *</h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="number" class="input-field" name="price" placeholder="{{ __("Enter Subscription Cost (Numeric Only)") }}" required="" value="{{ $data->price }}">
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading">{{ __("Days") }} *</h4>
                                <p class="sub-heading">
                                  ({{ __('Set 0 for lifetime') }})
                                </p>                                    
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="number" class="input-field" name="days" placeholder="{{ __("Enter Subscription Days (Numeric Only)") }}" required="" value="{{ $data->days }}">
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading">{{ __("Allowed Highlight Products") }} *</h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="number" min="0" class="input-field" id="allowed_products" name="allowed_products" placeholder="{{ __("Enter Allowed Products") }}" {{ $data->allowed_products != 0 ? "required" : "" }} value="{{ $data->allowed_products != 0 ? $data->allowed_products : '1' }}">
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading">Dropship Menu </h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <select name="is_dropship" id="is_dropship" class="form-control">
                              <option value="0">No</option>
                              <option value="1" <?php if($data->is_dropship ==1) {echo "selected";} ?>>Yes</option>
                            </select>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                              <h4 class="heading">
                                   {{ __("Details") }} *
                              </h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                              <textarea class="nic-edit" name="details" placeholder="{{ __("Details") }}">{{ $data->details }}</textarea> 
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                              
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <button class="addProductSubmit-btn" type="submit">{{ __("Save") }}</button>
                          </div>
                        </div>
                      </form>


                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>


@endsection