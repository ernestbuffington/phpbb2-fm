<?php
/*
*
* @package admin
* @version $Id: admin_pa_settings.php,v 3.0.0 2004/11/17 17:49:33 mohd Exp $
* @copyright (c) 2004 Mohd
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*  paFileDB 3.0
*  ©2001/2002 PHP Arena
*  Written by Todd
*  todd@phparena.net
*  http://www.phparena.net
*
*/

define('IN_PHPBB', 1);
define('IN_PA_CONFIG_ADMIN', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
//	$module['Download'][$lang['Settings']] = $file;
	
	return;
}

$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'includes/functions_selects.'.$phpEx);
include($phpbb_root_path . 'mods/pafiledb/pafiledb_common.'.$phpEx);


//
// Check to see what mode we should operate in.
//
if( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode);
}
else
{
	$mode = 'config';
}

$submit = (isset($_POST['submit'])) ? true : false;
$size = (isset($_POST['max_size'])) ? $_POST['max_size'] : '';

//
// Pull all config data
//
$sql = 'SELECT *
	FROM ' . PA_CONFIG_TABLE;
if(!$result = $db->sql_query($sql))
{
	message_die(CRITICAL_ERROR, 'Could not query config information in admin_pa_settings.', '', __LINE__, __FILE__, $sql);
}
else
{
	while( $row = $db->sql_fetchrow($result) )
	{
		$config_name = $row['config_name'];
		$config_value = $row['config_value'];
		$default_config[$config_name] = $config_value;
				
		$new[$config_name] = ( isset($_POST[$config_name]) ) ? $_POST[$config_name] : $default_config[$config_name];

		if ((empty($size)) && (!$submit) && ($config_name == 'max_file_size'))
		{
			$size = (intval($default_config[$config_name]) >= 1048576) ? 'mb' : ( (intval($default_config[$config_name]) >= 1024) ? 'kb' : 'b' );
		}
		
		if ( (!$submit) && ($config_name == 'max_file_size') )
		{
			if($new[$config_name] >= 1048576)
			{
				$new[$config_name] = round($new[$config_name] / 1048576 * 100) / 100;
			}
			else if($new[$config_name] >= 1024)
			{
				$new[$config_name] = round($new[$config_name] / 1024 * 100) / 100;
			}
		}
		
		if($submit)
		{
			if ($config_name == 'max_file_size')
			{
				$new[$config_name] = ( $size == 'kb' ) ? round($new[$config_name] * 1024) : ( ($size == 'mb') ? round($new[$config_name] * 1048576) : $new[$config_name] );
			}

			if($config_name == 'tpl_php' && isset($_POST[$config_name]) && $new[$config_name] != $default_config[$config_name])
			{
				$pafiledb_template->compile_cache_clear();
			}

			$pafiledb_functions->set_config($config_name, $new[$config_name]);
		}
	}
	$db->sql_freeresult($result);

	if($submit)
	{
		$cache->unload();
		
		$message = $lang['Board_config_updated'] . '<br /><br />' . sprintf($lang['Click_return_config'], '<a href="' . append_sid('admin_pa_settings.'.$phpEx.'?mode=' . $mode) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.'.$phpEx) . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message); 
	}
}


//
// Check to see what scetion we should load
//
switch($mode)
{
	case 'perms':
		$template->assign_block_vars('switch_perms', array());
		break;
	case 'comments':
		$template->assign_block_vars('switch_comments', array());
		break;
	case 'validate':
		$template->assign_block_vars('switch_validate', array());
		break;
	case 'config':
	default:
		$template->assign_block_vars('switch_config', array());
		break;
}
$hidden_fields = '<input type="hidden" name="mode" value="' . $mode . '" />';


$template->set_filenames(array(
	'admin' => 'admin/pa_config_body.tpl')
);  

$cat_auth_levels = array('ALL', 'REG', 'PRIVATE', 'MOD', 'ADMIN');
$cat_auth_const = array(AUTH_ALL, AUTH_REG, AUTH_ACL, AUTH_MOD, AUTH_ADMIN);
$global_auth = array('auth_search', 'auth_stats', 'auth_toplist', 'auth_viewall');
$auth_select = array();

foreach($global_auth as $auth)
{
	$auth_select[$auth]	= '&nbsp;<select name="' . $auth . '">';
	for($k = 0; $k < sizeof($cat_auth_levels); $k++)
	{
		$selected = ( $new[$auth] == $cat_auth_const[$k] ) ? ' selected="selected"' : '';
		$auth_select[$auth] .= '<option value="' . $cat_auth_const[$k] . '"' . $selected . '>' . $lang['Category_' . $cat_auth_levels[$k]] . '</option>';
	}
	$auth_select[$auth] .= '</select>&nbsp;';
}

$view_all_yes = ($new['settings_viewall']) ? 'checked="checked"' : '';
$view_all_no = (!$new['settings_viewall']) ? 'checked="checked"' : '';
	
$download_disable_yes = ($new['settings_disable']) ? 'checked="checked"' : '';
$download_disable_no = (!$new['settings_disable']) ? 'checked="checked"' : '';

$hotlink_prevent_yes = ($new['hotlink_prevent']) ? 'checked="checked"' : '';
$hotlink_prevent_no = (!$new['hotlink_prevent']) ? 'checked="checked"' : '';	

$php_template_yes = ($new['settings_tpl_php']) ? 'checked="checked"' : '';
$php_template_no = (!$new['settings_tpl_php']) ? 'checked="checked"' : '';	
	
$allow_html_yes = ($new['allow_html']) ? 'checked="checked"' : '';
$allow_html_no = (!$new['allow_html']) ? 'checked="checked"' : '';
	
$allow_bbcode_yes = ($new['allow_bbcode']) ? 'checked="checked"' : '';
$allow_bbcode_no = (!$new['allow_bbcode']) ? 'checked="checked"' : '';
	
$allow_smilies_yes = ($new['allow_smilies']) ? 'checked="checked"' : '';
$allow_smilies_no = (!$new['allow_smilies']) ? 'checked="checked"' : '';	

$allow_comment_links_yes = ($new['allow_comment_links']) ? 'checked="checked"' : '';
$allow_comment_links_no = (!$new['allow_comment_links']) ? 'checked="checked"' : '';
	
$allow_comment_images_yes = ($new['allow_comment_images']) ? 'checked="checked"' : '';
$allow_comment_images_no = (!$new['allow_comment_images']) ? 'checked="checked"' : '';
	
// MX Addon
$need_validation_yes = ($new['need_validation']) ? 'checked="checked"' : '';
$need_validation_no = (!$new['need_validation']) ? 'checked="checked"' : '';

$pm_notify_yes = ($new['pm_notify']) ? 'checked="checked"' : '';
$pm_notify_no = (!$new['pm_notify']) ? 'checked="checked"' : '';

$template->assign_vars(array(
	'L_PAGE_TITLE' => $lang['Download'] . ' ' . $lang['Setting'],
	'L_PAGE_EXPLAIN' => sprintf($lang['Config_explain'], $lang['Download']),
		
	'L_MAX_FILE_SIZE' => $lang['Max_pa_filesize'],
	'L_MAX_FILE_SIZE_INFO' => $lang['Max_pa_filesize_explain'],
	'L_UPLOAD_DIR' => $lang['Upload_directory'],
	'L_UPLOAD_DIR_EXPLAIN' => $lang['Upload_pa_directory_explain'],
	'L_SCREENSHOT_DIR' => $lang['Screenshots_directory'],
	'L_SCREENSHOT_DIR_EXPLAIN' => $lang['Screenshots_directory_explain'],
	'L_FORBIDDEN_EXTENSIONS' => $lang['Forbidden_extensions'],
	'L_FORBIDDEN_EXTENSIONS_EXPLAIN' => $lang['Forbidden_extensions_explain'],
	'L_PERMISSION_SETTINGS' => $lang['Permission_settings'],
	'L_ATUH_SEARCH' => $lang['Auth_search'],
	'L_ATUH_SEARCH_INFO' => $lang['Auth_search_explain'],
	'L_ATUH_STATS' => $lang['Auth_stats'],
	'L_ATUH_STATS_INFO' => $lang['Auth_stats_explain'],
	'L_ATUH_TOPLIST' => $lang['Auth_toplist'],
	'L_ATUH_TOPLIST_INFO' => $lang['Auth_toplist_explain'],
	'L_ATUH_VIEWALL' => $lang['Auth_viewall'],
	'L_ATUH_VIEWALL_INFO' => $lang['Auth_viewall_explain'],
	
	'L_FILE_IN_PAGE' => $lang['File_per_page'],
	'L_FILE_IN_PAGE_INFO' => $lang['File_per_page_info'],
	'L_HOTLINK' => $lang['Hotlink_prevent'],
	'L_HOTLINK_INFO' => $lang['Hotlinl_prevent_info'],
	'L_HOTLINK_ALLOWED' => $lang['Hotlink_allowed'],
	'L_HOTLINK_ALLOWED_INFO' => $lang['Hotlink_allowed_info'],
	'L_PHP_TPL' => $lang['Php_template'],
	'L_PHP_TPL_INFO' => $lang['Php_template_info'],
	'L_DBNAME' => $lang['Dbname'],
	'L_DBNAMEINFO' => $lang['Dbnameinfo'],
	'L_TOPNUM' => $lang['Topnum'],
	'L_TOPNUMINFO' => $lang['Topnuminfo'],
	'L_NFDAYS' => $lang['Nfdays'],
	'L_NFDAYSINFO' => $lang['Nfdaysinfo'],
	'L_COMMENT_SETTINGS' => $lang['Com_settings'],
	'L_SHOW_VIEWALL' => $lang['Showva'],
	'L_VIEWALL_INFO' => $lang['Showvainfo'],
	'L_DISABLE' => $lang['Dbdl'],
	'L_DISABLE_INFO' => $lang['Dbdlinfo'],
	'L_ALLOW_HTML' => $lang['Com_allowh'],
	'L_ALLOW_BBCODE' => $lang['Com_allowb'],
	'L_ALLOW_SMILIES' => $lang['Com_allows'],
	'L_ALLOW_LINKS' => $lang['Com_allowl'],
	'L_LINKS_MESSAGE' => $lang['Com_messagel'],
	'L_LINKS_MESSAGE_INFO' => $lang['Com_messagel_info'],
	'L_ALLOW_IMAGE' => $lang['Com_allowi'],
	'L_IMAGE_MESSAGE' => $lang['Com_messagei'],
	'L_MAX_CHAR' => $lang['Max_char'],
	'L_MAX_CHAR_INFO' => $lang['Max_char_info'],
	'L_IMAGE_MESSAGE_INFO' => $lang['Com_messagei_info'],
	'L_DEFAULT_SORT_METHOD' => $lang['Default_sort_method'],
	'L_DEFAULT_SORT_ORDER' => $lang['Default_sort_order'],
	'L_RATING' => $lang['DlRating'],
	'L_DOWNLOADS' => $lang['Dls'],
	'L_DATE' => $lang['Date'],
	'L_NAME' => $lang['Name'],
	'L_UPDATE_TIME' => $lang['Update_time'],
	'L_ASC' => $lang['Sort_Ascending'],
	'L_DESC' => $lang['Sort_Descending'],
	'L_VALIDATION_SETTINGS' => $lang['Validation_settings'],
	'L_NEED_VALIDATION' => $lang['Need_validate'],
	'L_VALIDATOR' => $lang['Validator'],
	'L_PM_NOTIFY' => $lang['PM_notify'],
	'L_VALIDATOR_ADMIN_OPTION' => $lang['Auth_Admin'],
	'L_VALIDATOR_MOD_OPTION' => $lang['Validator_mod_option'],

	'SETTINGS_DBNAME' => $new['settings_dbname'],
	'SETTINGS_FILE_PAGE' => $new['settings_file_page'],
	'HOTLINK_ALLOWED' => $new['hotlink_allowed'],
	'SETTINGS_TOPNUMBER' => $new['settings_topnumber'],
	'SETTINGS_NEWDAYS' => $new['settings_newdays'],
	'SETTINGS_TOPNUMBER' => $new['settings_topnumber'],
	'MESSAGE_LINK' => $new['no_comment_link_message'],
	'MAX_CHAR' => $new['max_comment_chars'],
	'MESSAGE_IMAGE' => $new['no_comment_image_message'],
	'SORT_NAME' => ($new['sort_method'] == 'file_name') ? 'selected="selected"' : '',
	'SORT_TIME' => ($new['sort_method'] == 'file_time') ? 'selected="selected"' : '',
	'SORT_RATING' => ($new['sort_method'] == 'rating') ? 'selected="selected"' : '',
	'SORT_DOWNLOADS' => ($new['sort_method'] == 'file_dls') ? 'selected="selected"' : '',
	'SORT_UPDATE_TIME' => ($new['sort_method'] == 'file_update_time') ? 'selected="selected"' : '',
	'SORT_ASC' => ($new['sort_order'] == 'ASC') ? 'checked="checked"' : '',
	'SORT_DESC' => ($new['sort_order'] == 'DESC') ? 'checked="checked"' : '',
	'VALIDATOR_ADMIN' => ($new['validator'] == 'validator_admin') ? 'selected="selected"' : '',
	'VALIDATOR_MOD' => ($new['validator'] == 'validator_mod') ? 'selected="selected"' : '',
	'MAX_FILE_SIZE' => $new['max_file_size'],
	'UPLOAD_DIR' => $new['upload_dir'],
	'SCREENSHOT_DIR' => $new['screenshots_dir'],
	'FORBIDDEN_EXTENSIONS' => $new['forbidden_extensions'],	
	'S_FILESIZE' => pa_size_select('max_size', $size),
	'S_ATUH_SEARCH' => $auth_select['auth_search'],
	'S_ATUH_STATS' => $auth_select['auth_stats'],
	'S_ATUH_TOPLIST' => $auth_select['auth_toplist'],
	'S_ATUH_VIEWALL' => $auth_select['auth_viewall'],
	'S_VIEW_ALL_YES' => $view_all_yes,
	'S_VIEW_ALL_NO' => $view_all_no,
	'S_DISABLE_YES' => $download_disable_yes,
	'S_DISABLE_NO' => $download_disable_no,
	'S_PHP_TPL_YES' => $php_template_yes,
	'S_PHP_TPL_NO' => $php_template_no,
	'S_HOTLINK_YES' => $hotlink_prevent_yes,
	'S_HOTLINK_NO' => $hotlink_prevent_no,
	'S_ALLOW_HTML_YES' => $allow_html_yes,
	'S_ALLOW_HTML_NO' => $allow_html_no,
	'S_ALLOW_BBCODE_YES' => $allow_bbcode_yes,
	'S_ALLOW_BBCODE_NO' => $allow_bbcode_no,
	'S_ALLOW_SMILIES_YES' => $allow_smilies_yes,
	'S_ALLOW_SMILIES_NO' => $allow_smilies_no,
	'S_ALLOW_LINKS_YES' => $allow_comment_links_yes,
	'S_ALLOW_LINKS_NO' => $allow_comment_links_no,

	'S_NEED_VALIDATION_YES' => $need_validation_yes,
	'S_NEED_VALIDATION_NO' => $need_validation_no,
	'S_PM_NOTIFY_YES' => $pm_notify_yes,
	'S_PM_NOTIFY_NO' => $pm_notify_no,

	'S_ALLOW_IMAGES_YES' => $allow_comment_images_yes,
	'S_ALLOW_IMAGES_NO' => $allow_comment_images_no,
		
	// All pages
	'CONFIG_SELECT' => config_select($mode, array(
		'config' => $lang['Configuration'],
		'perms' => $lang['Permission_settings'],
		'comments' => $lang['Com_settings'],
		'validate' => $lang['Validation_settings'])
	),
	
	'S_HIDDEN_FIELDS' => $hidden_fields,
	'S_CONFIG_ACTION' => append_sid('admin_pa_settings.'.$phpEx))		
);                 

$template->pparse('admin');

$pafiledb->_pafiledb();
$cache->unload();

include('./page_footer_admin.'.$phpEx);

// ------------------
// Begin function block
//
function pa_size_select($select_name, $size_compare)
{
	global $lang;

	$size_types_text = array($lang['Bytes'], 'KB', 'MB');
	$size_types = array('b', 'kb', 'mb');

	$select_field = '<select name="' . $select_name . '">';

	for ($i = 0; $i < sizeof($size_types_text); $i++)
	{
		$selected = ($size_compare == $size_types[$i]) ? ' selected="selected"' : '';

		$select_field .= '<option value="' . $size_types[$i] . '"' . $selected . '>' . $size_types_text[$i] . '</option>';
	}
	
	$select_field .= '</select>';

	return ($select_field);
}
//
// End function block
// ------------------

?>