<?php
/** 
*
* @package phpBB2
* @version $Id: toplist.php,v 1.3.8 2004/07/11 16:46:15 wyrihaximus Exp $
* @copyright (c) 2003 Cees-Jan Kiewiet
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*/
	
if( !defined("IN_ADMIN") ) 
{ 
   $in_admin = false; 
   $phpbb_root_path = './';
} 
else 
{ 
   $in_admin = true; 
   $phpbb_root_path = '../';
}

if ( !$in_admin && !isset($f) ) 
{ 
	$f = ( !empty($HTTP_POST_VARS['f']) ) ? htmlspecialchars($HTTP_POST_VARS['f']) : ( !empty($HTTP_GET_VARS['f']) ) ? htmlspecialchars($HTTP_GET_VARS['f']) : ( !empty($_POST['f']) ) ? htmlspecialchars($_POST['f']) : ( !empty($_GET['f']) ) ? htmlspecialchars($_GET['f']) : ( !empty($_REQUEST['f']) ) ? htmlspecialchars($_REQUEST['f']) : '';
} 

if ( $f != 'toplist_top10' ) 
{ 
	if( !isset($f) )
	{
		$f = 'toplist';
	}
	else if ($f == '')
	{
		$f = 'toplist';
	}

	if( !$in_admin && $f != 'toplist_top10' )
	{
		define('IN_PHPBB', true);
		include($phpbb_root_path . 'extension.inc');
		include($phpbb_root_path . 'common.'.$phpEx);

		// include language file
		$language = $board_config['default_lang'];
		if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_toplist.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_toplist.' . $phpEx);

		if(is_null($f))
		{
			$f = ( !empty($HTTP_POST_VARS['f']) ) ? htmlspecialchars($HTTP_POST_VARS['f']) : ( !empty($HTTP_GET_VARS['f']) ) ? htmlspecialchars($HTTP_GET_VARS['f']) : ( !empty($_POST['f']) ) ? htmlspecialchars($_POST['f']) : ( !empty($_GET['f']) ) ? htmlspecialchars($_GET['f']) : ( !empty($_REQUEST['f']) ) ? htmlspecialchars($_REQUEST['f']) : '';
		}
	
		//
		// Start session management
		//
		$userdata = session_pagestart($user_ip, PAGE_TOPLIST);
		init_userprefs($userdata);
		//
		// End session management
		//
		$template->set_filenames(array(
			'body' => 'toplist_body.tpl')
		);
		make_jumpbox('viewforum.'.$phpEx); 
	}
	else
	{
		$template->set_filenames(array(
			'body' => 'admin/toplist_edit_body.tpl')
		);
	}

	if( ( $f == 'toplisteditsitelogin' || $f == 'toplisteditsite' || $f == 'toplistnew' || $f == 'toplistnewadd' ) && !$userdata['session_logged_in'] )
	{
		$f = 'toplist';
	}
}

