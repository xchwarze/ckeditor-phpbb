<?php
/**
 * @author      DSR!
 * @since       23.03.20
 * @version     1.0.0
 */

namespace dsr\ckeditor\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event listener
 */
class main_listener implements EventSubscriberInterface
{
    /** @var \phpbb\template\template */
    private $template;
    /** @var \phpbb\user */
    private $user;
    /** @var \phpbb\config\config */
    private $config;
    /** @var \phpbb\db\driver\driver_interface */
    private $db;

    private $root_path;
    private $ckeditor_path;

    public function __construct(
        \phpbb\db\driver\driver_interface $db,
        \phpbb\template\template $template,
        \phpbb\config\config $config,
        \phpbb\user $user,
        $root_path
    ) {
        $this->template = $template;
        $this->user = $user;
        $this->config = $config;
        $this->db = $db;
        $this->root_path = $root_path;
        $this->ckeditor_path = realpath(__DIR__ . '/../styles/all/template/js/ckeditor');
    }

    static public function getSubscribedEvents()
    {
        return array(
            'core.display_custom_bbcodes'           => 'initialize_editor',
            'core.viewtopic_modify_page_title'      => 'initialize_quick_reply_editor',
            //'core.generate_smilies_after'         => 'initialize_editor',
            //'core.modify_posting_auth'            => 'initialize_editor',
            //'core.display_custom_bbcodes'         => 'initialize_editor',
        );
    }

    private function _get_lang()
    {
        $lang = substr($this->user->lang['USER_LANG'], 0, 2);

        // English is default and doesn't have to be loaded
        if ('en' === $lang)
        {
            return false;
        }

        return is_readable("{$this->ckeditor_path}/lang/{$lang}.js") ? $lang : false;
    }

    private function _fix_smileys()
    {
        // this is based on generate_smilies();
        //$root_path = (defined('PHPBB_USE_BOARD_URL_PATH') && PHPBB_USE_BOARD_URL_PATH) ? generate_board_url() . '/' : $phpbb_path_helper->get_web_root_path();
        $root_path = $this->root_path;

        $sql = 'SELECT *
                    FROM ' . SMILIES_TABLE . '
                    WHERE display_on_posting = 1
                    ORDER BY smiley_order';
        $result = $this->db->sql_query($sql, 3600);
        while ($row = $this->db->sql_fetchrow($result))
        {
            $this->template->assign_block_vars('smiley', array(
                'SMILEY_CODE'   => $row['code'],
                'A_SMILEY_CODE' => addslashes($row['code']),
                'SMILEY_IMG'    => $root_path . $this->config['smilies_path'] . '/' . $row['smiley_url'],
                'SMILEY_WIDTH'  => $row['smiley_width'],
                'SMILEY_HEIGHT' => $row['smiley_height'],
                'SMILEY_DESC'   => $row['emotion'])
            );
        }

        $this->db->sql_freeresult($result);
    }

    private function _initialize_editor($is_viewtopic)
    {
        $editor_normal_toolbar = [
            [ 'name' => 'basicstyles' ],
            [ 'name' => 'styles' ],
            [ 'name' => 'colors' ],
            [ 'name' => 'paragraph',   'groups' => [ 'align', 'list', 'indent', 'blocks', 'bidi' ] ],
            [ 'name' => 'editing',     'groups' => [ 'find', 'selection', 'spellchecker', 'cleanup', 'undo'  ] ],
            [ 'name' => 'forms' ],
            [ 'name' => 'links' ],
            [ 'name' => 'insert' ],
            [ 'name' => 'others',      'groups' => [ 'customBBcode' ] ],
            [ 'name' => 'document',    'groups' => [ 'tools', 'mode', 'document', 'doctools' ] ],
        ];
        $editor_quick_toolbar = [
            [ 'name' => 'basicstyles' ],
            [ 'name' => 'styles' ],
            [ 'name' => 'colors' ],
            [ 'name' => 'insert' ],
            [ 'name' => 'document',    'groups' => [ 'tools', 'mode', 'document', 'doctools' ] ],
        ];
        $remove_buttons_normal_toolbar = 'BGColor,Anchor,Font,Indent,Outdent';
        $remove_buttons_quick_toolbar  = 'BGColor,Anchor,Font,Indent,Outdent,Table,HorizontalRule';

        // code snippet
        // first install this!!!
        // https://github.com/s9e/phpbb-ext-highlighter
        $code_snippet_theme = 'monokai_sublime';
        $code_snippet_languages = [
            'arduino' => 'Arduino',
            'autoit' => 'Autoit',
            'bash' => 'Bash',
            'basic' => 'Basic',
            'cpp' => 'C/C++',
            'cs' => 'C#',
            'css' => 'CSS',
            'delphi' => 'Delphi',
            'diff' => 'Diff',
            'dockerfile' => 'Dockerfile',
            'dos' => 'DOS',
            'go' => 'Go',
            'http' => 'Http',
            'ini' => 'INI',
            'java' => 'Java',
            'javascript' => 'Javascript',
            'json' => 'JSON',
            'less' => 'Less',
            'lua' => 'Lua',
            'makefile' => 'Makefile',
            'markdown' => 'Markdown',
            'nginx' => 'Nginx',
            'php' => 'PHP',
            'powershell' => 'Powershell',
            'python' => 'Python',
            'ruby' => 'Ruby',
            'rust' => 'Rust',
            'scss' => 'Scss',
            'shell' => 'Shell',
            'sql' => 'SQL',
            'typescript' => 'Typescript',
            'vbnet' => 'VB .NET',
            'vbscript' => 'VB Script',
            'xml' => 'Xml',
            'yaml' => 'Yaml',
        ];

        $this->template->assign_vars(array(
            'CKE_STATUS' => true,
            'CKE_CONFIG' => json_encode([
                'lang' => $this->_get_lang(),
                'maxFontSize' => $this->config['max_post_font_size'],
                'toolbarGroups' => $is_viewtopic ? $editor_quick_toolbar : $editor_normal_toolbar,
                'removeButtons' => $is_viewtopic ? $remove_buttons_quick_toolbar : $remove_buttons_normal_toolbar,
                'codeSnippetTheme' => $code_snippet_theme,
                'codeSnippetLanguages' => $code_snippet_languages,
            ]),
        ));
    }

    public function initialize_editor() {
        $this->_initialize_editor(false);
    }

    public function initialize_quick_reply_editor() {
        //$is_viewtopic = $this->user->page['page_name'] === 'viewtopic.php';
        $this->_fix_smileys();
        $this->_initialize_editor(true);
    }
}
