<?php
/** 
*
* @package admin
* @version $Id: config_menu.php,v 1.0.0 28/03/2007 9:33 PM mj Exp $
* @copyright (c) 2007 MJ, Fully Modded phpBB
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

//
// Configuration select menu (all pages)
//
$nav_data = array(
	array(URL => 'index.'.$phpEx, Mode => '', Lang => $lang['Select_admin']),
	array(URL => 'admin_album_config.'.$phpEx, Mode => '', Lang => $lang['Album']),
	array(URL => 'admin_attachments.'.$phpEx, Mode => '?mode=manage', Lang => $lang['Attachment']),
	array(URL => 'admin_bank_config.'.$phpEx, Mode => '', Lang => $lang['Bank']),
	array(URL => 'admin_banner.'.$phpEx, Mode => '', Lang => $lang['Banner']),
	array(URL => 'admin_board.'.$phpEx, Mode => '', Lang => $lang['Board']),
	array(URL => 'admin_board_announcement.'.$phpEx, Mode => '', Lang => $lang['Site_Announcement']),
	array(URL => 'admin_bookies_config.'.$phpEx, Mode => '', Lang => $lang['Bookies']),
	array(URL => 'admin_charts.'.$phpEx, Mode => '', Lang => $lang['Chart']),
	array(URL => 'admin_chat_config.'.$phpEx, Mode => '', Lang => $lang['PJIRC_Chat_Settings']),
	array(URL => 'admin_xdata_fields.'.$phpEx, Mode => '', Lang => $lang['Users_fields']),
	array(URL => 'admin_sub_config.'.$phpEx, Mode => '', Lang => $lang['Donations_Subscriptions']),
	array(URL => 'admin_pa_settings.'.$phpEx, Mode => '', Lang => $lang['Download']),
	array(URL => 'admin_digests_config.'.$phpEx, Mode => '', Lang => $lang['Digests']),
	array(URL => 'admin_topic_force_read.'.$phpEx, Mode => '', Lang => $lang['FTR_config']),
	array(URL => 'admin_activity.'.$phpEx, Mode => '', Lang => $lang['Activity']),
	array(URL => 'admin_group_overview.'.$phpEx, Mode => '', Lang => $lang['Group_Overview']),
	array(URL => 'admin_guestbook.'.$phpEx, Mode => '', Lang => $lang['Guestbook']),
	array(URL => 'admin_helpdesk.'.$phpEx, Mode => '', Lang => $lang['Helpdesk']),
	array(URL => 'admin_jobs.'.$phpEx, Mode => '', Lang => $lang['Jobs']),
	array(URL => 'admin_linkdb.'.$phpEx, Mode => '', Lang => $lang['Link']),
	array(URL => 'admin_lottery_config.'.$phpEx, Mode => '', Lang => $lang['Lottery_title']),
	array(URL => 'admin_logs_config.'.$phpEx, Mode => '', Lang => $lang['Logger']),
	array(URL => 'admin_meeting.'.$phpEx, Mode => '?mode=config', Lang => $lang['Meeting_admin']),
	array(URL => 'admin_navlink.'.$phpEx, Mode => '', Lang => $lang['Navlink_title']),
	array(URL => 'admin_polls.'.$phpEx, Mode => '', Lang => $lang['Post_Poll'] . ' ' . $lang['Manage']),
	array(URL => 'admin_portal.'.$phpEx, Mode => '', Lang => $lang['Portal']),
	array(URL => 'admin_im_config.'.$phpEx, Mode => '?mode=config', Lang => $lang['Prillian']),
	array(URL => 'admin_rating.'.$phpEx, Mode => '', Lang => $lang['Rating_System']),
	array(URL => 'admin_shop_config.'.$phpEx, Mode => '', Lang => $lang['Shop']),
	array(URL => 'admin_stats_config.'.$phpEx, Mode => '?mode=config', Lang => $lang['Statistics']),
	array(URL => 'admin_styles_config.'.$phpEx, Mode => '', Lang => $lang['Style']),
	array(URL => 'admin_topic_kicker.'.$phpEx, Mode => '', Lang => $lang['Topic_kicker']),
);

$config_select = '<select name="page" onChange="change_page(\'window\',this,1)">';
for ( $row = 0; $row < sizeof($nav_data); $row++ )
{
	for ( $column = 0; $column < 1; $column++ )
	{
		// Only add $lang['Setting'] string if not Board Index link
		$page_title = ($nav_data[$row]['Lang'] == $lang['Select_admin']) ? $nav_data[$row]['Lang'] : $nav_data[$row]['Lang'] . ' ' . $lang['Setting'];

		$selected = (eregi($nav_data[$row]['URL'], $_SERVER['PHP_SELF'])) ? ' selected="selected"' : '';
		$config_select .= '<option value="' . append_sid($nav_data[$row]['URL'] . $nav_data[$row]['Mode']) . '" title="' . $page_title . '"' . $selected . ' />' . $page_title . '</option>';
	}
}
$config_select .= '</select>';


//
// Sub Menus (selected pages)
//
// Quick Access
$quick_access = '
	<li class="header">' . $lang['Quick_access'] . '</li>
		<li' . ((eregi('index.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('index.'.$phpEx) . '"><span>' . $lang['Admin_Index'] . '</span></a></li>
		<li' . ((eregi('admin_users.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_users.'.$phpEx) . '"><span>' . $lang['User_admin'] . '</span></a></li>
		<li' . ((eregi('admin_groups.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_groups.'.$phpEx) . '"><span>' . $lang['Group_administration'] . '</span></a></li>
		<li' . ((eregi('admin_forums.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_forums.'.$phpEx) . '"><span>' . $lang['Forum_admin'] . '</span></a></li>
		<li' . ((eregi('admin_page_permissions.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_page_permissions.'.$phpEx) . '"><span>' . $lang['Manage_portal'] . '</span></a></li>
		<li' . ((eregi('admin_logs.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_logs.'.$phpEx) . '"><span>' . $lang['Action_Logger']  . '</span></a></li>
		<li' . ((eregi('admin_board.'.$phpEx.'?mode=bots', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_board.'.$phpEx.'?mode=bots') . '"><span>' . $lang['Bots_Spiders']  . '</span></a></li>
		<li' . ((eregi('admin_notepad.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_notepad.'.$phpEx) . '"><span>' . $lang['Admin_notepad_title'] . '</span></a></li>
		<li' . ((eregi('admin_db_maintenance.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_db_maintenance.'.$phpEx) . '"><span>' . $lang['Maintenance_DB'] . '</span></a></li>
		<li' . ((eregi('report_bug.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="#" onClick=window.open("' . append_sid('report_bug.'.$phpEx) . '","br","width=400,height=600,resize")><span>' . $lang['Report_bug'] . '</span></a></li>
';

// Album
$album_menu = '
	<li class="header">' . $lang['Album'] . '</li>
		<li' . ((eregi('admin_album_config.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_album_config.'.$phpEx) . '"><span>' . $lang['Album'] . ' ' . $lang['Setting'] . '</span></a></li>
		<li' . ((eregi('admin_album_cat.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_album_cat.'.$phpEx) . '"><span>' . $lang['Cats'] . '</span></a></li>
		<li' . ((eregi('admin_album_auth.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_album_auth.'.$phpEx) . '"><span>' . $lang['Permissions'] . '</span></a></li>
		<li' . ((eregi('admin_album_personal.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_album_personal.'.$phpEx) . '"><span>' . $lang['Personal_Galleries'] . '</span></a></li>
		<li' . ((eregi('admin_album_unapproved.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_album_unapproved.'.$phpEx) . '"><span>' . $lang['album_unapproved_title'] . '</span></a></li>
		<li><a href="' . append_sid('admin_album_clearcache.'.$phpEx) . '"><span>' . $lang['Clear_Cache'] . '</span></a></li>
';

// Attachments
$attach_menu = '
	<li class="header">' . $lang['Attachment'] . ' ' . $lang['Manage'] . '</li>
		<li' . ((eregi('admin_attachments.'.$phpEx.'?mode=manage', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_attachments.'.$phpEx.'?mode=manage') . '"><span>' . $lang['Attachment'] . ' ' . $lang['Setting'] . '</span></a></li>
		<li' . ((eregi('admin_attach_cp.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_attach_cp.'.$phpEx) . '"><span>' . $lang['Control_panel_title'] . '</span></a></li>
		<li' . ((eregi('admin_extensions.'.$phpEx.'?mode=extensions', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_extensions.'.$phpEx.'?mode=extensions') . '"><span>' . $lang['Manage_extensions'] . '</span></a></li>
		<li' . ((eregi('admin_extensions.'.$phpEx.'?mode=groups', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_extensions.'.$phpEx.'?mode=groups') . '"><span>' . $lang['Manage_extension_groups'] . '</span></a></li>
		<li' . ((eregi('admin_extensions.'.$phpEx.'?mode=forbidden', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_extensions.'.$phpEx.'?mode=forbidden') . '"><span>' . $lang['Manage_forbidden_extensions'] . '</span></a></li>
		<li' . ((eregi('admin_attachmets.'.$phpEx.'?mode=cats', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_attachments.'.$phpEx.'?mode=cats') . '"><span>' . $lang['Manage_categories'] . '</span></a></li>
		<li' . ((eregi('admin_attachments.'.$phpEx.'?mode=quota', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_attachments.'.$phpEx.'?mode=quota') . '"><span>' . $lang['Manage_quotas'] . '</span></a></li>
		<li' . ((eregi('admin_attachments.'.$phpEx.'?mode=shadow', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_attachments.'.$phpEx.'?mode=shadow') . '"><span>' . $lang['Shadow_attachments'] . '</span></a></li>
		<li><a href="' . append_sid('admin_attachments.'.$phpEx.'?mode=sync') . '"><span>' . $lang['Sync_attachments'] . '</span></a></li>
';

// Avatars
$avatar_menu = '
	<li class="header">' . $lang['Manage_avatars'] . '</li>
		<li' . ((eregi('admin_board.'.$phpEx.'?mode=avatars', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_board.'.$phpEx.'?mode=avatars') . '"><span>' . $lang['Avatar'] . ' ' . $lang['Setting'] . '</span></a></li>
		<li' . ((eregi('admin_avatar_view.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_avatar_view.'.$phpEx) . '"><span>' . $lang['View_Avatars'] . '</span></a></li>
		<li' . ((eregi('admin_avatar_merge.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_avatar_merge.'.$phpEx) . '"><span>' . $lang['Unite_double_avatars'] . '</span></a></li>
		<li' . ((eregi('admin_avatar_unused.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_avatar_unused.'.$phpEx) . '"><span>' . $lang['Unused_Avatars'] . '</span></a></li>
';

// Banners
$banner_menu = '
	<li class="header">' . $lang['Banner'] . ' ' . $lang['Manage'] . '</li>
		<li' . ((eregi('admin_banner.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_banner.'.$phpEx) . '"><span>' . $lang['Banner_title'] . '</span></a></li>
		<li' . ((eregi('admin_banner.'.$phpEx.'?mode=add', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_banner.'.$phpEx.'?mode=add') . '"><span>' . $lang['Add_new_banner'] . '</span></a></li>
';

// Board Configuration
$config_menu = '
	<li class="header">' . $lang['Board'] . ' ' . $lang['Configuration'] . '</li>
		<li' . ((eregi('admin_board.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_board.'.$phpEx) . '"><span>' . $lang['Board'] . ' ' . $lang['Setting'] . '</span></a></li>
		<li' . ((eregi('admin_board.'.$phpEx.'?mode=avatars', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_board.'.$phpEx.'?mode=avatars') . '"><span>' . $lang['Avatar'] . ' ' . $lang['Setting'] . '</span></a></li>
		<li' . ((eregi('admin_board.'.$phpEx.'?mode=forum', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_board.'.$phpEx.'?mode=forum') . '"><span>' . $lang['Forum'] . ' ' . $lang['Setting'] . '</span></a></li>
		<li' . ((eregi('admin_board.'.$phpEx.'?mode=login', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_board.'.$phpEx.'?mode=login') . '"><span>' . $lang['Login'] . ' ' . $lang['Setting'] . '</span></a></li>
		<li' . ((eregi('admin_board.'.$phpEx.'?mode=post', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_board.'.$phpEx.'?mode=post') . '"><span>' . $lang['Post'] . ' ' . $lang['Setting'] . '</span></a></li>
		<li' . ((eregi('admin_styles_config.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_styles_config.'.$phpEx) . '"><span>' . $lang['Style'] . ' ' . $lang['Setting'] . '</span></a></li>
		<li' . ((eregi('admin_board.'.$phpEx.'?mode=register', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_board.'.$phpEx.'?mode=register') . '"><span>' . $lang['Registration_settings'] . ' ' . $lang['Setting'] . '</span></a></li>
';

// Lite board installed ?
if ( file_exists($phpbb_root_path . 'lite') )
{
	$config_menu .= '<li' . ((eregi('admin_lite_board.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_lite_board.'.$phpEx) . '"><span>' . $lang['Lite_Board_Settings'] . ' ' . $lang['Setting'] . '</span></a></li>';
}

// Bookies
$bookie_menu = '
	<li class="header">' . $lang['Bookies'] . '</li>
		<li' . ((eregi('admin_bookies_config.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_bookies_config.'.$phpEx) . '"><span>' . $lang['Bookies'] . ' ' .$lang['Setting'] . '</span></a></li>
		<li' . ((eregi('admin_bookies_plus_meetings.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_bookies_plus_meetings.'.$phpEx) . '"><span>' . $lang['Bookies_Meetings'] . '</span></a></li>
		<li' . ((eregi('admin_bookies_plus_categories.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_bookies_plus_categories.'.$phpEx) . '"><span>' . $lang['Manage_categorys'] . '</span></a></li>
		<li' . ((eregi('admin_bookies_plus_selections.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_bookies_plus_selections.'.$phpEx) . '"><span>' . $lang['Bookies_Selections'] . '</span></a></li>
		<li' . ((eregi('admin_bookies_commission.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_bookies_commission.'.$phpEx) . '"><span>' . $lang['Bookies_Commission'] . '</span></a></li>
		<li' . ((eregi('admin_bookies.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_bookies.'.$phpEx) . '"><span>' . $lang['Bookies_Process'] . '</span></a></li>
		<li' . ((eregi('admin_bookies_setbet.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_bookies_setbet.'.$phpEx) . '"><span>' . $lang['Bookies_Set'] . '</span></a></li>
		<li' . ((eregi('admin_bookies_edit_past.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_bookies_edit_past.'.$phpEx) . '"><span>' . $lang['Bookies_Edit'] . '</span></a></li>
		<li' . ((eregi('admin_bookies_purge.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_bookies_purge.'.$phpEx) . '"><span>' . $lang['Bookies_Purge'] . '</span></a></li>
		<li' . ((eregi('admin_bookies_sync.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_bookies_sync.'.$phpEx) . '"><span>' . $lang['Sync_attachments'] . '</span></a></li>
';

// Bots
$bots_menu = '
	<li class="header">' . $lang['Bots_Spiders'] . '</li>
		<li' . ((eregi('admin_bots.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_bots.'.$phpEx) . '"><span>' . $lang['Bots_manage'] . '</span></a></li>
		<li' . ((eregi('admin_logs_bots.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_logs_bots.'.$phpEx) . '"><span>' . $lang['Bots_logger'] . '</span></a></li>
';

// Custom Profile fields
$custom_profile_menu = '
	<li class="header">' . $lang['Users_fields'] . '</li>
		<li' . ((eregi('admin_xdata_fields.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_xdata_fields.'.$phpEx) . '"><span>' . $lang['manage_xdata_field'] . '</span></a></li>
		<li' . ((eregi('admin_xdata_fields.'.$phpEx.'?mode=add', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_xdata_fields.'.$phpEx.'?mode=add') . '"><span>' . $lang['add_xdata_field'] . '</span></a></li>
		<li' . ((eregi('admin_xdata_auth.'.$phpEx.'?type=user', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_xdata_auth.'.$phpEx.'?type=user') . '"><span>' . $lang['Auth_Control_User'] . '</span></a></li>
		<li' . ((eregi('admin_xdata_auth.'.$phpEx.'?type=group', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_xdata_auth.'.$phpEx.'?type=group') . '"><span>' . $lang['Auth_Control_Group'] . '</span></a></li>
';

// Database
$db_menu = '
	<li class="header">' . $lang['Database'] . '</li>
		<li' . ((eregi('admin_db_utilities.'.$phpEx.'?perform=backup', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_db_utilities.'.$phpEx.'?perform=backup') . '"><span>' . $lang['Database_Backup'] . '</span></a></li>
		<li' . ((eregi('admin_db_utiliites.'.$phpEx.'?perform=restore', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_db_utilities.'.$phpEx.'?perform=restore') . '"><span>' . $lang['Database_Restore'] . '</span></a></li>
		<li' . ((eregi('admin_db_autobackup.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_db_autobackup.'.$phpEx) . '"><span>' . $lang['Automatic_Backup'] . '</span></a></li>
';

// Downloads
$download_menu = '
	<li class="header">' . $lang['Download'] . ' ' . $lang['Manage'] . '</li>
		<li' . ((eregi('admin_pa_settings.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_pa_settings.'.$phpEx) . '"><span>' . $lang['Download'] . ' ' . $lang['Setting'] . '</span></a></li>
		<li' . ((eregi('admin_pa_file.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_pa_file.'.$phpEx) . '"><span>' . $lang['File_manage_title'] . '</span></a></li>
		<li' . ((eregi('admin_pa_category.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_pa_category.'.$phpEx) . '"><span>' . $lang['Manage_categorys'] . '</span></a></li>
		<li' . ((eregi('admin_pa_custom.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_pa_custom.'.$phpEx) . '"><span>' . $lang['Link_man_field'] . '</span></a></li>
		<li' . ((eregi('admin_pa_license.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_pa_license.'.$phpEx) . '"><span>' . $lang['License_title'] . '</span></a></li>
		<li' . ((eregi('admin_pa_catauth.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_pa_catauth.'.$phpEx) . '"><span>' . $lang['Permissions'] . '</span></a></li>
		<li' . ((eregi('admin_pa_fchecker.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_pa_fchecker.'.$phpEx) . '"><span>' . $lang['Fchecker'] . '</span></a></li>
';

// E-mail
$email_menu = '
	<li class="header">' . $lang['Email'] . ' ' . $lang['Manage'] . '</li>
		<li' . ((eregi('admin_email_mass.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_email_mass.'.$phpEx) . '"><span>' . $lang['Mass_Email'] . '</span></a></li>
		<li' . ((eregi('admin_email_list.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_email_list.'.$phpEx) . '"><span>' . $lang['Email_list'] . '</span></a></li>
		<li' . ((eregi('admin_email_search.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_email_search.'.$phpEx) . '"><span>' . $lang['Email_search'] . '</span></a></li>
';

// E-mail Digests
$digests_menu = '
	<li class="header">' . $lang['Digests'] . '</li>
		<li' . ((eregi('admin_digests_config.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_digests_config.'.$phpEx) . '"><span>' . $lang['Digests'] . ' ' . $lang['Setting'] . '</span></a></li>
		<li' . ((eregi('admin_digest_management.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_digests_management.'.$phpEx) . '"><span>' . $lang['Digest_title'] . '</span></a></li>
		<li' . ((eregi('admin_digests_add_users.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_digests_add_users.'.$phpEx) . '"><span>' . $lang['Digest_user_admin'] . '</span></a></li>
		<li' . ((eregi('admin_digests_add_groups.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_digests_add_groups.'.$phpEx) . '"><span>' . $lang['Digest_group_title'] . '</span></a></li>
		<li' . ((eregi('admin_digests_confirm.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_digests_confirm.'.$phpEx) . '"><span>' . $lang['Digest_confirm'] . '</span></a></li>
		<li' . ((eregi('admin_digests_mail.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_digests_mail.'.$phpEx) . '"><span>' . $lang['Digest_mail_title'] . '</span></a></li>
		<li' . ((eregi('admin_digests_log.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_digests_log.'.$phpEx) . '"><span>' . $lang['Digest_log_title'] . '</span></a></li>
		<li' . ((eregi('admin_digests_verify.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_digests_verify.'.$phpEx) . '"><span>' . $lang['Sync_attachments'] . '</span></a></li>
';

// Forums
$forum_menu = '
	<li class="header">' . $lang['Forum'] . ' ' . $lang['Manage'] . '</li>
		<li' . ((eregi('admin_board.'.$phpEx.'?mode=forum', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_board.'.$phpEx.'?mode=forum') . '"><span>' . $lang['Forum'] . ' ' . $lang['Setting'] . '</span></a></li>
		<li' . ((eregi('admin_board.'.$phpEx.'?mode=forum_modules', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_board.'.$phpEx.'?mode=forum_modules') . '"><span>' . $lang['Forum_module_title'] . '</span></a></li>
		<li' . ((eregi('admin_forum_tour.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_forum_tour.'.$phpEx) . '"><span>' . $lang['Forum_tour'] . '</span></a></li>
		<li' . ((eregi('admin_forum_prune.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_forum_prune.'.$phpEx) . '"><span>' . $lang['Forum_Prune'] . '</span></a></li>
		<li' . ((eregi('admin_forum_prune_overview.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_forum_prune_overview.'.$phpEx) . '"><span>' . $lang['Prune_Overview'] . ' ' . $lang['Setting'] . '</span></a></li>
';

// Games
$game_menu = '
	<li class="header">' . $lang['Activity'] . ' ' . $lang['Manage'] . '</li>
		<li' . ((eregi('admin_activity.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_activity.'.$phpEx) . '"><span>' . $lang['Activity'] . ' ' . $lang['Setting'] . '</span></a></li>
		<li' . ((eregi('admin_ina_char.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_ina_char.'.$phpEx) . '"><span>' . $lang['amp_char_title'] . '</span></a></li>
		<li' . ((eregi('admin_ina_category.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_ina_category.'.$phpEx) . '"><span>' . $lang['Manage_categorys'] . '</span></a></li>
		<li' . ((eregi('admin_activity.'.$phpEx.'?mode=edit_games', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_activity.'.$phpEx.'?mode=edit_games') . '"><span>' . $lang['admin_edit_title_r'] . '</span></a></li>
 		<li' . ((eregi('admin_ina_scores_edit.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_ina_scores_edit.'.$phpEx) . '"><span>' . $lang['admin_scores_1'] . '</span></a></li>
		<li' . ((eregi('admin_activity.'.$phpEx.'?mode=add_game', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_activity.'.$phpEx.'?mode=add_game') . '"><span>' . $lang['Add_game'] . '</span></a></li>
		<li' . ((eregi('admin_ina_bulk_add.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_ina_bulk_add.'.$phpEx) . '"><span>' . $lang['bulk_add_title'] . '</span></a></li>
		<li' . ((eregi('admin_ina_mass.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_ina_mass.'.$phpEx) . '"><span>' . $lang['mass_change_title1'] . '</span></a></li>
		<li' . ((eregi('admin_ina_disable.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_ina_disable.'.$phpEx) . '"><span>' . $lang['a_disable_1'] . '</span></a></li>
		<li' . ((eregi('admin_ina_xtras.'.$phpEx.'?mode=check_games', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_ina_xtras.'.$phpEx.'?mode=check_games') . '"><span>' . $lang['acp_check_games'] . '</span></a></li>
		<li' . ((eregi('admin_ina_xtras.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_ina_xtras.'.$phpEx) . '"><span>' . $lang['Sync_attachments'] . '</span></a></li>
		<li' . ((eregi('admin_ina_ban.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_ina_ban.'.$phpEx) . '"><span>' . $lang['a_ban_1'] . '</span></a></li>
';

// Groups
$group_menu = '
	<li class="header">' . $lang['Groupcp'] . ' ' . $lang['Manage'] . '</li>
		<li' . ((eregi('admin_group_overview.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_group_overview.'.$phpEx) . '"><span>' . $lang['Group_Overview'] . '</span></a></li>
';

// Inline Ads
$inline_ad_menu = '
	<li class="header">' . $lang['Inline_ad_config'] . ' ' . $lang['Manage'] . '</li>
		<li' . ((eregi('admin_board.'.$phpEx.'?mode=inline_ads', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_board.'.$phpEx.'?mode=inline_ads') . '"><span>' . $lang['Inline_ad_config'] . ' ' . $lang['Setting'] . '</span></a></li>
		<li' . ((eregi('admin_inline_ad_code.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_inline_ad_code.'.$phpEx) . '"><span>' . $lang['Inline_ad_manage'] . '</span></a></li>
		<li' . ((eregi('admin_inline_ad_code.'.$phpEx.'?action=add', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_inline_ad_code.'.$phpEx.'?action=add') . '"><span>' . $lang['ad_add'] . '</span></a></li>
';

// Knowledge Base
$kb_menu = '
	<li class="header">' . $lang['Kb'] . '</li>
		<li' . ((eregi('admin_board.'.$phpEx.'?mode=kb', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_board.'.$phpEx.'?mode=kb') . '"><span>' . $lang['Kb'] . ' ' . $lang['Setting']  . '</span></a></li>
		<li' . ((eregi('admin_kb_art.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_kb_art.'.$phpEx) . '"><span>' . $lang['Art_man'] . '</span></a></li>
		<li' . ((eregi('admin_kb_types.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_kb_types.'.$phpEx) . '"><span>' . $lang['Types_man'] . '</span></a></li>
		<li' . ((eregi('admin_kb_cat.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_kb_cat.'.$phpEx) . '"><span>' . $lang['Manage_categorys'] . '</span></a></li>
		<li' . ((eregi('admin_kb_cat.'.$phpEx.'mode=create', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_kb_cat.'.$phpEx.'?mode=create') . '"><span>' . $lang['Add_cat'] . '</span></a></li>
		<li' . ((eregi('admin_kb_resync.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_kb_resync.'.$phpEx) . '"><span>' . $lang['Sync_attachments'] . '</span></a></li>
';

// Langauge
$lang_menu = '
	<li class="header">' . $lang['Language'] . ' ' . $lang['Manage'] . '</li>
		<li' . ((eregi('admin_lang_stats.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_lang_stats.'.$phpEx) . '"><span>' . $lang['User_languages'] . '</span></a></li>
		<li' . ((eregi('admin_faq_editor.'.$phpEx.'?fie=faq', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_faq_editor.'.$phpEx.'?file=faq') . '"><span>' . $lang['Faq'] . '</span></a></li>
		<li' . ((eregi('admin_faq_editor.'.$phpEx.'?file=bbcode', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_faq_editor.'.$phpEx.'?file=bbcode') . '"><span>' . $lang['BBCode_guide'] . '</span></a></li>
		<li' . ((eregi('admin_faq_editor.'.$phpEx.'?file=faq_moderator', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_faq_editor.'.$phpEx.'?file=faq_moderator') . '"><span>' . $lang['Moderator'] . ' ' . $lang['Faq'] . '</span></a></li>
';

// Lexicon
$lexicon_menu = '
	<li class="header">' . $lang['Lexicon'] . ' ' . $lang['Manage'] . '</li>
		<li' . ((eregi('admin_board.'.$phpEx.'?mode=lexicon', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_board.'.$phpEx.'?mode=lexicon') . '"><span>' . $lang['Lexicon'] . ' ' . $lang['Setting']  . '</span></a></li>
		<li' . ((eregi('admin_lexicon_cat.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_lexicon_cat.'.$phpEx) . '"><span>' . $lang['Manage_categorys'] . '</span></a></li>
		<li' . ((eregi('admin_lexicon_edit.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_lexicon_edit.'.$phpEx) . '"><span>' . $lang['Keyword_administration'] . '</span></a></li>
		<li' . ((eregi('admin_lexicon_edit.'.$phpEx.'?mode=new', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_lexicon_edit.'.$phpEx.'?mode=new') . '"><span>' . $lang['Keyword_administration_new'] . '</span></a></li>
';

// Links
$link_menu = '
	<li class="header">' . $lang['Link'] . ' ' . $lang['Manage'] . '</li>
		<li' . ((eregi('admin_linkdb.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_linkdb.'.$phpEx) . '"><span>' . $lang['Link'] . ' ' . $lang['Setting']  . '</span></a></li>
		<li' . ((eregi('admin_linkdb.'.$phpEx.'?action=link_manage', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_linkdb.'.$phpEx.'?action=link_manage') . '"><span>' . $lang['Link_manage'] . '</span></a></li>
		<li' . ((eregi('admin_linkdb.'.$phpEx.'?action=cat_manage', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_linkdb.'.$phpEx.'?action=cat_manage') . '"><span>' . $lang['Manage_categorys'] . '</span></a></li>
		<li' . ((eregi('admin_linkdb.'.$phpEx.'?action=link_custom', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_linkdb.'.$phpEx.'?action=link_custom') . '"><span>' . $lang['Link_man_field'] . '</span></a></li>
';

// Logs
$log_menu = '
		<li class="header">' . $lang['Logger'] . ' ' . $lang['Manage'] . '</li>
		<li' . ((eregi('admin_logs_config.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_logs_config.'.$phpEx) . '"><span>' . $lang['Logger'] . ' ' . $lang['Setting']  . '</span></a></li>
		<li' . ((eregi('admin_logs_ip.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_logs_ip.'.$phpEx) . '"><span>' . $lang['IP_Logger'] . '</span></a></li>
		<li' . ((eregi('admin_logs_points.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_logs_points.'.$phpEx) . '"><span>' . $lang['Points_sys_logger']  . '</span></a></li>
		<li' . ((eregi('admin_logs_posting_ip.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_logs_posting_ip.'.$phpEx) . '"><span>' . $lang['IP_Search'] . '</span></a></li>
		<li' . ((eregi('admin_logs_referers.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_logs_referers.'.$phpEx) . '"><span>' . $lang['HTTP_Referers_Title']  . '</span></a></li>
';

// Medal System
$medals_menu = '
	<li class="header">' . $lang['Medals'] . ' ' . $lang['Manage'] . '</li>
		<li' . ((eregi('admin_board.'.$phpEx.'mode=medals', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_board.'.$phpEx.'?mode=medals') . '"><span>' . $lang['Medals'] . ' ' . $lang['Setting'] . '</span></a></li>
		<li' . ((eregi('admin_medal.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_medal.'.$phpEx) . '"><span>' . $lang['Medal_admin'] . '</span></a></li>
';

// Meetings
$meeting_menu = '
	<li class="header">' . $lang['Meeting_admin'] . ' ' . $lang['Manage'] . '</li>
		<li' . ((eregi('admin_meeting.'.$phpEx.'?mode=config', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_meeting.'.$phpEx.'?mode=config') . '"><span>' . $lang['Meeting_admin'] . ' ' . $lang['Setting']  . '</span></a></li>
		<li' . ((eregi('admin_meeting.'.$phpEx.'?mode=manage', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_meeting.'.$phpEx.'?mode=manage') . '"><span>' . $lang['Meeting_manage']  . '</span></a></li>
		<li' . ((eregi('admin_meeting.'.$phpEx.'?mode=add_new', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_meeting.'.$phpEx.'?mode=add_new') . '"><span>' . $lang['Meeting_add_new']  . '</span></a></li>
	
';

// Permissions
$perms_menu = '
	<li class="header">' . $lang['Permissions'] . '</li>
		<li' . ((eregi('admin_ug_auth.'.$phpEx.'?mode=user', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_ug_auth.'.$phpEx.'?mode=user') . '"><span>' . $lang['Auth_Control_User'] . '</span></a></li>
		<li' . ((eregi('admin_ug_auth.'.$phpEx.'?mode=group', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_ug_auth.'.$phpEx.'?mode=group') . '"><span>' . $lang['Auth_Control_Group'] . '</span></a></li>
		<li' . ((eregi('admin_forumauth.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_forumauth.'.$phpEx) . '"><span>' . $lang['Auth_Control_Forum'] . '</span></a></li>
		<li' . ((eregi('admin_forumauth_adv.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_forumauth_adv.'.$phpEx) . '"><span>' . $lang['Auth_Control_Forum_Adv'] . '</span></a></li>
		<li' . ((eregi('admin_forumauth_overall.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_forumauth_overall.'.$phpEx) . '"><span>' . $lang['Permissions_overall'] . '</span></a></li>
		<li' . ((eregi('admin_super_ug_auth.'.$phpEx.'?mode=user', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_super_ug_auth.'.$phpEx.'?mode=user') . '"><span>' . $lang['Auth_SuperMod_add'] . '</span></a></li>
		<li' . ((eregi('admin_superundo_ug_auth.'.$phpEx.'?mode=user', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_superundo_ug_auth.'.$phpEx.'?mode=user') . '"><span>' . $lang['Auth_SuperMod_remove'] . '</span></a></li>
		<li' . ((eregi('admin_user_reset_level.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_user_reset_level.'.$phpEx) . '"><span>' . $lang['Reset_user_level'] . '</span></a></li>
		<li' . ((eregi('admin_board.'.$phpEx.'mode=modcp', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_board.'.$phpEx.'?mode=modcp') . '"><span>' . $lang['Mod_CP'] . ' ' . $lang['Setting'] . '</span></a></li>
';

// Polls
$poll_menu = '
	<li class="header">' . $lang['Post_Poll'] . ' ' . $lang['Manage'] . '</li>
		<li' . ((eregi('admin_polls.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_polls.'.$phpEx) . '"><span>' . $lang['Vote_manager'] . '</span></a></li>
		<li' . ((eregi('admin_polls_votes.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_polls_votes.'.$phpEx) . '"><span>' . $lang['Who_voted'] . '</span></a></li>
';

// Portal
$portal_menu = '
	<li class="header">' . $lang['Portal'] . ' ' . $lang['Manage'] . '</li>
		<li' . ((eregi('admin_portal.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_portal.'.$phpEx) . '"><span>' . $lang['Manage_portal'] . '</span></a></li>
		<li' . ((eregi('admin_portal.'.$phpEx.'?mode=add', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_portal.'.$phpEx.'?mode=add') . '"><span>' . $lang['Add_new_Portal'] . '</span></a></li>
		<li' . ((eregi('admin_portal_newsfeed.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_portal_newsfeed.'.$phpEx) . '"><span>' . $lang['Newsfeed_config_title'] . '</span></a></li>

';

// Posts
$post_menu = '
	<li class="header">' . $lang['Post'] . ' ' . $lang['Manage'] . '</li>
		<li' . ((eregi('admin_board.'.$phpEx.'?mode=post', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_board.'.$phpEx.'?mode=post') . '"><span>' . $lang['Post'] . ' ' . $lang['Setting'] . '</span></a></li>
		<li' . ((eregi('admin_user_ranks.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_user_ranks.'.$phpEx) . '"><span>' . $lang['Ranks_title'] . '</span></a></li>
		<li' . ((eregi('admin_post_assoc.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_post_assoc.'.$phpEx) . '"><span>' . $lang['Post_Associator'] . '</span></a></li>
		<li' . ((eregi('admin_post_replace.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_post_replace.'.$phpEx) . '"><span>' . $lang['Post_Replacer'] . '</span></a></li>
		<li' . ((eregi('admin_user_prune_posts.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_user_prune_posts.'.$phpEx) . '"><span>' . $lang['Prune_user_posts'] . '</span></a></li>
		<li' . ((eregi('admin_board.'.$phpEx.'?mode=smilies', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_board.'.$phpEx.'?mode=smilies') . '"><span>' . $lang['Smiley'] . ' ' . $lang['Setting'] . '</span></a></li>
		<li' . ((eregi('admin_words.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_words.'.$phpEx) . '"><span>' . $lang['Word_Censor'] . '</span></a></li>
		<li' . ((eregi('admin_logs_posting_ip.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_logs_posting_ip.'.$phpEx) . '"><span>' . $lang['IP_Search'] . '</span></a></li>
		<li' . ((eregi('admin_spellcheck.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_spellcheck.'.$phpEx) . '"><span>' . $lang['Spellcheck'] = $lang['Spellcheck'] . '</span></a></li>
';

// Prillian
$prill_menu = '
	<li class="header">' . $lang['Prillian'] . ' ' . $lang['Manage'] . '</li>
		<li' . ((eregi('admin_im_users.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_im_users.'.$phpEx) . '"><span>' . $lang['Auth_Control_User'] . '</span></a></li>
		<li' . ((eregi('admin_im_log.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_im_log.'.$phpEx) . '"><span>' . $lang['Prillian_Log'] . '</span></a></li>
		<li' . ((eregi('admin_im_network.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_im_network.'.$phpEx) . '"><span>' . $lang['Network_title'] . '</span></a></li>
';

// Private Messages
$pm_menu = '
	<li class="header">' . $lang['Private_Message'] . ' ' . $lang['Manage'] . '</li>
		<li' . ((eregi('admin_priv_msgs.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_priv_msgs.'.$phpEx) . '"><span>' . $lang['Manage_Private_Messages'] . '</span></a></li>
		<li' . ((eregi('admin_priv_msgs_remover.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_priv_msgs_remover.'.$phpEx) . '"><span>' . $lang['PMR_TITLE'] . '</span></a></li>
';


// Server Config
$server_menu = '
	<li class="header">' . $lang['Web_server'] . ' ' . $lang['Configuration'] . '</li>
		<li' . ((eregi('admin_board.'.$phpEx.'?mode=cookie', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_board.'.$phpEx.'?mode=cookie') . '"><span>' . $lang['Cookie_settings'] . ' ' . $lang['Setting'] . '</span></a></li>
		<li' . ((eregi('admin_board.'.$phpEx.'?mode=server', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_board.'.$phpEx.'?mode=server') . '"><span>' . $lang['Web_server'] . ' ' . $lang['Setting'] . '</span></a></li>
		<li' . ((eregi('admin_board.'.$phpEx.'?mode=load', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_board.'.$phpEx.'?mode=load') . '"><span>' . $lang['Board_load'] . ' ' . $lang['Setting'] . '</span></a></li>
		<li' . ((eregi('admin_board.'.$phpEx.'?mode=search', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_board.'.$phpEx.'?mode=search') . '"><span>' . $lang['Search'] . ' ' . $lang['Setting'] . '</span></a></li>
';

// Smilies
$smiley_menu = '
	<li class="header">' . $lang['Smiley'] . ' ' . $lang['Manage'] . '</li>
		<li' . ((eregi('admin_smilies.'.$phpEx.'?cat_view', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_smilies.'.$phpEx.'?cat_view') . '"><span>' . $lang['Manage_smilies']  . '</span></a></li>
		<li' . ((eregi('admin_smilies.'.$phpEx.'?smiley_add', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_smilies.'.$phpEx.'?smiley_add') . '"><span>' . $lang['Add_new']  . '</span></a></li>
		<li' . ((eregi('admin_smilies.'.$phpEx.'?upload', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_smilies.'.$phpEx.'?upload') . '"><span>' . $lang['Upload']  . '</span></a></li>
		<li' . ((eregi('admin_smilies.'.$phpEx.'?unused_smilies', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_smilies.'.$phpEx.'?unused_smilies') . '"><span>' . $lang['Smilie_unused_title']  . '</span></a></li>
		<li' . ((eregi('admin_smilies.'.$phpEx.'?cat_add', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_smilies.'.$phpEx.'?cat_add') . '"><span>' . $lang['Add_cat']  . '</span></a></li>
		<li' . ((eregi('admin_smilies.'.$phpEx.'?cat_edit', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_smilies.'.$phpEx.'?cat_edit') . '"><span>' . $lang['Edit_Category']  . '</span></a></li>
		<li' . ((eregi('admin_smilies.'.$phpEx.'?view_perms', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_smilies.'.$phpEx.'?view_perms') . '"><span>' . $lang['Permissions']  . '</span></a></li>
		<li' . ((eregi('admin_smilies.'.$phpEx.'?import_pack', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_smilies.'.$phpEx.'?import_pack') . '"><span>' . $lang['Smilie_import_pak_title']  . '</span></a></li>
		<li' . ((eregi('admin_smilies.'.$phpEx.'?export_pack', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_smilies.'.$phpEx.'?export_pack') . '"><span>' . $lang['Smilie_export_pak_title']  . '</span></a></li>

';

// Statistics
$stats_menu = '
	<li class="header">' . $lang['Statistics'] . ' ' . $lang['Manage'] . '</li>
		<li' . ((eregi('admin_stats_config.'.$phpEx.'?mode=config', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_stats_config.'.$phpEx.'?mode=config') . '"><span>' . $lang['Statistics'] . ' ' . $lang['Setting']  . '</span></a></li>
		<li' . ((eregi('admin_statistics.'.$phpEx.'?mode=mod_manage', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_statistics.'.$phpEx.'?mode=mod_manage') . '"><span>' . $lang['Manage_modules']  . '</span></a></li>
		<li' . ((eregi('admin_statistics.'.$phpEx.'?mode=mod_install', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_statistics.'.$phpEx.'?mode=mod_install') . '"><span>' . $lang['Install_module']  . '</span></a></li>
		<li' . ((eregi('admin_stats_edit_module.'.$phpEx.'?mode=select_module', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_stats_edit_module.'.$phpEx.'?mode=select_module') . '"><span>' . $lang['Edit_module']  . '</span></a></li>
	
';

// Styles
$style_menu = '
	<li class="header">' . $lang['Style'] . ' ' . $lang['Manage'] . '</li>
		<li' . ((eregi('admin_styles_config.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_styles_config.'.$phpEx) . '"><span>' . $lang['Style'] . ' ' . $lang['Setting'] . '</span></a></li>
		<li' . ((eregi('admin_styles_users.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_styles_users.'.$phpEx) . '"><span>' . $lang['Styles_admin'] . '</span></a></li>
		<li' . ((eregi('admin_styles.'.$phpEx.'?mode=addnew', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_styles.'.$phpEx.'?mode=addnew') . '"><span>' . $lang['Add_style'] . '</span></a></li>
		<li' . ((eregi('admin_styles.'.$phpEx.'?mode=create', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_styles.'.$phpEx.'?mode=create') . '"><span>' . $lang['Create_theme'] . '</span></a></li>
		<li' . ((eregi('admin_styles.'.$phpEx.'?mode=export', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_styles.'.$phpEx.'?mode=export') . '"><span>' . $lang['Export_themes'] . '</span></a></li>
';

// Subscriptions / Donations
$subscription_menu = '
	<li class="header">' . $lang['Donations_Subscriptions'] . '</li>
		<li' . ((eregi('admin_sub_config.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_sub_config.'.$phpEx) . '"><span>' . $lang['Donations_Subscriptions'] . ' ' . $lang['Setting'] . '</span></a></li>
		<li' . ((eregi('admin_sub_users.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_sub_users.'.$phpEx) . '"><span>' . $lang['L_IPN_user_sub_title'] . '</span></a></li>
		<li' . ((eregi('admin_donors.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_donors.'.$phpEx) . '"><span>' . $lang['L_LW_DONATES_ADD'] . '</span></a></li>
		<li' . ((eregi('admin_sub_records.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_sub_records.'.$phpEx) . '"><span>' . $lang['L_IPN_log_title'] . '</span></a></li>
';

// Templates
$tpl_menu = '
	<li class="header">' . $lang['Template'] . ' ' . $lang['Manage'] . '</li>
		<li' . ((eregi('admin_styles_template_edit.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_styles_template_edit.'.$phpEx) . '"><span>' . $lang['Template_Edit_Title'] . '</span></a></li>
		<li' . ((eregi('admin_styles_advance_html.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_styles_advance_html.'.$phpEx) . '"><span>' . $lang['Advanced_HTML'] . '</span></a></li>
		<li' . ((eregi('admin_styles_haf.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_styles_haf.'.$phpEx) . '"><span>' . $lang['Custom_Footer_and_Header_settings'] . '</span></a></li>
		<li' . ((eregi('admin_styles_clearcache.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_styles_clearcache.'.$phpEx) . '"><span>' . $lang['Clear_Cache'] . '</span></a></li>
';

// Topics
$topic_menu = '
	<li class="header">' . $lang['Topic'] . ' ' . $lang['Manage'] . '</li>
		<li' . ((eregi('admin_topic_move.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_topic_move.'.$phpEx) . '"><span>' . $lang['Move_topics'] . '</span></a></li>
		<li' . ((eregi('admin_topic_shadow.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_topic_shadow.'.$phpEx) . '"><span>' . $lang['Topic_Shadow'] . '</span></a></li>
		<li' . ((eregi('admin_quick_title.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_quick_title.'.$phpEx) . '"><span>' . $lang['Quick_Title_infos'] . '</span></a></li>
		<li><a href="' . append_sid('admin_topics_anywhere.'.$phpEx) . '"><span>' . $lang['Topics_anywhere'] . '</span></a></li>
		<li' . ((eregi('admin_topic_kicker.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_topic_kicker.'.$phpEx) . '"><span>' . $lang['tk_kicker_table1'] . '</span></a></li>
		<li' . ((eregi('admin_topic_force_read.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_topic_force_read.'.$phpEx) . '"><span>' . $lang['FTR_config'] . ' ' . $lang['Setting'] . '</span></a></li>
		<li' . ((eregi('admin_topic_force_read.'.$phpEx.'?mode=users', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_topic_force_read.'.$phpEx.'?mode=users') . '"><span>' . $lang['admin_ftr_config_title'] . '</span></a></li>
		<li' . ((eregi('admin_board.'.$phpEx.'?mode=inline_ads', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_board.'.$phpEx.'?mode=inline_ads') . '"><span>' . $lang['Inline_ad_config'] . ' ' . $lang['Setting'] . '</span></a></li>
';

// Toplist
$toplist_menu = '
	<li class="header">' . $lang['Toplist'] . ' ' . $lang['Manage'] . '</li>
		<li' . ((eregi('admin_board.'.$phpEx.'mode=toplist_config', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_board.'.$phpEx.'?mode=toplist_config') . '"><span>' . $lang['Toplist'] . ' ' . $lang['Setting'] . '</span></a></li>
		<li' . ((eregi('admin_board.'.$phpEx.'?mode=toplist', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_board.'.$phpEx.'?mode=toplist') . '"><span>' . $lang['User_Toplists'] . '</span></a></li>
';
	
// Users
$user_menu = '
	<li class="header">' . $lang['User'] . ' ' . $lang['Manage'] . '</li>
		<li' . ((eregi('admin_user_list.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_user_list.'.$phpEx) . '"><span>' . $lang['Management_list'] . '</span></a></li>
		<li' . ((eregi('admin_priv_msgs.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_priv_msgs.'.$phpEx) . '"><span>' . $lang['Manage_Private_Messages'] . '</span></a></li>
		<li' . ((eregi('admin_user_referrals.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_user_referrals.'.$phpEx) . '"><span>' .  $lang['User_Referrals'] . '</span></a></li>
		<li' . ((eregi('admin_shop_users.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_shop_users.'.$phpEx) . '"><span>' . $lang['User_shops'] . '</span></a></li>
		<li' . ((eregi('admin_user_ranks.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_user_ranks.'.$phpEx) . '"><span>' . $lang['Ranks_title'] . '</span></a></li>
		<li' . ((eregi('admin_post_assoc.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_post_assoc.'.$phpEx) . '"><span>' . $lang['Post_Associator'] . '</span></a></li>
		<li' . ((eregi('admin_user_prune_posts.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_user_prune_posts.'.$phpEx) . '"><span>' . $lang['Prune_user_posts'] . '</span></a></li>
		<li' . ((eregi('admin_user_recent_logins.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_user_recent_logins.'.$phpEx) . '"><span>' . $lang['Recent_Logins'] . '</span></a></li>
		<li' . ((eregi('admin_user_import_csv.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_user_import_csv.'.$phpEx) . '"><span>' . $lang['Import_users'] . '</span></a></li>
';

// User Communication 
$usercom_menu = '
	<li class="header">' . $lang['User_communication'] . '</li>
		<li' . ((eregi('admin_board.'.$phpEx.'?mode=config', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_board.'.$phpEx.'?mode=email') . '"><span>' . $lang['Email'] . ' ' . $lang['Setting'] . '</span></a></li>
		<li' . ((eregi('admin_board.'.$phpEx.'?mode=pm', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_board.'.$phpEx.'?mode=pm') . '"><span>' . $lang['Private_Messaging'] . '</span></a></li>
		<li' . ((eregi('admin_im_config.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_im_config.'.$phpEx) . '"><span>' . $lang['Prillian'] . ' ' . $lang['Setting'] . '</span></a></li>
		<li' . ((eregi('admin_chat_config.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_chat_config.'.$phpEx) . '"><span>' . $lang['PJIRC_Chat_Settings'] . ' ' . $lang['Setting'] . '</span></a></li>
		<li' . ((eregi('admin_helpdesk.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_helpdesk.'.$phpEx) . '"><span>' . $lang['Helpdesk'] . ' ' . $lang['Setting'] . '</span></a></li>
';

// User Security
$ban_menu = '
	<li class="header">' . $lang['User_security'] . '</li>
		<li' . ((eregi('admin_ban.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_ban.'.$phpEx) . '"><span>' . $lang['Ban_Manage'] . '</span></a></li>
		<li' . ((eregi('admin_ban_adv.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_ban_adv.'.$phpEx) . '"><span>' . $lang['BM_Title'] . '</span></a></li>
		<li' . ((eregi('admin_ban_control.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_ban_control.'.$phpEx) . '"><span>' . $lang['Ban_control'] . '</span></a></li>
		<li' . ((eregi('admin_ban_referer.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_ban_referer.'.$phpEx) . '"><span>' . $lang['Ban_sites_title'] . '</span></a></li>
		<li' . ((eregi('admin_board.'.$phpEx.'?mode=bancard', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_board.'.$phpEx.'?mode=bancard') . '"><span>' . $lang['Ban_card_config'] . '</span></a></li>
		<li' . ((eregi('admin_disallow.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_disallow.'.$phpEx) . '"><span>' . $lang['Disallow_control'] . '</span></a></li>
		<li' . ((eregi('admin_user_prune.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_user_prune.'.$phpEx) . '"><span>' . $lang['Prune_users'] . '</span></a></li>
		<li' . ((eregi('admin_board.'.$phpEx.'?mode=autoprune', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_board.'.$phpEx.'?mode=autoprune') . '"><span>' . $lang['User_Auto_Delete'] . '</span></a></li>
		<li' . ((eregi('admin_logs_ip.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_logs_ip.'.$phpEx) . '"><span>' . $lang['IP_Logger'] . '</span></a></li>
';

// Utilities
$utils_menu = '
	<li class="header">' . $lang['Utilities_'] . '</li>
		<li' . ((eregi('admin_site_backup.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_site_backup.'.$phpEx) . '"><span>' . $lang['Database_Backup_Site'] . '</span></a></li>
		<li' . ((eregi('admin_deadlink_checker.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_deadlink_checker.'.$phpEx) . '"><span>' . $lang['Dead_link_checker'] . '</span></a></li>
		<li' . ((eregi('admin_db_utilities_phpbbmyadmin.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_db_utilities_phpbbmyadmin.'.$phpEx) . '"><span>' . $lang['PhpBBMyAdmin'] . '</span></a></li>
		<li' . ((eregi('admin_phpinfo.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_phpinfo.'.$phpEx) . '"><span>' . $lang['PHP_Info'] . '</span></a></li>
		<li' . ((eregi('admin_server_test.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_server_test.'.$phpEx) . '"><span>' . $lang['ST_default'] . '</span></a></li>
';

//
// Send to template
//
$template->assign_vars(array(		
	'ACP_CONFIG_MENU' => $config_select,
	'QUICK_MENU' => $quick_access,
	'CONFIG_MENU' => $config_menu,
	'ALBUM_MENU' => $album_menu,
	'ATTACH_MENU' => $attach_menu,
	'AVATAR_MENU' => $avatar_menu,
	'BANNER_MENU' => $banner_menu,
	'BAN_MENU' => $ban_menu,
	'BOOKIE_MENU' => $bookie_menu,
	'BOT_MENU' => $bots_menu,
	'CUSTOM_PROFILE_MENU' => $custom_profile_menu,
	'DB_MENU' => $db_menu,
	'DIGESTS_MENU' => $digests_menu,
	'DOWNLOAD_MENU' => $download_menu,
	'EMAIL_MENU' => $email_menu,
	'FORUM_MENU' => $forum_menu,
	'GAMES_MENU' => $game_menu,
	'GROUP_MENU' => $group_menu,
	'INLINE_AD_MENU' => $inline_ad_menu,
	'KB_MENU' => $kb_menu,
	'LANG_MENU' => $lang_menu,
	'LOG_MENU' => $log_menu,
	'LEXICON_MENU' => $lexicon_menu,
	'LINKDB_MENU' => $link_menu,
	'MEDALS_MENU' => $medals_menu,
	'MEETING_MENU' => $meeting_menu,
	'PERMS_MENU' => $perms_menu,
	'POST_MENU' => $post_menu,
	'PORTAL_MENU' => $portal_menu,
	'PRILL_MENU' => $prill_menu,
	'PM_MENU' => $pm_menu,
	'STYLE_MENU' => $style_menu,
	'SERVER_MENU' => $server_menu,
	'SMILEY_MENU' => $smiley_menu,
	'STATS_MENU' => $stats_menu,
	'SUBSCRIPTION_MENU' => $subscription_menu,
	'TOPIC_MENU' => $topic_menu,
	'TOPLIST_MENU' => $toplist_menu,
	'TPL_MENU' => $tpl_menu,
	'USERCOM_MENU' => $usercom_menu,
	'USER_MENU' => $user_menu,
	'UTILS_MENU' => $utils_menu,
	'VOTE_MENU' => $poll_menu,
));
	
?>