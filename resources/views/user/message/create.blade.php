@extends('layouts.front')
@section('content')


<section class="user-dashbord">
    <div class="container">
      <div class="row">
        @include('includes.user-dashboard-sidebar')
        <div class="col-lg-9">
					<div class="user-profile-details">
						<div class="order-history">
							<div class="header-area no-garis">
								<h4 class="title">
									{{ $langg->lang372 }}
                            @if($user->id == $conv->sent->id)
                            {{$conv->recieved->name}}    
                            @else
                            {{$conv->sent->name}}
                            @endif 
								</h4>
							</div>

                            <table id="" class="table dt-responsive" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Customer</th>
                                    </tr>
                                    
                                </thead>
                                <tbody>
                                    <tr>
                                        <td width="50%">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <img src="{{filter_var($conv->product->photo, FILTER_VALIDATE_URL) ?$conv->product->photo:asset('assets/images/products/'.$conv->product->photo)}}" class="mr-3 img-fluid rounded-circle"  style="height:100px;width:100px;" alt="...">
                                                </div>
                                                <div class="col-md-8">
                                                    <p style="font-weight:bold"><a href="{{url('item/'.$conv->product->slug)}}">{{$conv->product->name}}</a></p>
                                                    <p style="color:#EF4623;font-size:18px;font-weight:bold;">{{$conv->product->showCoin()}} EDC</p>
                                                </div>
                                            </div>
                                           

                                        </td>
                                        <td width="50%">
                                        @foreach($conv->messages->take(1) as $message)
                                            @if($message->sent_user != null)
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        @if($message->conversation->sent->is_provider == 1 )
                                                        <img class="img-fluid rounded-circle" src="{{ $message->conversation->sent->photo != null ? $message->conversation->sent->photo : asset('assets/images/noimage.png') }}" alt="" style="height:100px;width:100px;">
                                                        @else 
                                                        <img class="img-fluid rounded-circle" src="{{ $message->conversation->sent->photo != null ? asset('assets/images/users/'.$message->conversation->sent->photo) : asset('assets/images/noimage.png') }}" alt="" style="height:100px;width:100px;">
                                                        @endif
                                                    </div>
                                                    <div class="col-md-8">
                                                        <p style="font-weight:bold;">{{ $message->conversation->sent->name }}</p>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach

                                        </td>
                                    </tr>
                                </tbody>
                                
                            </table>


<div class="support-ticket-wrapper ">
                
            
                <div class="panel panel-primary no-garis">
                      <div class="gocover" style="background: url({{ asset('assets/images/'.$gs->loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>                  
                    <div class="panel-body no-garis" id="messages">
                      @foreach($conv->messages as $message)
                        @if($message->sent_user != null)

                        <div class="single-reply-area admin">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="reply-area">
                                        <div class="left">
                                            @if($message->conversation->sent->is_provider == 1 )
                                            <img class="img-circle" src="{{ $message->conversation->sent->photo != null ? $message->conversation->sent->photo : asset('assets/images/noimage.png') }}" alt="">
                                            @else 
                                            <img class="img-circle" src="{{ $message->conversation->sent->photo != null ? asset('assets/images/users/'.$message->conversation->sent->photo) : asset('assets/images/noimage.png') }}" alt="">
                                            @endif
                                            <p class="ticket-date">{{ $message->conversation->sent->name }}</p>
                                        </div>
                                        <div class="right">
                                            <p>{{ $message->message }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <br>
                        @else

                        <div class="single-reply-area user">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="reply-area">
                                        <div class="left">
                                            <p>{{ $message->message }}</p>
                                        </div>
                                        <div class="right">
                                            @if($message->conversation->recieved->is_provider == 1 )
                                            <img class="img-circle" src="{{ $message->conversation->recieved->photo != null ? $message->conversation->recieved->photo : asset('assets/images/noimage.png') }}" alt="">
                                            @else 
                                            <img class="img-circle" src="{{ $message->conversation->recieved->photo != null ? asset('assets/images/users/'.$message->conversation->recieved->photo) : asset('assets/images/noimage.png') }}" alt="">
                                            @endif
                                            <p class="ticket-date">{{$message->conversation->recieved->name}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <br>
                        @endif
                        @endforeach

                    </div>
                    <div class="panel-footer">
                        <form id="messageform" data-href="{{ route('user-vendor-message-load',$conv->id) }}" action="{{route('user-message-post')}}" method="POST">
                            {{csrf_field()}}
                            <div class="form-group">
                                              <input type="hidden" name="conversation_id" value="{{$conv->id}}">
                              @if($user->id == $conv->sent_user)
                                  <input type="hidden" name="sent_user" value="{{$conv->sent->id}}">
                                  <input type="hidden" name="reciever" value="{{$conv->recieved->id}}">
                                @else
                                  <input type="hidden" name="reciever" value="{{$conv->sent->id}}">
                                  <input type="hidden" name="recieved_user" value="{{$conv->recieved->id}}">
                              @endif

                                <textarea class="form-control form-bulet-border" name="message" id="wrong-invoice" rows="5" style="resize: vertical;" required="" placeholder="{{ $langg->lang374 }}"></textarea>
                            </div>
                            <div class="form-group" style="text-align:right">
                            <a  class="btn btn-outline-danger btn-sm" style="width:20%;"href="{{ route('user-messages') }}"> {{ $langg->lang373 }}</a>
                                <button class="btn btn-orange btn-sm" style="border-radius:20px; width:20%;">
                                    {{ $langg->lang375 }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>


						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

@endsection