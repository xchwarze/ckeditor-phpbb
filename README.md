# CKEDITOR for phpBB [![Build Status](https://travis-ci.org/xchwarze/ckeditor-phpbb.svg?branch=master)](https://travis-ci.org/xchwarze/ckeditor-phpbb)

### Editable textarea
![Example of editor](docs/example.png)

## Features
* Basic HTML tags ('li', 'ul', 's', 'sub', 'sup', 'left', 'right', 'center', 'justify', 'font=', 'ol', 'table', 'td', 'tr', 'hr')
* Autosave
* Youtube tag
* Custom bbcode tags support
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

## Compatibility ##
* PHPBB 3.2.x and 3.3.x
