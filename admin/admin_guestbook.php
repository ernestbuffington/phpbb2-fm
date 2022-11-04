<?php
/** 
*
* @package admin
* @version $Id: admin_guestbook.php,v 1.51.2.9 2004/11/18 17:49:33 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
//	$module['General']['Guestbook'] = $file;
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'includes/guestbook_class.'.$phpEx);

$guest_config = array();
$guest_book = new guestbook();
$guest_config = $guest_book->guest_config();

while (list($config_name, $config_value) = each ($guest_config))
{
	$default_config[$config_name] = isset($HTTP_POST_VARS['submit']) ? str_replace("'", "\'", $config_value) : $config_value;

	$guest_config[$config_name] = ( isset($HTTP_POST_VARS[$config_name]) ) ? $HTTP_POST_VARS[$config_name] : $default_config[$config_name];

	if( isset($HTTP_POST_VARS['submit']) )
	{
		$guest_book->update_guestbook($config_name, $guest_config[$config_name]);
	}
}

if( isset($HTTP_POST_VARS['submit']) )
{
	$message = $lang['Board_config_updated'] . "<br /><br />" . sprintf($lang['Click_return_config'], "<a href=\"" . append_sid("admin_guestbook.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

	message_die(GENERAL_MESSAGE, $message);
}
	
$enable_yes = ( $guest_config['enable_guestbook'] ) ? "checked=\"checked\"" : "";
$enable_no = ( !$guest_config['enable_guestbook'] ) ? "checked=\"checked\"" : "";

$smile_yes = ( $guest_config['no_only_smilies'] ) ? "checked=\"checked\"" : "";
$smile_no = ( !$guest_config['no_only_smilies'] ) ? "checked=\"checked\"" : "";

$quote_yes = ( $guest_config['no_only_quote'] ) ? "checked=\"checked\"" : "";
$quote_no = ( !$guest_config['no_only_quote'] ) ? "checked=\"checked\"" : "";

$hide_yes = ( $guest_config['hide_posts'] ) ? "checked=\"checked\"" : "";
$hide_no = ( !$guest_config['hide_posts'] ) ? "checked=\"checked\"" : "";

$word_wrap_yes = ($guest_config['word_wrap']) ? 'checked="checked"' : '';
$word_wrap_no = (!$guest_config['word_wrap']) ? 'checked="checked"' : '';

$permit_mod_yes = ( $guest_config['permit_mod'] ) ? "checked=\"checked\"" : "";
$permit_mod_no = ( !$guest_config['permit_mod'] ) ? "checked=\"checked\"" : "";

	
$template->set_filenames(array(
	"body" => "admin/guestbook_config_body.tpl")
);

$template->assign_vars(array(
	"S_CONFIG_ACTION" => append_sid("admin_guestbook.$phpEx"),

	"L_CONFIGURATION_TITLE" => $lang['Guestbook'] . ' ' . $lang['Setting'],
	"L_CONFIGURATION_EXPLAIN" => sprintf($lang['Config_explain'], $lang['Guestbook']),
	
	"L_ENABLE" => $lang['G_Enable'],
	"L_PASSWORD" => $lang['G_Password'],
	"L_NO_SMILIES" => $lang['No_only_smilies'],
	"L_NO_QUOTE" => $lang['No_only_quote'],
	"L_HIDE_POSTS" => $lang['Hide_posts'],
	"L_MAXLENGHT" => $lang['Maxlenght_posts'],
	"L_SESSION_POSTING" => $lang['Session_posting'],
	"L_SESSION_POSTING_EXPLAIN" => $lang['Session_posting_explain'],
	"L_WORD_WRAP" => $lang['Word_wrap'],
	"L_WORD_WRAP_LENGTH" => $lang['Word_wrap_length'],
	"L_PERMIT_MOD" => $lang['Permit_mod'],
	"N_VIEW_SMILE" => $lang['N_view_smile'],

	'VERSION' => $guest_config['version'],
	
	"ENABLE_YES" => $enable_yes,
    "ENABLE_NO"=> $enable_no,
    "PASSWORD" => $guest_config['password'],
	"WORD_WRAP_YES" => $word_wrap_yes,
    "WORD_WRAP_NO"=> $word_wrap_no,
    "WORD_WRAP_LENGTH" => $guest_config['word_wrap_length'],
    "SESSION_POSTING" => $guest_config['session_posting'],
	"NO_SMILIES_YES" => $smile_yes,
    "NO_SMILIES_NO" => $smile_no,
	"NO_QUOTE_YES" => $quote_yes,
    "NO_QUOTE_NO" => $quote_no,
    "HIDE_POSTS_YES" => $hide_yes,
	"HIDE_POSTS_NO" => $hide_no,
	"PERMIT_MOD_YES" => $permit_mod_yes,
	"PERMIT_MOD_NO" => $permit_mod_no,
	"SMILIES_COLUMN" => $guest_config['smilies_column'],
	"SMILIES_ROW" => $guest_config['smilies_row'],
	"MAXLENGHT" => $guest_config['maxlenght_posts'])
);

$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

?>