<?php

// config
$fix_lang_name = [
	'cs'        => 'C#',
	'cpp'       => 'C/C++',
	'vbscript'  => 'VB Script',
	'vbnet'     => 'VB .NET',
	'sql'       => 'SQL',
	'css'       => 'CSS',
	'json'      => 'JSON',
	'html'      => 'HTML',
	'asm'       => 'ASM',
	'ini'       => 'INI',
	'php'       => 'PHP',
	'dos'       => 'DOS',
];



// helpers
function fix_lang_name($lang)
{
	global $fix_lang_name;

	$name = $lang;
	if (isset($fix_lang_name[ $lang ])) {
		$name = $fix_lang_name[ $lang ];
	}

	return ucfirst($name);
}

function filter_callback($value)
{
	return (int)$value === 1;
}



// se fini
$content = file_get_contents('config.json');
$decode  = json_decode($content, true);
$decode  = array_filter($decode, 'filter_callback');
ksort($decode);

echo "code_snippet_languages = [\n";
foreach ($decode as $key => $value) {
	$clean_key = substr($key, 0, -3);
	$name = fix_lang_name($clean_key);
	echo "  { 'label': '{$name}', 'lang': '{$clean_key}' },\n";
}
echo "];\n\n";


/*
And if I only could
I'd make a deal with God
And I'd get him to swap our places
We're running up that road
We're running up that hill
We're running up that building
Say if I only could

https://www.youtube.com/watch?v=EL2Z-p1pdPM
*/