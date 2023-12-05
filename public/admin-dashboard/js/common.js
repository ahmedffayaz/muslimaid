

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            var preview = $('#logo-preview');
            preview.removeClass('d-none');

            preview.attr('src', e.target.result)
                .css('max-width', 80)
                .css('max-height', 120);
        };

        reader.readAsDataURL(input.files[0]);
    }
}
function readLinkURL(input) {
    if (input.value) {
        if (input.id === "logo_link") {
            $('#logo_link-preview').attr('src', input.value).removeClass('d-none');
        } else if (input.id === "banner_link") {
            $('#banner_link-preview').attr('src', input.value).removeClass('d-none');
        }
    }
}
function readBannerURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            if (input.id === "logo_upload") {
                $('#logo-preview').attr('src', e.target.result).removeClass('d-none');
            } else if (input.id === "banner_upload") {
                $('#banner-preview').attr('src', e.target.result).removeClass('d-none');
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function initializeTinyMCEEditor(containerId){
    tinymce.init({
        selector: 'div#'+containerId, // Replace this CSS selector to match the placeholder element for TinyMCE
        plugins: 'code table lists link image',
        toolbar: 'blocks | bold italic underline | alignleft aligncenter alignright alignjustify | link unlink | image | bullist numlist | code | table | forecolor backcolor',
        cleanup: true,
        promotion: false,
        branding: false,
        menubar: 'edit view insert format tools table'
    });
}

function spinner(isTrue = false){
    let loader;
    if(isTrue){
    loader = `<div class="text-center">
    <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
        <span class="sr-only">Loading...</span>
    </div>
    </div>`;
    }
    return loader;
}

function maintenanceConfirmationDialog(title, text, confirmButtonText, maintenance, token, url){
    Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: confirmButtonText
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    _token: token,
                    maintenance: maintenance
                },
                success: function(data) {
                    (function(NioApp, $) {
                        'use strict';
                        toastr.clear();
                        NioApp.Toast(data.message, data.response);
                    })(NioApp, jQuery);
                },
                error: function(data) {

                    (function(NioApp, $) {
                        'use strict';
                        toastr.clear();
                        NioApp.Toast(data.message, data.response);
                    })(NioApp, jQuery);

                }
            });
        }
    });
}

function deleteReviewConfirmationDialog(form_id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!'
    }).then(function(result) {
        if (result.value) {
            $('#' + form_id).submit();
        }
    });
}

function openEditReviewModal(reviewId, url, token, call){
    $.ajax({
        url: url,
        method: "GET",
        data: {
            _token: token
        },
        success: function(data) {
            $('#review-modal').modal('show');
            $('#review').html(data);
            if(call == "dashboard"){
                updateReview(1);
            }
            else if (call == "reviewMenu"){
                updateReview(2);
            }
        }
    });
}

function updateReview(key){
    $('#store-reviews-form').on('submit', function(event) {
        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: 'PUT',
            data: $(this).serialize(),
            success: function(data) {
                $('#review-modal').modal('hide');
                (function(NioApp, $) {
                    'use strict';
                    toastr.clear();
                    NioApp.Toast(data.message, 'success');

                })(NioApp, jQuery);
                if(key == 1){
                    fetchData(0);
                }
                else if (key == 2){
                    $('#table-data').load(window.location.href + ' #table-data');
                }
            },
            error: function(data) {
                (function(NioApp, $) {
                    'use strict';
                    toastr.clear();
                    NioApp.Toast('Something went wrong! unable to update the review', 'error');
                })(NioApp, jQuery);
            }
        })
    });
}
