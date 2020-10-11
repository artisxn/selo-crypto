@extends('layouts.front')
@section('content')


  <!-- Breadcrumb Area Start -->
  <div class="breadcrumb-area">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
        <ul class="pages">
          <li>
            <a href="{{ route('front.index') }}">
              {{ $langg->lang17 }}
            </a>
          </li>
          <li>
            <a href="#">
              All Category
            </a>
          </li>
        </ul> 
        </div>
      </div>
    </div>
  </div>
  <!-- Breadcrumb Area End -->

  <!-- Blog Page Area Start -->
  <section class="blogpagearea">
    <div class="container">
      <div id="ajaxContent">

      <div class="container">

			<div class="card" style="background: #FFFFFF 0% 0% no-repeat padding-box;box-shadow: 0px 2px 9px #00000029;border-radius: 10px;">
				<div class="card-body">
				
					<div class="row">
						<div class="col-md-12">
							<h6 style="color:#212121;font-weiht:bold;"><span style="color:#F47623!important;">All Category </span></h6>
							<p></p>

              
							<div class="row">
								@foreach($categori as $cat)
									<div class="col-md-2">
										<a href="{{ route('front.category',$cat->slug) }}" class="single-category">
											<div class="card" style="border: 1px solid red;">
												<div class="card-body" style="text-align:center;">
													<img src="{{asset('assets/images/categories/'.$cat->image) }}" alt="">
												</div>
											</div>
											<p style="text-align:center;">{{ $cat->name }}</p>
										</a>
									</div>
								@endforeach
							</div>
						</div>

						
						
					</div>

				</div>
			</div>
		</div>


</div>

    </div>
  </section>
  <!-- Blog Page Area Start -->




@endsection


@section('scripts')

<script type="text/javascript">
  

    // Pagination Starts

    $(document).on('click', '.pagination li', function (event) {
      event.preventDefault();
      if ($(this).find('a').attr('href') != '#' && $(this).find('a').attr('href')) {
        $('#preloader').show();
        $('#ajaxContent').load($(this).find('a').attr('href'), function (response, status, xhr) {
          if (status == "success") {
            $("html,body").animate({
              scrollTop: 0
            }, 1);
            $('#preloader').fadeOut();


          }

        });
      }
    });

    // Pagination Ends

</script>


@endsection