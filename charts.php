<?php
/** 
*
* @package phpBB2
* @version $Id: charts.php,v 2.0.7 2003/03/15 10:16:56 dzidzius Exp $
* @copyright (c) 2003 dzidzius
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
$userdata = session_pagestart($user_ip, PAGE_CHARTS);
init_userprefs($userdata);
//
// End session management
//

// include language file
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_charts.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_charts.' . $phpEx);


if (isset($HTTP_GET_VARS['action']) || isset($HTTP_POST_VARS['action']))
{
	$mode = ($HTTP_GET_VARS['action']) ? htmlspecialchars($HTTP_GET_VARS['action']) : htmlspecialchars($HTTP_POST_VARS['action']);
}
else
{
	//
	// These could be entered via a form button
	//
	if (isset($HTTP_POST_VARS['save']))
	{
		$mode = 'save';
	}
	else
	{
		$mode = 'list';
	}
}

if ($mode != '')
{
	if ($mode == 'list' || $mode == 'all_list')
    {
    	$page_title = $lang['Charts'];
        include($phpbb_root_path . 'includes/page_header.'.$phpEx);
        $list_type = ( $mode == 'list' ) ? 'all_list' : 'list';

		$template->set_filenames(array(
			'body' => 'charts_list_body.tpl')
		);
		make_jumpbox('viewforum.'.$phpEx); 
        
        $template->assign_vars(array(
            'L_WEEK' => $lang['Chart_Week'],
            'L_CHART_NAME' => $lang['Charts'],
            'L_THIS_WEEK' => $lang['Chart_Curr_Pos'],
            'L_LAST_WEEK' => $lang['Chart_Last_Pos'],
            'L_BEST_POS' => $lang['Chart_Best_Pos'],
            'L_TITLE' => $lang['Chart_Title'],
            'L_ARTIST' => $lang['Chart_Artist'],
            'L_ALBUM' => $lang['Chart_Album'],
            'L_LABEL' => $lang['Chart_Label'],
            'L_CAT_NO' => $lang['Chart_Cat_No'],
            'L_WEBSITE' => $lang['Website'],
            'L_ADDED' => $lang['Chart_Added_By'],
            'L_RATE' => $lang['Chart_Rate'],
            'L_SHOW_LIST' => ( $mode == 'list' ) ? $lang['All_Charts'] : $lang['Top_Ten'],
        	
        	'V_WEEK_NUM' => $board_config['charts_week_num'],
            
			'ADD_IMG' => '<img src="' . $images['icon_add_song'] . '" alt="' . $lang['New_Song'] . '" title="' . $lang['New_Song'] . '" />',
            'U_SHOW_LIST' => append_sid('charts.'.$phpEx.'?action=' . $list_type),
            'U_ADD_CHART' => append_sid('charts.'.$phpEx.'?action=new'))
		);
        
        $v_num = ( $mode == 'list' ) ? ' LIMIT 0, 10' : '';
        
        $sql = "SELECT c.*, u.user_id, u.username, u.user_level 
        	FROM " . CHARTS_TABLE . " c, " . USERS_TABLE . " u 
        	WHERE c.chart_poster_id = u.user_id 
        	ORDER BY (chart_hot-chart_not) DESC, c.chart_artist 
        	" . $v_num;
		if( !$result = $db->sql_query($sql) )
        {
        	message_die(GENERAL_ERROR, $lang['Chart_Sql_Error'], $lang['Error'], __LINE__, __FILE__, $sql);
		}
		
		$i = 0;
		while($row = $db->sql_fetchrow($result))
        { 
		 	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
       
	        $template->assign_block_vars('chart_block', array(
	        	'ROW_CLASS' => $row_class,
  	        	'CHART_SONG' => $row['chart_song_name'],
		        'CHART_ARTIST' => $row['chart_artist'],
  		        'CHART_ALBUM' => $row['chart_album'],
  		        'CHART_LABEL' => $row['chart_label'],
  		        'CHART_CAT_NO' => $row['chart_catno'],
  		        'CHART_WEBSITE' => $row['chart_website'],
		        'CHART_POSTER' => username_level_color($row['username'], $row['user_level'], $row['user_id']),
                'CHART_LAST' => ( $row['chart_last_pos'] == 0 ) ? $lang['Chart_New_Song'] : $row['chart_last_pos'],
                'CHART_BEST' => $row['chart_best_pos'],
                'CHART_HOT_NOT' => '(' . $lang['Chart_Hot'] . ': ' . $row['chart_hot'] . ' - ' . $lang['Chart_Not'] . ': ' . $row['chart_not'] . ')',
                
                'HOT_IMG' => '<img src="' . $images['icon_hot'] . '" alt="' . $lang['Chart_Hot'] . '" title="' . $lang['Chart_Hot'] . '" />',
                'NOT_IMG' => '<img src="' . $images['icon_not'] . '" alt="' . $lang['Chart_Not'] . '" title="' . $lang['Chart_Not'] . '" />',
                
                'U_POSTER' => append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $row['user_id']),
                'U_CHART_HOT' => append_sid('charts.'.$phpEx.'?action=vote&amp;rate=1&amp;id=' . $row['chart_id']),
                'U_CHART_NOT' => append_sid('charts.'.$phpEx.'?action=vote&amp;rate=2&amp;id=' . $row['chart_id']),
		        'CHART_POS' => $i + 1)
			);
			$i++;
		}
        $db->sql_freeresult($result);
    
	    //
		// Force password update
		//
		if ($board_config['password_update_days'])
		{
			include($phpbb_root_path . 'includes/update_password.'.$phpEx);
		}
    
        $template->pparse('body');
        
        include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
	}
    else if ($mode == 'vote')
    {
		if ( !$userdata['session_logged_in'] ) 
		{ 
			redirect("login.".$phpEx."?redirect=charts.".$phpEx); 
			exit; 
		} 
	
	  	$page_title = $lang['Charts'];
        include($phpbb_root_path . 'includes/page_header.'.$phpEx);

        if( isset($HTTP_POST_VARS['id']) || isset($HTTP_GET_VARS['id']) )
        {
        	$chart_id = ( isset($HTTP_POST_VARS['id']) ) ? intval($HTTP_POST_VARS['id']) : intval($HTTP_GET_VARS['id']);
        }
		else
		{
			$chart_id = 0;
		}
		
		if ( $chart_id )
		{
        	if( isset($HTTP_POST_VARS['rate']) || isset($HTTP_GET_VARS['rate']) )
            {
            	$rate = ( isset($HTTP_POST_VARS['rate']) ) ? intval($HTTP_POST_VARS['rate']) : intval($HTTP_GET_VARS['rate']);
            }
            else
            {
            	$rate = 0;
            }
            
            if ( $rate == 1 || $rate == 2 )
            {
                $sql = "SELECT COUNT(*) AS counter 
                	FROM " . CHARTS_VOTERS_TABLE . " 
                	WHERE vote_chart_id = " . $chart_id . "
                		AND vote_user_id = " . $userdata['user_id'];
				if( !$result = $db->sql_query($sql) )
                {
                	message_die(GENERAL_ERROR, $lang['Chart_Sql_Error'], $lang['Error'], __LINE__, __FILE__, $sql);
                }
                $row = $db->sql_fetchrow($result);
                $db->sql_freeresult($result);
                    
                if (!$row['counter'])
                {
                	$sql = "INSERT INTO " .CHARTS_VOTERS_TABLE . " (vote_user_id, vote_chart_id, vote_rate)
                    	VALUES (" . $userdata['user_id'] . ", " . $chart_id . ", " . $rate . ")";
					if(!$result = $db->sql_query($sql))
                    {
                    	message_die(GENERAL_ERROR, $lang['Chart_Sql_Base_Error'], $lang['Error'], __LINE__, __FILE__, $sql);
                    }
                        
                    if ( $rate == 1 )
                    {
						$sql = "UPDATE " . CHARTS_TABLE . "
							SET chart_hot = chart_hot + 1
							WHERE chart_id = " . $chart_id;
					}
					else
                    {
                    	$sql = "UPDATE " . CHARTS_TABLE . "
							SET chart_not = chart_not + 1
							WHERE chart_id = " . $chart_id;
					}
					
					if(!$result = $db->sql_query($sql))
                    {
                    	message_die(GENERAL_ERROR, $lang['Chart_Sql_Error'], $lang['Error'], __LINE__, __FILE__, $sql);
                    }
                           
                    $message = $lang['Vote_cast'] . "<br /><br />" . sprintf($lang['Chart_click_link'], "<a href=\"" . append_sid("charts.$phpEx?action=all_list") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");
                            
                    message_die(GENERAL_MESSAGE, $message);
				}
                else
                {
                	$message = $lang['Chart_Rate_Err'] . "<br /><br />" . sprintf($lang['Chart_click_link'], "<a href=\"" . append_sid("charts.$phpEx?action=all_list") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");

                    message_die(GENERAL_MESSAGE, $message);
				}
			}
            else
            {
				$message = $lang['Chart_Vote_Err'] . "<br /><br />" . sprintf($lang['Chart_click_link'], "<a href=\"" . append_sid("charts.$phpEx?action=all_list") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");
                    
            	message_die(GENERAL_MESSAGE, $message);
			}
		}
        else
        {
			$message = $lang['Chart_Choose_Err'] . "<br /><br />" . sprintf($lang['Chart_click_link'], "<a href=\"" . append_sid("charts.$phpEx?action=all_list") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");
                
        	message_die(GENERAL_MESSAGE, $message);
		}
	}
    else if ($mode == 'new')
    {
 		if ( !$userdata['session_logged_in'] ) 
		{ 
			redirect("login.".$phpEx."?redirect=charts.".$phpEx."&action=new"); 
			exit; 
		} 

		$page_title = $lang['Charts'] . ' :: ' . $lang['New_Song'];
        include($phpbb_root_path . 'includes/page_header.'.$phpEx);
                
        $s_hidden_fields = '';
		        
		$template->set_filenames(array(
			'body' => 'charts_new_body.tpl')
		);
		make_jumpbox('viewforum.'.$phpEx); 

        $template->assign_vars(array(
           	'L_ADDING_TITLE' => $lang['New_Song'],
        	'L_TITLE' => $lang['Charts'],
			'L_ITEMS_REQUIRED' => $lang['Items_required'],
			'L_SONG' => $lang['Chart_Title'],
            'L_ARTIST' => $lang['Chart_Artist'],
            'L_ALBUM' => $lang['Chart_Album'],
            'L_LABEL' => $lang['Chart_Label'],
            'L_CAT_NO' => $lang['Chart_Cat_No'],
            'L_WEBSITE' => $lang['Website'],
               
            'S_HIDDEN_FIELDS' => $s_hidden_fields,
            'S_CHART_ACTION' => append_sid('charts.'.$phpEx),
            'U_SHOW_LIST' => append_sid('charts.'.$phpEx.'?action=list'))
		);
                
        $template->pparse('body');
                
        include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
	}
    else if ($mode == 'save')
    {
   		if ( !$userdata['session_logged_in'] ) 
		{ 
			redirect("login.".$phpEx."?redirect=charts.".$phpEx); 
			exit; 
		} 
 
		$song_name = ( isset($HTTP_POST_VARS['song_name']) ) ? trim($HTTP_POST_VARS['song_name']) : '';
        $artist_name = ( isset($HTTP_POST_VARS['artist_name']) ) ? trim($HTTP_POST_VARS['artist_name']) : '';
        $album_name = ( isset($HTTP_POST_VARS['album_name']) ) ? trim($HTTP_POST_VARS['album_name']) : '';
        $label_name = ( isset($HTTP_POST_VARS['label_name']) ) ? trim($HTTP_POST_VARS['label_name']) : '';
       	$catno_name = ( isset($HTTP_POST_VARS['catno_name']) ) ? trim($HTTP_POST_VARS['catno_name']) : '';
        $website_name = ( isset($HTTP_POST_VARS['website_name']) ) ? trim($HTTP_POST_VARS['website_name']) : '';

        if ($artist_name == '' || $song_name == '')
        {
			$message = $lang['Chart_Fields_Err'] . "<br /><br />" . sprintf($lang['Chart_click_link'], "<a href=\"" . append_sid("charts.$phpEx?action=list") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");
                    
            message_die(GENERAL_MESSAGE, $message);
		}

        $sql = "INSERT INTO " . CHARTS_TABLE . " (chart_song_name, chart_artist, chart_album, chart_label, chart_catno, chart_website, chart_poster_id)
			VALUES ('" . $song_name . "', '" . $artist_name . "', '" . $album_name . "', '" . $label_name . "', '" . $catno_name . "', '" . $website_name . "', " . $userdata['user_id'] . ")";
		if (!$result = $db->sql_query($sql))
        {
			$message = $lang['Chart_Song_Err'] . "<br /><br />" . sprintf($lang['Chart_click_link'], "<a href=\"" . append_sid("charts.$phpEx?action=new") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");
                    
            message_die(GENERAL_ERROR, $message);
		}
                
		$message = $lang['Chart_Song_Add'] . "<br /><br />" . sprintf($lang['Chart_click_link'], "<a href=\"" . append_sid("charts.$phpEx?action=list") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");
                
        message_die(GENERAL_MESSAGE, $message);
	}
}

?>