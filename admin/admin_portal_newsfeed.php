<?php
/** 
*
* @package admin
* @version $Id: admin_portal_newsfeed.php,v 1.0 24/03/2005 1:56 PM mj Exp $
* @copyright (c) 2005 MJ, Fully Modded phpBB
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
define('IN_PHPBB', 1);
	
if( !empty($setmodules) )
{
	$file = basename(__FILE__);
//	$module['Portal']['Newsfeed_Settings'] = $file;
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);


// include language file
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_portal.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_portal.' . $phpEx);


//
// Pull all newfeed config data
//
$sql = "SELECT *
	FROM " . CONFIG_TABLE . " 
	WHERE config_name 
	LIKE '%newsfeed_%'";
if(!$result = $db->sql_query($sql))
{
	message_die(CRITICAL_ERROR, 'Could not query newsfeed config information.', __LINE__, __FILE__, $sql);
}
else
{
	while( $row = $db->sql_fetchrow($result) )
	{
		$config_name = $row['config_name'];
		$config_value = $row['config_value'];
		$default_config[$config_name] = $config_value;
		
		$new[$config_name] = ( isset($HTTP_POST_VARS[$config_name]) ) ? $HTTP_POST_VARS[$config_name] : $default_config[$config_name];
		$new[$config_name]= stripslashes($new[$config_name]);

		if( isset($HTTP_POST_VARS['submit']) )
		{
			$sql = "UPDATE " . CONFIG_TABLE . " SET
				config_value = '" . addslashes($new[$config_name]) . "'
				WHERE config_name = '$config_name'";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Failed to update general configuration for ' . $config_name, '', __LINE__, __FILE__, $sql);
			}
		}
	}
	$db->sql_freeresult($result);

	if( isset($HTTP_POST_VARS['submit']) )
	{
		// Remove cache file
		@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);

		$message = $lang['Board_config_updated'] . "<br /><br />" . sprintf($lang['Click_return_config'], "<a href=\"" . append_sid("admin_portal_newsfeed.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message);
	}
}

$template->set_filenames(array(
	'body' => 'admin/portal_newsfeed_body.tpl')
);

$template->assign_vars(array(
	'L_PAGE_TITLE' => $lang['Newsfeed_config_title'],
	'L_PAGE_EXPLAIN' => $lang['Newsfeed_config_explain'],
	'L_CONFIGURATION' => $lang['Configuration'],
	'L_NEWSFEED_RSS' => $lang['Newsfeed_rss'],
	'L_NEWSFEED_RSS_EXPLAIN' => sprintf($lang['Newsfeed_rss_explain'], '<a href="http://w.moreover.com/categories/category_list.html" target="_blank" class="gensmall">', '</a>'),
	'L_NEWSFEED_CACHE' => $lang['Newsfeed_cache'],
	'L_NEWSFEED_CACHE_EXPLAIN' => $lang['Newsfeed_cache_explain'],
	'L_NEWSFEED_CACHETIME' => $lang['Newsfeed_cachetime'],
	'L_NEWSFEED_CACHETIME_EXPLAIN' => $lang['Newsfeed_cachetime_explain'],
	'L_NEWSFEED_AMT' => $lang['Newsfeed_amt'],

	'L_FIELD_CONFIGURATION' => $lang['Newsfeed_field_config'],
	'L_FIELD_CONFIGURATION_EXPLAIN' => $lang['Newsfeed_field_config_explain'],
	'L_NEWSFEED_FIELD_ARTICLE' => $lang['Newsfeed_field_article'],
	'L_NEWSFEED_FIELD_URL' => $lang['Newsfeed_field_url'],
	'L_NEWSFEED_FIELD_TEXT' => $lang['Newsfeed_field_text'],
	'L_NEWSFEED_FIELD_SOURCE' => $lang['Newsfeed_field_source'],
	'L_NEWSFEED_FIELD_TIME' => $lang['Newsfeed_field_time'],

	'NEWSFEED_RSS' => $new['newsfeed_rss'],
	'NEWSFEED_CACHE' => $new['newsfeed_cache'],
	'NEWSFEED_CACHETIME' => $new['newsfeed_cachetime'],
	'NEWSFEED_AMT' => $new['newsfeed_amt'],

	'NEWSFEED_FIELD_ARTICLE' => $new['newsfeed_field_article'],
	'NEWSFEED_FIELD_URL' => $new['newsfeed_field_url'],
	'NEWSFEED_FIELD_TEXT' => $new['newsfeed_field_text'],
	'NEWSFEED_FIELD_SOURCE' => $new['newsfeed_field_source'],
	'NEWSFEED_FIELD_TIME' => $new['newsfeed_field_time'],
	'S_NEWSFEED_ACTION' => append_sid('admin_portal_newsfeed.'.$phpEx))
);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>