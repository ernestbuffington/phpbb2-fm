<?php
/** 
*
* @package admin
* @version $Id: page_footer_admin.php,v 1.9.2.2 2002/05/12 15:57:45 psotfx Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

//
// Show the overall footer.
//
$template->set_filenames(array(
	'page_footer' => 'admin/page_footer.tpl')
);

$template->assign_vars(array(
	'TRANSLATION_INFO' => (isset($lang['TRANSLATION_INFO'])) ? '<br />' . $lang['TRANSLATION_INFO'] : ((isset($lang['TRANSLATION'])) ? '<br />' . $lang['TRANSLATION'] : ''))
);

$template->pparse('page_footer');

//
// Close our DB connection.
//
$db->sql_close();

//
// Compress buffered output if required
// and send to browser
//
if( $do_gzip_compress )
{
	//
	// Borrowed from php.net!
	//
	$gzip_contents = ob_get_contents();
	ob_end_clean();

	$gzip_size = strlen($gzip_contents);
	$gzip_crc = crc32($gzip_contents);

	$gzip_contents = gzcompress($gzip_contents, 9);
	$gzip_contents = substr($gzip_contents, 0, strlen($gzip_contents) - 4);

	echo "\x1f\x8b\x08\x00\x00\x00\x00\x00";
	echo $gzip_contents;
	echo pack('V', $gzip_crc);
	echo pack('V', $gzip_size);
}

exit;

?>