{BOOKIE_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{NEW_HEADER}</h1>

<p>{NEW_EXPLAIN}</p>

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form action="{URL}" method="post">
<tr>
	<th class="thHead" colspan="2">{SELECTION}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{TEMPL_NAME_INPUT}:</b><br /><span class="gensmall">{TEMPL_NAME_INPUT_EXP}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="25" size="12" name="templ_name" /></td>
</tr>
<!-- BEGIN templ_selections -->
<tr>
	<td class="{templ_selections.ROW_CLASS}" colspan="2"><b>{templ_selections.NUMBER}</b> <input class="post" type="text" maxlength="100" size="40" name="{templ_selections.SELECTION_NAME}" /></td>
</tr>
<!-- END templ_selections -->
<tr>
	<td class="catbottom" align="center" colspan="2"><input type="submit" name="build_templ" value="{L_SUBMIT}" class="mainoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Forum Bookmakers {BOOKIE_VERSION} &copy 2004, {COPYRIGHT_YEAR} <a href="http://www.majormod.com" class="copyright" target="_blank">Majorflam</a></div>
