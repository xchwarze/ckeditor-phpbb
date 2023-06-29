<?php

if (!defined('IN_PHPBB')) {
    exit;
}

if (empty($lang) || !is_array($lang)) {
    $lang = [];
}

$lang = array_merge($lang, [
    // GENERIC
    'ACP_DSR_CKE_TITLE' => 'CKEditor 4',
    'ACP_DSR_CKE_SETTING' => 'Settings',
    'ACP_DSR_CKE_EXPLAIN' => 'Improve the text editors used by the user by the powerful CKEditor',
    'ACP_DSR_CKE_SAVED' => 'Changes Saved.',
    'ACP_DSR_CKE_JSON_ERROR' => 'The arrangement entered is invalid.',
    'ACP_DSR_CKE_CONFIG_SAVED' => '<strong>CKEditor 4 settings changed.</strong>',

    // ACP TEXTS
    'ACP_DSR_CKE_STATUS' => 'General settings',
    'ACP_DSR_CKE_STATUS_TEXT' => 'Enable',
    'ACP_DSR_CKE_USE_AUTO_SAVE_TEXT' => 'Use autosave',
    'ACP_DSR_CKE_USE_AUTO_SAVE_DESC' => 'Activate the autosave feature',
    'ACP_DSR_CKE_USE_EMOJIS_TEXT' => 'Use emojis',
    'ACP_DSR_CKE_USE_EMOJIS_DESC' => 'The use of smileys is replaced by emojis',
    'ACP_DSR_CKE_FORCE_SOURCE_ON_MOBILE_TEXT' => 'Use editor in source mode on mobile',
    'ACP_DSR_CKE_FORCE_SOURCE_ON_MOBILE_DESC' => 'Selecting this will load with the source view selected',
    'ACP_DSR_CKE_FORCE_PASTE_AS_TEXT_TEXT' => 'Automatically remove styles',
    'ACP_DSR_CKE_FORCE_PASTE_AS_TEXT_DESC' => 'If your users tend to copy and paste a lot in their posts this is the best solution to style problems',
    'ACP_DSR_CKE_PASTE_AS_TEXT_TYPE_0' => 'Preserves content formatting',
    'ACP_DSR_CKE_PASTE_AS_TEXT_TYPE_1' => 'Pastes all content as plain text',
    'ACP_DSR_CKE_PASTE_AS_TEXT_TYPE_ALLOW_WORD' => 'Only preserve content from Word',

    'ACP_DSR_CKE_TOOLBAR' => 'Toolbar settings',
    'ACP_DSR_CKE_NORMAL_TOOLBAR_GROUPS_TEXT' => 'Toolbar groups',
    'ACP_DSR_CKE_NORMAL_TOOLBAR_GROUPS_DESC' => 'In this way CKEditor calls the set of tools shown in the editor',
    'ACP_DSR_CKE_QUICK_TOOLBAR_GROUPS_TEXT' => 'Toolbar groups in Quick Reply',
    'ACP_DSR_CKE_QUICK_TOOLBAR_GROUPS_DESC' => 'In the "Toolbar Configurator" you will find them as the arrangement that follows config.toolbarGroups',
    'ACP_DSR_CKE_NORMAL_REMOVE_BUTTONS_TEXT' => 'Remove buttons',
    'ACP_DSR_CKE_NORMAL_REMOVE_BUTTONS_DESC' => 'This way you can exclude tools that are used in a group',
    'ACP_DSR_CKE_QUICK_REMOVE_BUTTONS_TEXT' => 'Remove buttons in Quick Reply',
    'ACP_DSR_CKE_QUICK_REMOVE_BUTTONS_DESC' => 'In the "Toolbar Configurator" you will find them as the fix that follows config.removeButtons',
    'ACP_DSR_CKE_NORMAL_EDITOR_HEIGHT_TEXT' => 'Editor Height',
    'ACP_DSR_CKE_NORMAL_EDITOR_HEIGHT_DESC' => 'Height supports all units used in css for this',
    'ACP_DSR_CKE_QUICK_EDITOR_HEIGHT_TEXT' => 'Height of the editor in quick response',
    'ACP_DSR_CKE_QUICK_EDITOR_HEIGHT_DESC' => 'Height supports all units used in css for this',
    'ACP_DSR_CKE_TOOLBAR_HINT_TEXT' => 'Help!',
    'ACP_DSR_CKE_TOOLBAR_HINT_DESC' => 'To easily configure this you can use the <a href="https://ckeditor.com/latest/samples/toolbarconfigurator/index.html#basic" target="_blank">Toolbar Configurator from here</a> ',

    'ACP_DSR_CKE_EXTENSIONS' => 'Extension settings',
    'ACP_DSR_CKE_IMGUR_CLIENT_ID_TEXT' => 'Imgur Client ID',
    'ACP_DSR_CKE_IMGUR_CLIENT_ID_DESC' => 'You can integrate Imgur so that they can upload images from the editor.<br> <a href="https://imgur.com/register/api_anon" target="_blank">To do that set up your account here</a>',
    'ACP_DSR_CKE_CODE_SNIPPET_THEME_TEXT' => 'Theme used in the code snippet',
    'ACP_DSR_CKE_CODE_SNIPPET_THEME_DESC' => 'Remember to install before <a href="https://github.com/s9e/phpbb-ext-highlighter" target="_blank"> this code snippet </a>',
    'ACP_DSR_CKE_CODE_SNIPPET_LANGUAGES_TEXT' => 'Snippet code languages',
    'ACP_DSR_CKE_CODE_SNIPPET_LANGUAGES_DESC' => 'You can see how this is configured in the <a href="https://github.com/xchwarze/ckeditor-phpbb" target="_blank"> extension documentation </a>',
    'ACP_DSR_CKE_EXTENSIONS_HINT_TEXT' => 'Help!',
    'ACP_DSR_CKE_EXTENSIONS_HINT_DESC' => 'If you use custom BBcode you must: <br> 1. Place the icons to use in this path: ./images/editor/custom/ <br> 2. The format used in the names is: {bbcode}.png (16x16) {bbcode}.hidpi.png (32x32) ',
]);
