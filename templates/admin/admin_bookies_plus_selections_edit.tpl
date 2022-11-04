{BOOKIE_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{HEADER}</h1>

<p>{HEADER_EXPLAIN}</p>

<table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form action="{URL}" method="post">
<tr>
	<th class="thHead" colspan="2">{SELECTION}</th>
</tr>
<tr>
	<td class="row1" width="50%"><b>{TEMPL_NAME_INPUT}:</b><br /><span class="gensmall">{TEMPL_NAME_INPUT_EXP}</span></td>
	<td class="row2"><input class="post" type="text" maxlength="25" size="11" name="templ_name" value="{THIS_TEMPL_NAME}" /></td>
</tr>
<!-- BEGIN templ_selections -->
<tr>
	<td class="{templ_selections.ROW_CLASS}" align="center" colspan="2"><textarea name="{templ_selections.SELECTION_NAME}" rows="5" cols="35" class="post" />{templ_selections.SELECTION_VALUE}</textarea></td>
</tr>
<!-- END templ_selections -->
<tr>
	<td class="catBottom" align="center" colspan="2"><input type="submit" name="build_templ" value="{SUBMIT}" class="mainoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Forum Bookmakers {BOOKIE_VERSION} &copy 2004, {COPYRIGHT_YEAR} <a href="http://www.majormod.com" class="copyright" target="_blank">Majorflam</a></div>

