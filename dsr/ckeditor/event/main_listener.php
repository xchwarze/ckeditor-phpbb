<?php

namespace dsr\ckeditor\event;

use phpbb\config\config;
use phpbb\config\db_text;
use phpbb\db\driver\driver_interface;
use phpbb\language\language;
use phpbb\template\template;
use phpbb\user;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event listener
 */
class main_listener implements EventSubscriberInterface
{
    /** @var template */
    protected $template;
    /** @var user */
    protected $user;
    /** @var config */
    protected $config;
    /** @var db_text */
    protected $config_text;
    /** @var driver_interface */
    protected $db;
    /** @var language */
    protected $language;
    protected $root_path;
    protected $ckeditor_path;

    public function __construct(
        driver_interface $db,
        template $template,
        config $config,
        db_text $config_text,
        user $user,
        language $language,
        $root_path
    ) {
        $this->template         = $template;
        $this->user             = $user;
        $this->config           = $config;
        $this->config_text      = $config_text;
        $this->db               = $db;
        $this->language         = $language;
        $this->root_path        = $root_path;
        $this->ckeditor_path    = realpath(__DIR__ . '/../styles/all/template/js/ckeditor');
    }

    static public function getSubscribedEvents()
    {
        return array(
            'core.display_custom_bbcodes'           => 'initialize_editor',
            'core.viewtopic_modify_page_title'      => 'initialize_quick_reply_editor',
            //'core.generate_smilies_after'         => 'initialize_editor',
            //'core.modify_posting_auth'            => 'initialize_editor',
        );
    }

    private function _get_config_text($key, $isJson)
    {
        $config = $this->config_text->get($key);
        if (!$isJson) {
            return $config;
        }

        // delete extra chars
        //$config = str_replace(["\r\n", "\n", "\r", " "], '', $config);

        // fix invalid json quotes
        $config = str_replace("'", '"', $config);

        return json_decode($config);
    }

    private function _get_lang()
    {
        $lang = substr($this->user->lang['USER_LANG'], 0, 2);

        return is_readable("{$this->ckeditor_path}/lang/{$lang}.js") ? $lang : false;
    }

    private function _fix_smileys()
    {
        // this is based on generate_smilies();
        //$root_path = (defined('PHPBB_USE_BOARD_URL_PATH') && PHPBB_USE_BOARD_URL_PATH) ? generate_board_url() . '/' : $phpbb_path_helper->get_web_root_path();

        $sql = 'SELECT *
                FROM ' . SMILIES_TABLE . '
                WHERE display_on_posting = 1
                ORDER BY smiley_order';
        $result = $this->db->sql_query($sql, $this->config['dsr_cke_cache_time']);
        while ($row = $this->db->sql_fetchrow($result))
        {
            $this->template->assign_block_vars('smiley', array(
                'SMILEY_CODE'   => $row['code'],
                'A_SMILEY_CODE' => $row['code'],
                'SMILEY_IMG'    => $this->root_path . $this->config['smilies_path'] . '/' . $row['smiley_url'],
                'SMILEY_WIDTH'  => $row['smiley_width'],
                'SMILEY_HEIGHT' => $row['smiley_height'],
                'SMILEY_DESC'   => $row['emotion'])
            );
        }

        $this->db->sql_freeresult($result);
    }

    private function _fix_languages()
    {
        // add missing texts
        $this->language->add_lang('posting');
    }

    private function _initialize_editor($is_viewtopic)
    {
        $toolbar_groups_config_name = $is_viewtopic ? 'dsr_cke_quick_editor_toolbar_groups' : 'dsr_cke_normal_editor_toolbar_groups';
        $remove_buttons_config_name = $is_viewtopic ? 'dsr_cke_quick_editor_remove_buttons' : 'dsr_cke_normal_editor_remove_buttons';
        $editor_height_config_name = $is_viewtopic ? 'dsr_cke_quick_editor_height' : 'dsr_cke_normal_editor_height';

        $editor_config = json_encode([
            'isQuickEditor'         => $is_viewtopic,
            'maxFontSize'           => $this->config['max_post_font_size'],
            'useAutoSave'           => (bool)$this->config['dsr_cke_use_auto_save'],
            'useEmojis'             => (bool)$this->config['dsr_cke_use_emojis'],
            'forcePasteAsText'      => (bool)$this->config['dsr_cke_force_paste_as_text'],
            'forceSourceOnMobile'   => (bool)$this->config['dsr_cke_force_source_on_mobile'],
            'toolbarGroups'         => $this->_get_config_text($toolbar_groups_config_name, true),
            'removeButtons'         => $this->_get_config_text($remove_buttons_config_name, false),
            'editorHeight'          => $this->config[$editor_height_config_name],
            'imgurClientId'         => $this->config['dsr_cke_imgur_client_id'],
            'codeSnippetTheme'      => $this->config['dsr_cke_code_snippet_theme'],
            'codeSnippetLanguages'  => $this->_get_config_text('dsr_cke_code_snippet_languages', true),
        ], JSON_HEX_QUOT | JSON_HEX_APOS);

        $this->template->assign_vars([
            'CKE_STATUS'            => (bool)$this->config['dsr_cke_status'],
            'CKE_LANG'              => $this->_get_lang(),
            'CKE_CONFIG'            => $editor_config,
        ]);
    }

    public function initialize_editor() {
        $this->_initialize_editor(false);
    }

    public function initialize_quick_reply_editor() {
        // check if user is login first!
        // TODO: Add guest postings support
        if ( empty($this->user->data['user_id']) || $this->user->data['user_id'] == ANONYMOUS ) {
            return;
        }

        if (!(bool)$this->config['dsr_cke_use_emojis']) {
            $this->_fix_smileys();
        }

        $this->_fix_languages();
        $this->_initialize_editor(true);
    }
}
