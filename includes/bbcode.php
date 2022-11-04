<?php
/** 
*
* @package includes
* @version $Id: bbcode.php,v 1.36.2.32 2004/07/11 16:46:19 acydburn Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

define("BBCODE_UID_LEN", 10);

global $phpbb_root_path, $phpEx;

include_once($phpbb_root_path . 'includes/functions_digests.'.$phpEx);

// global that holds loaded-and-prepared bbcode templates, so we only have to do
// that stuff once.
$bbcode_tpl = null;


/**
 * Multiple BBCode
 */
function Multi_BBCode()
{
	global $template, $lang, $is_auth, $userdata, $HTTP_SERVER_VARS;

	$hotkeys = array('', 'd', 'e', 'g', 'h', 'j', 'k', 'm', 'n', 'r', 's', 't', 'u', 'v', 'v1', 'x', 'x1', 'y', 'y1', 'z', 1, 2, 3, 4, 5, 6, 7, 8, 9, 0);
	$EMBB_widths = array('', 55, 50, 50, 50, 50, 50, 50, 50, 50, 65, 55, 55, 50, 50, 60, 50, 55, 50, 50, 55, 50, 50, 50, 60, 70, 50, 50, 50, 65);
	$EMBB_values = array('', $lang['BBCode_border'], $lang['BBCode_blur'], $lang['Code'], $lang['BBCode_ebay'], $lang['Edit'], $lang['BBCode_fade'], $lang['BBCode_flash'], $lang['BBCode_flipv'], $lang['BBCode_fliph'], $lang['BBCode_footnote'], $lang['BBCode_google'], $lang['BBCode_googlevid'], $lang['BBCode_hr'], $lang['BBCode_mp3'], $lang['BBCode_offtopic'], $lang['BBCode_pre'], $lang['Search'], $lang['BBCode_scroll'], $lang['BBCode_spoil'], $lang['BBCode_stream'], $lang['BBCode_strike'], $lang['BBCode_tab'], $lang['BBCode_table'], $lang['BBCode_updown'], $lang['Username'], $lang['BBCode_video'], $lang['BBCode_wave'], $lang['BBCode_yahoo'], $lang['BBCode_youtube']);

	if ( ($is_auth['auth_mod']) || ( ($userdata['user_level'] != 0) && ( strpos( basename($HTTP_SERVER_VARS['PHP_SELF']), 'privmsg') !== false ) ) )
	{
		$hotkeys[] = 'z1';
		$EMBB_widths[] = 50;
		$EMBB_values[] = $lang['Mod'];
	}
	
	$board_config['bbcode_rows'] = ( defined('IN_ADMIN') ) ? 5 : 8;
	
	$max_rows = ( (sizeof($EMBB_values) - 1) / $board_config['bbcode_rows']);
	$max_rows = ( $max_rows * 8 == sizeof($EMBB_values) ) ? $max_rows : $max_rows + 1;
	$code_count = 1;
	for ($i = 1; $i <= $max_rows; $i++)
	{
		$template->assign_block_vars('XBBcode', array(
			'ROW_ID' => $i)
		);
		
		for ($element=0; $element < $board_config['bbcode_rows']; $element++)
		{
			$val = ($code_count * 2) + ($board_config['bbcode_rows'] * 2 - 2);
			if ( $code_count < sizeof($EMBB_values))
			{
				$help_lang = ( !empty($lang['bbcode_help'][(strtolower($EMBB_values[$code_count]))]) ) ? $lang['bbcode_help'][(strtolower($EMBB_values[$code_count]))] : $lang['bbcode_help'][$EMBB_values[$code_count]];
				
				$template->assign_block_vars('XBBcode.BB', array(
					'KEY' => $hotkeys[$code_count],
					'NAME' => "addbbcode$val",
					'HELP' => sprintf($help_lang, $hotkeys[$code_count]), 
					'WIDTH' => $EMBB_widths[$code_count],
					'VALUE' => $EMBB_values[$code_count],
					'STYLE' => "bbstyle($val)")
				);
			}
			$code_count++;
		}
	}
}	


/**
 * Emoticon Creator
 */
function phpbb_schild($smilie, $parameter, $text)
{
	global $phpEx;
	
	$text = trim(urlencode($text));
	$fontcolor = 000000;
	$shadowcolor = '';
	$shieldshadow = 1;

	$parameter = trim($parameter);
	if ( !empty($parameter) )
	{
		$parameter = explode(' ', $parameter);
		$parameter2 = array();

		if ( !empty($parameter) )
		{
			reset($parameter);
			while (list( , $line) = each($parameter) )
			{
				if ( ( $pos = strpos(' ' . $line, '=') ) )
				{
					$name = substr($line, 0, $pos - 1);
					$value = substr($line, $pos);
					$parameter2[$name] = $value;
				}
			}

			if ( !empty($parameter2['fontcolor']) )
			{
				$fontcolor = $parameter2['fontcolor'];
			}

			if ( !empty($parameter2['shadowcolor']) )
			{
				$shadowcolor = $parameter2['shadowcolor'];
			}

			if ( $parameter2['shieldshadow'] == 0)
			{
				$shieldshadow = 0;
			}
			else
			{
				if ( !empty($parameter2['shieldshadow']) )
				{
					$shieldshadow = $shieldshadow;
				}
			}
		}
	}
	
	return "text2schild.".$phpEx."?smilie=$smilie&amp;fontcolor=$fontcolor&amp;shadowcolor=$shadowcolor&amp;shieldshadow=$shieldshadow&amp;text=$text";
}


/**
 * Loads bbcode templates from the bbcode.tpl file of the current template set.
 * Creates an array, keys are bbcode names like "b_open" or "url", values
 * are the associated template.
 * Probably pukes all over the place if there's something really screwed
 * with the bbcode.tpl file.
 *
 * Nathan Codding, Sept 26 2001.
 */
function load_bbcode_template()
{
	global $template, $bbfile;

	$bbfile = (!isset($bbfile)) ? "bbcode.tpl" : "bbcode_wap.tpl";
	$tpl_filename = $template->make_filename($bbfile);
	$tpl = fread(fopen($tpl_filename, 'r'), filesize($tpl_filename));

	// replace \ with \\ and then ' with \'.
	$tpl = str_replace('\\', '\\\\', $tpl);
	$tpl  = str_replace('\'', '\\\'', $tpl);

	// strip newlines.
	$tpl  = str_replace("\n", '', $tpl);

	// Turn template blocks into PHP assignment statements for the values of $bbcode_tpls..
	$tpl = preg_replace('#<!-- BEGIN (.*?) -->(.*?)<!-- END (.*?) -->#', "\n" . '$bbcode_tpls[\'\\1\'] = \'\\2\';', $tpl);

	$bbcode_tpls = array();

	eval($tpl);

	return $bbcode_tpls;
}


/**
 * Prepares the loaded bbcode templates for insertion into preg_replace()
 * or str_replace() calls in the bbencode_second_pass functions. This
 * means replacing template placeholders with the appropriate preg backrefs
 * or with language vars. NOTE: If you change how the regexps work in
 * bbencode_second_pass(), you MUST change this function.
 *
 * Nathan Codding, Sept 26 2001
 *
 */
