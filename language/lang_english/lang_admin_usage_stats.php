<?php
/** 
*
* @package lang_english
* @version $Id: lang_usage_stats.php,v 1.1.0 2003/06/10 00:31:19 calennert Exp $
* @copyright (c) 2003 Chris Lennert
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

$lang['BBUS_Mod_Title'] = 'Board Usage Statistics';
$lang['BBUS_Misc'] = 'Miscellaneous';

$lang['BBUS_ColHeader_PostRate'] = 'Post Rate';
$lang['BBUS_ColHeader_PctUTP'] = '%UTP';
$lang['BBUS_ColHeader_PctUTUP'] = '%UTUP';
$lang['BBUS_ColHeader_NewTopics'] = 'New Topics';
$lang['BBUS_ColHeader_TopicRate'] = 'Topic Rate';
$lang['BBUS_ColHeader_Topics_Watched'] = 'Watched';
$lang['BBUS_ColHeader_Header'] = 'Header';
$lang['BBUS_ColHeader_Description'] = 'Description';

$lang['BBUS_ColHeader_Posts_Explain'] = 'Total number of posts.';
$lang['BBUS_ColHeader_PostRate_Explain'] = 'Average number of posts per day.';
$lang['BBUS_ColHeader_PctUTP_Explain'] = 'Percentage of user\'s total posts.';
$lang['BBUS_ColHeader_PctUTUP_Explain'] = 'Percentage of user\'s total unpruned posts.';
$lang['BBUS_ColHeader_NewTopics_Explain'] = 'Total number of new topics initiated by user.';
$lang['BBUS_ColHeader_TopicRate_Explain'] = 'Average number of new topics initiated per day.';
$lang['BBUS_ColHeader_Topics_Watched_Explain'] = 'Total number of topics watched.';

$lang['BBUS_Col_Descriptions_Caption'] = 'Column Descriptions';

$lang['BBUS_Msg_NoPosts'] = 'User has not posted to any forums.';
$lang['BBUS_Unpruned_Posts'] = 'Total posts pruned';
$lang['BBUS_Scale_By'] = 'Scale By:';

// Admin Configuration page
$lang['BBUS_Settings_Explain'] = 'These settings allow you to configure which board users may view the board usage statistics on a user\'s Profile page and to configure various options related to the data presented on that page. It is recommended that you restrict the viewing of statistics, particularly for large boards, to avoid performance degradation issues arising from the stats being generated for many users at the same time.';

$lang['BBUS_Enable'] = 'Display board usage statistics in profiles';

$lang['BBUS_Setting_ViewLevel'] = 'View Level';
$lang['BBUS_Setting_ViewLevel_Caption'] = 'Use the check boxes below to set the view level for viewing of Usage Stats by particular classes of users. <b>Note</b>: Textbox is read-only.';

$lang['BBUS_ViewLevel_Anonymous_Caption'] = 'Anonymous';
$lang['BBUS_ViewLevel_Anonymous_Explain'] = 'Allow anonymous users to view stats.';
$lang['BBUS_ViewLevel_Self_Caption'] = 'Self';
$lang['BBUS_ViewLevel_Self_Explain'] = 'Allow each user to view his/her own stats.';
$lang['BBUS_ViewLevel_Users_Caption'] = 'Users';
$lang['BBUS_ViewLevel_Users_Explain'] = 'Allow any users to view other users\' stats.';
$lang['BBUS_ViewLevel_Moderators_Caption'] = 'Moderators';
$lang['BBUS_ViewLevel_Moderators_Explain'] = 'Allow moderators to view stats.';
$lang['BBUS_ViewLevel_Admins_Caption'] = 'Admins';
$lang['BBUS_ViewLevel_Admins_Explain'] = 'Allow administrators to view stats. (Recommended)';
$lang['BBUS_ViewLevel_SpecialGrp_Caption'] = 'Special Group';
$lang['BBUS_ViewLevel_SpecialGrp_Explain'] = 'Allow a designated special user group to view stats.';

$lang['BBUS_Setting_ViewOptions_Caption'] = 'View Options';
$lang['BBUS_Setting_ViewOptions_Explain'] = 'Like View Level, the View Options setting enables and disables various features of the Board Usage Statistics. Use the check boxes below to enable or disable these features.<br /><b>Note</b>: Textbox is read-only.';
$lang['BBUS_ViewOption_Show_All_Forums_Caption'] = 'Show All Forums, regardless of user\'s post count';
$lang['BBUS_ViewOption_PCTUTUP_Column_Visible_Caption'] = 'Display %UTUP Column in Stats Table';
$lang['BBUS_ViewOption_Misc_Section_Visible_Caption'] = 'Display Miscellaneous Info Section';
$lang['BBUS_ViewOption_Misc_TotPrunedPosts_Visible_Caption'] = 'Display "Total Unpruned Posts" in Misc Section';
$lang['BBUS_ViewOption_Viewer_Scalable_PR_Caption'] = 'Viewer Scalable Post Rate';
$lang['BBUS_ViewOption_Viewer_Scalable_TR_Caption'] = 'Viewer Scalable Topic Rate';

$lang['BBUS_Settings_Default_Post_Rate_Scaling_Caption'] = 'Default Post Rate Scaling Factor';
$lang['BBUS_Settings_Default_Post_Rate_Scaling_Explain'] = 'Sets the default scaling factor applied to values in the Post Rate stats column.';

$lang['BBUS_Settings_Default_Topic_Rate_Scaling_Caption'] = 'Default Topic Rate Scaling Factor';
$lang['BBUS_Settings_Default_Topic_Rate_Scaling_Explain'] = 'Sets the default scaling factor applied to values in the Topic Rate stats column.';

?>