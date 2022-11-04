<?php
/** 
*
* @package admin
* @version $Id: admin_kb_resync.php,v 1.0 10/19/2005 15:25 mj Exp $
 * @copyright (c) 2005 MJ, Fully Modded phpBB
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['KB_title']['Sync_attachments'] = $file;
	return;
}

//
// Let's set the root dir for phpBB
//
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

//
// Resync kb articles
//
// Get total categories
$sql = "SELECT *
	 FROM " . KB_CATEGORIES_TABLE;
if ( !($result = $db->sql_query($sql)) )
{
 	message_die(GENERAL_ERROR, "Could not query knowledge base configuration information", "", __LINE__, __FILE__, $sql);
}

$total_cats = 0;
while( $row = $db->sql_fetchrow($result) )
{
	$catrow[] = $row;
	$total_cats++;
}
$db->sql_freeresult($result);

for($i = 0; $i < $total_cats; $i++)
{	
	// Count articles in each cat
	$sql = "SELECT COUNT(article_id) AS total
		FROM " . KB_ARTICLES_TABLE . " 
		WHERE article_category_id = " . $catrow[$i]['category_id'] . "
			AND approved = 1";
	if ( !($result = $db->sql_query($sql)) )
	{
	 	message_die(GENERAL_ERROR, "Could not query knowledge base configuration information", "", __LINE__, __FILE__, $sql);
	}
	$articles = $db->sql_fetchrow($result); 

	// Update each cat with correct count
	$sql = "UPDATE " . KB_CATEGORIES_TABLE . " 
		SET number_articles = " . $articles['total'] . "
		WHERE category_id = " . $catrow[$i]['category_id'];
	if ( !($result = $db->sql_query($sql)) )
	{
	 	message_die(GENERAL_ERROR, "Could not query knowledge base configuration information", "", __LINE__, __FILE__, $sql);
	}
}

$message = $lang['KB_resync_success'] . "<br /><br />" . sprintf($lang['Click_return_config'], "<a href=\"" . append_sid("admin_kb_config.$phpEx?mode=config") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

message_die(GENERAL_MESSAGE, $message);

?>