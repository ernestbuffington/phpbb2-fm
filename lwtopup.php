<?php
/** 
*
* @package phpBB
* @version $Id: lwtopup.php,v 1.0.0.1 2004/10/29 17:49:33 acydburn Exp $
* @copyright (c) 2004 Loewen Enterprise - Xiong Zou
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//$unhtml_specialchars_match = array('#&gt;#', '#&lt;#', '#&quot;#', '#&amp;#');
//$unhtml_specialchars_replace = array('>', '<', '"', '&');

//
// Set page ID for session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
//
// End session management
//
	
if ( !$userdata['session_logged_in'] ) 
{ 
	redirect("login.".$phpEx."?redirect=lwtopup.".$phpEx); 
	exit; 
} 
	

//
// Include language
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_ipn_grp.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_ipn_grp.' . $phpEx);


//
// Is user a vip?
//
$uservip = 0;
if ( $userdata['user_rank'] > 0 )
{
	$sql = "SELECT r.rank_id, r.rank_title  
		FROM " . RANKS_TABLE . " r 
		WHERE r.rank_id = " . $userdata['user_rank'];
	if ( ($resultr = $db->sql_query($sql)) )
	{
		if( $rowr = $db->sql_fetchrow($resultr) )
		{
			if(strcmp(trim($rowr['rank_title']), trim(VIP_RANK_TITLE)) == 0)
			{
				$uservip = 1;
			}
		}	
	}
}

if( $userdata['user_level'] == ADMIN || $userdata['user_level'] == LESS_ADMIN || $userdata['user_level'] == MOD || $uservip == 1 )
{
	$message = $lang['Account_activated_lw'] . '<br /><br />' . sprintf($lang['Click_return_usercp'], '<a href="' . append_sid('profile.'.$phpEx.'?mode=editprofile&ucp=main') . '">', '</a>'). '<br /><br />' .  sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');
	message_die(GENERAL_MESSAGE, $message);
}

// get payment account, use business account first, if not exist, then choose personal account
$paypalaccount = lw_grap_sys_paypal_acct();
if(strlen($paypalaccount) <= 0)
{
	message_die(GENERAL_ERROR, $lang['LW_PAYPAL_ACCT_ERROR']);
}

$page_title = $lang['LW_TOPUP_TITLE'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'lwtopup_body.tpl')
);
make_jumpbox('viewforum.'.$phpEx); 

$sql = "SELECT * FROM " . GROUPS_TABLE . " 
	WHERE group_type = " . GROUP_PAYMENT . " 
	AND group_amount > 0";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Error getting group information', '', __LINE__, __FILE__, $sql);
}

$group_infos = array();
if( ($group_info = $db->sql_fetchrow($result)) )
{
	do
	{
		$group_infos[] = $group_info;	
	}
	while( $group_info = $db->sql_fetchrow($result) );
}

$group_id = -1;
if( isset($HTTP_POST_VARS['group_id']) || isset($HTTP_GET_VARS['group_id']) )
{
	$group_id = isset($HTTP_POST_VARS['group_id']) ? intval($HTTP_POST_VARS['group_id']) : intval($HTTP_GET_VARS['group_id']);
}

//get arrays of the group this user subscribed to
//$user_group_id = $user_grp_sub_recur = $user_current_memberfee = 0;
$user_group_id = $user_grp_sub_recur = $user_current_memberfee = array();

$sql = "SELECT ug.*, g.* 
	FROM " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g 
	WHERE g.group_type = " . GROUP_PAYMENT . " 
		AND g.group_amount > 0 
		AND g.group_id = ug.group_id 
		AND ug.user_id = " . $userdata['user_id'];
if ( !($result = $db->sql_query($sql)) )
{
		message_die(GENERAL_ERROR, 'Error getting group information', '', __LINE__, __FILE__, $sql);
}

$higheramount = array();
if( ($group_info = $db->sql_fetchrow($result)) )
{
	do
	{
		$higheramount[] = $group_info['group_amount'];
		$user_group_id[] = $group_info['group_id'];
		$user_current_memberfee[] = $group_info['group_amount'];
		$user_grp_sub_recur[] = $group_info['group_sub_recurring'];
	}
	while( $group_info = $db->sql_fetchrow($result) );
}

if($group_id <= 0 && sizeof($user_group_id) > 0)
{
	$group_id = $user_group_id[0];	
}

$groupexplain = 'N/A';
$amount_to_pay = $group_period = $grp_sub_recur = $group_first_trial_fee = $group_first_trial_period = $group_second_trial_fee = $group_second_trial_period = 0;
$groupname = $group_period_basis = $group_first_trial_period_basis = $group_second_trial_period_basis = $optiongroups = '';

$optiongroups .= "<form name=\"SelectGroup\" method=\"post\" action=\"lwtopup.$phpEx\"><input type=\"HIDDEN\" name=\"UpdateGroupBtn\" value=\"true\"><label for=\"group_id\" ><select onChange=\"document.SelectGroup.submit();\" id=\"group_id\" name=\"group_id\" class=\"droplist\" size=\"1\">";
$optiongroups .= "<option value=\"-1\">" . $lang['LW_SELECT_A_GROUP'] . "</option>";

for($i = 0; $i < sizeof($group_infos); $i++)
{
	//get group subscription period + basis, convert from M to Month(s), D to Day(s) and etc
	$grp_period_basis = lw_convert_period_basis($group_infos[$i]['group_period_basis']);
	
	if($group_infos[$i]['group_id'] == $group_id)
	{
		$grp_sub_recur = $group_infos[$i]['group_sub_recurring'];
		$group_first_trial_fee = $group_infos[$i]['group_first_trial_fee'];
		$group_first_trial_period = $group_infos[$i]['group_first_trial_period'];
		$group_first_trial_period_basis = $group_infos[$i]['group_first_trial_period_basis'];
		$group_second_trial_fee = $group_infos[$i]['group_second_trial_fee'];
		$group_second_trial_period = $group_infos[$i]['group_second_trial_period'];
		$group_second_trial_period_basis = $group_infos[$i]['group_second_trial_period_basis'];
		$group_period = $group_infos[$i]['group_period'];
		$group_period_basis = $group_infos[$i]['group_period_basis'];
		
		$groupexplain = $group_infos[$i]['group_description'];
		$amount_to_pay = $group_infos[$i]['group_amount'];
		$groupname = $group_infos[$i]['group_name'];
		$optiongroups .= "<option value=\"" . $group_infos[$i]['group_id'] . "\" selected>" . $group_infos[$i]['group_name'] . " - " . $group_infos[$i]['group_amount'] . " " . $board_config['paypal_currency_code'] . " For " . $group_infos[$i]['group_period'] . " " . $grp_period_basis . "</option>";			
	}
	else
	{
		$optiongroups .= "<option value=\"" . $group_infos[$i]['group_id'] . "\">" . $group_infos[$i]['group_name'] . " - " . $group_infos[$i]['group_amount'] . " " . $board_config['paypal_currency_code'] . " For " . $group_infos[$i]['group_period'] . " " . $grp_period_basis . "</option>";	
	}
}
$optiongroups .= "</select>";
$optiongroups .= "</label><noscript><input type=\"SUBMIT\" name=\"UpdateGroupBtn\" value=\"UpdateGroupBtn\"></noscript></form>";

// removed:check downgrade or upgrade
$payinstruction = '';
$ugrade_memship = 0;

$l_group_to_pay = $lang['L_LW_GROUP_TO_PAY'];

if($group_id == -1)
{
	$payinstruction = 'N/A';
}
else if($group_id > 0) 
{
	if($grp_sub_recur == 1) //mean auto-billing
	{
		$flag = 0;
		$payinstruction .= $lang['L_IPN_Subscribe_term_title'];
		//only allow trial period for new members (whose $userdata['user_expire_date'] <= 0 )
		if($userdata['user_expire_date'] <= 0 && $group_first_trial_period > 0 && strlen($group_first_trial_period_basis) > 0 && strcasecmp($group_first_trial_period_basis, '0') != 0)
		{
			$payinstruction .= "<br />" . ($group_first_trial_fee <= 0 ? $lang['L_IPN_Subscribe_free'] : ($group_first_trial_fee . " " . $board_config['paypal_currency_code'])) . $lang['L_IPN_Subscribe_for_first'] . $group_first_trial_period . " " . lw_convert_period_basis($group_first_trial_period_basis);
			$flag = 1;
		}
		//only allow trial period for new members (whose $userdata['user_expire_date'] <= 0 )
		if($userdata['user_expire_date'] <= 0 && $group_second_trial_period > 0 && strlen($group_second_trial_period_basis) > 0 && strcasecmp($group_second_trial_period_basis, '0') != 0)
		{
			$payinstruction .= "<br />" . $lang['L_IPN_Subscribe_then'] . " " . ($group_second_trial_fee <= 0 ? $lang['L_IPN_Subscribe_free'] : ($group_second_trial_fee . " " . $board_config['paypal_currency_code'])) . $lang['L_IPN_Subscribe_for_next'] . $group_second_trial_period . " " . lw_convert_period_basis($group_second_trial_period_basis);
			$flag = 1;
		}
		if($flag == 1)
		{
			$payinstruction .= "<br />" . $lang['L_IPN_Subscribe_then'] . " " . ($amount_to_pay <= 0 ? $lang['L_IPN_Subscribe_free'] : ($amount_to_pay . " " . $board_config['paypal_currency_code'])) . $lang['L_IPN_Subscribe_for_following'] . $group_period . " " . lw_convert_period_basis($group_period_basis);
			$payinstruction .= "<br />" . $lang['L_IPN_Subscribe_auto_renew'];
		}
		else
		{
			$payinstruction .= "<br />" . ($amount_to_pay <= 0 ? $lang['L_IPN_Subscribe_free'] : ($amount_to_pay . " " . $board_config['paypal_currency_code'])) . $lang['L_IPN_Subscribe_for_every'] . $group_period . " " . lw_convert_period_basis($group_period_basis);
			$payinstruction .= "<br />" . $lang['L_IPN_Subscribe_auto_renew'];
		}	
	}
	else
	{
		$payinstruction .= $lang['L_IPN_Subscribe_term_manual'];		
		$payinstruction .= "<br />" . ($amount_to_pay <= 0 ? $lang['L_IPN_Subscribe_free'] : ($amount_to_pay . " " . $board_config['paypal_currency_code'])) . $lang['L_IPN_Subscribe_for_every'] . $group_period . " " . lw_convert_period_basis($group_period_basis);
		$payinstruction .= "<br />" . $lang['L_IPN_Subscribe_manual_renew'] . $group_period . " " . lw_convert_period_basis($group_period_basis) . ".";
	}
 
}

$server_url = ($board_config['secure_cookie'] == 0 ? 'http://' : 'https://') . $board_config['server_name'];
$server_url .= ($board_config['server_port'] == 80) ? '' : ':' . $board_config['server_port'];
$server_url .= $board_config['script_path'];
$slashpos = ((strlen($board_config['script_path']) - 2) > 0 ) ? (strlen($board_config['script_path']) - 2) : 0;
$pos = strpos($board_config['script_path'], '/', ( $slashpos ) );
if($pos === false)
{
	$server_url .= '/';
}
$notifyurl = $server_url . 'lwtopupresult.' . $phpEx;
$returnurl = $server_url . 'lwtopupshowresult.' . $phpEx;

//$formaction = append_sid('lwtopupconfirm.'.$phpEx);
//$lw_submit = '<input type="submit" name="submit" class="mainoption" value="GO">';
//$lw_submit_hidden_fields = '<input type="hidden" name="upgrade_memship" class="mainoption" value="' . $ugrade_memship . '"><input type="hidden" name="group_name" class="mainoption" value="' . $groupname . '"><input type="hidden" name="group_id" class="mainoption" value="' . $group_id . '"><input type="hidden" name="currency_code" class="mainoption" value="' . $board_config['paypal_currency_code'] . '"><input type="hidden" name="amount" class="mainoption" value="' . $amount_to_pay . '">';
$formaction = $lw_submit = $lw_submit_hidden_fields = '';

$user_in_grp_flag = 0;
for($j = 0; $j < sizeof($user_group_id); $j++)
{
	if($user_group_id[$j] == $group_id) // if already subscribed to a group
	{
		$user_in_grp_flag = 1;
		
		$l_group_to_pay = $lang['L_LW_GROUP_ALREADY_JOIN'];
		$formaction = '';
		
		if($user_grp_sub_recur[$j] == 1)
		{
			//allow unsubscribe
			$formaction = '';
			$lw_submit_hidden_fields = '';
			$lw_submit = sprintf($lang['L_IPN_Subscribe_cancel_paypal'], str_replace('@', '%40', $paypalaccount));
		}
		else
		{
			$formaction = $lang['L_IPN_Subscribe_paypal_sub_url'];	
			$lw_submit = '<input type="submit" class="liteoption" value="' . $lang['L_IPN_Subscribe_extend'] . '" name="submit" />';
			$lw_submit_hidden_fields = '<input type="hidden" name="cmd" value="_xclick">';
			$lw_submit_hidden_fields .= '<input type="hidden" name="amount" value="' . $amount_to_pay . '">';
		  	$lw_submit_hidden_fields .= '<input type="hidden" name="currency_code" value="' . $board_config['paypal_currency_code'] . '">';
			$lw_submit_hidden_fields .= '<input type="hidden" name="business" value="' . $paypalaccount . '">';
			$lw_submit_hidden_fields .= '<input type="hidden" name="item_name" value="Subscribe to group - ' . $groupname . '">';
			$lw_submit_hidden_fields .= '<input type="hidden" name="item_number" value="' . $userdata['user_id'] . '-' . $group_id . '">';
			$lw_submit_hidden_fields .= '<input type="hidden" name="no_shipping" value="1">';
			$lw_submit_hidden_fields .= '<input type="hidden" name="notify_url" value="' . $notifyurl . '">';	
			$lw_submit_hidden_fields .= '<input type="hidden" name="return" value="' . $returnurl . '">';	
			$lw_submit_hidden_fields .= '<input type="hidden" name="cancel_return" value="' . $returnurl . '">';
		}
	}
}
//else if($user_group_id > 0)
//{
//	$l_group_to_pay = $lang['L_LW_GROUP_VIEW_DETAIL'];
//	$formaction = '';
//	if($user_grp_sub_recur == 1)
//	{
//		$lw_submit = 'If you want to subscribe to this group, you will have to <A HREF="https://www.paypal.com/cgi-bin/webscr?cmd=_subscr-find&alias=' . str_replace('@', '%40', $paypalaccount) . '">' . 
//				'<IMG SRC="https://www.paypal.com/en_US/i/btn/cancel_subscribe_gen.gif" BORDER="0"></A> from your current group first. <br>Note: Your unsubscription will take effect when your current expiration date is reached.';
//	}
//	else
//	{
//		$lw_submit = "If you want to subscribe to this group, you have to wait untill the subscription of your current group expires. <br>You can also contact our administrator for help.";	
//	}
//}
//else if($group_id > 0)

if($user_in_grp_flag == 0 && $group_id > 0)
{
	if($grp_sub_recur == 1)
	{
		$formaction = $lang['L_IPN_Subscribe_paypal_sub_url'];
		$lw_submit_hidden_fields = '<input type="hidden" name="cmd" value="_xclick-subscriptions">';
		$lw_submit_hidden_fields .= '<input type="hidden" name="business" value="' . $paypalaccount . '">';
		$lw_submit_hidden_fields .= '<input type="hidden" name="item_name" value="' . $lang['L_IPN_Subscribe_to_grp'] . $groupname . '">';
		$lw_submit_hidden_fields .= '<input type="hidden" name="item_number" value="' . $userdata['user_id'] . '-' . $group_id . '">';
		$lw_submit_hidden_fields .= '<input type="hidden" name="no_shipping" value="1">';
		$lw_submit_hidden_fields .= '<input type="hidden" name="notify_url" value="' . $notifyurl . '">';	
		$lw_submit_hidden_fields .= '<input type="hidden" name="return" value="' . $returnurl . '">';
		$lw_submit_hidden_fields .= '<input type="hidden" name="cancel_return" value="' . $returnurl . '">';
		$lw_submit_hidden_fields .= '<input type="hidden" name="no_note" value="1">';
		$lw_submit_hidden_fields .= '<input type="hidden" name="currency_code" value="' . $board_config['paypal_currency_code'] . '">';

		//if there is trial period #1
		//only allow trial period for new members (whose $userdata['user_expire_date'] <= 0 )
		if($userdata['user_expire_date'] <= 0 && $group_first_trial_period > 0 && strlen($group_first_trial_period_basis) > 0 && strcasecmp($group_first_trial_period_basis, '0') != 0)
		{
			$lw_submit_hidden_fields .= '<input type="hidden" name="a1" value="' . $group_first_trial_fee . '">';
			$lw_submit_hidden_fields .= '<input type="hidden" name="p1" value="' . $group_first_trial_period . '">';
			$lw_submit_hidden_fields .= '<input type="hidden" name="t1" value="' . $group_first_trial_period_basis . '">';
		}

		//if there is trial period #2	
		//only allow trial period for new members (whose $userdata['user_expire_date'] <= 0 )
		if($userdata['user_expire_date'] <= 0 && $group_second_trial_period > 0 && strlen($group_second_trial_period_basis) > 0 && strcasecmp($group_second_trial_period_basis, '0') != 0)
		{
			$lw_submit_hidden_fields .= '<input type="hidden" name="a2" value="' . $group_second_trial_fee . '">';
			$lw_submit_hidden_fields .= '<input type="hidden" name="p2" value="' . $group_second_trial_period . '">';
			$lw_submit_hidden_fields .= '<input type="hidden" name="t2" value="' . $group_second_trial_period_basis . '">';
		}

		$lw_submit_hidden_fields .= '<input type="hidden" name="a3" value="' . $amount_to_pay . '">';
		$lw_submit_hidden_fields .= '<input type="hidden" name="p3" value="' . $group_period . '">';
		$lw_submit_hidden_fields .= '<input type="hidden" name="t3" value="' . $group_period_basis . '">';
		$lw_submit_hidden_fields .= '<input type="hidden" name="src" value="1">';
		$lw_submit_hidden_fields .= '<input type="hidden" name="sra" value="1">';
		
		$lw_submit = '<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-butcc-subscribe.gif" border="0" name="submit" alt="' . $lang['L_IPN_Subscribe_paypal_button_alt'] . ' title="' . $lang['L_IPN_Subscribe_paypal_button_alt'] . '" />';
	}
	else
	{
		$formaction = $lang['L_IPN_Subscribe_paypal_sub_url'];	
		$lw_submit = '<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-butcc-subscribe.gif" name="submit" alt="' . $lang['L_IPN_Subscribe_paypal_button_alt'] . '">';
		$lw_submit_hidden_fields = '<input type="hidden" name="cmd" value="_xclick">';
		$lw_submit_hidden_fields .= '<input type="hidden" name="amount" value="' . $amount_to_pay . '">';
	  	$lw_submit_hidden_fields .= '<input type="hidden" name="currency_code" value="' . $board_config['paypal_currency_code'] . '">';
		$lw_submit_hidden_fields .= '<input type="hidden" name="business" value="' . $paypalaccount . '">';
		$lw_submit_hidden_fields .= '<input type="hidden" name="item_name" value="' . $lang['L_IPN_Subscribe_to_grp'] . $groupname . '">';
		$lw_submit_hidden_fields .= '<input type="hidden" name="item_number" value="' . $userdata['user_id'] . '-' . $group_id . '">';
		$lw_submit_hidden_fields .= '<input type="hidden" name="no_shipping" value="1">';
		$lw_submit_hidden_fields .= '<input type="hidden" name="notify_url" value="' . $notifyurl . '">';	
		$lw_submit_hidden_fields .= '<input type="hidden" name="return" value="' . $returnurl . '">';	
		$lw_submit_hidden_fields .= '<input type="hidden" name="cancel_return" value="' . $returnurl . '">';
	}
}

$template->assign_vars(array(
	'S_LW_TOPUP'		=> append_sid('lwtopup.'.$phpEx),
	'L_LW_TOPUP'		=> $lang['LW_ACCT_DEPOSIT_INTO'],
	'LW_PAYPAL_ACTION'	=> $formaction,	
	'L_LW_TOPUP_TITLE'	=> $lang['LW_TOPUP_TITLE'], 	
	'L_LW_GROUP_TO_PAY'	=> $l_group_to_pay, 	
	'LW_GROUP_TO_PAY' => $optiongroups,
	'L_LW_GROUP_EXPLAIN'	=> $groupexplain,
	'L_LW_GROUP_DESCRIPTION' => $lang['L_LW_GROUP_DESCRIPTION'],
	'LW_UPGRADE_REMIND' => $payinstruction,
	'L_LW_UPGRADE_REMIND' => $lang['L_LW_UPGRADE_REMIND'],
	'LW_SUBMIT_HIDDEN_FIELDS' => $lw_submit_hidden_fields,
	'LW_SUBMIT_ACTION' => $lw_submit)
);

include($phpbb_root_path . 'profile_menu.'.$phpEx);
	
$template->pparse('body');


include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>