function prepare_bbcode_template($bbcode_tpl)
{
	global $lang, $board_config, $phpEx, $u_login_logout, $theme, $images;

	$bbcode_tpl['olist_open'] = str_replace('{LIST_TYPE}', '\\1', $bbcode_tpl['olist_open']);

	$bbcode_tpl['color_open'] = str_replace('{COLOR}', '\\1', $bbcode_tpl['color_open']);

	$bbcode_tpl['size_open'] = str_replace('{SIZE}', '\\1', $bbcode_tpl['size_open']);

	$bbcode_tpl['quote_open'] = str_replace('{L_QUOTE}', $lang['Quote'], $bbcode_tpl['quote_open']);

	$bbcode_tpl['quote_username_open'] = str_replace('{L_QUOTE}', $lang['Quote'], $bbcode_tpl['quote_username_open']);
	$bbcode_tpl['quote_username_open'] = str_replace('{L_WROTE}', $lang['wrote'], $bbcode_tpl['quote_username_open']);
	$bbcode_tpl['quote_username_open'] = str_replace('{USERNAME}', '\\1', $bbcode_tpl['quote_username_open']);

	$bbcode_tpl['mod_open'] = str_replace('{MOD_WARN}', $lang['Mod_warning'], $bbcode_tpl['mod_open']);

	$bbcode_tpl['mod_username_open'] = str_replace('{L_WROTE}', $lang['wrote'], $bbcode_tpl['mod_username_open']);
	$bbcode_tpl['mod_username_open'] = str_replace('{MOD_WARN}', $lang['Mod_warning'], $bbcode_tpl['mod_username_open']);
	$bbcode_tpl['mod_username_open'] = str_replace('{USERNAME}', '\\1', $bbcode_tpl['mod_username_open']);

	$bbcode_tpl['code_open'] = str_replace('{L_CODE}', $lang['Code'], $bbcode_tpl['code_open']);

	$bbcode_tpl['edit_open'] = str_replace('{L_EDIT}', $lang['Edit'], $bbcode_tpl['edit_open']);

	$bbcode_tpl['spoiler_open'] = str_replace('{L_SPOILER}', $lang['Spoiler'], $bbcode_tpl['spoiler_open']);
	
	$reduce_img = ($board_config['reduce_bbcode_imgs']) ? ' onload="javascript:if(this.width > screen.width-200)this.width = (screen.width-200)" onclick="javascript:window.open(\'\\1\',\'\',\'scrollbars=1,toolbar=0,resizable=1,menubar=0,directories=0,status=0\')" alt="' . $lang['Reduced_image'] . '" title="' . $lang['Reduced_image'] . '"'  : '';
	$missing_img = ($board_config['missing_bbcode_imgs']) ? ' onError="javascript:this.src=\'' . $images['missing_image'] . '\'"'  : '';

	$bbcode_tpl['img'] = str_replace('{MISSING_IMG}', $missing_img, str_replace('{REDUCED_IMG}', $reduce_img, str_replace('{URL}', '\\1', $bbcode_tpl['img'])));
	$bbcode_tpl['left'] = str_replace('{MISSING_IMG}', $missing_img, str_replace('{REDUCED_IMG}', $reduce_img, str_replace('{URL}', '\\1', $bbcode_tpl['left'])));
	$bbcode_tpl['right'] = str_replace('{MISSING_IMG}', $missing_img, str_replace('{REDUCED_IMG}', $reduce_img, str_replace('{URL}', '\\1', $bbcode_tpl['right'])));

	// We do URLs in several different ways..
	$bbcode_tpl['url1'] = str_replace('{URL}', '\\1', $bbcode_tpl['url']);
	$bbcode_tpl['url1'] = str_replace('{DESCRIPTION}', '\\1', $bbcode_tpl['url1']);

	$bbcode_tpl['url2'] = str_replace('{URL}', 'http://\\1', $bbcode_tpl['url']);
	$bbcode_tpl['url2'] = str_replace('{DESCRIPTION}', '\\1', $bbcode_tpl['url2']);

	$bbcode_tpl['url3'] = str_replace('{URL}', '\\1', $bbcode_tpl['url']);
	$bbcode_tpl['url3'] = str_replace('{DESCRIPTION}', '\\2', $bbcode_tpl['url3']);

	$bbcode_tpl['url4'] = str_replace('{URL}', 'http://\\1', $bbcode_tpl['url']);
	$bbcode_tpl['url4'] = str_replace('{DESCRIPTION}', '\\3', $bbcode_tpl['url4']);

	$bbcode_tpl['email'] = str_replace('{EMAIL}', '\\1', $bbcode_tpl['email']);

	$bbcode_tpl['align_open'] = str_replace('{ALIGN}', '\\1', $bbcode_tpl['align_open']);
 
	$bbcode_tpl['flash'] = str_replace('{WIDTH}', '\\1', $bbcode_tpl['flash']); 
	$bbcode_tpl['flash'] = str_replace('{HEIGHT}', '\\2', $bbcode_tpl['flash']); 
	$bbcode_tpl['flash'] = str_replace('{LOOP}', '\\3', $bbcode_tpl['flash']); 
	$bbcode_tpl['flash'] = str_replace('{URL}', '\\4', $bbcode_tpl['flash']); 
	$bbcode_tpl['cf'] = str_replace('{URL}', '\\1', $bbcode_tpl['cf']); 

	$bbcode_tpl['highlight_open'] = str_replace('{HIGHLIGHTCOLOR}', '\\1', $bbcode_tpl['highlight_open']);

    $bbcode_tpl['video'] = str_replace('{URL}', '\\3', $bbcode_tpl['video']);
    $bbcode_tpl['video'] = str_replace('{WIDTH}', '\\1', $bbcode_tpl['video']);
    $bbcode_tpl['video'] = str_replace('{HEIGHT}', '\\2', $bbcode_tpl['video']);

	$bbcode_tpl['stream'] = str_replace('{URL}', '\\1', $bbcode_tpl['stream']);

	$bbcode_tpl['font_open'] = str_replace('{FONT}', '\\1', $bbcode_tpl['font_open']);

	$bbcode_tpl['glow_open'] = str_replace('{GLOWCOLOR}', '\\1', $bbcode_tpl['glow_open']);
	
	$bbcode_tpl['shadow_open'] = str_replace('{SHADOWCOLOR}', '\\1', $bbcode_tpl['shadow_open']);

	$bbcode_tpl['schild'] = str_replace('{URL}', "' . phpbb_schild('\\1', '\\2', '\\3') . '", "'" . $bbcode_tpl['schild'] . "'");

  	$bbcode_tpl['google'] = '\'' . $bbcode_tpl['google'] . '\'';
  	$bbcode_tpl['google'] = str_replace('{STRING}', "' . str_replace('\\\"', '\"', '\\1') . '", $bbcode_tpl['google']);
  	$bbcode_tpl['google'] = str_replace('{QUERY}', "' . urlencode(str_replace('\\\"', '\"', '\\1')) . '", $bbcode_tpl['google']);

	$bbcode_tpl['yahoo'] = '\'' . $bbcode_tpl['yahoo'] . '\'';
	$bbcode_tpl['yahoo'] = str_replace('{STRING}', "' . str_replace('\\\"', '\"', '\\1') . '", $bbcode_tpl['yahoo']);
	$bbcode_tpl['yahoo'] = str_replace('{QUERY}', "' . urlencode(str_replace('\\\"', '\"', '\\1')) . '", $bbcode_tpl['yahoo']);

	$bbcode_tpl['ebay'] = '\'' . $bbcode_tpl['ebay'] . '\'';
	$bbcode_tpl['ebay'] = str_replace('{STRING}', "' . str_replace('\\\"', '\"', '\\1') . '", $bbcode_tpl['ebay']);
	$bbcode_tpl['ebay'] = str_replace('{QUERY}', "' . urlencode(str_replace('\\\"', '\"', '\\1')) . '", $bbcode_tpl['ebay']);

	$bbcode_tpl['search'] = str_replace('{KEYWORD}', '\\1', $bbcode_tpl['search']);

	$bbcode_tpl['youtube'] = str_replace('{YOUTUBEID}', '\\1', $bbcode_tpl['youtube']);
	$bbcode_tpl['youtube'] = str_replace('{YOUTUBELINK}', $lang['BBCode_youtube'] . ' ' . $lang['Link'], $bbcode_tpl['youtube']);

	$bbcode_tpl['googlevid'] = str_replace('{GVIDEOID}', '\\1', $bbcode_tpl['googlevid']);
	$bbcode_tpl['googlevid'] = str_replace('{GVIDEOLINK}', $lang['BBCode_googlevid'] . ' ' . $lang['Link'], $bbcode_tpl['googlevid']);

	$bbcode_tpl['table_mainrow_color'] = str_replace('{TABMRCOLOR}', '\\1', $bbcode_tpl['table_mainrow_color']);
	$bbcode_tpl['table_mainrow_size'] = str_replace('{TABMRSIZE}', '\\1', $bbcode_tpl['table_mainrow_size']);
	$bbcode_tpl['table_mainrow_cs1'] = str_replace('{TABMRCSCOLOR}', '\\1', $bbcode_tpl['table_mainrow_cs']);
	$bbcode_tpl['table_mainrow_cs1'] = str_replace('{TABMRCSSIZE}', '\\2', $bbcode_tpl['table_mainrow_cs1']);
	$bbcode_tpl['table_maincol_color'] = str_replace('{TABMCCOLOR}', '\\1', $bbcode_tpl['table_maincol_color']);
	$bbcode_tpl['table_maincol_size'] = str_replace('{TABMCSIZE}', '\\1', $bbcode_tpl['table_maincol_size']);
	$bbcode_tpl['table_maincol_cs1'] = str_replace('{TABMCCSCOLOR}', '\\1', $bbcode_tpl['table_maincol_cs']);
	$bbcode_tpl['table_maincol_cs1'] = str_replace('{TABMCCSSIZE}', '\\2', $bbcode_tpl['table_maincol_cs1']);
	$bbcode_tpl['table_row_color'] = str_replace('{TABRCOLOR}', '\\1', $bbcode_tpl['table_row_color']);
	$bbcode_tpl['table_row_size'] = str_replace('{TABRSIZE}', '\\1', $bbcode_tpl['table_row_size']);
	$bbcode_tpl['table_row_cs1'] = str_replace('{TABRCSCOLOR}', '\\1', $bbcode_tpl['table_row_cs']);
	$bbcode_tpl['table_row_cs1'] = str_replace('{TABRCSSIZE}', '\\2', $bbcode_tpl['table_row_cs1']);
	$bbcode_tpl['table_col_color'] = str_replace('{TABCCOLOR}', '\\1', $bbcode_tpl['table_col_color']);
	$bbcode_tpl['table_col_size'] = str_replace('{TABCSIZE}', '\\1', $bbcode_tpl['table_col_size']);
	$bbcode_tpl['table_col_cs1'] = str_replace('{TABCCSCOLOR}', '\\1', $bbcode_tpl['table_col_cs']);
	$bbcode_tpl['table_col_cs1'] = str_replace('{TABCCSSIZE}', '\\2', $bbcode_tpl['table_col_cs1']);
	$bbcode_tpl['table_size'] = str_replace('{TABSIZE}', '\\1', $bbcode_tpl['table_size']);
	$bbcode_tpl['table_color'] = str_replace('{TABCOLOR}', '\\1', $bbcode_tpl['table_color']);
	$bbcode_tpl['table_cs1'] = str_replace('{TABCSCOLOR}', '\\1', $bbcode_tpl['table_cs']);
	$bbcode_tpl['table_cs1'] = str_replace('{TABCSSIZE}', '\\2', $bbcode_tpl['table_cs1']);

	$bbcode_tpl['login_request'] = str_replace('{L_TITLE}', $lang['Information'], $bbcode_tpl['login_request']);
	$bbcode_tpl['login_request'] = str_replace('{L_WARNING}', $lang['Links_Allowed_For_Registered_Only'], $bbcode_tpl['login_request']);
	$bbcode_tpl['login_request'] = str_replace('{GET_REGISTERED}', sprintf($lang['Get_Registered'], "<a href=\"" . append_sid('profile.' . $phpEx . '?mode=' . REGISTER_MODE) . "\">", "</a>"), $bbcode_tpl['login_request']);
	$bbcode_tpl['login_request'] = str_replace('{ENTER_FORUM}', sprintf($lang['Enter_Forum'], "<a href=\"" . append_sid($u_login_logout) . "\">", "</a>"), $bbcode_tpl['login_request']);

	$bbcode_tpl['post_count_request'] = str_replace('{L_TITLE}', $lang['Information'], $bbcode_tpl['post_count_request']);
	$bbcode_tpl['post_count_request'] = str_replace('{L_WARNING}', sprintf($lang['Post_Limit'], $board_config['hl_necessary_post_number']), $bbcode_tpl['post_count_request']);

	define("BBCODE_TPL_READY", true);

	return $bbcode_tpl;
}


/**
 * This function is used to remove the target="_blank" from any url that is posted
 * that matches the server name from the $board_config array. That way of people want
 * to link internally from one post to another, they won't open new windows all the
 * time. Only external links will open a new target window.
 * Mod Author: Dave Rathbun (drathbun@aol.com)
 */

function fix_local_urls($text)
{
   global $board_config;

   // match for url local links. If you are running multiple phpBB forums
   // on the same server, you might want to use the following line instead:
   // $local_url = '<a href="http://' . strtolower($board_config['server_name']) . strtolower($board_config['script_path']);

   // note that strtolower is ONLY used for comparison, the actual text of the URL is not changed
   $local_url = '<a href="http://' . strtolower($board_config['server_name']);

   // Find first local link in the post text, if any. If none, remainder of the
   // code is skipped.
   $start_url = strpos(strtolower($text), $local_url);

   // If any are found, process, otherwise drop out of function. Note that since
   // the first step done before processing the post text is to add one extra space,
   // we don't need to worry about the case where a url might be in the first position.
   // It won't. :-)
   while ($start_url)
   {
      // first, identify the end of the URL by locating the closing >
      $last_found_pos = $start_url + 1;
      $end_url = strpos ($text, '>', $last_found_pos);
      $url_len = $end_url - $start_url + 1;

      // get copy of URL from <a href=... to closing >
      $my_url = substr($text, $start_url, $url_len);

      // replace target with null string
      $my_local_url = str_replace('blank', 'top', $my_url);
      
      // replace old URL with new URL in post text. Note that while the
      // comparison was done with strtolower() to be sure to find all matches,
      // the actual URL text is not changed in any way. Only the target.
      $text = str_replace($my_url, $my_local_url, $text);

      // advance by length of URL - length of "target" string, check for next local URL
      $start_url = strpos(strtolower($text), $local_url, $last_found_pos + $url_len - 16);
   }

   return $text;
}


/**
 * Does second-pass bbencoding. This should be used before displaying the message in
 * a thread. Assumes the message is already first-pass encoded, and we are given the
 * correct UID as used in first-pass encoding.
 */