//
// Check and set various parameters
//
$sure = ( !empty($HTTP_POST_VARS['sure']) ) ? $HTTP_POST_VARS['sure'] : ( !empty($HTTP_GET_VARS['sure']) ) ? $HTTP_GET_VARS['sure'] : ( !empty($_POST['sure']) ) ? $_POST['sure'] : ( !empty($_GET['sure']) ) ? $_GET['sure'] : ( !empty($_REQUEST['sure']) ) ? $_REQUEST['sure'] : '';
$id = ( !empty($HTTP_POST_VARS['id']) ) ? $HTTP_POST_VARS['id'] : ( !empty($HTTP_GET_VARS['id']) ) ? $HTTP_GET_VARS['id'] : ( !empty($_POST['id']) ) ? $_POST['id'] : ( !empty($_GET['id']) ) ? $_GET['id'] : ( !empty($_REQUEST['id']) ) ? $_REQUEST['id'] : '';
$iduser = ( !empty($HTTP_POST_VARS['iduser']) ) ? $HTTP_POST_VARS['iduser'] : ( !empty($HTTP_GET_VARS['iduser']) ) ? $HTTP_GET_VARS['iduser'] : ( !empty($_POST['iduser']) ) ? $_POST['iduser'] : ( !empty($_GET['iduser']) ) ? $_GET['iduser'] : ( !empty($_REQUEST['iduser']) ) ? $_REQUEST['iduser'] : '';
$nam = ( !empty($HTTP_POST_VARS['nam']) ) ? $HTTP_POST_VARS['nam'] : ( !empty($HTTP_GET_VARS['nam']) ) ? $HTTP_GET_VARS['nam'] : ( !empty($_POST['nam']) ) ? $_POST['nam'] : ( !empty($_GET['nam']) ) ? $_GET['nam'] : ( !empty($_REQUEST['nam']) ) ? $_REQUEST['nam'] : '';
$inf = ( !empty($HTTP_POST_VARS['inf']) ) ? $HTTP_POST_VARS['inf'] : ( !empty($HTTP_GET_VARS['inf']) ) ? $HTTP_GET_VARS['inf'] : ( !empty($_POST['inf']) ) ? $_POST['inf'] : ( !empty($_GET['inf']) ) ? $_GET['inf'] : ( !empty($_REQUEST['inf']) ) ? $_REQUEST['inf'] : '';
$ban = ( !empty($HTTP_POST_VARS['ban']) ) ? $HTTP_POST_VARS['ban'] : ( !empty($HTTP_GET_VARS['ban']) ) ? $HTTP_GET_VARS['ban'] : ( !empty($_POST['ban']) ) ? $_POST['ban'] : ( !empty($_GET['ban']) ) ? $_GET['ban'] : ( !empty($_REQUEST['ban']) ) ? $_REQUEST['ban'] : '';
$lin = ( !empty($HTTP_POST_VARS['lin']) ) ? $HTTP_POST_VARS['lin'] : ( !empty($HTTP_GET_VARS['lin']) ) ? $HTTP_GET_VARS['lin'] : ( !empty($_POST['lin']) ) ? $_POST['lin'] : ( !empty($_GET['lin']) ) ? $_GET['lin'] : ( !empty($_REQUEST['lin']) ) ? $_REQUEST['lin'] : '';
$imgfile = ( !empty($HTTP_POST_VARS['imgfile']) ) ? $HTTP_POST_VARS['imgfile'] : ( !empty($HTTP_GET_VARS['imgfile']) ) ? $HTTP_GET_VARS['imgfile'] : ( !empty($_POST['imgfile']) ) ? $_POST['imgfile'] : ( !empty($_GET['imgfile']) ) ? $_GET['imgfile'] : ( !empty($_REQUEST['imgfile']) ) ? $_REQUEST['imgfile'] : '';
$imgfile_type = ( !empty($HTTP_POST_VARS['imgfile_type']) ) ? $HTTP_POST_VARS['imgfile_type'] : ( !empty($HTTP_GET_VARS['imgfile_type']) ) ? $HTTP_GET_VARS['imgfile_type'] : ( !empty($_POST['imgfile_type']) ) ? $_POST['imgfile_type'] : ( !empty($_GET['imgfile_type']) ) ? $_GET['imgfile_type'] : ( !empty($_REQUEST['imgfile_type']) ) ? $_REQUEST['imgfile_type'] : '';
$r = ( !empty($HTTP_POST_VARS['r']) ) ? $HTTP_POST_VARS['r'] : ( !empty($HTTP_GET_VARS['r']) ) ? $HTTP_GET_VARS['r'] : ( !empty($_POST['r']) ) ? $_POST['r'] : ( !empty($_GET['r']) ) ? $_GET['r'] : ( !empty($_REQUEST['r']) ) ? $_REQUEST['r'] : '';
$what = ( !empty($HTTP_POST_VARS['what']) ) ? $HTTP_POST_VARS['what'] : ( !empty($HTTP_GET_VARS['what']) ) ? $HTTP_GET_VARS['what'] : ( !empty($_POST['what']) ) ? $_POST['what'] : ( !empty($_GET['what']) ) ? $_GET['what'] : ( !empty($_REQUEST['what']) ) ? $_REQUEST['what'] : '';
$img = ( !empty($HTTP_POST_VARS['img']) ) ? $HTTP_POST_VARS['img'] : ( !empty($HTTP_GET_VARS['img']) ) ? $HTTP_GET_VARS['img'] : ( !empty($_POST['img']) ) ? $_POST['img'] : ( !empty($_GET['img']) ) ? $_GET['img'] : ( !empty($_REQUEST['img']) ) ? $_REQUEST['img'] : '';
$out = ( !empty($HTTP_POST_VARS['out']) ) ? $HTTP_POST_VARS['out'] : ( !empty($HTTP_GET_VARS['out']) ) ? $HTTP_GET_VARS['out'] : ( !empty($_POST['out']) ) ? $_POST['out'] : ( !empty($_GET['out']) ) ? $_GET['out'] : ( !empty($_REQUEST['out']) ) ? $_REQUEST['out'] : '';
$hin = ( !empty($HTTP_POST_VARS['hin']) ) ? $HTTP_POST_VARS['hin'] : ( !empty($HTTP_GET_VARS['hin']) ) ? $HTTP_GET_VARS['hin'] : ( !empty($_POST['hin']) ) ? $_POST['hin'] : ( !empty($_GET['hin']) ) ? $_GET['hin'] : ( !empty($_REQUEST['hin']) ) ? $_REQUEST['hin'] : '';
$resend = ( !empty($HTTP_POST_VARS['resend']) ) ? $HTTP_POST_VARS['resend'] : ( !empty($HTTP_GET_VARS['resend']) ) ? $HTTP_GET_VARS['resend'] : ( !empty($_POST['resend']) ) ? $_POST['resend'] : ( !empty($_GET['resend']) ) ? $_GET['resend'] : ( !empty($_REQUEST['resend']) ) ? $_REQUEST['resend'] : '';

