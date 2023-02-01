@props(['modalSize', 'headerAlignment', 'formWrapperClass'])
<!-- @@ Category Modal @e -->
<div class="modal fade" tabindex="-1" role="dialog" id="modal">
    <div class="modal-dialog {{ $modalSize }}" role="document">
        <div class="modal-content">
            <div class="modal-header {{ $headerAlignment }}">
                <div class="nk-file-title">
                    <div class="nk-file-name">
                        <div class="nk-file-name-text"><span class="title"></span></div>
                    </div>
                </div>
                <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
            </div>
            <div id="form-wrapper" class="{{ $formWrapperClass }} p-4"></div>
        </div><!-- .modal-content -->
    </div><!-- .modla-dialog -->
</div><!-- .modal -->
