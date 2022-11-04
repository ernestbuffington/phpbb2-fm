<?php
/** 
*
* @package includes
* @version $Id: functions_avatarsuite.php,v 1.133.2.34 2005/02/21 18:37:33 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

//
// Include language
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_avatar_suite.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_avatar_suite.' . $phpEx);

$avatarvote_css_setyet = FALSE;

function fullavatarpath($avatarfilename, $avatartype, &$avatardescription, &$solid, &$filesize)
{
	global $phpbb_root_path, $board_config, $lang, $userdata;

	switch ($avatartype) // Calculate the final path to the avatar
	{
		case USER_AVATAR_UPLOAD:
			$fullpath = $phpbb_root_path . $board_config['avatar_path'] . '/' . $avatarfilename; // Uploaded Avatar
			$avatardescription = $lang['avatarsuite_vote_whereuploaded'];
			$filesize = @filesize($fullpath);
			break; 
		case USER_AVATAR_REMOTE:
			$fullpath = $avatarfilename; // Avatar on other server
			$avatardescription = $lang['avatarsuite_vote_whereforeign'];
			$filesize = 20000; // Assume that it's 20KB big
			break; 
		case USER_AVATAR_GALLERY:
			$fullpath = $phpbb_root_path . $board_config['avatar_gallery_path'] . '/' . $avatarfilename; // Avatar from the Forum's Avatar Gallery
			$avatardescription = $lang['avatarsuite_vote_wheregallery'];
			$filesize = @filesize($fullpath);
			break; 
	}
	
	$solid = preg_match('/.*jpg$/i', $avatarfilename); // If it's a JPG it cannot be transparent
	
	return $fullpath;
}

function avatarvote_create_voting_form($avatarvote_filename, $avatarvote_type, $show_even_if_voted_before = FALSE)
{
	global $lang, $phpEx, $poster_id, $userdata, $poster_avatar, $previous_votings_of_current_viewer, $avatarvote_css, $avatarvote_css_setyet;
	
	$avatarvoteform = '';
	fullavatarpath($avatarvote_filename, $avatarvote_type, $dummy, $dummy, $filesize);
	
	$voting_allowed =
		   ($userdata['session_logged_in'])  // User must be logged in to vote OR b) it's own avatar OR c) poster has no avatar OR d) user already voted
		&& ($avatarvote_filename)
		&& ($poster_id != ANONYMOUS) // Poster must have posted as an logged in user
		&& ($poster_id != $userdata['user_id']) // Voter cannot vote for his own avatars
		&& ($poster_id >= 2) 
		&& ($poster_avatar) // The posts shows an avatar
		&& ($show_even_if_voted_before || (!$previous_votings_of_current_viewer[$avatarvote_filename.'-'.$avatarvote_type])) // The voter hasn't voted for this avatar yet
		&& ($filesize > 0);

/*
	if ($userdata['user_level'] == ADMIN)
		{
		echo '<br />Avatar owner:'.($poster_id);
		echo '<br />Logged in:'.($userdata['session_logged_in']);
		echo '<br />Not anon poster:'.($poster_id != ANONYMOUS);
		echo '<br />'.($poster_avatar);
		echo '<br />Type:'.($avatarvote_type);
		echo '<br />Poster <> Voter:'.($poster_id != $userdata['user_id']);
		echo '<br />Voted before:'.(!$previous_votings_of_current_viewer[$avatarvote_filename.'-'.$avatarvote_type]);
		echo '<br />Filesize:'.($filesize);
		echo '<hr />';
		}
/**/

	$avatarvote_filename = htmlentities(urlencode($avatarvote_filename), ENT_QUOTES);
	$avatarvote_type = intval($avatarvote_type);

	if (!$voting_allowed)
	{
		return '';
	}
	$avatarvote_linkbase = append_sid('avatarsuite_vote.'.$phpEx.'?filename=' . $avatarvote_filename . '&amp;type=' . $avatarvote_type);
	
	$avatarvotebuttons = '';
	foreach ($lang['avatarsuite_vote_value'] AS $avatarvotevalue => $avatarvote_optiontext)
	{
		$avatarvotebuttons .= $avatarvotebuttons ? '<td class="avatarvotedot">&bull;</td>' : '';
		$avatarvotebuttons .= <<<HEREDOC
		<td align="center" class="avatarvote" onmouseover="this.className='avatarvoteomo'" onmousedown="this.className='avatarvoteclick'" onmouseout="this.className='avatarvote'">
		<a href="{$avatarvote_linkbase}&amp;vote={$avatarvotevalue}" style="text-decoration:none; font-weight:normal; color:#000000;" target="avatarsuite_vote">{$avatarvote_optiontext}</a>
		</td>
HEREDOC;
// Alternative that uses javascript to open window
// <td class="avatarvote" onmouseover="this.className='avatarvoteomo'" onmousedown="this.className='avatarvoteclick'" onmouseout="this.className='avatarvote'" onClick="window.open('{$avatarvote_linkbase}&amp;vote={$avatarvotevalue}', 'avatarvote', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=450,height=300')"><span class="gensmall">{$avatarvote_optiontext}</span></td>
		}

	$avatarvote_css = <<<HEREDOC
	<STYLE TYPE="text/css">
	table.avatarvote { background-color:#d0d0d0; margin-top:5px; border:2px white solid; }
	a.avatarvote:link { text-decoration:none; font-weight:normal; color:#000000; font-size:10px }
	a.avatarvote:visited { text-decoration:none; font-weight:normal; color:#000000; font-size:10px }
	a.avatarvote:active { text-decoration:none; font-weight:normal; color:#000000; font-size:10px }
	a.avatarvote:hover { border:1px solid red; text-decoration:none; font-weight:normal; color:#000000; font-size:10px }
	
	td.avatarvotedescription { font-size:10px; text-align:center; color:#000000; padding:3px }
	td.avatarvotedot { font-size:10px; text-align:center; color:#000000; } 
	td.avatarvote { font-size:10px; text-align:center; background-color:#e0e0e0; border:1px #C0C0C0 ridge; padding-bottom:2px; padding-top:1px;    padding-left:1px;  padding-right:2px; }
	td.avatarvoteomo { font-size:10px; text-align:center; cursor:pointer; cursor:hand;	border:1px #404040 solid; background-color:white; padding-bottom:2px; padding-top:1px;    padding-left:1px;  padding-right:2px; }
	td.avatarvoteclick { font-size:10px; text-align:center; cursor:pointer; cursor:hand;	border:1px inset black; background-color:white; padding-top:2px; padding-bottom:1px; padding-right:1px; padding-left:2px; }	
	</style>
HEREDOC;

	$avatarvoteform = <<<HEREDOC
	<table align="center" cellspacing="0" cellpadding="0" class="avatarvote">
	<tr>
		<td align="center" class="avatarvotedescription">
		{$lang['avatarsuite_vote_ratehere']}
		</td>
	</tr>
	<tr>
		<td align="center" style="padding:3px"><table cellspacing="0" cellpadding="0">
		<tr>
		{$avatarvotebuttons}
		</tr>
		</table></td>
	</tr>
	</table>
HEREDOC;
	
	if (($avatarvoteform) && (!$avatarvote_css_setyet))
	{
		$avatarvote_css_setyet = TRUE;

		return $avatarvote_css . $avatarvoteform; // Prepend CSS <style> definitions. I know, I know, this sucks: Putting a <style> tag within the <body> tags. But phpBB doesn't know an overall.css (= valid for subSilver and all other styles) so that we have to trick a little
	}
	else
	{
		return $avatarvoteform;
	}
}
	
function avatarvote_create_voting_form_with_comment($avatarvote_filename, $avatarvote_type, $avatarvote_vote, $comment)
{
	global $lang, $phpEx;
	
	$avatarvote_filename = htmlentities(urlencode($avatarvote_filename), ENT_QUOTES);
	$avatarvote_type = intval($avatarvote_type);
	$comment = htmlentities($comment, ENT_QUOTES);
	
	$avatarvote_linkbase = append_sid('avatarsuite_vote.'.$phpEx.'?filename=' . $avatarvote_filename . '&amp;type=' . $avatarvote_type . '&amp;vote=' . $avatarvote_vote);
	
	$avatarvoteform = <<<HEREDOC
	<br /><br />
	<form method="post" action="{$avatarvote_linkbase}">
	{$lang['avatarsuite_vote_addcomment']}<br />
	<textarea style="width: 200px" rows="3" cols="30" class="post" name="comment" value="{$comment}"></textarea>
HEREDOC;

	return $avatarvoteform;
}
	
function avatarvote_get_previous_votings(&$resultingarray)
{
	global $db, $table_prefix, $userdata;

	$resultingarray = array();
	if ($userdata['session_logged_in']) // Only get former votings if user is logged in
	{
		$sql = "SELECT atop.avatar_filename, atop.avatar_type
			FROM " . $table_prefix . "avatartoplist atop
			WHERE atop.voter_id = " . $userdata['user_id'];
		if ($result = $db->sql_query($sql))
		{
			while ($row = $db->sql_fetchrow($result))
			{
				$resultingarray[$row['avatar_filename'] . '-' . $row['avatar_type']] = 1; // Yup user has voted for this avatar
			}
			$db->sql_freeresult($result);
		}
	}

	return $resultingarray;
}
	
function avatarsuite_timesused($times)
{
	global $lang;

	return sprintf($lang['avatarsuite_listavatars_timesused'], $times, $times == 1 ? '' : 's');
}

?>