{USERCOM_MENU}{EMAIL_MENU}{DIGESTS_MENU}
{MOD_CP_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_TITLE}</h1>

<p>{L_EXPLAIN}</p>

<table width="100%" class="forumline" cellspacing="1" cellpadding="4" align="center"><form method="post" name="search" action="{S_SEARCH_ACTION}">
<tr>
	<th colspan="2" class="thHead">&nbsp;{L_TITLE}&nbsp;</th>
</tr>
<tr> 
	<td class="row1" width="38%"><b>{L_EMAIL}:</b></td>
	<td class="row2"><input type="text" name="search_username" size="35" maxlength="255" value="{USERNAME}" class="post" /></td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center"><input type="submit" name="search" value="{L_SEARCH}" class="mainoption" /></td>
</tr>
</form></table>

<!-- BEGIN switch_select_name -->
<br />
<table width="100%" class="forumline" cellspacing="1" cellpadding="4" align="center">
<tr>
	<th class="thCornerL">&nbsp;{L_USERNAME}&nbsp;</th>
	<th class="thTop">&nbsp;{L_EMAIL}&nbsp;</th>
	<th class="thCornerR" width="15%">&nbsp;{L_ACTION}&nbsp;</th>
</tr>
{S_USERNAME_OPTIONS}
</table>
<!-- END switch_select_name -->
<br />
<div align="center" class="copyright">phpBB E-mail Search 1.0.0 &copy; 2004, {COPYRIGHT_YEAR} <a href="http://www.able2know.com" target="_blank" class="copyright">Craven de Kere</a></div>