if ( !$in_admin )
{
	include($phpbb_root_path . 'extension.inc');
	include($phpbb_root_path . 'mods/toplist/toplist_common.'.$phpEx);
}

$tpl_pre = ( $in_admin ) ? 'admin/toplist_top10' : 'index_toplist'; 
$template->set_filenames(array(
	'toplist_top10' => $tpl_pre . '_body.tpl')
);
toplist_startup();

$template->assign_vars(array(
	'L_SELECT' => $lang['Select'],
	'L_PLAIN' => $lang['Plain'],
	'L_INCLUDE_RANK' => $lang['Include_rank'],
    'RANK' => $lang['Rank'],
    'SITE' => $lang['Site'],
	'IN_HITS' => $lang['Hits_in'],
	'OUT_HITS' => $lang['Hits_out'],
	'IMG_HITS' => $lang['Image_displays'],
    'NAME' => $lang['Name'],
    'INFO' => $lang['Information'],
    'SURL' => $lang['SURL'],
    'BURL' => $lang['BURL'],
    'L_ADD_SITE' => $lang['As'],
    'L_EDIT_SITE' => $lang['Easi'],
    'L_RESEND_HTML' => $lang['Resend_HTML'],
    'L_IP' => $lang['IP_Address'],
    
    'BUTTON1' => $board_config['toplist_button_1'],
    'BUTTON2' => $board_config['toplist_button_2'],
    'BUTTON1_L' => $board_config['toplist_button_1_l'],
    'BUTTON2_L' => $board_config['toplist_button_2_l'],
    'LOCATION' => $location . 'mods/toplist/',
    'LOCATION_IMG' => $location . 'images/toplist/')
);

