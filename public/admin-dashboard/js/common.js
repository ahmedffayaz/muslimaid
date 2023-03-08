

var route_prefix = "/filemanager";

(function ($) {

    $.fn.filemanager = function (type, options) {
        type = type || 'file';

        this.on('click', function (e) {
            var route_prefix = (options && options.prefix) ? options.prefix : '/filemanager';
            var target_input = $('#' + $(this).data('input'));
            var target_preview = $('#' + $(this).data('preview'));
            window.open(route_prefix + '?type=' + type, 'FileManager', 'width=900,height=600');
            window.SetUrl = function (items) {
                var file_path = items.map(function (item) {
                    return item.url;
                }).join(',');

                // set the value of the desired input to image url
                target_input.val('').val(file_path).trigger('change');

                // clear previous preview
                target_preview.html('');

                // set or change the preview image src
                items.forEach(function (item) {
                    target_preview.append(
                        $('<img>').css('height', '5rem').attr('src', item.thumb_url)
                    );
                });

                // trigger change event
                target_preview.trigger('change');
            };
            return false;
        });
    }

})(jQuery);
$('#lfm').filemanager('image', {
    prefix: route_prefix
});
var lfm = function (id, type, options) {
    let button = document.getElementById(id);
    if (button) {
    button.addEventListener('click', function () {
        var route_prefix = (options && options.prefix) ? options.prefix : '/filemanager';
        var target_input = document.getElementById(button.getAttribute('data-input'));
        var target_preview = document.getElementById(button.getAttribute('data-preview'));

        window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
        window.SetUrl = function (items) {
            var file_path = items.map(function (item) {
                return item.url;
            }).join(',');

            // set the value of the desired input to image url
            target_input.value = file_path;
            target_input.dispatchEvent(new Event('change'));

            // clear previous preview
            target_preview.innerHtml = '';

            // set or change the preview image src
            items.forEach(function (item) {
                let img = document.createElement('img')
                img.setAttribute('style', 'height: 5rem')
                img.setAttribute('src', item.thumb_url)
                target_preview.appendChild(img);
            });

            // trigger change event
            target_preview.dispatchEvent(new Event('change'));
        };
    });
  }
};

lfm('lfm2', 'file', {
    prefix: route_prefix
});


    // Define function to open filemanager window
    var lfm = function (options, cb) {
        var route_prefix = (options && options.prefix) ? options.prefix : '/filemanager';
        window.open(route_prefix + '?type=' + options.type || 'file', 'FileManager', 'width=900,height=600');
        window.SetUrl = cb;
    };

    // Define LFM summernote button
    var LFMButton = function (context) {
        var ui = $.summernote.ui;
        var button = ui.button({
            contents: '<i class="note-icon-picture"></i> ',
            tooltip: 'Insert image with filemanager',
            click: function () {

                lfm({
                    type: 'image',
                    prefix: '/filemanager'
                }, function (lfmItems, path) {
                    lfmItems.forEach(function (lfmItem) {
                        context.invoke('insertImage', lfmItem.url);
                    });
                });

            }
        });
        return button.render();
    };

  
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
