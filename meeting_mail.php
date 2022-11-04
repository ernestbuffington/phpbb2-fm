<?php
/** 
*
* @package phpBB
* @version $Id: meeting.php,v 1.3.18 2006/08/09 oxpus Exp $
* @copyright (c) 2004 OXPUS
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
define('IN_MEETING', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_MEETING);
init_userprefs($userdata);
//
// End session management
//

// Check and set various parameters
$params = array(
	'submit' => 'submit',
	'cancel' => 'cancel',
	'm_id' => 'm_id',
	'start' => 'start',
	'meeting_mail_subject' => 'meeting_mail_subject',
	'meeting_mail_text' => 'meeting_mail_text',
	'mail_to' => 'mail_to'
);

while( list($var, $param) = @each($params) )
{
	if ( !empty($HTTP_POST_VARS[$param]) || !empty($HTTP_GET_VARS[$param]) )
	{
		$$var = ( !empty($HTTP_POST_VARS[$param]) ) ? $HTTP_POST_VARS[$param] : $HTTP_GET_VARS[$param];
	}
	else
	{
		$$var = '';
	}
}

$sql = "SELECT meeting_by_user, meeting_subject 
	FROM " . MEETING_DATA_TABLE . "
	WHERE meeting_id = " . $m_id;
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, "Could not read meeting data", '', __LINE__, __FILE__, $sql);
}

$meeting_data = $db->sql_fetchrow($result);

$meeting_by_user = $meeting_data['meeting_by_user'];
$meeting_subject = $meeting_data['meeting_subject'];

if ($meeting_by_user == $userdata['user_id'] || $userdata['user_level'] == ADMIN || $userdata['user_level'] == LESS_ADMIN || $userdata['user_level'] == MOD)
{
	$allow_mail = true;
}
else
{
	$allow_mail = false;
}

// Check some values
$m_id = intval($m_id);
$mail_to = intval($mail_to);
$start = intval($start);

// What shall we do on a cancel
if ( $cancel )
{
	$submit = '';
}

// Sending this email
if ( $submit && $allow_mail )
{
	$meeting_mail_subject = str_replace("\'", "''", trim($meeting_mail_subject));
	$meeting_mail_text = str_replace("\'", "''", trim($meeting_mail_text));

	if ($meeting_subject && $meeting_mail_subject && $meeting_mail_text )
	{
		switch ($mail_to)
		{
			case 1:
				$sql_meeting_where = ' AND meeting_sure <> 0 ';
				break;
			case 2:
				$sql_meeting_where = ' AND meeting_sure = 0 ';
				break;
			default:
				$sql_meeting_where = '';
				break;
		}

		$sql = "SELECT u.username, u.user_email, u.user_lang 
			FROM " . MEETING_USER_TABLE . " m, " . USERS_TABLE . " u
			WHERE m.user_id = u.user_id
				AND m.meeting_id = $m_id
				$sql_meeting_where";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not get meeting data', '', __LINE__, __FILE__, $sql);
		}
		
		$script_path = $board_config['script_path'];
		$server_name = trim($board_config['server_name']);
		$server_protocol = ( $board_config['cookie_secure'] ) ? 'https://' : 'http://';
		$server_port = ( $board_config['server_port'] <> 80 ) ? ':' . trim($board_config['server_port']) . '/' : '/';

		$server_url = $server_name . $server_port . $script_path;
		$server_url = $server_protocol . str_replace('//', '/', $server_url);

		include($phpbb_root_path . 'includes/emailer.'.$phpEx);

		if ( preg_match('/[c-z]:\\\.*/i', getenv('PATH')) && !$board_config['smtp_delivery'])
		{
			$ini_val = ( @phpversion() >= '4.0.0' ) ? 'ini_get' : 'get_cfg_var';

			$board_config['smtp_delivery'] = 1;
			$board_config['smtp_host'] = @$ini_val('SMTP');
		}

		while ($row = $db->sql_fetchrow($result))
		{
			$emailer = new emailer($board_config['smtp_delivery']);

			$emailer->from($board_config['board_email']);
			$emailer->replyto($board_config['board_email']);

			$email_headers = 'X-AntiAbuse: Board servername - ' . $board_config['server_name'] . "\n";
			$email_headers .= 'X-AntiAbuse: User_id - ' . $userdata['user_id'] . "\n";
			$email_headers .= 'X-AntiAbuse: Username - ' . $userdata['username'] . "\n";
			$email_headers .= 'X-AntiAbuse: User IP - ' . decode_ip($user_ip) . "\n";

			$emailer->use_template('meeting_email', $row['user_lang']);
			$emailer->email_address($row['user_email']);
			$emailer->set_subject($meeting_mail_subject);
			$emailer->extra_headers($email_headers);

			$emailer->assign_vars(array(
				'BOARD_EMAIL' => $board_config['board_email_sig'],
				'SITENAME' => $board_config['sitename'],
				'MEETING_MAIL_TEXT' => $meeting_mail_text,
				'MEETING_MAIL_SUBJECT' => $meeting_mail_subject,
				'MEETING_SUBJECT' => $meeting_subject,
				'USERNAME' => $row['username'],
				'FROM_USER' => $userdata['username'],
				'U_DOWNLOAD' => $server_url."meeting.$phpEx?mode=detail&m_id=$m_id")
			);

			$emailer->send();
			$emailer->reset();
		}
	}	
}

if ( !$allow_mail || $submit || $cancel )
{
	redirect(append_sid("meeting.$phpEx?mode=detail&m_id=$m_id"));
}

$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_meeting.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_meeting.' . $phpEx);

// Include the board header
$page_title = $lang['Meeting_mail'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

// Entering a new meeting email
$template->set_filenames(array(
	'body' => 'meeting_mail_body.tpl')
);

$template->assign_vars(array(
	'L_MEETING' => $lang['Meeting_viewlist'],
	'L_MEETING_DETAIL' => $meeting_subject,
	'L_MEETING_MAIL' => $lang['Meeting_mail'],
	'L_MEETING_MAIL_SUBJECT' => $lang['Meeting_mail_subject'],
	'L_MEETING_MAIL_TEXT' => $lang['Meeting_mail_text'],
	'L_MEETING_MAIL_TO' => $lang['Meeting_mail_to'],
	'L_MEETING_MAIL_ALL' => $lang['Meeting_mail_all'],
	'L_MEETING_MAIL_SIGN_YES' => $lang['Meeting_mail_sign_yes'],
	'L_MEETING_MAIL_SIGN_NO' => $lang['Meeting_mail_sign_no'],

	'L_CANCEL' => $lang['Cancel'],

	'MEETING_SUBJECT' => $meeting_subject,
	'S_ACTION' => append_sid("meeting_mail.$phpEx?m_id=$m_id"),

	'U_MEETING' => append_sid("meeting.$phpEx"),
	'U_MEETING_DETAIL' => append_sid("meeting.$phpEx?mode=detail&amp;m_id=$m_id"))
);

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>