<?php

/**************************************************************************************************************************
 *                                                    phpbbWapGate
 **************************************************************************************************************************
 *										              wapheader.php
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

define('IN_PHPBB', true);

include('wapconfig.php');
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include('waplang.php'); 
require ($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include_once($phpbb_root_path . 'includes/functions_post.'.$phpEx);
include_once($phpbb_root_path . 'includes/functions_search.'.$phpEx);

header("Content-type: text/vnd.wap.wml");


//-------------------------------------------------------------------------------------------------------------------------
//---[ Here we check the nature and making of the phone / mobile device ]--------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------

$bbfile = "bbcode_wap.tpl";

// this tells us that we are accessing the forum through wap - used again in bbcode.php

global $wwap;
$wwap= 1;

// useful only if you want to render pictures 

global $isxml;
$isxml = 0;

if(stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml")  ||
	stristr($_SERVER["HTTP_USER_AGENT"],"WDG_Validator")     ||
	stristr($_SERVER["HTTP_USER_AGENT"],"W3C_Validator")     ||
	stristr($_SERVER["HTTP_USER_AGENT"],"W3C_CSS_Validator")) {
        if(preg_match("/application\/xhtml\+xml;q=0(\.[1-9]+)/i",$_SERVER["HTTP_ACCEPT"],$matches)) {
                $xhtml_q = $matches[1];
                if(preg_match("/text\/html;q=0(\.[1-9]+)/i",$_SERVER["HTTP_ACCEPT"],$matches)) {
                        $html_q = $matches[1];
                        if($xhtml_q >= $html_q) {
                        $ixml = 1;
                        }
                }
        } else {
                $isxml = 1;
                }
}


$uap = $_SERVER["HTTP_USER_AGENT"]; 

// setting the screen size in latexrender, or in any other places, to avoid creating unnecessary tables 

global $phone;
$phone = "mobile device";

// regular screen size for wap enabled mobiles is at least 96
$screensize = 93;

if ( stristr ($uap, "K700i")) { $phone = "Sony Ericsson K700i"; $screensize = 173; } 
elseif (stristr($uap, "Nokia8620")) { $phone = "Nokia 8620"; }
elseif (stristr ($uap, "Nokia6230")) { $phone = "Nokia 6230"; $screensize = 125; } 
elseif (stristr ($uap, "P800")) { $phone = "Sony Ericsson P800"; $screensize = 173;}
elseif (stristr ($uap, "P900")) { $phone = "Sony Ericsson P900"; $screensize  = 173;  } 
elseif (stristr ($uap, "T610")) { $phone = "Sony Ericsson T610"; $screensize = 125; } 
elseif (stristr ($uap, "T630")) { $phone = "Sony Ericsson T630"; $screensize = 125; } 
elseif (stristr ($uap, "Z1010")) { $phone = "Sony Ericsson Z1010"; $screensize = 173; } 
elseif (stristr ($uap, "WinWAP")) { $phone = "WinWAP Emulator"; $screensize = 2000;  $isxml = 1;}
elseif (stristr ($uap, "SAGEM-myX-5m")) { $phone = "Sagem myX-5m"; } 
elseif (stristr ($uap, "Nokia6600")) { $phone = "Nokia 6600"; $screensize = 173; } 
elseif (stristr ($uap, "Nokia")) { $phone = "Nokia mobile phone"; } 
elseif (stristr ($uap, "SonyE")) { $phone = "Sony Ericsson mobile phone"; }
elseif (stristr ($uap, "Samsung")) { $phone = "Samsung mobile phone"; } 
elseif (stristr ($uap, "Motorola" )) { $phone = "Motorola mobile phone" ; } 


$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
$title = (isset($title)) ? $title : $wapconfig['site_title'];


//-------------------------------------------------------------------------------------------------------------------------
//---[ Displaying forums links ]-------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------

$alllink = $many_forums ?  "<anchor>". $lang['wap_all'] . "<go href=\"" . append_sid("wapmisc.php?action=forumlist") . "\" /></anchor>" : ""; 
$aflink = $many_forums ? "&nbsp;<anchor>A-F<go href=\"" . append_sid("wapmisc.php?action=forumlist&amp;ltr=af") . "\" /></anchor>" : "";
$gklink = $many_forums ? "&nbsp;<anchor>G-K<go href=\"" . append_sid("wapmisc.php?action=forumlist&amp;ltr=gk") . "\" /></anchor>" : "";
$lolink = $many_forums ? "&nbsp;<anchor>L-O<go href=\"" . append_sid("wapmisc.php?action=forumlist&amp;ltr=lo") . "\" /></anchor>" : "";
$pzlink = $many_forums ? "&nbsp;<anchor>P-Z<go href=\"" . append_sid("wapmisc.php?action=forumlist&amp;ltr=pz") . "\" /></anchor><br/>" : "<br/>";



//-------------------------------------------------------------------------------------------------------------------------
//---[ Make Headers & Footers ]--------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------

$pm		 =	($userdata['user_new_privmsg'] != 0 || $userdata['user_unread_privmsg'] != 0)
				? "<anchor>" . $lang['wap_new_pm_alert'] . "<go href=\"" . append_sid("wappm.php") . "\" /></anchor><br/>"
				: "";
$header = "<?xml version=\"1.0\"?>";

// the line below stops the highlighting editor getting confused
// <?php

$header		.=	"<!DOCTYPE wml PUBLIC \"-//WAPFORUM//DTD WML 1.1//EN\" \"http://www.wapforum.org/DTD/wml_1.1.xml\">"; 
$header		.=	"\n<wml><head><meta forua=\"true\" http-equiv=\"Cache-Control\" content=\"max-age=0\"/></head>";
$header		.=	"<template>\n<do type=\"prev\" name=\"Previous\" label=\"Back\">\n<prev/>\n</do>\n";
$header		.=	"<do type=\"accept\" name=\"menu\" label=\"" . $lang['wap_menu'] . "\">\n<go href=\"" . append_sid("wapmenu.php") . "\"/>\n</do>";
$header		.=	"<do type=\"accept\" name=\"home\" label=\"" . $lang['wap_forum_home'] ."\">\n<go href=\"" . append_sid("wap.php") . "\"/>\n</do>\n</template>";
$header		.=	"\n<card id=\"forum\" title=\"$title\"><p>\n<i>$pm</i>";
$menu		 =	"<br/><anchor>" . $lang['wap_menu'] . "<go href=\"" . append_sid("wapmenu.php") . "\" /></anchor><br/>";
$page_showfooter = 1;

foreach ($footer_show as $val)
{
	if($_SERVER['PHP_SELF'] == $val)
	$page_showfooter = 0;
}
$footer = ($page_showfooter) ? $menu ."<anchor>" . $lang['wap_forum_home'] . "<go href=\"" . append_sid("wap.$phpEx") . "\" /></anchor></p></card></wml>" : "</p></card></wml>";

//-------------------------------------------------------------------------------------------------------------------------
//---[ Validate text to be displayed ]-------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------


function wap_validate($post_text,$smiles=1,$tags=1,$uid="",$quote=0)
{
	global $phpbb_root_path, $phpEx;
	// $post_text = str_replace("&","&amp;",$post_text); 
	if($tags)
	{
		$post_text = str_replace("\n","<br/>",$post_text); 
		if(!$quote)
		{
			$post_text = str_replace("<br/>"," *aaaa* ",$post_text); 
		}
	}
	if(!$quote)
	{
		$post_text = bbencode_second_pass($post_text,$uid);
	}
    // $post_text = stripslashes($post_text);
	// $post_text = strip_tags($post_text);
	$post_text = str_replace(" *aaaa* ","<br/>",$post_text); 
	if($smiles)
	{
		$post_text = wap_smilies_pass($post_text);
	}
	if($quote)
	{
		strip_tags($post_text);
		htmlentities($post_text);
		$post_text = str_replace("<br/>", "\n", $post_text);
		$post_text = str_replace(":" . $uid, "", $post_text);
	}
	return $post_text;
}

//-------------------------------------------------------------------------------------------------------------------------
//---[ Prepares wap post, old stuff, currently only used for editing posts ]-----------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------


function prepare_wap_post($txt,$uid)
{	
    $txt = addslashes(unprepare_message($txt));
    $txt = prepare_message(trim($txt), 1, 1, 1, $uid);
	return $txt;
}

//-------------------------------------------------------------------------------------------------------------------------
//---[ Smilies Pass ]------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------

function wap_smilies_pass($message) 
{ 
	static $orig, $repl; 
	if (!isset($orig)) 
	{ 
		global $db, $board_config, $url_path; 
		$orig = $repl = array(); 
		$sql = 'SELECT code, smile_url FROM ' . SMILIES_TABLE; 
		if( !$result = $db->sql_query($sql) ) 
		{ 
			echo $lang['wap_not_obtain_smiles'];
		} 
		$smilies = $db->sql_fetchrowset($result); 
		usort($smilies, 'wap_smiley_sort'); 
		for($i = 0; $i < count($smilies); ++$i) 
		{ 
			$orig[] = "/(?<=.\W|\W.|^\W)" . phpbb_preg_quote($smilies[$i]['code'], "/") . "(?=.\W|\W.|\W$)/"; 
			if($smilies[$i]['smile_url'] == "")
			{
				$repl[] = '***image***';
			}
			else {
				#  $repl[] = '***image***';
			$repl[] = '<img src="' . $url_path . '/' . $board_config['smilies_path'] . '/' . $smilies[$i]['smile_url'] . '" alt="' . $smilies[$i]['emoticon'] . '" />'; 
			}
		} 
	} 
	if (count($orig)) 
	{ 
		$message = preg_replace($orig, $repl, ' ' . $message . ' '); 
		$message = substr($message, 1, -1); 
	} 
	return $message; 
	}

	function wap_smiley_sort($a, $b)
	{
		if ( strlen($a['code']) == strlen($b['code']) )
		{
			return 0;
		}

		return ( strlen($a['code']) > strlen($b['code']) ) ? -1 : 1;
	}

//-------------------------------------------------------------------------------------------------------------------------
//---[ Insert posts into database - borrowed from neclectic ]--------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------

// prepared field for user choosing notifications options; currently disabled 

function insert_post( $message, $subject, $forum_id, $user_id, $user_name, $user_attach_sig, $topic_id = NULL, $topic_type = POST_NORMAL, $do_notification = false,    $notify_user = false, $current_time = 0, $error_die_function = '', $html_on = 1, $bbcode_on = 1, $smilies_on = 1 )

{
    global $db, $board_config, $user_ip;

    // initialise some variables

    $topic_vote = 0; 
    $poll_title = '';
    $poll_options = '';
    $poll_length = '';
    $mode = 'reply'; 

    $bbcode_uid = ($bbcode_on) ? make_bbcode_uid() : ''; 
    $error_die_function = ($error_die_function == '') ? "message_die" : $error_die_function;
    $current_time = ($current_time == 0) ? time() : $current_time;
    
    // parse the message and the subject

    $message = addslashes(unprepare_message($message));
    $message = prepare_message(trim($message), $html_on, $bbcode_on, $smilies_on, $bbcode_uid);
    $subject = addslashes(unprepare_message(trim($subject)));
    $username = addslashes(unprepare_message(trim($user_name)));
    
    // fix for \" in username 
    $username = str_replace("\\\"","\"", $username);    
    
    // if this is a new topic then insert the topic details

    if ( is_null($topic_id) )
    {
        $mode = 'newtopic'; 
        $sql = "INSERT INTO " . TOPICS_TABLE . " (topic_title, topic_poster, topic_time, forum_id, topic_status, topic_type, topic_vote) VALUES ('$subject', " . $user_id . ", $current_time, $forum_id, " . TOPIC_UNLOCKED . ", $topic_type, $topic_vote)";
        if ( !$db->sql_query($sql, BEGIN_TRANSACTION) )
        {
            $error_die_function(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
        }
        $topic_id = $db->sql_nextid();
    }

    // insert the post details using the topic id

    $sql = "INSERT INTO " . POSTS_TABLE . " (topic_id, forum_id, poster_id, post_username, post_time, poster_ip, enable_bbcode, enable_html, enable_smilies, enable_sig) VALUES ($topic_id, $forum_id, " . $user_id . ", '$username', $current_time, '$user_ip', $bbcode_on, $html_on, $smilies_on, $user_attach_sig)";
    if ( !$db->sql_query($sql, BEGIN_TRANSACTION) )
    {
        $error_die_function(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
    }
    $post_id = $db->sql_nextid();
    
    $post_extra = $db->sql_query("select post_extra  from " . POSTS_TEXT_TABLE ); 
	
	// this checks if there is an extra field in post, and if there is does the appropriate changes 
	
	if ( $post_extra == "" ) 
	
	{ 
	$sql = "INSERT INTO " . POSTS_TEXT_TABLE . " (post_id, post_subject, bbcode_uid, post_text) VALUES ($post_id, '$subject', '$bbcode_uid', '$message')";
		if ( !$db->sql_query($sql, BEGIN_TRANSACTION) )
		{
        $error_die_function(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
		}
    
	} 

	else 
	
	{ 
	$sql = "INSERT INTO " . POSTS_TEXT_TABLE . " (post_id, post_subject, post_extra, bbcode_uid, post_text) VALUES ($post_id, '$subject', NULL,  '$bbcode_uid', '$message')";
       if ( !$db->sql_query($sql, BEGIN_TRANSACTION) )
       {
        $error_die_function(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
       }
    } 

	
	// update the post counts

	$newpostsql = ($mode == 'newtopic') ? ',forum_topics = forum_topics + 1' : '';
    $sql = "UPDATE " . FORUMS_TABLE . " SET 
                forum_posts = forum_posts + 1,
                forum_last_post_id = $post_id
                $newpostsql 	
            WHERE forum_id = $forum_id";
    if ( !$db->sql_query($sql, BEGIN_TRANSACTION) )
    {
        $error_die_function(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
    }
    
    // update the first / last post ids for the topic

    $first_post_sql = ( $mode == 'newtopic' ) ? ", topic_first_post_id = $post_id  " : ' , topic_replies=topic_replies+1'; 
    $sql = "UPDATE " . TOPICS_TABLE . " SET 
                topic_last_post_id = $post_id 
                $first_post_sql
            WHERE topic_id = $topic_id";
    if ( !$db->sql_query($sql, BEGIN_TRANSACTION) )
    {
        $error_die_function(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
    }
    
    // update the user's post count and commit the transaction

    $sql = "UPDATE " . USERS_TABLE . " SET 
                user_posts = user_posts + 1
            WHERE user_id = $user_id";
    if ( !$db->sql_query($sql, END_TRANSACTION) )
    {
        $error_die_function(GENERAL_ERROR, 'Error in posting', '', __LINE__, __FILE__, $sql);
    }
    
    // add the search words for our new post

    switch ($board_config['version'])
    {
        case '.0.0' : 
        case '.0.1' : 
        case '.0.2' : 
        case '.0.3' : 
            add_search_words($post_id, stripslashes($message), stripslashes($subject));
            break;
        
        default :
            add_search_words('', $post_id, stripslashes($message), stripslashes($subject));
            break;
    }
    
    // do we need to do user notification

    if ( ($mode == 'reply') && $do_notification )
    {
        $post_data = array();
        user_notification($mode, $post_data, $subject, $forum_id, $topic_id, $post_id, $notify_user);
    }
    
    // if all is well then return the id of our new post

    return array('post_id'=>$post_id, 'topic_id'=>$topic_id);
}

?>