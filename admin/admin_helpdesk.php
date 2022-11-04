<?php                          						   			  
/** 
*
* @package admin
* @version $Id: admin_helpdesk.php,v 1.0.0 2004/11/18 17:49:33 acydburn Exp $
* @copyright (c) 2003 aUsTiN-Inc
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
//	$module['General']['Help_Desk'] = $file;
	return;
}

//
// Load default header
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

//
// Include language
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_helpdesk.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_helpdesk.' . $phpEx);


//
// Mode setting
//
if( isset( $HTTP_POST_VARS['mode'] ) || isset( $HTTP_GET_VARS['mode'] ) )
{
	$mode = ( isset( $HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode); 
}
else
{
	$mode = '';
}

$link = append_sid('admin_helpdesk.'.$phpEx);
	
if( $mode == "main" || !$mode )
{
	echo $usercom_menu . '
		</ul>
</div></td>
<td valign="top" width="78%">	
	
	<h1>' . $lang['Helpdesk'] . ' ' . $lang['Setting'] . '</h1>
	<p>' . sprintf($lang['Config_explain'], $lang['Helpdesk']) . '</p>
	<table width="100%" class="forumline" cellspacing="1" cellpadding="4" align="center">
	<tr>
		<th class="thHead" colspan="2">' . $lang['Helpdesk'] . ' ' . $lang['Setting'] . '</th>
	</tr>
	<tr>
		<td width="38%" class="row1"><b>' . $lang['Helpdesk_admin_sub_title_1'] . ':</b></td>
		<td class="row2"><select>';	
		
	$q = "SELECT e_addr
		  FROM " . HELPDESK_E ."
		  WHERE e_id <> 0";
	$r			= $db -> sql_query($q);
	while($row 	= $db -> sql_fetchrow($r))
	{	
		$val = $row['e_addr'];
		echo '<option>' . $val . '</option>';					
	}
			
	echo '</select></td>
	</tr>
	<tr>
		<td class="row1"><b>' . $lang['Helpdesk_admin_sub_title_2'] . ':</b></td>
		<td class="row2"><select>';	

	$q = "SELECT *
		  FROM " . HELPDESK_I ."
		  WHERE value <> 0";
	$r			= $db -> sql_query($q);
	while($row 	= $db -> sql_fetchrow($r))
	{	
		$val = $row['data'];
		$id	= $row['value'];
		echo '<option>' . $id . ' - ' . $val . '</option>';					
	}
			
	echo '</select></td>
	</tr>	
	<tr>
		<td class="row1"><b>' . $lang['Helpdesk_admin_sub_title_3'] . ':</b></td>
		<td class="row2"><select>';	
	
	$q = "SELECT data
		  FROM " . HELPDESK_R ."
		  WHERE value <> 0";
	$r			= $db -> sql_query($q);
	while($row 	= $db -> sql_fetchrow($r))
	{	
		$val = $row['data'];
		echo '<option>' . $val . '</option>';					
	}
			
	echo '</select></td>
	</tr>	
	<tr>	
		<form name="add_email" action="' . append_sid('admin_helpdesk.'.$phpEx) . '" method="post">	
		<td class="row1"><b>' . $lang['Helpdesk_admin_contact_em'] . ':</b><br /><span class="gensmall">' . $lang['Helpdesk_admin_con_em_ex'] . '</span></td>
		<td class="row2"><input name="new_em" type="text" size="25" value="" class="post" /></td>
	</tr>
	<tr>
		<td class="row1"><b>' . $lang['Helpdesk_admin_contact_sc'] . ':</b><br /><span class="gensmall">' . $lang['Helpdesk_admin_con_em_sc'] . '</span></td>
		<td class="row2"><input name="new_em_s" type="text" size="25" value="" class="post"> &nbsp;<input type="hidden" name="mode" value="add_new_em"><input type="submit" class="liteoption" value="' . $lang['Add_new'] . '" onchange="document.add_email.submit()"></td>
		</form>
	</tr>
	<tr>
		<form name="e_email" action="' . append_sid('admin_helpdesk.'.$phpEx) . '" method="post">		
		<td class="row1"><b>' . $lang['Helpdesk_admin_sub_title_4'] . ':</b></td>
		<td class="row2"><select name="edit_email">';	

	$q = "SELECT *
		  FROM " . HELPDESK_E ."
		  WHERE e_id <> 0";
	$r = $db -> sql_query($q);
	while($row 	= $db -> sql_fetchrow($r))
	{	
		$val = $row['e_addr'];
		$id = $row['e_id'];
		echo '<option value="' . $id . '">' . $val . '</option>';
	}
			
	echo '</select> &nbsp;<input type="hidden" name="mode" value="edit_em"><input type="submit" class="liteoption" value="' . $lang['Edit'] . '" onchange="document.e_email.submit()"></td>
	</form>
	</tr>
	<tr>
		<form name="d_email" action="' . append_sid('admin_helpdesk.'.$phpEx) . '" method="post">		
		<td class="row1"><b>' . $lang['Helpdesk_admin_sub_title_5'] . ':</b></td>
		<td class="row2"><select name="edit_email">';	

	$q = "SELECT *
		FROM " . HELPDESK_E ."
		  WHERE e_id <> 0";
	$r = $db -> sql_query($q);
	while($row 	= $db -> sql_fetchrow($r))
	{	
		$val = $row['e_addr'];
		$id = $row['e_id'];
		echo '<option value="' . $id . '">' . $val . '</option>';					
	}
			
	echo '</select> &nbsp;<input type="hidden" name="mode" value="delete_em"><input type="submit" class="liteoption" value="' . $lang['Delete'] . '" onchange="document.d_email.submit()"></td>
	</form>					
	</tr>
	</table>';
}
	
if( $mode == 'delete_em')
{
	$delete = $_POST['edit_email'];
		
	if( !$delete )
	{
		$message = $lang['Helpdesk_admin_email_failed'] . "<br /><br />" . sprintf($lang['Click_return_admin_helpdesk'], "<a href=\"" . append_sid("admin_helpdesk.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message);
	}
			
	$q = "DELETE FROM " . HELPDESK_E ."
		WHERE e_id = '$delete'";
	$r = $db -> sql_query($q);

		$message = $lang['Helpdesk_admin_email_deleted'] . "<br /><br />" . sprintf($lang['Click_return_admin_helpdesk'], "<a href=\"" . append_sid("admin_helpdesk.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message);
}
		
if( $mode == 'edit_em')
{
	$to_edit = $_POST['edit_email'];
	
	if( !$to_edit )
	{
		$message = $lang['Helpdesk_admin_email_failed'] . "<br /><br />" . sprintf($lang['Click_return_admin_helpdesk'], "<a href=\"" . append_sid("admin_helpdesk.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message);
	}

	$q = "SELECT e_addr
		FROM " . HELPDESK_E ."
		WHERE e_id = '$to_edit'";
	$r = $db -> sql_query($q);
	$row = $db -> sql_fetchrow($r);	
	$editing = $row['e_addr'];
	
	$q = "SELECT e_msg
		FROM " . HELPDESK_M ."
		WHERE e_id = '$to_edit'";
	$r = $db -> sql_query($q);
	$row = $db -> sql_fetchrow($r);	
	$message = $row['e_msg'];				
			
	echo $usercom_menu . '
		</ul>
</div></td>
<td valign="top" width="78%">	
		
	<h1>' . $lang['Edit'] . ' ' . $lang['Helpdesk'] . '</h1>
		
	<p>' . sprintf($lang['Config_explain'], $lang['Helpdesk']) . '</p>
		
	<table width="100%" class="forumline" cellspacing="1" cellpadding="4" align="center"><form name="save_email" action="' . append_sid('admin_helpdesk.'.$phpEx) . '" method="post">
	<tr>
		<th class="thHead" colspan="2">' . $lang['Edit'] . ' ' . $lang['Help_Desk'] . '</th>
	</tr>
	<tr>
		<td width="38%" class="row1"><b>' . $lang['Email_address'] . ':</b></td>
		<td class="row2"><input name="save_em" type="text" size="35" value="' . $editing . '" class="post" /></td>
	</tr>
	<tr>
		<td class="row1"><b>' . $lang['Helpdesk_admin_edit_two'] . ':</b></td>
		<td class="row2"><input name="save_ems" type="text" size="35" value="' . $message . '" class="post" /></td>
	</tr>
	<tr>
		<td align="center" class="catBottom" colspan="2"><input type="hidden" name="mode" value="save_new_em"><input type="hidden" name="id" value="' . $to_edit . '"><input type="submit" class="mainoption" value="' . $lang['Submit'] . '" onchange="document.save_email.submit()" />&nbsp;&nbsp;<input type="reset" name="rest" value="' . $lang['Reset'] . '" class="liteoption" /></td>
	</tr>					
	</form></table>';
}
		
if( $mode == 'save_new_em' )
{
	$em = $_POST['save_em'];
	$es = $_POST['save_ems'];
	$id = $_POST['id'];
	
	if( !$em || !$es )
	{
		$message = $lang['Helpdesk_admin_email_short_failed'] . "<br /><br />" . sprintf($lang['Click_return_admin_helpdesk'], "<a href=\"" . append_sid("admin_helpdesk.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message);
	}
			
	$q = "SELECT e_addr
		FROM " . HELPDESK_E ."
		WHERE e_addr = '$em'";
	$r = $db -> sql_query($q);
	$row = $db -> sql_fetchrow($r);	
	$exists = $row['e_addr'];
	
	if( $exists )
	{
		$message = $lang['Helpdesk_admin_email_exists'] . "<br /><br />" . sprintf($lang['Click_return_admin_helpdesk'], "<a href=\"" . append_sid("admin_helpdesk.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message);
	}			
			
	$q = "UPDATE " . HELPDESK_E ."
		SET e_addr = '$em'
		WHERE e_id = '$id'";
	$r = $db -> sql_query($q);
	
	$q = "UPDATE " . HELPDESK_M ."
		SET e_msg = '$es'
		WHERE e_id = '$id'";
	$r = $db -> sql_query($q);

	$message = $lang['Helpdesk_admin_email_updated'] . "<br /><br />" . sprintf($lang['Click_return_admin_helpdesk'], "<a href=\"" . append_sid("admin_helpdesk.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

	message_die(GENERAL_MESSAGE, $message);
}
		
if( $mode == 'add_new_em' )
{
	$new = $_POST['new_em'];
	$srt = $_POST['new_em_s'];

	if( !$new || !$srt )
	{
		$message = $lang['Helpdesk_admin_email_short_failed'] . "<br /><br />" . sprintf($lang['Click_return_admin_helpdesk'], "<a href=\"" . append_sid("admin_helpdesk.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message);
	}
					
	if( $new )
	{
		$q = "SELECT e_addr
			FROM " . HELPDESK_E ."
		  	WHERE e_addr = '$new'";
		$r	= $db -> sql_query($q);
		$row = $db -> sql_fetchrow($r);	
		$exists = $row['e_addr'];
	
		if( $exists )
		{
			$message = $lang['Helpdesk_admin_email_exists'] . "<br /><br />" . sprintf($lang['Click_return_admin_helpdesk'], "<a href=\"" . append_sid("admin_helpdesk.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);
		}
				
		$q = "SELECT MAX(e_id) AS next
			FROM " . HELPDESK_E ."";
		$r = $db -> sql_query($q);
		$row = $db -> sql_fetchrow($r);	
		$next = $row['next'] + 1;
					
		$q = "INSERT INTO " . HELPDESK_E ."
			VALUES ('$next', '$new')";
		$r = $db -> sql_query($q);
			
		$q = "INSERT INTO " . HELPDESK_M ."
		  	VALUES ('$next', '$srt')";
		$r = $db -> sql_query($q);
		
		$message = sprintf($lang['Helpdesk_admin_email_added'], $new) . "<br /><br />" . sprintf($lang['Click_return_admin_helpdesk'], "<a href=\"" . append_sid("admin_helpdesk.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

		message_die(GENERAL_MESSAGE, $message);
	}
}	
	
echo '<br /><div align="center" class="copyright">Helpdesk 1.0.1 &copy; 2003, ' . date('Y') . ' <a href="mailto:austin_inc@hotmail.com" class="copyright" target="_blank">aUsTiN</a></div>';
	
include('page_footer_admin.' . $phpEx);

?>