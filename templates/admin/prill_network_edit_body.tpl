{USERCOM_MENU}{PRILL_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_NETWORK_TITLE}</h1>

<p>{L_NETWORK_TEXT}</p>

<table cellspacing="1" cellpadding="4" align="center" class="forumline" width="100%"><form method="post" action="{S_FORM_ACTION}">
<tr>
	<th class="thHead" colspan="2">{L_SITE_CONFIG}</th>
</tr>
<tr>
	<td class="row2" colspan="2"><span class="gensmall">{L_REQUIRED}</span></td>
</tr>
<tr>
	<td class="row1" width="50%"><b>{L_SITENAME}: *</b></td>
	<td class="row2"><input class="post" type="text" name="site_name" value="{NAME}" size="35" maxlength="50" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_URL}: *</b></td>
	<td class="row2"><input class="post" type="text" name="site_url" value="{URL}" size="35" maxlength="100" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_EXT}:</b><br /><span class="gensmall">{L_EXT_EXPLAIN}</span></td>
	<td class="row2"><input class="post" size="10" type="text" name="site_phpex" value="{EXT}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_PROFILE_PATH}:</b><br /><span class="gensmall">{L_PROFILE_PATH_EXPLAIN}</span></td>
	<td class="row2"><input class="post" size="20" type="text" name="site_profile" value="{PROFILE_PATH}" /></td>
</tr>
<tr>
	<td class="row1"><b>{L_ENABLED}:</b><br /><span class="gensmall">{L_ENABLED_EXPLAIN}</span></td>
	<td class="row2"><input type="radio" name="site_enable" value="1" {ENABLED_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="site_enable" value="0" {ENABLED_NO} /> {L_NO}</td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input class="mainoption" type="submit" value="{L_SUBMIT}" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Prillian 0.7.0 Beta &copy; 2003, {COPYRIGHT_YEAR} <a href="http://darkmods.sourceforge.net" class="copyright" target="_blank">Thoul</a></div>
