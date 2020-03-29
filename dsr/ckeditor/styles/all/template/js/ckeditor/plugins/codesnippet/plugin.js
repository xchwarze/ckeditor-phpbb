/**
 * CustomBBcode selector
 * v1.0.0
 * by DSR! (https://github.com/xchwarze)
 * 2020
 */

/**
 * Array.prototype.forEach() polyfill
 */
if (!Array.prototype.forEach) {
    Array.prototype.forEach = function (callback, thisArg) {
        thisArg = thisArg || window;
        for (var i = 0; i < this.length; i++) {
            callback.call(thisArg, this[i], i, this);
        }
    };
}

/**
 * Object.entries() polyfill
 */
if (!Object.entries) {
    Object.entries = function( obj ){
        var ownProps = Object.keys( obj ),
            i = ownProps.length,
            resArray = new Array(i); // preallocate the Array
        while (i--)
            resArray[i] = [ownProps[i], obj[ownProps[i]]];

        return resArray;
    };
}

CKEDITOR.config.codeSnippet_languages = {};

(function () {
    CKEDITOR.plugins.add('codesnippet', {
        requires: 'bbcode',
        lang: 'ar,az,bg,ca,cs,da,de,de-ch,el,en,en-au,en-gb,eo,es,es-mx,et,eu,fa,fi,fr,fr-ca,gl,he,hr,hu,id,it,ja,km,ko,ku,lt,lv,nb,nl,no,oc,pl,pt,pt-br,ro,ru,sk,sl,sq,sr,sr-latn,sv,th,tr,tt,ug,uk,vi,zh,zh-cn',
        icons: 'codesnippet',
        hidpi: true,
        init: function (editor) {
            editor.addCommand('codesnippet', new CKEDITOR.dialogCommand('codesnippet'));

            editor.ui.addButton( 'codesnippet', {
                label: editor.lang.codesnippet.button,
                command: 'codesnippet',
                toolbar: 'insert,10'
            } );

            CKEDITOR.dialog.add('codeSnippet', function (instance) {
                var snippetLangs = editor._.codesnippet.langs,
                    lang = editor.lang.codesnippet,
                    clientHeight = document.documentElement.clientHeight,
                    langSelectItems = [],
                    snippetLangId;

                langSelectItems.push( [ editor.lang.common.notSet, '' ] );

                for ( snippetLangId in snippetLangs )
                    langSelectItems.push( [ snippetLangs[ snippetLangId ], snippetLangId ] );

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
                        id: 'info',
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
                        var iframe = ytGenIframe(video);
                        var element = CKEDITOR.dom.element.createFromHtml(iframe);
                        instance.insertElement(element);
                    }
                };
            });
        }
    });
})();
