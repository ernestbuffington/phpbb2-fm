<?php
/** 
*
* @package phpBB2
* @version $Id: google_sitemap,v 1.0 2005 john Exp $
* @copyright (c) 2005 John Brookes
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/
	
define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

$server_protocol = ( $board_config['cookie_secure'] ) ? 'https://' : 'http://';
$server_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($board_config['server_name']));
$server_port = ( $board_config['server_port'] <> 80 ) ? ':' . trim($board_config['server_port']) : '';
$script_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($board_config['script_path']));
$script_name = ( $script_name == '' ) ? $script_name : '/' . $script_name;

define('FORUM_DOMAIN_ROOT', $server_protocol . $server_name . $server_port . $script_name . '/'); // Full URL with trailing slash!
define('FORUM_URL_PREFIX', 'viewforum.php?f='); // What comes up before the forum ID?
define('FORUM_URL_SUFFIX', ''); // What comes up after the forum ID?
define('THREAD_URL_PREFIX', 'viewtopic.php?t='); // What comes up before the thread ID?
define('THREAD_URL_SUFFIX', ''); // What comes up after the thread ID?

if ($_GET['fid']) 
{ 
	$fid = $_GET['fid']; 
}

// Sitemap File    <sitemapindex xmlns="http://www.google.com/schemas/sitemap/0.84">
// URL Index File  <urlset xmlns="http://www.google.com/schemas/sitemap/0.84">';
if (isset($fid)) 
{
  	echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
  	if ($fid == '65535') 
  	{
    	// Let's first send out the header & homepage
    	echo '<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">'."\n";
    	echo '<url>
    		<loc>' . FORUM_DOMAIN_ROOT . '</loc>
    		<changefreq>daily</changefreq>
    		</url>';
    
    	// Let's send out a URL list of forums
    	$sql = 'SELECT forum_id 
    		FROM ' . $prefix . 'forums 
    		WHERE auth_view = 0 
    			AND auth_read = 0
    			AND forum_id > 0';
    	$result = mysql_query($sql);
    	while ($data = mysql_fetch_assoc($result)) 
    	{
      	echo '<url>
      		<loc>' . FORUM_DOMAIN_ROOT . FORUM_URL_PREFIX . $data['forum_id'] . FORUM_URL_SUFFIX . '</loc>
      		<changefreq>daily</changefreq>
    		</url>';
    	}
    	echo ' </urlset>';
  	} 
  	else 
  	{
    	// Let's check it's not a restricted forum
    	$sql = 'SELECT forum_id 
    		FROM ' . $prefix . 'forums 
    		WHERE auth_view = 0 
    			AND auth_read = 0 
    			AND forum_id = "'.$fid.'"';
    	$result = mysql_query($sql);
    	$data = mysql_fetch_assoc($result);
    
    	if ($data['forum_id'] == $fid) 
    	{
    		echo '<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">'."\n";
      		$sql = 'SELECT t.*, u.username, u.user_id, u2.username AS user2, u2.user_id AS id2, p.post_username, p2.post_username AS post_username2, p2.post_time 
      			FROM ' . $prefix . 'topics t, ' . $prefix . 'users u, ' . $prefix . 'posts p, ' . $prefix . 'posts p2, ' . $prefix . 'users u2 
      			WHERE t.forum_id = '.$fid.' 
      				AND t.topic_poster = u.user_id 
      				AND p.post_id = t.topic_first_post_id 
      				AND p2.post_id = t.topic_last_post_id 
      				AND u2.user_id = p2.poster_id 
      			ORDER BY t.topic_type DESC, t.topic_last_post_id DESC';
      		$result = mysql_query($sql);

      		while ($data = mysql_fetch_assoc($result)) 
      		{
        		echo '<url>
      				<loc>' . FORUM_DOMAIN_ROOT . THREAD_URL_PREFIX . $data['topic_id'] . THREAD_URL_SUFFIX . '</loc>
      				<lastmod>' . date('Y-m-d', $data['post_time']), '</lastmod>
    				</url>';
 			}
     		echo '</urlset>';
		}
  	}
} 
else 
{
	echo '<?xml version="1.0" encoding="UTF-8"?>'."\n";
	echo '<sitemapindex xmlns="http://www.google.com/schemas/sitemap/0.84">'."\n";
    
    // Let's create a link to the main forum index sitemap
  	echo '<sitemap>
      	<loc>' . FORUM_DOMAIN_ROOT . 'forum-65535.xml</loc>
      	<changefreq>monthly</changefreq>
   		</sitemap>';
   	
   		// Let's do a loop here and list all the forums!
    	$sql = 'SELECT forum_id 
    		FROM ' . $prefix . 'forums 
    		WHERE auth_view = 0 
    			AND auth_read = 0
    			AND forum_id > 0';
    	$result = mysql_query($sql);
    	while ($data = mysql_fetch_assoc($result)) 
    	{
    		echo '<sitemap>
    	  		<loc>' . FORUM_DOMAIN_ROOT . 'forum-' . $data['forum_id'] . '.xml</loc>
    	  		<changefreq>daily</changefreq>
   				</sitemap>';
    	}
  		echo "\n" . '</sitemapindex>';
}

?>