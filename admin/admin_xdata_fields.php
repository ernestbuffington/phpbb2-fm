<?php
/** 
*
* @package lang_english
* @version $Id: admin_xdata_fields.php,v 1.1.0 2004/01/06 zayin Exp $
* @copyright (c) 2003 Daniel Lewis
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Users_fields']['Management'] = $file;
	return;
}

//
// Load default header
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'includes/functions_admin.'.$phpEx);

//
// include language file 
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_xd.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_xd.' . $phpEx);

//
// Mode setting
//
if( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode); 
}
else
{
	$mode = "view";
}

//
// Get the metadata
//
$xd_meta = get_xd_metadata();

//
// Main program
//
switch ($mode)
{
	case 'up':
	case 'down':

		if ( ! isset($HTTP_GET_VARS['name']) )
		{
			message_die(GENERAL_ERROR, 'No field selected.');
		}
		else
		{
			$name = $HTTP_GET_VARS['name'];
			
			if ( ! isset($xd_meta[$name]) )
			{
				message_die(GENERAL_ERROR, 'That field does not exist.');
			}
		}
		
		$swap1 = $xd_meta[$name];
		
		$sql = "SELECT field_id, field_order
			FROM " . XDATA_FIELDS_TABLE ."
			WHERE field_order " . ( ($mode == 'up') ? '<' : '>' ) . $swap1['field_order'] . "
			ORDER BY field_order " . ( ($mode == 'up') ? 'DESC' : 'ASC' ) . "
			LIMIT 1";
	
		if ( !( $result = $db->sql_query($sql) ) )
		{
			message_die(GENERAL_ERROR, 'Unable to get field to switch with.', '', __LINE__, __FILE__, $sql);
		}
		
		$swap2 = $db->sql_fetchrow($result);
	
		if ($swap1 && $swap2)
		{
			$sql = "UPDATE " . XDATA_FIELDS_TABLE . "
				SET field_order = " . $swap2['field_order'] . "
				WHERE field_id = " . $swap1['field_id'];

			if ( ! $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Failed to update first field\'s field_order.', '', __LINE__, __FILE__, $sql);
			}
		
		    $sql = "UPDATE " . XDATA_FIELDS_TABLE . "
				SET field_order = " . $swap1['field_order'] . "
				WHERE field_id = " . $swap2['field_id'];
				
			if ( ! $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Failed to update first field\'s field_order.', '', __LINE__, __FILE__, $sql);
			}
		}
	
		$mode = 'view';
		$xd_meta = get_xd_metadata(true);
		break;

	case 'edit':

    	if( isset($HTTP_POST_VARS['name']) || isset($HTTP_GET_VARS['name']) )
		{
			$name = ( isset($HTTP_POST_VARS['name']) ) ? $HTTP_POST_VARS['name'] : $HTTP_GET_VARS['name'];
		}
		else
		{
			message_die(GENERAL_ERROR, 'No field was specified.');
		}

		if ( ! isset($xd_meta[$name]) )
		{
			message_die(GENERAL_ERROR, 'That field does not exist.');
		}

		if ( ! isset($HTTP_POST_VARS['submit']) )
		{
			//
			// Show the edit form
			//

			$meta = $xd_meta[$name];
			
			if ($meta['field_type'] != 'special')
			{
				$template->set_filenames( array('body' => 'admin/xd_edit_body.tpl') );
			}
			else
			{
				$template->set_filenames( array('body' => 'admin/xd_edit_body_limited.tpl') );
			}

            $template->assign_vars(array(
				'NAME' => $meta['field_name'],
				'CODE_NAME' => $meta['code_name'],
				'DESCRIPTION' => $meta['field_desc'],
				'TYPE' => $meta['field_type'],
				'LENGTH' => $meta['field_length'],
				'VALUES' => $meta['field_values'],
				'REGEXP' => $meta['field_regexp'],

				'TEXT_SELECTED' => ( $meta['field_type'] == 'text' ) ? ' selected="selected"' : '',
				'TEXTAREA_SELECTED' => ( $meta['field_type'] == 'textarea' ) ? ' selected="selected"' : '',
				'RADIO_SELECTED' => ( $meta['field_type'] == 'radio' ) ? ' selected="selected"' : '',
				'SELECT_SELECTED' => ( $meta['field_type'] == 'select' ) ? ' selected="selected"' : '',
				'CUSTOM_SELECTED' => ( $meta['field_type'] == 'custom' ) ? ' selected="selected"' : '',

				'DEFAULT_AUTH_ALLOW_CHECKED' => ( $meta['default_auth'] == 1 ) ? ' checked="checked"' : '',
				'DEFAULT_AUTH_DENY_CHECKED' => ( $meta['default_auth'] == 0 ) ? ' checked="checked"' : '',

				'DISPLAY_REGISTER_NORMAL_CHECKED' => ( $meta['display_register'] == XD_DISPLAY_NORMAL ) ? ' checked="checked"' : '',
				'DISPLAY_REGISTER_NONE_CHECKED' => ( $meta['display_register'] == XD_DISPLAY_NONE ) ? ' checked="checked"' : '',
				'DISPLAY_REGISTER_ROOT_CHECKED' => ( $meta['display_register'] == XD_DISPLAY_ROOT ) ? ' checked="checked"' : '',

				'DISPLAY_POSTING_NORMAL_CHECKED' => ( $meta['display_posting'] == XD_DISPLAY_NORMAL ) ? ' checked="checked"' : '',
				'DISPLAY_POSTING_NONE_CHECKED' => ( $meta['display_posting'] == XD_DISPLAY_NONE ) ? ' checked="checked"' : '',
				'DISPLAY_POSTING_ROOT_CHECKED' => ( $meta['display_posting'] == XD_DISPLAY_ROOT ) ? ' checked="checked"' : '',

				'DISPLAY_PROFILE_NORMAL_CHECKED' => ( $meta['display_viewprofile'] == XD_DISPLAY_NORMAL ) ? ' checked="checked"' : '',
				'DISPLAY_PROFILE_NONE_CHECKED' => ( $meta['display_viewprofile'] == XD_DISPLAY_NONE ) ? ' checked="checked"' : '',
				'DISPLAY_PROFILE_ROOT_CHECKED' => ( $meta['display_viewprofile'] == XD_DISPLAY_ROOT ) ? ' checked="checked"' : '',

				'HANDLE_INPUT_YES_CHECKED' => ( $meta['handle_input'] == 1 ) ? ' checked="checked"' : '',
				'HANDLE_INPUT_NO_CHECKED' => ( $meta['handle_input'] == 0 ) ? ' checked="checked"' : '',

                'ALLOW_BBCODE_YES_CHECKED' => ( $meta['allow_bbcode'] == 1 ) ? ' checked="checked"' : '',
				'ALLOW_BBCODE_NO_CHECKED' => ( $meta['allow_bbcode'] == 0 ) ? 'checked="checked"' : '',

                'ALLOW_SMILIES_YES_CHECKED' => ( $meta['allow_smilies'] == 1 ) ? ' checked="checked"' : '',
				'ALLOW_SMILIES_NO_CHECKED' => ( $meta['allow_smilies'] == 0 ) ? ' checked="checked"' : '',

                'ALLOW_HTML_YES_CHECKED' => ( $meta['allow_html'] == 1 ) ? ' checked="checked"' : '',
				'ALLOW_HTML_NO_CHECKED' => ( $meta['allow_html'] == 0 ) ? ' checked="checked"' : '',


                'AUTH_ALLOW' => XD_AUTH_ALLOW,
        		'AUTH_DENY' => XD_AUTH_DENY,

				'S_HIDDEN_FIELDS' => '<input type="hidden" name="mode" value="edit" /><input type="hidden" name="name" value="'.$name.'" />'
				)
			);

			$template->assign_vars(array(
				'L_BASIC_OPTIONS' => $lang['Basic_Options'],
				'L_ADVANCED_OPTIONS' => $lang['Advanced_Options'],
				'L_ADVANCED_NOTICE' => $lang['Advanced_warning'],
				'L_XDATA_ADMIN' => $lang['edit_xdata_field'],
				'L_NAME' => $lang['Name'],
				'L_DESCRIPTION' => $lang['xd_description'],
				'L_TYPE' => $lang['type'],
				'L_TEXT' => $lang['Text'],
				'L_TEXTAREA' => $lang['Text_area'],
				'L_SELECT' => $lang['Select'],
				'L_RADIO' => $lang['Radio'],
				'L_CUSTOM' => $lang['Custom'],
				'L_LENGTH' => $lang['Length'],
				'L_LENGTH_EXPLAIN' => $lang['Length_explain'],
				'L_VALUES' => $lang['Values'],
				'L_VALUES_EXPLAIN' => $lang['Values_explain'],
				'L_DEFAULT_AUTH' => $lang['Default_auth'],
				'L_DEFAULT_AUTH_EXPLAIN' => $lang['Default_auth_explain'],

				'L_ALLOW_BBCODE' => $lang['Allow_BBCode'],
				'L_ALLOW_SMILIES' => $lang['Allow_smilies'],
                'L_ALLOW_HTML' => $lang['Allow_html'],
				
				'L_DISPLAY_TYPE' => $lang['Display_type'],
				'L_DISPLAY_REGISTER_EXPLAIN' => $lang['Display_register_explain'],
				'L_DISPLAY_PROFILE_EXPLAIN' => $lang['Display_viewprofile_explain'],
				'L_DISPLAY_POSTING_EXPLAIN' => $lang['Display_viewtopic_explain'],
				'L_NORMAL' => $lang['Display_normal'],
				'L_NONE' => $lang['Display_none'],
				'L_ROOT' => $lang['Display_root'],
				'L_CODE_NAME' => $lang['Code_name'],
				'L_CODE_NAME_EXPLAIN' => $lang['Code_name_explain'],
				'L_REGEXP' => $lang['Regexp'],
				'L_REGEXP_EXPLAIN' => $lang['Regexp_explain'],
				'L_HANDLE_INPUT' => $lang['handle_input'],
				'L_HANDLE_INPUT_EXPLAIN' => $lang['handle_input_explain'],
				'L_SUBMIT' => $lang['Submit'],
				'L_RESET' => $lang['Reset'],
				'L_ALLOW' => $lang['Allow'],
				'L_DENY' => $lang['Deny'],
				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No']
				)
			);

			$template->pparse('body');

		}
		else
		{
			//
			// The data has been submitted, so update it in the DB
			//

			$code_name = ( isset($HTTP_POST_VARS['name']) ) ? $HTTP_POST_VARS['name'] : '';
			$new_code_name = ( isset($HTTP_POST_VARS['new_code_name']) ) ? $HTTP_POST_VARS['new_code_name'] : '';
			$field_name = ( isset($HTTP_POST_VARS['field_name']) ) ? $HTTP_POST_VARS['field_name'] : '';
			$field_desc = ( isset($HTTP_POST_VARS['field_desc']) ) ? $HTTP_POST_VARS['field_desc'] : '';
			$type = ( isset($HTTP_POST_VARS['field_type']) ) ? $HTTP_POST_VARS['field_type'] : '';
			$field_length = intval(( isset($HTTP_POST_VARS['field_length']) ) ? $HTTP_POST_VARS['field_length'] : '');
			$field_values = ( isset($HTTP_POST_VARS['field_values']) ) ? $HTTP_POST_VARS['field_values'] : '';
			$field_regexp = ( isset($HTTP_POST_VARS['regexp']) ) ? $HTTP_POST_VARS['regexp'] : '';
			$default_auth = ( isset($HTTP_POST_VARS['default_auth']) ) ? $HTTP_POST_VARS['default_auth'] : '';
			$display_register = ( isset($HTTP_POST_VARS['display_register']) ) ? $HTTP_POST_VARS['display_register'] : XD_DISPLAY_NORMAL;
			$display_viewprofile = ( isset($HTTP_POST_VARS['display_viewprofile']) ) ? $HTTP_POST_VARS['display_viewprofile'] : XD_DISPLAY_NORMAL;
			$display_posting = ( isset($HTTP_POST_VARS['display_posting']) ) ? $HTTP_POST_VARS['display_posting'] : XD_DISPLAY_NORMAL;
			$handle_input = intval( ( isset($HTTP_POST_VARS['handle_input']) ) ? $HTTP_POST_VARS['handle_input'] : '1' );
			$allow_bbcode = intval( (isset($HTTP_POST_VARS['allow_bbcode']) ) ? $HTTP_POST_VARS['allow_bbcode'] : '1' );
			$allow_smilies = intval( ( isset($HTTP_POST_VARS['allow_smilies']) ) ? $HTTP_POST_VARS['allow_smilies'] : '1' );
   			$allow_html = intval( ( isset($HTTP_POST_VARS['allow_html']) ) ? $HTTP_POST_VARS['allow_html'] : '1' );

			if ( $code_name == '' )
			{
            	message_die(GENERAL_ERROR, 'No field specified.');
			}
			elseif ( ! isset($xd_meta[$code_name]) )
			{
				message_die(GENERAL_ERROR, 'The field you were editing does not exist.');
			}

			if ( strlen($field_regexp) > 0 )
			{
				$check = create_function('$errno, $errstr, $errfile, $errline', 'message_die("GENERAL_ERROR", "You have an error in your regular expression syntax:<br /><br />$errstr");');
				set_error_handler($check);
				$test = preg_match($field_regexp, 'this is a test to see whether the regexp will compile properly');
				restore_error_handler();
			}

			$sql = "UPDATE " . XDATA_FIELDS_TABLE . "
				SET field_name = '" . $field_name . "',
				" . "field_desc = '" . $field_desc . "',
				" . "field_type = '" . $type . "',
				" . "field_length = '" . $field_length . "',
				" . "field_values = '" . $field_values . "',
				" . "field_regexp = '" . $field_regexp . "',
				" . "default_auth = " . $default_auth . ",
				" . "display_register = " . $display_register . ",
				" . "display_viewprofile = " . $display_viewprofile . ",
				" . "display_posting = " . $display_posting . ",
				" . "handle_input = " . $handle_input . ",
				" . "allow_bbcode = " . $allow_bbcode . ",
				" . "allow_smilies = " . $allow_smilies . ",
				" . "allow_html = " . $allow_html . ",
				" . "code_name = '"
				. ( ( ( strlen($new_code_name) > 0 ) && ( $new_code_name != $code_name ) ) ? $new_code_name : $code_name )
				. "'
				WHERE code_name = '" . $code_name . "'";

			if ( ! $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not update field info.', '', __LINE__, __FILE__, $sql);
			}

            $message = $lang['Edit_success'] . "<br /><br />" . sprintf($lang['Click_return_fields'], "<a href=\"" . append_sid("admin_xdata_fields.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");
			message_die(GENERAL_MESSAGE, $message);
		}

		break;

	case 'add':

		if ( ! isset($HTTP_POST_VARS['submit']) )
		{
			//
			// Show the form
			//

			$template->set_filenames( array( 'body' => 'admin/xd_edit_body.tpl' ) );
        	
        	$template->assign_vars(array(
        		'DEFAULT_AUTH_ALLOW_CHECKED' => ' checked="checked"',
        		'AUTH_ALLOW' => XD_AUTH_ALLOW,
        		'AUTH_DENY' => XD_AUTH_DENY,
                'DISPLAY_REGISTER_NORMAL_CHECKED' => ' checked="checked"',
				'DISPLAY_PROFILE_NORMAL_CHECKED' => ' checked="checked"',
				'DISPLAY_POSTING_NORMAL_CHECKED' => ' checked="checked"',
				'HANDLE_INPUT_YES_CHECKED' => ' checked="checked"',
				'ALLOW_BBCODE_YES_CHECKED' => ' checked="checked"',
				'ALLOW_SMILIES_YES_CHECKED' => ' checked="checked"',
				'ALLOW_HTML_NO_CHECKED' => ' checked="checked"',

				'L_BASIC_OPTIONS' => $lang['Basic_Options'],
				'L_ADVANCED_OPTIONS' => $lang['Advanced_Options'],
				'L_ADVANCED_NOTICE' => $lang['Advanced_warning'],
				'L_XDATA_ADMIN' => $lang['add_xdata_field'],
				'L_NAME' => $lang['Name'],
				'L_DESCRIPTION' => $lang['xd_description'],
				'L_TYPE' => $lang['type'],
				'L_TEXT' => $lang['Text'],
				'L_TEXTAREA' => $lang['Text_area'],
				'L_SELECT' => $lang['Select'],
				'L_RADIO' => $lang['Radio'],
				'L_CUSTOM' => $lang['Custom'],
				'L_LENGTH' => $lang['Length'],
				'L_LENGTH_EXPLAIN' => $lang['Length_explain'],
				'L_VALUES' => $lang['Values'],
				'L_VALUES_EXPLAIN' => $lang['Values_explain'],
				'L_DEFAULT_AUTH' => $lang['Default_auth'],
				'L_DEFAULT_AUTH_EXPLAIN' => $lang['Default_auth_explain'],
				'L_ALLOW_BBCODE' => $lang['Allow_BBCode'],
				'L_ALLOW_SMILIES' => $lang['Allow_smilies'],
				'L_ALLOW_HTML' => $lang['Allow_html'],
				'L_DISPLAY_TYPE' => $lang['Display_type'],
				'L_DISPLAY_REGISTER_EXPLAIN' => $lang['Display_register_explain'],
				'L_DISPLAY_PROFILE_EXPLAIN' => $lang['Display_viewprofile_explain'],
				'L_DISPLAY_POSTING_EXPLAIN' => $lang['Display_viewtopic_explain'],
				'L_NORMAL' => $lang['Display_normal'],
				'L_NONE' => $lang['Display_none'],
				'L_ROOT' => $lang['Display_root'],
				'L_CODE_NAME' => $lang['Code_name'],
				'L_CODE_NAME_EXPLAIN' => $lang['Code_name_explain'],
				'L_REGEXP' => $lang['Regexp'],
				'L_REGEXP_EXPLAIN' => $lang['Regexp_explain'],
				'L_HANDLE_INPUT' => $lang['handle_input'],
				'L_HANDLE_INPUT_EXPLAIN' => $lang['handle_input_explain'],
				'L_SUBMIT' => $lang['Submit'],
				'L_RESET' => $lang['Reset'],
				'L_ALLOW' => $lang['Allow'],
				'L_DENY' => $lang['Deny'],
				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No']
				)
			);
			
			$template->pparse('body');
  		}
  		else
  		{
  			//
			// The data has been submitted, so add it to the DB
			//

			$field_name = ( isset($HTTP_POST_VARS['field_name']) ) ? $HTTP_POST_VARS['field_name'] : '';
			$new_code_name = ( isset($HTTP_POST_VARS['new_code_name']) ) ? $HTTP_POST_VARS['new_code_name'] : '';
			$field_desc = ( isset($HTTP_POST_VARS['field_desc']) ) ? $HTTP_POST_VARS['field_desc'] : '';
			$field_type = ( isset($HTTP_POST_VARS['field_type']) ) ? $HTTP_POST_VARS['field_type'] : '';
			$field_length = intval(( isset($HTTP_POST_VARS['field_length']) ) ? $HTTP_POST_VARS['field_length'] : '');
			$field_values = ( isset($HTTP_POST_VARS['field_values']) ) ? $HTTP_POST_VARS['field_values'] : '';
			$field_regexp = ( isset($HTTP_POST_VARS['field_regexp']) ) ? $HTTP_POST_VARS['field_regexp'] : '';
			$default_auth = ( isset($HTTP_POST_VARS['default_auth']) ) ? $HTTP_POST_VARS['default_auth'] : '';
			$display_register = ( isset($HTTP_POST_VARS['display_register']) ) ? $HTTP_POST_VARS['display_register'] : XD_DISPLAY_NORMAL;
			$display_viewprofile = ( isset($HTTP_POST_VARS['display_viewprofile']) ) ? $HTTP_POST_VARS['display_viewprofile'] : XD_DISPLAY_NORMAL;
			$display_posting = ( isset($HTTP_POST_VARS['display_posting']) ) ? $HTTP_POST_VARS['display_posting'] : XD_DISPLAY_NORMAL;
            $handle_input = intval( ( isset($HTTP_POST_VARS['handle_input']) ) ? $HTTP_POST_VARS['handle_input'] : '1' );
			$allow_bbcode = intval( ( isset($HTTP_POST_VARS['allow_bbcode']) ) ? $HTTP_POST_VARS['allow_bbcode'] : '0' );
			$allow_smilies = intval( ( isset($HTTP_POST_VARS['allow_smilies']) ) ? $HTTP_POST_VARS['allow_smilies'] : '0' );
            $allow_html = intval( ( isset($HTTP_POST_VARS['allow_html']) ) ? $HTTP_POST_VARS['allow_html'] : '1' );
            
			if ( strlen($field_regexp) > 0 )
			{
				$check = create_function('$errno, $errstr, $errfile, $errline', 'message_die("GENERAL_ERROR", $lang[\'Regexp_error\']."<br /><br />$errstr");');
				set_error_handler($check);
				$test = preg_match($field_regexp, 'this is a test to see whether the regexp will compile properly');
				restore_error_handler();
			}

			$sql = "SELECT MAX(field_id)+1 AS field_id, MAX(field_order)+1 AS field_order
			        FROM " . XDATA_FIELDS_TABLE;
			        
			if ( !($result = $db->sql_query($sql)) )
			{
            	message_die(GENERAL_ERROR, 'Could not get field_order and field_id for new field.', '', __LINE__, __FILE__, $sql);
			}

			$new_info = $db->sql_fetchrow($result);
			$field_id = $new_info['field_id'];
			$field_order = $new_info['field_order'];
			
			$code_name = ( $new_code_name == '' ) ? "xd_$field_id" : $new_code_name;

            if ( isset($xd_meta[$new_code_name]) )
			{
				message_die(GENERAL_ERROR, $lang['XD_duplicate_name']);
			}

			$sql = "INSERT INTO " . XDATA_FIELDS_TABLE . "
				(field_id, field_name, field_desc, field_type, field_length, field_values, field_regexp, field_order, code_name, default_auth, display_register, display_viewprofile, display_posting, handle_input, allow_bbcode, allow_smilies, allow_html)
				VALUES
				($field_id, '$field_name', '$field_desc', '$field_type', $field_length, '$field_values', '$field_regexp', $field_order, '$code_name', $default_auth, $display_register, $display_viewprofile, $display_posting, $handle_input, $allow_bbcode, $allow_smilies, $allow_html)";

			if ( ! $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, 'Could not insert field info.', '', __LINE__, __FILE__, $sql);
			}

            $message = $lang['Add_success'] . "<br /><br />" . sprintf($lang['Click_return_fields'], "<a href=\"" . append_sid("admin_xdata_fields.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");
			message_die(GENERAL_MESSAGE, $message);
		}

		break;

	case 'delete':
		if ( ! isset($HTTP_POST_VARS['yes']) && ! isset($HTTP_POST_VARS['no']) )
		{
	        //
	        // Show the confirm message
	        //

			if( isset($HTTP_POST_VARS['name']) || isset($HTTP_GET_VARS['name']) )
			{
				$code_name = ( isset($HTTP_POST_VARS['name']) ) ? $HTTP_POST_VARS['name'] : $HTTP_GET_VARS['name'];
			}
			else
			{
				message_die(GENERAL_ERROR, 'No field was specified.');
			}

            if ( !isset($xd_meta[$code_name]) )
			{
				message_die(GENERAL_ERROR, 'The field you tried to delete does not exist.');
			}

			$template->set_filenames(array( 
				'body' => 'admin/xd_confirm_delete.tpl') 
			);

			$template->assign_vars( array(
				'S_HIDDEN_VARS' => '<input type="hidden" name="name" value="' . $code_name . '" /><input type="hidden" name="mode" value="delete" />',
				'U_FORM_ACTION' => append_sid("admin_xdata_fields.$phpEx?name=$name"))
			);

			$template->assign_vars( array(
				'L_CONFIRM' => $lang['Confirm'],
				'L_ARE_YOU_SURE' => sprintf($lang['Are_you_sure'], $xd_meta[$code_name]['field_name']),
				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No'])
			);
			
			$template->pparse('body');
		}
		elseif ( isset($HTTP_POST_VARS['yes'] ) )
		{
			//
			// Already confirmed, so do the delete
			//
			
			if( isset($HTTP_POST_VARS['name']) || isset($HTTP_GET_VARS['name']) )
			{
				$code_name = ( isset($HTTP_POST_VARS['name']) ) ? $HTTP_POST_VARS['name'] : $HTTP_GET_VARS['name'];
			}
			else
			{
				message_die(GENERAL_ERROR, 'No field was specified.');
			}

            if ( ! isset($xd_meta[$code_name]) )
			{
				message_die(GENERAL_ERROR, 'The field you tried to delete does not exist.');
			}

			$sql = "DELETE FROM " . XDATA_DATA_TABLE . "
				WHERE field_id = " . $xd_meta[$code_name]['field_id'];
				
			if ( ! $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Unable to empty existing XData entries for the field.", "", __LINE__, __FILE__, $sql);
			}

            $sql = "DELETE FROM " . XDATA_AUTH_TABLE . "
				WHERE field_id = " . $xd_meta[$code_name]['field_id'];
			if ( ! $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Unable to remove auth data for the field.", "", __LINE__, __FILE__, $sql);
			}

			$sql = "DELETE FROM " . XDATA_FIELDS_TABLE . "
				WHERE code_name = '" . $code_name . "'";
			if ( ! $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Unable to delete the XData field.", "", __LINE__, __FILE__, $sql);
			}
			
			$message = $lang['Delete_success'] . "<br /><br />" . sprintf($lang['Click_return_fields'], "<a href=\"" . append_sid("admin_xdata_fields.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");
			message_die(GENERAL_MESSAGE, $message);

		}
		else
		{
			$mode = 'view';
		}
		
		break;
}

if ($mode == 'view')
{
/*  byte usage feature not implemented yet
	$sql = "SELECT SUM(LENGTH(xd.xdata_value)) AS len, xf.code_name AS code_name
		FROM " . XDATA_DATA_TABLE . " xd, " . XDATA_FIELDS_TABLE . " xf
		WHERE xd.field_id = xf.field_id
		GROUP BY xd.field_id";

	if ( !( $result = $db->sql_query($sql) ) )
	{
		message_die(GENERAL_ERROR, "Couldn't get field byte usage", "", __LINE__, __FILE__, $sql);
	}*/

	$template->set_filenames( array('body' => 'admin/xd_view_body.tpl') );

	if (count($xd_meta) == 0)
	{
		$template->assign_block_vars('switch_no_fields', array());
	}
	else
	{
		while ( list($code_name, $meta) = each($xd_meta) )
		{
        	$template->assign_block_vars('xd_field', array(
				'FIELD_NAME' => $meta['field_name'],
				'FIELD_TYPE' => $meta['field_type'],
				'U_MOVE_UP' => append_sid('admin_xdata_fields.'.$phpEx.'?mode=up&name='.$code_name),
				'U_MOVE_DOWN' => append_sid('admin_xdata_fields.'.$phpEx.'?mode=down&name='.$code_name),
				'U_EDIT' => append_sid('admin_xdata_fields.'.$phpEx.'?mode=edit&name='.$code_name),
				'U_DELETE' => append_sid('admin_xdata_fields.'.$phpEx.'?mode=delete&name='.$code_name),
        		)
        	);
        	
        	if ($meta['field_type'] != 'special')
			{
				$template->assign_block_vars('xd_field.normal', array());
			}
  		}
	}

	$template->assign_vars(array(
		'L_XDATA_ADMIN' => $lang['Users_fields'],
		'L_FORM_DESCRIPTION' => $lang['Xdata_view_description'],
		'L_FIELD_NAME' => $lang['Name'],
		'L_FIELD_TYPE' => $lang['type'],
		'L_MOVE' => $lang['xd_move'],
		'L_OPERATIONS' => $lang['xd_operations'],
		'L_MOVE_UP' => '<img src="' . $phpbb_root_path . $images['acp_up'] . '" alt="' . $lang['Move_up'] . '" title="' . $lang['Move_up'] . '" />',
		'L_MOVE_DOWN' => '<img src="' . $phpbb_root_path . $images['acp_down'] . '" alt="' . $lang['Move_down'] . '" title="' . $lang['Move_down'] . '" />',
		'L_EDIT' => '<img src="' . $phpbb_root_path . $images['acp_edit'] . '" alt="' . $lang['Edit'] . '" title="' . $lang['Edit'] . '" />',
		'L_DELETE' => '<img src="' . $phpbb_root_path . $images['acp_delete'] . '" alt="' . $lang['Delete'] . '" title="' . $lang['Delete'] . '" />',
		'L_NO_FIELDS' => $lang['No_fields'],
		'L_ADD_FIELD' => $lang['Add_field'],

		'U_ADD_FIELD' => append_sid("admin_xdata_fields.$phpEx?mode=add"))
	);
	
	$template->pparse('body');
}

include('./page_footer_admin.'.$phpEx);

?>
