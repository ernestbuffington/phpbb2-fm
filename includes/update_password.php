<?php

if ( !defined('IN_PHPBB') )
{
   die("Hacking attempt");
}


if ( !defined('UPDATE_PASSWORD') )
{
	define('UPDATE_PASSWORD', true);
   	
   	global $db, $board_config, $lang;
	
   	if ($userdata['session_logged_in'])
   	{
    	$update_seconds = ($board_config['password_update_days'] * 24 * 3600);
      	$time_now = time();

      	$sql = "SELECT user_password, user_lastpassword, user_lastpassword_time
        	FROM " . USERS_TABLE . "
        	WHERE user_id = ". $userdata['user_id'];
      	if ( !($result = $db->sql_query($sql)) )
      	{
      		message_die(GENERAL_ERROR, 'Could not query profile information', '', __LINE__, __FILE__, $sql);
      	}
      	
      	$update_pass_needed = false;

      	while ($row = $db->sql_fetchrow($result))
      	{
        	if (strlen($row['user_lastpassword_time']) < 1 || strlen($row['user_lastpassword']) < 1  || $row['user_password'] === $row['user_lastpassword'] || $row['user_lastpassword_time'] < ($time_now - $update_seconds))
            {
            	$update_pass_needed = true;
            }
      	}
      	$db->sql_freeresult($result);

      	if ( $update_pass_needed )
      	{
         	if (!isset($HTTP_GET_VARS['update_password'])) 
         	{
           	   $redirect = 'profile.'.$phpEx.'?mode=editprofile&ucp=reg_info&update_password=true';
               	
               	$meta_redir = '<meta http-equiv="refresh" content="3;url=' . append_sid($redirect) . '">';
         		
         		$template->assign_vars(array(
            		'META' => $meta_redir)
         		);

         		message_die(GENERAL_MESSAGE, $lang['Error_password_update'] . $meta_redir);
           	}
      	}
   	}
}

?>