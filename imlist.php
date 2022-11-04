<?php
/** 
*
* @package phpBB
* @version $Id: imlist.php,v 1.5.2 2002/08/31 17:50:00 Thoul Exp $
* @copyright (c) 2002 Jeremy Rogers
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

// Configuration Variables - these determine how the IM List behaves

$cfg = array();

/*
$cfg['notall'] - determines if all members are displayed or only members with a
value in the user database table for the specified Instant Messenger
 Possible settings: TRUE - displays only members with the IM information
						FALSE - displays all members
Example: $cfg['notall'] = FALSE;
*/

$cfg['notall'] = TRUE; 

/*
$cfg['notall_default'] and $cfg['notall_string'][TYPE]
	These are strings used to limit sql query if $cfg['notall'] is TRUE.  The
	strings should contain comma separated values. Any (words) should be enclosed
	in single 'quotes'.  Constants, like NULL or numbers, should not.

	$cfg['notall_default'] contains some default strings used to elimate users who
	entered	a single space, the word "none", or nothing at all for messenger
	information. It is recommended to leave $notall_default alone - it will be
	added added to each of other the individual strings as needed.

	There should be a $cfg['notall_string'][TYPE] line for each type of instant
	messager, with TYPE being replaced by name of the instant messenger information
	column in the user database table.
Example: $cfg['notall_string']['user_yim'] = "";  // This one is for Yahoo IM.

	If you want to prevent a person with a certain IM name from appearing in
	listings of that IM program, you can add that name to the "notall_string" for
	that IM program.  In the example below, the user that entered "SexyChick" for
	their Yahoo IM information would not appear in the list of Yahoo IM users.
Example: $cfg['notall_string']['user_yim'] = "'SexyChick'";
         $cfg['notall_string']['user_aim'] = "'SexyChick', 'ReallyCool'";

	Any single or double quotes in entries will need to be escaped.
Example: $cfg['notall_string']['user_yim'] = "'I\'m \"number 1!\"'";
*/

$cfg['notall_default'] = '" ", "none", "NULL"';
$cfg['notall_string']['user_yim']  = '';
$cfg['notall_string']['user_icq']  = '';
$cfg['notall_string']['user_aim']  = '';
$cfg['notall_string']['user_msnm'] = '';
$cfg['notall_string']['user_xfi'] = '';
$cfg['notall_string']['user_skype'] = '';
$cfg['notall_string']['user_gtalk'] = '';
// End Configuration Variables

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_IMLIST);
init_userprefs($userdata);
//
// End session management
//

function im_select($name, $current_type, $types)
{
	$select = '<select name="' . $name . '">';
	$selected = '';
	while( list($k, $v) = each($types) )
	{
		$selected = ( $current_type == $k ) ? ' selected="selected"' : '';
		$select .= '<option value="' . $k . '"' . $selected . '>' . $v . '</option>';
	}
	$select .= '</select>';

	return $select;
}


$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;
$start = ($start < 0) ? 0 : $start;

$im_type = '';

if( !empty( $HTTP_GET_VARS['im_type'] ) || $HTTP_POST_VARS['im_type'] )
{
	$temp_type = ( $HTTP_GET_VARS['im_type'] ) ? $HTTP_GET_VARS['im_type'] : $HTTP_POST_VARS['im_type'];
}
else
{
	$temp_type = 'all';
}

switch( $temp_type )
{
	case 'all':
	default:
		$im_type['sql'] = 'user_yim, user_aim, user_msnm, user_icq, user_xfi, user_skype, user_gtalk';
		$im_type['name']['user_yim'] = $lang['YIM'];
		$im_type['name']['user_aim'] = $lang['AIM'];
		$im_type['name']['user_msnm'] = $lang['MSNM'];
		$im_type['name']['user_icq'] = $lang['ICQ'];
		$im_type['name']['user_xfi'] = $lang['XFI'];
		$im_type['name']['user_skype'] = $lang['skype'];
		$im_type['name']['user_gtalk'] = $lang['GTALK'];
		$im_type['limit'] = array('user_yim', 'user_aim', 'user_msnm', 'user_icq', 'user_xfi', 'user_skype', 'user_gtalk');
		$im_type['type'] = 'all';
		break;
	case 'yim':
		$im_type['sql'] = 'user_yim';
		$im_type['name'] = $lang['YIM'];
		$im_type['type'] = 'yim';
		break;
	case 'aim':
		$im_type['sql'] = 'user_aim';
		$im_type['name'] = $lang['AIM'];
		$im_type['type'] = 'aim';
		break;
	case 'msnm':
		$im_type['sql'] = 'user_msnm';
		$im_type['name'] = $lang['MSNM'];
		$im_type['type'] = 'msnm';
		break;
	case 'icq':
		$im_type['sql'] = 'user_icq';
		$im_type['name'] = $lang['ICQ'];
		$im_type['type'] = 'icq';
		break;
	case 'xfi':
		$im_type['sql'] = 'user_xfi';
		$im_type['name'] = $lang['XFI'];
		$im_type['type'] = 'xfi';
		break;
	case 'skype':
		$im_type['sql'] = 'user_skype';
		$im_type['name'] = $lang['skype'];
		$im_type['type'] = 'skype';
		break;
	case 'gtalk':
		$im_type['sql'] = 'user_gtalk';
		$im_type['name'] = $lang['GTALK'];
		$im_type['type'] = 'gtalk';
		break;
}


