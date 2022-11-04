<?php
/** 
*
* @package admin
* @version $Id: admin_charts.php,v 1.51.2.9 2004/11/18 17:49:33 mj Exp $
* @copyright (c) 2003 dzidzius
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
   $file = basename(__FILE__);
   $module['General']['Charts'] = $file;
   return;
}

//
// Load default header
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

//
// Include language files
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_charts.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_charts.' . $phpEx);

if( isset($HTTP_GET_VARS['action']) || isset($HTTP_POST_VARS['action']) )
{
	$mode = ($HTTP_GET_VARS['action']) ? htmlspecialchars($HTTP_GET_VARS['action']) : htmlspecialchars($HTTP_POST_VARS['action']);
}
else
{
	if( isset($HTTP_POST_VARS['end_week']) )
	{
		$mode = 'end_week';
	}
	else if( isset($HTTP_POST_VARS['save']) )
	{
		$mode = 'save';
	}
	else if( isset($HTTP_POST_VARS['reset']) )
	{
		$mode = 'reset';
	}
	else
	{
		$mode = '';
	}
}

if ( $mode != '' )
{
	if ( $mode == 'edit' )
	{
		$chart_id = ( isset($HTTP_GET_VARS['id']) ) ? intval($HTTP_GET_VARS['id']) : 0;

		$template->set_filenames(array(
			'body' => 'admin/charts_edit_body.tpl')
		);

		$s_hidden_fields = '';

		if ( $mode == 'edit' )
		{
			if ( $chart_id )
			{
				$sql = "SELECT *
					FROM " . CHARTS_TABLE . "
					WHERE chart_id = $chart_id";
				if(!$result = $db->sql_query($sql))
				{
					message_die(GENERAL_ERROR, $lang['Chart_Sql_Error'], $lang['Error'], __LINE__, __FILE__, $sql);
				}

				$chart_info = $db->sql_fetchrow($result);
				$s_hidden_fields .= '<input type="hidden" name="id" value="' . $chart_id . '" />';
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['Chart_Choose_Err']);
			}
		}

		$template->assign_vars(array(
			'L_PAGE_DESC' => $lang['Chart_Page_Desc'],
			'L_PAGE_TITLE' => $lang['Chart'] . ' ' . $lang['Manage'],
			'L_ADDING_TITLE' => $lang['Chart_Edit'],
			'L_ITEMS_REQUIRED' => $lang['Items_required'],
			'L_ARTIST' => $lang['Chart_Artist'],
			'L_TITLE' => $lang['Chart_Title'],
			'L_ALBUM' => $lang['Chart_Album'],
			'L_LABEL' => $lang['Chart_Label'],
			'L_CAT_NO' => $lang['Chart_Cat_No'],
			'L_WEBSITE' => $lang['Website'],

			'SONG_NAME' => $chart_info['chart_song_name'],
			'ALBUM_NAME' => $chart_info['chart_album'],
			'ARTIST_NAME' => $chart_info['chart_artist'],
			'LABEL_NAME' => $chart_info['chart_label'],
			'CAT_NO_NAME' => $chart_info['chart_catno'],
			'WEBSITE_NAME' => $chart_info['chart_website'],

			'S_CHARTS_ACTION' => append_sid('admin_charts.'.$phpEx),
			'S_HIDDEN_FIELDS' => $s_hidden_fields)
		);

		$template->pparse('body');

		include('./page_footer_admin.'.$phpEx);
	}
	else if ( $mode == 'save' )
	{
		$chart_id = ( isset($HTTP_POST_VARS['id']) ) ? intval($HTTP_POST_VARS['id']) : 0;
        $song_name = ( isset($HTTP_POST_VARS['song_name']) ) ? trim($HTTP_POST_VARS['song_name']) : '';
        $artist_name = ( isset($HTTP_POST_VARS['artist_name']) ) ? trim($HTTP_POST_VARS['artist_name']) : '';
        $album_name = ( isset($HTTP_POST_VARS['album_name']) ) ? trim($HTTP_POST_VARS['album_name']) : '';
        $label_name = ( isset($HTTP_POST_VARS['label_name']) ) ? trim($HTTP_POST_VARS['label_name']) : '';
        $catno_name = ( isset($HTTP_POST_VARS['catno_name']) ) ? trim($HTTP_POST_VARS['catno_name']) : '';
        $website_name = ( isset($HTTP_POST_VARS['website_name']) ) ? trim($HTTP_POST_VARS['website_name']) : '';

		if ($song_name == '' || $artist_name == '' )
		{
			message_die(GENERAL_MESSAGE, $lang['Chart_Fields_Err']);
		}

		if ( $chart_id )
		{
			$sql = "UPDATE " . CHARTS_TABLE . "
				SET chart_song_name = '" . str_replace("\'", "''", $song_name) . "', chart_artist = '" . str_replace("\'", "''", $artist_name) . "', chart_album = '" . str_replace("\'", "''", $album_name) . "', chart_label = '" . str_replace("\'", "''", $label_name) . "', chart_catno = '" . str_replace("\'", "''", $catno_name) . "', chart_website = '" . str_replace("\'", "''", $website_name) . "' 
				WHERE chart_id = $chart_id";
			$message = $lang['Chart_DBase_Ok'];
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['Chart_Choose_Err']);
		}

		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, $lang['Chart_Sql_Base_Error'], $lang['Error'], __LINE__, __FILE__, $sql);
		}

		$message .= '<br /><br />' . sprintf($lang['Chart_Click_Return'], '<a href="' . append_sid('admin_charts.'.$phpEx) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.'.$phpEx) . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
	}
	else if( $mode == 'delete' )
	{
		if( isset($HTTP_POST_VARS['id']) || isset($HTTP_GET_VARS['id']) )
		{
			$chart_id = ( isset($HTTP_POST_VARS['id']) ) ? intval($HTTP_POST_VARS['id']) : intval($HTTP_GET_VARS['id']);
		}
		else
		{
			$chart_id = 0;
		}

		if( $chart_id )
		{
			$sql = "DELETE FROM " . CHARTS_VOTERS_TABLE . "
				WHERE vote_chart_id = $chart_id";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, $lang['Chart_SQL_Del'], $lang['Error'], __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . CHARTS_TABLE . "
				WHERE chart_id = $chart_id";
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, $lang['Chart_SQL_Del'], $lang['Error'], __LINE__, __FILE__, $sql);
			}

			$message = $lang['Chart_Del_Ok'] . "<br /><br />" . sprintf($lang['Chart_Click_Return'], "<a href=\"" . append_sid("admin_charts.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['Chart_Choose_Err']);
		}
	}
	else if( $mode == 'end_week' )
	{
		message_die(GENERAL_MESSAGE, '<form method="post" action="' . append_sid('admin_charts.'.$phpEx) . '">' . $lang['Chart_Ask_Week'] . '<br /><br /><input type="submit" name="reset" value="' . $lang['Yes'] . '" class="mainoption" />&nbsp;&nbsp;<input type="submit" name="" value="' . $lang['No'] . '" class="liteoption" /></form>');
	}
	else if( $mode == 'reset' )
    {
    	$sql = "SELECT * 
    		FROM " . CHARTS_TABLE . " 
        	ORDER BY (chart_hot-chart_not) DESC, chart_artist";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, $lang['Chart_Sql_Base_Error'], $lang['Error'], __LINE__, __FILE__, $sql);
		}
		$rowset = $db->sql_fetchrowset($result);
		$db->sql_freeresult($result);
		$chart_count = count($rowset);
		
		for($i = 0; $i < $chart_count; $i++)
		{
			if ($i + 1 < $rowset[$i]['chart_best_pos'] || $rowset[$i]['chart_best_pos'] == 0)
			{
				$add = ', chart_best_pos = ' . ($i + 1);
			}
			else
			{
				$add = '';
			}
				
			$sql = "UPDATE " . CHARTS_TABLE . "
				SET chart_last_pos = " . ($i + 1) . $add . "
				WHERE chart_id = " . $rowset[$i]['chart_id'];
			if(!$result = $db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, $lang['Chart_Sql_Base_Error'], $lang['Error'], __LINE__, __FILE__, $sql);
			}
		}
			
		$sql = "UPDATE " . CONFIG_TABLE . "
			SET config_value = config_value + 1
			WHERE config_name = 'charts_week_num'";
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR,$lang['Chart_Sql_Base_Error'], $lang['Error'], __LINE__, __FILE__, $sql);
		}
	
		// Remove cache file
		@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);
		
		$sql = "DELETE FROM " . CHARTS_VOTERS_TABLE;
		if(!$result = $db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, $lang['Chart_Sql_Base_Error'], $lang['Error'], __LINE__, __FILE__, $sql);
		}
					
		$message .= $lang['Chart_Week_Ok'] . '<br /><br />' . sprintf($lang['Chart_Click_Return'], '<a href="' . append_sid("admin_charts.$phpEx") . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.'.$phpEx) . '">', '</a>');
			
		message_die(GENERAL_MESSAGE,$message);
	}
}
else
{
	$template->set_filenames(array(
		'body' => 'admin/charts_body.tpl')
	);

	$sql = "SELECT *
		FROM " . CHARTS_TABLE;
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, $lang['Chart_Sql_Error'], $lang['Error'], __LINE__, __FILE__, $sql);
	}

	$template->assign_vars(array(
		'L_PAGE_DESC' => $lang['Chart_Page_Desc'],
		'L_PAGE_TITLE' => $lang['Chart'] . ' ' . $lang['Manage'],
		'L_ARTIST' => $lang['Chart_Artist'],
		'L_TITLE' => $lang['Chart_Title'],
		'L_ALBUM' => $lang['Chart_Album'],
		'L_LABEL' => $lang['Chart_Label'],
		'L_CAT_NO' => $lang['Chart_Cat_No'],
		'L_WEBSITE' => $lang['Website'],
		'L_EDIT' => '<img src="' . $phpbb_root_path . $images['acp_edit']  . '" alt="' . $lang['Edit'] . '" title="' . $lang['Edit'] . '" />',		
		'L_DELETE' => '<img src="' . $phpbb_root_path . $images['acp_delete'] . '" alt="' . $lang['Delete'] . '" title="' . $lang['title'] . '" />',
		'L_END_WEEK' => $lang['Chart_End_Week'],

		'S_CHARTS_ACTION' => append_sid('admin_charts.'.$phpEx),
		'S_HIDDEN_FIELDS' => '')
	);
		
	$i = 0;
	while($row = $db->sql_fetchrow($result))
	{
		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('charts', array(
			'ROW_CLASS' => $row_class,
			'ARTIST' => $row['chart_artist'],
			'TITLE' => $row['chart_song_name'],
			'ALBUM' => $row['chart_album'],
			'LABEL' => $row['chart_label'],
			'CAT_NO' => $row['chart_catno'],
			'WEBSITE' => $row['chart_website'],

			'U_EDIT' => append_sid("admin_charts.$phpEx?action=edit&amp;id=" . $row['chart_id']),
			'U_DELETE' => append_sid("admin_charts.$phpEx?action=delete&amp;id=" . $row['chart_id']))
		);
        $i++;
	}
	$db->sql_freeresult($result);
}

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>