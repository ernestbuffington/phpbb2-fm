<?php

/**************************************************************************************************************************
 *                                                    phpbbWapGate
 **************************************************************************************************************************
 *										               wapreply.php
 *									              --------------------
 *											   version 2.0 - modified 12.09.04 
 **************************************************************************************************************************
 *														AUTHORS:
 *
 *									Chirs Gray								  Valentin Vornicu
 *							   info@plus-media.co.uk						valentin@mathlinks.ro
 *							    www.plus-media.co.uk			              www.mathlinks.ro
 * 
 *************************************************************************************************************************/

require "wapheader.php";
require_once $phpbb_root_path . "includes/bbcode.$phpEx";

$topic = (empty($_GET['topic'])) ? "-1" : $_GET['topic'];
$start = (empty($_GET['s'])) ? "0" : $_GET['s'];
$forum_id = (empty($_GET['forum'])) ? "0" : $_GET['forum'];
$username = (empty($_POST['un'])) ? "0" : $_POST['un'];
$username = ($userdata['session_logged_in']) ? $userdata['username'] : $username;
$password = (empty($_POST['pw'])) ? "0" : $_POST['pw'];
$password = ($userdata['session_logged_in']) ? $userdata['user_password'] : md5($password);
$answer = (empty($_POST['re'])) ? "0" : $_POST['re'];
$post_id = (!isset($_GET['post'])) ? "0" : $_GET['post'];
$sig = $_POST['sig'];
$quote = (isset($_GET['quote'])) ? 1 : 0;

