@extends('layouts.front')

@section('styles')
                   <style>
                    .css-356h4j {
                        flex: 0 0 auto;
                        position: relative;
                        width: 100%;
                        padding: 12px 16px;
                        background-color: rgb(255, 234, 239);
    
                    }

                    </style>
@endsection

@section('content')



<div class="breadcrumb-area">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <ul class="pages">

          <li><a href="{{route('front.index')}}">{{ $langg->lang17 }}</a></li>
          <li><a href="{{route('front.category',$productt->category->slug)}}">{{$productt->category->name}}</a></li>
          @if(!empty($productt->subcategory))
          <li><a
              href="{{ route('front.subcat',['slug1' => $productt->category->slug, 'slug2' => $productt->subcategory->slug]) }}">{{$productt->subcategory->name}}</a>
          </li>
          @endif
          @if(!empty($productt->childcategory))
          <li><a
              href="{{ route('front.childcat',['slug1' => $productt->category->slug, 'slug2' => $productt->subcategory->slug, 'slug3' => $productt->childcategory->slug]) }}">{{$productt->childcategory->name}}</a>
          </li>
          @endif
          <li><a href="{{ route('front.product', $productt->slug) }}">{{ $productt->name }}</a>

        </ul>
      </div>
    </div>
  </div>
</div>

