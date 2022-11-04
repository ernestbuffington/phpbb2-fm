<table width="100%" cellspacing="0" cellpadding="10" align="center">
<tr>
	<td><img src="../templates/subSilver/images/logo_digests.gif" vspace="1" /></td>
	<td align="center" width="100%" valign="middle" colspan="2"><span class="maintitle">{L_SITENAME}<br />{L_HTML_TITLE}<br /></span></td>
</tr>
</table>

<table>
<tr>
	<td align="left" colspan="3"><span class="maintitle">{L_INFORMATION}</span></td>
</tr>

	<tr>
		<td span class="gen">{L_DATABASE}</span></td>
		<td span class="gen">&nbsp;::&nbsp;</span></td>
		<td span class="gen"><b>{SQL_LAYER}</b></span></td>
	</tr>

	<tr>
		<td span class="gen">{L_PHPBB_VER}</span></td>
		<td span class="gen">&nbsp;::&nbsp;</span></td>
		<td span class="gen"><b>2{PHPBB_VER}</b></span></td>
	</tr>

	<tr>
		<td span class="gen">{L_DIGEST_MOD_VER}</span></td>
		<td span class="gen">&nbsp;::&nbsp;</span></td>
		<td span class="gen"><b>{DIGEST_MOD_VER}</b></span></td>
	</tr>

	<tr>
		<td span class="gen">{L_URL}</span></td>
		<td span class="gen">&nbsp;::&nbsp;</span></td>
		<td span class="gen"><b>{SITE_URL}</b></span></td>
	</tr>

	<tr>
		<td span class="gen">{L_SERVER}</span></td>
		<td span class="gen">&nbsp;::&nbsp;</span></td>
		<td span class="gen"><b>{SERVER_TIME}</b></span></td>
	</tr>

</table>

<br />

<table>

<b>

<tr>
	<td span class="maintitle"><b>{DISABLE_MESSAGE}</b></span></td>
</tr>

<!-- BEGIN start_run -->
<tr>
	<td span class="gen"><b>{L_START}</b></span></td>
</tr>

<tr>
	<td span class="gen">{L_PARA_1}</span></td>
</tr>

<tr>
	<td span class="gen">-------------------------------------------------------------</span></td>
</tr>
<!-- END start_run -->

<!-- BEGIN activity_details -->
<tr>
	<td span class="gensmall">{activity_details.USERNAME}{activity_details.ACTIVITY_MESSAGE}</span></td>
</tr>
<!-- END activity_details -->

<tr><td>&nbsp;</td></tr>

<!-- BEGIN user_details -->
<tr>
	<td span class="gensmall">{user_details.USERNAME}{user_details.LAST}{user_details.LAST_DIGEST_DATE}{L_WE}{user_details.OPTION}{L_PROCESS}{user_details.DIGEST_TYPE}{L_DIGEST}</span></td>
<!-- END user_details -->

<tr>
	<td span class="gen">-------------------------------------------------------------</span></td>
</tr>

<tr>
	<td span class="gen"><b>{L_TOTAL}{SUBSCRIPTIONS}{L_MARKED}</b></span></td>
</tr>

<tr>
	<td span class="gen">-------------------------------------------------------------</span></td>
</tr>

<!-- BEGIN gather -->
<tr>
	<td span class="gen"><b>{gather.L_GATHER}</b></span></td>
</tr>
<!-- END gather -->

<!-- BEGIN forum_details -->
<tr>
	<td span class="gen">{forum_details.FORUM_NUMBER}&nbsp;-&nbsp;{forum_details.FORUM_NAME}</span></td>
</tr>
<!-- END forum_details -->

<!-- BEGIN forums -->
<tr>
	<td span class="gen">{forums.L_TOTAL}{forums.FORUMS}{forums.L_FOUND}</span></td>
</tr>

<tr>
	<td span class="gen">-------------------------------------------------------------</span></td>
</tr>

<tr>
	<td span class="gen"><b>{forums.L_PROCESS_MARKED}</b></span></td>
</tr>

<tr>
	<td span class="gen">-------------------------------------------------------------</span></td>
</tr>
<!-- END forums -->
</table>

<!-- BEGIN user_data -->
<table>
	<tr>
		<td span class="gen">{L_USERNAME}</span></td>
		<td span class="gen">&nbsp;::&nbsp;</span></td>
		<td span class="gen"><b>{user_data.USERNAME}</b></span></td>
	</tr>
	
	<tr>
		<td span class="gen">{L_MINE}</span></td>
		<td span class="gen">&nbsp;::&nbsp;</span></td>
		<td span class="gen"><b>{user_data.MINE}</b></span></td>
	</tr>
	
	<tr>
		<td span class="gen">{L_EMPTY}</span></td>
		<td span class="gen">&nbsp;::&nbsp;</span></td>
		<td span class="gen"><b>{user_data.EMPTY}</b></span></td>
	</tr>
	
	<tr>
		<td span class="gen">{L_NEW}</span></td>
		<td span class="gen">&nbsp;::&nbsp;</span></td>
		<td span class="gen"><b>{user_data.NEW}</b></span></td>
	</tr>
	
	<tr>
		<td span class="gen">{L_FORMAT}</span></td>
		<td span class="gen">&nbsp;::&nbsp;</span></td>
		<td span class="gen"><b>{user_data.FORMAT}</b></span></td>
	</tr>
</table>

<table>
<tr>
	<td span class="gen">{user_data.LAST_DIGEST}</span></td>
</tr>

<tr>
	<td span class="gen">{L_PERMISSIONS}<b>{user_data.AUTH_FORUMS}</b></span></td>
</tr>

<tr>
	<td span class="gen">{L_OPTED}<b>{user_data.LIST_FORUMS}</b></span></td>
</tr>


<tr>
	<td span class="gen">{L_RECEIVE}<b>{user_data.QUERY_FORUMS}</b></span></td>
</tr>

<tr>
	<td span class="gen">{user_data.SHOW_MINE}</span></td>
</tr>

<tr>
	<td span class="gen">{L_BUILDING}{user_data.USERNAME}{L_BODY}{user_data.HTML}&nbsp;.&nbsp;.&nbsp;. </span></td>
</tr>

<tr>
	<td span class="gen">{L_DIGEST_WITH}{user_data.TOTAL_TOPICS}{L_MESSAGES}{user_data.USERNAME}</span></td>
</tr>

<tr>
	<td span class="gen">{user_data.SENT_MESSAGE}</span></td>
</tr>

<tr>
	<td span class="gen">{L_LAST_STEP}</span></td>
</tr>

<tr>
	<td span class="gen">{L_SUCCESS}</span></td>
</tr>

<tr>
	<td span class="gen">-------------------------------------------------------------</span></td>
</tr>
</table>

<!-- END user_data -->

<table>
<tr>
	<td span class="gen">{L_COMPLETE}</span></td>
</tr>

<tr>
	<td span class="gen">{L_EXITING}</span></td>
</tr>

<tr><td>&nbsp;</td></tr>
</table>