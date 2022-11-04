<?php
/** 
*
* @package admin
* @version $Id: admin_sub_records.php,v 1.0.0.1 2004/10/29 17:49:33 acydburn Exp $
* @copyright (c) 2004 Loewen Enterprise - Xiong Zou
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Donations_Subscriptions']['IPN_Log'] = $filename;

	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = '../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

//
// Include language
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_ipn_grp.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_ipn_grp.' . $phpEx);


//
// Mode setting
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

if ( $mode == 'lookup' )
{
	$username = $hidden_fields = '';
	$user_id = 0;
	if( isset($HTTP_POST_VARS['username']) || isset($HTTP_GET_VARS['username']) )
	{
		$username = (isset($HTTP_POST_VARS['username'])) ? $HTTP_POST_VARS['username'] : $HTTP_GET_VARS['username'];	
	}
	
	if(strlen( trim($username) ) > 0)
	{
		$sql = "SELECT * 
			FROM " . USERS_TABLE . " 
			WHERE username = '" . $username . "'";
		if($result = $db->sql_query($sql))
		{
			if( ($userinfo = $db->sql_fetchrow($result)) )
			{
				$user_id = $userinfo['user_id'];
			}
		}
	}
	
	$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
	
	//
	// Generate a 'Show topics in previous x days' select box. If the topicsdays var is sent
	// then get it's value, find the number of topics with dates newer than it (to properly
	// handle pagination) and alter the main query
	//
	$previous_days = array(7, 14, 30, 90, 180, 364, 400);
	$previous_days_text = array($lang['7_Days'], $lang['2_Weeks'], $lang['1_Month'], $lang['3_Months'], $lang['6_Months'], $lang['1_Year'], $lang['LW_ALL_RECORDS']);
	
	$topic_days = 7;
	if ( !empty($HTTP_POST_VARS['topicdays']) || !empty($HTTP_GET_VARS['topicdays']) )
	{
		$topic_days = ( !empty($HTTP_POST_VARS['topicdays']) ) ? intval($HTTP_POST_VARS['topicdays']) : intval($HTTP_GET_VARS['topicdays']);
	
		if($topic_days > 0 && $topic_days < 400)
		{
			$min_topic_time = time() - ($topic_days * 86400);
	
			$limit_topics_time = "lw_date >= $min_topic_time";
		}
		else
		{
			$topic_days = 400;
			$limit_topics_time = '';
		}
	
		if ( !empty($HTTP_POST_VARS['topicdays']) )
		{
			$start = 0;
		}
	}
	else
	{
		$topic_days = 7;
		$min_topic_time = time() - ($topic_days * 86400);
	
		$limit_topics_time = "lw_date >= $min_topic_time";
	}
	
	$select_topic_days = '<select name="topicdays">';
	for($i = 0; $i < sizeof($previous_days); $i++)
	{
		$selected = ($topic_days == $previous_days[$i]) ? ' selected="selected"' : '';
		$select_topic_days .= '<option value="' . $previous_days[$i] . '"' . $selected . '>' . $previous_days_text[$i] . '</option>';
	}
	$select_topic_days .= '</select>';
	
	$template->set_filenames(array(
	   	'body' => 'admin/sub_records_body.tpl')
	);
		
	$hidden_fields = '<input type="hidden" name="mode" value="lookup" /><input type="hidden" name="username" value="' . $username . '" />';
	
	$template->assign_vars(array(
        'L_PAGE_TITLE' => $lang['L_IPN_log_title'],
		'L_PAGE_EXPLAIN' => $lang['L_IPN_log_title_explain'],
	   	'L_LW_USERNAME' => $lang['L_LW_USERNAME'],
		'L_LW_CURRENCY' => $lang['LW_ACCT_CURRENCY'],
		'L_LW_MONEY' => $lang['LW_ACCT_AMOUNT'],
		'L_LW_PLUS_MINUS' => $lang['LW_ACCT_PLUS_MINUS'],
		'L_LW_TXNID' => $lang['LW_ACCT_TXNID'],
		'L_LW_POSTID' => $lang['LW_ACCT_POSTID'],
		'L_LW_STATUS' => $lang['LW_ACCT_STATUS'],
		'L_LW_COMMENT' => $lang['LW_ACCT_COMMENT'],
		'L_LW_DATE' => $lang['Date'],
		'L_SUBMIT' => $lang['Go'],
		
		'L_DISPLAY_TOPICS' => $lang['LW_ACCT_DISPLAY_FROM'],
		'S_SELECT_TOPIC_DAYS' => $select_topic_days,
		'LW_HIDDEN_FIELDS' => $hidden_fields,
		'S_RECORDS_DAYS_ACTION' => append_sid('admin_sub_records.' . $phpEx))
	);
	
	$topics_count = 1;
	$strwhere = ( (strlen(trim($limit_topics_time)) > 0 || ($user_id > 0)) ? ' WHERE ' : '' );
	$strlimittime = trim($limit_topics_time);
	$strand = (strlen(trim($limit_topics_time)) > 0 && ($user_id > 0)) ? ' AND ' : '';
	$struserid = ($user_id > 0) ? ('user_id = ' . $user_id) : '';
	
	$sql = "SELECT COUNT(*) 
		FROM " . ACCT_HIST_TABLE . 
		$strwhere . 
		$strlimittime . 
		$strand . 
		$struserid;
	$result = $db->sql_query($sql);
	
	if ($row = $db->sql_fetchrow($result))
	{
		$topics_count = $row["COUNT(*)"];
	}
	$db->sql_freeresult($result);

	$sql = "SELECT username, user_id 
		FROM " . USERS_TABLE;
	$usernamearray = array();
	if ($result = $db->sql_query($sql))
	{
		while ( $row = $db->sql_fetchrow($result) ) 
		{
			$usernamearray[$row['user_id']] = $row['username'];
		}
		$db->sql_freeresult($result);
	}
		
//	$sql = "SELECT * FROM " . ACCT_HIST_TABLE . ((strlen(trim($limit_topics_time)) > 0) ? (" WHERE " . $limit_topics_time) : " ")
//		 . " ORDER BY lw_date DESC "
//		 . "LIMIT $start, $board_config['topics_per_page']";
	$sql = "SELECT * 
		FROM " . ACCT_HIST_TABLE . 
		$strwhere . $strlimittime . $strand . $struserid . "
		ORDER BY lw_date DESC
		LIMIT " . $start . ", " . $board_config['topics_per_page'];
		$result = $db->sql_query($sql);
	
	$i = 0;
	while ( $row = $db->sql_fetchrow($result) ) 
	{
		$canviewpost = 0;
		if(strnatcasecmp($row['lw_site'], $prefix) == 0)
		{
			$canviewpost = 1;
		}
		$posturl = '';
		if ($row['lw_post_id'] > 0)
		{
			if ($canviewpost == 1)
			{
				$posturl = '<a href="viewtopic.'.$phpEx.'?' . POST_POST_URL . '=' . $row['lw_post_id'] . '#' . $row['lw_post_id'] . '">' . $row['lw_post_id'] . '</a>';
			}
			else
			{
				$posturl = $row['lw_post_id'];
			}
		}

		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
		
	   	$template->assign_block_vars('topicrow', array(
	   		'ROW_CLASS' => $row_class,
	   		'LW_USERNAME' => '<a href="' . append_sid('admin_sub_records.'.$phpEx.'?mode=lookup&amp;username=' . $usernamearray[$row['user_id']]) . '">' . $usernamearray[$row['user_id']] . '</a>',
	   		'LW_CURRENCY' => $row['MNY_CURRENCY'],
			'LW_MONEY' => $row['lw_money'],
			'LW_PLUS_MINUS' => ( $row['lw_plus_minus'] == 1 ) ? $lang['LW_ACCT_CREDIT'] : $lang['LW_ACCT_DEBIT'],
			'LW_TXNID' => $row['txn_id'],
			'LW_POSTID' => $posturl,
			'LW_STATUS' => $row['status'],
			'LW_DATE' => create_date($board_config['default_dateformat'], $row['lw_date'], $board_config['board_timezone']),
			'LW_COMMENT' => $row['comment'])
	   	);
		$i++;	
	}
	$db->sql_freeresult($result);

	if ($topics_count > 0)
	{
		$template->assign_vars(array(
			'PAGINATION' => generate_pagination("admin_sub_records.$phpEx?topicdays=$topic_days&username=$username&mode=lookup", $topics_count, $board_config['topics_per_page'], $start),
			'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $board_config['topics_per_page'] ) + 1 ), ceil( $topics_count / $board_config['topics_per_page'] )),
	
			'L_GOTO_PAGE' => $lang['Goto_page'])
		);
	}
	
	if ($topics_count <= 0)
	{
		$template->assign_vars(array(
			'L_LW_NO_RECORDS' => $lang['None'])
		);
	
		$template->assign_block_vars('switch_lw_no_records', array());
	}
	
	$template->pparse('body');
}
else
{
	//
	// Default user selection box
	//
	$template->set_filenames(array(
		'body' => 'admin/user_select_body.tpl')
	);

	$template->assign_vars(array(
        'L_USER_TITLE' => $lang['L_IPN_log_title'],
		'L_USER_EXPLAIN' => $lang['L_IPN_log_title_explain'],
		'L_LOOK_UP' => $lang['Look_up_User'],
		'L_CREATE_USER' => $lang['Create_new_User'],
		'L_USERNAME' => $lang['Username'],
		'L_EMAIL_ADDRESS' => $lang['Email_address'],
		'L_POSTS' => $lang['Posts'],
		'L_JOINED' => $lang['Joined'],
		'L_JOINED_EXPLAIN' => $lang['User_joined_explain'],

		'U_SEARCH_USER' => append_sid("./../search.$phpEx?mode=searchuser"), 

		'S_PROFILE_ACTION' => append_sid($phpbb_root_path . 'profile.'.$phpEx.'?mode=' . REGISTER_MODE),
		'S_USER_ACTION' => append_sid('admin_sub_records.'.$phpEx),
		'S_USER_SELECT' => $select_list)
	);

	$template->pparse('body');
}

include('./page_footer_admin.'.$phpEx);

?>