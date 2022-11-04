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
 *                            link_user_upload.php
 *                           ----------------------
 *   Modified by CRLin
 ***************************************************************************/

class linkdb_user_upload extends linkdb_public
{
	function main($action)
	{
		global $_REQUEST, $_POST, $linkdb_config, $phpbb_root_path, $board_config;
		global $template, $db, $lang, $userdata, $user_ip, $phpEx, $linkdb_functions;
		
		include($phpbb_root_path . 'mods/linkdb/includes/functions_field.'.$phpEx);

		$custom_field = new custom_field();
		$custom_field->init();
		
		//
		// Get Vars
		//
		$cat_id = ( isset($_REQUEST['cat_id']) ) ? intval($_REQUEST['cat_id']) : 0;
		$do = (isset($_REQUEST['do'])) ? intval($_REQUEST['do']) : '';
		$link_id = (isset($_REQUEST['link_id'])) ? intval($_REQUEST['link_id']) : 0;
		
		if($linkdb_config['lock_submit_site'] && $userdata['user_level'] != ADMIN)
		{
			$message = $lang['Lock_submit_site'];
			$message .= '<br /><br />' . sprintf($lang['Click_return'], '<a href="javascript:history.go(-1)">', '</a>');
	
			$template->assign_vars(array(
				'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("linkdb.$phpEx") . '">'
			));

			message_die(GENERAL_MESSAGE, $message);
		}
		
		//
		// Check allow guest submit site
		//
		if($linkdb_config['allow_guest_submit_site'] && !$userdata['session_logged_in'])
		{
			$redirect = "linkdb.$phpEx?action=user_upload";
			if(!empty($cat_id))
			{
				$redirect .= "&cat_id=" . $cat_id;
			}
			$s_login_fields = '<input type="hidden" name="redirect" value="' . $redirect . '" />';
			$template->assign_vars(array(
				'ALLOW_GUEST' => '1',
				'LINK_GUEST_REG' => $lang['Link_guest_reg'],
				'L_ENTER_PASSWORD' => $lang['Enter_password'],
				'S_LOGIN_FIELDS' => $s_login_fields
			));
			$template->assign_block_vars('guestname',array());
		}
		elseif ( !$userdata['session_logged_in'] )
		{
			$redirect = "linkdb.$phpEx&action=user_upload";
			if($cat_id) $redirect .= "&cat_id=" . $cat_id;
			if($link_id) $redirect .= "&link_id=" . $link_id;
			redirect(append_sid("login.$phpEx?redirect=" . $redirect, true));
		}
		else
		{
			$template->assign_vars(array('ALLOW_GUEST' => '0'));
		}
		
		$sql = 'SELECT *
			FROM ' . LINKS_TABLE . "
			WHERE link_id = $link_id";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Couldn\'t get file info', '', __LINE__, __FILE__, $sql);
		}
		$user_info = $db->sql_fetchrow($result);
		if($do == 'delete')
		{
			if (($linkdb_config['allow_delete_link'] && $user_info['user_id'] != ANONYMOUS && $user_info['user_id'] == $userdata['user_id']) || $userdata['user_level'] == ADMIN)
			{
				$this->delete_links($link_id);
				$this->_linkdb();
				$message = $lang['Linkdeleted'] . '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid('linkdb.php') . '">', '</a>');
				message_die(GENERAL_MESSAGE, $message);
			}
			else
			{
				message_die(GENERAL_MESSAGE, $lang['Sorry_auth_delete']);
			}
		}
		
		//
		// If submit and check URL activated do URL check
		//
		$url_valid = TRUE;
		$url_to_check = '';
		if ( isset($_POST['submit']) && $linkdb_config['url_validation'])
		{
			include($phpbb_root_path . 'mods/linkdb/includes/validateUrlSyntax.'.$phpEx);
			
			$url_to_check = (!empty($_POST['link_url'])) ? $_POST['link_url'] : '';

			if (validateUrlSyntax($url_to_check, str_replace(':','',$linkdb_config['url_validation_setting'])))
			{
				$url_valid = TRUE;
			}
			else
			{
				$url_valid = FALSE;
			}
		}
		
