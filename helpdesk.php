<?php
/** 
*
* @package phpBB2
* @version $Id: helpdesk.php,v 1.0.0 2004 austin Exp $
* @copyright (c) 2003 aUsTiN-Inc
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

// include language file
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_helpdesk.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_helpdesk.' . $phpEx);

//
// Start session management 
//
$userdata = session_pagestart($user_ip, PAGE_HELPDESK);
init_userprefs($userdata);
//
// End session management 
//
	
if ( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
}
else
{
	$mode = '';
}
	
		
if( $mode != 'send_this' )
{
	//
	// VIP Code
	//
	if (!empty($board_config['vip_enable']))
	{
		$template->assign_block_vars('switch_vipcode', array());
	}

	$template->assign_vars(array(
		'L_ITEMS_REQUIRED' => $lang['Items_required'],
		'L_TITLE' => $lang['Helpdesk_main_title'], 		 		 
        'L_MAIN_1' => $lang['Helpdesk_main_select_1'],
        'L_MAIN_1_EXPLAIN' => $lang['Helpdesk_main_select_1_explain'],
		'L_MAIN_2' => $lang['Helpdesk_main_select_2'],
		'L_MAIN_2_EXPLAIN' => $lang['Helpdesk_main_select_2_explain'],
		'L_MAIN_3' => $lang['Helpdesk_main_select_3'],
		'L_MAIN_3_EXPLAIN' => $lang['Helpdesk_main_select_3_explain'],
		'L_REPLY' => $lang['Helpdesk_main_reply_box'],
		'L_SUBJECT' => $lang['Subject'],
		'L_BODY' => $lang['Message'],
		'L_ID' => $lang['Helpdesk_main_id'],

		'L_VIP_CODE' => $lang['Vip_code'],
		'L_VIP_CODE_EXPLAIN' => sprintf($lang['Vip_code_explain'], $board_config['vip_code']),
		
		'S_HELPDESK' => append_sid('helpdesk.'.$phpEx.'?mode=send_this'))
	); 
				
	$sql = "SELECT *
		  FROM " . HELPDESK_E . "
		  WHERE e_id <> 0";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain helpdesk email data.', '', __LINE__, __FILE__, $sql);
	} 

	while($row = $db->sql_fetchrow($result))
	{	
		$val = $row['e_addr'];
		$vid = $row['e_id'];
	
		$sql2 = "SELECT *
			FROM " . HELPDESK_M ."
		   	WHERE e_id = '$vid'";
		if ( !($result2 = $db->sql_query($sql2)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain helpdesk email.', '', __LINE__, __FILE__, $sql2);
		}	 
		$row2 = $db->sql_fetchrow($result2);
		
		$srt = $row2['e_msg'];
			
        $template->assign_block_vars('emails', array(
			"VAL" => $val, 
           	"SRT" => $srt)
		); 					
		$db->sql_freeresult($result2);
	}
	$db->sql_freeresult($result);
				
	$sql = "SELECT *
		FROM " . HELPDESK_I;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain helpdesk importance data.', '', __LINE__, __FILE__, $sql);
	} 

	while($row = $db->sql_fetchrow($result))
	{	
		$val = $row['value'];
		$dat = $row['data'];
	
    	$template->assign_block_vars('importance', array(
			"VAL" => $val, 
		   	"SPACE" => ' - ',		   
           	"DAT" => $dat)
		); 					
	}
	$db->sql_freeresult($result);
	
	$sql = "SELECT *
		  FROM " . HELPDESK_R;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain helpdesk reply data.', '', __LINE__, __FILE__, $sql);
	} 

	while($row = $db->sql_fetchrow($result))
	{	
		$val = $row['value'];
		$dat = $row['data'];

    	$template->assign_block_vars('reply', array(
			"VAL" 	=> $val, 
           	"DAT" 	=> $dat)
		); 						
	}
	$db->sql_freeresult($result);
}		
	
if( $mode == 'send_this')
{
	$subject = trim($_POST['subject']);
	$body = trim($_POST['body']);
	$email = $_POST['user_selected'];
	$importance = $_POST['user_importance'];
	$reply = $_POST['user_reply'];
	$r_id = $_POST['id'];
	$sender = $userdata['username'];
	$sender_ip = FindIp();
	$send_time = strftime("%b. %d, %Y @ %H:%M:%S", time());

    if ( !empty($board_config['vip_enable']) && ($HTTP_POST_VARS['vip_code'] != $board_config['vip_code']) )
    {
		message_die(GENERAL_MESSAGE, $lang['vip_spam_invalid'] . '<br /><br />' . sprintf($lang['Click_return_login'], '<a href="javascript:history.go(-1)">', '</a>'));
    }
    
	if ($subject == '' )
	{
		message_die(GENERAL_MESSAGE, $lang['Empty_subject_email'] . '<br /><br />' . sprintf($lang['Click_return_login'], '<a href="javascript:history.go(-1)">', '</a>'));
	}
	
	if ($body == '' )
	{
		message_die(GENERAL_MESSAGE, $lang['Empty_message_email'] . '<br /><br />' . sprintf($lang['Click_return_login'], '<a href="javascript:history.go(-1)">', '</a>'));
	}

	$sql = "SELECT data
		FROM " . HELPDESK_R ."
		WHERE value = '$reply'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain helpdesk reply data.', '', __LINE__, __FILE__, $sql);
	}	 
	$row = $db->sql_fetchrow($result);
	$contact = $row['data'];
	$db->sql_freeresult($result);
		
	$sql = "SELECT e_id
		FROM " . HELPDESK_E ."
		WHERE e_addr = '$email'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain helpdesk email data.', '', __LINE__, __FILE__, $sql);
	}	 
	$row = $db->sql_fetchrow($result);
	$id = $row['e_id'];
	$db->sql_freeresult($result);
		
	$sql = "SELECT e_msg
		FROM " . HELPDESK_M ."
		WHERE e_id = '$id'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain helpdesk email data.', '', __LINE__, __FILE__, $sql);
	}	 
	$row = $db->sql_fetchrow($result);
	$msg = $row['e_msg'];
	$db->sql_freeresult($result);
			
	$extras = $lang['Helpdesk_email_department'] . ": $msg\n
	   " . $lang['Helpdesk_email_username'] . ": $sender\n
	   " . $lang['Helpdesk_email_ip'] . ": $sender_ip\n
	   " . $lang['Helpdesk_email_reply_method'] . ": $contact\n
	   " . $lang['Helpdesk_email_reply_id'] . ": $r_id\n
	   " . $lang['Helpdesk_email_importance'] . ": $importance\n
	   " . $lang['Helpdesk_email_time'] . ": $send_time\n
	   " . $lang['Helpdesk_email_message'] . ":\n";
	
	$sent = @mail($email, $lang['Helpdesk'] . ' -> ' . $subject, $body, $extras);
	
	if($sent = 1)
	{
		$message = $lang['Email_sent'] . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.'.$phpEx) . '">', '</a>');
	
		message_die(GENERAL_MESSAGE, $message);
	}
	else
	{
		$message = $lang['Helpdesk_email_failed'] . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.'.$phpEx) . '">', '</a>');
	
		message_die(GENERAL_MESSAGE, $message);
	}
}
			
$page_title = $lang['Helpdesk_main_title'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx); 

$template->set_filenames(array(
	'body' => 'helpdesk_body.tpl')
);
make_jumpbox('viewforum.'.$phpEx);

$template->pparse('body'); 

include($phpbb_root_path . 'includes/page_tail.'.$phpEx); 	

//
// Function
//
function FindIP() 
{ 
	if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), 'unknown')) 									
	{
		$ip = getenv("HTTP_CLIENT_IP"); 
	}
	else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), 'unknown')) 						
	{
		$ip = getenv("HTTP_X_FORWARDED_FOR"); 
	}
	else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), 'unknown')) 										
	{
		$ip = getenv("REMOTE_ADDR"); 
	}
	else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) 	
	{
		$ip = $_SERVER['REMOTE_ADDR']; 
	}
	else 																													
	{
		$ip = 'unknown'; 		
	}
	
	return($ip); 
}

?>