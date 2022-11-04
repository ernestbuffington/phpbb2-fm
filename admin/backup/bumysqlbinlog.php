<?php
include('head.php');
include('head2.php');

include_once('bumyslqbinlog-config.php');

$number = substr_count($binlogpath,'$');
$alllogfiles = '';

// "bumysqlbinlog.php?daysago=2" will dump all mysql request from 2 days ago until NOW
// Works only with MySQL > 4.1.4
if (isset($HTTP_GET_VARS['daysago']))
	{
	$datefrom = time()-$HTTP_GET_VARS['daysago']*24*60*60;
	$daysago = '--start-datetime='.date('YmdHis',$datefrom);
	$datefromto = date('Y-m-d_H.ia',$datefrom).'_until_'.date('Y-m-d_H.ia');
	}
else
	{
	$daysago = '';
	$datefromto = date('Y-m-d_H.ia');
	}
	
$filename = $domainname.'-'.$dbname.'-mysql_binlog-'.$datefromto.'.sql.zip';

// Calculate all filenames, since jokers "mysql-bin.*" won't work
for ($i=$startlog;$i<=$endlog;$i++)
	{
	$alllogfiles .= ' '.str_replace(str_repeat('$',$number),sprintf("%0".$number."d",$i),$binlogpath);
	}

$command = "mysqlbinlog  --force-read --database=$dbname -h $dbhost -u $dbuname -p".$dbpasswd.$alllogfiles." ".$daysago." | zip > ".dirname(__FILE__).'/'.$filename.($suppressoutput ? ' 2> '.dirname(__FILE__).'/busqlerror.log &' : '');
// if "|zip" doesn't work for you then try "|gzip"
// More mysqlbinlog commands at http://dev.mysql.com/doc/mysql/en/mysqlbinlog.html

$shelloutput = shell_exec($command);

if (!$suppressoutput)
	{
//	echo '<br />'.htmlspecialchars($command);
	echo '<br />'.htmlspecialchars($shelloutput);
	echo '<br />Binlog Dump completed:<br /><a href="'.$filename.'">'.$filename.'</a>';
	include('foot.php');
	}

?>