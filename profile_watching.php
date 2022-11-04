<?php
/** 
*
* @package phpBB2
* @version $Id: profile_topics_watched.php,v 1.0.2 metclectic Exp $
* @copyright (c) 2003 Adrian Cockburn < adrian@netclectic.com >
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
    $redirect = ( isset($start) ) ? "start=$start" : '';
    redirect(append_sid("login.$phpEx?redirect=profile_topics_watched.$phpEx" . $redirect, true));
}


//
// Watched Topics
//
if ( isset($HTTP_POST_VARS['unwatch_topics']) )
{
    $topic_ids = implode(',', $HTTP_POST_VARS['unwatch_list']);
    
    $sql = "DELETE FROM " . TOPICS_WATCH_TABLE . "
	    WHERE topic_id IN(" .  $topic_ids . ") 
    		AND user_id = " . $userdata['user_id'];
    if ( !($result = $db->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, 'Could not delete topic watch information.', '', __LINE__, __FILE__, $sql);
    }
}

$sql = "SELECT COUNT(*) AS watch_count 
	FROM " . TOPICS_WATCH_TABLE . " 
	WHERE user_id = " . $userdata['user_id'];
if ( !($result = $db->sql_query($sql)) )
{
 	message_die(GENERAL_ERROR, 'Could not obtain watched topic information', '', __LINE__, __FILE__, $sql);
}
$row = $db->sql_fetchrow($result);

$watch_count = ($row['watch_count']) ? $row['watch_count'] : 0;

$db->sql_freeresult($result);

if ($watch_count)
{      
    // Grab a list of watched topics
    $sql = "SELECT w.*, t.*, p.post_time, p.poster_id, f.forum_name, first.username AS author_username, first.user_level AS author_user_level, last.username AS last_username, last.user_level AS last_user_level  
        FROM " . TOPICS_WATCH_TABLE . " w, " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p, " . FORUMS_TABLE . " f, " . USERS_TABLE . " first, " . USERS_TABLE . " last 
        WHERE t.topic_id = w.topic_id 
            AND p.post_id = t.topic_last_post_id  
            AND t.topic_poster = first.user_id
            AND p.poster_id = last.user_id
            AND f.forum_id = t.forum_id 
            AND w.user_id = " . $userdata['user_id'] . " 
        ORDER BY t.topic_last_post_id DESC 
    	LIMIT $start, " . $board_config['topics_per_page'];
	if ( !($result = $db->sql_query($sql)) )
    {
    	message_die(GENERAL_ERROR, 'Could not obtain watch topic information', '', __LINE__, __FILE__, $sql);
    }
    $watch_rows = $db->sql_fetchrowset($result);

    // are we currently watching any topics?
    if ($watch_rows)
    {
        for ( $i = 0; $i < sizeof($watch_rows); $i++ )
        {
            $last_poster = ($watch_rows[$i]['poster_id'] == ANONYMOUS) ? (($watch_rows[$i]['last_username'] != '') ? $watch_rows[$i]['last_username'] : $lang['Guest']) : '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . '='  . $watch_rows[$i]['poster_id']) . '" class="gensmall">' . username_level_color($watch_rows[$i]['last_username'], $watch_rows[$i]['last_user_level'], $watch_rows[$i]['poster_id']) . '</a>';
            $last_poster .= ' <a href="' . append_sid("viewtopic.$phpEx?"  . POST_POST_URL . '=' . $watch_rows[$i]['topic_last_post_id']) . '#' . $watch_rows[$i]['topic_last_post_id'] . '"><img src="' . $images['icon_latest_reply'] . '" alt="' . $lang['View_latest_post'] . '" title="' . $lang['View_latest_post'] . '" /></a>';
            $topic_poster = ($watch_rows[$i]['topic_poster'] == ANONYMOUS) ? (($watch_rows[$i]['author_username'] != '') ? $watch_rows[$i]['author_username'] : $lang['Guest']) : '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . '='  . $watch_rows[$i]['topic_poster']) . '" class="gensmall">' . username_level_color($watch_rows[$i]['author_username'], $watch_rows[$i]['author_user_level'], $watch_rows[$i]['topic_poster']) . '</a>';
            
            $replies = $watch_rows[$i]['topic_replies']; 
            if( ( $replies + 1 ) > $board_config['posts_per_page'] ) 
            { 
                $total_pages = ceil( ( $replies + 1 ) / $board_config['posts_per_page'] ); 
                $goto_page = ' [ <img src="' . $images['icon_gotopost'] . '" alt="' . $lang['Goto_page'] . '" title="' . $lang['Goto_page'] . '" />' . $lang['Goto_page'] . ': '; 
            
                $times = 1; 
                for($j = 0; $j < $replies + 1; $j += $board_config['posts_per_page']) 
                { 
                    $goto_page .= '<a href="' . append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=" . $watch_rows[$i]['topic_id'] . "&start=$j") . '">' . $times . '</a>'; 
                    if( $times == 1 && $total_pages > 4 ) 
                    { 
                        $goto_page .= ' ... '; 
                        $times = $total_pages - 3; 
                        $j += ( $total_pages - 4 ) * $board_config['posts_per_page']; 
                    } 
                    else if ( $times < $total_pages ) 
                    { 
                    $goto_page .= ', '; 
                    } 
                    $times++; 
                } 
                $goto_page .= ' ] '; 
            } 
            else 
            { 
                $goto_page = ''; 
            }            

	    	$topic_title = capitalization($watch_rows[$i]['topic_title']);

        	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
    
            $template->assign_block_vars('topic_watch_row', array(
                'ROW_CLASS' => $row_class,
                'S_WATCHED_TOPIC_ID' => $watch_rows[$i]['topic_id'],
                'S_WATCHED_TOPIC' => $topic_title,
				'S_WATCHED_TOPIC_REPLIES' => '<a href="javascript:who(' . $watch_rows[$i]['topic_id'] . ')">' . $watch_rows[$i]['topic_replies'] . '</a>',
   				'S_WATCHED_TOPIC_VIEWS' => ($board_config['enable_topic_view_users']) ? '<a href="javascript:who_viewed(' . $watch_rows[$i]['topic_id'] . ')">' . $watch_rows[$i]['topic_views'] . '</a>' : $watch_rows[$i]['topic_views'],
             	'S_WATCHED_TOPIC_START' => create_date($board_config['default_dateformat'], $watch_rows[$i]['topic_time'], $board_config['board_timezone']),
                'S_WATCHED_TOPIC_LAST' => create_date($board_config['default_dateformat'], $watch_rows[$i]['post_time'], $board_config['board_timezone']),
                'S_FORUM_TITLE' => $watch_rows[$i]['forum_name'],

                'TOPIC_POSTER' => $topic_poster,
                'LAST_POSTER' => $last_poster,
                'GOTO_PAGE' => $goto_page,
                
                'U_FORUM_LINK' => append_sid("viewforum.$phpEx?" . POST_FORUM_URL . '=' . $watch_rows[$i]['forum_id']),
                'U_WATCHED_TOPIC' => append_sid("viewtopic.$phpEx?"  . POST_TOPIC_URL . '=' . $watch_rows[$i]['topic_id']))
            );    
        }

        $pagination = generate_pagination("profile_watching.$phpEx?", $watch_count, $board_config['topics_per_page'], $start);
        
    	$template->assign_block_vars('switch_watched_topics_block', array(
	    	'TOTAL_TOPICS' => $watch_count,                
    		'PAGINATION' => $pagination,
    		'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $board_config['topics_per_page'] ) + 1 ), ceil( $watch_count / $board_config['topics_per_page'] )), 
    
    		'L_GOTO_PAGE' => $lang['Goto_page'])
    	);
    }        
   	$db->sql_freeresult($result);
}
else
{
    $template->assign_block_vars('switch_no_watched_topics', array(
    	'L_NO_WATCHED_TOPICS' => $lang['No_Watched_Topics'])
    );
}


//
// Watched Forums
//
$watch_count = 0;

if ( isset($HTTP_POST_VARS['unwatch_forums']) )
{
    $forum_ids = implode(',', $HTTP_POST_VARS['unwatch_list']);
    
    $sql = "DELETE FROM " . FORUMS_WATCH_TABLE . "
	    WHERE forum_id IN(" .  $forum_ids . ") 
    		AND user_id = " . $userdata['user_id'];
    if ( !($result = $db->sql_query($sql)) )
    {
        message_die(GENERAL_ERROR, 'Could not delete forum watch information.', '', __LINE__, __FILE__, $sql);
    }
}

$sql = "SELECT COUNT(*) AS watch_count 
	FROM " . FORUMS_WATCH_TABLE . " 
	WHERE user_id = " . $userdata['user_id'];
if ( !($result = $db->sql_query($sql)) )
{
 	message_die(GENERAL_ERROR, 'Could not obtain watched forum information', '', __LINE__, __FILE__, $sql);
}
$row = $db->sql_fetchrow($result);

$watch_count = ($row['watch_count']) ? $row['watch_count'] : 0;

$db->sql_freeresult($result);

if ($watch_count)
{      
    // Grab a list of watched forums
	$sql = "SELECT f.*, p.post_time, p.post_username, u.username, u.user_id, u.user_level, t.topic_title, t.title_compl_infos, t.title_pos, t.title_compl_color
		FROM (((( " . FORUMS_TABLE . " f
			LEFT JOIN " . POSTS_TABLE . " p ON p.post_id = f.forum_last_post_id )
			LEFT JOIN " . USERS_TABLE . " u ON u.user_id = p.poster_id )
			LEFT JOIN " . TOPICS_TABLE . " t ON t.topic_id = p.topic_id )
			LEFT JOIN " . FORUMS_WATCH_TABLE . " w ON w.forum_id = f.forum_id )
		WHERE w.user_id = " . $userdata['user_id'] . "
		ORDER BY f.cat_id, f.forum_order
    	LIMIT $start, " . $board_config['topics_per_page'];
	if ( !($result = $db->sql_query($sql)) )
    {
    	message_die(GENERAL_ERROR, 'Could not obtain watch topic information', '', __LINE__, __FILE__, $sql);
    }
    $watch_rows = $db->sql_fetchrowset($result);

    // are we currently watching any topics?
    if ($watch_rows)
    {       
        for ( $i = 0; $i < sizeof($watch_rows); $i++ )
        {
   			if ( $watch_rows[$i]['forum_last_post_id'] )
			{
 				$topic_title = $topic_title_alt = $watch_rows[$i]['topic_title'];
						
				// Censor topic title
				if ( !empty($orig_word) )
				{
					$topic_title = preg_replace($orig_word, $replacement_word, $topic_title);
					$topic_title_alt = preg_replace($orig_word, $replacement_word, $topic_title_alt);
				}
								
				if (strlen($topic_title) > 33) 
				{
					$topic_title = substr($topic_title, 0, 30) . '...';
				}
									
				$topic_title = capitalization($topic_title);
				$topic_title_alt = capitalization($topic_title_alt);
								
				// Add quick topic titles
				if ($board_config['enable_quick_titles'])
				{
					if ( $watch_rows[$i]['title_pos'] )
					{
						$topic_title = (empty($watch_rows[$i]['title_compl_infos'])) ? $topic_title : $topic_title . '<br /><span style="color: #' . $watch_rows[$i]['title_compl_color'] . '">' . $watch_rows[$i]['title_compl_infos'] . '</span>';
					}
					else
					{
						$topic_title = (empty($watch_rows[$i]['title_compl_infos'])) ? $topic_title : '<span style="color: #' . $watch_rows[$i]['title_compl_color'] . '">' . $watch_rows[$i]['title_compl_infos'] . '</span><br />' . $topic_title;
					}
				}
																
				$last_post_time = create_date($board_config['default_dateformat'], $watch_rows[$i]['post_time'], $board_config['board_timezone']);
							
				$last_post = '';
				$last_post .= ($watch_rows[$i]['index_lasttitle']) ? '<b><a href="' . append_sid("viewtopic.$phpEx?"  . POST_POST_URL . '=' . $watch_rows[$i]['forum_last_post_id']) . '&amp;no=1#' . $watch_rows[$i]['forum_last_post_id'] . '" title="' . $topic_title_alt . '" class="gensmall">' . str_replace("\'", "''", $topic_title) . '</a></b><br />' : '';
				$last_post .= $last_post_time . '<br />'; 
				$last_post .= ( $watch_rows[$i]['user_id'] == ANONYMOUS ) ? ( ($watch_rows[$i]['post_username'] != '' ) ? $watch_rows[$i]['post_username'] . ' ' : $lang['Guest'] . ' ' ) : '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . '='  . $watch_rows[$i]['user_id']) . '" class="gensmall">' . $watch_rows[$i]['username'] . '</a> <a href="' . append_sid("viewtopic.$phpEx?"  . POST_POST_URL . '=' . $watch_rows[$i]['forum_last_post_id']) . '&no=1#' . $watch_rows[$i]['forum_last_post_id'] . '"><img src="' . $images['icon_latest_reply'] . '" alt="' . $lang['View_latest_post'] . '" title="' . $lang['View_latest_post'] . '"></a>';
			}
			else
			{
				$last_post = $lang['No_Posts'];
			}
        	
        	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
    
            $template->assign_block_vars('forum_watch_row', array(
                'ROW_CLASS' => $row_class,
                'S_WATCHED_FORUM_ID' => $watch_rows[$i]['forum_id'],
        		'S_WATCHED_FORUM_POSTS' => $watch_rows[$i]['forum_posts'],
				'S_WATCHED_FORUM_TOPICS' => $watch_rows[$i]['forum_topics'],
   				'S_WATCHED_FORUM_VIEWS' => $watch_rows[$i]['forum_views'],
                'S_WATCHED_FORUM_LAST' => $last_post,
                'S_FORUM_TITLE' => $watch_rows[$i]['forum_name'],

                'TOPIC_POSTER' => $topic_poster,
                'LAST_POSTER' => $last_poster,
                'GOTO_PAGE' => $goto_page,
                
                'U_FORUM_LINK' => append_sid("viewforum.$phpEx?" . POST_FORUM_URL . '=' . $watch_rows[$i]['forum_id']),
                'U_WATCHED_TOPIC' => append_sid("viewtopic.$phpEx?"  . POST_TOPIC_URL . '=' . $watch_rows[$i]['topic_id']))
            );    
        }

        $pagination = generate_pagination("profile_watching.$phpEx?", $watch_count, $board_config['topics_per_page'], $start);
        
    	$template->assign_block_vars('switch_watched_forums_block', array(
	    	'TOTAL_FORUMS' => $watch_count,                
    		'PAGINATION' => $pagination,
    		'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $board_config['topics_per_page'] ) + 1 ), ceil( $watch_count / $board_config['topics_per_page'] )), 
    
    		'L_GOTO_PAGE' => $lang['Goto_page'])
    	);
    }        
   	$db->sql_freeresult($result);
}
else
{
    $template->assign_block_vars('switch_no_watched_forums', array(
    	'L_NO_WATCHED_FORUMS' => $lang['No_Watched_Forums'])
    );
}


//
// Generate the page
//
$page_title = $lang['Watching'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
    'body' => 'profile_watching_body.tpl')
);
make_jumpbox('viewforum.'.$phpEx);

$template->assign_vars(array( 
 	'L_TOPICS' => $lang['Topics'],
	'L_POSTS2' => $lang['Posts'],
	'L_WITHIN' => $lang['Within'],
	'L_ON' => $lang['On'],
	'L_STOP_WATCH_FORUMS' => $lang['Stop_watching_forum'],
	'L_STOP_WATCH' => $lang['Stop_watching_topic'],
    'L_MARK_ALL' => $lang['Mark_all'],
    'L_UNMARK_ALL' => $lang['Unmark_all'],
    'S_FORM_ACTION' => append_sid('profile_watching.'.$phpEx))
);

include($phpbb_root_path . 'profile_menu.'.$phpEx);

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>