function dsrCkeditorGenFontsConfig() {
    var parsed = '',
        fonts = Object.entries(ckeConfig.defaultFontSizes);

    fonts.forEach(function (element) {
        if (parseInt(element[1]) <= parseInt(ckeConfig.maxFontSize)) {
            parsed += element[0] + '/' + element[1] + '%;';
        }
    });

    return parsed;
}

function dsrCkeditorGenSmileyConfig() {
    var parsed = {
            images: [],
            descriptions: [],
            relations: {},
        },
        smileys = Object.entries(ckeConfig.defaultEmoticonsBBcode);

    smileys.forEach(function (element) {
        parsed.images.push(element[1]['src'].slice(2));
        parsed.descriptions.push(element[0]);
        parsed.relations[element[0]] = element[0];
    });

    return parsed;
}

function handleDragDrop( e )
{
    if( phpbb.plupload ) {
        // Dispatch drop events to the original message window
        var orig = document.getElementById( phpbb.plupload.config.drop_element );
        if( orig ) {
            orig.dispatchEvent( new DragEvent( e.name, e.data.$ ) );
            e.cancel();
        }
    }
}

(function () {
    var is_message = document.getElementsByName('message').length !== 0,
        is_signature = document.getElementsByName('signature').length !== 0;

    if (!is_message && !is_signature) {
        console.warn('[CKEDITOR-PHPBB] target not found!');
        return;
    }

    var fontSize_sizes = dsrCkeditorGenFontsConfig(),
        config = {
            height: ckeConfig.editorHeight,
            resize_minWidth: 300,
            title: false,
            disableObjectResizing: true,
            disableNativeSpellChecker: false,
            customConfig: '',
            stylesSet: false,
            toolbarGroups: ckeConfig.toolbarGroups,
            removeButtons: ckeConfig.removeButtons,
            removeDialogTabs: 'image:advanced;link:advanced',
            extraPlugins: ['bbcode', 'custombbcode', 'youtube', 'mentions'],
            removePlugins: [],
            bbcode_bbcodeMap: {
                b: 'strong', u: 'u', i: 'em', s: 's', sub: 'sub', sup: 'sup', color: 'span', size: 'span', left: 'div', right: 'div',
                center: 'div', justify: 'div', quote: 'blockquote', code: 'code', url: 'a', email: 'span', img: 'span', '*': 'li',
                list: 'ol', hr: 'hr', table: 'table', td: 'td', tr: 'tr'
            },
            bbcode_convertMap: {
                strong: 'b', b: 'b', u: 'u', em: 'i', i: 'i', s: 's', sub: 'sub', sup: 'sup', li: '*'
            },
            bbcode_tagnameMap: {
                strong: 'b', em: 'i', u: 'u', s: 's', sub: 'sub', sup: 'sup', li: '*', ul: 'list', ol: 'list', code: 'code', a: 'link',
                img: 'img', blockquote: 'quote', hr: 'hr', table: 'table', td: 'td', tr: 'tr'
            },
            bbcode_attributesMap: {
                url: 'href', email: 'mailhref', quote: 'cite', list: 'listType', code: 'data-cke-code-lang'
            },
            bbcode_smileyMap: false,
            customBBcode_codes: ckeConfig.defaultCustomBBcode,
            fontSize_sizes: fontSize_sizes,
            image_previewText: ' ',
            image_prefillDimensions: false,
            on: {
                // fix bbcode in editor
                setData: function (event) {
                    // 'posting.php?mode=quote', 'ucp.php?i=pm', '#preview'
                    var url_regex = /\?mode=quote|\?i=pm|#preview/gi;
                    if (url_regex.test(document.URL)) {
                        // TODO change this!
                        var bbcode = event.data.dataValue;
                        bbcode = bbcode.replace(/([\s\S]*)\/quote]/, "$1\/quote][justify][/justify]");

                        event.data.dataValue = bbcode;
                    }
                },
                drag: handleDragDrop,
                dragend: handleDragDrop,
                dragenter: handleDragDrop,
                dragleave: handleDragDrop,
                dragover: handleDragDrop,
                dragstart: handleDragDrop,
                drop: handleDragDrop
                
            }
        };

    if (ckeConfig.useAutoSave) {
        config.extraPlugins.push('autosave');
        config.autosave = {
            //Savekey: 'autosave_' + window.location.pathname + '_' + window.location.search,
            //autoLoad: false,
            NotOlderThen: 180,
            saveDetectionSelectors: 'input[name*="post"],input[name*="save"],input[name*="preview"]',
            saveOnDestroy: false,
            messageType: 'no',
        };
    } else {
        config.removePlugins.push('autosave');
    }

    if (ckeConfig.useEmojis) {
        config.removePlugins.push('smiley');
        config.extraPlugins.push('emoji');
        config.emoji_minChars = 2;
    } else {
        config.removePlugins.push('emoji');
        config.extraPlugins.push('smiley');

        var smiley_config = dsrCkeditorGenSmileyConfig();
        config.bbcode_smileyMap = smiley_config.relations;
        config.smiley_images = smiley_config.images;
        config.smiley_descriptions = smiley_config.descriptions;
        config.smiley_path = './';
    }

    // This field can be a string or a bool
    if (ckeConfig.forcePasteAsText.length > 1) {
        config.forcePasteAsPlainText = ckeConfig.forcePasteAsText;
    } else {
        config.forcePasteAsPlainText = ckeConfig.forcePasteAsText === '1';
    }

    if (ckeConfig.forceSourceOnMobile && (CKEDITOR.env.mobile || CKEDITOR.env.iOS)) {
        config.startupMode = 'source';
    }

    // phpbb-ext-highlighter
    if (ckeConfig.codeSnippetTheme) {
        config.extraPlugins.push('codesnippet');
        config.codeSnippet_theme = ckeConfig.codeSnippetTheme;
        config.codeSnippet_languages = ckeConfig.codeSnippetLanguages;
    }

    // paul999 mentions
    if (typeof U_AJAX_MENTION_URL !== 'undefined') {
        config.mentions = [{
            feed: function (options, callback) {
                if (options.query.length < MIN_MENTION_LENGTH) {
                    callback([]);
                    return;
                }

                $.getJSON(
                    U_AJAX_MENTION_URL,
                    {q: options.query},
                    function (data) {
                        callback(data);
                    }
                );
            },
            marker: '@',
            minChars: MIN_MENTION_LENGTH,
            throttle: 500,
            itemTemplate: '<li data-id="{user_id}">{value}</li>',
            //outputTemplate: '<a href="/tracker/{user_id}">@{value}</a>'
            outputTemplate: '[smention u={user_id}]{value}[/smention]'
        }];
    }

    // imgur
    if (ckeConfig.imgurClientId) {
        config.extraPlugins.push('imgur');
        config.imgurClientId = ckeConfig.imgurClientId;
    }

    // Editor Resizing
    if (ckeConfig.isQuickEditor) {
        config.resize_enabled = false;
    }

    if (ckeConfig.lang) {
        config.language = ckeConfig.lang;
    }

    // load editor
    CKEDITOR.replace(is_message ? 'message' : 'signature', config);
})();
