<?php

/**************************************************************************************************************************
 *                                                    phpbbWapGate
 **************************************************************************************************************************
 *										               waptopic.php
 *									              --------------------
 *											   version 2.1 - modified 13.09.04 
 **************************************************************************************************************************
 *														AUTHORS:
 *
 *									Chirs Gray								  Valentin Vornicu
 *							   info@plus-media.co.uk						valentin@mathlinks.ro
 *							    www.plus-media.co.uk			              www.mathlinks.ro
 * 
 *************************************************************************************************************************/



require 'wapheader.php';

$topic = (empty($_GET['topic'])) ? "-1" : $_GET['topic'];
$start = (empty($_GET['s'])) ? "0" : $_GET['s'];
$forum_id = (empty($_GET['forum'])) ? "0" : $_GET['forum'];

$go = ($topic == -1) ? ("Sorry, that thread does not exist.") : ("1");
echo($header);
$i = 0;

if($go != 1)
{
	echo($go);
}

$auth = auth(AUTH_ALL,$forum_id,$userdata);
if($auth['auth_read'] != 1)
{
	echo $lang['wap_cannot_view_topic']. "<br/>";
}

else {
	$query = $db->sql_query("select forum_id, forum_name from " . FORUMS_TABLE . " where forum_id = \"$forum_id\"");
	$forum = $db->sql_fetchrow($query);
	$forum = wap_validate($forum['forum_name'],0,0);
	$query = $db->sql_query("select * from " . TOPICS_TABLE . " where topic_id = \"$topic\" and forum_id = \"$forum_id\"");
	$topicinfo = $db->sql_fetchrow($query);
	$query = $db->sql_query("select * from " . POSTS_TABLE . " where topic_id = \"$topic\" and forum_id = \"$forum_id\" order by post_id ASC limit $start,1");
	$post = $db->sql_fetchrow($query);
	$query = $db->sql_query("select * from " . POSTS_TEXT_TABLE . " where post_id = \"".$post['post_id']."\"");
	$posttext = $db->sql_fetchrow($query);
	if(!empty($topicinfo['topic_moved_id']))
	{
		$query = $db->sql_query("select forum_id from " . TOPICS_TABLE . " where topic_id = \"".$topicinfo['topic_moved_id']."\"");
		$forum_id = $db->sql_fetchrow($query);
		echo $lang['wap_thread_moved']. ". ". ucwords($lang['wap_click']) . " <anchor>" . strtolower($lang['wap_here']) . "<go href=\"" . append_sid("waptopic.php?start=$start&amp;topic=".$topicinfo['topic_moved_id']."&amp;forum=".$forum_id['forum_id']) . "\" /></anchor> ". strtolower($lang['wap_to_move_to_correct_thread']) .".<br/>";
	}
	if(empty($posttext['post_text'])) {
		echo $lang['wap_nonexistent_post']. ".<br/>";
	}
	else {
				$query = $db->sql_query("update " . TOPICS_TABLE . " set topic_views = topic_views+1 where topic_id = \"$topic\"");
				$query = $db->sql_query("select user_id, username,user_posts from " . USERS_TABLE . " where user_id = \"".$post['poster_id']."\"");
				$user = $db->sql_fetchrow($query);
				$posts = ($user['user_posts'] == "1") ? ("post") : ("posts");
				$noposts = ($post['poster_id'] != -1) ? ( "(".$user['user_posts']." $posts)") : "";
				$next = $start;
				$prev = $start;
				++$next;
				--$prev;
				$topic_replies = $topicinfo['topic_replies'];
				if($start == 0)
				{
					if($topic_replies != 0)
					{
						$nav = "<anchor>" . ucwords($lang['wap_next']). "&gt;&gt;<go href=\"" . append_sid("waptopic.$phpEx?s=$next&amp;topic=$topic&amp;forum=$forum_id") . "\" /></anchor>";
					}
				}
				else
				{
					++$topic_replies;
					if($next >= $topic_replies)
					{
						$nav = "<anchor>&lt;&lt;" . ucwords($lang['wap_prev']). "<go href=\"" . append_sid("waptopic.$phpEx?s=$prev&amp;topic=$topic&amp;forum=$forum_id") . "\" /></anchor>";
					}
					else 
					{
						$nav = "<anchor>&lt;&lt;" . ucwords($lang['wap_prev']). "<go href=\"" . append_sid("waptopic.$phpEx?s=$prev&amp;topic=$topic&amp;forum=$forum_id") . "\" /></anchor>&nbsp;<anchor>" . ucwords($lang['wap_next']). "&gt;&gt;<go href=\"" . append_sid("waptopic.$phpEx?s=$next&amp;topic=$topic&amp;forum=$forum_id") . "\" /></anchor>";
					}
					--$topic_replies;
				}
				$nav .= "<br/><anchor>" . $lang['wap_last_post'] . "<go href=\"" . append_sid("waptopic.php?s=$topic_replies&amp;topic=$topic&amp;forum=$forum_id") . "\" /></anchor>";
				$post_text = $posttext['post_text'];
				
				$post_text = wap_validate($post_text,1,1,$posttext['bbcode_uid']);

				// makes the < bug disappear 
				
				$post_text = str_replace ("<table","*tttt*",$post_text);
				$post_text = str_replace ("<go","*gggg*",$post_text);
				$post_text = str_replace ("<tr","*trtt*",$post_text);
				$post_text = str_replace ("<td","*tdtt*",$post_text);
			    $post_text = str_replace ("<img","*mmmm*",$post_text);
				$post_text = str_replace ("<b","*bbbb*",$post_text);
				$post_text = str_replace ("<i","*iiii*",$post_text);
				$post_text = str_replace ("<u","*uuuu*",$post_text);
				$post_text = str_replace ("<anchor","*aaaaaa*",$post_text);
				$post_text = str_replace ("</","*zzzz*",$post_text);
				$post_text = str_replace ("<","&lt;",$post_text);
				$post_text = str_replace ("*mmmm*","<img",$post_text); 
				$post_text = str_replace ("*aaaaaa*","<anchor",$post_text);
				$post_text = str_replace ("*iiii*","<i",$post_text);
				$post_text = str_replace ("*bbbb*","<b",$post_text);
				$post_text = str_replace ("*uuuu*","<u",$post_text);
				$post_text = str_replace ("*zzzz*","</",$post_text); 
				$post_text = str_replace ("*tttt*","<table",$post_text);
				$post_text = str_replace ("*trtt*","<tr",$post_text);
				$post_text = str_replace ("*gggg*","<go",$post_text);
				$post_text = str_replace ("*tdtt*","<td",$post_text);
								

//-------------------------------------------------------------------------------------------------------------------------
//---[ BEGIN, url hacks ]--------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------
			
				// this correctly renders url-s in posts - a little tweak is necessar to make it ok for the mod 

				preg_match_all ("/\/viewtopic.php\?t=([0-9]*)/",$post_text,$matches);
				

				$match = $matches[1]; 
				$matches = $matches[0]; 
				foreach ($match as $key => $value) 
				 {   
					$query = $db->sql_query("select topic_id, forum_id from " . TOPICS_TABLE . " where topic_id = \"$value\"");
					$url_id = $db->sql_fetchrow($query);
					$forum_id = $url_id['forum_id'];
					$replacement = $wapdir . "/" . append_sid("waptopic.$phpEx?s=0&amp;topic=". $value. "&amp;forum=" . $forum_id); 
					$needle =  $matches[$key]. "/"; 
					$needle = str_replace ("?","\?",$needle);
					$post_text = preg_replace($needle, $replacement, $post_text,1); 
					
				 }

     			// this further more renders url-s that are not caught between bbtags, but preserves the already correct rendered ones 

				$post_text = str_replace ("href=\"wap","*hwap*",$post_text);
				$post_text = str_replace("href=\"http://","*http*",$post_text); 
				$post_text = str_replace("href=\"www","*www*",$post_text);
				$post_text = str_replace("src=\"http://","*srchttp*",$post_text); 
				$post_text = str_replace("src=\"www","*srcwww*",$post_text);
				
				// this deals with [url]http://www.yoursite.com[/url] tags
				
				$post_text = str_replace("<anchor>*#desc#*www","<anchor>*#descwww#*",$post_text);
				$post_text = str_replace("<anchor>*#desc#*http://","<anchor>*#deschttp#*",$post_text);

				// main transform string 
				
				$post_text = preg_replace("/(http:\/\/[0-9a-zA-Z\.\/?=&;%]*)/","<anchor>\$1<go href=\"\$1\"/></anchor>",$post_text); 
				
				// this transforms back the already converted [url]http://www.yoursite.com[/url] tags
					
				$post_text = str_replace("<anchor>*#deschttp#*","<anchor>http://",$post_text);
				$post_text = str_replace("<anchor>*#descwww#*","<anchor>www",$post_text);
				
				$post_text = str_replace("*srcwww*","src=\"www",$post_text);
				$post_text = str_replace("*srchttp*","src=\"http://",$post_text);				
				$post_text = str_replace("*www*","href=\"www",$post_text);
				$post_text = str_replace("*http*","href=\"http://",$post_text);
				$post_text = str_replace("*hwap*","href=\"wap",$post_text);

				
//-------------------------------------------------------------------------------------------------------------------------
//---[ END, url hacks ]----------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------
				


				// this line solves the problem where the $ sign is mistakenly taken as start php variable 

				$post_text = str_replace ("$", "&#36;", $post_text); 
				
//-------------------------------------------------------------------------------------------------------------------------
//---[ Now Write out the content ]-----------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------

				echo("<b> ".$topicinfo['topic_title']."</b><br/>" . ucwords($lang['wap_by']) . " ");
				if($user['username'] != "Anonymous")
				{
				echo("<anchor>" . $user['username'] . "<go href=\"" . append_sid("wapmisc.$phpEx?action=profile&amp;u=" . $user['user_id'] . "") . "\" /></anchor>");
				}
				else
				{
					echo $user['username'];
				}
				echo (" ".$noposts." on ");
				echo create_date($board_config['default_dateformat'], $post['post_time'], $board_config['board_timezone']);
				
				// fixes &amp; problem if it appears in the $forum variable
				
				$forum = str_replace ("&","&amp;",$forum); 
				
				// also a more useful link directly to the posts in forums 

				echo " ". strtolower($lang['wap_to']) . " " . strtolower($lang['wap_forum']) . " <anchor>$forum<go href=\"" . append_sid("wap.$phpEx?forum=$forum_id") . "\" /></anchor><br/><br/>";

				echo $post_text; 
				
				// build navigation in topic 
				
				echo "<br/>";
				echo "<br/><br/>". $nav;
				$edit = ($post['poster_id'] == $userdata['user_id']) ? "/<anchor>" . ucwords($lang['wap_edit']) . "<go href=\"" . append_sid("wapedit.$phpEx?post=" . $post['post_id']) . "\" /></anchor>" : "";
				echo "<br/><anchor>" . ucwords($lang['wap_reply']). "<go href=\"" . append_sid("wapreply.$phpEx?topic=$topic&amp;forum=$forum_id") . "\" /></anchor>/<anchor>" . ucwords($lang['wap_quote']). "<go href=\"" . append_sid("wapreply.$phpEx?topic=$topic&amp;forum=$forum_id&amp;quote=1&amp;post=" . $post['post_id']) . "\" /></anchor>$edit";
				}
}

echo($footer);

?>