if(isset($HTTP_POST_VARS['order']))
{
	$sort_order = ($HTTP_POST_VARS['order'] == 'ASC') ? 'ASC' : 'DESC';
}
else if(isset($HTTP_GET_VARS['order']))
{
	$sort_order = ($HTTP_GET_VARS['order'] == 'ASC') ? 'ASC' : 'DESC';
}
else if( $cfg['notall'] )
{
	$sort_order = 'ASC';
}
else
{
	$sort_order = 'DESC';
}

if ( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode);
}
else
{
	$mode = 'username';
}

//
// Instant Messenger list sorting
//
$mode_types = array('im_type' => $lang['Select_imtype_method'],	'username' => $lang['Sort_Username']);
$mode_im_types = array('all' => $lang['All'], 'aim' => $lang['AIM'], 'icq' => $lang['ICQ'], 'msnm' => $lang['MSNM'], 'yim' => $lang['YIM'], 'gtalk' => $lang['GTALK'], 'xfi' => $lang['XFI'], 'skype' => $lang['skype']);
$order_types = array('ASC' => $lang['Sort_Ascending'], 'DESC' => $lang['Sort_Descending']);

$select_sort_mode = im_select('mode', $mode, $mode_types);
$select_sort_order = im_select('order', $sort_order, $order_types);
$select_im_type_mode = im_select('im_type', $im_type['type'], $mode_im_types);


//
// Generate page
//
$page_title = $lang['Instant_messenger_list'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'body' => 'imlist_body.tpl')
);
make_jumpbox('viewforum.'.$phpEx);

$order_by = $imlist_where_sql = $limit_sql = $exlude_sql = '';

if( $mode == 'im_type' )
{
	$order_by = ' ORDER BY ' . $im_type['sql'];
}
else
{
	$order_by = ' ORDER BY username';
}
$order_by .= ' ' . $sort_order;


if ( $cfg['notall'] && $im_type['type'] != 'all' )
{
	$imlist_where_sql = ' AND ' .  $im_type['sql'] . ' NOT IN (' . $cfg['notall_default'];
	$imlist_where_sql .= ( !empty($cfg['notall_string'][$im_type['sql']]) ) ? ', ' . $cfg['notall_string'][$im_type['sql']] : '';
	$imlist_where_sql .= ')';
}
elseif( $cfg['notall'] )
{
	while( list($key, $val) = each($cfg['notall_string']) )
	{
		$exlude_sql .= ( (empty($exlude_sql) ) ? '': ' OR ' ) . '(' . $key . ' NOT IN (' . $cfg['notall_default'];
		$exlude_sql .= ( !empty($val) ) ? ', ' . $val : '';
		$exlude_sql .= '))';
	}

	$imlist_where_sql = ' AND ( ' . $exlude_sql . ')';
}

$limit_sql = ' LIMIT ' . $start . ', ' . $board_config['topics_per_page'];

$sql = 'SELECT username, user_id, user_level, ' . $im_type['sql'] . ' FROM ' . USERS_TABLE . ' WHERE user_id <> ' . ANONYMOUS;
$sql .= $imlist_where_sql . $order_by . $limit_sql;

if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, $str, '', __LINE__, __FILE__, $sql);
}

if( $im_type['type'] == 'all' )
{
	$template->assign_block_vars('switch_all_top', array(
		'L_IMTYPE_user_yim' => $im_type['name']['user_yim'],
		'L_IMTYPE_user_aim' => $im_type['name']['user_aim'],
		'L_IMTYPE_user_msnm' => $im_type['name']['user_msnm'],
		'L_IMTYPE_user_icq' => $im_type['name']['user_icq'],
		'L_IMTYPE_user_xfi' => $im_type['name']['user_xfi'],
		'L_IMTYPE_user_gtalk' => $im_type['name']['user_gtalk'],
		'L_IMTYPE_user_skype' => $im_type['name']['user_skype'])
	);
}
else
{
	$template->assign_block_vars('switch_single_top', array(
		'L_IMTYPE' => $im_type['name'])
	);
}

