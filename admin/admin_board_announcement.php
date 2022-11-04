<?php
/** 
*
* @package admin
* @version $Id: admin_board_announcement.php,v 1.1.0 2007/02/25 22:19:01 lefty Exp $
* @copyright (c) 2007 lefty74
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['General']['Announcements'] = $file;
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);

//
// Pull all announcement data
//
$sql = "SELECT *
	FROM " . ADVANCE_HTML_TABLE . "
	WHERE config_name 
	LIKE '%announcement_%'";
if(!$result = $db->sql_query($sql))
{
	message_die(CRITICAL_ERROR, "Could not query announcement information in admin_announcement_centre", "", __LINE__, __FILE__, $sql);
}
else
{
	while( $row = $db->sql_fetchrow($result) )
	{
		$config_name = $row['config_name'];
		$config_value = $row['config_value'];
		$default_config[$config_name] = isset($HTTP_POST_VARS['submit']) ? str_replace("'", "\'", $config_value) : $config_value;
		
		$new[$config_name] = ( isset($HTTP_POST_VARS[$config_name]) ) ? $HTTP_POST_VARS[$config_name] : $default_config[$config_name];

		if( isset($HTTP_POST_VARS['submit']) )
		{
			$sql = "UPDATE " . ADVANCE_HTML_TABLE . " 
				SET config_value = '" . str_replace("\'", "''", $new[$config_name]) . "'
				WHERE config_name = '$config_name'";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not update general configuration for <b>' . $config_name . '</b>.', '', __LINE__, __FILE__, $sql);
			}
		}
	}
	
	if( isset($HTTP_POST_VARS['preview']) )
	{
		$sql = "UPDATE " . ADVANCE_HTML_TABLE . " 
			SET config_value = '" . str_replace("\'", "''", $new['announcement_text_draft']) . "'
			WHERE config_name = 'announcement_text_draft'";
		if( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Failed to update general configuration for announcement_text_draft", "", __LINE__, __FILE__, $sql);
		}
	}
	
	if( isset($HTTP_POST_VARS['submit']) )
	{
		// Remove cache file
		@unlink($phpbb_root_path . 'cache/config_html.'.$phpEx);

		$message = $lang['Board_config_updated'] . '<br /><br />' . sprintf($lang['Click_return_config'], '<a href="' . append_sid('admin_board_announcement.'.$phpEx) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.'.$phpEx.'?pane=right') . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
	}
}

$announcement_status_yes = ( $new['announcement_status'] ) ? 'checked="checked"' : '';
$announcement_status_no = ( !$new['announcement_status'] ) ? 'checked="checked"' : '';

$show_announcement_all = ( $new['announcement_access'] == ANNOUNCEMENTS_SHOW_ALL ) ? 'checked="checked"' : '';
$show_announcement_reg = ( $new['announcement_access'] == ANNOUNCEMENTS_SHOW_REG ) ? 'checked="checked"' : '';
$show_announcement_mod = ( $new['announcement_access'] == ANNOUNCEMENTS_SHOW_MOD ) ? 'checked="checked"' : '';
$show_announcement_adm = ( $new['announcement_access'] == ANNOUNCEMENTS_SHOW_ADM ) ? 'checked="checked"' : '';

$announcement_guestannouncement_status_yes = ( $new['announcement_guest_status'] ) ? 'checked="checked"' : '';
$announcement_guestannouncement_status_no = ( !$new['announcement_guest_status'] ) ? 'checked="checked"' : '';

//
// Send smilies to template
//
$mode = 'inline';
$inline_columns = 4;
$inline_rows = 5;
$window_columns = 8;

$sql = "SELECT emoticon, code, smile_url   
	FROM " . SMILIES_TABLE . " 
	ORDER BY smilies_id";
if ($result = $db->sql_query($sql))
{
	$num_smilies = 0;
	$rowset = array();
	while ($row = $db->sql_fetchrow($result))
	{
		if (empty($rowset[$row['smile_url']]))
		{
			$rowset[$row['smile_url']]['code'] = str_replace("'", "\\'", str_replace('\\', '\\\\', $row['code']));
			$rowset[$row['smile_url']]['emoticon'] = $row['emoticon'];
			$num_smilies++;
		}
	}

	if ($num_smilies)
	{
		$smilies_count = ($mode == 'inline') ? min(19, $num_smilies) : $num_smilies;
		$smilies_split_row = ($mode == 'inline') ? $inline_columns - 1 : $window_columns - 1;

		$s_colspan = $row = $col = 0;

		while (list($smile_url, $data) = @each($rowset))
		{
			if (!$col)
			{
				$template->assign_block_vars('smilies_row', array());
			}

			$template->assign_block_vars('smilies_row.smilies_col', array(
				'SMILEY_CODE' => $data['code'],
				'SMILEY_IMG' => $phpbb_root_path . $board_config['smilies_path'] . '/' . $smile_url,
				'SMILEY_DESC' => $data['emoticon'])
			);

			$s_colspan = max($s_colspan, $col + 1);
			if ($col == $smilies_split_row)
			{
				if ($mode == 'inline' && $row == $inline_rows - 1)
				{
					break;
				}
				$col = 0;
				$row++;
			}
			else
			{
				$col++;
			}
		}

		if ($mode == 'inline' && $num_smilies > $inline_rows * $inline_columns)
		{
			$template->assign_block_vars('switch_smilies_extra', array());

			$template->assign_vars(array(
				'L_MORE_SMILIES' => $lang['More_emoticons'], 
				'U_MORE_SMILIES_ANNOUNCEMENT_TEXT' => append_sid($phpbb_root_path . "/posting.$phpEx?mode=smilies"))
			);
		}

		$template->assign_vars(array(
			'L_EMOTICONS' => $lang['Emoticons'], 
			'L_CLOSE_WINDOW' => $lang['Close_window'], 
			'S_SMILIES_COLSPAN' => $s_colspan)
		);
	}
}

$template->set_filenames(array(
	'body' => 'admin/board_announcement_body.tpl')
);

//
// Escape any quotes in the site description for proper display in the text
// box on the Announcement Box page 
//
$new['announcement_text'] = str_replace('"', '&quot;', $new['announcement_text']); 
$new['announcement_guest_text'] = str_replace('"', '&quot;', $new['announcement_guest_text']); 
$new['announcement_text_draft'] = str_replace('"', '&quot;', $new['announcement_text_draft']); 

$preview_announcement = $new['announcement_text_draft'];
$preview_announcement_uid = make_bbcode_uid();
$preview_announcement = bbencode_first_pass($preview_announcement, $preview_announcement_uid);
$preview_announcement = bbencode_second_pass ($preview_announcement, $preview_announcement_uid);
$preview_announcement = smilies_pass($preview_announcement);
$preview_announcement = str_replace("\n", "\n<br />\n", $preview_announcement);

$template->assign_vars(array(
	'S_ANNOUNCEMENT_ACTION' => append_sid('admin_board_announcement.'.$phpEx),
 
    'L_ANNOUNCEMENT_MAIN_TITLE' => $lang['Site_Announcement'] . ' ' . $lang['Setting'],	
    'L_ANNOUNCEMENT_MAIN_TITLE_EXPLAIN' => sprintf($lang['Config_explain'], $lang['Site_Announcement']) . ' ' . $lang['Announcement_main_title_explain'],	
	
	'L_BBCODE_B_HELP' => $lang['bbcode_b_help'], 
	'L_BBCODE_I_HELP' => $lang['bbcode_i_help'], 
	'L_BBCODE_U_HELP' => $lang['bbcode_u_help'], 
	'L_BBCODE_Q_HELP' => $lang['bbcode_q_help'], 
	'L_BBCODE_C_HELP' => $lang['bbcode_c_help'], 
	'L_BBCODE_L_HELP' => $lang['bbcode_l_help'], 
	'L_BBCODE_O_HELP' => $lang['bbcode_o_help'], 
	'L_BBCODE_P_HELP' => $lang['bbcode_p_help'], 
	'L_BBCODE_W_HELP' => $lang['bbcode_w_help'], 
	'L_BBCODE_A_HELP' => $lang['bbcode_a_help'], 
	'L_BBCODE_S_HELP' => $lang['bbcode_s_help'], 
	'L_BBCODE_F_HELP' => $lang['bbcode_f_help'], 
	
	'L_EMPTY_MESSAGE' => $lang['Empty_message'],
		
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
		
	'L_FONT_SIZE' => $lang['Font_size'], 
	'L_FONT_TINY' => $lang['font_tiny'], 
	'L_FONT_SMALL' => $lang['font_small'], 
	'L_FONT_NORMAL' => $lang['font_normal'], 
	'L_FONT_LARGE' => $lang['font_large'], 
	'L_FONT_HUGE' => $lang['font_huge'], 
		
	'L_BBCODE_CLOSE_TAGS' => $lang['Close_Tags'], 
	'L_STYLES_TIP' => $lang['Styles_tip'], 
	
	'L_CONFIGURATION' => $lang['Configuration'],	
    'L_ANNOUNCEMENT_DRAFT_TEXT' => $lang['Announcement_draft_text'],
	'L_ANNOUNCEMENT_DRAFT_TEXT_EXPLAIN' => $lang['Announcement_draft_text_explain'],	
	'L_SHOW_ANNOUNCEMENT_TEXT' => $lang['Show_announcement_text'],
	'L_ANNOUNCEMENT_TEXT' => $lang['Announcement_text'],	
    'L_ANNOUNCEMENT_GUEST_TEXT' => $lang['Announcement_guest_text'],
	'L_SELECT_ALL' => $lang['Select_all'],
	'L_COPY_TO_ANNOUNCEMENT' => $lang['Copy_to_Announcement'],
	'L_COPY_TO_GUEST_ANNOUNCEMENT' => $lang['Copy_to_Guest_Announcement'],
	'L_PREVIEW' => $lang['Preview'],
		
	'L_SHOW_ANNOUNCEMENT_ALL' => $lang['All'],
	'L_SHOW_ANNOUNCEMENT_REG' => $lang['External_members'],
	'L_SHOW_ANNOUNCEMENT_MOD' => $lang['Mod'],
	'L_SHOW_ANNOUNCEMENT_ADM' => $lang['Acc_Admin'],
	'L_SHOW_ANNOUNCEMENT_WHO' => $lang['Show_announcement_who'],
	'L_ANNOUNCEMENT_GUESTS_ONLY' => $lang['Announcement_guests_only'],
	'L_ANNOUNCEMENT_GUESTS_ONLY_EXPLAIN' => $lang['Announcement_guests_only_explain'],
 
 	'ANNOUNCEMENT_TEXT' => $new['announcement_text'],
	'ANNOUNCEMENT_GUEST_TEXT' => $new['announcement_guest_text'],
	'ANNOUNCEMENT_TEXT_DRAFT' => $new['announcement_text_draft'],
	'ANNOUNCEMENT_PREVIEW' => $preview_announcement,
	'S_ANNOUNCEMENT_STATUS_YES' => $announcement_status_yes,
	'S_ANNOUNCEMENT_STATUS_NO' => $announcement_status_no,
	'S_ANNOUNCEMENT_GUEST_SEPARATE_STATUS_YES' => $announcement_guestannouncement_status_yes,
	'S_ANNOUNCEMENT_GUEST_SEPARATE_STATUS_NO' => $announcement_guestannouncement_status_no,
	'SHOW_ANNOUNCEMENT_ALL' => ANNOUNCEMENTS_SHOW_ALL,
	'S_SHOW_ANNOUNCEMENT_ALL_CHECKED' => $show_announcement_all,
	'SHOW_ANNOUNCEMENT_REG' => ANNOUNCEMENTS_SHOW_REG,
	'S_SHOW_ANNOUNCEMENT_REG_CHECKED' => $show_announcement_reg,
	'SHOW_ANNOUNCEMENT_MOD' => ANNOUNCEMENTS_SHOW_MOD,
	'S_SHOW_ANNOUNCEMENT_MOD_CHECKED' => $show_announcement_mod,
	'SHOW_ANNOUNCEMENT_ADM' => ANNOUNCEMENTS_SHOW_ADM,
	'S_SHOW_ANNOUNCEMENT_ADM_CHECKED' => $show_announcement_adm)
);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>