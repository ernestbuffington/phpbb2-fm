{BAN_MENU}
{MOD_CP_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_BAN_REASONS}</h1>

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline">
<tr>
	<th class="thHead">{L_BAN_REASONS}</th>
</tr>
<tr>
	<td class="row1" align="center">
	<!-- BEGIN ban_username -->
	<p align="left">
	<span class="gen"><b>{ban_username.L_USERNAME}:</b> {ban_username.USERNAME}</span>
	</p>
	<!-- END ban_username -->
	<!-- BEGIN ban_ip -->
	<p align="left">
	<span class="gen"><b>{ban_ip.L_IP}:</b> {ban_ip.IP}</span>
	</p>
	<!-- END ban_ip -->
	<!-- BEGIN ban_email -->
	<p align="left">
	<span class="gen"><b>{ban_email.L_EMAIL}:</b> {ban_email.EMAIL}</span>
	</p>
	<!-- END ban_email -->
	<p align="left" style="margin: 0px;">
	<span class="gen"><b>{L_PRIVATE_REASON}:</b></span>
	<p align="left" style="margin: 0px 0px 0px 10px;">
	<span class="gen">{PRIVATE_REASON}</span>
	</p>
	</p>
	<p align="left" style="margin: 0px;">
	<span class="gen"><b>{L_PUBLIC_REASON}:</b></span>
	<p align="left" style="margin: 0px 0px 0px 10px;">
	<span class="gen">{PUBLIC_REASON}</span>
	</p>
	</p>
	<br /><br /><center><a href="{U_BANLIST}" class="genmed">{L_CLOSE_WINDOW}</a></center><br /><br />
	</td>
</tr>
</table>