if ( $row = $db->sql_fetchrow($result) )
{
	$i = 0;
	do
	{
		$username = username_level_color($row['username'], $row['user_level'], $row['user_id']);
		$user_id = $row['user_id'];

		$temp_url = append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $user_id);

		$im_type['img'] = $im_type['str'] = array();

		if( !empty($row['user_yim']) )
		{
			$im_type['img']['user_yim'] = '<a href="ymsgr:sendIM?' . $row['user_yim'] . '&__you+there?"><img src="http://opi.yahoo.com/online?u=' . $row['user_yim'] . '&m=g&t=1" alt="' . $lang['YIM'] . '" title="' . $lang['YIM'] . '" /></a>';
			$im_type['str']['user_yim'] = '<a href="ymsgr:sendIM?' . $row['user_yim'] . '&__you+there?">' . $lang['YIM'] . '</a>';
		}
		else		
		{
			$im_type['img']['user_yim'] = $im_type['str']['user_yim'] = '';
		}

		if( !empty($row['user_aim']) )
		{
			$im_type['img']['user_aim'] = '<a href="aim:goim?screenname=' . str_replace('+', '', $row['user_aim']) . '&amp;message=Hello+Are+you+there?"><img src="http://big.oscar.aol.com/' . $row['user_aim'] . '?on_url='.$images['icon_aim_online'].'&off_url='.$images['icon_aim_offline'].'" alt="' . $lang['AIM'] . '" title="' . $lang['AIM'] . '" /></a>';
			$im_type['str']['user_aim'] = '<a href="aim:goim?screenname=' . $row['user_aim'] . '&amp;message=Hello+Are+you+there?">' . $lang['AIM'] . '</a>';
		}
		else		
		{
			$im_type['img']['user_aim'] = $im_type['str']['user_aim'] = '';
		}

		if( !empty($row['user_msnm']) )
		{
           $im_type['img']['user_msnm'] = '<a href="http://members.msn.com/' . $row['user_msnm'] . '" target="_blank"><img src="' . $images['icon_msnm'] . '" alt="' . $lang['MSNM'] . '" title="' . $lang['MSNM'] . '" /></a>';
           $im_type['str']['user_msnm'] = '<a href="http://members.msn.com/' . $row['user_msnm'] . '" target="_blank">' . $lang['MSNM'] . '</a>';
		}
		else		
		{
			$im_type['img']['user_msnm'] = $im_type['str']['user_msnm'] = '';
		}

		if ( !empty($row['user_icq']) )
		{
			$im_type['img']['user_icq'] = '<a href="http://wwp.icq.com/scripts/search.dll?to=' . $row['user_icq'] . '"><img src="' . $images['icon_icq'] . '" alt="' . $lang['ICQ'] . '" title="' . $lang['ICQ'] . '" /></a>';
			$im_type['str']['user_icq'] =  '<a href="http://wwp.icq.com/scripts/search.dll?to=' . $row['user_icq'] . '">' . $lang['ICQ'] . '</a>';
		}
		else		
		{
			$im_type['img']['user_icq'] = $im_type['str']['user_icq'] = '';
		}
		
		if ( !empty($row['user_xfi']) )
		{
			$im_type['img']['user_xfi'] = '<a href="http://www.xfire.com/xf/modules.php?name=XFire&amp;file=profile&amp;uname=' . $row['user_xfi'] . '" target="_blank"><img src="' . $images['icon_xfi'] . '" alt="' . $lang['XFI'] . '" title="' . $lang['XFI'] . '" /></a>';
			$im_type['str']['user_xfi'] =  '<a href="http://www.xfire.com/xf/modules.php?name=XFire&amp;file=profile&amp;uname=' . $row['user_xfi'] . '">' . $lang['XFI'] . '</a>';
		}
		else		
		{
			$im_type['img']['user_xfi'] = $im_type['str']['user_xfi'] = '';
		}

		if ( !empty($row['user_gtalk']) )
		{
			$im_type['img']['user_gtalk'] = '<a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $row['user_id']) . '"><img src="' . $images['icon_gtalk'] . '" alt="' . $lang['GTALK'] . '" title="' . $lang['GTALK'] . '" /></a>';
			$im_type['str']['user_gtalk'] =  '<a href="' . append_sid('profile.'.$phpEx.'?mode=viewprofile&amp;' . POST_USERS_URL . '=' . $row['user_id']) . '">' . $lang['GTALK'] . '</a>';
		}
		else		
		{
			$im_type['img']['user_gtalk'] = $im_type['str']['user_gtalk'] = '';
		}

		if ( !empty($row['user_skype']) )
		{
   			$im_type['img']['user_skype'] = '<a href="#" onClick=window.open("profile_skype_popup.'.$phpEx.'?' . POST_USERS_URL. '=' . $row['user_id'] . '","gesamt","location=no,menubar=no,toolbar=no,scrollbars=auto,width=320,height=500,status=no",title="Skype")><img src="' . $images['icon_skype'] . '" alt="' . $lang['skype'] . '" title="' . $lang['skype'] . '" /></a>';
			$im_type['str']['user_skype'] = '<a href="#" onClick=window.open("profile_skype_popup.'.$phpEx.'?' . POST_USERS_URL . '=' . $row['user_id'] . '","gesamt","location=no,menubar=no,toolbar=no,scrollbars=auto,width=320,height=500,status=no",title="Skype")>' . $lang['skype'] . '</a>';
		}
		else		
		{
			$im_type['img']['user_skype'] = $im_type['str']['user_skype'] = '';
		}

		$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

		$template->assign_block_vars('memberrow', array(
			'ROW_NUMBER' => $i + ( $start + 1 ),
			'ROW_CLASS' => $row_class,
			'USERNAME' => $username,
			'U_VIEWPROFILE' => $temp_url)
		);

		if( $im_type['type'] == 'all' )
		{
			$template->assign_block_vars('memberrow.switch_all_list', array(
				'IMTYPE_IMG_user_yim' => $im_type['img']['user_yim'],
				'IMTYPE_user_yim' => $im_type['str']['user_yim'],
				'IMTYPE_IMG_user_aim' => $im_type['img']['user_aim'],
				'IMTYPE_user_aim' => $im_type['str']['user_aim'],
				'IMTYPE_IMG_user_msnm' => $im_type['img']['user_msnm'],
				'IMTYPE_user_msnm' => $im_type['str']['user_msnm'],
				'IMTYPE_IMG_user_icq' => $im_type['img']['user_icq'],
				'IMTYPE_user_icq' => $im_type['str']['user_icq'],
				'IMTYPE_IMG_user_xfi' => $im_type['img']['user_xfi'],
				'IMTYPE_user_xfi' => $im_type['str']['user_xfi'],
				'IMTYPE_IMG_user_gtalk' => $im_type['img']['user_gtalk'],
				'IMTYPE_user_gtalk' => $im_type['str']['user_gtalk'],
				'IMTYPE_IMG_user_skype' => $im_type['img']['user_skype'],
				'IMTYPE_USER_user_skype' => $im_type['user']['user_skype'],
				'IMTYPE_user_skype' => $im_type['str']['user_skype'])
			);
		}
		else
		{
			$template->assign_block_vars('memberrow.switch_single_list', array(
				'IMTYPE_IMG' => $im_type['img'][$im_type['sql']],
				'IMTYPE' => $im_type['str'][$im_type['sql']])
			);
		}

		$i++;
	}
	while ( $row = $db->sql_fetchrow($result) );
}


