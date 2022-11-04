<?php
/** 
*
* @package phpBB2
* @version $Id: lexicon.php, v 2.0.4.27 2005/06/29 19:09:00 AmigaLink Exp $
* @copyright (c) 2005 AmigaLink
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include($phpbb_root_path . 'includes/functions_post.'.$phpEx);
include($phpbb_root_path . 'includes/functions_lexicon.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_LEXICON);
init_userprefs($userdata);
//
// End session management

//
// Check and set various parameters
// page
( isset($HTTP_GET_VARS['start']) || isset($HTTP_POST_VARS['start']) ) ? $start = ( isset($HTTP_POST_VARS['start']) ) ? intval($HTTP_POST_VARS['start']) : intval($HTTP_GET_VARS['start']) : $start = 0;
$start = ($start < 0) ? 0 : $start;

// Categorie
( isset($HTTP_GET_VARS['cat']) || isset($HTTP_POST_VARS['cat']) ) ? $categorie_id = ( isset($HTTP_POST_VARS['cat']) ) ? intval($HTTP_POST_VARS['cat']) : intval($HTTP_GET_VARS['cat']) : $categorie_id = 0;
($categorie_id != 0) ? $lex_cat_mode = "&cat=$categorie_id" : $lex_cat_mode ='';
// letter
( isset($HTTP_GET_VARS['letter']) || isset($HTTP_POST_VARS['letter']) ) ? $letter = ( isset($HTTP_POST_VARS['letter']) ) ? htmlspecialchars($HTTP_POST_VARS['letter']) : htmlspecialchars($HTTP_GET_VARS['letter']) : $letter = '';
// searchword
( isset($HTTP_GET_VARS['search']) || isset($HTTP_POST_VARS['search']) ) ? $lexicon_searchword = ( isset($HTTP_POST_VARS['lexicon_searchword']) ) ? htmlspecialchars($HTTP_POST_VARS['lexicon_searchword']) : htmlspecialchars($HTTP_GET_VARS['lexicon_searchword']) : $lexicon_searchword = '';


// define some main variables
$lexicon_nav = '<a href="' . append_sid('lexicon.'.$phpEx) . '" class="nav">' . $board_config['lexicon_title'] . '</a>';
$lexicon_nav_seperator = ' -> ';

//
// Generate page ...
//
$page_title = $board_config['lexicon_title'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'lexicon_body.tpl')
);
make_jumpbox('viewforum.'.$phpEx); 

// Grab lexicon categories
$sql = "SELECT cat_id, cat_titel 
	FROM " . LEXICON_CAT_TABLE;
if(!$result = $db->sql_query($sql))
{
	message_die(GENERAL_ERROR, 'Could not get categories list.', '', __LINE__, __FILE__, $sql);
}

while($row = $db->sql_fetchrow($result))
{
	$lexicon_categories[$row['cat_id']] = @$lang[$row['cat_titel']];
	// take db entry if not exist a lang entry
	if ($lexicon_categories[$row['cat_id']] == '')
	{
		$lexicon_categories[$row['cat_id']] = $row['cat_titel'];
	}
}
$lexicon_categories[0] = $lang['overview'];
$db->sql_freeresult($result);

// init crosslinks
$sql = "SELECT keyword 
	FROM " . LEXICON_ENTRY_TABLE . " 
	ORDER BY keyword";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Error getting lexicon entries.', '', __LINE__, __FILE__, $sql);
}

$crosslink_word = array();
$crosslink_url = array();

while ( $row = $db->sql_fetchrow($result) )
{
	$crosslink_word[] = '#\b(' . $row['keyword'] . ')\s#i';
	$crosslink_url[] = '<a href="lexicon.' . $phpEx . '?letter=' . $row['keyword'] . '" class="crosslink">' . $row['keyword'] . '</a> ';
	$crosslink_word[] = '#\s(' . $row['keyword'] . ')\b#i';
	$crosslink_url[] = ' <a href="lexicon.' . $phpEx . '?letter=' . $row['keyword'] . '" class="crosslink">' . $row['keyword'] . '</a>';
}

// Count keywords (is select a categorie count there only) and generate pagination
$sql = "SELECT count(keyword) AS keywords 
	FROM " . LEXICON_ENTRY_TABLE;
if ( $categorie_id != 0 )
{
	if ($letter)
	{
		$sql .= " WHERE keyword LIKE '$letter%' AND cat = '$categorie_id'";
	}
	else
	{
		$sql .= " WHERE cat = '$categorie_id'";
	}
}
else if ($letter)
{
	$sql .= " WHERE keyword LIKE '$letter%'";
}

if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Error getting lexicon entrys', '', __LINE__, __FILE__, $sql);
}

if( $number_of_keywords = $db->sql_fetchrow($result) )
{
	$page = $number_of_keywords['keywords'];
	if ($letter)
	{
		$pagination = generate_pagination("lexicon.$phpEx?cat=$categorie_id&letter=$letter", $page, $board_config['topics_per_page'], $start). '&nbsp;';
	}
	else
	{
		$pagination = generate_pagination("lexicon.$phpEx?cat=$categorie_id", $page, $board_config['topics_per_page'], $start). '&nbsp;';
	}
}
$db->sql_freeresult($result);

if ( $number_of_keywords > intval($board_config['topics_per_page']) && $lexicon_searchword == '' ) 
{
	$template->assign_vars(array( 
		'PAGINATION' => $pagination, 
		'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / intval($board_config['topics_per_page']) ) + 1 ), ceil( $page / intval($board_config['topics_per_page']))))
	); 
} 

// Generate categories dropdown list if exists more than the default categorie
(count($lexicon_categories) > 2) ? $lexicon_cat_selector = lexicon_catselector($lexicon_categories, $categorie_id) : $lexicon_cat_selector ='';

// Generate header navigation
if ($categorie_id != 0)
{
	($lexicon_categories[1] == ' ' && $categorie_id == 1) ? $nav_cat_titel = $lang['generally'] : $nav_cat_titel = $lexicon_categories[$categorie_id];
	$lexicon_nav .= $lexicon_nav_seperator.'<a href="'.append_sid("lexicon.$phpEx?$lex_cat_mode").'" class="nav">'.$nav_cat_titel.'</a>';
}

// Which letters are present?
$sql = "SELECT DISTINCT ord(keyword) as letters 
	FROM " . LEXICON_ENTRY_TABLE;
if ( $categorie_id != 0 )
{
	$sql .= " WHERE cat = '$categorie_id'";
}

if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Error getting lexicon entries', '', __LINE__, __FILE__, $sql);
}
$present_letters = ' ';
while($row = $db->sql_fetchrow($result)) $present_letters.=strtoupper(chr($row['letters']));
$db->sql_freeresult($result);

// Generate navigation
$first_letter_navigation = extra_nav($letter, $present_letters, $categorie_id);  // Special character (show only if exist)
//$first_letter_navigation = extra_nav($letter, $present_letters, $categorie_id, true);  // Special character (show all)
$first_letter_navigation .= letter_nav($letter, $present_letters, $categorie_id);  // Alphabet

if ( isset($HTTP_GET_VARS['all']) || isset($HTTP_POST_VARS['all']))
{
	$first_letter_navigation .= '<b>&nbsp;-&nbsp;&nbsp;<font class="letter2">'.$lang['show_all'].'</a></b>';
}
else
{
	$first_letter_navigation .= '<b>&nbsp;-&nbsp;&nbsp;<a href="'.append_sid("lexicon.$phpEx?$lex_cat_mode").'" class="letter">'.$lang['show_all'].'</a></b>';
}

($letter) ? $counter_letter = sprintf($lang['Letter_count'], $letter) : $counter_letter = '';

$template->assign_vars(array(
	'FIRST_LETTER_NAVIGATION' => $first_letter_navigation,
	'KEYWORD_COUNT' => ($categorie_id == 0) ? sprintf($lang['Keyword_count_main'], $number_of_keywords['keywords'], $board_config['lexicon_title'], $counter_letter) : sprintf($lang['Keyword_count_cat'],  $number_of_keywords['keywords'], $board_config['lexicon_title'],$counter_letter),
	'LEXICON_NAV' => $lexicon_nav,
	'LEXICON_TITLE' => $board_config['lexicon_title'],
	'LEXICON_DESCRIPTION' => $board_config['lexicon_description'],

	'L_SEARCH' => $lang['Search'],
	
	'U_LEXICON' => append_sid('lexicon.'.$phpEx))
);


// prepare db-querie
$sql = "SELECT * FROM 
	" . LEXICON_ENTRY_TABLE;
if ($letter)
{
	// only a keyword or a letter selected?
	if ($categorie_id == 0)
	{
		// all categories
		$sql .= " WHERE keyword LIKE '$letter%' ORDER BY keyword, cat LIMIT $start, ". $board_config['topics_per_page'];
	}
	else
	{
		// only selected categorie
		$sql .= " WHERE keyword LIKE '$letter%' AND cat = '$categorie_id' ORDER BY keyword LIMIT $start, ". $board_config['topics_per_page'];
	}
}
else
{
	if($lexicon_searchword != '')
	{
		//  search	
		$sql .= " WHERE keyword LIKE '%$lexicon_searchword%' OR explanation LIKE '%$lexicon_searchword%' ORDER BY keyword, cat";
	}
	else
	{	
		if ($categorie_id == 0)
		{
			// show all
			$sql .= " ORDER BY keyword, cat LIMIT $start, ". $board_config['topics_per_page'];
		}
		else
		{
			// show all of selected categorie
			$sql .= " WHERE cat = '$categorie_id' ORDER BY keyword LIMIT $start, ". $board_config['topics_per_page'];
		}
	}
}
       
// do db-querie and show the result
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Error getting lexicon entries.', '', __LINE__, __FILE__, $sql);
}

while($val = $db->sql_fetchrow($result))
{
	$title_anchor = $val['keyword'];
	($lexicon_row %2 != 1 ) ? $row_class='row1' : $row_class='row2';

	$categorie = $lexicon_categories[$val['cat']];
	$explanation = smilies_pass($val['explanation']);
	$explanation = bbencode_second_pass($explanation, $val['bbcode_uid']);
	$explanation = make_clickable($explanation);
	$explanation = unprepare_message($explanation);
	$explanation = str_replace("\n", "\n<br />\n", $explanation);

	// highlight searchwords
	if($lexicon_searchword)
	{
		$highlight = '<span style="color:#FFA34F; font-weight:bold;">\1</span>';
		$val['keyword'] = str_highlight($val['keyword'], $lexicon_searchword, STR_HIGHLIGHT_SIMPLE, $highlight);
		$explanation = str_highlight($explanation, $lexicon_searchword, STR_HIGHLIGHT_STRIPLINKS, $highlight);
	}

	$explanation = parse_crosslinks($explanation);

	$template->assign_block_vars('lexicon_row', array(
		'ROW_CLASS' => $row_class,
		'ANCHOR' => $title_anchor,
		'KEYWORD' => '<a href="'.append_sid("lexicon.$phpEx?letter=$title_anchor$lex_cat_mode").'">'.$val['keyword'].'</a>',
		'CATEGORIE' => $categorie,
		'EXPLANATION' => $explanation)
	);
	$lexicon_row++;
}

if (!$lexicon_row)
{
	$template->assign_vars(array( 
		'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("lexicon.$phpEx") . '">',
		'L_INDEX' => '',
		'U_INDEX' => '')
	);

	($lexicon_searchword) ? $message = sprintf($lang['Lexicon_search_error'], $lexicon_searchword) : $message = sprintf($lang['Lexicon_error'], $letter, $lexicon_categories[$categorie_id]);
	
	$message .= '<br /><br />' . sprintf($lang['Click_return_lexicon'], '<a href="' . append_sid("lexicon.$phpEx") . '">', '</a>', $board_config['lexicon_title']);
	
	message_die(GENERAL_MESSAGE, $message);
}

$db->sql_freeresult($result);

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