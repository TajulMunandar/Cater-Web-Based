@props([
    'id',
    'modalSize' => '',
    'title',
    'route' => '',
    'method' => 'POST',
    'primaryBtnClass' => 'btn-dark',
    'secondaryBtnClass' => 'btn-light',
    'primaryBtnTitle' => 'Submit',
    'secondaryBtnTitle' => 'Cancel',
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog {{ $modalSize }}" role="document">
        <form action="{{ $route }}" method="post" enctype="multipart/form-data" id="dynamicModalForm">
            <div class="modal-content">
                <div class="modal-header bg-dark">
                    <h5 class="modal-title text-white">{{ $title }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                @csrf
                @method($method)
                <div class="modal-body" id="modalBodyContent">
                    {{ $slot }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn {{ $secondaryBtnClass }}" data-bs-dismiss="modal">
                        {{ $secondaryBtnTitle }}
                    </button>
                    <button type="submit" class="btn {{ $primaryBtnClass }}" id="modalSubmitBtn">
                        {{ $primaryBtnTitle }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
