<?php

/**************************************************************************************************************************
 *                                                    phpbbWapGate
 **************************************************************************************************************************
 *										               wapedit.php
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

$answer = (empty($_POST['re'])) ? "" : $_POST['re'];
$post_id = (!isset($_GET['post'])) ? "0" : $_GET['post'];

echo $header;

	?> 
		<do name="accptedit" type="accept" label="Reset Vars">
		<refresh>
		<setvar name="re" value=""/>
		</refresh>
		</do>

	<?php

if($answer == "")
{
	
	$query = $db->sql_query("select post_text, bbcode_uid from " . POSTS_TEXT_TABLE . " where post_id = \"$post_id\"");
	$post_text = $db->sql_fetchrow($query);
	$post = $post_text['post_text'];
	$post = wap_validate($post,0,1,$post_text['bbcode_uid'],1);
	$post_text = $post;
	$post_text = htmlentities($post_text); 
	$post_text = str_replace ("&amp;","&",$post_text);
	// deletes the posted from other mobile 

	$post_text = preg_replace("/\[i\]" . $lang['wap_posted_from_a']. "(.*)\[\/i\]/","",$post_text);
	$post_text = trim($post_text);

	//	$post_text = str_replace ("\\","\\\\",$post_text); 

?><?php echo $lang['wap_edit']; ?>: <input name="re" emptyok="false" maxlength="999" value="<?php echo $post_text; ?>"/><br/>
<anchor>
	<?php echo $lang['wap_post']; ?>
	<go href="<?php echo append_sid("wapedit.php?re=$(re)&amp;post=$post_id"); ?>" method="post">
		<setvar name="re" value=""/> 
		<postfield name="re" value="$(re)"/>
	</go>
</anchor>
<br/>


<?php  echo $lang['wap_reset_vars']; 
}
else
{
	
	// fixes quote issues
	$answer = str_replace("[quote=" . $username . "]", "[quote=\\\"" . $username . "\\\"]", $answer);
	
	// fixes &amp; problem when editing messages 
	
	$answer = str_replace ("&amp;","*amp*",$answer);
	$answer = str_replace ("&quot;","*quot*",$answer);
	$answer = str_replace ("&lt;","*lt*",$answer);
	$answer = str_replace ("&gt;","*gt*",$answer);
	$answer = str_replace ("&","&amp;",$answer);
	$answer = str_replace ("*amp*","&amp;",$answer);
	$answer = str_replace ("*quot*","&quot;",$answer);
	$answer = str_replace ("*lt*","&lt;",$answer);
	$answer = str_replace ("*gt*","&gt;",$answer);

	// fixes the <,> and \ problems when editing messages 

	$answer = str_replace ("<","&lt;",$answer);
	$answer = str_replace (">","&gt;",$answer);

	$time = time();
	$uid = make_bbcode_uid();

	$answer = $answer."\n\n[i]". $lang['wap_posted_from_a']. " ". $phone . "[/i]";
	$answer = prepare_wap_post($answer,$uid);
	$update_posts = $db->sql_query("update " . POSTS_TEXT_TABLE . " set post_text = \"$answer\", bbcode_uid =\"$uid\" where post_id = $post_id");
	$update_text = $db->sql_query("update " . POSTS_TABLE . " set post_edit_time = \"$time\", enable_bbcode = \"1\", enable_html = \"1\", post_edit_count = post_edit_count +1 where post_id = $post_id");
	
	$last = $db->sql_query("select p.topic_id, t.topic_replies, t.forum_id from " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p where p.post_id = $post_id and t.topic_id = p.topic_id");
	$last = $db->sql_fetchrow($last);
	$start = $last['topic_replies'];
	$topic = $last['topic_id'];
	$forum_id = $last['forum_id'];
	echo $lang['wap_edit_ok']. "."; 
	echo "<br/>";
	echo sprintf($lang['wap_click_view_post']," <anchor>","<go href=\"" . append_sid("waptopic.$phpEx?s=$start&amp;topic=$topic&amp;forum=$forum_id&amp;last=1") . "\"/></anchor>");
}

echo $footer;

?>