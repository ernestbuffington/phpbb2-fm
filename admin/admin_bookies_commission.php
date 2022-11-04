<?php
/** 
*
* @package admin
* @version $Id: admin_bookies_commission.php,v 3.0.0 2004/11/17 17:49:33 majorflam Exp $
* @copyright (c) 2004 Majorflam
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

//
// First we do the setmodules stuff for the admin cp.
//
if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Bookmakers']['Commission'] = $filename;

	return;
}

//
// Load default header
//
$no_page_header = TRUE;
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

//
// Output the authorisation details
//
$template->set_filenames(array(
	'body' => 'admin/admin_bookies_commission.tpl')
);

//
// Include language
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_bookmakers.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_admin_bookmakers.' . $phpEx);

//
// Retrieve the data
//
$commission=array();
$bets=array();
$un_commission=array();
$un_bets=array();
$setter=array();

$sql=" SELECT * FROM " . BOOKIE_BET_SETTER_TABLE;
if ( !$result=$db->sql_query($sql) )
{ 
	message_die(GENERAL_ERROR, 'Error gathering data', '', __LINE__, __FILE__, $sql); 
}
while ( $row=$db->sql_fetchrow($result) )
{
	$key=$row['setter'];
	$commission[$key]=( $row['paid'] ) ? $commission[$key]+$row['commission'] : $commission[$key];
	$un_commission[$key]=( !$row['paid'] ) ? $un_commission[$key]+$row['commission'] : $un_commission[$key];
	$bets[$key]=( $row['paid'] ) ? $bets[$key]+1 : $bets[$key];
	$un_bets[$key]=( !$row['paid'] ) ? $un_bets[$key]+1 : $un_bets[$key];
}
arsort($commission);
$keys=array_keys($commission);
for ( $i=0; $i<count($commission); $i++ )
{
	$key=$keys[$i];
	//
	// parse details to the template
	//
	$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2']; 
	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
		
	//
	// grab username
	//
	$sql_a=" SELECT username FROM " . USERS_TABLE . "
	WHERE user_id=$key
	";
	if ( !$result_a=$db->sql_query($sql_a) )
	{ 
		message_die(GENERAL_ERROR, 'Error gathering data', '', __LINE__, __FILE__, $sql_a); 
	}
	$row_a=$db->sql_fetchrow($result_a);
	$name=$row_a['username'];
		
	$template->assign_block_vars('commission', array(
	'ROW_COLOR' => '#' . $row_color, 
	'ROW_CLASS' => $row_class,
	'POS' => $i+1,
	'NAME' => $name,
	'NUM_BETS' => number_format($bets[$key]),
	'COMMISSION' => number_format($commission[$key]),
	'UN_NUM_BETS' => '<i>' . number_format($un_bets[$key]) . '</i>',
	'UN_COMMISSION' => '<i>' . number_format($un_commission[$key]) . '</i>',
	));
}
$rowspan=$i+2;

// Set template Vars
$template->assign_vars(array(
'COMM_HEADER' => $lang['bookie_comm_head'],
'COMM_EXPLAIN' => $lang['bookie_comm_head_exp'],
'COM_NAME' => $lang['Username'],
'COM_NUM_BETS' => $lang['bookie_comm_num_bets'],
'COM_COMMISSION_PAID' => $lang['bookie_commission'],
'ROWSPAN_VAL' => $rowspan,
'USER' => $lang['bookie_comm_user'],
'COM_COMMISSION_PENDING' => $lang['bookie_commission_pending'],
'COM_COMMISSION_DUE' => $lang['bookie_commission_due'],
'BOOKIE_VERSION' => $board_config['bookie_version'],
));

include('./page_header_admin.'.$phpEx);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>