function bbencode_second_pass($text, $uid)
{
	global $lang, $bbcode_tpl, $userdata, $board_config, $is_auth; 

 	$text = preg_replace('#(script|about|applet|activex|chrome):#is', "\\1&#058;", $text);
 
	// pad it with a space so we can distinguish between FALSE and matching the 1st char (index 0).
	// This is important; bbencode_quote(), bbencode_list(), and bbencode_code() all depend on it.
	$text = " " . $text;

	// First: If there isn't a "[" and a "]" in the message, don't bother.
	if (! (strpos($text, "[") && strpos($text, "]")) )
	{
		// Remove padding, return.
		$text = substr($text, 1);
		return $text;
	}

	// Only load the templates ONCE..
	if (!defined("BBCODE_TPL_READY"))
	{
		// load templates from file into array.
		$bbcode_tpl = load_bbcode_template();

		// prepare array for use in regexps.
		$bbcode_tpl = prepare_bbcode_template($bbcode_tpl);
	}

	// [CODE] and [/CODE] for posting code (HTML, PHP, C etc etc) in your posts.
	$text = bbencode_second_pass_code($text, $uid, $bbcode_tpl);

	// [QUOTE] and [/QUOTE] for posting replies with quote, or just for quoting stuff.
	$text = bbencode_second_pass_quote($text, $uid, $bbcode_tpl);

	// [offtopic] and [/offtopic] for posting replies with quote, or just for quoting stuff.
	$text = str_replace("[offtopic]", $bbcode_tpl['offtopic_open'], $text);
	$text = str_replace("[/offtopic]", $bbcode_tpl['offtopic_close'], $text);

	// New one liner to deal with opening quotes with usernames...
	// replaces the two line version that I had here before..
	$text = preg_replace("/\[quote:$uid=\"(.*?)\"\]/si", $bbcode_tpl['quote_username_open'], $text);

	// [mod] and [/mod] for posting moderator code in posts.
	$text = str_replace("[mod:$uid]", $bbcode_tpl['mod_open'], $text); 
	$text = str_replace("[/mod:$uid]", $bbcode_tpl['mod_close'], $text);
	$text = preg_replace("/\[mod:$uid=\"(.*?)\"\]/si", $bbcode_tpl['mod_username_open'], $text);

	// [list] and [list=x] for (un)ordered lists.
	// unordered lists
	$text = str_replace("[list:$uid]", $bbcode_tpl['ulist_open'], $text);
	// li tags
	$text = str_replace("[*:$uid]", $bbcode_tpl['listitem'], $text);
	// ending tags
	$text = str_replace("[/list:u:$uid]", $bbcode_tpl['ulist_close'], $text);
	$text = str_replace("[/list:o:$uid]", $bbcode_tpl['olist_close'], $text);
	// Ordered lists
	$text = preg_replace("/\[list=([a1]):$uid\]/si", $bbcode_tpl['olist_open'], $text);

	// colours
	$text = preg_replace("/\[color=(\#[0-9A-F]{6}|[a-z]+):$uid\]/si", $bbcode_tpl['color_open'], $text);
	$text = str_replace("[/color:$uid]", $bbcode_tpl['color_close'], $text);

	// size
	$text = preg_replace("/\[size=([1-2]?[0-9]):$uid\]/si", $bbcode_tpl['size_open'], $text);
	$text = str_replace("[/size:$uid]", $bbcode_tpl['size_close'], $text);

	// [b] and [/b] for bolding text.
	$text = str_replace("[b:$uid]", $bbcode_tpl['b_open'], $text);
	$text = str_replace("[/b:$uid]", $bbcode_tpl['b_close'], $text);

	// [u] and [/u] for underlining text.
	$text = str_replace("[u:$uid]", $bbcode_tpl['u_open'], $text);
	$text = str_replace("[/u:$uid]", $bbcode_tpl['u_close'], $text);

	// [i] and [/i] for italicizing text.
	$text = str_replace("[i:$uid]", $bbcode_tpl['i_open'], $text);
	$text = str_replace("[/i:$uid]", $bbcode_tpl['i_close'], $text);

	// align
	$text = preg_replace("/\[align=(left|right|center|justify):$uid\]/si", $bbcode_tpl['align_open'], $text);
	$text = str_replace("[/align:$uid]", $bbcode_tpl['align_close'], $text);

	// [blur] and [/blur] for blurring text. 
	$text = str_replace("[blur:$uid]", $bbcode_tpl['blur_open'], $text); 
	$text = str_replace("[/blur:$uid]", $bbcode_tpl['blur_close'], $text); 

	// [fade] and [/fade] for faded text.
	$text = str_replace("[fade:$uid]", $bbcode_tpl['fade_open'], $text);
	$text = str_replace("[/fade:$uid]", $bbcode_tpl['fade_close'], $text);

	// [flipv] and [/flipv] for flipped text. 
	$text = str_replace("[flipv:$uid]", $bbcode_tpl['flipv_open'], $text);
	$text = str_replace("[/flipv:$uid]", $bbcode_tpl['flipv_close'], $text);

	// [fliph] and [/fliph] for flipped text. 
	$text = str_replace("[fliph:$uid]", $bbcode_tpl['fliph_open'], $text);
	$text = str_replace("[/fliph:$uid]", $bbcode_tpl['fliph_close'], $text);

	// [highlight] and [/highlight] for highlighing text.
	$text = preg_replace("/\[highlight=(\#[0-9A-F]{6}|[a-z]+):$uid\]/si", $bbcode_tpl['highlight_open'], $text);
	$text = str_replace("[/highlight:$uid]", $bbcode_tpl['highlight_close'], $text);

    // [strike] and [/strike] for barring text. 
    $text = str_replace("[strike:$uid]", $bbcode_tpl['strike_open'], $text); 
    $text = str_replace("[/strike:$uid]", $bbcode_tpl['strike_close'], $text); 
	
	// [spoiler] and [/spoiler] for entering spoiled text.
	$text = str_replace("[spoiler:$uid]", $bbcode_tpl['spoiler_open'], $text);
	$text = str_replace("[/spoiler:$uid]", $bbcode_tpl['spoiler_close'], $text);

	// [table] and [/table] for making tables. 
	// beginning code [table], along with attributes
	$text = str_replace("[table:$uid]", $bbcode_tpl['table_open'], $text);
	$text = preg_replace("/\[table color=(\#[0-9A-F]{6}|[a-z]+):$uid\]/si", $bbcode_tpl['table_color'], $text);
	$text = preg_replace("/\[table fontsize=([1-2]?[0-9]):$uid\]/si", $bbcode_tpl['table_size'], $text);
	$text = preg_replace("/\[table color=(\#[0-9A-F]{6}|[a-z]+) fontsize=([1-2]?[0-9]):$uid\]/si", $bbcode_tpl['table_cs1'], $text);
	// mainrow tag [mrow], along with attributes
	$text = str_replace("[mrow:$uid]", $bbcode_tpl['table_mainrow'], $text);
	$text = preg_replace("/\[mrow color=(\#[0-9A-F]{6}|[a-z]+):$uid\]/si", $bbcode_tpl['table_mainrow_color'], $text);
	$text = preg_replace("/\[mrow fontsize=([1-2]?[0-9]):$uid\]/si", $bbcode_tpl['table_mainrow_size'], $text);
	$text = preg_replace("/\[mrow color=(\#[0-9A-F]{6}|[a-z]+) fontsize=([1-2]?[0-9]):$uid\]/si", $bbcode_tpl['table_mainrow_cs1'], $text);
	// maincol tag [mcol], along with attributes
	$text = str_replace("[mcol:$uid]", $bbcode_tpl['table_maincol'], $text);
	$text = preg_replace("/\[mcol color=(\#[0-9A-F]{6}|[a-z]+):$uid\]/si", $bbcode_tpl['table_maincol_color'], $text);
	$text = preg_replace("/\[mcol fontsize=([1-2]?[0-9]):$uid\]/si", $bbcode_tpl['table_maincol_size'], $text);
	$text = preg_replace("/\[mcol color=(\#[0-9A-F]{6}|[a-z]+) fontsize=([1-2]?[0-9]):$uid\]/si", $bbcode_tpl['table_maincol_cs1'], $text);
	// row tag [row], along with attributes
	$text = str_replace("[row:$uid]", $bbcode_tpl['table_row'], $text);
	$text = preg_replace("/\[row color=(\#[0-9A-F]{6}|[a-z]+):$uid\]/si", $bbcode_tpl['table_row_color'], $text);
	$text = preg_replace("/\[row fontsize=([1-2]?[0-9]):$uid\]/si", $bbcode_tpl['table_row_size'], $text);
	$text = preg_replace("/\[row color=(\#[0-9A-F]{6}|[a-z]+) fontsize=([1-2]?[0-9]):$uid\]/si", $bbcode_tpl['table_row_cs1'], $text);
	// column tag [col], along with attributes
	$text = str_replace("[col:$uid]", $bbcode_tpl['table_col'], $text);
	$text = preg_replace("/\[col color=(\#[0-9A-F]{6}|[a-z]+):$uid\]/si", $bbcode_tpl['table_col_color'], $text);
	$text = preg_replace("/\[col fontsize=([1-2]?[0-9]):$uid\]/si", $bbcode_tpl['table_col_size'], $text);
	$text = preg_replace("/\[col color=(\#[0-9A-F]{6}|[a-z]+) fontsize=([1-2]?[0-9]):$uid\]/si", $bbcode_tpl['table_col_cs1'], $text);
	// ending tag [/table]
	$text = str_replace("[/table:$uid]", $bbcode_tpl['table_close'], $text);

	// [scroll] and [/scroll] for scrolling text.
	$text = str_replace("[scroll:$uid]", $bbcode_tpl['scroll_open'], $text);
	$text = str_replace("[/scroll:$uid]", $bbcode_tpl['scroll_close'], $text);

	// [updown] and [/updown] for scrolling text.
	$text = str_replace("[updown:$uid]", $bbcode_tpl['updown_open'], $text);
	$text = str_replace("[/updown:$uid]", $bbcode_tpl['updown_close'], $text);

    // [hr] for line break.
    $text = str_replace("[hr:$uid]", $bbcode_tpl['hr'], $text);

	// [font] and [/font] for setting font style (note: The font=(.*?) is needed for Non-Western font names)
	$text = preg_replace("/\[font=(.*?):$uid\]/si", $bbcode_tpl['font_open'], $text);
	$text = str_replace("[/font:$uid]", $bbcode_tpl['font_close'], $text);

	// [glow=red] and [/glow] for glowing text.
	$text = preg_replace("/\[glow=(\#[0-9A-F]{6}|[a-z]+):$uid\]/si", $bbcode_tpl['glow_open'], $text);
	$text = str_replace("[/glow:$uid]", $bbcode_tpl['glow_close'], $text);

	// [shadow=red] and [/shadow] for shadowed text.
	$text = preg_replace("/\[shadow=(\#[0-9A-F]{6}|[a-z]+):$uid\]/si", $bbcode_tpl['shadow_open'], $text);
	$text = str_replace("[/shadow:$uid]", $bbcode_tpl['shadow_close'], $text);

	// [username] for reader username.
	$text = str_replace("[username:$uid]", $userdata['username'], $text); 

	// [wave] and [/wave] for waveing text. 
	$text = str_replace("[wave:$uid]", $bbcode_tpl['wave_open'], $text); 
	$text = str_replace("[/wave:$uid]", $bbcode_tpl['wave_close'], $text); 

	// [tab] for inserting tabs.
	$text = str_replace("[tab:$uid]", $bbcode_tpl['tab'], $text);

	// [border] and [/border] for making a border. 
   	$text = str_replace("[border:$uid]", $bbcode_tpl['border_open'], $text); 
   	$text = str_replace("[/border:$uid]", $bbcode_tpl['border_close'], $text); 

	// [mp3] and [/mp3] for adding mp3 audio files
	$text = str_replace("[mp3:$uid]", $bbcode_tpl['mp3_open'], $text);
	$text = str_replace("[/mp3:$uid]", $bbcode_tpl['mp3_close'], $text);
	
	// Patterns and replacements for URL and email tags..
	$patterns = $replacements = array();

	// [img]image_url_here[/img] code..
	// This one gets first-passed..
	$patterns[] = "#\[img:$uid\]([^?](?:[^\[]+|\[(?!url))*?)\[/img:$uid\]#i";
	$replacements[] = $bbcode_tpl['img'];

	// [img=left]image_url_here[/img] code..
	$patterns[] = "#\[img=left:$uid\](.*?)\[/img:$uid\]#si";
	$replacements[] = $bbcode_tpl['left'];

	// [img=right]image_url_here[/img] code..
	$patterns[] = "#\[img=right:$uid\](.*?)\[/img:$uid\]#si";
	$replacements[] = $bbcode_tpl['right'];

	$url_replacer = ($board_config['hl_enable'] ) ? (( !$userdata['session_logged_in'] ) ? $bbcode_tpl['login_request'] : ( ( ( $userdata['user_posts'] >= $board_config['hl_necessary_post_number'] ) || ( $userdata['user_level'] == ADMIN ) || ( ( $is_auth['auth_mod'] == 1 ) && ( $board_config['hl_mods_priority'] == 1 ) ) ) ? '' : $bbcode_tpl['post_count_request'] )) : '';

	// matches a [url]xxxx://www.phpbb.com[/url] code..
	$patterns[] = "#\[url\]([\w]+?://([\w\#$%&~/.\-;:=,?@\]+]+|\[(?!url=))*?)\[/url\]#is";
   	$replacements[] =  ( $url_replacer ) ? $url_replacer : $bbcode_tpl['url1'];

   	// [url]www.phpbb.com[/url] code.. (no xxxx:// prefix).
	$patterns[] = "#\[url\]((www|ftp)\.([\w\#$%&~/.\-;:=,?@\]+]+|\[(?!url=))*?)\[/url\]#is";
   	$replacements[] =  ( $url_replacer ) ? $url_replacer : $bbcode_tpl['url2'];

	// [url=xxxx://www.phpbb.com]phpBB[/url] code..
	$patterns[] = "#\[url=([\w]+?://[\w\#$%&~/.\-;:=,?@\[\]+]*?)\]([^?\n\r\t].*?)\[/url\]#is";
	$replacements[] =  ( $url_replacer ) ? $url_replacer : $bbcode_tpl['url3'];

	// [url=www.phpbb.com]phpBB[/url] code.. (no xxxx:// prefix).
	$patterns[] = "#\[url=((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*?)\]([^?\n\r\t].*?)\[/url\]#is";
	$replacements[] =  ( $url_replacer ) ? $url_replacer : $bbcode_tpl['url4'];

	// [email]user@domain.tld[/email] code..
	$patterns[] = "#\[email\]([a-z0-9&\-_.]+?@[\w\-]+\.([\w\-\.]+\.)?[\w]+)\[/email\]#si";
	$replacements[] =  ( $url_replacer ) ? $url_replacer : $bbcode_tpl['email'];

	// [flash width= height= loop= ] and [/flash] code.. 
	$patterns[] = "#\[flash width=([0-8]?[0-9]?[0-9]) height=([0-6]?[0-9]?[0-9]) loop=(true|false):$uid\](.*?)\[/flash:$uid\]#si"; 
	$replacements[] = $bbcode_tpl[flash]; 
	
	// [flash] and [/flash] code.. 
	$patterns[] = "#\[flash:$uid\](.*?)\[/flash:$uid\]#si"; 
	$replacements[] = $bbcode_tpl[cf]; 

    // [video width= height= ] and [/video] code..
    $patterns[] = "#\[video width=([0-6]?[0-9]?[0-9]) height=([0-5]?[0-9]?[0-9]):$uid\](.*?)\[/video:$uid\]#si";
    $replacements[] = $bbcode_tpl['video'];

	// [stream] and [/stream] code..
	$patterns[] = "#\[stream:$uid\](.*?)\[/stream:$uid\]#si";
	$replacements[] = $bbcode_tpl['stream'];

	// [schild=] and [/schild] code..
    $patterns[] = "#\[schild=([a-z0-9]+)([a-z0-9\-\.,\?!% \*_\#:;~\\&$@\/=\+\\\\)]*)\](.*?)\[/schild\]#sie";
    $replacements[] = $bbcode_tpl['schild'];

	// [google]string for search[/google] code..
	$patterns[] = "#\[google\](.*?)\[/google\]#ise";
	$replacements[] = $bbcode_tpl['google'];

	// [yahoo]string for search[/yahoo] code..
	$patterns[] = "#\[yahoo\](.*?)\[/yahoo\]#ise";
	$replacements[] = $bbcode_tpl['yahoo'];

	// [ebay]string for search[/ebay] code..
	$patterns[] = "#\[ebay\](.*?)\[/ebay\]#ise";
	$replacements[] = $bbcode_tpl['ebay'];
	
	// [search]string for search[/search] code..
	$patterns[] = "#\[search:$uid\](.*?)\[/search:$uid\]#si";
	$replacements[] = $bbcode_tpl['search'];

   	// [edit] and [/edit] for editing text. 
   	$text = str_replace("[edit:$uid]", $bbcode_tpl['edit_open'], $text); 
   	$text = str_replace("[/edit:$uid]", $bbcode_tpl['edit_close'], $text);

	// [url]ed2k://|file|...[/url] code..
	$patterns[] = "#\[url\](ed2k://\|file\|(.*?)\|\d+\|\w+\|(h=\w+\|)?/?)\[/url\]#is";
	$replacements[] = '<a href="$1" class="postlink">$2</a>';
	
	// [url=ed2k://|file|...]name[/url] code..
	$patterns[] = "#\[url=(ed2k://\|file\|(.*?)\|\d+\|\w+\|(h=\w+\|)?/?)\](.*?)\[/url\]#si";
	$replacements[] = '<a href="$1" class="postlink">$4</a>';
	
	// [url]ed2k://|server|ip|port|/[/url] code..
	$patterns[] = "#\[url\](ed2k://\|server\|([\d\.]+?)\|(\d+?)\|/?)\[/url\]#si";
	$replacements[] = 'ed2k server: <a href="$1" class="postlink">$2:$3</a>';
	
	// [url=ed2k://|server|ip|port|/]name[/url] code..
	$patterns[] = "#\[url=(ed2k://\|server\|[\d\.]+\|\d+\|/?)\](.*?)\[/url\]#si";
	$replacements[] = '<a href="$1" class="postlink">$2</a>';
	
	// [url]ed2k://|friend|name|ip|port|/[/url] code..
	$patterns[] = "#\[url\](ed2k://\|friend\|(.*?)\|[\d\.]+\|\d+\|/?)\[/url\]#si";
	$replacements[] = 'ed2k friend: <a href="$1" class="postlink">$2</a>';
	
	// [url=ed2k://|friend|name|ip|port|/]name[/url] code..
	$patterns[] = "#\[url=(ed2k://\|friend\|(.*?)\|[\d\.]+\|\d+\|/?)\](.*?)\[/url\]#si";
	$replacements[] = '<a href="$1" class="postlink">$3</a>';

	// [youtube]YouTube URL[/youtube] code..
	$patterns[] = "#\[youtube\]([0-9A-Za-z-_]{11})\[/youtube\]#is";
	$replacements[] = $bbcode_tpl['youtube'];

	// [googlevid]GoggleVideo URL[/googlevid] code..
	$patterns[] = "#\[googlevid\]([0-9]{18,20})\[/googlevid\]#is";
	$replacements[] = $bbcode_tpl['googlevid'];

	// [pre] for preserving formatted text.
	$text = preg_replace("#\[pre:$uid\](.*?)\[/pre:$uid\]#sie", "'${bbcode_tpl['pre_open']}' . str_replace('\\\"', '\"', str_replace(array('\r\n', '\n', '\r'), '<br />', '\\1')) . '${bbcode_tpl['pre_close']}'", $text);
	$text = str_replace("<br /><br />", "<br />&nbsp;<br />", $text);

	$text = preg_replace($patterns, $replacements, $text);

   	$text = fix_local_urls($text);

	// Remove our padding from the string..
	$text = substr($text, 1);

	return $text;

} // bbencode_second_pass()

