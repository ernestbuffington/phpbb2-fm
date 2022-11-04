<?php
/** 
*
* @package admin
* @version $Id: admin_styles_advance_html.php,v 1.51.2.3 2002/12/19 17:17:39 psotfx Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Styles']['Advanced_HTML'] = $file;
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

//
// Pull all config data
//
$sql = "SELECT *
	FROM " . ADVANCE_HTML_TABLE;
if(!$result = $db->sql_query($sql))
{
	message_die(CRITICAL_ERROR, 'Could not query config advanced html information.', '', __LINE__, __FILE__, $sql);
}
else
{
	while( $row = $db->sql_fetchrow($result) )
	{
		$config_name = $row['config_name'];
		$config_value = $row['config_value'];
		$default_config[$config_name] = $config_value;
		
		$new[$config_name] = ( isset($HTTP_POST_VARS[$config_name]) ) ? $HTTP_POST_VARS[$config_name] : $default_config[$config_name];
	
		if( isset($HTTP_POST_VARS['submit']) )
		{
			$sql = "UPDATE " . ADVANCE_HTML_TABLE . " 
				SET config_value = '" . str_replace("\'", "''", $new[$config_name]) . "'
				WHERE config_name = '$config_name'";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Failed to update general configuration for $config_name", "", __LINE__, __FILE__, $sql);
			}
		}
	}
	$db->sql_freeresult($result);

	if( isset($HTTP_POST_VARS['submit']) )
	{
		// Remove cache file
		@unlink($phpbb_root_path . 'cache/config_html.'.$phpEx);

		$message = $lang['Board_config_updated'] . "<br /><br />" . sprintf($lang['Click_return_config'], "<a href=\"" . append_sid("admin_styles_advance_html.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message);
	}
}

$template->set_filenames(array(
	'body' => 'admin/styles_advance_html_body.tpl')
);

$template->assign_vars(array(
	'S_CONFIG_ACTION' => append_sid('admin_styles_advance_html.'.$phpEx),

	'L_ADVANCE_HTML_TITLE' => $lang['Advanced_HTML'] . ' ' . $lang['Setting'],
	'L_ADVANCE_HTML_DESCRIPTION' => $lang['Advance_HTML_Description'],	
	'L_CUSTOM_HEADER' => $lang['Custom_Header'],	
	'L_CUSTOM_BODY_START' => $lang['Custom_Body_Start'],
	'L_CUSTOM_BODY_END' => $lang['Custom_Body_End'],
	'L_CUSTOM_FOOTER' => $lang['Custom_Footer'],
	'L_CUSTOM_END' => $lang['Custom_End'],

	'CUSTOM_HEADER' => $new['custom_header'],
	'CUSTOM_BODY' => $new['custom_body'],
	'CUSTOM_BODY_HEADER' => $new['custom_body_header'],
	'CUSTOM_FOOTER' => $new['custom_footer'])
);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>