{DOWNLOAD_MENU}
</ul>
</div></td>
<td valign="top" width="78%">

<h1>{L_ELICENSETITLE}</h1>

<p>{L_LICENSEEXPLAIN}</p>

<!-- BEGIN license_form -->
<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center"><form action="{S_EDIT_LIC_ACTION}" method="post">
  <tr>
	<th colspan="2" class="thHead">{L_ELICENSETITLE}</b></th>
  </tr>
  <tr>
	<td width="50%" class="row1"><b>{L_LNAME}:</b></td>
	<td class="row2"><input type="text" class="post" size="35" name="form[name]" value="{LICENSE_NAME}" /></td>
  </tr>
  <tr>
	<td class="row1"><b>{L_LTEXT}:</b></td>
	<td class="row2"><textarea name="form[text]" cols="35" rows="10" class="post">{TEXT}</textarea></td>
  </tr>
  <tr>
	<td align="center" class="catBottom" colspan="2"><input class="liteoption" type="submit" value="{L_ELICENSETITLE}" name="B1"><input type="hidden" name="action" value="admin"><input type="hidden" name="ad" value="license"><input type="hidden" name="license" value="edit"><input type="hidden" name="edit" value="do"><input type="hidden" name="id" value="{SELECT}"></td>
  </tr>
</form></table>	
<br />
<!-- END license_form -->

<!-- BEGIN license -->
<table width="100%" cellpadding="4" cellspacing="1" class="forumline" align="center"><form action="{S_EDIT_LIC_ACTION}" method="post">
  <tr>
	<th colspan="2" class="thHead">{L_ELICENSETITLE}</th>
  </tr>
  {ROW}
  <tr>
	<td align="center" class="catBottom" colspan="2"><input class="liteoption" type="submit" value="{L_ELICENSETITLE}" name="B1"><input type="hidden" name="action" value="admin"><input type="hidden" name="ad" value="license"><input type="hidden" name="license" value="edit"><input type="hidden" name="edit" value="form"></td>
  </tr>
</form></table>
<!-- END license -->
<br />
<div align="center" class="copyright">paFileDB phpBB Integration 0.0.9d &amp; <a href="http://www.mx-system.com/" target="_blank" class="copyright">MX Addon 1.2</a> &copy; 2003, {COPYRIGHT_YEAR} Mohd & Haplo</a></div>
