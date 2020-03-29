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
	if (document.getElementsByName('message').length === 0 &&
		document.getElementsByName('signature').length === 0) {
		console.log('[CKEDITOR-PHPBB] target not found!');
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
			extraPlugins: 'bbcode,customBBcode,youtube',
			bbcode_bbcodeMap: {
				b: 'strong', u: 'u', i: 'em', s: 's', sub: 'sub', sup: 'sup', color: 'span', size: 'span', left: 'div', right: 'div',
				center: 'div', justify: 'div', quote: 'blockquote', code: 'code', url: 'a', email: 'span', img: 'span', '*': 'li',
				list: 'ol', hr: 'hr', table: 'table', td: 'td', tr: 'tr'
			},
			bbcode_convertMap: {
				strong: 'b', b: 'b', u: 'u', em: 'i', i: 'i', s: 's', sub: 'sub', sup: 'sup', code: 'code', li: '*'
			},
			bbcode_tagnameMap: {
				strong: 'b', em: 'i', u: 'u', s: 's', sub: 'sub', sup: 'sup', li: '*', ul: 'list', ol: 'list', code: 'code', a: 'link',
				img: 'img', blockquote: 'quote', hr: 'hr', table: 'table', td: 'td', tr: 'tr'
			},
			bbcode_smileyMap: smiley_config.relations,
			customBBcode_codes: ckeConfig.defaultCustomBBcode,
			fontSize_sizes: fontSize_sizes,
			smiley_images: smiley_config.images,
			smiley_descriptions: smiley_config.descriptions,
			smiley_path: './',
			autosave_saveDetectionSelectors: 'input[name*="post"],input[name*="save"],input[name*="preview"]',
			autosave_saveOnDestroy: false,
			on: {
				setData: function(evt) {
					// TODO change this!
					// fix bbcode in editor
					var url = document.URL;
					if (url.indexOf('posting.php?mode=quote') || 0 <= url.indexOf('ucp.php?i=pm') || 0 <= url.indexOf('#preview')) {
						var bbcode = evt.data.dataValue;
						bbcode = bbcode.replace(/([\s\S]*)\/quote]/, "$1\/quote][justify][/justify]");

						evt.data.dataValue = bbcode;
					}
				},
			}
		};

	if (ckeConfig.lang) {
		config.language = ckeConfig.lang;
	}

	if (document.getElementsByName('message').length) {
		CKEDITOR.replace('message', config);
	} else {
		CKEDITOR.replace('signature', config);
	}
} )();

