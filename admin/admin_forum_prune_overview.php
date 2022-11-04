<?php
/***************************************************************************
 *                         admin_forum_prune_overview.php
 *                            -------------------
 *   begin                : September 16, 2004
 *   copyright            : (C) 2004 Leuchte
 *   email                : mail@leuchte.net
 *
 *   $Id: admin_forum_prune_overview.php,v 1.0.0 2004/09/16 18:26:35 Leuchte $
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
 
define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Forums']['Prune_Overview'] = $file;
	return;
}

$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

if ( $board_config['prune_enable'] )
{
	$board_prune_enabled = "checked=\"checked\"";
}
else
{
  	$board_prune_enabled = '';
}

    
$template->assign_vars(array(
    'L_PRUNE_TITLE' => $lang['Prune_Overview'] . ' ' . $lang['Setting'],
	'L_PRUNE_TEXT' => $lang['Prune_title_explain'],
    'L_PRUNE_FORUM' => $lang['Prune_forum'],
    'L_PRUNE_FREQ' => $lang['Prune_freq'],
    'L_PRUNE_CHECK' => $lang['Prune_check'],
    'L_PRUNE_ACTIVE' => $lang['Prune_active'],
    'L_DAYS_EXPLAIN' => $lang['Prune_days_explain'],
    'L_DAYS' => $lang['Prune_days'],
    'L_ENABLE_PRUNE' => $lang['Enable_prune'],
    'L_SUBMIT' => $lang['Submit'],
    
    'ENABLE_PRUNE' => $board_prune_enabled,
    'S_PRUNE_ACTION' => append_sid("admin_forum_prune_overview.$phpEx"))
);

$sql = "SELECT forum_name, forum_id, prune_enable
	FROM ". FORUMS_TABLE ."
	WHERE forum_id > 0 
	ORDER BY prune_enable DESC, forum_order ASC";

if ( !($result = $db->sql_query($sql)) )
{
   message_die(GENERAL_ERROR, 'Could not query forum information', '', __LINE__, __FILE__, $sql);
}
$forums = $db->sql_fetchrowset($result); 
$nums = $db->sql_numrows($result);
  
for ($i = 0; $i < $nums; $i++) 
{
    if( $forums[$i]['prune_enable'] )
	{
		$sql = "SELECT *
      		FROM " . PRUNE_TABLE . "
        	WHERE forum_id = '". $forums[$i]['forum_id'] ."'";
		
		if(!$pr_result = $db->sql_query($sql))
    	{
			 message_die(GENERAL_ERROR, "Couldn't read auto_prune table.", __LINE__, __FILE__);
		}

		$pr_row = $db->sql_fetchrow($pr_result);
    	$prune_enabled = "checked=\"checked\"";
    	$prune_days = $pr_row['prune_days'];
    	$prune_freq = $pr_row['prune_freq'];
    }
    else
    {
    	$prune_enabled = '';
    	$prune_days = 7;
    	$prune_freq = 1;
    }
                  
    $forum_url = append_sid($phpbb_root_path .'/admin/admin_forums.'.$phpEx.'?mode=editforum&f=' . $forums[$i]['forum_id']);
    $forum = '<a href="'. $forum_url .'">'. $forums[$i]['forum_name'] .'</a>';

    $row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
    $row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
     
    $template->assign_block_vars('prune_overview',array(
    	'ROW_COLOR' => '#' . $row_color, 
        'ROW_CLASS' => $row_class,
        'PRUNE_FORUM' => $forum,
        'FORUM_ID' => $forums[$i]['forum_id'],
        'PRUNE_DAYS' => $prune_days,
        'PRUNE_FREQ' => $prune_freq,
            
        'S_PRUNE_ENABLED' => $prune_enabled,
        'S_PRUNE_INDEX' => $i)
	);      					
} // End for ($i...
$db->sql_freeresult($result);

if ( isset($_POST['submit']) )
{
	$forum_id = ( isset($_POST['forum_id']) ) ? $_POST['forum_id'] : array();
	$prune_enable = ( isset($_POST['prune_enable']) ) ? $_POST['prune_enable'] : array();
	$prune_days = ( isset($_POST['prune_days']) ) ? $_POST['prune_days'] : array();
	$prune_freq = ( isset($_POST['prune_freq']) ) ? $_POST['prune_freq'] : array();

	for($i = 0; $i < $nums; $i++)
	{
	  	if( $prune_enable[$i] != 1 )
		{
			$prune_enable[$i] = 0;
		}
  
	  	$sql = "UPDATE ". FORUMS_TABLE ."
			SET prune_enable = ". $prune_enable[$i] ."
	    	WHERE forum_id = ". $forum_id[$i];
    
	    if(!$result = $db->sql_query($sql))
	    { 
	    	message_die(GENERAL_ERROR, "Couldn't update prune table.", __LINE__, __FILE__);
	 	}
   
    	if( $prune_enable[$i] == 1 )
		{
			if( $prune_days[$i] == "" || $prune_freq[$i] == "" )
			{
				message_die(GENERAL_MESSAGE, $lang['Set_prune_data']);
			}

			$sql = "SELECT *
				FROM ". PRUNE_TABLE ."
				WHERE forum_id = ". $forum_id[$i];
			
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't get forum Prune Information","",__LINE__, __FILE__, $sql);
			}

			if( $db->sql_numrows($result) > 0 )
			{
				$sql = "UPDATE ". PRUNE_TABLE ."
					SET	prune_days = ". intval($prune_days[$i]) .",	prune_freq = ". intval($prune_freq[$i]) ."
					WHERE forum_id = ". $forum_id[$i];
			}
			else
			{
				$sql = "INSERT INTO ". PRUNE_TABLE ." (forum_id, prune_days, prune_freq)
					VALUES(" . $forum_id[$i] . ", " . intval($prune_days[$i]) . ", " . intval($prune_freq[$i]) .")";
			}	

			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't Update Forum Prune Information","",__LINE__, __FILE__, $sql);
			}
		}  // End if( $prune_enable[$i] == 1 )
	} // End for($i...

	$value = isset($_POST['enable_prune']) ? 1 : 0;

	$sql = "UPDATE " . CONFIG_TABLE . " 
		SET config_value = '$value'
		WHERE config_name = 'prune_enable'";
	
	if( !$db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Failed to update prune config", "", __LINE__, __FILE__, $sql);
	}
      
	$message = $lang['Prune_update'] . "<br /><br />" . sprintf($lang['Click_return_admin_po'], "<a href=\"" . append_sid("admin_forum_prune_overview.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");
	
	message_die(GENERAL_MESSAGE, $message);
} // End if ( isset($_POST['submit']...

$template->set_filenames(array(
		'body' => 'admin/forum_prune_overview_body.tpl')
);
  
$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

?>