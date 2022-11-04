<?php
/** 
*
* @package admin
* @version $Id: admin_chat_config.php, v 1.0.1 2004/08/11 Midnightz Exp $
* @copyright (c) 2004 Midnightz / AlleyKat
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
//	$module['General']['PJIRC_Chat_Settings'] = $file;
	return;
}

// Load default header
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'includes/functions_selects.'.$phpEx);


//
// Check to see what mode we should operate in.
//
if( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode);
}
else
{
	$mode = 'config';
}


//
// Pull all config data
//
$sql = "SELECT *
	FROM " . PJIRC_TABLE;
if(!$result = $db->sql_query($sql))
{
	message_die(CRITICAL_ERROR, "Could not query chat information in admin_chat_config", "", __LINE__, __FILE__, $sql);
}
else
{ 
	while( $row = $db->sql_fetchrow($result) )
	{
		$pjirc_name = $row['pjirc_name'];
		$pjirc_value = $row['pjirc_value']; 
		$default_config[$pjirc_name] = $pjirc_value;
		
		$new[$pjirc_name] = ( isset($HTTP_POST_VARS[$pjirc_name]) ) ? $HTTP_POST_VARS[$pjirc_name] : $default_config[$pjirc_name];

		if( isset($HTTP_POST_VARS['submit']) )
		{
			$sql = "UPDATE " . PJIRC_TABLE . " SET
				pjirc_value = '" . str_replace("\'", "''", $new[$pjirc_name]) . "'
				WHERE pjirc_name = '$pjirc_name'";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Failed to update chat configuration for $pjirc_name", "", __LINE__, __FILE__, $sql);
			}
		}
	}
	$db->sql_freeresult($result);

	if( isset($HTTP_POST_VARS['submit']) )
	{
		// Remove cache file
		@unlink($phpbb_root_path . 'cache/config_pjirc.'.$phpEx);

		$message = $lang['Board_config_updated'] . "<br /><br />" . sprintf($lang['Click_return_config'], "<a href=\"" . append_sid("admin_chat_config.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message);
	}
}  		


//
// Check to see what section we should load
//
switch($mode)
{
	case 'access':
		$template->assign_block_vars('switch_access', array());
		break;
	case 'baf':
		$template->assign_block_vars('switch_baf', array());
		break;
	case 'smiley':
		$template->assign_block_vars('switch_smiley', array());
		break;
	case 'sound':
		$template->assign_block_vars('switch_sound', array());
		break;
	case 'bot':
		$template->assign_block_vars('switch_bot', array());
		break;
	case 'extra':
		$template->assign_block_vars('switch_extra', array());
		break;
	case 'style':
		$template->assign_block_vars('switch_style', array());
		break;
	case 'config':
	default:
		$template->assign_block_vars('switch_config', array());
		break;
}
$hidden_fields = '<input type="hidden" name="mode" value="' . $mode . '" />';


//
// Languages drop-down thingy
//
$dir = @opendir($phpbb_root_path.'/mods/chatroom/');
$count = 0;
while( $file = @readdir($dir) ) 
{
	if( !@is_dir(phpbb_realpath($phpbb_root_path.'/mods/chatroom/'.$file)) ) 
	{
    	if( preg_match('/(\.txt$)$/is', $file) && !(preg_match('#^pixx#i', $file)) ) 
    	{
        	$chat_langs[$count] = substr($file, 0, -4);
        	$count++;
      	}
   	}
}
@closedir($dir);

$language_list = '<select name="irc_language">';
for( $i = 0; $i < count($chat_langs); $i++ ) 
{
	if ($chat_langs[$i] == $new['irc_language'])
	{
		$language_list .= '<option selected value="'.$chat_langs[$i].'">'.$chat_langs[$i].'</option>';
	}
   	else
    {
    	$language_list .= '<option value="'.$chat_langs[$i].'">'.$chat_langs[$i].'</option>';
    }
}
$language_list .= '</select>';


//
// Templates drop-down thingy
//
$dir = @opendir($phpbb_root_path.'/mods/chatroom/templates/');
$count = 0;
while( $file = @readdir($dir) ) 
{
	if( !@is_dir(phpbb_realpath($phpbb_root_path.'/mods/chatroom/templates/'.$file)) ) 
	{
    	if( preg_match('/(\.php$)$/is', $file) ) 
    	{
        	$chat_temps[$count] = substr($file, 0, -4);
            $count++;
      	}
  	}
}
@closedir($dir);

$template_list = '<select name="irc_template">';
for( $i = 0; $i < count($chat_temps); $i++ ) 
{
	if ($chat_temps[$i] == $new['irc_template'])
    {
    	$template_list .= '<option selected value="'.$chat_temps[$i].'">'.$chat_temps[$i].'</option>';
   	}
   	else
    {
    	$template_list .= '<option value="'.$chat_temps[$i].'">'.$chat_temps[$i].'</option>';
	}
}
$template_list .= '</select>';


//
// Highlighter 0-15 dropdown 
//
$highlightcolor_list = '<select name="irc_highlightcolor">'; 
for( $i = 0; $i < 16 ; $i++ ) 
{ 
	if ($i == $new['irc_highlightcolor']) 
	{ 
    	$highlightcolor_list .= '<option selected value="'.$i.'">'.$i.'</option>'; 
    } 
    else 
    { 
       $highlightcolor_list .= '<option value="'.$i.'">'.$i.'</option>'; 
    } 
} 
$highlightcolor_list .= '</select>';


//
// Basic Configuration
//
$irc_status_open = ( $new['irc_status'] ) ? 'checked="checked"' : '';
$irc_status_closed = ( !$new['irc_status'] ) ? 'checked="checked"' : '';
$irc_server = $new['irc_server'];
$irc_port = $new['irc_port'];
$irc_channel = $new['irc_channel'];
$irc_language = $new['irc_language'];
$irc_template = $new['irc_template'];

// Access Control
$irc_popup_onoff_yes = ( $new['irc_popup_onoff'] ) ? 'checked="checked"' : '';
$irc_popup_onoff_no = ( !$new['irc_popup_onoff'] ) ? 'checked="checked"' : '';
$irc_allow_guests_yes = ( $new['irc_allow_guests'] ) ? 'checked="checked"' : '';
$irc_allow_guests_no = ( !$new['irc_allow_guests'] ) ? 'checked="checked"' : '';
$irc_guestname = $new['irc_guestname'];
$irc_auth_joinlist = $new['irc_auth_joinlist'];

// Buttons & Fields Control
$irc_show_connect_yes = ( $new['irc_show_connect'] ) ? 'checked="checked"' : '';
$irc_show_connect_no = ( !$new['irc_show_connect'] ) ? 'checked="checked"' : '';
$irc_show_chanlist_yes = ( $new['irc_show_chanlist'] ) ? 'checked="checked"' : '';
$irc_show_chanlist_no = ( !$new['irc_show_chanlist'] ) ? 'checked="checked"' : '';
$irc_show_about_yes = ( $new['irc_show_about'] ) ? 'checked="checked"' : '';
$irc_show_about_no = ( !$new['irc_show_about'] ) ? 'checked="checked"' : '';
$irc_show_help_yes = ( $new['irc_show_help'] ) ? 'checked="checked"' : '';
$irc_show_help_no = ( !$new['irc_show_help'] ) ? 'checked="checked"' : '';
$irc_show_close_yes = ( $new['irc_show_close'] ) ? 'checked="checked"' : '';
$irc_show_close_no = ( !$new['irc_show_close'] ) ? 'checked="checked"' : '';
$irc_show_status_yes = ( $new['irc_show_status'] ) ? 'checked="checked"' : '';
$irc_show_status_no = ( !$new['irc_show_status'] ) ? 'checked="checked"' : '';
$irc_show_dock_yes = ( $new['irc_show_dock'] ) ? 'checked="checked"' : '';
$irc_show_dock_no = ( !$new['irc_show_dock'] ) ? 'checked="checked"' : '';
$irc_show_nickfield_yes = ( $new['irc_show_nickfield'] ) ? 'checked="checked"' : '';
$irc_show_nickfield_no = ( !$new['irc_show_nickfield'] ) ? 'checked="checked"' : '';
$irc_time_stamp_yes = ( $new['irc_time_stamp'] ) ? 'checked="checked"' : '';
$irc_time_stamp_no = ( !$new['irc_time_stamp'] ) ? 'checked="checked"' : '';
$irc_topicscroller = $new['irc_topicscroller'];
$irc_quit = $new['irc_quit'];

// Smilies Control
$irc_smilies_on = ( $new['irc_smilies'] ) ? 'checked="checked"' : '';
$irc_smilies_off = ( !$new['irc_smilies'] ) ? 'checked="checked"' : '';
$irc_smilies_enter_yes = ( $new['irc_smilies_enter'] ) ? 'checked="checked"' : '';
$irc_smilies_enter_no = ( !$new['irc_smilies_enter'] ) ? 'checked="checked"' : '';
$irc_smilies_count = $new['irc_smilies_count'];
$irc_smilies_lines = $new['irc_smilies_lines'];

// Sound Control
$irc_sound_beep = $new['irc_sound_beep'];
$irc_enter_timer = $new['irc_enter_timer'];
$irc_sound_query = $new['irc_sound_query'];
$irc_sound_profile = $new['irc_sound_profile'];
$irc_sound_im = $new['irc_sound_im'];
$irc_sound_ignore = $new['irc_sound_ignore'];
$irc_sound_unignore = $new['irc_sound_unignore'];
$irc_sound_away = $new['irc_sound_away'];
$irc_sound_back = $new['irc_sound_back'];
$irc_sound_clear = $new['irc_sound_clear'];
$irc_sound_whois = $new['irc_sound_whois'];
$irc_sound_help = $new['irc_sound_help'];
$irc_sound1 = $new['irc_sound1']; 
$irc_soundwords1 = $new['irc_soundwords1']; 
$irc_sound2 = $new['irc_sound2']; 
$irc_soundwords2 = $new['irc_soundwords2'];

// BOT Control
$irc_bot_overall_yes = ( $new['irc_bot_overall'] ) ? 'checked="checked"' : '';
$irc_bot_overall_no = ( !$new['irc_bot_overall'] ) ? 'checked="checked"' : '';
$irc_bot_switch1_yes = ( $new['irc_bot_switch1'] ) ? 'checked="checked"' : '';
$irc_bot_switch1_no = ( !$new['irc_bot_switch1'] ) ? 'checked="checked"' : '';
$irc_bot_overall_timer = $new['irc_bot_timer'];
$irc_bot_switch2_yes = ( $new['irc_bot_switch2'] ) ? 'checked="checked"' : '';
$irc_bot_switch2_no = ( !$new['irc_bot_switch2'] ) ? 'checked="checked"' : '';

// Advanced Configuration
$irc_channel2 = $new['irc_channel2'];
$irc_channel3 = $new['irc_channel3'];
$irc_channel2_yes = ( $new['irc_channel2_on'] ) ? 'checked="checked"' : ''; 
$irc_channel2_no = ( !$new['irc_channel2_on'] ) ? 'checked="checked"' : '';
$irc_channel3_yes = ( $new['irc_channel3_on'] ) ? 'checked="checked"' : ''; 
$irc_channel3_no = ( !$new['irc_channel3_on'] ) ? 'checked="checked"' : '';
$irc_multiserver_yes = ( $new['irc_multiserver'] ) ? 'checked="checked"' : '';
$irc_multiserver_no = ( !$new['irc_multiserver'] ) ? 'checked="checked"' : '';
$irc_multiserver_server = $new['irc_multiserver_server']; 
$irc_multiserver_port = $new['irc_multiserver_port']; 
$irc_multiserver_delay = $new['irc_multiserver_delay'];
$irc_use_info_yes = ( $new['irc_use_info'] ) ? 'checked="checked"' : '';
$irc_use_info_no = ( !$new['irc_use_info'] ) ? 'checked="checked"' : '';

// Advanced Styles Control
$irc_style_selector_yes = ( $new['irc_style_selector'] ) ? 'checked="checked"' : '';
$irc_style_selector_no = ( !$new['irc_style_selector'] ) ? 'checked="checked"' : '';
$irc_style_selector_definition = ( $new['irc_style_selector_definition'] );
$irc_font_style_yes = ( $new['irc_font_style'] ) ? 'checked="checked"' : '';
$irc_font_style_no = ( !$new['irc_font_style'] ) ? 'checked="checked"' : '';
$irc_font_style_definition = ( $new['irc_font_style_definition'] );
$irc_style_nick_left = $new['irc_style_nick_left']; 
$irc_style_nick_right = $new['irc_style_nick_right'];
$irc_show_highlight_yes = ( $new['irc_show_highlight'] ) ? 'checked="checked"' : '';
$irc_show_highlight_no = ( !$new['irc_show_highlight'] ) ? 'checked="checked"' : '';
$irc_highlightcolor = $new['irc_highlightcolor'];
$irc_highlightwords = $new['irc_highlightwords'];
$irc_background_off = ( $new['irc_background_which'] == 0 ) ? 'checked="checked"' : '';
$irc_background_default1 = ( $new['irc_background_which'] == 1 ) ? 'checked="checked"' : '';
$irc_background_default2 = ( $new['irc_background_which'] == 2 ) ? 'checked="checked"' : '';
$irc_background_custom = ( $new['irc_background_which'] == 3 ) ? 'checked="checked"' : '';
$irc_background_file = $new['irc_background_file'];

$template->set_filenames(array(
	'body' => 'admin/chat_config_body.tpl')
);

$template->assign_vars(array( 
    'S_CHAT_ACTION' => append_sid('admin_chat_config.'.$phpEx),

	'L_IRC' => $lang['PJIRC_Chat_Settings'] . ' ' . $lang['Setting'],
	'L_IRC_CHAT_EXPLAIN' => sprintf($lang['Config_explain'], $lang['PJIRC_Chat_Settings']),
		
    'L_IRC_BASIC' => $lang['IRC_basic'],
    'L_STATUS' => $lang['IRC_status'],
    'L_STATUS_OPEN' => $lang['IRC_status_open'],
    'L_STATUS_CLOSED' => $lang['IRC_status_closed'],
	'L_SERVER' => $lang['IRC_server'],
	'L_PORT' => $lang['IRC_port'],
	'L_CHANNEL' => $lang['IRC_channel'],
    'L_IRC_LANGUAGE' => $lang['Default_language'],
    'L_IRC_TEMPLATE' => $lang['Default_style'],
    'L_IRC_ACCESS' => $lang['IRC_access'],
    'L_POPUP_ONOFF' => $lang['IRC_popup_onoff'],
    'L_ALLOW_GUESTS' => $lang['IRC_allow_guests'],
	'L_GUESTNAME' => $lang['IRC_guestname'],
    'L_GUESTNAME_EXPLAIN' => $lang['IRC_guestname_explain'],
    'L_AUTH_JOINLIST' => $lang['IRC_auth_joinlist'],
    'L_AUTH_JOINLIST_EXPLAIN' => $lang['IRC_auth_joinlist_explain'],
    'L_IRC_BUTTONS' => $lang['IRC_buttons'],
    'L_SHOW_CONNECT' => $lang['IRC_show_connect'],
	'L_SHOW_CHANLIST' => $lang['IRC_show_chanlist'],
	'L_SHOW_ABOUT' => $lang['IRC_show_about'],
	'L_SHOW_HELP' => $lang['IRC_show_help'],
    'L_SHOW_CLOSE' => $lang['IRC_show_close'],
	'L_SHOW_STATUS' => $lang['IRC_show_status'],
	'L_SHOW_DOCK' => $lang['IRC_show_dock'],
	'L_SHOW_NICKFIELD' => $lang['IRC_show_nickfield'],
    'L_TIME_STAMP' => $lang['IRC_time_stamp'],
    'L_TOPICSCROLLER' => $lang['IRC_topicscroller'],
    'L_TOPICSCROLLER_DEFINITION_EXPLAIN' => $lang['IRC_topicscroller_definition_explain'],
    'L_QUIT' => $lang['IRC_quit'],
	'L_SMILIES_CONTROL' => $lang['Smiley'] . ' ' . $lang['Setting'],
	'L_SMILIES' => $lang['IRC_smilies'],
    'L_SMILEY_ENTER' => $lang['IRC_smilies_enter'],
    'L_SMILEY_COUNT' => $lang['IRC_smilies_count'],
	'L_SMILEY_COUNT_EXPLAIN' => $lang['IRC_smilies_count_explain'],
	'L_SMILIES_LINES' => $lang['IRC_smilies_lines'],
	'L_IRC_SOUNDS' => $lang['IRC_sound'],
	'L_IRC_SOUND_EXPLAIN' => $lang['IRC_sound_explain'],
    'L_SOUND_BEEP' => $lang['IRC_sound_beep'],
    'L_SOUND_BEEP_DELAY' => $lang['IRC_sound_beep_delay'],
	'L_SOUND_QUERY' => $lang['IRC_sound_query'],
	'L_SOUND_PROFILE' => $lang['IRC_sound_profile'],
	'L_SOUND_IM' => $lang['IRC_sound_im'],
	'L_SOUND_IGNORE' => $lang['IRC_sound_ignore'],
	'L_SOUND_UNIGNORE' => $lang['IRC_sound_unignore'],
	'L_SOUND_AWAY' => $lang['IRC_sound_away'],
	'L_SOUND_BACK' => $lang['IRC_sound_back'],
	'L_SOUND_CLEAR' => $lang['IRC_sound_clear'],
	'L_SOUND_WHOIS' => $lang['IRC_sound_whois'],
	'L_SOUND_HELP' => $lang['IRC_sound_help'],
	'L_IRC_SOUNDWORDS_EXPLAIN' => $lang['IRC_soundwords_explain'],
	'L_SOUND_SOUND1' => $lang['IRC_sound_sound1'], 
    'L_SOUND_SOUND1WORDS' => $lang['IRC_sound_sound1words'], 
    'L_SOUND_SOUND2' => $lang['IRC_sound_sound2'], 
    'L_SOUND_SOUND2WORDS' => $lang['IRC_sound_sound2words'],
    'L_BOT_CONTROL' => $lang['IRC_bot_control'],
    'L_BOT_OVERALL' => $lang['IRC_bot_overall'],
    'L_BOT_OVERALL_EXPLAIN' => $lang['IRC_bot_overall_explain'],
    'L_BOT_SWITCH1' => $lang['IRC_bot_switch1'],
    'L_BOT_SWITCH1_YES' => $lang['Yes'],
    'L_BOT_SWITCH1_NO' => $lang['No'],
    'L_BOT_OVERALL_TIMER' => $lang['IRC_bot_overall_timer'],
    'L_BOT_SWITCH2' => $lang['IRC_bot_switch2'],
    'L_BOT_SWITCH2_YES' => $lang['Yes'],
    'L_BOT_SWITCH2_NO' => $lang['No'],
    'L_IRC_ADVANCED' => $lang['IRC_advanced'],
    'L_CHANNEL2' => $lang['IRC_channel2'],
    'L_CHANNEL2_DISABLE' => $lang['Disabled'],
    'L_CHANNEL2_DEFINITION_EXPLAIN' => $lang['IRC_channel2_definition_explain'],
    'L_CHANNEL3' => $lang['IRC_channel3'],
    'L_CHANNEL3_DISABLE' => $lang['Disabled'],
    'L_CHANNEL3_DEFINITION_EXPLAIN' => $lang['IRC_channel3_definition_explain'],
    'L_MULTISERVER' => $lang['IRC_multiserver'],
    'L_MULTISERVER_EXPLAIN' => $lang['IRC_multiserver_explain'],
    'L_MULTISERVER_SERVER' => $lang['IRC_multiserver_server'], 
    'L_MULTISERVER_PORT' => $lang['IRC_multiserver_port'],
    'L_MULTISERVER_DELAY' => $lang['IRC_multiserver_delay'],
    'L_MULTISERVER_DELAY_EXPLAIN' => $lang['IRC_multiserver_delay_explain'],
    'L_USE_INFO' => $lang['IRC_use_info'],
    'L_IRC_LOOKS' => $lang['IRC_looks'],
	'L_STYLE_SELECTOR' => $lang['IRC_style_selector'],
	'L_STYLE_SELECTOR_DEFINITION' => $lang['IRC_style_selector_definition'],
	'L_STYLE_SELECTOR_DEFINITION_EXPLAIN' => $lang['IRC_style_selector_definition_explain'],
	'L_FONT_STYLE' => $lang['IRC_font_style'],
	'L_FONT_STYLE_DEFINITION' => $lang['IRC_font_style_definition'],
    'L_FONT_STYLE_DEFINITION_EXPLAIN' => $lang['IRC_font_style_definition_explain'],
    'L_STYLE_NICK' => $lang['IRC_style_nick'], 
    'L_STYLE_NICK_EXPLAIN' => $lang['IRC_style_nick_explain'],
    'L_SHOW_HIGHLIGHT' => $lang['IRC_show_highlight'],
    'L_HIGHLIGHTCOLOR' => $lang['IRC_highlightcolor'],
    'L_HIGHLIGHTCOLOR_DEFINITION_EXPLAIN' => $lang['IRC_highlightcolor_definition_explain'],
    'L_HIGHLIGHTWORDS' => $lang['IRC_highlightwords'],
    'L_HIGHLIGHTWORDS_DEFINITION_EXPLAIN' => $lang['IRC_highlightwords_definition_explain'],
    'L_BACKGROUND_WHICH' => $lang['IRC_background_which'],
    'L_BACKGROUND_WHICH_0' => $lang['None'],
    'L_BACKGROUND_WHICH_1' => $lang['IRC_background_which_1'],
    'L_BACKGROUND_WHICH_2' => $lang['IRC_background_which_2'],
    'L_BACKGROUND_CUSTOM' => $lang['IRC_background_which_custom'],
    'L_BACKGROUND_CUSTOM_EXPLAIN' => $lang['IRC_background_custom_explain'],

    'IRC_STATUS_OPEN' => $irc_status_open,
	'IRC_STATUS_CLOSED' => $irc_status_closed,
	'IRC_SERVER' => $irc_server,
	'IRC_PORT' => $irc_port,
	'IRC_CHANNEL' => $irc_channel,
	'IRC_LANGUAGE_LIST' => $language_list,
    'IRC_TEMPLATE_LIST' => $template_list,
    'IRC_POPUP_ONOFF_YES' => $irc_popup_onoff_yes,
	'IRC_POPUP_ONOFF_NO' => $irc_popup_onoff_no,
    'IRC_ALLOW_GUESTS_YES' => $irc_allow_guests_yes,
	'IRC_ALLOW_GUESTS_NO' => $irc_allow_guests_no,
	'IRC_GUESTNAME' => $irc_guestname,
	'IRC_AUTH_JOINLIST' => $irc_auth_joinlist,
    'IRC_SHOW_CONNECT_YES' => $irc_show_connect_yes,
	'IRC_SHOW_CONNECT_NO' => $irc_show_connect_no,
	'IRC_SHOW_CHANLIST_YES' => $irc_show_chanlist_yes,
	'IRC_SHOW_CHANLIST_NO' => $irc_show_chanlist_no,
	'IRC_SHOW_ABOUT_YES' => $irc_show_about_yes,
	'IRC_SHOW_ABOUT_NO' => $irc_show_about_no,
	'IRC_SHOW_HELP_YES' => $irc_show_help_yes,
	'IRC_SHOW_HELP_NO' => $irc_show_help_no,
	'IRC_SHOW_CLOSE_YES' => $irc_show_close_yes,
	'IRC_SHOW_CLOSE_NO' => $irc_show_close_no,
    'IRC_SHOW_STATUS_YES' => $irc_show_status_yes,
	'IRC_SHOW_STATUS_NO' => $irc_show_status_no,
	'IRC_SHOW_DOCK_YES' => $irc_show_dock_yes,
	'IRC_SHOW_DOCK_NO' => $irc_show_dock_no,
	'IRC_SHOW_NICKFIELD_YES' => $irc_show_nickfield_yes,
	'IRC_SHOW_NICKFIELD_NO' => $irc_show_nickfield_no,
    'IRC_TIME_STAMP_YES' => $irc_time_stamp_yes,
	'IRC_TIME_STAMP_NO' => $irc_time_stamp_no,
    'IRC_TOPICSCROLLER' => $irc_topicscroller,
    'IRC_QUIT' => $irc_quit,
    'IRC_SMILIES_ON' => $irc_smilies_on,
	'IRC_SMILIES_OFF' => $irc_smilies_off,
    'IRC_SMILEY_ENTER_YES' => $irc_smilies_enter_yes,
    'IRC_SMILEY_ENTER_NO' => $irc_smilies_enter_no,
	'IRC_SMILEY_COUNT' => $irc_smilies_count,
	'IRC_SMILIES_LINES' => $irc_smilies_lines,
	'IRC_SOUND_BEEP' => $irc_sound_beep,
	'IRC_ENTER_TIMER' => $irc_enter_timer,
	'IRC_SOUND_QUERY' => $irc_sound_query,
	'IRC_SOUND_PROFILE' => $irc_sound_profile,
	'IRC_SOUND_IM' => $irc_sound_im,
	'IRC_SOUND_IGNORE' => $irc_sound_ignore,
	'IRC_SOUND_UNIGNORE' => $irc_sound_unignore,
	'IRC_SOUND_AWAY' => $irc_sound_away,
	'IRC_SOUND_BACK' => $irc_sound_back,
	'IRC_SOUND_CLEAR' => $irc_sound_clear,
	'IRC_SOUND_WHOIS' => $irc_sound_whois,
	'IRC_SOUND_HELP' => $irc_sound_help,
	'IRC_SOUND1' => $irc_sound1, 
    'IRC_SOUNDWORDS1' => $irc_soundwords1,
    'IRC_SOUND2' => $irc_sound2,
    'IRC_SOUNDWORDS2' => $irc_soundwords2,
    'IRC_BOT_OVERALL_YES' => $irc_bot_overall_yes,
    'IRC_BOT_OVERALL_NO' => $irc_bot_overall_no,
    'IRC_BOT_SWITCH1_YES' => $irc_bot_switch1_yes,
	'IRC_BOT_SWITCH1_NO' => $irc_bot_switch1_no,
	'IRC_BOT_OVERALL_TIMER' => $irc_bot_overall_timer,
	'IRC_BOT_SWITCH2_YES' => $irc_bot_switch2_yes,
	'IRC_BOT_SWITCH2_NO' => $irc_bot_switch2_no,
	'IRC_CHANNEL2' => $irc_channel2,
	'IRC_CHANNEL3' => $irc_channel3,
	'IRC_CHANNEL2_YES' => $irc_channel2_yes, 
    'IRC_CHANNEL2_NO' => $irc_channel2_no,
    'IRC_CHANNEL3_YES' => $irc_channel3_yes, 
    'IRC_CHANNEL3_NO' => $irc_channel3_no,
    'IRC_MULTISERVER_YES' => $irc_multiserver_yes,
	'IRC_MULTISERVER_NO' => $irc_multiserver_no,
	'IRC_MULTISERVER_SERVER' => $irc_multiserver_server, 
    'IRC_MULTISERVER_PORT' => $irc_multiserver_port,
    'IRC_MULTISERVER_DELAY' => $irc_multiserver_delay,
    'IRC_USE_INFO_YES' => $irc_use_info_yes,
	'IRC_USE_INFO_NO' => $irc_use_info_no,
	'IRC_STYLE_SELECTOR_YES' => $irc_style_selector_yes,
	'IRC_STYLE_SELECTOR_NO' => $irc_style_selector_no,
	'IRC_STYLE_SELECTOR_DEFINITION' => $irc_style_selector_definition,
    'IRC_FONT_STYLE_YES' => $irc_font_style_yes,
	'IRC_FONT_STYLE_NO' => $irc_font_style_no,
	'IRC_FONT_STYLE_DEFINITION' => $irc_font_style_definition,
    'IRC_STYLE_NICK_LEFT' => $irc_style_nick_left, 
    'IRC_STYLE_NICK_RIGHT' => $irc_style_nick_right,
    'IRC_SHOW_HIGHLIGHT_YES' => $irc_show_highlight_yes,
	'IRC_SHOW_HIGHLIGHT_NO' => $irc_show_highlight_no,
	'IRC_HIGHLIGHTCOLOR_LIST' => $highlightcolor_list,
	'IRC_HIGHLIGHTWORDS' => $irc_highlightwords,
    'IRC_BACKGROUND_WHICH' => $irc_background_off,
    'IRC_BACKGROUND_WHICH_1' => $irc_background_default1,
    'IRC_BACKGROUND_WHICH_2' => $irc_background_default2,
    'IRC_BACKGROUND_WHICH_CUSTOM' => $irc_background_custom,
    'IRC_BACKGROUND_FILE' => $irc_background_file,
    'PJIRC_MOD_VERSION' => $new['irc_mod_version'],
    	
 	// All pages
	'CONFIG_SELECT' => config_select($mode, array(
		'config' => $lang['Configuration'],
		'extra' => $lang['IRC_advanced'],
		'access' => $lang['IRC_access'],
		'baf' => $lang['IRC_buttons'],
		'smiley' => $lang['Smiley'] . ' ' . $lang['Setting'],
		'sound' => $lang['IRC_sound'],
		'bot' => $lang['IRC_bot_control'],
		'style' => $lang['IRC_looks'])
	),
	
	'S_HIDDEN_FIELDS' => $hidden_fields)		
);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>