<?php
/** 
*
* @package lang_english
* @version $Id: lang_toplist.php,v 1.35.2.9 2003/06/10 00:31:19 psotfx Exp $
* @copyright (c) 2001 The phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

// 
// Main  
// 
$lang['Toplist_top10'] = 'Toplist Top 10';
$lang['Rank'] = 'Rank'; 
$lang['Site'] = 'Site'; 
$lang['Img_dis'] = 'Img Displayed'; 
$lang['Name'] = 'Name'; 
$lang['SURL'] = 'Website URL'; 
$lang['BURL'] = 'Banner URL'; 
$lang['Cb'] = 'Select your button/banner'; 
$lang['Easi'] = 'Edit/Check Website Information'; 
$lang['As'] = 'Add Website'; 
$lang['Hits_in'] = 'Hits In';
$lang['Hits_out'] = 'Hits Out';
$lang['Image_displays'] = 'Img Hits';
$lang['Plain'] = 'Plain';
$lang['Include_rank'] = 'Including your rank';
$lang['Resend_HTML'] = 'E-mail me the HTML code again';
$lang['Newmem'] = 'You don\'t have a website in the %s TopList yet.'; 
$lang['Notmem'] = 'Add your website to the TopList? You must %sregister%s first.'; 
$lang['Del_toplist'] = 'Are you sure you want to delete %s from the TopList?'; 
$lang['Site_exists'] = 'This site already exists in the TopList.';
$lang['Toplist_no_sites'] = 'There aren\'t any sites in the %s Toplist yet.';
$lang['IP_Address_toplist'] = 'IP Address: %s';

$lang['Toplist_no_sites_add'] = 'Click %sHere%s to add one';
$lang['Click_return_toplist'] = 'Click %sHere%s to return to the Toplist';   
$lang['Site_added_success'] = 'Site Added Successfully.';
$lang['Site_update_success'] = 'Site Updated Successfully.';
$lang['Site_delete_success'] = '%s Deleted Successfully.';
$lang['Site_delete_fail'] = 'Could not delete site from the toplist as it does not exist.';
$lang['Add_HTML'] = 'You need to place this HTML code on your website for the Toplist';
$lang['Image_wrong_size'] = 'Your image exceeds this sites image dimensions, you image must be between';


// 
// Admin
// 
$lang['Admin_Toplist_Imge_Dis'] = 'Number of banner/buttons to show'; 
$lang['Admin_Toplist_Imge_Dis_explain'] = 'Set to -1 to display all banners.'; 
$lang['Admin_Button_Config'] = 'Buttons';
$lang['Admin_Button_Explain'] = 'There is space reserved for two buttons, one on the left bottom and one on the right bottom of the main toplist page.'; 
$lang['Admin_Button_1'] = 'Left button image URL'; 
$lang['Admin_Button_1_l'] = 'Left button link URL'; 
$lang['Admin_Button_2'] = 'Right button image URL'; 
$lang['Admin_Button_2_l'] = 'Right button link URL'; 
$lang['Admin_Disable_hits'] = 'Hits'; 
$lang['Admin_Hits_in'] = 'Disable inhits view'; 
$lang['Admin_Hits_out'] = 'Disable outhits view'; 
$lang['Admin_Hits_img'] = 'Disable imagehits view'; 
$lang['Admin_Dimensions_expl'] = 'Image dimensions';
$lang['Admin_Dimensions_expl_multiple'] = 'Select one or multiple dimensions to delete them';
$lang['Admin_Dimensions_expl_heigth'] = 'Add new dimension height';
$lang['Admin_Dimensions_expl_width'] = 'Add new dimension width';
$lang['Admin_Specify_in_prune_interval'] = 'Specify in-hits reset interval';
$lang['Admin_Specify_out_prune_interval'] = 'Specify out-hits reset interval';
$lang['Admin_Specify_img_prune_interval'] = 'Specify image-displays reset interval';
$lang['Admin_Specify_in_anti_flood_interval'] = 'Specify in-hits antiflood interval';
$lang['Admin_Specify_out_anti_flood_interval'] = 'Specify out-hits antiflood interval';
$lang['Admin_Specify_img_anti_flood_interval'] = 'Specify image-displays antiflood interval';
$lang['Admin_Intervals'] = 'Intervals';
$lang['Admin_Hin_activation'] = 'Only display hit sites';
$lang['Admin_Hin_activation_explain'] = 'Determines whether sites need at least one inhit to be displayed in the toplist.';
$lang['Admin_Count_hits_title'] = 'Site rankings';
$lang['Admin_Count_ex_hits'] = 'The three checkboxes below enable and disable ranking values, e.g. a site has 112 inhits, 45 outhits and 495 imagesdisplays; in the ranking settings only in and out hits are enabled, so the 495 imagesdisplays will be displayed as 0.';
$lang['Admin_Count_hits_in'] = 'Enable ranking on inhits';
$lang['Admin_Count_hits_img'] = 'Enable ranking on imagedisplays';
$lang['Admin_Count_hits_out'] = 'Enable ranking on outhits';
$lang['Admin_Count_ioi_oops'] = 'You need to enable inhits, outhits or imagedisplays to be ranked.';

?>