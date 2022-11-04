<?php
/** 
*
* @package admin
* @version $Id: admin_forumauth_adv.php,v 1.0.2 2002/8/08, 19:41:51 hnt Exp $
* @copyright (c) 2002 Smartor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

$forum_id = '1'; // You could change this value unless forum ID 1 did not exist in your board

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Forums']['Permissions_adv'] = $filename . '?' . POST_FORUM_URL . "=$forum_id";

	return;
}

//
// Load default header
//
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'mods/calendar/mycalendar_functions.'.$phpEx);

//
// Start program - define vars
//
//                View      Read      Post      Reply     Edit      Delete    Sticky    Announce  Global    Vote      Poll      S.Event   Warn/Ban  Voteban   Unban       Report
$simple_auth_ary = array(
	0  => array(AUTH_ALL, AUTH_ALL, AUTH_ALL, AUTH_ALL, AUTH_REG, AUTH_REG, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_MOD, AUTH_REG, AUTH_ADMIN, AUTH_REG),
	1  => array(AUTH_ALL, AUTH_ALL, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_MOD, AUTH_REG, AUTH_ADMIN, AUTH_REG),
	2  => array(AUTH_REG, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_REG, AUTH_REG, AUTH_REG, AUTH_MOD, AUTH_REG, AUTH_ADMIN, AUTH_REG),
	3  => array(AUTH_ALL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_MOD, AUTH_MOD, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_MOD, AUTH_REG, AUTH_ADMIN, AUTH_REG),
	4  => array(AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_MOD, AUTH_MOD, AUTH_ACL, AUTH_ACL, AUTH_ACL, AUTH_MOD, AUTH_REG, AUTH_ADMIN, AUTH_REG),
	5  => array(AUTH_ALL, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_REG, AUTH_ADMIN, AUTH_REG),
	6  => array(AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_MOD, AUTH_REG, AUTH_ADMIN, AUTH_REG),
);

$simple_auth_types = array($lang['Public'], $lang['Registered'], $lang['Registered'] . ' [' . $lang['Hidden'] . ']', $lang['Private'], $lang['Private'] . ' [' . $lang['Hidden'] . ']', $lang['Moderators'], $lang['Moderators'] . ' [' . $lang['Hidden'] . ']');

$forum_auth_fields = array('auth_view', 'auth_read', 'auth_post', 'auth_reply', 'auth_edit', 'auth_delete', 'auth_sticky', 'auth_announce', 'auth_globalannounce', 'auth_vote', 'auth_pollcreate', 'auth_suggest_event', 'auth_ban', 'auth_voteban', 'auth_greencard', 'auth_bluecard');

$field_names = array(
	'auth_view' => $lang['View'],
	'auth_read' => $lang['Read'],
	'auth_post' => $lang['Post'],
	'auth_reply' => $lang['Reply'],
	'auth_edit' => $lang['Edit'],
	'auth_delete' => $lang['Delete'],
	'auth_sticky' => $lang['Sticky'],
	'auth_announce' => $lang['Announce'], 
	'auth_globalannounce' => $lang['Globalannounce'],
	'auth_vote' => $lang['Vote'], 
	'auth_pollcreate' => $lang['Pollcreate'],
	'auth_suggest_event' => $lang['Suggestevent'],
	'auth_ban' => $lang['Ban'], 
	'auth_voteban' => $lang['VoteBan'], 
	'auth_greencard' => $lang['Greencard'], 
	'auth_bluecard' => $lang['Bluecard']
);

$forum_auth_levels = array('ALL', 'REG', 'PRIVATE', 'MOD', 'ADMIN');
$forum_auth_const = array(AUTH_ALL, AUTH_REG, AUTH_ACL, AUTH_MOD, AUTH_ADMIN);

$forum_auth_images = array(
	AUTH_ALL => 'ALL', 
	AUTH_REG => 'REG', 
	AUTH_ACL => 'PRIVATE', 
	AUTH_MOD => 'MOD', 
	AUTH_ADMIN => 'ADMIN',
);

$forum_auth_cats = array(
	'VIEW' => 'auth_view', 
	'READ' => 'auth_read', 
	'POST' => 'auth_post', 
	'REPLY' => 'auth_reply', 
	'EDIT' => 'auth_edit', 
	'DELETE' => 'auth_delete', 
	'STICKY' => 'auth_sticky', 
	'ANNOUNCE' => 'auth_announce', 
	'GLOBALANNOUNCE' => 'auth_globalannounce', 
	'VOTE' => 'auth_vote', 
	'POLLCREATE' => 'auth_pollcreate',
	'SUGGESTEVENT' => 'auth_suggest_event',
	'BAN' => 'auth_ban', 
	'VOTEBAN' => 'auth_voteban', 
	'UNBAN' => 'auth_greencard', 
	'REPORT' => 'auth_bluecard',
	'UPLOAD' => 'auth_attachments',
	'DLOAD' => 'auth_download',
);

for($i=0; $i<count($forum_auth_const); $i++) 
{
	$auth_key .= '<img src="../images/spacer.gif" width="10" height="10" alt="" class="' . $forum_auth_classes[$forum_auth_const[$i]] . '">&nbsp;' . $forum_auth_levels[$i] . '&nbsp;&nbsp;';		
	$template->assign_block_vars("authedit", array(
		'CLASS' => $forum_auth_classes[$forum_auth_const[$i]],
		'NAME' => $forum_auth_levels[$i],
		'VALUE' => $forum_auth_const[$i])
	);
}

if( isset($HTTP_GET_VARS['adv']) )
{
	$adv = intval($HTTP_GET_VARS['adv']);
}
else
{
	unset($adv);
}

$template->set_filenames(array(
	"body" => "admin/auth_overall_forum_body.tpl")
);

//
// Start program proper
//
if( isset($HTTP_POST_VARS['submit']) ) 
{
	foreach($_POST['auth'] as $forum_id => $forum) 
	{
		$forum_id = intval($forum_id);
		$sql = '';
		foreach($forum as $a => $newval) 
		{
			if ($newval && in_array($newval, $forum_auth_levels) && array_key_exists($a, $forum_auth_cats)) 
			{ 
				// Changed and is valid
				$sql .= ( ( $sql != '' ) ? ', ' : '' ) . $forum_auth_cats[$a] . '=' . array_search($newval, $forum_auth_images);
			}
		}
		if ($sql != '') 
		{
			$sql = "UPDATE " . FORUMS_TABLE . " 
				SET $sql 
				WHERE forum_id = $forum_id;";
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not update auth table', '', __LINE__, __FILE__, $sql);
			}
		}
	}
} // End of submit

//
$sql = "SELECT cat_id, cat_title, cat_order
	FROM " . CATEGORIES_TABLE . "
	ORDER BY cat_order";
if( !$q_categories = $db->sql_query($sql) )
{
	message_die(GENERAL_ERROR, "Could not query categories list", "", __LINE__, __FILE__, $sql);
}

if( $total_categories = $db->sql_numrows($q_categories) ) 
{
	$category_rows = $db->sql_fetchrowset($q_categories);

	$sql = "SELECT *
		FROM " . FORUMS_TABLE . "
		ORDER BY cat_id, forum_order";
	if(!$q_forums = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Could not query forums information", "", __LINE__, __FILE__, $sql);
	}

	if( $total_forums = $db->sql_numrows($q_forums) )
	{
		$forum_rows = $db->sql_fetchrowset($q_forums);
	}

	//
	// Okay, let's build the index
	//
	$gen_cat = array();

	for($i = 0; $i < $total_categories; $i++)
	{
		$cat_id = $category_rows[$i]['cat_id'];

		$template->assign_block_vars("catrow", array( 
			'CAT_ID' => $cat_id,
			'CAT_DESC' => $category_rows[$i]['cat_title'],
			
			'U_VIEWCAT' => append_sid('admin_forums.'.$phpEx.'?mode=editcat&amp;' . POST_CAT_URL . '=' . $cat_id))
		);

		for($j = 0; $j < $total_forums; $j++)
		{
			$forum_id = $forum_rows[$j]['forum_id'];
			
			if ($forum_rows[$j]['cat_id'] == $cat_id)
			{
				$template->assign_block_vars("catrow.forumrow",	array(
					'FORUM_NAME' => $forum_rows[$j]['forum_name'],
					'FORUM_ID' => $forum_rows[$j]['forum_id'],
					'ROW_COLOR' => $row_color,
					'AUTH_VIEW_IMG' => $forum_auth_images[$forum_rows[$j]['auth_view']],
					'AUTH_READ_IMG' => $forum_auth_images[$forum_rows[$j]['auth_read']],
					'AUTH_POST_IMG' => $forum_auth_images[$forum_rows[$j]['auth_post']],
					'AUTH_REPLY_IMG' => $forum_auth_images[$forum_rows[$j]['auth_reply']],
					'AUTH_EDIT_IMG' => $forum_auth_images[$forum_rows[$j]['auth_edit']],
					'AUTH_DELETE_IMG' => $forum_auth_images[$forum_rows[$j]['auth_delete']],
					'AUTH_STICKY_IMG' => $forum_auth_images[$forum_rows[$j]['auth_sticky']],
					'AUTH_ANNOUNCE_IMG' => $forum_auth_images[$forum_rows[$j]['auth_announce']],
					'AUTH_GLOBALANNOUNCE_IMG' => $forum_auth_images[$forum_rows[$j]['auth_globalannounce']],
					'AUTH_VOTE_IMG' => $forum_auth_images[$forum_rows[$j]['auth_vote']],
					'AUTH_POLLCREATE_IMG' => $forum_auth_images[$forum_rows[$j]['auth_pollcreate']],
					'AUTH_SUGGESTEVENT_IMG' => $forum_auth_images[$forum_rows[$j]['auth_suggest_event']],
					'AUTH_BAN_IMG' => $forum_auth_images[$forum_rows[$j]['auth_ban']],
					'AUTH_VOTEBAN_IMG' => $forum_auth_images[$forum_rows[$j]['auth_voteban']],
					'AUTH_UNBAN_IMG' => $forum_auth_images[$forum_rows[$j]['auth_greencard']],
					'AUTH_REPORT_IMG' => $forum_auth_images[$forum_rows[$j]['auth_bluecard']],
					'AUTH_UPLOAD_IMG' => $forum_auth_images[$forum_rows[$j]['auth_attachments']],
					'AUTH_DLOAD_IMG' => $forum_auth_images[$forum_rows[$j]['auth_download']],
					
					'U_VIEWFORUM' => append_sid('admin_forums.'.$phpEx.'?mode=editforum&amp;' . POST_FORUM_URL . '=' . $forum_rows[$j]['forum_id']))
				);
			}// if ... forumid == catid
		} // for ... forums
	} // for ... categories
}// if ... total_categories

$template->assign_vars(array(
	'L_FORUM_TITLE' => $lang['Auth_Control_Forum_Adv'],
	'L_FORUM_EXPLAIN' => $lang['Forum_auth_explain_overall'],
	'L_FORUM_EXPLAIN_EDIT' => $lang['Forum_auth_explain_overall_edit'],
	'L_FORUM_OVERALL_RESTORE' => $lang['Forum_auth_overall_restore'],
	'L_FORUM_OVERALL_STOP' => $lang['Forum_auth_overall_stop'],

	'AUTH_KEY' => $auth_key,
		
	'L_FORUM' => $lang['Forum'],
	'L_VIEW' => $lang['View'],
	'L_READ' => $lang['Read'],
	'L_POST' => $lang['Post'],
	'L_REPLY' => $lang['Reply'],
	'L_EDIT' => $lang['Edit'],
	'L_DELETE' => $lang['Delete'],
	'L_STICKY' => $lang['Sticky'],
	'L_ANNOUNCE' => $lang['Announce'], 
	'L_GLOBAL' => $lang['Globalannounce'],
	'L_VOTE' => $lang['Vote'], 
	'L_POLL' => $lang['Pollcreate'],
	'L_EVENT' => $lang['Suggestevent'],
	'L_BAN' => $lang['Ban'], 
	'L_VOTE_BAN' => $lang['VoteBan'], 
	'L_UNBAN' => $lang['Greencard'], 
	'L_REPORT' => $lang['Bluecard'],
	'L_UPLOAD' => $lang['Auth_attach'],
	'L_DLOAD' => $lang['Auth_download'])
);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>