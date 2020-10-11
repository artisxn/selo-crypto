@if(Auth::guard('web')->check())

<div class="review-area">
  <!-- <h4 class="title">{{ $langg->lang104 }}</h4> -->
</div>
<div class="write-comment-area">
  <form id="comment-form" action="{{ route('product.comment') }}" method="POST">
    <div class="heading-area">
                          <div class="col-lg-9">
                          <h4 class="title">
                            Diskusi : {{ $productt->name }} 
                          </h4>
                        </div>
                        <div class="col-lg-3">
                        <button class="myButton99" type="submit">Beri Komentar</button>
                        </div>                           
                        </div>
                        
    {{csrf_field()}}
    <input type="hidden" name="product_id" id="product_id" value="{{$productt->id}}">
    <input type="hidden" name="user_id" id="user_id" value="{{Auth::guard('web')->user()->id}}">
    <div class="row">
      <div class="col-lg-12">
      <textarea placeholder="{{ $langg->lang105 }}" name="text"></textarea>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
         <!--<button class="submit-btn" type="submit">{{ $langg->lang106 }}</button> -->
      </div>
    </div>
  </form>
</div>
<br>


<ul class="all-comment">
@if($productt->comments)  
@foreach($productt->comments()->orderBy('created_at','desc')->get() as $comment)
  <li>
    <div class="single-comment comment-section">
      <div class="left-area">
<b style="color: #A5A5A5;"> Q </b>
        <!-- <img src="{{$comment->user->photo != null ? asset('assets/images/users/'.$comment->user->photo) : asset('assets/images/noimage.png')}}" alt=""> -->
        <h5 class="name"></h5>
        <p class="date"></p>
      </div>
      <div class="right-area">
        <div class="comment-body">
          <p><b>
            {{$comment->text}}
          </b></p>
                <p style="text-align: left;font: Light 12px/25px Kanit;letter-spacing: 0;color: #A5A5A5;
opacity: 1;font-size: 12px;">{{ $comment->user->name }}&nbsp;{{ $comment->created_at->diffForHumans() }}</p>
        </div>
        <!-- username11 -->

        <div class="comment-footer">
          <div class="links">
            <a href="javascript:;" class="comment-link reply mr-2"><i class="fas fa-reply "></i>{{ $langg->lang107 }}</a>
            @if(count($comment->replies) > 0)
            <a href="javascript:;" class="comment-link view-reply mr-2"><i class="fas fa-eye "></i>{{ $langg->lang108 }} {{ count($comment->replies) == 1 ? $langg->lang109 : $langg->lang110  }}</a>
            @endif
          @if(Auth::guard('web')->user()->id == $comment->user->id)
            <a href="javascript:;" class="comment-link edit mr-2"><i class="fas fa-edit "></i>{{ $langg->lang111 }}</a>
            <a href="javascript:;" data-href="{{ route('product.comment.delete',$comment->id) }}" class="comment-link comment-delete mr-2"><i class="fas fa-trash"></i>{{ $langg->lang112 }}</a>
          @endif
          </div>
        </div>
      </div>
    </div>
    <div class="replay-area edit-area">
      <form class="update" action="{{ route('product.comment.edit',$comment->id) }}" method="POST">
        {{csrf_field()}}
        <textarea placeholder="{{ $langg->lang113 }}" name="text" required=""></textarea>
        <button type="submit">{{ $langg->lang114 }}</button>
        <a href="javascript:;" class="remove">{{ $langg->lang115 }}</a>
      </form>
    </div>
