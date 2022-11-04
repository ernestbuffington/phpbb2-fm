{USER_MENU}
{CUSTOM_PROFILE_MENU}
{BAN_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_AUTH_TITLE}</h1>

<h2>{L_USERNAME}: {USERNAME}</h2>

<p>{L_AUTH_EXPLAIN}</p>

<table cellspacing="1" cellpadding="4" align="center" class="forumline"><form method="post" action="{S_AUTH_ACTION}">
<tr>
	<th width="30%" class="thCornerL">&nbsp;{L_FIELD_NAME}&nbsp;</th>
	<th class="thTop">&nbsp;{L_ALLOW}&nbsp;</th>
	<th class="thTop">&nbsp;{L_DEFAULT}&nbsp;</th>
	<th class="thCornerR">&nbsp;{L_DENY}&nbsp;</th>
</tr>
<!-- BEGIN xdata -->
<tr>
	<td class="row1">{xdata.NAME}</td>
	<td class="row1" align="center"><input name="xd_{xdata.CODE_NAME}" value="{AUTH_ALLOW}" type="radio" {xdata.ALLOW_CHECKED}/></td>
	<td class="row2" align="center"><input name="xd_{xdata.CODE_NAME}" value="{AUTH_DEFAULT}" type="radio" {xdata.DEFAULT_CHECKED}/></td>
	<td class="row3" align="center"><input name="xd_{xdata.CODE_NAME}" value="{AUTH_DENY}" type="radio" {xdata.DENY_CHECKED}/></td>
</tr>
<!-- END xdata -->
<tr>
	<td colspan="4" class="catBottom" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" name="reset" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Custom Profiles 0.1.1 &copy; 2003, {COPYRIGHT_YEAR} zayin</div>
