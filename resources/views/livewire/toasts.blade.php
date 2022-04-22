<div class="toast-container position-absolute top-0 end-0 p-3 mt-5">

    @foreach($messages as $id => $message)
        <div id="{{ $id }}" class="toast align-items-center text-white bg-success" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    {{ $message }}
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    @endforeach

</div>
