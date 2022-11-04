{STATS_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_IMPORT_LANGUAGE}</h1>

<p>{L_IMPORT_LANGUAGE_EXPLAIN}</p>

<!-- BEGIN switch_select_lang -->

<table class="forumline" cellspacing="1" cellpadding="4" align="center" width="50%"><form method="post" action="{S_ACTION}">
	<tr> 
	  <th class="thHead">{L_SELECT_LANGUAGE}</th>
	</tr>
	<tr>
		<td class="row1" align="center">{S_SELECT_LANGUAGE}</td>
	</tr>
	<tr>
		<td class="catBottom" colspan="1" align="center">{S_SELECT_HIDDEN_FIELDS}
		<input class="mainoption" name="submit" type="submit" value="{L_SUBMIT}" /></td>
	</tr>
</form></table>
<!-- END switch_select_lang -->

<!-- BEGIN switch_upload_lang -->
<table class="forumline" cellspacing="1" cellpadding="4" align="center" width="50%"><form method="post" action="{S_ACTION}" enctype="multipart/form-data">
	<tr> 
	  <th class="thHead">{L_UPLOAD_LANGUAGE}</th>
	</tr>
	<tr>
		<td class="row1" align="center"><input type="file" name="package" size="20" value="" class="post" /></td>
	</tr>
	<tr>
		<td class="catBottom" colspan="1" align="center">{S_UPLOAD_HIDDEN_FIELDS}
		<input class="mainoption" name="submit" type="submit" value="{L_SUBMIT}" /></td>
	</tr>
</form></table>
<!-- END switch_upload_lang -->

<!-- BEGIN switch_install_language -->
<table class="forumline" cellspacing="1" cellpadding="4" align="center" width="100%"><form method="post" action="{S_ACTION}">
	<tr> 
	  <th class="thHead" colspan="2">{L_INSTALL_LANGUAGE}</th>
	</tr>
	<tr> 
	  <td class="catLeft" align="center" width="10%"><span class="cattitle">&nbsp;{L_LANGUAGE}&nbsp;</span></td>
	  <td class="catRight" align="center"><span class="cattitle">&nbsp;{L_MODULES}&nbsp;</span></td>
	</tr>
<!-- END switch_install_language -->
<!-- BEGIN languages -->
	<tr>
		<td class="row1" align="left" colspan="2"><span class="gen">{languages.LANGUAGE}</span></td>
	</tr>
<!-- BEGIN modules -->
	<tr>
		<td class="row2" align="center" colspan="2"><span class="gen">{languages.modules.MODULE}</span></td>
	</tr>
<!-- END modules -->
<!-- END languages -->
<!-- BEGIN switch_install_language -->	
	<tr>
		<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}
		<input class="mainoption" name="submit" type="submit" value="{L_INSTALL}" /></td>
	</tr>
</form></table>
<!-- END switch_install_language -->
