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

//$userdata['user_level'] = MOD; // Moderator permissions
//$userdata['user_level'] = LESS_ADMIN; // Super Moderator permissions

//
// Moderator CP
//
// Quick Access
$mod_menu =  '<li class="header">' . $lang['Quick_access'] . '</li>
		<li' . ((eregi('index.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('index.'.$phpEx) . '"><span>' . $lang['Admin_Index'] . '</span></a></li>
		<li><a href="' . append_sid($phpbb_root_path . 'faq.'.$phpEx.'?mode=moderator_faq') . '"><span>' . $lang['Moderators_Manual'] . '</span></a></li>
';

// Attachments
$mod_menu .= ($userdata['user_level'] == LESS_ADMIN) ? '<li class="header">' . $lang['Attachment'] . ' ' . $lang['Manage'] . '</li>
		<li' . ((eregi('admin_attachments.'.$phpEx.'?mode=shadow', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_attachments.'.$phpEx.'?mode=shadow') . '"><span>' . $lang['Shadow_attachments'] . '</span></a></li>
		<li><a href="' . append_sid('admin_attachments.'.$phpEx.'?mode=sync') . '"><span>' . $lang['Sync_attachments'] . '</span></a></li>
' : '';

// Avatars
$mod_menu .= (($userdata['user_level'] == MOD && $board_config['enable_module_avdelete']) || $userdata['user_level'] == LESS_ADMIN) ? '<li class="header">' . $lang['Manage_avatars'] . '</li>
		<li' . ((eregi('admin_avatar_unused.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_avatar_unused.'.$phpEx) . '"><span>' . $lang['Unused_Avatars'] . '</span></a></li>
' : '';
$mod_menu .= ($userdata['user_level'] == LESS_ADMIN) ? '
		<li' . ((eregi('admin_avatar_view.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_avatar_view.'.$phpEx) . '"><span>' . $lang['View_Avatars'] . '</span></a></li>
' : '';

// Database
$mod_menu .= (($userdata['user_level'] == MOD && $board_config['enable_module_backup']) || $userdata['user_level'] == LESS_ADMIN) ? '<li class="header">' . $lang['Database'] . '</li>
		<li' . ((eregi('admin_db_utilities.'.$phpEx.'?perform=backup', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_db_utilities.'.$phpEx.'?perform=backup') . '"><span>' . $lang['Database_Backup'] . '</span></a></li>
' : '';

// E-mail
$mod_menu .= (($userdata['user_level'] == MOD && $board_config['enable_module_mass_email']) || $userdata['user_level'] == LESS_ADMIN) ? '<li class="header">' . $lang['Email'] . ' ' . $lang['Manage'] . '</li>
		<li' . ((eregi('admin_email_mass.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_email_mass.'.$phpEx) . '"><span>' . $lang['Mass_Email'] . '</span></a></li>
' : '';
$mod_menu .= ($userdata['user_level'] == LESS_ADMIN) ? '
		<li' . ((eregi('admin_email_list.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_email_list.'.$phpEx) . '"><span>' . $lang['Email_list'] . '</span></a></li>
		<li' . ((eregi('admin_email_search.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_email_search.'.$phpEx) . '"><span>' . $lang['Email_search'] . '</span></a></li>
' : '';

// Forums
$mod_menu .= ($userdata['user_level'] == LESS_ADMIN) ? '
	<li class="header">' . $lang['Forum'] . ' ' . $lang['Manage'] . '</li>
		<li' . ((eregi('admin_forums.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_forums.'.$phpEx) . '"><span>' . $lang['Forum_admin'] . '</span></a></li>
		<li' . ((eregi('admin_forum_prune.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_forum_prune.'.$phpEx) . '"><span>' . $lang['Forum_Prune'] . '</span></a></li>
		<li' . ((eregi('admin_forum_prune_overview.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_forum_prune_overview.'.$phpEx) . '"><span>' . $lang['Prune_Overview'] . ' ' . $lang['Setting'] . '</span></a></li>
' : '';

// Posts
$mod_menu .= (($userdata['user_level'] == MOD && $board_config['enable_module_ranks']) || $userdata['user_level'] == LESS_ADMIN) ? '<li class="header">' . $lang['Post'] . ' ' . $lang['Manage'] . '</li>
		<li' . ((eregi('admin_user_ranks.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_user_ranks.'.$phpEx) . '"><span>' . $lang['Ranks_title'] . '</span></a></li>
' : '';

