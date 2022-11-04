<?php

require('./head.php');

echo <<<HEREDOC
<table align="center" cellpadding="4" cellspacing="1" width="100%">
    <tr>
    	<th class="thHead">Website Backup Suite</th>
    </tr>
    <tr>
      <td class="row1"><span class="genmed">
    	<ul>
HEREDOC;

if (file_exists('htaccess-protectthisfolder.php') && !file_exists('.htaccess') && !file_exists('.htpasswd'))
{
	echo <<<HEREDOC
	<li><a href="htaccess-protectthisfolder.php" class="nav">Protect this folder with a password</a> (Creates a .htaccess file)</li><br />
HEREDOC;
}

if (file_exists('bufiles.php'))
{
	echo <<<HEREDOC
	<li><a href="bufiles.php?whattobackup=list" class="nav">Files &amp; Folders Backup...</a> (Creates a zip file)<br /><span class="gensmall">Basically only needed, if your server creates files you want to backup, e.g. if users can upload something to your website like files or avatars. Run this weekly or so. (You can adjust what to backup and what to exclude)</span></li><br />
HEREDOC;
	
	if (file_exists('bumysqlbinlog.php'))
	{
		echo <<<HEREDOC
		<li><a href="bumysqlbinlog.php?daysago=2" class="nav">MySQL Binlog Backup</a> (Creates a zip file)<br /><span class="gensmall">Backs up all MySQL write commands from today and yesterday. Works only if you have binlogs enabled. See '<u>admin/backup/bumyslqbinlog-config.php</u>' for more details</span></li><br />
HEREDOC;
	}
}

if (file_exists('showzips.php'))
{
	echo <<<HEREDOC
	<li><a href="showzips.php" class="nav">List All Zipped Backups</a> (Shows all *.zip, *.tgz, *.gz files)</li><br />
HEREDOC;
}

echo <<<HEREDOC
      </ul>
    	</span></td>
    </tr>
</table>
HEREDOC;

if ($handle = @fopen('../../uploads/backups/test.txt', 'w'))
{
	@fwrite($handle, $content);
	@fclose($handle);
	unlink('../../uploads/backups/test.txt');
}
else
{
	echo '<font color="#FF0000">Cannot write. Are the folder permissions set to "world writable"? Please set the folder attributes of "/uploads/backups/" to 0777 or 0775</font>';
}

@require 'foot.php';

?>