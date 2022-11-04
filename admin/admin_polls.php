<?php 
/** 
*
* @package admin
* @version $Id: admin_polls.php,v 1.40.2.10 2003/01/05 02:36:00 psotfx Exp $
* @copyright (c) 2002 GAUTHIER Julien, BOBE
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1); 

if( !empty($setmodules) ) 
{ 
	$file = basename(__FILE__); 
	$module['Forums']['Vote_manager'] = $file; 
	return; 
} 

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc'); 
require('./pagestart.' . $phpEx);

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
	$mode = '';
}

if ( $mode != '' )
{
	switch( $mode )
	{
		case 'view': 
			$id = ( !empty($HTTP_POST_VARS['id']) ) ? $HTTP_POST_VARS['id'] : $HTTP_GET_VARS['id'];
			$id = intval($id);
	
			$sql = "SELECT * 
				FROM " . VOTE_DESC_TABLE . " AS v, " . TOPICS_TABLE . " AS t 
				WHERE t.topic_id = v.topic_id 
					AND v.vote_id = " . $id; 	
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not obtain polls', '', __LINE__, __FILE__, $sql);
			}
		
			if ( $poll_data = $db->sql_fetchrow($result) )
			{		
				$template->assign_block_vars('sondagerow',array( 
					'ROW_CLASS' => 'row1',
					'ID' => $poll_data['vote_id'], 
					'TITLE_TOPIC' => $poll_data['topic_title'], 
					'TITLE_SONDAGE' => $poll_data['vote_text'], 
					'T_ID' => append_sid($phpbb_root_path . 'viewtopic.'.$phpEx.'?' . POST_TOPIC_URL . '=' . $poll_data['topic_id']), 
					'SONDAGE_DATE' => create_date($board_config['default_dateformat'], $poll_data['vote_start'], $board_config['board_timezone']),
					'U_DELETE' => append_sid('admin_polls.'.$phpEx.'?mode=delete&amp;id=' . $poll_data['vote_id'] . '&amp;' . POST_TOPIC_URL . '=' . $poll_data['topic_id']),
					'U_VIEW' => append_sid('admin_polls.'.$phpEx.'?mode=view&amp;id=' . $poll_data['vote_id']))
				); 
			}
				
			$sql = "SELECT * 
				FROM " . VOTE_USERS_TABLE . " AS v, " . USERS_TABLE . " AS u 
				WHERE v.vote_id = " . $id . "
					AND v.vote_user_id = u.user_id"; 
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not obtain polls voters.', '', __LINE__, __FILE__, $sql);
			}
		
			while ( $row = $db->sql_fetchrow($result) )
			{ 
				$liste_votants .= '<a href="' . $phpbb_root_path . 'profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $row['user_id'] . '" target="_blank" class="genmed">' . username_level_color($row['username'], $row['user_level'], $row['user_id']) . '</a>, '; 
			} 
			$db->sql_freeresult($result);
			
			$template->assign_block_vars('details',array( 
				'LISTE_VOTANTS' => $liste_votants)
			); 
			
			$sql = "SELECT * 
				FROM " . VOTE_RESULTS_TABLE . " 
				WHERE vote_id = " . $id . " 
				ORDER BY vote_option_id"; 
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not obtain polls results.', '', __LINE__, __FILE__, $sql);
			}
		
			while ( $row = $db->sql_fetchrow($result) )
			{ 	
				$template->assign_block_vars('details.answerrow',array( 
					'ID_OPTION' => $row['vote_option_id'], 
					'OPTION' => $row['vote_option_text'], 
					'NB_ANSWER' => $row['vote_result'])
				);  
			} 
			$db->sql_freeresult($result);

			break;
	
		case 'delete': 
			$id = ( !empty($HTTP_POST_VARS['id']) ) ? $HTTP_POST_VARS['id'] : $HTTP_GET_VARS['id'];
			$id = intval($id);
			$t = ( !empty($HTTP_POST_VARS['t']) ) ? $HTTP_POST_VARS['t'] : $HTTP_GET_VARS['t'];
			$t = intval($t);
		
			$sql = "DELETE FROM " . VOTE_DESC_TABLE . " 
				WHERE vote_id = " . $id; 
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete poll.', '', __LINE__, __FILE__, $sql);
			}
		
			$sql = "DELETE FROM " . VOTE_RESULTS_TABLE . " 
				WHERE vote_id = " . $id; 
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete poll votes.', '', __LINE__, __FILE__, $sql);
			}
					
			$sql = "DELETE FROM " . VOTE_VOTERS_TABLE . " 
				WHERE vote_id = " . $id; 
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not delete poll voters.', '', __LINE__, __FILE__, $sql);
			}
		
			$sql = "UPDATE " . TOPICS_TABLE . " 
				SET topic_vote = 0 
				WHERE topic_id = " . $t; 
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not update topic type.', '', __LINE__, __FILE__, $sql);
			}
				
			$message = $lang['Poll_delete'] . '<br /><br />' . sprintf($lang['Click_return_poll_management'], '<a href="' . append_sid('admin_polls.'.$phpEx) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.'.$phpEx.'?pane=right') . '">', '</a>'); 
				
			message_die(GENERAL_MESSAGE, $message); 
			
			break;	
	}
}
else
{	
	$sql = "SELECT * 
		FROM " . VOTE_DESC_TABLE . " AS v, " . TOPICS_TABLE . " AS t 
		WHERE v.topic_id = t.topic_id 
		ORDER BY vote_start"; 
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain polls', '', __LINE__, __FILE__, $sql);
	}
		
	if ( $result )
	{ 	
		$i = 0;
		while ( $row = $db->sql_fetchrow($result) )
		{ 
			$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars('sondagerow',array( 
				'ROW_CLASS' => $row_class,
				'ID' => $row['vote_id'], 
				'TITLE_TOPIC' => $row['topic_title'], 
				'TITLE_SONDAGE' => $row['vote_text'], 
				'T_ID' => append_sid($phpbb_root_path . 'viewtopic.'.$phpEx.'?t=' . $row['topic_id']), 
				'SONDAGE_DATE' => create_date($board_config['default_dateformat'], $row['vote_start'], $board_config['board_timezone']),
				'U_DELETE' => append_sid('admin_polls.'.$phpEx.'?mode=delete&amp;id=' . $row['vote_id'] . '&amp;' . POST_TOPIC_URL . '=' . $row['topic_id']),
				'U_VIEW' => append_sid('admin_polls.'.$phpEx.'?mode=view&amp;id=' . $row['vote_id']))		
			); 
			$i++;
		} 
		$db->sql_freeresult($result);
	} 
	else 
	{ 
		$template->assign_block_vars('empty',array()); 
	} 
}
	
$template->set_filenames(array( 
	'body' => 'admin/polls_body.tpl')
); 

$template->assign_vars(array(
	'L_POLL_TITLE' => $lang['Vote_manager'],
	'L_POLL_EXPLAIN' => $lang['Poll_explain'],
	'L_NO' => $lang['Vote_id'],
	'L_TOPIC' => $lang['Poll_topic'],
	'L_POLL' => $lang['Poll'],
	'L_TITLE' => $lang['Poll_name'],
	'L_DATE' => $lang['Start_date'],
	'L_ACTION' => $lang['Action'],

	'VIEW' => '<img src="' . $phpbb_root_path . $images['acp_edit'] . '" alt="' . $lang['View'] . '" title="' . $lang['View'] . '" />',
	'DELETE' => '<img src="' . $phpbb_root_path . $images['acp_delete'] . '" alt="' . $lang['Delete'] . '" title="' . $lang['Delete'] . '" />',

	'L_NO_POLLS' => $lang['No_polls'],
	'L_POSS_RESPONSES' => $lang['Poss_responses'],
	'L_NO_RESPONSES' => $lang['No_responses'],
	'L_USERS_VOTED' => $lang['Users_voted'])
);
		
$template->pparse('body'); 
	
include('./page_footer_admin.'.$phpEx);
	
?>