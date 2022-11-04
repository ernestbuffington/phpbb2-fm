{LOG_MENU}{UTILS_MENU}{DB_MENU}{LANG_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_TITLE}</h1>

<p>{L_TITLE_EXPLAIN}</p>

<table width="100%" cellspacing="2" cellpadding="2" align="center"><form method="post" action="{S_MODE_DELETE}">
<tr>
	<td><input type="submit" name="delete" class="liteoption" value="{L_DELETE_ALL}"></td>	
</tr>
</form></table>
<table width="100%" class="forumline" cellspacing="1" cellpadding="4" align="center">
<tr>
	<th class="thCornerL" nowrap>&nbsp;{L_ADMIN}&nbsp;</td>
	<th class="thTop" nowrap>&nbsp;{L_USER}&nbsp;</td>
	<th class="thTop" nowrap>&nbsp;{L_DATE}&nbsp;</td>
	<th class="thTop" nowrap>&nbsp;{L_ACTION}&nbsp;</td>
	<th class="thCornerR" nowrap>&nbsp;{L_AMOUNT}&nbsp;</td>
</tr>
<!-- BEGIN log -->
<tr>
	<td class="{log.ROW_COLOR}" align="center">{log.ADMIN}</td>
	<td class="{log.ROW_COLOR}" align="center">{log.USER}</td>
	<td class="{log.ROW_COLOR}" align="center">{log.DATE}</td>
	<td class="{log.ROW_COLOR}" align="center">{log.ACTION}</td>
	<td class="{log.ROW_COLOR}" align="center">{log.AMOUNT}</td>
</tr>
<!-- END log -->
</table>
<table width="100%" cellspacing="2" cellpadding="2" align="center">
<tr>
	<td class="nav" valign="middle">{PAGE_NUMBER}</td>
	<td class="nav" valign="middle" nowrap="nowrap" align="right">{PAGINATION}</td>
</table>
<br />
<div align="center" class="copyright">Admin Points Tracker 1.0.0 &copy; 2004, {COPYRIGHT_YEAR} <a href="mailto:austin_inc@hotmail.com" class="copyright" target="_blank">aUsTiN</a></div>
