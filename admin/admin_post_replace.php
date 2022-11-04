<?php
/** 
*
* @package admin
* @version $Id: admin_post_replace.php,v 1.1.0 2004/04/02 mosymuis Exp $
* @copyright (c) 2003 mosymuis
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if (!empty ($setmodules))
{
	$filename = basename(__FILE__);
	$module['General']['Replace_title'] = $filename;
	return;
}

//
// Load default header
//
$phpbb_root_path = './../';
require ($phpbb_root_path . 'extension.inc');
require ('./pagestart.' . $phpEx);

$str_old = isset($HTTP_POST_VARS['str_old']) ? trim(htmlspecialchars($HTTP_POST_VARS['str_old'])) : '';
$str_new = isset($HTTP_POST_VARS['str_new']) ? trim(htmlspecialchars($HTTP_POST_VARS['str_new'])) : '';

if ( $HTTP_POST_VARS['submit'] && !empty($str_old) && $str_old != $str_new )
{
	$template->assign_block_vars('switch_forum_sent', array());

	$sql = "SELECT f.forum_id, f.forum_name, t.topic_id, t.topic_title, p.post_id, p.post_time, pt.post_text, u.user_id, u.username, u.user_level
		FROM " . FORUMS_TABLE . " f, " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p, " . POSTS_TEXT_TABLE . " pt, " . USERS_TABLE . " u
		WHERE post_text LIKE '%" . $str_old . "%'
			AND p.post_id = pt.post_id
			AND p.topic_id = t.topic_id
			AND p.forum_id = f.forum_id
			AND p.poster_id = u.user_id
		ORDER BY pt.post_id DESC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain posts', '', __LINE__, __FILE__, $sql);
	}

	if ( $db->sql_numrows($result) >= 1 )
	{
		for ($i = 1; $row = $db->sql_fetchrow($result); $i++)
		{
			$row_class = ( !($i % 2) ) ? $theme['td_class2'] : $theme['td_class1'];

			$row['username'] = username_level_color($row['username'], $row['user_level'], $row['user_id']);
	
			$template->assign_block_vars('switch_forum_sent.replaced', array(
				'ROW_CLASS' => $row_class,
				'NUMBER' => $i,
				'FORUM_NAME' => $row['forum_name'],
				'TOPIC_TITLE' => $row['topic_title'],
				'AUTHOR' => $row['username'],
				'POST' => create_date($userdata['user_dateformat'], $row['post_time'], $board_config['board_timezone']),

				'U_FORUM' => append_sid($phpbb_root_path . 'viewforum.'.$phpEx.'?' . POST_FORUM_URL . '=' . $row['forum_id']),
				'U_TOPIC' => append_sid($phpbb_root_path . 'viewtopic.'.$phpEx.'?' . POST_TOPIC_URL . '=' . $row['topic_id']),
				'U_AUTHOR' => append_sid($phpbb_root_path . 'profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $row['user_id']),
				'U_POST' => append_sid($phpbb_root_path . 'viewtopic.'.$phpEx.'?' . POST_POST_URL . '=' . $row['post_id']) . '#' . $row['post_id'])
			);

			$sql = "UPDATE " . POSTS_TEXT_TABLE . "
				SET post_text = '" . str_replace($str_old, $str_new, addslashes($row['post_text'])) . "'
				WHERE post_id = " . $row['post_id'];
			if ( !($result_update = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not update posts', '', __LINE__, __FILE__, $sql);
			}
		}
	} 
	else 
	{
		$template->assign_block_vars('switch_forum_sent.switch_no_results', array());
	}
}

$template->set_filenames(array(
	'body' => 'admin/post_replace_body.tpl')
);

$template->assign_vars(array(
	'S_FORM_ACTION' => append_sid('admin_post_replace.'.$phpEx),

	'L_REPLACE_TITLE' => $lang['Post_Replacer'],
	'L_REPLACE_TEXT' => $lang['Replace_text'],
	'L_STR_OLD' => $lang['Str_old'],
	'L_STR_NEW' => $lang['Str_new'],
	'L_FORUM' => $lang['Forum'],
	'L_TOPIC' => $lang['Topic'],
	'L_AUTHOR' => $lang['Author'],
	'L_LINK' => $lang['Link'],
	'L_NO_RESULTS' => $lang['No_results'],

	'REPLACED_COUNT' => ($i == 0) ? '&nbsp;' : sprintf($lang['Replaced_count'], $i -1),
	'STR_OLD' => $str_old,
	'STR_NEW' => $str_new,
	'POST_IMG' => $phpbb_root_path . $images['icon_latest_reply'])
);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>