// Need to initialize the random numbers only ONCE
mt_srand( (double) microtime() * 1000000);

function make_bbcode_uid()
{
	// Unique ID for this message..

	$uid = dss_rand();
	$uid = substr($uid, 0, BBCODE_UID_LEN);

	return $uid;
}

function bbencode_first_pass($text, $uid)
{
	// pad it with a space so we can distinguish between FALSE and matching the 1st char (index 0).
	// This is important; bbencode_quote(), bbencode_list(), and bbencode_code() all depend on it.
	$text = " " . $text;

	// [CODE] and [/CODE] for posting code (HTML, PHP, C etc etc) in your posts.
	$text = bbencode_first_pass_pda($text, $uid, '[code]', '[/code]', '', true, '');

	// [QUOTE] and [/QUOTE] for posting replies with quote, or just for quoting stuff.
	$text = bbencode_first_pass_pda($text, $uid, '[quote]', '[/quote]', '', false, '');
	$text = bbencode_first_pass_pda($text, $uid, '/\[quote=\\\\&quot;(.*?)\\\\&quot;\]/is', '[/quote]', '', false, '', "[quote:$uid=\\\"\\1\\\"]");

	// [MOD] and [/MOD] for Moderator Tags 
	$text = bbencode_first_pass_pda($text, $uid, '[mod]', '[/mod]', '', false, '');
	$text = bbencode_first_pass_pda($text, $uid, '/\[mod=\\\\&quot;(.*?)\\\\&quot;\]/is', '[/mod]', '', false, '', "[mod:$uid=\\\"\\1\\\"]");

	// [list] and [list=x] for (un)ordered lists.
	$open_tag = array();
	$open_tag[0] = "[list]";

	// unordered..
	$text = bbencode_first_pass_pda($text, $uid, $open_tag, "[/list]", "[/list:u]", false, 'replace_listitems');

	$open_tag[0] = "[list=1]";
	$open_tag[1] = "[list=a]";

	// ordered.
	$text = bbencode_first_pass_pda($text, $uid, $open_tag, "[/list]", "[/list:o]",  false, 'replace_listitems');


	// [color] and [/color] for setting text color
	$text = preg_replace("#\[color=(\#[0-9A-F]{6}|[a-z\-]+)\](.*?)\[/color\]#si", "[color=\\1:$uid]\\2[/color:$uid]", $text);

	// [size] and [/size] for setting text size
	$text = preg_replace("#\[size=([1-2]?[0-9])\](.*?)\[/size\]#si", "[size=\\1:$uid]\\2[/size:$uid]", $text);

	// [b] and [/b] for bolding text.
	$text = preg_replace("#\[b\](.*?)\[/b\]#si", "[b:$uid]\\1[/b:$uid]", $text);

	// [u] and [/u] for underlining text.
	$text = preg_replace("#\[u\](.*?)\[/u\]#si", "[u:$uid]\\1[/u:$uid]", $text);

	// [i] and [/i] for italicizing text.
	$text = preg_replace("#\[i\](.*?)\[/i\]#si", "[i:$uid]\\1[/i:$uid]", $text);

	// [img]image_url_here[/img] code..
	$text = preg_replace("#\[img\]((http|ftp|https|ftps)://)([^ \?&=\#\"\n\r\t<]*?(\.(jpg|jpeg|gif|png)))\[/img\]#sie", "'[img:$uid]\\1' . str_replace(' ', '%20', '\\3') . '[/img:$uid]'", $text);

	$text = preg_replace("#\[img=left]((http|ftp|https|ftps)://)([^ \?&=\#\"\n\r\t<]*?(\.(jpg|jpeg|gif|png)))\[/img\]#sie", "'[img=left:$uid]\\1' . str_replace(' ', '%20', '\\3') . '[/img:$uid]'", $text);
	$text = preg_replace("#\[img=right]((http|ftp|https|ftps)://)([^ \?&=\#\"\n\r\t<]*?(\.(jpg|jpeg|gif|png)))\[/img\]#sie", "'[img=right:$uid]\\1' . str_replace(' ', '%20', '\\3') . '[/img:$uid]'", $text);

	// [align] and [/align] for setting text align
	$text = preg_replace("#\[align=(left|right|center|justify)\](.*?)\[/align\]#si", "[align=\\1:$uid]\\2[/align:$uid]", $text);

	// [blur] and [/blur] for blurring text. 
	$text = preg_replace("#\[blur\](.*?)\[/blur\]#si", "[blur:$uid]\\1[/blur:$uid]", $text); 

	// [fade] and [/fade] for fading text.
	$text = preg_replace("#\[fade\](.*?)\[/fade\]#si", "[fade:$uid]\\1[/fade:$uid]", $text);

	// [flash width= heigth= loop=] and [/flash] code..
	$text = preg_replace("#\[flash width=([0-8]?[0-9]?[0-9]) height=([0-6]?[0-5]?[0-9]) loop=(true|false)\](([a-z]+?)://([^, \n\r]+))\[\/flash\]#si","[flash width=\\1 height=\\2 loop=\\3:$uid\]\\4[/flash:$uid]", $text); 
	$text = preg_replace("#\[flash width=([0-8]?[0-9]?[0-9]) height=([0-6]?[0-5]?[0-9])\](([a-z]+?)://([^, \n\r]+))\[\/flash\]#si","[flash width=\\1 height=\\2 loop=false:$uid\]\\3[/flash:$uid]", $text); 
	$text = preg_replace("#\[flash\](([a-z]+?)://([^, \n\r]+))\[\/flash\]#si","[flash:$uid\]\\1[/flash:$uid]", $text); 

	// [flipv] and [/flipv] for vertically flipped text.
	$text = preg_replace("#\[flipv\](.*?)\[/flipv\]#si", "[flipv:$uid]\\1[/flipv:$uid]", $text);

	// [fliph] and [/fliph] for horizontally flipped text.
	$text = preg_replace("#\[fliph\](.*?)\[/fliph\]#si", "[fliph:$uid]\\1[/fliph:$uid]", $text);

	// [highlight] and [/highlight] for highlighting text.
	$text = preg_replace("#\[highlight=(\#[0-9A-F]{6}|[a-z\-]+)\](.*?)\[/highlight\]#si", "[highlight=\\1:$uid]\\2[/highlight:$uid]", $text);

    // [video width= heigth=] and [/video] for video.
    $text = preg_replace("#\[video width=([0-6]?[0-9]?[0-9]) height=([0-4]?[0-5]?[0-9])\](([a-z]+?)://([^, \n\r]+))\[\/video\]#si","[video width=\\1 height=\\2:$uid\]\\3[/video:$uid]", $text);

	// [stream]image_url_here[/stream] for streaming video.
	$text = preg_replace("#\[stream\](([a-z]+?)://([^, \n\r]+))\[/stream\]#si", "[stream:$uid]\\1[/stream:$uid]", $text);

    // [strike] and [/strike] for barring text. 
    $text = preg_replace("#\[strike\](.*?)\[/strike\]#si", "[strike:$uid]\\1[/strike:$uid]", $text); 

	// [spoiler] and [/spoiler] for spoiled text.
	$text = preg_replace("#\[spoiler\](.*?)\[/spoiler\]#si", "[spoiler:$uid]\\1[/spoiler:$uid]", $text);

	// [table] and [/table] for making tables.
	// beginning code [table], along with attributes
	$text = preg_replace("#\[table\](.*?)\[/table\]#si", "[table:$uid]\\1[/table:$uid]", $text);
	$text = preg_replace("#\[table color=(\#[0-9A-F]{6}|[a-z\-]+)\](.*?)\[/table\]#si", "[table color=\\1:$uid]\\2[/table:$uid]", $text);
	$text = preg_replace("#\[table fontsize=([1-2]?[0-9])\](.*?)\[/table\]#si", "[table fontsize=\\1:$uid]\\2[/table:$uid]", $text);
	$text = preg_replace("#\[table color=(\#[0-9A-F]{6}|[a-z\-]+) fontsize=([1-2]?[0-9])\](.*?)\[/table\]#si", "[table color=\\1 fontsize=\\2:$uid]\\3[/table:$uid]", $text);
	$text = preg_replace("#\[table fontsize=([1-2]?[0-9]) color=(\#[0-9A-F]{6}|[a-z\-]+)\](.*?)\[/table\]#si", "[table color=\\2 fontsize=\\1:$uid]\\3[/table:$uid]", $text);
	// mainrow tag [mrow], along with attributes
	$text = preg_replace("#\[mrow\](.*?)#si", "[mrow:$uid]\\1", $text);
	$text = preg_replace("#\[mrow color=(\#[0-9A-F]{6}|[a-z\-]+)\](.*?)#si", "[mrow color=\\1:$uid]\\2", $text);
	$text = preg_replace("#\[mrow fontsize=([1-2]?[0-9])\](.*?)#si", "[mrow fontsize=\\1:$uid]\\2", $text);
	$text = preg_replace("#\[mrow color=(\#[0-9A-F]{6}|[a-z\-]+) fontsize=([1-2]?[0-9])\](.*?)#si", "[mrow color=\\1 fontsize=\\2:$uid]\\3", $text);
	$text = preg_replace("#\[mrow fontsize=([1-2]?[0-9]) color=(\#[0-9A-F]{6}|[a-z\-]+)\](.*?)#si", "[mrow color=\\2 fontsize=\\1:$uid]\\3", $text);
	// maincol tag [mcol], along with attributes
	$text = preg_replace("#\[mcol\](.*?)#si", "[mcol:$uid]\\1", $text);
	$text = preg_replace("#\[mcol color=(\#[0-9A-F]{6}|[a-z\-]+)\](.*?)#si", "[mcol color=\\1:$uid]\\2", $text);
	$text = preg_replace("#\[mcol fontsize=([1-2]?[0-9])\](.*?)#si", "[mcol fontsize=\\1:$uid]\\2", $text);
	$text = preg_replace("#\[mcol color=(\#[0-9A-F]{6}|[a-z\-]+) fontsize=([1-2]?[0-9])\](.*?)#si", "[mcol color=\\1 fontsize=\\2:$uid]\\3", $text);
	$text = preg_replace("#\[mcol fontsize=([1-2]?[0-9]) color=(\#[0-9A-F]{6}|[a-z\-]+)\](.*?)#si", "[mcol color=\\2 fontsize=\\1:$uid]\\3", $text);
	// row tag [row], along with attributes
	$text = preg_replace("#\[row\](.*?)#si", "[row:$uid]\\1", $text);
	$text = preg_replace("#\[row color=(\#[0-9A-F]{6}|[a-z\-]+)\](.*?)#si", "[row color=\\1:$uid]\\2", $text);
	$text = preg_replace("#\[row fontsize=([1-2]?[0-9])\](.*?)#si", "[row fontsize=\\1:$uid]\\2", $text);
	$text = preg_replace("#\[row color=(\#[0-9A-F]{6}|[a-z\-]+) fontsize=([1-2]?[0-9])\](.*?)#si", "[row color=\\1 fontsize=\\2:$uid]\\3", $text);
	$text = preg_replace("#\[row fontsize=([1-2]?[0-9]) color=(\#[0-9A-F]{6}|[a-z\-]+)\](.*?)#si", "[row color=\\2 fontsize=\\1:$uid]\\3", $text);
	// column tag [col], along with attributes
	$text = preg_replace("#\[col\](.*?)#si", "[col:$uid]\\1", $text);
	$text = preg_replace("#\[col color=(\#[0-9A-F]{6}|[a-z\-]+)\](.*?)#si", "[col color=\\1:$uid]\\2", $text);
	$text = preg_replace("#\[col fontsize=([1-2]?[0-9])\](.*?)#si", "[col fontsize=\\1:$uid]\\2", $text);
	$text = preg_replace("#\[col color=(\#[0-9A-F]{6}|[a-z\-]+) fontsize=([1-2]?[0-9])\](.*?)#si", "[col color=\\1 fontsize=\\2:$uid]\\3", $text);
	$text = preg_replace("#\[col fontsize=([1-2]?[0-9]) color=(\#[0-9A-F]{6}|[a-z\-]+)\](.*?)#si", "[col color=\\2 fontsize=\\1:$uid]\\3", $text);

	// [scroll] and [/scroll] for scrolling text.
	$text = preg_replace("#\[scroll\](.*?)\[/scroll\]#si", "[scroll:$uid]\\1[/scroll:$uid]", $text);

	// [updown] and [/updown] for scrolling text.
	$text = preg_replace("#\[updown\](.*?)\[/updown\]#si", "[updown:$uid]\\1[/updown:$uid]", $text);

    // [hr] for horizontal line break.
    $text = preg_replace("#\[hr\]#si", "[hr:$uid]", $text);

	// [font] and [/font] for setting font style (note: The font=(.*?) is needed for Non-Western font names)
	$text = preg_replace("#\[font=(.*?)\](.*?)\[/font\]#si", "[font=\\1:$uid]\\2[/font:$uid]", $text);

	// [glow=red] and [/glow] for glowing text.
	$text = preg_replace("#\[glow=(\#[0-9A-F]{6}|[a-z\-]+)\](.*?)\[/glow\]#si", "[glow=\\1:$uid]\\2[/glow:$uid]", $text);

	// [shadow=red] and [/shadow] for shadowed text.
	$text = preg_replace("#\[shadow=(\#[0-9A-F]{6}|[a-z\-]+)\](.*?)\[/shadow\]#si", "[shadow=\\1:$uid]\\2[/shadow:$uid]", $text);

	// [username] for reader username.
	$text = preg_replace("#\[username\]#si", "[username:$uid]", $text); 

	// [wave] and [/wave] for waveing text. 
	$text = preg_replace("#\[wave\](.*?)\[/wave\]#si", "[wave:$uid]\\1[/wave:$uid]", $text); 

	// [tab] for tabbed text.
	$text = preg_replace("#\[tab\]#si", "[tab:$uid]", $text);

	// [footnote] and [/footnote] for footnote text.
	$text = preg_replace("#\[footnote\](.*?)\[/footnote\]#si", "[footnote:$uid]\\1[/footnote:$uid]", $text);

	// [search] and [/search] for board search string text.
	$text = preg_replace("#\[search\](.*?)\[/search\]#si", "[search:$uid]\\1[/search:$uid]", $text);

   	// [border] and [/border] for bordering objects. 
   	$text = preg_replace("#\[border\](.*?)\[/border\]#si", "[border:$uid]\\1[/border:$uid]", $text); 

 	// [edit] and [/edit] for editing text. 
 	$text = preg_replace("#\[edit\](.*?)\[/edit\]#si", "[edit:$uid]\\1[/edit:$uid]", $text);

	// [pre] and [/pre] for preserving formatted text (<pre>).
	$text = preg_replace("#\[pre\](.*?)\[/pre\]#si", "[pre:$uid]\\1[/pre:$uid]", $text);

	// [mp3] and [/mp3] for adding mp3 audio files.
	$text = bbencode_first_pass_pda($text, $uid, '[mp3]', '[/mp3]', '', false, '');
	
	// Remove our padding from the string..
	return substr($text, 1);;

} // bbencode_first_pass()

