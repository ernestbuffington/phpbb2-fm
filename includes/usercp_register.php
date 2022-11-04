<?php
/** 
*
* @package includes
* @version $Id: usercp_register.php,v 1.20.2.58 2004/11/18 17:49:45 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

/*

	This code has been modified from its original form by psoTFX @ phpbb.com
	Changes introduce the back-ported phpBB 3 visual confirmation code. 

	NOTE: Anyone using the modified code contained within this script MUST include
	a relevant message such as this in usercp_register.php ... failure to do so 
	will affect a breach of Section 2a of the GPL and our copyright

	png visual confirmation system : (c) phpBB Group, 2003 : All Rights Reserved

*/

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
	exit;
}


//
// Start language file includes
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_edit_post_date.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_edit_post_date.' . $phpEx);
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_mass_pm.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_mass_pm.' . $phpEx);
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_myinfo.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_myinfo.' . $phpEx);
//
// End language file includes
//


$unhtml_specialchars_match = array('#&gt;#', '#&lt;#', '#&quot;#', '#&amp;#');
$unhtml_specialchars_replace = array('>', '<', '"', '&');

if ( !empty($HTTP_GET_VARS['ruid']) )
{
    $ruid = intval($HTTP_GET_VARS['ruid']);
}
else if ( !empty($_POST['ruid']) )
{
    $ruid = intval($_POST['ruid']);
}
else
{
    $ruid = $board_config['referral_id'];
}

if( isset($HTTP_GET_VARS['mode'] ) || isset($HTTP_POST_VARS['mode']) ) 
{ 
	$mode = ( isset($HTTP_GET_VARS['mode']) ) ? $HTTP_GET_VARS['mode'] : $HTTP_POST_VARS['mode']; 

	if( $mode == REGISTER_MODE && $userdata['user_level'] != ADMIN && $board_config['require_activation'] == USER_ACTIVATION_DISABLE )
	{ 
	    $template->assign_vars(array(
			"META" => '<meta http-equiv="refresh" content="3;url=' . append_sid("index.$phpEx") . '">')
		); 

	    $message = ( $board_config['disable_reg_msg'] ) ? $board_config['disable_reg_msg'] : $lang['disable_reg_msg']; 
	   
	    message_die(GENERAL_MESSAGE, $message); 
	} 
} 

// ---------------------------------------
// Load agreement template since user has not yet
// agreed to registration conditions/coppa
//
function show_coppa()
{
	global $board_config, $userdata, $template, $lang, $phpbb_root_path, $phpEx, $ruid;

	if ($board_config['enable_coppa'])
	{
		$template->assign_block_vars('switch_coppa_on', array(
			'L_AGREE_UNDER_13' => $lang['Agree_under_13'],
			'U_AGREE_UNDER13' => append_sid("profile.$phpEx?mode=" . REGISTER_MODE . "&amp;agreed=true&amp;coppa=true&amp;ruid=$ruid"))
		);
	}
	
	$template->set_filenames(array(
		'body' => 'agreement.tpl')
	);

	$template->assign_vars(array(
		'L_REGISTRATION' => $lang['Registration'],
		'L_AGREEMENT' => sprintf($lang['Reg_agreement'], $board_config['sitename'], $board_config['sitename'], $board_config['sitename']),
		'L_AGREE_OVER_13' => (!$board_config['enable_coppa']) ? $lang['Agree_do'] : $lang['Agree_over_13'],
		'L_DO_NOT_AGREE' => $lang['Agree_not'],

		'U_AGREE_OVER13' => append_sid("profile.$phpEx?mode=" . REGISTER_MODE . "&amp;agreed=true&amp;ruid=$ruid"))
	);

	$template->pparse('body');
}
//
// ---------------------------------------

//$error = FALSE;
$error_msg .= '';
$page_title = ( $mode == 'editprofile' ) ? $lang['Viewing_profile'] : $lang['Register'];

