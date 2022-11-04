<?php
/*-----------------------------------------------------------------------------
                 POST ASSOCIATOR - A phpBB Add-On
  ----------------------------------------------------------------------------
    Please read the file README.TXT for licensing and copyright information.
  ----------------------------------------------------------------------------
    lang_postassoc.php - English Language File
    File Version: 1.0.0
    Begun: Thursday, July 21, 2005
    Last Modified: Thursday, July 21, 2005
    Modified by:
        <ATTENTION ALL POTENTIAL TRANSLATORS!
            You are free to translate this file into other languages for your
            own use and to distribute translated versions according to the
            terms of the license under which it is released. Add your name and
            contact details in this area.>
-----------------------------------------------------------------------------*/
/* This is optional, if you would like a _SHORT_ message output
   along with the copyright message indicating you are the translator
   please add it here. */
// $lang['TRANSLATION'] .= 'Your Name Here';

// The format of this section of the file is ---> 'MESSAGE' => 'text'

$lang += array(
	'PTAS_Explain' => 'This utility will allow you to select all guest posts and topics attributed to a single name and reassociate those to a registered user on your forums. To be reassociated, a guest post must have a name entered at posting. This name should be listed with the posts in topics.<br /><br />Posts made by users that have been deleted are considered the same as guest posts made with the username of the poster.',
	'PTAS_Guest' => 'Guest User',
	'PTAS_User' => 'Registered User',
	'PTAS_Guest_explain' => 'Enter a username listed with posts by unregistered users.',
	'PTAS_User_explain' => 'Enter the username of a current registered user.',
	'PTAS_No_Guest_Name' => 'You did not enter a gust username. Please go back an enter a username appearing with guest posts on your forum.',
	'PTAS_No_Guest_Posts' => 'No guest posts with that guest username could be found.',
	'PTAS_No_Update_Posts' => 'Could not update guest posts to new user status',
	'PTAS_No_Update_Topics' => 'Could not update guest-started topics to new user status',
	'PTAS_No_Update_Posts_Count' => 'Could not update user post counts',
	'PTAS_Done' => 'Posts and topics updated successfully. %d topics and %d posts were changed to the new user.',
	'PTAS_Return' => 'Click %sHere%s to return to the Post Associator'
);


?>