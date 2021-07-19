<?php

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

$lang = array_merge($lang, [
    // GENERIC
    'ACP_DSR_CKE_TITLE'			=>	'CKEditor 4',
    'ACP_DSR_CKE_SETTING'		=>	'Ajustes',
    'ACP_DSR_CKE_EXPLAIN'		=>	'Mejora los editores de texto que usa el usuario por el poderoso CKEditor',
    'ACP_DSR_CKE_SAVED'		    =>  'Cambios guardados.',
    'ACP_DSR_CKE_JSON_ERROR'    =>  'El arreglo JSON ingresado no es válido.',

    // ACP TEXTS
    'ACP_DSR_CKE_STATUS'		        =>	'Ajustes generales',
    'ACP_DSR_CKE_STATUS_TEXT'          	=>	'Habilitar',
    'ACP_DSR_CKE_USE_AUTO_SAVE_TEXT'    =>  'Usar autoguardado',
    'ACP_DSR_CKE_USE_AUTO_SAVE_DESC'    =>  'Activa el mecanismo de autoguardado',
    'ACP_DSR_CKE_CACHE_TIME_TEXT'       =>  'Tiempo de cache',
    'ACP_DSR_CKE_CACHE_TIME_DESC'       =>  'Todas las querys que realice esta extensión serán cacheadas por estos segundos.<br> Para debug puedes ponerlo en 0 y su valor máximo es 86400',

    'ACP_DSR_CKE_TOOLBAR'		                =>	'Ajustes de toolbars',
    'ACP_DSR_CKE_NORMAL_TOOLBAR_GROUPS_TEXT'	=>	'Grupos de herramientas',
    'ACP_DSR_CKE_NORMAL_TOOLBAR_GROUPS_DESC'	=>	'De esta forma llama CKEditor al conjunto de herramientas que se muestran en el editor',
    'ACP_DSR_CKE_QUICK_TOOLBAR_GROUPS_TEXT'	    =>	'Grupos de herramientas en respuesta rápida',
    'ACP_DSR_CKE_QUICK_TOOLBAR_GROUPS_DESC'	    =>	'En el "Toolbar Configurator" las encontraras como el arreglo que sigue a config.toolbarGroups',
    'ACP_DSR_CKE_NORMAL_REMOVE_BUTTONS_TEXT'    =>  'Remover botones de grupo',
    'ACP_DSR_CKE_NORMAL_REMOVE_BUTTONS_DESC'    =>  'De esta forma puedes excluir herramientas que son usadas en un grupo',
    'ACP_DSR_CKE_QUICK_REMOVE_BUTTONS_TEXT'     =>  'Remover botones de grupo en respuesta rápida',
    'ACP_DSR_CKE_QUICK_REMOVE_BUTTONS_DESC'     =>  'En el "Toolbar Configurator" las encontraras como el arreglo que sigue a config.removeButtons',
    'ACP_DSR_CKE_TOOLBAR_HINT_TEXT'	            =>	'Ayuda!',
    'ACP_DSR_CKE_TOOLBAR_HINT_DESC'	            =>	'Para configurar fácilmente esto puedes usar el <a href="https://ckeditor.com/latest/samples/toolbarconfigurator/index.html#basic" target="_blank">Toolbar Configurator desde aquí</a>',

    'ACP_DSR_CKE_EXTENSIONS'		            =>	'Ajustes de extensiones',
    'ACP_DSR_CKE_IMGUR_CLIENT_ID_TEXT'          =>  'Imgur Client ID',
    'ACP_DSR_CKE_IMGUR_CLIENT_ID_DESC'          =>  'Puedes integrar Imgur para que puedan subir imágenes desde el mismo editor.<br> <a href="https://imgur.com/register/api_anon" target="_blank">Para hacerlo configura tu cuenta aqui</a>',
    'ACP_DSR_CKE_CODE_SNIPPET_THEME_TEXT'       =>  'Theme usado en el code snippet',
    'ACP_DSR_CKE_CODE_SNIPPET_THEME_DESC'       =>  'Recuerda instalar antes <a href="https://github.com/s9e/phpbb-ext-highlighter" target="_blank">este code snippet</a>',
    'ACP_DSR_CKE_CODE_SNIPPET_LANGUAGES_TEXT'   =>  'Lenguajes del code snippet',
    'ACP_DSR_CKE_CODE_SNIPPET_LANGUAGES_DESC'   =>  'Puedes ver cómo se configura esto en la <a href="https://github.com/xchwarze/ckeditor-phpbb" target="_blank">documentación de la extensión</a>',
    'ACP_DSR_CKE_EXTENSIONS_HINT_TEXT'	        =>	'Ayuda!',
    'ACP_DSR_CKE_EXTENSIONS_HINT_DESC'	        =>	'Si usas BBcode custom debes:<br> 1. Colocar los iconos a usar en esta ruta: ./images/editor/custom/<br> 2. El formato que se usa en los nombres es: {bbcode}.png (16x16) {bbcode}.hidpi.png (32x32)',
]);
