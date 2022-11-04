<?php
/** 
*
* @package phpBB
* @version $Id: avatarsuite_listavatars.php,v 1.36.2.2 2002/07/29 05:04:03 dougk_ff7 Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'includes/functions_avatarsuite.'.$phpEx);

//
// Include language
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_avatar_suite.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_avatar_suite.' . $phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_AVATAR_LIST);
init_userprefs($userdata);
//
// End session management
//

$maxavatarperuser = 250000; // Max avatars (as bytes) we will show PER USER
$maxavatarperpage = 2500000; // Max avatars (as bytes) we will show PER PAGE
$maxnumofavatarsperuserspage = 10; // Max NUMBER avatars we will show PER USER on the "?showonlyuserid=xxx" page

$startfromuser = intval($HTTP_GET_VARS['startfromuser']);
$showonlyuserid = intval($HTTP_GET_VARS['showonlyuserid']);
$showlast = intval($HTTP_GET_VARS['showlast']);

$startfrom = intval($HTTP_GET_VARS['startfrom']);
if ($startfrom == 0)
{
	$startfrom = 1;
}

$thisfilename = basename(__FILE__,'.'.$phpEx);
$thisfilename_php = $thisfilename.'.'.$phpEx;

function addavatar($poster_id, $avatar, $typ, $avatars, $counter)
{
	global $board_config, $lang, $phpbb_root_path;
	global $avataralreadyshown, $maxnumofavatarsperuserspage, $startfrom, $usersavatarcount, $sumofavatarsizesofuser, $sumofallavatars, $notshownavatarsofuser, $maxavatarperuser, $showonlyuserid;
	
	if ($avatar == '')
	{
		return  $avatars;
	}
	
	$avatar = fullavatarpath($avatar, $typ, $dummy, $solid, $dummy);
	
	// Avatar already there
	if ($avataralreadyshown[$avatar]) 
	{
		return $avatars;
	}
	// Avatar not there yet -> Add it
	else 
	{
		switch ($typ)
		{
			case USER_AVATAR_UPLOAD:
			case USER_AVATAR_GALLERY:
				$avatarsize = @filesize($avatar);
				break;
			case USER_AVATAR_REMOTE:
				// Avatar on other server -> Assume it's 20 KB big
				$avatarsize = 20000; 
				break; 
		}
			
		// File doesn't exist
		if (!$avatarsize) 
		{
			return $avatars;
		}
		if (($showonlyuserid == 0) && ($sumofavatarsizesofuser[$poster_id] > 0) && ($avatarsize+$sumofavatarsizesofuser[$poster_id] > $maxavatarperuser)) // Show max xxxKB of avatars per single user
		{ 
			// Don't add this avatar, since more than max allowed bytes would be shown
			$notshownavatarsofuser[$poster_id]++;
			return $avatars;
		}

		// We are on the user's avatar page "?showonlyuserid=xxx"
		if ($showonlyuserid > 0) 
		{
			// Number of user's avatars displayed already on this page
			$usersavatarcount++; 
			// don't display this avatar since we have to start from a later avatar because "&$startfrom=xxx" is set
			if ($usersavatarcount < $startfrom) 
			{
				$avataralreadyshown[$avatar] = 1;
				return $avatars;
			}
				
			// Don't display this avatar since we already displayed enough on this page
			if ($usersavatarcount >= $startfrom+$maxnumofavatarsperuserspage) 
			{
				$notshownavatarsofuser[$poster_id]++;
				return $avatars;
			}
		}
			
		$sumofavatarsizesofuser[$poster_id] += $avatarsize;
		$sumofallavatars += $avatarsize;
		$avataralreadyshown[$avatar] = 1;
		$avatar = '"' . $avatar . '"';
		
		if ($solid)
		{
			$borderstyle = 'style="border:1px solid black;"'; // Looks better
		}
		else
		{
			$borderstyle = 'style="border:0px"';
		}
		
		return  $avatars.' <img src='.$avatar.' '.$borderstyle.' title="'.$counter.'"> ';
	}
}

$sql = "SELECT DISTINCT u.user_id, u.username, user_posts, u.user_avatar, u.user_avatar_type
	FROM " . USERS_TABLE . " u, " . POSTS_TABLE . " p
	WHERE u.user_id <> " . ANONYMOUS . "
		AND u.user_id = p.poster_id " . ($showonlyuserid ? " AND u.user_id = " . $showonlyuserid : "") . "
	ORDER BY u.user_posts DESC";
$resultuser = $db->sql_query($sql);

$i = $usersavatarcount = $sumofallavatars = 0;
$tdclass = 1;
$sumofavatarsizesofuser = $notshownavatarsofuser = $avataralreadyshown = array();
$show_more = $showremaining = '';

// Cycle thru all users
while (($user = $db->sql_fetchrow($resultuser)) ) 
{
	$i++;
	// Users have been already listed on the last page
	if ($i < $startfromuser) 
	{
		continue;
	}
		
	if (($sumofallavatars > $maxavatarperpage) && ($showonlyuserid == 0))
	{
		if (!$show_more)
		{
			$l_show_more = $lang['avatarsuite_listavatars_seemoreusers'];
			$u_show_more = append_sid($thisfilename_php.'?startfromuser=' . ($i));
		}
		continue;
	}
	
	$avatars = '';
	$sql = "SELECT p.user_avatar, p.user_avatar_type, COUNT(p.user_avatar) AS postcount, MIN(p.post_time) AS ptime
		FROM " . POSTS_TABLE . " p
		WHERE p.poster_id = " . $user['user_id'] . "
		GROUP BY p.user_avatar
		ORDER BY ptime DESC";
	if ($result = $db->sql_query($sql)) 
	{
		while (($row = $db->sql_fetchrow($result)) ) // Cycle thru each Avatar
		{
			// No Avatar used for these posts -> Use standard user avatar
			if ($row['user_avatar'] == '') 
			{
				$avatars = addavatar($user['user_id'], $user['user_avatar'], $user['user_avatar_type'], $avatars, avatarsuite_timesused($row['postcount']));
			}
			// Persistent Avatars used for these posts
			else 
			{
				$avatars = addavatar($user['user_id'], $row['user_avatar'], $row['user_avatar_type'], $avatars, avatarsuite_timesused($row['postcount']));
			}
		}
		$db->sql_freeresult($result);

		$avatars = addavatar($user['user_id'], $user['user_avatar'], $user['user_avatar_type'],$avatars, $lang['avatarsuite_listavatars_unusedstandardavatar']); // Standard Avatar -> Will be added if the above loop hasn't added it yet
	}
	// No persistent mod installed = normal
	else 
	{
		$avatars = addavatar($user['user_id'], $user['user_avatar'], $user['user_avatar_type'], $avatars,avatarsuite_timesused($row['postcount'])); // Standard Avatar -> Will be added 
	}
		
	// There are more avatars of this user
	if ($notshownavatarsofuser[$user['user_id']]) 
	{
		if ($showonlyuserid)
		{
			$showremaining = sprintf($lang['avatarsuite_listavatars_see_remaining_avatars'], $notshownavatarsofuser[$showonlyuserid], ($notshownavatarsofuser[$showonlyuserid] == 1 ? '' : 's'));
			$showremaining = '<a href="' . append_sid($thisfilename_php.'?showonlyuserid=' . $user['user_id']).'&amp;startfrom=' . ($startfrom+$maxnumofavatarsperuserspage) . '">' . $showremaining . '</a>';
		}
		else
		{
			$showremaining = sprintf($lang['avatarsuite_listavatars_seemoreavatars'], $notshownavatarsofuser[$user['user_id']], ($notshownavatarsofuser[$user['user_id']] == 1 ? '' : 's'));
			$showremaining = '<a href="' . append_sid($thisfilename_php.'?showonlyuserid=' . $user['user_id']) . '">' . $showremaining . '</a>';
		}
		$avatars .= '<br />' . $showremaining;
	}
	
	// Display user, if the user has at least 1 avatar to show
	if ($avatars != '') 
	{
		$tdclass++;
		$usernamelink = $phpbb_root_path . 'profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user['user_id'];
		
		$row_class = ( !($tdclass % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
		
		$template->assign_block_vars('avatarblock', array(
			'ROW_CLASS' => $row_class,
			'USERNAME' => $user['username'],
			'USERNAMELINK' => append_sid($usernamelink),
			'AVATARS' => $avatars)
		);
	}
}
$db->sql_freeresult($resultuser);

if ($showonlyuserid)
{
	$l_show_more = $lang['avatarsuite_listavatars_showall'];
	$u_show_more = append_sid($thisfilename_php);
}

// Start output of page
$page_title = $lang['avatarsuite_listavatars_title'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

// General variables
$template->assign_vars(array( 
	'L_SHOWMORE' => $l_show_more,
	'U_SHOW_MORE' => $u_show_more,
	'TITLE' => $page_title,
	'LINK_TOPLIST' => $lang['L_AVATARTOPLIST'],
	'U_LINK_TOPLIST' => append_sid('avatarsuite_toplist.'.$phpEx))
);


$template->set_filenames(array(
	'listavatars' => ((intval(substr($thisfilename, -1)) > 0) ? substr($thisfilename, 0, -1) : $thisfilename).'.tpl')
);
make_jumpbox('viewforum.'.$phpEx); 

$template->pparse('listavatars');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>