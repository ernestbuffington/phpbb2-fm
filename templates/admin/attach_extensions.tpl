{ATTACH_MENU}{POST_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_EXTENSIONS_TITLE}</h1>

<p>{L_EXTENSIONS_EXPLAIN}</p>

{ERROR_BOX}

  <table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form method="post" action="{S_ATTACH_ACTION}">
	<tr> 
	  <td class="catHead" colspan="5" align="center"><span class="cattitle">{L_EXTENSIONS_TITLE}</span> 
	  </td>
	</tr>
	<tr> 
	  <th class="thLeft">&nbsp;{L_EXPLANATION}&nbsp;</th>
	  <th>&nbsp;{L_EXTENSION}&nbsp;</th>
	  <th>&nbsp;{L_EXTENSION_GROUP}&nbsp;</th>
	  <th class="thRight">&nbsp;{L_ADD_NEW}&nbsp;</th>
	</tr>
	<tr>
	  <td class="row1" align="center" valign="middle"><input type="text" size="30" maxlength="100" name="add_extension_explain" class="post" value="{ADD_EXTENSION_EXPLAIN}" /></td>
	  <td class="row2" align="center" valign="middle"><input type="text" size="20" maxlength="100" name="add_extension" class="post" value="{ADD_EXTENSION}" /></td>
	  <td class="row1" align="center" valign="middle">{S_ADD_GROUP_SELECT}</td>
	  <td class="row2" align="center" valign="middle"><input type="checkbox" name="add_extension_check" /></td>
	</tr>
	<tr align="right"> 
	  <td class="catBottom" colspan="5" height="29"> {S_HIDDEN_FIELDS} <input type="submit" name="submit" class="liteoption" value="{L_SUBMIT}" /></td>
    </tr>
	<tr> 
	  <th class="thLeft">&nbsp;{L_EXPLANATION}&nbsp;</th>
	  <th>&nbsp;{L_EXTENSION}&nbsp;</th>
	  <th>&nbsp;{L_EXTENSION_GROUP}&nbsp;</th>
	  <th class="thRight">&nbsp;{L_DELETE}&nbsp;</th>
	</tr>
<!-- BEGIN extension_row -->
	<tr> 
	  <input type="hidden" name="extension_change_list[]" value="{extension_row.EXT_ID}" />
	  <td class="row1" align="center" valign="middle"><input type="text" size="30" maxlength="100" name="extension_explain_list[]" class="post" value="{extension_row.EXTENSION_EXPLAIN}" /></td>
	  <td class="row2" align="center" valign="middle"><b><span class="gen">{extension_row.EXTENSION}</span></b></td>
	  <td class="row1" align="center" valign="middle">{extension_row.S_GROUP_SELECT}</td>
	  <td class="row2" align="center" valign="middle"><input type="checkbox" name="extension_id_list[]" value="{extension_row.EXT_ID}" /></td>
	</tr>
<!-- END extension_row -->
	<tr> 
	  <td align="right" class="catBottom" colspan="5"><input type="submit" name="{L_CANCEL}" class="liteoption" value="{L_CANCEL}" onClick="self.location.href='{S_CANCEL_ACTION}'" />&nbsp;&nbsp;<input type="submit" name="submit" class="mainoption" value="{L_SUBMIT}" /></td>
	</tr>
</form></table>
