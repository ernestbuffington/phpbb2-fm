<body oncontextmenu="javascript:return false">

<table cellpadding="2" cellspacing="2" align="center">
<tr>
	<td align="center" valign="middle">		
	<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0" id="inetangel" name="activitygame" width="{WIDTH}" height="{HEIGHT}">
		<param name="movie" value="{PATH}{SWFNAME}">
		<param name="quality" value="high">
		<param name="menu" value="false">
	<embed name="activitygame" src="{PATH}{SWFNAME}" width="{WIDTH}" height="{HEIGHT}" quality="high" menu="false" swliveconnect="true" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"></embed>
	</object>
	</td>
	<td width="180" nowrap="nowrap" valign="top" align="center">
	<table align="center" class="forumline" width="100%" cellpadding="4" cellspacing="1">
	<tr> 
		<th class="thHead" nowrap="nowrap"><img src="{T_IMAGE}" alt="" title="" /> {T_HOLDER} <img src="{T_IMAGE}" alt="" title="" /></th>
	</tr>
	<tr>
		<td class="row1"><center><b>{T_HOLDER_1}</b></center><br />
		{T_LINK}<br />{T_LINK_1}<br /><br />
		<center>{T_DATE_1}<br />
		{T_DATE}</td>
	</tr>
	<tr>
		<td class="row2" align="center">{T_SCORE_1}: <b>{T_SCORE}</b></td>
	</tr>				
	<tr>
		<td class="catBottom" align="center" nowrap="nowrap"><img src="{T_IMAGE}" alt="" title="" /> {NAME} <img src="{T_IMAGE}" alt="" title="" /></td>
	</tr>				
	</table>
	<br />

	<table align="center" class="forumline" width="100%" cellpadding="4" cellspacing="1">
	<tr>
		<th class="thHead" colspan="2">{R_TITLE}</th>
	</tr>
	<!-- BEGIN runner -->				
	<tr>
		<td class="row1" width="75%"><b>&bull;</b> {runner.R_U_NAME}</td>
		<td class="row2" align="center">{runner.R_U_SCORE}</td>					
	</tr>
	<!-- END runner -->
	</table>			
	<br />
	</td>
</tr>
</table>
