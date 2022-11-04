<?php
/** 
*
* @package album
* @version $Id: clown_album_functions.php,v 1.99.2.3 2004/01/18 16:46:15 acydburn Exp $
* @copyright (c) 2003 Volodymyr (CLowN) Skoryk
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

function ImageRating($rating)
{
//Pre: returns what type of rating style to display 

	global $db, $album_config, $lang, $images;
	
	//deside how user wants to show their rating
	//
	if ($album_config['rate_type'] == 0)//display only images
	{
		if (!$rating)
			return('<i>' . $lang['Not_rated'] . '</i>');
		else
		{
			$r = "";
			for ($temp = 1; $temp <= $rating; $temp++)
			{
				$r .= '<img src="' . $images['icon_album_rank'] . '" alt="" title="" />&nbsp;';
			}
			
			return ($r);
		}
	}	
	else if ($album_config['rate_type'] == 1) //display just text
	{
		if (!$rating)
			return('<i>' . $lang['Not_rated'] . '</i>');
		else
			return (round($rating, 2));
	}
	else //display both images and text
	{
		if (!$rating)
			return('<i>' . $lang['Not_rated'] . '</i>');
		else
		{
			$r = "";
			for ($temp = 1; $temp <= $rating; $temp++)
			{
				$r .= '<img src="' . $images['icon_album_rank'] . '" alt="" title="" />&nbsp;';
			}
		}
		
		return (round($rating, 2) .  '&nbsp;' . $r);
	}
}

//to have smilies window popup
function generate_smilies($mode, $page_id) //borrowed from phpbbforums...modified to work with album
{
	global $db, $board_config, $template, $lang, $images, $theme, $phpEx, $phpbb_root_path;
	global $user_ip, $session_length, $starttime;
	global $userdata;

	$inline_columns = 4;
	$inline_rows = 5;
	$window_columns = 8;

	if ($mode == 'window')
	{
		$userdata = session_pagestart($user_ip, $page_id);
		init_userprefs($userdata);

		$gen_simple_header = TRUE;

		$page_title = $lang['Emoticons'];
		include($phpbb_root_path . 'includes/page_header.'.$phpEx);

		$template->set_filenames(array(
			'smiliesbody' => 'album_posting_smilies.tpl')
		);
	}

	$sql = "SELECT emoticon, code, smile_url   
		FROM " . SMILIES_TABLE . " 
		ORDER BY smilies_id";
	if ($result = $db->sql_query($sql))
	{
		$num_smilies = 0;
		$rowset = array();
		while ($row = $db->sql_fetchrow($result))
		{
			if (empty($rowset[$row['smile_url']]))
			{
				$rowset[$row['smile_url']]['code'] = str_replace("'", "\\'", str_replace('\\', '\\\\', $row['code']));
				$rowset[$row['smile_url']]['emoticon'] = $row['emoticon'];
				$num_smilies++;
			}
		}

		if ($num_smilies)
		{
			$smilies_count = ($mode == 'inline') ? min(19, $num_smilies) : $num_smilies;
			$smilies_split_row = ($mode == 'inline') ? $inline_columns - 1 : $window_columns - 1;

			$s_colspan = 0;
			$row = 0;
			$col = 0;

			while (list($smile_url, $data) = @each($rowset))
			{
				if (!$col)
				{
					$template->assign_block_vars('smilies_row', array());
				}

				$template->assign_block_vars('smilies_row.smilies_col', array(
					'SMILEY_CODE' => $data['code'],
					'SMILEY_IMG' => $board_config['smilies_path'] . '/' . $smile_url,
					'SMILEY_DESC' => $data['emoticon'])
				);

				$s_colspan = max($s_colspan, $col + 1);

				if ($col == $smilies_split_row)
				{
					if ($mode == 'inline' && $row == $inline_rows - 1)
					{
						break;
					}
					$col = 0;
					$row++;
				}
				else
				{
					$col++;
				}
			}

			if ($mode == 'inline' && $num_smilies > $inline_rows * $inline_columns)
			{
				$template->assign_block_vars('switch_smilies_extra', array());

				$template->assign_vars(array(
					'L_MORE_SMILIES' => $lang['More_emoticons'], 
					'U_MORE_SMILIES' => append_sid("posting.$phpEx?mode=smilies"))
				);
			}

			$template->assign_vars(array(
				'L_EMOTICONS' => $lang['Emoticons'], 
				'L_CLOSE_WINDOW' => $lang['Close_window'], 
				'S_SMILIES_COLSPAN' => $s_colspan)
			);
		}
	}

	if ($mode == 'window')
	{
		$template->pparse('smiliesbody');

		include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
	}
}

function CanRated ($picID, $userID)
{
//PRE: deside if user can rate things on hot or not
	global $db, $album_config, $userdata;
	
	if (! $userdata['session_logged_in'] && $album_config['hon_rate_users'] == 1)
	{
		$alowed = true;
	}
	else if ($userdata['session_logged_in'] && $album_config['hon_rate_times'] == 0)
	{
		$sql = "SELECT *
					FROM ". ALBUM_RATE_TABLE ."
					WHERE rate_pic_id = $picID
						AND rate_user_id = $userID
					LIMIT 1";
 
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, 'Could not query rating information', '', __LINE__, __FILE__, $sql);
		}

		if ($db->sql_numrows($result) > 0)
		{
			$alowed = false;			
		}
		else
		{
			$alowed =  true;	
		}
	}
	else
	{
		$alowed = true;
	}
	
	return ($alowed);
}

?>