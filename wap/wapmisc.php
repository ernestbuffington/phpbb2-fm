<?php

/**************************************************************************************************************************
 *                                                    phpbbWapGate
 **************************************************************************************************************************
 *										               wapmisc.php
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

$action = (isset($_GET['action'])) ? $_GET['action'] : "forumlist";
$u = (empty($_GET['u'])) ? 0 : str_replace("\'", "''", $_GET['u']);

// if $many_forums then we try to split them up 

$ltr = (isset($_GET['ltr'])) ? $_GET['ltr'] : "all"; 


// depending on the action asked, we do different stuff 

switch ($action)
{

//-------------------------------------------------------------------------------------------------------------------------
//---[ View Who's Online ]-------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------

case 'online' :

		require 'waplang.php';
		$title = $lang['wap_users_online'];
		
		require 'wapheader.php';
		echo $header;
		$i = 0;

		$query = $db->sql_query("SELECT u.user_id, u.username, u.user_allow_viewonline, u.user_level, s.session_logged_in, s.session_time, s.session_page, s.session_ip
			FROM ".USERS_TABLE." u, ".SESSIONS_TABLE." s
			WHERE u.user_id = s.session_user_id
				AND s.session_time >= ".( time() - ($board_config['whosonline_time'] * 60) ) . "
			ORDER BY u.username ASC, s.session_ip ASC");

		$users = $db->sql_fetchrowset($query);
		
		echo "<b>". $lang['wap_registred_members'] .":</b><br/>";
		
		foreach ($users as $key => $val)
		{
			if(preg_match("/\b" . $val['username'] . "\b/","$user"))
			{
				continue;
			}
			if($val['user_allow_viewonline'] && $val['user_id'] != ANONYMOUS)
			{
				echo "<anchor>" . $val['username'] . "<go href=\"" . append_sid("wapmisc.$phpEx?action=profile&amp;u=" . $val['user_id']) . "\" /></anchor><br/>";
				$user .= "|" . $val['username'];
				++$i;
			}
		}
		
		if($i == 0)
		{
			echo $lang['wap_no_reg_users_online'];
		}

		echo "<br /><b>". $lang['wap_anonymous_users']. ":</b><br />";
		$i = 0;
		foreach ($users as $key => $val)
		{
			if(preg_match("/\b" . $val['userip'] . "\b/","$user"))
			{
				continue;
			}
			if($val['user_id'] == ANONYMOUS)
			{
				++$i;
				$user .= "|" . $val['userip'];
			}
		}
		echo $i;
		echo "<br/><b>". $lang['wap_hidden_users'] .":</b><br />";
		$i = 0;
		foreach ($users as $key => $val)
		{
			if(preg_match("/\b" . $val['username'] . "\b/","$user"))
			{
				continue;
			}
			if(!$val['user_allow_viewonline'] && $val['user_id'] != ANONYMOUS)
			{
				++$i;
				$user .= "|" . $val['username'];
			}
		}
		echo $i;
	
	break;

//-------------------------------------------------------------------------------------------------------------------------
//---[ View Profile ]------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------

case 'profile' :
	

		require 'waplang.php';
		$title = $lang['wap_user_profile'];
		
		require 'wapheader.php';

		

		echo $header;
		$query = $db->sql_query("select * from " . USERS_TABLE . " where user_id = \"$u\" limit 1");
		$user_details = $db->sql_fetchrow($query);
		
		
		// if the flag hack exists, it also displays the flag, if the phone $isxml - this should basically work with any board

		if ($isxml ) { $flag = ( $user_details['user_from_flag'] && ($user_details['user_from_flag']!="blank.gif") && $user_details['user_id'] != ANONYMOUS ) ? "<br/><b>". $lang['wap_country'].": </b>".  str_replace (".gif","&nbsp;", ucfirst ($user_details['user_from_flag'])) . "<img src=\"../images/flags/" . $user_details['user_from_flag'] . "\" alt=\"" . $user_details['user_from_flag'] . "\" width=\"32\" height=\"20\" />" : "";		
					}
		else {  $flag = ( $user_details['user_from_flag'] && ($user_details['user_from_flag']!="blank.gif") && $user_details['user_id'] != ANONYMOUS ) ? "<br/><b>". $lang['wap_country'].": </b>".  str_replace (".gif","&nbsp;", ucfirst ($user_details['user_from_flag'])) : "";	}

		
		echo "<b>". $lang['wap_username']. ": </b>" . wap_validate($user_details['username']);
		echo "<br/><b>". $lang['wap_joined']. ": </b>" . create_date($lang['DATE_FORMAT'], $user_details['user_regdate'], $board_config['board_timezone']);
		echo "<br/><b>". $lang['wap_no_of_posts']. ": </b>" . $user_details['user_posts'];
		echo "<br/><b>". $lang['wap_location']. ": </b>" . wap_validate($user_details['user_from']);

		// here the flag is displayed, if it exists obviously 

		echo $flag; 
		
		echo "<br/><b>". $lang['wap_website']. ": </b>" . wap_validate($user_details['user_website']);
		echo "<br/><b>". $lang['wap_occupation']. ": </b>" . wap_validate($user_details['user_occ']);
		echo "<br/><b>". $lang['wap_interests']. ": </b>" . wap_validate($user_details['user_interests']);
		
		if($user_details['user_viewemail'])
		{
			echo "<br/><b>Email: </b> " . wap_validate($user_details['user_email']);
		}
		
		echo "<br/><b>MSN: </b> " . wap_validate($user_details['user_msnm']);
		echo "<br/><b>Yahoo: </b> " . wap_validate($user_details['user_yim']);
		echo "<br/><b>AIM: </b> " . wap_validate($user_details['user_aim']);
		echo "<br/><b>ICQ</b> " . wap_validate($user_details['user_icq']);
		
		echo "<br/><anchor>" . $lang['wap_send_user_pm']. "<go href=\"" . append_sid("wappm.$phpEx?action=new&amp;username=" . $user_details['username'] . "") . "\" /></anchor>";
	
	break;

//-------------------------------------------------------------------------------------------------------------------------
//---[ View Forum List ]---------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------

case 'forumlist' :
        
    
			    require 'waplang.php';
			
				$title = $lang['wap_forum_list'];
            
				require 'wapheader.php';
			
				
				echo $header;
		
				

		switch ($ltr) {
				
				case 'af' : $query = $db->sql_query("select forum_name, forum_id from " . FORUMS_TABLE . " WHERE forum_id > 0 AND forum_name REGEXP '^[a-f]' order by forum_name ASC");
							$toprint = $alllink . "&nbsp;<b>A-F</b>". $gklink . $lolink . $pzlink; 
	

						  break; 

				case 'gk' : $query = $db->sql_query("select forum_name, forum_id from " . FORUMS_TABLE . " WHERE forum_id > 0 AND forum_name REGEXP '^[g-k]' order by forum_name ASC");
							$toprint = $alllink . $aflink . "&nbsp;<b>G-K</b>" . $lolink . $pzlink; 
						  break; 

				case 'lo' : $query = $db->sql_query("select forum_name, forum_id from " . FORUMS_TABLE . " WHERE forum_id > 0 AND forum_name REGEXP '^[l-o]' order by forum_name ASC");
							$toprint = $alllink . $aflink . $gklink ."&nbsp;<b>L-O</b>"  . $pzlink; 
						  break; 

				case 'pz' : $query = $db->sql_query("select forum_name, forum_id from " . FORUMS_TABLE . " WHERE forum_id > 0 AND forum_name REGEXP '^[p-z]' order by forum_name ASC");
							$toprint = $alllink . $aflink . $gklink . $lolink  . "&nbsp;<b>P-Z</b><br/>"; 
						  break; 
						  
				default : 	$query = $db->sql_query("select forum_name, forum_id from " . FORUMS_TABLE . " WHERE forum_id > 0 order by forum_name ASC");
							$toprint = $many_forums ? "<b>". $lang['wap_all']. "</b>" .   $aflink . $gklink . $lolink  . $pzlink : ""; 
							
					  } 


		// we build letter navigation device 
		
		echo $toprint; 

		// we continue to build the list of forums normally 
		

		
				$forum = $db->sql_fetchrowset($query);   
				
		// if we have a go, that is there are forums 
		
			if (isset($forum))  
		{			echo $lang['wap_choose_forum']. ":<br/>\n";

                foreach ($forum as $key => $val)
                {
			$auth = auth(AUTH_VIEW,$val['forum_id'],$userdata);
			if(!$auth['auth_view'])
			{
                		continue;
			}
			else
			{
				echo "<anchor>" . str_replace("&","&amp;",wap_validate($val['forum_name'])) . "<go href=\"" . append_sid("wapmisc.$phpEx?action=forum&amp;forum=" . $val['forum_id']) . "\" /></anchor><br/>\n";
			}
                 } 

		}

		// else we make an error message 
		
		elseif ($many_forums) {  echo $lang['wap_no_forums_cat']. "<br/>"; } 
		else { echo $lang['wap_no_forums']; } 

        
        break;
	
//-------------------------------------------------------------------------------------------------------------------------
//---[ View Forum ]--------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------

case 'forum' :

				require 'waplang.php';
				
				$title = $lang['wap_forum_details'];

        	    require 'wapheader.php';
				
                echo $header;
		$forum_id = (empty($_GET['forum'])) ? "0" : str_replace("\'", "''", $_GET['forum']);
		$auth = auth(AUTH_VIEW,$forum_id,$userdata);
		if(!$auth['auth_view'])
		{
			echo $lang['wap_cannot_view_forum']. "<br/>";
			exit (" " . $footer);
		}
		$query = $db->sql_query("select forum_name,forum_desc from " . FORUMS_TABLE . " where forum_id = $forum_id");
		$forum_info = $db->sql_fetchrow($query);

		echo "<b>" . $lang['wap_name'] . ": </b> " . str_replace("&","&amp;",wap_validate($forum_info['forum_name']));
		echo "<br/><b>" . $lang['wap_description'] . ": </b> " . wap_validate($forum_info['forum_desc']);
		echo "<br/><anchor>".$lang['wap_view_posts_from_this_forum_only'] ."<go href=\"" . append_sid("wap.php?forum=$forum_id") . "\" /></anchor>";
	break;
	
}
echo "<br/>";

echo $footer;

?>