
<h1>{PAGE_TITLE}</h1>

<table width="100%" align="center" cellpadding="2" cellspacing="2">
<tr>
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a> -> <a href="{U_RATINGS}" class="nav">{L_RATINGS}</a> -> <a href="{U_ALT_SCREEN}" class="nav">{L_ALT_SCREEN}</a></td>
</tr>
</table>
<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"> 
<tr> 
	<th align="center" class="thCornerL">&nbsp;{L_BIAS}&nbsp;</th> 
	<th align="center" class="thTop">&nbsp;{L_WHO}&nbsp;</th> 
	<th align="center" class="thTop">&nbsp;&nbsp;{L_WHEN}&nbsp;&nbsp;</th> 
	<th align="center" class="thTop">&nbsp;{L_REASON}&nbsp;</th> 
	<th align="center" class="thCornerR">&nbsp;{L_CURRENT}&nbsp;</th> 
</tr> 
<!-- BEGIN bias --> 
<tr> 
	<td class="row1" align="center" valign="middle"><span class="name">{bias.BIAS}</span></td> 
	<td class="row2" align="center" valign="middle"><span class="name">{bias.WHO}</span></td> 
	<td class="row1" align="center" valign="middle"><span class="name">{bias.WHEN}</span></td>
	<td class="row2" valign="middle"><span class="name">{bias.REASON}</span></td> 
	<td class="row3Right" align="center" valign="middle" nowrap="nowrap"><span class="name">{bias.CURRENT}</span></td> 
</tr>
<!-- END bias --> 
<!-- BEGIN nobias --> 
<tr> 
	<td class="row1" colspan="5" height="30" align="center" valign="middle"><span class="gen">{L_NO_BIAS}</span></td> 
</tr> 
<!-- END nobias --> 
</table>
<br />
<table width="100%" cellspacing="2" align="center"> 
<tr> 
	<td align="right" valign="middle" nowrap>{JUMPBOX}</td> 
</tr> 
</table> 
<br />
<div align="center" class="copyright">Rating System 1.1 &copy; 2003, 2005 <a href="http://www.mywebcommunities.com" class="copyright" target="_blank">Gentle Giant</a></div>
