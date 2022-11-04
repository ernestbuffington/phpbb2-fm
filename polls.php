<?php
/** 
*
* @package phpBB2
* @version $Id: polls.php,v 1.1.0 2004 fr Exp $
* @copyright (c) 2004 FR
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_POLL_OVERVIEW);
init_userprefs($userdata);
//
// End session management
//

//
// Define censored word matches
//
if ( !$board_config['allow_swearywords'] )
{
	$orig_word = $replacement_word = array();
	obtain_word_list($orig_word, $replacement_word);
}
else if ( !$userdata['user_allowswearywords'] )
{
	$orig_word = $replacement_word = array();
	obtain_word_list($orig_word, $replacement_word);
}

//
// Output page header
//
$page_title = $lang['Poll_overview'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'polls_body.tpl')
);

$sql = "SELECT topic_title, topic_id
	FROM " . TOPICS_TABLE . "
    WHERE topic_vote <> ''
	ORDER BY topic_time";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query post with polls', '', __LINE__, __FILE__, $sql);
}

$rc = $total_post_polls = 0;
while( $row = $db->sql_fetchrow($result) )
{
	$post_poll_rowset[] = $row;
    $total_post_polls++;
}
$db->sql_freeresult($result);

for($j = 0; $j < $total_post_polls; $j++)
{
	$sql = "SELECT vd.vote_id, vd.vote_text, vd.vote_start, vd.vote_length, vr.vote_option_id, vr.vote_option_text, vr.vote_result
		FROM " . VOTE_DESC_TABLE . " vd, " . VOTE_RESULTS_TABLE . " vr
		WHERE vd.topic_id = " . $post_poll_rowset[$j]['topic_id'] . " 
			AND vr.vote_id = vd.vote_id
		ORDER BY vr.vote_option_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Could not obtain vote data for this topic", '', __LINE__, __FILE__, $sql);
	}
	
	if ( $vote_info = $db->sql_fetchrowset($result) )
	{
		$db->sql_freeresult($result);
	
		$vote_options = sizeof($vote_info);
		$vote_id = $vote_info[0]['vote_id'];
		$vote_title = $vote_info[0]['vote_text'];
	
		$view_result = $vote_results_sum = 0;
	
		for($i = 0; $i < $vote_options; $i++)
		{
			$vote_results_sum += $vote_info[$i]['vote_result'];
		}
	
	    $vote_graphic = 0;
		$vote_graphic_max = sizeof($images['voting_graphic']);
	
		$row_class = ( !($rc % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('post_poll',array(
			'ROW_CLASS' => $row_class,
			'POLL_QUESTION' => $vote_title,
	        'L_TOTAL_VOTES' => $lang['Total_votes'],
			'TOTAL_VOTES' => $vote_results_sum,
			'S_HIDDEN_FIELDS' => $s_hidden_fields,
			'TOPIC_TITLE' => $post_poll_rowset[$j]['topic_title'],
			'U_TOPIC'=> append_sid('viewtopic.'.$phpEx.'?' . POST_TOPIC_URL . '=' . $post_poll_rowset[$j]['topic_id']))
		);
	
		for($i = 0; $i < $vote_options; $i++)
		{
			$vote_percent = ( $vote_results_sum > 0 ) ? $vote_info[$i]['vote_result'] / $vote_results_sum : 0;
			$vote_graphic_length = round($vote_percent * $board_config['vote_graphic_length']);
	
			$vote_graphic_img = $images['voting_graphic'][$vote_graphic];
			$vote_graphic = ($vote_graphic < $vote_graphic_max - 1) ? $vote_graphic + 1 : 0;
	
			if( !empty($orig_word) )
			{
				$vote_info[$i]['vote_option_text'] = preg_replace($orig_word, $replacement_word, $vote_info[$i]['vote_option_text']);
			}
	
			$template->assign_block_vars('post_poll.poll_option', array(
   	     		'POLL_OPTION_CAPTION' => $vote_info[$i]['vote_option_text'],
				'POLL_OPTION_RESULT' => $vote_info[$i]['vote_result'],
				'POLL_OPTION_PERCENT' => sprintf('%.1d%%', ($vote_percent * 100)),	

				'POLL_OPTION_IMG' => $vote_graphic_img,
				'POLL_OPTION_IMG_WIDTH' => $vote_graphic_length)
			);
		}
		$rc++;
	}
}

$template->assign_vars(array(
	'L_OVERVIEW' => $lang['poll_overview'])
);

//
// Force password update
//
if ($board_config['password_update_days'])
{
	include($phpbb_root_path . 'includes/update_password.'.$phpEx);
}

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>