<?php
/*************************************************************************** 
* 
* This program is free software; you can redistribute it and/or modify 
* it under the terms of the GNU General Public License as published by 
* the Free Software Foundation; either version 2 of the License, or 
* (at your option) any later version. 
* 
***************************************************************************/ 	 

if ( !empty($setmodules) )
{
	$filename = basename(__FILE__);
	$module['Forums']['Topics_anywhere'] = $filename;
	return;
}
?><html>
<head>
</head>
<body bgcolor="#FFFFFF" text="#000000">
<script language="javascript">
<!--
document.location.href="../topics_anywhere.php"
//-->
</script>
</body>
</html>