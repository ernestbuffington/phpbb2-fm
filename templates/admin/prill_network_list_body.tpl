{USERCOM_MENU}{PRILL_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_NETWORK_TITLE}</h1>

<p>{L_NETWORK_TEXT}</p>

<table width="100%" cellspacing="2" cellpadding="2" align="center"><form method="post" action="{S_FORM_ACTION}">
<tr>
	<td align="right">{S_HIDDEN_FIELDS}<input type="submit" name="add" value="{L_NETWORK_ADD}" class="liteoption" />&nbsp;&nbsp;<input type="submit" name="autodetect" value="{L_NETWORK_AUTODETECT}" class="liteoption" /></td>
</tr>
</table>
<table width="100%" cellspacing="1" cellpadding="4" align="center" class="forumline">
<tr>
	<th class="thCornerL">&nbsp;{L_SITENAME}&nbsp;</th>
	<th class="thTop">&nbsp;{L_URL}&nbsp;</th>
	<th class="thTop">&nbsp;{L_EXT}&nbsp;</th>
	<th class="thTop">&nbsp;{L_ENABLED}&nbsp;</th>
	<th class="thCornerR" width="15%">&nbsp;{L_ACTION}&nbsp;</th>
</tr>
<!-- BEGIN sites -->
<tr>
	<td class="{sites.ROW_CLASS}">{sites.NAME}</td>
	<td class="{sites.ROW_CLASS}"><a href="{sites.URL}" target="_blank">{sites.URL}</a></td>
	<td class="{sites.ROW_CLASS}">{sites.EXT}</td>
	<td class="{sites.ROW_CLASS}" align="center">{sites.ENABLED}</td>
	<td class="{sites.ROW_CLASS}" align="right"><a href="{sites.U_SITE_EDIT}">{L_EDIT}</a> <a href="{sites.U_SITE_DELETE}">{L_DELETE}</a></td>
</tr>
<!-- END sites -->
</form></table>
<br />
<div align="center" class="copyright">Prillian 0.7.0 Beta &copy; 2003, {COPYRIGHT_YEAR} <a href="http://darkmods.sourceforge.net" class="copyright" target="_blank">Thoul</a></div>
