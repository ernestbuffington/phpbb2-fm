<?php
/** 
*
* @package phpBB2
* @version $Id: album_rss.php,v 1.0.2 2005/04/21 20:20:00 chyduskam Exp $
* @copyright (c) 2005 Egor Naklonyaeff
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define ('IN_PHPBB', true);
$phpbb_root_path = './';
$album_root_path = $phpbb_root_path . 'mods/album/';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
include($phpbb_root_path . 'includes/rss_config.'.$phpEx);
include($phpbb_root_path . 'includes/rss_functions.'.$phpEx);

$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$starttime = $mtime;

$verinfo = 'v1.0.2';
$ProgName = $lang['Album_RSS'] . ' ' . $verinfo;
$deadline = 0;
if(isset($HTTP_SERVER_VARS['HTTP_IF_MODIFIED_SINCE']))
{
    $deadline = strtotime($HTTP_SERVER_VARS['HTTP_IF_MODIFIED_SINCE']);
	if (CACHE_TIME > 0) 
	{
		if ((time() - $deadline) < CACHE_TIME)
		{
    	    ExitWithHeader("304 Not Modified");
		}
	}
}

//
// Begin Cache 
//
$use_cached = false;
$cache_file = '';
if (CACHE_TO_FILE && CACHE_TIME > 0) 
{
	$cache_file = $phpbb_root_path . $cache_root . $cache_filename;
	if ($cache_root!='' && empty($HTTP_GET_VARS))
	{
		 $cachefiletime = @filemtime($cache_file);
		 $timedif = ($deadline - $cachefiletime);
		 if ($timedif < CACHE_TIME && filesize($cache_file) > 0) 
		 {
		 	$use_cached = true;
		}
	}
}


//
// gzip_compression
//
$do_gzip_compress = FALSE;
$useragent = (isset($HTTP_SERVER_VARS['HTTP_USER_AGENT'])) ? $HTTP_SERVER_VARS['HTTP_USER_AGENT'] : getenv('HTTP_USER_AGENT');
if ($use_cached && AUTOSTYLED && strpos($useragent, 'MSIE'))
{
	$use_cached = false;
}
if ( $board_config['gzip_compress'] )
{
	$phpver = phpversion();
	if ( $phpver >= '4.0.4pl1' && ( strstr($useragent,'compatible') || strstr($useragent,'Gecko') ) )
	{
		if ( extension_loaded('zlib') )
		{
			if (headers_sent() != TRUE) 
			{ 
				//
				// Here we updated the gzip function.
				// With this method we can get the server up
				// to 10% faster
				//
				$gz_possible = isset($HTTP_SERVER_VARS['HTTP_ACCEPT_ENCODING']) && eregi('gzip, deflate', $HTTP_SERVER_VARS['HTTP_ACCEPT_ENCODING']); 
				if ($gz_possible) 
				{
					ob_start('ob_gzhandler'); 
				}
			}
		}
	}
	else if ( $phpver > '4.0' )
	{
		if ( strstr($HTTP_SERVER_VARS['HTTP_ACCEPT_ENCODING'], 'gzip') )
		{
			if ( extension_loaded('zlib') )
			{
				$do_gzip_compress = TRUE;
				ob_start();
				ob_implicit_flush(0);
			
 				header('Content-Encoding: gzip');
			}
		}
	}
}


//
// How many posts do you want to returnd (count)?  Specified in the URL with "c=".  Defaults to 25, upper limit of 50.
//
$count = ( isset($HTTP_GET_VARS['c']) ) ? intval($HTTP_GET_VARS['c']) : DEFAULT_ITEMS;
$count = ( $count == 0 ) ? DEFAULT_ITEMS : $count;
$count = ( $count > MAX_ITEMS ) ? MAX_ITEMS : $count;
$needlogin = ( (isset($HTTP_GET_VARS['login'])) or (isset($HTTP_GET_VARS['uid']))) ? true : false;
$no_limit = ( isset($HTTP_GET_VARS['nolimit']) ) ? true : false;
$admin_mode = ( isset($HTTP_GET_VARS['admin']) ) ? true : false;
$see_comments = ((isset($HTTP_GET_VARS['comments'])) and (!$admin_mode)) ? true : false;
$cat_id = ( isset($HTTP_GET_VARS['cat_id']) ) ? intval($HTTP_GET_VARS['cat_id']) : '';
$sql_cat_where = ( (!empty($cat_id)) and ($cat_id!=0)) ? 'AND pic_cat_id =' . $cat_id : '';

//
// Start session management
//
// Check user
$user_id = ($needlogin) ? rss_get_user() : ANONYMOUS;
if ($user_id == ANONYMOUS && AUTOLOGIN)
{
	$userdata = session_pagestart($user_ip, PAGE_ALBUM_RSS);
	$user_id = $userdata['user_id'];
}
else 
{
	$userdata = rss_session_begin($user_id, $user_ip, PAGE_ALBUM_RSS);
}
init_userprefs($userdata);
$username = $userdata['username'];
//
// End session management
//
	
	
//
// Get general album information
//
include($album_root_path . 'album_common.'.$phpEx);
	
$site = real_path('album.'. $phpEx);
$index = real_path('album_page.'. $phpEx);
$thumbs = real_path('album_thumbnail.'. $phpEx);
$reply = real_path('album_comment.'. $phpEx);

$site_name = strip_tags($board_config['sitename']);
$site_descs = explode('@@@', $board_config['site_desc']);
$site_description = $site_descs[rand(0, sizeof($site_descs) - 1)];

$site_url = real_path('');

$index_site = $site;
$index_url = $index;
$thumb_url = $thumbs;
$reply_url = $reply;

//
// Initialise template
//
if (isset($HTTP_GET_VARS['atom']))
{
    $template->set_filenames(array(
    	'body' => 'atom_body.tpl')
   	);
    $verinfo .= 'A';
}
else
{
	$template->set_filenames(array(
		'body' => 'rss_body.tpl')
	);
    $verinfo .= 'R';
}

	
	if (isset($HTTP_GET_VARS['styled']) || (AUTOSTYLED and strpos($useragent, 'MSIE')))
{
	$template->assign_block_vars('switch_enable_xslt', array());
}

$user_lang = $userdata['user_lang'];
if (empty($user_lang))
{
	$user_lang = $board_config['default_lang'];
}

$template->assign_vars(array(
	'S_CONTENT_ENCODING' => $lang['ENCODING'],
	'BOARD_URL' => $site_url,
	'BOARD_TITLE' => htmlspecialchars(undo_htmlspecialchars($lang['Photo_Album'] . ' :: ' . $site_name)),
	'PROGRAM' => $ProgName,
	'BOARD_DESCRIPTION' => $site_description,
	'BOARD_MANAGING_EDITOR' => $board_config['board_email'],
	'BOARD_WEBMASTER' => $board_config['board_email'],
	'BUILD_DATE' => gmdate('D, d M Y H:i:s') . ' GMT',
	'ATOM_BUILD_DATE' => gmdate("Y-m-d\TH:i:s") . 'Z',
	'READER' => $username,
	'L_AUTHOR' => $lang['Author'],
	'L_POSTED' => $lang['Posted'],
	'LANGUAGE'=>FormatLanguage($user_lang),
	'L_POST' => $lang['Post'])
);

// BEGIN Recent Photo
// Start check permissions
$sql_allowed_cat = '';
$check_sel = ($admin_mode) ? 0 : 1;
if ($userdata['user_level'] <> ADMIN)
{
	$album_user_access = personal_gallery_access(true, false);
	$not_allowed_cat = ($album_user_access['view'] == 1 ) ? '' : 0;
	$sql = "SELECT c.*
		FROM ". ALBUM_CAT_TABLE ." AS c
		WHERE cat_id <> 0";
	if( !($result = $db->sql_query($sql)) )
	{
        ExitWithHeader("500 Internal Server Error","Could not query categories list");
	}
	while( $row = $db->sql_fetchrow($result) )
	{
		$album_user_access = album_user_access($row['cat_id'], $row, 1, 0, 0, 0, 0, 0); // VIEW
		if($admin_mode)
		{
			if (($album_user_access['moderator'] != 1) or ($row['cat_approval'] != MOD)) 
			{
				$not_allowed_cat .= ($not_allowed_cat == '') ? $row['cat_id'] : ',' . $row['cat_id'];
			}
		}
		else
		{
			if ($album_user_access['view'] != 1) 
			{
				$not_allowed_cat .= ($not_allowed_cat == '') ? $row['cat_id'] : ',' . $row['cat_id'];
			}
		}
	}
	$sql_not_allowed_cat = (empty($not_allowed_cat)) ? '' : "AND pic_cat_id NOT IN ($not_allowed_cat)";
}
// End check permissions
	
$NotErrorFlag = false;
$sql_limit_time = '';
if (!$no_limit and isset($HTTP_SERVER_VARS['HTTP_IF_MODIFIED_SINCE'])) 
{
	$NotErrorFlag = true;
	$NotModifiedSince = strtotime($HTTP_SERVER_VARS['HTTP_IF_MODIFIED_SINCE']);
	if($NotModifiedSince>0)
	{
		$sql_limit_time = "AND pic_time > " . $NotModifiedSince;
        $sql_limit_comment_time = "AND comment_time > " . $NotModifiedSince;
	}
}

$sql = "SELECT pic_id, pic_title, pic_time, pic_desc, pic_username, pic_cat_id, pic_approval, cat_title
	FROM " . ALBUM_TABLE . " 
		LEFT JOIN " . ALBUM_CAT_TABLE . " ON (cat_id = pic_cat_id)
	WHERE pic_approval = $check_sel
	$sql_not_allowed_cat $sql_cat_where	$sql_limit_time
	ORDER BY pic_time DESC
	LIMIT $count";
$picrow = $db->sql_query($sql);
if (!$picrow)
{
    ExitWithHeader("500 Internal Server Error","Failed obtaining list of active pictures");
}
else
{
    $topics = $db->sql_fetchrowset($picrow);
}
$LastPostTime = 0;
if ( sizeof($topics) == 0 )
{
    if(($NotErrorFlag) and (!$see_comments)) ExitWithHeader("304 Not Modified");
}
else
{
    // $topics contains all interesting data
    for ($i = 0; $i < sizeof($topics); $i++)
    {
        $title = htmlspecialchars($topics[$i]['pic_title']);
        $url = $index_url . '?' . 'pic_id=' . $topics[$i]['pic_id'];
        $thumb = $thumb_url . '?' . 'pic_id=' . $topics[$i]['pic_id'];
        $reply = $reply_url. '?' . 'pic_id=' . $topics[$i]['pic_id'];
        $author = htmlspecialchars($topics[$i]['pic_username']);
    	$description = '<a href="' . $url . '"><img src="' . $thumb . '" border="0" vspace="2" hspace="2" alt="" title="" /></a><br />';
    	$description .= $lang['Pic_Desc'] . ': ' . nl2br($topics[$i]['pic_desc']);

    	$pic_time = RSSTimeFormat($topics[$i]['pic_time'], $userdata['user_timezone']);
    	if ($topics[$i]['pic_time'] > $LastPostTime) 
    	{
    		$LastPostTime = $topics[$i]['pic_time'];
    	}
    	$subj = ($topics[$i]['pic_cat_id'] == 0) ? $lang['Users_Personal_Galleries'] . ' ' . $topics[$i]['pic_username'] : $topics[$i]['cat_title'];
        $subj = htmlspecialchars($subj);
        if ($admin_mode)
        {
			$approval_mode = ($topics[$i]['pic_approval'] == 0) ? 'approval' : 'unapproval';
			$approval_link = '<br /><a href="' . $index_site . "album_modcp.$phpEx?mode=$approval_mode&amp;pic_id=" . $topics[$i]['pic_id'] . '">';
			$approval_link .= ($topics[$i]['pic_approval'] == 0) ? '<b>' . $lang['Approve'] . '</b>' : $lang['Unapprove'];
			$approval_link .= '</a>';
        	$description .= $approval_link;
        }
    	$description = htmlspecialchars($description);
        
        $template->assign_block_vars('post_item', array(
			'POST_URL' 			=> $url,
			'FIRST_POST_URL'	=> $url,
			'REPLY_URL' 		=> $reply,
            'TOPIC_TITLE' 		=> $title,
			'AUTHOR0' 			=> $author,
			'AUTHOR' 			=> $author,
			'POST_TIME' 		=> create_date($board_config['default_dateformat'], $topics[$i]['pic_time'], $board_config['board_timezone']).' (GMT ' . $board_config['board_timezone'] . ')',
			'ATOM_TIME'			=> gmdate('Y-m-d\TH:i:s', $topics[$i]['pic_time']) . 'Z',
            'ATOM_TIME_M'		=> gmdate('Y-m-d\TH:i:s', $topics[$i]['pic_time']) . 'Z',
			'POST_SUBJECT' 		=> $post_subject,
			'FORUM_NAME' 		=> $subj,
			'UTF_TIME'			=> $pic_time,
			'POST_TEXT' 		=> $description,
			'USER_SIG' 			=> '',
			'TOPIC_REPLIES' 	=> $post['topic_replies'])
		);
	}
}

// Check Comments
if($see_comments)
{
	$sql = "SELECT c.*, a.pic_id, a.pic_title, pic_username, pic_cat_id 
		FROM " . ALBUM_COMMENT_TABLE . " AS c," . ALBUM_TABLE . " AS a
		WHERE comment_pic_id = pic_id
		$sql_not_allowed_cat $sql_limit_comment_time $sql_cat_where
	ORDER BY comment_time DESC
	LIMIT $count";
	$picrow = $db->sql_query($sql);
	if (!$picrow)
	{
    	ExitWithHeader("500 Internal Server Error","Failed obtaining list of active pictures");
	}
	else
	{
    	$topics = $db->sql_fetchrowset($picrow);
	}

    if (sizeof($topics) == 0)
	{
	    if(($NotErrorFlag) and ($LastPostTime==0)) 
	    {
	    	ExitWithHeader("304 Not Modified");
		}
	}
	else
	{
	    // $topics contains all interesting data
	    for ($i = 0; $i < sizeof($topics); $i++)
	    {
	        $title = htmlspecialchars($topics[$i]['pic_title']);
	        $url = $reply_url . '?' . 'pic_id=' . $topics[$i]['pic_id'] . '#' . $topics[$i]['comment_id'];
	        $thumb = $thumb_url . '?' . 'pic_id=' . $topics[$i]['pic_id'];
	        $reply = $reply_url. '?' . 'pic_id=' . $topics[$i]['pic_id'];
	        $author = htmlspecialchars($topics[$i]['comment_username']);
	    	$description = $lang['Comments'] . ': ' . nl2br($topics[$i]['comment_text']) . '<br />';
	    	$description .= '<a href="' . $url . '"><img src="' . $thumb . '" border="0" vspace="2" hspace="2" alt="" title="" /></a>';
	    	$description = htmlspecialchars($description);
	    	$pic_time = date('D, d M Y H:i:s O', $topics[$i]['comment_time']);
	    	
	    	if( $topics[$i]['comment_time'] > $LastPostTime) 
	    	{
	    		$LastPostTime = $topics[$i]['comment_time'];
	    	}
	    	
	    	$subj = $lang['Comments'];
	        $subj = htmlspecialchars($subj);
	        
	        $template->assign_block_vars('post_item', array(
				'POST_URL' 			=> $url,
				'FIRST_POST_URL' 	=> $index_url . '?' . 'pic_id=' . $topics[$i]['pic_id'],
				'REPLY_URL'			=> $reply,
	            'TOPIC_TITLE' 		=> $title,
				'AUTHOR0' 			=> $author,
				'AUTHOR'	 		=> $author,
				'POST_TIME' 		=> create_date($board_config['default_dateformat'], $topics[$i]['comment_time'], $board_config['board_timezone']).' (GMT ' . $board_config['board_timezone'] . ')',
				'ATOM_TIME'			=> gmdate('Y-m-d\TH:i:s', $topics[$i]['pic_time']) . 'Z',
	            'ATOM_TIME_M'		=> gmdate('Y-m-d\TH:i:s', $topics[$i]['pic_time']) . 'Z',
				'POST_SUBJECT' 		=> $post_subject,
				'FORUM_NAME' 		=> $subj,
				'UTF_TIME'			=> $pic_time,
				'POST_TEXT' 		=> $description,
				'USER_SIG' 			=> '',
				'TOPIC_REPLIES' 	=> $post['topic_replies'])
			);
		}
	}
}

// Check for E-Tag
$MyETag = '"RSS' . gmdate('YmdHis', $LastPostTime) . $verinfo . '"';
$MyGMTtime = gmdate('D, d M Y H:i:s', $LastPostTime) . ' GMT';
if (isset($HTTP_SERVER_VARS['HTTP_IF_NONE_MATCH']) && ($HTTP_SERVER_VARS['HTTP_IF_NONE_MATCH'] == $MyETag)) 
{
	ExitWithHeader("304 Not Modified");
}
if (isset($HTTP_SERVER_VARS['HTTP_IF_MODIFIED_SINCE']) && ($HTTP_SERVER_VARS['HTTP_IF_MODIFIED_SINCE'] == $MyGMTtime)) 
{
	ExitWithHeader("304 Not Modified");
}

//
// BEGIN XML and nocaching headers (copied from page_header.php)
//
if (!empty($HTTP_SERVER_VARS['SERVER_SOFTWARE']) && strstr($HTTP_SERVER_VARS['SERVER_SOFTWARE'], 'Apache/2'))
{
	header ('Cache-Control: no-cache, pre-check=0, post-check=0, max-age=0');
}
else
{
	header ('Cache-Control: private, pre-check=0, post-check=0, max-age=0');
}
header('Last-Modified: ' . $MyGMTtime);
header('Etag: ' . $MyETag);
header('Expires: ' . gmdate('D, d M Y H:i:s', time()) . ' GMT');
header ('Content-Type: text/xml; charset=' . $lang['ENCODING']);

$template->pparse('body');

$gzip_text = ($board_config['gzip_compress']) ? 'GZIP enabled' : 'GZIP disabled';
$mtime = microtime();
$mtime = explode(" ",$mtime);
$mtime = $mtime[1] + $mtime[0];
$endtime = $mtime;
$gentime = round(($endtime - $starttime), 4);

if ($show_time) 
{
	echo '<!-- Page generation time: ' . $gentime . 's ';
    if(SMARTOR_STATS) 
    {
      	$sql_time = round($db->sql_time, 4);
 		$sql_part = round($sql_time / $gentime * 100);
 		$excuted_queries = $db->num_queries;
		$php_part = 100 - $sql_part;
		echo '(PHP: ' . $php_part . '% - SQL: ' . $sql_part . '%) - SQL queries: ' . $excuted_queries;
     }
	if (function_exists('memory_get_usage') && ($mem = @memory_get_usage())) 
	{
		echo ' - Memory Usage: ' . (number_format(($mem / (1024 * 1024)), 3)) . ' Mb ';
	}
    echo  ' - ' . $gzip_text . ' -->';
}
$db->sql_close();

//
// Compress buffered output if required and send to browser
//
if ($do_gzip_compress)
{
	//
	// Borrowed from php.net!
	//
	$gzip_contents = ob_get_contents();
	ob_end_clean();

	$gzip_size = strlen($gzip_contents);
	$gzip_crc = crc32($gzip_contents);

	$gzip_contents = gzcompress($gzip_contents, 9);
	$gzip_contents = substr($gzip_contents, 0, strlen($gzip_contents) - 4);

	echo "\x1f\x8b\x08\x00\x00\x00\x00\x00";
	echo $gzip_contents;
	echo pack('V', $gzip_crc);
	echo pack('V', $gzip_size);
}
exit;

?>