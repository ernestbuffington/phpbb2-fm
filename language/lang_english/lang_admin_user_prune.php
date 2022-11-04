<?php
/***************************************************************************
*                            $RCSfile: lang_admin_auto_delete_users.php,v $
*                            -------------------
*   begin                : May 22, 2003
*   copyright            : (C) 2003 Nivisec.com
*   email                : support@nivisec.com
*
*   $Id: lang_admin_auto_delete_users.php,v 1.5 2003/07/03 15:13:16 nivisec Exp $
*
*
***************************************************************************/

/***************************************************************************
*
*   This program is free software; you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation; either version 2 of the License, or
*   (at your option) any later version.
*
***************************************************************************/

/* General */
$lang['Auto_Days'] = 'Auto Delete After Specified Days';

$lang['Fake_Delete'] = 'Fake Deletion is enabled.  Users are not actually deleted, but a running total is still kept as if they had been.';
$lang['Debug_Enabled'] = 'Debug is enabled.  You may see lots of extra information printed out.';

/* Optional Emailer */
$lang['Deletion_of_Inactive'] = 'Users who have not actived their accounts in %d days will be automatically removed from the system.';
$lang['Deletion_of_Non_Visiting'] = 'Users who fail to visit and log into their accounts after %d days will be automatically removed from the system.';
$lang['Deletion_of_Non_Posting'] = 'Users who fail to make a post after %d days will be automatically removed from the system.';

/* Table Column -> Text */
$lang['admin_auto_delete_inactive'] = 'Auto Delete Inactive Users';
$lang['admin_auto_delete_non_visit'] = 'Auto Delete Non-Visiting Users';
$lang['admin_auto_delete_no_post'] = 'Auto Delete Non-Posting Users';

$lang['DESC_admin_auto_delete_inactive'] = 'This type of user has created an account but never activated it via the activation e-mail.';
$lang['DESC_admin_auto_delete_non_visit'] = 'This type of user has created an account but has not visited in a long time.';
$lang['DESC_admin_auto_delete_no_post'] = 'This type of user registered and activated their account, but has never posted.';

$lang['admin_auto_delete_total'] = 'Total Users Auto Deleted';
$lang['DESC_admin_auto_delete_total'] = 'This is the running total of users that have been Auto Deleted.  It is auto-updated.';
$lang['admin_auto_delete_minutes'] = 'Try To Auto Delete Every Specified Minutes';
$lang['DESC_admin_auto_delete_minutes'] = 'This specifies how many minutes between auto delete searches.  A low value will cause a lot of extra queries to happen each page load.';

/* Errors */
$lang['Config_Table_Error'] = 'Error querying Configuration Table.';
$lang['Error_Auto_Delete'] = 'Error doing user auto-delete';

?>