		//
		// IF submit then upload the link and update the sql for it
		//
		if ( isset($_POST['submit']) && $url_valid)
		{
			if(!$link_id)
			{
				$temp_id = $this->update_add_link();
				$custom_field->file_update_data($temp_id);
				$this->_linkdb();
				$action_tmp = 'link_add';
			}
			elseif($link_id != '')
			{
				if (($linkdb_config['allow_edit_link'] && $user_info['user_id'] != ANONYMOUS && $user_info['user_id'] == $userdata['user_id']) || $userdata['user_level'] == ADMIN)
				{
					$link_id = $this->update_add_link($link_id);
					$custom_field->file_update_data($link_id);
					$this->_linkdb();
					$action_tmp = 'link_edit';
				}
				else
				{
					message_die(GENERAL_MESSAGE, $lang['Sorry_auth_edit']);
				}
			}

			if ( $userdata['user_level'] != ADMIN )
			{
				$link_url = (!empty($_POST['link_url'])) ? $_POST['link_url'] : '';
				
				if ( !$board_config['privmsg_disable'] && $linkdb_config['pm_notify'] )
				{
					$this->pm_to_admin($link_url, $action_tmp);
				}
				if ( $linkdb_config['email_notify'] )
				{
					$sql = "SELECT user_email, user_lang 
						FROM " . USERS_TABLE . "
						WHERE user_level = " . ADMIN;
					if ( !$admin_result = $db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, "Could not query users table", "", __LINE__, __FILE__, $sql);
					}
					
					include($phpbb_root_path . 'includes/emailer.'.$phpEx);
					$emailer = new emailer($board_config['smtp_delivery']);

					$emailer->from($board_config['board_email']);
					$emailer->replyto($board_config['board_email']);
					
					while( $to_userdata = $db->sql_fetchrow($admin_result) )
					{
						if ( !empty($to_userdata['user_email']) )
						{
							$emailer->email_address($to_userdata['user_email']);
							
							//
							// set to linkdb/lauguage
							//
							$phpbb_root_path_tmp = $phpbb_root_path;
							$phpbb_root_path = LINKDB_LANG_PATH;
							$emailer->use_template($action_tmp, $to_userdata['user_lang']);
							$phpbb_root_path = $phpbb_root_path_tmp;
							
							//$emailer->set_subject($lang['Notification_subject']);
					
							$emailer->assign_vars(array(
								'LINK_URL' => $link_url,
								'SITENAME' => $board_config['sitename']
								)
							);

							$emailer->send();
							$emailer->reset();
						}
					}
					$db->sql_freeresult($admin_result);
				}
			}
			
			$template->assign_vars(array( 
				'META' => '<meta http-equiv="refresh" content="3;url=' .  "linkdb.$phpEx?action=category&cat_id=$cat_id" . '">' 
			));

