<div class="col-lg-3">          
          
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
                <a target="_blank" href="{{ route('front.vendor',str_replace(' ', '-', Auth::user()->shop_name)) }}" class="wave-effect active">{{ $langg->lang440 }}</a>
              </li>
              <li>
                <a href="{{ route('vendor-dashboard') }}" class="{{ $link == route('vendor-dashboard') ? 'aktif':'' }}">
                  {{ $langg->lang200 }}
                </a>
              </li>
              @if (Auth::user()->subscribes->is_dropship === '1')
								<li>
									<a href="{{ route('vendor-store-edit') }}" class="wave-effect active">Edit Tampilan Toko</>
								</li>									
							@endif
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