/**
 * $text - The text to operate on.
 * $uid - The UID to add to matching tags.
 * $open_tag - The opening tag to match. Can be an array of opening tags.
 * $close_tag - The closing tag to match.
 * $close_tag_new - The closing tag to replace with.
 * $mark_lowest_level - boolean - should we specially mark the tags that occur
 * 					at the lowest level of nesting? (useful for [code], because
 *						we need to match these tags first and transform HTML tags
 *						in their contents..
 * $func - This variable should contain a string that is the name of a function.
 *				That function will be called when a match is found, and passed 2
 *				parameters: ($text, $uid). The function should return a string.
 *				This is used when some transformation needs to be applied to the
 *				text INSIDE a pair of matching tags. If this variable is FALSE or the
 *				empty string, it will not be executed.
 * If open_tag is an array, then the pda will try to match pairs consisting of
 * any element of open_tag followed by close_tag. This allows us to match things
 * like [list=A]...[/list] and [list=1]...[/list] in one pass of the PDA.
 *
 * NOTES:	- this function assumes the first character of $text is a space.
 *				- every opening tag and closing tag must be of the [...] format.
 */
function bbencode_first_pass_pda($text, $uid, $open_tag, $close_tag, $close_tag_new, $mark_lowest_level, $func, $open_regexp_replace = false)
{
	$open_tag_count = 0;

	if (!$close_tag_new || ($close_tag_new == ''))
	{
		$close_tag_new = $close_tag;
	}

	$close_tag_length = strlen($close_tag);
	$close_tag_new_length = strlen($close_tag_new);
	$uid_length = strlen($uid);

	$use_function_pointer = ($func && ($func != ''));

	$stack = array();

	if (is_array($open_tag))
	{
		if (0 == count($open_tag))
		{
			// No opening tags to match, so return.
			return $text;
		}
		$open_tag_count = count($open_tag);
	}
	else
	{
		// only one opening tag. make it into a 1-element array.
		$open_tag_temp = $open_tag;
		$open_tag = array();
		$open_tag[0] = $open_tag_temp;
		$open_tag_count = 1;
	}

	$open_is_regexp = false;

	if ($open_regexp_replace)
	{
		$open_is_regexp = true;
		if (!is_array($open_regexp_replace))
		{
			$open_regexp_temp = $open_regexp_replace;
			$open_regexp_replace = array();
			$open_regexp_replace[0] = $open_regexp_temp;
		}
	}

	if ($mark_lowest_level && $open_is_regexp)
	{
		message_die(GENERAL_ERROR, "Unsupported operation for bbcode_first_pass_pda().");
	}

	// Start at the 2nd char of the string, looking for opening tags.
	$curr_pos = 1;
	while ($curr_pos && ($curr_pos < strlen($text)))
	{
		$curr_pos = strpos($text, "[", $curr_pos);

		// If not found, $curr_pos will be 0, and the loop will end.
		if ($curr_pos)
		{
			// We found a [. It starts at $curr_pos.
			// check if it's a starting or ending tag.
			$found_start = false;
			$which_start_tag = "";
			$start_tag_index = -1;

			for ($i = 0; $i < $open_tag_count; $i++)
			{
				// Grab everything until the first "]"...
				$possible_start = substr($text, $curr_pos, strpos($text, ']', $curr_pos + 1) - $curr_pos + 1);

				//
				// We're going to try and catch usernames with "[' characters.
				//
			   if( preg_match('#\[quote=\\\&quot;#si', $possible_start, $match) && !preg_match('#\[quote=\\\&quot;(.*?)\\\&quot;\]#si', $possible_start) )
               {
                  // OK we are in a quote tag that probably contains a ] bracket.
                  // Grab a bit more of the string to hopefully get all of it..
                  if ($close_pos = strpos($text, '&quot;]', $curr_pos + 14))
                  {
                    if (strpos(substr($text, $curr_pos + 14, $close_pos - ($curr_pos + 14)), '[quote') === false)
                    {
                     	$possible_start = substr($text, $curr_pos, $close_pos - $curr_pos + 7);	
					}
					}
				}

				if( preg_match('#\[mod=\\\&quot;#si', $possible_start, $match) && !preg_match('#\[mod=\\\&quot;(.*?)\\\&quot;\]#si', $possible_start) )
				{
					// OK we are in a mod tag that probably contains a ] bracket.
					// Grab a bit more of the string to hopefully get all of it..
					if ($close_pos = strpos($text, '&quot;]', $curr_pos + 14))
					{
						if (strpos(substr($text, $curr_pos + 14, $close_pos - ($curr_pos + 14)), '[mod') === false)
						{
							$possible_start = substr($text, $curr_pos, $close_pos - $curr_pos + 7);
						}
					}
				}

				// Now compare, either using regexp or not.
				if ($open_is_regexp)
				{
					$match_result = array();
					if (preg_match($open_tag[$i], $possible_start, $match_result))
					{
						$found_start = true;
						$which_start_tag = $match_result[0];
						$start_tag_index = $i;
						break;
					}
				}
				else
				{
					// straightforward string comparison.
					if (0 == strcasecmp($open_tag[$i], $possible_start))
					{
						$found_start = true;
						$which_start_tag = $open_tag[$i];
						$start_tag_index = $i;
						break;
					}
				}
			}

			if ($found_start)
			{
				// We have an opening tag.
				// Push its position, the text we matched, and its index in the open_tag array on to the stack, and then keep going to the right.
				$match = array("pos" => $curr_pos, "tag" => $which_start_tag, "index" => $start_tag_index);
				array_push($stack, $match);
				//
				// Rather than just increment $curr_pos
				// Set it to the ending of the tag we just found
				// Keeps error in nested tag from breaking out
				// of table structure..
				//
				$curr_pos += strlen($possible_start);
			}
			else
			{
				// check for a closing tag..
				$possible_end = substr($text, $curr_pos, $close_tag_length);
				if (0 == strcasecmp($close_tag, $possible_end))
				{
					// We have an ending tag.
					// Check if we've already found a matching starting tag.
					if (sizeof($stack) > 0)
					{
						// There exists a starting tag.
						$curr_nesting_depth = sizeof($stack);
						// We need to do 2 replacements now.
						$match = array_pop($stack);
						$start_index = $match['pos'];
						$start_tag = $match['tag'];
						$start_length = strlen($start_tag);
						$start_tag_index = $match['index'];

						if ($open_is_regexp)
						{
							$start_tag = preg_replace($open_tag[$start_tag_index], $open_regexp_replace[$start_tag_index], $start_tag);
						}

						// everything before the opening tag.
						$before_start_tag = substr($text, 0, $start_index);

						// everything after the opening tag, but before the closing tag.
						$between_tags = substr($text, $start_index + $start_length, $curr_pos - $start_index - $start_length);

						// Run the given function on the text between the tags..
						if ($use_function_pointer)
						{
							$between_tags = $func($between_tags, $uid);
						}

						// everything after the closing tag.
						$after_end_tag = substr($text, $curr_pos + $close_tag_length);

						// Mark the lowest nesting level if needed.
						if ($mark_lowest_level && ($curr_nesting_depth == 1))
						{
							if ($open_tag[0] == '[code]')
							{
								$code_entities_match = array('#<#', '#>#', '#"#', '#:#', '#\[#', '#\]#', '#\(#', '#\)#', '#\{#', '#\}#');
								$code_entities_replace = array('&lt;', '&gt;', '&quot;', '&#58;', '&#91;', '&#93;', '&#40;', '&#41;', '&#123;', '&#125;');
								$between_tags = preg_replace($code_entities_match, $code_entities_replace, $between_tags);
							}
							$text = $before_start_tag . substr($start_tag, 0, $start_length - 1) . ":$curr_nesting_depth:$uid]";
							$text .= $between_tags . substr($close_tag_new, 0, $close_tag_new_length - 1) . ":$curr_nesting_depth:$uid]";
						}
						else
						{
							if ($open_tag[0] == '[code]')
							{
								$text = $before_start_tag . '&#91;code&#93;';
								$text .= $between_tags . '&#91;/code&#93;';
							}
							else
							{
								if ($open_is_regexp)
								{
									$text = $before_start_tag . $start_tag;
								}
								else
								{
									$text = $before_start_tag . substr($start_tag, 0, $start_length - 1) . ":$uid]";
								}
								$text .= $between_tags . substr($close_tag_new, 0, $close_tag_new_length - 1) . ":$uid]";
							}
						}

						$text .= $after_end_tag;

						// Now.. we've screwed up the indices by changing the length of the string.
						// So, if there's anything in the stack, we want to resume searching just after it.
						// otherwise, we go back to the start.
						if (sizeof($stack) > 0)
						{
							$match = array_pop($stack);
							$curr_pos = $match['pos'];
//							array_push($stack, $match);
//							++$curr_pos;
						}
						else
						{
							$curr_pos = 1;
						}
					}
					else
					{
						// No matching start tag found. Increment pos, keep going.
						++$curr_pos;
					}
				}
				else
				{
					// No starting tag or ending tag.. Increment pos, keep looping.,
					++$curr_pos;
				}
			}
		}
	} // while

	return $text;

} // bbencode_first_pass_pda()