			if($action_tmp == 'link_add')
			{
				$message = $lang['Linkadded'];
			}
			elseif($action_tmp == 'link_edit')
			{	
				$message = $lang['Linkedited'];
			}
			$message .=  '<br /><br />' . sprintf($lang['Click_return'], '<a href="' . append_sid("linkdb.$phpEx?action=category&cat_id=$cat_id") . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);	
		}
		else
		{
			if($userdata['user_level'] == ADMIN) $template->assign_block_vars('ADMIN', array());
			
			if($linkdb_config['allow_no_logo'])
			{	
				$template->assign_vars(array(
					'ALLOW_NO_LOGO' => '1',
					'L_LINK_LOGO_SRC' => sprintf($lang['Link_logo_src1'],$linkdb_config['width'],$linkdb_config['height']))
				);
			}
			else
			{
				$template->assign_vars(array(
					'ALLOW_NO_LOGO' => '0',
					'L_LINK_LOGO_SRC' => sprintf($lang['Link_logo_src'],$linkdb_config['width'],$linkdb_config['height']))
				);
			}

			if(!$link_id)
			{
				$link_name = '';
				$link_longdesc = '';
				$link_logo_src = 'http://';
				$file_cat_list = (!$cat_id) ? $this->jumpmenu_option(0, 0, '', true) : $this->jumpmenu_option(0, 0, array($cat_id => 1), true, true);
				$file_download = 0;
				$pin_checked_yes = '';
				$pin_checked_no = ' checked';
				$approved_checked_yes = ' checked';
				$approved_checked_no = '';
				$link_url = 'http://';
				$custom_exist = $custom_field->display_edit();
				$l_title = $lang['AddLink'];
			}
			elseif($link_id != '' )
			{
				if ( !$userdata['session_logged_in'] )
				{
					$redirect = "linkdb.$phpEx&action=user_upload&link_id=" . $link_id;
					redirect(append_sid("login.$phpEx?redirect=" . $redirect, true));
				}
				if ( !($linkdb_config['allow_edit_link'] && $user_info['user_id'] != ANONYMOUS && $user_info['user_id'] == $userdata['user_id']) && $userdata['user_level'] != ADMIN)
				{
					message_die(GENERAL_MESSAGE, $lang['Sorry_auth_edit']);
				}
				$sql = 'SELECT *
					FROM ' . LINKS_TABLE . "
					WHERE link_id = $link_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Couldn\'t get file info', '', __LINE__, __FILE__, $sql);
				}
				$file_info = $db->sql_fetchrow($result);

				$link_name = $file_info['link_name'];
				$link_longdesc = $file_info['link_longdesc'];
				$link_logo_src = $file_info['link_logo_src'];
				if( $link_logo_src ==  '') $link_logo_src = 'http://';
				$file_cat_list = $this->jumpmenu_option(0, 0, array($file_info['link_catid'] => 1), true);
				$file_download = intval($file_info['link_hits']);
				$pin_checked_yes = ($file_info['link_pin']) ? ' checked' : '';
				$pin_checked_no = (!$file_info['link_pin']) ? ' checked' : '';
				$approved_checked_yes = ($file_info['link_approved']) ? ' checked' : '';
				$approved_checked_no = (!$file_info['link_approved']) ? ' checked' : '';
				$link_url = $file_info['link_url'];
				$custom_exist = $custom_field->display_edit($link_id);
				$l_title = $lang['Elinktitle'];
				$s_hidden_fields = '<input type="hidden" name="link_id" value="' . $link_id . '">';
			}
			$s_hidden_fields .= '<input type="hidden" name="action" value="user_upload">';
			if (!$url_valid)
			{
				$template->assign_block_vars('notvalidurl',array());
			}

			$template->assign_vars(array(
				'S_ADD_FILE_ACTION' => append_sid("linkdb.$phpEx"),
				'LINK_CAT_NOT_ALLOW' => $lang['Cat_not_allow'],
				'LINKS' => $lang['Linkdb'],
				
				'FILE_NAME' => $link_name,
				'FILE_LONG_DESC' => $link_longdesc,
				'LINK_LOGO_SRC' => $link_logo_src,

				'LINK_URL' => $link_url,
				'L_FILE_PINNED' => $lang['Linkpin'],
				'L_FILE_PINNED_INFO' => $lang['Linkpininfo'],
				'FILE_DOWNLOAD' => $file_download,
				'CUSTOM_EXIST' => $custom_exist,
				'APPROVED_CHECKED_YES' => $approved_checked_yes,
				'APPROVED_CHECKED_NO' => $approved_checked_no,
				'PIN_CHECKED_YES' => $pin_checked_yes,
				'PIN_CHECKED_NO' => $pin_checked_no,
				
				'L_INDEX' => sprintf($lang['Forum_Index'], $board_config['sitename']),
				'L_ADD_EDIT_LINK' => $l_title,
				'L_FILE_TITLE' => $l_title,
				'L_FILE_APPROVED' => $lang['Approved'],
				'L_FILE_APPROVED_INFO' => $lang['Approved_info'],
				'L_ADDTIONAL_FIELD' => $lang['Addtional_field'],
				'L_GUESTNAME' => $lang['Guest_name'],
				'L_FILE_NAME' => $lang['Sitename'],
				'L_FILE_NAME_INFO' => $lang['Sitenameinfo'],
				'L_FILE_LONG_DESC' => $lang['Siteld'],
				'L_FILE_LONG_DESC_INFO' => $lang['Siteldinfo'],
				
				'L_LINK_LOGO' => $lang['Link_logo'],
				'L_PREVIEW' => $lang['Preview_logo'],

				'L_FILE_URL' => $lang['Siteurl'],
				'L_FILE_UPLOAD' => $lang['File_upload'],
				'L_FILEINFO_UPLOAD' => $lang['Fileinfo_upload'],
				'L_FILE_URL_INFO' => $lang['Fileurlinfo'],
				'L_FILE_CAT' => $lang['Sitecat'],
				'L_FILE_CAT_INFO' => $lang['Sitecatinfo'],
				'L_FILE_DOWNLOAD' => $lang['Link_hits'],
				'L_NO' => $lang['No'],
				'L_YES' => $lang['Yes'],
				'L_NOT_VALID_URL' => $lang['Link_not_valid_url'],
				'TESTED_URL' => ': '.$url_to_check,

				'LINK_GUEST_FIELD' => $lang['Link_guset_field'],
				'LINK_NAME_FIELD' => $lang['Link_name_field'],
				'LINK_URL_FIELD' => $lang['Link_url_field'],
				'LINK_LOGO_FIELD' => $lang['Link_logo_field'],
				'LINK_LONG_DES_FIELD' => $lang['Link_long_des_field'],

				'S_CAT_LIST' => $file_cat_list,
				'S_HIDDEN_FIELDS' => $s_hidden_fields,

				'U_INDEX' => append_sid('index.'.$phpEx),
				'U_LINK' => append_sid('linkdb.'.$phpEx))
			);
			if ($custom_exist) $template->assign_block_vars('custom_exit', array());

			$this->display($lang['Linkdb'] . ' :: ' . $l_title, 'link_add.tpl');
		}
	}

