<?php
/***************************************************************************
 *                             AJAXed_config.php
 *                            -------------------
 *   begin                : Wednesday, Mar 8, 2006
 *   copyright            : (C) 2006 *=Matt=*
 *   email                : matt.gru@gmail.com
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

Header("content-type: application/x-javascript");

define('IN_PHPBB', true);
$phpbb_root_path = './../';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
//
// End session management
//

$template->set_filenames(array(
	'poll' => 'ajaxed_poll.tpl')
);
ob_start();
?>
/***************************************************************************
 *                              AJAXed_config.js
 *                            -------------------
 *   begin                : Wednesday, Mar 8, 2006
 *   copyright            : (C) 2006 *=Matt=*
 *   email                : matt.gru@gmail.com
 *   version              : 2.0.9
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/


//
// Configuration
//
var config 					= 	new Array();
config['use_charset'] 				= 	"<?php echo $lang['ENCODING']; ?>";
config['AJAXed_Online'] 			=	<?php echo $board_config['AJAXed_status'] ? "true" : "false"; ?>;
config['AJAXed_Poll_Menu'] 			=	<?php echo $board_config['AJAXed_poll_menu'] ? "true" : "false"; ?>;
config['AJAXed_Poll_Title'] 			=	<?php echo $board_config['AJAXed_poll_title'] ? "true" : "false"; ?>;
config['AJAXed_Poll_Options'] 			=	<?php echo $board_config['AJAXed_poll_options'] ? "true" : "false"; ?>;
config['AJAXed_Poll_Max'] 			=	"<?php echo $board_config['max_poll_options']; ?>";
config['AJAXed_Poll_count'] 			=	"0";
config['AJAXed_Poll_counted'] 			=	"0";
config['AJAXed_Poll_Adding'] 			=	false;
config['AJAXed_Poll_View'] 			=	null;
config['AJAXed_Poll_Viewed'] 			=	null;
config['AJAXed_Post_Title'] 			=	<?php echo $board_config['AJAXed_post_title'] ? "true" : "false"; ?>;
config['AJAXed_User_List'] 			=	<?php echo $board_config['AJAXed_user_list'] ? "true" : "false"; ?>;
config['AJAXed_User_Selected']			=	null;
config['AJAXed_User_Power']			=	null;
config['AJAXed_User_Typed']			=	null;
config['AJAXed_User_Max']			=	null;
config['AJAXed_User_mode']			=	false;
config['AJAXed_Username_Check'] 		=	<?php echo $board_config['AJAXed_username_check'] ? "true" : "false"; ?>;
config['AJAXed_Password_Check'] 		=	<?php echo $board_config['AJAXed_password_check'] ? "true" : "false"; ?>;
config['AJAXed_Is_Loading']			=	false;
config['AJAXed_Topic_ID']			=	null;
config['AJAXed_Post_ID']			=	null;
config['AJAXed_Class_1']			=	"<?php echo $theme['tr_color1'] ?>";
config['AJAXed_Class_2']			=	"<?php echo $theme['tr_color2'] ?>";
config['AJAXed_KeyUp']				=	null;


//
// Language
//
var lang 					= 	new Array();
lang['AJAXed_Error']				=	"<?php echo $lang['AJAXed_error']; ?>";
lang['AJAXed_Loading']				=	"&nbsp;<?php echo $lang['AJAXed_loading']; ?>";
lang['AJAXed_Check_User_1']			= 	"<?php echo $lang['AJAXed_check_username3'] ?> ";
lang['AJAXed_Check_True']			= 	"<?php echo $lang['AJAXed_check_true'] ?> ";
lang['AJAXed_Check_False']			= 	"<?php echo $lang['AJAXed_check_false'] ?> ";
lang['AJAXed_Delete_Post']			=	"<?php echo $lang['Confirm_delete']; ?>";
lang['AJAXed_Delete_Confirm']			=	"<?php echo $lang['AJAXed_delete_confirm']; ?>";
lang['AJAXed_Poll_Update']			= 	"<?php echo $lang['Update']; ?>";
lang['AJAXed_Poll_Confirm']			=	"<?php echo $lang['AJAXed_poll_confirm']; ?>";
lang['AJAXed_Poll_Delete']			=	"<?php echo $lang['Delete']; ?>";
lang['AJAXed_Poll_Many']			=	"<?php echo $lang['To_many_poll_options']; ?>";
lang['AJAXed_Poll_Option']			= 	"<?php echo $lang['Poll_option']; ?>";
lang['AJAXed_Poll_Select']			=	"<?php echo $lang['AJAXed_poll_select']; ?>";
lang['AJAXed_Timed_Out']			=	"<?php echo $lang['AJAXed_Timed_out']; ?>";
lang['AJAXed_Topic_Move']			= 	"<?php echo $lang['Topic_Moved']; ?> ";
lang['AJAXed_Topic_Title']			=	"<?php echo $board_config['sitename']; ?> :: <?php echo $lang['View_topic']; ?> - ";


//
// Image
//
var image 					= 	new Array();
image['AJAXed_Wrong']				=	"<?php echo $images['AJAXed_X']; ?>";
image['AJAXed_Correct']				=	"<?php echo $images['AJAXed_Check']; ?>";
image['spacer']					=	"<?php echo $phpbb_root_path; ?>images/spacer.gif";


//
// Page Setup
//
var page 					= 	new Array();
page['clientX']					= 	null;
page['clientY']					= 	null;
page['clientWidth']				= 	null;
page['clientHeight']				= 	null;
page['scrollLeft']				=	null;
page['scrollTop']				=	null;
page['scrollWidth']				=	null;
page['scrollHeight']				=	null;

//
// Template
//
function poll(id)
{
<?php $template->pparse('poll'); ?>
	return template;
}
<?php
$html = ob_get_contents();
ob_end_clean();
echo $html;
?>