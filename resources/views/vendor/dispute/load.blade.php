@foreach($conv->messages as $message)
@if($message->user_id != null)
<div class="single-reply-area user">
<div class="row">
    <div class="col-lg-12">
        <div class="reply-area">
            <div class="left">
                <p>{{ $message->message }}</p>
            </div>
            <div class="right">
        {{-- @if($message->user->is_provider == 1)
        <img class="img-circle" src="{{$message->user->photo != null ? $message->user->photo : asset('assets/images/noimage.png')}}" alt="">
        @else 

        <img class="img-circle" src="{{$message->user->photo != null ? asset('assets/images/users/'.$message->user->photo) : asset('assets/images/noimage.png')}}" alt="">

        @endif --}}
        <img class="img-circle" src="{{ asset('assets/images/noimage.png') }}" alt="">
                {{-- <a target="_blank" class="d-block profile-btn" href="{{ route('admin-user-show',$message->user->id) }}" class="d-block">View Profile</a> --}}
                <p class="ticket-date">{{ $message->created_at->diffForHumans() }}</p>
                <p>{{ $message->user_id === Auth::user()->id  ? 'Vendor' : 'Customer' }}</p>
            </div>
        </div>
    </div>
</div>
</div>

<br>

@else

<div class="single-reply-area admin">
<div class="row">
    <div class="col-lg-12">
        <div class="reply-area">
            <div class="left">
                {{-- <img class="img-circle" src="{{ Auth::guard('admin')->user()->photo ? asset('assets/images/admins/'.Auth::guard('admin')->user()->photo ):asset('assets/images/noimage.png') }}" alt=""> --}}
                                                    @if ($message->admin_id <> '' && $message->admin <> '')
                                                        @if ($message->admin->photo <> '')
                                                        <img src="{{ asset('assets/images/admins/'.$message->admin->photo) }}" alt="">
                                                        @else
                                                        <img class="img-circle" src="{{ asset('assets/images/noimage.png') }}" alt="">
                                                        @endif
                                                    @else
                                                    <img class="img-circle" src="{{ asset('assets/images/noimage.png') }}" alt="">
                                                    @endif
                                                    <p class="ticket-date">{{ $message->created_at->diffForHumans() }}</p>
                                                    Admin
            </div>
            <div class="right">
                <p>{{ $message->message }}</p>
            </div>
        </div>
    </div>
</div>
</div>

<br>

@endif

@endforeach