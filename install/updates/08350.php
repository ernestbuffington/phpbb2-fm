<?php
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}


//
// Create new Fully Modded core-schema
//
$sql = 'DROP TABLE IF EXISTS ' . $table_prefix . 'config_nav';
_sql($sql, $errored, $error_ary);
$sql = 'CREATE TABLE ' . $table_prefix . 'config_nav (`navlink_id` MEDIUMINT(8) UNSIGNED NOT NULL auto_increment, `img` VARCHAR(100) DEFAULT "" NOT NULL, `alt` VARCHAR(100) DEFAULT "" NOT NULL, `url` VARCHAR(255) DEFAULT "" NOT NULL, `nav_order` MEDIUMINT(8) UNSIGNED DEFAULT "1" NOT NULL, `value` TINYINT(1) DEFAULT "0" NOT NULL, PRIMARY KEY (navlink_id))';
_sql($sql, $errored, $error_ary);


//
// Modify Fully Modded core-data
// phpbb_config_nav data
$id = 1;
$config_nav_data = array( 
	array( Img => 'icon_mini_portal.gif', Alt => 'Portal', Url => 'portal.php', Value => 0 ),
	array( Img => 'icon_mini_forums.gif', Alt => 'Board', Url => 'index.php', Value => 0 ),
	array( Img => 'icon_mini_faq.gif', Alt => 'FAQ', Url => 'faq.php', Value => 1 ),
	array( Img => 'icon_mini_search.gif', Alt => 'Search', Url => 'search.php', Value => 1 ),
	array( Img => 'icon_mini_calendar.gif', Alt => 'Calendar', Url => 'calendar.php', Value => 0 ),
	array( Img => 'icon_mini_imlist.gif', Alt => 'IM List', Url => 'imlist.php', Value => 0 ),
	array( Img => 'icon_mini_album.gif', Alt => 'Album', Url => 'album.php', Value => 0 ),
	array( Img => 'icon_mini_player.gif', Alt => 'mp3 Player', Url => 'player/index.php', Value => 0 ),
	array( Img => 'icon_mini_charts.gif', Alt => 'Charts', Url => 'charts.php?action=list', Value => 0 ),
	array( Img => 'icon_mini_smilies.gif', Alt => 'Smilies', Url => 'smilies.php', Value => 0 ),
	array( Img => 'icon_mini_groups.gif', Alt => 'Groups', Url => 'groupcp.php', Value => 1 ),
	array( Img => 'icon_mini_members.gif', Alt => 'Toplist', Url => 'toplist.php', Value => 0 ),
	array( Img => 'icon_mini_download.gif', Alt => 'Downloads', Url => 'dload.php', Value => 0 ),
	array( Img => 'icon_mini_ratings.gif', Alt => 'Ratings', Url => 'ratings.php', Value => 0 ),
	array( Img => 'icon_mini_statistics.gif', Alt => 'Statistics', Url => 'statistics.php', Value => 0 ),
	array( Img => 'icon_mini_ban.gif', Alt => 'Banlist', Url => 'banlist.php', Value => 0 ),
	array( Img => 'icon_mini_staff.gif', Alt => 'Staff', Url => 'staff.php', Value => 0 ),
	array( Img => 'icon_mini_radio.gif', Alt => 'Radio', Url => '#', Value => 0 ),
	array( Img => 'icon_mini_members.gif', Alt => 'Referrals', Url => 'top_referrals.php', Value => 0 ),
	array( Img => 'icon_mini_games.gif', Alt => 'Games', Url => 'activity.php', Value => 0 ),
	array( Img => 'icon_mini_shop.gif', Alt => 'Shop', Url => 'shop.php', Value => 0 )
);
for ( $row = 0; $row < sizeof($config_nav_data); $row++ )
{
	for ( $column = 0; $column < 1; $column++ )
	{
		$sql = "INSERT INTO " . $table_prefix . "config_nav (`img`, `alt`, `url`, `nav_order`, `value`) VALUES ('" . $config_nav_data[$row]['Img'] . "', '" . $config_nav_data[$row]['Alt'] . "', '" . $config_nav_data[$row]['Url'] . "', '" . $id . "0', '" . $config_nav_data[$row]['Value'] . "')";
		_sql($sql, $errored, $error_ary);
	}
	$id++;
}

?>