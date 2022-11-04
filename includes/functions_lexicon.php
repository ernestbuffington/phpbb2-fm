<?php
/***************************************************************************
 *							functions_lexicon.php
 *                           -------------------
 *   begin                : Saturday, May 22, 2005
 *   copyright            : (C) 2005 AmigaLink
 *   website              : www.amigalink.de
 *
 *   $Id: functions_lexicon.php, v 0.7.11 2005/06/14 13:30:00 AmigaLink Exp $
 *
 ***************************************************************************/ 

/***************************************************************************
 * function str_highlight based on a script by Aidan Lister < aidan@php.net >
 ***************************************************************************/ 

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

if (!defined('IN_PHPBB'))
{
	die('Hacking attempt');
}

// BEGIN highlight
/**
 * Perform a simple text replace
 * This should be used when the string does not contain HTML
 * (off by default)
 */
define('STR_HIGHLIGHT_SIMPLE', 1);

/**
 * Only match whole words in the string
 * (off by default)
 */
define('STR_HIGHLIGHT_WHOLEWD', 2);

/**
 * Case sensitive matching
 * (off by default)
 */
define('STR_HIGHLIGHT_CASESENS', 4);

/**
 * Overwrite links if matched
 * This should be used when the replacement string is a link
 * (off by default)
 */
define('STR_HIGHLIGHT_STRIPLINKS', 8);

/**
 * Highlight a string in text without corrupting HTML tags
 *
 * @param       string          $text           Haystack - The text to search
 * @param       array|string    $needle         Needle - The string to highlight
 * @param       bool            $options        Bitwise set of options
 * @param       array           $highlight      Replacement string
 * @return      Text with needle highlighted
 */
function str_highlight($text, $needle, $options = null, $highlight = null)
{
    // Default highlighting
    if ($highlight === null)
	{
        $highlight = '<strong>\1</strong>';
    }

    // Select pattern to use
    if ($options & STR_HIGHLIGHT_SIMPLE)
	{
        $pattern = '#(%s)#';
    }
	else
	{
        $pattern = '#(?!<.*?)(%s)(?![^<>]*?>)#';
        $sl_pattern = '#<a\s(?:.*?)>(%s)</a>#';
    }

    // Case sensitivity
    if ($options ^ STR_HIGHLIGHT_CASESENS)
	{
        $pattern .= 'i';
        $sl_pattern .= 'i';
    }

	$needle = (array) $needle;
	foreach ($needle as $needle_s)
	{
        $needle_s = preg_quote($needle_s);

        // Escape needle with optional whole word check
        if ($options & STR_HIGHLIGHT_WHOLEWD)
		{
            $needle_s = '\b' . $needle_s . '\b';
        }

        // Strip links
        if ($options & STR_HIGHLIGHT_STRIPLINKS)
		{
            $sl_regex = sprintf($sl_pattern, $needle_s);
            $text = preg_replace($sl_regex, '\1', $text);
        }

        $regex = sprintf($pattern, $needle_s);
		$text = preg_replace($regex, $highlight, $text);
	}
    return $text;
}
// END highlight

function letter_nav($letter, $present_letters, $categorie_id)
{
	global $phpEx, $lex_cat_mode;
	for($i=65;$i<91;$i++)
	{
		if($letter==chr($i)) 
		{	
			// selected
			$letter_navigation .= '<span class="letter2">&nbsp;'.chr($i).'&nbsp;</span>';
		}
		elseif(!strpos($present_letters,chr($i))) 
		{	
			// not available
			$letter_navigation .= '<span class="letter3">&nbsp;'.chr($i).'&nbsp;</span>';
		}
		else 
		{
			// available
			$letter_navigation .= '&nbsp;<a class="letter" href="'.append_sid("lexicon.$phpEx").'?letter='.chr($i).$lex_cat_mode.'">'.chr($i).'</a>&nbsp;';
		}
	}
	return $letter_navigation;
}

