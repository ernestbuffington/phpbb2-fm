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

if ( !defined('IN_PHPBB') || IN_ADMIN != true)
{
	die("Hacking attempt");
}

class linkdb_setting extends linkdb_public
{
	function main($action)
	{
		global $db, $template, $lang, $phpEx, $linkdb_functions;
		
		$validation_switch_array = array(
			's' => array('ID'=>'s','DEFAULT'=>'?','SWITCH_NAME'=>'schema_section_validation','NAME'=>$lang['schema_section_validation']),
			'H' => array('ID'=>'H','DEFAULT'=>'?','SWITCH_NAME'=>'http_validation','NAME'=>$lang['http_validation']),
			'S' => array('ID'=>'S','DEFAULT'=>'?','SWITCH_NAME'=>'https_validation','NAME'=>$lang['https_validation']),
			'E' => array('ID'=>'E','DEFAULT'=>'-','SWITCH_NAME'=>'mailto_validation','NAME'=>$lang['mailto_validation']),
			'F' => array('ID'=>'F','DEFAULT'=>'-','SWITCH_NAME'=>'ftp_validation','NAME'=>$lang['ftp_validation']),
			'u' => array('ID'=>'u','DEFAULT'=>'?','SWITCH_NAME'=>'user_detection_validation','NAME'=>$lang['user_detection_validation']),
			'P' => array('ID'=>'P','DEFAULT'=>'?','SWITCH_NAME'=>'password_validation','NAME'=>$lang['password_validation']),
			'a' => array('ID'=>'a','DEFAULT'=>'+','SWITCH_NAME'=>'address_section_validation','NAME'=>$lang['address_section_validation']),
			'I' => array('ID'=>'I','DEFAULT'=>'?','SWITCH_NAME'=>'ip_address_validation','NAME'=>$lang['ip_address_validation']),
			'p' => array('ID'=>'p','DEFAULT'=>'?','SWITCH_NAME'=>'port_number_validation','NAME'=>$lang['port_number_validation']),
			'f' => array('ID'=>'f','DEFAULT'=>'?','SWITCH_NAME'=>'file_path_validation','NAME'=>$lang['file_path_validation']),
			'q' => array('ID'=>'q','DEFAULT'=>'?','SWITCH_NAME'=>'query_section_validation','NAME'=>$lang['query_section_validation']),
			'r' => array('ID'=>'r','DEFAULT'=>'?','SWITCH_NAME'=>'fragment_anchor_validation','NAME'=>$lang['fragment_anchor_validation'])
		);

		$submit = (isset($_POST['submit'])) ? true : false;

		$sql = 'SELECT *
			FROM ' . LINK_CONFIG_TABLE;
		if(!$result = $db->sql_query($sql))
		{
			message_die(CRITICAL_ERROR, "Could not query config information in admin_board", "", __LINE__, __FILE__, $sql);
		}
		else
		{
			while( $row = $db->sql_fetchrow($result) )
			{
				$config_name = $row['config_name'];
				$config_value = $row['config_value'];
				$default_config[$config_name] = $config_value;
				
				$new[$config_name] = ( isset($_POST[$config_name]) ) ? $_POST[$config_name] : $default_config[$config_name];

				if($submit)
				{
					$linkdb_functions->set_config($config_name, $new[$config_name]);
				}
			}
			$db->sql_freeresult($result);

			//
			// URL validation switches 
			//
			if($submit)
			{
				$new['url_validation_setting']  = '';
				$new['url_validation_setting'] .= (( isset($_POST[schema_section_validation]) ) ? $_POST[schema_section_validation] : '').':';
				$new['url_validation_setting'] .= (( isset($_POST[http_validation]) ) ? $_POST[http_validation] : '').':';
				$new['url_validation_setting'] .= (( isset($_POST[https_validation]) ) ? $_POST[https_validation] : '').':';
				$new['url_validation_setting'] .= (( isset($_POST[mailto_validation]) ) ? $_POST[mailto_validation] : '').':';
				$new['url_validation_setting'] .= (( isset($_POST[ftp_validation]) ) ? $_POST[ftp_validation] : '').':';
				$new['url_validation_setting'] .= (( isset($_POST[user_detection_validation]) ) ? $_POST[user_detection_validation] : '').':';		
				$new['url_validation_setting'] .= (( isset($_POST[password_validation]) ) ? $_POST[password_validation] : '').':';
				$new['url_validation_setting'] .= (( isset($_POST[address_section_validation]) ) ? $_POST[address_section_validation] : '').':';		
				$new['url_validation_setting'] .= (( isset($_POST[ip_address_validation]) ) ? $_POST[ip_address_validation] : '').':';
				$new['url_validation_setting'] .= (( isset($_POST[port_number_validation]) ) ? $_POST[port_number_validation] : '').':';
				$new['url_validation_setting'] .= (( isset($_POST[file_path_validation]) ) ? $_POST[file_path_validation] : '').':';
				$new['url_validation_setting'] .= (( isset($_POST[query_section_validation]) ) ? $_POST[query_section_validation] : '').':';	
				$new['url_validation_setting'] .= (( isset($_POST[fragment_anchor_validation]) ) ? $_POST[fragment_anchor_validation] : '');
				$linkdb_functions->set_config('url_validation_setting', $new['url_validation_setting']);
			}

			if($submit)
			{
				$message = $lang['Board_config_updated'] . "<br /><br />" . sprintf($lang['Click_return_config'], "<a href=\"" . append_sid("admin_linkdb.$phpEx?action=setting") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

				message_die(GENERAL_MESSAGE, $message); 
			}
		}

		$template->set_filenames(array(
			'body' => LINKDB_TPL_PATH . 'admin/linkdb_admin_settings.tpl')
		);  

		$template->assign_vars(array(
			'S_SETTINGS_ACTION' => append_sid( "admin_linkdb.$phpEx"),

			'L_SETTINGSTITLE' => $lang['Link'] . ' ' . $lang['Setting'],
			'L_SETTINGSEXPLAIN' => sprintf($lang['Config_explain'], $lang['Link']),

			'LOCK_SUBMIT_SITE_YES' => ($new['lock_submit_site']) ? "checked=\"checked\"" : "",
			'LOCK_SUBMIT_SITE_NO' => (!$new['lock_submit_site']) ? "checked=\"checked\"" : "",
			'L_LOCK_SUBMIT_SITE' => $lang['lock_submit_site'],
			'CAT_COL' => $new['cat_col'],
			'L_CAT_COL' => $lang['cat_col'],
			'SPLIT_LINKS_YES' => ($new['split_links']) ? "checked=\"checked\"" : "",
			'SPLIT_LINKS_NO' => (!$new['split_links']) ? "checked=\"checked\"" : "",
			'L_SPLIT_LINKS' => $lang['split_links'],
		    'L_SITE_LOGO' => $lang['site_logo'],
			'L_SITE_URL' => $lang['site_url'],
			'L_WIDTH' => $lang['width'],
			'L_HEIGHT' => $lang['height'],
			'L_SETTINGS_LINK_PAGE' => $lang['settings_link_page'],
			'L_DISPLAY_INTERVAL' => $lang['interval'],
			'L_DISPLAY_LOGO_NUM' => $lang['display_logo'],
			'INTERVAL' => $new['display_interval'],
			'LOGO_NUM' => $new['display_logo_num'],
			'SITE_LOGO' => $new['site_logo'],
			'SITE_URL' => $new['site_url'],
			'WIDTH' => $new['width'],
			'HEIGHT' => $new['height'],
			'SETTINGS_LINK_PAGE' => $new['settings_link_page'],
			
			'ALLOW_GUEST_SUBMIT_SITE_YES' => ($new['allow_guest_submit_site']) ? "checked=\"checked\"" : "",
			'ALLOW_GUEST_SUBMIT_SITE_NO' => (!$new['allow_guest_submit_site']) ? "checked=\"checked\"" : "",
			'L_ALLOW_GUEST_SUBMIT_SITE' => $lang['allow_guest_submit_site'],
			'L_NEED_VALIDATION' => $lang['Need_validate'],
			'NEED_VALIDATE_YES' => ($new['need_validation']) ? "checked=\"checked\"" : "",
			'NEED_VALIDATE_NO' => (!$new['need_validation']) ? "checked=\"checked\"" : "",
			'L_URL_VALIDATION' => $lang['perform_url_validation'],
			'URL_VALIDATION_YES' => ($new['url_validation']) ? "checked=\"checked\"" : "",
			'URL_VALIDATION_NO' => (!$new['url_validation']) ? "checked=\"checked\"" : "",
			'L_URL_VALIDATION_SETTING' => $lang['url_validation_setting'],
			'L_URL_VALIDATION_SETTING_EXPLAIN' => $lang['url_validation_setting_explain'],
			'L_LEGEND' => $lang['legend'],
			'L_URL_VALIDATION_MANDATORY' => $lang['url_validation_mandatory'],
			'L_URL_VALIDATION_NOT_ALLOWED' => $lang['url_validation_not_allowed'],
			'L_URL_VALIDATION_OPTIONAL' => $lang['url_validation_optional'],
			'L_URL_VALIDATION_DEFAULT' => $lang['url_validation_default'],
			'ALLOW_NO_LOGO_YES' => ($new['allow_no_logo']) ? "checked=\"checked\"" : "",
			'ALLOW_NO_LOGO_NO' => (!$new['allow_no_logo']) ? "checked=\"checked\"" : "",
			'L_ALLOW_NO_LOGO' => $lang['allow_no_logo'],
			'DISLAY_LINKS_LOGO_YES' => ($new['display_links_logo']) ? "checked=\"checked\"" : "",
			'DISLAY_LINKS_LOGO_NO' => (!$new['display_links_logo']) ? "checked=\"checked\"" : "",
			'L_DISPLAY_LINKS_LOGO' => $lang['Link_display_links_logo'],
			'ALLOW_EDIT_LINK_YES' => ($new['allow_edit_link']) ? "checked=\"checked\"" : "",
			'ALLOW_EDIT_LINK_NO' => (!$new['allow_edit_link']) ? "checked=\"checked\"" : "",
			'L_ALLOW_EDIT_LINK' => $lang['Allow_Edit_Link'],
			'ALLOW_DELETE_LINK_YES' => ($new['allow_delete_link']) ? "checked=\"checked\"" : "",
			'ALLOW_DELETE_LINK_NO' => (!$new['allow_delete_link']) ? "checked=\"checked\"" : "",
			'L_ALLOW_DELETE_LINK' => $lang['Allow_Delete_Link'],
			'EMAIL_YES' => ($new['email_notify']) ? "checked=\"checked\"" : "",
			'EMAIL_NO' => (!$new['email_notify']) ? "checked=\"checked\"" : "",
			'L_LINK_EMAIL_NOTIFY' => $lang['Link_email_notify'],
			'PM_YES' => ($new['pm_notify']) ? "checked=\"checked\"" : "",
			'PM_NO' => (!$new['pm_notify']) ? "checked=\"checked\"" : "",
			'L_LINK_PM_NOTIFY' => $lang['Link_pm_notify'],
			'RATE_YES' => ($new['allow_vote']) ? "checked=\"checked\"" : "",
			'RATE_NO' => (!$new['allow_vote']) ? "checked=\"checked\"" : "",
			'L_LINK_ALLOW_RATE' => $lang['Link_allow_rate'],
			'COMMENT_YES' => ($new['allow_comment']) ? "checked=\"checked\"" : "",
			'COMMENT_NO' => (!$new['allow_comment']) ? "checked=\"checked\"" : "",
			'L_LINK_ALLOW_COMMENT' => $lang['Link_allow_comment'],
			'L_MAX_CHAR' => $lang['Max_char'],
			'L_MAX_CHAR_INFO' => $lang['Max_char_info'],
			'MAX_CHAR' => $new['max_comment_chars'],
			'L_YES' => $lang['Yes'],
			'L_NO' => $lang['No'],

			'L_DEFAULT_SORT_METHOD' => $lang['Default_sort_method'],
			'L_DEFAULT_SORT_ORDER' => $lang['Default_sort_order'],
			'L_DOWNLOADS' => $lang['Hits'],
			'L_DATE' => $lang['Date'],
			'L_NAME' => $lang['Sitename'],
			'L_LINK_SITE_DESC' => $lang['Siteld'],
			'L_ASC' => $lang['Sort_Ascending'],
			'L_DESC' => $lang['Sort_Descending'],

			'SORT_NAME' => ($new['sort_method'] == 'link_name') ? 'selected="selected"' : '',
			'SORT_TIME' => ($new['sort_method'] == 'link_time') ? 'selected="selected"' : '',
			'SORT_RATING' => ($new['sort_method'] == 'link_longdesc') ? 'selected="selected"' : '',
			'SORT_DOWNLOADS' => ($new['sort_method'] == 'link_hits') ? 'selected="selected"' : '',
			'SORT_ASC' => ($new['sort_order'] == 'ASC') ? 'selected="selected"' : '',
			'SORT_DESC' => ($new['sort_order'] == 'DESC') ? 'selected="selected"' : '',

			'L_SUBMIT' => $lang['Submit'],
			'L_RESET' => $lang['Reset'])
		);                 

		//
		// START: set the URL validation switches
		$new_validation_switch_array = split(':',$new['url_validation_setting']);
		foreach($new_validation_switch_array as $new_current_switch)
		{
			$validation_switch_curr[substr($new_current_switch,0,1)] = substr($new_current_switch,1,1);
		}

		//schema_section_validation
		$validation_switch_array['s'] = array_merge($validation_switch_array['s'],array('CURR_VAL'=>$validation_switch_curr['s']));
		//http_validation
		$validation_switch_array['H'] = array_merge($validation_switch_array['H'],array('CURR_VAL'=>$validation_switch_curr['H']));
		//https_validation
		$validation_switch_array['S'] = array_merge($validation_switch_array['S'],array('CURR_VAL'=>$validation_switch_curr['S']));
		//mailto_validation
		$validation_switch_array['E'] = array_merge($validation_switch_array['E'],array('CURR_VAL'=>$validation_switch_curr['E']));
		//ftp_validation
		$validation_switch_array['F'] = array_merge($validation_switch_array['F'],array('CURR_VAL'=>$validation_switch_curr['F']));
		//user_detection_validation
		$validation_switch_array['u'] = array_merge($validation_switch_array['u'],array('CURR_VAL'=>$validation_switch_curr['u']));
		//password_validation
		$validation_switch_array['P'] = array_merge($validation_switch_array['P'],array('CURR_VAL'=>$validation_switch_curr['P']));
		//address_section_validation
		$validation_switch_array['a'] = array_merge($validation_switch_array['a'],array('CURR_VAL'=>$validation_switch_curr['a']));
		//ip_address_validation
		$validation_switch_array['I'] = array_merge($validation_switch_array['I'],array('CURR_VAL'=>$validation_switch_curr['I']));
		//port_number_validation
		$validation_switch_array['p'] = array_merge($validation_switch_array['p'],array('CURR_VAL'=>$validation_switch_curr['p']));
		//file_path_validation
		$validation_switch_array['f'] = array_merge($validation_switch_array['f'],array('CURR_VAL'=>$validation_switch_curr['f']));
		//query_section_validation
		$validation_switch_array['q'] = array_merge($validation_switch_array['q'],array('CURR_VAL'=>$validation_switch_curr['q']));
		//fragment_anchor_validation
		$validation_switch_array['r'] = array_merge($validation_switch_array['r'],array('CURR_VAL'=>$validation_switch_curr['r']));

		$j = 1;
		foreach($validation_switch_array as $current_switch)
		{
			$template->assign_block_vars('validation_switch_row', array(
				'ROW_START' => ($j % 1) ? '<tr>' : '',
				'ROW_END'   => ($j % 1) ? "" : "</tr>",
				'ID'              => $current_switch['ID'],
				'NAME'            => $current_switch['NAME'],
				'SWITCH_NAME'     => $current_switch['SWITCH_NAME'],
				'DEFAULT_SETTING' => $current_switch['DEFAULT'],
				
				'MANDATORY' => ($current_switch['CURR_VAL'] == '+') ? 'selected="selected"' : '',
				'ALLOWED'   => ($current_switch['CURR_VAL'] == '-') ? 'selected="selected"' : '',
				'OPTIONAL'  => ($current_switch['CURR_VAL'] == '?') ? 'selected="selected"' : '',
				'DEFAULT'   => ($current_switch['CURR_VAL'] == '')  ? 'selected="selected"' : '',
				
				'L_URL_VALIDATION_MANDATORY'   => $lang['url_validation_mandatory'],
				'L_URL_VALIDATION_NOT_ALLOWED' => $lang['url_validation_not_allowed'],
				'L_URL_VALIDATION_OPTIONAL'    => $lang['url_validation_optional'],
				'L_URL_VALIDATION_DEFAULT'     => $lang['url_validation_default'])
			);
			$j++;
		}
		// END:  set the URL validation switches
		//
		$template->pparse('body');
	}
}
?>