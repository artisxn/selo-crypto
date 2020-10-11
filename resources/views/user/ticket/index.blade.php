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
									{{ $langg->lang376 }} 
								</h4>
							</div>
							<div class="mr-table allproduct message-area  mt-4">
								@include('includes.form-success')
									<div class="table-responsiv">
											<table id="example" class="table table-hover dt-responsive" cellspacing="0" width="100%">
												<thead>
													<tr>
														<th>{{ $langg->lang380 }}</th>
														<th>{{ $langg->lang381 }}</th>
														<th>{{ $langg->lang382 }}</th>
														<th>{{ $langg->lang383 }}</th>
													</tr>
												</thead>
												<tbody>
                        @foreach($convs as $conv)
                        
                          <tr class="conv">
                            <input type="hidden" value="{{$conv->id}}">
                            <td>
                              <a href="{{route('user-message-show',$conv->id)}}">
                              {{$conv->subject}}
                              </a>
                            </td>
                            <td>{{$conv->message}}</td>

                            <td>{{$conv->created_at->diffForHumans()}}</td>
                            <td>
                              <a href="javascript:;" data-toggle="modal" data-target="#confirm-delete" data-href="{{route('user-message-delete1',$conv->id)}}"class=""><i class="fa fa-trash" style="color:#A5A5A5;font-size:24px;"></i></a>
                            </td>

                          </tr>
                        @endforeach
												</tbody>
											</table>
									</div>
								</div>
                <a data-toggle="modal" data-target="#vendorform" class="btn btn-orange btn-sm" style="float:right;" href="javascript:;"> <i class="fas fa-envelope"></i> {{ $langg->lang377 }}</a>
						</div>
          </div>
                       
				</div>
			</div>
		</div>
	</section>

{{-- MESSAGE MODAL --}}
<div class="message-modal">
  <div class="modal" id="vendorform" tabindex="-1" role="dialog" aria-labelledby="vendorformLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="vendorformLabel">{{ $langg->lang384 }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
        </div>
      <div class="modal-body">
        <div class="container-fluid p-0">
          <div class="row">
            <div class="col-md-12">
              <div class="contact-form">
                <form id="emailreply1">
                  {{csrf_field()}}
                  <ul>
                    <li>
                      <input type="text" class="form-control" id="subj1" name="subject" placeholder="{{ $langg->lang387 }} *" required="">
                    </li>
                    <li>
                      <textarea class="form-control textarea" name="message" id="msg1" placeholder="{{ $langg->lang388 }} *" required=""></textarea>
                    </li>
                    <input type="hidden"  name="type" value="Ticket">
                  </ul>
                  <div style="text-align:right">
                    <button  class="btn btn-orange" style="border-radius:10px!important;"  id="emlsub1" type="submit">{{ $langg->lang389 }}</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      </div>
    </div>
  </div>
</div>

{{-- MESSAGE MODAL ENDS --}}


<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

    <div class="modal-header d-block text-center">

    </div>

                <div class="modal-body">
            <p class="text-center" style="margin-top:20px;">{{ $langg->lang391 }}</p>
            <!-- <p class="text-center">{{ $langg->lang393 }}</p> -->
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-outline-danger" style="width:30%;border-radius:10px;" data-dismiss="modal">{{ $langg->lang370 }}</button>
                    <a class="btn btn-orange btn-ok" style="width:30%;border-radius:10px;">{{ $langg->lang371 }}</a>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

<script type="text/javascript">
    
          $(document).on("submit", "#emailreply1" , function(){
          var token = $(this).find('input[name=_token]').val();
          var subject = $(this).find('input[name=subject]').val();
          var message =  $(this).find('textarea[name=message]').val();
          var $type  = $(this).find('input[name=type]').val();
          $('#subj1').prop('disabled', true);
          $('#msg1').prop('disabled', true);
          $('#emlsub1').prop('disabled', true);
     $.ajax({
            type: 'post',
            url: "{{URL::to('/user/admin/user/send/message')}}",
            data: {
                '_token': token,
                'subject'   : subject,
                'message'  : message,
                'type'   : $type
                  },
            success: function( data) {
          $('#subj1').prop('disabled', false);
          $('#msg1').prop('disabled', false);
          $('#subj1').val('');
          $('#msg1').val('');
        $('#emlsub1').prop('disabled', false);
        toastr.options = {
            "closeButton": false,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-center",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
          }
        if(data == 0)
          toastr.error("{{ $langg->something_wrong }}");
        else
          toastr.success("{{ $langg->message_sent }}");
        $('.close').click();
            }

        });          
          return false;
        });

</script>


<script type="text/javascript">

      $('#confirm-delete').on('show.bs.modal', function(e) {
          $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
      });

</script>

@endsection