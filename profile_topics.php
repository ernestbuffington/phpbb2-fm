<?php 
/** 
*
* @package phpBB2
* @version $Id: profile_topics.php,v 1.99.2.3 2004/07/11 16:46:15 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Start session management
// 
$userdata = session_pagestart($user_ip, PAGE_PROFILE); 
init_userprefs($userdata); 
//
// End session management
//

$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$start = ($start < 0) ? 0 : $start;

if (!$userdata['session_logged_in'])
{
    $redirect = ( isset($start) ) ? "?start=$start" : '';
    redirect(append_sid("login.$phpEx?redirect=profile_topics.$phpEx" . $redirect, true));
    exit; 
}


//
// Generate the page
//
$page_title = $lang['Your_topics'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx); 

$template->set_filenames(array( 
	'body' => 'profile_topics_body.tpl') 
); 
make_jumpbox('viewforum.'.$phpEx);

$template->assign_vars(array(
    'L_TOPIC_TITLE' => $page_title,
    'L_DATE' => $lang['Topic_started'])
);

$sql = "SELECT COUNT(*) AS topic_count 
	FROM " . TOPICS_TABLE . " 
	WHERE topic_poster = " . $userdata['user_id'];
if ( !($result = $db->sql_query($sql)) )
{
 	message_die(GENERAL_ERROR, 'Could not obtain total topic information', '', __LINE__, __FILE__, $sql);
}
$row = $db->sql_fetchrow($result);

$topic_count = ($row['topic_count']) ? $row['topic_count'] : 0;

$db->sql_freeresult($result);

if ($topic_count)
{      
	$sql = "SELECT t.*, f.forum_name
		FROM " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f
		WHERE t.topic_poster = " . $userdata['user_id'] . " 
            AND f.forum_id = t.forum_id 
		ORDER BY t.topic_time DESC
	    LIMIT $start, " . $board_config['topics_per_page']; 
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain topic information', '', __LINE__, __FILE__, $sql);
	}
	$topics_rows = $db->sql_fetchrowset($result);

	// has the user posted any topics?
	if ($topics_rows)
	{
	    for ( $i = 0; $i < sizeof($topics_rows); $i++ )
	    {
	    	$topic_title = capitalization($topics_rows[$i]['topic_title']);

	    	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
		
			$template->assign_block_vars('topics', array( 
		    	'ROW_CLASS' => $row_class, 
		    	'TOPIC_TITLE' => $topic_title,
                'FORUM_NAME' => $topics_rows[$i]['forum_name'],
		    	'TOPIC_VIEWS' => ( $board_config['enable_topic_view_users'] ) ? '<a href="javascript:who_viewed(' . $topics_rows[$i]['topic_id'] . ')">' . $topics_rows[$i]['topic_views'] . '</a>' : $topics_rows[$i]['topic_views'],
		    	'TOPIC_REPLIES' => '<a href="javascript:who(' . $topics_rows[$i]['topic_id'] . ')">' . $topics_rows[$i]['topic_replies'] . '</a>',
		    	'TOPIC_TIME' => create_date($board_config['default_dateformat'], $topics_rows[$i]['topic_time'], $board_config['board_timezone']),
	    
                'U_VIEWFORUM' => append_sid("viewforum.$phpEx?" . POST_FORUM_URL . '=' . $topics_rows[$i]['forum_id']),
				'U_VIEWTOPIC' => append_sid('viewtopic.'.$phpEx.'?' . POST_TOPIC_URL . '=' . $topics_rows[$i]['topic_id']))
	 		); 
		}

	    $pagination = generate_pagination("profile_topics.$phpEx?", $topic_count, $board_config['topics_per_page'], $start);
        
	    $template->assign_vars(array(
	    	'TOTAL_TOPICS' => $topic_count,                
            'PAGINATION' => $pagination,
	    	'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $board_config['topics_per_page'] ) + 1 ), ceil( $topic_count / $board_config['topics_per_page'] )), 
	    
	    	'L_GOTO_PAGE' => $lang['Goto_page'])
	    );
	}        
	$db->sql_freeresult($result);
}
else
{
    $template->assign_block_vars('switch_no_topics', array(
    	'L_NO_TOPICS' => $lang['No_Topics'])
    );
}

include($phpbb_root_path . 'profile_menu.'.$phpEx);

$template->pparse('body'); 

include($phpbb_root_path . 'includes/page_tail.'.$phpEx); 

?>