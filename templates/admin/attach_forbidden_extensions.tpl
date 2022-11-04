{ATTACH_MENU}{POST_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_EXTENSIONS_TITLE}</h1>

<p>{L_EXTENSIONS_EXPLAIN}</p>

{ERROR_BOX}

  <table width="100%" align="center" cellpadding="4" cellspacing="1" class="forumline"><form method="post" action="{S_ATTACH_ACTION}">
	<tr> 
	  <td class="catHead" colspan="5" align="center" height="28"><span class="cattitle">{L_EXTENSIONS_TITLE}</span> 
	  </td>
	</tr>
	<tr> 
	  <th class="thLeft">&nbsp;{L_EXTENSION}&nbsp;</th>
	  <th class="thRight">&nbsp;{L_ADD_NEW}&nbsp;</th>
	</tr>
	<tr>
		<td class="row1" align="center" valign="middle"><input type="text" size="8" maxlength="15" name="add_extension" class="post" value=""/></td>
		<td class="row2" align="center" valign="middle"><input type="checkbox" name="add_extension_check" /></td>
	</tr>
	<tr>
	  <td align="right" class="catBottom" colspan="5">{S_HIDDEN_FIELDS}<input type="submit" name="submit" class="liteoption" value="{L_SUBMIT}" /></td>
	</tr>
	<tr> 
	  <th class="thLeft">&nbsp;{L_EXTENSION}&nbsp;</th>
	  <th class="thRight">&nbsp;{L_DELETE}&nbsp;</th>
	</tr>
<!-- BEGIN extensionrow -->
	<tr> 
	  <td class="row1" align="center" valign="middle"><span class="postdetails">{extensionrow.EXTENSION_NAME}</span></td>
	  <td class="row2" align="center" valign="middle"><input type="checkbox" name="extension_id_list[]" value="{extensionrow.EXTENSION_ID}" /></td>
	</tr>
<!-- END extensionrow -->
	<tr>
	  <td align="right" class="catBottom" colspan="5"><input type="submit" name="submit" class="liteoption" value="{L_SUBMIT}" /></td>
	</tr>
</form></table>
