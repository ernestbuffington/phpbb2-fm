<?php
/** 
*
* @package phpBB2
* @version $Id: faq.php,v 1.14.2.2 2004/07/11 16:46:15 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_FAQ);
init_userprefs($userdata);
//
// End session management
//

if ( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
}
else
{
	$mode = '';
}

include_once(PRILL_PATH . 'prill_common.'.$phpEx);

// Set vars to prevent naughtiness
$faq = array();

//
// Load the appropriate faq file
//
switch($mode)
{
	case 'bbcode':
		$lang_file = 'lang_bbcode';
		$l_title = $lang['BBCode_guide'];
		break;
	case 'moderator_faq':
		$lang_file = 'lang_faq_moderator';
		$l_title = $lang['Moderators_Manual'];
		break;
	default:
		$lang_file = 'lang_faq';
		$l_title = $lang['Faq'];
		break;
}

// Include language file
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/' . $lang_file . '.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/' . $lang_file . '.' . $phpEx);


//
// Pull the array data from the lang pack
//
$j = $counter = $counter_2 = 0;
$faq_block = $faq_block_titles = array();

for($i = 0; $i < sizeof($faq); $i++)
{
	if( $faq[$i][0] != '--' )
	{
		$faq_block[$j][$counter]['id'] = $counter_2;
		$faq_block[$j][$counter]['question'] = $faq[$i][0];
		$faq_block[$j][$counter]['answer'] = $faq[$i][1];

		$counter++;
		$counter_2++;
	}
	else
	{
		$j = ( $counter != 0 ) ? $j + 1 : 0;

		$faq_block_titles[$j] = $faq[$i][1];

		$counter = 0;
	}
}

$total_faq = $counter_2;

// This is the color calculation code, it used to calculate hover color.
$rv2 = hexdec(substr($theme['tr_color1'], 0, 2));
$gv2 = hexdec(substr($theme['tr_color1'], 2, 2));
$bv2 = hexdec(substr($theme['tr_color1'], 4, 2));
$rv2 = ($rv2+255) / 2;
$gv2 = ($gv2+255) / 2;
$bv2 = ($bv2+255) / 2;
$rv = '0' . dechex($rv2);
$gv = '0' . dechex($gv2);
$bv = '0' . dechex($bv2);
$rv = substr($rv,strlen($rv) - 2);
$gv = substr($gv,strlen($gv) - 2);
$bv = substr($bv,strlen($bv) - 2);
$cvl = '#' . $rv . $gv . $bv;

//
// Lets build a page ...
//
$page_title = $l_title;
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'faq_body.tpl')
);
make_jumpbox('viewforum.'.$phpEx);

$template->assign_vars(array(
	'TOTAL_NUMBER_OF_FAQ_ITEMS' => $total_faq,
	'HOVER_COLOR' => $cvl,

	'L_RANKFAQ_BLOCK_TITLE' => $lang['RankFAQ_Block_Title'],
	'L_RANKFAQ_TITLE' => $lang['RankFAQ_Title'],
	'L_RANKFAQ_MIN' => $lang['RankFAQ_Min'],
	'L_RANKFAQ_IMAGE' => $lang['RankFAQ_Image'],

	'L_EXPAND_ALL' => $lang['Expand_all'],
	'L_COLLAPSE_ALL' => $lang['Collapse_all'],
	'L_SEARCH_KEYWORDS' => $lang['Search_keywords'],
	'L_SEARCH_KEYWORDS_FAQ' => $lang['Search_keywords_faq'],
	'L_FAQ_TITLE' => $l_title, 
	'L_BACK_TO_TOP' => $lang['Back_to_top'])
);

for($i = 0; $i < sizeof($faq_block); $i++)
{
	if( sizeof($faq_block[$i]) )
	{
		$template->assign_block_vars('faq_block', array(
			'BLOCK_TITLE' => $faq_block_titles[$i])
		);
		
		$template->assign_block_vars('faq_block_link', array( 
			'BLOCK_TITLE' => $faq_block_titles[$i])
		);

		for($j = 0; $j < sizeof($faq_block[$i]); $j++)
		{
			$row_class = ( !($j % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

			$template->assign_block_vars('faq_block.faq_row', array(
				'ROW_CLASS' => $row_class,
				'FAQ_QUESTION' => $faq_block[$i][$j]['question'], 
				'FAQ_ANSWER' => $faq_block[$i][$j]['answer'], 

				'U_FAQ_ID' => $faq_block[$i][$j]['id'])
			);

			$template->assign_block_vars('faq_block_link.faq_row_link', array(
				'ROW_CLASS' => $row_class,
				'FAQ_LINK' => $faq_block[$i][$j]['question'], 

				'U_FAQ_LINK' => '#' . $faq_block[$i][$j]['id'])
			);
		}
	}
}

//
// Show ranks
//
$sql = "SELECT * 
	FROM " . RANKS_TABLE . " 
	WHERE rank_special = 0 
	ORDER BY rank_min, rank_title";
if (!($result = $db->sql_query($sql))) 
{ 
	message_die(GENERAL_ERROR, 'Could not obtain ranks information', '', __LINE__, __FILE__, $sql); 
}

while ($row = $db->sql_fetchrow($result))
{ 
	$template->assign_block_vars('RankFAQ', array( 
		'RANKFAQ_TITLE' => $row[rank_title], 
		'RANKFAQ_MIN' => ($row[rank_min] >= 0) ? $row[rank_min] : $lang['RankFAQ_None'], 
		'RANKFAQ_IMAGE' => ($row['rank_image'] != '') ? '<img src="templates/' . $template_name . '/images/ranks/' . $row['rank_image'] . '" />' : '')
	);
} 
$db->sql_freeresult($result);

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>