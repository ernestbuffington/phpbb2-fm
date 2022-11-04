<?php
/** 
*
* @package includes
* @version $Id: functions_usergroup,v 1.47.2.5 2003/08/26 17:49:42 niels Exp $
* @copyright (c) 2003 Niels Chr. Rød
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

if ( !defined('IN_PHPBB') )
{
	die('Hacking attempt');
	exit;
}

function display_usergroups($viewer, $user, $blockname = '', $template_file = '')
{
	global $db, $template, $lang, $images, $phpEx;

	$sql = "SELECT  g.group_name, g.group_id, g.group_type, SUM(ug.user_id = '$viewer') AS viewer, SUM(ug.user_id = '$user') AS poster 
		FROM " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug 
	 	WHERE g.group_id = ug.group_id 
	 		AND NOT g.group_single_user 
	 		AND NOT ug.user_pending
			AND ug.user_id IN ('$viewer','$user')
		GROUP BY ug.group_id having poster 
			AND (g.group_type != " . GROUP_HIDDEN . " OR viewer) 
		ORDER BY g.group_name";
	if(!$result = $db->sql_query($sql)) 
   	{
   		message_die(GENERAL_ERROR, 'Error getting group information.', '', __LINE__, __FILE__, $sql); 
	}
	
	$total_groups = 0;
	unset($group_list);
	$group_list = $db->sql_fetchrowset($result);
	$db->sql_freeresult($result);
	
	if (!empty($group_list))
	{
		$template->set_filenames(array(
			'group_body' => ($template_file) ? $template_file . '.tpl' : 'show_usergroup_as_dropdown.tpl')
		);
		
		$template->flush_block_vars('group');
		$template->flush_block_vars('one_group');
		$template->flush_block_vars('multi_group');

		while (list($group_number, $group) = each($group_list))
		{
			$group_img = ($images['groups'][$group['group_id']]) ? '<img src="' . $images['groups'][$group['group_id']] . '" alt="' . $group['group_name'] . '" title="' . $group['group_name'] . '" />' : '';
			$group_url = append_sid('groupcp.'.$phpEx.'?' . POST_GROUPS_URL . '=' . $group['group_id']); 
			
			$template->assign_block_vars('group', array(
				'GROUP_ID' => $group['group_id'],
				'GROUP_NAME' => $group['group_name'],
				'GROUP_IMG' => $group_img,
				'U_GROUP' => $group_url)
			);
			
			if ($group['group_type'] != GROUP_HIDDEN)
			{
				$template->assign_block_vars('group.is_not_hidden', array());
			} 
			else
			{
				$template->assign_block_vars('group.is_hidden', array());
			}
			
			$total_groups++;
		}

		$template->assign_block_vars('multi_group', array(
			'L_SELECT_GROUP' => $lang['Select_group'])
		);
			
		$template->append_var_from_handle_to_block($blockname, 'SHOW_USERGROUPS', 'group_body');
		
		return true;
	}
	
	return false;
}

?>