<!-- Product Details Area Start -->
<section class="product-details-page">
  <div class="container">
    <div class="row">
    <div class="col-lg-9">
        <div class="row">

            <div class="col-lg-5 col-md-12">

              <div class="xzoom-container">
              <img class="xzoom5" id="xzoom-magnific" src="{{filter_var($productt->photo, FILTER_VALIDATE_URL) ?$productt->photo:asset('assets/images/products/'.$productt->photo)}}" xoriginal="{{filter_var($productt->photo, FILTER_VALIDATE_URL) ?$productt->photo:asset('assets/images/products/'.$productt->photo)}}" />
              <div class="xzoom-thumbs">

                <div class="all-slider">

                    <a href="{{filter_var($productt->photo, FILTER_VALIDATE_URL) ?$productt->photo:asset('assets/images/products/'.$productt->photo)}}">
                  <img class="xzoom-gallery5" width="80" src="{{filter_var($productt->photo, FILTER_VALIDATE_URL) ?$productt->photo:asset('assets/images/products/'.$productt->photo)}}" title="The description goes here">
                    </a>

                @foreach($productt->galleries as $gal)


                    <a href="{{asset('assets/images/galleries/'.$gal->photo)}}">
                  <img class="xzoom-gallery5" width="80" src="{{asset('assets/images/galleries/'.$gal->photo)}}" title="The description goes here">
                    </a>

                @endforeach

                </div>

              </div>
            </div>

            </div>

            <div class="col-lg-7">
              @if($productt->emptyStock())
                        <p>
                          <div aria-label="unf-ticker-item" class="css-356h4j eneo4yd2"><h4 class="css-a1fcc2-unf-heading e1qvo2ff4">Stok produk kosong</h4><p class="css-1hjz1j2-unf-heading e1qvo2ff8">Untuk sementara produk ini tidak dijual. Silakan hubungi toko yang bersangkutan untuk informasi lebih lanjut.</p></div>
                        </p>
              @endif                 
              <div class="right-area">
                <div class="product-info">
                  <h4 class="product-name">{{ $productt->name }}</h4>
                  <div class="info-meta-1">
                    <ul>

                      @if($productt->type == 'Physical')
                      @if($productt->emptyStock())
                      <li class="product-outstook">
                        <p>
                          <i class="icofont-close-circled"></i>
                          {{ $langg->lang78 }}
                        </p>
                      </li>
                      @else
                      <li class="product-isstook">
                        <p>
                          <i class="icofont-check-circled"></i>
                          {{ $gs->show_stock == 0 ? '' : ( !empty($productt->size) ? array_sum( $productt->size_qty ) : $productt->stock ) }} {{ $langg->lang79 }}
                        </p>
                      </li>
                      @endif
                      @endif
                      <li>
                        <div class="ratings">
                          <div class="empty-stars"></div>
                          <div class="full-stars" style="width:{{App\Models\Rating::ratings($productt->id)}}%"></div>
                        </div>
                      </li>
                      <li class="review-count">
                        <p>{{count($productt->ratings)}} {{ $langg->lang80 }}</p>
                      </li>
                      <li class="review-count">
                        <p>{{count($productt->clicks()->groupBy('date')->get())}}x {{ __('Dilihat') }}</p>
                      </li>
                  {{-- @if($productt->product_condition != 0)
                     <li>
                       <div class="{{ $productt->product_condition == 2 ? 'mybadge' : 'mybadge1' }}">
                        {{ $productt->product_condition == 2 ? 'Baru' : 'Bekas' }}
                       </div>
                     </li>
                  @endif --}}
                    </ul>
                  </div>



                  <div class="product-price">
              <style>
                .multicolumn {
                  height: 80px;
                  columns: 1;
                  -moz-columns: 1;
                }
              </style>
  <div class="multicolumn">
    <ul>
      <li style="height: 20px;"><p class="title">Harga</p></li>
      <li><span id="sizepriceedc" class="price">{{ $productt->showPrice() }}</span></li>
    </ul>

    <!-- <ul>
      <li><p class="title">Harga dalam rupiah</p></li>
      <li><small><del><span id="sizeprice" class="price">{{ $productt->showPrice() }}</span></del></small></p></li>
    </ul> -->

  </div>


    <!-- Setara dengan     <span id="price_edc">{{ $productt->showPrice() }}           </span> -->


                     <!-- <small><del>{{ $productt->showPreviousPrice() }}</del></small></p>-->
                      @if($productt->youtube != null)
                      <a href="{{ $productt->youtube }}" class="video-play-btn mfp-iframe">
                        <i class="fas fa-play"></i>
                      </a>
                    @endif
                  </div>

                  <div class="info-meta-2">
                    <ul>

                      @if($productt->type == 'License')

                      @if($productt->platform != null)
                      <li>
                        <p>{{ $langg->lang82 }}: <b>{{ $productt->platform }}</b></p>
                      </li>
                      @endif

                      @if($productt->region != null)
                      <li>
                        <p>{{ $langg->lang83 }}: <b>{{ $productt->region }}</b></p>
                      </li>
                      @endif

                      @if($productt->licence_type != null)
                      <li>
                        <p>{{ $langg->lang84 }}: <b>{{ $productt->licence_type }}</b></p>
                      </li>
                      @endif

                      @endif

                    </ul>
                  </div>


                  @if(!empty($productt->size))
                  <div class="product-size">
                    <p class="title">{{ $langg->lang88 }} :</p>
                    <ul class="siz-list">
                      @php
                      $is_first = true;
                      @endphp
                      @foreach($productt->size as $key => $data1)
                      <li class="{{ $is_first ? 'active' : '' }}">
                        <span class="box">{{ $data1 }}
                          <input type="hidden" class="size" value="{{ $data1 }}">
                          <input type="hidden" class="size_qty" value="{{ $productt->size_qty[$key] }}">
                          <input type="hidden" class="size_key" value="{{$key}}">
                          <input type="hidden" class="size_price"
                            value="{{ $productt->size_price[$key] }}">
                        </span>
                      </li>
                      @php
                      $is_first = false;
                      @endphp
                      @endforeach
                      <li>
                    </ul>
                  </div>
                  @endif

                  @if(!empty($productt->color))
                  <div class="product-color">
                    <p class="title">{{ $langg->lang89 }} :</p>
                    <ul class="color-list">
                      @php
                      $is_first = true;
                      @endphp
                      @foreach($productt->color as $key => $data1)
                      <li class="{{ $is_first ? 'active' : '' }}">
                        <span class="box" data-color="{{ $productt->color[$key] }}" style="background-color: {{ $productt->color[$key] }}"></span>
                      </li>
                      @php
                      $is_first = false;
                      @endphp
                      @endforeach

                    </ul>
                  </div>
                  @endif

                  @if(!empty($productt->size))

                  <input type="hidden" id="stock" value="{{ $productt->size_qty[0] }}">
                  @else
                  @php
                  $stck = (string)$productt->stock;
                  @endphp
                  @if($stck != null)
                  <input type="hidden" id="stock" value="{{ $stck }}">
                  @elseif($productt->type != 'Physical')
                  <input type="hidden" id="stock" value="0">
                  @else
                  <input type="hidden" id="stock" value="">
                  @endif

                  @endif

                  <input type="hidden" id="product_price" value="{{ str_replace(['Rp ', '.'], '', $productt->showPrevPrice()) }}">
                  <input type="hidden" id="product_edc_price" value="{{ str_replace(['Rp ', '.'], '', $productt->showCurrentPrice()) }}">
                  



                  <input type="hidden" id="product_id" value="{{ $productt->id }}">
                  <input type="hidden" id="curr_pos" value="{{ $gs->currency_format }}">
                  <input type="hidden" id="curr_sign" value="{{ $curr->sign }}">
                  <div class="info-meta-3">
                    <ul class="meta-list">
                      <li><font color="#A5A5A5">Jumlah produk yang ingin dipesan</font></li>
                      @if($productt->product_type != "affiliate")
                      <li class="d-block count {{ $productt->type == 'Physical' ? '' : 'd-none' }}">
                        <div class="qty">
                          <ul>
                            <li>
                              <span class="qtminus">
                                <i class="icofont-minus"></i>
                              </span>
                            </li>
                            <li>
                              <span class="qttotal">1</span>
                            </li>
                            <li>
                              <span class="qtplus">
                                <i class="icofont-plus"></i>
                              </span>
                            </li>
                          </ul>
                        </div>
                      </li>
                      @endif

                      @if (!empty($productt->attributes))
                        @php
                          $attrArr = json_decode($productt->attributes, true);
                        @endphp
                      @endif
                      @if (!empty($attrArr))
                        <div class="product-attributes my-4">
                          <div class="row">
                          @foreach ($attrArr as $attrKey => $attrVal)
                            @if (array_key_exists("details_status",$attrVal) && $attrVal['details_status'] == 1)

                          <div class="col-lg-6">
                              <div class="form-group mb-2">
                                <strong for="" class="text-capitalize">{{ str_replace("_", " ", $attrKey) }} :</strong>
                                <div class="">
                                @foreach ($attrVal['values'] as $optionKey => $optionVal)
                                  <div class="custom-control custom-radio">
                                    <input type="hidden" class="keys" value="">
                                    <input type="hidden" class="values" value="">
                                    <input type="radio" id="{{$attrKey}}{{ $optionKey }}" name="{{ $attrKey }}" class="custom-control-input product-attr"  data-key="{{ $attrKey }}" data-price = "{{ $attrVal['prices'][$optionKey] * $curr->value }}" value="{{ $optionVal }}" {{ $loop->first ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="{{$attrKey}}{{ $optionKey }}">{{ $optionVal }}

                                    @if (!empty($attrVal['prices'][$optionKey]))
                                      +
                                      {{$curr->sign}} {{$attrVal['prices'][$optionKey] * $curr->value}}
                                    @endif
                                    </label>
                                  </div>
                                @endforeach
                                </div>
                              </div>
                          </div>
                            @endif
                          @endforeach
                          </div>
                        </div>
                      @endif

                      <!--

                      <li class="addtocart">
                        <br>
                        <a href="javascript:;" class="add-to-compare"
                          data-href="{{ route('product.compare.add',$productt->id) }}"><i class="icofont-exchange"></i> Bandingkan</a>
                      </li>                                            

                      @if(Auth::guard('web')->check())
                      <li class="favorite">
                        <br>
                        <a href="javascript:;" class="add-to-wish"
                          data-href="{{ route('user-wishlist-add',$productt->id) }}"><i class="icofont-heart-alt"></i></a>
                      </li>
                      @else
                      <li class="favorite">
                        <br>
                        <a href="javascript:;" data-toggle="modal" data-target="#comment-log-reg"><i
                            class="icofont-heart-alt"></i></a>
                      </li>
                      @endif
                    </ul>
                    
                    @if(Auth::guard('web')->check() && @Auth::user()->subscribes->is_dropship === '1')
                    <ul class="meta-list" style="margin-top: 10px;">
                        <li class="addtocart">
                            <a href="#" class="add-to-dropship" data-href="{{ route('vendor-dropship-store',$productt->id) }}"><i class="far fa-handshake"></i>{{ __("Tambah Ke Produk Dropship") }}</a>
                        </li>
                    </ul>                    
                    @endif
                  -->
                  </div>
                   <!--
                  <div class="social-links social-sharing a2a_kit a2a_kit_size_32">
                    <ul class="link-list social-links">
                      <li>
                        <a class="facebook a2a_button_facebook" href="">
                          <i class="fab fa-facebook-f"></i>
                        </a>
                      </li>
                      <li>
                        <a class="twitter a2a_button_twitter" href="">
                          <i class="fab fa-twitter"></i>
                        </a>
                      </li>
                      <li>
                        <a class="linkedin a2a_button_linkedin" href="">
                          <i class="fab fa-linkedin-in"></i>
                        </a>
                      </li>
                      <li>
                        <a class="pinterest a2a_button_pinterest" href="">
                          <i class="fab fa-pinterest-p"></i>
                        </a>
                      </li>
                    </ul>
                  </div>
                  <script async src="https://static.addtoany.com/menu/page.js"></script>
                  -->
                  </div>
  
<font color="#A5A5A5">Info Produk</font>


<style>
table.cinereousTable {
  width: 100%;
  text-align: left;
}
table.cinereousTable td, table.cinereousTable th {
  padding: 2px 2px;
}
</style> 

<table class="cinereousTable">
  <tbody>
    <td>Berat</td>
    <td>Kondisi</td>
    <td>Estimasi</td>
    <tr>
      <td>
        @if( $productt->weight != null )
          <span class="idno"><b> {{ $productt->weight }}g</span>
        @endif
      </td>
      <td>
        @switch($productt->product_condition)
            @case(1)
            <span class="idno"><b>{{ __('Bekas') }}</span>
                @break
            @case(2)
            <span class="idno"><b>{{ __('Baru') }} </span>
                @break
            @default
            <span class="idno"><b> {{ __('Baru') }} </span>
        @endswitch
      </td>
      <td>
        @if($productt->ship != null)
          <span class="idno"><b> {{ $productt->ship }}</span>
        @endif
      </td>
    </tr>
</tbody>

</table>



      @if($gs->is_report)

      {{-- PRODUCT REPORT SECTION --}}

                    @if(Auth::guard('web')->check())

                    <div class="report-area">
                        <a href="javascript:;" data-toggle="modal" data-target="#report-modal"><i class="fas fa-flag"></i> {{ $langg->lang776 }}</a>
                    </div>

                    @else

                    <div class="report-area">
                        <a href="javascript:;" data-toggle="modal" data-target="#comment-log-reg"><i class="fas fa-flag"></i> {{ $langg->lang776 }}</a>
                    </div>
                    @endif

      {{-- PRODUCT REPORT SECTION ENDS --}}

      @endif



                </div>
              </div>
            </div>

          </div>
                     <style type="text/css">
.test{
  color:grey;
}
.test:hover{
  color:red;
}

                     </style>          
   <div class="col-lg-3">
      
      <div class="info-meta-3">
                    <ul class="meta-list">
                      
                  
                    <li class="favorite">
                            <a href="javascript:;" class="wish add-to-compare compare-product fa-3x" data-href="{{ route('product.compare.add',$productt->id) }}"><i class="fas test fa-exchange-alt"></i></a>
                      
                          @if(Auth::guard('web')->check())                    
                                                
                            <a href="javascript:;" class=".logo-header .helpful-links ul li .wish i, .logo-header .helpful-links ul li.my-dropdown .cart .icon i fa-3x"><i class="far test fa-heart add-to-wish" data-href="{{ route('user-wishlist-add',$productt->id) }}"></i></a>
                            @if (Auth::user()->subscribes <> '' &&  Auth::user()->subscribes->is_dropship === '1')
                              <a href="javascript:;" class="add-to-dropship" data-href="{{ route('vendor-dropship-store',$productt->id) }}"><img src="{{ asset('assets/images/sosial/handshake.png') }}" style="width:100px;height:100px; padding: 10px; margin-top: -20px;" alt="" srcset=""></a>    
                            @endif
                          @else                       
                            <a href="javascript:;" data-toggle="modal" data-target="#comment-log-reg" class=".logo-header .helpful-links ul li .wish i, .logo-header .helpful-links ul li.my-dropdown .cart .icon i fa-3x">
                              <i class="far test fa-heart"></i></a>
                            </a>
                          @endif
                         </li>   
                      

                       </ul>
                      
                      

                    
                    
                    
         </div>          

             <div class="col-lg-1">
                        <style type="text/css">
                        .share {
                        top: 329px;
                        left: 1142px;
                        width: 38px;
                        height: 23px;
                        text-align: right;
                        font: Light 15px/20px Kanit;
                        letter-spacing: 0;
                        color: #A5A5A5;
                        opacity: 1;
                        }
                        </style>


                        <p class="share">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Share</p>

    </div>
              <ul class="link-list" style="display:inline;">
            
                        <li style="display:inline;margin-right:5px;">
              
                <img src="{{ asset('assets/images/sosial/fb.png') }}" alt="">
              </a>
              </li>
            
                        <li style="display:inline;margin-right:10px;">
              
                <img src="{{ asset('assets/images/sosial/ig.png') }}" alt="">
              </a>
            </li>
                    
        
                        <li style="display:inline;margin-right:10px;">
             
                <img src="{{ asset('assets/images/sosial/twiter.png') }}" alt="">
              </a>
            </li>
            
            </ul>
    </div>


    <div class="row">
      <div class="col-lg-12">

      </div>
    </div>
  </div>
  <!-- Trending Item Area Start -->



<div class="container">
  <div class="row">
              <div class="col-lg-12">
                  <div id="product-details-tab">
                    <div class="top-menu-area">
                      <ul class="tab-menu">
                        <li class="komentar"><a href="#tabs-1">{{ $langg->lang92 }}</a></li>
                        <li class="komentar"><a href="#tabs-3">{{ $langg->lang94 }}({{ count($productt->ratings) }})</a></li>
                        @if($gs->is_comment == 1)
                        <li class="komentar"><a href="#tabs-4">{{ $langg->lang95 }}(<span
                              id="comment_count">{{ count($productt->comments) }}</span>)</a></li>
                        @endif
                        <li class="komentar"><a href="#tabs-2">Kebijakan Pengembalian</a></li>
                        
                      </ul>
                    </div>
                    <div class="tab-content-wrapper">
                      <div id="tabs-1" class="tab-content-area">
                        {!! $productt->details !!}</p>
                      </div>
                      <div id="tabs-2" class="tab-content-area">
                        {!! $productt->policy !!}</p>
                      </div>
                      <div id="tabs-3" class="tab-content-area">
                        <div class="heading-area">
                          <!--header area-->


                          {{-- <h4 class="title">
                            {{ $langg->lang96 }}
                          </h4> --}}
                          <div class="reating-area">
                            <!-- start logo
                            <div class="stars"><span id="star-rating">{{App\Models\Rating::rating($productt->id)}}</span> <i
                                class="fas fa-star"></i></div> -->
                          </div>
                        </div>
                        <div id="replay-area">
                          <div id="reviews-section">
                            @if(count($productt->ratings) > 0)
                            <ul class="all-replay">
                              @foreach($productt->ratings as $review)
                              <li>
                                <div class="single-review">
                                  <div class="left-area">
                                    <img
                                      src="{{ $review->user->photo ? asset('assets/images/users/'.$review->user->photo):asset('assets/images/noimage.png') }}"
                                      alt="">
                                    <h5 class="name">{{ $review->user->name }}</h5>
                                    <p class="date">
                                      {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$review->review_date)->diffForHumans() }}
                                    </p>
                                  </div>
                                  <div class="right-area">
                                    <div class="header-area">
                                      <div class="stars-area">
                                        <ul class="stars">
                                          <div class="ratings">
                                            <div class="empty-stars"></div>
                                            <div class="full-stars" style="width:{{$review->rating*20}}%"></div>
                                          </div>
                                        </ul>
                                      </div>
                                    </div>
                                    <!-- review test -->

                                    <div class="review-body">
                                      <p>
                                        {{$review->review}}
                                      </p>
                                    </div>


                                  </div>
                                </div>
                                @endforeach
                              </li>
                            </ul>
                    
                            @endif
                          </div>
                          @if(Auth::guard('web')->check())
                          <div class="review-area">
                              <!-- <h4 class="title">{{ $langg->lang98 }}</h4> -->
                              <div class="star-area">
                                <ul class="star-list">
  
                                  <li class="stars active" data-val="5">
                                    5 <i class="fas fa-star"></i>
                                    <!-- <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i> -->
                                  </li>
                                  <li class="stars" data-val="4">
                                    4 <i class="fas fa-star"></i>
                                    <!-- <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i> -->
                                  </li>
                                  <li class="stars" data-val="3">
                                    3 <i class="fas fa-star"></i>
                                    <!-- <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i> -->
                                  </li>
                                  <li class="stars" data-val="2">
                                    2 <i class="fas fa-star"></i>
                                    <!-- <i class="fas fa-star"></i> -->
                                  </li>
                                  <li class="stars" data-val="1">
                                   1 <i class="fas fa-star"></i>
                                  </li>
                                </ul>
                              </div>
                            </div>

                            <hr>
                           
                              @if(count($productt->ratings) == 0)
                              <div style="margin-top:20px">
                                <p>{{ $langg->lang97 }}</p>
                              </div>
                              @endif
  
                        
              
              
              
                            
                            <div class="write-comment-area">
  
                              <div class="gocover"
                                style="background: url({{ asset('assets/images/'.$gs->loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                              </div>
                              <form id="reviewform" action="{{route('front.review.submit')}}"
                                data-href="{{ route('front.reviews',$productt->id) }}" method="POST">
                                @include('includes.admin.form-both')
                                {{ csrf_field() }}
                                <input type="hidden" id="rating" name="rating" value="5">
                                <input type="hidden" name="user_id" value="{{Auth::guard('web')->user()->id}}">
                                <input type="hidden" name="product_id" value="{{$productt->id}}">
                                <div class="row">
                                  <div class="col-lg-12">
                                    <textarea name="review" placeholder="{{ $langg->lang99 }}" required=""></textarea>
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-lg-12">
                                    <button class="submit-btn" type="submit">{{ $langg->lang100 }}</button>
                                  </div>
                                </div>
                              </form>
                            </div>

                          <div class="reating-area">
                            <!-- start logo
                            <div class="stars"><span id="star-rating">0.0</span> <i
                                class="fas fa-star"></i></div> -->
                          </div>


                        </div>


                          {{-- <div id="reviews-section">
                            <p>Belum ada ulasan untuk produk ini</p>
                              </div> --}}
                          @else
                          <div class="row">
                            <div class="col-lg-12">
                              <br>
                              <h5 class="text-center"><a href="javascript:;" data-toggle="modal" data-target="#comment-log-reg"
                                  class="btn login-btn mr-1">{{ $langg->lang101 }}</a> {{ $langg->lang102 }}</h5>
                              <br>
                            </div>
                          </div>
                          @endif
                        </div>
                      </div>
                      @if($gs->is_comment == 1)
                      <div id="tabs-4" class="tab-content-area">
                       
                        


                        <div id="comment-area">

                          @include('includes.comment-replies')

                        </div>
                      </div>
                      @endif
                    </div>
                  </div>
                </div>
          </div>
