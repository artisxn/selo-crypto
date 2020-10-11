		<a class="clear">{{ __('New Product Uploaded') }}</a>
		@if(count($pendings) > 0)
		<a id="product-notf-clear" data-href="{{ route('product-notf-clear') }}" class="clear" href="javascript:;">
			{{ __('Clear All') }}
		</a>
		<ul>
		@foreach($pendings as $pending)
			@if($pending->product <> '')
			<li>
				<a href="{{ route('admin-prod-pending') }}"> <i class="icofont-cart"></i> {{strlen($pending->product->name) > 30 ? substr($pending->product->name,0,30) : $pending->product->name}}</a>
			</li>
			@endif
		@endforeach

		</ul>

		@else 

		<a class="clear" href="javascript:;">
			{{ __('No New Notifications.') }}
		</a>

		@endif

		<a class="clear">{{ __('Product(s) in Low Quantity.') }}</a>
		@if(count($datas) > 0)
		<a id="product-notf-clear" data-href="{{ route('product-notf-clear') }}" class="clear" href="javascript:;">
			{{ __('Clear All') }}
		</a>
		<ul>
		@foreach($datas as $data)
			@if($data->product <> '')
			<li>
				<a href="{{ route('admin-prod-edit',$data->product->id) }}"> <i class="icofont-cart"></i> {{strlen($data->product->name) > 30 ? substr($data->product->name,0,30) : $data->product->name}}</a>
				<a class="clear">{{ __('Stock') }} : {{$data->product->stock}}</a>
			</li>			
			@endif
		@endforeach

		</ul>

		@else 

		<a class="clear" href="javascript:;">
			{{ __('No New Notifications.') }}
		</a>

		@endif