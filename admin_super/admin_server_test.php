<?php
/** 
*
* @package admin
* @version $Id: admin_server_test.php,v 1.5.0 2002/02/09 dwing Exp $
* @copyright (c) 2002 Dimitri Seitz
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if(	!empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Utilities_']['ST_default'] = "$file";
	return;
}

//
// Load default header
//
$phpbb_root_path = "../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

$test = ( !empty($HTTP_POST_VARS['test']) ) ? $HTTP_POST_VARS['test'] : $HTTP_GET_VARS['test'];
$test = intval($test);
$test1 = ( !empty($HTTP_POST_VARS['fwrite']) ) ? $HTTP_POST_VARS['fwrite'] : $HTTP_GET_VARS['fwrite'];
$test1 = intval($test1);
$test2 = ( !empty($HTTP_POST_VARS['write']) ) ? $HTTP_POST_VARS['write'] : $HTTP_GET_VARS['write'];
$test2 = intval($test2);
$test3 = ( !empty($HTTP_POST_VARS['time']) ) ? $HTTP_POST_VARS['time'] : $HTTP_GET_VARS['time'];

if ($test == 1)
{
	if (!$fp = @fopen($phpbb_root_path . 'cache/test.dat', 'a+'))
	{ 
		$server_write = $lang['ST_write_not'];
	}
	else
	{
		$fwrite = 1;
		@fwrite($fp, '');
		@fclose($fp);
		$server_write = $lang['ST_write_can'];
		$write = include_once($phpbb_root_path . 'cache/test.dat');
		unlink($phpbb_root_path . 'cache/test.dat');
	}
	
	message_die(GENERAL_MESSAGE, sprintf($lang['ST_test_success'], 1) . '<br /><br />' . $server_write . '<br /><br />' . sprintf($lang['ST_next_test'], '<a href="' . append_sid('admin_server_test.'.$phpEx.'?test=2&amp;fwrite=' . $fwrite . '&amp;write=' . $write) . '">', '</a>'));
}
else if ($test == 2)
{
	//
	// Duration for performance test
	//
	list($usec, $sec) = split(' ', microtime()); 
	if($start = ((float)$usec + (float)$sec))
	{
		define('duration', TRUE);
	}
	
	for($i = 0; $i < 500; $i++) 
	{
		//
	}
	
	if ( defined('duration') )
	{
		list($usec, $sec) = split(' ',microtime()); 
		$end = ((float)$usec + (float)$sec); 
		$time = $end-$start; 
		
		$time = round($time, 10);
		$fwrite = $fwrite;
		$write = $write;

		message_die(GENERAL_MESSAGE, sprintf($lang['ST_test_success'], 2) . '<br /><br />' . $lang['ST_test_pre'] . '<br /><br />' . sprintf($lang['ST_next_test'], '<a href="' . append_sid('admin_server_test.'.$phpEx.'?test=3&amp;time=' . $time . '&amp;fwrite=' . $test1 . '&amp;write=' . $test2) . '">', '</a>'));
	}
}
else if ($test == 3)
{
	if ($test2)
	{
		$fwrite = $lang['ST_write_can'];
	} 
	else
	{
		$fwrite = $lang['ST_write_not'];
	} 

	if ($test1)
	{
		$write = $lang['ST_write_can'];
	} 
	else
	{
		$write = $lang['ST_write_not'];
	} 

	if ($test3 < 0.3)
	{
		$d_time = sprintf($lang['ST_server_vgood'], $test3);
	} 
	else if ($test3 < 0.5)
	{
		$d_time = sprintf($lang['ST_server_good'], $test3);
	} 
	else if ($test3 < 1)
	{
		$d_time = sprintf($lang['ST_server_bad'], $test3);
	} 
		
	$template->assign_block_vars('results', array(
		'L_PAGE_TITLE' => $lang['ST_default'], 
		'1' => $fwrite,
		'2' => $write,
		'3' => $d_time)
	);	
}
else
{
	$template->assign_block_vars('default', array(
		'L_PAGE_TITLE' => $lang['ST_default'], 
	   	'U_START' => sprintf($lang['ST_start'], '<a href="' . append_sid('admin_server_test.'.$phpEx.'?test=1') . '">', '</a>'))
	);
}

//
// Spit out the page.
//
$template->set_filenames(array(
	'body' => 'admin/utils_server_test_body.tpl')
);

$template->assign_vars(array(
	'L_PAGE_TITLE' => $lang['ST_default'], 
	'L_PAGE_EXPLAIN' => $lang['ST_default_explain'])
);

$template->pparse('body');

include('../admin/page_footer_admin.'.$phpEx);

?>