switch ($f)
{
	case 'toplist':
		$plus = false;
		
		$sql = "SELECT id 
			FROM " . TOPLIST_TABLE . (($board_config['toplist_' . HIN . '_activation'] == 1) ? " WHERE " . HIN . " > 0" : "") . " 
			LIMIT 1"; 
       	$result = $db->sql_query($sql); 
       
     	if(!$result) 
     	{ 
     		message_die(GENERAL_ERROR, 'Could not obtain toplist data.','', __LINE__, __FILE__, $sql); 
      	}
		
		while($row = $db->sql_fetchrow($result))
		{
			$plus = true;
			break;
		}
		$db->sql_freeresult($result);

		if( !$plus )
		{
			$message = sprintf($lang['Toplist_no_sites'], $board_config['sitename']) . '<br /><br />' . sprintf($lang['Toplist_no_sites_add'], '<a href="' . append_sid($phpbb_root_path . 'toplist.'.$phpEx.'?' . POST_FORUM_URL . '=toplistnew') . '">', '</a>');
	
			message_die(GENERAL_MESSAGE, $message);
		}

		$i = toplist_calculate_total();
		
		$ikke = 3;

		if( !$board_config['toplist_view_' . IMG . '_hits'] )
		{
			$ikke = $ikke - 1;
		}
		
		if(!$board_config['toplist_view_' . OUT . '_hits'])
		{
			$ikke = $ikke - 1;
		}
		
		if(!$board_config['toplist_view_' . HIN . '_hits'])
		{
			$ikke = $ikke - 1;
		}
		
		$template->assign_block_vars('main',array(
			'XTRASTUFF' => ( ( $userdata['session_logged_in'] ) ? $userdata['username'] . '<b>:</b> <a href="' . append_sid('toplist.'.$phpEx.'?' . POST_FORUM_URL . '=toplistnew') . '"><img src="' . $images['icon_add'] . '" alt="' . $lang['As'] . '" title="' . $lang['As'] . '" /></a> <a href="' . append_sid('toplist.'.$phpEx.'?' . POST_FORUM_URL . '=toplisteditsitelogin') . '"><img src="' . $images['icon_edit'] . '" alt="' . $lang['Easi'] . '" title="' . $lang['Easi'] . '" /></a>' : sprintf($lang['Notmem'], '<a href="' . append_sid('profile.'.$phpEx.'?mode=' . REGISTER_MODE) . '">', '</a>')),
			'HEAD2' => ( ( $ikke == 0 ) ? $lang['Toplist'] : $i[0] . ' ' . $lang['User'] . (($i>1) ? 's' : '')),
			'COLSPAN' => ( ( $ikke == 0 ) ? 1 : 2),
			'COLSPAN3' => ( ( $ikke == 0 ) ? 1 : $ikke),
			'HEAD' => ( ( $ikke == 0 ) ? $i[0] . ' ' . $lang['User']  . (($i>1) ? 's' : '') : $lang['Toplist']))
		);
		
		if($board_config['toplist_view_' . HIN . '_hits'])
		{
			$template->assign_block_vars('main.switch_toplist_view_in_hits_l', array(
				'L_IN_HITS' => $lang['Hits_in'])
			);
		}

		if($board_config['toplist_view_' . OUT . '_hits'])
		{
			$template->assign_block_vars('main.switch_toplist_view_out_hits_l', array(
				'L_OUT_HITS' => $lang['Hits_out'])
			);
		}

		if($board_config['toplist_view_' . IMG . '_hits'])
		{
			$template->assign_block_vars('main.switch_toplist_view_img_hits_l', array(
				'L_IMG_HITS' => $lang['Image_displays'])
			);
		}

		if($board_config['toplist_view_' . IMG . '_hits'] || $board_config['toplist_view_' . OUT . '_hits'] || $board_config['toplist_view_' . HIN . '_hits'])
		{
			$template->assign_block_vars('main.switch_toplist_view_middle',array(
	       		'COLSPAN2' => $ikke - 1,
        		'NEED' => '')
			);
		}
		
		$sql = "SELECT * 
			FROM " . TOPLIST_TABLE . (($board_config['toplist_' . HIN . '_activation'] == 1) ? ' WHERE ' . HIN . ' != 0' : '') . " ORDER BY tot DESC, nam, inf, owner DESC";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not query toplist information', '', __LINE__, __FILE__, $sql);
		}
				
		$template->assign_vars(array(
			'EDIT_IMG' => $images['icon_edit'],
			'DEL_IMG' => $images['topic_mod_delete'],
			'IP_IMG' => $images['icon_ip'])
		);

		$i = 0;
		while( $row = $db->sql_fetchrow($result) )
		{
			$i++;

	    	$template->assign_block_vars('main.toplist', array(
	           	'R' => $i,
	           	'ID' => $row['id'],
	           	'NAM' => $row['nam'],
	           	'INF' => $row['inf'],
	           	'BAN' => $location . 'toplist_image.'.$phpEx.'?url=' . rawurlencode((($i <= $board_config['toplist_imge_dis'] || $board_config['toplist_imge_dis'] == -1) ? $row['ban'] : $phpbb_root_path . 'images/spacer.gif')))
	    	);
	
    		if( $row['owner'] == $userdata['user_id'] || $userdata['user_level'] == ADMIN )
    		{
    			$template->assign_block_vars('main.toplist.switch_edit_del', array(
					'EDIT_URL' => append_sid('toplist.'.$phpEx.'?' . POST_FORUM_URL . '=toplisteditsite&amp;iduser=' . $row['id'] . '&amp;what=edit'),
					'DEL_URL' => append_sid('toplist.'.$phpEx.'?' . POST_FORUM_URL . '=toplisteditsite&amp;iduser=' . $row['id'] . '&amp;what=Del&amp;sure=yes'),
					'DEL_EXPL' => str_replace("'", "\'", sprintf($lang['Del_toplist'], $row['nam'])),
					'IP' => ((decode_ip($row['ip']) == '0.0.0.0') ? sprintf($lang['IP_Address_toplist'], decode_ip($row['ip']), $board_config['sitename'] . ' ' . $lang['Toplist'], $board_config['sitename'] . ' ' . $lang['Toplist_toplist']) : sprintf($lang['IP_Address'], decode_ip($row['ip']))))
    			);
    		}
    		
	    	if($board_config['toplist_view_' . HIN . '_hits'])
	    	{
	    		$template->assign_block_vars('main.toplist.switch_toplist_view_in_hits', array(
		           	'HIN' => $row[HIN])
			   	);
			}
	
	    	if($board_config['toplist_view_' . OUT . '_hits'])
	    	{
	    		$template->assign_block_vars('main.toplist.switch_toplist_view_out_hits', array(
	           		'OUT' => $row[OUT])
	    		);
	    	}
    
	    	if($board_config['toplist_view_' . IMG . '_hits'])
	    	{
	    		$template->assign_block_vars('main.toplist.switch_toplist_view_img_hits', array(
	           		'IMG' => $row[IMG])
	    		);
	    	}
		}
		$db->sql_freeresult($result);

		break;

	case 'toplist_top10':
		toplist_calculate_total();

		// include language file
		$language = $board_config['default_lang'];
		if( !file_exists($phpbb_root_path . 'language/lang_' . $language . '/lang_toplist.'.$phpEx) ) { $language = 'english'; } include($phpbb_root_path . 'language/lang_' . $language . '/lang_toplist.' . $phpEx);
	
		$ikke = 5;
		
		if(!$board_config['toplist_view_' . HIN . '_hits'])
		{
			$ikke = $ikke - 1;
		}
		
		if(!$board_config['toplist_view_' . IMG . '_hits'])
		{
			$ikke = $ikke - 1;
		}
		
		if(!$board_config['toplist_view_' . OUT . '_hits'])
		{
			$ikke = $ikke - 1;
		}
		
		$template->assign_vars(array(
			'COLSPAN' => $ikke,
			'L_TOPLIST_TOP10' => $lang['Toplist_top10'],
		    'RANK' => $lang['Rank'],
    		'SITE' => $lang['Site'])
		);
		
		if( $board_config['toplist_view_' . HIN . '_hits'] )
		{
			$template->assign_block_vars('switch_toplist_view_in_hits', array(
				'IN_HITS' => $lang['Hits_in'])
			);
		}
		
		if( $board_config['toplist_view_' . OUT . '_hits'] )
		{
			$template->assign_block_vars('switch_toplist_view_out_hits', array(
				'OUT_HITS' => $lang['Hits_out'])
			);
		}
		
		if( $board_config['toplist_view_' . IMG . '_hits'] )
		{
			$template->assign_block_vars('switch_toplist_view_img_hits', array(
				'IMG_HITS' => $lang['Image_displays'])
			);
		}
		
		if( $board_config['toplist_view_' . IMG . '_hits'] || $board_config['toplist_view_' . OUT . '_hits'] || $board_config['toplist_view_' . HIN . '_hits'] )
		{
			$template->assign_block_vars('switch_toplist_view_middle',array(
        			'COLSPAN2' => $ikke - 1)
			);
		}
		
		$sql = "SELECT * 
			FROM " . TOPLIST_TABLE . (($board_config['toplist_' . HIN . '_activation'] == 1) ? " WHERE " . HIN . " != 0" : "") . " ORDER BY tot DESC, nam LIMIT 10";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not query toplist information', '', __LINE__, __FILE__, $sql);
		}
		
		$i = 0;
		while( $row = $db->sql_fetchrow($result) )
		{
    		$i++;
    		
    		$template->assign_block_vars('toplist', array(
            	'R' => $i,
            	'ID' => $row['id'],
            	'NAM' => $row['nam'],
            	'INF' => $row['inf'],
            	'BAN' => $location . 'toplist_image.'.$phpEx.'?url=' . rawurlencode((($i <= $board_config['toplist_imge_dis'] || $board_config['toplist_imge_dis'] == -1) ? $row['ban'] : $phpbb_root_path . 'images/spacer.gif')))
    		);
    		
    		if($board_config['toplist_view_' . HIN . '_hits'])
    		{
    			$template->assign_block_vars('toplist.switch_toplist_view_in_hits', array(
	            	'HIN' => $row[HIN])
		    	);
			}
    		
    		if($board_config['toplist_view_' . OUT . '_hits'])
    		{
    			$template->assign_block_vars('toplist.switch_toplist_view_out_hits', array(
            		'OUT' => $row[OUT])
    			);
    		}
    
    		if($board_config['toplist_view_' . IMG . '_hits'])
    		{
    			$template->assign_block_vars('toplist.switch_toplist_view_img_hits', array(
            		'IMG' => $row[IMG])
    			);
    		}
		}
		$db->sql_freeresult($result);

		$template->assign_var_from_handle('TOPLIST_TOP10', 'toplist_top10');

		break;		
		
	case 'toplisteditsitelogin':
		$template->assign_block_vars('editlogin', array(
        	'SPACER' => " ")
		);
		
		$yn = false;
		$uid = $user_id;

		$sql = "SELECT * 
			FROM " . TOPLIST_TABLE . " 
			ORDER BY id";
		if ( !($query = $db->sql_query($sql)) )
		{
    		message_die(GENERAL_ERROR, 'Could not get topList sites.', '', __LINE__, __FILE__, $query);
		}
		
		while( $rst = $db->sql_fetchrow($query) )
		{
	    	if($rst['owner'] == $userdata['user_id'] || $userdata['user_level'] == ADMIN)
	    	{
	    		$yn = true;
	    	
	    		$template->assign_block_vars('editlogin.data', array(
	           		'ACTION' => append_sid(( ($in_admin) ? 'admin_board.'.$phpEx.'?mode=toplist&amp;' . POST_FORUM_URL . '=toplisteditsite' : 'toplist.'.$phpEx.'?' . POST_FORUM_URL . '=toplisteditsite')),
	           		'NAM' => $lang['Name'] . ': ' . $rst['nam'],
	           		'ID' => $rst['id'],
	           		'DEL' => sprintf($lang['Del_toplist'], $rst['nam']),
	           		'SID' => $sid,
            		'BAN' => $location . 'toplist_image.'.$phpEx.'?url=' . rawurlencode($rst['ban']))
	    		);
	    	}
		}
		$db->sql_freeresult($result);
	
		if( !$yn )
		{
			$message = sprintf($lang['Newmem'], $board_config['sitename']) . '<br /><br />' . sprintf($lang['Toplist_no_sites_add'], '<a href="' . append_sid($phpbb_root_path . 'toplist.'.$phpEx.'?' . POST_FORUM_URL . '=toplistnew') . '">', '</a>');
   		
			message_die(GENERAL_MESSAGE, $message);			
		}
		break;
		
	case 'toplisteditsite':
		$template->assign_block_vars('edit', array(
	        'SPACE' => " ")
		);
		
		if( $what == "Del" )
		{
			$query = "SELECT * 
				FROM " . TOPLIST_TABLE;
			if ( !($result = $db->sql_query($query)) )
			{
    			message_die(GENERAL_ERROR, 'Could not get toplist data.', '', __LINE__, __FILE__, $query);
			}
			
			$temp = " ";
			
			while( $row = $db->sql_fetchrow($result) )
			{
				if(($userdata['user_id'] == $row['owner'] || $userdata['user_level'] == ADMIN) && $iduser == $row['id'] && $sure == "yes")
				{
					$delquery = "DELETE FROM " . TOPLIST_TABLE . " 
						WHERE id = '$iduser'";
					if ( !($result = $db->sql_query($delquery)) )
					{
    					message_die(GENERAL_ERROR, 'Could not delete site from the toplist.', '', __LINE__, __FILE__, $query);
					}
					else
					{
						$return_url = ( $in_admin ) ? 'admin_board.'.$phpEx.'?mode=toplist' : 'toplist.'.$phpEx;

	    				message_die(GENERAL_MESSAGE, sprintf($lang['Site_delete_success'], $row['nam']) . '<br /><br />' . sprintf($lang['Click_return_toplist'], '<a href="' . append_sid($return_url) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.'.$phpEx) . '">', '</a>'));
					}
				}
			}
			$db->sql_freeresult($result);

			$return_url = ( $in_admin ) ? 'admin_board.'.$phpEx.'?mode=toplist' : 'toplist.'.$phpEx;

			$message = $lang['Site_delete_fail'] . '<br /><br />' . sprintf($lang['Click_return_toplist'], '<a href="' . append_sid($return_url) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid('index.'.$phpEx) . '">', '</a>');
		
			message_die(GENERAL_MESSAGE, $message);
		
		}
			
		if ($what == 'edit')
		{
			$query = "SELECT * 
				FROM " . TOPLIST_TABLE;
			if ( !($result = $db->sql_query($query)) )
			{
    			message_die(GENERAL_ERROR, 'Could not query toplist data.', '', __LINE__, __FILE__, $query);
			}
		
			while( $row = $db->sql_fetchrow($result) )
			{
				if(($userdata['user_id'] == $row['owner'] || $userdata['user_level'] == ADMIN) && $iduser == $row['id'])
				{
					$template->assign_block_vars('edit.data', array(
        				'ACTION' => append_sid(( ($in_admin) ? 'admin_board.'.$phpEx.'?mode=toplist&amp;' . POST_FORUM_URL . '=toplisteditsite' : 'toplist.'.$phpEx.'?' . POST_FORUM_URL . '=toplisteditsite')),
	       				'ID' => $row['id'],
	       				'INF' => $row['inf'],
	       				'BAN' => $row['ban'],
	       				'LIN' => $row['lin'],
	       				'NAM' => $row['nam'])
					);
						
			        $exp_imgfile = explode("$#$", $row['imgfile']);
			        
					$template->assign_block_vars('edit.data.stuff', array(
       					'X' => $lang['Cb'],
       					'G_SELECTED' => (sizeof($exp_imgfile) > 1) ? 'checked="checked"' : '',
       					'P_SELECTED' => ((!(sizeof($exp_imgfile) > 1)) ? 'checked="checked"' : ''))
					);
						
					if((sizeof($exp_imgfile) > 1))
					{
						$row['imgfile'] = $exp_imgfile[0];
					}
						
					if ($dir = @opendir($phpbb_root_path . 'images/toplist'))
					{
						while (($file = @readdir($dir)) !== false)
						{
    						if( $file != 'index.htm' && $file != 'index.html' && $file != '.' && $file != '..' && $file != '.htaccess' && $file != 'Thumbs.db')
    						{
    							$template->assign_block_vars('edit.data.stuff.image', array(
            						'FILENAME' => $file,
            						'SELECTED' => ($file == $row['imgfile']) ? 'checked="checked"' : '')
    							);
    						}
  						}  						
  						@closedir($dir);
					}
				}
			}
			$db->sql_freeresult($result);
		}

		if ($what == 'signch')
		{
			$query = "SELECT * 
				FROM " . TOPLIST_TABLE;
			if ( !($result = $db->sql_query($query)) )
			{
    				message_die(GENERAL_ERROR, 'Could not query toplist data.', '', __LINE__, __FILE__, $query);
			}

			while( $row = $db->sql_fetchrow($result) )
			{
				if(($userdata['user_id'] == $row['owner'] || $userdata['user_level'] == ADMIN) && $iduser == $row['id'])
				{
					if(function_exists('getimagesize') && $board_config['toplist_dimensions'] != '' && $ban != 'http://' && $ban != '' && $ban != ' ')
					{
						$good = false;
						
						$size = getimagesize($ban);
						
						$dimensions = explode('#', $board_config['toplist_dimensions']);
						
						for($i = 0; $i < sizeof($dimensions); $i++)
						{
							$image_dimensions = explode('x', $dimensions[$i]);
							
							if($image_dimensions[0] >= $size[0] && $image_dimensions[1] >= $size[1])
							{
								$good = true;
							}
							$dimension_list .= '<br />' . $dimensions[$i];
						}
			
						if(!$good)
						{
							message_die(GENERAL_ERROR, $lang['Image_wrong_size'] . ': ' . $dimension_list . '<br /><br />' . sprintf($lang['Click_return_toplist'], '<a href="' . append_sid('toplist.'.$phpEx) . '">', '</a>'));
						}
					}
								
					switch($imgfile_type)
					{
						case 'gdlib':
						//default:
							$imgfile = $imgfile . '$#$' . rand(0,68468447);
							break;
							
						case 'plain':
						default:
							$imgfile = $imgfile;
							break;
					}
								
					$xquery = "UPDATE " . TOPLIST_TABLE . " 
						SET nam = '" . $nam . "', inf = '" . $inf . "', lin = '" . $lin . "', ban = '" . $ban . "', imgfile = '" . $imgfile . "' 
						WHERE id = '" . $iduser . "'";
					if ( !($xresult = $db->sql_query($xquery)) )
					{
    						message_die(GENERAL_ERROR, 'Could not update toplist site data.', '', __LINE__, __FILE__, $xquery);
					}
					
					if (!$in_admin)
					{
						$html_code = gen_html_code($iduser);
						if ($resend)
						{
							$to_userdata = get_userdata($row['owner']);
							include($phpbb_root_path . 'includes/emailer.'.$phpEx);
							$emailer = new emailer($board_config['smtp_delivery']);
			
							$emailer->extra_headers('From: ' . $board_config['board_email'] . "\r\n"); 
							$emailer->extra_headers('Reply-to: ' . $board_config['board_email'] . "\r\n"); 

							$emailer->use_template('resend_toplist_html_code', $to_userdata['user_lang']);
							$emailer->email_address($to_userdata['user_email']);
							$emailer->set_subject('');
			
							$emailer->assign_vars(array(
								'HTML_CODE' => $html_code,
								'SITE_TOPLIST' => $board_config['sitename'] . ' :: ' . $lang['Toplist'], 
								'USERNAME' => $to_userdata['username'],
								'EMAIL_SIG' => (!empty($board_config['board_email_sig'])) ? str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']) : '')
							);
							$emailer->send();
							$emailer->reset();
						}

						$return_url = ( $in_admin ) ? 'admin_board.'.$phpEx.'?mode=toplist' : 'toplist.'.$phpEx;
					
						$message = $lang['Site_update_success'] . "<br /><br />" . $lang['Add_HTML'] . "<br /><br /><textarea rows='2' cols='120'>" . $html_code . "</textarea><br /><br />" . sprintf($lang['Click_return_toplist'], "<a href=\"" . append_sid($return_url) . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");
	
						message_die(GENERAL_MESSAGE, $message);					
					}
					else
					{
						$return_url = ( $in_admin ) ? 'admin_board.'.$phpEx.'?mode=toplist' : 'toplist.'.$phpEx;

						$message = $lang['Site_update_success'] . "<br /><br />" . sprintf($lang['Click_return_toplist'], "<a href=\"" . append_sid($return_url) . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");
		
						message_die(GENERAL_MESSAGE, $message);
					}
				}
			}
			$db->sql_freeresult($result);
		}
		break;
		
	case 'toplistnew':		
		$template->assign_block_vars('new', array(
        	'ACTION' => append_sid((($in_admin) ? 'admin_board.'.$phpEx.'?mode=toplist' : 'toplist.'.$phpEx)),
        	'SPACERT' => " ")
		);

		$template->assign_block_vars('new.stuff', array(
        	'X' => $lang['Cb'])
		);
		
		if ($dir = @opendir($phpbb_root_path . 'images/toplist'))
		{
			while (($file = readdir($dir)) !== false)
			{
    			if($file != 'index.htm' && $file != 'index.html' && $file != '.' && $file != '..' && $file != '.htaccess' && $file != 'Thumbs.db')
    			{
    				$template->assign_block_vars('new.stuff.image', array(
            			'FILENAME' => $file,
            			'SELECTED' => ($file == $row['imgfile']) ? 'checked="checked"' : '' )
    				);
    			}
  			}  
   			@closedir($dir);
		}
		
		break;
		
	case 'toplistnewadd':
		if( function_exists('getimagesize') && $board_config['toplist_dimensions'] != '' && $ban != 'http://' && $ban != '' && $ban != ' ')
		{
			$good = false;
			
			$size = getimagesize($ban);
			
			$dimensions = explode('#', $board_config['toplist_dimensions']);
			
			for($i = 0; $i < sizeof($dimensions); $i++)
			{
				$image_dimensions = explode('x', $dimensions[$i]);
				
				if( $image_dimensions[0] == $size[0] && $image_dimensions[1] == $size[1] )
				{
					$good = true;
				}
				$dimension_list .= '<br />' . $dimensions[$i];
			}

			if( !$good )
			{
				message_die(GENERAL_ERROR, $lang['Image_wrong_size'] . ': ' . $dimension_list);
			}
		}
	
		$xquery = "SELECT *
			FROM " . TOPLIST_TABLE;
		if ( !($xresult = $db->sql_query($xquery)) )
		{
    		message_die(GENERAL_ERROR, 'Could not get toplist data.', '', __LINE__, __FILE__, $xquery);
		}
		
		while( $row = $db->sql_fetchrow($xresult) )
		{
			if( $row['lin'] == $lin )
			{
				$message = $lang['Site_exists'] . "<br /><br />" . sprintf($lang['Click_return_toplist'], "<a href=\"" . append_sid("toplist.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");
	
				message_die(GENERAL_MESSAGE, $message);
			}
		}
		$db->sql_freeresult($result);

		$xquery = "INSERT INTO " . TOPLIST_TABLE . " (nam, lin, inf, ban, owner, imgfile, ip) 
			VALUES ('" . $nam . "', '" . $lin . "', '" . $inf . "', '" . $ban . "', '" . $userdata['user_id'] . "', '" . $imgfile . "', '" . $user_ip . "')";
		if ( !($xresult = $db->sql_query($xquery)) )
		{
   			message_die(GENERAL_ERROR, 'Could not add toplist site to database.', '', __LINE__, __FILE__, $xquery);
		}

		$sql = "SELECT * 
			FROM " . TOPLIST_TABLE . " 
			WHERE nam = '" . $nam . "' 
				AND lin = '" . $lin . "' 
				AND inf = '" . $inf . "' 
				AND ban = '" . $ban . "' 
				AND owner = '" . $userdata['user_id'] . "' 
				AND imgfile = '" . $imgfile . "'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not get toplist user data.', '', __LINE__, __FILE__, $sql);
		}

		$stuff = $db->sql_fetchrow($result);

		if(!$in_admin)
		{
			$html_code = gen_html_code($stuff['id']);
			
			include($phpbb_root_path . 'includes/emailer.'.$phpEx);
			$emailer = new emailer($board_config['smtp_delivery']);
			
			$emailer->extra_headers('From: ' . $board_config['board_email'] . "\r\n"); 
			$emailer->extra_headers('Reply-to: ' . $board_config['board_email'] . "\r\n"); 

			$emailer->use_template('toplist_html_code', $row['user_lang']);
			$emailer->email_address($userdata['user_email']);
			$emailer->set_subject('');

			$emailer->assign_vars(array(
				'USER_SITE' => $nam,
				'HTML_CODE' => $html_code,
				'SITE_TOPLIST' => $board_config['sitename'] . ' :: ' . $lang['Toplist'], 
				'USERNAME' => $userdata['username'],
				'EMAIL_SIG' => ( !empty( $board_config['board_email_sig'] ) ) ? str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']) : '')
			);
			
			$emailer->send();
			$emailer->reset();

			$message = $lang['Site_added_success'] . "<br /><br />" . $lang['Add_HTML'] . "<br /><br /><textarea rows='2' cols='120'>" . $html_code . "</textarea><br /><br />" . sprintf($lang['Click_return_toplist'], "<a href=\"" . append_sid("toplist.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");
	
			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			$message = $lang['Site_added_success'] . "<br /><br />" . sprintf($lang['Click_return_toplist'], "<a href=\"" . append_sid("toplist.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_index'], "<a href=\"" . append_sid("index.$phpEx") . "\">", "</a>");
		
			message_die(GENERAL_MESSAGE, $message);
		}
		break;
}

if( !$in_admin && $f != 'toplist_top10' )
{
	//
	// Generate the page
	//
	$page_title = $lang['Toplist'];
	include($phpbb_root_path . 'includes/page_header.'.$phpEx);
	
	//
	// Force password update
	//
	if ($board_config['password_update_days'])
	{
		include($phpbb_root_path . 'includes/update_password.'.$phpEx);
	}

	$template->pparse('body');

	include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
}

?>