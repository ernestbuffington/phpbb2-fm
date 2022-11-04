<?php
/** 
*
* @package phpBB2
* @version $Id: profile_notes.php,v 1.159.2.22 2003/07/11 16:46:16 oxpus Exp $
* @copyright (c) 2003 OXPUS
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'includes/functions_post.'.$phpEx);
include($phpbb_root_path . 'includes/functions_search.'.$phpEx);
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_PROFILE);
init_userprefs($userdata);
//
// End session management
//

// Check user logged in
if ( !$userdata['session_logged_in'] ) 
{ 
	redirect('login.'.$phpEx.'?redirect=profile_notes.'.$phpEx); 
	exit; 
} 

//
// Is user notes disabled?
//
if ( !$board_config['enable_user_notes'] )
{ 
	redirect('profile.'.$phpEx.'?mode=editprofile&ucp=main'); 
	exit; 
} 

// Getting submit status
$submit = ($HTTP_POST_VARS['submit']) ? htmlspecialchars($HTTP_POST_VARS['submit']) : '';

// Getting sorting values
$sql_order = ( $HTTP_POST_VARS['sort_order'] == 'DESC' ) ? 'DESC' : 'ASC';
$sql_order_by = ( $HTTP_POST_VARS['sort_by'] == 'post_subject' ) ? 'post_subject' : 'post_time';

// Set sorting
$sort_order = '<select name="sort_order">
	<option value="ASC">' . $lang['Sort_Ascending'] . '</option>
	<option value="DESC">' . $lang['Sort_Descending'] . '</option>
</select>';

$sort_order = str_replace('value="' . $sql_order. ' ">', 'value="' . $sql_order . '" selected="selected">', $sort_order);

$sort_by = '<select name="sort_by">
	<option value="post_subject">' . $lang['Subject'] . '</option>
	<option value="post_time">' . $lang['Time'] . '</option>
</select>';

$sort_by = str_replace('value="' . $sql_order_by . '">', 'value="' . $sql_order_by . '" selected="selected">', $sort_by);

// Getting search string
$search_keywords = ($HTTP_POST_VARS['search_string']) ? $HTTP_POST_VARS['search_string'] : '';
$sql_search_in = ($HTTP_POST_VARS['search_in']) ? $HTTP_POST_VARS['search_in'] : '';

$search_in = '<select name="search_in">
	<option value="post_subject">' . $lang['Subject'] . '</option>
	<option value="post_text">' . $lang['Post'] . '</option>
</select>';

$search_in = str_replace('value="' . $sql_search_in . '">', 'value="' . $sql_search_in . '" selected="selected">', $search_in);

$sql_search = '';

// Prepare search
if ( $search_keywords != '' )
{
	// Look for stopwords and synonyms
	$stopword_array = @file($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/search_stopwords.txt');
	$synonym_array = @file($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/search_synonyms.txt');

	// Prepare search terms
	$split_search = array();
	$sql_search_terms = '';
	$split_search = ( !strstr($multibyte_charset, $lang['ENCODING']) ) ?  split_words(clean_words('search', stripslashes($search_keywords), $stopword_array, $synonym_array), 'search') : split(' ', $search_keywords);

	foreach($split_search as $search_word)
	{
		$sql_search_terms .= ( $sql_search_terms != '' ) ? ' OR ' . $sql_search_in . ' LIKE (\'%'.$search_word . '%\')' : $sql_search_in . ' LIKE (\'%' . $search_word . '%\')';
	}

	$sql_search = ' AND (' . $sql_search_terms . ')';
}

//
// Go ahead and pull all data for the notes
//
$sql = "SELECT * 
	FROM " . NOTES_TABLE . "
	WHERE poster_id = " . $userdata['user_id'] . "
		$sql_search
	ORDER BY $sql_order_by $sql_order";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, "Could not obtain notes/user information.", '', __LINE__, __FILE__, $sql);
}

$i = 0;
$postrow = array();
while ($row = $db->sql_fetchrow($result))
{
	$i++;
	$postrow[] = $row;
}
$db->sql_freeresult($result);

$total_notes = $i;

//
// Load templates
//
$template->set_filenames(array(
	'body' => 'profile_notes_body.tpl')
);
make_jumpbox('viewforum.'.$phpEx);

//
// Output page header
//
if ( $userdata['user_popup_notes'] == TRUE )
{
	$gen_simple_header = TRUE;
	$template->assign_block_vars('switch_popup', array());
}
else
{
	$template->assign_block_vars('switch_no_popup', array());
}

$page_title = $lang['Notes'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

if ( $total_notes < $board_config['notes'] )
{
	$template->assign_block_vars('new_note', array(
		'POST_IMG' => $images['post_new'],
		'U_POST_NEW_TOPIC' => append_sid("posting_notes.$phpEx?mode=newtopic"))
	);
}

//
// Send vars to template
//
$template->assign_vars(array(
	'L_NOTES' => $lang['Notes'] . ' &nbsp; ['. $total_notes . '/' . $board_config['notes'] . ']',
	'L_POSTED' => $lang['Posted'],
	'L_POST_SUBJECT' => $lang['Post_subject'],
	'L_SUBJECT' => $lang['Subject'],
	'L_BACK_TO_TOP' => $lang['Back_to_top'],
	'L_DELETE_TOPIC' => $lang['Delete_topic'],
	'L_CLOSE' => $lang['Close_window'],
	'L_SEARCH' => ( $search_keywords == '' ) ? $lang['Search'] : $lang['Search'] . '*',
	'L_FILTER' => ( $search_keywords != '' ) ? $lang['Filter_notes'] : '',
	'L_SORT' => $lang['Sort'],
	'SORT_ORDER' => $sort_order,
	'SORT_BY' => $sort_by,
	'SEARCH_IN' => $search_in,
	'S_ACTION' => append_sid("profile_notes.$phpEx"))
);

//
// Okay, let's do the loop, yeah come on baby let's do the loop
// and it goes like this ...
//
for($i = 0; $i < $total_notes; $i++)
{
	$subject = stripslashes($postrow[$i]['post_subject']);

	$post_date = create_date($board_config['default_dateformat'], $postrow[$i]['post_time'], $board_config['board_timezone']);
	$message = stripslashes($postrow[$i]['post_text']);
	$bbcode_uid = $postrow[$i]['bbcode_uid'];

	//
	// Parse message for BBCode if reqd
	//
	if ( $bbcode_uid != '' )
	{
		$message = ($board_config['allow_bbcode']) ? bbencode_second_pass($message, $bbcode_uid) : preg_replace("/\:$bbcode_uid/si", '', $message);
	}

	$message = make_clickable($message);
	
	//
 	// ed2k link and add all
	//
	$message = make_addalled2k_link($message, $postrow[$i]['post_id']);

	// Parse smilies
	//
	if ( $board_config['allow_smilies'] )
	{
		if ( $postrow[$i]['smilies'] )
		{
			$message = smilies_pass($message);
		}
	}
	else
	{
		if( $board_config['smilie_removal1'] )
		{
			$message = smilies_code_removal($message);
		}
	}
	
	//
	// Highlight active words (primarily for search)
	//
	if ( $search_keywords != '' )
	{
		foreach($split_search as $search_word)
		{
			// This has been back-ported from 3.0 CVS
			$message = preg_replace('#(?!<.*)(?<!\w)(' . $search_word . ')(?!\w|[^<>]*>)#i', '<b style="color:#'.$theme['fontcolor4'].'">\1</b>', $message);
		}
	}

	//
	// Replace naughty words
	//
	if( !empty($orig_word) )
	{
		$post_subject = preg_replace($orig_word, $replacement_word, $post_subject);

		$message = str_replace('\"', '"', substr(@preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "@preg_replace(\$orig_word, \$replacement_word, '\\0')", '>' . $message . '<'), 1, -1));
	}

	//
	// Replace newlines (we use this rather than nl2br because
	// till recently it wasn't XHTML compliant)
	//
	$message = str_replace("\n", "\n<br />\n", $message);
	$message = word_wrap_pass($message);

	$temp_url = append_sid("posting_notes.$phpEx?mode=editpost&amp;" . POST_POST_URL . "=" . $postrow[$i]['post_id']);
	$edit_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_edit'] . '" alt="' . $lang['Edit_delete_post'] . '" title="' . $lang['Edit_delete_post'] . '" /></a>';
	$edit = '<a href="' . $temp_url . '">' . $lang['Edit_delete_post'] . '</a>';
	$temp_url = append_sid("posting_notes.$phpEx?mode=delete&amp;" . POST_POST_URL . "=" . $postrow[$i]['post_id']);
	$delpost_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_delpost'] . '" alt="' . $lang['Delete_post'] . '" title="' . $lang['Delete_post'] . '" /></a>';
	$delpost = '<a href="' . $temp_url . '">' . $lang['Delete_post'] . '</a>';

	//
	// Again this will be handled by the templating
	// code at some point
	//
	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

	$template->assign_block_vars('postrow', array(
		'ROW_CLASS' => $row_class,
		'POST_DATE' => $post_date,
		'POST_SUBJECT' => trim(stripslashes($subject)),
		'MESSAGE' => trim(stripslashes($message)),
		'ICON_MINIPOST_IMG' => $images['icon_minipost'],
		'DELETE_IMG' => $delpost_img,
		'DELETE' => $delpost,
		'EDIT_IMG' => $edit_img,
		'EDIT' => $edit)
	);
}

if (empty($total_notes))
{
	$template->assign_block_vars('no_notes', array(
		'L_NONE' => $lang['None'])
	);
}

include($phpbb_root_path . 'profile_menu.'.$phpEx);

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>