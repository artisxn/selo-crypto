<a class="clear">{{ __('Proccessing Order(s).') }}</a>

@if(count($datas) > 0)
    <ul>
    @foreach($datas as $data)
        @if ($data->proccess_order == '1')
            <li>
                <a href="{{ route('admin-order-show',$data->order_id) }}"> <i class="far fa-list-alt"></i> {{ __('You Have a proccessing order #'.$data->order->order_number) }}</a>
            </li>        
        @endif
    @endforeach
    </ul>       
@else 
    <a class="clear" href="javascript:;">
        {{ __('No New Notifications.') }}
    </a>
@endif

<a class="clear">{{ __('On Delivery Order(s).') }}</a>

@if(count($datas) > 0)
    <ul>
    @foreach($datas as $data)
        @if ($data->delivery_order == '1')
            <li>
                <a href="{{ route('admin-order-show',$data->order_id) }}"> <i class="far fa-list-alt"></i> {{ __('You Have a on delivery order #'.$data->order->order_number) }}</a>
            </li>        
        @endif
    @endforeach
    </ul>       
@else 
    <a class="clear" href="javascript:;">
        {{ __('No New Notifications.') }}
    </a>
@endif

<a class="clear">{{ __('Complete Order(s).') }}</a>

@if(count($datas) > 0)
    <ul>
    @foreach($datas as $data)
        @if ($data->complete_order == '1')
            <li>
                <a href="{{ route('admin-order-show',$data->order_id) }}"> <i class="far fa-list-alt"></i> {{ __('You Have a complete order #'.$data->order->order_number) }}</a>
            </li>        
        @endif
    @endforeach
    </ul>       
@else 
    <a class="clear" href="javascript:;">
        {{ __('No New Notifications.') }}
    </a>
@endif

<a id="trans-order-notf-clear" data-href="{{ route('trans-order-notf-clear') }}" class="clear" href="javascript:;">
    {{ __('Clear All') }}
</a> 