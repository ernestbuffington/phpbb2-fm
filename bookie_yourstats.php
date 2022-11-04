<?php 
/** 
*
* @package phpBB2
* @version $Id: bookie_yourstats.php,v 2.0.6 2004/11/17 17:49:34 majorflam Exp $
* @copyright (c) 2004 Majorflam
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true); 
$phpbb_root_path = './'; 
include($phpbb_root_path . 'extension.inc'); 
include($phpbb_root_path . 'common.'.$phpEx); 

// standard session management 
$userdata = session_pagestart($user_ip, PAGE_BOOKIE_YOURSTATS); 
init_userprefs($userdata); 

if ( !$userdata['session_logged_in'] ) 
{ 
	redirect("login.".$phpEx."?redirect=bookie_yourstats.".$phpEx); 
	exit; 
} 

// Start Pagination Vars
$pagination = '&'; 
$total_pag_items = 1;

// set page title 
$page_title = 'Forum Bookmakers - Your Stats'; 

// Get language Variables
include_once($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_bookmakers.' . $phpEx);

// set the config
$new_bet_img = '<a href="' . append_sid("bookies.$phpEx?mode=placebet") . '"><img src="' . $images['icon_bookie_place_bet'] . '" title="' . $lang['icon_bookie_place_bet'] . '" alt="' . $lang['icon_bookie_place_bet'] . '" /></a>';
$your_stats = '<a href="' . append_sid('bookie_yourstats.'.$phpEx) . '"><img src="' . $images['icon_bookie_yourstats'] . '" title="' . $lang['bookie_yourstats_title'] . '" alt="' . $lang['bookie_yourstats_title'] . '" /></a>';
$all_stats = '<a href="' . append_sid('bookie_allstats.'.$phpEx) . '"><img src="' . $images['icon_bookie_allstats'] . '" title="' . $lang['bookie_allstats_title'] . '" alt="' . $lang['bookie_allstats_title'] . '" hspace="3" /></a>';

$userID = $userdata['user_id'];
$timenow = time();

// standard page header 
include($phpbb_root_path . 'includes/page_header.'.$phpEx); 

$template->set_filenames(array( 
	'body' => 'bookie_yourstats.tpl') 
);

$viewmode = htmlspecialchars($HTTP_GET_VARS['viewmode']);
$this_user_view = intval($HTTP_GET_VARS['u']);

if ($viewmode)
{
	//
	// switch the userdata
	//
	$user_id = $this_user_view;
	$old_userdata = $userdata;
	
	$sql = "SELECT * 
		FROM " . USERS_TABLE . "
		WHERE user_id = " . $user_id;
	if (!$result= $db->sql_query($sql)) 
	{
		message_die(GENERAL_ERROR, 'Error in getting points name', '', __LINE__, __FILE__, $sql);
	}
	$userdata = $db->sql_fetchrow($result);
	
	$userID = $userdata['user_id'];
	
	$template->assign_block_vars('viewmode', array());
}

$points_name = $board_config['points_name'];
$users_points = $userdata['user_points'];		

// Get username from user_id
$user_name = $userdata['username'];

// Build Yourstats table
$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$start = ($start < 0) ? 0 : $start;

if ( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? htmlspecialchars($HTTP_POST_VARS['mode']) : htmlspecialchars($HTTP_GET_VARS['mode']);
}
else
{
	$mode = 'date';
}

if(isset($HTTP_POST_VARS['order']))
{
	$sort_order = ($HTTP_POST_VARS['order'] == 'ASC') ? 'ASC' : 'DESC';
}
else if(isset($HTTP_GET_VARS['order']))
{
	$sort_order = ($HTTP_GET_VARS['order'] == 'ASC') ? 'ASC' : 'DESC';
}
else
{
	$sort_order = 'DESC';
}

$mode_types_text = array( $lang['Date'], $lang['bookie_winloss'], $lang['bookie_slip_meeting']);
$mode_types = array('date', 'winnings', 'meeting');

$select_sort_mode = '<select name="mode">';
for($i = 0; $i < count($mode_types_text); $i++)
{
	$selected = ( $mode == $mode_types[$i] ) ? ' selected="selected"' : '';
	$select_sort_mode .= '<option value="' . $mode_types[$i] . '"' . $selected . '>' . $mode_types_text[$i] . '</option>';
}
$select_sort_mode .= '</select>';

$select_sort_order = '<select name="order">';
if($sort_order == 'ASC')
{
	$select_sort_order .= '<option value="ASC" selected="selected">' . $lang['Sort_Ascending'] . '</option><option value="DESC">' . $lang['Sort_Descending'] . '</option>';
}
else
{
	$select_sort_order .= '<option value="ASC">' . $lang['Sort_Ascending'] . '</option><option value="DESC" selected="selected">' . $lang['Sort_Descending'] . '</option>';
}
$select_sort_order .= '</select>';

switch( $mode )
{	
	case 'date':
		$order_by = "time $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'winnings':
		$order_by = "win_lose $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	case 'meeting':
		$order_by = "meeting $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	default:
		$order_by = "time $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
}

// Get Stats
$sql = "SELECT * 
	FROM " . BOOKIE_BETS_TABLE . " 
	WHERE user_id = " . $userID . "
	ORDER BY $order_by"; 
if ( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(GENERAL_ERROR, 'Error in getting users to build history table', '', __LINE__, __FILE__, $sql); 
}

$x = 1;
while ( $row = $db->sql_fetchrow($result) ) 
{ 	
	$bet_timestamp = $row['time']; 
	$meeting = $row['meeting']; 
	$selection = $row['selection'];
	$stake = $row['bet'];
	$odds_1 = $row['odds_1'];
	$odds_2 = $row['odds_2'];
	$winloss = $row['win_lose'];
	$checked = $row['checked'];
	$each_way = $row['each_way'];	
	$this_bet_result = $row['bet_result'];
	
	switch ($this_bet_result)
	{
		case 0:
			$bet_result = (!$checked) ? '<i>' . $lang['bookie_res_pending'] . '</i>' : '<i>' . $lang['bookie_res_undefined'] . '</i>';
			$display_stake = $stake;
			break;
		
		case 1:
			$bet_result = '<b>' . $lang['bookie_res_win'] . '</b>';
			$display_stake = $stake;
			break;
		
		case 2:
			$bet_result = '<b>' . $lang['bookie_res_loss'] . '</b>';
			$display_stake = $stake;
			break;
		
		case 3:
			$bet_result = '<b>' . $lang['bookie_res_refund'] . '</b>';
			$display_stake = $stake;
			break;
		
		case 4:
			$bet_result = '<b>' . $lang['bookie_res_win'] . '</b>';
			$display_stake = $stake;
			break;
		
		case 5:
			$bet_result = '<b>' . $lang['bookie_res_place'] . '</b>';
			$display_stake = $stake / 2;
			break;
		
		default:
			$bet_result='<i>' . $lang['bookie_res_undefined'] . '</i>';
			$display_stake = $stake;
			break;
	}
		
	if ( $winloss < 0 && $winloss != '?')
	{
		$winloss_color = '#993300';
	}
	else
	{
		$winloss_color = '#000000';
	}
	
	//
	// decimal odds?
	//
	if ( $board_config['bookie_frac_or_dec'] )
	{
		// convert fraction to decimal
		$odds_decimal = number_format((($odds_1 / $odds_2) + 1), 2);
	}

	// Convert date to viewable format
	$bet_time = create_date( $board_config['default_dateformat'], $bet_timestamp, $board_config['board_timezone'] );
		
	$row_class = ( !($x % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
		
	$template->assign_block_vars('yourstats', array(
		'ROW_CLASS' => $row_class,
		'BETID' => $betid,
		'TIME' => $bet_time,
		'MEETING' => $meeting,
		'SELECTION' => ( $each_way ) ? $selection . '<br /><b>(' . $lang['bookie_slip_each_way'] . ')</b>' : $selection,
		'STAKE' => number_format($stake),
		'RESULT' => $bet_result,
		'ODDS' => ( $odds_decimal ) ? $odds_decimal : $odds_1 . '/' . $odds_2,
		'WINLOSS' => ( $checked ) ? number_format(($winloss + $display_stake)) : number_format($winloss),
		'WINLOSS_COLOR' => $winloss_color)
	); 
	$x++;
}
$db->sql_freeresult($result);

//
// let's grab the stats on this user
//
$sql = "SELECT * 
	FROM " . BOOKIE_STATS_TABLE . "
	WHERE user_id = " . $userdata['user_id'];
if ( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(GENERAL_ERROR, 'Error in building statisticse', '', __LINE__, __FILE__, $sql); 
}
$row = $db->sql_fetchrow($result);

if ($row)
{
	$template->assign_block_vars('stats_available', array());

	$total_user_won = number_format($row['total_win']);
	$total_user_lost = number_format($row['total_lose']);
	$user_netpos = number_format($row['netpos']);
}

//
// Need to count some totals now
//
$sql = "SELECT COUNT(*) AS total_user_wins 
	FROM " . BOOKIE_BETS_TABLE . "
	WHERE user_id = " . $userdata['user_id'] . "
	AND win_lose > 0
	AND checked = 1";
if ( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(GENERAL_ERROR, 'Error in building statisticse', '', __LINE__, __FILE__, $sql); 
}
$row = $db->sql_fetchrow($result);

$user_wins = number_format($row['total_user_wins']);
$user_wins_tot = $row['total_user_wins'];

$sql = "SELECT COUNT(*) AS total_user_lose 
	FROM " . BOOKIE_BETS_TABLE . "
	WHERE user_id = " . $userdata['user_id'] . "
		AND win_lose < 0
		AND checked = 1";
if ( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(GENERAL_ERROR, 'Error in building statisticse', '', __LINE__, __FILE__, $sql); 
}
$row = $db->sql_fetchrow($result);

$user_lose = number_format($row['total_user_lose']);
$user_lose_tot = $row['total_user_lose'];

$sql = "SELECT COUNT(*) AS user_refund 
	FROM " . BOOKIE_BETS_TABLE . "
	WHERE user_id = " . $userdata['user_id'] . "
		AND win_lose = 0
		AND checked = 1";
if ( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(GENERAL_ERROR, 'Error in building statisticse', '', __LINE__, __FILE__, $sql); 
}
$row = $db->sql_fetchrow($result);

$stakes_refund = number_format($row['user_refund']);
$stakes_refund_tot = $row['user_refund'];

$total_user_bets = number_format(($user_wins_tot + $user_lose_tot + $stakes_refund_tot));

$sql = "SELECT COUNT(*) AS user_pending 
	FROM " . BOOKIE_BETS_TABLE . "
	WHERE user_id = " . $userdata['user_id'] . "
	AND checked = 0";
if ( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(GENERAL_ERROR, 'Error in building statisticse', '', __LINE__, __FILE__, $sql); 
}
$row = $db->sql_fetchrow($result);

$user_pending = number_format($row['user_pending']);

$sql = "SELECT MAX(bet) AS big_bet, MAX(win_lose) AS max, MIN(win_lose) AS min 
	FROM " . BOOKIE_BETS_TABLE . "
	WHERE user_id = " . $userdata['user_id'];
if ( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(GENERAL_ERROR, 'Error in building statistics', '', __LINE__, __FILE__, $sql); 
}
$row = $db->sql_fetchrow($result);

$max_winnings = $row['max'];
$min_winnings = $row['min'];
$big_bet = $row['big_bet'];

$total_spend = 0;
$sql = "SELECT bet 
	FROM " . BOOKIE_BETS_TABLE . "
	WHERE user_id = " . $userdata['user_id'];
if ( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(GENERAL_ERROR, 'Error in building statistics', '', __LINE__, __FILE__, $sql); 
}
while ( $row=$db->sql_fetchrow($result) )
{
	$total_spend=$total_spend+$row['bet'];
}
$db->sql_freeresult($result);

$your_complete_stats = (!$viewmode) ? sprintf($lang['bookie_your_complete_stats'], $total_user_bets, $user_wins, $user_lose, $stakes_refund, $total_user_won, $board_config['points_name'], $total_user_lost, $board_config['points_name'], $user_netpos, $board_config['points_name'], number_format($big_bet), $board_config['points_name'], number_format($max_winnings), $board_config['points_name'], ($min_winnings < 0) ? number_format(($min_winnings * -1)) : 0, $board_config['points_name'], number_format($total_spend), $board_config['points_name'], $user_pending) : 
sprintf($lang['bookie_your_complete_stats_other'], $userdata['username'], $total_user_bets, $user_wins, $user_lose, $stakes_refund, $userdata['username'], $total_user_won, $board_config['points_name'], $total_user_lost, $board_config['points_name'], $userdata['username'], $user_netpos, $board_config['points_name'], number_format($big_bet), $board_config['points_name'], number_format($max_winnings), $board_config['points_name'], ($min_winnings < 0) ? number_format(($min_winnings * -1)) : 0, $board_config['points_name'], $userdata['username'], number_format($total_spend), $board_config['points_name'], $userdata['username'], $user_pending);

// Pagination output
$sql = 'SELECT COUNT(*) AS total 
	FROM ' . BOOKIE_BETS_TABLE . " 
	WHERE user_id = " . $userID . "
	ORDER BY time DESC"; 
if ( !($result = $db->sql_query($sql)) ) 
{ 
   message_die(GENERAL_ERROR, 'Error getting total for pagination', '', __LINE__, __FILE__, $sql); 
} 

if ( $total = $db->sql_fetchrow($result) ) 
{ 
   $total_pag_items = $total['total'];
   $pagination = generate_pagination( ( !$viewmode ) ? "bookie_yourstats.php?mode=$mode&amp;order=$sort_order" : "bookie_yourstats.php?mode=$mode&amp;order=$sort_order&amp;viewmode=viewuser&amp;u=$user_id", $total_pag_items, $board_config['topics_per_page'], $start). ''; 
}

if ($viewmode)
{
	$userdata = $old_userdata;
}

// Set template Vars
$template->assign_vars(array(
	'PAGINATION' => $pagination, 
	'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $board_config['topics_per_page'] ) + 1 ), ceil( $total_pag_items / $board_config['topics_per_page'] )),
	'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
	'S_MODE_SELECT' => $select_sort_mode,
	'L_ORDER' => $lang['Order'],
	'S_ORDER_SELECT' => $select_sort_order,
	'L_SUBMIT' => $lang['Sort'],
	
	'BOOKIES_SLIP_TIME_STATS' => $lang['bookie_slip_time_stats'],
	'BOOKIES_SLIP_MEETING' => $lang['bookie_slip_meeting'],
	'BOOKIES_SLIP_SELECTION' => $lang['bookie_slip_selection'],
	'BOOKIES_SLIP_STAKE' => $lang['bookie_slip_stake'],
	'BOOKIES_SLIP_ODDS' => $lang['bookie_odds'],
	'BOOKIES_SLIP_WINLOSS' => $lang['bookie_winloss'],
	'BOOKIES_YOURSTATS_HEADER' => $lang['bookie_yourstats_header'],
	'POINTS_NAME' => $points_name,
	'USER_NAME' => $user_name,
	'YOUR_STATS' => $your_stats,
	'ALL_STATS' => $all_stats,
	'BOOKIES_SLIP_RESULT' => $lang['bookie_slip_result'],
	'NEW_BET' => $new_bet_img,
	'YOUR_COMPLETE_STATS' => $your_complete_stats,
	'BOOKIE_VERSION' => $board_config['bookie_version'])
);

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