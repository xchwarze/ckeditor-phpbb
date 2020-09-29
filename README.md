# CKEDITOR for phpBB [![Build Status](https://travis-ci.org/xchwarze/ckeditor-phpbb.svg?branch=master)](https://travis-ci.org/xchwarze/ckeditor-phpbb)

### Editable textarea
![Example of editor](docs/example.png)

## Features
* Basic HTML tags ('li', 'ul', 's', 'sub', 'sup', 'left', 'right', 'center', 'justify', 'font=', 'ol', 'table', 'td', 'tr', 'hr')
* Autosave
* Youtube tag
* Custom bbcode tags support
* Imgur
* Mentions
* Code Snippet and syntax highlighter
* Clean code (no spaghetti code)

## How to install
* Just copy folder dsr/ to {PHPBB_ROOT}/ext/

## Custom BBcode tags
* Place custom buttons icons in ./images/editor/custom/
* The name format is:
	{bbcode}.png (normal size)
	{bbcode}.hidpi.png (big size)
* Image size:
	normal size: 16 x 16
	big size: 32 x 32

## Imgur
* Create Imgur app
* Configure imgurClientId in ext/dsr/ckeditor/event/main_listener.php
```
    'CKE_CONFIG' => json_encode([
        'lang' => $this->_get_lang(),
        'maxFontSize' => $this->config['max_post_font_size'],
        'toolbarGroups' => $is_viewtopic ? $editor_quick_toolbar : $editor_normal_toolbar,
        'removeButtons' => $is_viewtopic ? $remove_buttons_quick_toolbar : $remove_buttons_normal_toolbar,
        'codeSnippetTheme' => $code_snippet_theme,
        'codeSnippetLanguages' => $code_snippet_languages,
        'imgurClientId' => 'xxxxxxxxxxxxxxxxxxxxx',
    ]),
```

## Mentions
* Install https://github.com/paul999/mention from master

## Code Snippet and syntax highlighter
* Install https://github.com/s9e/phpbb-ext-highlighter
* Configure code_snippet_languages in ext/dsr/ckeditor/event/main_listener.php
```
    $code_snippet_theme = 'monokai_sublime';
    $code_snippet_languages = [
        'arduino' => 'Arduino',
        'autoit' => 'Autoit',
        'bash' => 'Bash',
        'basic' => 'Basic',
        'cpp' => 'C/C++',
        'cs' => 'C#',
        'css' => 'CSS',
        'delphi' => 'Delphi'
    ];
```

## Compatibility ##
* PHPBB 3.2.x and 3.3.x
