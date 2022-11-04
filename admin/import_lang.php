<?php
/** 
*
* @package admin
* @version $Id: import_lang.php,v 4.2.8 2003/03/16 18:38:29 acydburn Exp $
* @copyright (c) 2003 Meik Sievertsen
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
if (!empty($board_config))
{
	@include_once($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_admin_statistics.' . $phpEx);
}

if ($cancel)
{
	$no_page_header = TRUE;
}

require('./pagestart.' . $phpEx);

$submit = (isset($HTTP_POST_VARS['submit'])) ? TRUE : FALSE;
$cancel = ( isset($HTTP_POST_VARS['cancel']) || isset($_POST['cancel']) ) ? true : false;

if( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
}
else
{
	$mode = '';
}

@include_once($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_admin_statistics.' . $phpEx);
include($phpbb_root_path . 'mods/stats/includes/constants.'.$phpEx);
include($phpbb_root_path . 'mods/stats/includes/stat_functions.'.$phpEx);
include($phpbb_root_path . 'mods/stats/includes/admin_functions.'.$phpEx);
include($phpbb_root_path . 'mods/stats/includes/lang_functions.'.$phpEx);

if ($cancel)
{
	$url = 'admin/' . append_sid("admin_stats_lang.$phpEx?mode=select", true);
	
	$server_protocol = ($board_config['cookie_secure']) ? 'https://' : 'http://';
	$server_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($board_config['server_name']));
	$server_port = ($board_config['server_port'] <> 80) ? ':' . trim($board_config['server_port']) . '/' : '/';
	$script_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($board_config['script_path']));
	$url = preg_replace('/^\/?(.*?)\/?$/', '/\1', trim($url));

	// Redirect via an HTML form for PITA webservers
	if (@preg_match('/Microsoft|WebSTAR|Xitami/', getenv('SERVER_SOFTWARE')))
	{
		header('Refresh: 0; URL=' . $server_protocol . $server_name . $server_port . $script_name . $url);
		echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"><meta http-equiv="refresh" content="0; url=' . $server_protocol . $server_name . $server_port . $script_name . $url . '"><title>Redirect</title></head><body><div align="center">If your browser does not support meta redirection please click <a href="' . $server_protocol . $server_name . $server_port . $script_name . $url . '">HERE</a> to be redirected</div></body></html>';
		exit;
	}

	// Behave as per HTTP/1.1 spec for others
	header('Location: ' . $server_protocol . $server_name . $server_port . $script_name . $url);
	exit;
}

if (($mode == 'import_new_lang') && ($submit))
{
	$template->set_filenames(array(
		'body' => 'admin/stat_import_language.tpl')
	);

	if (isset($HTTP_POST_VARS['install_language']))
	{
		$filename = trim(stripslashes(htmlspecialchars($HTTP_POST_VARS['filename'])));
		
		if (!($fp = @fopen($filename, 'r')) )
		{
			message_die(GENERAL_ERROR, 'Unable to open ' . $filename);
		}

		read_lang_pak_header($fp);
		@fclose($fp);

		if (strstr($filename, 'test.pak'))
		{
			@unlink($filename);
		}

		$stream = implode('', @file($filename));
		$lang_file = read_pak_file($stream, 'LANGPACK');

		// Prepare the Data
		$lang_array = parse_lang_file($lang_file);

		$languages = get_all_installed_languages();
		$inst_langs = array();
		@reset($lang_array);
		while (list($language, $content) = each($lang_array))
		{
			if (!in_array($language, $languages))
			{
				$inst_langs[$language] = $content;
			}
		}

		if (sizeof($inst_langs) == 0)
		{
			message_die(GENERAL_ERROR, 'All Languages enclosed within this Language Pack are already installed.');
		}

		$lang_array = $inst_langs;

		@reset($lang_array);
		while (list($key, $data) = @each($lang_array))
		{
			$modules = get_modules_from_lang_block($data);
			add_new_language_predefined($key, $modules);
		}
		
		message_die(GENERAL_MESSAGE, $lang['Language_pak_installed']);
	}

	if ( isset($HTTP_POST_VARS['fileselect']) )
	{
		$filename = $phpbb_root_path . 'modules/pakfiles/' . trim(htmlspecialchars($HTTP_POST_VARS['selected_pak_file']));
	}
	else if (isset($HTTP_POST_VARS['fileupload']))
	{
		$filename = $HTTP_POST_FILES['package']['tmp_name'];

		// check php upload-size
		if ( ($filename == 'none') || ($filename == '') )
		{
			message_die(GENERAL_ERROR, 'Unable to upload file, please use the pak file selector');
		}

		$contents = @implode('', @file($filename));

		if ($contents == '')
		{
			message_die(GENERAL_ERROR, 'Unable to upload file, please use the pak file selector');
		}

		if (!file_exists($phpbb_root_path . 'modules/cache'))
		{
			@umask(0);
			mkdir($phpbb_root_path . 'modules/cache', 0777);
		}
		
		if (!($fp = @fopen($phpbb_root_path . 'modules/cache/temp.pak', 'wt')))
		{
			message_die(GENERAL_ERROR, 'Unable to write temp file');
		}

		@fwrite($fp, $contents, strlen($contents));
		@fclose($fp);

		$filename = $phpbb_root_path . 'modules/cache/temp.pak';
	}
	else
	{
		message_die(GENERAL_ERROR, 'Unable to find Module Package');
	}

	if (!($fp = @fopen($filename, 'r')) )
	{
		message_die(GENERAL_ERROR, 'Unable to open ' . $filename);
	}
	
	read_lang_pak_header($fp);
	@fclose($fp);

	$stream = implode('', @file($filename));
	$lang_file = read_pak_file($stream, 'LANGPACK');

	$s_hidden_fields = '<input type="hidden" name="filename" value="' . $filename . '">';

	// Prepare the Data
	$lang_array = parse_lang_file($lang_file);

	$languages = get_all_installed_languages();
	$inst_langs = array();
	@reset($lang_array);
	while (list($language, $content) = each($lang_array))
	{
		if (!in_array($language, $languages))
		{
			$inst_langs[$language] = $content;
		}
	}

	if (sizeof($inst_langs) == 0)
	{
		message_die(GENERAL_ERROR, 'All Languages enclosed within this Language Pack are already installed.');
	}

	$lang_array = $inst_langs;

	// Prepare Template
	$template->assign_block_vars('switch_install_language', array());

	$template->assign_vars(array(
		'L_IMPORT_LANGUAGE' => $lang['Import_new_language'],
		'L_IMPORT_LANGUAGE_EXPLAIN' => $lang['Import_new_language_explain'],
		'L_INSTALL_LANGUAGE' => $lang['Install_language'],
		'L_INSTALL' => $lang['Install'],
		'L_LANGUAGE' => $lang['Language'],
		'L_MODULES' => $lang['Modules'])
	);

	@reset($lang_array);
	while (list($key, $data) = @each($lang_array))
	{
		$language = str_replace('lang_', '', $key);

		$template->assign_block_vars('languages', array(
			'LANGUAGE' => $language)
		);

		$modules = get_modules_from_lang_block($data);
		@reset($modules);
		while (list($module_name, $module_data) = each($modules))
		{
			$template->assign_block_vars('languages.modules', array(
				'MODULE' => $module_name)
			);
		}
	}

	$s_hidden_fields .= '<input type="hidden" name="install_language" value="1" />';

	$template->assign_vars(array(
		'S_HIDDEN_FIELDS' => $s_hidden_fields)
	);
}

if (($mode == 'import_new_lang') && (!$submit))
{
	$template->set_filenames(array(
		'body' => 'admin/stat_import_language.tpl')
	);

	if ( (!isset($HTTP_POST_VARS['fileupload'])) && (!isset($HTTP_POST_VARS['fileselect'])) )
	{
		$lang_paks = array();
	
		$dir = @opendir($phpbb_root_path . 'modules/pakfiles');

		while($file = @readdir($dir))
		{
			if( !@is_dir($phpbb_root_path . 'modules/pakfiles' . '/' . $file) )
			{
				if ( eregi('.pak$', $file) )
				{
					if (strstr($file, 'lang_'))
					{
						$lang_paks[] = $file;
					}
				}
			}
		}

		@closedir($dir);

		if (sizeof($lang_paks) > 0)
		{
			$template->assign_block_vars('switch_select_lang', array());

			$module_select_field = '<select name="selected_pak_file">';

			for ($i = 0; $i < sizeof($module_paks); $i++)
			{
				$selected = ($i == 0) ? ' selected="selected"' : '';

				$module_select_field .= '<option value="' . $lang_paks[$i] . '"' . $selected . '>' . $lang_paks[$i] . '</option>';
			}
	
			$module_select_field .= '</select>';
			
			$s_hidden_fields = '<input type="hidden" name="fileselect" value="1" />';

			$template->assign_vars(array(
				'L_SELECT_LANGUAGE' => $lang['Select_language_pak'],
				'S_SELECT_LANGUAGE' => $module_select_field,
				'S_SELECT_HIDDEN_FIELDS' => $s_hidden_fields)
			);
		
		}

		$template->assign_block_vars('switch_upload_lang', array());

		$s_hidden_fields = '<input type="hidden" name="fileupload" value="1" />';

		$template->assign_vars(array(
			'L_IMPORT_LANGUAGE' => $lang['Import_new_language'],
			'L_IMPORT_LANGUAGE_EXPLAIN' => $lang['Import_new_language_explain'],
			'L_UPLOAD_LANGUAGE' => $lang['Upload_language_pak'],
			'L_SUBMIT' => $lang['Submit'],
			'S_ACTION' => append_sid($phpbb_root_path . 'admin/import_lang.'.$phpEx.'?mode='.$mode),
			'S_UPLOAD_HIDDEN_FIELDS' => $s_hidden_fields)
		);

	}
}

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>