//
// User Accounts Limit
//
if ( $mode == REGISTER_MODE && $board_config['user_accounts_limit'] )
{
	$sql = "SELECT COUNT(user_id) AS total_users
		FROM " . USERS_TABLE . "
		WHERE user_id <> " . ANONYMOUS;
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain total user count.', '', __LINE__, __FILE__, $sql);
	}

	if ( !($row = $db->sql_fetchrow($result)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain total user count.', '', __LINE__, __FILE__, $sql);
	}
	
	if ( $row['total_users'] >= $board_config['user_accounts_limit'] )
	{
		message_die(GENERAL_MESSAGE, $lang['user_accounts_limit'] . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.'.$phpEx) . '">', '</a>'));
	}
}


if ( $mode == REGISTER_MODE && !isset($HTTP_POST_VARS['agreed']) && !isset($HTTP_GET_VARS['agreed']) )
{
	include($phpbb_root_path . 'includes/page_header.'.$phpEx);

	show_coppa();

	include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
}

$coppa = ( empty($HTTP_POST_VARS['coppa']) && empty($HTTP_GET_VARS['coppa']) ) ? 0 : TRUE;


//
// Profile CP Organize: Retrieve display mode
//
if ( isset($HTTP_GET_VARS['ucp']) || isset($HTTP_POST_VARS['ucp']) )
{
	$cpl_mode = ( isset($HTTP_GET_VARS['ucp']) ) ? htmlspecialchars($HTTP_GET_VARS['ucp']) : htmlspecialchars($HTTP_POST_VARS['ucp']);
}
if ($mode == REGISTER_MODE || ($cpl_mode != 'all' && $cpl_mode != 'main' && $cpl_mode != 'reg_info' && $cpl_mode != 'profile_info' && $cpl_mode != 'preferences' && $cpl_mode != 'avatar' && $cpl_mode != 'signature' && $cpl_mode != 'photo'))
{
	$cpl_mode = 'main';
}
if ($mode != REGISTER_MODE)
{
	$template->assign_block_vars('switch_cpl_menu', array());
}

//
// Check and initialize some variables if needed
//
include($phpbb_root_path . 'digests_common.'.$phpEx);
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
if ( isset($HTTP_POST_VARS['submit']) || isset($HTTP_POST_VARS['avatargallery']) || isset($HTTP_POST_VARS['submitavatar']) || isset($HTTP_POST_VARS['avatargenerator']) || isset($HTTP_POST_VARS['submitgenava']) || isset($HTTP_POST_VARS['cancelavatar']) || $mode == REGISTER_MODE )
{
	include($phpbb_root_path . 'includes/functions_validate.'.$phpEx);
	include($phpbb_root_path . 'includes/functions_post.'.$phpEx);

	if ( $mode == 'editprofile' )
	{
		$user_id = intval($HTTP_POST_VARS['user_id']);
		$current_email = trim(htmlspecialchars($HTTP_POST_VARS['current_email']));
	}

	$strip_var_list = array('realname' => 'realname', 'email' => 'email', 'icq' => 'icq', 'aim' => 'aim', 'msn' => 'msn', 'yim' => 'yim', 'xfi' => 'xfi', 'skype' => 'skype', 'gtalk' => 'gtalk', 'website' => 'website', 'stumble' => 'stumble', 'location' => 'location', 'occupation' => 'occupation', 'interests' => 'interests', 'zipcode' => 'zipcode', 'irc_commands' => 'irc_commands', 'custom_post_color' => 'custom_post_color', 'confirm_code' => 'confirm_code');
	
	// Strip all tags from data ... may p**s some people off, bah, strip_tags is
	// doing the job but can still break HTML output ... have no choice, have
	// to use htmlspecialchars ... be prepared to be moaned at.
	while( list($var, $param) = @each($strip_var_list) )
	{
		if ( !empty($HTTP_POST_VARS[$param]) )
		{
			$$var = trim(htmlspecialchars($HTTP_POST_VARS[$param]));
		}
	}

	$username = ( !empty($HTTP_POST_VARS['username']) ) ? phpbb_clean_username($HTTP_POST_VARS['username']) : '';
	$trim_var_list = array('cur_password' => 'cur_password', 'new_password' => 'new_password', 'password_confirm' => 'password_confirm', 'myInfo' => 'myInfo');

	while( list($var, $param) = @each($trim_var_list) )
	{
		if ( !empty($HTTP_POST_VARS[$param]) )
		{
			$$var = trim($HTTP_POST_VARS[$param]);
		}
	}

	$signature_bbcode_uid = '';

	$allow_mass_pm = ( isset($HTTP_POST_VARS['allow_mass_pm']) ) ? intval ($HTTP_POST_VARS['allow_mass_pm']) : 2;

	$gender = ( isset($HTTP_POST_VARS['gender']) ) ? intval ($HTTP_POST_VARS['gender']) : 0;

	if( isset($HTTP_POST_VARS['birthday']) ) 
	{ 
		$birthday = intval($HTTP_POST_VARS['birthday']); 
		if( $birthday != 999999 ) 
		{ 
			$b_day = realdate('j', $birthday); 
			$b_md = realdate('n', $birthday); 
			$b_year = realdate('Y', $birthday); 
		} 
	} 
	else 
	{ 
		$b_day = ( isset($HTTP_POST_VARS['b_day']) ) ? intval($HTTP_POST_VARS['b_day']) : 0; 
		$b_md = ( isset($HTTP_POST_VARS['b_md']) ) ? intval($HTTP_POST_VARS['b_md']) : 0; 
		$b_year = ( isset($HTTP_POST_VARS['b_year']) ) ? intval($HTTP_POST_VARS['b_year']) : 0; 
		if ($b_day && $b_md && $b_year) 
		{ 
			$birthday = mkrealdate($b_day, $b_md, $b_year); 
		} 
		else 
		{ 
			$birthday = 999999; 
		} 
	} 

	$myInfo = str_replace('<br />', "\n", $myInfo);

	// Run some validation on the optional fields. These are pass-by-ref, so they'll be changed to
	// empty strings if they fail.
	validate_optional_fields($icq, $aim, $msn, $yim, $xfi, $skype, $gtalk, $website, $stumble, $location, $occupation, $interests, $irc_commands, $custom_post_color);

	$xdata = array();
	$xd_meta = get_xd_metadata();
	foreach ($xd_meta as $name => $info)
	{
		if ( isset($HTTP_POST_VARS[$name]) && $info['handle_input'] )
		{
			$xdata[$name] = trim($HTTP_POST_VARS[$name]);
			$xdata[$name] = str_replace('<br />', "\n", $xdata[$name]);
		}
	}

	$viewemail = ( isset($HTTP_POST_VARS['viewemail']) ) ? ( ($HTTP_POST_VARS['viewemail']) ? TRUE : 0 ) : 0;
	$popup_notes = ( isset($HTTP_POST_VARS['popup_notes']) ) ? ( ($HTTP_POST_VARS['popup_notes']) ? TRUE : 0 ) : 0;
	$profile_view_popup = ( isset($HTTP_POST_VARS['profile_view_popup']) ) ? ( ($HTTP_POST_VARS['profile_view_popup']) ? TRUE : 0 ) : 0;
	$allowviewonline = ( isset($HTTP_POST_VARS['hideonline']) ) ? ( ($HTTP_POST_VARS['hideonline']) ? 0 : TRUE ) : TRUE;
	$notifyreply = ( isset($HTTP_POST_VARS['notifyreply']) ) ? ( ($HTTP_POST_VARS['notifyreply']) ? TRUE : 0 ) : 0;
	$notifypm = ( isset($HTTP_POST_VARS['notifypm']) ) ? ( ($HTTP_POST_VARS['notifypm']) ? TRUE : 0 ) : TRUE;
	$notifypmtext = ( isset($HTTP_POST_VARS['notifypmtext']) ) ? ( ($HTTP_POST_VARS['notifypmtext']) ? TRUE : 0 ) : 0;
	$notifydonation = ( isset($HTTP_POST_VARS['notifydonation']) ) ? ( ($HTTP_POST_VARS['notifydonation']) ? TRUE : 0 ) : 0;
	$popup_pm = ( isset($HTTP_POST_VARS['popup_pm']) ) ? ( ($HTTP_POST_VARS['popup_pm']) ? TRUE : 0 ) : TRUE;
   	$soundpm = ( isset($HTTP_POST_VARS['sound_pm']) ) ? ( ($HTTP_POST_VARS['sound_pm']) ? TRUE : 0 ) : 0; 
	$avatar_sticky = ( isset($HTTP_POST_VARS['avatar_sticky']) ) ? ( ($HTTP_POST_VARS['avatar_sticky']) ? TRUE : 0 ) : 0; 
	$mail_on_topic_moved = ( isset($HTTP_POST_VARS['topic_moved_mail']) ) ? ( ($HTTP_POST_VARS['topic_moved_mail']) ? TRUE : 0 ) : 0;
	$pm_on_topic_moved = ( isset($HTTP_POST_VARS['topic_moved_pm']) ) ? ( ($HTTP_POST_VARS['topic_moved_pm']) ? TRUE : 0 ) : 0;
	$pm_on_topic_moved_notify = ( isset($HTTP_POST_VARS['topic_moved_pm_notify']) ) ? ( ($HTTP_POST_VARS['topic_moved_pm_notify']) ? TRUE : 0 ) : 0;
	$sid = (isset($HTTP_POST_VARS['sid'])) ? $HTTP_POST_VARS['sid'] : 0;

	if ( $mode == REGISTER_MODE )
	{
		$attachsig = ( isset($HTTP_POST_VARS['attachsig']) ) ? ( ($HTTP_POST_VARS['attachsig']) ? TRUE : 0 ) : $board_config['allow_sig'];
		$allowhtml = ( isset($HTTP_POST_VARS['allowhtml']) ) ? ( ($HTTP_POST_VARS['allowhtml']) ? TRUE : 0 ) : $board_config['allow_html'];
		$allowbbcode = ( isset($HTTP_POST_VARS['allowbbcode']) ) ? ( ($HTTP_POST_VARS['allowbbcode']) ? TRUE : 0 ) : $board_config['allow_bbcode'];
		$allowsmilies = ( isset($HTTP_POST_VARS['allowsmilies']) ) ? ( ($HTTP_POST_VARS['allowsmilies']) ? TRUE : 0 ) : $board_config['allow_smilies'];
	    $allowswearywords = ( isset($HTTP_POST_VARS['allowswearywords']) ) ? ( ($HTTP_POST_VARS['allowswearywords']) ? TRUE : 0 ) : $board_config['allow_swearywords']; 
		$showsigs = ( isset($HTTP_POST_VARS['showsigs']) ) ? ( ($HTTP_POST_VARS['showsigs']) ? TRUE : 0 ) : 1; 
        $showavatars = ( isset($HTTP_POST_VARS['showavatars']) ) ? ( ($HTTP_POST_VARS['showavatars']) ? TRUE : 0 ) : 1;
		$digest_auto = (isset($HTTP_POST_VARS['digest_auto'])) ? (($HTTP_POST_VARS['digest_auto']) ? 1 : 0) : $digest_config['auto_subscribe'];
		$digest_new = (isset($HTTP_POST_VARS['digest_new'])) ? (($HTTP_POST_VARS['digest_new']) ? 2 : 0) :  $digest_config['new_sign_up'];
		$user_transition = TRUE;
	}
	else
	{
		$attachsig = ( isset($HTTP_POST_VARS['attachsig']) ) ? ( ($HTTP_POST_VARS['attachsig']) ? TRUE : 0 ) : $userdata['user_attachsig'];
		$retrosig = ( isset($HTTP_POST_VARS['retrosig']) ) ? ( ($HTTP_POST_VARS['retrosig']) ? TRUE : 0 ) : 0;
		$allowhtml = ( isset($HTTP_POST_VARS['allowhtml']) ) ? ( ($HTTP_POST_VARS['allowhtml']) ? TRUE : 0 ) : $userdata['user_allowhtml'];
		$allowbbcode = ( isset($HTTP_POST_VARS['allowbbcode']) ) ? ( ($HTTP_POST_VARS['allowbbcode']) ? TRUE : 0 ) : $userdata['user_allowbbcode'];
		$allowsmilies = ( isset($HTTP_POST_VARS['allowsmilies']) ) ? ( ($HTTP_POST_VARS['allowsmilies']) ? TRUE : 0 ) : $userdata['user_allowsmile'];
	    $allowswearywords = ( isset($HTTP_POST_VARS['allowswearywords']) ) ? ( ($HTTP_POST_VARS['allowswearywords']) ? TRUE : 0 ) : $userdata['user_allowswearywords']; 
		$showsigs = ( isset($HTTP_POST_VARS['showsigs']) ) ? ( ($HTTP_POST_VARS['showsigs']) ? TRUE : 0 ) : $userdata['user_showsigs']; 
        $showavatars = ( isset($HTTP_POST_VARS['showavatars']) ) ? ( ($HTTP_POST_VARS['showavatars']) ? TRUE : 0 ) : $userdata['user_showavatars'];
		$mail_on_topic_moved = ( isset($HTTP_POST_VARS['topic_moved_mail']) ) ? ( ($HTTP_POST_VARS['topic_moved_mail']) ? TRUE : 0 ) : $userdata['user_topic_moved_mail'];
		$pm_on_topic_moved = ( isset($HTTP_POST_VARS['topic_moved_pm']) ) ? ( ($HTTP_POST_VARS['topic_moved_pm']) ? TRUE : 0 ) : $userdata['user_topic_moved_pm'];
		$pm_on_topic_moved_notify = ( isset($HTTP_POST_VARS['topic_moved_pm_notify']) ) ? ( ($HTTP_POST_VARS['topic_moved_pm_notify']) ? TRUE : 0 ) : $userdata['user_topic_moved_pm_notify'];
		$user_transition = ( isset($HTTP_POST_VARS['user_transition']) ) ? ( ($HTTP_POST_VARS['user_transition']) ? TRUE : 0 ) : $userdata['user_transition'];
	}

	$user_wordwrap = ( isset($HTTP_POST_VARS['user_wordwrap']) ) ? intval($HTTP_POST_VARS['user_wordwrap']) : $board_config['wrap_def'];
	$user_style = ( isset($HTTP_POST_VARS['style']) ) ? intval($HTTP_POST_VARS['style']) : $board_config['default_style'];

	if ( !empty($HTTP_POST_VARS['language']) )
	{
		if ( preg_match('/^[a-z_]+$/i', $HTTP_POST_VARS['language']) )
		{
			$user_lang = htmlspecialchars($HTTP_POST_VARS['language']);
		}
		else
		{
			$error = true;
			$error_msg .= $lang['Fields_empty'];
		}
	}
	else
	{
		$user_lang = $board_config['default_lang'];
	}

	$user_timezone = ( isset($HTTP_POST_VARS['timezone']) ) ? doubleval($HTTP_POST_VARS['timezone']) : $board_config['board_timezone'];
	$user_flag = ( !empty($HTTP_POST_VARS['user_flag']) ) ? $HTTP_POST_VARS['user_flag'] : '' ;

	$user_dateformat = ( !empty($HTTP_POST_VARS['dateformat']) ) ? trim(htmlspecialchars($HTTP_POST_VARS['dateformat'])) : $board_config['default_dateformat'];
	$user_clockformat = ( !empty($HTTP_POST_VARS['clockformat']) ) ? trim(htmlspecialchars($HTTP_POST_VARS['clockformat'])) : $board_config['default_clock'];

	$user_avatar_local = ( isset($HTTP_POST_VARS['avatarselect']) && !empty($HTTP_POST_VARS['submitavatar']) && $board_config['allow_avatar_local'] ) ? htmlspecialchars($HTTP_POST_VARS['avatarselect']) : ( ( isset($HTTP_POST_VARS['avatarlocal'])  ) ? htmlspecialchars($HTTP_POST_VARS['avatarlocal']) : '' ); 
	$user_avatar_category = ( isset($HTTP_POST_VARS['avatarcatname']) && $board_config['allow_avatar_local'] ) ? htmlspecialchars($HTTP_POST_VARS['avatarcatname']) : '' ; 
	$user_avatar_generator = ( isset($HTTP_POST_VARS['avatarimage']) && isset($HTTP_POST_VARS['avatartext']) && !empty($HTTP_POST_VARS['submitgenava']) && $board_config['allow_avatar_generator'] ) ? htmlspecialchars($HTTP_POST_VARS['avatar_filename']) : ( ( isset($HTTP_POST_VARS['avatargenerator'])  ) ? htmlspecialchars($HTTP_POST_VARS['avatargenerator']) : '' );
	$user_avatar_remoteurl = ( !empty($HTTP_POST_VARS['avatarremoteurl']) ) ? trim(htmlspecialchars($HTTP_POST_VARS['avatarremoteurl'])) : '';
	$user_avatar_upload = ( !empty($HTTP_POST_VARS['avatarurl']) ) ? trim($HTTP_POST_VARS['avatarurl']) : ( ( $HTTP_POST_FILES['avatar']['tmp_name'] != "none") ? $HTTP_POST_FILES['avatar']['tmp_name'] : '' );
	$user_avatar_name = ( !empty($HTTP_POST_FILES['avatar']['name']) ) ? $HTTP_POST_FILES['avatar']['name'] : '';
	$user_avatar_size = ( !empty($HTTP_POST_FILES['avatar']['size']) ) ? $HTTP_POST_FILES['avatar']['size'] : 0;
	$user_avatar_filetype = ( !empty($HTTP_POST_FILES['avatar']['type']) ) ? $HTTP_POST_FILES['avatar']['type'] : '';
	$user_avatar = ( empty($user_avatar_local) && $mode == 'editprofile' ) ? $userdata['user_avatar'] : '';
	$user_avatar_type = ( empty($user_avatar_local) && $mode == 'editprofile' ) ? $userdata['user_avatar_type'] : '';

   	if ( $mode == 'editprofile' )
   	{
    	$matched = false;
    	$group_priority = ( isset($HTTP_POST_VARS['group_priority']) ) ? intval($HTTP_POST_VARS['group_priority']) : 0;
    	if ( is_array($color_groups['groupdata']) )
     	{
        	foreach($color_groups['groupdata'] AS $color_group)
        	{
            	if ( is_array($color_group['group_users']) )
            	{
               		if ( in_array($userdata['user_id'], $color_group['group_users']) && $color_group['group_id'] == $group_priority )
               		{
                		$matched = true;
               		}
            	}
         	}
         	if ( ! $matched )
         	{
         		$group_priority = 0;
         	}
     	}
   	}

	if ( (isset($HTTP_POST_VARS['avatargallery']) || isset($HTTP_POST_VARS['submitavatar']) || isset($HTTP_POST_VARS['avatargenerator']) || isset($HTTP_POST_VARS['submitgenava']) || isset($HTTP_POST_VARS['cancelavatar'])) && (!isset($HTTP_POST_VARS['submit'])) )
	{
		$username = stripslashes($username);
		$realname = stripslashes($realname); 
		$email = stripslashes($email);
		$cur_password = htmlspecialchars(stripslashes($cur_password));
		$new_password = htmlspecialchars(stripslashes($new_password));
		$password_confirm = htmlspecialchars(stripslashes($password_confirm));

		$icq = stripslashes($icq);
		$aim = stripslashes($aim);
		$msn = stripslashes($msn);
		$yim = stripslashes($yim);
      	$xfi = stripslashes($xfi); 
		$skype = stripslashes($skype);
		$gtalk = stripslashes($gtalk);

		$website = stripslashes($website);
		$stumble = stripslashes($stumble);
		$location = stripslashes($location);
		$occupation = stripslashes($occupation);
		$interests = stripslashes($interests);
		$birthday = stripslashes($birthday); 
		$irc_commands = stripslashes($irc_commands);

		$user_lang = stripslashes($user_lang);
		$user_dateformat = stripslashes($user_dateformat);
		$user_clockformat = stripslashes($user_clockformat);
		$custom_post_color = stripslashes($custom_post_color);
		$myInfo = stripslashes($myInfo);

		@reset($xdata);
		while ( list($code_name, $value) = each($xdata) )
		{
			$xdata[$code_name] = stripslashes($value);
		}

		if ( !isset($HTTP_POST_VARS['cancelavatar']))
		{
			if ( isset($HTTP_POST_VARS['submitgenava']) )
			{
				$user_avatar = $user_avatar_generator;
				$user_avatar_type = USER_AVATAR_GENERATOR;
			}
			else
			{
				$user_avatar = $user_avatar_category . '/' . $user_avatar_local;
				$user_avatar_type = USER_AVATAR_GALLERY;
			}
		}
	}
}

//
// Let's make sure the user isn't logged in while registering,
// and ensure that they were trying to register a second time
// (Prevents double registrations)
//
if ( $userdata['session_logged_in'] && $mode == REGISTER_MODE && $username == $userdata['username'])
{
	message_die(GENERAL_MESSAGE, $lang['Username_taken'], '', __LINE__, __FILE__);
}

//
// Did the user submit? In this case build a query to update the users profile in the DB
//
if ( isset($HTTP_POST_VARS['submit']) )
{
	include($phpbb_root_path . 'includes/usercp_avatar.'.$phpEx);

  	// skype name check 
  	if ( !empty($HTTP_POST_VARS['skype']) )
  	{
    	$skypemuster = '/^[a-zA-Z0-9_.\-@,]{6,32}$/';
    	if ( !preg_match( $skypemuster, $HTTP_POST_VARS['skype'] ) )
    	{
    		$error = TRUE; 
  			$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['skype_falsch'];
    	}
  	}

	// session id check
	if ($sid == '' || $sid != $userdata['session_id'])
	{
		$error = TRUE;
		$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Session_invalid'];
	}

	$passwd_sql = '';
	if ( $mode == 'editprofile' )
	{	
		if ( $user_id != $userdata['user_id'] )
		{
			$error = TRUE;
			$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Wrong_Profile'];
		}
	}
	else if ( $mode == REGISTER_MODE )
	{
		if ( empty($username) || empty($new_password) || empty($password_confirm) || empty($email) || ($board_config['gender_required'] && empty($gender)) )
		{
			$error = TRUE;
			$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Fields_empty'];
        }
	}

	if ($board_config['enable_confirm'] && $mode == REGISTER_MODE)
	{
		if (empty($HTTP_POST_VARS['confirm_id']))
		{
			$error = TRUE;
			$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Confirm_code_wrong'];
		}
		else
		{
			$confirm_id = htmlspecialchars($HTTP_POST_VARS['confirm_id']);
			if (!preg_match('/^[A-Za-z0-9]+$/', $confirm_id))
			{
				$confirm_id = '';
			}
			
			$sql = 'SELECT code 
				FROM ' . CONFIRM_TABLE . " 
				WHERE confirm_id = '$confirm_id' 
					AND session_id = '" . $userdata['session_id'] . "'";
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not obtain confirmation code', '', __LINE__, __FILE__, $sql);
			}

			if ($row = $db->sql_fetchrow($result))
			{
				if ($row['code'] != $confirm_code)
				{
					$error = TRUE;
					$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Confirm_code_wrong'];
				}
				else
				{
					$sql = 'DELETE FROM ' . CONFIRM_TABLE . " 
						WHERE confirm_id = '$confirm_id' 
							AND session_id = '" . $userdata['session_id'] . "'";
					if (!$db->sql_query($sql))
					{
						message_die(GENERAL_ERROR, 'Could not delete confirmation code', '', __LINE__, __FILE__, $sql);
					}
				}
			}
			else
			{		
				$error = TRUE;
				$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Confirm_code_wrong'];
			}
			$db->sql_freeresult($result);
		}
	}

    if ( ($mode == REGISTER_MODE && $board_config['vip_enable']) && ($HTTP_POST_VARS['vip_code'] != $board_config['vip_code']) )
    {
		$error = TRUE;
		$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['vip_spam_invalid'];
    }

	$passwd_sql = '';
	if ( !empty($new_password) && !empty($password_confirm) )
	{
		if ( $new_password != $password_confirm )
		{
			$error = TRUE;
			$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Password_mismatch'];
		}
		else if ( strlen($new_password) > 32 )
		{
			$error = TRUE;
			$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Password_long'];
		}
		else
		{
			if ( $mode == 'editprofile' )
			{
				$sql = "SELECT user_password
					FROM " . USERS_TABLE . "
					WHERE user_id = $user_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not obtain user_password information', '', __LINE__, __FILE__, $sql);
				}

				$row = $db->sql_fetchrow($result);

				if ( $row['user_password'] != md5($cur_password) )
				{
					$error = TRUE;
					$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Current_password_mismatch'];
				}
			}

			if ( !$error )
			{
				$new_password = md5($new_password);
				$passwd_sql = "user_password = '$new_password', ";
			}
		}
	}
	else if ( ( empty($new_password) && !empty($password_confirm) ) || ( !empty($new_password) && empty($password_confirm) ) )
	{
		$error = TRUE;
		$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Password_mismatch'];
	}

	//
	// Do a ban check on this email address
	//
	if ( $email != $userdata['user_email'] || $mode == REGISTER_MODE )
	{
		$result = validate_email($email);
		if ( $result['error'] )
		{
			$email = $userdata['user_email'];

			$error = TRUE;
			$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $result['error_msg'];
		}

		if ( $mode == 'editprofile' )
		{
			$sql = "SELECT user_password
				FROM " . USERS_TABLE . "
				WHERE user_id = $user_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not obtain user_password information', '', __LINE__, __FILE__, $sql);
			}

			$row = $db->sql_fetchrow($result);

			if ( $row['user_password'] != md5($cur_password) )
			{
				$email = $userdata['user_email'];

				$error = TRUE;
				$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Current_password_mismatch'];
			}
		}
	}

	$username_sql = '';
	if ( $board_config['allow_namechange'] || $mode == REGISTER_MODE )
	{
		if ( empty($username) )
		{
			// Error is already triggered, since one field is empty.
			$error = TRUE;
		}
		else if ( $username != $userdata['username'] || $mode == REGISTER_MODE )
		{
			if (strtolower($username) != strtolower($userdata['username']) || $mode == REGISTER_MODE)
			{
				$result = validate_username($username);
				if ( $result['error'] )
				{
					$error = TRUE;
					$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $result['error_msg'];
				}
			}

			if (!$error)
			{
				$username_sql = "username = '" . str_replace("\'", "''", $username) . "', ";
			}
		}
	}

	$xd_meta = get_xd_metadata();
	while ( list($code_name, $meta) = each($xd_meta) )
	{
		if ( $meta['handle_input'] && ( ($mode == REGISTER_MODE && $meta['default_auth'] == XD_AUTH_ALLOW) || ($mode != REGISTER_MODE ? xdata_auth($code_name, $user_id) : 0) || $userdata['user_level'] == ADMIN ) )
		{
			if ( ($meta['field_length'] > 0) && (strlen($xdata[$code_name]) > $meta['field_length']) )
			{
	           	$error = TRUE;
				$error_msg .=  ( ( isset($error_msg) ) ? '<br />' : '' ) . sprintf($lang['XData_too_long'], $meta['field_name']);
			}
	
			if ( ( count($meta['values_array']) > 0 ) && ( ! in_array($xdata[$code_name], $meta['values_array']) ) )
			{
	           	$error = TRUE;
				$error_msg .=  ( ( isset($error_msg) ) ? '<br />' : '' ) . sprintf($lang['XData_invalid'], $meta['field_name']);
			}
	
			if ( ( strlen($meta['field_regexp']) > 0 ) && ( ! preg_match($meta['field_regexp'], $xdata[$code_name]) ) )
			{
			    $error = TRUE;
				$error_msg .=  ( ( isset($error_msg) ) ? '<br />' : '' ) . sprintf($lang['XData_invalid'], $meta['field_name']);
			}
	
			if ( $meta['allow_bbcode'] )
			{
				if ( $signature_bbcode_uid == '' )
				{
					$signature_bbcode_uid = ( $allowbbcode ) ? make_bbcode_uid() : '';
				}
			}	
			$xdata[$code_name] = prepare_message($xdata[$code_name], $meta['allow_html'], $meta['allow_bbcode'], $meta['allow_smilies'], $signature_bbcode_uid);
		}
	}

	if ( $user_wordwrap < $board_config['wrap_min'] || $user_wordwrap > $board_config['wrap_max'] )
	{
		$error = TRUE;
		$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Word_Wrap_Error'];
	}

	if ( $website != '' )
	{
		rawurlencode($website);
	}
	
	if ( $stumble != '' )
	{
		rawurlencode($stumble);
	}

	$avatar_sql = '';

	if ( isset($HTTP_POST_VARS['avatardel']) && $mode == 'editprofile' && !$avatar_sticky )
	{
		$avatar_sql = user_avatar_delete($userdata['user_avatar_type'], $userdata['user_avatar']);
		$allowavatar = ( $board_config['disable_avatar_approve'] || $userdata['user_level'] == ADMIN ) ? 1 : 0;
	}
	else if ( ( !empty($user_avatar_upload) || !empty($user_avatar_name) ) && $board_config['allow_avatar_upload'] )
	{
		if ( !empty($user_avatar_upload) )
		{
			$avatar_mode = (empty($user_avatar_name)) ? 'remote' : 'local';
			$avatar_sql = user_avatar_upload($mode, $avatar_mode, $userdata['user_avatar'], $userdata['user_avatar_type'], $error, $error_msg, $user_avatar_upload, $user_avatar_name, $user_avatar_size, $user_avatar_filetype);
			$allowavatar = ( $board_config['disable_avatar_approve'] || $userdata['user_level'] == ADMIN ) ? 1 : 0;
		}
		else if ( !empty($user_avatar_name) )
		{
			$l_avatar_size = sprintf($lang['Avatar_filesize'], round($board_config['avatar_filesize'] / 1024));

			$error = true;
			$error_msg .= ( ( !empty($error_msg) ) ? '<br />' : '' ) . $l_avatar_size;
		}
	}
	else if ( $user_avatar_remoteurl != '' && $board_config['allow_avatar_remote'] )
	{
		user_avatar_delete($userdata['user_avatar_type'], $userdata['user_avatar']); 
		$avatar_sql = user_avatar_url($mode, $error, $error_msg, $user_avatar_remoteurl);
		$allowavatar = ( $board_config['disable_avatar_approve'] || $userdata['user_level'] == ADMIN ) ? 1 : 0;
	}
	else if ( $user_avatar_local != '' && $board_config['allow_avatar_local'] )
	{
		user_avatar_delete($userdata['user_avatar_type'], $userdata['user_avatar']); 
		$avatar_sql = user_avatar_gallery($mode, $error, $error_msg, $user_avatar_local, $user_avatar_category);
		$allowavatar = 1;  
	} 
	else if ( $user_avatar_generator != '' && $board_config['allow_avatar_generator'] )
	{
		user_avatar_delete($userdata['user_avatar_type'], $userdata['user_avatar']);
		$avatar_sql = user_avatar_generator($mode, $error, $error_msg, $user_avatar_generator);
		$allowavatar = 1;  
	}
	else 
	{
		$allowavatar = $userdata['user_allowavatar'];
	}

	if ( !$allowavatar && !$error )
	{
		include($phpbb_root_path . 'includes/emailer.'.$phpEx);

		$sql_mail = "SELECT username, user_email, user_lang
			FROM " . USERS_TABLE . "
			WHERE user_level = 1";
		if ( !$result = $db->sql_query($sql_mail) )
		{
			message_die(GENERAL_ERROR, "Couldn't notify admins", "", __LINE__, __FILE__, $sql_mail);
		}
		while ( $row = $db->sql_fetchrow($result) )
		{
			$emailer = new emailer($board_config['smtp_delivery']);

			$emailer->from($board_config['board_email']);
			$emailer->replyto($board_config['board_email']);
			$emailer->set_subject($lang['New_avatar_activation']);

			$emailer->use_template('avatar_approve', stripslashes($row['user_lang']));
			$emailer->email_address($row['user_email']);

			$emailer->assign_vars(array(
				'USERNAME' => $row['username'],
				'POSTER_USERNAME' => $userdata['username'],
				'U_PROFILE_LINK' => $server_url . '?mode=viewprofile&' . POST_USERS_URL . '=' . $userdata['user_id'],
				'U_ADMIN_LINK' => str_replace("profile.$phpEx", "admin/admin_users.$phpEx", $server_url) . '?mode=edit&' . POST_USERS_URL . '=' . $userdata['user_id'] . '#approve_avatar',
				'EMAIL_SIG' => (!empty($board_config['board_email_sig'])) ? str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']) : '')
			);
			$emailer->send();
			$emailer->reset();
		}
		$db->sql_freeresult($result);
	}

	// Find the birthday values, reflected by the $lang['Submit_date_format'] 
	if ($b_day || $b_md || $b_year) //if a birthday is submited, then validate it 
	{ 
		$user_age = (date('md') >= $b_md.( ($b_day <= 9) ? 0 : '' ) . $b_day) ? date('Y') - $b_year : date('Y') - $b_year - 1; 
	    // Check date, maximum / minimum user age 
	    if ( !checkdate($b_md, $b_day, $b_year) ) 
	    { 
	    	$error = TRUE; 
			$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Wrong_birthday_format']; 
	    } 
	    else if ($user_age > $board_config['max_user_age'])
		{
	    	$error = TRUE; 
			$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . sprintf($lang['Birthday_to_high'], $board_config['max_user_age']); 
		} 
		else if ($user_age < $board_config['min_user_age'])
		{
	        $error = TRUE; 
			$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . sprintf($lang['Birthday_to_low'], $board_config['min_user_age']); 
		} 
		else 
	    { 
	    	$birthday = ($error) ? $birthday : mkrealdate($b_day, $b_md, $b_year);
	        $next_birthday_greeting = (date('md') < $b_md . ( ($b_day <= 9) ? '0' : '' ) . $b_day) ? date('Y') : date('Y') + 1; 
	    } 
	} 
	else 
	{ 
		if ( $board_config['birthday_required'] ) 
		{ 	
			$error = TRUE; 
			$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Birthday_require']; 
		} 
		$birthday = 999999; 		
	} 	
	
	if ( !$error )
	{
		if ( $avatar_sql == '' )
		{
			if ($mode == 'editprofile') 
			{ 
				$avatar_sql = ''; 
			} 
			else 
			{ 
				$avatar_register = isset($HTTP_POST_VARS['avatar_select']) ? str_replace("\'", "''", htmlspecialchars(trim($HTTP_POST_VARS['avatar_select']))) : '';
				$avatar_sql = ( $avatar_register != '' ) ? "'$avatar_register', " . USER_AVATAR_GALLERY : "'', " . USER_AVATAR_NONE; 
			} 
		} 

		if ( $mode == 'editprofile' )
		{
			if ( $email != $userdata['user_email'] && $board_config['require_activation'] != USER_ACTIVATION_NONE && $userdata['user_level'] != ADMIN )
			{
				$user_active = 0;

				// Force e-mail update 
				if ( $userdata['email_validation'] == 1 )
				{
					if ( $userdata['user_email'] == str_replace("\'", "''", $email) )
					{
						$notifypm = 0;
					}
					else
					{
						$sql = "UPDATE " . USERS_TABLE . " 
							SET email_validation = 0 
							WHERE user_id = $user_id";
						if ( !($result = $db->sql_query($sql)) )
						{
							message_die(GENERAL_ERROR, 'Could not update users table for e-mail validation', '', __LINE__, __FILE__, $sql);
						}
					}
				}

				$user_actkey = gen_rand_string(true);
				$key_len = 54 - ( strlen($server_url) );
				$key_len = ( $key_len > 6 ) ? $key_len : 6;
				$user_actkey = substr($user_actkey, 0, $key_len);

				if ( $userdata['session_logged_in'] )
				{
					session_end($userdata['session_id'], $userdata['user_id']);
				}
			}
			else
			{
				// let the users status stay as it is... 
				$user_active = 'user_active'; 
				$user_actkey = 'user_actkey'; 
			}

			if ($board_config['password_update_days'])
			{
				$sql = "UPDATE " . USERS_TABLE . "
			    	SET user_lastpassword = '" . md5($cur_password) . "', user_lastpassword_time = " . time() . "
			        WHERE user_id = $user_id";
				if ( !$result = $db->sql_query($sql) )
			    {
			    	message_die(GENERAL_ERROR, 'Could not update users table', '', __LINE__, __FILE__, $sql);
			    }
			}
					 
			$sql = "UPDATE " . USERS_TABLE . "
				SET " . $username_sql . $passwd_sql . "user_email = '" . str_replace("\'", "''", $email) ."', user_wordwrap = '" . str_replace("\'", "''", $user_wordwrap) . "', user_topic_moved_mail = $mail_on_topic_moved, user_topic_moved_pm = $pm_on_topic_moved, user_topic_moved_pm_notify = $pm_on_topic_moved_notify, user_icq = '" . str_replace("\'", "''", $icq) . "', user_website = '" . str_replace("\'", "''", $website) . "', user_stumble = '" . str_replace("\'", "''", $stumble) . "', user_occ = '" . str_replace("\'", "''", $occupation) . "', user_from = '" . str_replace("\'", "''", $location) . "', user_from_flag = '$user_flag', user_interests = '" . str_replace("\'", "''", $interests) . "', user_custom_post_color = '" . str_replace("\'", "''", $custom_post_color) . "', user_popup_notes = $popup_notes, user_profile_view_popup = $profile_view_popup, user_birthday = '$birthday', user_next_birthday_greeting = '$next_birthday_greeting', user_viewemail = $viewemail, user_aim = '" . str_replace("\'", "''", str_replace(' ', '+', $aim)) . "', user_yim = '" . str_replace("\'", "''", $yim) . "', user_msnm = '" . str_replace("\'", "''", $msn) . "', user_xfi = '" . str_replace("\'", "''", $xfi) . "', user_skype = '" . str_replace("\'", "''", $skype) . "', user_attachsig = $attachsig, user_allowsmile = $allowsmilies, user_allowswearywords = $allowswearywords, user_transition = $user_transition, user_allowhtml = $allowhtml, user_allowbbcode = $allowbbcode, user_allow_viewonline = $allowviewonline, user_showavatars = $showavatars, user_showsigs = $showsigs, user_notify = $notifyreply, user_notify_pm = $notifypm, user_notify_pm_text = $notifypmtext, user_notify_donation = $notifydonation, user_allow_mass_pm = $allow_mass_pm, user_popup_pm = $popup_pm, user_sound_pm = $soundpm, user_timezone = $user_timezone, group_priority = '$group_priority', user_dateformat = '" . str_replace("\'", "''", $user_dateformat) . "', user_clockformat = '" . str_replace("\'", "''", $user_clockformat) . "', user_lang = '" . str_replace("\'", "''", $user_lang) . "', user_style = $user_style, user_active = $user_active, user_actkey = '$user_actkey'" . $avatar_sql . ", user_gender = $gender, user_zipcode = '" . str_replace("\'", "''", $zipcode) . "', avatar_sticky = $avatar_sticky, irc_commands = '" . str_replace("\'", "''", $irc_commands) . "', user_allowavatar = '$allowavatar', user_realname = '" . str_replace("\'", "''", $realname) . "', user_info = '" . str_replace("\'", "''", $myInfo) . "', user_gtalk = '" . str_replace("\'", "''", $gtalk) . "'
				WHERE user_id = $user_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not update users table', '', __LINE__, __FILE__, $sql);
			}

			// Retroactive Signature 
			if ($retrosig)
			{
				$sql = "UPDATE " . POSTS_TABLE . " 
					SET enable_sig = 1 
					WHERE poster_id = $user_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not update signature status on previous posts.', '', __LINE__, __FILE__, $sql);
				}
			}

			// We remove all stored login keys since the password has been updated
			// and change the current one (if applicable)
			if ( !empty($passwd_sql) )
			{
				session_reset_keys($user_id, $user_ip);
			}

			foreach ($xdata as $code_name => $value)
			{
				set_user_xdata($user_id, $code_name, $value);
			}	

			if ( !$user_active )
			{
				//
				// The users account has been deactivated, send them an email with a new activation key
				//
				include($phpbb_root_path . 'includes/emailer.'.$phpEx);
				$emailer = new emailer($board_config['smtp_delivery']);
 			
 				if ( $board_config['require_activation'] != USER_ACTIVATION_ADMIN )
 				{
					$emailer->from($board_config['board_email']);
					$emailer->replyto($board_config['board_email']);

					$emailer->use_template('user_activate', stripslashes($user_lang));
					$emailer->email_address($email);
					$emailer->set_subject($lang['Reactivate']);

					$emailer->assign_vars(array(
						'SITENAME' => $board_config['sitename'],
						'EMAIL_ADDRESS' => $email,
						'USERNAME' => preg_replace($unhtml_specialchars_match, $unhtml_specialchars_replace, substr(str_replace("\'", "'", $username), 0, $board_config['limit_username_max_length'])),
						'EMAIL_SIG' => (!empty($board_config['board_email_sig'])) ? str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']) : '',

						'U_ACTIVATE' => $server_url . '?mode=activate&' . POST_USERS_URL . '=' . $user_id . '&act_key=' . $user_actkey)
					);
					$emailer->send();
					$emailer->reset();
				}
 				else if ( $board_config['require_activation'] == USER_ACTIVATION_ADMIN )
 				{
 					$sql = 'SELECT user_email, user_lang 
 						FROM ' . USERS_TABLE . '
 						WHERE user_level = ' . ADMIN;				
 					if ( !($result = $db->sql_query($sql)) )
 					{
 						message_die(GENERAL_ERROR, 'Could not select Administrators', '', __LINE__, __FILE__, $sql);
 					}
 					
 					while ($row = $db->sql_fetchrow($result))
 					{
 						$emailer->from($board_config['board_email']);
 						$emailer->replyto($board_config['board_email']);
 						
 						$emailer->email_address(trim($row['user_email']));
 						$emailer->use_template("admin_activate", $row['user_lang']);
 						$emailer->set_subject($lang['Reactivate']);
 
 						$emailer->assign_vars(array(
 							'EMAIL_ADDRESS' => $email,
							
							'USERNAME' => preg_replace($unhtml_specialchars_match, $unhtml_specialchars_replace, substr(str_replace("\'", "'", $username), 0, $board_config['limit_username_max_length'])),
 							'EMAIL_SIG' => str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']),
 
 							'U_ACTIVATE' => $server_url . '?mode=activate&' . POST_USERS_URL . '=' . $user_id . '&act_key=' . $user_actkey)
 						);
 						$emailer->send();
 						$emailer->reset();
 					}
 					$db->sql_freeresult($result);
 				}
				
				$message = $lang['Profile_updated_inactive'] . '<br /><br />' . sprintf($lang['Click_return_index'],  '<a href="' . append_sid("index.$phpEx") . '">', '</a>');
			}
			else
			{
				$message = ((!$allowavatar) ? $lang['Profile_updated_avatar'] : $lang['Profile_updated']) . '<br /><br />' . sprintf($lang['Click_return_usercp'],  '<a href="' . append_sid("profile.$phpEx?mode=editprofile&ucp=" . $cpl_mode) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'],  '<a href="' . append_sid("index.$phpEx") . '">', '</a>');
			}

			$template->assign_vars(array(
				"META" => '<meta http-equiv="refresh" content="3;url=' . append_sid("profile.".$phpEx."?mode=editprofile&ucp=" . $cpl_mode) . '">')
			);

			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			$sql = "SELECT MAX(user_id) AS total
				FROM " . USERS_TABLE;
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not obtain next user_id information', '', __LINE__, __FILE__, $sql);
			}

			if ( !($row = $db->sql_fetchrow($result)) )
			{
				message_die(GENERAL_ERROR, 'Could not obtain next user_id information', '', __LINE__, __FILE__, $sql);
			}
			$user_id = $row['total'] + 1;

			//
			// Get current date
			//
			$sql = "INSERT INTO " . USERS_TABLE . " (user_id, username, user_regdate, user_password, user_email, user_wordwrap, user_topic_moved_mail, user_topic_moved_pm, user_topic_moved_pm_notify, user_icq, user_website, user_stumble, user_occ, user_from, user_from_flag, user_interests, irc_commands, user_popup_notes, user_profile_view_popup, user_avatar, user_avatar_type, user_viewemail, user_aim, user_yim, user_msnm, user_xfi, user_skype, user_attachsig, user_allowsmile, user_allowswearywords, user_transition, user_allowhtml, user_allowbbcode, user_allow_viewonline, user_showavatars, user_showsigs, user_notify, user_notify_pm, user_notify_pm_text, user_notify_donation, user_popup_pm, user_sound_pm, user_timezone, user_dateformat, user_clockformat, user_lang, user_style, user_gender, user_points, user_level, user_allow_pm, user_allow_mass_pm, user_custom_post_color, user_birthday, user_next_birthday_greeting, user_zipcode, user_realname, user_info, user_gtalk, user_digest_status, user_active, user_actkey) 
 				VALUES ($user_id, '" . str_replace("\'", "''", $username) . "', " . time() . ", '" . str_replace("\'", "''", $new_password) . "', '" . str_replace("\'", "''", $email) . "', '" . str_replace("\'", "''", $user_wordwrap) . "', $mail_on_topic_moved, $pm_on_topic_moved, $pm_on_topic_moved_notify, '" . str_replace("\'", "''", $icq) . "', '" . str_replace("\'", "''", $website) . "', '" . str_replace("\'", "''", $stumble) . "', '" . str_replace("\'", "''", $occupation) . "', '" . str_replace("\'", "''", $location) . "', '$user_flag', '" . str_replace("\'", "''", $interests) . "', '" . str_replace("\'", "''", $irc_commands) . "', $popup_notes, $profile_view_popup, $avatar_sql, $viewemail, '" . str_replace("\'", "''", str_replace(' ', '+', $aim)) . "', '" . str_replace("\'", "''", $yim) . "', '" . str_replace("\'", "''", $msn) . "', '" . str_replace("\'", "''", $xfi) . "', '" . str_replace("\'", "''", $skype) . "', $attachsig, $allowsmilies, $allowswearywords, $user_transition, $allowhtml, $allowbbcode, $allowviewonline, $showavatars, $showsigs, $notifyreply, $notifypm, $notifypmtext, $notifydonation, $popup_pm, $soundpm, $user_timezone, '" . str_replace("\'", "''", $user_dateformat) . "', '" . str_replace("\'", "''", $user_clockformat) . "', '" . str_replace("\'", "''", $user_lang) . "', $user_style, $gender, '" . $board_config['points_default'] . "', 0, " . $board_config['privmsg_newuser_disable'] . ", $allow_mass_pm, '" . str_replace("\'", "''", $custom_post_color) . "', '$birthday', '$next_birthday_greeting', '$zipcode', '$realname', '" . str_replace("\'", "''", $myInfo) . "', '" . str_replace("\'", "''", $gtalk) . "', " . ($digest_auto + $digest_new) . ", "; 

			if ( $board_config['require_activation'] == USER_ACTIVATION_SELF || $board_config['require_activation'] == USER_ACTIVATION_ADMIN || $coppa )
			{
				$user_actkey = gen_rand_string(true);
				$key_len = 54 - (strlen($server_url));
				$key_len = ( $key_len > 6 ) ? $key_len : 6;
				$user_actkey = substr($user_actkey, 0, $key_len);
				$sql .= "0, '" . str_replace("\'", "''", $user_actkey) . "')";
			}
			else
			{
				$sql .= "1, '')";
			}

			if ( !($result = $db->sql_query($sql, BEGIN_TRANSACTION)) )
			{
				message_die(GENERAL_ERROR, 'Could not insert data into users table', '', __LINE__, __FILE__, $sql);
			} 
			
			// Profile photo
			$profilephoto_mod->photo_insert($mode);
					
			// Referral
           	if ($board_config['referral_enable'])
	        {
       	    	if ($ruid != '')
            	{
                	$ruid = stripslashes($ruid);
                	
	 				if ( $board_config['referral_id'] > 1 && $board_config['referral_reward'] ) 
					{
						$sql = "UPDATE " . USERS_TABLE . " 
								SET user_points = user_points + " . $board_config['referral_reward'] . "
							WHERE user_id = '" . str_replace("\'", "''", $ruid) . "'";
						if( !$db->sql_query($sql) ) 
						{ 
							message_die(GENERAL_ERROR, 'Could not update users referral bonus ' . $board_config['points_name'], '', __LINE__, __FILE__, $sql); 
						}
					}
                	
	                $sql = "INSERT INTO " . REFERRAL_TABLE . "	(referral_id, ruid, nuid, referral_time) 
      	            	VALUES ('', '" . str_replace("\'", "''", $ruid) . "', '" . str_replace("\'", "''", $user_id) . "', " . time() . ")"; 	    
	                if ( !($result = $db->sql_query($sql)) )
	                {
	                 	message_die(GENERAL_ERROR, 'Could not insert data into referral table', '', __LINE__, __FILE__, $sql);
	                }
				}
			}

			// Prillian
			$sql = "INSERT INTO " . IM_PREFS_TABLE . " (user_id, themes_id)
				VALUES ($user_id, " . $board_config['default_style'] . ")";
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not insert data into im_prefs table.', '', __LINE__, __FILE__, $sql);
			}

			// Default auto-group
			if ($board_config['auto_group_id'])
			{
				$sql = "INSERT INTO " . USER_GROUP_TABLE . " (user_id, group_id, user_pending)
					VALUES ($user_id, " . $board_config['auto_group_id'] . ", 0)";
				if( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not insert data into user_group table (auto_group)', '', __LINE__, __FILE__, $sql);
				}
			}
			
			$sql = "INSERT INTO " . GROUPS_TABLE . " (group_name, group_description, group_single_user, group_moderator)
				VALUES ('', 'Personal User', 1, 0)";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not insert data into groups table', '', __LINE__, __FILE__, $sql);
			}

			$group_id = $db->sql_nextid();

			$sql = "INSERT INTO " . USER_GROUP_TABLE . " (user_id, group_id, user_pending)
				VALUES ($user_id, $group_id, 0)";
			if( !($result = $db->sql_query($sql, END_TRANSACTION)) )
			{
				message_die(GENERAL_ERROR, 'Could not insert data into user_group table', '', __LINE__, __FILE__, $sql);
			}

			foreach ($xdata as $code_name => $value)
			{
				set_user_xdata($user_id, $code_name, $value);
			}

			if ( $coppa )
			{
				$message = $lang['COPPA'];
				$email_template = 'coppa_welcome_inactive';
			}
			else if ( $board_config['require_activation'] == USER_ACTIVATION_SELF )
			{
				$message = $lang['Account_inactive'];
				$email_template = 'user_welcome_inactive';
			}
			else if ( $board_config['require_activation'] == USER_ACTIVATION_ADMIN )
			{
				$message = $lang['Account_inactive_admin'];
				$email_template = 'admin_welcome_inactive';
			}
			else
			{
				$message = $lang['Account_added'];
				$email_template = 'user_welcome';
			}

			include($phpbb_root_path . 'includes/emailer.'.$phpEx);
			$emailer = new emailer($board_config['smtp_delivery']);

			if ( $board_config['registration_notify'] )
			{
				$sql = "SELECT user_email, user_lang
					FROM " . USERS_TABLE . "
					WHERE user_level" . (($board_config['registration_notify'] == USER_REGISTRATION_NOTIFY_ADMIN) ? " = " : " >= ") . ADMIN;

				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not select Administrators', '', __LINE__, __FILE__, $sql);
				}

				while ($row = $db->sql_fetchrow($result))
				{
					$emailer->from($board_config['board_email']);
					$emailer->replyto($board_config['board_email']);

					$emailer->email_address(trim($row['user_email']));
					$emailer->use_template("admin_new_user", $row['user_lang']);
					$emailer->set_subject($lang['New_user_registration']);

					$emailer->assign_vars(array(
						'SITENAME' => $board_config['sitename'],
						'USERNAME' => preg_replace($unhtml_specialchars_match, $unhtml_specialchars_replace, substr(str_replace("\'", "'", $username), 0, $board_config['limit_username_max_length'])),
						'EMAIL_SIG' => str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']),

						'U_PROFILE' => $server_url . '?mode=viewprofile&' . POST_USERS_URL . '=' . $user_id)
					);
					$emailer->send();
					$emailer->reset();
				}
				$db->sql_freeresult($result);
			}

			$emailer->from($board_config['board_email']);
			$emailer->replyto($board_config['board_email']);

			$emailer->use_template($email_template, stripslashes($user_lang));
			$emailer->email_address($email);
			$emailer->set_subject(sprintf($lang['Welcome_subject'], $board_config['sitename']));

			if( $coppa )
			{
				$emailer->assign_vars(array(
					'SITENAME' => $board_config['sitename'],
					'WELCOME_MSG' => sprintf($lang['Welcome_subject'], $board_config['sitename']),
					'USERNAME' => preg_replace($unhtml_specialchars_match, $unhtml_specialchars_replace, substr(str_replace("\'", "'", $username), 0, $board_config['limit_username_max_length'])),
					'PASSWORD' => $password_confirm,
					'EMAIL_SIG' => str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']),

					'FAX_INFO' => $board_config['coppa_fax'],
					'MAIL_INFO' => $board_config['coppa_mail'],
					'EMAIL_ADDRESS' => $email,
					'ICQ' => $icq,
					'AIM' => $aim,
					'YIM' => $yim,
               		'XFI' => $xfi,
					'MSN' => $msn,
					'SKYPE' => $skype, 
					'GTALK' => $gtalk,
					'STUMBLE' => $stumble,
					'WEB_SITE' => $website,
					'FROM' => $location,
					'OCC' => $occupation,
					'INTERESTS' => $interests,
					'SITENAME' => $board_config['sitename']));
			}
			else
			{
				$emailer->assign_vars(array(
					'SITENAME' => $board_config['sitename'],
					'WELCOME_MSG' => sprintf($lang['Welcome_subject'], $board_config['sitename']),
					'USERNAME' => preg_replace($unhtml_specialchars_match, $unhtml_specialchars_replace, substr(str_replace("\'", "'", $username), 0, $board_config['limit_username_max_length'])),
					'PASSWORD' => $password_confirm,
					'EMAIL_SIG' => str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']),
					'EMAIL_ADDRESS' => $email,

					'U_ACTIVATE' => $server_url . '?mode=activate&' . POST_USERS_URL . '=' . $user_id . '&act_key=' . $user_actkey)
				);
			}

			$emailer->send();
			$emailer->reset();

			if ( $board_config['require_activation'] == USER_ACTIVATION_ADMIN )
			{
				$sql = "SELECT user_email, user_lang 
					FROM " . USERS_TABLE . "
					WHERE user_level = " . ADMIN;
				
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not select Administrators', '', __LINE__, __FILE__, $sql);
				}
				
				while ($row = $db->sql_fetchrow($result))
				{
					$emailer->from($board_config['board_email']);
					$emailer->replyto($board_config['board_email']);
					
					$emailer->email_address(trim($row['user_email']));
					$emailer->use_template("admin_activate", $row['user_lang']);
					$emailer->set_subject($lang['New_account_subject']);

					$emailer->assign_vars(array(
						'USERNAME' => preg_replace($unhtml_specialchars_match, $unhtml_specialchars_replace, substr(str_replace("\'", "'", $username), 0, $board_config['limit_username_max_length'])),
						'EMAIL_SIG' => str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']),
						'EMAIL_ADDRESS' => $email,
	
						'U_ACTIVATE' => $server_url . '?mode=activate&' . POST_USERS_URL . '=' . $user_id . '&act_key=' . $user_actkey)
					);
					$emailer->send();
					$emailer->reset();
				}
				$db->sql_freeresult($result);
			}

			$message = $message . '<br /><br />' . sprintf($lang['Click_return_index'],  '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		} // if mode == register
	}
} // End of submit


