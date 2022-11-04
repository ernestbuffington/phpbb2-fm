{KB_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_EDIT_TITLE}</h1>

<p>{L_EDIT_DESCRIPTION}</p>

<table align="center" width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center"><form action="{S_ACTION}" method="post">
<tr> 
	<th class="thHead" colspan="2">{L_CAT_SETTINGS}</th>
</tr>
<tr> 
	<td class="row2" colspan="2"><span class="gensmall">{L_ITEMS_REQUIRED}</span></dh>
</tr>
<tr> 
	<td class="row1" width="38%"><b>{L_CATEGORY}: *</b></td>
	<td class="row2"><input class="post" type="text" size="25" maxlength="255" name="catname" value="{CAT_NAME}" class="post" /></td>
</tr>
<!-- BEGIN switch_cat -->
<tr> 
	<td class="row1"><b>{L_DESCRIPTION}:</b></td>
	<td class="row2"><textarea rows="5" cols="35" maxlength="255" wrap="virtual" name="catdesc" class="post">{CAT_DESCRIPTION}</textarea></td>
</tr>
<tr>
	<td class="row1"><b>{L_PARENT}:</b></td>
	<td class="row2"><select name="parent">
		<option value="0">{L_NONE}</otpion>
		{PARENT_LIST}
	</select></td>
</tr>
<!-- BEGIN switch_edit_category -->
<tr> 
	<td class="row1"><b>{L_NUMBER_ARTICLES}:</b></td>
	<td class="row2"><input class="post" type="text" size="5" maxlength="5" name="number_articles" value="{NUMBER_ARTICLES}" class="post" /></td>
</tr>
<!-- END switch_edit_category -->
<!-- END switch_cat -->
<tr> 
	<td class="catBottom" colspan="2" align="center">{S_HIDDEN}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" /></td>
</tr>
</form></table>
<br />
<div align="center" class="copyright">Knowledge Base 0.7.6 Beta &copy; 2003, {COPYRIGHT_YEAR} <a href="http://eric.best-1.biz/" class="copyright" target="_blank">wGEric</a></div>
