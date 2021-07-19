/**
 * Simple Code Snippet Selector
 * v1.1.0
 * by DSR! (https://github.com/xchwarze)
 * Based on: https://github.com/ckeditor/ckeditor4/blob/master/plugins/codesnippet/plugin.js
 */

CKEDITOR.config.codeSnippet_languages = {};

(function () {
    CKEDITOR.plugins.add('codesnippet', {
        requires: 'bbcode',
        lang: 'ar,az,bg,ca,cs,da,de,de-ch,el,en,en-au,en-gb,eo,es,es-mx,et,eu,fa,fi,fr,fr-ca,gl,he,hr,hu,id,it,ja,km,ko,ku,lt,lv,nb,nl,no,oc,pl,pt,pt-br,ro,ru,sk,sl,sq,sr,sr-latn,sv,th,tr,tt,ug,uk,vi,zh,zh-cn',
        icons: 'codesnippet',
        hidpi: true,
        init: function (editor) {
            var codeSnippetLangs = editor.config.codeSnippet_languages;

            editor.ui.addButton( 'codesnippet', {
                label: editor.lang.codesnippet.button,
                command: 'codeSnippetView',
                toolbar: 'insert,10'
            } );

            editor.addCommand('codeSnippetView', new CKEDITOR.dialogCommand('codeSnippetView'));

            editor.addCommand('codesnippet', {
                exec: function(editor) {
                    if (editor.mode === 'wysiwyg') {
                        var text = editor.getSelectedHtml().getHtml();
                        editor.insertHtml('[code]' + text + '[/code]');
                    } else {
                        var sourceContainer = jQuery('textarea.cke_source'),
                            selectionStart  = sourceContainer[0].selectionStart,
                            selectionEnd    = sourceContainer[0].selectionEnd,
                            rawText         = sourceContainer.val();

                        if (selectionStart && selectionEnd && rawText) {
                            sourceContainer.val(
                                rawText.substring(0, selectionStart) +
                                '[code]' + rawText.substring(selectionStart, selectionEnd) + '[/code]' +
                                rawText.substring(selectionEnd)
                            );
                        }
                    }
                },
                modes: {
                    wysiwyg: 1,
                    source: 1
                }
            });

            CKEDITOR.dialog.add('codeSnippetView', function (instance) {
                var snippetLangs = codeSnippetLangs,
                    lang = editor.lang.codesnippet,
                    clientHeight = document.documentElement.clientHeight,
                    langSelectItems = [],
                    snippetLangId;

                langSelectItems.push( [ editor.lang.common.notSet, '' ] );

                for ( snippetLangId in snippetLangs ) {
                    var item = snippetLangs[ snippetLangId ];
                    if (item.hasOwnProperty('label')) {
                        langSelectItems.push( [ item.label, item.lang ] );
                    }
                }

                // TODO entiendo que esto es codigo legacy del plugin original
                // Size adjustments.
                var size = CKEDITOR.document.getWindow().getViewPaneSize(),
                    // Make it maximum 800px wide, but still fully visible in the viewport.
                    width = Math.min( size.width - 70, 800 ),
                    // Make it use 2/3 of the viewport height.
                    height = size.height / 1.5;

                // Low resolution settings.
                if ( clientHeight < 650 ) {
                    height = clientHeight - 220;
                }

                return {
                    title: lang.title,
                    minHeight: 200,
                    resizable: CKEDITOR.DIALOG_RESIZE_NONE,
                    contents: [{
                        id: 'codeSnippetView',
                        elements: [
                            {
                                id: 'lang',
                                type: 'select',
                                label: lang.language,
                                items: langSelectItems,
                            },
                            {
                                id: 'code',
                                type: 'textarea',
                                label: lang.codeContents,
                                required: true,
                                validate: CKEDITOR.dialog.validate.notEmpty( lang.emptySnippetError ),
                                inputStyle: 'cursor:auto;' +
                                    'width:' + width + 'px;' +
                                    'height:' + height + 'px;' +
                                    'tab-size:4;' +
                                    'text-align:left;',
                                'class': 'cke_source'
                            }
                        ]}
                    ],
                    onOk: function() {
                        var lang = this.getValueOf('codeSnippetView', 'lang'),
                            code = this.getValueOf('codeSnippetView', 'code'),
                            openTag = 'code' + (lang ? '=' + lang : '');

                        editor.insertHtml('[' + openTag + ']' + code + '[/code]');
                    }
                };
            });
        }
    });
})();
