<?php
/** 
*
* @package phpBB
* @version $Id: avatarsuite_toplist.php,v 1.36.2.2 2002/07/29 05:04:03 dougk_ff7 Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include $phpbb_root_path . 'includes/functions_avatarsuite.'.$phpEx;

//
// Include language
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_avatar_suite.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_avatar_suite.' . $phpEx);

$how_many_random_unvoted_avatars_shall_be_shown = 6;

// Explanation: If you set this to 10, but an avatar has only 5 voters then the final points 
// get get lowered to 50%. Why? Because if you sort by "average points" then an avatar having 1 voter 
// and 2 points would be listed higher than an avatar having 1.9 points and 100 voters
$how_many_voters_are_needed_to_be_representative = 10; 

// This is to prevent users to sign up as new users and to vote for their own avatars. Users with less 
// posts are allowed to vote but their vote will not count until they reach this amount of posts.
$minpostcount_to_be_a_valid_user = 5; 

$thisfilename = basename(__FILE__,'.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_AVATAR_TOPLIST);
init_userprefs($userdata);
//
// End session management
//

$user_id = $userdata['user_id'];
$sesid = $userdata['session_id'];
$session_logged_in = $userdata['session_logged_in'];

if (isset($_GET['startfrom']))
{
	$startfrom = intval($_GET['startfrom']);
}
else
{
	$startfrom = 1;
}
	
if (isset($_GET['genderofvoters']))
{
	$genderofvoters = intval($_GET['genderofvoters']);
}
else
{
	$genderofvoters = 0;
}
	

if (isset($_GET['step']))
{
	$step = intval($_GET['step']);
}
else
{
	$step = 10;
}

$showonlyuserid = 0;
if (isset($_GET['showonlyuserid']))
{
	$showonlyuserid = intval($_GET['showonlyuserid']);
}
if ($showonlyuserid <= 1)
{
	$showonlyuserid = 0;
}

if ($startfrom < 1)
{
	$startfrom = 1;
}

if ($step < 1)
{
	$step = 10;
}
	
if (intval($_GET['edit']))
{
	$edit = 1;
}
else
{
	$edit = 0;
}

$appendquerystandard = 'step|showonlyuserid|genderofvoters';
$appendquery['step'] = $step;
$appendquery['showonlyuserid'] = $showonlyuserid;
$appendquery['genderofvoters'] = $genderofvoters;
$appendquery['startfrom'] = $startfrom;
$appendquery['edit'] = $edit;

//
// Queries is something like 'startfrom|endby|maxnum'
//
function makelink($queries = '') 
{
	global $appendquery, $thisfilename, $phpEx, $appendquerystandard;

	if ($queries)
	{
		$queries = $appendquerystandard . '|' . $queries;
	}
	else
	{
		$queries = $appendquerystandard;
	}
	
	$querystr = '';	
	$queryar = explode('|', $queries);
	foreach ($queryar AS $parameter)
	{
		if ($parameter)
		{
			$value = $appendquery[$parameter];
			if ($value)
			{
				$querystr .= ($querystr ? '&amp;' : '?') . $parameter . '=' . htmlspecialchars($value, ENT_QUOTES);
			}
		}
	}
	
	return append_sid($thisfilename.'.'.$phpEx . $querystr);
}


//	
// Start output of page
//
// $gen_simple_header = TRUE;
$template->set_filenames(array(
	'cycletemplate' => ((intval(substr($thisfilename, -1)) > 0) ? substr($thisfilename, 0, -1) : $thisfilename).'.tpl')
);
make_jumpbox('viewforum.'.$phpEx); 


//	
// Example: $array = array_csort($array,'town','age',SORT_DESC,'name',SORT_ASC);
// coded by Ichier2003 
//
function array_csort()
{  
	$args = func_get_args(); 
	$marray = array_shift($args);
	$msortline = "return(array_multisort("; 
	foreach ($args as $arg)
	{ 
		$i++; 
		if (is_string($arg))
		{ 
			foreach ($marray as $row)
			{ 
				$sortarr[$i][] = $row[$arg]; 
	   		}
		}
		else
		{ 
			$sortarr[$i] = $arg; 
		}
		$msortline .= "\$sortarr[" . $i . "],"; 
	}
	$msortline .= "\$marray));";
	eval($msortline); 

	return $marray; 
} 


//
// Get all users with their avatars
//
$sql =	"SELECT u.user_id, u.username, u.user_level, SUM(u.user_posts) AS ownerpostcount, p.user_avatar, p.user_avatar_type, COUNT(*) AS usedinposts
	FROM " . USERS_TABLE . " u, " . POSTS_TABLE . " p
	WHERE p.poster_id = u.user_id
		AND p.poster_id != " . ANONYMOUS . "
		AND p.user_avatar != ''
	GROUP BY p.user_avatar, p.user_avatar_type, p.poster_id";
if (!$result = $db->sql_query($sql))
{
	message_die(GENERAL_MESSAGE, 'Could not obtain all users avatars.', '',__LINE__, __FILE__, $sql);
}

$avatarinfo = array();
$usernames = '';
while (($row = $db->sql_fetchrow($result)))  
{
	$av = $row['user_avatar'];
	$at = $row['user_avatar_type'];
	$avat = $av . '-' . $at;

	// Whose avatar is it? Only set if not set yet OR if the viewer is the owner (later needed for revoting)
	if (($row['user_id'] == $user_id) || (!$avatarinfo1[$avat]['owner_id']))
	{
		$avatarinfo1[$avat]['owner_id'] = $row['user_id'];
	}
	
	// In how many posts does this avatar appear?
	$avatarinfo1[$avat]['used'] = $row['usedinposts']; 
	
	// How many posts has this poster posted (= sum of ALL his posts, not only posts with THIS avatar)
	$avatarinfo1[$avat]['ownerpostcount'] = $row['ownerpostcount']; 
	
	$avatarinfo1[$avat][$row['user_id']] = 1;
	$avatarinfo1[$avat]['avatar_filename'] = $av;
	$avatarinfo1[$avat]['avatar_type'] = $at;
	
	// Not calculated yet
	if (!$avatarinfo1[$avat]['fullpath']) 
	{
		$avatarinfo1[$avat]['fullpath'] = fullavatarpath($av, $at, $avatarinfo1[$avat]['location'], $avatarinfo1[$avat]['solid'], $avatarinfo1[$avat]['filesize']);
	}
	
	// If we called the toplist as "?showonlyuserid" then the click on the username should lead to the profile
	if ($showonlyuserid >= 2) 
	{
		$usernamelink = append_sid($phpbb_root_path.'profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $row['user_id']);
	}
	else
	{
		$appendquery['showonlyuserid'] = $row['user_id'];
		$usernamelink = makelink();
		$appendquery['showonlyuserid'] = $showonlyuserid;
	}

	$avatarinfo1[$avat]['ownerusernames'] .= ($avatarinfo1[$avat]['ownerusernames'] ? ', ' : '') . '<a href="' . append_sid($usernamelink) . '">' . htmlentities($row['username'], ENT_QUOTES) . '</a>';
}
$db->sql_freeresult($result);


//
// No persistent avatars found
//
if (!$avatarinfo1) 
{
	if ($showonlyuserid >= 2)
	{
		message_die(GENERAL_MESSAGE, $lang['avatarsuite_vote_error_noavatarsfoundforthisuser']);
	}
	else
	{
		message_die(GENERAL_MESSAGE, $lang['avatarsuite_vote_error_noavatarsfound']);
	}
}


//
// At this point ALL avatars ever used in posts are in "$avatarinfo1"
// Now we need to calculate "$avatarinfo" which contains additional data (e.g. the votings sums) 
// and contains less avatars (because we will throw some invalid avatars out)
//
// Reset some variables
//
$highestnumberofvotersforanavatar = $numberofavatars = 0;
$previous_votings_of_current_viewer = array();
$there_is_an_avatar_on_this_page_the_user_already_voted_for = FALSE;

//
// Get all voters and votings
//
$sql = "SELECT atop.avatar_filename, atop.avatar_type, atop.voting, atop.comment, u.*, atop.voter_id
	FROM " . USERS_TABLE . " u, " . $table_prefix . "avatartoplist atop
	WHERE u.user_id = atop.voter_id
		AND atop.avatar_filename != ''";
if (!$result = $db->sql_query($sql))
{
	message_die(GENERAL_MESSAGE, $lang['avatarsuite_vote_error_novalidvotes'] . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.'.$phpEx) . '">', '</a>'));
}

while (($row = $db->sql_fetchrow($result)) )  // Get all avatar voting one by one
{
	$username = ''; 
	$genderisknown = isset($row['user_gender']);
	$avat = $row['avatar_filename'] . '-' . $row['avatar_type'];
	$vi = $row['voter_id'];
	
	// For Admin only
	if ($userdata['user_level'] == ADMIN) 
	{
		$username = $row['username'] . ': ';
	}

	// If the current toplist viewer is the voter (but if it's not the own avatar), 
	// later needed for random avatar voting and re-voting
	if (($vi == $user_id) && (!$avatarinfo1[$avat][$vi])) 
	{
		// Yup, mark this avatar as "viewer has voted for this one already"
		$previous_votings_of_current_viewer[$avat] = 1; 
	}
		
	// Something is wrong with the voter
	// It's the user's own avatar -> Don't count his vote
	// How could this be since a user cannot vote for his own avatar?
	// Well, if 2 users uploaded the same avatar and they voted for each other's avatar (which is allowed) 
	// AND THEN you would merge (via MySQL or so) these 2 avatars then you might have a case where one voted 
	// for someoneelse's avatar that became one's own
	// Voter doesn't have the gender required
	// Voter is too new to be counted (this prevents users to sign up as new users to vote for their own avatars)
	if ( ($avatarinfo1[$avat][$vi]) || ($genderofvoters && $genderisknown && ($row['user_gender'] != $genderofvoters)) || ($row['user_posts'] < $minpostcount_to_be_a_valid_user) )
	{
	 	// For Admins only
		$votersandvotings[$avat] .= ($votersandvotings[$avat] ? '<br />' : '') . '&bull; <span style="text-decoration: line-through">' . $username.intval($row['voting']) . ' (' . $lang['avatarsuite_vote_value'][intval($row['voting'])] . ')</span>';
		continue;
	}
	
	$votersandvotings[$avat] .= ($votersandvotings[$avat] ? '<br />' : '').'&middot;&nbsp;'.$username.intval($row['voting']).'&nbsp;('.$lang['avatarsuite_vote_value'][intval($row['voting'])].')'; // For Admins only
	
	// Something is wrong with the avatar
	// This avatar never appears in any post
	// This has also the same reason as before:
	// Sometimes it can happen that a poster uses an avatar, then a voter votes for it, but then this avatar 
	// is deleted (via MySQL, or from the webspace) = there is no single post with this avatar
	// Sometimes it can also happen that a user makes a post, someone else votes for that user's avatar, 
	// but then the post gets deleted (by the admin or so), thus there is no post with that avatar anymore.
	// "?showonlyuserid=xxx" is set but this avatar is not of user xxx
	if ( (!$avatarinfo1[$avat]['used']) || (($showonlyuserid >= 2) && (!$avatarinfo1[$avat][$showonlyuserid])) )
	{
		continue;
	}
		
	// First time that this avatar is looped
	if (!$avatarinfo[$avat]) 
	{
		// Copy all data that we have gathered. Why didn't we gather it immediately into $avatarinfo[$avat]? 
		// Because we don't want to gather, say, ['used'] but then not use it if there is no valid voter for it
		$avatarinfo[$avat] = $avatarinfo1[$avat]; 
	}
	
	// How many posts have the users posted all together? This is useful when 2 avatars have the same score, 
	// then we consider the avatar higher where the voters are better/more experienced posters
	$avatarinfo[$avat]['postcountofvoters'] += intval($row['user_posts']); 
	
	// How many posts have the users posted all together? This is useful when 2 avatars have the same score, 
	// then we consider the avatar higher where the voters are better/more experienced posters
	$avatarinfo[$avat]['votingmultiplied'] += intval($row['voting'])*intval($row['user_posts']); 
	
	// Show comments whether the commenter's will be count or not
	if (strlen($row['comment']) >= 3) 
	{
		$avatarinfo[$avat]['comments'] .= ($avatarinfo[$avat]['comments'] ? '<br />' : '') . '&bull; ' . $username . htmlentities($row['comment'], ENT_QUOTES);
	}

	$avatarinfo[$avat]['voting'] += $row['voting'];
	
	// First time this avatar is accessed in this Loop
	if (!$avatarinfo[$avat]['votercount'])
	{
		$numberofavatars++;
	}
		
	$avatarinfo[$avat]['votercount']++;
	$highestnumberofvotersforanavatar = max($avatarinfo[$avat]['votercount'], $highestnumberofvotersforanavatar);
	
	// Get the average
	$avatarinfo[$avat]['averagevoting'] = round($avatarinfo[$avat]['voting'] / $avatarinfo[$avat]['votercount'], 10); 

	// Explanation:
	// If 2 avatars have, say, 20 points
	// And 1 avatar has been voted by 8 users and the other one by 10 users, then, of course, the one with 10 voters is MORE representative
	// Thus we need to lower the finalpoints (of the 8 users avatar) to be 80%
	if ($avatarinfo[$avat]['votercount'] < $how_many_voters_are_needed_to_be_representative)
	{
		$avatarinfo[$avat]['averagevoting'] *= $avatarinfo[$avat]['votercount'] / $how_many_voters_are_needed_to_be_representative;
	}
	
	$avatarinfo[$avat]['averagevoting'] = round($avatarinfo[$avat]['averagevoting'], 2);
}
$db->sql_freeresult($result);


//	
// Change the sorting if you want to sort the toplist differently
//
// Noone voted for any avatar yet (or: There are votes, but the avatars are invalid (= the same reasons as mentioned above))
if (!$avatarinfo) 
{
	// I know, I know... If there is not a valid vote yet, then the "random avatars" won't show, since this line stops the program execution. 
	// But it's too much fuss to work around that. Simply vote for an avatar, goddamit, to get the toplist started
	message_die(GENERAL_MESSAGE, $lang['avatarsuite_vote_error_novalidvotes']); 
}
// Why do we sort by 'fullpath'? To not allow any randomity in the sorting. Because <---previous and next----> must always lead to the same avatars
$avatarinfo = array_csort($avatarinfo,'averagevoting', SORT_DESC, 'votercount', SORT_DESC, 'ownerpostcount', SORT_DESC, 'used', SORT_ASC, 'votingmultiplied', SORT_DESC, 'postcountofvoters', SORT_DESC, 'fullpath'); 

/*
	echo '<pre>';
	print_r($avatarinfo);
	echo '</pre>';
*/

