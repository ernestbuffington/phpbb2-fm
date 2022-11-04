<?php
/** 
*
* @package admin_mod
* @version $Id: admin_avatar_unused.php,v 1.00 2003/10/18 AWSW Exp $
* @copyright (c) 2003 AWSW
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Avatars']['Unused_Avatars'] = $filename;
	return;
}

//
// Load default header
//
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

//
// Include language
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_avatar_suite.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_avatar_suite.' . $phpEx);

// Module Activation
if ($board_config['enable_module_avdelete'])
{

//
// Delete files 
//
if($_POST[submit_update1])
{
	if ($_POST[check] == '')
	{	
		echo '<meta http-equiv="refresh" content="2;url=admin_avatar_unused.'.$phpEx.'?sid=' . $userdata['session_id'] . '">';
		message_die(GENERAL_MESSAGE, $lang['Avatar_Delete_5']);
	}
	
	foreach($_POST[check] as $file)
	{
		$base_dir = $phpbb_root_path . $board_config['avatar_path'] . '/';
		$path = $base_dir;
		@unlink($path . $file);
	}
}

echo $mod_menu . '
		</ul>
	</div></td>
	<td valign="top" width="78%">

<script language="Javascript" type="text/javascript">
function select_switch(status)
{
	for (i = 0; i < document.post.length; i++)
	{
		document.post.elements[i].checked = status;
	}
}
</script>

<h1>' . $lang['Unused_Avatars'] . '</h1>
	
<p>' . $lang['Avatar_Delete_explain'] .'</p>
	
<p>' . $lang['Avatar_Delete_1'] . ' <b>' . real_path($board_config['avatar_path'] . '/') . '</b></p>

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form method="post" name="post" action="' . append_sid('admin_avatar_unused.'.$phpEx) . '">
<tr>
	<th width="40%" class="thCornerL">&nbsp;' . $lang['Avatar_Delete_2'] . '&nbsp;</th>
	<th width="40%" class="thTop">&nbsp;' . $lang['Avatar_Delete_3'] . '&nbsp;</th>
	<th width="10%" class="thTop">&nbsp;' . $lang['Avatar_Delete_4'] . '&nbsp;</th>
	<th width="5%" class="thCornerR">&nbsp;' . $lang['Mark'] . '&nbsp;</th>
  </tr>';

// Read avatar directory 
$base_dir = $phpbb_root_path . $board_config['avatar_path'] . '/';
$path = $base_dir;
$dir_handle = @opendir($path);
$space = ' ';
if ($path != '.')
{
	while ($file = @readdir($dir_handle)) 
	{
		// Ignore following files
		if($file == '.' || $file == '..' || $file == 'index.htm' || $file == 'index.html' || $file == 'tmp' || $file == substr(strrchr($board_config['avatar_gallery_path'], '/'), 1))
		continue;

		$sql = "SELECT COUNT(*) AS total 
			FROM " . USERS_TABLE . "
			WHERE user_avatar = '" . $file . "'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_MESSAGE, 'Could not obtain unused avatars', '', __LINE__, __FILE__, $sql);
		}
		$row = $db->sql_fetchrow($result);
		
		if($row['total'] > 0)
		continue;
		
		$t = '<tr><td align="center" class="row1"><a href="' . $path . $file . '" target="_blank"><img src="' . $path . $file . '" alt="' . $file . '" title="' . $file . '" /></a></span></td><td align="center" class="row1"><a href="' . $path . $file . '" target="_blank">' . $file . '</a></td>';
		echo $t . substr($space, 0, 40 - strlen($file));

		$t = (filesize($path . $file) / 1024);
		$t = '<td align="center" class="row1">' . sprintf('%.2f KB', $t) . '</td><td align="center" class="row2"><input type="checkbox" name="check[]" value="' . $file . '"></td>';
		echo substr($space, 0, 10 - strlen($t)) . $t;
	}
} 
else if ($path = '.') 
{
	while ($file = @readdir($dir_handle)) 
	{
		$t = '<a href="' . $file . '">' . $file . '</a>';
		echo $t . substr($space, 0, 40 - strlen($file));

		$t = (filesize($file) / 1024);
		$t = sprintf("%01.2f", $t) . 'kb ';
		echo substr($space, 0, 10 - strlen($t)) . $t;
	}
}
@closedir($dir_handle);

echo '</tr>
<tr>
	<td colspan="4" class="catBottom" align="center">
		&nbsp;	
		<input class="mainoption" type="submit" name="submit_update1" value="' . $lang['Delete'] . '" />
</td>
</tr>
</form></table>
<table width="100%" align="center" cellpadding="2" cellspacing="2">
<tr>
		<td align="right"><b class="gensmall"><a href="javascript:select_switch(true);" class="gensmall">' . $lang['Mark_all'] . '</a> :: <a href="javascript:select_switch(false);" class="gensmall">' . $lang['Unmark_all'] . '</a></b></td>
</tr>
</table>
<br />
<div align="center" class="copyright">Delete Unused Avatars 1.00 &copy; 2003, ' . date('Y') . ' <a href="http://www.awsw.de" class="copyright" target="_blank">AWSW</a></div>';

include('../admin/page_footer_admin.'.$phpEx);

}
$message = $lang['Module_disabled'] . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid("index.$phpEx?pane=right") . '">', '</a>');
message_die(GENERAL_MESSAGE, $message);

?>