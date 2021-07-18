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
    'ACP_DSR_CKE_TITLE'			=>	'CKEditor 4',
    'ACP_DSR_CKE_SETTING'		=>	'Settings',
]);
