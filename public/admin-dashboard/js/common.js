

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