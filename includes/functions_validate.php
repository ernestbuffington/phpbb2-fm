<?php
/** 
*
* @package includes
* @version $Id: functions_validate.php,v 1.6.2.12 2003/06/09 19:13:05 psotfxExp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
//
// Check to see if the username has been taken, or if it is disallowed.
// Also checks if it includes the " character, which we don't allow in usernames.
// Used for registering, changing names, and posting anonymously with a username
//
function validate_username($username)
{
	global $db, $lang, $userdata, $board_config;

	// Remove doubled up spaces
	$username = preg_replace('#\s+#', ' ', trim($username)); 
	$username = phpbb_clean_username($username);

	$sql = "SELECT username 
		FROM " . USERS_TABLE . " 
		WHERE LOWER(username) = '" . strtolower($username) . "'";
	if ($result = $db->sql_query($sql))
	{
		while ($row = $db->sql_fetchrow($result))
		{
			if (($userdata['session_logged_in'] && $row['username'] != $userdata['username']) || !$userdata['session_logged_in'])
			{
				$db->sql_freeresult($result);
				return array('error' => true, 'error_msg' => $lang['Username_taken']);
			}
		}
	}
	$db->sql_freeresult($result);

	$sql = "SELECT group_name
		FROM " . GROUPS_TABLE . " 
		WHERE LOWER(group_name) = '" . strtolower($username) . "'";
	if ($result = $db->sql_query($sql))
	{
		if ($row = $db->sql_fetchrow($result))
		{
			$db->sql_freeresult($result);
			return array('error' => true, 'error_msg' => $lang['Username_taken']);
		}
	}
	$db->sql_freeresult($result);

	$sql = "SELECT disallow_username
		FROM " . DISALLOW_TABLE;
	if ($result = $db->sql_query($sql))
	{
		if ($row = $db->sql_fetchrow($result))
		{
			do
			{
				if (preg_match("#\b(" . str_replace("\*", ".*?", preg_quote($row['disallow_username'], '#')) . ")\b#i", $username))
				{
					$db->sql_freeresult($result);
					return array('error' => true, 'error_msg' => $lang['Username_disallowed']);
				}
			}
			while($row = $db->sql_fetchrow($result));
		}
	}
	$db->sql_freeresult($result);

	$sql = "SELECT word 
		FROM  " . WORDS_TABLE;
	if ($result = $db->sql_query($sql))
	{
		if ($row = $db->sql_fetchrow($result))
		{
			do
			{
				if (preg_match("#\b(" . str_replace("\*", ".*?", preg_quote($row['word'], '#')) . ")\b#i", $username))
				{
					$db->sql_freeresult($result);
					return array('error' => true, 'error_msg' => $lang['Username_disallowed']);
				}
			}
			while ($row = $db->sql_fetchrow($result));
		}
	}
	$db->sql_freeresult($result);

	// Don't allow " and ALT-255 in username.
	if (strstr($username, '"') || strstr($username, '&quot;') || strstr($username, chr(160)) || strstr($username, chr(173)) || strstr($username, ';'))
	{
		return array('error' => true, 'error_msg' => $lang['Username_invalid']);
	}

	// Check min. & max. username lengths 
	if ( strlen($username) > $board_config['limit_username_max_length'] )
	{
		return array('error' => true, 'error_msg' => sprintf($lang['Username_long'], $board_config['limit_username_max_length']));
	}
	else if ( strlen($username) < $board_config['limit_username_min_length'] || strlen($username) < 2 )
	{
		return array('error' => true, 'error_msg' => sprintf($lang['Username_short'], $board_config['limit_username_min_length']));
	}

	return array('error' => false, 'error_msg' => '');
}

//
// Check to see if email address is banned
// or already present in the DB
//
function validate_email($email)
{
	global $db, $lang;

	if ($email != '')
	{
		if (preg_match('/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-]+\.([a-z0-9\-]+\.)*?[a-z]+$/is', $email))
		{
			$sql = "SELECT ban_email
				FROM " . BANLIST_TABLE;
			if ($result = $db->sql_query($sql))
			{
				if ($row = $db->sql_fetchrow($result))
				{
					do
					{
						$match_email = str_replace('*', '.*?', $row['ban_email']);
						if (preg_match('/^' . $match_email . '$/is', $email))
						{
							$db->sql_freeresult($result);
							return array('error' => true, 'error_msg' => $lang['Email_banned']);
						}
					}
					while($row = $db->sql_fetchrow($result));
				}
			}
			$db->sql_freeresult($result);

			$sql = "SELECT user_email
				FROM " . USERS_TABLE . "
				WHERE user_email = '" . str_replace("\'", "''", $email) . "'";
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, "Couldn't obtain user email information.", "", __LINE__, __FILE__, $sql);
			}
		
			if ($row = $db->sql_fetchrow($result))
			{
				return array('error' => true, 'error_msg' => $lang['Email_taken']);
			}
			$db->sql_freeresult($result);

			return array('error' => false, 'error_msg' => '');
		}
	}

	return array('error' => true, 'error_msg' => $lang['Email_invalid']);
}

//
// Does supplementary validation of optional profile fields. This expects common stuff like trim() and strip_tags()
// to have already been run. Params are passed by-ref, so we can set them to the empty string if they fail.
//
function validate_optional_fields(&$icq, &$aim, &$msnm, &$yim, &$xfi, &$skype, &$gtalk, &$website, &$stumble, &$location, &$occupation, &$interests, &$irc_commands, &$custom_post_color)
{
	$check_var_length = array('aim', 'msnm', 'yim', 'xfi', 'skype', 'gtalk', 'location', 'occupation', 'interests', 'irc_commands', 'custom_post_color');

	for($i = 0; $i < count($check_var_length); $i++)
	{
		if (strlen($$check_var_length[$i]) < 2)
		{
			$$check_var_length[$i] = '';
		}
	}

	// ICQ number has to be only numbers.
	if (!preg_match('/^[0-9]+$/', $icq))
	{
		$icq = '';
	}
	
	// website has to start with http://, followed by something with length at least 3 that
	// contains at least one dot.
	if ($website != '')
	{
		if (!preg_match('#^http[s]?:\/\/#i', $website))
		{
			$website = 'http://' . $website;
		}

		if (!preg_match('#^http[s]?\\:\\/\\/[a-z0-9\-]+\.([a-z0-9\-]+\.)?[a-z]+#i', $website))
		{
			$website = '';
		}
	}

	// stumble has to start with http://, followed by something with length at least 3 that
	// contains at least one dot.
	if ($stumble != '')
	{
		if (!preg_match('#^http://#i', $stumble))
		{
			$stumble = 'http://' . $stumble;
		}

		if (!preg_match('#^http[s]?\\:\\/\\/[a-z0-9\-]+\.([a-z0-9\-]+\.)?[a-z]+#i', $stumble))
		{
			$stumble = '';
		}
	}
	
	// Custom_post_color should not contain characters that are not usable in HTML color code
	if (!preg_match('/^[0-9A-Fa-f]{6}$/', $custom_post_color))
	{
		$custom_post_color = '';
	}

	return;
}

//
// Generates a random password
//
function rand_pass($length = 255, $charset = 'A', $returnMD5 = false)
{
	$charset = strtoupper($charset);
	$charArray = array();
	$upper = range('A', 'Z');
	$lower = range('a', 'z');
	$num = range(0, 9);
	$special = array('~', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '-');
	
	for($x = 0; $x < strlen($charset); $x++)
	{
		switch($charset{$x})	
		{
			case 'A':
				$charArray = array_merge($charArray, $upper, $lower, $num);
				break;
			case 'S':
				$charArray = array_merge($charArray, $special);
				break;
			case 'U':
				$charArray = array_merge($charArray, $upper);
				break;
			case 'L':
				$charArray = array_merge($charArray, $lower);
				break;
			case 'N':
				$charArray = array_merge($charArray, $num);
				break;
		}
	} 
	
	if (version_compare(PHP_VERSION, '4.2.0') == -1)
	{
		mt_srand((double)microtime() * 1234567);
	}

	shuffle($charArray);
	$pass = '';
	for ($x = 0; $x < $length; $x++)
	{
		$pass .= $charArray[mt_rand(0, (sizeof($charArray)-1))];
	}

	if ($returnMD5)
	{
		return array('pass' => $pass, 'md5' => md5($pass));
	}
	else
	{
		return $pass;
	}
}

?>