$i = 0;
foreach ($avatarinfo as $index => $avatar)
{
	$i++;
	if ($i < $startfrom)
	{
		continue;
	}
		
	if ($i >= $startfrom+$step)
	{
		// OK, we have enough avatars to display
		break; 
	}
		
	if ($previous_votings_of_current_viewer[$index])
	{
		$there_is_an_avatar_on_this_page_the_user_already_voted_for = TRUE;
	}
		
	// Show the revote form (if "?edit" is used AND the user already voted for this avatar)
	if (($edit) && ($previous_votings_of_current_viewer[$index])) 
	{
		$poster_id = $avatar['owner_id'];
		
		// For avatarvote_create_voting_form()
		$poster_avatar = 'xxx'; 
		
		$votingform = avatarvote_create_voting_form($avatar['avatar_filename'], $avatar['avatar_type'],TRUE);
	}
	else
	{
		$votingform = '';
	}

	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

	// Variables within the <--- BEGIN/END cycleme ---> part
	$template->assign_block_vars('cycleme', array( 
		'ROW_CLASS' => $row_class,

		'USERNAMES' => $avatar['ownerusernames'],
		'WHOISWHO' => ($userdata['user_level'] == ADMIN) ? $votersandvotings[$index] : '', // For ADMIN only
		'AVATAR' => '<img src="' . htmlentities($avatar['fullpath'], ENT_QUOTES) . '"' . ($avatar['solid'] ? ' style="border:1 px black solid"' : '') . ' alt="' . avatarsuite_timesused($avatar['used']) . '" title="' . avatarsuite_timesused($avatar['used']) . '" />',
		'U_AVATAR' => htmlentities($avatar['fullpath'], ENT_QUOTES),
		'POSITION' => $i,
		'TOTALPOINTS' => $avatar['voting'],
		'AVERAGEVOTING' => $avatar['averagevoting'],
		'TOTALVOTERS' => $avatar['votercount'],
		'L_POINTS' => ($avatar['voting'] == 1) ? $lang['avatarsuite_vote_point'] : $lang['avatarsuite_vote_points'],
		'L_AVERAGEPOINTS' => ($avatar['averagevoting'] == 1) ? $lang['avatarsuite_vote_point'] : $lang['avatarsuite_vote_points'],
		'L_VOTERS' => ($avatar['votercount'] == 1) ? $lang['avatarsuite_vote_voter'] : $lang['avatarsuite_vote_voters'],
		'COMMENTS' => $avatar['comments'],
		'LOCATION' => $avatar['location'],
		'AVATAR_VOTE' => $votingform)
	);
}


