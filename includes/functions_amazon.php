<?php
/** 
*
* @package includes
* @version $Id: functions_amazon.php,v 1.47.2.5 2005/05/05 17:49:42 acydburn Exp $
* @copyright (c) 2005 mod@dvdsandstuff.net - http://www.dvdsandstuff.net
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
	exit;
}

if ( $board_config['amazon_enable'] )
{
	if ( $board_config['amazon_country'] == 1 )
	{
		$author_affiliate = 'dvdsstuf-20';
		$amazon_country = 'com';
		$amazon_image = $images['icon_amazon_dollar'];
	}
	elseif ( $board_config['amazon_country'] == 2 )
	{
		$author_affiliate = 'dvdsandstuffn-20';
		$amazon_country = 'ca';
		$amazon_image = $images['icon_amazon_dollar'];
	}
	elseif ( $board_config['amazon_country'] == 3 )
	{
		$author_affiliate = 'dvdsandstuffn-21';
		$amazon_country = 'de';
		$amazon_image = $images['icon_amazon_euro'];
	}
	elseif ( $board_config['amazon_country'] == 4 )
	{
		$author_affiliate = 'dvdsandstuf0d-21';
		$amazon_country = 'fr';
		$amazon_image = $images['icon_amazon_euro'];
	}
	else
	{
		$author_affiliate = 'dvdsstuf-21';
		$amazon_country = 'co.uk';
		$amazon_image = $images['icon_amazon_pound'];
	}

	if ( ($topic_rowset[$i]['topic_type'] == POST_GLOBAL_ANNOUNCE) && ($board_config['amazon_global_announce']) )
	{
		$show_global_announce = TRUE;
	}
	elseif ( ($topic_rowset[$i]['topic_type'] == POST_ANNOUNCE) && ($board_config['amazon_announce']) )
	{
		$show_announce = TRUE;
	}
	elseif ( ($topic_rowset[$i]['topic_type'] == POST_STICKY) && ($board_config['amazon_sticky']) )
	{
		$show_sticky = TRUE;
	}
	elseif ( ($topic_rowset[$i]['topic_type'] != POST_STICKY) && ($topic_rowset[$i]['topic_type'] != POST_ANNOUNCE) && ($topic_rowset[$i]['topic_type'] != POST_GLOBAL_ANNOUNCE) && ($board_config['amazon_normal']) )
	{
		$show_normal = TRUE;
	}
	else
	{
		$show_global_announce = $show_announce = $show_sticky = $show_normal = FALSE;
	}
	
	$new_window = ( $board_config['amazon_window'] ) ? '_blank' : '_self';
	$amazon_text = '';
	
	if ( !empty($board_config['amazon_username']) )
	{
		if ( $show_global_announce == TRUE || $show_announce == TRUE || $show_sticky == TRUE || $show_normal == TRUE )
		{ 
			$amazon_text = '<a href="http://www.amazon.' . $amazon_country . '/exec/obidos/external-search?keyword=' . $amazon_title . '&amp;tag=' . $board_config['amazon_username'] . '&amp;mode=blended" target="' . $new_window . '"><img src="' . $amazon_image . '" alt="' . $topic_title . '" title="' . $topic_title . '" /></a>';
		}
	}
	else
	{
		if ( $show_global_announce == TRUE || $show_announce == TRUE || $show_sticky == TRUE || $show_normal == TRUE )
		{
			$amazon_text = '<a href="http://www.amazon.' . $amazon_country . '/exec/obidos/external-search?keyword=' . $amazon_title . '&amp;tag=' . $author_affiliate . '&amp;mode=blended" target="' . $new_window . '"><img src="' . $amazon_image . '" alt="' . $topic_title . '" title="' . $topic_title . '" /></a>';
		}
	}
}

?>