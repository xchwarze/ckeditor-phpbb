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

function dsrCkeditorGenFontsConfig() {
	var parsed = '',
		fonts = Object.entries(ckeConfig.defaultFontSizes);
	fonts.forEach(function(element){
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
	smileys.forEach(function(element){
		parsed.images.push(element[1]['src'].slice(2));
		parsed.descriptions.push(element[0]);
		parsed.relations[element[0]] = element[0];
	});

	return parsed;
}

( function() {
	var is_message = document.getElementsByName('message').length !== 0,
		is_signature  = document.getElementsByName('signature').length !== 0;

	if (!is_message && !is_signature) {
		console.warn('[CKEDITOR-PHPBB] target not found!');
		return;
	}

	var fontSize_sizes = dsrCkeditorGenFontsConfig(),
		smiley_config  = dsrCkeditorGenSmileyConfig(),
		config = {
			toolbarGroups: ckeConfig.toolbarGroups,
			removeButtons: ckeConfig.removeButtons,
			removeDialogTabs: 'link:advanced',
			title: false,
			disableObjectResizing: true,
			extraPlugins: 'bbcode,custombbcode,youtube,mentions',
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
			bbcode_smileyMap: smiley_config.relations,
			customBBcode_codes: ckeConfig.defaultCustomBBcode,
			fontSize_sizes: fontSize_sizes,
			smiley_images: smiley_config.images,
			smiley_descriptions: smiley_config.descriptions,
			smiley_path: './',
			autosave: {
				saveDetectionSelectors: 'input[name*="post"],input[name*="save"],input[name*="preview"]',
				saveOnDestroy: false,
				messageType: 'no',
			},
			//image_prefillDimensions: false,
			on: {
				setData: function(event) {
					// TODO change this!
					// fix bbcode in editor
					var url = document.URL;
					if (url.indexOf('posting.php?mode=quote') || 0 <= url.indexOf('ucp.php?i=pm') || 0 <= url.indexOf('#preview')) {
						var bbcode = event.data.dataValue;
						bbcode = bbcode.replace(/([\s\S]*)\/quote]/, "$1\/quote][justify][/justify]");

						event.data.dataValue = bbcode;
					}
				},
			}
		};

	// phpbb-ext-highlighter
	if (ckeConfig.codeSnippetTheme) {
		config.extraPlugins = config.extraPlugins + ',codesnippet';
		config.codeSnippet_theme = ckeConfig.codeSnippetTheme;
		config.codeSnippet_languages = ckeConfig.codeSnippetLanguages;
	}

	// paul999 mentions
	if (typeof U_AJAX_MENTION_URL !== 'undefined') {
		config.mentions = [
			{
				feed: function(options, callback) {
					if (options.query.length < MIN_MENTION_LENGTH) {
						callback([]);
						return;
					}

					$.getJSON(
						U_AJAX_MENTION_URL,
						{ q: options.query },
						function (data) {
							callback(data);
						}
					);
				},
				marker: '@',
				minChars: MIN_MENTION_LENGTH,
				throttle: 500,
				itemTemplate: '<li data-id="{user_id}">{value}</li>',
				// TODO hay que ver como hacer esto mas vistozo dandole soporte en el editor
				//outputTemplate: '<a href="/tracker/{user_id}">@{value}</a>'
				outputTemplate: '[smention u={user_id}]{value}[/smention]'
			},
		];
	}

	// imgur
	if (ckeConfig.imgurClientId) {
		config.extraPlugins = config.extraPlugins + ',imgur';
		config.imgurClientId = ckeConfig.imgurClientId;
	}

	if (ckeConfig.lang) {
		config.language = ckeConfig.lang;
	}

	CKEDITOR.replace(is_message ? 'message' : 'signature', config);
} )();