$sql = 'SELECT count(*) AS total
	FROM ' . USERS_TABLE . '
	WHERE user_id <> ' . ANONYMOUS . $imlist_where_sql;
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Error getting total users', '', __LINE__, __FILE__, $sql);
}

if ( $total = $db->sql_fetchrow($result) )
{
	$total_members = $total['total'];
	$pagination = generate_pagination("imlist.$phpEx?mode=$mode&amp;order=$sort_order&amp;im_type=" . $im_type['type'], $total_members, $board_config['topics_per_page'], $start). '&nbsp;';
}

$template->assign_vars(array(
	'L_SELECT_SORT_METHOD' => $lang['Select_sort_method'],
	'L_SELECT_IMTYPE_METHOD' => $lang['Select_imtype_method'],
	'L_ORDER' => $lang['Order'],
	'L_SORT' => $lang['Sort'],

	'S_MODE_SELECT' => $select_sort_mode,
	'S_ORDER_SELECT' => $select_sort_order,
	'S_IMTYPE_SELECT' => $select_im_type_mode,
	'S_MODE_ACTION' => append_sid('imlist.'.$phpEx),

	'PAGINATION' => $pagination,
	'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / $board_config['topics_per_page'] ) + 1 ), ceil( $total_members / $board_config['topics_per_page'] )), 

	'L_GOTO_PAGE' => $lang['Goto_page'])
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