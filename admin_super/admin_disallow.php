<?php
/** 
*
* @package admin_super
* @version $Id:  admin_disallow.php, v1.9.2.4 2004/12/26 16:10:32 kooky Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Users']['Disallow'] = $filename;

	return;
}

//
// Include required files, get $phpEx and check permissions
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

if( isset($HTTP_POST_VARS['add_name']) )
{
	include($phpbb_root_path . 'includes/functions_validate.'.$phpEx);

	$disallowed_user = ( isset($HTTP_POST_VARS['username']) ) ? trim($HTTP_POST_VARS['username']) : trim($HTTP_GET_VARS['username']);

	if ($disallowed_user == '')
	{
		message_die(GENERAL_MESSAGE, $lang['Fields_empty']);
	}
	if( !validate_username($disallowed_user) )
	{
		$message = $lang['Disallowed_already'];
	}
	else
	{
		$sql = "INSERT INTO " . DISALLOW_TABLE . " (disallow_username) 
			VALUES('" . str_replace("\'", "''", $disallowed_user) . "')";
		$result = $db->sql_query( $sql );
		if ( !$result )
		{
			message_die(GENERAL_ERROR, "Could not add disallowed user.", "",__LINE__, __FILE__, $sql);
		}
		$message = $lang['Disallow_successful'];
	}

	$message .= "<br /><br />" . sprintf($lang['Click_return_disallowadmin'], "<a href=\"" . append_sid("admin_disallow.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

	message_die(GENERAL_MESSAGE, $message);
}
else if( isset($HTTP_POST_VARS['delete_name']) )
{
	$disallowed_id = ( isset($HTTP_POST_VARS['disallowed_id']) ) ? intval( $HTTP_POST_VARS['disallowed_id'] ) : intval( $HTTP_GET_VARS['disallowed_id'] );
	
	$sql = "DELETE FROM " . DISALLOW_TABLE . " 
		WHERE disallow_id = $disallowed_id";
	$result = $db->sql_query($sql);
	if( !$result )
	{
		message_die(GENERAL_ERROR, "Couldn't removed disallowed user.", "",__LINE__, __FILE__, $sql);
	}

	$message .= $lang['Disallowed_deleted'] . "<br /><br />" . sprintf($lang['Click_return_disallowadmin'], "<a href=\"" . append_sid("admin_disallow.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

	message_die(GENERAL_MESSAGE, $message);

}

//
// Grab the current list of disallowed usernames...
//
$sql = "SELECT * 
	FROM " . DISALLOW_TABLE;
$result = $db->sql_query($sql);
if( !$result )
{
	message_die(GENERAL_ERROR, "Couldn't get disallowed users.", "", __LINE__, __FILE__, $sql );
}

$disallowed = $db->sql_fetchrowset($result);

//
// Ok now generate the info for the template, which will be put out no matter
// what mode we are in.
//
$disallow_select = '<select name="disallowed_id">';

if( trim($disallowed) == "" )
{
	$disallow_select .= '<option value="">' . $lang['No_disallowed'] . '</option>';
}
else 
{
	$user = array();
	for( $i = 0; $i < count($disallowed); $i++ )
	{
		$disallow_select .= '<option value="' . $disallowed[$i]['disallow_id'] . '">' . $disallowed[$i]['disallow_username'] . '</option>';
	}
}

$disallow_select .= '</select>';

$template->set_filenames(array(
	"body" => "admin/disallow_body.tpl")
);

$template->assign_vars(array(
	"S_DISALLOW_SELECT" => $disallow_select,
	"S_FORM_ACTION" => append_sid("admin_disallow.$phpEx"),

	"L_INFO" => $output_info,
	"L_DISALLOW_TITLE" => $lang['Disallow_control'],
	"L_DISALLOW_EXPLAIN" => $lang['Disallow_explain'],
	"L_DELETE" => $lang['Delete'],
	"L_DELETE_DISALLOW" => $lang['Delete_disallow_title'],
	"L_DELETE_EXPLAIN" => $lang['Delete_disallow_explain'],
	"L_ADD" => $lang['Add'],
	"L_ADD_DISALLOW" => $lang['Add_disallow_title'],
	"L_ADD_EXPLAIN" => $lang['Add_disallow_explain'],
	"L_USERNAME" => $lang['Username'])
);

$template->pparse("body");

include('../admin/page_footer_admin.'.$phpEx);

?>