<?php

/**************************************************************************************************************************
 *                                                    phpbbWapGate
 **************************************************************************************************************************
 *														 wap.php
 *									              --------------------
 *											 version 2.0 - modified 12.09.04 
 **************************************************************************************************************************
 *														AUTHORS:
 *
 *									Chirs Gray								  Valentin Vornicu
 *							   info@plus-media.co.uk						valentin@mathlinks.ro
 *							    www.plus-media.co.uk			              www.mathlinks.ro
 * 
 *************************************************************************************************************************/


require 'wapheader.php';
echo($header);

// new algorithm for getting the topics has been implemented since version 1.0

$i = 0;
$topics = array();
$start = ((!isset($_GET['start'])) || (!is_numeric($_GET['start']))) ? "0" : $_GET['start'];
$user_forum = (!isset($_GET['forum'])) ? "-1" : $_GET['forum'];
if($user_forum != "-1")
{
	$sql = "select forum_name from " . FORUMS_TABLE . " where forum_id = \"$user_forum\"";
	$query = $db->sql_query($sql);
        if ($forum_name = $db->sql_fetchrowset($query))
        {
                $auth = auth(AUTH_VIEW, $user_forum, $userdata);
                if($auth['auth_view'])
                {
						$forum_header = sprintf($lang['wap_viewing_posts'],"<b>" . str_replace("&","&amp;",$forum_name[0][0]) . "</b>\n<br/>");
                        $forum_where = " where forum_id = \"$user_forum\"";
                }
                else
                {
                        echo $lang['wap_not_authorised_forum'];
                        exit(" " . $footer);
                }
         }
         else
         {	
         	echo $lang['wap_forum_not_exist'];
                exit(" " . $footer);
         }
}
else 
{
	$forum_header = "";
        $forum_where = "";
}
$i = 0;

// here we obtain topics from all forums, ordered by last post time 

if ($user_forum == "-1") { $sql = "SELECT t.topic_id, t.topic_last_post_id, t.forum_id, p.post_id, p.post_time FROM " . TOPICS_TABLE . " AS t, " . POSTS_TABLE . " AS p WHERE t.topic_status <> 2 AND p.post_id = t.topic_last_post_id ORDER BY p.post_id DESC LIMIT " .$maxtopics; } 

// and here we obtain topics from one specific forum

else { 
$sql = "SELECT t.topic_id, t.topic_last_post_id, t.forum_id, p.post_id, p.post_time FROM " . TOPICS_TABLE . " AS t, " . POSTS_TABLE . " AS p WHERE t.forum_id = \"$user_forum\" AND t.topic_status <> 2 AND p.post_id = t.topic_last_post_id ORDER BY p.post_id DESC LIMIT  " .$maxtopics; } 

$query = $db->sql_query($sql);
$latestposts = $db->sql_fetchrowset($query);

$num_rows = $db->sql_numrows($query);
if($num_rows == 0)
{
	echo $lang['wap_no_posts'];
	echo $footer;
	exit("<br/><br/>" . $footer);
}
foreach($latestposts as $val)
{
	$tid = $val['topic_id'];
	if(preg_match("/\b$tid\b/","$list"))
	{
		continue;
	}
	$auth = auth(AUTH_READ,$val['forum_id'],$userdata);
	if($auth['auth_read'] == "1")
	{
		$sql = "select topic_title, topic_moved_id, topic_replies from " . TOPICS_TABLE . " where topic_id = \"".$val['topic_id']."\"";
			
		$query = $db->sql_query($sql);
		$topic_title = $db->sql_fetchrow($query);
		if(empty($topic_title['topic_moved_id']))
		{
			// if all is well, add the text to the topic

			$topics[] = "<anchor>". str_replace ("&","&amp;",wap_validate($topic_title['topic_title'],0,0)) ."<go href=\"" . append_sid("waptopic.$phpEx?s=0&amp;topic=".$val['topic_id']."&amp;forum=".$val['forum_id']) . "\" /></anchor> (".$topic_title['topic_replies'].")<br/>\n";
			$list .= $val['topic_id']."|";
			$i++;
		}
	}

}
$list = explode("|",$list);
$list = count($list);
if($list == 1)
{
	echo $lang['wap_no_posts'];
	exit("<br/><br/>" . $footer);
}

//-------------------------------------------------------------------------------------------------------------------------
//---[ build navigation ]--------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------

$final = $i;
$next = $start;
$prev = $start;
$next = $next + 10;
$prev = $prev - 10;

if($start == 0)
{
	if($i > $next)
	{
		$nav = "<anchor>" . ucwords($lang['wap_next']) . "&gt;&gt;<go href=\"" . append_sid("wap.php?start=$next&amp;forum=$user_forum") . "\" /></anchor>";
	}
}

else
{
	if($i > $next)
	{
		$nav = "<anchor>&lt;&lt;" . ucwords($lang['wap_prev']) . "<go href=\"" . append_sid("wap.php?start=$prev&amp;forum=$user_forum") . "\" /></anchor> <anchor>" . ucwords($lang['wap_next']) . "&gt;&gt;<go href=\"" . append_sid("wap.php?start=$next&amp;forum=$user_forum") . "\" /></anchor>";
	}
	else
	{
		$nav = "<anchor>&lt;&lt;" . ucwords($lang['wap_prev']) . "<go href=\"" . append_sid("wap.php?start=$prev&amp;forum=$user_forum") . "\" /></anchor>";
	}
}

//-------------------------------------------------------------------------------------------------------------------------
//---[ build the page which the user is viewing ]--------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------


$i = 0;
echo $forum_header;
foreach($topics as $key => $val)
{
	if($i == 10)
	{
		break;
	}
	if($start != 0)
	{
		if($key < $start)
		{
			continue;
		}
	}
	$post_text = $val;
	$post_text = preg_replace('/\:[0-9a-z\:]+\]/si', ']', $post_text);
	$post_text = stripslashes($post_text);
	echo $post_text;
	$i++;
}
echo "<br/>";
echo $nav;
echo "<br/>";
echo $footer;

?>