<?php
/** 
*
* @package phpBB
* @version $Id: auctions.php,v 1.0.1 1/12/2006 11:51 PM mj Exp $
* @copyright (c) 2005 MJ < mj@phpbb-fm.com >
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);


//
// Include language file
//
$language = $board_config['default_lang'];
if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_auctions.'.$phpEx) ) 
{ 
	$language = 'english'; 
} 
include($phpbb_root_path . 'language/lang_' . $language . '/lang_auctions.' . $phpEx);


//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_AUCTIONS);
init_userprefs($userdata);


//
// Check page is enabled
//
if ( !$board_config['auction_enable'] ) 
{ 
	message_die(GENERAL_MESSAGE, $lang['Auctions_disable'] . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.'.$phpEx) .'">', '</a>')); 
}


//
// Main ebay work ...
//
// Build the ebay url	
$URL = 'http://cgi6.ebay.com/ws/eBayISAPI.dll?ViewListedItems&userid=' . $board_config['auction_ebay_user_id'] . '&include=0&since=' . $board_config['auction_enable_ended'] . '&sort=' . $board_config['auction_sort_order'] . '&rows=0'; 

// Where to Start grabbing and where to End grabbing
$GrabStart = '<tr bgcolor=\"#ffffff\">';
$GrabEnd = 'About eBay';

// Open the file
$file = @fopen($URL, 'r');

// Read the file
if (!function_exists('file_get_contents')) 
{
	$r = fread($file, 80000);
} 
else 
{
    $r = file_get_contents($URL);  
}

// Grab just the contents we want
$stuff = eregi("$GrabStart(.*)$GrabEnd", $r, $content);

//
// Get rid of some rubbish we don't need.
// And set things up to be split into lines and items.
//
if ( $stuff ) 
{
	$content[1] = str_replace("\r\n", "", $content[1]);
	$content[1] = str_replace("\n", "", $content[1]);
	$content[1] = str_replace("\r", "", $content[1]);
	$content[1] = str_replace("</td>", "[ITEMS]", $content[1]);
	$content[1] = str_replace("</tr>", "[LINES]\n", $content[1]);
	$content[1] = preg_replace("'<[\/\!]*?[^<>]*?>'si" , "" , $content[1]);

	// Line used during debug (for troubleshooting)
	//echo '<hr>' . $content[1] . '<hr />';

	// Close the file
	@fclose($file);

	$stuff = $content[1];

	// Build our first array for EOF
	$items = explode("[LINES]", $stuff);

	// Loop through our lines
	$count = 0;
	foreach ($items as $listing) 
	{
		// Break apart each line into individual items
		list($item_id, $starts, $ends, $price, $title, $highbidder) = explode("[ITEMS]", $listing);

		// We want to get rid of the (*) from the High Bidder
    	$highbidder = str_replace("(*)" , "" , $highbidder);

      	// We want change the Available to the Buy It Now logo for the High Bidder
      	$highbidder = str_replace('Available', '<img src="' . $images['icon_buynow'] . '" alt="" title="" />', $highbidder);

      	// Get rid of some rubbish from itemnum we don't need.
      	$item_id = str_replace("\n", "", $item_id);

		// Use a countdown to get Time Left
		// We first need to break apart End and convert the months to numbers
		$seperate = split('[- :]', $ends);

		$seperate[0] = str_replace('Jan', 1, $seperate[0]);
		$seperate[0] = str_replace('Feb', 2, $seperate[0]);
		$seperate[0] = str_replace('Mar', 3, $seperate[0]);
		$seperate[0] = str_replace('Apr', 4, $seperate[0]);
		$seperate[0] = str_replace('May', 5, $seperate[0]);
		$seperate[0] = str_replace('Jun', 6, $seperate[0]);
		$seperate[0] = str_replace('Jul', 7, $seperate[0]);
		$seperate[0] = str_replace('Aug', 8, $seperate[0]);
		$seperate[0] = str_replace('Sep', 9, $seperate[0]);
		$seperate[0] = str_replace('Oct', 10, $seperate[0]);
		$seperate[0] = str_replace('Nov', 11, $seperate[0]);
		$seperate[0] = str_replace('Dec', 12, $seperate[0]);
	
    	$month = $seperate[0];
    	$day = $seperate[1];
    	$year = $seperate[2];
    	$hour = $seperate[3] + $board_config['auction_timezone_offset']; 
    	$minute = $seperate[4];
		$second = $seperate[5];

		// mktime is the marked time, and time() is the current time. 
		$target = mktime($hour, $minute, $second, $month, $day, $year); 
		$diff = $target - time(); 

		$days = ($diff - ($diff % 86400)) / 86400; 
		$diff = $diff - ($days * 86400); 
		$hours = ($diff - ($diff % 3600)) / 3600; 
		$diff = $diff - ($hours * 3600); 
		$minutes = ($diff - ($diff % 60)) / 60; 
		$diff = $diff - ($minutes * 60); 
		$seconds = ($diff - ($diff % 1)) / 1; 

		// next we put it into a presentable format
		$left = $days . 'd' . ' ' . $hours . 'h' . ' ' . $minutes . 'm';
	
		// and last we want to print auction ended when the auction has ended
		if ( $seconds <= 0 ) 
		{
			$left = $lang['Auctions_Ended'];
		}
		else
		{
			$left = $left;
		}
	
		// Make sure we have content to print out and print it
		if ($starts && $ends && $title && ($count < $board_config['auction_amt'])) 
		{
			$row_class = ( !($count % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
	
			$template->assign_block_vars('items', array(
				'ROW_CLASS' => $row_class,
	
				'ID' => $item_id,
				'TITLE' => $title,
				'IMG' => ($board_config['auction_enable_thumbs']) ? '<img src="http://thumbs.ebaystatic.com/pict/' . $item_id . '6464_0.jpg" alt="' . $title . '" title="' . $title . '" /><br />' : '',
				'ITEM_URL' => $board_config['auction_ebay_url'] . '/ws/eBayISAPI.dll?ViewItem&item=' . $item_id,
				'STARTS' => $starts,
				'ENDS' => $ends,
				'LEFT' => '<span style="color: #FF0000">' . $left . '</span>',
				'PRICE' => '<span style="color: #009900">' . $price . '</span>',
				'HIGH_BIDDER' => $highbidder)
			);
				
			$count++;
		}
	}
}


//
// Generate page
//
$page_title = $lang['Auctions'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'auctions_body.tpl')
);
make_jumpbox('viewforum.'.$phpEx);

$template->assign_vars(array(
	'L_AUCTION_TITLE' => $lang['Auctions_Title'],
	'L_AUCTION_START' => $lang['Auctions_Start'],
	'L_AUCTION_END' => $lang['Auctions_End'],
	'L_AUCTION_LEFT' => $lang['Auctions_Left'],
	'L_AUCTION_PRICE' => $lang['Auctions_Price'],
	'L_AUCTION_HIGH_BIDDER' => $lang['Auctions_Highbidder'],

	'S_COPYRIGHT' => sprintf($lang['Auctions_Version_Copyright']))
);

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>