//
// Get random avatars
//
$unvotedavatarfound = 0;
if ($how_many_random_unvoted_avatars_shall_be_shown)
{
	// Mark all avatars as unvoted where the viewer hasn't voted yet
	foreach ($avatarinfo1 AS $index => $avatar) 
	{
		// User hasn't voted for this one yet => Add to the "Random unvoted avatars"
		if (!$previous_votings_of_current_viewer[$index]) 
		{
			$avatarinfo1[$index]['unvoted'] = 1;
			$avatarinfo1[$index]['random'] = rand(1, $numberofavatars);
		}
	}
		
	$avatarinfo1 = array_csort($avatarinfo1, 'unvoted', 'random');
	
	foreach ($avatarinfo1 AS $index => $avatar)
	{
		if ($avatar['unvoted'])
		{
			$poster_avatar = 'xxx';
			
			// Get one of the owners. If there are several owners then a random of the several is chosen. 
			// If the current viewer is an owner then the viewer_id is chosen
			$poster_id = $avatar['owner_id']; 
			
			$votingform = avatarvote_create_voting_form($avatar['avatar_filename'],$avatar['avatar_type']);
			
			if ($votingform)
			{
				$unvotedavatarfound++;
				
				$row_class = ( !($unvotedavatarfound % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
		
				$template->assign_block_vars('randomunvotedavatar', array(
					'ROW_CLASS' => $row_class,
					'AVATAR' => '<img src="' . htmlentities($avatar['fullpath'], ENT_QUOTES) . '"' . ($avatar['solid'] ? ' style="border:1 px black solid"' : '') . ' alt="" title="" />',
					'AVATAR_VOTE' => $votingform)
				);
	
				if ($unvotedavatarfound >= $how_many_random_unvoted_avatars_shall_be_shown)
				{
					// OK, enough random avatars found
					break; 
				}
			}
		}
	}
	
	// No unvoted avatars found at all (e.g. when user has voted for them all or random voting is disabled)
	if ($unvotedavatarfound == 0) 
	{
		$template->assign_block_vars('randomunvotedavatar', array(
			'ROW_CLASS' => $theme['td_class1'],
			'AVATAR' => '',
			'AVATAR_VOTE' => (!$session_logged_in) ? $lang['avatarsuite_vote_randomunvotedlogin'] : (($how_many_random_unvoted_avatars_shall_be_shown) ? $lang['avatarsuite_vote_randomunvotedfinished'] : $lang['avatarsuite_vote_randomunvoteddisabled']))
		);
	}
}
	
$page_title = $lang['avatarsuite_vote_pagetitle_toplist'];

$revotelink = '';
// We are already in the revote mode
if ($edit) 
{
	$appendquery['edit'] = 0;
	$revotelink = '<a href="' . makelink('edit|startfrom') . '" class="nav">' . $lang['avatarsuite_vote_unrevote'] . '</a>';
}
elseif ($there_is_an_avatar_on_this_page_the_user_already_voted_for)
{
	$appendquery['edit'] = 1;
	$revotelink = '<a href="' . makelink('edit|startfrom') . '" class="nav">' . $lang['avatarsuite_vote_revote'] . '</a>';
}

$appendquery['genderofvoters'] = 2;
$genderfemalelink = '[<a href="' . makelink() . '" title="' . $lang['avatarsuite_vote_showgender_femalestext'] . '" class="gensmall">' . $lang['avatarsuite_vote_showgender_female'] . '</a>]';

$appendquery['genderofvoters'] = 1;
$gendermalelink = '[<a href="' . makelink() . '" title="' . $lang['avatarsuite_vote_showgender_malestext'] . '" class="gensmall">' . $lang['avatarsuite_vote_showgender_male'] . '</a>]';

$appendquery['genderofvoters'] = 0;
$genderalllink = '[<a href="' . makelink() . '" title="' . $lang['avatarsuite_vote_showgender_alltext'] . '" class="gensmall">' . $lang['avatarsuite_vote_showgender_all'] . '</a>]';
$appendquery['genderofvoters'] = $genderofvoters;

if ($genderofvoters == 1)
{
	$genderlink = $genderfemalelink . ' ' . $genderalllink;
}
elseif ($genderofvoters == 2)
{
	$genderlink = $gendermalelink . ' ' . $genderalllink;
}
else
{
	$genderlink = $gendermalelink . ' ' . $genderfemalelink;
}

if (!$genderisknown)
{
	$genderlink = '';
}

if ($startfrom-1+$step < $numberofavatars)
{
	$appendquery['startfrom'] = $startfrom + $step;
	$posnextlink = '<a href="' . makelink('startfrom') . '" class="nav">' . $lang['avatarsuite_vote_next'] . '</a>';
}
else
{
	$posnextlink = '';
}
	
if ($startfrom > 1)
{
	$appendquery['startfrom'] = ($startfrom - $step);
	$pospreviouslink = '<a href="' . makelink('startfrom') . '" class="nav">' . $lang['avatarsuite_vote_previous'] . '</a>';
}
else
{
	$pospreviouslink = '';
}
 
if ($startfrom <> 1)
{
	$appendquery['startfrom'] = 1;
	$postoplink = '<a href="' . makelink('startfrom') . '" class="nav">' . $lang['avatarsuite_vote_start'] . '</a>';
}
else
{
	$postoplink = '';
}
 
$template->assign_vars(array(
	'TITLE' => $page_title,
	'L_POSITION' => $lang['avatarsuite_vote_position'],
	'L_TOPAVATARS' => $lang['L_AVATARTOPLIST'],
	'L_USERNAMES' => $lang['avatarsuite_vote_usedby'],
	'L_COMMENTS' => $lang['avatarsuite_vote_comments'],
	'L_RANDOM' => $lang['avatarsuite_vote_randomunvoted'],
	'L_LINK_LIST' => $lang['L_AVATARLIST'],		
		
	'NEXT' => $posnextlink,
	'PREVIOUS' => $pospreviouslink,
	'TOP' => $postoplink,
	'SHOWALLUSERS' => (!$showonlyuserid) ? '' : '<a href="' . append_sid($thisfilename.'.'.$phpEx) . '">' . $lang['avatarsuite_vote_listall'] . '</a>',
	'REVOTE' => $revotelink,
	'GENDERLINKS' => $genderlink,
	'U_LINK_LIST' => append_sid('avatarsuite_listavatars.'.$phpEx))
);

// Generate the page
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->pparse('cycletemplate');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>