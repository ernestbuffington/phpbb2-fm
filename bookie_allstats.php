<?php 
/** 
*
* @package phpBB2
* @version $Id: bookie_allstats.php,v 2.0.6 2004/11/17 17:49:34 majorflam Exp $
* @copyright (c) 2004 Majorflam
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', true); 
$phpbb_root_path = './'; 
include($phpbb_root_path . 'extension.inc'); 
include($phpbb_root_path . 'common.'.$phpEx); 

// standard session management 
$userdata = session_pagestart($user_ip, PAGE_BOOKIE_ALLSTATS); 
init_userprefs($userdata); 

if ( !$userdata['session_logged_in'] ) 
{ 
	redirect("login.".$phpEx."?redirect=bookie_allstats.".$phpEx); 
	exit; 
} 

// set page title 
$page_title = 'Forum Bookmakers - All Stats'; 

// Get language Variables
include_once($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_bookmakers.' . $phpEx);

// set the config
$new_bet_img = '<a href="' . append_sid("bookies.$phpEx?mode=placebet") . '"><img src="' . $images['icon_bookie_place_bet'] . '" title="' . $lang['icon_bookie_place_bet'] . '" alt="' . $lang['icon_bookie_place_bet'] . '" /></a>';
$your_stats = '<a href="' . append_sid('bookie_yourstats.'.$phpEx) . '"><img src="' . $images['icon_bookie_yourstats'] . '" title="' . $lang['bookie_yourstats_title'] . '" alt="' . $lang['bookie_yourstats_title'] . '" /></a>';
$userID = $userdata['user_id'];
$sel_userbet = intval($HTTP_POST_VARS['sel_userbet']);

// Start Pagination Vars
$pagination = '&'; 
$total_pag_items = 1;

$points_name = $board_config['points_name'];
		
// standard page header 
include($phpbb_root_path . 'includes/page_header.'.$phpEx); 

// assign template 
$template->set_filenames(array( 
	'body' => 'bookie_allstats.tpl') 
); 

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

$mode_types_text = array( $lang['Date'], $lang['bookie_winloss'], $lang['bookie_select_stake'], $lang['bookie_slip_meeting']);
$mode_types = array('date', 'winnings', 'stake', 'meeting');

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
	case 'stake':
		$order_by = "bet $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
	default:
		$order_by = "time $sort_order LIMIT $start, " . $board_config['topics_per_page'];
		break;
}

$sql = "SELECT * 
	FROM " . BOOKIE_BETS_TABLE . " 
	ORDER BY $order_by";
if ( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(GENERAL_ERROR, 'Error in getting bets to build history table', '', __LINE__, __FILE__, $sql); 
}

$x = 1;
while ( $row = $db->sql_fetchrow($result) ) 
{ 	
	$bet_userID = $row['user_id'];
	$betid = $row['bet_id'];
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
			$display_stake = $stake/2;
			break;
		
		default:
			$bet_result = '<i>' . $lang['bookie_res_undefined'] . '</i>';
			$display_stake = $stake;
			break;
	}
		
	// Convert date to viewable format
	$bet_time = create_date( $board_config['default_dateformat'], $bet_timestamp, $board_config['board_timezone'] );
				
	// Get username from userid
	$sql_a = "SELECT username, user_level 
		FROM " . USERS_TABLE . " 
		WHERE user_id = " . $bet_userID;
	$result_a = $db->sql_query($sql_a);
	$row_a = $db->sql_fetchrow($result_a);
	$bet_username = username_level_color($row_a['username'], $row_a['user_level'], $bet_userID);
		
	$username = '<a href=' . append_sid("bookie_yourstats.$phpEx?viewmode=viewuser&" . POST_USERS_URL . "=$bet_userID") . ' class="gensmall">' . $bet_username . '</a>';
	
	if ( $board_config['bookie_frac_or_dec'] )
	{
		// convert fraction to decimal
		$odds_decimal=number_format( (($odds_1/$odds_2)+1), 2);
	}
		
	$row_class = ( !($x % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

	$template->assign_block_vars('allstats', array(
		'ROW_CLASS' => $row_class,
		'USERNAME' => $username,
		'TIME' => $bet_time,
		'MEETING' => $meeting,
		'SELECTION' => ( $each_way ) ? $selection . '<br /><b>(' . $lang['bookie_slip_each_way'] . ')</b>' : $selection,
		'STAKE' => number_format($stake),
		'ODDS' => ( $odds_decimal ) ? $odds_decimal : $odds_1 . '/' . $odds_2,
		'RESULT' => $bet_result,
		'WINLOSS' => ( $checked ) ? number_format( ($winloss+$display_stake) ) : number_format($winloss),
		'WINLOSS_COLOR' => $winloss_color)
	);
	$x++;
}
$db->sql_freeresult($result);

//
// let's grab the stats on all users
//
$total_won_stat = $total_lost_stat = $bookie_netpos_stat = $stats_available = 0;

$sql = "SELECT * 
	FROM " . BOOKIE_STATS_TABLE;
if ( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(GENERAL_ERROR, 'Error in building statisticse', '', __LINE__, __FILE__, $sql); 
}

while ( $row = $db->sql_fetchrow($result) )
{
	$total_won_stat = $total_won_stat+$row['total_win'];
	$total_lost_stat = $total_lost_stat+$row['total_lose'];
	$bookie_netpos_stat = $bookie_netpos_stat+$row['netpos'];
	
	$stats_available++;
}
$db->sql_freeresult($result);

$bookie_netpos_stat = $bookie_netpos_stat * -1;

if ($stats_available)
{
	$template->assign_block_vars('stats_available', array());
}

//
// Need to count some totals now
//
$sql=" SELECT COUNT(*) AS total_user_wins 
	FROM " . BOOKIE_BETS_TABLE . "
	WHERE win_lose > 0
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
	WHERE win_lose < 0
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
	WHERE win_lose = 0
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
	WHERE checked = 0";
if ( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(GENERAL_ERROR, 'Error in building statisticse', '', __LINE__, __FILE__, $sql); 
}
$row = $db->sql_fetchrow($result);

$user_pending = number_format($row['user_pending']);

$sql = "SELECT user_id, bet 
	FROM " . BOOKIE_BETS_TABLE . "
	ORDER BY bet DESC 
	LIMIT 1";
if ( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(GENERAL_ERROR, 'Error in building statistics', '', __LINE__, __FILE__, $sql); 
}
$row = $db->sql_fetchrow($result);

$big_bet = $row['bet'];
$big_bet_id = $row['user_id'];

$sql = "SELECT user_id, win_lose 
	FROM " . BOOKIE_BETS_TABLE . "
	ORDER BY win_lose DESC 
	LIMIT 1";
if ( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(GENERAL_ERROR, 'Error in building statistics', '', __LINE__, __FILE__, $sql); 
}
$row = $db->sql_fetchrow($result);

$max_winnings = $row['win_lose'];
$max_winnings_id = $row['user_id'];

$sql = "SELECT user_id, win_lose 
	FROM " . BOOKIE_BETS_TABLE . "
	ORDER BY win_lose
	LIMIT 1";
if ( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(GENERAL_ERROR, 'Error in building statistics', '', __LINE__, __FILE__, $sql); 
}
$row = $db->sql_fetchrow($result);

$min_winnings = $row['win_lose'];
$min_winnings_id = $row['user_id'];

$total_spend = 0;
$sql = "SELECT bet 
	FROM " . BOOKIE_BETS_TABLE;
if ( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(GENERAL_ERROR, 'Error in building statistics', '', __LINE__, __FILE__, $sql); 
}
while ( $row = $db->sql_fetchrow($result) )
{
	$total_spend = $total_spend+$row['bet'];
}
$db->sql_freeresult($result);

$all_complete_stats = sprintf($lang['bookie_your_complete_allstats'], $total_user_bets, $user_wins, $user_lose, $stakes_refund, number_format($total_won_stat), $board_config['points_name'], number_format($total_lost_stat), $board_config['points_name'], number_format($bookie_netpos_stat), $board_config['points_name'], '<a href="' . append_sid("bookie_allstats.$phpEx?viewmode=viewuser&amp;" . POST_USERS_URL . "=$big_bet_id&amp;mode=stake&amp;order=DESC") . '" class="nav">' . number_format($big_bet) . '</a>',
$board_config['points_name'], '<a href="' . append_sid("bookie_allstats.$phpEx?viewmode=viewuser&amp;" . POST_USERS_URL . "=$max_winnings_id&amp;mode=winnings&amp;order=DESC") . '" class="nav">' . number_format($max_winnings) . '</a>', $board_config['points_name'], ( $min_winnings < 0 ) ? '<a href="' . append_sid("bookie_allstats.$phpEx?viewmode=viewuser&amp;" . POST_USERS_URL . "=$min_winnings_id&amp;mode=winnings&amp;order=ASC") . '" class="nav">' . number_format(($min_winnings * -1)) . '</a>' : 0, $board_config['points_name'], number_format($total_spend), $board_config['points_name'], $user_pending);

$sql = "SELECT COUNT(*) AS total 
	FROM " . BOOKIE_BETS_TABLE . " 
	ORDER BY time DESC"; 
if ( !($result = $db->sql_query($sql)) ) 
{ 
	message_die(GENERAL_ERROR, 'Error getting total for pagination', '', __LINE__, __FILE__, $sql); 
} 

if ( $total = $db->sql_fetchrow($result) ) 
{
	$total_pag_items = $total['total']; 
	$pagination = generate_pagination("bookie_allstats.php?mode=$mode&order=$sort_order", $total_pag_items, $board_config['topics_per_page'], $start). ''; 
}

$page_number = sprintf($lang['Page_of'], ( floor( $start / $board_config['topics_per_page'] ) + 1 ), ceil( $total_pag_items / $board_config['topics_per_page'] ));

// Set template Vars
$template->assign_vars(array(
	'PAGINATION' => $pagination, 
	'PAGE_NUMBER' => $page_number,
	'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
	'S_MODE_SELECT' => $select_sort_mode,
	'L_ORDER' => $lang['Order'],
	'S_ORDER_SELECT' => $select_sort_order,
	'L_SUBMIT' => $lang['Sort'],

	'BOOKIES_SLIP_TIME_STATS' => $lang['bookie_slip_time_stats'],
	'BOOKIES_SLIP_USERNAME' => $lang['bookie_slip_username'],
	'BOOKIES_SLIP_MEETING' => $lang['bookie_slip_meeting'],
	'BOOKIES_SLIP_SELECTION' => $lang['bookie_slip_selection'],
	'BOOKIES_SLIP_STAKE' => $lang['bookie_slip_stake'],
	'BOOKIES_SLIP_ODDS' => $lang['bookie_odds'],
	'BOOKIES_SLIP_WINLOSS' => $lang['bookie_winloss'],
	'BOOKIES_ALLSTATS_HEADER' => $lang['bookie_allstats_header'],
	'POINTS_NAME' => $points_name,
	'BOOKIES_SLIP_RESULT' => $lang['bookie_slip_result'],
	'NEW_BET' => $new_bet_img,
	'YOUR_STATS' => $your_stats,
	'ALL_COMPLETE_STATS' => $all_complete_stats,
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