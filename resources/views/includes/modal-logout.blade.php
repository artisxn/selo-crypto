<div class="modal fade" id="confirm-logout" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

    <div class="modal-header d-block text-center">
    </div>

                <div class="modal-body">
            <p class="text-center" style="margin-top:20px;">Apakah kamu yakin akan keluar?</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-outline-danger" style="width:30%;border-radius:10px;" data-dismiss="modal">{{ $langg->lang370 }}</button>
                    <a href="{{ route('user-logout') }}" class="btn btn-orange" style="width:30%;border-radius:10px;">{{ __('Keluar') }}</a>
                </div>
            </div>
        </div>
    </div>