// Posts
$mod_menu .= (!$board_config['enable_module_ranks'] && $board_config['enable_module_words']) ? '<li class="header">' . $lang['Post'] . ' ' . $lang['Manage'] . '</li>' : '';
$mod_menu .= (($userdata['user_level'] == MOD && $board_config['enable_module_words']) || $userdata['user_level'] == LESS_ADMIN) ? '<li' . ((eregi('admin_words.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_words.'.$phpEx) . '"><span>' . $lang['Word_Censor'] . '</span></a></li>' : '';

// Smilies
$mod_menu .= (($userdata['user_level'] == MOD && $board_config['enable_module_smilies']) || $userdata['user_level'] == LESS_ADMIN) ? '<li class="header">' . $lang['Smiley'] . ' ' . $lang['Manage'] . '</li>
		<li' . ((eregi('admin_smilies.'.$phpEx.'?cat_view', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_smilies.'.$phpEx.'?cat_view') . '"><span>' . $lang['Manage_smilies']  . '</span></a></li>
		<li' . ((eregi('admin_smilies.'.$phpEx.'?smiley_add', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_smilies.'.$phpEx.'?smiley_add') . '"><span>' . $lang['Add_new']  . '</span></a></li>
		<li' . ((eregi('admin_smilies.'.$phpEx.'?upload', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_smilies.'.$phpEx.'?upload') . '"><span>' . $lang['Upload']  . '</span></a></li>
		<li' . ((eregi('admin_smilies.'.$phpEx.'?unused_smilies', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_smilies.'.$phpEx.'?unused_smilies') . '"><span>' . $lang['Smilie_unused_title']  . '</span></a></li>
		<li' . ((eregi('admin_smilies.'.$phpEx.'?cat_add', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_smilies.'.$phpEx.'?cat_add') . '"><span>' . $lang['Add_cat']  . '</span></a></li>
		<li' . ((eregi('admin_smilies.'.$phpEx.'?cat_edit', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_smilies.'.$phpEx.'?cat_edit') . '"><span>' . $lang['Edit_Category']  . '</span></a></li>
		<li' . ((eregi('admin_smilies.'.$phpEx.'?view_perms', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_smilies.'.$phpEx.'?view_perms') . '"><span>' . $lang['Permissions']  . '</span></a></li>
		<li' . ((eregi('admin_smilies.'.$phpEx.'?import_pack', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_smilies.'.$phpEx.'?import_pack') . '"><span>' . $lang['Smilie_import_pak_title']  . '</span></a></li>
		<li' . ((eregi('admin_smilies.'.$phpEx.'?export_pack', $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_smilies.'.$phpEx.'?export_pack') . '"><span>' . $lang['Smilie_export_pak_title']  . '</span></a></li>
' : '';

// Topics
$mod_menu .= ($userdata['user_level'] == LESS_ADMIN) ? '<li class="header">' . $lang['Topic'] . ' ' . $lang['Manage'] . '</li>
		<li' . ((eregi('admin_topic_move.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_topic_move.'.$phpEx) . '"><span>' . $lang['Move_topics'] . '</span></a></li>
		<li' . ((eregi('admin_topic_shadow.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_topic_shadow.'.$phpEx) . '"><span>' . $lang['Topic_Shadow'] . '</span></a></li>
' : '';

// Users
$mod_menu .= (($userdata['user_level'] == MOD && $board_config['enable_module_users']) || $userdata['user_level'] == LESS_ADMIN) ? '<li class="header">' . $lang['User'] . ' ' . $lang['Manage'] . '</li>
		<li' . ((eregi('admin_users.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_users.'.$phpEx) . '"><span>' . $lang['User_admin'] . '</span></a></li>
' : '';

// User Security
$mod_menu .= (($userdata['user_level'] == MOD && $board_config['enable_module_user_ban']) || $userdata['user_level'] == LESS_ADMIN) ? '<li class="header">' . $lang['User_security'] . '</li>
		<li' . ((eregi('admin_ban.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_ban.'.$phpEx) . '"><span>' . $lang['Ban_Manage'] . '</span></a></li>
		<li' . ((eregi('admin_ban_adv.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_ban_adv.'.$phpEx) . '"><span>' . $lang['BM_Title'] . '</span></a></li>
		<li' . ((eregi('admin_ban_control.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_ban_control.'.$phpEx) . '"><span>' . $lang['Ban_control'] . '</span></a></li>
' : '';

// User Security
$mod_menu .= (!$board_config['enable_module_user_ban'] && $board_config['enable_module_disallow']) ? '<li class="header">' . $lang['User_security'] . '</li>' : '';
$mod_menu .= (($userdata['user_level'] == MOD && $board_config['enable_module_disallow']) || $userdata['user_level'] == LESS_ADMIN) ? '<li' . ((eregi('admin_disallow.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_disallow.'.$phpEx) . '"><span>' . $lang['Disallow_control'] . '</span></a></li>' : '';

// Utilities
$mod_menu .= '<li class="header">' . $lang['Utilities_'] . '</li>
		<li' . ((eregi('admin_phpinfo.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_phpinfo.'.$phpEx) . '"><span>' . $lang['PHP_Info'] . '</span></a></li>
		<li' . ((eregi('admin_server_test.'.$phpEx, $_SERVER['PHP_SELF'])) ? ' id="activemenu"' : '') . '><a href="' . append_sid('admin_server_test.'.$phpEx) . '"><span>' . $lang['ST_default'] . '</span></a></li>
';


//
// Send to template
//
$template->assign_vars(array(
	'MOD_CP_MENU' => $mod_menu)
);
	
?>