<?php
/** 
*
* @package admin
* @version $Id: admin_avatar_merge.php,v 1.51.2.15 2006/02/10 22:19:01 grahamje Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Avatars']['Unite_double_avatars'] = $file;
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

//
// Include language
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_avatar_suite.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_avatar_suite.' . $phpEx);

$there_are_doubles = FALSE;

if ($_GET['convertthese'] && $_GET['convertto'])
{
	$convertthese1 = $_GET['convertthese'];
	$convertto = $_GET['convertto'];
	
	$convertthese2 = explode('|', $convertthese1);
	$convertthese = "'" . implode("','", $convertthese2) . "'";
	
	$sql =	"UPDATE " . POSTS_TABLE . " 
		SET	user_avatar = '" . $convertto . "'
		WHERE user_avatar IN (" . $convertthese . ")
			AND user_avatar_type = " . USER_AVATAR_UPLOAD;
	if ($result = $db->sql_query($sql))
	{
		message_die(GENERAL_MESSAGE, $lang['avatarsuite_unite_admin_united']);
	}
	else
	{
		message_die(GENERAL_ERROR, "Couldn't update posts table.", "", __LINE__, __FILE__, $sql);
	}

	$sql = "UPDATE " . USERS_TABLE . "
		SET	user_avatar = '" . $convertto . "'
		WHERE user_avatar IN (" . $convertthese . ")
			AND user_avatar_type = " . USER_AVATAR_UPLOAD;
	if ($result = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Couldn't update users table.", "", __LINE__, __FILE__, $sql);
	}
}
	
// Start output of page
$sql = "SELECT user_avatar, poster_id, count(*) AS counter
	FROM " . POSTS_TABLE . "
	WHERE user_avatar <> ''
		AND user_avatar IS NOT NULL 
		AND poster_id <> " . ANONYMOUS . "
		AND user_avatar_type = ". USER_AVATAR_UPLOAD . "
	GROUP BY user_avatar
	ORDER BY counter DESC";
if ($result = $db->sql_query($sql))
{
	while (($row = $db->sql_fetchrow($result)) )
	{
		$avatarused[$row['user_avatar']] = 1;
		$avatarusedtimes[$row['user_avatar']] += $row['counter'];
	}
	$db->sql_freeresult($result);
}

$sql = "SELECT user_id, user_avatar, user_level
	FROM " . USERS_TABLE . "
	WHERE user_avatar <> ''	
		AND user_avatar IS NOT NULL
		AND user_id <> " . ANONYMOUS . "
		AND user_avatar_type = " . USER_AVATAR_UPLOAD . "
	GROUP BY user_avatar";
if ($result = $db->sql_query($sql))
{
	while (($row = $db->sql_fetchrow($result)) )
	{
		$avatarused[$row['user_avatar']] = 1;
	}
	$db->sql_freeresult($result);
}

$avataruploadpath = $phpbb_root_path . $board_config['avatar_path'] . '/';

// Get all files from the avatar directory
if ($contents = @opendir($avataruploadpath)) 
{
	$fileinfos = array();
	while (($node = @readdir($contents)) !== false)
	{
		$fullfilename = $avataruploadpath.$node;
		if (($node == '.') || ($node == '..') || is_dir($fullfilename) || !$avatarused[$node])
		{
			continue;
		}
		$filesize = filesize($fullfilename);
		$crc32 = crc32(file_get_contents($fullfilename));
			
		if ($fileinfos[$filesize . '+' . $crc32]) // There is already such a file with such CRC
		{
			$fileinfos[$filesize . '+' . $crc32] .= '|' . $node;
			$there_are_doubles = TRUE;
		}
		else
		{
			$fileinfos[$filesize . '+' . $crc32] = $node;
		}
	}
}

echo $avatar_menu . '
		</ul>
	</div></td>
	<td valign="top" width="78%">
	
	<h1>' . $lang['Unite_double_avatars'] . '</h1>
	
	<p>' . $lang['Unite_double_avatars_explain'] . '</p>
	
	<table class="foumline" cellpadding="4" cellspacing="1" width="100%" align="center">
	<tr>
		<th colspan="2" class="thHead">' . $lang['Unite_double_avatars'] . '</th>
	</tr>';

if ($there_are_doubles)
{
	echo '<tr>
			<td colspan="2" class="row2"><span class="gensmall">' . $lang['avatarsuite_unite_admin_doublesfound'] . '</span></td>
		</tr>';
	
	$i = 0;
	foreach ($fileinfos as $index => $fileinfo)
	{
		if (strpos($fileinfo, '|') > 0)
		{
			$doublesfound++;
			$convertto = '';

			$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
			
			echo '<tr>
					<td wrap="nowrap" class="' . $row_class . '">';
			
			$alldoubles = explode('|', $fileinfo);
			foreach ($alldoubles AS $double)
			{
				if (!$convertto) // No filename for uniting set yet
				{
					$convertto = $double;
				}
				$indexar = explode('+', $index);
				echo '<img src="' . $avataruploadpath . $double . '" title="' . htmlentities(intval($avatarusedtimes[$double]) . ' ' . $lang['avatarsuite_unite_admin_times used'] . ': ' . $index, ENT_QUOTES) . '" />&nbsp; &nbsp;';
			}
			
			echo '</td>
					<td wrap="nowrap" class="' . $row_class . '"><a href="' . append_sid(('admin_avatar_merge.'.$phpEx).'?convertthese=' . htmlspecialchars(urlencode($fileinfo)) . '&amp;convertto=' . htmlspecialchars(urlencode($convertto))) . '">' . $lang['Unite_avatars'] . '</a></td>
				</tr>';
		}
	}
}

if (!$there_are_doubles)
{
	echo '<td class="row1" align="center" height="30">' . $lang['avatarsuite_unite_admin_nodoubles'] . '</td>';
}

echo '</table>
<br />
<div align="center" class="copyright">Avatar Suite 1.2.0 &copy; 2005, ' . date('Y') . ' <a href="http://www.1-4a.com/" class="copyright" target="_blank">knnknn</a></div>';

include('./page_footer_admin.'.$phpEx);

?>