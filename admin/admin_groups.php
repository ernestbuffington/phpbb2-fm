<?php
/** 
*
* @package admin
* @version $Id: admin_groups.php,v 1.25.2.9 2004/03/25 15:57:20 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['UserGroups']['Manage'] = $filename;

	return;
}

//
// Load default header
//
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

//
// Include language files
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_mass_pm.'.$phpEx) )
{
	$language = 'english';
}
include($phpbb_root_path . 'language/lang_' . $language . '/lang_mass_pm.' . $phpEx);

if ( isset($HTTP_POST_VARS[POST_GROUPS_URL]) || isset($HTTP_GET_VARS[POST_GROUPS_URL]) )
{
	$group_id = ( isset($HTTP_POST_VARS[POST_GROUPS_URL]) ) ? intval($HTTP_POST_VARS[POST_GROUPS_URL]) : intval($HTTP_GET_VARS[POST_GROUPS_URL]);
}
else
{
	$group_id = 0;
}

if ( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode); 
}
else
{
	$mode = '';
}

attachment_quota_settings('group', $HTTP_POST_VARS['group_update'], $mode);

if ( isset($HTTP_POST_VARS['edit']) || isset($HTTP_GET_VARS['edit']) || isset($HTTP_POST_VARS['new']) )
{
	//
	// Ok they are editing a group or creating a new group
	//
	$template->set_filenames(array(
		'body' => 'admin/group_edit_body.tpl')
	);

	if ( isset($HTTP_POST_VARS['edit']) || isset($HTTP_GET_VARS['edit']))
	{		
		//
		// They're editing. Grab the vars.
		//
		$sql = "SELECT *
			FROM " . GROUPS_TABLE . "
			WHERE group_single_user <> " . TRUE . "
			AND group_id = $group_id";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Error getting group information', '', __LINE__, __FILE__, $sql);
		}

		if ( !($group_info = $db->sql_fetchrow($result)) )
		{
			message_die(GENERAL_MESSAGE, $lang['Group_not_exist']);
		}

		$mode = 'editgroup';
		$template->assign_block_vars('group_edit', array());

	}
	else if ( isset($HTTP_POST_VARS['new']) )
	{
		$group_info = array (
			'group_name' => '',
			'group_description' => '',
			'group_moderator' => '',
			'group_allow_pm' => AUTH_ADMIN,
			'group_type' => GROUP_OPEN,
			'group_digest' => 0,
			'group_members_count' => 0,
			'group_validate' => 1
		);
		$group_open = ' checked="checked"';

		$mode = 'newgroup';

	}

	//
	// Ok, now we know everything about them, let's show the page.
	//
	if ($group_info['group_moderator'] != '')
	{
		$sql = "SELECT user_id, username
			FROM " . USERS_TABLE . "
			WHERE user_id = " . $group_info['group_moderator'];
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain user info for moderator list', '', __LINE__, __FILE__, $sql);
		}

		if ( !($row = $db->sql_fetchrow($result)) )
		{
			message_die(GENERAL_ERROR, 'Could not obtain user info for moderator list', '', __LINE__, __FILE__, $sql);
		}

		$group_moderator = $row['username'];
	}
	else
	{
		$group_moderator = '';
	}

	$sql = "SELECT themes_id AS style_id, style_name AS style_name 
		FROM " . THEMES_TABLE . " 
		ORDER BY style_name";
	if (! $result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, 'Unable to fetch styles data from database.', __LINE__, __FILE__, $sql);
	}
	
	$templatedata = array();
	while ( $row = $db->sql_fetchrow($result) )
	{
		$templatedata[] = $row;
	}
	$db->sql_freeresult($result);

	// Unserialize the group colors.
	$group_info['group_colors'] = unserialize($group_info['group_colors']);

	// Assign the template input boxs
	for ( $i = 0; $i < sizeof($templatedata); $i++ )
	{
		$template->assign_block_vars('styles_block', array(
			'STYLE_ID' => $templatedata[$i]['style_id'],
			'STYLE_NAME' => $templatedata[$i]['style_name'],
			'STYLE_COLOR' => $group_info['group_colors'][$templatedata[$i]['style_id']])
		);
	}

	// Assign the order dropdown
	$template->assign_block_vars('group_row', array(
		'GROUP_ID' => 0,
		'GROUP_NAME' => $lang['color_groups_order_top'],
		'CHECKED' => ( $group_info['group_order'] - 1 == 0 ) ? 'selected="selected"' : '')
	);
	
	if ( is_array($color_groups['groupdata']) )
	{
		foreach ( $color_groups['groupdata'] AS $group_data )
		{
			$template->assign_block_vars('group_row', array(
				'GROUP_ID' => $group_data['group_id'],
				'GROUP_NAME' => '- ' . $group_data['group_name'],
				'CHECKED' => ( $group_info['group_order'] - 1 == $group_data['group_id'] ) ? 'selected="selected"' : '')
			);
		}
	}

	$group_open = ( $group_info['group_type'] == GROUP_OPEN ) ? ' checked="checked"' : '';
	$group_closed = ( $group_info['group_type'] == GROUP_CLOSED ) ? ' checked="checked"' : '';
	$group_hidden = ( $group_info['group_type'] == GROUP_HIDDEN ) ? ' checked="checked"' : '';
	$group_alllow_pm_all = ( $group_info['group_allow_pm'] == AUTH_ALL ) ? ' checked="checked"' : '';
	$group_allow_pm_reg = ( $group_info['group_allow_pm'] == AUTH_REG ) ? ' checked="checked"' : '';
	$group_allow_pm_private = ( $group_info['group_allow_pm'] == AUTH_ACL ) ? ' checked="checked"' : '';
	$group_allow_pm_mod = ( $group_info['group_allow_pm'] == AUTH_MOD ) ? ' checked="checked"' : '';
	$group_allow_pm_admin = ( $group_info['group_allow_pm'] == AUTH_ADMIN ) ? ' checked="checked"' : '';

	$group_payment = ( $group_info['group_type'] == GROUP_PAYMENT ) ? ' checked="checked"' : '';
	$group_period = intval($group_info['group_period']);
	$group_period_basis = $group_info['group_period_basis'];
	$group_first_trial_fee = ($group_info['group_first_trial_fee'] + 0.00);
	$group_first_trial_period = intval($group_info['group_first_trial_period']);
	$group_first_trial_period_basis = ($group_info['group_first_trial_period_basis']);
	$group_second_trial_fee = ($group_info['group_second_trial_fee'] + 0.00);
	$group_second_trial_period = intval($group_info['group_second_trial_period']);
	$group_second_trial_period_basis = ($group_info['group_second_trial_period_basis']);
	$group_sub_recurring = intval($group_info['group_sub_recurring']);
	$group_sub_recurring_stop = intval($group_info['group_sub_recurring_stop']);
	$group_sub_recurring_stop_num = intval($group_info['group_sub_recurring_stop_num']);
	$group_sub_reattempt = intval($group_info['group_sub_reattempt']);

	$grp_billing_circle = '<select name="group_period"><option>--</option>';
	for($i = 1; $i <= 30; $i++ )
	{
		if($group_period == $i)
		{
			$grp_billing_circle .= '<option value="' . $i . '" selected="selected">' . $i . '</option>';
		}
		else
		{
			$grp_billing_circle .= '<option value="' . $i . '">' . $i . '</option>';
		}
	}
	$grp_billing_circle .= '</select>';

	$grp_period_basis = '<select name="group_period_basis">';
	$grp_period_basis .= '<option value="0"' . ((strcasecmp($group_period_basis, '0') == 0) ? 'selected="selected"' : '') . ' >-select one-</option>';
	$grp_period_basis .= '<option value="D"' . ((strcasecmp($group_period_basis, 'D') == 0) ? 'selected="selected"' : '') . ' >Day(s)</option>';
	$grp_period_basis .= '<option value="W"' . ((strcasecmp($group_period_basis, 'W') == 0) ? 'selected="selected"' : '') . ' >Week(s)</option>';
	$grp_period_basis .= '<option value="M"' . ((strcasecmp($group_period_basis, 'M') == 0) ? 'selected="selected"' : '') . ' >Month(s)</option>';
	$grp_period_basis .= '<option value="Y"' . ((strcasecmp($group_period_basis, 'Y') == 0) ? 'selected="selected"' : '') . ' >Year(s)</option>';
	$grp_period_basis .= '</select>';
	
	$grp_recur_stop_num = '<select name="group_sub_recurring_stop_num"><option>--</option>';
	for($i = 2; $i <= 30; $i++ )
	{
		if($group_sub_recurring_stop_num == $i)
		{
			$grp_recur_stop_num .= '<option value="' . $i . '" selected="selected">' . $i . '</option>';
		}
		else
		{
			$grp_recur_stop_num .= '<option value="' . $i . '">' . $i . '</option>';
		}
	}
	$grp_recur_stop_num .= '</select>';
	
	$grp_first_trial_period = '<select name="group_first_trial_period"><option>--</option>';
	for($i = 1; $i <= 30; $i++ )
	{
		if($group_first_trial_period == $i)
		{
			$grp_first_trial_period .= '<option value="' . $i . '" selected="selected">' . $i . '</option>';
		}
		else
		{
			$grp_first_trial_period .= '<option value="' . $i . '">' . $i . '</option>';
		}
	}
	$grp_first_trial_period .= '</select>';
	
	$grp_first_trial_period_basis = '<select name="group_first_trial_period_basis">';
	$grp_first_trial_period_basis .= '<option value="0"' . ((strcasecmp($group_first_trial_period_basis, '0') == 0) ? 'selected="selected"' : '') . ' >-select one-</option>';
	$grp_first_trial_period_basis .= '<option value="D"' . ((strcasecmp($group_first_trial_period_basis, 'D') == 0) ? 'selected="selected"' : '') . ' >Day(s)</option>';
	$grp_first_trial_period_basis .= '<option value="W"' . ((strcasecmp($group_first_trial_period_basis, 'W') == 0) ? 'selected="selected"' : '') . ' >Week(s)</option>';
	$grp_first_trial_period_basis .= '<option value="M"' . ((strcasecmp($group_first_trial_period_basis, 'M') == 0) ? 'selected="selected"' : '') . ' >Month(s)</option>';
	$grp_first_trial_period_basis .= '<option value="Y"' . ((strcasecmp($group_first_trial_period_basis, 'Y') == 0) ? 'selected="selected"' : '') . ' >Year(s)</option>';
	$grp_first_trial_period_basis .= '</select>';
	
	$grp_second_trial_period = '<select name="group_second_trial_period"><opton>--</option>';
	for($i = 1; $i <= 30; $i++ )
	{
		if($group_second_trial_period == $i)
		{
			$grp_second_trial_period .= '<option value="' . $i . '" selected="selected">' . $i . '</option>';
		}
		else
		{
			$grp_second_trial_period .= '<option value="' . $i . '">' . $i . '</option>';
		}
	}
	$grp_second_trial_period .= '</select>';
	
	$grp_second_trial_period_basis = '<select name="group_second_trial_period_basis">';
	$grp_second_trial_period_basis .= '<option value="0"' . ((strcasecmp($group_second_trial_period_basis, '0') == 0) ? 'selected="selected"' : '') . ' >-select one-</option>';
	$grp_second_trial_period_basis .= '<option value="D"' . ((strcasecmp($group_second_trial_period_basis, 'D') == 0) ? 'selected="selected"' : '') . ' >Day(s)</option>';
	$grp_second_trial_period_basis .= '<option value="W"' . ((strcasecmp($group_second_trial_period_basis, 'W') == 0) ? 'selected="selected"' : '') . ' >Week(s)</option>';
	$grp_second_trial_period_basis .= '<option value="M"' . ((strcasecmp($group_second_trial_period_basis, 'M') == 0) ? 'selected="selected"' : '') . ' >Month(s)</option>';
	$grp_second_trial_period_basis .= '<option value="Y"' . ((strcasecmp($group_second_trial_period_basis, 'Y') == 0) ? 'selected="selected"' : '') . ' >Year(s)</option>';
	$grp_second_trial_period_basis .= '</select>';

	$group_validate_yes = ( $group_info['group_validate'] ) ? ' checked="checked"' : '';
	$group_validate_no = ( !$group_info['group_validate'] ) ? ' checked="checked"' : '';

	$s_hidden_fields = '<input type="hidden" name="mode" value="' . $mode . '" />';
	$s_hidden_fields .= '<input type="hidden" name="' . POST_GROUPS_URL . '" value="' . $group_id . '" />';

	$template->assign_vars(array(
		'L_GROUP_TITLE' => $lang['Group_administration'],
		'L_GROUP_EXPLAIN' => ( isset($HTTP_POST_VARS['new']) ) ? '' : $lang['Group_administration_explain'],
		'L_GROUP_EDIT_DELETE' => ( isset($HTTP_POST_VARS['new']) ) ? $lang['New_group'] : $lang['Edit_group'], 
		'L_GROUP_NAME' => $lang['Group_name'],
		'L_GROUP_DESCRIPTION' => $lang['Group_description'],
		'L_GROUP_DESCRIPTION_EXPLAIN' => $lang['group_description_explain'],
		'L_GROUP_MODERATOR' => $lang['group_moderator'], 
		'L_FIND_USERNAME' => $lang['Find_username'], 
		'L_GROUP_STATUS' => $lang['group_status'],
		'L_GROUP_OPEN' => $lang['group_open'],
		'L_GROUP_CLOSED' => $lang['group_closed'],
		'L_GROUP_HIDDEN' => $lang['group_hidden'],
		'L_GROUP_DELETE' => $lang['group_delete'],
		'L_GROUP_DELETE_CHECK' => $lang['group_delete_check'],
		'L_GROUP_ALLOW_PM' => $lang['group_allow_pm'],
		'L_GROUP_ALLOW_PM_EXPLAIN' => $lang['group_allow_pm_explain'],
		'L_GROUP_ALL_ALLOW_PM' => $lang['Forum_ALL'],
		'L_GROUP_REG_ALLOW_PM' => $lang['Forum_REG'],
		'L_GROUP_PRIVATE_ALLOW_PM' => $lang['Forum_PRIVATE'],
		'L_GROUP_MOD_ALLOW_PM' => $lang['Forum_MOD'],
		'L_GROUP_ADMIN_ALLOW_PM' => $lang['Forum_ADMIN'],
		'L_DELETE_MODERATOR' => $lang['delete_group_moderator'],
		'L_DELETE_MODERATOR_EXPLAIN' => $lang['delete_moderator_explain'],
		'L_GROUP_DIGESTS' => $lang['Group_digests'],
		'L_GROUP_DIGESTS_EXPLAIN' => $lang['Group_digest_explain'],
		'L_GROUP_PAYMENT' => $lang['group_payment'],
		'L_GROUP_PAYMENTS_LW' => sprintf($lang['L_GROUP_PAYMENTS_LW'], $board_config['paypal_currency_code']),
		'L_GROUP_PAYMENTS_EXPLAIN' => $lang['L_GROUP_PAYMENTS_EXPLAIN'],
		'L_GROUP_PAYMENTS_TRIAL1' => $lang['L_GROUP_PAYMENTS_TRIAL1'],
		'L_GROUP_PAYMENTS_TRIAL1_EXPLAIN' => $lang['L_GROUP_PAYMENTS_TRIAL1_EXPLAIN'],
		'L_GROUP_PAYMENTS_TRIAL2' => $lang['L_GROUP_PAYMENTS_TRIAL2'],
		'L_GROUP_PAYMENTS_TRIAL2_EXPLAIN' => $lang['L_GROUP_PAYMENTS_TRIAL2_EXPLAIN'],
		'L_GROUP_PAYMENT_OPTIONS' => $lang['L_GROUP_PAYMENT_OPTIONS'],
		'L_GROUP_PAYMENTS_LW_EXPLAIN' => $lang['L_GROUP_PAYMENTS_LW_EXPLAIN'],
		'L_GROUP_PAYMENTS_RECUR' => $lang['L_GROUP_PAYMENTS_RECUR'],
		'L_GROUP_PAYMENTS_RECUR_LENGTH' => $lang['L_GROUP_PAYMENTS_RECUR_LENGTH'],
		'L_GROUP_PAYMENTS_STOP_RECUR' => $lang['L_GROUP_PAYMENTS_STOP_RECUR'],
		'L_GROUP_PAYMENTS_STOP_RECUR_AMT' => $lang['L_GROUP_PAYMENTS_STOP_RECUR_AMT'],
		'L_GROUP_PAYMENTS_FAIL_REATTEMPT' => $lang['L_GROUP_PAYMENTS_FAIL_REATTEMPT'],
		'L_GROUP_PAYMENTS_BILLNOW' => $lang['L_GROUP_PAYMENTS_BILLNOW'],
		'L_GROUP_PAYMENTS_BILLNOW_EXPLAIN' => $lang['L_GROUP_PAYMENTS_BILLNOW_EXPLAIN'],
		'L_GROUP_PAYMENTS_TRIAL_PERIOD' => $lang['L_GROUP_PAYMENTS_TRIAL_PERIOD'],
		'L_GROUP_MEMBERS_COUNT' => $lang['group_members_count'],
		'L_GROUP_VALIDATE' => $lang['admin_group_validate'],
		'L_GROUP_VALIDATE_EXPLAIN' => $lang['admin_group_validate_explain'],
		'L_COLOR_FOR' => $lang['color_groups_for'],
		'L_COLOR_GROUPS' => $lang['color_groups'],
		'L_COLOR_GROUPS_EXPLAIN' => $lang['Color_explain'],
		'L_COLOR_GROUPS_ON' => $lang['color_groups_on'],
		'L_COLOR_GROUPS_ORDER' => $lang['color_groups_order'],
					
		'U_SEARCH_USER' => append_sid("../search.$phpEx?mode=searchuser"), 

		'GROUP_NAME' => $group_info['group_name'],
		'GROUP_DESCRIPTION' => $group_info['group_description'], 
		'GROUP_MODERATOR' => $group_moderator, 
		'GROUP_ALLOW_PM' => $group_info['group_allow_pm'],
		'GROUP_VALIDATE_YES' => $group_validate_yes,
		'GROUP_VALIDATE_NO' => $group_validate_no, 
		'GROUP_MEMBERS_COUNT_YES' => ($group_info['group_members_count']) ? 'checked="checked"' : '',
		'GROUP_MEMBERS_COUNT_NO' => (!$group_info['group_members_count']) ? 'checked="checked"' : '',
		'S_COLOR_GROUPS_ON_CHECKED' => ( $group_info['group_colored'] ) ? 'checked="checked"' : '',
		'S_GROUP_OPEN_TYPE' => GROUP_OPEN,
		'S_GROUP_CLOSED_TYPE' => GROUP_CLOSED,
		'S_GROUP_HIDDEN_TYPE' => GROUP_HIDDEN,
		'S_GROUP_OPEN_CHECKED' => $group_open,
		'S_GROUP_CLOSED_CHECKED' => $group_closed,
		'S_GROUP_HIDDEN_CHECKED' => $group_hidden,
		'S_GROUP_DIGEST_YES' => ($group_info['group_digest']) ? 'checked="checked"' : '',
		'S_GROUP_DIGEST_NO' => (!$group_info['group_digest']) ? 'checked="checked"' : '',
		'S_GROUP_ALL_ALLOW_PM_CHECKED' => $group_allow_pm_all,
		'S_GROUP_REG_ALLOW_PM_CHECKED' => $group_allow_pm_reg,
		'S_GROUP_PRIVATE_ALLOW_PM_CHECKED' => $group_allow_pm_private,
		'S_GROUP_MOD_ALLOW_PM_CHECKED' => $group_allow_pm_mod,
		'S_GROUP_ADMIN_ALLOW_PM_CHECKED' => $group_allow_pm_admin,
		'S_GROUP_ALL_ALLOW_PM' => AUTH_ALL,
		'S_GROUP_REG_ALLOW_PM' => AUTH_REG,
		'S_GROUP_PRIVATE_ALLOW_PM' => AUTH_ACL,
		'S_GROUP_MOD_ALLOW_PM' => AUTH_MOD,
		'S_GROUP_ADMIN_ALLOW_PM' => AUTH_ADMIN,
		'S_GROUP_ACTION' => append_sid("admin_groups.$phpEx"),
		'S_GROUP_PAYMENT_TYPE' => GROUP_PAYMENT,
		'S_GROUP_PAYMENT_CHECKED' => $group_payment,
		'GROUP_AMOUNT_LW' => $group_info['group_amount'],
		'LW_SUB_RECUR' => '<input type="radio" name="group_sub_recurring" value="1"' . ($group_sub_recurring == 1 ? ' checked="checked"' : '') . ' > ' . $lang['Yes'] . '&nbsp;&nbsp;<input type="radio" name="group_sub_recurring" value="0"' . ($group_sub_recurring == 0 ? ' checked="checked"' : '') . '> ' . $lang['No'],
		'LW_BILLING_CIRCLE_PERIOD' => $grp_billing_circle,
		'LW_BILLING_PERIOD_BASIS' => $grp_period_basis,
		'LW_STOP_RECURRING' => '<input type="radio" name="group_sub_recurring_stop" value="1"' . ($group_sub_recurring_stop == 1 ? ' checked="checked"' : '') . ' > ' . $lang['Yes'] . '&nbsp;&nbsp;<input type="radio" name="group_sub_recurring_stop" value="0"' . ($group_sub_recurring_stop == 0 ? ' checked="checked"' : '') . '> ' . $lang['No'],
		'LW_STOP_RECURRING_NUM' => $grp_recur_stop_num,
		'LW_SUBCRIBE_REATTEMPT' => '<input type="radio" name="group_sub_reattempt" value="1"' . ($group_sub_reattempt == 1 ? ' checked="checked"' : '') . ' > ' . $lang['Yes'] . '&nbsp;&nbsp;<input type="radio" name="group_sub_reattempt" value="0"' . ($group_sub_reattempt == 0 ? ' checked="checked"' : '') . '> ' . $lang['No'],
		'GROUP_TRIAL_PERIOD_ONE_FEE_LW' => $group_first_trial_fee,
		'LW_FIRST_TRIAL_PERIOD' => $grp_first_trial_period,
		'LW_FIRST_TRIAL_PERIOD_BASIS' => $grp_first_trial_period_basis,
		'GROUP_TRIAL_PERIOD_TWO_FEE_LW' => $group_second_trial_fee,
		'LW_SECOND_TRIAL_PERIOD' => $grp_second_trial_period,
		'LW_SECOND_TRIAL_PERIOD_BASIS' => $grp_second_trial_period_basis,
		'S_HIDDEN_FIELDS' => $s_hidden_fields)
	);

	$template->pparse('body');

}
else if ( isset($HTTP_POST_VARS['group_update']) )
{
	//
	// Ok, they are submitting a group, let's save the data based on if it's new or editing
	//
	if ( isset($HTTP_POST_VARS['group_delete']) )
	{
		//
		// Reset User Moderator Level
		//

		// Is Group moderating a forum ?
		$sql = "SELECT auth_mod 
			FROM " . AUTH_ACCESS_TABLE . " 
			WHERE group_id = " . $group_id;
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not select auth_access', '', __LINE__, __FILE__, $sql);
		}

		$row = $db->sql_fetchrow($result);
		if (intval($row['auth_mod']) == 1)
		{
			// Yes, get the assigned users and update their Permission if they are no longer moderator of one of the forums
			$sql = "SELECT user_id 
				FROM " . USER_GROUP_TABLE . "
				WHERE group_id = " . $group_id;
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not select user_group', '', __LINE__, __FILE__, $sql);
			}

			$rows = $db->sql_fetchrowset($result);
			for ($i = 0; $i < count($rows); $i++)
			{
				$sql = "SELECT g.group_id 
					FROM " . AUTH_ACCESS_TABLE . " a, " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug
					WHERE (a.auth_mod = 1) 
						AND (g.group_id = a.group_id) 
						AND (a.group_id = ug.group_id) 
						AND (g.group_id = ug.group_id) 
						AND (ug.user_id = " . intval($rows[$i]['user_id']) . ") 
						AND (ug.group_id <> " . $group_id . ")";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not obtain moderator permissions', '', __LINE__, __FILE__, $sql);
				}

				if ($db->sql_numrows($result) == 0)
				{
					$sql = "UPDATE " . USERS_TABLE . " 
						SET user_level = " . USER . " 
						WHERE user_level = " . MOD . " 
							AND user_id = " . intval($rows[$i]['user_id']);
					if ( !$db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'Could not update moderator permissions', '', __LINE__, __FILE__, $sql);
					}
				}
			}
		}

		$sql = "SELECT u.group_priority, u.user_id 
			FROM " . USER_GROUP_TABLE . " ug, " . USERS_TABLE . " u
			WHERE ug.group_id = $group_id
				AND u.user_id = ug.user_id";
		if ( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could select group users.', '', __LINE__, __FILE__, $sql);
		}
		while ( $row = $db->sql_fetchrow($result) )
		{
			if ( $row['group_priority'] == $group_id )
			{
				$sql = 'UPDATE ' . USERS_TABLE . ' 
					SET group_priority = 0 
					WHERE user_id = ' . $row['user_id'];
				if ( !$db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, 'Could not reset group priority.', '', __LINE__, __FILE__, $sql);
				}
			}
		}
		$db->sql_freeresult($result);

		//
		// Delete Group
		//
		$sql = "DELETE FROM " . GROUPS_TABLE . "
			WHERE group_id = " . $group_id;
		if ( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not update group', '', __LINE__, __FILE__, $sql);
		}

		$sql = "DELETE FROM " . USER_GROUP_TABLE . "
			WHERE group_id = " . $group_id;
		if ( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not update user_group', '', __LINE__, __FILE__, $sql);
		}

		$sql = "DELETE FROM " . AUTH_ACCESS_TABLE . "
			WHERE group_id = " . $group_id;
		if ( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not update auth_access', '', __LINE__, __FILE__, $sql);
		}

		$message = $lang['Deleted_group'] . '<br /><br />' . sprintf($lang['Click_return_groupsadmin'], '<a href="' . append_sid("admin_groups.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
	}
	else
	{
		$sql = "SELECT themes_id AS style_id, style_name AS style_name 
			FROM " . THEMES_TABLE . " 
			ORDER BY style_name";
		if (! $result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Unable to fetch styles data from database.', __LINE__, __FILE__, $sql);
		}
		
		$group_colors = $templatedata = array();
		while ( $row = $db->sql_fetchrow($result) )
		{
			$templatedata[] = $row;
		}
		$db->sql_freeresult($result);
		
		for ( $i = 0; $i < sizeof($templatedata); $i++)
		{
			$group_colors[$templatedata[$i]['style_id']] = substr(htmlspecialchars($HTTP_POST_VARS['color_' . $templatedata[$i]['style_id']]), 0, 6);
		}
		$group_colors = serialize($group_colors);
		
		$group_order = intval($HTTP_POST_VARS['color_group_order'] + 1);
		$group_colored_check = isset($HTTP_POST_VARS['group_colored']) ? 1 : 0;
		$group_type = isset($HTTP_POST_VARS['group_type']) ? intval($HTTP_POST_VARS['group_type']) : GROUP_OPEN;
		$group_name = isset($HTTP_POST_VARS['group_name']) ? htmlspecialchars(trim($HTTP_POST_VARS['group_name'])) : '';
		$group_description = isset($HTTP_POST_VARS['group_description']) ? trim($HTTP_POST_VARS['group_description']) : '';
		$group_moderator = isset($HTTP_POST_VARS['username']) ? $HTTP_POST_VARS['username'] : '';
		$group_digest = isset($HTTP_POST_VARS['group_digest']) ? $HTTP_POST_VARS['group_digest'] : 0;
		$delete_old_moderator = isset($HTTP_POST_VARS['delete_old_moderator']) ? true : false;
		$group_allow_pm = isset($HTTP_POST_VARS['group_allow_pm']) ? intval($HTTP_POST_VARS['group_allow_pm']) : AUTH_ADMIN;
		$group_members_count = isset($HTTP_POST_VARS['group_members_count']) ? $HTTP_POST_VARS['group_members_count'] : 0;

		$group_amount = isset($HTTP_POST_VARS['group_amount']) ? ($HTTP_POST_VARS['group_amount'] + 0.00) : 0;
		$group_period = isset($HTTP_POST_VARS['group_period']) ? intval($HTTP_POST_VARS['group_period']) : 0;
		$group_period_basis = isset($HTTP_POST_VARS['group_period_basis']) ? htmlspecialchars($HTTP_POST_VARS['group_period_basis']) : 0;
		$group_first_trial_fee = isset($HTTP_POST_VARS['group_first_trial_fee']) ? ($HTTP_POST_VARS['group_first_trial_fee'] + 0.00) : 0;
		$group_first_trial_period = isset($HTTP_POST_VARS['group_first_trial_period']) ? intval($HTTP_POST_VARS['group_first_trial_period']) : 0;
		$group_first_trial_period_basis = isset($HTTP_POST_VARS['group_first_trial_period_basis']) ? htmlspecialchars($HTTP_POST_VARS['group_first_trial_period_basis']) : 0;
		$group_second_trial_fee = isset($HTTP_POST_VARS['group_second_trial_fee']) ? ($HTTP_POST_VARS['group_second_trial_fee'] + 0.00) : 0;
		$group_second_trial_period = isset($HTTP_POST_VARS['group_second_trial_period']) ? intval($HTTP_POST_VARS['group_second_trial_period']) : 0;
		$group_second_trial_period_basis = isset($HTTP_POST_VARS['group_second_trial_period_basis']) ? htmlspecialchars($HTTP_POST_VARS['group_second_trial_period_basis']) : 0;
		$group_sub_recurring = isset($HTTP_POST_VARS['group_sub_recurring']) ? intval($HTTP_POST_VARS['group_sub_recurring']) : 1;
		$group_sub_recurring_stop = isset($HTTP_POST_VARS['group_sub_recurring_stop']) ? intval($HTTP_POST_VARS['group_sub_recurring_stop']) : 0;
		$group_sub_recurring_stop_num = isset($HTTP_POST_VARS['group_sub_recurring_stop_num']) ? intval($HTTP_POST_VARS['group_sub_recurring_stop_num']) : 0;
		$group_sub_reattempt = isset($HTTP_POST_VARS['group_sub_reattempt']) ? intval($HTTP_POST_VARS['group_sub_reattempt']) : 1;
		$group_validate = ( isset($HTTP_POST_VARS['group_validate']) ) ? intval($HTTP_POST_VARS['group_validate']) : 1;

		if ( $group_name == '' )
		{
			message_die(GENERAL_MESSAGE, $lang['No_group_name']);
		}
		else if ( $group_moderator == '' )
		{
			message_die(GENERAL_MESSAGE, $lang['No_group_moderator']);
		}
		
		$this_userdata = get_userdata($group_moderator, true);
		$group_moderator = $this_userdata['user_id'];

		if ( !$group_moderator )
		{
			message_die(GENERAL_MESSAGE, $lang['No_group_moderator']);
		}
				
		if( $mode == "editgroup" )
		{
			$sql = "SELECT *
				FROM " . GROUPS_TABLE . "
				WHERE group_single_user <> " . TRUE . "
					AND group_id = " . $group_id;
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Error getting group information', '', __LINE__, __FILE__, $sql);
			}

			if( !($group_info = $db->sql_fetchrow($result)) )
			{
				message_die(GENERAL_MESSAGE, $lang['Group_not_exist']);
			}
		
			if ( $group_info['group_moderator'] != $group_moderator )
			{
				if ( $delete_old_moderator )
				{
					$sql = "DELETE FROM " . USER_GROUP_TABLE . "
						WHERE user_id = " . $group_info['group_moderator'] . " 
							AND group_id = " . $group_id;
					if ( !$db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'Could not update group moderator', '', __LINE__, __FILE__, $sql);
					}
					
					if ( $this_userdata['group_priority'] == $group_id )
					{
						$sql = "UPDATE " . USERS_TABLE . " 
							SET group_priority = 0 
							WHERE user_id = $group_moderator";
						if ( !$db->sql_query($sql) )
						{
							message_die(GENERAL_ERROR, 'Could not insert new user-group info', '', __LINE__, __FILE__, $sql);
						}
					}
				}

				$sql = "SELECT user_id 
					FROM " . USER_GROUP_TABLE . " 
					WHERE user_id = $group_moderator 
						AND group_id = $group_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Failed to obtain current group moderator info', '', __LINE__, __FILE__, $sql);
				}

				if ( !($row = $db->sql_fetchrow($result)) )
				{
					$sql = "INSERT INTO " . USER_GROUP_TABLE . " (group_id, user_id, user_pending)
						VALUES (" . $group_id . ", " . $group_moderator . ", 0)";
					if ( !$db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'Could not update group moderator', '', __LINE__, __FILE__, $sql);
					}
				}
			}

			$sql = "UPDATE " . GROUPS_TABLE . "
				SET group_type = $group_type, group_name = '" . str_replace("\'", "''", $group_name) . "', group_description = '" . str_replace("\'", "''", $group_description) . "', group_moderator = $group_moderator, group_amount = $group_amount, group_period = $group_period, group_period_basis = '$group_period_basis', group_first_trial_fee = $group_first_trial_fee,	group_first_trial_period = $group_first_trial_period, group_first_trial_period_basis = '$group_first_trial_period_basis', group_second_trial_fee = $group_second_trial_fee, group_second_trial_period = $group_second_trial_period, group_second_trial_period_basis = '$group_second_trial_period_basis', group_sub_recurring = $group_sub_recurring, group_sub_recurring_stop = $group_sub_recurring_stop, group_sub_recurring_stop_num = $group_sub_recurring_stop_num, group_sub_reattempt = $group_sub_reattempt, group_digest = $group_digest, group_allow_pm = '$group_allow_pm', group_members_count = $group_members_count, group_colored = '$group_colored_check', group_colors = '$group_colors', group_order = '$group_order', group_validate = $group_validate
				WHERE group_id = $group_id";
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not update group', '', __LINE__, __FILE__, $sql);
			}
	
			$message = $lang['Updated_group'] . '<br /><br />' . sprintf($lang['Click_return_groupsadmin'], '<a href="' . append_sid("admin_groups.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');;

			message_die(GENERAL_MESSAGE, $message);
		}
		else if( $mode == 'newgroup' )
		{
			$sql = "SELECT MAX(group_rank_order) AS max_rank_order
				FROM " . GROUPS_TABLE . "
				WHERE group_single_user = 0";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not obtain group max_order', '', __LINE__, __FILE__, $sql);
			}
			$group_rank_order = $db->sql_fetchrow($result);
			$db->sql_freeresult($result);

			$group_rank_order = $group_rank_order['max_rank_order'] + 1;

			$sql = "INSERT INTO " . GROUPS_TABLE . " (group_type, group_name, group_description, group_moderator, group_allow_pm, group_single_user, group_digest, group_amount, group_period, group_period_basis, group_first_trial_fee, group_first_trial_period, group_first_trial_period_basis, group_second_trial_fee, group_second_trial_period, group_second_trial_period_basis, group_sub_recurring, group_sub_recurring_stop, group_sub_recurring_stop_num, group_sub_reattempt, group_members_count, group_colored, group_colors, group_order, group_rank_order, group_validate) 
				VALUES ($group_type, '" . str_replace("\'", "''", $group_name) . "', '" . str_replace("\'", "''", $group_description) . "', $group_moderator, '$group_allow_pm', '0', $group_digest, $group_amount, $group_period, '$group_period_basis', $group_first_trial_fee, $group_first_trial_period, '$group_first_trial_period_basis', $group_second_trial_fee, $group_second_trial_period, '$group_second_trial_period_basis', $group_sub_recurring, $group_sub_recurring_stop, $group_sub_recurring_stop_num, $group_sub_reattempt, $group_members_count, '$group_colored_check', '$group_colors', '$group_order', $group_rank_order, $group_validate)";
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not insert new group', '', __LINE__, __FILE__, $sql);
			}
			$new_group_id = $db->sql_nextid();

			$sql = "INSERT INTO " . USER_GROUP_TABLE . " (group_id, user_id, user_pending)
				VALUES ($new_group_id, $group_moderator, 0)";
			if ( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not insert new user-group info', '', __LINE__, __FILE__, $sql);
			}
			
			$message = $lang['Added_new_group'] . '<br /><br />' . sprintf($lang['Click_return_groupsadmin'], '<a href="' . append_sid("admin_groups.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');;

			message_die(GENERAL_MESSAGE, $message);

		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['No_group_action']);
		}
	}
}
else
{
	$sql = "SELECT group_id, group_name
		FROM " . GROUPS_TABLE . "
		WHERE group_single_user <> " . TRUE . "
		ORDER BY group_name";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain group list', '', __LINE__, __FILE__, $sql);
	}

	$select_list = '';
	if ( $row = $db->sql_fetchrow($result) )
	{
		$select_list .= '<select name="' . POST_GROUPS_URL . '">';
		do
		{
			$select_list .= '<option value="' . $row['group_id'] . '">' . $row['group_name'] . '</option>';
		}
		while ( $row = $db->sql_fetchrow($result) );
		$select_list .= '</select>';
	}

	$template->set_filenames(array(
		'body' => 'admin/group_select_body.tpl')
	);

	$template->assign_vars(array(
		'L_GROUP_TITLE' => $lang['Group_administration'],
		'L_GROUP_EXPLAIN' => $lang['Group_admin_explain'],
		'L_GROUP_SELECT' => $lang['Select_group'],
		'L_LOOK_UP' => $lang['Look_up_Group'],
		'L_CREATE_NEW_GROUP' => $lang['New_group'],

		'S_GROUP_ACTION' => append_sid("admin_groups.$phpEx"),
		'S_GROUP_SELECT' => $select_list)
	);

	if ( $select_list != '' )
	{
		$template->assign_block_vars('select_box', array());
	}

	$template->pparse('body');
}

include('./page_footer_admin.'.$phpEx);

?>