if ( $error )
{
	//
	// If an error occured we need to stripslashes on returned data
	//
	$username = stripslashes($username);
	$realname = stripslashes($realname); 
	$email = stripslashes($email);
	$cur_password = '';
	$new_password = '';
	$password_confirm = '';

	$icq = stripslashes($icq);
	$aim = str_replace('+', ' ', stripslashes($aim));
	$msn = stripslashes($msn);
	$yim = stripslashes($yim);
   	$xfi = stripslashes($xfi);
	$skype = stripslashes($skype);
	$gtalk = stripslashes($gtalk);

	$website = stripslashes($website);
	$stumble = stripslashes($stumble);
	$location = stripslashes($location);
	$occupation = stripslashes($occupation);
	$interests = stripslashes($interests);

	@reset($xdata);
	while ( list($code_name, $value) = each($xdata) )
	{
		$xdata[$code_name] = stripslashes($value);
	
		if ($xd_meta[$code_name]['allow_bbcode'])
		{
	    	$xdata[$code_name] = ($signature_bbcode_uid != '') ? preg_replace("/:(([a-z0-9]+:)?)$signature_bbcode_uid(=|\])/si", '\\3', $value) : $value;
		}
	}
	
	$user_lang = stripslashes($user_lang);
	$user_dateformat = stripslashes($user_dateformat);
	$user_clockformat = stripslashes($user_clockformat);
	$irc_commands = stripslashes($irc_commands);
	$custom_post_color = stripslashes($custom_post_color);
	$myInfo = stripslashes($myInfo);
	$user_wordwrap = stripslashes($user_wordwrap);
}
else if ( $mode == 'editprofile' && !isset($HTTP_POST_VARS['avatargallery']) && !isset($HTTP_POST_VARS['submitavatar']) && !isset($HTTP_POST_VARS['avatargenerator']) && !isset($HTTP_POST_VARS['submitgenava']) && !isset($HTTP_POST_VARS['cancelavatar']) )
{
	$user_id = $userdata['user_id'];
	$username = $userdata['username'];
	$realname = $userdata['user_realname'];
	$zipcode = $userdata['user_zipcode'];
	$email = $userdata['user_email'];
	$cur_password = $new_password = '';
	$password_confirm = '';
	$icq = $userdata['user_icq'];
	$aim = str_replace('+', ' ', $userdata['user_aim']);
	$msn = $userdata['user_msnm'];
	$yim = $userdata['user_yim'];
   	$xfi = $userdata['user_xfi']; 
	$skype = $userdata['user_skype'];
	$gtalk = $userdata['user_gtalk'];
	$website = $userdata['user_website'];
	$stumble = $userdata['user_stumble'];
	$location = $userdata['user_from'];
	$user_flag = $userdata['user_from_flag'];	
	$occupation = $userdata['user_occ'];
	$interests = $userdata['user_interests'];
	$allow_mass_pm = $userdata['user_allow_mass_pm'];
	$gender = $userdata['user_gender']; 
	$birthday = $userdata['user_birthday']; 
	$signature_bbcode_uid = $userdata['user_sig_bbcode_uid'];
	$xd_meta = get_xd_metadata();
	$xdata = get_user_xdata($userdata['user_id']);
	foreach ($xdata as $name => $value)
	{
		if ($xd_meta[$name]['allow_bbcode'])
		{
	    	$xdata[$name] = ($signature_bbcode_uid != '') ? preg_replace("/:(([a-z0-9]+:)?)$signature_bbcode_uid(=|\])/si", '\\3', $value) : $value;
		}
	}
	$viewemail = $userdata['user_viewemail'];
	$popup_notes = $userdata['user_popup_notes'];
	$profile_view_popup = $userdata['user_profile_view_popup'];
	$notifypm = $userdata['user_notify_pm'];
	$notifypmtext = $userdata['user_notify_pm_text'];
	$popup_pm = $userdata['user_popup_pm'];
	$soundpm = $userdata['user_sound_pm']; 
	$notifyreply = $userdata['user_notify'];
	$notifydonation = $userdata['user_notify_donation'];
	$attachsig = $userdata['user_attachsig'];
	$allowhtml = $userdata['user_allowhtml'];
	$allowbbcode = $userdata['user_allowbbcode'];
	$allowsmilies = $userdata['user_allowsmile'];
    $allowswearywords = $userdata['user_allowswearywords']; 
    $user_transition = $userdata['user_transition']; 
	$allowviewonline = $userdata['user_allow_viewonline'];
	$showsigs = $userdata['user_showsigs']; 
	$showavatars = $userdata['user_showavatars'];
	$user_avatar = $userdata['user_avatar'];
	$user_avatar_type = $userdata['user_avatar_type'];
	$avatar_sticky = $userdata['avatar_sticky'];
	$user_style = $userdata['user_style'];
	$user_lang = $userdata['user_lang'];
	$user_timezone = $userdata['user_timezone'];
	$user_dateformat = $userdata['user_dateformat'];
	$user_clockformat = $userdata['user_clockformat'];
	$irc_commands = $userdata['irc_commands'];
	$custom_post_color = $userdata['user_custom_post_color'];
	$myInfo = $userdata['user_info'];
	$mail_on_topic_moved = $userdata['user_topic_moved_mail'];
	$pm_on_topic_moved = $userdata['user_topic_moved_pm'];
	$pm_on_topic_moved_notify = $userdata['user_topic_moved_pm_notify'];
	$user_wordwrap = $userdata['user_wordwrap'];
}

