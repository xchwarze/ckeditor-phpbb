// from
// https://github.com/yfxie/ckeditor-imgur/blob/master/vendor/assets/javascripts/ckeditor/plugins/imgur/plugin.js
// https://github.com/carry0987/CKEditor-Imgur/blob/master/plugins/imgur/plugin.js
( function() {
    function _uploadImageToImgur(id, file, totalFiles, imgurClientId, placeholder, editor) {
        var form = new FormData();
        form.append('image', file);

        $.ajax({
            url: 'https://api.imgur.com/3/image',
            headers: {
                Authorization: 'Client-ID ' + imgurClientId
            },
            type: 'POST',
            processData: false,
            data: form,
            cache: false,
            contentType: false
        }).done(function(data) {
            var content = '<img src="' + data.data.link +'"/>';
            var element = CKEDITOR.dom.element.createFromHtml(content);
            editor.insertElement(element);
        }).fail(function(jqXHR) {
            var res = $.parseJSON(jqXHR.responseText);
            alert(editor.lang.imgur.failToUpload + res.data.error);
        }).always(function() {
            // if last file?
            if (totalFiles === (id + 1)) {
                placeholder.fadeOut();
            }
        });
    }

    CKEDITOR.plugins.add('imgur',
        {
            // jscs:disable maximumLineLength
            lang: ['zh', 'en', 'es'],
            icons: 'imgur', // %REMOVE_LINE_CORE%
            hidpi: true, // %REMOVE_LINE_CORE%
            init: function(editor)
            {
                var imgurClientId = editor.config.imgurClientId;
                if (!imgurClientId) {
                    alert(editor.lang.imgur.clientIdMissing);
                    return;
                }

                var placeholder = $('<div></div>').css({
                    position: 'absolute',
                    bottom: 0,
                    left: 0,
                    right: 0,
                    backgroundColor: 'rgba(20, 20, 20, .6)',
                    padding: 5,
                    color: '#fff'
                }).hide();

                editor.on('instanceReady', function () {
                    var win = $(editor.window.getFrame().$).parent();
                    win.css({ position: 'relative' });
                    placeholder.appendTo(win);
                });

                editor.ui.addButton('Imgur', {
                    label : 'Imgur',
                    toolbar : 'insert',
                    command : 'imgur-uploader',
                });

                editor.addCommand('imgur-uploader', {
                    exec: function() {
                        // TODO
                        // A binary file, base64 data, or a URL for an image. (up to 10MB)
                        // Add size check!
                        var fakeInput = $('<input type="file" multiple />');
                        fakeInput.on('change', function (event) {
                            var files = event.target.files;
                            var totalFiles = files.length;
                            placeholder.text(totalFiles + editor.lang.imgur.uploading).fadeIn();

                            $.each(files, function(id, file) {
                                _uploadImageToImgur(id, file, totalFiles, imgurClientId, placeholder, editor);
                            });
                        });

                        fakeInput.click();
                    }
                });

                // TODO?
                // editor.on('paste', function(event) {
            }
        }
    );
})();

