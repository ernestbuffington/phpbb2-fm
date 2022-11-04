{DOWNLOAD_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_FIELD_TITLE}</h1>

<p>{L_FIELD_EXPLAIN}</p>

<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center"><form action="{S_FIELD_ACTION}" method="post">
  <tr>
	<th colspan="2" class="thHead">{L_FIELD_TITLE}</th>
  </tr>
<!-- BEGIN field_row -->
	<tr>
	 <td width="3%" class="row1" align="center" valign="middle"><input type="radio" name="field_id" value="{field_row.FIELD_ID}" /></td>
	 <td width="97%" class="row1"><b>{field_row.FIELD_NAME}</b><br /><span class="gensmall">{field_row.FIELD_DESC}</span></td></tr>
<!-- END field_row -->
 	<td align="center" class="catBottom" colspan="2">{S_HIDDEN_FIELDS}<input class="mainoption" type="submit" value="{L_FIELD_TITLE}" name="submit" /></td>
  </tr>
</form></table>
<br />
<div align="center" class="copyright">paFileDB phpBB Integration 0.0.9d &amp; <a href="http://www.mx-system.com/" target="_blank" class="copyright">MX Addon 1.2</a> &copy; 2003, {COPYRIGHT_YEAR} Mohd & Haplo</a></div>