@if($comment->replies)
  @foreach($comment->replies as $reply)
    <div class="single-comment replay-review hidden">
      <div class="left-area">
       <b style="color: #A5A5A5;"> A </b>
        <!-- <img src="{{ $reply->user->photo != null ? asset('assets/images/users/'.$reply->user->photo) : asset('assets/images/noimage.png') }}" alt=""> -->
        <h5 class="name">{{ $reply->user->name }}</h5>
        <p class="date">{{ $reply->created_at->diffForHumans() }}</p>
      </div>
      <div class="right-area">
        <div class="comment-body">
          <p>
            {{ $reply->text }}
          </p>
        </div>
        <div class="comment-footer">
          <div class="links">

            <a href="javascript:;" class="comment-link reply mr-2"><i class="fas fa-reply "></i>{{ $langg->lang107 }}</a>
          @if(Auth::guard('web')->user()->id == $reply->user->id)
            <a href="javascript:;" class="comment-link edit mr-2"><i class="fas fa-edit "></i>{{ $langg->lang111 }}</a>
            <a href="javascript:;" data-href="{{ route('product.reply.delete',$reply->id) }}" class="comment-link reply-delete mr-2"><i class="fas fa-trash"></i>{{ $langg->lang112 }}</a>
          @endif
          </div>
        </div>
      </div>
    </div>
    <div class="replay-area edit-area">
      <form class="update" action="{{ route('product.reply.edit',$reply->id) }}" method="POST">
        {{csrf_field()}}
        <textarea placeholder="{{ $langg->lang116 }}" name="text" required=""></textarea>
        <button type="submit">{{ $langg->lang114 }}</button>
        <a href="javascript:;" class="remove">{{ $langg->lang115 }}</a>
      </form>
    </div>
  @endforeach
@endif
    
    <div class="replay-area reply-reply-area">
      <form class="reply-form" action="{{ route('product.reply',$comment->id) }}" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="user_id" value="{{ Auth::guard('web')->user()->id }}">
        <textarea placeholder="{{ $langg->lang117 }}" name="text" required=""></textarea>
        <button type="submit">{{ $langg->lang114 }}</button>
        <a href="javascript:;" class="remove">{{ $langg->lang115 }}</a>
      </form>
    </div>

  </li>
@endforeach
@endif
</ul>


@else
<div class="row">
<div class="col-lg-9">
<b>Diskusi: {{ $productt->name }}</b></div>
<div class="col-lg-3">
<td>  <h6 class="text-center"><a href="javascript:;" data-toggle="modal" data-target="#comment-log-reg" class="myButton97">Beri Komentar</a> </h6></div>

 <!-- 
  <h6 class="text-center"><a href="javascript:;" data-toggle="modal" data-target="#comment-log-reg" class="btn myButton99">{{ $langg->lang101 }}</a> </h6> -->
<br>
<hr>
</div>
</div>

<ul class="all-comment">
@if($productt->comments)  
@foreach($productt->comments()->orderBy('created_at','desc')->get() as $comment)
  <hr>
  <li>
    <div class="single-comment comment-section">
      <div class="row">
      <div class="col-lg-1 left-area">
      <b style="color: #A5A5A5;"> Q </b>
        <!-- <img src="{{$comment->user->photo != null ? asset('assets/images/users/'.$comment->user->photo) : asset('assets/images/noimage.png')}}" alt=""> -->
      
      </div>
      <div class="col-lg-11 right-area">
        <div class="comment-body">
          <p><b>
            {{$comment->text}}
          </b></p>
                <p style="text-align: left;font: Light 12px/25px Kanit;letter-spacing: 0;color: #A5A5A5;
opacity: 1;font-size: 12px;">{{ $comment->user->name }}&nbsp;{{ $comment->created_at->diffForHumans() }}</p>
        </div>

        <!-- username11 -->

               <div class="comment-footer">
          <div class="links">
          </div>
        </div>
      </div>
    </div>

@if($comment->replies)
  @foreach($comment->replies()->orderBy('created_at','desc')->get() as $reply)
    <div class="single-comment replay-review hidden">
      <div class="left-area">
<b style="color: #A5A5A5;"> A </b>
        <h5 class="name">{{ $reply->user->name }}</h5>
        <p class="date">{{ $reply->created_at->diffForHumans() }}</p>
      </div>


      <div class="right-area">
        <div class="comment-body">
          <p>
            {{ $reply->text }}
          </p>
        </div>

      </div>
    </div>
  @endforeach
@endif
    
  </li>
  @endforeach
</ul>
@endif

@endif