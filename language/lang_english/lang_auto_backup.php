<?php
/** 
*
* @package lang_english
* @version $Id: lang_auto_backup.php,v 1.13 2005/11/08 19:35:12 kkroo Exp $
* @copyright (c) 2002 by Omar Ramadan 
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

$lang['Automatic_Backup_Explain'] = 'From this panel you can edit settings for your automatic database backup, to use the basic time setup, select Basic Syntax, if you want to use the advanced field, select Advanced Syntax. Here you can specify when you want the database to backup, and the e-mail address to send it to.';
$lang['Automatic_Backup_Form_Explain'] = 'For the e-mail address field, it is recommended you use an e-mail account that is not on the same server, and has a large quota, because the database file may be very large. The more complicated time you set below, the longer it will take to load.';
$lang['Enable_autobackup'] = 'Enable ' . $lang['Automatic_Backup'];
$lang['Click_return_auto_backup'] = 'Click %sHere%s to return to Automatic Backup Configuration';

$lang['Error_updating_auto_backup'] = 'Error Updating SQL Backup data';
$lang['auto_backup_advanced_user'] = 'Advanced Syntax';
$lang['auto_backup_advanced_user_explain'] = 'This is a web interface to the crontab program. For example, * * * * * would mean every min and 0 0 * * * would mean at midnight of every day.';
$lang['auto_backup_basic_user'] = 'Basic Syntax';
$lang['auto_backup_level'] = 'Backup skill level';
$lang['Backup_type'] = 'Backup Type';
$lang['phpBB_only'] = 'Only phpBB related tables';
$lang['No_Search'] = 'Exclude Search tables';
$lang['Ignore_tables'] = 'Exclude Additional tables';
$lang['Ignore_tables_explain'] = 'Separate additional table names with a comma.';
$lang['Email_Address'] = 'E-mail Address';
$lang['Email_true'] = 'E-mail backups';
$lang['Email_true_explain'] = 'Separate additional e-amil address with a comma.';
$lang['Delay_time'] = 'Delay Time';

// Minutes
$lang['Minutes'] = 'Minutes';
$lang['Every_Minute'] = 'Every Minute';
$lang['Every_Other_Minutes']= 'Every Other Minute';
$lang['Every_Five_Minutes']= 'Every Five Minutes';
$lang['Every_Ten_Minutes']= 'Every Ten Minutes';
$lang['Every_Fifteen_Minutes']= 'Every Fifteen Minutes';
$lang['Hours'] = 'Hours';
$lang['Every_Hour'] = 'Every Hour';
$lang['Every_Other_Hour']= 'Every Other Hour';
$lang['Every_Four_Hours']= 'Every Four Hours';
$lang['Every_Six_Hours']= 'Every Six Hours';
$lang['Midnight']= 'Midnight';
$lang['Noon']= 'Noon';
$lang['Days'] = 'Day';
$lang['Every_Day'] = 'Every Day';
$lang['Months'] = 'Months';
$lang['Every_Month'] = 'Every Month';
$lang['Weekdays'] = 'Weekdays';
$lang['Every_Weekday'] = 'Every Weekday';

//FTP
$lang['FTP_true'] = 'Save backups to FTP server';
$lang['FTP_server'] = 'FTP Server';
$lang['FTP_user_name'] = 'FTP User Name';
$lang['FTP_user_pass'] = 'FTP User Password';
$lang['FTP_directory'] = 'FTP Directory';

// Write backups to backups directory
$lang['write_backups_true'] = 'Save to backup directory';
$lang['files_to_keep'] = 'Backups to keep';
$lang['files_to_keep_explain'] = 'Total number of backups kept on the server. -1 to keep all backups.';

$lang['Error_updating_auto_backup'] = 'Error Updating SQL Backup data';
$lang['Error_retrieving_auto_backup'] = 'Error retrieving SQL Backup data';
$lang['Error_enabling_disabling_board'] = 'Error enabling or disabling forum';
$lang['error_email_subject'] = 'An error occured while backing up your database';
$lang['auto_backup_email_message'] = 'Your database has been backed up successfully on ';
$lang['auto_backup_email_subject'] = 'Database Backup ';
$lang['file_saved_to_backups'] = 'The file has been saved to %s';

// FTP email messages
$lang['Current_directory'] = 'Current directory';
$lang['Change_directory_to'] = 'Changing directory to';
$lang['Couldnt_change_directory'] = 'Couldn\'t change directory';
$lang['Creating_directory'] = 'Creating Directory';
$lang['Created_directory'] = 'Created Directory';
$lang['Cant_Create_directory'] = 'Couldn\'t create directory';
$lang['FTP_upload_failed'] = 'FTP upload has failed';
$lang['FTP_connection_failed'] = 'FTP connection has failed';
$lang['FTP_file_information'] = 'FTP file information';
$lang['Uploaded_file_to'] = 'Uploaded %s to ';

?>