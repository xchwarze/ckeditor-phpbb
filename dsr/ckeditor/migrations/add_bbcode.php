<?php

namespace dsr\ckeditor\migrations;

class add_bbcode extends \phpbb\db\migration\migration
{
    static public function depends_on()
    {
        return ['\phpbb\db\migration\data\v320\v320'];
    }

    public function update_data()
    {
        return [
            ['custom', [[$this, 'addbbcode']]],
        ];
    }

    private function bbcode_exists($bbcode_tag)
    {
        $sql = 'SELECT bbcode_id FROM ' . $this->table_prefix . 'bbcodes WHERE bbcode_tag = \'' . $this->db->sql_escape($bbcode_tag) . '\'';
        $result = $this->db->sql_query($sql);
        $exists = $this->db->sql_fetchfield('bbcode_id');
        $this->db->sql_freeresult($result);

        return $exists;
    }

    public function addbbcode()
    {
        $sql = 'SELECT MAX(bbcode_id) AS max_id FROM ' . $this->table_prefix . 'bbcodes';
        $result = $this->db->sql_query($sql);

        $bbcode_ids = 0;
        if ($styles_row = $this->db->sql_fetchrow())
        {
            $bbcode_ids = $styles_row['max_id'];
        }
        $this->db->sql_freeresult($result);

        // Make sure we don't start too low
        if ($bbcode_ids <= NUM_CORE_BBCODES)
        {
            $bbcode_ids = NUM_CORE_BBCODES;
        }

        $custom_bbcodes = [
            [
                'bbcode_tag' => 'li',
                'bbcode_helpline' => '',
                'display_on_posting' => 0,
                'bbcode_match' => '[li]{TEXT}[/li]',
                'bbcode_tpl' => '<li>{TEXT}</li>',
                'first_pass_match' => '!\\[li\\](.*?]\\[/li\\]!ies',
                'first_pass_replace' => '\'[li:$uid]\'.str_replace(["\\r\\n", \'\\"\', \'\\\'\', \'(\', \']\'], ["\\n", \'"\', \'&#39;\', \'&#40;\', \'&#41;\'], trim(\'${1}\']].\'[/li:$uid]\'',
                'second_pass_match' => '!\\[li:$uid\\](.*?]\\[/li:$uid\\]!s',
                'second_pass_replace' => '<li>${1}</li>'
            ],
            [
                'bbcode_tag' => 'ul',
                'bbcode_helpline' => '',
                'display_on_posting' => 0,
                'bbcode_match' => '[ul]{TEXT}[/ul]',
                'bbcode_tpl' => '<ul>{TEXT}</ul>',
                'first_pass_match' => '!\\[ul\\](.*?]\\[/ul\\]!ies',
                'first_pass_replace' => '\'[ul:$uid]\'.str_replace(["\\r\\n", \'\\"\', \'\\\'\', \'(\', \']\'], ["\\n", \'"\', \'&#39;\', \'&#40;\', \'&#41;\'], trim(\'${1}\']].\'[/ul:$uid]\'',
                'second_pass_match' => '!\\[ul:$uid\\](.*?]\\[/ul:$uid\\]!s',
                'second_pass_replace' => '<ul>${1}</ul>'
            ],
            [
                'bbcode_tag' => 's',
                'bbcode_helpline' => '',
                'display_on_posting' => 0,
                'bbcode_match' => '[s]{TEXT}[/s]',
                'bbcode_tpl' => '<span style="text-decoration: line-through;">{TEXT}</span>',
                'first_pass_match' => '!\\[s\\](.*?]\\[/s\\]!ies',
                'first_pass_replace' => '\'[s:$uid]\'.str_replace(["\\r\\n", \'\\"\', \'\\\'\', \'(\', \']\'], ["\\n", \'"\', \'&#39;\', \'&#40;\', \'&#41;\'], trim(\'${1}\']].\'[/s:$uid]\'',
                'second_pass_match' => '!\\[s:$uid\\](.*?]\\[/s:$uid\\]!s',
                'second_pass_replace' => '<span style="text-decoration: line-through;">${1}</span>'
            ],
            [
                'bbcode_tag' => 'sub',
                'bbcode_helpline' => '',
                'display_on_posting' => 0,
                'bbcode_match' => '[sub]{TEXT}[/sub]',
                'bbcode_tpl' => '<style>sub { vertical-align: sub;  font-size: smaller;}</style><sub>{TEXT}</sub>',
                'first_pass_match' => '!\\[sub\\](.*?]\\[/sub\\]!ies',
                'first_pass_replace' => '\'[sub:$uid]\'.str_replace(["\\r\\n", \'\\"\', \'\\\'\', \'(\', \']\'], ["\\n", \'"\', \'&#39;\', \'&#40;\', \'&#41;\'], trim(\'${1}\']].\'[/sub:$uid]\'',
                'second_pass_match' => '!\\[sub:$uid\\](.*?]\\[/sub:$uid\\]!s',
                'second_pass_replace' => '<style>sub {vertical-align: sub; font-size: smaller;}</style><sub>${1}</sub>'
            ],
            [
                'bbcode_tag' => 'sup',
                'bbcode_helpline' => '',
                'display_on_posting' => 0,
                'bbcode_match' => '[sup]{TEXT}[/sup]',
                'bbcode_tpl' => '<style>sup { vertical-align: super;  font-size: smaller;}</style><sup>{TEXT}</sup>',
                'first_pass_match' => '!\\[sup\\](.*?]\\[/sup\\]!ies',
                'first_pass_replace' => '\'[sup:$uid]\'.str_replace(["\\r\\n", \'\\"\', \'\\\'\', \'(\', \']\'], ["\\n", \'"\', \'&#39;\', \'&#40;\', \'&#41;\'], trim(\'${1}\']].\'[/sup:$uid]\'',
                'second_pass_match' => '!\\[sup:$uid\\](.*?]\\[/sup:$uid\\]!s',
                'second_pass_replace' => '<style>sup {vertical-align: super; font-size: smaller;}</style><sup>${1}</sup>'
            ],
            [
                'bbcode_tag' => 'left',
                'bbcode_helpline' => '',
                'display_on_posting' => 0,
                'bbcode_match' => '[left]{TEXT}[/left]',
                'bbcode_tpl' => '<div align="left">{TEXT}</div>',
                'first_pass_match' => '!\\[left\\](.*?]\\[/left\\]!ies',
                'first_pass_replace' => '\'[left:$uid]\'.str_replace(["\\r\\n", \'\\"\', \'\\\'\', \'(\', \']\'], ["\\n", \'"\', \'&#39;\', \'&#40;\', \'&#41;\'], trim(\'${1}\']].\'[/left:$uid]\'',
                'second_pass_match' => '!\\[left:$uid\\](.*?]\\[/left:$uid\\]!s',
                'second_pass_replace' => '<div align="left">${1}</div>'
            ],
            [
                'bbcode_tag' => 'right',
                'bbcode_helpline' => '',
                'display_on_posting' => 0,
                'bbcode_match' => '[right]{TEXT}[/right]',
                'bbcode_tpl' => '<div align="right">{TEXT}</div>',
                'first_pass_match' => '!\\[right\\](.*?]\\[/right\\]!ies',
                'first_pass_replace' => '\'[right:$uid]\'.str_replace(["\\r\\n", \'\\"\', \'\\\'\', \'(\', \']\'], ["\\n", \'"\', \'&#39;\', \'&#40;\', \'&#41;\'], trim(\'${1}\']].\'[/right:$uid]\'',
                'second_pass_match' => '!\\[right:$uid\\](.*?]\\[/right:$uid\\]!s',
                'second_pass_replace' => '<div align="right">${1}</div>'
            ],
            [
                'bbcode_tag' => 'center',
                'bbcode_helpline' => '',
                'display_on_posting' => 0,
                'bbcode_match' => '[center]{TEXT}[/center]',
                'bbcode_tpl' => '<div align="center">{TEXT}</div>',
                'first_pass_match' => '!\\[center\\](.*?]\\[/center\\]!ies',
                'first_pass_replace' => '\'[center:$uid]\'.str_replace(["\\r\\n", \'\\"\', \'\\\'\', \'(\', \']\'], ["\\n", \'"\', \'&#39;\', \'&#40;\', \'&#41;\'], trim(\'${1}\']].\'[/center:$uid]\'',
                'second_pass_match' => '!\\[center:$uid\\](.*?]\\[/center:$uid\\]!s',
                'second_pass_replace' => '<div align="center">${1}</div>'
            ],
            [
                'bbcode_tag' => 'justify',
                'bbcode_helpline' => '',
                'display_on_posting' => 0,
                'bbcode_match' => '[justify]{TEXT}[/justify]',
                'bbcode_tpl' => '<div align="justify">{TEXT}</div>',
                'first_pass_match' => '!\\[justify\\](.*?]\\[/justify\\]!ies',
                'first_pass_replace' => '\'[justify:$uid]\'.str_replace(["\\r\\n", \'\\"\', \'\\\'\', \'(\', \']\'], ["\\n", \'"\', \'&#39;\', \'&#40;\', \'&#41;\'], trim(\'${1}\']].\'[/justify:$uid]\'',
                'second_pass_match' => '!\\[justify:$uid\\](.*?]\\[/justify:$uid\\]!s',
                'second_pass_replace' => '<div align="justify">${1}</div>'
            ],
            [
                'bbcode_tag' => 'font=',
                'bbcode_helpline' => '',
                'display_on_posting' => 0,
                'bbcode_match' => '[font={SIMPLETEXT}]{TEXT}[/font]',
                'bbcode_tpl' => '<span style="font-family: {SIMPLETEXT};">{TEXT}</span>',
                'first_pass_match' => '!\\[font\\=([a-zA-Z0-9-+.,_ ]+]\\](.*?]\\[/font\\]!ies',
                'first_pass_replace' => '\'[font=${1}:$uid]\'.str_replace(["\\r\\n", \'\\"\', \'\\\'\', \'(\', \']\'], ["\\n", \'"\', \'&#39;\', \'&#40;\', \'&#41;\'], trim(\'${2}\']].\'[/font:$uid]\'',
                'second_pass_match' => '!\\[font\\=([a-zA-Z0-9-+.,_ ]+]:$uid\\](.*?]\\[/font:$uid\\]!s',
                'second_pass_replace' => '<span style="font-family: ${1};">${2}</span>'
            ],
            [
                'bbcode_tag' => 'ol',
                'bbcode_helpline' => '',
                'display_on_posting' => 0,
                'bbcode_match' => '[ol]{TEXT}[/ol]',
                'bbcode_tpl' => '<ol>{TEXT}</ol>',
                'first_pass_match' => '!\\[ol\\](.*?]\\[/ol\\]!ies',
                'first_pass_replace' => '\'[ol:$uid]\'.str_replace(["\\r\\n", \'\\"\', \'\\\'\', \'(\', \']\'], ["\\n", \'"\', \'&#39;\', \'&#40;\', \'&#41;\'], trim(\'${1}\']].\'[/ol:$uid]\'',
                'second_pass_match' => '!\\[ol:$uid\\](.*?]\\[/ol:$uid\\]!s',
                'second_pass_replace' => '<ol>${1}</ol>'
            ],
            [
                'bbcode_tag' => 'table',
                'bbcode_helpline' => '',
                'display_on_posting' => 0,
                'bbcode_match' => '[table]{TEXT}[/table]',
                'bbcode_tpl' => '<table>{TEXT}</table>',
                'first_pass_match' => '!\\[table\\](.*?]\\[/table\\]!ies',
                'first_pass_replace' => '\'[table:$uid]\'.str_replace(["\\r\\n", \'\\"\', \'\\\'\', \'(\', \']\'], ["\\n", \'"\', \'&#39;\', \'&#40;\', \'&#41;\'], trim(\'${1}\']].\'[/table:$uid]\'',
                'second_pass_match' => '!\\[table:$uid\\](.*?]\\[/table:$uid\\]!s',
                'second_pass_replace' => '<table>${1}</table>'
            ],
            [
                'bbcode_tag' => 'td',
                'bbcode_helpline' => '',
                'display_on_posting' => 0,
                'bbcode_match' => '[td]{TEXT}[/td]',
                'bbcode_tpl' => '<td>{TEXT}</td>',
                'first_pass_match' => '!\\[td\\](.*?]\\[/td\\]!ies',
                'first_pass_replace' => '\'[td:$uid]\'.str_replace(["\\r\\n", \'\\"\', \'\\\'\', \'(\', \']\'], ["\\n", \'"\', \'&#39;\', \'&#40;\', \'&#41;\'], trim(\'${1}\']].\'[/td:$uid]\'',
                'second_pass_match' => '!\\[td:$uid\\](.*?]\\[/td:$uid\\]!s',
                'second_pass_replace' => '<td>${1}</td>'
            ],
            [
                'bbcode_tag' => 'tr',
                'bbcode_helpline' => '',
                'display_on_posting' => 0,
                'bbcode_match' => '[tr]{TEXT}[/tr]',
                'bbcode_tpl' => '<tr>{TEXT}</tr>',
                'first_pass_match' => '!\\[tr\\](.*?]\\[/tr\\]!ies',
                'first_pass_replace' => '\'[tr:$uid]\'.str_replace(["\\r\\n", \'\\"\', \'\\\'\', \'(\', \']\'], ["\\n", \'"\', \'&#39;\', \'&#40;\', \'&#41;\'], trim(\'${1}\']].\'[/tr:$uid]\'',
                'second_pass_match' => '!\\[tr:$uid\\](.*?]\\[/tr:$uid\\]!s',
                'second_pass_replace' => '<tr>${1}</tr>'
            ],
            [
                'bbcode_tag' => 'hr',
                'bbcode_helpline' => '',
                'display_on_posting' => 0,
                'bbcode_match' => '[hr]',
                'bbcode_tpl' => '<hr />',
                'first_pass_match' => '!\\[hr\\]!i',
                'first_pass_replace' => '[hr:$uid]',
                'second_pass_match' => '!\\[hr:$uid\\]!s',
                'second_pass_replace' => '<hr />'
            ],
            [
                'bbcode_tag' => 'youtube',
                'bbcode_helpline' => '',
                'display_on_posting' => 0,
                'bbcode_match' => '[youtube]{SIMPLETEXT}[/youtube]',
                'bbcode_tpl' => '<iframe width="560" height="315" src="https://www.youtube.com/embed/{SIMPLETEXT}?wmode=opaque" data-youtube-id="{SIMPLETEXT}" frameborder="0" allowfullscreen></iframe>',
                'first_pass_match' => '!\\[youtube\\]([a-zA-Z0-9-+.,_ ]+]\\[/youtube\\]!i',
                'first_pass_replace' => '[youtube:$uid]${1}[/youtube:$uid]',
                'second_pass_match' => '!\\[youtube:$uid\\]([a-zA-Z0-9-+.,_ ]+]\\[/youtube:$uid\\]!s',
                'second_pass_replace' => '<iframe width="560" height="315" src="https://www.youtube.com/embed/${1}?wmode=opaque" data-youtube-id="${1}" frameborder="0" allowfullscreen></iframe>'
            ],
            [
                'bbcode_tag' => 'rtl',
                'bbcode_helpline' => '',
                'display_on_posting' => 0,
                'bbcode_match' => '[rtl]{TEXT}[/rtl]',
                'bbcode_tpl' => '<div style="direction: rtl;">{TEXT}</div>',
                'first_pass_match' => '!\\[rtl\\](.*?]\\[/rtl\\]!ies',
                'first_pass_replace' => '\'[rtl:$uid]\'.str_replace(["\\r\\n", \'\\"\', \'\\\'\', \'(\', \']\'], ["\\n", \'"\', \'&#39;\', \'&#40;\', \'&#41;\'], trim(\'${1}\']].\'[/rtl:$uid]\'',
                'second_pass_match' => '!\\[rtl:$uid\\](.*?]\\[/rtl:$uid\\]!s',
                'second_pass_replace' => '<div style="direction: rtl;">${1}</div>'
            ],
            [
                'bbcode_tag' => 'ltr',
                'bbcode_helpline' => '',
                'display_on_posting' => 0,
                'bbcode_match' => '[ltr]{TEXT}[/ltr]',
                'bbcode_tpl' => '<div style="direction: ltr;">{TEXT}</div>',
                'first_pass_match' => '!\\[ltr\\](.*?]\\[/ltr\\]!ies',
                'first_pass_replace' => '\'[ltr:$uid]\'.str_replace(["\\r\\n", \'\\"\', \'\\\'\', \'(\', \']\'], ["\\n", \'"\', \'&#39;\', \'&#40;\', \'&#41;\'], trim(\'${1}\']].\'[/ltr:$uid]\'',
                'second_pass_match' => '!\\[ltr:$uid\\](.*?]\\[/ltr:$uid\\]!s',
                'second_pass_replace' => '<div style="direction: ltr;">${1}</div>'
            ]
        ];

        foreach ($custom_bbcodes as $bbcode)
        {
            if ($this->bbcode_exists($bbcode['bbcode_tag']))
            {
                continue;
            }

            $bbcode['bbcode_id'] = ++$bbcode_ids;
            $sql = 'INSERT INTO ' . $this->table_prefix . 'bbcodes' . $this->db->sql_build_array('INSERT', $bbcode);
            $this->db->sql_query($sql);
        }
    }
}
