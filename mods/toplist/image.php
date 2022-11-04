<?php
/** 
*
* @package toplist
* @version $Id: image.php,v 1.3.8 2004/07/11 16:46:15 wyrihaximus Exp $
* @copyright (c) 2003 Cees-Jan Kiewiet
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
@set_time_limit(15);

define('IN_PHPBB', true);
$phpbb_root_path = '../../';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'mods/toplist/toplist_common.'.$phpEx);

if (isset($HTTP_GET_VARS['imgfile']))
{
	if (!empty($HTTP_GET_VARS['imgfile']))
	{
		toplist_make_img_dg($HTTP_GET_VARS['imgfile'], 'Rank: #1', $location . 'images/toplist');
		exit;
	}
}

toplist_startup();
toplist_antiflood_startup();

if (!toplist_get_anti_flood_data(IMG, $HTTP_GET_VARS['id'], encode_ip($user_ip)))
{
	$db->sql_query("UPDATE " . TOPLIST_TABLE . " 
		SET " . IMG . " = " . IMG . " + 1 
		WHERE id = " . $HTTP_GET_VARS['id']);
	toplist_set_anti_flood_data(IMG, $HTTP_GET_VARS['id'], $user_ip);
}

$result = $db->sql_query("SELECT id, imgfile 
	FROM " . TOPLIST_TABLE . " 
	WHERE id = " . $HTTP_GET_VARS['id']);
while ($row = $db->sql_fetchrow($result))
{
	$exp_imgfile = explode("$#$", $row['imgfile']);
	if (count($exp_imgfile) > 1)
	{
		toplist_make_img_dg($exp_imgfile[0], '', $location . 'images/toplist');
	}
	else
	{
		header("Location: " . $phpbb_root_path . "images/toplist/" . $row['imgfile']);	
		exit;
	}
}
$db->sql_freeresult($result);

if ($dir = @opendir($phpbb_root_path . 'images/toplist'))
{
  	while (($file = @readdir($dir)) !== false)
  	{
    	if($file != 'index.html' && $file != '.' && $file != '..' && $file != '.htaccess' && $file != 'Thumbs.db')
    	{
	    	header("Location: " . $phpbb_root_path . "images/toplist/" . $file);	
			exit;
    	}
  	}  
  	
  	@closedir($dir);
}

header("Location: " . $phpbb_root_path . "images/spacer.gif");
exit;

?>