<?php

namespace dsr\ckeditor\migrations;

class update_module_133 extends \phpbb\db\migration\migration
{
    static public function depends_on()
    {
        return ['\dsr\ckeditor\migrations\add_module'];
    }

    public function update_data()
    {
        return [
            ['config.add', ['dsr_cke_use_emojis', 0]],
            ['config.add', ['dsr_cke_force_paste_as_text', 0]],
            ['config.add', ['dsr_cke_force_source_on_mobile', 0]],
        ];
    }
}