function pm_to_admin($link_url, $action_tmp)
{
	global $board_config, $db, $lang, $user_ip;
	
	$html_on = 0; $bbcode_on = 0; $smilies_on = 0; $attach_sig = 0;

	$sql = "SELECT user_id, user_allow_pm 
		FROM " . USERS_TABLE . "
		WHERE user_level = " . ADMIN;
	if ( !$admin_result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Could not query users table", "", __LINE__, __FILE__, $sql);
	}
				
	while( $to_userdata = $db->sql_fetchrow($admin_result) )
	{
		//
		// Has admin prevented user from sending PM's?
		//
		if ( $to_userdata['user_allow_pm'] )
		{
			$bbcode_uid = 0;
			$msg_time = time();
			//
			// See if recipient is at their inbox limit
			//
			$sql = "SELECT COUNT(privmsgs_id) AS inbox_items, MIN(privmsgs_date) AS oldest_post_time 
				FROM " . PRIVMSGS_TABLE . " 
				WHERE ( privmsgs_type = " . PRIVMSGS_NEW_MAIL . " 
				OR privmsgs_type = " . PRIVMSGS_READ_MAIL . "  
				OR privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . " ) 
				AND privmsgs_to_userid = " . $to_userdata['user_id'];
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_MESSAGE, $lang['No_such_user']);
			}

			$sql_priority = ( SQL_LAYER == 'mysql' ) ? 'LOW_PRIORITY' : '';

			if ( $inbox_info = $db->sql_fetchrow($result) )
			{
				if ( $inbox_info['inbox_items'] >= $board_config['max_inbox_privmsgs'] )
				{
					$sql = "SELECT privmsgs_id FROM " . PRIVMSGS_TABLE . " 
						WHERE ( privmsgs_type = " . PRIVMSGS_NEW_MAIL . " 
						OR privmsgs_type = " . PRIVMSGS_READ_MAIL . " 
						OR privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . "  ) 
						AND privmsgs_date = " . $inbox_info['oldest_post_time'] . " 
						AND privmsgs_to_userid = " . $to_userdata['user_id'];
					if ( !$result = $db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'Could not find oldest privmsgs (inbox)', '', __LINE__, __FILE__, $sql);
					}
					$old_privmsgs_id = $db->sql_fetchrow($result);
					$old_privmsgs_id = $old_privmsgs_id['privmsgs_id'];
				
					$sql = "DELETE $sql_priority FROM " . PRIVMSGS_TABLE . " 
						WHERE privmsgs_id = $old_privmsgs_id";
					if ( !$db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'Could not delete oldest privmsgs (inbox)'.$sql, '', __LINE__, __FILE__, $sql);
					}

					$sql = "DELETE $sql_priority FROM " . PRIVMSGS_TEXT_TABLE . " 
						WHERE privmsgs_text_id = $old_privmsgs_id";
					if ( !$db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, 'Could not delete oldest privmsgs text (inbox)', '', __LINE__, __FILE__, $sql);
					}
				}
			}
			
			if ($action_tmp == 'link_add' )
			{
				$privmsg_subject = $lang['Link_pm_notify_subject'];
			}
			else
			{
				$privmsg_subject = $lang['Link_edit_pm_subject'];
			}

			$sql_info = "INSERT INTO " . PRIVMSGS_TABLE . " (privmsgs_type, privmsgs_subject, privmsgs_from_userid, privmsgs_to_userid, privmsgs_date, privmsgs_ip, privmsgs_enable_html, privmsgs_enable_bbcode, privmsgs_enable_smilies, privmsgs_attach_sig)
				VALUES (" . PRIVMSGS_NEW_MAIL . ", '" . str_replace("\'", "''", $privmsg_subject) . "', " . $to_userdata['user_id'] . ", " . $to_userdata['user_id'] . ", $msg_time, '$user_ip', $html_on, $bbcode_on, $smilies_on, $attach_sig)";
			if ( !($result = $db->sql_query($sql_info, BEGIN_TRANSACTION)) )
			{
				message_die(GENERAL_ERROR, "Could not insert/update private message sent info.", "", __LINE__, __FILE__, $sql_info);
			}
		
			$privmsg_sent_id = $db->sql_nextid();
			
			if ($action_tmp == 'link_add' )
			{
				$privmsg_message = sprintf($lang['Link_pm_notify_message'], $link_url);
			}
			else
			{
				$privmsg_message = sprintf($lang['Link_edit_pm_message'], $link_url);
			}
					
			$sql = "INSERT INTO " . PRIVMSGS_TEXT_TABLE . " (privmsgs_text_id, privmsgs_bbcode_uid, privmsgs_text)
				VALUES ($privmsg_sent_id, '" . $bbcode_uid . "', '" . str_replace("\'", "''", $privmsg_message) . "')";
		
			if ( !$db->sql_query($sql, END_TRANSACTION) )
			{
				message_die(GENERAL_ERROR, "Could not insert/update private message sent text.", "", __LINE__, __FILE__, $sql_info);
			}

			//
			// Add to the users new pm counter
			//
			$sql = "UPDATE " . USERS_TABLE . "
				SET user_new_privmsg = user_new_privmsg + 1, user_last_privmsg = " . time() . "  
				WHERE user_id = " . $to_userdata['user_id']; 
			if ( !$status = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not update private message new/read status for user', '', __LINE__, __FILE__, $sql);
			}
		}
	}
	$db->sql_freeresult($admin_result);
}

}
?>