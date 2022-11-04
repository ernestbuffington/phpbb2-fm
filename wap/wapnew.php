<?php

/**************************************************************************************************************************
 *                                                    phpbbWapGate
 **************************************************************************************************************************
 *										                wapnew.php
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


$title = 'New Topic';
require 'wapheader.php';

$sub = (empty($_POST['sub'])) ? "0" : $_POST['sub'];
$txt = (empty($_POST['txt'])) ? "0" : $_POST['txt'];
$forum = (empty($_POST['forum'])) ? "-1" : $_POST['forum'];

echo $header;


if(!$userdata['session_logged_in'])
{
	echo $lang['wap_newtopic_login_first']. ". " . ucwords(strtolower($lang['wap_login'])) . " <anchor>" . $lang['wap_here'] . "<go href=\"" . append_sid("waplogin.$phpEx") . "\" /></anchor>";
}


else if($sub == "0" || $txt == "0" || $forum == "-1")
{

echo ucwords(strtolower($lang['wap_subject'])); ?>: <input name="sub" type="text" emptyok="false"/><br/>
<?php echo ucwords(strtolower($lang['wap_message'])); ?>: <input name="txt" emptyok="false"/><br/>
<?php echo ucwords(strtolower($lang['wap_select_forum'])); ?>: <select name="forum">
<option value="-1"><?php echo ucwords(strtolower($lang['wap_select_forum'])); ?>...</option>
<?php
	
	$query = $db->sql_query("select forum_id,forum_name from ". FORUMS_TABLE . " where forum_status = \"0\" order by cat_id ASC");
	$forum = $db->sql_fetchrowset($query);
	foreach ($forum as $val)
	{
		$auth = auth(AUTH_ALL,$val['forum_id'],$userdata);
		if($auth['auth_post'])
		{
			echo "<option value=\"".$val['forum_id']."\">".htmlentities ($val['forum_name'])."</option>\n";
		}
	}


?>
</select><br/>
<anchor>
	Post
	<go href="<?php echo append_sid("wapnew.$phpEx?sub=$(sub)&amp;txt=$(txt)&amp;forum=$(forum)"); ?>" method="post">
		<setvar name="sub" value=""/>
		<setvar name="txt" value=""/>
		<setvar name="forum" value=""/>		
		<postfield name="sub" value="$(sub)"/>
		<postfield name="txt" value="$(txt)"/>
		<postfield name="forum" value="$(forum)"/>
	</go>
</anchor>
<?php
}
else
{
	$current_time = time();
	$sql = $db->sql_query("SELECT * FROM " . USERS_TABLE . " WHERE username = '" . str_replace("\'", "''", $userdata['username']) . "'");
	$userdata = $db->sql_fetchrow($sql);
	$user_id = $userdata['user_id'];
	$username = $userdata['username'];
	if( $userdata['user_level'] != ADMIN && $board_config['board_disable'] )
	{
		echo $lang['wap_board_disabled']; 
	}
	else {
				$sql = "SELECT forum_status FROM " . FORUMS_TABLE . " WHERE forum_id = \"$forum_id\"";
				$query = $db->sql_query($sql);
				$forum_status = $db->sql_fetchrow($query);
				$auth = auth(AUTH_ALL,$forum_id,$userdata);
				if ( $forum_status['forum_status'] == FORUM_LOCKED && !$auth['auth_mod']) 
					{
					$footer = "<br/><br/><anchor>" . ucwords(strtolower($lang['wap_forum_home'])) . "<go href=\"" . append_sid("wap.$phpEx") . "\" /></anchor>".$footer;
					echo $lang['wap_forum_locked'];
					die(" ".$footer);
					}
					else 
					{
						// Flood Control
						
						$current_time = time();
						$query  = $db->sql_query("SELECT MAX(post_time) AS last_post_time FROM " . POSTS_TABLE . " WHERE poster_id = \"$user_id\"");
						$flood = $db->sql_fetchrow($query);
						if ( $flood['last_post_time'] > 0 && ( $current_time - $flood['last_post_time'] ) < $board_config['flood_interval'] )
						{
							echo $lang['wap_post_soon'] . ". ". $lang['wap_double_posting']; 
						}
						else
						{
							$txt = $txt." \n\n[i]" . trim($lang['wap_posted_from_a']) . " " . $phone ."[/i]";
							$uid = make_bbcode_uid();
							
   					 $post_details = insert_post($txt, $sub, $forum, $user_id, $username, 0); 
				     $topic = $post_details['topic_id'];
													
							echo sprintf($lang['wap_click_view_post']," <anchor>","<go href=\"" . append_sid("waptopic.$phpEx?topic=$topic&amp;forum=$forum") . "\"/></anchor>");
							
						}
					}
				}
			}
			
echo $footer;

?>