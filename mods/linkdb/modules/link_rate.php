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
 *                            link_rate.php
 *                           ---------------
 *   Modified by CRLin
 ***************************************************************************/

class linkdb_rate extends linkdb_public
{
	function main($action)
	{
		global $template, $lang, $board_config, $phpEx, $linkdb_config, $db, $userdata;
		global $_REQUEST, $_POST, $phpbb_root_path, $linkdb_functions;
		
		if(!$linkdb_config['allow_vote'])
		{
			$message = $lang['Not_allow_vote'];
			$message .= '<br /><br />' . sprintf($lang['Click_return'], '<a href="javascript:history.go(-1)">', '</a>');
	
			$template->assign_vars(array(
				'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("linkdb.$phpEx") . '">'
			));

			message_die(GENERAL_MESSAGE, $message);
		}
		
		if ( isset($_REQUEST['link_id']) )
		{
			$link_id = intval($_REQUEST['link_id']);
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['Link_not_exist']);
		}

		if ( !$userdata['session_logged_in'] )
		{
			$redirect = "linkdb.$phpEx&action=rate&link_id=$link_id";
			redirect(append_sid("login.$phpEx?redirect=" . $redirect, true));	
		}
		else
		{
			$sql = "SELECT *
				FROM ". LINK_VOTES_TABLE ."
				WHERE votes_link = '$link_id'
					AND user_id = '". $userdata['user_id'] ."'
				LIMIT 1";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not query rating information', '', __LINE__, __FILE__, $sql);
			}

			if ($db->sql_numrows($result) > 0)
			{
				$already_rated = TRUE;
			}
			else
			{
				$already_rated = FALSE;
			}
		}
		
		$rating = ( isset($_POST['rating']) ) ? intval($_POST['rating']) : '';

		$sql = 'SELECT f1.*, AVG(r.rate_point) AS rating, COUNT(r.votes_link) AS total_votes
			FROM ' . LINKS_TABLE . " AS f1
				LEFT JOIN " . LINK_VOTES_TABLE . " AS r ON f1.link_id = r.votes_link
			WHERE link_id = $link_id
			GROUP BY f1.link_id ";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Couldnt Query link info', '', __LINE__, __FILE__, $sql);
		}

		if(!$link_data = $db->sql_fetchrow($result))
		{
			message_die(GENERAL_MESSAGE, $lang['Link_not_exist']);
		}
		
		$db->sql_freeresult($result);

		$this->generate_category_nav($link_data['link_catid']);
		$template->assign_vars(array(
			'L_INDEX' => sprintf($lang['Forum_Index'], $board_config['sitename']),

			'U_INDEX' => append_sid('index.'.$phpEx),
			'U_LINK' => append_sid('linkdb.'.$phpEx),
			'U_FILE_NAME' => append_sid('linkdb.'.$phpEx.'?action=link&amp;link_id=' . $link_id),

			'FILE_NAME' => $link_data['link_name'],
			'LINK_LOGO' => $this->display_banner($link_data, $link_data),
			'L_VOTES' => $lang['Votes'],
			'FILE_VOTES' => $link_data['total_votes'],
			'L_RATING' => $lang['LinkRating'],
			'RATING' => ($link_data['rating'] != 0) ? round($link_data['rating'], 2) . '/10' : $lang['Not_rated'],
			'LINKS' => $lang['Linkdb'])
		); 

		if ( isset($_POST['submit']) )
		{
			$result_msg = str_replace("{filename}", $link_data['link_name'], $lang['Rconf']);

			$result_msg = str_replace("{rate}", $rating, $result_msg);
			
			if( ($rating <= 0) or ($rating > 10) )
			{
				message_die(GENERAL_ERROR, 'Bad submited value');
			}
			
			$linkdb_functions->update_voter_info($link_id, $rating);

			$rate_info = $linkdb_functions->get_rating($link_id);

			$result_msg = str_replace("{newrating}", $rate_info, $result_msg);
			
			$message = $result_msg . '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid('linkdb.'.$phpEx.'?action=category&cat_id=' . $link_data['link_catid']) . '">', '</a>');

			$template->assign_vars(array( 
				'META' => '<meta http-equiv="refresh" content="3;url=' .  "linkdb.$phpEx?action=category&cat_id=" . $link_data['link_catid'] . '">')
			);

			message_die(GENERAL_MESSAGE, $message);  

		}
		else
		{
			$rate_info = str_replace("{filename}", $link_data['link_name'], $lang['Rateinfo']);
			
			$template->assign_vars(array(
				'L_RATE' => ($already_rated) ? $lang['Rerror'] : $lang['Rate'],
				'RATEINFO' => $rate_info,
				'S_RATE_ACTION' => append_sid('linkdb.'.$phpEx.'?action=rate&amp;link_id=' . $link_id),
				'ID' => $link_id) 
			);

			if (!$already_rated)
			{
				for ($i = 0; $i < 10; $i++)
				{
					$template->assign_block_vars('rate_row', array(
						'POINT' => ($i + 1))
					);
				}
			}
		}
		$this->display($lang['Linkdb'], 'link_rate_body.tpl');
	}
}
?>