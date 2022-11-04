<?php
/** 
*
* @package admin
* @version $Id: admin_styles_config.php,v 1.333.2.33 2004/11/18 17:49:42 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);
if( !empty($setmodules) )
{
	$file = basename(__FILE__);
//	$module['Styles']['Configuration'] = $file;
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'includes/functions_selects.'.$phpEx);


//
// include language file
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_xs.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_xs.' . $phpEx);
	

if(isset($HTTP_POST_VARS['submit']))
{
	$vars = array('default_style', 'override_user_style', 'viewtopic_style', 'xs_use_cache', 'xs_cache_dir', 'xs_cache_dir_absolute', 'xs_auto_compile', 'xs_auto_recompile', 'xs_separator', 'xs_php', 'xs_def_template', 'xs_check_switches', 'xs_use_isset');
	foreach($vars as $var)
	{
		$new[$var] = trim($HTTP_POST_VARS[$var]);
		if(($var == 'xs_auto_recompile') && !$new['xs_auto_compile'])
		{
			$new[$var] = 0;
		}
		if(addslashes($board_config[$var]) !== $new[$var])
		{
			$sql = "UPDATE " . CONFIG_TABLE . " 
				SET config_value = '{$new[$var]}' 
				WHERE config_name = '{$var}'";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Failed to update general configuration for $config_name", "", __LINE__, __FILE__, $sql);
			}
			$board_config[$var] = stripslashes($new[$var]);
		}
	}
	
	// Remove cache file
	@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);

	$template->assign_block_vars('switch_updated', array());
}

$template->assign_vars(array(
	'L_XS_SETTINGS'	=> $lang['Style'] . ' ' . $lang['Setting'],
	'L_PAGE_EXPLAIN' => sprintf($lang['Config_explain'], $lang['Style']),
	
	'L_XS_WARNING' => $lang['xs_warning'],
	'L_XS_WARNING_EXPLAIN' => $lang['xs_warning_explain'],
	'L_XS_UPDATED' => $lang['xs_updated'],
	'L_XS_UPDATED_EXPLAIN' 	=> sprintf($lang['xs_updated_explain'], append_sid(basename(__FILE__))),
	'L_XS_SETTINGS_CACHE' 	=> $lang['xs_settings_cache'],
	'L_XS_USE_CACHE' => $lang['xs_use_cache'],
	'L_XS_CACHE_EXPLAIN' => $lang['xs_cache_explain'],
	'L_XS_CACHE_DIR' => $lang['xs_cache_dir'],
	'L_XS_CACHE_DIR_EXPLAIN' => $lang['xs_cache_dir_explain'],
	'L_XS_DIR_ABSOLUTE' => $lang['xs_dir_absolute'],
	'L_XS_DIR_ABSOLUTE_EXPLAIN'	=> $lang['xs_dir_absolute_explain'],
	'L_XS_DIR_RELATIVE' => $lang['xs_dir_relative'],
	'L_XS_DIR_RELATIVE_EXPLAIN'	=> $lang['xs_dir_relative_explain'],
	'L_XS_AUTO_COMPILE'	=> $lang['xs_auto_compile'],
	'L_XS_AUTO_COMPILE_EXPLAIN'	=> $lang['xs_auto_compile_explain'],
	'L_XS_AUTO_RECOMPILE' => $lang['xs_auto_recompile'],
	'L_XS_AUTO_RECOMPILE_EXPLAIN' => $lang['xs_auto_recompile_explain'],
	'L_XS_SEPARATOR' => $lang['xs_separator'],
	'L_XS_SEPARATOR_EXPLAIN' => $lang['xs_separator_explain'],
	'L_XS_PHP' => $lang['xs_php'],
	'L_XS_PHP_EXPLAIN' => $lang['xs_php_explain'],
	'L_XS_DEF_TEMPLATE' => $lang['xs_def_template'],
	'L_XS_DEF_TEMPLATE_EXPLAIN'	=> $lang['xs_def_template_explain'],
	'L_XS_CHECK_SWITCHES' => $lang['xs_check_switches'],
	'L_XS_CHECK_SWITCHES_EXPLAIN' => $lang['xs_check_switches_explain'],
	'L_XS_CHECK_SWITCHES_1'	=> $lang['xs_check_switches_1'],
	'L_XS_CHECK_SWITCHES_2'	=> $lang['xs_check_switches_2'],
	'L_XS_USE_ISSET' => $lang['xs_use_isset'],
	'L_XS_DEBUG_HEADER' => $lang['xs_debug_header'],
	'L_XS_DEBUG_EXPLAIN' => $lang['xs_debug_explain'],
	'L_XS_DEBUG_VARS' => $lang['xs_debug_vars'],
	'L_XS_DEBUG_TPL_NAME' => $lang['xs_debug_tpl_name'],
	'L_XS_DEBUG_CACHE_FILENAME'	=> $lang['xs_debug_cache_filename'],
	'L_XS_DEBUG_DATA' => $lang['xs_debug_data'],
	'L_DEFAULT_STYLE' => $lang['Default_style'],
	'L_OVERRIDE_STYLE' => $lang['Override_style'],
	'L_OVERRIDE_STYLE_EXPLAIN' => $lang['Override_style_explain'],
	'L_VIEWTOPIC_STYLE' => $lang['Viewtopic_style'], 
	
	'XS_USE_CACHE' => intval($board_config['xs_use_cache']),
	'XS_CACHE_DIR' => htmlspecialchars($board_config['xs_cache_dir']),
	'XS_CACHE_DIR_ABSOLUTE' => intval($board_config['xs_cache_dir_absolute']),
	'XS_AUTO_COMPILE' => intval($board_config['xs_auto_compile']),
	'XS_AUTO_RECOMPILE' => intval($board_config['xs_auto_recompile']),
	'XS_SEPARATOR' => htmlspecialchars($board_config['xs_separator']),
	'XS_PHP' => htmlspecialchars($board_config['xs_php']),
	'XS_DEF_TEMPLATE' => htmlspecialchars($board_config['xs_def_template']),
	'XS_CHECK_SWITCHES' => intval($board_config['xs_check_switches']),
	'XS_USE_ISSET' => intval($board_config['xs_use_isset']),
	'STYLE_SELECT' => style_select($board_config['default_style'], 'default_style', '../templates'),
	'OVERRIDE_STYLE' => intval($board_config['override_user_style']),
	'VIEWTOPIC_STYLE' => intval($board_config['viewtopic_style']),

	'S_HIDDEN_FIELDS' => $hidden_fields)		
);


function check_cache($filename)
{
	// check if filename is valid
	global $str, $template, $lang;
	
	if(substr($filename, 0, strlen($template->cachedir)) !== $template->cachedir)
	{
		$str .= $lang['xs_check_filename'] . "<br />\n";
		
		return false;
	}
	else
	{
		// try to open file
		$file = @fopen($filename, 'w');
		if(!$file)
		{
			$str .= sprintf($lang['xs_check_openfile1'], $filename) . "<br />\n";
			// try to create directories
			$dir = substr($filename, strlen($template->cachedir), strlen($filename));
			$dirs = explode('/', $dir);
			$path = $template->cachedir; 
			@umask(0);
			if(!@is_dir($path))
			{
				$str .= sprintf($lang['xs_check_nodir'], $path) . "<br />\n";
				if(!@mkdir($path))
				{
					$str .= sprintf($lang['xs_check_nodir2'], $path) . "<br />\n";
					return false;
				}
				else
				{
					$str .= sprintf($lang['xs_check_createddir'], $path) . "<br />\n";
					@chmod($path, 0777);
				}
			}
			else
			{
				$str .= sprintf($lang['xs_check_dir'] , $path) . "<br />\n";
			}
			if (sizeof($dirs) > 0)
			for ($i = 0; $i < sizeof($dirs)-1; $i++)
			{
				if($i>0)
				{
					$path .= '/';
				}
				$path .= $dirs[$i];
				if(!@is_dir($path))
				{
					$str .= sprintf($lang['xs_check_nodir'], $path) . "<br />\n";
					if(!@mkdir($path))
					{
						$str .= sprintf($lang['xs_check_nodir2'], $path) . "<br />\n";
						return false;
					}
					else
					{
						$str .= sprintf($lang['xs_check_createddir'], $path) . "<br />\n";
						@chmod($path, 0777);
					}
				}
				else
				{
					$str .= sprintf($lang['xs_check_dir'] , $path) . "<br />\n";
				}
			}
			// try to open file again after directories were created
			$file = @fopen($filename, 'w');
		}
		if(!$file)
		{
			$str .= sprintf($lang['xs_check_openfile2'], $filename) . "<br />\n";
			return false;
		}
		$str .= sprintf($lang['xs_check_ok'], $filename) . "<br />\n";
		fputs($file, '&nbsp;');
		fclose($file);
		@chmod($filename, 0777);
		
		return true;
	}
}

// test cache
$filename = $template->make_filename('_xs_test.tpl');
$filename2 = $template->make_filename_cache($filename);
$str = '';
if(!check_cache($filename2))
{
	$template->assign_block_vars('switch_xs_warning', array());
}
@unlink($filename2);
$debug1 = $str;

// test cache
$filename3 = $template->make_filename('admin/_admin_xs_test.tpl');
$filename4 = $template->make_filename_cache($filename3);
$str = '';
check_cache($filename4);
@unlink($filename4);
$debug2 = $str;

// add realpath
if(@function_exists('realpath') && @realpath($phpbb_root_path . 'includes/template.'.$phpEx))
{
	$str = @realpath($filename);
	if($str && ($str !== $filename))
	{
		$filename = $filename . '<br />(' . $str . ')';
	}
	$str = @realpath($filename2);
	if($str && ($str !== $filename2))
	{
		$filename2 = $filename2 . '<br />(' . $str . ')';
	}
	$str = @realpath($filename3);
	if($str && ($str !== $filename3))
	{
		$filename3 = $filename3 . '<br />(' . $str . ')';
	}
	$str = @realpath($filename4);
	if($str && ($str !== $filename4))
	{
		$filename4 = $filename4 . '<br />(' . $str . ')';
	}
}

$template->assign_vars(array(
	'XS_DEBUG_HDR1'	=> sprintf($lang['xs_check_hdr'], '_xs_test.tpl'),
	'XS_DEBUG_HDR2'	=> sprintf($lang['xs_check_hdr'], 'admin/_xs_test.tpl'),
	'XS_DEBUG_FILENAME1' => $filename,
	'XS_DEBUG_FILENAME2' => $filename2,
	'XS_DEBUG_FILENAME3' => $filename3,
	'XS_DEBUG_FILENAME4' => $filename4,
	'XS_DEBUG_DATA'	=> $debug1,
	'XS_DEBUG_DATA2' => $debug2)
);

$template->set_filenames(array(
	'body' => 'admin/styles_config_body.tpl')
);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>