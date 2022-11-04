<?php

/**************************************************************************************************************************
 *                                                    phpbbWapGate
 **************************************************************************************************************************
 *										              waplogin.php
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


require 'wapheader.php';

$username = (empty($_GET['un'])) ? "0" : $_GET['un'];
$password = (empty($_GET['pw'])) ? "0" : md5($_GET['pw']);

if(isset($_GET['logout']))
{
	$session = session_end($userdata['session_id'],$userdata['user_id']);
	echo $header;
	echo $lang['wap_logged_out'] . "<br/><br/>";
	$wapphp = append_sid("wap.$phpEx");
	exit("<anchor>" . $lang['wap_forum_home'] . "<go href=\"$wapphp\" /></anchor>" . $footer);
}

else if ($userdata['session_logged_in'])
{
	echo $header;
	echo sprintf($lang['wap_logged_in'],"<anchor>","<go href=\"" . append_sid("wap.$phpEx") . "\" /></anchor>");
	exit(" " . $footer);
}


else if($username == "0" || $password == "0")
{
if(!isset($_GET['logout'])) echo $header;
?>
<?php echo $lang['wap_username']; ?>: <input name="un" type="text" emptyok="false"/><br/>
<?php echo $lang['wap_password']; ?>: <input name="pw" type="password" emptyok="false"/><br/><br/>
<anchor>
	<?php echo $lang['wap_login']; ?>
	<go href="<?php echo append_sid("waplogin.$phpEx?login=1&amp;un=$(un)&amp;pw=$(pw)"); ?>" method="post">
	</go>
</anchor>
<?php
}
else
{
	$current_time = time();
	$sql = $db->sql_query("SELECT * FROM " . USERS_TABLE . " WHERE username = '" . str_replace("\'", "''", $username) . "'");
	$userdata = $db->sql_fetchrow($sql);
	#echo "<pre>";
	#print_r($userdata);
	$user_id = $userdata['user_id'];
	if( $userdata['user_level'] != ADMIN && $board_config['board_disable'] )
	{
		echo $header;
		echo $lang['wap_board_disabled'];
	}
	else
	{
		#$password = (isset($_SESSION['wap_pw'])) ? $password : md5($password);
		if( $password == $userdata['user_password'] && $userdata['user_active'] )
		{
			//Now it's time to log them in...
			$ban_info = $db->sql_fetchrow($result);
			if (!empty($ban_info['ban_ip']) || !empty($ban_info['ban_userid']) || !empty($ban_info['ban_email']) )
				{
					echo $header;
					echo $lang['wap_user_banned'];
				}
			else {
					$session_id = session_begin($user_id,$user_ip,PAGE_INDEX);
					echo $header;
					if($userdata['user_new_privmsg'] != 0)
					{
						$s = ($userdata['user_new_privmsg'] == 1) ? "" : "s";
						$pm = sprintf($lang['wap_new_pm'],"<anchor>",$userdata['user_new_privmsg'],$s,"<go href=\"" . append_sid("wappm.$phpEx") . "\" /></anchor><br/>");
					}
					else if($userdata['user_unread_privmsg'] != 0)
					{
						$s = ($userdata['user_unread_privmsg'] == 1) ? "" : "s";
						$pm = sprintf($lang['wap_unread_pm'],"<anchor>",$userdata['user_unread_privmsg'],$s,"<go href=\"" . append_sid("wappm.$phpEx") . "\" /></anchor><br/>");
					}
					else
					{
					$pm = "";
					}
					echo $lang['wap_login_ok'] . "<br/>$pm<anchor>" . $lang['wap_forum_home'] . "<go href=\"" . append_sid("wap.$phpEx") . "\" /></anchor><br/>" . $lang['wap_bookmark_page'];
				}
			}
		else
		{
			echo $header;
			echo $lang['wap_wrong_username'];
		}
	}
}

echo $footer;

?>