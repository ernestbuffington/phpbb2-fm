<?php

/**************************************************************************************************************************
 *                                                    phpbbWapGate
 **************************************************************************************************************************
 *										                wappm.php
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

require 'waplang.php';
$title = ucwords(strtolower($lang['wap_pms'])); 
require 'wapheader.php';

$start = (empty($_GET['start'])) ? "0" : str_replace("\'", "''", $_GET['start']);
$pmid = (empty($_GET['pmid'])) ? "0" : str_replace("\'", "''", $_GET['pmid']);
$username = (empty($_GET['username'])) ? "" : str_replace("\'", "''", $_GET['username']);
$sub = (empty($_POST['sub'])) ? "0" : str_replace("\'", "''", $_POST['sub']);
$txt = (empty($_POST['txt'])) ? "0" : str_replace("\'", "''", $_POST['txt']);
$to = (empty($_POST['to'])) ? "0" : str_replace("\'", "''", $_POST['to']);


$action = (isset($_GET['action'])) ? $_GET['action'] : "sum";

echo $header;
function save_pm($txt="",$sub="",$to="")
{
	global $userdata,$db;
	$current_time = time();
	$uid = make_bbcode_uid();
	$txt = prepare_wap_post($txt,$uid);
	if(!$query = $db->sql_query("select u.user_id, u.user_notify_pm from " . USERS_TABLE . " u where username = \"" . $to . "\""))
	{
	echo $lang['No_such_user'];
	}
	else
	{
		$user_to = $db->sql_fetchrow($query);
		$query = $db->sql_query("insert into " . PRIVMSGS_TABLE . " (privmsgs_type, privmsgs_subject, privmsgs_from_userid, privmsgs_to_userid, privmsgs_date, privmsgs_ip) values (\"" . PRIVMSGS_NEW_MAIL . "\", \"$sub\", \"" . $userdata['user_id'] . "\", \"" . $user_to['user_id'] . "\", \"$current_time\", \"" . $userdata['session_ip'] . "\")");
		$privmsg_id = $db->sql_nextid();
		$query = $db->sql_query("insert into " . PRIVMSGS_TEXT_TABLE . "(privmsgs_text_id, privmsgs_bbcode_uid, privmsgs_text) values (\"$privmsg_id\", \"$uid\", \"$txt\")");
		$query = $db->sql_query("update " . USERS_TABLE . " set user_new_privmsg = user_new_privmsg + 1, user_last_privmsg = " . time() . " where user_id = \"" . $user_to['user_id'] . "\"") or die(" ");
		echo $lang['wap_pm_ok'] . ".";
				?>
		<do name="accpt" type="accept" label="Reset Vars">
<refresh>
<setvar name="to" value=""/>
<setvar name="sub" value=""/>
<setvar name="txt" value=""/>
</refresh>

</do>

<?php 

	}
}
if(!$userdata['session_logged_in'])
{
	echo $lang['wap_pm_login_first']. ". ". ucwords(strtolower($lang['wap_login'])) . " <anchor>" . $lang['wap_here']. "<go href=\"" . append_sid("waplogin.$phpEx") . "\" /></anchor><br/><br/>";
}
else
{
	switch($action)
	{
		case "sum" :
			
			$i = 0;
			$query = $db->sql_query("update " . PRIVMSGS_TABLE . " set privmsgs_type = \"" . PRIVMSGS_UNREAD_MAIL . "\" where privmsgs_to_userid = \"" . $userdata['user_id'] . "\" and privmsgs_type = \"" . PRIVMSGS_NEW_MAIL . "\"");
			$query = $db->sql_query("select * from " . PRIVMSGS_TABLE . " where privmsgs_to_userid = \"" . $userdata['user_id'] . "\" and privmsgs_type != \"" . PRIVMSGS_SAVED_IN_MAIL . "\" and privmsgs_type != \"" . PRIVMSGS_SAVED_OUT_MAIL . "\" and privmsgs_type != \"" . PRIVMSGS_SENT_MAIL . "\"");
			$privmsgs_no = $db->sql_numrows($query);
			if ($privmsgs_no == 0)
			{
				echo $lang['wap_no_pms']. ".<br/>";
				exit (" " . $footer);
			}
			$query = $db->sql_query("select * from " . PRIVMSGS_TABLE . " where privmsgs_to_userid = \"" . $userdata['user_id'] . "\" and privmsgs_type != \"" . PRIVMSGS_SAVED_IN_MAIL . "\" and privmsgs_type != \"" . PRIVMSGS_SAVED_OUT_MAIL . "\" and privmsgs_type != \"" . PRIVMSGS_SENT_MAIL . "\" order by privmsgs_date DESC limit $start,-1");
			$privmsgs_list = $db->sql_fetchrowset($query);
			$privmsgs_no_unread = 0;
			
			// We have to get the number of private messages the user hasn't read

			foreach ($privmsgs_list as $key=>$val)
			{
				if($val['privmsgs_type'] == PRIVMSGS_UNREAD_MAIL)
				{
					++$i;
				}
			}
			$unread_privmsgs = $i;
			$i = 0;
			if($start == 0)
			{
				// Then it is updated in the users_table

				$query = $db->sql_query("update " . USERS_TABLE . " set user_new_privmsg = \"0\", user_unread_privmsg = \" " . $unread_privmsgs . "\" where user_id = " . $userdata['user_id']);
			}
			
			$next = $start;
			$prev = $start;
			$next = $next + 10;
			$prev = $prev - 10;
			if($start == 0)
			{
				if($privmsgs_no > 10)
				{
					$nav = "<anchor>". $lang['wap_next'] . "&gt;&gt;<go href=\"" . append_sid("wappm.$phpEx?start=$next") . "\" /></anchor>";
				}
			}
			else
			{
				if($next >= $privmsgs_no)
				{
					$nav = "<anchor>&lt;&lt;" . $lang['wap_prev'] . "<go href=\"" . append_sid("wappm.$phpEx?start=$prev") . "\" /></anchor>";
				}
				else 
				{
					$nav = "<anchor>&lt;&lt;". $lang['wap_prev']. "<go href=\"" . append_sid("wappm.$phpEx?start=$prev") . "\" /></anchor>&nbsp;<anchor>". $lang['wap_next']. "&gt;&gt;<go href=\"" . append_sid("wappm.$phpEx?start=$next") . "\" /></anchor>";
				}
			}
					
			foreach($privmsgs_list as $key=>$val)
			{	
				if($i > 10)
				{
					break;
				}
				$b = ($val['privmsgs_type'] == PRIVMSGS_NEW_MAIL || $val['privmsgs_type'] == PRIVMSGS_UNREAD_MAIL) ? "<b>" : "";
				$bclose = ($val['privmsgs_type'] == PRIVMSGS_NEW_MAIL || $val['privmsgs_type'] == PRIVMSGS_UNREAD_MAIL) ? "</b>" : "";
				$query = $db->sql_query("select username from " . USERS_TABLE . " where user_id = \"" . $val['privmsgs_from_userid'] . "\"");
				$from = $db->sql_fetchrow($query);
				$date = create_date($board_config['default_dateformat'], $val['privmsgs_date'], $board_config['board_timezone']);
				echo "$b <anchor>" . $val['privmsgs_subject'] . " " . strtolower($lang['wap_from']) ." ". $from['username'] . "<go href=\"" . append_sid("wappm.php?action=view&amp;pmid=" . $val['privmsgs_id']) . "\" /></anchor>$bclose<br/>";
				++$i;
			}
			echo "$nav <br/>";
		
		break;
		
		case "view" :
		
			$query - $db->sql_query("select * from " . PRIVMSGS_TABLE . " where privmsgs_id = \"" . $pmid . "\" and privmsgs_to_userid = \"" . $userdata['user_id'] . "\"");
			$privmsg = $db->sql_fetchrow($query);
			if($db->sql_numrows($query) == 0)
			{
				echo $lang['wap_pm_not_auth']. "<br/>";
			}
			else
			{
				// Get all the stuff which makes up the private message

				$query = $db->sql_query("select * from " . PRIVMSGS_TEXT_TABLE . " where privmsgs_text_id = \"" . $privmsg['privmsgs_id'] . "\" limit 1");
				$privmsgs_text = $db->sql_fetchrow($query);
				$query = $db->sql_query("select user_id, username from " . USERS_TABLE . " where user_id = " . $privmsg['privmsgs_from_userid'] . " limit 1");
				$user = $db->sql_fetchrow($query);
				$username = $user['username'];
				
				$privmsg_date = create_date($board_config['default_dateformat'], $privmsg['privmsgs_date'], $board_config['board_timezone']);
				
				// Now that it is read, in accordance with PHPBB stuff, we create a copy for the user who sent it and stores it as a SENT message... :-S

				$query = $db->sql_query("insert into " . PRIVMSGS_TABLE . " (privmsgs_type, privmsgs_subject, privmsgs_from_userid, privmsgs_to_userid, privmsgs_date, privmsgs_ip, privmsgs_enable_html, privmsgs_enable_bbcode, privmsgs_enable_smilies, privmsgs_attach_sig) values (" . PRIVMSGS_SENT_MAIL . ", '" . str_replace("\'", "''", addslashes($privmsg['privmsgs_subject'])) . "', " . $privmsg['privmsgs_from_userid'] . ", " . $privmsg['privmsgs_to_userid'] . ", " . $privmsg['privmsgs_date'] . ", '" . $privmsg['privmsgs_ip'] . "', " . $privmsg['privmsgs_enable_html'] . ", " . $privmsg['privmsgs_enable_bbcode'] . ", " . $privmsg['privmsgs_enable_smilies'] . ", " .  $privmsg['privmsgs_attach_sig'] . ")");
				$privmsg_sent_id = $db->sql_nextid();
				$query = $db->sql_query("insert into " . PRIVMSGS_TEXT_TABLE . " (privmsgs_text_id, privmsgs_bbcode_uid, privmsgs_text) values ($privmsg_sent_id, '" . $privmsgs_text['privmsgs_bbcode_uid'] . "', '" . str_replace("\'", "''", addslashes($privmsgs_text['privmsgs_text'])) . "')");
				$query = $db->sql_query("update " . PRIVMSGS_TABLE . " set privmsgs_type = \"" . PRIVMSGS_READ_MAIL . "\" where privmsgs_id = \"" . $pmid . "\"");
				$query = $db->sql_query("update " . USERS_TABLE . " set user_unread_privmsg = user_unread_privmsg-1 where user_id = \"" . $userdata['user_id'] . "\"");
				echo "<b>" . $privmsg['privmsgs_subject'] . "</b><br/>";
				echo $lang['wap_from']. ": <anchor>" . $username . "<go href=\"" . append_sid("wapmisc.$phpEx?action=profile&amp;u=" . $user['user_id']) . "\" /></anchor> on " . $privmsg_date . "<br/><br/>";
				echo wap_validate($privmsgs_text['privmsgs_text'],1,1,$privmsgs_text['privmsgs_bbcode_uid']);
				echo "<br/><br/><anchor>". $lang['wap_reply'] . "<go href=\"" . append_sid("wappm.$phpEx?action=reply&amp;pmid=$pmid") . "\" /></anchor>";
				echo "<br/><anchor>" . $lang['wap_delete']. "<go href=\"" . append_sid("wappm.php?action=delete&amp;pmid=$pmid") . "\" /></anchor>";
			}
		break;
		
		case "delete" :
		
			$query = $db->sql_query("select privmsgs_id from " . PRIVMSGS_TABLE . " where privmsgs_id = \"$pmid\" and privmsgs_to_userid = \"" . $userdata['user_id'] . "\" limit 1");
			$privmsg_info = $db->sql_fetchrow($query);
			if($privmsg_info['privmsgs_id'] == 0)
			{
				echo $lang['wap_pm_not_exist'];
			}
			else
			{
				$query = $db->sql_query("delete from " . PRIVMSGS_TABLE . " where privmsgs_id = " . $privmsg_info['privmsgs_id'] . " limit 1");
				if($query)
				{
					echo $lang['wap_pm_deleted_ok']; 
				}
				else
				{
					echo $lang['wap_there_was_an_error']. ". " . $lang['wap_please_try_again_later']. "."; 
				}
			}
		
		break;
		
		case "reply" :
		
			if(!$userdata['user_allow_pm'])
			{
			echo $lang['wap_not_allowed_to_send_pm']. ". ". $lang['wap_please_contact_admin'] . ".<br/><br/>";
			exit($footer);
			}
			$query = $db->sql_query("select p.privmsgs_from_userid, p.privmsgs_subject, u.username from " . PRIVMSGS_TABLE . " p, " . USERS_TABLE . " u where p.privmsgs_id = \"$pmid\" and p.privmsgs_to_userid = \"" . $userdata['user_id'] . "\" and u.user_id = p.privmsgs_from_userid limit 1");
			$privmsg_info = $db->sql_fetchrow($query);
			if($sub == "0" || $txt == "0")
			{
			$pm_to = ($to == "0") ? ($privmsg_info['username']) : ($to);
			$privmsgs_info['privmsgs_subject'] = (!empty($privmsgs_info['privmsgs_subject'])) ? "Re: " . $privmsgs_info['privmsgs_subject'] : "";
			echo ucwords(strtolower($lang['wap_to'])); ?>: <input name="to" type="text" emptyok="false" value="<?php echo $pm_to; ?>" /><br/>
			<?php echo ucwords(strtolower($lang['wap_subject'])); ?>: <input name="sub" type="text" emptyok="false" value="<?php echo wap_validate($privmsg_info['privmsgs_subject'],1,0); ?>" /><br/>
			<?php echo ucwords($lang['wap_message']); ?>: <input name="txt" emptyok="false"/><br/>
			<anchor>
			<?php echo ucwords($lang['wap_send']); ?>
				<go href="<?php echo append_sid("wappm.$phpEx?action=reply"); ?>" method="post">
				<setvar name="sub" value=""/>
				<setvar name="txt" value=""/>
				<setvar name="to" value=""/>		
				<postfield name="to" value="$(to)"/>
				<postfield name="sub" value="$(sub)"/>
				<postfield name="txt" value="$(txt)"/>
				</go>
			</anchor>
			<?php
			}
			
			else
			{
			save_pm($txt,$sub,$to);
			}
		
		
		break;
		
		case "new" :
		
		if($to == 0 && $choose == 0)
		{
		echo ucwords($lang['wap_to']); ?>: <input name="to" type="text" emptyok="false" value="<?php echo $username; ?>" /><br/>
		<anchor>
		<?php echo ucwords($lang['wap_next']); ?>
		<go href="<?php echo append_sid("wappm.$phpEx?action=reply"); ?>" method="post">
		<setvar name="to" value=""/>
		<postfield name="to" value="$(to)"/>
		</go>
		</anchor>
		<?php
		}
		break;
	}
}

echo $footer;

?>