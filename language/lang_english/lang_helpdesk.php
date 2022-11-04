<?php
/** 
*
* @package lang_english
* @version $Id: lang_helpdesk.php,v 1.0.0 2004 austin Exp $
* @copyright (c) 2003 aUsTiN-Inc
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	

// Main	
$lang['Helpdesk_main_title'] = 'Send a helpdesk message';
$lang['Helpdesk_main_select_1'] = 'Department';
$lang['Helpdesk_main_select_1_explain'] = 'What is this enquiry in reference to';
$lang['Helpdesk_main_select_2'] = 'Importance';
$lang['Helpdesk_main_select_2_explain'] = 'How quickly would you like a reply';
$lang['Helpdesk_main_select_3'] = 'Reply contact method';
$lang['Helpdesk_main_select_3_explain'] = 'Please include the ID for the method</i>';
$lang['Helpdesk_main_id'] = 'Reply method ID';
$lang['Helpdesk_email_failed'] = 'Sorry, your e-mail enquiry failed.';
$lang['Helpdesk_email_department'] = 'Send to department';
$lang['Helpdesk_email_username'] = 'Senders username';
$lang['Helpdesk_email_ip'] = 'Senders IP address';
$lang['Helpdesk_email_reply_method'] = 'Requested reply method';
$lang['Helpdesk_email_reply_id'] = 'Requested reply ID';
$lang['Helpdesk_email_importance'] = 'Message importance';
$lang['Helpdesk_email_time'] = 'Message sent on';
$lang['Helpdesk_email_message'] = 'Senders message';

// Admin
$lang['Helpdesk_admin_sub_title_1'] = 'Current contact e-mail addresses';
$lang['Helpdesk_admin_sub_title_2'] = 'Current importance choices';
$lang['Helpdesk_admin_sub_title_3'] = 'Current reply choices';
$lang['Helpdesk_admin_contact_em'] = 'Add a contact e-mail address';
$lang['Helpdesk_admin_con_em_ex'] = 'ie. forums@yourdomain.com';
$lang['Helpdesk_admin_contact_sc'] = 'Add a contact shortcut';
$lang['Helpdesk_admin_con_em_sc'] = 'ie. Forum Issues';
$lang['Helpdesk_admin_sub_title_4'] = 'Edit a contact e-mail address';
$lang['Helpdesk_admin_sub_title_5'] = 'Delete a contact e-mail address';
$lang['Helpdesk_admin_edit_two'] = 'E-mail Shortcut';

$lang['Helpdesk_admin_email_short_failed'] = 'You must add both an e-mail and shortcut before saving.';
$lang['Helpdesk_admin_email_failed'] = 'Could not modify E-mail Address.';
$lang['Helpdesk_admin_email_exists'] = 'That e-mail address already exists in the database.';
$lang['Helpdesk_admin_email_deleted'] = 'E-mail Address Deleted Successfully.';
$lang['Helpdesk_admin_email_updated'] = 'E-mail Address Updated Successfully.';
$lang['Helpdesk_admin_email_added'] = 'E-mail Address %s Added Successfully.';

$lang['Click_return_admin_helpdesk'] = 'Click %sHere%s to return to Help Desk Configuration';

?>