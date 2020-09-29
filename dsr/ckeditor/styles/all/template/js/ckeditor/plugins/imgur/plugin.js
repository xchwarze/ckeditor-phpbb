// from
// https://github.com/yfxie/ckeditor-imgur/blob/master/vendor/assets/javascripts/ckeditor/plugins/imgur/plugin.js
// https://github.com/carry0987/CKEditor-Imgur/blob/master/plugins/imgur/plugin.js
( function() {
    CKEDITOR.plugins.add('imgur',
        {
            // jscs:disable maximumLineLength
            lang: ['zh', 'en', 'es'],
            icons: 'imgur', // %REMOVE_LINE_CORE%
            hidpi: true, // %REMOVE_LINE_CORE%
            init: function( editor )
            {
                var ClientId = editor.config.imgurClientId;
                if (!ClientId) {
                    alert(editor.lang.imgur.clientIdMissing);
                    return;
                }

                var count = 0;
                var $placeholder = $('<div></div>').css({
                    position: 'absolute',
                    bottom: 0,
                    left: 0,
                    right: 0,
                    backgroundColor: 'rgba(20, 20, 20, .6)',
                    padding: 5,
                    color: '#fff'
                }).hide();

                editor.on('instanceReady', function () {
                    var $w = $(editor.window.getFrame().$).parent();
                    $w.css({ position: 'relative' });
                    $placeholder.appendTo($w);
                });

                editor.ui.addButton('Imgur', {
                    label : 'Imgur',
                    toolbar : 'insert',
                    command : 'imgur',
                });

                editor.addCommand('imgur', {
                    exec: function() {
                        // TODO
                        // A binary file, base64 data, or a URL for an image. (up to 10MB)
                        // Add size check!
                        $input = $('<input type="file" multiple/>');
                        $input.on('change', function (e) {
                            files = e.target.files;
                            $.each(files, function(i, file){
                                count++;
                                form = new FormData();
                                form.append('image', file);

                                $.ajax({
                                    url: 'https://api.imgur.com/3/image',
                                    headers: {
                                        Authorization: 'Client-ID ' + ClientId
                                    },
                                    type: 'POST',
                                    processData: false,
                                    data: form,
                                    cache: false,
                                    contentType: false
                                }).done(function(data){
                                    count--;
                                    $placeholder.text(count + editor.lang.imgur.uploading).toggle(count != 0);

                                    var content = '<img src="' + data.data.link +'"/>';
                                    var element = CKEDITOR.dom.element.createFromHtml(content);
                                    editor.insertElement(element);
                                }).fail(function(jqXHR){
                                    count--;
                                    $placeholder.text(count + editor.lang.imgur.uploading).toggle(count != 0);

                                    var res = $.parseJSON(jqXHR.responseText);
                                    alert(editor.lang.imgur.failToUpload + res.data.error);
                                });
                            });

                            $placeholder.text(count + editor.lang.imgur.uploading).fadeIn();
                        });

                        $input.click();
                    }
                });
            }
        });
})();

