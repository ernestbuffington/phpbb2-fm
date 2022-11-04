<?php
/** 
*
* @package admin
* @version $Id: admin_stats_mod_package.php,v 1.2 2003/03/16 19:38:27 acydburn Exp $
* @copyright (c) 2003 Meik Sievertsen
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
//	$module['Statistics']['Package_Module'] = $filename . '?mode=mod_pak';
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = '../';
require($phpbb_root_path . 'extension.inc');
require('pagestart.' . $phpEx);

if( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode); 
}
else
{
	$mode = '';
}

$submit = (isset($HTTP_POST_VARS['submit'])) ? TRUE : FALSE;

if (($mode == 'mod_pak') && ($submit))
{
	$no_page_header = true;
}

@include_once($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_admin_statistics.' . $phpEx);
include($phpbb_root_path . 'mods/statistics/includes/constants.'.$phpEx);
include($phpbb_root_path . 'mods/stats/includes/lang_functions.'.$phpEx);
include($phpbb_root_path . 'mods/stats/includes/stat_functions.'.$phpEx);
include($phpbb_root_path . 'mods/stats/includes/admin_functions.'.$phpEx);

// BEGIN Package Module
if (($mode == 'mod_pak') && ($submit))
{
	$info_file = trim($HTTP_POST_VARS['info_file']);
	$lang_file = trim($HTTP_POST_VARS['lang_file']);
	$php_file = trim($HTTP_POST_VARS['php_file']);

	$pak_name = (trim($HTTP_POST_VARS['pak_name']) != '') ? trim($HTTP_POST_VARS['pak_name']) . '.pak' : 'module.pak';

	// create temporary file
	if (!($fp = fopen($phpbb_root_path . 'modules/cache/' . $pak_name, 'wb')))
	{
		message_die(GENERAL_ERROR, 'Unable to write Package File. Please check the Package Naming.');
	}

	// Write PAK Header
	@fwrite($fp, '3.0.0', 5);
	@fwrite($fp, 'MPAK', 4);
	@fwrite($fp, pack("C*", 0xFF, 0xFC, 0xCC), 3);
	@fwrite($fp, 'INFO', 4);
	@fwrite($fp, pack("C*", 0xCC, 0xFC, 0xFF), 3);

	$content = implode('', file($phpbb_root_path . 'modules/pakfiles/' . $info_file));
	$size = strlen($content);
	@fwrite($fp, $content, $size);
	@fwrite($fp, pack("C*", 0xCC, 0xCC, 0xFF), 3);
	@fwrite($fp, 'INFO', 4);
	@fwrite($fp, pack("C*", 0xFF, 0xCC, 0xCC), 3);

	@fwrite($fp, pack("C*", 0xFF, 0xFC, 0xCC), 3);
	@fwrite($fp, 'LANG', 4);
	@fwrite($fp, pack("C*", 0xCC, 0xFC, 0xFF), 3);
	$content = implode('', file($phpbb_root_path . 'modules/pakfiles/' . $lang_file));
	$size = strlen($content);
	@fwrite($fp, $content, $size);
	@fwrite($fp, pack("C*", 0xCC, 0xCC, 0xFF), 3);
	@fwrite($fp, 'LANG', 4);
	@fwrite($fp, pack("C*", 0xFF, 0xCC, 0xCC), 3);
	
	@fwrite($fp, pack("C*", 0xFF, 0xFC, 0xCC), 3);
	@fwrite($fp, 'MOD', 3);
	@fwrite($fp, pack("C*", 0xCC, 0xFC, 0xFF), 3);
	$content = implode('', file($phpbb_root_path . 'modules/pakfiles/' . $php_file));
	$size = strlen($content);
	@fwrite($fp, $content, $size);
	@fwrite($fp, pack("C*", 0xCC, 0xCC, 0xFF), 3);
	@fwrite($fp, 'MOD', 4);
	@fwrite($fp, pack("C*", 0xFF, 0xCC, 0xCC), 3);

	@fclose($fp);

	$content = implode('', file($phpbb_root_path . 'modules/cache/' . $pak_name));
	
	@unlink($phpbb_root_path . 'modules/cache/' . $pak_name);

	header("Content-Type: text/x-delimtext; name=\"" . $pak_name . "\"");
	header("Content-disposition: attachment; filename=" . $pak_name);

	echo $content;

	exit;
}

if (($mode == 'mod_pak') && (!$submit))
{

	$template->set_filenames(array(
		'body' => 'admin/stat_make_pak.tpl')
	);

	$info_files = $lang_files = $php_files = array();
	
	$dir = @opendir($phpbb_root_path . 'modules/pakfiles');

	while($file = @readdir($dir))
	{
		if( !@is_dir($phpbb_root_path . 'modules/pakfiles' . '/' . $file) )
		{
			if ( eregi('.info$', $file) )
			{
				$info_files[] = $file;
			}
			else if ( eregi('.lang$', $file) )
			{
				$lang_files[] = $file;
			}
			else if ( eregi('.php$', $file) )
			{
				$php_files[] = $file;
			}
		}
	}

	@closedir($dir);

	if ((sizeof($info_files) == 0) || (sizeof($lang_files) == 0) || (sizeof($php_files) == 0))
	{
		message_die(GENERAL_MESSAGE, 'Found no files to package up. Info/Lang/PHP Files have to be placed into \'cache/modules/pakfiles\'.');
	}
	
	sort($info_files, SORT_STRING);
	sort($lang_files, SORT_STRING);
	sort($php_files, SORT_STRING);
	
	$info_select_field = '<select name="info_file">';

	for ($i = 0; $i < sizeof($info_files); $i++)
	{
		$selected = ($i == 0) ? ' selected="selected"' : '';

		$info_select_field .= '<option value="' . $info_files[$i] . '"' . $selected . '>' . $info_files[$i] . '</option>';
	}
	
	$info_select_field .= '</select>';

	$lang_select_field = '<select name="lang_file">';

	for ($i = 0; $i < sizeof($lang_files); $i++)
	{
		$selected = ($i == 0) ? ' selected="selected"' : '';

		$lang_select_field .= '<option value="' . $lang_files[$i] . '"' . $selected . '>' . $lang_files[$i] . '</option>';
	}
	
	$lang_select_field .= '</select>';

	$php_select_field = '<select name="php_file">';

	for ($i = 0; $i < sizeof($php_files); $i++)
	{
		$selected = ($i == 0) ? ' selected="selected"' : '';

		$php_select_field .= '<option value="' . $php_files[$i] . '"' . $selected . '>' . $php_files[$i] . '</option>';
	}
	
	$php_select_field .= '</select>';
		
	$template->assign_vars(array(
		'L_PACKAGE_MODULE' => $lang['Package_module'],
		'L_PACKAGE_MODULE_EXPLAIN' => $lang['Package_module_explain'],
		'L_SELECT_INFO_FILE' => $lang['Select_info_file'],
		'L_SELECT_LANG_FILE' => $lang['Select_lang_file'],
		'L_SELECT_MODULE_FILE' => $lang['Select_module_file'],
		'L_PACKAGE_NAME' => $lang['Package_name'],
		'L_CREATE' => $lang['Create'],
	
		'S_ACTION' => append_sid($phpbb_root_path . 'admin/admin_stats_mod_package.' . $phpEx . '?mode=' . $mode),
		'S_LANG_FILE' => $lang_select_field,
		'S_INFO_FILE' => $info_select_field,
		'S_PHP_FILE' => $php_select_field)
	);

}

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>