</div>





<!-- fix bottom -->
<style>
 
.navbar12 {
  overflow: hidden;
  background: #FFFFFF 0% 0% no-repeat padding-box;
  box-shadow: 0px 1px 10px #00000029;  
  position: fixed;
  bottom: 0;
  width: 100%;
  opacity: 1;
}
.navbar12 li {
  float: left;
  display: block;
/*  color: #EF4623; */
  text-align: left;
  padding: 10px 16px;
  text-decoration: none;
  font-size: 15px;
}



.navbar12 .icon {
  display: none;
}
@media screen and (max-width: 600px) {
  .navbar12 a:not(:first-child) {display: none;}
  .navbar12 a.icon {
    float: right;
    display: block;
  }
}

@media screen and (max-width: 600px) {
  .navbar12.responsive .icon {
    position: absolute;
    right: 0;
    bottom:0;
  }
  .navbar12.responsive li {
    float: none;
    display: block;
    text-align: left;
  }


}

</style>
<div class="navbar12 fixed-bottom">
 
 <ul>
  <li>&nbsp;</li>
  
  <li><img src="{{asset('assets/images/users/'.$productt->user->photo)}}" style="display:inline!important;width:60px;height:60px;" border: 1px solid #A5A5A5;></li>
    <li><span class="stor-name">
           @if( $productt->user_id  != 0)
              @if(isset($productt->user))
                {{ $vendor->shop_name }}

                @if($productt->user->checkStatus())
                <br>
                <a class="verify-link" href="javascript:;"  data-toggle="tooltip" data-placement="top" title="{{ $langg->lang783 }}">
                  {{--  {{ $langg->lang783 }}  --}}
                  <i class="fas fa-check-circle"></i>
                
                </a>
                @endif

              @else
                {{ $langg->lang247 }}
              @endif
          @else
              {{ App\Models\Admin::find(1)->shop_name }}
          @endif
          </span>
          <br>


          <table>
