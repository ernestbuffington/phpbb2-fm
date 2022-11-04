<?php
/** 
*
* @package lang_english
* @version $Id: lang_auto_backup.php,v 1.13 2006/01/13 19:35:12 kkroo Exp $
* @copyright (c) 2006 by Omar Ramadan 
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
 
define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
   	$filename = basename(__FILE__);
	$module['Utilities_']['Database_Backup_Auto'] = $filename;

   return;
}

//
// Let's set the root dir for phpBB
//
$no_page_header = TRUE;
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

//
// Include language
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_auto_backup.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_auto_backup.' . $phpEx);


if ($HTTP_GET_VARS['mode'] == 'submit')
{ 
	if ( isset($HTTP_POST_VARS['enable_autobackup']))
	{
		$enable_autobackup = ( isset($HTTP_POST_VARS['enable_autobackup']) ) ? intval($HTTP_POST_VARS['enable_autobackup']) : 0;

		$sql = "UPDATE " . CONFIG_TABLE . "
			SET config_value = " . $enable_autobackup . "
			WHERE config_name = 'enable_autobackup'";
		if ( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, $lang['Error_updating_auto_backup'], '', __LINE__, __FILE__, $sql);
		} 
			
		// Remove cache file
		@unlink($phpbb_root_path . 'cache/config_board.'.$phpEx);

		$message = $lang['Board_config_updated'] . "<br /><br />" . sprintf($lang['Click_return_auto_backup'], "<a href=\"" . append_sid("admin_db_autobackup.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");
	
		message_die(GENERAL_MESSAGE, $message);
	}
	
	if ( $_POST['backup_skill'] == 1)
	{
		$minute = $_POST['advanced_minute'];
		$hour = $_POST['advanced_hour'];
		$day = $_POST['advanced_day'];
		$month = $_POST['advanced_month'];
		$weekday = $_POST['advanced_weekday'];
	}
	else if ( $_POST['backup_skill'] == 0)
	{
		$minute = $_POST['minute'];
		$hour = $_POST['hour'];
		$day = $_POST['day'];
		$month = $_POST['month'];
		$weekday = $_POST['weekday'];
	}

	// Define Cronjob when line
	$when = "{$minute}    {$hour}    {$day }    {$month}    {$weekday}";

	// Define email
	$email_true = ( $_POST['email_true'] ) ? 1 : 0;
	$email = $_POST['email'];
	
	// Define delay time
	$delay_time = $_POST['delay_time'];

	// Define Backup types
	$backup_type = $_POST['backup_type'];
	$phpbb_only  = ( $_POST['phpbb_only'] ) ? 1 : 0;
	$no_search  = ( $_POST['no_search'] ) ? 1: 0;
	$ignore_tables = $_POST['ignore_tables'];
	
	// Define Backup skill
	$skill_level = $_POST['backup_skill'];
	
	// Define ftp
	$ftp_true = ( $_POST['ftp_true'] ) ? 1 : 0;
	$ftp_server  = $_POST['ftp_server'];
	$ftp_user_name  = $_POST['ftp_user_name'];
	$ftp_user_pass  = base64_encode($_POST['ftp_user_pass']);
	$ftp_directory  = $_POST['ftp_directory'];
	
	//Define save to backups directory
	$write_backups_true = ( $_POST['write_backups_true'] ) ? 1 : 0;
	$files_to_keep = $_POST['files_to_keep']; 
	
	// Submit to DB
	$sql = "UPDATE " . BACKUP_TABLE . " 	
		SET backup_skill = " . $skill_level . ", email_true = '" . $email_true . "', email = '" . $email . "', ftp_true = '" . $ftp_true . "', ftp_server = '" . $ftp_server . "', ftp_user_name = '" . $ftp_user_name . "', ftp_user_pass = '" . $ftp_user_pass . "', ftp_directory = '" . $ftp_directory . "', write_backups_true = '" . $write_backups_true . "', files_to_keep = '" . $files_to_keep . "', cron_time = '" . $when . "', backup_type = '" . $backup_type . "', phpbb_only = " . $phpbb_only . ", no_search = '" . $no_search . "', ignore_tables = '" . $ignore_tables . "', delay_time = '" . $delay_time . "'";
	if ( !$db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, $lang['Error_updating_auto_backup'], '', __LINE__, __FILE__, $sql);
	} 

	$message = $lang['Board_config_updated'] . "<br /><br />" . sprintf($lang['Click_return_auto_backup'], "<a href=\"" . append_sid("admin_db_autobackup.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

	message_die(GENERAL_MESSAGE, $message);
}


// Get info from backup table
$sql = "SELECT *
	FROM " . BACKUP_TABLE;
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, $lang['Error_retrieving_auto_backup'], '', __LINE__, __FILE__, $sql);
}
$backup_table = $db->sql_fetchrow($result);
$db->sql_freeresult($result);

$cron_time = explode('    ', $backup_table['cron_time']);
$delay_time = $backup_table['delay_time'];

switch( $backup_table['backup_type'] ) 
{
     case 'full': 
     	$backup_type_full = 'checked="checked"'; 
     	break;
	 case 'structure': 
	 	$backup_type_structure = 'checked="checked"'; 
	 	break;
     case 'data': 
     	$backup_type_data = 'checked="checked"';
     	break;
}

$skill_basic = ( !$backup_table['backup_skill'] ) ? 'checked="checked"' : '';
$skill_advanced = ( $backup_table['backup_skill'] ) ? 'checked="checked"' : '';

$no_search = (!empty($backup_table['no_search'])) ? 'checked="checked"' : '';
$phpbb_only = (!empty($backup_table['phpbb_only'])) ? 'checked="checked"' : '';
$ftp_true = (!empty($backup_table['ftp_true'])) ? 'checked="checked"' : '';
$email_true = (!empty($backup_table['email_true'])) ? 'checked="checked"' : '';
$write_backups_true = (!empty($backup_table['write_backups_true'])) ? 'checked="checked"' : '';

$enable_autobackup_yes = ( $board_config['enable_autobackup'] ) ? 'checked="checked"' : ''; 
$enable_autobackup_no = ( !$board_config['enable_autobackup'] ) ? 'checked="checked"' : ''; 

$template->set_filenames(array(
   'body' => 'admin/utils_autobackup_body.tpl')
);

$template->assign_vars(array(
	'FORM_ACTION' => append_sid("admin_db_autobackup.$phpEx?mode=submit"),
    'L_AUTOMATIC_BACKUP' => $lang['Automatic_Backup'],
    'L_BACKUP_EXPLAIN' => $lang['Automatic_Backup_Explain'],
    'L_FORM_EXPLAIN' => $lang['Automatic_Backup_Form_Explain'],
    
    'L_BACKUP_TYPE' => $lang['Backup_type'],
	'L_FULL_BACKUP' => $lang['Full_backup'],
    'L_STRUCTURE_BACKUP' => $lang['Structure_backup'],
    'L_DATA_BACKUP' => $lang['Data_backup'],
	'L_PHPBB_ONLY' => $lang['phpBB_only'],
	'L_NO_SEARCH' => $lang['No_Search'],
	'L_IGNORE_TABLES' => $lang['Ignore_tables'],
	'L_IGNORE_TABLES_EXPLAIN' => $lang['Ignore_tables_explain'],
	'L_ADVANCED_BACKUP' => $lang['auto_backup_advanced_user'],
	'L_ADVANCED_BACKUP_EXPLAIN' => $lang['auto_backup_advanced_user_explain'],
	'L_BASIC_BACKUP' => $lang['auto_backup_basic_user'],	  
	'L_LEVEL' => $lang['auto_backup_level'],	
	'L_BACKUP_TYPE' => $lang['Backup_type'],
	'L_EMAIL' => $lang['Email_Address'],
	'L_DELAY_TIME' => $lang['Delay_time'],

	'L_MINUTES' => $lang['Minutes'],
	'L_EVERY_MINUTE' => $lang['Every_Minute'],
	'L_EVERY_OTHER_MINUTES' => $lang['Every_Other_Minutes'],
	'L_EVERY_FIVE_MINUTES' => $lang['Every_Five_Minutes'],
	'L_EVERY_TEN_MINUTES' => $lang['Every_Ten_Minutes'],
	'L_EVERY_FIFTEEN_MINUTES' => $lang['Every_Fifteen_Minutes'],
	'L_HOURS' => $lang['Hours'],
	'L_EVERY_HOUR' => $lang['Every_Hour'],
	'L_EVERY_OTHER_HOUR' => $lang['Every_Other_Hour'],
	'L_EVERY_FOUR_HOURS' => $lang['Every_Four_Hours'],
	'L_EVERY_SIX_HOURS' => $lang['Every_Six_Hours'],
	'L_MIDNIGHT' => $lang['Midnight'],
	'L_NOON' => $lang['Noon'],
	'L_DAYS' => $lang['Days'],
	'L_EVERY_DAY' => $lang['Every_Day'],
		
	'L_MONTHS' => $lang['Months'],
	'L_EVERY_MONTH' => $lang['Every_Month'],
	'L_JANUARY' => $lang['datetime']['January'],
	'L_FEBRUARY' => $lang['datetime']['February'],
	'L_MARCH' => $lang['datetime']['March'],
	'L_APRIL' => $lang['datetime']['April'],
	'L_MAY' => $lang['datetime']['May'],
	'L_JUNE' => $lang['datetime']['June'],
	'L_JULY' => $lang['datetime']['July'],
	'L_AUGUST' => $lang['datetime']['August'],
	'L_SEPTEMBER' => $lang['datetime']['September'],
	'L_OCTOBER' => $lang['datetime']['October'],
	'L_NOVEMBER' => $lang['datetime']['November'],
	'L_DECEMBER' => $lang['datetime']['December'],
		
	'L_WEEKDAYS' => $lang['Weekdays'],
	'L_EVERY_WEEKDAY' => $lang['Every_Weekday'],
	'L_SUNDAY' => $lang['datetime']['Sunday'],
	'L_MONDAY' => $lang['datetime']['Monday'],
	'L_TUESDAY' => $lang['datetime']['Tuesday'],
	'L_WEDNESDAY' => $lang['datetime']['Wednesday'],
	'L_THURSDAY' => $lang['datetime']['Thursday'],
	'L_FRIDAY' => $lang['datetime']['Friday'],
	'L_SATURDAY' => $lang['datetime']['Saturday'],
		
	'L_EMAIL_TRUE' => $lang['Email_true'],
	'L_EMAIL_TRUE_EXPLAIN' => $lang['Email_true_explain'],
	'L_FTP_TRUE' => $lang['FTP_true'],
	'L_FTP_SERVER' => $lang['FTP_server'],
	'L_FTP_USER' => $lang['FTP_user_name'],
	'L_FTP_PASS' => $lang['FTP_user_pass'],
	'L_FTP_DIRECTORY' => $lang['FTP_directory'],
	'L_WRITE_BACKUPS_TRUE' => $lang['write_backups_true'],
	'L_FILES_TO_KEEP' => $lang['files_to_keep'],
	'L_FILES_TO_KEEP_EXPLAIN' => $lang['files_to_keep_explain'],

	'L_CONFIGURATION' => $lang['Configuration'],
	'L_ENABLE_AUTOBACKUP' => $lang['Enable_autobackup'],
	'ENABLE_AUTOBACKUP_YES' => $enable_autobackup_yes,
	'ENABLE_AUTOBACKUP_NO' => $enable_autobackup_no,
		
	'PHPBB_ONLY_YES' => $phpbb_only,
	'MINUTES' => $cron_time[0],
	'HOURS' => $cron_time[1],
	'DAYS' => $cron_time[2],
	'MONTHS' => $cron_time[3],
	'WEEKDAYS' => $cron_time[4],
	'DELAY_TIME' => $backup_table['delay_time'],
	'EMAIL_TRUE' => $email_true,
	'EMAIL' => $backup_table['email'],
	'PHPBB_ONLY' => $phpbb_only,
	'NO_SEARCH' => $no_search,
	'IGNORE_TABLES' => $backup_table['ignore_tables'],
	'FULL_BACKUP' => $backup_type_full,
	'STRUCTURE_BACKUP' => $backup_type_structure,
	'DATA_BACKUP' => $backup_type_data,
	'FTP_TRUE' => $ftp_true,
	'FTP_SERVER' => $backup_table['ftp_server'],
	'FTP_USER' => $backup_table['ftp_user_name'],
	'FTP_PASS' => base64_decode($backup_table['ftp_user_pass']),
	'FTP_DIRECTORY' => $backup_table['ftp_directory'],
	'WRITE_BACKUPS_TRUE' => $write_backups_true,
	'FILES_TO_KEEP' => $backup_table['files_to_keep'],
	'SKILL_BASIC' => $skill_basic,
	'SKILL_ADVANCED' => $skill_advanced)
);

include('./page_header_admin.'.$phpEx);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>