/**
 * Does second-pass bbencoding of the [code] tags. This includes
 * running htmlspecialchars() over the text contained between
 * any pair of [code] tags that are at the first level of
 * nesting. Tags at the first level of nesting are indicated
 * by this format: [code:1:$uid] ... [/code:1:$uid]
 * Other tags are in this format: [code:$uid] ... [/code:$uid]
 */
function bbencode_second_pass_code($text, $uid, $bbcode_tpl)
{
	global $lang;

	$code_start_html = $bbcode_tpl['code_open'];
	$code_end_html =  $bbcode_tpl['code_close'];

	// First, do all the 1st-level matches. These need an htmlspecialchars() run,
	// so they have to be handled differently.
	$match_count = preg_match_all("#\[code:1:$uid\](.*?)\[/code:1:$uid\]#si", $text, $matches);

	for ($i = 0; $i < $match_count; $i++)
	{
		$before_replace = $matches[1][$i];
		$after_replace = $matches[1][$i];

		// Replace 2 spaces with "&nbsp; " so non-tabbed code indents without making huge long lines.
		$after_replace = str_replace("  ", "&nbsp; ", $after_replace);

		// now Replace 2 spaces with " &nbsp;" to catch odd #s of spaces.
		$after_replace = str_replace("  ", " &nbsp;", $after_replace);

		// Replace tabs with "&nbsp; &nbsp;" so tabbed code indents sorta right without making huge long lines.
		$after_replace = str_replace("\t", "&nbsp; &nbsp;", $after_replace);

		// now Replace space occurring at the beginning of a line
		$after_replace = preg_replace("/^ {1}/m", '&nbsp;', $after_replace);

		$str_to_match = "[code:1:$uid]" . $before_replace . "[/code:1:$uid]";

		$replacement = $code_start_html;
		$replacement .= $after_replace;
		$replacement .= $code_end_html;

		$text = str_replace($str_to_match, $replacement, $text);
	}

	// Now, do all the non-first-level matches. These are simple.
	$text = str_replace("[code:$uid]", $code_start_html, $text);
	$text = str_replace("[/code:$uid]", $code_end_html, $text);

	return $text;

} // bbencode_second_pass_code()


/**
 * Does second-pass bbencoding of the [quote] tags. This includes
 * seperating out any [footnote] tags there may be in any pair of 
 * [quote] tags.
 */
function bbencode_second_pass_quote($text, $uid, $bbcode_tpl)
{
	// determine if any quotes are in the text a make nda recursive call on it if there is
	preg_match("#\[quote:$uid(=\".*\")?\](.*?)\[/quote:$uid\]#si", substr($text, 1), $match);

	if( $match[0] )
	{
		$start_pos = strpos($text, $match[0], 1);
		$text = substr($text, 0, $start_pos) . bbencode_second_pass_quote(substr($text, $start_pos), $uid, $bbcode_tpl);
	}
	
	$end_text = "";
	$end_pos = strpos($text, "[/quote:$uid]");
	
	
	// select just the quote itself and strip the closing quote tag (if there is a quote in the text)
	if( $end_pos )
	{
		$end_text = substr($text, ($end_pos + strlen("[/quote:$uid]")));
		$text = substr($text, 0, ($end_pos + strlen("[/quote:$uid]")));
		
		$text = str_replace("[/quote:$uid]", "", $text);

	}

	// [footnote] and [/footnote] for footnote text
	preg_match_all("#\[footnote:$uid\](.*?)\[/footnote:$uid\]#si", $text, $footnotes, PREG_PATTERN_ORDER);
	
	if(count($footnotes[1]) != 0)
	{
		$count = 1;
		$text .= $bbcode_tpl['footnote_open'];
	
		foreach($footnotes[1] as $key => $note)
		{
			$text = str_replace($footnotes[0][$key], $bbcode_tpl['super_open'] . $count . $bbcode_tpl['super_close'], $text);
			$text .= '<br />' . $bbcode_tpl['super_open'] . $count . $bbcode_tpl['super_close'] . ' ' . $note;
			$count++;
		}

		$text .= $bbcode_tpl['footnote_close'];
	}

	// add the closing quote tag again (if this was a quote)
	if( $end_pos )
	{
		$text .= $bbcode_tpl['quote_close'];
	}
	
	// substiture the opening quote tag with the correct template code
	$text = str_replace("[quote:$uid]", $bbcode_tpl['quote_open'], $text);
	$text = preg_replace("/\[quote:$uid=\"(.*?)\"\]/si", $bbcode_tpl['quote_username_open'], $text);

	// place the quote back into the main text
	$text .= $end_text;

	return $text;
}


/**
 * Returns a file size formatted in a more human-friendly format, rounded
 * to the nearest Gb, Mb, Kb, or byte.
 * Reworked by Bill Hicks to connect to razorback2 and show sources for ed2k files
 * by automatically retreving hash id from the files
 */