function extra_nav($letter, $present_letters, $categorie_id, $show_all = false)
{
	global $phpEx, $lex_cat_mode;
	for($i=48;$i<58;$i++)
	{
		if($letter==chr($i)) 
		{	
			// selected
			$extra_navigation .= '<span class="letter2">&nbsp;'.chr($i).'&nbsp;</span>';
			$one_exist = true;
		}
		elseif(!strpos($present_letters,chr($i))) 
		{
			// not available
			if ($show_all)
			{
				$extra_navigation .= '<span class="letter3">&nbsp;'.chr($i).'&nbsp;</span>';
			}
		}
		else 
		{
			// available
			$extra_navigation .= '&nbsp;<a class="letter" href="'.append_sid("lexicon.$phpEx").'?letter='.chr($i).$lex_cat_mode.'">'.chr($i).'</a>&nbsp;';
			$one_exist = true;
		}
	}

	if($letter==chr(64)) 
	{	
		// selected
		$extra_navigation .= '<span class="letter2">&nbsp;'.chr(64).'&nbsp;</span>';
	}
	elseif(!strpos($present_letters,chr(64))) 
	{	
		// not available
		if ($show_all)
		{
			$extra_navigation .= '<span class="letter3">&nbsp;'.chr(64).'&nbsp;</span>';
		}
		else if($one_exist)
		{
			$extra_navigation .= '<b>&nbsp;-&nbsp;</b>';
		}
	}
	else 
	{
		// available
		$extra_navigation .= '<a class="letter" href="'.append_sid("lexicon.$phpEx").'?letter='.chr(64).$lex_cat_mode.'">&nbsp;'.chr(64).'&nbsp;</a>';
	}
	return $extra_navigation;
}

function lexicon_catselector($lexicon_categories, $categorie_id)
{
	global $template, $userdata, $lang, $phpEx, $letter;

	($lexicon_categories[1] == ' ') ? $lexicon_categories[1] = $lang['generally'] : '';
	$lexicon_cat_selector = '<select name="cat" onchange="if(this.options[this.selectedIndex].value != -1){ forms[\'lexicon_cat_selector\'].submit() }"><option value="-1">' . $lexicon_categories[$categorie_id] . '</option>';
	foreach($lexicon_categories as $cat_id => $cat_titel)
	{
		if ($cat_id != $categorie_id)
		{
			$selected = ( $cat_id == $categorie_id ) ? 'selected="selected"' : '';
			$lexicon_cat_selector .= '<option value="'.$cat_id;
			($cat_id == '0') ? $lexicon_cat_selector .='" '.$selected.'>'.$cat_titel.'</option>' : $lexicon_cat_selector .= '" '.$selected.'>'.$cat_titel.'</option>';
		}
	}
	$lexicon_cat_selector .= '</select>';
	$lexicon_cat_selector .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />';
	$lexicon_cat_selector .= '<input type="hidden" name="letter" value="' . $letter . '" />';

	$template->set_filenames(array(
		'lexicon_cat_selector' => 'lexicon_jumpbox.tpl')
	);
	$template->assign_vars(array(
		'L_GO' => $lang['Go'],
		'L_JUMP_TO' => $lang['Show_only'],
		'L_SELECT_FORUM' => $lexicon_categories[$categorie_id],

		'S_JUMPBOX_SELECT' => $lexicon_cat_selector,
		'S_JUMPBOX_ACTION' => append_sid("lexicon.$phpEx"))
	);
	$template->assign_var_from_handle('LEXICON_CAT_SELECTOR', 'lexicon_cat_selector');
}

/*
function Crosslinks($message)
{
	global $phpEx, $db;

	$sql = 'SELECT keyword 
		FROM ' . LEXICON_ENTRY_TABLE . ' 
		ORDER BY keyword';
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error getting lexicon entrys', '', __LINE__, __FILE__, $sql);
	}

	if ( $row = $db->sql_fetchrow($result) )
	{
		do 
		{
			$crosslink_word[] = '#\b(' . $row['keyword'] . ')\s#i';
			$crosslink_url[] = '<a href="lexicon.' . $phpEx . '?letter=' . $row['keyword'] . '" class="crosslink">' . $row['keyword'] . '</a> ';
			$crosslink_word[] = '#\s(' . $row['keyword'] . ')\b#i';
			$crosslink_url[] = ' <a href="lexicon.' . $phpEx . '?letter=' . $row['keyword'] . '" class="crosslink">' . $row['keyword'] . '</a>';
		}
		while ( $row = $db->sql_fetchrow($result) );
	}
	if (count($crosslink_word)) {
		$message = str_replace('\"', '"', substr(preg_replace('#(\µ(((?>([^µ§]+|(?R)))*)\§))#se', "preg_replace(\$crosslink_word, \$crosslink_url, '\\0')", 'µ' . $message . '§'), 1, -1));
	}
	return $message;
}
*/

function parse_crosslinks($message)
{
	global $crosslink_word, $crosslink_url;

	if ($i < count($crosslink_word)) {
		$message = str_replace('\"', '"', substr(preg_replace('#(\µ(((?>([^µ§]+|(?R)))*)\§))#se', "preg_replace(\$crosslink_word, \$crosslink_url, '\\0')", 'µ' . $message . '§'), 1, -1));
		$i++;
	}
	return $message;
}

?>