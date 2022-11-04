<?php
/*
  paFileDB 3.0
  ©2001/2002 PHP Arena
  Written by Todd
  todd@phparena.net
  http://www.phparena.net
  Keep all copyright links on the script visible
  Please read the license included with this script for more information.
*/

/***************************************************************************
 *                            link_viewall.php
 *                           ------------------
 *   Modified by CRLin
 ***************************************************************************/

class linkdb_viewall extends linkdb_public
{
	function main($action)
	{
		global $template, $lang, $phpEx, $linkdb_config, $_REQUEST, $userdata;
		
		$start = ( isset($_REQUEST['start']) ) ? intval($_REQUEST['start']) : 0;

		if( isset($_REQUEST['sort_method']) )
		{
			switch ($_REQUEST['sort_method'])
			{
				case 'link_name':
					$sort_method = 'link_name';
					break;
				case 'link_time':
					$sort_method = 'link_time';
					break;
				case 'link_hits':
					$sort_method = 'link_hits';
					break;
				case 'link_longdesc':
					$sort_method = 'link_longdesc';
					break;
				default:
					$sort_method = $linkdb_config['sort_method'];
			}
		}
		else
		{
			$sort_method = $linkdb_config['sort_method'];
		}

		if( isset($_REQUEST['sort_order']) )
		{
			switch ($_REQUEST['sort_order'])
			{
				case 'ASC':
					$sort_order = 'ASC';
					break;
				case 'DESC':
					$sort_order = 'DESC';
					break;
				default:
					$sort_order = $linkdb_config['sort_order'];
			}
		}
		else
		{
			$sort_order = $linkdb_config['sort_order'];
		}
		
		$template->assign_vars(array(
			'L_VIEWALL' => $lang['Viewall'],	
			'L_INDEX' => sprintf($lang['Forum_Index'], $board_config['sitename']),

			'U_INDEX' => append_sid('index.'.$phpEx),
			'U_LINK' => append_sid('linkdb.'.$phpEx),

			'LINKS' => $lang['Linkdb'])
		);

		global $custom_field;
		include($phpbb_root_path . 'mods/linkdb/includes/functions_field.'.$phpEx);
		$custom_field = new custom_field();
		$custom_field->init();

		$this->display_files($sort_method, $sort_order, $start, TRUE);

		$this->display($lang['Linkdb'] . ' :: ' . $lang['Viewall'], 'link_viewall_body.tpl');
	}
}

?>
