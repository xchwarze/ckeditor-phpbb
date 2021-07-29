<?php

namespace dsr\ckeditor\acp;

class acp_info
{
    public function module()
    {
        return array(
            'filename' => '\dsr\ckeditor\acp\acp_module',
            'title' => 'ACP_DSR_CKE_TITLE',
            'modes' => [
                'settings' => [
                    'title' => 'ACP_DSR_CKE_SETTING',
                    'auth' => 'ext_dsr/ckeditor && acl_a_group',
                    'cat' => ['ACP_DSR_CKE_TITLE'],
                ],
            ],
        );
    }
}
