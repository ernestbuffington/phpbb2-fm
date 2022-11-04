<?php
	
$suppressoutput = $HTTP_GET_VARS['suppressoutput'];

if (!$suppressoutput)
{
	echo <<<HEREDOC
	<div class='outer'>
	<div class='inner'>
	<pre>
HEREDOC;
}

?>