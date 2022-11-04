<?php
/** 
*
* @package includes
* @version $Id: kb_header.php,v 1.0.1 2003/01/13 18:54:16 psotfx Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

define('ALLOW_NEW', 1);
define('ALLOW_ANON', 1);

//
// Parse and show the overall header.
//
$template->set_filenames(array(
	'kb_header' => 'kb_header.tpl')
);

if ( ( $board_config['kb_allow_new'] == ALLOW_NEW && $userdata['session_logged_in'] && $show_new ) || ( $board_config['kb_allow_new'] == ALLOW_NEW && $board_config['kb_allow_anon'] == ALLOW_ANON && $show_new ) || $is_admin && $show_new )
{
   	$add_article = '';
   	if ( $HTTP_GET_VARS['cat'] )
   	{
   		$template->assign_block_vars('switch_add_article', array(
			'L_ADD_ARTICLE' => $lang['Add_article'],
			'ADD_ARTICLE_IMG' => $images['icon_add'], // Specifically for prosilver theme
			'POST_IMG' => $images['post_new'],
			'U_ADD_ARTICLE' => append_sid('kb.'.$phpEx.'?mode=add&amp;cat=' . $HTTP_GET_VARS['cat']))
		);
   	}
    
}

$template->assign_vars(array(
	'L_KB_TITLE' => '<a href="' . append_sid('kb.'.$phpEx) . '"><img src="' . $images['kb_title'] . '" width="285" height="45" alt="' . $lang['KB_title'] . '" title="' . $lang['KB_title'] . '" /></a>',

	'KB_SEARCH_IMG' => $images['icon_search'],
	'U_KB_SEARCH' => append_sid('kb_search.'.$phpEx),

	'U_KB' => append_sid('kb.'.$phpEx),
	'L_KB' => $lang['Kb'])
);

get_quick_stats();

$template->pparse('kb_header');

?>