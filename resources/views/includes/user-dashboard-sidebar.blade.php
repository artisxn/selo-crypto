        <div class="col-lg-3">          
          <div class="card-abu">
            @if(Auth::user()->IsVendor() === FALSE)
              <p style="color:#212121;">Mau dapat UNTUNG lebih dari EDC Store</p> 
              <h4>Saatnya kamu,,</h4>

              
                <a href="{{ route('user-package') }}" class="btn btn-orange btn-sm btn-block">{{$langg->lang233}}</a>
            @else
              <a href="{{ route('vendor-dashboard') }}" class="btn btn-orange btn-sm btn-block">{{$langg->lang230}}</a>

              <a href="{{ route('user-package') }}" class="btn btn-orange btn-sm btn-block">{{ __('Upgrade Akun Vendor') }}</a>
            @endif
              {{-- <a href="{{ route('user-notif-index') }}" class="btn btn-outline-danger btn-sm btn-block">Notifikasi Jualanku

              <span class="badge badge-primary badge-pill notif-count" data-count="{{ route('user-notif-count') }}">{{ App\Models\Notification::countCustomer(Auth::user()->id) }}</span>

              </a> --}}
              

            <!-- <ul class="list-group">
            @if($gs->reg_vendor == 1 && config('dropship.is_dropship') === FALSE)
              <a href="{{ route('user-package') }}">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  {{$langg->lang233}}
                  <span class="badge badge-primary badge-pill">Go</span>
                </li>
              </a>
              @endif
              <a href="{{ route('user-notif-index') }}">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  Notifikasi
                  <span class="badge badge-primary badge-pill notif-count" data-count="{{ route('user-notif-count') }}">{{ App\Models\Notification::countCustomer(Auth::user()->id) }}</span>
                </li>              
              </a>
            </ul> -->
          </div>
          
          <div class="card-abu">
            <ul class="linkabu">
                @php 

                  if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') 
                  {
                    $link = "https"; 
                  }
                  else
                  {
                    $link = "http"; 
                      
                    // Here append the common URL characters. 
                    $link .= "://"; 
                      
                    // Append the host(domain name, ip) to the URL. 
                    $link .= $_SERVER['HTTP_HOST']; 
                      
                    // Append the requested resource location to the URL 
                    $link .= $_SERVER['REQUEST_URI']; 
                  }      

                @endphp
              <li>
                <a href="{{ route('user-dashboard') }}" class="{{ $link == route('user-dashboard') ? 'aktif':'' }}">
                  {{ $langg->lang200 }}
                </a>
              </li>

              <li>
                <a href="{{route('user-notif-index')}}" class="{{ $link == route('user-messages') ? 'aktif':'' }}">
                  {{ __('Notifikasi') }} 
                  <span class="badge badge-primary badge-pill notif-count" data-count="{{ route('user-notif-count') }}">{{ App\Models\Notification::countCustomer(Auth::user()->id) }}</span>
                </a>
              </li>

              {{-- @if(Auth::user()->IsVendor() === FALSE)
                <li>
                    <a href="{{route('user-edccash')}}" class="{{ $link == route('user-edccash') ? 'aktif':'' }}">EDC Cash</a>
                </li>
              @endif --}}
              <li>
                  <a href="{{route('user-invoice')}}" class="{{ $link == route('user-invoice') ? 'aktif':'' }}">Invoices</a>
              </li>

              <li>
                <a href="{{ route('user-orders') }}" class="{{ $link == route('user-orders') ? 'aktif':'' }}">
                  {{ $langg->lang201 }}
                </a>
              </li> 

              {{-- @if($gs->is_affilate == 1)

                <li>
                    <a href="{{ route('user-affilate-code') }}" class="{{ $link == route('user-affilate-code') ? 'aktif':'' }}">{{ $langg->lang202 }}</a>
                </li>

                <li>
                    <a href="{{route('user-wwt-index')}}" class="{{ $link == route('user-wwt-index') ? 'aktif':'' }}">{{ $langg->lang203 }}</a>
                </li>

              @endif --}}

              {{-- <li>
                  <a href="{{route('user-order-track')}}" class="{{ $link == route('user-order-track') ? 'aktif':'' }}">{{ $langg->lang772 }}</a>
              </li> --}}

              <li>
                  <a href="{{route('user-favorites')}}" class="{{ $link == route('user-favorites') ? 'aktif':'' }}">{{ $langg->lang231 }}</a>
              </li>

              <li>
                  <a href="{{route('user-messages')}}" class="{{ $link == route('user-messages') ? 'aktif':'' }}">{{ $langg->lang232 }}  <span class="badge badge-primary" id="count-unread-messages"></span></a>
              </li>

              <li>
                  <a href="{{route('user-message-index')}}" class="{{ $link == route('user-message-index') ? 'aktif':'' }}">{{ $langg->lang204 }}</a>
              </li>

              <li>
                  <a href="{{route('user-dmessage-index')}}" class="{{ $link == route('user-dmessage-index') ? 'aktif':'' }}">{{ $langg->lang250 }}</a>
              </li>

              <li>
                <a href="{{ route('user-profile') }}" class="{{ $link == route('user-profile') ? 'aktif':'' }}">
                  {{ $langg->lang205 }}
                </a>
              </li>

              <li>
                <a href="{{ route('user-reset') }}" class="{{ $link == route('user-reset') ? 'aktif':'' }}">
                 {{ $langg->lang206 }}
                </a>
              </li>

              <li>
                <!-- <a href="{{ route('user-logout') }}">
                  {{ $langg->lang207 }}
                </a> -->
                <a href="javascript:;" data-toggle="modal" data-target="#confirm-logout" data-href="{{ route('user-logout') }}" class="">{{ $langg->lang207 }}</a>

              </li>

            </ul>
          </div>
          <!-- @if($gs->reg_vendor == 1)
            <div class="row mt-4">
              <div class="col-lg-12 text-center">
                <a href="{{ route('user-package') }}" class="mybtn1 lg">
                  <i class="fas fa-dollar-sign"></i> {{ Auth::user()->is_vendor == 1 ? $langg->lang233 : (Auth::user()->is_vendor == 0 ? $langg->lang233 : $langg->lang237) }}
                </a>
              </div>
            </div>
          @endif -->
        </div>



@include('includes.modal-logout')
