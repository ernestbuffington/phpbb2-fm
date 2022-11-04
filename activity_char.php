<?php
/** 
*
* @package phpBB2
* @version $Id: activity_char.php,v 1.1.0 2005 austin Exp $
* @copyright (c) 2003 aUsTiN-Inc
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include_once($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include($phpbb_root_path . 'includes/functions_amod_plus.'.$phpEx);

//
// Start session management 
//
$userdata = session_pagestart($user_ip, PAGE_ACTIVITY);
init_userprefs($userdata);
//
// End session management 
//	
	

//
// Start Restriction Checks
///
BanCheck();				
//
// End Restriction Checks 
//	

$mode 	= (isset($HTTP_GET_VARS['mode'])) ? htmlspecialchars($HTTP_GET_VARS['mode']) : htmlspecialchars($HTTP_POST_VARS['mode']);
$action = (isset($HTTP_GET_VARS['action'])) ? htmlspecialchars($HTTP_GET_VARS['action']) : htmlspecialchars($HTTP_POST_VARS['action']);
			
if ( (!$mode) && ($userdata['ina_char_name']) )
{
	$mode = 'edit_char';
}
		
if ( (!$mode) && (!$userdata['ina_char_name']) )
{
	$mode = 'create_char';
}
				
#==== Dynamic Page Titles
$page_title = $lang['amp_char_'. $mode .'_page_title'];
	
#==== Setup What .tpl To Use
if ($mode == 'create_char' || $mode == 'edit_char' || $mode == 'del_char' || $mode == 'profile_char' || !$mode)
{
	$template->set_filenames(array(
		'body' => 'amod_files/activity_char_body.tpl') 
	);
	make_jumpbox('viewforum.'.$phpEx);
}

if ($mode == 'char_shop')
{
	$template->set_filenames(array(
		'body' => 'amod_files/activity_char_shop_body.tpl') 
	);
	make_jumpbox('viewforum.'.$phpEx);
}
		
#==== Template Switches
if ( ($mode == 'create_char' || !$mode) && (!$userdata['ina_char_name']) )
{
	$template->assign_block_vars('create_char', array());
}
	
if ( ($mode == 'edit_char' || !$mode) && ($userdata['ina_char_name']) )
{
	$template->assign_block_vars('edit_char', array());
}
		
if ($mode == 'profile_char')
{
	$template->assign_block_vars('profile_char', array());
}
	
#==== Char Info Array
$q = "SELECT *
	FROM " . USERS_TABLE . "
	WHERE ina_char_name <> ''";
$r 			= $db->sql_query($q);
$char_info 	= $db->sql_fetchrowset($r);

$users_drop = '';
$users_drop .= '<select onChange="if(options[selectedIndex].value)window.location.href=(options[selectedIndex].value)">';
$users_drop .= '<option selected value="">'. $lang['amp_char_other_chars'] .'</option>';
for ($x = 0; $x < sizeof($char_info); $x++)
{
	$users_drop .= '<option value="activity_char.'. $phpEx .'?mode=profile_char&char='. $char_info[$x]['user_id'] .'">'. $char_info[$x]['username'] .'\'s :: '. $char_info[$x]['ina_char_name'] .'</option>';
}
$users_drop .= '</select>';
$users_drop .= '<noscript><input type="submit" value="Go"></noscript>';
				
$template->assign_vars(array(
	'USERS'	=> $users_drop)
);
				
if ($mode == 'char_shop')
{
	if (!$userdata['ina_char_name'])
	{
		redirect('activity_char.'. $phpEx .'?sid='. $userdata['session_id'], TRUE);
	}	
	
	if ($action == 'save_settings')
	{			
		#==== Name Effects
		$name_bought_color		= $HTTP_POST_VARS['color_name'];
		$name_what_color_c		= $HTTP_POST_VARS['color_color_name'];
		$name_bought_shadow		= $HTTP_POST_VARS['shadow_name'];
		$name_what_color_s		= $HTTP_POST_VARS['shadow_color_name'];
		$name_bought_glow		= $HTTP_POST_VARS['glow_name'];
		$name_what_color_g		= $HTTP_POST_VARS['glow_color_name'];
		$name_bold 				= $HTTP_POST_VARS['bold_name'];
		$name_italic 			= $HTTP_POST_VARS['italic_name'];
		$name_underline 		= $HTTP_POST_VARS['underline_name'];

		#==== Saying Effects
		$saying_bought_color	= $HTTP_POST_VARS['color_saying'];
		$saying_what_color_c	= $HTTP_POST_VARS['color_color_saying'];
		$saying_bought_shadow	= $HTTP_POST_VARS['shadow_saying'];
		$saying_what_color_s	= $HTTP_POST_VARS['shadow_color_saying'];
		$saying_bought_glow		= $HTTP_POST_VARS['glow_saying'];
		$saying_what_color_g	= $HTTP_POST_VARS['glow_color_saying'];
		$saying_bold 			= $HTTP_POST_VARS['bold_saying'];
		$saying_italic 			= $HTTP_POST_VARS['italic_saying'];
		$saying_underline 		= $HTTP_POST_VARS['underline_saying'];

		#==== Title Effects
		$title_bought_color		= $HTTP_POST_VARS['color_title'];
		$title_what_color_c		= $HTTP_POST_VARS['color_color_title'];
		$title_bought_shadow	= $HTTP_POST_VARS['shadow_title'];
		$title_what_color_s		= $HTTP_POST_VARS['shadow_color_title'];
		$title_bought_glow		= $HTTP_POST_VARS['glow_title'];
		$title_what_color_g		= $HTTP_POST_VARS['glow_color_title'];
		$title_bold 			= $HTTP_POST_VARS['bold_title'];
		$title_italic 			= $HTTP_POST_VARS['italic_title'];
		$title_underline 		= $HTTP_POST_VARS['underline_title'];

		#==== Costs
		$title_change_costs 	= explode(',', $board_config['ina_char_title_effects_costs']);
		$saying_change_costs 	= explode(',', $board_config['ina_char_saying_effects_costs']);
		$name_change_costs 		= explode(',', $board_config['ina_char_name_effects_costs']);

		#==== Total Up GE Spent
		$total_spent			= 0;
		if ( ($name_bought_color) && (!eregi('c-', $userdata['ina_char_name_effects'])) ) 
		{
			$total_spent += $name_change_costs[0];
		}
		if ( ($name_bought_shadow) && (!eregi('s-', $userdata['ina_char_name_effects'])) ) 
		{
			$total_spent += $name_change_costs[1];
		}
		if ( ($name_bought_glow) && (!eregi('g-', $userdata['ina_char_name_effects'])) ) 
		{
			$total_spent += $name_change_costs[2];
		}
		if ( ($name_bold) && (!eregi('b-', $userdata['ina_char_name_effects'])) ) 
		{
			$total_spent += $name_change_costs[3];
		}
		if ( ($name_italic) && (!eregi('i-', $userdata['ina_char_name_effects'])) ) 
		{
			$total_spent += $name_change_costs[4];
		}
		if ( ($name_underline) && (!eregi('u-', $userdata['ina_char_name_effects'])) ) 
		{
			$total_spent += $name_change_costs[5];
		}
			
		if ( ($saying_bought_color) && (!eregi('c-', $userdata['ina_char_saying_effects'])) ) 
		{
			$total_spent += $saying_change_costs[0];
		}
		if ( ($saying_bought_shadow) && (!eregi('s-', $userdata['ina_char_saying_effects'])) ) 
		{
			$total_spent += $saying_change_costs[1];
		}
		if ( ($saying_bought_glow) && (!eregi('g-', $userdata['ina_char_saying_effects'])) ) 
		{
			$total_spent += $saying_change_costs[2];
		}
		if ( ($saying_bold) && (!eregi('b-', $userdata['ina_char_saying_effects'])) ) 
		{
			$total_spent += $saying_change_costs[3];
		}
		if ( ($saying_italic) && (!eregi('i-', $userdata['ina_char_saying_effects'])) ) 
		{
			$total_spent += $saying_change_costs[4];
		}
		if ( ($saying_underline) && (!eregi('u-', $userdata['ina_char_saying_effects'])) ) 
		{
			$total_spent += $saying_change_costs[5];
		}
			
		if ( ($title_bought_color) && (!eregi('c-', $userdata['ina_char_title_effects'])) ) 
		{
			$total_spent += $title_change_costs[0];
		}
		if ( ($title_bought_shadow) && (!eregi('s-', $userdata['ina_char_title_effects'])) ) 
		{
			$total_spent += $title_change_costs[1];
		}
		if ( ($title_bought_glow) && (!eregi('g-', $userdata['ina_char_title_effects'])) ) 
		{
			$total_spent += $title_change_costs[2];
		}
		if ( ($title_bold) && (!eregi('b-', $userdata['ina_char_title_effects'])) ) 
		{
			$total_spent += $title_change_costs[3];
		}
		if ( ($title_italic) && (!eregi('i-', $userdata['ina_char_title_effects'])) ) 
		{
			$total_spent += $title_change_costs[4];
		}
		if ( ($title_underline) && (!eregi('u-', $userdata['ina_char_title_effects'])) ) 
		{
			$total_spent += $title_change_costs[5];
		}
		
		#==== Not enough GE?
		if ($total_spent > $userdata['ina_char_ge'])
		{
			AMP_Error_Handler(2, $lang['amp_char_shop_not_enough_ge'], 'activity_char', '?mode=char_shop&amp;sid='. $userdata['session_id']);
		}
		
		$name_update = $title_update = $saying_update = '';
		if ( ($name_bought_color) && ($name_what_color_c) )
		{
			$name_update .= 'c-'. $name_what_color_c .',';
		}
		if ( ($name_bought_shadow) && ($name_what_color_s) )
		{
			$name_update .= 's-'. $name_what_color_s .',';
		}
		if ( ($name_bought_glow) && ($name_what_color_g) )
		{
			$name_update .= 'g-'. $name_what_color_g .',';
		}
		if ($name_bold)
		{
			$name_update .= 'b-,';
		}
		if ($name_italic)
		{
			$name_update .= 'i-,';
		}
		if ($name_underline)
		{
			$name_update .= 'u-,';
		}
			
		if ( ($title_bought_color) && ($title_what_color_c) )
		{
			$title_update .= 'c-'. $title_what_color_c .',';
		}
		if ( ($title_bought_shadow) && ($title_what_color_s) )
		{
			$title_update .= 's-'. $title_what_color_s .',';
		}
		if ( ($title_bought_glow) && ($title_what_color_g) )
		{
			$title_update .= 'g-'. $title_what_color_g .',';
		}
		if ($title_bold)
		{
			$title_update .= 'b-,';
		}
		if ($title_italic)
		{
			$title_update .= 'i-,';
		}
		if ($title_underline)
		{
			$title_update .= 'u-,';
		}
			
		if ( ($saying_bought_color) && ($saying_what_color_c) )
		{
			$saying_update .= 'c-'. $saying_what_color_c .',';
		}
		if ( ($saying_bought_shadow) && ($saying_what_color_s) )
		{
			$saying_update .= 's-'. $saying_what_color_s .',';
		}
		if ( ($saying_bought_glow) && ($saying_what_color_g) )
		{
			$saying_update .= 'g-'. $saying_what_color_g .',';
		}
		if ($saying_bold)
		{
			$saying_update .= 'b-,';
		}
		if ($saying_italic)
		{
			$saying_update .= 'i-,';
		}
		if ($saying_underline)
		{
			$saying_update .= 'u-,';
		}
		
		#==== Take some GE for this transaction!
		AMP_Sub_GE($userdata['user_id'], $total_spent);
		
		$q = "UPDATE ". USERS_TABLE ."
			SET ina_char_saying_effects = '". $saying_update ."', ina_char_title_effects = '". $title_update ."', ina_char_name_effects = '". $name_update ."'
			WHERE user_id = " . $userdata['user_id'];
		$db->sql_query($q);
		
		AMP_Error_Handler(1, $lang['amp_char_cp_success'], 'activity_char', '?mode=char_shop&amp;sid='. $userdata['session_id']);				
	}
			
	#==== Setup checkboxes for what user already has
	$saying_bold_val 	= (eregi('b-', $userdata['ina_char_saying_effects'])) ? 'checked="checked"' : '';
	$saying_italic_val 	= (eregi('i-', $userdata['ina_char_saying_effects'])) ? 'checked="checked"' : '';
	$saying_under_val 	= (eregi('u-', $userdata['ina_char_saying_effects'])) ? 'checked="checked"' : '';
	$saying_color_val	= (eregi('c-', $userdata['ina_char_saying_effects'])) ? 'checked="checked"' : '';
	$saying_glow_val	= (eregi('g-', $userdata['ina_char_saying_effects'])) ? 'checked="checked"' : '';
	$saying_shadow_val	= (eregi('s-', $userdata['ina_char_saying_effects'])) ? 'checked="checked"' : '';
	$saying_color_split	= explode(',', $userdata['ina_char_saying_effects']);

	for ($x = 0; $x < sizeof($saying_color_split); $x++)
	{
		if (eregi('c-', $saying_color_split[$x]))
		{
			$saying_color = explode('-', $saying_color_split[$x]);
			$saying_color = $saying_color[1];
		}
		if (eregi('g-', $saying_color_split[$x]))
		{
			$saying_glow = explode('-', $saying_color_split[$x]);
			$saying_glow = $saying_glow[1];		
		}
		if (eregi('s-', $saying_color_split[$x]))
		{
			$saying_shadow = explode('-', $saying_color_split[$x]);
			$saying_shadow = $saying_shadow[1];		
		}	
		if (!$saying_color_split[$x])
		{
			break;					
		}
	}
	
	$title_bold_val 	= (eregi('b-', $userdata['ina_char_title_effects'])) ? 'checked="checked"' : '';
	$title_italic_val 	= (eregi('i-', $userdata['ina_char_title_effects'])) ? 'checked="checked"' : '';
	$title_under_val 	= (eregi('u-', $userdata['ina_char_title_effects'])) ? 'checked="checked"' : '';
	$title_color_val	= (eregi('c-', $userdata['ina_char_title_effects'])) ? 'checked="checked"' : '';
	$title_glow_val		= (eregi('g-', $userdata['ina_char_title_effects'])) ? 'checked="checked"' : '';
	$title_shadow_val	= (eregi('s-', $userdata['ina_char_title_effects'])) ? 'checked="checked"' : '';
	$title_color_split	= explode(',', $userdata['ina_char_title_effects']);

	for ($x = 0; $x < sizeof($title_color_split); $x++)
	{
		if (eregi('c-', $title_color_split[$x]))
		{
			$title_color = explode('-', $title_color_split[$x]);
			$title_color = $title_color[1];
		}
		if (eregi('g-', $title_color_split[$x]))
		{
			$title_glow = explode('-', $title_color_split[$x]);
			$title_glow = $title_glow[1];		
		}
		if (eregi('s-', $title_color_split[$x]))
		{
			$title_shadow = explode('-', $title_color_split[$x]);
			$title_shadow = $title_shadow[1];		
		}	
		if (!$title_color_split[$x])
		{
			break;					
		}
	}
		
	$name_bold_val 		= (eregi('b-', $userdata['ina_char_name_effects'])) ? 'checked="checked"' : '';
	$name_italic_val 	= (eregi('i-', $userdata['ina_char_name_effects'])) ? 'checked="checked"' : '';
	$name_under_val 	= (eregi('u-', $userdata['ina_char_name_effects'])) ? 'checked="checked"' : '';
	$name_color_val		= (eregi('c-', $userdata['ina_char_name_effects'])) ? 'checked="checked"' : '';
	$name_glow_val		= (eregi('g-', $userdata['ina_char_name_effects'])) ? 'checked="checked"' : '';
	$name_shadow_val	= (eregi('s-', $userdata['ina_char_name_effects'])) ? 'checked="checked"' : '';
	$name_color_split	= explode(',', $userdata['ina_char_name_effects']);

	for ($x = 0; $x < sizeof($name_color_split); $x++)
	{
		if (eregi('c-', $name_color_split[$x]))
		{
			$name_color = explode('-', $name_color_split[$x]);
			$name_color = $name_color[1];
		}
		if (eregi('g-', $name_color_split[$x]))
		{
			$name_glow = explode('-', $name_color_split[$x]);
			$name_glow = $name_glow[1];		
		}
		if (eregi('s-', $name_color_split[$x]))
		{
			$name_shadow = explode('-', $name_color_split[$x]);
			$name_shadow = $name_shadow[1];		
		}	
		if (!$name_color_split[$x])
		{
			break;					
		}
	}
				
	$title_change_costs 	= explode(',', $board_config['ina_char_title_effects_costs']);
	$saying_change_costs 	= explode(',', $board_config['ina_char_saying_effects_costs']);
	$name_change_costs 		= explode(',', $board_config['ina_char_name_effects_costs']);
			
	$template->assign_vars(array(
		'RETURN'				=> append_sid('activity_char.'. $phpEx .'?mode=char_shop'),
		'NAME_COST_ONE'			=> ($name_change_costs[0]) ? str_replace('%T%', $name_change_costs[0], $lang['amp_char_shop_cost_yes']) : $lang['amp_char_shop_cost_no'],
		'NAME_COST_TWO'			=> ($name_change_costs[1]) ? str_replace('%T%', $name_change_costs[1], $lang['amp_char_shop_cost_yes']) : $lang['amp_char_shop_cost_no'],
		'NAME_COST_THREE'		=> ($name_change_costs[2]) ? str_replace('%T%', $name_change_costs[2], $lang['amp_char_shop_cost_yes']) : $lang['amp_char_shop_cost_no'],
		'NAME_COST_FOUR'		=> ($name_change_costs[3]) ? str_replace('%T%', $name_change_costs[3], $lang['amp_char_shop_cost_yes']) : $lang['amp_char_shop_cost_no'],
		'NAME_COST_FIVE'		=> ($name_change_costs[4]) ? str_replace('%T%', $name_change_costs[4], $lang['amp_char_shop_cost_yes']) : $lang['amp_char_shop_cost_no'],
		'NAME_COST_SIX'			=> ($name_change_costs[5]) ? str_replace('%T%', $name_change_costs[5], $lang['amp_char_shop_cost_yes']) : $lang['amp_char_shop_cost_no'],
		'SAYING_COST_ONE'		=> ($saying_change_costs[0]) ? str_replace('%T%', $saying_change_costs[0], $lang['amp_char_shop_cost_yes']) : $lang['amp_char_shop_cost_no'],
		'SAYING_COST_TWO'		=> ($saying_change_costs[1]) ? str_replace('%T%', $saying_change_costs[1], $lang['amp_char_shop_cost_yes']) : $lang['amp_char_shop_cost_no'],
		'SAYING_COST_THREE'		=> ($saying_change_costs[2]) ? str_replace('%T%', $saying_change_costs[2], $lang['amp_char_shop_cost_yes']) : $lang['amp_char_shop_cost_no'],
		'SAYING_COST_FOUR'		=> ($saying_change_costs[3]) ? str_replace('%T%', $saying_change_costs[3], $lang['amp_char_shop_cost_yes']) : $lang['amp_char_shop_cost_no'],
		'SAYING_COST_FIVE'		=> ($saying_change_costs[4]) ? str_replace('%T%', $saying_change_costs[4], $lang['amp_char_shop_cost_yes']) : $lang['amp_char_shop_cost_no'],
		'SAYING_COST_SIX'		=> ($saying_change_costs[5]) ? str_replace('%T%', $saying_change_costs[5], $lang['amp_char_shop_cost_yes']) : $lang['amp_char_shop_cost_no'],
		'TITLE_COST_ONE'		=> ($title_change_costs[0]) ? str_replace('%T%', $title_change_costs[0], $lang['amp_char_shop_cost_yes']) : $lang['amp_char_shop_cost_no'],
		'TITLE_COST_TWO'		=> ($title_change_costs[1]) ? str_replace('%T%', $title_change_costs[1], $lang['amp_char_shop_cost_yes']) : $lang['amp_char_shop_cost_no'],
		'TITLE_COST_THREE'		=> ($title_change_costs[2]) ? str_replace('%T%', $title_change_costs[2], $lang['amp_char_shop_cost_yes']) : $lang['amp_char_shop_cost_no'],
		'TITLE_COST_FOUR'		=> ($title_change_costs[3]) ? str_replace('%T%', $title_change_costs[3], $lang['amp_char_shop_cost_yes']) : $lang['amp_char_shop_cost_no'],
		'TITLE_COST_FIVE'		=> ($title_change_costs[4]) ? str_replace('%T%', $title_change_costs[4], $lang['amp_char_shop_cost_yes']) : $lang['amp_char_shop_cost_no'],
		'TITLE_COST_SIX'		=> ($title_change_costs[5]) ? str_replace('%T%', $title_change_costs[5], $lang['amp_char_shop_cost_yes']) : $lang['amp_char_shop_cost_no'],				
		
		'SAYING_BOLD'			=> $saying_bold_val,
		'SAYING_ITALIC'			=> $saying_italic_val,
		'SAYING_UNDERLINE'		=> $saying_under_val,
		'SAYING_COLOR'			=> $saying_color_val,
		'SAYING_GLOW'			=> $saying_glow_val,
		'SAYING_SHADOW'			=> $saying_shadow_val,
		'SAYING_COLOR_S'		=> ($saying_color) 	? ucwords(strtolower($saying_color)) 	: '-------',
		'SAYING_GLOW_S'			=> ($saying_glow) 	? ucwords(strtolower($saying_glow))		: '-------',
		'SAYING_SHADOW_S'		=> ($saying_shadow)	? ucwords(strtolower($saying_shadow)) 	: '-------',
		'SAYING_COLOR_V'		=> ($saying_color) 	? $saying_color 	: '',
		'SAYING_GLOW_V'			=> ($saying_glow) 	? $saying_glow		: '',
		'SAYING_SHADOW_V'		=> ($saying_shadow)	? $saying_shadow	: '',
		'TITLE_BOLD'			=> $title_bold_val,
		'TITLE_ITALIC'			=> $title_italic_val,
		'TITLE_UNDERLINE'		=> $title_under_val,
		'TITLE_COLOR'			=> $title_color_val,
		'TITLE_GLOW'			=> $title_glow_val,
		'TITLE_SHADOW'			=> $title_shadow_val,
		'TITLE_COLOR_S'			=> ($title_color) 	? ucwords(strtolower($title_color)) 	: '-------',
		'TITLE_GLOW_S'			=> ($title_glow) 	? ucwords(strtolower($title_glow)) 		: '-------',
		'TITLE_SHADOW_S'		=> ($title_shadow) 	? ucwords(strtolower($title_shadow)) 	: '-------',
		'TITLE_COLOR_V'			=> ($title_color) 	? $title_color	: '',
		'TITLE_GLOW_V'			=> ($title_glow) 	? $title_glow 	: '',
		'TITLE_SHADOW_V'		=> ($title_shadow) 	? $title_shadow	: '',
		'NAME_BOLD'				=> $name_bold_val,
		'NAME_ITALIC'			=> $name_italic_val,
		'NAME_UNDERLINE'		=> $name_under_val,
		'NAME_COLOR'			=> $name_color_val,
		'NAME_GLOW'				=> $name_glow_val,
		'NAME_SHADOW'			=> $name_shadow_val,				
		'NAME_COLOR_S'			=> ($name_color) 	? ucwords(strtolower($name_color)) 	: '-------',
		'NAME_GLOW_S'			=> ($name_glow) 	? ucwords(strtolower($name_glow)) 	: '-------',
		'NAME_SHADOW_S'			=> ($name_shadow) 	? ucwords(strtolower($name_shadow)) : '-------',
		'NAME_COLOR_V'			=> ($name_color) 	? $name_color 	: '',
		'NAME_GLOW_V'			=> ($name_glow) 	? $name_glow 	: '',
		'NAME_SHADOW_V'			=> ($name_shadow) 	? $name_shadow 	: '',
			
		'L_COLOR'				=> $lang['amp_char_shop_color'],
		'L_EFFECT'				=> $lang['amp_char_shop_effect'],
		'L_COST'				=> $lang['amp_char_shop_cost'],															
		'L_LINK'				=> $lang['amp_char_link_back'],
		'L_OPTIONS'				=> $lang['amp_char_shop_options'],
		'L_OPTIONS_NAME'		=> $lang['amp_char_shop_options_name'],
		'L_OPTIONS_SAYING'		=> $lang['amp_char_shop_options_saying'],
		'L_OPTIONS_TITLE'		=> $lang['amp_char_shop_options_title'],
		'L_COLOR_BLUE'			=> $lang['amp_char_shop_color_blue'],
		'L_COLOR_GREEN'			=> $lang['amp_char_shop_color_green'],
		'L_COLOR_BLACK'			=> $lang['amp_char_shop_color_black'],
		'L_COLOR_WHITE'			=> $lang['amp_char_shop_color_white'],
		'L_COLOR_GREEN'			=> $lang['amp_char_shop_color_green'],
		'L_COLOR_YELLOW'		=> $lang['amp_char_shop_color_yellow'],
		'L_COLOR_RED'			=> $lang['amp_char_shop_color_red'],
		'L_COLOR_VIOLET'		=> $lang['amp_char_shop_color_violet'],
		'L_COLOR_CYAN'			=> $lang['amp_char_shop_color_cyan'],
		'L_EFFECTS_COLOR'		=> $lang['amp_char_shop_option_color'],
		'L_EFFECTS_SHADOW'		=> $lang['amp_char_shop_option_shadow'],
		'L_EFFECTS_GLOW'		=> $lang['amp_char_shop_option_glow'],
		'L_EFFECTS_BOLD'		=> $lang['amp_char_shop_option_bold'],
		'L_EFFECTS_ITALIC'		=> $lang['amp_char_shop_option_italic'],
		'L_EFFECTS_UNDERLINE'	=> $lang['amp_char_shop_option_underline'])
	);
}
		
if ($mode == 'del_char')
{
	if (!$userdata['ina_char_name'])
	{
		AMP_Error_Handler(2, $lang['amp_char_cp_delete_error'], 'activity_char', '?sid='. $userdata['session_id']);
	}
	
	if ( (isset($HTTP_GET_VARS['confirm'])) && ($userdata['ina_char_name']) )
	{
		AMP_Delete_Char($userdata['user_id']);
	}
	
	$message = $lang['amp_char_cp_delete_confirm'] . '<br /><br /><a href="' . append_sid('activity_char.'.$phpEx.'?mode=del_char&confirm=true') . '">' . $lang['amp_char_hof_member_y'] . '</a> &nbsp;&nbsp; <a href="' . append_sid('activity_char.'.$phpEx) . '">' . $lang['amp_char_hof_member_n'] . '</a>';

	message_die(GENERAL_MESSAGE, $message);
}
		
if ($mode == 'profile_char')
{
	$char = intval(($_GET['char']) ? $_GET['char'] : $HTTP_GET_VARS['char']);
	
	if (!AMP_Profile_Char($char, TRUE))
	{
		message_die(GENERAL_MESSAGE, $lang['amp_char_pro_no_char']);
	}
								
	$template->assign_block_vars('profile_char.data', array(
		'CHAR_PROFILE' => AMP_Profile_Char($char, 'TRUE'))
	);

	AMP_Profile_Views($char);
}
		
if ($mode == 'create_char' || !$mode)
{					
	if (!$userdata['ina_char_name'])
	{
		#==== Gather Male Character Options
		$m_dir 		= $phpbb_root_path .'images/activity/characters/male/';
		$browse_m 	= @opendir($m_dir);
		$stop 		= 0;
				
		while ($males = @readdir($browse_m)) 
		{			
			if ( ($males != '.') && ($males != '..') && ($males != 'index.htm') && ($males != 'index.html') && ($males != 'Thumbs.db') )
			{
				$seperate 	= explode('.', $males);
				$display	= str_replace('_', ' ', $seperate[0]);
				$value		= $seperate[0] .'.'. $seperate[1];
			
				$template->assign_block_vars('create_char.males', array(
					'NAMES'		=> ucwords(strtolower($display)),
					'VALUES'	=> $value)
				);					
			}
						
			if ( ($males != '.') && ($males != '..') && ($males != 'index.htm') && ($males != 'index.html') && ($males != 'Thumbs.db') && ($stop == 0) )
			{
				$template->assign_block_vars('create_char.mdefault', array(
					'VALUE'	=> $males)
				);										
				$stop++;
			}				
		}			
		@closedir($m_dir);
		
		#==== Gather Female Character Options	
		$f_dir 		= $phpbb_root_path .'images/activity/characters/female/';
		$browse_f 	= @opendir($f_dir);
		$stop		= 0;
			
		while ($females = @readdir($browse_f)) 
		{			
			if ( ($females != '.') && ($females != '..') && ($females != 'index.htm') && ($females != 'index.html') && ($females != 'Thumbs.db'))
			{
				$seperate 	= explode('.', $females);
				$display	= str_replace('_', ' ', $seperate[0]);
				$value		= $seperate[0] .'.'. $seperate[1];			
				
				$template->assign_block_vars('create_char.females', array(
					'NAMES'		=> ucwords(strtolower($display)),
					'VALUES'	=> $value)
				);					
			}
					
			if ( ($females != '.') && ($females != '..') && ($females != 'index.htm') && ($females != 'index.html') && ($females != 'Thumbs.db') && ($stop == 0) )
			{
				$template->assign_block_vars('create_char.fdefault', array(
					'VALUE'	=> $females)
				);										
				$stop++;
			}				
		}
		@closedir($f_dir);
	}
}
			
if ( ($mode == 'create_char' || $mode == 'edit_char') && ($action == 'save_char') )
{
	$char_name 		= isset($HTTP_POST_VARS['name']) ? htmlspecialchars($HTTP_POST_VARS['name']) : htmlspecialchars($HTTP_GET_VARS['name']);
	$char_img_m		= isset($HTTP_POST_VARS['mchar']) ? htmlspecialchars($HTTP_POST_VARS['mchar']) : htmlspecialchars($HTTP_GET_VARS['mchar']);
	$char_img_f		= isset($HTTP_POST_VARS['fchar']) ? htmlspecialchars($HTTP_POST_VARS['fchar']) : htmlspecialchars($HTTP_GET_VARS['fchar']);
	$char_gender 	= isset($HTTP_POST_VARS['gender']) ?  intval($HTTP_POST_VARS['gender']) :  intval($HTTP_GET_VARS['gender']);
	$char_intrests	= isset($HTTP_POST_VARS['intrests']) ? htmlspecialchars($HTTP_POST_VARS['intrests']) : htmlspecialchars($HTTP_GET_VARS['intrests']);
	$char_from		= isset($HTTP_POST_VARS['from']) ? htmlspecialchars($HTTP_POST_VARS['from']) : htmlspecialchars($HTTP_GER_VARS['from']);
	$char_age		= isset($HTTP_POST_VARS['age']) ? intval($HTTP_POST_VARS['age']) : intval($HTTP_GET_VARS['age']);
	$char_change 	= isset($HTTP_POST_VARS['change']) ? intval($HTTP_POST_VARS['change']) : intval($HTTP_GET_VARS['change']);	
	$char_title		= isset($HTTP_POST_VARS['title']) ? htmlspecialchars($HTTP_POST_VARS['title']) : htmlspecialchars($HTTP_GET_VARS['title']);
	$char_saying	= isset($HTTP_POST_VARS['saying']) ? htmlspecialchars($HTTP_POST_VARS['saying']) : htmlspecialchars($HTTP_GET_VARS['saying']);

	#==== make sure everything was filled in that has to be.
	$error = '';
	
	if (empty($char_name))
	{
		$error .= $lang['amp_char_cp_error_name'];
	}
			
	if (empty($char_img_m))
	{
		$error .= (($error) ? '<br />' : '') . $lang['amp_char_cp_error_character'];
	}
			
	if (empty($char_img_f))
	{
		$error .= (($error) ? '<br />' : '') . $lang['amp_char_cp_error_character'];
	}
			
	if (!$char_age || $char_age <= '0')
	{
		$error .= (($error) ? '<br />' : '') . $lang['amp_char_cp_error_age'];
	}
			
	if (!$char_gender)
	{
		$error .= (($error) ? '<br />' : '') . $lang['amp_char_cp_error_gender'];
	}
		
	if ( ($mode == 'edit_char') && (!$char_change) )
	{
		$error .= (($error) ? '<br />' : '') . $lang['amp_char_cp_error_change'];
	}
			
	if ($error)
	{
		AMP_Error_Handler(1, $error, 'activity_char', '?sid='. $userdata['session_id']);
	}
			
	$male	= $phpbb_root_path . 'images/activity/characters/male/' . $char_img_m;
	$female	= $phpbb_root_path . 'images/activity/characters/female/' . $char_img_f;
		
	$char = ($char_gender == 1) ? $male : $female;
	
	if ($mode == 'edit_char')
	{
		$char = ($char_change == 1) ? $char : $userdata['ina_char_img'];
	}			
	
	AMP_Update_Char($userdata['user_id'], $char_name, $char_age, $char_gender, $char, $char_from, $char_intrests, $char_title, $char_saying);
}
		
if ( (!$mode) && ($userdata['ina_char_name']) || $mode == 'edit_char')
{
	$template->assign_block_vars('edit_char.view', array(
		'CHAR_PROFILE'	=> AMP_Profile_Char($userdata['user_id'], ''),		
		'EDIT_EXP'		=> $lang['amp_char_edit_exp'])
	);
	
	#==== Change character
	$template->assign_block_vars('edit_char.edit', array(
		'CHR_EDIT_EXP' => $lang['amp_char_cp_your_edits'] .' :: <a href="activity_char.'. $phpEx .'?mode=char_shop&sid='. $userdata['session_id'] .'">'. $lang['amp_char_goto_shop_link'] .'</a>')
	);
	
	if ($board_config['ina_char_change_char_cost'])
	{
		if ($board_config['ina_char_change_char_cost'] > $userdata['ina_char_ge'])
		{
			$template->assign_block_vars('edit_char.edit.off', array(
				'ERROR' => $lang['amp_char_cp_not_enough_ge'])
			);
		}
		else
		{
			$template->assign_block_vars('edit_char.edit.on', array());				
			
			#==== Gather Male Character Options
			$m_dir 		= $phpbb_root_path .'images/activity/characters/male/';
			$browse_m 	= @opendir($m_dir);
			$stop 		= 0;
				
			while ($males = @readdir($browse_m)) 
			{			
				if ( ($males != '.') && ($males != '..') && ($males != 'index.htm') && ($males != 'index.html') )
				{
					$seperate 	= explode('.', $males);
					$display	= str_replace('_', ' ', $seperate[0]);
					$value		= $seperate[0] .'.'. $seperate[1];

					$template->assign_block_vars('edit_char.edit.males', array(
						'NAMES'		=> ucwords(strtolower($display)),
						'VALUES'	=> $value)
					);					
				}
							
				if ( ($males != '.') && ($males != '..') && ($males != 'index.htm') && ($males != 'index.html') && ($stop == 0) )
				{
					$template->assign_block_vars('edit_char.edit.mdefault', array(
						'VALUE'	=> $males)
					);										
					$stop++;
				}				
			}			
			@closedir($m_dir);

			#==== Gather Female Character Options	
			$f_dir 		= $phpbb_root_path .'images/activity/characters/female/';
			$browse_f 	= @opendir($f_dir);
			$stop		= 0;
				
			while ($females = @readdir($browse_f)) 
			{			
				if ( ($females != '.') && ($females != '..') && ($females != 'index.htm') && ($females != 'index.html') )
				{
					$seperate 	= explode('.', $females);
					$display	= str_replace('_', ' ', $seperate[0]);
					$value		= $seperate[0] .'.'. $seperate[1];

					$template->assign_block_vars('edit_char.edit.females', array(
						'NAMES'		=> ucwords(strtolower($display)),
						'VALUES'	=> $value)
					);					
				}
						
				if ( ($females != '.') && ($females != '..') && ($females != 'index.htm') && ($females != 'index.html') && ($stop == 0) )
				{
					$template->assign_block_vars('edit_char.edit.fdefault', array(
						'VALUE'	=> $females)
					);										
					$stop++;
				}				
			}	
			@closedir($f_dir);
		}
	}
	else
	{
		$template->assign_block_vars('edit_char.edit.on', array());			
		
		#==== Gather Male Character Options
		$m_dir 		= $phpbb_root_path .'images/activity/characters/male/';
		$browse_m 	= @opendir($m_dir);
		$stop 		= 0;
				
		while ($males = @readdir($browse_m)) 
		{			
			if ( ($males != '.') && ($males != '..') && ($males != 'index.htm') && ($males != 'index.html') )
			{
				$seperate 	= explode('.', $males);
				$display	= str_replace('_', ' ', $seperate[0]);
				$value		= $seperate[0] .'.'. $seperate[1];
				
				$template->assign_block_vars('edit_char.edit.males', array(
					'NAMES'		=> ucwords(strtolower($display)),
					'VALUES'	=> $value)
				);					
			}
						
			if ( ($males != '.') && ($males != '..') && ($males != 'index.htm') && ($males != 'index.html') && ($stop == 0) )
			{
				$template->assign_block_vars('edit_char.edit.mdefault', array(
					'VALUE'	=> $males)
				);										
				$stop++;
			}									
		}			
		@closedir($m_dir);
					
		#==== Gather Female Character Options	
		$f_dir 		= $phpbb_root_path .'images/activity/characters/female/';
		$browse_f 	= @opendir($f_dir);
		$stop		= 0;
			
		while ($females = @readdir($browse_f)) 
		{			
			if ( ($females != '.') && ($females != '..') && ($females != 'index.htm') && ($females != 'index.html') )
			{
				$seperate 	= explode('.', $females);
				$display	= str_replace('_', ' ', $seperate[0]);
				$value		= $seperate[0] .'.'. $seperate[1];
			
				$template->assign_block_vars('edit_char.edit.females', array(
					'NAMES'		=> ucwords(strtolower($display)),
					'VALUES'	=> $value)
				);					
			}
					
			if ( ($females != '.') && ($females != '..') && ($females != 'index.htm') && ($females != 'index.html') && ($stop == 0) )
			{
				$template->assign_block_vars('edit_char.edit.fdefault', array(
					'VALUE'	=> $females)
				);										
				$stop++;
			}				
		}	
		@closedir($f_dir);
						
	}
				
	#==== Change gender
	$male 	= ($userdata['ina_char_gender'] == 1) ? 'checked = "checked"' : '';
	$female = ($userdata['ina_char_gender'] == 2) ? 'checked = "checked"' : '';	

	if ($board_config['ina_char_change_gender_cost'])
	{			
		if ($board_config['ina_char_change_gender_cost'] > $userdata['ina_char_ge'])
		{
			$change_gender_return = $lang['amp_char_cp_not_enough_ge'];
		}
		else
		{
			$change_gender_return = '<input type="radio" name="gender" value="1" '. $male .'> '. $lang['Male'] .'&nbsp;&nbsp;<input type="radio" name="gender" value="2" '. $female .'> '. $lang['Female'];
		}
	}
	else
	{
		$change_gender_return = '<input type="radio" name="gender" value="1" '. $male .'> '. $lang['Male'] .'&nbsp;&nbsp;<input type="radio" name="gender" value="2" '. $female .'> '. $lang['Female'];
	}	
	
	#==== Change age
	if ($board_config['ina_char_change_age_cost'])
	{
		if ($board_config['ina_char_change_age_cost'] > $userdata['ina_char_ge'])
		{
			$change_age_return = $lang['amp_char_cp_not_enough_ge'];
		}
		else
		{
			$change_age_return = '<input type="text" name="age" value="'. $userdata['ina_char_age'] .'" class="post" size="30" />';
		}
	}
	else
	{
		$change_age_return = '<input type="text" name="age" value="'. $userdata['ina_char_age'] .'" class="post" size="30" />';			
	}	
	
	#==== Change name
	if ($board_config['ina_char_change_name_cost'])
	{
		if ($board_config['ina_char_change_char_cost'] > $userdata['ina_char_ge'])
		{
			$change_name_return = $lang['amp_char_cp_not_enough_ge'];
		}
		else
		{
			$change_name_return = '<input type="text" name="name" value="'. $userdata['ina_char_name'] .'" class="post" size="30" />';
		}
	}
	else
	{
		$change_name_return = '<input type="text" name="name" value="'. $userdata['ina_char_name'] .'" class="post" size="30" />';			
	}	
	
	#==== Change from
	if ($board_config['ina_char_change_from_cost'])
	{
		if ($board_config['ina_char_change_from_cost'] > $userdata['ina_char_ge'])
		{
			$change_from_return = $lang['amp_char_cp_not_enough_ge'];
		}
		else
		{
			$change_from_return = '<input type="text" name="from" value="'. $userdata['ina_char_from'] .'" class="post" size="30" />';
		}
	}
	else
	{
		$change_from_return = '<input type="text" name="from" value="'. $userdata['ina_char_from'] .'" class="post" size="30" />';			
	}	
	
	#==== Change intrests
	if ($board_config['ina_char_change_intrests_cost'])
	{
		if ($board_config['ina_char_change_intrests_cost'] > $userdata['ina_char_ge'])
		{
			$change_intrests_return = $lang['amp_char_cp_not_enough_ge'];
		}
		else
		{
			$change_intrests_return = '<input type="text" name="intrests" value="'. $userdata['ina_char_intrests'] .'" class="post" size="30" />';
		}
	}
	else
	{
		$change_intrests_return = '<input type="text" name="intrests" value="'. $userdata['ina_char_intrests'] .'" class="post" size="30" />';			
	}
	
	#==== Change saying
	if ($board_config['ina_char_change_saying_cost'])
	{
		if ($board_config['ina_char_change_saying_cost'] > $userdata['ina_char_ge'])
		{
			$change_saying_return = $lang['amp_char_cp_not_enough_ge'];
		}
		else
		{
			$change_saying_return = '<input type="text" name="saying" value="'. $userdata['ina_char_saying'] .'" class="post" size="30" />';
		}
	}
	else
	{
		$change_saying_return = '<input type="text" name="saying" value="'. $userdata['ina_char_saying'] .'" class="post" size="30" />';			
	}
		
	#==== Change title
	if ($board_config['ina_char_change_title_cost'])
	{
		if ($board_config['ina_char_change_title_cost'] > $userdata['ina_char_ge'])
		{
			$change_title_return = $lang['amp_char_cp_not_enough_ge'];
		}
		else
		{
			$change_title_return = '<input type="text" name="title" value="'. $userdata['ina_char_title'] .'" class="post" size="30">';
		}
	}
	else
	{
		$change_title_return = '<input type="text" name="title" value="'. $userdata['ina_char_title'] .'" class="post" size="30">';			
	}						
				
	$template->assign_block_vars('edit_char.edit.values', array(
		'CHR_CHNG_NAME'		=> $change_name_return,
		'CHR_CHNG_AGE'		=> $change_age_return,
		'CHR_CHNG_INTRESTS'	=> $change_intrests_return,
		'CHR_CHNG_FROM'		=> $change_from_return,
		'CHR_CHNG_GENDER'	=> $change_gender_return,
		'CHR_CHNG_CHAR'		=> $change_char_return,
		'CHR_CHNG_TITLE'	=> $change_title_return,
		'CHR_CHNG_SAYING'	=> $change_saying_return)
	);
}
		
$template->assign_vars(array(
	'CHAR_OPTIONS'			=> $lang['amp_char_cp_chars'],
	'CHAR_OPTIONS_M'		=> $lang['amp_char_cp_gender_m'],
	'CHAR_OPTIONS_F'		=> $lang['amp_char_cp_gender_f'],
	'CHAR_GENDER'			=> $lang['amp_char_cp_gender'],
	'CHAR_GENDER_M'			=> $lang['Male'],
	'CHAR_GENDER_F'			=> $lang['Female'],
	'CHAR_AGE'				=> $lang['amp_char_cp_age'],
	'CHAR_AGE_EXP'			=> $lang['amp_char_cp_age_exp'],
	'CHAR_INTRESTS'			=> $lang['amp_char_cp_intrests'],
	'CHAR_FROM'				=> $lang['amp_char_cp_from'],
	'CHAR_NAME'				=> $lang['amp_char_cp_name'],
	'CHAR_GE'				=> $lang['amp_char_cp_ge'],
	'CHAR_GE_LEVEL'			=> $lang['amp_char_cp_ge_level'],
	'CHAR_GAME_PLAYS'		=> $lang['amp_char_total_games'],
	'CHAR_LAST_GAME'		=> $lang['amp_char_last_played'],
	'CHAR_CHANGE_CHECK'		=> $lang['amp_char_cp_did_change_char'],
	'CHAR_CHANGE_CHECK_Y'	=> $lang['Yes'],
	'CHAR_CHANGE_CHECK_N'	=> $lang['No'],
	'CHAR_TITLE'			=> $lang['amp_char_cp_title'],
	'CHAR_SAYING'			=> $lang['amp_char_cp_saying'],				
	'L_DELETE'				=> $lang['Delete'],
	'CHAR_CREATE_LINK'		=> ( ($userdata['ina_char_name']) && ($userdata['user_id'] != ANONYMOUS) ) ? '' : ' -> <a href="' . append_sid('activity_char.'.$phpEx.'?mode=create_char') . '" class="nav">'. $lang['amp_char_create_char_link'] .'</a>',
	'CHAR_SHOP_LINK'		=> ( ($userdata['ina_char_name']) && ($userdata['user_id'] != ANONYMOUS) ) ? ' -> <a href="' . append_sid('activity_char.'.$phpEx.'?mode=char_shop') . '" class="nav">'. $lang['amp_char_goto_shop_link'] .'</a>' : '',

	'S_SUBMIT' 				=> append_sid('activity_char.'.$phpEx),
	
	'CHAR_SUBMIT'			=> $lang['amp_char_cp_submit'],
	'CHAR_SAVE'				=> $lang['amp_char_cp_save_char'])
);
			
//
// Generate page
//
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>