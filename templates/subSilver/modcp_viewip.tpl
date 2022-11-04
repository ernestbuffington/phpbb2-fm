<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr> 
	<td class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></td>
</tr>
</table>

<table width="100%" cellpadding="3" cellspacing="1" class="forumline">
<tr> 
	<th class="thHead">{L_IP_INFO}</th>
</tr>
<tr> 
	<td class="catHead"><span class="cattitle">{L_THIS_POST_IP}</span></td>
</tr>
<tr> 
	<td class="row1"><table width="100%" cellspacing="0" cellpadding="0">
	<tr> 
		<td class="gen"> {IP} [ {POSTS} ]</td>
		<td align="right" class="gen">[ <a href="{U_LOOKUP_IP}">{L_LOOKUP_IP}</a> ]&nbsp;</td>
	</tr>
	</table></td>
</tr>
<tr> 
	<td class="catHead"><span class="cattitle">{L_OTHER_USERS}</span></td>
</tr>
<!-- BEGIN userrow -->
<tr> 
	<td class="{userrow.ROW_CLASS}"><table width="100%" cellspacing="0" cellpadding="0">
	<tr> 
		<td class="gen"> <a href="{userrow.U_PROFILE}">{userrow.USERNAME}</a> [ {userrow.POSTS} ]</td>
		<td align="right"><a href="{userrow.U_SEARCHPOSTS}" title="{userrow.L_SEARCH_POSTS}"><img src="{SEARCH_IMG}" alt="{L_SEARCH}" title="{L_SEARCH}" /></a> &nbsp;</td>
	</tr>
	</table></td>
</tr>
<!-- END userrow -->
<tr> 
	<td class="catHead"><span class="cattitle">{L_OTHER_IPS}</span></td>
</tr>
<!-- BEGIN iprow -->
<tr> 
	<td class="{iprow.ROW_CLASS}"><table width="100%" cellspacing="0" cellpadding="0">
	<tr> 
		<td class="gen"> {iprow.IP} [ {iprow.POSTS} ]</td>
		<td align="right" class="gen">[ <a href="{iprow.U_LOOKUP_IP}">{L_LOOKUP_IP}</a> ]&nbsp;</td>
	</tr>
	</table></td>
</tr>
<!-- END iprow -->
</table>
<br />