<tbody>
<tr>
<td><span style="color: #A5A5A5;font-size: 12px;"> {{ $productt->user->regency->name }} 
       </span></td>
<td><div class="stars">
                              <div class="ratings">
                                <div class="empty-stars"></div>
                                
                            </div></td>
</tr>
</tbody>
</table>
          
       
        
      </li>

      <li>
      </li>
    <li>
      </li>
      <li>
      </li>


     <li><img src="{{ asset('assets/images/icons/All Asset-18.png') }}" alt="" style="display:inline!important;width:30px;height:30px; margin-top: 15px;"></li>
     <a href="javascript:;" data-toggle="modal" data-target="{{ Auth::guard('web')->check() ? '#vendorform1' : '#comment-log-reg' }}"><li><img src="{{ asset('assets/images/icons/All Asset-20.png') }}" alt="" style="display:inline!important;width:30px;height:30px; margin-top: 15px;"></li></a>
  <li>&nbsp;</li>
    <li>&nbsp;</li>
      <li>&nbsp;</li>
              {{-- <li>Total Harga<br><span style="color:#EF4623;font: SemiBold 22px/25px Kanit;">{{ $productt->showCoin() .' Coin' }}</span></li>
              <li>  <a href="javascript:;" id="addcrt" class="myButton91">Ke Keranjang</a></li>
              <li>  <a href="{{ route('product.cart.quickadd', [$productt->id, $subdomain]) }}" class="myButton91">{{ $langg->lang251 }}</a></li> --}}

              <li>Total Harga<br><span style="color:#EF4623;font: SemiBold 22px/25px Kanit;">{{ $productt->showPrice() }}</span></li>
                @if($productt->emptyStock())
                   <li>  
                      <a href="javascript:;" class="add-to-wish" data-href="{{ route('user-wishlist-add',$productt->id) }}"> <span class="myButton91" alt="" srcset="" data-href="{{ route('user-wishlist-add',$productt->id) }}">Tambah Wishlist</a>
                    </li>
                @else 
                  <li>  
                    <!-- <a href="{{ route('product.cart.quickadd',$productt->id) }}" class="myButton91">{{ $langg->lang251 }}</a> -->
                    <a id="qaddcrt" href="javascript:;" class="myButton91">{{ $langg->lang251 }}</a>
                  </li>

                  <li>  <a href="javascript:;" id="addcrt" class="myButton91">Ke Keranjang</a></li> 
                             
                @endif               
              
 </ul>
 </div>


  <!--end bottom -->


