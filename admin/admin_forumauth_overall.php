<?php
/** 
*
* @package admin
* @version $Id: admin_forumauth_overall.php,v 1.0.2 2002/8/08, 19:41:51 hnt Exp $
* @copyright (c) 2002 Smartor
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

$forum_id = 1; // You could change this value unless forum ID 3 did not exist in your board

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Forums']['Permissions_overall'] = $filename . '?' . POST_FORUM_URL . "=$forum_id";

	return;
}

//
// Load default header
//
$no_page_header = TRUE;
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
	'auth_bluecard' => $lang['Bluecard']);

$forum_auth_levels = array('ALL', 'REG', 'PRIVATE', 'MOD', 'ADMIN');
$forum_auth_const = array(AUTH_ALL, AUTH_REG, AUTH_ACL, AUTH_MOD, AUTH_ADMIN);

attach_setup_forum_auth($simple_auth_ary, $forum_auth_fields, $field_names);

if( isset($HTTP_GET_VARS['adv']) )
{
	$adv = intval($HTTP_GET_VARS['adv']);
}
else
{
	unset($adv);
}

//
// Start program proper
//
if( isset($HTTP_POST_VARS['submit']) )
{
	$sql = '';

	if(!empty($forum_id))
	{
		if(isset($HTTP_POST_VARS['simpleauth']))
		{
			$simple_ary = $simple_auth_ary[$HTTP_POST_VARS['simpleauth']];

			for($i = 0; $i < count($simple_ary); $i++)
			{
				$sql .= ( ( $sql != '' ) ? ', ' : '' ) . $forum_auth_fields[$i] . ' = ' . $simple_ary[$i];
			}

			$sql = "UPDATE " . FORUMS_TABLE . " SET $sql";
		}
		else
		{
			for($i = 0; $i < count($forum_auth_fields); $i++)
			{
				if ($forum_auth_fields[$i] == 'auth_suggest_event' && !mycal_forum_check($forum_id)) 
					continue;

				$value = $HTTP_POST_VARS[$forum_auth_fields[$i]];

				if ( $forum_auth_fields[$i] == 'auth_vote' )
				{
					if ( $HTTP_POST_VARS['auth_vote'] == AUTH_ALL )
					{
						$value = AUTH_REG;
					}
				}

				$sql .= ( ( $sql != '' ) ? ', ' : '' ) .$forum_auth_fields[$i] . ' = ' . $value;
			}

			$sql = "UPDATE " . FORUMS_TABLE . " SET $sql";
		}

		$sql .= " WHERE forum_id > 0";
		
		if ( $sql != '' )
		{
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not update auth table', '', __LINE__, __FILE__, $sql);
			}
		}

		$forum_sql = '';
		$adv = 0;
	}

	$template->assign_vars(array(
		'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("admin_forumauth_overall.$phpEx?" . POST_FORUM_URL . "=$forum_id") . '">')
	);
	$message = $lang['Forum_auth_updated'] . '<br /><br />' . sprintf($lang['Click_return_forumauth'],  '<a href="' . append_sid("admin_forumauth_overall.$phpEx?" . POST_FORUM_URL . "=$forum_id") . '">', "</a>");
	message_die(GENERAL_MESSAGE, $message);

} // End of submit

//
// Get required information, either all forums if
// no id was specified or just the requsted if it
// was
//
$sql = "SELECT f.*
	FROM " . FORUMS_TABLE . " f, " . CATEGORIES_TABLE . " c
	WHERE c.cat_id = f.cat_id
	AND forum_id = $forum_id
	ORDER BY c.cat_order ASC, f.forum_order ASC";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, "Couldn't obtain forum list", "", __LINE__, __FILE__, $sql);
}

$forum_rows = $db->sql_fetchrowset($result);
$db->sql_freeresult($result);

if( empty($forum_id) )
{
	// Nothing... hehehehe - Smartor
}
else
{
	//
	// Output the authorisation details if an id was
	// specified
	//
	$template->set_filenames(array(
		'body' => 'admin/auth_forum_body.tpl')
	);

	$forum_name = 'Overall';

	@reset($simple_auth_ary);
	while( list($key, $auth_levels) = each($simple_auth_ary))
	{
		$matched = 1;
		for($k = 0; $k < count($auth_levels); $k++)
		{
			$matched_type = $key;

			if ( $forum_rows[0][$forum_auth_fields[$k]] != $auth_levels[$k] )
			{
				$matched = 0;
			}
		}

		if ( $matched )
		{
			break;
		}
	}

	//
	// If we didn't get a match above then we
	// automatically switch into 'advanced' mode
	//
	if ( !isset($adv) && !$matched )
	{
		$adv = 1;
	}

	$s_column_span == 0;

	if ( empty($adv) )
	{
		$simple_auth = '<select name="simpleauth">';

		for($j = 0; $j < count($simple_auth_types); $j++)
		{
			$selected = ( $matched_type == $j ) ? ' selected="selected"' : '';
			$simple_auth .= '<option value="' . $j . '"' . $selected . '>' . $simple_auth_types[$j] . '</option>';
		}

		$simple_auth .= '</select>';

		$template->assign_block_vars('forum_auth_titles', array(
			'CELL_TITLE' => $lang['Simple_mode'])
		);
		$template->assign_block_vars('forum_auth_data', array(
			'S_AUTH_LEVELS_SELECT' => $simple_auth)
		);

		$s_column_span++;
	}
	else
	{
		//
		// Output values of individual
		// fields
		//
		for($j = 0; $j < count($forum_auth_fields); $j++)
		{
			if ($forum_auth_fields[$j] == 'auth_suggest_event' && !mycal_forum_check($forum_id)) 
				continue;

			$custom_auth[$j] = '&nbsp;<select name="' . $forum_auth_fields[$j] . '">';

			for($k = 0; $k < count($forum_auth_levels); $k++)
			{
				$selected = ( $forum_rows[0][$forum_auth_fields[$j]] == $forum_auth_const[$k] ) ? ' selected="selected"' : '';
				$custom_auth[$j] .= '<option value="' . $forum_auth_const[$k] . '"' . $selected . '>' . $lang['Forum_' . $forum_auth_levels[$k]] . '</option>';
			}
			$custom_auth[$j] .= '</select>&nbsp;';

			$cell_title = $field_names[$forum_auth_fields[$j]];

			$template->assign_block_vars('forum_auth_titles', array(
				'CELL_TITLE' => $cell_title)
			);
			$template->assign_block_vars('forum_auth_data', array(
				'S_AUTH_LEVELS_SELECT' => $custom_auth[$j])
			);

			$s_column_span++;
		}
	}

	$adv_mode = ( empty($adv) ) ? '1' : '0';
	$switch_mode = append_sid("admin_forumauth_overall.$phpEx?" . POST_FORUM_URL . "=" . $forum_id . "&adv=". $adv_mode);
	$switch_mode_text = ( empty($adv) ) ? $lang['Advanced_mode'] : $lang['Simple_mode'];
	$u_switch_mode = '<a href="' . $switch_mode . '">' . $switch_mode_text . '</a>';

	$s_hidden_fields = '<input type="hidden" name="' . POST_FORUM_URL . '" value="' . $forum_id . '">';

	$template->assign_vars(array(
		'FORUM_NAME' => $forum_name,

		'L_FORUM' => $lang['Forum'], 
		'L_AUTH_TITLE' => $lang['Auth_Control_Forum'],
		'L_AUTH_EXPLAIN' => $lang['Forum_auth_explain'],
		'L_SUBMIT' => $lang['Submit'],
		'L_RESET' => $lang['Reset'],

		'U_SWITCH_MODE' => $u_switch_mode,

		'S_FORUMAUTH_ACTION' => append_sid("admin_forumauth_overall.$phpEx"),
		'S_COLUMN_SPAN' => $s_column_span,
		'S_HIDDEN_FIELDS' => $s_hidden_fields)
	);

}

include('./page_header_admin.'.$phpEx);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>