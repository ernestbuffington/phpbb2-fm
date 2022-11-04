<?php
/** 
*
* @package phpBB2
* @version $Id: lang_banner.php,v 1.2.0 2003/12/10 15:57:44 niels Exp $
* @copyright (c) 2001 Niels Chr. Rød
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*/
	
// this is the text shown in admin panel, depending on your template layout,
// you may change the text, so this reflects the placement in the templates
// these are only examples, you should define exactly the number of banner placements you are using

$lang['Banner_spot']['0'] = "Top [ Overall ]"; // used for {BANNER_0_IMG} tag in the template files
$lang['Banner_spot']['1'] = "Top [ 1 ]"; // used for {BANNER_1_IMG} tag in the template files
$lang['Banner_spot']['2'] = "Top [ 2 ]"; // used for {BANNER_2_IMG} tag in the template files
$lang['Banner_spot']['3'] = "Top [ 3 ]"; // used for {BANNER_3_IMG} tag in the template files
$lang['Banner_spot']['4'] = "Top [ 4 ]"; // used for {BANNER_4_IMG} tag in the template files
$lang['Banner_spot']['5'] = "Top [ 5 ]"; // used for {BANNER_5_IMG} tag in the template files
$lang['Banner_spot']['6'] = "Top [ 6 ]"; // used for {BANNER_6_IMG} tag in the template files
$lang['Banner_spot']['7'] = "Bottom Row 1 [ Left ]"; // used for {BANNER_7_IMG} tag in the template files
$lang['Banner_spot']['8'] = "Bottom Row 1 [ Center ]"; // used for {BANNER_8_IMG} tag in the template files
$lang['Banner_spot']['9'] = "Bottom Row 1 [ Right ]"; // used for {BANNER_9_IMG} tag in the template files
$lang['Banner_spot']['10'] = "Bottom Row 2 [ Left ]"; // used for {BANNER_10_IMG} tag in the template files
$lang['Banner_spot']['11'] = "Bottom Row 2 [ Center ]"; // used for {BANNER_11_IMG} tag in the template files
$lang['Banner_spot']['12'] = "Bottom Row 2 [ Right ]"; // used for {BANNER_12_IMG} tag in the template files
$lang['Banner_spot']['13'] = "Top [ Forum View ]"; // used for {BANNER_13_IMG} tag in the template files
$lang['Banner_spot']['14'] = "Top [ Topic View ]"; // used for {BANNER_14_IMG} tag in the template files
$lang['Banner_spot']['15'] = "Bottom [ Topic View ]"; // used for {BANNER_15_IMG} tag in the template files

//
// please do not modify the text below (except if you are translating)
//
$lang['Banner_text'] = 'From here you may modify the banners used on the site, banners can be defined on a time based rule.';
$lang['Banner_add_text'] = 'Here you may add/edit a selected banner.';
$lang['Edit_banner'] = 'Edit banner';

$lang['Banner_example'] = 'Example';
$lang['Banner_example_explain'] = 'This is how the banner will be displayed';
$lang['Banner_type_text'] = 'Type';
$lang['Banner_type_explain'] = 'Select the type of banner';
// Pre-defined types
$lang['Banner_type'][0] = 'Image link';
$lang['Banner_type'][2] = 'Text link';
$lang['Banner_type'][4] = 'Custom HTML code';
$lang['Banner_type'][6] = 'Flash file';

$lang['Banner_name'] = 'Image';
$lang['Banner_name_explain'] = 'Must be relative to phpbb2 path or a complete URL';
$lang['Banner_size'] = "Image Size";
$lang['Banner_size_explain'] = "If values are left empty, the image will default to its' pixel size (Height x Width in pixels)";

$lang['Banner_activated'] = 'Activated';
$lang['Banner_activate'] = 'Activate Banner';
$lang['Banner_comment'] = 'Comment';
$lang['Banner_description'] = 'Image Description';
$lang['Banner_description_explain'] = 'This text is shown onmouseover of the banner image';
$lang['Banner_url'] = 'Redirect URL';
$lang['Banner_url_explain'] = 'The URL of the site to redirect to, when the banner is clicked';
$lang['Banner_owner'] = 'Banner Moderator';
$lang['Banner_owner_explain'] = 'This user may manage the banner';
$lang['Banner_placement'] = 'Banner Placement';
$lang['Banner_clicks'] = 'Clicks';
$lang['Banner_clicks_explain'] = 'Click counting is only enabled if type is an Image or Text link';
$lang['Banner_view'] = 'Views';
$lang['Banner_weigth'] = 'Banner Weight';
$lang['Banner_weigth_explain'] = 'How often this banner is to be shown, relative to other active banners at the current time (1-99)';
$lang['Show_to_users'] ='Show to users';
$lang['Show_to_users_explain'] = 'Select which type of users should see the banner';
$lang['Show_to_users_select'] = 'User must be %s<br />to %s'; //%s are supstituded with dropdown selections
$lang['Banner_level']['-1'] = 'Guest';
$lang['Banner_level']['0'] = 'Registered';
$lang['Banner_level']['1'] = 'Moderator';
$lang['Banner_level']['2'] = 'Super Moderator';
$lang['Banner_level']['3'] = 'Administrator';
$lang['Banner_level_type']['0'] = 'equal';
$lang['Banner_level_type']['1'] = 'less or equal';
$lang['Banner_level_type']['2'] = 'greater or equal';
$lang['Banner_level_type']['3'] = 'not';

$lang['Time_interval'] = 'Time Interval';
$lang['Time_interval_explain'] = 'Only apply either a date, a day of week or/and a time';
$lang['Start'] = 'Start';
$lang['End'] = 'End';
$lang['Year'] = 'Year';
$lang['Month'] = 'Month';
$lang['Weekday'] = 'Weekday';
$lang['Hour'] = 'Hour';
$lang['Min'] = 'Min';
$lang['Time_type'] = 'Time Type';
$lang['Time_type_explain'] = 'Select if the information is a time interval or a date interval (You may still apply a time interval, if you select a date based rule)';
$lang['Not_specify'] = 'Not Specified';
$lang['No_time'] = 'No time';
$lang['By_time'] = 'By time';
$lang['By_week'] = 'By day of week';
$lang['By_date'] = 'By date';

// messages
$lang['Missing_banner_id'] = 'The banner id is missing';
$lang['Missing_banner_owner'] = 'You must select a banner owner';
$lang['Missing_banner_name'] = 'You must select a banner name';
$lang['Missing_time'] = 'When you define a banner as time based, you must provide the time interval';
$lang['Missing_date'] = 'When you define a banner by date, you must at least provide a date and a time interval';
$lang['Missing_week'] = 'When you define a banner by week day, you must at least provide a day of week and a time interval';

$lang['Banner_removed'] = 'Banner Deleted Successfully.';
$lang['Banner_updated'] = 'Banner Updated Successfully.';
$lang['Banner_added'] = 'Banner Added Successfully.';
$lang['Click_return_banneradmin'] = 'Click %sHere%s to return to Banner Settings';

$lang['No_redirect_error'] = 'If the page does not show shortly, please click <b><a href="%s">Here<a></b> to go to the requested URL';
$lang['Left_via_banner'] = 'Left via banner';

$lang['Banner_filter'] = 'Filter';
$lang['Banner_filter_explain'] = 'Hide this banner after the user have clicked on it';
$lang['Banner_filter_time'] = 'Inactive click time';
$lang['Banner_filter_time_explain'] = 'Number of seconds the banner will be inactive after a user clicks on it, if banner filter is enabled the banner will not show for this period';

?>