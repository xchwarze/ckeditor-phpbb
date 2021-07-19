<?php

namespace dsr\ckeditor\acp;

class acp_module
{
    public $u_action;
    public $tpl_name;
    public $page_title;

    public function main($id, $mode)
    {
        global $phpbb_root_path, $phpEx, $phpbb_container;

        /** @var \phpbb\config\config */
        $config = $phpbb_container->get('config');

        /** @var \phpbb\config\config */
        $config_text = $phpbb_container->get('config_text');

        /** @var \phpbb\user */
        $user = $phpbb_container->get('user');

        /** @var \phpbb\template\template */
        $template = $phpbb_container->get('template');

        /** @var \phpbb\request\request */
        $request = $phpbb_container->get('request');

        /** @var \phpbb\language\language */
        $language = $phpbb_container->get('language');

        $user->add_lang('acp/common');
        $user->add_lang_ext('dsr/ckeditor', 'info_acp_ckeditor');
        $this->tpl_name = 'acp_ckeditor';
        $this->page_title = 'ACP_DSR_CKE_TITLE';
        add_form_key('acp_ckeditor');

        // tendria que haber una forma mas moderna de hacer la validacion del formulario
        if (!function_exists('validate_data'))
        {
            include("{$phpbb_root_path}includes/functions_user.{$phpEx}");
        }

        $validation_error = false;
        if ($request->is_set_post('submit'))
        {
            if (!check_form_key('acp_ckeditor'))
            {
                trigger_error('FORM_INVALID');
            }

            $submit_data = [
                'dsr_cke_status'			            => $request->variable('dsr_cke_status', true),
                'dsr_cke_cache_time'		            => $request->variable('dsr_cke_cache_time', 0),
                'dsr_cke_normal_editor_toolbar_groups'  => $request->variable('dsr_cke_normal_editor_toolbar_groups', ''),
                'dsr_cke_normal_editor_remove_buttons'  => $request->variable('dsr_cke_normal_editor_remove_buttons', ''),
                'dsr_cke_quick_editor_toolbar_groups'   => $request->variable('dsr_cke_quick_editor_toolbar_groups', ''),
                'dsr_cke_quick_editor_remove_buttons'   => $request->variable('dsr_cke_quick_editor_remove_buttons', ''),
                'dsr_cke_imgur_client_id'               => $request->variable('dsr_cke_imgur_client_id', ''),
                'dsr_cke_code_snippet_theme'            => $request->variable('dsr_cke_code_snippet_theme', ''),
                'dsr_cke_code_snippet_languages'        => $request->variable('dsr_cke_code_snippet_languages', ''),
            ];

            $validation_checks = [
                'dsr_cke_cache_time'                    => ['num', true, 0, 86400],
                'dsr_cke_normal_editor_toolbar_groups'  => ['string', true, 0, 5000],
                'dsr_cke_normal_editor_remove_buttons'  => ['string', true, 0, 1000],
                'dsr_cke_quick_editor_toolbar_groups'   => ['string', true, 0, 5000],
                'dsr_cke_quick_editor_remove_buttons'   => ['string', true, 0, 1000],
                'dsr_cke_imgur_client_id'               => ['string', true, 0, 255],
                'dsr_cke_code_snippet_theme'            => ['string', true, 0, 255],
            ];

            $main_validation_error = validate_data($submit_data, $validation_checks);
            $custom_validation_error = $this->validate_js_json($submit_data);
            $validation_error = array_merge($main_validation_error, $custom_validation_error);

            if (!count($validation_error)) {
                $config->set('dsr_cke_status', $submit_data['dsr_cke_status']);
                $config->set('dsr_cke_cache_time', $submit_data['dsr_cke_cache_time']);
                $config_text->set('dsr_cke_normal_editor_toolbar_groups', $submit_data['dsr_cke_normal_editor_toolbar_groups']);
                $config_text->set('dsr_cke_normal_editor_remove_buttons', $submit_data['dsr_cke_normal_editor_remove_buttons']);
                $config_text->set('dsr_cke_quick_editor_toolbar_groups', $submit_data['dsr_cke_quick_editor_toolbar_groups']);
                $config_text->set('dsr_cke_quick_editor_remove_buttons', $submit_data['dsr_cke_quick_editor_remove_buttons']);
                $config->set('dsr_cke_imgur_client_id', $submit_data['dsr_cke_imgur_client_id']);
                $config->set('dsr_cke_code_snippet_theme', $submit_data['dsr_cke_code_snippet_theme']);
                $config_text->set('dsr_cke_code_snippet_languages', $submit_data['dsr_cke_code_snippet_languages']);

                trigger_error($user->lang['ACP_DSR_CKE_SAVED'] . adm_back_link($this->u_action));
            }
        }

        $template->assign_vars([
            'DSR_CKE_STATUS'                        => $config['dsr_cke_status'],
            'DSR_CKE_CACHE_TIME'                    => $config['dsr_cke_cache_time'],
            'DSR_CKE_NORMAL_EDITOR_TOOLBAR_GROUPS'  => $config_text->get('dsr_cke_normal_editor_toolbar_groups'),
            'DSR_CKE_NORMAL_EDITOR_REMOVE_BUTTONS'  => $config_text->get('dsr_cke_normal_editor_remove_buttons'),
            'DSR_CKE_QUICK_EDITOR_TOOLBAR_GROUPS'   => $config_text->get('dsr_cke_quick_editor_toolbar_groups'),
            'DSR_CKE_QUICK_EDITOR_REMOVE_BUTTONS'   => $config_text->get('dsr_cke_quick_editor_remove_buttons'),
            'DSR_CKE_IMGUR_CLIENT_ID'               => $config['dsr_cke_imgur_client_id'],
            'DSR_CKE_CODE_SNIPPET_THEME'            => $config['dsr_cke_code_snippet_theme'],
            'DSR_CKE_CODE_SNIPPET_LANGUAGES'        => $config_text->get('dsr_cke_code_snippet_languages'),
            'S_ERROR'                               => (bool)$validation_error,
            'ERROR_MSG'	                            => $validation_error ? implode('<br />', array_map(array($language, 'lang'), $validation_error)) : '',
            'U_ACTION'                              => $this->u_action,
        ]);
    }

    private function validate_js_json($submit_data)
    {
        // json values
        $validate_json = [
            'dsr_cke_normal_editor_toolbar_groups',
            'dsr_cke_quick_editor_toolbar_groups',
            'dsr_cke_code_snippet_languages',
        ];

        $error = [];
        foreach ($validate_json as $validate_json_item_name) {
            if (empty($submit_data[ $validate_json_item_name ])) {
                continue;
            }

            // fix invalid json quotes
            $data = str_replace("'", '"', $submit_data[ $validate_json_item_name ]);

            // check invalid value
            if (empty(json_decode($data))) {
                $error[] = 'ACP_DSR_CKE_JSON_ERROR';
            }
        }

        return $error;
    }
}
