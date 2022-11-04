<?php
	
if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

//
// Modify phpBB core-data
// phpbb_config data
$config_data = array( 'ina_daily_game_date' => date('Y-m-d'), 'ina_daily_game_id' => 1, 'ina_daily_game_random' => 1, 'ina_force_registration' => 0, 'ina_jackpot_pool' => 0, 'ina_max_gamble' => 100, 'ina_online_list_color' => '#0099CC', 'ina_online_list_text' => 'Game Players', 'ina_use_daily_game' => 1, 'ina_use_shoutbox' => 1 );
while ( list ( $config_name, $config_value ) = each ( $config_data ) )
{
	$sql = "INSERT INTO " . $prefix . "config (`config_name`, `config_value`) VALUES ('" . $config_name . "', '" . $config_value . "')";
	_sql($sql, $errored, $error_ary);
}

?>