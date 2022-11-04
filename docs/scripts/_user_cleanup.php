<?php

//
// Begin main prog
//
@set_time_limit(300);
define('IN_PHPBB', true); 
$phpbb_root_path = './'; 
include($phpbb_root_path . 'extension.inc'); 
include($phpbb_root_path . 'common.'.$phpEx); 

$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);

if (!$userdata['session_logged_in'])
{
	header('Location: ' . append_sid("login.$phpEx?redirect=_user_cleanup.$phpEx", true));
}

if ($userdata['user_level'] != ADMIN)
{
	message_die(GENERAL_MESSAGE, $lang['Not_Authorised']);
}

//
// List of tables to prune (22491)
// TABLE_NAME => field_name
//
$default_field = 'user_id';
$tables = array(
	ATTACH_QUOTA_TABLE => $default_field,
	ATTACH_QUOTA_TABLE => $default_field,
	$prefix . 'avatartoplist' => 'voter_id',
	BANK_TABLE => $default_field,
	BANVOTE_VOTERS_TABLE => 'banvote_' . $default_field . ' OR banvote_banner_id',
	BOOKIE_BETS_TABLE => $default_field,
	BOOKIE_STATS_TABLE => $default_field,
	CHARTS_TABLE => 'chart_poster_id',
	CHARTS_VOTERS_TABLE => 'vote_' . $default_field,
	DIGEST_FORUMS_TABLE => $default_field,
	DIGEST_LOG_TABLE => $default_field,
	FORUMS_WATCH_TABLE => $default_field,
	GUESTBOOK => $default_field,
	BUDDY_LIST_TABLE => $default_field,
	$prefix . 'im_prefs' => $default_field,
	$prefix . 'im_sessions' => 'session_' . $default_field,
	$prefix . 'ina_ban' => 'id',
	$prefix . 'ina_challenge_tracker' => 'user',
	$prefix . 'ina_challenge_users' => 'user_to OR user_from',
	$prefix . 'ina_cheat_fix ' => 'player',
	$prefix . 'ina_favorites' => 'user',
	$prefix . 'ina_gamble_in_progress' => 'sender_id OR reciever_id',
	$prefix . 'ina_last_game_played' => $default_field,
	$prefix . 'ina_hall_of_fame' => 'current_' . $default_field . ' OR old_' . $default_field,
	$prefix . 'ina_rating_votes' => 'player',
	$prefix . 'ina_sessions' => 'playing_id',
	$prefix . 'ina_top_scores' => 'player',
	$prefix . 'ina_trophy_comments' => 'player',
	EMPLOYED_TABLE => $default_field,
	$prefix . 'links' => $default_field,
	$prefix . 'link_comments' => 'poster_id',
	$prefix . 'link_votes' => $default_field,
	LOGS_TABLE => $default_field,
	LOTTERY_TABLE => $default_field,
	LOTTERY_HISTORY_TABLE => $default_field,
	MEETING_COMMENT_TABLE => $default_field,
	MEETING_GUESTNAMES_TABLE => $default_field,
	MEETING_USER_TABLE => $default_field,
	PA_COMMENTS_TABLE => 'poster_id',
	PA_DOWNLOAD_INFO_TABLE => $default_field,
	PA_FILES_TABLE => $default_field,
	PA_VOTES_TABLE => $default_field,
	POSTS_EDIT_TABLE => $default_field,
	HIDE_TABLE => $default_field,
	PROFILE_VIEW_TABLE => $default_field . ' OR viewer_id',
	RATING_TABLE => $default_field,
	RATING_BIAS_TABLE => $default_field,
	REFERRAL_TABLE => 'ruid OR nuid',
	SESSIONS_TABLE => 'session_' . $default_field,
	SESSIONS_KEYS_TABLE => $default_field,
	SHOPTRANS_TABLE => 'trans_user',
	SHOUTBOX_TABLE => 'shout_' . $default_field,
	SUBSCRIPTIONS_LIST_TABLE => $default_field,
	THANKS_TABLE => $default_field,
	THREAD_KICKER_TABLE => $default_field,
	TOPICS_VIEWDATA_TABLE => $default_field,
	TOPICS_WATCH_TABLE => $default_field,
	TRANSACTION_TABLE => 'trans_from OR trans_to',
	NOTES_TABLE => 'poster_id',
	TABLE_USER_SHOPS => $default_field,
	USERS_COMMENTS_TABLE => $default_field,
	VOTE_USERS_TABLE => 'vote_' . $default_field,
	XDATA_DATA_TABLE => $default_field,
);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<title>Fully Modded phpBB &bull; Deleted User Cleanup</title>
<head>
<meta http-equiv="Content-Type" content="text/html;">
<style type="text/css">
<!--
* { margin-top: 0; }
body {  font-family: "Lucida Grande", Verdana, Helvetica, Arial, sans-serif; color: #536482; background: #E4EDF0 url("templates/subSilver/images/bg_header.gif") 0 0 repeat-x; font-size: 62.5%; scrollbar-face-color: #DCE1E5; scrollbar-highlight-color: #FFFFFF; scrollbar-shadow-color: #DCE1E5; scrollbar-3dlight-color: #C7CFD7; scrollbar-arrow-color:  #006699; scrollbar-track-color: #ECECEC; scrollbar-darkshadow-color: #98AAB1; margin-top: 0; }
font,th,td { font-size: 11px; color: #323D4F; font-family: Verdana, Arial, Helvetica, sans-serif }
p { margin-bottom: 1.0em; line-height: 1.5em; font-size: 11px; }
ul { list-style: disc; margin: 0 0 1em 2em; }
a:link,a:active,a:visited { color: #006699 ;text-decoration: none; }
a:hover	{ text-decoration: underline; color: #DD6900; }
hr { border: 0 none; border-top: 1px solid #C7CFD7; margin-bottom: 5px; padding-bottom: 5px; height: 1px; }
.bodyline { background-color: #FFFFFF; border: 1px #98AAB1 solid; }
.copyright { font-size: 10px; color: #444444; letter-spacing: -1px; }
a.copyright { color: #444444; text-decoration: none; }
a.copyright:hover { color: #323D4F; text-decoration: underline; }
h1 { margin: 0; font: bold 1.8em "Lucida Grande", 'Trebuchet MS', Verdana, sans-serif; text-decoration: none; color: #323D4F; }
input, select { color: #323D4F; background-color: #FFFFFF; font: normal 11px Verdana, Arial, Helvetica, sans-serif; border-color: #323D4F; border-top-width : 1px; border-right-width : 1px; border-bottom-width : 1px; border-left-width : 1px; }
input { text-indent : 2px; }
input.mainoption { background-color: #FAFAFA; font-weight: bold; }
img, .forumline img { border: 0; }
.ok { color: #009900; }
.error { color: #D20000; }
#wrap { padding: 0 20px 0px 20px; min-width: 615px; }
#page-header { text-align: right; background: url("templates/subSilver/images/logo_acp_phpBB.gif") 0 0 no-repeat; height: 84px; }
#page-header h1 { font-family: "Lucida Grande", Verdana, Arial, Helvetica, sans-serif; color: #323D4F; font-size: 1.5em; font-weight: normal; }
#page-header p { font-size: 1.1em; }
-->
</style>
</head>
<body topmargin="0" bgcolor="#FFFFFF" text="#323D4F" link="#006699" vlink="#5493B4">

<div id="wrap">
	<div id="page-header"><br />
		<h1>Fully Modded phpBB &bull; Deleted User Cleanup</h1>
		<p><i>Cleaning up non-existant userdata</i></p>
	</div>
</div>
<table align="center" width="98%" cellpadding="10" cellspacing="0">
<tr> 
	<td class="bodyline"><table width="100%" cellpadding="5" cellspacing="0">
  	<tr> 
  		<td>

<?php

$clean = ( isset($HTTP_POST_VARS['clean']) ) ? $HTTP_POST_VARS['clean'] : 0;

if ( $clean )
{
	echo '<h1>Obtaining current user_ids\'</h1><p>';

	//
	// Get list of current users
	//
	$sql = "SELECT user_id
		FROM " . USERS_TABLE . " 
		WHERE user_id != " . ANONYMOUS;
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain current user_ids.', '', __LINE__, __FILE__, $sql);
	}
	$users = $db->sql_fetchrowset($result);
	$user_list = '';
	for($i = 0; $i < sizeof($users); $i++)
	{
		$user_list .= ( ( $user_list != '' ) ? ', ' : '' ) . intval($users[$i]['user_id']);
	}
	
	echo $user_list . '</p><h1>Cleaning database of non-existant userdata</h1>';
		
	//
	// Start the cleanup
	//
	while ( list ( $table_name, $field_name ) = each ( $tables ) )
	{
		$sql = "DELETE FROM
			" . $table_name . "
			WHERE " . $field_name . " 
				NOT IN (" . $user_list . ")";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not delete ' . $field_name . ' from ' . $table_name . ' table.', '', __LINE__, __FILE__, $sql);
		}
	
		echo '<p>' .$sql . ';<p>';
	}
}
else
{
	echo '<h1>Deleted User Cleanup</h1>
		<p>This script will automatically try and delete any userdata for users that no longer exist.</p>
		<p>Tables that will be checked for userdata are as follows:<br />
		<pre>';

	while ( list ( $table_name, $field_name ) = each ( $tables ) )
	{
		echo '- ' . $table_name . '<br />';
	}	

	echo '</pre>
		<p>Now if you are ready, proceed by clicking Clean Up below!</p>
		<center>
			<form action="' . append_sid('_user_cleanup.'.$phpEx) . '" method="post">
				<input type="submit" name="clean" value="Clean Up" class="mainoption" />
			</form>
		</center>';
}

?>
		</td>
	</tr>
	<tr>
		<td colspan="2" height="1"><img src="images/spacer.gif" height="1" alt="" title="" /></td>
	</tr>
	</table></td>
</tr>
</table>
<div align="center" class="copyright">
Powered by <a href='http://phpbb-fm.com/' target='_blank' class='copyright'>Fully Modded phpBB</a> &copy; 2005, <?php echo date('Y'); ?>
</div>
</body>
</html>