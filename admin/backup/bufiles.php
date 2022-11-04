<?php
	
include('head.php');

$configfolder = 'files/';

if ($whattobackup == 'list')
{
	$filelist = array();	
	$handle = @opendir($configfolder);
	while (false !== ($file = readdir($handle)))
	{
		if ($file == "." || $file == ".." || $file == "index.html")
		{
			continue;
		}
	   	if	(is_file($configfolder."//".$file))
		{
   			$filelist[] = $file;
		}
  	}
	@closedir($handle);
	
	if (!$suppressoutput)
	{
		echo "<ul>";
		foreach ($filelist as $file)
		{
			@require($configfolder.$file);
		  	echo '<li><a href="bufiles.php?whattobackup='.basename($file).'">'.$title.'</a></li>';
		}
		echo "</ul>";
		include('foot.php');
	}
}
else
{
	include('head2.php');
	
	if (!$whattobackup) // no .php name given -> backup as per 'all.php'
	{
		$whattobackup = 'all.php';
	}
	$filenameadd = $domainname.'-'.$dbname.'-files-and-folders-backup-'.basename($whattobackup);
	$whattobackup = $configfolder.$whattobackup;

	@require $whattobackup;
	$filename = $filenameadd.'-'.date('Y-m-d_H.ia').'.tgz';
	
	$command = "tar czf $filename $backupparameters --totals --preserve-order --preserve-permissions --ignore-failed-read --verbose ".($suppressoutput ? ' > '.dirname(__FILE__).'/bualllog.log 2> '.dirname(__FILE__).'/busqlerror.log &' : '');
	
	$shelloutput = shell_exec($command);
	if (!$suppressoutput)
	{
		echo '<br />'.htmlspecialchars($shelloutput);
		echo '<br />Full backup completed:<br /><a href="'.$filename.'">'.$filename.'</a>';
		include('foot.php');
	}
}

?>