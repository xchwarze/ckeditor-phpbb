/**
 * Youtube BBcode tag
 * v1.0.0
 * by DSR! (https://github.com/xchwarze)
 * Based on: https://github.com/ckeditor/ckeditor4/blob/master/plugins/codesnippet/plugin.js
 */
(function () {
    CKEDITOR.plugins.add('youtube', {
        // jscs:disable maximumLineLength
        lang: [ 'en', 'bg', 'pt', 'pt-br', 'ja', 'hu', 'it', 'fr', 'tr', 'ru', 'de', 'ar', 'nl', 'pl', 'vi', 'zh', 'el', 'he', 'es', 'nb', 'nn', 'fi', 'et', 'sk', 'cs', 'ko', 'eu', 'uk'],
        icons: 'youtube', // %REMOVE_LINE_CORE%
        hidpi: true, // %REMOVE_LINE_CORE%
        beforeInit: function( editor ) {
            var config = editor.config,
                bbcodeMap = config.bbcode_bbcodeMap;

            // add new bbcode with this dirty hack
            bbcodeMap.youtube = 'youtube';
            CKEDITOR.tools.extend( config, {
                bbcode_bbcodeMap: bbcodeMap
            }, true );

            editor.dataProcessor.dataFilter.addRules({
                elements: {
                    // ugaaaaaaaa ugaaaaa!!!!!11!!!1
                    youtube: function (element) {
                        if (element.children.length === 1) {
                            var iframe = ytGenIframe(element.children[0].value);
                            return new CKEDITOR.htmlParser.text(iframe);
                        }
                    }
                }
            });
            editor.dataProcessor.htmlFilter.addRules ({
                elements: {
                    iframe: function (element) {
                        var youtubeVideo = element.attributes[ 'data-cke-youtube-id' ];
                        if (youtubeVideo) {
                            return new CKEDITOR.htmlParser.text('[youtube]' + youtubeVideo + '[/youtube]');
                        }
                    }
                }
            });
        },
        init: function (editor) {
            editor.addCommand('youtube', new CKEDITOR.dialogCommand('youtube'));

            editor.ui.addButton('Youtube', {
                label : editor.lang.youtube.button,
                toolbar : 'insert',
                command : 'youtube',
            });

            CKEDITOR.dialog.add('youtube', function (instance) {
                var video;

                return {
                    title : editor.lang.youtube.title,
                    minWidth : 300,
                    minHeight : 100,
                    contents: [{
                        id : 'youtubePlugin',
                        expand : true,
                        elements: [{
                            id : 'txtUrl',
                            type : 'text',
                            label : editor.lang.youtube.txtUrl,
                            validate : function () {
                                if (this.isEnabled()) {
                                    if (!this.getValue()) {
                                        alert(editor.lang.youtube.noCode);
                                        return false;
                                    }
                                    else{
                                        video = ytVidId(this.getValue());

                                        if (this.getValue().length === 0 ||  video === false)
                                        {
                                            alert(editor.lang.youtube.invalidUrl);
                                            return false;
                                        }
                                    }
                                }
                            }
                        }]
                    }],
                    onOk: function()
                    {
                        var iframe = ytGenIframe(video);
                        var element = CKEDITOR.dom.element.createFromHtml(iframe);
                        instance.insertElement(element);
                    }
                };
            });
        }
    });
})();

function ytVidId(url) {
    var p = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
    return (url.match(p)) ? RegExp.$1 : false;
}

function ytGenIframe(youtubeId) {
    // responsiveStyle = 'style="position:absolute;top:0;left:0;width:100%;height:100%"';
    // content += '<div class="youtube-embed-wrapper" style="position:relative;padding-bottom:56.25%;padding-top:30px;height:0;overflow:hidden">' +
    //      '<iframe ........ />' +
    // '</div>';
    return '<iframe ' +
                'src="https://www.youtube.com/embed/' + youtubeId + '?wmode=opaque" ' +
                'data-cke-youtube-id="' + youtubeId + '" ' +
                'width="560" ' +
                'height="315" ' +
                'frameborder="0">' +
            '</iframe>';
}
