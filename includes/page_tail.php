<?php
/** 
*
* @package includes
* @version $Id: page_tail.php,v 1.27.2.2 2002/11/26 11:42:12 psotfx Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
if ( !defined('IN_PHPBB') )
{
	die('Hacking attempt');
}

//
// Show the overall footer.
//
global $do_gzip_compress;

// Banners
if ($banner_show_list)
{
	$banner_show_list['0'] = ($banner_show_list) ? ' ' : '';
	$sql = "UPDATE " . BANNERS_TABLE . " 
		SET banner_view = banner_view + 1 
		WHERE banner_id IN ($banner_show_list)"; 
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not update banner data', '', __LINE__, __FILE__, $sql);
	} 
}

// Theme style box (if more than one theme available)
if ( !$board_config['override_user_style'] ) 
{
	$template->assign_block_vars('switch_style_select', array());

	$sql = "SELECT themes_id, style_name, theme_public 
		FROM " . THEMES_TABLE . "
		ORDER BY style_name, themes_id"; 
	if ( !($result = $db->sql_query($sql)) ) 
	{ 
		message_die(GENERAL_ERROR, 'Could not obtain theme/style information', '', __LINE__, __FILE__, $sql); 
	} 
	
	while ($style_select = $db->sql_fetchrow($result)) 
	{ 
		$selected = ( $style_select['themes_id'] == $userdata['user_style'] ) ? ' selected="selected"' : '';

		if ( $style_select['theme_public'] == TRUE || $userdata['user_level'] == ADMIN || $style_select['themes_id'] == $board_config['default_style'] ) 
		{
			$template->assign_block_vars('switch_style_select.style_select', array(
				 'options' => '<option value="changestyle.'.$phpEx.'?' . STYLE_URL . '=' . $style_select['style_name'] . '"' . $selected . '>' . $style_select['style_name'] . '</option>')
			); 
		}
	}
	$db->sql_freeresult($result);
}

// Page transition toggle
if ($board_config['page_transition'] && $userdata['session_logged_in'])
{
	$template->assign_block_vars('switch_transition_toggle', array(
		'L_TRANSITION_TOGGLE' => (($userdata['user_transition']) ? $lang['Disable'] : $lang['Enable']) . ' ' . $lang['Page_transition'],
		'TRANSITION_TOGGLE' => append_sid('profile.'.$phpEx.'?mode=transition&amp;' . POST_TOPIC_URL . '=' . ($userdata['user_transition'] ? 0 : TRUE)))
	);
	
}

// Search Box
if ($board_config['search_footer'])
{	
	$template->assign_block_vars('switch_search_footer', array());
}

// Sitemap
if ( $board_config['board_sitemap'] )
{
	$sitemap_link = '<a href="' . append_sid('sitemap.'.$phpEx) . '" class="copyright">' . $lang['Sitemap'] . '</a><br />';
}

// Serverload
$out_server = '';
if ($board_config['board_hits'])
{
	$server_load = serverload();
	$out_server = $server_load . $lang['Pages_served'] . ' | ' . uniquehits() . ' ' . sprintf($lang['Unique_hits'], ($board_config['uniquehits_time'] / 60) );
}

// Administration Control Panel
if ( $userdata['user_level'] == ADMIN ) 
{ 
	$admin_link = '[ <a href="admin/index.' . $phpEx . '?sid=' . $userdata['session_id'] . '" class="copyright" title="' . $lang['Admin_panel'] . '" target="_top">' . $lang['Admin_panel'] . '</a> | <a href="' . append_sid('statistics.'.$phpEx) . '" class="copyright" title="' . $lang['Stats_panel'] . '">' . $lang['Stats_panel'] . '</a> ]<br /><br />'; 
} 
else if ( $userdata['user_level'] == LESS_ADMIN ) 
{ 
	$admin_link = '[ <a href="admin_super/index.' . $phpEx . '?sid=' . $userdata['session_id'] . '" class="copyright" title="' . $lang['Admin_panel'] . '" target="_top">' . $lang['Admin_panel'] . '</a> | <a href="' . append_sid('statistics.'.$phpEx) . '" class="copyright" title="' . $lang['Stats_panel'] . '">' . $lang['Stats_panel'] . '</a> ]<br /><br />'; 
}
else if ( $userdata['user_level'] == MOD ) 
{ 
	$admin_link = '[ <a href="admin_mod/index.' . $phpEx . '?sid=' . $userdata['session_id'] . '" class="copyright" title="' . $lang['Admin_panel'] . '" target="_top">' . $lang['Admin_panel'] . '</a> | <a href="' . append_sid('statistics.'.$phpEx) . '" class="copyright" title="' . $lang['Stats_panel'] . '">' . $lang['Stats_panel'] . '</a> ]<br /><br />'; 
}


//
// Parse and show the overall footer.
// If an ImageSet Child Theme check if it has custom footer
//
$theme_custom_footer = ( $theme['theme_footer'] ) ? 'images/' . $theme['image_cfg'] . '/' : '';
$template->set_filenames(array(
	'overall_footer' => ( empty($gen_simple_header) ) ? $theme_custom_footer . 'overall_footer.tpl' : 'simple_footer.tpl')
);

//
// Footer credits for styles
//
if ($theme['style_name'] === 'Charcoal2')
{
	$style_credit = '<br />Original Charcoal2 by <a href="http://www.zarron.com/" target="_blank" class="copyright">Zarron Media</a>';
}

$template->assign_vars(array(
	'L_ALL_CONTENT' => $lang['All_content'],
	'L_ORIG_AUTHOR' => $lang['Original_author'],
	'BOARD_SIG' => (!empty($board_config['board_sig'])) ? '<br />' . $board_config['board_sig'] : '',
	'TRANSLATION_INFO' => (isset($lang['TRANSLATION_INFO'])) ? '<br />' . $lang['TRANSLATION_INFO'] . $style_credit : $style_credit,
	'SITEMAP_LINK' => $sitemap_link,
	'ADMIN_LINK' => $admin_link, 
	'AUTO_BACKUP' => ($board_config['enable_autobackup']) ? $phpbb_root_path . 'cron.'.$phpEx : $phpbb_root_path . 'images/spacer.gif',
	'SERVER_LOAD' => $out_server)
);

$template->pparse('overall_footer');

if ( $board_config['admin_auto_delete_non_visit'] || $board_config['admin_auto_delete_inactive'] || $board_config['admin_auto_delete_no_post'] )
{
	include($phpbb_root_path . 'includes/prune_users.'.$phpEx);
	auto_delete_users();
}

//
// Generation Creation
//
if ($board_config['board_serverload'])
{
	$mtime = microtime(); 
	$mtime = explode(" ",$mtime); 
	$mtime = $mtime[1] + $mtime[0]; 
	$endtime = $mtime; 
	$totaltime = ($endtime - $starttime); 

	$gzip_text = ( $board_config['gzip_compress'] ) ? 'GZIP : ' . $lang['ON'] : ''; 
	$debug_mode = ( $board_config['debug_value'] ) ? ' | Debug : ' . $lang['ON'] : ''; 
}

//
// Close our DB connection.
//
$db->sql_close();

//
// Compress buffered output if required and send to browser
//
if ( $do_gzip_compress )
{
	//
	// Borrowed from php.net!
	//
	$gzip_contents = ob_get_contents();
	ob_end_clean();

	$gzip_size = strlen($gzip_contents);
	$gzip_crc = crc32($gzip_contents);

	$gzip_contents = gzcompress($gzip_contents, $board_config['gzip_level']);
	$gzip_contents = substr($gzip_contents, 0, strlen($gzip_contents) - 4);

	echo "\x1f\x8b\x08\x00\x00\x00\x00\x00";
	echo $gzip_contents;
	echo pack('V', $gzip_crc);
	echo pack('V', $gzip_size);
}

if ($board_config['board_serverload'])
{
	$generation_time = printf("<div align=\"center\" class=\"copyright\">[ Time : %fs | " . $db->num_queries . " Queries | " . $gzip_text . $debug_mode . " ]</div></body></html>", $totaltime); 
}
else
{
	echo '<br /></body></html>';
}
exit;

?>