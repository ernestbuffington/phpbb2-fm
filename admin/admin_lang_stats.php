<?php
/***************************************************************************
 *                              admin_lang_stats.php
 *                            -------------------
 *   begin                : Monday, July 18, 2003
 *   copyright            : (C) 2005 MJ, Fully Modded Mods
 *   email                : mj@phpbb-fm.com
 *
 *   $Id: admin_lang_stats.php,v 0.0.0.1 7/18/2005 11:25 PM mj Exp $
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Language_edit']['Management'] = $filename;

	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

if( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ( isset($HTTP_GET_VARS['mode']) ) ? $HTTP_GET_VARS['mode'] : $HTTP_POST_VARS['mode'];
	$mode = htmlspecialchars($mode); 
}
else 
{
	$mode = '';
}

if ($HTTP_GET_VARS['mode'] == 'override')
{
	$language = ( isset($HTTP_GET_VARS['lang']) ) ? trim($HTTP_GET_VARS['lang']) : trim($HTTP_POST_VARS['lang']);

	$sql = "UPDATE " . USERS_TABLE . "
		SET user_lang = '" . $language . "'";
	if(!$result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, 'Could not update users language information.', '', __LINE__, __FILE__, $sql);
	}
	else
	{
		$message = $lang['Users_lang_updated'] . "<br /><br />" . sprintf($lang['Click_return_admin_lang_stats'], "<a href=\"" . append_sid("admin_lang_stats.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");
		
		message_die(GENERAL_MESSAGE, $message);
	}
}

$template->set_filenames(array(
	'body' => 'admin/lang_stats_body.tpl')
);

$template->assign_vars(array(
	'L_PAGE_TITLE' => $lang['User_languages'],
	'L_PAGE_EXPLAIN' => $lang['Lang_stats_explain'],
	'L_USERS' => $lang['Users'],
	'L_LANGUAGES' => $lang['Language'],
	'L_SWITCH_USERS' => $lang['Switch_users_lang'])
);


//
// Read a listing of languages...
//
$dirname = 'language';
$dir = opendir($phpbb_root_path . $dirname . '/');

$lang = array();
while ( $file = readdir($dir) )
{
	if (preg_match('#^lang_#i', $file) && !is_file(@phpbb_realpath($phpbb_root_path . $dirname . '/' . $file)) && !is_link(@phpbb_realpath($phpbb_root_path . $dirname . '/' . $file)))
	{
		$filename = trim(str_replace("lang_", "", $file));
		$displayname = preg_replace("/^(.*?)_(.*)$/", "\\1 [ \\2 ]", $filename);
		$displayname = preg_replace("/\[(.*?)_(.*)\]/", "[ \\1 - \\2 ]", $displayname);
		$lang[$displayname] = $filename;
	}
}

closedir($dir);

@asort($lang);
@reset($lang);


//
// Loop through the languages
//
while ( list($displayname, $filename) = @each($lang) )
{
	$sql = "SELECT COUNT(user_lang) AS users
		FROM " . USERS_TABLE . "
		WHERE user_lang = '" . $filename . "'
			AND user_id != " . ANONYMOUS;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not count users with the language: ' . $filename, '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	
	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

	$template->assign_block_vars('lang', array(
		'ROW_CLASS' => $row_class,
		

		'LANG' => ucwords($displayname),
		'NO' => $row['users'],
		'SWITCH_USERS' => append_sid('admin_lang_stats.'.$phpEx.'?mode=override&amp;lang=' . $filename))
	);
	$i++;
}

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>