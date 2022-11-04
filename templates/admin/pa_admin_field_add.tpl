{DOWNLOAD_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_FIELD_TITLE}</h1>

<p>{L_FIELD_EXPLAIN}</p>

<!-- IF ERROR neq '' -->
<table width="100%" cellpadding="2" cellspacing="2" align="center">
  <tr>
	<td align="center">{ERROR}</td>
  </tr>
</table>
<!-- ENDIF -->

<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center"><form action="{S_FIELD_ACTION}" method="post">
  <tr>
	<th colspan="2" class="thHead">{L_FIELD_TITLE}</th>
  </tr>
  <tr>	
	<td width="50%" class="row1"><b>{L_FIELD_NAME}:</b><br /><span class="gensmall">{L_FIELD_NAME_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="35" name="field_name" value="{FIELD_NAME}" /></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_FIELD_DESC}:</b><br /><span class="gensmall">{L_FIELD_DESC_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="35" name="field_desc" value="{FIELD_DESC}" /></td>
  </tr>
<!-- IF DATA -->
  <tr>
	<td class="row1"><b>{L_FIELD_DATA}:</b><br /><span class="gensmall">{L_FIELD_DATA_INFO}</span></td>
	<td class="row2"><textarea rows="6" name="data" cols="35" rows="5">{FIELD_DATA}</textarea></td>
  </tr>
<!-- ENDIF -->

<!-- IF REGEX -->
  <tr>
	<td class="row1"><b>{L_FIELD_REGEX}:</b><br /><span class="gensmall">{L_FIELD_REGEX_INFO}</span></td>
	<td class="row2"><input type="text" class="post" size="35" name="regex" value="{FIELD_REGEX}" /></td>
  </tr>
<!-- ENDIF -->

<!-- IF ORDER -->
  <tr>	
	<td class="row1"><b>{L_FIELD_ORDER}:</b></td>
	<td class="row2"><input type="text" class="post" size="6" name="field_order" value="{FIELD_ORDER}" /></td>
  </tr>
<!-- ENDIF -->
  <tr>
	<td align="center" class="catBottom" colspan="2">{S_HIDDEN_FIELDS}<input class="mainoption" type="submit" value="{L_FIELD_TITLE}" name="submit" /></td>
  </tr>
</form></table>
<br />
<div align="center" class="copyright">paFileDB phpBB Integration 0.0.9d &amp; <a href="http://www.mx-system.com/" target="_blank" class="copyright">MX Addon 1.2</a> &copy; 2003, {COPYRIGHT_YEAR} Mohd & Haplo</a></div>
