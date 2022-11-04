<?php
	
// $suppressoutput makes only sense here if it's regarding the head and foot output. Not regarding COMPLETE output
$whattolookfor = $HTTP_GET_VARS['whattolookfor'];

include('head.php');
include('head2.php');

function addoutput($output)
{
	global $finaloutput;
	
	$finaloutput .= $output;
}
	
// Example: $array = array_csort($array,'town','age',SORT_DESC,'name',SORT_ASC);
//coded by Ichier2003 
function array_csort()
{  
	$args = func_get_args();
	if (count($args) == 0) // Empty array
	{
		return;
	}
	$marray = array_shift($args);
	if (count($marray) == 0) // Check whether the array that
	{
		return $marray;
	}
	$msortline = "return(array_multisort("; 
	foreach ($args as $arg)
	{ 
		$i++; 
		if (is_string($arg))
		{ 
			foreach ($marray as $row)
			{ 
				$sortarr[$i][] = $row[$arg]; 
	   		}
		}
		else
		{ 
			$sortarr[$i] = $arg; 
		}
		$msortline .= "\$sortarr[" . $i . "],"; 
	}
	$msortline .= "\$marray));";
	eval($msortline); 
	
	return $marray; 
}
	
function map_dirs($path,$level) // path, level to start (start at 0)
{
	global $nodear;

	if(is_dir($path))
	{
		if($contents = opendir($path))
		{
			while (($node = readdir($contents)) !== false)
			{
				switch ($whattolookfor)
				{
					case 2:
						$ok = (($node != '.') && ($node != '..'));
						break;
					default:
						$ok = (($node != '.') && ($node != '..') && (preg_match('/[.](tar|tgz|zip|gz)$/',$node)));
						break;
				}
				
				if ($ok)
				{
					$index = count($nodear);
					$nodear[$index]['level'] = $level;
					
					if (is_dir($path . '/' . $node))
					{
						$nodear[$index]['isdir'] = '+';
					}
					$fullpath = str_replace('.//', '', "$path/$node");
					$nodear[$index]['fullpath'] = $fullpath;
					$nodear[$index]['node'] = $node;
					
					map_dirs("$path/$node", $level + 1); // Next level
				}
			}
		}
	}
}

$dir = '../../uploads/backups';
$nodear = array();
$finaloutput = '';
map_dirs($dir,0);

// $nodear is set here
$nodear = array_csort($nodear, 'level', 'isdir', 'node');

if (count($nodear) > 0)
{
	foreach ($nodear as $node)
	{
		if (!$continued)
		{
			addoutput('You currently have the following backups:<br /><br />');
			$continued = TRUE;
		}
		
		addoutput(str_repeat('&nbsp;&nbsp;', $node['level']), $node['isdir']);
		addoutput('&bull;&nbsp;<a href="' . $node['fullpath'] . '">' . $node['node'] . '</a><br />');
	}
	echo $finaloutput;
}
	
include('foot.php');

?>