<div class="trending">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 remove-padding">
        <div class="section-top">
          <h2 class="section-title">
            {{ $langg->lang216 }}
          </h2>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12 remove-padding">
        <div class="trending-item-slider">
          @foreach($productt->category->products()->where('status','=',1)->where('id','!=',$productt->id)->take(8)->get()
          as $prod)
          @include('includes.product.slider-product')
          @endforeach
        </div>
      </div>

    </div>
  </div>
</div>
<!-- Tranding Item Area End -->
</section>
<!-- Product Details Area End -->



{{-- MESSAGE MODAL --}}
<div class="message-modal">
  <div class="modal" id="vendorform" tabindex="-1" role="dialog" aria-labelledby="vendorformLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="vendorformLabel">{{ $langg->lang118 }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="container-fluid p-0">
            <div class="row">
              <div class="col-md-12">
                <div class="contact-form">
                  <form id="emailreply1">
                    {{csrf_field()}}
                    <ul>
                      <li>
                        <input type="text" class="input-field" id="subj1" name="subject"
                          placeholder="{{ $langg->lang119}}" required="">
                      </li>
                      <li>
                        <textarea class="input-field textarea" name="message" id="msg1"
                          placeholder="{{ $langg->lang120 }}" required=""></textarea>
                      </li>
                      <input type="hidden"  name="type" value="Ticket">
                    </ul>
                    <button class="submit-btn" id="emlsub" type="submit">{{ $langg->lang118 }}</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- MESSAGE MODAL ENDS --}}


  @if(Auth::guard('web')->check())

  @if($productt->user_id != 0)

  {{-- MESSAGE VENDOR MODAL --}}


  <div class="modal" id="vendorform1" tabindex="-1" role="dialog" aria-labelledby="vendorformLabel1" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="vendorformLabel1">{{ $langg->lang118 }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="container-fluid p-0">
            <div class="row">
              <div class="col-md-12">
                <div class="contact-form">
                  <form id="emailreply">
                    {{csrf_field()}}
                    <ul>

                      <li>
                        <input type="text" class="input-field" readonly=""
                          placeholder="Send To {{ $productt->user->shop_name }}" readonly="">
                      </li>

                      <li>
                        <input type="text" class="input-field" id="subj" name="subject"
                          placeholder="{{ $langg->lang119}}" required="">
                      </li>

                      <li>
                        <textarea class="input-field textarea" name="message" id="msg"
                          placeholder="{{ $langg->lang120 }}" required=""></textarea>
                      </li>

                      <input type="hidden" name="email" value="{{ Auth::guard('web')->user()->email }}">
                      <input type="hidden" name="name" value="{{ Auth::guard('web')->user()->name }}">
                      <input type="hidden" name="user_id" value="{{ Auth::guard('web')->user()->id }}">
                      <input type="hidden" name="vendor_id" value="{{ $productt->user->id }}">
                      <input type="hidden" name="product_id" value="{{ $productt->id }}">

                    </ul>
                    <button class="submit-btn" id="emlsub1" type="submit">{{ $langg->lang118 }}</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  {{-- MESSAGE VENDOR MODAL ENDS --}}


  @endif

  @endif

</div>


@if($gs->is_report)

@if(Auth::check())

{{-- REPORT MODAL SECTION --}}

<div class="modal fade" id="report-modal" tabindex="-1" role="dialog" aria-labelledby="report-modal-Title" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

 <div class="gocover" style="background: url({{ asset('assets/images/'.$gs->loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>

                    <div class="login-area">
                        <div class="header-area forgot-passwor-area">
                            <h4 class="title">{{ $langg->lang777 }}</h4>
                            <p class="text">{{ $langg->lang778 }}</p>
                        </div>
                        <div class="login-form">

                            <form id="reportform" action="{{ route('product.report') }}" method="POST">

                              @include('includes.admin.form-login')

                                {{ csrf_field() }}
                                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                <input type="hidden" name="product_id" value="{{ $productt->id }}">
                                <div class="form-input">
                                    <input type="text" name="title" class="User Name" placeholder="{{ $langg->lang779 }}" required="">
                                    <i class="icofont-notepad"></i>
                                </div>

                                <div class="form-input">
                                  <textarea name="note" class="User Name" placeholder="{{ $langg->lang780 }}" required=""></textarea>
                                </div>

                                <button type="submit" class="submit-btn">{{ $langg->lang196 }}</button>
                            </form>
                        </div>
                    </div>
      </div>
    </div>
  </div>
</div>




{{-- REPORT MODAL SECTION ENDS --}}

@endif

@endif

@endsection


@section('scripts')

<script type="text/javascript">

  $(document).on("submit", "#emailreply1", function () {
    var token = $(this).find('input[name=_token]').val();
    var subject = $(this).find('input[name=subject]').val();
    var message = $(this).find('textarea[name=message]').val();
    var $type  = $(this).find('input[name=type]').val();
    $('#subj1').prop('disabled', true);
    $('#msg1').prop('disabled', true);
    $('#emlsub').prop('disabled', true);
    $.ajax({
      type: 'post',
      url: "{{URL::to('/user/admin/user/send/message')}}",
      data: {
        '_token': token,
        'subject': subject,
        'message': message,
        'type'   : $type
      },
      success: function (data) {
        $('#subj1').prop('disabled', false);
        $('#msg1').prop('disabled', false);
        $('#subj1').val('');
        $('#msg1').val('');
        $('#emlsub').prop('disabled', false);
        if(data == 0)
          toastr.error("Oops Something Goes Wrong !!");
        else
          toastr.success("Message Sent !!");
        $('.close').click();
      }

    });
    return false;
  });

</script>


<script type="text/javascript">

  $(document).on("submit", "#emailreply", function () {
    var token = $(this).find('input[name=_token]').val();
    var subject = $(this).find('input[name=subject]').val();
    var message = $(this).find('textarea[name=message]').val();
    var email = $(this).find('input[name=email]').val();
    var name = $(this).find('input[name=name]').val();
    var user_id = $(this).find('input[name=user_id]').val();
    var vendor_id = $(this).find('input[name=vendor_id]').val();
    var product_id = $(this).find('input[name=product_id]').val();
    $('#subj').prop('disabled', true);
    $('#msg').prop('disabled', true);
    $('#emlsub').prop('disabled', true);
    $.ajax({
      type: 'post',
      url: "{{URL::to('/vendor/contact')}}",
      data: {
        '_token': token,
        'subject': subject,
        'message': message,
        'email': email,
        'name': name,
        'user_id': user_id,
        'vendor_id': vendor_id,
        'product_id': product_id
      },
      success: function () {
        $('#subj').prop('disabled', false);
        $('#msg').prop('disabled', false);
        $('#subj').val('');
        $('#msg').val('');
        $('#emlsub').prop('disabled', false);
        toastr.success("{{ $langg->message_sent }}");
        $('.ti-close').click();
      }
    });
    return false;
  });

</script>

@endsection