function humanize_size($size, $rounder = 0)
{
   	$sizes = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
   	$rounders = array(0,   1,    2,    2,    2,    3,    3,    3,    3);
   	$ext = $sizes[0];
   	$rnd = $rounders[0];

   	if ($size < 1024)
   	{
    	$rounder = 0;
	 	$format =  '%.' . $rounder . 'f Bytes';
   	}
   	else
   	{
   		for ($i = 1, $cnt = count($sizes); ($i < $cnt && $size >= 1024); $i++)
      	{
        	$size = $size / 1024;
        	$ext  = $sizes[$i];
        	$rnd  = $rounders[$i];
        	$format =  '%.' . $rnd . 'f ' . $ext;
      	}
   	}

   	if (!$rounder)
   	{
    	$rounder = $rnd;
   	}

	return sprintf($format, round($size, $rounder));
}

function ed2k_link_callback($m)
{
	global $theme, $board_config, $lang, $phpEx;
	
	$max_len = 100;
	$href = $m[2] . '" title="' . rawurldecode($m[3]) . '"';
	$fname = rawurldecode($m[3]);
	$size = humanize_size($m[4]);

	if (strlen($fname) > $max_len)
	{
    	$fname = substr($fname, 0, $max_len - 19) . '...' . substr($fname, -16);
   	}	
   	
   	if (preg_match('#[<>"]#', $fname))
   	{
    	$fname = htmlspecialchars($fname);
   	}
   	
   	return '<a href="http://tothbenedek.hu/ed2kstats/ed2k?hash=' . $m[5] . '" target="_blank"><img src="templates/' . ( ( $theme['image_cfg'] ) ? $theme['template_name'] . '/images/' . $theme['image_cfg'] : $theme['template_name'] . '/images' ) . '/icon_ed2k_stats.gif" alt="' . $lang['Statistics'] . '" title="' . $lang['Statistics'] . '" /></a> <a href="http://www.emugle.com/details.'.$phpEx.'?' . POST_FORUM_URL . '=' . $m[5] . '" target="_blank"><img src="templates/' . ( ( $theme['image_cfg'] ) ? $theme['template_name'] . '/images/' . $theme['image_cfg'] : $theme['template_name'] . '/images' ) . '/icon_ed2k_info.gif" alt="" title="" /></a> <img src="templates/' . ( ( $theme['image_cfg'] ) ? $theme['template_name'] . '/images/' . $theme['image_cfg'] : $theme['template_name'] . '/images' ) . '/icon_ed2k_mule.gif" alt="ed2k" title="ed2k" /> <a href="' . $href . '">' . $fname . '</a> <b class="gensmall">(' . $size . ')</b>';
}


/**
 * Rewritten by Nathan Codding - Feb 6, 2001.
 * - Goes through the given string, and replaces xxxx://yyyy with an HTML <a> tag linking
 * 	to that URL
 * - Goes through the given string, and replaces www.xxxx.yyyy[zzzz] with an HTML <a> tag linking
 * 	to http://www.xxxx.yyyy[/zzzz]
 * - Goes through the given string, and replaces xxxx@yyyy with an HTML mailto: tag linking
 *		to that email address
 * - Only matches these 2 patterns either after a space, or at the beginning of a line
 *
 * Notes: the email one might get annoying - it's easy to make it more restrictive, though.. maybe
 * have it require something like xxxx@yyyy.zzzz or such. We'll see.
 */
