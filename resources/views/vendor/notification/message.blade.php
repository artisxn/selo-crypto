<a class="clear">{{ __('Conversation(s).') }}</a>
@if(count($datas) > 0)
<a id="conv-notf-clear" data-href="{{ route('vendor-conv-notf-clear') }}" class="clear" href="javascript:;">
    {{ __('Clear All') }}
</a>
<ul>
@foreach($datas as $data)
    <li>
        <a href="{{ route('vendor-dmessage-details',$data->conversation_id) }}"> <i class="fas fa-envelope"></i> {{ __('Anda Dapat Pesan Baru') }}</a>
    </li>
@endforeach

</ul>

@else 

<a class="clear" href="javascript:;">
    {{ __('No New Notifications.') }}
</a>

@endif