if ( $board_config['AJAXed_status'] && $board_config['AJAXed_username_check'] )
{
	include($phpbb_root_path . 'includes/sajax.'.$phpEx);
	$sajax->request_type = 'POST';
	$sajax->init('ajax.'.$phpEx);
	$sajax->export('check_username');
	$sajax->handle_client_request();
	$sajax->show_javascript();
	$template->assign_block_vars('switch_ajax', array());
}

//
// Default pages
//
$tmp_error = $error; 
include($phpbb_root_path . 'includes/page_header.'.$phpEx);
$error = $tmp_error;

make_jumpbox('viewforum.'.$phpEx);

if ( $mode == 'editprofile' )
{
	if ( $user_id != $userdata['user_id'] )
	{
		$error = TRUE;
		$error_msg .= $lang['Wrong_Profile'];
	}
}

if( isset($HTTP_POST_VARS['avatargallery']) || isset($HTTP_GET_VARS['avatargallery']) && !$error )
{
	$start = ( !empty($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0; 

	include($phpbb_root_path . 'includes/usercp_avatar.'.$phpEx);

	$avatar_category = ( !empty($HTTP_POST_VARS['avatarcategory']) ) ? htmlspecialchars($HTTP_POST_VARS['avatarcategory']) : '';
	$avatar_category = ( !empty($HTTP_GET_VARS['category']) ) ? htmlspecialchars($HTTP_GET_VARS['category']) : $avatar_category;

	$template->set_filenames(array(
		'body' => 'profile_avatar_gallery.tpl')
	);

	$allowviewonline = !$allowviewonline;

	display_avatar_gallery($mode, $avatar_category, $user_id, $email, $current_email, $coppa, $username, $email, $new_password, $cur_password, $password_confirm, $icq, $aim, $msn, $yim, $xfi, $skype, $website, $stumble, $location, $user_flag, $occupation, $interests, $viewemail, $notifypm, $popup_pm, $soundpm, $notifyreply, $attachsig, $allowhtml, $allowbbcode, $allowsmilies, $allowswearywords, $user_transition, $allowviewonline, $user_style, $user_lang, $user_timezone, $user_dateformat, $user_clockformat, $birthday, $gender, $zipcode, $avatar_sticky, $allow_mass_pm, $profile_view_popup, $showavatars, $showsigs, $popup_notes, $irc_commands, $realname, $custom_post_color, $myInfo, $gtalk, $user_wordwrap, $xdata, $userdata['session_id']);
}
else if( isset($HTTP_POST_VARS['avatargenerator']) || isset($HTTP_GET_VARS['avatargenerator']) && !$error )
{
	include($phpbb_root_path . 'includes/usercp_avatar.'.$phpEx);

	$avatar_filename = ( isset($HTTP_POST_VARS['avatar_filename'])  ) ? htmlspecialchars($HTTP_POST_VARS['avatar_filename']) : $board_config['avatar_path'] . '/' . uniqid(rand()) . '.gif'; 

	if ( file_exists(@phpbb_realpath('./' . $avatar_filename)) )
	{
		@unlink('./' . $avatar_filename);
	}

	$avatar_image = ( !empty($HTTP_POST_VARS['avatarimage']) ) ? htmlspecialchars($HTTP_POST_VARS['avatarimage']) : 'Random';
	$avatar_text  = ( !empty($HTTP_POST_VARS['avatartext']) ) ? htmlspecialchars($HTTP_POST_VARS['avatartext']) : $username;

	$template->set_filenames(array(
		'body' => 'profile_avatar_generator.tpl')
	);

	$allowviewonline = !$allowviewonline;

	display_avatar_generator($mode, $cpl_mode, $avatar_filename, $avatar_image, $avatar_text, $user_id, $email, $current_email, $coppa, $username, $email, $new_password, $cur_password, $password_confirm, $icq, $aim, $msn, $yim, $xfi, $skype, $website, $stumble, $location, $user_flag, $occupation, $interests, $viewemail, $notifypm, $popup_pm, $soundpm, $notifyreply, $attachsig, $allowhtml, $allowbbcode, $allowsmilies, $allowswearywords, $user_transition, $allowviewonline, $user_style, $user_lang, $user_timezone, $user_dateformat, $user_clockformat, $birthday, $gender, $zipcode, $avatar_sticky, $allow_mass_pm, $profile_view_popup, $showavatars, $showsigs, $popup_notes, $irc_commands, $realname, $custom_post_color, $myInfo, $gtalk, $xdata, $userdata['session_id']);
}
else
{
	include($phpbb_root_path . 'includes/functions_selects.'.$phpEx);

	if ( !isset($coppa) )
	{
		$coppa = FALSE;
	}

	if ( !isset($user_style) )
	{
		$user_style = $board_config['default_style'];
	}

	$avatar_img = '';
	if ( $user_avatar_type )
	{
		switch( $user_avatar_type )
		{
			case USER_AVATAR_UPLOAD:
				$avatar_img = ( $board_config['allow_avatar_upload'] ) ? '<img src="' . $board_config['avatar_path'] . '/' . $user_avatar . '" alt="" title="" />' : '';
				break;
			case USER_AVATAR_REMOTE:
				$avatar_img = ( $board_config['allow_avatar_remote'] ) ? '<img src="' . $user_avatar . '" alt="" title="" />' : '';
				break;
			case USER_AVATAR_GALLERY:
				$avatar_img = ( $board_config['allow_avatar_local'] ) ? '<img src="' . $board_config['avatar_gallery_path'] . '/' . $user_avatar . '" alt="" title="" />' : '';
				break;
			case USER_AVATAR_GENERATOR:
				$avatar_img = ( $board_config['allow_avatar_generator'] ) ? '<img src="' . $user_avatar . '" alt="" title="" />' : '';
				break;
		}
	}

	$s_hidden_fields = '<input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="agreed" value="true" /><input type="hidden" name="coppa" value="' . $coppa . '" /><input type="hidden" name="ruid" value="' . $ruid . '" /><input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />';

	if ( $mode == 'editprofile' )
	{
		$s_hidden_fields .= '<input type="hidden" name="user_id" value="' . $userdata['user_id'] . '" />';
		//
		// Send the users current email address. If they change it, and account activation is turned on
		// the user account will be disabled and the user will have to reactivate their account.
		//
		$s_hidden_fields .= '<input type="hidden" name="current_email" value="' . $userdata['user_email'] . '" />';
	}

	if ( !empty($user_avatar_local) )
	{
		$s_hidden_fields .= '<input type="hidden" name="avatarlocal" value="' . $user_avatar_local . '" /><input type="hidden" name="avatarcatname" value="' . $user_avatar_category . '" />';
	}

	if ( !empty($user_avatar_generator) )
	{
		$s_hidden_fields .= '<input type="hidden" name="avatargenerator" value="' . $user_avatar_generator . '" />';
	}

	$html_status =  ( $userdata['user_allowhtml'] && $board_config['allow_html'] ) ? $lang['HTML_is_ON'] : $lang['HTML_is_OFF'];
	$bbcode_status = ( $userdata['user_allowbbcode'] && $board_config['allow_bbcode']  ) ? $lang['BBCode_is_ON'] : $lang['BBCode_is_OFF'];
	$smilies_status = ( $userdata['user_allowsmile'] && $board_config['allow_smilies']  ) ? $lang['Smilies_are_ON'] : $lang['Smilies_are_OFF'];

	$user_sig = $userdata['user_sig'];
	$user_sig_bbcode_uid = $userdata['user_sig_bbcode_uid'];
	
	if ( !$board_config['allow_html'] || !$userdata['user_allowhtml']) 
	{
		if ( $user_sig != '' ) 
		{ 
			$user_sig = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $user_sig); 
		}
	}
	if ( $board_config['allow_bbcode'] ) 
	{
		if ( $user_sig != '' && $user_sig_bbcode_uid != '' ) 
		{ 
			$user_sig = ( $board_config['allow_bbcode'] ) ? bbencode_second_pass($user_sig, $user_sig_bbcode_uid) : preg_replace('/\:[0-9a-z\:]+\]/si', ']', $user_sig); 
		}
	}
	if ( $board_config['allow_smilies'] ) 
	{
		if ( $userdata['user_allowsmile'] && $user_sig != '' ) 
		{ 
			$user_sig = smilies_pass($user_sig); 
		}
	}
	if ( $user_sig != '' ) 
	{ 
		$user_sig = str_replace("\n", "\n<br />\n", $user_sig); 
	}

	switch ($allow_mass_pm) 
	{ 
		case 2: 
	   		$allow_mass_pm_checked = 'checked="checked"';
	   		break; 
	   	case 4: 
	   		$allow_mass_pm_notify_checked = 'checked="checked"';
	   		break; 
	   	default:
	   		$disable_mass_pm_checked = 'checked="checked"'; 
	   		break; 
	} 

	switch ($gender) 
	{ 
	 	case 1: 
	 		$gender_male_checked = 'checked="checked"';
	 		break; 
	 	case 2: 
	 		$gender_female_checked = 'checked="checked"';
	 		break; 
	   	default:
	   		$gender_no_specify_checked = 'checked="checked"'; 
	   		break; 
	} 

	if ($birthday != 999999) 
	{ 
		$b_day = realdate('j', $birthday); 
		$b_md = realdate('n', $birthday); 
		$b_year = realdate('Y', $birthday); 
		$birthday = realdate($lang['Submit_date_format'], $birthday); 
	} 
	else 
	{ 
		$b_day = $b_md = $b_year = $birthday = ''; 
	}

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
	
	
	//
	// Select template
	//
	if ( $mode == REGISTER_MODE ) 
	{
		$template->set_filenames(array(   
			'body' => 'profile_register_body.tpl')
		); 

		//
		// E-mail Digests
		//
		if ($digest_config['auto_subscribe'] == 1)
		{
			$template->assign_block_vars('switch_auto_subscribe_digest', array());
		}

		if ($digest_config['new_sign_up'] == 1)
		{
			$template->assign_block_vars('switch_new_sign_up', array());
		}

		//
		// Visual Confirmation
		//
		$confirm_image = '';
		if (!empty($board_config['enable_confirm']))
		{
			$sql = 'SELECT session_id 
				FROM ' . SESSIONS_TABLE; 
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not select session data', '', __LINE__, __FILE__, $sql);
			}
	
			if ($row = $db->sql_fetchrow($result))
			{
				$confirm_sql = '';
				do
				{
					$confirm_sql .= (($confirm_sql != '') ? ', ' : '') . "'" . $row['session_id'] . "'";
				}
				while ($row = $db->sql_fetchrow($result));
			
				$sql = 'DELETE FROM ' .  CONFIRM_TABLE . " 
					WHERE session_id NOT IN ($confirm_sql)";
				if (!$db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, 'Could not delete stale confirm data', '', __LINE__, __FILE__, $sql);
				}
			}
			$db->sql_freeresult($result);
	
			$sql = 'SELECT COUNT(session_id) AS attempts 
				FROM ' . CONFIRM_TABLE . " 
				WHERE session_id = '" . $userdata['session_id'] . "'";
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not obtain confirm code count', '', __LINE__, __FILE__, $sql);
			}
	
			if ($row = $db->sql_fetchrow($result))
			{
				if ($row['attempts'] > 3)
				{
					message_die(GENERAL_MESSAGE, $lang['Too_many_registers']);
				}
			}
			$db->sql_freeresult($result);
			
			// Generate the required confirmation code
			// NB 0 (zero) could get confused with O (the letter) so we make change it
			$code = dss_rand();
			$code = substr(str_replace('0', 'Z', strtoupper(base_convert($code, 16, 35))), 2, 6);
	
			$confirm_id = md5(uniqid($user_ip));
	
			$sql = 'INSERT INTO ' . CONFIRM_TABLE . " (confirm_id, session_id, code) 
				VALUES ('$confirm_id', '". $userdata['session_id'] . "', '$code')";
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not insert new confirm code information', '', __LINE__, __FILE__, $sql);
			}	
	
			unset($code);
			
			$confirm_image = '<img src="' . append_sid("profile.$phpEx?mode=confirm&amp;id=$confirm_id") . '" alt="" title="" />';
			$s_hidden_fields .= '<input type="hidden" name="confirm_id" value="' . $confirm_id . '" />';
	
			$template->assign_block_vars('switch_confirm', array());
		}

		//
		// Avatar Selection
		//
		if ($board_config['enable_avatar_register'])
		{
			$template->assign_block_vars('switch_avatar_select', array());

			$dir = @opendir($board_config['avatar_gallery_path']); 
		
			$avatar_images = $avatar_names = array(); 
			$avatar_count = 0; 
			while( $avatar_category = @readdir($dir) ) 
			{ 
				if( $avatar_category != '.' && $avatar_category != '..' && !is_file($board_config['avatar_gallery_path'] . '/' . $avatar_category) && !is_link($board_config['avatar_gallery_path'] . '/' . $avatar_category) ) 
				{ 
					$sub_dir = @opendir($board_config['avatar_gallery_path'] . '/' . $avatar_category); 
			
					while( $avatar_file = @readdir($sub_dir) ) 
					{ 
						if( preg_match('/(\.gif$|\.png$|\.jpg|\.jpeg)$/is', $avatar_file) ) 
						{ 
							$avatar_images[$avatar_count] = $avatar_category . '/' . $avatar_file; 
							$avatar_names[$avatar_count] = ucfirst(str_replace("_", " ", preg_replace('/^(.*)\..*$/', '\1', $avatar_category))) . ' - ' . ucfirst(str_replace("_", " ", preg_replace('/^(.*)\..*$/', '\1', $avatar_file))); 
							$avatar_count++; 
						} 
					} 
					@closedir($sub_dir); 
				} 
			} 
		
			@closedir($dir); 
		
			@ksort($avatar_images); 
			@reset($avatar_images); 
		
			// Here we dump arrays above to a long string :) 
			$avatar_select_options = ''; 
			for ($i = 0; $i < $avatar_count; $i++) 
			{ 
				$avatar_select_options .= '<option value="' . $avatar_images[$i] . '">' . $avatar_names[$i] . '</option>'; 
			} 
		}
		
		//
		// Password Generator
		//
		if (!empty($board_config['pass_gen_enable']))
		{
			$charset = '';
			if (!empty($board_config['pass_gen_alphanumerical'])) 
			{
				$charset .= 'A';
			} 
			else if (!empty($board_config['pass_gen_specialchars'])) 
			{
				$charset .= 'S';
			} 
			else if (!empty($board_config['pass_gen_uppercase'])) 
			{
				$charset .= 'U';
			} 
			else if (!empty($board_config['pass_gen_lowercase'])) 
			{
				$charset .= 'L';
			} 
			else if (!empty($board_config['pass_gen_numbers'])) 
			{
				$charset .= 'N';
			}
					
			$data = rand_pass($board_config['pass_gen_length'], $charset, true);
			$pass_gen = $data['pass'];
		}
		
		//
		// VIP Code
		//
		if (!empty($board_config['vip_enable']))
		{
			$template->assign_block_vars('switch_vipcode', array());
		}
	}
	else 
	{
		$template->set_filenames(array(
			'body' => 'profile_add_body.tpl')
		);	
	}

	//
	// Profile CP Organize: Variables for Handling the creation of deeper switches
	//
	if ($mode == 'editprofile')
	{	
		$cpl_registration_info = $cpl_avatar_control = '';
	
		// Assign profile blocks selected for display
		if ( $cpl_mode == 'main' || $cpl_mode == 'all' )
		{
			$template->assign_block_vars('switch_cpl_main', array());
			$cpl_main = 'switch_cpl_main.';
		}
		
		if ( $cpl_mode != 'main' )
		{
			$template->assign_block_vars('switch_cpl_foot.', array());
		}
		if ( $cpl_mode == 'reg_info' || $cpl_mode == 'all' )
		{
			$template->assign_block_vars('switch_cpl_reg_info', array());
			$cpl_registration_info = 'switch_cpl_reg_info.';
		}
		if ( $cpl_mode == 'profile_info' || $cpl_mode == 'all' )
		{
			$template->assign_block_vars('switch_cpl_profile_info', array());
			$cpl_profile_info = 'switch_cpl_profile_info.';
		}
		if ( $cpl_mode == 'preferences' || $cpl_mode == 'all' )
		{
			$template->assign_block_vars('switch_cpl_preferences', array());
			$cpl_preferences_info = 'switch_cpl_preferences.';
		}
		if ( $cpl_mode == 'avatar' || $cpl_mode == 'all' )
		{
			$template->assign_block_vars('switch_cpl_avatar', array());
			$cpl_avatar_control = 'switch_cpl_avatar.';
		}
		if ( $cpl_mode == 'photo' || $cpl_mode == 'all' )
		{
			$template->assign_block_vars('switch_cpl_photo.', array());
			$cpl_photo_control = 'switch_cpl_photo.';
		}
		
		// Add Hidden inputs to replace the ones not displayed.
		if ($cpl_mode != 'reg_info' && $cpl_mode != 'all')
		{
			$s_hidden_fields .= '<input type="hidden" name="username" value="' . $username . '" />';
			$s_hidden_fields .= '<input type="hidden" name="email" value="' . $email . '" />';
		}
		if ($cpl_mode != 'profile_info' && $cpl_mode != 'all')
		{
			$s_hidden_fields .= '<input type="hidden" name="realname" value="' . $realname . '" />';
			$s_hidden_fields .= '<input type="hidden" name="skype" value="' . $skype . '" />';
			$s_hidden_fields .= '<input type="hidden" name="icq" value="' . $icq . '" />';
			$s_hidden_fields .= '<input type="hidden" name="aim" value="' . $aim . '" />';
			$s_hidden_fields .= '<input type="hidden" name="xfi" value="' . $xfi . '" />';
			$s_hidden_fields .= '<input type="hidden" name="msn" value="' . $msn . '" />';
			$s_hidden_fields .= '<input type="hidden" name="yim" value="' . $yim . '" />';
			$s_hidden_fields .= '<input type="hidden" name="gtalk" value="' . $gtalk . '" />';
			$s_hidden_fields .= '<input type="hidden" name="website" value="' . $website . '" />';
			$s_hidden_fields .= '<input type="hidden" name="stumble" value="' . $stumble . '" />';
			$s_hidden_fields .= '<input type="hidden" name="location" value="' . $location . '" />';
			$s_hidden_fields .= '<input type="hidden" name="zipcode" value="' . $zipcode . '" />';
			$s_hidden_fields .= '<input type="hidden" name="occupation" value="' . $occupation . '" />';
			$s_hidden_fields .= '<input type="hidden" name="user_flag" value="' . $user_flag . '" />';
			$s_hidden_fields .= '<input type="hidden" name="interests" value="' . $interests . '" />';
			$s_hidden_fields .= '<input type="hidden" name="gender" value="' . $gender . '" />';
			$s_hidden_fields .= '<input type="hidden" name="birthday" value="' . (($b_day && $b_md && $b_year) ? mkrealdate($b_day, $b_md, $b_year) : 999999) . '" />';
			$s_hidden_fields .= '<input type="hidden" name="next_birthday_greeting" value="' . (($b_day && $b_md && $b_year) ? ((date('md') < $b_md . ( ($b_day <= 9) ? 0 : '' ) . $b_day) ? date('Y') : date('Y') + 1) : '') . '" />';
			$s_hidden_fields .= '<input type="hidden" name="myInfo" value="' . $myInfo . '" />';
	
			reset($xdata);
			while ( list($key, $value) = each($xdata) )
			{
				$s_hidden_fields .= '<input type="hidden" name="' . $key . '" value="' . $value . '" />';
			}
		}
		if ($cpl_mode != 'preferences' && $cpl_mode != 'all')
		{
			$s_hidden_fields .= '<input type="hidden" name="viewemail" value="' . $viewemail . '" />';
			$s_hidden_fields .= '<input type="hidden" name="hideonline" value="' . !$allowviewonline . '" />';
			$s_hidden_fields .= '<input type="hidden" name="notifyreply" value="' . $notifyreply . '" />';
			$s_hidden_fields .= '<input type="hidden" name="group_priority" value="' . $group_priority . '" />';
			$s_hidden_fields .= '<input type="hidden" name="notifydonation" value="' . $notifydonation . '" />';
			$s_hidden_fields .= '<input type="hidden" name="notifypm" value="' . $notifypm . '" />';
			$s_hidden_fields .= '<input type="hidden" name="notifypmtext" value="' . $notifypmtext . '" />';
			$s_hidden_fields .= '<input type="hidden" name="allow_mass_pm" value="' . $allow_mass_pm. '" />';
			$s_hidden_fields .= '<input type="hidden" name="sound_pm" value="' . $soundpm . '" />'; 
			$s_hidden_fields .= '<input type="hidden" name="popup_pm" value="' . $popup_pm . '" />';
			$s_hidden_fields .= '<input type="hidden" name="profile_view_popup" value="' . $profile_view_popup . '" />';
			$s_hidden_fields .= '<input type="hidden" name="popup_notes" value="' . $popup_notes . '" />';
			$s_hidden_fields .= '<input type="hidden" name="allowbbcode" value="' . $allowbbcode . '" />';
			$s_hidden_fields .= '<input type="hidden" name="allowhtml" value="' . $allowhtml . '" />';
			$s_hidden_fields .= '<input type="hidden" name="allowsmilies" value="' . $allowsmilies . '" />';
			$s_hidden_fields .= '<input type="hidden" name="allowswearywords" value="' . $allowswearywords . '" />';
			$s_hidden_fields .= '<input type="hidden" name="user_transition" value="' . $user_transition . '" />';
			$s_hidden_fields .= '<input type="hidden" name="style" value="' . $user_style . '" />';
			$s_hidden_fields .= '<input type="hidden" name="dateformat" value="' . $user_dateformat . '" />';
			$s_hidden_fields .= '<input type="hidden" name="clockformat" value="' . $user_clockformat  . '" />';
			$s_hidden_fields .= '<input type="hidden" name="language" value="' . $user_lang . '" />';
			$s_hidden_fields .= '<input type="hidden" name="timezone" value="' . $user_timezone . '" />';
			$s_hidden_fields .= '<input type="hidden" name="custom_post_color" value="' . $custom_post_color . '" />'; 
			$s_hidden_fields .= '<input type="hidden" name="irc_commands" value="' . $irc_commands . '" />'; 
			$s_hidden_fields .= '<input type="hidden" name="topic_moved_mail" value="' . $mail_on_topic_moved . '" />'; 
			$s_hidden_fields .= '<input type="hidden" name="topic_moved_pm" value="' . $pm_on_topic_moved . '" />'; 
			$s_hidden_fields .= '<input type="hidden" name="topic_moved_pm_notify" value="' . $pm_on_topic_moved_notify . '" />'; 
			$s_hidden_fields .= '<input type="hidden" name="wrap" value="' . $user_wordwrap .'" />';
			$s_hidden_fields .= '<input type="hidden" name="attachsig" value="' . $attachsig . '" />';
			$s_hidden_fields .= '<input type="hidden" name="showsigs" value="' . $showsigs . '" />';
		}
		if ($cpl_mode != 'avatar' && $cpl_mode != 'all')
		{
			$s_hidden_fields .= '<input type="hidden" name="allowavatars" value="' . $showavatars . '" />';
			$s_hidden_fields .= '<input type="hidden" name="avatar_sticky" value="' . $avatar_sticky. '" />';
		}	

		$template->assign_block_vars($cpl_registration_info . 'switch_edit_profile', array());
	}

	if ( ($mode == REGISTER_MODE) || ($board_config['allow_namechange']) )
	{
		$template->assign_block_vars($cpl_registration_info . 'switch_namechange_allowed', array());
	}
	else
	{
		$template->assign_block_vars($cpl_registration_info . 'switch_namechange_disallowed', array());
	}


	//
	// Custom Profile Fields
	//
	$xd_meta = get_xd_metadata();
	while ( list($code_name, $info) = each($xd_meta) )
	{
		if ( xdata_auth($code_name, $userdata['user_id']) || intval($userdata['user_level']) == ADMIN )
		{
			if ($info['display_register'] == XD_DISPLAY_NORMAL)
			{
				$template->assign_block_vars($cpl_profile_info . 'xdata', array(
					'CODE_NAME' => $code_name,
					'NAME' => $info['field_name'],
					'DESCRIPTION' => $info['field_desc'],
	    			'VALUE' => isset($xdata[$code_name]) ? str_replace('"', '&quot;', $xdata[$code_name]) : '',
					'MAX_LENGTH' => ($info['field_length'] > 0) ? $info['field_length'] : '')
				);
	
				switch ($info['field_type'])
				{
					case 'text':
						$template->assign_block_vars($cpl_profile_info . 'xdata.switch_type_text', array());
						break;
	
					case 'textarea':
						$template->assign_block_vars($cpl_profile_info . 'xdata.switch_type_textarea', array());
						break;
	
					case 'radio':
						$template->assign_block_vars($cpl_profile_info . 'xdata.switch_type_radio', array());
	
						while ( list( , $option) = each($info['values_array']) )
						{
		                	$template->assign_block_vars($cpl_profile_info . 'xdata.switch_type_radio.options', array(
		                		'OPTION' => $option,
		                		'CHECKED' => ($xdata[$code_name] == $option) ? 'checked="checked"' : '')
		                	);
						}
						break;
		
					case 'select':
						$template->assign_block_vars($cpl_profile_info . 'xdata.switch_type_select', array());
						
						while ( list( , $option) = each($info['values_array']) )
						{
		                	$template->assign_block_vars($cpl_profile_info . 'xdata.switch_type_select.options', array(
		                		'OPTION' => $option,
		                		'SELECTED' => ($xdata[$code_name] == $option) ? 'selected="selected"' : '')	
	                		);
						}
						break;
				}
			}
			elseif ($info['display_register'] == XD_DISPLAY_ROOT)
			{
	            $template->assign_block_vars($cpl_profile_info . 'xdata', array(
					'CODE_NAME' => $code_name,
		  			'NAME' => $xd_meta[$code_name]['field_name'],
		  			'DESCRIPTION' => $xd_meta[$code_name]['field_desc'],
       				'VALUE' => isset($xdata[$code_name]) ? str_replace('"', '&quot;', $xdata[$code_name]) : '') 
       			);
		  	
		  		$template->assign_block_vars($cpl_profile_info . 'xdata.switch_is_'.$code_name, array());
		  	
		  		switch ($info['field_type'])
				{
					case 'radio':
						while ( list( , $option) = each($info['values_array']) )
						{
		                	$template->assign_block_vars($cpl_profile_info . 'xdata.switch_is_'.$code_name.'.options', array(
		                		'OPTION' => $option,
		                		'CHECKED' => ($xdata[$code_name] == $option) ? 'checked="checked"' : '')
		                	);
						}
						break;

					case 'select':
						while ( list( , $option) = each($info['values_array']) )
						{
		                	$template->assign_block_vars($cpl_profile_info . 'xdata.switch_is_'.$code_name.'.options', array(
		                		'OPTION' => $option,
		                		'SELECTED' => ($xdata[$code_name] == $option) ? 'selected="selected"' : '')
		                	);
						}
						break;
				}
			}
		}
	}

	if ($mode != REGISTER_MODE)
	{
		//
		// Color groups
		//
		$group_values = '';
		$group_count = 0;
	
		if ( is_array($color_groups['groupdata']) )
		{
			foreach($color_groups['groupdata'] AS $color_group)
			{
				if ( in_array($userdata['user_id'], $color_group['group_users']) )
				{
					$group_priority_selected = ( $userdata['group_priority'] == $color_group['group_id'] ) ? ' selected="selected"' : '';
					$group_values .= '<option value="' . $color_group['group_id'] . '" title="' . $color_group['group_name'] . '"' . $group_priority_selected . '>' . $color_group['group_name'] . '</option>';
					$group_count++;
				}
			}
		}
	
		if ( $group_values && $group_count > 1)
		{
			$group_drop_down = '<select name="group_priority">' . $group_values . '</select>';
			$template->assign_block_vars($cpl_preferences_info . 'switch_color_groups', array());
		}
		else
		{
			$group_priority = 0;
		}
			

		//
		// Get list of flags
		//
		$sql = "SELECT *
			FROM " . FLAG_TABLE . "
			ORDER BY flag_id";
		if(!$flags_result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not obtain flags information.', '', __LINE__, __FILE__, $sql);
		}
		$flag_row = $db->sql_fetchrowset($flags_result);
		$num_flags = $db->sql_numrows($flags_result);
	
		// Build the html select statement
		$flag_start_image = 'blank.gif';
		$selected = ( isset($user_flag) ) ? '' : ' selected="selected"';
		$flag_select = "<select name=\"user_flag\" onChange=\"document.images['user_flag'].src = 'images/flags/' + this.value;\" >";
		$flag_select .= '<option value="blank.gif"' . $selected . '>' . $lang['Select_Country'] . '</option>';
		for ($i = 0; $i < $num_flags; $i++)
		{
			$flag_name = $flag_row[$i]['flag_name'];
			$flag_image = $flag_row[$i]['flag_image'];
			$selected = ( isset( $user_flag) ) ? (($user_flag == $flag_image) ? 'selected="selected"' : '' ) : '';
			$flag_select .= '<option value="' . $flag_image . '" title="' . $flag_name . '"' . $selected . '>' . $flag_name . '</option>';
			if ( isset( $user_flag) && ($user_flag == $flag_image))
			{
				$flag_start_image = $flag_image;
			}
		}
		$flag_select .= '</select>';
	}

	
	//
	// Birthdays
	//
	$s_b_day = $lang['Day'] . ': <select name="b_day">
		<option value="0">--</option>
		<option value="1">' . $lang['datetime']['01'] . '</option> 
		<option value="2">' . $lang['datetime']['02'] . '</option> 
		<option value="3">' . $lang['datetime']['03'] . '</option> 
		<option value="4">' . $lang['datetime']['04'] . '</option> 
		<option value="5">' . $lang['datetime']['05'] . '</option> 
		<option value="6">' . $lang['datetime']['06'] . '</option> 
		<option value="7">' . $lang['datetime']['07'] . '</option> 
		<option value="8">' . $lang['datetime']['08'] . '</option> 
		<option value="9">' . $lang['datetime']['09'] . '</option> 
		<option value="10">' . $lang['datetime']['10'] . '</option> 
		<option value="11">' . $lang['datetime']['11'] . '</option> 
		<option value="12">' . $lang['datetime']['12'] . '</option> 
		<option value="13">' . $lang['datetime']['13'] . '</option> 
		<option value="14">' . $lang['datetime']['14'] . '</option> 
		<option value="15">' . $lang['datetime']['15'] . '</option> 
		<option value="16">' . $lang['datetime']['16'] . '</option> 
		<option value="17">' . $lang['datetime']['17'] . '</option> 
		<option value="18">' . $lang['datetime']['18'] . '</option> 
		<option value="19">' . $lang['datetime']['19'] . '</option> 
		<option value="20">' . $lang['datetime']['20'] . '</option> 
		<option value="21">' . $lang['datetime']['21'] . '</option> 
		<option value="22">' . $lang['datetime']['22'] . '</option> 
		<option value="23">' . $lang['datetime']['23'] . '</option> 
		<option value="24">' . $lang['datetime']['24'] . '</option> 
		<option value="25">' . $lang['datetime']['25'] . '</option> 
		<option value="26">' . $lang['datetime']['26'] . '</option> 
		<option value="27">' . $lang['datetime']['27'] . '</option> 
		<option value="28">' . $lang['datetime']['28'] . '</option> 
		<option value="29">' . $lang['datetime']['29'] . '</option> 
		<option value="30">' . $lang['datetime']['30'] . '</option> 
		<option value="31">' . $lang['datetime']['31'] . '</option> 
	</select>&nbsp;&nbsp;';
	$s_b_md = $lang['Month'] . ': <select name="b_md"> 
		<option value="0">--</option> 
		<option value="1" title="' . $lang['datetime']['January'] . '">' . $lang['datetime']['January'] . '</option> 
		<option value="2" title="' . $lang['datetime']['February'] . '">' . $lang['datetime']['February'] . '</option> 
		<option value="3" title="' . $lang['datetime']['March'] . '">' . $lang['datetime']['March'] . '</option> 
		<option value="4" title="' . $lang['datetime']['April'] . '">' . $lang['datetime']['April'] . '</option> 
		<option value="5" title="' . $lang['datetime']['May'] . '">' . $lang['datetime']['May'] . '</option> 
		<option value="6" title="' . $lang['datetime']['June'] . '">' . $lang['datetime']['June'] . '</option> 
		<option value="7" title="' . $lang['datetime']['July'] . '">' . $lang['datetime']['July'] . '</option> 
		<option value="8" title="' . $lang['datetime']['August'] . '">' . $lang['datetime']['August'] . '</option> 
		<option value="9" title="' . $lang['datetime']['September'] . '">' . $lang['datetime']['September'] . '</option> 
		<option value="10" title="' . $lang['datetime']['October'] . '">' . $lang['datetime']['October'] . '</option> 
		<option value="11" title="' . $lang['datetime']['November'] . '">' . $lang['datetime']['November'] . '</option> 
		<option value="12" title="' . $lang['datetime']['December'] . '">' . $lang['datetime']['December'] . '</option> 
	</select>&nbsp;&nbsp;'; 
	$s_b_day = str_replace('value="' . $b_day . '"', 'value="' . $b_day . '" selected="selected"', $s_b_day);
	$s_b_md = str_replace('value="' . $b_md . '"', 'value="' . $b_md . '" selected="selected"', $s_b_md);
	$s_b_year = $lang['Year'] . ': <input type="text" class="post" name="b_year" size="5" maxlength="4" value="' . $b_year . '" />';
	$i = 0;
	$s_birthday = '';
	for ($i = 0; $i <= strlen($lang['Submit_date_format']); $i++) 
	{ 
		switch ($lang['Submit_date_format'][$i]) 
	    { 
	    	case d: 
	    		$s_birthday .= $s_b_day;
	    		break; 
	        case m: 
	        	$s_birthday .= $s_b_md;
	        	break; 
	      	case Y: 
	      		$s_birthday .= $s_b_year;
	      		break; 
		}
	}


	//
	// Let's do an overall check for settings/versions which would prevent
	// us from doing file uploads....
	//
	$ini_val = ( phpversion() >= '4.0.0' ) ? 'ini_get' : 'get_cfg_var';
	$form_enctype = ( @$ini_val('file_uploads') == '0' || strtolower(@$ini_val('file_uploads') == 'off') || phpversion() == '4.0.4pl1' || !$board_config['allow_avatar_upload'] || ( phpversion() < '4.0.3' && @$ini_val('open_basedir') != '' ) ) ? '' : 'enctype="multipart/form-data"';
	
	//
	// Send to template
	//	
	$template->assign_vars(array(
		'USERNAME' => isset($username) ? $username : '',
		'USERNAME_ONKEY' => ( $board_config['AJAXed_username_check'] ) ? 'onkeyup="ua();" ' : '',
		'USERNAME_CHECK' => ( $board_config['AJAXed_username_check'] ) ? '&nbsp;<img src="images/spacer.gif" width="16" height="16" id="AJAXed_username" alt="" title="" />' : '',
		'PASSWORD_ONKEY' => ( $board_config['AJAXed_password_check'] ) ? 'onkeyup="uj();" ' : '',
		'PASSWORD_CHECK' => ( $board_config['AJAXed_password_check'] ) ? '&nbsp;<img src="images/spacer.gif" width="16" height="16" id="AJAXed_password" alt="" title="" />' : '',
		'PASS_GEN' => (!empty($board_config['pass_gen_enable'])) ? sprintf($lang['password_gen'], $pass_gen) : '',
		'ZIPCODE' => $zipcode,
		'CUR_PASSWORD' => isset($cur_password) ? $cur_password : '',
		'NEW_PASSWORD' => isset($new_password) ? $new_password : '',
		'PASSWORD_CONFIRM' => isset($password_confirm) ? $password_confirm : '',
		'EMAIL' => isset($email) ? $email : '',
        'CONFIRM_IMG' => $confirm_image, 
		'YIM' => $yim,
		'ICQ' => $icq,
		'MSN' => $msn,
		'AIM' => $aim,
      	'XFI' => $xfi, 
		'SKYPE' => $skype,
		'GTALK' => $gtalk,
		'OCCUPATION' => $occupation,
		'INTERESTS' => $interests,
		'LOCATION' => $location,
		'FLAG_SELECT' => $flag_select,
		'FLAG_START' => $flag_start_image,
		'GENDER' => $gender, 
		'GENDER_REQUIRED' => ( $board_config['gender_required'] ) ? ' *' : '', 
		'GENDER_NO_SPECIFY_CHECKED' => $gender_no_specify_checked, 
		'GENDER_MALE_CHECKED' => $gender_male_checked, 
		'GENDER_FEMALE_CHECKED' => $gender_female_checked, 
		'BIRTHDAY_REQUIRED' => ( $board_config['birthday_required'] ) ? ' *' : '',
		'WEBSITE' => $website,
		'STUMBLE' => $stumble,
		'VIEW_EMAIL_YES' => ( $viewemail ) ? 'checked="checked"' : '',
		'VIEW_EMAIL_NO' => ( !$viewemail ) ? 'checked="checked"' : '',
		'HIDE_USER_YES' => ( !$allowviewonline ) ? 'checked="checked"' : '',
		'HIDE_USER_NO' => ( $allowviewonline ) ? 'checked="checked"' : '',
		'NOTIFY_PM_YES' => ( $notifypm ) ? 'checked="checked"' : '',
		'NOTIFY_PM_NO' => ( !$notifypm ) ? 'checked="checked"' : '',
		'NOTIFY_PM_TEXT_YES' => ( $notifypmtext ) ? 'checked="checked"' : '',
		'NOTIFY_PM_TEXT_NO' => ( !$notifypmtext ) ? 'checked="checked"' : '',
		'POPUP_PM_YES' => ( $popup_pm ) ? 'checked="checked"' : '',
		'POPUP_PM_NO' => ( !$popup_pm ) ? 'checked="checked"' : '',
		'SOUND_PM_YES' => ( $soundpm ) ? 'checked="checked"' : '', 
		'SOUND_PM_NO' => ( !$soundpm ) ? 'checked="checked"' : '', 
		'ALWAYS_ADD_SIGNATURE_YES' => ( $attachsig ) ? 'checked="checked"' : '',
		'ALWAYS_ADD_SIGNATURE_NO' => ( !$attachsig ) ? 'checked="checked"' : '',
		'NOTIFY_REPLY_YES' => ( $notifyreply ) ? 'checked="checked"' : '',
		'NOTIFY_REPLY_NO' => ( !$notifyreply ) ? 'checked="checked"' : '',
		'NOTIFY_DONATION_YES' => ( $notifydonation ) ? 'checked="checked"' : '',
		'NOTIFY_DONATION_NO' => ( !$notifydonation ) ? 'checked="checked"' : '',
		'ALWAYS_ALLOW_BBCODE_YES' => ( $allowbbcode ) ? 'checked="checked"' : '',
		'ALWAYS_ALLOW_BBCODE_NO' => ( !$allowbbcode ) ? 'checked="checked"' : '',
		'ALWAYS_ALLOW_HTML_YES' => ( $allowhtml ) ? 'checked="checked"' : '',
		'ALWAYS_ALLOW_HTML_NO' => ( !$allowhtml ) ? 'checked="checked"' : '',
		'ALWAYS_ALLOW_SMILIES_YES' => ( $allowsmilies ) ? 'checked="checked"' : '',
		'ALWAYS_ALLOW_SMILIES_NO' => ( !$allowsmilies ) ? 'checked="checked"' : '',
	    'ALWAYS_ALLOW_SWEARYWORDS_YES' => ( $allowswearywords ) ? 'checked="checked"' : '', 
	    'ALWAYS_ALLOW_SWEARYWORDS_NO' => ( !$allowswearywords ) ? 'checked="checked"' : '', 
	    'ALWAYS_ALLOW_TRANSITION_YES' => ( $user_transition  ) ? 'checked="checked"' : '', 
	    'ALWAYS_ALLOW_TRANSITION_NO' => ( !$user_transition ) ? 'checked="checked"' : '', 
		'ALLOW_AVATAR' => $board_config['allow_avatar_upload'],
		'AVATAR' => $avatar_img,
		'AVATAR_SIZE' => $board_config['avatar_filesize'],
		'LANGUAGE_SELECT' => language_select($user_lang, 'language'),
		'STYLE_SELECT' => style_select($user_style, 'style'),
		'TIMEZONE_SELECT' => tz_select($user_timezone, 'timezone'),
		'DATE_FORMAT_SELECT' => date_format_select($user_dateformat, $user_timezone),
		'CLOCK_FORMAT_SELECT' => clock_format_select($user_clockformat),
		'GROUP_PRIORITY_SELECT' => $group_drop_down,
		'HTML_STATUS' => $html_status,
		'BBCODE_STATUS' => sprintf($bbcode_status, '<a href="' . append_sid("faq.$phpEx?mode=bbcode") . '" target="_phpbbcode">', '</a>'),
		'SMILIES_STATUS' => $smilies_status,
		'ALLOW_MASS_PM' => $allow_mass_pm,
		'ALLOW_MASS_PM_CHECKED' => $allow_mass_pm_checked,
		'ALLOW_MASS_PM_NOTIFY_CHECKED' => $allow_mass_pm_notify_checked,
		'DISABLE_MASS_PM_CHECKED' => $disable_mass_pm_checked,
		'AVATAR_SELECT_START' => isset($images['default_avatar']) ? $images['default_avatar'] : 'images/spacer.gif',
		'AVATAR_GALLERY_PATH' => $board_config['avatar_gallery_path'], 
		'AVATAR_SELECT_OPTIONS' => $avatar_select_options, 
		'AVATAR_STICKY_YES' => ( $avatar_sticky ) ? 'checked="checked"' : '',
		'AVATAR_STICKY_NO' => ( !$avatar_sticky ) ? 'checked="checked"' : '',
		'PROFILE_VIEW_POPUP_YES' => ( $profile_view_popup ) ? 'checked="checked"' : '',
		'PROFILE_VIEW_POPUP_NO' => ( !$profile_view_popup ) ? 'checked="checked"' : '',
		'SHOW_SIGS_YES' => ( $showsigs ) ? 'checked="checked"' : '', 
		'SHOW_SIGS_NO' => ( !$showsigs ) ? 'checked="checked"' : '', 
		'POPUP_NOTES_YES' => ( $popup_notes ) ? 'checked="checked"' : '',
		'POPUP_NOTES_NO' => ( !$popup_notes ) ? 'checked="checked"' : '',
		'IRC_COMMANDS' => $irc_commands,
		'SHOW_AVATARS_YES' => ( $showavatars ) ? 'checked="checked"' : '',
		'SHOW_AVATARS_NO' => ( !$showavatars ) ? 'checked="checked"' : '',
      	'REALNAME' => $realname, 
		'MYINFO' => str_replace('<br />', "\n", $myInfo),
		'DIGEST_AUTO_YES' => ( $digest_auto ) ? 'checked="checked"' : '',
		'DIGEST_AUTO_NO' => ( !$digest_auto ) ? 'checked="checked"' : '',
		'DIGEST_NEW_YES' => ( $digest_new ) ? 'checked="checked"' : '',
		'DIGEST_NEW_NO' => ( !$digest_new ) ? 'checked="checked"' : '',
		'TOPIC_MOVED_MAIL_YES' => ( $mail_on_topic_moved ) ? 'checked="checked"' : '',
		'TOPIC_MOVED_MAIL_NO' => ( !$mail_on_topic_moved ) ? 'checked="checked"' : '',
		'TOPIC_MOVED_PM_YES' => ( $pm_on_topic_moved ) ? 'checked="checked"' : '',
		'TOPIC_MOVED_PM_NO' => ( !$pm_on_topic_moved ) ? 'checked="checked"' : '',
		'TOPIC_MOVED_PM_NOTIFY_YES' => ( $pm_on_topic_moved_notify ) ? 'checked="checked"' : '',
		'TOPIC_MOVED_PM_NOTIFY_NO' => ( !$pm_on_topic_moved_notify ) ? 'checked="checked"' : '',
		'WRAP_ROW' => $user_wordwrap,

		'L_LIMIT_USERNAME_LENGTH_EXPLAIN' => sprintf($lang['Allowed_username_length'], $board_config['limit_username_min_length'], $board_config['limit_username_max_length']),
		'L_CURRENT_PASSWORD' => $lang['Current_password'],
		'L_NEW_PASSWORD' => ( $mode == REGISTER_MODE ) ? $lang['Password'] : $lang['New_password'],
		'L_CONFIRM_PASSWORD' => $lang['Confirm_password'],
		'L_CONFIRM_PASSWORD_EXPLAIN' => ( $mode == 'editprofile' ) ? $lang['Confirm_password_explain'] : '',
		'L_PASSWORD_IF_CHANGED' => ( $mode == 'editprofile' ) ? $lang['password_if_changed'] : '',
		'L_PASSWORD_CONFIRM_IF_CHANGED' => ( $mode == 'editprofile' ) ? $lang['password_confirm_if_changed'] : '',
		'L_ICQ_NUMBER' => $lang['ICQ'],
		'L_MESSENGER' => $lang['MSNM'],
		'L_YAHOO' => $lang['YIM'],
		'L_WEBSITE' => $lang['Website'],
		'L_STUMBLE' => $lang['Stumble'],
		'L_AIM' => $lang['AIM'],
     	'L_XFIRE' => $lang['XFI'], 
		'L_SKYPE' => $lang['skype'], 
		'L_GTALK' => $lang['GTALK'],
		'L_LOCATION' => $lang['Location'],
		'L_FLAG' => $lang['Country_Flag'],
		'L_OCCUPATION' => $lang['Occupation'],
		'L_BOARD_LANGUAGE' => $lang['Board_lang'],
		'L_BOARD_STYLE' => $lang['Board_style'],
		'L_TIMEZONE' => $lang['Timezone'],
		'L_DATE_FORMAT' => $lang['Date_format'],
		'L_DATE_FORMAT_EXPLAIN' => $lang['Date_format_explain'],
		'L_CLOCK_FORMAT' => $lang['Clock_format'],
		'L_CLOCK_FORMAT_EXPLAIN' => sprintf($lang['Clock_format_explain'], '<a href="javascript:void(0);" onclick="clocks();">', '</a>'),
		'L_INTERESTS' => $lang['Interests'],
		'L_BIRTHDAY' => $lang['Birthday'], 
		'L_BIRTHDAY_EXPLAIN' => $lang['Birthday_explain'], 
		'L_GENDER' => $lang['Gender'], 
		'L_GENDER_MALE' => $lang['Male'], 
		'L_GENDER_FEMALE' => $lang['Female'], 
		'L_GENDER_NOT_SPECIFY' => $lang['No_gender_specify'],
	    'L_ZIPCODE' => $lang['Zip_code'],
      	'L_ZIPCODE_VIEWABLE' => sprintf($lang['Zip_code_viewable'], '<a href="javascript:void(0);" onclick="spawn();">', '</a>'),
		'L_ALWAYS_ALLOW_SWEARYWORDS' => $lang['Always_swear'], 
		'L_ALWAYS_ALLOW_TRANSITION' => $lang['Enable'] . ' ' . $lang['Page_transition'], 
		'L_ALWAYS_ALLOW_SMILIES' => $lang['Always_smile'],
		'L_ALWAYS_ALLOW_BBCODE' => $lang['Always_bbcode'],
		'L_ALWAYS_ALLOW_HTML' => $lang['Always_html'],
		'L_HIDE_USER' => $lang['Hide_user'],
		'L_ALWAYS_ADD_SIGNATURE' => $lang['Always_add_sig'],
		'L_RETRO_SIG' => $lang['Retro_sig'],
		'L_RETRO_SIG_EXPLAIN' => $lang['Retro_sig_explain'],
		'L_AVATAR_PANEL' => $lang['Avatar_panel'],
		'L_AVATAR_EXPLAIN' => sprintf($lang['Avatar_explain'], $board_config['avatar_max_width'], $board_config['avatar_max_height'], (round($board_config['avatar_filesize'] / 1024))),
		'L_UPLOAD_AVATAR_FILE' => $lang['Upload_Avatar_file'],
		'L_UPLOAD_AVATAR_URL' => $lang['Upload_Avatar_URL'],
		'L_UPLOAD_AVATAR_URL_EXPLAIN' => $lang['Upload_Avatar_URL_explain'],
		'L_AVATAR_GALLERY' => $lang['Select_from_gallery'],
		'L_SHOW_GALLERY' => $lang['View_avatar_gallery'],
		'L_GENERATE_AVATAR' => $lang['Create_with_generator'],
		'L_AVATAR_GENERATOR' => $lang['View_avatar_generator'],
		'L_LINK_REMOTE_AVATAR' => $lang['Link_remote_Avatar'],
		'L_LINK_REMOTE_AVATAR_EXPLAIN' => $lang['Link_remote_Avatar_explain'],
		'L_AVATAR_SELECT' => $lang['Select_avatar'], 
		'L_AVATAR_SELECT_EXPLAIN' => $lang['Avatar_register_explain'], 
		'L_NO_AVATAR' => $lang['NO_AVATAR'], 
		'L_AVATAR_STICKY' => $lang['Avatar_Sticky'],
		'L_AVATAR_STICKY_EXPLAIN' => $lang['Avatar_Sticky_Explain'],
		'L_DELETE_AVATAR' => $lang['Delete_Image'],
		'L_CURRENT_IMAGE' => $lang['Current_Image'],
		'L_ADVANCED_SIG_MODE' => ( $board_config['allow_sig'] && $board_config['enable_sig_editor'] && $userdata['user_sig'] ) ? $lang['Advanced_sig_mode'] : '',
		'L_NOTIFY_ON_REPLY' => $lang['Always_notify'],
		'L_NOTIFY_ON_REPLY_EXPLAIN' => $lang['Always_notify_explain'],
		'L_NOTIFY_ON_PRIVMSG' => $lang['Notify_on_privmsg'],
		'L_NOTIFY_ON_PRIVMSG_TEXT' => $lang['Notify_on_privmsg_text'],
		'L_POPUP_ON_PRIVMSG' => $lang['Popup_on_privmsg'],
		'L_SOUND_ON_PRIVMSG' => $lang['Sound_on_privmsg'], 
		'L_PREFERENCES' => $lang['Preferences'],
		'L_PUBLIC_VIEW_EMAIL' => $lang['Public_view_email'],
		'L_ITEMS_REQUIRED' => $lang['Items_required'],
		'L_REGISTRATION_INFO' => $lang['Registration_info'],
		'L_PROFILE_INFO' => $lang['Profile_info'],
		'L_PROFILE_INFO_NOTICE' => $lang['Profile_info_warn'],
		'L_EMAIL_ADDRESS' => $lang['Email_address'],
		'L_NOTIFY_DONATION' => sprintf($lang['Points_notify'], $board_config['points_name']),
		'L_NOTIFY_DONATION_EXPLAIN' => sprintf($lang['Points_notify_explain'], $board_config['points_name']),
		'L_CONFIRM_CODE_TITLE' => $lang['Confirm_code_title'], 
		'L_CONFIRM_CODE_IMPAIRED' => sprintf($lang['Confirm_code_impaired'], '<a href="mailto:' . $board_config['board_email'] . '?subject=' . $lang['Confirm_code'] . '">', '</a>'), 
		'L_CONFIRM_CODE' => $lang['Confirm_code'], 
		'L_CONFIRM_CODE_EXPLAIN' => $lang['Confirm_code_explain'], 
		'L_ENABLE_MASS_PM' => $lang['Enable_mass_pm'],
		'L_ENABLE_MASS_PM_EXPLAIN' => $lang['Enable_mass_pm_explain'],
        'L_NO_MASS_PM' => $lang['No_mass_pm'],
		'L_PROFILE_VIEW_POPUP' => $lang['Profile_view_option'],
		'L_ALWAYS_ALLOW_SIGS' => $lang['Always_sigs'], 
		'L_POPUP_NOTES' => $lang['popup_notes'],
		'L_IRC_COMMANDS' => $lang['IRC_commands'],
		'L_IRC_EXPLAIN' => $lang['IRC_commands_explain'],
		'L_SHOW_AVATARS' => $lang['Show_avatars'],
		'L_NO_AVATAR_POSTS' => sprintf($lang['No_avatar_posts'], $board_config['avatar_posts']),
		'L_REALNAME' => $lang['real_name'],
		'L_MYINFO_PROFILE' => $lang['myInfo_profile'],
		'L_MYINFO_PROFILE_EXPLAIN' => $lang['myInfo_profile_explain'],
		'L_DIGEST_AUTO' => sprintf($lang['Digest_user_auto'], get_group_name($digest_config['auto_subscribe_group'])),
		'L_DIGEST_NEW' => $lang['Digest_user_new'],
		'L_PHOTO_PANEL' => $lang['Photo_panel'],
		'L_IP_ADDRESS' => $lang['IP_Address'],
		'L_TOPIC_MOVED_MAIL' => $lang['topic_moved_mail'],
		'L_TOPIC_MOVED_PM' => $lang['topic_moved_pm'],
		'L_TOPIC_MOVED_PM_NOTIFY' => $lang['topic_moved_pm_notify'],
		'L_TOPIC_MOVED_PM_NOTIFY_EXPLAIN' => $lang['topic_moved_pm_notify_explain'],
		'L_WORD_WRAP' => $lang['Word_Wrap'],
		'L_WORD_WRAP_EXPLAIN' => strtr($lang['Word_Wrap_Explain'], array('%min%' => $board_config['wrap_min'], '%max%' => $board_config['wrap_max'])),
		'L_WORD_WRAP_EXTRA' => $lang['Word_Wrap_Extra'],
		'L_VIP_CODE' => $lang['Vip_code'],
		'L_VIP_CODE_EXPLAIN' => sprintf($lang['Vip_code_explain'], $board_config['vip_code']),
		'L_GROUP_PRIORITY' => $lang['Group_priority'],

		'S_BIRTHDAY' => $s_birthday,
		'S_ALLOW_AVATAR_UPLOAD' => $board_config['allow_avatar_upload'],
		'S_ALLOW_AVATAR_LOCAL' => $board_config['allow_avatar_local'],
		'S_ALLOW_AVATAR_REMOTE' => $board_config['allow_avatar_remote'],
		'S_ALLOW_AVATAR_GENERATOR' => $board_config['allow_avatar_generator'],
		'S_HIDDEN_FIELDS' => $s_hidden_fields,
		'S_FORM_ENCTYPE' => $form_enctype,
		'U_SEARCH_POSTS' => append_sid('search.'.$phpEx.'?search_author=' . urlencode($userdata['username']) . '&amp;showresults=posts'),
		'U_ADVANCED_SIG_MODE' => append_sid('profile_sig_editor.'.$phpEx),
		'S_PROFILE_ACTION' => append_sid('profile.'.$phpEx.'?mode=' . $mode . '&amp;ucp=' . $cpl_mode))
	);

	//
	// This is another cheat using the block_var capability
	// of the templates to 'fake' an IF...ELSE...ENDIF solution
	// it works well :)
	//
	if ( $mode != REGISTER_MODE )
	{
		if ( $board_config['allow_avatar_upload'] || $board_config['allow_avatar_local'] || $board_config['allow_avatar_remote'] || $board_config['allow_avatar_generator'] )
		{
			$template->assign_block_vars($cpl_avatar_control . 'switch_avatar_block', array());

			if ( ($board_config['avatar_posts'] <= $userdata['user_posts'] || $userdata['user_level'] == ADMIN) && $board_config['allow_avatar_sticky'] )
    		{
    			$template->assign_block_vars($cpl_avatar_control . 'switch_avatar_block.switch_avatar_sticky', array());
	    	}

			if ( ($board_config['avatar_posts'] <= $userdata['user_posts'] || $userdata['user_level'] == ADMIN) && $board_config['allow_avatar_upload'] && file_exists(@phpbb_realpath('./' . $board_config['avatar_path'])) )
			{
				if ( $form_enctype != '' )
				{
					$template->assign_block_vars($cpl_avatar_control . 'switch_avatar_block.switch_avatar_local_upload', array());
				}
				$template->assign_block_vars($cpl_avatar_control . 'switch_avatar_block.switch_avatar_remote_upload', array());
			}

			if ( ($board_config['avatar_posts'] <= $userdata['user_posts'] || $userdata['user_level'] == ADMIN) && $board_config['allow_avatar_remote'] )
			{
				$template->assign_block_vars($cpl_avatar_control . 'switch_avatar_block.switch_avatar_remote_link', array());
			}

			if ( $board_config['allow_avatar_local'] && file_exists(@phpbb_realpath('./' . $board_config['avatar_gallery_path'])) )
			{
				$template->assign_block_vars($cpl_avatar_control . 'switch_avatar_block.switch_avatar_local_gallery', array());
			}

			if ( $board_config['allow_avatar_generator'] && file_exists(@phpbb_realpath('./' . $board_config['avatar_generator_template_path'])) )
			{
				$template->assign_block_vars($cpl_avatar_control . 'switch_avatar_block.switch_avatar_generator', array());
			}

			if ( $board_config['avatar_posts'] > $userdata['user_posts'] && $userdata['user_level'] != ADMIN && ($board_config['allow_avatar_upload'] || $board_config['allow_avatar_remote']) )
			{
				$template->assign_block_vars($cpl_avatar_control . 'switch_avatar_block.switch_avatar_posts_block', array());
			}
		}
		
        if ( $board_config['allow_custom_post_color'] && ($userdata['user_posts'] >= $board_config['allow_custom_post_color']) )
        {
			$template->assign_block_vars($cpl_preferences_info . 'switch_custom_post_color', array(
               	'CUSTOM_POST_COLOR' => $custom_post_color,
             	'L_CUSTOM_POST_COLOR' => $lang['Custom_post_color'], 
                'L_CUSTOM_POST_COLOR_EXPLAIN' => $lang['Custom_post_color_explain'],
                'I_PICK_COLOR' => $images['acp_icon_pickcolor'])
			);
			
        	if (!empty($custom_post_color))
			{
				$template->assign_block_vars($cpl_preferences_info . 'switch_custom_post_color.no_info_color', array(
            		'CUSTOM_POST_COLOR' => $custom_post_color)
            	);
			}
        }        

		if ( $board_config['myInfo_enable'] ) 
		{ 
			$template->assign_block_vars($cpl_profile_info . 'xdata.switch_myInfo_active', array()); 
		} 

		if ( $pjirc_config['irc_status'] ) 
		{
			$template->assign_block_vars($cpl_preferences_info . 'switch_chat_commands', array());
		}

		if ( $board_config['allow_swearywords'] )
		{
			$template->assign_block_vars($cpl_preferences_info . 'switch_swearywords', array());
		}

		if ( $board_config['page_transition'] )
		{
			$template->assign_block_vars($cpl_preferences_info . 'switch_transition', array());
		}

		if ( $board_config['wrap_enable'] )
		{
			$template->assign_block_vars($cpl_preferences_info . 'force_word_wrapping',array());
		}

		if ( $board_config['enable_user_notes'] ) 
		{
			$template->assign_block_vars($cpl_preferences_info . 'switch_notes',array());
			
		}
		
		//
		// Who can disable receiving mass PM ?
		// Set 'A' for Admins, 'M' for Admins/SuperMods/Mods, or 'U' for all Users
		//
		$can_disable_mass_pm = 'U';
		
		switch ( $can_disable_mass_pm ) 
		{ 
			case A:
				if ( $userdata['user_level'] == ADMIN ) 
				{ 
					$template->assign_block_vars($cpl_preferences_info . 'switch_can_disable_mass_pm', array()); 
				} 
				else 
				{ 
					$template->assign_block_vars($cpl_preferences_info . 'switch_can_not_disable_mass_pm', array()); 
				} 
				break;
			case M:
				if ( $userdata['user_level'] == ADMIN || $userdata['user_level'] == LESS_ADMIN || $userdata['user_level'] == MOD ) 
				{ 
					$template->assign_block_vars($cpl_preferences_info . 'switch_can_disable_mass_pm', array()); 
				} 
				else 
				{ 
					$template->assign_block_vars($cpl_preferences_info . 'switch_can_not_disable_mass_pm', array()); 
				} 
				break;
			default:
				$template->assign_block_vars($cpl_preferences_info . 'switch_can_disable_mass_pm', array()); 
				break;
		}
	}
}


//
// Template switches for Styles, Gender, Birthday
//
if ( !$board_config['override_user_style'] ) 
{
	$template->assign_block_vars($cpl_preferences_info . 'override_user_style_block', array());
}
if ( $board_config['gender_required'] )
{
	$template->assign_block_vars($cpl_profile_info . 'switch_gender', array());
}
if ( $board_config['birthday_required'] )
{
	$template->assign_block_vars($cpl_profile_info . 'switch_birthday', array());
}

include($phpbb_root_path . 'profile_menu.'.$phpEx);

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
