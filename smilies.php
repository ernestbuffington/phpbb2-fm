<?php
/** 
*
* @package phpBB2
* @version $Id: smilies.php,v 1.1.0 2002/04/05 02:43:12 nivisec Exp $
* @copyright (c) 2002 Nivisec.com
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
define('IN_PHPBB', true);
$phpbb_root_path = "./";
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);


//
// Include language file
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_admin.'.$phpEx) ) { $language = 'english'; } include ($phpbb_root_path . 'language/lang_' . $language . '/lang_admin.' . $phpEx);


//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_SMILES);
init_userprefs($userdata);
//
// End session management
//


//
// Check smilies are enabled
//
if ( !$board_config['allow_smilies'] ) 
{ 
	message_die(GENERAL_MESSAGE, $lang['Emoticons_disable'] . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.'.$phpEx) .'">', '</a>')); 
}


//
// Obtain Smilies
//
$sql = "SELECT code, smile_url, emoticon
	FROM " . SMILIES_TABLE;
if (!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, 'Could not obtain smilie data', '', __LINE__, __FILE__, $sql);
}


//
// Sort into 2-D array indexed by image
//
while( $smilie_data = $db->sql_fetchrow($result) )
{
	$smilie_url_array[$smilie_data['smile_url']]['emoticon'] = $smilie_data['emoticon'];
	$smilie_url_array[$smilie_data['smile_url']]['code'] = ( !isset($smilie_url_array[$smilie_data['smile_url']]['code']) ) ? $smilie_data['code'] : $smilie_url_array[$smilie_data['smile_url']]['code'] . ' or ' . $smilie_data['code'];
}
	
$db->sql_freeresult($result);


//
// Assign template block vars and live happily ever after
//
$count = 0;
while ( list($key) = each($smilie_url_array) )
{
	$count++;
	
	$template->assign_block_vars('smilies', array(
		'URL' => '<img src="' . $board_config['smilies_path'] . '/' . $key . '" alt="' . $smilie_url_array[$key]['emoticon'] . '" title="' . $smilie_url_array[$key]['emoticon'] . '" />',
		'EMOTICON' => $smilie_url_array[$key]['emoticon'],
		'START' => ( ($count % 2) ) ? '<tr>' : '',
		'END' => ( !($count % 2) ) ? '</tr>' : '',
		'CODE' => $smilie_url_array[$key]['code'])
	);
}


//
// Generate the page
//
$page_title = $lang['Emoticons'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->assign_vars(array(
	'L_EMOTICONS' => $lang['Emoticons'],
	'L_IMAGE' => $lang['smiley_url'],
	'L_CODE' => $lang['smiley_code'],
	'CLASS_1' => $theme['td_class1'],
	'CLASS_2' => $theme['td_class2'],
	'PAGE_NAME' => $page_title)
);

$template->set_filenames(array(
	'body' => 'smilies_body.tpl')
);
make_jumpbox('viewforum.'.$phpEx); 

//
// Force password update
//
if ($board_config['password_update_days'])
{
	include($phpbb_root_path . 'includes/update_password.'.$phpEx);
}

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>