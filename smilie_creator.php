<?php
/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX, $session_length);
init_userprefs($userdata);
//
// End session management
//

if ( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
}
else
{
	$mode = '';
}

if( $mode == 'text2schild')
{
	$anz_smilie = -1;
	$hdl = @opendir('images/smiles/schild/');
	while($res = @readdir($hdl))
	{
		if(strtolower(substr($res, (strlen($res) - 3), 3)) == "png") $anz_smilie++;
	}
	@closedir($hdl);

	$i = 1;
	$ii = 1;
	while($i <= $anz_smilie)
	{
		$smilies_wahl .= "<td><input type=\"radio\" name=\"smilie\" value=\"".$i."\"><img src=\"images/smiles/schild/smilie".$i.".png\"></td>";
		$smilies_js .= "	if(document.schilderstellung.smilie[".($i-1)."].checked) var smilie = document.schilderstellung.smilie[".($i-1)."].value;\n";
		if($ii >= 5)
		{
			$smilies_wahl .= "</tr><tr>";
			$ii = 0;
		}
		$i++;
		$ii++;
	}

	$smilies_js .= "	if(document.schilderstellung.smilie[".($i-1)."].checked) var smilie = document.schilderstellung.smilie[".($i-1)."].value;\n";
	$smilies_js .= "	if(document.schilderstellung.smilie[".$i."].checked) var smilie = document.schilderstellung.smilie[".$i."].value;\n";
}

//
// Generate page
//
$page_title = $lang['Smilie_creator'];
$gen_simple_header = 1;
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
   'body' => 'smilie_creator.tpl',
   'jumpbox' => 'jumpbox.tpl')
);

$jumpbox = make_jumpbox($forum_id);

$template->assign_vars(array(
    'L_GO' => $lang['Go'],
    'SMILIES_WAHL' => $smilies_wahl,
    'SMILIES_JS' => $smilies_js,
    'L_SMILIE_CREATOR' => $lang['Smilie_creator'],
    'L_CREATE_SMILIE' => $lang['SC_create_smilie'],
    'L_STOP_CREATING' => $lang['SC_stop_creating'],
    'L_SHIELDSHADOW_ON' => $lang['SC_shieldshadow_on'],
    'L_SHIELDSHADOW_OFF' => $lang['SC_shieldshadow_off'],
    'L_SHIELDTEXT' => $lang['SC_shieldtext'],
    'L_SHADOWCOLOR' => $lang['SC_shadowcolor'],
    'L_SHIELDSHADOW' => $lang['SC_shieldshadow'],
    'L_SMILIECHOOSER' => $lang['SC_smiliechooser'],
    'L_RANDOM_SMILIE' => $lang['SC_random_smilie'],
    'L_DEFAULT_SMILIE' => $lang['SC_default_smilie'],

    'L_FONTCOLOR' => $lang['SC_fontcolor'],
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
	'L_COLOR_CADET_BLUE' => $lang['color_cadet_blue'],
	'L_COLOR_CORAL' => $lang['color_coral'], 
	'L_COLOR_CRIMSON' => $lang['color_crimson'], 
	'L_COLOR_TOMATO' => $lang['color_tomato'], 
	'L_COLOR_SEA_GREEN' => $lang['color_sea_green'], 
	'L_COLOR_DARK_ORCHID' => $lang['color_dark_orchid'], 
	'L_COLOR_CHOCOLATE' => $lang['color_chocolate'],
	'L_COLOR_DEEPSKYBLUE' => $lang['color_deepskyblue'], 
	'L_COLOR_GOLD' => $lang['color_gold'], 
	'L_COLOR_GRAY' => $lang['color_gray'], 
	'L_COLOR_MIDNIGHTBLUE' => $lang['color_midnightblue'], 
	'L_COLOR_DARKGREEN' => $lang['color_darkgreen'], 

    'L_JUMP_TO' => $lang['Jump_to'],
    'L_SELECT_FORUM' => $lang['Select_forum'],

    'S_JUMPBOX_LIST' => $jumpbox,
    'S_JUMPBOX_ACTION' => append_sid('viewforum.'.$phpEx))
);

$template->assign_var_from_handle('JUMPBOX', 'jumpbox');

$template->pparse('body');

?>