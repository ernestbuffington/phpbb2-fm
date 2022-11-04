{FORUM_MENU}{VOTE_MENU}
{MOD_CP_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<script language="javascript" type="text/javascript">
<!--
function update_icon(newimage)
{
	document.icon_image.src = "{S_ICON_BASEDIR}/" + newimage;
}
//-->
</script>

<h1>{L_EDIT_CATEGORY}</h1>

<p>{L_EDIT_CATEGORY_EXPLAIN}</p>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center"><form action="{S_FORUM_ACTION}" method="post">
	<tr> 
	  <th class="thHead" colspan="2">{L_EDIT_CATEGORY}</th>
	</tr>
	<tr> 
	  <td class="row1" width="50%"><b>{L_CATEGORY}:</b></td>
	  <td class="row2"><input class="post" type="text" size="35" name="cat_title" value="{CAT_TITLE}" /></td>
	</tr>
	<tr> 
	   <td class="row1"><b>{L_CATEGORY_ICON}:</b><br /><span class="gensmall">{L_PHPBB_ROOT_DIR}</span></td>
	   <td class="row2"><select name="cat_icon" onchange="update_icon(this.options[selectedIndex].value);">{S_FILENAME_OPTIONS}</select> &nbsp; <img name="icon_image" src="{CAT_ICON}" alt="" title="" /> &nbsp;</td>
	</tr>
	<tr> 
	  <td class="row1"><b>{L_PARENT_FORUM_ID}:</b></td>
	  <td class="row2">{PARENT_FORUM_ID}</td>
	</tr>
      <tr> 
	  <td class="row1"><b>{L_CAT_SPONSOR_IMG}:</b></td>
	  <td class="row2"><input class="post" type="text" size="35" name="cat_sponsor_img" value="{CAT_SPONSOR_IMG}" /></td>
	</tr>
      <tr> 
	  <td class="row1"><b>{L_CAT_SPONSOR_ALT}:</b></td>
	  <td class="row2"><input class="post" type="text" size="35" name="cat_sponsor_alt" value="{CAT_SPONSOR_ALT}" /></td>
	</tr>
	<tr> 
	  <td class="row1"><b>{L_CAT_SPONSOR_URL}:</b></td>
	  <td class="row2"><input class="post" type="text" size="35" name="cat_sponsor_url" value="{CAT_SPONSOR_URL}" /></td>
	</tr>
	<tr> 
	  <td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{S_SUBMIT_VALUE}" class="mainoption" /></td>
	</tr>
</form></table>	
