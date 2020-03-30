/**
 * CustomBBcode selector
 * v1.0.0
 * by DSR! (https://github.com/xchwarze)
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

CKEDITOR.config.customBBcode_codes = {};

(function () {
    CKEDITOR.plugins.add('customBBcode', {
        requires: 'bbcode',
        init: function(editor) {
            var bbcodes = Object.entries(editor.config.customBBcode_codes);
            bbcodes.forEach(function(bbcode){
                var name = bbcode[0],
                    info = bbcode[1],
                    command = 'custom' + name;

                editor.addCommand(command, {
                    exec: function(editor) {
                        if (editor.mode === 'wysiwyg') {
                            var text = editor.getSelectedHtml().getHtml();
                            editor.insertHtml('[' + name + ']' + text + '[/' + name + ']');
                        } else {
                            var sourceContainer = jQuery('textarea.cke_source'),
                                selectionStart  = sourceContainer[0].selectionStart,
                                selectionEnd    = sourceContainer[0].selectionEnd,
                                rawText         = sourceContainer.val();

                            if (selectionStart && selectionEnd && rawText) {
                                sourceContainer.val(
                                    rawText.substring(0, selectionStart) +
                                    '[' + name + ']' + rawText.substring(selectionStart, selectionEnd) + '[/' + name + ']' +
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
                editor.ui.addButton(name, {
                    label: info,
                    command: command,
                    icon: 'custombbcode/' + name + '.png',
                    iconHiDpi: 'custombbcode/' + name + '.hidpi.png',
                });
            });
        }
    });
})();
