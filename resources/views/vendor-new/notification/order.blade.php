<a class="clear">{{ $langg->lang436 }}</a>
@if(count($datas) > 0)
<ul>
	@foreach($datas as $data)
	<li>
		<a href="{{ route('vendor-order-show',$data->order_number) }}"> <i class="fas fa-newspaper"></i>
			{{ $langg->lang438 }} #{{ $data->order_number }}</a>
	</li>
	@endforeach

</ul>

@else

<a class="clear" href="javascript:;">
	{{ $langg->lang439 }}
</a>

@endif


<a class="clear">{{ _("Pesanan Selesai") }}</a>
@if(count($completes) > 0)
<ul>
	@foreach($completes as $data)
	<li>
		<a href="{{ route('vendor-order-show',$data->order->order_number) }}"> <i class="fas fa-newspaper"></i>
			{{ "Anda memiliki pesanan sudah selesai #".$data->order->order_number }}</a>
	</li>
	@endforeach

</ul>

@else

<a class="clear" href="javascript:;">
	{{ $langg->lang439 }}
</a>

@endif

<a class="clear">{{ _("Pesanan Lunas Pembayaran") }}</a>
@if(count($paidOrder) > 0)
<ul>
	@foreach($paidOrder as $data)
	<li>
		<a href="{{ route('vendor-order-show',$data->order->order_number) }}"> <i class="fas fa-newspaper"></i>
			{{ "Anda memiliki pesanan sudah dibayar #".$data->order->order_number }}</a>
	</li>
	@endforeach

</ul>

@else

<a class="clear" href="javascript:;">
	{{ $langg->lang439 }}
</a>

@endif

<a class="clear">{{ _("Penerimaan Pembayaran") }}</a>
@if(count($payment) > 0)
<ul>
	@foreach($payment as $data)
	<li>
		<a href="#" data-toggle="tooltip"
			title="{{ __("Nomor Referensi : #".$data->order->payment_vendor_reference) }}"> <i
				class="fas fa-newspaper"></i>
			{{ __("Pesanan dengan nomor #".$data->order->order_number." sudah dibayar.") }}</a>
	</li>
	@endforeach

</ul>

@else

<a class="clear" href="javascript:;">
	{{ $langg->lang439 }}
</a>

@endif

@if (Auth::user()->subscribes->is_dropship === 1)
	<a class="clear">{{ _("Penerimaan Pembayaran Dropshipper") }}</a>
	@if(count($dropships) > 0)
		<ul>
			@foreach($dropships as $data)
				<li>
					<a href="#" data-toggle="tooltip" title="{{ __("Penerimaan Pembayaran Fee Dropshipper dengan Nomor Referensi #").$data->order->payment_dropshipper_reference }}"></a>
				</li>
			@endforeach

		</ul>		

	@else 

	<a class="clear" href="javascript:;">
		{{ $langg->lang439 }}
	</a>		

	@endif
@endif


<a id="order-notf-clear" data-href="{{ route('vendor-order-notf-clear',Auth::guard('web')->user()->id) }}" class="clear"
	href="javascript:;">
	{{ $langg->lang437 }}
</a>
