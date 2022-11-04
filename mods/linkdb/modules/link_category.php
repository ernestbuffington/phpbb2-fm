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
 *                            link_category.php
 *                           -------------------
 *   Modified by CRLin
 ***************************************************************************/
 
class linkdb_category extends linkdb_public
{
	function main($action)
	{
		global $template, $lang, $phpEx, $linkdb_config, $_REQUEST;

		//
		// Get the id
		//

		if ( isset($_REQUEST['cat_id']))
		{
			$cat_id = intval($_REQUEST['cat_id']);
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['Cat_not_exist']);
		}

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

		if(!isset($this->cat_rowset[$cat_id]))
		{
			message_die(GENERAL_MESSAGE, $lang['Cat_not_exist']);	
		}

		//
		// assign var for naviagation
		//
		$this->generate_category_nav($cat_id);

		$template->assign_vars(array(
			'L_INDEX' => sprintf($lang['Forum_Index'], $board_config['sitename']),

			'U_INDEX' => append_sid('index.'.$phpEx),
			'U_LINK' => append_sid('linkdb.'.$phpEx),

			'LINKS' => $lang['Linkdb'])
		);

		$no_file_message = TRUE;

		$filelist = FALSE;

		if (isset($this->subcat_rowset[$cat_id])) 
		{
			$no_file_message = FALSE;

			$this->category_display($cat_id);
		}
		
		global $custom_field;
		include($phpbb_root_path . 'mods/linkdb/includes/functions_field.'.$phpEx);
		$custom_field = new custom_field();
		$custom_field->init();

		$this->display_files($sort_method, $sort_order, $start, $no_file_message, $cat_id);

		$this->display($lang['Linkdb'], 'link_category_body.tpl');
	}
}
?>
