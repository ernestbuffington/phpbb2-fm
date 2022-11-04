<?php 
/***************************************************************************
 *                               index_weather.php
 *                            -------------------
 *   begin                : Saturday, Sep 4, 2001
 *   copyright            : (C) 2004, 2005 Fully Modded MODS
 *   email                : mj@phpbb-fm.com
 *
 *   $Id: index_weather.php,v 1.0.0 2/12/2005 6:38 PM mj Exp $
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

define('IN_PHPBB', true); 
$phpbb_root_path = './'; 
include($phpbb_root_path . 'extension.inc'); 
include($phpbb_root_path . 'common.'.$phpEx); 
require($phpbb_root_path . 'mods/weather/weather.'.$phpEx);

// 
// Start session management 
// 
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata); 
// 
// End session management 
// 

// 
// Lets build a page ... 
// 
$template->set_filenames(array( 
	'body' => 'index_weather_body.tpl') 
); 

$zip_code = $userdata['user_zipcode'];

$template->assign_vars(array( 
	'T_BODY_BGCOLOR' => '#'.$theme['td_color1'],
	'T_TR_COLOR1' => '#'.$theme['tr_color1'],
	'T_TR_COLOR2' => '#'.$theme['tr_color2'],
	'T_TR_COLOR3' => '#'.$theme['tr_color3'],
	'T_TD_COLOR2' => '#'.$theme['td_color2'],
	'T_BODY_LINK' => '#'.$theme['body_link'],
	'T_BODY_HLINK' => '#'.$theme['body_hlink'],
	'T_BODY_TEXT' => '#'.$theme['body_text'],
	'T_FONTCOLOR3' => '#'.$theme['fontcolor3'],
	'T_FONTSIZE1' => '#'.$theme['fontsize1'],
	'T_TH_COLOR1' => '#'.$theme['th_color1'],

	'ZIPCODE' => $zip_code,
	'MY_WEATHER' => weather($zip_code))
); 

$template->pparse('body'); 

?>