if(!isset($_GET['go']))
{
	echo $header;

	?> 
		<do name="accptedit" type="accept" label="Reset Vars">
		<refresh>
		<setvar name="re" value=""/>
		</refresh>
		</do>

	<?php

	if(!$userdata['session_logged_in'])
	{
?>


Username: <input name="un" type="text" emptyok="false"/><br/>
Password: <input name="pw" type="password" emptyok="false"/><br/>
<?php
	}
if($quote)
{
	$query = $db->sql_query("select post_text, bbcode_uid from " . POSTS_TEXT_TABLE . " where post_id = \"$post_id\"");
	$post_text = $db->sql_fetchrow($query);
	$post = $post_text['post_text'];
	$query = $db->sql_query("select poster_id from " . POSTS_TABLE . " where post_id = \"$post_id\"");
	$poster_id = $db->sql_fetchrow($query);
	$poster_id = $poster_id['poster_id'];
	$query = $db->sql_query("select username from " . USERS_TABLE . " where user_id = \"$poster_id\"");
	$username = $db->sql_fetchrow($query);
	$username = $username['username'];
	$post = wap_validate($post,0,1,$post_text['bbcode_uid'],1);
	
	$quotetext = "[quote=\"$username\"] $post [/quote]\n\n";
	$quotetext = htmlentities($quotetext); 
	$quotetext = str_replace ("&amp;","&",$quotetext);
	$quotetext = preg_replace("/\[i\]".$lang['wap_posted_from_a']."(.*)\[\/i\]/","",$quotetext);
	$quotetext = trim($quotetext);

}
else
$quotetext = "";

echo ucwords($lang['wap_answer']); ?>: <input name="re" emptyok="false" maxlength="999" value="<?php echo $quotetext; ?>"/><br/>
<?php echo ucwords($lang['wap_show_sig']); ?>: <select name="sig">
<option value="0">No</option>
<option value="1">Yes</option>
</select><br/>
<anchor>
	Post
	<go href="<?php echo append_sid("wapreply.php?sig=$(sig)&amp;topic=$topic&amp;forum=$forum_id&amp;start=$start&amp;re=$(re)&amp;go=1&amp;quote=$quote&amp;post=$post_id"); ?>" method="post">
		<setvar name="re" value=""/>
		<postfield name="un" value="$(un)"/>
		<postfield name="pw" value="$(pw)"/>
		<postfield name="re" value="$(re)"/>
		<postfield name="sig" value="$(sig)"/>
	</go>
</anchor>
<br/> <?php echo $lang['wap_reset_vars']; 
}
else
{
	$sql = $db->sql_query("SELECT * FROM " . USERS_TABLE . " WHERE username = '" . str_replace("\'", "''", $username) . "'");
	$userdata = $db->sql_fetchrow($sql);
	$current_time = time();
	$user_id = $userdata['user_id'];
	if(!$userdata['session_logged_in'])
	{
			$session_id = session_begin($user_id,$user_ip,PAGE_INDEX);
			echo $header;
	}
	else
	{
		echo $header;
	}
	if( $userdata['user_level'] != ADMIN && $board_config['board_disable'] )
	{
		echo $lang['wap_board_disabled']; 
	}
	else
	{
		if( $password == $userdata['user_password'] && $userdata['user_active'] )
		{
		
		//Now it's time to log them in...
		
		$sql = "SELECT ban_ip, ban_userid, ban_email FROM " . BANLIST_TABLE . " WHERE ban_userid = $user_id";
		$sql .= " OR ban_email LIKE '" . str_replace("\'", "''", $userdata['user_email']) . "' OR ban_email LIKE '" . substr(str_replace("\'", "''", $userdata['user_email']), strpos(str_replace("\'", "''", $userdata['user_email']), "@")) . "'";
		if ( !($result = $db->sql_query($sql)) )
		{
			echo $lang['wap_sql_error']; 
		}
		$ban_info = $db->sql_fetchrow($result);
		if (!empty($ban_info['ban_ip']) || !empty($ban_info['ban_userid']) || !empty($ban_info['ban_email']) )
			{
				echo $lang['wap_user_banned']; 
			}
		else {
				$sql = "SELECT forum_status FROM " . FORUMS_TABLE . " WHERE forum_id = \"$forum_id\"";
				$query = $db->sql_query($sql);
				$forum_status = $db->sql_fetchrow($query);
				$sql = "SELECT topic_status, topic_moved_id FROM " . TOPICS_TABLE . " WHERE topic_id = \"$topic\"";
				$query = $db->sql_query($sql);
				$topic_status = $db->sql_fetchrow($query);
				$auth = auth(AUTH_ALL,$forum_id,$userdata);
				if ( $forum_status['forum_status'] == FORUM_LOCKED && !$auth['auth_mod']) 
					{
					$footer = "<br/><br/><anchor>" . $lang['wap_forum_home'] . "<go href=\"" . append_sid("wap.$phpEx") . "\" /></anchor>".$footer;
					echo $lang['wap_forum_locked']; 
					die(" ".$footer);
					} 
					else if ($topic_status['topic_status'] == TOPIC_LOCKED && !$auth['auth_mod']) 
					{
						$footer = "<br/><br/><anchor>" . $lang['wap_forum_home'] . "<go href=\"". append_sid("wap.$phpEx") . "\" /></anchor>".$footer;
						echo $lang['wap_topic_locked']; 
						die(" ".$footer);
					}
					else if(!empty($topic_status['topic_moved_id']))
					{
						$query = $db->sql_query("select forum_id from " . TOPICS_TABLE . " where topic_id = \"".$topicinfo['topic_moved_id']."\"");
						$forum_id = $db->sql_fetchrow($query);
						echo $lang['wap_thread_moved']. ". " . ucwords($lang['wap_click']) . " <anchor>". $lang['wap_here']. "<go href=\"" . append_sid("wapreply.$phpEx?topic=".$topicinfo['topic_moved_id']."&amp;forum=".$forum_id['forum_id']."") . "\" /></anchor> ". strtolower($lang['wap_to_reply_to_moved']) . ".<br/>";
					}
					else 
					{
						// Flood Control

						$current_time = time();
						$query  = $db->sql_query("SELECT MAX(post_time) AS last_post_time FROM " . POSTS_TABLE . " WHERE poster_id = \"$user_id\"");
						$flood = $db->sql_fetchrow($query);
						if ( $flood['last_post_time'] > 0 && ( $current_time - $flood['last_post_time'] ) < $board_config['flood_interval'] )
						{
							echo $lang['wap_post_soon'] . $lang['wap_double_posting'];
						}
						else
						{
							$answer = $answer." \n\n[i]" . $lang['wap_posted_from_a'] . " " . $phone . "[/i]";
							if($quote)
							{
								$query = $db->sql_query("select u.username from " . POSTS_TABLE . " AS p, " . USERS_TABLE . " AS u where u.user_id = p.poster_id and p.post_id = $post_id");
								$username = $db->sql_fetchrow($query);
								$username = $username['username'];
						 	$answer = str_replace("[quote=" . $username . "]", "[quote=\\\"" . $username . "\\\"]", $answer);
						
						}		

							$post_details = insert_post($answer, "", $forum_id, $user_id, "", $sig, $topic); 

							$last = $db->sql_query("select topic_replies from " . TOPICS_TABLE . " where topic_id = \"$topic\"");
							$last = $db->sql_fetchrow($last);
							$start = $last['topic_replies'];
							echo sprintf($lang['wap_click_view_post']," <anchor>","<go href=\"" . append_sid("waptopic.$phpEx?s=$start&amp;topic=$topic&amp;forum=$forum_id&amp;last=1") . "\"/></anchor>");
												
						}
					}
				}
			}
		else
			{	
			echo $lang['wap_incorrect_user_pass']. "<br/>";
			exit($footer);
			}
	}
}

echo $footer;

?>