<?php
/** 
*
* @package phpBB
* @version $Id: avatarsuite_vote.php,v 1.36.2.2 2002/07/29 05:04:03 dougk_ff7 Exp $
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
$userdata = session_pagestart($user_ip, PAGE_AVATAR_TOPLIST);
init_userprefs($userdata);
//
// End session management
//

$loggedin = $userdata['session_logged_in'];
$userid = $userdata['user_id'];
$sesid = $userdata['session_id'];

// Start output of page
// Generate the page
$page_title = $lang['avatarsuite_vote_pagetitle_vote'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

function endhere($message, $showcommentinput = FALSE, $previouscomment = '')
{
	global $lang, $phpEx, $language_filename, $avatarvoting, $avatartype;

	$commentform = '';
	if ($showcommentinput)
	{
		$commentform = avatarvote_create_voting_form_with_comment($_GET['filename'], $avatartype, $avatarvoting, $previouscomment);
	}
	
	$toplisturl = sprintf($lang['Click_avatar_toplist'], '<a href="' . append_sid('avatarsuite_toplist.'.$phpEx) . '">', '</a>');
	
	message_die(GENERAL_MESSAGE, $message . $commentform . $toplisturl);
}


if (!$loggedin)
{
	endhere($lang['avatarsuite_vote_error_loggedout']);
}

if (isset($_GET['filename']))
{
	$avatarfilename = ($_GET['filename']);
}
else
{
	endhere($lang['avatarsuite_vote_error_missingfilename']);
}

if (isset($_GET['type']))
{
	$avatartype = intval($_GET['type']);
}
else
{
	endhere($lang['avatarsuite_vote_error_missingtype']);
}

if (isset($_GET['vote']))
{
	$avatarvoting = intval($_GET['vote']);
}
else
{
	endhere(sprintf($lang['avatarsuite_vote_error_missingvote'], implode('/', $lang['avatarsuite_vote_value'])));
}
	
// &value=XX where XX doesn't exist (e.g. someone tried to vote for an avatar with a very high value)
if ($lang['avatarsuite_vote_value'][$avatarvoting] == '') 
{
	endhere(sprintf($lang['avatarsuite_vote_error_wrongvalue'], implode('/', $lang['avatarsuite_vote_value'])));
}
	
// Get all users that use this avatar and check whether the persistent avatar mod is installed
$sql = "SELECT DISTINCT poster_id
	FROM " . POSTS_TABLE . "
	WHERE user_avatar = '" . $avatarfilename . "'
		AND user_avatar_type = '" . $avatartype . "'";
if (!$result = $db->sql_query($sql))
{
	endhere($lang['avatarsuite_vote_error_modmissing']); // Persistent Avatars mod is not installed
}

// Get all users who use this avatar
$avatarisusedbynonvoter = 0;
while ($row = $db->sql_fetchrow($result)) 
{
	 // Yup, voter votes for someoneelse's avatar
	if ($row['poster_id'] <> $userid)
	{
		$avatarisusedbynonvoter = 1;
		break;
	}

	// Voter votes for his own avatar
	if ($row['poster_id'] == $userid) 
	{
		$avatarisusedbynonvoter = 1;
	}
}
$db->sql_freeresult($result);
	
// Voter mustn't vote for his own avatars
if ($avatarisusedbynonvoter == -1) 
{
	endhere($lang['avatarsuite_vote_error_noself']); 
}
	
// No matching avatars found
if ($avatarisusedbynonvoter == 0) 
{
	endhere($lang['avatarsuite_vote_error_0found']); 
}

// OK, from here on is clear that the voter is allowed to vote for this avatar AND this avatar exists
$sql = "SELECT *
	FROM " . $table_prefix . "avatartoplist
	WHERE avatar_filename = '" . $avatarfilename . "'
		AND avatar_type = '" . $avatartype . "'
		AND voter_id  = " . $userid;
if (($result = $db->sql_query($sql)) && ($row = $db->sql_fetchrow($result))) 
{
	// Yup, voter voted already for this avatar
	$formervoting = $row['voting']; 
	$formercomment = $row['comment'];
}
else
{
	// Nope, first time voter votes for this avatar
	$formervoting = -10; 
	$formercomment = '';
}
	
if (isset($_POST['comment']))
{
	$comment = $_POST['comment'];
}
else
{
	$comment = $formercomment;
}

// OK, accept the vote
$addcomment = FALSE;
	
// General creation of a table for this mod
$sqlct = "CREATE TABLE IF NOT EXISTS " . $table_prefix . "avatartoplist (`avatar_filename` TEXT NOT NULL, `avatar_type` tinyint(4) NOT NULL default '0', `voter_id` mediumint(8) NOT NULL, `voting` mediumint(8) NOT NULL, `comment` text NOT NULL, INDEX `voter_id` (`voter_id`))";
if (!$result = $db->sql_query($sqlct))
{
	// Sever error.
	endhere($lang['avatarsuite_vote_error_sqlcreate']); 
}
	
// User never voted for this avatar before -> Insert a new row
if ($formervoting == -10)
{
	$sql = "INSERT INTO " . $table_prefix . "avatartoplist 
		SET	voting = '" . $avatarvoting . "', avatar_type = '" . $avatartype . "', avatar_filename = '" . $avatarfilename . "', voter_id = " . $userid . ", comment = '" . ($comment) . "'";
	$resultmessage = (sprintf($lang['avatarsuite_vote_accepted'], $lang['avatarsuite_vote_value'][$avatarvoting]));
	$addcomment = TRUE;
}
// User already voted for this avatar -> Only update his new vote
else 
{
	$sql = " UPDATE " . $table_prefix . "avatartoplist
		SET	voting = '" . $avatarvoting . "', comment = '" . ($comment) . "'
		WHERE avatar_type = '" . $avatartype . "'
			AND avatar_filename = '" . $avatarfilename . "'
			AND voter_id = " . $userid;
	// not "if ($comment)"
	if (isset($_POST['comment'])) 
	{
		$resultmessage = $lang['avatarsuite_vote_commentaccepted'];
	}
	else
	{
		// User has already voted and he votes now again with the same result
		if ($formervoting == $avatarvoting) 
		{
			$resultmessage = sprintf($lang['avatarsuite_vote_unchanged'], $lang['avatarsuite_vote_value'][$avatarvoting]);
			$addcomment = TRUE;
		}
		else
		{
			$resultmessage = sprintf($lang['avatarsuite_vote_updated'], $lang['avatarsuite_vote_value'][$avatarvoting]);
			$addcomment = TRUE;
		}
	}
}

// Yup, vote has been recorded
if ($result = $db->sql_query($sql)) 
{
	endhere($resultmessage, $addcomment, $comment);
}
else
{
	endhere($lang['avatarsuite_vote_error_sqlnotaccepted']);
}

// Shouldn't actually ever be called
include($phpbb_root_path . 'includes/page_tail.'.$phpEx); 

?>