<?php
/** 
*
* @package phpBB2
* @version $Id: index_statistics.php,v 4.2.8 2003/03/16 19:38:28 acydburn Exp $
* @copyright (c) 2003 Meik Sievertsen
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'mods/stats/includes/constants.'.$phpEx);
include($phpbb_root_path . 'mods/stats/includes/lang_functions.'.$phpEx);
include($phpbb_root_path . 'mods/stats/includes/stat_functions.'.$phpEx);
include($phpbb_root_path . 'mods/stats/includes/template.'.$phpEx);
include($phpbb_root_path . 'mods/stats/core.'.$phpEx);

init_core();

$preview_module = intval($board_config['stat_index']);

// Get all module informations about given module_id (activated or not)
$modules = get_modules(false, $preview_module);
$core->do_not_use_cache = TRUE;

$sql = "SELECT config_value 
	FROM " . CONFIG_TABLE . " 
	WHERE config_name = 'default_lang'";
if (!($result = $db->sql_query($sql)))
{
	message_die(GENERAL_ERROR, 'Unable to query config table', '', __LINE__, __FILE__, $sql);
}

$row = $db->sql_fetchrow($result);
$default_board_lang = trim($row['config_value']);

//
// Include Language
//
$lang_failover = array($board_config['default_lang'], $default_board_lang, 'english');
$languages_to_include = array(
	'language/lang_xxx/lang_admin.' . $phpEx,
	'language/lang_xxx/lang_statistics.' . $phpEx,
	'modules/language/lang_xxx/lang_modules.' . $phpEx
);

for ($i = 0; $i < sizeof($languages_to_include); $i++)
{
	$found = FALSE;

	for ($j = 0; $j < sizeof($lang_failover) && !$found; $j++)
	{
		$language_file = str_replace('xxx', $lang_failover[$j], $languages_to_include[$i]);
	
		if ( @file_exists(@realpath($phpbb_root_path . $language_file)) )
		{
			@include_once($language_file);
			$found = TRUE;
			if (strstr($languages_to_include[$i], 'lang_modules'))
			{
				$core->used_language = $lang_failover[$j];
			}
		}
	}
}

if (trim($core->used_language) == '')
{
	$core->used_language = 'english';
}

$development = FALSE;

$iterate_index = 0;
$iterate_end = sizeof($modules);

while ($iterate_index < $iterate_end)
{
	$first_iterate = (($iterate_index == 0) && (!$development)) ? TRUE : FALSE;
	$last_iterate = ($iterate_index == $iterate_end-1) ? TRUE : FALSE;

	$core->current_module_path = 'modules/' . trim($modules[$iterate_index]['short_name']) . '/';
	$core->current_module_name = trim($modules[$iterate_index]['short_name']);
	$core->current_module_id = intval($modules[$iterate_index]['module_id']);

	// Set Language
	$keys = array();
	eval('$current_lang = $' . $core->current_module_name . ';');
		
	if (is_array($current_lang))
	{
		foreach ($current_lang as $key => $value)
		{
			$lang[$key] = $value;
			$keys[] = $key;
		}
	}

	include($phpbb_root_path . $core->current_module_path . 'module.'.$phpEx);

	$iterate_index++;

}

$template->set_filenames(array(
	'indexstats_body' => 'index_statistics_body.tpl')
);

$template->assign_var_from_handle('STATISTIC_BLOK', 'indexstats_body');

?>