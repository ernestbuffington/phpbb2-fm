<?php
// Edit this if it includes something it shouldn't or if it excludes something it shouldn't
$title = 'Backup all files [except zipped files, except /cache/, folders, except the standard avatar gallery folder ]';
$backupparameters = "../../../* --exclude '*.tgz' --exclude '*.zip' --exclude '*.gz' --exclude 'images/avatars/*' --exclude 'cache/*'";
?>