function make_clickable($text)
{
	global $bbcode_tpl, $userdata, $board_config, $is_auth;

	$text = preg_replace('#(script|about|applet|activex|chrome):#is', "\\1&#058;", $text);

	// pad it with a space so we can match things at the start of the 1st line.
	$ret = ' ' . $text;

	// ed2k://|file|fileName|fileSize|fileHash|(optional params)|(optional params)|etc|/
	$ret = preg_replace_callback("#(^|(?<=[^\w\"']))(ed2k://\|file\|([^\\/\|:<>\*\?\"]+?)\|(\d+?)\|([a-f0-9]{32})\|(.*?)/?)(?![\"'])(?=([,\.]*?[\s<\[])|[,\.]*?$)#i", "ed2k_link_callback", $ret);

	// ed2k://|server|serverIP|serverPort|/
	$ret = preg_replace("#(^|(?<=[^\w\"']))(ed2k://\|server\|([\d\.]+?)\|(\d+?)\|/?)#i", "ed2k server: <a href=\"\\2\" class=\"postLink\">\\3:\\4</a>", $ret);

	// ed2k://|friend|name|clientIP|clientPort|/
	$ret = preg_replace("#(^|(?<=[^\w\"']))(ed2k://\|friend\|([^\\/\|:<>\*\?\"]+?)\|([\d\.]+?)\|(\d+?)\|/?)#i", "ed2k friend: <a href=\"\\2\" class=\"postLink\">\\3</a>", $ret);

	$url_replacer = ($board_config['hl_enable'] ) ? (( !$userdata['session_logged_in'] ) ? $bbcode_tpl['login_request'] : ( ( ( $userdata['user_posts'] >= $board_config['hl_necessary_post_number'] ) || ( $userdata['user_level'] == ADMIN ) || ( ( $is_auth['auth_mod'] == 1 ) && ( $board_config['hl_mods_priority'] == 1 ) ) ) ? '' : $bbcode_tpl['post_count_request'] )) : '';

 	if ( $url_replacer )
	{
		// matches an "xxxx://yyyy" URL at the start of a line, or after a space.
		// xxxx can only be alpha characters.
		// yyyy is anything up to the first space, newline, comma, double quote or <
		$ret = preg_replace("#(^|[\n ])([\w]+?://[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", $url_replacer, $ret);
	
		// matches a "www|ftp.xxxx.yyyy[/zzzz]" kinda lazy URL thing
		// Must contain at least 2 dots. xxxx contains either alphanum, or "-"
		// zzzz is optional.. will contain everything up to the first space, newline, 
		// comma, double quote or <.
		$ret = preg_replace("#(^|[\n ])((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", $url_replacer, $ret);
	
		// matches an email@domain type address at the start of a line, or after a space.
		// Note: Only the followed chars are valid; alphanums, "-", "_" and or ".".
		$ret = preg_replace("#(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", $url_replacer, $ret);
	}
	else
	{	
 		// matches an "xxxx://yyyy" URL at the start of a line, or after a space.
		// xxxx can only be alpha characters.
		// yyyy is anything up to the first space, newline, comma, double quote or <
		$ret = preg_replace("#(^|[\n ])([\w]+?://[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "\\1<a href=\"\\2\" target=\"_blank\">\\2</a>", $ret);

		// matches a "www|ftp.xxxx.yyyy[/zzzz]" kinda lazy URL thing
		// Must contain at least 2 dots. xxxx contains either alphanum, or "-"
		// zzzz is optional.. will contain everything up to the first space, newline, 
		// comma, double quote or <.
		$ret = preg_replace("#(^|[\n ])((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", "\\1<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $ret);
	
		// matches an email@domain type address at the start of a line, or after a space.
		// Note: Only the followed chars are valid; alphanums, "-", "_" and or ".".
		$ret = preg_replace("#(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $ret);
	}
	
	$ret = fix_local_urls($ret);
	
		// Remove our padding..
	$ret = substr($ret, 1);

	return($ret);
}


/**
* Christian Riesen - Jun 27, 2004
* Adds the "Add all links to ed2k client" link to the end of the post text
*/
function make_addalled2k_link($text, $post_id)
{
	global $theme, $lang;
	
	// padding
	$ret = ' ' . $text;

	// dig through the message for all ed2k links!
	// split up by "ed2k:"
	$t_ed2k_raw = explode('ed2k:',$text);

	// The first item is garbage
	unset($t_ed2k_raw[0]);

	// no need to dig through it if there are not at least 2 links!
	$t_ed2k_possibles = sizeof($t_ed2k_raw);
	if ($t_ed2k_possibles > 1)
	{
		unset($t_ed2k_reallinks);
		foreach ($t_ed2k_raw as $t_ed2k_raw_line)
		{
			$t_ed2k_parts = explode('|', $t_ed2k_raw_line);

			/* This looks now like this (only important parts included
				[1]=>
				string(4) "file"
				[2]=>
				string(46) "some-filename-here.txt"
				[3]=>
				string(9) "321456789"
				[4]=>
				string(32) "112233445566778899AABBCCDDEEFF11"
				[5]=>
				string(?) "source or AICH hash"
			*/

			// Check the obvious things
			if (strlen($t_ed2k_parts[1]) == 4 && $t_ed2k_parts[1] === 'file' && strlen($t_ed2k_parts[2]) > 0 && strlen($t_ed2k_parts[4]) == 32 && floatval($t_ed2k_parts[3]) > 0)
			{
				// This is a true link, lets paste it together and put it in an array
				if (substr($t_ed2k_parts[5], 0, 2) == 'h=' || substr($t_ed2k_parts[5], 0, 7) == 'sources')
				{
					$t_ed2k_reallinks[] = 'ed2k://|file|' . str_replace('\'', '\\\'', $t_ed2k_parts[2]) . '|' . $t_ed2k_parts[3] . '|' . $t_ed2k_parts[4] . '|' . $t_ed2k_parts[5] . '|/';
				}
				else
				{
					$t_ed2k_reallinks[] = 'ed2k://|file|' . str_replace('\'','\\\'', $t_ed2k_parts[2]) . '|' . $t_ed2k_parts[3] . '|' . $t_ed2k_parts[4] . '|/';
				}
			}
		}

		// Now lets see if we have 2 or more links
		// Only then, we do our little trick, because otherwise, it would be wasted for one link alone!
		$t_ed2k_confirmed = sizeof($t_ed2k_reallinks);
		if ($t_ed2k_confirmed > 1)
		{
			$t_ed2kinsert = " \n";
			$t_ed2kinsert .= "<script> ";
			$t_ed2kinsert .= "filearray" . $post_id . "=new Array; ";
			$t_ed2kinsert .= "n=0; ";

			$i = 0;
			foreach($t_ed2k_reallinks as $t_ed2klink)
			{
				$t_ed2kinsert .= "filearray" . $post_id . "[" . $i . "]='" . $t_ed2klink . "'; ";
				$i++;
			}

			$t_ed2kinsert .= "iv=false; ";
			$t_ed2kinsert .= "function addfile" . $post_id . "(){ ";
			$t_ed2kinsert .= " var s=filearray" . $post_id . "[n]; ";
			$t_ed2kinsert .= " n++; ";
			$t_ed2kinsert .= " if(n==filearray" . $post_id . ".length && iv){ ";
			$t_ed2kinsert .= " top.clearInterval(iv); ";
			$t_ed2kinsert .= " n=0; ";
			$t_ed2kinsert .= " } ";
			$t_ed2kinsert .= " top.document.location=s; ";
			$t_ed2kinsert .= " return true; ";
			$t_ed2kinsert .= "} ";
			$t_ed2kinsert .= "function addall" . $post_id . "(){iv=top.setInterval('addfile" . $post_id . "()',250)} ";
			$t_ed2kinsert .= "</script> ";
			//added an icon in front of link (ppw)
			$t_ed2kinsert .= "<br /><img src='templates/" . $theme['template_name'] . "/images/icon_ed2k_info.gif' border='0' alt='' title='' /> <img src='templates/" . $theme['template_name'] . "/images/icon_ed2k_mule.gif' border='0' alt='' title='' /> <a href='javascript:addall" . $post_id . "()'>" . sprintf($lang['Add_all_ed2k_links'], $t_ed2k_confirmed) . "</a>";

			$ret = $ret . $t_ed2kinsert;
		}
	}

	// remove padding
	$ret = substr($ret, 1);
	
	return($ret);
}


/**
 * Nathan Codding - Feb 6, 2001
 * Reverses the effects of make_clickable(), for use in editpost.
 * - Does not distinguish between "www.xxxx.yyyy" and "http://aaaa.bbbb" type URLs.
 *
 */
function undo_make_clickable($text)
{
	$text = preg_replace("#<!-- BBCode auto-link start --><a href=\"(.*?)\" target=\"_blank\">.*?</a><!-- BBCode auto-link end -->#i", "\\1", $text);
	$text = preg_replace("#<!-- BBcode auto-mailto start --><a href=\"mailto:(.*?)\">.*?</a><!-- BBCode auto-mailto end -->#i", "\\1", $text);

	return $text;

}

/**
 * Nathan Codding - August 24, 2000.
 * Takes a string, and does the reverse of the PHP standard function
 * htmlspecialchars().
 */
function undo_htmlspecialchars($input)
{
	$input = preg_replace("/&gt;/i", ">", $input);
	$input = preg_replace("/&lt;/i", "<", $input);
	$input = preg_replace("/&quot;/i", "\"", $input);
	$input = preg_replace("/&amp;/i", "&", $input);

	return $input;
}

/**
 * This is used to change a [*] tag into a [*:$uid] tag as part
 * of the first-pass bbencoding of [list] tags. It fits the
 * standard required in order to be passed as a variable
 * function into bbencode_first_pass_pda().
 */
function replace_listitems($text, $uid)
{
	$text = str_replace("[*]", "[*:$uid]", $text);

	return $text;
}

/**
 * Escapes the "/" character with "\/". This is useful when you need
 * to stick a runtime string into a PREG regexp that is being delimited
 * with slashes.
 */
function escape_slashes($input)
{
	$output = str_replace('/', '\/', $input);
	return $output;
}

/**
 * This function does exactly what the PHP4 function array_push() does
 * however, to keep phpBB compatable with PHP 3 we had to come up with our own
 * method of doing it.
 * This function was deprecated in phpBB 2.0.18
 */
function bbcode_array_push(&$stack, $value)
{
   $stack[] = $value;
   return(sizeof($stack));
}

/**
 * This function does exactly what the PHP4 function array_pop() does
 * however, to keep phpBB compatable with PHP 3 we had to come up with our own
 * method of doing it.
 * This function was deprecated in phpBB 2.0.18
 */
function bbcode_array_pop(&$stack)
{
   $arrSize = count($stack);
   $x = 1;

   while(list($key, $val) = each($stack))
   {
      if($x < count($stack))
      {
	 		$tmpArr[] = $val;
      }
      else
      {
	 		$return_val = $val;
      }
      $x++;
   }
   $stack = $tmpArr;

   return($return_val);
}

//
// Smilies code ... would this be better tagged on to the end of bbcode.php?
// Probably so and I'll move it before B2
//
function smilies_pass($message, $forum_id = FALSE)
{
 	global $board_config, $phpbb_root_path;

  	static $orig, $repl;

   	if (!isset($orig))
   	{
    	global $db, $userdata, $phpbb_root_path;
  
    	$orig = $repl = array();

		$permissions = ( $userdata['session_logged_in'] ) ? (( $userdata['user_level'] == ADMIN ) ? '<= 40' : (( $userdata['user_level'] == MOD || $userdata['user_level'] == LESS_ADMIN ) ? '<= 30' : (( $userdata['user_level'] == USER ) ? '<= 20' : '= 10'))) : '= 10';
		$which_forum = ( $forum_id == '999' ) ? "c.cat_forum LIKE '%999%'" : ( $forum_id && $forum_id != '999' ) ? "c.cat_forum LIKE '%" . $forum_id . "%'" : "c.cat_open = 1";

		if( $board_config['smilie_usergroups'] )
		{
			$sql = "SELECT g.group_id
				FROM " . GROUPS_TABLE . " g, " . USER_GROUP_TABLE . " ug
				WHERE ug.user_id = " . $userdata['user_id'] . "  
					AND ug.group_id = g.group_id
					AND g.group_single_user <> " . TRUE;
			if( $result = $db->sql_query($sql) )
			{
				$group_num = 0;
				$array_groups = array();
				while( $row = $db->sql_fetchrow($result) )
				{
					$array_groups[] = $row;
					$group_num++;
				}

				for($i = 0; $i < $group_num; $i++)
				{
					$which_forum .= " OR c.cat_group LIKE '%" . $array_groups[$i]['group_id'] . "%'";
				}
			}
		}

		$sql = "SELECT s.code, s.smile_url, s.emoticon
			FROM " . SMILIES_CAT_TABLE . " c, " . SMILIES_TABLE . " s
			WHERE c.cat_perms $permissions
				AND $which_forum
				AND c.cat_order = s.cat_id";
      	if( !$result = $db->sql_query($sql) )
      	{
      		message_die(GENERAL_ERROR, 'Could not obtain smilies data.', '', __LINE__, __FILE__, $sql);
      	}
      	$smilies = $db->sql_fetchrowset($result);

		if (sizeof($smilies))
		{
			usort($smilies, 'smiley_sort');
		}	

		for($i = 0; $i < sizeof($smilies); $i++)
	    {
        	$orig[] = "/(?<=.\W|\W.|^\W)" . phpbb_preg_quote($smilies[$i]['code'], "/") . "(?=.\W|\W.|\W$)/";
	   		$repl[] = '<img src="' . $phpbb_root_path . $board_config['smilies_path'] . '/' . $smilies[$i]['smile_url'] . '" alt="' . $smilies[$i]['emoticon'] . '" title="' . $smilies[$i]['emoticon'] . '" />';
      	}
   	}

   	if (sizeof($orig))
   	{
   		$message = preg_replace($orig, $repl, ' ' . $message . ' ');
      	$message = substr($message, 1, -1);
   	}

	if( $board_config['smilie_removal2'] )
	{
		$message = smilies_code_removal($message);
	}

   	return $message;
}

function smiley_sort($a, $b)
{
	if ( strlen($a['code']) == strlen($b['code']) )
	{
		return 0;
	}

	return ( strlen($a['code']) > strlen($b['code']) ) ? -1 : 1;
}

function smilies_code_removal($message)
{
	static $orig, $repl;

	if (!isset($orig))
	{
		global $db, $lang;
		
		$orig = $repl = array();

		$sql = "SELECT code
			FROM " . SMILIES_TABLE;
		if( !$result = $db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Couldn't obtain smilies data", "", __LINE__, __FILE__, $sql);
		}

		$smilies = $db->sql_fetchrowset($result);

		for($i = 0; $i < sizeof($smilies); $i++ )
		{
			$orig[] = "/(?<=.\W|\W.|^\W)" . phpbb_preg_quote($smilies[$i]['code'], "/") . "(?=.\W|\W.|\W$)/";
			$repl[] = $lang['smiley_code_replacement'];
		}
	}

	if (sizeof($orig))
	{
		$message = preg_replace($orig, $repl, ' ' . $message . ' ');
		$message = substr($message, 1, -1);
	}
	
	return $message;
}

/**
 * Mouse hover topic preview 
 */
function bbencode_strip($message, $uid)
{
	$message = strip_tags($message);

	// url #2
	$message = str_replace("[url]","", $message);
	$message = str_replace("[/url]", "", $message);

	// url /\[url=([a-z0-9\-\.,\?!%\*_\/:;~\\&$@\/=\+]+)\](.*?)\[/url\]/si
	$message = preg_replace("/\[url=([a-z0-9\-\.,\?!%\*_\/:;~\\&$@\/=\+]+)\]/si", "", $message);
	$message = str_replace("[/url:$uid]", "", $message);

	$message = preg_replace("/\[.*?:$uid:?.*?\]/si", '', $message);
	$message = preg_replace('/\[url\]|\[\/url\]/si', '', $message);
	$message = str_replace('"', "'", $message);

	return $message;
}

// Force Word Wrapping (by TerraFrost)
function word_wrap_pass($message)
{
	global $userdata, $board_config;

	if ( !$board_config['wrap_enable'] )
	{
		return $message;
	}

	$tempText = $finalText = $ampText = '';
	$curCount = $tempCount = 0;
	$longestAmp = 9;
	$inTag = false;
	
	$len = strlen($message);

	for ($num=0; $num < $len; $num++)
	{
		$curChar = $message{$num};

		if ($curChar == '<')
		{
			for ($snum=0; $snum < strlen($ampText); $snum++)
			{
				addWrap($ampText{$snum}, $ampText{$snum+1}, $userdata['user_wordwrap'], $finalText, $tempText, $curCount, $tempCount);
			}
			$ampText = '';
			$tempText .= '<';
			$inTag = true;
		}
		else if ($inTag && $curChar == '>')
		{
			$tempText .= '>';
			$inTag = false;
		}
		else if ($inTag)
		{
			$tempText .= $curChar;
		}
		else if ($curChar == '&')
		{
			for ($snum=0; $snum < strlen($ampText); $snum++)
			{
				addWrap($ampText{$snum}, $ampText{$snum+1}, $userdata['user_wordwrap'], $finalText, $tempText, $curCount, $tempCount);
			}
			$ampText = '&';
		}
		else if (strlen($ampText) < $longestAmp && $curChar == ';' && function_exists('html_entity_decode') && (strlen(html_entity_decode("$ampText;")) == 1 || preg_match('/^&#[0-9]+$/',$ampText)))
		{
			addWrap($ampText.';', $message{$num+1}, $userdata['user_wordwrap'], $finalText, $tempText, $curCount, $tempCount);
			$ampText = '';
		}
		else if (strlen($ampText) >= $longestAmp || $curChar == ';')
		{
			for ($snum=0;$snum<strlen($ampText);$snum++)
			{
				addWrap($ampText{$snum}, $ampText{$snum+1}, $userdata['user_wordwrap'], $finalText, $tempText, $curCount, $tempCount);
			}
			addWrap($curChar, $message{$num+1}, $userdata['user_wordwrap'], $finalText, $tempText, $curCount, $tempCount);
			$ampText = '';
		}
		else if (strlen($ampText) != 0 && strlen($ampText) < $longestAmp)
		{
			$ampText .= $curChar;
		}
		else
		{
			addWrap($curChar, $message{$num+1}, $userdata['user_wordwrap'], $finalText, $tempText, $curCount, $tempCount);
		}
	}

	return $finalText . $tempText;
}

function addWrap($curChar, $nextChar, $maxChars, &$finalText, &$tempText, &$curCount, &$tempCount) 
{
	$wrapProhibitedChars = "([{!;,\\/:?}])";

	if ($curChar == ' ' || $curChar == "\n")
	{
		$finalText .= $tempText . $curChar;
		$tempText = $curChar = '';
		$curCount = 0;
	}
	else if ($curCount >= $maxChars)
	{
		$finalText .= $tempText . ' ';
		$tempText = '';
		$curCount = 1;
	}
	else
	{
		$tempText .= $curChar;
		$curCount++;
	}

	// the following code takes care of (unicode) characters prohibiting non-mandatory breaks directly before them.

	// $curChar isn't a " " or "\n"
	if ($tempText != '' && $curChar != '')
	{
		$tempCount++;
	}
	// $curChar is " " or "\n", but $nextChar prohibits wrapping.
	else if ( ($curCount == 1 && strstr($wrapProhibitedChars,$curChar) !== false) || ($curCount == 0 && $nextChar != '' && $nextChar != ' ' && $nextChar != "\n" && strstr($wrapProhibitedChars,$nextChar) !== false))
	{
		$tempCount++;
	}
	// $curChar and $nextChar aren't both either " " or "\n"
	else if (!($curCount == 0 && ($nextChar == ' ' || $nextChar == "\n")))
	{
		$tempCount = 0;
	}

	if ($tempCount >= $maxChars && $tempText == '')
	{
		$finalText .= '&nbsp;';
		$tempCount = 1;
		$curCount = 2;
	}

	if ($tempText == ''  && $curCount > 0)
	{
		$finalText .= $curChar;
	}
}

?>