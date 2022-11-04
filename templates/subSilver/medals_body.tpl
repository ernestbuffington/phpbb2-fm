<table width="100%" cellspacing="2" cellpadding="2">
<tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
</tr>
</table>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<th class="thCornerL" width="5%" nowrap="nowrap" colspan="2">&nbsp;{L_MEDAL_NAME}&nbsp;</th>
	<th class="thTop" nowrap="nowrap">&nbsp;{L_USERS_LIST}&nbsp;</th>
	<th class="thCornerR" nowrap="nowrap">&nbsp;{L_MEDAL_MODERATOR}&nbsp;</th>
</tr>
<!-- BEGIN catrow -->
<tr> 
	<td class="catLeft" colspan="2"><span class="cattitle">{catrow.CAT_DESC}</span></td>
	<td class="rowpic" colspan="2" align="right">&nbsp;</td>
</tr>
<!-- BEGIN medals -->
<tr>
	<td class="row1" align="center" valign="middle" nowrap="nowrap">{catrow.medals.MEDAL_IMAGE}</td>
	<td class="row1" width="50%"><span class="forumlink">{catrow.medals.MEDAL_NAME}</span><br />
		<span class="genmed">{catrow.medals.MEDAL_DESCRIPTION}</td>
	<td class="row2" valign="middle"><span class="gensmall">{catrow.medals.USERS_LIST}</span></td>
	<td class="row2" valign="middle" align="center" nowrap="nowrap"><span class="gensmall">{catrow.medals.MEDAL_MOD}
	<!-- BEGIN switch_mod_option -->
	<br /><a href="{catrow.medals.U_MEDAL_CP}" class="gensmall">{L_LINK_TO_CP}</a>
	<!-- END switch_mod_option -->
	</span></td>
</tr>
<!-- END medals -->
<!-- END catrow -->
</table>
<br />
<table width="100%" cellspacing="2" align="center"> 
  <tr> 
	<td align="right" valign="middle" nowrap>{JUMPBOX}</td> 
  </tr> 
</table> 
<br />
<div align="center" class="copyright">Medal System 0.4.6 &copy; 2003, {COPYRIGHT_YEAR} <a href="http://macphpbbmod.sourceforge.net" target="_blank" class="copyright">ycl6</a></div>
