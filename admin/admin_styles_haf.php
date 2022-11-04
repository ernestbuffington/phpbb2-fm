<?php
/***************************************************************************
 *                              admin_styles_haf.php
 *                            -------------------
 *   begin                : Thursday, Jul 12, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: admin_avatar.php,v 1.51.2.9 2004/11/18 17:49:33 acydburn Exp $
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
	$file = basename(__FILE__);
	$module['Styles']['Custom_Footer_and_Header_settings'] = $file;
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);


//
// Pull all config data
//
$sql = "SELECT *
	FROM " . CONFIG_TABLE;
if(!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, "Could not query config information in admin_styles_haf", "", __LINE__, __FILE__, $sql);
}
else
{
	while( $row = $db->sql_fetchrow($result) )
	{
		$config_name = $row['config_name'];
		$config_value = addslashes($row['config_value']);
		$default_config[$config_name] = $config_value;
		
		$new[$config_name] = ( isset($HTTP_POST_VARS[$config_name]) ) ? $HTTP_POST_VARS[$config_name] : $default_config[$config_name];
		$new[$config_name] = stripslashes($new[$config_name]);

		if( isset($HTTP_POST_VARS['submit']) )
		{
			$sql = "UPDATE " . CONFIG_TABLE . " SET config_value = '" . addslashes($new[$config_name]) . "'
				WHERE config_name = '$config_name'";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Failed to update general configuration for $config_name", "", __LINE__, __FILE__, $sql);
			}
		}
	}

	if( isset($HTTP_POST_VARS['submit']) )
	{
		// Remove cache file
		@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);

		$message = $lang['Board_config_updated'] . "<br /><br />" . sprintf($lang['Click_return_config'], "<a href=\"" . append_sid("admin_styles_haf.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message);
	}
}

$template->set_filenames(array(
	'body' => 'admin/styles_haf_body.tpl')
);

$template->assign_vars(array(
	'S_CONFIG_ACTION' => append_sid("admin_styles_haf.$phpEx"),

	'L_CUSTOM_FOOTER_AND_HEADER_SETTINGS' => $lang['Custom_Footer_and_Header_settings'] . ' ' . $lang['Setting'],
	'L_CUSTOM_OVERALL_HEADER' => $lang['Custom_Overall_Header'],
	'L_CUSTOM_OVERALL_FOOTER' => $lang['Custom_Overall_Footer'],
	'L_CUSTOM_SIMPLE_HEADER' => $lang['Custom_Simple_Header'],
	'L_CUSTOM_SIMPLE_FOOTER' => $lang['Custom_Simple_Footer'],
	'L_BOARD_SIG' => $lang['Board_Sig'],
	'L_BOARD_SIG_EXPLAIN' => $lang['Board_Sig_explain'],
		
	'BOARD_SIG' => $new['board_sig'],
	'CUSTOM_OVERALL_HEADER' => $new['custom_overall_header'],
	'CUSTOM_OVERALL_FOOTER' => $new['custom_overall_footer'],
	'CUSTOM_SIMPLE_HEADER' => $new['custom_simple_header'],
	'CUSTOM_SIMPLE_FOOTER' => $new['custom_simple_footer'])
);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>
