@extends('layouts.front')
@section('content')


<section class="user-dashbord">
	<div class="container">
		<div class="row">
			@include('includes.user-dashboard-sidebar')
			<div class="col-lg-9">
				<div class="user-profile-details">
					<div class="order-history">
						<div class="header-area">
							<h4 class="title">
								Daftar Alamat 
								<a style="float: right;" class="mybtn1" href="{{ route('user-address-add') }}"> <i class="fas fa-envelope"></i> {{ __('Tambah Alamat') }}</a>
							</h4>

						</div>
						<div class="mr-table allproduct message-area  mt-4">
							@include('includes.form-success')
							<div class="table-responsiv">
								<table id="example" class="table table-hover dt-responsive" cellspacing="0"
									width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>Nama Alamat</th>
											<th>Penerima</th>
											<th>Alamat Lengkap</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody>
										<?php $no = 1; ?>
										@foreach($address as $alamat)
										<tr>
											<td>{{$no++}}</td>
											<td> {{ $alamat->address_name }} </td>
											<td>{{ $alamat->receiver }}</td>
											<td>{{ $alamat->address.", ".$alamat->regency->name." ".$alamat->zip }}</td>
											<td align="center">
												<a href="{{ route('user-address-edit', [$alamat->id]) }}" class="link view"><i class="fa fa-pen"></i></a>
                                                <a href="javascript:;" data-toggle="modal" data-target="#confirm-delete" data-href="{{route('user-address-remove',$alamat->id)}}" class="link remove"><i class="fa fa-trash"></i></a>
											</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>

				  
			</div>
		</div>
	</div>
</section>


<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header d-block text-center">
                <h4 class="modal-title d-inline-block">{{ $langg->lang367 }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-center">{{ __('Anda akan menghapus Alamat ini') }}</p>
                <p class="text-center">{{ $langg->lang369 }}</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ $langg->lang370 }}</button>
                <a class="btn btn-danger btn-ok">{{ $langg->lang371 }}</a>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript">
    $('#confirm-delete').on('show.bs.modal', function(e) {
          $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    });
</script>
@endsection