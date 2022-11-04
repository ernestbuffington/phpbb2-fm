<?php
/*
  paFileDB 3.0
  ©2001/2002 PHP Arena
  Written by Todd
  todd@phparena.net
  http://www.phparena.net
  Keep all copyright links on the script visible
  Sub category counting bug fix by Kron
  Please read the license included with this script for more information.
*/

/***************************************************************************
 *                            link_main.php
 *                           ---------------
 *   Modified by CRLin
 ***************************************************************************/

class linkdb_main extends linkdb_public
{
	function main($action)
	{
		global $template, $lang, $board_config, $phpEx, $linkdb_config;
		//
		// assign var for naviagation
		//
		$template->assign_vars(array(
			'L_INDEX' => $lang['Forum_Index'],
			'U_INDEX' => append_sid('index.'.$phpEx),
			'U_LINK' => append_sid('linkdb.'.$phpEx),
			'LINKS' => $lang['Linkdb'],
			'TREE' => $menu_output,
			'L_LINK_US' => $lang['Link_us'],
			'U_SITE_LOGO' => $linkdb_config['site_logo'],
			'L_LINK_US_EXPLAIN' => sprintf($lang['Link_us_explain'], $board_config['sitename']),
			'LINK_US_SYNTAX' => str_replace(' ', '&nbsp;', sprintf(htmlentities($lang['Link_us_syntax'], ENT_QUOTES), $linkdb_config['site_url'], real_path($linkdb_config['site_logo']), $linkdb_config['width'], $linkdb_config['height'], $board_config['sitename'], $board_config['sitename']))
//			'LINK_US_SYNTAX' => str_replace(' ', '&nbsp;', sprintf(htmlentities($lang['Link_us_syntax'], ENT_QUOTES), $linkdb_config['site_url'], real_path($linkdb_config['site_logo']), $linkdb_config['width'], $linkdb_config['height'], $board_config['sitename']))
		)); 

		//
		// Show the Category
		//
		$this->category_display();

		$this->display($lang['Linkdb'], 'link_main_body.tpl');
	}
}

?>