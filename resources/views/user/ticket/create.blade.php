@extends('layouts.front')
@section('content')


<section class="user-dashbord">
    <div class="container">
      <div class="row">
        @include('includes.user-dashboard-sidebar')
        <div class="col-lg-9">
					<div class="user-profile-details no-garis">
						<div class="order-history no-garis">
							<div class="header-area no-garis">
                                @if( $conv->order_number != null )
                                <h4 class="title">
                                    {{ $langg->lang396 }} {{ $conv->order_number }} 
                                </h4>
                                @endif
								<h4 class="title">
									{{ $langg->lang397 }} {{$conv->subject}} 
								</h4>
                            </div>
                            
<div class="row" style="background-color:#F6F6F6!important;color:#A5A5A5!important;padding-top:10px;padding-bottom:10px;">
    <div class="col-md-4">
        Customer
    </div>
    <div class="col-md-6">
        Tiket
    </div>
</div>

<!-- @foreach($conv->messages as $message)
    @if($message->user_id != 0)
        <div class="row">
            <div class="col-md-12">
                <p style="color:#FCB415;">{{$message->message}}</p>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-md-4">
                <div class="row">
                    <div class="col-6">
                        <img class="img-fluid rounded-circle" src="{{ asset('assets/images/admin.jpg')}}" alt="">
                    </div>
                    <div class="col-md-6">
                        <p class="ticket-date">{{ $langg->lang399 }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
               <p>{{$message->message}}</p>
            </div>
        </div>
    @endif
@endforeach
    -->


<div class="support-ticket-wrapper no-garis">
                <div class="panel panel-primary">
                      <div class="gocover" style="background: url({{ asset('assets/images/'.$gs->loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>                  
                    <div class="panel-body" id="messages">
                      @foreach($conv->messages as $message)
                        @if($message->user_id === Auth::user()->id)
                        <div style="background-color:#fff;">
                            <div class="row">
                                <div class="col-lg-12">
                                <p style="color:#FCB415;">
                                    {{$message->message}}
                                    <br>
                                    <small style="color:#A5A5A5!important">{{$message->created_at->diffForHumans()}}</small>
                                </p>
                                

                                    <!-- <div class="reply-area" >
                                        <div class="left">
                                            <p>{{$message->message}}</p>
                                        </div>
                                        <div class="right">
                                            @if($message->conversation->user->is_provider == 1)
                                            <img class="img-circle" src="{{$message->conversation->user->photo != null ? $message->conversation->user->photo : asset('assets/images/noimage.png')}}" alt="">
                                            @else 

                                            <img class="img-circle" src="{{$message->conversation->user->photo != null ? asset('assets/images/users/'.$message->conversation->user->photo) : asset('assets/images/noimage.png')}}" alt="">

                                            @endif
                                            <p class="ticket-date">{{$message->conversation->user->name}}</p>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                        <br>
                        @elseif($message->user_id !== Auth::user()->id && $message->user_id !== null)
                        <div class="single-reply-area admin" style="background-color:#fff;">
                            <div class="row">
                                <div class="col-lg-4">
                                    <!-- <div class="reply-area">
                                        <div class="left"> -->
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <img class="img-circle" src="{{ asset('assets/images/admin.jpg')}}" alt="">
                                                </div>
                                                <div class="col-md-8">
                                                    <p class="ticket-date">{{ __('Vendor') }}</p>
                                                </div>
                                            </div>
                                        <!-- </div>
                                        <div class="right">
                                            <p>{{$message->message}}</p>
                                        </div>
                                    </div> -->
                                </div>
                                <div class="col-md-8">
                                    <p style="color:#212121;">
                                        {{$message->message}}
                                        <br>
                                        <small style="color:#A5A5A5!important">{{$message->created_at->diffForHumans()}}</small>

                                    </p>
                                </div>
                            </div>
                        </div>                        
                        @else
                        <div class="single-reply-area admin" style="background-color:#fff;">
                            <div class="row">
                                <div class="col-lg-4">
                                    <!-- <div class="reply-area">
                                        <div class="left"> -->
                                            <div class="row">
                                                <div class="col-md-4">
                                                    {{-- <img class="img-circle" src="{{ asset('assets/images/admin.jpg')}}" alt=""> --}}
                                                    @if ($message->admin_id <> '' && $message->admin <> '')
                                                        @if ($message->admin->photo <> '')
                                                        <img src="{{ asset('assets/images/admins/'.$message->admin->photo) }}" alt="">
                                                        @else
                                                        <img class="img-circle" src="{{ asset('assets/images/noimage.png') }}" alt="">
                                                        @endif
                                                    @else
                                                    <img class="img-circle" src="{{ asset('assets/images/noimage.png') }}" alt="">
                                                    @endif                                                    
                                                </div>
                                                <div class="col-md-8">
                                                    <p class="ticket-date">{{ $langg->lang399 }}</p>
                                                </div>
                                            </div>
                                        <!-- </div>
                                        <div class="right">
                                            <p>{{$message->message}}</p>
                                        </div>
                                    </div> -->
                                </div>
                                <div class="col-md-8">
                                    <p style="color:#212121;">
                                        {{$message->message}}
                                        <br>
                                        <small style="color:#A5A5A5!important">{{$message->created_at->diffForHumans()}}</small>

                                    </p>
                                </div>
                            </div>
                        </div>
                        <br>
                        @endif
                        @endforeach

                    </div>
                    @if ($conv->status <> '1')
                    <div class="panel-footer">
                        <form id="messageform" data-href="{{ route('user-message-load',$conv->id) }}" action="{{route('user-message-store')}}" method="POST">
                            {{csrf_field()}}
                            <div class="form-group">
                                <input type="hidden" name="conversation_id" value="{{$conv->id}}">
                                <input type="hidden" name="user_id" value="{{$conv->user->id}}">
                                <textarea class="form-control form-bulet-border" name="message" id="wrong-invoice" rows="5" style="resize: vertical;" required="" placeholder="{{ $langg->lang400 }}"></textarea>
                            </div>
                            <div class="form-group" style="text-align:right">
                            <a  class="btn btn-outline-danger btn-sm" style="width:15%;border-radius:10px;" href="{{ url()->previous() }}">{{ $langg->lang398 }}</a>
                                <button class="btn btn-orange btn-sm" style="border-radius:10px;">
                                    {{ $langg->lang401 }}
                                </button>
                            </div>
                        </form>
                    </div>                                            
                    @endif
                </div>


						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

@endsection