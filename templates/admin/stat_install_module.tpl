{STATS_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_INSTALL_MODULE}</h1>

<p>{L_INSTALL_MODULE_EXPLAIN}</p>

<!-- BEGIN switch_select_module -->
<table class="forumline" cellspacing="1" cellpadding="4" align="center" width="50%"><form method="post" action="{S_ACTION}">
	<tr> 
	  <th class="thHead">{L_SELECT_MODULE}</th>
	</tr>
	<tr>
		<td class="row1" align="center">{S_SELECT_MODULE}</td>
	</tr>
	<tr>
		<td class="catBottom" colspan="1" align="center">{S_SELECT_HIDDEN_FIELDS}{S_UPLOAD_HIDDEN_FIELDS}<input class="mainoption" name="submit" type="submit" value="{L_SUBMIT}" /></td>
	</tr>
</form></table>
<br />
<!-- END switch_select_module -->

<!-- BEGIN switch_upload_module -->
<table class="forumline" cellspacing="1" cellpadding="4" align="center" width="50%"><form method="post" action="{S_ACTION}" enctype="multipart/form-data">
	<tr> 
	  <th class="thHead">{L_UPLOAD_MODULE}</th>
	</tr>
	<tr>
		<td class="row1" align="center"><input type="file" name="package" size="20" value="" class="post" /></td>
	</tr>
	<tr>
		<td class="catBottom" colspan="1" align="center">{S_UPLOAD_HIDDEN_FIELDS}<input class="mainoption" name="submit" type="submit" value="{L_SUBMIT}" /></td>
	</tr>
</form></table>
<br />
<!-- END switch_upload_module -->

<!-- BEGIN switch_install_module -->
<table class="forumline" cellspacing="1" cellpadding="4" align="center" width="100%"><form method="post" action="{S_ACTION}">
	<tr> 
	  <th class="thHead" colspan="2">{L_INSTALL_MODULE}</th>
	</tr>
	<tr>
		<td class="row1" width="50%"><b>{L_MODULE_NAME}:</span></td>
		<td class="row2">{MODULE_NAME}</span></td>
	</tr>
	<tr>
		<td class="row1"><b>{L_MODULE_DESCRIPTION}:</b></td>
		<td class="row2">{MODULE_DESCRIPTION}</td>
	</tr>
	<tr>
		<td class="row1"><b>{L_MODULE_VERSION}:</b></td>
		<td class="row2">{MODULE_VERSION}</td>
	</tr>
	<tr>
		<td class="row1"><b>{L_MODULE_AUTHOR}:</b></td>
		<td class="row2">{MODULE_AUTHOR}</td>
	</tr>
	<tr>
		<td class="row1"><b>{L_AUTHOR_EMAIL}:</b></td>
		<td class="row2"><a href="mailto:{AUTHOR_EMAIL}" target="_blank">{AUTHOR_EMAIL}</a></td>
	</tr>
	<tr>
		<td class="row1"><b>{L_MODULE_URL}:</b></td>
		<td class="row2"><a href="{MODULE_URL}" target="_blank">{MODULE_URL}</a></td>
	</tr>
	<tr>
		<td class="row1"><b>{L_UPDATE_URL}:</b></td>
		<td class="row2"><a href="{UPDATE_URL}" target="_blank">{UPDATE_URL}</a></td>
	</tr>
	<tr> 
	  <th class="thCornerL">{L_PROVIDED_LANGUAGE}:</th>
	  <th class="thCornerR">{L_INSTALL_LANGUAGE}</th>
	</tr>
<!-- END switch_install_module -->
<!-- BEGIN languages -->
	<tr>
		<td class="row1"><b>{languages.MODULE_LANGUAGE}:</b></td>
		<td class="row2"><input type="checkbox" name="checked_languages[]" value="{languages.MODULE_LANGUAGE}" checked="checked"></td>
	</tr>
<!-- END languages -->
<!-- BEGIN switch_install_module -->	
	<tr>
		<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input class="mainoption" name="submit" type="submit" value="{L_INSTALL}" /></td>
	</tr>
</table></form>
<!-- END switch_install_module -->
