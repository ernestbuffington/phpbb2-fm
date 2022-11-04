<?php
/** 
*
* @package phpBB2
* @version $Id: guestbook.php,v 2.2.0 2006/02/5 12:02:15 nardi Exp $
* @copyright (c) 2001 Cricca Hi!Wap
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include($phpbb_root_path . 'includes/functions_post.'.$phpEx);
include($phpbb_root_path . 'includes/guestbook_class.'.$phpEx);

function refresh_page($message = '') 
{
	global $template, $lang, $phpEx, $view;
 
 	$template->assign_vars(array(
		'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("guestbook.$phpEx?view=$view") . '">')
	);

   	$msg = $message . '<br /><br />' . sprintf($lang['Guest_return'], '<a href="' . append_sid("guestbook.$phpEx?view=$view") . '">', '</a> ') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

   	message_die(GENERAL_MESSAGE, $msg);
} 

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_GUESTBOOK);
init_userprefs($userdata);
//
// End session management
//


$guest_config = array();
$guest_book = new guestbook();
$guest_config = $guest_book->guest_config();

if ( !$guest_config['enable_guestbook'] )
{ 
	message_die(GENERAL_MESSAGE, $lang['Error_guestbook_closed']); 
}

// define team to manage guest
if ( $userdata['session_logged_in'] )
{
   	switch ($userdata['user_level'])	
   	{ 
   		case ADMIN: 
   			define('STAFF', true); 
   			break;
   		case LESS_ADMIN: 
    	case MOD: 
			if ( $guest_config['permit_mod'] )
			{
				define('STAFF', true);  
			}
			break;
	}
}

$hide = ( isset($HTTP_POST_VARS['hide']) ) ? ( ($HTTP_POST_VARS['hide']) ? TRUE : 0 ) : 0;
$unhide = ( isset($HTTP_POST_VARS['unhide']) ) ? ( ($HTTP_POST_VARS['unhide']) ? TRUE : 0 ) : 0;
$confirm = ( isset($HTTP_POST_VARS['confirm']) ) ? TRUE : 0;
$cancel = ( isset($HTTP_POST_VARS['cancel']) ) ? TRUE : 0;
$delete = ( isset($HTTP_POST_VARS['delete']) ) ? TRUE : FALSE;
$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$start = ($start < 0) ? 0 : $start;

if ( isset($HTTP_POST_VARS['idmsg']) || isset($HTTP_GET_VARS['idmsg']) )
{
	$idmsg = ( isset($HTTP_POST_VARS['idmsg']) ) ? $HTTP_POST_VARS['idmsg'] : $HTTP_GET_VARS['idmsg'];
	$idmsg = intval($idmsg);	
}
else
{
		$idmsg = '';
}

if ( isset($HTTP_POST_VARS['view']) || isset($HTTP_GET_VARS['view']) )
{
	$view = ( isset($HTTP_POST_VARS['view']) ) ? $HTTP_POST_VARS['view'] : $HTTP_GET_VARS['view'];
	$view = htmlspecialchars($view);	
}
else
{
	$view = '';
}

if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode);
}
else
{
	if ( $delete )
	{
		$mode = 'delete';
	}
	else if ( $hide )
	{
		$mode = 'hide';
		$hidden = 1;
		$msg_hidden = $lang['Guest_msg_hiden'];
	}
	else if ( $unhide )
	{
		$mode = 'unhide';
		$hidden = 0;
		$msg_hidden = $lang['Guest_msg_show'];
	}
	else
	{
		$mode = '';
	}
}

//
// Cancel
//
if ( $cancel )
{
	redirect(append_sid("guestbook.$phpEx", TRUE));
}

$error_msg = '';

if (isset($HTTP_POST_VARS['submit']))
{
	if (time() - $guest_book->decrypt($mode) > intval($guest_config['session_posting'])) 
	{
		die("Spaming attempt");
 	}

	$html_entities_match = array('#&(?!(\#[0-9]+;))#', '#<#', '#>#', '#"#');
	$html_entities_replace = array('&amp;', '&lt;', '&gt;', '&quot;');

	$nick = ( !empty($HTTP_POST_VARS['nick']) ) ? phpbb_clean_username($HTTP_POST_VARS['nick']) : $userdata['username'];
  	$email = ( !empty($HTTP_POST_VARS['email']) ) ? trim(htmlspecialchars($HTTP_POST_VARS['email'])) : '';
  	$sito = ( !empty($HTTP_POST_VARS['sito']) ) ? trim(htmlspecialchars($HTTP_POST_VARS['sito'])) : '';
  	$comento = ( !empty($HTTP_POST_VARS['message']) ) ? trim($HTTP_POST_VARS['message']) : '';
  
    if ( $email != '' )
    {
		$email = (eregi("[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,3}", $email)) ? $email : '';
    }

    if ( $sito != '' )
	{
		if ( !preg_match('#^http:\/\/#i', $sito) )
		{
			$sito = 'http://' . $sito;
		}

		if ( !preg_match('#^http\\:\\/\\/[a-z0-9\-]+\.([a-z0-9\-]+\.)?[a-z]+#i', $sito) )
		{
			$sito = '';
		}
	}
	
   	$maxlenght = intval($guest_config['maxlenght_posts']);
   
   	if ( strlen( $comento ) > $maxlenght )
   	{
     	$error_msg .= (!empty($error_msg)) ? '<br />' . sprintf($lang['Long_message'], $maxlenght)  : sprintf($lang['Long_message'], $maxlenght) ;
   	}
   	else if ( $comento != '' )
	{   
    	$check = $guest_book->check_post_error($comento);
      
        if ( $check['error'] )
		{
        	$error_msg .= (!empty($error_msg)) ? '<br />' . $check['error_msg'] : $check['error_msg'];
		}
	    else
		{
        	$comento 		= $guest_book->word_wrap($comento, $guest_config['word_wrap_length']);
			$bbcode_uid 	= make_bbcode_uid();
		    $comento 		= preg_replace($html_entities_match, $html_entities_replace, $comento);
		    $comento 		= bbencode_first_pass($comento, $bbcode_uid);
		}
	}
	else
	{
		$error_msg .= (!empty($error_msg)) ? '<br />' . $lang['Empty_message'] : $lang['Empty_message'];
	}
		
   	$data_ora = time();
   	$agent = trim(htmlspecialchars($HTTP_SERVER_VARS['HTTP_USER_AGENT'])); // ??? :(
   	$hide = $guest_config['hide_posts'];
   
   	if ( $board_config['flood_interval'] > 0 )
	{      
    	$time_request = time() - $board_config['flood_interval'];
    	$last_posting = intval($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_flood']);
      
	  	if(!isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_flood']) || $last_posting < $time_request)  
	  	{
	  		setcookie($board_config['cookie_name'] . '_flood', time(), time() + 3600, $board_config['cookie_path'], $board_config['cookie_domain'], $board_config['cookie_secure']);
    	}
    	if ($last_posting > $time_request)
    	{
    		$error_msg .= (!empty($error_msg)) ? '<br />' . $lang['Flood_Error'] : $lang['Flood_Error'];
    	}
    }
    
  	if (!$error_msg)
  	{    
		if (defined('STAFF') && $idmsg != '') 
		{
			$sql = "UPDATE " . GUESTBOOK . " 
	    		SET nick = '$nick', email = '" . str_replace("\'", "''", $email) . "',  sito = '" . str_replace("\'", "''", $sito) . "', comento = '" . str_replace("\'", "''", $comento) . "', bbcode_uid = '$bbcode_uid' 
				WHERE id = '$idmsg'";
			$message_sing = $lang['Message_edited'];
		} 
		else 
		{
	    	$sql = "INSERT INTO " . GUESTBOOK . " (nick, data_ora, email, sito, comento, bbcode_uid, ipi, agent, user_id, hide)
	           VALUES ('$nick', '$data_ora', '" . str_replace("\'", "''", $email) . "', '" . str_replace("\'", "''", $sito) . "', '" . str_replace("\'", "''", $comento) . "', '$bbcode_uid', '$user_ip', '$agent', '" . $userdata['user_id'] . "', '$hide')";
			$message_sing = $lang['Message_sing'];
		} 
    
		if( !($result = $db->sql_query($sql)) )
    	{
   	  		message_die(GENERAL_ERROR, 'Could not query guestbook', '', __LINE__, __FILE__, $sql);
		}

   		refresh_page($message_sing); 

  	} 
  	else 
  	{
    	$field_nick 	= ( !empty($HTTP_POST_VARS['nick']) ) ? stripslashes($nick) : '';
        $field_email 	= ( !empty($HTTP_POST_VARS['email']) ) ? stripslashes($email) : '';
        $field_sito 	= ( !empty($HTTP_POST_VARS['sito']) ) ? stripslashes($sito) : '';
        $field_comento = ( !empty($HTTP_POST_VARS['message']) ) ? stripslashes($comento) : '';
        $field_comento = preg_replace('/\:(([a-z0-9]:)?)' . $bbcode_uid . '/s', '', $field_comento);
	}
}

if ( (defined('STAFF') && $mode == 'edit') || $mode == 'quote') 
{
	$sql = ($mode == 'edit') ? "SELECT * FROM " . GUESTBOOK . " WHERE id = '$idmsg'" : "SELECT nick, comento, bbcode_uid FROM " . GUESTBOOK . " WHERE id = '$idmsg'";
	if ( $result = $db->sql_query($sql) ) 
	{
		$row = $db->sql_fetchrow($result);
        
        if (!$row)  
        {
        	message_die(GENERAL_MESSAGE, $lang['Guest_none_selected']);
		}	 
		
		if ($mode == 'edit') 
		{
	 		$field_email 	= ( !empty($row['email']) ) ? trim(stripslashes($row['email'])) : '';
	        $field_sito 	= ( !empty($row['sito']) ) ? trim(stripslashes($row['sito'])) : '';
	        $field_nick 	= ( !empty($row['nick']) ) ? trim(stripslashes($row['nick'])) : ANONYMOUS;
	        $bbcodes        = $row['bbcode_uid'];
	        $editid 		= $idmsg;
		}
               
		$quote_nick 	= ( !empty($row['nick']) ) ? trim(stripslashes($row['nick'])) : ANONYMOUS;
		$field_comento = ( !empty($row['comento']) ) ? stripslashes($row['comento']) : '';
	    $field_comento = preg_replace('/\:(([a-z0-9]:)?)' . $row['bbcode_uid'] . '/s', '', $field_comento);
	         
	    $field_comento = str_replace('<', '&lt;', $field_comento);
		$field_comento = str_replace('>', '&gt;', $field_comento);
		//$field_comento = str_replace('"', '&quot;', $field_comento);
		$field_comento = str_replace('<br />', "\n", $field_comento);
				
	    if ( $mode == 'quote' ) 
	    {
			$field_comento = '[quote="' . $quote_nick . '"]' . $field_comento . '[/quote]';
		}			
	
		//
		// Define censored word matches
		//
		if ( !$board_config['allow_swearywords'] )
		{
			$orig_word = $replacement_word = array();
			obtain_word_list($orig_word, $replacement_word);
		}
		else if ( !$userdata['user_allowswearywords'] )
		{
			$orig_word = $replacement_word = array();
			obtain_word_list($orig_word, $replacement_word);
		}
		
		if ( !empty($orig_word) )
		{
			$field_comento = ( !empty($field_comento) ) ? preg_replace($orig_word, $replace_word, $field_comento) : '';
		}
	} 
	else 
	{
		message_die(GENERAL_ERROR, 'Could not query guestbook', '', __LINE__, __FILE__, $sql);
	}
} 
 
if ( defined('STAFF') && ($mode == 'hide' || $mode == 'unhide'))
{
	if ( empty($HTTP_POST_VARS['msg_list']) && empty($idmsg) )
	{
		message_die(GENERAL_MESSAGE, $lang['None_selected']);
	}
			
   	$idmsgs = ( isset($HTTP_POST_VARS['msg_list']) ) ? $HTTP_POST_VARS['msg_list'] : array($idmsg);
   
   	if ( $mode == 'hide' )
	{
		$hidden = 1;
		$msg_hidden = $lang['Guest_msg_hidden'];
	}
	else 
	{
		$hidden = 0;
		$msg_hidden = $lang['Guest_msg_show'];
	}
	          			
	if ( sizeof($idmsgs) )
	{			
		for ($i = 0; $i < sizeof($idmsgs); $i++)
		{
			$count_sql[$i] = "UPDATE " . GUESTBOOK . " 
				SET hide = $hidden 
				WHERE id = " . intval($idmsgs[$i]);
			if ( !$db->sql_query($count_sql[$i]) )
			{
				message_die(GENERAL_ERROR, 'Could not update hide data for guestbook', '', __LINE__, __FILE__, $sql);
			}
		}
	}
		
	refresh_page($msg_hidden);               
} 
else if ( defined('STAFF') && $mode == 'delete')
{
	if ( empty($HTTP_POST_VARS['msg_list']) && empty($idmsg) )
	{
		message_die(GENERAL_MESSAGE, $lang['Guest_none_selected']);
	}
    
	$idmsgs = ( isset($HTTP_POST_VARS['msg_list']) ) ? $HTTP_POST_VARS['msg_list'] : array($idmsg);
    
    if ( !$confirm )
	{
		$s_hidden_fields = '<input type="hidden" name="confirm" value="true" /><input type="hidden" name="delete" value="true" /><input type="hidden" name="view" value="' . $view . '" />';
		
		if ( isset($HTTP_POST_VARS['msg_list']) )
		{
			for ($i = 0; $i < sizeof($idmsgs); $i++)
			{
				$s_hidden_fields .= '<input type="hidden" name="msg_list[]" value="' . intval($idmsgs[$i]) . '" />';
			}
		}
		else
		{
			$s_hidden_fields .= '<input type="hidden" name="idmsg" value="' . $idmsg . '" />';
		}

		include($phpbb_root_path . 'includes/page_header.'.$phpEx);

		$template->set_filenames(array(
			'confirm_body' => 'confirm_body.tpl')
		);
		
		$template->assign_vars(array(
			'MESSAGE_TITLE' => $lang['Information'],
			'MESSAGE_TEXT' => $lang['Guest_delete_coment'],

			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],

			'S_CONFIRM_ACTION' => append_sid("guestbook.$phpEx"),
			'S_HIDDEN_FIELDS' => $s_hidden_fields)
		);

		$template->pparse('confirm_body');

		include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
	} 
	else 
	{ 	          
		$idmsg_sql = '';
		for ($i = 0; $i < sizeof($idmsgs); $i++)
		{
			$idmsg_sql .= ( ( $idmsg_sql != '' ) ? ', ' : '' ) . intval($idmsgs[$i]);
		}
	          
	    $sql = "DELETE FROM " . GUESTBOOK . " 
	    	WHERE id IN ($idmsg_sql)";
		if ( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not delete message text', '', __LINE__, __FILE__, $sql);
		}
 
		refresh_page($lang['Guest_msg_delete']);               
	}
} 
else if ( defined('STAFF') && $mode == 'sent_email') 
{
	$sql = "SELECT nick, email 
		FROM " . GUESTBOOK . " 
		WHERE id = '$idmsg'";
   
   	$gen_simple_header = TRUE;
   
   	if ( $result = $db->sql_query($sql) ) 
   	{
		$row = $db->sql_fetchrow($result);
        $guest_email = $row['email'];
        $guest_name  = $row['nick'];
        $mail_inviato = false;
 
		if ( isset($HTTP_POST_VARS['submit']) )
		{
			$error = FALSE;

			if ( !empty($HTTP_POST_VARS['subject']) )
			{
				$subject = trim(stripslashes($HTTP_POST_VARS['subject']));
			}
			else
			{
				$error = TRUE;
				$error_msg = ( !empty($error_msg) ) ? $error_msg . '<br />' . $lang['Empty_subject_email'] : $lang['Empty_subject_email'];
			}

			if ( !empty($HTTP_POST_VARS['message']) )
			{
				$message = trim(stripslashes($HTTP_POST_VARS['message']));
			}
			else
			{
				$error = TRUE;
				$error_msg = ( !empty($error_msg) ) ? $error_msg . '<br />' . $lang['Empty_message_email'] : $lang['Empty_message_email'];
			}
            
            if ( !$error )
			{
	        	include($phpbb_root_path . 'includes/emailer.'.$phpEx);
				$emailer = new emailer($board_config['smtp_delivery']);

				$emailer->from($userdata['user_email']);
				$emailer->replyto($userdata['user_email']);

				$email_headers = 'X-AntiAbuse: Board servername - ' . $server_name . "\n";
				$email_headers .= 'X-AntiAbuse: User_id - ' . $userdata['user_id'] . "\n";
				$email_headers .= 'X-AntiAbuse: Username - ' . $userdata['username'] . "\n";
	
				$emailer->use_template('guest_contact_email', $board_config['default_lang']);
				$emailer->email_address($guest_email);
				$emailer->set_subject($subject);
				$emailer->extra_headers($email_headers);
	
				$emailer->assign_vars(array( 
					'FROM_USERNAME' => $userdata['username'],
					'SITENAME' => $board_config['sitename'],
					'MESSAGE' => $message)
				);
			
				$emailer->send();
				$emailer->reset();
            	
            	$mail_inviato = true;
			}
		}

        $s_hidden_fields = '<input type="hidden" name="submit" value="' . $lang['Submit'] . '" /><input type="hidden" name="idmsg" value="' . $idmsg . '" /><input type="hidden" name="mode" value="sent_email" />';
        
        $msg_header = $lang['Guest_sent_email'] . '<b>' . $row['nick'] . '</b> ( ' . $row['email'] . ' ) ';
        
       	//
		// Output confirmation page
		//
		include($phpbb_root_path . 'includes/page_header.'.$phpEx);

		$template->set_filenames(array(
			'email_body' => 'guest_sent_email.tpl')
		);

		if ( $error )
		{
			$template->set_filenames(array(
				'reg_header' => 'error_body.tpl')
			);
			$template->assign_vars(array(
				'ERROR_MESSAGE' => $error_msg)
			);
			$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
		}

		$template->assign_vars(array(
			'L_SUBJECT' => $lang['Subject'],
			'L_MESSAGE_BODY' => $lang['Message_body'],
			'L_SEND_EMAIL' => $lang['Send_email'],
			'MSG_HEADER' => $msg_header,
			'L_SUBMIT' => $lang['Submit'],
			'MSG_SENT' => $lang['Email_sent'],
			'L_CLOSE_WINDOW' => $lang['Close_window'],
			'S_POST_ACTION' => append_sid("guestbook.$phpEx"),
			'S_HIDDEN_FIELDS' => $s_hidden_fields)
		);
				
		if ( !$mail_inviato )
		{
			$template->assign_block_vars('prepare_email', array());
		}
		else
		{
			$template->assign_block_vars('email_is_sent', array());
		}
	
		$template->pparse('email_body');
		include($phpbb_root_path . 'includes/page_tail.'.$phpEx);		
	} 
	else
    {
    	$gen_simple_header = TRUE;
    
        message_die(GENERAL_MESSAGE, $lang['User_not_exist']);
	}
}

switch ($view)
{
	case 'hidden':
    	if ( defined('STAFF') )
		{   
	    	$title_msg = $lang['Guest_hidden_mode'];
	    	$sql = "SELECT COUNT(*) AS total 
	    		FROM " . GUESTBOOK . " 
	    		WHERE hide = 1";
	    	$hidepost = 'WHERE hide = 1'; 	
		} 
		else 
		{
	    	$title_msg = $lang['Guestbook'];
	    	$sql = "SELECT COUNT(*) AS total 
	    		FROM " . GUESTBOOK . " 
	    		WHERE hide = 0";
	     	$hidepost = 'WHERE hide = 0';
		}
  		break;
  	case 'view_all':
    	if ( defined('STAFF') )
		{   
	    	$title_msg = $lang['Guest_all_mode'];
			$sql = "SELECT COUNT(*) AS total 
				FROM " . GUESTBOOK;
	    	$hidepost = ''; 	
		} 
		else 
		{
	    	$title_msg = $lang['Guestbook'];
	    	$sql = "SELECT COUNT(*) AS total 
	    		FROM " . GUESTBOOK . " 
	    		WHERE hide = 0";
	     	$hidepost = 'WHERE hide = 0';
		}
  		break;
 	default: 
		$title_msg = $lang['Guestbook'];
	  	$sql = "SELECT COUNT(*) AS total 
	  		FROM " . GUESTBOOK . " 
	  		WHERE hide = 0";
	    $hidepost = 'WHERE hide = 0';
		break;
} 

$result = $db->sql_query($sql);
if( $result )
{
	$row = $db->sql_fetchrow($result);
	$num = $row['total'];
}
$db->sql_freeresult($result);
      
$link_view_all = (defined('STAFF')) ? '<a href="' . append_sid("guestbook.$phpEx?view=view_all") . '" class="nav">'.$lang['Guest_view_all'].'</a>' : '';
$link_view_hidden = (defined('STAFF')) ? '<a href="' . append_sid("guestbook.$phpEx?view=hidden") . '" class="nav">'.$lang['Guest_view_hidden'].'</a>' : '';
$link_view_visible = (defined('STAFF')) ? '<a href="' . append_sid("guestbook.$phpEx") . '" class="nav">'.$lang['Guest_view_visible'].'</a>' : '';
				  
$pagination = (defined('STAFF')) ? generate_pagination("guestbook.$phpEx?mode=leggi&amp;view=$view", $num, $board_config['posts_per_page'], $start). ' ': generate_pagination("guestbook.$phpEx?mode=leggi", $num, $board_config['posts_per_page'], $start). ' ';

$field_view = (defined('STAFF')) ?  '<input type="hidden" name="view" value="'.$view.'" />' : '';
$field_hide = (defined('STAFF')) ? '<input type="submit" name="hide" value="'.$lang['Guest_hide_selected'].'" class="liteoption" />' : '';
$field_unhide = (defined('STAFF')) ? '<input type="submit" name="unhide" value="'.$lang['Guest_show_selected'].'" class="liteoption" />' : '';
$field_delete = (defined('STAFF')) ? '<input type="submit" name="delete" value="'.$lang['Delete_selected'].'" class="liteoption" />' : '';
		
$marked =  (defined('STAFF')) ? '<a href="javascript:select_switch(true);">'.$lang['Mark_all'].'</a> :: <a href="javascript:select_switch(false);">'.$lang['Unmark_all'].'</a> <br />' : '';

$s_hidden_fields = '<input type="hidden" name="submit" value="'.$lang['Submit'].'" />';
$s_hidden_fields .= ($mode =='edit') ? '<input type="hidden" name="idmsg" value="'.$editid.'" />' : $s_hidden_fields;
$s_hidden_fields .= '<input type="hidden" name="mode" value="'. $guest_book->encrypt(time()) . '" />';

// standard page header 
$page_title = $lang['Guestbook'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx); 

if ( $error_msg != '' )
{
	$template->set_filenames(array(
		'reg_header' => 'error_body.tpl')
	);
	$template->assign_vars(array(
		'ERROR_MESSAGE' => $error_msg)
	);
	$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
}

// assign template 
$template->set_filenames(array( 
	'body' => 'guestbook_body.tpl') 
); 
make_jumpbox('viewforum.'.$phpEx);

$guest_book->guest_counter();

$template->assign_vars(array(
	'L_MESSAGE' => $lang['Message'], 
	'L_GUESTBOOK' => $title_msg,
	'L_USERNAME' => $lang['Username'],
	'L_EMAIL' => $lang['Email'],
	'L_SITE' => $lang['Website'],

	'LINK_VIEW_ALL' => $link_view_all,
    'LINK_VIEW_HIDDEN' => $link_view_hidden,
    'LINK_VIEW_VISIBLE' => $link_view_visible,
    'MARKED' => $marked,
    'FIELD_DELETE' => $field_delete,
    'FIELD_UNHIDE' => $field_unhide,
    'FIELD_HIDE' => $field_hide,
    'FIELD_VIEW' => $field_view,
        
    'FIELD_NICK' => $field_nick,
    'FIELD_EMAIL' => $field_email,
    'FIELD_SITO' => ( $error_msg != '' ) ? $field_sito : 'http://',
    'FIELD_COMENTO' => $field_comento,
    'COUNTER' => $guest_config['contatore'],
        
    'L_FONT_COLOR' => $lang['Font_color'], 
	'L_COLOR_DEFAULT' => $lang['color_default'], 
	'L_COLOR_DARK_RED' => $lang['color_dark_red'], 
	'L_COLOR_RED' => $lang['color_red'], 
	'L_COLOR_ORANGE' => $lang['color_orange'], 
	'L_COLOR_BROWN' => $lang['color_brown'], 
	'L_COLOR_YELLOW' => $lang['color_yellow'], 
	'L_COLOR_GREEN' => $lang['color_green'], 
	'L_COLOR_OLIVE' => $lang['color_olive'], 
	'L_COLOR_CYAN' => $lang['color_cyan'], 
	'L_COLOR_BLUE' => $lang['color_blue'], 
	'L_COLOR_DARK_BLUE' => $lang['color_dark_blue'], 
	'L_COLOR_INDIGO' => $lang['color_indigo'], 
	'L_COLOR_VIOLET' => $lang['color_violet'], 
	'L_COLOR_WHITE' => $lang['color_white'], 
	'L_COLOR_BLACK' => $lang['color_black'], 
	'L_COLOR_CADET_BLUE' => $lang['color_cadet_blue'],
	'L_COLOR_CORAL' => $lang['color_coral'], 
	'L_COLOR_CRIMSON' => $lang['color_crimson'], 
	'L_COLOR_TOMATO' => $lang['color_tomato'], 
	'L_COLOR_SEA_GREEN' => $lang['color_sea_green'], 
	'L_COLOR_DARK_ORCHID' => $lang['color_dark_orchid'], 
	'L_COLOR_CHOCOLATE' => $lang['color_chocolate'],
	'L_COLOR_DEEPSKYBLUE' => $lang['color_deepskyblue'], 
	'L_COLOR_GOLD' => $lang['color_gold'], 
	'L_COLOR_GRAY' => $lang['color_gray'], 
	'L_COLOR_MIDNIGHTBLUE' => $lang['color_midnightblue'], 
	'L_COLOR_DARKGREEN' => $lang['color_darkgreen'], 

	'L_FONT_SIZE' => $lang['Font_size'], 
	'L_FONT_TINY' => $lang['font_tiny'], 
	'L_FONT_SMALL' => $lang['font_small'], 
    'L_FONT_NORMAL' => $lang['font_normal'], 
	'L_FONT_LARGE' => $lang['font_large'], 
	'L_FONT_HUGE' => $lang['font_huge'],

	'L_FLAG' => $lang['Flag'],
	'L_SUBJECT' => $lang['Subject'],
	'L_POSTED' => $lang['Posted'], 
	'L_DATE' => $lang['Date'],
	'L_FROM' => $lang['From'],
	'L_DELETE' => $lang['Delete'],
	'L_SELECT' => $lang['Select'],
	'L_MARK_ALL' => $lang['Mark_all'], 
	'L_UNMARK_ALL' => $lang['Unmark_all'],
	'L_CAVEAT' => $lang['caveat'],
	'L_MESSAGGIO' => $lang['Post'],

	'L_BBCODE_CLOSE_TAGS' => $lang['Close_Tags'],
	'L_STYLES_TIP' => $lang['Styles_tip'],
	'L_EMPTY_MESSAGE' => $lang['Empty_message'],
	'L_COUNTER' => $lang['Views'],
   
   	'VERSION' => $guest_config['version'],
 
 	'BBCODE_STATUS' => sprintf($lang['BBCode_is_ON'], '<a href="' . append_sid("faq.$phpEx?mode=bbcode") . '" target="_phpbbcode">', '</a>'), 
   	'PAGINATION' => $pagination,
	'U_GUESTBOOK' => append_sid("guestbook.$phpEx"), 
	'S_GUESTBOOK_ACTION' => append_sid("guestbook.$phpEx?mode=scrivi"),
	'S_HIDDEN_FIELDS' => $s_hidden_fields)
);

//
// Smilies
//
if( $board_config['allow_smilies'] )
{
	generate_smilies('inline', PAGE_GUESTBOOK);
}

$sql = "SELECT * 
	FROM " . GUESTBOOK . "
	" . $hidepost . "
    ORDER BY data_ora DESC 
	LIMIT " . $start . ", " . $board_config['posts_per_page'];
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query guestbook', '', __LINE__, __FILE__, $sql);
}
	    
if ( $row = $db->sql_fetchrow($result) )
{
	$i = $start;
	
	do
	{
		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
	  
	 	//
     	// Define censored word matches
     	//
     	if ( $userdata['user_level'] < 1 && !$userdata['user_allowswearywords'] )
		{
	        $orig_word = $replacement_word = array();
			obtain_word_list($orig_word, $replacement_word);
        }
        
        $comento = bbencode_second_pass($row['comento'], $row['bbcode_uid']);
        $comento = smilies_pass($comento);
        $comento = make_clickable($comento);
        $comento = str_replace("\n", "\n<br />\n", $comento);
		
		//
      	// Censor message
      	//
      	if ( count($orig_word) )
        {
	    	$comento = ($row['comento'] != '') ? str_replace('\"', '"', substr(@preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "@preg_replace(\$orig_word, \$replacement_word, '\\0')", '>' . $comento . '<'), 1, -1)) : '';
	    	$mail = ($row['email'] != '') ? preg_replace($orig_word, $replacement_word, $row['email']) : '';
	    	$sito = ($row['sito'] != '') ? preg_replace($orig_word, $replacement_word, $row['sito']) : '';
	    	$nick = ($row['nick'] != '') ? preg_replace($orig_word, $replacement_word, $row['nick']) : '';
        }
      	else
        {
        	$mail = $row['email'];
	    	$sito = $row['sito'];
	    	$nick = $row['nick'];
        }
         
       	$www_img = ( $row['sito'] ) ? '<a href="' . $sito . '" target="_userwww"><img src="' . $images['icon_www'] . '" alt="' . $sito . '" title="' . $lang['Visit_website'] . '" /></a>' : '';
       	$post_date = create_date($board_config['default_dateformat'], $row['data_ora'], $board_config['board_timezone']);
       
	   	$edit = append_sid("guestbook.$phpEx?mode=edit&amp;idmsg=" . $row['id']);
       	$edit_img = '<img src="' . $images['icon_edit'] . '" alt="' . $lang['Edit_Post'] . '" />';
       	
       	$quote = append_sid("guestbook.$phpEx?mode=quote&amp;idmsg=" . $row['id']);
       	$quote_img = '<img src="' . $images['icon_quote'] . '" alt="' . $lang['Quote'] . '" />';

	   	if ( defined('STAFF') )
		{
			$email_tmp = "<a href=\"javascript:open_window('', '" . append_sid("guestbook.$phpEx?mode=sent_email&amp;idmsg=" . $row['id']) . "', 0, 0, 680, 585, 0, 0, 0, 0, 0)\"  onmouseover=\"window.status='$mail';  return true;\" onmouseout=\"window.status='';  return true;\">";
			$email_img = ( $row['email'] ) ? $email_tmp . '<img src="' . $images['icon_email'] . '" alt="' . $mail . '" title="' . $mail . '" /></a>' : '';		
			$temp_hiden = get_userdata($row['user_id'] );
			$poster_hiden = ( $row['user_id'] == ANONYMOUS ) ? $lang['Guest'] : $temp_hiden['username'];
	    	$check_row = '<input type="checkbox" name="msg_list[]" value="' . $row['id'] . '" />';
		} 
		else 
		{ 
			$poster_hiden = '';
			$email_img = ( $row['email'] ) ? '<a href="mailto:' . $mail . '"><img src="' . $images['icon_email'] . '" alt="' . $mail . '" title="' . $lang['Send_email'] . '" /></a>' : '';
            $colspan = '';
		}

 		$template->assign_block_vars('postrow', array(
        	'ROW_CLASS' => $row_class,
		    'CHECK_ROW' => $check_row,
		    'EDIT' => ( defined('STAFF') ) ? '<a href="' . $edit . '">' . $edit_img . '</a>' : '',
		    'QUOTE' => '<a href="' . $quote . '">' . $quote_img . '</a>',
		    'COLSPAN' => $colspan,
		    'DETTAGLI' => $dettagli,
			'NICK' => $nick,
			'HIDEN_NICK' => $poster_hiden,
			'MESSAGE' => $comento,
			'SITO' => $www_img,
			'GUEST_IP' => ( defined('STAFF') ) ? '<a href="http://network-tools.com/default.asp?prog=trace&amp;host=' . decode_ip($row['ipi']) . '">' . decode_ip($row['ipi']) . '</a>' :'',
			'EMAIL' => $email_img,
			'AGENT' => ( defined('STAFF') ) ? $row['agent'] : '',
			'DATA' => $post_date)
		);
    	$i++;
	}
	
	while ( $row = $db->sql_fetchrow($result) );
	
	$db->sql_freeresult($result);
}         
else 
{
 	$template->assign_block_vars('guest_empty', array(
		'GUEST_EMPTY' => $lang['Guest_empty'] )
	);       
}
      
if ( defined('STAFF') )
{
	  $template->assign_block_vars('switch_user_staff', array());
}


$template->pparse('body'); 

include($phpbb_root_path . 'includes/page_tail.'.$phpEx); 

?>