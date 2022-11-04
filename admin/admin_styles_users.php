<?php
/** 
*
* @package admin
* @version $Id: admin_styles_users.php,v 1.133.2.33 2004/11/18 17:49:42 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

if(!defined('IN_PHPBB'))
{
	define('IN_PHPBB', 1);
	$phpbb_root_path = "./../";
	require($phpbb_root_path . 'extension.inc');
	require('./pagestart.' . $phpEx);
}
else
{
	if( !empty($setmodules) )
	{
		$file = basename(__FILE__);
		$module['Styles']['User Settings'] = $file;
	}
}

//
// include language file
//
if(!defined('XS_LANG_INCLUDED'))
{
	$xs_lang_file = $phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_xs.'.$phpEx;
	if( !@file_exists($xs_lang_file) )
	{	// load english version if there is no translation to current language
		$xs_lang_file = $phpbb_root_path . 'language/lang_english/lang_xs.'.$phpEx;
	}
	@include($xs_lang_file);
	define('XS_LANG_INCLUDED', true);
}

//
// exit if mod was called just to set modules
//
if( !empty($setmodules) )
{
	return;
}

//
// set new default style
//
if(!empty($HTTP_GET_VARS['setdefault']))
{
	$board_config['default_style'] = intval($HTTP_GET_VARS['setdefault']);
	
	$sql = "UPDATE " . CONFIG_TABLE . " 
		SET config_value = '" . $board_config['default_style'] . "' 
		WHERE config_name = 'default_style'";
	$db->sql_query($sql);
}

//
// change "override" variable
//
if(isset($HTTP_GET_VARS['setoverride']))
{
	$board_config['override_user_style'] = intval($HTTP_GET_VARS['setoverride']);
	
	$sql = "UPDATE " . CONFIG_TABLE . " 
		SET config_value = '" . $board_config['override_user_style'] . "' 
		WHERE config_name = 'override_user_style'";
	$db->sql_query($sql);
}

//
// move all users to some style
//
if(!empty($HTTP_GET_VARS['moveusers']))
{
	$id = intval($HTTP_GET_VARS['moveusers']);
	
	$sql = "UPDATE " . USERS_TABLE . " 
		SET user_style = '" . $id . "'";
	$db->sql_query($sql);
}

//
// move all users from some style
//
if(!empty($HTTP_POST_VARS['moveaway']))
{
	$id = intval($HTTP_POST_VARS['moveaway']);
	$id2 = intval($HTTP_POST_VARS['style']);
	
	if($id2)
	{
		$sql = "UPDATE " . USERS_TABLE . " 
			SET user_style='" . $id2 . "' 
			WHERE user_style = " . $id;
	}
	else
	{
		$sql = "UPDATE " . USERS_TABLE . " 
			SET user_style = " . $board_config['default_style'] . "
			WHERE user_style = " . $id;
	}
	$db->sql_query($sql);
}

//
// get list of installed styles
//
$sql = 'SELECT themes_id, template_name, style_name, style_version, image_cfg, theme_header, theme_footer 
	FROM ' . THEMES_TABLE . ' 
	ORDER BY style_name';
if(!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, 'Could not get style information!', '', __LINE__, __FILE__, $sql);
}
$style_rowset = $db->sql_fetchrowset($result);

for($i = 0; $i < sizeof($style_rowset); $i++)
{
	$id = $style_rowset[$i]['themes_id'];
	
	$sql = 'SELECT count(user_id) AS total 
		FROM ' . USERS_TABLE . ' 
		WHERE user_style = ' . $id;
	$result = $db->sql_query($sql);
	
	if(!$result)
	{
		$total = 0;
	}
	else
	{
		$total = $db->sql_fetchrow($result);
		$total = $total['total'];
	}

	if ( $style_rowset[$i]['style_name'] == $style_rowset[$i]['template_name'] )
	{
		$template_type = '<b>P</b>';
	}
	else if ( !$style_rowset[$i]['image_cfg'] )
	{
		$template_type = 'C';
	}
	else if ( $style_rowset[$i]['image_cfg'] && !$style_rowset[$i]['theme_header'] && !$style_rowset[$i]['theme_footer'] )
	{
		$template_type = 'ISC';
	}
	else
	{
		$template_type = 'CISC';
	}

	$template->assign_block_vars('styles', array(
		'STYLE'				=> $style_rowset[$i]['style_name'] . ' ' . $style_rowset[$i]['style_version'],
		'TYPE' => $template_type,
		'TEMPLATE'			=> $style_rowset[$i]['template_name'],
		'ID'				=> $id,
		'TOTAL'				=> $total,
		'U_STYLES_EDIT' => append_sid("admin_styles.$phpEx?mode=edit&amp;style_id=" . $style_rowset[$i]['themes_id']),
		'U_STYLES_DELETE' => append_sid("admin_styles.$phpEx?mode=delete&amp;style_id=" . $style_rowset[$i]['themes_id']))
	);
}

$template->assign_vars(array(
	'L_PAGE_TITLE' => $lang['Styles_admin'],
	'L_PAGE_EXPLAIN' => $lang['Styles_explain'],
	'L_XS_STYLES_ID' => $lang['xs_styles_id'],
	'L_XS_STYLES_TYPE' => $lang['Type'],
	'L_XS_STYLES_TEMPLATE' => $lang['Template'],
	'L_XS_STYLES_STYLE' => $lang['Style'],
	'L_XS_STYLES_USER' => $lang['Users'],
	'L_XS_STYLES_OPTIONS' => $lang['Options'],
	'L_XS_STYLES_SET_DEFAULT' => $lang['xs_styles_set_default'],
	'L_XS_STYLES_NO_OVERRIDE' => $lang['xs_styles_no_override'],
	'L_XS_STYLES_DO_OVERRIDE' => $lang['xs_styles_do_override'],
	'L_XS_STYLES_SWITCH_ALL' => $lang['xs_styles_switch_all'],
	'L_XS_STYLES_SWITCH_ALL2' => $lang['xs_styles_switch_all2'],
	'L_XS_STYLES_DEFSTYLE' => $lang['xs_styles_defstyle'],
	'L_XS_STYLES_AVAILABLE' => $lang['xs_styles_available'],
	'L_EDIT' => '<img src="' . $phpbb_root_path . $images['acp_edit'] . '" alt="' . $lang['Edit'] . '" title="' . $lang['Edit'] . '" />',
	'L_DELETE' => '<img src="' . $phpbb_root_path . $images['acp_delete'] . '" alt="' . $lang['Delete'] . '" title="' . $lang['Delete'] . '" />',
	'SCRIPT' => append_sid('admin_styles_users.'.$phpEx))
);

$template->set_filenames(array(
	'body' => 'admin/styles_users_body.tpl')
);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>