<?php

namespace dsr\ckeditor\migrations;

class update_module_150 extends \phpbb\db\migration\migration
{
    static public function depends_on()
    {
        return ['\dsr\ckeditor\migrations\update_module_133'];
    }

    public function update_data()
    {
        return [
            ['config.remove', ['dsr_cke_cache_time']],
            ['config.add', ['dsr_cke_normal_editor_height', '33em']],
            ['config.add', ['dsr_cke_quick_editor_height', '20em']],
        ];
    }

    //phpBB can undo all your changes you do in this particular migration automatically
    //public function revert_data()
}
